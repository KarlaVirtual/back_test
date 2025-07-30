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
use Backend\dto\ItTransaccion;
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
 *TransactionReport/GetTransactionReport
 *
 * Obtener historial de apuestas con detalles
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
 * @OA\Post(path="apipv/TransactionReport/GetTransactionReport", tags={"Report"}, description = "",
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
 *                   example= 309
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
 * ),
 * @OA\Response (response="404", description="Not found")
 * ),
 */


/**
 * Obtener historial de apuestas con detalles.
 *
 * Este script genera un reporte de transacciones de apuestas basado en los filtros proporcionados.
 *
 * @param $_REQUEST array Valores de entrada para el reporte:
 * @param int $_REQUEST["UserId"] ID del usuario.
 * @param string $_REQUEST["Ticket"] ID del ticket.
 * @param string $_REQUEST["Type"] Tipo de transacción.
 * @param string $_REQUEST["dateFrom"] Fecha inicial del rango (YYYY-MM-DD).
 * @param string $_REQUEST["dateTo"] Fecha final del rango (YYYY-MM-DD).
 * @param string $_REQUEST["TypeReport"] Tipo de reporte (1 para resumen, otro para detalle).
 * @param string $_REQUEST["CountrySelect"] País seleccionado.
 * @param int $_REQUEST["count"] Número máximo de registros a devolver.
 * @param int $_REQUEST["start"] Índice de inicio para la paginación.
 * 
 *
 * @return array Respuesta con los siguientes valores:
 * - HasError (boolean): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta (e.g., "success").
 * - AlertMessage (string): Mensaje de alerta.
 * - ModelErrors (array): Lista de errores del modelo.
 * - pos (int): Posición inicial de los registros.
 * - total_count (int): Total de registros encontrados.
 * - data (array): Datos del reporte.
 */

/* Se crean variables para procesar una transacción con datos solicitados. */
$ItTransaccion = new ItTransaccion();

$UserId = $_REQUEST["UserId"];
$Ticket = $_REQUEST["Ticket"];
$Type = $_REQUEST["Type"];
$dateFrom = $_REQUEST["dateFrom"];

/* obtiene valores de entrada de una solicitud HTTP para su procesamiento. */
$dateTo = $_REQUEST["dateTo"];
$TypeReport = $_REQUEST["TypeReport"];
$Country = $_REQUEST["CountrySelect"];


$MaxRows = $_REQUEST["count"];


/* asigna valor a $SkeepRows basado en la solicitud del usuario. */
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];
$seguir = true;

if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* asigna 100 a $MaxRows si está vacío y inicializa un array $rules. */
if ($MaxRows == "") {
    $MaxRows = 100;
}


$rules = [];

/* Se crea una nueva instancia de la clase ConfigurationEnvironment en la variable. */
$ConfigurationEnvironment = new ConfigurationEnvironment();

