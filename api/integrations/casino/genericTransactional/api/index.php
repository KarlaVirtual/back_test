<?php
/**
 * Este archivo contiene un script para procesar solicitudes genéricas de transacciones
 * en un entorno de casino, utilizando configuraciones específicas de proveedores.
 *
 * @category API
 * @package  casino\genericTransactional\api
 * @author   Esteban Arévalo
 * @version  1.0
 * @since    2025-05-11
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $_REQUEST              Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $_SERVER               Contiene la URI de la solicitud actual ['REQUEST_URI'].
 * @var mixed $_ENV                  Variable de entorno que habilita el modo de depuración ['debug'].
 * @var mixed $url                   Almacena la URI de la solicitud actual.
 * @var mixed $transactionalProccess Objeto que maneja el flujo de transacciones genéricas.
 * @var mixed $manageJsonController  Objeto que gestiona la configuración y validación de datos JSON.
 * @var mixed $supplierName          Nombre del proveedor extraído de la URI.
 * @var mixed $request               Datos de la solicitud procesados en formato JSON o XML.
 * @var mixed $response              Respuesta generada por el proceso de transacción.
 * @var mixed $continuesExecuting    Lista de métodos que deben ejecutarse de forma continua.
 * @var mixed $rollbackTo            Lista de métodos para realizar un rollback.
 * @var mixed $isOnlyResponse        Bandera que indica si solo se debe devolver la respuesta sin procesar datos.
 */
require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\genericTransactional\controller\ManageJsonController;
use Backend\integrations\casino\genericTransactional\controller\ManageJsonErrorController;
use Backend\integrations\casino\genericTransactional\controller\TransactionalProccess;
use Backend\integrations\casino\genericTransactional\models\DataConvert;
use Backend\integrations\casino\genericTransactional\models\DataManager;
use Backend\integrations\casino\genericTransactional\models\StructureParameters;
use Backend\integrations\casino\genericTransactional\utils\ErrorCodes;

header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT');
$_ENV["enabledConnectionGlobal"] = 1;

