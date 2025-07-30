<?php

use Backend\dto\CuentaCobro;
use Backend\dto\Usuario;
use Backend\dto\UsuarioTokenInterno;

/**
 * Este script busca información sobre un retiro realizado por un usuario.
 *
 * @param object $params Objeto que contiene los siguientes valores:
 * @param string $params->shop Identificador del comercio.
 * @param string $params->token Token de autenticación.
 * @param string $params->withdrawId Nota asociada al retiro.
 * @param string $params->password Contraseña del usuario.
 * @param string $params->country País del usuario.
 * @param string $params->transactionId Identificador de la transacción.
 *
 * @return array $response Arreglo que contiene:
 *  - int $response["error"] Código de error (0 si no hay errores).
 *  - int|string $response["code"] Código adicional (0 si no hay errores).
 *  - string $response["name"] Nombre del usuario.
 *  - string $response["currency"] Moneda del usuario.
 *  - float $response["amount"] Monto del retiro.
 *  - string $response["userId"] Identificador del usuario.
 *
 * @throws Exception Si alguno de los campos obligatorios está vacío o si ocurren errores durante el proceso:
 *  - "Field: Key" (50001) Si el token está vacío.
 *  - "Field: Nota" (50001) Si la nota está vacía.
 *  - "Field: Clave" (50001) Si la contraseña está vacía.
 *  - "Field: Pais" (50001) Si el país está vacío.
 *  - "Usuario no pertenece al pais" (50005) Si el usuario no pertenece al país indicado.
 *  - "Código de país incorrecto" (10018) Si el código de país no coincide.
 *  - "Usuario no pertenece al partner" (50006) Si el usuario no pertenece al partner indicado.
 *  - "Datos de login incorrectos" (50003) Si las credenciales son incorrectas.
 */


//error_reporting(E_ALL);
//ini_set('display_errors', 'ON');


/* Se carga el autoload de Composer y se leen parámetros JSON de entrada. */
require(__DIR__ . '../../../../vendor/autoload.php');

$params = file_get_contents('php://input');
$params = json_decode($params);

$MaxRows = 1;

/* inicializa variables y registra la URI de la solicitud en un log. */
$OrderedItem = 1;
$SkeepRows = 0;


$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . $_SERVER['REQUEST_URI'];

/* registra información en un archivo de log con datos de entrada. */
$log = $log . "info=" . $_GET['info'];
$log = $log . trim(file_get_contents('php://input'));
//Save string to log, use FILE_APPEND to append.

fwriteCustom('log_' . date("Y-m-d") . '.log', $log);


$shop = $params->shop;

/* asigna variables a parámetros de entrada para procesar una transacción. */
$token = $params->token;
$nota = $params->withdrawId;
$clave = $params->password;
$pais = $params->country;

$transactionId = $params->transactionId;


/* lanza una excepción si el token está vacío, indicando un error. */
if ($token == "") {
    throw new Exception("Field: Key", "50001");

}
if ($token == "") {
    throw new Exception("Field: Key", "50001");

}


/* lanza excepciones si 'nota' o 'clave' están vacíos. */
if ($nota == "") {
    throw new Exception("Field: Nota", "50001");

}

if ($clave == "") {
    throw new Exception("Field: Clave", "50001");

}

/* verifica si $pais está vacío, lanzando una excepción si es así. */
if ($pais == "") {
    throw new Exception("Field: Pais", "50001");

}

$MaxRows = 1;

/* Inicializa variables para ordenar elementos y omitir filas en un conjunto de reglas. */
$OrderedItem = 1;
$SkeepRows = 0;


$rules = [];

/* array_push($rules, array("field" => "usuario.usuario_id", "data" => "$shop", "op" => "eq"));
 array_push($rules, array("field" => "usuario_log.tipo", "data" => "CODIGOAGENT", "op" => "eq"));
 array_push($rules, array("field" => "usuario_log.estado", "data" => "P,A", "op" => "in"));
 array_push($rules, array("field" => "usuario_log.valor_despues", "data" => $token, "op" => "eq"));

 $filtro = array("rules" => $rules, "groupOp" => "AND");

 $json = json_encode($filtro);

 setlocale(LC_ALL, 'czech');


 $select = " usuario_log.* ";


 $UsuarioLog = new UsuarioLog();
 $data = $UsuarioLog->getUsuarioLogsCustom($select, "usuario_log.usuariolog_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping);

 $data = json_decode($data);*/


/* Se crean reglas de filtrado para consultas, agrupadas con la operación AND. */
array_push($rules, array("field" => "usuario.usuario_id", "data" => "$shop", "op" => "eq"));
array_push($rules, array("field" => "usuario_token_interno.tipo", "data" => "0", "op" => "eq"));
array_push($rules, array("field" => "usuario_token_interno.estado", "data" => "'A'", "op" => "in"));
array_push($rules, array("field" => "usuario_token_interno.valor", "data" => $token, "op" => "eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND");


/* convierte un arreglo a formato JSON y establece la localización en checo. */
$json = json_encode($filtro);

setlocale(LC_ALL, 'czech');


$select = " usuario_token_interno.* ";


/* Se crea un objeto que obtiene tokens de usuario y se procesa su respuesta JSON. */
$UsuarioTokenInterno = new UsuarioTokenInterno();
$data = $UsuarioTokenInterno->getUsuarioTokenInternosCustom($select, "usuario_token_interno.usutokeninterno_id", "asc", $SkeepRows, $MaxRows, $json, true);

$data = json_decode($data);


$response["error"] = 0;

/* Asigna el valor 0 a la clave "code" del arreglo "$response". */
$response["code"] = 0;


if (count($data->data) > 0) {


    /* Validación de que dos usuarios pertenecen al mismo país antes de continuar. */
    $CuentaCobro = new CuentaCobro($nota, "", $clave);
    $Usuario = new Usuario($CuentaCobro->getUsuarioId());

    $UsuarioPuntoVenta = new Usuario($shop);

    if ($Usuario->paisId != $UsuarioPuntoVenta->paisId) {
        throw new Exception("Usuario no pertenece al pais", "50005");

    }

    /* Valida que el país y mandante del usuario sean correctos; lanza excepciones si fallan. */
    if ($Usuario->paisId != $pais && $UsuarioPuntoVenta != $pais) {
        throw new Exception("Código de país incorrecto", "10018");

    }
    if ($Usuario->mandante != $UsuarioPuntoVenta->mandante) {
        throw new Exception("Usuario no pertenece al partner", "50006");

    }


    /* Asigna datos del usuario y cuenta a una respuesta estructurada. */
    $response["name"] = $Usuario->nombre;
    $response["currency"] = $Usuario->moneda;
    $response["amount"] = $CuentaCobro->getValor();
    $response["userId"] = $CuentaCobro->getUsuarioId();


} else {
    /* lanza una excepción por datos de inicio de sesión incorrectos. */

    throw new Exception("Datos de login incorrectos", "50003");

}
