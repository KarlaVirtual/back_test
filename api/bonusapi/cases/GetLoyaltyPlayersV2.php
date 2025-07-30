<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\UsuarioLealtad;

/**
 * Este script procesa datos de jugadores relacionados con lealtades, aplicando filtros
 * y reglas para generar una respuesta estructurada con información detallada.
 *
 * @param object $params Objeto JSON decodificado que contiene los siguientes parámetros:
 * @param string $params->CasinoId Identificador del casino.
 * @param string $params->Id Identificador del jugador.
 * @param string $params->BeginDateModified Fecha de inicio de modificación.
 * @param string $params->EndDateModified Fecha de fin de modificación.
 * @param string $params->BeginDate Fecha de inicio.
 * @param string $params->EndDate Fecha de fin.
 * @param string $params->LealtadDefinitionIds Identificadores de definiciones de lealtad.
 * @param string $params->PlayerExternalId Identificador externo del jugador.
 * @param string $params->Code Código asociado al jugador.
 * @param string $params->ExternalId Identificador externo.
 * @param int $params->ResultTypeId Tipo de resultado (1: A, 2: I, etc.).
 * @param int $params->IdBonus Identificador de la bonificación.
 * @param string $params->TypeAward Tipo de premio.
 * @param int $params->Limit Límite de filas a consultar.
 * @param int $params->Offset Desplazamiento para la consulta.
 * @param int $params->draw Parámetro para DataTables.
 * @param int $params->length Número de filas por página.
 * @param int $params->start Índice inicial para la consulta.
 * @param array $params->columns Columnas para ordenar.
 * @param array $params->order Orden de las columnas.
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

/* lee y decodifica datos JSON, separando fechas en componentes. */
$params = file_get_contents('php://input');
$params = json_decode($params);

$BeginDateModified = explode("T", $params->BeginDateModified);
$EndDateModified = explode("T", $params->EndDateModified);

$EndDate = explode("T", $params->EndDate);

/* procesa fechas, extrayendo componentes de strings en formato específico. */
$EndDate = $EndDate[0];

$BeginDate = explode("T", $params->BeginDate);
$BeginDate = $BeginDate[0];

$BeginDateModified = $BeginDateModified[0];

/* Se extraen valores específicos de un objeto llamado $params en el código. */
$EndDateModified = $EndDateModified[0];

$LealtadDefinitionIds = $params->LealtadDefinitionIds;
$PlayerExternalId = $params->PlayerExternalId;
$Code = $params->Code;
$ExternalId = $params->ExternalId;

/* Transforma el identificador ResultTypeId en letras basadas en su valor numérico. */
$ResultTypeId = $params->ResultTypeId;
$IdLealtad = $params->IdBonus;
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


/* Código para establecer límites, orden y desplazamiento en una consulta de datos. */
$MaxRows = $params->Limit;
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($params->Offset) * $MaxRows;

$OrderedItem = "usuario_lealtad.usulealtad_id";
$OrderType = "desc";

/* Asignación de parámetros para gestionar datos de un casino y lealtad en una aplicación. */
$CasinoId = $params->CasinoId;
$IdLealtad = $params->IdBonus;

$draw = $params->draw;
$length = $params->length;
$start = $params->start;

/* asigna valores a variables según condiciones de entrada de parámetros. */
$Id = $params->Id;
if ($start != "") {
    $SkeepRows = $start;

}

if ($length != "") {
    $MaxRows = $length;

}


/* Asignación de columnas y orden a partir de los parámetros proporcionados. */
$columns = $params->columns;
$order = $params->order;

foreach ($order as $item) {

    switch ($columns[$item->column]->data) {
        case "Id":
            /* Asigna un ID de lealtad basado en dirección del ítem en un caso específico. */

            $OrderedItem = "usuario_lealtad.usulealtad_id";
            $OrderType = $item->dir;
            break;

        case "Date":
            /* Código que asigna una fecha de creación a un ítem y define su orden. */

            $OrderedItem = "usuario_lealtad.fecha_crea";
            $OrderType = $item->dir;
            break;

        case "PlayerExternalId":
            /* Se asigna el ID de usuario a una variable según dirección especificada. */

            $OrderedItem = "usuario_lealtad.usuario_id";
            $OrderType = $item->dir;
            break;

        case "AmountLealtad":
            /* asigna valores a variables según la opción "AmountLealtad". */

            $OrderedItem = "usuario_lealtad.valor";
            $OrderType = $item->dir;

            break;

        case "AmountBase":
            /* Asigna valores a variables según el caso "AmountBase" en un código PHP. */

            $OrderedItem = "usuario_lealtad.valor_base";
            $OrderType = $item->dir;
            break;

    }

}


/* Añade reglas de validación para un usuario basado en su lealtad y ID externo. */
$rules = [];

array_push($rules, array("field" => "usuario_lealtad.lealtad_id", "data" => "$IdLealtad", "op" => "eq"));

if ($PlayerExternalId != "") {
    array_push($rules, array("field" => "usuario_lealtad.usuario_id", "data" => "$PlayerExternalId", "op" => "eq"));
}


/* Agrega reglas a un array basadas en condiciones de CasinoId e Id. */
if ($CasinoId != "") {

    array_push($rules, array("field" => "usuario_mandante.usumandante_id", "data" => "$CasinoId", "op" => "eq"));
}
if ($Id != "") {

    array_push($rules, array("field" => "usuario_lealtad.usulealtad_id", "data" => $Id, "op" => "eq"));
}


/* Agrega reglas a un array si las variables no están vacías. */
if ($Code != "") {
    array_push($rules, array("field" => "usuario_lealtad.codigo", "data" => "$Code", "op" => "eq"));
}


