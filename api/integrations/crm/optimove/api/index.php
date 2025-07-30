<?php

/**
 * Este archivo contiene la implementación de un endpoint para manejar integraciones con el sistema CRM Optimove.
 *
 * Proporciona funcionalidades para procesar solicitudes HTTP relacionadas con campañas, notificaciones y mensajes
 * en el contexto de Optimove. Incluye la ejecución de scripts en segundo plano para manejar tareas específicas.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-02-05
 */

/**
 * Variables globales utilizadas en este archivo:
 *
 * @var mixed $log                      Variable para registrar mensajes y eventos de log.
 * @var mixed $_SERVER                  Superglobal que contiene información del servidor y entorno de ejecución.
 * @var mixed $_ENV                     Superglobal que contiene variables de entorno del sistema.
 * @var mixed $body                     Contenido del cuerpo de la solicitud HTTP.
 * @var mixed $URI                      URI de la petición actual.
 * @var mixed $brand                    Marca asociada a la solicitud.
 * @var mixed $_REQUEST                 Superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $ContryId                 Identificador del país.
 * @var mixed $EventTypeID              Identificador del tipo de evento.
 * @var mixed $ConfigurationEnvironment Configuración del entorno.
 * @var mixed $secrekey                 Clave secreta utilizada para la firma digital.
 * @var mixed $sing                     Firma digital generada.
 * @var mixed $UserId                   Identificador del usuario.
 * @var mixed $BonusId                  Identificador del bono.
 * @var mixed $CampaignId               Identificador de la campaña.
 * @var mixed $TemplateId               Identificador de la plantilla.
 * @var mixed $data                     Datos procesados o retornados.
 * @var mixed $Optimove                 Instancia para manejar operaciones relacionadas con Optimove.
 * @var mixed $Token                    Token de autenticación.
 * @var mixed $Response                 Respuesta generada por una operación.
 * @var mixed $DetailCampa              Detalles de la campaña.
 * @var mixed $key                      Claves genéricas.
 * @var mixed $value                    Valores asociados a claves.
 * @var mixed $BonoId                   Identificador del bono.
 * @var mixed $UsuarioId                Información del usuario.
 * @var mixed $signature                Firma digital.
 * @var mixed $CampaignID               Identificador de la campaña.
 * @var mixed $Channel                  Canal de comunicación.
 * @var mixed $response                 Respuesta generada por una operación.
 * @var mixed $promoCode                Código promocional.
 * @var mixed $customerID               Identificador del cliente.
 * @var mixed $templateID               Identificador de la plantilla.
 * @var mixed $Body                     Contenido del cuerpo de una solicitud.
 * @var mixed $respuesta                Respuesta de una operación.
 */

ini_set('display_errors', 'OFF');

// Carga automática de dependencias
require(__DIR__ . '../../../../../vendor/autoload.php');

// Importación de clases necesarias
use Backend\dto\ConfigurationEnvironment;
use Backend\integrations\crm\Optimove;
use Backend\utils\RedisConnectionTrait;

// Configuración de cabecera para respuestas JSON
header('Content-type: application/json; charset=utf-8');

// Registro inicial de logs
$log = "\r\n" . "------------" . date('Y-m-d H:i:s') . "-------------" . "\r\n";
$log = $log . $_SERVER['REQUEST_URI'];
$log = $log . trim(file_get_contents('php://input'));
fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

