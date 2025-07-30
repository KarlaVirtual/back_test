<?php

use Backend\sql\Transaction;
use Backend\dto\BonoInterno;
use Backend\dto\LogroReferido;
use Backend\mysql\LogroReferidoMySqlDAO;
use Backend\dto\ItTicketEnc;
use Backend\dto\ItTicketDet;
use Backend\dto\ItTicketEncInfo1;
use Backend\mysql\ItTicketEncInfo1MySqlDAO;
use Backend\dto\UsuarioReferenteResumen;
use Backend\mysql\TransjuegoInfoMySqlDAO;
use Backend\dto\TransjuegoInfo;



/**
 * Clase 'CronJobLogrosReferidos'
 *
 *
 *
 *
 * Ejemplo de uso:
 *
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class CronJobLogrosReferidos
{


    public function __construct()
    {
    }

    public function execute()
    {


        $initialTransaction = new Transaction();
        $BonoInterno = new BonoInterno();


//Truncando la ejecución de futuros hilos
        $truncateTransaction = new Transaction();
        $executionId = rand(10000, 99999);
        $temporalType = 'REFERIDO_' . $executionId;
        $sql = "UPDATE proceso_interno2 set tipo = '" . $temporalType . "' WHERE tipo = 'REFERIDO'";
        $sqlProcessConcluded = "UPDATE proceso_interno2 set tipo = 'REFERIDO' WHERE tipo = '" . $temporalType . "'";
        $BonoInterno->execQuery($truncateTransaction, $sql);
        $truncateTransaction->commit();

//Conociendo información de la última ejecución del CRON
        $sql = "SELECT * FROM proceso_interno2 WHERE tipo= '" . $temporalType . "'";
        $ProcesoInterno = $BonoInterno->execQuery($initialTransaction, $sql);
        $ultimaEjecucion = $ProcesoInterno[0]->{'proceso_interno2.fecha_ultima'};

        if (empty($ultimaEjecucion)) {
            $BonoInterno->execQuery($initialTransaction, $sqlProcessConcluded);
            $initialTransaction->commit();
            exit();
        }

        $fechaL1 = date('Y-m-d H:i:00', strtotime($ultimaEjecucion . '+1 minute'));
        $fechaL2 = date('Y-m-d H:i:59', strtotime($ultimaEjecucion . '+2 minute'));
        if ($fechaL2 >= date('Y-m-d H:i:00')) {
            $BonoInterno->execQuery($initialTransaction, $sqlProcessConcluded);
            $initialTransaction->commit();
            exit();
        }

        try {
            $log = "\r\n" . "-------------------------" . "\r\n";
            $log = $log . "Inicia cronLogrosReferidos: " . date('Y-m-d H:i:s');
            fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

        } catch (Exception $e) {
        }

        /** Validando condición CONDMINFIRSTDEPOSITREFERRED*/
        try {
            $firstDepositTransaction = new Transaction();

            $sql = "select mandante_detalle.manddetalle_id, logro_referido.logroreferido_id, logro_referido.usuid_referido, usuario.fecha_primerdeposito, usuario.monto_primerdeposito, mandante_detalle.valor from clasificador inner join mandante_detalle on (clasificador.clasificador_id = mandante_detalle.tipo) inner join logro_referido on (mandante_detalle.manddetalle_id = logro_referido.tipo_condicion) inner join usuario on (logro_referido.usuid_referido = usuario.usuario_id) inner join usuario_mandante on (usuario.usuario_id = usuario_mandante.usuario_mandante) inner join mandante_detalle as mandante_detalle_estado on (mandante_detalle_estado.mandante = usuario_mandante.mandante and mandante_detalle_estado.pais_id = usuario_mandante.pais_id) inner join clasificador as clasificador_estado on (mandante_detalle_estado.tipo = clasificador_estado.clasificador_id) where clasificador_estado.abreviado = 'ACEPTAREFERIDO' and mandante_detalle_estado.estado = 'A' and mandante_detalle_estado.valor = 1 and clasificador.abreviado = 'CONDMINFIRSTDEPOSITREFERRED' and usuario.fecha_primerdeposito between '" . $fechaL1 . "' and '" . $fechaL2 . "' and logro_referido.estado = 'P' and logro_referido.estado_grupal = 'P' and (logro_referido.fecha_expira is null or date_format(logro_referido.fecha_expira,'%Y-%m-%d') >= date_format('" . $fechaL2 . "','%Y-%m-%d'))";
            $firstDepositReferreds = $BonoInterno->execQuery($firstDepositTransaction, $sql);

            $logroReferidoNewStatus = array_map(function ($firstDepoReferred) {
                $logroNewStatusInfo = [];
                $logroNewStatusInfo['id'] = $firstDepoReferred->{'logro_referido.logroreferido_id'};
                $logroNewStatusInfo['valor'] = $firstDepoReferred->{'usuario.monto_primerdeposito'};
                $logroNewStatusInfo['estado'] = $firstDepoReferred->{'mandante_detalle.valor'} <= $firstDepoReferred->{'usuario.monto_primerdeposito'} ? 'C' : 'F';
                return $logroNewStatusInfo;
            }, $firstDepositReferreds);

            //Actualización estado del logro
            foreach ($logroReferidoNewStatus as $logroUpdateInfo) {
                $LogroReferido = new LogroReferido($logroUpdateInfo['id']);
                $LogroReferido->setValorCondicion($logroUpdateInfo['valor']);
                $LogroReferido->setFechaUso(date('Y-m-d H:i:s'));
                $LogroReferido->setEstado($logroUpdateInfo['estado']);
                $LogroReferido->actualizarLogrosAgrupados($firstDepositTransaction, $LogroReferido);
            }

            $firstDepositTransaction->commit();
        } catch (Exception $e) {
        }
        /** Final validación condición CONDMINFIRSTDEPOSITREFERRED*/


        /** Validación condición CONDMINBETREFERRED*/
