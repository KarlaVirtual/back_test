<?php
namespace Backend\integrations\casino\genericTransactional\utils;

use Exception;
use Throwable;

/**
 * This class is an auxiliary class for the 'TransactionalProccess' class. 
 * It contains specific methods required for the correct functionality of 'TransactionalProccess'.
 * The methods included are responsible for generating a UUID and creating a generic response.
 * 
 * @category API
 * @package casino\genericTransactional\utils\
 * @author Esteban Arévalo
 * @version 1.0
 * @since 4/3/2025
 */
class TransactionalUtilities {
    
    /**
     * The function generates a UUID (Universally Unique Identifier) in PHP using random numbers.
     * The UUID is generated using a combination of random numbers and formatted in the standard UUID format.
     * @return string UUID (Universally Unique Identifier) is being returned.
     * @throws Exception If the UUID generation fails.
     */
    public function generateUUID() :string {
        try {
            return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                mt_rand(0, 0xffff), mt_rand(0, 0xffff),
                mt_rand(0, 0xffff),
                mt_rand(0, 0x0fff) | 0x4000,
                mt_rand(0, 0x3fff) | 0x8000,
                mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
            );
        } catch (Throwable $th) {
            throw new Exception("It was not possible to generate a UUID.", 300126);
        }
    }

    /**
     * Creates a generic response by combining keys from a JSON source with provided values.
     * @param string $abbreviatedJson The identifier for retrieving keys from the JSON source. Typically a method name.
     * @param mixed ...$valuesArray Variadic arguments representing the values to be combined with the keys.
     * @throws Exception If the JSON decoding fails or the number of keys and values do not match.
     * @return array An associative array where the keys are derived from the JSON and values are user-provided.
     */
    public function createGenericResponse(string $abbreviatedJson, ...$valuesArray) :array {
        try {
            $genericKeys = $this->getGenericKeysFromJson($abbreviatedJson);
        
            if (count($genericKeys) === count($valuesArray)){
                $genericResponse = array_combine($genericKeys, $valuesArray);
            } else {
                throw new Exception("The number of keys and values to combine the array do not match.", 300130);
            } 
            
            return $genericResponse;

        } catch (Throwable $th) {
            throw new Exception($th->getMessage(), $th->getCode() ? 300105 : $th->getCode());
        }
    }

    /**
     * Retrieves all keys from the JSON file corresponding to the specified method type.
     * @param string $abbreviatedJson The identifier for the response type (e.g., 'DEBIT', 'CREDIT').
     * @throws Exception If the method type does not exist in the mapping or the JSON file cannot be loaded.
     * @return array An array of keys retrieved from the corresponding JSON file.
     */
    private function getGenericKeysFromJson(string $abbreviatedJson) :array {
        try {
            $responseMap = [
                'DEBIT' => 'debitGenericResponse.json',
                'CREDIT' => 'creditGenericResponse.json',
                'BALANCE' => 'balanceGenericResponse.json',
                'ROLLBACK' => 'rollbackGenericResponse.json',
                'AUTHENTICATE' => 'authenticateGenericResponse.json',
                'SOURCE' => 'sourceErrorGenericResponse.json'
            ];
    
            if (!isset($responseMap[$abbreviatedJson])){
                throw new Exception("This method does not exist, the keys to build the array cannot be found.", 300127);
            }
    
            $filePath = __DIR__ . "/../responses/genericResponses/" . $responseMap[$abbreviatedJson];
            if (!file_exists($filePath) || !is_readable($filePath)){
                throw new Exception("The JSON file '{$responseMap[$abbreviatedJson]}' could not be found or read.", 300128);
            }
    
            $genericKeysContent = file_get_contents($filePath);
            $genericKeys = array_keys(json_decode($genericKeysContent, true));
            
            if (json_last_error() !== JSON_ERROR_NONE){
                throw new Exception("Error decoding JSON: " . json_last_error_msg(), 300129);
            }
            
            return $genericKeys;

        } catch (Throwable $th) {
            throw new Exception($th->getMessage(), $th->getCode() ? 300105 : $th->getCode());
        }
    }
}