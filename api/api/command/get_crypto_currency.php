<?php
use Backend\dto\CriptoRed;
use Backend\dto\Criptomoneda;
use Backend\dto\RedBlockchain;

/**
 * Obtener las criptomonedas activas asociadas a redes blockchain.
 *
 * Este recurso filtra las criptomonedas activas asociadas a redes blockchain activas,
 * eliminando duplicados en los nombres de las criptomonedas.
 *
 * @return array Respuesta con las criptomonedas activas.
 * code`: Código de estado de la respuesta (0 para éxito).
 * `data`: Lista de criptomonedas con los campos:
 * `crypto_id`: ID de la criptomoneda.
 *  value`: Nombre de la criptomoneda.
 *
 * @throws Exception Si ocurre algún error durante la ejecución.
 */


$rules = [];

array_push($rules,array("field"=>"cripto_red.estado","data"=>"A","op"=>"eq"));
array_push($rules,array("field"=>"criptomoneda.estado","data"=>"A","op"=>"eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND");
$jsonfiltro = json_encode($filtro);

$criptored = new CriptoRed();
$datos = $criptored->getCriptoRedCustom("cripto_red.criptomoneda_id,criptomoneda.nombre","cripto_red.criptored_id","desc",0,100,$jsonfiltro,true);


$datos = json_decode($datos);


$final = [];
$names = []; // Array auxiliar para rastrear nombres únicos


foreach ($datos->data as $key => $value) {
    if (!in_array($value->{"criptomoneda.nombre"}, $names)) {
        $array = [];
        $array["crypto_id"] = $value->{"cripto_red.criptomoneda_id"};
        $array["value"] = $value->{"criptomoneda.nombre"};

        $final[] = $array; // Agrega el array al resultado final
        $names[] = $value->{"criptomoneda.nombre"}; // Marca el nombre como agregado
    }
}

$response = array();
$response["code"] = 0;
$response["data"] = $final;

