<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\IntDeporte;

/**
 * Este script maneja diferentes casos relacionados con deportes, como obtener deportes entre fechas específicas
 * o realizar consultas personalizadas para obtener información detallada de deportes.
 *
 * @param string $URI URI de la solicitud que se utiliza para determinar el caso a ejecutar.
 * @param string $BeginDate Fecha de inicio para filtrar deportes (solo en el caso "Sport/GetSports").
 * @param string $EndDate Fecha de fin para filtrar deportes (solo en el caso "Sport/GetSports").
 * @param string $json Filtro JSON utilizado para consultas personalizadas (solo en el caso "OddsFeed/GetSports").
 *
 * @return array $response Respuesta estructurada con los datos solicitados, incluyendo:
 * - HasError (bool): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta (por ejemplo, "success").
 * - AlertMessage (string): Mensaje de la operación.
 * - ModelErrors (array): Lista de errores de modelo, si los hay.
 * - Data (array): Datos obtenidos según el caso ejecutado.
 */

/* divide una URI en partes y cuenta elementos utilizando una función personalizada. */
$obj = (explode("/", current(explode("?", $URI))));

$count = oldCount($obj);
switch ($obj[$count - 2] . "/" . $obj[$count - 1]) {
    case "Sport/GetSports":
        /* obtiene deportes entre dos fechas y genera una respuesta de éxito. */

        $BeginDate = $_REQUEST["BeginDate"];
        $EndDate = $_REQUEST["EndDate"];
        $sports = getSports($BeginDate, $EndDate);
        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "Operation has completed successfuly";
        $response["ModelErrors"] = [];
        $response["Data"] = $sports;

        break;

    case "OddsFeed/GetSports":


        /* define filtros JSON y obtiene deportes mediante una consulta personalizada. */
        $json = '{"rules" : [{"field" : "", "data" : "2","op":"eq"}] ,"groupOp" : "AND"}';


        $IntDeporte = new IntDeporte();
        $sports = $IntDeporte->getDeportesCustom(" int_deporte.* ", "int_deporte.deporte_id", "asc", 0, 10000, $json, false);
        $sports = json_decode($sports);


        /* Se crea un arreglo final extrayendo información de cada deporte en un bucle. */
        $final = array();

        foreach ($sports->data as $sport) {

            $array = array();
            $array["Id"] = $sport->{"int_deporte.deporte_id"};
            $array["Name"] = $sport->{"int_deporte.nombre"};
            $array["NameId"] = $sport->{"int_deporte.nombre"};

            array_push($final, $array);

        }


        /* establece una respuesta con datos y un mensaje de éxito. */
        $response["Data"] = $final;


        /* Inicializa un arreglo vacío para almacenar errores de modelo en la respuesta. */
        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "Operation has completed successfuly";
        $response["ModelErrors"] = [];

        break;
}