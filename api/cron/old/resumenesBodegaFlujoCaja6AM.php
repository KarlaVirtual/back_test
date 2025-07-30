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
use Backend\sql\ConnectionProperty;

require(__DIR__.'/../vendor/autoload.php');
///home/devadmin/api/api/

ini_set('memory_limit', '-1');

$message = "*CRON: (Inicio) * " . " BodegaFlujoCaja - Fecha: " . date("Y-m-d H:i:s");

//exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");


$ResumenesCron = new ResumenesCron();

//$ResumenesCron->generateResumenes();

$fechaSoloDia = date("Y-m-d", strtotime('-1 days'));
$fecha1 = date("Y-m-d 06:00:00", strtotime('-1 days'));
$fecha2 = date("Y-m-d 05:59:59", strtotime('-1 days'));

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
$fechaSoloDia2 = date("Y-m-d", strtotime('+1 days',strtotime($fechaSoloDia)));


try {

//BETWEEN '".$fecha1."' AND '".$fecha2."'
    ini_set('mysql.connect_timeout', 300);
    ini_set('default_socket_timeout', 300);

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

      from (select d.nombre            punto_venta,
                   d.usuario_id,
                   d.mandante          mandante,
                   DATE_FORMAT('".$fechaSoloDia."', '%Y-%m-%d') fecha_crea,
                   d.moneda,
                   f.pais_id,
                   f.pais_nom,
                   f.iso               pais_iso,
                   c.usupadre_id,
                   c.usupadre2_id,
                   c.concesionario_id,

                   0                   cant_tickets,
                   sum(CASE
                           WHEN ((CONCAT(x.fecha_crea, ' ', x.hora_crea)) >=
                                 ('".$fechaSoloDia." 06:00:00')
                               and (CONCAT(x.fecha_crea, ' ', x.hora_crea)) <=
                                   ('".$fechaSoloDia2." 05:59:59')) THEN case
                                                                     when x.tipomov_id = 'S' then 0
                                                                     when x.tipomov_id = 'E' and x.ticket_id <> '' and not x.ticket_id is null
                                                                         then x.valor
                                                                     else x.valor_forma1 end -
                                                                 case when x.tipomov_id = 'S' then 0 else x.valor_forma2 end -
                                                                 case when x.tipomov_id = 'S' then 0 else x.valor_forma2 end -
                                                                 case when x.tipomov_id = 'E' and x.traslado = 'S' then x.valor else 0 end
                           ELSE 0 END) valor_entrada_efectivo,
                   sum(CASE
                           WHEN ((CONCAT(x.fecha_crea, ' ', x.hora_crea)) >=
                                 ('".$fechaSoloDia." 06:00:00')
                               and (CONCAT(x.fecha_crea, ' ', x.hora_crea)) <=
                                   ('".$fechaSoloDia2." 05:59:59'))
                               THEN case when x.tipomov_id = 'S' then 0 else x.valor_forma2 end
                           ELSE 0 END) valor_entrada_bono,
                   sum(CASE
                           WHEN ((CONCAT(x.fecha_crea, ' ', x.hora_crea)) >=
                                 ('".$fechaSoloDia." 06:00:00')
                               and (CONCAT(x.fecha_crea, ' ', x.hora_crea)) <=
                                   ('".$fechaSoloDia2." 05:59:59')) THEN case
                                                                     when x.traslado = 'N' and
                                                                          (x.ticket_id = '' or x.ticket_id is null) and
                                                                          x.tipomov_id = 'E'  and x.recarga_id != '0'
                                                                         then x.valor
                                                                     else 0 end
                           ELSE 0 END) valor_entrada_recarga,
                           sum(CASE
                           WHEN ((CONCAT(x.fecha_crea, ' ', x.hora_crea)) >=
                                 ('".$fechaSoloDia." 06:00:00')
                               and (CONCAT(x.fecha_crea, ' ', x.hora_crea)) <=
                                   ('".$fechaSoloDia2." 05:59:59')) THEN case
                                                                     when 
                                                                          (x.ticket_id = '' or x.ticket_id is null) and x.cupolog_id = '0'  and x.recarga_id != '0'   and x.recarga_id != '' 
                                                                          and x.tipomov_id = 'S'
                                                                         then x.valor
                                                                     else 0 end
                           ELSE 0 END) valor_entrada_recarga_anuladas,
                   sum(CASE
                           WHEN ((CONCAT(x.fecha_crea, ' ', x.hora_crea)) >=
                                 ('".$fechaSoloDia." 06:00:00')
                               and (CONCAT(x.fecha_crea, ' ', x.hora_crea)) <=
                                   ('".$fechaSoloDia2." 05:59:59')) THEN
                               case when x.tipomov_id = 'E' and x.traslado = 'S' then x.valor else 0 end
                           ELSE 0 END) valor_entrada_traslado,
                   sum(CASE
                           WHEN ((CONCAT(x.fecha_crea, ' ', x.hora_crea)) >=
                                 ('".$fechaSoloDia." 06:00:00')
                               and (CONCAT(x.fecha_crea, ' ', x.hora_crea)) <=
                                   ('".$fechaSoloDia2." 05:59:59')) THEN
                               case when x.tipomov_id = 'S' and x.traslado = 'S' then x.valor else 0 end
                           ELSE 0 END) valor_salida_traslado,
                   sum(CASE
                           WHEN ((CONCAT(x.fecha_crea, ' ', x.hora_crea)) >=
                                 ('".$fechaSoloDia." 06:00:00')
                               and (CONCAT(x.fecha_crea, ' ', x.hora_crea)) <=
                                   ('".$fechaSoloDia2." 05:59:59')) THEN case
                                                                     when x.traslado = 'N' and
                                                                          (x.ticket_id = '' or x.ticket_id is null) and
                                                                          x.tipomov_id = 'S' AND cuenta_id != ''
                                                                         then x.valor
                                                                         when x.traslado = 'N' and
                                                                          (x.ticket_id = '' or x.ticket_id is null) and
                                                                          x.tipomov_id = 'E' AND cuenta_id != ''
                                                                         then -x.valor
                                                                     else 0 end
                           ELSE 0 END) valor_salida_notaret,
                   sum(CASE
                           WHEN ((CONCAT(x.fecha_crea, ' ', x.hora_crea)) >=
                                 ('".$fechaSoloDia." 06:00:00')
                               and (CONCAT(x.fecha_crea, ' ', x.hora_crea)) <=
                                   ('".$fechaSoloDia2." 05:59:59')) THEN case when x.tipomov_id = 'E' then x.valor else 0 end
                           ELSE 0 END) valor_entrada,
                   sum(CASE
                           WHEN ((CONCAT(x.fecha_crea, ' ', x.hora_crea)) >=
                                 ('".$fechaSoloDia." 06:00:00')
                               and (CONCAT(x.fecha_crea, ' ', x.hora_crea)) <=
                                   ('".$fechaSoloDia2." 05:59:59')) THEN case when x.tipomov_id = 'S' then x.valor else 0 end
                           ELSE 0 END) valor_salida,
                   sum(CASE
                           WHEN ((CONCAT(x.fecha_crea, ' ', x.hora_crea)) >=
                                 ('".$fechaSoloDia." 06:00:00')
                               and (CONCAT(x.fecha_crea, ' ', x.hora_crea)) <=
                                   ('".$fechaSoloDia2." 05:59:59')) THEN case
                                                                     when x.tipomov_id = 'S' and x.traslado <> 'S' and
                                                                          ((x.ticket_id <> '' and not x.ticket_id is null) OR
                                                                           (x.recarga_id <> '' and not x.recarga_id is null))
                                                                         then x.valor
                                                                     else 0 end
                           ELSE 0 END) valor_salida_efectivo,
                   0                   premios_pend,
                   SUM(CASE
                           WHEN ((CONCAT(x.fecha_crea, ' ', x.hora_crea)) >=
                                 ('".$fechaSoloDia." 06:00:00')
                               and (CONCAT(x.fecha_crea, ' ', x.hora_crea)) <=
                                   ('".$fechaSoloDia2." 05:59:59')) THEN valor_iva
                           ELSE 0 END) impuestos,
                   0                   apuestas_void,
                   0                   premios_void
            from flujo_caja x
                     inner join usuario d on (x.mandante = d.mandante and x.usucrea_id = d.usuario_id)
                     left outer join punto_venta e on (d.mandante = e.mandante and d.puntoventa_id = e.usuario_id)
                     left outer join punto_venta pv
                                     on (d.mandante = e.mandante and e.puntoventa_id = pv.puntoventa_id)
                     left outer join usuario upv
                                     on (upv.usuario_id = pv.usuario_id)

                     left outer join pais f on (d.pais_id = f.pais_id)
                     left outer join concesionario c
                                     on (e.usuario_id = c.usuhijo_id and c.prodinterno_id = 0 AND c.estado = 'A')
            where 1 = 1
              and (((x.fecha_crea))) >=
                  ('".$fechaSoloDia."')
              and (((x.fecha_crea))) <=
                  ('".$fechaSoloDia2."')
              and d.mandante in (3, 4, 5, 6, 7, 10, 22, 25)
            group by d.mandante, upv.usuario_id, x.fecha_crea, d.moneda) z
      union
      select d.nombre                              punto_venta,
             d.usuario_id,
             d.mandante                            mandante,
             DATE_FORMAT('".$fechaSoloDia."', '%Y-%m-%d') fecha_crea,
             d.moneda,
             f.pais_id,
             f.pais_nom,
             f.iso                                 pais_iso,
             c.usupadre_id,
             c.usupadre2_id,
             c.concesionario_id,
             0                                     cant_tickets,
             0                                     valor_entrada_efectivo,
             0                                     valor_entrada_bono,
             0                                     valor_entrada_recarga,
                          0                 valor_entrada_recarga_anuladas,
             0                                     valor_entrada_traslado,
             0                                     valor_salida_traslado,
             0                                     valor_salida_notaret,
             0                                     valor_entrada,
             0                                     valor_salida,
             0                                     valor_salida_efectivo,
             sum(CASE
                           WHEN ((CONCAT(z.fecha_crea, ' ', z.hora_crea)) >=
                                 ('".$fechaSoloDia." 06:00:00')
                               and (CONCAT(z.fecha_crea, ' ', z.hora_crea)) <=
                                   ('".$fechaSoloDia2." 05:59:59')) THEN z.vlr_premio ELSE 0 END)                     premios_pend,
             0                                     impuestos,
             0                                     apuestas_void,
             0                                     premios_void
      from it_ticket_enc z
               inner join usuario d on (z.mandante = d.mandante and z.usuario_id = d.usuario_id)
               left outer join punto_venta e on (d.mandante = e.mandante and d.puntoventa_id = e.usuario_id)
               left outer join punto_venta pv on (d.mandante = e.mandante and e.puntoventa_id = pv.puntoventa_id)
               left outer join usuario upv
                               on (upv.usuario_id = pv.usuario_id)
               left outer join pais f on (d.pais_id = f.pais_id)
               left outer join concesionario c
                               on (e.usuario_id = c.usuhijo_id and c.prodinterno_id = 0 AND c.estado = 'A')
      where (z.fecha_crea) <= ('".$fechaSoloDia2."')
        and d.mandante in (3, 4, 5, 6, 7, 10, 22, 25)
        and z.premiado = 'S'
        and z.premio_pagado = 'N'
        and date(z.fecha_maxpago) >= date(now())

      group by d.mandante, upv.usuario_id, DATE_FORMAT('".$fechaSoloDia." 00:00:00', '%Y-%m-%d'), d.moneda

      union
      select d.nombre           punto_venta,
             d.usuario_id,
             d.mandante         mandante,
             DATE_FORMAT('".$fechaSoloDia."', '%Y-%m-%d') fecha_crea,
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
             0                  valor_entrada_traslado,
             0                  valor_salida_traslado,
             0                  valor_salida_notaret,
             0                  valor_entrada,
             0                  valor_salida,
             0                  valor_salida_efectivo,
             0                  premios_pend,
             0                  impuestos,
             SUM(CASE
                           WHEN ((CONCAT(z.fecha_crea, ' ', z.hora_crea)) >=
                                 ('".$fechaSoloDia." 06:00:00')
                               and (CONCAT(z.fecha_crea, ' ', z.hora_crea)) <=
                                   ('".$fechaSoloDia2." 05:59:59')) THEN z.vlr_apuesta ELSE 0 END) apuestas_void,
             0                  premios_void
      from it_ticket_enc z
               inner join usuario d on (z.mandante = d.mandante and z.usuario_id = d.usuario_id)
               inner join punto_venta e on (d.mandante = e.mandante and d.puntoventa_id = e.usuario_id)
               left outer join punto_venta pv on (d.mandante = e.mandante and e.puntoventa_id = pv.puntoventa_id)
               left outer join usuario upv
                               on (upv.usuario_id = pv.usuario_id)
               left outer join pais f on (d.pais_id = f.pais_id)
               left outer join concesionario c
                               on (e.usuario_id = c.usuhijo_id and c.prodinterno_id = 0 AND c.estado = 'A')

      where 1 = 1
        and z.bet_status = 'A'
        and z.eliminado = 'N'
        and ((((z.fecha_crea)))) >=
            ('".$fechaSoloDia."')
        and ((((z.fecha_crea)))) <=
            ('".$fechaSoloDia2."')
        and d.mandante in (3, 4, 5, 6, 7, 10, 22, 25)
      group by d.mandante, upv.usuario_id, '".$fechaSoloDia."', d.moneda

      union
      select d.nombre           punto_venta,
             d.usuario_id,
             d.mandante         mandante,
             DATE_FORMAT('".$fechaSoloDia."', '%Y-%m-%d') fecha_crea,
             d.moneda,
             f.pais_id,
             f.pais_nom,
             f.iso              pais_iso,
             c.usupadre_id,
             c.usupadre2_id,
             c.concesionario_id,
             SUM(CASE
                           WHEN ((CONCAT(z.fecha_crea, ' ', z.hora_crea)) >=
                                 ('".$fechaSoloDia." 06:00:00')
                               and (CONCAT(z.fecha_crea, ' ', z.hora_crea)) <=
                                   ('".$fechaSoloDia2." 05:59:59')) THEN 1 ELSE 0 END) cant_tickets,
             0                  valor_entrada_efectivo,
             0                  valor_entrada_bono,
             0                  valor_entrada_recarga,
             0                  valor_entrada_recarga_anuladas,
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
               inner join punto_venta e on (d.mandante = e.mandante and d.puntoventa_id = e.usuario_id)
               left outer join punto_venta pv on (d.mandante = e.mandante and e.puntoventa_id = pv.puntoventa_id)
               left outer join usuario upv
                               on (upv.usuario_id = pv.usuario_id)
               left outer join pais f on (d.pais_id = f.pais_id)
               left outer join concesionario c
                               on (e.usuario_id = c.usuhijo_id and c.prodinterno_id = 0 AND c.estado = 'A')

      where 1 = 1
        and (((z.fecha_crea))) >=
            ('".$fechaSoloDia."')
        and ((((z.fecha_crea)))) <=
            ('".$fechaSoloDia2."')
        and d.mandante in (3, 4, 5, 6, 7, 10, 22, 25)


      group by d.mandante, upv.usuario_id, '".$fechaSoloDia."', d.moneda
      union
      select d.nombre          punto_venta,
             d.usuario_id,
             d.mandante        mandante,
             DATE_FORMAT('".$fechaSoloDia."', '%Y-%m-%d') fecha_pago,
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
             0                 valor_entrada_traslado,
             0                 valor_salida_traslado,
             0                 valor_salida_notaret,
             0                 valor_entrada,
             0                 valor_salida,
             0                 valor_salida_efectivo,
             0                 premios_pend,
             0                 impuestos,
             0                 apuestas_void,
             SUM(CASE
                           WHEN ((CONCAT(z.fecha_pago, ' ', z.hora_pago)) >=
                                 ('".$fechaSoloDia." 06:00:00')
                               and (CONCAT(z.fecha_pago, ' ', z.fecha_pago)) <=
                                   ('".$fechaSoloDia2." 05:59:59')) THEN z.vlr_premio ELSE 0 END) premios_void
      from it_ticket_enc z
               inner join usuario d on (z.mandante = d.mandante and z.usuario_id = d.usuario_id)
               inner join punto_venta e on (d.mandante = e.mandante and d.puntoventa_id = e.usuario_id)
               left outer join punto_venta pv on (d.mandante = e.mandante and e.puntoventa_id = pv.puntoventa_id)
               left outer join usuario upv
                               on (upv.usuario_id = pv.usuario_id)
               left outer join pais f on (d.pais_id = f.pais_id)
               left outer join concesionario c
                               on (e.usuario_id = c.usuhijo_id and c.prodinterno_id = 0 AND c.estado = 'A')

      where 1 = 1
        and z.bet_status = 'A'
        and d.mandante in (3, 4, 5, 6, 7, 10, 22, 25)
        and z.eliminado = 'N'
        and premio_pagado = 'S'
        and (((z.fecha_pago))) >=
            ('".$fechaSoloDia."')
        and ((((z.fecha_pago)))) <=
            ('".$fechaSoloDia2."')


      group by d.mandante, upv.usuario_id, '".$fechaSoloDia."', d.moneda
     ) y

         left outer join usuario uu on (y.usupadre_id = uu.usuario_id)


