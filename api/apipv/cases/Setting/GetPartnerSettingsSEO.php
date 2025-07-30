<?php

use Backend\dto\ConfigurationEnvironment;

/**
 * Setting/GetPartnerSettingsSEO
 *
 * Este script envía configuraciones cifradas a un servicio web para obtener configuraciones SEO.
 *
 * @param $params object Objeto con los siguientes atributos:
 * @param string $params->Country Código del país.
 * @param string $params->Language Idioma solicitado.
 * @param array $params->Data Datos adicionales.
 * @param string $params->Partner Identificador del socio.
 * 
 *
 * @return array $response Estructura de respuesta que incluye:
 *                         - "HasError" (boolean): Indica si ocurrió un error.
 *                         - "AlertType" (string): Tipo de alerta ("success" o "error").
 *                         - "AlertMessage" (string): Mensaje asociado a la alerta.
 *                         - "ModelErrors" (array): Lista de errores del modelo (vacío si no hay errores).
 *                         - "Data" (array): Configuración obtenida del servicio web.
 *
 * @throws none
 */

/* asigna valores de parámetros a variables correspondientes. */
$Country = $params->Country;
$Language = $params->Language;
$Data = $params->Data;
$Partner = $params->Partner;


/* Se asignan parámetros y se crea un objeto "Pais" en PHP. */
$Country = $params->Country;
$Language = $params->Language;
$Data = $params->Data;
$Partner = $params->Partner;


$Pais = new \Backend\dto\Pais($Country);


/* Convierte el código del país a minúsculas y lo encierra en un array. */
$Country = strtolower(
    $Pais->iso
);

$final = array(
    'token' => 'D0radobet1234!',
    'partner' => $Partner,
    'lang' => $Language,
    'country' => $Country,
);


/* Código para enviar una configuración cifrada a un servicio web mediante cURL. */
$ConfigurationEnvironment = new ConfigurationEnvironment();
$payload = $ConfigurationEnvironment->encrypt(json_encode(($final)));

/* Configura una solicitud POST con cURL, incluyendo datos y encabezados HTTP específicos. */
$ch = curl_init("http://app11.local/settings/getSetting/");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLINFO_HEADER_OUT, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Cookie: SERVERID=app1"));

// Set HTTP Header for POST request
/* mide la longitud de un payload y decodifica un JSON de respuesta. */
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($payload))
);
//$rs = curl_exec($ch);
$result = (curl_exec($ch));


$array = json_decode($result, true);


/* define una respuesta exitoso con datos y sin errores. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "success";
$response["ModelErrors"] = [];

$response["Data"] = $array;