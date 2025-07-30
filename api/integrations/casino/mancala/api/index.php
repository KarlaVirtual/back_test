<?php
/**
 * Este archivo contiene un script para procesar y manejar las solicitudes de la API del casino 'Mancala'.
 * Proporciona funcionalidades para consultar balances, realizar débitos, créditos y reembolsos.
 *
 * @category   Casino
 * @package    API
 * @subpackage Integrations
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-11
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed   $_REQUEST              Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var integer $_ENV                  Indica si la conexión global está habilitada ["enabledConnectionGlobal"].
 * @var integer $_ENV                  Indica si se debe verificar la caché ['checkCache'].
 * @var boolean $_ENV                  Activa el modo de depuración si está habilitado ['debug'].
 * @var string  $_ENV                  Configuración para habilitar el tiempo de espera de bloqueo ["ENABLEDSETLOCKWAITTIMEOUT"].
 * @var string  $URI                   Contiene la URI de la solicitud actual.
 * @var string  $body                  Contiene el cuerpo de la solicitud en formato JSON.
 * @var object  $data                  Objeto decodificado del cuerpo de la solicitud.
 * @var object  $datos                 Alias del objeto $data.
 * @var string  $hashOriginal          Variable para almacenar el hash original.
 * @var string  $token                 Token de sesión proporcionado en la solicitud.
 * @var string  $hash                  Hash proporcionado en la solicitud.
 * @var string  $ExtraData             Datos adicionales proporcionados en la solicitud.
 * @var float   $amount                Monto de la transacción.
 * @var string  $TransactionGuid       Identificador único de la transacción.
 * @var string  $roundid               Identificador único de la ronda.
 * @var boolean $BonusTransaction      Indica si la transacción es un bono.
 * @var boolean $IsBonus               Indica si la transacción se trata como un bono.
 * @var string  $ExternalBonusId       Identificador externo del bono.
 * @var string  $RefundTransactionGuid Identificador único de la transacción de reembolso.
 * @var object  $Mancala               Objeto de la clase Mancala para manejar las operaciones.
 * @var mixed   $respuesta             Respuesta generada por las operaciones realizadas.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\dto\ConfigurationEnvironment;
use Backend\integrations\casino\Mancala;

ini_set('memory_limit', '-1');
header('Content-type: application/json');

$_ENV["enabledConnectionGlobal"] = 1;
$_ENV['checkCache'] = 1;

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

$_ENV["ENABLEDSETLOCKWAITTIMEOUT"] = '1';
$URI = $_SERVER['REQUEST_URI'];

$body = json_encode($_REQUEST);
$body = file_get_contents('php://input');

if ($body != "") {
    $data = json_decode($body);
}

$datos = $data;

$hashOriginal = '';


if (true) {
    if (strpos($URI, "Balance") !== false) {
        $token = $data->SessionId;
        $hash = $data->Hash;
        $ExtraData = $data->ExtraData;

        /* Procesamos */
        $Mancala = new Mancala($token, $hash, "", $hashOriginal);
        $respuesta = $Mancala->getBalance("");
    } elseif (strpos($URI, "Debit") !== false) {
        $amount = $data->Amount;
        $token = $data->SessionId; //Token
        $TransactionGuid = $data->TransactionGuid;
        $roundid = $data->RoundGuid;
        $hash = $data->Hash;
        $ExtraData = $data->ExtraData;
        $BonusTransaction = $data->BonusTransaction;
        $ExternalBonusId = $data->ExternalBonusId;

        if ($BonusTransaction == false) {
            $IsBonus = false;
        } else {
            $IsBonus = true;
        }

        $Mancala = new Mancala($token, $hash, "", $hashOriginal);

        //Que pasa con el parametro gameId que esta vacio?

        $respuesta = $Mancala->Credit("", $amount, $roundid, $TransactionGuid, json_encode($datos), $IsBonus);
    } elseif (strpos($URI, "Credit") !== false) {
        $amount = $data->Amount; //OK
        $token = $data->SessionId; //Token
        $TransactionGuid = $data->TransactionGuid;
        $RoundId = $data->RoundGuid;
        $hash = $data->Hash;
        $ExtraData = $data->ExtraData;
        $BonusTransaction = $data->BonusTransaction;
        $ExternalBonusId = $data->ExternalBonusId;
        $Mancala = new Mancala($token, $hash, "", $hashOriginal);
        $respuesta = $Mancala->Debit("", $amount, $RoundId, $TransactionGuid, json_encode($datos));
    } elseif (strpos($URI, "Refund") !== false) {
        $amount = $data->Amount; //OK
        $token = $data->SessionId; //Token
        $TransactionGuid = $data->TransactionGuid; //OK
        $RefundTransactionGuid = $data->RefundTransactionGuid; //OK
        $RoundId = $data->RoundGuid;//OK
        $hash = $data->Hash;//OK
        $ExtraData = $data->ExtraData;

        $Mancala = new Mancala($token, $hash, "", $hashOriginal);

        $respuesta = $Mancala->Rollback($amount, $RoundId, $TransactionGuid, "", json_encode($datos));
    }

    print_r(
        $respuesta
    );
}



