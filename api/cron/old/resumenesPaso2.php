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
use Backend\dto\JackpotInterno;



require(__DIR__.'/../vendor/autoload.php');
///home/devadmin/api/api/
$hour = date('H');
if(intval($hour)>9){
    //exit();
}
$_ENV["NEEDINSOLATIONLEVEL"] ='1';

ini_set('memory_limit', '-1');

$message = "*CRON: (Inicio) * " . " Resumenes - Fecha: " . date("Y-m-d H:i:s");

exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#procesos-cron-resumenes' > /dev/null & ");


$ResumenesCron = new ResumenesCron();

//$ResumenesCron->generateResumenes();

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

try {
    $globalJackpotTypesInBonoLog = JackpotInterno::getJackpotTypesForBonoLogString();
    $sportbookJackpotTypesInBonoLog = JackpotInterno::getJackpotTypesForBonoLogString('DEPORTIVAS');
    $casinoJackpotTypesInBonoLog = JackpotInterno::getJackpotTypesForBonoLogString('CASINO');
//BETWEEN '".$fecha1."' AND '".$fecha2."'

    $strEliminado = "DELETE FROM usuario_deporte_resumen WHERE fecha_crea >= '" . $fecha1 . "';
DELETE FROM usuario_casino_resumen WHERE fecha_crea >= '" . $fecha1 . "';
DELETE FROM usucasino_detalle_resumen WHERE fecha_crea >= '" . $fecha1 . "';
DELETE FROM usuario_retiro_resumen WHERE fecha_crea >= '" . $fecha1 . "';
DELETE FROM usuario_recarga_resumen WHERE fecha_crea >= '" . $fecha1 . "';
DELETE FROM usuario_bono_resumen WHERE fecha_crea >= '" . $fecha1 . "';
DELETE FROM usuario_ajustes_resumen WHERE fecha_crea >= '" . $fecha1 . "';
DELETE FROM usuario_saldo WHERE fecha_crea >= '" . $fecha1 . "';
";

    /* Recargas de USUARIOS por dia*/
    $sqlRecargaUsuarioDia = "
  SELECT
    usuario_recarga.usuario_id usuarioId,
    SUM(usuario_recarga.valor) valor,
    date_format(usuario_recarga.fecha_crea, '%Y-%m-%d') fecha_crea,
    0 usucrea_id,
    0 usumodif_id,
    'A' estado,
    COUNT(*) cantidad,
    0 mediopago_id,
    usuario_recarga.puntoventa_id puntoventa_id
  FROM usuario_recarga
  inner join registro on usuario_recarga.usuario_id = registro.usuario_id
    WHERE date_format(usuario_recarga.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "' and usuario_recarga.puntoventa_id != 0 /*and estado='A'*/ AND usuario_recarga.mandante NOT IN (3,4,5,6,7,10,22,17,25)
  GROUP BY date_format(usuario_recarga.fecha_crea, '%Y-%m-%d'),usuario_recarga.usuario_id,usuario_recarga.puntoventa_id
/*  UNION
    SELECT
    usuario_recarga.usuario_id usuarioId,
    -SUM(usuario_recarga.valor) valor,
    date_format(usuario_recarga.fecha_crea, '%Y-%m-%d') fecha_crea,
    0 usucrea_id,
    0 usumodif_id,
    'I' estado,
    COUNT(*) cantidad,
    0 mediopago_id,
    puntoventa_id puntoventa_id
  FROM usuario_recarga
    WHERE date_format(usuario_recarga.fecha_elimina, '%Y-%m-%d') = '" . $fechaSoloDia . "' and puntoventa_id != 0 AND estado='I'
  GROUP BY date_format(usuario_recarga.fecha_elimina, '%Y-%m-%d'),usuario_recarga.usuario_id,usuario_recarga.puntoventa_id;*/
  ";

    /* Recargas hechos por PUNTO DE VENTA por dia*/


    $sqlRecargaPuntoVentaDia = "
SELECT
    usuario_recarga.puntoventa_id usuarioId,
    SUM(usuario_recarga.valor) valor,
    date_format(usuario_recarga.fecha_crea, '%Y-%m-%d') fecha_crea,
    0 usucrea_id,
    0 usumodif_id,
    'A' estado,
    COUNT(*) cantidad
  FROM usuario_recarga
    WHERE date_format(usuario_recarga.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND usuario_recarga.puntoventa_id != 0  AND usuario_recarga.mandante NOT IN (3,4,5,6,7,10,22,17,25)
  GROUP BY date_format(usuario_recarga.fecha_crea, '%Y-%m-%d'), usuario_recarga.puntoventa_id
  UNION
  
  SELECT
    usuario_recarga.puntoventa_id usuarioId,
    -SUM(usuario_recarga.valor) valor,
    date_format(usuario_recarga.fecha_elimina, '%Y-%m-%d') fecha_crea,
    0 usucrea_id,
    0 usumodif_id,
    'I' estado,
    COUNT(*) cantidad
  FROM usuario_recarga
    WHERE date_format(usuario_recarga.fecha_elimina, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND usuario_recarga.puntoventa_id != 0 AND estado='I'  AND usuario_recarga.mandante NOT IN (3,4,5,6,7,10,22,17,25)
  GROUP BY date_format(usuario_recarga.fecha_elimina, '%Y-%m-%d'), usuario_recarga.puntoventa_id";


    /* Recargas hechos por PASARELAS por dia*/

    $sqlRecargaPasarelaDia = "
SELECT
    usuario_recarga.usuario_id usuarioId,
    SUM(usuario_recarga.valor) valor,
    date_format(usuario_recarga.fecha_crea, '%Y-%m-%d') fecha_crea,
    0 usucrea_id,
    0 usumodif_id,
    'A' estado,
    COUNT(*) cantidad,
    transaccion_producto.producto_id mediopago_id

  FROM usuario_recarga
    inner join registro on usuario_recarga.usuario_id = registro.usuario_id

    INNER JOIN transaccion_producto ON (transaccion_producto.final_id = usuario_recarga.recarga_id)
    WHERE date_format(usuario_recarga.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND  usuario_recarga.puntoventa_id = 0  AND usuario_recarga.mandante NOT IN (3,4,5,6,7,10,22,17,25)
  GROUP BY date_format(usuario_recarga.fecha_crea, '%Y-%m-%d'),usuario_recarga.usuario_id, transaccion_producto.producto_id
  ORDER BY date_format(usuario_recarga.fecha_crea, '%Y-%m-%d'),usuario_recarga.usuario_id, transaccion_producto.producto_id;";

    /* Retiros pagados a USUARIOS por dia*/

    $sqlRetiroUsuarioDiaPagado = "
SELECT
    cuenta_cobro.usuario_id usuarioId,
    SUM(cuenta_cobro.valor) valor,
    date_format(cuenta_cobro.fecha_pago, '%Y-%m-%d') fecha_crea,
    0 usucrea_id,
    0 usumodif_id,
    'I' estado,
    COUNT(*) cantidad,
    cuenta_cobro.puntoventa_id puntoventa_id, cuenta_cobro.version
  FROM cuenta_cobro
    WHERE cuenta_cobro.estado='I' AND date_format(cuenta_cobro.fecha_pago, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND cuenta_cobro.puntoventa_id != 0 AND cuenta_cobro.mandante NOT IN (3,4,5,6,7,10,22,17,25) AND cuenta_cobro.version in (1,2) 
  GROUP BY date_format(cuenta_cobro.fecha_pago, '%Y-%m-%d'), cuenta_cobro.usuario_id,cuenta_cobro.puntoventa_id, cuenta_cobro.version
  ORDER BY date_format(cuenta_cobro.fecha_pago, '%Y-%m-%d'), cuenta_cobro.usuario_id,cuenta_cobro.puntoventa_id;";

    /* Retiros pagados a USUARIOS por dia pagados CUENTA BANCARIA fisicamente */

    $sqlRetiroUsuarioDiaPagadoCuentaBancariaFisicamente = "
SELECT
    cuenta_cobro.usuario_id usuarioId,
    SUM(cuenta_cobro.valor) valor,
    date_format(cuenta_cobro.fecha_pago, '%Y-%m-%d') fecha_crea,
    0 usucrea_id,
    0 usumodif_id,
    'I' estado,
    COUNT(*) cantidad,
    0 puntoventa_id, cuenta_cobro.version
  FROM cuenta_cobro
    WHERE cuenta_cobro.estado='I' AND date_format(cuenta_cobro.fecha_pago, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND cuenta_cobro.puntoventa_id = 0  AND cuenta_cobro.transproducto_id = 0 AND cuenta_cobro.mandante NOT IN (3,4,5,6,7,10,22,17,25) AND cuenta_cobro.version in (1,2)
  GROUP BY date_format(cuenta_cobro.fecha_pago, '%Y-%m-%d'), cuenta_cobro.usuario_id,cuenta_cobro.puntoventa_id, cuenta_cobro.version
  ORDER BY date_format(cuenta_cobro.fecha_pago, '%Y-%m-%d'), cuenta_cobro.usuario_id,cuenta_cobro.puntoventa_id;";


    /* Retiros PENDIENTES a USUARIOS por dia*/

    $sqlRetiroUsuarioDiaPendiente = "
SELECT
    cuenta_cobro.usuario_id usuarioId,
    SUM(cuenta_cobro.valor) valor,
    date_format(cuenta_cobro.fecha_crea, '%Y-%m-%d') fecha_crea,
    0 usucrea_id,
    0 usumodif_id,
    'A' estado,
    COUNT(*) cantidad, cuenta_cobro.version
  FROM cuenta_cobro

    WHERE date_format(cuenta_cobro.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "'  AND cuenta_cobro.mandante NOT IN (3,4,5,6,7,10,22,17,25)

  GROUP BY date_format(cuenta_cobro.fecha_crea, '%Y-%m-%d'), cuenta_cobro.usuario_id, cuenta_cobro.version
  ORDER BY date_format(cuenta_cobro.fecha_crea, '%Y-%m-%d'), cuenta_cobro.usuario_id;";

    /* Retiros ELIMINADAS a USUARIOS por dia*/

    $sqlRetiroUsuarioDiaEliminadas = "
SELECT
    cuenta_cobro.usuario_id usuarioId,
    SUM(cuenta_cobro.valor) valor,
    date_format(cuenta_cobro.fecha_accion, '%Y-%m-%d') fecha_crea,
    0 usucrea_id,
    0 usumodif_id,
    'E' estado,
    COUNT(*) cantidad, cuenta_cobro.version
  FROM cuenta_cobro

    WHERE date_format(cuenta_cobro.fecha_accion, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND cuenta_cobro.estado ='E' AND cuenta_cobro.mandante NOT IN (3,4,5,6,7,10,22,17,25)

  GROUP BY date_format(cuenta_cobro.fecha_accion, '%Y-%m-%d'), cuenta_cobro.usuario_id, cuenta_cobro.version
  ORDER BY date_format(cuenta_cobro.fecha_accion, '%Y-%m-%d');";


    $sqlRetiroUsuarioDiaDevueltas = "
SELECT
    cuenta_cobro.usuario_id usuarioId,
    SUM(cuenta_cobro.valor) valor,
    date_format(cuenta_cobro.fecha_accion, '%Y-%m-%d') fecha_crea,
    0 usucrea_id,
    0 usumodif_id,
    'D' estado,
    COUNT(*) cantidad, cuenta_cobro.version
  FROM cuenta_cobro

    WHERE date_format(cuenta_cobro.fecha_accion, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND cuenta_cobro.estado ='D' AND cuenta_cobro.mandante NOT IN (3,4,5,6,7,10,22,17,25)

  GROUP BY date_format(cuenta_cobro.fecha_accion, '%Y-%m-%d'), cuenta_cobro.usuario_id, cuenta_cobro.version
  ORDER BY date_format(cuenta_cobro.fecha_accion, '%Y-%m-%d'), cuenta_cobro.usuario_id;";

    /* Retiros RECHAZADAS a USUARIOS por dia*/

    $sqlRetiroUsuarioDiaRechazadas = "
SELECT
    cuenta_cobro.usuario_id usuarioId,
    SUM(cuenta_cobro.valor) valor,
    date_format(cuenta_cobro.fecha_accion, '%Y-%m-%d') fecha_crea,
    0 usucrea_id,
    0 usumodif_id,
    'R' estado,
    COUNT(*) cantidad, cuenta_cobro.version
  FROM cuenta_cobro

    WHERE date_format(cuenta_cobro.fecha_accion, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND cuenta_cobro.estado ='R' AND cuenta_cobro.mandante NOT IN (3,4,5,6,7,10,22,17,25)

  GROUP BY date_format(cuenta_cobro.fecha_accion, '%Y-%m-%d'), cuenta_cobro.usuario_id, cuenta_cobro.version
  ORDER BY date_format(cuenta_cobro.fecha_accion, '%Y-%m-%d'), cuenta_cobro.usuario_id;";

    /* Retiros PENDIENTES a USUARIOS HOY*/

    $sqlRetiroUsuarioPendienteHoy = "
SELECT
    cuenta_cobro.usuario_id usuarioId,
    SUM(cuenta_cobro.valor) valor,
    date_format('" . $fechaSoloDia . "', '%Y-%m-%d') fecha_crea,
    0 usucrea_id,
    0 usumodif_id,
    'P' estado,
    COUNT(*) cantidad, cuenta_cobro.version
  FROM cuenta_cobro

    WHERE cuenta_cobro.estado IN ('A','M','S','P') AND cuenta_cobro.mandante NOT IN (3,4,5,6,7,10,22,17,25)

  GROUP BY  cuenta_cobro.usuario_id, cuenta_cobro.version
  ORDER BY  cuenta_cobro.usuario_id;";


    /* Retiros  pagados por PUNTOS DE VENTA por dia*/

    $sqlRetiroPuntoVentaDiaPagado = "
 SELECT
    cuenta_cobro.puntoventa_id usuarioId,
    SUM(cuenta_cobro.valor) valor,
    date_format(cuenta_cobro.fecha_pago, '%Y-%m-%d') fecha_crea,
    0 usucrea_id,
    0 usumodif_id,
    'I' estado,
    COUNT(*) cantidad, cuenta_cobro.version
  FROM cuenta_cobro


    WHERE cuenta_cobro.estado='I' AND date_format(cuenta_cobro.fecha_pago, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND cuenta_cobro.puntoventa_id != 0 AND cuenta_cobro.mandante NOT IN (3,4,5,6,7,10,22,17,25)  AND cuenta_cobro.version in (1,2)

  GROUP BY date_format(cuenta_cobro.fecha_pago, '%Y-%m-%d'), cuenta_cobro.puntoventa_id, cuenta_cobro.version
  ORDER BY date_format(cuenta_cobro.fecha_pago, '%Y-%m-%d'), cuenta_cobro.puntoventa_id;";

    /* Retiros  pagados por Productos por dia*/

    $sqlRetiroProductosPagado = "
SELECT
    cuenta_cobro.usuario_id usuarioId,
    SUM(cuenta_cobro.valor) valor,
    date_format(cuenta_cobro.fecha_pago, '%Y-%m-%d') fecha_crea,
    0 usucrea_id,
    0 usumodif_id,
    'I' estado,
    COUNT(*) cantidad,
    transaccion_producto.producto_id producto_id, cuenta_cobro.version
  FROM cuenta_cobro
  
  INNER JOIN transaccion_producto ON (transaccion_producto.transproducto_id = cuenta_cobro.transproducto_id)


    WHERE   cuenta_cobro.estado = 'I' AND date_format(cuenta_cobro.fecha_pago, '%Y-%m-%d') = '" . $fechaSoloDia . "'  AND cuenta_cobro.mandante NOT IN (3,4,5,6,7,10,22,17,25)

  GROUP BY date_format(cuenta_cobro.fecha_pago, '%Y-%m-%d'),cuenta_cobro.usuario_id, transaccion_producto.producto_id, cuenta_cobro.version
  ORDER BY date_format(cuenta_cobro.fecha_pago, '%Y-%m-%d'),cuenta_cobro.usuario_id, transaccion_producto.producto_id;";

    /* Retiros  pagados por PUNTOS DE VENTA por dia*/
    /*
    $sqlApuestasDeportivasUsuarioDia = "INSERT INTO usuario_deporte_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, tipo, cantidad)
        SELECT
      it_ticket_enc.usuario_id,
      SUM(it_ticket_enc.vlr_apuesta),
      date_format(it_ticket_enc.fecha_crea, '%Y-%m-%d'),
          0,
          0,
          'A',
          '1',
      COUNT(*) cantidad
    FROM it_ticket_enc
      INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = it_ticket_enc.usuario_id)

        WHERE date_format(it_ticket_enc.fecha_crea, '%Y-%m-%d') = '".$fechaSoloDia."' AND usuario_perfil.perfil_id = \"USUONLINE\" AND it_ticket_enc.eliminado='N'
    GROUP BY date_format(it_ticket_enc.fecha_crea, '%Y-%m-%d'), it_ticket_enc.usuario_id
    ORDER BY date_format(it_ticket_enc.fecha_crea, '%Y-%m-%d'),it_ticket_enc.usuario_id;";

    $sqlPremiosDeportivasUsuarioDia = "INSERT INTO usuario_deporte_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, tipo, cantidad)
    SELECT
      it_ticket_enc.usuario_id,
      SUM(it_ticket_enc.vlr_premio),
      date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d'),
      0,
      0,
      'C',
      '2',
      COUNT(*) cantidad
    FROM it_ticket_enc
      INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = it_ticket_enc.usuario_id)

        WHERE date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d') = '".$fechaSoloDia."' AND usuario_perfil.perfil_id = \"USUONLINE\" AND it_ticket_enc.premiado='S' AND it_ticket_enc.eliminado='N'

    GROUP BY date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d'), it_ticket_enc.usuario_id
    ORDER BY date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d'),  it_ticket_enc.usuario_id;";*/

    $sqlApuestasDeportivasUsuarioDia = "
SELECT
  it_transaccion.usuario_id usuarioId, 
  SUM(it_transaccion.valor) valor,
  date_format(it_transaccion.fecha_crea, '%Y-%m-%d') fecha_crea,
      0 usucrea_id,
      it_ticket_enc.wallet usumodif_id,
      'A' estado,
      tipo tipo,
  COUNT(*) cantidad,usuario_perfil.perfil_id
FROM it_transaccion
  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = it_transaccion.usuario_id)
 INNER JOIN it_ticket_enc ON (it_ticket_enc.ticket_id = it_transaccion.ticket_id) 
    WHERE (it_transaccion.fecha_crea) = '" . $fechaSoloDia . "'     AND usuario_perfil.mandante NOT IN (3,4,5,6,7,10,22,17,25)
GROUP BY it_ticket_enc.wallet,(it_transaccion.fecha_crea),it_transaccion.tipo, it_transaccion.usuario_id
ORDER BY it_ticket_enc.wallet,(it_transaccion.fecha_crea),it_transaccion.tipo,it_transaccion.usuario_id;";

    $sqlApuestasDeportivasUsuarioDiaIMPUESTOS = "
SELECT
  it_ticket_enc.usuario_id usuarioId, 
  SUM(it_ticket_enc.impuesto) valor,
  date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d') fecha_crea,
      0 usucrea_id,
      it_ticket_enc.wallet usumodif_id,
      'A' estado,
      'TAXWIN' tipo,
  COUNT(*) cantidad,usuario_perfil.perfil_id
FROM it_ticket_enc
  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = it_ticket_enc.usuario_id)
    WHERE (it_ticket_enc.fecha_cierre) = '" . $fechaSoloDia . "'  AND impuesto >0    AND usuario_perfil.mandante NOT IN (3,4,5,6,7,10,22,17,25)
GROUP BY it_ticket_enc.wallet,(it_ticket_enc.fecha_cierre), it_ticket_enc.usuario_id
ORDER BY it_ticket_enc.wallet,(it_ticket_enc.fecha_cierre),it_ticket_enc.usuario_id;";

    $sqlApuestasDeportivasPuntoVentaDia = "
SELECT
  CASE WHEN usuario_perfil.perfil_id != 'USUONLINE' then it_ticket_enc.usuario_id else 0 end usuarioId,
  SUM(CASE WHEN usuario_perfil.perfil_id != 'USUONLINE' then it_ticket_enc.vlr_apuesta else 0 end) valor,
  date_format(it_ticket_enc.fecha_crea, '%Y-%m-%d')fecha_crea,
  0 usucrea_id,
  it_ticket_enc.wallet usumodif_id ,
  'A' estado,
  '1' tipo,
  SUM(CASE WHEN usuario_perfil.perfil_id != 'USUONLINE' then 1 else 0 end) cantidad
FROM it_ticket_enc
  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = it_ticket_enc.usuario_id)

    WHERE (it_ticket_enc.fecha_crea) = '" . $fechaSoloDia . "'  AND it_ticket_enc.eliminado ='N' AND usuario_perfil.mandante NOT IN (3,4,5,6,7,10,22,17,25)
GROUP BY it_ticket_enc.wallet,(it_ticket_enc.fecha_crea), it_ticket_enc.usuario_id HAVING usuarioId != '0'
ORDER BY it_ticket_enc.wallet,(it_ticket_enc.fecha_crea),it_ticket_enc.usuario_id;";

    $sqlApuestasDeportivasPuntoVentaoDiaCierre = "
SELECT
  CASE WHEN usuario_perfil.perfil_id != 'USUONLINE' then it_ticket_enc.usuario_id else 0 end usuarioId,
  SUM(CASE WHEN usuario_perfil.perfil_id != 'USUONLINE' then it_ticket_enc.vlr_apuesta else 0 end) valor,
      it_ticket_enc.fecha_cierre fecha_crea,
  0 usucrea_id,
  it_ticket_enc.wallet usumodif_id,
  'A' estado,
  '2' tipo,
  SUM(CASE WHEN usuario_perfil.perfil_id != 'USUONLINE' then 1 ELSE 0 END) cantidad
FROM it_ticket_enc
  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = it_ticket_enc.usuario_id)

    WHERE (it_ticket_enc.fecha_cierre) = '" . $fechaSoloDia . "' AND  it_ticket_enc.eliminado='N' AND usuario_perfil.mandante NOT IN (3,4,5,6,7,10,22,17,25)

GROUP BY it_ticket_enc.wallet,it_ticket_enc.fecha_cierre, it_ticket_enc.usuario_id HAVING usuarioId != '0'
ORDER BY it_ticket_enc.wallet,it_ticket_enc.usuario_id;";

    $sqlPremiosDeportivasPuntoVentaoDia = "
SELECT
  CASE WHEN usuario_perfil.perfil_id != 'USUONLINE' then it_ticket_enc.usuario_id else 0 END usuarioId,
  SUM(CASE WHEN usuario_perfil.perfil_id != 'USUONLINE' then it_ticket_enc.vlr_premio else 0 end) valor,
  it_ticket_enc.fecha_cierre fecha_crea,
  0 usucrea_id,
  it_ticket_enc.wallet usumodif_id,
  'P' estado,
  '2' tipo,
  sum(CASE WHEN usuario_perfil.perfil_id != 'USUONLINE' then 1 else 0 end) cantidad
FROM it_ticket_enc
  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = it_ticket_enc.usuario_id)

    WHERE (it_ticket_enc.fecha_cierre) = '" . $fechaSoloDia . "'  AND it_ticket_enc.premiado='S' AND it_ticket_enc.eliminado='N' AND usuario_perfil.mandante NOT IN (3,4,5,6,7,10,22,17,25)

GROUP BY it_ticket_enc.wallet,it_ticket_enc.fecha_cierre, it_ticket_enc.usuario_id HAVING usuarioId != '0'
ORDER BY it_ticket_enc.wallet,it_ticket_enc.usuario_id;";


    $sqlPremiosPagadosDeportivasPuntoVentaoDia = "
SELECT
  CASE WHEN usuario_perfil.perfil_id != 'USUONLINE' then it_ticket_enc.usuario_id else 0 end usuarioId,
  SUM(CASE WHEN usuario_perfil.perfil_id != 'USUONLINE' then it_ticket_enc.vlr_premio-it_ticket_enc.impuesto else 0 end ) valor,
  it_ticket_enc.fecha_pago fecha_crea,
  0 usucrea_id,
  it_ticket_enc.wallet usumodif_id,
  'P' estado,
  '3' tipo,
  SUM(CASE WHEN usuario_perfil.perfil_id != 'USUONLINE' then 1 ELSE 0 END) cantidad
FROM it_ticket_enc
  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = it_ticket_enc.usuario_id)

    WHERE (it_ticket_enc.fecha_pago) = '" . $fechaSoloDia . "'AND it_ticket_enc.premiado='S' AND it_ticket_enc.eliminado='N' AND usuario_perfil.mandante NOT IN (3,4,5,6,7,10,22,17,25)

GROUP BY it_ticket_enc.wallet,it_ticket_enc.fecha_pago, it_ticket_enc.usuario_id HAVING usuarioId != '0'
ORDER BY it_ticket_enc.wallet,it_ticket_enc.usuario_id;";

    $sqlPremiosDeportivasPuntoVentaoDiaCONTIPO = "
SELECT
  CASE WHEN usuario_perfil.perfil_id != 'USUONLINE' then it_transaccion.usuario_id else 0 end usuarioId,
  SUM(CASE WHEN usuario_perfil.perfil_id != 'USUONLINE' then it_transaccion.valor else 0 end) valor,
  it_transaccion.fecha_crea fecha_crea,
  0 usucrea_id,
  0 usumodif_id,
  'A' estado,
  tipo tipo,
  SUM(CASE WHEN usuario_perfil.perfil_id != 'USUONLINE' then 1 ELSE 0 END) cantidad
FROM it_ticket_enc
  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = it_ticket_enc.usuario_id)

    WHERE (it_transaccion.fecha_crea) = '" . $fechaSoloDia . "' 

GROUP BY it_transaccion.fecha_crea, it_transaccion.usuario_id HAVING usuarioId != '0'
ORDER BY it_transaccion.usuario_id;";

    /*$sqlApuestasCasinoDia="INSERT INTO usuario_casino_resumen (usuario_id, valor,valor_premios, fecha_crea, usucrea_id, usumodif_id, estado, tipo, cantidad)
    SELECT
      transaccion_juego.usuario_id,
      SUM(transaccion_juego.valor_ticket),
      SUM(transaccion_juego.valor_premio),
      date_format(transaccion_juego.fecha_crea, '%Y-%m-%d'),
      0,
      0,
      'A',
      '1',
      COUNT(*) cantidad
    FROM transaccion_juego
      INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = transaccion_juego.usuario_id)

        WHERE date_format(transaccion_juego.fecha_crea, '%Y-%m-%d') = '".$fechaSoloDia."' AND usuario_perfil.perfil_id ='USUONLINE'

    GROUP BY date_format(transaccion_juego.fecha_crea, '%Y-%m-%d'), transaccion_juego.usuario_id
    ORDER BY date_format(transaccion_juego.fecha_crea, '%Y-%m-%d'),transaccion_juego.usuario_id;";*/

    $sqlApuestasCasinoDia = "
SELECT
  transaccion_juego.usuario_id usuarioId,
  SUM(transjuego_log.valor) valor,
  SUM(CASE WHEN transaccion_juego.tipo ='FREECASH' THEN transjuego_log.saldo_free  ELSE 0 END) valor_premios,
  date_format(transjuego_log.fecha_crea, '%Y-%m-%d') fecha_crea,
  0 usucrea_id, 
  0 usumodif_id,
  'A' estado, 
  CASE WHEN transaccion_juego.tipo ='FREECASH' THEN '6' WHEN transaccion_juego.tipo ='FREESPIN' THEN '4'  ELSE '1' END tipo,
  COUNT(*) cantidad
      FROM transjuego_log      
      
      inner join time_dimension force index (time_dimension_timestampint_dbtimestamp_index)
                    on (time_dimension.dbtimestamp = transjuego_log.fecha_crea)
      
        INNER JOIN transaccion_juego ON (transjuego_log.transjuego_id =transaccion_juego.transjuego_id)
             INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usumandante_id)

    WHERE time_dimension.dbdate = '" . $fechaSoloDia . "' AND transjuego_log.tipo LIKE '%DEBIT%'   AND usuario_mandante.mandante NOT IN (3,4,5,6,7,10,22,17,25)
GROUP BY transaccion_juego.tipo,time_dimension.dbdate, transaccion_juego.usuario_id
ORDER BY time_dimension.dbdate,transaccion_juego.usuario_id;";

    $sqlPremiosCasinoDia = "
SELECT
  transaccion_juego.usuario_id usuarioId,
  SUM(transjuego_log.valor) valor,
  SUM(transjuego_log.valor) valor_premios,
  date_format(transjuego_log.fecha_crea, '%Y-%m-%d') fecha_crea,
  0 usucrea_id,
  0 usumodif_id,
  'A' estado,
  CASE WHEN transaccion_juego.tipo ='FREECASH' THEN '7' WHEN transaccion_juego.tipo ='FREESPIN' THEN '5' ELSE '2' END tipo,
  COUNT(*) cantidad
      FROM transjuego_log         
      inner join time_dimension force index (time_dimension_timestampint_dbtimestamp_index)
                    on (time_dimension.dbtimestamp = transjuego_log.fecha_crea)

      
        INNER JOIN transaccion_juego ON (transjuego_log.transjuego_id =transaccion_juego.transjuego_id)
             INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usumandante_id)

    WHERE time_dimension.dbdate = '" . $fechaSoloDia . "' AND (transjuego_log.tipo LIKE '%CREDIT%'OR transjuego_log.tipo like '%ROLLBACK%')   AND usuario_mandante.mandante NOT IN (3,4,5,6,7,10,22,17,25)
GROUP BY transaccion_juego.tipo,time_dimension.dbdate, transaccion_juego.usuario_id
ORDER BY time_dimension.dbdate,transaccion_juego.usuario_id;";


    $sqlApuestasCasinoDiaConProducto = "
SELECT
  transaccion_juego.usuario_id usuarioId,
  producto_mandante.prodmandante_id,
  producto_mandante.producto_id,
  producto.subproveedor_id,
  producto.proveedor_id,
  producto_mandante.mandante,
  SUM(transjuego_log.valor) valor,
  SUM(transjuego_log.valor) valor_premios,
  date_format(transjuego_log.fecha_crea, '%Y-%m-%d') fecha_crea,
  0 usucrea_id,
  0 usumodif_id,
  'A' estado, 
  '1' tipo,
  COUNT(*) cantidad
      FROM transjuego_log
            inner join time_dimension force index (time_dimension_timestampint_dbtimestamp_index)
                    on (time_dimension.dbtimestamp = transjuego_log.fecha_crea)
      INNER JOIN transaccion_juego ON (transjuego_log.transjuego_id =transaccion_juego.transjuego_id)
        INNER JOIN producto_mandante ON (prodmandante_id =transaccion_juego.producto_id)
        INNER JOIN producto ON (producto.producto_id =producto_mandante.producto_id)
        INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usumandante_id)

    WHERE time_dimension.dbdate = '" . $fechaSoloDia . "'  AND transjuego_log.tipo LIKE '%DEBIT%'   AND usuario_mandante.mandante NOT IN (3,4,5,6,7,10,22,17,25)
GROUP BY transaccion_juego.producto_id,time_dimension.dbdate, transaccion_juego.usuario_id
ORDER BY time_dimension.dbdate,transaccion_juego.usuario_id;";

    $sqlPremiosCasinoDiaConProducto = "
SELECT
  transaccion_juego.usuario_id usuarioId,
  producto_mandante.prodmandante_id,
  producto_mandante.producto_id,
  producto.subproveedor_id,
  producto.proveedor_id,
  producto_mandante.mandante,
  SUM(transjuego_log.valor) valor,
  SUM(transjuego_log.valor) valor_premios,
  date_format(transjuego_log.fecha_crea, '%Y-%m-%d') fecha_crea,
  0 usucrea_id,
  0 usumodif_id,
  'A' estado,
  '2' tipo,
  COUNT(*) cantidad
      FROM transjuego_log
                  inner join time_dimension force index (time_dimension_timestampint_dbtimestamp_index)
                    on (time_dimension.dbtimestamp = transjuego_log.fecha_crea)

        INNER JOIN transaccion_juego ON (transjuego_log.transjuego_id =transaccion_juego.transjuego_id)
        INNER JOIN producto_mandante ON (prodmandante_id =transaccion_juego.producto_id)
        INNER JOIN producto ON (producto.producto_id =producto_mandante.producto_id)
        INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usumandante_id)

    WHERE time_dimension.dbdate = '" . $fechaSoloDia . "'  AND (transjuego_log.tipo LIKE '%CREDIT%'OR transjuego_log.tipo like '%ROLLBACK%')   AND usuario_mandante.mandante NOT IN (3,4,5,6,7,10,22,17,25)
GROUP BY transaccion_juego.producto_id,time_dimension.dbdate, transaccion_juego.usuario_id
ORDER BY time_dimension.dbdate,transaccion_juego.usuario_id;";


    /*
    $sqlPremiosCasinoDia ="INSERT INTO usucasino_detalle_resumen (usuario_id, producto_id, valor,valor_premios, fecha_crea, usucrea_id, usumodif_id, estado, tipo, cantidad)
    SELECT
      transaccion_juego.usuario_id,
      transaccion_juego.producto_id,
      SUM(transaccion_juego.valor_ticket),
      SUM(transaccion_juego.valor_premio),
      date_format(transaccion_juego.fecha_crea, '%Y-%m-%d'),
      0,
      0,
      'A',
      '1',
      COUNT(*) cantidad
    FROM transaccion_juego
      INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = transaccion_juego.usuario_id)

        WHERE date_format(transaccion_juego.fecha_crea, '%Y-%m-%d') = '".$fechaSoloDia."' AND usuario_perfil.perfil_id = 'USUONLINE'

    GROUP BY date_format(transaccion_juego.fecha_crea, '%Y-%m-%d'),transaccion_juego.producto_id, transaccion_juego.usuario_id
    ORDER BY date_format(transaccion_juego.fecha_crea, '%Y-%m-%d'),transaccion_juego.producto_id,transaccion_juego.usuario_id;";*/

    $sqlDetalleApuesCasinoDia = "
SELECT
  transaccion_juego.usuario_id usuarioId,
  transaccion_juego.producto_id producto_id,
  SUM(transjuego_log.valor) valor,
  SUM(CASE WHEN transaccion_juego.tipo = 'FREECASH' THEN transjuego_log.saldo_free ElSE 0 END) valor_premios,
  date_format(transjuego_log.fecha_crea, '%Y-%m-%d') fecha_crea,
  0 usucrea_id,
  0 usumodif_id,
  'A' estado,
  CASE WHEN transaccion_juego.tipo = 'FREESPIN' THEN 'DEBITFREESPIN' WHEN transaccion_juego.tipo = 'FREECASH' THEN 'DEBITFREECASH' ElSE 'DEBIT' END tipo,
  COUNT(*) cantidad
      FROM transjuego_log
                  inner join time_dimension force index (time_dimension_timestampint_dbtimestamp_index)
                    on (time_dimension.dbtimestamp = transjuego_log.fecha_crea)

        INNER JOIN transaccion_juego ON (transjuego_log.transjuego_id =transaccion_juego.transjuego_id)
             INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usumandante_id)

    WHERE time_dimension.dbdate = '" . $fechaSoloDia . "' AND transjuego_log.tipo LIKE '%DEBIT%'   AND usuario_mandante.mandante NOT IN (3,4,5,6,7,10,22,17,25)

