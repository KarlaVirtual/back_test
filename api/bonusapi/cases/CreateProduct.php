<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\Producto;
use Backend\mysql\ProductoMySqlDAO;

/**
 * CreateProduct
 * 
 * Crea un nuevo producto en el sistema
 *
 * @param object $params {
 *   "Notes": string,        // Descripción del producto
 *   "ProviderId": int,      // ID del proveedor
 *   "State": string,        // Estado del producto
 *   "Verification": string, // Tipo de verificación
 *   "ImageUrl": string,     // URL de la imagen del producto
 *   "ExternalId": string,   // ID externo del producto
 *   "CategoryId": int       // ID de la categoría
 * }
 *
 * @return array {
 *   "ErrorCode": int,           // Código de error (0 = éxito)
 *   "ErrorDescription": string  // Descripción del resultado
 * }
 *
 * @throws Exception        // Errores de procesamiento
 */

// Obtiene los parámetros enviados para crear el producto
$Notes = $params->Notes;
$ProviderId = $params->ProviderId;
$State = $params->State;
$Verification = $params->Verification;
$ImageUrl = $params->ImageUrl;
$ExternalId = $params->ExternalId;
$CategoryId = $params->CategoryId;
$Verification = $params->Verification;

// Crea una nueva instancia del objeto Producto
$Producto = new Producto();

// Configura las propiedades del producto con los valores recibidos
$Producto->setDescripcion($Notes);
$Producto->setProveedorId($ProviderId);
$Producto->setEstado($State);
$Producto->setImageUrl($ImageUrl);
$Producto->setExternoId($ExternalId);
$Producto->setVerifica($Verification);
$Producto->setUsucreaId(0);
$Producto->setUsumodifId(0);

// Inicializa la respuesta con valores por defecto de éxito
$response["ErrorCode"] = 0;
$response["ErrorDescription"] = "success";

// Intenta insertar el producto en la base de datos
try {
    $ProductoMySqlDAO = new ProductoMySqlDAO();
    $ProductoMySqlDAO->insert($Producto);
    $ProductoMySqlDAO->getTransaction()->commit();

} catch (Exception $e) {
    // En caso de error, actualiza la respuesta con el código y mensaje de error
    $response["ErrorCode"] = $e->getCode();
    $response["ErrorDescription"] = " Ocurrio un error. Error: " . $e->getCode();
}

// Devuelve la respuesta
$response = $response;