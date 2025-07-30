<?php


// Crear una instancia del cliente Redis
use Backend\dto\BonoDetalle;
use Backend\dto\BonoInterno;
use Backend\dto\Proveedor;
use Backend\dto\UsuarioBono;
use Backend\integrations\crm\Optimove;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\UsuarioBonoMySqlDAO;
use Backend\utils\RedisConnectionTrait;


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

$sleep = $argv[5];

if($sleep !='' && $sleep >0){
    // usleep($sleep); // 30,000 microsegundos = 60ms
}

function createLog($tipo,$usuario_id,$valor_id1,$valor_id2,$valor_id3,$valor1,$valor2,$estado){
    $BonoInterno = new BonoInterno();
    $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();
    $transaction = $BonoInternoMySqlDAO->getTransaction();

    if(strpos($valor_id1,'==') !== false){
        $valor_id1 = base64_decode($valor_id2);
    }

    if(strpos($valor_id2,'==') !== false){
        $valor_id2 = base64_decode($valor_id2);
    }
    $sql = "
INSERT INTO casino.log_cron (tipo, usuario_id, valor_id1, valor_id2, valor_id3, valor1, valor2, fecha_crea, fecha_modif,
                             estado)
VALUES ('$tipo', '$usuario_id', '$valor_id1', '$valor_id2', '$valor_id3', '$valor1', '$valor2', DEFAULT, DEFAULT, '$estado');

";
    $resultsql = $BonoInterno->execUpdate($transaction, $sql, 1);
    $transaction->commit();
    return $resultsql;
}
function updateLog($logcron_id,$estado,$valor1='',$valor2=''){
    $BonoInterno = new BonoInterno();
    $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();
    $transaction = $BonoInternoMySqlDAO->getTransaction();

    $sql = "
UPDATE log_cron SET estado='$estado',valor1='".str_replace("'",'"',$valor1)."',valor2='".str_replace("'",'"',$valor2)."' WHERE logcron_id=$logcron_id;
";
    $resultsql = $BonoInterno->execUpdate($transaction, $sql, 1);
    $transaction->commit();
    return $resultsql;
}


