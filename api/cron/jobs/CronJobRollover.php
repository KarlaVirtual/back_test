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
use Backend\mysql\BonoInternoMySqlDAO;
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
use Backend\sql\ConnectionProperty;


/**
 * Clase 'CronJobRollover'
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
class CronJobRollover
{

    private $SlackVS;
    private $BackgroundProcessVS;

    public function __construct()
    {
        $this->SlackVS = new SlackVS('cron-rollover');
        $this->BackgroundProcessVS = new BackgroundProcessVS();

    }

    public function execute()
    {
        /* asigna valores a variables según la conexión y parámetros existentes. */
        $_ENV["enabledConnectionGlobal"] = 1;

        $redis = RedisConnectionTrait::getRedisInstance(true);
        $comandos = array();

        $datetie = date('s');


        $_ENV["NEEDINSOLATIONLEVEL"] = '1';
        $_ENV["debug"] = true;

        $filename = __DIR__ . '/lastrunCronJobRollover';

        $ConfigurationEnvironment = new ConfigurationEnvironment();

        $BonoInterno = new BonoInterno();

        $sqlProcesoInterno2 = "SELECT * FROM proceso_interno2 WHERE tipo='ROLLOVERGENERAL'";

        $data = $BonoInterno->execQuery('', $sqlProcesoInterno2);
        $data = $data[0];
        $line = $data->{'proceso_interno2.fecha_ultima'};


        if ($line == '') {
            return;
        }
        $fechaL1 = date('Y-m-d H:i:s', strtotime($line . '+120 seconds'));
        $fechaL2 = date('Y-m-d H:i:s', strtotime($line . '+240 seconds'));


        $filename .= str_replace(' ', '-', str_replace(':', '-', $fechaL1));

        if ($fechaL2 >= date('Y-m-d H:i:00', strtotime('-1 minute'))) {
            return;
        }


        if (file_exists($filename)) {
            print_r('FILEEXITS');

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

        $sqlProcesoInterno2 = "UPDATE proceso_interno2 SET fecha_ultima='" . $fechaL1 . "' WHERE  tipo='ROLLOVERGENERAL';";


        $data = $BonoInterno->execQuery($transaccion, $sqlProcesoInterno2);
        $transaccion->commit();


        if (!$ConfigurationEnvironment->isDevelopment()) {

        }
        //$this->SlackVS->sendMessage("*CRON: (CronJobRollover) * " . " - Fecha: " . date("Y-m-d H:i:s"));

        $ActivacionOtros = true;
        $ActivacionSleepTime = false;

        $ConfigurationEnvironment   = new ConfigurationEnvironment();
        if ($ActivacionOtros) {
            $debug = false;

            $BonoInterno = new BonoInterno();


            if ($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null) {

                /* Conecta a la base de datos usando la configuración almacenada en el entorno. */
                $connOriginal = $_ENV["connectionGlobal"]->getConnection();


                try {


                    /* Conexión a la base de datos en producción utilizando PDO y SSL. */
                    $connDB5 = null;

                    if ($_ENV['ENV_TYPE'] == 'prod') {

                        $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()
                            , array(
                                PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-bundle.crt',
                                PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
                            )
                        );
                    } else {
                        /* Conecta a una base de datos MySQL usando PDO y variables de entorno. */


                        $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()

                        );
                    }


                    /* Configura la conexión a la base de datos con codificación UTF-8 y zona horaria. */
                    $connDB5->exec("set names utf8");


                    if ($_ENV["TIMEZONE"] != null && $_ENV["TIMEZONE"] != '') {
                        $connDB5->exec('SET time_zone = "' . $_ENV["TIMEZONE"] . '";');
                    }


                    /* Configura el nivel de aislamiento y tiempo de espera de bloqueo en una conexión a base de datos. */
                    if ($_ENV["NEEDINSOLATIONLEVEL"] != null && $_ENV["NEEDINSOLATIONLEVEL"] == '1') {
                        $connDB5->exec('SET TRANSACTION ISOLATION LEVEL READ COMMITTED;');
                    }
                    if ($_ENV["ENABLEDSETLOCKWAITTIMEOUT"] != null && $_ENV["ENABLEDSETLOCKWAITTIMEOUT"] == '1') {
                        // $connDB5->exec('SET SESSION innodb_lock_wait_timeout = 5;');
                    }

                    /* configura tiempo de ejecución y codificación UTF-8 en la base de datos. */
                    if ($_ENV["ENABLEDSETMAX_EXECUTION_TIME"] != null && $_ENV["ENABLEDSETMAX_EXECUTION_TIME"] == '1') {
                        // $connDB5->exec('SET SESSION MAX_EXECUTION_TIME = 120000;');
                    }
                    if ($_ENV["DBNEEDUTF8"] != null && $_ENV["DBNEEDUTF8"] == '1') {
                        $connDB5->exec("SET NAMES utf8mb4");
                    }

                    /* Establece una conexión a la base de datos utilizando una variable de entorno. */
                    $_ENV["connectionGlobal"]->setConnection($connDB5);

                } catch (\Exception $e) {
                    /* captura excepciones en PHP, evitando interrupciones en la ejecución. */


                }

            }

            $sqlApuestasDeportivasUsuarioDiaCierre = "

