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
use Backend\mysql\BonoDetalleMySqlDAO;




/**
 * Clase 'CronJobResumenesAutoexclusiones'
 *
 *
 *
 *
 * Ejemplo de uso:
 *
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class CronJobResumenesAutoexclusiones
{


    public function __construct()
    {
    }

    public function execute()
    {


        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if (!$ConfigurationEnvironment->isDevelopment()) {
            $message = "*CRON: (Inicio) * " . " ResumenesAutoexclusiones - Fecha:(" . $argv[1] . ") " . date("Y-m-d H:i:s");
            exec("php -f " . __DIR__ . "../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }


        $ResumenesCron = new ResumenesCron();

//$ResumenesCron->generateResumenes();

        $fechaSoloDia = date("Y-m-d H:00:00");
        $fecha1 = date("Y-m-d 00:00:00");
        $fecha2 = date("Y-m-d 23:59:59");

        $hour = date('H');
        if (intval($hour) == 0) {
            sleep(900);
        }
        if ($_REQUEST["diaSpc"] != "") {

            if (!$ConfigurationEnvironment->isDevelopment()) {
                exec("php -f " . __DIR__ . "/resumenesAutoexclusiones.php " . $_REQUEST["diaSpc"] . " > /dev/null &");
            }

            $fechaSoloDia = date("Y-m-d H:00:00", strtotime($_REQUEST["diaSpc"]));
            $fecha1 = date("Y-m-d 00:00:00", strtotime($_REQUEST["diaSpc"]));
            $fecha2 = date("Y-m-d 23:59:59", strtotime($_REQUEST["diaSpc"]));

        } else {
            $arg1 = $argv[1];
            if ($arg1 != "") {
                $fechaSoloDia = date("Y-m-d H:00:00", strtotime($arg1));
                $fecha1 = date("Y-m-d 00:00:00", strtotime($arg1));
                $fecha2 = date("Y-m-d 23:59:59", strtotime($arg1));

            } else {
                //exit();
            }

        }

        try {

//BETWEEN '".$fecha1."' AND '".$fecha2."'

            /* $strEliminado = "DELETE FROM usuario_deporte_resumen WHERE date_format(fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "';
         DELETE FROM usuario_casino_resumen WHERE date_format(fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "';
         DELETE FROM usucasino_detalle_resumen WHERE date_format(fecha_crea, '%Y-%m-%d')= '" . $fechaSoloDia . "';
         DELETE FROM usuario_retiro_resumen WHERE date_format(fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "';
         DELETE FROM usuario_recarga_resumen WHERE date_format(fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "';
         DELETE FROM usuario_bono_resumen WHERE date_format(fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "';
         DELETE FROM usuario_ajustes_resumen WHERE date_format(fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "';
         DELETE FROM usuario_saldo WHERE date_format(fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "';
         ";*/

            /* Recargas*/
            $sqlLIMITEDEPOSITO = "
SELECT SUM(usuario_recarga.valor) valor,
       usuario_configuracion.usuario_id,
       usuario_configuracion.usuconfig_id,
       date_format(DATE_SUB(
                           DATE_SUB('" . $fechaSoloDia . "', INTERVAL
                                    MOD(TIMESTAMPDIFF(SECOND, usuario_configuracion.fecha_modif, '" . $fechaSoloDia . "'), 3600)
                                    SECOND), INTERVAL 3600 SECOND), '%Y-%m-%d %H:%i:%s') fecha,
                                    0 usucrea_id,
                                    0 usumodif_id,
                                    'A' estado
FROM usuario_recarga
         INNER JOIN usuario_configuracion ON (usuario_recarga.usuario_id = usuario_configuracion.usuario_id)
         INNER JOIN clasificador ON (clasificador.clasificador_id = usuario_configuracion.tipo)

