<?php

/**
 * Este archivo contiene un script para procesar y confirmar datos de usuarios
 * basados en identificadores como DNI o userId, utilizando una base de datos MySQL.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-11
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $params   Contiene los datos de entrada en formato JSON enviados a través del cuerpo de la solicitud.
 * @var mixed $data     Objeto decodificado desde el JSON de entrada, que contiene los parámetros enviados por el cliente.
 * @var mixed $registro Instancia de la clase RegistroMySqlDAO para interactuar con la base de datos.
 * @var mixed $datos    Almacena los resultados obtenidos de las consultas a la base de datos.
 * @var mixed $response Array que contiene la respuesta generada por el script, en formato JSON.
 */

use Exception;
use Backend\dto\Usuario;
use Backend\mysql\RegistroMySqlDAO;

error_reporting(E_ALL);
ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../../vendor/autoload.php');

// Permitir solicitudes desde cualquier origen
header("Access-Control-Allow-Origin: *");
// Permitir solicitudes con los métodos GET, POST, OPTIONS
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
// Permitir solicitudes con los encabezados especificados
header("Access-Control-Allow-Headers: Content-Type");
// Permitir cookies de terceros
header("Access-Control-Allow-Credentials: true");

header('Content-type: application/json');

$params = file_get_contents('php://input');

$data = json_decode($params);

try {
    //Obtenemos el usuario con el usuarioID o DNI
    $registro = new RegistroMySqlDAO();
    $datos = [];
    if (trim($data->dni) != '') {
        $datos = $registro->queryByCedula($data->dni);
        if (trim($data->userId) != '') {
            $datos = $registro->queryByUsuarioId($data->userId);
        }
    } elseif (trim($data->userId) != '') {
        $datos = $registro->queryByUsuarioId($data->userId);
    }


    if (count($datos) == 0) {
        throw new Exception("Usuario no encontrado", 2000);
    }

    $response = array(
        "DNI" => $datos[0]->cedula,
        "USERID" => $datos[0]->usuarioId,
        "status" => 'OK'
    );
} catch (Exception $e) {
    $response = array(
        "DNI" => '',
        "USERID" => '',
        "status" => $e->getMessage()
    );
}


echo json_encode($response);