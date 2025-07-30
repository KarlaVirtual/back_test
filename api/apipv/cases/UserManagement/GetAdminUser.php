<?php

use Backend\dto\ApiTransaction;
use Backend\dto\Area;
use Backend\dto\Bono;
use Backend\dto\BonoDetalle;
use Backend\dto\BonoInterno;
use Backend\dto\BonoLog;
use Backend\dto\Cargo;
use Backend\dto\Categoria;
use Backend\dto\CategoriaProducto;
use Backend\dto\CentroCosto;
use Backend\dto\Clasificador;
use Backend\dto\CodigoPromocional;
use Backend\dto\Competencia;
use Backend\dto\CompetenciaPuntos;
use Backend\dto\Concepto;
use Backend\dto\Concesionario;
use Backend\dto\Consecutivo;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\ContactoComercial;
use Backend\dto\ContactoComercialLog;
use Backend\dto\CuentaCobro;
use Backend\dto\CuentaContable;
use Backend\dto\CupoLog;
use Backend\dto\Descarga;
use Backend\dto\DocumentoUsuario;
use Backend\dto\Egreso;
use Backend\dto\Empleado;
use Backend\dto\FlujoCaja;
use Backend\dto\Flujocajafact;
use Backend\dto\Ingreso;
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
use Backend\dto\PaisMandante;
use Backend\dto\Perfil;
use Backend\dto\PerfilSubmenu;
use Backend\dto\ProdMandanteTipo;
use Backend\dto\Producto;
use Backend\dto\ProductoComision;
use Backend\dto\ProductoInterno;
use Backend\dto\ProductoTercero;
use Backend\dto\ProductoterceroUsuario;
use Backend\dto\PromocionalLog;
use Backend\dto\Proveedor;
use Backend\dto\ProveedorMandante;
use Backend\dto\ProveedorTercero;
use Backend\dto\PuntoVenta;
use Backend\dto\ProductoMandante;
use Backend\dto\Registro;
use Backend\dto\SaldoUsuonlineAjuste;
use Backend\dto\Submenu;
use Backend\dto\TransaccionApi;
use Backend\dto\TransaccionApiMandante;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransaccionSportsbook;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransprodLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioAlerta;
use Backend\dto\UsuarioBanco;
use Backend\dto\UsuarioBloqueado;
use Backend\dto\UsuarioBono;
use Backend\dto\UsuarioCierrecaja;
use Backend\dto\UsuarioComision;
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
use Backend\dto\UsuarioRecargaResumen;
use Backend\dto\UsuarioRetiroResumen;
use Backend\dto\UsuarioSaldo;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioTokenInterno;
use Backend\dto\UsucomisionResumen;
use Backend\dto\UsumarketingResumen;
use Backend\imports\Google\GoogleAuthenticator;
use Backend\integrations\mensajeria\Okroute;
use Backend\integrations\payout\LPGSERVICES;
use Backend\mysql\AreaMySqlDAO;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\mysql\BonoLogMySqlDAO;
use Backend\mysql\CargoMySqlDAO;
use Backend\mysql\CategoriaMySqlDAO;
use Backend\mysql\CategoriaProductoMySqlDAO;
use Backend\mysql\CentroCostoMySqlDAO;
use Backend\mysql\ClasificadorMySqlDAO;
use Backend\mysql\CodigoPromocionalMySqlDAO;
use Backend\mysql\CompetenciaPuntosMySqlDAO;
use Backend\mysql\ConceptoMySqlDAO;
use Backend\mysql\ConcesionarioMySqlDAO;
use Backend\mysql\ConsecutivoMySqlDAO;
use Backend\mysql\ContactoComercialLogMySqlDAO;
use Backend\mysql\ContactoComercialMySqlDAO;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\CuentaContableMySqlDAO;
use Backend\mysql\CupoLogMySqlDAO;
use Backend\mysql\DocumentoUsuarioMySqlDAO;
use Backend\mysql\EgresoMySqlDAO;
use Backend\mysql\EmpleadoMySqlDAO;
use Backend\mysql\FlujoCajaMySqlDAO;
use Backend\mysql\IngresoMySqlDAO;
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
use Backend\mysql\ProductoTerceroMySqlDAO;
use Backend\mysql\ProductoterceroUsuarioMySqlDAO;
use Backend\mysql\ProveedorMandanteMySqlDAO;
use Backend\mysql\ProveedorMySqlDAO;
use Backend\mysql\ProveedorTerceroMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\RegistroMySqlDAO;
use Backend\mysql\SaldoUsuonlineAjusteMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\mysql\UsuarioAlertaMySqlDAO;
use Backend\mysql\UsuarioBancoMySqlDAO;
use Backend\mysql\UsuarioBloqueadoMySqlDAO;
use Backend\mysql\UsuarioBonoMySqlDAO;
use Backend\mysql\UsuarioCierrecajaMySqlDAO;
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
use Backend\mysql\UsuarioTokenInternoMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\mysql\UsucomisionResumenMySqlDAO;
use Backend\websocket\WebsocketUsuario;


