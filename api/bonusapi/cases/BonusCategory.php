<?php

use Backend\dto\Clasificador;
use Backend\mysql\ClasificadorMySqlDAO;

/**
 * bonusapi/cases/BonusCategory
 *
 * Este recurso se encarga de obtener y organizar los clasificadores de acuerdo con dos filtros definidos para
 * categorías y detalles de campaña. Los clasificadores se recuperan utilizando un servicio que filtra por tipo
 * y organiza los resultados según ciertos parámetros. Los datos obtenidos se estructuran y retornan en un formato
 * adecuado para ser utilizado en otras operaciones o presentados en una vista.
 *
 * @param object $params : Objeto que contiene los parámetros necesarios para la operación, que incluyen filtros
 *                         y otros valores de configuración como cantidad de filas y tipo de orden.
 *
 * @returns object El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error en la operación.
 *  - *AlertType* (string): Especifica el tipo de alerta que se mostrará en la vista (success, danger, etc.).
 *  - *AlertMessage* (string): Contiene el mensaje que se mostrará en la vista, indicando el estado de la operación.
 *  - *ModelErrors* (array): Contiene los errores específicos si los hubo.
 *  - *Data* (array): Contiene los datos organizados, en este caso:
 *      - *Categories* (array): Lista de clasificadores con tipo "TBC".
 *      - *DetailsCampaign* (array): Lista de clasificadores con tipo "TBD".
 *
 * Objeto en caso de error:
 *
 * "HasError" => true,
 * "AlertType" => "danger",
 * "AlertMessage" => "Mensaje de error",
 * "ModelErrors" => array(),
 * "Data" => array(),
 *
 * @throws Exception Error general en la ejecución de la operación, como problemas con la base de datos o con los datos.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */



/* Configura parámetros para paginación y reglas de filtrado en una consulta. */
$MaxRows = 10;
$SkeepRows = 0;
$OrderItem = "Desc";

$rules = [];
array_push($rules, array("field" => "clasificador.tipo", "data" => "TBC", "op" => "eq"));


/* Se filtran clasificadores personalizados usando reglas y se decodifican resultados JSON. */
$filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

$Clasificador = new Clasificador();

$datos = $Clasificador->getClasificadoresCustom("clasificador.*", "clasificador.clasificador_id", $OrderItem, $SkeepRows, $MaxRows, $filter, true);

$datos = json_decode($datos);



/* organiza datos en un arreglo asociativo con identificador y descripción. */
$final3 = [];

$final = [];
foreach ($datos->data as $key => $value) {
    $array = [];
//    $final[] = $value->{"clasificador.clasificador_id"}." ".$value->{"clasificador.descripcion"};
    $array["Id"] = $value->{"clasificador.clasificador_id"};
    $array["Description"] = $value->{"clasificador.descripcion"};

    array_push($final, $array);

}


/* Se definen reglas de filtrado en formato JSON para un clasificador. */
$rules = [];

array_push($rules, array("field" => "clasificador.tipo", "data" => "TBD", "op" => "eq"));

$filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

$Clasificador = new Clasificador();

/* Se obtienen datos de clasificadores y se decodifican en formato JSON. */
$datos = $Clasificador->getClasificadoresCustom("clasificador.descripcion,clasificador.clasificador_id", "clasificador.clasificador_id", $OrderItem, $SkeepRows, $MaxRows, $filter, true);

$datos = json_decode($datos);


$final2 = [];

/* Recorre datos, extrae información y la almacena en un nuevo array. */
foreach ($datos->data as $key => $value) {

    $array = [];

    $array["Id"] = $value->{"clasificador.clasificador_id"};
    $array["Description"] = $value->{"clasificador.descripcion"};

    array_push($final2, $array);
}



/* crea una respuesta JSON sin errores, incluyendo datos y detalles de campaña. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];
$response["Data"] = array(
    "Categories" => $final,
    "DetailsCampaign" => $final2
);

