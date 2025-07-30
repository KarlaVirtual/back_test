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



require(__DIR__.'/../vendor/autoload.php');
///home/devadmin/api/api/

ini_set('memory_limit', '-1');

$message = "*CRON: (Inicio) * " . " Resumenes - Fecha: " . date("Y-m-d H:i:s");

exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");


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

//BETWEEN '".$fecha1."' AND '".$fecha2."'

    $strEliminado = "
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

    WHERE cuenta_cobro.estado='A'

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
  SUM(transjuego_log.valor) valor_premios,
  date_format(transjuego_log.fecha_crea, '%Y-%m-%d') fecha_crea,
  0 usucrea_id,
  0 usumodif_id,
  'A' estado, 
  '1' tipo,
  COUNT(*) cantidad
      FROM transjuego_log
        INNER JOIN transaccion_juego ON (transjuego_log.transjuego_id =transaccion_juego.transjuego_id)
             INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usumandante_id)

    WHERE date_format(transjuego_log.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND transjuego_log.tipo LIKE '%DEBIT%'
GROUP BY date_format(transjuego_log.fecha_crea, '%Y-%m-%d'), transaccion_juego.usuario_id
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
  '2' tipo,
  COUNT(*) cantidad
      FROM transjuego_log
        INNER JOIN transaccion_juego ON (transjuego_log.transjuego_id =transaccion_juego.transjuego_id)
             INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usumandante_id)

    WHERE date_format(transjuego_log.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND (transjuego_log.tipo LIKE '%CREDIT%'OR transjuego_log.tipo like '%ROLLBACK%')
GROUP BY date_format(transjuego_log.fecha_crea, '%Y-%m-%d'), transaccion_juego.usuario_id
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
  SUM(transjuego_log.valor) valor_premios,
  date_format(transjuego_log.fecha_crea, '%Y-%m-%d') fecha_crea,
  0 usucrea_id,
  0 usumodif_id,
  'A' estado,
  'DEBIT' tipo,
  COUNT(*) cantidad
      FROM transjuego_log
        INNER JOIN transaccion_juego ON (transjuego_log.transjuego_id =transaccion_juego.transjuego_id)
             INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usumandante_id)

    WHERE date_format(transjuego_log.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND transjuego_log.tipo LIKE '%DEBIT%'

GROUP BY date_format(transjuego_log.fecha_crea, '%Y-%m-%d'),transaccion_juego.producto_id, transaccion_juego.usuario_id
ORDER BY date_format(transjuego_log.fecha_crea, '%Y-%m-%d'),transaccion_juego.producto_id,transaccion_juego.usuario_id;";

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
  'CREDIT' tipo,
  COUNT(*) cantidad
      FROM transjuego_log
        INNER JOIN transaccion_juego ON (transjuego_log.transjuego_id =transaccion_juego.transjuego_id)
             INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usumandante_id)

    WHERE date_format(transjuego_log.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND (transjuego_log.tipo LIKE '%CREDIT%')

GROUP BY date_format(transjuego_log.fecha_crea, '%Y-%m-%d'),transaccion_juego.producto_id, transaccion_juego.usuario_id
ORDER BY date_format(transjuego_log.fecha_crea, '%Y-%m-%d'),transaccion_juego.producto_id,transaccion_juego.usuario_id;";


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
  'ROLLBACK' tipo,
  COUNT(*) cantidad
      FROM transjuego_log
        INNER JOIN transaccion_juego ON (transjuego_log.transjuego_id =transaccion_juego.transjuego_id)
             INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usumandante_id)

    WHERE date_format(transjuego_log.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND (transjuego_log.tipo like '%ROLLBACK%')

GROUP BY date_format(transjuego_log.fecha_crea, '%Y-%m-%d'),transaccion_juego.producto_id, transaccion_juego.usuario_id
ORDER BY date_format(transjuego_log.fecha_crea, '%Y-%m-%d'),transaccion_juego.producto_id,transaccion_juego.usuario_id;";


    $paso = true;


    $BonoInterno = new BonoInterno();
    $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
    $transaccion = $BonoDetalleMySqlDAO->getTransaction();
    $transaccion->getConnection()->beginTransaction();




    $data = $BonoInterno->execQuery('', $sqlRecargaUsuarioDia);
    foreach ($data as $datanum) {
        $sql = "INSERT INTO usuario_recarga_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, cantidad,mediopago_id,puntoventa_id)
              VALUES ('" . $datanum->{'usuario_recarga.usuarioId'} . "','" . $datanum->{'.valor'} . "','" . $datanum->{'.fecha_crea'} . "','" . $datanum->{'.usucrea_id'} . "','" . $datanum->{'.usumodif_id'} . "','" . $datanum->{'.estado'} . "','" . $datanum->{'.cantidad'} . "','" . $datanum->{'.mediopago_id'} . "','" . $datanum->{'usuario_recarga.puntoventa_id'} . "')";
        $BonoInterno->execQuery($transaccion, $sql);
    }


    $transaccion->commit();



    $message = "*CRON: (Fin) * " . " Resumenes - Fecha: " . date("Y-m-d H:i:s");

    exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");


} catch (Exception $e) {
    print_r($e);



    $message = "*CRON: (ERROR) * " . " Resumenes - Fecha: " . date("Y-m-d H:i:s");

    exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

}





