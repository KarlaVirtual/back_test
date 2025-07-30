<?php

use Backend\dto\CuentaCobro;
use Backend\dto\UsuarioMandante;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\UsuarioBanco;
use Backend\dto\Usuario;

/**
 * Obtiene los retiros basándose en los datos proporcionados en la solicitud.
 *
 * @param string $params JSON codificado que contiene los datos de la solicitud, incluyendo:
 * @param int $params ->UserId: Identificador del usuario.
 * @param string $params ->dateFrom: Fecha de inicio para filtrar los retiros (formato 'Y-m-d').
 * @param string $params ->dateTo: Fecha de fin para filtrar los retiros (formato 'Y-m-d').
 * @param int $params ->AccountBank: Identificador de la cuenta bancaria asociada.
 * @param string $params ->State: Estado del retiro ('0' para activo, '1' para eliminado, etc.).
 * @param int $params ->OrderedItem: Orden de los elementos.
 * @param int $params ->start: Número de filas a omitir.
 * @param int $params ->count: Número máximo de filas a devolver.
 * @param int $params ->Id: Identificador específico del retiro.
 * 
 *
 * @return array $response Contiene los datos de la respuesta, incluyendo:
 *                         - HasError (bool): Indica si ocurrió un error.
 *                         - AlertType (string): Tipo de alerta ('success' en caso de éxito).
 *                         - AlertMessage (string): Mensaje de alerta.
 *                         - ModelErrors (array): Lista de errores del modelo.
 *                         - pos (int): Posición inicial de los datos devueltos.
 *                         - total_count (int): Número total de registros encontrados.
 *                         - data (array): Datos de los retiros, incluyendo:
 *                             - UserName (string): Nombre del usuario.
 *                             - Id (int): Identificador del retiro.
 *                             - AmountBase (string): Monto base del retiro.
 *                             - Tax (float): Impuesto aplicado al retiro.
 *                             - NetAmount (float): Monto neto después de impuestos.
 *                             - CreationDate (string): Fecha de creación del retiro.
 *                             - AccountBank (string): Cuenta bancaria asociada.
 *                             - payment_system_name (string): Nombre del sistema de pago.
 *                             - State (int): Estado del retiro (0: activo, 1: eliminado, etc.).
 *                             - PayDate (string|null): Fecha de pago del retiro.
 */


/* recibe datos JSON, los decodifica y obtiene un identificador de usuario. */
$params = file_get_contents('php://input');
$params = json_decode($params);

$ConfigurationEnvironment = new ConfigurationEnvironment();


$UserId = $_REQUEST["UserId"];

/* obtiene parámetros de solicitud para filtrar datos bancarios específicos. */
$BeginDate = $_REQUEST["dateFrom"]; //
$EndDate = $_REQUEST["dateTo"];//
$AccountBank = $_REQUEST["AccountBank"]; //usubanco_id
$State = $_REQUEST["State"]; //
$OrderedItem = $_REQUEST["OrderedItem"];
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];

/* recibe parámetros de entrada y prepara una respuesta sin errores. */
$start = $_REQUEST["start"];
$Id = $_REQUEST["Id"];
$MaxRows = $_REQUEST["count"];


$response["HasError"] = false;

/* Código inicializa un array para una respuesta JSON, indicando éxito y sin errores. */
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];
$response["pos"] = 0;
$response["total_count"] = 0;
$response["data"] = array();


/* establece la variable $SkeepRows basándose en la variable $start. */
if ($start != "") {
    $SkeepRows = $start;

}

if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* Establece valores predeterminados para $OrderedItem y $MaxRows si están vacíos. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 1000;
}


/* convierte fechas con zona horaria y establece límites temporales. */
if ($_REQUEST["dateTo"] != "") {
    $ToDateLocal = date("Y-m-d 23:59:59", strtotime(str_replace(" - ", " ", $_REQUEST["dateTo"]) . $timezone . ' hour '));
}

if ($_REQUEST["dateFrom"] != "") {
    $FromDateLocal = date("Y-m-d 00:00:00", strtotime(str_replace(" - ", " ", $_REQUEST["dateFrom"]) . $timezone . ' hour '));
}


