<?php
use Backend\dto\CriptoRed;
use Backend\dto\Criptomoneda;
use Backend\dto\RedBlockchain;




/**
 * Este recurso permite obtener las redes blockchain asociadas a una criptored siempre y cuando esten activas
 *
 *  Definición de las reglas de filtrado para la consulta.
 *  En este caso, se especifica que solo se traerán las redes de blockchain que estén activas (estado "A").
 */
$keyword = $params->Filter;

/*Descripcion: Este recurso permite obtener las criptomonedas activas*/
if ($keyword != "" & $keyword != null) {
    // Agrega reglas de filtro por descripción y estado activo
    $rules = [];

    array_push($rules, array("field" => "red_blockchain.nombre", "data" => $keyword, "op" => "cn"));

    // Se agrega una regla al arreglo de reglas, indicando que el campo "estado" de la tabla "red_blockchain" debe ser igual a "A".
    array_push($rules, array("field" => "red_blockchain.estado", "data" => "A", "op" => "eq"));

    // Construcción del filtro JSON y consulta de Franquicias
    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);
    /*Se realiza una instancia a la clase RedBlockchain para de esta manera obtener la informacion de las redes blockchain*/
    $RedBlockchain = new RedBlockchain();
    $datos = $RedBlockchain->getRedBlockchainsCustom("red_blockchain.redblockchain_id,red_blockchain.nombre", "red_blockchain.redblockchain_id", "desc", 0, 200, $json, true);

    // Se convierte el arreglo de reglas a formato JSON para ser utilizado en la consulta.

    $datos = json_decode($datos);


    $final = [];
    $names = []; // Array auxiliar para rastrear nombres únicos


    /**
     * @var array $id Contiene los identificadores únicos de las redes de blockchain.
     * @var array $value Contiene los nombres de las redes de blockchain.
     * @var array $names Almacena los nombres únicos para evitar duplicados.
     * @var array $final Resultado final con los datos procesados.
     * @var object $value Elemento actual del recorrido en los datos.
     */

    foreach ($datos->data as $key => $value) {
        if (!in_array($value->{"red_blockchain.nombre"}, $names)) {
            $array = [];
            $array["id"] = $value->{"red_blockchain.redblockchain_id"};
            $array["value"] = $value->{"red_blockchain.nombre"};
            $final[] = $array; // Agrega el array al resultado final
            $names[] = $value->{"red_blockchain.nombre"}; // Marca el nombre como agregado
        }
    }
}

$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "Consulta exitosa";
$response["Data"] = $final;

