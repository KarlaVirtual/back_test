<?php
/**
 * Este script genera una respuesta con información de un usuario administrador asociado a un socio.
 * 
 * @param array $params No se utiliza en este script, pero puede incluir parámetros de entrada en el futuro.
 * @return array $response Contiene los siguientes campos:
 * - HasError (bool): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta (por ejemplo, "success").
 * - AlertMessage (string): Mensaje de alerta.
 * - ModelErrors (array): Lista de errores del modelo.
 * - Data (array): Información del usuario administrador, incluyendo:
 *   - Id (int): Identificador del usuario.
 *   - Name (string): Nombre del usuario.
 *   - Adress (null|string): Dirección del usuario.
 *   - AgentId (null|int): Identificador del agente.
 *   - CashDeskId (null|int): Identificador de la caja.
 *   - CashDeskName (null|string): Nombre de la caja.
 *   - CreatedLocalDate (string): Fecha de creación en formato local.
 *   - EMail (string): Correo electrónico del usuario.
 *   - FirstName (string): Primer nombre del usuario.
 *   - Hired (string): Fecha de contratación.
 *   - IsAgent (bool): Indica si es un agente.
 *   - IsGiven (bool): Indica si se ha otorgado algo.
 *   - IsQRCodeSent (bool): Indica si se envió un código QR.
 *   - IsSuspended (bool): Indica si el usuario está suspendido.
 *   - IsTwoFactorEnabled (bool): Indica si tiene habilitada la autenticación de dos factores.
 *   - LastName (string): Apellido del usuario.
 *   - PartnerId (int): Identificador del socio.
 *   - Password (null|string): Contraseña del usuario.
 *   - UserName (string): Nombre de usuario.
 */

/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/


/* Configura una respuesta sin errores, indicando éxito y sin mensajes de alerta. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

$final = [];


/* Se crea un arreglo `$array` con información del usuario Daniel y campos vacíos. */
$array = [];

$array["Id"] = 56575;
$array["Name"] = "Daniel";
$array["Adress"] = null;
$array["AgentId"] = null;

/* inicializa un arreglo con datos de un empleado y fechas relevantes. */
$array["CashDeskId"] = null;
$array["CashDeskName"] = null;
$array["CreatedLocalDate"] = "2018-01-13T17:03:13.024";
$array["EMail"] = "danielftg@hotmail.com";
$array["FirstName"] = "Daniel";
$array["Hired"] = "0001-01-01T00:00:00";

/* Se inicializan variables booleanas y un apellido en un arreglo. */
$array["IsAgent"] = false;
$array["IsGiven"] = false;
$array["IsQRCodeSent"] = false;
$array["IsSuspended"] = false;
$array["IsTwoFactorEnabled"] = false;
$array["LastName"] = "Tqammaa";

/* Se crea un array con datos de usuario y se almacena en otra variable. */
$array["PartnerId"] = 123213213123;
$array["Password"] = null;
$array["UserName"] = "danielftg@hotmail.com";

array_push($final, $array);


$response["Data"] = $array;