try {
    $url = $_SERVER['REQUEST_URI'];
    $transactionalProccess = new TransactionalProccess();

    // Habilita el modo de depuración si se recibe un parámetro específico en la solicitud.
    if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
        $_ENV['debug'] = true;
        error_reporting(E_ALL);
        ini_set('display_errors', 'ON');
    }

    try {
        $manageJsonController = new ManageJsonController();
        $supplierName = findSupplierName($url);

        // Configura el proveedor y extrae su configuración desde un archivo JSON.
        $manageJsonController->setSupplierName($supplierName);
        $manageJsonController->extractSupplierConfiguration();
        $dataFormat = $manageJsonController->getDataFormat();

        /**
         * Extracts a value from either a property or a function. 
         * The process identifies the method name based on the supplier's implementation
         */
        $manageJsonController->setValidateActionFor();
        $validateActionFor = $manageJsonController->getValidateActionFor();
        
        // Search the request action for the url
        if ($validateActionFor['type'] == 'url'){
            $manageJsonController->findRequestActionByUrl($url);

            // Retrieves the array values that define the methods to be called for this provider
            $manageJsonController->setJsonAction();
        }

        // Extracts parameters from the URL path (not from the query string).
        $pathSegmentConfiguration = $manageJsonController->getPathSegmentConfiguration();
        if ($pathSegmentConfiguration != false) {
            $requestPathParams = $manageJsonController->getDataManager()->extractPathSegmentsFromUrl($url, $pathSegmentConfiguration);
        }

        // Extract the data according to the format used by the provider; the formats are processed as XML or JSON.
        if ($dataFormat === 'json'){

            if (empty($_REQUEST)){
                $request = file_get_contents('php://input');
            } else {
                $request = DataConvert::toJson($_REQUEST);
            }

            // Combine the request body and header data into a single object.
            $requestBody = file_get_contents('php://input') ?: null;
            $requestHeader = DataConvert::toJson($_REQUEST) ?: null;

            if (!empty($requestBody) && !empty($requestHeader) && $requestHeader != '[]'){
                $request = $manageJsonController->getDataManager()->processAndMergeRequestData($requestBody, $requestHeader);
            }

            if (!empty($requestHeader) && empty($requestBody) && !empty($requestPathParams)) {
                $request = DataConvert::toJson($requestPathParams);
            }

            if (empty($request)) {
                throw new Exception('The request is empty.', 300075);
            }

            header('Content-type: application/json');
            $request = DataConvert::fromJsonToObject($request);
        } elseif ($dataFormat === 'xml') {
            $request = trim(file_get_contents('php://input'));
            if (empty($request)) {
                throw new Exception('The request is empty.', 300075);
            }

            header('Content-type: text/xml');
            $request = simplexml_load_string($request);
        }

        // Searches the request action for a method or a property
        if ($validateActionFor['type'] == 'callable'){
            $manageJsonController->findRequestActionByCallable($request);

            // Retrieves the array values that define the methods to be called for this provider
            $manageJsonController->setJsonAction();
        }

        $manageJsonController->modifyRequestActionByProperty($request);

        // Verifica si la acción está definida en la configuración JSON.
        $manageJsonController->validateExistenceOfRequestAction();

        // Extrae la configuración del método específico.
        $manageJsonController->setMethodConfiguration();

        // Configura las estructuras necesarias para la respuesta y los parámetros.
        $manageJsonController->setStructureArrays();

        // Obtiene los métodos que deben ejecutarse de forma continua.
        $continuesExecuting = $manageJsonController->getContinuesExecuting();

        // Determina si solo se debe devolver la respuesta sin procesar datos.
        $isOnlyResponse = $manageJsonController->getIsOnlyResponse();

        // Ejecuta múltiples métodos de forma simultánea.
        if ( ! empty($continuesExecuting)) {
            foreach ($continuesExecuting as $action) {
                $manageJsonController->setRequestAction($action);
                $manageJsonController->validateExistenceOfRequestAction();
                $manageJsonController->setStructureArrays();
                $method = $manageJsonController->getNameMethod();
                $arrayFormatOrder = StructureParameters::$method();
                $parameterMapping = buildTransactionalMethodData($manageJsonController, $request, $transactionalProccess);
                $orderedArray = $manageJsonController->getDataManager()->sortParametersForRequest($parameterMapping, $arrayFormatOrder);
                $genericResponse = executeTransationalMethod($manageJsonController, $request, $transactionalProccess, array_values($orderedArray));
                $response = createTransactionSuccessResponse($manageJsonController, $transactionalProccess, $genericResponse);
            }
        }

        // Realiza un rollback para cada método ejecutado.
        $rollbackTo = $manageJsonController->getRollbackTo();
        if ( ! empty($rollbackTo)) {
            foreach ($rollbackTo as $action) {
                $transactionalProccess->setRollbackTo($action);
                $method = $manageJsonController->getNameMethod();
                $arrayFormatOrder = StructureParameters::$method();
                $parameterMapping = buildTransactionalMethodData($manageJsonController, $request, $transactionalProccess);
                $orderedArray = $manageJsonController->getDataManager()->sortParametersForRequest($parameterMapping, $arrayFormatOrder);
                $genericResponse = executeTransationalMethod($manageJsonController, $request, $transactionalProccess, array_values($orderedArray));
                $response = createTransactionSuccessResponse($manageJsonController, $transactionalProccess, $genericResponse);
            }
        }

        // Ejecuta un método transaccional.
        if (empty($continuesExecuting) && empty($rollbackTo) && $isOnlyResponse === false) {
            $method = $manageJsonController->getNameMethod();
            $arrayFormatOrder = StructureParameters::$method();
            $parameterMapping = buildTransactionalMethodData($manageJsonController, $request, $transactionalProccess);
            $orderedArray = $manageJsonController->getDataManager()->sortParametersForRequest($parameterMapping, $arrayFormatOrder);
            $genericResponse = executeTransationalMethod($manageJsonController, $request, $transactionalProccess, array_values($orderedArray));
            $response = createTransactionSuccessResponse($manageJsonController, $transactionalProccess, $genericResponse);
        }

        // Simula la ejecución de un método devolviendo solo la respuesta.
        if ($isOnlyResponse === true) {
            $httpResponse = $manageJsonController->getHttpResponse();
            $dataFormat = isset($httpResponse['dataFormat']) ? $httpResponse['dataFormat'] : $dataFormat;
            $response = $dataFormat == 'json' ? DataConvert::toJson($httpResponse) : DataConvert::toXML($httpResponse);
        }

        print_r($response);
    } catch (Throwable $th) {
        if ($_ENV['debug']) {
            print_r($th);
        }

        $supplierName = findSupplierName($url);

        // Si el código de error está en la lista de errores silenciosos, no se muestra al usuario.
        if (containsErrorCode($th->getCode())) {
            throw new Exception($th->getMessage(), $th->getCode());
        }

        DataManager::$nameRequestAction = $manageJsonController->getInitialRequestAction();

        // Retrieve the error configuration for the provider.
        $manageJsonErrorController = new ManageJsonErrorController();
        $manageJsonErrorController->setSupplierName($supplierName);
        $manageJsonErrorController->extractSupplierConfiguration();
        $httpCodes = $manageJsonErrorController->getResponseHttpCodes();

        if (isset($httpCodes) && array_key_exists($th->getCode(), $httpCodes)){
            http_response_code($httpCodes[$th->getCode()]);
        }

        // Extracts and constructs the error structure for the response.
        $response = constructErrorTransactionalData($th->getCode(), $transactionalProccess, $manageJsonErrorController);
        print_r($response);
    }
} catch (Throwable $th) {
    if ($_ENV['debug']) {
        print_r($th);
    }

    // Devuelve una respuesta de error genérica si ocurre una excepción durante el procesamiento.
    $manageJsonErrorController = new ManageJsonErrorController();
    print_r(DataConvert::toJson($manageJsonErrorController->getGenericError()));
}

