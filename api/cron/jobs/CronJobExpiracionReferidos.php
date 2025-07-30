<?php

use Backend\sql\Transaction;
use Backend\dto\BonoInterno;
use Backend\dto\LogroReferido;



/**
 * Clase 'CronJobExpiracionReferidos'
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
class CronJobExpiracionReferidos
{


    public function __construct()
    {
    }

    public function execute()
    {


        $initialTransaction = new Transaction();
        $BonoInterno = new BonoInterno();
        $sql = "select fecha_ultima from proceso_interno2 where tipo = 'REFERIDO_EXPIRACION'";

        $procesoInternoData = $BonoInterno->execQuery($initialTransaction, $sql);
        $lastExecution = $procesoInternoData[0]->{'proceso_interno2.fecha_ultima'};
        $lastExecutionDay = date('Y-m-d', strtotime($lastExecution));
        $currentDay = date('Y-m-d');
        $currentDayWithTime = date('Y-m-d H:i:s');

        if ($lastExecutionDay >= $currentDay) die;

        $sql = "update proceso_interno2 set fecha_ultima = '" . $currentDayWithTime . "' where tipo = 'REFERIDO_EXPIRACION'";
        $BonoInterno->execUpdate($initialTransaction, $sql);
        $initialTransaction->commit();

        /** Expirando condiciones */
        try {
            $conditionsTransaction = new Transaction();
            $sqlExpiredConditions = "select CondsExpiradas.mandante, CondsExpiradas.pais_id, CondsExpiradas.usuario_id, CondsExpiradas.tipo_premio
                            from (select referido.usuario_id, logro_referido.tipo_premio, referido.mandante, referido.pais_id
                            from logro_referido
                            inner join usuario as referido on (logro_referido.usuid_referido = referido.usuario_id)
                            where logro_referido.estado_grupal = 'P'
                            and logro_referido.fecha_expira is not null
                            and logro_referido.fecha_expira < now()
                            group by referido.usuario_id, logro_referido.tipo_premio) as CondsExpiradas
                            order by CondsExpiradas.mandante asc, CondsExpiradas.pais_id desc, CondsExpiradas.usuario_id asc";

            $expiredConditions = $BonoInterno->execQuery($conditionsTransaction, $sqlExpiredConditions);

            $currentDate = date('Y-m-d H:i:s');
            $LogroReferido = new LogroReferido();
            $estadoGlobal = null;
            $contSql = 0;
            $strSql = [];
            foreach ($expiredConditions as $conditionGroup) {
                //Iterando cada premio perteneciente a un referido

                //Verificando que los valores para actualización no sean nulos
                if (empty($conditionGroup->{'CondsExpiradas.usuario_id'}) || empty($conditionGroup->{'CondsExpiradas.tipo_premio'})) continue;

                //Actualizando estado grupal del premio
                $contSql++;
                $strSql[$contSql] = "update logro_referido set estado_grupal = 'F' where usuid_referido = " . $conditionGroup->{'CondsExpiradas.usuario_id'} . " and tipo_premio = " . $conditionGroup->{'CondsExpiradas.tipo_premio'};

                $queriedColumns = 'logro_referido.logroreferido_id, logro_referido.estado, logro_referido.estado_grupal, logro_referido.fecha_expira';

                //Recuperando cada condicion del premio
                $conditions = $LogroReferido->getLogrosAgrupados($conditionGroup->{'CondsExpiradas.usuario_id'}, $conditionGroup->{'CondsExpiradas.tipo_premio'}, $queriedColumns);

                foreach ($conditions as $condition) {
                    //Determinando estado y estado_global de los premios expirados
                    $condFechaExpira = $condition->{'logro_referido.fecha_expira'};
                    $condLogroId = $condition->{'logro_referido.logroreferido_id'};
                    $condEstado = $condition->{'logro_referido.estado'};
                    $condEstadoGlobal = $condition->{'logro_referido.estado_grupal'};

                    //Determinando si la condición bajo análisis ha expirado
                    $estado = null;
                    $expDate = date('Y-m-d H:i:s', strtotime($condFechaExpira));
                    if ($currentDate > $expDate && $condEstado == 'P') $estado = 'CE';

                    if (is_numeric($condLogroId) && !empty($condLogroId) && $estado != null) {
                        $contSql++;
                        $strSql[$contSql] = "update logro_referido set estado = '" . $estado . "' where logroreferido_id = " . $condLogroId;
                    }
                }
            }

            foreach ($strSql as $sql) {
                //Ejecutando actualizaciones
                $BonoInterno->execQuery($conditionsTransaction, $sql);
            }

            $conditionsTransaction->commit();
        } catch (Exception $e) {

        }

        /** Expirando premios */
        try {
            $awardsTransaction = new Transaction();
            $sqlExpiredAward = "select
                        premioExpirado.usuid_referido,
                        premioExpirado.tipo_premio,
                        premioExpirado.fecha_ult,
                        date_add(premioExpirado.fecha_ult, interval premioExpirado.valor DAY) as fechaExpiracion,
                        if(now() > date_add(premioExpirado.fecha_ult, interval premioExpirado.valor DAY), true, false) premioExpiro
                        from (select logro_referido.usuid_referido, logro_referido.tipo_premio, max(logro_referido.fecha_uso) as fecha_ult, mandante_detalle.valor
                        from mandante_detalle
                        inner join clasificador on (mandante_detalle.tipo = clasificador.clasificador_id)
                        inner join usuario as referente on (mandante_detalle.mandante = referente.mandante and
                                                   mandante_detalle.pais_id = referente.pais_id)
                        inner join logro_referido on (referente.usuario_id = logro_referido.usuid_referente)
                        where clasificador.abreviado = 'LIMITDAYSAWARDCHOICINGREFERENT'
                        and mandante_detalle.estado = 'A'
                        and mandante_detalle.valor > 0
                        and logro_referido.estado_grupal = 'C'
                        and fecha_uso is not null
                        group by logro_referido.usuid_referido, logro_referido.tipo_premio) as premioExpirado having premioExpiro = true";

            $expiredAwards = $BonoInterno->execQuery($awardsTransaction, $sqlExpiredAward);

            $contSql = 0;
            $strSql = [];
            foreach ($expiredAwards as $expiredAward) {
                //Iterando premios expirados por no seleccionar / redimir por parte del referente
                $usuidReferido = $expiredAward->{'premioExpirado.usuid_referido'};
                $tipoPremio = $expiredAward->{'premioExpirado.tipo_premio'};

                if (empty($usuidReferido) || empty($tipoPremio)) continue;

                $contSql++;
                $strSql[$contSql] = "update logro_referido set estado_grupal = 'PE', fecha_expira_premio = now() where logro_referido.usuid_referido = " . $usuidReferido . " and logro_referido.tipo_premio = " . $tipoPremio;
            }

            foreach ($strSql as $sql) {
                //Ejecutando actualizaciones
                $BonoInterno->execQuery($awardsTransaction, $sql);
            }

            $awardsTransaction->commit();
            print_r('PROCCESS OK');
        } catch (Exception $e) {

        }
    }
}