<?php


use Backend\dto\AuditoriaGeneral;
use Backend\dto\ConfigMandante;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\AuditoriaGeneralMySqlDAO;
use Backend\mysql\ConfigMandanteMySqlDAO;


/* reemplaza caracteres HTML en una cadena si "local" es igual a '1'. */
$params = file_get_contents('php://input');
if ($_REQUEST["local"] == '1') {
    $unwanted_array = array(
        '&#225;' => 'á', '&#233;' => 'é', '&#237;' => 'í', '&#243;' => 'ó', '&#250;' => 'ú',
        '&#193;' => 'A', '&#201;' => 'E', '&#205;' => 'I', '&#211;' => 'O', '&#218;' => 'U',
        '&#209;' => 'N', '&#241;' => 'n', '&nbsp;' => ' ');
    $params = strtr($params, $unwanted_array);

} else {
    /* Decodifica parámetros en base64 y reemplaza caracteres HTML en texto. */

    $params = base64_decode($params);
    $unwanted_array = array(
        '&#225;' => 'á', '&#233;' => 'é', '&#237;' => 'í', '&#243;' => 'ó', '&#250;' => 'ú',
        '&#193;' => 'A', '&#201;' => 'E', '&#205;' => 'I', '&#211;' => 'O', '&#218;' => 'U',
        '&#209;' => 'N', '&#241;' => 'n', '&nbsp;' => ' ');
    $params = strtr($params, $unwanted_array);

}

function cleanStrings($array)
{
    if (is_array($array) === false || oldCount($array) === 0) return $array;

    foreach ($array as $key => $value) {
        if (is_array($value) === true || oldCount($value) > 0) $array[$key] = cleanStrings($value);
        if (is_string($value)) $array[$key] = str_replace(["\\", "/", "\"", "\n", "\r", "\t", "\x08", "\x0c"], ["\\\\", "\\/", "\\\"", "\\n", "\\r", "\\t", "\\f", "\\b"], $value);
    }

    return $array;
}

//$params = html_entity_decode ($params) ;
$params = json_decode($params);


$Country = 'DF';
$Language = 'DF';
$Data = $params->Data;
$Partner = $params->Partner;


/**
 * UpdatePartnerSettingsConfig
 *
 * Este script procesa configuraciones de socios, decodifica parámetros, actualiza configuraciones
 * en la base de datos y realiza solicitudes cURL para sincronizar datos con un API externo.
 *
 * @param string $json JSON recibido desde la entrada que contiene los datos de configuración.
 * @param array $Data Datos de configuración a actualizar.
 * @param int $Partner ID del socio.
 * 
 * 
 * @return array $response Respuesta con el estado de la operación.
 *                         - HasError: bool Indica si ocurrió un error.
 *                         - AlertType: string Tipo de alerta (success, error, etc.).
 *                         - AlertMessage: string Mensaje de alerta.
 *                         - ModelErrors: array Lista de errores del modelo.
 *                         - result: string Resultado de las operaciones realizadas.
 */
