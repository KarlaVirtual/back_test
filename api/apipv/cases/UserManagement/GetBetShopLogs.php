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
use Backend\dto\Ciudad;
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
use Backend\dto\UsuarioLog2;
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
 * UserManagement/GetUserLogs
 *
 * Obtener logs de los usuarios
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
 * @OA\Post(path="apipv/UserManagement/GetUserLogsBetShop", tags={"UserManagement"}, description = "",
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
 *                   property="State",
 *                   description="",
 *                   type="integer",
 *                   example= "1"
 *               ),
 *               @OA\Property(
 *                   property="FromDateLocal",
 *                   description="FromDateLocal",
 *                   type="string",
 *                   example= ""
 *               ),
 *               @OA\Property(
 *                   property="ToDateLocal",
 *                   description="ToDateLocal",
 *                   type="string",
 *                   example= ""
 *               ),
 *               @OA\Property(
 *                   property="clientId",
 *                   description="clientId",
 *                   type="string",
 *                   example= ""
 *               ),
 *               @OA\Property(
 *                   property="IsDetails",
 *                   description="IsDetails",
 *                   type="string",
 *                   example= ""
 *               ),
 *               @OA\Property(
 *                   property="Id",
 *                   description="Id",
 *                   type="string",
 *                   example= ""
 *               ),
 *               @OA\Property(
 *                   property="UserName",
 *                   description="UserName",
 *                   type="string",
 *                   example= ""
 *               ),
 *               @OA\Property(
 *                   property="CountrySelect",
 *                   description="CountrySelect",
 *                   type="string",
 *                   example= ""
 *               ),
 *               @OA\Property(
 *                   property="CountrySelect2",
 *                   description="CountrySelect2",
 *                   type="string",
 *                   example= ""
 *               ),
 *               @OA\Property(
 *                   property="Field",
 *                   description="Field",
 *                   type="string",
 *                   example= ""
 *               ),
 *               @OA\Property(
 *                   property="UserNameRequest",
 *                   description="UserNameRequest",
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
 *               )
 *             )
 *         )
 *      )
 * )
 */

/**
 * Obtener logs de los usuarios en puntos de venta.
 *
 * Este script procesa una solicitud para obtener los registros de logs de usuarios
 * en puntos de venta, aplicando filtros y reglas según los parámetros proporcionados.
 *
 * @param object $params Objeto JSON con los siguientes valores:
 * @param string $params->FromDateLocal Fecha inicial en formato local.
 * @param string $params->ToDateLocal Fecha final en formato local.
 * @param string $params->clientId ID del cliente.
 * @param string $params->IsDetails Indica si se requieren detalles adicionales.
 * @param integer $params->MaxRows Número máximo de registros a devolver.
 * @param integer $params->OrderedItem Orden de los registros.
 * @param integer $params->SkeepRows Número de registros a omitir.
 * @param string $params->State Estado del registro (0, 1 o "P").
 * @param string $params->CountrySelect País seleccionado.
 * @param string $params->CountrySelect2 Segundo país seleccionado.
 * @param string $params->Field Campo específico para filtrar.
 * @param string $params->UserNameRequest Nombre de usuario que realiza la solicitud.
 * @param string $params->UserName Nombre de usuario.
 *
 * @return array $response Respuesta en formato JSON con los siguientes valores:
 *  - HasError (boolean): Indica si ocurrió un error.
 *  - AlertType (string): Tipo de alerta (e.g., "success", "error").
 *  - AlertMessage (string): Mensaje de alerta o error.
 *  - ModelErrors (array): Lista de errores de validación.
 *  - Data (array): Datos procesados, incluyendo:
 *      - Objects (array): Lista de logs procesados.
 *      - Count (integer): Número total de registros.
 *  - pos (integer): Posición inicial de los registros devueltos.
 *  - total_count (integer): Total de registros disponibles.
 *  - data (array): Datos procesados (duplicado de "Objects").
 */