if ($ExternalId != "") {
    array_push($rules, array("field" => "usuario_lealtad.externo_id", "data" => "$ExternalId", "op" => "eq"));
}


/* Agrega reglas a un array dependiendo de variables no vacías. */
if ($ResultTypeId != "") {
    array_push($rules, array("field" => "usuario_lealtad.estado", "data" => "$ResultTypeId", "op" => "eq"));
}


if ($BeginDate != "") {
    array_push($rules, array("field" => "usuario_lealtad.fecha_crea", "data" => "$BeginDate 00:00:00", "op" => "ge"));
}

/* Agrega reglas de filtrado de fechas al array según las condiciones establecidas. */
if ($EndDate != "") {
    array_push($rules, array("field" => "usuario_lealtad.fecha_crea", "data" => "$EndDate 23:59:59", "op" => "le"));
}
if ($BeginDateModified != "") {
    array_push($rules, array("field" => "usuario_lealtad.fecha_modif", "data" => "$BeginDateModified 00:00:00", "op" => "ge"));
}

/* verifica una fecha y añade reglas si el país está condicionado. */
if ($EndDateModified != "") {
    array_push($rules, array("field" => "usuario_lealtad.fecha_modif", "data" => "$EndDateModified 23:59:59", "op" => "le"));
}
// Si el usuario esta condicionado por País
if ($_SESSION['PaisCond'] == "S") {
}
// Si el usuario esta condicionado por el mandante y no es de Global

/* Agrega reglas a un array basado en condiciones de sesión y tipo de premio. */
if ($_SESSION['Global'] == "N") {
    array_push($rules, array("field" => "lealtad_interna.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
}
if ($TypeAward != "") {
    array_push($rules, array("field" => "lealtad_interna.tipo_premio", "data" => $TypeAward, "op" => "eq"));
}


/* Define un filtro y ajusta valores de variables si están vacías. */
$filtro = array("rules" => $rules, "groupOp" => "AND");

if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}


/* establece un límite de filas y convierte un filtro a JSON. */
if ($MaxRows == "") {
    $MaxRows = 1000000000;
}

$json = json_encode($filtro);


$UsuarioLealtad = new UsuarioLealtad();

/* obtiene y decodifica datos de usuario lealtad en formato JSON. */
$data = $UsuarioLealtad->getUsuarioLealtadCustom("usuario_lealtad.*,usuario.nombre,usuario_mandante.usumandante_id,lealtad_interna.nombre,lealtad_interna.tipo_premio", $OrderedItem, $OrderType, $SkeepRows, $MaxRows, $json, true, '');


$data = json_decode($data);

$final = [];

foreach ($data->data as $key => $value) {


    /* crea un array asociativo con datos de usuario y lealtad. */
    $array = [];
    $array["Id"] = $value->{"usuario_lealtad.usulealtad_id"};
    $array["PlayerExternalId"] = $value->{"usuario_lealtad.usuario_id"};
    $array["PlayerName"] = $value->{"usuario.nombre"};
    $array["Amount"] = $value->{"usuario_lealtad.valor"};
    $array["AmountBase"] = $value->{"usuario_lealtad.valor_base"};

    /* asigna valores de un objeto a un array asociativo. */
    $array["AmountLealtad"] = $value->{"usuario_lealtad.valor_lealtad"};
    $array["Code"] = $value->{"usuario_lealtad.codigo"};
    $array["AmountToWager"] = $value->{"usuario_lealtad.rollower_requerido"};
    $array["WageredAmount"] = $value->{"usuario_lealtad.apostado"};
    $array["Date"] = $value->{"usuario_lealtad.fecha_crea"};
    $array["ExternalId"] = $value->{"usuario_lealtad.externo_id"};

    /* asigna valores a un array, manejando premios y observaciones. */
    $array["TypeAward"] = intval($value->{"lealtad_interna.tipo_premio"});
    $array["Observation"] = $value->{"usuario_lealtad.observacion"};
    $array["Prize"] = $value->{"usuario_lealtad.premio"};
    if ($array["Prize"] == "") {
        $array["Prize"] = $value->{"lealtad_interna.nombre"};
    }

    /* asigna valores a un array utilizando datos de un objeto. */
    $array["CasinoId"] = $value->{"usuario_mandante.usumandante_id"};
    $array["GiftId"] = $value->{"usuario_lealtad.lealtad_id"};
    switch ($value->{"usuario_lealtad.estado"}) {
        case "A":
            /* Asigna el valor 1 a "ResultTypeId" si el caso es "A". */

            $array["ResultTypeId"] = 1;
            break;

        case "E":
            /* Asigna el valor 3 a "ResultTypeId" si el caso es "E". */

            $array["ResultTypeId"] = 3;
            break;

        case "R":
            /* Asigna el valor 4 a "ResultTypeId" si el caso es "R". */

            $array["ResultTypeId"] = 4;
            break;

        case "I":
            /* Asigna el valor 2 a "ResultTypeId" si el caso es "I". */

            $array["ResultTypeId"] = 2;
            break;

        case "D":
            /* Asigna 5 a "ResultTypeId" en el array, si el caso es "D". */

            $array["ResultTypeId"] = 5;
            break;


    }

    /* Se agrega la fecha de modificación del usuario a un array final. */
    $array["DateModified"] = $value->{"usuario_lealtad.fecha_modif"};

    array_push($final, $array);
}


/* Código que construye una respuesta exitosa sin errores ni mensajes. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Result"] = $final;

/* Asigna datos finales y conteo a un arreglo de respuesta en PHP. */
$response["Data"] = $final;
$response["Count"] = $data->count[0]->{".count"};
