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
use Backend\dto\Departamento;
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
 * Client/GetClientWithdrawalRequests
 *
 * Obtener las solicitudes de retiro.
 *
 * @param object $params Objeto con los siguientes valores:
 * @param string $params ->ToDateLocal Fecha final en formato "Y-m-d H:i:s".
 * @param string $params ->FromDateLocal Fecha inicial en formato "Y-m-d H:i:s".
 * @param string $params ->BetShopId Identificador de la tienda de apuestas.
 * @param string $params ->ClientId Identificador del cliente.
 * @param string $params ->PaymentTypeId Identificador del tipo de pago.
 * @param string $params ->State Estado de la solicitud.
 * @param string $params ->WithDrawTypeId Tipo de retiro.
 * @param string $params ->ByAllowDate Indica si se filtra por fecha permitida.
 * @param string $params ->PlayerId Identificador del jugador.
 * @param string $params ->Ip Dirección IP del cliente.
 * @param string $params ->FromId Identificador del origen.
 * @param integer $params ->count Número máximo de registros a devolver.
 * @param integer $params ->start Índice inicial para la paginación.
 * @param string $params ->PaymentMethods Métodos de pago utilizados.
 * @param string $params ->PaymentMethodBankAccounts Cuentas bancarias asociadas al método de pago.
 * @param string $params ->Type Tipo de solicitud.
 * @param string $params ->CountrySelect País seleccionado.
 * @param string $params ->WithdrawId Identificador del retiro.
 * @param string $params ->ValueMinimum Valor mínimo del retiro.
 * @param string $params ->ValueMaximum Valor máximo del retiro.
 * @param string $params ->SystemId Identificador del sistema.
 * @param string $params ->dateDeleteFrom Fecha inicial de eliminación.
 * @param string $params ->dateDeleteTo Fecha final de eliminación.
 * @param string $params ->datePayFrom Fecha inicial de pago.
 * @param string $params ->datePayTo Fecha final de pago.
 * @param string $params ->ModifiedLocal Fecha de modificación inicial.
 * @param string $params ->ModifiedLocalEnd Fecha de modificación final.
 * @param string $params ->RiskStatus Estado de riesgo.
 * @param string $params ->BankId Identificador del banco.
 * @param string $params ->SubproviderId Identificador del sub
 * @param string $params ->ByAllowDate Indica si se filtra por fecha permitida.
 * @param string $params ->TypeUser Tipo de usuario.
 *
 *
 * @return array $response Respuesta con los siguientes valores:
 * - HasError (boolean): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta.
 * - AlertMessage (string): Mensaje de alerta.
 * - ModelErrors (array): Errores del modelo.
 * - Data (array): Datos de las solicitudes de retiro.
 * - pos (integer): Posición inicial de los datos devueltos.
 * - total_count (integer): Total de registros encontrados.
 *
 * @throws Exception Si ocurre un error general o de validación.
 */

/**
 * @OA\Post(path="apipv/Client/GetClientWithdrawalRequest", tags={"Client"}, description = "",
 *
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="json",
 *             @OA\Schema(required={},
 *               @OA\Property(
 *                   property="dateTo",
 *                   description="",
 *                   type="string",
 *                   example= "2020-09-25 23:59:59"
 *               ),
 *              @OA\Property(
 *                   property="dateFrom",
 *                   description="",
 *                   type="string",
 *                   example= "2020-09-25 00:00:00"
 *               ),
 *              @OA\Property(
 *                   property="BetShopId",
 *                   description="",
 *                   type="string",
 *                   example= ""
 *               ),
 *               @OA\Property(
 *                   property="ClientId",
 *                   description="ClientId",
 *                   type="string",
 *                   example= ""
 *               ),
 *                @OA\Property(
 *                   property="PaymentTypeId",
 *                   description="PaymentTypeId",
 *                   type="string",
 *                   example= ""
 *               ),
 *               @OA\Property(
 *                   property="State",
 *                   description="State",
 *                   type="string",
 *                   example= ""
 *               ),
 *               @OA\Property(
 *                   property="WithDrawTypeId",
 *                   description="WithDrawTypeId",
 *                   type="string",
 *                   example= ""
 *               ),
 *               @OA\Property(
 *                   property="ByAllowDate",
 *                   description="ByAllowDate",
 *                   type="string",
 *                   example= ""
 *               ),
 *               @OA\Property(
 *                   property="PlayerId",
 *                   description="PlayerId",
 *                   type="string",
 *                   example= ""
 *               ),
 *
 *               @OA\Property(
 *                   property="Ip",
 *                   description="Ip",
 *                   type="string",
 *                   example= ""
 *               ),
 *               @OA\Property(
 *                   property="FromId",
 *                   description="FromId",
 *                   type="string",
 *                   example= ""
 *               ),
 *               @OA\Property(
 *                   property="count",
 *                   description="count",
 *                   type="integer",
 *                   example= 2
 *               ),
 *
 *               @OA\Property(
 *                   property="start",
 *                   description="start",
 *                   type="integer",
 *                   example= 3
 *               ),
 *               @OA\Property(
 *                   property="PaymentMethods",
 *                   description="PaymentMethods",
 *                   type="string",
 *                   example= ""
 *               ),
 *               @OA\Property(
 *                   property="PaymentMethodBankAccounts",
 *                   description="PaymentMethodBankAccounts",
 *                   type="string",
 *                   example= ""
 *               ),
 *               @OA\Property(
 *                   property="Type",
 *                   description="Type",
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
 *                   property="WithdrawId",
 *                   description="WithdrawId",
 *                   type="string",
 *                   example= ""
 *               ),
 *               @OA\Property(
 *                   property="ValueMinimum",
 *                   description="ValueMinimum",
 *                   type="string",
 *                   example= ""
 *               ),
 *               @OA\Property(
 *                   property="ValueMaximum",
 *                   description="ValueMaximum",
 *                   type="string",
 *                   example= ""
 *               ),
 *               @OA\Property(
 *                   property="SystemId",
 *                   description="SystemId",
 *                   type="string",
 *                   example= ""
 *               ),
 *               @OA\Property(
 *                   property="dateDeleteFrom",
 *                   description="dateDeleteFrom",
 *                   type="string",
 *                   example= ""
 *               ),
 *               @OA\Property(
 *                   property="dateDeleteTo",
 *                   description="dateDeleteTo",
 *                   type="string",
 *                   example= ""
 *               ),
 *               @OA\Property(
 *                   property="datePayFrom",
 *                   description="datePayFrom",
 *                   type="string",
 *                   example= ""
 *               ),
 *               @OA\Property(
 *                   property="datePayTo",
 *                   description="datePayTo",
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
 *               ),     *
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

//error_reporting(E_ALL);
//ini_set('display_errors', 'ON');


/* Se inicializa una clase y se definen fechas y un ID de tienda de apuestas. */
$CuentaCobro = new CuentaCobro();


$ToDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->ToDateLocal) . ' +1 day'));
$FromDateLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->FromDateLocal)));
$BetShopId = $params->BetShopId;

/* asigna parámetros y convierte $ByAllowDate en un booleano. */
$ClientId = $params->ClientId;
$PaymentTypeId = $params->PaymentTypeId;
$State = $params->State;
$WithDrawTypeId = $params->WithDrawTypeId;
$ByAllowDate = $params->ByAllowDate;

$ByAllowDate = ($ByAllowDate == '1') ? 'true' : 'false';


/* convierte una fecha de entrada en un formato específico para uso posterior. */
$ToDateLocal = "";
$FromDateLocal = "";
if ($_REQUEST["dateFrom"] != "") {
    $FromDateLocal = date("Y-m-d 00:00:00", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"])));

}


/* asigna una fecha límite a partir de una entrada del usuario. */
if ($_REQUEST["dateTo"] != "") {
    $ToDateLocal = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"])));

}
$ToDateDeleteLocal = "";
$FromDateDeleteLocal = "";

/* establece fechas de inicio y fin a partir de solicitudes del usuario. */
if ($_REQUEST["dateDeleteFrom"] != "") {
    $FromDateDeleteLocal = date("Y-m-d 00:00:00", strtotime(str_replace(" - ", " ", $_REQUEST["dateDeleteFrom"])));

}

if ($_REQUEST["dateDeleteTo"] != "") {
    $ToDateDeleteLocal = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $_REQUEST["dateDeleteTo"])));

}

/* asigna fechas locales según el rango proporcionado en una solicitud. */
$ToDatePayLocal = "";
$FromDatePayLocal = "";
if ($_REQUEST["datePayFrom"] != "") {
    $FromDatePayLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $_REQUEST["datePayFrom"])));

}


