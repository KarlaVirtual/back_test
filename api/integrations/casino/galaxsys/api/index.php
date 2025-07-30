<?php
/**
 * Este archivo contiene un script para manejar las solicitudes de la API del casino 'Galaxsys'.
 * Proporciona diferentes puntos de entrada para operaciones como autenticación, consulta de saldo,
 * apuestas, ganancias, reembolsos, y más.
 *
 * @category   API
 * @package    Integrations
 * @subpackage Casino
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-11
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $_REQUEST Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $_ENV     Activa o desactiva el modo de depuración ['debug'].
 * @var mixed $_ENV     Habilita la conexión global ["enabledConnectionGlobal"].
 * @var mixed $_ENV     Habilita el tiempo de espera para bloqueos ["ENABLEDSETLOCKWAITTIMEOUT"].
 * @var mixed $URI      Contiene la URI de la solicitud actual.
 * @var mixed $body     Contiene el cuerpo de la solicitud en formato JSON.
 * @var mixed $data     Contiene los datos decodificados del cuerpo de la solicitud.
 * @var mixed $datos    Alias para los datos decodificados de la solicitud.
 * @var mixed $response Contiene la respuesta generada por las operaciones de la API.
 */
require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\dto\ConfigurationEnvironment;
use Backend\integrations\casino\Galaxsys;

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

$_ENV["enabledConnectionGlobal"] = 1;
$_ENV["ENABLEDSETLOCKWAITTIMEOUT"] = 1;

header('Content-type: application/json');

$URI = $_SERVER['REQUEST_URI'];

$body = json_encode($_REQUEST);
$body = file_get_contents('php://input');

if ($body != "") {
    $data = json_decode($body);
}

$datos = $data;

$URI = explode('/', $URI);
$URI = $URI[oldCount($URI) - 1];

