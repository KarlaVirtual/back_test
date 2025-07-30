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
 * UserManagement/GetTableReprintCheck
 *
 * Este script permite obtener información de cheques y tickets para reimpresión con base en los parámetros proporcionados.
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
 * @param string $params->Id ID del cheque.
 * @param boolean $params->IsDetails Indica si se requieren detalles.
 *
 * @return array $response Respuesta en formato JSON con los siguientes campos:
 * - pos (integer): Posición inicial de los registros.
 * - total_count (integer): Total de registros disponibles.
 * - data (array): Datos procesados, incluyendo:
 *   - Id (string): ID del cheque.
 *   - NroCheck (string): Número del cheque.
 *   - Client (string): Nombre del cliente.
 *   - DocClient (string): Documento del cliente.
 *   - Date (string): Fecha del cheque.
 *   - Origin (string): Origen del cheque.
 *   - ReferenceDocument (string): Documento de referencia.
 *   - Value (float): Valor del cheque.
 *   - Currency (string): Moneda del cheque.
 *
 * @throws Exception Si ocurre un error durante el procesamiento de los datos.
 */


/* Se crea un objeto PuntoVenta y se decodifica un JSON de entrada. */
$PuntoVenta = new PuntoVenta();

$params = file_get_contents('php://input');
$params = json_decode($params);

$ToDateLocal = $params->dateTo;


/* convierte fechas en formato local teniendo en cuenta la zona horaria y parámetros. */
$ToDateLocal = date("Y-m-d", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . '' . $timezone . ' hour '));

$FromDateLocal = $params->dateFrom;

$FromDateLocal = date("Y-m-d", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));


$PaymentSystemId = $params->PaymentSystemId;

/* Asignación de variables desde un objeto $params para procesamiento financiero. */
$CashDeskId = $params->CashDeskId;
$ClientId = $params->ClientId;
$AmountFrom = $params->AmountFrom;
$AmountTo = $params->AmountTo;
$CurrencyId = $params->CurrencyId;
$ExternalId = $params->ExternalId;

/* valida y asigna parámetros de entrada a variables. */
$Id = $params->Id;
$IsDetails = ($params->IsDetails == true) ? true : false;

$CountrySelect = (is_numeric($_REQUEST["CountrySelect"])) ? $_REQUEST["CountrySelect"] : '';
$BetShopId = (is_numeric($_REQUEST["BetShopId"])) ? $_REQUEST["BetShopId"] : '';
$UserId = (is_numeric($_REQUEST["UserId"])) ? $_REQUEST["UserId"] : '';

/* asigna valores basados en parámetros de solicitud y verifica si son numéricos. */
$TypeDetail = ($_REQUEST["TypeDetail"] == "0") ? 0 : 1;
$TypeTotal = ($_REQUEST["Type"] == "0") ? 0 : 1;

$UserIdAgent = (is_numeric($_REQUEST["UserIdAgent"])) ? $_REQUEST["UserIdAgent"] : '';
$UserIdAgent2 = (is_numeric($_REQUEST["UserIdAgent2"])) ? $_REQUEST["UserIdAgent2"] : '';

$MaxRows = $_REQUEST["count"];

/* verifica si hay filas para procesar; de lo contrario, detiene la ejecución. */
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];
$seguir = true;

if ($SkeepRows == "" || $MaxRows == "") {
    $seguir = false;

}


/* Inicializa un arreglo vacío y una variable totalm en cero. */
$final = [];
$totalm = 0;

if (true) {


    /* Se inicializan fechas y se preparan reglas para filtrar datos. */
    $FromDateLocal = date("Y-m-d");
    $ToDateLocal = date("Y-m-d");

    $rules = [];

    //array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
    //array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));


    $grouping = "";

    /* Inicializa variables $SkeepRows y $OrderedItem si están vacías. */
    $select = "";

    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }

    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }


    /* asigna valores predeterminados a variables si no están definidas. */
    if ($MaxRows == "") {
        $MaxRows = 100;
    }


    if ($_REQUEST["UserId"] != "") {
        $BetShopId = $_REQUEST["UserId"];

    }

    /* Consulta SQL para obtener detalles de cheques y tickets según condiciones específicas. */
    $select = "  cheque.id,cheque.nro_cheque,case when cheque.origen='NR' then 'Nota Retiro' else 'Ticket' end origen,cheque.documento_id,case when cheque.origen='NR' then cuenta_cobro.valor else it_ticket_enc.vlr_premio end valor,case when cheque.origen='NR' then usuario.moneda when it_ticket_enc.tipo_beneficiario='RN' then usuariobeneficiario.moneda else registro_rapido.moneda end moneda,case when cheque.origen='NR' then usuario.nombre when usuariobeneficiario.nombre is null then concat(registro_rapido.nombre1,' ',registro_rapido.nombre2,' ',registro_rapido.apellido1,' ',registro_rapido.apellido2) else usuariobeneficiario.nombre end cliente,case when cheque.origen='NR' then registro.cedula when usuariobeneficiario.nombre is null then registro_rapido.cedula else registro.cedula end cedula,case when cheque.origen='NR' then cuenta_cobro.fecha_crea else concat(it_ticket_enc.fecha_crea,' ',it_ticket_enc.hora_crea) end fecha ";

    $paisId = $_SESSION["pais_id"];
    $usuarioId = $_SESSION["usuario"];


    $Cheque = new Cheque();


    /* Código para obtener y procesar información de cheques de un usuario específico. */
    $transacciones = $Cheque->getChequesCustom($usuarioId, $select, "cheque.id", "desc", $SkeepRows, $MaxRows, $json, true, $paisId);


    $transacciones = json_decode($transacciones);


    foreach ($transacciones->data as $key => $value) {
        $array = [];
        $array["Id"] = $value->{"cheque.nro_cheque"};
        $array["NroCheck"] = $value->{"cheque.nro_cheque"};
        $array["Client"] = $value->{".cliente"};
        $array["DocClient"] = $value->{".cedula"};
        $array["Date"] = $value->{".fecha"};
        $array["Origin"] = $value->{".origen"};
        $array["ReferenceDocument"] = $value->{"cheque.documento_id"};
        $array["Value"] = $value->{".valor"};
        $array["Currency"] = $value->{".moneda"};


        array_push($final, $array);


    }
}


/* Asigna valores a un array de respuesta: posición, conteo total y datos finales. */
$response["pos"] = $SkeepRows;
$response["total_count"] = $transacciones->count[0]->{".count"};
$response["data"] = $final;