GROUP BY time_dimension.dbdate,transaccion_juego.producto_id, transaccion_juego.usuario_id,transaccion_juego.tipo
ORDER BY time_dimension.dbdate,transaccion_juego.producto_id,transaccion_juego.usuario_id,transaccion_juego.tipo;";

    $sqlDetallePremiosCasinoDia = "
SELECT
  transaccion_juego.usuario_id usuarioId,
  transaccion_juego.producto_id producto_id,
  SUM(transjuego_log.valor) valor,
  SUM(transjuego_log.valor) valor_premios,
  date_format(transjuego_log.fecha_crea, '%Y-%m-%d') fecha_crea,
  0 usucrea_id,
  0 usumodif_id,
  'A' estado,
  CASE WHEN transaccion_juego.tipo = 'FREESPIN' THEN 'CREDITFREESPIN' WHEN transaccion_juego.tipo = 'FREECASH' THEN 'CREDITFREECASH' ElSE 'CREDIT' END tipo,
  COUNT(*) cantidad
      FROM transjuego_log
                  inner join time_dimension force index (time_dimension_timestampint_dbtimestamp_index)
                    on (time_dimension.dbtimestamp = transjuego_log.fecha_crea)

        INNER JOIN transaccion_juego ON (transjuego_log.transjuego_id =transaccion_juego.transjuego_id)
             INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usumandante_id)

    WHERE time_dimension.dbdate = '" . $fechaSoloDia . "' AND (transjuego_log.tipo LIKE '%CREDIT%')   AND usuario_mandante.mandante NOT IN (3,4,5,6,7,10,22,17,25)

