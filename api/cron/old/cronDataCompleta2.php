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
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\TransaccionApi;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\UsuarioAutomation;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioMandante;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransjuegoLogMySqlDAO;
use Backend\mysql\UsuarioAutomationMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;



require(__DIR__.'/../vendor/autoload.php');
require(__DIR__.'/cronSegundosCron.php');

ini_set('memory_limit', '-1');

for($i=0;$i<10;$i++) {

    $filename=__DIR__.'/lastrunDataCompleta2';
    $argv1 = $argv[1];


    $datefilename = date("Y-m-d H:i:s", filemtime($filename));
    if ($datefilename <= date("Y-m-d H:i:s", strtotime('-12 hour'))) {
        unlink($filename);

    }
    if (file_exists($filename)) {
        throw new Exception("There is a process currently running", "1");
        exit();
    }
    file_put_contents($filename, 'RUN');

    $message = "*CRON: (cronDATACOMPLETA2) * " . " - Fecha: " . date("Y-m-d H:i:s");
    $ConfigurationEnvironment = new ConfigurationEnvironment();
    $_ENV["NEEDINSOLATIONLEVEL"] = '1';

    if (!$ConfigurationEnvironment->isDevelopment()) {
        exec("php -f " . __DIR__ . "../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

    }
    /*
    $BonoInterno = new BonoInterno();
    $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
    $transaccion = $BonoDetalleMySqlDAO->getTransaction();
    $BonoInterno->execQuery($transaccion, "UPDATE usuario_bono SET estado='E' WHERE  estado='A' and fecha_expiracion <= now()");


    $transaccion->commit();*/

    if (false) {

        $BonoInterno = new BonoInterno();
        $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
        $transaccion = $BonoDetalleMySqlDAO->getTransaction();

        $sqlInsert = "SELECT t.*
        FROM casino.usuario_bono t
        WHERE estado='A' and fecha_expiracion <= now() LIMIT 500;";


        $datosBonosAExpirar = $BonoInterno->execQuery($transaccion, $sqlInsert);


        foreach ($datosBonosAExpirar as $datanum) {
            $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
            $transaccion = $BonoDetalleMySqlDAO->getTransaction();
            $BonoInterno->execQuery($transaccion, "UPDATE usuario_bono SET estado='E' WHERE usubono_id='" . $datanum->{'t.usubono_id'} . "'; ");
            $transaccion->commit();

        }
    }

    $BonoInterno = new BonoInterno();

    $sqlProcesoInterno2 = "

SELECT * FROM proceso_interno2 WHERE tipo='DATACOMPLETA2'
";


    $data = $BonoInterno->execQuery('', $sqlProcesoInterno2);
    $data = $data[0];
    $line = $data->{'proceso_interno2.fecha_ultima'};

    if ($line == '') {
        unlink($filename);

        exit();
    }


    $fechaL1 = date('Y-m-d H:i:00', strtotime($line . '+1 minute'));
    $fechaL2 = date('Y-m-d H:i:59', strtotime($line . '+10 minute'));


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
            unlink($filename);
            exit();
        }
// The new person to add to the file
        $person = $fechaL1 . "\n";

// Write the contents to the file,
// using the FILE_APPEND flag to append the content to the end of the file
// and the LOCK_EX flag to prevent anyone else writing to the file at the same time
        file_put_contents($file, $person, FILE_APPEND | LOCK_EX);


    }


    if ($fechaL1 >= date('Y-m-d H:i:00', strtotime('-10 minute'))) {
        unlink($filename);
        exit();
    }

    $BonoInterno = new BonoInterno();
    $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
    $transaccion = $BonoDetalleMySqlDAO->getTransaction();

    $sqlProcesoInterno2 = "
UPDATE proceso_interno2 SET fecha_ultima='" . $fechaL2 . "' WHERE  tipo='DATACOMPLETA2';
";


    $data = $BonoInterno->execQuery($transaccion, $sqlProcesoInterno2);
    $transaccion->commit();


    $fechaSoloDia = $fechaL1;
    $fechaSoloDia2 = $fechaL2;
    try {

        if (true) {
            print_r('Fecha Inicio: ' . $fechaL1 . ' - Fecha Fin: ' . $fechaL2);

            $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();


            $sqlHistorial2 = "

    select usuario_mandante.usumandante_id,usuario_mandante.fecha_crea
from usuario_mandante

where 
 usuario_mandante.fecha_crea >= '" . $fechaL1 . "'
  and usuario_mandante.fecha_crea <= '" . $fechaL2 . "'
";
            $sqlHistorial = '

    select usuario_mandante.usumandante_id,usuario_mandante.fecha_crea
from usuario_mandante
         left outer join data_completa2 on (usuario_mandante.usumandante_id = data_completa2.usuario_id)

where data_completa2.usuario_id is null

';
            $usuHistorialIds2 = $BonoInternoMySqlDAO->querySQL($sqlHistorial);

            $BonoInterno = new BonoInterno();
            $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
            $transaccion = $BonoDetalleMySqlDAO->getTransaction();
            $cont = 0;
            $contG = 0;

            $sqlInsert = 'INSERT INTO data_completa2
(usuario_id,fecha_creacion)
 VALUES ';
            foreach ($usuHistorialIds2 as $item) {
                //print_r($cont);
                //print_r(PHP_EOL);

                try {

                    if ($cont == 0) {
                        $sqlInsert .= ' (
        "' . $item['usuario_mandante.usumandante_id'] . '","' . $item['usuario_mandante.fecha_crea'] . '")

';
                    } else {

                        $sqlInsert .= ', (
        "' . $item['usuario_mandante.usumandante_id'] . '","' . $item['usuario_mandante.fecha_crea'] . '")

';
                    }
                    $cont++;

                    if ($cont == 1000) {

                        $BonoInterno->execQuery($transaccion, $sqlInsert);


                        $transaccion->commit();

                        $sqlInsert = 'INSERT INTO data_completa2
(usuario_id,fecha_creacion)
 VALUES ';

                        $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
                        $transaccion = $BonoDetalleMySqlDAO->getTransaction();
                        $cont = 0;
                    }
                } catch (Exception $e) {

                    $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
                    $transaccion = $BonoDetalleMySqlDAO->getTransaction();
                }
                $contG++;

            }

            if ($cont > 0) {

                $BonoInterno->execQuery($transaccion, $sqlInsert);


                $transaccion->commit();

            }
        }


        $BonoInterno = new BonoInterno();
        $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
        $transaccion = $BonoDetalleMySqlDAO->getTransaction();
        $BonoInterno = new BonoInterno();
        $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
        $transaccion = $BonoDetalleMySqlDAO->getTransaction();
        //$transaccion->getConnection()->beginTransaction();


        $sql = "

### INCIAL
SELECT usuario_mandante.usumandante_id,
       usuario.fecha_crea
FROM usuario 
         INNER JOIN usuario_mandante
                         ON usuario_mandante.usuario_mandante = usuario.usuario_id

                           WHERE usuario.fecha_crea >= '" . $fechaSoloDia . "'
                             AND usuario.fecha_crea < '" . $fechaSoloDia2 . "';
";

        print_r($sql);
        if (!$ConfigurationEnvironment->isDevelopment()) {
            //exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . str_replace("'", '', $sql) . "' '#virtualsoft-cron' > /dev/null & ");

        }
        if (false) {

            $data = $BonoInterno->execQuery($transaccion, $sql);

            foreach ($data as $datum) {
                $sql2 = "

INSERT INTO data_completa2 (usuario_id, fecha_creacion)

VALUES ('" . $datum->{'usuario_mandante.usumandante_id'} . "','" . $datum->{'usuario.fecha_crea'} . "')
ON DUPLICATE KEY UPDATE data_completa2.fecha_creacion='" . $datum->{'usuario.fecha_crea'} . "'
;
";


                $sql2 = "

UPDATE
data_completa2 SET 
                   
          data_completa2.fecha_creacion='" . $datum->{'usuario.fecha_crea'} . "'
                      
WHERE data_completa2.usuario_id='" . $datum->{'usuario_mandante.usumandante_id'} . "'
";


                $data = $BonoInterno->execQuery($transaccion, $sql2);

                $transaccion->commit();

                $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
                $transaccion = $BonoDetalleMySqlDAO->getTransaction();


            }
        }

        /*  $sql = "
      SELECT usuario_mandante.usumandante_id,
             usuario.fecha_ult
      FROM usuario
               INNER JOIN usuario_mandante
                               ON usuario_mandante.usuario_mandante = usuario.usuario_id

                                 WHERE usuario.fecha_ult >= '" . $fechaSoloDia . "'
                                   AND usuario.fecha_ult < '" . $fechaSoloDia2 . "'
      ;
      ";
          $data = $BonoInterno->execQuery($transaccion, $sql);

          foreach ($data as $datum) {
              $sql2 = "

      INSERT INTO data_completa2 (usuario_id, ultimo_inicio_sesion)


      VALUES ('" . $datum->{'usuario_mandante.usumandante_id'} . "','" . $datum->{'usuario.fecha_ult'} . "')
      ON DUPLICATE KEY UPDATE data_completa2.ultimo_inicio_sesion='" . $datum->{'usuario.fecha_ult'} . "'
      ;
      ";
              $data = $BonoInterno->execQuery($transaccion, $sql2);

          }*/

        $sql = "




SELECT usuario_mandante.usumandante_id,
       usuario.fecha_primerdeposito,
       usuario.monto_primerdeposito
FROM usuario 
         INNER JOIN usuario_mandante
                         ON usuario_mandante.usuario_mandante = usuario.usuario_id

                           WHERE usuario.fecha_primerdeposito >= '" . $fechaSoloDia . "'
                             AND usuario.fecha_primerdeposito < '" . $fechaSoloDia2 . "'
