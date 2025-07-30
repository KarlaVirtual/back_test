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
use Backend\mysql\BonoInternoMySqlDAO;


/**
 * Clase 'CronJobResumenes'
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
class CronJobResumenesActualizacionSaldo
{


    public function __construct()
    {
    }

    public function execute()
    {
        /*
         * Actualizacion del saldo final de ayer con el saldo final del dia a procesar
         *
         */
        global $argv;

        $fechaSoloDia = date("Y-m-d", strtotime('-1 days'));

        $arg1 = $argv[2];
        $arg2 = $argv[3];
        if ($arg1 != "") {
            $fechaSoloDia = date("Y-m-d", strtotime($arg1));
        } else {
        }

        $sqlPartners =' not in (3, 4, 5, 6, 7, 10, 17, 22) ';

        if($arg2 == '6AM'){
            $_ENV["TIMEZONE"] = "-11:00";
            $sqlPartners ='  in (3, 4, 5, 6, 7, 10, 17, 22) ';
        }

        $nametable = " usuario_saldo";
        $nametableUnDiaDespues = " usuario_saldo";
        $nametableUnDiaAntes = " usuario_saldo";

        if ($fechaSoloDia != date("Y-m-d", strtotime('-1 days'))) {
            $nametable = " usuario_saldo_" . date("Y_m_d", strtotime($fechaSoloDia));
        }

        $fechaSiguienteDia = date("Y-m-d", strtotime($fechaSoloDia . '+1 days'));
        if ($fechaSiguienteDia != date("Y-m-d", strtotime('-1 days'))) {
            $nametableUnDiaDespues = " usuario_saldo_" . date("Y_m_d", strtotime($fechaSiguienteDia));
        }


        $fechaDiaAntes = date("Y-m-d", strtotime($fechaSoloDia . '-1 days'));
        if ($fechaDiaAntes != date("Y-m-d", strtotime('-1 days'))) {
            $nametableUnDiaAntes = " usuario_saldo_" . date("Y_m_d", strtotime($fechaDiaAntes));
        }

        $fecha1 = date("Y-m-d 00:00:00", strtotime($fechaSoloDia));
        $fecha2 = date("Y-m-d 23:59:59", strtotime($fechaSoloDia));

        /*
                      * Actualizacion del saldo inicial con el saldo del dia de ayer
                      *
                      */

        $BonoInterno = new BonoInterno();
        $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
        $transaccion = $BonoDetalleMySqlDAO->getTransaction();

        $sqlUpdate = "
        UPDATE {$nametable},{$nametableUnDiaAntes}
        SET {$nametable}.saldo_creditos_base_inicial = {$nametableUnDiaAntes}.saldo_creditos_base_final,
            {$nametable}.saldo_creditos_inicial      = {$nametableUnDiaAntes}.saldo_creditos_final,
            {$nametable}.saldo_inicial               = {$nametableUnDiaAntes}.saldo_final
        WHERE {$nametable}.usuario_id = {$nametableUnDiaAntes}.usuario_id
        AND {$nametable}.mandante {$sqlPartners} ;
        ";
        print_r($sqlUpdate);
        $BonoInterno->execQuery($transaccion, $sqlUpdate);
        $transaccion->commit();


        /*
         * Actualizacion del saldo final de ayer con el saldo inicial del dia a procesar
         *
         */
        $BonoInterno = new BonoInterno();
        $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
        $transaccion = $BonoDetalleMySqlDAO->getTransaction();

        $sqlUpdate = "UPDATE {$nametable}
SET {$nametable}.saldo_final={$nametable}.saldo_inicial,
    {$nametable}.saldo_creditos_final = {$nametable}.saldo_creditos_inicial,
    {$nametable}.saldo_creditos_base_final = {$nametable}.saldo_creditos_base_inicial
WHERE {$nametable}.mandante {$sqlPartners};";

        print_r($sqlUpdate);
        $BonoInterno->execQuery($transaccion, $sqlUpdate);
        $transaccion->commit();


        /*
         * Insertamos los nuevos usuarios
         *
         */


        $sqlInsert = "
        
        SELECT CONVERT(({$nametableUnDiaAntes}.usuario_id), UNSIGNED) usuario_id,
       CONVERT(({$nametableUnDiaAntes}.mandante), UNSIGNED) mandante,
       '" . date("Y-m-d", strtotime($fechaSoloDia)) . "',
       CONVERT(ROUND({$nametableUnDiaAntes}.saldo_final, 4), FLOAT) saldo_inicial,
       CONVERT(ROUND({$nametableUnDiaAntes}.saldo_creditos_base_final, 4), FLOAT) saldo_creditos_base_inicial,
       CONVERT(ROUND({$nametableUnDiaAntes}.saldo_creditos_final, 4), FLOAT) saldo_creditos_inicial,
       CONVERT(ROUND({$nametableUnDiaAntes}.saldo_final, 4), FLOAT) saldo_final,
       CONVERT(ROUND({$nametableUnDiaAntes}.saldo_creditos_base_final, 4), FLOAT) saldo_creditos_base_final,
       CONVERT(ROUND({$nametableUnDiaAntes}.saldo_creditos_final, 4), FLOAT) saldo_creditos_final
