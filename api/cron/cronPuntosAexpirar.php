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
use Backend\dto\Usuario;
use Backend\dto\UsuarioMandante;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\TransaccionApi;
use Backend\dto\LealtadInterna;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\UsuarioAutomation;
use Backend\dto\UsuarioHistorial;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransjuegoLogMySqlDAO;
use Backend\mysql\UsuarioAutomationMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;


date_default_timezone_set('America/Lima');
require_once __DIR__ . '../../vendor/autoload.php';
///home/devadmin/api/api/
$message = "*CRON: (Inicio) * " . " CronAExpirar - Fecha: " . date("Y-m-d H:i:s");

exec("php -f " . __DIR__ . "../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");


sleep(300);
ini_set('memory_limit', '-1');
$hour = date('H');
if (intval($hour) > 9) {
    exit();
}
$message = "*CRON: (Inicio) * " . " CronAExpirar - Fecha: " . date("Y-m-d H:i:s");

exec("php -f " . __DIR__ . "../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

$date1 = date('Y-m-d 00:00:00', strtotime('+0 days'));
$date2 = date('Y-m-d 23:59:59', strtotime('+0 days'));
$date3 = date('Y-m-d H:i:s', strtotime('-90 days'));


$sql = "SELECT CASE WHEN SUM(Entradas) >= SUM(Salidas) THEN
       SUM(Entradas) - SUM(Salidas)
    ELSE 0 END AS Puntos, usuarioId
FROM  (SELECT SUM(lealtad_historial.valor) AS Entradas, 0 AS Salidas, (usuario_id) as usuarioId
FROM lealtad_historial
WHERE 1=1
AND (fecha_exp >= '$date1'
AND fecha_exp <= '$date2')
AND movimiento = 'E' GROUP BY usuarioId
UNION
SELECT  0 AS Entradas , SUM(lealtad_historial.valor) AS Salidas, (usuario_id) as usuarioId
FROM lealtad_historial
WHERE 1=1
AND (fecha_crea >= '$date3'
AND fecha_crea <= '$date2')
AND movimiento = 'S'
GROUP BY usuarioId) x
GROUP BY usuarioId";

$BonoInterno = new BonoInterno();


$Usuario = new \Backend\dto\Usuario();

$UsuarioMySqlDAO = new \Backend\mysql\UsuarioMySqlDAO();

$transaccion = $UsuarioMySqlDAO->getTransaction();
$transaccion->getConnection()->beginTransaction();
$Usuarios = $BonoInterno->execQuery($transaccion, $sql);

foreach ($Usuarios as $key => $value) {

    $Iduser = strval($value->{"x.usuarioId"});
    $puntos = strval($value->{".Puntos"});

    $sql = ' UPDATE usuario_puntoslealtad SET 
                    puntos_aexpirar = puntos_aexpirar + ' . $puntos . '
            WHERE usuario_id = ' . $Iduser;

    $BonoInterno->execQuery($transaccion, $sql);

    $transaccion->commit();

    $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
    $transaccion = $BonoDetalleMySqlDAO->getTransaction();
    print_r($sql);

    $sql = ' UPDATE usuario_puntoslealtad SET 
                    puntos_lealtad = CASE WHEN puntos_lealtad - puntos_aexpirar <= 0 THEN 0 ELSE  puntos_lealtad - puntos_aexpirar END
            WHERE usuario_id = ' . $Iduser;
    $BonoInterno->execQuery($transaccion, $sql);

    $transaccion->commit();

    $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
    $transaccion = $BonoDetalleMySqlDAO->getTransaction();
}

$transaccion->commit();





