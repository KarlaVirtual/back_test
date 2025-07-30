<?php
namespace Backend\integrations\casino\genericTransactional\controller;

use Backend\integrations\casino\genericTransactional\models\DataConvert;
use Backend\integrations\casino\genericTransactional\models\DataManager;
use Exception;
use Throwable;

/**
 * Extracts the entire configuration from the supplierConfig.json file and 
 * sets each section into local variables, which can be accessed using 'get' methods.
 * 
 * @category API
 * @package casino\genericTransactional\controller\
 * @author Esteban Arévalo
 * @version 1.0
 * @since 4/3/2025
 */
class ManageJsonController {

    /**
     * Path to the JSON file that contains the configuration of the suppliers.
     * @var string
     */
    private string $jsonPath = __DIR__ . "/../suppliersConfig.json";

    /**
     * The content of the JSON file is stored in an associative array.
     * @var array
     */
    private $contentJson;

    /**
     * The name of the supplier to be used to extract the specific configuration.
     * @var string
     */
    private $supplierName;

    /**
     * The specific configuration of the supplier is stored in an associative array.
     * @var array
     */
    private $supplierConfiguration;

    /**
     * Array that defines how the method name provided by the supplier is extracted.
     * @var array
     */
    private $validateActionFor;

    /**
     * It's the method name defined by the supplier and is used to extract the name 
     * of the method to be executed and its configuration.
     * * The value may be different (from the one received in the URL) 
     * depending on the provider's configuration.
     * @var string
     */
    private $requestAction;

    /**
     * Save a copy of the original request action (the one received from the URL) 
     * to be used later in error responses.
     * @var string
     */
    private $initialRequestAction;

    /**
     * Translate the names defined by the provider to transactional methods.
     * @var array
     */
    private $jsonAction;

    /**
     * Saves the definitions of variables to be sent to a constructor method.
     * @var array
     */
    private $builderData;

    /**
     * Saves the configuration and response structure for the specified supplier.
     * @var array
     */
    private $httpResponse;

    /**
     * Saves the array of variables to be validated before executing the method.
     * @var array
     */
    private $validateFor;

    /**
     * Saves the configuration of the parameters to be sent to the method.
     * @var array
     */
    private $methodParameters;

    /**
     * Flag that indicates whether the request must be saved in the transaction API or not.
     * @var bool
     */
    private $isFullDataRequired;

    /**
     * Stores the configuration of the specified method.
     * @var array
     */
    private $methodConfiguration;

    /**
     * Instance of an object used to access methods for transforming data.
     * @var DataManager
     */
    private DataManager $dataManager;

    /**
     * Build the path to the JSON file and check if it exists and is readable and
     * then convert the JSON content to an associative array.
     * 
     * Intance of DataManager is created to manage the data.
     * @return void
     * @throws Exception If the JSON file could not be found or read.
     */
    public function __construct() {
        try {
            if (!file_exists($this->jsonPath) || !is_readable($this->jsonPath)){
                throw new Exception("The JSON file supplierConfig.json could not be found or read at: " . $this->jsonPath, 300086);
            }

            $this->contentJson = DataConvert::fromJsonToAssociativeArray(file_get_contents($this->jsonPath));
            $this->dataManager = new DataManager();
            
        } catch (Throwable $th) {
            throw new Exception($th->getMessage(), $th->getCode() == 0 ? 300105 : $th->getCode());
        }
    }

    /**
     * Extracts the configuration of a transactional method, including the parameter array for the constructor, 
     * response structure, validation array (userId, token, etc.), parameter creation settings, 
     * and a flag indicating whether the full request information is needed.
     * 
     * @return void
     * @throws Exception If the keys required to extract the content of each structure are not found.
     */
    public function setStructureArrays() :void {
        try {
            $structuredArrays = $this->supplierConfiguration[$this->requestAction];
            [
                'construct' => $this->builderData,
                'response' => $this->httpResponse,
                'validateFor' => $this->validateFor,
                'parameters' => $this->methodParameters, 
                'requireFullData' => $this->isFullDataRequired,
            ] = $structuredArrays;
            
        } catch (Throwable $th) {
            throw new Exception("An error occurred whilen extracting the structure arrays, details: {$th->getMessage()}", 300113);
        }
    }

