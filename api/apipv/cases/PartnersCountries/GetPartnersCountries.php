<?php

use Backend\dto\Pais;
use Backend\dto\PaisMandante;

/**
 * Obtener países asociados a un mandante.
 *
 * Este script permite obtener la lista de países asociados a un mandante con filtros personalizados.
 *
 * @param array $_REQUEST Arreglo que contiene los siguientes parámetros:
 * @param int $_REQUEST["count"] Número máximo de filas a obtener.
 * @param int $_REQUEST["start"] Número de filas a omitir.
 * @param string $_REQUEST["Partner"] Identificador del mandante.
 * @param int $_REQUEST["Id"] ID del país.
 * @param string $_REQUEST["Name"] Nombre del país.
 * @param string $_REQUEST["paisString"] Cadena de IDs de países.
 * 
 *
 * @return array $response Respuesta con los siguientes índices:
 *  - hasError (bool): Indica si hubo un error.
 *  - AlertType (string): Tipo de alerta ('success', 'error', etc.).
 *  - AlertMessage (string): Mensaje de alerta.
 *  - ModelErrors (array): Lista de errores del modelo.
 *  - Data (array): Listas de países incluidos y excluidos.
 *  - pos (int): Posición actual en la paginación.
 *  - total_count (int): Total de registros encontrados.
 */

// use Backend\mandante;


/* gestiona la paginación estableciendo filas máximas y filas a omitir. */
$MaxRows = $_REQUEST["count"];
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];

if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* Asigna valores predeterminados a las variables si están vacías. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 10000;
}


/* obtiene valores de solicitudes y define una orden de país. */
$Partner = $_REQUEST["Partner"];
$Id = $_REQUEST["Id"];
$Name = $_REQUEST["Name"];
$paisString = $_REQUEST["paisString"];


$orden = "pais_mandante.pais_id";

/* Código para definir reglas de filtrado basadas en el valor de una variable. */
$tipoOrden = "asc";

$rules = [];

if ($partner != "") {
    array_push($rules, array("field" => "pais_mandante.mandante", "data" => $partner, "op" => "eq"));
}


/* Agrega reglas a un arreglo según condiciones de variables no vacías. */
if ($Id != "") {
    array_push($rules, array("field" => "pais.pais_id", "data" => "$Id", "op" => "eq"));
}

if ($Partner != "") {
    array_push($rules, array("field" => "pais_mandante.mandante", "data" => "$Partner", "op" => "eq"));
}

/* Se define un filtro para reglas sobre el campo "estado" de "pais_mandante". */
array_push($rules, array("field" => "pais_mandante.estado", "data" => "A", "op" => "eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND");
$jsonfiltro = json_encode($filtro);

$paisMandante = new PaisMandante(); //una instancia de la clase PaisMandante que obteniendo las propiedades de la clase

/* obtiene y transforma datos filtrados de "pais_mandante" a un objeto JSON. */
$datos = $paisMandante->getPaisMandantesCustom("pais_mandante.* ", $orden, $tipoOrden, $SkeepRows, $MaxRows, $jsonfiltro, true);//propiedad de la clase PaisMandante mandar unos parametros para armar una consulta que retona un json que tiene dos propiedades count que trae el numero de datos que hay en la tabla y los datos filtrados de la table en si

$datos = json_decode($datos);//lo vuelve un objeto. la $datos representa los datos obtenidos de la tabla pais_mandante

// nota : esta parte del codigo esta funcionando.

$final = [];


/* recorre datos y almacena IDs de país en una variable concatenada. */
$children_final = [];
$children_final2 = [];

foreach ($datos->data as $key => $value) { //esta recorriendo el objeto datos en su propiedad data y esta sacando el valor de pais_id y lo esta almacenando en una variable
    $paisString = $paisString . $value->{"pais_mandante.pais_id"} . ",";  //estoy trayendo todos los id_pais que tengo en la tabla de pais_mandante

}


/* obtiene parámetros para gestionar filas en una consulta, incluyendo conteo y desplazamiento. */
$MaxRows = $_REQUEST["count"];
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];


if ($SkeepRows == "") {
    $SkeepRows = 0;
}

/* Asigna valores predeterminados a $OrderedItem y $MaxRows si están vacíos. */
if ($OrderedItem == "") {
    $OrderedItem = 0;
}
if ($MaxRows == "") {
    $MaxRows = 10000000;
}


/* Se define un filtro de búsqueda basado en reglas y condiciones específicas. */
$rules = []; // estoy definiendo las reglas por las cuales voy a filtrar

