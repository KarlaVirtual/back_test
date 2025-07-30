<?php


use Backend\dto\CuentaCobro;
use Backend\dto\TransapiusuarioLog;
use Backend\dto\UsuarioRecarga;
use Backend\dto\UsuarioTokenInterno;


/**
 * Este script realiza la conciliación de transacciones para un comercio en función de los parámetros proporcionados.
 *
 * @param object $params Objeto que contiene los siguientes valores:
 * @param string $params->shop Identificador del comercio.
 * @param string $params->token Token de autenticación.
 * @param int $params->type Tipo de conciliación (1 para transacciones activas, 2 para inactivas).
 * @param string $params->data Datos adicionales para la conciliación.
 * @param string $params->date Fecha de las transacciones a conciliar.
 * 
 *
 * @return array $response Arreglo que contiene:
 *  - error (int): Código de error (0 si no hay errores).
 *  - data (array): Datos de conciliación, incluyendo:
 *    - accountingDate (string): Fecha actual en formato ISO 8601.
 *    - items (array): Lista de transacciones con detalles como fecha, ID, monto y estado.
 *
 * @throws Exception Si alguno de los campos obligatorios está vacío o si ocurren errores durante el proceso.
 */

/* Código inicializa variables y registra la URI de la solicitud en un log. */
$MaxRows = 1;
$OrderedItem = 1;
$SkeepRows = 0;

$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . $_SERVER['REQUEST_URI'];

/* registra información de entrada en un archivo de log diario. */
$log = $log . "info=" . $_GET['info'];
$log = $log . trim(file_get_contents('php://input'));
//Save string to log, use FILE_APPEND to append.

fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

$shop = $params->shop;

/* obtiene parámetros y lanza una excepción si el campo 'shop' está vacío. */
$token = $params->token;
$type = $params->type;
$data = $params->data;
$date = $params->date;


if ($shop == "") {
    throw new Exception("Field: $shop", "50001");
}

/* Lanza excepciones si los campos 'token' o 'type' están vacíos. */
if ($token == "") {
    throw new Exception("Field: token", "50001");
}

if ($type == "") {
    throw new Exception("Field: type", "50001");
}


/* verifica si los campos "data" y "date" están vacíos, lanzando excepciones. */
if ($data == "") {
    throw new Exception("Field: data", "50001");

}
if ($date == "") {
    throw new Exception("Field: date", "50001");
}


/* Se definen variables para controlar filas y un arreglo para reglas. */
$MaxRows = 100;
$OrderedItem = 1;
$SkeepRows = 0;


$rules = [];