FROM {$nametableUnDiaAntes}
         LEFT OUTER JOIN {$nametable}
                         ON {$nametable}.usuario_id = {$nametableUnDiaAntes}.usuario_id

WHERE {$nametable}.usuario_id IS NULL
  AND {$nametableUnDiaAntes}.mandante {$sqlPartners};

                ";


        $datosUsuariosNuevos = $BonoInterno->execQuery($transaccion, $sqlInsert);

        $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
        $transaccion = $BonoDetalleMySqlDAO->getTransaction();

        $batchSize = 100000;
        $insertValues = [];

        foreach ($datosUsuariosNuevos as $datanum) {
            $insertValues[] = "(
        '{$datanum->{".usuario_id"}}',
        '{$datanum->{".mandante"}}',
        '" . date("Y-m-d", strtotime($fechaSoloDia)) . "',
        0, 0, 0, 0, 0, 0, 0,
        '{$datanum->{".saldo_inicial"}}',
        '{$datanum->{".saldo_final"}}',
        0,
        '{$datanum->{".saldo_creditos_inicial"}}',
        '{$datanum->{".saldo_creditos_base_inicial"}}',
        '{$datanum->{".saldo_creditos_final"}}',
        '{$datanum->{".saldo_creditos_base_final"}}',
        0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0
    )";

            if (count($insertValues) >= $batchSize) {
                $sql = "INSERT INTO {$nametable} (
            usuario_id, mandante, fecha, saldo_recarga, saldo_apuestas,
            saldo_premios, saldo_notaret_pagadas, saldo_notaret_pend,
            saldo_ajustes_entrada, saldo_ajustes_salida, saldo_inicial, saldo_final,
            saldo_bono, saldo_creditos_inicial, saldo_creditos_base_inicial, saldo_creditos_final,
            saldo_creditos_base_final, saldo_notaret_creadas, saldo_apuestas_casino, saldo_premios_casino,
            saldo_notaret_eliminadas, saldo_bono_free_ganado, saldo_bono_casino_free_ganado,
            saldo_bono_casino_vivo, saldo_bono_casino_vivo_free_ganado, saldo_bono_virtual,
            saldo_bono_virtual_free_ganado, billetera_id, saldo_apuestas_casino_vivo
        ) VALUES " . implode(",", $insertValues);

                $BonoInterno->execQuery($transaccion, $sql);
                $insertValues = []; // Reiniciar el buffer
                $transaccion->commit();
            }
        }

