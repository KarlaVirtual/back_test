<?php

/**
 * Este archivo maneja la autenticación para la integración de Virtual Generation.
 * Procesa solicitudes HTTP, decodifica datos JSON y utiliza la clase VirtualGeneration
 * para realizar la autenticación.
 *
 * @category Seguridad
 * @package  Auth
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $URI               Esta variable contiene el URI de la petición actual.
 * @var mixed $_SERVER           Variable superglobal que contiene información del servidor y entorno de ejecución.
 * @var mixed $body              Esta variable contiene el contenido del cuerpo de la solicitud HTTP.
 * @var mixed $data              Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $Username          Variable que almacena el nombre de usuario de un sistema.
 * @var mixed $FirstName         Variable que almacena el primer nombre de una persona.
 * @var mixed $LastName          Variable que almacena el primer apellido de una persona.
 * @var mixed $Town              Variable que almacena la ciudad o localidad.
 * @var mixed $State             Variable que almacena el estado general de un proceso o entidad.
 * @var mixed $Country           Esta variable indica la cantidad total de elementos o registros, útil para iteraciones o validaciones.
 * @var mixed $BirthDate         Variable que almacena la fecha de nacimiento.
 * @var mixed $Gender            Variable que almacena el género.
 * @var mixed $ZipCode           Variable que almacena el código postal.
 * @var mixed $SessionId         Variable que almacena el identificador de sesión.
 * @var mixed $CurrencyISoCode   Variable que almacena el código ISO de la moneda.
 * @var mixed $VirtualGeneration Variable que almacena información sobre generación virtual.
 * @var mixed $response          Esta variable almacena la respuesta generada por una operación o petición.
 */

ini_set('display_errors', 'off');

require(__DIR__ . '../../../../../../vendor/autoload.php');

use Backend\integrations\casino\VirtualGeneration;

$URI = $_SERVER['REQUEST_URI'];
$body = file_get_contents('php://input');
if ($body != "") {
    $data = json_decode($body);

    $Username = $data->Username;
    $FirstName = $data->FirstName;
    $LastName = $data->LastName;
    $Town = $data->Town;
    $State = $data->State;
    $Country = $data->Country;
    $BirthDate = $data->BirthDate;
    $Gender = $data->Gender;
    $ZipCode = $data->ZipCode;
    $SessionId = $data->SessionId;
    $CurrencyISoCode = $data->CurrencyISoCode;


    /* Procesamos */


    $VirtualGeneration = new VirtualGeneration("", "");

    $response = $VirtualGeneration->Auth();

    syslog(LOG_WARNING, "AUTH Virtual Generation RESPONSE:" . $response);

    print $response;
}








