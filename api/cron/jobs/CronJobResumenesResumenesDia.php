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
use Backend\dto\JackpotInterno;
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
class CronJobResumenesResumenesDia
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

        $globalJackpotTypesInBonoLog = JackpotInterno::getJackpotTypesForBonoLogString();
        $sportbookJackpotTypesInBonoLog = JackpotInterno::getJackpotTypesForBonoLogString('DEPORTIVAS');
        $casinoJackpotTypesInBonoLog = JackpotInterno::getJackpotTypesForBonoLogString('CASINO');


        $fechaSoloDia = date("Y-m-d", strtotime('-1 days'));

        $arg1 = $argv[2];
        if ($arg1 != "") {
            $fechaSoloDia = date("Y-m-d", strtotime($arg1));
        } else {
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


        $UsuarioPuntoVentaFinalConDetalles = "
SELECT data.usuario_id,
       usuario.mandante,
       DATE_FORMAT(data.fecha, '%Y-%m-%d')                                       fecha,

       SUM(data.saldo_recarga)                                                        saldo_recarga,
       SUM(data.saldo_apuestas)                                                       saldo_apuestas,
       SUM(data.saldo_premios)                                                        saldo_premios,
       SUM(data.saldo_impuestos_apuestas_deportivas)                                                        saldo_impuestos_apuestas_deportivas,
       SUM(data.saldo_impuestos_apuestas_casino)                                                        saldo_impuestos_apuestas_casino,
       SUM(data.saldo_impuestos_premios_deportivas)                                                        saldo_impuestos_premios_deportivas,
       SUM(data.saldo_impuestos_depositos)                                                        saldo_impuestos_depositos,
       SUM(data.saldo_apuestas_casino)                                                saldo_apuestas_casino,
       SUM(data.saldo_premios_casino)                                                 saldo_premios_casino,
       SUM(data.saldo_notaret_pagadas)                                                saldo_notaret_pagadas,
       SUM(data.saldo_notaret_pend)                                                   saldo_notaret_pend,
       SUM(data.saldo_notaret_creadas)                                                saldo_notaret_creadas,
       SUM(data.saldo_ajustes_entrada)                                                saldo_ajustes_entrada,
       SUM(data.saldo_ajustes_salida)                                                 saldo_ajustes_salida,
       SUM(data.saldo_bono)                                                           saldo_bono,
       SUM(data.saldo_notaret_eliminadas)                                             saldo_notaret_eliminadas,
       SUM(data.saldo_bono_free_ganado)                                             saldo_bono_free_ganado,
       0 saldo_inicial,
       0 saldo_final,
       0 saldo_creditos_inicial,
       0 saldo_creditos_base_inicial,
       0                           saldo_creditos_final,
       0                            saldo_creditos_base_final
FROM (
       (SELECT usuario_id,
               SUM(valor)                          saldo_recarga,
               DATE_FORMAT(fecha_crea, '%Y-%m-%d') fecha,

               0                                   saldo_apuestas,
               0                                   saldo_premios,
               0                                   saldo_impuestos_apuestas_deportivas,
               0                                   saldo_impuestos_apuestas_casino,
               0                                   saldo_impuestos_premios_deportivas,
               0                                   saldo_impuestos_depositos,
               0                                   saldo_apuestas_casino,
               0                                   saldo_premios_casino,
               0                                   saldo_notaret_pagadas,
               0                                   saldo_notaret_pend,
               0                                   saldo_notaret_creadas,
               0                                   saldo_ajustes_entrada,
               0                                   saldo_ajustes_salida,
               0                                   saldo_bono,
               0                                   saldo_notaret_eliminadas,
               0                                   saldo_bono_free_ganado
        FROM casino.usuario_recarga_resumen
        WHERE (fecha_crea)   ='" . $fecha1 . "' AND estado='A'
        GROUP BY usuario_id
       )
       UNION
        
        (SELECT usuario_id,
               0                         saldo_recarga,
               DATE_FORMAT(fecha_crea, '%Y-%m-%d') fecha,

               0                                   saldo_apuestas,
               0                                   saldo_premios,
               0                                   saldo_impuestos_apuestas_deportivas,
               0                                   saldo_impuestos_apuestas_casino,
               0                                   saldo_impuestos_premios_deportivas,
               0                                   saldo_impuestos_depositos,
               0                                   saldo_apuestas_casino,
               0                                   saldo_premios_casino,
               0                                   saldo_notaret_pagadas,
               0                                   saldo_notaret_pend,
               0                                   saldo_notaret_creadas,
               0                                   saldo_ajustes_entrada,
               0                                   saldo_ajustes_salida,
               0                                   saldo_bono,
               SUM(valor)                                    saldo_notaret_eliminadas,
               0                                   saldo_bono_free_ganado
        FROM casino.usuario_recarga_resumen
        WHERE (fecha_crea)   ='" . $fecha1 . "' AND estado='I'
        GROUP BY usuario_id
       )

       UNION

       (SELECT usuario_id,
               0                                                                   saldo_recarga,
               DATE_FORMAT(fecha_crea, '%Y-%m-%d')                                 fecha,
               SUM(valor) saldo_apuestas,
               0                                                                   saldo_premios,
               0                                   saldo_impuestos_apuestas_deportivas,
               0                                   saldo_impuestos_apuestas_casino,
               0                                   saldo_impuestos_premios_deportivas,
               0                                   saldo_impuestos_depositos,
               0                                                                   saldo_apuestas_casino,
               0                                                                   saldo_premios_casino,
               0                                                                   saldo_notaret_pagadas,
               0                                                                   saldo_notaret_pend,
               0                                                                   saldo_notaret_creadas,
               0                                                                   saldo_ajustes_entrada,
               0                                                                   saldo_ajustes_salida,
               0                                                                   saldo_bono,
               0                                   saldo_notaret_eliminadas,
               0                                   saldo_bono_free_ganado
        FROM casino.usuario_deporte_resumen
        WHERE tipo ='1' AND  (fecha_crea)   ='" . $fecha1 . "'
        GROUP BY usuario_id
       )
       UNION

       (SELECT usuario_id,
               0                                                                                 saldo_recarga,
               DATE_FORMAT(fecha_crea, '%Y-%m-%d')                                               fecha,
               0                                                                                 saldo_apuestas,
               SUM(valor) saldo_premios,
               0                                   saldo_impuestos_apuestas_deportivas,
               0                                   saldo_impuestos_apuestas_casino,
               0                                   saldo_impuestos_premios_deportivas,
               0                                   saldo_impuestos_depositos,
               0                                                                                 saldo_apuestas_casino,
               0                                                                                 saldo_premios_casino,
               0                                                                                 saldo_notaret_pagadas,
               0                                                                                 saldo_notaret_pend,
               0                                                                                 saldo_notaret_creadas,
               0                                                                                 saldo_ajustes_entrada,
               0                                                                                 saldo_ajustes_salida,
               0                                                                                 saldo_bono,
               0                                   saldo_notaret_eliminadas,
               0                                   saldo_bono_free_ganado
        FROM casino.usuario_deporte_resumen
        WHERE tipo = '3'   AND  (fecha_crea)   ='" . $fecha1 . "'
        GROUP BY usuario_id)

       UNION

       (SELECT usuario_id,
               0                                                                                 saldo_recarga,
               DATE_FORMAT(fecha_crea, '%Y-%m-%d')                                               fecha,
               0                                                                                 saldo_apuestas,
               0 saldo_premios,
               SUM(valor)                                   saldo_impuestos_apuestas_deportivas,
               0                                 saldo_impuestos_apuestas_casino,
               0                                   saldo_impuestos_premios_deportivas,
               0                                   saldo_impuestos_depositos,
               0                                                                                 saldo_apuestas_casino,
               0                                                                                 saldo_premios_casino,
               0                                                                                 saldo_notaret_pagadas,
               0                                                                                 saldo_notaret_pend,
               0                                                                                 saldo_notaret_creadas,
               0                                                                                 saldo_ajustes_entrada,
               0                                                                                 saldo_ajustes_salida,
               0                                                                                 saldo_bono,
               0                                   saldo_notaret_eliminadas,
               0                                   saldo_bono_free_ganado
        FROM casino.usuario_deporte_resumen
        WHERE tipo = 'TAXBET'   AND  (fecha_crea)   ='" . $fecha1 . "'
        GROUP BY usuario_id)

       UNION

       (SELECT usuario_id,
               0                                                                                 saldo_recarga,
               DATE_FORMAT(fecha_crea, '%Y-%m-%d')                                               fecha,
               0                                                                                 saldo_apuestas,
               0 saldo_premios,
               0                                   saldo_impuestos_apuestas_deportivas,
               0                                   saldo_impuestos_apuestas_casino,
               SUM(valor)                                   saldo_impuestos_premios_deportivas,
               0                                   saldo_impuestos_depositos,
               0                                                                                 saldo_apuestas_casino,
               0                                                                                 saldo_premios_casino,
               0                                                                                 saldo_notaret_pagadas,
               0                                                                                 saldo_notaret_pend,
               0                                                                                 saldo_notaret_creadas,
               0                                                                                 saldo_ajustes_entrada,
               0                                                                                 saldo_ajustes_salida,
               0                                                                                 saldo_bono,
               0                                   saldo_notaret_eliminadas,
               0                                   saldo_bono_free_ganado
        FROM casino.usuario_deporte_resumen
        WHERE tipo = 'TAXWIN'   AND  (fecha_crea)   ='" . $fecha1 . "'
        GROUP BY usuario_id)
        
        
       UNION
       (
         SELECT usuario_mandante,
               0                                                                                 saldo_recarga,
               DATE_FORMAT(usuario_casino_resumen.fecha_crea, '%Y-%m-%d') fecha,
               0                                                                                 saldo_apuestas,
               0 saldo_premios,
               0                                   saldo_impuestos_apuestas_deportivas,
               SUM(valor)                                   saldo_impuestos_apuestas_casino,
               0                                   saldo_impuestos_premios_deportivas,
               0                                   saldo_impuestos_depositos,
               0                                                                                 saldo_apuestas_casino,
               0                                                                                 saldo_premios_casino,
               0                                                                                 saldo_notaret_pagadas,
               0                                                                                 saldo_notaret_pend,
               0                                                                                 saldo_notaret_creadas,
               0                                                                                 saldo_ajustes_entrada,
               0                                                                                 saldo_ajustes_salida,
               0                                                                                 saldo_bono,
               0                                   saldo_notaret_eliminadas,
               0                                   saldo_bono_free_ganado
         FROM casino.usuario_casino_resumen
                         INNER JOIN usuario_mandante ON (usumandante_id=usuario_id)

         WHERE tipo IN ('8','9') AND  (usuario_casino_resumen.fecha_crea)   ='" . $fecha1 . "'
         GROUP BY usuario_id
       )

       UNION

       (SELECT usuario_id,
               0                                   saldo_recarga,
               DATE_FORMAT(fecha_crea, '%Y-%m-%d') fecha,
               0                                   saldo_apuestas,
               0                                   saldo_premios,
               0                                   saldo_impuestos_apuestas_deportivas,
               0                                   saldo_impuestos_apuestas_casino,
               0                                   saldo_impuestos_premios_deportivas,
               0                                   saldo_impuestos_depositos,
               0                                   saldo_apuestas_casino,
               0                                   saldo_premios_casino,
               SUM(valor)                          saldo_notaret_pagadas,
               0                                   saldo_notaret_pend,
               0                                   saldo_notaret_creadas,
               0                                   saldo_ajustes_entrada,
               0                                   saldo_ajustes_salida,
               0                                   saldo_bono,
               0                                   saldo_notaret_eliminadas,
               0                                   saldo_bono_free_ganado
        FROM casino.usuario_retiro_resumen 
        WHERE estado = 'I' AND  (usuario_retiro_resumen.fecha_crea)   ='" . $fecha1 . "' AND puntoventa_id=0
        GROUP BY usuario_id
       )
       
       
       UNION

       (SELECT usuario_id,
               0                                   saldo_recarga,
               DATE_FORMAT(fecha_crea, '%Y-%m-%d') fecha,
               0                                   saldo_apuestas,
               0                                   saldo_premios,
               0                                   saldo_impuestos_apuestas_deportivas,
               0                                   saldo_impuestos_apuestas_casino,
               0                                   saldo_impuestos_premios_deportivas,
               0                                   saldo_impuestos_depositos,
               0                                   saldo_apuestas_casino,
               0                                   saldo_premios_casino,
               0                                   saldo_notaret_pagadas,
               0                                   saldo_notaret_pend,
               0                                   saldo_notaret_creadas,

               SUM(valor)                          saldo_ajustes_entrada,
               0                                   saldo_ajustes_salida,
               0                                   saldo_bono,
               0                                   saldo_notaret_eliminadas,
               0                                   saldo_bono_free_ganado
        FROM casino.usuario_ajustes_resumen
        WHERE tipo = 'E' AND tipo_ajuste = 'R' AND  DATE_FORMAT(fecha_crea, '%Y-%m-%d')   ='" . $fechaSoloDia . "'
        GROUP BY usuario_id
       )
       UNION

       (SELECT usuario_id,
               0                                   saldo_recarga,
               DATE_FORMAT(fecha_crea, '%Y-%m-%d') fecha,
               0                                   saldo_apuestas,
               0                                   saldo_premios,
               0                                   saldo_impuestos_apuestas_deportivas,
               0                                   saldo_impuestos_apuestas_casino,
               0                                   saldo_impuestos_premios_deportivas,
               0                                   saldo_impuestos_depositos,
               0                                   saldo_apuestas_casino,
               0                                   saldo_premios_casino,
               0                                   saldo_notaret_pagadas,
               0                                   saldo_notaret_pend,
               0                                   saldo_notaret_creadas,
               0                                   saldo_ajustes_entrada,
               SUM(valor)                          saldo_ajustes_salida,
               0                                   saldo_bono,
               0                                   saldo_notaret_eliminadas,
               0                                   saldo_bono_free_ganado
        FROM casino.usuario_ajustes_resumen
        WHERE tipo = 'S' AND tipo_ajuste = 'R' AND  (fecha_crea)   ='" . $fecha1 . "'
        GROUP BY usuario_id
       )
       
       
       
       UNION

       (SELECT usuario_id,
               0                                   saldo_recarga,
               DATE_FORMAT(fecha_crea, '%Y-%m-%d') fecha,
               0                                   saldo_apuestas,
               0                                   saldo_premios,
               0                                   saldo_impuestos_apuestas_deportivas,
               0                                   saldo_impuestos_apuestas_casino,
               0                                   saldo_impuestos_premios_deportivas,
               0                                   saldo_impuestos_depositos,
               0                                   saldo_apuestas_casino,
               SUM(valor)                                   saldo_premios_casino,
               0                                   saldo_notaret_pagadas,
               0                                   saldo_notaret_pend,
               0                                   saldo_notaret_creadas,

               0                         saldo_ajustes_entrada,
               0                                   saldo_ajustes_salida,
               0                                   saldo_bono,
               0                                   saldo_notaret_eliminadas,
               0                                   saldo_bono_free_ganado
        FROM casino.usuario_ajustes_resumen
        WHERE tipo = 'E' AND tipo_ajuste = 'A' AND (fecha_crea)   ='" . $fecha1 . "'
        GROUP BY usuario_id
       )
       UNION

       (SELECT usuario_id,
               0                                   saldo_recarga,
               DATE_FORMAT(fecha_crea, '%Y-%m-%d') fecha,
               0                                   saldo_apuestas,
               0                                   saldo_premios,
               0                                   saldo_impuestos_apuestas_deportivas,
               0                                   saldo_impuestos_apuestas_casino,
               0                                   saldo_impuestos_premios_deportivas,
               0                                   saldo_impuestos_depositos,
               SUM(valor)                                   saldo_apuestas_casino,
               0                                   saldo_premios_casino,
               0                                   saldo_notaret_pagadas,
               0                                   saldo_notaret_pend,
               0                                   saldo_notaret_creadas,
               0                                   saldo_ajustes_entrada,
               0                          saldo_ajustes_salida,
               0                                   saldo_bono,
               0                                   saldo_notaret_eliminadas,
               0                                   saldo_bono_free_ganado
        FROM casino.usuario_ajustes_resumen
        WHERE tipo = 'S' AND tipo_ajuste = 'A' AND  (fecha_crea)   ='" . $fecha1 . "'
        GROUP BY usuario_id
       )
       
     ) data
     INNER JOIN usuario ON (data.usuario_id = usuario.usuario_id)
     INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = usuario.usuario_id)

