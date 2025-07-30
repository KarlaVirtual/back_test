<?php


// Crear una instancia del cliente Redis
use Backend\dto\BonoDetalle;
use Backend\dto\BonoInterno;
use Backend\dto\Clasificador;
use Backend\dto\JackpotInterno;
use Backend\dto\Proveedor;
use Backend\dto\UsuarioBono;
use Backend\integrations\crm\Crm;
use Backend\integrations\crm\Optimove;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\UsuarioBonoMySqlDAO;
use Backend\utils\RedisConnectionTrait;
use Backend\dto\RuletaInterno;


use Backend\dto\CategoriaMandante;
use Backend\dto\CategoriaProducto;
use Backend\dto\Ciudad;

use Exception;
use Backend\dto\Mandante;
use Backend\dto\Pais;
use Backend\dto\ProductoDetalle;
use Backend\dto\ProductoMandante;

use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\Usuario;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\Subproveedor;
use Backend\dto\TransprodLog;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransproductoDetalle;
use Backend\dto\SubproveedorMandante;

use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioMensaje;
use Backend\dto\UsuarioMensajecampana;
use Backend\dto\UsuarioRecarga;
use Backend\dto\UsuarioTarjetacredito;
use Backend\integrations\casino\PLAYNGOSERVICES;
use Backend\integrations\casino\PLAYTECHSERVICES;
use Backend\integrations\mensajeria\Intico;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\mysql\CiudadMySqlDAO;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransproductoDetalleMySqlDAO;

use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\UsuarioTarjetacreditoMySqlDAO;
use phpseclib3\Math\BigInteger\Engines\PHP;
use \CurlWrapper;

require(__DIR__ . '/../vendor/autoload.php');
ini_set('memory_limit', '-1');
$_ENV["enabledConnectionGlobal"] = 1;
ini_set('max_execution_time', 0); // 0 significa sin límite

$redis = RedisConnectionTrait::getRedisInstance(true);
$argvG = $argv;

$pattern = $argvG[1];
//exec("php -f /home/home2/backendprodfinal/api/src/imports/Slack/message.php '" . $pattern . "' '#dev2' > /dev/null & ");

// Definir el patrón
$filename = __DIR__ . '/lastruncron' . str_replace('*', '', $pattern);

$datefilename = date("Y-m-d H:i:s", filemtime($filename));
if ($datefilename <= date("Y-m-d H:i:s", strtotime('-90 minutes'))) {
    unlink($filename);

}
if ($pattern == 'F2BACK*') {
    if ($datefilename <= date("Y-m-d H:i:s", strtotime('-20 minutes'))) {
        unlink($filename);

    }
}
if (file_exists($filename)) {
    //throw new Exception("There is a process currently running", "1");
    exit();
}
file_put_contents($filename, 'RUN');

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

