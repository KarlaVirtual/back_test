<?php

/**
 * Archivo principal para la integración con la API de Elinmejorable.
 *
 * Este script maneja las solicitudes HTTP entrantes, procesa los datos
 * y realiza operaciones como obtener el balance, realizar transacciones
 * y ejecutar rollbacks. También registra logs para facilitar la depuración.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $log             Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $_SERVER         Variable superglobal que contiene información del servidor y entorno de ejecución.
 * @var mixed $body            Esta variable contiene el contenido del cuerpo de la solicitud HTTP.
 * @var mixed $URI             Esta variable contiene el URI de la petición actual.
 * @var mixed $URIRECURSO      Variable que almacena el identificador de un recurso en una URI.
 * @var mixed $URL             Variable que almacena una dirección URL.
 * @var mixed $sing            Esta variable se utiliza para almacenar y manipular la firma digital.
 * @var mixed $data            Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $playerId        Variable que almacena el identificador único de un jugador.
 * @var mixed $apikey          Variable que almacena una clave de API para autenticación.
 * @var mixed $Elinmejorable   Variable cuyo significado específico depende del contexto de uso.
 * @var mixed $response        Esta variable almacena la respuesta generada por una operación o petición.
 * @var mixed $description     Variable que almacena una descripción.
 * @var mixed $PlayerId        Variable que almacena el identificador único de un jugador.
 * @var mixed $round           Variable que almacena el identificador de una ronda de juego.
 * @var mixed $typeTransaction Variable que almacena el tipo de transacción.
 * @var mixed $gameId          Variable que almacena el identificador de un juego.
 * @var mixed $reference       Variable que almacena una referencia para una transacción o proceso.
 * @var mixed $amount          Variable que almacena un monto o cantidad.
 * @var mixed $Apikey          Variable que almacena una clave de API para autenticación (posible duplicado de apikey).
 * @var mixed $freespin        Variable que almacena información sobre giros gratis en un juego.
 * @var mixed $datos           Variable que almacena datos genéricos.
 * @var mixed $numberRandom    Variable que almacena un número aleatorio.
 */

ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Elinmejorable;

header('Content-Type: application/json');

$body = file_get_contents('php://input');

$URI = $_SERVER['REQUEST_URI'];
$URIRECURSO = explode("/", $URI);
$URL = $URIRECURSO[oldCount($URIRECURSO) - 1];

if ($body != "") {
    $data = json_decode($body);

    if (strpos($URL, "getBalance") !== false) {
        $playerId = $data->PlayerId;
        $apikey = $data->apikey;

        /* Procesamos */
        $Elinmejorable = new Elinmejorable($playerId);
        $response = ($Elinmejorable->getBalance());
    }

    if (strpos($URL, "transactions") !== false) {
        $description = $data->description;
        $PlayerId = $data->PlayerId;
        $round = $data->reference;
        $typeTransaction = $data->typeTransaction;
        $gameId = $data->gameId;
        $reference = $data->reference;
        $amount = $data->amount;
        $Apikey = $data->Apikey;

        $freespin = false;
        $datos = $data;
        $Elinmejorable = new Elinmejorable($PlayerId);
        if ($typeTransaction == "debito") {
            $response = ($Elinmejorable->Debit($gameId, $amount, $round, $reference . $PlayerId, json_encode($datos), $freespin));
        }

        if ($typeTransaction == "credito") {
            $numberRandom = date("Ym");
            $reference = "credit" . $reference . $PlayerId . $numberRandom;
            $response = $Elinmejorable->Credit($gameId, $amount, $round, $reference, json_encode($datos));
        }
    }

    if (strpos($URL, "rollback") !== false) {
        $description = $data->description;
        $PlayerId = $data->PlayerId;
        $round = $data->reference;
        $typeTransaction = $data->typeTransaction;
        $gameId = $data->gameId;
        $reference = $data->reference;
        $amount = $data->amount;

        $datos = $data;

        /* Procesamos */

        $Elinmejorable = new Elinmejorable($PlayerId);
        $response = $Elinmejorable->Rollback($amount, $round, $reference . $PlayerId, "", json_encode($datos));
    }

    print_r($response);
}
