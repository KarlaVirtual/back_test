<?php


use Backend\dto\SorteoDetalle;
use Backend\dto\SorteoInterno;
use Backend\dto\UsuarioMandante;
use Backend\dto\ProductoMandante;
use Backend\dto\Pais;
use Backend\dto\UsuarioSorteo;
use Backend\mysql\SorteoInternoMySqlDAO;

/**
 * command/road_with_prizes
 *
 * Devuelve los sorteos disponibles del usuario
 *
 * @param string $ToDateLocal : Fecha de inicio
 * @param string $FromDateLocal : Fecha final
 * @param int $TypeId : Tipo de id
 * @param int $site_id : id del Partner
 * @param string $isMobile : Si viene por Mobile
 * @param mixed $SkeepRows : Número de filas a omitir en la consulta (paginación).
 * @param mixed $OrderedItem : Criterio de ordenamiento de los registros.
 * @param mixed $MaxRows : Número máximo de filas a retornar.
 * @param string $StateType :Tipo de estado
 * @param int $idLottery : Id de la loteria
 * @param string $State : Estado
 * @param int $Country : Pais vinculado
 *
 *
 * El objeto $response es un array con los siguientes atributos:
 *  - *code* (int): Codigo de error desde el proveedor
 *  - *rid* (string): Contiene el mensaje de error.
 *  - *count* (int): Devuelve la cantidad de registros de la busqueda.
 *  - *data* (array): Devuelve la respuesta de la busqueda con los sorteos
 *
 *
 * @throws No
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */

/**
 * Obtiene los encabezados de la solicitud HTTP.
 *
 * @return array Un array asociativo de los encabezados HTTP.
 */
function getRequestHeaders()
{
    $headers = array();
    foreach ($_SERVER as $key => $value) {
        if (substr($key, 0, 5) <> 'HTTP_') {
            continue;
        }
        $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
        $headers[$header] = $value;
    }
    return $headers;
}


/**
 * Ejecuta una consulta SQL y devuelve el resultado.
 *
 * @param string $sql La consulta SQL a ejecutar.
 * @return mixed El resultado de la consulta SQL.
 */
function execQuery($sql)
{
    $SorteoInternoMySqlDAO = new SorteoInternoMySqlDAO();
    $return = $SorteoInternoMySqlDAO->querySQL($sql);
    $return = json_decode(json_encode($return), FALSE);

    return $return;
}

/* ejecuta un script PHP en segundo plano para importar mensajes de Slack. */
$headers = getRequestHeaders();
try {
    exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . ' road_with_prizes ' . "' '#virtualsoft-cron' > /dev/null & ");
} catch (Exception $e) {

}


/* extrae y normaliza parámetros de un objeto JSON para su uso posterior. */
$params = $json->params;
$ToDateLocal = $params->StartTimeLocal;
$FromDateLocal = $params->EndTimeLocal;
$TypeId = $params->TypeId;
$site_id = $json->params->site_id;
$site_id = strtolower($site_id);


/* Código para procesar parámetros de paginación y detección de dispositivo móvil. */
$isMobile = strtolower($json->params->isMobile);


$MaxRows = $params->Limit;
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($params->Offset) * $MaxRows;


/* asigna valores de parámetros a variables para usarlas posteriormente. */
$StateType = $params->StateType;
$idLottery = $params->idLottery;

$State = $params->State;

$Country = $params->Country;


/* Se inicializan variables y objetos para manejo de sorteos internos y detalles. */
$rules = [];

$SorteoInterno = new SorteoInterno();
$SorteoDetalle = new SorteoDetalle();


$rules = [];


/* asigna un país según el código de país de la cabecera. */
$countryCode = strtolower($headers["Cf-Ipcountry"]);

if ($countryCode != "" && $site_id == 8) {
    if ($countryCode == "ni") {
        $Country = 'ni';
    } elseif ($countryCode == "cr") {
        $Country = 'cr';
    } else {
        $Country = 'pe';
    }
}

/* Condiciones para agregar reglas basadas en el país y el sorteo. */
if ($site_id == 8) {
    $Country = '';
}


/*if ($Country != "") {
    try {
        $Pais = new Pais("", $Country);

    } catch (Exception $e) {

    }
    array_push($rules, array("field" => "sorteo_detalle.tipo", "data" => "CONDPAISUSER", "op" => "eq"));
    array_push($rules, array("field" => "sorteo_detalle.valor", "data" => $Pais->paisId, "op" => "eq"));
} else {
    array_push($rules, array("field" => "sorteo_detalle.tipo", "data" => "TIPOPRODUCTO", "op" => "eq"));

}*/
if ($idLottery != "") {
    array_push($rules, array("field" => "sorteo_interno.sorteo_id", "data" => $idLottery, "op" => "eq"));

} else {
    /* agrega una regla a un array si se cumple una condición específica. */

    array_push($rules, array("field" => "sorteo_interno.sorteo_id", "data" => 0, "op" => "eq"));

}