    /**
     * Extracts the configuration for extracting path segments.
     * 
     * @return mixed An array with the path segment configuration or false if not set.
     * @throws Exception If any error occurs while extracting the configuration.
     */
    public function getPathSegmentConfiguration() :mixed {
        try {
            if (isset($this->jsonAction[$this->requestAction]['extractPathSegments'])){
                return $this->jsonAction[$this->requestAction]['extractPathSegments'];
            }

            return false;

        } catch (Throwable $th) {
            throw new Exception($th->getMessage(), $th->getCode() == 0 ? 300105 : $th->getCode());
        }
    }

    /**
     * Creates a copy of the request action and updates it using a request property if needed.
     * 
     * If the request is in XML format, it is converted into a stdClass object.
     * Then, the request action is modified using a specific property from the request,
     * but only if modification is required.
     * 
     * @param object &$request Representation of the request data.
     * 
     * @return void
     * @throws Exception If an error occurs during the process.
     */
    public function modifyRequestActionByProperty(object &$request) :void {
        try {
            // Convert XML to object for uniform processing
            if (self::getDataFormat() == 'xml'){
                $request = DataConvert::fromXMLToObject($request);
            }

            // Retrieves the requestAction from a specific property within the request body.
            if (is_array(self::getPropertyExtractionConfig()) && !isset($this->jsonAction[$this->requestAction]['extractPathSegments'])){
                self::resolveMethodFromRequest($request);
            }

        } catch (Throwable $th) {
            throw new Exception($th->getMessage(), $th->getCode() == 0 ? 300105 : $th->getCode());
        }
    }

    /**
     * Validates the action to be executed based on a callable method or property.
     * 
     * @param object $request Representation of the request data.
     * 
     * @return void
     * @throws Exception If the action name could not be found or if the callable is not a valid method or property.
     */
    public function findRequestActionByCallable(object &$request) :void {
        try {
            ['callable' => $callable] = $this->validateActionFor;

            if (!is_callable([$request, $callable]) && !property_exists($request, $callable)){ 
                throw new Exception("Callable '{$callable}' is not a valid method or property.", 300077);
            }
            $this->requestAction = is_callable([$request, $callable]) ? $request->$callable() : $request->$callable;
            self::createCopyRequestAction();

        } catch (Throwable $th) {
            throw new Exception($th->getMessage(), $th->getCode() == 0 ? 300105 : $th->getCode());
        }
    }

    /**
     * Validates the action to be executed based on the URL.
     * 
     * @param string $url It's the URL that contains the action to be executed.
     * 
     * @return void
     * @throws Exception If the action name could not be found.
     */
    public function findRequestActionByUrl($url) :void {
        try {
            if (preg_match('#([^/]+?)(?:[\.\?&].*)?$#', $url, $matches)){
                $this->requestAction = $matches[1];
                self::createCopyRequestAction();
            } else {
                throw new Exception("Action name could not be found.", 300076);
            }
        } catch (Throwable $th) {
            throw new Exception($th->getMessage(), $th->getCode() == 0 ? 300105 : $th->getCode());
        }
    }

    /**
     * creates a copy of the request action so that the original can be accessed later in case it is modified
     * @return void
     * @throws Exception If an error occurs during the process.
     */
    private function createCopyRequestAction() :void {
        try {
            $this->initialRequestAction = $this->requestAction;
        } catch (Throwable $th) {
            throw new Exception($th->getMessage(), $th->getCode() == 0 ? 300105 : $th->getCode());
        }
    }

    /**
     * Resolves the method to be executed based on the request data.
     *
     * This function dynamically determines the method that should be called by 
     * mapping the request action to the corresponding method name defined in the 
     * configuration (`jsonAction`). It updates `$this->requestAction` with the resolved method name.
     *
     * @param object &$request The request object containing the data needed to resolve the method.
     * @throws Exception If an error occurs while mapping the request action.
     */
    private function resolveMethodFromRequest(object &$request){
        try {
            $methods = $this->jsonAction[$this->requestAction];
            $methods = $this->dataManager->mappingDataOnArray($methods, $request);
            $methodToCall = $methods[$this->requestAction];
            $this->requestAction = $methods[$methodToCall];

        } catch (Throwable $th) {
            throw new Exception($th->getMessage(), $th->getCode() == 0 ? 300105 : $th->getCode());
        }
    }

