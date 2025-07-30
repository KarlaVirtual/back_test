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


require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__.'/cronSegundosCron.php');

ini_set('max_execution_time', 0);
ini_set('memory_limit', '-1');
header('Content-Type: application/json');

function runLealtadDeportivas($fechaL1, $fechaL2, $Mandante, $PaisId, $ActivateLealtad, Clasificador $Clasificador1)
{
    print_r('runLealtadDeportivas ' .$fechaL1.' '.$fechaL2. 'Partner ' .$Mandante. ' ' . 'Country '.$PaisId . ' ' . 'ActivateLealtad '.$ActivateLealtad . ' ' . 'Clasificador1 '.$Clasificador1->getClasificadorId() );

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

            $Clasificador = new Clasificador("", "LOYALTYBASEVALUE");
            $MandanteDetalle = new MandanteDetalle("", $Mandante, $Clasificador->getClasificadorId(), $PaisId, 'A');
            $valor_LOYALTYBASEVALUE = floatval($MandanteDetalle->valor);

            $Clasificador = new Clasificador("", "LOYALTYBETTINGSPORTSSIMPLE");
            $MandanteDetalle = new MandanteDetalle("", $Mandante, $Clasificador->getClasificadorId(), $PaisId, 'A');
            $valor_LOYALTYBETTINGSPORTSSIMPLE = floatval($MandanteDetalle->valor);

            $Clasificador = new Clasificador("", "LOYALTYBETTINGSPORTSCOMBINEDTWO");
            $MandanteDetalle = new MandanteDetalle("", $Mandante, $Clasificador->getClasificadorId(), $PaisId, 'A');
            $valor_LOYALTYBETTINGSPORTSCOMBINEDTWO = floatval($MandanteDetalle->valor);

            $Clasificador = new Clasificador("", "LOYALTYBETTINGSPORTSCOMBINEDTHREE");
            $MandanteDetalle = new MandanteDetalle("", $Mandante, $Clasificador->getClasificadorId(), $PaisId, 'A');
            $valor_LOYALTYBETTINGSPORTSCOMBINEDTHREE = floatval($MandanteDetalle->valor);

            $Clasificador = new Clasificador("", "LOYALTYBETTINGSPORTSCOMBINEDFOUR");
            $MandanteDetalle = new MandanteDetalle("", $Mandante, $Clasificador->getClasificadorId(), $PaisId, 'A');
            $valor_LOYALTYBETTINGSPORTSCOMBINEDFOUR = floatval($MandanteDetalle->valor);

            $Clasificador = new Clasificador("", "LOYALTYBETTINGSPORTSCOMBINEDFIVE");
            $MandanteDetalle = new MandanteDetalle("", $Mandante, $Clasificador->getClasificadorId(), $PaisId, 'A');
            $valor_LOYALTYBETTINGSPORTSCOMBINEDFIVE = floatval($MandanteDetalle->valor);

            $Clasificador = new Clasificador("", "LOYALTYBETTINGSPORTSCOMBINEDSIX");
            $MandanteDetalle = new MandanteDetalle("", $Mandante, $Clasificador->getClasificadorId(), $PaisId, 'A');
            $valor_LOYALTYBETTINGSPORTSCOMBINEDSIX = floatval($MandanteDetalle->valor);

            $Clasificador = new Clasificador("", "LOYALTYBETTINGSPORTSCOMBINEDSEVEN");
            $MandanteDetalle = new MandanteDetalle("", $Mandante, $Clasificador->getClasificadorId(), $PaisId, 'A');
            $valor_LOYALTYBETTINGSPORTSCOMBINEDSEVEN = floatval($MandanteDetalle->valor);

            $Clasificador = new Clasificador("", "LOYALTYBETTINGSPORTSCOMBINEDEIGHT");
            $MandanteDetalle = new MandanteDetalle("", $Mandante, $Clasificador->getClasificadorId(), $PaisId, 'A');
            $valor_LOYALTYBETTINGSPORTSCOMBINEDEIGHT = floatval($MandanteDetalle->valor);

            $Clasificador = new Clasificador("", "LOYALTYBETTINGSPORTSCOMBINEDNINE");
            $MandanteDetalle = new MandanteDetalle("", $Mandante, $Clasificador->getClasificadorId(), $PaisId, 'A');
            $valor_LOYALTYBETTINGSPORTSCOMBINEDNINE = floatval($MandanteDetalle->valor);

            $Clasificador = new Clasificador("", "LOYALTYBETTINGSPORTSCOMBINEDTEN");
            $MandanteDetalle = new MandanteDetalle("", $Mandante, $Clasificador->getClasificadorId(), $PaisId, 'A');
            $valor_LOYALTYBETTINGSPORTSCOMBINEDTEN = floatval($MandanteDetalle->valor);


            $sql = "
        SELECT usuario.usuario_id,
       'E' u2,
       2000008 u3,
       SUM(
           CASE
               WHEN it_ticket_enc.cant_lineas = 1 THEN
           FLOOR(((it_ticket_enc.vlr_apuesta) * " . $valor_LOYALTYBASEVALUE . ") / " . $valor_LOYALTYBETTINGSPORTSSIMPLE . ")
               WHEN it_ticket_enc.cant_lineas = 2 THEN
           FLOOR(((it_ticket_enc.vlr_apuesta) * " . $valor_LOYALTYBASEVALUE . ") / " . $valor_LOYALTYBETTINGSPORTSCOMBINEDTWO . ")
               WHEN it_ticket_enc.cant_lineas = 3 THEN
           FLOOR(((it_ticket_enc.vlr_apuesta) * " . $valor_LOYALTYBASEVALUE . ") / " . $valor_LOYALTYBETTINGSPORTSCOMBINEDTHREE . ")
               WHEN it_ticket_enc.cant_lineas = 4 THEN
           FLOOR(((it_ticket_enc.vlr_apuesta) * " . $valor_LOYALTYBASEVALUE . ") / " . $valor_LOYALTYBETTINGSPORTSCOMBINEDFOUR . ")
               WHEN it_ticket_enc.cant_lineas = 5 THEN
           FLOOR(((it_ticket_enc.vlr_apuesta) * " . $valor_LOYALTYBASEVALUE . ") / " . $valor_LOYALTYBETTINGSPORTSCOMBINEDFIVE . ")
               WHEN it_ticket_enc.cant_lineas = 6 THEN
           FLOOR(((it_ticket_enc.vlr_apuesta) * " . $valor_LOYALTYBASEVALUE . ") / " . $valor_LOYALTYBETTINGSPORTSCOMBINEDSIX . ")
               WHEN it_ticket_enc.cant_lineas = 7 THEN
           FLOOR(((it_ticket_enc.vlr_apuesta) * " . $valor_LOYALTYBASEVALUE . ") / " . $valor_LOYALTYBETTINGSPORTSCOMBINEDSEVEN . ")
               WHEN it_ticket_enc.cant_lineas = 8 THEN
           FLOOR(((it_ticket_enc.vlr_apuesta) * " . $valor_LOYALTYBASEVALUE . ") / " . $valor_LOYALTYBETTINGSPORTSCOMBINEDEIGHT . ")
               WHEN it_ticket_enc.cant_lineas = 9 THEN
           FLOOR(((it_ticket_enc.vlr_apuesta) * " . $valor_LOYALTYBASEVALUE . ") / " . $valor_LOYALTYBETTINGSPORTSCOMBINEDNINE . ")
               ELSE
           FLOOR(((it_ticket_enc.vlr_apuesta) * " . $valor_LOYALTYBASEVALUE . ") / " . $valor_LOYALTYBETTINGSPORTSCOMBINEDTEN . ")


           END) u4,
       '' u5,
       it_ticket_enc.ticket_id,
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
from it_ticket_enc
         inner join usuario on usuario.usuario_id = it_ticket_enc.usuario_id
         inner join usuario_perfil on usuario.usuario_id = usuario_perfil.usuario_id

    WHERE (it_ticket_enc.fecha_cierre_time) >= '" . $fechaL1 . "' AND (it_ticket_enc.fecha_cierre_time) <= '" . $fechaL2 . "' 
    AND  it_ticket_enc.eliminado='N'  AND usuario_perfil.perfil_id='USUONLINE'
    AND usuario.mandante  IN ('" . $Mandante . "') 
    AND usuario.pais_id  IN ('" . $PaisId . "') 
    AND it_ticket_enc.bet_status NOT IN ('T')
GROUP BY it_ticket_enc.ticket_id
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

                array_push($arrayUsuarios, $usuarioPLealtad->{'usuario.usuario_id'});
                try {
                    $sql = " INSERT INTO lealtad_historial (usuario_id, movimiento, tipo, valor, descripcion, externo_id, usucrea_id,
                               usumodif_id, creditos, creditos_base, saldo_casino_bonos,
                               saldo_bonos_liberados, fecha_exp,fecha_exptime,mandante,pais_id)
VALUES (
        '" . $usuarioPLealtad->{'usuario.usuario_id'} . "',
        '" . $usuarioPLealtad->{'.u2'} . "',
        '" . $usuarioPLealtad->{'.u3'} . "',
        '" . $usuarioPLealtad->{'.u4'} . "',
        '" . $usuarioPLealtad->{'.u5'} . "',
        '" . $usuarioPLealtad->{'it_ticket_enc.ticket_id'} . "',
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

            $arrayUsuariosEnTabla = array();
            $usuariosPLealtad = $BonoInterno->execQuery($transaccion, $sql);
            $transaccion->commit();
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
                              WHERE tipo = '2000008' 
                              GROUP BY usuario_id ";

            $usuariosPLealtad = $BonoInterno->execQuery($transaccion, $sql);


            foreach ($usuariosPLealtad as $usuarioPLealtad) {

                $sql = "
UPDATE usuario_puntoslealtad SET puntos_lealtad = puntos_lealtad + '" . $usuarioPLealtad->{'.valor'} . "'
WHERE usuario_id='" . $usuarioPLealtad->{'lealtad_historial.usuario_id'} . "'; ";

                $BonoInterno->execQuery($transaccion, $sql);
                $transaccion->commit();
            }


            $sql = "UPDATE lealtad_historial
                     SET tipo = '20'
WHERE tipo = '2000008' ";
            $BonoInterno->execQuery($transaccion, $sql);


            $transaccion->commit();

        }

    } catch (Exception $e) {
        print_r($e);

    }
    print_r('runLealtadDeportivas OK'  );

}