WHERE data.usuario_id IS NOT NULL AND usuario_perfil.perfil_id != 'USUONLINE'    AND usuario_perfil.mandante NOT IN (3,4,5,6,7,10,22,17,25)
  AND data.fecha = '" . $fechaSoloDia . "'

GROUP BY data.usuario_id;

";

        $UsuarioSaldoFinalConDetalles = "
SELECT data.usuario_id,
       usuario.mandante,
       DATE_FORMAT(data.fecha, '%Y-%m-%d')                                       fecha,

       SUM(data.saldo_recarga)                                                        saldo_recarga,
       SUM(data.saldo_apuestas)                                                       saldo_apuestas,
       SUM(data.saldo_premios)                                                        saldo_premios,
       SUM(data.saldo_impuestos_apuestas_deportivas)                                                        saldo_impuestos_apuestas_deportivas,
       SUM(data.saldo_impuestos_premios_deportivas)                                                        saldo_impuestos_premios_deportivas,
       SUM(data.saldo_apuestas_casino) -  SUM(data.saldo_ventas_loteria)                                   saldo_apuestas_casino,
       SUM(data.saldo_premios_casino)  - SUM(data.saldo_rollbacks_loteria)                                 saldo_premios_casino,
       SUM(data.saldo_notaret_pagadas)                                                saldo_notaret_pagadas,
       SUM(data.saldo_notaret_pend)                                                   saldo_notaret_pend,
       SUM(data.saldo_notaret_creadas)                                                saldo_notaret_creadas,
       SUM(data.saldo_ajustes_entrada)                                                saldo_ajustes_entrada,
       SUM(data.saldo_ajustes_salida)                                                 saldo_ajustes_salida,
       SUM(data.saldo_bono)                                                           saldo_bono,
       SUM(data.saldo_bono_casino_vivo)                                                           saldo_bono_casino_vivo,
       SUM(data.saldo_notaret_eliminadas)                                             saldo_notaret_eliminadas,
       SUM(data.saldo_bono_free_ganado)                                             saldo_bono_free_ganado,
       0 saldo_inicial,
       0 saldo_final,
       0 saldo_creditos_inicial,
       0 saldo_creditos_base_inicial,
       0                              saldo_creditos_final,
       0                            saldo_creditos_base_final,
       data.billetera_id,
       SUM(data.saldo_premios_jackpot_casino)                                    saldo_premios_jackpot_casino,
       SUM(data.saldo_premios_jackpot_deportivas)                                saldo_premios_jackpot_deportivas,
       SUM(data.saldo_ventas_loteria)                                saldo_ventas_loteria,
       SUM(data.saldo_rollbacks_loteria)                                saldo_rollbacks_loteria,
       SUM(data.saldo_impuestos_apuestas_casino)                                saldo_impuestos_apuestas_casino,
       SUM(data.saldo_impuestos_depositos)                                                        saldo_impuestos_depositos
