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
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\TransaccionApi;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioMandante;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransjuegoLogMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;



require(__DIR__.'/../vendor/autoload.php');
///home/devadmin/api/api/

ini_set('memory_limit', '-1');

$sql="select transaccion_id,count(*) count,valor from transjuego_log where fecha_crea >= '".date("Y-m-d H:00:00", strtotime('-1 hours'))."' group by transaccion_id having count >1";

$BonoInterno = new BonoInterno();
$BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
$transaccion = $BonoDetalleMySqlDAO->getTransaction();


$dataSaldoInicial = $BonoInterno->execQuery($transaccion, $sql);
foreach ($dataSaldoInicial as $datanum) {
    $message = "*CRON: (TRANSACCIONES DUPLICADAS) * " .  $datanum->{'transjuego_log.transaccion_id'}. " $ ".  $datanum->{'transjuego_log.valor'}  . " # ".  $datanum->{'.count'} .  " - Fecha: " . date("Y-m-d H:i:s");

    exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");
}


exit();
try {

    $message = "*CRON: (Eliminamos Ezugi RROLLBACK) * " . " - Fecha: " . date("Y-m-d H:i:s");

    exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

    $rules = [];
    array_push($rules, array("field" => "transaccion_api.tipo", "data" => "RROLLBACK", "op" => "eq"));
    array_push($rules, array("field" => "transaccion_api.proveedor_id", "data" => "12", "op" => "eq"));
    array_push($rules, array("field" => "(transaccion_api.fecha_crea)", "data" => date("Y-m-d H:00:00", strtotime('-1 hours')), "op" => "ge"));


    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);


    $select = "transaccion_api.*";
    $grouping = "";


    $TransaccionApiMandante = new TransaccionApi();
    $data = $TransaccionApiMandante->getTransaccionesCustom($select, "transaccion_api.transapi_id", "asc", 0, 1000, $json, true, $grouping);
    $data = json_decode($data);

    $procesadas = array();
    foreach ($data->data as $key => $value) {
        try {
            if (!in_array($value->{'transaccion_api.identificador'}, $procesadas)) {
                array_push($procesadas, $value->{'transaccion_api.identificador'});
                $TransaccionJuego = new TransaccionJuego("", $value->{'transaccion_api.identificador'});
                if ($TransaccionJuego->getEstado() == "A") {


                    $rules = [];
                    array_push($rules, array("field" => "transjuego_log.transjuego_id", "data" => $TransaccionJuego->getTransjuegoId(), "op" => "eq"));


                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                    $json = json_encode($filtro);


                    $select = "transjuego_log.*";
                    $grouping = "transjuego_log.transjuegolog_id";


                    $TransjuegoLog = new TransjuegoLog();
                    $data = $TransjuegoLog->getTransjuegoLogsCustom($select, "transjuego_log.transjuegolog_id", "asc", 0, 100, $json, true, $grouping);
                    $data = json_decode($data);


                    if (oldCount($data->data) == 1) {
                        $value = $data->data[0];
                        if (strpos($value->{"transjuego_log.tipo"}, "DEBIT") !== false) {

                            $TransjuegoLogMySqlDAO = new TransjuegoLogMySqlDAO();


                            //  Creamos el log de la transaccion juego para auditoria
                            $TransjuegoLog2 = new TransjuegoLog();
                            $TransjuegoLog2->setTransjuegoId($TransaccionJuego->getTransjuegoId());
                            $TransjuegoLog2->setTransaccionId("ROLLBACK" . $value->{'transjuego_log.transaccion_id'});
                            $TransjuegoLog2->setTipo('ROLLBACKMANUAL');
                            $TransjuegoLog2->setTValue(json_encode(array()));
                            $TransjuegoLog2->setUsucreaId(0);
                            $TransjuegoLog2->setUsumodifId(0);
                            $TransjuegoLog2->setValor($value->{'transjuego_log.valor'});

                            $TransjuegoLog_id = $TransjuegoLogMySqlDAO->insert($TransjuegoLog2);


                            $TransaccionJuego->setValorPremio($value->{'transjuego_log.valor'});
                            $TransaccionJuego->setEstado('I');

                            $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
                            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                            $Usuario->creditWin($value->{'transjuego_log.valor'}, $TransjuegoLogMySqlDAO->getTransaction());

                            $UsuarioHistorial = new UsuarioHistorial();
                            $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                            $UsuarioHistorial->setDescripcion('');
                            $UsuarioHistorial->setMovimiento('C');
                            $UsuarioHistorial->setUsucreaId(0);
                            $UsuarioHistorial->setUsumodifId(0);
                            $UsuarioHistorial->setTipo(30);
                            $UsuarioHistorial->setValor($TransjuegoLog2->getValor());
                            $UsuarioHistorial->setExternoId($TransjuegoLog_id);

                            $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($TransjuegoLogMySqlDAO->getTransaction());
                            $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);


                            $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO($TransjuegoLogMySqlDAO->getTransaction());
                            $TransaccionJuegoMySqlDAO->update($TransaccionJuego);


                            $TransjuegoLogMySqlDAO->getTransaction()->commit();


                        }
                    }
                }

            }
        } catch (Exception $e) {

        }
    }


}catch (Exception $e){

}