if ($Partner == 13 || true) {

    try {

        /* decodifica caracteres HTML en una cadena si "local" es igual a '1'. */
        $params = file_get_contents('php://input');
        if ($_REQUEST["local"] == '1') {
            $unwanted_array = array(
                '&#225;' => 'á', '&#233;' => 'é', '&#237;' => 'í', '&#243;' => 'ó', '&#250;' => 'ú',
                '&#193;' => 'A', '&#201;' => 'E', '&#205;' => 'I', '&#211;' => 'O', '&#218;' => 'U',
                '&#209;' => 'N', '&#241;' => 'n', '&nbsp;' => ' ');
            $params = strtr($params, $unwanted_array);

        } else {
            /* Decodifica parámetros y reemplaza entidades HTML por caracteres especiales en PHP. */

            $params = base64_decode($params);
            $unwanted_array = array(
                '&#225;' => 'á', '&#233;' => 'é', '&#237;' => 'í', '&#243;' => 'ó', '&#250;' => 'ú',
                '&#193;' => 'A', '&#201;' => 'E', '&#205;' => 'I', '&#211;' => 'O', '&#218;' => 'U',
                '&#209;' => 'N', '&#241;' => 'n', '&nbsp;' => ' ');
            $params = strtr($params, $unwanted_array);

        }

        /* decodifica un JSON y verifica si 'Data' está vacío. */
        $params = json_decode($params, true);

        $Data = $params['Data'];

        if ($Data == "") {
            exit();
        }

        $Partner = $params['Partner'];

        if ($Data == '') throw new Exception('No hay datos para atualizar', '01');


        /* Se crea una nueva instancia de la clase ConfigurationEnvironment en el código. */
        $ConfigurationEnvironment = new ConfigurationEnvironment();


        /* combina datos existentes en un arreglo, verificando su existencia y cantidad. */
        $array_combine = [];

        if (isset($Data['languagesDataBackoffice']) && oldCount($Data['languagesDataBackoffice']) > 0) {
            $array_combine['languagesDataBackoffice'] = $Data['languagesDataBackoffice'];
        }

        if (isset($Data['bannersDesktop']) && oldCount($Data['bannersDesktop']) > 0) {
            $array_combine['bannersDesktop'] = $Data['bannersDesktop'];
        }


        /* Combina datos de 'termsandconditionBackoffice' y limpia cadenas en un arreglo. */
        if (isset($Data['termsandconditionBackoffice']) && oldCount($Data['termsandconditionBackoffice']) > 0) $array_combine['termsandconditionBackoffice'] = $Data['termsandconditionBackoffice'];

        $array_combine = cleanStrings($array_combine);


        /* actualiza la configuración de un mandante y guarda los cambios en la base de datos. */
        try {
            $ConfigMandante = new ConfigMandante('', $Partner);
            $current_config = json_decode($ConfigMandante->getConfig(), true);
            $conf = !empty($current_config) ? $ConfigurationEnvironment->updateSiteBuilderg($current_config, $array_combine) : $array_combine;
            $ConfigMandante->setConfig(json_encode($conf));
            $ConfigMandante->setUsumodifId($_SESSION['usuario']);
            $ConfigMandanteMySqlDAO = new ConfigMandanteMySqlDAO();
            $ConfigMandanteMySqlDAO->update($ConfigMandante);
            $ConfigMandanteMySqlDAO->getTransaction()->commit();

        } catch (Exception $ex) {
            /* Manejo de excepciones para insertar configuración en la base de datos, si ocurre un error específico. */

            if ($ex->getMessage() == 114) {
                try {
                    $ConfigMandante = new ConfigMandante();
                    $conf = json_encode($array_combine, true);
                    $ConfigMandante->setMandante($Partner);
                    $ConfigMandante->setUsucreaId($_SESSION['usuario']);
                    $ConfigMandante->setUsumodifId(0);
                    $ConfigMandante->setConfig($conf);
                    $ConfigMandanteMySqlDAO = new ConfigMandanteMySqlDAO();
                    $ConfigMandanteMySqlDAO->insert($ConfigMandante);
                    $ConfigMandanteMySqlDAO->getTransaction()->commit();
                } catch (Exception $ex) {
                }
            }
        }

        $ConfigurationEnvironment = new ConfigurationEnvironment();

        $curl_params = [
            'token' => 'D0radobet1234!',
            'partner' => $Partner,
            'lang' => $Language,
            'country' => $Country
        ];

        $payload = $ConfigurationEnvironment->encrypt(json_encode($curl_params));

        if ($ConfigurationEnvironment->isDevelopment()) {


            /* Inicializa una solicitud POST con cURL para obtener configuraciones de un API. */
            $curl = curl_init('https://devsitebuilderconfig.virtualsoft.bet/settings2/getSetting/');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLINFO_HEADER_OUT, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($curl, CURLOPT_TIMEOUT, 20);

            /* establece encabezados HTTP y ejecuta una solicitud cURL para obtener configuración. */
            curl_setopt($curl, CURLOPT_HTTPHEADER, [
                'Content-Type:' => 'application/json',
                'Content-Length:' => strlen($payload)
            ]);

            $config = json_decode(curl_exec($curl), true);

            /* cierra una sesión cURL y actualiza la configuración del sitio. */
            curl_close($curl);
            $new_config = $ConfigurationEnvironment->updateSiteBuilderg($config, $Data);


            $curl_params['content'] = $new_config;

            /* encripta parámetros y realiza una solicitud POST a una URL. */
            $payload = $ConfigurationEnvironment->encrypt(json_encode($curl_params));

            $curl = curl_init('https://devsitebuilderconfig.virtualsoft.bet/settings2/upload/');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLINFO_HEADER_OUT, true);
            curl_setopt($curl, CURLOPT_POST, true);

            /* Configura una solicitud cURL para enviar datos JSON con tiempo de espera. */
            curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($curl, CURLOPT_TIMEOUT, 30);
            curl_setopt($curl, CURLOPT_HTTPHEADER, [
                'Content-Type' => 'application/json',
                'Content-Length' => strlen($payload)
            ]);


            /* Ejecuta una solicitud cURL y cierra la sesión al finalizar. */
            curl_exec($curl);
            curl_close($curl);
        } else {

            /* realiza una solicitud POST utilizando cURL configurando diversas opciones. */
            $curl = curl_init('http://app11.local/settings2/getSetting/');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLINFO_HEADER_OUT, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($curl, CURLOPT_TIMEOUT, 30);

            /* configura encabezados HTTP y ejecuta una solicitud cURL, decodificando la respuesta JSON. */
            curl_setopt($curl, CURLOPT_HTTPHEADER, [
                'Content-Type' => 'application/json',
                'Content-Length' => strlen($payload)
            ]);
            $respuesta = curl_exec($curl);
            $config = json_decode($respuesta, true);

            try {


                /* Se crea una nueva auditoría asignando datos de sesión del usuario. */
                $AuditoriaGeneral = new AuditoriaGeneral();
                $AuditoriaGeneral->usuarioId = $_SESSION['usuario'];
                $AuditoriaGeneral->usuarioIp = $_SESSION['dir_ip'];
                $AuditoriaGeneral->usuariosolicitaId = $_SESSION['usuario'];
                $AuditoriaGeneral->usuariosolicitaIp = $_SESSION['dir_ip'];
                $AuditoriaGeneral->usuarioaprobarId = 0;

                /* Código que registra una auditoría de actualización de configuración de respaldo. */
                $AuditoriaGeneral->usuarioaprobarIp = 0;
                $AuditoriaGeneral->tipo = 'ACTUALIZACION_CONFIGURACION_BACKUP';
                $AuditoriaGeneral->valorAntes = '';
                $AuditoriaGeneral->valorDespues = trim($respuesta);
                $AuditoriaGeneral->usucreaId = $_SESSION['usuario'];
                $AuditoriaGeneral->usumodifId = 0;

                /* Se configuran propiedades de un objeto AuditoriaGeneral para registrar información específica. */
                $AuditoriaGeneral->estado = 'A';
                $AuditoriaGeneral->dispositivo = '';
                $AuditoriaGeneral->observacion = $Partner . '_' . $Language . '_' . $Country;
                $AuditoriaGeneral->data = '';
                $AuditoriaGeneral->campo = '';

                $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO();

                /* Insertar un registro de auditoría y obtener la transacción en MySQL. */
                $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);
                $AuditoriaGeneralMySqlDAO->getTransaction()->commit();


            } catch (Exception $ex) {
                /* Bloque para manejar excepciones en PHP, sin acciones definidas. */

            }


            /* Actualiza la configuración y la prepara para subir mediante una solicitud cURL. */
            $new_config = $ConfigurationEnvironment->updateSiteBuilderg($config, $Data);
            //$ConfigurationEnvironment->generalAuditing($config, $Data);

            $curl_params['content'] = $new_config;
            $payload = $ConfigurationEnvironment->encrypt(json_encode($curl_params));

            $curl = curl_init('http://app11.local/settings2/upload/');

            /* Configura opciones para una solicitud HTTP POST con cURL en PHP. */
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLINFO_HEADER_OUT, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($curl, CURLOPT_TIMEOUT, 30);
            curl_setopt($curl, CURLOPT_HTTPHEADER, [
                'Content-Type' => 'application/json',
                'Content-Length' => strlen($payload)
            ]);

            /* Ejecuta una solicitud cURL y guarda el resultado en una variable. */
            $result = '-1- ' . (curl_exec($curl));

            curl_exec($curl);
            curl_close($curl);
            //sleep(1);
        }
        // Close cURL session handle
        curl_close($curl);
        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "success";
        $response["ModelErrors"] = [];
        $response["result"] = $result;

    } catch (Exception $ex) {
    }

    $response['HasError'] = false;
    $response['AlertType'] = 'success';
    $response['AlertMessage'] = 'success';
    $response['ModelErrors'] = [];
} else {
    exit();
    try {

        /* reemplaza caracteres HTML específicos en una cadena si se cumple una condición. */
        $params = file_get_contents('php://input');
        if ($_REQUEST["local"] == '1') {
            $unwanted_array = array(
                '&#225;' => 'á', '&#233;' => 'é', '&#237;' => 'í', '&#243;' => 'ó', '&#250;' => 'ú',
                '&#193;' => 'A', '&#201;' => 'E', '&#205;' => 'I', '&#211;' => 'O', '&#218;' => 'U',
                '&#209;' => 'N', '&#241;' => 'n', '&nbsp;' => ' ');
            $params = strtr($params, $unwanted_array);

        } else {
            /* Decodifica parámetros y reemplaza entidades HTML por caracteres acentuados y espacios. */

            $params = base64_decode($params);
            $unwanted_array = array(
                '&#225;' => 'á', '&#233;' => 'é', '&#237;' => 'í', '&#243;' => 'ó', '&#250;' => 'ú',
                '&#193;' => 'A', '&#201;' => 'E', '&#205;' => 'I', '&#211;' => 'O', '&#218;' => 'U',
                '&#209;' => 'N', '&#241;' => 'n', '&nbsp;' => ' ');
            $params = strtr($params, $unwanted_array);

        }


        /* verifica datos y crea un array con información específica. */
        if ($Data == "") {
            exit();
        }
//$Pais = new \Backend\dto\Pais($Country);

        /*$Country=strtolower(
        $Pais->iso
        );*/
        $final = array(
            'token' => 'D0radobet1234!',
            'partner' => $Partner,
            'lang' => $Language,
            'country' => $Country,
            'content' => $Data,
        );


        /* Inserta o actualiza configuraciones en base de datos para un socio específico. */
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
            /* Captura excepciones al insertar configuración de un mandante en la base de datos. */

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

        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $payload = $ConfigurationEnvironment->encrypt(json_encode(($final)));
        $result = '';


        /* realiza una solicitud POST con CURL si el entorno es de desarrollo. */
        if ($ConfigurationEnvironment->isDevelopment()) {
            $ch = curl_init("https://devsitebuilderconfig.virtualsoft.bet/settings2/upload/");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);


//curl_setopt($ch, CURLOPT_HTTPHEADER, array("Cookie: SERVERID=app1"));

// Set HTTP Header for POST request
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($payload))
            );