    /**
     * Validates the existence of the request action in the JSON configuration, if it's 
     * not found, an exception is thrown.
     * 
     * @return void
     * @throws Exception If the action could not be found in the JSON configuration.
     */
    public function validateExistenceOfRequestAction() :void {
        try {
            if (!isset($this->jsonAction[$this->requestAction])){
                throw new Exception("Action '{$this->requestAction}' is not defined in json configuration.", 300078);
            }
        } catch (Throwable $th) {
            throw new Exception($th->getMessage(), $th->getCode() == 0 ? 300105 : $th->getCode());
        }
    }

    /**
     * Obtains the name of the method to be executed
     * @return string The name of the method to be executed.
     * @throws Exception If the method name could not be obtained.
     */ 
    public function getNameMethod() :string {
        try {
            if (isset($this->jsonAction[$this->requestAction]['extractPathSegments'])){
                return $this->jsonAction[$this->requestAction]['methodName'];
            } else {
                return $this->jsonAction[$this->requestAction];
            }
        } catch (Throwable $th) {
            throw new Exception("It was not possible to obtain the name of the method to be executed.", 300117);
        }
    }

    /**
     * Fetches the configuration required to extract a property's value from the request
     * @return mixed An array with the property's extraction settings.
     * @throws Exception If the property extraction configuration could not be obtained. 
     */
    public function getPropertyExtractionConfig() :mixed {
        try {
            return $this->jsonAction[$this->requestAction];
        } catch (Throwable $th) {
            throw new Exception("It was not possible to obtain the name of the property.", 300120);
        }
    }

    /**
     * Obtains the construct configuration.
     * @return mixed The construct configuration.
     */
    public function getBuilderData() :mixed {
        return $this->builderData ?? null;
    }
    
    /**
     * Obtains the response configuration.
     * @return mixed The response configuration.
     */
    public function getHttpResponse() :mixed {
        return $this->httpResponse ?? null;
    }
    
    /**
     * Obtains array for validation.
     * @return mixed The array for validation.
     */
    public function getValidateFor() :mixed {
        return $this->validateFor ?? null;
    }
    
    /** 
     * Obtains the method parameters configuration.
     * @return mixed The method parameters configuration.
     */
    public function getMethodParameters() :mixed {
        return $this->methodParameters ?? null;
    }
    
    /**
     * Obtains true in case that full data of the request is required or false otherwise.
     * @return bool True if full data is required, false otherwise.
     */
    public function getIsFullDataRequired() :bool {
        return $this->isFullDataRequired ?? false;
    }

    /**
     * Extracts the 'action' section from the configuration JSON and sets 
     * its content in a property within the controller.
     * @return void
     * @throws Exception If the method names configuration could not be obtained.
     */
    public function setJsonAction() :void {
        try {
            $this->jsonAction = $this->supplierConfiguration['action'];
        } catch (Throwable $th) {
            throw new Exception("Could not obtain the method names configuration.", 300118);
        }
    }

    /**
     * Extracts the 'validateActionFor' section from the configuration JSON and sets 
     * its content in a property within the controller.
     * @return void
     * @throws Exception If the validate action for configuration could not be obtained.
     */
    public function setValidateActionFor() :void {
        try {
            $this->validateActionFor = $this->supplierConfiguration['validateActionFor'];
        } catch (Throwable $th) {
            throw new Exception("Could not obtain the validate action for configuration.", 300119);
        }
    }

    /**
     * Obtains the validateActionFor configuration.
     * @return array The validateActionFor configuration.
     * @throws Exception If the validate action for configuration could not be obtained.
     */
    public function getValidateActionFor() :array {
        try {
            return $this->validateActionFor;
        } catch (Throwable $th) {
            throw new Exception("Could not obtain the validate action for configuration.", 300119);
        }
    }

