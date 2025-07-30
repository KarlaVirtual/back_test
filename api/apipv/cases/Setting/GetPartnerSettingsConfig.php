<?php

use Backend\dto\ConfigurationEnvironment;

/**
 * Setting/GetPartnerSettingsConfig
 *
 * Este script obtiene configuraciones específicas de un socio desde un entorno de desarrollo o producción.
 *
 * @param $params object Objeto con los siguientes atributos:
 * @param array $params->Data Datos adicionales.
 * @param string $params->Partner Identificador del socio.
 *
 * @return array $response Estructura de respuesta que incluye:
 *                         - "HasError" (boolean): Indica si ocurrió un error.
 *                         - "AlertType" (string): Tipo de alerta ("success" o "error").
 *                         - "AlertMessage" (string): Mensaje asociado a la alerta.
 *                         - "ModelErrors" (array): Lista de errores del modelo (vacío si no hay errores).
 *                         - "Data" (array): Configuración obtenida del entorno.
 *
 * @throws none
 */

/* asigna valores a variables relacionadas con país, idioma y datos. */
$Country = 'DF';
$Language = 'DF';
$Data = $params->Data;
$Partner = $params->Partner;


/* crea un arreglo con datos de autenticación y parámetros definidos. */
$Country = 'DF';
$Language = 'DF';
$Data = $params->Data;
$Partner = $params->Partner;


/*$Pais = new \Backend\dto\Pais($Country);

$Country=strtolower(
    $Pais->iso
);*/

$final = array(
    'token' => 'D0radobet1234!',
    'partner' => $Partner,
    'lang' => $Language,
    'country' => $Country,
);


/* Configura un entorno de desarrollo y envía datos encriptados mediante POST. */
$ConfigurationEnvironment = new ConfigurationEnvironment();
$payload = $ConfigurationEnvironment->encrypt(json_encode(($final)));
if ($ConfigurationEnvironment->isDevelopment()) {

    $ch = curl_init("https://devsitebuilderconfig.virtualsoft.bet/settings2/getSetting/");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);

// Set HTTP Header for POST request
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($payload))
    );
//$rs = curl_exec($ch);
    $result = (curl_exec($ch));
} else {


    /* Configura una solicitud POST con cURL para obtener configuraciones desde una URL específica. */
    $ch = curl_init("http://app11.local/settings2/getSetting/");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);


    /* imprime datos en modo debug y establece encabezados para una solicitud POST. */
    if ($_ENV['debug']) {
        print_r($payload);
    }
// Set HTTP Header for POST request
    /* Código que ejecuta una solicitud cURL y muestra el resultado en modo depuración. */
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($payload))
    );

//$rs = curl_exec($ch);
    $result = (curl_exec($ch));

    if ($_ENV['debug']) {
        print_r('resultt');
        print_r($result);
    }

}


/* Convierte un JSON en array y define un mensaje de éxito en la respuesta. */
$array = json_decode($result, true);


/* inicializa un arreglo de errores y asigna datos a la respuesta. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "success";
$response["ModelErrors"] = [];

$response["Data"] = $array;
