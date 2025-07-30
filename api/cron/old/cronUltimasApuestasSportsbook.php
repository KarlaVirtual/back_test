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
use Backend\dto\SitioTracking;
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
///home/devadmin/api/api/
ini_set('max_execution_time',0);
ini_set('memory_limit', '-1');
header('Content-Type: application/json');
$message = "*CRON: (cronSorteos) * " . " - Fecha: " . date("Y-m-d H:i:s");


$BonoInterno = new BonoInterno();

$sqlProcesoInterno2 = "

SELECT * FROM proceso_interno2 WHERE tipo='ULTIMASAPUESTASPORTSBOOK'
";


$data = $BonoInterno->execQuery('', $sqlProcesoInterno2);
$data=$data[0];

$line = $data->{'proceso_interno2.fecha_ultima'};

if($line == ''){
    exit();
}


$fechaL1 = date('Y-m-d H:i:00', strtotime($line.'+1 minute'));
$fechaL2 = date('Y-m-d H:i:59', strtotime($line.'+1 minute'));


if ($fechaL1 >= date('Y-m-d H:i:00')) {
    exit();
}

$BonoInterno = new BonoInterno();
$BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
$transaccion = $BonoDetalleMySqlDAO->getTransaction();

$sqlProcesoInterno2 = "
UPDATE proceso_interno2 SET fecha_ultima='".$fechaL2."' WHERE  tipo='SORTEOSPORTSBOOK';
";


$data = $BonoInterno->execQuery($transaccion, $sqlProcesoInterno2);
$transaccion->commit();


$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log ."Inicia: ". date('Y-m-d H:i:s');
$fp = fopen(__DIR__.'/logs/Slog_' . date("Y-m-d") . '.log', 'a');
//fwrite($fp, $log);
//fclose($fp);


$rules=[];

$debug=false;

$BonoInterno = new BonoInterno();


$TypeBet=2;


array_push($rules, array("field" => "it_ticket_enc.bet_status", "data" => "'S','N'", "op" => "in"));
array_push($rules, array("field" => "it_ticket_enc.freebet", "data" => "0", "op" => "eq"));
array_push($rules, array("field" => "it_ticket_enc.eliminado", "data" => "N", "op" => "eq"));
//array_push($rules, array("field" => "it_ticket_enc.ticket_id", "data" => implode(',',$ArrayTransaction), "op" => "ni"));


if ( $fechaL1 != "") {
    if ($TypeBet == 2) {
        array_push($rules, array("field" => "(it_ticket_enc.fecha_cierre_time)", "data" => "$fechaL1", "op" => "ge"));
        $daydimensionFecha=2;
    }
}

if ( $fechaL2 != "") {

    if ($TypeBet == 2) {
        array_push($rules, array("field" => "(it_ticket_enc.fecha_cierre_time)", "data" => "$fechaL2", "op" => "le"));
        $daydimensionFecha=2;
    }
}

array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "USUONLINE", "op" => "eq"));
//array_push($rules, array("field" => "usuario.mandante", "data" => $value->{"sorteo_interno.mandante"}, "op" => "eq"));


$SkeepRows=0;
$MaxRows=10000000;

$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);


$ItTicketEnc = new ItTicketEnc();