WHERE clasificador.abreviado IN ('LIMITEDEPOSITODIARIO','LIMITEDEPOSITOSEMANA','LIMITEDEPOSITOMENSUAL','LIMITEDEPOSITOANUAL') AND usuario_configuracion.estado='A' AND date_format(usuario_recarga.fecha_crea, '%Y-%m-%d %H:%i:%s') >=
      date_format(DATE_SUB(
                          DATE_SUB('" . $fechaSoloDia . "', INTERVAL
                                   MOD(TIMESTAMPDIFF(SECOND, usuario_configuracion.fecha_modif, '" . $fechaSoloDia . "'), 3600)
                                   SECOND), INTERVAL 3600 SECOND), '%Y-%m-%d %H:%i:%s')
  AND date_format(usuario_recarga.fecha_crea, '%Y-%m-%d %H:%i:%s') <
      date_format(DATE_SUB(
                          DATE_SUB('" . $fechaSoloDia . "', INTERVAL
                                   MOD(TIMESTAMPDIFF(SECOND, usuario_configuracion.fecha_modif, '" . $fechaSoloDia . "'), 3600)
                                   SECOND), INTERVAL 0 SECOND), '%Y-%m-%d %H:%i:%s')
                                   group by usuario_configuracion.usuario_id
  ";

            $sqlLIMITEDEPOSITOELIMINADO = "
SELECT SUM(-usuario_recarga.valor) valor,
       usuario_configuracion.usuario_id,
       usuario_configuracion.usuconfig_id,
       date_format(DATE_SUB(
                           DATE_SUB('" . $fechaSoloDia . "', INTERVAL
                                    MOD(TIMESTAMPDIFF(SECOND, usuario_configuracion.fecha_modif, '" . $fechaSoloDia . "'), 3600)
                                    SECOND), INTERVAL 3600 SECOND), '%Y-%m-%d %H:%i:%s') fecha,
                                    0 usucrea_id,
                                    0 usumodif_id,
                                    'A' estado
FROM usuario_recarga
         INNER JOIN usuario_configuracion ON (usuario_recarga.usuario_id = usuario_configuracion.usuario_id)
         INNER JOIN clasificador ON (clasificador.clasificador_id = usuario_configuracion.tipo)

WHERE clasificador.abreviado IN ('LIMITEDEPOSITODIARIO','LIMITEDEPOSITOSEMANA','LIMITEDEPOSITOMENSUAL','LIMITEDEPOSITOANUAL') AND usuario_configuracion.estado='A' AND usuario_recarga.estado='I' AND date_format(usuario_recarga.fecha_elimina, '%Y-%m-%d %H:%i:%s') >=
      date_format(DATE_SUB(
                          DATE_SUB('" . $fechaSoloDia . "', INTERVAL
                                   MOD(TIMESTAMPDIFF(SECOND, usuario_configuracion.fecha_modif, '" . $fechaSoloDia . "'), 3600)
                                   SECOND), INTERVAL 3600 SECOND), '%Y-%m-%d %H:%i:%s')
  AND date_format(usuario_recarga.fecha_elimina, '%Y-%m-%d %H:%i:%s') <
      date_format(DATE_SUB(
                          DATE_SUB('" . $fechaSoloDia . "', INTERVAL
                                   MOD(TIMESTAMPDIFF(SECOND, usuario_configuracion.fecha_modif, '" . $fechaSoloDia . "'), 3600)
                                   SECOND), INTERVAL 0 SECOND), '%Y-%m-%d %H:%i:%s')
                                   group by usuario_configuracion.usuario_id
  ";


            $sqlLIMITEDEPOSITOMANDANTE = "
SELECT SUM(usuario_recarga.valor) valor,
       usuario.usuario_id,
       mandante_detalle.manddetalle_id,
       date_format(DATE_SUB('" . $fechaSoloDia . "', INTERVAL 3600 SECOND), '%Y-%m-%d %H:%i:%s') fecha,
                                    0 usucrea_id,
                                    0 usumodif_id,
                                    'A' estado
FROM usuario_recarga
         INNER JOIN usuario ON (usuario_recarga.usuario_id = usuario.usuario_id)
         INNER JOIN mandante_detalle ON (mandante_detalle.mandante = usuario.mandante)
         INNER JOIN clasificador ON (clasificador.clasificador_id = mandante_detalle.tipo AND mandante_detalle.pais_id=usuario.pais_id)

