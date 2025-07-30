<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\Usuario;

/**
 * Obtiene información de un usuario administrativo por su ID.
 *
 * Este script procesa solicitudes para obtener información detallada de un usuario
 * administrativo basado en su identificador.
 *
 * @param int $_REQUEST["userId"] Identificador del usuario administrativo.
 *
 * @return array $response
 *   - HasError: boolean, indica si ocurrió un error.
 *   - AlertType: string, tipo de alerta (e.g., "success", "error").
 *   - AlertMessage: string, mensaje de alerta.
 *   - ModelErrors: array, lista de errores del modelo.
 *   - Data: array, información del usuario con los siguientes campos:
 *       - Id: int, identificador del usuario.
 *       - Name: string, nombre del usuario.
 *       - UserName: string, nombre de usuario.
 */

/* recibe datos JSON, decodifica y crea un objeto Usuario con userId. */
$params = file_get_contents('php://input');
$params = json_decode($params);

$id = $_REQUEST["userId"];

$Usuario = new Usuario($id);


/* Crea un arreglo asociativo con datos del usuario: ID, nombre y nombre de usuario. */
$final = [];

$final["Id"] = $Usuario->usuarioId;
$final["Name"] = $Usuario->nombre;
$final["UserName"] = $Usuario->login;
$final["Name"] = $Usuario->nombre;


/* establece una respuesta de éxito sin errores y con datos finales. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$response["Data"] = $final;