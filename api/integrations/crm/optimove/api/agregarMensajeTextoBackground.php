<?php

/**
 * Este script se utiliza para procesar mensajes de texto en segundo plano
 * en el contexto de integraciones con CRM Optimove.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-02-05
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $CampaignID  Esta variable se utiliza para almacenar y manipular el identificador de la campaña.
 * @var mixed $argv        Esta variable se utiliza para almacenar y manipular los argumentos pasados al script.
 * @var mixed $brand       Esta variable se utiliza para almacenar y manipular la marca asociada.
 * @var mixed $ContryId    Esta variable se utiliza para almacenar y manipular el identificador del país.
 * @var mixed $EventTypeID Esta variable se utiliza para almacenar y manipular el identificador del tipo de evento.
 * @var mixed $Channel     Esta variable se utiliza para almacenar y manipular el canal de comunicación.
 * @var mixed $TemplateId  Esta variable se utiliza para almacenar y manipular el identificador de la plantilla.
 * @var mixed $UserId      Esta variable se utiliza para almacenar y manipular el identificador del usuario.
 * @var mixed $Optimove    Esta variable se utiliza para almacenar y manipular datos relacionados con Optimove.
 * @var mixed $Token       Esta variable contiene el token de autenticación, utilizado para verificar y autorizar peticiones de forma segura.
 * @var mixed $redisParam  Esta variable se utiliza para almacenar y manipular parámetros de Redis.
 * @var mixed $redisPrefix Esta variable se utiliza para almacenar y manipular el prefijo utilizado en Redis.
 * @var mixed $redis       Esta variable se utiliza para almacenar y manipular instancias de Redis.
 * @var mixed $response    Esta variable almacena la respuesta generada por una operación o petición.
 * @var mixed $log         Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 */

ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\dto\ConfigurationEnvironment;
use Backend\integrations\crm\Optimove;
use Backend\utils\RedisConnectionTrait;

header('Content-type: application/json; charset=utf-8');
ini_set('memory_limit', '-1');

// Variables iniciales
$CampaignID = $argv[1]; // Identificador de la campaña
$brand = $argv[2]; // Marca asociada
$ContryId = $argv[3]; // Identificador del país
$EventTypeID = $argv[4]; // Identificador del tipo de evento
$Channel = $argv[5]; // Canal de comunicación
$TemplateId = $argv[6]; // Identificador de la plantilla
$UserId = $argv[7]; // Identificador del usuario

/* Procesamos */
$Optimove = new Optimove(); // Instancia de la clase Optimove

// Configuración de parámetros de Redis
$redisParam = ['ex' => 18000]; // Tiempo de expiración en segundos
$redisPrefix = "F2BACK+agregarMensajeTextoBackground+UID" . $CampaignID . '+' . $Channel; // Prefijo para las claves de Redis

// Obtención de instancia de Redis
$redis = RedisConnectionTrait::getRedisInstance(true);

if ($redis != null) {
    // Almacena los argumentos en Redis y finaliza el script
    $redis->set($redisPrefix, json_encode($argv), $redisParam);
    exit();
}

exit();

if ($EventTypeID == 13) {
    // Obtiene detalles de ejecución de cliente para mensajes de texto de campaña
    $response = $Optimove->GetCustomerExecutionDetailsByCampaignMenssageText($brand, $ContryId, $EventTypeID, $CampaignID, $Channel);
    $response = json_encode($response);
} elseif ($EventTypeID == 11) {
    // Obtiene detalles de ejecución en tiempo real para mensajes de texto de campaña
    $response = $Optimove->GetCustomerExecutionDetailsByCampaignMenssageTextRealTime($brand, $ContryId, $EventTypeID, $CampaignID, $Channel, $TemplateId, $UserId);
    $response = json_encode($response);
}

// Registro de logs
$log = "";
$log = $log . "/" . time();
$log = $log . "\r\n" . "-------------------------" . "\r\n";
$log = $log . ($response);

// Guarda el log en un archivo
fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

// Imprime la respuesta
print_r($response);

