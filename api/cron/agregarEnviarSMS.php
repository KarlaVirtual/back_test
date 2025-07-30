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

$CampaignID = $argv[1];
$brand = $argv[2];
$ContryId = $argv[3];
$EventTypeID = $argv[4];
$Channel = $argv[5];
$TemplateId = $argv[6];
$UserId = $argv[7];

$UsuarioId = $argv[1];
$TemplateId = $argv[2];

try {

    $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();

    $Usuario = new Usuario($UsuarioId);
    $Registro = new Registro('', $UsuarioId);

    $UsuarioMensajecampana = new UsuarioMensajecampana($TemplateId);
    $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
    $Contenido = $UsuarioMensajecampana->body;


    $ConfigurationEnvironment = new ConfigurationEnvironment();

    $UsuarioMensajes = array();
    $varArray = array();
    $varArray['usumandanteId'] = $UsuarioMandante->usumandanteId;
    $varArray['tophone'] = $Registro->celular;
    $varArray['link'] = '';

    array_push($UsuarioMensajes, $varArray);
    $envio = $ConfigurationEnvironment->EnviarMensajeTexto($Contenido, '', $Registro->celular, 0, $UsuarioMandante, $UsuarioMensajecampana->usumencampanaId);


} catch (Exception $e) {

}