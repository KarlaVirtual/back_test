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


$UsuarioId = $argv[1];
$TemplateID = $argv[2];
$ChannelID = $argv[3];

try {
    if($UsuarioId =='' || $UsuarioId =='0'){
        exit();
    }
    $Usuario = new Usuario($UsuarioId);

    $UsuarioMensajecampana = new UsuarioMensajecampana($TemplateID);
    $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
    $Registro = new Registro("", $Usuario->usuarioId);
    $Pais = new Pais($Usuario->paisId);
    $Mandante = new Mandante($UsuarioMandante->mandante);
    $Contenido = $UsuarioMensajecampana->body;

    $Contenido = str_replace('#UsuarioId#', $Usuario->usuarioId, $Contenido);
    $Contenido = str_replace('#Nombre#', $Registro->nombre1, $Contenido);
    $Contenido = str_replace('#Apellido#', $Registro->apellido1, $Contenido);
    $Contenido = str_replace('#Documento#', $Registro->cedula, $Contenido);
    $Contenido = str_replace('#Telefono#', $Registro->celular, $Contenido);
    $Contenido = str_replace('#Email#', $Registro->email, $Contenido);
    $Contenido = str_replace('#Marca#', $Mandante->nombre, $Contenido);
    $Contenido = str_replace('#Pais#', $Pais->paisNom, $Contenido);
    $Contenido = str_replace('#Moneda#', $UsuarioMandante->moneda, $Contenido);

    $UsuarioMensaje = new UsuarioMensaje();
    $UsuarioMensaje->usufromId = 0;
    $UsuarioMensaje->usutoId = $UsuarioMandante->usumandanteId;
    $UsuarioMensaje->isRead = 0;
    $UsuarioMensaje->body = $Contenido;
    $UsuarioMensaje->msubject = $UsuarioMensajecampana->nombre;
    $UsuarioMensaje->parentId = 0;
    $UsuarioMensaje->proveedorId = $UsuarioMandante->mandante;
    $UsuarioMensaje->tipo = "MENSAJE";
    $UsuarioMensaje->paisId = $Usuario->paisId;
    $UsuarioMensaje->fechaExpiracion = $UsuarioMensajecampana->fechaExpiracion;
    $UsuarioMensaje->usumencampanaId = $UsuarioMensajecampana->usumencampanaId;
    syslog(LOG_WARNING, "INBOX CRM: " . (json_encode($UsuarioMensaje)));

    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
    $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
    $UsuarioMensajeMySqlDAO->getTransaction()->commit();


} catch (Exception $e) {

}