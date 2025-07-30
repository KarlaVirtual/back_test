<?php
use Backend\dto\Criptomoneda;
use Backend\dto\UsuarioMandante;
use Backend\dto\CriptoRed;
use Backend\dto\RedBlockchain;
use Backend\dto\UsuarioBanco;
use Backend\mysql\UsuarioBancoMySqlDAO;



/**
 * Descripcion: Este recurso permite obtener las wallets asociadas a una cuenta de usuario.
 *
 * @param string $SkeepRows Número de filas a omitir en la consulta.
 * @param string $OrderedItem Identificador del elemento por el cual se ordenará la consulta.
 * @param string $MaxRows Número máximo de filas a devolver en la consulta.
 */

$UsuarioMandante = new UsuarioMandante($json->session->usuario);
$SkeepRows = $_REQUEST['SkeepRows'];
$OrderedItem = $_REQUEST['OrderedItem'];
$MaxRows = $_REQUEST['MaxRows'];


if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 1000;
}



/*Definimos los filtos en los cuales solo traeremos las wallets que pertenecen al usuario actual*/

$rules = [];
array_push($rules, array("field" => "usuario_banco.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
array_push($rules, array("field" => "usuario_banco.tipo_cuenta", "data" => "Crypto", "op" => "eq"));
array_push($rules, array("field" => "usuario_banco.estado", "data" => "A", "op" => "eq"));



$rules = json_encode(array("rules" => $rules, "groupOp" => "AND"));

/*Realizamos instancia a la clase usuario banco para obtener las cuentas del usuario*/


$UsuarioBanco = new UsuarioBanco();
$cuentas = $UsuarioBanco->getUsuarioBancosCustom(" usuario_banco.*,banco.*,cripto_red.criptomoneda_id,cripto_red.redblockchain_id, cripto_red.criptored_id ", "usuario_banco.estado", "asc", $SkeepRows, $MaxRows, $rules, true);

    /**
     * Decodifica las cuentas obtenidas y prepara una colección temporal para almacenar información adicional.
     *
     * Obtiene los identificadores únicos de las redes de criptomonedas asociadas a las cuentas del usuario.
     *
     * Realiza una consulta personalizada para obtener información detallada de las redes de criptomonedas y las redes asociadas.
     */

    $cuentas = json_decode($cuentas);

    $temp = [];

if (isset($cuentas->count[0]->{'.count'}) && intval($cuentas->count[0]->{'.count'}) != 0) {
    $criptoRedIds = array_column($cuentas->data, 'cripto_red.criptored_id');

    /*Obteniendo información Cripto y redes*/
    $Rules = [];
    $CriptoRed = new CriptoRed();
    array_push($Rules, array("field" => "cripto_red.criptored_id", "data" => implode(",", $criptoRedIds), "op" => "in"));
    $Rules = json_encode(array("rules" => $Rules, "groupOp" => "AND"));
    $datos = $CriptoRed->getCriptoRedCustom("cripto_red.*,red_blockchain.nombre,criptomoneda.nombre", "cripto_red.criptored_id", "asc", 0, 100, $Rules, true);
    $datos = json_decode($datos);
}

foreach ($cuentas->data as $key => $value) {
    if (oldCount($datos->data) > 0) {
        $coleccionInfoCriptoRed = array_filter($datos->data, function ($item) use ($value) {
            return $item->{"cripto_red.criptored_id"} == $value->{"cripto_red.criptored_id"};
        });
    }
    $coleccionInfoCriptoRed = array_values($coleccionInfoCriptoRed);

    $nombreCriptoMoneda = $coleccionInfoCriptoRed[0]->{"criptomoneda.nombre"};
    $nombreCriptoRed = $coleccionInfoCriptoRed[0]->{"red_blockchain.nombre"};


    /**
     * Construye un arreglo con información procesada de una cuenta de usuario.
     *
     * @var array $array Arreglo que contiene los datos procesados de la cuenta.
     * @var int $array["id"] Identificador único de la cuenta del usuario.
     * @var string $array["account"] Número de cuenta abreviado, mostrando los primeros y últimos 4 caracteres.
     * @var string $array["state"] Estado actual de la cuenta del usuario.
     * @var string $array["cryptocurrency"] Nombre de la criptomoneda asociada a la cuenta.
     * @var string $array["network"] Nombre de la red blockchain asociada a la cuenta.
     */

    $array = [];

    $array["id"] = $value->{"usuario_banco.usubanco_id"};
    $primeros4 = substr($value->{"usuario_banco.cuenta"} , 0, 4);
    $segundos4 = substr($value->{"usuario_banco.cuenta"} , -4);
    $array["account"] = $primeros4 . "... " . $segundos4;
    $array["state"] = $value->{"usuario_banco.estado"};
    $array["cryptocurrency"] = $nombreCriptoMoneda;
    $array["network"] = $nombreCriptoRed;


    // Abreviar cuenta (si existe)
    array_push($temp, $array);
}



$response = array(
    "data" => $temp,
);
$response['code'] = 0;
