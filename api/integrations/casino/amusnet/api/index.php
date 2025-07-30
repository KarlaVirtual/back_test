<?php


/**
 * Este archivo contiene un script para manejar las solicitudes de la API del casino 'Amusnet'.
 * Procesa operaciones como autenticación, retiros, depósitos y combinaciones de ambas.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    ninguna
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $_REQUEST            Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $URI                 Contiene la URI de la solicitud actual.
 * @var mixed $body                Almacena el cuerpo de la solicitud en formato de texto.
 * @var mixed $method              Variable para almacenar el método HTTP utilizado en la solicitud.
 * @var mixed $date                Fecha y hora actual en formato "Y-m-d H:i:s".
 * @var mixed $data                Objeto SimpleXML que contiene los datos del cuerpo de la solicitud.
 * @var mixed $log                 Variable utilizada para almacenar información de registro.
 * @var mixed $response            Almacena la respuesta generada por las operaciones realizadas.
 * @var mixed $UserName            Nombre de usuario enviado en la solicitud.
 * @var mixed $Password            Contraseña enviada en la solicitud.
 * @var mixed $PlayerId            Identificador del jugador.
 * @var mixed $AuthenticationToken Token de autenticación enviado en la solicitud.
 * @var mixed $PortalCode          Código del portal asociado a la solicitud.
 * @var mixed $SessionId           Identificador de la sesión.
 * @var mixed $GameId              Identificador del juego.
 * @var mixed $TransferId          Identificador de la transferencia.
 * @var mixed $GameNumber          Número del juego asociado a la solicitud.
 * @var mixed $Amount              Monto de la operación, convertido a la unidad correspondiente.
 * @var mixed $Currency            Moneda utilizada en la operación.
 * @var mixed $Reason              Razón o motivo de la operación.
 * @var mixed $PlatformType        Tipo de plataforma desde la cual se realiza la solicitud.
 * @var mixed $CampaignUniqueCode  Código único de la campaña de giros gratis.
 * @var mixed $Identification      Identificación asociada a los giros gratis.
 * @var mixed $Total               Total de giros gratis disponibles.
 * @var mixed $Remaining           Giros gratis restantes.
 * @var mixed $Freespin            Información sobre los giros gratis, si están presentes.
 * @var mixed $WinAmount           Monto de ganancia en caso de una operación combinada.
 * @var mixed $datos               Arreglo que contiene los datos procesados para las operaciones.
 * @var mixed $freespin            Indica si la operación incluye giros gratis.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Amusnet;

header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Access-Control-Allow-Headers, Access-Control-Allow-Credentials,Authentication, Origin, X-Requested-With, Content-Type');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS,PUT');
$_ENV["enabledConnectionGlobal"] = 1;

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

$URI = $_SERVER['REQUEST_URI'];
$body = trim(file_get_contents('php://input'));
$method = "";
$date = date("Y-m-d H:i:s");

if ($body != "") {
    header('Content-type: text/xml');
    $data = simplexml_load_string($body);
}

$log = "";
$log = $log . "\r\n";
$log = $log . "************ DATE: " . $date . "************" . " / " . time();
$log = $log . "\r\n";
$log = $log . "\r\n" . "--------------DATA REQUEST----------------" . "\r\n";
$log = $log . "\r\n";
$log = $log . (http_build_query($_REQUEST)) . "\r\n";
$log = $log . "\r\n";
$log = $log . trim(file_get_contents('php://input'));
$log = $log . "\r\n" . "---------------------------------" . "\r\n";
$log = time();

$URI = explode('/', $URI);
$URI = $URI[count($URI) - 1];
$URI = explode('?', $URI);
$URI = $URI[0];

if ($URI == 'Authenticate') {
    $UserName = strval($data->UserName);
    $Password = strval($data->Password);
    $PlayerId = strval($data->PlayerId);

    if ( ! empty($data->DefenceCode)) {
        $AuthenticationToken = strval($data->DefenceCode);
    } else {
        $AuthenticationToken = strval($data->AuthenticationToken);
    }

    $PortalCode = strval($data->PortalCode);
    $SessionId = strval($data->SessionId);
    $GameId = strval($data->GameId);

    $Amusnet = new Amusnet($AuthenticationToken, $PlayerId, $GameId);
    $response = ($Amusnet->Auth($UserName, $Password, $PortalCode));
} elseif ($URI == "Withdraw") {
    $UserName = strval($data->UserName);
    $Password = strval($data->Password);
    $TransferId = strval($data->TransferId);
    $GameId = strval($data->GameId);
    $GameNumber = strval($data->GameNumber);
    $SessionId = strval($data->SessionId);
    $Amount = $data->Amount;
    $Amount = $Amount / 100;
    $Currency = strval($data->Currency);
    $Reason = strval($data->Reason);
    $PortalCode = strval($data->PortalCode);
    $PlayerId = strval($data->PlayerId);
    $PlatformType = strval($data->PlatformType);
    $CampaignUniqueCode = strval($data->Freespin->CampaignUniqueCode);
    $Identification = strval($data->Freespin->Identification);
    $Total = strval($data->Freespin->Total);
    $Remaining = strval($data->Freespin->Remaining);
    $Freespin = isset($data->Freespin) ? (string)$data->Freespin : null;

    $Amusnet = new Amusnet("", $PlayerId, $GameId);

    $datos = array(
        "UserName" => (string)$UserName,
        "Password" => (string)$Password,
        "TransferId" => (string)$TransferId,
        "GameId" => (string)$GameId,
        "GameNumber" => (string)$GameNumber,
        "SessionId" => (string)$SessionId,
        "Amount" => (string)$Amount,
        "Currency" => (string)$Currency,
        "Reason" => (string)$Reason,
        "PortalCode" => (string)$PortalCode,
        "PlatformType" => (string)$PlatformType,
        "Freespin" => (string)$Freespin,
        "CampaignUniqueCode" => (string)$CampaignUniqueCode,
        "Identification" => (string)$Identification,
        "Total" => (string)$Total,
        "Remaining" => (string)$Remaining
    );

    $freespin = false;
    if ($Identification !== "") {
        $freespin = true;
        $Amount = 0;
    }

    $response = ($Amusnet->Debit($GameId, $GameNumber, $Amount, $TransferId, json_encode($datos), $freespin, $UserName, $Password));
} elseif ($URI == "Deposit") {
    $UserName = strval($data->UserName);
    $Password = strval($data->Password);
    $TransferId = strval($data->TransferId);
    $GameId = strval($data->GameId);
    $GameNumber = strval($data->GameNumber);
    $SessionId = strval($data->SessionId);
    $Amount = $data->Amount;
    $Amount = $Amount / 100;
    $Currency = strval($data->Currency);
    $Reason = strval($data->Reason);
    $PortalCode = strval($data->PortalCode);
    $PlayerId = strval($data->PlayerId);
    $PlatformType = strval($data->PlatformType);
    $CampaignUniqueCode = strval($data->Freespin->CampaignUniqueCode);
    $Identification = strval($data->Freespin->Identification);
    $Total = strval($data->Freespin->Total);
    $Remaining = strval($data->Freespin->Remaining);
    $Freespin = isset($data->Freespin) ? (string)$data->Freespin : null;

    $Amusnet = new Amusnet("", $PlayerId, $GameId);

    $datos = array(
        "UserName" => (string)$UserName,
        "Password" => (string)$Password,
        "TransferId" => (string)$TransferId,
        "GameId" => (string)$GameId,
        "GameNumber" => (string)$GameNumber,
        "SessionId" => (string)$SessionId,
        "Amount" => (string)$Amount,
        "Currency" => (string)$Currency,
        "Reason" => (string)$Reason,
        "PortalCode" => (string)$PortalCode,
        "PlatformType" => (string)$PlatformType,
        "Freespin" => (string)$Freespin,
        "CampaignUniqueCode" => (string)$CampaignUniqueCode,
        "Identification" => (string)$Identification,
        "Total" => (string)$Total,
        "Remaining" => (string)$Remaining
    );

    $freespin = false;
    if ($Identification !== "") {
        $freespin = true;
    }

    if ($Reason == "ROUND_END") {
        $response = ($Amusnet->Credit($GameId, $GameNumber, $Amount, $TransferId, json_encode($datos), true, $UserName, $Password));
    }

    if ($Reason == "JACKPOT_END") {
        $response = ($Amusnet->Debit($GameId, "JP" . $GameNumber, 0, "JP" . $TransferId, json_encode($datos), $freespin, $UserName, $Password));
        $response = ($Amusnet->Credit($GameId, "JP" . $GameNumber, $Amount, $TransferId, json_encode($datos), true, $UserName, $Password));
    }

    if ($Reason == "ROUND_CANCEL") {
        $response = ($Amusnet->Rollback($GameId, $GameNumber, $Amount, $TransferId, json_encode($datos), $UserName, $Password));
    }
} elseif ($URI == "WithdrawAndDeposit") {
    $UserName = strval($data->UserName);
    $Password = strval($data->Password);
    $TransferId = strval($data->TransferId);
    $GameId = strval($data->GameId);
    $GameNumber = strval($data->GameNumber);
    $SessionId = strval($data->SessionId);
    $Amount = $data->Amount;
    $Amount = $Amount / 100;
    $WinAmount = $data->WinAmount;
    $WinAmount = $WinAmount / 100;
    $Currency = strval($data->Currency);
    $Reason = strval($data->Reason);
    $PortalCode = strval($data->PortalCode);
    $PlayerId = strval($data->PlayerId);
    $PlatformType = strval($data->PlatformType);
    $CampaignUniqueCode = strval($data->Freespin->CampaignUniqueCode);
    $Identification = strval($data->Freespin->Identification);
    $Total = strval($data->Freespin->Total);
    $Remaining = strval($data->Freespin->Remaining);
    $Freespin = isset($data->Freespin) ? (string)$data->Freespin : null;

    $Amusnet = new Amusnet("", $PlayerId, $GameId);

    $datos = array(
        "UserName" => (string)$UserName,
        "Password" => (string)$Password,
        "TransferId" => (string)$TransferId,
        "GameId" => (string)$GameId,
        "GameNumber" => (string)$GameNumber,
        "SessionId" => (string)$SessionId,
        "Amount" => (string)$Amount,
        "WinAmount" => (string)$WinAmount,
        "Currency" => (string)$Currency,
        "Reason" => (string)$Reason,
        "PortalCode" => (string)$PortalCode,
        "PlatformType" => (string)$PlatformType,
        "Freespin" => (string)$Freespin,
        "CampaignUniqueCode" => (string)$CampaignUniqueCode,
        "Identification" => (string)$Identification,
        "Total" => (string)$Total,
        "Remaining" => (string)$Remaining
    );

    $freespin = false;
    if ($Identification !== "") {
        $freespin = true;
        $Amount = 0;
    }

    $response = ($Amusnet->WithdrawAndResponse($GameId, $GameNumber, $Amount, $TransferId, json_encode($datos), $freespin, $UserName, $Password));

    if ($Reason == "ROUND_END") {
        $response = ($Amusnet->DepositResponse($GameId, $GameNumber, $WinAmount, "Credit" . $TransferId, json_encode($datos), "", $UserName, $Password));
    }

    if ($Reason == "ROUND_CANCEL") {
        $response = ($Amusnet->Rollback($GameId, $GameNumber, $Amount, $TransferId, json_encode($datos), $UserName, $Password));
    }

    $datos = $data;
}

$log = "";
$log = "";
$log = $log . "/" . time();
$log = $log . "\r\n" . "-------------------------" . "\r\n";
$log = $log . ($response);
print_r($response);

