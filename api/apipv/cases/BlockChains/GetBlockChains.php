<?php

use Backend\dto\RedBlockchain;
use Backend\dto\ConfigurationEnvironment;


/**
 * Obtener listado de RedBlockchains
 *
 * Obtiene una lista de RedBlockchains basándose en filtros como nombre, estado, código o ID,
 * con soporte para paginación y ordenamiento. También valida los permisos del usuario para acceder a la operación.
 *
 * @param int $count          Número máximo de registros a devolver. Por defecto: 100.
 * @param int $start          Número de registros a omitir para la paginación. Por defecto: 0.
 * @param int $OrderedItem    Criterio de ordenamiento. Por defecto: 1.
 * @param string $name        Nombre exacto de la RedBlockchain a filtrar.
 * @param string $IsActivate  Estado de la RedBlockchain ('A' para activo, 'I' para inactivo).
 * @param string $code        Código exacto de la RedBlockchain.
 * @param int $Id             ID específico de la RedBlockchain.
 *
 * El objeto $response es un array con los siguientes atributos:
 *
 *  - *HasError* (bool)         Indica si hubo un error en la operación.
 *  - *AlertType* (string)      Especifica el tipo de alerta que se mostrará en la vista.
 *  - *AlertMessage* (string)   Contiene el mensaje que se mostrará en la vista.
 *  - *ModelErrors*  (array)    Lista de errores de validación del modelo. En este caso, retorna un array vacío si no hay errores.
 *  - *pos* (int):              Posición inicial del resultado (paginación).
 *  - *total_count* (int)       Número total de registros encontrados.
 *  - *data* (array)            Arreglo de objetos que representan cada RedBlockchain, con los siguientes campos:
 *      - *id* (int)            Identificador de la RedBlockchain.
 *      - *name* (string)       Nombre de la RedBlockchain.
 *      - *code* (string)       Código único de la RedBlockchain.
 *      - *state* (string)      Estado ('A' o 'I').
 *      - *date_crea* (string)  Fecha de creación.
 *      - *date_modif* (string) Fecha de última modificación.
 *      - *usucrea_id* (int)    ID del usuario que creó el registro.
 *      - *usumodif_id* (int)   ID del usuario que modificó el registro.
 *
 * Ejemplo de respuesta en caso de éxito:
 *
 * $response["HasError"] = false;
 * $response["AlertType"] = "success";
 * $response["AlertMessage"] = "";
 * $response["ModelErrors"] = [];
 * $response["pos"] = 0;
 * $response["total_count"] = 3;
 * $response["data"] = [
 *      [
 *          "id" => 1,
 *          "name" => "RedX",
 *          "code" => "COD123",
 *          "state" => "A",
 *          "date_crea" => "2025-06-06 10:00:00",
 *          "date_modif" => "2025-06-06 11:00:00",
 *          "usucrea_id" => 100,
 *          "usumodif_id" => 0
 *      ],
 *      ...
 * ];
 *
 * @throws Exception no.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */



/* procesa parámetros de solicitud para filtrar y paginar datos. */
$MaxRows = $_REQUEST["count"];
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];

$Name = $_REQUEST["Name"];  // parametro que permite filtar por nombre
$State = $_REQUEST["IsActivate"]; // parametro que permite filtar por estado
$Code = $_REQUEST["code"]; // parametro que permite filtar por estado
/* obtiene parámetros de solicitud para filtrar y establecer valores predeterminados. */
$Id = $_REQUEST["Id"]; // parametro que permite filtar por Id del RedBlockchain

try {
    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }

    if ($OrderedItem == "" || $OrderedItem == null) {
        $OrderedItem = 1;
    }


    /* establece un valor predeterminado y prepara filtros para una consulta. */
    if ($MaxRows == "") {
        $MaxRows = 100;
    }

    $rules = [];

// filtros

    if ($Id != "") {
        array_push($rules, array("field" => "red_blockchain.redblockchain_id", "data" => "$Id", "op" => "eq"));
    }


    /* agrega condiciones a un arreglo basado en variables `$Name` y `$State`. */
    if ($Name != "") {
        array_push($rules, array("field" => "red_blockchain.redblockchain_id", "data" => "$Name", "op" => "eq"));
    }

    if ($Code != "") {
        array_push($rules, array("field" => "red_blockchain.codigo_red", "data" => "$Code", "op" => "eq"));
    }

    if ($State != "" and $State == "A") {
        array_push($rules, array("field" => "red_blockchain.estado", "data" => "A", "op" => "eq"));
    } else if ($State != "" and $State == "I") {
        /* Agrega una regla si el estado es "I". */

        array_push($rules, array("field" => "red_blockchain.estado", "data" => "I", "op" => "eq"));
    }


    /* Se generan registros de red_blockchains filtrados y paginados desde la base de datos. */
    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $jsonfiltro = json_encode($filtro);

    $RedBlockchain = new RedBlockchain(); // se instancia la clase RedBlockchain para traer los registros de la Base de datos en la tabla RedBlockchain
    $RedBlockchains = $RedBlockchain->getRedBlockchainsCustom("red_blockchain.*", "red_blockchain.redblockchain_id", "desc", $SkeepRows, $MaxRows, $jsonfiltro, true);

    $RedBlockchains = json_decode($RedBlockchains);


    /* transforma datos de RedBlockchains en un arreglo estructurado. */
    $final = [];

    foreach ($RedBlockchains->data as $key => $value) {
        $array = [];
        $array["id"] = $value->{"red_blockchain.redblockchain_id"};
        $array["name"] = $value->{"red_blockchain.nombre"};
        $array["code"] = $value->{"red_blockchain.codigo_red"};
        $array["state"] = $value->{"red_blockchain.estado"};
        $array["date_crea"] = $value->{"red_blockchain.fecha_crea"};
        $array["date_modif"] = $value->{"red_blockchain.fecha_modif"};
        $array["usucrea_id"] = $value->{"red_blockchain.usucrea_id"};
        $array["usumodif_id"] = $value->{"red_blockchain.usumodif_id"};
        array_push($final, $array);

    }

} catch (Exception $e) {


}



/* Código que inicializa una respuesta sin errores, y asigna valores relacionados con la respuesta. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["pos"] = $SkeepRows;

/* Asigna el conteo de RedBlockChains y datos finales a la respuesta. */
$response["total_count"] = $RedBlockchains->count[0]->{".count"};
$response["data"] = $final;