FROM (
       (SELECT usuario_id,
               SUM(valor)                          saldo_recarga,
               DATE_FORMAT(fecha_crea, '%Y-%m-%d') fecha,

               0                                   saldo_apuestas,
               0                                   saldo_premios,
               0                                   saldo_impuestos_apuestas_deportivas,
               0                                   saldo_impuestos_premios_deportivas,
               0                                   saldo_apuestas_casino,
               0                                   saldo_premios_casino,
               0                                   saldo_notaret_pagadas,
               0                                   saldo_notaret_pend,
               0                                   saldo_notaret_creadas,
               0                                   saldo_ajustes_entrada,
               0                                   saldo_ajustes_salida,
               0                                   saldo_bono,
               0                                   saldo_bono_casino_vivo,
               0                                   saldo_notaret_eliminadas,
               0                                   saldo_bono_free_ganado,
               0    billetera_id,
               0    saldo_premios_jackpot_casino,
               0    saldo_premios_jackpot_deportivas,
               0    saldo_ventas_loteria,
               0    saldo_rollbacks_loteria,
               0    saldo_impuestos_apuestas_casino,
               SUM(usuario_recarga_resumen.impuesto)                                   saldo_impuestos_depositos
        FROM casino.usuario_recarga_resumen
        WHERE (fecha_crea)   ='" . $fecha1 . "'
        GROUP BY usuario_id
       )

       UNION       
       
       (
       SELECT usuario_mandante.usuario_mandante usuario_id,
       0                           saldo_recarga,
       DATE_FORMAT(usucasino_detalle_resumen.fecha_crea, '%Y-%m-%d') fecha,
       0                                   saldo_apuestas,
       0                                   saldo_premios,
       0                                   saldo_impuestos_apuestas_deportivas,
       0                                   saldo_impuestos_premios_deportivas,
       0                                   saldo_apuestas_casino,
       0                                   saldo_premios_casino,
       0                                   saldo_notaret_pagadas,
       0                                   saldo_notaret_pend,
       0                                   saldo_notaret_creadas,
       0                                   saldo_ajustes_entrada,
       0                                   saldo_ajustes_salida,
       0                                   saldo_bono,
       0                                   saldo_bono_casino_vivo,
       0                                   saldo_notaret_eliminadas,
       0                                   saldo_bono_free_ganado,
       0                                   billetera_id,
       0                                   saldo_premios_jackpot_casino,
       0                                   saldo_premios_jackpot_deportivas,
       SUM(CASE WHEN usucasino_detalle_resumen.tipo = 'DEBIT' THEN usucasino_detalle_resumen.valor ELSE 0 END)    saldo_ventas_loteria,
       SUM(CASE WHEN usucasino_detalle_resumen.tipo = 'ROLLBACK' THEN usucasino_detalle_resumen.valor ELSE 0 END)    saldo_rollbacks_loteria,
       0        saldo_impuestos_apuestas_casino,
       0        saldo_impuestos_depositos
        FROM casino.usucasino_detalle_resumen
        INNER JOIN producto_mandante ON (usucasino_detalle_resumen.producto_id = producto_mandante.prodmandante_id)
        INNER JOIN producto ON (producto_mandante.producto_id = producto.producto_id)
        INNER JOIN subproveedor ON (producto.subproveedor_id = subproveedor.subproveedor_id)
        INNER JOIN usuario_mandante ON (usucasino_detalle_resumen.usuario_id = usuario_mandante.usumandante_id)
        WHERE (usucasino_detalle_resumen.fecha_crea)   ='" . $fecha1 . "'
        AND subproveedor.tipo = 'LOTTERY'
        GROUP BY usuario_mandante.usuario_mandante
        )


       UNION

       (SELECT usuario_id,
               0                                                                   saldo_recarga,
               DATE_FORMAT(fecha_crea, '%Y-%m-%d')                                 fecha,
               SUM(CASE WHEN tipo IN ('BET') THEN valor ELSE -valor END) saldo_apuestas,
               0                                                                   saldo_premios,
               0                                   saldo_impuestos_apuestas_deportivas,
               0                                   saldo_impuestos_premios_deportivas,
               0                                                                   saldo_apuestas_casino,
               0                                                                   saldo_premios_casino,
               0                                                                   saldo_notaret_pagadas,
               0                                                                   saldo_notaret_pend,
               0                                                                   saldo_notaret_creadas,
               0                                                                   saldo_ajustes_entrada,
               0                                                                   saldo_ajustes_salida,
               0                                                                   saldo_bono,
               0                                   saldo_bono_casino_vivo,
               0                                   saldo_notaret_eliminadas,
               0                                   saldo_bono_free_ganado,
               usumodif_id    billetera_id,
               0    saldo_premios_jackpot_casino,
               0    saldo_premios_jackpot_deportivas,
               0    saldo_ventas_loteria,
               0    saldo_rollbacks_loteria,
               0    saldo_impuestos_apuestas_casino,
               0        saldo_impuestos_depositos
        FROM casino.usuario_deporte_resumen
        WHERE tipo IN ('BET', 'STAKEDECREASE', 'REFUND') AND  (fecha_crea)   ='" . $fecha1 . "'
        GROUP BY usumodif_id,usuario_id
       )
       UNION

       (SELECT usuario_id,
               0                                                                                 saldo_recarga,
               DATE_FORMAT(fecha_crea, '%Y-%m-%d')                                               fecha,
               0                                                                                 saldo_apuestas,
               SUM(CASE WHEN tipo IN ('WIN', 'NEWCREDIT', 'CASHOUT') THEN valor ELSE -valor END) saldo_premios,
               0                                   saldo_impuestos_apuestas_deportivas,
               0                                   saldo_impuestos_premios_deportivas,
               0                                                                                 saldo_apuestas_casino,
               0                                                                                 saldo_premios_casino,
               0                                                                                 saldo_notaret_pagadas,
               0                                                                                 saldo_notaret_pend,
               0                                                                                 saldo_notaret_creadas,
               0                                                                                 saldo_ajustes_entrada,
               0                                                                                 saldo_ajustes_salida,
               0                                                                                 saldo_bono,
               0                                   saldo_bono_casino_vivo,
               0                                   saldo_notaret_eliminadas,
               0                                   saldo_bono_free_ganado,
               usumodif_id    billetera_id,
               0    saldo_premios_jackpot_casino,
               0    saldo_premios_jackpot_deportivas,
               0    saldo_ventas_loteria,
               0    saldo_rollbacks_loteria,
               0    saldo_impuestos_apuestas_casino,
               0        saldo_impuestos_depositos
        FROM casino.usuario_deporte_resumen
        WHERE tipo IN ('WIN', 'NEWCREDIT', 'CASHOUT', 'NEWDEBIT') AND  (fecha_crea)   ='" . $fecha1 . "'
        GROUP BY usumodif_id,usuario_id)

       UNION

       (SELECT usuario_id,
               0                                                                                 saldo_recarga,
               DATE_FORMAT(fecha_crea, '%Y-%m-%d')                                               fecha,
               0                                                                                 saldo_apuestas,
               0                                            saldo_premios,
               SUM(valor)                                   saldo_impuestos_apuestas_deportivas,
               0                                            saldo_impuestos_premios_deportivas,
               0                                                                                 saldo_apuestas_casino,
               0                                                                                 saldo_premios_casino,
               0                                                                                 saldo_notaret_pagadas,
               0                                                                                 saldo_notaret_pend,
               0                                                                                 saldo_notaret_creadas,
               0                                                                                 saldo_ajustes_entrada,
               0                                                                                 saldo_ajustes_salida,
               0                                                                                 saldo_bono,
               0                                   saldo_bono_casino_vivo,
               0                                   saldo_notaret_eliminadas,
               0                                   saldo_bono_free_ganado,
               usumodif_id    billetera_id,
               0    saldo_premios_jackpot_casino,
               0    saldo_premios_jackpot_deportivas,
               0    saldo_ventas_loteria,
               0    saldo_rollbacks_loteria,
               0    saldo_impuestos_apuestas_casino,
               0        saldo_impuestos_depositos
        FROM casino.usuario_deporte_resumen
        WHERE tipo IN ('TAXBET') AND  (fecha_crea)   ='" . $fecha1 . "'
        GROUP BY usumodif_id,usuario_id)

       UNION



       (SELECT usuario_id,
               0                                                                                 saldo_recarga,
               DATE_FORMAT(fecha_crea, '%Y-%m-%d')                                               fecha,
               0                                                                                 saldo_apuestas,
               0                                            saldo_premios,
               0                                   saldo_impuestos_apuestas_deportivas,
               SUM(valor)                                            saldo_impuestos_premios_deportivas,
               0                                                                                 saldo_apuestas_casino,
               0                                                                                 saldo_premios_casino,
               0                                                                                 saldo_notaret_pagadas,
               0                                                                                 saldo_notaret_pend,
               0                                                                                 saldo_notaret_creadas,
               0                                                                                 saldo_ajustes_entrada,
               0                                                                                 saldo_ajustes_salida,
               0                                                                                 saldo_bono,
               0                                   saldo_bono_casino_vivo,
               0                                   saldo_notaret_eliminadas,
               0                                   saldo_bono_free_ganado,
               usumodif_id    billetera_id,
               0    saldo_premios_jackpot_casino,
               0    saldo_premios_jackpot_deportivas,
               0    saldo_ventas_loteria,
               0    saldo_rollbacks_loteria,
               0    saldo_impuestos_apuestas_casino,
               0        saldo_impuestos_depositos
        FROM casino.usuario_deporte_resumen
        WHERE tipo IN ('TAXWIN') AND  (fecha_crea)   ='" . $fecha1 . "'
        GROUP BY usumodif_id,usuario_id)

       UNION
       
       (SELECT usuario_mandante,
               0                                   saldo_recarga,
               DATE_FORMAT(usuario_casino_resumen.fecha_crea, '%Y-%m-%d') fecha,
               0                                   saldo_apuestas,
               0                                   saldo_premios,
               0                                   saldo_impuestos_apuestas_deportivas,
               0                                   saldo_impuestos_premios_deportivas,
               SUM(valor)                          saldo_apuestas_casino,
               0                                   saldo_premios_casino,
               0                                   saldo_notaret_pagadas,
               0                                   saldo_notaret_pend,
               0                                   saldo_notaret_creadas,
               0                                   saldo_ajustes_entrada,
               0                                   saldo_ajustes_salida,
               0                                   saldo_bono,
               0                                   saldo_bono_casino_vivo,
               0                                   saldo_notaret_eliminadas,
               SUM(valor_premios)                  saldo_bono_free_ganado,
               0    billetera_id,
               0    saldo_premios_jackpot_casino,
               0    saldo_premios_jackpot_deportivas,
               0    saldo_ventas_loteria,
               0    saldo_rollbacks_loteria,
               0    saldo_impuestos_apuestas_casino,
               0        saldo_impuestos_depositos
        FROM casino.usuario_casino_resumen
                                 INNER JOIN usuario_mandante ON (usumandante_id=usuario_id)
        WHERE tipo IN ('1','4','6') AND  (usuario_casino_resumen.fecha_crea)   ='" . $fecha1 . "'
        GROUP BY usuario_id
       )

       UNION
       (
         SELECT usuario_mandante,
                0                                   saldo_recarga,
                DATE_FORMAT(usuario_casino_resumen.fecha_crea, '%Y-%m-%d') fecha,
                0                                   saldo_apuestas,
                0                                   saldo_premios,
               0                                   saldo_impuestos_apuestas_deportivas,
               0                                   saldo_impuestos_premios_deportivas,
                0                                   saldo_apuestas_casino,
                SUM(valor)                          saldo_premios_casino,
                0                                   saldo_notaret_pagadas,
                0                                   saldo_notaret_pend,
                0                                   saldo_notaret_creadas,
                0                                   saldo_ajustes_entrada,
                0                                   saldo_ajustes_salida,
                0                                   saldo_bono,
               0                                   saldo_bono_casino_vivo,
               0                                   saldo_notaret_eliminadas,
               0                                   saldo_bono_free_ganado,
               0    billetera_id,
               0    saldo_premios_jackpot_casino,
               0    saldo_premios_jackpot_deportivas,
               0    saldo_ventas_loteria,
               0    saldo_rollbacks_loteria,
               0    saldo_impuestos_apuestas_casino,
               0        saldo_impuestos_depositos
         FROM casino.usuario_casino_resumen
                         INNER JOIN usuario_mandante ON (usumandante_id=usuario_id)

         WHERE tipo IN ('2','5','7') AND  (usuario_casino_resumen.fecha_crea)   ='" . $fecha1 . "'
         GROUP BY usuario_id
       )
       
       UNION
       (
         SELECT usuario_mandante,
                0                                   saldo_recarga,
                DATE_FORMAT(usuario_casino_resumen.fecha_crea, '%Y-%m-%d') fecha,
                0                                   saldo_apuestas,
                0                                   saldo_premios,
               0                                   saldo_impuestos_apuestas_deportivas,
               0                                   saldo_impuestos_premios_deportivas,
                0                                   saldo_apuestas_casino,
                0                          saldo_premios_casino,
                0                                   saldo_notaret_pagadas,
                0                                   saldo_notaret_pend,
                0                                   saldo_notaret_creadas,
                0                                   saldo_ajustes_entrada,
                0                                   saldo_ajustes_salida,
                0                                   saldo_bono,
               0                                   saldo_bono_casino_vivo,
               0                                   saldo_notaret_eliminadas,
               0                                   saldo_bono_free_ganado,
               0    billetera_id,
               0    saldo_premios_jackpot_casino,
               0    saldo_premios_jackpot_deportivas,
               0    saldo_ventas_loteria,
               0    saldo_rollbacks_loteria,
               SUM(valor)    saldo_impuestos_apuestas_casino,
               0        saldo_impuestos_depositos
         FROM casino.usuario_casino_resumen
                         INNER JOIN usuario_mandante ON (usumandante_id=usuario_id)

         WHERE tipo IN ('8','9') AND  (usuario_casino_resumen.fecha_crea)   ='" . $fecha1 . "'
         GROUP BY usuario_id
       )

       UNION

       (SELECT usuario_retiro_resumen.usuario_id,
               0                                   saldo_recarga,
               DATE_FORMAT(fecha_crea, '%Y-%m-%d') fecha,
               0                                   saldo_apuestas,
               0                                   saldo_premios,
               0                                   saldo_impuestos_apuestas_deportivas,
               0                                   saldo_impuestos_premios_deportivas,
               0                                   saldo_apuestas_casino,
               0                                   saldo_premios_casino,
               SUM(usuario_retiro_resumen.valor)                          saldo_notaret_pagadas,
               0                                   saldo_notaret_pend,
               0                                   saldo_notaret_creadas,
               0                                   saldo_ajustes_entrada,
               0                                   saldo_ajustes_salida,
               0                                   saldo_bono,
               0                                   saldo_bono_casino_vivo,
               0                                   saldo_notaret_eliminadas,
               0                                   saldo_bono_free_ganado,
               0    billetera_id,
               0    saldo_premios_jackpot_casino,
               0    saldo_premios_jackpot_deportivas,
               0    saldo_ventas_loteria,
               0    saldo_rollbacks_loteria,
               0    saldo_impuestos_apuestas_casino,
               0        saldo_impuestos_depositos
        FROM casino.usuario_retiro_resumen
        INNER JOIN usuario_perfil ON usuario_perfil.usuario_id = usuario_retiro_resumen.usuario_id
        WHERE usuario_retiro_resumen.estado = 'I' AND  (usuario_retiro_resumen.fecha_crea)   ='" . $fecha1 . "' and usuario_perfil.perfil_id='USUONLINE'
        GROUP BY usuario_retiro_resumen.usuario_id
       )

       UNION

       (SELECT usuario_retiro_resumen.usuario_id,
               0                                   saldo_recarga,
               DATE_FORMAT(fecha_crea, '%Y-%m-%d') fecha,
               0                                   saldo_apuestas,
               0                                   saldo_premios,
               0                                   saldo_impuestos_apuestas_deportivas,
               0                                   saldo_impuestos_premios_deportivas,
               0                                   saldo_apuestas_casino,
               0                                   saldo_premios_casino,
               -SUM(usuario_retiro_resumen.valor)                          saldo_notaret_pagadas,
               0                                   saldo_notaret_pend,
               0                                   saldo_notaret_creadas,
               0                                   saldo_ajustes_entrada,
               0                                   saldo_ajustes_salida,
               0                                   saldo_bono,
               0                                   saldo_bono_casino_vivo,
               0                                   saldo_notaret_eliminadas,
               0                                   saldo_bono_free_ganado,
               0    billetera_id,
               0    saldo_premios_jackpot_casino,
               0    saldo_premios_jackpot_deportivas,
               0    saldo_ventas_loteria,
               0    saldo_rollbacks_loteria,
               0    saldo_impuestos_apuestas_casino,
               0        saldo_impuestos_depositos
        FROM casino.usuario_retiro_resumen
        INNER JOIN usuario_perfil ON usuario_perfil.usuario_id = usuario_retiro_resumen.usuario_id
        WHERE usuario_retiro_resumen.estado = 'D' AND  (usuario_retiro_resumen.fecha_crea)   ='" . $fecha1 . "' and usuario_perfil.perfil_id='USUONLINE'
        GROUP BY usuario_retiro_resumen.usuario_id
       )
       UNION

       (SELECT usuario_id,
               0                                   saldo_recarga,
               DATE_FORMAT(fecha_crea, '%Y-%m-%d') fecha,
               0                                   saldo_apuestas,
               0                                   saldo_premios,
               0                                   saldo_impuestos_apuestas_deportivas,
               0                                   saldo_impuestos_premios_deportivas,
               0                                   saldo_apuestas_casino,
               0                                   saldo_premios_casino,
               0                                   saldo_notaret_pagadas,
               SUM(valor)                          saldo_notaret_pend,
               0                                   saldo_notaret_creadas,
               0                                   saldo_ajustes_entrada,
               0                                   saldo_ajustes_salida,
               0                                   saldo_bono,
               0                                   saldo_bono_casino_vivo,
               0                                   saldo_notaret_eliminadas,
               0                                   saldo_bono_free_ganado,
               0    billetera_id,
               0    saldo_premios_jackpot_casino,
               0    saldo_premios_jackpot_deportivas,
               0    saldo_ventas_loteria,
               0    saldo_rollbacks_loteria,
               0    saldo_impuestos_apuestas_casino,
               0        saldo_impuestos_depositos
        FROM casino.usuario_retiro_resumen
        WHERE estado = 'P' AND  (usuario_retiro_resumen.fecha_crea)   ='" . $fecha1 . "'
        GROUP BY usuario_id
       )
       UNION

       (SELECT usuario_id,
               0                                   saldo_recarga,
               DATE_FORMAT(fecha_crea, '%Y-%m-%d') fecha,
               0                                   saldo_apuestas,
               0                                   saldo_premios,
               0                                   saldo_impuestos_apuestas_deportivas,
               0                                   saldo_impuestos_premios_deportivas,
               0                                   saldo_apuestas_casino,
               0                                   saldo_premios_casino,
               0                                   saldo_notaret_pagadas,
               0                                   saldo_notaret_pend,
               SUM(valor)                          saldo_notaret_creadas,
               0                                   saldo_ajustes_entrada,
               0                                   saldo_ajustes_salida,
               0                                   saldo_bono,
               0                                   saldo_bono_casino_vivo,
               0                                   saldo_notaret_eliminadas,
               0                                   saldo_bono_free_ganado,
               0    billetera_id,
               0    saldo_premios_jackpot_casino,
               0    saldo_premios_jackpot_deportivas,
               0    saldo_ventas_loteria,
               0    saldo_rollbacks_loteria,
               0    saldo_impuestos_apuestas_casino,
               0    saldo_impuestos_depositos
        FROM casino.usuario_retiro_resumen
        WHERE estado = 'A' AND  (fecha_crea)   ='" . $fecha1 . "'
        GROUP BY usuario_id
       )

       UNION

       (SELECT usuario_id,
               0                                   saldo_recarga,
               DATE_FORMAT(fecha_crea, '%Y-%m-%d') fecha,
               0                                   saldo_apuestas,
               0                                   saldo_premios,
               0                                   saldo_impuestos_apuestas_deportivas,
               0                                   saldo_impuestos_premios_deportivas,
               0                                   saldo_apuestas_casino,
               0                                   saldo_premios_casino,
               0                                   saldo_notaret_pagadas,
               0                                   saldo_notaret_pend,
               0                                   saldo_notaret_creadas,
               SUM(valor)                          saldo_ajustes_entrada,
               0                                   saldo_ajustes_salida,
               0                                   saldo_bono,
               0                                   saldo_bono_casino_vivo,
               0                                   saldo_notaret_eliminadas,
               0                                   saldo_bono_free_ganado,
               0    billetera_id,
               0    saldo_premios_jackpot_casino,
               0    saldo_premios_jackpot_deportivas,
               0    saldo_ventas_loteria,
               0    saldo_rollbacks_loteria,
               0    saldo_impuestos_apuestas_casino,
               0        saldo_impuestos_depositos
        FROM casino.usuario_ajustes_resumen
        WHERE tipo = 'E' AND  DATE_FORMAT(fecha_crea, '%Y-%m-%d')   ='" . $fechaSoloDia . "'
        GROUP BY usuario_id
       )
       UNION

       (SELECT usuario_id,
               0                                   saldo_recarga,
               DATE_FORMAT(fecha_crea, '%Y-%m-%d') fecha,
               0                                   saldo_apuestas,
               0                                   saldo_premios,
               0                                   saldo_impuestos_apuestas_deportivas,
               0                                   saldo_impuestos_premios_deportivas,
               0                                   saldo_apuestas_casino,
               0                                   saldo_premios_casino,
               0                                   saldo_notaret_pagadas,
               0                                   saldo_notaret_pend,
               0                                   saldo_notaret_creadas,
               0                                   saldo_ajustes_entrada,
               SUM(valor)                          saldo_ajustes_salida,
               0                                   saldo_bono,
               0                                   saldo_bono_casino_vivo,
               0                                   saldo_notaret_eliminadas,
               0                                   saldo_bono_free_ganado,
               0    billetera_id,
               0    saldo_premios_jackpot_casino,
               0    saldo_premios_jackpot_deportivas,
               0    saldo_ventas_loteria,
               0    saldo_rollbacks_loteria,
               0    saldo_impuestos_apuestas_casino,
               0        saldo_impuestos_depositos
        FROM casino.usuario_ajustes_resumen
        WHERE tipo = 'S' AND  DATE_FORMAT(fecha_crea, '%Y-%m-%d')   ='" . $fechaSoloDia . "'
        GROUP BY usuario_id
       )

       UNION

       (SELECT usuario_id,
               0                                                    saldo_recarga,
               DATE_FORMAT(fecha_crea, '%Y-%m-%d')                  fecha,
               0                                                    saldo_apuestas,
               0                                                    saldo_premios,
               0                                   saldo_impuestos_apuestas_deportivas,
               0                                   saldo_impuestos_premios_deportivas,
               0                                                    saldo_apuestas_casino,
               0                                                    saldo_premios_casino,
               0                                                    saldo_notaret_pagadas,
               0                                                    saldo_notaret_pend,
               0                                                    saldo_notaret_creadas,
               0                                                    saldo_ajustes_entrada,
               0                                                    saldo_ajustes_salida,
               SUM(CASE when (estado = 'L' AND (tipo NOT IN ({$globalJackpotTypesInBonoLog}, 'TC','TL','SC','SCV','SL','TV','FC','DC', 'DL', 'DV', 'NC', 'NL', 'NV'))) then valor when (estado = 'E' AND (tipo NOT IN ({$globalJackpotTypesInBonoLog}, 'TC','TL','SC','SCV','SL','TV','FC','DC', 'DL', 'DV', 'NC', 'NL', 'NV'))) then -valor else 0 end) saldo_bono,
               SUM(CASE when (estado = 'L' AND (tipo IN ('TC','TL','SC','SCV','SL','TV','DC', 'DL', 'DV', 'NC', 'NL', 'NV'))) then valor when (estado = 'E' AND (tipo IN ('TC','TL','SC','SCV','SL','TV','DC', 'DL', 'DV', 'NC', 'NL', 'NV'))) then -valor else 0 end) saldo_bono_casino_vivo,
               0                                   saldo_notaret_eliminadas,
               0                                   saldo_bono_free_ganado,
               0    billetera_id,
               0    saldo_premios_jackpot_casino,
               0    saldo_premios_jackpot_deportivas,
               0    saldo_ventas_loteria,
               0    saldo_rollbacks_loteria,
               0    saldo_impuestos_apuestas_casino,
               0        saldo_impuestos_depositos
        FROM casino.usuario_bono_resumen
        WHERE DATE_FORMAT(fecha_crea, '%Y-%m-%d')   ='" . $fechaSoloDia . "'
        GROUP BY usuario_id
       )
       

       UNION

        (SELECT usuario_id,
               0                                   saldo_recarga,
               DATE_FORMAT(fecha_crea, '%Y-%m-%d') fecha,
               0                                   saldo_apuestas,
               0                                   saldo_premios,
               0                                   saldo_impuestos_apuestas_deportivas,
               0                                   saldo_impuestos_premios_deportivas,
               0                                   saldo_apuestas_casino,
               0                                   saldo_premios_casino,
               0                                   saldo_notaret_pagadas,
               0                                   saldo_notaret_pend,
               0                          saldo_notaret_creadas,
               0                                   saldo_ajustes_entrada,
               0                                   saldo_ajustes_salida,
               0                                   saldo_bono,
               0                                   saldo_bono_casino_vivo,
               SUM(valor)                                   saldo_notaret_eliminadas,
               0                                   saldo_bono_free_ganado,
               0    billetera_id,
               0    saldo_premios_jackpot_casino,
               0    saldo_premios_jackpot_deportivas,
               0    saldo_ventas_loteria,
               0    saldo_rollbacks_loteria,
               0    saldo_impuestos_apuestas_casino,
               0        saldo_impuestos_depositos
        FROM casino.usuario_retiro_resumen
        WHERE (estado = 'R' OR estado = 'E' OR estado = 'D' OR estado = 'Z' OR estado = 'W') AND  (fecha_crea)   ='" . $fecha1 . "'
        GROUP BY usuario_id
       )

       UNION

       (SELECT usuario_id,
               0                                                    saldo_recarga,
               DATE_FORMAT(fecha_crea, '%Y-%m-%d')                  fecha,
               0                                                    saldo_apuestas,
               0                                                    saldo_premios,
               0                                   saldo_impuestos_apuestas_deportivas,
               0                                   saldo_impuestos_premios_deportivas,
               0                                                    saldo_apuestas_casino,
               0                                                    saldo_premios_casino,
               0                                                    saldo_notaret_pagadas,
               0                                                    saldo_notaret_pend,
               0                                                    saldo_notaret_creadas,
               0                                                    saldo_ajustes_entrada,
               0                                                    saldo_ajustes_salida,
               0 saldo_bono,
               0                                   saldo_bono_casino_vivo,
               0                                   saldo_notaret_eliminadas,
               SUM(CASE when tipo = 'W' then valor else 0 end)  saldo_bono_free_ganado,
               0    billetera_id,
               0    saldo_premios_jackpot_casino,
               0    saldo_premios_jackpot_deportivas,
               0    saldo_ventas_loteria,
               0    saldo_rollbacks_loteria,
               0    saldo_impuestos_apuestas_casino,
               0        saldo_impuestos_depositos
        FROM casino.usuario_bono_resumen
        WHERE DATE_FORMAT(fecha_crea, '%Y-%m-%d')   ='" . $fecha1 . "'
        GROUP BY usuario_id
       )
       
       UNION

       (SELECT usuario_id,
               0                                                    saldo_recarga,
               DATE_FORMAT(fecha_crea, '%Y-%m-%d')                  fecha,
               0                                                    saldo_apuestas,
               0                                                    saldo_premios,
               0                                   saldo_impuestos_apuestas_deportivas,
               0                                   saldo_impuestos_premios_deportivas,
               0                                                    saldo_apuestas_casino,
               0                                                    saldo_premios_casino,
               0                                                    saldo_notaret_pagadas,
               0                                                    saldo_notaret_pend,
               0                                                    saldo_notaret_creadas,
               0                                                    saldo_ajustes_entrada,
               0                                                    saldo_ajustes_salida,
               0 saldo_bono,
               0                                   saldo_bono_casino_vivo,
               0                                   saldo_notaret_eliminadas,
               0                                   saldo_bono_free_ganado,
               0    billetera_id,
               SUM(valor)    saldo_premios_jackpot_casino,
               0    saldo_premios_jackpot_deportivas,
               0    saldo_ventas_loteria,
               0    saldo_rollbacks_loteria,
               0    saldo_impuestos_apuestas_casino,
               0        saldo_impuestos_depositos
        FROM casino.usuario_bono_resumen
        WHERE DATE_FORMAT(fecha_crea, '%Y-%m-%d')   ='" . $fechaSoloDia . "' AND estado = 'L' AND tipo IN ({$casinoJackpotTypesInBonoLog})
        GROUP BY usuario_id
       )
       
        UNION

       (SELECT usuario_id,
               0                                                    saldo_recarga,
               DATE_FORMAT(fecha_crea, '%Y-%m-%d')                  fecha,
               0                                                    saldo_apuestas,
               0                                                    saldo_premios,
               0                                   saldo_impuestos_apuestas_deportivas,
               0                                   saldo_impuestos_premios_deportivas,
               0                                                    saldo_apuestas_casino,
               0                                                    saldo_premios_casino,
               0                                                    saldo_notaret_pagadas,
               0                                                    saldo_notaret_pend,
               0                                                    saldo_notaret_creadas,
               0                                                    saldo_ajustes_entrada,
               0                                                    saldo_ajustes_salida,
               0                                                    saldo_bono,
               0                                                    saldo_bono_casino_vivo,
               0                                                    saldo_notaret_eliminadas,
               0                                                    saldo_bono_free_ganado,
               0                                                    billetera_id,
               0                                                    saldo_premios_jackpot_casino,
               SUM(valor)                                           saldo_premios_jackpot_deportivas,
               0    saldo_ventas_loteria,
               0    saldo_rollbacks_loteria,
               0    saldo_impuestos_apuestas_casino,
               0        saldo_impuestos_depositos
        FROM casino.usuario_bono_resumen
        WHERE DATE_FORMAT(fecha_crea, '%Y-%m-%d')   ='" . $fechaSoloDia . "' AND estado = 'L' AND tipo IN ({$sportbookJackpotTypesInBonoLog})
        GROUP BY usuario_id
       )
       
       
       
     ) data
     INNER JOIN usuario ON (data.usuario_id = usuario.usuario_id)
     INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = usuario.usuario_id)