select DISTINCT (transjuego_log.transjuegolog_id) transjuegolog_id,producto.subproveedor_id,transaccion_juego.usuario_id,transjuego_log.valor,
       producto.producto_id,
       producto.proveedor_id,
       producto.subproveedor_id,
       producto_mandante.prodmandante_id,
       subproveedor.tipo,
       
       usuario_mandante.usumandante_id,
       usuario_mandante.usuario_mandante,
       usuario_mandante.mandante,
       usuario_mandante.moneda,
       usuario_mandante.pais_id
       
from transjuego_log
INNER JOIN transaccion_juego on transjuego_log.transjuego_id = transaccion_juego.transjuego_id
INNER JOIN producto_mandante on transaccion_juego.producto_id = producto_mandante.prodmandante_id
INNER JOIN producto on producto_mandante.producto_id = producto.producto_id
INNER JOIN subproveedor on subproveedor.subproveedor_id = producto.subproveedor_id

INNER JOIN usuario_mandante on usuario_mandante.usumandante_id = transaccion_juego.usuario_id



    WHERE transjuego_log.fecha_crea >= '" . $fechaL1 . "' AND transjuego_log.fecha_crea < '" . $fechaL2 . "' and usuario_mandante.mandante=19
and transjuego_log.tipo LIKE 'DEBIT%'
";
            print_r($sqlApuestasDeportivasUsuarioDiaCierre);



            $time = time();

            $data = $BonoInterno->execQuery('', $sqlApuestasDeportivasUsuarioDiaCierre);


            if ($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null) {
                $connDB5 = null;
                $_ENV["connectionGlobal"]->setConnection($connOriginal);
            }

            $cont = 0;
            foreach ($data as $datanum) {

                if ($datanum->{'usuario_mandante.usuario_mandante'} == '9002180') {
                    $this->SlackVS->sendMessage("*CRON: (CronJobRollover) * U" . $datanum->{'usuario_mandante.usuario_mandante'} . ' ' . $datanum->{'transjuego_log.transjuegolog_id'});
                }

                //$UsuarioMandante = new UsuarioMandante($datanum->{'transaccion_juego.usuario_id'});
                $amount = floatval($datanum->{'transjuego_log.valor'});

                $typeP = "CASINO";

                if ($datanum->{'subproveedor.tipo'} == 'CASINO') {
                    $typeP = "CASINO";
                } elseif ($datanum->{'subproveedor.tipo'} == 'LIVECASINO') {
                    $typeP = "LIVECASINO";
                } elseif ($datanum->{'subproveedor.tipo'} == 'VIRTUAL') {
                    $typeP = "VIRTUALES";
                }
                //$comandos[] = $this->BackgroundProcessVS->getCommandExecute(__DIR__ . "/../../src/integrations/casino/VerificarRollower.php", "CASINO  " . $datanum->{'usuario_mandante.usuario_mandante'} . " " . $datanum->{'transjuego_log.transjuegolog_id'});

                // Asignación de argumentos a variables
                //$arg1 = $argv[1]; // paisId
                //$arg2 = $argv[2]; // usuarioId
                //$arg3 = $argv[3]; // Valor apuesta
                //$arg4 = $argv[4]; // Tipo bono
                //$arg5 = $argv[5]; // ProductoId
                //$arg6 = $argv[6]; // Valor Ganancia
                //$arg7 = $argv[7]; // CategoriaId
                //$arg8 = $argv[8]; // SubProvedorId
                //$arg9 = $argv[9]; // TransjuegoLog_id

                $arrayCommands=array(
                        __DIR__ . "/../../src/integrations/casino/VerificarCashBack.php",
                        $datanum->{'usuario_mandante.pais_id'},
                        $datanum->{'usuario_mandante.usumandante_id'},
                        $amount,
                        4,
                    $datanum->{'producto_mandante.prodmandante_id'},
                    0,
                    0,
                    $datanum->{'producto.subproveedor_id'},
                    $datanum->{'transjuego_log.transjuegolog_id'}
                    );

                $logID = $this->createLog('VerificarCashBack', $datanum->{'usuario_mandante.usuario_mandante'}, $typeP, $datanum->{'transjuego_log.transjuegolog_id'}, '0', json_encode($arrayCommands), '', 'PREPROCESS');

                $cont++;

            }

            if (oldCount($comandos) > 0) {
                //exec(implode(' ', $comandos), $output);
                //$comandos=array();
            }
        }
        if ($ActivacionOtros) {
            $debug = false;

            $BonoInterno = new BonoInterno();


            if ($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null) {

                /* Conecta a la base de datos usando la configuración almacenada en el entorno. */
                $connOriginal = $_ENV["connectionGlobal"]->getConnection();


                try {


                    /* Conexión a la base de datos en producción utilizando PDO y SSL. */
                    $connDB5 = null;

                    if ($_ENV['ENV_TYPE'] == 'prod') {

                        $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()
                            , array(
                                PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-bundle.crt',
                                PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
                            )
                        );
                    } else {
                        /* Conecta a una base de datos MySQL usando PDO y variables de entorno. */


                        $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()

                        );
                    }


                    /* Configura la conexión a la base de datos con codificación UTF-8 y zona horaria. */
                    $connDB5->exec("set names utf8");


                    if ($_ENV["TIMEZONE"] != null && $_ENV["TIMEZONE"] != '') {
                        $connDB5->exec('SET time_zone = "' . $_ENV["TIMEZONE"] . '";');
                    }


                    /* Configura el nivel de aislamiento y tiempo de espera de bloqueo en una conexión a base de datos. */
                    if ($_ENV["NEEDINSOLATIONLEVEL"] != null && $_ENV["NEEDINSOLATIONLEVEL"] == '1') {
                        $connDB5->exec('SET TRANSACTION ISOLATION LEVEL READ COMMITTED;');
                    }
                    if ($_ENV["ENABLEDSETLOCKWAITTIMEOUT"] != null && $_ENV["ENABLEDSETLOCKWAITTIMEOUT"] == '1') {
                        // $connDB5->exec('SET SESSION innodb_lock_wait_timeout = 5;');
                    }

                    /* configura tiempo de ejecución y codificación UTF-8 en la base de datos. */
                    if ($_ENV["ENABLEDSETMAX_EXECUTION_TIME"] != null && $_ENV["ENABLEDSETMAX_EXECUTION_TIME"] == '1') {
                        // $connDB5->exec('SET SESSION MAX_EXECUTION_TIME = 120000;');
                    }
                    if ($_ENV["DBNEEDUTF8"] != null && $_ENV["DBNEEDUTF8"] == '1') {
                        $connDB5->exec("SET NAMES utf8mb4");
                    }

                    /* Establece una conexión a la base de datos utilizando una variable de entorno. */
                    $_ENV["connectionGlobal"]->setConnection($connDB5);

                } catch (\Exception $e) {
                    /* captura excepciones en PHP, evitando interrupciones en la ejecución. */


                }

            }

            $sqlApuestasDeportivasUsuarioDiaCierre = "