#CRON Apuestas deportivas
        function isInItTicketInfoReferidos(int $ticketId, Transaction $transaction): bool
        {
            $BonoInterno = new BonoInterno();
            $sql = "SELECT COUNT(1) AS lastLog FROM it_ticket_enc_info1 WHERE tipo = 'REFERIDO' AND ticket_id = $ticketId";
            $ticketInfoLogs = $BonoInterno->execQuery($transaction, $sql);
            $lastLog = $ticketInfoLogs[0]->{'.lastLog'};

            return $lastLog > 0;
        }

        try {
            $sportBookMinBetTransaction = new Transaction();
            $sql = "select logro_referido.logroreferido_id, mandante_detalle.mandante, mandante_detalle.pais_id, usuario.usuario_id, logro_referido.tipo_premio, logro_referido.tipo_condicion, logro_referido.valor_condicion, mandante_detalle.valor, if(logro_referido.fecha_uso is not null, logro_referido.fecha_uso, logro_referido.fecha_crea) as ultima_revision, it_ticket_enc.it_ticket_id, it_ticket_enc.bet_mode, it_ticket_enc.bet_status, it_ticket_enc.vlr_apuesta, it_ticket_enc.ticket_id from it_ticket_enc inner join usuario on (it_ticket_enc.usuario_id = usuario.usuario_id) inner join usuario_mandante on (usuario.usuario_id = usuario_mandante.usuario_mandante) inner join logro_referido on (usuario.usuario_id = logro_referido.usuid_referido) inner join mandante_detalle on (logro_referido.tipo_condicion = mandante_detalle.manddetalle_id) inner join clasificador on (mandante_detalle.tipo = clasificador.clasificador_id) inner join mandante_detalle as mandante_detalle_estado on (mandante_detalle_estado.mandante = usuario_mandante.mandante and mandante_detalle_estado.pais_id = usuario_mandante.pais_id) inner join clasificador as clasificador_estado on (mandante_detalle_estado.tipo = clasificador_estado.clasificador_id) where clasificador_estado.abreviado = 'ACEPTAREFERIDO' and mandante_detalle_estado.estado = 'A' and mandante_detalle_estado.valor = 1 and it_ticket_enc.fecha_cierre_time between '" . $fechaL1 . "' and '" . $fechaL2 . "' and it_ticket_enc.bet_mode = 'PreLive' and it_ticket_enc.bet_status not in ('T', 'R', 'M') and it_ticket_enc.eliminado = 'N' and clasificador.abreviado = 'CONDMINBETREFERRED' and logro_referido.estado = 'P' and logro_referido.estado_grupal = 'P' and (logro_referido.fecha_expira is null or date_format(logro_referido.fecha_expira,'%Y-%m-%d') >= date_format('" . $fechaL2 . "','%Y-%m-%d')) order by mandante_detalle.mandante, mandante_detalle.pais_id, usuario.usuario_id desc, logro_referido.tipo_premio desc";
            $sportbookBets = $BonoInterno->execQuery($sportBookMinBetTransaction, $sql);

            $lastReviewedPartner_Country = null;
            $minSelPrice = null;
            $validBets = array_filter($sportbookBets, function ($bet) use ($BonoInterno, $sportBookMinBetTransaction, $lastReviewedPartner_Country, $minSelPrice) {
                //Verificando validez de las apuestas
                $validBet = true;
                if ($bet->{'it_ticket_enc.bet_mode'} != 'PreLive') $validBet = false;
                elseif ($validBet && in_array($bet->{'it_ticket_enc.bet_status'}, ['T', 'R', 'M'])) $validBet = false;
                elseif (isInItTicketInfoReferidos($bet->{'it_ticket_enc.ticket_id'}, $sportBookMinBetTransaction)) $validBet = false;
                else {
                    //Consultando cuota mínima definida para cada programa de referidos
                    $currentReviewedPartner_Country = $bet->{'mandante_detalle.mandante'} . '_' . $bet->{'mandante_detalle.pais_id'};
                    if ($currentReviewedPartner_Country != $lastReviewedPartner_Country) {
                        $sql = "select mandante_detalle.valor from clasificador inner join mandante_detalle on (clasificador.clasificador_id = mandante_detalle.tipo) where mandante_detalle.estado = 'A' and mandante_detalle.mandante = " . $bet->{'mandante_detalle.mandante'} . " and mandante_detalle.pais_id = " . $bet->{'mandante_detalle.pais_id'} . " and clasificador.abreviado = 'MINSELPRICEREFERRED'";
                        $minSelPrice = $BonoInterno->execQuery($sportBookMinBetTransaction, $sql);
                        $minSelPrice = $minSelPrice[0]->{'mandante_detalle.valor'};
                        $minSelPrice = (float)$minSelPrice == null || $minSelPrice == '' ? 100 : $minSelPrice;
                        $lastReviewedPartner_Country = $currentReviewedPartner_Country;
                    }

                    //Verificando que apuestas cumplan con la cuota mínima definida para cada programa de referidos
                    $sql = "select it_ticket_det.it_ticketdet_id, it_ticket_det.logro from it_ticket_det where ticket_id = '" . $bet->{'it_ticket_enc.ticket_id'} . "'";
                    $betDetails = $BonoInterno->execQuery($sportBookMinBetTransaction, $sql);
                    foreach ($betDetails as $betDetail) {
                        if (floatval($betDetail->{'it_ticket_det.logro'}) < $minSelPrice) $validBet = false;
                    }
                }

                if ($validBet) return $bet;
            });
            $validBets = array_values($validBets);

            $betValue = 0;
            $currentBet = 0;
            $countedBets = 0;
            $goalValue = 0;
            $usedTickets = [];
            $ItTicketEncInfo1MySqlDAO = new ItTicketEncInfo1MySqlDAO($sportBookMinBetTransaction);
            for ($i = 0; $i <= count($validBets); $i += 1) {
                //Sumando y cambiando estado de los logros
                if (!empty($logroReferidoId) && $logroReferidoId != $validBets[$i]->{'logro_referido.logroreferido_id'}) {
                    $LogroReferido = new LogroReferido($logroReferidoId);
                    $LogroReferido->setValorCondicion($betValue);
                    $LogroReferido->setFechaUso(date('Y-m-d H:i:s'));
                    $LogroReferido->setEstado($betValue < $goalValue ? 'P' : 'C');
                    $LogroReferido->actualizarLogrosAgrupados($sportBookMinBetTransaction, $LogroReferido);

                    //Marcando saldo en resúmenes
                    if ($currentBet) {
                        $UsuarioReferenteResumen = new UsuarioReferenteResumen();
                        $UsuarioReferenteResumen->registrarResumenApuesta($sportBookMinBetTransaction, $currentBet, 'REFERIDO', $LogroReferido->getUsuidReferido(), 'SPORTBOOKBETSVALUE', $countedBets);
                    }

                    //Previniendo suma duplicada de transacciones en resumenes
                    $logroReferidoId = 0;
                }

                if (empty($logroReferidoId) || $logroReferidoId != $validBets[$i]->{'logro_referido.logroreferido_id'}) {
                    //Almacenando logroId en la iteración, valor base y valor objetivo
                    $logroReferidoId = $validBets[$i]->{'logro_referido.logroreferido_id'};
                    $betValue = $validBets[$i]->{'logro_referido.valor_condicion'} ?? 0;
                    $goalValue = $validBets[$i]->{'mandante_detalle.valor'};
                    $currentBet = 0;
                    $countedBets = 0;
                }

                //Se valida si el ticket ya fue sumado anteriormente para el informe de apuestas realizadas por el referido, previniendo sumas duplicadas
                if (!in_array($validBets[$i]->{'it_ticket_enc.ticket_id'}, $usedTickets)) {
                    array_push($usedTickets, $validBets[$i]->{'it_ticket_enc.ticket_id'});
                    $currentBet += $validBets[$i]->{'it_ticket_enc.vlr_apuesta'};
                    $countedBets++;
                } else continue;

                //Acumulando valor de las apuestas
                $betValue += $validBets[$i]->{'it_ticket_enc.vlr_apuesta'};


                if ($i < count($validBets)) {
                    //Dejando registro en it_ticket_enc_info1
                    $ItTicketEncInfo1 = new ItTicketEncInfo1();
                    $ItTicketEncInfo1->ticketId = $validBets[$i]->{'it_ticket_enc.ticket_id'};
                    $ItTicketEncInfo1->tipo = 'REFERIDO';
                    $ItTicketEncInfo1->valor = $validBets[$i]->{'logro_referido.logroreferido_id'};
                    $ItTicketEncInfo1->fechaCrea = date('Y-m-d H:i:s');
                    $ItTicketEncInfo1->fechaModif = date('Y-m-d H:i:s');
                    $ItTicketEncInfo1MySqlDAO->insert($ItTicketEncInfo1);
                }
            }

            $sportBookMinBetTransaction->commit();
        } catch (Exception $e) {
        }