WHERE clasificador.abreviado IN ('LIMITEDEPOSITODIARIODEFT','LIMITEDEPOSITOSEMANADEFT','LIMITEDEPOSITOMENSUALDEFT','LIMITEDEPOSITOANUALDEFT','LIMITEDEPOSITODIARIOGLOBAL','LIMITEDEPOSITOSEMANAGLOBAL','LIMITEDEPOSITOMENSUALGLOBAL','LIMITEDEPOSITOANUALGLOBAL') AND mandante_detalle.estado='A'  AND date_format(usuario_recarga.fecha_crea, '%Y-%m-%d %H:%i:%s') >=
      date_format(DATE_SUB('" . $fechaSoloDia . "', INTERVAL 3600 SECOND), '%Y-%m-%d %H:%i:%s')
  AND date_format(usuario_recarga.fecha_crea, '%Y-%m-%d %H:%i:%s') <
      date_format(DATE_SUB('" . $fechaSoloDia . "', INTERVAL 0 SECOND), '%Y-%m-%d %H:%i:%s')
                                   group by usuario_recarga.usuario_id";

            $sqlLIMITEDEPOSITOELIMINADOMANDANTE = "
SELECT SUM(-usuario_recarga.valor) valor,
       usuario.usuario_id,
       mandante_detalle.manddetalle_id,
       date_format(DATE_SUB('" . $fechaSoloDia . "', INTERVAL 3600 SECOND), '%Y-%m-%d %H:%i:%s') fecha,

                                    0 usucrea_id,
                                    0 usumodif_id,
                                    'A' estado
FROM usuario_recarga
         INNER JOIN usuario ON (usuario_recarga.usuario_id = usuario.usuario_id)
         INNER JOIN mandante_detalle ON (mandante_detalle.mandante = usuario.mandante)
         INNER JOIN clasificador ON (clasificador.clasificador_id = mandante_detalle.tipo AND mandante_detalle.pais_id=usuario.pais_id)

WHERE clasificador.abreviado IN ('LIMITEDEPOSITODIARIODEFT','LIMITEDEPOSITOSEMANADEFT','LIMITEDEPOSITOMENSUALDEFT','LIMITEDEPOSITOANUALDEFT','LIMITEDEPOSITODIARIOGLOBAL','LIMITEDEPOSITOSEMANAGLOBAL','LIMITEDEPOSITOMENSUALGLOBAL','LIMITEDEPOSITOANUALGLOBAL') AND mandante_detalle.estado='A' AND usuario_recarga.estado='I'   AND date_format(usuario_recarga.fecha_elimina, '%Y-%m-%d %H:%i:%s') >=
      date_format(DATE_SUB('" . $fechaSoloDia . "', INTERVAL 3600 SECOND), '%Y-%m-%d %H:%i:%s')
  AND date_format(usuario_recarga.fecha_elimina, '%Y-%m-%d %H:%i:%s') <
      date_format(DATE_SUB('" . $fechaSoloDia . "', INTERVAL 0 SECOND), '%Y-%m-%d %H:%i:%s')
                                   group by usuario_recarga.usuario_id
  ";

            $sqlLIMITEAPUESTASDEPORTIVAS = "
SELECT SUM(CASE
               WHEN it_transaccion.tipo IN ('BET') THEN it_transaccion.valor
               WHEN it_transaccion.tipo IN ('STAKEDECREASE', 'REFUND') THEN -it_transaccion.valor
               ELSE 0 END)                                                               valor,
       usuario_configuracion.usuario_id,
       usuario_configuracion.usuconfig_id,
       date_format(DATE_SUB(
                           DATE_SUB('" . $fechaSoloDia . "', INTERVAL
                                    MOD(TIMESTAMPDIFF(SECOND, usuario_configuracion.fecha_modif, '" . $fechaSoloDia . "'), 3600)
                                    SECOND), INTERVAL 3600 SECOND), '%Y-%m-%d %H:%i:%s') fecha,
       0                                                                                 usucrea_id,
       0                                                                                 usumodif_id,
       'A'                                                                               estado
FROM it_transaccion
         INNER JOIN usuario_configuracion ON (it_transaccion.usuario_id = usuario_configuracion.usuario_id)
         INNER JOIN clasificador ON (clasificador.clasificador_id = usuario_configuracion.tipo)

