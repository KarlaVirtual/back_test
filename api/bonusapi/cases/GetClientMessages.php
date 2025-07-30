<?php
/**
 * Este script gestiona la obtención de mensajes de usuarios.
 * 
 * @param int $SkeepRows Número de filas a omitir en la consulta (por defecto 0).
 * @param int $OrderedItem Orden de los elementos en la consulta (por defecto 1).
 * @param int $MaxRows Número máximo de filas a devolver (por defecto 10).
 * 
 * @return array $response Respuesta estructurada que incluye:
 * - data: Contiene los mensajes obtenidos (array).
 * - Data: Datos de los mensajes obtenidos (array).
 * - code: Código de estado de la operación (int).
 * - rid: Identificador de la solicitud (string).
 */

use Backend\dto\UsuarioMensaje;


/* inicializa variables si están vacías: $SkeepRows y $OrderedItem. */
if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}


/* asigna 10 a $MaxRows si está vacío y define un array. */
if ($MaxRows == "") {
    $MaxRows = 10;
}

$mensajesRecibidos = [];


$json2 = '{"rules" : [{"field" : "usuario_mensaje.usuto_id", "data": "0","op":"eq"}] ,"groupOp" : "AND"}';


/* Se crea un objeto para obtener mensajes de usuarios y se decodifica en JSON. */
$UsuarioMensaje = new UsuarioMensaje();
$usuarios = $UsuarioMensaje->getUsuarioMensajesCustom(" usuario_mensaje.*,usufrom.* ", "usuario_mensaje.usumensaje_id", "asc", $SkeepRows, $MaxRows, $json2, true);
$usuarios = json_decode($usuarios);

foreach ($usuarios->data as $key => $value) {


    /* crea un arreglo asociativo con datos de usuario extraídos de un objeto. */
    $array = [];
    $array["Id"] = $value->{"usuario_mensaje.usumensaje_id"};
    $array["ClientId"] = $value->{"usuario_mensaje.usufrom_id"};
    $array["Login"] = $value->{"usuario_mensaje.usufrom_id"};
    $array["FirstName"] = $value->{"usufrom.nombres"};
    $array["LastName"] = $value->{"usufrom.apellidos"};

    /* verifica si un mensaje ha sido leído y asigna un estado correspondiente. */
    if ($value->{"usuario_mensaje.is_read"} == 1) {
        $array["State"] = 2;

    } else {
        $array["State"] = 0;

    }

    /* asigna valores de un objeto a un arreglo y lo añade a otro arreglo. */
    $array["Title"] = $value->{"usuario_mensaje.msubject"};
    $array["Message"] = $value->{"usuario_mensaje.body"};

    array_push($mensajesRecibidos, $array);

}


/* Se crea un array PHP para almacenar mensajes en una respuesta estructurada. */
$response = array();


$response["data"] = array(
    "messages" => array()
);


/* asigna datos y códigos a un objeto de respuesta JSON. */
$response["Data"] = $mensajesRecibidos;

$response["code"] = 0;
$response["rid"] = $json->rid;