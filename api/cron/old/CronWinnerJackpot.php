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
use Backend\dto\ItTicketEnc;
use Backend\dto\JackpotInterno;
use Backend\dto\PreUsuarioSorteo;
use Backend\dto\SorteoDetalle;
use Backend\dto\TorneoDetalle;
use Backend\dto\TorneoInterno;
use Backend\dto\CategoriaProducto;
use Backend\dto\SorteoInterno;
use Backend\dto\TransaccionApi;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\UsuarioBono;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioJackpot;
use Backend\dto\UsuariojackpotGanador;
use Backend\dto\UsuarioMandante;
use Backend\dto\Usuario;
use Backend\dto\TransjuegoInfo;
use Backend\dto\UsuarioMensaje;
use Backend\dto\UsuarioTorneo;
use Backend\dto\UsuarioSession;
use Backend\dto\UsuarioSorteo;
use Backend\dto\Mandante;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\BonoLogMySqlDAO;
use Backend\mysql\JackpotInternoMySqlDAO;
use Backend\mysql\PreUsuarioSorteoMySqlDAO;
use Backend\mysql\SorteoDetalleMySqlDAO;
use Backend\mysql\SorteoInternoMySqlDAO;
use Backend\mysql\TorneoDetalleMySqlDAO;
use Backend\mysql\TorneoInternoMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransjuegoLogMySqlDAO;
use Backend\mysql\UsuarioBonoMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\UsuariojackpotGanadorMySqlDAO;
use Backend\mysql\UsuarioJackpotMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\UsuarioTorneoMySqlDAO;
use Backend\mysql\UsuarioSorteoMySqlDAO;
use Backend\websocket\WebsocketUsuario;
use Backend\dao\TransjuegoInfoDAO;
use Backend\mysql\ItTicketEncInfo1MySqlDAO;
use Backend\mysql\TransjuegoInfoMySqlDAO;
use Exception;




require(__DIR__ . '/../vendor/autoload.php');


ini_set('max_execution_time', 0);
ini_set('memory_limit', '-1');
header('Content-Type: application/json');
$message = "*CRON: (cronWinnerJackpot) * " . " - Fecha: " . date("Y-m-d H:i:s");


ini_set("display_errors", "OFF");

