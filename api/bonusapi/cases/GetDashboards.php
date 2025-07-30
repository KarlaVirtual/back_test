<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\TransjuegoInfo;
use Backend\dto\UsuarioBono;
use Backend\dto\UsuarioTorneo;

/**
 * Este script genera datos estadísticos para tableros de control.
 *
 * @param object $params Objeto JSON decodificado que contiene los siguientes parámetros:
 * @param string $params->ResultToDate Fecha de finalización en formato local.
 * @param string $params->ResultFromDate Fecha de inicio en formato local.
 * @param array $params->BonusDefinitionIds IDs de definiciones de bonos.
 * @param string $params->PlayerExternalId ID externo del jugador.
 * @param int $params->Limit Límite de filas a devolver.
 * @param int $params->OrderedItem Elemento de ordenación.
 * @param int $params->Offset Número de páginas a omitir.
 * @param int $params->Id ID del reporte.
 * @param int $params->TypeAmount Tipo de cálculo de montos.
 * @param string $params->State Estado del bono.
 * @param int $params->TypeReport Tipo de reporte.
 * @param string $params->Currency Moneda utilizada.
 * @param string $params->DateFrom Fecha de inicio.
 * @param string $params->DateTo Fecha de finalización.
 *
 * @return array $response Respuesta estructurada con los siguientes datos:
 *  - HasError (bool): Indica si ocurrió un error.
 *  - AlertType (string): Tipo de alerta (e.g., "success").
 *  - AlertMessage (string): Mensaje de alerta.
 *  - ModelErrors (array): Lista de errores del modelo.
 *  - Result (array): Datos procesados del tablero.
 *  - Data (array): Contiene etiquetas, montos y totales.
 */

/* recibe un JSON, lo decodifica y extrae fechas e identificadores. */
$params = file_get_contents('php://input');
$params = json_decode($params);

$ToDateLocal = $params->ResultToDate;
$FromDateLocal = $params->ResultFromDate;
$BonusDefinitionIds = $params->BonusDefinitionIds;

/* asigna parámetros de entrada a variables en un contexto de programación. */
$PlayerExternalId = $params->PlayerExternalId;

$MaxRows = $params->Limit;
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($params->Offset) * $MaxRows;

$Id = $params->Id;

/* asigna valores de parámetros a variables y convierte una fecha. */
$TypeAmount = $params->TypeAmount;
$State = $params->State;
$TypeReport = $params->TypeReport;
$Currency = $params->Currency;
$DateFrom = $params->DateFrom;
$FromDateLocal = date("Y-m-d H:i:s", strtotime($DateFrom));


/* Convierte una fecha pasada en formato de texto a formato de fecha local. */
$DateTo = $params->DateTo;
$ToDateLocal = date("Y-m-d H:i:s", strtotime($DateTo));


