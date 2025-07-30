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


/* Se crean instancias de UsuarioMandante y Usuario utilizando datos de sesión. */
$UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);
$UsuarioPuntoVenta = new Usuario($UsuarioMandante->getUsuarioMandante());


/**
 * UserManagement/GetRecargaCreditoAgent
 *
 * Este script permite obtener detalles para realizar un depósito basado en varios filtros.
 *
 * @param object $params Objeto que contiene los siguientes parámetros:
 * @param int $params->MaxRows Número máximo de filas a devolver.
 * @param int $params->OrderedItem Elemento por el cual se ordenará la consulta.
 * @param int $params->SkeepRows Número de filas a omitir en la consulta.
 * @param string $params->Concessionaire ID del concesionario.
 * @param string $params->Subconcessionaire ID del sub-concesionario.
 * @param string $params->Subconcessionaire2 ID del segundo sub-concesionario.
 * @param string $params->UserId ID del usuario.
 * @param string $params->BetShopId ID del punto de venta.
 *
 * @return array Respuesta en formato JSON con los siguientes valores:
 *  - HasError (boolean): Indica si ocurrió un error.
 *  - AlertType (string): Tipo de alerta (e.g., "success", "error").
 *  - AlertMessage (string): Mensaje asociado a la alerta.
 *  - data (array): Contiene los datos del usuario o punto de venta.
 */

/**
 * @OA\Post(path="apipv/UserManagement/GetRecargaCredito", tags={"UserManagement"}, description = "",
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
 *                   type="string",
 *                   example= "2"
 *               ),
 *               @OA\Property(
 *                   property="Id",
 *                   description="",
 *                   type="integer",
 *                   example= ""
 *               ),
 *               @OA\Property(
 *                   property="Cedula",
 *                   description="",
 *                   type="string",
 *                   example= ""
 *               ),
 *               @OA\Property(
 *                   property="Email",
 *                   description="",
 *                   type="string",
 *                   example= ""
 *               ),
 *             )
 *         ),
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
 *                   property="data",
 *                   description="",
 *                   type="Array",
 *                   example= {}
 *               ),
 *             )
 *         ),
 *         )
 * ),
 * @OA\Response (response="404", description="Not found")
 * ),
 */


/* recibe datos JSON, los decodifica y obtiene parámetros de la solicitud. */
$params = file_get_contents('php://input');
$params = json_decode($params);


$Concessionaire = $_REQUEST["Concessionaire"];
$Subconcessionaire = $_REQUEST["Subconcessionaire"];

/* obtiene valores de solicitudes HTTP usando el método $_REQUEST en PHP. */
$Subconcessionaire2 = $_REQUEST["Subconcessionaire2"];
$Subconcessionaire2 = $_REQUEST["Subconcessionaire2"];
$UserId = $_REQUEST["UserId"];
$BetShopId = $_REQUEST["BetShopId"];


