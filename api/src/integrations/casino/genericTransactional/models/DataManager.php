<?php
namespace Backend\integrations\casino\genericTransactional\models;

use Backend\dto\ConfigurationEnvironment;
use DateTime;
use DateTimeZone;
use Exception;
use Throwable;

/**
 * Handles the processing and customization of transactional method parameters.
 * 
 * - Adjusts parameters for each transactional method.
 * - Manages provider-specific bonuses and free spins by setting the bet amount to 0 when applicable.
 * - Extracts values from objects, regardless of their depth in the data structure.
 * - Customizes a variable by combining one or more values (e.g., transactionId, sessionId, roundId).
 * - Formats numbers flexibly, allowing multiplication, division, rounding to a specified number of decimals,
 *   or formatting as an integer or float.
 * - Manages data serialization and deserialization according to the provider's required format.
 * - Constructs both error and success responses.
 * - Sorts an array based on a reference array.
 * - Merges data received from both the request header and body, returning a unified JSON structure.
 * 
 * @category API
 * @package casino\genericTransactional\models\
 * @author Esteban Arévalo
 * @version 1.0
 * @since 4/3/2025
 */
class DataManager {

    /**
     * $nameRequestAction Store the name of the method that comes from the URL.
     * @var string
     */
    public static $nameRequestAction = "";

    /**
     * Maps the values of an associative array onto another array using an object as the source of data.
     * This function is commonly used for parameter mapping in transactional methods or formatting structured responses.
     *
     * @param array &$arrayToMap The associative array containing the mapping rules. 
     *        Each key can define the following properties:
     *        - 'key': The property name in the object to extract the value from.
     *        - 'keys': An array of property names for deep extraction.
     *        - 'formatConfiguration': A set of rules for formatting numeric values.
     *        - 'customizeValue': A customization rule to modify the extracted value.
     *        - 'value': A default value used if the property does not exist in the object.
     *        - 'isArray': Boolean indicating whether the key should represent an array structure.
     *        - 'bonusGame': Configuration for handling provider-specific bonus bets.
     *        - 'freeSpin': Configuration for handling free spins.
     * @param object &$data The object containing the source data for mapping. 
     *        The properties of this object are matched with the keys in `$arrayToMap`.
     * @return array Returns the newly mapped associative array with the extracted and formatted data.
     * @throws Exception If a property defined in the mapping rules ('key') does not exist 
     *         in the object `$data` and no default 'value' is provided.
     */
    public function mappingDataOnArray(array &$arrayToMap, object &$data) :array {
        try {
            $mappedArray = [];
            $keysForValidate = [
                'formatConfiguration',
                'allowCustomization',
                'dateCustomization',
                'conditionalValue',
                'customizeValue',
                'extractValue',
                'bonusGame',
                'freeSpin',
                'isArray',
                'value',
                'keys',
                'key'
            ];
            
            foreach ($arrayToMap as $key => $value){
                if (array_intersect(array_keys($value), $keysForValidate)){
                    $formatConfiguration = $value['formatConfiguration'] ?? null;
                    $customizeValue = $value['customizeValue'] ?? null;
                    $isArray = $value['isArray'] ?? false;
                    $jsonValue = $value['value'] ?? null;
                    $requestKey = $value['key'] ?? null;
                    $deepRequestKeys = $value['keys'] ?? null;
                    $allowCustomization = $value['allowCustomization'] ?? null;

                    if ($isArray === true){
                        $mappedArray[$key] = [];
                        continue;
                    }

                    // Supplier's own bonds, extracts the value of the request and if it is True establishes the amount in 0
                    $bonusGame = $value['bonusGame'] ?? null;
                    if (isset($bonusGame) && is_array($bonusGame)){
                        if (self::handleProviderBonusBet($bonusGame, $data, $mappedArray, $key, $jsonValue) === true) continue;
                    } 
                    
                    // Handle freeSpins, extracts the value of the request and if it is True establishes the amount in 0
                    $freeSpin = $value['freeSpin'] ?? null;
                    if (isset($freeSpin) && is_array($freeSpin) || !empty($deepRequestKeys)){
                        if (self::handledProviderBonusFreeSpin($freeSpin, $deepRequestKeys, $data, $mappedArray, $key, $jsonValue) === true) continue;
                    }
                
                    // Checks the request for the existence of multiple properties, extracts the value of the first found property, and returns it.
                    if (is_array($requestKey)) $requestKey = self::checkMultipleKeysInRequest($requestKey, $data);

                    // Verify that a property exists in the request
                    if (!property_exists($data, $requestKey) && !array_key_exists('value', $value)){
                        throw new Exception(
                            "The property '{$requestKey}' does not exist in the request and does not have a default value configured.",
                            300082
                        );
                    }

                    $dataValue = property_exists($data, $requestKey) ? $data->$requestKey : $jsonValue;

                    // Resolves the value of the property based on the configuration and the value coming from the request
                   $conditionalValue = $value['conditionalValue'] ?? null;
                    if (is_array($conditionalValue) && !empty($conditionalValue)){
                        $dataValue = self::resolveConditionalValue($conditionalValue, $dataValue, $jsonValue);
                    }

                    // Customizes the date format and timezone.
                    $dateCustomization = $value['dateCustomization'] ?? null;
                    if (!empty($dateCustomization) && is_array($dateCustomization)) $dataValue = self::customizeDate($dateCustomization);

                    // Extracts a specific part of a string using a separator and an exact position.
                    $extractValue = $value['extractValue'] ?? null;
                    if (is_array($extractValue) && !empty($extractValue)) $dataValue = self::extractSubstringByPosition($extractValue, $dataValue);
                    
                    $dataValue = !empty($allowCustomization) ? self::isCustomizationNeeded($allowCustomization, $data, $dataValue) : $dataValue;
                    $mappedArray[$key] = !empty($customizeValue) ? self::customizeVariable($customizeValue, $data) : $dataValue;
                    $mappedArray[$key] = !empty($formatConfiguration) ? self::customNumberFormat($dataValue, $formatConfiguration) : $mappedArray[$key];

                } else if (is_array($value)){
                    // Applies recursion to preserve the response structure of each provider.
                    $mappedArray[$key] = self::mappingDataOnArray($value, $data);
                }
            }
            
            return $mappedArray;

        } catch (Throwable $th) {
            throw new Exception($th->getMessage(), $th->getCode() == 0 ? 300105 : $th->getCode());
        }
    }

