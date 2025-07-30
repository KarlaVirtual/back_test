<?php
use Backend\dto\CriptoRed;
use Backend\dto\RedBlockchain;
use Backend\dto\Criptomoneda;
use Backend\dto\UsuarioMandante;


/**
 * Obtener redes de blockchain asociadas a una criptomoneda.
 *
 * Este recurso filtra las redes de blockchain activas asociadas a una criptomoneda específica,
 * eliminando duplicados en los nombres de las redes.
 *
 * @param object $json Objeto JSON con los parámetros de entrada.
 * - `cryptocurrency_id`: ID de la criptomoneda para filtrar las redes.
 * @throws Exception Si ocurre algún error durante la ejecución.
 */

$cryptoCurrency_id = $json->params->cryptocurrency_id;

$rules = [];


// Construcción de las reglas del filtro


array_push($rules, array("field" => "cripto_red.criptomoneda_id", "data" => $cryptoCurrency_id, "op" => "eq"));
array_push($rules, array("field" => "cripto_red.estado", "data" => "A", "op" => "eq"));
array_push($rules,array("field"=>"criptomoneda.estado","data"=>"A","op"=>"eq"));
array_push($rules, array("field" => "red_blockchain.estado", "data" => "A", "op" => "eq"));

// Codificación del filtro en formato JSON

$filtro = array("rules" => $rules, "groupOp" => "AND");
$jsonfiltro = json_encode($filtro);

$criptoRed = new CriptoRed();
$datos = $criptoRed->getCriptoRedCustom("cripto_red.redblockchain_id,red_blockchain.nombre", "cripto_red.criptored_id", "desc", 0, 100, $jsonfiltro, true);


// Decodificar los datos obtenidos

$datos = json_decode($datos);


$final = [];
$names = []; // Array auxiliar para rastrear nombres únicos

foreach($datos->data as $key=>$value){
    if (!in_array($value->{"red_blockchain.nombre"}, $names)) {
        $array = [];
        $array["network_Id"] = $value->{"cripto_red.redblockchain_id"};
        $array["value"] = $value->{"red_blockchain.nombre"};

        $final[] = $array; // Agrega el array al resultado final
        $names[] = $value->{"red_blockchain.nombre"}; // Marca el nombre como agregado
    }
}

$response = array();
$response["code"] = 0;
$response["data"] = $final;