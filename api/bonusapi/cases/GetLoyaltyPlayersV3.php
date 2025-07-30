<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\UsuarioLealtad;

/**
 * Este script procesa datos de jugadores relacionados con lealtades, aplicando filtros
 * y reglas para generar una respuesta estructurada con información detallada.
 *
 * @param object $params Objeto JSON decodificado que contiene los siguientes parámetros:
 * @param string $params->ResultToDate Fecha de fin de resultados.
 * @param string $params->ResultFromDate Fecha de inicio de resultados.
 * @param string $params->BeginDateModified Fecha de inicio de modificación.
 * @param string $params->EndDateModified Fecha de fin de modificación.
 * @param string $params->BeginDate Fecha de inicio.
 * @param string $params->EndDate Fecha de fin.
 * @param array $params->LealtadDefinitionIds Identificadores de definiciones de lealtad.
 * @param string $params->PlayerExternalId Identificador externo del jugador.
 * @param string $params->Code Código asociado al jugador.
 * @param string $params->ExternalId Identificador externo.
 * @param int $params->ResultTypeId Tipo de resultado (1: A, 2: I, etc.).
 * @param int $params->IdBonus Identificador de la bonificación.
 * @param string $params->Country País asociado al jugador.
 * @param int $params->CasinoId Identificador del casino.
 * @param int $params->TypeAward Tipo de premio.
 * @param int $params->Limit Límite de filas a consultar.
 * @param int $params->Offset Desplazamiento para la consulta.
 * @param int $params->draw Parámetro para DataTables.
 * @param int $params->length Número de filas por página.
 * @param int $params->start Índice inicial para la consulta.
 * @param array $params->columns Columnas para ordenar.
 * @param array $params->order Orden de las columnas.
 * 
 * 
 *
 * @return array $response Respuesta estructurada con los siguientes elementos:
 *  - HasError: Indica si ocurrió un error (false si no hay errores).
 *  - AlertType: Tipo de alerta (success, error, etc.).
 *  - AlertMessage: Mensaje de alerta.
 *  - ModelErrors: Lista de errores de validación.
 *  - Result: Array con los datos procesados de jugadores.
 *  - Data: Array con los datos procesados de jugadores.
 *  - Count: Número total de registros encontrados.
 */

/* recibe datos JSON, los decodifica y extrae fechas específicas. */
$params = file_get_contents('php://input');
$params = json_decode($params);

$ToDateLocal = $params->ResultToDate;
$FromDateLocal = $params->ResultFromDate;


$BeginDateModified = explode("T", $params->BeginDateModified);

/* Separa fechas en formato "T" y obtiene solo la parte de la fecha. */
$EndDateModified = explode("T", $params->EndDateModified);

$EndDate = explode("T", $params->EndDate);
$EndDate = $EndDate[0];

$BeginDate = explode("T", $params->BeginDate);

/* asigna valores iniciales a variables a partir de arrays. */
$BeginDate = $BeginDate[0];

$BeginDateModified = $BeginDateModified[0];
$EndDateModified = $EndDateModified[0];

$LealtadDefinitionIds = $params->LealtadDefinitionIds;

/* Asignación de valores de parámetros a variables para su posterior uso en el código. */
$PlayerExternalId = $params->PlayerExternalId;
$Code = $params->Code;
$ExternalId = $params->ExternalId;
$ResultTypeId = $params->ResultTypeId;
$IdLealtad = $params->IdBonus;
$Country = $params->Country;

/* asigna valores de tipo según el identificador de resultado. */
$CasinoId = $params->CasinoId;
$TypeAward = $params->TypeAward;
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
    case 5:
        $ResultTypeId = 'D';
        break;
    default:
        $ResultTypeId = '';
        break;
}


/* configura parámetros para paginación y ordenamiento de datos. */
$MaxRows = $params->Limit;
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($params->Offset) * $MaxRows;

$OrderedItem = "usuario_lealtad.usulealtad_id";
$OrderType = "desc";


/* recopila parámetros relacionados con lealtad y paginación desde una solicitud. */
$IdLealtad = $params->IdBonus;

$draw = $params->draw;
$length = $params->length;
$start = $params->start;

$Id = $params->Id;


/* asigna valores a variables según condiciones de entrada no vacías. */
if ($start != "") {
    $SkeepRows = $start;

}