/* recibe y decodifica datos JSON de una solicitud HTTP en PHP. */
$params = file_get_contents('php://input');
$params = json_decode($params);


$FromDateLocal = $params->FromDateLocal;
$ToDateLocal = $params->ToDateLocal;

/* Asignación de parámetros desde un objeto a variables en un código. */
$ClientId = $params->clientId;
$IsDetails = $params->IsDetails;

$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;

/* captura datos de formulario enviados mediante solicitudes HTTP. */
$State = $_REQUEST["State"];
$CountrySelect = $_REQUEST["CountrySelect"];
$CountrySelect2 = $_REQUEST["CountrySelect2"];

$UserNameRequest = $_REQUEST["UserNameRequest"];

$MaxRows = $_REQUEST["count"];

/* obtiene parámetros de entrada para procesar un pedido de cliente. */
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];

$ClientId = $_REQUEST["Id"];
$UserName = $_REQUEST["UserName"];
$Field = $_REQUEST["Field"];



/* procesa una solicitud de nombre de usuario y maneja una fecha con formato específico. */
$UserNameRequest = $_REQUEST["UserNameRequest"];





if ($_REQUEST["dateTo"] != "") {
    $dateTo = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . $timezone . ' hour '));

}



/* procesa una fecha de entrada y establece una variable de filas a omitir. */
if ($_REQUEST["dateFrom"] != "") {
    $dateFrom = date("Y-m-d 00:00:00", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));
}


if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* inicializa variables si están vacías: $OrderedItem y $MaxRows. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 10000;
}



/* agrega reglas de filtrado basadas en condiciones de cliente y fecha. */
$rules = [];

if ($ClientId != "") {
    array_push($rules, array("field" => "usuario_log2.usuario_id", "data" => "$ClientId", "op" => "eq"));
}


if ($dateFrom != "") {
    array_push($rules, array("field" => "usuario_log2.fecha_crea", "data" => "$dateFrom", "op" => "ge"));

}

/* Añade reglas a un array según condiciones de fecha y estado. */
if ($dateTo != "") {
    array_push($rules, array("field" => "usuario_log2.fecha_crea", "data" => "$dateTo", "op" => "le"));

}

if ($State != "0" && $State != "1") {
    array_push($rules, array("field" => "usuario_log2.estado", "data" => "P", "op" => "eq"));
}


/* Agrega reglas según el estado: "A" para 0 y "NA" para 1. */
if ($State == "0") {
    array_push($rules, array("field" => "usuario_log2.estado", "data" => "A", "op" => "eq"));
}

if ($State == "1") {
    array_push($rules, array("field" => "usuario_log2.estado", "data" => "NA", "op" => "eq"));
}

/* Agrega reglas si los países seleccionados son válidos y diferentes de cero. */
if ($CountrySelect != "" && $CountrySelect != "0") {
    array_push($rules, array("field" => "usuario.pais_id", "data" => $CountrySelect, "op" => "eq"));
}
if ($CountrySelect2 != "" && $CountrySelect2 != "0") {
    array_push($rules, array("field" => "usuario.pais_id", "data" => "$CountrySelect2", "op" => "eq"));

}


/* Condicionales que agregan reglas a un arreglo si los campos no están vacíos. */
if($Field != ""){
//array_push($rules,array("field"=>"usuario_log2.tipo","data"=>"$Field","op"=>"eq")); // pendiente
}

if($UserNameRequest != ""){
    array_push($rules, array("field" => "usuario_log2.usuariosolicita_id", "data" => $UserNameRequest, "op" => "eq"));

}


/* Agrega reglas basadas en los valores de usuario y campo si no están vacíos. */
if ($UserName != "") {
    array_push($rules, array("field" => "usuario.login", "data" => "$UserName", "op" => "cn"));
}