/* Se agregan reglas de filtrado a un arreglo para consultas específicas. */
array_push($rules, array("field" => "sorteo_detalle.tipo", "data" => "'RANKAWARDMAT','BONO','RANKAWARD'", "op" => "in"));
if ($State != "") {
    array_push($rules, array("field" => "sorteo_interno.estado", "data" => "$State", "op" => "eq"));

}
array_push($rules, array("field" => "sorteo_interno.mandante", "data" => $site_id, "op" => "eq"));


/* Configura un filtro y establece valores predeterminados para dos variables vacías. */
$filtro = array("rules" => $rules, "groupOp" => "AND");

if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}


/* Si $MaxRows está vacío, se establece en 1000 y obtiene detalles de sorteos. */
if ($MaxRows == "") {
    $MaxRows = 1000;
}

$jsonfiltro = json_encode($filtro);

$sorteos = $SorteoDetalle->getSorteoDetallesCustom("sorteo_detalle.*,sorteo_interno.*", "sorteo_interno.sorteo_id", "asc", $SkeepRows, $MaxRows, $jsonfiltro, TRUE);


/* convierte datos JSON en un array PHP y crea un array vacío. */
$sorteos = json_decode($sorteos);


$final = [];
foreach ($sorteos->data as $key2 => $value2) {


    switch ($value2->{"sorteo_detalle.tipo"}) {


        case "USERSUBSCRIBE":
            /* verifica si el valor de suscripción es cero y determina la necesidad de suscripción. */


            if ($value2->{"sorteo_detalle.valor"} == 0) {

            } else {
                $needSubscribe = true;
            }

            break;

        case "RANKAWARDMAT":


            /* Se crea un array con detalles de sorteos a partir de un objeto. */
            $array2 = [];
            $array2['position'] = $value2->{"sorteo_detalle.valor"};
            $array2['detailId'] = $value2->{"sorteo_detalle.sorteodetalle_id"};
            $array2['description'] = $value2->{"sorteo_detalle.descripcion"};
            $array2['value'] = str_replace(' ', '', str_replace('	', '', $value2->{"sorteo_detalle.valor3"}));
            $array2['type'] = "Fisico";

            /* Se crean elementos en un array a partir de propiedades de un objeto. */
            $array2['image'] = $value2->{"sorteo_detalle.imagen_url"};
            $array2['date'] = strtotime($value2->{"sorteo_detalle.fecha_sorteo"}) * 1000;
            $array2['state'] = $value2->{"sorteo_detalle.estado"};

            if ($value2->{"sorteo_detalle.estado"} == "R") {

                /* Se crea un conjunto de reglas para validar un sorteo específico. */
                $rules = [];
                $SorteoDetalle = new SorteoDetalle($array2['detailId']);

                array_push($rules, array("field" => "usuario_sorteo.estado", "data" => "R", "op" => "eq"));


                array_push($rules, array("field" => "usuario_sorteo.sorteo_id", "data" => $SorteoDetalle->sorteoId, "op" => "eq"));

                /* Se define una regla de filtro y la estructura para aplicar condiciones lógicas. */
                array_push($rules, array("field" => "usuario_sorteo.premio_id", "data" => $value2->{"sorteo_detalle.sorteodetalle_id"}, "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                $SkeepRows = 0;
                $OrderedItem = 1;

                /* Se obtiene información de sorteos de usuario en formato JSON limitado a una fila. */
                $MaxRows = 1;

                $json = json_encode($filtro);

                $UsuarioSorteo = new UsuarioSorteo();
                $data = $UsuarioSorteo->getUsuarioSorteosCustom("usuario_sorteo.*", $OrderedItem, 'asc', $SkeepRows, $MaxRows, $json, true, false);

                /* decodifica JSON y obtiene información de usuario para crear un objeto. */
                $data = json_decode($data);

                $UserId = $data->data[0]->{"usuario_sorteo.usuario_id"};

                $UsuarioMandante = new UsuarioMandante($UserId);
                $array2['UserWin'] = $UsuarioMandante->getUsuarioMandante() . "**" . $UsuarioMandante->getNombres();
            }


            /* Agrega el contenido de $array2 al final del array $final. */
            array_push($final, ($array2));

            break;
        case "BONO":


            /* Crea un array asociativo con detalles de un sorteo y formatea el valor. */
            $array2 = [];
            $array2['position'] = $value2->{"sorteo_detalle.valor"};
            $array2['detailId'] = $value2->{"sorteo_detalle.sorteodetalle_id"};
            $array2['description'] = $value2->{"sorteo_detalle.descripcion"};
            $array2['value'] = str_replace(' ', '', str_replace('	', '', $value2->{"sorteo_detalle.valor3"}));
            $array2['type'] = "Bono";

            /* Asigna valores de un objeto a un array, formateando la fecha en milisegundos. */
            $array2['image'] = $value2->{"sorteo_detalle.imagen_url"};
            $array2['date'] = strtotime($value2->{"sorteo_detalle.fecha_sorteo"}) * 1000;
            $array2['state'] = $value2->{"sorteo_detalle.estado"};

            if ($value2->{"sorteo_detalle.estado"} == "R") {

                /* Definición de reglas para validar estados y premios en sorteos. */
                $rules = [];
                $SorteoDetalle = new SorteoDetalle($array2['detailId']);

                array_push($rules, array("field" => "usuario_sorteo.estado", "data" => "R", "op" => "eq"));

                array_push($rules, array("field" => "usuario_sorteo.premio_id", "data" => $value2->{"sorteo_detalle.sorteodetalle_id"}, "op" => "eq"));

                /* Se define una regla de filtrado para un sorteio específico en un array. */
                array_push($rules, array("field" => "usuario_sorteo.sorteo_id", "data" => $SorteoDetalle->sorteoId, "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                $SkeepRows = 0;
                $OrderedItem = 1;

                /* Código que obtiene datos de usuarios sorteados usando parámetros específicos en una base de datos. */
                $MaxRows = 1;

                $json = json_encode($filtro);

                $UsuarioSorteo = new UsuarioSorteo();
                $data = $UsuarioSorteo->getUsuarioSorteosCustom("usuario_sorteo.*", $OrderedItem, 'asc', $SkeepRows, $MaxRows, $json, true, false);

                /* decodifica un JSON y obtiene información del usuario para almacenarla. */
                $data = json_decode($data);

                $UserId = $data->data[0]->{"usuario_sorteo.usuario_id"};

                $UsuarioMandante = new UsuarioMandante($UserId);
                $array2['UserWin'] = $UsuarioMandante->getUsuarioMandante() . "**" . $UsuarioMandante->getNombres();
            }

            /* Agrega el contenido de $array2 al final del array $final. */
            array_push($final, ($array2));
            break;


        case "RANKAWARD":


            /* Se crea un array con datos procesados de un objeto. */
            $array2 = [];
            $array2['position'] = $value2->{"sorteo_detalle.valor"};
            $array2['detailId'] = $value2->{"sorteo_detalle.sorteodetalle_id"};
            $array2['description'] = $value2->{"sorteo_detalle.descripcion"};
            $array2['value'] = str_replace(' ', '', str_replace('	', '', $value2->{"sorteo_detalle.valor3"}));
            $array2['type'] = "Efectivo";

            /* Extrae información de un objeto y la almacena en un array asociativo. */
            $array2['image'] = $value2->{"sorteo_detalle.imagen_url"};
            $array2['date'] = strtotime($value2->{"sorteo_detalle.fecha_sorteo"}) * 1000;
            $array2['state'] = $value2->{"sorteo_detalle.estado"};

            if ($value2->{"sorteo_detalle.estado"} == "R") {

                /* crea reglas para filtrar datos de usuario en un sorteo específico. */
                $rules = [];
                $SorteoDetalle = new SorteoDetalle($array2['detailId']);

                array_push($rules, array("field" => "usuario_sorteo.estado", "data" => "R", "op" => "eq"));

                array_push($rules, array("field" => "usuario_sorteo.premio_id", "data" => $value2->{"sorteo_detalle.sorteodetalle_id"}, "op" => "eq"));

                /* Agrega reglas de filtro para la consulta de un sorteo específico en un array. */
                array_push($rules, array("field" => "usuario_sorteo.sorteo_id", "data" => $SorteoDetalle->sorteoId, "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                $SkeepRows = 0;
                $OrderedItem = 1;

                /* obtiene datos de sorteos de un usuario aplicando un filtro JSON. */
                $MaxRows = 1;

                $json = json_encode($filtro);

                $UsuarioSorteo = new UsuarioSorteo();
                $data = $UsuarioSorteo->getUsuarioSorteosCustom("usuario_sorteo.*", $OrderedItem, 'asc', $SkeepRows, $MaxRows, $json, true, false);

                /* Se decodifica un JSON y se crea un objeto UsuarioMandante con un ID específico. */
                $data = json_decode($data);

                $UserId = $data->data[0]->{"usuario_sorteo.usuario_id"};

                $UsuarioMandante = new UsuarioMandante($UserId);
                $array2['UserWin'] = $UsuarioMandante->getUsuarioMandante() . "**" . $UsuarioMandante->getNombres();
            }

            /* Agrega los elementos de $array2 al final del array $final en PHP. */
            array_push($final, ($array2));
            break;

    }
}

/* crea un arreglo de respuesta con información sobre un proceso. */
$response = array();
$response["code"] = 0;
$response["rid"] = $json->rid;
$response["count"] = oldCount($final);
$response["data"] = $final;