if ($Concessionaire != "" || $Subconcessionaire != "" || $Subconcessionaire2 != "" || $UserId != "" || $BetShopId != "") {

    /* asigna parámetros y obtiene un valor de solicitudes HTTP. */
    $MaxRows = $params->MaxRows;
    $OrderedItem = $params->OrderedItem;
    $SkeepRows = $params->SkeepRows;


    $MaxRows = $_REQUEST["count"];

    /* establece valores predeterminados para variables basadas en solicitudes. */
    $SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];

    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }

    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }


    /* asigna un valor predeterminado a MaxRows y define reglas de filtrado. */
    if ($MaxRows == "") {
        $MaxRows = 1000;
    }

    $rules = [];
    array_push($rules, array("field" => "usuario.pais_id", "data" => "$UsuarioPuntoVenta->paisId", "op" => "eq"));

    /* Agrega reglas de validación basadas en el mandante y país del usuario. */
    array_push($rules, array("field" => "usuario.mandante", "data" => "$UsuarioPuntoVenta->mandante", "op" => "eq"));

    // Si el usuario esta condicionado por País
    if ($_SESSION['PaisCond'] == "S") {
        array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
    }

    // Si el usuario esta condicionado por el mandante y no es de Global

    /* agrega reglas basadas en la sesión del usuario para filtrado. */
    if ($_SESSION['Global'] == "N") {
        array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
    } else {

        if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
            array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
        }

    }


    /* Agrega reglas a un arreglo si $Concessionaire es válido y definido. */
    if ($Concessionaire != "" && $Concessionaire != "undefined") {
        // array_push($rules, array("field" => "concesionario.usupadre_id", "data" => "$Concessionaire", "op" => "eq"));
        array_push($rules, array("field" => "usuario.usuario_id", "data" => "$Concessionaire", "op" => "eq"));
        array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "CONCESIONARIO", "op" => "eq"));

    }


    /* Agrega reglas a un arreglo si $Subconcessionaire no está vacío ni es "undefined". */
    if ($Subconcessionaire != "" && $Subconcessionaire != "undefined") {
        //array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => "$Subconcessionaire", "op" => "eq"));
        array_push($rules, array("field" => "usuario.usuario_id", "data" => "$Subconcessionaire", "op" => "eq"));
        array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "CONCESIONARIO2", "op" => "eq"));

    }


    /* Añade reglas a un array si $Subconcessionaire2 no está vacío ni es indefinido. */
    if ($Subconcessionaire2 != "" && $Subconcessionaire2 != "undefined") {
        // array_push($rules, array("field" => "concesionario.usupadre3_id", "data" => "$Subconcessionaire2", "op" => "eq"));
        array_push($rules, array("field" => "usuario.usuario_id", "data" => "$Subconcessionaire2", "op" => "eq"));
        array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "CONCESIONARIO3", "op" => "eq"));

    }


    /* Verifica $UserId y añade reglas de filtrado a un arreglo. */
    if ($UserId != "" && $UserId != "undefined") {

        array_push($rules, array("field" => "usuario.usuario_id", "data" => "$UserId", "op" => "eq"));
        array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "'CONCESIONARIO','CONCESIONARIO2','CONCESIONARIO3','PUNTOVENTA'", "op" => "in"));

    }


    /* Añade reglas de filtrado si $BetShopId es válido y definido. */
    if ($BetShopId != "" && $BetShopId != "undefined") {

        array_push($rules, array("field" => "usuario.usuario_id", "data" => "$BetShopId", "op" => "eq"));
        array_push($rules, array("field" => "usuario_perfil.perfil_id", "data" => "PUNTOVENTA", "op" => "eq"));

    }

    /* Se define un filtro con reglas para validar condiciones de usuario y registro. */
    array_push($rules, array("field" => "usuario.usuario_id", "data" => $_SESSION['usuario'], "op" => "ne"));


    // array_push($rules, array("field" => "registro.estado_valida", "data" => "I", "op" => "eq"));


    $filtro = array("rules" => $rules, "groupOp" => "AND");

    /* convierte un filtro a JSON y obtiene usuarios según criterios especificados. */
    $json = json_encode($filtro);

    $Usuario = new Usuario();


    $tickets = $Usuario->getUsuariosCustom("pais.pais_nom,usuario.usuario_id,usuario.nombre,usuario.moneda ", "usuario.usuario_id", "asc", 0, 1, $json, true);

    /* Decodifica tickets JSON y establece una respuesta sin errores y exitosa. */
    $tickets = json_decode($tickets);


    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

    /* asigna datos de tickets a una respuesta si existen registros previos. */
    if (oldCount($tickets->data) > 0) {
        $response["data"] =
            array(
                "Id" => $tickets->data[0]->{"usuario.usuario_id"},
                "Name" => $tickets->data[0]->{"usuario.nombre"},
                "Pais" => $tickets->data[0]->{"pais.pais_nom"},
                "Moneda" => $tickets->data[0]->{"usuario.moneda"});

    } else {
        /* asigna 'true' a "HasError" si no se cumple una condición previa. */

        $response["HasError"] = true;
    }


} else {
    /* lanza una excepción con un mensaje y un código de error específico. */

    throw new Exception("Error ", "1");
}