$tickets = $ItTicketEnc->getTicketsCustom2("usuario.mandante,usuario_mandante.pais_id, usuario_mandante.usumandante_id, usuario.usuario_id,usuario.nombre,usuario.login,usuario.moneda,it_ticket_enc.ticket_id,it_ticket_enc.bet_mode,it_ticket_enc.vlr_apuesta,it_ticket_enc.vlr_premio,it_ticket_enc.estado,it_ticket_enc.premiado",  'it_ticket_enc.ticket_id',"desc",$SkeepRows, $MaxRows, $json, true,"","",false,$daydimensionFecha,false, "", "");
$tickets = json_decode($tickets);
$dataUsuario=$tickets->data;
if($dataUsuario !== NULL &&  $dataUsuario !== "" &&  $dataUsuario[0] !== NULL) {
    foreach ($dataUsuario as $key4=>$datanum) {

        if($datanum->{"usuario.mandante"} ==17 && $datanum->{"it_ticket_enc.estado"} =='I' && $datanum->{"it_ticket_enc.premiado"} =='N'){


            $campaignName = '';
            $campaignSource = '';
            $campaignContent = '';

            $jsonW = '{"rules" : [{"field" : "sitio_tracking.tabla", "data": "registro","op":"eq"},{"field" : "sitio_tracking.tabla_id", "data": "' . $datanum->{"usuario.usuario_id"} . '","op":"eq"}] ,"groupOp" : "AND"}';

            $SitioTracking = new SitioTracking();
            $sitiosTracking = $SitioTracking->getSitioTrackingesCustom(" sitio_tracking.* ", "sitio_tracking.sitiotracking_id", "asc", 0, 1, $jsonW, true);
            $sitiosTracking = json_decode($sitiosTracking);

            $tvalue = $sitiosTracking->data[0]->{'sitio_tracking.tvalue'};

            if ($tvalue != '') {
                $tvalue = json_decode($tvalue);

                if ($tvalue->vs_utm_campaign != '') {
                    $campaignName = $tvalue->vs_utm_campaign;
                }
                if ($tvalue->vs_utm_source != '') {
                    $campaignSource = $tvalue->vs_utm_source;
                }
                if ($tvalue->vs_utm_content != '') {
                    $campaignContent = $tvalue->vs_utm_content;
                }

            }

            if($campaignName != '' && strpos($campaignName,'clickid_') !== false) {


                $curl = curl_init('https://apretailer.com.br/ok/22322.png?partner=' . str_replace('clickid_', '', $campaignName) . '&apid=' . $datanum->{"usuario.usuario_id"} . '&price=1');
                curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
                curl_setopt($curl, CURLOPT_TIMEOUT, 30);
                (curl_exec($curl));
                curl_close($curl);

                if (false) {
                    $sql = "SELECT * FROM usuario_saldoresumen where usuario_id='" . $datanum->{"usuario.usuario_id"} . "'";
                    $data = $BonoInterno->execQuery('', $sql);
                    $data = $data[0];

                    $saldo_recarga = 0;
                    $saldo_apuestas = 0;
                    $saldo_premios = 0;
                    $saldo_notaret_pagadas = 0;
                    $saldo_notaret_pend = 0;
                    $saldo_ajustes_entrada = 0;
                    $saldo_ajustes_salida = 0;
                    $saldo_bono = 0;
                    $saldo_notaret_creadas = 0;
                    $saldo_apuestas_casino = 0;
                    $saldo_apuestas_casino = 0;
                    $saldo_notaret_eliminadas = 0;
                    $saldo_bono_free_ganado = 0;
                    $saldo_bono_casino_free_ganado = 0;
                    $saldo_bono_casino_vivo = 0;
                    $saldo_bono_casino_vivo_free_ganado = 0;
                    $saldo_bono_virtual = 0;
                    $saldo_bono_virtual_free_ganado = 0;
                    $saldo_apuestas_casino_vivo = 0;

                    if ($data != null) {

                        $saldo_recarga = floatval($data->{'.saldo_recarga'});
                        $saldo_apuestas = floatval($data->{'.saldo_apuestas'});
                        $saldo_premios = floatval($data->{'.saldo_premios'});
                        $saldo_notaret_pagadas = floatval($data->{'.saldo_notaret_pagadas'});
                        $saldo_notaret_pend = floatval($data->{'.saldo_notaret_pend'});
                        $saldo_ajustes_entrada = floatval($data->{'.saldo_ajustes_entrada'});
                        $saldo_ajustes_salida = floatval($data->{'.saldo_ajustes_salida'});
                        $saldo_bono = floatval($data->{'.saldo_bono'});
                        $saldo_notaret_creadas = floatval($data->{'.saldo_notaret_creadas'});
                        $saldo_apuestas_casino = floatval($data->{'.saldo_apuestas_casino'});
                        $saldo_apuestas_casino = floatval($data->{'.saldo_premios_casino'});
                        $saldo_notaret_eliminadas = floatval($data->{'.saldo_notaret_eliminadas'});
                        $saldo_bono_free_ganado = floatval($data->{'.saldo_bono_free_ganado'});
                        $saldo_bono_casino_free_ganado = floatval($data->{'.saldo_bono_casino_free_ganado'});
                        $saldo_bono_casino_vivo = floatval($data->{'.saldo_bono_casino_vivo'});
                        $saldo_bono_casino_vivo_free_ganado = floatval($data->{'.saldo_bono_casino_vivo_free_ganado'});
                        $saldo_bono_virtual = floatval($data->{'.saldo_bono_virtual'});
                        $saldo_bono_virtual_free_ganado = floatval($data->{'.saldo_bono_virtual_free_ganado'});
                        $saldo_apuestas_casino_vivo = floatval($data->{'.saldo_apuestas_casino_vivo'});
                    }


                    $sql = "
select usuario_id,sum(usuario_recarga.valor) as valor
from usuario_recarga
where fecha_crea LIKE '" . date('Y-m-d') . "%' and usuario_id = '" . $datanum->{"usuario.usuario_id"} . "'";
                    $data = $BonoInterno->execQuery('', $sql);
                    $data = $data[0];
                    if ($data != null) {
                        $saldo_recarga = $saldo_recarga + floatval($data->{'.valor'});
                    }


                    if($saldo_recarga >=30){

                        $curl = curl_init('https://apretailer.com.br/ok/22322.png?partner=' . str_replace('clickid_', '', $campaignName) . '&apid=' . $datanum->{"usuario.usuario_id"} . '&price=1');
                        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
                        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
                        (curl_exec($curl));
                        curl_close($curl);
                    }
                }


            }

        }

    }

}else{
    exit();
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


