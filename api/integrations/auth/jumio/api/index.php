<?php

/**
 * Archivo principal para la integración con Jumio Services.
 *
 * Este script procesa solicitudes HTTP entrantes, decodifica datos JSON,
 * y utiliza servicios de Jumio para realizar verificaciones de identidad.
 * También registra eventos y respuestas en un archivo de log.
 *
 * @category Seguridad
 * @package  Auth
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-05-09
 */

/**
 * Variables utilizadas en el script:
 *
 * @var mixed $_REQUEST          Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $log               Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $_SERVER           Variable superglobal que contiene información del servidor y entorno de ejecución.
 * @var mixed $body              Esta variable contiene el contenido del cuerpo de la solicitud HTTP.
 * @var mixed $URI               Esta variable contiene el URI de la petición actual.
 * @var mixed $data              Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $callbackSentAt    Esta variable se utiliza para almacenar y manipular la fecha y hora en que se envió el callback.
 * @var mixed $userReference     Esta variable se utiliza para almacenar y manipular la referencia del usuario.
 * @var mixed $workflowExecution Esta variable se utiliza para almacenar y manipular la ejecución del flujo de trabajo.
 * @var mixed $uuid              Esta variable se utiliza para almacenar y manipular el identificador único universal (UUID).
 * @var mixed $href              Esta variable se utiliza para almacenar y manipular una URL o enlace.
 * @var mixed $definitionKey     Esta variable se utiliza para almacenar y manipular la clave de definición.
 * @var mixed $status            Esta variable se utiliza para almacenar y manipular el estado.
 * @var mixed $account           Esta variable contiene información de la cuenta, útil para iteraciones o validaciones.
 * @var mixed $accountId         Identificador único de la cuenta.
 * @var mixed $accountHref       URL asociada a la cuenta.
 * @var mixed $JUMIOSERVICES     Instancia del servicio de integración con Jumio para verificaciones de identidad.
 * @var mixed $Usuario           Objeto que representa la información del usuario en el sistema.
 * @var mixed $UsuarioMandante   Objeto que representa al usuario mandante, utilizado para autenticación y autorización.
 * @var mixed $token             Token de autenticación generado para autorizar peticiones.
 * @var mixed $UsuarioPerfil     Objeto que representa el perfil del usuario, utilizado para determinar el tipo de procesamiento.
 * @var mixed $response          Respuesta generada por las operaciones realizadas en el sistema.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\auth\JUMIOSERVICES;

ini_set('display_errors', 'OFF');

/**
 * Habilita el modo de depuración si se recibe el parámetro `isDebug` en la solicitud.
 */
if ($_REQUEST["isDebug"] == 1) {
    error_reporting(E_ALL);
    ini_set(
        "display_errors", "ON"
    );
}

/**
 * Registra la solicitud HTTP en un archivo de log.
 */
$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . $_SERVER['REQUEST_URI'];
$log = $log . trim(file_get_contents('php://input'));
fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

$_ENV["enabledConnectionGlobal"] = 1;

/**
 * Obtiene el cuerpo de la solicitud HTTP y el URI.
 */
$body = file_get_contents('php://input');

$URI = $_SERVER['REQUEST_URI'];

/**
 * Procesa el cuerpo de la solicitud si no está vacío.
 */
if ($body != "") {
    /**
     * Decodifica el cuerpo de la solicitud como JSON.
     */
    $data = json_decode($body);

    // Variables extraídas del JSON
    $callbackSentAt = $data->callbackSentAt;
    $userReference = $data->userReference;
    $workflowExecution = $data->workflowExecution;

    $uuid = $workflowExecution->id;
    $href = $workflowExecution->href;
    $definitionKey = $workflowExecution->definitionKey;
    $status = $workflowExecution->status;
    $account = $data->account;
    $accountId = $account->id;
    $accountHref = $account->href;

    /**
     * Determina el estado de la solicitud basado en las capacidades de extracción.
     */
    $status = $data->capabilities->extraction[0]->decision->type;

    if ($status == '') {
        $status = $workflowExecution->status;
    }

    /**
     * Si el estado no está vacío, realiza el procesamiento con Jumio Services.
     */
    if ($status != '') {
        // Inicializa el servicio de Jumio
        $JUMIOSERVICES = new \Backend\integrations\auth\JUMIOSERVICES();

        // Crea objetos de usuario y obtiene el token de acceso
        $Usuario = new \Backend\dto\Usuario($userReference);
        $UsuarioMandante = new \Backend\dto\UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);
        $token = ($JUMIOSERVICES->accesToken($UsuarioMandante));

        // Obtiene el perfil del usuario y procesa según el tipo de perfil
        $UsuarioPerfil = new \Backend\dto\UsuarioPerfil($Usuario->usuarioId);
        if ($UsuarioPerfil->perfilId == "PUNTOVENTA") {
            $response = $JUMIOSERVICES->processPV($userReference, $status, $token, $accountId, $uuid);
        }
        if ($UsuarioPerfil->perfilId == "USUONLINE") {
            $response = $JUMIOSERVICES->process($userReference, $status, $token, $accountId, $uuid);
        }

        /**
         * Registra la respuesta en el log y la imprime.
         */
        $log = "";
        $log = $log . "/" . time();

        $log = $log . "\r\n" . "-------------------------" . "\r\n";
        $log = $log . ($response);
        fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

        print_r($response);
    } else {
        exit();
    }
}