    /**
     * Obtains the data format from the JSON file.
     * @return string The data format.
     * @throws Exception If the data format could not be obtained.
     */
    public function getDataFormat() :string {
        try {
            return $this->supplierConfiguration['dataFormat'];
        } catch (Throwable $th) {
            throw new Exception("It was not possible to obtain the data format.", 300120);
        }
    }

    /** 
     * Obtains the specific supplier configuration.
     * @return void
     * @throws Exception If the supplier configuration could not be obtained.
     */
    public function extractSupplierConfiguration() :void {
        try {
            $this->supplierConfiguration = $this->contentJson[$this->supplierName];
        } catch (Throwable $th) {
            throw new Exception("It was not possible to obtain the supplier configuration.", 300121);
        }
    }

    /**
     * Sets the supplier name in a property to be used later 
     * for extracting the supplier-specific configuration.
     * @return void
     * @throws Exception If the supplier name could not be obtained.
     */
    public function setSupplierName(string &$supplierName) :void {
        try {
            $this->supplierName = $supplierName;
        } catch (Throwable $th) {
            throw new Exception("Data type error", 300115);
        }   
    }

    /**
     * Obtains the supplier name.
     * @return string The supplier name.
     */
    public function getSupplierName() :string {
        return $this->supplierName;
    }

    /**
     * Sets the request action in a property, allowing it to be used 
     * later for extracting the method name to be executed.
     * 
     * @param string $requestAction The method name used to identify the corresponding method to 
     * be executed for the specified supplier.
     * @return void
     * @throws Exception If the request action could not be obtained.
     */
    public function setRequestAction(string &$requestAction) :void {
        try {
            $this->requestAction = $requestAction;
        } catch (Throwable $th) {
            throw new Exception("Data type error", 300115);
        }
    }

    /**
     * Gets the name of the original requestAction, without any 
     * modifications that may occur during the execution cycle (exactly as received from the URL).
     * @return string The name of the request action.
     * @throws Exception If the request action was not found or was not initialized.
     */
    public function getInitialRequestAction() :string {
        try {
            return $this->initialRequestAction;
        } catch (Throwable $th) {
            throw new Exception("The request action was not found or was not initialized.", 300147);
        }
    }

    /**
     * Obtains the method names in case another method needs to be called consecutively.
     * @return mixed Can be an array with the method names or null.
     * @throws Exception If the 'continuesExecuting' configuration could not be obtained.
     */
    public function getContinuesExecuting() :mixed {
        try {
            return $this->methodConfiguration['continuesExecuting'] ?? null;
        } catch (Throwable $th) {
            throw new Exception("It was not possible to obtain the continuesExecuting configuration.", 300122);
        }
    }

    /**
     * Retrieves the data manager instance to access conversion and data management methods.
     * @return object DataManager The data manager instance.
     */
    public function getDataManager() :object {
        return $this->dataManager;
    }

    /**
     * Extracts the specific method configuration using the 'requestAction'.
     * @return void
     * @throws Exception If the method configuration could not be found.
     */
    public function setMethodConfiguration() :void {
        try {
            $this->methodConfiguration = $this->supplierConfiguration[$this->requestAction];
        } catch (Throwable $th) {
            throw new Exception("The method configuration could not be obtained, it does not exist or is badly defined.", 300123);
        }
    }

    /**
     * Extracts the 'rollbackTo' section from the configuration JSON and sets 
     * its content in a property within the controller.
     * @return mixed Can be an array with the 'rollbackTo' configuration or null.
     * @throws Exception If the rollbackTo configuration could not be found.
     */
    public function getRollbackTo() :mixed {
        try {
            return $this->methodConfiguration['rollbackTo'] ?? null;
        } catch (Throwable $th) {
            throw new Exception("Could not be obtained the rollbackTo configuration.", 300124);
        }
    }

    /**
     * Extracts the 'isOnlyResponse' section from the configuration JSON and sets 
     * its content in a property within the controller.
     * @return bool True if the method is only a response, false otherwise.
     * @throws Exception If the isOnlyResponse configuration could not be found.
     */
    public function getIsOnlyResponse() :bool {
        try {
            return $this->methodConfiguration['isOnlyResponse'] ?? false;
        } catch (Throwable $th) {
            throw new Exception("Couldn't obtain the isOnlyResponse configuration.", 300125);
        }
    }
}