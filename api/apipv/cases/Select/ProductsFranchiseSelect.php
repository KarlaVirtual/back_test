<?php

use Backend\dto\MandanteProducto;
use Backend\dto\ProductoMandante;
use Backend\mysql\MandanteProductoMySqlDAO;

/**
 * ProductsFranchiseSelect
 *
 * Este script permite obtener una lista de productos y proveedores bancarios filtrados según las condiciones especificadas.
 *
 * @param object $params Objeto que contiene los parámetros de entrada:
 * @param string $params->Filter Palabra clave para filtrar productos por descripción.
 *
 * @return array Respuesta en formato JSON con las siguientes claves:
 * - HasError: (boolean) Indica si ocurrió un error.
 * - AlertType: (string) Tipo de alerta (por ejemplo, "success").
 * - AlertMessage: (string) Mensaje de alerta.
 * - ModelErrors: (array) Lista de errores del modelo.
 * - Data: (array) Datos finales con productos y proveedores.
 * - pos: (int) Posición inicial de los resultados.
 * - total_count: (int) Total de registros encontrados.
 * - data: (array) Datos finales con productos y proveedores.
 *
 * @throws Exception Si ocurre un error durante la ejecución.
 */


/*error_reporting(E_ALL);
ini_set('display_errors', 'ON');*/

/* Inicializa un objeto y establece parámetros para filtrar y paginar resultados. */
$ProductoMandante = new ProductoMandante();
$keyword = $params->Filter;

$OrderedItem = 1;
$SkeepRows = 0;
$MaxRows = 100;


/* Se inicializa un array vacío llamado $rules en PHP. */
$rules = [];

if ($keyword != "" & $keyword != null) {

    /* Se agregan reglas de filtrado para distintos campos y condiciones en un arreglo. */
    array_push($rules, array("field" => "producto.descripcion", "data" => $keyword, "op" => "cn"));
    array_push($rules, array("field" => "proveedor.estado", "data" => "A", "op" => "eq"));
    array_push($rules, array("field" => "producto.estado", "data" => "A", "op" => "eq"));
    array_push($rules, array("field" => "producto_mandante.estado", "data" => "A", "op" => "eq"));
    array_push($rules, array("field" => "subproveedor.estado", "data" => "A", "op" => "eq"));
    array_push($rules, array("field" => "subproveedor_mandante.estado", "data" => "A", "op" => "eq"));
    array_push($rules, array("field" => "proveedor_mandante.estado", "data" => "A", "op" => "eq"));
    /* Agrega reglas para validar condiciones según el estado y el mandante del usuario. */


// Si el usuario esta condicionado por el mandante y no es de Global
    if ($_SESSION['Global'] == "N") {
        array_push($rules, array("field" => "producto_mandante.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
    } else {
        /* Agrega una regla si "mandanteLista" de sesión no está vacía ni es "-1". */


        if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
            array_push($rules, array("field" => "producto_mandante.mandante", "data" => $_SESSION["mandanteLista"], "op" => "in"));
        }

    }

    /* Condiciones para agregar reglas basadas en la sesión del país del usuario. */
    if ($_SESSION['PaisCond'] == "S") {
        array_push($rules, array("field" => "producto_mandante.pais_id", "data" => $_SESSION['pais_id'], "op" => "eq"));
    } else {
        if ($_SESSION["PaisCondS"] != '') {
            array_push($rules, array("field" => "producto_mandante.pais_id", "data" => $_SESSION['PaisCondS'], "op" => "eq"));
        }
    }
    array_push($rules, array("field" => "proveedor.tipo", "data" => "PAYMENT", "op" => "eq")); //Proveedor del tipo de pago aceptado para Franquicias
    /* Se crea un filtro JSON y se obtienen productos de una base de datos. */
    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);



    $productos = $ProductoMandante->getProductosMandanteCustom2("producto_mandante.prodmandante_id, producto.descripcion,proveedor.descripcion, producto.producto_id", "producto_mandante.prodmandante_id", "asc", $SkeepRows, $MaxRows, $json, true);

    $productos = json_decode($productos);

    /* transforma datos de productos en un nuevo arreglo estructurado. */
    $final = [];


    foreach ($productos->data as $key => $value) {

        $array = [];


        $array["id"] = $value->{"producto.producto_id"};
        $array["value"] = $value->{"producto.descripcion"} . "(" . $value->{"proveedor.descripcion"} . ")";

        array_push($final, $array);


    }

}

/* Código inicializa respuesta sin errores y asigna datos finales a la variable. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = $final;

/* asigna valores a las claves del array de respuesta. */
$response["pos"] = $SkeepRows;
$response["total_count"] = $productos->count[0]->{".count"};
$response["data"] = $final;