try {


    if ($pattern == 'F3BACK*') {

        $arrayCampaign = array();

        $iterator = null;
        $count = 10000; // Cantidad sugerida por iteración (no es exacta)
        $limit = 10000;
        $total = 0;

        while ($iterator !== 0 && $total < $limit) {
            $keys = $redis->scan($iterator, $pattern, $count);
            if ($keys === false) break;
            $total += oldCount($keys);

            foreach ($keys as $key) {

                if (strpos($key, 'AgregarBonoBackground') !== false) {
                    $argg = explode('+', $key);
                    $UsuarioId = str_replace('UID', '', $argg[2]);
                    $BonoId = $argg[3];
                    $CampaignID = $argg[4];

                    if ($arrayCampaign[$CampaignID] == null) {
                        $arrayCampaign[$CampaignID] = 0;
                    }

                    exec("php -f " . __DIR__ . "/agregarBonoExec.php " . $UsuarioId . " " . $BonoId . " " . $CampaignID . " " . $logID . " " . ($arrayCampaign[$CampaignID] * 45000) . " > /dev/null &");
                    $arrayCampaign[$CampaignID]++;


                } else {
                    // Obtener el valor de la clave
                    $value = $redis->get($key);

                    $value = json_decode($value, true);


                }
                if (strpos($key, 'AgregarMensajeTextoBackground') !== false) {
                    $argv = $value;
                    $UsuarioId = $argv[1];
                    $TemplateId = $argv[2];
                    $CampaignId = $argv[3];
                    exec("php -f " . __DIR__ . "/agregarEnviarSMS.php " . $UsuarioId . "  " . $TemplateId . " > /dev/null &");


                }

                // Eliminar la clave
                //echo "Clave eliminada: $key\n";
                $redis->del($key);

            }
            //$redis->del($keys);
        }
    }

    if ($pattern == 'ADMIN2F3BACK*') {


        $iterator = null;
        $count = 10000; // Cantidad sugerida por iteración (no es exacta)
        $limit = 30000;
        $total = 0;

        while ($iterator !== 0 && $total < $limit) {
            $keys = $redis->scan($iterator, $pattern, $count);
            if ($keys === false) break;
            $total += oldCount($keys);

            foreach ($keys as $key) {

                if (strpos($key, 'AgregarBonoBackground') !== false) {
                    $argg = explode('+', $key);
                    $UsuarioId = str_replace('UID', '', $argg[2]);
                    $BonoId = $argg[3];
                    $CampaignID = $argg[4];

                    if ($arrayCampaign[$CampaignID] == null) {
                        $arrayCampaign[$CampaignID] = 0;
                    }

                    exec("php -f " . __DIR__ . "/agregarBonoExec.php " . $UsuarioId . " " . $BonoId . " " . $CampaignID . " " . $logID . " " . ($arrayCampaign[$CampaignID] * 45000) . " > /dev/null &");
                    $arrayCampaign[$CampaignID]++;


                } else {
                    // Obtener el valor de la clave
                    $value = $redis->get($key);

                    $value = json_decode($value, true);


                }
                if (strpos($key, 'AgregarMensajeTextoBackground') !== false) {
                    $argv = $value;
                    $UsuarioId = $argv[1];
                    $TemplateId = $argv[2];
                    $CampaignId = $argv[3];
                    exec("php -f " . __DIR__ . "/agregarEnviarSMS.php " . $UsuarioId . "  " . $TemplateId . " > /dev/null &");


                }

                // Eliminar la clave
                //echo "Clave eliminada: $key\n";

            }
            $redis->del($keys);

        }
    }

    if ($pattern == 'ADMIN2PLAYTECHF3BACK*' || $pattern == 'ADMIN2PRAGMATICF3BACK*'

        || $pattern == 'ADMIN3PLAYTECHF3BACK*' || $pattern == 'ADMIN3PRAGMATICF3BACK*'
        || $pattern == 'ADMIN3PLATIPUSF3BACK*' || $pattern == 'ADMIN2PLATIPUSF3BACK*'
    ) {


        // Control de procesos simultáneos
        $maxConcurrentProcesses = 20; // Máximo de 10 procesos a la vez
        $activeProcesses = [];


        // Obtener todas las claves que coinciden con el patrón

        $iterator = null;
        $count = 10000; // Cantidad sugerida por iteración (no es exacta)
        $limit = 10000;
        $total = 0;

        while ($iterator !== 0 && $total < $limit) {
            $keys = $redis->scan($iterator, $pattern, $count);
            if ($keys === false) break;
            $total += oldCount($keys);
            foreach ($keys as $key) {

                if (strpos($key, 'AgregarBonoBackground') !== false) {
                    $argg = explode('+', $key);
                    $UsuarioId = str_replace('UID', '', $argg[2]);
                    $BonoId = $argg[3];
                    $CampaignID = $argg[4];
                    $logID = $argg[5];

                    if ($arrayCampaign[$CampaignID] == null) {
                        $arrayCampaign[$CampaignID] = 0;
                    }

                    //usleep(4500); // 30,000 microsegundos = 60ms


                    // Esperar si hay 10 procesos corriendo
                    while (count($activeProcesses) >= $maxConcurrentProcesses) {
                        foreach ($activeProcesses as $key => $pid) {
                            $res = pcntl_waitpid($pid, $status, WNOHANG);
                            if ($res > 0) {
                                unset($activeProcesses[$key]); // Remover procesos terminados
                            }
                        }
                        usleep(10000); // Pequeña pausa (0.1s) para no consumir CPU en el loop
                    }


                    print_r(PHP_EOL);
                    print_r($key);
                    print_r(PHP_EOL);

                    // Crear un nuevo proceso hijo
                    $pid = pcntl_fork();
                    if ($pid == -1) {
                    } elseif ($pid == 0) {
                        // Código que ejecuta cada proceso hijo


                        $Usuario = new Usuario($UsuarioId);
                        $BonoInterno = new BonoInterno($BonoId);
                        $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();

                        $Registro = new Registro("", $Usuario->usuarioId);
                        $CiudadMySqlDAO = new CiudadMySqlDAO();
                        $CONDSUBPROVIDER = array();
                        $CONDGAME = array();


                        try {
                            $BonoDetalleROUNDSFREE = new BonoDetalle('', $BonoId, 'REPETIRBONO');
                            $repetirBono = $BonoDetalleROUNDSFREE->valor;
                        } catch (Exception $e) {
                            $repetirBono = 0;
                        }
                        $continue = true;
                        if (!$repetirBono) {
                            try {
                                $VerifUsuarioBono = new UsuarioBono('', $Usuario->usuarioId, $BonoId);
                                $continue = false;
                            } catch (Exception $e) {
                            }
                        }
                        if ($continue) {


//Bonos Freespin
                            if ($BonoInterno->tipo == "8") {
                                $Transaction = $BonoInternoMySqlDAO->getTransaction();
                                $sqlDetalleBono = "select * from bono_detalle a  where a.bono_id='" . $BonoId . "' AND (moneda='' OR moneda='" . $Usuario->moneda . "') ";

                                $bonoDetalles = $BonoInterno->execQuery($Transaction, $sqlDetalleBono);

                                foreach ($bonoDetalles as $bonoDetalle) {


                                    if (stristr($bonoDetalle->{'a.tipo'}, 'CONDSUBPROVIDER')) {

                                        $idSub = explode("CONDSUBPROVIDER", $bonoDetalle->{'a.tipo'})[1];

                                        array_push($CONDSUBPROVIDER, $idSub);

                                    }

                                    if (stristr($bonoDetalle->{'a.tipo'}, 'CONDGAME')) {

                                        $idGame = explode("CONDGAME", $bonoDetalle->{'a.tipo'})[1];
                                        if ($idGame == '') {
                                            if ($bonoDetalle->{'a.valor'} != '') {
                                                $idGame = $bonoDetalle->{'a.valor'};
                                            }
                                        }
                                        array_push($CONDGAME, $idGame);

                                    }


                                    if (stristr($bonoDetalle->{'a.tipo'}, 'PREFIX')) {

                                        $Prefix = explode("PREFIX", $bonoDetalle->{'a.tipo'})[1];
                                        if ($Prefix == '') {
                                            if ($bonoDetalle->{'a.valor'} != '') {
                                                $Prefix = $bonoDetalle->{'a.valor'};
                                            }
                                        }

                                    }
                                    if (stristr($bonoDetalle->{'a.tipo'}, 'MAXJUGADORES')) {

                                        $MaxplayersCount = explode("MAXJUGADORES", $bonoDetalle->{'a.tipo'})[1];
                                        if ($MaxplayersCount == '') {
                                            if ($bonoDetalle->{'a.valor'} != '') {
                                                $MaxplayersCount = $bonoDetalle->{'a.valor'};
                                            }
                                        }

                                    }
                                }

                                $Subproveedor = new Subproveedor($idSub);
                                $Proveedor = new Proveedor($Subproveedor->proveedorId);

                                syslog(LOG_WARNING, "CAMPA OPTIMOVE 5: " . ($Subproveedor->abreviado));
                                if (in_array($Subproveedor->subproveedorId, $CONDSUBPROVIDER)) {

                                    $responseBonoGlobal = $BonoInterno->bonoGlobal($Proveedor, $BonoId, $CONDGAME, $Usuario->mandante, $Usuario->usuarioId, $Transaction, 0, false, 0, $BonoInterno->nombre, $Prefix, $MaxplayersCount);
                                    if ($responseBonoGlobal["status"] != "ERROR") {

                                        $Transaction->commit();
                                        updateLog($logID, 'OK', '', json_encode($responseBonoGlobal));

                                    } else {

                                        updateLog($logID, 'ERRORFINAL', '', json_encode($responseBonoGlobal));
                                    }
                                } else {
                                    updateLog($logID, 'ERROR', '', 'CONDin_array($Subproveedor->subproveedorId, $CONDSUBPROVIDER)');

                                }

                            }
                        } else {
                            updateLog($logID, 'ERRORREPETIRBONO', '', '');

                        }
                        $arrayCampaign[$CampaignID]++;


                        exit(0);
                    } else {
                        $activeProcesses[] = $pid; // Registrar proceso en ejecución
                    }


                    //exec("php -f " . __DIR__ . "/agregarBonoExecProvider.php " . $UsuarioId . " " . $BonoId . " " . $CampaignID . " " . $logID  . " " . ($arrayCampaign[$CampaignID] * 45000) . " > /dev/null &");


                }

                // Eliminar la clave
                //echo "Clave eliminada: $key\n";

            }
            $redis->del($keys);
        }
// Esperar a que terminen los procesos restantes
        foreach ($activeProcesses as $pid) {
            pcntl_waitpid($pid, $status);
        }
    }


    if ($pattern == 'ADMIN3ALTENARF3BACK*' || $pattern == 'ADMIN2ALTENARF3BACK*'
    ) {



        // Control de procesos simultáneos
        $maxConcurrentProcesses = 20; // Máximo de 10 procesos a la vez
        $activeProcesses = [];



        // Obtener todas las claves que coinciden con el patrón

        $iterator = null;
        $count = 10000; // Cantidad sugerida por iteración (no es exacta)
        $limit = 10000;
        $total = 0;

        while ($iterator !== 0 && $total < $limit) {
            $keys = $redis->scan($iterator, $pattern, $count);
            if ($keys === false) break;
            $total += oldCount($keys);

            foreach ($keys as $key) {

                if (strpos($key, 'AgregarBonoBackground') !== false) {
                    $argg = explode('+', $key);
                    $UsuarioId = str_replace('UID', '', $argg[2]);
                    $BonoId = $argg[3];
                    $CampaignID = $argg[4];
                    $logID = $argg[5];

                    if ($arrayCampaign[$CampaignID] == null) {
                        $arrayCampaign[$CampaignID] = 0;
                    }

                    //usleep(4500); // 30,000 microsegundos = 60ms


                    // Esperar si hay 10 procesos corriendo
                    while (count($activeProcesses) >= $maxConcurrentProcesses) {
                        foreach ($activeProcesses as $key => $pid) {
                            $res = pcntl_waitpid($pid, $status, WNOHANG);
                            if ($res > 0) {
                                unset($activeProcesses[$key]); // Remover procesos terminados
                            }
                        }
                        usleep(10000); // Pequeña pausa (0.1s) para no consumir CPU en el loop
                    }


                    print_r(PHP_EOL);
                    print_r($key);
                    print_r(PHP_EOL);

                    // Crear un nuevo proceso hijo
                    $pid = pcntl_fork();
                    if ($pid == -1) {
                    } elseif ($pid == 0) {

                        if (true) {


                            try {


                                $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();

                                $BonoId = $BonoId;
                                if ($BonoId == '26354') {
                                    $BonoId = 26366;
                                }

                                $BonoInterno = new BonoInterno($BonoId);


                                $Usuario = new Usuario($UsuarioId);
                                if ($BonoId == 32785) {
                                    $UsuarioBono = new UsuarioBono();

                                    $UsuarioBono->setUsuarioId($Usuario->usuarioId);
                                    $UsuarioBono->setBonoId($BonoInterno->bonoId);
                                    $UsuarioBono->setValor(0);
                                    $UsuarioBono->setValorBono(0);
                                    $UsuarioBono->setValorBase(0);
                                    $UsuarioBono->setEstado('P');
                                    $UsuarioBono->setErrorId('0');
                                    $UsuarioBono->setIdExterno('0');
                                    $UsuarioBono->setMandante($BonoInterno->mandante);
                                    $UsuarioBono->setUsucreaId('0');
                                    $UsuarioBono->setUsumodifId('0');
                                    $UsuarioBono->setApostado('0');
                                    $UsuarioBono->setVersion('3');
                                    $UsuarioBono->setRollowerRequerido('0');
                                    $UsuarioBono->setCodigo('');
                                    $UsuarioBono->setExternoId('0');
                                    $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO();
                                    $UsuarioBonoMysqlDAO->insert($UsuarioBono);
                                    $UsuarioBonoMysqlDAO->getTransaction()->commit();
                                    updateLog($logID, 'OK', '', '');

                                } elseif ($BonoId == 32793) {
                                    $UsuarioBono = new UsuarioBono();

                                    $UsuarioBono->setUsuarioId($Usuario->usuarioId);
                                    $UsuarioBono->setBonoId($BonoInterno->bonoId);
                                    $UsuarioBono->setValor(0);
                                    $UsuarioBono->setValorBono(0);
                                    $UsuarioBono->setValorBase(0);
                                    $UsuarioBono->setEstado('P');
                                    $UsuarioBono->setErrorId('0');
                                    $UsuarioBono->setIdExterno('0');
                                    $UsuarioBono->setMandante($BonoInterno->mandante);
                                    $UsuarioBono->setUsucreaId('0');
                                    $UsuarioBono->setUsumodifId('0');
                                    $UsuarioBono->setApostado('0');
                                    $UsuarioBono->setVersion('3');
                                    $UsuarioBono->setRollowerRequerido('0');
                                    $UsuarioBono->setCodigo('');
                                    $UsuarioBono->setExternoId('0');
                                    $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO();
                                    $UsuarioBonoMysqlDAO->insert($UsuarioBono);
                                    $UsuarioBonoMysqlDAO->getTransaction()->commit();
                                    updateLog($logID, 'OK', '', '');

                                } elseif ($BonoId == 32821) {
                                    $UsuarioBono = new UsuarioBono();

                                    $UsuarioBono->setUsuarioId($Usuario->usuarioId);
                                    $UsuarioBono->setBonoId($BonoInterno->bonoId);
                                    $UsuarioBono->setValor(0);
                                    $UsuarioBono->setValorBono(0);
                                    $UsuarioBono->setValorBase(0);
                                    $UsuarioBono->setEstado('P');
                                    $UsuarioBono->setErrorId('0');
                                    $UsuarioBono->setIdExterno('0');
                                    $UsuarioBono->setMandante($BonoInterno->mandante);
                                    $UsuarioBono->setUsucreaId('0');
                                    $UsuarioBono->setUsumodifId('0');
                                    $UsuarioBono->setApostado('0');
                                    $UsuarioBono->setVersion('3');
                                    $UsuarioBono->setRollowerRequerido('0');
                                    $UsuarioBono->setCodigo('');
                                    $UsuarioBono->setExternoId('0');
                                    $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO();
                                    $UsuarioBonoMysqlDAO->insert($UsuarioBono);
                                    $UsuarioBonoMysqlDAO->getTransaction()->commit();
                                    updateLog($logID, 'OK', '', '');

                                } elseif ($BonoId == 32822) {
                                    $UsuarioBono = new UsuarioBono();

                                    $UsuarioBono->setUsuarioId($Usuario->usuarioId);
                                    $UsuarioBono->setBonoId($BonoInterno->bonoId);
                                    $UsuarioBono->setValor(0);
                                    $UsuarioBono->setValorBono(0);
                                    $UsuarioBono->setValorBase(0);
                                    $UsuarioBono->setEstado('P');
                                    $UsuarioBono->setErrorId('0');
                                    $UsuarioBono->setIdExterno('0');
                                    $UsuarioBono->setMandante($BonoInterno->mandante);
                                    $UsuarioBono->setUsucreaId('0');
                                    $UsuarioBono->setUsumodifId('0');
                                    $UsuarioBono->setApostado('0');
                                    $UsuarioBono->setVersion('3');
                                    $UsuarioBono->setRollowerRequerido('0');
                                    $UsuarioBono->setCodigo('');
                                    $UsuarioBono->setExternoId('0');
                                    $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO();
                                    $UsuarioBonoMysqlDAO->insert($UsuarioBono);
                                    $UsuarioBonoMysqlDAO->getTransaction()->commit();
                                    updateLog($logID, 'OK', '', '');

                                } else {

                                    try {
                                        $BonoDetalleROUNDSFREE = new BonoDetalle('', $BonoId, 'REPETIRBONO');
                                        $repetirBono = $BonoDetalleROUNDSFREE->valor;
                                    } catch (Exception $e) {
                                        $repetirBono = 0;
                                    }
                                    $continue = true;
                                    if (!$repetirBono) {
                                        try {
                                            $VerifUsuarioBono = new UsuarioBono('', $Usuario->usuarioId, $BonoId);
                                            $continue = false;
                                        } catch (Exception $e) {
                                        }
                                    }
                                    if ($continue) {
                                        $UserId = $UsuarioId;
                                        $Usuario = new Usuario($UserId);
                                        $BonoInterno = new BonoInterno($BonoId);
                                        $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();

                                        $Registro = new Registro("", $Usuario->usuarioId);
                                        $CiudadMySqlDAO = new CiudadMySqlDAO();
                                        $CONDSUBPROVIDER = array();
                                        $CONDGAME = array();
//Bonos Freespin
                                        if ($BonoInterno->tipo == "8") {
                                            $Transaction = $BonoInternoMySqlDAO->getTransaction();
                                            $sqlDetalleBono = "select * from bono_detalle a  where a.bono_id='" . $BonoId . "' AND (moneda='' OR moneda='" . $Usuario->moneda . "') ";

                                            $bonoDetalles = $BonoInterno->execQuery($Transaction, $sqlDetalleBono);

                                            foreach ($bonoDetalles as $bonoDetalle) {


                                                if (stristr($bonoDetalle->{'a.tipo'}, 'CONDSUBPROVIDER')) {

                                                    $idSub = explode("CONDSUBPROVIDER", $bonoDetalle->{'a.tipo'})[1];

                                                    array_push($CONDSUBPROVIDER, $idSub);

                                                }

                                                if (stristr($bonoDetalle->{'a.tipo'}, 'CONDGAME')) {

                                                    $idGame = explode("CONDGAME", $bonoDetalle->{'a.tipo'})[1];
                                                    if ($idGame == '') {
                                                        if ($bonoDetalle->{'a.valor'} != '') {
                                                            $idGame = $bonoDetalle->{'a.valor'};
                                                        }
                                                    }
                                                    array_push($CONDGAME, $idGame);

                                                }


                                                if (stristr($bonoDetalle->{'a.tipo'}, 'PREFIX')) {

                                                    $Prefix = explode("PREFIX", $bonoDetalle->{'a.tipo'})[1];
                                                    if ($Prefix == '') {
                                                        if ($bonoDetalle->{'a.valor'} != '') {
                                                            $Prefix = $bonoDetalle->{'a.valor'};
                                                        }
                                                    }

                                                }
                                                if (stristr($bonoDetalle->{'a.tipo'}, 'MAXJUGADORES')) {

                                                    $MaxplayersCount = explode("MAXJUGADORES", $bonoDetalle->{'a.tipo'})[1];
                                                    if ($MaxplayersCount == '') {
                                                        if ($bonoDetalle->{'a.valor'} != '') {
                                                            $MaxplayersCount = $bonoDetalle->{'a.valor'};
                                                        }
                                                    }

                                                }
                                            }
                                            print_r('CAMPA OPTIMOVE 4');

                                            $Subproveedor = new Subproveedor($idSub);
                                            $Proveedor = new Proveedor($Subproveedor->proveedorId);
                                            print_r($Subproveedor);
                                            syslog(LOG_WARNING, "CAMPA OPTIMOVE 5: " . ($Subproveedor->abreviado));
                                            if (in_array($Subproveedor->subproveedorId, $CONDSUBPROVIDER)) {

                                                $responseBonoGlobal = $BonoInterno->bonoGlobal($Proveedor, $BonoId, $CONDGAME, $Usuario->mandante, $Usuario->usuarioId, $Transaction, 0, false, 0, $BonoInterno->nombre, $Prefix, $MaxplayersCount);
                                                if ($responseBonoGlobal["status"] != "ERROR") {

                                                    $Transaction->commit();
                                                    updateLog($logID, 'OK', '', json_encode($responseBonoGlobal));

                                                } else {

                                                    updateLog($logID, 'ERRORFINAL', '', json_encode($responseBonoGlobal));
                                                }
                                            } else {
                                                updateLog($logID, 'ERROR', '', 'CONDin_array($Subproveedor->subproveedorId, $CONDSUBPROVIDER)');

                                            }

                                        }
//Bonos Freebet
                                        if ($BonoInterno->tipo == "6") {
                                            $Transaction = $BonoInternoMySqlDAO->getTransaction();

                                            $UsuarioBono = new UsuarioBono();
                                            $UsuarioBono->setUsuarioId($Usuario->usuarioId);
                                            $UsuarioBono->setBonoId($BonoInterno->bonoId);
                                            $UsuarioBono->setValor(0);
                                            $UsuarioBono->setValorBono(0);
                                            $UsuarioBono->setValorBase(0);
                                            $UsuarioBono->setEstado('A');
                                            $UsuarioBono->setErrorId('0');
                                            $UsuarioBono->setIdExterno('0');
                                            $UsuarioBono->setMandante($BonoInterno->mandante);
                                            $UsuarioBono->setUsucreaId('0');
                                            $UsuarioBono->setUsumodifId('0');
                                            $UsuarioBono->setApostado('0');
                                            $UsuarioBono->setVersion('3');
                                            $UsuarioBono->setRollowerRequerido('0');
                                            $UsuarioBono->setCodigo('');
                                            $UsuarioBono->setExternoId('0');
                                            $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($Transaction);

                                            $usubonoId = $UsuarioBonoMysqlDAO->insert($UsuarioBono);


                                            $Transaction->commit();
                                            updateLog($logID, 'OK', '', 'DIRECT');
                                        }
//Bonos Deposito
                                        if ($BonoInterno->tipo == "2") {
                                            $Transaction = $BonoInternoMySqlDAO->getTransaction();

                                            $UsuarioBono = new UsuarioBono();
                                            $UsuarioBono->setUsuarioId($Usuario->usuarioId);
                                            $UsuarioBono->setBonoId($BonoInterno->bonoId);
                                            $UsuarioBono->setValor('0');
                                            $UsuarioBono->setValorBono('0');
                                            $UsuarioBono->setValorBase('0');
                                            $UsuarioBono->setEstado('P');
                                            $UsuarioBono->setErrorId('0');
                                            $UsuarioBono->setIdExterno('0');
                                            $UsuarioBono->setMandante($BonoInterno->mandante);
                                            $UsuarioBono->setUsucreaId('0');
                                            $UsuarioBono->setUsumodifId('0');
                                            $UsuarioBono->setApostado('0');
                                            $UsuarioBono->setVersion('3');
                                            $UsuarioBono->setRollowerRequerido('0');
                                            $UsuarioBono->setCodigo('');
                                            $UsuarioBono->setExternoId('0');
                                            $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($Transaction);

                                            $usubonoId = $UsuarioBonoMysqlDAO->insert($UsuarioBono);

                                            $Transaction->commit();
                                            updateLog($logID, 'OK', '', 'DIRECT');

                                        }
//Bonos No deposito  //Rollower Requerido
                                        if ($BonoInterno->tipo == "3") {

                                            $Transaction = $BonoInternoMySqlDAO->getTransaction();

                                            try {
                                                $BonoDetalleVALORBONO = new BonoDetalle('', $BonoId, 'VALORBONO');
                                                $valor_bono = $BonoDetalleVALORBONO->valor;
                                            } catch (Exception $e) {

                                            }

                                            if ($valor_bono == '') {
                                                $valor_bono = '0';
                                            }

                                            try {
                                                $BonoDetalleWFACTORBONO = new BonoDetalle('', $BonoId, 'WFACTORBONO');
                                                $rollowerBono = $BonoDetalleWFACTORBONO->valor;
                                            } catch (Exception $e) {

                                            }

                                            try {
                                                $BonoDetalleROUNDSFREE = new BonoDetalle('', $BonoId, 'VALORROLLOWER');
                                                $rollowerValor = $BonoDetalleROUNDSFREE->valor;
                                            } catch (Exception $e) {

                                            }
                                            $rollowerRequerido = 0;

                                            if ($rollowerBono) {
                                                $rollowerRequerido = $rollowerRequerido + ($rollowerBono * $valor_bono);

                                            }
                                            if ($rollowerValor) {
                                                $rollowerRequerido = $rollowerRequerido + ($rollowerValor);


                                            }
                                            $codigoBono = 'CRM' . sprintf('%010d', rand(0, 9999));
                                            $UsuarioBono = new UsuarioBono();
                                            $UsuarioBono->setUsuarioId(0);
                                            $UsuarioBono->setBonoId($BonoInterno->bonoId);
                                            $UsuarioBono->setValor($valor_bono);
                                            $UsuarioBono->setValorBono($valor_bono);
                                            $UsuarioBono->setValorBase($valor_bono);
                                            $UsuarioBono->setEstado('L');
                                            $UsuarioBono->setErrorId('0');
                                            $UsuarioBono->setIdExterno('0');
                                            $UsuarioBono->setMandante($BonoInterno->mandante);
                                            $UsuarioBono->setUsucreaId('0');
                                            $UsuarioBono->setUsumodifId('0');
                                            $UsuarioBono->setApostado('0');
                                            $UsuarioBono->setVersion('3');
                                            $UsuarioBono->setRollowerRequerido($rollowerRequerido);
                                            $UsuarioBono->setCodigo($codigoBono);
                                            $UsuarioBono->setExternoId('0');
                                            $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($Transaction);

                                            $usubonoId = $UsuarioBonoMysqlDAO->insert($UsuarioBono);
                                            $Transaction->commit();


                                            $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
                                            $transaccion = $BonoDetalleMySqlDAO->getTransaction();

                                            $BonoInterno = new BonoInterno();

                                            $usuarioSql = "select ciudad.ciudad_id,ciudad.depto_id,usuario.pais_id,usuario.moneda,usuario.mandante FROM registro
  INNER JOIN usuario ON (usuario.usuario_id = registro.usuario_id)
  INNER JOIN pais ON (pais.pais_id = usuario.pais_id)
  LEFT OUTER JOIN ciudad ON (ciudad.ciudad_id = registro.ciudad_id)
  LEFT OUTER JOIN departamento ON (ciudad.depto_id = departamento.depto_id) WHERE registro.usuario_id='" . $Usuario->usuarioId . "'";

                                            $dataUsuario = $BonoInterno->execQuery($transaccion, $usuarioSql);


                                            if ($dataUsuario[0]->{'usuario.mandante'} != "") {
                                                $detalles = array(
                                                    "PaisUSER" => $dataUsuario[0]->{'usuario.pais_id'},
                                                    "DepartamentoUSER" => $dataUsuario[0]->{'ciudad.depto_id'},
                                                    "CiudadUSER" => $dataUsuario[0]->{'ciudad.ciudad_id'},
                                                    "MonedaUSER" => $dataUsuario[0]->{'usuario.moneda'},
                                                    "ValorDeposito" => 0

                                                );
                                                $detalles = json_decode(json_encode($detalles));

                                                $respuesta = $BonoInterno->agregarBonoFree($BonoId, $Usuario->usuarioId, $Usuario->mandante, $detalles, true, $codigoBono, $transaccion);
                                                $transaccion->commit();
                                                updateLog($logID, 'OK', '', 'DIRECT2');
                                                updateLog($logID, 'OK', '', json_encode($respuesta));
                                            } else {

                                                updateLog($logID, 'ERROR', '', 'DIRECT');
                                            }

                                        }
//Bonos FreeCasino
                                        if ($BonoInterno->tipo == "5") {
                                            $Transaction = $BonoInternoMySqlDAO->getTransaction();


                                            try {
                                                $BonoDetalleVALORBONO = new BonoDetalle('', $BonoId, 'VALORBONO');
                                                $valorBase = $BonoDetalleVALORBONO->valor;
                                            } catch (Exception $e) {

                                            }

                                            try {
                                                $BonoDetalleVALORBONO = new BonoDetalle('', $BonoId, 'MINAMOUNT');
                                                $valorBono = $BonoDetalleVALORBONO->valor;
                                            } catch (Exception $e) {

                                            }
                                            $UsuarioBono = new UsuarioBono;
                                            $UsuarioBono->setUsuarioId($Usuario->usuarioId);
                                            $UsuarioBono->setBonoId($BonoInterno->bonoId);
                                            $UsuarioBono->setValor(0);
                                            $UsuarioBono->setValorBono($valorBono);
                                            $UsuarioBono->setValorBase($valorBono);
                                            $UsuarioBono->setEstado('A');
                                            $UsuarioBono->setErrorId('0');
                                            $UsuarioBono->setIdExterno('0');
                                            $UsuarioBono->setMandante($BonoInterno->mandante);
                                            $UsuarioBono->setUsucreaId('0');
                                            $UsuarioBono->setUsumodifId('0');
                                            $UsuarioBono->setApostado('0');
                                            $UsuarioBono->setVersion('3');
                                            $UsuarioBono->setRollowerRequerido('0');
                                            $UsuarioBono->setCodigo('');
                                            $UsuarioBono->setExternoId('0');
                                            $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($Transaction);

                                            $usubonoId = $UsuarioBonoMysqlDAO->insert($UsuarioBono);

                                            $Transaction->commit();

                                            updateLog($logID, 'OK', '', 'DIRECT');


                                        }
                                    } else {

                                        updateLog($logID, 'ERRORGEN', '', '');
                                    }
                                }
                            } catch (Exception $e) {
                                print_r($e);

                            }

                        }
                        exit(0);
                    } else {
                        $activeProcesses[] = $pid; // Registrar proceso en ejecución
                    }


                }

                // Eliminar la clave
                //echo "Clave eliminada: $key\n";

            }
            $redis->del($keys);
        }
        // $redis->del($keys);

// Esperar a que terminen los procesos restantes
        foreach ($activeProcesses as $pid) {
            pcntl_waitpid($pid, $status);
        }
    }

    if ($pattern == 'ADMIN3F3BACK*') {




        // Obtener todas las claves que coinciden con el patrón

        $iterator = null;
        $count = 10000; // Cantidad sugerida por iteración (no es exacta)
        $limit = 10000;
        $total = 0;

        while ($iterator !== 0 && $total < $limit) {
            $keys = $redis->keys($iterator, $pattern, $count);
            if ($keys === false) break;
            
            $total += oldCount($keys);

            foreach ($keys as $key) {

                if (strpos($key, 'AgregarBonoBackground') !== false) {
                    $argg = explode('+', $key);
                    $UsuarioId = str_replace('UID', '', $argg[2]);
                    $BonoId = $argg[3];
                    $CampaignID = $argg[4];

                    if ($arrayCampaign[$CampaignID] == null) {
                        $arrayCampaign[$CampaignID] = 0;
                    }

                    exec("php -f " . __DIR__ . "/agregarBonoExec.php " . $UsuarioId . " " . $BonoId . " " . $CampaignID . " " . $logID . " " . ($arrayCampaign[$CampaignID] * 45000) . " > /dev/null &");
                    $arrayCampaign[$CampaignID]++;


                } else {
                    // Obtener el valor de la clave
                    $value = $redis->get($key);

                    $value = json_decode($value, true);


                }
                if (strpos($key, 'AgregarMensajeTextoBackground') !== false) {
                    $argv = $value;
                    $UsuarioId = $argv[1];
                    $TemplateId = $argv[2];
                    $CampaignId = $argv[3];
                    exec("php -f " . __DIR__ . "/agregarEnviarSMS.php " . $UsuarioId . "  " . $TemplateId . " > /dev/null &");


                }

                // Eliminar la clave
                //echo "Clave eliminada: $key\n";
                $redis->del($key);

            }
        }

    }

    if ($pattern == 'FULT3BACK*') {

        $pattern = 'F3BACK*';



        // Obtener todas las claves que coinciden con el patrón

        $iterator = null;
        $count = 10000; // Cantidad sugerida por iteración (no es exacta)
        $limit = 10000;
        $total = 0;

        while ($iterator !== 0 && $total < $limit) {
            $keys = $redis->scan($iterator, $pattern, $count);
            if ($keys === false) break;
            $total += oldCount($keys);

            foreach ($keys as $key) {

                if (strpos($key, 'AgregarBonoBackground') !== false) {
                    $argg = explode('+', $key);
                    $UsuarioId = str_replace('UID', '', $argg[2]);
                    $BonoId = $argg[3];
                    $CampaignID = $argg[4];


                    exec("php -f " . __DIR__ . "/agregarBonoExec.php " . $UsuarioId . " " . $BonoId . " " . $CampaignID . " " . $logID . " > /dev/null &");


                } else {
                    // Obtener el valor de la clave
                    $value = $redis->get($key);

                    $value = json_decode($value, true);


                }
                if (strpos($key, 'AgregarMensajeTextoBackground') !== false) {
                    $argv = $value;
                    $UsuarioId = $argv[1];
                    $TemplateId = $argv[2];
                    $CampaignId = $argv[3];
                    exec("php -f " . __DIR__ . "/agregarEnviarSMS.php " . $UsuarioId . "  " . $TemplateId . " > /dev/null &");


                }

                // Eliminar la clave
                //echo "Clave eliminada: $key\n";
            }
            $redis->del($keys);
        }
    }

// Definir el patrón
    if ($pattern == 'F100BACK*') {

// Obtener todas las claves que coinciden con el patrón


        try {


            // Obtener todas las claves que coinciden con el patrón

            $iterator = null;
            $count = 10000; // Cantidad sugerida por iteración (no es exacta)
            $limit = 10000;
            $total = 0;

            while ($iterator !== 0 && $total < $limit) {
                $keys = $redis->scan($iterator, $pattern, $count);
                if ($keys === false) break;
                $total += oldCount($keys);
                foreach ($keys as $key) {
                    // Obtener el valor de la clave
                    $value = $redis->get($key);

                    if (strpos($key, 'COMMANDEXEC') !== false) {
                        $value = base64_decode($value);
                        exec($value);
                    }

                    // Eliminar la clave
                    $redis->del($key);
                }
            }
        } catch (Exception $e) {
            print_r($e);
        }


    }

// Definir el patrón
    if ($pattern == 'F2BACK*') {

// Obtener todas las claves que coinciden con el patrón

        try {


            // Obtener todas las claves que coinciden con el patrón

            $iterator = null;
            $count = 10000; // Cantidad sugerida por iteración (no es exacta)
            $limit = 10000;
            $total = 0;

            while ($iterator !== 0 && $total < $limit) {
                $keys = $redis->scan($iterator, $pattern, $count);
                if ($keys === false) break;
                $total += oldCount($keys);
                foreach ($keys as $key) {
                    // Obtener el valor de la clave
                    $value = $redis->get($key);

                    $value = json_decode($value, true);

                    if (strpos($key, 'agregarMensajeInboxBackground') !== false) {
                        $argv = $value;
                        $CampaignID = $argv[1];
                        $brand = $argv[2];
                        $ContryId = $argv[3];
                        $EventTypeID = $argv[4];
                        $Channel = $argv[5];

                        $logID = createLog('agregarMensajeInboxBackground', $UserId, $CampaignID, $BonoId, '0', json_encode($argv), '', 'INIT');


                        /* Procesamos */
                        $Optimove = new Optimove();
//$Token = $Optimove->Login($brand,$ContryId);
//$Token=$Token->response;
                        $response = $Optimove->GetCustomerExecutionDetailsByCampaignMenssageInbox($brand, $ContryId, $EventTypeID, $CampaignID, $Channel);
                        $response = json_encode($response);
                        updateLog($logID, 'OK', '', $response);

                    }
                    if (strpos($key, 'AgregarMensajeInboxDirecto') !== false) {
                        $argv = $value;
                        $CampaignID = $argv[0];
                        $Channel = $argv[1];
                        $templateID = $argv[2];
                        $customerID = $argv[3];

                        exec("php -f " . __DIR__ . "/agregarEnviarInbox.php " . $customerID . "  " . $templateID . "  " . $Channel . " > /dev/null &");

                        //updateLog($logID, 'OK', '', $response);

                    }
                    if (strpos($key, 'agregarBonoBackground') !== false) {
                        $argv = $value;
                        $CampaignID = $argv[1];
                        $brand = $argv[2];
                        $ContryId = $argv[3];
                        $EventTypeID = $argv[4];
                        $Channel = $argv[5];
                        $BonoId = $argv[6];
                        $UserId = $argv[7];

                        $logID = createLog('agregarBonoBackground', $UserId == '' ? '0' : $UserId, $CampaignID, $BonoId, '0', json_encode($argv), '', 'INIT');

                        /* Procesamos */
                        $Optimove = new Optimove();
//$Token = $Optimove->Login($brand,$ContryId);
//$Token=$Token->response;
                        if ($EventTypeID == 13) {

                            $response = $Optimove->GetCustomerExecutionDetailsByCampaign($brand, $ContryId, $EventTypeID, $CampaignID, $Channel);
                            $response = json_encode($response);
                        } elseif ($EventTypeID == 11) {
                            $response = $Optimove->GetCustomerExecutionDetailsByCampaignRealTime($brand, $ContryId, $EventTypeID, $CampaignID, $Channel, $BonoId, $UserId);
                            $response = json_encode($response);
                        }
                        updateLog($logID, 'OK', '', $response);

                    }
                    if (strpos($key, 'agregarMensajeTextoBackground') !== false) {
                        $argv = $value;
                        $CampaignID = $argv[1];
                        $brand = $argv[2];
                        $ContryId = $argv[3];
                        $EventTypeID = $argv[4];
                        $Channel = $argv[5];
                        $TemplateId = $argv[6];
                        $UserId = $argv[7];

                        exec("php -f " . __DIR__ . "/agregarMensajeTextoExec.php " . $argv[1] . " " . $argv[2] . " " . $argv[3] . " " . $argv[4] . " " . $argv[5] . " " . $argv[6] . " " . $argv[7] . " > /dev/null &");


                    }
                    if (strpos($key, 'agregarContainermediaNotification') !== false) {
                        $argv = $value;

                        $CampaignID = $argv[1];
                        $brand = $argv[2];
                        $ContryId = $argv[3];
                        $EventTypeID = $argv[4];
                        $Channel = $argv[5];


                        /* Procesamos */
                        $Optimove = new Optimove();


                        if ($EventTypeID == 11) {
                            $Json = $argv[6];
                            $response = $Optimove->GetCustomerExecutionDetailsByContainermediaRealtime($brand, $ContryId, $EventTypeID, $CampaignID, $Channel, $Json);
                            $response = json_encode($response);
                        } else {
                            $response = $Optimove->GetCustomerExecutionDetailsByContainermediaNotification($brand, $ContryId, $EventTypeID, $CampaignID, $Channel);
                            $response = json_encode($response);
                        }


                    }

                    // Eliminar la clave
                    $redis->del($key);
                }
            }
        } catch (Exception $e) {
            print_r($e);
        }
    }

// Definir el patrón
    if ($pattern == 'F4BACK*') {

// Obtener todas las claves que coinciden con el patrón



        // Obtener todas las claves que coinciden con el patrón

        $iterator = null;
        $count = 10000; // Cantidad sugerida por iteración (no es exacta)
        $limit = 10000;
        $total = 0;

        while ($iterator !== 0 && $total < $limit) {
            $keys = $redis->scan($iterator, $pattern, $count);
            if ($keys === false) break;
            $total += oldCount($keys);

            foreach ($keys as $key) {
                // Obtener el valor de la clave
                $keyvalue = explode('+', $key);

                if (strpos($key, 'addbonusmassive') !== false) {
                    $UsuarioId = $keyvalue[3];
                    $BonoId = $keyvalue[4];
                    $CampaignID = $keyvalue[5];

                    $argv = array();
                    $argv[0] = '';
                    $argv[1] = $UsuarioId;
                    $argv[2] = $BonoId;
                    $argv[3] = $CampaignID;


                    $redisParam = ['ex' => 18000];

                    $Prefix = '';
                    $random = mt_rand() / mt_getrandmax(); // Genera un número entre 0 y 1

                    if ($random < 0.3) {
                        $Prefix = 'ADMIN2';
                    } elseif ($random < 0.6) {
                        $Prefix = 'ADMIN3';
                    }

                    $redisPrefix = $Prefix . "F3BACK+AgregarBonoBackground+UID" . $UsuarioId . '+' . $BonoId . '+' . $CampaignID;

                    $redis = RedisConnectionTrait::getRedisInstance(true);

                    if ($redis != null) {

                        $redis->set($redisPrefix, json_encode($argv), $redisParam);
                    }
                }


                // Eliminar la clave
                $redis->del($key);
            }
        }
    }

    $arrayTimes=array(
        'F10BACK*' => 50, //ADMIN12
        'F11BACK*' => 50, //ADMIN3
        'F12BACK*' => 50, //ADMIN12
        'F13BACK*' => 50, //ADMIN13
        'F14BACK*' => 50, //ADMIN14
        'F15BACK*' => 50, //ADMIN14
        'F16BACK*' => 50, //ADMIN12
        'F17BACK*' => 50, //ADMIN13
        'F18BACK*' => 50, //ADMIN14
        'F19BACK*' => 50, //ADMIN10
        'F20BACK*' => 50, //ADMIN13
        'F21BACK*' => 50 //ADMIN3
    );
// Definir el patrón
    if ($pattern == 'F10BACK*' || $pattern == 'F11BACK*' || $pattern == 'F12BACK*'

        || $pattern == 'F13BACK*' || $pattern == 'F14BACK*' || $pattern == 'F15BACK*' || $pattern == 'F16BACK*' || $pattern == 'F17BACK*' || $pattern == 'F18BACK*' || $pattern == 'F19BACK*' || $pattern == 'F20BACK*' || $pattern == 'F21BACK*') {


        // Control de procesos simultáneos
        $maxConcurrentProcesses = 20; // Máximo de 10 procesos a la vez

        if($arrayTimes[$pattern] != '' && $arrayTimes[$pattern] != null) {
            $maxConcurrentProcesses = $arrayTimes[$pattern]; // Máximo de 10 procesos a la vez

        }

        $activeProcesses = [];

        // Obtener todas las claves que coinciden con el patrón

        $iterator = null;
        $count = 5000; // Cantidad sugerida por iteración (no es exacta)
        $limit = 10000;
        $total = 0;
        print_r('empezo');
        flush();
        ob_flush();

        while ($iterator !== 0 && $total < $limit) {
            $keys = $redis->scan($iterator, $pattern, $count);
            if ($keys === false) break;
            
            $total += oldCount($keys);


            // Eliminar la clave
            //exec("php -f /home/home2/backendprodfinal/api/src/imports/Slack/message.php '" .$key . "' '#dev2' > /dev/null & ");
            syslog(LOG_WARNING,'cronRedisCRMKEYtotal '.json_encode($keys));
            print_r(PHP_EOL);
            print_r(PHP_EOL);

            print_r('TOTal'.oldCount($keys));
            print_r(PHP_EOL);
            print_r(PHP_EOL);
            flush();
            ob_flush();
            $cantArrayUsers = array();
            foreach ($keys as $keyy => $key) {
                print_r(PHP_EOL);
                print_r($keyy);
                flush();
                ob_flush();
                try {

                    $_ENV["connectionGlobal"]=null;

                    // Esperar si hay 10 procesos corriendo
                    while (count($activeProcesses) >= $maxConcurrentProcesses) {
                        foreach ($activeProcesses as $key => $pid) {
                            $res = pcntl_waitpid($pid, $status, WNOHANG);
                            if ($res > 0) {
                                unset($activeProcesses[$key]); // Remover procesos terminados
                            }
                        }

                        usleep(10000); // Pequeña pausa (0.1s) para no consumir CPU en el loop
                    }

                    // Crear un nuevo proceso hijo
                    $pid = pcntl_fork();
                    if ($pid == -1) {
                    } elseif ($pid == 0) {
                        // Código que ejecuta cada proceso hijo

                        if (strpos($key, 'AgregarValorJackpot.php') !== false) {
                            $keyvalue = explode('AgregarValorJackpot.php', $key)[1];
                            $keyvalue = ltrim($keyvalue);
                            $keyvalue = explode(' ', $keyvalue);


                            $arg1 = $keyvalue[0]; //Tipo de transaccion (CASINO, LIVECASINO, VIRTUALES, SPORTBOOK)
                            $arg2 = $keyvalue[1]; //ID Transaccion (transjuego_log.transjuegolog_id o it_ticket_enc.ticket_id)

                            $logID = createLog('AgregarValorJackpot', '', $arg1, $arg2, '0', json_encode($argv), '', 'INIT');

                            //Definiendo vertical por la cual sumará la apuesta al Jackpot
                            $vertical = match ($arg1) {
                                'CASINO' => 'CASINO',
                                'LIVECASINO' => 'LIVECASINO',
                                'VIRTUALES' => 'VIRTUAL',
                                'VIRTUAL' => 'VIRTUAL',
                                'SPORTBOOK' => 'SPORTBOOK',
                                default => null
                            };


                            $JackpoInterno = new JackpotInterno();
                            $JackpoInterno->intentarAcreditarApuesta($vertical, $arg2);
                            updateLog($logID, 'OK');


                        }

                        if (strpos($key, 'VerificarRollower.php') !== false) {
                            $keyvalue = explode('VerificarRollower.php', $key)[1];
                            $keyvalue = ltrim($keyvalue);
                            $keyvalue = explode(' ', $keyvalue);

                            $arg1 = $keyvalue[0];
                            $arg2 = $keyvalue[1];
                            $arg3 = $keyvalue[2];
                            $arg4 = $keyvalue[3];


                            $detalles2 = array(
                                "JuegosCasino" => array(array(
                                    "Id" => 2
                                )

                                ),
                                "ValorApuesta" => 0
                            );
                            $logID = createLog('VerificarRollower', '', $arg1, $arg2 != '' ? $arg2 : $arg4, '0', json_encode($argv), '', 'INIT');

                            $BonoInterno = new BonoInterno();
                            $respuesta = $BonoInterno->verificarBonoRollower($arg3, $detalles2, $arg1, $arg2, $arg4);
                            updateLog($logID, 'OK');

                        }

                        if (strpos($key, 'ActivacionRuletaCasino.php') !== false) {
                            //exec("php -f /home/home2/backendprodfinal/api/src/imports/Slack/message.php '" . 'ActivacionRuletaCasino' . "' '#dev2' > /dev/null & ");
                            $keyvalue = explode('ActivacionRuletaCasino.php', $key)[1];
                            $keyvalue = ltrim($keyvalue);
                            $keyvalue = explode(' ', $keyvalue);


                            $arg1 = $keyvalue[0]; //usuario_mandante.pais_id
                            $arg2 = $keyvalue[1]; //usuario_mandante.usumandante_id
                            $arg3 = $keyvalue[2]; //Amount
                            $arg4 = $keyvalue[3]; //Tipo = 2
                            $arg5 = $keyvalue[4]; //$Categoria->categoriaId
                            $arg6 = $keyvalue[5]; //producto.subproveedor_id
                            $arg7 = $keyvalue[6]; //producto_mandante.prodmandante_id

                            $RuletaInterno = new RuletaInterno();
                            $Response = $RuletaInterno->agregarRuleta($arg1, $arg2, $arg3, $arg4, $arg5, $arg6, $arg7);

                        }


                        if (strpos($key, 'ActivacionRuletaSportBook.php') !== false) {
                            //exec("php -f /home/home2/backendprodfinal/api/src/imports/Slack/message.php '" . 'ActivacionRuletaSportBookMenssage' . "' '#dev2' > /dev/null & ");

                            $keyvalue = explode('ActivacionRuletaSportBook.php', $key)[1];
                            //exec("php -f /home/home2/backendprodfinal/api/src/imports/Slack/message.php '" . 'ActivacionRuletaSportBookMenssage2' . "' '#dev2' > /dev/null & ");

                            $keyvalue = ltrim($keyvalue);
                            $keyvalue = explode(' ', $keyvalue);


                            $arg1 = $keyvalue[0]; //usuario.pais_id
                            $arg2 = $keyvalue[1]; //it_ticket_enc.usuario_id
                            $arg3 = $keyvalue[2]; //Amount
                            $arg4 = $keyvalue[3]; //Tipo = 1
                            $arg5 = $keyvalue[4]; //it_ticket_enc.ticket_id

                            //exec("php -f /home/home2/backendprodfinal/api/src/imports/Slack/message.php '" . $arg1 . "' '#dev2' > /dev/null & ");
                            //exec("php -f /home/home2/backendprodfinal/api/src/imports/Slack/message.php '" . $arg2 . "' '#dev2' > /dev/null & ");
                            //exec("php -f /home/home2/backendprodfinal/api/src/imports/Slack/message.php '" . $arg3 . "' '#dev2' > /dev/null & ");
                            //exec("php -f /home/home2/backendprodfinal/api/src/imports/Slack/message.php '" . $arg4 . "' '#dev2' > /dev/null & ");
                            //exec("php -f /home/home2/backendprodfinal/api/src/imports/Slack/message.php '" . $arg5 . "' '#dev2' > /dev/null & ");


                            $RuletaInterno = new RuletaInterno();
                            $Response = $RuletaInterno->agregarRuleta($arg1, $arg2, $arg3, $arg4, "", "", "", $arg5);

                        }


                        exit(0);
                    } else {
                        $activeProcesses[] = $pid; // Registrar proceso en ejecución
                    }


                } catch (Exception $e) {
                    syslog(LOG_WARNING,'ERRORcronRedisCRM '.$e->getMessage());
                }

            }

            $redis->del($keys);

        }
    }

// Definir el patrón
    if ($pattern == 'AGREGARCRM*') {
        $activeProcesses = array();
        // Control de procesos simultáneos
        $maxConcurrentProcesses = 20; // Máximo de 200 procesos a la vez

        try {


            // Obtener todas las claves que coinciden con el patrón

            $iterator = null;
            $count = 10000; // Cantidad sugerida por iteración (no es exacta)
            $limit = 10000;
            $total = 0;

            while ($iterator !== 0 && $total < $limit) {
                $keys = $redis->scan($iterator, $pattern, $count);
                if ($keys === false) break;
                $total += oldCount($keys);


                foreach ($keys as $key) {


                    // Esperar si hay 10 procesos corriendo
                    while (count($activeProcesses) >= $maxConcurrentProcesses) {
                        foreach ($activeProcesses as $key => $pid) {
                            $res = pcntl_waitpid($pid, $status, WNOHANG);
                            if ($res > 0) {
                                unset($activeProcesses[$key]); // Remover procesos terminados
                            }
                        }
                        usleep(10000); // Pequeña pausa (0.1s) para no consumir CPU en el loop
                    }

                    // Crear un nuevo proceso hijo
                    $pid = pcntl_fork();
                    if ($pid == -1) {
                    } elseif ($pid == 0) {
                        // Código que ejecuta cada proceso hijo

                        print_r($key);
                        // Obtener el valor de la clave
                        //$value = $redis->get($key);

                        //$value = json_decode($value, true);

                        if (strpos($key, 'AGREGARCRM') !== false) {
                            try {
                                $argv = explode('+', $key);
                                print_r($argv);
                                //$argv = $value;

                                $UsuarioId = str_replace('UID', '', $argv[1]); //$Usuario->usuarioId 20828
                                $Abreviado = $argv[2];
                                $IdMovimiento = $argv[3]; //Id de movimiento
                                $Server = $argv[4]; //Id de movimiento
                                $Ismobile = $argv[5]; //Id de movimiento
                                $Clasificador = new Clasificador("", $Abreviado);

                                $Crm = new Crm();
                                $Response = $Crm->CrmMovements($UsuarioId, $Clasificador, $IdMovimiento, $Server, $Ismobile);

                            } catch (Exception $e) {
                            }
                        }


                        // Eliminar la clave
                        $redis->del($key);
                        exit();
                    } else {
                        $activeProcesses[] = $pid; // Registrar proceso en ejecución

                    }
                    print_r('entro2');

                }
            }
        } catch (Exception $e) {
        }
    }

// Cerrar la conexión
    $redis->close();
} catch (Exception $e) {
    if ($redis != null && $redis->isConnected()) {
        // Cerrar la conexión
        $redis->close();

    }
}
print_r('OK');
unlink($filename);