;
";
        $data = $BonoInterno->execQuery($transaccion, $sql);


        foreach ($data as $datum) {
            $sql2 = "

INSERT INTO data_completa2 (usuario_id, fecha_primer_deposito,
                            monto_primer_deposito)


VALUES ('" . $datum->{'usuario_mandante.usumandante_id'} . "','" . $datum->{'usuario.fecha_primerdeposito'} . "','" . $datum->{'usuario.monto_primerdeposito'} . "')
ON DUPLICATE KEY UPDATE data_completa2.fecha_primer_deposito='" . $datum->{'usuario.fecha_primerdeposito'} . "',data_completa2.monto_primer_deposito='" . $datum->{'usuario.monto_primerdeposito'} . "'
;
";
            $sql2 = "

UPDATE
data_completa2 SET 
                   data_completa2.fecha_primer_deposito='" . $datum->{'usuario.fecha_primerdeposito'} . "',data_completa2.monto_primer_deposito='" . $datum->{'usuario.monto_primerdeposito'} . "'
                   
WHERE data_completa2.usuario_id='" . $datum->{'usuario_mandante.usumandante_id'} . "'
";
            $data = $BonoInterno->execQuery($transaccion, $sql2);

            $transaccion->commit();

            $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
            $transaccion = $BonoDetalleMySqlDAO->getTransaction();


        }

        $sql = "




SELECT usuario_mandante.usumandante_id, usuario_mandante.usuario_mandante,
       usuario_log.fecha_crea
FROM usuario_log
         INNER JOIN usuario_mandante
                         ON usuario_mandante.usuario_mandante = usuario_log.usuario_id

                           WHERE usuario_log.fecha_crea >= '" . $fechaSoloDia . "'
                             AND usuario_log.fecha_crea < '" . $fechaSoloDia2 . "'  AND tipo='LOGIN'
;
";
        $data = $BonoInterno->execQuery($transaccion, $sql);


        foreach ($data as $datum) {
            $sql2 = "

INSERT INTO data_completa2 (usuario_id, ultimo_inicio_sesion)


VALUES ('" . $datum->{'usuario_mandante.usumandante_id'} . "','" . $datum->{'usuario_log.fecha_crea'} . "')
ON DUPLICATE KEY UPDATE data_completa2.ultimo_inicio_sesion='" . $datum->{'usuario_log.fecha_crea'} . "'
;
";
            $sql2 = "

UPDATE
data_completa2 SET 
                   
data_completa2.ultimo_inicio_sesion='" . $datum->{'usuario_log.fecha_crea'} . "'
                   
WHERE data_completa2.usuario_id='" . $datum->{'usuario_mandante.usumandante_id'} . "'
";
            $data = $BonoInterno->execQuery($transaccion, $sql2);

            $sql2 = "

UPDATE
usuario SET 
                   
usuario.intentos='0',usuario.estado='A'
                   
WHERE usuario.usuario_id='" . $datum->{'usuario_mandante.usuario_mandante'} . "'
";
            $data = $BonoInterno->execQuery($transaccion, $sql2);

            $transaccion->commit();

            $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
            $transaccion = $BonoDetalleMySqlDAO->getTransaction();


        }

        $sql = "



### FECHA ULTIMA RECARGA

SELECT usuario_mandante.usumandante_id,
       usuario_recarga_max.fecha_crea,
       usuario_recarga_max.valor

FROM usuario_mandante

         INNER JOIN
     (SELECT usuario_recarga.*
      FROM usuario_recarga
               inner join (SELECT MAX(recarga_id) recarga_id, usuario_id
                           FROM usuario_recarga
                           WHERE fecha_crea >= '" . $fechaSoloDia . "'
                             AND fecha_crea < '" . $fechaSoloDia2 . "'
                           GROUP BY usuario_id) a on a.recarga_id = usuario_recarga.recarga_id) usuario_recarga_max
     ON usuario_mandante.usuario_mandante = usuario_recarga_max.usuario_id


";
        $data = $BonoInterno->execQuery($transaccion, $sql);


        foreach ($data as $datum) {
            $sql2 = "

INSERT INTO data_completa2 (usuario_id, fecha_ultimo_deposito, monto_ultimo_deposito)


VALUES ('" . $datum->{'usuario_mandante.usumandante_id'} . "','" . $datum->{'usuario_recarga_max.fecha_crea'} . "','" . $datum->{'usuario_recarga_max.valor'} . "')
ON DUPLICATE KEY UPDATE data_completa2.fecha_ultimo_deposito='" . $datum->{'usuario_recarga_max.fecha_crea'} . "',data_completa2.monto_ultimo_deposito='" . $datum->{'usuario_recarga_max.valor'} . "'
;
";

            $sql2 = "

UPDATE
data_completa2 SET 
                   
data_completa2.fecha_ultimo_deposito='" . $datum->{'usuario_recarga_max.fecha_crea'} . "',data_completa2.monto_ultimo_deposito='" . $datum->{'usuario_recarga_max.valor'} . "'

                   
WHERE data_completa2.usuario_id='" . $datum->{'usuario_mandante.usumandante_id'} . "'
";


            $data = $BonoInterno->execQuery($transaccion, $sql2);

            $transaccion->commit();

            $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
            $transaccion = $BonoDetalleMySqlDAO->getTransaction();

        }


        $sql = "


### FECHA ULTIMA SOLICITUD DE DEPOSITO

SELECT usuario_mandante.usumandante_id,
       transaccion_producto.fecha_crea,
       transaccion_producto.valor

FROM usuario_mandante

         INNER JOIN
     (SELECT transaccion_producto.*
      FROM transaccion_producto
               inner join (SELECT MAX(transaccion_producto.transproducto_id) transproducto_id, usuario_id
                           FROM transaccion_producto
                           WHERE fecha_crea >= '" . $fechaSoloDia . "'
                             AND fecha_crea < '" . $fechaSoloDia2 . "'
                           GROUP BY usuario_id) a on a.transproducto_id = transaccion_producto.transproducto_id) transaccion_producto
     ON usuario_mandante.usuario_mandante = transaccion_producto.usuario_id

";
        $data = $BonoInterno->execQuery($transaccion, $sql);


        foreach ($data as $datum) {
            $sql2 = "

INSERT INTO data_completa2 (usuario_id, fecha_ultima_solicituddeposito, monto_ultimo_solicituddeposito)


VALUES ('" . $datum->{'usuario_mandante.usumandante_id'} . "','" . $datum->{'transaccion_producto.fecha_crea'} . "','" . $datum->{'transaccion_producto.valor'} . "')
ON DUPLICATE KEY UPDATE data_completa2.fecha_ultima_solicituddeposito='" . $datum->{'transaccion_producto.fecha_crea'} . "',data_completa2.monto_ultimo_solicituddeposito='" . $datum->{'transaccion_producto.valor'} . "'
;
";

            $sql2 = "

UPDATE
data_completa2 SET 
                   
 data_completa2.fecha_ultima_solicituddeposito='" . $datum->{'transaccion_producto.fecha_crea'} . "',data_completa2.monto_ultimo_solicituddeposito='" . $datum->{'transaccion_producto.valor'} . "'
                   
WHERE data_completa2.usuario_id='" . $datum->{'usuario_mandante.usumandante_id'} . "'
";


            $data = $BonoInterno->execQuery($transaccion, $sql2);

            $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
            $transaccion = $BonoDetalleMySqlDAO->getTransaction();


        }


        $sql = "
## ULTIMO RETIRO

SELECT usuario_mandante.usumandante_id,
       cuenta_cobro.fecha_crea,
       cuenta_cobro.valor

FROM usuario_mandante

         INNER JOIN
     (SELECT cuenta_cobro.*
      FROM cuenta_cobro
               inner join (SELECT MAX(cuenta_id) cuenta_id, usuario_id
                           FROM cuenta_cobro
                           WHERE fecha_crea >= '" . $fechaSoloDia . "'
                             AND fecha_crea < '" . $fechaSoloDia2 . "'
                           GROUP BY usuario_id) a on a.cuenta_id = cuenta_cobro.cuenta_id) cuenta_cobro
     ON usuario_mandante.usuario_mandante = cuenta_cobro.usuario_id

";

        $data = $BonoInterno->execQuery($transaccion, $sql);


        foreach ($data as $datum) {
            $sql2 = "

INSERT INTO data_completa2 (usuario_id, fecha_ultimo_retiro,
                            monto_ultimo_retiro)


VALUES ('" . $datum->{'usuario_mandante.usumandante_id'} . "','" . $datum->{'cuenta_cobro.fecha_crea'} . "','" . $datum->{'cuenta_cobro.valor'} . "')
ON DUPLICATE KEY UPDATE data_completa2.fecha_ultimo_retiro='" . $datum->{'cuenta_cobro.fecha_crea'} . "',data_completa2.monto_ultimo_retiro='" . $datum->{'cuenta_cobro.valor'} . "'
;
";

            $sql2 = "

UPDATE
data_completa2 SET 
                   
 data_completa2.fecha_ultimo_retiro='" . $datum->{'cuenta_cobro.fecha_crea'} . "',data_completa2.monto_ultimo_retiro='" . $datum->{'cuenta_cobro.valor'} . "'
 
                    
WHERE data_completa2.usuario_id='" . $datum->{'usuario_mandante.usumandante_id'} . "'
";


            $data = $BonoInterno->execQuery($transaccion, $sql2);

            $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
            $transaccion = $BonoDetalleMySqlDAO->getTransaction();


        }

        $sql = "
## ULTIMO RETIRO ELIMINADO

SELECT usuario_mandante.usumandante_id,
       cuenta_cobro.fecha_eliminacion,
       cuenta_cobro.valor

FROM usuario_mandante

         INNER JOIN
     (SELECT cuenta_cobro.*
      FROM cuenta_cobro
               inner join (SELECT MAX(cuenta_id) cuenta_id, usuario_id
                           FROM cuenta_cobro
                           where estado = 'E'
                             AND fecha_eliminacion >= '" . $fechaSoloDia . "'
                             AND fecha_eliminacion < '" . $fechaSoloDia2 . "'
                           GROUP BY usuario_id) a on a.cuenta_id = cuenta_cobro.cuenta_id) cuenta_cobro
     ON usuario_mandante.usuario_mandante = cuenta_cobro.usuario_id

