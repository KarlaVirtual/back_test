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
use Backend\dto\TransaccionApi;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\UsuarioAutomation;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioMandante;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransjuegoLogMySqlDAO;
use Backend\mysql\UsuarioAutomationMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\sql\ConnectionProperty;
use Exception;
use Backend\utils\SlackVS;
use Backend\utils\BackgroundProcessVS;


/**
 * Clase 'CronJobAMonitorServer'
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
class CronJobReporteCasinoResumen
{

    private $SlackVS;
    private $BackgroundProcessVS;

    public function __construct()
    {
        $this->SlackVS = new SlackVS('monitor-server');
        $this->BackgroundProcessVS = new BackgroundProcessVS();

    }

    public function execute()
    {
        // Obtener la fecha y hora una hora antes
        $fecha_una_hora_antes = date('Y-m-d H', strtotime('-1 hour'));

        $sql="
INSERT INTO reporte_casino_resumen (mandante, pais_id, moneda, transaccion_juego_tipo, transjuego_log_tipo, valor,
                                    cantidad, fecha_crea, producto_id)
SELECT usuario.mandante,
       usuario.pais_id,
       usuario.moneda,
       transaccion_juego.tipo         transaccion_juego_tipo,
       transjuego_log.tipo            transjuego_log_tipo,
       sum(valor)                     valor,
       count(*)                       cantidad,
       MAX(transjuego_log.fecha_crea) fecha_crea,
       transaccion_juego.producto_id
FROM transjuego_log
         inner join transaccion_juego on transaccion_juego.transjuego_id = transjuego_log.transjuego_id
         inner join usuario_mandante on usuario_mandante.usumandante_id = transaccion_juego.usuario_id
         inner join usuario on usuario.usuario_id = usuario_mandante.usuario_mandante
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
WHERE 1 = 1

  AND transjuego_log.fecha_crea >= '".$fecha_una_hora_antes.":00:00'
  AND transjuego_log.fecha_crea <= '".$fecha_una_hora_antes.":59:59'
group by mandante.mandante, usuario.pais_id, transaccion_juego.tipo, transjuego_log.tipo, transaccion_juego.producto_id;
";

        $BonoInterno = new BonoInterno();
        $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
        $transaccion = $BonoDetalleMySqlDAO->getTransaction();


        $data = $BonoInterno->execQuery($transaccion, $sql);
        $transaccion->commit();

    }
}