WHERE data.usuario_id IS NOT NULL AND usuario_perfil.perfil_id = 'USUONLINE'    AND usuario_perfil.mandante NOT IN (3,4,5,6,7,10,22,17,25)
  AND data.fecha = '" . $fechaSoloDia . "'

GROUP BY data.usuario_id,data.billetera_id;

";
        $BonoInterno = new BonoInterno();

        $data = $BonoInterno->execQuery('', $UsuarioSaldoFinalConDetalles);


        foreach ($data as $datanum) {

            $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
            $transaccion = $BonoDetalleMySqlDAO->getTransaction();
            $transaccion->getConnection()->beginTransaction();

            $sql = "INSERT INTO {$nametable} (usuario_id, mandante, fecha, saldo_recarga, saldo_apuestas, saldo_premios,
                           saldo_apuestas_casino, saldo_premios_casino, saldo_notaret_pagadas,
                           saldo_notaret_pend, saldo_notaret_creadas, saldo_ajustes_entrada, saldo_ajustes_salida,
                           saldo_inicial, saldo_final, saldo_bono,saldo_bono_casino_vivo, saldo_creditos_inicial, saldo_creditos_base_inicial,
                           saldo_creditos_final, saldo_creditos_base_final,saldo_notaret_eliminadas,saldo_bono_free_ganado,
                           billetera_id,saldo_impuestos_apuestas_deportivas,saldo_impuestos_premios_deportivas, saldo_premios_jackpot_casino,
                           saldo_premios_jackpot_deportivas, saldo_ventas_loteria, saldo_rollbacks_loteria,saldo_impuestos_apuestas_casino, saldo_impuestos_depositos) VALUES (
                           '" . $datanum->{'data.usuario_id'} . "',
                           '" . $datanum->{'usuario.mandante'} . "',
                           '" . $datanum->{'.fecha'} . "',
                           '" . $datanum->{'.saldo_recarga'} . "',
                           '" . $datanum->{'.saldo_apuestas'} . "',
                           '" . $datanum->{'.saldo_premios'} . "',
                           '" . $datanum->{'.saldo_apuestas_casino'} . "',
                           '" . $datanum->{'.saldo_premios_casino'} . "',
                           '" . $datanum->{'.saldo_notaret_pagadas'} . "',
                           '" . $datanum->{'.saldo_notaret_pend'} . "',
                           '" . $datanum->{'.saldo_notaret_creadas'} . "',
                           '" . $datanum->{'.saldo_ajustes_entrada'} . "',
                           '" . $datanum->{'.saldo_ajustes_salida'} . "',
                           '" . $datanum->{'.saldo_inicial'} . "',
                           '" . $datanum->{'.saldo_final'} . "',
                           '" . $datanum->{'.saldo_bono'} . "',
                           '" . $datanum->{'.saldo_bono_casino_vivo'} . "',
                           '" . $datanum->{'.saldo_creditos_inicial'} . "',
                           '" . $datanum->{'.saldo_creditos_base_inicial'} . "',
                           '" . $datanum->{'.saldo_creditos_final'} . "',
                           '" . $datanum->{'.saldo_creditos_base_final'} . "',
                           '" . $datanum->{'.saldo_notaret_eliminadas'} . "',
                           '" . $datanum->{'.saldo_bono_free_ganado'} . "',
                           '" . $datanum->{'data.billetera_id'} . "',
                           '" . $datanum->{'.saldo_impuestos_apuestas_deportivas'} . "',
                           '" . $datanum->{'.saldo_impuestos_premios_deportivas'} . "',
                           '" . $datanum->{'.saldo_premios_jackpot_casino'} . "',
                           '" . $datanum->{'.saldo_premios_jackpot_deportivas'} . "',
                           '" . $datanum->{'.saldo_ventas_loteria'} . "',
                           '" . $datanum->{'.saldo_rollbacks_loteria'} . "',
                           '" . $datanum->{'.saldo_impuestos_apuestas_casino'} . "', 
                           '" . $datanum->{'.saldo_impuestos_depositos'} . "'
                                 )
       
       