";


        $data = $BonoInterno->execQuery($transaccion, $sql);


        foreach ($data as $datum) {
            $sql2 = "

INSERT INTO data_completa2 (usuario_id, fecha_ultimo_retiroeliminado, monto_ultimo_retiroeliminado)


VALUES ('" . $datum->{'usuario_mandante.usumandante_id'} . "','" . $datum->{'cuenta_cobro.fecha_eliminacion'} . "','" . $datum->{'cuenta_cobro.valor'} . "')
ON DUPLICATE KEY UPDATE data_completa2.fecha_ultimo_retiroeliminado='" . $datum->{'cuenta_cobro.fecha_eliminacion'} . "',data_completa2.monto_ultimo_retiroeliminado='" . $datum->{'cuenta_cobro.valor'} . "'
;
";

            $sql2 = "

UPDATE
data_completa2 SET 
                   
data_completa2.fecha_ultimo_retiroeliminado='" . $datum->{'cuenta_cobro.fecha_eliminacion'} . "',data_completa2.monto_ultimo_retiroeliminado='" . $datum->{'cuenta_cobro.valor'} . "' 
                    
WHERE data_completa2.usuario_id='" . $datum->{'usuario_mandante.usumandante_id'} . "'
";


            $data = $BonoInterno->execQuery($transaccion, $sql2);

            $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
            $transaccion = $BonoDetalleMySqlDAO->getTransaction();


        }

        $sql = "
## ULTIMO RETIRO PAGADO

SELECT usuario_mandante.usumandante_id,
       cuenta_cobro.fecha_pago,
       cuenta_cobro.valor

FROM usuario_mandante

         INNER JOIN
     (SELECT cuenta_cobro.*
      FROM cuenta_cobro
               inner join (SELECT MAX(cuenta_id) cuenta_id, usuario_id
                           FROM cuenta_cobro
                           where estado = 'I'
                             AND fecha_pago >= '" . $fechaSoloDia . "'
                             AND fecha_pago < '" . $fechaSoloDia2 . "'
                           GROUP BY usuario_id) a on a.cuenta_id = cuenta_cobro.cuenta_id) cuenta_cobro
     ON usuario_mandante.usuario_mandante = cuenta_cobro.usuario_id


";
        $data = $BonoInterno->execQuery($transaccion, $sql);


        foreach ($data as $datum) {
            $sql2 = "

INSERT INTO data_completa2 (usuario_id, fecha_ultimo_retiropagado, monto_ultimo_retiropagado)


VALUES ('" . $datum->{'usuario_mandante.usumandante_id'} . "','" . $datum->{'cuenta_cobro.fecha_pago'} . "','" . $datum->{'cuenta_cobro.valor'} . "')
ON DUPLICATE KEY UPDATE data_completa2.fecha_ultimo_retiropagado='" . $datum->{'cuenta_cobro.fecha_pago'} . "',data_completa2.monto_ultimo_retiropagado='" . $datum->{'cuenta_cobro.valor'} . "'
;
";

            $sql2 = "

UPDATE
data_completa2 SET 
                   
 data_completa2.fecha_ultimo_retiropagado='" . $datum->{'cuenta_cobro.fecha_pago'} . "',data_completa2.monto_ultimo_retiropagado='" . $datum->{'cuenta_cobro.valor'} . "'
 
                     
WHERE data_completa2.usuario_id='" . $datum->{'usuario_mandante.usumandante_id'} . "'
";


            $data = $BonoInterno->execQuery($transaccion, $sql2);

            $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
            $transaccion = $BonoDetalleMySqlDAO->getTransaction();


        }


        $sql = "
## ULTIMO RETIRO PENDIENTE

SELECT usuario_mandante.usumandante_id,
       cuenta_cobro.fecha_crea,
       cuenta_cobro.valor

FROM usuario_mandante

         INNER JOIN
     (SELECT cuenta_cobro.*
      FROM cuenta_cobro
               inner join (SELECT MAX(cuenta_id) cuenta_id, usuario_id
                           FROM cuenta_cobro
                           where estado IN ('A')
                             AND fecha_crea >= '" . $fechaSoloDia . "'
                             AND fecha_crea < '" . $fechaSoloDia2 . "'
                           GROUP BY usuario_id) a on a.cuenta_id = cuenta_cobro.cuenta_id) cuenta_cobro
     ON usuario_mandante.usuario_mandante = cuenta_cobro.usuario_id
     ;

";
        if (!$ConfigurationEnvironment->isDevelopment()) {
            //exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . str_replace("'", '', $sql) . "' '#virtualsoft-cron' > /dev/null & ");

        }

        $data = $BonoInterno->execQuery($transaccion, $sql);


        foreach ($data as $datum) {
            $sql2 = "

INSERT INTO data_completa2 (usuario_id, fecha_ultimo_retiropendiente, monto_ultimo_retiropendiente)


VALUES ('" . $datum->{'usuario_mandante.usumandante_id'} . "','" . $datum->{'cuenta_cobro.fecha_crea'} . "','" . $datum->{'cuenta_cobro.valor'} . "')
ON DUPLICATE KEY UPDATE data_completa2.fecha_ultimo_retiropendiente='" . $datum->{'cuenta_cobro.fecha_crea'} . "',data_completa2.monto_ultimo_retiropendiente='" . $datum->{'cuenta_cobro.valor'} . "'
;
";

            $sql2 = "

UPDATE
data_completa2 SET 
                   
 data_completa2.fecha_ultimo_retiropendiente='" . $datum->{'cuenta_cobro.fecha_crea'} . "',data_completa2.monto_ultimo_retiropendiente='" . $datum->{'cuenta_cobro.valor'} . "' 
                     
WHERE data_completa2.usuario_id='" . $datum->{'usuario_mandante.usumandante_id'} . "'
";


            $data = $BonoInterno->execQuery($transaccion, $sql2);

            $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
            $transaccion = $BonoDetalleMySqlDAO->getTransaction();


        }

        $sql = "
## ULTIMO RETIRO PENDIENTE POR PAGAR

SELECT usuario_mandante.usumandante_id,
       cuenta_cobro.fecha_accion,
       cuenta_cobro.valor

FROM usuario_mandante

         INNER JOIN
     (SELECT cuenta_cobro.*
      FROM cuenta_cobro
               inner join (SELECT MAX(cuenta_id) cuenta_id, usuario_id
                           FROM cuenta_cobro
                           where estado IN ('P')
                             AND fecha_accion >= '" . $fechaSoloDia . "'
                             AND fecha_accion < '" . $fechaSoloDia2 . "'
                           GROUP BY usuario_id) a on a.cuenta_id = cuenta_cobro.cuenta_id) cuenta_cobro
     ON usuario_mandante.usuario_mandante = cuenta_cobro.usuario_id


";
        $data = $BonoInterno->execQuery($transaccion, $sql);


        foreach ($data as $datum) {
            $sql2 = "

INSERT INTO data_completa2 (usuario_id, fecha_ultimo_retiropendienteporpagar, monto_ultimo_retiropendienteporpagar)


VALUES ('" . $datum->{'usuario_mandante.usumandante_id'} . "','" . $datum->{'cuenta_cobro.fecha_accion'} . "','" . $datum->{'cuenta_cobro.valor'} . "')
ON DUPLICATE KEY UPDATE data_completa2.fecha_ultimo_retiropendienteporpagar='" . $datum->{'cuenta_cobro.fecha_accion'} . "',data_completa2.monto_ultimo_retiropendienteporpagar='" . $datum->{'cuenta_cobro.valor'} . "'
;
";

            $sql2 = "

UPDATE
data_completa2 SET 
                   
data_completa2.fecha_ultimo_retiropendienteporpagar='" . $datum->{'cuenta_cobro.fecha_accion'} . "',data_completa2.monto_ultimo_retiropendienteporpagar='" . $datum->{'cuenta_cobro.valor'} . "'

                     
WHERE data_completa2.usuario_id='" . $datum->{'usuario_mandante.usumandante_id'} . "'
";


            $data = $BonoInterno->execQuery($transaccion, $sql2);

            $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
            $transaccion = $BonoDetalleMySqlDAO->getTransaction();

        }

        $sql = "
## ULTIMO AJUSTE ENTRADA

SELECT usuario_mandante.usumandante_id,
       saldo_usuonline_ajuste.fecha_crea,
       saldo_usuonline_ajuste.valor

FROM usuario_mandante

         INNER JOIN
     (SELECT saldo_usuonline_ajuste.*
      FROM saldo_usuonline_ajuste
               inner join (SELECT MAX(saldo_usuonline_ajuste.ajuste_id) cuenta_id, usuario_id
                           FROM saldo_usuonline_ajuste
                           where saldo_usuonline_ajuste.tipo IN ('E')
                             AND fecha_crea >= '" . $fechaSoloDia . "'
                             AND fecha_crea < '" . $fechaSoloDia2 . "'
                           GROUP BY usuario_id) a
                          on a.cuenta_id = saldo_usuonline_ajuste.ajuste_id) saldo_usuonline_ajuste
     ON usuario_mandante.usuario_mandante = saldo_usuonline_ajuste.usuario_id

";


        $data = $BonoInterno->execQuery($transaccion, $sql);


        foreach ($data as $datum) {
            $sql2 = "

INSERT INTO data_completa2 (usuario_id, fecha_ultimo_ajusteentrada, monto_ultimo_ajusteentrada)


VALUES ('" . $datum->{'usuario_mandante.usumandante_id'} . "','" . $datum->{'saldo_usuonline_ajuste.fecha_crea'} . "','" . $datum->{'saldo_usuonline_ajuste.valor'} . "')
ON DUPLICATE KEY UPDATE data_completa2.fecha_ultimo_ajusteentrada='" . $datum->{'saldo_usuonline_ajuste.fecha_crea'} . "',data_completa2.monto_ultimo_ajusteentrada='" . $datum->{'saldo_usuonline_ajuste.valor'} . "'
;
";

            $sql2 = "

UPDATE
data_completa2 SET 
                   
 data_completa2.fecha_ultimo_ajusteentrada='" . $datum->{'saldo_usuonline_ajuste.fecha_crea'} . "',data_completa2.monto_ultimo_ajusteentrada='" . $datum->{'saldo_usuonline_ajuste.valor'} . "'
 
                     
WHERE data_completa2.usuario_id='" . $datum->{'usuario_mandante.usumandante_id'} . "'
";


            $data = $BonoInterno->execQuery($transaccion, $sql2);

            $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
            $transaccion = $BonoDetalleMySqlDAO->getTransaction();


        }

        $sql = "