GROUP BY time_dimension.dbdate,transaccion_juego.producto_id, transaccion_juego.usuario_id,transaccion_juego.tipo
ORDER BY time_dimension.dbdate,transaccion_juego.producto_id,transaccion_juego.usuario_id,transaccion_juego.tipo;";


    $sqlDetalleRollbackCasinoDia = "
SELECT
  transaccion_juego.usuario_id usuarioId,
  transaccion_juego.producto_id producto_id,
  SUM(transjuego_log.valor) valor,
  SUM(transjuego_log.valor) valor_premios,
  date_format(transjuego_log.fecha_crea, '%Y-%m-%d') fecha_crea,
  0 usucrea_id,
  0 usumodif_id,
  'A' estado,
  CASE WHEN transaccion_juego.tipo = 'FREESPIN' THEN 'ROLLBACKFREESPIN' WHEN transaccion_juego.tipo = 'FREECASH' THEN 'ROLLBACKFREECASH' ElSE 'ROLLBACK' END tipo,
  COUNT(*) cantidad
      FROM transjuego_log
                  inner join time_dimension force index (time_dimension_timestampint_dbtimestamp_index)
                    on (time_dimension.dbtimestamp = transjuego_log.fecha_crea)

        INNER JOIN transaccion_juego ON (transjuego_log.transjuego_id =transaccion_juego.transjuego_id)
             INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usumandante_id)

    WHERE time_dimension.dbdate = '" . $fechaSoloDia . "' AND (transjuego_log.tipo like '%ROLLBACK%')   AND usuario_mandante.mandante NOT IN (3,4,5,6,7,10,22,17,25)