/**
 * UserManagement/GetAdminUser
 *
 * Obtener Usuario administrativo
 *
 * @param no
 *
 * @return
 *{"HasError":boolean,"AlertType": string,"AlertMessage": string,"url": string,"success": string}
 *
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */

/**
 * @OA\Post(path="apipv/UserManagement/GetAdminUser", tags={"UserManagement"}, description = "",
 *
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="json",
 *             @OA\Schema(required={},
 *               @OA\Property(
 *                   property="count",
 *                   description="Número total de registros",
 *                   type="integer",
 *                   example= 469
 *               ),
 *               @OA\Property(
 *                   property="start",
 *                   description="Indice de posición de registros",
 *                   type="integer",
 *                   example= 2
 *               ),
 *               @OA\Property(
 *                   property="Id",
 *                   description="Id",
 *                   type="string",
 *                   example= ""
 *               ),
 *               @OA\Property(
 *                   property="Login",
 *                   description="Login",
 *                   type="string",
 *                   example= ""
 *               ),
 *               @OA\Property(
 *                   property="dateFrom",
 *                   description="dateFrom",
 *                   type="string",
 *                   example= ""
 *               ),
 *               @OA\Property(
 *                   property="dateTo",
 *                   description="dateTo",
 *                   type="string",
 *                   example= ""
 *               ),
 *               @OA\Property(
 *                   property="Partner",
 *                   description="Partner",
 *                   type="string",
 *                   example= ""
 *               ),
 *               @OA\Property(
 *                   property="FirstName",
 *                   description="FirstName",
 *                   type="string",
 *                   example= ""
 *               ),
 *               @OA\Property(
 *                   property="Email",
 *                   description="Email",
 *                   type="string",
 *                   example= ""
 *               ),
 *               @OA\Property(
 *                   property="City",
 *                   description="City",
 *                   type="string",
 *                   example= ""
 *               )
 *             )
 *         )
 *     ),
 *
 * @OA\Response (
 *      response="200",
 *      description="Success",
 *         @OA\MediaType(
 *             mediaType="json",
 *             @OA\Schema(
 *               @OA\Property(
 *                   property="HasError",
 *                   description="Hay error",
 *                   type="boolean",
 *                   example= false
 *               ),
 *               @OA\Property(
 *                   property="AlertType",
 *                   description="Mensaje de la API",
 *                   type="string",
 *                   example= "success"
 *               ),
 *               @OA\Property(
 *                   property="AlertMessage",
 *                   description="Mensaje con el error especifico",
 *                   type="string",
 *                   example= "0"
 *               ),
 *               @OA\Property(
 *                   property="ModelErrors",
 *                   description="",
 *                   type="Array",
 *                   example= {}
 *               ),
 *               @OA\Property(
 *                   property="Data",
 *                   description="",
 *                   type="Array",
 *                   example= {}
 *               ),
 *               @OA\Property(
 *                   property="data",
 *                   description="",
 *                   type="Array",
 *                   example= {}
 *               ),
 *               @OA\Property(
 *                   property="pos",
 *                   description="",
 *                   type="string",
 *                   example= ""
 *               ),
 *               @OA\Property(
 *                   property="total_account",
 *                   description="Total de registros",
 *                   type="integer",
 *                   example= 20
 *               ),
 *             )
 *         ),
 *         )
 * )
 */