    /**
     * Checks if any of the configured values match the value received from the request.
     *
     * If a match is found, the configured value (`valueIfMatch`) is assigned.  
     * If no match is found, the default value (`defaultValue`) is used.
     *
     * @param array $config Configuration array with the following keys:
     *         - 'checkAgainst' (array): List of values to compare against the request value.
     *         - 'valueIfMatch' (mixed): Value to assign if a match is found.
     *         - 'defaultValue' (mixed): Value to assign if no match is found.
     * 
     * @param mixed $requestValue The value received from the request to be checked.
     * @param mixed $defaultValue The default value to assign if no match is found.*
     * @return mixed The final value assigned based on the match logic.
     * 
     * @throws Exception If the configuration is incomplete or if an error occurs during the process.
     */
    private function resolveConditionalValue(array $conditionalValue, $requestValue, $defaultValue) :mixed {
        try {
            if (!array_key_exists('checkAgainst', $conditionalValue) || !array_key_exists('valueIfMatch', $conditionalValue)){
                throw new Exception("The configuration required for the conditional value is missing or incomplete.", 300150);
            }

            ['checkAgainst' => $checkAgainst, 'valueIfMatch' => $valueIfMatch] = $conditionalValue;
            
            $finalValue = in_array($requestValue, $checkAgainst) ? $valueIfMatch : $defaultValue;
            return $finalValue;

        } catch (Throwable $th) {
            throw new Exception($th->getMessage(), $th->getCode() == 0 ? 300105 : $th->getCode());
        }
    }