GROUP BY time_dimension.dbdate,transaccion_juego.producto_id, transaccion_juego.usuario_id
ORDER BY time_dimension.dbdate,transaccion_juego.producto_id,transaccion_juego.usuario_id;";


    $sqlBonoUsuarioCreados = "
SELECT
  bono_log.usuario_id usuarioId,
  SUM(bono_log.valor) valor,
  bono_log.fecha_crea fecha_crea,
  0 usucrea_id,
  0 usumodif_id,
  bono_log.estado estado,
  bono_log.tipo tipo,
  COUNT(*) cantidad
FROM bono_log
  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = bono_log.usuario_id)

    WHERE bono_log.fecha_crea is not null
  and date_format(bono_log.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND usuario_perfil.perfil_id = \"USUONLINE\"    AND usuario_perfil.mandante NOT IN (3,4,5,6,7,10,22,17,25)

GROUP BY bono_log.tipo,bono_log.estado,bono_log.fecha_crea, bono_log.usuario_id
ORDER BY bono_log.usuario_id;";


    $sqlUsuarioAjustesDia = "
SELECT
  saldo_usuonline_ajuste.usuario_id usuarioId,
  SUM(saldo_usuonline_ajuste.valor) valor,
  saldo_usuonline_ajuste.fecha_crea fecha_crea,
  0 usucrea_id,
  0 usumodif_id,
  saldo_usuonline_ajuste.tipo_id tipo,
  COUNT(*) cantidad,
  tipo tipo_ajuste,
  proveedor_id proveedor_id
FROM saldo_usuonline_ajuste
  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = saldo_usuonline_ajuste.usuario_id)

    WHERE  date_format(saldo_usuonline_ajuste.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND usuario_perfil.perfil_id = \"USUONLINE\"   AND usuario_perfil.mandante NOT IN (3,4,5,6,7,10,22,17,25)

GROUP BY tipo,proveedor_id,saldo_usuonline_ajuste.tipo_id,saldo_usuonline_ajuste.fecha_crea, saldo_usuonline_ajuste.usuario_id
ORDER BY saldo_usuonline_ajuste.usuario_id;";

    $sqlUsuarioAjustesDiaPuntoVenta = "
SELECT
  cupo_log.usuario_id usuarioId,
  SUM(cupo_log.valor) valor,
  date_format(cupo_log.fecha_crea, '%Y-%m-%d') fecha_crea,
  0 usucrea_id,
  0 usumodif_id,
  cupo_log.tipo_id tipo,
  COUNT(*) cantidad,
  cupo_log.tipocupo_id tipo_ajuste,
  0 proveedor_id
FROM cupo_log
  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = cupo_log.usuario_id)

    WHERE  date_format(cupo_log.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "'   AND usuario_perfil.mandante NOT IN (3,4,5,6,7,10,22,17,25)

GROUP BY cupo_log.tipocupo_id,tipo,proveedor_id,cupo_log.tipo_id,date_format(cupo_log.fecha_crea, '%Y-%m-%d'), cupo_log.usuario_id
ORDER BY cupo_log.usuario_id;";

    $sqlUsuarioAjustesDiaPuntoVenta2 = "
SELECT
  cupo_log.usucrea_id usuarioId,
  SUM(cupo_log.valor) valor,
  date_format(cupo_log.fecha_crea, '%Y-%m-%d') fecha_crea,
  0 usucrea_id,
  0 usumodif_id,
  (CASE WHEN cupo_log.tipo_id = 'E' THEN 'S' ELSE 'E' END) tipo,
  COUNT(*) cantidad,
  cupo_log.tipocupo_id tipo_ajuste,
  0 proveedor_id
FROM cupo_log
  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = cupo_log.usucrea_id)

    WHERE  date_format(cupo_log.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "'   AND usuario_perfil.mandante NOT IN (3,4,5,6,7,10,22,17,25)
 AND cupo_log.usucrea_id != cupo_log.usuario_id
GROUP BY cupo_log.tipocupo_id,tipo,proveedor_id,cupo_log.tipo_id,date_format(cupo_log.fecha_crea, '%Y-%m-%d'), cupo_log.usucrea_id
ORDER BY cupo_log.usucrea_id;";

    $UsuarioSaldoInicial = "
SELECT registro.usuario_id,
       registro.mandante,
       DATE_FORMAT(DATE_SUB(now(), INTERVAL 1 DAY), '%Y-%m-%d') fecha,
       0,
       0,
       0,
       0,
       0,
       0,
       0,
       0,
       0,
       0,
       0,
       0,
       0,
       0,
       0,
       registro.creditos,
       registro.creditos_base

FROM registro;
";

    $UsuarioSaldoInicialPuntoVenta = "
SELECT punto_venta.usuario_id,
       punto_venta.mandante,
       DATE_FORMAT(DATE_SUB(now(), INTERVAL 1 DAY), '%Y-%m-%d') fecha,
       0,
       0,
       0,
       0,
       0,
       0,
       0,
       0,
       0,
       0,
       0,
       0,
       0,
       0,
       0,
       punto_venta.cupo_recarga,
       punto_venta.creditos_base

FROM punto_venta;
";

    $UsuarioSaldoInicialFIX = "
SELECT usuario_saldo.usuario_id,
       usuario_saldo.mandante,
       usuario_saldo.fecha,
       usuario_saldo.saldo_creditos_final,
       usuario_saldo.saldo_creditos_base_final,
       usuario_saldo.saldo_final
FROM usuario_saldo
where DATE_FORMAT(fecha, '%Y-%m-%d')   ='" . $fechaSoloDia . "'

";

    $UsuarioSaldoInicialFIX222 = "
SELECT usuario_saldo.usuario_id,
       usuario_saldo.mandante,
       usuario_saldo.fecha,
       usuario_saldo.saldo_creditos_inicial,
       usuario_saldo.saldo_creditos_base_inicial,
       usuario_saldo.saldo_inicial
FROM usuario_saldo
where DATE_FORMAT(fecha, '%Y-%m-%d')   ='" . $fechaSoloDia . "'

";
    $UsuarioSaldoInicialFIX2 = "




SELECT usuario_id,creditos,creditos_base,fecha_crea
FROM usuario_historial
WHERE usuhistorial_id IN (
  SELECT usuario_historial.usuhistorial_id
  FROM usuario_historial
         INNER JOIN (
    SELECT usuhistorial_id,MAX(fecha_crea) fecha,usuario_id
    FROM usuario_historial
    WHERE DATE_FORMAT(fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "'
    GROUP BY usuario_id) data ON (data.usuario_id = usuario_historial.usuario_id AND data.fecha = fecha_crea)
)

";


    $UsuarioSaldoInicialFIX3 = "




SELECT usuario_id,creditos,creditos_base,fecha_crea
FROM usuario_historial
WHERE usuhistorial_id IN (
  SELECT usuario_historial.usuhistorial_id
  FROM usuario_historial
         INNER JOIN (
    SELECT usuhistorial_id,MAX(fecha_crea) fecha,usuario_id
    FROM usuario_historial
    WHERE DATE_FORMAT(fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "'
    GROUP BY usuario_id) data ON (data.usuario_id = usuario_historial.usuario_id AND data.fecha = fecha_crea)
)

";

    $UsuarioSaldoFinal = "
SELECT registro.usuario_id,
       registro.mandante,
       DATE_FORMAT(DATE_SUB(now(), INTERVAL 0 DAY), '%Y-%m-%d') fecha,
       0,
       0,
       0,
       0,
       0,
       0,
       0,
       0,
       0,
       0,
       0,
       0,
       0,
       registro.creditos,
       registro.creditos_base,
       0,
       0
       

FROM registro;

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
       SUM(data.saldo_apuestas_casino)                                                saldo_apuestas_casino,
       SUM(data.saldo_premios_casino)                                                 saldo_premios_casino,
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
       CASE WHEN data2.saldo_inicial IS NULL THEN 0 ELSE data2.saldo_inicial END saldo_final,
       0 saldo_creditos_inicial,
       0 saldo_creditos_base_inicial,
       CASE
         WHEN data2.saldo_creditos_inicial IS NULL THEN 0
         ELSE data2.saldo_creditos_inicial END
                                                                                 saldo_creditos_final,
       CASE
         WHEN data2.saldo_creditos_base_inicial IS NULL THEN 0
         ELSE data2.saldo_creditos_base_inicial END                              saldo_creditos_base_final,
       data.billetera_id,
       SUM(data.saldo_premios_jackpot_casino)                                    saldo_premios_jackpot_casino,
       SUM(data.saldo_premios_jackpot_deportivas)                                saldo_premios_jackpot_deportivas
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
               0    saldo_premios_jackpot_deportivas
        FROM casino.usuario_recarga_resumen
        WHERE (fecha_crea)   ='" . $fecha1 . "'
        GROUP BY usuario_id
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
               0    saldo_premios_jackpot_deportivas
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
               0    saldo_premios_jackpot_deportivas
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
               0    saldo_premios_jackpot_deportivas
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
               0    saldo_premios_jackpot_deportivas
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
               0    saldo_premios_jackpot_deportivas
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
               0    saldo_premios_jackpot_deportivas
         FROM casino.usuario_casino_resumen
                         INNER JOIN usuario_mandante ON (usumandante_id=usuario_id)

         WHERE tipo IN ('2','5','7') AND  (usuario_casino_resumen.fecha_crea)   ='" . $fecha1 . "'
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
               0    saldo_premios_jackpot_deportivas
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
               0    saldo_premios_jackpot_deportivas
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
               0    saldo_premios_jackpot_deportivas
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
               0    saldo_premios_jackpot_deportivas
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
               0    saldo_premios_jackpot_deportivas
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
               0    saldo_premios_jackpot_deportivas
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
               0    saldo_premios_jackpot_deportivas
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
               0    saldo_premios_jackpot_deportivas
        FROM casino.usuario_retiro_resumen
        WHERE (estado = 'R' OR estado = 'E' OR estado = 'D') AND  (fecha_crea)   ='" . $fecha1 . "'
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
               0    saldo_premios_jackpot_deportivas
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
               0    saldo_premios_jackpot_deportivas
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
               SUM(valor)                                           saldo_premios_jackpot_deportivas
        FROM casino.usuario_bono_resumen
        WHERE DATE_FORMAT(fecha_crea, '%Y-%m-%d')   ='" . $fechaSoloDia . "' AND estado = 'L' AND tipo IN ({$sportbookJackpotTypesInBonoLog})
        GROUP BY usuario_id
       )
       
       
       
     ) data
     INNER JOIN usuario ON (data.usuario_id = usuario.usuario_id)
     INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = usuario.usuario_id)
    LEFT OUTER JOIN usuario_saldo_".date("Y_m_d")." as data2 on (data.usuario_id = data2.usuario_id)

