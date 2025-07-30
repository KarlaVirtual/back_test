<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\UsuarioTorneo;

/**
 * Obtiene y procesa información de jugadores en un torneo.
 *
 * @param string $params JSON de entrada con los siguientes valores:
 * @param string $params->ResultToDate Fecha de fin del resultado.
 * @param string $params->ResultFromDate Fecha de inicio del resultado.
 * @param array $params->BonusDefinitionIds IDs de definición de bonos.
 * @param string $params->PlayerExternalId ID externo del jugador.
 * @param string $params->Position Posición del jugador.
 * @param int $params->Limit Límite de filas.
 * @param string $params->OrderedItem Elemento para ordenar.
 * @param int $params->Offset Desplazamiento de filas.
 * @param int $params->draw Número de dibujo.
 * @param int $params->length Número de filas por página.
 * @param int $params->start Índice inicial.
 * @param array $params->columns Columnas para ordenar.
 * @param array $params->order Orden de las columnas.
 * 
 * 
 * @return array $response Respuesta estructurada con los siguientes valores:
 * - HasError (bool): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta ('success', 'error', etc.).
 * - AlertMessage (string): Mensaje de alerta.
 * - ModelErrors (array): Lista de errores del modelo.
 * - Result (array): Datos procesados de los jugadores.
 * - Data (array): Datos procesados de los jugadores.
 * - Count (int): Número total de registros.
 */

/* obtiene y decodifica datos JSON de una entrada HTTP en PHP. */
$params = file_get_contents('php://input');
$params = json_decode($params);

$ToDateLocal = $params->ResultToDate;
$FromDateLocal = $params->ResultFromDate;
$BonusDefinitionIds = $params->BonusDefinitionIds;

/* asigna variables a parámetros de entrada relacionados con un jugador y su posición. */
$PlayerExternalId = $params->PlayerExternalId;
$Position = $params->Position;


$MaxRows = $params->Limit;
$OrderedItem = $params->OrderedItem;

/* Se define un desplazamiento de filas y se establece ordenamiento de resultados. */
$SkeepRows = ($params->Offset) * $MaxRows;

$OrderedItem = "usuario_torneo.valor";
$OrderType = "desc";


$Id = $_REQUEST["Id"];

/* asigna valores de $params a variables y verifica si $start no está vacío. */
$Id = $params->Id;

$draw = $params->draw;
$length = $params->length;
$start = $params->start;

if ($start != "") {
    $SkeepRows = $start;

}


/* Establece el valor de $MaxRows si $length no está vacío. Obtiene columnas de $params. */
if ($length != "") {
    $MaxRows = $length;

}

$columns = $params->columns;

/* Se asigna el valor de 'order' desde el objeto '$params' a la variable '$order'. */
$order = $params->order;

foreach ($order as $item) {

    switch ($columns[$item->column]->data) {
        case "Position":
            /* asigna valores a variables según el caso "Position". */

            $OrderedItem = "position.position";
            $OrderType = $item->dir;
            break;

        case "PlayerExternalId":
            /* Asigna un identificador de usuario y tipo de orden según la dirección indicada. */

            $OrderedItem = "usuario_torneo.usuario_id";
            $OrderType = $item->dir;
            break;

        case "PlayerName":
            /* Asignación de ordenamiento para el nombre del jugador en una consulta basada en dirección. */

            $OrderedItem = "usuario_mandante.nombres";
            $OrderType = $item->dir;
            break;

        case "Amount":
            /* asigna valores a variables dependiendo del caso "Amount". */

            $OrderedItem = "usuario_torneo.valor";
            $OrderType = $item->dir;

            break;

        case "AmountBase":
            /* Asigna valores a $OrderedItem y $OrderType basados en "AmountBase". */

            $OrderedItem = "usuario_torneo.valor_base";
            $OrderType = $item->dir;
            break;

        case "AmountWin":
            /* Asignación de variables para ordenar premios en un torneo según dirección especificada. */

            $OrderedItem = "usuario_torneo.valor_premio";
            $OrderType = $item->dir;
            break;

        case "GGR":
            /* Calcula la diferencia entre valor_base y valor_premio para la orden "GGR". */

            $OrderedItem = "usuario_torneo.valor_base - usuario_torneo.valor_premio";
            $OrderType = $item->dir;
            break;
    }

}


