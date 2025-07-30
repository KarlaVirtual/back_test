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
use Backend\dto\SorteoDetalle;
use Backend\dto\SorteoInterno;
use Backend\dto\TransaccionApi;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\UsuarioBono;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioMandante;
use Backend\dto\Usuario;
use Backend\dto\UsuarioMensaje;
use Backend\dto\UsuarioSession;
use Backend\dto\UsuarioSorteo;
use Backend\integrations\crm\Optimove;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\BonoLogMySqlDAO;
use Backend\mysql\SorteoDetalleMySqlDAO;
use Backend\mysql\SorteoInternoMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransjuegoLogMySqlDAO;
use Backend\mysql\UsuarioBonoMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\UsuarioSorteoMySqlDAO;
use Backend\websocket\WebsocketUsuario;


require(__DIR__ . '/../vendor/autoload.php');
///home/devadmin/api/api/
ini_set('max_execution_time',0);
ini_set('memory_limit', '-1');
header('Content-Type: application/json');
$message = "*CRON: (cronEnvioSmsCrm) * " . " - Fecha: " . date("Y-m-d H:i:s");
$ConfigurationEnvironment = new ConfigurationEnvironment();

if (!$ConfigurationEnvironment->isDevelopment()) {
    exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

}

try {
    $UsuarioMensajecampana = new \Backend\dto\UsuarioMensajecampana();

    $rules = [];

    array_push($rules, array("field" => "usuario_mensajecampana.tipo", "data" => "SMS", "op" => "eq"));
    array_push($rules, array("field" => "usuario_mensajecampana.fecha_envio", "data" => date('Y-m-d H:i:s'), "op" => "le"));

    print_r($rules);

    $filtro = array("rules" => $rules, "groupOp" => "AND");

    $json = json_encode($filtro);

    $data = $UsuarioMensajecampana->getUsuarioMensajesCustom("usuario_mensajecampana.*", "usuario_mensajecampana.usumencampana_id", "ASC", 0, 1000, $json, true);

    $data = json_decode($data);

    $final = [];

    $pos = 1;
    $sorteosAnalizados = '';

    foreach ($data->data as $key => $value) {
        try {

            $UsuarioMensaje= new UsuarioMensaje();

            $rules = [];

            array_push($rules, array("field" => "usuario_mensaje.usumencampana_id", "data" => $value->{"usuario_mensajecampana.usumencampana_id"}, "op" => "eq"));
            array_push($rules, array("field" => "usuario_mensaje.is_read", "data" => "0", "op" => "eq"));
            array_push($rules, array("field" => "usuario_mensaje.fecha_crea", "data" => '2024-03-01 00:00:00', "op" => "le"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");

            $json = json_encode($filtro);

            $UsuarioMensajes = $UsuarioMensaje->getUsuarioMensajesCustom(" usuario_mensaje.*", "usuario_mensaje.usumensaje_id", "asc", 0, 2000000, $json, TRUE);

            $UsuarioMensajes = json_decode($UsuarioMensajes);

            $final = [];
            $Enviados = 0;
            $Fallidos = 0;
            foreach ($UsuarioMensajes->data as $key2 => $value2) {

                if($value2->{"usuario_mensaje.usuto_id"} !='-1'){

                    $Contenido = $value2->{"usuario_mensaje.body"};
                    $UsuarioMandante = new UsuarioMandante($value2->{"usuario_mensaje.usuto_id"});
                    $Registro = new \Backend\dto\Registro("",$UsuarioMandante->usuarioMandante);
                    $UsuarioMensaje = new UsuarioMensaje($value2->{"usuario_mensaje.usumensaje_id"});
                    $respuesta =  $ConfigurationEnvironment->EnviarMensajeTexto($Contenido, '', $Registro->celular, $UsuarioMandante->mandante, $UsuarioMandante,$UsuarioMensaje);
                    $respuesta = json_decode($respuesta);

                    $UsuarioMensaje->setIsRead('1');
                    $UsuarioMensajeMySqlDAO= new UsuarioMensajeMySqlDAO();
                    $UsuarioMensajeMySqlDAO->update($UsuarioMensaje);
                    $UsuarioMensajeMySqlDAO->getTransaction()->commit();

                    if($respuesta->FileRegister != null || $respuesta->FileRegister != "") {
                        $Enviados = $Enviados + 1;
                    }else{
                        $Fallidos = $Fallidos + 1;
                    }

                    $CampaignID= $UsuarioMensaje->valor1;
                }

            }
            $TemplateID = $value->{"usuario_mensajecampana.usumencampana_id"};
            $brand = $value->{"usuario_mensajecampana.mandante"};
            $ContryId = $value->{"usuario_mensajecampana.pais_id"};

            $Optimove = new Optimove();
            $Token = $Optimove->Login($brand,$ContryId);
            $Token=$Token->response;

            //$respuesta = $this->UpdateCampaignMetrics(509,$CampaignID,$Token,$TemplateID,$Enviados,$Fallidos);

        } catch (Exception $e) {
            print_r($e);

        }
    }
    $message = "*CRON: FIN (cronEnvioSmsCrm) * " . " - Fecha: " . date("Y-m-d H:i:s");

    if (!$ConfigurationEnvironment->isDevelopment()) {
        exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

    }
}catch (Exception $e){
    print_r($e);
}