WHERE data.usuario_id IS NOT NULL AND usuario_perfil.perfil_id = 'USUONLINE'    AND usuario_perfil.mandante NOT IN (3,4,5,6,7,10,22,17,25)
  AND data.fecha = '" . $fechaSoloDia . "'

GROUP BY data.usuario_id,data.billetera_id;

";


    $UsuarioPuntoVentaFinalConDetalles = "
SELECT data.usuario_id,
       usuario.mandante,
       DATE_FORMAT(data.fecha, '%Y-%m-%d')                                       fecha,

       SUM(data.saldo_recarga)                                                        saldo_recarga,
       SUM(data.saldo_apuestas)                                                       saldo_apuestas,
       SUM(data.saldo_premios)                                                        saldo_premios,
       SUM(data.saldo_impuestos_apuestas_deportivas)                                                        saldo_impuestos_apuestas_deportivas,
       SUM(data.saldo_impuestos_premios_deportivas)                                                        saldo_impuestos_premios_deportivas,
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
       CASE WHEN data2.saldo_inicial IS NULL THEN 0 ELSE data2.saldo_inicial END saldo_final,
       0 saldo_creditos_inicial,
       0 saldo_creditos_base_inicial,
       CASE
         WHEN data2.saldo_creditos_inicial IS NULL THEN 0
         ELSE data2.saldo_creditos_inicial END
                                                                                 saldo_creditos_final,
       CASE
         WHEN data2.saldo_creditos_base_inicial IS NULL THEN 0
         ELSE data2.saldo_creditos_base_inicial END                              saldo_creditos_base_final
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
               0                                   saldo_impuestos_premios_deportivas,
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
               0                                   saldo_impuestos_premios_deportivas,
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
               0                                   saldo_impuestos_premios_deportivas,
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
               0                                   saldo_impuestos_premios_deportivas,
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
               SUM(valor)                                   saldo_impuestos_premios_deportivas,
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

       (SELECT usuario_id,
               0                                   saldo_recarga,
               DATE_FORMAT(fecha_crea, '%Y-%m-%d') fecha,
               0                                   saldo_apuestas,
               0                                   saldo_premios,
               0                                   saldo_impuestos_apuestas_deportivas,
               0                                   saldo_impuestos_premios_deportivas,
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
               0                                   saldo_impuestos_premios_deportivas,
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
               0                                   saldo_impuestos_premios_deportivas,
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
               0                                   saldo_impuestos_premios_deportivas,
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
               0                                   saldo_impuestos_premios_deportivas,
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
    LEFT OUTER JOIN usuario_saldo_".date("Y_m_d")." as data2 on (data.usuario_id = data2.usuario_id)

WHERE data.usuario_id IS NOT NULL AND usuario_perfil.perfil_id != 'USUONLINE'    AND usuario_perfil.mandante NOT IN (3,4,5,6,7,10,22,17,25)
  AND data.fecha = '" . $fechaSoloDia . "'

GROUP BY data.usuario_id;

";

    $SelectTicketExpirados = "

SELECT ticket_id
FROM it_ticket_enc
WHERE premiado='S' and premio_pagado='N'  and  date(now()) > date(fecha_maxpago)

";
    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "Inicia: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);


    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "Inicia: " . $UsuarioSaldoFinal . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);


    $paso = true;
    ini_set('mysql.connect_timeout', 3000);
    ini_set('default_socket_timeout', 3000);

    if(true) {

        $BonoInterno = new BonoInterno();
        $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
        $transaccion = $BonoDetalleMySqlDAO->getTransaction();
        $transaccion->getConnection()->beginTransaction();


        //$BonoInterno->execQuery($transaccion, $strEliminado);

       // $BonoInterno->execQuery($transaccion, "set session wait_timeout=3000");

        if(true) {
            $log = "\r\n" . "-------------------------" . "\r\n";
            $log = $log . "Eliminado: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
            fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
            print_r("PASO");

            $data = $BonoInterno->execQuery('', $sqlRecargaUsuarioDia);
            foreach ($data as $datanum) {
                $sql = "INSERT INTO usuario_recarga_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, cantidad,mediopago_id,puntoventa_id)
              VALUES ('" . $datanum->{'usuario_recarga.usuarioId'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'.fecha_crea'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'.usumodif_id'} . "','" . $datanum->{'.estado'} . "','" . $datanum->{'.cantidad'} . "','" . $datanum->{'.mediopago_id'} . "','" . $datanum->{'usuario_recarga.puntoventa_id'} . "')";
                $BonoInterno->execQuery($transaccion, $sql);
            }


            $log = "\r\n" . "-------------------------" . "\r\n";
            $log = $log . "RecargaUsuarioDia: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
            fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
            print_r("PASO");
            print_r($sqlRecargaPuntoVentaDia);

            $data = $BonoInterno->execQuery('', $sqlRecargaPuntoVentaDia);

            foreach ($data as $datanum) {

                $sql = "INSERT INTO usuario_recarga_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, cantidad)
              VALUES ('" . $datanum->{'.usuarioId'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'.fecha_crea'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'.usumodif_id'} . "','" . $datanum->{'.estado'} . "','" . $datanum->{'.cantidad'} . "')";
                $BonoInterno->execQuery($transaccion, $sql);
            }

            $log = "\r\n" . "-------------------------" . "\r\n";
            $log = $log . "RecargaPuntoVentaDia: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
            fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
            print_r("PASO");
            print_r($sqlRecargaPasarelaDia);

            $data = $BonoInterno->execQuery('', $sqlRecargaPasarelaDia);


            foreach ($data as $datanum) {
                $sql = "INSERT INTO usuario_recarga_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, cantidad,mediopago_id)
              VALUES ('" . $datanum->{'usuario_recarga.usuarioId'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'.fecha_crea'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'.usumodif_id'} . "','" . $datanum->{'.estado'} . "','" . $datanum->{'.cantidad'} . "','" . $datanum->{'transaccion_producto.mediopago_id'} . "')";
                $BonoInterno->execQuery($transaccion, $sql);
            }

            $log = "\r\n" . "-------------------------" . "\r\n";
            $log = $log . "RecargaPasarelaDia: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
            fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
            print_r("PASO");
            print_r($sqlRetiroUsuarioDiaPagado);

            $data = $BonoInterno->execQuery('', $sqlRetiroUsuarioDiaPagado);


            foreach ($data as $datanum) {
                $sql = "INSERT INTO usuario_retiro_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, cantidad,puntoventa_id,version)
              VALUES ('" . $datanum->{'cuenta_cobro.usuarioId'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'.fecha_crea'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'.usumodif_id'} . "','" . $datanum->{'.estado'} . "','" . $datanum->{'.cantidad'} . "','" . $datanum->{'cuenta_cobro.puntoventa_id'} . "','" . $datanum->{'cuenta_cobro.version'} . "')";
                $BonoInterno->execQuery($transaccion, $sql);
            }

            $log = "\r\n" . "-------------------------" . "\r\n";
            $log = $log . "RetiroUsuarioDiaPagado: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
            fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
            print_r("PASO");
            print_r($sqlRetiroUsuarioDiaPagadoCuentaBancariaFisicamente);

            $data = $BonoInterno->execQuery('', $sqlRetiroUsuarioDiaPagadoCuentaBancariaFisicamente);


            foreach ($data as $datanum) {
                $sql = "INSERT INTO usuario_retiro_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, cantidad,puntoventa_id,version)
              VALUES ('" . $datanum->{'cuenta_cobro.usuarioId'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'.fecha_crea'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'.usumodif_id'} . "','" . $datanum->{'.estado'} . "','" . $datanum->{'.cantidad'} . "','0','" .  $datanum->{'cuenta_cobro.version'} . "')";
                $BonoInterno->execQuery($transaccion, $sql);
            }

            $log = "\r\n" . "-------------------------" . "\r\n";
            $log = $log . "RetiroUsuarioDiaPagado: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
            fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
            print_r("PASO");
            print_r($sqlRetiroUsuarioDiaPendiente);

            $data = $BonoInterno->execQuery('', $sqlRetiroUsuarioDiaPendiente);


            foreach ($data as $datanum) {
                $sql = "INSERT INTO usuario_retiro_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, cantidad,version)
              VALUES ('" . $datanum->{'cuenta_cobro.usuarioId'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'.fecha_crea'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'.usumodif_id'} . "','" . $datanum->{'.estado'} . "','" . $datanum->{'.cantidad'} . "','" .  $datanum->{'cuenta_cobro.version'} . "')";
                $BonoInterno->execQuery($transaccion, $sql);
            }

            $log = "\r\n" . "-------------------------" . "\r\n";
            $log = $log . "RetiroUsuarioDiaPendiente: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
            fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
            print_r("PASO");
            print_r($sqlRetiroUsuarioDiaEliminadas);

            $data = $BonoInterno->execQuery('', $sqlRetiroUsuarioDiaEliminadas);


            foreach ($data as $datanum) {
                $sql = "INSERT INTO usuario_retiro_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, cantidad,version)
              VALUES ('" . $datanum->{'cuenta_cobro.usuarioId'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'.fecha_crea'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'.usumodif_id'} . "','" . $datanum->{'.estado'} . "','" . $datanum->{'.cantidad'} . "','" .  $datanum->{'cuenta_cobro.version'} . "')";
                $BonoInterno->execQuery($transaccion, $sql);
            }

            $log = "\r\n" . "-------------------------" . "\r\n";
            $log = $log . "RetiroUsuarioDiaEliminadas: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
            fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
            print_r("PASO");
            print_r($sqlRetiroUsuarioDiaDevueltas);

            $data = $BonoInterno->execQuery('', $sqlRetiroUsuarioDiaDevueltas);


            foreach ($data as $datanum) {
                $sql = "INSERT INTO usuario_retiro_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, cantidad,version)
              VALUES ('" . $datanum->{'cuenta_cobro.usuarioId'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'.fecha_crea'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'.usumodif_id'} . "','" . $datanum->{'.estado'} . "','" . $datanum->{'.cantidad'} . "','" .  $datanum->{'cuenta_cobro.version'} . "')";
                $BonoInterno->execQuery($transaccion, $sql);
            }

            $log = "\r\n" . "-------------------------" . "\r\n";
            $log = $log . "RetiroUsuarioDiaEliminadas: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
            fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
            print_r("PASO");

            $data = $BonoInterno->execQuery('', $sqlRetiroUsuarioDiaRechazadas);


            foreach ($data as $datanum) {
                $sql = "INSERT INTO usuario_retiro_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, cantidad,version)
              VALUES ('" . $datanum->{'cuenta_cobro.usuarioId'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'.fecha_crea'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'.usumodif_id'} . "','" . $datanum->{'.estado'} . "','" . $datanum->{'.cantidad'} . "','" .  $datanum->{'cuenta_cobro.version'} . "')";
                $BonoInterno->execQuery($transaccion, $sql);
            }

            $log = "\r\n" . "-------------------------" . "\r\n";
            $log = $log . "RetiroUsuarioDiaRechazadas: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
            fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
            print_r("PASO");


            $data = $BonoInterno->execQuery('', $sqlRetiroPuntoVentaDiaPagado);


            foreach ($data as $datanum) {
                $sql = "INSERT INTO usuario_retiro_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, cantidad,version)
              VALUES ('" . $datanum->{'cuenta_cobro.usuarioId'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'.fecha_crea'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'.usumodif_id'} . "','" . $datanum->{'.estado'} . "','" . $datanum->{'.cantidad'} . "','" .  $datanum->{'cuenta_cobro.version'} . "')";
                $BonoInterno->execQuery($transaccion, $sql);
            }

            $log = "\r\n" . "-------------------------" . "\r\n";
            $log = $log . "RetiroPuntoVentaDiaPagado: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
            fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
            print_r("PASO");

            $data = $BonoInterno->execQuery('', $sqlRetiroUsuarioPendienteHoy);

            foreach ($data as $datanum) {
                $sql = "INSERT INTO usuario_retiro_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, cantidad,version)
              VALUES ('" . $datanum->{'cuenta_cobro.usuarioId'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'.fecha_crea'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'.usumodif_id'} . "','" . $datanum->{'.estado'} . "','" . $datanum->{'.cantidad'} . "','" .  $datanum->{'cuenta_cobro.version'} . "')";
                $BonoInterno->execQuery($transaccion, $sql);
            }

            $log = "\r\n" . "-------------------------" . "\r\n";
            $log = $log . "sqlRetiroUsuarioPendienteHoy: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
            fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
            print_r("PASO");

            $data = $BonoInterno->execQuery('', $sqlRetiroProductosPagado);


            foreach ($data as $datanum) {
                $sql = "INSERT INTO usuario_retiro_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, cantidad,producto_id,version)
              VALUES ('" . $datanum->{'cuenta_cobro.usuarioId'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'.fecha_crea'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'.usumodif_id'} . "','" . $datanum->{'.estado'} . "','" . $datanum->{'.cantidad'} . "','" . $datanum->{'transaccion_producto.producto_id'} . "','" . $datanum->{'cuenta_cobro.version'} . "')";
                $BonoInterno->execQuery($transaccion, $sql);
            }

            $log = "\r\n" . "-------------------------" . "\r\n";
            $log = $log . "sqlRetiroProductosPagado: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
            fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
            print_r("PASO");

        }



        $data = $BonoInterno->execQuery('', $sqlApuestasDeportivasUsuarioDiaIMPUESTOS);

        foreach ($data as $datanum) {
            if($datanum->{'usuario_perfil.perfil_id'} != 'USUONLINE'){
                continue;
            }
            $sql = "INSERT INTO usuario_deporte_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, tipo, cantidad)
              VALUES ('" . $datanum->{'it_ticket_enc.usuarioId'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'.fecha_crea'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'it_ticket_enc.usumodif_id'} . "','" . $datanum->{'.estado'} . "','" . $datanum->{'.tipo'} . "','" . $datanum->{'.cantidad'} . "')";
            $BonoInterno->execQuery($transaccion, $sql);
        }

        $data = $BonoInterno->execQuery('', $sqlApuestasDeportivasUsuarioDia);


        foreach ($data as $datanum) {
            if($datanum->{'usuario_perfil.perfil_id'} != 'USUONLINE'){
                continue;
            }
            $sql = "INSERT INTO usuario_deporte_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, tipo, cantidad)
              VALUES ('" . $datanum->{'it_transaccion.usuarioId'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'.fecha_crea'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'it_ticket_enc.usumodif_id'} . "','" . $datanum->{'.estado'} . "','" . $datanum->{'it_transaccion.tipo'} . "','" . $datanum->{'.cantidad'} . "')";
            $BonoInterno->execQuery($transaccion, $sql);
        }

        $log = "\r\n" . "-------------------------" . "\r\n";
        $log = $log . "ApuestasDeportivasUsuarioDia: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
        fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
        print_r("PASO");

        /*$BonoInterno->execQuery($transaccion,$sqlPremiosDeportivasUsuarioDia);

        $log = "\r\n" . "-------------------------" . "\r\n";
        $log = $log ."PremiosDeportivasUsuarioDia: ".$fechaSoloDia ." - ". date('Y-m-d H:i:s');
        fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
        print_r("PASO");*/

        $data = $BonoInterno->execQuery('', $sqlApuestasDeportivasPuntoVentaDia);


        foreach ($data as $datanum) {
            $sql = "INSERT INTO usuario_deporte_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, tipo, cantidad)
              VALUES ('" . $datanum->{'.usuarioId'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'.fecha_crea'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'it_ticket_enc.usumodif_id'} . "','" . $datanum->{'.estado'} . "','" . $datanum->{'.tipo'} . "','" . $datanum->{'.cantidad'} . "')";
            $BonoInterno->execQuery($transaccion, $sql);
        }

        $data = $BonoInterno->execQuery('', $sqlApuestasDeportivasPuntoVentaoDiaCierre);


        foreach ($data as $datanum) {
            $sql = "INSERT INTO usuario_deporte_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, tipo, cantidad)
              VALUES ('" . $datanum->{'.usuarioId'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'it_ticket_enc.fecha_crea'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'it_ticket_enc.usumodif_id'} . "','" . $datanum->{'.estado'} . "','" . $datanum->{'.tipo'} . "','" . $datanum->{'.cantidad'} . "')";
            $BonoInterno->execQuery($transaccion, $sql);
        }


        $log = "\r\n" . "-------------------------" . "\r\n";
        $log = $log . "ApuestasDeportivasPuntoVentaDia: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
        fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
        print_r("PASO");

        $data = $BonoInterno->execQuery('', $sqlPremiosDeportivasPuntoVentaoDia);


        foreach ($data as $datanum) {
            $sql = "INSERT INTO usuario_deporte_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, tipo, cantidad)
              VALUES ('" . $datanum->{'.usuarioId'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'it_ticket_enc.fecha_crea'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'it_ticket_enc.usumodif_id'} . "','" . $datanum->{'.estado'} . "','" . $datanum->{'.tipo'} . "','" . $datanum->{'.cantidad'} . "')";
            $BonoInterno->execQuery($transaccion, $sql);
        }

        $log = "\r\n" . "-------------------------" . "\r\n";
        $log = $log . "PremiosDeportivasPuntoVentaDia: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
        fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
        print_r("PASO");

        $data = $BonoInterno->execQuery('', $sqlPremiosPagadosDeportivasPuntoVentaoDia);


        foreach ($data as $datanum) {
            $sql = "INSERT INTO usuario_deporte_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, tipo, cantidad)
              VALUES ('" . $datanum->{'.usuarioId'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'it_ticket_enc.fecha_crea'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'it_ticket_enc.usumodif_id'} . "','" . $datanum->{'.estado'} . "','" . $datanum->{'.tipo'} . "','" . $datanum->{'.cantidad'} . "')";
            $BonoInterno->execQuery($transaccion, $sql);
        }

        $log = "\r\n" . "-------------------------" . "\r\n";
        $log = $log . "PremiosPagadosDeportivasPuntoVentaoDia: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
        fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
        print_r("PASO");


        $data = $BonoInterno->execQuery('', $sqlApuestasCasinoDia);


        foreach ($data as $datanum) {
            $sql = "INSERT INTO usuario_casino_resumen (usuario_id, valor,valor_premios, fecha_crea, usucrea_id, usumodif_id, estado, tipo, cantidad)
              VALUES ('" . $datanum->{'transaccion_juego.usuarioId'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'.valor_premios'} . "','" . $datanum->{'.fecha_crea'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'.usumodif_id'} . "','" . $datanum->{'.estado'} . "','" . $datanum->{'.tipo'} . "','" . $datanum->{'.cantidad'} . "')";
            $BonoInterno->execQuery($transaccion, $sql);
        }


        $log = "\r\n" . "-------------------------" . "\r\n";
        $log = $log . "ApuestasCasinoDia: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
        fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
        print_r("PASO");

        $data = $BonoInterno->execQuery('', $sqlPremiosCasinoDia);


        foreach ($data as $datanum) {
            $sql = "INSERT INTO usuario_casino_resumen (usuario_id, valor,valor_premios, fecha_crea, usucrea_id, usumodif_id, estado, tipo, cantidad)
              VALUES ('" . $datanum->{'transaccion_juego.usuarioId'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'.valor_premios'} . "','" . $datanum->{'.fecha_crea'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'.usumodif_id'} . "','" . $datanum->{'.estado'} . "','" . $datanum->{'.tipo'} . "','" . $datanum->{'.cantidad'} . "')";
            $BonoInterno->execQuery($transaccion, $sql);
        }


        $log = "\r\n" . "-------------------------" . "\r\n";
        $log = $log . "PremiosCasinoDia: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
        fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
        print_r("PASO");


        $data = $BonoInterno->execQuery('', $sqlDetalleApuesCasinoDia);

        foreach ($data as $datanum) {
            $sql = "INSERT INTO usucasino_detalle_resumen (usuario_id, producto_id, valor,valor_premios, fecha_crea, usucrea_id, usumodif_id, estado, tipo, cantidad)
              VALUES ('" . $datanum->{'transaccion_juego.usuarioId'} . "','" . $datanum->{'transaccion_juego.producto_id'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'.valor_premios'} . "','" . $datanum->{'.fecha_crea'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'.usumodif_id'} . "','" . $datanum->{'.estado'} . "','" . $datanum->{'.tipo'} . "','" . $datanum->{'.cantidad'} . "')";
            $BonoInterno->execQuery($transaccion, $sql);
        }

        $log = "\r\n" . "-------------------------" . "\r\n";
        $log = $log . "DetalleApuesCasinoDia: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
        fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
        print_r("PASO");


        $data = $BonoInterno->execQuery('', $sqlDetallePremiosCasinoDia);


        foreach ($data as $datanum) {
            $sql = "INSERT INTO usucasino_detalle_resumen (usuario_id, producto_id, valor,valor_premios, fecha_crea, usucrea_id, usumodif_id, estado, tipo, cantidad)
              VALUES ('" . $datanum->{'transaccion_juego.usuarioId'} . "','" . $datanum->{'transaccion_juego.producto_id'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'.valor_premios'} . "','" . $datanum->{'.fecha_crea'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'.usumodif_id'} . "','" . $datanum->{'.estado'} . "','" . $datanum->{'.tipo'} . "','" . $datanum->{'.cantidad'} . "')";
            $BonoInterno->execQuery($transaccion, $sql);
        }


        $log = "\r\n" . "-------------------------" . "\r\n";
        $log = $log . "DetallePremiosCasinoDia: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
        fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
        print_r("PASO");


        $data = $BonoInterno->execQuery('', $sqlDetalleRollbackCasinoDia);


        foreach ($data as $datanum) {
            $sql = "INSERT INTO usucasino_detalle_resumen (usuario_id, producto_id, valor,valor_premios, fecha_crea, usucrea_id, usumodif_id, estado, tipo, cantidad)
              VALUES ('" . $datanum->{'transaccion_juego.usuarioId'} . "','" . $datanum->{'transaccion_juego.producto_id'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'.valor_premios'} . "','" . $datanum->{'.fecha_crea'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'.usumodif_id'} . "','" . $datanum->{'.estado'} . "','" . $datanum->{'.tipo'} . "','" . $datanum->{'.cantidad'} . "')";
            $BonoInterno->execQuery($transaccion, $sql);
        }


        $log = "\r\n" . "-------------------------" . "\r\n";
        $log = $log . "DetalleRollbackCasinoDia: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
        fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
        print_r("PASO");


        $data = $BonoInterno->execQuery('', $sqlBonoUsuarioCreados);


        foreach ($data as $datanum) {
            $sql = "INSERT INTO usuario_bono_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, tipo, cantidad)
              VALUES ('" . $datanum->{'bono_log.usuarioId'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'bono_log.fecha_crea'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'.usumodif_id'} . "','" . $datanum->{'bono_log.estado'} . "','" . $datanum->{'bono_log.tipo'} . "','" . $datanum->{'.cantidad'} . "')";
            $BonoInterno->execQuery($transaccion, $sql);
        }


        $log = "\r\n" . "-------------------------" . "\r\n";
        $log = $log . "BonoUsuarioCreados: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
        fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
        print_r("PASO");


        $data = $BonoInterno->execQuery('', $sqlUsuarioAjustesDia);


        foreach ($data as $datanum) {
            $sql = "INSERT INTO usuario_ajustes_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, tipo, cantidad,tipo_ajuste,proveedor_id)
              VALUES ('" . $datanum->{'saldo_usuonline_ajuste.usuarioId'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'saldo_usuonline_ajuste.fecha_crea'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'.usumodif_id'} . "','" . $datanum->{'saldo_usuonline_ajuste.tipo'} . "','" . $datanum->{'.cantidad'} . "','" . $datanum->{'saldo_usuonline_ajuste.tipo_ajuste'} . "','" . $datanum->{'saldo_usuonline_ajuste.proveedor_id'} . "')";
            $BonoInterno->execQuery($transaccion, $sql);
        }


        $log = "\r\n" . "-------------------------" . "\r\n";
        $log = $log . "UsuarioAjustesDia: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
        fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
        print_r("PASO");


        $data = $BonoInterno->execQuery('', $sqlUsuarioAjustesDiaPuntoVenta);


        foreach ($data as $datanum) {
            $sql = "INSERT INTO usuario_ajustes_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, tipo, cantidad,tipo_ajuste,proveedor_id)
              VALUES ('" . $datanum->{'cupo_log.usuarioId'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'.fecha_crea'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'.usumodif_id'} . "','" . $datanum->{'cupo_log.tipo'} . "','" . $datanum->{'.cantidad'} . "','" . $datanum->{'cupo_log.tipo_ajuste'} . "','" . $datanum->{'.proveedor_id'} . "')";
            $BonoInterno->execQuery($transaccion, $sql);
        }


        $data = $BonoInterno->execQuery('', $sqlUsuarioAjustesDiaPuntoVenta2);


        foreach ($data as $datanum) {
            $sql = "INSERT INTO usuario_ajustes_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, tipo, cantidad,tipo_ajuste,proveedor_id)
              VALUES ('" . $datanum->{'cupo_log.usuarioId'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'.fecha_crea'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'.usumodif_id'} . "','" . $datanum->{'.tipo'} . "','" . $datanum->{'.cantidad'} . "','" . $datanum->{'cupo_log.tipo_ajuste'} . "','" . $datanum->{'.proveedor_id'} . "')";
            $BonoInterno->execQuery($transaccion, $sql);
        }

        $log = "\r\n" . "-------------------------" . "\r\n";
        $log = $log . "UsuarioCupoLogDia: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
        fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
        print_r("PASO");


        $BonoInterno->execQuery($transaccion, "UPDATE bono_interno SET estado='I' WHERE fecha_fin <= now() AND estado='A';");


        $log = "\r\n" . "-------------------------" . "\r\n";
        $log = $log . "Inactivacion Bonos: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
        fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
        print_r("PASO");

        /*  $sqlExpirados = "UPDATE usuario_bono, registro, bono_detalle
      SET usuario_bono.estado = 'E', registro.creditos_bono = registro.creditos_bono - usuario_bono.valor
      WHERE usuario_bono.usuario_id = registro.usuario_id AND (usuario_bono.bono_id = bono_detalle.bono_id AND
                                                               (bono_detalle.tipo = 'EXPDIA' OR
                                                                bono_detalle.tipo = 'EXPFECHA')) AND usuario_bono.estado = 'A'
            AND
            (CASE WHEN bono_detalle.tipo = 'EXPDIA'
              THEN now() > DATE_ADD(usuario_bono.fecha_crea, INTERVAL bono_detalle.valor DAY)
             ELSE now() > bono_detalle.valor END
            )
      ";
          $BonoInterno->execQuery($transaccion, $sqlExpirados);*/
        $procesoInterno=$BonoInterno->execQuery($transaccion, "INSERT INTO procesos_internos ( tipo, fecha_crea, usucrea_id) VALUES ('ResumenesPaso2','".date("Y-m-d 00:00:00")."','0');");

        $transaccion->commit();


        $BonoInterno = new BonoInterno();
        $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
        $transaccion = $BonoDetalleMySqlDAO->getTransaction();
        $transaccion->getConnection()->beginTransaction();


        $data = $BonoInterno->execQuery('', $SelectTicketExpirados);



        foreach ($data as $datanum) {
            $sql = "  UPDATE it_ticket_enc SET estado='E' WHERE ticket_id='" . $datanum->{'it_ticket_enc.ticket_id'} . "' ";
            $BonoInterno->execQuery($transaccion, $sql);
            $transaccion->commit();
            $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
            $transaccion = $BonoDetalleMySqlDAO->getTransaction();
            $transaccion->getConnection()->beginTransaction();

        }

    }
    $BonoInterno = new BonoInterno();

    $data = $BonoInterno->execQuery('', $UsuarioSaldoFinalConDetalles);


    foreach ($data as $datanum) {

        $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
        $transaccion = $BonoDetalleMySqlDAO->getTransaction();
        $transaccion->getConnection()->beginTransaction();

        $sql = "INSERT INTO usuario_saldo (usuario_id, mandante, fecha, saldo_recarga, saldo_apuestas, saldo_premios,
                           saldo_apuestas_casino, saldo_premios_casino, saldo_notaret_pagadas,
                           saldo_notaret_pend, saldo_notaret_creadas, saldo_ajustes_entrada, saldo_ajustes_salida,
                           saldo_inicial, saldo_final, saldo_bono,saldo_bono_casino_vivo, saldo_creditos_inicial, saldo_creditos_base_inicial,
                           saldo_creditos_final, saldo_creditos_base_final,saldo_notaret_eliminadas,saldo_bono_free_ganado,billetera_id,saldo_impuestos_apuestas_deportivas,saldo_impuestos_premios_deportivas, saldo_premios_jackpot_casino, saldo_premios_jackpot_deportivas) VALUES (
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
                           '" . $datanum->{'.saldo_premios_jackpot_deportivas'} . "'
                                 )
       
       
