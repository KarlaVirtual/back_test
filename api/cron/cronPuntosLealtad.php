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
use Backend\sql\ConnectionProperty;


error_reporting(E_ALL);
ini_set('display_errors', 'ON');

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/cronSegundosCron.php');

ini_set('max_execution_time', 0);
ini_set('memory_limit', '-1');
header('Content-Type: application/json');

/* asigna valores a variables según la conexión y parámetros existentes. */
$_ENV["enabledConnectionGlobal"] = 1;

function runLealtad($fechaL1, $fechaL2, $Mandante, $PaisId, $ActivateLealtad, Clasificador $Clasificador1, $Clasificador2, $Clasificador3, $tipo)
{
    print_r('runLealtad ' .$fechaL1.' '.$fechaL2. 'Partner ' .$Mandante. ' ' . 'Country '.$PaisId . ' ' . 'ActivateLealtad '.$ActivateLealtad . ' ' . 'Clasificador1 '.$Clasificador1->getClasificadorId() . ' Clasificador2' . $Clasificador2->getClasificadorId().' Clasificador3'.$Clasificador3->getClasificadorId().' '.'Type '.$tipo);
    try {
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


            $Clasificador = $Clasificador1;
            $MandanteDetalle = new MandanteDetalle("", $Mandante, $Clasificador->getClasificadorId(), $PaisId, 'A');
            $diasExpiracion = $MandanteDetalle->valor;

            $Clasificador = $Clasificador2;
            $MandanteDetalle = new MandanteDetalle("", $Mandante, $Clasificador->getClasificadorId(), $PaisId, 'A');
            $valor_LOYALTYBASEVALUE = floatval($MandanteDetalle->valor);

            $Clasificador = $Clasificador3;
            $MandanteDetalle = new MandanteDetalle("", $Mandante, $Clasificador->getClasificadorId(), $PaisId, 'A');
            $valor_LOYALTYBETCASINO = floatval($MandanteDetalle->valor);

            $tipoValor = '3000000';
            $tipoValor2 = '30';

            if ($tipo == 'LIVECASINO') {

                $tipoValor = '3100000';
                $tipoValor2 = '31';
            }
            $sqlExclusion='';
            if($tipo == 'CASINO'){
                $sqlExclusion = ' AND producto.categoria_id NOT IN (1197,1206) ';

            }

            $sql = "
    SELECT usuario_mandante.usuario_mandante,
       'E' u2,
       " . $tipoValor . " u3,
       SUM(FLOOR(((transjuego_log.valor) * " . $valor_LOYALTYBASEVALUE . ") / " . $valor_LOYALTYBETCASINO . ")) u4,
       '' u5,
       transjuego_log.transjuegolog_id u6,
       0 u7,
       0 u8,
       0 u9,
       0 u10,
       0 u11,
       0 u12,
       '" . date("Y-m-d H:i:s", strtotime("+" . $diasExpiracion . " days")) . "' u13,
       '" . strtotime(date("Y-m-d H:i:s", strtotime("+" . $diasExpiracion . " days"))) . "' u14,
       '" . $Mandante . "' u15,
       '" . $PaisId . "' u16
from transjuego_log
         inner join transaccion_juego on transjuego_log.transjuego_id = transaccion_juego.transjuego_id
         inner join producto_mandante on transaccion_juego.producto_id = producto_mandante.prodmandante_id
         inner join producto on producto.producto_id = producto_mandante.producto_id
         inner join subproveedor on producto.subproveedor_id = subproveedor.subproveedor_id
         inner join usuario_mandante on transaccion_juego.usuario_id = usuario_mandante.usumandante_id

where transjuego_log.fecha_crea >= '" . $fechaL1 . "'
  and transjuego_log.fecha_crea <= '" . $fechaL2 . "'
    and 
    producto.producto_id not in (26309,15406,13470,13259,13296,23864,22238,21016,22416,26222,23530,13800,17371,20995,18797,17813,21061,13290,22911,24223,17309,22908,13476,17819,21993,21996,21999,25924,21990,22002,21526,23527,16749,25955,18800,26103,17324,17371,22941,18791,15747,17792,22750,22113,22005,21520,24208,22110,18794,26100,21789,23906,17810,22872,13293,16259,26160,19165,24211,13293,18797,16259,17813,21295,15406,17180,24211)
  and transjuego_log.tipo like 'DEBIT%'
      AND usuario_mandante.mandante  IN ('" . $Mandante . "') 
    AND usuario_mandante.pais_id  IN ('" . $PaisId . "') 

  and subproveedor.tipo = '" . $tipo . "' ".$sqlExclusion."
   AND transjuego_log.valor >= " . $valor_LOYALTYBETCASINO . "
GROUP BY transjuego_log.transjuegolog_id
";


            if ($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null) {

                /* Conecta a la base de datos usando la configuración almacenada en el entorno. */
                $connOriginal = $_ENV["connectionGlobal"]->getConnection();


                try {


                    /* Conexión a la base de datos en producción utilizando PDO y SSL. */
                    $connDB5 = null;

                    if ($_ENV['ENV_TYPE'] == 'prod') {

                        $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()
                            , array(
                                PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-bundle.crt',
                                PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
                            )
                        );
                    } else {
                        /* Conecta a una base de datos MySQL usando PDO y variables de entorno. */


                        $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()

                        );
                    }


                    /* Configura la conexión a la base de datos con codificación UTF-8 y zona horaria. */
                    $connDB5->exec("set names utf8");


                    if ($_ENV["TIMEZONE"] != null && $_ENV["TIMEZONE"] != '') {
                        $connDB5->exec('SET time_zone = "' . $_ENV["TIMEZONE"] . '";');
                    }


                    /* Configura el nivel de aislamiento y tiempo de espera de bloqueo en una conexión a base de datos. */
                    if ($_ENV["NEEDINSOLATIONLEVEL"] != null && $_ENV["NEEDINSOLATIONLEVEL"] == '1') {
                        $connDB5->exec('SET TRANSACTION ISOLATION LEVEL READ COMMITTED;');
                    }
                    if ($_ENV["ENABLEDSETLOCKWAITTIMEOUT"] != null && $_ENV["ENABLEDSETLOCKWAITTIMEOUT"] == '1') {
                        // $connDB5->exec('SET SESSION innodb_lock_wait_timeout = 5;');
                    }

                    /* configura tiempo de ejecución y codificación UTF-8 en la base de datos. */
                    if ($_ENV["ENABLEDSETMAX_EXECUTION_TIME"] != null && $_ENV["ENABLEDSETMAX_EXECUTION_TIME"] == '1') {
                        // $connDB5->exec('SET SESSION MAX_EXECUTION_TIME = 120000;');
                    }
                    if ($_ENV["DBNEEDUTF8"] != null && $_ENV["DBNEEDUTF8"] == '1') {
                        $connDB5->exec("SET NAMES utf8mb4");
                    }

                    /* Establece una conexión a la base de datos utilizando una variable de entorno. */
                    $_ENV["connectionGlobal"]->setConnection($connDB5);

                } catch (\Exception $e) {
                    /* captura excepciones en PHP, evitando interrupciones en la ejecución. */


                }

            }

            $usuariosPLealtad = $BonoInterno->execQuery($transaccion, $sql);

            if ($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null) {
                $connDB5 = null;
                $_ENV["connectionGlobal"]->setConnection($connOriginal);
            }

            $arrayUsuarios = array();
            foreach ($usuariosPLealtad as $usuarioPLealtad) {

                array_push($arrayUsuarios, $usuarioPLealtad->{'usuario_mandante.usuario_mandante'});
                try {
                    $sql = " INSERT INTO lealtad_historial (usuario_id, movimiento, tipo, valor, descripcion, externo_id, usucrea_id,
                               usumodif_id, creditos, creditos_base, saldo_casino_bonos,
                               saldo_bonos_liberados, fecha_exp,fecha_exptime,mandante,pais_id)
VALUES (
        '" . $usuarioPLealtad->{'usuario_mandante.usuario_mandante'} . "',
        '" . $usuarioPLealtad->{'.u2'} . "',
        '" . $usuarioPLealtad->{'.u3'} . "',
        '" . $usuarioPLealtad->{'.u4'} . "',
        '" . $usuarioPLealtad->{'.u5'} . "',
        '" . $usuarioPLealtad->{'transjuego_log.u6'} . "',
        '" . $usuarioPLealtad->{'.u7'} . "',
        '" . $usuarioPLealtad->{'.u8'} . "',
        '" . $usuarioPLealtad->{'.u9'} . "',
        '" . $usuarioPLealtad->{'.u10'} . "',
        '" . $usuarioPLealtad->{'.u11'} . "',
        '" . $usuarioPLealtad->{'.u12'} . "',
        '" . $usuarioPLealtad->{'.u13'} . "',
        '" . $usuarioPLealtad->{'.u14'} . "',
        '" . $usuarioPLealtad->{'.u15'} . "',
        '" . $usuarioPLealtad->{'.u16'} . "'
        
        
        
        
        )";

                    $BonoInterno->execQuery($transaccion, $sql);
                    $transaccion->commit();

                } catch (Exception $e) {

                }
            }

            if (count($arrayUsuarios) == 0) {
                return;
            }


            $sql = "
