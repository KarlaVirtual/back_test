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
class CronJobResumenesActualizacionUsuarioSaldoResumen
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

        $sqlPartners = ' not in (3, 4, 5, 6, 7, 10, 17, 22) ';

        if ($arg2 == '6AM') {
            $_ENV["TIMEZONE"] = "-11:00";
            $sqlPartners = '  in (3, 4, 5, 6, 7, 10, 17, 22) ';
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
         * Insertamos los nuevos usuarios
         *
         */


        $sqlInsert = "
      SELECT usuario_mandante.usuario_mandante, usuario_mandante.mandante,
       SUM(usuario_recarga_resumen.valor)                          saldo_recarga,
       DATE_FORMAT(usuario_recarga_resumen.fecha_crea, '%Y-%m-%d') fecha,

       0                                                           saldo_apuestas,
       0                                                           saldo_premios,
       0                                                           saldo_impuestos_apuestas_deportivas,
       0                                                           saldo_impuestos_premios_deportivas,
       0                                                           saldo_apuestas_casino,
       0                                                           saldo_premios_casino,
       0                                                           saldo_notaret_pagadas,
       0                                                           saldo_notaret_pend,
       0                                                           saldo_notaret_creadas,
       0                                                           saldo_ajustes_entrada,
       0                                                           saldo_ajustes_salida,
       0                                                           saldo_bono,
       0                                                           saldo_bono_casino_vivo,
       0                                                           saldo_notaret_eliminadas,
       0                                                           saldo_bono_free_ganado,
       0                                                           billetera_id,
       0                                                           saldo_premios_jackpot_casino,
       0                                                           saldo_premios_jackpot_deportivas,
       0                                                           saldo_ventas_loteria,
       0                                                           saldo_rollbacks_loteria,
       0                                                           saldo_impuestos_apuestas_casino,
       SUM(usuario_recarga_resumen.impuesto)                       saldo_impuestos_depositos
FROM casino.usuario_recarga_resumen
         INNER JOIN usuario_mandante on usuario_mandante.usuario_mandante = usuario_recarga_resumen.usuario_id
         INNER JOIN usuario_perfil on usuario_mandante.usuario_mandante = usuario_perfil.usuario_id
WHERE usuario_perfil.perfil_id='USUONLINE'
GROUP BY usuario_recarga_resumen.usuario_id

                ";

        $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
        $transaccion = $BonoDetalleMySqlDAO->getTransaction();

        $BonoInterno = new BonoInterno();
        $data = $BonoInterno->execQuery($transaccion, $sqlInsert);


        $batchSize = 100000;
        $insertValues = [];


        foreach ($data as $datanum) {
            $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
            $transaccion = $BonoDetalleMySqlDAO->getTransaction();
            $transaccion->getConnection()->beginTransaction();

            $sql = "INSERT INTO usuario_saldoresumen (usuario_id, mandante, saldo_recarga,saldo_impuestos_depositos) VALUES (
                           '" . $datanum->{'usuario_mandante.usuario_mandante'} . "',
                           '" . $datanum->{'usuario_mandante.mandante'} . "',
                           '" . $datanum->{'.saldo_recarga'} . "',
                           '" . $datanum->{'.saldo_impuestos_depositos'} . "'
                                 )
       
       
ON DUPLICATE KEY UPDATE usuario_saldoresumen.saldo_recarga      =  '" . $datanum->{'.saldo_recarga'} . "',

                         usuario_saldoresumen.saldo_impuestos_depositos      =  '" . $datanum->{'.saldo_impuestos_depositos'} . "'
       ";

            $BonoInterno->execQuery($transaccion, $sql);

            $transaccion->commit();

        }


    }
}