where y.fecha_crea is not null

group by y.mandante, y.usuario_id, y.fecha_crea, y.moneda
order by y.usuario_id, y.fecha_crea";

    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "Inicia: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);


    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "Inicia: " . $UsuarioSaldoFinal . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);


    $paso = true;

    $BonoInterno = new BonoInterno();
    $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
    $transaccion = $BonoDetalleMySqlDAO->getTransaction();
    $transaccion->getConnection()->beginTransaction();

/*    $conn = new PDO("mysql:host=" . ConnectionProperty::getHost() . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword());
    $conn->setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC );
    $conn->setAttribute( PDO::ATTR_TIMEOUT, 28800 );

    $dataSaldoInicial = $conn->exec($sqlFlujoCaja);
print_r($dataSaldoInicial);
    exit();*/


    $BonoInterno->execQuery($transaccion, "SET session interactive_timeout=28800");
    $BonoInterno->execQuery($transaccion, "SET session net_read_timeout=28800");

    print_r($sqlFlujoCaja);
    $dataSaldoInicial = $BonoInterno->execQuery($transaccion, $sqlFlujoCaja);


    foreach ($dataSaldoInicial as $datanum) {

        print_r($datanum);
        $sql = "INSERT INTO bodega_flujo_caja (usuario_id, fecha, pais_id, mandante, concesionario_id, cant_tickets, valor_entrada_efectivo, valor_entrada_bono, valor_entrada_recarga,recargas_anuladas, valor_entrada_traslado, valor_salida_traslado, valor_salida_notaret, valor_entrada, valor_salida, valor_salida_efectivo, premios_pend, impuestos, apuestas_void, premios_void) 
                    VALUES ('" . $datanum->{'y.usuario_id'} . "',
                    '" . $datanum->{'y.fecha_crea'} . "',
                    '" . $datanum->{'y.pais_id'} . "',
                    '" . $datanum->{'y.mandante'} . "',
                    '" . $datanum->{'y.concesionario_id'} . "',
                    '" . ($datanum->{'.cant_tickets'} == '' ? 0 : $datanum->{'.cant_tickets'}) . "',
                    '" . ($datanum->{'.valor_entrada_efectivo'} == '' ? 0 : $datanum->{'.valor_entrada_efectivo'}) . "',
                    '" . ($datanum->{'.valor_entrada_bono'} == '' ? 0 : $datanum->{'.valor_entrada_bono'}) . "',
                    '" . ($datanum->{'.valor_entrada_recarga'} == '' ? 0 : $datanum->{'.valor_entrada_recarga'}) . "',
                    '" . ($datanum->{'.valor_entrada_recarga_anuladas'} == '' ? 0 : $datanum->{'.valor_entrada_recarga_anuladas'}) . "',
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


   // $procesoInterno=$BonoInterno->execQuery($transaccion, "INSERT INTO procesos_internos ( tipo, fecha_crea, usucrea_id) VALUES ('ResumenesBodegaFlujoCaja-6AM','".date("Y-m-d 00:00:00")."','0');");

    $transaccion->commit();

    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "Terminacion: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);

    $message = "*CRON: (Fin) * " . " BodegaFlujoCaja - Fecha: " . date("Y-m-d H:i:s");

    //exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");


    //$BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
    //$transaccion = $BonoDetalleMySqlDAO->getTransaction();



    //$BonoInterno->execQuery($transaccion, "CALL LoadCalendars('".date("Y-m-d", strtotime("+1 day"))." 00:00:00', 86400);");
    //$BonoInterno->execQuery($transaccion, "CALL LoadCalendars2('".date("Y-m-d", strtotime("+1 day"))." 00:00:00', 1);");

    //$transaccion->commit();


} catch (Exception $e) {
    print_r($e);
    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "ERROR: " . $e->getMessage() . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);


    $message = "*CRON: (ERROR) * " . " BodegaFlujoCaja - Fecha: " . date("Y-m-d H:i:s");

    exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

}





