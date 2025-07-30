<?php
/**
 * Resúmen cronométrico
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 * @date 18.10.17
 *
 */

use Backend\cron\ResumenesCron;
use Backend\dto\BonoInterno;
use Backend\dto\UsuarioSession;
use Backend\dto\BonoLog;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\ItTicketEnc;
use Backend\dto\PreUsuarioSorteo;
use Backend\dto\SorteoDetalle;
use Backend\dto\CategoriaProducto;
use Backend\dto\SorteoInterno;
use Backend\dto\TransaccionApi;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\UsuarioBono;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioMandante;
use Backend\dto\Usuario;
use Backend\dto\TransjuegoInfo;
use Backend\dto\ItTicketEncInfo1;
use Backend\dto\UsuarioMensaje;
use Backend\dto\UsuarioSorteo;
use Backend\dto\Mandante;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\BonoLogMySqlDAO;
use Backend\mysql\PreUsuarioSorteoMySqlDAO;
use Backend\mysql\SorteoDetalleMySqlDAO;
use Backend\mysql\SorteoInternoMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransjuegoLogMySqlDAO;
use Backend\mysql\UsuarioBonoMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\UsuarioSorteoMySqlDAO;
use Backend\websocket\WebsocketUsuario;

use Backend\dao\TransjuegoInfoDAO;
use Backend\mysql\ItTicketEncInfo1MySqlDAO;
use Backend\mysql\TransjuegoInfoMySqlDAO;
use Exception;


require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__.'/cronSegundosCron.php');

ini_set('max_execution_time', 0);
ini_set('memory_limit', '-1');
header('Content-Type: application/json');


for($i=0;$i<10;$i++) {
    $message = "*CRON: (cronSorteos) * " . " - Fecha: " . date("Y-m-d H:i:s");
    $responseEnable = file_get_contents(__DIR__ . '/../../logSit/enabled');


    if ($responseEnable == 'BLOCKED') {
        exit();
    }

    $BonoInterno = new BonoInterno();

    $sqlProcesoInterno2 = "

SELECT * FROM proceso_interno2 WHERE tipo='SORTEOSPORTSBOOK'
";


    $data = $BonoInterno->execQuery('', $sqlProcesoInterno2);
    $data = $data[0];

    $line = $data->{'proceso_interno2.fecha_ultima'};

    if ($line == '') {
        exit();
    }


    $fechaL1 = date('Y-m-d H:i:00', strtotime($line . '+1 minute'));
    $fechaL2 = date('Y-m-d H:i:59', strtotime($line . '+1 minute'));


    if ($fechaL1 >= date('Y-m-d H:i:00')) {
        exit();
    }

    $BonoInterno = new BonoInterno();
    $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
    $transaccion = $BonoDetalleMySqlDAO->getTransaction();

    $sqlProcesoInterno2 = "
UPDATE proceso_interno2 SET fecha_ultima='" . $fechaL2 . "' WHERE  tipo='SORTEOSPORTSBOOK';
";


    $data = $BonoInterno->execQuery($transaccion, $sqlProcesoInterno2);
    $transaccion->commit();


    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "Inicia: " . date('Y-m-d H:i:s');
    $fp = fopen(__DIR__ . '/logs/Slog_' . date("Y-m-d") . '.log', 'a');
//fwrite($fp, $log);
//fclose($fp);


    if (true) {

        $rules = [];

        $debug = false;

        $BonoInterno = new BonoInterno();


        $TypeBet = 2;

        array_push($rules, array("field" => "it_ticket_enc.bet_status", "data" => "'S','N'", "op" => "in"));

        array_push($rules, array("field" => "it_ticket_enc.bet_status", "data" => "'S','N'", "op" => "in"));
        array_push($rules, array("field" => "it_ticket_enc.freebet", "data" => "0", "op" => "eq"));
        array_push($rules, array("field" => "it_ticket_enc.eliminado", "data" => "N", "op" => "eq"));
//array_push($rules, array("field" => "it_ticket_enc.ticket_id", "data" => implode(',',$ArrayTransaction), "op" => "ni"));
        array_push($rules, array("field" => "usuario.mandante", "data" => "19", "op" => "ni"));


        if ($fechaL1 != "") {
            if ($TypeBet == 2) {
                array_push($rules, array("field" => "(it_ticket_enc.fecha_cierre_time)", "data" => "$fechaL1", "op" => "ge"));
                $daydimensionFecha = 2;
            }
        }

        if ($fechaL2 != "") {

            if ($TypeBet == 2) {
                array_push($rules, array("field" => "(it_ticket_enc.fecha_cierre_time)", "data" => "$fechaL2", "op" => "le"));
                $daydimensionFecha = 2;
            }
        }

        array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "USUONLINE", "op" => "eq"));