if ($Field != "") {
    array_push($rules, array("field" => "usuario_log2.tipo", "data" => "$Field", "op" => "eq"));
}else{
    /* agrega reglas de validación para diferentes tipos de usuario en un array. */


//array_push($rules, array("field" => "usuario_log2.tipo", "data" => "'USUDNIANTERIOR', 'USUDNIPOSTERIOR', 'USUFECHANACIM', 'USUAPELLIDO2', 'USUAPELLIDO', 'USUNOMBRE2', 'USUNOMBRE'", "op" => "in"));


    array_push($rules, array("field" => "usuario_log2.tipo", "data" => "TOKENPASS", "op" => "ne"));
    array_push($rules, array("field" => "usuario_log2.tipo", "data" => "TOKENPASSIMPORT", "op" => "ne"));
    array_push($rules, array("field" => "usuario_log2.tipo", "data" => "VERIFYPHONE", "op" => "ne"));
    array_push($rules, array("field" => "usuario_log2.tipo", "data" => "LOGIN1INCORRECTO", "op" => "ne"));
    array_push($rules, array("field" => "usuario_log2.tipo", "data" => "LOGIN1", "op" => "ne"));
    array_push($rules, array("field" => "usuario_log2.tipo", "data" => "LOGININCORRECTO", "op" => "ne"));
    array_push($rules, array("field" => "usuario_log2.tipo", "data" => "LOGIN", "op" => "ne"));

//array_push($rules, array("field" => "usuario_log2.usuario_id", "data" => "$ClientId", "op" => "eq"));
//array_push($rules, array("field" => "usuario_log2.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
//array_push($rules, array("field" => "usuario_log2.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));
//array_push($rules, array("field" => "usuario_log2.tipo", "data" => "LOGIN", "op" => "ne"));

}




/* valida condiciones de usuario y los agrega a un arreglo de reglas. */
if ($UserNameRequest != "") {
    array_push($rules, array("field" => "usuario_log2.usuariosolicita_id", "data" => "$UserNameRequest", "op" => "eq"));

}

// Si el usuario esta condicionado por País
if ($_SESSION['PaisCond'] == "S") {
    array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
}
// Si el usuario esta condicionado por el mandante y no es de Global

/* agrega reglas basadas en la sesión del usuario. */
if ($_SESSION['Global'] == "N") {
    array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
} else {

    if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
        array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
    }

}

// Inactivamos reportes para el país Colombia
//array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));



/* crea un filtro JSON y selecciona campos de base de datos. */
$filtro = array("rules" => $rules, "groupOp" => "AND");

$json = json_encode($filtro);

setlocale(LC_ALL, 'czech');


$select = "usuariosolicita.nombres,usuarioaprobar.nombres, usuario_log2.*,clasificador.descripcion,pais.iso ";
//$select = "usuario_perfil.perfil_id,usuariosolicita.nombres,usuarioaprobar.nombres, usuario_log2.*,clasificador.descripcion,pais.iso ";



