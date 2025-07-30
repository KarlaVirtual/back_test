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
use Backend\dto\Mandante;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\BonoLogMySqlDAO;
use Backend\mysql\PreUsuarioSorteoMySqlDAO;
use Backend\mysql\SorteoDetalleMySqlDAO;
use Backend\mysql\SorteoInternoMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransjuegoInfoMySqlDAO;
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
$message = "*CRON: (cronSorteos) * " . " - Fecha: " . date("Y-m-d H:i:s");
$ConfigurationEnvironment = new ConfigurationEnvironment();


if (!$ConfigurationEnvironment->isDevelopment()) {
    // $message = "*CRON: (Segundos) * " . " - Fecha: " . date("Y-m-d H:i:s");
    // exec("php -f /home/home2/backendprodfinal/api/src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

}
#14:53
//exec("php -f " . __DIR__ . "/cronSorteosStickers.php > /dev/null ");
//exec("php -f " . __DIR__ . "/cronSorteosStickersCompleted.php > /dev/null ");
exec("php -f " . __DIR__ . "/cronAlertasRiesgoAdmin8.php > /dev/null ");