/* procesa y convierte fechas desde solicitudes HTTP, ajustando formatos y zonas horarias. */
if ($_REQUEST["datePayTo"] != "") {
    $ToDatePayLocal = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $_REQUEST["datePayTo"])));
    //$ModifiedLocal = date("Y-m-d H:i:s", strtotime(str_replace("%20 ", " ", $_REQUEST["ModifiedLocal"]) . $timezone . ' hour '));
}

if ($_REQUEST["ModifiedLocal"] != "") {
    $FromDateDeleteLocal = "";
    $FromDatePayLocal = "";
    $ModifiedLocal = date("Y-m-d H:i:00", strtotime(str_replace(" - ", " ", $_REQUEST["ModifiedLocal"]) . $timezone . ' hour '));
    $ModifiedLocal = date("Y-m-d H:i:00", strtotime(str_replace("%20 ", " ", $_REQUEST["ModifiedLocal"]) . $timezone . ' hour '));
}


/* modifica y formatea una fecha basada en datos de entrada. */
if ($_REQUEST["ModifiedLocalEnd"] != "") {
    $ToDateDeleteLocal = "";
    $ToDatePayLocal = "";
    $ModifiedLocalEnd = date("Y-m-d H:i:59", strtotime(str_replace(" - ", " ", $_REQUEST["ModifiedLocalEnd"]) . $timezone . ' hour '));
    $ModifiedLocalEnd = date("Y-m-d H:i:59", strtotime(str_replace("%20 ", " ", $_REQUEST["ModifiedLocalEnd"]) . $timezone . ' hour '));
}

/* obtiene datos de entrada del usuario a través de solicitudes HTTP. */
$PlayerId = $_REQUEST["PlayerId"];
$Ip = $_REQUEST["Ip"];
$FromId = $_REQUEST["FromId"];
$MaxRows = $_REQUEST["count"];
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];

/* Validación de estado y asignación de variables desde la solicitud HTTP. */
$State = ($_REQUEST["State"] != 'A' && $_REQUEST["State"] != 'X' && $_REQUEST["State"] != 'I' && $_REQUEST["State"] != 'E' && $_REQUEST["State"] != 'R' && $_REQUEST["State"] != 'P' && $_REQUEST["State"] != 'PS' && $_REQUEST["State"] != 'AS' && $_REQUEST["State"] != 'RS' && $_REQUEST["State"] != 'D' && $_REQUEST["State"] != 'M' && $_REQUEST["State"] != 'X') ? '' : $_REQUEST["State"];
$PaymentMethods = $_REQUEST["PaymentMethods"];
$PaymentMethodBankAccounts = $_REQUEST["PaymentMethodBankAccounts"];
$TypeTotal = $_REQUEST["Type"];
$BetShopId = $_REQUEST["BetShopId"];
$ManagerId = $_REQUEST["ManagerId"];


/* obtiene parámetros de una solicitud HTTP para procesamiento posterior. */
$WithdrawId = $_REQUEST["WithdrawId"];
$SystemId = $_REQUEST["SystemId"];
$ValueMinimum = $_REQUEST["ValueMinimum"];
$ValueMaximum = $_REQUEST["ValueMaximum"];
$PaymentChannel = $_REQUEST["PaymentChannel"];
$Categorization = $_REQUEST["Categorization"];

/* recibe un parámetro y lo divide en elementos para consultas SQL. */
$RiskStatus = $_REQUEST["RiskStatus"];
// Suponiendo que esto es lo que recibes desde el front
$elementos = explode(",", $RiskStatus);  // Divide los valores si hay más de uno

// Si el valor tiene más de un elemento, creamos la condición "IN" para la consulta
$cantidad = count($elementos);

/* asigna un operador y mapea letras a números para clasificación de riesgos. */
if ($cantidad > 1) {
    $op = "in";  // Operador IN
} else {
    $op = "eq";  // Operador igual (=)
}

// Luego mapeamos las letras a los números 1, 2, 3
$RiskStatusArray = array(
    'B' => 1,  // B se convierte en 1
    'M' => 2,  // M se convierte en 2
    'A' => 3   // A se convierte en 3
);

// Si hay más de un elemento, los convertimos a los valores numéricos correspondientes

/* Convierte letras en números, filtra valores inválidos y los une en una cadena. */
if ($cantidad > 1) {
    $RiskStatus = array_map(function ($value) use ($RiskStatusArray) {
        return $RiskStatusArray[$value] ?? null;  // Convierte la letra a número, o null si no es válida
    }, $elementos);

    // Filtramos los valores nulos (en caso de que haya valores no válidos)
    $RiskStatus = array_filter($RiskStatus);
    $RiskStatus = implode(",", $RiskStatus);  // Convertimos el array a una cadena de números separados por comas
} else {
    /* Convierte un solo valor de riesgo a su equivalente numérico, si existe en el array. */

    // Si solo hay un valor, lo convertimos a su equivalente numérico
    $RiskStatus = $RiskStatusArray[$RiskStatus] ?? null;
}


/* captura y ajusta parámetros de solicitudes para procesar pasarelas de pago. */
$BankId = $_REQUEST["BankId"];
$SubproviderId = $_REQUEST["SubproviderId"]; // retornar pasarelas de pagos de cashout

$ByAllowDate = $_REQUEST["ByAllowDate"];

$ByAllowDate = ($ByAllowDate == '1') ? 'true' : 'false';


/* Se reciben parámetros de una solicitud, incluyendo país y configuraciones de retiro. */
$CountrySelect = $_REQUEST["CountrySelect"];

$WithdrawId = $_REQUEST["WithdrawId"];
$ValueMinimum = $_REQUEST["ValueMinimum"];
$ValueMaximum = $_REQUEST["ValueMaximum"];
$SystemId = $_REQUEST["SystemId"];

/* captura fechas y tipo de usuario desde una solicitud HTTP. */
$dateDeleteFrom = $_REQUEST["dateDeleteFrom"];
$dateDeleteTo = $_REQUEST["dateDeleteTo"];
$datePayFrom = $_REQUEST["datePayFrom"];
$datePayTo = $_REQUEST["datePayTo"];

$TypeUser = $_REQUEST["TypeUser"];


/* verifica condiciones y ajusta variables según la entrada proporcionada. */
$seguir = true;

if ($SkeepRows == "") {
    $seguir = false;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}


/* Verifica si $MaxRows está vacío; si es así, establece $seguir como falso. */
if ($MaxRows == "") {
    $seguir = false;
}