## ULTIMO AJUSTE SALIDA


SELECT usuario_mandante.usumandante_id,
       saldo_usuonline_ajuste.fecha_crea,
       saldo_usuonline_ajuste.valor

FROM usuario_mandante

         INNER JOIN
     (SELECT saldo_usuonline_ajuste.*
      FROM saldo_usuonline_ajuste
               inner join (SELECT MAX(saldo_usuonline_ajuste.ajuste_id) cuenta_id, usuario_id
                           FROM saldo_usuonline_ajuste
                           where saldo_usuonline_ajuste.tipo IN ('S')
                             AND fecha_crea >= '" . $fechaSoloDia . "'
                             AND fecha_crea < '" . $fechaSoloDia2 . "'
                           GROUP BY usuario_id) a
                          on a.cuenta_id = saldo_usuonline_ajuste.ajuste_id) saldo_usuonline_ajuste
     ON usuario_mandante.usuario_mandante = saldo_usuonline_ajuste.usuario_id


";

        $data = $BonoInterno->execQuery($transaccion, $sql);


        foreach ($data as $datum) {
            $sql2 = "

INSERT INTO data_completa2 (usuario_id, fecha_ultimo_ajustesalida, monto_ultimo_ajustesalida)


VALUES ('" . $datum->{'usuario_mandante.usumandante_id'} . "','" . $datum->{'saldo_usuonline_ajuste.fecha_crea'} . "','" . $datum->{'saldo_usuonline_ajuste.valor'} . "')
ON DUPLICATE KEY UPDATE data_completa2.fecha_ultimo_ajustesalida='" . $datum->{'saldo_usuonline_ajuste.fecha_crea'} . "',data_completa2.monto_ultimo_ajustesalida='" . $datum->{'saldo_usuonline_ajuste.valor'} . "'
;
";

            $sql2 = "

UPDATE
data_completa2 SET 
                   
 data_completa2.fecha_ultimo_ajustesalida='" . $datum->{'saldo_usuonline_ajuste.fecha_crea'} . "',data_completa2.monto_ultimo_ajustesalida='" . $datum->{'saldo_usuonline_ajuste.valor'} . "' 
                     
WHERE data_completa2.usuario_id='" . $datum->{'usuario_mandante.usumandante_id'} . "'
";


            $data = $BonoInterno->execQuery($transaccion, $sql2);

            $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
            $transaccion = $BonoDetalleMySqlDAO->getTransaction();


        }


        $sql = "
## ULTIMO APUESTA CASINO

SELECT usuario_mandante.usumandante_id,
       transjuego_log.fecha_crea,
       transjuego_log.valor

FROM usuario_mandante

         INNER JOIN
     (SELECT transjuego_log.*, a.usuario_id
      FROM transjuego_log
               inner join (SELECT MAX(transjuego_log.transjuegolog_id) transjuegolog_id, usuario_id
                           FROM transjuego_log
                                    INNER JOIN transaccion_juego
                                               on transjuego_log.transjuego_id = transaccion_juego.transjuego_id

                                    INNER JOIN producto_mandante
                                               on transaccion_juego.producto_id = producto_mandante.prodmandante_id
                                    INNER JOIN producto on producto_mandante.producto_id = producto.producto_id
                                    INNER JOIN subproveedor on subproveedor.subproveedor_id = producto.subproveedor_id

                           where transjuego_log.tipo LIKE 'DEBIT%'
                             AND subproveedor.tipo = 'CASINO'
                             AND transjuego_log.fecha_crea >= '" . $fechaSoloDia . "'
                             AND transjuego_log.fecha_crea < '" . $fechaSoloDia2 . "'
                           GROUP BY transaccion_juego.usuario_id) a on a.transjuegolog_id = transjuego_log.transjuegolog_id) transjuego_log
     ON usuario_mandante.usumandante_id = transjuego_log.usuario_id

";

        $data = $BonoInterno->execQuery($transaccion, $sql);

        print_r($sql);


        foreach ($data as $datum) {
            $sql2 = "

INSERT INTO data_completa2 (usuario_id, fecha_ultima_apuestacasino, monto_ultimo_apuestacasino)


VALUES ('" . $datum->{'usuario_mandante.usumandante_id'} . "','" . $datum->{'transjuego_log.fecha_crea'} . "','" . $datum->{'transjuego_log.valor'} . "')
ON DUPLICATE KEY UPDATE data_completa2.fecha_ultima_apuestacasino='" . $datum->{'transjuego_log.fecha_crea'} . "',data_completa2.monto_ultimo_apuestacasino='" . $datum->{'transjuego_log.valor'} . "'
;
";


            $sql2 = "

UPDATE
data_completa2 SET 
                   
 data_completa2.fecha_ultima_apuestacasino='" . $datum->{'transjuego_log.fecha_crea'} . "',data_completa2.monto_ultimo_apuestacasino='" . $datum->{'transjuego_log.valor'} . "'
 
                      
WHERE data_completa2.usuario_id='" . $datum->{'usuario_mandante.usumandante_id'} . "'
";


            $data = $BonoInterno->execQuery($transaccion, $sql2);

            $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
            $transaccion = $BonoDetalleMySqlDAO->getTransaction();


        }


        $sql = "
## ULTIMO PREMIO CASINO

SELECT usuario_mandante.usumandante_id,
       transjuego_log.fecha_crea,
       transjuego_log.valor

FROM usuario_mandante

         INNER JOIN
     (SELECT transjuego_log.*, a.usuario_id
      FROM transjuego_log
               inner join (SELECT MAX(transjuego_log.transjuegolog_id) transjuegolog_id, usuario_id
                           FROM transjuego_log
                                    INNER JOIN transaccion_juego
                                               on transjuego_log.transjuego_id = transaccion_juego.transjuego_id

                                    INNER JOIN producto_mandante
                                               on transaccion_juego.producto_id = producto_mandante.prodmandante_id
                                    INNER JOIN producto on producto_mandante.producto_id = producto.producto_id
                                    INNER JOIN subproveedor on subproveedor.subproveedor_id = producto.subproveedor_id

                           where transjuego_log.tipo LIKE 'CREDIT%' AND (transjuego_log.tipo NOT LIKE '%ROLLBACK%')
                             AND subproveedor.tipo = 'CASINO'
                             AND transjuego_log.fecha_crea >= '" . $fechaSoloDia . "'
                             AND transjuego_log.fecha_crea < '" . $fechaSoloDia2 . "'

                           GROUP BY transaccion_juego.usuario_id) a on a.transjuegolog_id = transjuego_log.transjuegolog_id) transjuego_log
     ON usuario_mandante.usumandante_id = transjuego_log.usuario_id

";
        $data = $BonoInterno->execQuery($transaccion, $sql);


        foreach ($data as $datum) {
            $sql2 = "

INSERT INTO data_completa2 (usuario_id, fecha_ultima_premiocasino, monto_ultimo_premiocasino)


VALUES ('" . $datum->{'usuario_mandante.usumandante_id'} . "','" . $datum->{'transjuego_log.fecha_crea'} . "','" . $datum->{'transjuego_log.valor'} . "')
ON DUPLICATE KEY UPDATE data_completa2.fecha_ultima_premiocasino='" . $datum->{'transjuego_log.fecha_crea'} . "',data_completa2.monto_ultimo_premiocasino='" . $datum->{'transjuego_log.valor'} . "'
;
";


            $sql2 = "

UPDATE
data_completa2 SET 
                   
 data_completa2.fecha_ultima_premiocasino='" . $datum->{'transjuego_log.fecha_crea'} . "',data_completa2.monto_ultimo_premiocasino='" . $datum->{'transjuego_log.valor'} . "' 
                      
WHERE data_completa2.usuario_id='" . $datum->{'usuario_mandante.usumandante_id'} . "'
";


            $data = $BonoInterno->execQuery($transaccion, $sql2);

            $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
            $transaccion = $BonoDetalleMySqlDAO->getTransaction();


        }


        $sql = "
## ULTIMO APUESTA CASINO VIVO


SELECT usuario_mandante.usumandante_id,
       transjuego_log.fecha_crea,
       transjuego_log.valor

FROM usuario_mandante

         INNER JOIN
     (SELECT transjuego_log.*, a.usuario_id
      FROM transjuego_log
               inner join (SELECT MAX(transjuego_log.transjuegolog_id) transjuegolog_id, usuario_id
                           FROM transjuego_log
                                    INNER JOIN transaccion_juego
                                               on transjuego_log.transjuego_id = transaccion_juego.transjuego_id

                                    INNER JOIN producto_mandante
                                               on transaccion_juego.producto_id = producto_mandante.prodmandante_id
                                    INNER JOIN producto on producto_mandante.producto_id = producto.producto_id
                                    INNER JOIN subproveedor on subproveedor.subproveedor_id = producto.subproveedor_id


                           where transjuego_log.tipo LIKE 'DEBIT%'
                             AND subproveedor.tipo = 'LIVECASINO'
                             AND transjuego_log.fecha_crea >= '" . $fechaSoloDia . "'
                             AND transjuego_log.fecha_crea < '" . $fechaSoloDia2 . "'

                           GROUP BY transaccion_juego.usuario_id) a on a.transjuegolog_id = transjuego_log.transjuegolog_id) transjuego_log
     ON usuario_mandante.usumandante_id = transjuego_log.usuario_id