//array_push($rules, array("field" => "usuario.mandante", "data" => $value->{"sorteo_interno.mandante"}, "op" => "eq"));


        $SkeepRows = 0;
        $MaxRows = 10000000;

        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);


        $ItTicketEnc = new ItTicketEnc();


        $tickets = $ItTicketEnc->getTicketsCustom2("usuario.mandante,usuario_mandante.pais_id, usuario_mandante.usumandante_id, usuario.usuario_id,usuario.nombre,usuario.login,usuario.moneda,it_ticket_enc.ticket_id,it_ticket_enc.bet_mode,it_ticket_enc.vlr_apuesta,it_ticket_enc.vlr_premio", 'it_ticket_enc.ticket_id', "desc", $SkeepRows, $MaxRows, $json, true, "", "", false, $daydimensionFecha, false, "", "");
        $tickets = json_decode($tickets);
        $dataUsuario = $tickets->data;
        if ($dataUsuario !== NULL && $dataUsuario !== "" && $dataUsuario[0] !== NULL) {

        } else {
            exit();
        }

        $SorteoInterno = new SorteoInterno();

        $rules = [];

        array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));
        array_push($rules, array("field" => "sorteo_interno.pegatinas", "data" => "1", "op" => "eq"));
        array_push($rules, array("field" => "sorteo_interno.habilita_deportivas", "data" => "1", "op" => "eq"));
        array_push($rules, array("field" => "sorteo_interno.fecha_inicio", "data" => date('Y-m-d H:i:s'), "op" => "le"));
        array_push($rules, array("field" => "sorteo_interno.fecha_fin", "data" => date('Y-m-d H:i:s'), "op" => "ge"));
        array_push($rules, array("field" => "sorteo_interno.mandante", "data" => "19", "op" => "ni"));


        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);

        $data = $SorteoInterno->getSorteosCustom("sorteo_interno.*", "sorteo_interno.orden", "DESC", 0, 1000, $json, true);

        $data = json_decode($data);


        $pos = 1;

        $sorteosAnalizados = '';

        $ActivacionSleepTime = true;

        $ArrayTransaction = array('1');


        $Sportsbook = true;


        foreach ($data->data as $key => $value) {

            if ($Sportsbook) {


                if ($value->condicional == 'NA' || $value->condicional == '') {
                    $tipocomparacion = "OR";

                } else {
                    $tipocomparacion = $value->condicional;

                }


                if ($dataUsuario !== NULL && $dataUsuario !== "" && $dataUsuario[0] !== NULL) {


                    //SPORTSBOOK
                    $condMinBetPrice = array();
                    $condMinBetPrice2 = array();
                    $cumplecondicionproducto = false;
                    $condDetallesSelecCouta = 0;
                    $cumpleCondicionContCouta = "";
                    $CondiDeportes = array();
                    $CondiLiga = array();
                    $CondiEvento = array();
                    $CondiDeporteMercado = array();
                    $condDetalleCuotaTotal = 0;
                    $condDetalleMinSelCount = 0;

                    $condPaises = array();

                    $bet_mode = "";
                    $condbet_mode = "";


                    $SorteoDetalle = new SorteoDetalle();

                    $rules = [];

                    array_push($rules, array("field" => "sorteo_interno.sorteo_id", "data" => $value->{"sorteo_interno.sorteo_id"}, "op" => "eq"));
                    array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));
                    array_push($rules, array("field" => "sorteo_interno.mandante", "data" => $value->{"sorteo_interno.mandante"}, "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $json = json_encode($filtro);

                    $sorteodetalles = $SorteoDetalle->getSorteoDetallesCustom(" sorteo_detalle.*,sorteo_interno.* ", "sorteo_interno.orden", "desc", 0, 1000, $json, TRUE);

                    $sorteodetalles = json_decode($sorteodetalles);


                    foreach ($sorteodetalles->data as $key2 => $value2) {


                        switch ($value2->{"sorteo_detalle.tipo"}) {


                            case "USERSUBSCRIBE":


                                break;

                            case "MINBETPRICESPORTSBOOK":

                                $condMinBetPrice[$value2->{"sorteo_detalle.moneda"}] = floatval($value2->{"sorteo_detalle.valor"});

                                break;

                            case "VISIBILIDAD":

                                if ($value2->{"sorteo_detalle.valor"} == 1) {
                                    $needSubscribe = true;
                                }

                                break;


                            case "USERSUBSCRIBE":

                                if ($value2->{"sorteo_detalle.valor"} == 0) {

                                } else {
                                    $needSubscribe = true;
                                }

                                break;

                            case "CONDPAISUSER":

                                array_push($condPaises, $value2->{"sorteo_detalle.valor"});

                                break;

                            case "REPETIRSORTEO":

                                if ($value2->{"sorteo_detalle.valor"} == '1') {

                                    $puederepetirBono = true;
                                }

                                break;

                            case "NUMBERSPORTSBOOKSTICKERS":

                                $NUMBERSPORTSBOOKSTICKERS = $value2->{"sorteo_detalle.valor"};

                                break;

                            case "MINBETPRICE2SPORTSBOOK":

                                $condMinBetPrice2[$value2->{"sorteo_detalle.moneda"}] = floatval($value2->{"sorteo_detalle.valor"});

                                break;

                            case "LIVEORPREMATCH":

                                $condbet_mode = $value2->{"sorteo_detalle.valor"};

                                break;

                            case "MINSELCOUNT":

                                // Realmente se debe Validar?
                                $minselcount = $value2->{"sorteo_detalle.valor"};

                                $condDetalleMinSelCount = $value2->{"sorteo_detalle.valor"};


                                break;

                            case "MINSELPRICE":

                                $condDetallesSelecCouta = $value2->{"sorteo_detalle.valor"};


                                break;

                            case "MINSELPRICETOTAL":


                                $condDetalleCuotaTotal = $value2->{"sorteo_detalle.valor"};


                                break;

                            case "ITAINMENT1":

                                array_push($CondiDeportes, $value2->{"sorteo_detalle.valor"});

                                break;

                            case "ITAINMENT3":

                                array_push($CondiLiga, $value2->{"sorteo_detalle.valor"});

                                break;

                            case "ITAINMENT4":

                                array_push($CondiEvento, $value2->{"sorteo_detalle.valor"});

                                break;

                            case "ITAINMENT5":

                                array_push($CondiDeporteMercado, $value2->{"sorteo_detalle.valor"});

                                break;

                            case "ITAINMENT82":

                                if ($value2->{"sorteo_detalle.valor"} == 1) {
                                    $sePuedeSimples = 1;

                                }

                                if ($value2->{"sorteo_detalle.valor"} == 2) {
                                    $sePuedeCombinadas = 1;

                                }
                                break;

                            default:


                                break;

                        }

                    }

                    foreach ($dataUsuario as $key4 => $datanum) {

                        if (in_array($datanum->{"it_ticket_enc.ticket_id"}, $ArrayTransaction)) {
                            continue;
                        }
                        if (($value->{"sorteo_interno.mandante"} != $datanum->{"usuario.mandante"})) {
                            continue;
                        }

                        $final = [];

                        $creditosConvert = 0;

                        $cumpleCondicion = true;

                        $condicionesproducto = 0;
                        $cumpleCondicionProd = false;

                        $condicionesProveedor = 0;
                        $cumpleCondicionProv = false;

                        $cumpleCondicionCont = 0;
                        $cumpleCondicionPais = false;

                        $minBetPrice = 0;
                        $minBetPrice2 = 0;
                        $NUMBERCASINOSTICKERS = 0;

                        $pegatinas = $value->{"sorteo_interno.pegatinas"};

                        $detalleCuotaTotal = 1;

                        $ConfigurationEnvironment = new ConfigurationEnvironment();

                        if (!$ConfigurationEnvironment->isDevelopment() || true) {
                            //$sqlSport = "select te.bet_mode,te.vlr_apuesta,td.ticket_id,td.apuesta,td.agrupador,td.logro,td.opcion,td.apuesta_id,td.agrupador_id,td.fecha_evento,td.hora_evento,te.usuario_id,td.sportid,td.matchid,td.ligaid from it_ticket_det td INNER JOIN it_ticket_enc te ON (td.ticket_id = te.ticket_id ) where  te.ticket_id='".$ticketId."' ";
                            $sqlSport = "select te.vlr_apuesta,td.ticket_id,td.apuesta,td.agrupador,td.logro,td.opcion,td.apuesta_id,td.agrupador_id,td.sportid,td.fecha_evento,td.hora_evento,te.usuario_id from it_ticket_det td INNER JOIN it_ticket_enc te ON (td.ticket_id = te.ticket_id ) where  te.ticket_id='" . $datanum->{"it_ticket_enc.ticket_id"} . "' ";

                        } else {
                            $sqlSport = "select te.vlr_apuesta,te.ticket_id,td.apuesta,td.agrupador,td.logro,td.opcion,td.apuesta_id,td.agrupador_id,td.sportid,td.fecha_evento,te.usuario_id from transsportsbook_detalle td INNER JOIN transaccion_sportsbook te ON (td.transsport_id = te.transsport_id ) where  te.ticket_id='" . $datanum->{"it_ticket_enc.ticket_id"} . "' ";
                        }

                        $detalleTicket = execQuery('', $sqlSport);

                        $array = array();

                        foreach ($detalleTicket as $detalle) {
                            $detalle->sportid = $detalle->{'td.sportid'};
                            $detalle->agrupador_id = $detalle->{'td.agrupador_id'};
                            $detalle->ligaid = $detalle->{'td.ligaid'};
                            $detalle->logro = $detalle->{'td.logro'};
                            $detalle->vlr_apuesta = $detalle->{'te.vlr_apuesta'};

                            $detalles = array(
                                "DeporteMercado" => $detalle->sportid . "M" . $detalle->agrupador_id,
                                "Deporte" => $detalle->sportid,
                                "Liga" => $detalle->ligaid,
                                // "Evento"=>$detalle->apuesta_id,
                                "Cuota" => $detalle->logro

                            );
                            $detalleValorApuesta = $detalle->vlr_apuesta;

                            array_push($array, $detalles);

                            if (!$ConfigurationEnvironment->isDevelopment()) {
                                $usuarioId = $detalle->usuario_id;
                            }
                            $bet_mode = $detalle->bet_mode;
                            $detalleCuotaTotal = $detalleCuotaTotal * $detalle->logro;

                        }
                        $detallesFinal = json_decode(json_encode($array));

                        $detalleSelecciones = $detallesFinal;


                        if (oldCount($CondiDeportes) > 0) {


                            foreach ($CondiDeportes as $Deport) {

                                foreach ($detalleSelecciones as $item) {


                                    if ($tipocomparacion == "OR") {
                                        if ($Deport == $item->Deporte) {
                                            $cumplecondicionproducto = true;
                                        }
                                    } elseif ($tipocomparacion == "AND") {
                                        if ($Deport != $item->Deporte) {
                                            $cumplecondicionproducto = false;
                                        }

                                        if ($condicionesproducto == 0) {
                                            if ($Deport == $item->Deporte) {
                                                $cumplecondicionproducto = true;
                                            }
                                        } else {
                                            if ($Deport == $item->Deporte && $cumplecondicionproducto) {
                                                $cumplecondicionproducto = true;
                                            }
                                        }

                                    }

                                }
                                if ($cumplecondicionproducto == false) {
                                    $BonoInterno = new BonoInterno();
                                    $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                    VALUES ('{$datanum->{"usuario.usuario_id"}}','SORTEO','{$value->{"sorteo_interno.sorteo_id"}}','SPORTBOOK','{$datanum->{"it_ticket_enc.ticket_id"}}','ITAINMENT1')";
                                    //$BonoInterno->execQuery($transaccion, $sqlLog);
                                }
                            }

                            $condicionesproducto++;

                        }

                        if (oldCount($CondiLiga) > 0) {

                            foreach ($detalleSelecciones as $item) {
                                if ($tipocomparacion == "OR") {
                                    if ($value2->{"sorteo_detalle.valor"} == $item->Liga) {
                                        $cumplecondicionproducto = true;

                                    }
                                } elseif ($tipocomparacion == "AND") {
                                    if ($value2->{"sorteo_detalle.valor"} != $item->Liga) {
                                        $cumplecondicionproducto = false;

                                    }

                                    if ($condicionesproducto == 0) {
                                        if ($value2->{"sorteo_detalle.valor"} == $item->Liga) {
                                            $cumplecondicionproducto = true;
                                        }
                                    } else {
                                        if ($value2->{"sorteo_detalle.valor"} == $item->Liga && $cumplecondicionproducto) {
                                            $cumplecondicionproducto = true;

                                        }
                                    }

                                }
                                if ($cumplecondicionproducto == false) {
                                    $BonoInterno = new BonoInterno();
                                    $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                    VALUES ('{$datanum->{"usuario.usuario_id"}}','SORTEO','{$value->{"sorteo_interno.sorteo_id"}}','SPORTBOOK','{$datanum->{"it_ticket_enc.ticket_id"}}','ITAINMENT3')";
                                    //$BonoInterno->execQuery($transaccion, $sqlLog);
                                }

                            }

                            $condicionesproducto++;

                        }

                        if (oldCount($CondiEvento) > 0) {

                            foreach ($detalleSelecciones as $item) {
                                if ($tipocomparacion == "OR") {
                                    if ($value2->{"sorteo_detalle.valor"} == $item->Evento) {
                                        $cumplecondicionproducto = true;

                                    }
                                } elseif ($tipocomparacion == "AND") {
                                    if ($value2->{"sorteo_detalle.valor"} != $item->Evento) {
                                        $cumplecondicionproducto = false;

                                    }

                                    if ($condicionesproducto == 0) {

                                        if ($value2->{"sorteo_detalle.valor"} == $item->Evento) {
                                            $cumplecondicionproducto = true;
                                        }
                                    } else {

                                        if ($value2->{"sorteo_detalle.valor"} == $item->Evento && $cumplecondicionproducto) {
                                            $cumplecondicionproducto = true;

                                        }
                                    }

                                }
                                if ($cumplecondicionproducto == false) {
                                    $BonoInterno = new BonoInterno();
                                    $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                    VALUES ('{$datanum->{"usuario.usuario_id"}}','SORTEO','{$value->{"sorteo_interno.sorteo_id"}}','SPORTBOOK','{$datanum->{"it_ticket_enc.ticket_id"}}','ITAINMENT4')";
                                    //$BonoInterno->execQuery($transaccion, $sqlLog);
                                }
                            }

                            $condicionesproducto++;
                        }

                        if (oldCount($CondiDeporteMercado) > 0) {

                            foreach ($detalleSelecciones as $item) {
                                if ($tipocomparacion == "OR") {
                                    if ($value2->{"sorteo_detalle.valor"} == $item->DeporteMercado) {
                                        $cumplecondicionproducto = true;


                                    }
                                } elseif ($tipocomparacion == "AND") {
                                    if ($value2->{"sorteo_detalle.valor"} != $item->DeporteMercado) {
                                        $cumplecondicionproducto = false;

                                    }

                                    if ($condicionesproducto == 0) {
                                        if ($value2->{"sorteo_detalle.valor"} == $item->DeporteMercado) {
                                            $cumplecondicionproducto = true;
                                        }
                                    } else {
                                        if ($value2->{"sorteo_detalle.valor"} == $item->DeporteMercado && $cumplecondicionproducto) {
                                            $cumplecondicionproducto = true;

                                        }
                                    }

                                }
                                if ($cumplecondicionproducto == false) {
                                    $BonoInterno = new BonoInterno();
                                    $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                    VALUES ('{$datanum->{"usuario.usuario_id"}}','SORTEO','{$value->{"sorteo_interno.sorteo_id"}}','SPORTBOOK','{$datanum->{"it_ticket_enc.ticket_id"}}','ITAINMENT5')";
                                    //$BonoInterno->execQuery($transaccion, $sqlLog);
                                }
                            }

                            $condicionesproducto++;
                        }

                        if ($condDetallesSelecCouta > 0) {

                            foreach ($detalleSelecciones as $item) {
                                if ($condDetallesSelecCouta > $item->Cuota) {
                                    $cumpleCondicion = false;
                                    $BonoInterno = new BonoInterno();
                                    $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                VALUES ('{$datanum->{"usuario.usuario_id"}}','SORTEO','{$value->{"sorteo_interno.sorteo_id"}}','SPORTBOOK','{$datanum->{"it_ticket_enc.ticket_id"}}','MINSELPRICE')";
                                    //$BonoInterno->execQuery($transaccion, $sqlLog);
                                }
                            }
                        }

                        if ($condDetalleCuotaTotal > 0) {

                            foreach ($detalleSelecciones as $item) {
                                if ($condDetalleCuotaTotal > $detalleCuotaTotal) {
                                    $cumpleCondicion = false;
                                    $BonoInterno = new BonoInterno();
                                    $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                VALUES ('{$datanum->{"usuario.usuario_id"}}','SORTEO','{$value->{"sorteo_interno.sorteo_id"}}','SPORTBOOK','{$datanum->{"it_ticket_enc.ticket_id"}}','MINSELPRICETOTAL')";
                                    //$BonoInterno->execQuery($transaccion, $sqlLog);
                                }
                            }
                        }

                        if (oldCount($condPaises) > 0) {

                            if (in_array($datanum->{"usuario_mandante.pais_id"}, $condPaises)) {
                                $cumpleCondicionPais = true;
                            }
                            if ($cumpleCondicionPais == false) {
                                $BonoInterno = new BonoInterno();
                                $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                VALUES ('{$datanum->{"usuario.usuario_id"}}','SORTEO','{$value->{"sorteo_interno.sorteo_id"}}','SPORTBOOK','{$datanum->{"it_ticket_enc.ticket_id"}}','CONDPAISUSER')";
                                //$BonoInterno->execQuery($transaccion, $sqlLog);
                            }
                            $cumpleCondicionCont++;
                        }


                        if ($condbet_mode == 2) {

                            if ($datanum->{"it_ticket_enc.bet_mode"} == "PreLive") {

                                $cumplecondicionproducto = true;

                            } else {
                                $cumplecondicionproducto = false;
                                $BonoInterno = new BonoInterno();
                                $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                VALUES ('{$datanum->{"usuario.usuario_id"}}','SORTEO','{$value->{"sorteo_interno.sorteo_id"}}','SPORTBOOK','{$datanum->{"it_ticket_enc.ticket_id"}}','LIVEORPREMATCH')";
                                //$BonoInterno->execQuery($transaccion, $sqlLog);

                            }

                        }

                        if ($condbet_mode == 1) {
                            if ($datanum->{"it_ticket_enc.bet_mode"} == "Live") {
                                $cumplecondicionproducto = true;
                            } else {
                                $cumplecondicionproducto = false;
                                $BonoInterno = new BonoInterno();
                                $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                VALUES ('{$datanum->{"usuario.usuario_id"}}','SORTEO','{$value->{"sorteo_interno.sorteo_id"}}','SPORTBOOK','{$datanum->{"it_ticket_enc.ticket_id"}}','LIVEORPREMATCH')";
                                //$BonoInterno->execQuery($transaccion, $sqlLog);
                            }

                        }

                        if ($condbet_mode == 0) {
                            /*if($datanum->{"it_ticket_enc.bet_mode"}== == "Mixed") {
                                $cumplecondicionproducto = true;
                            }else{
                                $cumplecondicionproducto = false;
                            }*/
                        }


                        foreach ($condMinBetPrice as $moneda => $valor) {
                            if ($moneda == $datanum->{"usuario.moneda"}) {
                                $minBetPrice = floatval($valor);

                            }
                        }

                        foreach ($condMinBetPrice2 as $moneda2 => $valor2) {

                            if ($moneda2 == $datanum->{"usuario.moneda"}) {
                                $minBetPrice2 = floatval($valor2);

                            }

                        }


                        if ($condicionesproducto > 0 && !$cumplecondicionproducto) {

                            $cumpleCondicion = false;
                        }

                        if ($condicionesProveedor > 0 && !$cumpleCondicionProv) {

                            $cumpleCondicion = false;
                        }

                        if (!$cumpleCondicionPais && $cumpleCondicionCont > 0) {


                            $cumpleCondicion = false;
                        }


                        if ($minBetPrice2 > floatval($datanum->{"it_ticket_enc.vlr_apuesta"})) {

                            $cumpleCondicion = false;
                            $BonoInterno = new BonoInterno();
                            $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                VALUES ('{$datanum->{"usuario.usuario_id"}}','SORTEO','{$value->{"sorteo_interno.sorteo_id"}}','SPORTBOOK','{$datanum->{"it_ticket_enc.ticket_id"}}','MINBETPRICE2SPORTSBOOK')";
                            //$BonoInterno->execQuery($transaccion, $sqlLog);

                        }


                        if ($sePuedeCombinadas != 0 || $sePuedeSimples != 0) {
                            $condItainment82 = true;
                            if (oldCount($detalleSelecciones) == 1 && !$sePuedeSimples) {
                                $cumpleCondicion = false;
                                $condItainment82 = false;
                            }

                            if (oldCount($detalleSelecciones) > 1 && !$sePuedeCombinadas) {
                                $cumpleCondicion = false;
                                $condItainment82 = false;
                            }

                            if ($sePuedeCombinadas) {
                                if (oldCount($detalleSelecciones) > 1 && oldCount($detalleSelecciones) < $minselcount) {
                                    $cumpleCondicion = false;
                                    $condItainment82 = false;
                                }
                            }

                            if ($condItainment82 == false) {
                                $BonoInterno = new BonoInterno();
                                $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                VALUES ('{$datanum->{"usuario.usuario_id"}}','SORTEO','{$value->{"sorteo_interno.sorteo_id"}}','SPORTBOOK','{$datanum->{"it_ticket_enc.ticket_id"}}','ITAINMENT82')";
                                //$BonoInterno->execQuery($transaccion, $sqlLog);
                            }
                        } else {
                            if (oldCount($detalleSelecciones) > 1 && oldCount($detalleSelecciones) < $minselcount) {
                                $cumpleCondicion = false;

                            }
                        }

                        $valorTicket = floatval($datanum->{"it_ticket_enc.vlr_apuesta"});


                        if ($cumpleCondicion) {

                            if ($puederepetirBono) {


                            } else {


                                $sqlRepiteSorteo = "select * from usuario_sorteo a where  a.ususorteo_id !=0 and  a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.usuario_id = '" . $datanum->{"usuario.usuario_id"} . "'";
                                $repiteSorteo = execQuery('', $sqlRepiteSorteo);

                                if ((!$puederepetirBono && oldCount($repiteSorteo) == 0)) {

                                } else {
                                    $cumpleCondicion = false;
                                    $BonoInterno = new BonoInterno();
                                    $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                VALUES ('{$datanum->{"usuario.usuario_id"}}','SORTEO','{$value->{"sorteo_interno.sorteo_id"}}','SPORTBOOK','{$datanum->{"it_ticket_enc.ticket_id"}}','REPETIRSORTEO')";
                                    //$BonoInterno->execQuery($transaccion, $sqlLog);
                                }

                            }
                        }


                        if ($needSubscribe) {


                            $rules = [];
                            array_push($rules, ['field' => 'usuario_sorteo.estado', 'data' => 'I', 'op' => 'eq']);
                            array_push($rules, ['field' => 'usuario_sorteo.sorteo_id', 'data' => $value->{"sorteo_interno.sorteo_id"}, 'op' => 'eq']);
                            array_push($rules, ['field' => 'usuario_sorteo.usuario_id', 'data' => $datanum->{"usuario_mandante.usumandante_id"}, 'op' => 'eq']);

                            $filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);
                            $UsuarioSorteo2 = new UsuarioSorteo();
                            $allCoupons = (string)$UsuarioSorteo2->getUsuarioSorteosCustom('COUNT(distinct(usuario_sorteo.usuario_id)) countUsers,COUNT((usuario_sorteo.ususorteo_id)) countStickers', 'usuario_sorteo.ususorteo_id', 'asc', 0, 1000000, $filter, true);

                            $allCoupons = json_decode($allCoupons, true);


                            if ($allCoupons['count'][0]['.count'] > 0) {
                            } else {
                                $cumpleCondicion = false;
                                $BonoInterno = new BonoInterno();
                                $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                VALUES ('{$datanum->{"usuario.usuario_id"}}','SORTEO','{$value->{"sorteo_interno.sorteo_id"}}','SPORTBOOK','{$datanum->{"it_ticket_enc.ticket_id"}}','NEEDSUSCRIBE')";
                                //$BonoInterno->execQuery($transaccion, $sqlLog);
                            }
                        }


                        if ($cumpleCondicion) {

                            $ItTicketEncInfo1MySqlDAO = new ItTicketEncInfo1MySqlDAO();


                            array_push($ArrayTransaction, "'" . $datanum->{"it_ticket_enc.ticket_id"} . "'");

                            if ($pegatinas == 1) {
                                $estado = "P";
                                $sqlRepiteSorteo = "select * from preusuario_sorteo a where  a.ususorteo_id !=0 and  a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND  tipo='2'  AND a.usuario_id = '" . $datanum->{"usuario.usuario_id"} . "' AND a.estado = '" . $estado . "' AND valor_base > apostado ";
                                $repiteSorteo = execQuery('', $sqlRepiteSorteo);


                                if (oldCount($repiteSorteo) == 0) {
                                    $BonoInterno = new BonoInterno();
                                    $PreUsuarioSorteo = new PreUsuarioSorteo();
                                    $PreUsuarioSorteo->usuarioId = $datanum->{"usuario_mandante.usumandante_id"};
                                    $PreUsuarioSorteo->sorteoId = $value->{"sorteo_interno.sorteo_id"};
                                    $PreUsuarioSorteo->valor = 0;
                                    $PreUsuarioSorteo->posicion = 0;
                                    $PreUsuarioSorteo->valorBase = $minBetPrice;
                                    $PreUsuarioSorteo->usucreaId = 0;
                                    $PreUsuarioSorteo->usumodifId = 0;
                                    $PreUsuarioSorteo->mandante = $datanum->{"usuario.mandante"};
                                    $PreUsuarioSorteo->tipo = 2;

                                    if ($datanum->{"it_ticket_enc.vlr_apuesta"} < $minBetPrice) {
                                        $PreUsuarioSorteo->estado = "P";

                                    } else {
                                        $PreUsuarioSorteo->estado = "A";
                                    }
                                    $PreUsuarioSorteo->errorId = 0;
                                    $PreUsuarioSorteo->idExterno = 0;
                                    $PreUsuarioSorteo->version = 0;
                                    $PreUsuarioSorteo->apostado = $datanum->{"it_ticket_enc.vlr_apuesta"};
                                    $PreUsuarioSorteo->codigo = 0;
                                    $PreUsuarioSorteo->externoId = 0;
                                    $PreUsuarioSorteo->valor = $PreUsuarioSorteo->valor + $creditosConvert;

                                    $PreUsuarioSorteoMySqlDAO = new PreUsuarioSorteoMySqlDAO($ItTicketEncInfo1MySqlDAO->getTransaction());


                                    $idUsuSorteo = $PreUsuarioSorteoMySqlDAO->insert($PreUsuarioSorteo);


                                } else {
                                    $ususorteoId = $repiteSorteo[0]->{"a.preususorteo_id"};
                                    $BonoInterno = new BonoInterno();
                                    $idUsuSorteo = $ususorteoId;

                                    $transaccion = $ItTicketEncInfo1MySqlDAO->getTransaction();
                                    $sql = "UPDATE preusuario_sorteo SET apostado = apostado + " . (floatval($datanum->{"it_ticket_enc.vlr_apuesta"})) . " WHERE preususorteo_id =" . $ususorteoId;


                                    $BonoInterno->execQuery($transaccion, $sql);

                                }

                            } else {
                                $estado = "P";
                                $sqlRepiteSorteo = "select * from usuario_sorteo a where  a.ususorteo_id !=0 and  a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.usuario_id = '" . $datanum->{"usuario.usuario_id"} . "' AND a.estado = '" . $estado . "'";
                                $repiteSorteo = execQuery('', $sqlRepiteSorteo);


                                if (oldCount($repiteSorteo) == 0) {
                                    $UsuarioSorteo = new UsuarioSorteo();
                                    $UsuarioSorteo->usuarioId = $datanum->{"usuario_mandante.usumandante_id"};
                                    $UsuarioSorteo->sorteoId = $value->{"sorteo_interno.sorteo_id"};
                                    $UsuarioSorteo->valor = 0;
                                    $UsuarioSorteo->posicion = 0;
                                    $UsuarioSorteo->valorBase = $minBetPrice;
                                    $UsuarioSorteo->usucreaId = 0;
                                    $UsuarioSorteo->usumodifId = 0;


                                    if ($datanum->{"it_ticket_enc.vlr_apuesta"} < $minBetPrice) {
                                        $UsuarioSorteo->estado = "P";

                                    } else {
                                        $UsuarioSorteo->estado = "A";
                                    }

                                    $UsuarioSorteo->errorId = 0;
                                    $UsuarioSorteo->idExterno = 0;
                                    $UsuarioSorteo->mandante = 0;
                                    $UsuarioSorteo->version = 0;
                                    $UsuarioSorteo->apostado = $datanum->{"it_ticket_enc.vlr_apuesta"};
                                    $UsuarioSorteo->codigo = 0;
                                    $UsuarioSorteo->externoId = 0;
                                    $UsuarioSorteo->valor = $UsuarioSorteo->valor + $creditosConvert;
                                    //$UsuarioSorteo->valorBase = ($UsuarioSorteo->valorBase + $TransaccionApi->valor);


                                    $UsuarioSorteoMySqlDAO = new UsuarioSorteoMySqlDAO($ItTicketEncInfo1MySqlDAO->getTransaction());
                                    $ususorteoId = $UsuarioSorteoMySqlDAO->insert($UsuarioSorteo);

                                } else {
                                    $ususorteoId = $repiteSorteo[0]->{"a.ususorteo_id"};
                                    $BonoInterno = new BonoInterno();

                                    $transaccion = $ItTicketEncInfo1MySqlDAO->getTransaction();

                                    $sql = "UPDATE usuario_sorteo SET apostado = apostado + " . (floatval($datanum->{"it_ticket_enc.vlr_apuesta"})) . " WHERE ususorteo_id =" . $ususorteoId;
                                    $BonoInterno->execQuery($transaccion, $sql);

                                }

                            }

                            /*$UsuarioMensaje = new UsuarioMensaje();
                            $UsuarioMensaje->usufromId = 0;
                            $UsuarioMensaje->usutoId = $UsuarioSorteo->getUsuarioId();
                            $UsuarioMensaje->isRead = 0;
                            $UsuarioMensaje->body = '¡ Bien :thumbsup: ! Estas participando en el sorteo ' . $value->{"sorteo_interno.nombre"} . ' :clap:';
                            $UsuarioMensaje->msubject = 'Notificacion';
                            $UsuarioMensaje->parentId = 0;
                            $UsuarioMensaje->proveedorId = 0;
                            $UsuarioMensaje->tipo = "PUSHNOTIFICACION";
                            $UsuarioMensaje->paisId = 0;
                            $UsuarioMensaje->fechaExpiracion = '';

                            $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($ItTicketEncInfo1MySqlDAO->getTransaction());
                            $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);*/


                            $ItTicketEncInfo1 = new ItTicketEncInfo1();
                            $ItTicketEncInfo1->ticketId = $datanum->{"it_ticket_enc.ticket_id"};
                            $ItTicketEncInfo1->tipo = "SORTEOSTICKER";

                            $ItTicketEncInfo1->valor = $idUsuSorteo;
                            $ItTicketEncInfo1->fechaCrea = date('Y-m-d H:i:s');
                            $ItTicketEncInfo1->fechaModif = date('Y-m-d H:i:s');
                            //$ItTicketEncInfo1->valor = $creditosConvert;

                            $ItTicketEncInfo1MySqlDAO->insert($ItTicketEncInfo1);
                            $ItTicketEncInfo1MySqlDAO->getTransaction()->commit();

                        }
                    }
                }
            }


        }


    }

    if (true) {


        $rules = [];

        $debug = false;

        $BonoInterno = new BonoInterno();


        $TypeBet = 2;


        array_push($rules, array("field" => "it_ticket_enc.bet_status", "data" => "'S','N'", "op" => "in"));
        array_push($rules, array("field" => "it_ticket_enc.freebet", "data" => "0", "op" => "eq"));
        array_push($rules, array("field" => "it_ticket_enc.eliminado", "data" => "N", "op" => "eq"));
        array_push($rules, array("field" => "usuario.mandante", "data" => "19", "op" => "eq"));
//array_push($rules, array("field" => "it_ticket_enc.ticket_id", "data" => implode(',',$ArrayTransaction), "op" => "ni"));


        if ($fechaL1 != "") {
            if ($TypeBet == 2) {
                array_push($rules, array("field" => "(it_ticket_enc.fecha_cierre_time)", "data" => "$fechaL1", "op" => "ge"));
                $daydimensionFecha = 2;
            }
        }

        if ($fechaL2 != "") {

            if ($TypeBet == 2) {
                array_push($rules, array("field" => "(it_ticket_enc.fecha_cierre_time)", "data" => "$fechaL2", "op" => "le"));
                $daydimensionFecha = 2;
            }
        }

        array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "USUONLINE", "op" => "eq"));