ON DUPLICATE KEY UPDATE {$nametable}.saldo_recarga      = '" . $datanum->{'.saldo_recarga'} . "',
                         {$nametable}.saldo_apuestas      = '" . $datanum->{'.saldo_apuestas'} . "',
                         {$nametable}.saldo_premios      = '" . $datanum->{'.saldo_premios'} . "',
                         {$nametable}.saldo_apuestas_casino      = '" . $datanum->{'.saldo_apuestas_casino'} . "',
                         {$nametable}.saldo_premios_casino      = '" . $datanum->{'.saldo_premios_casino'} . "',
                         {$nametable}.saldo_notaret_pagadas      = '" . $datanum->{'.saldo_notaret_pagadas'} . "',
                         {$nametable}.saldo_notaret_pend      = '" . $datanum->{'.saldo_notaret_pend'} . "',
                         {$nametable}.saldo_notaret_creadas      = '" . $datanum->{'.saldo_notaret_creadas'} . "',
                         {$nametable}.saldo_ajustes_entrada      = '" . $datanum->{'.saldo_ajustes_entrada'} . "',
                         {$nametable}.saldo_ajustes_salida      = '" . $datanum->{'.saldo_ajustes_salida'} . "',
                         {$nametable}.saldo_bono      = '" . $datanum->{'.saldo_bono'} . "',
                         {$nametable}.saldo_bono_casino_vivo      = '" . $datanum->{'.saldo_bono_casino_vivo'} . "',
                         {$nametable}.saldo_notaret_eliminadas      = '" . $datanum->{'.saldo_notaret_eliminadas'} . "',
                         {$nametable}.saldo_bono_free_ganado      = '" . $datanum->{'.saldo_bono_free_ganado'} . "',
                         {$nametable}.saldo_impuestos_apuestas_deportivas      = '" . $datanum->{'.saldo_impuestos_apuestas_deportivas'} . "',
                         {$nametable}.saldo_impuestos_premios_deportivas      = '" . $datanum->{'.saldo_impuestos_premios_deportivas'} . "',
                         {$nametable}.saldo_premios_jackpot_casino      = '" . $datanum->{'.saldo_premios_jackpot_casino'} . "',
                         {$nametable}.saldo_premios_jackpot_deportivas      = '" . $datanum->{'.saldo_premios_jackpot_deportivas'} . "',
                         {$nametable}.saldo_ventas_loteria      = '" . $datanum->{'.saldo_ventas_loteria'} . "',
                         {$nametable}.saldo_rollbacks_loteria      = '" . $datanum->{'.saldo_rollbacks_loteria'} . "',
                         {$nametable}.saldo_impuestos_apuestas_casino      = '" . $datanum->{'.saldo_impuestos_apuestas_casino'} . "',
                         {$nametable}.saldo_impuestos_depositos      = '" . $datanum->{'.saldo_impuestos_depositos'} . "'
                         

       ";

            $BonoInterno->execQuery($transaccion, $sql);
            if (true) {
                $sql = "INSERT INTO usuario_saldoresumen (usuario_id, mandante, saldo_recarga, saldo_apuestas, saldo_premios,
                           saldo_apuestas_casino, saldo_premios_casino, saldo_notaret_pagadas,
                           saldo_notaret_pend, saldo_notaret_creadas, saldo_ajustes_entrada, saldo_ajustes_salida,
                           saldo_inicial, saldo_final, saldo_bono, saldo_creditos_inicial, saldo_creditos_base_inicial,
                           saldo_creditos_final, saldo_creditos_base_final,saldo_notaret_eliminadas,saldo_bono_free_ganado,billetera_id,
                            saldo_impuestos_apuestas_deportivas,saldo_impuestos_premios_deportivas, saldo_premios_jackpot_casino, saldo_premios_jackpot_deportivas, 
                                  saldo_ventas_loteria, saldo_rollbacks_loteria, saldo_impuestos_apuestas_casino,saldo_impuestos_depositos) VALUES (
                           '" . $datanum->{'data.usuario_id'} . "',
                           '" . $datanum->{'usuario.mandante'} . "',
                           '" . $datanum->{'.saldo_recarga'} . "',
                           '" . $datanum->{'.saldo_apuestas'} . "',
                           '" . $datanum->{'.saldo_premios'} . "',
                           '" . $datanum->{'.saldo_apuestas_casino'} . "',
                           '" . $datanum->{'.saldo_premios_casino'} . "',
                           '" . $datanum->{'.saldo_notaret_pagadas'} . "',
                           '" . $datanum->{'.saldo_notaret_pend'} . "',
                           '" . $datanum->{'.saldo_notaret_creadas'} . "',
                           '" . $datanum->{'.saldo_ajustes_entrada'} . "',
                           '" . $datanum->{'.saldo_ajustes_salida'} . "',
                           '" . $datanum->{'.saldo_inicial'} . "',
                           '" . $datanum->{'.saldo_final'} . "',
                           '" . $datanum->{'.saldo_bono'} . "',
                           '" . $datanum->{'.saldo_creditos_inicial'} . "',
                           '" . $datanum->{'.saldo_creditos_base_inicial'} . "',
                           '" . $datanum->{'.saldo_creditos_final'} . "',
                           '" . $datanum->{'.saldo_creditos_base_final'} . "',
                           '" . $datanum->{'.saldo_notaret_eliminadas'} . "',
                           '" . $datanum->{'.saldo_bono_free_ganado'} . "',
                           '" . $datanum->{'data.billetera_id'} . "',
                           '" . $datanum->{'.saldo_impuestos_apuestas_deportivas'} . "',
                           '" . $datanum->{'.saldo_impuestos_premios_deportivas'} . "',
                           '" . $datanum->{'.saldo_premios_jackpot_casino'} . "',
                           '" . $datanum->{'.saldo_premios_jackpot_deportivas'} . "',
                           '" . $datanum->{'.saldo_ventas_loteria'} . "',
                           '" . $datanum->{'.saldo_rollbacks_loteria'} . "',
                           '" . $datanum->{'.saldo_impuestos_apuestas_casino'} . "',
                           '" . $datanum->{'.saldo_impuestos_depositos'} . "'
                                 )
       
       
