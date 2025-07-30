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
use Backend\dto\Clasificador;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\ItTicketEnc;
use Backend\dto\MandanteDetalle;
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
    $message = "*CRON: (cronPuntosLealtad) * " . " - Fecha: " . date("Y-m-d H:i:s");

    $BonoInterno = new BonoInterno();

    $sqlProcesoInterno2 = "

SELECT * FROM proceso_interno2 WHERE tipo='CASHBACKPREMIOS'
";


    $data = $BonoInterno->execQuery('', $sqlProcesoInterno2);
    $data = $data[0];
    $line = $data->{'proceso_interno2.fecha_ultima'};

    if ($line == '') {
        exit();
    }


    $fechaL1 = date('Y-m-d H:i:00', strtotime($line . '+1 minute'));
    $fechaL2 = date('Y-m-d H:i:59', strtotime($line . '+1 minute'));


    if (false) {


        $file = "/home/home2/datePuntosLealtad.txt";
        $data = file($file);
        $line = $data[oldCount($data) - 1];

        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $fechaL1 = date('Y-m-d H:i:00', strtotime($line . '+1 minute'));
        $fechaL2 = date('Y-m-d H:i:59', strtotime($line . '+1 minute'));
        if ($line == '') {
            $fechaL1 = date('Y-m-d H:i:00', strtotime('-1 minute'));
            $fechaL2 = date('Y-m-d H:i:59');
        }


        if ($fechaL1 > '2022-11-02 19:29:00') {
            exit();
        }
// The new person to add to the file
        $person = $fechaL1 . "\n";

// Write the contents to the file,
// using the FILE_APPEND flag to append the content to the end of the file
// and the LOCK_EX flag to prevent anyone else writing to the file at the same time
        file_put_contents($file, $person, FILE_APPEND | LOCK_EX);


    }

    if ($fechaL1 >= date('Y-m-d H:i:00')) {
        exit();
    }

    if (date('H:i:s') >= '00:00:00' && date('H:i:s') <= '00:10:00') {
        sleep(300);
    }
    $BonoInterno = new BonoInterno();
    $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
    $transaccion = $BonoDetalleMySqlDAO->getTransaction();

    $sqlProcesoInterno2 = "
UPDATE proceso_interno2 SET fecha_ultima='" . $fechaL1 . "' WHERE  tipo='CASHBACKPREMIOS';
";


    $data = $BonoInterno->execQuery($transaccion, $sqlProcesoInterno2);
    $transaccion->commit();

    $message = '*Corriendo Lealtad2*: ' . $fechaL1;
#exec("php -f ".__DIR__."../src/imports/Slack/message.php '".$message."' '#virtualsoft-cron' > /dev/null & ");

    $ActivateLealtad = true;

    $ActivacionSleepTime = true;


    $BonoInterno = new BonoInterno();


    /*DEPORTIVAS*/

    if ($ActivateLealtad) {
        $BonoInterno = new BonoInterno();
        $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
        $transaccion = $BonoDetalleMySqlDAO->getTransaction();
        $ConfigurationEnvironment = new ConfigurationEnvironment();


        $hour = date('H');
        $minute = date("i");
        if ($hour == '0' && $minute < 5) {
            //sleep(300);
        }


        $sql = "SELECT transjuego_log.transjuegolog_id,transjuego_log.transjuego_id,transjuego_info.descripcion
FROM transjuego_log
         INNER JOIN transjuego_info on transjuego_info.transjuego_id = transjuego_log.transjuego_id

WHERE transjuego_log.tipo LIKE '%CREDIT%'
  AND transjuego_log.tipo NOT LIKE '%ROLLBACK%'
  AND transjuego_info.tipo = 'CASHBACKDEBIT'
  AND transjuego_log.fecha_crea >= '" . $fechaL1 . "'
  AND transjuego_log.fecha_crea < '" . $fechaL2 . "'
    
";
        print_r($sql);
        $data = $BonoInterno->execQuery($transaccion, $sql);


        foreach ($data as $datum) {
            print_r($datum);

            $Transjuegolog = new TransjuegoLog($datum->{'transjuego_log.transjuegolog_id'});


            $sql = "UPDATE usuario_bono set premio=premio+'" . $Transjuegolog->getValor() . "' where estado='P' AND  usubono_id='" . $datum->{'transjuego_info.descripcion'} . "';";

            $BonoInterno->execQuery($transaccion, $sql);

        }


        $transaccion->commit();
    }



    print_r('PROCCESS OK');
    sleep(3);

}

