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
class CronJobActualizacionUsuariosConBono
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

        $redisParam = ['ex' => 300];

        if ($redis != null) {

            $BonoInterno = new BonoInterno();
            $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
            $transaccion = $BonoDetalleMySqlDAO->getTransaction();


            $sql = "
SELECT distinct (usuario_id) usuario_id
FROM usuario_bono
inner join bono_interno on usuario_bono.bono_id=bono_interno.bono_id
WHERE usuario_bono.estado = 'A'
  and bono_interno.tipo = '5'
";
            $data = $BonoInterno->execQuery($transaccion, $sql);


            foreach ($data as $datum) {
                $datum = json_decode(json_encode($datum), true);
                $Usuario = new Usuario($datum['usuario_bono.usuario_id']);
                $UsuarioMandante = new UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);
                $usumandanteId = $UsuarioMandante->usumandanteId;

                    $cachedKey = 'TIENEBONOFREECASH' . '+' . $usumandanteId;

                    try {
                        $redis->set($cachedKey, 1, $redisParam);

                    } catch (Exception $e) {
                    }

            }

            exit();

            $sql = "
SELECT clasificador.abreviado, usuario_configuracion.usuario_id
FROM usuario_configuracion
    INNER JOIN clasificador
ON (clasificador.clasificador_id = usuario_configuracion.tipo)
WHERE usuario_configuracion.estado = 'A'
  and clasificador.tipo='UC'
  AND clasificador.abreviado in ('EXCPRODUCT'
    , 'EXCTIMEOUT'
    , 'EXCCASINOCATEGORY'
    , 'EXCCASINOGAME'
    )

";
            $data = $BonoInterno->execQuery($transaccion, $sql);


            foreach ($data as $datum) {
                $datum = json_decode(json_encode($datum), true);
                $Usuario = new Usuario($datum['usuario_configuracion.usuario_id']);
                $UsuarioMandante = new UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);
                $usumandanteId = $UsuarioMandante->usumandanteId;

                $cachedKey = 'TIENE' . '+'.$datum['clasificador.abreviado'].'+' . $usumandanteId;

                try {
                    $redis->set($cachedKey, 1, $redisParam);

                } catch (Exception $e) {
                }

            }





        }


    }
}

