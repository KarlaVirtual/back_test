<?php

use Backend\dto\ConfigurationEnvironment;

/**
 * Actualiza la configuración de un socio.
 *
 * Este script realiza las siguientes acciones:
 * - Decodifica y sanitiza los parámetros de entrada.
 * - Valida los datos y el identificador del socio.
 * - Actualiza o inserta la configuración en la base de datos.
 * - Cifra los datos y realiza solicitudes cURL para actualizar la configuración.
 *
 * @param object $params Parámetros de entrada en formato JSON que incluyen:
 * @param mixed $params->Data Datos de configuración.
 * @param int $params->Partner Identificador del socio.
 * 
 *
 * @return array $response Respuesta con los siguientes valores:
 * - HasError (bool): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta ('success' en caso de éxito).
 * - AlertMessage (string): Mensaje de alerta.
 * - ModelErrors (array): Lista de errores del modelo (vacío si no hay errores).
 * - result (string): Resultado de las solicitudes cURL.
 *
 * @throws Exception Si ocurre un error al actualizar o insertar la configuración en la base de datos.
 */

try {


    /* Se ejecuta un script PHP y se sanitizan parámetros de entrada. */
    exec("php -f /home/home2/backend/api/src/imports/Slack/message.php ' " . 'UpdatePartnerSettingsConfig2' . "' '#dev2' > /dev/null & ");


    $params = file_get_contents('php://input');
    if ($_REQUEST["local"] == '1') {
        $unwanted_array = array(
            '&#225;' => 'á', '&#233;' => 'é', '&#237;' => 'í', '&#243;' => 'ó', '&#250;' => 'ú',
            '&#193;' => 'A', '&#201;' => 'E', '&#205;' => 'I', '&#211;' => 'O', '&#218;' => 'U',
            '&#209;' => 'N', '&#241;' => 'n', '&nbsp;' => ' ');
        $params = strtr($params, $unwanted_array);

    } else {
        /* Decodifica parámetros en base64 y reemplaza caracteres HTML no deseados. */

        $params = base64_decode($params);
        $unwanted_array = array(
            '&#225;' => 'á', '&#233;' => 'é', '&#237;' => 'í', '&#243;' => 'ó', '&#250;' => 'ú',
            '&#193;' => 'A', '&#201;' => 'E', '&#205;' => 'I', '&#211;' => 'O', '&#218;' => 'U',
            '&#209;' => 'N', '&#241;' => 'n', '&nbsp;' => ' ');
        $params = strtr($params, $unwanted_array);

    }


    //$params = html_entity_decode ($params) ;

    /* decodifica parámetros JSON y asigna variables para país e idioma. */
    $params = json_decode($params);


    $Country = 'DF';
    $Language = 'DF';
    $Data = $params->Data;

    /* Valida el Partner y la variable $Data, finaliza si no cumplen condiciones. */
    $Partner = $params->Partner;
    if ($Partner != 13 && $Partner != 23 && $Partner != 25) {
        exit();
    }
    if ($Data == "") {
        exit();
    }
//$Pais = new \Backend\dto\Pais($Country);

    /*$Country=strtolower(
        $Pais->iso
    );*/

    /* Se crea un arreglo asociativo con datos para una solicitud o API. */
    $final = array(
        'token' => 'D0radobet1234!',
        'partner' => $Partner,
        'lang' => $Language,
        'country' => $Country,
        'content' => $Data,
    );


    /* Actualiza la configuración de un socio y guarda cambios en la base de datos. */
    try {
        $ConfigMandante = new \Backend\dto\ConfigMandante("", $Partner);
        $ConfigMandante->setConfig(json_encode($Data->languagesDataBackoffice));

        if (isset($Data->bannersDesktop)) {
            $ConfigMandante->setConfig(json_encode(array_merge((array)json_decode($ConfigMandante->getConfig()), array('bannersDesktop' => (array)$Data->bannersDesktop))));
        }

        $ConfigMandanteMySqlDAO = new \Backend\mysql\ConfigMandanteMySqlDAO();
        $ConfigMandanteMySqlDAO->update($ConfigMandante);
        $ConfigMandanteMySqlDAO->getTransaction()->commit();

    } catch (Exception  $e) {
        /* Maneja excepciones al insertar configuración de partner en la base de datos. */

        try {
            $ConfigMandante = new \Backend\dto\ConfigMandante();
            $ConfigMandante->setMandante(strtoupper($Partner));
            $ConfigMandante->setUsucreaId(0);
            $ConfigMandante->setUsumodifId(0);
            $ConfigMandante->setConfig(json_encode($Data->languagesDataBackoffice));
            if (isset($Data->bannersDesktop)) {
                $ConfigMandante->setConfig(json_encode(array_merge((array)json_decode($ConfigMandante->getConfig()), array('bannersDesktop' => (array)$Data->bannersDesktop))));
            }
            $ConfigMandanteMySqlDAO = new \Backend\mysql\ConfigMandanteMySqlDAO();
            $ConfigMandanteMySqlDAO->insert($ConfigMandante);
            $ConfigMandanteMySqlDAO->getTransaction()->commit();
        } catch (Exception  $e) {

        }
    }


    /* configura un entorno, cifra datos y realiza una solicitud CURL en desarrollo. */
    $ConfigurationEnvironment = new ConfigurationEnvironment();
    $payload = $ConfigurationEnvironment->encrypt(json_encode(($final)));
    $result = '';
    if ($ConfigurationEnvironment->isDevelopment()) {
        $ch = curl_init("https://devsitebuilderconfig.virtualsoft.bet/settings2/upload/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        // Set HTTP Header for POST request
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($payload))
        );
//$rs = curl_exec($ch);
        $result = (curl_exec($ch));
    } else {


        /* Ejecuta un script PHP y realiza una petición cURL al mismo tiempo. */
        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . base64_encode($payload) . "' '#dev2' '" . $_SERVER['SERVER_ADDR'] . "' 'base64' '" . base64_encode(json_encode($_SERVER)) . "' '" . base64_encode(json_encode($_SESSION)) . "' '" . base64_encode('') . "' > /dev/null & ");

        /* Configura opciones para una solicitud POST en cURL, incluyendo encabezados y tiempo de espera. */
        $ch = curl_init("http://app11.local/settings2/upload/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

//curl_setopt($ch, CURLOPT_HTTPHEADER, array("Cookie: SERVERID=app1"));

// Set HTTP Header for POST request
        /* utiliza cURL para ejecutar una solicitud y procesar resultados en segundo plano. */
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($payload))
        );
