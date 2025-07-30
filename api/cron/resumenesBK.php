<?php
/**
 * Created by PhpStorm.
 * User: danielfelipetamayogarcia
 * Date: 18/10/17
 * Time: 11:40 PM
 */

use Backend\cron\ResumenesCron;
use Backend\dto\BonoInterno;
use Backend\mysql\BonoDetalleMySqlDAO;



require(__DIR__.'/../vendor/autoload.php');


$message="*CRON: (Inicio) * " . " Resumenes - Fecha: " . date("Y-m-d H:i:s");

exec("php -f ".__DIR__."../src/imports/Slack/message.php '".$message."' '#virtualsoft-cron' > /dev/null & ");



$ResumenesCron = new ResumenesCron();

//$ResumenesCron->generateResumenes();

$fechaSoloDia = date("Y-m-d", strtotime( '-1 days' ) );
$fecha1 = date("Y-m-d 00:00:00", strtotime( '-1 days' ) );
$fecha2 = date("Y-m-d 23:59:59",strtotime( '-1 days' )  );

if($_REQUEST["diaSpc"] != ""){
    exec("php -f ".__DIR__."/resumenes.php ".$_REQUEST["diaSpc"]." > /dev/null &");
exit();
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
    $sqlRecargaUsuarioDia = "INSERT INTO usuario_recarga_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, cantidad,mediopago_id,puntoventa_id)
  SELECT
    usuario_recarga.usuario_id,
    SUM(usuario_recarga.valor),
    date_format(usuario_recarga.fecha_crea, '%Y-%m-%d'),
    0,
    0,
    'I',
    COUNT(*) cantidad,
    0,
    puntoventa_id
  FROM usuario_recarga
    WHERE date_format(usuario_recarga.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "' and mediopago_id = 0
  GROUP BY date_format(usuario_recarga.fecha_crea, '%Y-%m-%d'), usuario_recarga.usuario_id,usuario_recarga.puntoventa_id
  ORDER BY date_format(usuario_recarga.fecha_crea, '%Y-%m-%d'), usuario_recarga.usuario_id,usuario_recarga.puntoventa_id;";

    /* Recargas hechos por PUNTO DE VENTA por dia*/


    $sqlRecargaPuntoVentaDia = "INSERT INTO usuario_recarga_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, cantidad)
  SELECT
    usuario_recarga.puntoventa_id,
    SUM(usuario_recarga.valor),
    date_format(usuario_recarga.fecha_crea, '%Y-%m-%d'),
    0,
    0,
    'I',
    COUNT(*) cantidad
  FROM usuario_recarga
    WHERE date_format(usuario_recarga.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND usuario_recarga.puntoventa_id != 0
  GROUP BY date_format(usuario_recarga.fecha_crea, '%Y-%m-%d'), usuario_recarga.puntoventa_id
  ORDER BY date_format(usuario_recarga.fecha_crea, '%Y-%m-%d'), usuario_recarga.puntoventa_id;";


    /* Recargas hechos por PASARELAS por dia*/

    $sqlRecargaPasarelaDia = "INSERT INTO usuario_recarga_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, cantidad, mediopago_id)
  SELECT
    usuario_recarga.usuario_id,
    SUM(usuario_recarga.valor),
    date_format(usuario_recarga.fecha_crea, '%Y-%m-%d'),
    0,
    0,
    'I',
    COUNT(*) cantidad,
    transaccion_producto.producto_id

  FROM usuario_recarga

    INNER JOIN transaccion_producto ON (transaccion_producto.final_id = usuario_recarga.recarga_id)
    WHERE date_format(usuario_recarga.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND  usuario_recarga.puntoventa_id = 0
  GROUP BY date_format(usuario_recarga.fecha_crea, '%Y-%m-%d'),usuario_recarga.usuario_id, transaccion_producto.producto_id
  ORDER BY date_format(usuario_recarga.fecha_crea, '%Y-%m-%d'),usuario_recarga.usuario_id, transaccion_producto.producto_id;";

    /* Retiros pagados a USUARIOS por dia*/

    $sqlRetiroUsuarioDiaPagado = "INSERT INTO usuario_retiro_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, cantidad,puntoventa_id)
  SELECT
    cuenta_cobro.usuario_id,
    SUM(cuenta_cobro.valor),
    date_format(cuenta_cobro.fecha_pago, '%Y-%m-%d'),
    0,
    0,
    'I',
    COUNT(*) cantidad,
    cuenta_cobro.puntoventa_id
  FROM cuenta_cobro
    WHERE cuenta_cobro.estado='I' AND date_format(cuenta_cobro.fecha_pago, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND cuenta_cobro.puntoventa_id != 0
  GROUP BY date_format(cuenta_cobro.fecha_pago, '%Y-%m-%d'), cuenta_cobro.usuario_id,cuenta_cobro.puntoventa_id
  ORDER BY date_format(cuenta_cobro.fecha_pago, '%Y-%m-%d'), cuenta_cobro.usuario_id,cuenta_cobro.puntoventa_id;";

    /* Retiros PENDIENTES a USUARIOS por dia*/

    $sqlRetiroUsuarioDiaPendiente = "INSERT INTO usuario_retiro_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, cantidad)
  SELECT
    cuenta_cobro.usuario_id,
    SUM(cuenta_cobro.valor),
    date_format(cuenta_cobro.fecha_crea, '%Y-%m-%d'),
    0,
    0,
    'A',
    COUNT(*) cantidad
  FROM cuenta_cobro

    WHERE date_format(cuenta_cobro.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND cuenta_cobro.estado='A'

  GROUP BY date_format(cuenta_cobro.fecha_crea, '%Y-%m-%d'), cuenta_cobro.usuario_id
  ORDER BY date_format(cuenta_cobro.fecha_crea, '%Y-%m-%d'), cuenta_cobro.usuario_id;";

    /* Retiros PENDIENTES a USUARIOS HOY*/

    $sqlRetiroUsuarioPendienteHoy = "INSERT INTO usuario_retiro_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, cantidad)
  SELECT
    cuenta_cobro.usuario_id,
    SUM(cuenta_cobro.valor),
    date_format(cuenta_cobro.fecha_crea, '%Y-%m-%d'),
    0,
    0,
    'P',
    COUNT(*) cantidad
  FROM cuenta_cobro

    WHERE cuenta_cobro.estado='A'

  GROUP BY  cuenta_cobro.usuario_id
  ORDER BY  cuenta_cobro.usuario_id;";


    /* Retiros  pagados por PUNTOS DE VENTA por dia*/

    $sqlRetiroPuntoVentaDiaPagado = "INSERT INTO usuario_retiro_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, cantidad)
  SELECT
    cuenta_cobro.puntoventa_id,
    SUM(cuenta_cobro.valor),
    date_format(cuenta_cobro.fecha_pago, '%Y-%m-%d'),
    0,
    0,
    'A',
    COUNT(*) cantidad
  FROM cuenta_cobro


    WHERE cuenta_cobro.estado='I' AND date_format(cuenta_cobro.fecha_pago, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND cuenta_cobro.puntoventa_id != 0

  GROUP BY date_format(cuenta_cobro.fecha_pago, '%Y-%m-%d'), cuenta_cobro.puntoventa_id
  ORDER BY date_format(cuenta_cobro.fecha_pago, '%Y-%m-%d'), cuenta_cobro.puntoventa_id;";

    /* Retiros  pagados por Productos por dia*/

    $sqlRetiroProductosPagado = "INSERT INTO usuario_retiro_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, cantidad,producto_id)
  SELECT
    cuenta_cobro.usuario_id,
    SUM(cuenta_cobro.valor),
    date_format(cuenta_cobro.fecha_pago, '%Y-%m-%d'),
    0,
    0,
    'A',
    COUNT(*) cantidad,
    transaccion_producto.producto_id
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

    $sqlApuestasDeportivasUsuarioDia = "INSERT INTO usuario_deporte_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, tipo, cantidad)
    SELECT
  it_transaccion.usuario_id,
  SUM(it_transaccion.valor),
  date_format(it_transaccion.fecha_crea, '%Y-%m-%d'),
      0,
      0,
      'A',
      tipo,
  COUNT(*) cantidad
FROM it_transaccion
  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = it_transaccion.usuario_id)

    WHERE date_format(it_transaccion.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND usuario_perfil.perfil_id = \"USUONLINE\" 
GROUP BY date_format(it_transaccion.fecha_crea, '%Y-%m-%d'), it_transaccion.usuario_id
ORDER BY date_format(it_transaccion.fecha_crea, '%Y-%m-%d'),it_transaccion.usuario_id;";

    $sqlApuestasDeportivasPuntoVentaDia = "INSERT INTO usuario_deporte_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, tipo, cantidad)
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

    WHERE date_format(it_ticket_enc.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND usuario_perfil.perfil_id = \"PUNTOVENTA\" 
GROUP BY date_format(it_ticket_enc.fecha_crea, '%Y-%m-%d'), it_ticket_enc.usuario_id
ORDER BY date_format(it_ticket_enc.fecha_crea, '%Y-%m-%d'),it_ticket_enc.usuario_id;";

    $sqlPremiosDeportivasPuntoVentaoDia = "INSERT INTO usuario_deporte_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, tipo, cantidad)

SELECT
  it_ticket_enc.usuario_id,
  SUM(it_ticket_enc.vlr_premio),
  it_ticket_enc.fecha_pago,
  0,
  0,
  'P',
  '2',
  COUNT(*) cantidad
FROM it_ticket_enc
  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = it_ticket_enc.usuario_id)

    WHERE date_format(it_ticket_enc.fecha_pago, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND usuario_perfil.perfil_id = \"PUNTOVENTA\" AND it_ticket_enc.premiado='S' AND it_ticket_enc.eliminado='N'

GROUP BY it_ticket_enc.fecha_pago, it_ticket_enc.usuario_id
ORDER BY it_ticket_enc.usuario_id;";

    $sqlPremiosDeportivasPuntoVentaoDiaCONTIPO = "INSERT INTO usuario_deporte_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, tipo, cantidad)

SELECT
  it_transaccion.usuario_id,
  SUM(it_transaccion.valor),
  it_transaccion.fecha_crea,
  0,
  0,
  'A',
  tipo,
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

    $sqlApuestasCasinoDia = "INSERT INTO usuario_casino_resumen (usuario_id, valor,valor_premios, fecha_crea, usucrea_id, usumodif_id, estado, tipo, cantidad)
SELECT
  transaccion_juego.usuario_id,
  SUM(transjuego_log.valor),
  SUM(transjuego_log.valor),
  date_format(transjuego_log.fecha_crea, '%Y-%m-%d'),
  0,
  0,
  'A',
  '1',
  COUNT(*) cantidad
      FROM transjuego_log
        INNER JOIN transaccion_juego ON (transjuego_log.transjuego_id =transaccion_juego.transjuego_id)
             INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usumandante_id)

    WHERE date_format(transjuego_log.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND transjuego_log.tipo LIKE '%DEBIT%'
GROUP BY date_format(transjuego_log.fecha_crea, '%Y-%m-%d'), transaccion_juego.usuario_id
ORDER BY date_format(transjuego_log.fecha_crea, '%Y-%m-%d'),transaccion_juego.usuario_id;";

    $sqlPremiosCasinoDia = "INSERT INTO usuario_casino_resumen (usuario_id, valor,valor_premios, fecha_crea, usucrea_id, usumodif_id, estado, tipo, cantidad)
SELECT
  transaccion_juego.usuario_id,
  SUM(transjuego_log.valor),
  SUM(transjuego_log.valor),
  date_format(transjuego_log.fecha_crea, '%Y-%m-%d'),
  0,
  0,
  'A',
  '2',
  COUNT(*) cantidad
      FROM transjuego_log
        INNER JOIN transaccion_juego ON (transjuego_log.transjuego_id =transaccion_juego.transjuego_id)
             INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usuario_mandante)

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

    $sqlDetalleApuesCasinoDia = "INSERT INTO usucasino_detalle_resumen (usuario_id, producto_id, valor,valor_premios, fecha_crea, usucrea_id, usumodif_id, estado, tipo, cantidad)
SELECT
  transaccion_juego.usuario_id,
  transaccion_juego.producto_id,
  SUM(transjuego_log.valor),
  SUM(transjuego_log.valor),
  date_format(transjuego_log.fecha_crea, '%Y-%m-%d'),
  0,
  0,
  'A',
  'DEBIT',
  COUNT(*) cantidad
      FROM transjuego_log
        INNER JOIN transaccion_juego ON (transjuego_log.transjuego_id =transaccion_juego.transjuego_id)
             INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usumandante_id)

    WHERE date_format(transjuego_log.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND transjuego_log.tipo LIKE '%DEBIT%'

GROUP BY date_format(transjuego_log.fecha_crea, '%Y-%m-%d'),transaccion_juego.producto_id, transaccion_juego.usuario_id
ORDER BY date_format(transjuego_log.fecha_crea, '%Y-%m-%d'),transaccion_juego.producto_id,transaccion_juego.usuario_id;";

    $sqlDetallePremiosCasinoDia = "INSERT INTO usucasino_detalle_resumen (usuario_id, producto_id, valor,valor_premios, fecha_crea, usucrea_id, usumodif_id, estado, tipo, cantidad)
SELECT
  transaccion_juego.usuario_id,
  transaccion_juego.producto_id,
  SUM(transjuego_log.valor),
  SUM(transjuego_log.valor),
  date_format(transjuego_log.fecha_crea, '%Y-%m-%d'),
  0,
  0,
  'A',
  'CREDIT',
  COUNT(*) cantidad
      FROM transjuego_log
        INNER JOIN transaccion_juego ON (transjuego_log.transjuego_id =transaccion_juego.transjuego_id)
             INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usumandante_id)

    WHERE date_format(transjuego_log.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND (transjuego_log.tipo LIKE '%CREDIT%')

GROUP BY date_format(transjuego_log.fecha_crea, '%Y-%m-%d'),transaccion_juego.producto_id, transaccion_juego.usuario_id
ORDER BY date_format(transjuego_log.fecha_crea, '%Y-%m-%d'),transaccion_juego.producto_id,transaccion_juego.usuario_id;";


    $sqlDetalleRollbackCasinoDia = "INSERT INTO usucasino_detalle_resumen (usuario_id, producto_id, valor,valor_premios, fecha_crea, usucrea_id, usumodif_id, estado, tipo, cantidad)
SELECT
  transaccion_juego.usuario_id,
  transaccion_juego.producto_id,
  SUM(transjuego_log.valor),
  SUM(transjuego_log.valor),
  date_format(transjuego_log.fecha_crea, '%Y-%m-%d'),
  0,
  0,
  'A',
  'ROLLBACK',
  COUNT(*) cantidad
      FROM transjuego_log
        INNER JOIN transaccion_juego ON (transjuego_log.transjuego_id =transaccion_juego.transjuego_id)
             INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usumandante_id)

    WHERE date_format(transjuego_log.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND (transjuego_log.tipo like '%ROLLBACK%')

GROUP BY date_format(transjuego_log.fecha_crea, '%Y-%m-%d'),transaccion_juego.producto_id, transaccion_juego.usuario_id
ORDER BY date_format(transjuego_log.fecha_crea, '%Y-%m-%d'),transaccion_juego.producto_id,transaccion_juego.usuario_id;";


    $sqlBonoUsuarioCreados = "INSERT INTO usuario_bono_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, tipo, cantidad)

SELECT
  bono_log.usuario_id,
  SUM(bono_log.valor),
  bono_log.fecha_crea,
  0,
  0,
  bono_log.estado,
  bono_log.tipo,
  COUNT(*) cantidad
FROM bono_log
  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = bono_log.usuario_id)

    WHERE bono_log.fecha_crea is not null

  and date_format(bono_log.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND usuario_perfil.perfil_id = \"USUONLINE\" 

GROUP BY bono_log.tipo,bono_log.estado,bono_log.fecha_crea, bono_log.usuario_id
ORDER BY bono_log.usuario_id;";


    $sqlUsuarioAjustesDia = "INSERT INTO usuario_ajustes_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, tipo, cantidad,tipo_ajuste,proveedor_id)

SELECT
  saldo_usuonline_ajuste.usuario_id,
  SUM(saldo_usuonline_ajuste.valor),
  saldo_usuonline_ajuste.fecha_crea,
  0,
  0,
  saldo_usuonline_ajuste.tipo_id,
  COUNT(*) cantidad,
  tipo,
  proveedor_id
FROM saldo_usuonline_ajuste
  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = saldo_usuonline_ajuste.usuario_id)

    WHERE  date_format(saldo_usuonline_ajuste.fecha_crea, '%Y-%m-%d') = '" . $fechaSoloDia . "' AND usuario_perfil.perfil_id = \"USUONLINE\"

GROUP BY tipo,proveedor_id,saldo_usuonline_ajuste.tipo_id,saldo_usuonline_ajuste.fecha_crea, saldo_usuonline_ajuste.usuario_id
ORDER BY saldo_usuonline_ajuste.usuario_id;";


    $UsuarioSaldoInicial = "INSERT INTO usuario_saldo (usuario_id, mandante, fecha, saldo_recarga, saldo_apuestas, saldo_premios,
                           saldo_apuestas_casino, saldo_premios_casino, saldo_notaret_pagadas,
                           saldo_notaret_pend, saldo_notaret_creadas, saldo_ajustes_entrada, saldo_ajustes_salida,
                           saldo_inicial, saldo_final, saldo_bono, saldo_creditos_inicial, saldo_creditos_base_inicial,
                           saldo_creditos_final, saldo_creditos_base_final)


SELECT registro.usuario_id,
       registro.mandante,
       DATE_FORMAT(DATE_SUB(now(), INTERVAL 1 DAY), '%Y-%m-%d'),
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

ON DUPLICATE KEY UPDATE usuario_saldo.saldo_creditos_final      = registro.creditos,
                        usuario_saldo.saldo_creditos_base_final = registro.creditos_base;

";


    $UsuarioSaldoFinal = "INSERT INTO usuario_saldo (usuario_id, mandante, fecha, saldo_recarga, saldo_apuestas, saldo_premios,
                           saldo_apuestas_casino, saldo_premios_casino, saldo_notaret_pagadas,
                           saldo_notaret_pend, saldo_notaret_creadas, saldo_ajustes_entrada, saldo_ajustes_salida,
                           saldo_inicial, saldo_final, saldo_bono, saldo_creditos_inicial, saldo_creditos_base_inicial,
                           saldo_creditos_final, saldo_creditos_base_final)


SELECT registro.usuario_id,
       registro.mandante,
       DATE_FORMAT(DATE_SUB(now(), INTERVAL 0 DAY), '%Y-%m-%d'),
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
       

FROM registro

ON DUPLICATE KEY UPDATE usuario_saldo.saldo_creditos_inicial      = registro.creditos,
                        usuario_saldo.saldo_creditos_base_inicial = registro.creditos_base;

";


    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "Inicia: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);


    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "Inicia: " . $UsuarioSaldoFinal . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);


    $paso = true;

    /*$BonoInterno = new BonoInterno();
    $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
    $transaccion = $BonoDetalleMySqlDAO->getTransaction();


    $BonoInterno->execQuery($transaccion,$UsuarioSaldoInicial);

    $transaccion->commit();*/


    $BonoInterno = new BonoInterno();
    $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
    $transaccion = $BonoDetalleMySqlDAO->getTransaction();


    $BonoInterno->execQuery($transaccion, $strEliminado);

    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "Eliminado: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
    print_r("PASO");

    $BonoInterno->execQuery($transaccion, $sqlRecargaUsuarioDia);


    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "RecargaUsuarioDia: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
    print_r("PASO");

    $BonoInterno->execQuery($transaccion, $sqlRecargaPuntoVentaDia);

    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "RecargaPuntoVentaDia: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
    print_r("PASO");

    $BonoInterno->execQuery($transaccion, $sqlRecargaPasarelaDia);

    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "RecargaPasarelaDia: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
    print_r("PASO");

    $BonoInterno->execQuery($transaccion, $sqlRetiroUsuarioDiaPagado);

    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "RetiroUsuarioDiaPagado: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
    print_r("PASO");

    $BonoInterno->execQuery($transaccion, $sqlRetiroUsuarioDiaPendiente);

    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "RetiroUsuarioDiaPendiente: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
    print_r("PASO");

    $BonoInterno->execQuery($transaccion, $sqlRetiroPuntoVentaDiaPagado);

    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "RetiroPuntoVentaDiaPagado: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
    print_r("PASO");

    $BonoInterno->execQuery($transaccion, $sqlRetiroUsuarioPendienteHoy);

    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "sqlRetiroUsuarioPendienteHoy: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
    print_r("PASO");

    $BonoInterno->execQuery($transaccion, $sqlRetiroProductosPagado);

    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "sqlRetiroProductosPagado: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
    print_r("PASO");


    $BonoInterno->execQuery($transaccion, $sqlApuestasDeportivasUsuarioDia);

    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "ApuestasDeportivasUsuarioDia: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
    print_r("PASO");

    /*$BonoInterno->execQuery($transaccion,$sqlPremiosDeportivasUsuarioDia);

    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log ."PremiosDeportivasUsuarioDia: ".$fechaSoloDia ." - ". date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
    print_r("PASO");*/

    $BonoInterno->execQuery($transaccion, $sqlApuestasDeportivasPuntoVentaDia);

    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "ApuestasDeportivasPuntoVentaDia: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
    print_r("PASO");

    $BonoInterno->execQuery($transaccion, $sqlPremiosDeportivasPuntoVentaoDia);

    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "PremiosDeportivasPuntoVentaDia: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
    print_r("PASO");

    $BonoInterno->execQuery($transaccion, $sqlApuestasCasinoDia);

    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "ApuestasCasinoDia: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
    print_r("PASO");

    $BonoInterno->execQuery($transaccion, $sqlPremiosCasinoDia);

    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "PremiosCasinoDia: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
    print_r("PASO");


    $BonoInterno->execQuery($transaccion, $sqlDetalleApuesCasinoDia);

    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "DetalleApuesCasinoDia: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
    print_r("PASO");


    $BonoInterno->execQuery($transaccion, $sqlDetallePremiosCasinoDia);


    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "DetallePremiosCasinoDia: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
    print_r("PASO");


    $BonoInterno->execQuery($transaccion, $sqlDetalleRollbackCasinoDia);


    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "DetalleRollbackCasinoDia: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
    print_r("PASO");


    $BonoInterno->execQuery($transaccion, $sqlBonoUsuarioCreados);


    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "BonoUsuarioCreados: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
    print_r("PASO");


    $BonoInterno->execQuery($transaccion, $sqlUsuarioAjustesDia);


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

    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "Terminacion: " . $fechaSoloDia . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);

    $message = "*CRON: (Fin) * " . " Resumenes - Fecha: " . date("Y-m-d H:i:s");

    exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");


}catch (Exception $e){

    $log = "\r\n" . "-------------------------" . "\r\n";
    $log = $log . "ERROR: " . $e->getMessage() . " - " . date('Y-m-d H:i:s');
    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);


    $message="*CRON: (ERROR) * " . " Resumenes - Fecha: " . date("Y-m-d H:i:s");

    exec("php -f ".__DIR__."../src/imports/Slack/message.php '".$message."' '#virtualsoft-cron' > /dev/null & ");

}