    /**
     * Converts a date from the 'America/Bogota' timezone to the timezone specified
     * in the configuration parameters.
     *
     * @param array $dateConfiguration The date configuration, which includes:
     *      - 'format' (string): The desired date format.
     *      - 'timezone' (string): The timezone to which the date will be converted.
     *      - 'convertToTimestamp' (bool): Indicates whether the date should be converted to a timestamp.
     *
     * @return string The date converted to the specified format and timezone.
     * @throws Exception If the date configuration is incomplete or if an error occurs during the conversion.
     */
    private function customizeDate(array $dateConfiguration) :string {
        try {
            [
                'format' => $dateFormat,
                'timezone' => $timezone,
                'convertToTimestamp' => $convertToTimestamp
            ] = $dateConfiguration;

            if (empty($dateFormat) || empty($timezone) || !isset($convertToTimestamp)){
                throw new Exception("The configuration for the date customization is incomplete.", 300141);
            }

            $date = date($dateFormat);
            $date = new DateTime($date, new DateTimeZone('America/Bogota'));
            $date->setTimezone(new DateTimeZone($timezone));

            if ($convertToTimestamp) {
                $date = $date->getTimestamp();
            } else {
                $date = $date->format($dateFormat);
            }
            
            return $date;

        } catch (Throwable $th) {
            throw new Exception($th->getMessage(), $th->getCode() == 0 ? 300105 : $th->getCode());
        }
    }

    /**
     * Extracts a specific part of a string based on its position and a defined separator.
     *
     * This function takes a string containing multiple substrings separated by a specific 
     * character (e.g., ".", ",", "_", "*") and extracts the substring at the specified position.
     *
     * @param string $variableToExtractValue The original string containing the delimited substrings.
     * @param array $configuration The configuration for extracting the substring, including:
     *       - 'separator' (string): The character used as a delimiter between substrings.
     *       - 'position' (int): The index (zero-based) of the substring to extract.
     * @return mixed Returns the extracted value or an array specifying the validation function 
     *      for the extracted data and whether validation is required.
     * @throws Exception If the configuration is incomplete or the extraction fails.
     */
    private function extractSubstringByPosition(array $configuration, $variableToExtractValue) :mixed {
        try {
            $configurationEnvironment = new ConfigurationEnvironment();
            $configurationEnvironment->isDevelopment() ? $environment = 'isDevelopment' : $environment = 'isProduction';

            $validationRequired = $configuration['validationRequired'] ?? null;
            $isRequired = $validationRequired['isRequired'] ?? null;
            $validationType = $validationRequired['validationType'] ?? null;
            $separator = $configuration['separator'] ?? null;
            $position = $configuration[$environment]['position'] ?? null;

            if (empty($separator) || empty($position)){
                throw new Exception("The configuration for the extraction of the substring is incomplete.", 300141);
            }

            $extractedValue = explode($separator, $variableToExtractValue);
            $extractedValue = $extractedValue[$position];

            // Merge the extracted value with the validation type for later validation as specified.
            if ($isRequired == true && !empty($validationType)){
                $extractedValue = ['extractedValue' => $extractedValue, 'validationType' => $validationType];
            }

            return $extractedValue;

        } catch (Throwable $th) {
            throw new Exception($th->getMessage(), $th->getCode() == 0 ? 300105 : $th->getCode());
        } 
    }

    /**
     * Extracts the value of a property, regardless of its depth within the request object.
     * If the extracted value is `true`, it sets the 'amount' to 0 and assigns `true` to 'isFreeSpin'.
     *
     * @param array|null $freeSpin The free spin configuration, containing:
     *        - 'keys' (array): Nested properties within the request object from which the free spin status is extracted.
     *        - 'bonusValue' (mixed): The default value if the free spin status is not found or is incorrectly defined. The default value is false.
     * @param array|null $deepRequestKeys Nested properties within the request object from which the free spin status is extracted.
     * @param object &$data The request data object from which values will be extracted.
     * @param array &$mappedArray The array where the mapped values will be stored.
     * @param string $key The key being processed (e.g., 'amount', 'isFreeSpin').
     * @param mixed $jsonValue The default value to be used if no specific value is found.
     * 
     * @return bool Returns true if a free spin condition is met and processed; otherwise, false.
     */
    private function handledProviderBonusFreeSpin($freeSpin, $deepRequestKeys, object &$data, array &$mappedArray, string $key, $jsonValue) :bool {
        $freeSpin = !empty($freeSpin) ? ['keys' => $deepRequestKeys, 'bonusValue' => $bonusValue] = $freeSpin : null;

        if (!empty($deepRequestKeys)){
            $defaultValue = isset($bonusValue) ? $bonusValue : $jsonValue;
            $deepRequestValue = self::getDeepPropertyValue($data, $deepRequestKeys, $defaultValue);
            $mappedArray[$key] = $deepRequestValue == true ?: $defaultValue;

            if ($deepRequestValue == true && $key == 'amount'){
                $mappedArray[$key] = $jsonValue;
                return true;
            }

            if ($deepRequestValue == true && $key == 'isFreeSpin') return true;
        }

        return false;
    }

