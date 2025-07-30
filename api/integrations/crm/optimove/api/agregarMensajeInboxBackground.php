<?php

/**
 * Este script se utiliza para procesar mensajes de inbox en segundo plano
 * y realizar operaciones relacionadas con campañas en el sistema CRM Optimove.
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
 * @var mixed $CampaignID  Esta variable se utiliza para almacenar y manipular el identificador de la campaña.
 * @var mixed $argv        Esta variable se utiliza para almacenar y manipular los argumentos pasados al script.
 * @var mixed $brand       Esta variable se utiliza para almacenar y manipular la marca asociada.
 * @var mixed $ContryId    Esta variable se utiliza para almacenar y manipular el identificador del país.
 * @var mixed $EventTypeID Esta variable se utiliza para almacenar y manipular el identificador del tipo de evento.
 * @var mixed $Channel     Esta variable se utiliza para almacenar y manipular el canal de comunicación.
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

$CampaignID = $argv[1];
$brand = $argv[2];
$ContryId = $argv[3];
$EventTypeID = $argv[4];
$Channel = $argv[5];

$redisParam = ['ex' => 18000];

$redisPrefix = "I2BACK+agregarMensajeInboxBackground+UID" . $CampaignID;


$redis = RedisConnectionTrait::getRedisInstance(true);

if ($redis != null) {
    /**
     * Almacena los argumentos en Redis y finaliza la ejecución.
     */
    $redis->set($redisPrefix, json_encode($argv), $redisParam);
    exit();
}
exit();

/* Procesamos */
$Optimove = new Optimove();
$response = $Optimove->GetCustomerExecutionDetailsByCampaignMenssageInbox($brand, $ContryId, $EventTypeID, $CampaignID, $Channel);
$response = json_encode($response);

/**
 * Escribe los logs en un archivo.
 */
$log = "";
$log = $log . "/" . time();
$log = $log . "\r\n" . "-------------------------" . "\r\n";
$log = $log . ($response);
fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

/**
 * Imprime la respuesta en formato JSON.
 */
print_r($response);

