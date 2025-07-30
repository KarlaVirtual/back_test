<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\UsuarioLealtad;

/**
 * Procesa los datos de una solicitud para obtener información de jugadores en un programa de lealtad.
 *
 * @param object $params Objeto JSON decodificado que contiene:
 * @param string $params->ResultToDate Fecha de finalización del rango.
 * @param string $params->ResultFromDate Fecha de inicio del rango.
 * @param array $params->LealtadDefinitionIds IDs de definiciones de lealtad.
 * @param string $params->PlayerExternalId ID externo del jugador.
 * @param string $params->Code Código del jugador.
 * @param string $params->ExternalId ID externo adicional.
 * @param int $params->ResultTypeId Tipo de resultado (1: A, 2: I, 3: E, 4: R, etc.).
 * @param int $params->IdBonus ID del bono de lealtad.
 * @param int $params->Limit Límite de filas a obtener.
 * @param string $params->OrderedItem Elemento por el cual ordenar.
 * @param int $params->Offset Número de páginas a omitir.
 * @param int $params->draw Parámetro de paginación.
 * @param int $params->length Número de filas a obtener.
 * @param int $params->start Número de filas a omitir.
 * @param array $params->columns Parámetros de columnas.
 * @param array $params->order Parámetros de ordenamiento.
 *
 * @return array $response Respuesta estructurada que incluye:
 * - HasError (bool): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta (e.g., "success").
 * - AlertMessage (string): Mensaje de alerta.
 * - ModelErrors (array): Lista de errores del modelo.
 * - Result (array): Resultado final.
 * - Data (array): Datos procesados de jugadores.
 * - Count (int): Número total de registros.
 */

/* obtiene y decodifica datos JSON desde la entrada estándar en PHP. */
$params = file_get_contents('php://input');
$params = json_decode($params);

$ToDateLocal = $params->ResultToDate;
$FromDateLocal = $params->ResultFromDate;
//$EndDate =str_replace(" ", "T", $params->EndDate) ;
//$BeginDate =str_replace(" ", "T", $params->BeginDate);

$LealtadDefinitionIds = $params->LealtadDefinitionIds;

/* Se asignan valores de parámetros y se mapean tipos de resultado mediante un switch. */
$PlayerExternalId = $params->PlayerExternalId;
$Code = $params->Code;
$ExternalId = $params->ExternalId;
$ResultTypeId = $params->ResultTypeId;
$IdLealtad = $params->IdBonus;

switch ($ResultTypeId) {
    case 1:
        $ResultTypeId = 'A';
        break;
    case 2:
        $ResultTypeId = 'I';
        break;
    case 3:
        $ResultTypeId = 'E';
        break;
    case 4:
        $ResultTypeId = 'R';
        break;
    default:
        $ResultTypeId = '';
        break;
}


/* Asignación de parámetros para paginación y ordenamiento de registros en una consulta. */
$MaxRows = $params->Limit;
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($params->Offset) * $MaxRows;

$OrderedItem = "usuario_lealtad.usulealtad_id";
$OrderType = "desc";


/* asigna valores de parámetros a variables y establece una condición para filas. */
$IdLealtad = $params->IdBonus;

$draw = $params->draw;
$length = $params->length;
$start = $params->start;

if ($start != "") {
    $SkeepRows = $start;

}


/* verifica si "$length" no está vacío y asigna su valor a "$MaxRows". */
if ($length != "") {
    $MaxRows = $length;

}

$columns = $params->columns;

/* Asignación del valor de orden desde los parámetros a la variable $order. */
$order = $params->order;

foreach ($order as $item) {

    switch ($columns[$item->column]->data) {
        case "Id":
            /* Asignación de variables según el caso "Id" en una estructura switch. */

            $OrderedItem = "usuario_lealtad.usulealtad_id";
            $OrderType = $item->dir;
            break;

        case "Date":
            /* asigna una fecha de creación basándose en una dirección especificada. */

            $OrderedItem = "usuario_lealtad.fecha_crea";
            $OrderType = $item->dir;
            break;

        case "PlayerExternalId":
            /* Código que establece parámetros de ordenamiento según el identificador externo del jugador. */

            $OrderedItem = "usuario_lealtad.usuario_id";
            $OrderType = $item->dir;
            break;

        case "AmountLealtad":
            /* Asignación de valores y tipo de orden según el caso "AmountLealtad" en código. */

            $OrderedItem = "usuario_lealtad.valor";
            $OrderType = $item->dir;

            break;

        case "AmountBase":
            /* Se asignan valores a variables según el tipo de orden en base a "AmountBase". */

            $OrderedItem = "usuario_lealtad.valor_base";
            $OrderType = $item->dir;
            break;

    }

}


/* establece reglas de condición para filtrar datos según lealtad y usuario. */
$rules = [];