//$rs = curl_exec($ch);
            $result = (curl_exec($ch));
        } else {


            /* Código PHP que configura una solicitud POST con curl a una URL específica. */
            $ch = curl_init("http://app1.local/settings2/upload/");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);

//curl_setopt($ch, CURLOPT_HTTPHEADER, array("Cookie: SERVERID=app1"));

// Set HTTP Header for POST request

            /* establece encabezados HTTP para una solicitud cURL y ejecuta la consulta. */
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($payload))
            );
//$rs = curl_exec($ch);
            $result .= '-1- ' . (curl_exec($ch));


// Close cURL session handle

            /* Se cierra la sesión cURL y se inicializa otra para subir datos. */
            curl_close($ch);
//sleep(1);


            $ch = curl_init("http://app2.local/settings2/upload/");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            /* Configura una solicitud POST con encabezados HTTP y un límite de tiempo. */
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);

//curl_setopt($ch, CURLOPT_HTTPHEADER, array("Cookie: SERVERID=app2"));

// Set HTTP Header for POST request
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($payload))
            );

//$rs = curl_exec($ch);
            $result .= '-2- ' . (curl_exec($ch));


// Close cURL session handle
            curl_close($ch);
//sleep(1);


            /* Inicializa una solicitud cURL para enviar datos via POST a una URL específica. */
            $ch = curl_init("http://app3.local/settings2/upload/");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);

