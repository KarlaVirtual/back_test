<?php

use Backend\dto\ApiTransaction;
use Backend\dto\Bono;
use Backend\dto\BonoDetalle;
use Backend\dto\BonoInterno;
use Backend\dto\BonoLog;
use Backend\dto\Categoria;
use Backend\dto\CategoriaProducto;
use Backend\dto\Clasificador;
use Backend\dto\CompetenciaPuntos;
use Backend\dto\Concesionario;
use Backend\dto\Consecutivo;
use Backend\dto\CuentaCobro;
use Backend\dto\CupoLog;
use Backend\dto\Descarga;
use Backend\dto\DocumentoUsuario;
use Backend\dto\FlujoCaja;
use Backend\dto\Flujocajafact;
use Backend\dto\IntApuesta;
use Backend\dto\IntApuestaDetalle;
use Backend\dto\IntCompetencia;
use Backend\dto\IntDeporte;
use Backend\dto\IntEquipo;
use Backend\dto\IntEvento;
use Backend\dto\IntEventoApuesta;
use Backend\dto\IntEventoApuestaDetalle;
use Backend\dto\IntEventoDetalle;
use Backend\dto\IntRegion;
use Backend\dto\ItTicketEnc;
use Backend\dto\LenguajeMandante;
use Backend\dto\Mandante;
use Backend\dto\MandanteDetalle;
use Backend\dto\Pais;
use Backend\dto\Perfil;
use Backend\dto\PerfilSubmenu;
use Backend\dto\ProdMandanteTipo;
use Backend\dto\Producto;
use Backend\dto\ProductoComision;
use Backend\dto\ProductoInterno;
use Backend\dto\PromocionalLog;
use Backend\dto\Proveedor;
use Backend\dto\ProveedorMandante;
use Backend\dto\PuntoVenta;
use Backend\dto\ProductoMandante;
use Backend\dto\Registro;
use Backend\dto\SaldoUsuonlineAjuste;
use Backend\dto\Submenu;
use Backend\dto\TransaccionApi;
use Backend\dto\TransaccionApiUsuario;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransapiusuarioLog;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransprodLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioAlerta;
use Backend\dto\UsuarioBanco;
use Backend\dto\UsuarioBloqueado;
use Backend\dto\UsuarioBono;
use Backend\dto\UsuarioConfig;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioLog;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioMensaje;
use Backend\dto\UsuarioNotas;
use Backend\dto\UsuarioPerfil;
use Backend\dto\UsuarioPremiomax;
use Backend\dto\UsuarioPublicidad;
use Backend\dto\UsuarioRecarga;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioTokenInterno;
use Backend\dto\UsucomisionResumen;
use Backend\dto\UsumarketingResumen;
use Backend\imports\Google\GoogleAuthenticator;
use Backend\integrations\mensajeria\Okroute;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\mysql\CategoriaMySqlDAO;
use Backend\mysql\CompetenciaPuntosMySqlDAO;
use Backend\mysql\ConcesionarioMySqlDAO;
use Backend\mysql\ConsecutivoMySqlDAO;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\CupoLogMySqlDAO;
use Backend\mysql\DocumentoUsuarioMySqlDAO;
use Backend\mysql\FlujoCajaMySqlDAO;
use Backend\mysql\IntApuestaDetalleMySqlDAO;
use Backend\mysql\IntApuestaMySqlDAO;
use Backend\mysql\IntCompetenciaMySqlDAO;
use Backend\mysql\IntDeporteMySqlDAO;
use Backend\mysql\IntEventoApuestaDetalleMySqlDAO;
use Backend\mysql\IntEventoApuestaMySqlDAO;
use Backend\mysql\IntEventoDetalleMySqlDAO;
use Backend\mysql\IntEventoMySqlDAO;
use Backend\mysql\IntRegionMySqlDAO;
use Backend\mysql\LenguajeMandanteMySqlDAO;
use Backend\mysql\MandanteDetalleMySqlDAO;
use Backend\mysql\MandanteMySqlDAO;
use Backend\mysql\PerfilSubmenuMySqlDAO;
use Backend\mysql\ProdMandanteTipoMySqlDAO;
use Backend\mysql\ProductoComisionMySqlDAO;
use Backend\mysql\ProveedorMandanteMySqlDAO;
use Backend\mysql\ProveedorMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\RegistroMySqlDAO;
use Backend\mysql\SaldoUsuonlineAjusteMySqlDAO;
use Backend\mysql\TransaccionApiUsuarioMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransapiusuarioLogMySqlDAO;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\mysql\UsuarioAlertaMySqlDAO;
use Backend\mysql\UsuarioBancoMySqlDAO;
use Backend\mysql\UsuarioBloqueadoMySqlDAO;
use Backend\mysql\UsuarioBonoMySqlDAO;
use Backend\mysql\UsuarioConfigMySqlDAO;
use Backend\mysql\UsuarioConfiguracionMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\mysql\UsuarioMandanteMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\ProductoMySqlDAO;
use Backend\mysql\ProductoMandanteMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioNotasMySqlDAO;
use Backend\mysql\UsuarioPerfilMySqlDAO;
use Backend\mysql\UsuarioPremiomaxMySqlDAO;
use Backend\mysql\UsuarioPublicidadMySqlDAO;
use Backend\mysql\UsuarioRecargaMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\mysql\UsucomisionResumenMySqlDAO;
use Backend\websocket\WebsocketUsuario;


