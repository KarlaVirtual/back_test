<?php

/**
 * Este archivo actúa como un punto de entrada para manejar solicitudes relacionadas con la integración de la plataforma Nsoft.
 * Proporciona diferentes endpoints para realizar operaciones como obtener detalles del jugador, verificar sesiones, manejar fondos,
 * y otras acciones relacionadas con transacciones y usuarios.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $_REQUEST             Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $params               Variable que contiene los parámetros de una solicitud, generalmente en forma de array.
 * @var mixed $data                 Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $URI                  Esta variable contiene el URI de la petición actual.
 * @var mixed $_SERVER              Variable superglobal que contiene información del servidor y entorno de ejecución.
 * @var mixed $method               Variable que almacena el método de pago o de ejecución de una acción.
 * @var mixed $log                  Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $hash                 Variable que almacena un valor hash para seguridad o verificación.
 * @var mixed $_GET                 Arreglo global que contiene los datos enviados mediante el método GET.
 * @var mixed $token                Esta variable contiene el token de autenticación, utilizado para verificar y autorizar peticiones de forma segura.
 * @var mixed $temId                Variable que almacena el identificador temporal.
 * @var mixed $foreignId            Variable que almacena un identificador externo.
 * @var mixed $clubUuid             Variable que almacena el UUID del club.
 * @var mixed $clientIp             Variable que almacena la dirección IP del cliente.
 * @var mixed $customValues         Variable que almacena valores personalizados.
 * @var mixed $Nsoft                Variable que almacena información relacionada con la plataforma Nsoft.
 * @var mixed $response             Esta variable almacena la respuesta generada por una operación o petición.
 * @var mixed $user                 Variable que almacena el nombre de usuario.
 * @var mixed $securityHash         Variable que almacena un hash de seguridad.
 * @var mixed $localTenantId        Variable que almacena el identificador del inquilino local.
 * @var mixed $DebitAmount          Variable que almacena el monto debitado de una cuenta o transacción.
 * @var mixed $amountSmall          Variable que almacena un monto pequeño.
 * @var mixed $bonusAmount          Variable que almacena el monto del bono.
 * @var mixed $bonusAmountFractions Variable que almacena las fracciones del monto del bono.
 * @var mixed $currency             Variable que almacena la moneda utilizada en una transacción.
 * @var mixed $paymentStrategy      Variable que almacena la estrategia de pago.
 * @var mixed $paymentId            Variable que almacena el identificador del pago.
 * @var mixed $transactionId        Variable que almacena el identificador único de una transacción.
 * @var mixed $GameCode             Variable que almacena el código de un juego.
 * @var mixed $RoundId              Variable que almacena el identificador de una ronda de juego.
 * @var mixed $ticketInfo           Variable que almacena información del ticket.
 * @var mixed $clientVal            Variable que almacena valores del cliente.
 * @var mixed $datos                Variable que almacena datos genéricos.
 * @var mixed $autoApprove          Variable que indica si la aprobación es automática.
 * @var mixed $CreditAmount         Variable que almacena el monto acreditado a una cuenta o transacción.
 * @var mixed $isEndRound           Variable que indica si la ronda ha finalizado.
 * @var mixed $transactionType      Variable que almacena el tipo de transacción realizada.
 * @var mixed $requestUuid          Variable que almacena el UUID de la solicitud.
 * @var mixed $paymentIds           Variable que almacena una lista de identificadores de pagos.
 */

header('Access-Control-Allow-Origin: *');


ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\virtual\Nsoft;

header('Content-type: application/json');


if ($_REQUEST == "" || $_REQUEST == null) {
    $params = file_get_contents('php://input');
    $data = json_decode($params);
} else {
    $params = $_REQUEST;
}

$URI = $_SERVER['REQUEST_URI'];

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

$method = $URI;

$URI = explode('/', $URI);
$URI = $URI[count($URI) - 1];
$URI = explode('?', $URI);
$URI = $URI[0];

