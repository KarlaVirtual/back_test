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

use Backend\utils\RedisConnectionTrait;

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
class CronJobReportingRedis
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
        $filename = __DIR__ . '/lastrunCronJobReportingRedis';
        $datefilename = date("Y-m-d H:i:s", filemtime($filename));
        if ($datefilename <= date("Y-m-d H:i:s", strtotime('-20 minutes'))) {
            unlink($filename);

        }
        if (file_exists($filename)) {
            exit();
        }
        file_put_contents($filename, 'RUN');

        $_ENV['DB_HOST'] = $_ENV['DB_HOST_BACKUP'];

        $sql = "
       
SELECT UPPER(mandante.descripcion)                                 name,
       COUNT(DISTINCT (usuario_log.usuario_id))              value,
       DATE_FORMAT(MAX(usuario_log.fecha_crea), '%Y-%m-%d %H:%i:%s') date
   FROM usuario_log
         inner join usuario on usuario.usuario_id = usuario_log.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
 WHERE   1=1           AND (usuario_log.tipo LIKE 'LOGIN%' AND usuario_log.tipo != 'LOGININCORRECTO')
  AND usuario_log.fecha_crea >= '" . date("Y-m-d") . " 00:00:00'
  AND usuario_log.fecha_crea <= '" . date("Y-m-d") . " 23:59:59'
group by mandante.mandante
order by value desc;
        ";


        $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();
        $Transaction = $BonoInternoMySqlDAO->getTransaction();

        $BonoInterno = new BonoInterno();
        $Resultado = $BonoInterno->execQuery($Transaction, $sql);
        $redisPrefix = 'CantTotalLoginUsersUniqueTotalTotal+'; // Valor por defecto

        $redis = RedisConnectionTrait::getRedisInstance(true);
        $redisParam = ['ex' => 18000];
        if ($redis != null) {
            $redis->set($redisPrefix, json_encode($Resultado), $redisParam);
        }


        $sql = "
       
SELECT CONCAT(UPPER(mandante.descripcion),' ',UPPER(pais.pais_nom))                                 name,
       COUNT(DISTINCT (usuario_log.usuario_id))              value,
       DATE_FORMAT(MAX(usuario_log.fecha_crea), '%Y-%m-%d %H:%i:%s') date
   FROM usuario_log
         inner join usuario on usuario.usuario_id = usuario_log.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
 WHERE   1=1           AND (usuario_log.tipo LIKE 'LOGIN%' AND usuario_log.tipo != 'LOGININCORRECTO')
  AND usuario_log.fecha_crea >= '" . date("Y-m-d") . " 00:00:00'
  AND usuario_log.fecha_crea <= '" . date("Y-m-d") . " 23:59:59'
group by mandante.mandante,pais.pais_id
order by value desc;
        ";


        $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();
        $Transaction = $BonoInternoMySqlDAO->getTransaction();

        $BonoInterno = new BonoInterno();
        $Resultado = $BonoInterno->execQuery($Transaction, $sql);
        $redisPrefix = 'CantTotalLoginUsersUniqueByCountryTotalTotal+'; // Valor por defecto

        $redis = RedisConnectionTrait::getRedisInstance(true);
        $redisParam = ['ex' => 18000];
        if ($redis != null) {
            $redis->set($redisPrefix, json_encode($Resultado), $redisParam);
        }


        $sql = "
       
SELECT UPPER(mandante.descripcion)                                 name,
       COUNT(DISTINCT (usuario_log.usuario_id))              value,
       DATE_FORMAT(MAX(usuario_log.fecha_crea), '%Y-%m-%d %H:%i:%s') date
   FROM usuario_log
         inner join usuario on usuario.usuario_id = usuario_log.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
 WHERE   1=1           AND (usuario_log.tipo = 'LOGININCORRECTO')
  AND usuario_log.fecha_crea >= '" . date("Y-m-d") . " 00:00:00'
  AND usuario_log.fecha_crea <= '" . date("Y-m-d") . " 23:59:59'
