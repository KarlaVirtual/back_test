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
use Backend\dto\UsuarioAutomation;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioMandante;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransjuegoLogMySqlDAO;
use Backend\mysql\UsuarioAutomationMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;



require(__DIR__.'/../vendor/autoload.php');
///home/devadmin/api/api/


use Backend\integrations\casino\Vivogaming;

ini_set('memory_limit', '-1');

$message = "*CRON: (cronMensajes) * " . " - Fecha: " . date("Y-m-d H:i:s");
$ConfigurationEnvironment = new ConfigurationEnvironment();

if (!$ConfigurationEnvironment->isDevelopment()){
    exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

}

try {

    $message = "*CRON: (Eliminamos VIVOGAMING RROLLBACK) * " . " - Fecha: " . date("Y-m-d H:i:s");

    exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

    $rules = [];
    array_push($rules, array("field" => "transaccion_api.tipo", "data" => "RROLLBACK_0", "op" => "eq"));
    array_push($rules, array("field" => "transaccion_api.proveedor_id", "data" => "68", "op" => "eq"));
    array_push($rules, array("field" => "(transaccion_api.fecha_crea)", "data" => date("Y-m-d H:00:00", strtotime('-12 hours')), "op" => "ge"));


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
            $externalId = $value->{'transaccion_api.usuario_id'};
            $Token = "";
            $accessToken = "";
            $method = "";
            $gameId = "";
            $roundId = explode("VIVOGAMING", $value->{'transaccion_api.identificador'})[1];
            $real = $value->{'transaccion_api.valor'};
            $transactionId = $value->{'transaccion_api.transaccion_id'};
            $datos = $value->{'transaccion_api.t_value'};

            $Vivogaming = new Vivogaming($Token, $accessToken, $externalId, $method);

            $response = ($Vivogaming->RollbackCRON($gameId, $roundId, "", $real, $transactionId, json_encode($datos)));


        } catch (Exception $e) {

        }
    }


}catch (Exception $e){

}
