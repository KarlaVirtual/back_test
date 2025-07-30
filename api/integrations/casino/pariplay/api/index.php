<?php

/**
 * Este archivo contiene un script para procesar y manejar las solicitudes de la API del casino 'Pariplay'.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-11
 * @access     público
 *
 * Variables globales utilizadas en el script:
 *
 * @var mixed $_REQUEST     Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $_ENV         Variable superglobal que contiene variables de entorno configuradas para el script.
 * @var mixed $URI          Almacena la URI de la solicitud actual.
 * @var mixed $body         Contiene el cuerpo de la solicitud HTTP recibido.
 * @var mixed $data         Almacena los datos decodificados del cuerpo de la solicitud.
 * @var mixed $datos        Variable que almacena los datos procesados de la solicitud.
 * @var mixed $log          Variable utilizada para construir y almacenar registros de log.
 * @var mixed $response     Almacena la respuesta generada por las operaciones realizadas en el script.
 * @var mixed $hashOriginal Variable utilizada para almacenar un hash inicial vacío.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Pariplay;

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

header('Content-type: application/json');

$_ENV["enabledConnectionGlobal"] = 1;

$URI = $_SERVER['REQUEST_URI'];

$body = json_encode($_REQUEST);
$body = file_get_contents('php://input');

if ($body != "") {
    $data = json_decode($body);
}

$datos = $data;

$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . ($URI);
$log = $log . (http_build_query($_REQUEST));
$log = $log . json_encode($data);

$hashOriginal = '';

if ($data != '') {
    $UserName = $data->Account->UserName;
    $Password = $data->Account->Password;

    $Pariplay = new Pariplay('', '', '', '');
    $keys = $Pariplay->autchToken($data->PlayerId, $data->Token);
    $keys = explode("_", $keys);

    if ($UserName !== $keys[0] || $Password !== $keys[1]) {
        $return = array(
            "Balance" => 0.0,
            "BonusBalance" => 0.0,
            "Error" => [
                "ErrorCode" => 4,
            ],
        );
        $response = json_encode($return);
    } else {
        if (strpos($URI, "Authenticate") !== false) {
            $hash = $data->hash;
            $token = $data->Token;
            $PlayerId = $data->PlayerId;
            $PlatformType = $data->PlatformType;

            /* Procesamos */
            $Pariplay = new Pariplay($token, $hash, $PlayerId, $hashOriginal);
            $response = ($Pariplay->Authentication($token, $PlayerId, $PlatformType));
        } elseif (strpos($URI, "GetBalance") !== false) {
            $hash = $data->hash;
            $token = $data->Token;
            $providerId = $data->providerId;
            $externalPlayerId = $data->externalPlayerId;
            $PlayerId = $data->PlayerId;

            /* Procesamos */
            $Pariplay = new Pariplay($token, $hash, $externalPlayerId, $hashOriginal);
            $response = ($Pariplay->getBalance($token, $PlayerId));
        } else {
            $token = $data->Token;
            $hash = $data->hash;
            $GameCode = $data->GameCode;
            $PlayerId = $data->PlayerId;
            $RoundId = $data->RoundId;
            $TransactionId = $data->TransactionId;
            $Amount = floatval($data->Amount);
            $EndGame = $data->EndGame;
            $Feature = $data->Feature;
            $TransactionConfiguration = $data->TransactionConfiguration;
            $FeatureId = $data->FeatureId;
            $UsePoints = $data->UsePoints;
            $DebitAndCredit = false;

            if ($Feature == "BonusWin" || $Feature == "Offer") {
                $isbonus = true;
            } else {
                $isbonus = false;
            }

            $AllowClosedRound = false;
            $continue = true; //Variable que me permite continuar en caso de no entrar a ningun case del Switch

            switch ($TransactionConfiguration[0]) {
                case 2:
                    $Amount = -$Amount;
                    $Pariplay = new Pariplay($token, $hash, $PlayerId, $hashOriginal);
                    $response = $Pariplay->Credit($GameCode, $Amount, $RoundId, $TransactionId, json_encode($datos), $isbonus, $EndGame);
                    $continue = false;
                    break;

                case 3:

                    $Pariplay = new Pariplay($token, $hash, $PlayerId, $hashOriginal);
                    $response = $Pariplay->Credit($GameCode, $Amount, $RoundId, $TransactionId, json_encode($datos), $isbonus, $EndGame);

                    $respuestaMR = json_decode($response)->Error;
                    $respuestaMR = $respuestaMR->ErrorCode;
                    if ($respuestaMR != " " && $respuestaMR != null) {
                        $response = ($Pariplay->Debit($GameCode, 0, $RoundId, 'D' . $TransactionId, $EndGame, json_encode($datos), $AllowClosedRound));
                        $response = $Pariplay->Credit($GameCode, $Amount, $RoundId, $TransactionId, json_encode($datos), $isbonus, $EndGame);
                    }
                    $continue = false;
                    break;

                case 6:

                    $AllowClosedRound = true;
                    break;

                case 8:

                    $Pariplay = new Pariplay($token, $hash, $PlayerId, $hashOriginal);
                    $response = ($Pariplay->Debit($GameCode, 0, $RoundId, 'FS' . $TransactionId, $EndGame, json_encode($datos), $AllowClosedRound));
                    $response = $Pariplay->Credit($GameCode, $Amount, $RoundId, $TransactionId, json_encode($datos), $isbonus, $EndGame, $AllowClosedRound);
                    $continue = false;
                    break;
            }

            if (strpos($URI, "DebitAndCredit") !== false) {
                $DebitAmount = $data->DebitAmount;
                $CreditAmount = $data->CreditAmount;
                $CreditType = $data->CreditType;
                $TicketAmount = $data->TicketAmount;
                $DebitAndCredit = true;

                $Pariplay = new Pariplay($token, $hash, $PlayerId, $hashOriginal);

                if ($isbonus === true) {
                    $response = ($Pariplay->Debit($GameCode, 0, $RoundId, 'DC' . $TransactionId, $EndGame, json_encode($datos), $AllowClosedRound, $DebitAndCredit));
                } else {
                    $response = ($Pariplay->Debit($GameCode, $DebitAmount, $RoundId, 'DC' . $TransactionId, $EndGame, json_encode($datos), $AllowClosedRound, $DebitAndCredit));
                }

                if ($response != '') {
                    try {
                        $respuestaMR = json_decode($response)->Error;
                        $respuestaMR = $respuestaMR->ErrorCode;
                        if ($respuestaMR != " " && $respuestaMR != null) {
                            $response = $response;
                        } else {
                            $response = $Pariplay->Credit($GameCode, $CreditAmount, $RoundId, $TransactionId, json_encode($datos), $isbonus, $EndGame, $AllowClosedRound);
                        }

                        if ($respuestaMR != " " && $respuestaMR != null) {
                        } else {
                            $Pariplay->EndRound($token, $GameCode, $PlayerId, $RoundId, $TransactionId, $TransactionConfiguration, json_encode($datos));
                        }
                    } catch (Exception $e) {
                    }
                }
            } elseif (strpos($URI, "Debit") && $continue === true) {
                $Pariplay = new Pariplay($token, $hash, $PlayerId, $hashOriginal);

                if ($isbonus === true) {
                    $response = ($Pariplay->Debit($GameCode, 0, $RoundId, $TransactionId, $EndGame, json_encode($datos), $AllowClosedRound));
                } else {
                    $response = ($Pariplay->Debit($GameCode, $Amount, $RoundId, $TransactionId, $EndGame, json_encode($datos), $AllowClosedRound));
                }

                if ($EndGame === true) {
                    $Pariplay->EndRound($token, $GameCode, $PlayerId, $RoundId, $TransactionId, $TransactionConfiguration, json_encode($datos));
                }
            } elseif (strpos($URI, "Credit") && $continue === true) {
                $Pariplay = new Pariplay($token, $hash, $PlayerId, $hashOriginal);

                $response = $Pariplay->Credit($GameCode, $Amount, $RoundId, $TransactionId, json_encode($datos), $isbonus, $EndGame, $AllowClosedRound);
            } elseif (strpos($URI, "CancelTransaction")) {
                $RefTransactionId = $data->RefTransactionId; //Id de la transaccion que se quiere "Devolver" es decir la de Debit o Credit previo.
                $CancelEntireRound = $data->CancelEntireRound;
                $TransactionId = $data->TransactionId;
                $Reason = $data->Reason;
                $DebitAndCredit = false;

                $Pariplay = new Pariplay($token, $hash, $PlayerId, $hashOriginal);

                if ($CancelEntireRound === false) {
                    $response = $Pariplay->Rollback($token, 'DC' . $RefTransactionId, $GameCode, $PlayerId, $RoundId, $CancelEntireRound, 'DC' . $TransactionId, $Reason, $Amount, json_encode($datos), $DebitAndCredit);
                    $response = $Pariplay->Rollback($token, $RefTransactionId, $GameCode, $PlayerId, $RoundId, $CancelEntireRound, $TransactionId, $Reason, $Amount, json_encode($datos), $DebitAndCredit);
                } elseif ($CancelEntireRound === true) {
                    $response = $Pariplay->Rollback($token, $RefTransactionId, $GameCode, $PlayerId, $RoundId, $CancelEntireRound, $TransactionId, $Reason, $Amount, json_encode($datos), $DebitAndCredit);
                }
            } elseif ($UsePoints == true && strpos($URI, "AwardFreeRoundsPoints")) {
            } elseif (strpos($URI, "CreateToken")) {
                $Token = $data->Token;
                $PlayerId = $data->PlayerId;
                $GameCode = $data->GameCode;
                $FinancialMode = $data->FinancialMode;

                $Pariplay = new Pariplay($Token, $hash, $PlayerId, $hashOriginal);

                $response = $Pariplay->CreateToken($Token, $PlayerId, $GameCode, $FinancialMode, json_encode($datos));
            } elseif (strpos($URI, "EndGame")) {
                if ($response == '') {
                    $Pariplay = new Pariplay($token, $hash, $PlayerId, $hashOriginal);

                    $response = $Pariplay->EndRound($token, $GameCode, $PlayerId, $RoundId, $TransactionId, $TransactionConfiguration, json_encode($datos));
                }
            }
        }
    }

    $log = "";
    $log = $log . "/" . time();
    $log = $log . "\r\n" . "-------------------------" . "\r\n";
    $log = $log . ($response);
    print_r($response);
}