if ($length != "") {
    $MaxRows = $length;

}


/* Asigna columnas y orden a partir de parámetros proporcionados. */
$columns = $params->columns;
$order = $params->order;

foreach ($order as $item) {

    switch ($columns[$item->column]->data) {
        case "Id":
            /* asocia un identificador de lealtad con un tipo de orden específico. */

            $OrderedItem = "usuario_lealtad.usulealtad_id";
            $OrderType = $item->dir;
            break;

        case "Date":
            /* asigna una columna y dirección de orden según un caso específico. */

            $OrderedItem = "usuario_lealtad.fecha_crea";
            $OrderType = $item->dir;
            break;

        case "PlayerExternalId":
            /* Código que asigna un identificador y ordena según dirección de un item. */

            $OrderedItem = "usuario_lealtad.usuario_id";
            $OrderType = $item->dir;
            break;

        case "AmountLealtad":
            /* asigna un valor y tipo de orden basado en "AmountLealtad". */

            $OrderedItem = "usuario_lealtad.valor";
            $OrderType = $item->dir;

            break;

        case "AmountBase":
            /* Asignación de valores para un caso específico en una estructura de control. */

            $OrderedItem = "usuario_lealtad.valor_base";
            $OrderType = $item->dir;
            break;

    }

}


/* Agrega reglas de filtrado basadas en los valores de $Id y $CasinoId. */
$rules = [];
if ($Id != "") {

    array_push($rules, array("field" => "usuario_lealtad.usulealtad_id", "data" => $Id, "op" => "eq"));
}

if ($CasinoId != "") {

    array_push($rules, array("field" => "usuario_mandante.usumandante_id", "data" => "$CasinoId", "op" => "eq"));
}


/* Añade reglas a un array según las variables `$IdLealtad` y `$ResultTypeId`. */
if ($IdLealtad != "") {

    array_push($rules, array("field" => "usuario_lealtad.lealtad_id", "data" => "$IdLealtad", "op" => "eq"));
}
if ($ResultTypeId != "") {
    array_push($rules, array("field" => "usuario_lealtad.estado", "data" => $ResultTypeId, "op" => "eq"));
} else {
    /* Agrega una regla para el campo "usuario_lealtad.estado" con valores permitidos 'R' y 'D'. */

    array_push($rules, array("field" => "usuario_lealtad.estado", "data" => "'R','D'", "op" => "in"));
}


/* Agrega reglas a un arreglo si el ID de jugador o código no están vacíos. */
if ($PlayerExternalId != "") {
    array_push($rules, array("field" => "usuario_lealtad.usuario_id", "data" => "$PlayerExternalId", "op" => "eq"));
}


if ($Code != "") {
    array_push($rules, array("field" => "usuario_lealtad.codigo", "data" => "$Code", "op" => "eq"));
}


/* Agrega reglas a un array basadas en condiciones de variables no vacías. */
if ($ExternalId != "") {
    array_push($rules, array("field" => "usuario_lealtad.externo_id", "data" => "$ExternalId", "op" => "eq"));
}


if ($ResultTypeId != "") {
    array_push($rules, array("field" => "usuario_lealtad.estado", "data" => "$ResultTypeId", "op" => "eq"));
}


/* Agrega reglas de fecha a un array si las fechas de inicio y fin están definidas. */
if ($BeginDate != "") {
    array_push($rules, array("field" => "usuario_lealtad.fecha_crea", "data" => "$BeginDate 00:00:00", "op" => "ge"));
}
if ($EndDate != "") {
    array_push($rules, array("field" => "usuario_lealtad.fecha_crea", "data" => "$EndDate 23:59:59", "op" => "le"));
}

/* Agrega reglas de fecha para filtrar registros modificados dentro de un rango específico. */
if ($BeginDateModified != "") {
    array_push($rules, array("field" => "usuario_lealtad.fecha_modif", "data" => "$BeginDateModified 00:00:00", "op" => "ge"));
}
if ($EndDateModified != "") {
    array_push($rules, array("field" => "usuario_lealtad.fecha_modif", "data" => "$EndDateModified 23:59:59", "op" => "le"));
}

// Si el usuario esta condicionado por País

