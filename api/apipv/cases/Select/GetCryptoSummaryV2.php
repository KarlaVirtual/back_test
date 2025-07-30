<?php
use Backend\dto\CriptoRed;
use Backend\dto\Criptomoneda;
use Backend\dto\RedBlockchain;
$keyword = $params->Filter;

/*Descripcion: Este recurso permite obtener las criptomonedas activas*/
if ($keyword != "" & $keyword != null) {
    // Se inicializa un arreglo vacío para almacenar las reglas de filtrado.
    $rules = [];

    // Agrega reglas de filtro por descripción y estado activo
    array_push($rules, array("field" => "criptomoneda.nombre", "data" => $keyword, "op" => "cn"));
    // Se agrega una regla al arreglo de reglas, indicando que el campo "estado" de la tabla "criptomoneda" debe ser igual a "A".
    array_push($rules, array("field" => "criptomoneda.estado", "data" => "A", "op" => "eq"));

    // Se crea un filtro que agrupa las reglas con el operador lógico "AND".
    $filtro = array("rules" => $rules, "groupOp" => "AND");

    // Se convierte el filtro a formato JSON para ser utilizado en la consulta.
    $jsonfiltro = json_encode($filtro);

    // Se instancia la clase CriptoRed para realizar la consulta de criptomonedas.
    $criptored = new CriptoRed();

    // Se ejecuta la consulta personalizada para obtener los datos de las criptomonedas activas.
    $datos = $criptored->getCriptoRedCustom("criptomoneda.criptomoneda_id,criptomoneda.nombre", "criptomoneda.criptomoneda_id", "desc", 0, 200, $jsonfiltro, true);

    // Se decodifica el resultado de la consulta desde formato JSON a un objeto PHP.
    $datos = json_decode($datos);

    // Se inicializan arreglos auxiliares para procesar los datos obtenidos.
    $final = [];
    $names = []; // Array auxiliar para rastrear nombres únicos.


    /**
     * Itera sobre los datos obtenidos y filtra nombres únicos de criptomonedas.
     *
     * @var object $value Elemento actual del recorrido en los datos.
     * @var array $array Arreglo temporal que almacena el id y nombre de la criptomoneda.
     * @var array $names Almacena los nombres únicos para evitar duplicados.
     * @var array $final Resultado final con los datos procesados.
     * @var int $id Identificador único de la criptomoneda.
     */


    foreach ($datos->data as $key => $value) {
        if (!in_array($value->{"criptomoneda.nombre"}, $names)) {
            $array = [];
            $array["id"] = $value->{"criptomoneda.criptomoneda_id"};
            $array["value"] = $value->{"criptomoneda.nombre"};
            $final[] = $array; // Agrega el array al resultado final
            $names[] = $value->{"criptomoneda.nombre"}; // Marca el nombre como agregado
        }
    }
}

$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "Consulta exitosa";
$response["Data"] = $final;

