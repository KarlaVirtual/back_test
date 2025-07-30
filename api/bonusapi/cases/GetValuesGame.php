<?php

use Exception;
use Backend\dto\Proveedor;
use Backend\integrations\casino\PRAGMATICSERVICES;

/**
 * Este script maneja la obtención de valores de juegos a través de servicios de proveedores.
 * 
 * @param object $params Objeto que contiene los siguientes parámetros:
 * @param int $params->PartnerId ID del socio asociado.
 * @param int $params->CountryId ID del país asociado.
 * @param int $params->ProviderId ID del proveedor de servicios.
 * @param array $params->GameIds Lista de IDs de juegos a consultar.
 * 
 * @return array $response Arreglo que contiene:
 * - msg: Mensaje de respuesta o error.
 * - HasError: Indica si ocurrió un error (true/false).
 * - AlertType: Tipo de alerta (success/danger).
 * - AlertMessage: Código del mensaje de alerta.
 * - Data: Datos obtenidos de la consulta.
 */

/* configura la respuesta como JSON y habilita la depuración si se cumple una condición. */
header("Content-type: application/json; charset=utf-8");

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}


/* Código que define variables para manejar errores y mensajes de alerta en un sistema. */
$msg = 'error';
$HasError = true;
$AlertType = 'danger';
$AlertMessage = '300061';
$data = [];

$PartnerId = $params->PartnerId;

/* Asigna valores de parámetros a variables para su uso en el código. */
$CountryId = $params->CountryId;
$ProviderId = $params->ProviderId;
$GameIds = $params->GameIds;


/* maneja un servicio basado en proveedores y procesa respuestas JSON. */
try {
    $Proveedor = new Proveedor($ProviderId);
    switch ($Proveedor->getAbreviado()) {
        case "PRAGMATIC":
            $PRAGMATICSERVICES = new PRAGMATICSERVICES();
            $return = $PRAGMATICSERVICES->getValues($GameIds, $PartnerId, $CountryId);
            break;
    }

    $return = json_decode($return);

    if (!$return->error) {
        $msg = $return->msg;
        $HasError = false;
        $AlertType = 'success';
        $AlertMessage = '0';
        $data = $return->data;
    }

} catch (Exception $e) {
    /* Captura excepciones y almacena el objeto de error en la variable $msg. */

    $msg = $e;
}


/* Se configura un arreglo de respuesta con mensajes y estado de error. */
$response["msg"] = $msg;
$response["HasError"] = $HasError;
$response["AlertType"] = $AlertType;
$response["AlertMessage"] = $AlertMessage;
$response["Data"] = $data;
