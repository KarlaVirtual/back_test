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


//date_default_timezone_set('America/Lima');
date_default_timezone_set('America/Bogota');
require_once __DIR__ . '../../vendor/autoload.php';
///home/devadmin/api/api/

ini_set('memory_limit', '-1');
header('Content-Type: application/json');
try {

    $date1 =date('Y-m-d 00:00:00');

    $date2 =date('Y-m-d 23:59:59');

    $server = 'sftp://ds.gaming-curacao.com';
    $usuario = 'vsnetsolnv';
    $pass = '!jHEUNeys8bZ';



    $sql=
        "SELECT usuario.mandante,
       usuario.pais_id,
       usuario_historial.tipo,
       SUM(usuario_historial.valor) sum,
       usuario.moneda
FROM usuario_historial
         INNER JOIN usuario ON (usuario.usuario_id = usuario_historial.usuario_id)
         INNER JOIN pais ON (usuario.pais_id = pais.pais_id)
         INNER JOIN usuario_perfil 
                    ON (usuario_perfil.usuario_id = usuario.usuario_id)
WHERE usuario_historial.fecha_crea >= '2022-04-01 00:00:00'
  AND usuario_historial.fecha_crea <= '2022-04-01 23:59:59'
group by usuario.mandante, usuario.pais_id, usuario_historial.tipo
" ;

    $BonoInterno = new BonoInterno();
    $UsuarioHistorial= new \Backend\dto\UsuarioHistorial();

    $UsuarioHistorialMySqlDAO = new \Backend\mysql\UsuarioHistorialMySqlDAO();

    $transaccion = $UsuarioHistorialMySqlDAO->getTransaction();
    $transaccion->getConnection()->beginTransaction();
    $Usuarios = $BonoInterno->execQuery($transaccion, $sql);

    $Usuarios = (json_encode($Usuarios));

    print_r($Usuarios);

}catch (Exception $e){
print_r($e);
}