/* Se obtiene y decodifica un registro de usuario desde un punto de venta. */
$UsuarioLog = new UsuarioLog2();
$data = $UsuarioLog->getUsuarioLog2sCustomPuntoVenta($select, "usuario_log2.usuariolog2_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping);
$data = json_decode($data);


$final = [];

foreach ($data->data as $key => $value) {


    /* Se crea un array asociativo con datos de un usuario logueado. */
    $array = [];

    $array["Id"] = $value->{"usuario_log2.usuariolog2_id"};
    $array["Time"] = $value->{"usuario_log2.fecha_crea"};
    $array["UserName"] = $value->{"usuario_log2.usuario_id"};
    $array["UserNameRequest"] = $value->{"usuario_log2.usuariosolicita_id"};

    /* Asigna nombres de usuario a un array según condiciones específicas y datos de sesión. */
    $array["UserNameApproval"] = $value->{"usuario_log2.usuarioaprobar_id"};
    if($array["UserNameApproval"]  == '0'){
        $array["UserNameApproval"] ='';
    }

    if($_SESSION['mandante'] == '2'){
        $array["UserNameRequest"] = $value->{"usuariosolicita.nombres"};
        $array["UserNameApproval"] = $value->{"usuarioaprobar.nombres"};
        if($array["UserNameApproval"]  == ''){
            $array["UserNameApproval"] =$value->{"usuario_log2.usuario_id"};
        }
        if($array["UserNameRequest"]  == ''){
            $array["UserNameRequest"] =$value->{"usuario_log2.usuario_id"};
        }
    }


    /* asigna valores a un array basado en un objeto y condiciones específicas. */
    $array["Field"] = $value->{"usuario_log2.tipo"};
    $array["StateId"] = $value->{"usuario_log2.estado"};

    $array["Country"] = 'pe';
    $array["Country"] = strtolower($value->{"pais.iso"});

    if (is_numeric($array["Field"])) {
        $array["Field"] = $value->{"clasificador.descripcion"};
    }



    /* Asigna estados y valores a un array, incluyendo una imagen en base64. */
    $array["State"] = ($value->{"usuario_log2.estado"} == "P") ? "Pendiente" : ($value->{"usuario_log2.estado"} == "A") ? "Aprobada" : "Rechazada";
    $array["OldValue"] = $value->{"usuario_log2.valor_antes"};
    $array["NewValue"] = $value->{"usuario_log2.valor_despues"};

    $base642 = 'data:image/;base64,' . ($value->{"usuario_log2.imagen"});

    $array["Imagen"] = $base642;

    switch ($array["Field"]){

        case "USUDIRECCION":
            /* Asigna 'Direccion' o 'Address' según el idioma de sesión en uso. */

            $array["Field"] = 'Direccion';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Field"] = 'Address';
            }

            break;
        case "USUGENERO":
            /* Código que asigna 'Genero' o 'Gender' según el idioma de la sesión. */

            $array["Field"] = 'Genero';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Field"] = 'Gender';
            }

            break;

        case "USUTELEFONO":
            /* Asigna 'Telefono' o 'Phone' según el idioma de la sesión en curso. */

            $array["Field"] = 'Telefono';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Field"] = 'Phone';
            }


            break;


        case "USUNOMBRE1":
            /* Asigna "Primer Nombre" o "First Name" a un campo según el idioma de sesión. */

            $array["Field"] = 'Primer Nombre';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Field"] = 'First Name';
            }
            break;



        case "USUNOMBRE2":
            /* Asigna "Segundo Nombre" o "Second name" a un campo según el idioma. */

            $array["Field"] = 'Segundo Nombre';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Field"] = 'Second name';
            }

            break;



        case "USUAPELLIDO1":
            /* Asigna nombres de campos según el idioma en la sesión del usuario. */

            $array["Field"] = 'Primer Apellido';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Field"] = 'Surname';
            }

            break;



        case "USUAPELLIDO2":
            /* Define el campo "Segundo Apellido" y lo traduce al inglés si es necesario. */

            $array["Field"] = 'Segundo Apellido';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Field"] = 'Second surname';
            }
            break;

        case "USUCELULAR":
            /* Asigna "Celular" o "Mobile" a un campo según el idioma de la sesión. */

            $array["Field"] = 'Celular';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Field"] = 'Mobile';
            }
            break;


        case "USUEMAIL":
            /* asigna 'Email' al campo correspondiente en un array para "USUEMAIL". */

            $array["Field"] = 'Email';
            break;


        case "LIMITEDEPOSITOSIMPLE":
            /* Asigna un límite de depósito según el idioma de la sesión. */

            $array["Field"] = 'Limite Deposito Simple';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Field"] = 'Simple Deposit Limit';
            }
            break;

        case "LIMITEDEPOSITODIARIO":
            /* Asigna el campo de límite de depósito diario según el idioma de la sesión. */

            $array["Field"] = 'Limite Deposito Diario';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Field"] = 'Daily Deposit Limit';
            }
            break;

        case "LIMITEDEPOSITOSEMANA":
            /* Establece un límite de depósito semanal según el idioma de la sesión. */

            $array["Field"] = 'Limite Deposito Semanal';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Field"] = 'Weekly Deposit Limit';
            }
            break;

        case "LIMITEDEPOSITOMENSUAL":
            /* Asigna un campo según el idioma para el límite de depósito mensual. */

            $array["Field"] = 'Limite Deposito Mensual';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Field"] = 'Monthly Deposit Limit';
            }
            break;

        case "LIMITEDEPOSITOANUAL":
            /* Asigna un texto a un campo según el idioma almacenado en la sesión. */

            $array["Field"] = 'Limite Deposito Anual';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Field"] = 'Annual Deposit Limit';
            }
            break;


        case "LIMAPUDEPORTIVASIMPLE":
            /* asigna un valor a un campo dependiendo del idioma de la sesión. */

            $array["Field"] = 'Limite Deportivas Simple';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Field"] = 'Simple Sports Limit';
            }

            break;

        case "LIMAPUDEPORTIVADIARIO":
            /* asigna un nombre de campo según el idioma seleccionado por el usuario. */

            $array["Field"] = 'Limite Deportivas Diario';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Field"] = 'Daily Sports Limit';
            }

            break;

        case "LIMAPUDEPORTIVASEMANA":
            /* Asigna nombres de campo según el idioma de la sesión: español o inglés. */

            $array["Field"] = 'Limite Deportivas Semanal';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Field"] = 'Weekly Sports Limit';
            }
            break;

        case "LIMAPUDEPORTIVAMENSUAL":
            /* Código que asigna un campo de límite deportivo según el idioma de la sesión. */

            $array["Field"] = 'Limite Deportivas Mensual';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Field"] = 'Monthly Sports Limit';
            }
            break;

        case "LIMAPUDEPORTIVAANUAL":
            /* asigna un límite deportivo anual basado en el idioma de la sesión. */

            $array["Field"] = 'Limite Deportivas Anual';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Field"] = 'Annual Sports Limit';
            }
            break;


        case "LIMAPUCASINOSIMPLE":
            /* Asigna un nombre a una variable según el idioma definido en la sesión. */

            $array["Field"] = 'Limite Casino Simple';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Field"] = 'Limit Simple Casino';
            }

            break;

        case "LIMAPUCASINODIARIO":
            /* Asigna un nombre de campo basado en el idioma para límite diario en casinos. */

            $array["Field"] = 'Limite Casino Diario';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Field"] = 'Limit Daily Casino';
            }

            break;

        case "LIMAPUCASINOSEMANA":
            /* asigna un límite semanal para un casino según el idioma elegido. */

            $array["Field"] = 'Limite Casino Semanal';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Field"] = 'Limit Weekly Casino';
            }
            break;

        case "LIMAPUCASINOMENSUAL":
            /* Asigna un campo de límite de casino mensual según el idioma de la sesión. */

            $array["Field"] = 'Limite Casino Mensual';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Field"] = 'Limit Monthly Casino';
            }
            break;

        case "LIMAPUCASINOANUAL":
            /* Asigna un nombre de campo según el idioma de la sesión del usuario. */

            $array["Field"] = 'Limite Casino Anual';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Field"] = 'Limit Annual Casino';
            }
            break;


        case "LIMAPUCASINOVIVOSIMPLE":
            /* Cambia el nombre del campo según el idioma de la sesión del usuario. */

            $array["Field"] = 'Limite Casino Vivo Simple';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Field"] = 'Limit Simple Live Casino';
            }

            break;

        case "LIMAPUCASINOVIVODIARIO":
            /* Asigna un nombre de límite diario para casino vivo según el idioma de la sesión. */

            $array["Field"] = 'Limite Casino Vivo Diario';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Field"] = 'Limit Daily Live Casino';
            }

            break;

        case "LIMAPUCASINOVIVOSEMANA":
            /* Asigna un campo de límite semanales para un casino en función del idioma. */

            $array["Field"] = 'Limite Casino Vivo Semanal';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Field"] = 'Limit Weekly Live Casino';
            }
            break;

        case "LIMAPUCASINOVIVOMENSUAL":
            /* Define un campo de límite de casino mensual según el idioma del usuario. */

            $array["Field"] = 'Limite Casino Vivo Mensual';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Field"] = 'Limit Monthly Live Casino';
            }
            break;

        case "LIMAPUCASINOVIVOANUAL":
            /* asigna un valor a un campo según el idioma de la sesión. */

            $array["Field"] = 'Limite Casino Vivo Anual';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Field"] = 'Limit Annual Live Casino';
            }
            break;

        case "LIMAPUVIRTUALESSIMPLE":
            /* Asigna un valor a un campo basado en el idioma del usuario. */

            $array["Field"] = 'Limite Casino Vivo Simple';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Field"] = 'Limit Simple Live Casino';
            }

            break;

        case "LIMAPUVIRTUALESDIARIO":
            /* asigna un valor a un campo según el idioma del usuario. */

            $array["Field"] = 'Limite Casino Vivo Diario';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Field"] = 'Limit Daily Live Casino';
            }

            break;

        case "LIMAPUVIRTUALESSEMANA":
            /* Establece un límite semanal para el casino en función del idioma seleccionado. */

            $array["Field"] = 'Limite Casino Vivo Semanal';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Field"] = 'Limit Weekly Live Casino';
            }
            break;

        case "LIMAPUVIRTUALESMENSUAL":
            /* Establece el campo "Límite Casino Vivo Mensual" según el idioma de la sesión. */

            $array["Field"] = 'Limite Casino Vivo Mensual';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Field"] = 'Limit Monthly Live Casino';
            }
            break;

        case "LIMAPUVIRTUALESANUAL":
            /* Asigna nombres de campo según el idioma: español o inglés para límites anuales. */

            $array["Field"] = 'Limite Casino Vivo Anual';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Field"] = 'Limit Annual Live Casino';
            }
            break;


        case "TIEMPOLIMITEAUTOEXCLUSION":
            /* Asigna un valor de autoexclusión según el idioma de la sesión. */

            $array["Field"] = 'Autoexclusion por tiempo';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Field"] = 'Self-exclusion by time';
            }
            break;

        case "CAMBIOSAPROBACION":
            /* asigna un campo según el idioma de la sesión. */

            $array["Field"] = 'Cambios Aprobacion';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Field"] = 'Changes Approval';
            }
            break;

        case "ESTADOUSUARIO":
            /* asigna un valor a "Field" según el idioma de la sesión. */

            $array["Field"] = 'Estado Usuario';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Field"] = 'State User';
            }
            break;


        case "USUDNIANTERIOR":
            /* Asigna un nombre de campo basado en el idioma de la sesión. */

            $array["Field"] = 'DNI Anterior';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Field"] = 'Document front side';
            }
            break;


        case "USUDNIPOSTERIOR":
            /* Asigna el campo "DNI Posterior" según el idioma de la sesión. */

            $array["Field"] = 'DNI Posterior';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Field"] = 'Document back side';
            }
            break;

        case "USUVERDOM":
            /* Asigna un campo de verificación de dirección según el idioma de la sesión. */

            $array["Field"] = 'Verificacion de Direccion';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Field"] = 'Address Verification';
            }
            break;
        case "USUTRNANTERIOR":
            /* Asigna 'TRN Anterior' o 'Front of TRN' según el idioma de la sesión. */

            $array["Field"] = 'TRN Anterior';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Field"] = 'Front of TRN';
            }
            break;

        case "USUTRNPOSTERIOR":
            /* Asigna "TRN Posterior" o "Back of TRN" según el idioma de la sesión. */

            $array["Field"] = 'TRN Posterior';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Field"] = 'Back of TRN';
            }
            break;


        case "USUCIUDAD":
            /* asigna nombres de ciudades según el idioma y recupera valores antiguos y nuevos. */

            $array["Field"] = 'Ciudad Residencia';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Field"] = 'City of Residence';
            }

            $array["OldValue"] = $value->{"usuario_log2.valor_antes"};

            $Ciudad = new Ciudad($array["OldValue"]);
            $array["OldValue"]=$Ciudad->ciudadNom;

            $Ciudad = new Ciudad($array["NewValue"]);
            $array["NewValue"]=$Ciudad->ciudadNom;

            break;


        case "USUCODIGOPOSTAL":
            /* asigna el nombre del campo según el idioma de la sesión. */

            $array["Field"] = 'Codigo Postal';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Field"] = 'Postal Code';
            }
            break;

        case "USUFECHANACIM":
            /* asigna el nombre del campo según el idioma seleccionado en la sesión. */

            $array["Field"] = 'Fecha de Nacimiento';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Field"] = 'Date of birth';
            }
            break;

        case "USUCEDULA":
            /* asigna un nombre de campo según el idioma de la sesión. */

            $array["Field"] = 'Cedula (ID)';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Field"] = 'Identification (DNI or ID)';
            }
            break;

        case "USUNACIONALIDAD":
            /* Código asigna etiquetas de nacionalidad según idioma y actualiza valores de país. */

            $array["Field"] = 'Nacionalidad';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Field"] = 'Nationality';
            }

            $Pais = new Pais($array["OldValue"]);
            $array["OldValue"]=$Pais->paisNom;

            $Pais = new Pais($array["NewValue"]);
            $array["NewValue"]=$Pais->paisNom;

            break;

        case "USUTIPODOC":
            /* Asigna el nombre del tipo de documento según el idioma de la sesión. */

            $array["Field"] = 'Tipo de Documento';
            if(strtolower($_SESSION["idioma"])=="en"){
                $array["Field"] = 'Document type';
            }
            break;

        case "GENDER":
            /* asigna el valor 'Genero' a un campo en un arreglo. */

            $array["Field"] = 'Genero';
            break;
        case "UINFO1":
            /* Define un campo en un array basado en un caso específico. */

            $array["Field"] = 'Info 1';
            break;

        case "UINFO2":
            /* Asigna el valor 'Info 2' a la clave 'Field' del array. */

            $array["Field"] = 'Info 2';
            break;

        case "UINFO3":
            /* asigna 'Info 3' al elemento 'Field' de un array. */

            $array["Field"] = 'Info 3';
            break;

        case "USUFACEBOOK":
            /* Configura el campo "Facebook" según el idioma de la sesión. */

            $array["Field"] = 'Facebook';
            if (strtolower($_SESSION["idioma"]) == "en") {
                $array["Field"] = 'Facebook';
            }
            break;
        case "USUINSTAGRAM":
            /* Asigna 'Instagram' a un campo según el idioma de la sesión. */

            $array["Field"] = 'Instagram';
            if (strtolower($_SESSION["idioma"]) == "en") {
                $array["Field"] = 'Instagram';
            }
            break;
        case "PVNOMBRE":
            /* asigna nombres a un campo según el idioma de la sesión. */

            $array["Field"] = 'Nombre Punto de Venta';
            if (strtolower($_SESSION["idioma"]) == "en") {
                $array["Field"] = 'Bet shop name';
            }
            break;
        case "USUOTRAREDSOCIAL":
            /* Asigna el nombre del campo según el idioma establecido en la sesión. */

            $array["Field"] = 'Red Social Adicional';
            if (strtolower($_SESSION["idioma"]) == "en") {
                $array["Field"] = 'Additional Social Network';
            }
            break;
    }


    array_push($final, $array);

}

/*Generación formato de respuesta*/
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = array(
    "Objects" => $final,
    "Count" => $data->count[0]->{".count"},

);

$response["Data"] = $final;
$response["pos"] = $SkeepRows;
$response["total_count"] = oldCount($final);
$response["data"] = $final;