$UserId = $argv[1];
$BonoId = $argv[2];
$CampaignID = $argv[3];
$logID = $argv[4];
$UsuarioId=$UserId;
$logID = createLog('AgregarBonoBackground', $UsuarioId, $CampaignID, $BonoId, '0', json_encode($argv), '', 'INIT');

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
        updateLog($logID,'OK','','');

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
        updateLog($logID,'OK','','');

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
        updateLog($logID,'OK','','');

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
        updateLog($logID,'OK','','');

    } else {

        try {
            $BonoDetalleROUNDSFREE = new BonoDetalle('', $BonoId, 'REPETIRBONO');
            $repetirBono = $BonoDetalleROUNDSFREE->valor;
        } catch (Exception $e) {
            $repetirBono = 0;
        }
        $continue = true;
        $TypeError='ERRORGEN';
        if (!$repetirBono) {
            try {
                $VerifUsuarioBono = new UsuarioBono('', $Usuario->usuarioId, $BonoId);
                $continue = false;
                $TypeError='ERRORREPETIRBONO';
            } catch (Exception $e) {
            }
        }
        if ($continue) {

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


                try {
                    $BonoDetalleROUNDSFREE = new BonoDetalle('', $BonoId, 'REPETIRBONO');
                    $repetirBono = $BonoDetalleROUNDSFREE->valor;
                } catch (Exception $e) {
                    $repetirBono = 0;
                }
                if (!$repetirBono) {
                    try {
                        $VerifUsuarioBono = new UsuarioBono('', $UsuarioId, $BonoId);
                        updateLog($logID,'ERRORREPETIRBONO','','');
                        exit();
                    } catch (Exception $e) {

                    }
                }

                print_r('CAMPA OPTIMOVE 4');

                $Subproveedor = new Subproveedor($idSub);
                $Proveedor = new Proveedor($Subproveedor->proveedorId);
                print_r($Subproveedor);
                syslog(LOG_WARNING, "CAMPA OPTIMOVE 5: " . ($Subproveedor->abreviado));
                if($Subproveedor->abreviado =='PLAYTECH' || $Subproveedor->abreviado =='PRAGMATIC' || $Subproveedor->abreviado =='PLATIPUS'){
                    $Prefix = 'ADMIN2';
                    if ($Usuario->mandante == '0' && $Usuario->paisId == 173) {
                        $Prefix = 'ADMIN2';
                    } elseif ($Usuario->mandante != '8') {
                        $Prefix = 'ADMIN3';
                    }

                    $redisParam = ['ex' => 18000];

                    $UserId = $argv[1];
                    $BonoId = $argv[2];
                    $CampaignID = $argv[3];
                    $UsuarioId=$UserId;

                    $redisPrefix = $Prefix . $Subproveedor->abreviado."F3BACK+AgregarBonoBackground+UID" . $UsuarioId . '+' . $BonoId . '+' . $CampaignID. '+' . $logID;

                    $redis = RedisConnectionTrait::getRedisInstance(true);

                    if ($redis != null) {

                        $redis->set($redisPrefix, json_encode($argv), $redisParam);
                    }
                    exit();

                }
                if (in_array($Subproveedor->subproveedorId, $CONDSUBPROVIDER)) {

                    $responseBonoGlobal = $BonoInterno->bonoGlobal($Proveedor, $BonoId, $CONDGAME, $Usuario->mandante, $Usuario->usuarioId, $Transaction, 0, false, 0, $BonoInterno->nombre, $Prefix, $MaxplayersCount);
                    if ($responseBonoGlobal["status"] != "ERROR") {

                        $Transaction->commit();
                        updateLog($logID,'OK','',json_encode($responseBonoGlobal));

                    }else{

                        updateLog($logID,'ERRORFINAL','',json_encode($responseBonoGlobal));
                    }
                }else{
                    updateLog($logID,'ERROR','','CONDin_array($Subproveedor->subproveedorId, $CONDSUBPROVIDER)');

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
                updateLog($logID,'OK','','DIRECT');
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
                updateLog($logID,'OK','','DIRECT');

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

                $IsAltenar=false;
                try {
                    $BONUSPLANIDALTENAR = new BonoDetalle('', $BonoId, 'BONUSPLANIDALTENAR');
                    $BONUSPLANIDALTENARVALOR = $BONUSPLANIDALTENAR->valor;
                    if($BONUSPLANIDALTENARVALOR >0){
                        $IsAltenar=true;

                    }
                } catch (Exception $e) {

                }
                if($IsAltenar) {


                    $Prefix = 'ADMIN2';
                    if ($Usuario->mandante == '0' && $Usuario->paisId == 173) {
                        $Prefix = 'ADMIN2';
                    } elseif ($Usuario->mandante != '8') {
                        $Prefix = 'ADMIN3';
                    }

                    $redisParam = ['ex' => 18000];

                    $UserId = $argv[1];
                    $BonoId = $argv[2];
                    $CampaignID = $argv[3];
                    $UsuarioId = $UserId;

                    $redisPrefix = $Prefix . 'ALTENAR' . "F3BACK+AgregarBonoBackground+UID" . $UsuarioId . '+' . $BonoId . '+' . $CampaignID . '+' . $logID;

                    $redis = RedisConnectionTrait::getRedisInstance(true);

                    if ($redis != null) {

                        $redis->set($redisPrefix, json_encode($argv), $redisParam);
                    }
                    exit();
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
                    updateLog($logID,'OK','','DIRECT2');
                    updateLog($logID,'OK','',json_encode($respuesta));
                }else{

                    updateLog($logID,'ERROR','','DIRECT');
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

                updateLog($logID,'OK','','DIRECT');


            }
        }else{

            updateLog($logID,$TypeError,'','');
        }
    }
} catch (Exception $e) {
    print_r($e);

}