";

        $data = $BonoInterno->execQuery($transaccion, $sql);


        foreach ($data as $datum) {
            $sql2 = "

INSERT INTO data_completa2 (usuario_id, fecha_ultima_apuestacasinovivo, monto_ultimo_apuestacasinovivo)


VALUES ('" . $datum->{'usuario_mandante.usumandante_id'} . "','" . $datum->{'transjuego_log.fecha_crea'} . "','" . $datum->{'transjuego_log.valor'} . "')
ON DUPLICATE KEY UPDATE data_completa2.fecha_ultima_apuestacasinovivo='" . $datum->{'transjuego_log.fecha_crea'} . "',data_completa2.monto_ultimo_apuestacasinovivo='" . $datum->{'transjuego_log.valor'} . "'
;
";


            $sql2 = "

UPDATE
data_completa2 SET 
                   
  data_completa2.fecha_ultima_apuestacasinovivo='" . $datum->{'transjuego_log.fecha_crea'} . "',data_completa2.monto_ultimo_apuestacasinovivo='" . $datum->{'transjuego_log.valor'} . "'
                      
WHERE data_completa2.usuario_id='" . $datum->{'usuario_mandante.usumandante_id'} . "'
";


            $data = $BonoInterno->execQuery($transaccion, $sql2);

            $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
            $transaccion = $BonoDetalleMySqlDAO->getTransaction();


        }

        $sql = "
## ULTIMO PREMIO CASINO


SELECT usuario_mandante.usumandante_id,
       transjuego_log.fecha_crea,
       transjuego_log.valor

FROM usuario_mandante

         INNER JOIN
     (SELECT transjuego_log.*, a.usuario_id
      FROM transjuego_log
               inner join (SELECT MAX(transjuego_log.transjuegolog_id) transjuegolog_id, usuario_id
                           FROM transjuego_log
                                    INNER JOIN transaccion_juego
                                               on transjuego_log.transjuego_id = transaccion_juego.transjuego_id

                                    INNER JOIN producto_mandante
                                               on transaccion_juego.producto_id = producto_mandante.prodmandante_id
                                    INNER JOIN producto on producto_mandante.producto_id = producto.producto_id
                                    INNER JOIN subproveedor on subproveedor.subproveedor_id = producto.subproveedor_id


                           where transjuego_log.tipo LIKE 'CREDIT%' AND (transjuego_log.tipo NOT LIKE '%ROLLBACK%')
                             AND subproveedor.tipo = 'LIVECASINO'
                             AND transjuego_log.fecha_crea >= '" . $fechaSoloDia . "'
                             AND transjuego_log.fecha_crea < '" . $fechaSoloDia2 . "'

                           GROUP BY transaccion_juego.usuario_id) a on a.transjuegolog_id = transjuego_log.transjuegolog_id) transjuego_log
     ON usuario_mandante.usumandante_id = transjuego_log.usuario_id

";

        $data = $BonoInterno->execQuery($transaccion, $sql);


        foreach ($data as $datum) {
            $sql2 = "

INSERT INTO data_completa2 (usuario_id, fecha_ultima_premiocasinovivo, monto_ultimo_premiocasinovivo)


VALUES ('" . $datum->{'usuario_mandante.usumandante_id'} . "','" . $datum->{'transjuego_log.fecha_crea'} . "','" . $datum->{'transjuego_log.valor'} . "')
ON DUPLICATE KEY UPDATE data_completa2.fecha_ultima_premiocasinovivo='" . $datum->{'transjuego_log.fecha_crea'} . "',data_completa2.monto_ultimo_premiocasinovivo='" . $datum->{'transjuego_log.valor'} . "'
;
";


            $sql2 = "

UPDATE
data_completa2 SET 
                   
   data_completa2.fecha_ultima_premiocasinovivo='" . $datum->{'transjuego_log.fecha_crea'} . "',data_completa2.monto_ultimo_premiocasinovivo='" . $datum->{'transjuego_log.valor'} . "'
                      
WHERE data_completa2.usuario_id='" . $datum->{'usuario_mandante.usumandante_id'} . "'
";

            $data = $BonoInterno->execQuery($transaccion, $sql2);

            $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
            $transaccion = $BonoDetalleMySqlDAO->getTransaction();


        }


        $sql = "
## ULTIMO APUESTA VIRTUALES

SELECT usuario_mandante.usumandante_id,
       transjuego_log.fecha_crea,
       transjuego_log.valor

FROM usuario_mandante

         INNER JOIN
     (SELECT transjuego_log.*, a.usuario_id
      FROM transjuego_log
               inner join (SELECT MAX(transjuego_log.transjuegolog_id) transjuegolog_id, usuario_id
                           FROM transjuego_log
                                    INNER JOIN transaccion_juego
                                               on transjuego_log.transjuego_id = transaccion_juego.transjuego_id

                                    INNER JOIN producto_mandante
                                               on transaccion_juego.producto_id = producto_mandante.prodmandante_id
                                    INNER JOIN producto on producto_mandante.producto_id = producto.producto_id
                                    INNER JOIN subproveedor on subproveedor.subproveedor_id = producto.subproveedor_id


                           where transjuego_log.tipo LIKE '%DEBIT%'
                             AND subproveedor.tipo = 'VIRTUAL'
                             AND transjuego_log.fecha_crea >= '" . $fechaSoloDia . "'
                             AND transjuego_log.fecha_crea < '" . $fechaSoloDia2 . "'

                           GROUP BY transaccion_juego.usuario_id) a on a.transjuegolog_id = transjuego_log.transjuegolog_id) transjuego_log
     ON usuario_mandante.usumandante_id = transjuego_log.usuario_id

";
        if (!$ConfigurationEnvironment->isDevelopment()) {
            //exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . str_replace("'", '', $sql) . "' '#virtualsoft-cron' > /dev/null & ");

        }
        $data = $BonoInterno->execQuery($transaccion, $sql);


        foreach ($data as $datum) {
            $sql2 = "

INSERT INTO data_completa2 (usuario_id, fecha_ultima_apuestavirtuales,
                            monto_ultimo_apuestavirtuales)


VALUES ('" . $datum->{'usuario_mandante.usumandante_id'} . "','" . $datum->{'transjuego_log.fecha_crea'} . "','" . $datum->{'transjuego_log.valor'} . "')
ON DUPLICATE KEY UPDATE data_completa2.fecha_ultima_apuestavirtuales='" . $datum->{'transjuego_log.fecha_crea'} . "',data_completa2.monto_ultimo_apuestavirtuales='" . $datum->{'transjuego_log.valor'} . "'
;
";


            $sql2 = "

UPDATE
data_completa2 SET 
                   
    data_completa2.fecha_ultima_apuestavirtuales='" . $datum->{'transjuego_log.fecha_crea'} . "',data_completa2.monto_ultimo_apuestavirtuales='" . $datum->{'transjuego_log.valor'} . "'
                      
WHERE data_completa2.usuario_id='" . $datum->{'usuario_mandante.usumandante_id'} . "'
";

            $data = $BonoInterno->execQuery($transaccion, $sql2);

            $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
            $transaccion = $BonoDetalleMySqlDAO->getTransaction();


        }

        $sql = "
## ULTIMO PREMIO VIRTUALES

SELECT usuario_mandante.usumandante_id,
       transjuego_log.fecha_crea,
       transjuego_log.valor

FROM usuario_mandante

         INNER JOIN
     (SELECT transjuego_log.*, a.usuario_id
      FROM transjuego_log
               inner join (SELECT MAX(transjuego_log.transjuegolog_id) transjuegolog_id, usuario_id
                           FROM transjuego_log
                                    INNER JOIN transaccion_juego
                                               on transjuego_log.transjuego_id = transaccion_juego.transjuego_id

                                    INNER JOIN producto_mandante
                                               on transaccion_juego.producto_id = producto_mandante.prodmandante_id
                                    INNER JOIN producto on producto_mandante.producto_id = producto.producto_id
                                    INNER JOIN subproveedor on subproveedor.subproveedor_id = producto.subproveedor_id


                           where transjuego_log.tipo LIKE '%CREDIT%' AND (transjuego_log.tipo NOT LIKE '%ROLLBACK%')
                             AND subproveedor.tipo = 'VIRTUAL'
                             AND transjuego_log.fecha_crea >= '" . $fechaSoloDia . "'
                             AND transjuego_log.fecha_crea < '" . $fechaSoloDia2 . "'

                           GROUP BY transaccion_juego.usuario_id) a on a.transjuegolog_id = transjuego_log.transjuegolog_id) transjuego_log
     ON usuario_mandante.usumandante_id = transjuego_log.usuario_id


";

        $data = $BonoInterno->execQuery($transaccion, $sql);


        foreach ($data as $datum) {
            $sql2 = "

INSERT INTO data_completa2 (usuario_id, fecha_ultima_premiovirtuales, monto_ultimo_premiovirtuales)


VALUES ('" . $datum->{'usuario_mandante.usumandante_id'} . "','" . $datum->{'transjuego_log.fecha_crea'} . "','" . $datum->{'transjuego_log.valor'} . "')
ON DUPLICATE KEY UPDATE data_completa2.fecha_ultima_premiovirtuales='" . $datum->{'transjuego_log.fecha_crea'} . "',data_completa2.monto_ultimo_premiovirtuales='" . $datum->{'transjuego_log.valor'} . "'
;
";


            $sql2 = "

UPDATE
data_completa2 SET 
                   
    data_completa2.fecha_ultima_premiovirtuales='" . $datum->{'transjuego_log.fecha_crea'} . "',data_completa2.monto_ultimo_premiovirtuales='" . $datum->{'transjuego_log.valor'} . "'
                      
WHERE data_completa2.usuario_id='" . $datum->{'usuario_mandante.usumandante_id'} . "'
";


            $data = $BonoInterno->execQuery($transaccion, $sql2);

            $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
            $transaccion = $BonoDetalleMySqlDAO->getTransaction();


        }

        $sql = "
## ULTIMO APUESTA DEPORTIVAS