/**
 * Este script valida tokens y actualiza direcciones IP para usuarios en el contexto de Betshop.
 * 
 * @param string $_GET['info'] Información del usuario, utilizada para generar el token.
 * @param string $_SERVER['HTTP_X_FORWARDED_FOR'] Dirección IP del cliente (si está disponible).
 * @param string $_SERVER['REMOTE_ADDR'] Dirección IP remota del cliente.
 * 
 * @return array $response Respuesta que incluye:
 *  - int $error Código de error (0 si no hay errores).
 *  - int $code Código adicional (0 si no hay errores).
 * 
 * @throws Exception Si alguno de los siguientes errores ocurre:
 *  - "Datos de login incorrectos" (código 86): Cuando la longitud de la IP es mayor o igual a 20 caracteres o no se encuentran datos válidos.
 */

/* registra la URI de la solicitud en un log. */
$MaxRows = 1;
$OrderedItem = 1;
$SkeepRows = 0;

$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . $_SERVER['REQUEST_URI'];

/* registra información y la guarda en un archivo log diario. */
$log = $log . "info=" . $_GET['info'];
$log = $log . trim(file_get_contents('php://input'));
//Save string to log, use FILE_APPEND to append.

fwriteCustom('log_' . date("Y-m-d") . '.log', $log);


$ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];

/* obtiene la primera IP de una cadena separada por comas y la asigna a una variable. */
$ip = explode(",", $ip)[0];

// echo "Remote IP:$ip-$URI";


//Se hace explode para tomar la primera IP
$dir_ip = $ip;


/* valida la longitud de una IP y captura información de usuario para depuración. */
if (strlen($ip) >= 20) {
    throw new Exception("Datos de login incorrectos", "86");
}
//Se captura la URL para de allí extraer el numero del punto de venta
$usuario = $_GET['info'];

//Depurarar caracteres
$dir_ip = DepurarCaracteres($dir_ip);

/* prepara un token y establece reglas para filtrar usuarios. */
$token = DepurarCaracteres($usuario);
$token = (explode("info=", $URI)[1]);

$rules = array();

// array_push($rules, array("field" => "usuario.usuario_id", "data" => "$shop", "op" => "eq"));
array_push($rules, array("field" => "usuario_token_interno.tipo", "data" => "2", "op" => "eq"));

/* Se construye un filtro en formato JSON con reglas para una consulta. */
array_push($rules, array("field" => "usuario_token_interno.estado", "data" => "'A'", "op" => "in"));
array_push($rules, array("field" => "usuario_token_interno.valor", "data" => $token, "op" => "eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND");

$json = json_encode($filtro);


/* Configura la localización y obtiene datos de usuarios en formato JSON. */
setlocale(LC_ALL, 'czech');


$UsuarioTokenInterno = new UsuarioTokenInterno();
$data = $UsuarioTokenInterno->getUsuarioTokenInternosCustom("usuario_token_interno.*,usuario.usuario_id", "usuario_token_interno.usutokeninterno_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping);

$data = json_decode($data);


/* Se inicializan valores de error y código en una respuesta. */
$response["error"] = 0;
$response["code"] = 0;


if (oldCount($data->data) > 0) {


    /* Se crea un usuario, obtiene una transacción y se inicializa el registro de usuario. */
    $Usuario = new Usuario($data->data[0]->{'usuario.usuario_id'});

    $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
    $Transaction = $UsuarioLogMySqlDAO->getTransaction();

    $UsuarioLog = new UsuarioLog();

    /* Código que establece propiedades de un objeto UsuarioLog relacionado a un usuario. */
    $UsuarioLog->setUsuarioId($Usuario->usuarioId);
    $UsuarioLog->setUsuarioIp('');

    $UsuarioLog->setUsuariosolicitaId(0);
    $UsuarioLog->setUsuariosolicitaIp($ip);

    $UsuarioLog->setTipo("DIRIPBETSHOP");

    /* Registro de cambios en el estado y dirección IP del usuario en la base de datos. */
    $UsuarioLog->setEstado("A");
    $UsuarioLog->setValorAntes($Usuario->dirIp);
    $UsuarioLog->setValorDespues($dir_ip);
    $UsuarioLog->setUsucreaId(0);
    $UsuarioLog->setUsumodifId(0);


    $UsuarioLogMySqlDAO->insert($UsuarioLog);


    /* Actualiza la IP del usuario en la base de datos y confirma la transacción. */
    $Usuario->usuarioIp = $dir_ip;

    $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);
    $UsuarioMySqlDAO->update($Usuario);

    $Transaction->commit();
} else {
    /* lanza una excepción al recibir datos de inicio de sesión incorrectos. */

    throw new Exception("Datos de login incorrectos", "86");

}


