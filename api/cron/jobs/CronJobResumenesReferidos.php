<?php


use Backend\sql\Transaction;
use Backend\dto\BonoInterno;
use Backend\dto\UsuarioReferenteResumen;
use Backend\mysql\UsuarioReferenteResumenMySqlDAO;



/**
 * Clase 'CronJobResumenesReferidos'
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
class CronJobResumenesReferidos
{


    public function __construct()
    {
    }

    public function execute()
    {


///home/devadmin/api/api/
        $hour = date('H');
        if (intval($hour) > 9) {
            // exit();
        }

        ini_set('memory_limit', '-1');

        $message = "*CRON: (Inicio) * " . " ResumenesReferidos - Fecha: " . date("Y-m-d H:i:s");

        exec("php -f " . __DIR__ . "/../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        $fechaSoloDia = date("Y-m-d", strtotime('-1 days'));
        $fecha1 = date("Y-m-d 00:00:00", strtotime('-1 days'));
        $fecha2 = date("Y-m-d 23:59:59", strtotime('-1 days'));


        if ($_REQUEST["diaSpc"] != "") {
            exit();

            exec("php -f " . __DIR__ . "/resumenes.php " . $_REQUEST["diaSpc"] . " > /dev/null &");

            $fechaSoloDia = date("Y-m-d", strtotime($_REQUEST["diaSpc"]));
            $fecha1 = date("Y-m-d 00:00:00", strtotime($_REQUEST["diaSpc"]));
            $fecha2 = date("Y-m-d 23:59:59", strtotime($_REQUEST["diaSpc"]));

        } else {
            $arg1 = $argv[1];
            if ($arg1 != "") {
                $fechaSoloDia = date("Y-m-d", strtotime($arg1));
                $fecha1 = date("Y-m-d 00:00:00", strtotime($arg1));
                $fecha2 = date("Y-m-d 23:59:59", strtotime($arg1));

            } else {
                //exit();
            }
        }

        /** Verificando última ejecución del cron */
        $Transaction = new Transaction();
        $BonoInterno = new BonoInterno();
        $sql = "SELECT fecha_ultima FROM proceso_interno2 WHERE tipo = 'REFERIDO_RESUMEN'";
        $lastExecution = $BonoInterno->execQuery($Transaction, $sql);
        $lastExecution = $lastExecution[0]->{'proceso_interno2.fecha_ultima'};
        $lastExecutionDate = date('Y-m-d', strtotime($lastExecution . ' -1 day'));

        if ($fechaSoloDia <= $lastExecutionDate) die;

        /** Registrando que el cron SI se va a ejecutar y el intervalo de data que analizó*/
        $message = "*CRON: (Inicio) * " . " ResumenesReferidos - Intervalo ejecucion: " . $fecha1 . '__' . $fecha2;
        exec("php -f " . __DIR__ . "/../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");


        /** Marcando última ejecución del CRON */
        $Transaction = new Transaction();
        $BonoInterno = new BonoInterno();

        $sql = "UPDATE proceso_interno2 SET fecha_ultima = now() WHERE  tipo='REFERIDO_RESUMEN'";
        $data = $BonoInterno->execQuery($Transaction, $sql);
        $Transaction->commit();

#Tipo: NEWREFERRED
        try {
            $Transaction = new Transaction();
            $sql = "select referente.usuario_id, count(referido.usuario_id) totalNewReferreds from usuario_otrainfo as referido_otrainfo inner join usuario as referido on (referido_otrainfo.usuario_id = referido.usuario_id) inner join usuario as referente on (referido_otrainfo.usuid_referente = referente.usuario_id) where referido_otrainfo.usuid_referente is not null and referido_otrainfo.usuid_referente != '' and referido.fecha_crea between '" . $fecha1 . "' and '" . $fecha2 . "' group by referente.usuario_id";
            $BonoInterno = new BonoInterno();
            $totalNewReferreds = $BonoInterno->execQuery($Transaction, $sql);
            $UsuarioReferenteResumenMySqlDAO = new UsuarioReferenteResumenMySqlDAO($Transaction);
            foreach ($totalNewReferreds as $totalByReferent) {
                //Iterando y almacenando los acumulados de nuevos referidos para cada referente
                $UsuarioReferenteResumen = new UsuarioReferenteResumen();
                $UsuarioReferenteResumen->tipoUsuario = 'REFERENTE';
                $UsuarioReferenteResumen->usuarioId = $totalByReferent->{'referente.usuario_id'};
                $UsuarioReferenteResumen->valor = $totalByReferent->{'.totalNewReferreds'};
                $UsuarioReferenteResumen->usucreaId = 0;
                $UsuarioReferenteResumen->usumodifId = 0;
                $UsuarioReferenteResumen->tipo = 'NEWREFERRED';

                $UsuarioReferenteResumenMySqlDAO->insert($UsuarioReferenteResumen);
            }
            $Transaction->commit();
        } catch (Exception $e) {
        }
