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


ini_set('display_errors', 'OFF');

require_once __DIR__ . '../../vendor/autoload.php';
///home/devadmin/api/api/

ini_set('memory_limit', '-1');

$hour = date('H');
if(intval($hour)>9){
    exit();
}
//sleep(300);
$message = "*CRON: (Inicio) * " . " CronExpirados - Fecha: " . date("Y-m-d H:i:s");

exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

$sql=
    "SELECT usuario_puntoslealtad.puntos_aexpirar AS puntosExpirados, usuario_id AS  usuarioId
FROM usuario_puntoslealtad
WHERE puntos_aexpirar > 0
GROUP BY  usuario_id
; " ;

$BonoInterno = new BonoInterno();

$Usuario = new \Backend\dto\Usuario();

$UsuarioMySqlDAO = new \Backend\mysql\UsuarioMySqlDAO();

$transaccion = $UsuarioMySqlDAO->getTransaction();
$transaccion->getConnection()->beginTransaction();
$Usuarios = $BonoInterno->execQuery($transaccion, $sql);

foreach ($Usuarios as $key => $value) {

    $Iduser = strval($value->{"usuario_puntoslealtad.usuarioId"});
    $puntos = strval($value->{"usuario_puntoslealtad.puntosExpirados"});

    if($Iduser != '' && $Iduser != '0') {
        // $Usuario = new \Backend\dto\Usuario($Iduser);

        // $valorPoints = $puntos;
        // $Usuario->creditPointsExpire(intval($valorPoints),$transaccion);
        // $Usuario->debitPointsToExpire(intval($valorPoints),$transaccion);


        $sql = ' UPDATE usuario_puntoslealtad SET 
                    puntos_expirados=puntos_expirados+puntos_aexpirar,
                    puntos_aexpirar=0
            WHERE usuario_id=' . $Iduser;
        print_r($Iduser);
        print_r(PHP_EOL);

        $BonoInterno->execQuery($transaccion, $sql);
        $transaccion->commit();

        $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
        $transaccion = $BonoDetalleMySqlDAO->getTransaction();


        $sql = "INSERT INTO lealtad_historial (usuario_id, movimiento, tipo, valor, descripcion, externo_id, usucrea_id, usumodif_id,
                               fecha_exp, fecha_exptime, mandante, pais_id,creditos)

SELECT usuario_puntoslealtad.usuario_id, 'S', '51', '" . $puntos . "', 'Expirados', 0, 0, 0, null, 0, usuario.mandante, usuario.pais_id,usuario_puntoslealtad.puntos_lealtad
from usuario_puntoslealtad
INNER JOIN usuario ON usuario_puntoslealtad.usuario_id = usuario.usuario_id
where usuario.usuario_id = '" . $Iduser . "'";
        //print_r($sql);

        $BonoInterno->execQuery($transaccion, $sql);
        $transaccion->commit();

        $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
        $transaccion = $BonoDetalleMySqlDAO->getTransaction();
    }
}

$transaccion->commit();
$message = "*CRON: (Fin) * " . " CronExpirados - Fecha: " . date("Y-m-d H:i:s");

exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");