    /**
     * Handles the provider's bonus bet by checking if the next play is a provider-specific bonus.
     * If the bonus condition is met, the bet amount is set to 0 to avoid charging the user.
     *
     * @param array $bonusGame An array containing the configuration for the provider's bonus.
     *        Expected keys:
     *        - 'key' (string): The property name in the request that indicates the bonus state.
     *        - 'bonusValue' (mixed): If the request does not exist, this will be the default value, which is false.
     * @param object &$data The object containing request data.
     * @param array &$mappedArray The array where the mapped values are stored.
     * @param string $key The key in `$mappedArray` to store the updated value.
     * @param mixed $jsonValue The default value to set if the bonus condition is met.
     * @return bool Returns true if the play is identified as a bonus and the bet amount is set to 0, otherwise false.
     * @throws Exception If the bonus key is missing in the request and no default value is provided.
     */
    private function handleProviderBonusBet(array $bonusGame, object &$data, array &$mappedArray, string $key, $jsonValue) :bool {
        $freeGames = !empty($bonusGame) ? 
        [
            'key' => $bonusKey, 
            'bonusValue' => $bonusValue, 
            'expectedValue' => $expectedValue
        ] = $bonusGame : null;

        if (is_array($freeGames)){
            if (!property_exists($data, $bonusKey) && !array_key_exists('bonusValue', $bonusGame)){
                throw new Exception(
                    "The property '{$bonusKey}' does not exist in the request and does not have a default value configured.",
                    300082
                );
            }

            $dataValue = property_exists($data, $bonusKey) ? $data->$bonusKey : $bonusValue;
            if ($dataValue == $expectedValue){
                $mappedArray[$key] = $jsonValue;
                return true;
            }
        }

        return false;
    }

    /**
     * Checks if the properties exist in the request and returns the value of the property that does exist.
     * 
     * @param array &$requestKey &$requestKey Reference to the '$requestKey' variable that will be checked.
     * @param object &$data The object containing the request data.
     * @return string $requestKey The property that exists in the request.
     * @throws Exception If the properties do not exist in the request or are incorrectly defined.
     */
    private function checkMultipleKeysInRequest(array &$requestKey, object &$data){
        try {
            $propertyExists = array_filter($requestKey, fn($property) => property_exists($data, $property));

            if (empty($propertyExists)){
                throw new Exception(
                    "The properties '{$requestKey}' do not exist in the request or is incorrectly defined",
                    300082
                );
            }

            foreach($propertyExists as $value){
                $requestKey = $value;
            };

            return $requestKey;

        } catch (Throwable $th) {
            throw new Exception($th->getMessage(), $th->getCode() == 0 ? 300105 : $th->getCode());
        } 
    }

    /**
     * Extracts the value of a property, regardless of its depth within the request object.
     * 
     * @param object $data The object containing the request data.
     * @param array &$keys Definition of the child properties of the object from which the value will be extracted.
     * @param &$defaultValue The default value to be used if the property does not exist in the object.
     * @return mixed The value of the property extracted from the object.
     * @throws Exception If the property does not exist in the object or is incorrectly defined.
     */
    private function getDeepPropertyValue(object $data, array &$keys, &$defaultValue) :mixed {
        try {
            foreach ($keys as $key) {
                if (!isset($data->$key) && !isset($defaultValue)){
                    throw new Exception(
                        "The property '{$data->$key}' does not exist, it is poorly defined or has no default value.",
                         300106
                    );
                }
    
                if (isset($data->$key)){
                    $data = $data->$key; 
                } else {
                    return $defaultValue;
                }
            }

            return $data;

        } catch (Throwable $th) {
            throw new Exception($th->getMessage(), $th->getCode() == 0 ? 300105 : $th->getCode());
        } 
    }