try {
    $BonoInterno = new BonoInterno();

    $sqlProcesoInterno2 = "

SELECT * FROM proceso_interno2 WHERE tipo='JACKPOT'
";


    $data = $BonoInterno->execQuery('', $sqlProcesoInterno2);


    $data = $data[0];
    $line = $data->{'proceso_interno2.fecha_ultima'};

    if ($line == '') {
        exit();
    }


    $fechaL1 = date('Y-m-d H:i:00', strtotime($line . '+1 minute'));
    $fechaL2 = date('Y-m-d H:i:59', strtotime($line . '+1 minute'));
    if($_ENV['debug']){

        $fechaL1 = '2022-11-17 18:55:00';
        $fechaL2 = '2022-11-17 18:55:59';
    }else{

        if ($fechaL1 >= date('Y-m-d H:i:00')) {
            exit();
        }
        $BonoInterno = new BonoInterno();
        $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
        $transaccion = $BonoDetalleMySqlDAO->getTransaction();


        $sqlProcesoInterno2 = "
UPDATE proceso_interno2 SET fecha_ultima='".$fechaL1."' WHERE  tipo='JACKPOT';
";

        $data = $BonoInterno->execQuery($transaccion, $sqlProcesoInterno2);
        $transaccion->commit();
    }

    $message ='*Corriendo Jackpot Casino*: '.$fechaL1;
    exec("php -f ".__DIR__."../src/imports/Slack/message.php '".$message."' '#virtualsoft-cron' > /dev/null & ");


    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "Inicia: " . date('Y-m-d H:i:s');

    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);


    $Tipos = array(
        'CASINO','LIVECASINO','VIRTUAL'
    );

    foreach ($Tipos as $tipo) {


        $JackpotInterno = new JackpotInterno();

        $rules = [];

        array_push($rules, array("field" => "jackpot_interno.estado", "data" => "A", "op" => "eq"));
        array_push($rules, array("field" => "jackpot_interno.valor_actual", "data" => "jackpot_interno.valor_maximo", "op" => "gefield"));
        array_push($rules, array("field" => "jackpot_interno.fecha_inicio", "data" => $fechaL1, "op" => "le"));
        array_push($rules, array("field" => "jackpot_interno.fecha_fin", "data" => $fechaL2, "op" => "ge"));

        if ($_ENV['debug']) {
            array_push($rules, array("field" => "jackpot_interno.jackpot_id", "data" => 2009, "op" => "eq"));

        }
        switch ($tipo) {
            case 'CASINO':
                array_push($rules, array("field" => "jackpot_interno.tipo", "data" => "1", "op" => "eq"));

                break;
            case 'LIVECASINO':
                array_push($rules, array("field" => "jackpot_interno.tipo", "data" => "3", "op" => "eq"));

                break;
            case 'VIRTUAL':
                array_push($rules, array("field" => "jackpot_interno.tipo", "data" => "4", "op" => "eq"));

                break;

        }

        print_r($rules);
        $SkeepRows = 0;
        $OrderedItem = 1;
        $MaxRows = 1000;

        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $jsonfiltro = json_encode($filtro);


        $jackpots = $JackpotInterno->getJackpotCustom("jackpot_interno.* ", "jackpot_interno.jackpot_id", "asc", $SkeepRows, $MaxRows, $jsonfiltro, TRUE);

print_r($jackpots);
        $jackpots = json_decode($jackpots);

        foreach ($jackpots->data as $value) {

            $JackpotId = $value->{"jackpot_interno.jackpot_id"};
            $NombreJackpot = $value->{"jackpot_interno.nombre"};
            $ValorActual = $value->{"jackpot_interno.valor_actual"};
            $MinimoTicket = $value->{"jackpot_interno.minimo_ticket"};
            $MaximoTicket = $value->{"jackpot_interno.maximo_ticket"};
            $CantidadApuestamax = $value->{"jackpot_interno.cantidad_apuestamax"};
            $PorcentajeApuestas = $value->{"jackpot_interno.porcentaje_apuestas"};


            $UsuarioJackpot = new UsuarioJackpot();
            $rules = [];
            array_push($rules, array("field" => "usuario_jackpot.jackpot_id", "data" => "$JackpotId", "op" => "eq"));


            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $SkeepRows = 0;
            $OrderedItem = 'rand()';
            $MaxRows = 1;

            $json = json_encode($filtro);

            $UsuJackpots = $UsuarioJackpot->getUsuarioJackpotCustom("usuario_jackpot.*", $OrderedItem, 'asc', $SkeepRows, $MaxRows, $json, true, $OrderedItem);
            $UsuJackpots = json_decode($UsuJackpots);


            if (intval($UsuJackpots->count[0]->{".count"}) == 0) {
                throw new Exception("No existen participantes", "9000");
            }

            $UsuarioId = $UsuJackpots->data[0]->{"usuario_jackpot.usuario_id"};
            $JackpotId = $UsuJackpots->data[0]->{"usuario_jackpot.jackpot_id"};
            $UsujackpotId = $UsuJackpots->data[0]->{"usuario_jackpot.usujackpot_id"};



            $UsuariojackpotGanadorMySqlDAO= new UsuariojackpotGanadorMySqlDAO();
            $UsuariojackpotGanador = new UsuariojackpotGanador();
            $UsuariojackpotGanador->setJackpotId($JackpotId);
            $UsuariojackpotGanador->setUsujackpotId($UsujackpotId);
            $UsuariojackpotGanador->setUsuarioId($UsuarioId);
            $UsuariojackpotGanador->setValorPremio($ValorActual);
            $UsuariojackpotGanador->setUsucreaId(0);
            $UsuariojackpotGanador->setUsumodifId(0);

            $UsuariojackpotGanadorMySqlDAO->insert($UsuariojackpotGanador);

            $JackpotInternoMySqlDAO = new JackpotInternoMySqlDAO();
            $Transaction = $JackpotInternoMySqlDAO->getTransaction();
            $JackpotInterno = new JackpotInterno($JackpotId);
            $JackpotInterno->setEstado("G");
            $JackpotInternoMySqlDAO->update($JackpotInterno);

            $Usuario = new Usuario($UsuarioId);
            $UsuarioMandante = new UsuarioMandante("",$Usuario->usuarioId,$Usuario->mandante);

            $JackpotInternoMySqlDAO = new JackpotInternoMySqlDAO();
            $BonoLog = new BonoLog();
            $BonoLog->setUsuarioId($UsuarioMandante->usuarioMandante);
            $BonoLog->setTipo('JC');
            $BonoLog->setValor($ValorActual);
            $BonoLog->setFechaCrea(date('Y-m-d H:i:s'));
            $BonoLog->setEstado('L');
            $BonoLog->setErrorId(0);
            $BonoLog->setIdExterno($UsujackpotId);
            $BonoLog->setMandante($UsuarioMandante->mandante);
            $BonoLog->setFechaCierre('');
            $BonoLog->setTransaccionId('');
            $BonoLog->setTipobonoId(4);
            $BonoLog->setTiposaldoId(1);


                $Usuario->creditWin($ValorActual, $Transaction);

            $BonoLogMySqlDAO = new BonoLogMySqlDAO($Transaction);
            $bonologId = $BonoLogMySqlDAO->insert($BonoLog);


            $UsuarioHistorial = new UsuarioHistorial();
            $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
            $UsuarioHistorial->setDescripcion('');
            $UsuarioHistorial->setMovimiento('E');
            $UsuarioHistorial->setUsucreaId(0);
            $UsuarioHistorial->setUsumodifId(0);
            $UsuarioHistorial->setTipo(50);
            $UsuarioHistorial->setValor($ValorActual);
            $UsuarioHistorial->setExternoId($UsujackpotId);


            $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
            $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);

            $Premios = array(
                "jackpotId"=>$JackpotId,
                "description"=>$JackpotInterno->nombre,
                "valor"=>$ValorActual,
                "imagen"=>$JackpotInterno->imagen,
                "imagen2"=>$JackpotInterno->imagen2,
                "gif"=>$JackpotInterno->gif,
                "video"=>$JackpotInterno->gif2,
                "videoMobile"=>$JackpotInterno->videoMobile,
            );


            $UsuarioJackpotMySqlDAO = new UsuarioJackpotMySqlDAO($Transaction);
            $UsuarioMensaje = new UsuarioMensaje();
            $UsuarioMensaje->usufromId = 0;
            $UsuarioMensaje->usutoId = $UsuarioMandante->getUsumandanteId();
            $UsuarioMensaje->isRead = 0;
            $UsuarioMensaje->body = '¡ Bien :thumbsup: ! Has ganado el ' . $NombreJackpot  .' Con un valor de ' . $ValorActual . '  ' . $UsuarioMandante->moneda .' :clap:';
            $UsuarioMensaje->msubject = 'Felicidades eres el ganador';
            $UsuarioMensaje->parentId = 0;
            $UsuarioMensaje->proveedorId = 0;
            $UsuarioMensaje->usucreaId = 0;
            $UsuarioMensaje->usumodifId = 0;
            $UsuarioMensaje->tipo = "JACKPOTWIN";
            $UsuarioMensaje->paisId = $UsuarioMandante->paisId;
            $UsuarioMensaje->fechaExpiracion = '';
            $UsuarioMensaje->valor1 = json_encode($Premios);

            $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($Transaction);
            $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);


            $Transaction->commit();

        }


    }
}catch (Exception $e){
    print_r($e);
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

    $JackpotInternoMySqlDAO = new TorneoInternoMySqlDAO($transaccion);
    $return = $JackpotInternoMySqlDAO->querySQL($sql);
    $return = json_decode(json_encode($return), FALSE);

    return $return;

}


