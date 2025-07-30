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
 * Clase 'CronJobResumenesPaso36AM'
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
class CronJobResumenesPaso36AM
{


    public function __construct()
    {
    }

    public function execute()
    {


        $hour = date('H');
        if (intval($hour) > 9) {
            //exit();
        }

        ini_set('memory_limit', '-1');
        ini_set('mysql.connect_timeout', 30000);
        ini_set('default_socket_timeout', 30000);

        $message = "*CRON: (Inicio) * " . " ResumenesPaso3 - Fecha: " . date("Y-m-d H:i:s");

        exec("php -f " . __DIR__ . "../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");


        $ResumenesCron = new ResumenesCron();

//$ResumenesCron->generateResumenes();

        $fechaSoloDia = date("Y-m-d", strtotime('-1 days'));
        $fecha1 = date("Y-m-d 00:00:00", strtotime('-1 days'));
        $fecha2 = date("Y-m-d 23:59:59", strtotime('-1 days'));

        if ($_REQUEST["diaSpc"] != "") {

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
        $nametable = " usuario_saldo ";
        if ($fechaSoloDia < date("Y-m-d", strtotime('-1 days'))) {
            $nametable = " usuario_saldo_" . date("Y_m_d", strtotime($fechaSoloDia));
        }
        try {

            $sqlUsuarioSaldoResumen_Usuario = "
SELECT usuario.mandante,
       pais.pais_id,
       usuario_saldo.fecha,
       1,
       SUM(CASE WHEN usuario_perfil.perfil_id = 'USUONLINE' THEN saldo_recarga ELSE 0 END)        saldo_recarga,
       SUM(CASE WHEN usuario_perfil.perfil_id = 'USUONLINE' THEN saldo_apuestas ELSE 0 END)       saldo_apuestas,
       SUM(CASE WHEN usuario_perfil.perfil_id = 'USUONLINE' THEN saldo_premios ELSE 0 END)        saldo_premios,
       SUM(CASE
               WHEN usuario_perfil.perfil_id = 'USUONLINE' THEN saldo_notaret_pagadas
               ELSE 0 END)                                                                        saldo_notaret_pagadas,
       SUM(CASE WHEN usuario_perfil.perfil_id = 'USUONLINE' THEN saldo_notaret_pend ELSE 0 END)   saldo_notaret_pend,
       SUM(CASE
               WHEN usuario_perfil.perfil_id = 'USUONLINE' THEN saldo_ajustes_entrada
               ELSE 0 END)                                                                        saldo_ajustes_entrada,
       SUM(CASE WHEN usuario_perfil.perfil_id = 'USUONLINE' THEN saldo_ajustes_salida ELSE 0 END) saldo_ajustes_salida,
       SUM(CASE WHEN usuario_perfil.perfil_id = 'USUONLINE' THEN saldo_inicial ELSE 0 END)        saldo_inicial,
       SUM(CASE WHEN usuario_perfil.perfil_id = 'USUONLINE' THEN saldo_final ELSE 0 END)          saldo_final,
       SUM(CASE WHEN usuario_perfil.perfil_id = 'USUONLINE' THEN saldo_bono ELSE 0 END)           saldo_bono,
       SUM(CASE
               WHEN usuario_perfil.perfil_id = 'USUONLINE' THEN saldo_creditos_inicial
               ELSE 0 END)                                                                        saldo_creditos_inicial,
       SUM(CASE
               WHEN usuario_perfil.perfil_id = 'USUONLINE'
                   THEN saldo_creditos_base_inicial ELSE 0 END)                                              saldo_creditos_base_inicial,
       SUM(CASE WHEN usuario_perfil.perfil_id = 'USUONLINE' THEN saldo_creditos_final ELSE 0 END) saldo_creditos_final,
       SUM(CASE
               WHEN usuario_perfil.perfil_id = 'USUONLINE' THEN saldo_creditos_base_final
               ELSE 0 END)                                                                        saldo_creditos_base_final,

       SUM(CASE
               WHEN usuario_perfil.perfil_id = 'USUONLINE' THEN saldo_notaret_creadas
               ELSE 0 END)                                                                        saldo_notaret_creadas,
       SUM(CASE
               WHEN usuario_perfil.perfil_id = 'USUONLINE' THEN saldo_apuestas_casino
               ELSE 0 END)                                                                        saldo_apuestas_casino,
       SUM(CASE WHEN usuario_perfil.perfil_id = 'USUONLINE' THEN saldo_premios_casino ELSE 0 END) saldo_premios_casino,

       SUM(CASE
               WHEN usuario_perfil.perfil_id = 'USUONLINE' THEN saldo_notaret_eliminadas
               ELSE 0 END)                                                                        saldo_notaret_eliminadas,


       SUM(CASE
               WHEN usuario_perfil.perfil_id = 'USUONLINE' THEN saldo_bono_free_ganado
               ELSE 0 END)                                                                        saldo_bono_free_ganado,
       SUM(CASE
               WHEN usuario_perfil.perfil_id = 'USUONLINE'
                   THEN saldo_bono_casino_free_ganado
               ELSE 0 END)                                                                        saldo_bono_casino_free_ganado,
       SUM(CASE
               WHEN usuario_perfil.perfil_id = 'USUONLINE' THEN saldo_bono_casino_vivo
               ELSE 0 END)                                                                        saldo_bono_casino_vivo,
       SUM(CASE
               WHEN usuario_perfil.perfil_id = 'USUONLINE'
                   THEN saldo_bono_casino_vivo_free_ganado
               ELSE 0 END)                                                                        saldo_bono_casino_vivo_free_ganado,
       SUM(CASE WHEN usuario_perfil.perfil_id = 'USUONLINE' THEN saldo_bono_virtual ELSE 0 END)   saldo_bono_virtual,
       SUM(CASE
               WHEN usuario_perfil.perfil_id = 'USUONLINE'
                   THEN saldo_bono_virtual_free_ganado
               ELSE 0 END)                                                                        saldo_bono_virtual_free_ganado,
       usuario_saldo.billetera_id,

       SUM(CASE
               WHEN usuario_perfil.perfil_id = 'USUONLINE' THEN saldo_apuestas_casino_vivo
               ELSE 0 END)                                                                        saldo_apuestas_casino_vivo,


       SUM(CASE WHEN usuario_perfil.perfil_id = 'USUONLINE' THEN saldo_inicial ELSE 0 END) + SUM(
               CASE WHEN usuario_perfil.perfil_id = 'USUONLINE' THEN saldo_recarga ELSE 0 END) -
       SUM(CASE WHEN usuario_perfil.perfil_id = 'USUONLINE' THEN saldo_apuestas ELSE 0 END) + SUM(
               CASE WHEN usuario_perfil.perfil_id = 'USUONLINE' THEN saldo_premios ELSE 0 END) -
       SUM(CASE WHEN usuario_perfil.perfil_id = 'USUONLINE' THEN saldo_apuestas_casino ELSE 0 END)
           + SUM(CASE WHEN usuario_perfil.perfil_id = 'USUONLINE' THEN saldo_premios_casino ELSE 0 END) -
       SUM(CASE WHEN usuario_perfil.perfil_id = 'USUONLINE' THEN saldo_notaret_creadas ELSE 0 END) +
       SUM(CASE WHEN usuario_perfil.perfil_id = 'USUONLINE' THEN saldo_notaret_eliminadas ELSE 0 END) +
       SUM(CASE
               WHEN usuario_perfil.perfil_id = 'USUONLINE' THEN saldo_ajustes_entrada
               ELSE 0 END) -
       sum(CASE WHEN usuario_perfil.perfil_id = 'USUONLINE' THEN saldo_ajustes_salida ELSE 0 END) +
       sum(CASE WHEN usuario_perfil.perfil_id = 'USUONLINE' THEN saldo_bono ELSE 0 END) +
       sum(CASE WHEN usuario_perfil.perfil_id = 'USUONLINE' THEN saldo_bono_casino_vivo ELSE 0 END) -
       SUM(CASE WHEN usuario_perfil.perfil_id = 'USUONLINE' THEN saldo_final ELSE 0 END)          desfase,
       0                                                                                          cantidad_recargas
FROM " . $nametable . " usuario_saldo
         INNER JOIN usuario ON (usuario_saldo.usuario_id = usuario.usuario_id)
         INNER JOIN pais ON (usuario.pais_id = pais.pais_id)
         inner join usuario_perfil ON (usuario.usuario_id = usuario_perfil.usuario_id)

where 1 = 1
  AND ((usuario_saldo.fecha)) >= '" . $fechaSoloDia . "'
  AND ((usuario_saldo.fecha)) <= '" . $fechaSoloDia . "'
  AND  usuario.mandante IN (3,4,5,6,7,10,22)
GROUP BY usuario.mandante, usuario.pais_id, usuario_saldo.fecha,usuario_saldo.billetera_id;


  ";


            $sqlUsuarioSaldoResumen_PuntoVenta = "SELECT usuario.mandante,
       pais.pais_id,
       usuario_saldo.fecha,
       2,
       SUM(saldo_recarga)                                                                          saldo_recarga,
       SUM(saldo_apuestas)                                                                         saldo_apuestas,
       SUM(saldo_premios)                                                                          saldo_premios,
       SUM(saldo_notaret_pagadas)                                                                  saldo_notaret_pagadas,
       SUM(saldo_notaret_pend)                                                                     saldo_notaret_pend,
       SUM(saldo_ajustes_entrada)                                                                  saldo_ajustes_entrada,
       SUM(saldo_ajustes_salida)                                                                   saldo_ajustes_salida,
       SUM(saldo_inicial)                                                                          saldo_inicial,
       SUM(saldo_final)                                                                            saldo_final,
       SUM(saldo_bono)                                                                             saldo_bono,
       SUM(saldo_creditos_inicial)                                                                 saldo_creditos_inicial,
       SUM(saldo_creditos_base_inicial)                                                            saldo_creditos_base_inicial,
       SUM(saldo_creditos_final)                                                                   saldo_creditos_final,
       SUM(saldo_creditos_base_final)                                                              saldo_creditos_base_final,

       SUM(saldo_notaret_creadas)                                                                  saldo_notaret_creadas,
       SUM(saldo_apuestas_casino)                                                                  saldo_apuestas_casino,
       SUM(saldo_premios_casino)                                                                   saldo_premios_casino,

       SUM(saldo_notaret_eliminadas)                                                               saldo_notaret_eliminadas,


       SUM(saldo_bono_free_ganado)                                                                 saldo_bono_free_ganado,
       SUM(saldo_bono_casino_free_ganado)                                                          saldo_bono_casino_free_ganado,
       SUM(saldo_bono_casino_vivo)                                                                 saldo_bono_casino_vivo,
       SUM(saldo_bono_casino_vivo_free_ganado)                                                     saldo_bono_casino_vivo_free_ganado,
       SUM(saldo_bono_virtual)                                                                     saldo_bono_virtual,
       SUM(saldo_bono_virtual_free_ganado)                                                         saldo_bono_virtual_free_ganado,
       usuario_saldo.billetera_id,

       SUM(saldo_apuestas_casino_vivo)                                                             saldo_apuestas_casino_vivo,


              SUM(saldo_inicial) - SUM(saldo_final) - SUM(saldo_recarga) + SUM(saldo_notaret_pagadas) - SUM(saldo_apuestas) + SUM(saldo_premios) - SUM(saldo_apuestas_casino) + SUM(saldo_premios_casino) - SUM(saldo_notaret_creadas) - SUM(saldo_notaret_eliminadas) + SUM(saldo_ajustes_entrada) - SUM(saldo_ajustes_salida) + SUM(saldo_notaret_pend) desfase,


       usuario_recargas.cantidad_recargas
FROM " . $nametable . " usuario_saldo
         INNER JOIN usuario ON (usuario_saldo.usuario_id = usuario.usuario_id)
         INNER JOIN pais ON (usuario.pais_id = pais.pais_id)
         inner join usuario_perfil ON (usuario.usuario_id = usuario_perfil.usuario_id)
         LEFT OUTER JOIN (select count(*)      usuarios_recargas,
                                 SUM(cantidad) cantidad_recargas,
                                 usuario_recarga_resumen.fecha_crea,
                                 usuario_id
                          from usuario_recarga_resumen
                                   inner join day_dimension force index (day_dimension_dbtimestamp_timestrc_index)
                                              on (day_dimension.dbtimestamp = usuario_recarga_resumen.fecha_crea) where puntoventa_id!=0
                          GROUP BY usuario_id, fecha_crea) usuario_recargas ON (usuario_recargas.fecha_crea = fecha and
                                                                                usuario_saldo.usuario_id = usuario_recargas.usuario_id)
where 1 = 1
  AND ((usuario_saldo.fecha)) >= '" . $fechaSoloDia . "' 
  AND ((usuario_saldo.fecha)) <= '" . $fechaSoloDia . "' 
  AND ((usuario_perfil.perfil_id)) IN ('PUNTOVENTA')
  AND  usuario.mandante IN (3,4,5,6,7,10,22)
GROUP BY usuario.mandante, usuario.pais_id, usuario_saldo.fecha;";


            $sqlUsuarioSaldoResumen_Cajero = "SELECT usuario.mandante,
       pais.pais_id,
       usuario_saldo.fecha,
       3,
       SUM(saldo_recarga)                                                                          saldo_recarga,
       SUM(saldo_apuestas)                                                                         saldo_apuestas,
       SUM(saldo_premios)                                                                          saldo_premios,
       SUM(saldo_notaret_pagadas)                                                                  saldo_notaret_pagadas,
       SUM(saldo_notaret_pend)                                                                     saldo_notaret_pend,
       SUM(saldo_ajustes_entrada)                                                                  saldo_ajustes_entrada,
       SUM(saldo_ajustes_salida)                                                                   saldo_ajustes_salida,
       SUM(saldo_inicial)                                                                          saldo_inicial,
       SUM(saldo_final)                                                                            saldo_final,
       SUM(saldo_bono)                                                                             saldo_bono,
       SUM(saldo_creditos_inicial)                                                                 saldo_creditos_inicial,
       SUM(saldo_creditos_base_inicial)                                                            saldo_creditos_base_inicial,
       SUM(saldo_creditos_final)                                                                   saldo_creditos_final,
       SUM(saldo_creditos_base_final)                                                              saldo_creditos_base_final,

       SUM(saldo_notaret_creadas)                                                                  saldo_notaret_creadas,
       SUM(saldo_apuestas_casino)                                                                  saldo_apuestas_casino,
       SUM(saldo_premios_casino)                                                                   saldo_premios_casino,

       SUM(saldo_notaret_eliminadas)                                                               saldo_notaret_eliminadas,


       SUM(saldo_bono_free_ganado)                                                                 saldo_bono_free_ganado,
       SUM(saldo_bono_casino_free_ganado)                                                          saldo_bono_casino_free_ganado,
       SUM(saldo_bono_casino_vivo)                                                                 saldo_bono_casino_vivo,
       SUM(saldo_bono_casino_vivo_free_ganado)                                                     saldo_bono_casino_vivo_free_ganado,
       SUM(saldo_bono_virtual)                                                                     saldo_bono_virtual,
       SUM(saldo_bono_virtual_free_ganado)                                                         saldo_bono_virtual_free_ganado,
       billetera_id,

       SUM(saldo_apuestas_casino_vivo)                                                             saldo_apuestas_casino_vivo,


              SUM(saldo_inicial) - SUM(saldo_final) - SUM(saldo_recarga) + SUM(saldo_notaret_pagadas) - SUM(saldo_apuestas) + SUM(saldo_premios) - SUM(saldo_apuestas_casino) + SUM(saldo_premios_casino) - SUM(saldo_notaret_creadas) - SUM(saldo_notaret_eliminadas) + SUM(saldo_ajustes_entrada) - SUM(saldo_ajustes_salida) + SUM(saldo_notaret_pend) desfase,


       usuario_recargas.cantidad_recargas
FROM " . $nametable . " usuario_saldo
         INNER JOIN usuario ON (usuario_saldo.usuario_id = usuario.usuario_id)
         INNER JOIN pais ON (usuario.pais_id = pais.pais_id)
         inner join usuario_perfil ON (usuario.usuario_id = usuario_perfil.usuario_id)
         LEFT OUTER JOIN (select count(*)      usuarios_recargas,
                                 SUM(cantidad) cantidad_recargas,
                                 usuario_recarga_resumen.fecha_crea,
                                 usuario_id
                          from usuario_recarga_resumen
                                   inner join day_dimension force index (day_dimension_dbtimestamp_timestrc_index)
                                              on (day_dimension.dbtimestamp = usuario_recarga_resumen.fecha_crea) where puntoventa_id!=0
                          GROUP BY usuario_id, fecha_crea) usuario_recargas ON (usuario_recargas.fecha_crea = fecha and
                                                                                usuario_saldo.usuario_id = usuario_recargas.usuario_id)
where 1 = 1
  AND ((usuario_saldo.fecha)) >= '" . $fechaSoloDia . "' 
  AND ((usuario_saldo.fecha)) <= '" . $fechaSoloDia . "' 
  AND ((usuario_perfil.perfil_id)) IN ( 'CAJERO')
  AND  usuario.mandante IN (3,4,5,6,7,10,22)
GROUP BY usuario.mandante, usuario.pais_id, usuario_saldo.fecha;";

            $sqlUsuarioSaldoResumen_Concesionario = "SELECT usuario.mandante,
       pais.pais_id,
       usuario_saldo.fecha,
       4,
       SUM(saldo_recarga)                                                                          saldo_recarga,
       SUM(saldo_apuestas)                                                                         saldo_apuestas,
       SUM(saldo_premios)                                                                          saldo_premios,
       SUM(saldo_notaret_pagadas)                                                                  saldo_notaret_pagadas,
       SUM(saldo_notaret_pend)                                                                     saldo_notaret_pend,
       SUM(saldo_ajustes_entrada)                                                                  saldo_ajustes_entrada,
       SUM(saldo_ajustes_salida)                                                                   saldo_ajustes_salida,
       SUM(saldo_inicial)                                                                          saldo_inicial,
       SUM(saldo_final)                                                                            saldo_final,
       SUM(saldo_bono)                                                                             saldo_bono,
       SUM(saldo_creditos_inicial)                                                                 saldo_creditos_inicial,
       SUM(saldo_creditos_base_inicial)                                                            saldo_creditos_base_inicial,
       SUM(saldo_creditos_final)                                                                   saldo_creditos_final,
       SUM(saldo_creditos_base_final)                                                              saldo_creditos_base_final,

       SUM(saldo_notaret_creadas)                                                                  saldo_notaret_creadas,
       SUM(saldo_apuestas_casino)                                                                  saldo_apuestas_casino,
       SUM(saldo_premios_casino)                                                                   saldo_premios_casino,

       SUM(saldo_notaret_eliminadas)                                                               saldo_notaret_eliminadas,


       SUM(saldo_bono_free_ganado)                                                                 saldo_bono_free_ganado,
       SUM(saldo_bono_casino_free_ganado)                                                          saldo_bono_casino_free_ganado,
       SUM(saldo_bono_casino_vivo)                                                                 saldo_bono_casino_vivo,
       SUM(saldo_bono_casino_vivo_free_ganado)                                                     saldo_bono_casino_vivo_free_ganado,
       SUM(saldo_bono_virtual)                                                                     saldo_bono_virtual,
       SUM(saldo_bono_virtual_free_ganado)                                                         saldo_bono_virtual_free_ganado,
       billetera_id,

       SUM(saldo_apuestas_casino_vivo)                                                             saldo_apuestas_casino_vivo,


              SUM(saldo_inicial) - SUM(saldo_final) - SUM(saldo_recarga) + SUM(saldo_notaret_pagadas) - SUM(saldo_apuestas) + SUM(saldo_premios) - SUM(saldo_apuestas_casino) + SUM(saldo_premios_casino) - SUM(saldo_notaret_creadas) - SUM(saldo_notaret_eliminadas) + SUM(saldo_ajustes_entrada) - SUM(saldo_ajustes_salida) + SUM(saldo_notaret_pend) desfase,


       usuario_recargas.cantidad_recargas
FROM " . $nametable . " usuario_saldo
         INNER JOIN usuario ON (usuario_saldo.usuario_id = usuario.usuario_id)
         INNER JOIN pais ON (usuario.pais_id = pais.pais_id)
         inner join usuario_perfil ON (usuario.usuario_id = usuario_perfil.usuario_id)
         LEFT OUTER JOIN (select count(*)      usuarios_recargas,
                                 SUM(cantidad) cantidad_recargas,
                                 usuario_recarga_resumen.fecha_crea,
                                 usuario_id
                          from usuario_recarga_resumen
                                   inner join day_dimension force index (day_dimension_dbtimestamp_timestrc_index)
                                              on (day_dimension.dbtimestamp = usuario_recarga_resumen.fecha_crea) where puntoventa_id!=0
                          GROUP BY usuario_id, fecha_crea) usuario_recargas ON (usuario_recargas.fecha_crea = fecha and
                                                                                usuario_saldo.usuario_id = usuario_recargas.usuario_id)
where 1 = 1
  AND ((usuario_saldo.fecha)) >= '" . $fechaSoloDia . "' 
  AND ((usuario_saldo.fecha)) <= '" . $fechaSoloDia . "' 
  AND ((usuario_perfil.perfil_id)) IN ( 'CONCESIONARIO')
  AND  usuario.mandante IN (3,4,5,6,7,10,22)
GROUP BY usuario.mandante, usuario.pais_id, usuario_saldo.fecha;";

            $sqlUsuarioSaldoResumen_Concesionario2 = "SELECT usuario.mandante,
       pais.pais_id,
       usuario_saldo.fecha,
       5,
       SUM(saldo_recarga)                                                                          saldo_recarga,
       SUM(saldo_apuestas)                                                                         saldo_apuestas,
       SUM(saldo_premios)                                                                          saldo_premios,
       SUM(saldo_notaret_pagadas)                                                                  saldo_notaret_pagadas,
       SUM(saldo_notaret_pend)                                                                     saldo_notaret_pend,
       SUM(saldo_ajustes_entrada)                                                                  saldo_ajustes_entrada,
       SUM(saldo_ajustes_salida)                                                                   saldo_ajustes_salida,
       SUM(saldo_inicial)                                                                          saldo_inicial,
       SUM(saldo_final)                                                                            saldo_final,
       SUM(saldo_bono)                                                                             saldo_bono,
       SUM(saldo_creditos_inicial)                                                                 saldo_creditos_inicial,
       SUM(saldo_creditos_base_inicial)                                                            saldo_creditos_base_inicial,
       SUM(saldo_creditos_final)                                                                   saldo_creditos_final,
       SUM(saldo_creditos_base_final)                                                              saldo_creditos_base_final,

       SUM(saldo_notaret_creadas)                                                                  saldo_notaret_creadas,
       SUM(saldo_apuestas_casino)                                                                  saldo_apuestas_casino,
       SUM(saldo_premios_casino)                                                                   saldo_premios_casino,

       SUM(saldo_notaret_eliminadas)                                                               saldo_notaret_eliminadas,


       SUM(saldo_bono_free_ganado)                                                                 saldo_bono_free_ganado,
       SUM(saldo_bono_casino_free_ganado)                                                          saldo_bono_casino_free_ganado,
       SUM(saldo_bono_casino_vivo)                                                                 saldo_bono_casino_vivo,
       SUM(saldo_bono_casino_vivo_free_ganado)                                                     saldo_bono_casino_vivo_free_ganado,
       SUM(saldo_bono_virtual)                                                                     saldo_bono_virtual,
       SUM(saldo_bono_virtual_free_ganado)                                                         saldo_bono_virtual_free_ganado,
       billetera_id,

       SUM(saldo_apuestas_casino_vivo)                                                             saldo_apuestas_casino_vivo,


              SUM(saldo_inicial) - SUM(saldo_final) - SUM(saldo_recarga) + SUM(saldo_notaret_pagadas) - SUM(saldo_apuestas) + SUM(saldo_premios) - SUM(saldo_apuestas_casino) + SUM(saldo_premios_casino) - SUM(saldo_notaret_creadas) - SUM(saldo_notaret_eliminadas) + SUM(saldo_ajustes_entrada) - SUM(saldo_ajustes_salida) + SUM(saldo_notaret_pend) desfase,


       usuario_recargas.cantidad_recargas
FROM " . $nametable . " usuario_saldo
         INNER JOIN usuario ON (usuario_saldo.usuario_id = usuario.usuario_id)
         INNER JOIN pais ON (usuario.pais_id = pais.pais_id)
         inner join usuario_perfil ON (usuario.usuario_id = usuario_perfil.usuario_id)
         LEFT OUTER JOIN (select count(*)      usuarios_recargas,
                                 SUM(cantidad) cantidad_recargas,
                                 usuario_recarga_resumen.fecha_crea,
                                 usuario_id
                          from usuario_recarga_resumen
                                   inner join day_dimension force index (day_dimension_dbtimestamp_timestrc_index)
                                              on (day_dimension.dbtimestamp = usuario_recarga_resumen.fecha_crea) where puntoventa_id!=0
                          GROUP BY usuario_id, fecha_crea) usuario_recargas ON (usuario_recargas.fecha_crea = fecha and
                                                                                usuario_saldo.usuario_id = usuario_recargas.usuario_id)
where 1 = 1
  AND ((usuario_saldo.fecha)) >= '" . $fechaSoloDia . "' 
  AND ((usuario_saldo.fecha)) <= '" . $fechaSoloDia . "' 
  AND ((usuario_perfil.perfil_id)) IN ( 'CONCESIONARIO2')
  AND  usuario.mandante IN (3,4,5,6,7,10,22)
GROUP BY usuario.mandante, usuario.pais_id, usuario_saldo.fecha;";


            if (true) {
                $BonoInterno = new BonoInterno();
                $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
                $transaccion = $BonoDetalleMySqlDAO->getTransaction();
                $transaccion->getConnection()->beginTransaction();

                $data = $BonoInterno->execQuery('', 'set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000');

                if (true) {
                    print_r($sqlUsuarioSaldoResumen_Usuario);
                    $data = $BonoInterno->execQuery('', $sqlUsuarioSaldoResumen_Usuario);
                    foreach ($data as $datanum) {
                        $sql = "INSERT INTO bodega_usuario_saldo (mandante, pais_id, fecha, tipo, saldo_recarga, saldo_apuestas, saldo_premios,
                                  saldo_notaret_pagadas, saldo_notaret_pend, saldo_ajustes_entrada,
                                  saldo_ajustes_salida, saldo_inicial, saldo_final, saldo_bono, saldo_creditos_inicial,
                                  saldo_creditos_base_inicial, saldo_creditos_final, saldo_creditos_base_final,
                                  saldo_notaret_creadas, saldo_apuestas_casino, saldo_premios_casino,
                                  saldo_notaret_eliminadas, saldo_bono_free_ganado, saldo_bono_casino_free_ganado,
                                  saldo_bono_casino_vivo, saldo_bono_casino_vivo_free_ganado, saldo_bono_virtual,
                                  saldo_bono_virtual_free_ganado, billetera_id, saldo_apuestas_casino_vivo, desfase,
                                  cantidad_recargas)
              VALUES (
                                         '" . $datanum->{'usuario.mandante'} . "',
                                         '" . $datanum->{'pais.pais_id'} . "',
                           '" . $datanum->{'usuario_saldo.fecha'} . "',
                           '" . "1" . "',
                           '" . $datanum->{'.saldo_recarga'} . "',
                           '" . $datanum->{'.saldo_apuestas'} . "',
                           '" . $datanum->{'.saldo_premios'} . "',
                           '" . $datanum->{'.saldo_notaret_pagadas'} . "',
                           '" . $datanum->{'.saldo_notaret_pend'} . "',
                           '" . $datanum->{'.saldo_ajustes_entrada'} . "',
                           '" . $datanum->{'.saldo_ajustes_salida'} . "',
                           '" . $datanum->{'.saldo_inicial'} . "',
                           '" . $datanum->{'.saldo_final'} . "',
                           '" . $datanum->{'.saldo_bono'} . "',
                           '" . $datanum->{'.saldo_creditos_inicial'} . "',
                           '" . $datanum->{'.saldo_creditos_base_inicial'} . "',
                           '" . $datanum->{'.saldo_creditos_final'} . "',
                           '" . $datanum->{'.saldo_creditos_base_final'} . "',
                           '" . $datanum->{'.saldo_notaret_creadas'} . "',
                           '" . $datanum->{'.saldo_apuestas_casino'} . "',
                           '" . $datanum->{'.saldo_premios_casino'} . "',
                           '" . $datanum->{'.saldo_notaret_eliminadas'} . "',
                           '" . $datanum->{'.saldo_bono_free_ganado'} . "',
                           '" . $datanum->{'.saldo_bono_casino_free_ganado'} . "',
                           '" . $datanum->{'.saldo_bono_casino_vivo'} . "',
                           '" . $datanum->{'.saldo_bono_casino_vivo_free_ganado'} . "',
                           '" . $datanum->{'.saldo_bono_virtual'} . "',
                           '" . $datanum->{'.saldo_bono_virtual_free_ganado'} . "',
                           '" . $datanum->{'usuario_saldo.billetera_id'} . "',
                           '" . $datanum->{'.saldo_apuestas_casino_vivo'} . "',
                           '" . $datanum->{'.desfase'} . "',
                           '0'
              )";
                        $BonoInterno->execQuery($transaccion, $sql);
                    }
                }
                //$procesoInterno=$BonoInterno->execQuery($transaccion, "INSERT INTO procesos_internos ( tipo, fecha_crea, usucrea_id) VALUES ('ResumenesPaso3-6AM','".date("Y-m-d 00:00:00")."','0');");

                $transaccion->commit();

            }

            $log = "\r\n" . "-------------------------" . "\r\n";
            $log = $log . "Terminacion: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
            fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

            $message = "*CRON: (Fin) * " . " ResumenesPaso3 - Fecha: " . date("Y-m-d H:i:s");

            exec("php -f " . __DIR__ . "../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");


        } catch (Exception $e) {
            print_r($e);
            $log = "\r\n" . "-------------------------" . "\r\n";
            $log = $log . "ERROR: " . $e->getMessage() . " - " . date('Y-m-d H:i:s');
            fwriteCustom('log_' . date("Y-m-d") . '.log', $log);


            $message = "*CRON: (ERROR) * " . " ResumenesPaso3 - Fecha: " . date("Y-m-d H:i:s");

            exec("php -f " . __DIR__ . "../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

        }


    }
}