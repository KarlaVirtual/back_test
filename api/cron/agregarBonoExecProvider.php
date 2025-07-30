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
//$logID = createLog('AgregarBonoBackground', $UsuarioId, $CampaignID, $BonoId, '0', json_encode($argv), '', 'INIT');