WHERE clasificador.abreviado IN
      ('LIMAPUDEPORTIVADIARIO', 'LIMAPUDEPORTIVASEMANA', 'LIMAPUDEPORTIVAMENSUAL', 'LIMAPUDEPORTIVAANUAL')
  AND date_format(CONCAT(it_transaccion.fecha_crea, ' ', it_transaccion.hora_crea), '%Y-%m-%d %H:%i:%s') >=
      date_format(DATE_SUB(
                          DATE_SUB('" . $fechaSoloDia . "', INTERVAL
                                   MOD(TIMESTAMPDIFF(SECOND, usuario_configuracion.fecha_modif, '" . $fechaSoloDia . "'), 3600)
                                   SECOND), INTERVAL 3600 SECOND), '%Y-%m-%d %H:%i:%s')
  AND date_format(CONCAT(it_transaccion.fecha_crea, ' ', it_transaccion.hora_crea), '%Y-%m-%d %H:%i:%s') <
      date_format(DATE_SUB(
                          DATE_SUB('" . $fechaSoloDia . "', INTERVAL
                                   MOD(TIMESTAMPDIFF(SECOND, usuario_configuracion.fecha_modif, '" . $fechaSoloDia . "'), 3600)
                                   SECOND), INTERVAL 0 SECOND), '%Y-%m-%d %H:%i:%s')
                                   group by usuario_configuracion.usuario_id
                                     ";


            $sqlLIMITECASINO = "
SELECT SUM(CASE
               WHEN transjuego_log.tipo LIKE ('%DEBIT%') THEN transjuego_log.valor
               WHEN transjuego_log.tipo LIKE ('%ROLLBACK%') THEN transjuego_log.valor
               ELSE 0 END)                                                               valor,
       usuario_configuracion.usuario_id,
       usuario_configuracion.usuconfig_id,
       date_format(DATE_SUB(
                           DATE_SUB('" . $fechaSoloDia . "', INTERVAL
                                    MOD(TIMESTAMPDIFF(SECOND, usuario_configuracion.fecha_modif, '" . $fechaSoloDia . "'), 3600)
                                    SECOND), INTERVAL 3600 SECOND), '%Y-%m-%d %H:%i:%s') fecha,
       0                                                                                 usucrea_id,
       0                                                                                 usumodif_id,
       'A'                                                                               estado
FROM transjuego_log
         INNER JOIN transaccion_juego ON (transaccion_juego.transjuego_id = transjuego_log.transjuego_id)
         INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usuario_mandante.usumandante_id)
         INNER JOIN producto_mandante ON (producto_mandante.prodmandante_id = transaccion_juego.producto_id)
         INNER JOIN producto ON (producto.producto_id = producto_mandante.producto_id)
         INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id)
         INNER JOIN usuario_configuracion ON (usuario_mandante.usuario_mandante = usuario_configuracion.usuario_id)
         INNER JOIN clasificador ON (clasificador.clasificador_id = usuario_configuracion.tipo)

WHERE clasificador.abreviado IN
      ('LIMAPUCASINODIARIO', 'LIMAPUCASINOSEMANA', 'LIMAPUCASINOMENSUAL', 'LIMAPUCASINOANUAL') AND proveedor.tipo ='CASINO'
  AND transjuego_log.fecha_crea >=
      date_format(DATE_SUB(
                          DATE_SUB('" . $fechaSoloDia . "', INTERVAL
                                   MOD(TIMESTAMPDIFF(SECOND, usuario_configuracion.fecha_modif, '" . $fechaSoloDia . "'), 3600)
                                   SECOND), INTERVAL 3600 SECOND), '%Y-%m-%d %H:%i:%s')
  AND transjuego_log.fecha_crea <
      date_format(DATE_SUB(
                          DATE_SUB('" . $fechaSoloDia . "', INTERVAL
                                   MOD(TIMESTAMPDIFF(SECOND, usuario_configuracion.fecha_modif, '" . $fechaSoloDia . "'), 3600)
                                   SECOND), INTERVAL 0 SECOND), '%Y-%m-%d %H:%i:%s')
                                   group by usuario_configuracion.usuario_id
                                     ";


            $sqlLIMITECASINOVIVO = "
