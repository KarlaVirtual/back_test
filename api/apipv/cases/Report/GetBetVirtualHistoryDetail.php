<?php

use Backend\dto\Producto;
use Backend\dto\PaisMandante;
use Backend\dto\TransjuegoInfo;
use Backend\dto\ProductoMandante;
use Backend\dto\Proveedor;
use Backend\dto\TransaccionJuego;

/**
 * Report/GetBetVirtualHistoryDetail
 * 
 * Obtiene el historial detallado de apuestas virtuales para un ticket específico
 *
 * @param string $id ID del ticket a consultar
 * @param int $start Número de registros a omitir (paginación)
 * @param int $count Cantidad de registros a retornar
 * @param string $OrderedItem Campo por el cual ordenar los resultados
 *
 * @return array {
 *   "HasError": boolean,
 *   "AlertType": string,
 *   "AlertMessage": string,
 *   "ModelErrors": array,
 *   "pos": int,
 *   "total_count": int,
 *   "data": array {
 *     "TicketId": string,
 *     "Amount": float,
 *     "Price": float,
 *     "WinningAmount": float,
 *     "StateName": string,
 *     "CreatedLocal": string,
 *     "ClientLoginIP": string,
 *     "Currency": string,
 *     "UserId": string,
 *     "UserName": string,
 *     "BetShop": string,
 *     "State": string,
 *     "Date": string,
 *     "Tax": float,
 *     "WinningAmountTotal": float,
 *     "Description": string
 *   }
 * }
 *
 * @throws Exception Si ocurre un error al procesar la consulta o no existe el ticket
 *
 * @access public
 */

// Habilita el modo debug si se proporciona el parámetro secreto
if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

// Obtiene y procesa los parámetros de entrada
$Id = "CASI_".$_REQUEST["id"];
$MaxRows = $_REQUEST["count"];
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];
$seguir = true;

// Establece valores por defecto para los parámetros de paginación
if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 1000000000;
}

// Guarda el ID original del ticket y crea objeto PaisMandante
$ticket = $Id;
$pais = new PaisMandante();

// Elimina el prefijo "CASI_" del ID si existe
$prefix = "CASI_";
if (strpos($Id, $prefix) === 0) {
    $Id = substr($Id, strlen($prefix));
}

// Obtiene la información de la transacción del juego
$TransaccionJuego = new TransaccionJuego($Id);

if ($TransaccionJuego == null) {
    throw new Exception("No existe Ticket", 24);
}

// Procesa la información del ticket si existe
if ($TransaccionJuego != null) {

    // Obtiene información adicional del producto, proveedor y usuario
    $ProductoMandante = new ProductoMandante("", "", $TransaccionJuego->productoId);
    $Producto = new Producto($ProductoMandante->productoId);
    $Proveedor = new Proveedor($Producto->proveedorId);
    $TransjuegoInfo = new TransjuegoInfo("", $Id, "USUARIORELACIONADO");

    // Construye el array de respuesta con la información del ticket
    $array = [];
    $array["TicketId"] = $Id;
    $array["Proveedor"] = $Proveedor->descripcion;
    $array["Juego"] = $Producto->descripcion;
    $array["Usario"] = $TransjuegoInfo->valor;

    // Prepara la respuesta exitosa con los datos del ticket
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];
    $response["pos"] = $SkeepRows;
    $response["total_count"] = 1;
    $response["data"] = $array;
} else {
    // Prepara respuesta vacía si no se encuentra el ticket
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];
    $response["pos"] = 0;
    $response["total_count"] = 0;
    $response["data"] = array();
}