//curl_setopt($ch, CURLOPT_HTTPHEADER, array("Cookie: SERVERID=app2"));

// Set HTTP Header for POST request

            /* Configura cabeceras para una solicitud HTTP y ejecuta la petición cURL. */
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($payload))
            );

//$rs = curl_exec($ch);
            $result .= '-3- ' . (curl_exec($ch));


// Close cURL session handle

            /* inicializa una sesión cURL para enviar datos a una URL específica. */
            curl_close($ch);
//sleep(1);


            $ch = curl_init("http://app4.local/settings2/upload/");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            /* Configura una solicitud HTTP POST con encabezados y tiempo de espera utilizando cURL. */
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);

//curl_setopt($ch, CURLOPT_HTTPHEADER, array("Cookie: SERVERID=app2"));

// Set HTTP Header for POST request
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($payload))
            );

//$rs = curl_exec($ch);
            $result .= '-4- ' . (curl_exec($ch));


// Close cURL session handle
            curl_close($ch);

//sleep(1);


            /* Código PHP para enviar datos mediante cURL a una URL específica mediante POST. */
            $ch = curl_init("http://app5.local/settings2/upload/");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);

//curl_setopt($ch, CURLOPT_HTTPHEADER, array("Cookie: SERVERID=app2"));

// Set HTTP Header for POST request

            /* Configura encabezados HTTP para una solicitud cURL en PHP, luego ejecuta la solicitud. */
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($payload))
            );

//$rs = curl_exec($ch);
            $result .= '-5- ' . (curl_exec($ch));

//sleep(1);


            /* Código en PHP para realizar una petición POST usando cURL a una URL específica. */
            $ch = curl_init("http://app6.local/settings2/upload/");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);

//curl_setopt($ch, CURLOPT_HTTPHEADER, array("Cookie: SERVERID=app2"));

// Set HTTP Header for POST request

            /* Configura encabezados HTTP para una solicitud cURL que envía datos en JSON. */
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($payload))
            );


            $ch = curl_init("http://app7.local/settings2/upload/");

            /* Configura una solicitud POST en cURL con un payload JSON y tiempo de espera. */
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);

//curl_setopt($ch, CURLOPT_HTTPHEADER, array("Cookie: SERVERID=app2"));

// Set HTTP Header for POST request
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($payload))
            );

//$rs = curl_exec($ch);
            $result .= '-6- ' . (curl_exec($ch));

        }
        // Close cURL session handle
        curl_close($ch);
        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "success";
        $response["ModelErrors"] = [];
        $response["result"] = $result;

    } catch (Exception $e) {

        print_r($e);
    }
}