if ($dateFrom != "" && $dateTo != "") {


    /* Agrega reglas de filtrado para transacciones según fechas y usuario. */
    array_push($rules, array("field" => "it_transaccion.fecha_crea", "data" => "$dateFrom", "op" => "ge"));
    array_push($rules, array("field" => "it_transaccion.fecha_crea", "data" => "$dateTo", "op" => "le"));

    if ($UserId != "") {
        array_push($rules, array("field" => "it_transaccion.usuario_id", "data" => "$UserId", "op" => "eq"));
    }


    /* Agrega reglas de filtrado basadas en condiciones para tickets y tipos. */
    if ($Ticket != "") {
        array_push($rules, array("field" => "it_transaccion.ticket_id", "data" => "$Ticket", "op" => "eq"));
    }

    if ($Type != "") {
        array_push($rules, array("field" => "it_transaccion.tipo", "data" => "$Type", "op" => "eq"));
    }

    /* Agrega reglas de filtro para el país del usuario si cumplen ciertas condiciones. */
    if ($Country != "") {
        array_push($rules, array("field" => "usuario.pais_id", "data" => "$Country", "op" => "eq"));
    }

    // Si el usuario esta condicionado por País
    if ($_SESSION['PaisCond'] == "S") {
        array_push($rules, array("field" => "usuario.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
    }
    // Si el usuario esta condicionado por el mandante y no es de Global

    /* maneja reglas de acceso basado en la sesión del usuario. */
    if ($_SESSION['Global'] == "N") {
        array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
    } else {

        if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
            array_push($rules, array("field" => "usuario.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
        }

    }


    /* Se crea una nueva instancia de la clase `ItTicketEnc`. */
    $ItTicketEnc = new ItTicketEnc();

    if ($TypeReport == "1") {

        /* filtra y transforma datos de transacciones de tickets en formato JSON. */
        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);
        $tickets = $ItTicketEnc->getTicketTransactionsCustom("sum(it_transaccion.valor) as valor, it_transaccion.*,usuario.moneda", "", "", $SkeepRows, $MaxRows, $json, true, "it_transaccion.tipo,usuario.moneda");
        $tickets = json_decode($tickets);

        $final = [];

        foreach ($tickets->data as $key => $value) {


            /* Se inicializa un array vacío en PHP para almacenar elementos posteriormente. */
            $array = [];

            switch ($value->{"it_transaccion.tipo"}) {
                case "BET" :
                    /* asigna "Apuesta" a la variable $tipo si se cumple el caso "BET". */

                    $tipo = "Apuesta";
                    break;
                case "LOSS" :
                    /* Asigna "Apuesta perdida" a $tipo si la condición es "LOSS". */

                    $tipo = "Apuesta perdida";
                    break;
                case "WIN" :
                    /* Asigna "Ganancia" a la variable $tipo si el caso es "WIN". */

                    $tipo = "Ganancia";
                    break;
                case "REFUND" :
                    /* asigna un tipo de devolución a una variable según una condición específica. */

                    $tipo = "Devolución de apuesta";
                    break;
                case "STAKEDECREASE" :
                    /* asigna "Disminución de apuesta" al tipo para el caso "STAKEDECREASE". */

                    $tipo = "Disminución de apuesta";
                    break;
                case "NEWDEBIT" :
                    /* Asignación de tipo "Ajuste DEBIT" al caso "NEWDEBIT" en una estructura switch. */

                    $tipo = "Ajuste DEBIT";
                    break;
                case "NEWCREDIT" :
                    /* Asigna "Ajuste CREDIT" a la variable $tipo si se recibe "NEWCREDIT". */

                    $tipo = "Ajuste CREDIT";
                    break;
                case "CASHOUT" :
                    /* asigna "CASHOUT" a la variable $tipo en un caso específico. */

                    $tipo = "CASHOUT";
                    break;
                default :

                    /* Se asigna el tipo de transacción a la variable $tipo desde un objeto $value. */
                    $tipo = $value->{"it_transaccion.tipo"};
                    break;
            }

            /* asigna valores a un array y lo agrega a otro array final. */
            $array["Type"] = $tipo;
            $array["CreatedLocalDate"] = $value->{"it_transaccion.fecha_crea"} . " " . $value->{"it_transaccion.hora_crea"};
            $array["Amount"] = $value->{".valor"};
            $array["CurrencyId"] = $value->{"usuario.moneda"};


            array_push($final, $array);

        }
    } else {

        /* Código filtra y obtiene transacciones de tickets en formato JSON. */
        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);
        $tickets = $ItTicketEnc->getTicketTransactionsCustom("it_transaccion.*,usuario.moneda", "", "", $SkeepRows, $MaxRows, $json, true);
        $tickets = json_decode($tickets);

        $final = [];

        foreach ($tickets->data as $key => $value) {


            /* Se crea un array asignando valores de un objeto a claves específicas. */
            $array = [];
            $array["Ticket"] = $value->{"it_transaccion.ticket_id"};
            $array["UserId"] = $value->{"it_transaccion.usuario_id"};
            switch ($value->{"it_transaccion.tipo"}) {
                case "BET" :
                    /* asigna el valor "Apuesta" a la variable $tipo en caso de "BET". */

                    $tipo = "Apuesta";
                    break;
                case "LOSS" :
                    /* asigna "Apuesta perdida" a la variable $tipo si se recibe "LOSS". */

                    $tipo = "Apuesta perdida";
                    break;
                case "WIN" :
                    /* Código PHP que asigna "Ganancia" a la variable $tipo si el caso es "WIN". */

                    $tipo = "Ganancia";
                    break;
                case "REFUND" :
                    /* asigna un texto descriptivo a una variable para un tipo de transacción. */

                    $tipo = "Devolución de apuesta";
                    break;
                case "STAKEDECREASE" :
                    /* asigna una descripción a un tipo de evento de reducción de apuesta. */

                    $tipo = "Disminución de apuesta";
                    break;
                case "NEWDEBIT" :
                    /* define un tipo de ajuste para un caso específico llamado "NEWDEBIT". */

                    $tipo = "Ajuste DEBIT";
                    break;
                case "NEWCREDIT" :
                    /* establece un tipo de ajuste para un nuevo crédito. */

                    $tipo = "Ajuste CREDIT";
                    break;
                case "CASHOUT" :
                    /* define una variable "$tipo" como "CASHOUT" bajo una condición específica. */

                    $tipo = "CASHOUT";
                    break;
                default :

                    /* Se asigna el valor del tipo de transacción a la variable $tipo. */
                    $tipo = $value->{"it_transaccion.tipo"};
                    break;
            }

            /* Asigna valores a un array y lo agrega a un array final. */
            $array["Type"] = $tipo;
            $array["CreatedLocalDate"] = $value->{"it_transaccion.fecha_crea"} . " " . $value->{"it_transaccion.hora_crea"};
            $array["Amount"] = $value->{"it_transaccion.valor"};
            $array["CurrencyId"] = $value->{"usuario.moneda"};


            array_push($final, $array);
        }
    }


    /* Se inicializa un objeto de respuesta con estado y mensajes para una operación. */
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];


    $response["pos"] = $SkeepRows;
    //$response["total_count"] = Count($final);

    /* asigna el conteo de tickets a una respuesta y agrega datos finales. */
    $response["total_count"] = $tickets->count[0]->{".count"};
    $response["data"] = $final;

} else {
    /* Código que inicializa una respuesta sin errores y con datos vacíos. */


    $response["HasError"] = false;
    $response["AlertType"] = "success2";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

    //$response["Data"] = array("Objects" => $final,
    //"Count" => $tickets->count[0]->{".count"});

    $response["pos"] = 0;
    $response["total_count"] = 0;
    $response["data"] = array();

}