ON DUPLICATE KEY UPDATE usuario_saldo.saldo_recarga      = '" . $datanum->{'.saldo_recarga'} . "',
                         usuario_saldo.saldo_apuestas      = '" . $datanum->{'.saldo_apuestas'} . "',
                         usuario_saldo.saldo_premios      = '" . $datanum->{'.saldo_premios'} . "',
                         usuario_saldo.saldo_apuestas_casino      = '" . $datanum->{'.saldo_apuestas_casino'} . "',
                         usuario_saldo.saldo_premios_casino      = '" . $datanum->{'.saldo_premios_casino'} . "',
                         usuario_saldo.saldo_notaret_pagadas      = '" . $datanum->{'.saldo_notaret_pagadas'} . "',
                         usuario_saldo.saldo_notaret_pend      = '" . $datanum->{'.saldo_notaret_pend'} . "',
                         usuario_saldo.saldo_notaret_creadas      = '" . $datanum->{'.saldo_notaret_creadas'} . "',
                         usuario_saldo.saldo_ajustes_entrada      = '" . $datanum->{'.saldo_ajustes_entrada'} . "',
                         usuario_saldo.saldo_ajustes_salida      = '" . $datanum->{'.saldo_ajustes_salida'} . "',
                         usuario_saldo.saldo_bono      = '" . $datanum->{'.saldo_bono'} . "',
                         usuario_saldo.saldo_bono_casino_vivo      = '" . $datanum->{'.saldo_bono_casino_vivo'} . "',
                         usuario_saldo.saldo_notaret_eliminadas      = '" . $datanum->{'.saldo_notaret_eliminadas'} . "',
                         usuario_saldo.saldo_bono_free_ganado      = '" . $datanum->{'.saldo_bono_free_ganado'} . "',
                         usuario_saldo.saldo_impuestos_apuestas_deportivas      = '" . $datanum->{'.saldo_impuestos_apuestas_deportivas'} . "',
                         usuario_saldo.saldo_impuestos_premios_deportivas      = '" . $datanum->{'.saldo_impuestos_premios_deportivas'} . "',
                         usuario_saldo.saldo_premios_jackpot_casino      = '" . $datanum->{'.saldo_premios_jackpot_casino'} . "',
                         usuario_saldo.saldo_premios_jackpot_deportivas      = '" . $datanum->{'.saldo_premios_jackpot_deportivas'} . "'
                         

       ";

        $BonoInterno->execQuery($transaccion, $sql);
        if(true) {
            $sql = "INSERT INTO usuario_saldoresumen (usuario_id, mandante, saldo_recarga, saldo_apuestas, saldo_premios,
                           saldo_apuestas_casino, saldo_premios_casino, saldo_notaret_pagadas,
                           saldo_notaret_pend, saldo_notaret_creadas, saldo_ajustes_entrada, saldo_ajustes_salida,
                           saldo_inicial, saldo_final, saldo_bono, saldo_creditos_inicial, saldo_creditos_base_inicial,
                           saldo_creditos_final, saldo_creditos_base_final,saldo_notaret_eliminadas,saldo_bono_free_ganado,billetera_id,saldo_impuestos_apuestas_deportivas,saldo_impuestos_premios_deportivas, saldo_premios_jackpot_casino, saldo_premios_jackpot_deportivas) VALUES (
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
                           '" . $datanum->{'.saldo_premios_jackpot_deportivas'} . "'
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
                         usuario_saldoresumen.saldo_premios_jackpot_casino      = '" . $datanum->{'.saldo_premios_jackpot_casino'} . "',
                         usuario_saldoresumen.saldo_premios_jackpot_deportivas      = '" . $datanum->{'.saldo_premios_jackpot_deportivas'} . "'
                         

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

        $sql = "INSERT INTO usuario_saldo (usuario_id, mandante, fecha, saldo_recarga, saldo_apuestas, saldo_premios,
                           saldo_apuestas_casino, saldo_premios_casino, saldo_notaret_pagadas,
                           saldo_notaret_pend, saldo_notaret_creadas, saldo_ajustes_entrada, saldo_ajustes_salida,
                           saldo_inicial, saldo_final, saldo_bono, saldo_creditos_inicial, saldo_creditos_base_inicial,
                           saldo_creditos_final, saldo_creditos_base_final,saldo_notaret_eliminadas,saldo_bono_free_ganado,billetera_id,saldo_impuestos_apuestas_deportivas,saldo_impuestos_premios_deportivas, saldo_premios_jackpot_casino,saldo_premios_jackpot_deportivas) VALUES (
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
                           '0'
                                 )
       
       
