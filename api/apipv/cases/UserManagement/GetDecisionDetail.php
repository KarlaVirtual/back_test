<?php

use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Registro;
use Backend\dto\Usuario;


/**
 * Obtener detalles de decisión
 *
 * Este script permite obtener los detalles de una decisión basada en un ID específico.
 *
 * @param object $params Objeto con los siguientes parámetros:
 * @param $params->Id string Identificador de la decisión.
 *
 * @return array $response Respuesta en formato JSON con los siguientes campos:
 * - HasError (boolean): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta (e.g., success, danger).
 * - AlertMessage (string): Mensaje de alerta.
 * - ModelErrors (array): Lista de errores del modelo.
 * - Data (object): Detalles de la decisión en formato JSON.
 *
 * @throws Exception Si ocurre un error en la obtención de datos.
 */

/* Configura el entorno y recibe datos JSON desde la entrada estándar. */
$ConfigurationEnvironment = new ConfigurationEnvironment();
$params = file_get_contents('php://input');
$params = json_decode($params);

$Id = $params->Id;

$SkeepRows = 0;

/* Se inicializa una variable y un objeto para gestionar registros de verificación. */
$MaxRows = 1;

$Data = array();
$VerificacionLog = new \Backend\dto\VerificacionLog();

if ($Id != "") {


    /* crea un filtro de reglas para una verificación de usuario en una base de datos. */
    $rules = array();

    array_push($rules, array("field" => "verificacion_log.usuverificacion_id", "data" => $Id, "op" => "eq"));


    $filtro = array("rules" => $rules, "groupOp" => "AND");

    /* Código convierte un filtro a JSON, consulta datos y decodifica la respuesta. */
    $json = json_encode($filtro);


    $jsonDetail = $VerificacionLog->getVerificacionLogCustom("verificacion_log.* ", "verificacion_log.verificacion_id", "desc", $SkeepRows, $MaxRows, $json, true);

    $jsonDetail = json_decode($jsonDetail);


    /* Verifica si el conteo es mayor a cero y asigna datos específicos. */
    if (intval($jsonDetail->count[0]->{".count"}) > 0) {

        $Data = $jsonDetail->data[0]->{'verificacion_log.json'};

    }
}


/* Evalúa errores en datos y prepara respuesta en formato JSON. */
$response["HasError"] = (oldCount($Data) > 0) ? false : true;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = json_decode($Data);