// Configuración de errores en modo depuración
if ($_ENV['debug']) {
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

// Obtención del cuerpo de la solicitud HTTP
$body = file_get_contents('php://input');

// Procesamiento de la URI y parámetros de la solicitud
$URI = $_SERVER['REQUEST_URI'];
$brand = $_REQUEST["brand"];
$ContryId = $_REQUEST["ContryId"];
$EventTypeID = $_REQUEST["EventTypeID"];

// Configuración del entorno
$ConfigurationEnvironment = new ConfigurationEnvironment();
if ($ConfigurationEnvironment->isDevelopment()) {
    $secrekey = "";
} else {
    $secrekey = "";
}

// Generación de firma digital
$URI = explode('?', $URI)[0];
$sing = hash("sha256", $secrekey . $body, false);

// Manejo de diferentes rutas y operaciones
if (strpos($URI, "addbonusmassive") !== false) {
    /**
     * Maneja la operación de agregar bonos masivos.
     */
    $UserId = $_REQUEST['UserId'];
    $BonusId = $_REQUEST['BonusId'];
    $CampaignId = $_REQUEST['CampaignId'];
    exec("php -f " . __DIR__ . "/../../../../src/integrations/crm/AgregarBonoBackground.php " . $UserId . " " . $BonusId . " '" . $CampaignId . "' > /dev/null &");
} elseif (strpos($URI, "addtextmassive") !== false) {
    /**
     * Maneja la operación de agregar mensajes de texto masivos.
     */
    $UserId = $_REQUEST['UserId'];
    $TemplateId = $_REQUEST['TemplateId'];
    $CampaignId = $_REQUEST['CampaignId'];
    exec("php -f " . __DIR__ . "/../../../../src/integrations/crm/AgregarMensajeTextoBackground.php " . $UserId . " " . $TemplateId . " '" . $CampaignId . "' > /dev/null &");
} else {
    /**
     * Manejo de operaciones genéricas y registro de solicitudes.
     */
    syslog(LOG_WARNING, "OPTIMOVEREQUEST 1 :" . $URI . ' - ' . json_encode($_SERVER) . json_encode($_REQUEST));

    if ($body != "" || strpos($URI, "gettoken") !== false) {
        $data = json_decode($body);

        if (strpos($URI, "gettoken") !== false) {
            /**
             * Maneja la operación de obtención de token.
             */
            $Optimove = new Optimove();
            $Token = $Optimove->Login($brand, $ContryId);
            print_r($Token);
        }

        if (strpos($URI, "campaignnotification") !== false) {
            /**
             * Maneja notificaciones de campañas.
             */
            if ($EventTypeID == 13) {
                $CampaignID = $data->CampaignID;
                $Channel = $data->ChannelID;
                exec("php -f " . __DIR__ . "/agregarBonoBackground.php '" . $CampaignID . "' '" . $brand . "' '" . $ContryId . "' '" . $EventTypeID . "' '" . $Channel . "' > /dev/null &");
            } elseif ($EventTypeID == 11) {
                $CampaignID = strtolower($data->campaignID);
                $Channel = $data->channelID;
                $promoCode = base64_encode(json_encode($data->promoCode));
                $customerID = $data->customerID;
                exec("php -f " . __DIR__ . "/agregarBonoBackground.php " . $CampaignID . " " . $brand . " '" . $ContryId . "' " . $EventTypeID . " " . $Channel . " " . $promoCode . " " . $customerID . " > /dev/null &");
            }
        }
        //CANAL 509
        if (strpos($URI, "campaignmessagetext") !== false) {
            /**
             * Maneja mensajes de texto de campañas.
             */
            if ($EventTypeID == 13) {
                $CampaignID = $data->CampaignID;
                $Channel = $data->ChannelID;
                if ($ContryId == '') {
                    $ContryId = '0';
                }
                exec("php -f " . __DIR__ . "/agregarMensajeTextoBackground.php '" . $CampaignID . "' '" . $brand . "' '" . $ContryId . "' '" . $EventTypeID . "' '" . $Channel . "'  > /dev/null &");
            } elseif ($EventTypeID == 11) {
                $CampaignID = strtolower($data->campaignID);
                $Channel = $data->channelID;
                $templateID = $data->templateID;
                $customerID = $data->customerID;
                exec("php -f " . __DIR__ . "/agregarMensajeTextoBackground.php " . $CampaignID . " " . $brand . " '" . $ContryId . "' " . $EventTypeID . " " . $Channel . " " . $templateID . " " . $customerID . " > /dev/null &");
            }
        }
        //CANAL 505
        if (strpos($URI, "containermedianotification") !== false) {
            /**
             * Maneja notificaciones de contenedores multimedia.
             */
            if ($EventTypeID == 13) {
                $CampaignID = $data->CampaignID;
                $Channel = $data->ChannelID;
                if ($ContryId == '') {
                    $ContryId = '0';
                }
                exec("php -f " . __DIR__ . "/agregarContainermediaNotification.php " . $CampaignID . " " . $brand . " " . $ContryId . " " . $EventTypeID . " " . $Channel . " > /dev/null &");
            } elseif ($EventTypeID == 11) {
                $CampaignID = $data->CampaignID;
                $Channel = $data->ChannelID;
                $Body = base64_encode($body);
                if ($ContryId == '') {
                    $ContryId = '0';
                }
                exec("php -f " . __DIR__ . "/agregarContainermediaNotification.php " . $CampaignID . " " . $brand . " " . $ContryId . " " . $EventTypeID . " " . $Channel . " " . $Body . " > /dev/null &");
            }
        }

        //CANAL 511
        if (strpos($URI, "campaignmessageinternal") !== false) {
            /**
             * Maneja mensajes internos de campañas.
             */
            if ($EventTypeID == 13) {
                $CampaignID = $data->CampaignID;
                $Channel = $data->ChannelID;
                if ($ContryId == '') {
                    $ContryId = '0';
                }
                exec("php -f " . __DIR__ . "/agregarMensajeInboxBackground.php " . $CampaignID . " " . $brand . " " . $ContryId . " " . $EventTypeID . " " . $Channel . " > /dev/null &");
            } elseif ($EventTypeID == 11) {
                $CampaignID = strtolower($data->campaignID);
                $Channel = $data->channelID;
                $templateID = $data->templateID;
                $customerID = $data->customerID;

                $redisParam = ['ex' => 18000];
                $redisPrefix = "I2BACK+AgregarMensajeInboxDirecto+UID" . $CampaignID . '+' . $Channel . '+' . $templateID . '+' . $customerID;
                $redis = RedisConnectionTrait::getRedisInstance(true);
                if ($redis != null) {
                    $redis->set($redisPrefix, json_encode(array($CampaignID, $Channel, $templateID, $customerID)), $redisParam);
                    exit();
                }
                exit();
            }
        }
    }
}