/* Se crean reglas de filtrado para una consulta basada en condiciones específicas. */
array_push($rules, array("field" => "usuario.usuario_id", "data" => "$shop", "op" => "eq"));
array_push($rules, array("field" => "usuario_token_interno.tipo", "data" => "0", "op" => "eq"));
array_push($rules, array("field" => "usuario_token_interno.estado", "data" => "'A'", "op" => "in"));
array_push($rules, array("field" => "usuario_token_interno.valor", "data" => $token, "op" => "eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND");


/* convierte un filtro a JSON y establece la configuración regional a checa. */
$json = json_encode($filtro);

setlocale(LC_ALL, 'czech');

$select = " usuario_token_interno.* ";

$UsuarioTokenInterno = new UsuarioTokenInterno();

/* Se obtienen datos de usuario, se decodifica JSON y se prepara respuesta. */
$data = $UsuarioTokenInterno->getUsuarioTokenInternosCustom($select, "usuario_token_interno.usutokeninterno_id", "asc", $SkeepRows, $MaxRows, $json, true);

$data = json_decode($data);

$response["error"] = 0;
$response["code"] = 0;


/* Inicializa un arreglo vacío llamado $final para almacenar datos posteriores. */
$final = [];

if ($type == 1) {


    /* Define reglas para filtrar registros por fecha en una consulta. */
    $MaxRows = 1000;
    $OrderedItem = 1;
    $SkeepRows = 0;
    $rules = [];
    array_push($rules, array("field" => "transapiusuario_log.fecha_crea", "data" => "$date" . " 00:00:00", "op" => "ge"));
    array_push($rules, array("field" => "transapiusuario_log.fecha_crea", "data" => "$date" . " 23:59:59", "op" => "le"));

    /* Se construye un filtro JSON con reglas para consultarle a una base de datos. */
    array_push($rules, array("field" => "transapiusuario_log.usuariogenera_id", "data" => $shop, "op" => "eq"));
    array_push($rules, array("field" => "transapiusuario_log.tipo", "data" => '"0","2"', "op" => "in"));


    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);


    /* Se obtiene y decodifica un conjunto de logs de usuario de una API. */
    $transApiUsuarioLog = new TransapiusuarioLog();
    $datos = $transApiUsuarioLog->getTransapiusuarioLogsCustom("transapiusuario_log.*", "transapiusuario_log.transapiusuariolog_id", "DESC", $SkeepRows, $MaxRows, $json, true, "");
    $datos = json_decode($datos);


    /* Transforma datos de transacciones en un array estructurado con formato y estado. */
    foreach ($datos->data as $key => $value) {
        $array = [];

        $fecha = str_replace(" ", "T", $value->{"transapiusuario_log.fecha_crea"});
        $array["transactionDate"] = $fecha;
        $array["transactionId"] = $value->{"transapiusuario_log.transaccion_id"};
        $array["accountId"] = $value->{"transapiusuario_log.usuario_id"};
        $array["paidAmount"] = number_format($value->{"transapiusuario_log.valor"}, 2, '.', ' ');
        $array["status"] = $value->{"transapiusuario_log.tipo"};
        if ($array["status"] == 0) {
            $array["status"] = "activo";
        } else if ($array["status"] == 1) {
            $array["status"] = "activo";
        } else if ($array["status"] == 2) {
            $array["status"] = "inactivo";
        } else if ($array["status"] == 3) {
            $array["status"] = "inactivo";
        }
        array_push($final, $array);
    }

}
if ($type == 2) {


    /* Se establece un límite de filas y una regla para filtrar por fecha. */
    $MaxRows = 1000;
    $OrderedItem = 1;
    $SkeepRows = 0;

    $rules = [];
    array_push($rules, array("field" => "transapiusuario_log.fecha_crea", "data" => "$date" . " 00:00:00", "op" => "ge"));

    /* Se crean reglas de filtrado para una consulta de logs en un array. */
    array_push($rules, array("field" => "transapiusuario_log.fecha_crea", "data" => "$date" . " 23:59:59", "op" => "le"));
    array_push($rules, array("field" => "transapiusuario_log.usuariogenera_id", "data" => $shop, "op" => "eq"));
    array_push($rules, array("field" => "transapiusuario_log.tipo", "data" => '"1","3"', "op" => "in"));


    $filtro = array("rules" => $rules, "groupOp" => "AND");

    /* convierte datos a JSON, consulta logs y decodifica el resultado. */
    $json = json_encode($filtro);

    $transApiUsuarioLog = new TransapiusuarioLog();
    $datos = $transApiUsuarioLog->getTransapiusuarioLogsCustom("transapiusuario_log.*", "transapiusuario_log.transapiusuariolog_id", "DESC", $SkeepRows, $MaxRows, $json, true, "");
    $datos = json_decode($datos);

    foreach ($datos->data as $key => $value) {

        /* convierte fechas a formato ISO 8601, reemplazando espacios por "T". */
        $array = [];

        $fechaActual = date('Y-m-d H:i:s');
        $fechaActual = str_replace(" ", "T", $fechaActual);

        $fecha = str_replace(" ", "T", $value->{"transapiusuario_log.fecha_crea"});

        /* Se asignan valores a un array según datos de transacciones y formato específico. */
        $array["transactionDate"] = $fecha;
        $array["transactionId"] = $value->{"transapiusuario_log.transaccion_id"};
        $array["accountId"] = $value->{"transapiusuario_log.usuario_id"};
        $array["paidAmount"] = number_format($value->{"transapiusuario_log.valor"}, 2, ',', ' ');
        $array["status"] = $value->{"transapiusuario_log.tipo"};
        if ($array["status"] == 0) {
            $array["status"] = "activo";
        } else if ($array["status"] == 1) {
            /* Se cambia el valor de "status" a "activo" si es igual a 1. */

            $array["status"] = "activo";
        } else if ($array["status"] == 2) {
            /* asigna el valor "inactivo" si el estado es igual a 2. */

            $array["status"] = "inactivo";
        } else if ($array["status"] == 3) {
            /* Modifica el estado del array a "inactivo" si es igual a 3. */

            $array["status"] = "inactivo";
        }


        /* Añade el contenido de `$array` al final del array `$final`. */
        array_push($final, $array);
    }

}


/* Genera un array con la fecha actual y otros elementos definidos. */
$array2 = [];
//date_default_timezone_set('America/Bogota');
/* Inicializa respuesta sin errores y asigna un array con $array2 a datos. */
$fechaActual = date('Y-m-d H:i:s');
$fechaActual = str_replace(" ", "T", $fechaActual);
$array2["accountingDate"] = $fechaActual;
$array2["items"] = $final;
$response["error"] = 0;
$response["data"] = array($array2);
