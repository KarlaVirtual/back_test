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
 * UserManagement/GetPagoPremio
 *
 * Este script obtiene el detalle para el pago de premios.
 *
 * @param string $params JSON recibido que contiene los datos de entrada:
 * @param int $params->MaxRows Número máximo de filas a devolver.
 * @param int $params->OrderedItem Orden de los elementos.
 * @param int $params->SkeepRows Número de filas a omitir.
 * @param string $_REQUEST["NoTicket"] Número del ticket.
 * @param string $_REQUEST["ClaveTicket"] Clave del ticket.
 *
 * @return array $response Respuesta en formato JSON con los siguientes campos:
 *                         - HasError (boolean): Indica si ocurrió un error.
 *                         - AlertType (string): Tipo de alerta (por ejemplo, "success").
 *                         - AlertMessage (string): Mensaje de alerta.
 *                         - ModelErrors (array): Lista de errores del modelo.
 *                         - Data (array): Información del premio, incluyendo:
 *                           - ValorApostado (float): Valor apostado.
 *                           - ValorImpuesto (float): Valor del impuesto.
 *                           - ValorPagar (float): Valor a pagar.
 *                           - PremioProyectado (float): Premio proyectado.
 *                           - Estado (string): Estado del ticket.
 *                           - Ganador (string): Indica si es ganador ("SI" o "NO").
 *                           - PremioPagado (string): Indica si el premio fue pagado ("S" o "N").
 */


/* crea instancias de usuarios y decodifica parámetros JSON recibidos en una solicitud. */
$UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

$UsuarioPuntoVenta = new Usuario($UsuarioMandante->getUsuarioMandante());

$params = file_get_contents('php://input');
$params = json_decode($params);


/* obtiene y limpia dos parámetros de entrada para su uso posterior. */
$NoTicket = $_REQUEST["NoTicket"];
$ClaveTicket = $_REQUEST["ClaveTicket"];

$ConfigurationEnvironment = new ConfigurationEnvironment();

$ClaveTicket = $ConfigurationEnvironment->DepurarCaracteres($ClaveTicket);

/* Depura caracteres del ticket y verifica longitud, lanzando excepción si es inusual. */
$NoTicket = $ConfigurationEnvironment->DepurarCaracteres($NoTicket);

$ItTicketEnc = new ItTicketEnc();

if (strlen($ClaveTicket) > 10) {
    throw new Exception("Inusual Detected", "11");

}


/* Se definen variables para gestionar paginación de datos en una aplicación. */
$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;

$MaxRows = $_REQUEST["count"];
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];


/* inicializa variables si están vacías, estableciendo valores por defecto. */
if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}


/* establece valores predeterminados y valida parámetros necesarios, lanzando excepciones si faltan. */
if ($MaxRows == "") {
    $MaxRows = 1000;
}

if ($NoTicket == "" || $ClaveTicket == "") {
    throw new Exception("Error en los parametros enviados", "100001");
}


/* Verifica un ticket; lanza excepción si no se encuentra. */
$ItTicketEnc = new ItTicketEnc();

$ItTicketEnc = $ItTicketEnc->checkTicket($NoTicket, $ClaveTicket);

if ($ItTicketEnc == null) {

    throw new Exception("No existe Ticket", "24");
}


/* Construye reglas de filtrado para la consulta de tickets según condiciones específicas. */
$rules = [];

array_push($rules, array("field" => "it_ticket_enc.ticket_id", "data" => "$NoTicket", "op" => "eq"));
array_push($rules, array("field" => "it_ticket_enc.eliminado", "data" => "N", "op" => "eq"));
if ($UsuarioPuntoVenta->mandante != '1' && $UsuarioPuntoVenta->mandante != '2') {
    array_push($rules, array("field" => "usuario.puntoventa_id", "data" => $UsuarioPuntoVenta->puntoventaId, "op" => "eq"));

}


/* Se crea un filtro JSON y se obtienen tickets personalizados según parámetros específicos. */
$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);


$ItTicketEnc = new ItTicketEnc();
$tickets = $ItTicketEnc->getTicketsCustom(" it_ticket_enc.vlr_apuesta,it_ticket_enc.vlr_premio,it_ticket_enc.estado,it_ticket_enc.premiado,it_ticket_enc.premio_pagado ", "it_ticket_enc.ticket_id", "asc", 0, 1, $json, true);

/* decodifica un JSON y establece una respuesta sin errores. */
$tickets = json_decode($tickets);


$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";

/* Inicia un arreglo vacío para almacenar errores de modelo en la respuesta. */
$response["ModelErrors"] = [];

