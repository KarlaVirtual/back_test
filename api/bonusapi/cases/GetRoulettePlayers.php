<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\UsuarioRuleta;

/**
 * Este script procesa una solicitud HTTP para obtener información sobre jugadores de ruleta.
 *
 * @param object $params Objeto JSON decodificado que contiene:
 * @param string $params->ResultToDate Fecha y hora de fin de los resultados.
 * @param string $params->ResultFromDate Fecha y hora de inicio de los resultados.
 * @param array $params->BonusDefinitionIds IDs de definiciones de bonificación.
 * @param string $params->PlayerExternalId ID externo del jugador.
 * @param int $params->Limit Número máximo de filas a obtener.
 * @param string $params->OrderedItem Campo por el cual ordenar los resultados.
 * @param int $params->Offset Número de filas a omitir.
 * @param int $params->draw Número de dibujo para paginación.
 * @param int $params->length Número de filas por página.
 * @param int $params->start Índice inicial para la paginación.
 * @param array $params->columns Columnas para ordenar.
 * @param array $params->order Orden de las columnas.
 *
 * @return array $response Respuesta estructurada que contiene:
 *  - HasError (bool): Indica si ocurrió un error.
 *  - AlertType (string): Tipo de alerta ("success", "error", etc.).
 *  - AlertMessage (string): Mensaje de alerta.
 *  - ModelErrors (array): Lista de errores de modelo.
 *  - Result (array): Datos de los jugadores, incluyendo:
 *      - Position (int): Posición del jugador.
 *      - Id (int): ID del jugador en la ruleta.
 *      - PlayerExternalId (string): ID externo del jugador.
 *      - PlayerName (string): Nombre del jugador.
 *      - Amount (float): Monto apostado.
 *      - AmountBase (float): Monto base.
 *      - AmountWin (float): Monto ganado.
 *      - GGR (float): Ganancia bruta.
 *      - Code (string): Código del jugador.
 *      - AmountToWager (float): Monto requerido para apostar.
 *      - WageredAmount (float): Monto apostado.
 *      - Date (string): Fecha de creación.
 *      - ExternalId (string): ID externo.
 *      - State (string): Estado del jugador ("Activo", "Pendiente", "Redimido").
 *      - Description (string): Descripción del premio.
 *  - Count (int): Número total de registros.
 */

/* procesa datos JSON recibidos, extrayendo fechas y IDs de bonificación. */
$params = file_get_contents('php://input');
$params = json_decode($params);

$ToDateLocal = $params->ResultToDate;
$FromDateLocal = $params->ResultFromDate;
$BonusDefinitionIds = $params->BonusDefinitionIds;

/* asigna parámetros para gestionar límites y orden en una consulta. */
$PlayerExternalId = $params->PlayerExternalId;

$MaxRows = $params->Limit;
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($params->Offset) * $MaxRows;

$OrderedItem = "usuario_ruleta.valor";

/* asigna valores a variables a partir de solicitudes y parámetros. */
$OrderType = "desc";

$Id = $_REQUEST["Id"];
$Id = $params->Id;


$draw = $params->draw;

/* asigna valores de parámetros a variables, verificando si 'start' no está vacío. */
$length = $params->length;
$start = $params->start;

if ($start != "") {
    $SkeepRows = $start;

}


/* Configura el número máximo de filas según el valor de $length. */
if ($length != "") {
    $MaxRows = $length;

}

$columns = $params->columns;

/* Se asigna el valor de "order" a la variable "$order" desde "$params". */
$order = $params->order;

foreach ($order as $item) {

    switch ($columns[$item->column]->data) {

        case "PlayerExternalId":
            /* Se asigna un ID de usuario y un tipo de orden basado en 'PlayerExternalId'. */

            $OrderedItem = "usuario_ruleta.usuario_id";
            $OrderType = $item->dir;
            break;

        case "PlayerName":
            /* asigna ordenamiento basado en el nombre del jugador y dirección. */

            $OrderedItem = "usuario_mandante.nombres";
            $OrderType = $item->dir;
            break;

        case "Amount":
            /* Asigna valores según la dirección del ítem al pedido en el caso "Amount". */

            $OrderedItem = "usuario_ruleta.valor";
            $OrderType = $item->dir;

            break;

        case "AmountBase":
            /* asigna un valor a $OrderedItem según el caso "AmountBase". */

            $OrderedItem = "usuario_ruleta.valor_base";
            $OrderType = $item->dir;
            break;

        case "AmountWin":
            /* Asigna el valor de premio a $OrderedItem y determina el tipo de orden. */

            $OrderedItem = "usuario_ruleta.valor_premio";
            $OrderType = $item->dir;
            break;

        case "GGR":
            /* asigna una operación aritmética a $OrderedItem según la dirección de $item. */

            $OrderedItem = "usuario_ruleta.valor_base - usuario_ruleta.valor_premio";
            $OrderType = $item->dir;
            break;
    }

}


/* asigna valores a variables si no están vacías. */
if ($start != "") {
    $SkeepRows = $start;

}