$message = "*CRON: (Analisis) * " . " - Fecha: " . date("Y-m-d H:i:s");

exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");


$ResumenesCron = new ResumenesCron();

//$ResumenesCron->generateResumenes();

$fechaSoloDia = date("Y-m-d", strtotime('0 days'));
$fecha1 = date("Y-m-d H:i:s", strtotime('-1 hour'));
$fecha2 = date("Y-m-d H:i:s", strtotime('0 hour'));

$usuario="";

if ($_REQUEST["diaSpc"] != "" && $_REQUEST["diaSpc2"] != "") {


    $fechaSoloDia = date("Y-m-d", strtotime($_REQUEST["diaSpc"]));
    $fecha1 = date("Y-m-d H:i:s", strtotime($_REQUEST["diaSpc"]));
    $fecha2 = date("Y-m-d H:i:s", strtotime($_REQUEST["diaSpc2"]));

    exit();
} else {
    $arg1 = $argv[1];
    $arg2 = $argv[2];
    $arg3 = $argv[3];
    if ($arg1 != "" && $arg2 != "") {
        $fechaSoloDia = date("Y-m-d", strtotime($arg1));
        $fecha1 = date("Y-m-d H:i:s", strtotime($arg1));
        $fecha2 = date("Y-m-d H:i:s", strtotime($arg2));

        if($arg3 != ''){
            $usuario = " AND usuario.usuario_id='".$arg3."' ";
        }

    } else {
        //exit();
    }

}
try {


    /* OBTENER TODOS LOS USUARIOS EN LA FECHA A ANALIZAR*/
    $sqlUsuariosTabla = "    
 select usuario_historial.usuario_id from usuario_historial  INNER JOIN usuario ON (usuario_historial.usuario_id = usuario.usuario_id)   INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = usuario.usuario_id)
 where usuario.mandante not in (1,2) and usuario_perfil.perfil_id IN ('USUONLINE','PUNTOVENTA') and usuario_historial.fecha_crea >= '".$fecha1."' ".$usuario." and usuario_historial.fecha_crea <= '".$fecha2."' group by usuario_historial.usuario_id