array_push($rules, array("field" => "usuario_lealtad.lealtad_id", "data" => "$IdLealtad", "op" => "eq"));

if ($PlayerExternalId != "") {
    array_push($rules, array("field" => "usuario_lealtad.usuario_id", "data" => "$PlayerExternalId", "op" => "eq"));
}


/* Agrega reglas a un arreglo basándose en condiciones de variables no vacías. */
if ($Code != "") {
    array_push($rules, array("field" => "usuario_lealtad.codigo", "data" => "$Code", "op" => "eq"));
}


if ($ExternalId != "") {
    array_push($rules, array("field" => "usuario_lealtad.externo_id", "data" => "$ExternalId", "op" => "eq"));
}


/* Agrega reglas de filtrado basadas en id y fecha a un array. */
if ($ResultTypeId != "") {
    array_push($rules, array("field" => "usuario_lealtad.estado", "data" => "$ResultTypeId", "op" => "eq"));
}


if ($BeginDate != "") {
    array_push($rules, array("field" => "usuario_lealtad.fecha_crea", "data" => "$BeginDate", "op" => "ge"));
}

/* verifica una fecha y condiciona reglas según el país del usuario. */
if ($EndDate != "") {
    array_push($rules, array("field" => "usuario_lealtad.fecha_crea", "data" => "$EndDate", "op" => "le"));
}


// Si el usuario esta condicionado por País
if ($_SESSION['PaisCond'] == "S") {
}
// Si el usuario esta condicionado por el mandante y no es de Global

/* Agrega reglas a un filtro basado en la sesión del usuario. */
if ($_SESSION['Global'] == "N") {
    array_push($rules, array("field" => "lealtad_interna.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
}


$filtro = array("rules" => $rules, "groupOp" => "AND");


/* Establece valores predeterminados para $SkeepRows y $OrderedItem si están vacíos. */
if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}


/* establece un valor predeterminado y convierte un filtro a JSON. */
if ($MaxRows == "") {
    $MaxRows = 1000000000;
}

$json = json_encode($filtro);


$UsuarioLealtad = new UsuarioLealtad();

/* obtiene datos de usuario, los decodifica y prepara un arreglo final. */
$data = $UsuarioLealtad->getUsuarioLealtadCustom("usuario_lealtad.*,usuario.nombre", $OrderedItem, $OrderType, $SkeepRows, $MaxRows, $json, true, '');


$data = json_decode($data);

$final = [];

foreach ($data->data as $key => $value) {


    /* Crear un array asociativo con datos del usuario y lealtad. */
    $array = [];
    $array["Id"] = $value->{"usuario_lealtad.usulealtad_id"};
    $array["PlayerExternalId"] = $value->{"usuario_lealtad.usuario_id"};
    $array["PlayerName"] = $value->{"usuario.nombre"};
    $array["Amount"] = $value->{"usuario_lealtad.valor"};
    $array["AmountBase"] = $value->{"usuario_lealtad.valor_base"};
    $array["AmountLealtad"] = $value->{"usuario_lealtad.valor_lealtad"};
    $array["Code"] = $value->{"usuario_lealtad.codigo"};
    $array["AmountToWager"] = $value->{"usuario_lealtad.rollower_requerido"};
    $array["WageredAmount"] = $value->{"usuario_lealtad.apostado"};
    $array["Date"] = $value->{"usuario_lealtad.fecha_crea"};
    $array["ExternalId"] = $value->{"usuario_lealtad.externo_id"};
    $array["Prize"] = $value->{"usuario_lealtad.premio"};
    $array["Observation"] = $value->{"usuario_lealtad.observacion"};
    switch ($value->{"usuario_lealtad.estado"}) {
        case "A":
            /* Asigna el valor 1 a "ResultTypeId" si el caso es "A". */

            $array["ResultTypeId"] = 1;
            break;

        case "E":
            /* Establece el valor del campo "ResultTypeId" a 3 si la condición es "E". */

            $array["ResultTypeId"] = 3;
            break;

        case "R":
            /* asigna el valor 4 a "ResultTypeId" si el caso es "R". */

            $array["ResultTypeId"] = 4;
            break;

        case "I":
            /* asigna el valor 2 a "ResultTypeId" si el caso es "I". */

            $array["ResultTypeId"] = 2;
            break;

        case "D":
            /* Asigna el valor 5 a "ResultTypeId" en el array si la condición es "D". */

            $array["ResultTypeId"] = 5;
            break;


    }


    /* Agrega el contenido de `$array` al final del array `$final`. */
    array_push($final, $array);
}


/* Código establece una respuesta exitosa sin errores y con resultados finales. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Result"] = $final;
$response["Data"] = $final;
$response["Count"] = $data->count[0]->{".count"};