select usuario_id from usuario_puntoslealtad
where usuario_id in (" . implode(',', $arrayUsuarios) . ");
  ";
            print_r($sql);

            $arrayUsuariosEnTabla = array();
            $usuariosPLealtad = $BonoInterno->execQuery($transaccion, $sql);
            foreach ($usuariosPLealtad as $usuarioPLealtad) {
                array_push($arrayUsuariosEnTabla, $usuarioPLealtad->{'usuario_puntoslealtad.usuario_id'});
            }

            $arrayUsuarios = array_diff($arrayUsuarios, $arrayUsuariosEnTabla);
            sort($arrayUsuarios);


            foreach ($arrayUsuarios as $arrayUsuario) {

                try {
                    $sql = "INSERT INTO usuario_puntoslealtad (usuario_id, puntos_lealtad, nivel_lealtad, puntos_aexpirar,
                                   usucrea_id, usumodif_id, puntos_expirados, puntos_redimidos)
VALUES ('" . $arrayUsuario . "',0,0,0,0,0,0,0)";

                    $BonoInterno->execQuery($transaccion, $sql);
                    $transaccion->commit();
                } catch (Exception $e) {
                    print_r($e);
                }
            }


            $sql = " SELECT usuario_id, SUM(valor) valor
                              FROM lealtad_historial
                              WHERE tipo = '" . $tipoValor . "' 
                              GROUP BY usuario_id ";

            $usuariosPLealtad = $BonoInterno->execQuery($transaccion, $sql);
            print_r(PHP_EOL);
            print_r(PHP_EOL);
            print_r(' SQL ');
            print_r(PHP_EOL);
            print_r(PHP_EOL);
            print_r($sql);

            foreach ($usuariosPLealtad as $usuarioPLealtad) {

                $sql = "
UPDATE usuario_puntoslealtad SET puntos_lealtad = puntos_lealtad + '" . $usuarioPLealtad->{'.valor'} . "'
WHERE usuario_id='" . $usuarioPLealtad->{'lealtad_historial.usuario_id'} . "'; ";

                $BonoInterno->execQuery($transaccion, $sql);
                $transaccion->commit();
            }


            $sql = "UPDATE lealtad_historial

