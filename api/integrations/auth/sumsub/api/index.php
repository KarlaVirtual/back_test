<?php

/**
 * Archivo principal para la integración con SUMSUB.
 *
 * Este archivo procesa solicitudes HTTP entrantes, decodifica datos JSON,
 * y utiliza servicios de autenticación para manejar revisiones y usuarios.
 * También registra eventos y respuestas en un archivo de log.
 *
 * @category   Seguridad
 * @package    Auth
 * @subpackage Integraciones
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables utilizadas en el script:
 *
 * @var mixed $_REQUEST         Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $_ENV             Variable superglobal que contiene el entorno de variables del sistema.
 * @var mixed $log              Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $_SERVER          Variable superglobal que contiene información del servidor y entorno de ejecución.
 * @var mixed $body             Esta variable contiene el contenido del cuerpo de la solicitud HTTP.
 * @var mixed $URI              Esta variable contiene el URI de la petición actual.
 * @var mixed $data             Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $createdAt        Esta variable se utiliza para almacenar y manipular la fecha de creación.
 * @var mixed $externalUserId   Esta variable se utiliza para almacenar y manipular el identificador externo del usuario.
 * @var mixed $reviewResult     Esta variable se utiliza para almacenar y manipular el resultado de una revisión.
 * @var mixed $reviewAnswer     Esta variable se utiliza para almacenar y manipular la respuesta de una revisión.
 * @var mixed $reviewRejectType Esta variable se utiliza para almacenar y manipular el tipo de rechazo en una revisión.
 * @var mixed $levelName        Esta variable se utiliza para almacenar y manipular el nombre del nivel.
 * @var mixed $status           Esta variable se utiliza para almacenar y manipular el estado.
 * @var mixed $applicantId      Esta variable se utiliza para almacenar y manipular el identificador del solicitante.
 * @var mixed $inspectionId     Esta variable se utiliza para almacenar y manipular el identificador de la inspección.
 * @var mixed $applicantType    Esta variable se utiliza para almacenar y manipular el tipo de solicitante.
 * @var mixed $correlationId    Esta variable se utiliza para almacenar y manipular el identificador de correlación.
 * @var mixed $type             Esta variable se utiliza para almacenar y manipular el tipo.
 * @var mixed $clientId         Esta variable se utiliza para almacenar y manipular el identificador del cliente.
 * @var mixed $SUMSUBSERVICES   Esta variable se utiliza para almacenar y manipular el valor de SUMSUBSERVICES.
 * @var mixed $Usuario          Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $UsuarioMandante  Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $UsuarioPerfil    Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $e                Esta variable se utiliza para capturar excepciones o errores en bloques try-catch.
 * @var mixed $response         Esta variable almacena la respuesta generada por una operación o petición.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\dto\Usuario;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioPerfil;
use Backend\integrations\auth\SUMSUBSERVICES;

// Obtiene el cuerpo de la solicitud HTTP
$body = file_get_contents('php://input');

// Obtiene el URI de la solicitud actual
$URI = $_SERVER['REQUEST_URI'];

// Procesa el cuerpo de la solicitud si no está vacío
if ($body != "") {
    // Decodifica el cuerpo JSON de la solicitud
    $data = json_decode($body);

    // Extrae datos relevantes del cuerpo de la solicitud
    $createdAt = $data->createdAt;
    $externalUserId = $data->externalUserId;
    $reviewResult = $data->reviewResult;

    $reviewAnswer = $reviewResult->reviewAnswer;
    $reviewRejectType = $reviewResult->reviewRejectType;
    $levelName = $data->levelName;
    $status = $data->reviewStatus;
    $applicantId = $data->applicantId;
    $inspectionId = $data->inspectionId;
    $applicantType = $data->applicantType;
    $correlationId = $data->correlationId;
    $type = $data->type;
    $clientId = $data->clientId;

    // Verifica si el estado no está vacío
    if ($status != '') {
        // Inicializa el servicio SUMSUB
        $SUMSUBSERVICES = new SUMSUBSERVICES();

        try {
            // Crea instancias de usuario y perfiles relacionados
            $Usuario = new Usuario($externalUserId);
            $UsuarioMandante = new UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);
            $UsuarioPerfil = new UsuarioPerfil($Usuario->usuarioId);
        } catch (Exception $e) {
            print_r('No exite Usuario');
        }

        // Procesa según el perfil del usuario
        if ($UsuarioPerfil->perfilId == "PUNTOVENTA") {
            $response = $SUMSUBSERVICES->processPV($externalUserId, $status, $applicantId, $reviewResult, $inspectionId);
        }
        if ($UsuarioPerfil->perfilId == "USUONLINE") {
            $response = $SUMSUBSERVICES->process($externalUserId, $status, $applicantId, $reviewResult, $inspectionId);
        }

        // Imprime la respuesta
        print_r($response);
    } else {
        exit();
    }
}