#Fin tipo: NEWREFERRED


#Tipo: SUCCESSFULREFERRED
        try {
            $Transaction = new Transaction();
            $sql = "select referente.usuario_id, count(premio.duplaPremio) as premiosTotales from (select concat(usuid_referido, '_', tipo_premio) as duplaPremio, logro_referido.usuid_referente as usuidreferente from usuario_otrainfo as referido inner join logro_referido on (referido.usuario_id = logro_referido.usuid_referido) where logro_referido.fecha_uso between '" . $fecha1 . "' and '" . $fecha2 . "' and estado_grupal in ('R', 'C', 'PE') group by usuid_referido, tipo_premio) as premio inner join usuario as referente on (premio.usuidreferente = referente.usuario_id)group by referente.usuario_id";
            $BonoInterno = new BonoInterno();
            $totalSuccessfulReferreds = $BonoInterno->execQuery($Transaction, $sql);
            $UsuarioReferenteResumenMySqlDAO = new UsuarioReferenteResumenMySqlDAO($Transaction);
            foreach ($totalSuccessfulReferreds as $successByReferent) {
                //Iterando y almacenando los acumulados de nuevos premios para cada referente
                $UsuarioReferenteResumen = new UsuarioReferenteResumen();
                $UsuarioReferenteResumen->tipoUsuario = 'REFERENTE';
                $UsuarioReferenteResumen->usuarioId = $successByReferent->{'referente.usuario_id'};
                $UsuarioReferenteResumen->valor = $successByReferent->{'.premiosTotales'};
                $UsuarioReferenteResumen->usucreaId = 0;
                $UsuarioReferenteResumen->usumodifId = 0;
                $UsuarioReferenteResumen->tipo = 'SUCCESSFULREFERRED';

                $UsuarioReferenteResumenMySqlDAO->insert($UsuarioReferenteResumen);
            }
            $Transaction->commit();
        } catch (Exception $e) {
        }
#Fin tipo: SUCCESSFULREFERRED


#Tipo: NEWSUCCESSFULREFERRED
        try {
            $Transaction = new Transaction();
            $sql = "select count(*) totalNuevosPremios, usuario_otrainfo.usuid_referente from (select min(fecha_uso) min_fecha_uso, usuid_referido,referido.usuid_referente, tipo_premio, valor_premio, abreviado from usuario_otrainfo as referido inner join logro_referido on (referido.usuario_id = logro_referido.usuid_referido) inner join mandante_detalle on (mandante_detalle.manddetalle_id = logro_referido.tipo_condicion) inner join clasificador on (mandante_detalle.tipo = clasificador.clasificador_id) where estado_grupal in ('R', 'C', 'PE') group by usuid_referido having min_fecha_uso between '" . $fecha1 . "' and '" . $fecha2 . "') as nuevoPremio inner join usuario_otrainfo on (nuevoPremio.usuid_referido = usuario_otrainfo.usuario_id) group by usuario_otrainfo.usuid_referente";
            $BonoInterno = new BonoInterno();
            $newSuccessFulReferred = $BonoInterno->execQuery($Transaction, $sql);
            $UsuarioReferenteResumenMySqlDAO = new UsuarioReferenteResumenMySqlDAO($Transaction);
            foreach ($newSuccessFulReferred as $successByReferent) {
                //Iterando y almacenando los acumulados de nuevos premios para cada referente
                $UsuarioReferenteResumen = new UsuarioReferenteResumen();
                $UsuarioReferenteResumen->tipoUsuario = 'REFERENTE';
                $UsuarioReferenteResumen->usuarioId = $successByReferent->{'usuario_otrainfo.usuid_referente'};
                $UsuarioReferenteResumen->valor = $successByReferent->{'.totalNuevosPremios'};
                $UsuarioReferenteResumen->usucreaId = 0;
                $UsuarioReferenteResumen->usumodifId = 0;
                $UsuarioReferenteResumen->tipo = 'NEWSUCCESSFULREFERRED';

                $UsuarioReferenteResumenMySqlDAO->insert($UsuarioReferenteResumen);
            }
            $Transaction->commit();
        } catch (Exception $e) {
        }