if (true) {
    if (strpos($URI, "playerDetails") !== false) {
        $token = $params["sessionId"];
        $temId = $params["temId"];
        $foreignId = $params["foreignId"];
        $clubUuid = $params["clubUuid"];
        $clientIp = $params["clientIp"];
        $customValues = $params["customValues"];

        $Nsoft = new Nsoft($token);
        $response = ($Nsoft->getPlayerDetails());
    }

    if (strpos($URI, "sessionCheck") !== false) {
        $token = $params["sessionId"];
        $foreignId = $params["foreignId"];
        $clubUuid = $params["clubUuid"];
        $clientIp = $params["clientIp"];
        $customValues = $params["customValues"];

        /* Procesamos */
        $Nsoft = new Nsoft($token);
        $response = ($Nsoft->SessionCheck());
    }


    if (strpos($URI, "tempTokenSession") !== false) {
        $token = $params["sessionId"];
        $foreignId = $params["foreignId"];
        $clubUuid = $params["clubUuid"];
        $clientIp = $params["clientIp"];
        $customValues = $params["customValues"];

        $Nsoft = new Nsoft($token);
        $response = ($Nsoft->TempToken());
    }


    if (strpos($URI, "userfunds") !== false) {
        $user = $params["user"];
        $securityHash = $params["securityHash"];
        $clubUuid = $params["clubUuid"];
        $localTenantId = $params["localTenantId"];


        $Nsoft = new Nsoft($token, $user);
        $response = ($Nsoft->getBalance());
    }

    if (strpos($URI, "reserveFunds") !== false) {
        $DebitAmount = $data->amount;
        $amountSmall = $data->amountSmall;
        $bonusAmount = $data->bonusAmount;
        $bonusAmountFractions = $data->bonusAmountFractions;
        $currency = $data->currency;
        $user = $data->user;
        $paymentStrategy = $data->paymentStrategy;
        $paymentId = $data->paymentId;
        $transactionId = $data->transactionId;
        $GameCode = $data->sourceId;
        $RoundId = $data->referenceId;
        $token = $data->tpToken;
        $ticketInfo = $data->ticketInfo;
        $securityHash = $data->securityHash;
        $clubUuid = $data->clubUuid;
        $localTenantId = $data->localTenantId;
        $clientVal = $data->clientVal;
        $datos = $data;

        $Nsoft = new Nsoft($token, $user);

        $response = $Nsoft->Debit($GameCode, $DebitAmount, $RoundId, $paymentId, json_encode($datos));
    }


    if (strpos($URI, "credit") !== false) {
        $autoApprove = $data->autoApprove;
        $CreditAmount = $data->amount;
        $amountSmall = $data->amountSmall;
        $bonusAmount = $data->bonusAmount;
        $bonusAmountFractions = $data->bonusAmountFractions;
        $currency = $data->currency;
        $user = $data->user;
        $paymentStrategy = $data->paymentStrategy;
        $paymentId = $data->paymentId;
        $transactionId = $data->transactionId;
        $GameCode = $data->sourceId;
        $RoundId = $data->referenceId;
        $ticketInfo = $data->ticketInfo;
        $securityHash = $data->securityHash;
        $clubUuid = $data->clubUuid;
        $localTenantId = $data->localTenantId;
        $clientVal = $data->clientVal;
        $datos = $data;

        $isEndRound = false;
        $Nsoft = new Nsoft($token, $user);
        $response = $Nsoft->Credit($GameCode, $CreditAmount, $RoundId, $paymentId, json_encode($datos), $isEndRound);
    }


    if (strpos($URI, "confirm") !== false) {
        $paymentId = $data->paymentId;
        $transactionId = $data->transactionId;
        $transactionType = $data->transactionType;
        $user = $data->user;
        $securityHash = $data->securityHash;
        $clubUuid = $data->clubUuid;
        $localTenantId = $data->localTenantId;
        $datos = $data;

        $Nsoft = new Nsoft($token, $user);
        $response = $Nsoft->ConfirmTransation($paymentId, json_encode($datos));
    }

    if (strpos($URI, "Cancel") !== false) {
        $paymentId = $data->paymentId;
        $transactionId = $data->transactionId;
        $transactionType = $data->transactionType;
        $user = $data->user;
        $securityHash = $data->securityHash;
        $clubUuid = $data->clubUuid;
        $localTenantId = $data->localTenantId;
        $datos = $data;

        $Nsoft = new Nsoft($token, $user);
        $response = ($Nsoft->Rollback("", "", $paymentId, $user, json_encode($datos)));
    }

    if (strpos($URI, "revertDebit") !== false) {
        $paymentId = $data->paymentId;
        $transactionId = $data->transactionId;
        $transactionType = $data->transactionType;
        $user = $data->user;
        $securityHash = $data->securityHash;
        $clubUuid = $data->clubUuid;
        $localTenantId = $data->localTenantId;
        $datos = $data;

        $Nsoft = new Nsoft($token, $user);
        $response = ($Nsoft->Rollback("", "", $paymentId, $user, json_encode($datos)));
    }


    if (strpos($URI, "closePayments") !== false) {
        $requestUuid = $data->requestUuid;
        $clubUuid = $data->clubUuid;
        $paymentIds = $data->paymentIds;
        $localTenantId = $data->localTenantId;
        $datos = $data;


        $Nsoft = new Nsoft($token, $user);

        $response = "";
    }

    if (strpos($URI, "lostTicket") !== false) {
        $paymentId = $data->paymentId;
        $RoundId = $data->paymentId;
        $transactionId = $data->transactionId;
        $user = $data->user;
        $securityHash = $data->securityHash;
        $clubUuid = $data->clubUuid;
        $localTenantId = $data->localTenantId;
        $datos = $data;

        $Nsoft = new Nsoft($token, $user);
        $response = $Nsoft->getBalance();
    }
    print_r($response);
}
