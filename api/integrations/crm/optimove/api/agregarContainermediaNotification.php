<?php

/**
 * Este script se utiliza para manejar notificaciones de contenedores multimedia
 * en el sistema CRM Optimove. Realiza operaciones como almacenamiento en Redis
 * y obtención de detalles de ejecución de clientes.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-02-05
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $CampaignID     Esta variable se utiliza para almacenar y manipular el identificador de la campaña.
 * @var mixed $argv           Esta variable se utiliza para almacenar y manipular los argumentos pasados al script.
 * @var mixed $brand          Esta variable se utiliza para almacenar y manipular la marca asociada.
 * @var mixed $ContryId       Esta variable se utiliza para almacenar y manipular el identificador del país.
 * @var mixed $EventTypeID    Esta variable se utiliza para almacenar y manipular el identificador del tipo de evento.
 * @var mixed $Channel        Esta variable se utiliza para almacenar y manipular el canal de comunicación.
 * @var mixed $redisParam     Esta variable se utiliza para almacenar y manipular parámetros de Redis.
 * @var mixed $redisPrefix    Esta variable se utiliza para almacenar y manipular el prefijo utilizado en Redis.
 * @var mixed $redis          Esta variable se utiliza para almacenar y manipular instancias de Redis.
 * @var mixed $Optimove       Esta variable se utiliza para almacenar y manipular datos relacionados con Optimove.
 * @var mixed $ContainerMedia Esta variable se utiliza para almacenar y manipular datos de contenedores multimedia.
 * @var mixed $Token          Esta variable contiene el token de autenticación, utilizado para verificar y autorizar peticiones de forma segura.
 * @var mixed $Json           Esta variable se utiliza para almacenar y manipular datos en formato JSON.
 * @var mixed $response       Esta variable almacena la respuesta generada por una operación o petición.
 * @var mixed $log            Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
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

$redisParam = ['ex' => 18000]; // Parámetros de configuración para Redis
$redisPrefix = "F2BACK+agregarContainermediaNotification+UID" . $CampaignID . '+' . $Channel; // Prefijo para claves en Redis

// Obtención de instancia de Redis
$redis = RedisConnectionTrait::getRedisInstance(true);

if ($redis != null) {
    // Almacena los datos en Redis y finaliza la ejecución
    $redis->set($redisPrefix, json_encode($argv), $redisParam);
    exit();
}
exit();

/* Procesamiento de datos */
$Optimove = new Optimove(); // Instancia de la integración con Optimove
$ContainerMedia = new \Backend\integrations\crm\ContainerMedia(); // Instancia de contenedores multimedia

// Manejo de eventos según el tipo
if ($EventTypeID == 11) {
    $Json = $argv[6]; // Datos en formato JSON
    $response = $Optimove->GetCustomerExecutionDetailsByContainermediaRealtime($brand, $ContryId, $EventTypeID, $CampaignID, $Channel, $Json);
    $response = json_encode($response); // Respuesta en formato JSON
} else {
    $response = $Optimove->GetCustomerExecutionDetailsByContainermediaNotification($brand, $ContryId, $EventTypeID, $CampaignID, $Channel);
    $response = json_encode($response); // Respuesta en formato JSON
}

// Registro de logs
$log = "";
$log = $log . "/" . time();
$log = $log . "\r\n" . "-------------------------" . "\r\n";
$log = $log . ($response);

// Guardar logs en un archivo
fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

// Imprimir la respuesta
print_r($response);

