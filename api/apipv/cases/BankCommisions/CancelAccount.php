<?php

use Backend\dto\UsuarioBanco;
use Backend\mysql\UsuarioBancoMySqlDAO;
use Backend\dto\UsuarioMandante;
use Backend\dto\Usuario;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Cancela una cuenta bancaria de usuario basándose en los datos proporcionados en la solicitud.
 *
 * @param string $params JSON codificado que contiene los datos de la solicitud, incluyendo:
 * @param int $params->Id Identificador de la cuenta bancaria a cancelar.
 *
 * @return array $response Contiene los datos de la respuesta, incluyendo:
 *                         - HasError (bool): Indica si ocurrió un error.
 *                         - AlertType (string): Tipo de alerta ('success' en caso de éxito).
 *                         - AlertMessage (string): Mensaje de alerta.
 *                         - ModelErrors (array): Lista de errores del modelo.
 *                         - data (array): Datos adicionales de la respuesta.
 */


/* Se obtiene y decodifica un JSON para inicializar un objeto UsuarioBanco con un ID. */
$params = file_get_contents('php://input');
$params = json_decode($params);


$Id = $params->Id;


$UsuarioBanco = new UsuarioBanco($Id);

/* Actualiza el estado de un usuario en la base de datos a 'I' (inactivo). */
$UsuarioBanco->estado = 'I';


$UsuarioBancoMySqlDAO = new UsuarioBancoMySqlDAO();
$UsuarioBancoMySqlDAO->update($UsuarioBanco);
$UsuarioBancoMySqlDAO->getTransaction()->commit();


/* Estructura de respuesta para manejar éxito en una operación, sin errores. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];
$response["data"] = [];


?>