if (true) {
    if ($URI == "authenticate") {
        $operatorId = $data->operatorId;
        $timestamp = $data->timestamp;
        $signature = $data->signature;
        $token = $data->token;

        /* Procesamos */
        $Galaxsys = new Galaxsys($token, $signature);
        $response = $Galaxsys->Auth($operatorId, $token, $timestamp, $signature);
    } elseif ($URI == "getbalance") {
        $operatorId = $data->operatorId;
        $timestamp = $data->timestamp;
        $signature = $data->signature;
        $token = $data->token;
        $playerId = $data->playerId;
        $currencyId = $data->currencyId;

        /* Procesamos */
        $Galaxsys = new Galaxsys($token, $signature, $playerId);
        $response = $Galaxsys->getBalance($playerId, $operatorId, $token, $timestamp, $signature, $currencyId);
    } elseif ($URI == "refreshtoken") {
        $operatorId = $data->operatorId;
        $timestamp = $data->timestamp;
        $signature = $data->signature;
        $token = $data->token;
        $changeToken = $data->changeToken;
        $tokenLifeTime = $data->tokenLifeTime;
        $playerId = "";

        /* Procesamos */
        $Galaxsys = new Galaxsys($token, $signature, $playerId);
        $response = $Galaxsys->refreshToken($playerId, $changeToken, $token, $tokenLifeTime, $operatorId, $timestamp, $signature);
    } elseif ($URI == "bet") {
        $operatorId = $data->operatorId;
        $timestamp = $data->timestamp;
        $signature = $data->signature;
        $allOrNone = $data->allOrNone;
        $providerId = $data->providerId;
        $token = $data->items[0]->token;
        $playerId = $data->items[0]->playerId;
        $info = $data->items[0]->info;

        $Galaxsys = new Galaxsys($token, $signature, $playerId);
        $final = $Galaxsys->Generate($operatorId, $token, $timestamp, $signature, false, 1, false, $info);

        $hayError_ = false;
        if ($allOrNone == true) {
            $final_ = $Galaxsys->Generate($operatorId, $token, $timestamp, $signature, true, 1, false, $info);
            $final_ = json_decode($final_);
            foreach ($data->items as $i => $items) {
                $gameId = $items->gameId;
                $playerId = $items->playerId;
                $currencyId = $items->currencyId;

                $resG = $Galaxsys->Game($gameId);
                $resG = json_decode($resG);

                $resCur = $Galaxsys->Currenci($currencyId);
                $resCur = json_decode($resCur);

                $resUs = $Galaxsys->User($playerId);
                $resUs = json_decode($resUs);

                if ($final_->errorCode != 1 || $resG->errorCode != 1 || $resCur->errorCode != 1 || $resUs->errorCode != 1) {
                    $hayError_ = true;
                    break;
                }
            }
        }

        if ($final['errorCode'] == 1) {
            foreach ($data->items as $i => $items) {
                $token = $items->token;
                $playerId = $items->playerId;
                $gameId = $items->gameId;
                $roundId = $items->roundId;
                $txId = $items->txId;
                $operationType = $items->operationType;
                $changeBalance = $items->changeBalance;
                $currencyId = $items->currencyId;

                $betAmount = $items->betAmount;
                if ($changeBalance == false || $operationType == 2 || $operationType == 99
                    || ($changeBalance == true && $operationType == 30)
                    || $hayError_
                ) {
                    $betAmount = 0;
                }

                $ignoreExpiry = $items->ignoreExpiry;
                $info = $items->info;

                $isBonus = true;
                $isFreeSpin = false;
                if ($items->bonusTicketId != '' && $items->remainingFreeGameCount != '') {
                    $isBonus = false;
                    $isFreeSpin = true;
                }

                $respuesta = $Galaxsys->Debit($gameId, $betAmount, $roundId, 'D_' . $txId, json_encode($datos), $info, "", $playerId, $operatorId, $token, $timestamp, $signature, $currencyId, $ignoreExpiry, $allOrNone, false, $isFreeSpin);

                if (($isBonus) && ( ! $changeBalance || in_array($operationType, [2, 99, 30, 4, 25]))) {
                    $saldo = $Galaxsys->getBalance2('19', $info);
                    array_push($final['items'], $saldo['items']);
                } else {
                    if ($respuesta['items']['errorCode'] != 1) {
                        if ($respuesta['items']['errorCode'] == 14) {
                            $saldo = $Galaxsys->getBalance2($respuesta['items']['errorCode'], $info);
                            array_push($final['items'], $saldo['items']);
                        } elseif ($allOrNone) {
                            if ($hayError_) {
                                $final = $Galaxsys->Generate($operatorId, $token, $timestamp, $signature, false, 11, false, $info);
                            }
                            array_push($final['items'], $respuesta['items']);
                        } else {
                            if (in_array($respuesta['errorCode'], [5, 3, 16, 11, 4])) {
                                $saldo = $Galaxsys->getBalance2($respuesta['errorCode'], $info);
                                array_push($final['items'], $saldo['items']);
                            } else {
                                $final = $respuesta;
                            }
                        }
                    } else {
                        array_push($final['items'], $respuesta['items']);
                    }
                }
            }
        } elseif (in_array($final['errorCode'], [2, 15, 12])) {
            $saldo = $Galaxsys->getBalance2($final['errorCode'], $info);
            array_push($final['items'], $saldo['items']);
        }

        $response = $final;
    } elseif (strpos($URI, 'win') !== false) {
        $operatorId = $data->operatorId; //OK
        $timestamp = $data->timestamp; //OK
        $signature = $data->signature; //OK
        $allOrNone = $data->allOrNone; //OK
        $providerId = $data->providerId; //OK
        $playerId = $data->items[0]->playerId;
        $info = $data->items[0]->info;

        $Galaxsys = new Galaxsys("", $signature, $playerId);
        $final = $Galaxsys->Generate($operatorId, '00', $timestamp, $signature, false, 1, false, $info);

        if ($final['errorCode'] == 1) {
            foreach ($data->items as $i => $items) {
                $playerId = $items->playerId;
                $gameId = $items->gameId;
                $betTxId = $items->betTxId;
                $roundId = $items->roundId;
                if ($roundId == null) {
                    $roundId = $betTxId;
                }
                $txId = $items->txId;
                $operationType = $items->operationType;
                $betOperationType = $items->betOperationType;
                $currencyId = $items->currencyId;
                $winAmount = $items->winAmount;
                if ($allOrNone == true) {
                    $winAmount = 0;
                    $final = $Galaxsys->Generate($operatorId, '00', $timestamp, $signature, false, 11, false, $info);
                }
                $bonusTicketId = $items->bonusTicketId;
                $rake = $items->rake;
                $info = $items->info;
                $metadata = $items->metadata;
                $EndRound = $items->RoundFinished;

                $rounT = $items->roundId;

                if ($operationType != 99 && $operationType != 1) {
                    $isFreeSpin = false;
                    if ($items->bonusTicketId != '' && $items->remainingFreeGameCount != '') {
                        $isBonus = false;
                        $isFreeSpin = true;
                    }

                    $respuesta = $Galaxsys->Credit($gameId, $winAmount, $roundId, $txId, json_encode($datos), $isFreeSpin, $info, "", $rounT, $playerId, $currencyId, $ganar = 'si_', $EndRound);

                    $err = $respuesta['items']['errorCode'];
                    if ($err == 1) {
                        array_push($final['items'], $respuesta['items']);
                    } elseif ($respuesta['errorCode'] == 7) {
                        if ($betTxId == 'wrongBetTxId') {
                            $final = $respuesta;
                        } else {
                            if ($allOrNone == true) {
                                $final = $Galaxsys->Generate($operatorId, '00', $timestamp, $signature, false, 7, false, $info);
                            }
                            $err = $respuesta['errorCode'];
                            $saldo = $Galaxsys->getBalance2($err, $info);
                            array_push($final['items'], $saldo['items']);
                        }
                    } elseif ($err == 11 || $err == 15) {
                        $saldo = $Galaxsys->getBalance2($err, $info);
                        array_push($final['items'], $saldo['items']);
                    } elseif ($respuesta['errorCode'] != 1) {
                        $final = $respuesta;
                    } else {
                        $err = $respuesta['errorCode'];
                        $saldo = $Galaxsys->getBalance2($err, $info);
                        array_push($final['items'], $saldo['items']);
                    }
                } else {
                    $saldo = $Galaxsys->getBalance2(19, $info);
                    array_push($final['items'], $saldo['items']);
                }
            }
        } else {
            $err = $final['errorCode'];
            if ($err == 15 || $err == 12) {
                $saldo = $Galaxsys->getBalance2($err, $info);
                array_push($final['items'], $saldo['items']);
            }
        }

        $response = $final;
    } elseif ($URI == "betwin") {
        $operatorId = $data->operatorId;
        $timestamp = $data->timestamp;
        $signature = $data->signature;
        $allOrNone = $data->allOrNone;
        $providerId = $data->providerId;
        $playerId = $data->items[0]->playerId;
        $token = $data->items[0]->token;
        $betInfo = $data->items[0]->betInfo;

        $Galaxsys = new Galaxsys($token, $signature, $playerId);
        $final = $Galaxsys->Generate($operatorId, $token, $timestamp, $signature, false, 1, false, $betInfo);

        foreach ($data->items as $i => $items) {
            $token = $items->token;
            $playerId = $items->playerId;
            $gameId = $items->gameId;
            $roundId = $items->roundId;
            $txId = $items->txId;
            $betOperationType = $items->betOperationType;
            $winOperationType = $items->winOperationType;
            $currencyId = $items->currencyId;
            $betAmount = $items->betAmount;
            $winAmount = $items->winAmount;
            $changeBalance = $items->changeBalance;

            if ($changeBalance == false || $betOperationType == 99 || $winOperationType == 99 || $betOperationType == 2 || $winOperationType == 1) {
                $betAmount = 0;
            } elseif ($changeBalance == true && $betOperationType == 30) {
                $betAmount = 0;
            } else {
                if ($allOrNone == true) {
                    $betAmount = 0;
                    $winAmount = 0;
                }
            }

            $ignoreExpiry = $items->ignoreExpiry;
            $bonusTicketId = $items->bonusTicketId;
            $betInfo = $items->betInfo;
            $winInfo = $items->winInfo;
            $metadata = $items->metadata;
            $IsCombineRounds = $items->IsCombineRounds;
            $rounT = $items->roundId;

            $respuesta = $Galaxsys->Debit($gameId, $betAmount, $roundId, 'D_' . $txId, json_encode($datos), $betInfo, $winInfo, $playerId, $operatorId, $token, $timestamp, $signature, $currencyId, $ignoreExpiry, $allOrNone);

            if ($respuesta['items']['errorCode'] == 1) {
                $respuesta = $Galaxsys->Credit($gameId, $winAmount, $roundId, $txId, json_encode($datos), false, $betInfo, $winInfo, $rounT, $playerId, $currencyId);
            }

            if (($changeBalance == false || $betOperationType == 99 || $winOperationType == 99 || $betOperationType == 2 || $winOperationType == 1) && ($betOperationType != 30) && ($betOperationType != 4) && ($betOperationType != 25)) {
                $saldo = $Galaxsys->getBalance2('19', $betInfo);
                array_push($final['items'], $saldo['items']);
            } elseif ($changeBalance == true && $betOperationType == 30) {
                $saldo = $Galaxsys->getBalance2('19', $betInfo);
                array_push($final['items'], $saldo['items']);
            } elseif ($changeBalance == true && $betOperationType == 4) {
                $saldo = $Galaxsys->getBalance2('19', $betInfo);
                array_push($final['items'], $saldo['items']);
            } elseif ($changeBalance == true && $betOperationType == 25) {
                $saldo = $Galaxsys->getBalance2('19', $betInfo);
                array_push($final['items'], $saldo['items']);
            } else {
                $err = $respuesta['items']['errorCode'];
                if ($err == 11 || $err == 5) {
                    if ($allOrNone == true) {
                        $final = $Galaxsys->Generate($operatorId, $token, $timestamp, $signature, false, 11, false, $info);
                    }
                    $saldo = $Galaxsys->getBalance2($err, $betInfo);
                    array_push($final['items'], $saldo['items']);
                } elseif ($err != 1) {
                    $err = $respuesta['errorCode'];
                    if ($err == 5 || $err == 3 || $err == 16 || $err == 11 || $err == 4) {
                        $saldo = $Galaxsys->getBalance2($err, $betInfo);
                        array_push($final['items'], $saldo['items']);
                    } else {
                        $final = $respuesta;
                    }
                } else {
                    array_push($final['items'], $respuesta['items']);
                }
            }
        }

        $response = $final;
    } elseif ($URI == "refund" || strpos($URI, 'refund') !== false) {
        $operatorId = $data->operatorId;
        $timestamp = $data->timestamp;
        $signature = $data->signature;
        $allOrNone = $data->allOrNone;
        $providerId = $data->providerId;
        $playerId = $data->items[0]->playerId;
        $info = $data->items[0]->info;

        $Galaxsys = new Galaxsys("", $signature, $playerId);

        $final = $Galaxsys->Generate($operatorId, '00', $timestamp, $signature, false, 1, false, $info);

        foreach ($data->items as $i => $items) {
            $playerId = strval($items->playerId);
            $roundId = $items->roundId;
            $txId = $items->txId;
            $originalTxId = $items->originalTxId;
            if ($roundId == null || $roundId == '') {
                $roundId = $originalTxId;
            }
            $refundRound = $items->refundRound;
            $bonusTicketId = $items->bonusTicketId;
            $info = $items->info;

            $CancelEntireRound = false;
            if ($originalTxId == null || $originalTxId == '') {
                $CancelEntireRound = true;
            }

            $respuesta = $Galaxsys->Rollback("", $roundId, 'D_' . $originalTxId, $playerId, json_encode($datos), $txId, $info, $CancelEntireRound, $refundRound, $allOrNone, $originalTxId);

            if ($refundRound == true) {
                $Galaxsys->EndRound("", "", $playerId, $roundId, $txId, "", json_encode($datos), $Estado_ = 'I');
            } else {
                $Galaxsys->EndRound("", "", $playerId, $roundId, $txId, "", json_encode($datos), $Estado_ = 'A');
            }

            $err = $respuesta['items']['errorCode'];

            if ($err != 1) {
                $err = $respuesta['errorCode'];
                $final = $Galaxsys->Generate($operatorId, '00', $timestamp, $signature, false, $err, false, $info);
                if ($err == 20 || $err == 14) {
                    $final = $final;
                } elseif ($err == 8) {
                    $final = $respuesta;
                } elseif ($err == 7) {
                    $saldo = $Galaxsys->getBalance2($err, $info);
                    array_push($final['items'], $saldo['items']);
                } else {
                    array_push($final['items'], $respuesta['items']);
                }
            } else {
                $final = $Galaxsys->Generate($operatorId, '00', $timestamp, $signature, false, $err, false, $info);
                array_push($final['items'], $respuesta['items']);
            }
        }

        $response = $final;
    } elseif ($URI == "amend") {
        $operatorId = $data->operatorId;
        $timestamp = $data->timestamp;
        $signature = $data->signature;
        $allOrNone = $data->allOrNone;
        $providerId = $data->providerId;
        $playerId = $data->items[0]->playerId;
        $info = $data->items[0]->info;

        $Galaxsys = new Galaxsys("", $signature, $playerId);
        $final = $Galaxsys->Generate($operatorId, '00', $timestamp, $signature, false, 1, false, $info);

        if ($final['errorCode'] == 1) {
            foreach ($data->items as $i => $items) {
                $playerId = $items->playerId;
                $gameId = $items->gameId;
                $roundId = $items->roundId;
                $txId = $items->txId;
                $operationType = $items->operationType;
                $changeBalance = $items->changeBalance;
                $winTxId = $items->winTxId;
                $winOperationType = $items->winOperationType;
                $currencyId = $items->currencyId;
                $amendAmount = $items->amendAmount;
                if ($allOrNone == true) {
                    $amendAmount = 0;
                }
                $bonusTicketId = $items->bonusTicketId;
                $info = $items->info;
                $metadata = $items->metadata;

                if ($operationType != 99 && $operationType != 2) {
                    $respuesta = $Galaxsys->Amend($gameId, $amendAmount, $roundId, $txId, json_encode($datos), false, $info, $operationType, $playerId);
                    $err = $respuesta['errorCode'];

                    if ($err == 11) {
                        $saldo = $Galaxsys->getBalance2($err, $info);
                        array_push($final['items'], $saldo['items']);
                    } elseif ($err == 7) {
                        $saldo = $Galaxsys->getBalance2($err, $info);
                        array_push($final['items'], $saldo['items']);
                    } elseif ($err == 4) {
                        $return = array(
                            "items" => array(
                                "balance" => 0,
                                "errorCode" => $err
                            )
                        );
                        array_push($final['items'], $return['items']);
                    } else {
                        array_push($final['items'], $respuesta['items']);
                    }
                } else {
                    $saldo = $Galaxsys->getBalance2(19, $info);
                    array_push($final['items'], $saldo['items']);
                }
            }
        } else {
            $err = $final['errorCode'];
            if ($err == 15 || $err == 12) {
                $saldo = $Galaxsys->getBalance2($err, $info);
                array_push($final['items'], $saldo['items']);
            }
        }

        $response = $final;
    } elseif ($URI == "checktxstatus") {
        $operatorId = $data->operatorId;
        $timestamp = $data->timestamp;
        $signature = $data->signature;
        $providerId = $data->providerId;
        $externalTxId = $data->externalTxId;
        $providerTxId = $data->providerTxId;

        $Galaxsys = new Galaxsys("", $signature);
        $final = $Galaxsys->Generate($operatorId, '00', $timestamp, $signature, false, 1, false, 00);

        if ($final['errorCode'] == 1) {
            $respuesta = $Galaxsys->CheckTxStatus($externalTxId, $providerTxId, json_encode($datos), $signature, $timestamp);
            $final = $respuesta;
        }

        $response = $final;
    } elseif ($URI == "charge") {
        $operatorId = $data->operatorId;
        $timestamp = $data->timestamp;
        $signature = $data->signature;
        $providerId = $data->providerId;
        $token = $data->token;
        $playerId = $data->playerId;
        $gameId = $data->gameId;
        $txId = $data->txId;
        $operationType = $data->operationType;
        $currencyId = $data->currencyId;
        $chargeAmount = $data->chargeAmount;
        $changeBalance = $data->changeBalance;
        $metadata = $data->metadata;

        $Galaxsys = new Galaxsys($token, $signature, $playerId);
        $final = $Galaxsys->Generate($operatorId, $token, $timestamp, $signature, false, 1, false, 00);

        if ($final['errorCode'] == 1) {
            if (($operationType != 1 && $changeBalance == true) && ($operationType != 99)) {
                $respuesta = $Galaxsys->Debit($gameId, $chargeAmount, $txId, 'D_' . $txId, json_encode($datos), "", "", $playerId, $operatorId, $token, $timestamp, $signature, $currencyId, "", "", $ischarge = true);

                $err = $respuesta['errorCode'];
                $sig = $final['signature'];
                $tim = $final['timestamp'];
                if ($err == 3 || $err == 11 || $err == 16 || $err == 4 || $err == 5) {
                    $respuesta = $Galaxsys->getBalance3($err, $sig, $tim);
                    $final = $respuesta;
                } elseif ($err == 8) {
                    $final = $respuesta;
                } else {
                    $final = $respuesta;
                }
            } else {
                $final = $Galaxsys->getBalance3(19, $signature, $timestamp, 'D_' . $txId);
            }
        } else {
            $err = $final['errorCode'];
            $sig = $final['signature'];
            $tim = $final['timestamp'];
            if ($err == 15 || $err == 12 || $err == 2) {
                $respuesta = $Galaxsys->getBalance3($err, $sig, $tim);
                $final = $respuesta;
            }
        }

        $response = $final;
    } elseif ($URI == "promowin") {
        $operatorId = $data->operatorId;
        $timestamp = $data->timestamp;
        $signature = $data->signature;
        $providerId = $data->providerId;
        $playerId = $data->playerId;
        $gameId = $data->gameId;
        $bonusTicketId = $data->bonusTicketId;
        $operationType = $data->operationType;
        $promoWinAmount = $data->promoWinAmount;
        $currencyId = $data->currencyId;
        $txId = $data->txId;
        $metadata = $data->metadata;

        if ($gameId == null) {
            $gameId = 'prov';
        }

        $Galaxsys = new Galaxsys("", $signature, $playerId);
        $final = $Galaxsys->Generate($operatorId, '00', $timestamp, $signature, false, 1, false, 00);

        if ($operationType != 99 && $operationType != 2) {
            if ($final['errorCode'] == 1) {
                $final = $Galaxsys->Debit($gameId, 0, $txId, 'D_' . $txId, json_encode($datos), "", "", $playerId, $operatorId, "", $timestamp, $signature, $currencyId, "", "");

                $err = $final['errorCode'];
                if ($err == 1) {
                    $final = $Galaxsys->Credit($gameId, $promoWinAmount, $txId, $txId, json_encode($datos), false, "", "promowin", "", $playerId, $currencyId, "si_");
                } elseif ($err == 8) {
                    $final = $Galaxsys->getBalance3($err, $signature, $timestamp, 'D_' . $txId);
                }
            }
        } else {
            $final = $Galaxsys->getBalance3(19, $signature, $timestamp, 'D_' . $txId);
        }

        $response = $final;
    }
}

echo json_encode($response);




