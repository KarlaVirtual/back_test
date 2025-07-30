<?php

use Backend\dto\ApiTransaction;
use Backend\dto\Area;
use Backend\dto\BodegaFlujoCaja;
use Backend\dto\Bono;
use Backend\dto\BonoDetalle;
use Backend\dto\BonoInterno;
use Backend\dto\BonoLog;
use Backend\dto\Cargo;
use Backend\dto\Categoria;
use Backend\dto\CategoriaProducto;
use Backend\dto\CentroCosto;
use Backend\dto\Cheque;
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
use Backend\dto\RegistroRapido;
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
 * UserManagement/GetTableRegisterFast
 *
 * Este script permite obtener registros rápidos con base en los parámetros proporcionados.
 *
 * @param object $params Objeto JSON con los siguientes campos:
 * @param string $params->dateTo Fecha final en formato local.
 * @param string $params->dateFrom Fecha inicial en formato local.
 * @param string $params->PaymentSystemId ID del sistema de pago.
 * @param string $params->CashDeskId ID de la caja.
 * @param string $params->ClientId ID del cliente.
 * @param float $params->AmountFrom Monto mínimo.
 * @param float $params->AmountTo Monto máximo.
 * @param string $params->CurrencyId ID de la moneda.
 * @param string $params->ExternalId ID externo.
 * @param string $params->Id ID del registro.
 * @param boolean $params->IsDetails Indica si se requieren detalles.
 *
 * @return array $response Respuesta en formato JSON con los siguientes campos:
 * - pos (integer): Posición inicial de los registros.
 * - total_count (integer): Total de registros disponibles.
 * - data (array): Datos procesados, incluyendo:
 *   - Id (string): ID del registro.
 *   - DocumentType (string): Tipo de documento.
 *   - DocumentNumber (string): Número de documento.
 *   - Country (string): País del registro.
 *   - Currency (string): Moneda del registro.
 *   - Surname (string): Primer apellido.
 *   - SecondSurname (string): Segundo apellido.
 *   - FirstName (string): Primer nombre.
 *   - SecondName (string): Segundo nombre.
 *
 * @throws Exception Si ocurre un error durante el procesamiento de los datos.
 */


/* crea un objeto y obtiene datos JSON de la entrada. */
$RegistroRapido = new RegistroRapido();

$params = file_get_contents('php://input');
$params = json_decode($params);

$ToDateLocal = $params->dateTo;


/* convierte fechas a formato local considerando la zona horaria y parámetros. */
$ToDateLocal = date("Y-m-d", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . '' . $timezone . ' hour '));

$FromDateLocal = $params->dateFrom;

$FromDateLocal = date("Y-m-d", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));


$PaymentSystemId = $params->PaymentSystemId;

/* asigna valores de parámetros a variables específicas para su uso posterior. */
$CashDeskId = $params->CashDeskId;
$ClientId = $params->ClientId;
$AmountFrom = $params->AmountFrom;
$AmountTo = $params->AmountTo;
$CurrencyId = $params->CurrencyId;
$ExternalId = $params->ExternalId;

/* asigna valores a variables según la validez de los parámetros recibidos. */
$Id = $params->Id;
$IsDetails = ($params->IsDetails == true) ? true : false;

$CountrySelect = (is_numeric($_REQUEST["CountrySelect"])) ? $_REQUEST["CountrySelect"] : '';
$BetShopId = (is_numeric($_REQUEST["BetShopId"])) ? $_REQUEST["BetShopId"] : '';
$UserId = (is_numeric($_REQUEST["UserId"])) ? $_REQUEST["UserId"] : '';

/* valida y asigna valores de entradas de solicitud HTTP. */
$TypeDetail = ($_REQUEST["TypeDetail"] == "0") ? 0 : 1;
$TypeTotal = ($_REQUEST["Type"] == "0") ? 0 : 1;

$UserIdAgent = (is_numeric($_REQUEST["UserIdAgent"])) ? $_REQUEST["UserIdAgent"] : '';
$UserIdAgent2 = (is_numeric($_REQUEST["UserIdAgent2"])) ? $_REQUEST["UserIdAgent2"] : '';

$MaxRows = $_REQUEST["count"];

/* obtiene un elemento ordenado y verifica condiciones para continuar ejecución. */
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];
$seguir = true;

if ($SkeepRows == "" || $MaxRows == "") {
    $seguir = false;

}


/* Se inicializan un arreglo vacío y una variable total en cero. */
$final = [];
$totalm = 0;

if (true) {


    /* Código que define fechas y establece reglas para filtrar datos en un array. */
    $FromDateLocal = date("Y-m-d");
    $ToDateLocal = date("Y-m-d");

    $rules = [];

    //array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
    //array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));


    $grouping = "";

    /* inicializa variables $SkeepRows y $OrderedItem si están vacías. */
    $select = "";

    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }

    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }


    /* asigna valores predeterminados a variables según condiciones específicas. */
    if ($MaxRows == "") {
        $MaxRows = 100;
    }


    if ($_REQUEST["UserId"] != "") {
        $BetShopId = $_REQUEST["UserId"];

    }


    /* Se agregan reglas y se construye una consulta SQL para obtener registros. */
    array_push($rules, array("field" => "registro_rapido.mandante", "data" => $_SESSION["mandante"], "op" => "eq"));

    array_push($rules, array("field" => "registro_rapido.pais_id", "data" => $_SESSION["pais_id"], "op" => "eq"));
    array_push($rules, array("field" => "registro_rapido.pais_id", "data" => "1", "op" => "ne"));

    $select = " registro_rapido.registro_id,case when registro_rapido.tipo_doc='C' then 'Cedula de Ciudadania' when registro_rapido.tipo_doc='E' then 'Cedula de Extranjeria' else 'Pasaporte' end tipo_doc,registro_rapido.cedula,pais.*,registro_rapido.moneda,registro_rapido.apellido1,registro_rapido.apellido2,registro_rapido.nombre1,registro_rapido.nombre2 ";


    /* almacena valores de sesión y prepara un filtro en formato JSON. */
    $paisId = $_SESSION["pais_id"];
    $usuarioId = $_SESSION["usuario"];

    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $jsonfiltro = json_encode($filtro);





    /* obtiene registros, los decodifica en JSON y procesa datos específicos en un array. */
    $transacciones = $RegistroRapido->getRegistrosRapidosCustom($select, "registro_rapido.registro_id", "desc", $SkeepRows, $MaxRows, $jsonfiltro, true);


    $transacciones = json_decode($transacciones);


    foreach ($transacciones->data as $key => $value) {
        $array = [];
        $array["Id"] = $value->{"registro_rapido.registro_id"};
        $array["DocumentType"] = $value->{".tipo_doc"};
        $array["DocumentNumber"] = $value->{"registro_rapido.cedula"};
        $array["Country"] = strtolower($value->{"pais.iso"});
        $array["Currency"] = $value->{"registro_rapido.moneda"};
        $array["Surname"] = $value->{"registro_rapido.apellido1"};
        $array["SecondSurname"] = $value->{"registro_rapido.apellido2"};
        $array["FirstName"] = $value->{"registro_rapido.nombre1"};
        $array["SecondName"] = $value->{"registro_rapido.nombre2"};


        array_push($final, $array);


    }
}


/* asigna valores a un array de respuesta sobre transacciones. */
$response["pos"] = $SkeepRows;
$response["total_count"] = $transacciones->count[0]->{".count"};
$response["data"] = $final;
