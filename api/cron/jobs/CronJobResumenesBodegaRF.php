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
 * Clase 'CronJobResumenesBodegaRF'
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
class CronJobResumenesBodegaRF
{


    public function __construct()
    {
    }

    public function execute()
    {


        $wallets = array('0', '1');

        foreach ($wallets as $wallet) {


            ini_set('memory_limit', '-1');

            $message = "*CRON: (Inicio) * " . " ResumenesBodegaRF - Fecha: " . date("Y-m-d H:i:s");

//exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#procesos-cron-resumenes' > /dev/null & ");


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

                $strEliminado = "DELETE FROM bodega_informe_gerencial_rf WHERE date_format(fecha, '%Y-%m-%d') = '" . $fechaSoloDia . "';";

                /* INFORME GERENCIAL USUARIO  por dia por fecha cierre*/
                $sqlInformeGerencialUsuarioFechaCierre = "
SELECT x.pais_id,
       x.mandante,
       x.moneda,
       DATE_FORMAT(fecha, '%Y-%m-%d') fecha_cierre,
       SUM(cant_tickets)              cant_tickets,

       SUM(saldo_apuestas)            valor_apostado,
       SUM(saldo_premios)             valor_premios,
       0                              proyeccion_premios,
       SUM(bonos)                     bonos,
       SUM(registros)                 registros,
       SUM(primerdepositos)           primerdepositos,
       SUM(jackpots)                  jackpots

FROM ((SELECT usuario.mandante,
              usuario.pais_id,
              usuario.moneda,
              0                                                              saldo_recarga,
              DATE_FORMAT(usuario_deporte_resumen_rf.fecha_crea, '%Y-%m-%d') fecha,
              SUM(CASE WHEN tipo IN ('BET') THEN valor ELSE -valor END)      saldo_apuestas,
              SUM(CASE WHEN tipo IN ('BET') THEN 1 ELSE 0 END)               cant_tickets,
              0                                                              saldo_premios,
              0                                                              registros,
              0                                                              bonos,
              0                                                              primerdepositos,
              0                                                              jackpots
       FROM casino.usuario_deporte_resumen_rf
                inner join usuario on usuario.usuario_id = usuario_deporte_resumen_rf.usuario_id
       WHERE tipo IN ('BET', 'STAKEDECREASE', 'REFUND')
         AND (usuario_deporte_resumen_rf.fecha_crea) = '" . $fechaSoloDia . "'
         AND usuario.mandante = 8
       GROUP BY usuario.mandante, usuario.pais_id)
      UNION

      (SELECT usuario.mandante,
              usuario.pais_id,
              usuario.moneda,
              0                                                                                 saldo_recarga,
              DATE_FORMAT(usuario_deporte_resumen_rf.fecha_crea, '%Y-%m-%d')                    fecha,
              0                                                                                 saldo_apuestas,
              0                                                                                 cant_tickets,
              SUM(CASE WHEN tipo IN ('WIN', 'NEWCREDIT', 'CASHOUT') THEN valor ELSE -valor END) saldo_premios,
              0                                                                                 registros,
              0                                                                                 bonos,
              0                                                                                 primerdepositos,
              0                                                                                 jackpots
       FROM casino.usuario_deporte_resumen_rf
                inner join usuario on usuario.usuario_id = usuario_deporte_resumen_rf.usuario_id

       WHERE tipo IN ('WIN', 'NEWCREDIT', 'CASHOUT', 'NEWDEBIT')
         AND (usuario_deporte_resumen_rf.fecha_crea) = '" . $fechaSoloDia . "'
         AND usuario.mandante = 8
       GROUP BY usuario.mandante, usuario.pais_id)

      UNION

      SELECT usuario.mandante,
             usuario.pais_id,
             usuario.moneda,
             0                                           saldo_recarga,
             DATE_FORMAT(usuario.fecha_crea, '%Y-%m-%d') fecha,
             0                                           saldo_apuestas,
             0                                           cant_tickets,
             0                                           saldo_premios,
             COUNT(*)                                    registros,
             0                                           bonos,
             0                                           primerdepositos,
             0                                           jackpots
      FROM usuario
               INNER JOIN usuario_perfil ON (usuario.usuario_id = usuario_perfil.usuario_id AND
                                             usuario_perfil.perfil_id = 'USUONLINE')
               inner join pais on (pais.pais_id = usuario.pais_id)

      WHERE DATE_FORMAT(usuario.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "'
        AND usuario.mandante = 8

      GROUP BY usuario.mandante, usuario.pais_id

      UNION

      SELECT usuario.mandante,
             usuario.pais_id,
             usuario.moneda,
             0                                      saldo_recarga,
             DATE_FORMAT(pl.fecha_crea, '%Y-%m-%d') fecha,
             0                                      saldo_apuestas,
             0                                      cant_tickets,
             0                                      saldo_premios,
             0                                      registros,
             SUM(CASE
                     when (pl.estado = 'L') then pl.valor
                     when (pl.estado = 'E') then -pl.valor
                     else 0 end)                    bonos,
             0                                      primerdepositos,
             0                                      jackpots
      FROM bono_log pl
               INNER JOIN usuario ON (usuario.usuario_id = pl.usuario_id)
      where (pl.estado = 'L' OR pl.estado = 'E')
        AND pl.tipo NOT IN ('TC', 'TL', 'SC', 'SCV', 'SL', 'TV', 'FC','DC', 'DL', 'DV', 'NC', 'NL', 'NV', 'JS', 'JL', 'JV', 'JD')
        and DATE_FORMAT(pl.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "'
        AND usuario.mandante = 8

      GROUP BY usuario.mandante, usuario.pais_id, usuario.moneda

      UNION

      SELECT usuario.mandante,
             usuario.pais_id,
             usuario.moneda,
             0                                                     saldo_recarga,
             DATE_FORMAT(usuario.fecha_primerdeposito, '%Y-%m-%d') fecha,
             0                                                     saldo_apuestas,
             0                                                     cant_tickets,
             0                                                     saldo_premios,
             0                                                     registros,
             0                                                     bonos,
             COUNT(*)                                              primerdepositos,
             0                                                     jackpots
      FROM usuario
               INNER JOIN usuario_perfil ON (usuario.usuario_id = usuario_perfil.usuario_id AND
                                             usuario_perfil.perfil_id = 'USUONLINE')
      WHERE DATE_FORMAT(usuario.fecha_primerdeposito, '%Y-%m-%d') = '" . $fechaSoloDia . "'
        AND usuario.mandante = 8


      GROUP BY usuario.mandante, usuario.pais_id, usuario.moneda
      
      UNION

      SELECT usuario.mandante,
             usuario.pais_id,
             usuario.moneda,
             0                                      saldo_recarga,
             DATE_FORMAT(pl.fecha_crea, '%Y-%m-%d') fecha,
             0                                      saldo_apuestas,
             0                                      cant_tickets,
             0                                      saldo_premios,
             0                                      registros,
             0                                      bonos,
             0                                      primerdepositos,
             SUM (pl.valor)                         jackpots
      FROM bono_log pl
               INNER JOIN usuario ON (usuario.usuario_id = pl.usuario_id)
      where (pl.estado = 'L')
        AND pl.tipo = 'JD'
        and DATE_FORMAT(pl.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "'
        AND usuario.mandante = 8

      GROUP BY usuario.mandante, usuario.pais_id, usuario.moneda) x
";

                /* INFORME GERENCIAL USUARIO  por dia por fecha creacion*/
                $sqlInformeGerencialUsuarioFechaCreacion = "
SELECT x.pais_id,
       x.mandante,
       x.moneda,
       DATE_FORMAT(fecha, '%Y-%m-%d') fecha_cierre,
       SUM(cant_tickets)              cant_tickets,

       SUM(saldo_apuestas)            valor_apostado,
       SUM(saldo_premios)             valor_premios,
       0                              proyeccion_premios,
       SUM(bonos)                     bonos,
       SUM(registros)                 registros,
       SUM(primerdepositos)           primerdepositos,
       SUM(jackpots)                  jackpots

FROM ((SELECT usuario.mandante,
              usuario.pais_id,
              usuario.moneda,
              0                                                              saldo_recarga,
              DATE_FORMAT(usuario_deporte_resumen_rf.fecha_crea, '%Y-%m-%d') fecha,
              SUM(CASE WHEN tipo IN ('BET') THEN valor ELSE -valor END)      saldo_apuestas,
              SUM(CASE WHEN tipo IN ('BET') THEN 1 ELSE 0 END)               cant_tickets,
              0                                                              saldo_premios,
              0                                                              registros,
              0                                                              bonos,
              0                                                              primerdepositos,
              0                                                              jackpots
       FROM casino.usuario_deporte_resumen_rf
                inner join usuario on usuario.usuario_id = usuario_deporte_resumen_rf.usuario_id
       WHERE tipo IN ('BET', 'STAKEDECREASE', 'REFUND')
         AND (usuario_deporte_resumen_rf.fecha_crea) = '" . $fechaSoloDia . "'
         AND usuario.mandante = 8
       GROUP BY usuario.mandante, usuario.pais_id)
      UNION

      (SELECT usuario.mandante,
              usuario.pais_id,
              usuario.moneda,
              0                                                                                 saldo_recarga,
              DATE_FORMAT(usuario_deporte_resumen_rf.fecha_crea, '%Y-%m-%d')                    fecha,
              0                                                                                 saldo_apuestas,
              0                                                                                 cant_tickets,
              SUM(CASE WHEN tipo IN ('WIN', 'NEWCREDIT', 'CASHOUT') THEN valor ELSE -valor END) saldo_premios,
              0                                                                                 registros,
              0                                                                                 bonos,
              0                                                                                 primerdepositos,
              0                                                                                 jackpots
       FROM casino.usuario_deporte_resumen_rf
                inner join usuario on usuario.usuario_id = usuario_deporte_resumen_rf.usuario_id

       WHERE tipo IN ('WIN', 'NEWCREDIT', 'CASHOUT', 'NEWDEBIT')
         AND (usuario_deporte_resumen_rf.fecha_crea) = '" . $fechaSoloDia . "'
         AND usuario.mandante = 8
       GROUP BY usuario.mandante, usuario.pais_id)

      UNION

      SELECT usuario.mandante,
             usuario.pais_id,
             usuario.moneda,
             0                                           saldo_recarga,
             DATE_FORMAT(usuario.fecha_crea, '%Y-%m-%d') fecha,
             0                                           saldo_apuestas,
             0                                           cant_tickets,
             0                                           saldo_premios,
             COUNT(*)                                    registros,
             0                                           bonos,
             0                                           primerdepositos,
             0                                           jackpots
      FROM usuario
               INNER JOIN usuario_perfil ON (usuario.usuario_id = usuario_perfil.usuario_id AND
                                             usuario_perfil.perfil_id = 'USUONLINE')
               inner join pais on (pais.pais_id = usuario.pais_id)

      WHERE DATE_FORMAT(usuario.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "'
        AND usuario.mandante = 8

      GROUP BY usuario.mandante, usuario.pais_id

      UNION

      SELECT usuario.mandante,
             usuario.pais_id,
             usuario.moneda,
             0                                      saldo_recarga,
             DATE_FORMAT(pl.fecha_crea, '%Y-%m-%d') fecha,
             0                                      saldo_apuestas,
             0                                      cant_tickets,
             0                                      saldo_premios,
             0                                      registros,
             SUM(CASE
                     when (pl.estado = 'L') then pl.valor
                     when (pl.estado = 'E') then -pl.valor
                     else 0 end)                    bonos,
             0                                      primerdepositos,
             0                                      jackpots
      FROM bono_log pl
               INNER JOIN usuario ON (usuario.usuario_id = pl.usuario_id)
      where (pl.estado = 'L' OR pl.estado = 'E')
        AND pl.tipo NOT IN ('TC', 'TL', 'SC', 'SCV', 'SL', 'TV', 'FC','DC', 'DL', 'DV', 'NC', 'NL', 'NV', 'JS', 'JL', 'JV', 'JD')
        and DATE_FORMAT(pl.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "'
        AND usuario.mandante = 8

      GROUP BY usuario.mandante, usuario.pais_id, usuario.moneda

      UNION

      SELECT usuario.mandante,
             usuario.pais_id,
             usuario.moneda,
             0                                                     saldo_recarga,
             DATE_FORMAT(usuario.fecha_primerdeposito, '%Y-%m-%d') fecha,
             0                                                     saldo_apuestas,
             0                                                     cant_tickets,
             0                                                     saldo_premios,
             0                                                     registros,
             0                                                     bonos,
             COUNT(*)                                              primerdepositos,
             0                                                     jackpots
      FROM usuario
               INNER JOIN usuario_perfil ON (usuario.usuario_id = usuario_perfil.usuario_id AND
                                             usuario_perfil.perfil_id = 'USUONLINE')
      WHERE DATE_FORMAT(usuario.fecha_primerdeposito, '%Y-%m-%d') = '" . $fechaSoloDia . "'
        AND usuario.mandante = 8


      GROUP BY usuario.mandante, usuario.pais_id, usuario.moneda
      
      UNION

      SELECT usuario.mandante,
             usuario.pais_id,
             usuario.moneda,
             0                                      saldo_recarga,
             DATE_FORMAT(pl.fecha_crea, '%Y-%m-%d') fecha,
             0                                      saldo_apuestas,
             0                                      cant_tickets,
             0                                      saldo_premios,
             0                                      registros,
             0                                      bonos,
             0                                      primerdepositos,
             SUM(pl.valor)                          jackpots
      FROM bono_log pl
               INNER JOIN usuario ON (usuario.usuario_id = pl.usuario_id)
      where (pl.estado = 'L')
        AND pl.tipo = 'JD'
        and DATE_FORMAT(pl.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "'
        AND usuario.mandante = 8

      GROUP BY usuario.mandante, usuario.pais_id, usuario.moneda) x
";

                $sqlInformeGerencialPVFechaCierre = "
        
SELECT x.pais_id,
       x.mandante,
       x.moneda,
       DATE_FORMAT(fecha, '%Y-%m-%d') fecha_cierre,
       SUM(cant_tickets)              cant_tickets,

       SUM(saldo_apuestas)            valor_apostado,
       SUM(saldo_premios)             valor_premios,
       0                              proyeccion_premios,
       SUM(bonos)                     bonos,
       SUM(registros)                 registros,
       SUM(primerdepositos)           primerdepositos

FROM ((SELECT usuario.mandante,
              usuario.pais_id,
              usuario.moneda,
              0                                                              saldo_recarga,
              DATE_FORMAT(usuario_deporte_resumen_rf.fecha_crea, '%Y-%m-%d') fecha,
              SUM(valor)                                                     saldo_apuestas,
              SUM(cantidad)                                                  cant_tickets,
              0                                                              saldo_premios,
              0                                                              registros,
              0                                                              bonos,
              0                                                              primerdepositos
       FROM casino.usuario_deporte_resumen_rf
                inner join usuario on usuario.usuario_id = usuario_deporte_resumen_rf.usuario_id
       WHERE tipo = '1'
         AND (usuario_deporte_resumen_rf.fecha_crea) = '" . $fechaSoloDia . "'
         AND usuario.mandante = 8
       GROUP BY usuario.mandante, usuario.pais_id)
      UNION

      (SELECT usuario.mandante,
              usuario.pais_id,
              usuario.moneda,
              0                                                              saldo_recarga,
              DATE_FORMAT(usuario_deporte_resumen_rf.fecha_crea, '%Y-%m-%d') fecha,
              0                                                              saldo_apuestas,
              0                                                              cant_tickets,
              SUM(valor)                                                     saldo_premios,
              0                                                              registros,
              0                                                              bonos,
              0                                                              primerdepositos
       FROM casino.usuario_deporte_resumen_rf
                inner join usuario on usuario.usuario_id = usuario_deporte_resumen_rf.usuario_id

       WHERE tipo = '3'
         AND (usuario_deporte_resumen_rf.fecha_crea) = '" . $fechaSoloDia . "'
         AND usuario.mandante = 8
       GROUP BY usuario.mandante, usuario.pais_id)) x

";

                /* INFORME GERENCIAL USUARIO  por dia por fecha creacion*/
                $sqlInformeGerencialPVFechaCreacion = "
        
SELECT x.pais_id,
       x.mandante,
       x.moneda,
       DATE_FORMAT(fecha, '%Y-%m-%d') fecha_cierre,
       SUM(cant_tickets)              cant_tickets,

       SUM(saldo_apuestas)            valor_apostado,
       SUM(saldo_premios)             valor_premios,
       0                              proyeccion_premios,
       SUM(bonos)                     bonos,
       SUM(registros)                 registros,
       SUM(primerdepositos)           primerdepositos

FROM ((SELECT usuario.mandante,
              usuario.pais_id,
              usuario.moneda,
              0                                                              saldo_recarga,
              DATE_FORMAT(usuario_deporte_resumen_rf.fecha_crea, '%Y-%m-%d') fecha,
              SUM(valor)                                                     saldo_apuestas,
              SUM(cantidad)                                                  cant_tickets,
              0                                                              saldo_premios,
              0                                                              registros,
              0                                                              bonos,
              0                                                              primerdepositos
       FROM casino.usuario_deporte_resumen_rf
                inner join usuario on usuario.usuario_id = usuario_deporte_resumen_rf.usuario_id
       WHERE tipo = '1'
         AND (usuario_deporte_resumen_rf.fecha_crea) = '" . $fechaSoloDia . "'
         AND usuario.mandante = 8
       GROUP BY usuario.mandante, usuario.pais_id)
      UNION

      (SELECT usuario.mandante,
              usuario.pais_id,
              usuario.moneda,
              0                                                              saldo_recarga,
              DATE_FORMAT(usuario_deporte_resumen_rf.fecha_crea, '%Y-%m-%d') fecha,
              0                                                              saldo_apuestas,
              0                                                              cant_tickets,
              SUM(valor)                                                     saldo_premios,
              0                                                              registros,
              0                                                              bonos,
              0                                                              primerdepositos
       FROM casino.usuario_deporte_resumen_rf
                inner join usuario on usuario.usuario_id = usuario_deporte_resumen_rf.usuario_id

       WHERE tipo = '3'
         AND (usuario_deporte_resumen_rf.fecha_crea) = '" . $fechaSoloDia . "'
         AND usuario.mandante = 8
       GROUP BY usuario.mandante, usuario.pais_id)) x
";
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


                $dataSaldoInicial = $BonoInterno->execQuery($transaccion, $sqlInformeGerencialUsuarioFechaCierre);


                foreach ($dataSaldoInicial as $datanum) {

                    print_r($datanum);
                    $sql = "INSERT INTO bodega_informe_gerencial_rf (pais_id, mandante, fecha, cantidad, saldo_apuestas, saldo_premios, saldo_premios_pendientes, saldo_bono, tipo_usuario, tipo_fecha, primeros_depositos,usuarios_registrados,billetera_id, premio_jackpot) 
                    VALUES (" . $datanum->{'x.pais_id'} . ",
                    " . $datanum->{'x.mandante'} . ",
                    '" . $datanum->{'.fecha_cierre'} . "',
                    '" . ($datanum->{'.cant_tickets'} == '' ? 0 : $datanum->{'.cant_tickets'}) . "',
                    '" . ($datanum->{'.valor_apostado'} == '' ? 0 : $datanum->{'.valor_apostado'}) . "',
                    '" . ($datanum->{'.valor_premios'} == '' ? 0 : $datanum->{'.valor_premios'}) . "',
                    '" . ($datanum->{'.proyeccion_premios'} == '' ? 0 : $datanum->{'.proyeccion_premios'}) . "',
                    '" . ($datanum->{'.bonos'} == '' ? 0 : $datanum->{'.bonos'}) . "',
                    1,
                    2,
                    '" . ($datanum->{'.primerdepositos'} == '' ? 0 : $datanum->{'.primerdepositos'}) . "',
                    '" . ($datanum->{'.registros'} == '' ? 0 : $datanum->{'.registros'}) . "',
                    '" . $wallet . "',
                    '" . ($datanum->{'.jackpots'} == '' ? 0 : $datanum->{'.jackpots'}) . "'

                    )
                    
       ;

       ";
                    print $sql;

                    $BonoInterno->execQuery($transaccion, $sql);

                }

                $dataSaldoInicial = $BonoInterno->execQuery($transaccion, $sqlInformeGerencialUsuarioFechaCreacion);


                foreach ($dataSaldoInicial as $datanum) {


                    $sql = "INSERT INTO bodega_informe_gerencial_rf (pais_id, mandante, fecha, cantidad, saldo_apuestas, saldo_premios, saldo_premios_pendientes, saldo_bono, tipo_usuario, tipo_fecha, primeros_depositos,usuarios_registrados,billetera_id, premio_jackpot) 
                    VALUES (" . $datanum->{'x.pais_id'} . ",
                    " . $datanum->{'x.mandante'} . ",
                    '" . $datanum->{'.fecha_cierre'} . "',
                    '" . ($datanum->{'.cant_tickets'} == '' ? 0 : $datanum->{'.cant_tickets'}) . "',
                    '" . ($datanum->{'.valor_apostado'} == '' ? 0 : $datanum->{'.valor_apostado'}) . "',
                    '" . ($datanum->{'.valor_premios'} == '' ? 0 : $datanum->{'.valor_premios'}) . "',
                    '" . ($datanum->{'.proyeccion_premios'} == '' ? 0 : $datanum->{'.proyeccion_premios'}) . "',
                    '" . ($datanum->{'.bonos'} == '' ? 0 : $datanum->{'.bonos'}) . "',
                    1,
                    1,
                    '" . ($datanum->{'.primerdepositos'} == '' ? 0 : $datanum->{'.primerdepositos'}) . "',
                    '" . ($datanum->{'.registros'} == '' ? 0 : $datanum->{'.registros'}) . "',
                     '" . $wallet . "',
                     '" . ($datanum->{'.jackpots'} == '' ? 0 : $datanum->{'.jackpots'}) . "'
                    
                    )
                    
       
;

       ";
                    print_r($sql);

                    $BonoInterno->execQuery($transaccion, $sql);

                }

                print_r('PASO');
                print_r($sqlInformeGerencialPVFechaCierre);

                $dataSaldoInicial = $BonoInterno->execQuery($transaccion, $sqlInformeGerencialPVFechaCierre);

                print_r('PASO2');

                foreach ($dataSaldoInicial as $datanum) {

                    print_r($datanum);

                    $sql = "INSERT INTO bodega_informe_gerencial_rf (pais_id, mandante, fecha, cantidad, saldo_apuestas, saldo_premios, saldo_premios_pendientes, saldo_bono, tipo_usuario, tipo_fecha, primeros_depositos,usuarios_registrados,billetera_id) 
                    VALUES (" . $datanum->{'x.pais_id'} . ",
                    " . $datanum->{'x.mandante'} . ",
                    '" . $datanum->{'.fecha_cierre'} . "',
                    '" . ($datanum->{'.cant_tickets'} == '' ? 0 : $datanum->{'.cant_tickets'}) . "',
                    '" . ($datanum->{'.valor_apostado'} == '' ? 0 : $datanum->{'.valor_apostado'}) . "',
                    '" . ($datanum->{'.valor_premios'} == '' ? 0 : $datanum->{'.valor_premios'}) . "',
                    '" . ($datanum->{'.proyeccion_premios'} == '' ? 0 : $datanum->{'.proyeccion_premios'}) . "',
                    '" . 0 . "',
                    2,
                    2,
                    '" . ($datanum->{'.primerdepositos'} == '' ? 0 : $datanum->{'.primerdepositos'}) . "',
                    '" . ($datanum->{'.registros'} == '' ? 0 : $datanum->{'.registros'}) . "',
                                        " . $wallet . "

                    )
                    
                 ;

       ";
                    print_r($sql);

                    $BonoInterno->execQuery($transaccion, $sql);

                }

                $dataSaldoInicial = $BonoInterno->execQuery($transaccion, $sqlInformeGerencialPVFechaCreacion);


                foreach ($dataSaldoInicial as $datanum) {

                    print_r($datanum);

                    $sql = "INSERT INTO bodega_informe_gerencial_rf (pais_id, mandante, fecha, cantidad, saldo_apuestas, saldo_premios, saldo_premios_pendientes, saldo_bono, tipo_usuario, tipo_fecha, primeros_depositos,usuarios_registrados,billetera_id) 
                    VALUES (" . $datanum->{'x.pais_id'} . ",
                    " . $datanum->{'x.mandante'} . ",
                    '" . $datanum->{'.fecha_cierre'} . "',
                    '" . ($datanum->{'.cant_tickets'} == '' ? 0 : $datanum->{'.cant_tickets'}) . "',
                    '" . ($datanum->{'.valor_apostado'} == '' ? 0 : $datanum->{'.valor_apostado'}) . "',
                    '" . ($datanum->{'.valor_premios'} == '' ? 0 : $datanum->{'.valor_premios'}) . "',
                    '" . ($datanum->{'.proyeccion_premios'} == '' ? 0 : $datanum->{'.proyeccion_premios'}) . "',
                    '" . 0 . "',
                    2,
                    1,
                    '" . ($datanum->{'.primerdepositos'} == '' ? 0 : $datanum->{'.primerdepositos'}) . "',
                    '" . ($datanum->{'.registros'} == '' ? 0 : $datanum->{'.registros'}) . "',
                                        " . $wallet . "
                    
                    )
                    
         ;

       ";

                    $BonoInterno->execQuery($transaccion, $sql);

                }
                //$procesoInterno=$BonoInterno->execQuery($transaccion, "INSERT INTO procesos_internos ( tipo, fecha_crea, usucrea_id) VALUES ('ResumenesBodegaRF','".date("Y-m-d 00:00:00")."','0');");

                $transaccion->commit();

                $log = "\r\n" . "-------------------------" . "\r\n";
                $log = $log . "Terminacion: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
                fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

                $message = "*CRON: (Fin) * " . " ResumenesBodegaRF - Fecha: " . date("Y-m-d H:i:s");

                //exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#procesos-cron-resumenes' > /dev/null & ");


            } catch (Exception $e) {
                print_r($e);
                $log = "\r\n" . "-------------------------" . "\r\n";
                $log = $log . "ERROR: " . $e->getMessage() . " - " . date('Y-m-d H:i:s');
                fwriteCustom('log_' . date("Y-m-d") . '.log', $log);


                $message = "*CRON: (ERROR) * " . " Resumenes - Fecha: " . date("Y-m-d H:i:s");

                exec("php -f " . __DIR__ . "../src/imports/Slack/message.php '" . $message . "' '#procesos-cron-resumenes' > /dev/null & ");

            }


        }

    }
}