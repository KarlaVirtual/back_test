<?php

use Backend\dto\ConfigurationEnvironment;
use Backend\dto\AuditoriaGeneral;
use Backend\mysql\AuditoriaGeneralMySqlDAO;

/**
 * Actualiza la configuración SEO de un socio.
 *
 * Este script realiza las siguientes acciones:
 * - Asigna y formatea parámetros de entrada.
 * - Cifra los datos y realiza una solicitud cURL para actualizar la configuración.
 * - Detecta el dispositivo y sistema operativo del usuario.
 * - Registra auditorías en la base de datos.
 *
 * @param object $params Objeto que contiene los siguientes valores:
 * @param string $params->Country Código del país.
 * @param string $params->Language Idioma seleccionado.
 * @param mixed $params->Data Datos de configuración.
 * @param int $params->Partner Identificador del socio.
 * 
 *
 * @return array $response Respuesta con los siguientes valores:
 * - HasError (bool): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta ('success' en caso de éxito).
 * - AlertMessage (string): Mensaje de alerta.
 * - ModelErrors (array): Lista de errores del modelo (vacío si no hay errores).
 *
 * @throws Exception Si ocurre un error al insertar o confirmar la auditoría en la base de datos.
 */

/* asigna parámetros y formatea el nombre del país a minúsculas. */
$Country = $params->Country;
$Language = $params->Language;
$Data = $params->Data;
$Partner = $params->Partner;
$Pais = new \Backend\dto\Pais($Country);

$Country = strtolower(
    $Pais->iso
);

/* Se crea un array final con datos de configuración y autenticación. */
$final = array(
    'token' => 'D0radobet1234!',
    'partner' => $Partner,
    'lang' => $Language,
    'country' => $Country,
    'content' => $Data,
);


/* inicializa una configuración, encripta datos y configura una solicitud cURL. */
$ConfigurationEnvironment = new ConfigurationEnvironment();
$payload = $ConfigurationEnvironment->encrypt(json_encode(($final)));

/* Configura una solicitud POST en cURL con encabezados y tiempos de espera. */
$ch = curl_init("http://app11.local/settings/upload/");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLINFO_HEADER_OUT, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

//curl_setopt($ch, CURLOPT_HTTPHEADER, array("Cookie: SERVERID=app1"));

// Set HTTP Header for POST request
/* Ejecuta una solicitud cURL y cierra la sesión después de recibir el resultado. */
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($payload))
);
//$rs = curl_exec($ch);
$result = (curl_exec($ch));

// Close cURL session handle
curl_close($ch);


// Close cURL session handle

/* Cierra una conexión cURL y obtiene la dirección IP del usuario. */
curl_close($ch);


$ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
$ip = explode(",", $ip)[0];

/**
 * Detecta el tipo de dispositivo del usuario.
 *
 * Analiza el user agent del navegador para determinar si el usuario
 * está utilizando un dispositivo móvil o una PC.
 *
 * @return string 'Móvil' si se detecta un dispositivo móvil, 'PC' en caso contrario.
 */
function detectarDispositivo()
{
    /* Identifica si el usuario está en un dispositivo móvil analizando el user agent. */
    $userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);
    $movilKeywords = ['android', 'iphone', 'ipad', 'ipod', 'blackberry', 'windows phone', 'opera mini', 'mobile', 'silk'];
    foreach ($movilKeywords as $keyword) {
        if (strpos($userAgent, $keyword) !== false) {
            return 'Móvil';
        }
    }
    return 'PC';
}

/* detecta el sistema operativo del usuario según el "user agent". */
$dispositivo = detectarDispositivo();

/**
 * Detecta el sistema operativo del usuario.
 *
 * Analiza el user agent del navegador para determinar el sistema operativo.
 *
 * @param string $userAgent El user agent del navegador.
 * @return string El nombre del sistema operativo detectado.
 */
function getOS($userAgent)
{
    $os = "Desconocido";
    if (stripos($userAgent, 'Windows') !== false) {
        $os = 'Windows';
    } elseif (stripos($userAgent, 'Linux') !== false) {
        /* Detecta si el agente de usuario corresponde a un sistema operativo Linux. */

        $os = 'Linux';
    } elseif (stripos($userAgent, 'Macintosh') !== false || stripos($userAgent, 'Mac OS X') !== false) {
        /* Detecta si el usuario está en un sistema operativo Macintosh. */

        $os = 'Mac';
    } elseif (stripos($userAgent, 'Android') !== false) {
        /* Verifica si el agente de usuario contiene 'Android' y establece la variable del sistema operativo. */

        $os = 'Android';
    } elseif (stripos($userAgent, 'iPhone') !== false || stripos($userAgent, 'iPad') !== false) {
        /* Detecta si el user agent corresponde a dispositivos iPhone o iPad, asignando 'iOS'. */

        $os = 'iOS';
    }
    return $os;
}

/* Código que obtiene información del sistema operativo y registra auditoría de usuario. */
$so = getOS($_SERVER['HTTP_USER_AGENT']);

$AuditoriaGeneral = new AuditoriaGeneral();
$AuditoriaGeneral->usuarioId = $_SESSION['usuario'];
$AuditoriaGeneral->usuarioIp = $ip;
$AuditoriaGeneral->usuariosolicitaId = $_SESSION['usuario'];

/* Código que registra cambios de configuración, guardando valores antes y después de la actualización. */
$AuditoriaGeneral->usuarioaprobarIp = '';
$AuditoriaGeneral->tipo = 'ACTUALIZACION_CONFIGURACION';
$AuditoriaGeneral->valorAntes = ''; // Valor antes de la actualización
$AuditoriaGeneral->valorDespues = json_encode($final); // Valor después de la actualización
$AuditoriaGeneral->usucreaId = $_SESSION['usuario'];
$AuditoriaGeneral->usumodifId = 0;

/* Se asignan valores a propiedades de un objeto AuditoriaGeneral relacionado con una actualización. */
$AuditoriaGeneral->estado = 'A';
$AuditoriaGeneral->dispositivo = $dispositivo;
$AuditoriaGeneral->soperativo = $so;
$AuditoriaGeneral->imagen = '';
$AuditoriaGeneral->observacion = "Actualización de configuración de socio";
$AuditoriaGeneral->data = '';

/* Se inserta una auditoría en la base de datos y se confirma la transacción. */
$AuditoriaGeneral->campo = 'Configuración';

$AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO();
$AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);
$AuditoriaGeneralMySqlDAO->getTransaction()->commit();

$response["HasError"] = false;

/* establece un mensaje de éxito y un arreglo vacío para errores en respuesta. */
$response["AlertType"] = "success";
$response["AlertMessage"] = "success";
$response["ModelErrors"] = [];