if ($Name != "") {
    array_push($rules, array("field" => "pais.pais_nom", "data" => $Name, "op" => "eq"));
}

$filtro = array("rules" => $rules, "groupOp" => "AND");

/* Se convierte un filtro a JSON y se obtienen países personalizados a través de una consulta. */
$jsonfiltro = json_encode($filtro);

$pais = new Pais(); // estoy instanciando de la clase pais que es necesaria para poder armar la consulta que recibe unos parametros para luego lo que obtengo en esta variable pais lo convierto en un objeto;

$paises = $pais->getPaisesCustom("pais_nom", "asc", $SkeepRows, $MaxRows, $jsonfiltro, true);
$paises = json_decode($paises);   // la variable paises representa los nombres obtenidos de la tabla pais.

// esta consulta esta funcionando.


/* recorre un objeto, extrae datos y los agrega a un array final. */
$final = [];

foreach ($paises->data as $key => $value) {  // esta recorriendo el objeto paises en su propiedad data

    $array = [];

    $children = [];
    $children["id"] = $value->{"pais.pais_id"};
    $children["value"] = $value->{"pais.pais_nom"};

    array_push($final, $children); // con este arrayu push lo que estoy haciendo es que a el array final[]
    // le estoy agregando el contenido del array children.

    // print_r($final);

    // esta parte del codigo hasta aca esta correcta.
}

if ($partnerReference != "" && $partner != "-1") {  // acá lo que estamos haciendo es una validacion para saber que el partner de referencia en este caso sea 0


    /* Se define un array de reglas y se agrega una condición si $Id no está vacío. */
    $rules = [];  // aca lo que estoy haciendo es que estoy definiendo las reglas


    if ($Id != "") {
        array_push($rules, array("field" => "pais_mandante.paismandanteid", "data" => "$Id", "op" => "eq")); // NOTA VER EJEMPLO MAÑANA LINEA 493 DE GETGROUPROVIDERS

    }

    /* Agrega condiciones a un array de reglas basado en referencias y país. */
    if ($partnerReference != "" && $partnerReference != "-1") {
        array_push($rules, array("field" => "pais_mandante.mandante", "data" => "$partnerReference", "op" => "eq"));
    }
    if ($PaisId = !"") {
        array_push($rules, array("field" => "pais_mandante.pais_id", "data" => "$PaisId", "op" => "eq"));
    }

    /* Agrega reglas de filtrado para un país y su estado en una consulta. */
    if ($Name != "") {
        array_push($rules, array("field" => "pais.pais_nom", "data" => "$Name", "op" => "eq"));
    }

    array_push($rules, array("field" => "pais_mandante.estado", "data" => "A", "op" => "eq"));  // estoy creando la regla para poder filtar por estado


    $orden = "pais_mandante.paismandante_id";


    /* Se crea un filtro y se obtienen países y mandantes con condiciones personalizadas. */
    $paisMandante = new PaisMandante();

    $filtro = array("rules" => $rules, "group" => "AND");
    $jsonfiltro = json_encode($filtro);

    $paises = $paisMandante->getPaisMandantesCustom("pais_mandante.*,mandante.*pais. * ", $orden, $ordenTipo, $SkeepRows, $MaxRows, $jsonfiltro, true);

    /* decodifica JSON de países y lo imprime para revisión. */
    $paises = json_decode($paises);

    // nota: revision en esta parte del codigo esta el error que no permite que los datos provengan
    print_r($paises);

    $final = [];


    /* crea un array con información de países a partir de un objeto. */
    $children_final = [];
    // $children_final2 = [];

    foreach ($paises->data as $key => $value) {
        $array = [];
        $children["id"] = $value->{"pais.pais_id"};
        $children["value"] = $value->{"pais.nom"};

        array_push($children_final, $children);
    }

}


/* Código establece una respuesta sin errores y procesa una cadena de país eliminando el último carácter. */
$response["hasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

//print_r($jsonfiltro);

if ($paisString != '') {
    $paisString = substr($paisString, 0, strlen($paisString) - 1);
}

/* asigna listas de países a un arreglo de respuesta. */
$response["Data"]["ExcludedCountriesList"] = $final;
$response["Data"]["IncludedCountriesList"] = $paisString; // acá lo que esta haciendo es que esta respondiendo lo que tiene la $paisString que esta mas arriba definida

// echo json_encode($response);


$response["pos"] = $SkeepRows;

/* Asigna el conteo de datos a "total_count" en la respuesta. */
$response["total_count"] = $datos->count[0]->{".count"};
// $response["data"] = $final;

