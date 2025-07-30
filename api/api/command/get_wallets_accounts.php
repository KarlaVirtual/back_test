<?php

/**
 * Obtiene las cuentas de wallets asociadas a un usuario.
 *
 * Este recurso consulta las cuentas de wallets (criptomonedas) vinculadas a un usuario específico,
 * retornando información relevante como el identificador, nombre de la criptomoneda, red blockchain y
 * una versión abreviada de la cuenta.
 *
 * @author  David Alvarez
 * @since   2024-06-26
 *
 * @param   object $json Objeto JSON recibido con los parámetros de la petición (incluye el rid).
 * @return  array  Respuesta en formato JSON con el listado de wallets y sus datos abreviados.
 *
 * Ejemplo de respuesta:
 * {
 *   "code": 0,
 *   "rid": "valor_rid",
 *   "pos": 0,
 *   "total_count": 2,
 *   "data": [
 *     {
 *       "id": 123,
 *       "wallet": "Bitcoin - Ethereum -> 1A2B...3YZ"
 *     },
 *     {
 *       "id": 124,
 *       "wallet": "USDT - Tron -> 1C3D...7WX"
 *     }
 *   ]
 * }
 *
 * Notas:
 * - Solo se retornan cuentas activas (estado 'A').
 * - La cuenta se muestra abreviada para mayor seguridad.
 * - El recurso utiliza joins con las tablas banco y usuario_banco para obtener la información completa.
 */


use Backend\dto\CriptoRed;
use Backend\dto\Usuario;

$UsuarioMandante = $UsuarioMandanteSite;
$Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

$rules = [];

array_push($rules, array("field" => "cripto_red.estado", "data" => "A", "op" => "eq"));
array_push($rules, array("field" => "usuario_banco.estado", "data" => "A", "op" => "eq"));
array_push($rules, array("field" => "usuario_banco.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

$filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

$joins[] = ['type' => 'INNER', 'table' => 'banco', 'on' => 'cripto_red.banco_id = banco.banco_id'];
$joins[] = ['type' => 'INNER', 'table' => 'usuario_banco', 'on' => 'banco.banco_id = usuario_banco.banco_id'];

$criptoRed = new CriptoRed();

$cuentas = $criptoRed->getCriptoRedCustom(" cripto_red.criptored_id, criptomoneda.nombre, red_blockchain.nombre, usuario_banco.cuenta, usuario_banco.usubanco_id", "cripto_red.criptored_id", "desc", 0, 1000, $filter, true, $joins);

/* decodifica un JSON y prepara un array para almacenar datos. */
$cuentas = json_decode((string) $cuentas);

$dataResponse = array();

foreach ($cuentas->data as $key => $value) {

    $arraybet = array();

    $arraybet["id"] = $value->{'usuario_banco.usubanco_id'};

    $cuenta = $value->{'usuario_banco.cuenta'};
    $cuentaAbreviada = (strlen($cuenta) > 8) ? $cuentaAbreviada = substr($cuenta, 0, 4) . '...' . substr($cuenta, -3) : $cuenta;
    
    $arraybet["wallet"] = $value->{'criptomoneda.nombre'} . ' - ' . $value->{'red_blockchain.nombre'} . ' -> ' . $cuentaAbreviada;

    array_push($dataResponse, $arraybet);
}

/* crea una respuesta JSON con información de solicitudes de retiro. */
$response = array();
$response["code"] = 0;
$response["rid"] = $json->rid;
$response["pos"] = 0;
$response["total_count"] = $cuentas->count[0]->{".count"};
$response["data"] = $dataResponse;
