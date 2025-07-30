<?php

use Backend\integrations\general\hub\Hub;

/**
 * Asigna valores de un JSON a variables y formatea la fecha de nacimiento.
 *
 * @param object $json->rid Identificador de la solicitud.
 * @param object $json->params->docnumber Cédula del usuario.
 * @param object $json->params->DateOfBirth Fecha de nacimiento del usuario.
 * @return array Respuesta de la API con la información obtenida usando cédula, rid y fecha de nacimiento.
 * @throws Exception Si ocurre un error al formatear la fecha de nacimiento.
 */


/* Asigna valores de un JSON a variables y formatea la fecha de nacimiento. */
$rid = $json->rid;
$cedula = $json->params->docnumber;
$fechaNacimiento = $json->params->DateOfBirth;
$fechaNacimiento = date("d-m-Y", strtotime($fechaNacimiento));
$fechaNacimiento = str_replace("-", "/", $fechaNacimiento);
$api = new Hub();



/* llama a una API para obtener información usando cédula, rid y fecha de nacimiento. */
$response = $api->cedula($cedula, $rid, $fechaNacimiento);
//$response = json_encode($response);
//print_r($response);
