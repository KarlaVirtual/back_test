<?php

use Backend\dto\IntCompetencia;

/**
 * Este script maneja la obtención de competiciones deportivas y de feed de probabilidades.
 * 
 * @param array $URI Contiene la URL de la solicitud dividida en segmentos.
 * @param array $_REQUEST Contiene los parámetros de la solicitud HTTP, como sportId, regionId, BeginDate y EndDate.
 * 
 * @return array $response Respuesta estructurada que incluye:
 * - HasError: Indica si ocurrió un error (boolean).
 * - AlertType: Tipo de alerta (string).
 * - AlertMessage: Mensaje de la operación (string).
 * - ModelErrors: Lista de errores de modelo (array).
 * - Data: Datos de las competiciones obtenidas (array).
 */



/* divide una URL en segmentos y obtiene un conteo antiguo de ellos. */
$obj = (explode("/", current(explode("?", $URI))));
$count = oldCount($obj);
switch ($obj[$count - 2] . "/" . $obj[$count - 1]) {
    case "Sport/GetCompetitions":
        /* obtiene competiciones deportivas basándose en parámetros de fecha y ubicación. */

        $BeginDate = $_REQUEST["BeginDate"];
        $EndDate = $_REQUEST["EndDate"];
        $sports = getCompetitions($_REQUEST['sportId'], $_REQUEST['regionId'], $BeginDate, $EndDate);

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "Operation has completed successfuly";
        $response["ModelErrors"] = [];
        $response["Data"] = $sports;


        break;

    case "OddsFeed/GetCompetitions":


        /* Recoge datos de solicitudes y crea una regla en formato JSON para consultas. */
        $sportId = $_REQUEST["sportId"];
        $regionId = $_REQUEST["regionId"];

        $json = '{"rules" : [{"field" : "int_competencia.region_id", "data" : "' . $regionId . '","op":"eq"}] ,"groupOp" : "AND"}';


        $IntCompetencia = new IntCompetencia();

        /* obtiene y transforma datos de competencias en un array final. */
        $competencias = $IntCompetencia->getCompetenciasCustom(" int_competencia.* ", "int_competencia.competencia_id", "asc", 0, 10000, $json, true);
        $competencias = json_decode($competencias);


        $final = array();

        foreach ($competencias->data as $competencia) {

            $array = array();
            $array["Id"] = $competencia->{"int_competencia.competencia_id"};
            $array["Name"] = $competencia->{"int_competencia.nombre"};

            array_push($final, $array);

        }


        /* crea una respuesta con datos, sin errores y mensaje de éxito. */
        $response["Data"] = $final;


        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "Operation has completed successfuly";

        /* Se inicializa un array vacío para almacenar errores de modelo en la respuesta. */
        $response["ModelErrors"] = [];

        break;
}
