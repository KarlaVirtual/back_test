<?php
use Backend\cron\ResumenesCron;
use Backend\dto\BonoInterno;
use Backend\dto\BonoLog;
use Backend\dto\Categoria;
use Backend\dto\CategoriaProducto;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\ItTicketEnc;
use Backend\dto\Proveedor;
use Backend\dto\SorteoDetalle;
use Backend\dto\SorteoInterno;
use Backend\dto\Subproveedor;
use Backend\dto\TransaccionApi;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\UsuarioBono;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioMandante;
use Backend\dto\Usuario;
use Backend\dto\UsuarioMensaje;
use Backend\dto\UsuarioSession;
use Backend\dto\UsuarioSorteo;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\ProductoMandante;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\BonoLogMySqlDAO;
use Backend\mysql\SorteoDetalleMySqlDAO;
use Backend\mysql\SorteoInternoMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransjuegoLogMySqlDAO;
use Backend\mysql\UsuarioBonoMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\UsuarioSorteoMySqlDAO;
use Backend\utils\RedisConnectionTrait;
use Backend\utils\SlackVS;
use Backend\utils\BackgroundProcessVS;
use Backend\websocket\WebsocketUsuario;

/**
 * Resúmen cronométrico
 *
 * Esta clase provee la ejecución segura de un conjunto de comandos ejecutados en tiempo real (cron),
 * usado para monitorear las apuestas tanto deportivas como casino para desencadenar las acciones
 * referentes a una ruleta (asignaciones, activaciones ...)
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version v1.0.0
 * @access public
 * @see no
 * @date 18.10.17
 *
 */
class CronJobRuleta
{

    private $SlackVS;
    private $BackgroundProcessVS;

    /**
     * Constructor de la clase CronJobRuleta
     *
     * Instancia la clase CronJobRuleta para su correcta ejecución dentro del Core del sistema
     *
     */
    public function __construct()
    {
        $this->SlackVS = new SlackVS('cron-rollover');
        $this->BackgroundProcessVS = new BackgroundProcessVS();

    }

