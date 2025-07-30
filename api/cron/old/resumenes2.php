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
use Backend\mysql\BonoDetalleMySqlDAO;



require(__DIR__.'/../vendor/autoload.php');

ini_set('memory_limit', '-1');

$message="*CRON: (Inicio) * " . " Resumenes - Fecha: " . date("Y-m-d H:i:s");

exec("php -f ".__DIR__."../src/imports/Slack/message.php '".$message."' '#virtualsoft-cron' > /dev/null & ");



$ResumenesCron = new ResumenesCron();

//$ResumenesCron->generateResumenes();

$fechaSoloDia = date("Y-m-d", strtotime( '-1 days' ) );
$fecha1 = date("Y-m-d 00:00:00", strtotime( '-1 days' ) );
$fecha2 = date("Y-m-d 23:59:59",strtotime( '-1 days' )  );

if($_REQUEST["diaSpc"] != ""){
    exit();

    exec("php -f ".__DIR__."/resumenes.php ".$_REQUEST["diaSpc"]." > /dev/null &");

    $fechaSoloDia = date("Y-m-d", strtotime( $_REQUEST["diaSpc"] ) );
    $fecha1 = date("Y-m-d 00:00:00", strtotime( $_REQUEST["diaSpc"] ) );
    $fecha2 = date("Y-m-d 23:59:59",strtotime( $_REQUEST["diaSpc"] )  );

}else{
    $arg1 = $argv[1];
    if($arg1 != ""){
        $fechaSoloDia = date("Y-m-d", strtotime( $arg1 ) );
        $fecha1 = date("Y-m-d 00:00:00", strtotime( $arg1 ) );
        $fecha2 = date("Y-m-d 23:59:59",strtotime( $arg1 )  );

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
    WHERE date_format(usuario_recarga.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "' and puntoventa_id != 0
  GROUP BY date_format(usuario_recarga.fecha_crea, '%Y-%m-%d'), usuario_recarga.usuario_id,usuario_recarga.puntoventa_id
  ORDER BY date_format(usuario_recarga.fecha_crea, '%Y-%m-%d'), usuario_recarga.usuario_id,usuario_recarga.puntoventa_id;";

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
  ORDER BY date_format(usuario_recarga.fecha_crea, '%Y-%m-%d'), usuario_recarga.puntoventa_id;";


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
    'A' estado,
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

    WHERE date_format(it_ticket_enc.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND usuario_perfil.perfil_id = \"PUNTOVENTA\" 
GROUP BY date_format(it_ticket_enc.fecha_crea, '%Y-%m-%d'), it_ticket_enc.usuario_id
ORDER BY date_format(it_ticket_enc.fecha_crea, '%Y-%m-%d'),it_ticket_enc.usuario_id;";

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

    WHERE date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND usuario_perfil.perfil_id = \"PUNTOVENTA\" AND it_ticket_enc.premiado='S' AND it_ticket_enc.eliminado='N'

GROUP BY it_ticket_enc.fecha_cierre, it_ticket_enc.usuario_id
ORDER BY it_ticket_enc.usuario_id;";


    $sqlPremiosPagadosDeportivasPuntoVentaoDia = "
SELECT
  it_ticket_enc.usuario_id usuarioId,
  SUM(it_ticket_enc.vlr_premio) valor,
  it_ticket_enc.fecha_pago fecha_crea,
  0 usucrea_id,
  0 usumodif_id,
  'P' estado,
  '3' tipo,
  COUNT(*) cantidad
FROM it_ticket_enc
  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = it_ticket_enc.usuario_id)

    WHERE date_format(it_ticket_enc.fecha_pago, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND usuario_perfil.perfil_id = \"PUNTOVENTA\" AND it_ticket_enc.premiado='S' AND it_ticket_enc.eliminado='N'

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

    WHERE date_format(it_transaccion.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND usuario_perfil.perfil_id = \"PUNTOVENTA\"

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


    $UsuarioSaldoInicialFIX = "
SELECT usuario_saldo.usuario_id,
       usuario_saldo.mandante,
       usuario_saldo.fecha,
       usuario_saldo.saldo_creditos_final,
       usuario_saldo.saldo_creditos_base_final,
       usuario_saldo.saldo_final
FROM usuario_saldo
where DATE_FORMAT(fecha, '%Y-%m-%d')   ='".$fechaSoloDia."'

";

    $UsuarioSaldoInicialFIX222 = "
SELECT usuario_saldo.usuario_id,
       usuario_saldo.mandante,
       usuario_saldo.fecha,
       usuario_saldo.saldo_creditos_inicial,
       usuario_saldo.saldo_creditos_base_inicial,
       usuario_saldo.saldo_inicial
FROM usuario_saldo
where DATE_FORMAT(fecha, '%Y-%m-%d')   ='".$fechaSoloDia."'

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
    WHERE DATE_FORMAT(fecha_crea, '%Y-%m-%d') = '".$fechaSoloDia."'
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
    WHERE DATE_FORMAT(fecha_crea, '%Y-%m-%d') = '".$fechaSoloDia."'
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
        WHERE DATE_FORMAT(fecha_crea, '%Y-%m-%d')   ='".$fechaSoloDia."'
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
        WHERE tipo IN ('BET', 'STAKEDECREASE', 'REFUND') AND  DATE_FORMAT(fecha_crea, '%Y-%m-%d')   ='".$fechaSoloDia."'
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
        WHERE tipo IN ('WIN', 'NEWCREDIT', 'CASHOUT', 'NEWDEBIT') AND  DATE_FORMAT(fecha_crea, '%Y-%m-%d')   ='".$fechaSoloDia."'
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
               0                                   saldo_bono_free_ganado
        FROM casino.usuario_casino_resumen
                                 INNER JOIN usuario_mandante ON (usumandante_id=usuario_id)
        WHERE tipo = '1' AND  DATE_FORMAT(usuario_casino_resumen.fecha_crea, '%Y-%m-%d')   ='".$fechaSoloDia."'
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

         WHERE tipo = '2' AND  DATE_FORMAT(usuario_casino_resumen.fecha_crea, '%Y-%m-%d')   ='".$fechaSoloDia."'
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
        WHERE estado = 'I' AND  DATE_FORMAT(usuario_retiro_resumen.fecha_crea, '%Y-%m-%d')   ='".$fechaSoloDia."'
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
        WHERE estado = 'P' AND  DATE_FORMAT(usuario_retiro_resumen.fecha_crea, '%Y-%m-%d')   ='".$fechaSoloDia."'
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
        WHERE estado = 'A' AND  DATE_FORMAT(fecha_crea, '%Y-%m-%d')   ='".$fechaSoloDia."'
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
        WHERE tipo = 'E' AND  DATE_FORMAT(fecha_crea, '%Y-%m-%d')   ='".$fechaSoloDia."'
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
        WHERE tipo = 'S' AND  DATE_FORMAT(fecha_crea, '%Y-%m-%d')   ='".$fechaSoloDia."'
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
        WHERE DATE_FORMAT(fecha_crea, '%Y-%m-%d')   ='".$fechaSoloDia."'
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
        WHERE (estado = 'R' OR estado = 'E') AND  DATE_FORMAT(fecha_crea, '%Y-%m-%d')   ='".$fechaSoloDia."'
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
        WHERE DATE_FORMAT(fecha_crea, '%Y-%m-%d')   ='".$fechaSoloDia."'
        GROUP BY usuario_id
       )
       
     ) data
     INNER JOIN usuario ON (data.usuario_id = usuario.usuario_id)
       LEFT OUTER JOIN (select usuario_id,saldo_inicial,saldo_creditos_inicial,saldo_creditos_base_inicial
                        from usuario_saldo
                        WHERE fecha=DATE_FORMAT(DATE_ADD('".$fechaSoloDia."', INTERVAL 1 DAY), '%Y-%m-%d')) data2
                       ON (data.usuario_id = data2.usuario_id)

WHERE data.usuario_id IS NOT NULL
  AND data.fecha = '".$fechaSoloDia."'

GROUP BY data.usuario_id;

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


    $dataSaldoInicial=$BonoInterno->execQuery($transaccion,$UsuarioSaldoInicial);


    foreach ($dataSaldoInicial as $datanum) {
        $sql="INSERT INTO usuario_saldo (usuario_id, mandante, fecha, saldo_recarga, saldo_apuestas, saldo_premios,
                           saldo_apuestas_casino, saldo_premios_casino, saldo_notaret_pagadas,
                           saldo_notaret_pend, saldo_notaret_creadas, saldo_ajustes_entrada, saldo_ajustes_salida,
                           saldo_inicial, saldo_final, saldo_bono, saldo_creditos_inicial, saldo_creditos_base_inicial,
                           saldo_creditos_final, saldo_creditos_base_final) VALUES ('".$datanum->{'registro.usuario_id'}."','".$datanum->{'registro.mandante'}."',DATE_FORMAT(DATE_ADD('".$fechaSoloDia."', INTERVAL 0 DAY), '%Y-%m-%d'),0,
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
       '".((floatval($datanum->{'registro.creditos'})+floatval($datanum->{'registro.creditos_base'})))."',
       0,
       0,
       0,'".$datanum->{'registro.creditos'}."','".$datanum->{'registro.creditos_base'}."')
       
       
ON DUPLICATE KEY UPDATE usuario_saldo.saldo_final      = '".((floatval($datanum->{'registro.creditos'})+floatval($datanum->{'registro.creditos_base'})))."',
                        usuario_saldo.saldo_creditos_final      = '".$datanum->{'registro.creditos'}."',
                        usuario_saldo.saldo_creditos_base_final = '".$datanum->{'registro.creditos_base'}."';

       ";
        $BonoInterno->execQuery($transaccion, $sql);

        $sql="INSERT INTO usuario_saldo (usuario_id, mandante, fecha, saldo_recarga, saldo_apuestas, saldo_premios,
                           saldo_apuestas_casino, saldo_premios_casino, saldo_notaret_pagadas,
                           saldo_notaret_pend, saldo_notaret_creadas, saldo_ajustes_entrada, saldo_ajustes_salida,
                           saldo_inicial, saldo_final, saldo_bono, saldo_creditos_inicial, saldo_creditos_base_inicial,
                           saldo_creditos_final, saldo_creditos_base_final) VALUES ('".$datanum->{'registro.usuario_id'}."','".$datanum->{'registro.mandante'}."',DATE_FORMAT(DATE_ADD('".$fechaSoloDia."', INTERVAL 1 DAY), '%Y-%m-%d'),0,
       0,
       0,
       0,
       0,
       0,
       0,
       0,
       0,
       0,
       '".((floatval($datanum->{'registro.creditos'})+floatval($datanum->{'registro.creditos_base'})))."',
       0,
       0,'".$datanum->{'registro.creditos'}."','".$datanum->{'registro.creditos_base'}."',0,0)
       
       
ON DUPLICATE KEY UPDATE usuario_saldo.saldo_inicial      = '".((floatval($datanum->{'registro.creditos'})+floatval($datanum->{'registro.creditos_base'})))."',
                        usuario_saldo.saldo_creditos_inicial      = '".$datanum->{'registro.creditos'}."',
                        usuario_saldo.saldo_creditos_base_inicial = '".$datanum->{'registro.creditos_base'}."';

       ";

        $BonoInterno->execQuery($transaccion, $sql);

    }

    $transaccion->commit();


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


    $BonoInterno->execQuery($transaccion, $strEliminado);

    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "Eliminado: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
    print_r("PASO");

    $data=$BonoInterno->execQuery('', $sqlRecargaUsuarioDia);
    foreach ($data as $datanum) {
        $sql="INSERT INTO usuario_recarga_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, cantidad,mediopago_id,puntoventa_id)
              VALUES ('".$datanum->{'usuario_recarga.usuarioId'}."','".$datanum->{'.valor'}."','".$datanum->{'.fecha_crea'}."','".$datanum->{'.usucrea_id'}."','".$datanum->{'.usumodif_id'}."','".$datanum->{'.estado'}."','".$datanum->{'.cantidad'}."','".$datanum->{'.mediopago_id'}."','".$datanum->{'usuario_recarga.puntoventa_id'}."')";
        $BonoInterno->execQuery($transaccion, $sql);
    }



    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "RecargaUsuarioDia: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
    print_r("PASO");

    $data=$BonoInterno->execQuery('', $sqlRecargaPuntoVentaDia);

    foreach ($data as $datanum) {

        $sql="INSERT INTO usuario_recarga_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, cantidad)
              VALUES ('".$datanum->{'usuario_recarga.usuarioId'}."','".$datanum->{'.valor'}."','".$datanum->{'.fecha_crea'}."','".$datanum->{'.usucrea_id'}."','".$datanum->{'.usumodif_id'}."','".$datanum->{'.estado'}."','".$datanum->{'.cantidad'}."')";
        $BonoInterno->execQuery($transaccion, $sql);
    }

    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "RecargaPuntoVentaDia: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
    print_r("PASO");

    $data=$BonoInterno->execQuery('', $sqlRecargaPasarelaDia);


    foreach ($data as $datanum) {
        $sql="INSERT INTO usuario_recarga_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, cantidad,mediopago_id)
              VALUES ('".$datanum->{'usuario_recarga.usuarioId'}."','".$datanum->{'.valor'}."','".$datanum->{'.fecha_crea'}."','".$datanum->{'.usucrea_id'}."','".$datanum->{'.usumodif_id'}."','".$datanum->{'.estado'}."','".$datanum->{'.cantidad'}."','".$datanum->{'transaccion_producto.mediopago_id'}."')";
        $BonoInterno->execQuery($transaccion, $sql);
    }

    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "RecargaPasarelaDia: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
    print_r("PASO");

    $data=$BonoInterno->execQuery('', $sqlRetiroUsuarioDiaPagado);



    foreach ($data as $datanum) {
        $sql="INSERT INTO usuario_retiro_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, cantidad,puntoventa_id)
              VALUES ('".$datanum->{'cuenta_cobro.usuarioId'}."','".$datanum->{'.valor'}."','".$datanum->{'.fecha_crea'}."','".$datanum->{'.usucrea_id'}."','".$datanum->{'.usumodif_id'}."','".$datanum->{'.estado'}."','".$datanum->{'.cantidad'}."','".$datanum->{'cuenta_cobro.puntoventa_id'}."')";
        $BonoInterno->execQuery($transaccion, $sql);
    }

    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "RetiroUsuarioDiaPagado: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
    print_r("PASO");

    $data=$BonoInterno->execQuery('', $sqlRetiroUsuarioDiaPagadoCuentaBancariaFisicamente);



    foreach ($data as $datanum) {
        $sql="INSERT INTO usuario_retiro_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, cantidad,puntoventa_id)
              VALUES ('".$datanum->{'cuenta_cobro.usuarioId'}."','".$datanum->{'.valor'}."','".$datanum->{'.fecha_crea'}."','".$datanum->{'.usucrea_id'}."','".$datanum->{'.usumodif_id'}."','".$datanum->{'.estado'}."','".$datanum->{'.cantidad'}."','0')";
        $BonoInterno->execQuery($transaccion, $sql);
    }

    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "RetiroUsuarioDiaPagado: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
    print_r("PASO");

    $data=$BonoInterno->execQuery('', $sqlRetiroUsuarioDiaPendiente);



    foreach ($data as $datanum) {
        $sql="INSERT INTO usuario_retiro_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, cantidad)
              VALUES ('".$datanum->{'cuenta_cobro.usuarioId'}."','".$datanum->{'.valor'}."','".$datanum->{'.fecha_crea'}."','".$datanum->{'.usucrea_id'}."','".$datanum->{'.usumodif_id'}."','".$datanum->{'.estado'}."','".$datanum->{'.cantidad'}."')";
        $BonoInterno->execQuery($transaccion, $sql);
    }

    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "RetiroUsuarioDiaPendiente: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
    print_r("PASO");

    $data=$BonoInterno->execQuery('', $sqlRetiroUsuarioDiaEliminadas);



    foreach ($data as $datanum) {
        $sql="INSERT INTO usuario_retiro_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, cantidad)
              VALUES ('".$datanum->{'cuenta_cobro.usuarioId'}."','".$datanum->{'.valor'}."','".$datanum->{'.fecha_crea'}."','".$datanum->{'.usucrea_id'}."','".$datanum->{'.usumodif_id'}."','".$datanum->{'.estado'}."','".$datanum->{'.cantidad'}."')";
        $BonoInterno->execQuery($transaccion, $sql);
    }

    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "RetiroUsuarioDiaEliminadas: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
    print_r("PASO");

    $data=$BonoInterno->execQuery('', $sqlRetiroUsuarioDiaRechazadas);



    foreach ($data as $datanum) {
        $sql="INSERT INTO usuario_retiro_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, cantidad)
              VALUES ('".$datanum->{'cuenta_cobro.usuarioId'}."','".$datanum->{'.valor'}."','".$datanum->{'.fecha_crea'}."','".$datanum->{'.usucrea_id'}."','".$datanum->{'.usumodif_id'}."','".$datanum->{'.estado'}."','".$datanum->{'.cantidad'}."')";
        $BonoInterno->execQuery($transaccion, $sql);
    }

    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "RetiroUsuarioDiaRechazadas: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
    print_r("PASO");



    $data=$BonoInterno->execQuery('', $sqlRetiroPuntoVentaDiaPagado);




    foreach ($data as $datanum) {
        $sql="INSERT INTO usuario_retiro_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, cantidad)
              VALUES ('".$datanum->{'cuenta_cobro.usuarioId'}."','".$datanum->{'.valor'}."','".$datanum->{'.fecha_crea'}."','".$datanum->{'.usucrea_id'}."','".$datanum->{'.usumodif_id'}."','".$datanum->{'.estado'}."','".$datanum->{'.cantidad'}."')";
        $BonoInterno->execQuery($transaccion, $sql);
    }

    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "RetiroPuntoVentaDiaPagado: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
    print_r("PASO");

    $data=$BonoInterno->execQuery('', $sqlRetiroUsuarioPendienteHoy);

    foreach ($data as $datanum) {
        $sql="INSERT INTO usuario_retiro_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, cantidad)
              VALUES ('".$datanum->{'cuenta_cobro.usuarioId'}."','".$datanum->{'.valor'}."','".$datanum->{'.fecha_crea'}."','".$datanum->{'.usucrea_id'}."','".$datanum->{'.usumodif_id'}."','".$datanum->{'.estado'}."','".$datanum->{'.cantidad'}."')";
        $BonoInterno->execQuery($transaccion, $sql);
    }

    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "sqlRetiroUsuarioPendienteHoy: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
    print_r("PASO");

    $data=$BonoInterno->execQuery('', $sqlRetiroProductosPagado);


    foreach ($data as $datanum) {
        $sql="INSERT INTO usuario_retiro_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, cantidad,producto_id)
              VALUES ('".$datanum->{'cuenta_cobro.usuarioId'}."','".$datanum->{'.valor'}."','".$datanum->{'.fecha_crea'}."','".$datanum->{'.usucrea_id'}."','".$datanum->{'.usumodif_id'}."','".$datanum->{'.estado'}."','".$datanum->{'.cantidad'}."','".$datanum->{'transaccion_producto.producto_id'}."')";
        $BonoInterno->execQuery($transaccion, $sql);
    }

    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "sqlRetiroProductosPagado: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
    print_r("PASO");


    $data=$BonoInterno->execQuery('', $sqlApuestasDeportivasUsuarioDia);


    foreach ($data as $datanum) {
        $sql="INSERT INTO usuario_deporte_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, tipo, cantidad)
              VALUES ('".$datanum->{'it_transaccion.usuarioId'}."','".$datanum->{'.valor'}."','".$datanum->{'.fecha_crea'}."','".$datanum->{'.usucrea_id'}."','".$datanum->{'.usumodif_id'}."','".$datanum->{'.estado'}."','".$datanum->{'it_transaccion.tipo'}."','".$datanum->{'.cantidad'}."')";
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

    $data=$BonoInterno->execQuery('', $sqlApuestasDeportivasPuntoVentaDia);



    foreach ($data as $datanum) {
        $sql="INSERT INTO usuario_deporte_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, tipo, cantidad)
              VALUES ('".$datanum->{'it_ticket_enc.usuarioId'}."','".$datanum->{'.valor'}."','".$datanum->{'.fecha_crea'}."','".$datanum->{'.usucrea_id'}."','".$datanum->{'.usumodif_id'}."','".$datanum->{'.estado'}."','".$datanum->{'.tipo'}."','".$datanum->{'.cantidad'}."')";
        $BonoInterno->execQuery($transaccion, $sql);
    }

    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "ApuestasDeportivasPuntoVentaDia: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
    print_r("PASO");

    $data=$BonoInterno->execQuery('', $sqlPremiosDeportivasPuntoVentaoDia);



    foreach ($data as $datanum) {
        $sql="INSERT INTO usuario_deporte_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, tipo, cantidad)
              VALUES ('".$datanum->{'it_ticket_enc.usuarioId'}."','".$datanum->{'.valor'}."','".$datanum->{'it_ticket_enc.fecha_crea'}."','".$datanum->{'.usucrea_id'}."','".$datanum->{'.usumodif_id'}."','".$datanum->{'.estado'}."','".$datanum->{'.tipo'}."','".$datanum->{'.cantidad'}."')";
        $BonoInterno->execQuery($transaccion, $sql);
    }

    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "PremiosDeportivasPuntoVentaDia: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
    print_r("PASO");

    $data=$BonoInterno->execQuery('', $sqlPremiosPagadosDeportivasPuntoVentaoDia);



    foreach ($data as $datanum) {
        $sql="INSERT INTO usuario_deporte_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, tipo, cantidad)
              VALUES ('".$datanum->{'it_ticket_enc.usuarioId'}."','".$datanum->{'.valor'}."','".$datanum->{'it_ticket_enc.fecha_crea'}."','".$datanum->{'.usucrea_id'}."','".$datanum->{'.usumodif_id'}."','".$datanum->{'.estado'}."','".$datanum->{'.tipo'}."','".$datanum->{'.cantidad'}."')";
        $BonoInterno->execQuery($transaccion, $sql);
    }

    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "PremiosPagadosDeportivasPuntoVentaoDia: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
    print_r("PASO");



    $data=$BonoInterno->execQuery('', $sqlApuestasCasinoDia);


    foreach ($data as $datanum) {
        $sql="INSERT INTO usuario_casino_resumen (usuario_id, valor,valor_premios, fecha_crea, usucrea_id, usumodif_id, estado, tipo, cantidad)
              VALUES ('".$datanum->{'transaccion_juego.usuarioId'}."','".$datanum->{'.valor'}."','".$datanum->{'.valor_premios'}."','".$datanum->{'.fecha_crea'}."','".$datanum->{'.usucrea_id'}."','".$datanum->{'.usumodif_id'}."','".$datanum->{'.estado'}."','".$datanum->{'.tipo'}."','".$datanum->{'.cantidad'}."')";
        $BonoInterno->execQuery($transaccion, $sql);
    }


    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "ApuestasCasinoDia: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
    print_r("PASO");

    $data=$BonoInterno->execQuery('', $sqlPremiosCasinoDia);


    foreach ($data as $datanum) {
        $sql="INSERT INTO usuario_casino_resumen (usuario_id, valor,valor_premios, fecha_crea, usucrea_id, usumodif_id, estado, tipo, cantidad)
              VALUES ('".$datanum->{'transaccion_juego.usuarioId'}."','".$datanum->{'.valor'}."','".$datanum->{'.valor_premios'}."','".$datanum->{'.fecha_crea'}."','".$datanum->{'.usucrea_id'}."','".$datanum->{'.usumodif_id'}."','".$datanum->{'.estado'}."','".$datanum->{'.tipo'}."','".$datanum->{'.cantidad'}."')";
        $BonoInterno->execQuery($transaccion, $sql);
    }


    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "PremiosCasinoDia: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
    print_r("PASO");


    $data=$BonoInterno->execQuery('', $sqlDetalleApuesCasinoDia);

    foreach ($data as $datanum) {
        $sql="INSERT INTO usucasino_detalle_resumen (usuario_id, producto_id, valor,valor_premios, fecha_crea, usucrea_id, usumodif_id, estado, tipo, cantidad)
              VALUES ('".$datanum->{'transaccion_juego.usuarioId'}."','".$datanum->{'transaccion_juego.producto_id'}."','".$datanum->{'.valor'}."','".$datanum->{'.valor_premios'}."','".$datanum->{'.fecha_crea'}."','".$datanum->{'.usucrea_id'}."','".$datanum->{'.usumodif_id'}."','".$datanum->{'.estado'}."','".$datanum->{'.tipo'}."','".$datanum->{'.cantidad'}."')";
        $BonoInterno->execQuery($transaccion, $sql);
    }

    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "DetalleApuesCasinoDia: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
    print_r("PASO");


    $data=$BonoInterno->execQuery('', $sqlDetallePremiosCasinoDia);


    foreach ($data as $datanum) {
        $sql="INSERT INTO usucasino_detalle_resumen (usuario_id, producto_id, valor,valor_premios, fecha_crea, usucrea_id, usumodif_id, estado, tipo, cantidad)
              VALUES ('".$datanum->{'transaccion_juego.usuarioId'}."','".$datanum->{'transaccion_juego.producto_id'}."','".$datanum->{'.valor'}."','".$datanum->{'.valor_premios'}."','".$datanum->{'.fecha_crea'}."','".$datanum->{'.usucrea_id'}."','".$datanum->{'.usumodif_id'}."','".$datanum->{'.estado'}."','".$datanum->{'.tipo'}."','".$datanum->{'.cantidad'}."')";
        $BonoInterno->execQuery($transaccion, $sql);
    }



    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "DetallePremiosCasinoDia: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
    print_r("PASO");


    $data=$BonoInterno->execQuery('', $sqlDetalleRollbackCasinoDia);



    foreach ($data as $datanum) {
        $sql="INSERT INTO usucasino_detalle_resumen (usuario_id, producto_id, valor,valor_premios, fecha_crea, usucrea_id, usumodif_id, estado, tipo, cantidad)
              VALUES ('".$datanum->{'transaccion_juego.usuarioId'}."','".$datanum->{'transaccion_juego.producto_id'}."','".$datanum->{'.valor'}."','".$datanum->{'.valor_premios'}."','".$datanum->{'.fecha_crea'}."','".$datanum->{'.usucrea_id'}."','".$datanum->{'.usumodif_id'}."','".$datanum->{'.estado'}."','".$datanum->{'.tipo'}."','".$datanum->{'.cantidad'}."')";
        $BonoInterno->execQuery($transaccion, $sql);
    }


    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "DetalleRollbackCasinoDia: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
    print_r("PASO");


    $data= $BonoInterno->execQuery('', $sqlBonoUsuarioCreados);



    foreach ($data as $datanum) {
        $sql="INSERT INTO usuario_bono_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, tipo, cantidad)
              VALUES ('".$datanum->{'bono_log.usuarioId'}."','".$datanum->{'.valor'}."','".$datanum->{'bono_log.fecha_crea'}."','".$datanum->{'.usucrea_id'}."','".$datanum->{'.usumodif_id'}."','".$datanum->{'bono_log.estado'}."','".$datanum->{'bono_log.tipo'}."','".$datanum->{'.cantidad'}."')";
        $BonoInterno->execQuery($transaccion, $sql);
    }



    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "BonoUsuarioCreados: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
    print_r("PASO");


    $data= $BonoInterno->execQuery('', $sqlUsuarioAjustesDia);


    foreach ($data as $datanum) {
        $sql="INSERT INTO usuario_ajustes_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, tipo, cantidad,tipo_ajuste,proveedor_id)
              VALUES ('".$datanum->{'saldo_usuonline_ajuste.usuarioId'}."','".$datanum->{'.valor'}."','".$datanum->{'saldo_usuonline_ajuste.fecha_crea'}."','".$datanum->{'.usucrea_id'}."','".$datanum->{'.usumodif_id'}."','".$datanum->{'saldo_usuonline_ajuste.tipo'}."','".$datanum->{'.cantidad'}."','".$datanum->{'saldo_usuonline_ajuste.tipo_ajuste'}."','".$datanum->{'saldo_usuonline_ajuste.proveedor_id'}."')";
        $BonoInterno->execQuery($transaccion, $sql);
    }



    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "UsuarioAjustesDia: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
    print_r("PASO");

    $BonoInterno->execQuery($transaccion, "UPDATE bono_interno SET estado='I' WHERE fecha_fin <= now() AND estado='A';");


    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "Inactivacion Bonos: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
    print_r("PASO");

    $sqlExpirados = "UPDATE usuario_bono, registro, bono_detalle
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
    $BonoInterno->execQuery($transaccion, $sqlExpirados);

    $transaccion->commit();


    $BonoInterno = new BonoInterno();
    $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
    $transaccion = $BonoDetalleMySqlDAO->getTransaction();


    $data=$BonoInterno->execQuery('',$UsuarioSaldoFinalConDetalles);


    foreach ($data as $datanum) {
        $sql="INSERT INTO usuario_saldo (usuario_id, mandante, fecha, saldo_recarga, saldo_apuestas, saldo_premios,
                           saldo_apuestas_casino, saldo_premios_casino, saldo_notaret_pagadas,
                           saldo_notaret_pend, saldo_notaret_creadas, saldo_ajustes_entrada, saldo_ajustes_salida,
                           saldo_inicial, saldo_final, saldo_bono, saldo_creditos_inicial, saldo_creditos_base_inicial,
                           saldo_creditos_final, saldo_creditos_base_final,saldo_notaret_eliminadas,saldo_bono_free_ganado) VALUES (
                           '".$datanum->{'data.usuario_id'}."',
                           '".$datanum->{'usuario.mandante'}."',
                           '".$datanum->{'.fecha'}."',
                           '".$datanum->{'.saldo_recarga'}."',
                           '".$datanum->{'.saldo_apuestas'}."',
                           '".$datanum->{'.saldo_premios'}."',
                           '".$datanum->{'.saldo_apuestas_casino'}."',
                           '".$datanum->{'.saldo_premios_casino'}."',
                           '".$datanum->{'.saldo_notaret_pagadas'}."',
                           '".$datanum->{'.saldo_notaret_pend'}."',
                           '".$datanum->{'.saldo_notaret_creadas'}."',
                           '".$datanum->{'.saldo_ajustes_entrada'}."',
                           '".$datanum->{'.saldo_ajustes_salida'}."',
                           '".$datanum->{'.saldo_inicial'}."',
                           '".$datanum->{'.saldo_final'}."',
                           '".$datanum->{'.saldo_bono'}."',
                           '".$datanum->{'.saldo_creditos_inicial'}."',
                           '".$datanum->{'.saldo_creditos_base_inicial'}."',
                           '".$datanum->{'.saldo_creditos_final'}."',
                           '".$datanum->{'.saldo_creditos_base_final'}."',
                           '".$datanum->{'.saldo_notaret_eliminadas'}."',
                           '".$datanum->{'.saldo_bono_free_ganado'}."'
                                 )
       
       
ON DUPLICATE KEY UPDATE usuario_saldo.saldo_recarga      = '".$datanum->{'.saldo_recarga'}."',
                         usuario_saldo.saldo_apuestas      = '".$datanum->{'.saldo_apuestas'}."',
                         usuario_saldo.saldo_premios      = '".$datanum->{'.saldo_premios'}."',
                         usuario_saldo.saldo_apuestas_casino      = '".$datanum->{'.saldo_apuestas_casino'}."',
                         usuario_saldo.saldo_premios_casino      = '".$datanum->{'.saldo_premios_casino'}."',
                         usuario_saldo.saldo_notaret_pagadas      = '".$datanum->{'.saldo_notaret_pagadas'}."',
                         usuario_saldo.saldo_notaret_pend      = '".$datanum->{'.saldo_notaret_pend'}."',
                         usuario_saldo.saldo_notaret_creadas      = '".$datanum->{'.saldo_notaret_creadas'}."',
                         usuario_saldo.saldo_ajustes_entrada      = '".$datanum->{'.saldo_ajustes_entrada'}."',
                         usuario_saldo.saldo_ajustes_salida      = '".$datanum->{'.saldo_ajustes_salida'}."',
                         usuario_saldo.saldo_bono      = '".$datanum->{'.saldo_bono'}."',
                         usuario_saldo.saldo_notaret_eliminadas      = '".$datanum->{'.saldo_notaret_eliminadas'}."',
                         usuario_saldo.saldo_bono_free_ganado      = '".$datanum->{'.saldo_bono_free_ganado'}."'
                         

       ";

        $BonoInterno->execQuery($transaccion, $sql);

    }


    $transaccion->commit();

    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "Terminacion: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);

    $message = "*CRON: (Fin) * " . " Resumenes - Fecha: " . date("Y-m-d H:i:s");

    exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");


}catch (Exception $e){
    print_r($e);
    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "ERROR: " . $e->getMessage() . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);


    $message="*CRON: (ERROR) * " . " Resumenes - Fecha: " . date("Y-m-d H:i:s");

    exec("php -f ".__DIR__."../src/imports/Slack/message.php '".$message."' '#virtualsoft-cron' > /dev/null & ");

}

