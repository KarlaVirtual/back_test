<?php

    use Backend\dto\EquipoFavorito;
    use Backend\dto\Pais;

    /**
     * Obtiene y devuelve una lista de equipos favoritos filtrados por país y sitio.
     *
     * @param object $json Objeto JSON que contiene los parámetros:
     *  - string $params->country País para filtrar los equipos.
     *  - int $params->site_id ID del sitio para filtrar los equipos.
     *  - int $params->MaxRows Número máximo de filas a obtener.
     *  - int $params->OrderedItem Elemento ordenado.
     *  - int $params->SkeepRows Número de filas a omitir.
     *  - int $params->count Número máximo de filas a obtener.
     *  - int $params->start Número de filas a omitir.
     *
     * @return array Respuesta con los datos de los equipos favoritos:
     *  - int $response["code"] Código de respuesta.
     *  - string $response["rid"] ID de la solicitud.
     *  - array $response["data"] Datos de los equipos favoritos.
     *  - int $response["total_count"] Número total de equipos favoritos.
     */

    /*El código obtiene y devuelve una lista de equipos favoritos filtrados por país y sitio, utilizando parámetros proporcionados en formato JSON.*/
    $response = array();
    $response["code"] = 0;
    $response["rid"] = $json->rid;
    $response["data"] = array(
        "result" => ""
    );

    /*Obtención de parámetros*/
    $params = $json->params;
    $country = $params->country;
    $site_id = $params->site_id;

    $MaxRows = $params->MaxRows;
    $OrderedItem = $params->OrderedItem;
    $SkeepRows = $params->SkeepRows;

    $MaxRows = $json->params->count;
    $SkeepRows = $json->params->start;

    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }

    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }

    if ($MaxRows == "") {
        $MaxRows = 20;
    }

    $Pais = new Pais('', strtoupper($country));

    /*Definiendo criterios de filtrado*/
    $rules = [];

    array_push($rules, array("field" => "equipo_favorito.mandante", "data" => $site_id, "op" => "eq"));
    array_push($rules, array("field" => "equipo_favorito.pais_id", "data" => $Pais->paisId, "op" => "eq"));
    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $jsonfiltro = json_encode($filtro);

    $select = "equipo_favorito.*";

    $EquipoFavorito = new EquipoFavorito();

    /*Obtención de colección de información personalizada*/
    $data = $EquipoFavorito->getEquipoFavorito($select, "equipo_favorito.equipo_id", 'asc', $SkeepRows, $MaxRows, $jsonfiltro, true);
    $equipos = json_decode($data);
    $equiposData = array();

    /*El código obtiene una lista de equipos favoritos filtrados por país y sitio, y devuelve los datos en formato JSON.*/
    foreach ($equipos->data as $key => $value) {
        $array = array();
        $array["Id"] = $value->{"equipo_favorito.equipo_id"};
        $array["Name"] = $value->{"equipo_favorito.nombre"};
        $array["Shield"] = $value->{"equipo_favorito.escudo"};

        array_push($equiposData, $array);
    }

    //Definición de respuesta
    $response = array();
    $response["code"] = 0;
    $response["data"] = $equiposData;
    $response["total_count"] = $equipos->count[0]->{".count"};
    $response["rid"] = $json->rid;
?>
