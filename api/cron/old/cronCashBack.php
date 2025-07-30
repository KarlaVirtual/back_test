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
use Backend\dto\BonoLog;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\BonoDetalle;
use Backend\dto\TransaccionApi;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\UsuarioBono;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioMandante;
use Backend\dto\Usuario;
use Backend\dto\UsuarioMensaje;
use Backend\dto\UsuarioSession;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\BonoLogMySqlDAO;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransjuegoLogMySqlDAO;
use Backend\mysql\UsuarioBonoMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\websocket\WebsocketUsuario;

//Cron Cashback



require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__.'/cronSegundosCron.php');

ini_set('max_execution_time',0);
ini_set('memory_limit', '-1');
header('Content-Type: application/json');
$ConfigurationEnvironment = new ConfigurationEnvironment();
date_default_timezone_set('America/Bogota');

for($i=0;$i<10;$i++) {
    $message = "*CRON: (cronCashBack) * " . " - Fecha: " . date("Y-m-d H:i:s");

    if (!$ConfigurationEnvironment->isDevelopment()) {
        exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

    }

    if (true) {

        $BonoInterno = new BonoInterno();

        $sqlProcesoInterno2 = "

SELECT * FROM proceso_interno2 WHERE tipo='CASHBACKGGR'
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

        if (date('H:i:s') >= '00:00:00' && date('H:i:s') <= '00:10:00') {
            sleep(300);
        }

        $BonoInterno = new BonoInterno();
        $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
        $transaccion = $BonoDetalleMySqlDAO->getTransaction();

        $sqlProcesoInterno2 = "
UPDATE proceso_interno2 SET fecha_ultima='" . $fechaL1 . "' WHERE  tipo='CASHBACKGGR';
";


        $data = $BonoInterno->execQuery($transaccion, $sqlProcesoInterno2);
        $transaccion->commit();

        try {
            $BonoInterno = new BonoInterno();

            $rules = [];

            array_push($rules, array("field" => "bono_interno.estado", "data" => "A", "op" => "eq"));
            array_push($rules, array("field" => "bono_interno.tipo", "data" => "4", "op" => "eq"));
            array_push($rules, array("field" => "bono_interno.fecha_fin", "data" => $fechaL1, "op" => "ge"));
            array_push($rules, array("field" => "bono_interno.fecha_fin", "data" => $fechaL2, "op" => "le"));
//array_push($rules, array("field" => "bono_interno.bono_id", "data" => '87', "op" => "eq"));
//array_push($rules, array("field" => "bono_interno.fecha_fin", "data" => date('Y-m-d H:i:s'), "op" => "ge"));
//array_push($rules, array("field" => "bono_interno.tipo", "data" => "2", "op" => "eq"));

            print_r($rules);
            $filtro = array("rules" => $rules, "groupOp" => "AND");

            $json = json_encode($filtro);


            $data = $BonoInterno->getBonosCustom("bono_interno.*", "bono_interno.orden", "ASC", 0, 1000, $json, true);

            $data = json_decode($data);

            print_r($data);

            $final = [];

            $pos = 1;
            $sorteosAnalizados = '';

            $GGRPorcentaje = array();


            foreach ($data->data as $key => $value) {


                $BonoDetalle = new BonoDetalle();

                $rules = [];

                //array_push($rules, array("field" => "bono_interno.bono_id", "data" => 132, "op" => "eq"));
                array_push($rules, array("field" => "bono_interno.bono_id", "data" => $value->{"bono_interno.bono_id"}, "op" => "eq"));
                array_push($rules, array("field" => "bono_interno.estado", "data" => "A", "op" => "eq"));
                array_push($rules, array("field" => "bono_detalle.tipo", "data" => "'TIPOSALDO','PORCENTAJEGGR'", "op" => "in"));
                $filtro = array("rules" => $rules, "groupOp" => "AND");

                $json = json_encode($filtro);

                $bonodetalles = $BonoDetalle->getBonoDetallesCustom(" bono_detalle.*,bono_interno.* ", "bono_interno.bono_id", "asc", 0, 1000, $json, TRUE);


                $bonodetalles = json_decode($bonodetalles);


                if (intval($data->count[0]->{".count"}) == 0) {
                    throw new Exception("No existen bonos", "8000");
                }
                $final = [];


                foreach ($bonodetalles->data as $key2 => $value2) {


                    switch ($value2->{"bono_detalle.tipo"}) {

                        case  "TIPOSALDO":

                            $TipoSaldo = $value2->{"bono_detalle.valor"};
                            break;
                        case  "PORCENTAJEGGR":

                            var_dump($value2->{"bono_detalle.moneda"});

                            $Moneda = $value2->{"bono_detalle.moneda"};
                            $GGRPorcentajeBase = $value2->{"bono_detalle.valor"};

                            $GGRPorcentaje[$value2->{"bono_detalle.moneda"}] = $value2->{"bono_detalle.valor"};

                            var_dump($GGRPorcentaje);

                            break;

                        case "MINPAGO":

                            $PagoMin = $value2->{"bono_detalle.valor"};

                            break;

                        case "MAXPAGO":

                            $PagoMax = $value2->{"bono_detalle.valor"};

                            break;

                    }

                }

                $rules = [];


                array_push($rules, array("field" => "usuario_bono.estado", "data" => "P", "op" => "eq"));

                array_push($rules, array("field" => "usuario_bono.bono_id", "data" => $value->{"bono_interno.bono_id"}, "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                $SkeepRows = 0;
                $OrderedItem = 1;
                $MaxRows = 10000000;

                $json = json_encode($filtro);

                $UsuarioBono = new UsuarioBono();
                $data3 = $UsuarioBono->getUsuarioBonoCustom2("usuario_bono.*", "usuario_bono.usubono_id", 'asc', 0, 1000, $json, true);
                $data3 = json_decode($data3);


                if (intval($data3->count[0]->{".count"}) == 0) {
                    throw new Exception("No existen participantes", "9000");
                }


                foreach ($data3->data as $key3 => $value3) {


                    $usuBonoId = $value3->{"usuario_bono.usubono_id"};

                    $UsuarioBono = new UsuarioBono($usuBonoId);

                    $Apuestas = $UsuarioBono->getApostado();
                    $Premios = $UsuarioBono->getPremio();
                    $BonoId = $UsuarioBono->getBonoId();
                    $GGR = ($Premios - $Apuestas);


                    if ($GGR < 0) {

                        $valor = ($GGR * -1);

                        $valorCashBack = ($valor * ($GGRPorcentajeBase / 100));

                        if ($valorCashBack >= $PagoMin || $PagoMin == 0 || $PagoMin == "" || $PagoMin == null) {

                            $Usuario = new Usuario($UsuarioBono->usuarioId);
                            $UsuarioMandante = new UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);

                            $valor = ($GGR * -1);


                            if ($valorCashBack > $PagoMax && $PagoMax !== 0 && $PagoMax !== "" && $PagoMax !== null) {

                                $valorCashBack = $PagoMax;
                            }

                            $UsuarioBono->setEstado("R");

                            $UsuarioBonoMySqlDAO = new UsuarioBonoMySqlDAO();
                            $UsuarioBonoMySqlDAO->update($UsuarioBono);
                            $UsuarioBonoMySqlDAO->getTransaction()->commit();
                            //usuario credit // saldo recargas
                            //usuario credit win

                            $UsuarioBonoMySqlDAO = new UsuarioBonoMySqlDAO();
                            $Transaction = $UsuarioBonoMySqlDAO->getTransaction();

                            $BonoLog = new BonoLog();
                            $BonoLog->setUsuarioId($Usuario->usuarioId);
                            $BonoLog->setTipo('CC');
                            $BonoLog->setValor($valorCashBack);
                            $BonoLog->setFechaCrea(date('Y-m-d H:i:s'));
                            $BonoLog->setEstado('L');
                            $BonoLog->setErrorId(0);
                            $BonoLog->setIdExterno($UsuarioBono->usubonoId);
                            $BonoLog->setMandante($Usuario->mandante);
                            $BonoLog->setFechaCierre('');
                            $BonoLog->setTransaccionId('');
                            $BonoLog->setTipobonoId(4);
                            $BonoLog->setTiposaldoId("");

                            if ($TipoSaldo == '0') {

                                $Usuario->credit($valorCashBack, $Transaction);

                            } elseif ($TipoSaldo == '1') {
                                $Usuario->creditWin($valorCashBack, $Transaction);

                            }
                            $BonoLogMySqlDAO = new BonoLogMySqlDAO($Transaction);


                            $bonologId = $BonoLogMySqlDAO->insert($BonoLog);


                            $UsuarioHistorial = new UsuarioHistorial();
                            $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                            $UsuarioHistorial->setDescripcion('');
                            $UsuarioHistorial->setMovimiento('E');
                            $UsuarioHistorial->setUsucreaId($Usuario->usuarioId);
                            $UsuarioHistorial->setUsumodifId(0);
                            $UsuarioHistorial->setTipo(50);
                            $UsuarioHistorial->setValor($valorCashBack);
                            $UsuarioHistorial->setExternoId($bonologId);

                            $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                            $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);

                            $UsuarioBono->setValor($valorCashBack);

                            //$Transaction->commit();

                            $title = '';
                            $messageBody = '';
                            $cashBackName = $value->{"bono_interno.nombre"};

                            switch (strtolower($Usuario->idioma)) {
                                case 'es':
                                    $title = 'Felicidades!!';
                                    $messageBody = "¡ Bien :thumbsup: ! Has ganado un Bono de CashBack {$cashBackName}_ :clap:";
                                    break;
                                case 'en':
                                    $title = 'Congratulations!';
                                    $messageBody = "¡ Great :thumbsup: ! You have earned a Cashback bonus {$cashBackName}_ :clap:";
                                    break;
                                case 'pt':
                                    $title = 'Parabéns!';
                                    $messageBody = "Eaí! :thumbsup: Você ganhou um bônus de CashBack {$cashBackName}_ :clap:";
                                    break;
                            }

                            $UsuarioMensaje = new UsuarioMensaje();
                            $UsuarioMensaje->usufromId = 0;
                            $UsuarioMensaje->usutoId = $UsuarioMandante->getUsumandanteId();
                            $UsuarioMensaje->isRead = 0;
                            $UsuarioMensaje->body = $messageBody;
                            $UsuarioMensaje->msubject = $title;
                            $UsuarioMensaje->parentId = 0;
                            $UsuarioMensaje->proveedorId = 0;
                            $UsuarioMensaje->tipo = "PUSHNOTIFICACION";
                            $UsuarioMensaje->paisId = $UsuarioMandante->paisId;
                            $UsuarioMensaje->fechaExpiracion = '';
                            $UsuarioMensaje->valor1 = json_encode("");

                            $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($Transaction);
                            $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);

                            $UsuarioBonoMySqlDAO->update($UsuarioBono);
                            $Transaction->commit();


                            $mensajesRecibidos = [];
                            $array = [];

                            $array["body"] = $UsuarioMensaje->body;

                            array_push($mensajesRecibidos, $array);
                            $data4 = array();
                            $data4["messages"] = $mensajesRecibidos;


                            //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());
                            $ConfigurationEnvironment = new ConfigurationEnvironment();

                            if (!$ConfigurationEnvironment->isDevelopment()) {

                                if (in_array($UsuarioMandante->mandante, array('0', 8, 6))) {

                                    $dataSend = $data4;
                                    $WebsocketUsuario = new WebsocketUsuario('', '');
                                    $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend);

                                }
                            }
                        }
                    }
                }
            }
            $message = "*CRON: FIN (cronCashBack) * " . " - Fecha: " . date("Y-m-d H:i:s");

            if (!$ConfigurationEnvironment->isDevelopment()) {
                exec("php -f " . __DIR__ . "../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

            }

        } catch (Exception $e) {
            print_r($e);
        }

    }


    print_r('PROCCESS OK');
    sleep(3);

}


