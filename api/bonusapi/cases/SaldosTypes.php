<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

/**
 * Este script genera una respuesta en formato JSON con información sobre tipos de saldo.
 *
 * @param array $params No se utiliza en este script.
 * @return array $response Respuesta en formato JSON que incluye:
 * - @property bool $HasError Indica si hubo un error (false por defecto).
 * - @property string $AlertType Tipo de alerta (success por defecto).
 * - @property string $AlertMessage Mensaje de alerta (Success por defecto).
 * - @property array $ModelErrors Lista de errores del modelo (vacío por defecto).
 * - @property array $Result Resultado con información de los tipos de saldo.
 */

/* crea una respuesta exitosa en formato JSON con información sobre un saldo. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "Success";
$response["ModelErrors"] = [];
$response["Result"] = array(
    array(
        "Id" => 0,
        "Name" => "Saldo Creditos")
);
/*
,

array(
    "Id" => 1,
    "Name" => "Saldo Premios")*/