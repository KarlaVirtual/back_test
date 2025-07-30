<?php

use Backend\dto\Usuario;
use Backend\dto\UsuarioTokenInterno;

/**
 * Este script busca información sobre un usuario en el sistema.
 *
 * @param object $params Objeto que contiene los siguientes valores:
 * @param string $params->shop Identificador del comercio.
 * @param string $params->token Token de autenticación.
 * @param string $params->document Documento del usuario.
 * @param string $params->userid Identificador del usuario.
 * @param string $params->country País del usuario.
 * @param string $params->phone Teléfono del usuario.
 *
 * @return array $response Arreglo que contiene:
 *  - int $response["error"] Código de error (0 si no hay errores).
 *  - int|string $response["code"] Código adicional (0 si no hay errores).
 *  - string $response["name"] Nombre del usuario.
 *  - string $response["userid"] Identificador del usuario.
 *
 * @throws Exception Si alguno de los campos obligatorios está vacío o si ocurren errores durante el proceso:
 *  - "Field: Key" (50001) Si el token está vacío.
 *  - "Field: document, userid, phone" (50001) Si todos los campos de identificación están vacíos.
 *  - "Field: Pais" (50001) Si el país está vacío.
 *  - "Usuario no pertenece al pais" (50005) Si el usuario no pertenece al país indicado.
 *  - "Usuario no pertenece al partner" (50006) Si el usuario no pertenece al partner indicado.
 *  - "Datos de login incorrectos" (50003) Si las credenciales son incorrectas.
 */

/* inicializa variables y registra la URI de la solicitud actual. */
$MaxRows = 1;
$OrderedItem = 1;
$SkeepRows = 0;

$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . $_SERVER['REQUEST_URI'];

/* Registra información de entradas GET y POST en un archivo de log. */
$log = $log . "info=" . $_GET['info'];
$log = $log . trim(file_get_contents('php://input'));
//Save string to log, use FILE_APPEND to append.

fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

/* valida la existencia del token en los parámetros recibidos. */
$shop = $params->shop;
$token = $params->token;
$document = $params->document;
$userid = $params->userid;
$pais = $params->country;
$phone = $params->phone;

if ($token == "") {
    throw new Exception("Field: Key", "50001");

}

/* verifica campos vacíos y lanza excepciones si es necesario. */
if ($document == "" && $userid == "" && $phone == "") {
    throw new Exception("Field: document, userid, phone", "50001");

}

if ($pais == "") {
    throw new Exception("Field: Pais", "50001");

}


/* Se definen variables para controlar filas y un array para reglas en un sistema. */
$MaxRows = 1;
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


/* crea un filtro con reglas para consultar datos de usuario y token. */
array_push($rules, array("field" => "usuario.usuario_id", "data" => "$shop", "op" => "eq"));
array_push($rules, array("field" => "usuario_token_interno.tipo", "data" => "0", "op" => "eq"));
array_push($rules, array("field" => "usuario_token_interno.estado", "data" => "'A'", "op" => "in"));
array_push($rules, array("field" => "usuario_token_interno.valor", "data" => $token, "op" => "eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND");


/* Convierte un array PHP a JSON y establece la configuración regional a checa. */
$json = json_encode($filtro);

setlocale(LC_ALL, 'czech');


$select = " usuario.mandante,usuario_token_interno.* ";


/* Se crea un token, obtiene datos y los decodifica en formato JSON. */
$UsuarioTokenInterno = new UsuarioTokenInterno();
$data = $UsuarioTokenInterno->getUsuarioTokenInternosCustom($select, "usuario_token_interno.usutokeninterno_id", "asc", $SkeepRows, $MaxRows, $json, true);


$data = json_decode($data);


$response["error"] = 0;

/* asigna el valor 0 a la clave "code" en el array $response. */
$response["code"] = 0;


if (count($data->data) > 0) {


    /* verifica si $userid no está vacío y crea un objeto Usuario. */
    if ($userid != "") {
        $Usuario = new Usuario($userid);

    } else {

        if ($document != "" || $phone != '') {

            /* crea reglas para validar campos de usuario y documento. */
            $rules = [];

            array_push($rules, array("field" => "usuario.mandante", "data" => $data->data[0]->{"usuario.mandante"}, "op" => "eq"));

            if ($document != "") {
                array_push($rules, array("field" => "registro.cedula", "data" => "$document", "op" => "eq"));
            }

            /* Condiciona la adición de reglas de filtrado según la variable de teléfono. */
            if ($phone != '') {
                array_push($rules, array("field" => "registro.celular", "data" => "$phone", "op" => "eq"));
            }


            $filtro = array("rules" => $rules, "groupOp" => "AND");

            /* Convierte filtro a JSON, obtiene usuarios personalizados y los decodifica. */
            $json = json_encode($filtro);

            $Usuario = new Usuario();

            $usuarios = $Usuario->getUsuariosCustom("  usuario.usuario_id ", "usuario.usuario_id", "asc", $SkeepRows, $MaxRows, $json, true);
            $usuarios = json_decode($usuarios);


            /* Se crea un objeto Usuario utilizando el ID del primer usuario en la lista. */
            $Usuario = new Usuario($usuarios->data[0]->{"usuario.usuario_id"});


        }
    }

    /* compara países de dos usuarios y lanza una excepción si no coinciden. */
    $UsuarioPuntoVenta = new Usuario($shop);

    if ($Usuario->paisId != $UsuarioPuntoVenta->paisId) {
        throw new Exception("Usuario no pertenece al pais", "50005");

    }


    /* Verifica la pertenencia de usuario a un partner, lanza excepción si no coincide. */
    if ($Usuario->mandante != $UsuarioPuntoVenta->mandante) {
        throw new Exception("Usuario no pertenece al partner", "50006");

    }

    $response["name"] = $Usuario->nombre;

    /* Asigna el ID de usuario a la variable de respuesta en formato de array. */
    $response["userid"] = $Usuario->usuarioId;

} else {
    /* Lanza una excepción si los datos de inicio de sesión son incorrectos. */

    throw new Exception("Datos de login incorrectos", "50003");

}



