<?php

/**
 * Este script se utiliza para procesar datos relacionados con campañas y usuarios en el sistema CRM Optimove.
 * Realiza operaciones como almacenamiento en Redis, obtención de detalles de ejecución de campañas y registro de logs.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $_REQUEST    Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $CampaignID  Esta variable se utiliza para almacenar y manipular el identificador de la campaña.
 * @var mixed $argv        Esta variable se utiliza para almacenar y manipular los argumentos pasados al script.
 * @var mixed $brand       Esta variable se utiliza para almacenar y manipular la marca asociada.
 * @var mixed $ContryId    Esta variable se utiliza para almacenar y manipular el identificador del país.
 * @var mixed $EventTypeID Esta variable se utiliza para almacenar y manipular el identificador del tipo de evento.
 * @var mixed $Channel     Esta variable se utiliza para almacenar y manipular el canal de comunicación.
 * @var mixed $BonoId      Esta variable se utiliza para almacenar y manipular el identificador del bono.
 * @var mixed $UserId      Esta variable se utiliza para almacenar y manipular el identificador del usuario.
 * @var mixed $redisParam  Esta variable se utiliza para almacenar y manipular parámetros de Redis.
 * @var mixed $redisPrefix Esta variable se utiliza para almacenar y manipular el prefijo utilizado en Redis.
 * @var mixed $redis       Esta variable se utiliza para almacenar y manipular instancias de Redis.
 * @var mixed $Optimove    Esta variable se utiliza para almacenar y manipular datos relacionados con Optimove.
 * @var mixed $Token       Esta variable contiene el token de autenticación, utilizado para verificar y autorizar peticiones de forma segura.
 * @var mixed $response    Esta variable almacena la respuesta generada por una operación o petición.
 * @var mixed $log         Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 */

ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\dto\ConfigurationEnvironment;
use Backend\integrations\crm\Optimove;
use Backend\utils\RedisConnectionTrait;

ini_set('memory_limit', '-1');

header('Content-type: application/json; charset=utf-8');

// Establece un valor de prueba en la superglobal $_REQUEST.
$_REQUEST['test'] = '1';

// Variables inicializadas con argumentos pasados al script.
$CampaignID = $argv[1]; // Identificador de la campaña.
$brand = $argv[2]; // Marca asociada.
$ContryId = $argv[3]; // Identificador del país.
$EventTypeID = $argv[4]; // Identificador del tipo de evento.
$Channel = $argv[5]; // Canal de comunicación.
$BonoId = $argv[6]; // Identificador del bono.
$UserId = $argv[7]; // Identificador del usuario.

// Parámetros para la conexión a Redis.
$redisParam = ['ex' => 18000];

// Prefijo utilizado para las claves en Redis.
$redisPrefix = "F2BACK+agregarBonoBackground+UID" . $CampaignID . '+' . $Channel;

// Obtiene una instancia de Redis.
$redis = RedisConnectionTrait::getRedisInstance(true);

// Si Redis está disponible, almacena los datos y finaliza el script.
if ($redis != null) {
    $redis->set($redisPrefix, json_encode($argv), $redisParam);
    exit('1');
}
exit('2');

// Procesamiento de datos con Optimove.
$Optimove = new Optimove();

// Si el tipo de evento es 13, obtiene detalles de ejecución de la campaña.
if ($EventTypeID == 13) {
    $response = $Optimove->GetCustomerExecutionDetailsByCampaign($brand, $ContryId, $EventTypeID, $CampaignID, $Channel);
    $response = json_encode($response);
} // Si el tipo de evento es 11, obtiene detalles de ejecución en tiempo real.
elseif ($EventTypeID == 11) {
    $response = $Optimove->GetCustomerExecutionDetailsByCampaignRealTime($brand, $ContryId, $EventTypeID, $CampaignID, $Channel, $BonoId, $UserId);
    $response = json_encode($response);
}

// Registro de logs.
$log = "";
$log = $log . "/" . time();
$log = $log . "\r\n" . "-------------------------" . "\r\n";
$log = $log . ($response);

// Guarda el log en un archivo.
fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

// Imprime la respuesta.
print_r($response);

