<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\Pais;

/**
 * Este script obtiene una lista de monedas activas por país.
 *
 * @param object $params No se reciben parámetros de entrada en este script.
 *
 * @return array $response Respuesta estructurada con los siguientes datos:
 *  - HasError (bool): Indica si ocurrió un error.
 *  - AlertType (string): Tipo de alerta (e.g., "success").
 *  - AlertMessage (string): Mensaje de alerta.
 *  - ModelErrors (array): Lista de errores del modelo.
 *  - Data (array): Lista única de monedas disponibles.
 */

/* crea un objeto 'Pais' y define el 'mandanteUsuario' según la sesión. */
$Pais = new Pais();

$mandanteUsuario = '';
// Si el usuario esta condicionado por el mandante y no es de Global
if ($_SESSION['Global'] == "N") {
    $mandanteUsuario = $_SESSION['mandante'];
}


/* obtiene países filtrados según una condición específica en formato JSON. */
$SkeepRows = 0;
$MaxRows = 1000000;

$json = '{"rules" : [{"field" : "pais.estado", "data": "A","op":"eq"}] ,"groupOp" : "AND"}';

$paises = $Pais->getPaises("pais_moneda.pais_id,ciudad.ciudad_id", "asc", $SkeepRows, $MaxRows, $json, true, $mandanteUsuario);


/* decodifica un JSON de países y prepara varias estructuras de datos vacías. */
$paises = json_decode($paises);

$final = [];
$arrayf = [];
$monedas = [];

$ciudades = [];

/* Se inicializa un arreglo vacío llamado "departamentos" en PHP. */
$departamentos = [];

foreach ($paises->data as $key => $value) {


    /* Asignación de valores de un objeto a un array en PHP. */
    $array = [];

    $array["Id"] = $value->{"pais.pais_id"};
    $array["Name"] = $value->{"pais.pais_nom"};

    $departamento_id = $value->{"departamento.depto_id"};

    /* Código que gestiona departamentos, ciudades y monedas en un arreglo final. */
    $departamento_texto = $value->{"departamento.depto_nom"};

    $ciudad_id = $value->{"ciudad.ciudad_id"};
    $ciudad_texto = $value->{"ciudad.ciudad_nom"};

    if ($array["Id"] != $arrayf["Id"] && $arrayf["Id"] != "") {

        $arrayf["currencies"] = array_unique($monedas);
        $arrayf["departaments"] = $departamentos;
        array_push($final, $arrayf);
        array_push($monedas, $moneda);

        $arrayf = [];
        //$monedas = [];
        $departamentos = [];
        $ciudades = [];

    }


    /* Se asignan valores de país y moneda a un array asociativo en PHP. */
    $arrayf["Id"] = $value->{"pais.pais_id"};
    $arrayf["Name"] = $value->{"pais.pais_nom"};

    $moneda = [];
    $moneda["Id"] = $value->{"pais_moneda.moneda"};
    $moneda["Name"] = $value->{"pais_moneda.moneda"};


    /* Condición que agrega un departamento y sus ciudades a un arreglo. */
    if ($departamento_idf != $departamento_id && $departamento_idf != "") {

        $departamento = [];
        $departamento["Id"] = $departamento_idf;
        $departamento["Name"] = $departamento_textof;
        $departamento["cities"] = $ciudades;

        array_push($departamentos, $departamento);

        $ciudades = [];

        $ciudad = [];
        $ciudad["Id"] = $ciudad_id;
        $ciudad["Name"] = $ciudad_texto;

        array_push($ciudades, $ciudad);

    } else {
        /* Agrega un nuevo elemento ciudad a un arreglo si no se cumple una condición. */

        $ciudad = [];
        $ciudad["Id"] = $ciudad_id;
        $ciudad["Name"] = $ciudad_texto;

        array_push($ciudades, $ciudad);
    }


    /* Asignación de variables para ID y nombre de departamento desde un objeto. */
    $departamento_idf = $value->{"departamento.depto_id"};
    $departamento_textof = $value->{"departamento.depto_nom"};

}


/* Se crea un array con detalles de un departamento y se agrega a otro array. */
$departamento = [];
$departamento["Id"] = $departamento_idf;
$departamento["Name"] = $departamento_textof;
$departamento["cities"] = $ciudades;

array_push($departamentos, $departamento);


/* Se crea un arreglo con monedas y departamentos, evitando duplicados en monedas. */
$ciudades = [];

array_push($monedas, $moneda);
$arrayf["currencies"] = array_unique($monedas);
$arrayf["departments"] = $departamentos;

array_push($final, $arrayf);


/* Se crea un array que almacena regiones con su ID, nombre y países asociados. */
$regiones = [];

$array["Id"] = "1";
$array["Name"] = "America";
$array["countries"] = $final;

array_push($regiones, $array);


/* configura una respuesta exitosa con datos y sin errores. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = ($monedas);

/* Guarda datos únicos de "monedas" en el arreglo multidimensional "Data" de $response. */
$response["Data"] = (unique_multidim_array2($monedas, "Id"));