//array_push($rules, array("field" => "usuario.mandante", "data" => $value->{"sorteo_interno.mandante"}, "op" => "eq"));


        $SkeepRows = 0;
        $MaxRows = 10000000;

        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);


        $ItTicketEnc = new ItTicketEnc();


        $tickets = $ItTicketEnc->getTicketsCustom2("usuario.mandante,usuario_mandante.pais_id, usuario_mandante.usumandante_id, usuario.usuario_id,usuario.nombre,usuario.login,usuario.moneda,it_ticket_enc.ticket_id,it_ticket_enc.bet_mode,it_ticket_enc.vlr_apuesta,it_ticket_enc.vlr_premio", 'it_ticket_enc.ticket_id', "desc", $SkeepRows, $MaxRows, $json, true, "", "", false, $daydimensionFecha, false, "", "");
        $tickets = json_decode($tickets);
        $dataUsuario = $tickets->data;
        if ($dataUsuario !== NULL && $dataUsuario !== "" && $dataUsuario[0] !== NULL) {

        } else {
            exit();
        }

        $SorteoInterno = new SorteoInterno();

        $rules = [];

        array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));
        array_push($rules, array("field" => "sorteo_interno.pegatinas", "data" => "1", "op" => "eq"));
        array_push($rules, array("field" => "sorteo_interno.habilita_deportivas", "data" => "1", "op" => "eq"));
        array_push($rules, array("field" => "sorteo_interno.fecha_inicio", "data" => date('Y-m-d H:i:s'), "op" => "le"));
        array_push($rules, array("field" => "sorteo_interno.fecha_fin", "data" => date('Y-m-d H:i:s'), "op" => "ge"));

        array_push($rules, array("field" => "sorteo_interno.mandante", "data" => "19", "op" => "eq"));

        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);

        $data = $SorteoInterno->getSorteosCustom("sorteo_interno.*", "sorteo_interno.orden", "DESC", 0, 1000, $json, true);

        $data = json_decode($data);


        $pos = 1;

        $sorteosAnalizados = '';

        $ActivacionSleepTime = true;

        $ArrayTransaction = array('1');


        $Sportsbook = true;


        foreach ($data->data as $key => $value) {

            if ($Sportsbook) {


                if ($value->condicional == 'NA' || $value->condicional == '') {
                    $tipocomparacion = "OR";

                } else {
                    $tipocomparacion = $value->condicional;

                }


                if ($dataUsuario !== NULL && $dataUsuario !== "" && $dataUsuario[0] !== NULL) {


                    //SPORTSBOOK
                    $condMinBetPrice = array();
                    $condMinBetPrice2 = array();
                    $cumplecondicionproducto = false;
                    $condDetallesSelecCouta = 0;
                    $cumpleCondicionContCouta = "";
                    $CondiDeportes = array();
                    $CondiLiga = array();
                    $CondiEvento = array();
                    $CondiDeporteMercado = array();
                    $condDetalleCuotaTotal = 0;
                    $condDetalleMinSelCount = 0;

                    $condPaises = array();

                    $bet_mode = "";
                    $condbet_mode = "";


                    $SorteoDetalle = new SorteoDetalle();

                    $rules = [];

                    array_push($rules, array("field" => "sorteo_interno.sorteo_id", "data" => $value->{"sorteo_interno.sorteo_id"}, "op" => "eq"));
                    array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));
                    array_push($rules, array("field" => "sorteo_interno.mandante", "data" => $value->{"sorteo_interno.mandante"}, "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $json = json_encode($filtro);

                    $sorteodetalles = $SorteoDetalle->getSorteoDetallesCustom(" sorteo_detalle.*,sorteo_interno.* ", "sorteo_interno.orden", "desc", 0, 1000, $json, TRUE);

                    $sorteodetalles = json_decode($sorteodetalles);


                    foreach ($sorteodetalles->data as $key2 => $value2) {


                        switch ($value2->{"sorteo_detalle.tipo"}) {


                            case "USERSUBSCRIBE":


                                break;

                            case "MINBETPRICESPORTSBOOK":

                                $condMinBetPrice[$value2->{"sorteo_detalle.moneda"}] = floatval($value2->{"sorteo_detalle.valor"});

                                break;

                            case "VISIBILIDAD":

                                if ($value2->{"sorteo_detalle.valor"} == 1) {
                                    $needSubscribe = true;
                                }

                                break;


                            case "USERSUBSCRIBE":

                                if ($value2->{"sorteo_detalle.valor"} == 0) {

                                } else {
                                    $needSubscribe = true;
                                }

                                break;

                            case "CONDPAISUSER":

                                array_push($condPaises, $value2->{"sorteo_detalle.valor"});

                                break;

                            case "REPETIRSORTEO":

                                if ($value2->{"sorteo_detalle.valor"} == '1') {

                                    $puederepetirBono = true;
                                }

                                break;

                            case "NUMBERSPORTSBOOKSTICKERS":

                                $NUMBERSPORTSBOOKSTICKERS = $value2->{"sorteo_detalle.valor"};

                                break;

                            case "MINBETPRICE2SPORTSBOOK":

                                $condMinBetPrice2[$value2->{"sorteo_detalle.moneda"}] = floatval($value2->{"sorteo_detalle.valor"});

                                break;

                            case "LIVEORPREMATCH":

                                $condbet_mode = $value2->{"sorteo_detalle.valor"};

                                break;

                            case "MINSELCOUNT":

                                // Realmente se debe Validar?
                                $minselcount = $value2->{"sorteo_detalle.valor"};

                                $condDetalleMinSelCount = $value2->{"sorteo_detalle.valor"};


                                break;

                            case "MINSELPRICE":

                                $condDetallesSelecCouta = $value2->{"sorteo_detalle.valor"};


                                break;

                            case "MINSELPRICETOTAL":


                                $condDetalleCuotaTotal = $value2->{"sorteo_detalle.valor"};


                                break;

                            case "ITAINMENT1":

                                array_push($CondiDeportes, $value2->{"sorteo_detalle.valor"});

                                break;

                            case "ITAINMENT3":

                                array_push($CondiLiga, $value2->{"sorteo_detalle.valor"});

                                break;

                            case "ITAINMENT4":

                                array_push($CondiEvento, $value2->{"sorteo_detalle.valor"});

                                break;

                            case "ITAINMENT5":

                                array_push($CondiDeporteMercado, $value2->{"sorteo_detalle.valor"});

                                break;

                            case "ITAINMENT82":

                                if ($value2->{"sorteo_detalle.valor"} == 1) {
                                    $sePuedeSimples = 1;

                                }

                                if ($value2->{"sorteo_detalle.valor"} == 2) {
                                    $sePuedeCombinadas = 1;

                                }
                                break;

                            default:


                                break;

                        }

                    }

                    foreach ($dataUsuario as $key4 => $datanum) {

                        if (in_array($datanum->{"it_ticket_enc.ticket_id"}, $ArrayTransaction)) {
                            continue;
                        }
                        if (($value->{"sorteo_interno.mandante"} != $datanum->{"usuario.mandante"})) {
                            continue;
                        }

                        $final = [];

                        $creditosConvert = 0;

                        $cumpleCondicion = true;

                        $condicionesproducto = 0;
                        $cumpleCondicionProd = false;

                        $condicionesProveedor = 0;
                        $cumpleCondicionProv = false;

                        $cumpleCondicionCont = 0;
                        $cumpleCondicionPais = false;

                        $minBetPrice = 0;
                        $minBetPrice2 = 0;
                        $NUMBERCASINOSTICKERS = 0;

                        $pegatinas = $value->{"sorteo_interno.pegatinas"};

                        $detalleCuotaTotal = 1;

                        $ConfigurationEnvironment = new ConfigurationEnvironment();

                        if (!$ConfigurationEnvironment->isDevelopment() || true) {
                            //$sqlSport = "select te.bet_mode,te.vlr_apuesta,td.ticket_id,td.apuesta,td.agrupador,td.logro,td.opcion,td.apuesta_id,td.agrupador_id,td.fecha_evento,td.hora_evento,te.usuario_id,td.sportid,td.matchid,td.ligaid from it_ticket_det td INNER JOIN it_ticket_enc te ON (td.ticket_id = te.ticket_id ) where  te.ticket_id='".$ticketId."' ";
                            $sqlSport = "select te.vlr_apuesta,td.ticket_id,td.apuesta,td.agrupador,td.logro,td.opcion,td.apuesta_id,td.agrupador_id,td.sportid,td.fecha_evento,td.hora_evento,te.usuario_id from it_ticket_det td INNER JOIN it_ticket_enc te ON (td.ticket_id = te.ticket_id ) where  te.ticket_id='" . $datanum->{"it_ticket_enc.ticket_id"} . "' ";

                        } else {
                            $sqlSport = "select te.vlr_apuesta,te.ticket_id,td.apuesta,td.agrupador,td.logro,td.opcion,td.apuesta_id,td.agrupador_id,td.sportid,td.fecha_evento,te.usuario_id from transsportsbook_detalle td INNER JOIN transaccion_sportsbook te ON (td.transsport_id = te.transsport_id ) where  te.ticket_id='" . $datanum->{"it_ticket_enc.ticket_id"} . "' ";
                        }

                        $detalleTicket = execQuery('', $sqlSport);

                        $array = array();

                        foreach ($detalleTicket as $detalle) {
                            $detalle->sportid = $detalle->{'td.sportid'};
                            $detalle->agrupador_id = $detalle->{'td.agrupador_id'};
                            $detalle->ligaid = $detalle->{'td.ligaid'};
                            $detalle->logro = $detalle->{'td.logro'};
                            $detalle->vlr_apuesta = $detalle->{'te.vlr_apuesta'};

                            $detalles = array(
                                "DeporteMercado" => $detalle->sportid . "M" . $detalle->agrupador_id,
                                "Deporte" => $detalle->sportid,
                                "Liga" => $detalle->ligaid,
                                // "Evento"=>$detalle->apuesta_id,
                                "Cuota" => $detalle->logro

                            );
                            $detalleValorApuesta = $detalle->vlr_apuesta;

                            array_push($array, $detalles);

                            if (!$ConfigurationEnvironment->isDevelopment()) {
                                $usuarioId = $detalle->usuario_id;
                            }
                            $bet_mode = $detalle->bet_mode;
                            $detalleCuotaTotal = $detalleCuotaTotal * $detalle->logro;

                        }
                        $detallesFinal = json_decode(json_encode($array));

                        $detalleSelecciones = $detallesFinal;


                        if (oldCount($CondiDeportes) > 0) {


                            foreach ($CondiDeportes as $Deport) {

                                foreach ($detalleSelecciones as $item) {


                                    if ($tipocomparacion == "OR") {
                                        if ($Deport == $item->Deporte) {
                                            $cumplecondicionproducto = true;
                                        }
                                    } elseif ($tipocomparacion == "AND") {
                                        if ($Deport != $item->Deporte) {
                                            $cumplecondicionproducto = false;
                                        }

                                        if ($condicionesproducto == 0) {
                                            if ($Deport == $item->Deporte) {
                                                $cumplecondicionproducto = true;
                                            }
                                        } else {
                                            if ($Deport == $item->Deporte && $cumplecondicionproducto) {
                                                $cumplecondicionproducto = true;
                                            }
                                        }

                                    }

                                }
                                if ($cumplecondicionproducto == false) {
                                    $BonoInterno = new BonoInterno();
                                    $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                    VALUES ('{$datanum->{"usuario.usuario_id"}}','SORTEO','{$value->{"sorteo_interno.sorteo_id"}}','SPORTBOOK','{$datanum->{"it_ticket_enc.ticket_id"}}','ITAINMENT1')";
                                    //$BonoInterno->execQuery($transaccion, $sqlLog);
                                }
                            }

                            $condicionesproducto++;

                        }

                        if (oldCount($CondiLiga) > 0) {

                            foreach ($detalleSelecciones as $item) {
                                if ($tipocomparacion == "OR") {
                                    if ($value2->{"sorteo_detalle.valor"} == $item->Liga) {
                                        $cumplecondicionproducto = true;

                                    }
                                } elseif ($tipocomparacion == "AND") {
                                    if ($value2->{"sorteo_detalle.valor"} != $item->Liga) {
                                        $cumplecondicionproducto = false;

                                    }

                                    if ($condicionesproducto == 0) {
                                        if ($value2->{"sorteo_detalle.valor"} == $item->Liga) {
                                            $cumplecondicionproducto = true;
                                        }
                                    } else {
                                        if ($value2->{"sorteo_detalle.valor"} == $item->Liga && $cumplecondicionproducto) {
                                            $cumplecondicionproducto = true;

                                        }
                                    }

                                }
                                if ($cumplecondicionproducto == false) {
                                    $BonoInterno = new BonoInterno();
                                    $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                    VALUES ('{$datanum->{"usuario.usuario_id"}}','SORTEO','{$value->{"sorteo_interno.sorteo_id"}}','SPORTBOOK','{$datanum->{"it_ticket_enc.ticket_id"}}','ITAINMENT3')";
                                    //$BonoInterno->execQuery($transaccion, $sqlLog);
                                }

                            }

                            $condicionesproducto++;

                        }

                        if (oldCount($CondiEvento) > 0) {

                            foreach ($detalleSelecciones as $item) {
                                if ($tipocomparacion == "OR") {
                                    if ($value2->{"sorteo_detalle.valor"} == $item->Evento) {
                                        $cumplecondicionproducto = true;

                                    }
                                } elseif ($tipocomparacion == "AND") {
                                    if ($value2->{"sorteo_detalle.valor"} != $item->Evento) {
                                        $cumplecondicionproducto = false;

                                    }

                                    if ($condicionesproducto == 0) {

                                        if ($value2->{"sorteo_detalle.valor"} == $item->Evento) {
                                            $cumplecondicionproducto = true;
                                        }
                                    } else {

                                        if ($value2->{"sorteo_detalle.valor"} == $item->Evento && $cumplecondicionproducto) {
                                            $cumplecondicionproducto = true;

                                        }
                                    }

                                }
                                if ($cumplecondicionproducto == false) {
                                    $BonoInterno = new BonoInterno();
                                    $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                    VALUES ('{$datanum->{"usuario.usuario_id"}}','SORTEO','{$value->{"sorteo_interno.sorteo_id"}}','SPORTBOOK','{$datanum->{"it_ticket_enc.ticket_id"}}','ITAINMENT4')";
                                    //$BonoInterno->execQuery($transaccion, $sqlLog);
                                }
                            }

                            $condicionesproducto++;
                        }

                        if (oldCount($CondiDeporteMercado) > 0) {

                            foreach ($detalleSelecciones as $item) {
                                if ($tipocomparacion == "OR") {
                                    if ($value2->{"sorteo_detalle.valor"} == $item->DeporteMercado) {
                                        $cumplecondicionproducto = true;


                                    }
                                } elseif ($tipocomparacion == "AND") {
                                    if ($value2->{"sorteo_detalle.valor"} != $item->DeporteMercado) {
                                        $cumplecondicionproducto = false;

                                    }

                                    if ($condicionesproducto == 0) {
                                        if ($value2->{"sorteo_detalle.valor"} == $item->DeporteMercado) {
                                            $cumplecondicionproducto = true;
                                        }
                                    } else {
                                        if ($value2->{"sorteo_detalle.valor"} == $item->DeporteMercado && $cumplecondicionproducto) {
                                            $cumplecondicionproducto = true;

                                        }
                                    }

                                }
                                if ($cumplecondicionproducto == false) {
                                    $BonoInterno = new BonoInterno();
                                    $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                    VALUES ('{$datanum->{"usuario.usuario_id"}}','SORTEO','{$value->{"sorteo_interno.sorteo_id"}}','SPORTBOOK','{$datanum->{"it_ticket_enc.ticket_id"}}','ITAINMENT5')";
                                    //$BonoInterno->execQuery($transaccion, $sqlLog);
                                }
                            }

                            $condicionesproducto++;
                        }

                        if ($condDetallesSelecCouta > 0) {

                            foreach ($detalleSelecciones as $item) {
                                if ($condDetallesSelecCouta > $item->Cuota) {
                                    $cumpleCondicion = false;
                                    $BonoInterno = new BonoInterno();
                                    $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                VALUES ('{$datanum->{"usuario.usuario_id"}}','SORTEO','{$value->{"sorteo_interno.sorteo_id"}}','SPORTBOOK','{$datanum->{"it_ticket_enc.ticket_id"}}','MINSELPRICE')";
                                    //$BonoInterno->execQuery($transaccion, $sqlLog);
                                }
                            }
                        }

                        if ($condDetalleCuotaTotal > 0) {

                            foreach ($detalleSelecciones as $item) {
                                if ($condDetalleCuotaTotal > $detalleCuotaTotal) {
                                    $cumpleCondicion = false;
                                    $BonoInterno = new BonoInterno();
                                    $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                VALUES ('{$datanum->{"usuario.usuario_id"}}','SORTEO','{$value->{"sorteo_interno.sorteo_id"}}','SPORTBOOK','{$datanum->{"it_ticket_enc.ticket_id"}}','MINSELPRICETOTAL')";
                                    //$BonoInterno->execQuery($transaccion, $sqlLog);
                                }
                            }
                        }

                        if (oldCount($condPaises) > 0) {

                            if (in_array($datanum->{"usuario_mandante.pais_id"}, $condPaises)) {
                                $cumpleCondicionPais = true;
                            }
                            if ($cumpleCondicionPais == false) {
                                $BonoInterno = new BonoInterno();
                                $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                VALUES ('{$datanum->{"usuario.usuario_id"}}','SORTEO','{$value->{"sorteo_interno.sorteo_id"}}','SPORTBOOK','{$datanum->{"it_ticket_enc.ticket_id"}}','CONDPAISUSER')";
                                //$BonoInterno->execQuery($transaccion, $sqlLog);
                            }
                            $cumpleCondicionCont++;
                        }


                        if ($condbet_mode == 2) {

                            if ($datanum->{"it_ticket_enc.bet_mode"} == "PreLive") {

                                $cumplecondicionproducto = true;

                            } else {
                                $cumplecondicionproducto = false;
                                $BonoInterno = new BonoInterno();
                                $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                VALUES ('{$datanum->{"usuario.usuario_id"}}','SORTEO','{$value->{"sorteo_interno.sorteo_id"}}','SPORTBOOK','{$datanum->{"it_ticket_enc.ticket_id"}}','LIVEORPREMATCH')";
                                //$BonoInterno->execQuery($transaccion, $sqlLog);

                            }

                        }

                        if ($condbet_mode == 1) {
                            if ($datanum->{"it_ticket_enc.bet_mode"} == "Live") {
                                $cumplecondicionproducto = true;
                            } else {
                                $cumplecondicionproducto = false;
                                $BonoInterno = new BonoInterno();
                                $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                VALUES ('{$datanum->{"usuario.usuario_id"}}','SORTEO','{$value->{"sorteo_interno.sorteo_id"}}','SPORTBOOK','{$datanum->{"it_ticket_enc.ticket_id"}}','LIVEORPREMATCH')";
                                //$BonoInterno->execQuery($transaccion, $sqlLog);
                            }

                        }

                        if ($condbet_mode == 0) {
                            /*if($datanum->{"it_ticket_enc.bet_mode"}== == "Mixed") {
                                $cumplecondicionproducto = true;
                            }else{
                                $cumplecondicionproducto = false;
                            }*/
                        }


                        foreach ($condMinBetPrice as $moneda => $valor) {
                            if ($moneda == $datanum->{"usuario.moneda"}) {
                                $minBetPrice = floatval($valor);

                            }
                        }

                        foreach ($condMinBetPrice2 as $moneda2 => $valor2) {

                            if ($moneda2 == $datanum->{"usuario.moneda"}) {
                                $minBetPrice2 = floatval($valor2);

                            }

                        }


                        if ($condicionesproducto > 0 && !$cumplecondicionproducto) {

                            $cumpleCondicion = false;
                        }

                        if ($condicionesProveedor > 0 && !$cumpleCondicionProv) {

                            $cumpleCondicion = false;
                        }

                        if (!$cumpleCondicionPais && $cumpleCondicionCont > 0) {


                            $cumpleCondicion = false;
                        }


                        if ($minBetPrice2 > floatval($datanum->{"it_ticket_enc.vlr_apuesta"})) {

                            $cumpleCondicion = false;
                            $BonoInterno = new BonoInterno();
                            $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                VALUES ('{$datanum->{"usuario.usuario_id"}}','SORTEO','{$value->{"sorteo_interno.sorteo_id"}}','SPORTBOOK','{$datanum->{"it_ticket_enc.ticket_id"}}','MINBETPRICE2SPORTSBOOK')";
                            //$BonoInterno->execQuery($transaccion, $sqlLog);

                        }


                        if ($sePuedeCombinadas != 0 || $sePuedeSimples != 0) {
                            $condItainment82 = true;
                            if (oldCount($detalleSelecciones) == 1 && !$sePuedeSimples) {
                                $cumpleCondicion = false;
                                $condItainment82 = false;
                            }

                            if (oldCount($detalleSelecciones) > 1 && !$sePuedeCombinadas) {
                                $cumpleCondicion = false;
                                $condItainment82 = false;
                            }

                            if ($sePuedeCombinadas) {
                                if (oldCount($detalleSelecciones) > 1 && oldCount($detalleSelecciones) < $minselcount) {
                                    $cumpleCondicion = false;
                                    $condItainment82 = false;
                                }
                            }

                            if ($condItainment82 == false) {
                                $BonoInterno = new BonoInterno();
                                $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                VALUES ('{$datanum->{"usuario.usuario_id"}}','SORTEO','{$value->{"sorteo_interno.sorteo_id"}}','SPORTBOOK','{$datanum->{"it_ticket_enc.ticket_id"}}','ITAINMENT82')";
                                //$BonoInterno->execQuery($transaccion, $sqlLog);
                            }
                        } else {
                            if (oldCount($detalleSelecciones) > 1 && oldCount($detalleSelecciones) < $minselcount) {
                                $cumpleCondicion = false;

                            }
                        }

                        $valorTicket = floatval($datanum->{"it_ticket_enc.vlr_apuesta"});


                        if ($cumpleCondicion) {

                            if ($puederepetirBono) {


                            } else {


                                $sqlRepiteSorteo = "select * from usuario_sorteo a where  a.ususorteo_id !=0 and  a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.usuario_id = '" . $datanum->{"usuario.usuario_id"} . "'";
                                $repiteSorteo = execQuery('', $sqlRepiteSorteo);

                                if ((!$puederepetirBono && oldCount($repiteSorteo) == 0)) {

                                } else {
                                    $cumpleCondicion = false;
                                    $BonoInterno = new BonoInterno();
                                    $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                VALUES ('{$datanum->{"usuario.usuario_id"}}','SORTEO','{$value->{"sorteo_interno.sorteo_id"}}','SPORTBOOK','{$datanum->{"it_ticket_enc.ticket_id"}}','REPETIRSORTEO')";
                                    //$BonoInterno->execQuery($transaccion, $sqlLog);
                                }

                            }
                        }


                        if ($needSubscribe) {


                            $rules = [];
                            array_push($rules, ['field' => 'usuario_sorteo.estado', 'data' => 'I', 'op' => 'eq']);
                            array_push($rules, ['field' => 'usuario_sorteo.sorteo_id', 'data' => $value->{"sorteo_interno.sorteo_id"}, 'op' => 'eq']);
                            array_push($rules, ['field' => 'usuario_sorteo.usuario_id', 'data' => $datanum->{"usuario_mandante.usumandante_id"}, 'op' => 'eq']);

                            $filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);
                            $UsuarioSorteo2 = new UsuarioSorteo();
                            $allCoupons = (string)$UsuarioSorteo2->getUsuarioSorteosCustom('COUNT(distinct(usuario_sorteo.usuario_id)) countUsers,COUNT((usuario_sorteo.ususorteo_id)) countStickers', 'usuario_sorteo.ususorteo_id', 'asc', 0, 1000000, $filter, true);

                            $allCoupons = json_decode($allCoupons, true);


                            if ($allCoupons['count'][0]['.count'] > 0) {
                            } else {
                                $cumpleCondicion = false;
                                $BonoInterno = new BonoInterno();
                                $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                VALUES ('{$datanum->{"usuario.usuario_id"}}','SORTEO','{$value->{"sorteo_interno.sorteo_id"}}','SPORTBOOK','{$datanum->{"it_ticket_enc.ticket_id"}}','NEEDSUSCRIBE')";
                                //$BonoInterno->execQuery($transaccion, $sqlLog);
                            }
                        }


                        if ($cumpleCondicion) {

                            $ItTicketEncInfo1MySqlDAO = new ItTicketEncInfo1MySqlDAO();


                            array_push($ArrayTransaction, "'" . $datanum->{"it_ticket_enc.ticket_id"} . "'");

                            if ($pegatinas == 1) {
                                $BonoInterno = new BonoInterno();
                                $sqlSorteo = "CALL insert_sorteos({$minBetPrice},{$datanum->{"it_ticket_enc.vlr_apuesta"}},{$value->{"sorteo_interno.sorteo_id"}},{$datanum->{"usuario.usuario_id"}}, '2',{$datanum->{"usuario.mandante"}})";
                                $BonoInterno->execQuery($transaccion, $sqlSorteo);
                            } else {
                                $estado = "P";
                                $sqlRepiteSorteo = "select * from usuario_sorteo a where  a.ususorteo_id !=0 and  a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.usuario_id = '" . $datanum->{"usuario.usuario_id"} . "' AND a.estado = '" . $estado . "'";
                                $repiteSorteo = execQuery('', $sqlRepiteSorteo);


                                if (oldCount($repiteSorteo) == 0) {
                                    $UsuarioSorteo = new UsuarioSorteo();
                                    $UsuarioSorteo->usuarioId = $datanum->{"usuario_mandante.usumandante_id"};
                                    $UsuarioSorteo->sorteoId = $value->{"sorteo_interno.sorteo_id"};
                                    $UsuarioSorteo->valor = 0;
                                    $UsuarioSorteo->posicion = 0;
                                    $UsuarioSorteo->valorBase = $minBetPrice;
                                    $UsuarioSorteo->usucreaId = 0;
                                    $UsuarioSorteo->usumodifId = 0;


                                    if ($datanum->{"it_ticket_enc.vlr_apuesta"} < $minBetPrice) {
                                        $UsuarioSorteo->estado = "P";

                                    } else {
                                        $UsuarioSorteo->estado = "A";
                                    }

                                    $UsuarioSorteo->errorId = 0;
                                    $UsuarioSorteo->idExterno = 0;
                                    $UsuarioSorteo->mandante = 0;
                                    $UsuarioSorteo->version = 0;
                                    $UsuarioSorteo->apostado = $datanum->{"it_ticket_enc.vlr_apuesta"};
                                    $UsuarioSorteo->codigo = 0;
                                    $UsuarioSorteo->externoId = 0;
                                    $UsuarioSorteo->valor = $UsuarioSorteo->valor + $creditosConvert;
                                    //$UsuarioSorteo->valorBase = ($UsuarioSorteo->valorBase + $TransaccionApi->valor);


                                    $UsuarioSorteoMySqlDAO = new UsuarioSorteoMySqlDAO($ItTicketEncInfo1MySqlDAO->getTransaction());
                                    $ususorteoId = $UsuarioSorteoMySqlDAO->insert($UsuarioSorteo);

                                } else {
                                    $ususorteoId = $repiteSorteo[0]->{"a.ususorteo_id"};
                                    $BonoInterno = new BonoInterno();

                                    $transaccion = $ItTicketEncInfo1MySqlDAO->getTransaction();

                                    $sql = "UPDATE usuario_sorteo SET apostado = apostado + " . (floatval($datanum->{"it_ticket_enc.vlr_apuesta"})) . " WHERE ususorteo_id =" . $ususorteoId;
                                    $BonoInterno->execQuery($transaccion, $sql);

                                }

                            }

                            /*$UsuarioMensaje = new UsuarioMensaje();
                            $UsuarioMensaje->usufromId = 0;
                            $UsuarioMensaje->usutoId = $UsuarioSorteo->getUsuarioId();
                            $UsuarioMensaje->isRead = 0;
                            $UsuarioMensaje->body = '¡ Bien :thumbsup: ! Estas participando en el sorteo ' . $value->{"sorteo_interno.nombre"} . ' :clap:';
                            $UsuarioMensaje->msubject = 'Notificacion';
                            $UsuarioMensaje->parentId = 0;
                            $UsuarioMensaje->proveedorId = 0;
                            $UsuarioMensaje->tipo = "PUSHNOTIFICACION";
                            $UsuarioMensaje->paisId = 0;
                            $UsuarioMensaje->fechaExpiracion = '';

                            $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($ItTicketEncInfo1MySqlDAO->getTransaction());
                            $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);*/


                            $ItTicketEncInfo1 = new ItTicketEncInfo1();
                            $ItTicketEncInfo1->ticketId = $datanum->{"it_ticket_enc.ticket_id"};
                            $ItTicketEncInfo1->tipo = "SORTEOSTICKER";

                            $ItTicketEncInfo1->valor = $idUsuSorteo;
                            $ItTicketEncInfo1->fechaCrea = date('Y-m-d H:i:s');
                            $ItTicketEncInfo1->fechaModif = date('Y-m-d H:i:s');
                            //$ItTicketEncInfo1->valor = $creditosConvert;

                            $ItTicketEncInfo1MySqlDAO->insert($ItTicketEncInfo1);
                            $ItTicketEncInfo1MySqlDAO->getTransaction()->commit();

                        }
                    }
                }
            }


        }

    }


