<?php
use Backend\dto\BonoInterno;
use Backend\utils\RedisConnectionTrait;
use Backend\utils\SlackVS;
use Backend\utils\BackgroundProcessVS;
use Backend\sql\Transaction;

class CronJobReprocessRollover
{
    private $BackgroundProcessVS;

    public function __construct()
    {
        $this->BackgroundProcessVS = new BackgroundProcessVS();
    }


    public function execute() {
        date_default_timezone_set('America/Bogota');
        $redis = RedisConnectionTrait::getRedisInstance(true);
        $filename = __DIR__ . '/lastrunCronJobReprocessRollover';

        /* Obtención última ejecución */
        $BonoInterno = new BonoInterno();
        $sqlProcesoInterno2 = "SELECT * FROM proceso_interno2 WHERE tipo='REPROCESSROLLOVER'";
        $data = $BonoInterno->execQuery('', $sqlProcesoInterno2);
        $data = $data[0];
        $lastExecDate = $data->{'proceso_interno2.fecha_ultima'};

        if ($lastExecDate == '') return;


        /*Verificación intervalos de ejecución*/
        $minutesBetweenExecutions = 2;
        $currentExecDate = date('Y-m-d H:i:00');
        $differenceBetweenExecsTimeStamp = strtotime($currentExecDate) - strtotime($lastExecDate);
        if (($differenceBetweenExecsTimeStamp/60) < $minutesBetweenExecutions) return;


        /*Zona habilita nuevamente cron Ante posibles suspensiones*/
        if (file_exists($filename)) {

            $datefilename = date("Y-m-d H:i:s", filemtime($filename));

            if ($datefilename <= date("Y-m-d H:i:s", strtotime('-60 minute'))) {
                unlink($filename);
            }

            return;
        }


        /*Actualización última ocasión de ejecución*/
        $Transaction = new Transaction();
        $sql = "UPDATE proceso_interno2 set fecha_ultima = '".$currentExecDate."' WHERE tipo='REPROCESSROLLOVER'";
        $BonoInterno->execUpdate($Transaction, $sql);
        $Transaction->commit();
        file_put_contents($filename, 'RUN');


        /*Obtención solicitudes a reprocesar*/
        $unconcludedRolloverSql = "SELECT * FROM log_cron WHERE tipo LIKE 'ROLLOVER_%' AND estado LIKE 'INIT%' AND fecha_modif <= ADDDATE(now(), INTERVAL - 10 MINUTE)";
        $unconcludedRolloverProcesses = $BonoInterno->execQuery("", $unconcludedRolloverSql);

        /*Definición índices para reintento*/
        $comandos = [];
        $maxTries = 4;
        foreach ($unconcludedRolloverProcesses as &$unconcludedProcess) {
            $lastTry = 0;
            $lastTry = explode("_", $unconcludedProcess->{"log_cron.estado"})[1];
            if (empty($lastTry)) {
                $unconcludedProcess->{"log_cron.estado"} .= "_2";
            }
            elseif($lastTry >= $maxTries) {
                $unconcludedProcess->{"log_cron.estado"} = "REJECTED";
                continue;
            }
            else {
                $unconcludedProcess->{"log_cron.estado"} = explode("_", $unconcludedProcess->{"log_cron.estado"})[0] . "_" . ($lastTry + 1);
            }

            /*Generamos el índice para disparar de nuevo su ejecución*/
            $transactionType = "";
            $transactionType = (explode("_", $unconcludedProcess->{'tipo'}))[1];
            if ($transactionType == "SPORT") {
                $comandos[] = $this->BackgroundProcessVS->getCommandExecute(__DIR__ . "/../../src/integrations/casino/VerificarRollower.php", "SPORT " . $unconcludedProcess->{'log_cron.valor_id2'} . ' '. $unconcludedProcess->{'log_cron.usuario_id'} );
            }
            else {
                $comandos[] = $this->BackgroundProcessVS->getCommandExecute(__DIR__ . "/../../src/integrations/casino/VerificarRollower.php", "CASINO '' " . $unconcludedProcess->{'log_cron.usuario_id'} . " " . $unconcludedProcess->{'log_cron.valor_id2'});
            }
        }
        unset($unconcludedProcess);


        /*Actualización estados de los procesos*/
        foreach ($unconcludedRolloverProcesses as $process) {
            $logId = 0;
            $logId = $process->{'log_cron.logcron_id'};
            $logState = $process->{'log_cron.estado'};

            $Transaction = new Transaction();
            $turnOffSql = "UPDATE log_cron set estado = '". $logState ."' WHERE logcron_id = {$logId}";
            $BonoInterno->execUpdate($Transaction, $turnOffSql);
            $Transaction->commit();
        }


        /*Calcula el grupo basado en unidades de 10 minutos con el desplazamiento que especificaste*/
        $minute = date('i');
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

        /*Envío índices a redis*/
        $redisParam = ['ex' => 18000];
        foreach ($comandos as $comando) {
            if ($redis != null) {
                $redisPrefix = $redisPrefixPrefix . "+UID" . $comando;
                $argv = explode($comando, ' ');
                $redis->set($redisPrefix, json_encode($argv), $redisParam);
            }
        }

        unlink($filename);
    }
}