    /**
     * Formats a number by converting it to either a decimal or an integer based on the specified requirement. 
     * If the result is a decimal, the number of decimal places can be customized.
     * 
     * @param mixed $value The number to be formatted.
     * @param array &$formatConfiguration The configuration for the number format, including:
     *          - 'type' (string): The function to be applied to the number (e.g., 'intval', 'floatval').
     *          - 'multiply' (int): The value by which the number should be multiplied.
     *          - 'split' (int): The value by which the number should be divided.
     *          - 'numberFormat' (array): An array containing the number format configuration, including:
     *               - 'decimals' (int): The number of decimal places to round to.
     *               - 'decimalSeparator' (string): The character used as the decimal separator.
     *               - 'thousandsSeparator' (string): The character used as the thousands separator.
     * @return mixed The formatted number.
     * @throws Exception If the function does not exist or the configuration format is incorrectly defined.
     */
    private function customNumberFormat($value, array &$formatConfiguration) :mixed {
        try {
            if (empty($formatConfiguration)){
                throw new Exception(
                    "The configuration for the format does not exist or is incorrectly defined.", 
                    300131
                );
            }
        
            $functionType = $formatConfiguration['type'] ?? null;
            $multiply = $formatConfiguration['multiply'] ?? null;
            $numberFormat = $formatConfiguration['numberFormat'] ?? null;
            $split = $formatConfiguration['split'] ?? null;
        
            // Apply rounding if specified
            if (!empty($numberFormat) && is_array($numberFormat)){
                [
                    'decimals' => $decimals,
                    'decimalSeparator' => $decimalSeparator,
                    'thousandsSeparator' => $thousandsSeparator
                ] = $numberFormat;

                if (!isset($decimals) || !isset($decimalSeparator) || !isset($thousandsSeparator)){
                    throw new Exception("The configuration for the number format is incomplete.", 300150);
                }

                $value = number_format($value, $decimals, $decimalSeparator, $thousandsSeparator);
            }
        
            $decimals = empty($decimals) ? 0 : $decimals;
        
            // Apply multiplication is specified
            $value = !empty($multiply) ? self::bcmul_fallback($value, "$multiply", $decimals) : $value *= 1;
        
            // Apply division is specified
            $value = !empty($split) ? self::bcdiv_fallback($value, "$split", $decimals) : $value;
    
            if (!empty($functionType) && !function_exists($functionType)){
                throw new Exception("The '{$functionType}' function doesn't exist.", 300083); 
            }
    
            return !empty($functionType) ? $functionType($value) : $value;

        } catch (Throwable $th) {
            throw new Exception($th->getMessage(), $th->getCode() == 0 ? 300105 : $th->getCode());
        } 
    }

    /**
     * Creates and returns an error response in the format specified by the supplier (JSON / XML).
     *
     * @param array $structureResponse The error structure to be displayed to the user.
     * @param array $contentError The content of the error (message, code, etc.).
     * @param string $dataFormat Specifies whether the returned format should be XML or JSON.
     * @return mixed $response The error response formatted according to the supplier's defined structure and format.
     * @throws Exception If the arguments provided are invalid or the data format is not supported.
     */
    public function makingResponseWithCorrespondingFormat(array &$structureResponse, array &$contentResponse, string $dataFormat) :mixed {
        try {
            if (array_key_exists('addToRootName', $contentResponse)) $addToRootName = $contentResponse['addToRootName'];
            $contentResponse = DataConvert::fromAssociativeArrayToObject($contentResponse);
            $responseMapping = self::mappingDataOnArray($structureResponse, $contentResponse);

            $isMatrix = Validate::isMatrix($responseMapping);
            
            if (!$isMatrix && $dataFormat == 'xml'){
                if (isset($addToRootName)){
                    $responseMapping = array(self::$nameRequestAction . $addToRootName => $responseMapping);
                } else {
                    $responseMapping = array(self::$nameRequestAction => $responseMapping);
                }  
            }

            $response = ($dataFormat == 'json') ? DataConvert::toJson($responseMapping) : DataConvert::toXML($responseMapping);
            return $response;

        } catch (Throwable $th) {
            throw new Exception($th->getMessage(), $th->getCode() == 0 ? 300081 : $th->getCode());
        }
    }