if (oldCount($tickets->data) > 0) {


    /* Cálculo del impuesto sobre una apuesta utilizando clase y datos específicos del mandante. */
    $impuesto = 0;

    try {
        $Clasificador = new Clasificador("", "TAXBETPAYPRIZE");
        $minimoMontoPremios = 0;

        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioPuntoVenta->paisId, 'A');

        $impuestoPorcSobreApuesta = $MandanteDetalle->getValor();

        $impuesto = floatval($impuestoPorcSobreApuesta / 100) * floatval($tickets->data[0]->{"it_ticket_enc.vlr_apuesta"});

    } catch (Exception $e) {
        /* Bloque de código que captura excepciones en PHP sin realizar ninguna acción. */


    }


    /* Cálculo del impuesto sobre premio en función del monto de apuestas y premios. */
    try {
        $Clasificador = new Clasificador("", "TAXPRIZEBETSHOP");
        $minimoMontoPremios = 0;

        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioPuntoVenta->paisId, 'A');

        $impuestoPorcSobrePremio = $MandanteDetalle->getValor();

        $paraImpuesto = floatval($tickets->data[0]->{"it_ticket_enc.vlr_premio"}) - floatval($tickets->data[0]->{"it_ticket_enc.vlr_apuesta"});
        if ($paraImpuesto < 0) {
            $impuesto += 0;
        } else {
            $impuesto += floatval($impuestoPorcSobrePremio / 100) * $paraImpuesto;
        }


    } catch (Exception $e) {
        /* El bloque captura excepciones para evitar que el programa se detenga. */


    }

    /* Calcula un impuesto sobre premios para ciertos puntos de venta en PHP. */
    if (in_array($UsuarioPuntoVenta->puntoventaId, array("67561", "129971", "135893", "156774", "161521", "153389", "161529", "147670", "145514", "135930", "147676", "157928", "157933", "140996", "140998", "135893", "156973", "132134", "152495", "164397", "164410", "145483", "135871", "135876", "166627", "174951", "174936", "135876", "166627", "174928", "176650"))) {

        $impuestoPorcSobrePremio = 7;

        $paraImpuesto = floatval($tickets->data[0]->{"it_ticket_enc.vlr_premio"}) - floatval($tickets->data[0]->{"it_ticket_enc.vlr_apuesta"});
        if ($paraImpuesto < 0) {
            $impuesto += 0;
        } else {
            $impuesto += floatval($impuestoPorcSobrePremio / 100) * $paraImpuesto;
        }
    }

    if ($UsuarioPuntoVenta->paisId == "94" && false) {

        try {

            /* calcula el impuesto sobre un premio, considerando un porcentaje específico. */
            $impuesto = 0;
            $impuestoPorcSobrePremio = 3;

            $paraImpuesto = floatval($tickets->data[0]->{"it_ticket_enc.vlr_premio"});
            if ($paraImpuesto < 0) {
                $impuesto += 0;
            } else {
                /* Calcula un impuesto adicional basado en un porcentaje sobre un premio determinado. */

                $impuesto += floatval($impuestoPorcSobrePremio / 100) * $paraImpuesto;
            }


            /* Calcula el impuesto basado en el valor del premio y un porcentaje específico. */
            $impuesto2 = 0;

            $impuestoPorcSobrePremio = 10;

            $paraImpuesto = floatval($tickets->data[0]->{"it_ticket_enc.vlr_premio"}) - floatval($impuesto);
            if ($paraImpuesto < 0) {
                $impuesto2 += 0;
            } else {
                /* Calcula el impuesto basado en un porcentaje aplicable a un premio monetario. */

                $impuesto2 += floatval($impuestoPorcSobrePremio / 100) * $paraImpuesto;
            }


            /* Suma dos variables de impuestos y almacena el resultado en la primera variable. */
            $impuesto = $impuesto + $impuesto2;

        } catch (Exception $e) {
            /* Bloque de captura para manejar excepciones en PHP sin realizar ninguna acción. */

        }
    }


    /* asigna valores a un arreglo con información sobre apuestas y premios. */
    $response["data"] =
        array(
            "ValorApostado" => $tickets->data[0]->{"it_ticket_enc.vlr_apuesta"},
            "ValorImpuesto" => $impuesto,

            "ValorPagar" => floatval($tickets->data[0]->{"it_ticket_enc.vlr_premio"}) - $impuesto,
            "PremioProyectado" => $tickets->data[0]->{"it_ticket_enc.vlr_premio"},
            "Estado" => $tickets->data[0]->{"it_ticket_enc.estado"},
            "Ganador" => $tickets->data[0]->{"it_ticket_enc.premiado"},
        );


    /* Verifica si un ticket es ganador y actualiza la respuesta correspondientemente. */
    if ($tickets->data[0]->{"it_ticket_enc.premiado"} == "S") {
        $response["data"]["Ganador"] = "SI";

    } else {
        $response["data"]["Ganador"] = "NO";

    }


    /* Verifica si el premio está pagado y lo almacena en la respuesta. */
    if ($tickets->data[0]->{"it_ticket_enc.premio_pagado"} == "S") {
        $response["data"]["PremioPagado"] = "S";

    } else {
        $response["data"]["PremioPagado"] = "N";

    }
} else {
    /* indica que hay un error al establecer la respuesta. */

    $response["HasError"] = true;

}