//A partir de aqui es lo que habia antes
    /*

    $rules = [];



    $daydimensionFecha=0;
    $FromDateLocal = date("Y-m-d H:i:s", strtotime('-1 minute'));
    $ToDateLocal = date("Y-m-d H:i:s");

    if (!$ConfigurationEnvironment->isDevelopment()) {
       // $message = "*CRON: (Segundos)  Stickerts * " . " - Fecha: " . $FromDateLocal. " - Fecha: " . $ToDateLocal;
       // exec("php -f /home/home2/backendprodfinal/api/src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

    }
    $TypeBet=2;

    array_push($rules, array("field" => "usuario.pais_id", "data" => "66", "op" => "eq"));
    array_push($rules, array("field" => "usuario.mandante", "data" => "8", "op" => "eq"));
    //array_push($rules, array("field" => "usuario.usuario_id", "data" => "73818", "op" => "eq"));
    array_push($rules, array("field" => "it_ticket_enc.bet_status", "data" => "'S','N'", "op" => "in"));
    array_push($rules, array("field" => "it_ticket_enc.freebet", "data" => "0", "op" => "eq"));
    array_push($rules, array("field" => "it_ticket_enc.eliminado", "data" => "N", "op" => "eq"));


    if ( $FromDateLocal != "") {
        //array_push($rules, array("field" => "CONCAT(it_ticket_enc.fecha_crea,' ',it_ticket_enc.hora_crea)", "data" => "$FromDateLocal", "op" => "ge"));
        if ($TypeBet == 2) {
            array_push($rules, array("field" => "(it_ticket_enc.fecha_cierre_time)", "data" => "$FromDateLocal", "op" => "ge"));
            $daydimensionFecha=2;
        } else if ($TypeBet == 3) {
            array_push($rules, array("field" => "(it_ticket_enc.fecha_pago_time)", "data" => "$FromDateLocal", "op" => "ge"));
            $daydimensionFecha=1;

        } else if ($TypeBet == 4) {
            array_push($rules, array("field" => "(it_ticket_enc.fecha_cierre_time)", "data" => "$FromDateLocal", "op" => "ge"));
            $daydimensionFecha=2;

        } else {
            array_push($rules, array("field" => "(it_ticket_enc.fecha_crea_time)", "data" => "$FromDateLocal", "op" => "ge"));
        }
    }
    if ( $ToDateLocal != "") {
        //array_push($rules, array("field" => "CONCAT(it_ticket_enc.fecha_crea,' ',it_ticket_enc.hora_crea)", "data" => "$ToDateLocal", "op" => "le"));

        if ($TypeBet == 2) {
            array_push($rules, array("field" => "(it_ticket_enc.fecha_cierre_time)", "data" => "$ToDateLocal", "op" => "le"));
            $daydimensionFecha=2;
        } else if ($TypeBet == 3) {
            array_push($rules, array("field" => "(it_ticket_enc.fecha_pago_time)", "data" => "$ToDateLocal", "op" => "le"));
            $daydimensionFecha=1;

        } else if ($TypeBet == 4) {
            array_push($rules, array("field" => "(it_ticket_enc.fecha_cierre_time)", "data" => "$FromDateLocal", "op" => "ge"));
            $daydimensionFecha=2;

        } else {
            array_push($rules, array("field" => "(it_ticket_enc.fecha_crea_time)", "data" => "$ToDateLocal", "op" => "le"));

        }
    }
    array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "USUONLINE", "op" => "eq"));

    //array_push($rules, array("field" => "it_ticket_enc.bet_status", "data" => "T", "op" => "eq"));

    // array_push($rules, array("field" => "it_ticket_enc.usuario_id", "data" => "$ClientId", "op" => "eq"));

    $SkeepRows=0;
    $MaxRows=10000;

    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);


    $ItTicketEnc = new ItTicketEnc();
    $tickets = $ItTicketEnc->getTicketsCustom(" usuario.mandante,usuario.usuario_id,usuario.nombre,usuario.login,usuario.moneda,it_ticket_enc.ticket_id,it_ticket_enc.vlr_apuesta,it_ticket_enc.vlr_premio",  'it_ticket_enc.ticket_id',"desc",$SkeepRows, $MaxRows, $json, true,"","",true,$daydimensionFecha,false);
    $tickets = json_decode($tickets);
    $final = [];|

    foreach ($tickets->data as $key => $value) {



        $detalles2 = array(
        );
        $SorteoInterno = new SorteoInterno();
        $respuesta = $SorteoInterno->verificarSorteoUsuario($value->{'usuario.usuario_id'},$detalles2,'SPORTBOOK',$value->{'it_ticket_enc.ticket_id'});


    }*/


    sleep(3);

}
/**
 * Ejecutar un query
 *
 *
 * @param Objeto transaccion transaccion
 * @param String sql sql
 *
 * @return Array $result resultado de la verificación
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */
function execQuery($transaccion, $sql)
{

    $SorteoInternoMySqlDAO = new SorteoInternoMySqlDAO($transaccion);
    $return = $SorteoInternoMySqlDAO->querySQL($sql);
    $return = json_decode(json_encode($return), FALSE);

    return $return;

}