    /**
     * Función execute.
     *
     * Ejecuta el cron analizando las diferentes apuestas de los usuarios (deportivas o casino)
     * en las diferentes plataformas para entonces desencadenar las acciones de las ruletas
     *
     */
    public function execute()
    {
        $redis = RedisConnectionTrait::getRedisInstance(true);
        $comandos = array();

        $datetie = date('s');

        print_r('ENTRO1');

        $_ENV["NEEDINSOLATIONLEVEL"] = '1';
        $_ENV["debug"] = true;

        //Obtiene la ultima vez que se ejecutó el cron y configura fecha de finalización de ejecución para proxima ejecución
        $filename = __DIR__ . '/lastrunCronJobRuleta';

        $ConfigurationEnvironment = new ConfigurationEnvironment();

        $BonoInterno = new BonoInterno();

        $sqlProcesoInterno2 = "SELECT * FROM proceso_interno2 WHERE tipo='RULETAGENERAL'";

        $data = $BonoInterno->execQuery('', $sqlProcesoInterno2);
        $data = $data[0];
        $line = $data->{'proceso_interno2.fecha_ultima'};


        if ($line == '') {
            return;
        }
        $fechaL1 = date('Y-m-d H:i:s', strtotime($line . '+0 seconds'));
        $fechaL2 = date('Y-m-d H:i:s', strtotime($line . '+60 seconds'));


        $filename .= str_replace(' ', '-', str_replace(':', '-', $fechaL1));

        if ($fechaL1 >= date('Y-m-d H:i:s', strtotime('-1 minute'))) {
            return;
        }


        if (file_exists($filename)) {

            $datefilename = date("Y-m-d H:i:s", filemtime($filename));

            if ($datefilename <= date("Y-m-d H:i:s", strtotime('-10 minute'))) {
                unlink($filename);
            }

            return;
        }
        file_put_contents($filename, 'RUN');

        $BonoInterno = new BonoInterno();
        $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
        $transaccion = $BonoDetalleMySqlDAO->getTransaction();

        $sqlProcesoInterno2 = "UPDATE proceso_interno2 SET fecha_ultima='" . $fechaL2 . "' WHERE  tipo='RULETAGENERAL';";


        $data = $BonoInterno->execQuery($transaccion, $sqlProcesoInterno2);
        $transaccion->commit();


        if (!$ConfigurationEnvironment->isDevelopment()) {

        }
        //$this->SlackVS->sendMessage("*CRON: (CronJobRuleta) * " . " - Fecha: " . date("Y-m-d H:i:s"));

        $ActivacionOtros = true;
        $ActivacionSleepTime = false;


        if ($ActivacionOtros) {
            $debug = false;
            /** Comienza ejecución del cron para apuestas de Casino */
            $BonoInterno = new BonoInterno();

            //Obtiene las apuestas de Casino dependientes de la fecha de ejecución del cron
            $sqlApuestasDeportivasUsuarioDiaCierre = "

select transjuego_log.transjuegolog_id,
       producto.subproveedor_id,
       transaccion_juego.usuario_id,
       transjuego_log.valor,
       producto.producto_id,
       producto.proveedor_id,
       producto_mandante.prodmandante_id,
       subproveedor.tipo,
       usuario_mandante.usumandante_id,
       usuario_mandante.usuario_mandante,
       usuario_mandante.mandante,
       usuario_mandante.moneda,
       usuario_mandante.pais_id,
       y.categorias,
       z.categoria_id

from transjuego_log
         INNER JOIN transaccion_juego on transjuego_log.transjuego_id = transaccion_juego.transjuego_id
         INNER JOIN producto_mandante on transaccion_juego.producto_id = producto_mandante.prodmandante_id
         INNER JOIN producto on producto_mandante.producto_id = producto.producto_id
         INNER JOIN subproveedor on subproveedor.subproveedor_id = producto.subproveedor_id

         INNER JOIN usuario_mandante on usuario_mandante.usumandante_id = transaccion_juego.usuario_id


         INNER JOIN
     (select ruleta_detalle.ruleta_id, ruleta_detalle.moneda, ruleta_detalle.valor pais_id, ruleta_interno.mandante
      from ruleta_interno
               INNER JOIN ruleta_detalle
                          ON (ruleta_detalle.ruleta_id = ruleta_interno.ruleta_id
                              AND ruleta_detalle.tipo='CONDPAISUSER')
      where ruleta_interno.estado='A' AND ruleta_interno.fecha_inicio <= '" . date('Y-m-d H:i:s') . "')x on (
         x.moneda = usuario_mandante.moneda
             AND x.pais_id = usuario_mandante.pais_id
             AND x.mandante = usuario_mandante.mandante
         )

         LEFT OUTER JOIN
     (select ruleta_detalle.ruleta_id,GROUP_CONCAT(ruleta_detalle.valor SEPARATOR ',') categorias
      from ruleta_interno
               INNER JOIN ruleta_detalle
                          ON (ruleta_detalle.ruleta_id = ruleta_interno.ruleta_id
                              AND ruleta_detalle.tipo = 'CONDCATEGORY')

group by ruleta_detalle.ruleta_id) y on (
         y.ruleta_id = x.ruleta_id
         )
         LEFT OUTER JOIN
     (select categoria_producto.producto_id,categoria_producto.categoria_id
      from categoria_producto
      ) z on (
         z.producto_id = producto.producto_id AND z.categoria_id IN (y.categorias)
         )


    WHERE transjuego_log.fecha_crea >= '" . $fechaL1 . "' AND transjuego_log.fecha_crea < '" . $fechaL2 . "'
and transjuego_log.tipo LIKE 'DEBIT%' AND transaccion_juego.tipo != 'FREECASH' AND transaccion_juego.tipo != 'FREESPIN' group by transjuegolog_id;
";
            print_r($sqlApuestasDeportivasUsuarioDiaCierre);
            $time = time();

            $data = $BonoInterno->execQuery('', $sqlApuestasDeportivasUsuarioDiaCierre);

            print_r(oldCount($data));
            $cont = 0;

            // Se recorren las apuestas encontradas para desencadenar acciones de la ruleta si se necesita
            foreach ($data as $datanum) {

                try {

                    $amount = floatval($datanum->{'transjuego_log.valor'});

                    $typeP = "2";

                    if ($datanum->{'subproveedor.tipo'} == 'CASINO') {
                        $typeP = "2";
                    } elseif ($datanum->{'subproveedor.tipo'} == 'LIVECASINO') {
                        $typeP = "3";
                    } elseif ($datanum->{'subproveedor.tipo'} == 'VIRTUAL') {
                        $typeP = "4";
                    }

                    //Se ejecuta en segundo plano el llamado al recurso de asignación de Ruleta
                    $comandos[] = $this->BackgroundProcessVS->getCommandExecute(__DIR__ . "/../../src/integrations/casino/ActivacionRuletaCasino.php " . $datanum->{'usuario_mandante.pais_id'} . " " . $datanum->{'usuario_mandante.usumandante_id'} . " " . $amount . " " . $typeP . " " . $datanum->{'z.categoria_id'} . " " . $datanum->{'producto.subproveedor_id'} . " " . $datanum->{'producto_mandante.prodmandante_id'});

                    if (($cont % 50) == 0) {
                        //exec(implode(' ', $comandos), $output);
                        //$comandos=array();
                    }

                    $cont++;

                } catch (Exception $e) {

                }


            }

            if (oldCount($comandos) > 0) {
                //exec(implode(' ', $comandos), $output);
                //$comandos=array();
            }
        }
        /** Finaliza ejecución del cron para apuestas de Casino */


        if ($ActivacionOtros) {

            /** Comienza ejecución del cron para apuestas de Deportivas */

            $BonoInterno = new BonoInterno();

            // Se obtienen las apuestas deportivas dentro del tiempo de ejecución de ejecución del cron
            $sqlApuestasDeportivasUsuarioDiaCierre = "
        SELECT
        it_ticket_enc.ticket_id,it_ticket_enc.vlr_apuesta,usuario_mandante.usumandante_id,usuario_perfil.perfil_id, usuario.pais_id
        FROM it_ticket_enc
        INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
        INNER JOIN usuario_perfil ON (usuario.usuario_id = usuario_perfil.usuario_id)
        INNER JOIN usuario_mandante ON (usuario_mandante.usuario_mandante = usuario.usuario_id)
        INNER JOIN registro ON (registro.usuario_id = it_ticket_enc.usuario_id)
        WHERE usuario.mandante in (8 , 0 ,23,19,18) AND (it_ticket_enc.fecha_crea_time) >= '" . $fechaL1 . "' AND (it_ticket_enc.fecha_crea_time) < '" . $fechaL2 . "' AND  it_ticket_enc.eliminado='N' AND it_ticket_enc.bet_status NOT IN ('T') and it_ticket_enc.freebet = '0'";
            $time = time();
            print_r($sqlApuestasDeportivasUsuarioDiaCierre);

            $data = $BonoInterno->execQuery('', $sqlApuestasDeportivasUsuarioDiaCierre);
            print_r(oldCount($data));

            $cont = 0;
            //$comandos=array();

            // Se recorren las apuestas obtenidas dentro de la ejecución del cron
            foreach ($data as $datanum) {

                if ($ActivacionSleepTime) {
                    usleep(5 * 1000);
                }


                //$this->SlackVS->sendMessage("*CRON: (CronJobRuleta) * " . $datanum->{'it_ticket_enc.ticket_id'}.' '.__DIR__);
                //$UsuarioMandante = new UsuarioMandante($datanum->{'transaccion_juego.usuario_id'});
                $amount = floatval($datanum->{'it_ticket_enc.vlr_apuesta'});

                //Se discrimina por usuario de la plataforma
                if ($datanum->{'usuario_perfil.perfil_id'} == "USUONLINE") {

                    //Se ejecuta en segundo plano el recurso de asignación de ruleta
                    $comandos[] = $this->BackgroundProcessVS->getCommandExecute(__DIR__ . "/../../src/integrations/sportsbook/ActivacionRuletaSportBook.php " . $datanum->{'usuario.pais_id'} . " " . $datanum->{'usuario_mandante.usumandante_id'} . " " . $amount . " " . 1 . " " . $datanum->{'it_ticket_enc.ticket_id'});


                    if (($cont % 50) == 0) {
                        //exec(implode(' ', $comandos), $output);
                        // $comandos = array();
                    }

                }


                $cont++;

            }

            if (oldCount($comandos) > 0) {
                //exec(implode(' ', $comandos), $output);
                //$comandos=array();
            }
        }
        /** Finaliza ejecución del cron para apuestas Deportivas */

        print_r('OK2');
        print_r(PHP_EOL);


        $redisPrefixPrefix = 'F10BACK'; // Valor por defecto
        $minute = date('i');

// Calcular el grupo basado en unidades de 10 minutos con el desplazamiento que especificaste
        if ($minute % 10 == 1) {
            $redisPrefixPrefix = 'F10BACK';
        } elseif ($minute % 10 == 2) {
            $redisPrefixPrefix = 'F11BACK';
        } elseif ($minute % 10 == 3) {
            $redisPrefixPrefix = 'F12BACK';
        } elseif ($minute % 10 == 4) {
            $redisPrefixPrefix = 'F13BACK';
        } elseif ($minute % 10 == 5) {
            $redisPrefixPrefix = 'F14BACK';
        } elseif ($minute % 10 == 6) {
            $redisPrefixPrefix = 'F15BACK';
        } elseif ($minute % 10 == 7) {
            $redisPrefixPrefix = 'F16BACK';
        } elseif ($minute % 10 == 8) {
            $redisPrefixPrefix = 'F17BACK';
        } elseif ($minute % 10 == 9) {
            $redisPrefixPrefix = 'F18BACK';
        } elseif ($minute % 10 == 0) {
            $redisPrefixPrefix = 'F19BACK';
        } elseif ($minute % 10 == 1) {
            $redisPrefixPrefix = 'F20BACK';
        }

        $redisParam = ['ex' => 18000];
        foreach ($comandos as $comando) {
            if ($redis != null) {
                $redisPrefix = $redisPrefixPrefix . "+UID" . $comando;

                $argv = explode($comando, ' ');

                $redis->set($redisPrefix, json_encode($argv), $redisParam);
            }

        }
        print_r('OK3');
        print_r(PHP_EOL);
        unlink($filename);


    }
}

