<?php

use Backend\dto\UsuarioJackpot;

/**
 * Obtiene la lista de jugadores asociados a un jackpot.
 *
 * @param object $params Objeto JSON decodificado que contiene:
 * @param string $params->ResultToDate Fecha de fin de resultados.
 * @param string $params->ResultFromDate Fecha de inicio de resultados.
 * @param array $params->BonusDefinitionIds IDs de definiciones de bonos.
 * @param string $params->PlayerExternalId ID externo del jugador.
 * @param string $params->PlayerName Nombre del jugador.
 * @param string $params->State Estado del jugador en el jackpot.
 * @param string $params->Code Código asociado al jackpot.
 * @param int $params->limit Número máximo de filas a recuperar.
 * @param int $params->OrderedItem Elemento por el cual ordenar.
 * @param int $params->offset Número de páginas a omitir.
 * @param int $params->Id ID del jackpot.
 * @param int $params->draw Número de sorteo.
 * @param int $params->length Número de filas por página.
 * @param int $params->start Índice de inicio.
 *
 * @return array $response Respuesta estructurada con:
 *  - HasError (bool): Indica si ocurrió un error.
 *  - AlertType (string): Tipo de alerta (e.g., "success").
 *  - AlertMessage (array): Mensajes de alerta.
 *  - ModelErrors (array): Lista de errores del modelo.
 *  - Data (array): Lista de jugadores procesados.
 *  - Result (array): Resultado procesado.
 *  - Count (int): Número total de jugadores.
 */

/* obtiene y decodifica datos JSON de una solicitud, extrayendo fechas e identificadores. */
$params = file_get_contents('php://input');
$params = json_decode($params);

/* Se asignan valores de parámetros a variables para su posterior uso. */
$ToDateLocal = $params->ResultToDate;
$FromDateLocal = $params->ResultFromDate;
$BonusDefinitionsIds = $params->BonusDefinitionIds;
$PlayerExternalId = $params->PlayerExternalId;
$PlayerName = $params->PlayerName;
$State = $params->State;
$Code = $params->Code;

/* Asignación de parámetros y configuración para consultas a la base de datos. */
$MaxRows = $params->limit;
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($params->offset) * $MaxRows;


$OrderedItem = "usuario_jackpot.usujackpot_id";
$OrderedType = "desc";


/* Código PHP que captura y asigna parámetros de entrada para su uso posterior. */
$Id = $_REQUEST["Id"];
$Id = $params->Id;

$draw = $params->draw;
$length = $params->length;
$start = $params->start;


/* Establece variables para filas a omitir y máximo de filas si están definidas. */
if ($start != "") {
    $SkeepRows = $start;

}

if ($length != "") {
    $MaxRows = $length;
}


/* asigna columnas y orden, y define filas a omitir si 'start' no está vacío. */
$columns = $params->columns;
$order = $params->order;


if ($start != "") {
    $SkeepRows = $start;
}


/* Establece condiciones y reglas basadas en la longitud y el ID del jugador externo. */
if ($length != "") {
    $MaxRows = $length;
}

$rules = [];

if ($PlayerExternalId != "") {
    array_push($rules, array("field" => "usuario_mandante.usumandante_id", "data" => $PlayerExternalId, "op" => "eq"));
}

/* Agrega reglas a un array si las variables no están vacías. */
if ($PlayerName != "") {
    array_push($rules, array("field" => "usuario_mandante.nombres", "data" => $PlayerName, "op" => "eq"));
}
if ($Id != "") {
    array_push($rules, array("field" => "usuario_jackpot.jackpot_id", "data" => "$Id", "op" => "eq"));
}

/* añade una regla si $State no está vacío y verifica una condición de sesión. */
if ($State != "") {
    array_push($rules, array("field" => "usuario_jackpot.estado", "data" => "$State", "op" => "eq"));
}

if ($_SESSION['PaisCond'] == "S") {

}

/* Agrega una regla a un array si la sesión es "N", luego lo filtra. */
if ($_SESSION['Global'] == "N") {
    array_push($rules, array("field" => "jackpot_interno.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
}


$filtro = array("rules" => $rules, "groupOp" => "AND");


/* establece valores predeterminados para variables si están vacías. */
if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}

/* Se configura un orden y se convierte un filtro a formato JSON. */
$OrderedItem = 'jackpot_interno.orden';
$OrderedType = 'DESC';
if ($MaxRows == "") {
    $MaxRows = 1000000000;
}

$json = json_encode($filtro);


/* Se crea un objeto UsuarioJackpot y se obtiene datos decodificados en JSON. */
$UsuarioJackpot = new UsuarioJackpot();
$data = $UsuarioJackpot->getUsuarioJackpotCustom("usuario_jackpot.*,usuario_mandante.*,jackpot_interno.*", $OrderedItem, $OrderedType, $SkeepRows, $MaxRows, $json, true, false);


$data = json_decode($data);

$final = [];


foreach ($data->data as $key => $value) {


    /* asigna valores a un arreglo si el premio es mayor a cero. */
    $array = [];
    if ((int)$value->{"usuario_jackpot.valor_premio"} > 0) {
        $array["Id"] = $value->{"usuario_jackpot.usujackpot_id"};
        $array["UserId"] = $value->{"usuario_jackpot.usuario_id"};
        $array["State"] = "Ganador";
        $array["Prize"] = $value->{"usuario_jackpot.valor_premio"};
        $array["JackpotId"] = $value->{"usuario_jackpot.jackpot_id"};
    } else {
        /* Asignación de valores a un array basándose en propiedades de un objeto. */

        $array["Id"] = $value->{"usuario_jackpot.usujackpot_id"};
        $array["UserId"] = $value->{"usuario_jackpot.usuario_id"};
        $array["State"] = "Participante";
        $array["JackpotId"] = $value->{"usuario_jackpot.jackpot_id"};
        $array["Prize"] = "";

    }


    /* Asignación de datos a un array y adición a una lista final. */
    $array["PlayerExternalId"] = $value->{"usuario_mandante.usuario_mandante"};

    $array["PlayerName"] = $value->{"usuario_mandante.nombres"};
    $array["Amount"] = $value->{"usuario_jackpot.valor"};
    array_push($final, $array);
}


/* Código estructurado que define una respuesta con éxito y datos finales. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = [];
$response["ModelErrors"] = [];

$response["Data"] = $final;
$response["Result"] = $final;

$response["Count"] = $data->count[0]->{".count"};


?>
