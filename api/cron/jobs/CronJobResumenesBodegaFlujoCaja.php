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
 * Clase 'CronJobResumenesBodegaFlujoCaja'
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
class CronJobResumenesBodegaFlujoCaja
{


    public function __construct()
    {
    }

    public function execute()
    {


        $message = "*CRON: (Inicio) * " . " BodegaFlujoCaja - Fecha: " . date("Y-m-d H:i:s");

        exec("php -f " . __DIR__ . "../src/imports/Slack/message.php '" . $message . "' '#procesos-cron-resumenes' > /dev/null & ");


        $ResumenesCron = new ResumenesCron();

//$ResumenesCron->generateResumenes();

        $fechaSoloDia = date("Y-m-d", strtotime('-1 days'));
        $fecha1 = date("Y-m-d 00:00:00", strtotime('-1 days'));
        $fecha2 = date("Y-m-d 23:59:59", strtotime('-1 days'));

        if ($_REQUEST["diaSpc"] != "") {


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


        try {

//BETWEEN '".$fecha1."' AND '".$fecha2."'

            $strEliminado = "DELETE FROM bodega_flujo_caja WHERE date_format(fecha, '%Y-%m-%d') = '" . $fechaSoloDia . "';";

            /* REPORTE FLUJO DE CAJA */
            $sqlFlujoCaja = "select y.usuario_id,
       y.fecha_crea,
       y.pais_id,
       y.mandante,
       y.concesionario_id,
       sum(y.cant_tickets)           cant_tickets,
       sum(y.valor_entrada_efectivo) valor_entrada_efectivo,
       sum(y.valor_entrada_bono)     valor_entrada_bono,
       sum(y.valor_entrada_recarga)  valor_entrada_recarga,
       sum(y.valor_entrada_recarga_anuladas)  valor_entrada_recarga_anuladas,
        sum(y.valor_entrada_recarga_agentes)  valor_entrada_recarga_agentes,

       sum(y.valor_entrada_traslado) valor_entrada_traslado,
       sum(y.valor_salida_traslado)  valor_salida_traslado,
       sum(y.valor_salida_notaret)   valor_salida_notaret,
       sum(y.valor_entrada)          valor_entrada,
       sum(y.valor_salida)           valor_salida,
       sum(y.valor_salida_efectivo)  valor_salida_efectivo,
       sum(y.premios_pend)           premios_pend,
       sum(y.impuestos)              impuestos,
       sum(y.apuestas_void)          apuestas_void,
       sum(y.premios_void)           premios_void
from (select *

      from (select d.nombre                                                                         punto_venta,
                   d.usuario_id,
                   d.mandante                                                                        mandante,
                   x.fecha_crea,
                   d.moneda,
                   f.pais_id,
                   f.pais_nom,
                   f.iso                                                                              pais_iso,
                   c.usupadre_id,
                   c.usupadre2_id,
                   c.concesionario_id,

                   0                                                                                  cant_tickets,
                   sum(case
                           when x.tipomov_id = 'S' then 0
                           when x.tipomov_id = 'E' and x.ticket_id <> '' and not x.ticket_id is null then x.valor
                           else x.valor_forma1 end - case when x.tipomov_id = 'S' then 0 else x.valor_forma2 end -
                       case when x.tipomov_id = 'E' and x.traslado = 'S' then x.valor else 0 end)     valor_entrada_efectivo,
                   sum(case when x.tipomov_id = 'S' then 0 else x.valor_forma2 end)                   valor_entrada_bono,
                   sum(case
                         when x.traslado = 'N' and (x.ticket_id = '' or x.ticket_id is null) and x.tipomov_id = 'E' and x.cupolog_id = '0'  and x.recarga_id != '0'
                           then x.valor
                         else 0 end)                                                              valor_entrada_recarga, 
                   sum(case
                         when  (x.ticket_id = '' or x.ticket_id is null) and x.tipomov_id = 'S' and x.cupolog_id = '0'  and x.recarga_id != '0'   and x.recarga_id != '' 
                           then x.valor
                         else 0 end)                                                              valor_entrada_recarga_anuladas, 
                   sum(case
                           when x.traslado = 'N' and ( x.ticket_id = '' or x.ticket_id is null) AND x.cupolog_id != '0' and (x.recarga_id = '0' or x.recarga_id is null) and x.tipomov_id = 'E'
                               then x.valor
                           else 0 end)                                                                valor_entrada_recarga_agentes,
                   sum(
                           case when x.tipomov_id = 'E' and x.traslado = 'S' then x.valor else 0 end) valor_entrada_traslado,
                   sum(
                           case when x.tipomov_id = 'S' and x.traslado = 'S' then x.valor else 0 end) valor_salida_traslado,
                   sum(case
                           when x.traslado = 'N' and (x.ticket_id = '' or x.ticket_id is null) and
                                x.tipomov_id = 'S' AND cuenta_id != ''
                               then x.valor
                           when x.traslado = 'N' and (x.ticket_id = '' or x.ticket_id is null) and
                                x.tipomov_id = 'E' AND cuenta_id != ''
                               then -x.valor
                           else 0 end)                                                                valor_salida_notaret,
                   sum(case when x.tipomov_id = 'E' then x.valor else 0 end)                          valor_entrada,
                   sum(case when x.tipomov_id = 'S' then x.valor else 0 end)                          valor_salida,
                   sum(case
                           when x.tipomov_id = 'S' and x.traslado <> 'S' and
                                ((x.ticket_id <> '' and not x.ticket_id is null))
                               then x.valor
                           else 0 end)                                                                valor_salida_efectivo,
                   0                                                                                  premios_pend,
                   SUM(valor_iva)                                                                     impuestos,
                   0                                                                                  apuestas_void,
                   0                                                                                  premios_void
            from flujo_caja x
                     inner join usuario d on (x.mandante = d.mandante and x.usucrea_id = d.usuario_id)
                     left outer join punto_venta e on ( d.puntoventa_id = e.usuario_id)
                     left outer join punto_venta pv
                                     on ( e.puntoventa_id = pv.puntoventa_id)
                     left outer join usuario upv
                                     on (upv.usuario_id = pv.usuario_id)

                     left outer join pais f on (d.pais_id = f.pais_id)
                     left outer join concesionario c on (e.usuario_id = c.usuhijo_id and c.prodinterno_id = 0 AND c.estado='A')
            where 1 = 1
              and (((x.fecha_crea))) >=
                  ('" . $fechaSoloDia . "')
              and (((x.fecha_crea))) <=
                  ('" . $fechaSoloDia . "') and d.mandante NOT IN (3,4,5,6,7,10,22,17,25)
            group by d.mandante, upv.usuario_id, x.fecha_crea, d.moneda) z
      union
      select d.nombre                                     punto_venta,
             d.usuario_id,
             d.mandante                                    mandante,
             DATE_FORMAT('" . $fechaSoloDia . "', '%Y-%m-%d') fecha_crea,
             d.moneda,
             f.pais_id,
             f.pais_nom,
             f.iso                                          pais_iso,
             c.usupadre_id,
             c.usupadre2_id,
             c.concesionario_id,
             0                                              cant_tickets,
             0                                              valor_entrada_efectivo,
             0                                              valor_entrada_bono,
             0                                              valor_entrada_recarga,
             0                                              valor_entrada_recarga_anuladas,
             0                 valor_entrada_recarga_agentes,
             0                                              valor_entrada_traslado,
             0                                              valor_salida_traslado,
             0                                              valor_salida_notaret,
             0                                              valor_entrada,
             0                                              valor_salida,
             0                                              valor_salida_efectivo,
             sum(z.vlr_premio)                              premios_pend,
             0                                              impuestos,
             0                                              apuestas_void,
             0                                              premios_void
      from it_ticket_enc z
               inner join usuario d on (z.mandante = d.mandante and z.usuario_id = d.usuario_id)
               left outer join punto_venta e on ( d.puntoventa_id = e.usuario_id)
               left outer join punto_venta pv on ( e.puntoventa_id = pv.puntoventa_id)
               left outer join usuario upv
                               on (upv.usuario_id = pv.usuario_id)
               left outer join pais f on (d.pais_id = f.pais_id)
               left outer join concesionario c on (e.usuario_id = c.usuhijo_id and c.prodinterno_id = 0 AND c.estado='A')
      where z.fecha_crea <= ('" . $fechaSoloDia . "')
        and z.premiado = 'S'
        and z.premio_pagado = 'N'
        and date(z.fecha_maxpago) >= date(now()) and d.mandante NOT IN (3,4,5,6,7,10,22,17,25)

      group by d.mandante, upv.usuario_id, DATE_FORMAT('" . $fechaSoloDia . "', '%Y-%m-%d'), d.moneda

      union
      select d.nombre         punto_venta,
             d.usuario_id,
             d.mandante        mandante,
             DATE_FORMAT('" . $fechaSoloDia . "', '%Y-%m-%d') fecha_crea,
             d.moneda,
             f.pais_id,
             f.pais_nom,
             f.iso              pais_iso,
             c.usupadre_id,
             c.usupadre2_id,
             c.concesionario_id,
             0                  cant_tickets,
             0                  valor_entrada_efectivo,
             0                  valor_entrada_bono,
             0                  valor_entrada_recarga,
             0                 valor_entrada_recarga_anuladas,
             0                 valor_entrada_recarga_agentes,
             0                  valor_entrada_traslado,
             0                  valor_salida_traslado,
             0                  valor_salida_notaret,
             0                  valor_entrada,
             0                  valor_salida,
             0                  valor_salida_efectivo,
             0                  premios_pend,
             0                  impuestos,
             SUM(z.vlr_apuesta) apuestas_void,
             0                  premios_void
      from it_ticket_enc z
               inner join usuario d on (z.mandante = d.mandante and z.usuario_id = d.usuario_id)
               inner join punto_venta e on ( d.puntoventa_id = e.usuario_id)
               left outer join punto_venta pv on ( e.puntoventa_id = pv.puntoventa_id)
               left outer join usuario upv
                               on (upv.usuario_id = pv.usuario_id)
               left outer join pais f on (d.pais_id = f.pais_id)
               left outer join concesionario c on (e.usuario_id = c.usuhijo_id and c.prodinterno_id = 0  AND c.estado='A')

      where 1 = 1
        and z.bet_status = 'A'
        and z.eliminado = 'N'
        and (((z.fecha_crea))) >=
            ('" . $fechaSoloDia . "')
        and (((z.fecha_crea))) <=
            ('" . $fechaSoloDia . "') and d.mandante NOT IN (3,4,5,6,7,10,22,17,25)
      group by d.mandante, upv.usuario_id, z.fecha_crea, d.moneda

      union
      select d.nombre         punto_venta,
             d.usuario_id,
             d.mandante        mandante,
             z.fecha_crea,
             d.moneda,
             f.pais_id,
             f.pais_nom,
             f.iso              pais_iso,
             c.usupadre_id,
             c.usupadre2_id,
             c.concesionario_id,
             count(z.ticket_id) cant_tickets,
             0                  valor_entrada_efectivo,
             0                  valor_entrada_bono,
             0                  valor_entrada_recarga,
             0                  valor_entrada_recarga_anuladas,
             0                 valor_entrada_recarga_agentes,
             0                  valor_entrada_traslado,
             0                  valor_salida_traslado,
             0                  valor_salida_notaret,
             0                  valor_entrada,
             0                  valor_salida,
             0                  valor_salida_efectivo,
             0                  premios_pend,
             0                  impuestos,
             0                  apuestas_void,
             0                  premios_void
      from it_ticket_enc z
               inner join usuario d on (z.mandante = d.mandante and z.usuario_id = d.usuario_id)
               inner join punto_venta e on ( d.puntoventa_id = e.usuario_id)
               left outer join punto_venta pv on ( e.puntoventa_id = pv.puntoventa_id)
               left outer join usuario upv
                               on (upv.usuario_id = pv.usuario_id)
               left outer join pais f on (d.pais_id = f.pais_id)
               left outer join concesionario c on (e.usuario_id = c.usuhijo_id and c.prodinterno_id = 0 AND c.estado='A')

      where 1 = 1
        and (((z.fecha_crea))) >=
            ('" . $fechaSoloDia . "')
        and (((z.fecha_crea))) <=
            ('" . $fechaSoloDia . "') and d.mandante NOT IN (3,4,5,6,7,10,22,17,25)


      group by d.mandante, upv.usuario_id, z.fecha_crea, d.moneda
      union
      select d.nombre        punto_venta,
             d.usuario_id,
             d.mandante       mandante,
             z.fecha_pago,
             d.moneda,
             f.pais_id,
             f.pais_nom,
             f.iso             pais_iso,
             c.usupadre_id,
             c.usupadre2_id,
             c.concesionario_id,
             0                 cant_tickets,
             0                 valor_entrada_efectivo,
             0                 valor_entrada_bono,
             0                 valor_entrada_recarga,
             0                 valor_entrada_recarga_anuladas,
             0                 valor_entrada_recarga_agentes,
             0                 valor_entrada_traslado,
             0                 valor_salida_traslado,
             0                 valor_salida_notaret,
             0                 valor_entrada,
             0                 valor_salida,
             0                 valor_salida_efectivo,
             0                 premios_pend,
             0                 impuestos,
             0                 apuestas_void,
             SUM(z.vlr_premio) premios_void
      from it_ticket_enc z
               inner join usuario d on (z.mandante = d.mandante and z.usuario_id = d.usuario_id)
               inner join punto_venta e on ( d.puntoventa_id = e.usuario_id)
               left outer join punto_venta pv on ( e.puntoventa_id = pv.puntoventa_id)
               left outer join usuario upv
                               on (upv.usuario_id = pv.usuario_id)
               left outer join pais f on (d.pais_id = f.pais_id)
               left outer join concesionario c on (e.usuario_id = c.usuhijo_id and c.prodinterno_id = 0  AND c.estado='A')

      where 1 = 1
        and z.bet_status = 'A'
        and z.eliminado = 'N'
        and premio_pagado = 'S'
        and (((z.fecha_pago))) >=
            ('" . $fechaSoloDia . "')
        and (((z.fecha_pago))) <=
            ('" . $fechaSoloDia . "') and d.mandante NOT IN (3,4,5,6,7,10,22,17,25)

        


      group by d.mandante, upv.usuario_id, z.fecha_crea, d.moneda
     ) y

         left outer join usuario uu on (y.usupadre_id = uu.usuario_id)


where y.fecha_crea is not null

group by y.mandante, y.usuario_id, y.fecha_crea, y.moneda
order by y.usuario_id, y.fecha_crea";

            $log = "\r\n" . "-------------------------" . "\r\n";
            $log = $log . "Inicia: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
            fwriteCustom('log_' . date("Y-m-d") . '.log', $log);


            $log = "\r\n" . "-------------------------" . "\r\n";
            $log = $log . "Inicia: " . $UsuarioSaldoFinal . " - " . date('Y-m-d H:i:s');
            fwriteCustom('log_' . date("Y-m-d") . '.log', $log);


            $paso = true;

            $BonoInterno = new BonoInterno();
            $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
            $transaccion = $BonoDetalleMySqlDAO->getTransaction();
            $transaccion->getConnection()->beginTransaction();


            //$BonoInterno->execQuery($transaccion, $strEliminado);

            print_r($sqlFlujoCaja);
            $dataSaldoInicial = $BonoInterno->execQuery($transaccion, $sqlFlujoCaja);


            foreach ($dataSaldoInicial as $datanum) {

                print_r($datanum);
                $sql = "INSERT INTO bodega_flujo_caja (usuario_id, fecha, pais_id, mandante, concesionario_id, cant_tickets, valor_entrada_efectivo, valor_entrada_bono, valor_entrada_recarga,recargas_anuladas,valor_entrada_recarga_agentes, valor_entrada_traslado, valor_salida_traslado, valor_salida_notaret, valor_entrada, valor_salida, valor_salida_efectivo, premios_pend, impuestos, apuestas_void, premios_void) 
                    VALUES ('" . $datanum->{'y.usuario_id'} . "',
                    '" . $datanum->{'y.fecha_crea'} . "',
                    '" . $datanum->{'y.pais_id'} . "',
                    '" . $datanum->{'y.mandante'} . "',
                    '" . ($datanum->{'y.concesionario_id'} == '' ? '0' : $datanum->{'y.concesionario_id'}) . "',
                    '" . ($datanum->{'.cant_tickets'} == '' ? 0 : $datanum->{'.cant_tickets'}) . "',
                    '" . ($datanum->{'.valor_entrada_efectivo'} == '' ? 0 : $datanum->{'.valor_entrada_efectivo'}) . "',
                    '" . ($datanum->{'.valor_entrada_bono'} == '' ? 0 : $datanum->{'.valor_entrada_bono'}) . "',
                    '" . ($datanum->{'.valor_entrada_recarga'} == '' ? 0 : $datanum->{'.valor_entrada_recarga'}) . "',
                    '" . ($datanum->{'.valor_entrada_recarga_anuladas'} == '' ? 0 : $datanum->{'.valor_entrada_recarga_anuladas'}) . "',
                    '" . ($datanum->{'.valor_entrada_recarga_agentes'} == '' ? 0 : $datanum->{'.valor_entrada_recarga_agentes'}) . "',
                    '" . ($datanum->{'.valor_entrada_traslado'} == '' ? 0 : $datanum->{'.valor_entrada_traslado'}) . "',
                    '" . ($datanum->{'.valor_salida_traslado'} == '' ? 0 : $datanum->{'.valor_salida_traslado'}) . "',
                    '" . ($datanum->{'.valor_salida_notaret'} == '' ? 0 : $datanum->{'.valor_salida_notaret'}) . "',
                    '" . ($datanum->{'.valor_entrada'} == '' ? 0 : $datanum->{'.valor_entrada'}) . "',
                    '" . ($datanum->{'.valor_salida'} == '' ? 0 : $datanum->{'.valor_salida'}) . "',
                    '" . ($datanum->{'.valor_salida_efectivo'} == '' ? 0 : $datanum->{'.valor_salida_efectivo'}) . "',
                    '" . ($datanum->{'.premios_pend'} == '' ? 0 : $datanum->{'.premios_pend'}) . "',
                    '" . ($datanum->{'.impuestos'} == '' ? 0 : $datanum->{'.impuestos'}) . "',
                    '" . ($datanum->{'.apuestas_void'} == '' ? 0 : $datanum->{'.apuestas_void'}) . "',
                    '" . ($datanum->{'.premios_void'} == '' ? 0 : $datanum->{'.premios_void'}) . "'

                    )    
                    ;

       ";

                $BonoInterno->execQuery($transaccion, $sql);

            }


            $procesoInterno = $BonoInterno->execQuery($transaccion, "INSERT INTO procesos_internos ( tipo, fecha_crea, usucrea_id) VALUES ('ResumenesBodegaFlujoCaja','" . date("Y-m-d 00:00:00") . "','0');");

            $transaccion->commit();

            $log = "\r\n" . "-------------------------" . "\r\n";
            $log = $log . "Terminacion: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
            fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

            $message = "*CRON: (Fin) * " . " BodegaFlujoCaja - Fecha: " . date("Y-m-d H:i:s");

            exec("php -f " . __DIR__ . "../src/imports/Slack/message.php '" . $message . "' '#procesos-cron-resumenes' > /dev/null & ");


            $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
            $transaccion = $BonoDetalleMySqlDAO->getTransaction();
            $transaccion->getConnection()->beginTransaction();


            $BonoInterno->execQuery($transaccion, "CALL LoadCalendars('" . date("Y-m-d", strtotime("+1 day")) . " 00:00:00', 86400);");
            $BonoInterno->execQuery($transaccion, "CALL LoadCalendars2('" . date("Y-m-d", strtotime("+1 day")) . " 00:00:00', 1);");
            $BonoInterno->execQuery($transaccion, "CALL LoadCalendars4('" . date("Y-m-d", strtotime("+1 day")) . " 00:00:00', 86400);");

            $transaccion->commit();


        } catch (Exception $e) {
            print_r($e);
            $log = "\r\n" . "-------------------------" . "\r\n";
            $log = $log . "ERROR: " . $e->getMessage() . " - " . date('Y-m-d H:i:s');
            fwriteCustom('log_' . date("Y-m-d") . '.log', $log);


            $message = "*CRON: (ERROR) * " . $e->getLine() . " BodegaFlujoCaja - Fecha: " . date("Y-m-d H:i:s");

            exec("php -f " . __DIR__ . "../src/imports/Slack/message.php '" . $message . "' '#procesos-cron-resumenes' > /dev/null & ");

        }


    }
}