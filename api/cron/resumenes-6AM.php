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



require(__DIR__.'/../vendor/autoload.php');
///home/devadmin/api/api/
$hour = date('H');
if(intval($hour)>9){
    //exit();
}
$_ENV["enabledConnectionGlobal"]=1;
$_ENV["TIMEZONE"] = "-11:00";

ini_set('memory_limit', '-1');

$message = "*CRON: (Inicio) * " . " Resumenes - Fecha: " . date("Y-m-d H:i:s");

exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

//$_ENV["NEEDINSOLATIONLEVEL"] ='1';

$ResumenesCron = new ResumenesCron();

//$ResumenesCron->generateResumenes();

$fechaSoloDia = date("Y-m-d", strtotime('-1 days'));
$fechaHoy = date("Y-m-d", strtotime('0 days'));
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

//BETWEEN '".$fecha1."' AND '".$fecha2."'

    $strEliminado = "DELETE FROM usuario_deporte_resumen WHERE date_format(fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "';
DELETE FROM usuario_casino_resumen WHERE date_format(fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "';
DELETE FROM usucasino_detalle_resumen WHERE date_format(fecha_crea, '%Y-%m-%d')= '" . $fechaSoloDia . "';
DELETE FROM usuario_retiro_resumen WHERE date_format(fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "';
DELETE FROM usuario_recarga_resumen WHERE date_format(fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "';
DELETE FROM usuario_bono_resumen WHERE date_format(fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "';
DELETE FROM usuario_ajustes_resumen WHERE date_format(fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "';
DELETE FROM usuario_saldo WHERE date_format(fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "';
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
    puntoventa_id puntoventa_id
  FROM usuario_recarga
    WHERE date_format(usuario_recarga.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "' and puntoventa_id != 0 /*and estado='A'*/
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
    WHERE date_format(usuario_recarga.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND usuario_recarga.puntoventa_id != 0
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
    WHERE date_format(usuario_recarga.fecha_elimina, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND usuario_recarga.puntoventa_id != 0 AND estado='I'
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

    INNER JOIN transaccion_producto ON (transaccion_producto.final_id = usuario_recarga.recarga_id)
    WHERE date_format(usuario_recarga.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND  usuario_recarga.puntoventa_id = 0
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
    cuenta_cobro.puntoventa_id puntoventa_id
  FROM cuenta_cobro
    WHERE cuenta_cobro.estado='I' AND date_format(cuenta_cobro.fecha_pago, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND cuenta_cobro.puntoventa_id != 0
  GROUP BY date_format(cuenta_cobro.fecha_pago, '%Y-%m-%d'), cuenta_cobro.usuario_id,cuenta_cobro.puntoventa_id
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
    0 puntoventa_id
  FROM cuenta_cobro
    WHERE cuenta_cobro.estado='I' AND date_format(cuenta_cobro.fecha_pago, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND cuenta_cobro.puntoventa_id = 0  AND cuenta_cobro.transproducto_id = 0
  GROUP BY date_format(cuenta_cobro.fecha_pago, '%Y-%m-%d'), cuenta_cobro.usuario_id,cuenta_cobro.puntoventa_id
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
    COUNT(*) cantidad
  FROM cuenta_cobro

    WHERE date_format(cuenta_cobro.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "'

  GROUP BY date_format(cuenta_cobro.fecha_crea, '%Y-%m-%d'), cuenta_cobro.usuario_id
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
    COUNT(*) cantidad
  FROM cuenta_cobro

    WHERE date_format(cuenta_cobro.fecha_accion, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND cuenta_cobro.estado ='E'

  GROUP BY date_format(cuenta_cobro.fecha_accion, '%Y-%m-%d'), cuenta_cobro.usuario_id
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
    COUNT(*) cantidad
  FROM cuenta_cobro

    WHERE date_format(cuenta_cobro.fecha_accion, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND cuenta_cobro.estado ='R'

  GROUP BY date_format(cuenta_cobro.fecha_accion, '%Y-%m-%d'), cuenta_cobro.usuario_id
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
    COUNT(*) cantidad
  FROM cuenta_cobro

    WHERE cuenta_cobro.estado IN ('A','M')

  GROUP BY  cuenta_cobro.usuario_id
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
    COUNT(*) cantidad
  FROM cuenta_cobro


    WHERE cuenta_cobro.estado='I' AND date_format(cuenta_cobro.fecha_pago, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND cuenta_cobro.puntoventa_id != 0

  GROUP BY date_format(cuenta_cobro.fecha_pago, '%Y-%m-%d'), cuenta_cobro.puntoventa_id
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
    transaccion_producto.producto_id producto_id
  FROM cuenta_cobro
  
  INNER JOIN transaccion_producto ON (transaccion_producto.transproducto_id = cuenta_cobro.transproducto_id)


    WHERE   cuenta_cobro.estado = 'I' AND date_format(cuenta_cobro.fecha_pago, '%Y-%m-%d') = '" . $fechaSoloDia . "' 

  GROUP BY date_format(cuenta_cobro.fecha_pago, '%Y-%m-%d'),cuenta_cobro.usuario_id, transaccion_producto.producto_id
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
      0 usumodif_id,
      'A' estado,
      tipo tipo,
  COUNT(*) cantidad
FROM it_transaccion
  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = it_transaccion.usuario_id)

    WHERE date_format(it_transaccion.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND usuario_perfil.perfil_id = \"USUONLINE\" 
GROUP BY date_format(it_transaccion.fecha_crea, '%Y-%m-%d'),it_transaccion.tipo, it_transaccion.usuario_id
ORDER BY date_format(it_transaccion.fecha_crea, '%Y-%m-%d'),it_transaccion.tipo,it_transaccion.usuario_id;";

    $sqlApuestasDeportivasPuntoVentaDia = "
SELECT
  it_ticket_enc.usuario_id usuarioId,
  SUM(it_ticket_enc.vlr_apuesta) valor,
  date_format(it_ticket_enc.fecha_crea, '%Y-%m-%d')fecha_crea,
  0 usucrea_id,
  0 usumodif_id ,
  'A' estado,
  '1' tipo,
  COUNT(*) cantidad
FROM it_ticket_enc
  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = it_ticket_enc.usuario_id)

    WHERE (it_ticket_enc.fecha_crea) = '" . $fechaSoloDia . "'  AND it_ticket_enc.eliminado ='N'AND usuario_perfil.perfil_id  != 'USUONLINE'
GROUP BY (it_ticket_enc.fecha_crea), it_ticket_enc.usuario_id
ORDER BY (it_ticket_enc.fecha_crea),it_ticket_enc.usuario_id;";

    $sqlApuestasDeportivasPuntoVentaoDiaCierre = "
SELECT
  it_ticket_enc.usuario_id usuarioId,
  SUM(it_ticket_enc.vlr_premio) valor,
  it_ticket_enc.fecha_cierre fecha_crea,
  0 usucrea_id,
  0 usumodif_id,
  'A' estado,
  '2' tipo,
  COUNT(*) cantidad
FROM it_ticket_enc
  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = it_ticket_enc.usuario_id)

    WHERE (it_ticket_enc.fecha_cierre) = '" . $fechaSoloDia . "' AND usuario_perfil.perfil_id  != 'USUONLINE' AND it_ticket_enc.premiado='S' AND it_ticket_enc.eliminado='N'

GROUP BY it_ticket_enc.fecha_cierre, it_ticket_enc.usuario_id
ORDER BY it_ticket_enc.usuario_id;";

    $sqlPremiosDeportivasPuntoVentaoDia = "
SELECT
  it_ticket_enc.usuario_id usuarioId,
  SUM(it_ticket_enc.vlr_premio) valor,
  it_ticket_enc.fecha_cierre fecha_crea,
  0 usucrea_id,
  0 usumodif_id,
  'P' estado,
  '2' tipo,
  COUNT(*) cantidad
FROM it_ticket_enc
  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = it_ticket_enc.usuario_id)

    WHERE (it_ticket_enc.fecha_cierre) = '" . $fechaSoloDia . "' AND usuario_perfil.perfil_id  != 'USUONLINE' AND it_ticket_enc.premiado='S' AND it_ticket_enc.eliminado='N'

GROUP BY it_ticket_enc.fecha_cierre, it_ticket_enc.usuario_id
ORDER BY it_ticket_enc.usuario_id;";


    $sqlPremiosPagadosDeportivasPuntoVentaoDia = "
SELECT
  it_ticket_enc.usuario_id usuarioId,
  SUM(it_ticket_enc.vlr_premio-it_ticket_enc.impuesto) valor,
  it_ticket_enc.fecha_pago fecha_crea,
  0 usucrea_id,
  0 usumodif_id,
  'P' estado,
  '3' tipo,
  COUNT(*) cantidad
FROM it_ticket_enc
  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = it_ticket_enc.usuario_id)

    WHERE (it_ticket_enc.fecha_pago) = '" . $fechaSoloDia . "' AND usuario_perfil.perfil_id  != 'USUONLINE' AND it_ticket_enc.premiado='S' AND it_ticket_enc.eliminado='N'

GROUP BY it_ticket_enc.fecha_pago, it_ticket_enc.usuario_id
ORDER BY it_ticket_enc.usuario_id;";

    $sqlPremiosDeportivasPuntoVentaoDiaCONTIPO = "
SELECT
  it_transaccion.usuario_id usuarioId,
  SUM(it_transaccion.valor) valor,
  it_transaccion.fecha_crea fecha_crea,
  0 usucrea_id,
  0 usumodif_id,
  'A' estado,
  tipo tipo,
  COUNT(*) cantidad
FROM it_ticket_enc
  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = it_ticket_enc.usuario_id)

    WHERE date_format(it_transaccion.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND usuario_perfil.perfil_id  != 'USUONLINE'

GROUP BY it_transaccion.fecha_crea, it_transaccion.usuario_id
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
        INNER JOIN transaccion_juego ON (transjuego_log.transjuego_id =transaccion_juego.transjuego_id)
             INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usumandante_id)

    WHERE date_format(transjuego_log.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND transjuego_log.tipo LIKE '%DEBIT%'
GROUP BY transaccion_juego.tipo,date_format(transjuego_log.fecha_crea, '%Y-%m-%d'), transaccion_juego.usuario_id
ORDER BY date_format(transjuego_log.fecha_crea, '%Y-%m-%d'),transaccion_juego.usuario_id;";

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
        INNER JOIN transaccion_juego ON (transjuego_log.transjuego_id =transaccion_juego.transjuego_id)
             INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usumandante_id)

    WHERE date_format(transjuego_log.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND (transjuego_log.tipo LIKE '%CREDIT%'OR transjuego_log.tipo like '%ROLLBACK%')
GROUP BY transaccion_juego.tipo,date_format(transjuego_log.fecha_crea, '%Y-%m-%d'), transaccion_juego.usuario_id
ORDER BY date_format(transjuego_log.fecha_crea, '%Y-%m-%d'),transaccion_juego.usuario_id;";


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
        INNER JOIN transaccion_juego ON (transjuego_log.transjuego_id =transaccion_juego.transjuego_id)
        INNER JOIN producto_mandante ON (prodmandante_id =transaccion_juego.producto_id)
        INNER JOIN producto ON (producto.producto_id =producto_mandante.producto_id)
        INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usumandante_id)

    WHERE transjuego_log.fecha_crea >= '" . $fecha1 . "' AND transjuego_log.fecha_crea <= '" . $fecha2 . "' AND transjuego_log.tipo LIKE '%DEBIT%'
GROUP BY transaccion_juego.producto_id,date_format(transjuego_log.fecha_crea, '%Y-%m-%d'), transaccion_juego.usuario_id
ORDER BY date_format(transjuego_log.fecha_crea, '%Y-%m-%d'),transaccion_juego.usuario_id;";

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
        INNER JOIN transaccion_juego ON (transjuego_log.transjuego_id =transaccion_juego.transjuego_id)
        INNER JOIN producto_mandante ON (prodmandante_id =transaccion_juego.producto_id)
        INNER JOIN producto ON (producto.producto_id =producto_mandante.producto_id)
        INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usumandante_id)

    WHERE transjuego_log.fecha_crea >= '" . $fecha1 . "' AND transjuego_log.fecha_crea <= '" . $fecha2 . "' AND (transjuego_log.tipo LIKE '%CREDIT%'OR transjuego_log.tipo like '%ROLLBACK%')
GROUP BY transaccion_juego.producto_id,date_format(transjuego_log.fecha_crea, '%Y-%m-%d'), transaccion_juego.usuario_id
ORDER BY date_format(transjuego_log.fecha_crea, '%Y-%m-%d'),transaccion_juego.usuario_id;";


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
  CASE WHEN transaccion_juego.tipo = 'FREESPIN' THEN 'DEBIT' WHEN transaccion_juego.tipo = 'FREECASH' THEN 'DEBITFREECASH' ElSE 'DEBIT' END tipo,
  COUNT(*) cantidad
      FROM transjuego_log
        INNER JOIN transaccion_juego ON (transjuego_log.transjuego_id =transaccion_juego.transjuego_id)
             INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usumandante_id)

    WHERE date_format(transjuego_log.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND transjuego_log.tipo LIKE '%DEBIT%'

GROUP BY date_format(transjuego_log.fecha_crea, '%Y-%m-%d'),transaccion_juego.producto_id, transaccion_juego.usuario_id,transaccion_juego.tipo
ORDER BY date_format(transjuego_log.fecha_crea, '%Y-%m-%d'),transaccion_juego.producto_id,transaccion_juego.usuario_id,transaccion_juego.tipo;";

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
        INNER JOIN transaccion_juego ON (transjuego_log.transjuego_id =transaccion_juego.transjuego_id)
             INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usumandante_id)

    WHERE date_format(transjuego_log.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND (transjuego_log.tipo LIKE '%CREDIT%')

GROUP BY date_format(transjuego_log.fecha_crea, '%Y-%m-%d'),transaccion_juego.producto_id, transaccion_juego.usuario_id,transaccion_juego.tipo
ORDER BY date_format(transjuego_log.fecha_crea, '%Y-%m-%d'),transaccion_juego.producto_id,transaccion_juego.usuario_id,transaccion_juego.tipo;";


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
        INNER JOIN transaccion_juego ON (transjuego_log.transjuego_id =transaccion_juego.transjuego_id)
             INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usumandante_id)

    WHERE date_format(transjuego_log.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND (transjuego_log.tipo like '%ROLLBACK%')

GROUP BY date_format(transjuego_log.fecha_crea, '%Y-%m-%d'),transaccion_juego.producto_id, transaccion_juego.usuario_id
ORDER BY date_format(transjuego_log.fecha_crea, '%Y-%m-%d'),transaccion_juego.producto_id,transaccion_juego.usuario_id;";


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
  and bono_log.fecha_crea != ''
  and date_format(bono_log.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND usuario_perfil.perfil_id = \"USUONLINE\" 

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

    WHERE  date_format(saldo_usuonline_ajuste.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND usuario_perfil.perfil_id = \"USUONLINE\"

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

    WHERE  date_format(cupo_log.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "'

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

    WHERE  date_format(cupo_log.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "'

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

FROM registro
INNER JOIN usuario on (registro.usuario_id = usuario.usuario_id)
WHERE usuario.mandante in (3,4,5,6,7,10,22)
;
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

FROM punto_venta
INNER JOIN usuario on (punto_venta.usuario_id = usuario.usuario_id)

INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = punto_venta.usuario_id) WHERE perfil_id != 'MAQUINAANONIMA'
AND usuario.mandante in (3,4,5,6,7,10,22)
;
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

       SUM(saldo_recarga)                                                        saldo_recarga,
       SUM(saldo_apuestas)                                                       saldo_apuestas,
       SUM(saldo_premios)                                                        saldo_premios,
       SUM(saldo_apuestas_casino)                                                saldo_apuestas_casino,
       SUM(saldo_premios_casino)                                                 saldo_premios_casino,
       SUM(saldo_notaret_pagadas)                                                saldo_notaret_pagadas,
       SUM(saldo_notaret_pend)                                                   saldo_notaret_pend,
       SUM(saldo_notaret_creadas)                                                saldo_notaret_creadas,
       SUM(saldo_ajustes_entrada)                                                saldo_ajustes_entrada,
       SUM(saldo_ajustes_salida)                                                 saldo_ajustes_salida,
       SUM(saldo_bono)                                                           saldo_bono,
       SUM(saldo_notaret_eliminadas)                                             saldo_notaret_eliminadas,
       SUM(saldo_bono_free_ganado)                                             saldo_bono_free_ganado,
       SUM(saldo_bono_casino_free_ganado)                                    saldo_bono_casino_free_ganado
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
               0                                   saldo_apuestas_casino,
               0                                   saldo_premios_casino,
               0                                   saldo_notaret_pagadas,
               0                                   saldo_notaret_pend,
               0                                   saldo_notaret_creadas,
               0                                   saldo_ajustes_entrada,
               0                                   saldo_ajustes_salida,
               0                                   saldo_bono,
               0                                   saldo_notaret_eliminadas,
               0                                   saldo_bono_free_ganado,
               0                                    saldo_bono_casino_free_ganado
        FROM casino.usuario_recarga_resumen
        WHERE DATE_FORMAT(fecha_crea, '%Y-%m-%d')   ='" . $fechaSoloDia . "'
        GROUP BY usuario_id
       )


       UNION

       (SELECT usuario_id,
               0                                                                   saldo_recarga,
               DATE_FORMAT(fecha_crea, '%Y-%m-%d')                                 fecha,
               SUM(CASE WHEN tipo IN ('BET') THEN valor ELSE -valor END) saldo_apuestas,
               0                                                                   saldo_premios,
               0                                                                   saldo_apuestas_casino,
               0                                                                   saldo_premios_casino,
               0                                                                   saldo_notaret_pagadas,
               0                                                                   saldo_notaret_pend,
               0                                                                   saldo_notaret_creadas,
               0                                                                   saldo_ajustes_entrada,
               0                                                                   saldo_ajustes_salida,
               0                                                                   saldo_bono,
               0                                   saldo_notaret_eliminadas,
               0                                   saldo_bono_free_ganado,
               0                                    saldo_bono_casino_free_ganado
        FROM casino.usuario_deporte_resumen
        WHERE tipo IN ('BET', 'STAKEDECREASE', 'REFUND') AND  DATE_FORMAT(fecha_crea, '%Y-%m-%d')   ='" . $fechaSoloDia . "'
        GROUP BY usuario_id
       )
       UNION

       (SELECT usuario_id,
               0                                                                                 saldo_recarga,
               DATE_FORMAT(fecha_crea, '%Y-%m-%d')                                               fecha,
               0                                                                                 saldo_apuestas,
               SUM(CASE WHEN tipo IN ('WIN', 'NEWCREDIT', 'CASHOUT') THEN valor ELSE -valor END) saldo_premios,
               0                                                                                 saldo_apuestas_casino,
               0                                                                                 saldo_premios_casino,
               0                                                                                 saldo_notaret_pagadas,
               0                                                                                 saldo_notaret_pend,
               0                                                                                 saldo_notaret_creadas,
               0                                                                                 saldo_ajustes_entrada,
               0                                                                                 saldo_ajustes_salida,
               0                                                                                 saldo_bono,
               0                                   saldo_notaret_eliminadas,
               0                                   saldo_bono_free_ganado,
               0                                    saldo_bono_casino_free_ganado
        FROM casino.usuario_deporte_resumen
        WHERE tipo IN ('WIN', 'NEWCREDIT', 'CASHOUT', 'NEWDEBIT') AND  DATE_FORMAT(fecha_crea, '%Y-%m-%d')   ='" . $fechaSoloDia . "'
        GROUP BY usuario_id)

       UNION

       (SELECT usuario_mandante,
               0                                   saldo_recarga,
               DATE_FORMAT(usuario_casino_resumen.fecha_crea, '%Y-%m-%d') fecha,
               0                                   saldo_apuestas,
               0                                   saldo_premios,
               SUM(valor)                          saldo_apuestas_casino,
               0                                   saldo_premios_casino,
               0                                   saldo_notaret_pagadas,
               0                                   saldo_notaret_pend,
               0                                   saldo_notaret_creadas,
               0                                   saldo_ajustes_entrada,
               0                                   saldo_ajustes_salida,
               0                                   saldo_bono,
               0                                   saldo_notaret_eliminadas,
               0                                   saldo_bono_free_ganado,
               SUM(valor_premios)                       saldo_bono_casino_free_ganado
        FROM casino.usuario_casino_resumen
                                 INNER JOIN usuario_mandante ON (usumandante_id=usuario_id)
        WHERE tipo IN ('1','4','6') AND  DATE_FORMAT(usuario_casino_resumen.fecha_crea, '%Y-%m-%d')   ='" . $fechaSoloDia . "'
        GROUP BY usuario_id
       )

       UNION
       (
         SELECT usuario_mandante,
                0                                   saldo_recarga,
                DATE_FORMAT(usuario_casino_resumen.fecha_crea, '%Y-%m-%d') fecha,
                0                                   saldo_apuestas,
                0                                   saldo_premios,
                0                                   saldo_apuestas_casino,
                SUM(valor)                          saldo_premios_casino,
                0                                   saldo_notaret_pagadas,
                0                                   saldo_notaret_pend,
                0                                   saldo_notaret_creadas,
                0                                   saldo_ajustes_entrada,
                0                                   saldo_ajustes_salida,
                0                                   saldo_bono,
               0                                   saldo_notaret_eliminadas,
               0                                   saldo_bono_free_ganado,
               0                                    saldo_bono_casino_free_ganado
         FROM casino.usuario_casino_resumen
                         INNER JOIN usuario_mandante ON (usumandante_id=usuario_id)

         WHERE tipo IN ('2','5','7') AND  DATE_FORMAT(usuario_casino_resumen.fecha_crea, '%Y-%m-%d')   ='" . $fechaSoloDia . "'
         GROUP BY usuario_id
       )

       UNION

       (SELECT usuario_id,
               0                                   saldo_recarga,
               DATE_FORMAT(fecha_crea, '%Y-%m-%d') fecha,
               0                                   saldo_apuestas,
               0                                   saldo_premios,
               0                                   saldo_apuestas_casino,
               0                                   saldo_premios_casino,
               SUM(valor)                          saldo_notaret_pagadas,
               0                                   saldo_notaret_pend,
               0                                   saldo_notaret_creadas,
               0                                   saldo_ajustes_entrada,
               0                                   saldo_ajustes_salida,
               0                                   saldo_bono,
               0                                   saldo_notaret_eliminadas,
               0                                   saldo_bono_free_ganado,
               0                                    saldo_bono_casino_free_ganado
        FROM casino.usuario_retiro_resumen
        WHERE estado = 'I' AND  DATE_FORMAT(usuario_retiro_resumen.fecha_crea, '%Y-%m-%d')   ='" . $fechaSoloDia . "'
        GROUP BY usuario_id
       )
       UNION

       (SELECT usuario_id,
               0                                   saldo_recarga,
               DATE_FORMAT(fecha_crea, '%Y-%m-%d') fecha,
               0                                   saldo_apuestas,
               0                                   saldo_premios,
               0                                   saldo_apuestas_casino,
               0                                   saldo_premios_casino,
               0                                   saldo_notaret_pagadas,
               SUM(valor)                          saldo_notaret_pend,
               0                                   saldo_notaret_creadas,
               0                                   saldo_ajustes_entrada,
               0                                   saldo_ajustes_salida,
               0                                   saldo_bono,
               0                                   saldo_notaret_eliminadas,
               0                                   saldo_bono_free_ganado,
               0                                    saldo_bono_casino_free_ganado
        FROM casino.usuario_retiro_resumen
        WHERE estado = 'P' AND  DATE_FORMAT(usuario_retiro_resumen.fecha_crea, '%Y-%m-%d')   ='" . $fechaSoloDia . "'
        GROUP BY usuario_id
       )
       UNION

       (SELECT usuario_id,
               0                                   saldo_recarga,
               DATE_FORMAT(fecha_crea, '%Y-%m-%d') fecha,
               0                                   saldo_apuestas,
               0                                   saldo_premios,
               0                                   saldo_apuestas_casino,
               0                                   saldo_premios_casino,
               0                                   saldo_notaret_pagadas,
               0                                   saldo_notaret_pend,
               SUM(valor)                          saldo_notaret_creadas,
               0                                   saldo_ajustes_entrada,
               0                                   saldo_ajustes_salida,
               0                                   saldo_bono,
               0                                   saldo_notaret_eliminadas,
               0                                   saldo_bono_free_ganado,
               0                                    saldo_bono_casino_free_ganado
        FROM casino.usuario_retiro_resumen
        WHERE estado = 'A' AND  DATE_FORMAT(fecha_crea, '%Y-%m-%d')   ='" . $fechaSoloDia . "'
        GROUP BY usuario_id
       )

       UNION

       (SELECT usuario_id,
               0                                   saldo_recarga,
               DATE_FORMAT(fecha_crea, '%Y-%m-%d') fecha,
               0                                   saldo_apuestas,
               0                                   saldo_premios,
               0                                   saldo_apuestas_casino,
               0                                   saldo_premios_casino,
               0                                   saldo_notaret_pagadas,
               0                                   saldo_notaret_pend,
               0                                   saldo_notaret_creadas,

               SUM(valor)                          saldo_ajustes_entrada,
               0                                   saldo_ajustes_salida,
               0                                   saldo_bono,
               0                                   saldo_notaret_eliminadas,
               0                                   saldo_bono_free_ganado,
               0                                    saldo_bono_casino_free_ganado
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
               0                                   saldo_apuestas_casino,
               0                                   saldo_premios_casino,
               0                                   saldo_notaret_pagadas,
               0                                   saldo_notaret_pend,
               0                                   saldo_notaret_creadas,
               0                                   saldo_ajustes_entrada,
               SUM(valor)                          saldo_ajustes_salida,
               0                                   saldo_bono,
               0                                   saldo_notaret_eliminadas,
               0                                   saldo_bono_free_ganado,
               0                                    saldo_bono_casino_free_ganado
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
               0                                                    saldo_apuestas_casino,
               0                                                    saldo_premios_casino,
               0                                                    saldo_notaret_pagadas,
               0                                                    saldo_notaret_pend,
               0                                                    saldo_notaret_creadas,
               0                                                    saldo_ajustes_entrada,
               0                                                    saldo_ajustes_salida,
               SUM(CASE when estado = 'L' then valor else 0 end) saldo_bono,
               0                                   saldo_notaret_eliminadas,
               0                                   saldo_bono_free_ganado,
               0                                    saldo_bono_casino_free_ganado
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
               0                                   saldo_apuestas_casino,
               0                                   saldo_premios_casino,
               0                                   saldo_notaret_pagadas,
               0                                   saldo_notaret_pend,
               0                          saldo_notaret_creadas,
               0                                   saldo_ajustes_entrada,
               0                                   saldo_ajustes_salida,
               0                                   saldo_bono,
               SUM(valor)                                   saldo_notaret_eliminadas,
               0                                   saldo_bono_free_ganado,
               0                                    saldo_bono_casino_free_ganado
        FROM casino.usuario_retiro_resumen
        WHERE (estado = 'R' OR estado = 'E') AND  DATE_FORMAT(fecha_crea, '%Y-%m-%d')   ='" . $fechaSoloDia . "'
        GROUP BY usuario_id
       )

       UNION

       (SELECT usuario_id,
               0                                                    saldo_recarga,
               DATE_FORMAT(fecha_crea, '%Y-%m-%d')                  fecha,
               0                                                    saldo_apuestas,
               0                                                    saldo_premios,
               0                                                    saldo_apuestas_casino,
               0                                                    saldo_premios_casino,
               0                                                    saldo_notaret_pagadas,
               0                                                    saldo_notaret_pend,
               0                                                    saldo_notaret_creadas,
               0                                                    saldo_ajustes_entrada,
               0                                                    saldo_ajustes_salida,
               0 saldo_bono,
               0                                   saldo_notaret_eliminadas,
               SUM(CASE when tipo = 'W' then valor else 0 end)  saldo_bono_free_ganado,
               0                                    saldo_bono_casino_free_ganado
        FROM casino.usuario_bono_resumen
        WHERE DATE_FORMAT(fecha_crea, '%Y-%m-%d')   ='" . $fechaSoloDia . "'
        GROUP BY usuario_id
       )
       
     ) data
     INNER JOIN usuario ON (data.usuario_id = usuario.usuario_id)
     INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = usuario.usuario_id)
       LEFT OUTER JOIN (select usuario_id,saldo_inicial,saldo_creditos_inicial,saldo_creditos_base_inicial
                        from usuario_saldo
                        WHERE fecha=DATE_FORMAT(DATE_ADD('" . $fechaSoloDia . "', INTERVAL 1 DAY), '%Y-%m-%d')) data2
                       ON (data.usuario_id = data2.usuario_id)

WHERE data.usuario_id IS NOT NULL AND usuario_perfil.perfil_id = 'USUONLINE'
  AND data.fecha = '" . $fechaSoloDia . "'

GROUP BY data.usuario_id;

";


    $UsuarioPuntoVentaFinalConDetalles = "
SELECT data.usuario_id,
       usuario.mandante,
       DATE_FORMAT(data.fecha, '%Y-%m-%d')                                       fecha,

       SUM(saldo_recarga)                                                        saldo_recarga,
       SUM(saldo_apuestas)                                                       saldo_apuestas,
       SUM(saldo_premios)                                                        saldo_premios,
       SUM(saldo_apuestas_casino)                                                saldo_apuestas_casino,
       SUM(saldo_premios_casino)                                                 saldo_premios_casino,
       SUM(saldo_notaret_pagadas)                                                saldo_notaret_pagadas,
       SUM(saldo_notaret_pend)                                                   saldo_notaret_pend,
       SUM(saldo_notaret_creadas)                                                saldo_notaret_creadas,
       SUM(saldo_ajustes_entrada)                                                saldo_ajustes_entrada,
       SUM(saldo_ajustes_salida)                                                 saldo_ajustes_salida,
       SUM(saldo_bono)                                                           saldo_bono,
       SUM(saldo_notaret_eliminadas)                                             saldo_notaret_eliminadas,
       SUM(saldo_bono_free_ganado)                                             saldo_bono_free_ganado,
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
        WHERE DATE_FORMAT(fecha_crea, '%Y-%m-%d')   ='" . $fechaSoloDia . "' AND estado='A'
        GROUP BY usuario_id
       )
       UNION
        
        (SELECT usuario_id,
               0                         saldo_recarga,
               DATE_FORMAT(fecha_crea, '%Y-%m-%d') fecha,

               0                                   saldo_apuestas,
               0                                   saldo_premios,
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
        WHERE DATE_FORMAT(fecha_crea, '%Y-%m-%d')   ='" . $fechaSoloDia . "' AND estado='I'
        GROUP BY usuario_id
       )

       UNION

       (SELECT usuario_id,
               0                                                                   saldo_recarga,
               DATE_FORMAT(fecha_crea, '%Y-%m-%d')                                 fecha,
               SUM(valor) saldo_apuestas,
               0                                                                   saldo_premios,
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
        WHERE tipo ='1' AND  DATE_FORMAT(fecha_crea, '%Y-%m-%d')   ='" . $fechaSoloDia . "'
        GROUP BY usuario_id
       )
       UNION

       (SELECT usuario_id,
               0                                                                                 saldo_recarga,
               DATE_FORMAT(fecha_crea, '%Y-%m-%d')                                               fecha,
               0                                                                                 saldo_apuestas,
               SUM(valor) saldo_premios,
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
        WHERE tipo = '3'   AND  DATE_FORMAT(fecha_crea, '%Y-%m-%d')   ='" . $fechaSoloDia . "'
        GROUP BY usuario_id)

       UNION

       (SELECT usuario_id,
               0                                   saldo_recarga,
               DATE_FORMAT(fecha_crea, '%Y-%m-%d') fecha,
               0                                   saldo_apuestas,
               0                                   saldo_premios,
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
        WHERE estado = 'I' AND  DATE_FORMAT(usuario_retiro_resumen.fecha_crea, '%Y-%m-%d')   ='" . $fechaSoloDia . "'
        GROUP BY usuario_id
       )
       
       
       UNION

       (SELECT usuario_id,
               0                                   saldo_recarga,
               DATE_FORMAT(fecha_crea, '%Y-%m-%d') fecha,
               0                                   saldo_apuestas,
               0                                   saldo_premios,
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
        WHERE tipo = 'E' AND tipo_ajuste = 'R' AND DATE_FORMAT(fecha_crea, '%Y-%m-%d')   ='" . $fechaSoloDia . "'
        GROUP BY usuario_id
       )
       UNION

       (SELECT usuario_id,
               0                                   saldo_recarga,
               DATE_FORMAT(fecha_crea, '%Y-%m-%d') fecha,
               0                                   saldo_apuestas,
               0                                   saldo_premios,
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
        WHERE tipo = 'S' AND tipo_ajuste = 'R' AND  DATE_FORMAT(fecha_crea, '%Y-%m-%d')   ='" . $fechaSoloDia . "'
        GROUP BY usuario_id
       )
       
       
       
       UNION

       (SELECT usuario_id,
               0                                   saldo_recarga,
               DATE_FORMAT(fecha_crea, '%Y-%m-%d') fecha,
               0                                   saldo_apuestas,
               0                                   saldo_premios,
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
        WHERE tipo = 'E' AND tipo_ajuste = 'A' AND DATE_FORMAT(fecha_crea, '%Y-%m-%d')   ='" . $fechaSoloDia . "'
        GROUP BY usuario_id
       )
       UNION

       (SELECT usuario_id,
               0                                   saldo_recarga,
               DATE_FORMAT(fecha_crea, '%Y-%m-%d') fecha,
               0                                   saldo_apuestas,
               0                                   saldo_premios,
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
        WHERE tipo = 'S' AND tipo_ajuste = 'A' AND  DATE_FORMAT(fecha_crea, '%Y-%m-%d')   ='" . $fechaSoloDia . "'
        GROUP BY usuario_id
       )
       
     ) data
     INNER JOIN usuario ON (data.usuario_id = usuario.usuario_id)
     INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = usuario.usuario_id)
       LEFT OUTER JOIN (select usuario_id,saldo_inicial,saldo_creditos_inicial,saldo_creditos_base_inicial
                        from usuario_saldo
                        WHERE fecha=DATE_FORMAT(DATE_ADD('" . $fechaSoloDia . "', INTERVAL 1 DAY), '%Y-%m-%d')) data2
                       ON (data.usuario_id = data2.usuario_id)

WHERE data.usuario_id IS NOT NULL AND usuario_perfil.perfil_id != 'USUONLINE'
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

    $BonoInterno = new BonoInterno();
    $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
    $transaccion = $BonoDetalleMySqlDAO->getTransaction();



    if (true) {

        $message = "*CRON: (Fin) * " . " Resumenes PASO1 - Fecha: " . date("Y-m-d H:i:s");
        exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron-error-urg' > /dev/null & ");


        $message = "*CRON: (Fin) * " . " Resumenes PASO2 - Fecha: " . date("Y-m-d H:i:s");
        exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron-error-urg' > /dev/null & ");

        $primerMetodoDeInsercion = true;
        //$BonoInterno->execQuery($transaccion, $sql);
        if($primerMetodoDeInsercion){
            $BonoInternoMySqlDAO = new BonoInternoMySqlDAO($transaccion);

            if(true) {
                $transaccion->commit();
                $_ENV["NEEDINSOLATIONLEVEL"] ='1';

                $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
                $transaccion = $BonoDetalleMySqlDAO->getTransaction();

                $sqlUpdate = 'UPDATE usuario_saldo
SET usuario_saldo.saldo_final=usuario_saldo.saldo_inicial,
    usuario_saldo.saldo_creditos_final = usuario_saldo.saldo_creditos_inicial,
    usuario_saldo.saldo_creditos_base_final = usuario_saldo.saldo_creditos_base_inicial
WHERE usuario_saldo.mandante in (3, 4, 5, 6, 7, 10, 22, 25);';

                $BonoInterno->execQuery($transaccion, $sqlUpdate);
                $transaccion->commit();
                $_ENV["NEEDINSOLATIONLEVEL"] ='';

                $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
                $transaccion = $BonoDetalleMySqlDAO->getTransaction();

            }

            //INSERCION DE USUARIOS NUEVOS EN LA TABLA
            if (false) {


                $sqlInsert = 'INSERT INTO usuario_saldo
(usuario_id, mandante, fecha, saldo_recarga, saldo_apuestas, saldo_premios, saldo_notaret_pagadas, saldo_notaret_pend, saldo_ajustes_entrada, saldo_ajustes_salida, saldo_inicial, saldo_final, saldo_bono, saldo_creditos_inicial, saldo_creditos_base_inicial, saldo_creditos_final, saldo_creditos_base_final, saldo_notaret_creadas, saldo_apuestas_casino, saldo_premios_casino, saldo_notaret_eliminadas, saldo_bono_free_ganado, saldo_bono_casino_free_ganado, saldo_bono_casino_vivo, saldo_bono_casino_vivo_free_ganado, saldo_bono_virtual, saldo_bono_virtual_free_ganado, billetera_id, saldo_apuestas_casino_vivo)

SELECT 
usuario_id, mandante, "' . date("Y-m-d", strtotime('-1 days')) . '", 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0


FROM usuario where usuario.fecha_crea like "' . date("Y-m-d", strtotime('-1 days')) . '%" and   usuario.mandante in (3, 4, 5, 6, 7, 10, 22, 25);';


                $sqlInsert = '
SELECT 
usuario_id, mandante, "' . date("Y-m-d", strtotime('-1 days')) . '", 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0


FROM usuario where usuario.fecha_crea like "' . date("Y-m-d", strtotime('-1 days')) . '%" and   usuario.mandante in (3, 4, 5, 6, 7, 10, 22, 25);';


                $datosUsuariosNuevos = $BonoInterno->execQuery($transaccion, $sqlInsert);


                foreach ($datosUsuariosNuevos as $datanum) {
                    $sql = "
                    
                    INSERT INTO usuario_saldo
(usuario_id, mandante, fecha, saldo_recarga, saldo_apuestas, saldo_premios, saldo_notaret_pagadas, saldo_notaret_pend, saldo_ajustes_entrada, saldo_ajustes_salida, saldo_inicial, saldo_final, saldo_bono, saldo_creditos_inicial, saldo_creditos_base_inicial, saldo_creditos_final, saldo_creditos_base_final, saldo_notaret_creadas, saldo_apuestas_casino, saldo_premios_casino, saldo_notaret_eliminadas, saldo_bono_free_ganado, saldo_bono_casino_free_ganado, saldo_bono_casino_vivo, saldo_bono_casino_vivo_free_ganado, saldo_bono_virtual, saldo_bono_virtual_free_ganado, billetera_id, saldo_apuestas_casino_vivo

                        ) VALUES (
                                '" . $datanum->{'usuario.usuario_id'} . "', '" . $datanum->{'usuario.mandante'} . "', '" . date("Y-m-d", strtotime('-1 days')) . "', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0
                                );";

                    $BonoInterno->execQuery($transaccion, $sql);
                }
            }

            $transaccion->commit();
            $_ENV["NEEDINSOLATIONLEVEL"] ='1';
            $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
            $transaccion = $BonoDetalleMySqlDAO->getTransaction();

            $sqlHistorial="
            SELECT max(usuhistorial_id) usuhistorial_id
FROM usuario_historial
         inner join usuario_perfil on usuario_perfil.usuario_id = usuario_historial.usuario_id
where usuario_perfil.mandante in (3, 4, 5, 6, 7, 10, 22, 25)
  and usuario_historial.fecha_crea >= '".$fecha1."'
  and usuario_historial.fecha_crea <= '".$fecha2."'

group by usuario_perfil.usuario_id;
";

            $sqlHistorial="SET group_concat_max_len = 18446744073709551615;";
            $dataHistorial = $BonoInternoMySqlDAO->querySQL($sqlHistorial);

            $sqlHistorial="
           SELECT GROUP_CONCAT(usuhistorial_id SEPARATOR ', ')  usuhistorial_id

                  FROM ( SELECT max(usuhistorial_id)  usuhistorial_id
FROM usuario_historial
         inner join usuario_perfil on usuario_perfil.usuario_id = usuario_historial.usuario_id
where usuario_perfil.mandante in (3, 4, 5, 6, 7, 10, 22, 25)
  and usuario_historial.fecha_crea >= '".$fecha1."'
  and usuario_historial.fecha_crea <= '".$fecha2."'

group by usuario_perfil.usuario_id) a;
";


            $dataHistorial = $BonoInternoMySqlDAO->querySQL($sqlHistorial);

            $usuHistorialIds2 = '';
            foreach ($dataHistorial[0] as $item) {
                $usuHistorialIds2 = $item;
            }

            $usuHistorialIds2 = explode(',', $usuHistorialIds2);


            $contUsu = 0;
            $usuHistorialIds = '0';
            foreach ($usuHistorialIds2 as $item) {
                if ($contUsu >= 1000) {
                    if ($usuHistorialIds != '0' && $usuHistorialIds != '0,' ) {


                        $sqlHistorial = "
            UPDATE usuario_saldo,usuario_historial
SET usuario_saldo.saldo_final=usuario_historial.creditos+usuario_historial.creditos_base,
    usuario_saldo.saldo_creditos_final = usuario_historial.creditos,
    usuario_saldo.saldo_creditos_base_final = usuario_historial.creditos_base
where usuario_historial.usuario_id=usuario_saldo.usuario_id
AND usuhistorial_id in ( " . $usuHistorialIds . ");
";
                        $_ENV["NEEDINSOLATIONLEVEL"] = '1';

                        $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
                        $transaccion = $BonoDetalleMySqlDAO->getTransaction();

                        $BonoInterno->execUpdate($transaccion, $sqlHistorial);
                        $transaccion->commit();
                        $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
                        $transaccion = $BonoDetalleMySqlDAO->getTransaction();

                        $usuHistorialIds = '0';

                    }

                }
                $usuHistorialIds = $usuHistorialIds . ',' . $item;
                $contUsu++;
            }

            if ($usuHistorialIds != '0' && $usuHistorialIds != '0,') {


                $sqlHistorial = "
            UPDATE usuario_saldo,usuario_historial
SET usuario_saldo.saldo_final=usuario_historial.creditos+usuario_historial.creditos_base,
    usuario_saldo.saldo_creditos_final = usuario_historial.creditos,
    usuario_saldo.saldo_creditos_base_final = usuario_historial.creditos_base
where usuario_historial.usuario_id=usuario_saldo.usuario_id
AND usuhistorial_id in ( " . $usuHistorialIds . ");
";

                $BonoInterno->execUpdate($transaccion, $sqlHistorial);
                $transaccion->commit();
                $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
                $transaccion = $BonoDetalleMySqlDAO->getTransaction();

                $usuHistorialIds = '0';

            }
            $message = "*CRON: (Fin) * " . " Resumenes PASO2-5 - Fecha: " . date("Y-m-d H:i:s");
            exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron-error-urg' > /dev/null & ");


            //AGREGAMOS TODOS LOS USUARIOS A LA NUEVA TABLA
            if(true) {


                $newTableUsuarioSaldo = "usuario_saldo_" . date("Y_m_d") . "";


                $sqlHistorial = '
    SELECT 
usuario_saldo.usuario_id, usuario_saldo.mandante, "' . date("Y-m-d") . '", 0, 0, 0, 0, 0, 0, 0, usuario_saldo.saldo_final, 0, 0, usuario_saldo.saldo_creditos_final, usuario_saldo.saldo_creditos_base_final, 0,0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, usuario_saldo.billetera_id, 0


FROM usuario_saldo  


where  usuario_saldo.mandante in (3, 4, 5, 6, 7, 10, 22, 25);

';
                $usuHistorialIds2 = $BonoInternoMySqlDAO->querySQL($sqlHistorial);

                $BonoInterno = new BonoInterno();
                $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
                $transaccion = $BonoDetalleMySqlDAO->getTransaction();
                $cont = 0;
                $contG = 0;

                $sqlInsert = 'INSERT INTO ' . $newTableUsuarioSaldo . ' 
(usuario_id, mandante, fecha, saldo_recarga, saldo_apuestas, saldo_premios, saldo_notaret_pagadas, saldo_notaret_pend, saldo_ajustes_entrada, saldo_ajustes_salida, saldo_inicial, saldo_final, saldo_bono, saldo_creditos_inicial, saldo_creditos_base_inicial, saldo_creditos_final, saldo_creditos_base_final, saldo_notaret_creadas, saldo_apuestas_casino, saldo_premios_casino, saldo_notaret_eliminadas, saldo_bono_free_ganado, saldo_bono_casino_free_ganado, saldo_bono_casino_vivo, saldo_bono_casino_vivo_free_ganado, saldo_bono_virtual, saldo_bono_virtual_free_ganado, billetera_id, saldo_apuestas_casino_vivo)
 VALUES ';
                foreach ($usuHistorialIds2 as $item) {
                    //print_r($cont);
                    //print_r(PHP_EOL);

                    try {

                        if ($cont == 0) {
                            $sqlInsert .= ' (
        "' . $item['usuario_saldo.usuario_id'] . '", "' . $item['usuario_saldo.mandante'] . '", "' . date("Y-m-d") . '", 0, 0, 0, 0, 0, 0, 0, "' . $item['usuario_saldo.saldo_final'] . '", 0, 0, "' . $item['usuario_saldo.saldo_creditos_final'] . '", "' . $item['usuario_saldo.saldo_creditos_base_final'] . '", 0,0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, "' . $item['usuario_saldo.billetera_id'] . '", 0)

';
                        } else {

                            $sqlInsert .= ', (
        "' . $item['usuario_saldo.usuario_id'] . '", "' . $item['usuario_saldo.mandante'] . '", "' . date("Y-m-d") . '", 0, 0, 0, 0, 0, 0, 0, "' . $item['usuario_saldo.saldo_final'] . '", 0, 0, "' . $item['usuario_saldo.saldo_creditos_final'] . '", "' . $item['usuario_saldo.saldo_creditos_base_final'] . '", 0,0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, "' . $item['usuario_saldo.billetera_id'] . '", 0)

';
                        }
                        $cont++;

                        if ($cont == 10000) {
                            print_r($cont);

                            $BonoInterno->execQuery($transaccion, $sqlInsert);


                            $transaccion->commit();

                            usleep(500);


                            $sqlInsert = 'INSERT INTO ' . $newTableUsuarioSaldo . ' 
(usuario_id, mandante, fecha, saldo_recarga, saldo_apuestas, saldo_premios, saldo_notaret_pagadas, saldo_notaret_pend, saldo_ajustes_entrada, saldo_ajustes_salida, saldo_inicial, saldo_final, saldo_bono, saldo_creditos_inicial, saldo_creditos_base_inicial, saldo_creditos_final, saldo_creditos_base_final, saldo_notaret_creadas, saldo_apuestas_casino, saldo_premios_casino, saldo_notaret_eliminadas, saldo_bono_free_ganado, saldo_bono_casino_free_ganado, saldo_bono_casino_vivo, saldo_bono_casino_vivo_free_ganado, saldo_bono_virtual, saldo_bono_virtual_free_ganado, billetera_id, saldo_apuestas_casino_vivo)
 VALUES ';

                            $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
                            $transaccion = $BonoDetalleMySqlDAO->getTransaction();
                            $cont = 0;
                        }
                    } catch (Exception $e) {

                        $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
                        $transaccion = $BonoDetalleMySqlDAO->getTransaction();
                    }
                    $contG++;

                }

                if($cont>0){
                    try{
                        $BonoInterno->execQuery($transaccion, $sqlInsert);



                        $transaccion->commit();
                        $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
                        $transaccion = $BonoDetalleMySqlDAO->getTransaction();

                    } catch (Exception $e) {
                    }
                }

                if(true){
                    try{
                        $sqlInsert='INSERT INTO ' . $newTableUsuarioSaldo . ' 
(usuario_id, mandante, fecha, saldo_recarga, saldo_apuestas, saldo_premios, saldo_notaret_pagadas, saldo_notaret_pend,
 saldo_ajustes_entrada, saldo_ajustes_salida, saldo_inicial, saldo_final, saldo_bono, saldo_creditos_inicial,
 saldo_creditos_base_inicial, saldo_creditos_final, saldo_creditos_base_final, saldo_notaret_creadas,
 saldo_apuestas_casino, saldo_premios_casino, saldo_notaret_eliminadas, saldo_bono_free_ganado,
 saldo_bono_casino_free_ganado, saldo_bono_casino_vivo, saldo_bono_casino_vivo_free_ganado, saldo_bono_virtual,
 saldo_bono_virtual_free_ganado, billetera_id, saldo_apuestas_casino_vivo)
select usuario_saldo.usuario_id,
       usuario_saldo.mandante,
       "' . date("Y-m-d") . '",
       0,
       0,
       0,
       0,
       0,
       0,
       0,
       usuario_saldo.saldo_final,
       0,
       0,
       usuario_saldo.saldo_creditos_final,
       usuario_saldo.saldo_creditos_base_final,
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
       usuario_saldo.billetera_id,
       0
from usuario_saldo
         left outer join ' . $newTableUsuarioSaldo . ' u2 on u2.usuario_id = usuario_saldo.usuario_id
where u2.usuario_id is null
  and usuario_saldo.mandante in (3, 4, 5, 6, 7, 10, 22, 25);';
                        $BonoInterno->execQuery($transaccion, $sqlInsert);



                        $transaccion->commit();
                        $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
                        $transaccion = $BonoDetalleMySqlDAO->getTransaction();

                    } catch (Exception $e) {
                    }
                }


            }

            if(false) {
                $dataSaldoInicial2 = $BonoInternoMySqlDAO->querySQL($UsuarioSaldoInicialPuntoVenta);


                //$dataSaldoInicial = $BonoInternoMySqlDAO->querySQL($UsuarioSaldoInicial);

                $dataSaldoInicial = $BonoInterno->execQuery($transaccion, $UsuarioSaldoInicial);


                $sqlValues = '';
                $sqlValuesCont = 0;

                foreach ($dataSaldoInicial as $datanum) {
                    $datanum = json_decode(json_encode($datanum), FALSE);

                    /* $sql = "INSERT INTO usuario_saldo (usuario_id, mandante, fecha, saldo_recarga, saldo_apuestas, saldo_premios,
                                    saldo_apuestas_casino, saldo_premios_casino, saldo_notaret_pagadas,
                                    saldo_notaret_pend, saldo_notaret_creadas, saldo_ajustes_entrada, saldo_ajustes_salida,
                                    saldo_inicial, saldo_final, saldo_bono, saldo_creditos_inicial, saldo_creditos_base_inicial,
                                    saldo_creditos_final, saldo_creditos_base_final) VALUES ('" . $datanum->{'registro.usuario_id'} . "','" . $datanum->{'registro.mandante'} . "',DATE_FORMAT(DATE_ADD('" . $fechaSoloDia . "', INTERVAL 0 DAY), '%Y-%m-%d'),0,
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
                '" . ((floatval($datanum->{'registro.creditos'}) + floatval($datanum->{'registro.creditos_base'}))) . "',
                0,
                0,
                0,'" . $datanum->{'registro.creditos'} . "','" . $datanum->{'registro.creditos_base'} . "')


         ON DUPLICATE KEY UPDATE usuario_saldo.saldo_final      = '" . ((floatval($datanum->{'registro.creditos'}) + floatval($datanum->{'registro.creditos_base'}))) . "',
                                 usuario_saldo.saldo_creditos_final      = '" . $datanum->{'registro.creditos'} . "',
                                 usuario_saldo.saldo_creditos_base_final = '" . $datanum->{'registro.creditos_base'} . "';

                ";*/
                    /*$sql = " UPDATE usuario_saldo SET   usuario_saldo.saldo_final      = '" . ((floatval($datanum->{'registro.creditos'}) + floatval($datanum->{'registro.creditos_base'}))) . "',
                                usuario_saldo.saldo_creditos_final      = '" . $datanum->{'registro.creditos'} . "',
                                usuario_saldo.saldo_creditos_base_final = '" . $datanum->{'registro.creditos_base'} . "' WHERE usuario_id ='" . $datanum->{'registro.usuario_id'} . "' AND fecha='" . $fechaSoloDia . "'  AND  mandante ='" . $datanum->{'registro.mandante'} . "' ;

               ";
                    $BonoInterno->execQuery($transaccion, $sql);*/

                    $sql2 = "INSERT INTO usuario_saldo (usuario_id, mandante, fecha, saldo_recarga, saldo_apuestas, saldo_premios,
                           saldo_apuestas_casino, saldo_premios_casino, saldo_notaret_pagadas,
                           saldo_notaret_pend, saldo_notaret_creadas, saldo_ajustes_entrada, saldo_ajustes_salida,
                           saldo_inicial, saldo_final, saldo_bono, saldo_creditos_inicial, saldo_creditos_base_inicial,
                           saldo_creditos_final, saldo_creditos_base_final) VALUES ('" . $datanum->{'registro.usuario_id'} . "','" . $datanum->{'registro.mandante'} . "',DATE_FORMAT(DATE_ADD('" . $fechaSoloDia . "', INTERVAL 1 DAY), '%Y-%m-%d'),0,
       0,
       0,
       0,
       0,
       0,
       0,
       0,
       0,
       0,
       '" . ((floatval($datanum->{'registro.creditos'}) + floatval($datanum->{'registro.creditos_base'}))) . "',
       0,
       0,'" . $datanum->{'registro.creditos'} . "','" . $datanum->{'registro.creditos_base'} . "',0,0)


ON DUPLICATE KEY UPDATE usuario_saldo.saldo_inicial      = '" . ((floatval($datanum->{'registro.creditos'}) + floatval($datanum->{'registro.creditos_base'}))) . "',
                        usuario_saldo.saldo_creditos_inicial      = '" . $datanum->{'registro.creditos'} . "',
                        usuario_saldo.saldo_creditos_base_inicial = '" . $datanum->{'registro.creditos_base'} . "';

       ";
                    if ($sqlValuesCont > 0) {
                        $sqlValues = $sqlValues . ',';
                    }


                    $sqlValues = $sqlValues . " ('" . $datanum->{'registro.usuario_id'} . "','" . $datanum->{'registro.mandante'} . "',(('" . $fechaHoy . "')),0,
       0,
       0,
       0,
       0,
       0,
       0,
       0,
       0,
       0,
       '" . ((floatval($datanum->{'registro.creditos'}) + floatval($datanum->{'registro.creditos_base'}))) . "',
       0,
       0,'" . $datanum->{'registro.creditos'} . "','" . $datanum->{'registro.creditos_base'} . "',0,0)

       ";
                    $sqlValuesCont++;

                    if ($sqlValuesCont >= 100000) {
                        if ($sqlValues != '') {
                            print_r('paso1');
                            $sql = "INSERT INTO usuario_saldo_" . date("Y_m_d") . " (usuario_id, mandante, fecha, saldo_recarga, saldo_apuestas, saldo_premios,
                           saldo_apuestas_casino, saldo_premios_casino, saldo_notaret_pagadas,
                           saldo_notaret_pend, saldo_notaret_creadas, saldo_ajustes_entrada, saldo_ajustes_salida,
                           saldo_inicial, saldo_final, saldo_bono, saldo_creditos_inicial, saldo_creditos_base_inicial,
                           saldo_creditos_final, saldo_creditos_base_final) VALUES 

       " . $sqlValues;
                            $BonoInterno->execQuery($transaccion, $sql);
                            $sqlValues = '';
                            $sqlValuesCont = 0;
                        }
                    }


                }

                if ($sqlValues != '') {
                    print_r('paso1');
                    $sql = "INSERT INTO usuario_saldo_" . date("Y_m_d") . " (usuario_id, mandante, fecha, saldo_recarga, saldo_apuestas, saldo_premios,
                           saldo_apuestas_casino, saldo_premios_casino, saldo_notaret_pagadas,
                           saldo_notaret_pend, saldo_notaret_creadas, saldo_ajustes_entrada, saldo_ajustes_salida,
                           saldo_inicial, saldo_final, saldo_bono, saldo_creditos_inicial, saldo_creditos_base_inicial,
                           saldo_creditos_final, saldo_creditos_base_final) VALUES 

       " . $sqlValues;
                    $BonoInterno->execQuery($transaccion, $sql);
                }


                $sqlValues = '';
                $sqlValuesCont = 0;

                foreach ($dataSaldoInicial2 as $datanum) {
                    $datanum = json_decode(json_encode($datanum), FALSE);

                    /*$sql = "INSERT INTO usuario_saldo (usuario_id, mandante, fecha, saldo_recarga, saldo_apuestas, saldo_premios,
                                   saldo_apuestas_casino, saldo_premios_casino, saldo_notaret_pagadas,
                                   saldo_notaret_pend, saldo_notaret_creadas, saldo_ajustes_entrada, saldo_ajustes_salida,
                                   saldo_inicial, saldo_final, saldo_bono, saldo_creditos_inicial, saldo_creditos_base_inicial,
                                   saldo_creditos_final, saldo_creditos_base_final) VALUES ('" . $datanum->{'punto_venta.usuario_id'} . "','" . $datanum->{'punto_venta.mandante'} . "',DATE_FORMAT(DATE_ADD('" . $fechaSoloDia . "', INTERVAL 0 DAY), '%Y-%m-%d'),0,
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
               '" . ((floatval($datanum->{'punto_venta.cupo_recarga'}) + floatval($datanum->{'punto_venta.creditos_base'}))) . "',
               0,
               0,
               0,'" . $datanum->{'punto_venta.cupo_recarga'} . "','" . $datanum->{'punto_venta.creditos_base'} . "')


        ON DUPLICATE KEY UPDATE usuario_saldo.saldo_final      = '" . ((floatval($datanum->{'punto_venta.cupo_recarga'}) + floatval($datanum->{'punto_venta.creditos_base'}))) . "',
                                usuario_saldo.saldo_creditos_final      = '" . $datanum->{'punto_venta.cupo_recarga'} . "',
                                usuario_saldo.saldo_creditos_base_final = '" . $datanum->{'punto_venta.creditos_base'} . "';

               ";*/

                    /* $sql = " UPDATE usuario_saldo SET   usuario_saldo.saldo_final      = '" . ((floatval($datanum->{'punto_venta.cupo_recarga'}) + floatval($datanum->{'punto_venta.creditos_base'}))) . "',
                                 usuario_saldo.saldo_creditos_final      = '" . $datanum->{'punto_venta.cupo_recarga'} . "',
                                 usuario_saldo.saldo_creditos_base_final = '" . $datanum->{'punto_venta.creditos_base'} . "' WHERE usuario_id ='" . $datanum->{'punto_venta.usuario_id'} . "' AND fecha=(('" . $fechaSoloDia . "')) AND  mandante ='" . $datanum->{'punto_venta.mandante'} . "' ;

                ";
                     $BonoInterno->execQuery($transaccion, $sql);*/

                    /* $sql = "INSERT INTO usuario_saldo (usuario_id, mandante, fecha, saldo_recarga, saldo_apuestas, saldo_premios,
                                    saldo_apuestas_casino, saldo_premios_casino, saldo_notaret_pagadas,
                                    saldo_notaret_pend, saldo_notaret_creadas, saldo_ajustes_entrada, saldo_ajustes_salida,
                                    saldo_inicial, saldo_final, saldo_bono, saldo_creditos_inicial, saldo_creditos_base_inicial,
                                    saldo_creditos_final, saldo_creditos_base_final) VALUES ('" . $datanum->{'punto_venta.usuario_id'} . "','" . $datanum->{'punto_venta.mandante'} . "',DATE_FORMAT(DATE_ADD('" . $fechaSoloDia . "', INTERVAL 1 DAY), '%Y-%m-%d'),0,
                0,
                0,
                0,
                0,
                0,
                0,
                0,
                0,
                0,
                '" . ((floatval($datanum->{'punto_venta.cupo_recarga'}) + floatval($datanum->{'punto_venta.creditos_base'}))) . "',
                0,
                0,'" . $datanum->{'punto_venta.cupo_recarga'} . "','" . $datanum->{'punto_venta.creditos_base'} . "',0,0)


         ON DUPLICATE KEY UPDATE usuario_saldo.saldo_inicial      = '" . ((floatval($datanum->{'punto_venta.cupo_recarga'}) + floatval($datanum->{'punto_venta.creditos_base'}))) . "',
                                 usuario_saldo.saldo_creditos_inicial      = '" . $datanum->{'punto_venta.cupo_recarga'} . "',
                                 usuario_saldo.saldo_creditos_base_inicial = '" . $datanum->{'punto_venta.creditos_base'} . "';

                ";*/

                    if ($sqlValuesCont > 0) {
                        $sqlValues = $sqlValues . ',';
                    }

                    $sqlValues = $sqlValues . "('" . $datanum->{'punto_venta.usuario_id'} . "','" . $datanum->{'punto_venta.mandante'} . "',(('" . $fechaHoy . "')),0,
       0,
       0,
       0,
       0,
       0,
       0,
       0,
       0,
       0,
       '" . ((floatval($datanum->{'punto_venta.cupo_recarga'}) + floatval($datanum->{'punto_venta.creditos_base'}))) . "',
       0,
       0,'" . $datanum->{'punto_venta.cupo_recarga'} . "','" . $datanum->{'punto_venta.creditos_base'} . "',0,0)

       ";
                    $sqlValuesCont++;

                    if ($sqlValuesCont >= 100000) {
                        if ($sqlValues != '') {
                            print_r('paso1');
                            $sql = "INSERT INTO usuario_saldo_" . date("Y_m_d") . " (usuario_id, mandante, fecha, saldo_recarga, saldo_apuestas, saldo_premios,
                           saldo_apuestas_casino, saldo_premios_casino, saldo_notaret_pagadas,
                           saldo_notaret_pend, saldo_notaret_creadas, saldo_ajustes_entrada, saldo_ajustes_salida,
                           saldo_inicial, saldo_final, saldo_bono, saldo_creditos_inicial, saldo_creditos_base_inicial,
                           saldo_creditos_final, saldo_creditos_base_final) VALUES 

       " . $sqlValues;
                            $BonoInterno->execQuery($transaccion, $sql);
                            $sqlValues = '';
                            $sqlValuesCont = 0;
                        }
                    }

                }
                if ($sqlValues != '') {

                    $sql = "INSERT INTO usuario_saldo_" . date("Y_m_d") . " (usuario_id, mandante, fecha, saldo_recarga, saldo_apuestas, saldo_premios,
                           saldo_apuestas_casino, saldo_premios_casino, saldo_notaret_pagadas,
                           saldo_notaret_pend, saldo_notaret_creadas, saldo_ajustes_entrada, saldo_ajustes_salida,
                           saldo_inicial, saldo_final, saldo_bono, saldo_creditos_inicial, saldo_creditos_base_inicial,
                           saldo_creditos_final, saldo_creditos_base_final) VALUES 

       " . $sqlValues;
                    $BonoInterno->execQuery($transaccion, $sql);
                }
            }

        }

        $message = "*CRON: (Fin) * " . " Resumenes PASO3 - Fecha: " . date("Y-m-d H:i:s");
        exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron-error-urg' > /dev/null & ");

        if(!$primerMetodoDeInsercion) {


            $sqlMandante = "select * from mandante where mandante in (3,4,5,6,7,10,22)";
            $dataSaldoInicial2 = $BonoInterno->execQuery($transaccion, $sqlMandante);

            $arrayMandantes = array();

            foreach ($dataSaldoInicial2 as $item) {

                array_push($arrayMandantes, $item->{'mandante.mandante'});
            }

            foreach ($arrayMandantes as $arrayMandante) {
                $UsuarioSaldoInicialPuntoVentaINSERT = "
INSERT INTO usuario_saldo_" . date("Y_m_d") . " (usuario_id, mandante, fecha, saldo_recarga, saldo_apuestas, saldo_premios,
                           saldo_apuestas_casino, saldo_premios_casino, saldo_notaret_pagadas,
                           saldo_notaret_pend, saldo_notaret_creadas, saldo_ajustes_entrada, saldo_ajustes_salida,
                           saldo_inicial, saldo_final, saldo_bono, saldo_creditos_inicial, saldo_creditos_base_inicial,
                           saldo_creditos_final, saldo_creditos_base_final)

SELECT punto_venta.usuario_id,
       punto_venta.mandante,
       DATE_FORMAT((now()), '%Y-%m-%d') fecha,
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
       punto_venta.cupo_recarga + punto_venta.creditos_base,
       0,
       0,
       punto_venta.cupo_recarga,
       punto_venta.creditos_base,
       0,
       0

FROM punto_venta
INNER JOIN usuario on (punto_venta.usuario_id = usuario.usuario_id)

INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = punto_venta.usuario_id) WHERE perfil_id != 'MAQUINAANONIMA'
AND usuario.mandante  = '" . $arrayMandante . "'
;
";

                $dataSaldoInicial2 = $BonoInterno->execQuery($transaccion, $UsuarioSaldoInicialPuntoVentaINSERT);


                $UsuarioSaldoInicialINSERT = "
INSERT INTO usuario_saldo_" . date("Y_m_d") . " (usuario_id, mandante, fecha, saldo_recarga, saldo_apuestas, saldo_premios,
                           saldo_apuestas_casino, saldo_premios_casino, saldo_notaret_pagadas,
                           saldo_notaret_pend, saldo_notaret_creadas, saldo_ajustes_entrada, saldo_ajustes_salida,
                           saldo_inicial, saldo_final, saldo_bono, saldo_creditos_inicial, saldo_creditos_base_inicial,
                           saldo_creditos_final, saldo_creditos_base_final) 
SELECT registro.usuario_id,
       registro.mandante,
       DATE_FORMAT((now()), '%Y-%m-%d') fecha,
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
       registro.creditos_base+registro.creditos,
       0,
       0,
       registro.creditos,
       registro.creditos_base,
       0,
       0

FROM registro
INNER JOIN usuario on (registro.usuario_id = usuario.usuario_id)
WHERE usuario.mandante = '" . $arrayMandante . "'
;
";
                $dataSaldoInicial = $BonoInterno->execQuery($transaccion, $UsuarioSaldoInicialINSERT);


            }
        }

        //$procesoInterno=$BonoInterno->execQuery($transaccion, "INSERT INTO procesos_internos ( tipo, fecha_crea, usucrea_id) VALUES ('CapturaSaldos','".date("Y-m-d 00:00:00")."','0');");



        $transaccion->commit();
        $message = "*CRON: (Fin) * " . " Resumenes PASO4 - Fecha: " . date("Y-m-d H:i:s");
        exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron-error-urg' > /dev/null & ");


    } elseif (false) {

        $dataSaldoInicial = $BonoInterno->execQuery($transaccion, $UsuarioSaldoInicialFIX2);


        foreach ($dataSaldoInicial as $datanum) {
            $sql = "INSERT INTO usuario_saldo (usuario_id, mandante, fecha, saldo_recarga, saldo_apuestas, saldo_premios,
                           saldo_apuestas_casino, saldo_premios_casino, saldo_notaret_pagadas,
                           saldo_notaret_pend, saldo_notaret_creadas, saldo_ajustes_entrada, saldo_ajustes_salida,
                           saldo_inicial, saldo_final, saldo_bono, saldo_creditos_inicial, saldo_creditos_base_inicial,
                           saldo_creditos_final, saldo_creditos_base_final) VALUES ('" . $datanum->{'usuario_historial.usuario_id'} . "','0',DATE_FORMAT(DATE_ADD('" . $fechaSoloDia . "', INTERVAL 0 DAY), '%Y-%m-%d'),0,
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
       '" . ((floatval($datanum->{'usuario_historial.creditos'}) + floatval($datanum->{'usuario_historial.creditos_base'}))) . "',
       0,
       0,
       0,'" . $datanum->{'usuario_historial.creditos'} . "','" . $datanum->{'usuario_historial.creditos_base'} . "')
       
       
ON DUPLICATE KEY UPDATE usuario_saldo.saldo_final      = '" . ((floatval($datanum->{'usuario_historial.creditos'}) + floatval($datanum->{'usuario_historial.creditos_base'}))) . "',
                        usuario_saldo.saldo_creditos_final      = '" . $datanum->{'usuario_historial.creditos'} . "',
                        usuario_saldo.saldo_creditos_base_final = '" . $datanum->{'usuario_historial.creditos_base'} . "';

       ";
            $BonoInterno->execQuery($transaccion, $sql);

        }
        $transaccion->commit();

    } elseif (false) {


        $UsuarioSaldoInicialFIX2 = "
        SELECT usuario_id,saldo_inicial,saldo_creditos_inicial,saldo_creditos_base_inicial,fecha
FROM usuario_saldo WHERE DATE_FORMAT(fecha, '%Y-%m-%d') = '" . $fechaSoloDia . "'
AND saldo_recarga = 0
  AND saldo_apuestas = 0
  AND saldo_premios = 0
  AND saldo_apuestas_casino = 0
  AND saldo_premios_casino = 0
  AND saldo_notaret_pagadas = 0
  AND saldo_notaret_creadas = 0
  AND saldo_ajustes_entrada = 0
  AND saldo_ajustes_salida = 0
  AND saldo_bono = 0
  AND saldo_inicial != 0
";
        $dataSaldoInicial = $BonoInterno->execQuery($transaccion, $UsuarioSaldoInicialFIX2);


        foreach ($dataSaldoInicial as $datanum) {
            $sql = "INSERT INTO usuario_saldo (usuario_id, mandante, fecha, saldo_recarga, saldo_apuestas, saldo_premios,
                           saldo_apuestas_casino, saldo_premios_casino, saldo_notaret_pagadas,
                           saldo_notaret_pend, saldo_notaret_creadas, saldo_ajustes_entrada, saldo_ajustes_salida,
                           saldo_inicial, saldo_final, saldo_bono, saldo_creditos_inicial, saldo_creditos_base_inicial,
                           saldo_creditos_final, saldo_creditos_base_final) VALUES ('" . $datanum->{'usuario_saldo.usuario_id'} . "','0',DATE_FORMAT(DATE_ADD('" . $fechaSoloDia . "', INTERVAL 0 DAY), '%Y-%m-%d'),0,
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
       '" . $datanum->{'usuario_saldo.saldo_inicial'} . "',
       0,
       0,
       0,'" . $datanum->{'usuario_saldo.saldo_creditos_inicial'} . "','" . $datanum->{'usuario_saldo.saldo_creditos_base_inicial'} . "')
       
       
ON DUPLICATE KEY UPDATE usuario_saldo.saldo_final      = '" . $datanum->{'usuario_saldo.saldo_inicial'} . "',
                        usuario_saldo.saldo_creditos_final      = '" . $datanum->{'usuario_saldo.saldo_creditos_inicial'} . "',
                        usuario_saldo.saldo_creditos_base_final = '" . $datanum->{'usuario_saldo.saldo_creditos_base_inicial'} . "';

       ";
            $BonoInterno->execQuery($transaccion, $sql);

        }
        $transaccion->commit();

    } else {


        $UsuarioSaldoInicialFIX2 = "
        SELECT usuario_id,saldo_final,saldo_creditos_final,saldo_creditos_base_final,fecha
FROM usuario_saldo WHERE DATE_FORMAT(fecha, '%Y-%m-%d') = '" . $fechaSoloDia . "'";
        $dataSaldoInicial = $BonoInterno->execQuery($transaccion, $UsuarioSaldoInicialFIX2);


        foreach ($dataSaldoInicial as $datanum) {

            $sql = "INSERT INTO usuario_saldo (usuario_id, mandante, fecha, saldo_recarga, saldo_apuestas, saldo_premios,
                           saldo_apuestas_casino, saldo_premios_casino, saldo_notaret_pagadas,
                           saldo_notaret_pend, saldo_notaret_creadas, saldo_ajustes_entrada, saldo_ajustes_salida,
                           saldo_inicial, saldo_final, saldo_bono, saldo_creditos_inicial, saldo_creditos_base_inicial,
                           saldo_creditos_final, saldo_creditos_base_final) VALUES ('" . $datanum->{'usuario_saldo.usuario_id'} . "','0',DATE_FORMAT(DATE_ADD('" . $fechaSoloDia . "', INTERVAL 1 DAY), '%Y-%m-%d'),0,
       0,
       0,
       0,
       0,
       0,
       0,
       0,
       0,
       0,
              '" . $datanum->{'usuario_saldo.saldo_final'} . "',
       0,
       0,'" . $datanum->{'usuario_saldo.saldo_creditos_final'} . "','" . $datanum->{'usuario_saldo.saldo_creditos_base_final'} . "',
       0,
       0)
       
       
ON DUPLICATE KEY UPDATE usuario_saldo.saldo_final      = '" . $datanum->{'usuario_saldo.saldo_final'} . "',
                        usuario_saldo.saldo_creditos_final      = '" . $datanum->{'usuario_saldo.saldo_creditos_final'} . "',
                        usuario_saldo.saldo_creditos_base_final = '" . $datanum->{'usuario_saldo.saldo_creditos_base_final'} . "';

       ";
            $BonoInterno->execQuery($transaccion, $sql);

        }
        $transaccion->commit();

    }
    $message = "*CRON: (Fin) * " . " Resumenes - Fecha: " . date("Y-m-d H:i:s");
    exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");



} catch (Exception $e) {
    print_r($e);
    if($transaccion != null){
        if($transaccion->isIsconnected()){
            $transaccion->rollback();
        }
    }
    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "ERROR: " . $e->getMessage() . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);


    $message = "*CRON: (ERROR) (".$e->getLine().") * " . " Resumenes - Fecha: " . date("Y-m-d H:i:s");

    exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

}





