<?php

/**
 * Este archivo contiene un script para obtener y procesar datos de ventas del día anterior
 * desde una base de datos, generando un resultado en formato JSON.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $date1                    Variable que almacena una primera fecha en un proceso.
 * @var mixed $date2                    Variable que almacena una segunda fecha en un proceso.
 * @var mixed $server                   Variable que almacena información del servidor.
 * @var mixed $usuario                  Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $pass                     Variable que almacena una contraseña o clave de acceso.
 * @var mixed $sql                      Variable que almacena una consulta SQL a ejecutar en la base de datos.
 * @var mixed $BonoInterno              Variable que representa un bono interno en el sistema.
 * @var mixed $UsuarioHistorial         Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $UsuarioHistorialMySqlDAO Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $transaccion              Variable que almacena datos relacionados con una transacción.
 * @var mixed $Usuarios                 Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $data                     Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $e                        Esta variable se utiliza para capturar excepciones o errores en bloques try-catch.
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
date_default_timezone_set('America/Bogota');


ini_set('memory_limit', '-1');
header('Content-Type: application/json');
try {
    $date1 = date('Y-m-d 00:00:00', strtotime('-1 day'));

    $date2 = date('Y-m-d 23:59:59', strtotime('-1 day'));

    $server = 'sftp://ds.gaming-curacao.com';
    $usuario = 'vsnetsolnv';
    $pass = '!jHEUNeys8bZ';


    $sql =
        "SELECT usuario.mandante ,
       pais.pais_nom,
       usuario_historial.tipo ,
       SUM(usuario_historial.valor) sum,
       usuario.moneda,date_format(usuario_historial.fecha_crea, '%Y-%m-%d %H:00:00') fecha
FROM usuario_historial
         INNER JOIN usuario ON (usuario.usuario_id = usuario_historial.usuario_id)
         INNER JOIN pais ON (usuario.pais_id = pais.pais_id)
         INNER JOIN usuario_perfil 
                    ON (usuario_perfil.usuario_id = usuario.usuario_id)
WHERE usuario_historial.fecha_crea >= '" . $date1 . "'
  AND usuario_historial.fecha_crea <= '" . $date2 . "'
group by usuario.mandante, usuario.pais_id, usuario_historial.tipo,date_format(usuario_historial.fecha_crea, '%Y-%m-%d %H:00:00')
";

    $BonoInterno = new BonoInterno();
    $UsuarioHistorial = new \Backend\dto\UsuarioHistorial();

    $UsuarioHistorialMySqlDAO = new \Backend\mysql\UsuarioHistorialMySqlDAO();

    $transaccion = $UsuarioHistorialMySqlDAO->getTransaction();
    $transaccion->getConnection()->beginTransaction();
    $Usuarios = $BonoInterno->execQuery($transaccion, $sql);

    $data = array();
    foreach ($Usuarios as $usuario) {
        array_push($data, array(
            'partner' => $usuario->{'usuario.mandante'},
            'country' => $usuario->{'pais.pais_nom'},
            'type' => $usuario->{'usuario_historial.tipo'},
            'date' => $usuario->{'.fecha'},
            'sum' => str_replace('.', ',', $usuario->{'.sum'}),
            'currency' => $usuario->{'usuario.moneda'}
        ));
    }

    $data = (json_encode($data));


    print_r($data);
    exit();
} catch (Exception $e) {
    print_r($e);
}