/**
 * Obtener Usuario administrativo
 *
 * Este script permite obtener información de usuarios administrativos con base en filtros y reglas específicas.
 *
 * @param object $params Objeto JSON con los siguientes atributos:
 * @param int $params->count Número total de registros.
 * @param int $params->start Índice de posición de registros.
 * @param string $params->Id Identificador del usuario.
 * @param string $params->Login Nombre de usuario.
 * @param string $params->dateFrom Fecha inicial del rango.
 * @param string $params->dateTo Fecha final del rango.
 * @param string $params->Partner Socio asociado al usuario.
 * @param string $params->FirstName Nombre del usuario.
 * @param string $params->Email Correo electrónico del usuario.
 * @param string $params->City Ciudad del usuario.
 *
 * @return array $response Respuesta en formato JSON con los siguientes atributos:
 * - HasError (boolean): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta (e.g., "success").
 * - AlertMessage (string): Mensaje de alerta o error.
 * - ModelErrors (array): Lista de errores de validación.
 * - pos (integer): Posición inicial de los registros omitidos.
 * - total_count (integer): Total de registros encontrados.
 * - data (array): Lista de usuarios administrativos con sus atributos.
 */


/* verifica permisos de usuario para gestionar administradores en un entorno específico. */
$Usuario = new Usuario();

$params = file_get_contents('php://input');
$params = json_decode($params);
$ConfigurationEnvironment = new ConfigurationEnvironment();
$permission = $ConfigurationEnvironment->checkUserPermission('UserManagement/GetAdminUser', $_SESSION['win_perfil'], $_SESSION['usuario'], 'adminUser');

if (!$permission) throw new Exception('Permiso denegado', 100035);


/* procesa parámetros y establece variables para paginación y orden de ítems. */
$Id = $params->Id;

$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;

$MaxRows = $_REQUEST["count"];

/* asigna valores de parámetros HTTP a variables dependiendo de ciertas condiciones. */
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];


$Id = $_REQUEST["Id"];

$Login = $_REQUEST["Login"];
//$dateFrom=  $_REQUEST["dateFrom"];
//$dateTo =  $_REQUEST["dateTo"];

/* obtiene datos de entrada y establece un valor predeterminado para $SkeepRows. */
$Partner = $_REQUEST["Partner"];
$FirstName = $_REQUEST["FirstName"];
$Email = $_REQUEST["Email"];
$City = $_REQUEST["City"];

if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* Inicializa las variables $OrderedItem y $MaxRows si están vacías. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 1000;
}


/* Asigna un valor a $mandanteEspecifico según condiciones de sesión. */
$mandanteEspecifico = '';
if ($_SESSION['Global'] == "N") {
    $mandanteEspecifico = $_SESSION['mandante'];
} else {

    if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
        $mandanteEspecifico = $_SESSION["mandanteLista"];
    }

}


/* Se define un conjunto de reglas para validar condiciones de usuario y perfil. */
$rules = [];
array_push($rules, array("field" => "perfil.tipo", "data" => "A", "op" => "eq"));
array_push($rules, array("field" => "usuario.eliminado", "data" => "N", "op" => "eq"));

if ($Login) {
    array_push($rules, array("field" => "usuario.login", "data" => $Login, "op" => "eq"));
}


