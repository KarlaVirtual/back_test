<?php

use Backend\dto\CriptoRed;
use Backend\dto\RedBlockchain;
use Backend\dto\Criptomoneda;
use Backend\dto\UsuarioMandante;


/**
 * Este script obtiene las redes de criptomonedas activas y las devuelve en un formato específico.
 */

// Inicialización de reglas para filtrar las redes de criptomonedas activas

$rules = [];


array_push($rules, array("field" => "criptomoneda.estado", "data" => "A", "op" => "eq"));
array_push($rules, array("field" => "red_blockchain.estado", "data" => "A", "op" => "eq"));


$filtro = array("rules" => $rules, "groupOp" => "AND");
$jsonfiltro = json_encode($filtro);

/**
 * Clase CriptoRed utilizada para obtener datos de redes de criptomonedas.
 */

$criptoRed = new CriptoRed();
$datos = $criptoRed->getCriptoRedCustom("cripto_red.criptored_id,cripto_red.redblockchain_id,red_blockchain.nombre,criptomoneda.nombre,criptomoneda.criptomoneda_id", "cripto_red.criptored_id", "desc", 0, 100, $jsonfiltro, true);
// Decodificación de los datos obtenidos

$datos = json_decode($datos);

$final = [];
$names = []; // Array auxiliar para rastrear nombres únicos


/**
 *  Procesa los datos obtenidos y elimina duplicados basados en el nombre de la blockchain.
 *
 * @var array $final Arreglo que almacena el resultado final con los datos procesados.
 * @var object $data->data Contiene los datos obtenidos de la consulta.
 * @var object $value Elemento actual del recorrido en los datos.
 * @var array $array Arreglo temporal que almacena el id y descripción de la criptored.
 */

foreach ($datos->data as $key => $value) {
        $array = [];
        $array["id"] = $value->{"cripto_red.criptored_id"};
        $array["value"] = $value->{"criptomoneda.nombre"} . " - " . $value->{"red_blockchain.nombre"};
        $final[] = $array; // Agrega el array al resultado final
        $names[] = $value->{"red_blockchain.nombre"}; // Marca el nombre como agregado
}


// Construcción de la respuesta final

$response = array();
$response["code"] = 0;
$response["Data"] = $final; // Datos procesados