//$rs = curl_exec($ch);
        $result .= '-1- ' . (curl_exec($ch));
        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php ' " . 'APP1' . $result . "' '#dev2' > /dev/null & ");


// Close cURL session handle
        curl_close($ch);
        //sleep(1);


        /* Inicia una petición POST a una URL específica, configurando opciones de cURL. */
        /* Configura opciones cURL para desactivar verificación SSL y establecer encabezados HTTP para POST. */
        $ch = curl_init("https://app11.local/settings2/upload/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

//curl_setopt($ch, CURLOPT_HTTPHEADER, array("Cookie: SERVERID=app2"));

// Set HTTP Header for POST request
        /* ejecuta una solicitud cURL y envía resultados a un script de Slack. */
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($payload))
        );

//$rs = curl_exec($ch);
        $result .= '-6- ' . (curl_exec($ch));
        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php ' " . 'APP11' . $result . json_encode($_SERVER) . "' '#dev2' > /dev/null & ");


    }
// Close cURL session handle

    /* Cierra conexión cURL y prepara respuesta JSON sin errores y con resultados. */
    curl_close($ch);
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "success";
    $response["ModelErrors"] = [];
    $response["result"] = $result;

} catch (Exception $e) {
    /* Captura excepciones y muestra información sobre el error en PHP. */


    print_r($e);
}