ON DUPLICATE KEY UPDATE usuario_saldoresumen.saldo_recarga      = usuario_saldoresumen.saldo_recarga + '" . $datanum->{'.saldo_recarga'} . "',
                         usuario_saldoresumen.saldo_apuestas      = usuario_saldoresumen.saldo_apuestas + '" . $datanum->{'.saldo_apuestas'} . "',
                         usuario_saldoresumen.saldo_premios      = usuario_saldoresumen.saldo_premios + '" . $datanum->{'.saldo_premios'} . "',
                         usuario_saldoresumen.saldo_apuestas_casino      = usuario_saldoresumen.saldo_apuestas_casino + '" . $datanum->{'.saldo_apuestas_casino'} . "',
                         usuario_saldoresumen.saldo_premios_casino      = usuario_saldoresumen.saldo_premios_casino + '" . $datanum->{'.saldo_premios_casino'} . "',
                         usuario_saldoresumen.saldo_notaret_pagadas      = usuario_saldoresumen.saldo_notaret_pagadas + '" . $datanum->{'.saldo_notaret_pagadas'} . "',
                         usuario_saldoresumen.saldo_notaret_pend      = usuario_saldoresumen.saldo_notaret_pend + '" . $datanum->{'.saldo_notaret_pend'} . "',
                         usuario_saldoresumen.saldo_notaret_creadas      = usuario_saldoresumen.saldo_notaret_creadas + '" . $datanum->{'.saldo_notaret_creadas'} . "',
                         usuario_saldoresumen.saldo_ajustes_entrada      = usuario_saldoresumen.saldo_ajustes_entrada + '" . $datanum->{'.saldo_ajustes_entrada'} . "',
                         usuario_saldoresumen.saldo_ajustes_salida      = usuario_saldoresumen.saldo_ajustes_salida + '" . $datanum->{'.saldo_ajustes_salida'} . "',
                         usuario_saldoresumen.saldo_bono      = usuario_saldoresumen.saldo_bono + '" . $datanum->{'.saldo_bono'} . "',
                         usuario_saldoresumen.saldo_notaret_eliminadas      = usuario_saldoresumen.saldo_notaret_eliminadas + '" . $datanum->{'.saldo_notaret_eliminadas'} . "',
                         usuario_saldoresumen.saldo_bono_free_ganado      = usuario_saldoresumen.saldo_bono_free_ganado + '" . $datanum->{'.saldo_bono_free_ganado'} . "',
                         usuario_saldoresumen.saldo_impuestos_apuestas_deportivas      = '" . $datanum->{'.saldo_impuestos_apuestas_deportivas'} . "',
                         usuario_saldoresumen.saldo_impuestos_premios_deportivas      = '" . $datanum->{'.saldo_impuestos_premios_deportivas'} . "',
                         usuario_saldoresumen.saldo_premios_jackpot_casino      = usuario_saldoresumen.saldo_premios_jackpot_casino + '" . $datanum->{'.saldo_premios_jackpot_casino'} . "',
                         usuario_saldoresumen.saldo_premios_jackpot_deportivas      = usuario_saldoresumen.saldo_premios_jackpot_deportivas + '" . $datanum->{'.saldo_premios_jackpot_deportivas'} . "',
                         usuario_saldoresumen.saldo_ventas_loteria      = usuario_saldoresumen.saldo_ventas_loteria + '" . $datanum->{'.saldo_ventas_loteria'} . "',
                         usuario_saldoresumen.saldo_rollbacks_loteria      = usuario_saldoresumen.saldo_rollbacks_loteria + '" . $datanum->{'.saldo_rollbacks_loteria'} . "',
                         usuario_saldoresumen.saldo_impuestos_apuestas_casino      = usuario_saldoresumen.saldo_impuestos_apuestas_casino + '" . $datanum->{'.saldo_impuestos_apuestas_casino'} . "',
                         usuario_saldoresumen.saldo_impuestos_depositos      = usuario_saldoresumen.saldo_impuestos_depositos + '" . $datanum->{'.saldo_impuestos_depositos'} . "'
       ";

                $BonoInterno->execQuery($transaccion, $sql);
            }
            $transaccion->commit();

        }


        $data = $BonoInterno->execQuery('', $UsuarioPuntoVentaFinalConDetalles);


        foreach ($data as $datanum) {
            $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
            $transaccion = $BonoDetalleMySqlDAO->getTransaction();
            $transaccion->getConnection()->beginTransaction();

            $sql = "INSERT INTO {$nametable} (usuario_id, mandante, fecha, saldo_recarga, saldo_apuestas, saldo_premios,
                           saldo_apuestas_casino, saldo_premios_casino, saldo_notaret_pagadas,
                           saldo_notaret_pend, saldo_notaret_creadas, saldo_ajustes_entrada, saldo_ajustes_salida,
                           saldo_inicial, saldo_final, saldo_bono, saldo_creditos_inicial, saldo_creditos_base_inicial,
                           saldo_creditos_final, saldo_creditos_base_final,saldo_notaret_eliminadas,saldo_bono_free_ganado,billetera_id,saldo_impuestos_apuestas_deportivas,saldo_impuestos_premios_deportivas, saldo_premios_jackpot_casino,saldo_premios_jackpot_deportivas, saldo_impuestos_apuestas_casino, saldo_impuestos_depositos) VALUES (
                           '" . $datanum->{'data.usuario_id'} . "',
                           '" . $datanum->{'usuario.mandante'} . "',
                           '" . $datanum->{'.fecha'} . "',
                           '" . $datanum->{'.saldo_recarga'} . "',
                           '" . $datanum->{'.saldo_apuestas'} . "',
                           '" . $datanum->{'.saldo_premios'} . "',
                           '" . $datanum->{'.saldo_apuestas_casino'} . "',
                           '" . $datanum->{'.saldo_premios_casino'} . "',
                           '" . $datanum->{'.saldo_notaret_pagadas'} . "',
                           '" . $datanum->{'.saldo_notaret_pend'} . "',
                           '" . $datanum->{'.saldo_notaret_creadas'} . "',
                           '" . $datanum->{'.saldo_ajustes_entrada'} . "',
                           '" . $datanum->{'.saldo_ajustes_salida'} . "',
                           '" . $datanum->{'.saldo_inicial'} . "',
                           '" . $datanum->{'.saldo_final'} . "',
                           '" . $datanum->{'.saldo_bono'} . "',
                           '" . $datanum->{'.saldo_creditos_inicial'} . "',
                           '" . $datanum->{'.saldo_creditos_base_inicial'} . "',
                           '" . $datanum->{'.saldo_creditos_final'} . "',
                           '" . $datanum->{'.saldo_creditos_base_final'} . "',
                           '" . $datanum->{'.saldo_notaret_eliminadas'} . "',
                           '" . $datanum->{'.saldo_bono_free_ganado'} . "',
                           '0',
                           '" . $datanum->{'.saldo_impuestos_apuestas_deportivas'} . "',
                           '" . $datanum->{'.saldo_impuestos_premios_deportivas'} . "',
                           '0',
                           '0',
                           '" . $datanum->{'.saldo_impuestos_apuestas_casino'} . "',
                           '" . $datanum->{'.saldo_impuestos_depositos'} . "'
                                 )
       
       