ON DUPLICATE KEY UPDATE usuario_saldo.saldo_recarga      = '" . $datanum->{'.saldo_recarga'} . "',
                         usuario_saldo.saldo_apuestas      = '" . $datanum->{'.saldo_apuestas'} . "',
                         usuario_saldo.saldo_premios      = '" . $datanum->{'.saldo_premios'} . "',
                         usuario_saldo.saldo_apuestas_casino      = '" . $datanum->{'.saldo_apuestas_casino'} . "',
                         usuario_saldo.saldo_premios_casino      = '" . $datanum->{'.saldo_premios_casino'} . "',
                         usuario_saldo.saldo_notaret_pagadas      = '" . $datanum->{'.saldo_notaret_pagadas'} . "',
                         usuario_saldo.saldo_notaret_pend      = '" . $datanum->{'.saldo_notaret_pend'} . "',
                         usuario_saldo.saldo_notaret_creadas      = '" . $datanum->{'.saldo_notaret_creadas'} . "',
                         usuario_saldo.saldo_ajustes_entrada      = '" . $datanum->{'.saldo_ajustes_entrada'} . "',
                         usuario_saldo.saldo_ajustes_salida      = '" . $datanum->{'.saldo_ajustes_salida'} . "',
                         usuario_saldo.saldo_bono      = '" . $datanum->{'.saldo_bono'} . "',
                         usuario_saldo.saldo_notaret_eliminadas      = '" . $datanum->{'.saldo_notaret_eliminadas'} . "',
                         usuario_saldo.saldo_bono_free_ganado      = '" . $datanum->{'.saldo_bono_free_ganado'} . "',
                         usuario_saldo.saldo_impuestos_apuestas_deportivas      = '" . $datanum->{'.saldo_impuestos_apuestas_deportivas'} . "',
                         usuario_saldo.saldo_impuestos_premios_deportivas      = '" . $datanum->{'.saldo_impuestos_premios_deportivas'} . "',
                         usuario_saldo.saldo_premios_jackpot_casino         =     usuario_saldo.saldo_premios_jackpot_casino + 0,
                         usuario_saldo.saldo_premios_jackpot_deportivas         =     usuario_saldo.saldo_premios_jackpot_deportivas + 0";

        $BonoInterno->execQuery($transaccion, $sql);
        if(true) {
            $sql = "INSERT INTO usuario_saldoresumen (usuario_id, mandante, saldo_recarga, saldo_apuestas, saldo_premios,
                           saldo_apuestas_casino, saldo_premios_casino, saldo_notaret_pagadas,
                           saldo_notaret_pend, saldo_notaret_creadas, saldo_ajustes_entrada, saldo_ajustes_salida,
                           saldo_inicial, saldo_final, saldo_bono, saldo_creditos_inicial, saldo_creditos_base_inicial,
                           saldo_creditos_final, saldo_creditos_base_final,saldo_notaret_eliminadas,saldo_bono_free_ganado,billetera_id,saldo_impuestos_apuestas_deportivas,saldo_impuestos_premios_deportivas) VALUES (
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
                           '" . $datanum->{'.saldo_impuestos_premios_deportivas'} . "'
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
                         usuario_saldoresumen.saldo_impuestos_apuestas_deportivas      = '" . $datanum->{'.saldo_impuestos_apuestas_deportivas'} . "',
                         usuario_saldoresumen.saldo_impuestos_premios_deportivas      = '" . $datanum->{'.saldo_impuestos_premios_deportivas'} . "'
                         

       ";

            $BonoInterno->execQuery($transaccion, $sql);
        }
        $transaccion->commit();
    }
    $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
    $transaccion = $BonoDetalleMySqlDAO->getTransaction();
    $transaccion->getConnection()->beginTransaction();
    $procesoInterno=$BonoInterno->execQuery($transaccion, "INSERT INTO procesos_internos ( tipo, fecha_crea, usucrea_id) VALUES ('ResumenesPaso2-2','".date("Y-m-d 00:00:00")."','0');");
    $transaccion->commit();


    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "Terminacion: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);

    $message = "*CRON: (Fin) * " . " Resumenes - Fecha: " . date("Y-m-d H:i:s");

