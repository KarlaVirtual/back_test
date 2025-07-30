<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\Usuario;

/**
 * Este script genera un resumen de depósitos basado en un rango de fechas.
 *
 * @param object $params Objeto JSON decodificado que contiene los siguientes parámetros:
 * @param string $params->MaxCreatedLocal Fecha máxima en formato local.
 * @param string $params->MinCreatedLocal Fecha mínima en formato local.
 * @param int $params->MaxRows Número máximo de filas a devolver.
 * @param int $params->OrderedItem Elemento de ordenación.
 * @param int $params->SkeepRows Número de filas a omitir.
 *
 * @return array $response Respuesta estructurada con los siguientes datos:
 *  - HasError (bool): Indica si ocurrió un error.
 *  - AlertType (string): Tipo de alerta (e.g., "success").
 *  - AlertMessage (string): Mensaje de alerta.
 *  - ModelErrors (array): Lista de errores del modelo.
 *  - Data (int): Número total de usuarios procesados.
 */

/* Se crea un objeto Usuario y se procesa una fecha en formato JSON. */
$Usuario = new Usuario();

$params = file_get_contents('php://input');
$params = json_decode($params);

$MaxCreatedLocal = date("Y-m-d H:00:00", strtotime(str_replace(" - ", " ", $params->MaxCreatedLocal) . ' +1 day'));

/* Asignación de parámetros de entrada a variables para procesamiento posterior. */
$MinCreatedLocal = $params->MinCreatedLocal;


$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;


/* inicializa variables si están vacías, estableciendo valores predeterminados. */
if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}


/* Configura la cantidad máxima de filas y genera una consulta JSON para usuarios. */
if ($MaxRows == "") {
    $MaxRows = 10;
}

$json = '{"rules" : [{"field" : "a.fecha_crea", "data": "' . $MinCreatedLocal . '","op":"ge"},{"field" : "a.fecha_crea", "data": "' . $MaxCreatedLocal . '","op":"le"}] ,"groupOp" : "AND"}';

$usuarios = $Usuario->getUsuarios("A", "", "a.usuario_id", "asc", $SkeepRows, $MaxRows, $json, true);


/* Se decodifica un JSON de usuarios en un array y se inicializa un array vacío. */
$usuarios = json_decode($usuarios);

$usuariosFinal = [];

foreach ($usuarios->data as $key => $value) {


    /* Se crea un array con datos del usuario a partir de un objeto. */
    $array = [];

    $array["Id"] = $value->{"a.usuario_id"};
    $array["Ip"] = $value->{"a.dir_ip"};
    $array["Login"] = $value->{"a.login"};
    $array["Estado"] = array($value->{"a.estado"});

    /* Asigna valores de un objeto a un array mediante claves específicas. */
    $array["EstadoEspecial"] = $value->{"a.estado_esp"};
    $array["PermiteRecargas"] = $value->{".permite_recarga"};
    $array["ImprimeRecibo"] = $value->{".recibo_caja"};
    $array["Pais"] = $value->{"a.pais_id"};
    $array["Idioma"] = $value->{"a.idioma"};
    $array["Nombre"] = $value->{"a.nombre"};

    /* Asigna valores a un array a partir de propiedades de un objeto. */
    $array["FirstName"] = $value->{"a.nombre"};
    $array["TipoUsuario"] = $value->{"e.perfil_id"};
    $array["Intentos"] = $value->{"a.intentos"};
    $array["Observaciones"] = $value->{"a.observ"};
    $array["PinAgent"] = $value->{".pinagent"};
    $array["BloqueoVentas"] = $value->{"a.bloqueo_ventas"};

    /* Asigna valores a un arreglo utilizando propiedades de un objeto $value. */
    $array["Moneda"] = $value->{"a.moneda"};
    $array["ActivarRecarga"] = $value->{"a.permite_activareg"};
    $array["City"] = $value->{"g.ciudad_nom"};
    $array["Phone"] = $value->{"f.telefono"};
    $array["FechaCrea"] = $value->{"a.fecha_crea"};
    $array["LastLoginLocalDate"] = $value->{"a.fecha_crea"};

    /* Se asignan valores a un array y se agrega a otro array de usuarios. */
    $array["FechaCrea"] = $value->{".fecha_ult"};
    $array["IsLocked"] = false;

    array_push($usuariosFinal, $array);

}


/* define una respuesta que indica éxito y cuenta usuarios sin errores. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = $usuarios->count[0]->{".count"};