select DISTINCT (transjuego_log.transjuegolog_id) transjuegolog_id,producto.subproveedor_id,transaccion_juego.usuario_id,transjuego_log.valor,
       producto.producto_id,
       producto.proveedor_id,
       producto.subproveedor_id,
       producto_mandante.prodmandante_id,
       subproveedor.tipo,
       
       usuario_mandante.usumandante_id,
       usuario_mandante.usuario_mandante,
       usuario_mandante.mandante,
       usuario_mandante.moneda,
       usuario_mandante.pais_id
       
from transjuego_log
INNER JOIN transaccion_juego on transjuego_log.transjuego_id = transaccion_juego.transjuego_id
INNER JOIN producto_mandante on transaccion_juego.producto_id = producto_mandante.prodmandante_id
INNER JOIN producto on producto_mandante.producto_id = producto.producto_id
INNER JOIN subproveedor on subproveedor.subproveedor_id = producto.subproveedor_id

INNER JOIN usuario_mandante on usuario_mandante.usumandante_id = transaccion_juego.usuario_id

         INNER JOIN
     (select usuario_bono.usuario_id, bono_interno.bono_id, bd1.tipo tipo1, bd2.tipo tipo2, bd3.tipo tipo3
      from usuario_bono
               INNER JOIN bono_interno
                          ON (bono_interno.bono_id = usuario_bono.bono_id)
                INNER JOIN bono_detalle
                    ON (bono_interno.bono_id = bono_detalle.bono_id and bono_detalle.tipo = 'TIPOPRODUCTO' AND
                        bono_detalle.valor != 2)
               LEFT OUTER JOIN bono_detalle bd1
                               ON (bono_interno.bono_id = bd1.bono_id and bd1.tipo LIKE 'CONDGAME%')
               LEFT OUTER JOIN bono_detalle bd2
                               ON (bono_interno.bono_id = bd2.bono_id and bd2.tipo LIKE 'CONDSUBPROVIDER%')
               LEFT OUTER JOIN bono_detalle bd3
                               ON (bono_interno.bono_id = bd3.bono_id and bd3.tipo LIKE 'CONDPROVIDER%')
      where bono_interno.tipo in (2, 3)
         and usuario_bono.rollower_requerido > 0 and usuario_bono.fecha_crea <= '" . $fechaL2 . "'
        and usuario_bono.estado = 'A') x on (
         x.usuario_id = usuario_mandante.usuario_mandante
             AND ((tipo1 = CONCAT('CONDGAME', producto_mandante.prodmandante_id) or
                   tipo2 = CONCAT('CONDSUBPROVIDER', producto.subproveedor_id) or
                   tipo3 = CONCAT('CONDPROVIDER', producto.proveedor_id)) or
                  (tipo1 is null and tipo2 is null and tipo3 is null))
         )


    WHERE transjuego_log.fecha_crea >= '" . $fechaL1 . "' AND transjuego_log.fecha_crea < '" . $fechaL2 . "'