SELECT usuario_mandante.usumandante_id,
       it_transaccion.fecha_crea_time,
       it_transaccion.valor

FROM usuario_mandante

         INNER JOIN
     (SELECT it_transaccion.*
      FROM it_transaccion
               inner join (SELECT MAX(it_transaccion.it_cuentatrans_id) it_cuentatrans_id, usuario_id
                           FROM it_transaccion

                           WHERE it_transaccion.tipo = 'BET'
                             AND it_transaccion.fecha_crea_time >= '" . $fechaSoloDia . "'
                             AND it_transaccion.fecha_crea_time < '" . $fechaSoloDia2 . "'

                           GROUP BY it_transaccion.usuario_id) a
                          on a.it_cuentatrans_id = it_transaccion.it_cuentatrans_id) it_transaccion
     ON usuario_mandante.usuario_mandante = it_transaccion.usuario_id

";
        $data = $BonoInterno->execQuery($transaccion, $sql);


        foreach ($data as $datum) {
            $sql2 = "

INSERT INTO data_completa2 (usuario_id, fecha_ultima_apuestadeportivas,
                            monto_ultimo_apuestadeportivas)


VALUES ('" . $datum->{'usuario_mandante.usumandante_id'} . "','" . $datum->{'it_transaccion.fecha_crea_time'} . "','" . $datum->{'it_transaccion.valor'} . "')
ON DUPLICATE KEY UPDATE data_completa2.fecha_ultima_apuestadeportivas='" . $datum->{'it_transaccion.fecha_crea_time'} . "',data_completa2.monto_ultimo_apuestadeportivas='" . $datum->{'it_transaccion.valor'} . "'
;
";


            $sql2 = "

UPDATE
data_completa2 SET 
                   
     data_completa2.fecha_ultima_apuestadeportivas='" . $datum->{'it_transaccion.fecha_crea_time'} . "',data_completa2.monto_ultimo_apuestadeportivas='" . $datum->{'it_transaccion.valor'} . "'
                      
WHERE data_completa2.usuario_id='" . $datum->{'usuario_mandante.usumandante_id'} . "'
";


            $data = $BonoInterno->execQuery($transaccion, $sql2);

            $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
            $transaccion = $BonoDetalleMySqlDAO->getTransaction();


        }

        $sql = "
## ULTIMO PREMIO DEPORTIVAS

SELECT usuario_mandante.usumandante_id,
       it_transaccion.fecha_crea_time,
       it_transaccion.valor

FROM usuario_mandante

         INNER JOIN
     (SELECT it_transaccion.*
      FROM it_transaccion
               inner join (SELECT MAX(it_transaccion.it_cuentatrans_id) it_cuentatrans_id
                           FROM it_transaccion
                                    INNER JOIN it_ticket_enc on it_transaccion.ticket_id = it_ticket_enc.ticket_id

                           WHERE it_ticket_enc.bet_mode != ''
                             AND it_transaccion.tipo = 'WIN'
                             AND it_transaccion.fecha_crea_time >= '" . $fechaSoloDia . "'
                             AND it_transaccion.fecha_crea_time < '" . $fechaSoloDia2 . "'

                           GROUP BY it_transaccion.usuario_id) a
                          on a.it_cuentatrans_id = it_transaccion.it_cuentatrans_id) it_transaccion
     ON usuario_mandante.usuario_mandante = it_transaccion.usuario_id


";

        $data = $BonoInterno->execQuery($transaccion, $sql);


        foreach ($data as $datum) {
            $sql2 = "

INSERT INTO data_completa2 (usuario_id, fecha_ultima_premiodeportivas,
                            monto_ultimo_premiodeportivas)


VALUES ('" . $datum->{'usuario_mandante.usumandante_id'} . "','" . $datum->{'it_transaccion.fecha_crea_time'} . "','" . $datum->{'it_transaccion.valor'} . "')
ON DUPLICATE KEY UPDATE data_completa2.fecha_ultima_premiodeportivas='" . $datum->{'it_transaccion.fecha_crea_time'} . "',data_completa2.monto_ultimo_premiodeportivas='" . $datum->{'it_transaccion.valor'} . "'
;
";


            $sql2 = "

UPDATE
data_completa2 SET 
                   
     data_completa2.fecha_ultima_premiodeportivas='" . $datum->{'it_transaccion.fecha_crea_time'} . "',data_completa2.monto_ultimo_premiodeportivas='" . $datum->{'it_transaccion.valor'} . "'
                      
WHERE data_completa2.usuario_id='" . $datum->{'usuario_mandante.usumandante_id'} . "'
";

            $data = $BonoInterno->execQuery($transaccion, $sql2);

            $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
            $transaccion = $BonoDetalleMySqlDAO->getTransaction();


        }

        $sql = "
## ULTIMO APUESTA DEPORTIVAS PRELIVE

SELECT usuario_mandante.usumandante_id,
       it_transaccion.fecha_crea_time,
       it_transaccion.valor

FROM usuario_mandante

         INNER JOIN
     (SELECT it_transaccion.*
      FROM it_transaccion
               inner join (SELECT MAX(it_transaccion.it_cuentatrans_id) it_cuentatrans_id, it_transaccion.usuario_id
                           FROM it_transaccion
                                    INNER JOIN it_ticket_enc on it_transaccion.ticket_id = it_ticket_enc.ticket_id
                           where it_ticket_enc.bet_mode = 'prelive'
                             AND it_transaccion.tipo = 'BET'
                             AND it_transaccion.fecha_crea_time >= '" . $fechaSoloDia . "'
                             AND it_transaccion.fecha_crea_time < '" . $fechaSoloDia2 . "'

                           GROUP BY it_transaccion.usuario_id) a
                          on a.it_cuentatrans_id = it_transaccion.it_cuentatrans_id) it_transaccion
     ON usuario_mandante.usuario_mandante = it_transaccion.usuario_id

";


        $data = $BonoInterno->execQuery($transaccion, $sql);


        foreach ($data as $datum) {
            $sql2 = "

INSERT INTO data_completa2 (usuario_id, fecha_ultima_apuestadeportivasprelive,
                            monto_ultimo_apuestadeportivasprelive)


VALUES ('" . $datum->{'usuario_mandante.usumandante_id'} . "','" . $datum->{'it_transaccion.fecha_crea_time'} . "','" . $datum->{'it_transaccion.valor'} . "')
ON DUPLICATE KEY UPDATE data_completa2.fecha_ultima_apuestadeportivasprelive='" . $datum->{'it_transaccion.fecha_crea_time'} . "',data_completa2.monto_ultimo_apuestadeportivasprelive='" . $datum->{'it_transaccion.valor'} . "'
;
";


            $sql2 = "

UPDATE
data_completa2 SET 
                   
      data_completa2.fecha_ultima_apuestadeportivasprelive='" . $datum->{'it_transaccion.fecha_crea_time'} . "',data_completa2.monto_ultimo_apuestadeportivasprelive='" . $datum->{'it_transaccion.valor'} . "'
                      
WHERE data_completa2.usuario_id='" . $datum->{'usuario_mandante.usumandante_id'} . "'
";


            $data = $BonoInterno->execQuery($transaccion, $sql2);

            $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
            $transaccion = $BonoDetalleMySqlDAO->getTransaction();


        }

        $sql = "
## ULTIMO PREMIO DEPORTIVAS PRELIVE

SELECT usuario_mandante.usumandante_id,
       it_transaccion.fecha_crea_time,
       it_transaccion.valor

FROM usuario_mandante

         INNER JOIN
     (SELECT it_transaccion.*
      FROM it_transaccion
               inner join (SELECT MAX(it_transaccion.it_cuentatrans_id) it_cuentatrans_id, it_transaccion.usuario_id
                           FROM it_transaccion
                                    INNER JOIN it_ticket_enc on it_transaccion.ticket_id = it_ticket_enc.ticket_id
                           where it_ticket_enc.bet_mode = 'prelive'
                             AND it_transaccion.tipo = 'WIN'
                             AND it_transaccion.fecha_crea_time >= '" . $fechaSoloDia . "'
                             AND it_transaccion.fecha_crea_time < '" . $fechaSoloDia2 . "'

                           GROUP BY it_transaccion.usuario_id) a
                          on a.it_cuentatrans_id = it_transaccion.it_cuentatrans_id) it_transaccion
     ON usuario_mandante.usuario_mandante = it_transaccion.usuario_id


";
        if (!$ConfigurationEnvironment->isDevelopment()) {
            //exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . str_replace("'", '', $sql) . "' '#virtualsoft-cron' > /dev/null & ");

        }


        $data = $BonoInterno->execQuery($transaccion, $sql);


        foreach ($data as $datum) {
            $sql2 = "

INSERT INTO data_completa2 (usuario_id, fecha_ultima_premiodeportivasprelive,
                            monto_ultimo_premiodeportivasprelive)


VALUES ('" . $datum->{'usuario_mandante.usumandante_id'} . "','" . $datum->{'it_transaccion.fecha_crea_time'} . "','" . $datum->{'it_transaccion.valor'} . "')
ON DUPLICATE KEY UPDATE data_completa2.fecha_ultima_premiodeportivasprelive='" . $datum->{'it_transaccion.fecha_crea_time'} . "',data_completa2.monto_ultimo_premiodeportivasprelive='" . $datum->{'it_transaccion.valor'} . "'
;
";


            $sql2 = "

UPDATE
data_completa2 SET 
                   
      data_completa2.fecha_ultima_premiodeportivasprelive='" . $datum->{'it_transaccion.fecha_crea_time'} . "',data_completa2.monto_ultimo_premiodeportivasprelive='" . $datum->{'it_transaccion.valor'} . "'
                      
WHERE data_completa2.usuario_id='" . $datum->{'usuario_mandante.usumandante_id'} . "'
";


            $data = $BonoInterno->execQuery($transaccion, $sql2);

            $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
            $transaccion = $BonoDetalleMySqlDAO->getTransaction();


        }

        $sql = "
## ULTIMO APUESTA DEPORTIVAS LIVE