SELECT SUM(CASE
               WHEN transjuego_log.tipo LIKE ('%DEBIT%') THEN transjuego_log.valor
               WHEN transjuego_log.tipo LIKE ('%ROLLBACK%') THEN transjuego_log.valor
               ELSE 0 END)                                                               valor,
       usuario_configuracion.usuario_id,
       usuario_configuracion.usuconfig_id,
       date_format(DATE_SUB(
                           DATE_SUB('" . $fechaSoloDia . "', INTERVAL
                                    MOD(TIMESTAMPDIFF(SECOND, usuario_configuracion.fecha_modif, '" . $fechaSoloDia . "'), 3600)
                                    SECOND), INTERVAL 3600 SECOND), '%Y-%m-%d %H:%i:%s') fecha,
       0                                                                                 usucrea_id,
       0                                                                                 usumodif_id,
       'A'                                                                               estado
FROM transjuego_log
         INNER JOIN transaccion_juego ON (transaccion_juego.transjuego_id = transjuego_log.transjuego_id)
         INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usuario_mandante.usumandante_id)
         INNER JOIN producto_mandante ON (producto_mandante.prodmandante_id = transaccion_juego.producto_id)
         INNER JOIN producto ON (producto.producto_id = producto_mandante.producto_id)
         INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id)
         INNER JOIN usuario_configuracion ON (usuario_mandante.usuario_mandante = usuario_configuracion.usuario_id)
         INNER JOIN clasificador ON (clasificador.clasificador_id = usuario_configuracion.tipo)