/* Verifica si hay un UserId y crea un objeto Usuario basado en él. */
$seguir = false;
if ($UserId != "") {

    $UsuarioMandante = new UsuarioMandante("", $UserId, $_SESSION["mandante"]);
    //$UsuarioMandante = new UsuarioMandante("", $UserId, 0);
    $Usuario = new Usuario ($UsuarioMandante->getUsuarioMandante());

    $seguir = true;
} else {
    /* Crea un objeto UsuarioMandante y un objeto Usuario a partir de la sesión. */


    $UsuarioMandante = new UsuarioMandante("", $_SESSION["usuario"], $_SESSION["mandante"]);
    $Usuario = new Usuario ($UsuarioMandante->getUsuarioMandante());
    $seguir = true;

}
if ($seguir) {


    /* verifica el ID de usuario y establece reglas de filtrado. */
    $agentId = $Usuario->usuarioId;


    $rules = [];

    /*if ($_SESSION["win_perfil"] != "ADMINAFILIADOS" && $_SESSION["win_perfil"] != "ADMIN" && $_SESSION["win_perfil"] != "SA" && $_SESSION["win_perfil"] != "ADMIN2") {
        array_push($rules, array("field" => "cuenta_cobro.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
    }*/

    if ($agentId != "") {

        array_push($rules, array("field" => "cuenta_cobro.usuario_id", "data" => $agentId, "op" => "eq"));

    }


    /*if(!empty($agentId)) {
        array_push($rules, ['field' => 'cuenta_cobro.usuario_id', 'data' => $agentId, 'op' => 'eq']);
    }*/


    /* Agrega reglas a un arreglo basado en condiciones de fecha e ID. */
    if ($BeginDate != "") {
        array_push($rules, array("field" => "cuenta_cobro.fecha_crea", "data" => $FromDateLocal, "op" => "ge"));

    }

    if ($Id != "") {
        array_push($rules, array("field" => "cuenta_cobro.cuenta_id", "data" => $Id, "op" => "eq"));

    }


    /* Agrega reglas basadas en condiciones de fecha y cuenta bancaria. */
    if ($EndDate != "") {
        array_push($rules, array("field" => "cuenta_cobro.fecha_crea", "data" => $ToDateLocal, "op" => "le"));

    }


    if ($AccountBank != "") {
        array_push($rules, array("field" => "cuenta_cobro.mediopago_id", "data" => $AccountBank, "op" => "eq"));

    }


    /* Agrega reglas al arreglo según el estado proporcionado (0 o 1). */
    if ($State == "0") {
        array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "A", "op" => "eq"));

    }

    if ($State == '1') {
        array_push($rules, ['field' => 'cuenta_cobro.estado', 'data' => 'E', 'op' => 'eq']);
    }


    /* Agrega reglas según el estado: "P" para 2, "I" para 3. */
    if ($State == "2") {
        array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "P", "op" => "eq"));

    }

    if ($State == "3") {
        array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "I", "op" => "eq"));

    }


    /* Agrega una regla al arreglo si el estado es "4", luego crea un filtro. */
    if ($State == "4") {
        array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "R", "op" => "eq"));

    }


    $filtro = array("rules" => $rules, "groupOp" => "AND");

    /* Convierte filtro a JSON y obtiene cuentas de cobro con parámetros personalizados. */
    $json2 = json_encode($filtro);


    $CuentaCobro = new CuentaCobro();

    $cuentas = $CuentaCobro->getCuentasCobroCustom(" usuario.nombre,usuario_banco.cuenta,cuenta_cobro.fecha_pago,cuenta_cobro.impuesto,cuenta_cobro.cuenta_id,cuenta_cobro.estado,cuenta_cobro.valor,cuenta_cobro.fecha_crea, cuenta_cobro.mediopago_id", "cuenta_cobro.cuenta_id", "asc", $SkeepRows, $MaxRows, $json2, true, "cuenta_cobro.cuenta_id");


    /* Decodifica datos JSON y los almacena en un arreglo vacío para su uso posterior. */
    $cuentas = json_decode($cuentas);


    $cuentasData = array();

    foreach ($cuentas->data as $key => $value) {


        /* Crea un array con información de usuario y detalles de cuenta de cobro. */
        $arraybet = array();
        $arraybet["UserName"] = ($value->{"usuario.nombre"});
        $arraybet["Id"] = ($value->{"cuenta_cobro.cuenta_id"});
        $arraybet["AmountBase"] = strval(intval($value->{"cuenta_cobro.valor"}));
        $arraybet["Tax"] = ($value->{"cuenta_cobro.impuesto"});
        $arraybet["NetAmount"] = floatval($value->{"cuenta_cobro.valor"}) - floatval($value->{"cuenta_cobro.impuesto"});

        /* asigna valores a un array basado en un objeto y su estado. */
        $arraybet["CreationDate"] = ($value->{"cuenta_cobro.fecha_crea"});
        $arraybet["AccountBank"] = ($value->{"usuario_banco.cuenta"});
        $arraybet["payment_system_name"] = 'local';

        if ($value->{"cuenta_cobro.estado"} == "I") {
            $arraybet["State"] = 3;

        } elseif ($value->{"cuenta_cobro.estado"} == "A") {
            /* Condición que verifica si el estado de la cuenta de cobro es 'A'. */

            /* asigna un estado en un arreglo basado en el valor de una condición. */
            $arraybet["State"] = 0;

        } elseif ($value->{'cuenta_cobro.estado'} == 'E') {
            $arraybet['State'] = 1;

        } elseif ($value->{"cuenta_cobro.estado"} == "P") {

            /* Asignación de estados y fecha de pago en un arreglo basado en condiciones específicas. */
            $arraybet["State"] = 2;

        } elseif ($value->{"cuenta_cobro.estado"} == "R") {
            $arraybet["State"] = 4;

        }
        $arraybet["PayDate"] = ($value->{"cuenta_cobro.fecha_pago"});


        /* Agrega el contenido de $arraybet al final del array $cuentasData. */
        array_push($cuentasData, $arraybet);


    }


    /* Verifica si .count es numérico y lo asigna a $count, sin errores. */
    $count = 0;

    if (is_numeric($cuentas->count[0]->{'.count'})) {
        $count = $cuentas->count[0]->{'.count'};
    }


    $response["HasError"] = false;

    /* configura una respuesta exitosa con datos y errores modelo vacíos. */
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];
    $response["pos"] = $SkeepRows;
    $response["total_count"] = $count;
    $response["data"] = $cuentasData;


}
?>
