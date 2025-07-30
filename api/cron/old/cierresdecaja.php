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
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\dto\Usuario;

require(__DIR__.'/../vendor/autoload.php');
///home/devadmin/api/api/

ini_set('memory_limit', '-1');

$message = "*CRON: (Inicio) * " . " Resumenes - Fecha: " . date("Y-m-d H:i:s");

exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");


$ResumenesCron = new ResumenesCron();

//$ResumenesCron->generateResumenes();

$fechaSoloDia = date("Y-m-d", strtotime('-1 days'));
$fecha1 = date("Y-m-d 00:00:00", strtotime('-1 days'));
$fecha2 = date("Y-m-d 23:59:59", strtotime('-1 days'));

if ($_REQUEST["diaSpc"] != "") {
    exit();

    exec("php -f " . __DIR__ . "/cierresdecaja.php " . $_REQUEST["diaSpc"] . " > /dev/null &");

    $fechaSoloDia = date("Y-m-d", strtotime($_REQUEST["diaSpc"]));
    $fecha1 = date("Y-m-d 00:00:00", strtotime($_REQUEST["diaSpc"]));
    $fecha2 = date("Y-m-d 23:59:59", strtotime($_REQUEST["diaSpc"]));

} else {
    $arg1 = $argv[1];
    if ($arg1 != "") {
        $fechaSoloDia = date("Y-m-d", strtotime($arg1));
        $fecha1 = date("Y-m-d 00:00:00", strtotime($arg1));
        $fecha2 = date("Y-m-d 23:59:59", strtotime($arg1));

    } else {
        //exit();
    }

}

try {

//BETWEEN '".$fecha1."' AND '".$fecha2."'

    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "Terminacion: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);

    $message = "*CRON: (Fin) * " . " Resumenes - Fecha: " . date("Y-m-d H:i:s");

    try{

        $rules = [];
        array_push($rules, array("field" => "usuario.usuario_id", "data" => "'5208','14919','5703','5234','5219','22580','8752','204','9362','14913','12798','8937','1687','391','9385','24604','21229','21016','20856','9723','202','1589','3145','23399','2197','1775','20969','33302','15611','9386','5218','27278','199','3308','38554','38202','26687','34821','30497','4105','9336'", "op" => "in"));


        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);

        $Usuario = new Usuario();

        $usuarios = $Usuario->getUsuariosCustom("  usuario.usuario_id,usuario.fecha_cierrecaja,usuario_mandante.usumandante_id ", "usuario.usuario_id", "desc", 0, 100000, $json, true);

        $usuarios = json_decode($usuarios);

        $usuariosFinal = [];

        foreach ($usuarios->data as $key => $value) {

            if($value->{'usuario.fecha_cierrecaja'} != ''){
                if(date('Y-m-d H:i:s',strtotime($value->{'usuario.fecha_cierrecaja'})) < date('Y-m-d H:i:s',strtotime('-1 days'))){

                    $ConfigurationEnvironment = new ConfigurationEnvironment();

                    $ConfigurationEnvironment->CierreCaja($value->{'usuario_mandante.usumandante_id'},array(),array(),array(),date('Y-m-d',strtotime('-1 days')),date('Y-m-d 00:00:00',strtotime('-1 days')),date('Y-m-d 23:59:59',strtotime('-1 days')));
                }
            }

        }

        $log = "\r\n" . "-------------------------" . "\r\n";
        $log = $log . "Terminacion Cierres de caja: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
        fwriteCustom('log_' . date("Y-m-d") . '.log',$log);

        $message = "*CRON: (Fin) * " . " Terminacion Cierres de caja - Fecha: " . date("Y-m-d H:i:s");

        exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

    }catch (Exception $e){
        print_r($e);
        $log = "\r\n" . "-------------------------" . "\r\n";
        $log = $log . "ERROR: " . $e->getMessage() . " - " . date('Y-m-d H:i:s');
        fwriteCustom('log_' . date("Y-m-d") . '.log',$log);


        $message = "*CRON: (ERROR) * " . " Cierres de caja - Fecha: " . date("Y-m-d H:i:s");

        exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

    }
    exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");


} catch (Exception $e) {
    print_r($e);
    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "ERROR: " . $e->getMessage() . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);


    $message = "*CRON: (ERROR) * " . " Resumenes - Fecha: " . date("Y-m-d H:i:s");

    exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

}