/* Agrega reglas de validación para nombre y mandante en un array. */
if ($FirstName) {
    array_push($rules, array("field" => "usuario.nombre", "data" => $FirstName, "op" => "eq"));
}


if ($mandanteEspecifico != '') {
    array_push($rules, array("field" => "usuario.mandante", "data" => $mandanteEspecifico, "op" => "in"));

}


/* Agrega reglas a un array si se cumplen ciertas condiciones de identificador y sesión. */
if ($Id != "") {
    array_push($rules, array("field" => "usuario.usuario_id", "data" => "$Id", "op" => "eq"));
}

if ($_SESSION['PaisCond'] == "S") {
    array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
}
// Si el usuario esta condicionado por el mandante y no es de Global

/* agrega reglas a un arreglo según la sesión del usuario. */
if ($_SESSION['Global'] == "N") {
    array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
} else {

    if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
        array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
    }

}


/* filtra y obtiene usuarios, convirtiendo datos a formato JSON. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);


$usuarios = $Usuario->getUsuariosCustom("  DISTINCT (usuario.usuario_id),usuario_perfil.mandante_lista,usuario.fecha_ult,usuario.moneda,usuario.idioma,usuario.dir_ip,usuario.login,usuario.estado,usuario.estado_esp,usuario.pais_id,usuario.fecha_crea,usuario.nombre,usuario.fecha_ult ", "usuario.usuario_id", "asc", $SkeepRows, $MaxRows, $json, true);

$usuarios = json_decode($usuarios);


/* Inicializa un array vacío llamado "usuariosFinal" para almacenar datos de usuarios. */
$usuariosFinal = [];

foreach ($usuarios->data as $key => $value) {


    /* verifica el estado del usuario y determina si está bloqueado. */
    $Islocked = false;

    if ($value->{"usuario.estado"} == "I") {
        $Islocked = true;
    }

    $array = [];


    /* asigna valores de un objeto a un arreglo asociativo. */
    $array["Id"] = $value->{"usuario.usuario_id"};
    $array["Name"] = $value->{"usuario.nombre"};
    $array["Adress"] = null;
    $array["AgentId"] = null;
    $array["CashDeskId"] = null;
    $array["CashDeskName"] = null;

    /* Se define un arreglo con datos de usuario y estados específicos. */
    $array["CreatedLocalDate"] = "2018-01-13T17:03:13.024";
    $array["EMail"] = "";
    $array["FirstName"] = $value->{"usuario.nombre"};
    $array["Hired"] = "0001-01-01T00:00:00";
    $array["IsAgent"] = false;
    $array["IsGiven"] = false;

    /* Se inicializa un arreglo con valores predeterminados para un usuario. */
    $array["IsQRCodeSent"] = false;
    $array["IsSuspended"] = false;
    $array["IsTwoFactorEnabled"] = false;
    $array["LastName"] = "";
    $array["PartnerId"] = 0;
    $array["Password"] = null;

    /* asigna valores de un objeto a un arreglo asociativo en PHP. */
    $array["UserName"] = $value->{"usuario.login"};
    $array["Login"] = $value->{"usuario.login"};
    $array["Phone"] = $value->{"usuario.telefono"};
    $array["LastLoginLocalDate"] = $value->{"usuario.fecha_ult"};

    $array["Partners"] = $value->{"usuario_perfil.mandante_lista"};


    /* Añade el contenido de $array al final del arreglo $usuariosFinal. */
    array_push($usuariosFinal, $array);

}


/* Código configura una respuesta indicando éxito sin errores y posiciones a omitir. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

// $response["Data"] = $usuariosFinal;

$response["pos"] = $SkeepRows;

/* Asigna el conteo de usuarios y datos finales a un arreglo de respuesta. */
$response["total_count"] = $usuarios->count[0]->{".count"};
$response["data"] = $usuariosFinal;