if ($length != "") {
    $MaxRows = $length;

}


/* Agrega una regla si el ID externo del jugador no está vacío. */
$rules = [];

if ($PlayerExternalId != "") {
    array_push($rules, array("field" => "usuario_mandante.usuario_mandante", "data" => $PlayerExternalId, "op" => "eq"));

}


/* Agrega una regla de comparación al array y verifica condición de país para el usuario. */
array_push($rules, array("field" => "ruleta_interno.ruleta_id", "data" => "$Id", "op" => "eq"));


// Si el usuario esta condicionado por País
if ($_SESSION['PaisCond'] == "S") {
}
// Si el usuario esta condicionado por el mandante y no es de Global

/* verifica una sesión y agrega reglas a un filtro si se cumple la condición. */
if ($_SESSION['Global'] == "N") {
    array_push($rules, array("field" => "ruleta_interno.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
}


$filtro = array("rules" => $rules, "groupOp" => "AND");


/* inicializa variables si están vacías, asignando valores predeterminados. */
if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}


/* Establece un valor predeterminado y convierte un filtro a formato JSON. */
if ($MaxRows == "") {
    $MaxRows = 1000000000;
}

$json = json_encode($filtro);

$UsuarioRuleta = new UsuarioRuleta();
/* obtiene datos de usuarios y los decodifica en formato JSON. */
$data = $UsuarioRuleta->getUsuarioRuletasCustom("usuario_ruleta.*,usuario_mandante.nombres,usuario_mandante.usuario_mandante", $OrderedItem, $OrderType, $SkeepRows, $MaxRows, $json, true, false);

$data = json_decode($data);

$final = [];

$pos = 1;
foreach ($data->data as $key => $value) {


    /* Crea un arreglo asociativo con datos de jugadores y sus posiciones en un juego. */
    $array = [];
    $array["Position"] = $value->{"position.position"};
    $array["Id"] = $value->{"usuario_ruleta.usuruleta_id"};
    $array["PlayerExternalId"] = $value->{"usuario_mandante.usuario_mandante"};
    $array["PlayerName"] = $value->{"usuario_mandante.nombres"};
    $array["Amount"] = $value->{"usuario_ruleta.valor"};

    /* asigna y calcula valores relacionados con las apuestas en un juego. */
    $array["AmountBase"] = $value->{"usuario_ruleta.valor_base"};
    $array["AmountWin"] = $value->{"usuario_ruleta.valor_premio"};
    $array["GGR"] = ($value->{"usuario_ruleta.valor_base"} - $value->{"usuario_ruleta.valor_premio"});

    $array["Amount"] = round($array["Amount"], 2);
    $array["AmountBase"] = round($array["AmountBase"], 2);

    /* redondea montos y asigna valores a un arreglo. */
    $array["AmountWin"] = round($array["AmountWin"], 2);
    $array["GGR"] = round($array["GGR"], 2);


    $array["Code"] = $value->{"usuario_ruleta.codigo"};
    $array["AmountToWager"] = $value->{"usuario_ruleta.rollower_requerido"};

    /* asigna valores a un arreglo basado en datos de usuario de ruleta. */
    $array["WageredAmount"] = $value->{"usuario_ruleta.apostado"};
    $array["Date"] = $value->{"usuario_ruleta.fecha_crea"};
    $array["ExternalId"] = $value->{"usuario_ruleta.externo_id"};

    if ($value->{"usuario_ruleta.estado"} == "A") {

        $Estado = "Activo";
    }

    /* verifica el estado de "usuario_ruleta" y asigna un texto correspondiente. */
    if ($value->{"usuario_ruleta.estado"} == "P") {

        $Estado = "Pendiente";
    }
    if ($value->{"usuario_ruleta.estado"} == "R") {

        $Estado = "Redimido";
    }
    if($value->{"usuario_ruleta.estado"} == "E"){

        $Estado = "Expirado";
    }
    //Se agrega nuevo estado PR para la visual de Torneos y Bono
    if($value->{"usuario_ruleta.estado"} == "PR"){

        $Estado = "Pendiente rollover";
    }
    //Se agrega nuevo estado PP para la visual de Torneos y Bono
    if($value->{"usuario_ruleta.estado"} == "PP"){

        $Estado = "Pendiente procesar (PP)";
    }


    /* Asigna estado a un arreglo y verifica si el premio está vacío o nulo. */
    $array["State"] = $Estado;

    $premio = $value->{"usuario_ruleta.premio"};

    if ($premio == "" || $premio == null) {

        $array["Description"] = "";
    } else {
        /* decodifica un JSON y asigna su texto a un array. */


        $premio = json_decode($premio);
        $array["Description"] = $premio->text;
    }


    /* Agrega el contenido de `$array` al final del arreglo `$final`. */
    array_push($final, $array);
    $pos++;
}


/* Código PHP que estructura una respuesta sin errores para una solicitud. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Result"] = $final;

/* asigna datos y un conteo a un arreglo de respuesta. */
$response["Data"] = $final;
$response["Count"] = intval($data->count[0]->{".count"});
