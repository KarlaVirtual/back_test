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
 * Clase 'CronJobResumenesBodega'
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
class CronJobResumenesBodega
{


    public function __construct()
    {
    }

    public function execute()
    {


///home/devadmin/api/api/
        $_ENV["NEEDINSOLATIONLEVEL"] = '1';
        $_ENV["enabledConnectionGlobal"] = 1;

        $wallets = array('0', '1');

        foreach ($wallets as $wallet) {


            ini_set('memory_limit', '-1');

            $message = "*CRON: (Inicio) * " . " ResumenesBodega - Fecha: " . date("Y-m-d H:i:s");

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

                $strEliminado = "DELETE FROM bodega_informe_gerencial WHERE date_format(fecha, '%Y-%m-%d') = '" . $fechaSoloDia . "';";

                /* INFORME GERENCIAL USUARIO  por dia por fecha cierre*/
                $sqlInformeGerencialUsuarioFechaCierre = "select x.*, (pl2.valor) bonos, pl3.registros, pl4.primerdepositos, (pl5.valor) jackpots
from (select a.mandante,
             a.fecha_crea,
             a.fecha_cierre,
             a.pais_id,
             a.pais_iso                                                        pais_iso,
             a.moneda,
             sum(a.cant_tickets)                                           cant_tickets,
             sum(a.impuesto_premios)                                                        impuesto_premios,
             0                                                        impuesto_apuestas,
             sum(a.valor_apostado
                 )                                                        valor_apostado,
             sum(a.valor_ticket_prom)        valor_ticket_prom,
             sum(a.proyeccion_premios)                                           proyeccion_premios,
             sum(a.valor_premios) valor_premios

             FROM (

    select pais_mandante.mandante mandante,  '" . $fechaSoloDia . "' fecha_crea,  '" . $fechaSoloDia . "' fecha_cierre, pais_mandante.pais_id pais_id, pais.iso pais_iso, pais_moneda.moneda moneda,
                      0                                       cant_tickets,
             0                                                        impuesto_premios,
             0                                                        impuesto_apuestas,
                      0                                       valor_apostado,
                      0                                       valor_ticket_prom,
                      0                                       proyeccion_premios,
                      0 valor_premios

         from pais_mandante
                  inner join pais on (pais.pais_id = pais_mandante.pais_id)
                  inner join pais_moneda on (pais.pais_id = pais_moneda.pais_id and pais.moneda = pais_mandante.moneda)

         UNION select a.mandante,a.fecha_crea,
             a.fecha_cierre,
             c.pais_id,
             c.iso                                                        pais_iso,
             b.moneda,
             count(a.ticket_id)                                           cant_tickets,
             sum(a.impuesto)                                                        impuesto_premios,
             0                                                        impuesto_apuestas,
             sum(CASE
                     WHEN a.eliminado = 'S' AND a.fecha_crea != fecha_cierre AND a.fecha_cierre = '" . $fechaSoloDia . "'
                         THEN -a.vlr_apuesta
                     WHEN (a.eliminado = 'S' AND a.fecha_crea = fecha_cierre)
                         THEN 0
                     ELSE a.vlr_apuesta END
                 )                                                        valor_apostado,
             sum(CASE
                     WHEN a.eliminado = 'S' AND a.fecha_crea != fecha_cierre AND a.fecha_cierre = '" . $fechaSoloDia . "'
                         THEN -a.vlr_apuesta
                     WHEN a.eliminado = 'S' AND a.fecha_crea = fecha_cierre
                         THEN 0
                     ELSE a.vlr_apuesta END) / sum(CASE
                                                       WHEN a.eliminado = 'S' AND a.fecha_crea != fecha_cierre AND
                                                            a.fecha_cierre = '" . $fechaSoloDia . "'
                                                           THEN -1
                                                       WHEN a.eliminado = 'S' AND a.fecha_crea = fecha_cierre
                                                           THEN 0
                                                       ELSE 1 END)        valor_ticket_prom,
             sum((select exp(sum(log(x.logro))) logros from it_ticket_det x where x.ticket_id = a.ticket_id) *
                 a.vlr_apuesta)                                           proyeccion_premios,
             sum(case when a.premiado = 'S' then a.vlr_premio else 0 end) valor_premios
      from it_ticket_enc a
               inner join usuario b on (a.mandante = b.mandante and a.usuario_id = b.usuario_id)
               inner join usuario_perfil up on (up.usuario_id = b.usuario_id)
               inner join pais c on (b.pais_id = c.pais_id and c.pais_id != 1)
      where 1 = 1
        and ((a.eliminado = 'S' AND a.fecha_crea != fecha_cierre AND a.fecha_cierre = '" . $fechaSoloDia . "')) and a.wallet='" . $wallet . "'
        AND up.perfil_id = 'USUONLINE'
      group by a.mandante,a.fecha_cierre, c.pais_id, b.moneda
      
         UNION select a.mandante,a.fecha_crea,
             a.fecha_cierre,
             c.pais_id,
             c.iso                                                        pais_iso,
             b.moneda,
             count(a.ticket_id)                                           cant_tickets,
             sum(CASE
                     WHEN a.eliminado = 'S' AND a.fecha_crea != fecha_cierre AND a.fecha_cierre = '" . $fechaSoloDia . "'
                         THEN -a.impuesto
                     WHEN (a.eliminado = 'S' AND a.fecha_crea = fecha_cierre)
                         THEN 0
                     ELSE a.impuesto END
                 )                                                         impuesto_premios,
             0                                                        impuesto_apuestas,
             sum(CASE
                     WHEN a.eliminado = 'S' AND a.fecha_crea != fecha_cierre AND a.fecha_cierre = '" . $fechaSoloDia . "'
                         THEN -a.vlr_apuesta
                     WHEN (a.eliminado = 'S' AND a.fecha_crea = fecha_cierre)
                         THEN 0
                     ELSE a.vlr_apuesta END
                 )                                                        valor_apostado,
             sum(CASE
                     WHEN a.eliminado = 'S' AND a.fecha_crea != fecha_cierre AND a.fecha_cierre = '" . $fechaSoloDia . "'
                         THEN -a.vlr_apuesta
                     WHEN a.eliminado = 'S' AND a.fecha_crea = fecha_cierre
                         THEN 0
                     ELSE a.vlr_apuesta END) / sum(CASE
                                                       WHEN a.eliminado = 'S' AND a.fecha_crea != fecha_cierre AND
                                                            a.fecha_cierre = '" . $fechaSoloDia . "'
                                                           THEN -1
                                                       WHEN a.eliminado = 'S' AND a.fecha_crea = fecha_cierre
                                                           THEN 0
                                                       ELSE 1 END)        valor_ticket_prom,
             sum((select exp(sum(log(x.logro))) logros from it_ticket_det x where x.ticket_id = a.ticket_id) *
                 a.vlr_apuesta)                                           proyeccion_premios,
             sum(case when a.premiado = 'S' then a.vlr_premio else 0 end) valor_premios
      from it_ticket_enc a
               inner join usuario b on (a.mandante = b.mandante and a.usuario_id = b.usuario_id)
               inner join usuario_perfil up on (up.usuario_id = b.usuario_id)
               inner join pais c on (b.pais_id = c.pais_id and c.pais_id != 1)
      where 1 = 1
        and (
             (a.eliminado = 'N' AND a.fecha_cierre = '" . $fechaSoloDia . "')) and a.wallet='" . $wallet . "'
        AND up.perfil_id = 'USUONLINE'
      group by a.mandante,a.fecha_cierre, c.pais_id, b.moneda
                   UNION
                   SELECT usuario.mandante,
                          it_transaccion.fecha_crea,
                          it_transaccion.fecha_crea,
                          usuario.pais_id                         usuario,
                          pais.iso                                pais_iso,
                          usuario.moneda,
                          0                                       cant_tickets,
             0                                                        impuesto_premios,
             0                                                        impuesto_apuestas,
                          0                                       valor_apostado,
                          0                                       valor_ticket_prom,
                          0                                       proyeccion_premios,
                          SUM(CASE
                                  WHEN it_transaccion.tipo IN ('NEWCREDIT', 'WIN', 'REFUND', 'CASHOUT')
                                      then it_transaccion.valor
                                  else -it_transaccion.valor END) valor_premios

                   FROM it_transaccion
                            INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_transaccion.ticket_id)
                            INNER JOIN usuario ON (usuario.usuario_id = it_transaccion.usuario_id)
                            inner join pais on (pais.pais_id = usuario.pais_id)
                            inner join usuario_perfil  on (usuario_perfil.usuario_id = usuario.usuario_id)

                   WHERE it_transaccion.fecha_crea = '" . $fechaSoloDia . "' AND usuario_perfil.perfil_id = 'USUONLINE'
                     AND (it_transaccion.fecha_crea > it_ticket_enc.fecha_cierre and it_ticket_enc.fecha_cierre!='') and it_ticket_enc.wallet='" . $wallet . "'
                    group by usuario.mandante, it_transaccion.fecha_crea, usuario.pais_id, usuario.moneda
                  ) a

      group by a.mandante, a.fecha_cierre, a.pais_id, a.moneda

      order by a.mandante,a.fecha_cierre, a.pais_id, a.moneda) x
         LEFT OUTER JOIN (SELECT usuario.moneda,usuario.mandante,usuario.pais_id,
                                 pl.estado,
                                 SUM(CASE when (pl.estado = 'L') then pl.valor when (pl.estado = 'E') then -pl.valor else 0 end) valor,
                                 DATE_FORMAT(pl.fecha_crea, '%Y-%m-%d') fecha_crea2
                          FROM bono_log pl
                                   INNER JOIN usuario ON (usuario.usuario_id = pl.usuario_id)
                          where (pl.estado = 'L' OR pl.estado = 'E') AND pl.tipo NOT IN ('TC','TL','SC','SCV','SL','TV','FC','DC', 'DL', 'DV', 'NC', 'NL', 'NV', 'JS', 'JL', 'JV', 'JD') 
                          GROUP BY usuario.mandante,usuario.pais_id,usuario.moneda, fecha_crea2) pl2
                         ON (pl2.fecha_crea2 = DATE_FORMAT(x.fecha_cierre, '%Y-%m-%d') AND x.moneda = pl2.moneda AND x.pais_id = pl2.pais_id AND x.mandante = pl2.mandante)
         LEFT OUTER JOIN (SELECT usuario.moneda,usuario.mandante,
                                 DATE_FORMAT(usuario.fecha_crea, '%Y-%m-%d') fecha_crea2,
                                 COUNT(*)                                     registros,
                                 pais.pais_id
                          FROM usuario
                                   INNER JOIN usuario_perfil ON (usuario.usuario_id = usuario_perfil.usuario_id AND
                                                                 usuario_perfil.perfil_id = 'USUONLINE')
                                   inner join pais on (pais.pais_id = usuario.pais_id)
                          GROUP BY usuario.mandante,usuario.pais_id, fecha_crea2) pl3
                         ON (pl3.fecha_crea2 = DATE_FORMAT(x.fecha_cierre, '%Y-%m-%d') AND x.pais_id = pl3.pais_id  AND x.mandante = pl3.mandante)
         LEFT OUTER JOIN (SELECT usuario.moneda,usuario.mandante,
                                 DATE_FORMAT(usuario.fecha_primerdeposito, '%Y-%m-%d') fecha_crea2,
                                 COUNT(*)                                               primerdepositos,usuario.pais_id
                          FROM usuario
                                   INNER JOIN usuario_perfil ON (usuario.usuario_id = usuario_perfil.usuario_id AND
                                                                 usuario_perfil.perfil_id = 'USUONLINE')
                          GROUP BY usuario.mandante,usuario.pais_id,usuario.moneda, fecha_crea2) pl4
                         ON (pl4.fecha_crea2 = DATE_FORMAT(x.fecha_cierre, '%Y-%m-%d') AND x.moneda = pl4.moneda AND x.pais_id = pl4.pais_id  AND x.mandante = pl4.mandante)
         LEFT OUTER JOIN (SELECT usuario.moneda,usuario.mandante,usuario.pais_id,
                                 pl.estado,
                                 SUM(CASE when (pl.estado = 'L') then pl.valor when (pl.estado = 'E') then -pl.valor else 0 end) valor,
                                 DATE_FORMAT(pl.fecha_crea, '%Y-%m-%d') fecha_crea2
                          FROM bono_log pl
                                   INNER JOIN usuario ON (usuario.usuario_id = pl.usuario_id)
                          where (pl.estado = 'L' OR pl.estado = 'E') AND pl.tipo = 'JD' 
                          GROUP BY usuario.mandante,usuario.pais_id,usuario.moneda, fecha_crea2) pl5
                         ON (pl5.fecha_crea2 = DATE_FORMAT(x.fecha_cierre, '%Y-%m-%d') AND x.moneda = pl5.moneda AND x.pais_id = pl5.pais_id AND x.mandante = pl5.mandante)
 WHERE x.mandante NOT IN (3,4,5,6,7,10,22,17,25)
ORDER BY x.fecha_cierre ASC;
";

                /* INFORME GERENCIAL USUARIO  por dia por fecha creacion*/
                $sqlInformeGerencialUsuarioFechaCreacion = "select x.*, (pl2.valor) bonos, pl3.registros, pl4.primerdepositos, (pl5.valor) jackpots
from (
 select a.mandante,
             a.fecha_crea,
             a.fecha_cierre,
             a.pais_id,
             a.pais_iso                                                        pais_iso,
             a.moneda,
             sum(a.cant_tickets)                                           cant_tickets,
             sum(a.impuesto_premios)                                                        impuesto_premios,
             0                                                        impuesto_apuestas,
             sum(a.valor_apostado)                                                        valor_apostado,
             sum(a.valor_ticket_prom)        valor_ticket_prom,
             sum(a.proyeccion_premios)                                           proyeccion_premios,
             sum(a.valor_premios) valor_premios

             FROM (

    select pais_mandante.mandante mandante,  '" . $fechaSoloDia . "' fecha_crea,  '" . $fechaSoloDia . "' fecha_cierre, pais_mandante.pais_id pais_id, pais.iso pais_iso, pais_moneda.moneda moneda,
                      0                                       cant_tickets,
                      0                                       impuesto_premios,
                      0                                       impuesto_apuestas,
                      0                                       valor_apostado,
                      0                                       valor_ticket_prom,
                      0                                       proyeccion_premios,
                      0 valor_premios

         from pais_mandante
                  inner join pais on (pais.pais_id = pais_mandante.pais_id)
                  inner join pais_moneda on (pais.pais_id = pais_moneda.pais_id and pais.moneda = pais_mandante.moneda)

         UNION select a.mandante,CASE
                                 WHEN a.eliminado = 'S' AND a.fecha_crea != fecha_cierre AND a.fecha_cierre = '" . $fechaSoloDia . "'
                                     THEN a.fecha_cierre ELSE a.fecha_crea END fecha_crea,
             a.fecha_cierre,
             c.pais_id,
             c.iso                                                        pais_iso,
             b.moneda,
             count(a.ticket_id)                                           cant_tickets,
             sum(CASE
                     WHEN a.eliminado = 'S' AND a.fecha_crea != fecha_cierre AND a.fecha_cierre = '" . $fechaSoloDia . "'
                         THEN -a.impuesto
                     WHEN a.eliminado = 'S' AND a.fecha_crea = fecha_cierre
                         THEN 0
                     ELSE a.impuesto END
                 )                                                         impuesto_premios,
             0                                                        impuesto_apuestas,
             sum(CASE
                     WHEN a.eliminado = 'S' AND a.fecha_crea != fecha_cierre AND a.fecha_cierre = '" . $fechaSoloDia . "'
                         THEN -a.vlr_apuesta
                     WHEN a.eliminado = 'S' AND a.fecha_crea = fecha_cierre
                         THEN 0
                     ELSE a.vlr_apuesta END
                 )                                                        valor_apostado,
             sum(CASE
                     WHEN a.eliminado = 'S' AND a.fecha_crea != fecha_cierre AND a.fecha_cierre = '" . $fechaSoloDia . "'
                         THEN -a.vlr_apuesta
                     WHEN a.eliminado = 'S' AND a.fecha_crea = fecha_cierre
                         THEN 0
                     ELSE a.vlr_apuesta END) / sum(CASE
                                                       WHEN a.eliminado = 'S' AND a.fecha_crea != fecha_cierre AND
                                                            a.fecha_crea = '" . $fechaSoloDia . "'
                                                           THEN 1
                                                       WHEN a.eliminado = 'S' AND a.fecha_crea = fecha_cierre
                                                           THEN 0
                                                       ELSE 1 END)        valor_ticket_prom,
             sum((select exp(sum(log(x.logro))) logros from it_ticket_det x where x.ticket_id = a.ticket_id) *
                 a.vlr_apuesta)                                           proyeccion_premios,
             sum(case when a.premiado = 'S' then a.vlr_premio else 0 end) valor_premios
      from it_ticket_enc a
               inner join usuario b on (a.mandante = b.mandante and a.usuario_id = b.usuario_id)
               inner join usuario_perfil up on (up.usuario_id = b.usuario_id)
               inner join pais c on (b.pais_id = c.pais_id and c.pais_id != 1)
      where 1 = 1
        and ((a.eliminado = 'S' AND a.fecha_crea != fecha_cierre AND a.fecha_cierre = '" . $fechaSoloDia . "')) and a.wallet='" . $wallet . "'
        AND up.perfil_id = 'USUONLINE'
      group by a.mandante,a.fecha_crea, c.pais_id, b.moneda
      
         UNION select a.mandante,CASE
                                 WHEN a.eliminado = 'S' AND a.fecha_crea != fecha_cierre AND a.fecha_cierre = '" . $fechaSoloDia . "'
                                     THEN a.fecha_cierre ELSE a.fecha_crea END fecha_crea,
             a.fecha_cierre,
             c.pais_id,
             c.iso                                                        pais_iso,
             b.moneda,
             count(a.ticket_id)                                           cant_tickets,
             sum(CASE
                     WHEN a.eliminado = 'S' AND a.fecha_crea != fecha_cierre AND a.fecha_cierre = '" . $fechaSoloDia . "'
                         THEN -a.impuesto
                     WHEN a.eliminado = 'S' AND a.fecha_crea = fecha_cierre
                         THEN 0
                     ELSE a.impuesto END
                 )                                                           impuesto_premios,
             0                                                        impuesto_apuestas,
             sum(CASE
                     WHEN a.eliminado = 'S' AND a.fecha_crea != fecha_cierre AND a.fecha_cierre = '" . $fechaSoloDia . "'
                         THEN -a.vlr_apuesta
                     WHEN a.eliminado = 'S' AND a.fecha_crea = fecha_cierre
                         THEN 0
                     ELSE a.vlr_apuesta END
                 )                                                        valor_apostado,
             sum(CASE
                     WHEN a.eliminado = 'S' AND a.fecha_crea != fecha_cierre AND a.fecha_cierre = '" . $fechaSoloDia . "'
                         THEN -a.vlr_apuesta
                     WHEN a.eliminado = 'S' AND a.fecha_crea = fecha_cierre
                         THEN 0
                     ELSE a.vlr_apuesta END) / sum(CASE
                                                       WHEN a.eliminado = 'S' AND a.fecha_crea != fecha_cierre AND
                                                            a.fecha_crea = '" . $fechaSoloDia . "'
                                                           THEN 1
                                                       WHEN a.eliminado = 'S' AND a.fecha_crea = fecha_cierre
                                                           THEN 0
                                                       ELSE 1 END)        valor_ticket_prom,
             sum((select exp(sum(log(x.logro))) logros from it_ticket_det x where x.ticket_id = a.ticket_id) *
                 a.vlr_apuesta)                                           proyeccion_premios,
             sum(case when a.premiado = 'S' then a.vlr_premio else 0 end) valor_premios
      from it_ticket_enc a
               inner join usuario b on (a.mandante = b.mandante and a.usuario_id = b.usuario_id)
               inner join usuario_perfil up on (up.usuario_id = b.usuario_id)
               inner join pais c on (b.pais_id = c.pais_id and c.pais_id != 1)
      where 1 = 1
        and (
             ( a.fecha_crea = '" . $fechaSoloDia . "')) and a.wallet='" . $wallet . "'
        AND up.perfil_id = 'USUONLINE'
      group by a.mandante,a.fecha_crea, c.pais_id, b.moneda
      
      
                   UNION
                   SELECT usuario.mandante,
                          it_transaccion.fecha_crea,
                          it_transaccion.fecha_crea,
                          usuario.pais_id                         usuario,
                          pais.iso                                pais_iso,
                          usuario.moneda,
                          0                                       cant_tickets,
             0                                                        impuesto_premios,
             0                                                        impuesto_apuestas,
                          0                                       valor_apostado,
                          0                                       valor_ticket_prom,
                          0                                       proyeccion_premios,
                          SUM(CASE
                                  WHEN it_transaccion.tipo IN ('NEWCREDIT', 'WIN', 'REFUND', 'CASHOUT')
                                      then it_transaccion.valor
                                  else -it_transaccion.valor END) valor_premios

                   FROM it_transaccion
                            INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_transaccion.ticket_id)
                            INNER JOIN usuario ON (usuario.usuario_id = it_transaccion.usuario_id)
                            inner join pais on (pais.pais_id = usuario.pais_id)
                            inner join usuario_perfil  on (usuario_perfil.usuario_id = usuario.usuario_id)

                   WHERE it_transaccion.fecha_crea = '" . $fechaSoloDia . "' AND usuario_perfil.perfil_id = 'USUONLINE'
                     AND (it_transaccion.fecha_crea > it_ticket_enc.fecha_cierre and it_ticket_enc.fecha_cierre!='') and it_ticket_enc.wallet='" . $wallet . "'
                    group by usuario.mandante, it_transaccion.fecha_crea, usuario.pais_id, usuario.moneda
                                      ) a

      group by a.mandante, a.fecha_crea, a.pais_id, a.moneda

      order by a.mandante,a.fecha_crea, a.pais_id, a.moneda) x
         LEFT OUTER JOIN (SELECT usuario.moneda,usuario.mandante,usuario.pais_id,
                                 pl.estado,
                                 SUM(CASE when (pl.estado = 'L') then pl.valor when (pl.estado = 'E') then -pl.valor else 0 end)                           valor,
                                 DATE_FORMAT(pl.fecha_crea, '%Y-%m-%d') fecha_crea2
                          FROM bono_log pl
                                   INNER JOIN usuario ON (usuario.usuario_id = pl.usuario_id)
                          where (pl.estado = 'L' OR pl.estado = 'E') AND pl.tipo NOT IN ('TC','TL','SC','SCV','SL','TV','FC','DC', 'DL', 'DV', 'NC', 'NL', 'NV', 'JS', 'JL', 'JV', 'JD')
                          GROUP BY usuario.mandante,usuario.pais_id,usuario.moneda, fecha_crea2) pl2
                         ON (pl2.fecha_crea2 = DATE_FORMAT(x.fecha_crea, '%Y-%m-%d') AND x.moneda = pl2.moneda AND x.pais_id = pl2.pais_id AND x.mandante = pl2.mandante)
         LEFT OUTER JOIN (SELECT usuario.moneda,usuario.mandante,
                                 DATE_FORMAT(usuario.fecha_crea, '%Y-%m-%d') fecha_crea2,
                                 COUNT(*)                                     registros,
                                 pais.pais_id
                          FROM usuario
                                   INNER JOIN usuario_perfil ON (usuario.usuario_id = usuario_perfil.usuario_id AND
                                                                 usuario_perfil.perfil_id = 'USUONLINE')
                                   inner join pais on (pais.pais_id = usuario.pais_id)
                          GROUP BY usuario.mandante,usuario.pais_id, fecha_crea2) pl3
                         ON (pl3.fecha_crea2 = DATE_FORMAT(x.fecha_crea, '%Y-%m-%d') AND x.pais_id = pl3.pais_id AND x.mandante = pl3.mandante)
         LEFT OUTER JOIN (SELECT usuario.moneda,usuario.mandante,
                                 DATE_FORMAT(usuario.fecha_primerdeposito, '%Y-%m-%d') fecha_crea2,
                                 COUNT(*)                                               primerdepositos,usuario.pais_id
                          FROM usuario
                                   INNER JOIN usuario_perfil ON (usuario.usuario_id = usuario_perfil.usuario_id AND
                                                                 usuario_perfil.perfil_id = 'USUONLINE')
                          GROUP BY usuario.mandante,usuario.pais_id,usuario.moneda, fecha_crea2) pl4
                         ON (pl4.fecha_crea2 = DATE_FORMAT(x.fecha_crea, '%Y-%m-%d') AND x.moneda = pl4.moneda AND x.pais_id = pl4.pais_id AND x.mandante = pl4.mandante)
         LEFT OUTER JOIN (SELECT usuario.moneda,usuario.mandante,usuario.pais_id,
                                 pl.estado,
                                 SUM(CASE when (pl.estado = 'L') then pl.valor when (pl.estado = 'E') then -pl.valor else 0 end)                           valor,
                                 DATE_FORMAT(pl.fecha_crea, '%Y-%m-%d') fecha_crea2
                          FROM bono_log pl
                                   INNER JOIN usuario ON (usuario.usuario_id = pl.usuario_id)
                          where (pl.estado = 'L' OR pl.estado = 'E') AND pl.tipo = 'JD'
                          GROUP BY usuario.mandante,usuario.pais_id,usuario.moneda, fecha_crea2) pl5
                         ON (pl5.fecha_crea2 = DATE_FORMAT(x.fecha_crea, '%Y-%m-%d') AND x.moneda = pl5.moneda AND x.pais_id = pl5.pais_id AND x.mandante = pl5.mandante)
 WHERE x.mandante NOT IN (3,4,5,6,7,10,22,17,25)
ORDER BY x.fecha_crea ASC;
";

