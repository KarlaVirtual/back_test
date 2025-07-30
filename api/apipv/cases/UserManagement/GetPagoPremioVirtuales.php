<?php

use Backend\dto\Usuario;
use Backend\dto\Clasificador;
use Backend\dto\MandanteDetalle;
use Backend\dto\UsuarioMandante;
use Backend\dto\TransaccionJuego;
use Backend\dto\ConfigurationEnvironment;

/**
 * UserManagement/GetPagoPremioVirtuales
 *
 * Este script obtiene el detalle para el pago de premios virtuales.
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


/* Activa la depuración de errores si se recibe una petición específica. */
if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

$UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);


/* crea un nuevo usuario y decodifica parámetros JSON de una solicitud. */
$UsuarioPuntoVenta = new Usuario($UsuarioMandante->getUsuarioMandante());

$params = file_get_contents('php://input');
$params = json_decode($params);

$NoTicket = "CASI_" . $_REQUEST["NoTicket"];

/* obtiene y depura la variable "ClaveTicket" usando un objeto de configuración. */
$ClaveTicket = $_REQUEST["ClaveTicket"];

$ConfigurationEnvironment = new ConfigurationEnvironment();

$ClaveTicket = $ConfigurationEnvironment->DepurarCaracteres($ClaveTicket);
$NoTicket = $ConfigurationEnvironment->DepurarCaracteres($NoTicket);


/* Verifica la longitud de $ClaveTicket y lanza una excepción si es mayor a 10. */
if (strlen($ClaveTicket) > 10) {
    throw new Exception("Inusual Detected", 11);
}

$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;

/* Define el número de filas a omitir y establece un valor por defecto. */
$SkeepRows = $params->SkeepRows;

$MaxRows = $_REQUEST["count"];
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];

if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* Asigna valores predeterminados a variables si están vacías. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 1000;
}


/* Valida parámetros y procesa el número de ticket eliminando un prefijo específico. */
if ($NoTicket == "" || $ClaveTicket == "") {
    throw new Exception("Error en los parametros enviados", 100001);
}

$prefix = "CASI_";
if (strpos($NoTicket, $prefix) === 0) {
    $NoTicket = substr($NoTicket, strlen($prefix));
} else {
    /* Lanza una excepción si no se encuentra el ticket solicitado. */

    throw new Exception("No existe Ticket", 24);
}


/* Verifica la existencia de un ticket; lanza excepción si no se encuentra. */
$TransaccionJuego = new TransaccionJuego();
$TransaccionJuego = $TransaccionJuego->checkTicket($NoTicket, $ClaveTicket);

if ($TransaccionJuego == null) {
    throw new Exception("No existe Ticket", 24);
}


/* inicializa un array de respuesta sin errores y con mensaje de éxito. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

if ($TransaccionJuego != null) {


    /* Calcula el impuesto sobre premios utilizando un clasificador y detalles del mandante. */
    $impuesto = 0;

    try {
        $Clasificador = new Clasificador("", "TAXBETPAYPRIZE");
        $minimoMontoPremios = 0;

        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioPuntoVenta->paisId, 'A');

        $impuestoPorcSobreApuesta = $MandanteDetalle->getValor();

        $impuesto = floatval($impuestoPorcSobreApuesta / 100) * floatval($TransaccionJuego->valorPremio);
    } catch (Exception $e) {
        /* Bloque de código PHP para capturar excepciones sin realizar ninguna acción específica. */

    }


    /* Calcula impuestos sobre premios basándose en detalles del mandante y transacciones. */
    try {
        $Clasificador = new Clasificador("", "TAXPRIZEBETSHOP");
        $minimoMontoPremios = 0;

        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioPuntoVenta->paisId, 'A');

        $impuestoPorcSobrePremio = $MandanteDetalle->getValor();

        $paraImpuesto = floatval($TransaccionJuego->valorPremio) - floatval($TransaccionJuego->valorTicket);
        if ($paraImpuesto < 0) {
            $impuesto += 0;
        } else {
            $impuesto += floatval($impuestoPorcSobrePremio / 100) * $paraImpuesto;
        }
    } catch (Exception $e) {
        /* Captura cualquier excepción en PHP sin realizar ninguna acción específica. */

    }


    /* Verifica si el ID del punto de venta está en una lista y calcula impuestos. */
    if (in_array($UsuarioPuntoVenta->puntoventaId, array("67561", "129971", "135893", "156774", "161521", "153389", "161529", "147670", "145514", "135930", "147676", "157928", "157933", "140996", "140998", "135893", "156973", "132134", "152495", "164397", "164410", "145483", "135871", "135876", "166627", "174951", "174936", "135876", "166627", "174928", "176650"))) {

        $impuestoPorcSobrePremio = 7;

        $paraImpuesto = floatval($TransaccionJuego->valorPremio) - floatval($TransaccionJuego->valorTicket);
        if ($paraImpuesto < 0) {
            $impuesto += 0;
        } else {
            $impuesto += floatval($impuestoPorcSobrePremio / 100) * $paraImpuesto;
        }
    }

    if ($UsuarioPuntoVenta->paisId == "94" && false) {

        try {

            /* Calcula un impuesto basado en el valor del premio, si es negativo no se aplica. */
            $impuesto = 0;
            $impuestoPorcSobrePremio = 3;

            $paraImpuesto = floatval($TransaccionJuego->valorPremio);
            if ($paraImpuesto < 0) {
                $impuesto += 0;
            } else {
                /* Calcula el impuesto sumando un porcentaje sobre el premio a una variable existente. */

                $impuesto += floatval($impuestoPorcSobrePremio / 100) * $paraImpuesto;
            }


            /* Calcula el impuesto sobre un premio, asegurando que no sea negativo. */
            $impuesto2 = 0;

            $impuestoPorcSobrePremio = 10;

            $paraImpuesto = floatval($TransaccionJuego->valorPremio) - floatval($impuesto);
            if ($paraImpuesto < 0) {
                $impuesto2 += 0;
            } else {
                /* calcula un impuesto aplicando un porcentaje sobre un premio específico. */

                $impuesto2 += floatval($impuestoPorcSobrePremio / 100) * $paraImpuesto;
            }


            /* Suma el valor de $impuesto2 a $impuesto y actualiza $impuesto. */
            $impuesto = $impuesto + $impuesto2;
        } catch (Exception $e) {
            /* Bloque en PHP que captura excepciones sin realizar ninguna acción al respecto. */

        }
    }


    /* organiza y estructura información de una transacción de juego en un array. */
    $response["data"] = array(
        "ValorApostado" => $TransaccionJuego->valorTicket,
        "ValorImpuesto" => $impuesto,

        "ValorPagar" => floatval($TransaccionJuego->valorPremio) - $impuesto,
        "PremioProyectado" => $TransaccionJuego->valorPremio,
        "Estado" => $TransaccionJuego->estado,
        "Ganador" => $TransaccionJuego->premiado,
    );


    /* Evalúa si el jugador ganó y si se pagó el premio. */
    if ($TransaccionJuego->premiado == "S") {
        $response["data"]["Ganador"] = "SI";
    } else {
        $response["data"]["Ganador"] = "NO";
    }

    if ($TransaccionJuego->premioPagado == "S") {
        $response["data"]["PremioPagado"] = "S";
    } else {
        /* Asigna "N" al campo "PremioPagado" en la respuesta si no se cumple una condición. */

        $response["data"]["PremioPagado"] = "N";
    }
} else {
    /* establece que hay un error si no se cumple una condición previa. */

    $response["HasError"] = true;
}
