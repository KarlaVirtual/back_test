<?php
/**
 * Este archivo contiene un script para procesar y manejar las solicitudes de la API del casino 'Thunderkick'.
 * Proporciona funcionalidades para gestionar balances, apuestas, ganancias y reversiones de transacciones.
 *
 * @category   Casino
 * @package    API
 * @subpackage Integrations
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-11
 * @access     public
 *
 * Variables globales utilizadas en el script:
 *
 * @var array   $_REQUEST                 Variable superglobal que contiene datos enviados a través de cualquier método HTTP.
 * @var string  $_ENV                     Variable que habilita la conexión global ["enabledConnectionGlobal"].
 * @var string  $_ENV                     Variable que habilita el tiempo de espera para el bloqueo ["ENABLEDSETLOCKWAITTIMEOUT"] .
 * @var string  $URI                      URI de la solicitud actual.
 * @var string  $body                     Cuerpo de la solicitud HTTP.
 * @var object  $data                     Datos decodificados del cuerpo de la solicitud.
 * @var array   $headers                  Encabezados HTTP de la solicitud.
 * @var string  $user                     Usuario autenticado en la solicitud.
 * @var string  $pass                     Contraseña autenticada en la solicitud.
 * @var boolean $auth                     Indica si la autenticación fue exitosa.
 * @var string  $requestOrder             Orden de la solicitud procesada.
 * @var object  $ConfigurationEnvironment Objeto para manejar configuraciones del entorno.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\dto\ConfigurationEnvironment;
use Backend\integrations\casino\Thunderkick;
use Exception;


header('Content-type: application/json');

if ( ! function_exists('getallheaders')) {
    /**
     * Obtiene todos los encabezados de la solicitud HTTP.
     *
     * @return array Un arreglo asociativo con los encabezados de la solicitud.
     */
    function getallheaders()
    {
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
}

$headers = getallheaders();

$_ENV["enabledConnectionGlobal"] = 1;
$_ENV["ENABLEDSETLOCKWAITTIMEOUT"] = '1';
$URI = $_SERVER['REQUEST_URI'];

$body = json_encode($_REQUEST);

$body = file_get_contents('php://input');

if ($body != "") {
    $data = json_decode($body);
}

$datos = $data;

$user = ($_SERVER['PHP_AUTH_USER']); //OK
$pass = ($_SERVER['PHP_AUTH_PW']); //OK


$auth = false;

$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . ('SERVER');
$log = $log . (json_encode($_SERVER));
$log = $log . ($URI);
$log = $log . (http_build_query($_REQUEST));
$log = $log . ' HEADERS ';
$log = $log . (json_encode($headers));
$log = $log . json_encode($data);
$log = $log . $user;
$log = $log . $pass;

$requestOrder = "";
ksort($_REQUEST);
$cont = 0;


$ConfigurationEnvironment = new ConfigurationEnvironment();

if ($ConfigurationEnvironment->isDevelopment()) {
    $hashOriginal = md5($requestOrder . "testKey");
    $hashOriginal = $_REQUEST["hash"];
    if ($user == 'tk' && $pass == 'tk') {
        $auth = true;
    } else {
        $auth = false;
    }
} else {
    $hashOriginal = md5($requestOrder . "Dlk8Y_iZllt");
    $hashOriginal = '';


    $user = 'virtualsoft_thunderkick';
    $pass = 'virtualsoft1234!';

    $auth = false;

    if ($user == 'virtualsoft_thunderkick' && $pass = 'virtualsoft1234!') {
        $auth = true;
    } else {
        $auth = false;
    }
}

