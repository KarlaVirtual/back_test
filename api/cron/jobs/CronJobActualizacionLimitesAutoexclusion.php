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
use Backend\dto\SubproveedorMandantePais;
use Backend\dto\TransaccionApi;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioAutomation;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioMandante;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransjuegoLogMySqlDAO;
use Backend\mysql\UsuarioAutomationMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\sql\ConnectionProperty;
use Backend\sql\SqlQuery;
use Backend\utils\RedisConnectionTrait;
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
class CronJobActualizacionLimitesAutoexclusion
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
        /* Conecta a Redis y recupera un valor basado en un clave generada. */
        $redis = RedisConnectionTrait::getRedisInstance(
            true,
            'redis-13988.c39707.us-central1-mz.gcp.cloud.rlrcp.com',
            13988,
            'LrWXJFKjCS9PYCnprkLA1gRCqhLEcu0D',
            'default'
        );

        $redisParam = ['ex' => 86400];

        if ($redis != null) {

            $BonoInterno = new BonoInterno();
            $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
            $transaccion = $BonoDetalleMySqlDAO->getTransaction();


            $sql = "
            
            SELECT usuario_configuracion.usuconfig_id,usuario_configuracion.fecha_modif as fechaModif, clasificador.abreviado as abreviado
FROM usuario_configuracion
         INNER JOIN clasificador ON (clasificador.clasificador_id = usuario_configuracion.tipo)
WHERE usuario_configuracion.estado = 'A' and clasificador.tipo='UC'
  AND clasificador.abreviado not in ('EXCPRODUCT','FAVORITEINTERFACETHEME',
                                'LIMITPERCLIENTTEST',
                                'LIMITEDEPOSITOANUAL',
                                'LIMITEDEPOSITOANUALDEFT',
                                'LIMITEDEPOSITOANUALGLOBAL',
                                'LIMITEDEPOSITODIARIO',
                                'LIMITEDEPOSITODIARIODEFT',
                                'LIMITEDEPOSITODIARIOGLOBAL',
                                'LIMITEDEPOSITOMENSUAL',
                                'LIMITEDEPOSITOMENSUALDEFT',
                                'LIMITEDEPOSITOMENSUALGLOBAL',
                                'LIMITEDEPOSITOSEMANA',
                                'LIMITEDEPOSITOSEMANADEFT',
                                'LIMITEDEPOSITOSEMANAGLOBAL',
                                'LIMITEDEPOSITOSIMPLE',
                                'LIMITEDEPOSITOSIMPLEDEFT',
                                'LIMITEDEPOSITOSIMPLEGLOBAL'
    )
    
";
            print_r($sql);
            $data = $BonoInterno->execQuery($transaccion, $sql);


            foreach ($data as $datum) {
                $datum = json_decode(json_encode($datum), true);
                $fecha = $datum;
                $UsuarioConfiguracion = new UsuarioConfiguracion('','','','',$fecha['usuario_configuracion.usuconfig_id']);
                $Usuario = new Usuario($UsuarioConfiguracion->getUsuarioId());
                $UsuarioMandante = new UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);
                $usumandanteId = $UsuarioMandante->usumandanteId;

                $fechaModifDiario = "";
                $fechaModifDiario2 = "";
                $fechaModifSemana = "";
                $fechaModifSemana2 = "";
                $fechaModifMensual = "";
                $fechaModifMensual2 = "";
                $fechaModifAnual = "";
                $fechaModifAnual2 = "";


                if ($fecha['clasificador.abreviado'] == "LIMAPUCASINODIARIO") {

                    $diff = ((time() - strtotime($fecha['usuario_configuracion.fechaModif'])) % 86400);
                    $fechaModifDiario = date("Y-m-d H:i:s", strtotime("- $diff sec"));
                    $diff2 = ((time() - strtotime($fecha['usuario_configuracion.fechaModif'])) % 3600);
                    $fechaModifDiario2 = date("Y-m-d H:i:s", strtotime("- $diff2 sec"));

                }
                if ($fecha['clasificador.abreviado'] == "LIMAPUCASINOSEMANA") {
                    $diff = ((time() - strtotime($fecha['usuario_configuracion.fechaModif'])) % 604800);
                    $fechaModifSemana = date("Y-m-d H:i:s", strtotime("- $diff sec"));
                    $diff2 = ((time() - strtotime($fecha['usuario_configuracion.fechaModif'])) % 3600);
                    $fechaModifSemana2 = date("Y-m-d H:i:s", strtotime("- $diff2 sec"));
                }

                if ($fecha['clasificador.abreviado'] == "LIMAPUCASINOMENSUAL") {
                    $diff = ((time() - strtotime($fecha['usuario_configuracion.fechaModif'])) % 2592000);
                    $fechaModifMensual = date("Y-m-d H:i:s", strtotime("- $diff sec"));
                    $diff2 = ((time() - strtotime($fecha['usuario_configuracion.fechaModif'])) % 3600);
                    $fechaModifMensual2 = date("Y-m-d H:i:s", strtotime("- $diff2 sec"));
                }
                if ($fecha['clasificador.abreviado'] == "LIMAPUCASINOANUAL") {
                    $diff = ((time() - strtotime($fecha['usuario_configuracion.fechaModif'])) % 31536000);
                    $fechaModifAnual = date("Y-m-d H:i:s", strtotime("- $diff sec"));
                    $diff2 = ((time() - strtotime($fecha['usuario_configuracion.fechaModif'])) % 3600);
                    $fechaModifAnual2 = date("Y-m-d H:i:s", strtotime("- $diff2 sec"));
                }


                if ($fechaModifDiario != '' && $fechaModifDiario2 != '') {

                    $sql = "SELECT SUM(valor) valor,valorconfig
      FROM (SELECT 0 valor, usuario_configuracion.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_configuracion
                    INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINODIARIO' AND
                                                clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "'
              AND usuario_configuracion.estado = 'A' 
            UNION 
              SELECT SUM(usuario_configuracion_resumen.valor) valor, usuario_configuracion.usuario_id, 0 valorconfig
            FROM usuario_configuracion_resumen
                     INNER JOIN usuario_configuracion
                                ON (usuario_configuracion.usuconfig_id =
                                    usuario_configuracion_resumen.usuconfig_id)
                    INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINODIARIO' AND
                                                clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "'
              AND usuario_configuracion.estado = 'A' 
              AND usuario_configuracion_resumen.fecha_crea >= '$fechaModifDiario'

            UNION

            SELECT SUM(CASE
               WHEN transjuego_log.tipo LIKE ('DEBIT%') THEN transjuego_log.valor
               WHEN transjuego_log.tipo LIKE ('ROLLBACK%') THEN transjuego_log.valor
               ELSE 0 END)                                                               valor, usuario_mandante.usuario_mandante, usuario_configuracion.valor valorconfig
            FROM transjuego_log
                     INNER JOIN transaccion_juego ON (transaccion_juego.transjuego_id = transjuego_log.transjuego_id)
                     INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usuario_mandante.usumandante_id)
                     INNER JOIN producto_mandante ON (producto_mandante.prodmandante_id = transaccion_juego.producto_id)
                     INNER JOIN producto ON (producto.producto_id = producto_mandante.producto_id)
                     INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id)
                     INNER JOIN usuario_configuracion ON (usuario_mandante.usuario_mandante = usuario_configuracion.usuario_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINODIARIO' AND clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_mandante.usumandante_id = $usumandanteId AND proveedor.tipo ='CASINO'
              AND usuario_configuracion.estado = 'A'
              AND transjuego_log.fecha_crea >= '$fechaModifDiario2'
                  
                              
           ) data2
";



                }
                if ($fechaModifSemana != '' && $fechaModifSemana2 != '') {
                    $sql = "SELECT SUM(valor) valor,valorconfig
      FROM (SELECT 0 valor, usuario_configuracion.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_configuracion
                    INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOSEMANA' AND
                                                clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "'
              AND usuario_configuracion.estado = 'A' 
            UNION 
              SELECT SUM(usuario_configuracion_resumen.valor) valor, usuario_configuracion.usuario_id, 0 valorconfig
            FROM usuario_configuracion_resumen
                     INNER JOIN usuario_configuracion
                                ON (usuario_configuracion.usuconfig_id =
                                    usuario_configuracion_resumen.usuconfig_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOSEMANA' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "'
              AND usuario_configuracion.estado = 'A'
              AND usuario_configuracion_resumen.fecha_crea >= '$fechaModifSemana'

            UNION

            SELECT SUM(CASE
               WHEN transjuego_log.tipo LIKE ('DEBIT%') THEN transjuego_log.valor
               WHEN transjuego_log.tipo LIKE ('ROLLBACK%') THEN transjuego_log.valor
               ELSE 0 END)                                                               valor, usuario_mandante.usuario_mandante, usuario_configuracion.valor valorconfig
            FROM transjuego_log
                     INNER JOIN transaccion_juego ON (transaccion_juego.transjuego_id = transjuego_log.transjuego_id)
                     INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usuario_mandante.usumandante_id)
                     INNER JOIN producto_mandante ON (producto_mandante.prodmandante_id = transaccion_juego.producto_id)
                     INNER JOIN producto ON (producto.producto_id = producto_mandante.producto_id)
                     INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id)
                     INNER JOIN usuario_configuracion ON (usuario_mandante.usuario_mandante = usuario_configuracion.usuario_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOSEMANA' AND clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_mandante.usumandante_id = $usumandanteId AND proveedor.tipo ='CASINO'
              AND usuario_configuracion.estado = 'A' 
              AND transjuego_log.fecha_crea >= '$fechaModifSemana2'
                              
           ) data2
";

                }

                if ($fechaModifMensual != '' && $fechaModifMensual2 != '') {
                    $sql = "SELECT SUM(valor) valor,valorconfig
      FROM (SELECT 0 valor, usuario_configuracion.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_configuracion
                    INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOMENSUAL' AND
                                                clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "'
              AND usuario_configuracion.estado = 'A' 
            UNION 
              SELECT SUM(usuario_configuracion_resumen.valor) valor, usuario_configuracion.usuario_id, 0 valorconfig
            FROM usuario_configuracion_resumen
                     INNER JOIN usuario_configuracion
                                ON (usuario_configuracion.usuconfig_id =
                                    usuario_configuracion_resumen.usuconfig_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOMENSUAL' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "'
              AND usuario_configuracion.estado = 'A' 
              AND usuario_configuracion_resumen.fecha_crea >= '$fechaModifMensual'
                 

            UNION

            SELECT SUM(CASE
               WHEN transjuego_log.tipo LIKE ('DEBIT%') THEN transjuego_log.valor
               WHEN transjuego_log.tipo LIKE ('ROLLBACK%') THEN transjuego_log.valor
               ELSE 0 END)                                                               valor, usuario_mandante.usuario_mandante, usuario_configuracion.valor valorconfig
            FROM transjuego_log
                     INNER JOIN transaccion_juego ON (transaccion_juego.transjuego_id = transjuego_log.transjuego_id)
                     INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usuario_mandante.usumandante_id)
                     INNER JOIN producto_mandante ON (producto_mandante.prodmandante_id = transaccion_juego.producto_id)
                     INNER JOIN producto ON (producto.producto_id = producto_mandante.producto_id)
                     INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id)
                     INNER JOIN usuario_configuracion ON (usuario_mandante.usuario_mandante = usuario_configuracion.usuario_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOMENSUAL' AND clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_mandante.usumandante_id = $usumandanteId AND proveedor.tipo ='CASINO'
              AND usuario_configuracion.estado = 'A' 
              AND transjuego_log.fecha_crea >= '$fechaModifMensual2'
                              
           ) data2
";

                }

                if ($fechaModifAnual != '' && $fechaModifAnual2 != '') {


                    $sql = "SELECT SUM(valor) valor,valorconfig
      FROM (SELECT 0 valor, usuario_configuracion.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_configuracion
                    INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOANUAL' AND
                                                clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "'
              AND usuario_configuracion.estado = 'A' 
            UNION 
              SELECT SUM(usuario_configuracion_resumen.valor) valor, usuario_configuracion.usuario_id, 0 valorconfig
            FROM usuario_configuracion_resumen
                     INNER JOIN usuario_configuracion
                                ON (usuario_configuracion.usuconfig_id =
                                    usuario_configuracion_resumen.usuconfig_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOANUAL' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "'
              AND usuario_configuracion.estado = 'A'
              AND usuario_configuracion_resumen.fecha_crea >= '$fechaModifAnual'

            UNION

            SELECT SUM(CASE
               WHEN transjuego_log.tipo LIKE ('DEBIT%') THEN transjuego_log.valor
               WHEN transjuego_log.tipo LIKE ('ROLLBACK%') THEN transjuego_log.valor
               ELSE 0 END)                                                               valor, usuario_mandante.usuario_mandante, usuario_configuracion.valor valorconfig
            FROM transjuego_log
                     INNER JOIN transaccion_juego ON (transaccion_juego.transjuego_id = transjuego_log.transjuego_id)
                     INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usuario_mandante.usumandante_id)
                     INNER JOIN producto_mandante ON (producto_mandante.prodmandante_id = transaccion_juego.producto_id)
                     INNER JOIN producto ON (producto.producto_id = producto_mandante.producto_id)
                     INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id)
                     INNER JOIN usuario_configuracion ON (usuario_mandante.usuario_mandante = usuario_configuracion.usuario_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOANUAL' AND clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_mandante.usumandante_id = $usumandanteId  AND proveedor.tipo ='CASINO'
              AND usuario_configuracion.estado = 'A' 
              AND transjuego_log.fecha_crea >= '$fechaModifAnual2'
                              
           ) data2
";


                }
                $data2 = $BonoInterno->execQuery($transaccion, $sql);

                if ($data2 != '' && $data2 != null) {
                    $cachedKey = 'AUTOEXCLUSION' . '+' . $fecha['clasificador.abreviado'] . '+' . $usumandanteId;

                    try {
                        $redis->set($cachedKey, json_encode($data2), $redisParam);

                    } catch (Exception $e) {
                    }
                }

            }





        }


    }
}