SELECT usuario_mandante.usumandante_id,
       it_transaccion.fecha_crea_time,
       it_transaccion.valor

FROM usuario_mandante

         INNER JOIN
     (SELECT it_transaccion.*
      FROM it_transaccion
               inner join (SELECT MAX(it_transaccion.it_cuentatrans_id) it_cuentatrans_id
                           FROM it_transaccion
                                    INNER JOIN it_ticket_enc on it_transaccion.ticket_id = it_ticket_enc.ticket_id
                           where it_ticket_enc.bet_mode = 'live'
                             AND it_transaccion.tipo = 'BET'
                             AND it_transaccion.fecha_crea_time >= '" . $fechaSoloDia . "'
                             AND it_transaccion.fecha_crea_time < '" . $fechaSoloDia2 . "'

                           GROUP BY it_transaccion.usuario_id) a
                          on a.it_cuentatrans_id = it_transaccion.it_cuentatrans_id) it_transaccion
     ON usuario_mandante.usuario_mandante = it_transaccion.usuario_id

";


        $data = $BonoInterno->execQuery($transaccion, $sql);


        foreach ($data as $datum) {
            $sql2 = "

INSERT INTO data_completa2 (usuario_id, fecha_ultima_apuestadeportivaslive,
                            monto_ultimo_apuestadeportivaslive)


VALUES ('" . $datum->{'usuario_mandante.usumandante_id'} . "','" . $datum->{'it_transaccion.fecha_crea_time'} . "','" . $datum->{'it_transaccion.valor'} . "')
ON DUPLICATE KEY UPDATE data_completa2.fecha_ultima_apuestadeportivaslive='" . $datum->{'it_transaccion.fecha_crea_time'} . "',data_completa2.monto_ultimo_apuestadeportivaslive='" . $datum->{'it_transaccion.valor'} . "'
;
";


            $sql2 = "

UPDATE
data_completa2 SET 
                   
       data_completa2.fecha_ultima_apuestadeportivaslive='" . $datum->{'it_transaccion.fecha_crea_time'} . "',data_completa2.monto_ultimo_apuestadeportivaslive='" . $datum->{'it_transaccion.valor'} . "'
                      
WHERE data_completa2.usuario_id='" . $datum->{'usuario_mandante.usumandante_id'} . "'
";


            $data = $BonoInterno->execQuery($transaccion, $sql2);

            $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
            $transaccion = $BonoDetalleMySqlDAO->getTransaction();


        }

        $sql = "
## ULTIMO PREMIO DEPORTIVAS LIVE

SELECT usuario_mandante.usumandante_id,
       it_transaccion.fecha_crea_time,
       it_transaccion.valor

FROM usuario_mandante

         INNER JOIN
     (SELECT it_transaccion.*
      FROM it_transaccion
               inner join (SELECT MAX(it_transaccion.it_cuentatrans_id) it_cuentatrans_id
                           FROM it_transaccion
                                    INNER JOIN it_ticket_enc on it_transaccion.ticket_id = it_ticket_enc.ticket_id
                           where it_ticket_enc.bet_mode = 'live'
                             AND it_transaccion.tipo = 'WIN'
                             AND it_transaccion.fecha_crea_time >= '" . $fechaSoloDia . "'
                             AND it_transaccion.fecha_crea_time < '" . $fechaSoloDia2 . "'

                           GROUP BY it_transaccion.usuario_id) a
                          on a.it_cuentatrans_id = it_transaccion.it_cuentatrans_id) it_transaccion
     ON usuario_mandante.usuario_mandante = it_transaccion.usuario_id


";


        $data = $BonoInterno->execQuery($transaccion, $sql);


        foreach ($data as $datum) {
            $sql2 = "

INSERT INTO data_completa2 (usuario_id, fecha_ultima_premiodeportivaslive,
                            monto_ultimo_premiodeportivaslive)


VALUES ('" . $datum->{'usuario_mandante.usumandante_id'} . "','" . $datum->{'it_transaccion.fecha_crea_time'} . "','" . $datum->{'it_transaccion.valor'} . "')
ON DUPLICATE KEY UPDATE data_completa2.fecha_ultima_premiodeportivaslive='" . $datum->{'it_transaccion.fecha_crea_time'} . "',data_completa2.monto_ultimo_premiodeportivaslive='" . $datum->{'it_transaccion.valor'} . "'
;
";


            $sql2 = "

UPDATE
data_completa2 SET 
                   
       data_completa2.fecha_ultima_premiodeportivaslive='" . $datum->{'it_transaccion.fecha_crea_time'} . "',data_completa2.monto_ultimo_premiodeportivaslive='" . $datum->{'it_transaccion.valor'} . "'
                      
WHERE data_completa2.usuario_id='" . $datum->{'usuario_mandante.usumandante_id'} . "'
";


            $data = $BonoInterno->execQuery($transaccion, $sql2);

            $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
            $transaccion = $BonoDetalleMySqlDAO->getTransaction();


        }


        $sql = "
## ULTIMO APUESTA DEPORTIVAS MIXED

SELECT usuario_mandante.usumandante_id,
       it_transaccion.fecha_crea_time,
       it_transaccion.valor

FROM usuario_mandante

         INNER JOIN
     (SELECT it_transaccion.*
      FROM it_transaccion
               inner join (SELECT MAX(it_transaccion.it_cuentatrans_id) it_cuentatrans_id
                           FROM it_transaccion
                                    INNER JOIN it_ticket_enc on it_transaccion.ticket_id = it_ticket_enc.ticket_id
                           where it_ticket_enc.bet_mode = 'mixed'
                             AND it_transaccion.tipo = 'BET'
                             AND it_transaccion.fecha_crea_time >= '" . $fechaSoloDia . "'
                             AND it_transaccion.fecha_crea_time < '" . $fechaSoloDia2 . "'

                           GROUP BY it_transaccion.usuario_id) a
                          on a.it_cuentatrans_id = it_transaccion.it_cuentatrans_id) it_transaccion
     ON usuario_mandante.usuario_mandante = it_transaccion.usuario_id

";


        $data = $BonoInterno->execQuery($transaccion, $sql);


        foreach ($data as $datum) {
            $sql2 = "

INSERT INTO data_completa2 (usuario_id, fecha_ultima_apuestadeportivasmixed,
                            monto_ultimo_apuestadeportivasmixed)


VALUES ('" . $datum->{'usuario_mandante.usumandante_id'} . "','" . $datum->{'it_transaccion.fecha_crea_time'} . "','" . $datum->{'it_transaccion.valor'} . "')
ON DUPLICATE KEY UPDATE data_completa2.fecha_ultima_apuestadeportivasmixed='" . $datum->{'it_transaccion.fecha_crea_time'} . "',data_completa2.monto_ultimo_apuestadeportivasmixed='" . $datum->{'it_transaccion.valor'} . "'
;
";


            $sql2 = "

UPDATE
data_completa2 SET 
                   
       data_completa2.fecha_ultima_apuestadeportivasmixed='" . $datum->{'it_transaccion.fecha_crea_time'} . "',data_completa2.monto_ultimo_apuestadeportivasmixed='" . $datum->{'it_transaccion.valor'} . "'
                      
WHERE data_completa2.usuario_id='" . $datum->{'usuario_mandante.usumandante_id'} . "'
";


            $data = $BonoInterno->execQuery($transaccion, $sql2);

            $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
            $transaccion = $BonoDetalleMySqlDAO->getTransaction();


        }


        $sql = "
## ULTIMO PREMIO DEPORTIVAS MIXED

SELECT usuario_mandante.usumandante_id,
       it_transaccion.fecha_crea_time,
       it_transaccion.valor

FROM usuario_mandante

         INNER JOIN
     (SELECT it_transaccion.*
      FROM it_transaccion
               inner join (SELECT MAX(it_transaccion.it_cuentatrans_id) it_cuentatrans_id
                           FROM it_transaccion
                                    INNER JOIN it_ticket_enc on it_transaccion.ticket_id = it_ticket_enc.ticket_id
                           where it_ticket_enc.bet_mode = 'mixed'
                             AND it_transaccion.fecha_crea_time >= '" . $fechaSoloDia . "'
                             AND it_transaccion.fecha_crea_time < '" . $fechaSoloDia2 . "'

                           GROUP BY it_transaccion.usuario_id) a
                          on a.it_cuentatrans_id = it_transaccion.it_cuentatrans_id) it_transaccion
     ON usuario_mandante.usuario_mandante = it_transaccion.usuario_id


";


        $data = $BonoInterno->execQuery($transaccion, $sql);


        foreach ($data as $datum) {
            $sql2 = "

INSERT INTO data_completa2 (usuario_id, fecha_ultima_premiodeportivasmixed,
                            monto_ultimo_premiodeportivasmixed)


VALUES ('" . $datum->{'usuario_mandante.usumandante_id'} . "','" . $datum->{'it_transaccion.fecha_crea_time'} . "','" . $datum->{'it_transaccion.valor'} . "')
ON DUPLICATE KEY UPDATE data_completa2.fecha_ultima_premiodeportivasmixed='" . $datum->{'it_transaccion.fecha_crea_time'} . "',data_completa2.monto_ultimo_premiodeportivasmixed='" . $datum->{'it_transaccion.valor'} . "'
;
";


            $sql2 = "

UPDATE
data_completa2 SET 
                   
        data_completa2.fecha_ultima_premiodeportivasmixed='" . $datum->{'it_transaccion.fecha_crea_time'} . "',data_completa2.monto_ultimo_premiodeportivasmixed='" . $datum->{'it_transaccion.valor'} . "'
                      
WHERE data_completa2.usuario_id='" . $datum->{'usuario_mandante.usumandante_id'} . "'
";


            $data = $BonoInterno->execQuery($transaccion, $sql2);

            $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
            $transaccion = $BonoDetalleMySqlDAO->getTransaction();


        }


        $sql = "
## ULTIMO BONO CASINO

SELECT usuario_mandante.usumandante_id,
       bono_log.fecha_crea,
       bono_log.valor

