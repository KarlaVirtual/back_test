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


require(__DIR__.'/../vendor/autoload.php');
///home/devadmin/api/api/

ini_set('memory_limit', '-1');

$message = "*CRON: (Inicio) * " . " BodegaFlujoCaja - Fecha: " . date("Y-m-d H:i:s");

//exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");


$ResumenesCron = new ResumenesCron();

//$ResumenesCron->generateResumenes();

$fechaSoloDia = date("Y-m-d", strtotime('-1 days'));
$fecha1 = date("Y-m-d 00:00:00", strtotime('-1 days'));
$fecha2 = date("Y-m-d 23:59:59", strtotime('-1 days'));

if ($_REQUEST["diaSpc"] != "") {


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


    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "Terminacion: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);

    $message = "*CRON: (Fin) * " . " BodegaFlujoCaja - Fecha: " . date("Y-m-d H:i:s");

    //exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");


    $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
    $transaccion = $BonoDetalleMySqlDAO->getTransaction();
    $BonoInterno= new BonoInterno();



    print_r("CALL LoadCalendars('".date("Y-m-d", strtotime("+1 day"))." 00:00:00', 86400);");

    $BonoInterno->execQuery($transaccion, "CALL LoadCalendars('".date("Y-m-d", strtotime("+1 day"))." 00:00:00', 86400);");
    $BonoInterno->execQuery($transaccion, "CALL LoadCalendars2('".date("Y-m-d", strtotime("+1 day"))." 00:00:00', 1);");

    $transaccion->commit();


} catch (Exception $e) {
    print_r($e);
    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "ERROR: " . $e->getMessage() . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);


    $message = "*CRON: (ERROR) * " . " BodegaFlujoCaja - Fecha: " . date("Y-m-d H:i:s");

    exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

}





