<?php

namespace Backend\integrations\casino\genericTransactional\controller;

use Backend\integrations\casino\genericTransactional\models\DataConvert;
use Backend\integrations\casino\genericTransactional\models\DataManager;
use Exception;
use Throwable;

/**
 * Extracts the entire configuration from the errorResponseConfig.json file and 
 * sets each section into local variables, which can be accessed using 'get' methods.
 * 
 * @category API
 * @package casino\genericTransactional\controller\
 * @author Esteban Arévalo
 * @version 1.0
 * @since 3/3/2025
 */
class ManageJsonErrorController {

    /**
     * Path to the JSON file that contains the configuration of the suppliers.
     * @var string
     */
    private string $jsonPath = __DIR__ . "/../errorResponseConfig.json";

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
     * Saves the configuration and structure of the error response.
     * @var array
     */
    private $structureError;

    /**
     * Stores the content of the error response, including its structure and details.
     * @var array
     */
    private $contentError;

    /**
     * Stores the default error structure along with its content.
     * @var array
     */
    private $defaultError;

    /**
     * The specific configuration of the supplier.
     * @var array
     */
    private $supplierConfiguration;

    /**
     * Type of format used by the supplier (XML or JSON).
     * @var string
     */
    private $dataFormat;

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
     * 
     * @return void
     * @throws Exception If the JSON file could not be found or read.
     */
    public function __construct() {
        try {
            if (!file_exists($this->jsonPath) || !is_readable($this->jsonPath)){
                throw new Exception("The JSON file errorResponseConfig.json could not be found or read.", 300086);
            }
    
            $this->contentJson = DataConvert::fromJsonToAssociativeArray(file_get_contents($this->jsonPath));
            $this->dataManager = new DataManager();

        } catch (Throwable $th) {
            throw new Exception($th->getMessage(), $th->getCode() == 0 ? 300105 : $th->getCode());
        }
    }

    
   /**
    * Retrieves the configuration structures, including response structure, error content, 
    * default error for the specific provider, and format type.
    * 
    * @param string $errorCode The error code to be used to extract the specific provider configuration.
    * @return void
    * @throws Exception If the structure arrays could not be set.
    */
    public function setStructureArrays($errorCode) :void {
        try {
            [
                'structureErrorResponse' => $this->structureError,
                $errorCode => $this->contentError, 
                'default' => $this->defaultError,
                'dataFormat' => $this->dataFormat
            ] = $this->supplierConfiguration;
            
        } catch (Throwable $th) {
            throw new Exception("An error occurred whilen extracting the structure arrays, details: {$th->getMessage()}", 300113);
        }
    }

    /**
     * Retrieves the configuration for a specific supplier from a JSON content array.
     * @return void
     * @throws Exception If the supplier configuration could not be found.
     */
    public function extractSupplierConfiguration() :void {
        try {
            $this->supplierConfiguration = $this->contentJson[$this->supplierName];
        } catch (Throwable $th) {
            throw new Exception("Configuration for the supplier {$this->supplierName} could not be found.", 300114);
        }
    }

   /**
    * Sets the provider name in a property to be used later for extracting the provider-specific configuration.
    * @param string $supplierName The name of the provider to be set.
    * @return void
    * @throws Exception If the data type is not a string.
    */
    public function setSupplierName(string $supplierName) :void {
        try {
            $this->supplierName = $supplierName;
        } catch (Throwable $th) {
            throw new Exception("Data type error", 300115);
        }
    }

    /**
     * Retrieves the content error array.
     * @return mixed The content error array.
     */
    public function getContentError() :mixed {
        return $this->contentError;
    }

    /**
     * Retrieves the default error array.
     * @return mixed The default error array.
     */
    public function getDefaultError() :array {
        return $this->defaultError;
    }

    /**
     * Retrieves the structure error array.
     * @return array The structure error array.
     */
    public function getStructureError() :array {
        return $this->structureError;
    }

    /**
     * Retrieves the data manager instance to access conversion and data management methods.
     * @return object DataManager The data manager instance.
     */
    public function getDataManager() :object {
        return $this->dataManager;
    }

    /**
     * Retrieves the data format type.
     * @return string The data format type.
     */
    public function getDataFormat() :string {
        return $this->dataFormat;
    }

    /**
     * Retrieves the generic error array.
     * @return array The generic error array.
     * @throws Exception If the array could not be found.
     */
    public function getGenericError() :array {
        try {
            return $this->contentJson['default'];
        } catch (Throwable $th) {
            throw new Exception("Array not found in the supplier configuration", 300116);
        }
    }

    /**
     * Retrieves the corresponding HTTP status code based on the generated error code.
     *
     * This method determines the HTTP response code that should be returned
     * depending on the type or value of the internal application error code.
     *
     * @return array|null The HTTP status codes associated with the supplier configuration, 
     * or null if not found.
     */
    public function getResponseHttpCodes() :mixed {
        try {
            return $this->supplierConfiguration['responseHttpCodes'] ?? null;
        } catch (Throwable $th) {
            throw new Exception("Configuration for the supplier {$this->supplierName} could not be found.", 300114);
        }
    }
}