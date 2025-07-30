<?php
/**
 * Este archivo contiene un script para procesar y manejar solicitudes relacionadas con la API de casino 'Playson'.
 *
 * @category   Casino
 * @package    API
 * @subpackage Integrations
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-11
 * @access     público
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed  $_REQUEST      Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var string $URI           URI de la solicitud actual, incluyendo el método HTTP.
 * @var string $body          Cuerpo de la solicitud HTTP recibido.
 * @var string $method        Método HTTP utilizado en la solicitud.
 * @var string $log           Variable utilizada para almacenar información de registro.
 * @var mixed  $datos         Variable que almacena datos procesados.
 * @var mixed  $usumandanteId Identificador del usuario mandante.
 * @var mixed  $productoId    Identificador del producto.
 * @var mixed  $proveedorId   Identificador del proveedor.
 * @var mixed  $RoundId       Identificador de la ronda.
 * @var mixed  $CreditAmount  Monto de crédito a procesar.
 * @var mixed  $isEndRound    Indica si la ronda ha finalizado.
 * @var mixed  $transactionId Identificador de la transacción.
 */

ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Playson;

if ($_REQUEST['isDebug'] == '1') {
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Access-Control-Allow-Headers, Access-Control-Allow-Credentials,Authentication, Origin, X-Requested-With, Content-Type');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS,PUT');

$URI = $_SERVER['REQUEST_URI'] . " C " . $_SERVER['REQUEST_METHOD'];
$body = trim(file_get_contents('php://input'));
$method = "";


$log = "\r\n" . date("Y-m-d H:i:s") . "-------------------------" . "\r\n";
$log = $log . $URI;
$log = $log . trim(file_get_contents('php://input'));

$log = time();

print_r($log);


$datos = $data;

/* Procesamos */

$usumandanteId = $argv[1];
$productoId = $argv[2];
$proveedorId = $argv[3];
$RoundId = $argv[4];
$CreditAmount = $argv[5];
$isEndRound = $argv[6];
$transactionId = $argv[7];


if ($RoundId != '') {
    $Playson = new Playson('', "", '', "", '', '', $proveedorId, '');


    $respuestaCredit = $Playson->Credit3($RoundId, $CreditAmount, $transactionId, $isEndRound, $usumandanteId, $productoId, json_encode(array()));


    $log = $log . "/" . time();

    $log = $log . "\r\n" . date("Y-m-d H:i:s") . "-------------------------" . "\r\n";
    $log = $log . ($respuestaCredit);

    print_r($respuestaCredit);
}