";

    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "Inicia Analisis: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);


    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "Inicia: " . $UsuarioSaldoFinal . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);


    $paso = true;

    $BonoInterno = new BonoInterno();
    $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
    $transaccion = $BonoDetalleMySqlDAO->getTransaction();
    $transaccion->getConnection()->beginTransaction();
    $resultados=array();


    $dataSaldoInicial = $BonoInterno->execQuery($transaccion, $sqlUsuariosTabla);


    foreach ($dataSaldoInicial as $datanum) {

        print_r($datanum);

        $sqlUsuariosTablaDetalle = " 
 select * from usuario_historial 
 where fecha_crea >= '".$fecha1."' and fecha_crea <= '".$fecha2."' AND usuario_id = '".$datanum->{'usuario_historial.usuario_id'}."'";


        $dataSaldoInicialDetalle = $BonoInterno->execQuery($transaccion, $sqlUsuariosTablaDetalle);

        $cont=0;
        $valor=0;
        $saldoCreditosBaseInicial=0;
        $saldoCreditosInicial=0;


        foreach ($dataSaldoInicialDetalle as $datanum2) {

            if($cont>0){

                if($datanum2->{'usuario_historial.movimiento'} == 'E' ){

                    $rest=(($saldoCreditosInicial + $saldoCreditosBaseInicial + floatval($datanum2->{'usuario_historial.valor'})) - (floatval($datanum2->{'usuario_historial.creditos'}) + floatval($datanum2->{'usuario_historial.creditos_base'}) ));
                    if( abs($rest)>0.1){
                        $resultado =  ' Error: *Diferencia:*'.$rest.' *ID:*'.$datanum2->{'usuario_historial.usuhistorial_id'};
                        array_push($resultados,$resultado);
                    }
                }
                if($datanum2->{'usuario_historial.movimiento'} == 'S' ){
                    $rest=(($saldoCreditosInicial + $saldoCreditosBaseInicial - floatval($datanum2->{'usuario_historial.valor'})) - (floatval($datanum2->{'usuario_historial.creditos'}) + floatval($datanum2->{'usuario_historial.creditos_base'}) ));
                    if(abs($rest)>0.1){
                        $resultado =  ' Error: *Diferencia:*'.$rest.' *ID:*'.$datanum2->{'usuario_historial.usuhistorial_id'};
                        array_push($resultados,$resultado);
                    }
                }


            }

            $saldoCreditosInicial=floatval($datanum2->{'usuario_historial.creditos'});
            $saldoCreditosBaseInicial=floatval($datanum2->{'usuario_historial.creditos_base'});
            $valor=floatval($datanum2->{'usuario_historial.valor'});
            $cont++;

        }

    }



    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "Terminacion: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);


    $cont=0;
    foreach ( $resultados as $item) {
        exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $item . "' '#virtualsoft-cron' > /dev/null & ");
        $cont++;
        if($cont == oldCount($resultados)){
            $message = "*CRON: (Fin) * " . " Fin Analisis - Fecha: " . date("Y-m-d H:i:s");
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }
        sleep(0.2);

    }
    if(oldCount($resultados) == 0 ) {

        $message = "*CRON: (Fin) * " . " Fin Analisis - Fecha: " . date("Y-m-d H:i:s");
        exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");
    }

    try {

        $message = "*CRON: (Eliminamos Ezugi RROLLBACK) * " . " - Fecha: " . date("Y-m-d H:i:s");

        exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        $rules = [];
        array_push($rules, array("field" => "transaccion_api.tipo", "data" => "RROLLBACK", "op" => "eq"));
        array_push($rules, array("field" => "transaccion_api.proveedor_id", "data" => "12", "op" => "eq"));
        array_push($rules, array("field" => "(transaccion_api.fecha_crea)", "data" => date("Y-m-d H:00:00", strtotime('-1 hours')), "op" => "ge"));


        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);


        $select = "transaccion_api.*";
        $grouping = "";


        $TransaccionApiMandante = new TransaccionApi();
        $data = $TransaccionApiMandante->getTransaccionesCustom($select, "transaccion_api.transapi_id", "asc", 0, 1000, $json, true, $grouping);
        $data = json_decode($data);

        $procesadas = array();
        foreach ($data->data as $key => $value) {
            try {
                if (!in_array($value->{'transaccion_api.identificador'}, $procesadas)) {
                    array_push($procesadas, $value->{'transaccion_api.identificador'});
                    $TransaccionJuego = new TransaccionJuego("", $value->{'transaccion_api.identificador'});
                    if ($TransaccionJuego->getEstado() == "A") {


                        $rules = [];
                        array_push($rules, array("field" => "transjuego_log.transjuego_id", "data" => $TransaccionJuego->getTransjuegoId(), "op" => "eq"));


                        $filtro = array("rules" => $rules, "groupOp" => "AND");
                        $json = json_encode($filtro);


                        $select = "transjuego_log.*";
                        $grouping = "transjuego_log.transjuegolog_id";


                        $TransjuegoLog = new TransjuegoLog();
                        $data = $TransjuegoLog->getTransjuegoLogsCustom($select, "transjuego_log.transjuegolog_id", "asc", 0, 100, $json, true, $grouping);
                        $data = json_decode($data);


                        if (oldCount($data->data) == 1) {
                            $value = $data->data[0];
                            if (strpos($value->{"transjuego_log.tipo"}, "DEBIT") !== false) {

                                $TransjuegoLogMySqlDAO = new TransjuegoLogMySqlDAO();


                                //  Creamos el log de la transaccion juego para auditoria
                                $TransjuegoLog2 = new TransjuegoLog();
                                $TransjuegoLog2->setTransjuegoId($TransaccionJuego->getTransjuegoId());
                                $TransjuegoLog2->setTransaccionId("ROLLBACK" . $value->{'transjuego_log.transaccion_id'});
                                $TransjuegoLog2->setTipo('ROLLBACKMANUAL');
                                $TransjuegoLog2->setTValue(json_encode(array()));
                                $TransjuegoLog2->setUsucreaId(0);
                                $TransjuegoLog2->setUsumodifId(0);
                                $TransjuegoLog2->setValor($value->{'transjuego_log.valor'});

                                $TransjuegoLog_id = $TransjuegoLogMySqlDAO->insert($TransjuegoLog2);


                                $TransaccionJuego->setValorPremio($value->{'transjuego_log.valor'});
                                $TransaccionJuego->setEstado('I');

                                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
                                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                                $Usuario->creditWin($value->{'transjuego_log.valor'}, $TransjuegoLogMySqlDAO->getTransaction());

                                $UsuarioHistorial = new UsuarioHistorial();
                                $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                                $UsuarioHistorial->setDescripcion('');
                                $UsuarioHistorial->setMovimiento('C');
                                $UsuarioHistorial->setUsucreaId(0);
                                $UsuarioHistorial->setUsumodifId(0);
                                $UsuarioHistorial->setTipo(30);
                                $UsuarioHistorial->setValor($TransjuegoLog2->getValor());
                                $UsuarioHistorial->setExternoId($TransjuegoLog_id);

                                $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($TransjuegoLogMySqlDAO->getTransaction());
                                $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);


                                $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO($TransjuegoLogMySqlDAO->getTransaction());
                                $TransaccionJuegoMySqlDAO->update($TransaccionJuego);


                                $TransjuegoLogMySqlDAO->getTransaction()->commit();


                            }
                        }
                    }

                }
            } catch (Exception $e) {

            }
        }


    }catch (Exception $e){

    }

} catch (Exception $e) {
    print_r($e);
/*    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "ERROR: " . $e->getMessage() . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);


    $message = "*CRON: (ERROR) * " . " Resumenes - Fecha: " . date("Y-m-d H:i:s");

    exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");*/

}