if ($seguir) {


    /* Configuración de reglas para filtrar datos por fechas en una cuenta de cobro. */
    $rules = [];
    //array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "I", "op" => "eq"));
    $daydimensionFechaPorPago = false;
    $daydimensionFechaPorAccion = false;


    if ($ByAllowDate == "false") {
        if ($FromDateLocal != "" && $WithdrawId == "" && $SystemId == "") {
            array_push($rules, array("field" => "cuenta_cobro.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
            array_push($rules, array("field" => "cuenta_cobro.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));

            //array_push($rules, array("field" => "time_dimension.timestampint", "data" => strtotime($FromDateLocal. ' '. 'America/Bogota'), "op" => "ge"));
            //array_push($rules, array("field" => "time_dimension.timestampint", "data" => strtotime($ToDateLocal. ' '. 'America/Bogota'), "op" => "le"));

        } else {


        }
    } else {
        /* Condicional que valida un ID y agrega reglas de fecha a un arreglo. */

        if ($WithdrawId == "") {
            array_push($rules, array("field" => "cuenta_cobro.fecha_pago", "data" => "$FromDateLocal ", "op" => "ge"));
            array_push($rules, array("field" => "cuenta_cobro.fecha_pago", "data" => "$ToDateLocal", "op" => "le"));
            $daydimensionFechaPorPago = true;
            //array_push($rules, array("field" => "time_dimension.timestampint", "data" => strtotime($FromDateLocal. ' '. 'America/Bogota'), "op" => "ge"));
            //array_push($rules, array("field" => "time_dimension.timestampint", "data" => strtotime($ToDateLocal. ' '. 'America/Bogota'), "op" => "le"));

        }

    }

    /* Se añaden reglas de fechas para filtrar registros de cobros y pagos. */
    if ($FromDateDeleteLocal != "" && $ToDateDeleteLocal != "") {
        //array_push($rules, array("field" => "cuenta_cobro.fecha_eliminacion", "data" => "$FromDateDeleteLocal ", "op" => "ge"));
        //array_push($rules, array("field" => "cuenta_cobro.fecha_eliminacion", "data" => "$ToDateDeleteLocal", "op" => "le"));
    }
    if ($FromDatePayLocal != "" && $ToDatePayLocal != "") {
        array_push($rules, array("field" => "cuenta_cobro.fecha_pago", "data" => date('Y-m-d 00:00:00', strtotime($FromDatePayLocal)), "op" => "ge"));
        array_push($rules, array("field" => "cuenta_cobro.fecha_pago", "data" => date('Y-m-d 23:59:59', strtotime($ToDatePayLocal)), "op" => "le"));
    }


    /* Agrega reglas a un arreglo basado en condiciones específicas de variables. */
    if ($ModifiedLocal != "") {

        array_push($rules, array("field" => "cuenta_cobro.fecha_cambio", "data" => "$ModifiedLocal", "op" => "ge"));
    }

    if ($Categorization != "") {
        array_push($rules, array("field" => "usuario.clave_tv", "data" => $Categorization, "op" => "eq"));
    }


    /* Condicionales que agregan reglas a un array basadas en ciertas variables. */
    if ($ModifiedLocalEnd != "") {

        array_push($rules, array("field" => "cuenta_cobro.fecha_cambio", "data" => "$ModifiedLocalEnd", "op" => "le"));
    }
    if ($BetShopId != "" && $BetShopId != "853460" && $BetShopId != "693978") {
        array_push($rules, array("field" => "cuenta_cobro.puntoventa_id", "data" => "$BetShopId", "op" => "eq"));
    }


    /* Agrega reglas basadas en condiciones de BetShopId y ClientId en un array. */
    if ($BetShopId == "853460" || $BetShopId == "693978") {
        array_push($rules, array("field" => "cuenta_cobro.mediopago_id", "data" => "$BetShopId", "op" => "eq"));
    }

    if ($ClientId != "") {
        array_push($rules, array("field" => "cuenta_cobro.usuario_id", "data" => "$ClientId", "op" => "eq"));
    }

    /* Agrega reglas condiciones basadas en BankId y ManagerId a un array. */
    if ($BankId != "") {
        array_push($rules, array("field" => "usuario_banco.banco_id", "data" => "$BankId", "op" => "eq"));
    }

    if ($ManagerId != "") {
        array_push($rules, array("field" => "cuenta_cobro.usucambio_id", "data" => $ManagerId, "op" => "eq"));
    }


    /* Añade condiciones al array "rules" si "RiskStatus" no está vacío. */
    if ($RiskStatus != "") {
        array_push($rules, array("field" => "cuenta_cobro.puntaje_jugador", "data" => $RiskStatus, "op" => "$op"));
    }

    if ($State != "") {

        /* verifica y agrega reglas según el estado especificado en una lista. */
        if ($State != 'A' && $State != 'I' && $State != 'E' && $State != 'R' && $State != 'P' && $State != 'X' && $State != 'M') {
            if ($State == "PS") {
                array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "S", "op" => "eq"));
                array_push($rules, array("field" => "cuenta_cobro.transproducto_id", "data" => "S", "op" => "nn"));
            }
            if ($State == "AS") {
                array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "I", "op" => "eq"));
                array_push($rules, array("field" => "cuenta_cobro.transproducto_id", "data" => "S", "op" => "nn"));
            }
            if ($State == "RS") {
                array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "R", "op" => "eq"));
                array_push($rules, array("field" => "cuenta_cobro.transproducto_id", "data" => "S", "op" => "nn"));
            }
            if ($State == "D") {
                array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "D", "op" => "eq"));
                $daydimensionFechaPorAccion = true;
            }

        } else {
            /* Agrega reglas basadas en el estado y tipo total de 'cuenta_cobro'. */

            if ($State != "I") {
                array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "$State", "op" => "eq"));
            } else {
                if ($TypeTotal == "0" || $TypeTotal == "") {
                    array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "$State", "op" => "eq"));
                }
            }

        }
    }


    /* Agrega reglas a un array si las variables no están vacías. */
    if (!empty($SystemId)) {
        array_push($rules, ['field' => 'cuenta_cobro.mediopago_id', 'data' => $SystemId, 'op' => 'eq']);
    }

    if (!empty($datePayFrom)) {
        array_push($rules, ['field' => 'cuenta_cobro.fecha_pago', 'data' => date('Y-m-d 00:00:00'), 'op' => 'ge']);
    }


    /* Agrega reglas condicionadas sobre fechas de pago y eliminación en un arreglo. */
    if (!empty($datePayTo)) {
        array_push($rules, ['field' => 'cuenta_cobro.fecha_pago', 'data' => date('Y-m-d 23:59:59'), 'op' => 'le']);
    }

    if (!empty($dateDeleteFrom)) {
        $timestamp = strtotime($dateDeleteFrom);
        if ($timestamp != -1) {

            array_push($rules, ['field' => 'cuenta_cobro.fecha_eliminacion', 'data' => date('Y-m-d 00:00:00', $timestamp), 'op' => 'ge']);
        }
    }


    /* Condiciona la adición de una regla basada en una fecha de eliminación no vacía. */
    if (!empty($dateDeleteTo)) {
        $timestamp = strtotime($dateDeleteFrom);
        if ($timestamp != -1) {
            array_push($rules, ['field' => 'cuenta_cobro.fecha_eliminacion', 'data' => date('Y-m-d 23:59:59', $timestamp), 'op' => 'le']);
        }

    }


    /* Agrega reglas de filtrado si WithdrawId o BankId no están vacíos. */
    if (!empty($WithdrawId)) {
        array_push($rules, ['field' => 'cuenta_cobro.cuenta_id', 'data' => $WithdrawId, 'op' => 'eq']);
    }

    if (!empty($BankId)) {
        array_push($rules, ['field' => 'banco.banco_id', 'data' => $BankId, 'op' => 'eq']);
    }


    /* Se agregan reglas de filtrado basadas en condiciones de $SubproviderId y $BetShopId. */
    if (!empty($SubproviderId)) {
        array_push($rules, ['field' => 'pr.subproveedor_id', 'data' => $SubproviderId, 'op' => 'eq']);
        array_push($rules, ['field' => 'banco_detalle.estado', 'data' => 'A', 'op' => 'eq']);
    }

    if ($BetShopId != "") {
        array_push($rules, array("field" => "cuenta_cobro.puntoventa_id", "data" => "$BetShopId", "op" => "eq"));
    }


    /* agrega reglas para filtrar datos según condiciones específicas. */
    if ($ClientId != "") {
        array_push($rules, array("field" => "cuenta_cobro.usuario_id", "data" => "$ClientId", "op" => "eq"));
    }

    if (!empty($ValueMinimum)) {
        array_push($rules, ['field' => 'cuenta_cobro.valor', 'data' => $ValueMinimum, 'op' => 'ge']);
    }


    /* Agrega una regla si $ValueMaximum no está vacío, para comparación en la validación. */
    if (!empty($ValueMaximum)) {
        array_push($rules, ['field' => 'cuenta_cobro.valor', 'data' => $ValueMaximum, 'op' => 'le']);
    }


    if ($State != "") {

        /* valida el estado y agrega reglas a un arreglo según condiciones específicas. */
        if ($State != 'A' && $State != 'I' && $State != 'E' && $State != 'R' && $State != 'P' && $State != 'X' && $State != 'M') {
            if ($State == "PS") {
                array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "S", "op" => "eq"));
                array_push($rules, array("field" => "cuenta_cobro.transproducto_id", "data" => "S", "op" => "nn"));
            }
            if ($State == "AS") {
                array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "I", "op" => "eq"));
                array_push($rules, array("field" => "cuenta_cobro.transproducto_id", "data" => "S", "op" => "nn"));
            }
            if ($State == "RS") {
                array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "R", "op" => "eq"));
                array_push($rules, array("field" => "cuenta_cobro.transproducto_id", "data" => "S", "op" => "nn"));
            }
            if ($State == "D") {
                array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "D", "op" => "eq"));
                $daydimensionFechaPorAccion = true;
            }

        } else {
            /* Añade reglas a la matriz según el estado y tipo especificado. */

            if ($State != "I") {
                array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "$State", "op" => "eq"));
            } else {
                if ($TypeTotal == "0" || $TypeTotal == "") {
                    array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "$State", "op" => "eq"));
                }
            }

        }
    }


    /* Condiciona reglas según el tipo de retiro, modificando el medio de pago. */
    if ($WithDrawTypeId == "1") {
        array_push($rules, array("field" => "cuenta_cobro.mediopago_id", "data" => "0", "op" => "eq"));

    } elseif ($WithDrawTypeId == "2") {

        array_push($rules, array("field" => "cuenta_cobro.mediopago_id", "data" => "0", "op" => "ne"));

    }


    /* Añade reglas a un array basado en valores de PlayerId e Ip. */
    if ($PlayerId != "") {
        array_push($rules, array("field" => "cuenta_cobro.usuario_id", "data" => "$PlayerId", "op" => "eq"));

    }

    if ($Ip != "") {
        array_push($rules, array("field" => "cuenta_cobro.dir_ip", "data" => "$Ip", "op" => "cn"));

    }

    if ($PaymentMethods != "" && $PaymentMethods != 0) {
        switch ($PaymentMethods) {
            case 1:
                /* Añade una regla de comparación para "mediopago_id" en un array de reglas. */

                array_push($rules, array("field" => "cuenta_cobro.mediopago_id", "data" => "0", "op" => "eq"));

                break;

            case 2:
                /* Añade reglas de validación para campos específicos en un array. */


                array_push($rules, array("field" => "cuenta_cobro.mediopago_id", "data" => "0", "op" => "ne"));
                array_push($rules, array("field" => "cuenta_cobro.mediopago_id", "data" => "47", "op" => "ne"));
                array_push($rules, array("field" => "cuenta_cobro.version", "data" => "1", "op" => "eq"));

                break;

            case 3:
                /* Se agregan reglas para filtrar datos de "cuenta_cobro" en un array. */


                array_push($rules, array("field" => "cuenta_cobro.mediopago_id", "data" => "47", "op" => "eq"));
                array_push($rules, array("field" => "cuenta_cobro.version", "data" => "1", "op" => "eq"));

                break;
        }
    }


    /* Condicional que agrega reglas para cuentas de cobro según el método de pago. */
    if ($PaymentMethodBankAccounts == "1") {
        array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "I", "op" => "eq"));
        array_push($rules, array("field" => "cuenta_cobro.puntoventa_id", "data" => "0", "op" => "eq"));
        array_push($rules, array("field" => "cuenta_cobro.transproducto_id", "data" => "0", "op" => "eq"));

    }


    /* agrega reglas basadas en condiciones de métodos de pago y retiro. */
    if ($PaymentMethodBankAccounts == "2") {
        array_push($rules, array("field" => "cuenta_cobro.transproducto_id", "data" => "0", "op" => "ne"));
    }


    if ($WithdrawId != "") {
        array_push($rules, array("field" => "cuenta_cobro.cuenta_id", "data" => $WithdrawId, "op" => "eq"));

    }


    /* Agrega reglas de filtrado a un array basado en condiciones específicas. */
    if ($SystemId != "") {
        array_push($rules, array("field" => "cuenta_cobro.transproducto_id", "data" => $SystemId, "op" => "eq"));

    }
    if ($ValueMinimum != "") {
        array_push($rules, array("field" => "cuenta_cobro.valor", "data" => $ValueMinimum, "op" => "ge"));

    }

    /* Agrega reglas a un arreglo basado en condiciones de valores y pagos. */
    if ($ValueMaximum != "") {
        array_push($rules, array("field" => "cuenta_cobro.valor", "data" => $ValueMaximum, "op" => "le"));

    }


    if ($PaymentChannel != "") {


        array_push($rules, array("field" => "usuario_banco_puntoventa.login", "data" => $PaymentChannel, "op" => "cn"));

    }


    /* Consulta SQL para seleccionar y agrupar información financiera de usuarios y cuentas. */
    $select = " usuario.pais_id,usuario.clave_tv,registro.direccion,usuario_banco_puntoventa.nombre,cuenta_cobro.version,cuenta_cobro.fecha_cambio,cuenta_cobro.dirip_cambio,cuenta_cobro.fecha_accion,cuenta_cobro.dirip_accion, cuenta_cobro.fecha_pago,ciudad.*,departamento.*,usuario_banco.cuenta,usuario_banco.codigo,usuario_banco.tipo_cliente,usuario_banco.tipo_cuenta,usuario_punto.login,usuario_punto.nombre,cuenta_cobro.cuenta_id,cuenta_cobro.usuario_id,usuario.nombre,cuenta_cobro.usucambio_id,cuenta_cobro.observacion,cuenta_cobro.mensaje_usuario,usuario.login,cuenta_cobro.fecha_crea,cuenta_cobro.valor,cuenta_cobro.mediopago_id,usuario.moneda,usuario.verifcedula_ant,usuario.verifcedula_post,cuenta_cobro.puntoventa_id,punto_venta.descripcion puntoventa,cuenta_cobro.mediopago_id, banco.descripcion banco_nombre,cuenta_cobro.estado,usuario_banco.cuenta,cuenta_cobro.dir_ip,cuenta_cobro.transproducto_id,registro.cedula,registro.celular,registro.tipo_doc,banco.producto_pago,cuenta_cobro.impuesto,cuenta_cobro.impuesto2,usuario_banco.banco_id,banco.tipo, cuenta_cobro.producto_pago_id,cuenta_cobro.puntaje_jugador,transaccion_producto.externo_id,data_completa2.monto_ultimo_retiro,data_completa2.monto_ultimo_deposito";
    $grouping = "cuenta_cobro.cuenta_id";

    if ($TypeTotal == 1) {
        $select = "SUM(cuenta_cobro.valor) valor,pais.iso,cuenta_cobro.estado";

        $grouping = "cuenta_cobro.estado,pais.pais_id";
    }


    /* Se seleccionan campos de varias tablas según el tipo de usuario y condiciones. */
    if ($TypeUser == 1) {

        $select = "punto_venta.*,cuenta_cobro.version,cuenta_cobro.producto_pago_id,usuario_perfil.perfil_id,registro.direccion,usuario_banco_puntoventa.nombre,cuenta_cobro.fecha_cambio, cuenta_cobro.dirip_cambio,cuenta_cobro.fecha_accion,cuenta_cobro.dirip_accion, cuenta_cobro.fecha_pago,ciudad.*,departamento.*,usuario_banco.cuenta,usuario_banco.codigo,usuario_banco.tipo_cliente,usuario_banco.tipo_cuenta,usuario_punto.login,usuario_punto.nombre,cuenta_cobro.cuenta_id,cuenta_cobro.usuario_id,usuario.nombre,cuenta_cobro.usucambio_id,cuenta_cobro.observacion,cuenta_cobro.mensaje_usuario,usuario.login,cuenta_cobro.fecha_crea,cuenta_cobro.valor,cuenta_cobro.mediopago_id,usuario.moneda,usuario.verifcedula_ant,usuario.verifcedula_post,cuenta_cobro.puntoventa_id,punto_venta.descripcion puntoventa,cuenta_cobro.mediopago_id, banco.descripcion banco_nombre,banco.tipo, cuenta_cobro.estado,usuario_banco.cuenta,cuenta_cobro.dir_ip,cuenta_cobro.transproducto_id,registro.cedula,registro.celular,registro.tipo_doc,banco.producto_pago,cuenta_cobro.impuesto,cuenta_cobro.impuesto2,usuario_banco.banco_id,cuenta_cobro.factura,cuenta_cobro.producto_pago_id,transaccion_producto.externo_id,usuario.clave_tv,cuenta_cobro.producto_pago_id,cuenta_cobro.puntaje_jugador";
    }

    if ($_SESSION["PaisCondS"] != '' && $_SESSION["PaisCondS"] != '0') {
        array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION["PaisCondS"], "op" => "eq"));
    } else {
        /* Condicional que agrega reglas según el país del usuario o selección. */


        // Si el usuario esta condicionado por País
        if ($_SESSION['PaisCond'] == "S") {
            array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
        } else {

            if ($CountrySelect != "" && is_numeric($CountrySelect)) {
                array_push($rules, array("field" => "usuario.pais_id", "data" => $CountrySelect, "op" => "eq"));
            }

        }

    }

    // Si el usuario esta condicionado por el mandante y no es de Global

    /* añade reglas basadas en la sesión del usuario actual. */
    if ($_SESSION['Global'] == "N") {
        array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
    } else {

        if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
            array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
        }

    }

    // Inactivamos reportes para el país Colombia
    //array_push($rules, array("field" => "usuario.pais_id", "data" => "1", "op" => "ne"));


    /* asigna reglas según el perfil del usuario basado en su ID. */
    if ($FromId != "") {

        $UsuarioPerfil = new UsuarioPerfil($FromId, "");

        if ($UsuarioPerfil->perfilId == "PUNTOVENTA") {
            array_push($rules, array("field" => "usuario_punto.puntoventa_id", "data" => "$FromId", "op" => "eq"));

        } else if ($UsuarioPerfil->perfilId == 'AFILIADOR') {
            array_push($rules, array("field" => "cuenta_cobro.usuario_id", "data" => "$FromId", "op" => "eq"));
        } else {
            array_push($rules, array("field" => "cuenta_cobro.puntoventa_id", "data" => "$FromId", "op" => "eq"));
        }
        //array_push($rules, array("field" => "punto_venta.puntoventa_id", "data" => "$FromIdGetBetHistory", "op" => "eq"));
    }


    /* Agrega reglas según el perfil del usuario en la sesión activa. */
    if ($_SESSION["win_perfil2"] == "CAJERO") {

        array_push($rules, array("field" => "cuenta_cobro.puntoventa_id", "data" => $_SESSION["usuario"], "op" => "eq"));
    }

    if ($_SESSION["win_perfil2"] == "PUNTOVENTA") {

        array_push($rules, array("field" => "usuario_punto.puntoventa_id", "data" => $_SESSION["usuario"], "op" => "eq"));
    }


    /* Verifica el perfil de usuario y agrega reglas a un array basado en condiciones. */
    if ($_SESSION['consultaAgente'] != "0" && $_SESSION['consultaAgente'] != null) {

        $UsuarioPerfil = new UsuarioPerfil($_SESSION['consultaAgente']);

        if ($UsuarioPerfil->perfilId == "CONCESIONARIO2") {

            array_push($rules, array("field" => "concesionario.usupadre2_id", "data" => $_SESSION['consultaAgente'], "op" => "eq"));

        } else if ($UsuarioPerfil->perfilId == "CONCESIONARIO") {

            array_push($rules, array("field" => "concesionario.usupadre_id", "data" => $_SESSION['consultaAgente'], "op" => "eq"));

        }
    }


    /* Condicional que agrega reglas según el tipo de usuario. */
    if ($TypeUser == 1) {
        array_push($rules, array("field" => "usuario.puntoventa_id", "data" => "", "0" => "ne"));

    } else {
        //array_push($rules, array("field" => "registro.usuario_id", "data" => "0", "op" => "ne"));

    }


    /* Crea un filtro en JSON y ajusta la opción de conteo según el tipo. */
    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);

    $withCount = true;

    if ($TypeTotal == 1) {
        $withCount = false;

    }


    /* Selecciona cuentas según el tipo de usuario y las procesa en JSON. */
    if ($TypeUser == 1) {
        $cuentas = $CuentaCobro->getCuentasCobroPuntoVentaCustom($select, "", "", $SkeepRows, $MaxRows, $json, true, $grouping, '', true, $daydimensionFechaPorPago);

        $cuentas = json_decode($cuentas);
    } else {
        // Llama a la función para cuentas de cobro estándar
        $cuentas = $CuentaCobro->getCuentasCobroCustom($select, "", "", $SkeepRows, $MaxRows, $json, true, $grouping, '', $withCount, $daydimensionFechaPorPago, true, $daydimensionFechaPorAccion);

        $cuentas = json_decode($cuentas);
    }

    //Consultando tipo para fecha de expiración del documento

    /* Se crea un clasificador para fechas de expiración y se inicializan variables para almacenar resultados. */
    $ClasificadorDocExpira = new Clasificador('', 'EXPIRYDATE');

    $final = array(); // Array que se utilizará para almacenar los resultados finales.
    $accumulatedStats = []; // Array que almacena estadísticas acumuladas.
    foreach ($cuentas->data as $key => $value) {


        /* Se inicializa una variable como un arreglo vacío en PHP. */
        $array = [];
        if ($TypeTotal == 0) {

            $array["Id"] = $value->{"cuenta_cobro.cuenta_id"};
            $array["ClientId"] = $value->{"cuenta_cobro.usuario_id"};
            $array["ClientLogin"] = $value->{"usuario.login"};
            $array["Email"] = $value->{"usuario.login"};
            $array["Phone"] = $value->{"registro.celular"};
            $array["ClientName"] = $value->{"usuario.nombre"};
            $array["RequestTime"] = $value->{"cuenta_cobro.fecha_crea"};
            $array["CreatedLocal"] = $value->{"cuenta_cobro.fecha_crea"};
            $array["ModifiedLocal"] = $value->{"cuenta_cobro.fecha_crea"};
            $array["City"] = $value->{"ciudad.ciudad_nom"};
            $array["Department"] = $value->{"departamento.depto_nom"};
            $array["WayPay"] = "Cuenta Bancaria";
            $array["DigitalBankAccount"] = $value->{"banco.tipo"} == "Digital" ? $value->{"usuario_banco.cuenta"} : $value->{"cuenta_cobro.usuario_id"};
            $array["AccountBank"] = $value->{"usuario_banco.cuenta"};
            $array["CodeInterbank"] = $value->{"usuario_banco.codigo"};
            $array["Categorization"] = $value->{"usuario.clave_tv"};
            $array["Address"] = $value->{"registro.direccion"};
            switch ($value->{"cuenta_cobro.puntaje_jugador"}) {
                case 1:
                    $array["RiskStatus"] = 'B';
                    break;
                case 2:
                    $array["RiskStatus"] = 'M';
                    break;
                case 3:
                    $array["RiskStatus"] = 'A';
                    break;
                default:
                    $array["RiskStatus"] = null;
                    break;
            }

            if ($value->{"cuenta_cobro.factura"} != "") {
                $array["Invoice"] = true; // Indica que hay factura
            } else {
                $array["Invoice"] = false; // Indica que no hay factura
            }

            if ($value->{"cuenta_cobro.producto_pago_id"} != '' && $value->{"cuenta_cobro.producto_pago_id"} != 0) {
                $array["AccountBank"] = "UserAgent";
            }

            $array["Type"] = $value->{"usuario_banco.tipo_cliente"};
            if (strpos($array["Type"], ' - ') !== false) {
                $array["BranchBank"] = explode(' - ', $value->{"usuario_banco.tipo_cliente"})[1];

            }
            if ($array["Type"] == 'PERSONA') {
                $array["Type"] = '';
            }
            $array["VerifdniPost"] = $value->{"usuario.verifcedula_ant"};
            $array["VerifdniAnt"] = $value->{"usuario.verifcedula_post"};
            $array["DNI"] = ($value->{"usuario.verifcedula_post"} == 'S' && $value->{"usuario.verifcedula_post"} == 'S') ? 'S' : 'N';
            $array["PaymentDate"] = $value->{"cuenta_cobro.fecha_pago"};
            $array["Identification"] = $value->{"registro.cedula"};
            $array["TypeDocument"] = $value->{"registro.tipo_doc"};

            // Cambio de identificación para tipo de usuario 1
            if ($TypeUser == 1) {
                $array["Identification"] = $value->{"punto_venta.cedula"};
            }

            $array["MobilePhone"] = $value->{"registro.celular"};
            $array["Currency"] = $value->{"usuario.moneda"};

            if ($array["AccountBank"] == "" && $value->{"cuenta_cobro.producto_pago_id"} == 0 && $value->{"cuenta_cobro.mediopago_id"} == 0) {

                // $array["AccountBank"] = 'PuntoVenta';
            }

            // Si la cuenta bancaria está vacía, se establece como cadena vacía
            if ($array["AccountBank"] == "") {
                $array["AccountBank"] = '';
            }

// Asignación de tipo de documento en función de su valor original
            if ($array["TypeDocument"] == "C") {
                $array["TypeDocument"] = "DNI";
            }

            if ($array["TypeDocument"] == "E") {
                $array["TypeDocument"] = "DNI Extranjeria ";
            }

            if ($array["TypeDocument"] == "P") {
                $array["TypeDocument"] = "Pasaporte";

            }

            // Traducción del tipo de documento si el idioma de la sesión es inglés
            if (strtolower($_SESSION["idioma"]) == "en") {
                $array["TypeDocument"] = str_replace("DNI", "National ID", $array["TypeDocument"]);
                $array["TypeDocument"] = str_replace("Pasaporte", "Passport", $array["TypeDocument"]);
                $array["TypeDocument"] = str_replace("DNI extranjero", "Foreigner ID", $array["TypeDocument"]);
            }

            if ($value->{"usuario.pais_id"} == '94') {
                if ($array["TypeDocument"] == "DNI") {
                    $array["TypeDocument"] = "DIP";
                }

                if ($array["TypeDocument"] == "DNI Extranjeria") {
                    $array["TypeDocument"] = "DPI Extranjero domiciliado";
                }

                if ($array["TypeDocument"] == "P") {
                    $array["TypeDocument"] = "Pasaporte";

                }
            }

            /**
             * Si el tipo de usuario es '1', se extraen y asignan varios datos al arreglo.
             * Incluye teléfono, dirección, nombre del contacto, login del usuario y correo electrónico.
             * Dependiendo de la versión de cuenta de cobro, se asigna un tipo de movimiento específico.
             */
            if ($TypeUser == '1') {
                $array["MobilePhone"] = $value->{"punto_venta.telefono"};
                $array["Phone"] = $value->{"punto_venta.telefono"};
                $array["Address"] = $value->{"punto_venta.direccion"};

                //$PuntoVenta = new PuntoVenta('',$value->{"punto_venta.usuario_id"});
                $Ciudad = new Ciudad($value->{"punto_venta.ciudad_id"});

                $array["ClientName"] = $value->{"punto_venta.nombre_contacto"};
                $array["ClientLogin"] = $value->{"usuario.login"};
                $array["Email"] = $value->{"punto_venta.email"};
                $array["City"] = $Ciudad->ciudadNom;
                $array["Department"] = '';


                $array["TypeDocument"] = "DNI";

                if ($value->{"cuenta_cobro.version"} == '3') {
                    $array["Type"] = 'Saldo Recargas';
                }
                if ($value->{"cuenta_cobro.version"} == '4') {
                    $array["Type"] = 'Saldo Premios';
                }

            }

            $array["TypeAccount"] = "";

            if ($value->{"cuenta_cobro.estado"} != "A") {

                $array["PaymentMethod"] = "Punto de venta";


                if (strtolower($_SESSION["idioma"]) == "en") {
                    $array["PaymentMethod"] = "BetShop";
                }

                if ($value->{"cuenta_cobro.transproducto_id"} == "0" && $value->{"cuenta_cobro.mediopago_id"} != "0") {
                    $array["PaymentMethod"] = "Fisicamente";

                    if (strtolower($_SESSION["idioma"]) == "en") {
                        $array["PaymentMethod"] = "Physically";
                    }
                }


                if ($value->{"cuenta_cobro.transproducto_id"} != "0") {
                    $array["PaymentMethod"] = "Sistema";

                    if (strtolower($_SESSION["idioma"]) == "en") {
                        $array["PaymentMethod"] = "System";
                    }
                }


            }
            if ($value->{"cuenta_cobro.mediopago_id"} != "0") {
                // Si el tipo de cuenta es 'Crypto', se asigna 'Criptomoneda', de lo contrario se asigna el tipo de cuenta
                $array["TypeAccount"] = $value->{"usuario_banco.tipo_cuenta"} == "Crypto" ? 'Criptomoneda' : $value->{"usuario_banco.tipo_cuenta"};
            }

            /**
             * Comienza a procesar la información del usuario y su cuenta bancaria.
             * Si el tipo de cuenta es '0' (Ahorros) o '1' (Corriente), se determina el tipo de cuenta correspondiente.
             */
            if ($value->{"usuario_banco.tipo_cuenta"} == '0' || $value->{"usuario_banco.tipo_cuenta"} == '1' || $value->{"usuario_banco.tipo_cuenta"} == 'Digital') {

                switch ($value->{"usuario_banco.tipo_cuenta"}) {
                    case 0:
                        $array["TypeAccount"] = "Ahorros";
                        break;

                    case 1:
                        $array["TypeAccount"] = "Corriente";

                        break;

                    case 'Ahorros':
                        $array["TypeAccount"] = "Ahorros";
                        break;

                    case 'Corriente':
                        $array["TypeAccount"] = "Corriente";

                        break;
                    case 'Digital':
                        $array["TypeAccount"] = $value->{"usuario_banco.tipo_cuenta"};
                        $array["PaymentMethod"] = $value->{"usuario_banco.tipo_cuenta"};
                }
            }

            /**
             * Se verifica el idioma del usuario y se asigna el nombre correspondiente para el tipo de cuenta en inglés.
             */
            if (strtolower($_SESSION["idioma"]) == "en") {
                if ($array["TypeAccount"] == "Ahorros") {
                    $array["TypeAccount"] = "Savings account";
                }
                if ($array["TypeAccount"] == "Corriente") {
                    $array["TypeAccount"] = "Current Account";
                }
            }

            // Asigna el valor de la cuenta de cobro al array
            $array["Amount"] = $value->{"cuenta_cobro.valor"};
            $array["Tax1"] = doubleval($value->{"cuenta_cobro.impuesto"});
            $array["Tax2"] = doubleval($value->{"cuenta_cobro.impuesto2"});
            $array["FinalAmount"] = round(doubleval($value->{"cuenta_cobro.valor"}) - doubleval($value->{"cuenta_cobro.impuesto"}) - doubleval($value->{"cuenta_cobro.impuesto2"}), 2);

            $nombreMetodoPago = 'Efectivo';
            if (strtolower($_SESSION["idioma"]) == "en") {
                $nombreMetodoPago = 'Cash';
            }

            // ID del método de pago
            $idMetodoPago = 0;

            $estado = 'Pendiente de Pago';
            $array["Action"] = "None";

            // Cambia el estado a inglés si es necesario
            if (strtolower($_SESSION["idioma"]) == "en") {
                $estado = 'Pending payment'; // Traduce "Pendiente de Pago" a "Pending payment"
            }


            if ($value->{"cuenta_cobro.estado"} == "I") {
                $estado = 'Pagado';

                if (strtolower($_SESSION["idioma"]) == "en") {
                    $estado = 'Paid';
                }
                //$array["Action"] = "";

            } elseif ($value->{"cuenta_cobro.estado"} == "R") {
                $estado = 'Rechazado';

                if (strtolower($_SESSION["idioma"]) == "en") {
                    $estado = 'Rejected';
                }
                //$array["Action"] = "";

            } elseif ($value->{"cuenta_cobro.estado"} == "A") {
                $estado = 'Activo';

                if (strtolower($_SESSION["idioma"]) == "en") {
                    $estado = 'Active';
                }


                //$array["Action"] = "";
            } elseif ($value->{"cuenta_cobro.estado"} == "P") {
                $estado = 'Pendiente';

                $array["Action"] = "Aprobar";

                if (strtolower($_SESSION["idioma"]) == "en") {
                    $estado = 'Pending';
                    $array["Action"] = "Approve";
                }
            } elseif ($value->{"cuenta_cobro.estado"} == "E") {
                $estado = 'Eliminado';

                $array["Action"] = "None";

                if (strtolower($_SESSION["idioma"]) == "en") {
                    $estado = 'Deleted';
                }
            } elseif ($value->{"cuenta_cobro.estado"} == "X") {

                $estado = 'Procesando';

                // Inicializa la acción en el array como “None”
                $array["Action"] = "None";

                if (strtolower($_SESSION["idioma"]) == "en") {
                    $estado = 'Processing';
                }
            }
            if ($State == "I") {
                $estado = 'Pagada';
            }
            if ($value->{"cuenta_cobro.producto_pago_id"} == 18625) {

                if ($value->{"cuenta_cobro.estado"} == "S") {
                    $array["CancelPayment"] = true;
                    $array["ResendKey"] = true;
                    $array["AccountBank"] = "";
                } else {
                    $array["CancelPayment"] = false;
                    $array["ResendKey"] = false;
                }

            }

            if ($value->{"banco.banco_nombre"} != '') {

                if ($value->{"banco.tipo"} != 'Digital') {
                    $nombreMetodoPago = $value->{"banco.banco_nombre"} . " - " . $value->{"usuario_banco.cuenta"};
                }
                else {
                    $nombreMetodoPago = $value->{"banco.banco_nombre"};
                }

                // Obtiene el ID del banco del usuario
                $CodeBank = $value->{"usuario_banco.banco_id"};

                // Si no hay cuenta bancaria establecida, fija la cuenta como 'Giro Bancario'
                if ($array["AccountBank"] == "") {
                    $array["AccountBank"] = "Giro Bancario";
                }

                // Si la cuenta bancaria sigue vacía y el idioma es inglés, establece como 'Bank Draft'
                if ($array["AccountBank"] == "" && strtolower($_SESSION["idioma"]) == "en") {
                    $array["AccountBank"] = "Bank Draft";
                }
            }

            if ($TypeUser == '0') {

                if ($value->{"usuario_banco_puntoventa.nombre"} != '') {
                    $array["PaymentMethod"] = $value->{"usuario_banco_puntoventa.nombre"};
                }
            }

            // Si la cuenta bancaria sigue vacía, se asegura de que sea una cadena vacía
            if ($array["AccountBank"] == "") {
                $array["AccountBank"] = '';
            }

            // Verifica el ID de la cuenta y asigna el banco correspondiente
            if ($array["Id"] == "6549157") {
                $array["AccountBank"] = 'Kasnet';
                $array["AccountBank"] = "UserAgent";

            }

            // Verifica el ID del producto de pago
            if ($value->{"cuenta_cobro.producto_pago_id"} == "18625") {
                $array["AccountBank"] = 'Kasnet';
                $array["AccountBank"] = "UserAgent";

            }

// Verifica si el ID del producto de pago no está vacío ni es 0
            if ($value->{"cuenta_cobro.producto_pago_id"} != "" && $value->{"cuenta_cobro.producto_pago_id"} != "0") {
                $CodeBank = ''; // Inicializa CodeBank como vacío
            }

// Asigna el método de pago y el código del banco al array
            $array["WayPay"] = $nombreMetodoPago; // Asigna el nombre del método de pago
            $array["CodeBank"] = $CodeBank; // Asigna el código del banco

// Verifica si el ID del método de pago no está vacío
            if ($value->{"cuenta_cobro.metodopago_id"} != '') {
                $idMetodoPago = $value->{"cuenta_cobro.metodopago_id"};
            }

            $array["PaymentChannel"] = ''; // Inicializa PaymentChannel como vacío

            // Verifica la versión y el ID del medio de pago
            if ($value->{"cuenta_cobro.version"} == '2' && $value->{"cuenta_cobro.mediopago_id"} != '' && $value->{"cuenta_cobro.mediopago_id"} != '0') {
                try {
                    $UsuarioRed = new Usuario($value->{"cuenta_cobro.mediopago_id"});
                    $array["PaymentChannel"] = $UsuarioRed->nombre;

                } catch (Exception $e) {

                }
            }

            // Verifica la versión y el ID del transproducto
            if ($value->{"cuenta_cobro.version"} == '1' && $value->{"cuenta_cobro.transproducto_id"} != '') {
                //$TransProd = new TransaccionProducto($value->{"cuenta_cobro.transproducto_id"});
                //$Producto = new Producto($TransProd->productoId);
                //$Proveedor = new Proveedor($Producto->proveedorId);
                //$array["PaymentChannel"] = $Proveedor->descripcion;
            }
            $array["PaymentSystemName"] = $nombreMetodoPago;
            $array["PaymentSystemId"] = $idMetodoPago;
            $array["TypeName"] = "Payment";

            $array["CurrencyId"] = $value->{"cuenta_cobro.moneda"};
            $array["CashDeskId"] = $value->{"cuenta_cobro.puntoventa_id"};
            $array["CashDeskId"] = $value->{"cuenta_cobro.puntoventa_id"};
            $array["BetshopId"] = $value->{"cuenta_cobro.puntoventa_id"};
            $array["BetShopName"] = $value->{"usuario_punto.nombre"};
            $array["BetShop"] = $value->{"usuario_punto.nombre"};

            $array["RejectUserName"] = $value->{"cuenta_cobro.usurechaza_id"};
            $array["AllowUserName"] = $value->{"cuenta_cobro.usucambio_id"};
            $array["PaidUserName"] = $value->{"cuenta_cobro.usupago_id"};
            $array["Notes"] = $value->{"cuenta_cobro.mensaje_usuario"};
            $array["RejectReason"] = $value->{"cuenta_cobro.observacion"};
            $array["Description"] = $value->{"cuenta_cobro.observacion"};
            $array["StateName"] = $estado;
            if ($State != "I") {
                $array["State"] = $value->{"cuenta_cobro.estado"};
                $array["StateId"] = $value->{"cuenta_cobro.estado"};
            } else {
                $array["State"] = 'I';
                $array["StateId"] = 'I';

            }
            $array["Note"] = "";
            $array["ExternalId"] = $value->{"transaccion_producto.externo_id"};
            $array["PaymentDocumentId"] = "";
            $array["Ip"] = $value->{"cuenta_cobro.dir_ip"};

            $array["SystemId"] = $value->{"cuenta_cobro.transproducto_id"};

            $array["ChangeDate"] = $value->{"cuenta_cobro.fecha_cambio"};
            $array["ChangeIp"] = $value->{"cuenta_cobro.dirip_cambio"};
            $array["ActionDate"] = $value->{"cuenta_cobro.fecha_accion"};
            $array["ActionIp"] = $value->{"cuenta_cobro.dirip_accion"};
            $array["AllowPayApi"] = false;

            if ($value->{"banco.producto_pago"} != '0' && $value->{"banco.producto_pago"} != ""
                && $value->{"cuenta_cobro.estado"} == "P") {
                $array["AllowPayApi"] = 'true';
                $array["PermittedPay"] = true;

            }
            if ($value->{"cuenta_cobro.mediopago_id"} == '5996264'
                && $value->{"cuenta_cobro.estado"} == "P") {
                // Se permite el uso de la API de pago y se configura el estado de la cuenta bancaria y el permiso de pago
                $array["AllowPayApi"] = 'true';
                $array["PermittedPay"] = true;
                $array["AccountBank"] = 'true';
                $array["PermittedPay"] = true;

            }

            // Verifica si el producto de pago ID no está vacío y el estado es permitido
            if ($value->{"cuenta_cobro.producto_pago_id"} != '' && $value->{"cuenta_cobro.producto_pago_id"} != 0
                && $value->{"cuenta_cobro.estado"} == "P") {
                $array["AccountBank"] = "UserAgent";
                $array["AllowPayApi"] = 'true';
                $nombreMetodoPago = "Redes Aliadas";

            }
            // Se añade la información de pago al documento
            $array2["PaymentDocumentData"] = $array;

            // Condición específica para un ID determinado
            if ($array["Id"] == '895367') {
                /* $array["AccountBank"] = 'true';
                 $array["PermittedPay"] = true;*/

            }

            // Verifica el estado y si el ID está en una lista específica
            if ($array["State"] == 'A' && in_array($array["Id"], array(7023037, 7023838, 7024933, 7029613, 7035241, 7036366, 7040581, 7053223, 7053256, 7053262, 7053370, 7053484, 7053502, 7053838, 7054117, 7054120, 7054645, 7055662, 7055740, 7056427, 7056664, 7056874, 7057546, 7057747, 7057822, 7058089, 7058101, 7058608, 7058938, 7059100, 7059409, 7059469, 7059799, 7059844, 7060084, 7060219, 7060603, 7060831, 7060876, 7061269, 7061356, 7061482, 7061638, 7061842, 7061887, 7062265, 7062271, 7062664, 7062871, 7063003, 7063069, 7063117, 7063615, 7063735, 7063906, 7063924, 7064191, 7064230, 7064293, 7064299, 7064338, 7064518, 7064662, 7064881, 7064947, 7065025, 7065112, 7065280, 7065409, 7065646, 7065967, 7065982, 7066027, 7066105, 7066360, 7066429, 7066660, 7066774, 7066888, 7067008, 7067056, 7067074, 7067209, 7067323, 7067440, 7067527, 7067779, 7067782, 7067854, 7067935, 7068133, 7068163, 7068370, 7068394, 7068541, 7068565, 7068688, 7068937, 7068979, 7069042, 7069051, 7069468, 7069486, 7069507, 7069528, 7069540, 7069558, 7069567, 7069735, 7069825, 7069873, 7069909, 7069936, 7069957, 7070293, 7070341, 7070377, 7070728, 7070824, 7070887, 7070926, 7071079, 7071094, 7071265, 7071280, 7071298, 7071520, 7072150, 7072246, 7072282, 7072369, 7072402, 7072489, 7072585, 7072642, 7072714, 7072717, 7073008, 7073143, 7073950, 7074403, 7074619))) {
                $array["AccountBank"] = 'true';
                $array["PermittedPay"] = true;
                $array["State"] = 'M';

            }
            // Verifica si el medio de pago ID es el específico
            if ($value->{"cuenta_cobro.mediopago_id"} == '853460') {
                $array["AccountBank"] = '';
                $array["PermittedPay"] = true;

            }

            // Verifica si la cuenta ID está entre las específicas para permitir el pago
            if ($value->{"cuenta_cobro.cuenta_id"} == "1367588" || $value->{"cuenta_cobro.cuenta_id"} == "1398255") {
                $array["AllowPayApi"] = 'true';
                $array["PermittedPay"] = true;
                $array["AccountBank"] = 'true';
                $array["PermittedPay"] = true;


            }
            // Verifica si la cuenta ID está en un conjunto específico
            if (in_array($value->{"cuenta_cobro.cuenta_id"}, array(1512412, 1512103, 1512106))) {
                $array["AllowPayApi"] = 'true';
                $array["PermittedPay"] = true;
                $array["AccountBank"] = 'true';
                $array["PermittedPay"] = true;


            }
            if (in_array($value->{"cuenta_cobro.mediopago_id"}, array(2088007))) {
                $array["AllowPayApi"] = 'true';
                $array["PermittedPay"] = true;
                $array["AccountBank"] = 'true';
                $array["PermittedPay"] = true;


            }


        } else {
            // Asigna el valor de ".valor" al índice "Amount" del array
            $array["Amount"] = $value->{".valor"};
            $array["Country"] = strtolower($value->{"pais.iso"});
            $array["State"] = $value->{"cuenta_cobro.estado"};
            if ($State != "I") {
                $array["State"] = $value->{"cuenta_cobro.estado"};
            } else {
                // Si el estado es "I", asigna 'I' al índice "State" del array
                $array["State"] = 'I';

            }

        }

        if ($_SESSION['usuario'] == 449) {
            $array["PermittedPay"] = true;  // Permite el pago
            $array["AllowPayApi"] = 'true'; // Permite la API de pago

            // Verifica si la cuenta de cobro está en el listado permitido
            if (in_array($value->{"cuenta_cobro.cuenta_id"}, array(3969334, 3969277, 3966859, 3967711, 3969109, 3969310, 3964504, 3969265, 3966331, 3969208, 3967435, 3966154, 3968959))) {
                $array["State"] = 'A'; // Establece el estado a 'A'
                $array["AccountBank"] = '1'; // Establece la cuenta bancaria
            }
        }

        // Asigna los valores del último depósito y retiro al arreglo
        $array["ValueLastDeposit"] = $value->{'data_completa2.monto_ultimo_deposito'}; // Último monto depositado
        $array["ValueLastWithdraw"] = $value->{'data_completa2.monto_ultimo_retiro'}; // Último monto retirado

        $array['DocExpirationDate'] = '0'; // Inicializa la fecha de vencimiento del documento
        try {
            //Consultando fecha de vencimiento del documento
            $UsuarioConfiguracion = new UsuarioConfiguracion($value->{'cuenta_cobro.usuario_id'}, 'A', $ClasificadorDocExpira->getClasificadorId());
            $docExpirationDate = $UsuarioConfiguracion->getValor();
            $array['DocExpirationDate'] = $docExpirationDate;
        } catch (Exception $e) {
            if ($e->getCode() != 46) throw $e;
        }


        /* Consultado valores acumulados del usuario */
        $yesterdayDate = date('Y-m-d 23:59:59', strtotime('-1 days'));

        if (!isset($accumulatedStats[$value->{'cuenta_cobro.usuario_id'}]['AccumulatedDepositsValue'])) {

            $BonoInterno = new BonoInterno();
            // Total de depósitos día vencido
            $sql = "select * from usuario_saldoresumen where usuario_id = " . $value->{'cuenta_cobro.usuario_id'};
            $usuario_saldoresumen = $BonoInterno->execQuery('', $sql)[0];

            //Consulta la estadística si esta no ha sido consultada anteriormente para el usuario
            $accumulatedDeposits = $usuario_saldoresumen->{'usuario_saldoresumen.saldo_recarga'};
            $accumulatedStats[$value->{'cuenta_cobro.usuario_id'}]['AccumulatedDepositsValue'] = $accumulatedDeposits ?? '0';

            //Consulta la estadística si esta no ha sido consultada anteriormente para el usuario
            $accumulatedBets = $usuario_saldoresumen->{'usuario_saldoresumen.saldo_apuestas'} + $usuario_saldoresumen->{'usuario_saldoresumen.saldo_apuestas_casino'} + $usuario_saldoresumen->{'usuario_saldoresumen.saldo_apuestas_casino_vivo'};
            $accumulatedStats[$value->{'cuenta_cobro.usuario_id'}]['AccumulatedBetsValue'] = $accumulatedBets ?? '0';

            //Consulta la estadística si esta no ha sido consultada anteriormente para el usuario
            $accumulatedWithdrawals = $usuario_saldoresumen->{'usuario_saldoresumen.saldo_notaret_pagadas'};
            $accumulatedStats[$value->{'cuenta_cobro.usuario_id'}]['AccumulatedWithdrawalsValue'] = $accumulatedWithdrawals ?? '0';

            //Consulta la estadística si esta no ha sido consultada anteriormente para el usuario
            $accumulatedAwards = $usuario_saldoresumen->{'usuario_saldoresumen.saldo_premios'} + $usuario_saldoresumen->{'usuario_saldoresumen.saldo_premios_casino'};
            $accumulatedStats[$value->{'cuenta_cobro.usuario_id'}]['AccumulatedAwardsValue'] = $accumulatedAwards ?? '0';

            $accumulatedGGR =
                $usuario_saldoresumen->{'usuario_saldoresumen.saldo_apuestas'} +
                $usuario_saldoresumen->{'usuario_saldoresumen.saldo_apuestas_casino'} +
                $usuario_saldoresumen->{'usuario_saldoresumen.saldo_apuestas_casino_vivo'} -
                $usuario_saldoresumen->{'usuario_saldoresumen.saldo_premios'} -
                $usuario_saldoresumen->{'usuario_saldoresumen.saldo_premios_casino'} -
                $usuario_saldoresumen->{'usuario_saldoresumen.saldo_bono'} -
                $usuario_saldoresumen->{'usuario_saldoresumen.saldo_bono_free_ganado'} -
                $usuario_saldoresumen->{'usuario_saldoresumen.saldo_bono_casino_free_ganado'} -
                $usuario_saldoresumen->{'usuario_saldoresumen.saldo_bono_virtual'} -
                $usuario_saldoresumen->{'usuario_saldoresumen.saldo_bono_virtual_free_ganado'};

            $accumulatedStats[$value->{'cuenta_cobro.usuario_id'}]['AccumulatedGGRValue'] = $accumulatedGGR ?? '0';

        }


        //Entregando una estadística que ya fue entregada para un registro anterior del mismo usuario
        $array['AccumulatedDepositsValue'] = (double)$accumulatedStats[$value->{'cuenta_cobro.usuario_id'}]['AccumulatedDepositsValue'];
        $array['AccumulatedDepositsValue'] = (string)round($array['AccumulatedDepositsValue'], 2, PHP_ROUND_HALF_DOWN);


        //Entregando una estadística que ya fue entregada para un registro anterior del mismo usuario
        $array['AccumulatedWithdrawalsValue'] = (double)$accumulatedStats[$value->{'cuenta_cobro.usuario_id'}]['AccumulatedWithdrawalsValue'];
        $array['AccumulatedWithdrawalsValue'] = (string)round($array['AccumulatedWithdrawalsValue'], 2, PHP_ROUND_HALF_DOWN);


        //Entregando una estadística que ya fue entregada para un registro anterior del mismo usuario
        $array['AccumulatedAwardsValue'] = (double)$accumulatedStats[$value->{'cuenta_cobro.usuario_id'}]['AccumulatedAwardsValue'];
        $array['AccumulatedAwardsValue'] = (string)round($array['AccumulatedAwardsValue'], 2, PHP_ROUND_HALF_DOWN);


        //Total de apuestas acumulados día vencido


        //Entregando una estadística que ya fue entregada para un registro anterior del mismo usuario
        $array['AccumulatedBetsValue'] = (double)$accumulatedStats[$value->{'cuenta_cobro.usuario_id'}]['AccumulatedBetsValue'];
        $array['AccumulatedBetsValue'] = (string)round($array['AccumulatedBetsValue'], 2, PHP_ROUND_HALF_DOWN);


        //Entregando una estadística que ya fue entregada para un registro anterior del mismo usuario
        $array['AccumulatedGGRValue'] = (double)$accumulatedStats[$value->{'cuenta_cobro.usuario_id'}]['AccumulatedGGRValue'];
        $array['AccumulatedGGRValue'] = (string)round($array['AccumulatedGGRValue'], 2, PHP_ROUND_HALF_DOWN);

        $array['Trm'] = null;
        $array['CriptoAmount'] = null;

        array_push($final, $array);
    }

    $response["HasError"] = false; // Indica si hubo un error en el proceso
    $response["AlertType"] = "success"; // Tipo de alerta para la respuesta
    $response["AlertMessage"] = ""; // Mensaje de alerta que se enviará
    $response["ModelErrors"] = []; // Errores del modelo, si los hubiera

    $response["Data"] = $final; // Datos finales de la respuesta

    $response["pos"] = intval($SkeepRows); // Fila desde la que se está recuperando la información
    $response["total_count"] = intval($cuentas->count[0]->{".count"}); // Total de registros contados
    $response["data"] = $final; // Datos finales que se enviarán en la respuesta

} else {

    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

    $response["Data"] = array();

    $response["pos"] = 0;
    $response["total_count"] = 0;
    $response["data"] = array();
}