    /**
     * Builds and formats an error response based on the provided parameters and conditions.
     *
     * @param int $errorCode The error code to include in the response if applicable.
     * @param array &$contentError An array containing details about the error. This can include
     *                             flags and additional supplier-specific error information.
     * @param array &$structureError The structure used to format the error response.
     * @param string &$dataFormat The format in which the error response should be structured (e.g., JSON, XML).
     * @param array &$sourceArray The primary data array to merge into the error response. This cannot be empty.
     *
     * @return mixed Returns the formatted error response.
     * @throws Exception If the source array is empty.
     */
    public function buildError(int $errorCode, array &$contentError, array &$structureError, string &$dataFormat, &$sourceArray) :mixed {
        try {
            if (empty($sourceArray)){
                throw new Exception("The source array cannot be empty. Please provide valid data.", 300080);
            }
    
            if (array_key_exists('isHandledError', $contentError) && $contentError['isHandledError'] === true){
                [
                    'supplierCode' => $supplierCode, 
                    'supplierMessage' => $supplierMessage,
                    'addToRootName' => $addToRootName
                ] = $contentError;

                $supplierCode ?? null;
                $supplierMessage ?? null;
    
                $structureError = $contentError['structureErrorResponse'];
                $contentError = $sourceArray;
    
                if (!empty($supplierCode) && !empty($supplierMessage)){
                    $contentError = array_merge(
                        $sourceArray,
                        ['supplierCode' => $supplierCode],
                        ['supplierMessage' => $supplierMessage],
                        ['addToRootName' => $addToRootName]
                    );
                }
                
                $response = self::makingResponseWithCorrespondingFormat($structureError, $contentError, $dataFormat);
    
            } else {
                $contentError = array_merge($contentError, ['errorCode' => $errorCode], $sourceArray);
                $response = self::makingResponseWithCorrespondingFormat($structureError, $contentError, $dataFormat);
            }
    
            return $response;
            
        } catch (Throwable $th) {
            throw new Exception($th->getMessage(), $th->getCode() == 0 ? 300105 : $th->getCode());
        }
    }

    /**
     * Sorts an associative array based on the order of keys defined in a reference indexed array.
     *
     * The function reorders the input associative array so that its keys follow the order 
     * specified in the reference array. Keys not present in the reference array are placed 
     * at the end of the associative array, maintaining their original relative order.
     *
     * @param array &$arrayToOrganize The associative array to be sorted.
     * @param array &$referenceArray The indexed array that defines the desired key order.
     * @return array The reorganized associative array with keys sorted as per the reference array.
     *
     * @example
     * $parameters = ['param2' => 'value2', 'param1' => 'value1', 'param3' => 'value3'];
     * $reference = ['param1', 'param2'];
     * $sorted = sortParametersForRequest($parameters, $reference);
     * // Result: ['param1' => 'value1', 'param2' => 'value2', 'param3' => 'value3']
     *
     * @throws InvalidArgumentException If either input parameter is not an array.
     */
    public function sortParametersForRequest (array $arrayToOrganize, array &$referenceArray){
        try {
            $sortedArray = $arrayToOrganize;
            uasort($sortedArray, function($a, $b) use ($arrayToOrganize, $referenceArray){
                // Retrieves keys from the original array
                $keyA = array_search($a, $arrayToOrganize, true);
                $keyB = array_search($b, $arrayToOrganize, true);

                 // Ensure false is handled correctly
                if ($keyA === false) return 1;
                if ($keyB === false) return -1;
        
                // Compare the keys accordding to their position in the reference array
                $posA = array_search($keyA, $referenceArray, true);
                $posB = array_search($keyB, $referenceArray, true);
        
                // If the keys are not found in the reference array, they are assigned the maximum position
                $posA = $posA === false ? PHP_INT_MAX : $posA;
                $posB = $posB === false ? PHP_INT_MAX : $posB;
        
                // Compare the positions
                return $posA <=> $posB;
            });
        
            return $sortedArray;

        } catch (Throwable $th) {
            throw new Exception("An error ocurred while sorting the array, details: {$th->getMessage()}", 300132);
        }
    }