FROM usuario_mandante

         INNER JOIN
     (SELECT bono_log.*
      FROM bono_log
               inner join (SELECT MAX(bono_log.bonolog_id) bonolog_id, usuario_id
                           FROM bono_log
                           WHERE bono_log.tipo IN ('TC', 'TL', 'SC','SCV', 'SL', 'TV', 'FC', 'DC', 'DL', 'DV', 'NC', 'NL', 'NV')
                             and estado = 'L'
                             AND bono_log.fecha_crea >= '" . $fechaSoloDia . "'
                             AND bono_log.fecha_crea < '" . $fechaSoloDia2 . "'
                           GROUP BY usuario_id) a on a.bonolog_id = bono_log.bonolog_id) bono_log
     ON usuario_mandante.usuario_mandante = bono_log.usuario_id

";


        $data = $BonoInterno->execQuery($transaccion, $sql);


        foreach ($data as $datum) {
            $sql2 = "

INSERT INTO data_completa2 (usuario_id, fecha_ultima_bonocasino,
                            monto_ultimo_bonocasino)


VALUES ('" . $datum->{'usuario_mandante.usumandante_id'} . "','" . $datum->{'bono_log.fecha_crea'} . "','" . $datum->{'bono_log.valor'} . "')
ON DUPLICATE KEY UPDATE data_completa2.fecha_ultima_bonocasino='" . $datum->{'bono_log.fecha_crea'} . "',data_completa2.monto_ultimo_bonocasino='" . $datum->{'bono_log.valor'} . "'
;
";
            $sql2 = "

UPDATE
data_completa2 SET 
                   
        data_completa2.fecha_ultima_bonocasino='" . $datum->{'bono_log.fecha_crea'} . "',data_completa2.monto_ultimo_bonocasino='" . $datum->{'bono_log.valor'} . "'
                      
WHERE data_completa2.usuario_id='" . $datum->{'usuario_mandante.usumandante_id'} . "'
";


            $data = $BonoInterno->execQuery($transaccion, $sql2);

            $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
            $transaccion = $BonoDetalleMySqlDAO->getTransaction();


        }


        $sql = "
## ULTIMO BONO DEPORTIVAS


SELECT usuario_mandante.usumandante_id,
       bono_log.fecha_crea,
       bono_log.valor

FROM usuario_mandante

         INNER JOIN
     (SELECT bono_log.*
      FROM bono_log
               inner join (SELECT MAX(bono_log.bonolog_id) bonolog_id, usuario_id
                           FROM bono_log
                           WHERE bono_log.tipo NOT IN ('TC', 'TL', 'SC','SCV', 'SL', 'TV', 'FC', 'DC', 'DL', 'DV', 'NC', 'NL', 'NV', 'JS', 'JL', 'JV', 'JD')
                             and estado = 'L'
                             AND bono_log.fecha_crea >= '" . $fechaSoloDia . "'
                             AND bono_log.fecha_crea < '" . $fechaSoloDia2 . "'
                           GROUP BY usuario_id) a on a.bonolog_id = bono_log.bonolog_id) bono_log
     ON usuario_mandante.usuario_mandante = bono_log.usuario_id




";
        if (!$ConfigurationEnvironment->isDevelopment()) {
            //exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . str_replace("'", '', $sql) . "' '#virtualsoft-cron' > /dev/null & ");

        }


        $data = $BonoInterno->execQuery($transaccion, $sql);


        foreach ($data as $datum) {
            $sql2 = "

INSERT INTO data_completa2 (usuario_id, fecha_ultima_bonodeportivas,
                            monto_ultimo_bonodeportivas)


VALUES ('" . $datum->{'usuario_mandante.usumandante_id'} . "','" . $datum->{'bono_log.fecha_crea'} . "','" . $datum->{'bono_log.valor'} . "')
ON DUPLICATE KEY UPDATE data_completa2.fecha_ultima_bonodeportivas='" . $datum->{'bono_log.fecha_crea'} . "',data_completa2.monto_ultimo_bonodeportivas='" . $datum->{'bono_log.valor'} . "'
;
";
            $sql2 = "

UPDATE
data_completa2 SET 
                   
         data_completa2.fecha_ultima_bonodeportivas='" . $datum->{'bono_log.fecha_crea'} . "',data_completa2.monto_ultimo_bonodeportivas='" . $datum->{'bono_log.valor'} . "'
                      
WHERE data_completa2.usuario_id='" . $datum->{'usuario_mandante.usumandante_id'} . "'
";


            $data = $BonoInterno->execQuery($transaccion, $sql2);

            $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
            $transaccion = $BonoDetalleMySqlDAO->getTransaction();


        }


        $transaccion->commit();

        $message = "*CRON FIN: (cronDATACOMPLETA2) * " . $fechaSoloDia . " - Fecha: " . date("Y-m-d H:i:s");

        if (!$ConfigurationEnvironment->isDevelopment()) {
            exec("php -f " . __DIR__ . "../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }


        $hour = date('H');

        if (intval($hour) == 3) {

            $BonoInterno = new BonoInterno();

            $sqlProcesoInterno2 = "

SELECT * FROM proceso_interno2 WHERE tipo='DEPURACIONUTOKEN'
";


            $data = $BonoInterno->execQuery('', $sqlProcesoInterno2);
            $data = $data[0];
            $line = $data->{'proceso_interno2.fecha_ultima'};

            if ($line == '') {
                unlink($filename);
                exit();
            }


            $fechaL1 = date('Y-m-d H:i:00', strtotime($line . '+1 minute'));
            $fechaL2 = date('Y-m-d 23:59:59', strtotime($line . '+1 minute'));


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
                    unlink($filename);
                    exit();
                }
// The new person to add to the file
                $person = $fechaL1 . "\n";

// Write the contents to the file,
// using the FILE_APPEND flag to append the content to the end of the file
// and the LOCK_EX flag to prevent anyone else writing to the file at the same time
                file_put_contents($file, $person, FILE_APPEND | LOCK_EX);


            }


            if ($fechaL2 >= date('Y-m-d H:i:00', strtotime('-2 days'))) {
                unlink($filename);
                exit();
            }
            $BonoInterno = new BonoInterno();
            $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
            $transaccion = $BonoDetalleMySqlDAO->getTransaction();

            $sqlProcesoInterno2 = "
UPDATE proceso_interno2 SET fecha_ultima='" . $fechaL2 . "' WHERE  tipo='DEPURACIONUTOKEN';
";


            $data = $BonoInterno->execQuery($transaccion, $sqlProcesoInterno2);
            $transaccion->commit();


            $fechaSoloDia = $fechaL1;
            $fechaSoloDia2 = $fechaL2;


            $sql = "
select usutoken_id
from usuario_token
where estado = 'A'
  and fecha_modif <= '" . $fechaL2 . "' 
";

            $data2 = $BonoInterno->execQuery($transaccion, $sql);

            $data = array();

            foreach ($data2 as $datum) {
                array_push($data, $datum->{'usuario_token.usutoken_id'});
            }

            $cont = 0;
            $contG = 0;

            $BonoInterno = new BonoInterno();
            $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
            $transaccion = $BonoDetalleMySqlDAO->getTransaction();
            $dataC = array();


            $message = "*CRON INICIO: (Inactivamos Tokens Usuario) * " . oldCount($data) . " - Fecha: " . date("Y-m-d H:i:s");

            if (!$ConfigurationEnvironment->isDevelopment()) {
                exec("php -f " . __DIR__ . "../src/imports/Slack/message.php '" . $message . "' '#usuario-token-inact' > /dev/null & ");
            }

            foreach ($data as $datum) {
                if ($datum != '') {

                    if ($contG >= 0) {

                        array_push($dataC, $datum);


                        if (($contG % 10000) == 0) {

                        }
                        if ($cont == 10) {

                            $sql2 = "

UPDATE
usuario_token SET 
                   
estado='I'
                   
WHERE usuario_token.usutoken_id IN (" . implode(',', $dataC) . ")
";
                            $data = $BonoInterno->execQuery($transaccion, $sql2);


                            $transaccion->commit();

                            $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
                            $transaccion = $BonoDetalleMySqlDAO->getTransaction();

                            $cont = 0;
                            if (($contG % 1000) == 0) {
                                usleep(500);
                            }
                            $dataC = array();

                        }
                        $cont++;

                    }

                    $contG++;
                }

            }

            if ($cont > 0 && oldCount($dataC) > 0) {

                $sql2 = "

UPDATE
usuario_token SET 
                   
estado='I'
                   
WHERE usuario_token.usutoken_id IN (" . implode(',', $dataC) . ")
";
                //print_r($sql2);
                $data = $BonoInterno->execQuery($transaccion, $sql2);


                $transaccion->commit();

                $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
                $transaccion = $BonoDetalleMySqlDAO->getTransaction();

                $cont = 0;
                if (($contG % 100000) == 0) {
                    sleep(2);
                }
                $dataC = array();


            }

            $transaccion->commit();

            $message = "*CRON FIN: (Inactivamos Tokens Usuario) * " . $fechaSoloDia . " - Fecha: " . date("Y-m-d H:i:s");

            if (!$ConfigurationEnvironment->isDevelopment()) {
                exec("php -f " . __DIR__ . "../src/imports/Slack/message.php '" . $message . "' '#usuario-token-inact' > /dev/null & ");
            }
        }


        /*  $fechaSoloDia = date("Y-m-d H:00:00", strtotime('-1 hour'));

          $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
          $transaccion = $BonoDetalleMySqlDAO->getTransaction();
          $BonoInterno->execQuery($transaccion, "DELETE FROM casino_transprovisional where  fecha_crea < '".$fechaSoloDia."'");


          $transaccion->commit();

          exec("php -f ".__DIR__."../src/imports/Slack/message.php '".'Eliminamos casino_transprovisional'."' '#virtualsoft-cron' > /dev/null & ");*/

    } catch (Exception $e) {
        print_r($e);
    }
    unlink($filename);

    print_r('PROCCESS OK');
    sleep(3);

}