#CRON Apuestas de casino
        function isInTransJuegoInfoReferidos(int $transApiId, Transaction $transaction): bool
        {
            $BonoInterno = new BonoInterno();
            $sql = "SELECT COUNT(1) AS lastLog FROM transjuego_info WHERE tipo = 'REFERIDO' AND transapi_id = $transApiId";
            $transJuegoLogs = $BonoInterno->execQuery($transaction, $sql);
            $lastLog = $transJuegoLogs[0]->{'.lastLog'};

            return $lastLog > 0;

        }

        try {
            $casinoMinBetTransaction = new Transaction();
            $sql = "select mandante_detalle.valor, logro_referido.logroreferido_id,logro_referido.usuid_referido, logro_referido.tipo_premio, logro_referido.valor_condicion, transaccion_juego.transjuego_id, transaccion_juego.producto_id, transaccion_juego.valor_ticket, logro_referido.usuid_referente, usuario.pais_id, usuario.mandante, transaccion_api.transapi_id from clasificador inner join mandante_detalle on (clasificador.clasificador_id = mandante_detalle.tipo) inner join logro_referido on (mandante_detalle.manddetalle_id = logro_referido.tipo_condicion) inner join usuario on (logro_referido.usuid_referido = usuario.usuario_id) inner join usuario_mandante on (usuario.usuario_id = usuario_mandante.usuario_mandante) inner join transaccion_juego on (usuario_mandante.usumandante_id = transaccion_juego.usuario_id) inner join transaccion_api on (transaccion_juego.transaccion_id = transaccion_api.transaccion_id) inner join mandante_detalle as mandante_detalle_estado on (mandante_detalle_estado.mandante = usuario_mandante.mandante and mandante_detalle_estado.pais_id = usuario_mandante.pais_id) inner join clasificador as clasificador_estado on (mandante_detalle_estado.tipo = clasificador_estado.clasificador_id) where clasificador_estado.abreviado = 'ACEPTAREFERIDO' and mandante_detalle_estado.estado = 'A' and mandante_detalle_estado.valor = 1 and clasificador.abreviado = 'CONDMINBETREFERRED' and logro_referido.estado_grupal = 'P' and logro_referido.estado = 'P' and (logro_referido.fecha_expira is null or date_format(logro_referido.fecha_expira,'%Y-%m-%d') >= date_format('" . $fechaL2 . "','%Y-%m-%d')) and transaccion_juego.fecha_crea between '" . $fechaL1 . "' and '" . $fechaL2 . "' order by logro_referido.usuid_referente asc, logro_referido.usuid_referido desc, logro_referido.tipo_premio desc";
            $casinoBets = $BonoInterno->execQuery($casinoMinBetTransaction, $sql);


            $logroReferidoId = null;
            $productoId = null;
            $referente = null;
            $betValue = null;
            $currentBet = 0;
            $countedBets = 0;
            $goalValue = null;
            $validDuo = null;
            $gameCategories = [];
            $excludedCategories = [];
            $usedTransactions = [];
            $countCasinoBets = count($casinoBets);
            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO($casinoMinBetTransaction);
            for ($i = 0; $i <= $countCasinoBets; $i += 1) {
                //Iterando transacciones/apuestas
                if (!empty($logroReferidoId) && $logroReferidoId != $casinoBets[$i]->{'logro_referido.logroreferido_id'}) {
                    //Sumando, cambiando y almacenando estado de los logros
                    $LogroReferido = new LogroReferido($logroReferidoId);
                    $LogroReferido->setValorCondicion($betValue);
                    $LogroReferido->setFechaUso(date('Y-m-d H:i:s'));
                    $LogroReferido->setEstado($betValue < $goalValue ? 'P' : 'C');
                    $LogroReferido->actualizarLogrosAgrupados($casinoMinBetTransaction, $LogroReferido);

                    //Marcando saldo en resúmenes
                    if ($currentBet) {
                        $UsuarioReferenteResumen = new UsuarioReferenteResumen();
                        $UsuarioReferenteResumen->registrarResumenApuesta($casinoMinBetTransaction, $currentBet, 'REFERIDO', $LogroReferido->getUsuidReferido(), 'CASINOBETSVALUE', $countedBets);
                    }

                    //Previniendo suma duplicada de transacciones en resumenes
                    $logroReferidoId = 0;
                }

                if ($i < $countCasinoBets && isInTransJuegoInfoReferidos($casinoBets[$i]->{'transaccion_api.transapi_id'}, $casinoMinBetTransaction)) continue;

                if ($productoId != $casinoBets[$i]->{'transaccion_juego.producto_id'} && $i < $countCasinoBets) {
                    //Consultando las categorías a las que pertenece el juego
                    $isCasinoSlot = true;
                    $productoId = $casinoBets[$i]->{'transaccion_juego.producto_id'};
                    $sql = "select categoria_mandante.catmandante_id,categoria_mandante.tipo from producto_mandante inner join producto on (producto_mandante.producto_id = producto.producto_id) inner join categoria_mandante on (producto.categoria_id = categoria_mandante.catmandante_id) where categoria_mandante.mandante = -1 and categoria_mandante.estado = 'A' and producto_mandante.prodmandante_id = " . $productoId;
                    $gameCategories = $BonoInterno->execQuery($casinoMinBetTransaction, $sql);
                    $gameCategories = array_map(function ($game) use ($isCasinoSlot) {
                        if ($game->{'categoria_mandante.tipo'} != 'CASINO') $isCasinoSlot = false;
                        return $game->{'categoria_mandante.catmandante_id'};
                    }, $gameCategories);

                    if (!$isCasinoSlot) continue;
                }

                if ($referente != $casinoBets[$i]->{'logro_referido.usuid_referente'} && $i < $countCasinoBets) {
                    //Consultando categorías de casino excluidas por partner para el referente/programa de referidos
                    $referente = $casinoBets[$i]->{'logro_referido.usuid_referente'};
                    $sql = "select mandante_detalle.valor from clasificador inner join mandante_detalle on (clasificador.clasificador_id = mandante_detalle.tipo) where clasificador.abreviado = 'EXCLUDEDCASINOCATEGORYREFERS' and mandante_detalle.estado = 'A' and mandante_detalle.mandante = " . $casinoBets[$i]->{'usuario.mandante'} . " and mandante_detalle.pais_id = " . $casinoBets[$i]->{'usuario.pais_id'};
                    $excludedCategories = $BonoInterno->execQuery($casinoMinBetTransaction, $sql);
                    $excludedCategories = explode(',', $excludedCategories[0]->{'mandante_detalle.valor'});
                }

                $proposedDuo = null;
                if ($i < $countCasinoBets) $proposedDuo = $productoId . '_' . $referente;
                if ($validDuo != $proposedDuo) {
                    //Verificando que el juego no pertenezca a una categoría prohibida
                    foreach ($gameCategories as $category) {
                        if (in_array($category, $excludedCategories)) continue 2;
                    }
                    //Aprobando duo verificado
                    $validDuo = $proposedDuo;
                }

                if ($logroReferidoId != $casinoBets[$i]->{'logro_referido.logroreferido_id'} && $i < $countCasinoBets) {
                    //Almacenando logroId en la iteración, valor base y valor objetivo
                    $logroReferidoId = $casinoBets[$i]->{'logro_referido.logroreferido_id'};
                    $betValue = $casinoBets[$i]->{'logro_referido.valor_condicion'} ?? 0;
                    $goalValue = $casinoBets[$i]->{'mandante_detalle.valor'};
                    $currentBet = 0;
                    $countedBets = 0;
                }

                //Acumulando valor de las apuestas
                $betValue += $casinoBets[$i]->{'transaccion_juego.valor_ticket'};

                //Se valida si el transjuegoId fue sumado anteriormente para el informe de apuestas realizadas por el referido, previniendo sumas duplicadas
                if (!in_array($casinoBets[$i]->{'transaccion_juego.transjuego_id'}, $usedTransactions)) {
                    array_push($usedTransactions, $casinoBets[$i]->{'transaccion_juego.transjuego_id'});
                    $currentBet += $casinoBets[$i]->{'transaccion_juego.valor_ticket'};
                    $countedBets++;
                } else continue;

                if ($i < $countCasinoBets) {
                    $TransjuegoInfo = new TransjuegoInfo();
                    $TransjuegoInfo->tipo = 'REFERIDO';
                    $TransjuegoInfo->transaccionId = '';
                    $TransjuegoInfo->descripcion = $casinoBets[$i]->{'logro_referido.logroreferido_id'};
                    $TransjuegoInfo->usucreaId = 0;
                    $TransjuegoInfo->usumodifId = 0;
                    $TransjuegoInfo->valor = $casinoBets[$i]->{'transaccion_juego.valor_ticket'};
                    $TransjuegoInfo->transapiId = $casinoBets[$i]->{'transaccion_api.transapi_id'};
                    $TransjuegoInfoMySqlDAO->insert($TransjuegoInfo);
                }
            }

            $casinoMinBetTransaction->commit();
        } catch (Exception $e) {
        }
        /** Final validación condición CONDMINBETREFERRED*/


        /** Validación condición CONDVERIFIEDREFERRED*/
        try {
            $verifiedTransaction = new Transaction();
            $sql = "select logro_referido.logroreferido_id, logro_referido.estado, logro_referido.estado_grupal from clasificador inner join mandante_detalle on (clasificador.clasificador_id = mandante_detalle.tipo) inner join logro_referido on (mandante_detalle.manddetalle_id = logro_referido.tipo_condicion) inner join usuario on (logro_referido.usuid_referido = usuario.usuario_id) inner join usuario_mandante on (usuario.usuario_id = usuario_mandante.usuario_mandante) inner join mandante_detalle as mandante_detalle_estado on (mandante_detalle_estado.mandante = usuario_mandante.mandante and mandante_detalle_estado.pais_id = usuario_mandante.pais_id) inner join clasificador as clasificador_estado on (mandante_detalle_estado.tipo = clasificador_estado.clasificador_id) where clasificador_estado.abreviado = 'ACEPTAREFERIDO' and mandante_detalle_estado.estado = 'A' and mandante_detalle_estado.valor = 1 and clasificador.abreviado = 'CONDVERIFIEDREFERRED' and logro_referido.estado = 'P' AND logro_referido.estado_grupal = 'P' and usuario.verifcedula_ant = 'S' and usuario.verifcedula_post = 'S' and (logro_referido.fecha_expira is null or date_format(logro_referido.fecha_expira,'%Y-%m-%d') >= date_format('" . $fechaL2 . "','%Y-%m-%d'))";
            $verifiedAchievements = $BonoInterno->execQuery($verifiedTransaction, $sql);
            foreach ($verifiedAchievements as $achievement) {
                $LogroReferido = new LogroReferido($achievement->{'logro_referido.logroreferido_id'});
                $LogroReferido->setValorCondicion(1);
                $LogroReferido->setFechaUso(date('Y-m-d H:i:s'));
                $LogroReferido->setEstado('C');
                $LogroReferido->actualizarLogrosAgrupados($verifiedTransaction, $LogroReferido);
            }
            $verifiedTransaction->commit();
        } catch (Exception $e) {
        }
        /** Final validación condición  CONDVERIFIEDREFERRED*/

//Finalizando proceso
        $sqlProcesoInterno2 = "UPDATE proceso_interno2 SET fecha_ultima='" . $fechaL2 . "' WHERE  tipo='" . $temporalType . "'";
        $BonoInterno->execQuery($initialTransaction, $sqlProcesoInterno2);
        $BonoInterno->execQuery($initialTransaction, $sqlProcessConcluded);
        $initialTransaction->commit();


    }
}