    /**
     * Merges the data from the header and body into a single JSON format to simplify data access and manipulation.
     * 
     * @param string $requestBody The request body data in JSON format.
     * @param string $requestHeader The request header data in JSON format.
     * @return mixed The merged data in JSON format.
     * @throws Exception If the request data cannot be processed or merged.
     */
    public function processAndMergeRequestData($requestBody, $requestHeader) :mixed {
        try {
            $requestBody = DataConvert::fromJsonToAssociativeArray($requestBody);
            $requestHeader = DataConvert::fromJsonToAssociativeArray($requestHeader);
            $arrayMerged = array_merge($requestBody, $requestHeader);
            return DataConvert::toJson($arrayMerged);

        } catch (Throwable $th) {
            throw new Exception("It was not possible to process and merge the request data.", 300133);
        }
    }

    /**
     * Checks whether a variable should be customized based on one or more properties that may be present in the request.
     * If the variable matches one of the expected values, the customization value is applied.
     * @param array &$allowCustomization The customization configuration, including:
     *       - 'key' (string): The property name in the object to extract the value from.
     *       - 'expectedValues' (array): Expected values in the request that indicate customization should be performed.
     *       - 'customizationValue' (mixed): The default value in case none of the properties exist in the request.
     *       - 'customizeValue' (array): The customization rules for the variable.
     * @param object &$data The object containing the source data for the variable.
     * @return mixed The customized variable value.
     * @throws Exception If the customization configuration is incomplete or empty.
     */
    private function isCustomizationNeeded(array &$allowCustomization, object &$data, $requestValue) :mixed {
        try {
            [
                'key' => $key, 
                'expectedValues' => $expectedValues
            ] = $allowCustomization;

            if (empty($key) || empty($expectedValues) || !isset($requestValue)){
                throw new Exception("Configuration for variable customization is incomplete", 300144);
            }

            $dataValue = property_exists($data, $key) ? $data->$key : $requestValue;
            $customizeValueConfigutation = !empty($allowCustomization['customizeValue']) ? $allowCustomization['customizeValue'] : null;

            if ($customizeValueConfigutation == null) {
                throw new Exception("The customization configuration must not be empty", 300145);
            }

            if (in_array($dataValue, $expectedValues)) $customizationValue = self::customizeVariable($customizeValueConfigutation, $data);

            return $customizationValue ?: $requestValue;
            
        } catch (Throwable $th) {
            throw new Exception($th->getMessage(), $th->getCode() == 0 ? 300105 : $th->getCode());
        }
    }

    /**
     * Customizes a variable by combining one or more values extracted from an object.
     * It can also apply a custom function to the combined value.
     * 
     * @param array &$customizeValue The customization rules for the variable, including:
     *       - 'variables' (array): The properties to extract from the object.
     *       - 'functionFormatIsRequired' (array): The function to apply to the combined value, including:
     *             - 'value' (bool) Indicates whether the function should be applied. If set to true, the 
     *               function is executed; otherwise, it is ignored.
     *             - 'nameFunction' (string): The name of the function to apply to the combined value.
     * @param object &$request The object containing the source data for the variable.
     * @return mixed The customized variable value.
     * @throws Exception If the function to be applied does not exist or any error ocurrs during the customization process.
     */
    private function customizeVariable(array &$customizeValue, object &$request) :mixed {
        try {
            $variables = $customizeValue['variables'] ?? null;
            $functionFormatIsRequired = $customizeValue['functionFormatIsRequired'] ?? null;

            if (empty($variables)){
                throw new Exception("The customization configuration is incomplete.", 300144);
            }

            if (!empty($functionFormatIsRequired)){
                $value = $functionFormatIsRequired['value'] ?? null;
                $nameFunction = $functionFormatIsRequired['nameFunction'] ?? null;
            }
            
            $customVariable = "";
            foreach ($variables as $variable) {
                !property_exists($request, $variable) ? $customVariable .= $variable : $customVariable .= $request->$variable;
            }

            if ($value !== false){
                if (!function_exists($nameFunction)){
                    throw new Exception("The '{$nameFunction}' function doesn't exist.", 300134); 
                }

                $customVariable = $nameFunction($customVariable);
            }
            
            return $customVariable;

        } catch (Throwable $th) {
            throw new Exception($th->getMessage(), $th->getCode() == 0 ? 300105 : $th->getCode());
        }
    }