/* Condiciona reglas basadas en el país y el estado del usuario. */
if ($Country != "") {
    array_push($rules, array("field" => "usuario.pais_id", "data" => $Country, "op" => "eq"));
}
// Si el usuario esta condicionado por el mandante y no es de Global
if ($_SESSION['Global'] == "N") {
    array_push($rules, array("field" => "lealtad_interna.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
}

/* Condicionalmente agrega reglas a un filtro basado en el tipo de premio especificado. */
if ($TypeAward != "") {
    array_push($rules, array("field" => "lealtad_interna.tipo_premio", "data" => $TypeAward, "op" => "eq"));
}

$filtro = array("rules" => $rules, "groupOp" => "AND");

if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* Asignación de valores predeterminados para variables si están vacías en PHP. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 1000;
}


/* convierte datos en JSON, consulta usuarios y los decodifica. */
$json = json_encode($filtro);


$UsuarioLealtad = new UsuarioLealtad();
$data = $UsuarioLealtad->getUsuarioLealtadCustom("usuario_lealtad.*,usuario.nombre,usuario_mandante.usumandante_id,lealtad_interna.nombre,lealtad_interna.tipo_premio", $OrderedItem, $OrderType, $SkeepRows, $MaxRows, $json, true, '');


$data = json_decode($data);


/* Se define un array vacío llamado final, preparado para almacenar elementos. */
$final = [];

foreach ($data->data as $key => $value) {


    /* asigna valores de un objeto a un array asociativo en PHP. */
    $array = [];
    $array["Id"] = $value->{"usuario_lealtad.usulealtad_id"};
    $array["PlayerExternalId"] = $value->{"usuario_lealtad.usuario_id"};
    $array["PlayerName"] = $value->{"usuario.nombre"};
    $array["Amount"] = $value->{"usuario_lealtad.valor"};
    $array["AmountBase"] = $value->{"usuario_lealtad.valor_base"};

    /* Asignación de valores de un objeto a un array asociativo en PHP. */
    $array["AmountLealtad"] = $value->{"usuario_lealtad.valor_lealtad"};
    $array["Code"] = $value->{"usuario_lealtad.codigo"};
    $array["AmountToWager"] = $value->{"usuario_lealtad.rollower_requerido"};
    $array["WageredAmount"] = $value->{"usuario_lealtad.apostado"};
    $array["Date"] = $value->{"usuario_lealtad.fecha_crea"};
    $array["ExternalId"] = $value->{"usuario_lealtad.externo_id"};


    /* asigna valores de una estructura a un arreglo basado en condiciones. */
    $array["TypeAward"] = intval($value->{"lealtad_interna.tipo_premio"});


    $array["Observation"] = $value->{"usuario_lealtad.observacion"};
    $array["Prize"] = $value->{"usuario_lealtad.premio"};
    if ($array["Prize"] == "") {
        $array["Prize"] = $value->{"lealtad_interna.nombre"};
    }

    /* asigna valores a un array desde un objeto utilizando propiedades específicas. */
    $array["CasinoId"] = $value->{"usuario_mandante.usumandante_id"};
    $array["GiftId"] = $value->{"usuario_lealtad.lealtad_id"};
    switch ($value->{"usuario_lealtad.estado"}) {
        case "A":
            /* Asigna el valor 1 a "ResultTypeId" si el caso es "A". */

            $array["ResultTypeId"] = 1;
            break;

        case "E":
            /* Asignación del valor 3 a "ResultTypeId" si el caso es "E". */

            $array["ResultTypeId"] = 3;
            break;

        case "R":
            /* asigna el valor 4 a "ResultTypeId" si el caso es "R". */

            $array["ResultTypeId"] = 4;
            break;

        case "I":
            /* asigna el valor 2 a "ResultTypeId" si se cumple la condición "I". */

            $array["ResultTypeId"] = 2;
            break;

        case "D":
            /* asigna el valor 5 a "ResultTypeId" si "D" es el caso. */

            $array["ResultTypeId"] = 5;
            break;


    }

    /* asigna una fecha y la agrega a un array final. */
    $array["DateModified"] = $value->{"usuario_lealtad.fecha_modif"};

    array_push($final, $array);
}


/* establece una respuesta de éxito sin errores ni mensajes de alerta. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Result"] = $final;

/* Asigna datos finales y un conteo a un arreglo de respuesta en PHP. */
$response["Data"] = $final;
$response["Count"] = $data->count[0]->{".count"};
