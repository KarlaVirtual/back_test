<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\ProductoMandante;
use Backend\mysql\ProductoMandanteMySqlDAO;

/**
 * CreateSale
 * 
 * Crea una nueva venta asociando un producto a un mandante/partner
 *
 * @param object $params {
 *   "ProductId": int,         // ID del producto a asociar
 *   "PartnerId": int,        // ID del mandante/partner 
 *   "IsWorking": boolean,    // Estado del producto (activo/inactivo)
 *   "IsVerification": boolean // Si requiere verificación
 * }
 *
 * @return array {
 *   "ErrorCode": int,        // Código de error (0 = éxito)
 *   "ErrorDescription": string // Descripción del resultado
 * }
 *
 * @throws Exception         // Errores de procesamiento
 */
// Obtiene los parámetros de entrada y configura los estados
$ProductId = $params->ProductId;
$PartnerId = $params->PartnerId; 
$State = ($params->IsWorking) ? "A" : "I";
$Verification = ($params->IsVerification) ? "A" : "I";

// Crea y configura el objeto ProductoMandante con los datos recibidos
$ProductoMandante = new ProductoMandante();

$ProductoMandante->productoId = $ProductId;
$ProductoMandante->mandante = $PartnerId;
$ProductoMandante->estado = $State;
$ProductoMandante->verifica = $Verification;
$ProductoMandante->usucreaId = 0;
$ProductoMandante->usumodifId = 0;

// Inicializa la respuesta con valores por defecto de éxito
$response["ErrorCode"] = 0;
$response["ErrorDescription"] = "success";

// Intenta insertar el producto en la base de datos y hacer commit
try {
    $ProductoMandanteMySqlDAO = new ProductoMandanteMySqlDAO();
    $ProductoMandanteMySqlDAO->insert($ProductoMandante);
    $ProductoMandanteMySqlDAO->getTransaction()->commit();

// Captura cualquier error y actualiza la respuesta con el código de error
} catch (Exception $e) {
    $response["ErrorCode"] = $e->getCode();
    $response["ErrorDescription"] = " Ocurrio un error. Error: " . $e->getCode();

}

// Retorna el objeto de respuesta
$response = $response;