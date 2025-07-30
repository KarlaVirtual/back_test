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
class CronJobActualizacionCredencialProveedores
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

            /*Generación filtros de consulta*/
            $rules = [];


            /* Se filtran y obtienen datos de subproveedores con ciertas condiciones y orden. */
            $filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

            $SubproveedorMandantePais = new SubproveedorMandantePais();

            $query = $SubproveedorMandantePais->getSubproveedoresMandantePaisCustomCredentials('proveedor.*, subproveedor.*, subproveedor_mandante_pais.*,  mandante.nombre as mandante_nombre, pais.pais_nom', 'subproveedor_mandante_pais.orden', 'asc', 0, 100000, $filter, true);

            $query = json_decode($query);

            /* procesa datos de proveedores y almacena información específica en un array. */
            $providers = [];

            foreach ($query->data as $key => $value) {
                $token = '';
                switch ($value->{'proveedor.abreviado'}) {
                    case 'EVOLUTIONOSS':
                        $credentials = $value->{'subproveedor_mandante_pais.credentials'};
                        if ($credentials != '') {
                            $credentials = json_decode($credentials);
                            $token = $credentials->TOKEN_AUTH;
                        }
                        break;
                }
                if ($token != '') {

                    $cachedKey = 'TOKEN_AUTH' . '+' . $value->{'proveedor.abreviado'} . '+' . $token;
                    print_r($cachedKey);

                    try {
                        $redis->set($cachedKey, 1, $redisParam);

                    } catch (Exception $e) {
                    }
                }
            }


        }


    }
}

