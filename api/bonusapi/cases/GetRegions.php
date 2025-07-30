<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\IntRegion;
use Backend\dto\Pais;

/**
 * Obtiene regiones basadas en un deporte, fechas o países, manejando errores y generando una respuesta estructurada.
 *
 * @param object $params Objeto con los siguientes valores:
 * @param string $params->SportId ID del deporte para filtrar regiones.
 * @param string $params->BeginDate Fecha de inicio para filtrar regiones.
 * @param string $params->EndDate Fecha de fin para filtrar regiones.
 *
 * @return array $response Respuesta estructurada con los siguientes campos:
 * - HasError (bool): Indica si hubo un error (false si no hay errores).
 * - AlertType (string): Tipo de alerta (success en caso de éxito).
 * - AlertMessage (string): Mensaje de alerta.
 * - ModelErrors (array): Lista de errores del modelo (vacío en caso de éxito).
 * - Data (array): Datos de las regiones, países o monedas según el filtro aplicado.
 */

/* obtiene regiones basadas en un deporte y fechas, manejando errores. */
$SportId = $params->SportId;
$sportId = $_REQUEST["sportId"];


if ($SportId != "") {
    $BeginDate = $params->BeginDate;
    $EndDate = $params->EndDate;

    $regions = getRegions($SportId, $BeginDate, $EndDate);

    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "Operation has completed successfuly";
    $response["ModelErrors"] = [];
    $response["Data"] = $regions;

} elseif ($sportId != "") {


    /* Genera una consulta JSON para obtener regiones filtradas por un deporte específico. */
    $json = '{"rules" : [{"field" : "int_region.deporte_id", "data" : "' . $sportId . '","op":"eq"}] ,"groupOp" : "AND"}';


    $IntRegion = new IntRegion();
    $regiones = $IntRegion->getRegionesCustom(" int_deporte.*,int_region.* ", "int_region.region_id", "asc", 0, 10000, $json, true);
    $regiones = json_decode($regiones);


    /* crea un arreglo final con IDs y nombres de regiones. */
    $final = array();

    foreach ($regiones->data as $region) {

        $array = array();
        $array["Id"] = $region->{"int_region.region_id"};
        $array["Name"] = $region->{"int_region.nombre"};

        array_push($final, $array);

    }

    /* crea una respuesta exitosa sin errores y con datos finales. */
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "Operation has completed successfuly";
    $response["ModelErrors"] = [];

    $response["Data"] = $final;


} else {

    /* Se crea un objeto 'Pais' y se define un filtro JSON para consultar datos. */
    $Pais = new Pais();

    $SkeepRows = 0;
    $MaxRows = 1000000;

    $json = '{"rules" : [{"field" : "pais.estado", "data": "A","op":"eq"}] ,"groupOp" : "AND"}';

    /* Código para obtener y formatear datos de países y monedas en un arreglo. */
    $Partner = $_SESSION['mandante'];

    $paises = $Pais->getPaises("pais_moneda.pais_id,ciudad.ciudad_id,pais_mandante.moneda", "asc", $SkeepRows, $MaxRows, $json, true, $Partner);

    $paises = json_decode($paises);

    $final = [
        array(
            "Id" => "",
            "Name" => "All",
            "currencies" => array(
                "Id" => "",
                "Name" => "All",
            ),
            "departments" => array(
                "Id" => "",
                "Name" => "All",
            )
        )
    ];

    /* Se inicializan arreglos vacíos para almacenar monedas, ciudades y departamentos. */
    $arrayf = [];
    $monedas = [];

    $ciudades = [];
    $departamentos = [];

    foreach ($paises->data as $key => $value) {


        /* Se asignan valores a un array desde un objeto, usando propiedades específicas. */
        $array = [];

        $array["Id"] = $value->{"pais.pais_id"};
        $array["Name"] = $value->{"pais.pais_nom"};

        $departamento_id = $value->{"departamento.depto_id"};

        /* organiza datos de departamentos, ciudades y monedas en un array final. */
        $departamento_texto = $value->{"departamento.depto_nom"};

        $ciudad_id = $value->{"ciudad.ciudad_id"};
        $ciudad_texto = $value->{"ciudad.ciudad_nom"};

        if ($array["Id"] != $arrayf["Id"] && $arrayf["Id"] != "") {

            $arrayf["currencies"] = array_unique($monedas);
            $arrayf["departments"] = $departamentos;
            array_push($final, $arrayf);

            $arrayf = [];
            $monedas = [];
            $departamentos = [];
            $ciudades = [];

        }


        /* Asigna valores de país y moneda a un arreglo en PHP. */
        $arrayf["Id"] = $value->{"pais.pais_id"};
        $arrayf["Name"] = $value->{"pais.pais_nom"};

        $moneda = [];
        $moneda["Id"] = $value->{"pais_mandante.moneda"};
        $moneda["Name"] = $value->{"pais_mandante.moneda"};


        /* Se agregan monedas y departamentos, y se inicializan listas de ciudades. */
        array_push($monedas, $moneda);

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
            /* Agrega un nuevo elemento de ciudad al arreglo si no se cumple una condición. */

            $ciudad = [];
            $ciudad["Id"] = $ciudad_id;
            $ciudad["Name"] = $ciudad_texto;

            array_push($ciudades, $ciudad);
        }


        /* Extrae el ID y nombre del departamento de un objeto. */
        $departamento_idf = $value->{"departamento.depto_id"};
        $departamento_textof = $value->{"departamento.depto_nom"};

    }


    /* Se crea un departamento con ID, nombre y ciudades, luego se agrega a un array. */
    $departamento = [];
    $departamento["Id"] = $departamento_idf;
    $departamento["Name"] = $departamento_textof;
    $departamento["cities"] = $ciudades;

    array_push($departamentos, $departamento);


    /* Se agrega una moneda única y departamentos a un arreglo final. */
    $ciudades = [];

    array_push($monedas, $moneda);
    $arrayf["currencies"] = array_unique($monedas);
    $arrayf["departments"] = $departamentos;

    array_push($final, $arrayf);


    /* inicializa una respuesta sin errores y establece mensajes de éxito. */
    $regiones = $final;

    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];


    /* Asignación de datos de regiones a la clave "Data" en un arreglo de respuesta. */
    $response["Data"] = $regiones;
}