for($i=0;$i<10;$i++) {

    $message = "*CRON: (cronPuntosLealtad) * " . " - Fecha: " . date("Y-m-d H:i:s");

    $filename = __DIR__ . '/lastruncronPuntosLealtadEcuabet';

    $BonoInterno = new BonoInterno();

    $sqlProcesoInterno2 = "

SELECT * FROM proceso_interno2 WHERE tipo='LEALTAD2'
";


    $data = $BonoInterno->execQuery('', $sqlProcesoInterno2);
    $data = $data[0];
    $line = $data->{'proceso_interno2.fecha_ultima'};

    if ($line == '') {
        exit();
    }


    $fechaL1 = date('Y-m-d H:i:00', strtotime($line . '+1 minute'));
    $fechaL2 = date('Y-m-d H:i:59', strtotime($line . '+5 minute'));


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

    $message = '*cronPuntosLealtadEcuabet:* ' . date('Y-m-d H:i:s');

    //exec("php -f /home/home2/backendprodfinal/api/src/imports/Slack/message.php '" . $message . "' '#dev2' > /dev/null & ");


    if (date('H:i:s') >= '00:00:00' && date('H:i:s') <= '00:10:00') {
        sleep(300);
    }
    print_r('Fecha Inicio: '.$fechaL1.' - Fecha Fin: '.$fechaL2);


    $message = '*Corriendo Lealtad2:5* ' . $fechaL1;
#exec("php -f /home/home2/backendprodfinal/api/src/imports/Slack/message.php '".$message."' '#virtualsoft-cron' > /dev/null & ");

    $ActivateLealtad = true;

    $ActivacionSleepTime = true;


    $BonoInterno = new BonoInterno();


    $Clasificador1 = new Clasificador("", "LOYALTYEXPIRATIONDATE");

    runLealtadDeportivas($fechaL1, $fechaL2, 8, 66, $ActivateLealtad, $Clasificador1);


    $BonoInterno = new BonoInterno();
    $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
    $transaccion = $BonoDetalleMySqlDAO->getTransaction();

    $sqlProcesoInterno2 = "
UPDATE proceso_interno2 SET fecha_ultima='" . $fechaL2 . "' WHERE  tipo='LEALTAD2';
";


    $data = $BonoInterno->execQuery($transaccion, $sqlProcesoInterno2);
    $transaccion->commit();
    unlink($filename);

    print_r('PROCCESS OK');

    sleep(3);

}

