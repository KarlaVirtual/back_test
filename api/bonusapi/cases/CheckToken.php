<?php
/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/

use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioToken;

/**
 * bonusapi/cases/CheckToken
 *
 * Obtener información del usuario autenticado
 *
 * Este recurso obtiene la información del usuario autenticado a partir del token de autenticación enviado en los encabezados.
 * Retorna detalles del usuario, su configuración y la lista de permisos asociados.
 *
 * @param string $headers ['authentication'] : Token de autenticación enviado en los encabezados de la solicitud.
 *
 * @returns object El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error en la operación.
 *  - *AlertType* (string): Especifica el tipo de alerta que se mostrará en la vista.
 *  - *AlertMessage* (string): Contiene el mensaje que se mostrará en la vista.
 *  - *ModelErrors* (array): Retorna un array vacío en caso de éxito o con los errores detectados.
 *  - *Result* (array): Contiene los datos del usuario autenticado, estructurados de la siguiente manera:
 *      - *UserName* (string): Identificador del usuario mandante.
 *      - *UserId* (string): Identificador del usuario mandante.
 *      - *FirstName* (string): Nombres del usuario.
 *      - *Settings* (array): Configuración del usuario, con los siguientes atributos:
 *          - *Language* (string): Idioma preferido del usuario.
 *          - *ReportCurrency* (string): Moneda utilizada en los reportes.
 *      - *PermissionList* (array): Lista de permisos asociados al usuario.
 *
 *
 * @throws Exception Si el token de autenticación no es válido o hay un error en la obtención de datos del usuario.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* obtiene encabezados HTTP para crear instancias de usuario. */
$headers = getallheaders();
$UsuarioToken = new UsuarioToken($headers['authentication'], '0');

$UsuarioMandante = new UsuarioMandante($UsuarioToken->getUsuarioId(), "");


$data["UserName"] = $UsuarioMandante->usumandanteId;

/* Asigna valores de un objeto a un array, incluyendo configuración de idioma y moneda. */
$data["UserId"] = $UsuarioMandante->usumandanteId;
$data["FirstName"] = $UsuarioMandante->nombres;
$data["Settings"] = array(
    "Language" => "en",
    "ReportCurrency" => "USD"
);

/* Define permisos y una respuesta inicial sin errores ni mensajes de alerta. */
$data["PermissionList"] = array("BEManageCasinoBonus", "BEManageSportBonus", "BEViewCasinoBonus", "BEViewSportBonus", "EditBonus", "ManageBonus", "ManageClientBonuses", "ViewBonus");

$response["HasError"] = false;
$response["AlertType"] = "danger";
$response["AlertMessage"] = '';
$response["ModelErrors"] = [];

/* Asigna el valor de $data a la clave "Result" del array $response. */
$response["Result"] = $data;