// Insertar cualquier remanente si existen registros pendientes
        if (!empty($insertValues)) {
            $sql = "INSERT INTO {$nametable} (
        usuario_id, mandante, fecha, saldo_recarga, saldo_apuestas,
        saldo_premios, saldo_notaret_pagadas, saldo_notaret_pend,
        saldo_ajustes_entrada, saldo_ajustes_salida, saldo_inicial, saldo_final,
        saldo_bono, saldo_creditos_inicial, saldo_creditos_base_inicial, saldo_creditos_final,
        saldo_creditos_base_final, saldo_notaret_creadas, saldo_apuestas_casino, saldo_premios_casino,
        saldo_notaret_eliminadas, saldo_bono_free_ganado, saldo_bono_casino_free_ganado,
        saldo_bono_casino_vivo, saldo_bono_casino_vivo_free_ganado, saldo_bono_virtual,
        saldo_bono_virtual_free_ganado, billetera_id, saldo_apuestas_casino_vivo
    ) VALUES " . implode(",", $insertValues);

            $BonoInterno->execQuery($transaccion, $sql);
            $transaccion->commit();
        }



        /*
         * Insertamos los nuevos usuarios
         *
         */


        $sqlInsert = "
                SELECT 
                    usuario.usuario_id, usuario.mandante, '" . date("Y-m-d", strtotime($fechaSoloDia)) . "', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0
                FROM usuario 
                LEFT OUTER JOIN {$nametable} ON usuario.usuario_id ={$nametable}.usuario_id
                
                WHERE {$nametable}.usuario_id IS NULL 
                AND usuario.fecha_crea LIKE '" . date("Y-m-d", strtotime($fechaSoloDia)) . "%' 
                AND usuario.mandante {$sqlPartners}; 
                ";


        print_r($sqlInsert);
        $datosUsuariosNuevos = $BonoInterno->execQuery($transaccion, $sqlInsert);



        $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
        $transaccion = $BonoDetalleMySqlDAO->getTransaction();

        foreach ($datosUsuariosNuevos as $datanum) {
            $sql = "
                    
                    INSERT INTO {$nametable}
(usuario_id, mandante, fecha, saldo_recarga, saldo_apuestas, saldo_premios, saldo_notaret_pagadas, saldo_notaret_pend, saldo_ajustes_entrada, saldo_ajustes_salida, saldo_inicial, saldo_final, saldo_bono, saldo_creditos_inicial, saldo_creditos_base_inicial, saldo_creditos_final, saldo_creditos_base_final, saldo_notaret_creadas, saldo_apuestas_casino, saldo_premios_casino, saldo_notaret_eliminadas, saldo_bono_free_ganado, saldo_bono_casino_free_ganado, saldo_bono_casino_vivo, saldo_bono_casino_vivo_free_ganado, saldo_bono_virtual, saldo_bono_virtual_free_ganado, billetera_id, saldo_apuestas_casino_vivo

                        ) VALUES (
                                '" . $datanum->{'usuario.usuario_id'} . "', '" . $datanum->{'usuario.mandante'} . "', '" . date("Y-m-d", strtotime($fechaSoloDia)) . "', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0
                                );";

            $BonoInterno->execQuery($transaccion, $sql);
        }

        $transaccion->commit();


        $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();

        $sqlHistorial = "SET group_concat_max_len = 18446744073709551615;";
        $BonoInternoMySqlDAO->querySQL($sqlHistorial);

        $sqlHistorial = "

SELECT GROUP_CONCAT(usuhistorial_id SEPARATOR ', ') usuhistorial_id

FROM (SELECT usuario.usuario_id,MAX(usuhistorial_id)usuhistorial_id
      FROM usuario

               inner join (SELECT usuario_historial.usuhistorial_id, usuario_id
                           FROM usuario_historial
                           where usuario_historial.fecha_crea >= '{$fecha1}'
                             and usuario_historial.fecha_crea <= '{$fecha2}') uh
                          ON usuario.usuario_id = uh.usuario_id

      group by usuario.usuario_id) a;

";


        print_r($sqlHistorial);
        $dataHistorial = $BonoInternoMySqlDAO->querySQL($sqlHistorial);

        $usuHistorialIds2 = '';
        foreach ($dataHistorial[0] as $item) {
            $usuHistorialIds2 = $item;
        }

        $usuHistorialIds2 = explode(',', $usuHistorialIds2);


        $contUsu = 0;
        $usuHistorialIds = '0';
        foreach ($usuHistorialIds2 as $item) {
            if ($item == '') {
                continue;
            }
            if ($contUsu >= 1000) {
                if ($usuHistorialIds != '0' && $usuHistorialIds != '0,') {


                    $sqlHistorial = "
            UPDATE {$nametable},usuario_historial
SET {$nametable}.saldo_final=usuario_historial.creditos+usuario_historial.creditos_base,
    {$nametable}.saldo_creditos_final = usuario_historial.creditos,
    {$nametable}.saldo_creditos_base_final = usuario_historial.creditos_base
where usuario_historial.usuario_id={$nametable}.usuario_id
AND usuhistorial_id in ( " . $usuHistorialIds . ");
";
                    $_ENV["NEEDINSOLATIONLEVEL"] = '1';

                    $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
                    $transaccion = $BonoDetalleMySqlDAO->getTransaction();
                    $BonoInterno->execUpdate($transaccion, $sqlHistorial);
                    $transaccion->commit();

                    $usuHistorialIds = '0';

                }

            }
            $usuHistorialIds = $usuHistorialIds . ',' . $item;
            $contUsu++;
        }

        if ($usuHistorialIds != '0' && $usuHistorialIds != '0,') {


            $sqlHistorial = "
            UPDATE {$nametable},usuario_historial
SET {$nametable}.saldo_final=usuario_historial.creditos+usuario_historial.creditos_base,
    {$nametable}.saldo_creditos_final = usuario_historial.creditos,
    {$nametable}.saldo_creditos_base_final = usuario_historial.creditos_base
where usuario_historial.usuario_id={$nametable}.usuario_id
AND usuhistorial_id in ( " . $usuHistorialIds . ");
";
            $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
            $transaccion = $BonoDetalleMySqlDAO->getTransaction();
            $BonoInterno->execUpdate($transaccion, $sqlHistorial);
            $transaccion->commit();

        }

    }
}