    /**
     * Extracts parameters from the URL path (not from the query string),
     * and combines them with predefined keys to produce an associative array
     * in the format: [paramName => value].
     *
     * This process only occurs if the corresponding configuration is defined.
     *
     * @param string $url The URL from which to extract the parameters.
     * @param array  $extractPathConfiguration Configuration for extracting path segments that includes:
     *                 - 'startFrom': The starting point in the URL path from which to begin extracting segments.
     *                 - 'positionSegmentsValue': An array of positions to extract values from the URL path segments.
     *                 - 'separator': The character used to separate segments in the URL path.
     *                 - 'nameSegments': An array of names to use as keys for the extracted values.
     * 
     * @return mixed An associative array of extracted parameters or false if no configuration is defined.
     * @throws Exception If an error occurs during the extraction process.
     */
    public function extractPathSegmentsFromUrl($url, array $segmentsConfiguration) :array {
        try {
            [
                'startFrom' => $startFrom,
                'positionSegmentsValue' => $positionSegmentsValue,
                'separator' => $separator,
                'nameSegments' => $nameSegments
            ] = $segmentsConfiguration;

            $path = parse_url($url, PHP_URL_PATH);
            $startPosition = strpos($path, $startFrom);

            if ($startPosition !== false){
                // Cut until after '$startFrom'
                $pathAfterStart = substr($path, $startPosition + strlen($startFrom));
                $segments = explode($separator, trim($pathAfterStart, $separator));
                
                // Extracts the value of the defined positions
                $segmentsValue = [];
                foreach ($positionSegmentsValue as $value) {
                    $segmentsValue[] = $segments[$value];
                }
            }

            if (count($segmentsValue) === count($nameSegments)){
                // creates an associative array using the extracted values and the defined names
                return array_combine($nameSegments, $segmentsValue);
            } else {
                throw new Exception("The number of keys and values to combine the array do not match.", 300130);
            }
            
        } catch (Throwable $th) {
            throw new Exception($th->getMessage(), $th->getCode() == 0 ? 300105 : $th->getCode());
        }
    }

    /**
     * Multiplies two numbers with arbitrary precision as a fallback for `bcmul`.
     *
     * This function performs a multiplication between two numeric values using
     * standard floating-point arithmetic and returns a string formatted to the
     * specified scale (number of decimal places).
     *
     * @param string|int|float $leftOperand The left operand, as a numeric.
     * @param string|int|float $rightOperand The right operand, as a numeric.
     * @param int $scale The number of decimal digits to round the result to.
     *
     * @return string The result of the multiplication as a string.
     * @throws Exception If the operands are not numeric or if an error occurs during the multiplication.
     */
    private function bcmul_fallback($leftOperand, $rightOperand, $scale = 0) :string {
        try {
            if (!is_numeric($leftOperand) || !is_numeric($rightOperand)){
                throw new Exception("Both operands must be numeric.", 300135);
            }

            $result = (float) $leftOperand * (float) $rightOperand;
            return number_format($result, $scale, '.', '');

        } catch (Throwable $th) {
            throw new Exception($th->getMessage(), $th->getCode() == 0 ? 300105 : $th->getCode());
        }
    }

    /**
     * Divides two numbers with arbitrary precision as a fallback for `bcdiv`.
     *
     * This function performs a division between two numeric values using
     * standard floating-point arithmetic and returns a string formatted to the
     * specified scale (number of decimal places).
     *
     * @param string|int|float $leftOperand The dividend, as a numeric.
     * @param string|int|float $rightOperand The divisor, as a numeric.
     * @param int $scale The number of decimal digits to round the result to.
     *
     * @return string The result of the division as a string, or false on error.
     * @throws Exception If the operands are not numeric or if division by zero is attempted.
     */
    private function bcdiv_fallback($leftOperand, $rightOperand, $scale = 0) :string {
        try {
            if (!is_numeric($leftOperand) || !is_numeric($rightOperand)){
                throw new Exception("Both operands must be numeric.", 100157);
            }

            if ((float) $rightOperand == 0.0){
                throw new Exception("Division by zero is not allowed.", 300158);
            }

            $result = (float) $leftOperand / (float) $rightOperand;
            return number_format($result, $scale, '.', '');

        } catch (Throwable $th) {
            throw new Exception($th->getMessage(), $th->getCode() == 0 ? 300105 : $th->getCode());
        }
    }
}