and transjuego_log.tipo LIKE 'DEBIT%'
";
            print_r($sqlApuestasDeportivasUsuarioDiaCierre);



            $time = time();

            $data = $BonoInterno->execQuery('', $sqlApuestasDeportivasUsuarioDiaCierre);


            if ($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null) {
                $connDB5 = null;
                $_ENV["connectionGlobal"]->setConnection($connOriginal);
            }

            $cont = 0;
            foreach ($data as $datanum) {

                if ($datanum->{'usuario_mandante.usuario_mandante'} == '9002180') {
                    $this->SlackVS->sendMessage("*CRON: (CronJobRollover) * U" . $datanum->{'usuario_mandante.usuario_mandante'} . ' ' . $datanum->{'transjuego_log.transjuegolog_id'});
                }

                //$UsuarioMandante = new UsuarioMandante($datanum->{'transaccion_juego.usuario_id'});
                $amount = floatval($datanum->{'transjuego_log.valor'});

                $typeP = "CASINO";

                if ($datanum->{'subproveedor.tipo'} == 'CASINO') {
                    $typeP = "CASINO";
                } elseif ($datanum->{'subproveedor.tipo'} == 'LIVECASINO') {
                    $typeP = "LIVECASINO";
                } elseif ($datanum->{'subproveedor.tipo'} == 'VIRTUAL') {
                    $typeP = "VIRTUALES";
                }
                //$comandos[] = $this->BackgroundProcessVS->getCommandExecute(__DIR__ . "/../../src/integrations/casino/VerificarRollower.php", "CASINO  " . $datanum->{'usuario_mandante.usuario_mandante'} . " " . $datanum->{'transjuego_log.transjuegolog_id'});

                $arrayCommands=array(
                    __DIR__ . "/../../src/integrations/casino/VerificarRollower.php",
                    "CASINO",
                    "",
                    $datanum->{'usuario_mandante.usuario_mandante'},
                    $datanum->{'transjuego_log.transjuegolog_id'}
                );
                $logID = $this->createLog('VerificarRollower', $datanum->{'usuario_mandante.usuario_mandante'}, 'CASINO', $datanum->{'transjuego_log.transjuegolog_id'}, '0', json_encode($arrayCommands), '', 'PREPROCESS');

                $cont++;

            }

            if (oldCount($comandos) > 0) {
                //exec(implode(' ', $comandos), $output);
                //$comandos=array();
            }
        }
        if ($ActivacionOtros) {
            $debug = false;

            $BonoInterno = new BonoInterno();

            $sqlApuestasDeportivasUsuarioDiaCierre = "

select DISTINCT(transjuego_log.transjuegolog_id) transjuegolog_id,producto.subproveedor_id,transaccion_juego.usuario_id,transjuego_log.valor,
       producto.producto_id,
       producto.proveedor_id,
       producto.subproveedor_id,
       producto_mandante.prodmandante_id,
       subproveedor.tipo,
       
       usuario_mandante.usumandante_id,
       usuario_mandante.usuario_mandante,
       usuario_mandante.mandante,
       usuario_mandante.moneda,
       usuario_mandante.pais_id
       
from transjuego_log
INNER JOIN transaccion_juego on transjuego_log.transjuego_id = transaccion_juego.transjuego_id
INNER JOIN producto_mandante on transaccion_juego.producto_id = producto_mandante.prodmandante_id
INNER JOIN producto on producto_mandante.producto_id = producto.producto_id
INNER JOIN subproveedor on subproveedor.subproveedor_id = producto.subproveedor_id

INNER JOIN usuario_mandante on usuario_mandante.usumandante_id = transaccion_juego.usuario_id


         INNER JOIN
     (select jackpot_detalle.moneda,jackpot_detalle.valor pais_id,jackpot_interno.mandante
      from jackpot_interno
               INNER JOIN jackpot_detalle
                          ON (jackpot_detalle.jackpot_id = jackpot_interno.jackpot_id 
                                  AND jackpot_detalle.tipo='CONDPAISUSER')
      where 
      jackpot_interno.estado='A' AND jackpot_interno.fecha_inicio <= '" . date('Y-m-d H:i:s') . "'
          ) x on ( 
          x.moneda = usuario_mandante.moneda 
          AND x.pais_id = usuario_mandante.pais_id 
          AND x.mandante = usuario_mandante.mandante
         )


    WHERE transjuego_log.fecha_crea >= '" . $fechaL1 . "' AND transjuego_log.fecha_crea < '" . $fechaL2 . "'
and transjuego_log.tipo LIKE 'DEBIT%'
";



            $time = time();


            if ($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null) {

                /* Conecta a la base de datos usando la configuración almacenada en el entorno. */
                $connOriginal = $_ENV["connectionGlobal"]->getConnection();


                try {


                    /* Conexión a la base de datos en producción utilizando PDO y SSL. */
                    $connDB5 = null;

                    if ($_ENV['ENV_TYPE'] == 'prod') {

                        $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()
                            , array(
                                PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-bundle.crt',
                                PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
                            )
                        );
                    } else {
                        /* Conecta a una base de datos MySQL usando PDO y variables de entorno. */


                        $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()

                        );
                    }


                    /* Configura la conexión a la base de datos con codificación UTF-8 y zona horaria. */
                    $connDB5->exec("set names utf8");


                    if ($_ENV["TIMEZONE"] != null && $_ENV["TIMEZONE"] != '') {
                        $connDB5->exec('SET time_zone = "' . $_ENV["TIMEZONE"] . '";');
                    }


                    /* Configura el nivel de aislamiento y tiempo de espera de bloqueo en una conexión a base de datos. */
                    if ($_ENV["NEEDINSOLATIONLEVEL"] != null && $_ENV["NEEDINSOLATIONLEVEL"] == '1') {
                        $connDB5->exec('SET TRANSACTION ISOLATION LEVEL READ COMMITTED;');
                    }
                    if ($_ENV["ENABLEDSETLOCKWAITTIMEOUT"] != null && $_ENV["ENABLEDSETLOCKWAITTIMEOUT"] == '1') {
                        // $connDB5->exec('SET SESSION innodb_lock_wait_timeout = 5;');
                    }

                    /* configura tiempo de ejecución y codificación UTF-8 en la base de datos. */
                    if ($_ENV["ENABLEDSETMAX_EXECUTION_TIME"] != null && $_ENV["ENABLEDSETMAX_EXECUTION_TIME"] == '1') {
                        // $connDB5->exec('SET SESSION MAX_EXECUTION_TIME = 120000;');
                    }
                    if ($_ENV["DBNEEDUTF8"] != null && $_ENV["DBNEEDUTF8"] == '1') {
                        $connDB5->exec("SET NAMES utf8mb4");
                    }

                    /* Establece una conexión a la base de datos utilizando una variable de entorno. */
                    $_ENV["connectionGlobal"]->setConnection($connDB5);

                } catch (\Exception $e) {
                    /* captura excepciones en PHP, evitando interrupciones en la ejecución. */


                }

            }

            $data = $BonoInterno->execQuery('', $sqlApuestasDeportivasUsuarioDiaCierre);

            if ($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null) {
                $connDB5 = null;
                $_ENV["connectionGlobal"]->setConnection($connOriginal);
            }

            $cont = 0;
            foreach ($data as $datanum) {

                if ($datanum->{'usuario_mandante.usuario_mandante'} == '9002180') {
                    $this->SlackVS->sendMessage("*CRON: (CronJobRollover) * U" . $datanum->{'usuario_mandante.usuario_mandante'} . ' ' . $datanum->{'transjuego_log.transjuegolog_id'});
                }

                //$UsuarioMandante = new UsuarioMandante($datanum->{'transaccion_juego.usuario_id'});
                $amount = floatval($datanum->{'transjuego_log.valor'});

                $typeP = "CASINO";

                if ($datanum->{'subproveedor.tipo'} == 'CASINO') {
                    $typeP = "CASINO";
                } elseif ($datanum->{'subproveedor.tipo'} == 'LIVECASINO') {
                    $typeP = "LIVECASINO";
                } elseif ($datanum->{'subproveedor.tipo'} == 'VIRTUAL') {
                    $typeP = "VIRTUALES";
                }



                //$comandos[] = $this->BackgroundProcessVS->getCommandExecute(__DIR__ . "/../../src/integrations/casino/AgregarValorJackpot.php", $typeP . " " . $datanum->{'transjuego_log.transjuegolog_id'});

                $arrayCommands=array(
                    __DIR__ . "/../../src/integrations/casino/AgregarValorJackpot.php",
                    $typeP,
                    $datanum->{'transjuego_log.transjuegolog_id'}
                );

                $logID = $this->createLog('AgregarValorJackpot', $datanum->{'usuario_mandante.usuario_mandante'}, $typeP, $datanum->{'transjuego_log.transjuegolog_id'}, '0', json_encode($arrayCommands), '', 'PREPROCESS');

                $cont++;

            }
            $this->SlackVS->sendMessage("*CRON: (CronJobRollover) Cant Casino Jackpot: * " . $fechaL1 . ' - '. $fechaL2 . ' - ' . date('Y-m-d H:i:s') . ' - ' . $cont . ' ' . __DIR__);

            if (oldCount($comandos) > 0) {
                //exec(implode(' ', $comandos), $output);
                //$comandos=array();
            }
        }


        flush();
        ob_flush();

        if ($ActivacionOtros) {

            $BonoInterno = new BonoInterno();

            $sqlApuestasDeportivasUsuarioDiaCierre = "
        SELECT
        it_ticket_enc.ticket_id,it_ticket_enc.vlr_apuesta,it_ticket_enc.usuario_id
        FROM it_ticket_enc
            
    INNER JOIN
     (select usuario_bono.usuario_id
      from usuario_bono
               INNER JOIN bono_interno
                          ON (bono_interno.bono_id = usuario_bono.bono_id)
                INNER JOIN bono_detalle
                    ON (bono_interno.bono_id = bono_detalle.bono_id and bono_detalle.tipo = 'TIPOPRODUCTO' AND
                        bono_detalle.valor = 2)
      where bono_interno.tipo in (2, 3)
        and usuario_bono.estado = 'A' and usuario_bono.rollower_requerido > 0 and usuario_bono.fecha_crea <= '" . $fechaL2 . "'
        ) x on x.usuario_id = it_ticket_enc.usuario_id
            
        INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
        INNER JOIN registro ON (registro.usuario_id = it_ticket_enc.usuario_id)
        WHERE (it_ticket_enc.fecha_cierre_time) >= '" . $fechaL1 . "' AND (it_ticket_enc.fecha_cierre_time) < '" . $fechaL2 . "' AND  it_ticket_enc.eliminado='N' AND it_ticket_enc.bet_status NOT IN ('T')";
            $time = time();
            print_r($sqlApuestasDeportivasUsuarioDiaCierre);


            if ($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null) {

                /* Conecta a la base de datos usando la configuración almacenada en el entorno. */
                $connOriginal = $_ENV["connectionGlobal"]->getConnection();


                try {


                    /* Conexión a la base de datos en producción utilizando PDO y SSL. */
                    $connDB5 = null;

                    if ($_ENV['ENV_TYPE'] == 'prod') {

                        $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()
                            , array(
                                PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-bundle.crt',
                                PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
                            )
                        );
                    } else {
                        /* Conecta a una base de datos MySQL usando PDO y variables de entorno. */


                        $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()

                        );
                    }


                    /* Configura la conexión a la base de datos con codificación UTF-8 y zona horaria. */
                    $connDB5->exec("set names utf8");


                    if ($_ENV["TIMEZONE"] != null && $_ENV["TIMEZONE"] != '') {
                        $connDB5->exec('SET time_zone = "' . $_ENV["TIMEZONE"] . '";');
                    }


                    /* Configura el nivel de aislamiento y tiempo de espera de bloqueo en una conexión a base de datos. */
                    if ($_ENV["NEEDINSOLATIONLEVEL"] != null && $_ENV["NEEDINSOLATIONLEVEL"] == '1') {
                        $connDB5->exec('SET TRANSACTION ISOLATION LEVEL READ COMMITTED;');
                    }
                    if ($_ENV["ENABLEDSETLOCKWAITTIMEOUT"] != null && $_ENV["ENABLEDSETLOCKWAITTIMEOUT"] == '1') {
                        // $connDB5->exec('SET SESSION innodb_lock_wait_timeout = 5;');
                    }

                    /* configura tiempo de ejecución y codificación UTF-8 en la base de datos. */
                    if ($_ENV["ENABLEDSETMAX_EXECUTION_TIME"] != null && $_ENV["ENABLEDSETMAX_EXECUTION_TIME"] == '1') {
                        // $connDB5->exec('SET SESSION MAX_EXECUTION_TIME = 120000;');
                    }
                    if ($_ENV["DBNEEDUTF8"] != null && $_ENV["DBNEEDUTF8"] == '1') {
                        $connDB5->exec("SET NAMES utf8mb4");
                    }

                    /* Establece una conexión a la base de datos utilizando una variable de entorno. */
                    $_ENV["connectionGlobal"]->setConnection($connDB5);

                } catch (\Exception $e) {
                    /* captura excepciones en PHP, evitando interrupciones en la ejecución. */


                }

            }

            $data = $BonoInterno->execQuery('', $sqlApuestasDeportivasUsuarioDiaCierre);


            if ($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null) {
                $connDB5 = null;
                $_ENV["connectionGlobal"]->setConnection($connOriginal);
            }



            $cont = 0;
            foreach ($data as $datanum) {

                if ($ActivacionSleepTime) {
                    usleep(5 * 1000);
                }

                //$this->SlackVS->sendMessage("*CRON: (CronJobRollover) * " . $datanum->{'it_ticket_enc.ticket_id'}.' '.__DIR__);
                //$UsuarioMandante = new UsuarioMandante($datanum->{'transaccion_juego.usuario_id'});
                $amount = floatval($datanum->{'it_ticket_enc.vlr_apuesta'});

                $typeP = "SPORT";


                //$comandos[] = $this->BackgroundProcessVS->getCommandExecute(__DIR__ . "/../../src/integrations/casino/VerificarRollower.php", "SPORT " . $datanum->{'it_ticket_enc.ticket_id'} . " " . $datanum->{'it_ticket_enc.usuario_id'});

                $arrayCommands=array(
                    __DIR__ . "/../../src/integrations/casino/VerificarRollower.php",
                    "SPORT",
                    $datanum->{'it_ticket_enc.ticket_id'},
                    $datanum->{'it_ticket_enc.usuario_id'}
                );

                $logID = $this->createLog('VerificarRollower', $datanum->{'it_ticket_enc.usuario_id'}, "SPORT", $datanum->{'it_ticket_enc.ticket_id'}, '0', json_encode($arrayCommands), '', 'PREPROCESS');



                $cont++;

            }
            $this->SlackVS->sendMessage("*CRON: (CronJobRollover) Cant Casino: * " . $fechaL1 . ' - '. $fechaL2 . ' - ' . date('Y-m-d H:i:s') . ' - ' . $cont . ' ' . __DIR__);

            if (oldCount($comandos) > 0) {
                //exec(implode(' ', $comandos), $output);
                //$comandos=array();
            }
            print_r('OK1');
            print_r(PHP_EOL);
        }

        if ($ActivacionOtros) {

            $BonoInterno = new BonoInterno();

            $sqlApuestasDeportivasUsuarioDiaCierre = "
        SELECT
        it_ticket_enc.ticket_id,it_ticket_enc.vlr_apuesta,usuario_mandante.usumandante_id,usuario_perfil.perfil_id, usuario.pais_id
        FROM it_ticket_enc
        INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
        INNER JOIN usuario_perfil ON (usuario.usuario_id = usuario_perfil.usuario_id)
        INNER JOIN usuario_mandante ON (usuario_mandante.usuario_mandante = usuario.usuario_id)
        INNER JOIN registro ON (registro.usuario_id = it_ticket_enc.usuario_id)
        WHERE usuario.mandante in (8 , 0 ,23,19,18) AND (it_ticket_enc.fecha_crea_time) >= '" . $fechaL1 . "' AND (it_ticket_enc.fecha_crea_time) < '" . $fechaL2 . "' AND  it_ticket_enc.eliminado='N' AND it_ticket_enc.bet_status NOT IN ('T')";
            $time = time();
            print_r($sqlApuestasDeportivasUsuarioDiaCierre);



            $data = $BonoInterno->execQuery('', $sqlApuestasDeportivasUsuarioDiaCierre);




            print_r(oldCount($data));

            $cont = 0;
            //$comandos=array();

            foreach ($data as $datanum) {

                if ($ActivacionSleepTime) {
                    usleep(5 * 1000);
                }


                //$this->SlackVS->sendMessage("*CRON: (CronJobRollover) * " . $datanum->{'it_ticket_enc.ticket_id'}.' '.__DIR__);
                //$UsuarioMandante = new UsuarioMandante($datanum->{'transaccion_juego.usuario_id'});
                $amount = floatval($datanum->{'it_ticket_enc.vlr_apuesta'});

                if ($datanum->{'usuario_perfil.perfil_id'} == "USUONLINE") {
                    //$comandos[] = $this->BackgroundProcessVS->getCommandExecute(__DIR__ . "/../../src/integrations/casino/AgregarValorJackpot.php ", 'SPORTBOOK' . " " . $datanum->{'it_ticket_enc.ticket_id'});
                   // $comandos[] = $this->BackgroundProcessVS->getCommandExecute(__DIR__ . "/../../src/integrations/sportsbook/ActivacionRuletaSportBook.php " . $datanum->{'usuario.pais_id'} . " " . $datanum->{'usuario_mandante.usumandante_id'} . " " . $amount . " " . 1 . " " . $datanum->{'it_ticket_enc.ticket_id'});


                    $arrayCommands=array(
                        __DIR__ . "/../../src/integrations/casino/AgregarValorJackpot.php",
                        "SPORTBOOK",
                        $datanum->{'it_ticket_enc.ticket_id'},
                        $datanum->{'it_ticket_enc.usuario_id'}
                    );
                    $logID = $this->createLog('AgregarValorJackpot', $datanum->{'it_ticket_enc.usuario_id'}, "SPORTBOOK", $datanum->{'it_ticket_enc.ticket_id'}, '0', json_encode($arrayCommands), '', 'PREPROCESS');

                    $arrayCommands=array(
                        __DIR__ . "/../../src/integrations/casino/ActivacionRuletaSportBook.php",
                        $datanum->{'usuario.pais_id'},
                        $datanum->{'usuario_mandante.usumandante_id'},
                        $amount,
                        1,
                        $datanum->{'it_ticket_enc.ticket_id'}
                    );
                    $logID = $this->createLog('ActivacionRuletaSportBook', $datanum->{'it_ticket_enc.usuario_id'}, "SPORTBOOK", $datanum->{'it_ticket_enc.ticket_id'}, '0', json_encode($arrayCommands), '', 'PREPROCESS');

                    /*$arrayCommands=array(
                        __DIR__ . "/../../src/integrations/casino/VerificarCashBack.php",
                        $datanum->{'usuario.pais_id'},
                        $datanum->{'usuario_mandante.usumandante_id'},
                        $amount,
                        4,
                        0,
                        0,
                        0,
                        0
                    );

                    $logID = $this->createLog('AgregarValorJackpot', $datanum->{'it_ticket_enc.usuario_id'}, "SPORTBOOK", $datanum->{'it_ticket_enc.ticket_id'}, '0', json_encode($arrayCommands), '', 'PREPROCESS');*/


                }


                $cont++;

            }
            $this->SlackVS->sendMessage("*CRON: (CronJobRollover) Cant Sports - Jackpot: * " . $fechaL1 . ' - '. $fechaL2 . ' - ' . date('Y-m-d H:i:s') . ' - ' . $cont . ' ' . __DIR__);

            if (oldCount($comandos) > 0) {
                //exec(implode(' ', $comandos), $output);
                //$comandos=array();
            }
        }



        flush();
        ob_flush();

        $this->SlackVS->sendMessage("*CRON: (CronJobRollover) Cant Sports: * " . $fechaL1 . ' - ' . $fechaL2 . ' - ' . date('Y-m-d H:i:s') . ' - ' . $cont . ' ' . __DIR__);



        unlink($filename);


    }

    function createLog($tipo, $usuario_id, $valor_id1, $valor_id2, $valor_id3, $valor1, $valor2, $estado)
    {
        $BonoInterno = new BonoInterno();
        $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();
        $transaction = $BonoInternoMySqlDAO->getTransaction();

        if (strpos($valor_id1, '==') !== false) {
            $valor_id1 = base64_decode($valor_id2);
        }

        if (strpos($valor_id2, '==') !== false) {
            $valor_id2 = base64_decode($valor_id2);
        }

        $sql = "
INSERT INTO casino.log_cron (tipo, usuario_id, valor_id1, valor_id2, valor_id3, valor1, valor2, fecha_crea, fecha_modif,
                             estado)
VALUES ('$tipo', '" . ($usuario_id != '' ? $usuario_id : '0') . "', '$valor_id1', '$valor_id2', '$valor_id3', '$valor1', '$valor2', DEFAULT, DEFAULT, '$estado');

";
        $resultsql = $BonoInterno->execUpdate($transaction, $sql, 1);
        $transaction->commit();
        return $resultsql;
    }

    function updateLog($logcron_id, $estado, $valor1 = '', $valor2 = '')
    {

        $BonoInterno = new BonoInterno();
        $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();
        $transaction = $BonoInternoMySqlDAO->getTransaction();

        $sql = "
            UPDATE log_cron SET estado='$estado'
    ";
        if ($valor1 != '') {
            $sql .= ",valor1='" . str_replace("'", '"', $valor1) . "' ";
        }
        if ($valor2 != '') {
            $sql .= ",valor2='" . str_replace("'", '"', $valor2) . "' ";

        }
        $sql .= " WHERE logcron_id=$logcron_id; ";

        $resultsql = $BonoInterno->execUpdate($transaction, $sql, 1);
        $transaction->commit();
        return $resultsql;
    }

}

