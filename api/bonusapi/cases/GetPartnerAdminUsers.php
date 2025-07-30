<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

/**
 * Obtiene una lista de usuarios administrativos de socios.
 *
 * Este script genera una lista de usuarios administrativos con información básica,
 * incluyendo su correo electrónico, nombre y estado.
 *
 * @return array $response
 *   - HasError: boolean, indica si ocurrió un error.
 *   - AlertType: string, tipo de alerta (e.g., "success", "error").
 *   - AlertMessage: string, mensaje de alerta.
 *   - ModelErrors: array, lista de errores del modelo.
 *   - Data: array, lista de usuarios administrativos con los siguientes campos:
 *       - Id: int, identificador del usuario.
 *       - Name: string, nombre del usuario.
 *       - Adress: string|null, dirección del usuario.
 *       - AgentId: int|null, identificador del agente.
 *       - CashDeskId: int|null, identificador del escritorio de caja.
 *       - CashDeskName: string|null, nombre del escritorio de caja.
 *       - CreatedLocalDate: string, fecha de creación.
 *       - EMail: string, correo electrónico del usuario.
 *       - FirstName: string, primer nombre del usuario.
 *       - Hired: string, fecha de contratación.
 *       - IsAgent: boolean, indica si el usuario es un agente.
 *       - IsGiven: boolean, indica si el usuario tiene permisos asignados.
 *       - IsQRCodeSent: boolean, indica si se envió un código QR.
 *       - IsSuspended: boolean, indica si el usuario está suspendido.
 *       - IsTwoFactorEnabled: boolean, indica si el usuario tiene habilitada la autenticación de dos factores.
 *       - LastName: string, apellido del usuario.
 *       - PartnerId: int, identificador del socio.
 *       - Password: string|null, contraseña del usuario.
 *       - UserName: string, nombre de usuario.
 */

/* inicializa un array de respuesta sin errores y lista vacía de errores. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$final = [];


/* Se crea un arreglo con datos de un usuario, incluyendo ID y nombre. */
$array = [];

$array["Id"] = 56575;
$array["Name"] = "Daniel";
$array["Adress"] = null;
$array["AgentId"] = null;

/* define un arreglo asociativo con información sobre un empleado y su escritorio. */
$array["CashDeskId"] = null;
$array["CashDeskName"] = null;
$array["CreatedLocalDate"] = "2018-01-13T17:03:13.024";
$array["EMail"] = "danielftg@hotmail.com";
$array["FirstName"] = "Daniel";
$array["Hired"] = "0001-01-01T00:00:00";

/* Se definen variables booleanas y un apellido en un arreglo asociativo. */
$array["IsAgent"] = false;
$array["IsGiven"] = false;
$array["IsQRCodeSent"] = false;
$array["IsSuspended"] = false;
$array["IsTwoFactorEnabled"] = false;
$array["LastName"] = "Tqammaa";

/* Crea un arreglo con datos y lo agrega a una respuesta final. */
$array["PartnerId"] = 123213213123;
$array["Password"] = null;
$array["UserName"] = "danielftg@hotmail.com";

array_push($final, $array);


$response["Data"] = $final;