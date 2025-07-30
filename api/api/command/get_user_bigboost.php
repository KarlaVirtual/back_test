<?php
//require('../../vendor/autoload.php');
use Backend\integrations\general\bigboost\Bigboost;

//header('Content-Type: application/json');

/**
 * Procesa la solicitud para obtener información del usuario.
 *
 * @param object $json Objeto JSON que contiene los parámetros de la solicitud.
 * @param string $json->rid Identificador único relacionado con la operación.
 * @param string $json->params->docnumber Número de cédula del usuario.
 * @param string $json->params->site_id Identificador del sitio asociado.
 *
 * @return array Respuesta con el código y los datos del usuario.
 * @throws Exception Si ocurre un error al procesar la solicitud.
 */


$rid=$json->rid; // Identificador único relacionado con la operación
$cedula=$json->params->docnumber; // Número de cédula del usuario
$site_id=$json->params->site_id; // Identificador del sitio asociado

$api = new Bigboost(); // Instancia de la clase Bigboost

$response = $api->cedula($cedula, $rid, $site_id);