                $sqlInformeGerencialPVFechaCierre = "select x.*
from (select a.mandante,
             a.fecha_crea,
             a.fecha_cierre,
             a.pais_id,
             a.pais_iso                                                        pais_iso,
             a.moneda,
             sum(a.cant_tickets)                                           cant_tickets,
             sum(a.impuesto_premios)                                                        impuesto_premios,
             0                                                        impuesto_apuestas,
             sum(a.valor_apostado)                                                        valor_apostado,
             sum(a.valor_ticket_prom)        valor_ticket_prom,
             sum(a.proyeccion_premios)                                           proyeccion_premios,
             sum(a.valor_premios) valor_premios

             FROM (

    select pais_mandante.mandante mandante,  '" . $fechaSoloDia . "' fecha_crea,  '" . $fechaSoloDia . "' fecha_cierre, pais_mandante.pais_id pais_id, pais.iso pais_iso, pais_moneda.moneda moneda,
                      0                                       cant_tickets,
             0                                                        impuesto_premios,
             0                                                        impuesto_apuestas,
                      0                                       valor_apostado,
                      0                                       valor_ticket_prom,
                      0                                       proyeccion_premios,
                      0 valor_premios

         from pais_mandante
                  inner join pais on (pais.pais_id = pais_mandante.pais_id)
                  inner join pais_moneda on (pais.pais_id = pais_moneda.pais_id and pais.moneda = pais_mandante.moneda)

         UNION select a.mandante,a.fecha_crea,
             a.fecha_cierre,
             c.pais_id,
             c.iso                                                        pais_iso,
             b.moneda,
             CASE WHEN up.perfil_id IN ('PUNTOVENTA', 'CAJERO') THEN count(a.ticket_id)            ELSE 0 END                                 cant_tickets,
             CASE WHEN up.perfil_id IN ('PUNTOVENTA', 'CAJERO') THEN sum(CASE
                     WHEN a.eliminado = 'S' AND a.fecha_crea != fecha_cierre AND a.fecha_cierre = '" . $fechaSoloDia . "'
                         THEN -a.impuesto
                     WHEN (a.eliminado = 'S' AND a.fecha_crea = fecha_cierre)
                         THEN 0
                     ELSE a.impuesto END
                 )                          ELSE 0 END                                                          impuesto_premios,
             0                                                        impuesto_apuestas,
             CASE WHEN up.perfil_id IN ('PUNTOVENTA', 'CAJERO') THEN sum(CASE
                     WHEN a.eliminado = 'S' AND a.fecha_crea != fecha_cierre AND a.fecha_cierre = '" . $fechaSoloDia . "'
                         THEN -a.vlr_apuesta
                     WHEN (a.eliminado = 'S' AND a.fecha_crea = fecha_cierre)
                         THEN 0
                     ELSE a.vlr_apuesta END
                 )                          ELSE 0 END                                valor_apostado,
             CASE WHEN up.perfil_id IN ('PUNTOVENTA', 'CAJERO') THEN sum(CASE
                     WHEN a.eliminado = 'S' AND a.fecha_crea != fecha_cierre AND a.fecha_cierre = '" . $fechaSoloDia . "'
                         THEN -a.vlr_apuesta
                     WHEN a.eliminado = 'S' AND a.fecha_crea = fecha_cierre
                         THEN 0
                     ELSE a.vlr_apuesta END) / sum(CASE
                                                       WHEN a.eliminado = 'S' AND a.fecha_crea != fecha_cierre AND
                                                            a.fecha_cierre = '" . $fechaSoloDia . "'
                                                           THEN -1
                                                       WHEN a.eliminado = 'S' AND a.fecha_crea = fecha_cierre
                                                           THEN 0
                                                       ELSE 1 END)    ELSE 0 END      valor_ticket_prom,
             CASE WHEN up.perfil_id IN ('PUNTOVENTA', 'CAJERO') THEN sum((select exp(sum(log(x.logro))) logros from it_ticket_det x where x.ticket_id = a.ticket_id) *
                 a.vlr_apuesta)                           ELSE 0 END                  proyeccion_premios,
             CASE WHEN up.perfil_id IN ('PUNTOVENTA', 'CAJERO') THEN sum(case when a.premiado = 'S' then a.vlr_premio else 0 end)  ELSE 0 END  valor_premios
      from it_ticket_enc a
               inner join usuario b on (a.mandante = b.mandante and a.usuario_id = b.usuario_id)
               inner join usuario_perfil up on (up.usuario_id = b.usuario_id)
               inner join pais c on (b.pais_id = c.pais_id and c.pais_id != 1)
      where 1 = 1
        and ((a.eliminado = 'S' AND a.fecha_crea != fecha_cierre AND a.fecha_cierre = '" . $fechaSoloDia . "')) and a.wallet='" . $wallet . "'
      group by a.mandante,up.perfil_id,a.fecha_cierre, c.pais_id, b.moneda
      
         UNION select a.mandante,a.fecha_crea,
             a.fecha_cierre,
             c.pais_id,
             c.iso                                                        pais_iso,
             b.moneda,
             CASE WHEN up.perfil_id IN ('PUNTOVENTA', 'CAJERO') THEN count(a.ticket_id)            ELSE 0 END                                 cant_tickets,
              CASE WHEN up.perfil_id IN ('PUNTOVENTA', 'CAJERO') THEN sum(CASE
                     WHEN a.eliminado = 'S' AND a.fecha_crea != fecha_cierre AND a.fecha_cierre = '" . $fechaSoloDia . "'
                         THEN -a.impuesto
                     WHEN (a.eliminado = 'S' AND a.fecha_crea = fecha_cierre)
                         THEN 0
                     ELSE a.impuesto END
                 )                          ELSE 0 END                                                        impuesto_premios,
             0                                                        impuesto_apuestas,
             CASE WHEN up.perfil_id IN ('PUNTOVENTA', 'CAJERO') THEN sum(CASE
                     WHEN a.eliminado = 'S' AND a.fecha_crea != fecha_cierre AND a.fecha_cierre = '" . $fechaSoloDia . "'
                         THEN -a.vlr_apuesta
                     WHEN (a.eliminado = 'S' AND a.fecha_crea = fecha_cierre)
                         THEN 0
                     ELSE a.vlr_apuesta END
                 )                          ELSE 0 END                                valor_apostado,
             CASE WHEN up.perfil_id IN ('PUNTOVENTA', 'CAJERO') THEN sum(CASE
                     WHEN a.eliminado = 'S' AND a.fecha_crea != fecha_cierre AND a.fecha_cierre = '" . $fechaSoloDia . "'
                         THEN -a.vlr_apuesta
                     WHEN a.eliminado = 'S' AND a.fecha_crea = fecha_cierre
                         THEN 0
                     ELSE a.vlr_apuesta END) / sum(CASE
                                                       WHEN a.eliminado = 'S' AND a.fecha_crea != fecha_cierre AND
                                                            a.fecha_cierre = '" . $fechaSoloDia . "'
                                                           THEN -1
                                                       WHEN a.eliminado = 'S' AND a.fecha_crea = fecha_cierre
                                                           THEN 0
                                                       ELSE 1 END)    ELSE 0 END      valor_ticket_prom,
             CASE WHEN up.perfil_id IN ('PUNTOVENTA', 'CAJERO') THEN sum((select exp(sum(log(x.logro))) logros from it_ticket_det x where x.ticket_id = a.ticket_id) *
                 a.vlr_apuesta)                           ELSE 0 END                  proyeccion_premios,
             CASE WHEN up.perfil_id IN ('PUNTOVENTA', 'CAJERO') THEN sum(case when a.premiado = 'S' then a.vlr_premio else 0 end)  ELSE 0 END  valor_premios
      from it_ticket_enc a
               inner join usuario b on (a.mandante = b.mandante and a.usuario_id = b.usuario_id)
               inner join usuario_perfil up on (up.usuario_id = b.usuario_id)
               inner join pais c on (b.pais_id = c.pais_id and c.pais_id != 1)
      where 1 = 1
        and (
             (a.fecha_cierre = '" . $fechaSoloDia . "')) and a.wallet='" . $wallet . "'
      group by a.mandante,up.perfil_id,a.fecha_cierre, c.pais_id, b.moneda
      
      
                   UNION
                   SELECT usuario.mandante,
                          it_transaccion.fecha_crea,
                          it_transaccion.fecha_crea,
                          usuario.pais_id                         usuario,
                          pais.iso                                pais_iso,
                          usuario.moneda,
                          0                                       cant_tickets,
             0                                                        impuesto_premios,
             0                                                        impuesto_apuestas,
                          0                                       valor_apostado,
                          0                                       valor_ticket_prom,
                          0                                       proyeccion_premios,
                           CASE
                          WHEN usuario_perfil.perfil_id IN ('PUNTOVENTA', 'CAJERO') THEN SUM(CASE
                                  WHEN it_transaccion.tipo IN ('NEWCREDIT', 'WIN', 'REFUND', 'CASHOUT')
                                      then it_transaccion.valor
                                  else -it_transaccion.valor END) ELSE 0 END valor_premios

                   FROM it_transaccion
                            INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_transaccion.ticket_id)
                            INNER JOIN usuario ON (usuario.usuario_id = it_transaccion.usuario_id)
                            inner join pais on (pais.pais_id = usuario.pais_id)
                            inner join usuario_perfil  on (usuario_perfil.usuario_id = usuario.usuario_id)

                   WHERE it_transaccion.fecha_crea = '" . $fechaSoloDia . "' 
                     AND (it_transaccion.fecha_crea > it_ticket_enc.fecha_cierre and it_ticket_enc.fecha_cierre!='') and it_ticket_enc.wallet='" . $wallet . "'
                    group by usuario.mandante, it_transaccion.fecha_crea, usuario.pais_id, usuario.moneda
                                      ) a

