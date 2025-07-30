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
//sleep(600);

ini_set('memory_limit', '-1');

$message = "*CRON: (Inicio) * " . " Resumenes - Fecha: " . date("Y-m-d H:i:s");

exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

$message = "*CRON: (Fin) * " . " ResumenesF PASO1 - Fecha: " . date("Y-m-d H:i:s");
exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#procesos-cron-resumenes' > /dev/null & ");


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
  CASE WHEN transaccion_juego.tipo ='FREECASH' THEN '6' WHEN transaccion_juego.tipo ='FREECASH' THEN '4'  ELSE '1' END tipo,
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
  CASE WHEN transaccion_juego.tipo = 'FREESPIN' THEN 'DEBITFREESPIN' WHEN transaccion_juego.tipo = 'FREECASH' THEN 'DEBITFREECASH' ElSE 'DEBIT' END tipo,
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
WHERE usuario.mandante NOT IN (3,4,5,6,7,10,22)
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
AND usuario.mandante NOT IN (3,4,5,6,7,10,22)
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
               0                                   saldo_bono_free_ganado
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
               0                                   saldo_bono_free_ganado
        FROM casino.usuario_deporte_resumen
        WHERE tipo IN ('WIN', 'NEWCREDIT', 'CASHOUT', 'NEWDEBIT') AND  DATE_FORMAT(fecha_crea, '%Y-%m-%d')   ='" . $fechaSoloDia . "'
        GROUP BY usuario_id)

       UNION

       (SELECT usuario_mandante,
               0                                   saldo_recarga,
               DATE_FORMAT(usuario_casino_resumen.fecha_crea, '%Y-%m-%d') fecha,
               0                                   saldo_apuestas,
               0                                   saldo_premios,
               SUM(valor+valor_premios)                          saldo_apuestas_casino,
               0                                   saldo_premios_casino,
               0                                   saldo_notaret_pagadas,
               0                                   saldo_notaret_pend,
               0                                   saldo_notaret_creadas,
               0                                   saldo_ajustes_entrada,
               0                                   saldo_ajustes_salida,
               0                                   saldo_bono,
               0                                   saldo_notaret_eliminadas,
               0                                   saldo_bono_free_ganado
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
               0                                   saldo_bono_free_ganado
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
               SUM(valor)                          saldo_notaret_pend,
               0                                   saldo_notaret_creadas,
               0                                   saldo_ajustes_entrada,
               0                                   saldo_ajustes_salida,
               0                                   saldo_bono,
               0                                   saldo_notaret_eliminadas,
               0                                   saldo_bono_free_ganado
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
               0                                   saldo_bono_free_ganado
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
               0                                   saldo_bono_free_ganado
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
               0                                   saldo_bono_free_ganado
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
               0                                   saldo_bono_free_ganado
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
               0                                   saldo_bono_free_ganado
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
               SUM(CASE when tipo = 'W' then valor else 0 end)  saldo_bono_free_ganado
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


        //SCRIPT QUE CREA TABLA DEL DIA ACTUAL Y USUARIO_SALDO A ANTIER Y RENOMBRA TABLA DEL DIA ANTERIOR A USUARIO_SALDO
        if(true) {


            try {
                $newTableUsuarioSaldo = "usuario_saldo_" . date("Y_m_d") . "_rf";


                $port = 3306;
                $driver = 'mysql';

                $url = "mysql:host=" . $_ENV['DB_HOST_ALTER'] . ";dbname=" . $_ENV['DB_NAME_ALTER'] . ";charset=utf8mb4";
                // $mysqli = new PDO("mysql:unix_socket=/tmp/proxysql.sock;dbname=" . $name, $user, $pass);
                $conn2 = new PDO($url, $_ENV['DB_USER_ALTER'], $_ENV['DB_PASSWORD_ALTER'], [
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ]);
                //$conn = new PDO("mysql:unix_socket=/tmp/proxysql.sock;dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword());

                //$conn2 = new PDO("mysql:host=" . 'mysql.local' . ";dbname=" . 'casino', 'alteruser', 'CV2XsFLzmgdenuzn');


                $conn2->exec( "set names utf8");


                $sql2 = "rename table usuario_saldo_rf to usuario_saldo_" . date("Y_m_d", strtotime('-2 days')) . "_rf;";

                $conn2->exec( $sql2);

                $sql2 = "rename table usuario_saldo_" . date("Y_m_d", strtotime('-1 days')) . "_rf to usuario_saldo_rf;";

                $conn2->exec( $sql2);

                $sql2 = "
create table " . $newTableUsuarioSaldo . "
(
	usuariosaldo_id bigint unsigned auto_increment comment 'Autonumerico',
	usuario_id bigint unsigned comment 'Id del usuario',
	mandante bigint unsigned default 0 not null comment 'Mandante',
	fecha varchar(10) default '' not null comment 'Periodo',
	saldo_recarga double default 0 not null comment 'Saldo de recargas',
	saldo_apuestas double default 0 not null comment 'Saldo apostado',
	saldo_premios double default 0 not null comment 'Saldo premios',
	saldo_notaret_pagadas double default 0 not null comment 'Saldo notas de retiro pagadas',
	saldo_notaret_pend double default 0 not null comment 'Saldo notas de retiro pendientes',
	saldo_ajustes_entrada double default 0 not null comment 'Saldo ajustes por entrada',
	saldo_ajustes_salida double default 0 not null comment 'Saldo ajustes por salida',
	saldo_inicial double default 0 not null comment 'Saldo inicial',
	saldo_final double default 0 not null comment 'Saldo final',
	saldo_bono double default 0 not null comment 'Saldo de bonos',
	saldo_creditos_inicial double default 0 null,
	saldo_creditos_base_inicial double default 0 null,
	saldo_creditos_final double default 0 null,
	saldo_creditos_base_final double default 0 null,
	saldo_notaret_creadas double default 0 null,
	saldo_apuestas_casino double default 0 null,
	saldo_premios_casino double default 0 null,
	saldo_notaret_eliminadas double default 0 null,
	saldo_bono_free_ganado double default 0 null,
	saldo_bono_casino_free_ganado double default 0 null,
	saldo_bono_casino_vivo double default 0 null,
	saldo_bono_casino_vivo_free_ganado double default 0 null,
	saldo_bono_virtual double default 0 null,
	saldo_bono_virtual_free_ganado double default 0 null,
	billetera_id int default 0 null,
	saldo_apuestas_casino_vivo double default 0 null,
	primary key (usuariosaldo_id)
);
		
		";
                print_r($sql2);
                $conn2->exec( $sql2);

                $sql2 = "create index idx_" . $newTableUsuarioSaldo . "_mandante
	on " . $newTableUsuarioSaldo . " (mandante);
		
		";

                $conn2->exec( $sql2);


                $sql2 = "

create index " . $newTableUsuarioSaldo . "_fecha_index
	on " . $newTableUsuarioSaldo . " (fecha);
		
		";

                $conn2->exec( $sql2);

                $sql2 = "
create index " . $newTableUsuarioSaldo . "_usuario_id_index
	on " . $newTableUsuarioSaldo . " (usuario_id);
		";

                $conn2->exec( $sql2);

                $sql2 = "
	alter table " . $newTableUsuarioSaldo . "
	add constraint " . $newTableUsuarioSaldo . "_pk
		unique (usuario_id, fecha,billetera_id);
		
		";

                $conn2->exec( $sql2);

                $sql2 = "
create index " . $newTableUsuarioSaldo . "_billetera_id_index
	on " . $newTableUsuarioSaldo . " (billetera_id);
		";

                $conn2->exec( $sql2);

                $sql2 = "
create index " . $newTableUsuarioSaldo . "_usuario_id_billetera_id_index
	on " . $newTableUsuarioSaldo . " (usuario_id, billetera_id);
		";

                $conn2->exec( $sql2);

                $sql2 = "
create index " . $newTableUsuarioSaldo . "_usuario_id_fecha_billetera_id_index
	on " . $newTableUsuarioSaldo . " (usuario_id, fecha, billetera_id);
		";

                $conn2->exec( $sql2);
            } catch (Exception $e) {
                syslog(LOG_WARNING, "ERRORCRON :" . $e->getCode() . ' - ' . $e->getMessage());
                print_r($e);

            }
            
        }

        $message = "*CRON: (Fin) * " . " ResumenesF PASO2 - Fecha: " . date("Y-m-d H:i:s");
        exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#procesos-cron-resumenes' > /dev/null & ");

        $primerMetodoDeInsercion = true;
        //$BonoInterno->execQuery($transaccion, $sql);
        if($primerMetodoDeInsercion){
            $BonoInternoMySqlDAO = new BonoInternoMySqlDAO($transaccion);

            if(true) {


                $sqlUpdate = 'UPDATE usuario_saldo_rf
SET usuario_saldo_rf.saldo_final=usuario_saldo_rf.saldo_inicial,
    usuario_saldo_rf.saldo_creditos_final = usuario_saldo_rf.saldo_creditos_inicial,
    usuario_saldo_rf.saldo_creditos_base_final = usuario_saldo_rf.saldo_creditos_base_inicial
WHERE usuario_saldo_rf.mandante  in (8);';

                $BonoInterno->execQuery($transaccion, $sqlUpdate);
                $transaccion->commit();
                $_ENV["NEEDINSOLATIONLEVEL"] ='';

                $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
                $transaccion = $BonoDetalleMySqlDAO->getTransaction();

            }

            //INSERCION DE USUARIOS NUEVOS EN LA TABLA
            if (true) {


                $sqlInsert = 'INSERT INTO usuario_saldo_rf
(usuario_id, mandante, fecha, saldo_recarga, saldo_apuestas, saldo_premios, saldo_notaret_pagadas, saldo_notaret_pend, saldo_ajustes_entrada, saldo_ajustes_salida, saldo_inicial, saldo_final, saldo_bono, saldo_creditos_inicial, saldo_creditos_base_inicial, saldo_creditos_final, saldo_creditos_base_final, saldo_notaret_creadas, saldo_apuestas_casino, saldo_premios_casino, saldo_notaret_eliminadas, saldo_bono_free_ganado, saldo_bono_casino_free_ganado, saldo_bono_casino_vivo, saldo_bono_casino_vivo_free_ganado, saldo_bono_virtual, saldo_bono_virtual_free_ganado, billetera_id, saldo_apuestas_casino_vivo)

SELECT 
usuario_id, mandante, "' . date("Y-m-d", strtotime('-1 days')) . '", 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0


FROM usuario where usuario.fecha_crea like "' . date("Y-m-d", strtotime('-1 days')) . '%" and   usuario.mandante  in (8);';



                $sqlInsert = '
SELECT 
usuario_id, mandante, "' . date("Y-m-d", strtotime('-1 days')) . '", 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0


FROM usuario where usuario.fecha_crea like "' . date("Y-m-d", strtotime('-1 days')) . '%" and   usuario.mandante in (8);';


                $datosUsuariosNuevos = $BonoInterno->execQuery($transaccion, $sqlInsert);


                foreach ($datosUsuariosNuevos as $datanum) {
                    $sql = "
                    
                    INSERT INTO usuario_saldo_rf
(usuario_id, mandante, fecha, saldo_recarga, saldo_apuestas, saldo_premios, saldo_notaret_pagadas, saldo_notaret_pend, saldo_ajustes_entrada, saldo_ajustes_salida, saldo_inicial, saldo_final, saldo_bono, saldo_creditos_inicial, saldo_creditos_base_inicial, saldo_creditos_final, saldo_creditos_base_final, saldo_notaret_creadas, saldo_apuestas_casino, saldo_premios_casino, saldo_notaret_eliminadas, saldo_bono_free_ganado, saldo_bono_casino_free_ganado, saldo_bono_casino_vivo, saldo_bono_casino_vivo_free_ganado, saldo_bono_virtual, saldo_bono_virtual_free_ganado, billetera_id, saldo_apuestas_casino_vivo)
 VALUES (
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
         inner join usuario on usuario.usuario_id = usuario_historial.usuario_id
where usuario.mandante  in (8)
  and usuario_historial.fecha_crea >= '".$fecha1."'
  and usuario_historial.fecha_crea <= '".$fecha2."'

group by usuario.usuario_id;
";

            $sqlHistorial="
           SET SESSION group_concat_max_len = 10000000000000;

";
            $dataHistorial = $BonoInternoMySqlDAO->querySQL($sqlHistorial);

            $sqlHistorial="
           SELECT GROUP_CONCAT(usuhistorial_id SEPARATOR ', ')  usuhistorial_id

                  FROM ( SELECT max(usuhistorial_id)  usuhistorial_id
FROM usuario_historial
         inner join usuario on usuario.usuario_id = usuario_historial.usuario_id
where usuario.mandante  in (8)
  and usuario_historial.fecha_crea >= '".$fecha1."'
  and usuario_historial.fecha_crea <= '".$fecha2."'

group by usuario.usuario_id) a;
";
            $dataHistorial = $BonoInternoMySqlDAO->querySQL($sqlHistorial);

            $usuHistorialIds2='';
            foreach ($dataHistorial[0] as $item) {
                $usuHistorialIds2=$item;
            }

            $usuHistorialIds2 = explode(',',$usuHistorialIds2);

            $contUsu=0;
            $usuHistorialIds='0';
            foreach ($usuHistorialIds2 as $item) {
                if($item == ''){
                    continue;
                }
                if($contUsu >= 1000){
                    if($usuHistorialIds != '0') {


                        $sqlHistorial = "
            UPDATE usuario_saldo_rf,usuario_historial
SET usuario_saldo_rf.saldo_final=usuario_historial.creditos+usuario_historial.creditos_base,
    usuario_saldo_rf.saldo_creditos_final = usuario_historial.creditos,
    usuario_saldo_rf.saldo_creditos_base_final = usuario_historial.creditos_base
where usuario_historial.usuario_id=usuario_saldo_rf.usuario_id
AND usuhistorial_id in ( " . $usuHistorialIds . ");
";

                        $_ENV["NEEDINSOLATIONLEVEL"] ='1';
                        $transaccion->commit();
                        $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
                        $transaccion = $BonoDetalleMySqlDAO->getTransaction();

                        $BonoInterno->execQuery($transaccion, $sqlHistorial);
                        $transaccion->commit();
                        $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
                        $transaccion = $BonoDetalleMySqlDAO->getTransaction();

                        $usuHistorialIds='0';

                    }

                }
                $usuHistorialIds =$usuHistorialIds .','. $item;
                $contUsu++;
            }

            if($usuHistorialIds != '0') {


                $sqlHistorial = "
            UPDATE usuario_saldo_rf,usuario_historial
SET usuario_saldo_rf.saldo_final=usuario_historial.creditos+usuario_historial.creditos_base,
    usuario_saldo_rf.saldo_creditos_final = usuario_historial.creditos,
    usuario_saldo_rf.saldo_creditos_base_final = usuario_historial.creditos_base
where usuario_historial.usuario_id=usuario_saldo_rf.usuario_id
AND usuhistorial_id in ( " . $usuHistorialIds . ");
";

                $BonoInterno->execQuery($transaccion, $sqlHistorial);
                $transaccion->commit();
                $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
                $transaccion = $BonoDetalleMySqlDAO->getTransaction();

                $usuHistorialIds='0';

            }

            if(true) {


                $newTableUsuarioSaldo = "usuario_saldo_" . date("Y_m_d") . "_rf";

                $sqlInsert = 'INSERT INTO ' . $newTableUsuarioSaldo . ' 
(usuario_id, mandante, fecha, saldo_recarga, saldo_apuestas, saldo_premios, saldo_notaret_pagadas, saldo_notaret_pend, saldo_ajustes_entrada, saldo_ajustes_salida, saldo_inicial, saldo_final, saldo_bono, saldo_creditos_inicial, saldo_creditos_base_inicial, saldo_creditos_final, saldo_creditos_base_final, saldo_notaret_creadas, saldo_apuestas_casino, saldo_premios_casino, saldo_notaret_eliminadas, saldo_bono_free_ganado, saldo_bono_casino_free_ganado, saldo_bono_casino_vivo, saldo_bono_casino_vivo_free_ganado, saldo_bono_virtual, saldo_bono_virtual_free_ganado, billetera_id, saldo_apuestas_casino_vivo)

SELECT 
usuario_id, mandante, "' . date("Y-m-d") . '", 0, 0, 0, 0, 0, 0, 0, saldo_final, 0, 0, saldo_creditos_final, saldo_creditos_base_final, 0,0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0


FROM usuario_saldo_rf  where usuario_saldo_rf.mandante  in (8);';

                $BonoInterno->execQuery($transaccion, $sqlInsert);
                $transaccion->commit();
                $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
                $transaccion = $BonoDetalleMySqlDAO->getTransaction();


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

        $message = "*CRON: (Fin) * " . " ResumenesF PASO3 - Fecha: " . date("Y-m-d H:i:s");
        exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#procesos-cron-resumenes' > /dev/null & ");

        if(!$primerMetodoDeInsercion) {


            $sqlMandante = "select * from mandante where mandante NOT IN (3,4,5,6,7,10,22)";
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

        $procesoInterno=$BonoInterno->execQuery($transaccion, "INSERT INTO procesos_internos ( tipo, fecha_crea, usucrea_id) VALUES ('CapturaSaldosRF','".date("Y-m-d 00:00:00")."','0');");



        $transaccion->commit();

        $message = "*CRON: (Fin) * " . " ResumenesF PASO4 - Fecha: " . date("Y-m-d H:i:s");
        exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#procesos-cron-resumenes' > /dev/null & ");


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
    exit();

    $BonoInterno = new BonoInterno();
    $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
    $transaccion = $BonoDetalleMySqlDAO->getTransaction();

    $sqlFin="UPDATE usuario_saldo
    INNER JOIN usuario_saldo_".date("Y_m_d")." on usuario_saldo.usuario_id = usuario_saldo_".date("Y_m_d").".usuario_id
SET usuario_saldo.saldo_final=usuario_saldo_".date("Y_m_d").".saldo_inicial,
    usuario_saldo.saldo_creditos_final=usuario_saldo_".date("Y_m_d").".saldo_creditos_inicial,
    usuario_saldo.saldo_creditos_base_final=usuario_saldo_".date("Y_m_d").".saldo_creditos_base_inicial
WHERE usuario_saldo.usuario_id = usuario_saldo_".date("Y_m_d").".usuario_id;";

     $BonoInterno->execQuery($transaccion, $sqlFin);
    $transaccion->commit();


    exit();
    /*$dataSaldoInicial=$BonoInterno->execQuery($transaccion,$UsuarioSaldoInicialFIX);


    foreach ($dataSaldoInicial as $datanum) {
        $sql="INSERT INTO usuario_saldo (usuario_id, mandante, fecha, saldo_recarga, saldo_apuestas, saldo_premios,
                           saldo_apuestas_casino, saldo_premios_casino, saldo_notaret_pagadas,
                           saldo_notaret_pend, saldo_notaret_creadas, saldo_ajustes_entrada, saldo_ajustes_salida,
                           saldo_inicial, saldo_final, saldo_bono, saldo_creditos_inicial, saldo_creditos_base_inicial,
                           saldo_creditos_final, saldo_creditos_base_final) VALUES ('".$datanum->{'usuario_saldo.usuario_id'}."','".$datanum->{'usuario_saldo.mandante'}."',DATE_FORMAT(DATE_ADD('".$fechaSoloDia."', INTERVAL 1 DAY), '%Y-%m-%d'),0,
       0,
       0,
       0,
       0,
       0,
       0,
       0,
       0,
       0,
       '".((floatval($datanum->{'usuario_saldo.saldo_creditos_final'})+floatval($datanum->{'usuario_saldo.saldo_creditos_base_final'})))."',
       0,
       0,'".$datanum->{'usuario_saldo.saldo_creditos_final'}."','".$datanum->{'usuario_saldo.saldo_creditos_base_final'}."',0,0)


ON DUPLICATE KEY UPDATE usuario_saldo.saldo_inicial      = '".((floatval($datanum->{'usuario_saldo.saldo_creditos_final'})+floatval($datanum->{'usuario_saldo.saldo_creditos_base_final'})))."',
                        usuario_saldo.saldo_creditos_inicial      = '".$datanum->{'usuario_saldo.saldo_creditos_final'}."',
                        usuario_saldo.saldo_creditos_base_inicial = '".$datanum->{'usuario_saldo.saldo_creditos_base_final'}."';

       ";
        $BonoInterno->execQuery($transaccion, $sql);

    }*/


    /*$dataSaldoInicial=$BonoInterno->execQuery($transaccion,$UsuarioSaldoInicialFIX);


    foreach ($dataSaldoInicial as $datanum) {
        $sql="INSERT INTO usuario_saldo (usuario_id, mandante, fecha, saldo_recarga, saldo_apuestas, saldo_premios,
                           saldo_apuestas_casino, saldo_premios_casino, saldo_notaret_pagadas,
                           saldo_notaret_pend, saldo_notaret_creadas, saldo_ajustes_entrada, saldo_ajustes_salida,
                           saldo_inicial, saldo_final, saldo_bono, saldo_creditos_inicial, saldo_creditos_base_inicial,
                           saldo_creditos_final, saldo_creditos_base_final) VALUES ('".$datanum->{'usuario_saldo.usuario_id'}."','".$datanum->{'usuario_saldo.mandante'}."',DATE_FORMAT(DATE_ADD('".$fechaSoloDia."', INTERVAL 0 DAY), '%Y-%m-%d'),0,
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
       '".((floatval($datanum->{'usuario_saldo.saldo_creditos_inicial'})+floatval($datanum->{'usuario_saldo.saldo_creditos_base_inicial'})))."',
       0,0,0,'".$datanum->{'usuario_saldo.saldo_creditos_inicial'}."','".$datanum->{'usuario_saldo.saldo_creditos_base_inicial'}."')


ON DUPLICATE KEY UPDATE usuario_saldo.saldo_final     = '".((floatval($datanum->{'usuario_saldo.saldo_creditos_inicial'})+floatval($datanum->{'usuario_saldo.saldo_creditos_base_inicial'})))."',
                        usuario_saldo.saldo_creditos_final      = '".$datanum->{'usuario_saldo.saldo_creditos_inicial'}."',
                        usuario_saldo.saldo_creditos_base_final = '".$datanum->{'usuario_saldo.saldo_creditos_base_inicial'}."';

       ";
        $BonoInterno->execQuery($transaccion, $sql);

    }*/

    /*   $dataSaldoInicial=$BonoInterno->execQuery($transaccion,$UsuarioSaldoInicialFIX2);


        foreach ($dataSaldoInicial as $datanum) {
            $sql="INSERT INTO usuario_saldo (usuario_id, mandante, fecha, saldo_recarga, saldo_apuestas, saldo_premios,
                               saldo_apuestas_casino, saldo_premios_casino, saldo_notaret_pagadas,
                               saldo_notaret_pend, saldo_notaret_creadas, saldo_ajustes_entrada, saldo_ajustes_salida,
                               saldo_inicial, saldo_final, saldo_bono, saldo_creditos_inicial, saldo_creditos_base_inicial,
                               saldo_creditos_final, saldo_creditos_base_final) VALUES ('".$datanum->{'usuario_historial.usuario_id'}."','0',DATE_FORMAT(DATE_ADD('".$fechaSoloDia."', INTERVAL 0 DAY), '%Y-%m-%d'),0,
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
           '".((floatval($datanum->{'usuario_historial.creditos'})+floatval($datanum->{'usuario_historial.creditos_base'})))."',
           0,
           0,
           0,'".$datanum->{'usuario_historial.creditos'}."','".$datanum->{'usuario_historial.creditos_base'}."')


    ON DUPLICATE KEY UPDATE usuario_saldo.saldo_final      = '".((floatval($datanum->{'usuario_historial.creditos'})+floatval($datanum->{'usuario_historial.creditos_base'})))."',
                            usuario_saldo.saldo_creditos_final      = '".$datanum->{'usuario_historial.creditos'}."',
                            usuario_saldo.saldo_creditos_base_final = '".$datanum->{'usuario_historial.creditos_base'}."';

           ";
            $BonoInterno->execQuery($transaccion, $sql);

        }
    */


    /*$transaccion->commit();*/


    $BonoInterno = new BonoInterno();
    $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
    $transaccion = $BonoDetalleMySqlDAO->getTransaction();
    $transaccion->getConnection()->beginTransaction();

     //$BonoInterno->execQuery($transaccion, $strEliminado);

    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "Eliminado: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
    print_r("PASO");

    if(false) {

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

        $data = $BonoInterno->execQuery('', $sqlRetiroUsuarioDiaPagado);


        foreach ($data as $datanum) {
            $sql = "INSERT INTO usuario_retiro_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, cantidad,puntoventa_id)
              VALUES ('" . $datanum->{'cuenta_cobro.usuarioId'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'.fecha_crea'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'.usumodif_id'} . "','" . $datanum->{'.estado'} . "','" . $datanum->{'.cantidad'} . "','" . $datanum->{'cuenta_cobro.puntoventa_id'} . "')";
            $BonoInterno->execQuery($transaccion, $sql);
        }

        $log = "\r\n" . "-------------------------" . "\r\n";
        $log = $log . "RetiroUsuarioDiaPagado: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
        fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
        print_r("PASO");

        $data = $BonoInterno->execQuery('', $sqlRetiroUsuarioDiaPagadoCuentaBancariaFisicamente);


        foreach ($data as $datanum) {
            $sql = "INSERT INTO usuario_retiro_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, cantidad,puntoventa_id)
              VALUES ('" . $datanum->{'cuenta_cobro.usuarioId'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'.fecha_crea'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'.usumodif_id'} . "','" . $datanum->{'.estado'} . "','" . $datanum->{'.cantidad'} . "','0')";
            $BonoInterno->execQuery($transaccion, $sql);
        }

        $log = "\r\n" . "-------------------------" . "\r\n";
        $log = $log . "RetiroUsuarioDiaPagado: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
        fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
        print_r("PASO");

        $data = $BonoInterno->execQuery('', $sqlRetiroUsuarioDiaPendiente);


        foreach ($data as $datanum) {
            $sql = "INSERT INTO usuario_retiro_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, cantidad)
              VALUES ('" . $datanum->{'cuenta_cobro.usuarioId'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'.fecha_crea'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'.usumodif_id'} . "','" . $datanum->{'.estado'} . "','" . $datanum->{'.cantidad'} . "')";
            $BonoInterno->execQuery($transaccion, $sql);
        }

        $log = "\r\n" . "-------------------------" . "\r\n";
        $log = $log . "RetiroUsuarioDiaPendiente: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
        fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
        print_r("PASO");

        $data = $BonoInterno->execQuery('', $sqlRetiroUsuarioDiaEliminadas);


        foreach ($data as $datanum) {
            $sql = "INSERT INTO usuario_retiro_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, cantidad)
              VALUES ('" . $datanum->{'cuenta_cobro.usuarioId'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'.fecha_crea'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'.usumodif_id'} . "','" . $datanum->{'.estado'} . "','" . $datanum->{'.cantidad'} . "')";
            $BonoInterno->execQuery($transaccion, $sql);
        }

        $log = "\r\n" . "-------------------------" . "\r\n";
        $log = $log . "RetiroUsuarioDiaEliminadas: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
        fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
        print_r("PASO");

        $data = $BonoInterno->execQuery('', $sqlRetiroUsuarioDiaRechazadas);


        foreach ($data as $datanum) {
            $sql = "INSERT INTO usuario_retiro_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, cantidad)
              VALUES ('" . $datanum->{'cuenta_cobro.usuarioId'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'.fecha_crea'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'.usumodif_id'} . "','" . $datanum->{'.estado'} . "','" . $datanum->{'.cantidad'} . "')";
            $BonoInterno->execQuery($transaccion, $sql);
        }

        $log = "\r\n" . "-------------------------" . "\r\n";
        $log = $log . "RetiroUsuarioDiaRechazadas: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
        fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
        print_r("PASO");


        $data = $BonoInterno->execQuery('', $sqlRetiroPuntoVentaDiaPagado);


        foreach ($data as $datanum) {
            $sql = "INSERT INTO usuario_retiro_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, cantidad)
              VALUES ('" . $datanum->{'cuenta_cobro.usuarioId'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'.fecha_crea'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'.usumodif_id'} . "','" . $datanum->{'.estado'} . "','" . $datanum->{'.cantidad'} . "')";
            $BonoInterno->execQuery($transaccion, $sql);
        }

        $log = "\r\n" . "-------------------------" . "\r\n";
        $log = $log . "RetiroPuntoVentaDiaPagado: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
        fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
        print_r("PASO");

        $data = $BonoInterno->execQuery('', $sqlRetiroUsuarioPendienteHoy);

        foreach ($data as $datanum) {
            $sql = "INSERT INTO usuario_retiro_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, cantidad)
              VALUES ('" . $datanum->{'cuenta_cobro.usuarioId'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'.fecha_crea'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'.usumodif_id'} . "','" . $datanum->{'.estado'} . "','" . $datanum->{'.cantidad'} . "')";
            $BonoInterno->execQuery($transaccion, $sql);
        }

        $log = "\r\n" . "-------------------------" . "\r\n";
        $log = $log . "sqlRetiroUsuarioPendienteHoy: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
        fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
        print_r("PASO");

        $data = $BonoInterno->execQuery('', $sqlRetiroProductosPagado);


        foreach ($data as $datanum) {
            $sql = "INSERT INTO usuario_retiro_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, cantidad,producto_id)
              VALUES ('" . $datanum->{'cuenta_cobro.usuarioId'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'.fecha_crea'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'.usumodif_id'} . "','" . $datanum->{'.estado'} . "','" . $datanum->{'.cantidad'} . "','" . $datanum->{'transaccion_producto.producto_id'} . "')";
            $BonoInterno->execQuery($transaccion, $sql);
        }

        $log = "\r\n" . "-------------------------" . "\r\n";
        $log = $log . "sqlRetiroProductosPagado: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
        fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
        print_r("PASO");
    }

        $data = $BonoInterno->execQuery('', $sqlApuestasDeportivasUsuarioDia);


        foreach ($data as $datanum) {
            $sql = "INSERT INTO usuario_deporte_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, tipo, cantidad)
              VALUES ('" . $datanum->{'it_transaccion.usuarioId'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'.fecha_crea'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'.usumodif_id'} . "','" . $datanum->{'.estado'} . "','" . $datanum->{'it_transaccion.tipo'} . "','" . $datanum->{'.cantidad'} . "')";
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
              VALUES ('" . $datanum->{'it_ticket_enc.usuarioId'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'.fecha_crea'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'.usumodif_id'} . "','" . $datanum->{'.estado'} . "','" . $datanum->{'.tipo'} . "','" . $datanum->{'.cantidad'} . "')";
        $BonoInterno->execQuery($transaccion, $sql);
    }

    $data = $BonoInterno->execQuery('', $sqlApuestasDeportivasPuntoVentaoDiaCierre);


    foreach ($data as $datanum) {
        $sql = "INSERT INTO usuario_deporte_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, tipo, cantidad)
              VALUES ('" . $datanum->{'it_ticket_enc.usuarioId'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'it_ticket_enc.fecha_crea'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'.usumodif_id'} . "','" . $datanum->{'.estado'} . "','" . $datanum->{'.tipo'} . "','" . $datanum->{'.cantidad'} . "')";
        $BonoInterno->execQuery($transaccion, $sql);
    }



    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "ApuestasDeportivasPuntoVentaDia: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
    print_r("PASO");

    $data = $BonoInterno->execQuery('', $sqlPremiosDeportivasPuntoVentaoDia);


    foreach ($data as $datanum) {
        $sql = "INSERT INTO usuario_deporte_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, tipo, cantidad)
              VALUES ('" . $datanum->{'it_ticket_enc.usuarioId'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'it_ticket_enc.fecha_crea'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'.usumodif_id'} . "','" . $datanum->{'.estado'} . "','" . $datanum->{'.tipo'} . "','" . $datanum->{'.cantidad'} . "')";
        $BonoInterno->execQuery($transaccion, $sql);
    }

    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "PremiosDeportivasPuntoVentaDia: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
    print_r("PASO");

    $data = $BonoInterno->execQuery('', $sqlPremiosPagadosDeportivasPuntoVentaoDia);


    foreach ($data as $datanum) {
        $sql = "INSERT INTO usuario_deporte_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, tipo, cantidad)
              VALUES ('" . $datanum->{'it_ticket_enc.usuarioId'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'it_ticket_enc.fecha_crea'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'.usumodif_id'} . "','" . $datanum->{'.estado'} . "','" . $datanum->{'.tipo'} . "','" . $datanum->{'.cantidad'} . "')";
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

    $transaccion->commit();


    $BonoInterno = new BonoInterno();
    $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
    $transaccion = $BonoDetalleMySqlDAO->getTransaction();


    $data = $BonoInterno->execQuery('', $SelectTicketExpirados);


    foreach ($data as $datanum) {
        $sql = "  UPDATE it_ticket_enc SET estado='E' WHERE ticket_id='".$datanum->{'it_ticket_enc.ticket_id'}."' ";
        $BonoInterno->execQuery($transaccion, $sql);
    }

    $transaccion->commit();

    $BonoInterno = new BonoInterno();
    $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
    $transaccion = $BonoDetalleMySqlDAO->getTransaction();

    $data = $BonoInterno->execQuery('', $UsuarioSaldoFinalConDetalles);


    foreach ($data as $datanum) {
        $sql = "INSERT INTO usuario_saldo (usuario_id, mandante, fecha, saldo_recarga, saldo_apuestas, saldo_premios,
                           saldo_apuestas_casino, saldo_premios_casino, saldo_notaret_pagadas,
                           saldo_notaret_pend, saldo_notaret_creadas, saldo_ajustes_entrada, saldo_ajustes_salida,
                           saldo_inicial, saldo_final, saldo_bono, saldo_creditos_inicial, saldo_creditos_base_inicial,
                           saldo_creditos_final, saldo_creditos_base_final,saldo_notaret_eliminadas,saldo_bono_free_ganado) VALUES (
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
                           '" . $datanum->{'.saldo_bono_free_ganado'} . "'
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
                         usuario_saldo.saldo_bono_free_ganado      = '" . $datanum->{'.saldo_bono_free_ganado'} . "'
                         

       ";

        $BonoInterno->execQuery($transaccion, $sql);

    }



    $data = $BonoInterno->execQuery('', $UsuarioPuntoVentaFinalConDetalles);


    foreach ($data as $datanum) {
        $sql = "INSERT INTO usuario_saldo (usuario_id, mandante, fecha, saldo_recarga, saldo_apuestas, saldo_premios,
                           saldo_apuestas_casino, saldo_premios_casino, saldo_notaret_pagadas,
                           saldo_notaret_pend, saldo_notaret_creadas, saldo_ajustes_entrada, saldo_ajustes_salida,
                           saldo_inicial, saldo_final, saldo_bono, saldo_creditos_inicial, saldo_creditos_base_inicial,
                           saldo_creditos_final, saldo_creditos_base_final,saldo_notaret_eliminadas,saldo_bono_free_ganado) VALUES (
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
                           '" . $datanum->{'.saldo_bono_free_ganado'} . "'
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
                         usuario_saldo.saldo_bono_free_ganado      = '" . $datanum->{'.saldo_bono_free_ganado'} . "'
                         

       ";

        $BonoInterno->execQuery($transaccion, $sql);

    }

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

        exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

    }catch (Exception $e){
        print_r($e);
        $log = "\r\n" . "-------------------------" . "\r\n";
        $log = $log . "ERROR: " . $e->getMessage() . " - " . date('Y-m-d H:i:s');
        fwriteCustom('log_' . date("Y-m-d") . '.log',$log);


        $message = "*CRON: (ERROR) * " . " Cierres de caja - Fecha: " . date("Y-m-d H:i:s");

        exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

    }*/
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





