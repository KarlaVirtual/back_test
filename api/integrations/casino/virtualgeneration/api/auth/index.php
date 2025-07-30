<?php
/**
 * Este archivo contiene un script para procesar solicitudes de autenticación
 * en la API de casino 'virtualgeneration'.
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
 * @var string $URI               URI de la solicitud actual.
 * @var string $body              Cuerpo de la solicitud HTTP recibido en formato JSON.
 * @var object $data              Objeto decodificado del cuerpo de la solicitud.
 * @var string $Username          Nombre de usuario proporcionado en la solicitud.
 * @var string $FirstName         Primer nombre del usuario.
 * @var string $LastName          Apellido del usuario.
 * @var string $Town              Ciudad del usuario.
 * @var string $State             Estado o región del usuario.
 * @var string $Country           País del usuario.
 * @var string $BirthDate         Fecha de nacimiento del usuario.
 * @var string $Gender            Género del usuario.
 * @var string $ZipCode           Código postal del usuario.
 * @var string $SessionId         Identificador de sesión proporcionado.
 * @var string $CurrencyISoCode   Código ISO de la moneda utilizada.
 * @var object $VirtualGeneration Instancia de la clase VirtualGeneration.
 * @var string $response          Respuesta generada por el método de autenticación.
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