if ($data != '') {
    if ($auth) {
        if (strpos($URI, "balances") !== false) {
            $playerExternalReference = $data->playerExternalReference;
            $playerSessionToken = $data->playerSessionToken;
            $operatorSessionToken = $data->operatorSessionToken;
            $gameName = $data->gameName;
            $distributionChannel = $data->distributionChannel;
            $hash = $data->hash;

            /* Procesamos */
            $Thunderkick = new Thunderkick($operatorSessionToken, $hash, $playerExternalReference, $hashOriginal);
            $response = ($Thunderkick->getBalance($operatorSessionToken, $playerExternalReference));


            $log = "";
            $log = $log . "/" . time();

            $log = $log . "\r\n" . "-------------------------" . "\r\n";
            $log = $log . ($response);

            print_r($response);
        } else {
            $playerId = $data->playerId;
            $playerExternalReference = $data->playerExternalReference;
            $ipAddress = $data->ipAddress;
            $gameName = $data->gameRound->gameName;
            $gameRoundId = $data->gameRound->gameRoundId;
            $providerGameRoundId = $data->gameRound->gameRoundId;
            $providerId = $data->gameRound->providerId;
            $gameRoundStartDate = $data->gameRound->gameRoundStartDate;
            $gameRoundEndDate = $data->gameRound->gameRoundEndDate;
            $numberOfBets = $data->gameRound->numberOfBets;
            $numberOfWins = $data->gameRound->numberOfWins;
            $gameSessionToken = $data->gameSessionToken;
            $playerSessionToken = $data->playerSessionToken;
            $operatorSessionToken = $data->operatorSessionToken;
            $distributionChannel = $data->distributionChannel;
            $amountBet = $data->bets[0]->bet->amount;
            $currencyBet = $data->bets[0]->bet->currency;
            $accountId = $data->bets[0]->accountId;
            $accountType = $data->bets[0]->accountType;
            $betTime = $data->betTime;
            $amountWin = $data->wins[0]->win->amount;
            $currencyWin = $data->wins[0]->win->currency;
            $winTime = $data->winTime;
            $winTransactionId = $data->winTransactionId;

            $hash = $data->hash;
            $EndGame = false;

            $isbonus = false;

            if ($accountType == "FREE_ROUND") {
                $isbonus = true;
            } else {
                $isbonus = false;
            }

            $AllowClosedRound = false; //Permitir Ronda Cerrada

            if (strpos($URI, "bet")) {
                $betTransactionId = (substr($URI, strpos($URI, "bet") + 4));


                $Thunderkick = new Thunderkick($operatorSessionToken, $hash, $playerExternalReference, $hashOriginal);

                if ($isbonus === true) {
                    $respuesta = ($Thunderkick->Debit($gameName, 0, $gameRoundId, $betTransactionId, $EndGame, json_encode($datos), $AllowClosedRound));
                } else {
                    $respuesta = ($Thunderkick->Debit($gameName, $amountBet, $gameRoundId, $betTransactionId, $EndGame, json_encode($datos), $AllowClosedRound));
                }


                print_r($respuesta);
            } elseif (strpos($URI, "win")) {
                $winTransactionId = (substr($URI, strpos($URI, "win") + 4));

                $Thunderkick = new Thunderkick($operatorSessionToken, $hash, $playerExternalReference, $hashOriginal);

                $respuesta = $Thunderkick->Credit($gameName, $amountWin, $gameRoundId, $winTransactionId, json_encode($datos), $isbonus, $EndGame, $AllowClosedRound, " ");

                print_r($respuesta);
            } elseif (strpos($URI, "rollbackBet")) {
                $rollbackTransactionId = (substr($URI, strpos($URI, "rollbackBet") + 12));

                $playerId = $data->playerId; //OK
                $playerExternalReference = $data->playerExternalReference; //OK
                $gameSessionToken = $data->gameSessionToken; //OK
                $operatorSessionToken = $data->operatorSessionToken; //OK
                $gameName = $data->gameName;//OK
                $gameRoundId = $data->gameRoundId;//OK
                $Amount = $data->betAmount->amount;//OK
                $currency = $data->betAmount->currency;//OK
                $rollbackTime = $data->rollbackTime;//OK
                $betTransactionId = $data->betTransactionId;//OK
                $betTime = $data->betTime;//OK
                $numberOfRetries = $data->numberOfRetries;//OK
                $distributionChannel = $data->distributionChannel;//OK
                $externalAccountId = $data->externalAccountId;//OK
                $accountType = $data->accountType;//OK
                $CancelEntireRound = true;

                $Thunderkick = new Thunderkick($operatorSessionToken, $hash, $playerExternalReference, $hashOriginal, $rollbackTransactionId);

                $respuesta = $Thunderkick->Rollback2($operatorSessionToken, $betTransactionId, $gameName, $playerExternalReference, $gameRoundId, $CancelEntireRound, $rollbackTransactionId, $Amount, json_encode($datos));

                print_r($respuesta);
            }

            $log = "";
            $log = $log . "/" . time();

            $log = $log . "\r\n" . "-------------------------" . "\r\n";
            $log = $log . ($respuesta);
        }
    } else {
        header("HTTP/1.1 401 unauthorized");
    }
}
