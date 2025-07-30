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
use Backend\dto\Categoria;
use Backend\dto\CategoriaProducto;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\ItTicketEnc;
use Backend\dto\SorteoDetalle;
use Backend\dto\SorteoInterno;
use Backend\dto\Subproveedor;
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
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\ProductoMandante;
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
require(__DIR__.'/cronSegundosCron.php');

ini_set('max_execution_time',0);
ini_set('memory_limit', '-1');
header('Content-Type: application/json');

for($i=0;$i<10;$i++) {

    $message = "*CRON: (cronSorteos) * " . " - Fecha: " . date("Y-m-d H:i:s");


    $ConfigurationEnvironment = new ConfigurationEnvironment();

    $BonoInterno = new BonoInterno();

    $sqlProcesoInterno2 = "

SELECT * FROM proceso_interno2 WHERE tipo='ALERTASRIESGO'
";


    $data = $BonoInterno->execQuery('', $sqlProcesoInterno2);
    $data = $data[0];
    $line = $data->{'proceso_interno2.fecha_ultima'};

    if ($line == '') {
        exit();
    }

    $_ENV["NEEDINSOLATIONLEVEL"] = '1';

    $fechaL1 = date('Y-m-d H:i:00', strtotime($line . '+1 minute'));
    $fechaL2 = date('Y-m-d H:i:59', strtotime($line . '+1 minute'));


    if ($fechaL1 >= date('Y-m-d H:i:00', strtotime('-2 minute'))) {
        exit();
    }

    print_r('Fecha Inicio: ' . $fechaL1 . ' - Fecha Fin: ' . $fechaL2);


    $BonoInterno = new BonoInterno();
    $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
    $transaccion = $BonoDetalleMySqlDAO->getTransaction();

    $sqlProcesoInterno2 = "
UPDATE proceso_interno2 SET fecha_ultima='" . $fechaL1 . "' WHERE  tipo='ALERTASRIESGO';
";


    $data = $BonoInterno->execQuery($transaccion, $sqlProcesoInterno2);
    $transaccion->commit();


    if (!$ConfigurationEnvironment->isDevelopment()) {

    }
    exec("php -f " . __DIR__ . "../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

    $ActivateAlertasRiesgo = true;
    $ActivateLealtad = false;
    $ActivateDataCompleta2 = false;

    $ActivacionOtros = false;

    $ActivacionSleepTime = true;

    if ($ActivateAlertasRiesgo) {
        $rules = [];


        $daydimensionFecha = 0;
        $FromDateLocal = $fechaL1;
        $ToDateLocal = $fechaL2;

        $TypeBet = 2;

        array_push($rules, array("field" => "usuario.mandante", "data" => "8", "op" => "eq"));
        array_push($rules, array("field" => "it_ticket_enc.bet_status", "data" => "T", "op" => "eq"));


        if ($FromDateLocal != "") {
            //array_push($rules, array("field" => "CONCAT(it_ticket_enc.fecha_crea,' ',it_ticket_enc.hora_crea)", "data" => "$FromDateLocal", "op" => "ge"));
            if ($TypeBet == 2) {
                array_push($rules, array("field" => "(it_ticket_enc.fecha_cierre_time)", "data" => "$FromDateLocal", "op" => "ge"));
                $daydimensionFecha = 2;
            } else if ($TypeBet == 3) {
                array_push($rules, array("field" => "(it_ticket_enc.fecha_pago_time)", "data" => "$FromDateLocal", "op" => "ge"));
                $daydimensionFecha = 1;

            } else if ($TypeBet == 4) {
                array_push($rules, array("field" => "(it_ticket_enc.fecha_cierre_time)", "data" => "$FromDateLocal", "op" => "ge"));
                $daydimensionFecha = 2;

            } else {
                array_push($rules, array("field" => "(it_ticket_enc.fecha_crea_time)", "data" => "$FromDateLocal", "op" => "ge"));
            }
        }
        if ($ToDateLocal != "") {
            //array_push($rules, array("field" => "CONCAT(it_ticket_enc.fecha_crea,' ',it_ticket_enc.hora_crea)", "data" => "$ToDateLocal", "op" => "le"));

            if ($TypeBet == 2) {
                array_push($rules, array("field" => "(it_ticket_enc.fecha_cierre_time)", "data" => "$ToDateLocal", "op" => "le"));
                $daydimensionFecha = 2;
            } else if ($TypeBet == 3) {
                array_push($rules, array("field" => "(it_ticket_enc.fecha_pago_time)", "data" => "$ToDateLocal", "op" => "le"));
                $daydimensionFecha = 1;

            } else if ($TypeBet == 4) {
                array_push($rules, array("field" => "(it_ticket_enc.fecha_cierre_time)", "data" => "$FromDateLocal", "op" => "ge"));
                $daydimensionFecha = 2;

            } else {
                array_push($rules, array("field" => "(it_ticket_enc.fecha_crea_time)", "data" => "$ToDateLocal", "op" => "le"));

            }
        }


// array_push($rules, array("field" => "it_ticket_enc.usuario_id", "data" => "$ClientId", "op" => "eq"));

        $SkeepRows = 0;
        $MaxRows = 10000;

        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);


        $ItTicketEnc = new ItTicketEnc();
        $tickets = $ItTicketEnc->getTicketsCustom(" usuario.mandante,usuario.usuario_id,usuario.nombre,usuario.login,usuario.moneda,it_ticket_enc.ticket_id,it_ticket_enc.vlr_apuesta,it_ticket_enc.vlr_premio,it_ticket_enc.fecha_cierre_time,it_ticket_enc.fecha_crea_time", 'it_ticket_enc.ticket_id', "desc", $SkeepRows, $MaxRows, $json, true, "", "", true, $daydimensionFecha, false);
        $tickets = json_decode($tickets);
        $final = [];

        foreach ($tickets->data as $key => $value) {
            $date2 = ($value->{'it_ticket_enc.fecha_cierre_time'});
            $date1 = ($value->{'it_ticket_enc.fecha_crea_time'});

            $seconds = (strtotime($date2) - strtotime($date1));


            if (floatval($value->{'it_ticket_enc.vlr_apuesta'})>0 && ((floatval($value->{'it_ticket_enc.vlr_premio'}) / floatval($value->{'it_ticket_enc.vlr_apuesta'})) * 100) >= 85 && ((floatval($value->{'it_ticket_enc.vlr_premio'}) / floatval($value->{'it_ticket_enc.vlr_apuesta'})) * 100) < 100
                && floatval($value->{'it_ticket_enc.vlr_apuesta'}) >= 50
                && $seconds <= 120
            ) {
                try {
                    $Mandante = new Mandante($value->{'usuario.mandante'});

                    $message = 'Cashout Inmediato V4 ' . $seconds . ' - *Partner:*' . $Mandante->nombre . ' *Usuario:* ' . $value->{'usuario.usuario_id'} . ' - *Ticket:* ' . $value->{'it_ticket_enc.ticket_id'} . ' - *Valor:* ' . $value->{'usuario.moneda'} . ' ' . $value->{'it_ticket_enc.vlr_apuesta'} . ' - *Valor Premio:* ' . $value->{'usuario.moneda'} . ' ' . $value->{'it_ticket_enc.vlr_premio'};

                    exec("php -f " . __DIR__ . "../src/imports/Slack/message.php '" . $message . "' '#riesgo-general' > /dev/null & ");
                } catch (Exception $e) {

                }
            }

        }


    }

    print_r('PROCCESS OK');


}