      group by a.mandante, a.fecha_cierre, a.pais_id, a.moneda

                    
      order by a.mandante,a.fecha_cierre, a.pais_id, a.moneda) x
 WHERE x.mandante NOT IN (3,4,5,6,7,10,22,17,25)
ORDER BY x.fecha_cierre ASC;
";

                /* INFORME GERENCIAL USUARIO  por dia por fecha creacion*/
                $sqlInformeGerencialPVFechaCreacion = "select x.*
from (select a.mandante,
             a.fecha_crea,
             a.fecha_cierre,
             a.pais_id,
             a.pais_iso                                                        pais_iso,
             a.moneda,
             sum(a.cant_tickets)                                           cant_tickets,
             sum(a.impuesto_premios)                                                        impuesto_premios,
             0                                                        impuesto_apuestas,
             sum(a.valor_apostado)                                                        valor_apostado,
             sum(a.valor_ticket_prom)        valor_ticket_prom,
             sum(a.proyeccion_premios)                                           proyeccion_premios,
             sum(a.valor_premios) valor_premios

             FROM (

    select pais_mandante.mandante mandante,  '" . $fechaSoloDia . "' fecha_crea,  '" . $fechaSoloDia . "' fecha_cierre, pais_mandante.pais_id pais_id, pais.iso pais_iso, pais_moneda.moneda moneda,
                      0                                       cant_tickets,
             0                                                        impuesto_premios,
             0                                                        impuesto_apuestas,
                      0                                       valor_apostado,
                      0                                       valor_ticket_prom,
                      0                                       proyeccion_premios,
                      0 valor_premios

         from pais_mandante
                  inner join pais on (pais.pais_id = pais_mandante.pais_id)
                  inner join pais_moneda on (pais.pais_id = pais_moneda.pais_id and pais.moneda = pais_mandante.moneda)

         UNION select a.mandante,CASE
                                 WHEN a.eliminado = 'S' AND a.fecha_crea != fecha_cierre AND a.fecha_cierre = '" . $fechaSoloDia . "'
                                     THEN a.fecha_cierre ELSE a.fecha_crea END fecha_crea,
             a.fecha_cierre,
             c.pais_id,
             c.iso                                                        pais_iso,
             b.moneda,
             CASE WHEN up.perfil_id IN ('PUNTOVENTA', 'CAJERO') THEN  count(a.ticket_id)  ELSE 0 END                                           cant_tickets,
             CASE WHEN up.perfil_id IN ('PUNTOVENTA', 'CAJERO') THEN  sum(CASE
                     WHEN a.eliminado = 'S' AND a.fecha_crea != fecha_cierre AND a.fecha_cierre = '" . $fechaSoloDia . "'
                         THEN -a.impuesto
                     WHEN a.eliminado = 'S' AND a.fecha_crea = fecha_cierre
                         THEN 0
                     ELSE a.impuesto END
                 )      ELSE 0 END                                                       impuesto_premios,
             0                                                        impuesto_apuestas,
             CASE WHEN up.perfil_id IN ('PUNTOVENTA', 'CAJERO') THEN  sum(CASE
                     WHEN a.eliminado = 'S' AND a.fecha_crea != fecha_cierre AND a.fecha_cierre = '" . $fechaSoloDia . "'
                         THEN -a.vlr_apuesta
                     WHEN a.eliminado = 'S' AND a.fecha_crea = fecha_cierre
                         THEN 0
                     ELSE a.vlr_apuesta END
                 )      ELSE 0 END                                                    valor_apostado,
             CASE WHEN up.perfil_id IN ('PUNTOVENTA', 'CAJERO') THEN  sum(CASE
                     WHEN a.eliminado = 'S' AND a.fecha_crea != fecha_cierre AND a.fecha_cierre = '" . $fechaSoloDia . "'
                         THEN -a.vlr_apuesta
                     WHEN a.eliminado = 'S' AND a.fecha_crea = fecha_cierre
                         THEN 0
                     ELSE a.vlr_apuesta END) / sum(CASE
                                                       WHEN a.eliminado = 'S' AND a.fecha_crea != fecha_cierre AND
                                                            a.fecha_cierre = '" . $fechaSoloDia . "'
                                                           THEN 1
                                                       WHEN a.eliminado = 'S' AND a.fecha_crea = fecha_cierre
                                                           THEN 0
                                                       ELSE 1 END)    ELSE 0 END      valor_ticket_prom,
             CASE WHEN up.perfil_id IN ('PUNTOVENTA', 'CAJERO') THEN sum((select exp(sum(log(x.logro))) logros from it_ticket_det x where x.ticket_id = a.ticket_id) *
                 a.vlr_apuesta)                                     ELSE 0 END        proyeccion_premios,
             CASE WHEN up.perfil_id IN ('PUNTOVENTA', 'CAJERO') THEN sum(case when a.premiado = 'S' then a.vlr_premio else 0 end)  ELSE 0 END valor_premios
      from it_ticket_enc a
               inner join usuario b on (a.mandante = b.mandante and a.usuario_id = b.usuario_id)
               inner join usuario_perfil up on (up.usuario_id = b.usuario_id)
               inner join pais c on (b.pais_id = c.pais_id and c.pais_id != 1)
      where 1 = 1
        and ((a.eliminado = 'S' AND a.fecha_crea != fecha_cierre AND a.fecha_cierre = '" . $fechaSoloDia . "')) and a.wallet='" . $wallet . "'
      group by a.mandante,up.perfil_id,a.fecha_crea, c.pais_id, b.moneda
      
         UNION select a.mandante,CASE
                                 WHEN a.eliminado = 'S' AND a.fecha_crea != fecha_cierre AND a.fecha_cierre = '" . $fechaSoloDia . "'
                                     THEN a.fecha_cierre ELSE a.fecha_crea END fecha_crea,
             a.fecha_cierre,
             c.pais_id,
             c.iso                                                        pais_iso,
             b.moneda,
             CASE WHEN up.perfil_id IN ('PUNTOVENTA', 'CAJERO') THEN  count(a.ticket_id)  ELSE 0 END                                           cant_tickets,
             CASE WHEN up.perfil_id IN ('PUNTOVENTA', 'CAJERO') THEN  sum(CASE
                     WHEN a.eliminado = 'S' AND a.fecha_crea != fecha_cierre AND a.fecha_cierre = '" . $fechaSoloDia . "'
                         THEN -a.impuesto
                     WHEN a.eliminado = 'S' AND a.fecha_crea = fecha_cierre
                         THEN 0
                     ELSE a.impuesto END
                 )      ELSE 0 END                                                        impuesto_premios,
             0                                                        impuesto_apuestas,
             CASE WHEN up.perfil_id IN ('PUNTOVENTA', 'CAJERO') THEN  sum(CASE
                     WHEN a.eliminado = 'S' AND a.fecha_crea != fecha_cierre AND a.fecha_cierre = '" . $fechaSoloDia . "'
                         THEN -a.vlr_apuesta
                     WHEN a.eliminado = 'S' AND a.fecha_crea = fecha_cierre
                         THEN 0
                     ELSE a.vlr_apuesta END
                 )      ELSE 0 END                                                    valor_apostado,
             CASE WHEN up.perfil_id IN ('PUNTOVENTA', 'CAJERO') THEN  sum(CASE
                     WHEN a.eliminado = 'S' AND a.fecha_crea != fecha_cierre AND a.fecha_cierre = '" . $fechaSoloDia . "'
                         THEN -a.vlr_apuesta
                     WHEN a.eliminado = 'S' AND a.fecha_crea = fecha_cierre
                         THEN 0
                     ELSE a.vlr_apuesta END) / sum(CASE
                                                       WHEN a.eliminado = 'S' AND a.fecha_crea != fecha_cierre AND
                                                            a.fecha_cierre = '" . $fechaSoloDia . "'
                                                           THEN 1
                                                       WHEN a.eliminado = 'S' AND a.fecha_crea = fecha_cierre
                                                           THEN 0
                                                       ELSE 1 END)    ELSE 0 END      valor_ticket_prom,
             CASE WHEN up.perfil_id IN ('PUNTOVENTA', 'CAJERO') THEN sum((select exp(sum(log(x.logro))) logros from it_ticket_det x where x.ticket_id = a.ticket_id) *
                 a.vlr_apuesta)                                     ELSE 0 END        proyeccion_premios,
             CASE WHEN up.perfil_id IN ('PUNTOVENTA', 'CAJERO') THEN sum(case when a.premiado = 'S' then a.vlr_premio else 0 end)  ELSE 0 END valor_premios
      from it_ticket_enc a
               inner join usuario b on (a.mandante = b.mandante and a.usuario_id = b.usuario_id)
               inner join usuario_perfil up on (up.usuario_id = b.usuario_id)
               inner join pais c on (b.pais_id = c.pais_id and c.pais_id != 1)
      where 1 = 1
        and (
             (a.eliminado = 'N' AND a.fecha_crea = '" . $fechaSoloDia . "')) and a.wallet='" . $wallet . "'
      group by a.mandante,up.perfil_id,a.fecha_crea, c.pais_id, b.moneda
      
      
                   UNION
                   SELECT usuario.mandante,
                          it_transaccion.fecha_crea,
                          it_transaccion.fecha_crea,
                          usuario.pais_id                         usuario,
                          pais.iso                                pais_iso,
                          usuario.moneda,
                          0                                       cant_tickets,
             0                                                        impuesto_premios,
             0                                                        impuesto_apuestas,
                          0                                       valor_apostado,
                          0                                       valor_ticket_prom,
                          0                                       proyeccion_premios,
                           CASE
                          WHEN usuario_perfil.perfil_id IN ('PUNTOVENTA', 'CAJERO') THEN SUM(CASE
                                  WHEN it_transaccion.tipo IN ('NEWCREDIT', 'WIN', 'REFUND', 'CASHOUT')
                                      then it_transaccion.valor
                                  else -it_transaccion.valor END) ELSE 0 END valor_premios

                   FROM it_transaccion
                            INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_transaccion.ticket_id)
                            INNER JOIN usuario ON (usuario.usuario_id = it_transaccion.usuario_id)
                            inner join pais on (pais.pais_id = usuario.pais_id)
                            inner join usuario_perfil  on (usuario_perfil.usuario_id = usuario.usuario_id)

                   WHERE it_transaccion.fecha_crea = '" . $fechaSoloDia . "'
                     AND (it_transaccion.fecha_crea > it_ticket_enc.fecha_cierre and it_ticket_enc.fecha_cierre!='') and it_ticket_enc.wallet='" . $wallet . "'
                    group by usuario.mandante, it_transaccion.fecha_crea, usuario.pais_id, usuario.moneda
                                      ) a

      group by a.mandante, a.fecha_crea, a.pais_id, a.moneda

      order by a.mandante,a.fecha_crea, a.pais_id, a.moneda) x
 WHERE x.mandante NOT IN (3,4,5,6,7,10,22,17,25)