ON DUPLICATE KEY UPDATE {$nametable}.saldo_recarga      = '" . $datanum->{'.saldo_recarga'} . "',
                         {$nametable}.saldo_apuestas      = '" . $datanum->{'.saldo_apuestas'} . "',
                         {$nametable}.saldo_premios      = '" . $datanum->{'.saldo_premios'} . "',
                         {$nametable}.saldo_apuestas_casino      = '" . $datanum->{'.saldo_apuestas_casino'} . "',
                         {$nametable}.saldo_premios_casino      = '" . $datanum->{'.saldo_premios_casino'} . "',
                         {$nametable}.saldo_notaret_pagadas      = '" . $datanum->{'.saldo_notaret_pagadas'} . "',
                         {$nametable}.saldo_notaret_pend      = '" . $datanum->{'.saldo_notaret_pend'} . "',
                         {$nametable}.saldo_notaret_creadas      = '" . $datanum->{'.saldo_notaret_creadas'} . "',
                         {$nametable}.saldo_ajustes_entrada      = '" . $datanum->{'.saldo_ajustes_entrada'} . "',
                         {$nametable}.saldo_ajustes_salida      = '" . $datanum->{'.saldo_ajustes_salida'} . "',
                         {$nametable}.saldo_bono      = '" . $datanum->{'.saldo_bono'} . "',
                         {$nametable}.saldo_notaret_eliminadas      = '" . $datanum->{'.saldo_notaret_eliminadas'} . "',
                         {$nametable}.saldo_bono_free_ganado      = '" . $datanum->{'.saldo_bono_free_ganado'} . "',
                         {$nametable}.saldo_impuestos_apuestas_deportivas      = '" . $datanum->{'.saldo_impuestos_apuestas_deportivas'} . "',
                         {$nametable}.saldo_impuestos_premios_deportivas      = '" . $datanum->{'.saldo_impuestos_premios_deportivas'} . "',
                         {$nametable}.saldo_premios_jackpot_casino         =     {$nametable}.saldo_premios_jackpot_casino + 0,
                         {$nametable}.saldo_premios_jackpot_deportivas         =     {$nametable}.saldo_premios_jackpot_deportivas + 0,
                         {$nametable}.saldo_impuestos_apuestas_casino    = '" . $datanum->{'.saldo_impuestos_apuestas_casino'} . "',
                         {$nametable}.saldo_impuestos_depositos      = '" . $datanum->{'.saldo_impuestos_depositos'} . "'";

            $BonoInterno->execQuery($transaccion, $sql);
            if (true) {
                $sql = "INSERT INTO usuario_saldoresumen (usuario_id, mandante, saldo_recarga, saldo_apuestas, saldo_premios,
                           saldo_apuestas_casino, saldo_premios_casino, saldo_notaret_pagadas,
                           saldo_notaret_pend, saldo_notaret_creadas, saldo_ajustes_entrada, saldo_ajustes_salida,
                           saldo_inicial, saldo_final, saldo_bono, saldo_creditos_inicial, saldo_creditos_base_inicial,
                           saldo_creditos_final, saldo_creditos_base_final,saldo_notaret_eliminadas,saldo_bono_free_ganado,billetera_id,saldo_impuestos_apuestas_deportivas,saldo_impuestos_premios_deportivas, saldo_impuestos_apuestas_casino, saldo_impuestos_depositos) VALUES (
                           '" . $datanum->{'data.usuario_id'} . "',
                           '" . $datanum->{'usuario.mandante'} . "',
                           '" . $datanum->{'.saldo_recarga'} . "',
                           '" . $datanum->{'.saldo_apuestas'} . "',
                           '" . $datanum->{'.saldo_premios'} . "',
                           '" . $datanum->{'.saldo_apuestas_casino'} . "',
                           '" . $datanum->{'.saldo_premios_casino'} . "',
                           '" . $datanum->{'.saldo_notaret_pagadas'} . "',
                           '" . $datanum->{'.saldo_notaret_pend'} . "',
                           '" . $datanum->{'.saldo_notaret_creadas'} . "',
                           '" . $datanum->{'.saldo_ajustes_entrada'} . "',
                           '" . $datanum->{'.saldo_ajustes_salida'} . "',
                           '" . $datanum->{'.saldo_inicial'} . "',
                           '" . $datanum->{'.saldo_final'} . "',
                           '" . $datanum->{'.saldo_bono'} . "',
                           '" . $datanum->{'.saldo_creditos_inicial'} . "',
                           '" . $datanum->{'.saldo_creditos_base_inicial'} . "',
                           '" . $datanum->{'.saldo_creditos_final'} . "',
                           '" . $datanum->{'.saldo_creditos_base_final'} . "',
                           '" . $datanum->{'.saldo_notaret_eliminadas'} . "',
                           '" . $datanum->{'.saldo_bono_free_ganado'} . "',
                           '0',
                           '" . $datanum->{'.saldo_impuestos_apuestas_deportivas'} . "',
                           '" . $datanum->{'.saldo_impuestos_premios_deportivas'} . "',
                           '" . $datanum->{'.saldo_impuestos_apuestas_casino'} . "',
                           '" . $datanum->{'.saldo_impuestos_depositos'} . "'
                           
                                 )
       
       