#Fin tipo: NEWSUCCESSFULREFERRED


#tipo:SUCCESSFULCONDITION
        try {
            $Transaction = new Transaction();
            $sql = "select logro_referido.usuid_referente, abreviado, count(*) as totalCondicion from usuario_otrainfo as referido inner join logro_referido on (referido.usuario_id = logro_referido.usuid_referido) inner join mandante_detalle on (mandante_detalle.manddetalle_id = logro_referido.tipo_condicion) inner join clasificador on (mandante_detalle.tipo = clasificador.clasificador_id) where logro_referido.fecha_uso between '" . $fecha1 . "' and '" . $fecha2 . "' and estado_grupal in ('R', 'C', 'PE') group by logro_referido.usuid_referente, abreviado";
            $BonoInterno = new BonoInterno();
            $newSuccessfulConditions = $BonoInterno->execQuery($Transaction, $sql);
            $UsuarioReferenteResumenMySqlDAO = new UsuarioReferenteResumenMySqlDAO($Transaction);
            foreach ($newSuccessfulConditions as $successByReferentAndCondition) {
                //Iterando y almacenando los acumulados de nuevos premios para cada referente
                $UsuarioReferenteResumen = new UsuarioReferenteResumen();
                $UsuarioReferenteResumen->tipoUsuario = 'REFERENTE';
                $UsuarioReferenteResumen->usuarioId = $successByReferentAndCondition->{'logro_referido.usuid_referente'};
                $UsuarioReferenteResumen->valor = $successByReferentAndCondition->{'.totalCondicion'};
                $UsuarioReferenteResumen->usucreaId = 0;
                $UsuarioReferenteResumen->usumodifId = 0;
                $UsuarioReferenteResumen->tipo = 'SUCCESSFULCONDITION';
                $UsuarioReferenteResumen->tipoCondicion = $successByReferentAndCondition->{'clasificador.abreviado'};

                $UsuarioReferenteResumenMySqlDAO->insert($UsuarioReferenteResumen);
            }
            $Transaction->commit();
        } catch (Exception $e) {
        }
#Fin tipo:SUCCESSFULCONDITION


#Tipo: SUCCESSFULREDEEMEDAWARD

        try {
            $Transaction = new Transaction();
            $sql = "select referente.usuario_id, count(premioPorReferido.usuid_referente) as premiosRedimidos from (select logro_referido.usuid_referente, usuid_referido, tipo_premio from logro_referido inner join usuario_otrainfo on (logro_referido.usuid_referente = usuario_otrainfo.usuid_referente) where logro_referido.fecha_modif between '" . $fecha1 . "' and '" . $fecha2 . "' and estado_grupal in ('R') group by usuid_referido, tipo_premio) as premioPorReferido inner join usuario as referente on (premioPorReferido.usuid_referente = referente.usuario_id) group by referente.usuario_id";

            $BonoInterno = new BonoInterno();
            $totalSuccessfulRedeemedAwards = $BonoInterno->execQuery($Transaction, $sql);

            $UsuarioReferenteResumenMySqlDAO = new UsuarioReferenteResumenMySqlDAO($Transaction);
            foreach ($totalSuccessfulRedeemedAwards as $awardsByReferent) {
                //Iterando y almacenando los acumulados de nuevos premios para cada referente
                $UsuarioReferenteResumen = new UsuarioReferenteResumen();
                $UsuarioReferenteResumen->tipoUsuario = 'REFERENTE';
                $UsuarioReferenteResumen->usuarioId = $awardsByReferent->{'referente.usuario_id'};
                $UsuarioReferenteResumen->valor = $awardsByReferent->{'.premiosRedimidos'};
                $UsuarioReferenteResumen->usucreaId = 0;
                $UsuarioReferenteResumen->usumodifId = 0;
                $UsuarioReferenteResumen->tipo = 'SUCCESSFULREDEEMEDAWARD';

                $UsuarioReferenteResumenMySqlDAO->insert($UsuarioReferenteResumen);
            }

            $Transaction->commit();
        } catch (Exception $e) {
        }
#Fin tipo: SUCCESSFULREDEEMEDAWARD

    }
}