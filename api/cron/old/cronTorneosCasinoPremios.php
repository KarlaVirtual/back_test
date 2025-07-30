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
use Backend\mysql\PreUsuarioSorteoMySqlDAO;
use Backend\mysql\SorteoDetalleMySqlDAO;
use Backend\mysql\SorteoInternoMySqlDAO;
use Backend\mysql\TorneoDetalleMySqlDAO;
use Backend\mysql\TorneoInternoMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransjuegoLogMySqlDAO;
use Backend\mysql\UsuarioBonoMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
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
$message = "*CRON: (cronSorteos) * " . " - Fecha: " . date("Y-m-d H:i:s");


ini_set("display_errors", "off");

$hour = date('H');
if(intval($hour)==0){
    sleep(900);
}

$BonoInterno = new BonoInterno();

$sqlProcesoInterno2 = "

SELECT * FROM proceso_interno2 WHERE tipo='TORNEO'
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
UPDATE proceso_interno2 SET fecha_ultima='".$fechaL1."' WHERE  tipo='TORNEOPREMIOS';

";

    $data = $BonoInterno->execQuery($transaccion, $sqlProcesoInterno2);
    $transaccion->commit();
}

$message ='*Corriendo Torneos Casino2*: '.$fechaL1;
#exec("php -f /home/home2/backendprodfinal/api/src/imports/Slack/message.php '".$message."' '#virtualsoft-cron' > /dev/null & ");


$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . "Inicia: " . date('Y-m-d H:i:s');
$fp = fopen(__DIR__ . '/logs/Slog_' . date("Y-m-d") . '.log', 'a');
//fwrite($fp, $log);
//fclose($fp);


$sqlApuestasDeportivasUsuarioDiaCierre = "

SELECT transjuego_log.valor,transjuego_info.descripcion
FROM transjuego_log

         inner join transjuego_log transjuego_log2 on transjuego_log2.transjuego_id = transjuego_log.transjuego_id
         inner join transjuego_info on transjuego_info.transapi_id = transjuego_log2.transjuegolog_id
where transjuego_log.tipo LIKE 'CREDIT%'
  and transjuego_log.fecha_crea >= '2023-05-18 20:00:00'
  and transjuego_log.fecha_crea < '2023-05-18 21:00:00'
  and transjuego_info.tipo = 'TORNEO'
  
  ";

$dataUsuario = $BonoInterno->execQuery('', $sqlApuestasDeportivasUsuarioDiaCierre);

if ($dataUsuario !== NULL && $dataUsuario !== "" && $dataUsuario[0] !== NULL) {

    foreach ($dataUsuario as $key4 => $datanum) {

        $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
        $transaccion = $BonoDetalleMySqlDAO->getTransaction();

        $UPDATEusuario_torneo = "
UPDATE usuario_torneo SET valor_premio=valor_premio+ '".$datanum->{"transjuego_log.valor"}."' WHERE usutorneo_id = '".$datanum->{"transjuego_info.descripcion"}."' 
";

        $data = $BonoInterno->execQuery($transaccion, $UPDATEusuario_torneo);
        $transaccion->commit();
    }
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


