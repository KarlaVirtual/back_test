<?php namespace Backend\cron;
use Backend\dto\BonoInterno;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\dto\Mandante;
/** 
* Clase 'ResumenesCron'
* 
* Esta clase provee un resumen con registros y estadísticas para el usuario actual
* 
* Ejemplo de uso: 
* $ResumenesCron = new ResumenesCron();
* 
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class ResumenesCron
{

    /**
    * Constructor de clase
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct()
    {
    }

    /**
    * Obtener el resumen del usuario
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function generateResumenes()
    {

        $fecha1 = date("Y-m-d 00:00:00", strtotime( '-1 days' ) );
        $fecha2 = date("Y-m-d 23:59:59",strtotime( '-1 days' )  );

        $sqlRecargaUsuarioDia = "INSERT INTO usuario_recarga_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, cantidad)
  SELECT
    usuario_recarga.usuario_id,
    SUM(usuario_recarga.valor),
    date_format(usuario_recarga.fecha_crea, '%Y-%m-%d'),
    0,
    0,
    'I',
    COUNT(*) cantidad
  FROM usuario_recarga
    WHERE date_format(usuario_recarga.fecha_crea, '%Y-%m-%d') BETWEEN '".$fecha1."' AND '".$fecha2."'
  GROUP BY date_format(usuario_recarga.fecha_crea, '%Y-%m-%d'), usuario_recarga.usuario_id
  ORDER BY date_format(usuario_recarga.fecha_crea, '%Y-%m-%d'), usuario_recarga.usuario_id;";


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
    WHERE date_format(usuario_recarga.fecha_crea, '%Y-%m-%d') BETWEEN '".$fecha1."' AND '".$fecha2."' AND usuario_recarga.puntoventa_id != 0
  GROUP BY date_format(usuario_recarga.fecha_crea, '%Y-%m-%d'), usuario_recarga.puntoventa_id
  ORDER BY date_format(usuario_recarga.fecha_crea, '%Y-%m-%d'), usuario_recarga.puntoventa_id;";


        $sqlRecargaPasarelaDia = "INSERT INTO usuario_recarga_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, cantidad, mediopago_id)
  SELECT
    0,
    SUM(usuario_recarga.valor),
    date_format(usuario_recarga.fecha_crea, '%Y-%m-%d'),
    0,
    0,
    'I',
    COUNT(*) cantidad,
    transaccion_producto.producto_id

  FROM usuario_recarga

    INNER JOIN transaccion_producto ON (transaccion_producto.final_id = usuario_recarga.recarga_id)
    WHERE date_format(usuario_recarga.fecha_crea, '%Y-%m-%d') BETWEEN '".$fecha1."' AND '".$fecha2."' AND  usuario_recarga.puntoventa_id = 0
  GROUP BY date_format(usuario_recarga.fecha_crea, '%Y-%m-%d'), transaccion_producto.producto_id
  ORDER BY date_format(usuario_recarga.fecha_crea, '%Y-%m-%d'), transaccion_producto.producto_id;";

        $sqlRetiroUsuarioDiaPagado = "INSERT INTO usuario_retiro_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, cantidad)
  SELECT
    cuenta_cobro.usuario_id,
    SUM(cuenta_cobro.valor),
    date_format(cuenta_cobro.fecha_pago, '%Y-%m-%d'),
    0,
    0,
    'I',
    COUNT(*) cantidad
  FROM cuenta_cobro
    WHERE cuenta_cobro.fecha_pago != NULL AND date_format(cuenta_cobro.fecha_pago, '%Y-%m-%d') BETWEEN '".$fecha1."' AND '".$fecha2."'
  GROUP BY date_format(cuenta_cobro.fecha_pago, '%Y-%m-%d'), cuenta_cobro.usuario_id
  ORDER BY date_format(cuenta_cobro.fecha_pago, '%Y-%m-%d'), cuenta_cobro.usuario_id;";

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

    WHERE date_format(cuenta_cobro.fecha_crea, '%Y-%m-%d') BETWEEN '".$fecha1."' AND '".$fecha2."' AND cuenta_cobro.puntoventa_id = 0

  GROUP BY date_format(cuenta_cobro.fecha_crea, '%Y-%m-%d'), cuenta_cobro.usuario_id
  ORDER BY date_format(cuenta_cobro.fecha_crea, '%Y-%m-%d'), cuenta_cobro.usuario_id;";

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


    WHERE cuenta_cobro.fecha_pago != NULL AND date_format(cuenta_cobro.fecha_pago, '%Y-%m-%d') BETWEEN '".$fecha1."' AND '".$fecha2."' AND cuenta_cobro.puntoventa_id != 0

  GROUP BY date_format(cuenta_cobro.fecha_pago, '%Y-%m-%d'), cuenta_cobro.puntoventa_id
  ORDER BY date_format(cuenta_cobro.fecha_pago, '%Y-%m-%d'), cuenta_cobro.puntoventa_id;";

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

    WHERE date_format(it_ticket_enc.fecha_crea, '%Y-%m-%d') BETWEEN '".$fecha1."' AND '".$fecha2."' AND usuario_perfil.perfil_id = \"USUONLINE\" AND it_ticket_enc.eliminado='N'
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

    WHERE date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d') BETWEEN '".$fecha1."' AND '".$fecha2."' AND usuario_perfil.perfil_id = \"USUONLINE\" AND it_ticket_enc.premiado='S' AND it_ticket_enc.eliminado='N'

GROUP BY date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d'), it_ticket_enc.usuario_id
ORDER BY date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d'),  it_ticket_enc.usuario_id;";

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

    WHERE date_format(it_ticket_enc.fecha_crea, '%Y-%m-%d') BETWEEN '".$fecha1."' AND '".$fecha2."' AND usuario_perfil.perfil_id = \"PUNTOVENTA\" AND it_ticket_enc.eliminado='N'
GROUP BY date_format(it_ticket_enc.fecha_crea, '%Y-%m-%d'), it_ticket_enc.usuario_id
ORDER BY date_format(it_ticket_enc.fecha_crea, '%Y-%m-%d'),it_ticket_enc.usuario_id;";

        $sqlPremiosDeportivasPuntoVentaoDia = "INSERT INTO usuario_deporte_resumen (usuario_id, valor, fecha_crea, usucrea_id, usumodif_id, estado, tipo, cantidad)

SELECT
  it_ticket_enc.usuario_id,
  SUM(it_ticket_enc.vlr_premio),
  it_ticket_enc.fecha_cierre,
  0,
  0,
  'P',
  '2',
  COUNT(*) cantidad
FROM it_ticket_enc
  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = it_ticket_enc.usuario_id)

    WHERE date_format(it_ticket_enc.fecha_cierre, '%Y-%m-%d') BETWEEN '".$fecha1."' AND '".$fecha2."' AND usuario_perfil.perfil_id = \"PUNTOVENTA\" AND it_ticket_enc.premiado='S' AND it_ticket_enc.eliminado='N'

GROUP BY it_ticket_enc.fecha_cierre, it_ticket_enc.usuario_id
ORDER BY it_ticket_enc.usuario_id;";

        $sqlApuestasCasinoDia="INSERT INTO usuario_casino_resumen (usuario_id, valor,valor_premios, fecha_crea, usucrea_id, usumodif_id, estado, tipo, cantidad)
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

    WHERE date_format(transaccion_juego.fecha_crea, '%Y-%m-%d') BETWEEN '".$fecha1."' AND '".$fecha2."' AND usuario_perfil.perfil_id ='USUONLINE'

GROUP BY date_format(transaccion_juego.fecha_crea, '%Y-%m-%d'), transaccion_juego.usuario_id
ORDER BY date_format(transaccion_juego.fecha_crea, '%Y-%m-%d'),transaccion_juego.usuario_id;";

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

    WHERE date_format(transaccion_juego.fecha_crea, '%Y-%m-%d') BETWEEN '".$fecha1."' AND '".$fecha2."'AND usuario_perfil.perfil_id = 'USUONLINE'

GROUP BY date_format(transaccion_juego.fecha_crea, '%Y-%m-%d'),transaccion_juego.producto_id, transaccion_juego.usuario_id
ORDER BY date_format(transaccion_juego.fecha_crea, '%Y-%m-%d'),transaccion_juego.producto_id,transaccion_juego.usuario_id;";


        $BonoInterno = new BonoInterno();
        $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
        $transaccion = $BonoDetalleMySqlDAO->getTransaction();

        $BonoInterno->execQuery($transaccion,$sqlRecargaUsuarioDia);


        print_r("test");
        $log = "\r\n" . "-------------------------" . "\r\n";
        $log = $log ."RecargaUsuarioDia". date('Y-m-d H:i:s');
         fwriteCustom('log_' . date("Y-m-d") . '.log',$log);
        print_r("test2");

        $BonoInterno->execQuery($transaccion,$sqlRecargaPuntoVentaDia);

        $log = "\r\n" . "-------------------------" . "\r\n";
        $log = $log ."RecargaPuntoVentaDia". date('Y-m-d H:i:s');
        fwriteCustom('log_' . date("Y-m-d") . '.log',$log);

        $BonoInterno->execQuery($transaccion,$sqlRecargaPasarelaDia);

        $log = "\r\n" . "-------------------------" . "\r\n";
        $log = $log ."RecargaPasarelaDia". date('Y-m-d H:i:s');
        fwriteCustom('log_' . date("Y-m-d") . '.log',$log);

        $BonoInterno->execQuery($transaccion,$sqlRetiroUsuarioDiaPagado);

        $log = "\r\n" . "-------------------------" . "\r\n";
        $log = $log ."RetiroUsuarioDiaPagado". date('Y-m-d H:i:s');
        fwriteCustom('log_' . date("Y-m-d") . '.log',$log);

        $BonoInterno->execQuery($transaccion,$sqlRetiroUsuarioDiaPendiente);

        $log = "\r\n" . "-------------------------" . "\r\n";
        $log = $log ."RetiroUsuarioDiaPendiente". date('Y-m-d H:i:s');
        fwriteCustom('log_' . date("Y-m-d") . '.log',$log);

        $BonoInterno->execQuery($transaccion,$sqlRetiroPuntoVentaDiaPagado);

        $log = "\r\n" . "-------------------------" . "\r\n";
        $log = $log ."RetiroPuntoVentaDiaPagado". date('Y-m-d H:i:s');
        fwriteCustom('log_' . date("Y-m-d") . '.log',$log);

        $BonoInterno->execQuery($transaccion,$sqlApuestasDeportivasUsuarioDia);

        $log = "\r\n" . "-------------------------" . "\r\n";
        $log = $log ."ApuestasDeportivasUsuarioDia". date('Y-m-d H:i:s');
        fwriteCustom('log_' . date("Y-m-d") . '.log',$log);

        $BonoInterno->execQuery($transaccion,$sqlPremiosDeportivasUsuarioDia);

        $log = "\r\n" . "-------------------------" . "\r\n";
        $log = $log ."PremiosDeportivasUsuarioDia". date('Y-m-d H:i:s');
        fwriteCustom('log_' . date("Y-m-d") . '.log',$log);

        $BonoInterno->execQuery($transaccion,$sqlApuestasDeportivasPuntoVentaDia);

        $log = "\r\n" . "-------------------------" . "\r\n";
        $log = $log ."ApuestasDeportivasPuntoVentaDia". date('Y-m-d H:i:s');
        fwriteCustom('log_' . date("Y-m-d") . '.log',$log);

        $BonoInterno->execQuery($transaccion,$sqlPremiosDeportivasPuntoVentaoDia);

        $log = "\r\n" . "-------------------------" . "\r\n";
        $log = $log ."PremiosDeportivasPuntoVentaDia". date('Y-m-d H:i:s');
        fwriteCustom('log_' . date("Y-m-d") . '.log',$log);

        $BonoInterno->execQuery($transaccion,$sqlApuestasCasinoDia);

        $log = "\r\n" . "-------------------------" . "\r\n";
        $log = $log ."ApuestasCasinoDia". date('Y-m-d H:i:s');
        fwriteCustom('log_' . date("Y-m-d") . '.log',$log);

        $BonoInterno->execQuery($transaccion,$sqlPremiosCasinoDia);

        $log = "\r\n" . "-------------------------" . "\r\n";
        $log = $log ."PremiosCasinoDia". date('Y-m-d H:i:s');
        fwriteCustom('log_' . date("Y-m-d") . '.log',$log);



    }
}