ORDER BY x.fecha_crea ASC;
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
                    $sql = "INSERT INTO bodega_informe_gerencial (pais_id, mandante, fecha, cantidad, saldo_apuestas, saldo_premios, saldo_premios_pendientes, saldo_bono, tipo_usuario, tipo_fecha, primeros_depositos,usuarios_registrados,billetera_id,impuesto_apuestas,impuesto_premios, premio_jackpot) 
                    VALUES (" . $datanum->{'x.pais_id'} . ",
                    " . $datanum->{'x.mandante'} . ",
                    '" . $datanum->{'x.fecha_cierre'} . "',
                    '" . ($datanum->{'x.cant_tickets'} == '' ? 0 : $datanum->{'x.cant_tickets'}) . "',
                    '" . ($datanum->{'x.valor_apostado'} == '' ? 0 : $datanum->{'x.valor_apostado'}) . "',
                    '" . ($datanum->{'x.valor_premios'} == '' ? 0 : $datanum->{'x.valor_premios'}) . "',
                    '" . ($datanum->{'x.proyeccion_premios'} == '' ? 0 : $datanum->{'x.proyeccion_premios'}) . "',
                    '" . ($datanum->{'pl2.bonos'} == '' ? 0 : $datanum->{'pl2.bonos'}) . "',
                    1,
                    2,
                    '" . ($datanum->{'pl4.primerdepositos'} == '' ? 0 : $datanum->{'pl4.primerdepositos'}) . "',
                    '" . ($datanum->{'pl3.registros'} == '' ? 0 : $datanum->{'pl3.registros'}) . "',
                                        " . $wallet . ",
                    '" . ($datanum->{'x.impuesto_apuestas'} == '' ? 0 : $datanum->{'x.impuesto_apuestas'}) . "',
                    '" . ($datanum->{'x.impuesto_premios'} == '' ? 0 : $datanum->{'x.impuesto_premios'}) . "',
                    '" . ($datanum->{'pl5.jackpots'} == '' ? 0 : $datanum->{'pl5.jackpots'}) . "'
                    

                    )
                    
       ;

       ";
                    print $sql;

                    $BonoInterno->execQuery($transaccion, $sql);

                }

                $dataSaldoInicial = $BonoInterno->execQuery($transaccion, $sqlInformeGerencialUsuarioFechaCreacion);


                foreach ($dataSaldoInicial as $datanum) {


                    $sql = "INSERT INTO bodega_informe_gerencial (pais_id, mandante, fecha, cantidad, saldo_apuestas, saldo_premios, saldo_premios_pendientes, saldo_bono, tipo_usuario, tipo_fecha, primeros_depositos,usuarios_registrados,billetera_id,impuesto_apuestas,impuesto_premios, premio_jackpot) 
                    VALUES (" . $datanum->{'x.pais_id'} . ",
                    " . $datanum->{'x.mandante'} . ",
                    '" . $datanum->{'x.fecha_cierre'} . "',
                    '" . ($datanum->{'x.cant_tickets'} == '' ? 0 : $datanum->{'x.cant_tickets'}) . "',
                    '" . ($datanum->{'x.valor_apostado'} == '' ? 0 : $datanum->{'x.valor_apostado'}) . "',
                    '" . ($datanum->{'x.valor_premios'} == '' ? 0 : $datanum->{'x.valor_premios'}) . "',
                    '" . ($datanum->{'x.proyeccion_premios'} == '' ? 0 : $datanum->{'x.proyeccion_premios'}) . "',
                    '" . ($datanum->{'pl2.bonos'} == '' ? 0 : $datanum->{'pl2.bonos'}) . "',
                    1,
                    1,
                    '" . ($datanum->{'pl4.primerdepositos'} == '' ? 0 : $datanum->{'pl4.primerdepositos'}) . "',
                    '" . ($datanum->{'pl3.registros'} == '' ? 0 : $datanum->{'pl3.registros'}) . "',
                                        " . $wallet . ",
                    '" . ($datanum->{'x.impuesto_apuestas'} == '' ? 0 : $datanum->{'x.impuesto_apuestas'}) . "',
                    '" . ($datanum->{'x.impuesto_premios'} == '' ? 0 : $datanum->{'x.impuesto_premios'}) . "',
                    '" . ($datanum->{'pl5.jackpots'} == '' ? 0 : $datanum->{'pl5.jackpots'}) . "'
                    
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

                    $sql = "INSERT INTO bodega_informe_gerencial (pais_id, mandante, fecha, cantidad, saldo_apuestas, saldo_premios, saldo_premios_pendientes, saldo_bono, tipo_usuario, tipo_fecha, primeros_depositos,usuarios_registrados,billetera_id,impuesto_apuestas,impuesto_premios) 
                    VALUES (" . $datanum->{'x.pais_id'} . ",
                    " . $datanum->{'x.mandante'} . ",
                    '" . $datanum->{'x.fecha_cierre'} . "',
                    '" . ($datanum->{'x.cant_tickets'} == '' ? 0 : $datanum->{'x.cant_tickets'}) . "',
                    '" . ($datanum->{'x.valor_apostado'} == '' ? 0 : $datanum->{'x.valor_apostado'}) . "',
                    '" . ($datanum->{'x.valor_premios'} == '' ? 0 : $datanum->{'x.valor_premios'}) . "',
                    '" . ($datanum->{'x.proyeccion_premios'} == '' ? 0 : $datanum->{'x.proyeccion_premios'}) . "',
                    '" . 0 . "',
                    2,
                    2,
                    '" . ($datanum->{'pl4.primerdepositos'} == '' ? 0 : $datanum->{'pl4.primerdepositos'}) . "',
                    '" . ($datanum->{'pl3.registros'} == '' ? 0 : $datanum->{'pl3.registros'}) . "',
                                        " . $wallet . ",
                    '" . ($datanum->{'x.impuesto_apuestas'} == '' ? 0 : $datanum->{'x.impuesto_apuestas'}) . "',
                    '" . ($datanum->{'x.impuesto_premios'} == '' ? 0 : $datanum->{'x.impuesto_premios'}) . "'

                    )
                    
                 ;

       ";
                    print_r($sql);

                    $BonoInterno->execQuery($transaccion, $sql);

                }

                $dataSaldoInicial = $BonoInterno->execQuery($transaccion, $sqlInformeGerencialPVFechaCreacion);


                foreach ($dataSaldoInicial as $datanum) {

                    print_r($datanum);

                    $sql = "INSERT INTO bodega_informe_gerencial (pais_id, mandante, fecha, cantidad, saldo_apuestas, saldo_premios, saldo_premios_pendientes, saldo_bono, tipo_usuario, tipo_fecha, primeros_depositos,usuarios_registrados,billetera_id,impuesto_apuestas,impuesto_premios) 
                    VALUES (" . $datanum->{'x.pais_id'} . ",
                    " . $datanum->{'x.mandante'} . ",
                    '" . $datanum->{'x.fecha_cierre'} . "',
                    '" . ($datanum->{'x.cant_tickets'} == '' ? 0 : $datanum->{'x.cant_tickets'}) . "',
                    '" . ($datanum->{'x.valor_apostado'} == '' ? 0 : $datanum->{'x.valor_apostado'}) . "',
                    '" . ($datanum->{'x.valor_premios'} == '' ? 0 : $datanum->{'x.valor_premios'}) . "',
                    '" . ($datanum->{'x.proyeccion_premios'} == '' ? 0 : $datanum->{'x.proyeccion_premios'}) . "',
                    '" . 0 . "',
                    2,
                    1,
                    '" . ($datanum->{'pl4.primerdepositos'} == '' ? 0 : $datanum->{'pl4.primerdepositos'}) . "',
                    '" . ($datanum->{'pl3.registros'} == '' ? 0 : $datanum->{'pl3.registros'}) . "',
                                        " . $wallet . ",
                    '" . ($datanum->{'x.impuesto_apuestas'} == '' ? 0 : $datanum->{'x.impuesto_apuestas'}) . "',
                    '" . ($datanum->{'x.impuesto_premios'} == '' ? 0 : $datanum->{'x.impuesto_premios'}) . "'
                    
                    )
                    
         ;

       ";

                    $BonoInterno->execQuery($transaccion, $sql);

                }
                $procesoInterno = $BonoInterno->execQuery($transaccion, "INSERT INTO procesos_internos ( tipo, fecha_crea, usucrea_id) VALUES ('ResumenesBodega" . $wallet . "','" . date("Y-m-d 00:00:00") . "','0');");

                $transaccion->commit();

                $log = "\r\n" . "-------------------------" . "\r\n";
                $log = $log . "Terminacion: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
                fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

                $message = "*CRON: (Fin) * " . " ResumenesBodega - Fecha: " . date("Y-m-d H:i:s");

                exec("php -f " . __DIR__ . "../src/imports/Slack/message.php '" . $message . "' '#procesos-cron-resumenes' > /dev/null & ");


            } catch (Exception $e) {
                print_r($e);
                $log = "\r\n" . "-------------------------" . "\r\n";
                $log = $log . "ERROR: " . $e->getMessage() . " - " . date('Y-m-d H:i:s');
                fwriteCustom('log_' . date("Y-m-d") . '.log', $log);


                $message = "*CRON: (ERROR) * " . " ResumenesBodega - Fecha: " . date("Y-m-d H:i:s");

                exec("php -f " . __DIR__ . "../src/imports/Slack/message.php '" . $message . "' '#procesos-cron-resumenes' > /dev/null & ");

            }


        }

    }
}