/**
 * Crea una respuesta exitosa para un método transaccional.
 *
 * Esta función genera una respuesta formateada según el formato de datos requerido
 * (JSON u otro) y devuelve la respuesta de manera consistente.
 *
 * - Si se requiere la respuesta completa, se formatea utilizando el gestor de datos
 *   y se almacena en el objeto del proceso transaccional.
 * - Si no se requiere la respuesta completa, simplemente se devuelve la respuesta formateada.
 *
 * @param object &$manageJsonController  Referencia al controlador que gestiona la configuración
 *                                       y validación de datos JSON.
 * @param object &$transactionalProccess Referencia al objeto que maneja el flujo de transacciones.
 * @param object &$genericResponse       Referencia a un objeto de respuesta genérica utilizado
 *                                       durante el proceso de formateo.
 *
 * @return mixed La respuesta formateada, que puede estar en JSON u otro formato según la configuración.
 * @throws Exception Si ocurre un error durante el proceso de formateo de la respuesta.
 */
function createTransactionSuccessResponse(&$manageJsonController, &$transactionalProccess, &$genericResponse)
{
    try {
        $dataFormat = $manageJsonController->getDataFormat();
        $httpResponse = $manageJsonController->getHttpResponse();

        $dataFormat = isset($httpResponse['dataFormat']) ? $httpResponse['dataFormat'] : $dataFormat;
        unset($httpResponse['dataFormat']);

        if ($manageJsonController->getIsFullDataRequired() === true){
            $response = $manageJsonController->getDataManager()->makingResponseWithCorrespondingFormat($httpResponse, $genericResponse, $dataFormat);
            $transactionalProccess->setResponseInTransaccionApi($response);
        } else {
            $response = $manageJsonController->getDataManager()->makingResponseWithCorrespondingFormat($httpResponse, $genericResponse, $dataFormat);
        }

        return $response;
    } catch (Throwable $th) {
        throw new Exception($th->getMessage(), $th->getCode() == 0 ? 300105 : $th->getCode());
    }
}

/**
 * Ejecuta un método transaccional y genera una respuesta basada en los datos y la configuración proporcionados.
 *
 * Este método procesa la solicitud convirtiendo los datos, validándolos y asegurando que los parámetros
 * necesarios se pasen al método. El comportamiento del método varía dependiendo de si se requiere
 * o no la totalidad de los datos de la solicitud.
 *
 * @param object &$manageJsonController  Controlador encargado de gestionar los datos JSON y su validación.
 * @param object &$request               Objeto de solicitud que contiene los datos de entrada.
 * @param object &$transactionalProccess Objeto que maneja el flujo del proceso transaccional.
 * @param array   $indexedArray          Array con los datos indexados que se pasarán al método transaccional.
 *
 * @return mixed Respuesta generada por la ejecución del método transaccional.
 * @throws Exception Si el nombre del método no es invocable o si ocurre un error durante la ejecución.
 */
function executeTransationalMethod(object &$manageJsonController, object &$request, object &$transactionalProccess, array $indexedArray): mixed
{
    try {
        $dataConverted = DataConvert::toJson($request);
        $nameMethod = $manageJsonController->getNameMethod();
        $supplierName = $manageJsonController->getSupplierName();


        if ( ! is_callable([$transactionalProccess, $nameMethod])) {
            throw new Exception("The method '$nameMethod' could not be found.", 300079);
        }

        $validateFor = $manageJsonController->getValidateFor();
        ! empty($validateFor) ? $validateFor : throw new Exception(
            "Validation failed: The array of acronyms cannot be empty.", 300087
        );


        if ($manageJsonController->getIsFullDataRequired() === true) {
            $genericResponse = $transactionalProccess->$nameMethod($supplierName, $validateFor, $dataConverted, ...$indexedArray);
        } else {
            $genericResponse = $transactionalProccess->$nameMethod($supplierName, $validateFor, ...$indexedArray);
        }

        return $genericResponse;
    } catch (Throwable $th) {
        throw new Exception($th->getMessage(), $th->getCode() == 0 ? 300105 : $th->getCode());
    }
}

