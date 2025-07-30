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
use Backend\dto\PreUsuarioTorneo;
use Backend\dto\TorneoDetalle;
use Backend\dto\CategoriaProducto;
use Backend\dto\TorneoInterno;
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
use Backend\dto\UsuarioTorneo;
use Backend\dto\Mandante;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\BonoLogMySqlDAO;
use Backend\mysql\PreUsuarioTorneoMySqlDAO;
use Backend\mysql\TorneoDetalleMySqlDAO;
use Backend\mysql\TorneoInternoMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransjuegoLogMySqlDAO;
use Backend\mysql\UsuarioBonoMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\UsuarioTorneoMySqlDAO;
use Backend\websocket\WebsocketUsuario;

use Backend\dao\TransjuegoInfoDAO;
use Backend\dto\UsuarioConfiguracion;
use Backend\mysql\ItTicketEncInfo1MySqlDAO;
use Backend\mysql\TransjuegoInfoMySqlDAO;
use Exception;



/**
 * Clase 'CronJobTorneoDeportivas'
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
class CronJobTorneoDeportivas
{


    public function __construct()
    {
    }

    public function execute()
    {


            $message = "*CRON: (cronTorneos) * " . " - Fecha: " . date("Y-m-d H:i:s");
            $responseEnable = file_get_contents(__DIR__ . '/../../logSit/enabled');

            if ($responseEnable == 'BLOCKED') {
                exit();
            }


            $filename = __DIR__ . '/lastrunCronTorneosDeportivas';
            $argv1 = $argv[1];
            $datefilename = date("Y-m-d H:i:s", filemtime($filename));

            if ($datefilename <= date("Y-m-d H:i:s", strtotime('-1 hour'))) {
                unlink($filename);
            }

            if (file_exists($filename)) {
                throw new Exception("There is a process currently running", "1");
                exit();
            }
            file_put_contents($filename, 'RUN');


            $BonoInterno = new BonoInterno();

            $sqlProcesoInterno2 = "SELECT * FROM proceso_interno2 WHERE tipo='TORNEOSPORTSBOOK'";


            $data = $BonoInterno->execQuery('', $sqlProcesoInterno2);
            $data = $data[0];

            $line = $data->{'proceso_interno2.fecha_ultima'};

            if ($line == '') {
                exit();
            }


            $fechaL1 = date('Y-m-d H:i:00', strtotime($line . '+1 minute'));
            $fechaL2 = date('Y-m-d H:i:59', strtotime($line . '+60 minute'));


            if ($fechaL2 >= date('Y-m-d H:i:00')) {
                unlink($filename);
                exit();
            }

            $BonoInterno = new BonoInterno();
            $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
            $transaccion = $BonoDetalleMySqlDAO->getTransaction();

            $sqlProcesoInterno2 = "UPDATE proceso_interno2 SET fecha_ultima='" . $fechaL2 . "' WHERE  tipo='TORNEOSPORTSBOOK';";


            $data = $BonoInterno->execQuery($transaccion, $sqlProcesoInterno2);
            $transaccion->commit();


            $log = "\r\n" . "-------------------------" . "\r\n";
            $log = $log . "Inicia: " . date('Y-m-d H:i:s');
            $fp = fopen(__DIR__ . '/logs/Slog_' . date("Y-m-d") . '.log', 'a');

            if (true) {

            print_r('INGRESO AL CRON');
            $rules = [];

            $debug = false;

            $BonoInterno = new BonoInterno();


            $ConfigurationEnvironment = new ConfigurationEnvironment();

            $TorneoInterno = new TorneoInterno();

            $rules = [];

            array_push($rules, array("field" => "torneo_interno.estado", "data" => "A", "op" => "eq"));
            array_push($rules, array("field" => "torneo_interno.fecha_inicio", "data" => date('Y-m-d H:i:s'), "op" => "le"));
            array_push($rules, array("field" => "torneo_interno.fecha_fin", "data" => date('Y-m-d H:i:s'), "op" => "ge"));
            array_push($rules, array("field" => "torneo_interno.tipo", "data" => "1", "op" => "eq"));


            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);

            $data = $TorneoInterno->getTorneosCustom("torneo_interno.*", "torneo_interno.orden", "DESC", 0, 1000, $json, true);

            $data = json_decode($data);
            print_r('torneo aceptado');


            foreach ($data->data as $key => $value) {

                $TorneoDetalle = new TorneoDetalle();

                $rules = [];

                array_push($rules, array("field" => "torneo_interno.torneo_id", "data" => $value->{"torneo_interno.torneo_id"}, "op" => "eq"));
                array_push($rules, array("field" => "torneo_interno.estado", "data" => "A", "op" => "eq"));
                array_push($rules, array("field" => "torneo_interno.mandante", "data" => $value->{"torneo_interno.mandante"}, "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                $json = json_encode($filtro);

                $torneodetalles = $TorneoDetalle->getTorneoDetallesCustom(" torneo_detalle.*,torneo_interno.* ", "torneo_interno.orden", "desc", 0, 1000, $json, TRUE);

                $torneodetalles = json_decode($torneodetalles);
                ($data->data[$key])->torneodetalles = $torneodetalles;
            }

            $pos = 1;

            $torneosAnalizados = '';

            $ActivacionSleepTime = true;

            $ArrayTransaction = array('1');


            $Sportsbook = true;

            if (true) {
                foreach ($data->data as $key => $value) {


                    //SPORTSBOOK
                    $condMinBetPrice = array();
                    $condMinBetPrice2 = array();
                    $condDetallesSelecCouta = 0;
                    $CondiDeportes = array();
                    $CondiLiga = array();
                    $CondiEvento = array();
                    $CondiDeporteMercado = array();
                    $condDetalleCuotaTotal = 0;
                    $condDetalleMinSelCount = 0;
                    $condRank = array();
                    $needSubscribe = false;

                    /*

                    CAMBIOS NUEVOS

                    */

                    $sePuedeCombinadas = 0;
                    $sePuedeSimples = 0;

                    /*

                    FIN CAMBIOS NUEVOS

                    */

                    $condPaises = array();
                    $incomeNetPoint = 0;
                    $pointValueQuota = 0;

                    $torneodetalles = $value->torneodetalles;
                    foreach ($torneodetalles->data as $key2 => $value2) {


                        switch ($value2->{"torneo_detalle.tipo"}) {
                            case "RANK":

                                $arrayT = array(
                                    'valor' => $value2->{"torneo_detalle.valor"},
                                    'valor2' => $value2->{"torneo_detalle.valor2"},
                                    'valor3' => $value2->{"torneo_detalle.valor3"}
                                );

                                if ($condRank[$value2->{"torneo_detalle.moneda"}] == null) {
                                    $condRank[$value2->{"torneo_detalle.moneda"}] = array();
                                }

                                array_push($condRank[$value2->{"torneo_detalle.moneda"}], $arrayT);


                                break;

                            case "RANKNETINCOME":

                                $incomeNetPoint = $value2->{"torneo_detalle.valor"};

                                break;

                            case "RANKQUOTAS":

                                $pointValueQuota = $value2->{"torneo_detalle.valor"};

                                break;

                            case "MINBETPRICESPORTSBOOK":

                                $condMinBetPrice[$value2->{"torneo_detalle.moneda"}] = floatval($value2->{"torneo_detalle.valor"});

                                break;

                            case "VISIBILIDAD":

                                if ($value2->{"torneo_detalle.valor"} == 1) {
                                    $needSubscribe = true;
                                }

                                break;


                            case "USERSUBSCRIBE":

                                if ($value2->{"torneo_detalle.valor"} == 0) {

                                } else {
                                    $needSubscribe = true;
                                }

                                break;

                            case "CONDPAISUSER":

                                if ($value2->{"torneo_detalle.valor"} == '') {
                                    $value2->{"torneo_detalle.valor"} = '0';
                                }

                                array_push($condPaises, $value2->{"torneo_detalle.valor"});

                                break;


                            case "LIVEORPREMATCH":

                                $condbet_mode = $value2->{"torneo_detalle.valor"};

                                break;

                            case "MINSELCOUNT":

                                // Realmente se debe Validar?
                                $minselcount = $value2->{"torneo_detalle.valor"};

                                $condDetalleMinSelCount = $value2->{"torneo_detalle.valor"};


                                break;

                            case "MINSELPRICE":

                                $condDetallesSelecCouta = $value2->{"torneo_detalle.valor"};


                                break;

                            case "MINSELPRICETOTAL":


                                $condDetalleCuotaTotal = $value2->{"torneo_detalle.valor"};


                                break;

                            case "ITAINMENT1":

                                if ($value2->{"torneo_detalle.valor"} == '') {
                                    $value2->{"torneo_detalle.valor"} = '0';
                                }

                                array_push($CondiDeportes, $value2->{"torneo_detalle.valor"});

                                break;

                            case "ITAINMENT3":

                                if ($value2->{"torneo_detalle.valor"} == '') {
                                    $value2->{"torneo_detalle.valor"} = '0';
                                }

                                array_push($CondiLiga, $value2->{"torneo_detalle.valor"});

                                break;

                            case "ITAINMENT4":

                                if ($value2->{"torneo_detalle.valor"} == '') {
                                    $value2->{"torneo_detalle.valor"} = '0';
                                }
                                array_push($CondiEvento, $value2->{"torneo_detalle.valor"});

                                break;

                            case "ITAINMENT5":

                                if ($value2->{"torneo_detalle.valor"} == '') {
                                    $value2->{"torneo_detalle.valor"} = '0';
                                }

                                array_push($CondiDeporteMercado, $value2->{"torneo_detalle.valor"});

                                break;

                            case "ITAINMENT82":

                                if ($value2->{"torneo_detalle.valor"} == 1) {
                                    $sePuedeSimples = 1;

                                }

                                if ($value2->{"torneo_detalle.valor"} == 2) {
                                    $sePuedeCombinadas = 1;

                                }
                                break;

                            default:


                                break;

                        }

                    }

                    //$sqlSport = "select te.bet_mode,te.vlr_apuesta,td.ticket_id,td.apuesta,td.agrupador,td.logro,td.opcion,td.apuesta_id,td.agrupador_id,td.fecha_evento,td.hora_evento,te.usuario_id,td.sportid,td.matchid,td.ligaid from it_ticket_det td INNER JOIN it_ticket_enc te ON (td.ticket_id = te.ticket_id ) where  te.ticket_id='".$ticketId."' ";
                    $sqlSport = "SET group_concat_max_len = 18446744073709551615;";

                    $sqlArt = "";
                    if (oldCount($condPaises) > 0) {
                        $sqlArt .= " AND ((usuario.pais_id)) IN (" . implode(',', $condPaises) . ") ";
                    }
                    if (oldCount($CondiDeportes) > 0) {
                        $sqlArt .= " AND ((it_ticket_det.sportid)) IN (" . implode(',', array_map(fn($v) => "'$v'", $CondiDeportes)) . ") ";
                    }
                    if (oldCount($CondiLiga) > 0) {
                        $sqlArt .= " AND ((it_ticket_det.ligaid)) IN (" . implode(',', array_map(fn($v) => "'$v'", $CondiLiga)) . ") ";
                    }

                    if (oldCount($CondiEvento) > 0) {
                        $sqlArt .= " AND ((it_ticket_det.matchid)) IN (" . implode(',', array_map(fn($v) => "'$v'", $CondiEvento))  . ") ";
                    }

                    //CONDICION PARA GANANCIA DE PUNTOS RANK
                    if ($incomeNetPoint == 0) {
                        print_r('ENTRO EN RANK NUEVO SIN VALOR POR PUNTO');

                        $sqlSport = "select usuario.mandante,
                                usuario_mandante.pais_id,
                                usuario_mandante.usumandante_id,
                                usuario.usuario_id,
                                usuario.nombre,
                                usuario.login,
                                usuario.moneda,
                                it_ticket_enc.ticket_id,
                                it_ticket_enc.bet_mode,
                                it_ticket_enc.vlr_apuesta,
                                it_ticket_enc.vlr_premio,
                                it_ticket_enc.fecha_crea_time,
                                it_ticket_det.*
                                from it_ticket_det
                                INNER JOIN(select it_ticket_enc.ticket_id
                                from it_ticket_det
                                INNER JOIN
                                (select it_ticket_enc.ticket_id
                                from it_ticket_det
                                INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_ticket_det.ticket_id)
                                LEFT OUTER JOIN it_ticket_enc_info1  ON (it_ticket_enc_info1.ticket_id = it_ticket_det.ticket_id and it_ticket_enc_info1.tipo='TORNEO')
                                INNER JOIN usuario ON (it_ticket_enc.usuario_id = usuario.usuario_id)
                                INNER JOIN usuario_mandante ON (usuario_mandante.usuario_mandante = usuario.usuario_id and usuario_mandante.mandante = usuario.mandante)
                                INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = usuario.usuario_id)
                                where 1 = 1 and it_ticket_enc_info1.it_ticket2_id is null
                                AND ((it_ticket_enc.bet_status)) != 'T'
                                AND ((it_ticket_enc.freebet)) = '0'
                                AND ((it_ticket_enc.eliminado)) = 'N'
                                AND ((it_ticket_enc.mandante)) = '" . $value->{"torneo_interno.mandante"} . "' " . $sqlArt . "
                                AND (((it_ticket_enc.fecha_crea_time))) >= '" . $fechaL1 . "'
                                AND (((it_ticket_enc.fecha_crea_time))) <= '" . $fechaL2 . "'
                                AND ((usuario_perfil.perfil_id)) = 'USUONLINE') a ON (a.ticket_id = it_ticket_det.ticket_id)
                                INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_ticket_det.ticket_id)
                                LEFT OUTER JOIN it_ticket_enc_info1
                                ON (it_ticket_enc_info1.ticket_id = it_ticket_det.ticket_id and
                                it_ticket_enc_info1.tipo = 'TORNEO')
                                INNER JOIN usuario ON (it_ticket_enc.usuario_id = usuario.usuario_id)
                                INNER JOIN usuario_mandante ON (usuario_mandante.usuario_mandante = usuario.usuario_id and
                                usuario_mandante.mandante = usuario.mandante)
                                INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = usuario.usuario_id)
                                group by it_ticket_det.ticket_id
                                HAVING min(logro)>" . $condDetallesSelecCouta . ") b ON (b.ticket_id = it_ticket_det.ticket_id)
                                INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_ticket_det.ticket_id)
                                INNER JOIN usuario ON (it_ticket_enc.usuario_id = usuario.usuario_id)
                                INNER JOIN usuario_mandante ON (usuario_mandante.usuario_mandante = usuario.usuario_id and
                                usuario_mandante.mandante = usuario.mandante)
                                INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = usuario.usuario_id); ";
                        $detalleTicket = $this->execQuery('', $sqlSport);

                        $dataUsuarioProv = array();
                        $dataUsuarioProv2 = array();
                        foreach ($detalleTicket as $detalle) {

                            $detalle->bet_mode = $detalle->{'it_ticket_enc.bet_mode'};
                            $dataUsuarioProv2[$detalle->{'it_ticket_enc.ticket_id'}] = $detalle;
                        }

                        $array = array();
                        $detalleCuotaTotal = 1;
                        $bet_mode = '';
                        foreach ($detalleTicket as $detalle) {
                            $detalle->sportid = $detalle->{'it_ticket_det.sportid'};
                            $detalle->ligaid = $detalle->{'it_ticket_det.ligaid'};
                            $detalle->agrupador_id = $detalle->{'it_ticket_det.agrupador_id'};
                            $detalle->logro = $detalle->{'it_ticket_det.logro'};
                            $detalle->vlr_apuesta = $detalle->{'it_ticket_enc.vlr_apuesta'};
                            $detalle->bet_mode = $detalle->{'it_ticket_enc.bet_mode'};

                            $detalles = array(
                                "DeporteMercado" => $detalle->sportid . "M" . $detalle->agrupador_id,
                                "Deporte" => $detalle->sportid,
                                "Liga" => $detalle->ligaid,
                                // "Evento"=>$detalle->apuesta_id,
                                "Cuota" => $detalle->logro

                            );
                            $detalleValorApuesta = $detalle->vlr_apuesta;

                            if (($dataUsuarioProv2[$detalle->{'it_ticket_enc.ticket_id'}])->detallesFinal == null) {
                                ($dataUsuarioProv2[$detalle->{'it_ticket_enc.ticket_id'}])->detallesFinal = array();
                                ($dataUsuarioProv2[$detalle->{'it_ticket_enc.ticket_id'}])->detalleCuotaTotal = 1;
                            }

                            array_push(($dataUsuarioProv2[$detalle->{'it_ticket_enc.ticket_id'}])->detallesFinal, $detalles);

                            $bet_mode = $detalle->bet_mode;
                            ($dataUsuarioProv2[$detalle->{'it_ticket_enc.ticket_id'}])->detalleCuotaTotal = ($dataUsuarioProv2[$detalle->{'it_ticket_enc.ticket_id'}])->detalleCuotaTotal * $detalle->logro;

                        }
                        foreach ($dataUsuarioProv2 as $detalle) {
                            ($dataUsuarioProv2[$detalle->{'it_ticket_enc.ticket_id'}])->detallesFinal = json_decode(json_encode((($dataUsuarioProv2[$detalle->{'it_ticket_enc.ticket_id'}])->detallesFinal)));
                        }
                        $dataUsuario = $dataUsuarioProv2;
                        foreach ($dataUsuario as $key4 => $datanum) {


                            if (in_array($datanum->{"it_ticket_enc.ticket_id"}, $ArrayTransaction)) {
                                continue;
                            }

                            if ($Sportsbook) {

                                if (!($datanum->{"it_ticket_enc.fecha_crea_time"} >= $value->{"torneo_interno.fecha_inicio"}
                                    && $datanum->{"it_ticket_enc.fecha_crea_time"} <= $value->{"torneo_interno.fecha_fin"})
                                ) {
                                    continue;
                                }

                                if (($value->{"torneo_interno.mandante"} != $datanum->{"usuario.mandante"})) {
                                    continue;
                                }


                                if ($value->condicional == 'NA' || $value->condicional == '') {
                                    $tipocomparacion = "OR";

                                } else {
                                    $tipocomparacion = $value->condicional;

                                }


                                //SPORTSBOOK
                                $condMinBetPrice = array();
                                $condMinBetPrice2 = array();
                                $condDetallesSelecCouta = 0;
                                $CondiDeportes = array();
                                $CondiLiga = array();
                                $CondiEvento = array();
                                $CondiDeporteMercado = array();
                                $condDetalleCuotaTotal = 0;
                                $condDetalleMinSelCount = 0;
                                $condRank = array();
                                $needSubscribe = false;
                                $puedeRepetirDeportes = false;
                                $idDeportes = '';
                                $puedeRepetirLigas = false;
                                $idLigas = '';
                                $puedeRepetirMercados = false;
                                $idMercados = '';
                                $sePuedeCombinadas = 0;
                                $sePuedeSimples = 0;

                                $condPaises = array();

                                $bet_mode = "";
                                $condbet_mode = "";


                                $torneodetalles = $value->torneodetalles;
                                foreach ($torneodetalles->data as $key2 => $value2) {


                                    switch ($value2->{"torneo_detalle.tipo"}) {
                                        case "RANK":

                                            $arrayT = array(
                                                'valor' => $value2->{"torneo_detalle.valor"},
                                                'valor2' => $value2->{"torneo_detalle.valor2"},
                                                'valor3' => $value2->{"torneo_detalle.valor3"}
                                            );

                                            if ($condRank[$value2->{"torneo_detalle.moneda"}] == null) {
                                                $condRank[$value2->{"torneo_detalle.moneda"}] = array();
                                            }

                                            array_push($condRank[$value2->{"torneo_detalle.moneda"}], $arrayT);


                                            break;


                                        case "MINBETPRICESPORTSBOOK":

                                            $condMinBetPrice[$value2->{"torneo_detalle.moneda"}] = floatval($value2->{"torneo_detalle.valor"});

                                            break;

                                        case "VISIBILIDAD":

                                            if ($value2->{"torneo_detalle.valor"} == 1) {
                                                $needSubscribe = true;
                                            }

                                            break;


                                        case "USERSUBSCRIBE":

                                            if ($value2->{"torneo_detalle.valor"} == 0) {

                                            } else {
                                                $needSubscribe = true;
                                            }

                                            break;

                                        case "CONDPAISUSER":

                                            if ($value2->{"torneo_detalle.valor"} == '') {
                                                $value2->{"torneo_detalle.valor"} = '0';
                                            }


                                            array_push($condPaises, $value2->{"torneo_detalle.valor"});

                                            break;


                                        case "LIVEORPREMATCH":

                                            $condbet_mode = $value2->{"torneo_detalle.valor"};

                                            break;

                                        case "MINSELCOUNT":

                                            // Realmente se debe Validar?
                                            $minselcount = $value2->{"torneo_detalle.valor"};

                                            $condDetalleMinSelCount = $value2->{"torneo_detalle.valor"};


                                            break;

                                        case "MINSELPRICE":

                                            $condDetallesSelecCouta = $value2->{"torneo_detalle.valor"};


                                            break;

                                        case "MINSELPRICETOTAL":


                                            $condDetalleCuotaTotal = $value2->{"torneo_detalle.valor"};


                                            break;

                                        case "ITAINMENT1":

                                            if ($value2->{"torneo_detalle.valor"} == '') {
                                                $value2->{"torneo_detalle.valor"} = '0';
                                            }

                                            array_push($CondiDeportes, $value2->{"torneo_detalle.valor"});

                                            break;

                                        case "ITAINMENT3":

                                            if ($value2->{"torneo_detalle.valor"} == '') {
                                                $value2->{"torneo_detalle.valor"} = '0';
                                            }

                                            array_push($CondiLiga, $value2->{"torneo_detalle.valor"});

                                            break;

                                        case "ITAINMENT4":

                                            if ($value2->{"torneo_detalle.valor"} == '') {
                                                $value2->{"torneo_detalle.valor"} = '0';
                                            }

                                            array_push($CondiEvento, $value2->{"torneo_detalle.valor"});

                                            break;

                                        case "ITAINMENT5":

                                            if ($value2->{"torneo_detalle.valor"} == '') {
                                                $value2->{"torneo_detalle.valor"} = '0';
                                            }

                                            array_push($CondiDeporteMercado, $value2->{"torneo_detalle.valor"});

                                            break;

                                        case "ITAINMENT82":

                                            if ($value2->{"torneo_detalle.valor"} == 1) {
                                                $sePuedeSimples = 1;

                                            }

                                            if ($value2->{"torneo_detalle.valor"} == 2) {
                                                $sePuedeCombinadas = 1;

                                            }
                                            break;

                                        case "REPETIRDEPORTEBOOL":
                                            if ($value2->{"torneo_detalle.valor"}) $puedeRepetirDeportes = true;
                                            break;

                                        case "REPETIRMERCADOBOOL":
                                            if ($value2->{"torneo_detalle.valor"}) $puedeRepetirMercados = true;
                                            break;

                                        case "REPETIRLIGABOOL":
                                            if ($value2->{"torneo_detalle.valor"}) $puedeRepetirLigas = true;
                                            break;

                                        case "REPETIRDEPORTE":
                                            $idDeportes = $value2->{"torneo_detalle.valor"};
                                            break;

                                        case "REPETIRMERCADO":
                                            $idMercados = $value2->{"torneo_detalle.valor"};
                                            break;

                                        case "REPETIRLIGA":
                                            $idLigas = $value2->{"torneo_detalle.valor"};
                                            break;

                                        default:


                                            break;

                                    }

                                }


                                if (oldCount($condPaises) > 0) {
                                    if (in_array($datanum->{"usuario_mandante.pais_id"}, $condPaises)) {
                                        $cumpleCondicionPais = true;
                                    }
                                    if ($cumpleCondicionPais == false) {
                                        continue;
                                    }
                                }


                                $final = [];

                                $creditosConvert = 0;

                                $cumpleCondicion = true;

                                $cumplecondicionproducto = false;
                                $cumpleCondicionContCouta = "";
                                $condicionesproducto = 0;
                                $cumpleCondicionProd = false;

                                $condicionesProveedor = 0;
                                $cumpleCondicionProv = false;

                                $cumpleCondicionCont = 0;
                                $cumpleCondicionPais = false;

                                $minBetPrice = 0;
                                $minBetPrice2 = 0;

                                /* POSIBLE RETIRAR APARTADO DEL CODIGO QUE NO SE USA  */
                                $NUMBERCASINOSTICKERS = 0;

                                $pegatinas = $value->{"torneo_interno.pegatinas"};

                                $detalleCuotaTotal = 1;

                                $detalleCuotaTotal = $datanum->detalleCuotaTotal;
                                $bet_mode = $datanum->bet_mode;


                                $detalleSelecciones = $datanum->detallesFinal;


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
                                    }
                                    if ($cumplecondicionproducto == false) {
                                        try {

                                            $BonoInterno = new BonoInterno();
                                            $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                                VALUES ('{$datanum->{"usuario.usuario_id"}}','TORNEO','{$value->{"torneo_interno.torneo_id"}}','SPORTBOOK','{$datanum->{"it_ticket_enc.ticket_id"}}','ITAINMENT1')";
                                            $BonoInterno->execQuery($transaccion, $sqlLog);
                                        } catch (Exception $e) {
                                            exec("php -f /home/home2/backendprodfinal/api/src/imports/Slack/message.php 'ERRORTORNEOLOG " . $e->getMessage() . "' '#virtualsoft-cron-error-urg' > /dev/null & ");

                                        }
                                    }


                                    $condicionesproducto++;

                                }

                                if (oldCount($CondiLiga) > 0) {

                                    foreach ($CondiLiga as $itemarr) {


                                        foreach ($detalleSelecciones as $item) {
                                            if ($tipocomparacion == "OR") {
                                                if ($itemarr == $item->Liga) {
                                                    $cumplecondicionproducto = true;

                                                }
                                            } elseif ($tipocomparacion == "AND") {
                                                if ($itemarr != $item->Liga) {
                                                    $cumplecondicionproducto = false;

                                                }

                                                if ($condicionesproducto == 0) {
                                                    if ($itemarr == $item->Liga) {
                                                        $cumplecondicionproducto = true;
                                                    }
                                                } else {
                                                    if ($itemarr == $item->Liga && $cumplecondicionproducto) {
                                                        $cumplecondicionproducto = true;

                                                    }
                                                }

                                            }

                                        }

                                        $condicionesproducto++;
                                    }


                                    if ($cumplecondicionproducto == false) {
                                        try {

                                            $BonoInterno = new BonoInterno();
                                            $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                                VALUES ('{$datanum->{"usuario.usuario_id"}}','TORNEO','{$value->{"torneo_interno.torneo_id"}}','SPORTBOOK','{$datanum->{"it_ticket_enc.ticket_id"}}','ITAINMENT3')";
                                            $BonoInterno->execQuery($transaccion, $sqlLog);
                                        } catch (Exception $e) {
                                            exec("php -f /home/home2/backendprodfinal/api/src/imports/Slack/message.php 'ERRORTORNEOLOG " . $e->getMessage() . "' '#virtualsoft-cron-error-urg' > /dev/null & ");

                                        }
                                    }
                                }


                                if (oldCount($CondiEvento) > 0) {
                                    foreach ($CondiEvento as $itemarr) {

                                        foreach ($detalleSelecciones as $item) {
                                            if ($tipocomparacion == "OR") {
                                                if ($itemarr == $item->Evento) {
                                                    $cumplecondicionproducto = true;

                                                }
                                            } elseif ($tipocomparacion == "AND") {
                                                if ($itemarr != $item->Evento) {
                                                    $cumplecondicionproducto = false;

                                                }

                                                if ($condicionesproducto == 0) {

                                                    if ($itemarr == $item->Evento) {
                                                        $cumplecondicionproducto = true;
                                                    }
                                                } else {

                                                    if ($itemarr == $item->Evento && $cumplecondicionproducto) {
                                                        $cumplecondicionproducto = true;

                                                    }
                                                }

                                            }
                                        }

                                        $condicionesproducto++;
                                    }

                                    if ($cumplecondicionproducto == false) {
                                        try {
                                            $BonoInterno = new BonoInterno();
                                            $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                                VALUES ('{$datanum->{"usuario.usuario_id"}}','TORENO','{$value->{"torneo_interno.torneo_id"}}','SPORTBOOK','{$datanum->{"it_ticket_enc.ticket_id"}}','ITAINMENT4')";
                                            $BonoInterno->execQuery($transaccion, $sqlLog);
                                        } catch (Exception $e) {
                                            exec("php -f /home/home2/backendprodfinal/api/src/imports/Slack/message.php 'ERRORTORNEOLOG " . $e->getMessage() . "' '#virtualsoft-cron-error-urg' > /dev/null & ");

                                        }
                                    }
                                }

                                if (oldCount($CondiDeporteMercado) > 0) {
                                    foreach ($CondiDeporteMercado as $itemarr) {

                                        foreach ($detalleSelecciones as $item) {
                                            if ($tipocomparacion == "OR") {
                                                if ($itemarr == $item->DeporteMercado) {
                                                    $cumplecondicionproducto = true;


                                                }
                                            } elseif ($tipocomparacion == "AND") {
                                                if ($itemarr != $item->DeporteMercado) {
                                                    $cumplecondicionproducto = false;

                                                }

                                                if ($condicionesproducto == 0) {
                                                    if ($itemarr == $item->DeporteMercado) {
                                                        $cumplecondicionproducto = true;
                                                    }
                                                } else {
                                                    if ($itemarr == $item->DeporteMercado && $cumplecondicionproducto) {
                                                        $cumplecondicionproducto = true;

                                                    }
                                                }

                                            }
                                        }

                                        $condicionesproducto++;
                                    }

                                    if ($cumplecondicionproducto == false) {
                                        try {
                                            $BonoInterno = new BonoInterno();
                                            $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                                VALUES ('{$datanum->{"usuario.usuario_id"}}','TORNEO','{$value->{"torneo_interno.torneo_id"}}','SPORTBOOK','{$datanum->{"it_ticket_enc.ticket_id"}}','ITAINMENT5')";
                                            $BonoInterno->execQuery($transaccion, $sqlLog);
                                        } catch (Exception $e) {
                                            exec("php -f /home/home2/backendprodfinal/api/src/imports/Slack/message.php 'ERRORTORNEOLOG " . $e->getMessage() . "' '#virtualsoft-cron-error-urg' > /dev/null & ");

                                        }
                                    }
                                }


                                if ($condDetalleCuotaTotal > 0) {
                                    foreach ($detalleSelecciones as $item) {
                                        if ($condDetalleCuotaTotal > $detalleCuotaTotal) {
                                            $cumpleCondicion = false;
                                            try {
                                                $BonoInterno = new BonoInterno();
                                                $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                                    VALUES ('{$datanum->{"usuario.usuario_id"}}','TORNEO','{$value->{"torneo_interno.torneo_id"}}','SPORTBOOK','{$datanum->{"it_ticket_enc.ticket_id"}}','MINSELPRICETOTAL')";
                                                $BonoInterno->execQuery($transaccion, $sqlLog);
                                            } catch (Exception $e) {
                                                exec("php -f /home/home2/backendprodfinal/api/src/imports/Slack/message.php 'ERRORTORNEOLOG " . $e->getMessage() . "' '#virtualsoft-cron-error-urg' > /dev/null & ");

                                            }
                                        }
                                    }
                                }

                                if (oldCount($condPaises) > 0) {

                                    if (in_array($datanum->{"usuario_mandante.pais_id"}, $condPaises)) {
                                        $cumpleCondicionPais = true;
                                    }
                                    if ($cumpleCondicionPais == false) {
                                        try {


                                            $BonoInterno = new BonoInterno();
                                            $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                                VALUES ('{$datanum->{"usuario.usuario_id"}}','TORNEO','{$value->{"torneo_interno.torneo_id"}}','SPORTBOOK','{$datanum->{"it_ticket_enc.ticket_id"}}','CONDPAISUSER')";
                                            $BonoInterno->execQuery($transaccion, $sqlLog);
                                        } catch (Exception $e) {
                                            exec("php -f /home/home2/backendprodfinal/api/src/imports/Slack/message.php 'ERRORTORNEOLOG " . $e->getMessage() . "' '#virtualsoft-cron-error-urg' > /dev/null & ");

                                        }
                                    }
                                    $cumpleCondicionCont++;
                                }


                                if ($condbet_mode == 2) {

                                    if ($datanum->{"it_ticket_enc.bet_mode"} == "PreLive") {

                                        if ($condicionesproducto == 0) {
                                            $cumplecondicionproducto = true;
                                        }

                                    } else {
                                        $cumplecondicionproducto = false;
                                        try {
                                            $BonoInterno = new BonoInterno();
                                            $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                                VALUES ('{$datanum->{"usuario.usuario_id"}}','TORNEO','{$value->{"torneo_interno.torneo_id"}}','SPORTBOOK','{$datanum->{"it_ticket_enc.ticket_id"}}','LIVEORPREMATCH')";
                                            $BonoInterno->execQuery($transaccion, $sqlLog);
                                        } catch (Exception $e) {
                                            exec("php -f /home/home2/backendprodfinal/api/src/imports/Slack/message.php 'ERRORTORNEOLOG " . $e->getMessage() . "' '#virtualsoft-cron-error-urg' > /dev/null & ");

                                        }

                                    }

                                }

                                if ($condbet_mode == 1) {
                                    if ($datanum->{"it_ticket_enc.bet_mode"} == "Live") {

                                        if ($condicionesproducto == 0) {
                                            $cumplecondicionproducto = true;
                                        }

                                    } else {
                                        $cumplecondicionproducto = false;
                                        try {
                                            $BonoInterno = new BonoInterno();
                                            $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                                VALUES ('{$datanum->{"usuario.usuario_id"}}','TORNEO','{$value->{"torneo_interno.torneo_id"}}','SPORTBOOK','{$datanum->{"it_ticket_enc.ticket_id"}}','LIVEORPREMATCH')";
                                            $BonoInterno->execQuery($transaccion, $sqlLog);
                                        } catch (Exception $e) {
                                            exec("php -f /home/home2/backendprodfinal/api/src/imports/Slack/message.php 'ERRORTORNEOLOG " . $e->getMessage() . "' '#virtualsoft-cron-error-urg' > /dev/null & ");

                                        }
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
                                    try {
                                        $BonoInterno = new BonoInterno();
                                        $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                            VALUES ('{$datanum->{"usuario.usuario_id"}}','TORNEO','{$value->{"torneo_interno.torneo_id"}}','SPORTBOOK','{$datanum->{"it_ticket_enc.ticket_id"}}','MINBETPRICE2SPORTSBOOK')";
                                        // $BonoInterno->execQuery($transaccion, $sqlLog);
                                    } catch (Exception $e) {
                                        exec("php -f /home/home2/backendprodfinal/api/src/imports/Slack/message.php 'ERRORTORNEOLOG " . $e->getMessage() . "' '#virtualsoft-cron-error-urg' > /dev/null & ");

                                    }

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
                                        try {
                                            $BonoInterno = new BonoInterno();
                                            $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                                VALUES ('{$datanum->{"usuario.usuario_id"}}','TORNEO','{$value->{"torneo_interno.torneo_id"}}','SPORTBOOK','{$datanum->{"it_ticket_enc.ticket_id"}}','ITAINMENT82')";
                                            //$BonoInterno->execQuery($transaccion, $sqlLog);
                                        } catch (Exception $e) {
                                            exec("php -f /home/home2/backendprodfinal/api/src/imports/Slack/message.php 'ERRORTORNEOLOG " . $e->getMessage() . "' '#virtualsoft-cron-error-urg' > /dev/null & ");

                                        }
                                    }
                                } else {
                                    if (oldCount($detalleSelecciones) > 1 && oldCount($detalleSelecciones) < $minselcount) {
                                        $cumpleCondicion = false;

                                    }
                                }

                                $valorTicket = floatval($datanum->{"it_ticket_enc.vlr_apuesta"});


                                $puederepetirBono = false;

                                if ($cumpleCondicion && ($cumplecondicionproducto || $condicionesproducto == 0)) {
                                } else {
                                    $cumpleCondicion = false;
                                }


                                if ($condDetallesSelecCouta > 0) {
                                    foreach ($detalleSelecciones as $item) {
                                        if (floatval($condDetallesSelecCouta) > floatval($item->Cuota)) {
                                            $cumpleCondicion = false;
                                            try {
                                                $BonoInterno = new BonoInterno();
                                                $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                                    VALUES ('{$datanum->{"usuario.usuario_id"}}','TORNEO','{$value->{"torneo_interno.torneo_id"}}','SPORTBOOK','{$datanum->{"it_ticket_enc.ticket_id"}}','MINSELPRICE')";
                                                //$BonoInterno->execQuery($transaccion, $sqlLog);
                                            } catch (Exception $e) {
                                                exec("php -f /home/home2/backendprodfinal/api/src/imports/Slack/message.php 'ERRORTORNEOLOG " . $e->getMessage() . "' '#virtualsoft-cron-error-urg' > /dev/null & ");

                                            }
                                        }
                                    }

                                }

                                if ($cumpleCondicion) {

                                    if ($puederepetirBono) {


                                    } else {


                                        $sqlRepiteTorneo = "select * from usuario_torneo a where  a.usutorneo_id !=0 and  a.torneo_id='" . $value->{"torneo_interno.torneo_id"} . "' AND a.usuario_id = '" . $datanum->{"usuario.usuario_id"} . "'";
                                        $repiteTorneo = $this->execQuery('', $sqlRepiteTorneo);

                                        if ((!$puederepetirBono && oldCount($repiteTorneo) == 0)) {

                                        } else {
                                            $cumpleCondicion = false;
                                            try {
                                                $BonoInterno = new BonoInterno();
                                                $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                                    VALUES ('{$datanum->{"usuario.usuario_id"}}','TORNEO','{$value->{"torneo_interno.torneo_id"}}','SPORTBOOK','{$datanum->{"it_ticket_enc.ticket_id"}}','REPETIRTORNEO')";
                                                //$BonoInterno->execQuery($transaccion, $sqlLog);
                                            } catch (Exception $e) {
                                                exec("php -f /home/home2/backendprodfinal/api/src/imports/Slack/message.php 'ERRORTORNEOLOG " . $e->getMessage() . "' '#virtualsoft-cron-error-urg' > /dev/null & ");

                                            }
                                        }

                                    }
                                }


                                if (oldCount($condRank) > 0) {

                                    foreach ($condRank[$datanum->{"usuario.moneda"}] as $item) {

                                        if ($valorTicket >= $item["valor"]) {

                                            if ($valorTicket <= $item["valor2"]) {

                                                $creditosConvert = $item["valor3"];
                                            }
                                        }
                                        $cumpleCondicionRank = true;
                                    }

                                }
                                if ($creditosConvert == 0) {
                                    try {
                                        $BonoInterno = new BonoInterno();
                                        $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                            VALUES ('{$datanum->{"usuario.usuario_id"}}','TORNEO','{$value->{"torneo_interno.torneo_id"}}','SPORTBOOK','{$datanum->{"it_ticket_enc.ticket_id"}}','RANK')";
                                        //$BonoInterno->execQuery($transaccion, $sqlLog);
                                    } catch (Exception $e) {
                                        exec("php -f /home/home2/backendprodfinal/api/src/imports/Slack/message.php 'ERRORTORNEOLOG " . $e->getMessage() . "' '#virtualsoft-cron-error-urg' > /dev/null & ");

                                    }
                                    continue;
                                }


                                if ($needSubscribe) {


                                    $rules = [];
                                    array_push($rules, ['field' => 'usuario_torneo.torneo_id', 'data' => $value->{"torneo_interno.torneo_id"}, 'op' => 'eq']);
                                    array_push($rules, ['field' => 'usuario_torneo.usuario_id', 'data' => $datanum->{"usuario_mandante.usumandante_id"}, 'op' => 'eq']);

                                    $filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);
                                    $UsuarioTorneo2 = new UsuarioTorneo();
                                    $allCoupons = (string)$UsuarioTorneo2->getUsuarioTorneosCustom('COUNT(distinct(usuario_torneo.usuario_id)) countUsers,COUNT((usuario_torneo.usutorneo_id)) countStickers', 'usuario_torneo.usutorneo_id', 'asc', 0, 1000000, $filter, true);

                                    $allCoupons = json_decode($allCoupons, true);


                                    if ($allCoupons['count'][0]['.count'] > 0) {
                                    } else {
                                        $cumpleCondicion = false;
                                        try {
                                            $BonoInterno = new BonoInterno();
                                            $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                                VALUES ('{$datanum->{"usuario.usuario_id"}}','TORNEO','{$value->{"torneo_interno.torneo_id"}}','SPORTBOOK','{$datanum->{"it_ticket_enc.ticket_id"}}','NEEDSUSCRIBE')";
                                            //$BonoInterno->execQuery($transaccion, $sqlLog);
                                        } catch (Exception $e) {
                                            exec("php -f /home/home2/backendprodfinal/api/src/imports/Slack/message.php 'ERRORTORNEOLOG " . $e->getMessage() . "' '#virtualsoft-cron-error-urg' > /dev/null & ");

                                        }
                                    }
                                }

                                if (
                                    !$this->validarEventosRepetidos('deportes', $idDeportes, $puedeRepetirDeportes, $datanum->detallesFinal) ||
                                    !$this->validarEventosRepetidos('ligas', $idLigas, $puedeRepetirLigas, $datanum->detallesFinal) ||
                                    !$this->validarEventosRepetidos('mercados', $idMercados, $puedeRepetirMercados, $datanum->detallesFinal)
                                ) {
                                    $cumpleCondicion = false;
                                }



                                $UsuarioConfiguracion = new UsuarioConfiguracion();
                                $UsuarioConfiguracion = $UsuarioConfiguracion->getUserBondABuser($datanum->{"usuario.usuario_id"});
                                if (!empty($UsuarioConfiguracion->usuconfigId)){
                                    $cumpleCondicion = false;
                                    $BonoInterno = new BonoInterno();
                                    $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                        VALUES ('{$datanum->{"usuario.usuario_id"}}','TORNEO','{$value->{"torneo_interno.torneo_id"}}','SPORTBOOK','{$datanum->{"it_ticket_enc.ticket_id"}}','Contingencia activa: Abusador de bonos')";
                                    //$BonoInterno->execQuery($transaccion, $sqlLog);
                                }

                                if ($cumpleCondicion) {

                                    $ItTicketEncInfo1MySqlDAO = new ItTicketEncInfo1MySqlDAO();


                                    $estado = "P";
                                    $sqlRepiteTorneo = "select * from usuario_torneo a where  a.usutorneo_id !=0 and  a.torneo_id='" . $value->{"torneo_interno.torneo_id"} . "' AND a.usuario_id = '" . $datanum->{"usuario_mandante.usumandante_id"} . "' ";
                                    $repiteTorneo = $this->execQuery('', $sqlRepiteTorneo);


                                    if (oldCount($repiteTorneo) == 0) {
                                        $UsuarioTorneo = new UsuarioTorneo();
                                        $UsuarioTorneo->usuarioId = $datanum->{"usuario_mandante.usumandante_id"};
                                        $UsuarioTorneo->torneoId = $value->{"torneo_interno.torneo_id"};
                                        $UsuarioTorneo->valor = 0;
                                        $UsuarioTorneo->posicion = 0;
                                        $UsuarioTorneo->usucreaId = 0;
                                        $UsuarioTorneo->usumodifId = 0;


                                        $UsuarioTorneo->estado = "A";
                                        $UsuarioTorneo->valor = $UsuarioTorneo->valor + $creditosConvert;
                                        $UsuarioTorneo->valorBase = ($UsuarioTorneo->valorBase + $valorTicket);

                                        $UsuarioTorneo->errorId = 0;
                                        $UsuarioTorneo->idExterno = 0;
                                        $UsuarioTorneo->mandante = 0;
                                        $UsuarioTorneo->version = 0;
                                        $UsuarioTorneo->apostado = $datanum->{"it_ticket_enc.vlr_apuesta"};
                                        $UsuarioTorneo->codigo = 0;
                                        $UsuarioTorneo->externoId = 0;
                                        //$UsuarioTorneo->valorBase = ($UsuarioTorneo->valorBase + $TransaccionApi->valor);


                                        $UsuarioTorneoMySqlDAO = new UsuarioTorneoMySqlDAO($ItTicketEncInfo1MySqlDAO->getTransaction());
                                        $usutorneoId = $UsuarioTorneoMySqlDAO->insert($UsuarioTorneo);

                                    } else {
                                        $usutorneoId = $repiteTorneo[0]->{"a.usutorneo_id"};
                                        $BonoInterno = new BonoInterno();

                                        $transaccion = $ItTicketEncInfo1MySqlDAO->getTransaction();


                                        $sql = "UPDATE usuario_torneo SET valor = valor + " . ($creditosConvert) . ", valor_base = valor_base + " . ($valorTicket) . " WHERE usutorneo_id =" . $usutorneoId;
                                        $BonoInterno->execQuery($transaccion, $sql);

                                    }
                                    $idUsuTorneo = $usutorneoId;

                                    /*$UsuarioMensaje = new UsuarioMensaje();
                                    $UsuarioMensaje->usufromId = 0;
                                    $UsuarioMensaje->usutoId = $UsuarioTorneo->getUsuarioId();
                                    $UsuarioMensaje->isRead = 0;
                                    $UsuarioMensaje->body = '¡ Bien :thumbsup: ! Estas participando en el torneo ' . $value->{"torneo_interno.nombre"} . ' :clap:';
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
                                    $ItTicketEncInfo1->tipo = "TORNEO";

                                    $ItTicketEncInfo1->valor = $idUsuTorneo;
                                    $ItTicketEncInfo1->fechaCrea = date('Y-m-d H:i:s');
                                    $ItTicketEncInfo1->fechaModif = date('Y-m-d H:i:s');
                                    $ItTicketEncInfo1->valor2 = $creditosConvert;

                                    $ItTicketEncInfo1MySqlDAO->insert($ItTicketEncInfo1);
                                    $ItTicketEncInfo1MySqlDAO->getTransaction()->commit();

                                    array_push($ArrayTransaction, $datanum->{"it_ticket_enc.ticket_id"});
                                    print_r(' FINALIZO RANK NUEVO SIN VALOR POR PUNTO');
                                }
                            }
                        }
                    }

                    //SE HACE CONSULTA PARA APUESTAS GANADAS, PARA SISTEMA DE PUNTOS POR GANANCIA NETA O DE RANKING TIPO 'CUOTAS'
                    if ($incomeNetPoint > 0 || $pointValueQuota > 0) {
                        print_r('ENTRO EN RANK NUEVO CON VALOR POR PUNTO');
                        $sqlSport = "select usuario.mandante,
                                usuario_mandante.pais_id,
                                usuario_mandante.usumandante_id,
                                usuario.usuario_id,
                                usuario.nombre,
                                usuario.login,
                                usuario.moneda,
                                it_ticket_enc.ticket_id,
                                it_ticket_enc.bet_mode,
                                it_ticket_enc.vlr_apuesta,
                                it_ticket_enc.vlr_premio,
                                it_ticket_enc.fecha_crea_time,
                                it_ticket_enc.fecha_cierre_time,
                                it_ticket_det.*
                                from it_ticket_det
                                INNER JOIN(select it_ticket_enc.ticket_id
                                from it_ticket_det   
                                INNER JOIN
                                (select it_ticket_enc.ticket_id
                                from it_ticket_det
                                INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_ticket_det.ticket_id)
                                LEFT OUTER JOIN it_ticket_enc_info1  ON (it_ticket_enc_info1.ticket_id = it_ticket_det.ticket_id and it_ticket_enc_info1.tipo='TORNEO')
                                INNER JOIN usuario ON (it_ticket_enc.usuario_id = usuario.usuario_id)
                                INNER JOIN usuario_mandante ON (usuario_mandante.usuario_mandante = usuario.usuario_id and usuario_mandante.mandante = usuario.mandante)
                                INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = usuario.usuario_id)
                                where 1 = 1 and it_ticket_enc_info1.it_ticket2_id is null
                                AND ((it_ticket_enc.bet_status)) = 'S'
                                AND ((it_ticket_enc.freebet)) = '0'
                                AND ((it_ticket_enc.eliminado)) = 'N'
                                AND ((it_ticket_enc.mandante)) = '" . $value->{"torneo_interno.mandante"} . "' " . $sqlArt . "
                                AND (((it_ticket_enc.fecha_cierre_time))) >= '" . $fechaL1 . "'
                                AND (((it_ticket_enc.fecha_cierre_time))) <= '" . $fechaL2 . "'
                                AND (((it_ticket_enc.vlr_premio))) > '0'
                                AND ((usuario_perfil.perfil_id)) = 'USUONLINE') a ON (a.ticket_id = it_ticket_det.ticket_id)
                                INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_ticket_det.ticket_id)
                                LEFT OUTER JOIN it_ticket_enc_info1
                                ON (it_ticket_enc_info1.ticket_id = it_ticket_det.ticket_id and
                                it_ticket_enc_info1.tipo = 'TORNEO')
                                INNER JOIN usuario ON (it_ticket_enc.usuario_id = usuario.usuario_id)
                                INNER JOIN usuario_mandante ON (usuario_mandante.usuario_mandante = usuario.usuario_id and
                                usuario_mandante.mandante = usuario.mandante)
                                INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = usuario.usuario_id)
                                group by it_ticket_det.ticket_id
                                HAVING min(logro)>" . $condDetallesSelecCouta . ") b ON (b.ticket_id = it_ticket_det.ticket_id)
                                INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_ticket_det.ticket_id)
                                INNER JOIN usuario ON (it_ticket_enc.usuario_id = usuario.usuario_id)
                                INNER JOIN usuario_mandante ON (usuario_mandante.usuario_mandante = usuario.usuario_id and
                                usuario_mandante.mandante = usuario.mandante)
                                INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = usuario.usuario_id); ";
                        $detalleTicket = $this->execQuery('', $sqlSport);

                        $dataUsuarioProv = array();
                        $dataUsuarioProv2 = array();
                        foreach ($detalleTicket as $detalle) {

                            $detalle->bet_mode = $detalle->{'it_ticket_enc.bet_mode'};
                            $dataUsuarioProv2[$detalle->{'it_ticket_enc.ticket_id'}] = $detalle;
                        }

                        $array = array();
                        $detalleCuotaTotal = 1;
                        $bet_mode = '';
                        foreach ($detalleTicket as $detalle) {
                            $detalle->sportid = $detalle->{'it_ticket_det.sportid'};
                            $detalle->ligaid = $detalle->{'it_ticket_det.ligaid'};
                            $detalle->agrupador_id = $detalle->{'it_ticket_det.agrupador_id'};
                            $detalle->logro = $detalle->{'it_ticket_det.logro'};
                            $detalle->vlr_apuesta = $detalle->{'it_ticket_enc.vlr_apuesta'};
                            $detalle->bet_mode = $detalle->{'it_ticket_enc.bet_mode'};

                            $detalles = array(
                                "DeporteMercado" => $detalle->sportid . "M" . $detalle->agrupador_id,
                                "Deporte" => $detalle->sportid,
                                "Liga" => $detalle->ligaid,
                                // "Evento"=>$detalle->apuesta_id,
                                "Cuota" => $detalle->logro

                            );
                            $detalleValorApuesta = $detalle->vlr_apuesta;

                            if (($dataUsuarioProv2[$detalle->{'it_ticket_enc.ticket_id'}])->detallesFinal == null) {
                                ($dataUsuarioProv2[$detalle->{'it_ticket_enc.ticket_id'}])->detallesFinal = array();
                                ($dataUsuarioProv2[$detalle->{'it_ticket_enc.ticket_id'}])->detalleCuotaTotal = 1;
                            }

                            array_push(($dataUsuarioProv2[$detalle->{'it_ticket_enc.ticket_id'}])->detallesFinal, $detalles);

                            $bet_mode = $detalle->bet_mode;
                            ($dataUsuarioProv2[$detalle->{'it_ticket_enc.ticket_id'}])->detalleCuotaTotal = ($dataUsuarioProv2[$detalle->{'it_ticket_enc.ticket_id'}])->detalleCuotaTotal * $detalle->logro;

                        }
                        foreach ($dataUsuarioProv2 as $detalle) {
                            ($dataUsuarioProv2[$detalle->{'it_ticket_enc.ticket_id'}])->detallesFinal = json_decode(json_encode((($dataUsuarioProv2[$detalle->{'it_ticket_enc.ticket_id'}])->detallesFinal)));
                        }
                        $dataUsuario = $dataUsuarioProv2;

                        foreach ($dataUsuario as $key4 => $datanum) {


                            if (in_array($datanum->{"it_ticket_enc.ticket_id"}, $ArrayTransaction)) {
                                continue;
                            }

                            if ($Sportsbook) {

                                if (!($datanum->{"it_ticket_enc.fecha_cierre_time"} >= $value->{"torneo_interno.fecha_inicio"}
                                    && $datanum->{"it_ticket_enc.fecha_cierre_time"} <= $value->{"torneo_interno.fecha_fin"})
                                ) {
                                    continue;
                                }

                                if (($value->{"torneo_interno.mandante"} != $datanum->{"usuario.mandante"})) {
                                    continue;
                                }


                                if ($value->condicional == 'NA' || $value->condicional == '') {
                                    $tipocomparacion = "OR";

                                } else {
                                    $tipocomparacion = $value->condicional;

                                }


                                //SPORTSBOOK
                                $condMinBetPrice = array();
                                $condMinBetPrice2 = array();
                                $condDetallesSelecCouta = 0;
                                $CondiDeportes = array();
                                $CondiLiga = array();
                                $CondiEvento = array();
                                $CondiDeporteMercado = array();
                                $condDetalleCuotaTotal = 0;
                                $condDetalleMinSelCount = 0;
                                $condRank = array();
                                $needSubscribe = false;
                                $puedeRepetirDeportes = false;
                                $idDeportes = '';
                                $puedeRepetirLigas = false;
                                $idLigas = '';
                                $puedeRepetirMercados = false;
                                $idMercados = '';
                                $sePuedeCombinadas = 0;
                                $sePuedeSimples = 0;

                                $condPaises = array();

                                $bet_mode = "";
                                $condbet_mode = "";


                                $torneodetalles = $value->torneodetalles;
                                foreach ($torneodetalles->data as $key2 => $value2) {


                                    switch ($value2->{"torneo_detalle.tipo"}) {

                                        case "MINBETPRICESPORTSBOOK":

                                            $condMinBetPrice[$value2->{"torneo_detalle.moneda"}] = floatval($value2->{"torneo_detalle.valor"});

                                            break;

                                        case "VISIBILIDAD":

                                            if ($value2->{"torneo_detalle.valor"} == 1) {
                                                $needSubscribe = true;
                                            }

                                            break;


                                        case "USERSUBSCRIBE":

                                            if ($value2->{"torneo_detalle.valor"} == 0) {

                                            } else {
                                                $needSubscribe = true;
                                            }

                                            break;

                                        case "CONDPAISUSER":

                                            if ($value2->{"torneo_detalle.valor"} == '') {
                                                $value2->{"torneo_detalle.valor"} = '0';
                                            }


                                            array_push($condPaises, $value2->{"torneo_detalle.valor"});

                                            break;


                                        case "LIVEORPREMATCH":

                                            $condbet_mode = $value2->{"torneo_detalle.valor"};

                                            break;

                                        case "MINSELCOUNT":

                                            // Realmente se debe Validar?
                                            $minselcount = $value2->{"torneo_detalle.valor"};

                                            $condDetalleMinSelCount = $value2->{"torneo_detalle.valor"};


                                            break;

                                        case "MINSELPRICE":

                                            $condDetallesSelecCouta = $value2->{"torneo_detalle.valor"};


                                            break;

                                        case "MINSELPRICETOTAL":


                                            $condDetalleCuotaTotal = $value2->{"torneo_detalle.valor"};


                                            break;

                                        case "ITAINMENT1":

                                            if ($value2->{"torneo_detalle.valor"} == '') {
                                                $value2->{"torneo_detalle.valor"} = '0';
                                            }

                                            array_push($CondiDeportes, $value2->{"torneo_detalle.valor"});

                                            break;

                                        case "ITAINMENT3":

                                            if ($value2->{"torneo_detalle.valor"} == '') {
                                                $value2->{"torneo_detalle.valor"} = '0';
                                            }

                                            array_push($CondiLiga, $value2->{"torneo_detalle.valor"});

                                            break;

                                        case "ITAINMENT4":

                                            if ($value2->{"torneo_detalle.valor"} == '') {
                                                $value2->{"torneo_detalle.valor"} = '0';
                                            }

                                            array_push($CondiEvento, $value2->{"torneo_detalle.valor"});

                                            break;

                                        case "ITAINMENT5":

                                            if ($value2->{"torneo_detalle.valor"} == '') {
                                                $value2->{"torneo_detalle.valor"} = '0';
                                            }

                                            array_push($CondiDeporteMercado, $value2->{"torneo_detalle.valor"});

                                            break;

                                        case "ITAINMENT82":

                                            if ($value2->{"torneo_detalle.valor"} == 1) {
                                                $sePuedeSimples = 1;

                                            }

                                            if ($value2->{"torneo_detalle.valor"} == 2) {
                                                $sePuedeCombinadas = 1;

                                            }
                                            break;

                                        case "REPETIRDEPORTEBOOL":
                                            if ($value2->{"torneo_detalle.valor"}) $puedeRepetirDeportes = true;
                                            break;

                                        case "REPETIRMERCADOBOOL":
                                            if ($value2->{"torneo_detalle.valor"}) $puedeRepetirMercados = true;
                                            break;

                                        case "REPETIRLIGABOOL":
                                            if ($value2->{"torneo_detalle.valor"}) $puedeRepetirLigas = true;
                                            break;

                                        case "REPETIRDEPORTE":
                                            $idDeportes = $value2->{"torneo_detalle.valor"};
                                            break;

                                        case "REPETIRMERCADO":
                                            $idMercados = $value2->{"torneo_detalle.valor"};
                                            break;

                                        case "REPETIRLIGA":
                                            $idLigas = $value2->{"torneo_detalle.valor"};
                                            break;


                                        default:


                                            break;

                                    }

                                }


                                if (oldCount($condPaises) > 0) {
                                    if (in_array($datanum->{"usuario_mandante.pais_id"}, $condPaises)) {
                                        $cumpleCondicionPais = true;
                                    }
                                    if ($cumpleCondicionPais == false) {
                                        continue;
                                    }
                                }


                                $final = [];

                                $creditosConvert = 0;

                                $cumpleCondicion = true;

                                $cumplecondicionproducto = false;
                                $cumpleCondicionContCouta = "";
                                $condicionesproducto = 0;
                                $cumpleCondicionProd = false;

                                $condicionesProveedor = 0;
                                $cumpleCondicionProv = false;

                                $cumpleCondicionCont = 0;
                                $cumpleCondicionPais = false;

                                $minBetPrice = 0;
                                $minBetPrice2 = 0;

                                /* RETIRAR ESTA PARTE DE STICKER QUE NO INFLUYE EN NADA */
                                $NUMBERCASINOSTICKERS = 0;

                                $pegatinas = $value->{"torneo_interno.pegatinas"};

                                $detalleCuotaTotal = 1;

                                $detalleCuotaTotal = $datanum->detalleCuotaTotal;
                                $bet_mode = $datanum->bet_mode;


                                $detalleSelecciones = $datanum->detallesFinal;


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
                                    }
                                    if ($cumplecondicionproducto == false) {
                                        try {

                                            $BonoInterno = new BonoInterno();
                                            $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                                VALUES ('{$datanum->{"usuario.usuario_id"}}','TORNEO','{$value->{"torneo_interno.torneo_id"}}','SPORTBOOK','{$datanum->{"it_ticket_enc.ticket_id"}}','ITAINMENT1')";
                                            //$BonoInterno->execQuery($transaccion, $sqlLog);
                                        } catch (Exception $e) {
                                            exec("php -f /home/home2/backendprodfinal/api/src/imports/Slack/message.php 'ERRORTORNEOLOG " . $e->getMessage() . "' '#virtualsoft-cron-error-urg' > /dev/null & ");

                                        }
                                    }


                                    $condicionesproducto++;

                                }

                                if (oldCount($CondiLiga) > 0) {

                                    foreach ($CondiLiga as $itemarr) {


                                        foreach ($detalleSelecciones as $item) {
                                            if ($tipocomparacion == "OR") {
                                                if ($itemarr == $item->Liga) {
                                                    $cumplecondicionproducto = true;

                                                }
                                            } elseif ($tipocomparacion == "AND") {
                                                if ($itemarr != $item->Liga) {
                                                    $cumplecondicionproducto = false;

                                                }

                                                if ($condicionesproducto == 0) {
                                                    if ($itemarr == $item->Liga) {
                                                        $cumplecondicionproducto = true;
                                                    }
                                                } else {
                                                    if ($itemarr == $item->Liga && $cumplecondicionproducto) {
                                                        $cumplecondicionproducto = true;

                                                    }
                                                }

                                            }

                                        }

                                        $condicionesproducto++;
                                    }


                                    if ($cumplecondicionproducto == false) {
                                        try {

                                            $BonoInterno = new BonoInterno();
                                            $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                                VALUES ('{$datanum->{"usuario.usuario_id"}}','TORNEO','{$value->{"torneo_interno.torneo_id"}}','SPORTBOOK','{$datanum->{"it_ticket_enc.ticket_id"}}','ITAINMENT3')";
                                            //$BonoInterno->execQuery($transaccion, $sqlLog);
                                        } catch (Exception $e) {
                                            exec("php -f /home/home2/backendprodfinal/api/src/imports/Slack/message.php 'ERRORTORNEOLOG " . $e->getMessage() . "' '#virtualsoft-cron-error-urg' > /dev/null & ");

                                        }
                                    }
                                }


                                if (oldCount($CondiEvento) > 0) {
                                    foreach ($CondiEvento as $itemarr) {

                                        foreach ($detalleSelecciones as $item) {
                                            if ($tipocomparacion == "OR") {
                                                if ($itemarr == $item->Evento) {
                                                    $cumplecondicionproducto = true;

                                                }
                                            } elseif ($tipocomparacion == "AND") {
                                                if ($itemarr != $item->Evento) {
                                                    $cumplecondicionproducto = false;

                                                }

                                                if ($condicionesproducto == 0) {

                                                    if ($itemarr == $item->Evento) {
                                                        $cumplecondicionproducto = true;
                                                    }
                                                } else {

                                                    if ($itemarr == $item->Evento && $cumplecondicionproducto) {
                                                        $cumplecondicionproducto = true;

                                                    }
                                                }

                                            }
                                        }

                                        $condicionesproducto++;
                                    }

                                    if ($cumplecondicionproducto == false) {
                                        try {
                                            $BonoInterno = new BonoInterno();
                                            $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                                VALUES ('{$datanum->{"usuario.usuario_id"}}','TORENO','{$value->{"torneo_interno.torneo_id"}}','SPORTBOOK','{$datanum->{"it_ticket_enc.ticket_id"}}','ITAINMENT4')";
                                            //$BonoInterno->execQuery($transaccion, $sqlLog);
                                        } catch (Exception $e) {
                                            exec("php -f /home/home2/backendprodfinal/api/src/imports/Slack/message.php 'ERRORTORNEOLOG " . $e->getMessage() . "' '#virtualsoft-cron-error-urg' > /dev/null & ");

                                        }
                                    }
                                }

                                if (oldCount($CondiDeporteMercado) > 0) {
                                    foreach ($CondiDeporteMercado as $itemarr) {

                                        foreach ($detalleSelecciones as $item) {
                                            if ($tipocomparacion == "OR") {
                                                if ($itemarr == $item->DeporteMercado) {
                                                    $cumplecondicionproducto = true;


                                                }
                                            } elseif ($tipocomparacion == "AND") {
                                                if ($itemarr != $item->DeporteMercado) {
                                                    $cumplecondicionproducto = false;

                                                }

                                                if ($condicionesproducto == 0) {
                                                    if ($itemarr == $item->DeporteMercado) {
                                                        $cumplecondicionproducto = true;
                                                    }
                                                } else {
                                                    if ($itemarr == $item->DeporteMercado && $cumplecondicionproducto) {
                                                        $cumplecondicionproducto = true;

                                                    }
                                                }

                                            }
                                        }

                                        $condicionesproducto++;
                                    }

                                    if ($cumplecondicionproducto == false) {
                                        try {
                                            $BonoInterno = new BonoInterno();
                                            $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                                VALUES ('{$datanum->{"usuario.usuario_id"}}','TORNEO','{$value->{"torneo_interno.torneo_id"}}','SPORTBOOK','{$datanum->{"it_ticket_enc.ticket_id"}}','ITAINMENT5')";
                                            //$BonoInterno->execQuery($transaccion, $sqlLog);
                                        } catch (Exception $e) {
                                            exec("php -f /home/home2/backendprodfinal/api/src/imports/Slack/message.php 'ERRORTORNEOLOG " . $e->getMessage() . "' '#virtualsoft-cron-error-urg' > /dev/null & ");

                                        }
                                    }
                                }


                                if ($condDetalleCuotaTotal > 0) {
                                    foreach ($detalleSelecciones as $item) {
                                        if ($condDetalleCuotaTotal > $detalleCuotaTotal) {
                                            $cumpleCondicion = false;
                                            try {
                                                $BonoInterno = new BonoInterno();
                                                $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                                    VALUES ('{$datanum->{"usuario.usuario_id"}}','TORNEO','{$value->{"torneo_interno.torneo_id"}}','SPORTBOOK','{$datanum->{"it_ticket_enc.ticket_id"}}','MINSELPRICETOTAL')";
                                                //$BonoInterno->execQuery($transaccion, $sqlLog);
                                            } catch (Exception $e) {
                                                exec("php -f /home/home2/backendprodfinal/api/src/imports/Slack/message.php 'ERRORTORNEOLOG " . $e->getMessage() . "' '#virtualsoft-cron-error-urg' > /dev/null & ");

                                            }
                                        }
                                    }
                                }

                                if (oldCount($condPaises) > 0) {

                                    if (in_array($datanum->{"usuario_mandante.pais_id"}, $condPaises)) {
                                        $cumpleCondicionPais = true;
                                    }
                                    if ($cumpleCondicionPais == false) {
                                        try {


                                            $BonoInterno = new BonoInterno();
                                            $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                                VALUES ('{$datanum->{"usuario.usuario_id"}}','TORNEO','{$value->{"torneo_interno.torneo_id"}}','SPORTBOOK','{$datanum->{"it_ticket_enc.ticket_id"}}','CONDPAISUSER')";
                                            //$BonoInterno->execQuery($transaccion, $sqlLog);
                                        } catch (Exception $e) {
                                            exec("php -f /home/home2/backendprodfinal/api/src/imports/Slack/message.php 'ERRORTORNEOLOG " . $e->getMessage() . "' '#virtualsoft-cron-error-urg' > /dev/null & ");

                                        }
                                    }
                                    $cumpleCondicionCont++;
                                }


                                if ($condbet_mode == 2) {

                                    if ($datanum->{"it_ticket_enc.bet_mode"} == "PreLive") {

                                        if ($condicionesproducto == 0) {
                                            $cumplecondicionproducto = true;
                                        }

                                    } else {
                                        $cumplecondicionproducto = false;
                                        try {
                                            $BonoInterno = new BonoInterno();
                                            $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                                VALUES ('{$datanum->{"usuario.usuario_id"}}','TORNEO','{$value->{"torneo_interno.torneo_id"}}','SPORTBOOK','{$datanum->{"it_ticket_enc.ticket_id"}}','LIVEORPREMATCH')";
                                            //$BonoInterno->execQuery($transaccion, $sqlLog);
                                        } catch (Exception $e) {
                                            exec("php -f /home/home2/backendprodfinal/api/src/imports/Slack/message.php 'ERRORTORNEOLOG " . $e->getMessage() . "' '#virtualsoft-cron-error-urg' > /dev/null & ");

                                        }

                                    }

                                }

                                if ($condbet_mode == 1) {
                                    if ($datanum->{"it_ticket_enc.bet_mode"} == "Live") {

                                        if ($condicionesproducto == 0) {
                                            $cumplecondicionproducto = true;
                                        }

                                    } else {
                                        $cumplecondicionproducto = false;
                                        try {
                                            $BonoInterno = new BonoInterno();
                                            $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                                VALUES ('{$datanum->{"usuario.usuario_id"}}','TORNEO','{$value->{"torneo_interno.torneo_id"}}','SPORTBOOK','{$datanum->{"it_ticket_enc.ticket_id"}}','LIVEORPREMATCH')";
                                            //$BonoInterno->execQuery($transaccion, $sqlLog);
                                        } catch (Exception $e) {
                                            exec("php -f /home/home2/backendprodfinal/api/src/imports/Slack/message.php 'ERRORTORNEOLOG " . $e->getMessage() . "' '#virtualsoft-cron-error-urg' > /dev/null & ");

                                        }
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
                                    try {
                                        $BonoInterno = new BonoInterno();
                                        $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                            VALUES ('{$datanum->{"usuario.usuario_id"}}','TORNEO','{$value->{"torneo_interno.torneo_id"}}','SPORTBOOK','{$datanum->{"it_ticket_enc.ticket_id"}}','MINBETPRICE2SPORTSBOOK')";
                                        // $BonoInterno->execQuery($transaccion, $sqlLog);
                                    } catch (Exception $e) {
                                        exec("php -f /home/home2/backendprodfinal/api/src/imports/Slack/message.php 'ERRORTORNEOLOG " . $e->getMessage() . "' '#virtualsoft-cron-error-urg' > /dev/null & ");

                                    }

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
                                        try {
                                            $BonoInterno = new BonoInterno();
                                            $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                                VALUES ('{$datanum->{"usuario.usuario_id"}}','TORNEO','{$value->{"torneo_interno.torneo_id"}}','SPORTBOOK','{$datanum->{"it_ticket_enc.ticket_id"}}','ITAINMENT82')";
                                            //$BonoInterno->execQuery($transaccion, $sqlLog);
                                        } catch (Exception $e) {
                                            exec("php -f /home/home2/backendprodfinal/api/src/imports/Slack/message.php 'ERRORTORNEOLOG " . $e->getMessage() . "' '#virtualsoft-cron-error-urg' > /dev/null & ");

                                        }
                                    }
                                } else {
                                    if (oldCount($detalleSelecciones) > 1 && oldCount($detalleSelecciones) < $minselcount) {
                                        $cumpleCondicion = false;

                                    }
                                }

                                $valorTicket = floatval($datanum->{"it_ticket_enc.vlr_apuesta"});


                                $puederepetirBono = false;

                                if ($cumpleCondicion && ($cumplecondicionproducto || $condicionesproducto == 0)) {
                                } else {
                                    $cumpleCondicion = false;
                                }


                                if ($condDetallesSelecCouta > 0) {
                                    foreach ($detalleSelecciones as $item) {
                                        if (floatval($condDetallesSelecCouta) > floatval($item->Cuota)) {
                                            $cumpleCondicion = false;
                                            try {
                                                $BonoInterno = new BonoInterno();
                                                $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                                    VALUES ('{$datanum->{"usuario.usuario_id"}}','TORNEO','{$value->{"torneo_interno.torneo_id"}}','SPORTBOOK','{$datanum->{"it_ticket_enc.ticket_id"}}','MINSELPRICE')";
                                                //$BonoInterno->execQuery($transaccion, $sqlLog);
                                            } catch (Exception $e) {
                                                exec("php -f /home/home2/backendprodfinal/api/src/imports/Slack/message.php 'ERRORTORNEOLOG " . $e->getMessage() . "' '#virtualsoft-cron-error-urg' > /dev/null & ");

                                            }
                                        }
                                    }

                                }

                                if ($cumpleCondicion) {

                                    if ($puederepetirBono) {


                                    } else {


                                        $sqlRepiteTorneo = "select * from usuario_torneo a where  a.usutorneo_id !=0 and  a.torneo_id='" . $value->{"torneo_interno.torneo_id"} . "' AND a.usuario_id = '" . $datanum->{"usuario.usuario_id"} . "'";
                                        $repiteTorneo = $this->execQuery('', $sqlRepiteTorneo);

                                        if ((!$puederepetirBono && oldCount($repiteTorneo) == 0)) {

                                        } else {
                                            $cumpleCondicion = false;
                                            try {
                                                $BonoInterno = new BonoInterno();
                                                $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                                    VALUES ('{$datanum->{"usuario.usuario_id"}}','TORNEO','{$value->{"torneo_interno.torneo_id"}}','SPORTBOOK','{$datanum->{"it_ticket_enc.ticket_id"}}','REPETIRTORNEO')";
                                                //$BonoInterno->execQuery($transaccion, $sqlLog);
                                            } catch (Exception $e) {
                                                exec("php -f /home/home2/backendprodfinal/api/src/imports/Slack/message.php 'ERRORTORNEOLOG " . $e->getMessage() . "' '#virtualsoft-cron-error-urg' > /dev/null & ");

                                            }
                                        }

                                    }
                                }


                                if (oldCount($condRank) > 0) {

                                    foreach ($condRank[$datanum->{"usuario.moneda"}] as $item) {

                                        if ($valorTicket >= $item["valor"]) {

                                            if ($valorTicket <= $item["valor2"]) {

                                                $creditosConvert = $item["valor3"];
                                            }
                                        }
                                        $cumpleCondicionRank = true;
                                    }

                                }
                                if ($incomeNetPoint > 0 && $pointValueQuota == 0) {
                                    //SE HACE EL CALCULO DE PUNTOS POR GANANCIA NETA
                                    $netIncome = floatval($datanum->{'it_ticket_enc.vlr_premio'}) - floatval($datanum->{'it_ticket_enc.vlr_apuesta'});
                                    $creditosConvert = $netIncome / floatval($incomeNetPoint);
                                } else if ($incomeNetPoint == 0 && $pointValueQuota > 0) {
                                    //CALCULO DE PUNTOS DE CUOTA TOTAL PUNTOS DE CUOTA (TIPO RANKING 'CUOTAS')
                                    $creditosConvert = $detalleCuotaTotal * floatval($pointValueQuota);
                                } else {
                                    continue;
                                }

                                if ($creditosConvert == 0) {
                                    try {
                                        $BonoInterno = new BonoInterno();
                                        $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                            VALUES ('{$datanum->{"usuario.usuario_id"}}','TORNEO','{$value->{"torneo_interno.torneo_id"}}','SPORTBOOK','{$datanum->{"it_ticket_enc.ticket_id"}}','RANK')";
                                        //$BonoInterno->execQuery($transaccion, $sqlLog);
                                    } catch (Exception $e) {
                                        exec("php -f /home/home2/backendprodfinal/api/src/imports/Slack/message.php 'ERRORTORNEOLOG " . $e->getMessage() . "' '#virtualsoft-cron-error-urg' > /dev/null & ");

                                    }
                                    continue;
                                }


                                if ($needSubscribe) {


                                    $rules = [];
                                    array_push($rules, ['field' => 'usuario_torneo.torneo_id', 'data' => $value->{"torneo_interno.torneo_id"}, 'op' => 'eq']);
                                    array_push($rules, ['field' => 'usuario_torneo.usuario_id', 'data' => $datanum->{"usuario_mandante.usumandante_id"}, 'op' => 'eq']);

                                    $filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);
                                    $UsuarioTorneo2 = new UsuarioTorneo();
                                    $allCoupons = (string)$UsuarioTorneo2->getUsuarioTorneosCustom('COUNT(distinct(usuario_torneo.usuario_id)) countUsers,COUNT((usuario_torneo.usutorneo_id)) countStickers', 'usuario_torneo.usutorneo_id', 'asc', 0, 1000000, $filter, true);

                                    $allCoupons = json_decode($allCoupons, true);


                                    if ($allCoupons['count'][0]['.count'] > 0) {
                                    } else {
                                        $cumpleCondicion = false;
                                        try {
                                            $BonoInterno = new BonoInterno();
                                            $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                                VALUES ('{$datanum->{"usuario.usuario_id"}}','TORNEO','{$value->{"torneo_interno.torneo_id"}}','SPORTBOOK','{$datanum->{"it_ticket_enc.ticket_id"}}','NEEDSUSCRIBE')";
                                            //$BonoInterno->execQuery($transaccion, $sqlLog);
                                        } catch (Exception $e) {
                                            exec("php -f /home/home2/backendprodfinal/api/src/imports/Slack/message.php 'ERRORTORNEOLOG " . $e->getMessage() . "' '#virtualsoft-cron-error-urg' > /dev/null & ");

                                        }
                                    }
                                }

                                if (
                                    !$this->validarEventosRepetidos('deportes', $idDeportes, $puedeRepetirDeportes, $datanum->detallesFinal) ||
                                    !$this->validarEventosRepetidos('ligas', $idLigas, $puedeRepetirLigas, $datanum->detallesFinal) ||
                                    !$this->validarEventosRepetidos('mercados', $idMercados, $puedeRepetirMercados, $datanum->detallesFinal)
                                ) {
                                    $cumpleCondicion = false;
                                }

                                $UsuarioConfiguracion = new UsuarioConfiguracion();
                                $UsuarioConfiguracion = $UsuarioConfiguracion->getUserBondABuser($datanum->{"usuario.usuario_id"});
                                if (!empty($UsuarioConfiguracion->usuconfigId)){
                                    $cumpleCondicion = false;
                                    $BonoInterno = new BonoInterno();
                                    $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                        VALUES ('{$datanum->{"usuario.usuario_id"}}','TORNEO','{$value->{"torneo_interno.torneo_id"}}','SPORTBOOK','{$datanum->{"it_ticket_enc.ticket_id"}}','Contingencia activa: Abusador de bonos')";
                                    //$BonoInterno->execQuery($transaccion, $sqlLog);
                                }

                                if ($cumpleCondicion) {

                                    $ItTicketEncInfo1MySqlDAO = new ItTicketEncInfo1MySqlDAO();


                                    $estado = "P";
                                    $sqlRepiteTorneo = "select * from usuario_torneo a where  a.usutorneo_id !=0 and  a.torneo_id='" . $value->{"torneo_interno.torneo_id"} . "' AND a.usuario_id = '" . $datanum->{"usuario_mandante.usumandante_id"} . "' ";
                                    $repiteTorneo = $this->execQuery('', $sqlRepiteTorneo);


                                    if (oldCount($repiteTorneo) == 0) {
                                        $UsuarioTorneo = new UsuarioTorneo();
                                        $UsuarioTorneo->usuarioId = $datanum->{"usuario_mandante.usumandante_id"};
                                        $UsuarioTorneo->torneoId = $value->{"torneo_interno.torneo_id"};
                                        $UsuarioTorneo->valor = 0;
                                        $UsuarioTorneo->posicion = 0;
                                        $UsuarioTorneo->usucreaId = 0;
                                        $UsuarioTorneo->usumodifId = 0;


                                        $UsuarioTorneo->estado = "A";
                                        $UsuarioTorneo->valor = $UsuarioTorneo->valor + $creditosConvert;
                                        $UsuarioTorneo->valorBase = ($UsuarioTorneo->valorBase + $valorTicket);

                                        $UsuarioTorneo->errorId = 0;
                                        $UsuarioTorneo->idExterno = 0;
                                        $UsuarioTorneo->mandante = 0;
                                        $UsuarioTorneo->version = 0;
                                        $UsuarioTorneo->apostado = $datanum->{"it_ticket_enc.vlr_apuesta"};
                                        $UsuarioTorneo->codigo = 0;
                                        $UsuarioTorneo->externoId = 0;
                                        //$UsuarioTorneo->valorBase = ($UsuarioTorneo->valorBase + $TransaccionApi->valor);


                                        $UsuarioTorneoMySqlDAO = new UsuarioTorneoMySqlDAO($ItTicketEncInfo1MySqlDAO->getTransaction());
                                        $usutorneoId = $UsuarioTorneoMySqlDAO->insert($UsuarioTorneo);

                                    } else {
                                        $usutorneoId = $repiteTorneo[0]->{"a.usutorneo_id"};
                                        $BonoInterno = new BonoInterno();

                                        $transaccion = $ItTicketEncInfo1MySqlDAO->getTransaction();


                                        $sql = "UPDATE usuario_torneo SET valor = valor + " . ($creditosConvert) . ", valor_base = valor_base + " . ($valorTicket) . " WHERE usutorneo_id =" . $usutorneoId;
                                        $BonoInterno->execQuery($transaccion, $sql);

                                    }
                                    $idUsuTorneo = $usutorneoId;

                                    /*$UsuarioMensaje = new UsuarioMensaje();
                                    $UsuarioMensaje->usufromId = 0;
                                    $UsuarioMensaje->usutoId = $UsuarioTorneo->getUsuarioId();
                                    $UsuarioMensaje->isRead = 0;
                                    $UsuarioMensaje->body = '¡ Bien :thumbsup: ! Estas participando en el torneo ' . $value->{"torneo_interno.nombre"} . ' :clap:';
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
                                    $ItTicketEncInfo1->tipo = "TORNEO";

                                    $ItTicketEncInfo1->valor = $idUsuTorneo;
                                    $ItTicketEncInfo1->fechaCrea = date('Y-m-d H:i:s');
                                    $ItTicketEncInfo1->fechaModif = date('Y-m-d H:i:s');
                                    $ItTicketEncInfo1->valor2 = $creditosConvert;

                                    $ItTicketEncInfo1MySqlDAO->insert($ItTicketEncInfo1);
                                    $ItTicketEncInfo1MySqlDAO->getTransaction()->commit();

                                    array_push($ArrayTransaction, $datanum->{"it_ticket_enc.ticket_id"});
                                }
                            }
                        }

                    }

                }


            }

        }

            unlink($filename);


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

        $TorneoInternoMySqlDAO = new TorneoInternoMySqlDAO($transaccion);
        $return = $TorneoInternoMySqlDAO->querySQL($sql);
        $return = json_decode(json_encode($return), FALSE);

        return $return;

    }


    /**
     * Función para validar eventos repetidos según las reglas del torneo
     * @param string $tipo
     * @param string $idEventos
     * @param bool $puedeRepetir
     * @param mixed $detallesFinal
     * @return bool
     */
    function validarEventosRepetidos($tipo, $idEventos, $puedeRepetir, $detallesFinal) {
        if (!empty($idEventos)) {
            $idsEstablecidosEnTorneo = explode(',', $idEventos);
            $eventosRepetidos = $this->obtenerEventosRepetidosEnApuesta($detallesFinal, $tipo);

            if ($puedeRepetir) {
                // Validar que los eventos repetidos estén permitidos en el torneo
                foreach ($eventosRepetidos as $idEvento) {
                    if (!in_array($idEvento, $idsEstablecidosEnTorneo)) {
                        return false;
                    };
                }
            } else {
                // Validar que no haya eventos repetidos en la apuesta
                foreach ($eventosRepetidos as $idEvento) {
                    if (in_array($idEvento, $idsEstablecidosEnTorneo)) return false;
                }
            }
        } else {
            // Si no se establecieron restricciones, cualquier repetición es inválida si no puede repetir
            if (!$puedeRepetir) {
                $eventosRepetidos = $this->obtenerEventosRepetidosEnApuesta($detallesFinal, $tipo);
                if (count($eventosRepetidos) > 0) return false;
            }
        }

        return true;
    }


    /**
     * Función para obtener los eventos repetidos en las apuestas
     * @param mixed $apuestas
     * @param string $evento
     * @return int[]|string[]
     */
    function obtenerEventosRepetidosEnApuesta($apuestas, $evento) {
        $idsEventos = array_map(function($apuesta) use ($evento) {
            return match ($evento) {
                'deportes' => $apuesta->Deporte,
                'ligas'    => $apuesta->Liga,
                'mercados' => substr($apuesta->DeporteMercado, -1)
            };
        }, $apuestas);
        $conteo = array_count_values($idsEventos);
        return array_keys(array_filter($conteo, fn($cantidad) => $cantidad > 1));
    }

}