ON DUPLICATE KEY UPDATE usuario_saldoresumen.saldo_recarga      = usuario_saldoresumen.saldo_recarga + '" . $datanum->{'.saldo_recarga'} . "',
                         usuario_saldoresumen.saldo_apuestas      = usuario_saldoresumen.saldo_apuestas +'" . $datanum->{'.saldo_apuestas'} . "',
                         usuario_saldoresumen.saldo_premios      = usuario_saldoresumen.saldo_premios + '" . $datanum->{'.saldo_premios'} . "',
                         usuario_saldoresumen.saldo_apuestas_casino      = usuario_saldoresumen.saldo_apuestas_casino + '" . $datanum->{'.saldo_apuestas_casino'} . "',
                         usuario_saldoresumen.saldo_premios_casino      = usuario_saldoresumen.saldo_premios_casino + '" . $datanum->{'.saldo_premios_casino'} . "',
                         usuario_saldoresumen.saldo_notaret_pagadas      = usuario_saldoresumen.saldo_notaret_pagadas + '" . $datanum->{'.saldo_notaret_pagadas'} . "',
                         usuario_saldoresumen.saldo_notaret_pend      = usuario_saldoresumen.saldo_notaret_pend + '" . $datanum->{'.saldo_notaret_pend'} . "',
                         usuario_saldoresumen.saldo_notaret_creadas      = usuario_saldoresumen.saldo_notaret_creadas + '" . $datanum->{'.saldo_notaret_creadas'} . "',
                         usuario_saldoresumen.saldo_ajustes_entrada      = usuario_saldoresumen.saldo_ajustes_entrada + '" . $datanum->{'.saldo_ajustes_entrada'} . "',
                         usuario_saldoresumen.saldo_ajustes_salida      = usuario_saldoresumen.saldo_ajustes_salida + '" . $datanum->{'.saldo_ajustes_salida'} . "',
                         usuario_saldoresumen.saldo_bono      = usuario_saldoresumen.saldo_bono + '" . $datanum->{'.saldo_bono'} . "',
                         usuario_saldoresumen.saldo_notaret_eliminadas      = usuario_saldoresumen.saldo_notaret_eliminadas + '" . $datanum->{'.saldo_notaret_eliminadas'} . "',
                         usuario_saldoresumen.saldo_bono_free_ganado      = usuario_saldoresumen.saldo_bono_free_ganado + '" . $datanum->{'.saldo_bono_free_ganado'} . "',
                         usuario_saldoresumen.saldo_impuestos_apuestas_deportivas      = usuario_saldoresumen.saldo_impuestos_apuestas_deportivas+'" . $datanum->{'.saldo_impuestos_apuestas_deportivas'} . "',
                         usuario_saldoresumen.saldo_impuestos_premios_deportivas      =usuario_saldoresumen.saldo_impuestos_premios_deportivas + '" . $datanum->{'.saldo_impuestos_premios_deportivas'} . "',
                         usuario_saldoresumen.saldo_impuestos_apuestas_casino      = usuario_saldoresumen.saldo_impuestos_apuestas_casino + '" . $datanum->{'.saldo_impuestos_apuestas_casino'} . "',
                         usuario_saldoresumen.saldo_impuestos_depositos      = usuario_saldoresumen.saldo_impuestos_depositos +'" . $datanum->{'.saldo_impuestos_depositos'} . "'

       ";

                $BonoInterno->execQuery($transaccion, $sql);
            }
            $transaccion->commit();
        }

    }


}