/**
 * Construye los datos necesarios para un método transaccional.
 *
 * Este método valida la solicitud, convierte formatos, mapea datos y asegura que
 * el método correspondiente exista en el proceso transaccional. Luego prepara
 * los parámetros y los devuelve para su procesamiento posterior.
 *
 * @param object &$manageJsonController  Controlador encargado de gestionar los datos JSON y su validación.
 * @param object &$request               Objeto de solicitud que contiene los datos de entrada.
 * @param object &$transactionalProccess Objeto que maneja el flujo del proceso transaccional.
 *
 * @return array Devuelve los parámetros mapeados para el método transaccional.
 * @throws Exception Si la acción de la solicitud es inválida o falta.
 */
function buildTransactionalMethodData(object &$manageJsonController, object &$request, object &$transactionalProccess): array
{
    try {
        // Extract structured arrays and initialize the transactional process
        $builderData = $manageJsonController->getBuilderData();
        $constructorMapping = $manageJsonController->getDataManager()->mappingDataOnArray($builderData, $request);
        $transactionalProccess->initializeData($constructorMapping);

        /**
         * Map the parameters, make the call to the transactional method,
         * and create the response according to the format and structure
         * defined by the provider's configuration.
         */
        $methodParameters = $manageJsonController->getMethodParameters();
        return $manageJsonController->getDataManager()->mappingDataOnArray($methodParameters, $request);
    } catch (Throwable $th) {
        throw new Exception ($th->getMessage(), $th->getCode() == 0 ? 300105 : $th->getCode());
    }
}

/**
 * Verifica si un código de error está presente en la lista de códigos de error definidos.
 *
 * @param integer $exceptionError Código de error que se desea verificar.
 *
 * @return boolean Devuelve `true` si el código de error está en la lista, de lo contrario `false`.
 */
function containsErrorCode(int $exceptionError): bool
{
    $errorCodes = ErrorCodes::getErrorCodes();
    return in_array($exceptionError, $errorCodes) ? true : false;
}

/**
 * Extrae y devuelve el nombre del proveedor desde una URL proporcionada.
 *
 * Este método busca en la URL el nombre del proveedor utilizando una expresión regular.
 * Si no se encuentra el nombre del proveedor, lanza una excepción.
 *
 * @param string $url La URL de la cual se extraerá el nombre del proveedor.
 *
 * @return string El nombre del proveedor en letras mayúsculas.
 * @throws Exception Si no se puede encontrar el nombre del proveedor en la URL.
 */
function findSupplierName(string $url): string
{
    try {
        // Locate the supplier's name in the url
        if (preg_match('#/casino/genericTransactional/api/([^/]+)/#', $url, $matches)) {
            return strtoupper($matches[1]);
        } else {
            throw new Exception('Supplier name could not be found.', 300074);
        }
    } catch (Throwable $th) {
        throw new Exception($th->getMessage(), $th->getCode() == 0 ? 300105 : $th->getCode());
    }
}

/**
 * Crea una respuesta de error personalizada para cada proveedor.
 *
 * Este método realiza las siguientes acciones:
 * - Extrae la configuración específica del proveedor utilizando `$supplierName`.
 * - Obtiene la estructura del error, el contenido del error y el contenido de error predeterminado.
 * - Genera un array de recursos adicionales, como saldo y moneda, utilizando `$transactionalProccess`.
 * - Si la estructura del error incluye `'isHandledError'` con un valor de `true`, el error se trata como no-error
 *   y se realiza una verificación de saldo, devolviendo la respuesta predefinida de la estructura del error.
 *
 * @param int $errorCode The error code used to extract the error structure and content.
 * @param object $transactionalProccess Additional resources such as balance and currency.
 * @param object $manageJsonErrorController The controller responsible for managing JSON error responses.
 * @return mixed The error response formatted according to `errorResponseConfig.json`.
 * @throws Exception If an error occurs during the error response creation process.
 */
function constructErrorTransactionalData(int $errorCode, object &$transactionalProccess, object &$manageJsonErrorController) :mixed {
    try {
        // Extract the error structure, the error content, and the default error content.
        $manageJsonErrorController->setStructureArrays($errorCode);

        $structureError = $manageJsonErrorController->getStructureError();
        $defaultError = $manageJsonErrorController->getDefaultError();
        $contentError = $manageJsonErrorController->getContentError();
        $dataFormat = $manageJsonErrorController->getDataFormat();

        $contentError = empty($contentError) ? $defaultError : $contentError;
        if (array_key_exists('isHandledError', $contentError) && $contentError['isHandledError'] === true) {
            $sourceArray = $transactionalProccess->buildSourceArray();
        } else {
            $sourceArray = $transactionalProccess->retrieveBalance();
        }

        return $manageJsonErrorController->getDataManager()->buildError($errorCode, $contentError, $structureError, $dataFormat, $sourceArray);
    } catch (Throwable $th) {
        throw new Exception($th->getMessage(), $th->getCode() == 0 ? 300105 : $th->getCode());
    }
}