/* asigna valores a variables basadas en condiciones de no vacío. */
if ($start != "") {
    $SkeepRows = $start;

}

if ($length != "") {
    $MaxRows = $length;

}


/* Se crea una regla si 'PlayerExternalId' no está vacío. */
$rules = [];

if ($PlayerExternalId != "") {
    array_push($rules, array("field" => "usuario_mandante.usuario_mandante", "data" => $PlayerExternalId, "op" => "eq"));

}


/* Condiciona reglas de filtrado basadas en posición, torneo y país del usuario. */
if ($Position != "") {
    array_push($rules, array("field" => "usuario_torneo.usutorneo_id", "data" => "$Position", "op" => "eq"));
}

array_push($rules, array("field" => "torneo_interno.torneo_id", "data" => "$Id", "op" => "eq"));


// Si el usuario esta condicionado por País
if ($_SESSION['PaisCond'] == "S") {
}
// Si el usuario esta condicionado por el mandante y no es de Global

/* Se verifica una condición para agregar reglas a un filtro en una sesión. */
if ($_SESSION['Global'] == "N") {
    array_push($rules, array("field" => "torneo_interno.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
}


$filtro = array("rules" => $rules, "groupOp" => "AND");


/* inicializa variables si están vacías, estableciendo valores predeterminados. */
if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}


/* Establece un máximo de filas y codifica un filtro en formato JSON. */
if ($MaxRows == "") {
    $MaxRows = 1000000000;
}

$json = json_encode($filtro);

$UsuarioTorneo = new UsuarioTorneo();

/* obtiene datos de torneos y los decodifica en formato JSON. */
$data = $UsuarioTorneo->getUsuarioTorneosCustom("usuario_torneo.*,usuario_mandante.nombres,position.position", $OrderedItem, $OrderType, $SkeepRows, $MaxRows, $json, true, true);

$data = json_decode($data);

$final = [];

$pos = 1;
foreach ($data->data as $key => $value) {


    /* Se crea un arreglo asociativo con información de un jugador y su torneo. */
    $array = [];
    $array["Position"] = $value->{"position.position"};
    $array["Id"] = $value->{"usuario_torneo.usutorneo_id"};
    $array["PlayerExternalId"] = $value->{"usuario_torneo.usuario_id"};
    $array["PlayerName"] = $value->{"usuario_mandante.nombres"};
    $array["Amount"] = $value->{"usuario_torneo.valor"};

    /* Se asignan y redondean valores de torneo a un arreglo. */
    $array["AmountBase"] = $value->{"usuario_torneo.valor_base"};
    $array["AmountWin"] = $value->{"usuario_torneo.valor_premio"};
    $array["GGR"] = ($value->{"usuario_torneo.valor_base"} - $value->{"usuario_torneo.valor_premio"});

    $array["Amount"] = round($array["Amount"], 2);
    $array["AmountBase"] = round($array["AmountBase"], 2);

    /* redondea valores y asigna propiedades de un objeto a un array. */
    $array["AmountWin"] = round($array["AmountWin"], 2);
    $array["GGR"] = round($array["GGR"], 2);


    $array["Code"] = $value->{"usuario_torneo.codigo"};
    $array["AmountToWager"] = $value->{"usuario_torneo.rollower_requerido"};

    /* asigna valores a un array según el estado de un torneo. */
    $array["WageredAmount"] = $value->{"usuario_torneo.apostado"};
    $array["Date"] = $value->{"usuario_torneo.fecha_crea"};
    $array["ExternalId"] = $value->{"usuario_torneo.externo_id"};

    switch ($value->{"usuario_torneo.estado"}) {
        case "A":
            $array["ResultTypeId"] = 1;
            break;

        case "E":
            $array["ResultTypeId"] = 3;
            break;

        case "R":
            $array["ResultTypeId"] = 4;
            break;

    }


    /* Agrega el contenido de `$array` al final de `$final` en PHP. */
    array_push($final, $array);
    $pos++;
}


/* Respuesta estructurada en formato JSON, indicando éxito y sin errores. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

/* Asigna datos finales y cuenta a un arreglo de respuesta en PHP. */
$response["Result"] = $final;
$response["Data"] = $final;
$response["Count"] = intval($data->count[0]->{".count"});