WHERE clasificador.abreviado IN
      ('LIMAPUCASINOVIVODIARIO', 'LIMAPUCASINOVIVOSEMANA', 'LIMAPUCASINOVIVOMENSUAL', 'LIMAPUCASINOVIVOANUAL') AND proveedor.tipo ='LIVECASINO'
  AND transjuego_log.fecha_crea >=
      date_format(DATE_SUB(
                          DATE_SUB('" . $fechaSoloDia . "', INTERVAL
                                   MOD(TIMESTAMPDIFF(SECOND, usuario_configuracion.fecha_modif, '" . $fechaSoloDia . "'), 3600)
                                   SECOND), INTERVAL 3600 SECOND), '%Y-%m-%d %H:%i:%s')
  AND transjuego_log.fecha_crea <
      date_format(DATE_SUB(
                          DATE_SUB('" . $fechaSoloDia . "', INTERVAL
                                   MOD(TIMESTAMPDIFF(SECOND, usuario_configuracion.fecha_modif, '" . $fechaSoloDia . "'), 3600)
                                   SECOND), INTERVAL 0 SECOND), '%Y-%m-%d %H:%i:%s')
                                   group by usuario_configuracion.usuario_id
                                     ";

            $log = "\r\n" . "-------------------------" . "\r\n";
            $log = $log . "Inicia: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
            fwriteCustom('log_' . date("Y-m-d") . '.log', $log);


            $paso = true;

            $BonoInterno = new BonoInterno();
            $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
            $transaccion = $BonoDetalleMySqlDAO->getTransaction();

            $BonoInterno = new BonoInterno();
            $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
            $transaccion = $BonoDetalleMySqlDAO->getTransaction();
            $transaccion->getConnection()->beginTransaction();


            $data = $BonoInterno->execQuery('', $sqlLIMITEDEPOSITO);
            foreach ($data as $datanum) {
                if ($datanum->{'usuario_configuracion.usuario_id'} != '') {
                    $sql = "INSERT INTO usuario_configuracion_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, usuconfig_id)
              VALUES ('" . $datanum->{'usuario_configuracion.usuario_id'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'.fecha'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'.usumodif_id'} . "','" . $datanum->{'.estado'} . "','" . $datanum->{'usuario_configuracion.usuconfig_id'} . "')";
                    $BonoInterno->execQuery($transaccion, $sql);

                }
            }

            $data = $BonoInterno->execQuery('', $sqlLIMITEDEPOSITOELIMINADO);
            foreach ($data as $datanum) {
                if ($datanum->{'usuario_configuracion.usuario_id'} != '') {
                    $sql = "INSERT INTO usuario_configuracion_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, usuconfig_id)
              VALUES ('" . $datanum->{'usuario_configuracion.usuario_id'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'.fecha'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'.usumodif_id'} . "','" . $datanum->{'.estado'} . "','" . $datanum->{'usuario_configuracion.usuconfig_id'} . "')";
                    $BonoInterno->execQuery($transaccion, $sql);

                }
            }


            $data = $BonoInterno->execQuery('', $sqlLIMITEDEPOSITOMANDANTE);


            foreach ($data as $datanum) {
                if ($datanum->{'usuario.usuario_id'} != '') {
                    $sql = "INSERT INTO usuario_configuracion_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, manddetalle_id)
              VALUES ('" . $datanum->{'usuario.usuario_id'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'.fecha'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'.usumodif_id'} . "','" . $datanum->{'.estado'} . "','" . $datanum->{'mandante_detalle.manddetalle_id'} . "')";
                    $BonoInterno->execQuery($transaccion, $sql);

                }
            }

            $data = $BonoInterno->execQuery('', $sqlLIMITEDEPOSITOELIMINADOMANDANTE);
            foreach ($data as $datanum) {
                if ($datanum->{'usuario.usuario_id'} != '') {
                    $sql = "INSERT INTO usuario_configuracion_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, manddetalle_id)
              VALUES ('" . $datanum->{'usuario.usuario_id'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'.fecha'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'.usumodif_id'} . "','" . $datanum->{'.estado'} . "','" . $datanum->{'mandante_detalle.manddetalle_id'} . "')";
                    $BonoInterno->execQuery($transaccion, $sql);

                }
            }


            $data = $BonoInterno->execQuery('', $sqlLIMITEAPUESTASDEPORTIVAS);
            foreach ($data as $datanum) {
                if ($datanum->{'usuario_configuracion.usuario_id'} != '') {
                    $sql = "INSERT INTO usuario_configuracion_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, usuconfig_id)
              VALUES ('" . $datanum->{'usuario_configuracion.usuario_id'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'.fecha'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'.usumodif_id'} . "','" . $datanum->{'.estado'} . "','" . $datanum->{'usuario_configuracion.usuconfig_id'} . "')";
                    $BonoInterno->execQuery($transaccion, $sql);

                }
            }


            $data = $BonoInterno->execQuery('', $sqlLIMITECASINO);
            foreach ($data as $datanum) {
                if ($datanum->{'usuario_configuracion.usuario_id'} != '') {
                    $sql = "INSERT INTO usuario_configuracion_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, usuconfig_id)
              VALUES ('" . $datanum->{'usuario_configuracion.usuario_id'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'.fecha'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'.usumodif_id'} . "','" . $datanum->{'.estado'} . "','" . $datanum->{'usuario_configuracion.usuconfig_id'} . "')";
                    $BonoInterno->execQuery($transaccion, $sql);

                }
            }


            $data = $BonoInterno->execQuery('', $sqlLIMITECASINOVIVO);
            foreach ($data as $datanum) {
                if ($datanum->{'usuario_configuracion.usuario_id'} != '') {
                    $sql = "INSERT INTO usuario_configuracion_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, usuconfig_id)
              VALUES ('" . $datanum->{'usuario_configuracion.usuario_id'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'.fecha'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'.usumodif_id'} . "','" . $datanum->{'.estado'} . "','" . $datanum->{'usuario_configuracion.usuconfig_id'} . "')";
                    $BonoInterno->execQuery($transaccion, $sql);

                }
            }

            $transaccion->commit();

            $log = "\r\n" . "-------------------------" . "\r\n";
            $log = $log . "Terminacion: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
            fwriteCustom('log_' . date("Y-m-d") . '.log', $log);


            if (!$ConfigurationEnvironment->isDevelopment()) {
                $message = "*CRON: (Fin) * " . " ResumenesAutoexclusiones - Fecha: " . date("Y-m-d H:i:s");

                exec("php -f " . __DIR__ . "../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");
            }


        } catch
        (Exception $e) {
            print_r($e);
            $log = "\r\n" . "-------------------------" . "\r\n";
            $log = $log . "ERROR: " . $e->getMessage() . " - " . date('Y-m-d H:i:s');
            fwriteCustom('log_' . date("Y-m-d") . '.log', $log);


            if (!$ConfigurationEnvironment->isDevelopment()) {
                $message = "*CRON: (ERROR) * " . " ResumenesAutoexclusiones - Fecha: " . date("Y-m-d H:i:s");

                exec("php -f " . __DIR__ . "../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");
            }

        }

    }
}