group by mandante.mandante
order by value desc;
        ";


        $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();
        $Transaction = $BonoInternoMySqlDAO->getTransaction();

        $BonoInterno = new BonoInterno();
        $Resultado = $BonoInterno->execQuery($Transaction, $sql);
        $redisPrefix = 'CantTotalLoginErrorUsersUniqueTotalTotal+'; // Valor por defecto

        $redis = RedisConnectionTrait::getRedisInstance(true);
        $redisParam = ['ex' => 18000];
        if ($redis != null) {
            $redis->set($redisPrefix, json_encode($Resultado), $redisParam);
        }


        $sql = "
       
SELECT CONCAT(UPPER(mandante.descripcion),' ',UPPER(pais.pais_nom))                                 name,
       COUNT(DISTINCT (usuario_log.usuario_id))              value,
       DATE_FORMAT(MAX(usuario_log.fecha_crea), '%Y-%m-%d %H:%i:%s') date
   FROM usuario_log
         inner join usuario on usuario.usuario_id = usuario_log.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
 WHERE   1=1           AND (usuario_log.tipo = 'LOGININCORRECTO')
  AND usuario_log.fecha_crea >= '" . date("Y-m-d") . " 00:00:00'
  AND usuario_log.fecha_crea <= '" . date("Y-m-d") . " 23:59:59'