/*    try{

        $rules = [];
        array_push($rules, array("field" => "usuario.usuario_id", "data" => "'5208','14919','5703','5234','5219','22580','8752','204','9362','14913','12798','8937','1687','391','9385','24604','21229','21016','20856','9723','202','1589','3145','23399','2197','1775','20969','33302','15611','9386','5218','27278','199','3308','38554','38202','26687','34821','30497','4105','9336'", "op" => "in"));


        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);

        $Usuario = new Usuario();

        $usuarios = $Usuario->getUsuariosCustom("  usuario.usuario_id,usuario.fecha_cierrecaja,usuario_mandante.usumandante_id ", "usuario.usuario_id", "desc", 0, 100000, $json, true);

        $usuarios = json_decode($usuarios);

        $usuariosFinal = [];

        foreach ($usuarios->data as $key => $value) {

            if($value->{'usuario.fecha_cierrecaja'} != ''){
                if(date('Y-m-d H:i:s',strtotime($value->{'usuario.fecha_cierrecaja'})) < date('Y-m-d H:i:s',strtotime('-1 days'))){

                    $ConfigurationEnvironment = new ConfigurationEnvironment();

                    $ConfigurationEnvironment->CierreCaja($value->{'usuario_mandante.usumandante_id'},array(),array(),array(),date('Y-m-d',strtotime('-1 days')),date('Y-m-d 00:00:00',strtotime('-1 days')),date('Y-m-d 23:59:59',strtotime('-1 days')));
                }
            }

        }

        $log = "\r\n" . "-------------------------" . "\r\n";
        $log = $log . "Terminacion Cierres de caja: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
        fwriteCustom('log_' . date("Y-m-d") . '.log',$log);

        $message = "*CRON: (Fin) * " . " Terminacion Cierres de caja - Fecha: " . date("Y-m-d H:i:s");

        exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#procesos-cron-resumenes' > /dev/null & ");

    }catch (Exception $e){
        print_r($e);
        $log = "\r\n" . "-------------------------" . "\r\n";
        $log = $log . "ERROR: " . $e->getMessage() . " - " . date('Y-m-d H:i:s');
        fwriteCustom('log_' . date("Y-m-d") . '.log',$log);


        $message = "*CRON: (ERROR) * " . " Cierres de caja - Fecha: " . date("Y-m-d H:i:s");

        exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#procesos-cron-resumenes' > /dev/null & ");

    }*/
    exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#procesos-cron-resumenes' > /dev/null & ");


} catch (Exception $e) {
    print_r($e);
    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "ERROR: " . $e->getMessage() . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);


    $message = "*CRON: (ERROR) * " . " Resumenes - Fecha: " . date("Y-m-d H:i:s");

        exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#procesos-cron-resumenes' > /dev/null & ");

}