SET tipo = '" . $tipoValor2 . "'
WHERE tipo = '" . $tipoValor . "' ";
            $BonoInterno->execQuery($transaccion, $sql);
            print_r($sql);


            $transaccion->commit();

        }
    } catch (Exception $e) {
        print_r($e);

    }
    print_r('runLealtad OK ');

}

for ($i = 0; $i < 10; $i++) {

    $message = "*CRON: (cronPuntosLealtad) * " . " - Fecha: " . date("Y-m-d H:i:s");

    $filename = __DIR__ . '/lastruncronPuntosLealtad';

    $BonoInterno = new BonoInterno();

    $sqlProcesoInterno2 = "

SELECT * FROM proceso_interno2 WHERE tipo='LEALTAD'
";


    $data = $BonoInterno->execQuery('', $sqlProcesoInterno2);
    $data = $data[0];
    $line = $data->{'proceso_interno2.fecha_ultima'};

    if ($line == '') {
        exit();
    }


    $fechaL1 = date('Y-m-d H:i:00', strtotime($line . '+1 minute'));
    $fechaL2 = date('Y-m-d H:i:59', strtotime($line . '+2 minute'));


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

    if ($fechaL2 >= date('Y-m-d H:i:00')) {
        unlink($filename);
        exit();
    }


    $datefilename = date("Y-m-d H:i:s", filemtime($filename));

    if ($datefilename <= date("Y-m-d H:i:s", strtotime('-12 hour'))) {
        unlink($filename);

    }
    if (file_exists($filename)) {
        //throw new Exception("There is a process currently running", "1");
        exit();
    }
    file_put_contents($filename, 'RUN');


    if (date('H:i:s') >= '00:00:00' && date('H:i:s') <= '00:10:00') {
        sleep(300);
    }
    print_r('Fecha Inicio: ' . $fechaL1 . ' - Fecha Fin: ' . $fechaL2);


    $message = '*Corriendo Lealtad*: ' . $fechaL1;
#exec("php -f /home/home2/backendprodfinal/api/src/imports/Slack/message.php '".$message."' '#virtualsoft-cron' > /dev/null & ");

    $ActivateLealtad = true;

    $ActivacionSleepTime = true;



    $BonoInterno = new BonoInterno();


    $Clasificador1 = new Clasificador("", "LOYALTYEXPIRATIONDATE");
    $Clasificador2 = new Clasificador("", "LOYALTYBASEVALUE");
    $Clasificador3 = new Clasificador("", "LOYALTYBETCASINO");
    $Clasificador4 = new Clasificador("", "LOYALTYBETCASVIVO");

    $Clasificador5 = new Clasificador("", "LOYALTYBETVIRTUALES");

    runLealtad($fechaL1, $fechaL2, 8, 66, $ActivateLealtad, $Clasificador1, $Clasificador2, $Clasificador3, "CASINO");
    runLealtad($fechaL1, $fechaL2, 0, 173, $ActivateLealtad, $Clasificador1, $Clasificador2, $Clasificador3, "CASINO");
    runLealtad($fechaL1, $fechaL2, 0, 68, $ActivateLealtad, $Clasificador1, $Clasificador2, $Clasificador3, "CASINO");
    runLealtad($fechaL1, $fechaL2, 0, 46, $ActivateLealtad, $Clasificador1, $Clasificador2, $Clasificador3, "CASINO");
    runLealtad($fechaL1, $fechaL2, 0, 66, $ActivateLealtad, $Clasificador1, $Clasificador2, $Clasificador3, "CASINO");
    runLealtad($fechaL1, $fechaL2, 0, 60, $ActivateLealtad, $Clasificador1, $Clasificador2, $Clasificador3, "CASINO");
    runLealtad($fechaL1, $fechaL2, 0, 94, $ActivateLealtad, $Clasificador1, $Clasificador2, $Clasificador3, "CASINO");
    runLealtad($fechaL1, $fechaL2, 23, 102, $ActivateLealtad, $Clasificador1, $Clasificador2, $Clasificador3, "CASINO");
    runLealtad($fechaL1, $fechaL2, 19, 173, $ActivateLealtad, $Clasificador1, $Clasificador2, $Clasificador3, "CASINO");
    runLealtad($fechaL1, $fechaL2, 27, 94, $ActivateLealtad, $Clasificador1, $Clasificador2, $Clasificador3, "CASINO");
    runLealtad($fechaL1, $fechaL2, 27, 68, $ActivateLealtad, $Clasificador1, $Clasificador2, $Clasificador3, "CASINO");
    runLealtad($fechaL1, $fechaL2, 21, 232, $ActivateLealtad, $Clasificador1, $Clasificador2, $Clasificador3, "CASINO");
    runLealtad($fechaL1, $fechaL2, 21, 243, $ActivateLealtad, $Clasificador1, $Clasificador2, $Clasificador3, "CASINO");


    runLealtad($fechaL1, $fechaL2, 8, 66, $ActivateLealtad, $Clasificador1, $Clasificador2, $Clasificador5, "VIRTUAL");
    runLealtad($fechaL1, $fechaL2, 0, 173, $ActivateLealtad, $Clasificador1, $Clasificador2, $Clasificador5, "VIRTUAL");
    runLealtad($fechaL1, $fechaL2, 0, 68, $ActivateLealtad, $Clasificador1, $Clasificador2, $Clasificador5, "VIRTUAL");
    runLealtad($fechaL1, $fechaL2, 0, 46, $ActivateLealtad, $Clasificador1, $Clasificador2, $Clasificador5, "VIRTUAL");
    runLealtad($fechaL1, $fechaL2, 0, 66, $ActivateLealtad, $Clasificador1, $Clasificador2, $Clasificador5, "VIRTUAL");
    runLealtad($fechaL1, $fechaL2, 0, 60, $ActivateLealtad, $Clasificador1, $Clasificador2, $Clasificador5, "VIRTUAL");
    runLealtad($fechaL1, $fechaL2, 0, 94, $ActivateLealtad, $Clasificador1, $Clasificador2, $Clasificador5, "VIRTUAL");
    runLealtad($fechaL1, $fechaL2, 23, 102, $ActivateLealtad, $Clasificador1, $Clasificador2, $Clasificador5, "VIRTUAL");
    runLealtad($fechaL1, $fechaL2, 19, 173, $ActivateLealtad, $Clasificador1, $Clasificador2, $Clasificador5, "VIRTUAL");
    runLealtad($fechaL1, $fechaL2, 27, 94, $ActivateLealtad, $Clasificador1, $Clasificador2, $Clasificador5, "VIRTUAL");
    runLealtad($fechaL1, $fechaL2, 27, 68, $ActivateLealtad, $Clasificador1, $Clasificador2, $Clasificador5, "VIRTUAL");
    runLealtad($fechaL1, $fechaL2, 21, 232, $ActivateLealtad, $Clasificador1, $Clasificador2, $Clasificador5, "VIRTUAL");
    runLealtad($fechaL1, $fechaL2, 21, 243, $ActivateLealtad, $Clasificador1, $Clasificador2, $Clasificador5, "VIRTUAL");


    $Clasificador1 = new Clasificador("", "LOYALTYEXPIRATIONDATE");
    $Clasificador2 = new Clasificador("", "LOYALTYBASEVALUE");
    $Clasificador3 = new Clasificador("", "LOYALTYBETCASINO");
    $Clasificador4 = new Clasificador("", "LOYALTYBETCASVIVO");
    $Clasificador5 = new Clasificador("", "LOYALTYBETVIRTUALES");

    runLealtad($fechaL1, $fechaL2, 8, 66, $ActivateLealtad, $Clasificador1, $Clasificador2, $Clasificador4, "LIVECASINO");
    runLealtad($fechaL1, $fechaL2, 0, 173, $ActivateLealtad, $Clasificador1, $Clasificador2, $Clasificador4, "LIVECASINO");
    runLealtad($fechaL1, $fechaL2, 0, 68, $ActivateLealtad, $Clasificador1, $Clasificador2, $Clasificador4, "LIVECASINO");
    runLealtad($fechaL1, $fechaL2, 0, 46, $ActivateLealtad, $Clasificador1, $Clasificador2, $Clasificador4, "LIVECASINO");
    runLealtad($fechaL1, $fechaL2, 0, 66, $ActivateLealtad, $Clasificador1, $Clasificador2, $Clasificador4, "LIVECASINO");
    runLealtad($fechaL1, $fechaL2, 0, 94, $ActivateLealtad, $Clasificador1, $Clasificador2, $Clasificador4, "LIVECASINO");
    runLealtad($fechaL1, $fechaL2, 23, 102, $ActivateLealtad, $Clasificador1, $Clasificador2, $Clasificador4, "LIVECASINO");
    runLealtad($fechaL1, $fechaL2, 19, 173, $ActivateLealtad, $Clasificador1, $Clasificador2, $Clasificador4, "LIVECASINO");
    runLealtad($fechaL1, $fechaL2, 27, 94, $ActivateLealtad, $Clasificador1, $Clasificador2, $Clasificador4, "LIVECASINO");
    runLealtad($fechaL1, $fechaL2, 27, 68, $ActivateLealtad, $Clasificador1, $Clasificador2, $Clasificador4, "LIVECASINO");
    runLealtad($fechaL1, $fechaL2, 21, 232, $ActivateLealtad, $Clasificador1, $Clasificador2, $Clasificador4, "LIVECASINO");
    runLealtad($fechaL1, $fechaL2, 21, 243, $ActivateLealtad, $Clasificador1, $Clasificador2, $Clasificador4, "LIVECASINO");


    $BonoInterno = new BonoInterno();
    $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
    $transaccion = $BonoDetalleMySqlDAO->getTransaction();

    $sqlProcesoInterno2 = "
UPDATE proceso_interno2 SET fecha_ultima='" . $fechaL2 . "' WHERE  tipo='LEALTAD';
";


    $data = $BonoInterno->execQuery($transaccion, $sqlProcesoInterno2);
    $transaccion->commit();

    unlink($filename);

    print_r('PROCCESS OK');

    sleep(3);

}