group by mandante.mandante,pais.pais_id
order by value desc;
        ";


        $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();
        $Transaction = $BonoInternoMySqlDAO->getTransaction();

        $BonoInterno = new BonoInterno();
        $Resultado = $BonoInterno->execQuery($Transaction, $sql);
        $redisPrefix = 'CantTotalLoginErrorUsersUniqueByCountryTotalTotal+'; // Valor por defecto

        $redis = RedisConnectionTrait::getRedisInstance(true);
        $redisParam = ['ex' => 18000];
        if ($redis != null) {
            $redis->set($redisPrefix, json_encode($Resultado), $redisParam);
        }

        $Types = array('NORMAL', 'FREESPIN', 'FREECASH', '');
        foreach ($Types as $type) {
            $sql = "
       
SELECT UPPER(mandante.descripcion)                                 name,
       COUNT(DISTINCT (transaccion_juego.usuario_id))              value,
       DATE_FORMAT(MAX(transjuego_log.fecha_crea), '%Y-%m-%d %H:%i:%s') date
FROM transjuego_log
         inner join transaccion_juego on transjuego_log.transjuego_id = transaccion_juego.transjuego_id
         inner join usuario_mandante on usuario_mandante.usumandante_id = transaccion_juego.usuario_id
         inner join pais on usuario_mandante.pais_id = pais.pais_id
         inner join mandante on usuario_mandante.mandante = mandante.mandante
WHERE 1 = 1
  AND transjuego_log.tipo LIKE '%DEBIT%'
  " . (($type != '') ? "AND transaccion_juego.tipo = '{$type}'" : '') . "

  AND transjuego_log.fecha_crea >= '" . date("Y-m-d") . " 00:00:00'
  AND transjuego_log.fecha_crea <= '" . date("Y-m-d") . " 23:59:59'
group by mandante.mandante
order by value desc;
        ";


            $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();
            $Transaction = $BonoInternoMySqlDAO->getTransaction();

            $BonoInterno = new BonoInterno();
            $Resultado = $BonoInterno->execQuery($Transaction, $sql);
            $redisPrefix = 'CantTotalUserBetsCasinoTotalTotal+' . $type; // Valor por defecto

            $redis = RedisConnectionTrait::getRedisInstance(true);
            $redisParam = ['ex' => 18000];
            if ($redis != null) {
                $redis->set($redisPrefix, json_encode($Resultado), $redisParam);
            }
        }
        $Types = array('NORMAL', 'FREESPIN', 'FREECASH', '');
        foreach ($Types as $type) {
            $sql = "
       
SELECT CONCAT(UPPER(mandante.descripcion),' ',UPPER(pais.pais_nom))                                 name,
       COUNT(DISTINCT (transaccion_juego.usuario_id))              value,
       DATE_FORMAT(MAX(transjuego_log.fecha_crea), '%Y-%m-%d %H:%i:%s') date
FROM transjuego_log
         inner join transaccion_juego on transjuego_log.transjuego_id = transaccion_juego.transjuego_id
         inner join usuario_mandante on usuario_mandante.usumandante_id = transaccion_juego.usuario_id
         inner join pais on usuario_mandante.pais_id = pais.pais_id
         inner join mandante on usuario_mandante.mandante = mandante.mandante
WHERE 1 = 1
  AND transjuego_log.tipo LIKE '%DEBIT%'
  " . (($type != '') ? "AND transaccion_juego.tipo = '{$type}'" : '') . "

  AND transjuego_log.fecha_crea >= '" . date("Y-m-d") . " 00:00:00'
  AND transjuego_log.fecha_crea <= '" . date("Y-m-d") . " 23:59:59'
group by mandante.mandante,pais.pais_id
order by value desc;
        ";


            $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();
            $Transaction = $BonoInternoMySqlDAO->getTransaction();

            $BonoInterno = new BonoInterno();
            $Resultado = $BonoInterno->execQuery($Transaction, $sql);
            $redisPrefix = 'CantTotalUserBetsCasinoCountryTotalTotal+' . $type; // Valor por defecto

            $redis = RedisConnectionTrait::getRedisInstance(true);
            $redisParam = ['ex' => 18000];
            if ($redis != null) {
                $redis->set($redisPrefix, json_encode($Resultado), $redisParam);
            }
        }


        $Types = array('FREEBET', '');
        foreach ($Types as $type) {
            $sql = "
       
SELECT UPPER(mandante.descripcion)                                 name,
       COUNT(DISTINCT (it_ticket_enc.usuario_id))              value,
       DATE_FORMAT(MAX(it_ticket_enc.fecha_crea_time), '%Y-%m-%d %H:%i:%s') date
FROM it_ticket_enc
         inner join usuario on usuario.usuario_id = it_ticket_enc.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
WHERE 1 = 1
  " . (($type != '') ? "AND it_ticket_enc.freebet != '0'" : '') . "

  AND it_ticket_enc.fecha_crea_time >= '" . date("Y-m-d") . " 00:00:00'
  AND it_ticket_enc.fecha_crea_time <= '" . date("Y-m-d") . " 23:59:59'
group by mandante.mandante
order by value desc;
        ";


            $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();
            $Transaction = $BonoInternoMySqlDAO->getTransaction();

            $BonoInterno = new BonoInterno();
            $Resultado = $BonoInterno->execQuery($Transaction, $sql);
            $redisPrefix = 'CantTotalUserBetsSportsTotalTotal+' . $type; // Valor por defecto

            $redis = RedisConnectionTrait::getRedisInstance(true);
            $redisParam = ['ex' => 18000];
            if ($redis != null) {
                $redis->set($redisPrefix, json_encode($Resultado), $redisParam);
            }
        }
        $Types = array('FREEBET', '');
        foreach ($Types as $type) {
            $sql = "
       
SELECT CONCAT(UPPER(mandante.descripcion),' ',UPPER(pais.pais_nom))                                 name,
       COUNT(DISTINCT (it_ticket_enc.usuario_id))              value,
       DATE_FORMAT(MAX(it_ticket_enc.fecha_crea_time), '%Y-%m-%d %H:%i:%s') date
FROM it_ticket_enc
         inner join usuario on usuario.usuario_id = it_ticket_enc.usuario_id
         inner join pais on usuario.pais_id = pais.pais_id
         inner join mandante on usuario.mandante = mandante.mandante
WHERE 1 = 1
  " . (($type != '') ? "AND it_ticket_enc.freebet != '0'" : '') . "

  AND it_ticket_enc.fecha_crea_time >= '" . date("Y-m-d") . " 00:00:00'
  AND it_ticket_enc.fecha_crea_time <= '" . date("Y-m-d") . " 23:59:59'
group by mandante.mandante,pais.pais_id
order by value desc;
        ";


            $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();
            $Transaction = $BonoInternoMySqlDAO->getTransaction();

            $BonoInterno = new BonoInterno();
            $Resultado = $BonoInterno->execQuery($Transaction, $sql);
            $redisPrefix = 'CantTotalUserBetsSportsCountryTotalTotal+' . $type; // Valor por defecto

            $redis = RedisConnectionTrait::getRedisInstance(true);
            $redisParam = ['ex' => 18000];
            if ($redis != null) {
                $redis->set($redisPrefix, json_encode($Resultado), $redisParam);
            }
        }
        unlink($filename);

    }
}