if ($Id == 0) {

    /* crea un arreglo de reglas para validar datos de usuario. */
    $rules = [];
    array_push($rules, array("field" => "usuario.moneda", "data" => "$Currency", "op" => "eq"));

    switch ($State) {
        case "A":
            /* Código que formatea fechas y define reglas para el estado del usuario. */

            $fechaSql = "DATE_FORMAT(usuario_bono.fecha_crea,";
            array_push($rules, array("field" => "usuario_bono.estado", "data" => "A", "op" => "eq"));
            break;

        case "R":
            /* asigna una fecha formateada y agrega una regla de estado "R". */

            $fechaSql = "DATE_FORMAT(usuario_bono.fecha_modif,";
            array_push($rules, array("field" => "usuario_bono.estado", "data" => "R", "op" => "eq"));

            break;

        case "E":
            /* Se formatea la fecha y se agrega una regla para el estado "E". */

            $fechaSql = "DATE_FORMAT(usuario_bono.fecha_modif,";
            array_push($rules, array("field" => "usuario_bono.estado", "data" => "E", "op" => "eq"));

            break;

        default:

            /* "Convierte 'fecha_crea' del usuario a un formato de fecha específico en SQL." */
            $fechaSql = "DATE_FORMAT(usuario_bono.fecha_crea,";
            break;
    }


    /* ajusta el formato de fecha según el tipo de informe seleccionado. */
    switch ($TypeReport) {
        case "0":
            $fechaSql = $fechaSql . "'%Y-%m')";
            break;
        case "2":
            $fechaSql = $fechaSql . "'%Y-%m-%d %H')";
            break;

        default:
            $fechaSql = $fechaSql . "'%Y-%m-%d')";
            break;

    }


    /* Selecciona diferentes cálculos según el valor de $TypeAmount. */
    switch ($TypeAmount) {

        case "1":
            $select = "SUM(usuario_bono.valor_base) valor, " . $fechaSql . " fecha";
            break;

        case "2":
            $select = "COUNT(usuario_bono.usuario_id) valor, " . $fechaSql . " fecha";
            break;
        default:
            $select = "SUM(usuario_bono.valor) valor, " . $fechaSql . " fecha";

            break;
    }

    /* Se agregan condiciones de filtrado a un arreglo, utilizando operadores lógicos. */
    array_push($rules, array("field" => "$fechaSql", "data" => "$FromDateLocal", "op" => "ge"));
    array_push($rules, array("field" => "$fechaSql", "data" => "$ToDateLocal", "op" => "le"));

    //array_push($rules, array("field" => "bono_interno.bono_id", "data" => "$IdBonus", "op" => "eq"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");


    /* asigna valores predeterminados si las variables están vacías. */
    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }

    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }


    /* asigna un valor predeterminado a $MaxRows y convierte $filtro a JSON. */
    if ($MaxRows == "") {
        $MaxRows = 1000000000;
    }

    $json = json_encode($filtro);


    $UsuarioBono = new UsuarioBono();

    /* obtiene datos de bonos de usuario y los decodifica en formato JSON. */
    $data = $UsuarioBono->getUsuarioBonosCustom($select, "usuario_bono.usubono_id", "asc", $SkeepRows, $MaxRows, $json, true, $fechaSql);


    $data = json_decode($data);

    $finalLabel = [];

    /* organiza fechas y valores en dos arreglos desde un objeto de datos. */
    $finalAmount = [];

    foreach ($data->data as $key => $value) {
        $array = array(
            "start" => strtotime($value->{'.fecha'})
        );
        array_push($finalLabel, $array);
        array_push($finalAmount, $value->{'.valor'});
    }


    /* Se crean condiciones para filtrar datos de "usuario_bono" entre dos fechas. */
    $final = [];

    $rules = [];

    array_push($rules, array("field" => "usuario_bono.fecha_crea", "data" => "$FromDateLocal", "op" => "ge"));
    array_push($rules, array("field" => "usuario_bono.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));


    /* establece filtros y condiciones para procesar datos en una consulta. */
    $filtro = array("rules" => $rules, "groupOp" => "AND");

    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }

    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }


    /* Calcula sumas y cantidades de bonos según su estado en una consulta SQL. */
    if ($MaxRows == "") {
        $MaxRows = 1000000000;
    }

    $json = json_encode($filtro);

    $select = "SUM(CASE WHEN usuario_bono.estado =  'R' THEN usuario_bono.valor ELSE 0 END) valor_redimidos,
        SUM(CASE WHEN usuario_bono.estado =  'A' THEN usuario_bono.valor ELSE 0 END) valor_activos,
        SUM(CASE WHEN usuario_bono.estado =  'E' THEN usuario_bono.valor ELSE 0 END) valor_expirados,
        SUM(CASE WHEN usuario_bono.estado =  'R' THEN 1 ELSE 0 END) cant_redimidos,
        SUM(CASE WHEN usuario_bono.estado =  'A' THEN 1 ELSE 0 END) cant_activos,
        SUM(CASE WHEN usuario_bono.estado =  'E' THEN 1 ELSE 0 END) cant_expirados
        ";


    /* Se obtiene y decodifica información de bonos de usuario en formato JSON. */
    $UsuarioBono = new UsuarioBono();
    $data = $UsuarioBono->getUsuarioBonosCustom($select, "usuario_bono.usubono_id", "asc", $SkeepRows, $MaxRows, $json, true, '');


    $data = json_decode($data);

    $value = $data->data[0];


    /* Establece un arreglo para bonificaciones activas y reglas de filtrado basadas en fecha. */
    $final["ActiveBonus"] = [];
    $final["ActiveBonus"]["Total"] = $value->{".cant_activos"};
    $final["ActiveBonus"]["Amount"] = round($value->{".valor_activos"}, 2);

    $rules = [];

    array_push($rules, array("field" => "usuario_bono.fecha_modif", "data" => "$FromDateLocal", "op" => "ge"));

    /* Agrega reglas de filtrado y establece el desplazamiento de filas a procesar. */
    array_push($rules, array("field" => "usuario_bono.fecha_modif", "data" => "$ToDateLocal", "op" => "le"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");

    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }


    /* Asignación de valores predeterminados a variables si están vacías. */
    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }

    if ($MaxRows == "") {
        $MaxRows = 1000000000;
    }


    /* Código que agrega valores de bonos según su estado: redimidos, activos y expirados. */
    $json = json_encode($filtro);

    $select = "SUM(CASE WHEN usuario_bono.estado =  'R' THEN usuario_bono.valor ELSE 0 END) valor_redimidos,
        SUM(CASE WHEN usuario_bono.estado =  'A' THEN usuario_bono.valor ELSE 0 END) valor_activos,
        SUM(CASE WHEN usuario_bono.estado =  'E' THEN usuario_bono.valor ELSE 0 END) valor_expirados,
        SUM(CASE WHEN usuario_bono.estado =  'R' THEN 1 ELSE 0 END) cant_redimidos,
        SUM(CASE WHEN usuario_bono.estado =  'A' THEN 1 ELSE 0 END) cant_activos,
        SUM(CASE WHEN usuario_bono.estado =  'E' THEN 1 ELSE 0 END) cant_expirados
        ";


    /* Se obtiene un usuario bono y se decodifica el resultado JSON. */
    $UsuarioBono = new UsuarioBono();
    $data = $UsuarioBono->getUsuarioBonosCustom($select, "usuario_bono.usubono_id", "asc", $SkeepRows, $MaxRows, $json, true, '');


    $data = json_decode($data);

    $value = $data->data[0];


    /* asigna valores redondeados y totales a bonificaciones redimidas y expiradas. */
    $final["RedimBonus"] = [];
    $final["RedimBonus"]["Total"] = $value->{".cant_redimidos"};
    $final["RedimBonus"]["Amount"] = round($value->{".valor_redimidos"}, 2);
    $final["ExpiratedBonus"] = [];
    $final["ExpiratedBonus"]["Total"] = $value->{".cant_expirados"};
    $final["ExpiratedBonus"]["Amount"] = round($value->{".valor_expirados"}, 2);


    /* Suma totales y montos de bonificaciones activas, redimidas y expiradas en un array. */
    $final["AllBonus"] = [];
    $final["AllBonus"]["Total"] = $final["ActiveBonus"]["Total"] + $final["RedimBonus"]["Total"] + $final["ExpiratedBonus"]["Total"];
    $final["AllBonus"]["Amount"] = round($final["ActiveBonus"]["Amount"] + $final["RedimBonus"]["Amount"] + $final["ExpiratedBonus"]["Amount"], 2);


}
if ($Id == 1) {

    /* Define reglas de validación y selecciona formato de fecha según tipo de informe. */
    $rules = [];
    array_push($rules, array("field" => "usuario_mandante.moneda", "data" => "$Currency", "op" => "eq"));

    $fechaSql2 = '';

    switch ($TypeReport) {
        case "0":
            $fechaSql2 = "'%Y-%m')";
            break;
        case "2":
            $fechaSql2 = "'%Y-%m-%d %H')";
            break;

        default:
            $fechaSql2 = "'%Y-%m-%d')";
            break;

    }

    /* Variables que definen el número de filas a omitir y el máximo de filas a procesar. */
    $SkeepRows = 0;
    $MaxRows = 100;

    switch ($TypeAmount) {

        case "0":
            /* Consulta SQL que suma valores y formatea fechas de transacciones de juego. */


            $fechaSql = "DATE_FORMAT(transjuego_info.fecha_crea," . $fechaSql2;

            $select = "SUM(transjuego_info.valor) valor, " . $fechaSql . " fecha";
            $TransjuegoInfo = new TransjuegoInfo();
            $data = $TransjuegoInfo->getTransjuegoInfosCustom($select, "transjuego_info.transjuegoinfo_id", "asc", $SkeepRows, $MaxRows, $json, true, $fechaSql);

            break;


        case "1":
            /* Consulta y suma valores de transacciones, formateando fechas en SQL. */

            $fechaSql = "DATE_FORMAT(transjuego_info.fecha_crea," . $fechaSql2;

            $select = "SUM(transaccion_api.valor) valor, " . $fechaSql . " fecha";
            $TransjuegoInfo = new TransjuegoInfo();
            $data = $TransjuegoInfo->getTransjuegoInfosCustom($select, "transjuego_info.transjuegoinfo_id", "asc", $SkeepRows, $MaxRows, $json, true, $fechaSql);

            break;

        case "2":
            /* Código SQL para contar usuarios en torneos, formateando la fecha de creación. */

            $fechaSql = "DATE_FORMAT(usuario_torneo.fecha_crea," . $fechaSql2;
            $select = "COUNT(usuario_torneo.usuario_id) valor, " . $fechaSql . " fecha";
            $UsuarioTorneo = new UsuarioTorneo();
            $data = $UsuarioTorneo->getUsuarioTorneosCustom($select, "usuario_torneo.usutorneo_id", "asc", $SkeepRows, $MaxRows, $json, true, $fechaSql);

            break;

        case "3":
            /* cuenta registros y formatea fechas en una consulta SQL específica. */

            $fechaSql = "DATE_FORMAT(transjuego_info.fecha_crea," . $fechaSql2;
            $select = "COUNT(transjuego_info.transjuegoinfo_id) valor, " . $fechaSql . " fecha";
            $TransjuegoInfo = new TransjuegoInfo();
            $data = $TransjuegoInfo->getTransjuegoInfosCustom($select, "transjuego_info.transjuegoinfo_id", "asc", $SkeepRows, $MaxRows, $json, true, $fechaSql);

            break;
    }

    /* agrega reglas de filtrado a un arreglo para consultas. */
    array_push($rules, array("field" => "$fechaSql", "data" => "$FromDateLocal", "op" => "ge"));
    array_push($rules, array("field" => "$fechaSql", "data" => "$ToDateLocal", "op" => "le"));

    //array_push($rules, array("field" => "bono_interno.bono_id", "data" => "$IdBonus", "op" => "eq"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");


    /* inicializa variables si están vacías, estableciendo valores predeterminados. */
    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }

    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }


    /* establece un límite máximo de filas y convierte datos a formato JSON. */
    if ($MaxRows == "") {
        $MaxRows = 1000000000;
    }

    $json = json_encode($filtro);


    $data = json_decode($data);


    /* organiza datos en dos arrays: fechas y valores correspondientes. */
    $finalLabel = [];
    $finalAmount = [];

    foreach ($data->data as $key => $value) {
        $array = array(
            "start" => strtotime($value->{'.fecha'})
        );
        array_push($finalLabel, $array);
        array_push($finalAmount, $value->{'.valor'});
    }


    /* Se definen reglas de filtrado para fechas en un torneo de usuarios. */
    $rules = [];
    array_push($rules, array("field" => "usuario_torneo.fecha_crea", "data" => "$FromDateLocal", "op" => "ge"));
    array_push($rules, array("field" => "usuario_torneo.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));


    $filtro = array("rules" => $rules, "groupOp" => "AND");


    /* Asigna valores predeterminados a variables si están vacías. */
    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }

    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }


    /* establece un valor máximo y prepara una consulta SQL. */
    if ($MaxRows == "") {
        $MaxRows = 1000000000;
    }

    $json = json_encode($filtro);

    $select = "COUNT(*) usuarios,
        SUM(usuario_torneo.valor) creditos,
        SUM(usuario_torneo.valor_base) dinero
        ";


    /* Se obtiene y decodifica datos de torneos de un usuario utilizando JSON. */
    $UsuarioTorneo = new UsuarioTorneo();
    $data = $UsuarioTorneo->getUsuarioTorneosCustom($select, "usuario_torneo.usutorneo_id", "asc", $SkeepRows, $MaxRows, $json, true, '');


    $data = json_decode($data);

    $value = $data->data[0];


    /* organiza y almacena datos de créditos y dinero en un array. */
    $final = [];
    $final["Credits"] = [];
    $final["Credits"]["Total"] = $value->{".creditos"};
    $final["Credits"]["Amount"] = $value->{".creditos"};
    $final["RealMoney"] = [];
    $final["RealMoney"]["Total"] = $value->{".dinero"};

    /* Asigna valores de dinero y usuarios a un arreglo final en formato específico. */
    $final["RealMoney"]["Amount"] = $value->{".dinero"};
    $final["Players"] = [];
    $final["Players"]["Total"] = $value->{".usuarios"};
    $final["Players"]["Amount"] = $value->{".usuarios"};


}


/* asigna valores a un array de respuesta para una operación exitosa. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Result"] = $final;

/* crea un array con etiquetas y valores específicos para la respuesta. */
$response["Data"] = array(
    "Label" => $finalLabel,
    "Amount" => $finalAmount,
    "Total" => $final
);
