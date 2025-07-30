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
use Backend\dto\ConfigurationEnvironment;
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
use Backend\dto\LealtadInterna;
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
use Backend\dto\UsuarioLealtad;
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
use Backend\mysql\UsuarioLealtadMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioNotasMySqlDAO;
use Backend\mysql\UsuarioPerfilMySqlDAO;
use Backend\mysql\UsuarioPremiomaxMySqlDAO;
use Backend\mysql\UsuarioPublicidadMySqlDAO;
use Backend\mysql\UsuarioRecargaMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\mysql\UsucomisionResumenMySqlDAO;
use Backend\websocket\WebsocketUsuario;

use function React\Promise\all;

// error_reporting(E_ALL);
// ini_set("display_errors","ON");


/**
 * Este script procesa la entrega de premios a usuarios en función de parámetros de entrada.
 *
 * @param object $params Objeto que contiene los siguientes valores:
 * @param string $params->shop Identificador del comercio.
 * @param string $params->token Token de autenticación.
 * @param string $params->withdrawId Nota asociada al retiro.
 * @param string $params->password Contraseña del usuario.
 * @param string $params->country País del usuario.
 * @param string $params->transactionId Identificador de la transacción.
 * @param string $params->prizeId Identificador del premio.
 * 
 *
 * @return array $response Arreglo que contiene:
 *  - error (int): Código de error (0 si no hay errores).
 *  - code (int|string): Código adicional (0 si no hay errores).
 *  - message (string): Mensaje de éxito o error.
 */

/* asigna parámetros de entrada a variables para procesar una transacción. */
$shop = $params->shop;
$token = $params->token;
$nota = $params->withdrawId;
$clave = $params->password;
$pais = $params->country;

//usulealtadId es el campo que se encrypta
$transactionId = $params->transactionId;


/* verifica si $shop y $token están vacíos y lanza una excepción. */
if ($shop == "") {
    throw new Exception("Field: Key", "50001");

}
if ($token == "") {
    throw new Exception("Field: Key", "50001");

}


/* verifica si el ID de transacción está vacío y lanza una excepción. */
if ($transactionId == "") {
    throw new Exception("Field: transactionId", "50001");

}


$Usuario = new Usuario($shop);


/* verifica condiciones antes de permitir el pago de un premio. */
try {
    $Clasificador = new Clasificador("", "PAYPRIZEBETSHOP");

    $MandanteDetalle = new MandanteDetalle("", $Usuario->mandante, $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');

    if ($MandanteDetalle->valor == "A") {
        throw new Exception("No es posible realizar pago de premio actualmente", "300006");
    }

} catch (Exception $e) {
    /* maneja excepciones, re-lanzando solo si el código no es 34 o 41. */

    if ($e->getCode() != 34 && $e->getCode() != 41) {
        throw $e;
    }
}


/* Se inicializan variables para manejar filas y establecer reglas en un proceso. */
$MaxRows = 1;
$OrderedItem = 1;
$SkeepRows = 0;


$rules = [];

/* array_push($rules, array("field" => "usuario.usuario_id", "data" => "$shop", "op" => "eq"));
array_push($rules, array("field" => "usuario_log.tipo", "data" => "CODIGOAGENT", "op" => "eq"));
array_push($rules, array("field" => "usuario_log.estado", "data" => "P,A", "op" => "in"));
array_push($rules, array("field" => "usuario_log.valor_despues", "data" => $token, "op" => "eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND");

$json = json_encode($filtro);

setlocale(LC_ALL, 'czech');


$select = " usuario_log.* ";


$UsuarioLog = new UsuarioLog();
$data = $UsuarioLog->getUsuarioLogsCustom($select, "usuario_log.usuariolog_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping);

$data = json_decode($data);*/


/* establece reglas de filtrado para una consulta a base de datos. */
array_push($rules, array("field" => "usuario.usuario_id", "data" => "$shop", "op" => "eq"));
array_push($rules, array("field" => "usuario_token_interno.tipo", "data" => "0", "op" => "eq"));
array_push($rules, array("field" => "usuario_token_interno.estado", "data" => "'A'", "op" => "in"));
array_push($rules, array("field" => "usuario_token_interno.valor", "data" => $token, "op" => "eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND");


/* convierte un filtro a JSON y establece la localización en checo. */
$json = json_encode($filtro);

setlocale(LC_ALL, 'czech');


$select = " usuario_token_interno.* ";


/* Se crea un token interno, se obtiene y decodifica datos en formato JSON. */
$UsuarioTokenInterno = new UsuarioTokenInterno();


$data = $UsuarioTokenInterno->getUsuarioTokenInternosCustom($select, "usuario_token_interno.usutokeninterno_id", "asc", $SkeepRows, $MaxRows, $json, true);

$data = json_decode($data);


/* inicializa un arreglo de respuesta con valores de error y código en cero. */
$response["error"] = 0;
$response["code"] = 0;


if (count($data->data) > 0) {


    /* inicializa objetos relacionados con usuarios y sus estados de lealtad. */
    $Id = $params->prizeId;

    $UsuariosLealtad = new UsuarioLealtad($Id);

    $LealtadInterna = new LealtadInterna($UsuariosLealtad->getLealtadId());

    $estadoObtenido = $UsuariosLealtad->getEstado();


    /* Actualiza el estado de un usuario a "D" si condiciones específicas son cumplidas. */
    if ($estadoObtenido == "R" && $LealtadInterna->tipoPremio == 0) {
        $UsuariosLealtad->setEstado("D");
        $UsuariosLealtad->fechaModif = date("Y-m-d H:i:s");
        $usuarioLealtadMysqlDao = new UsuarioLealtadMySqlDAO();
        $Transaction = $usuarioLealtadMysqlDao->getTransaction();
        $usuarioLealtadMysqlDao->update($UsuariosLealtad);
        $usuarioLealtadMysqlDao->getTransaction()->commit();


        $response["error"] = 0;
        $response["code"] = 0;
        $response["message"] = 'success';

    } else {
        /* Código que maneja un error al intentar entregar un premio ya otorgado. */

        $response["error"] = 120;
        $response["code"] = '';
        $response["message"] = 'El premio ya fue entregado';

    }

} else {
    /* Lanza una excepción por datos de login incorrectos con código "50003". */

    throw new Exception("Datos de login incorrectos", "50003");

}

?>