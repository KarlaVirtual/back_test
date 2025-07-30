<?php

/**
 * Prueba api
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */

use \PDO;
use Backend\dto\Usuario;
use Backend\dto\Subproveedor;
use Backend\dto\SubproveedorMandantePais;

require_once __DIR__ . '../../vendor/autoload.php';

sleep(10);

$user = $argv[1];

//exec("php -f /home/home2/backend/api/src/imports/Slack/message.php 'BONO BR " . $user . "' '#virtualsoft-cron' > /dev/null & ");

$Usuario = new Usuario($user);
$Registro = new \Backend\dto\Registro('', $user);
$Mandante = new \Backend\dto\Mandante($Usuario->mandante);

$moneda_default = $Usuario->moneda;
$Pais = new \Backend\dto\Pais($Usuario->paisId);

$pathPartner = $Mandante->pathItainment;
$pathFixed = $Pais->codigoPath;
$usermoneda = $moneda_default;
$userpath = $pathFixed;

$Subproveedor = new Subproveedor("", "ITN");
$SubproveedorMandantePais = new SubproveedorMandantePais('', $Subproveedor->subproveedorId, $Usuario->mandante, $Usuario->paisId);
$Credentials = json_decode($SubproveedorMandantePais->getCredentials());
$urlAltenar = $Credentials->URL2;
$walletCode = $Credentials->WALLET_CODE;

$pathFixed = '2:Web ' . $usermoneda . ',' . $userpath;

if ($Mandante->mandante != '') {
    if (is_numeric($Mandante->mandante)) {
        if (intval($Mandante->mandante) >  2) {

            $pathFixed = '2:Web' . $Mandante->mandante . ' ' . $usermoneda . ',' . $Mandante->mandante . $userpath;
            if (intval($Mandante->mandante) ==  9) {

                $pathFixed = '2:Web' . $Mandante->mandante . ' ' . $usermoneda . ',' . $Mandante->mandante . 'W' . $userpath;
            }
        }
    }
}

$IdUsuarioAltenar = $Usuario->usuarioId;
if ((intval($Usuario->usuarioId) > 73758) || (in_array(intval($Usuario->usuarioId), array(70363, 72098, 67393, 73497, 57196)))) {
    $IdUsuarioAltenar = $Usuario->usuarioId . "U";
}

$dataD = array(
    "ExtUser" => array(
        "LoginName" => $Usuario->nombre,
        "Currency" => $moneda_default,
        "Country" => $Pais->iso,
        "ExternalUserId" => $IdUsuarioAltenar,
        "AffiliationPath" => $Usuario->getAffiliationPathAltenar(),
        "UserCode" => "3",
        "FirstName" => $Registro->nombre1,
        "LastName" => $Registro->apellido1,
        "UserBalance" => "0"
    ),
    "WalletCode" => $walletCode
);

$dataD = json_encode($dataD);

$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => $urlAltenar . '/api/Bonus/CreateUser/json',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 3000,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => $dataD,
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json'
    ),
));

$time = time();

print_r($dataD);
$response2 = curl_exec($curl);
//exec("php -f /home/home2/backend/api/src/imports/Slack/message.php 'BONOR BR " . $user ." ".$response2. "' '#virtualsoft-cron' > /dev/null & ");

print_r($response2);

if ($response2 == '') {
    sleep(10);

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $urlAltenar . '/api/Bonus/CreateUser/json',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 3000,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $dataD,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
    ));

    print_r($dataD);
    $response2 = curl_exec($curl);
    //exec("php -f /home/home2/backend/api/src/imports/Slack/message.php 'BONOR2 BR " . $user ." ".$response2. "' '#virtualsoft-cron' > /dev/null & ");

    if ($response2 == '') {
        sleep(5);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $urlAltenar . '/api/Bonus/CreateUser/json',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 3000,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $dataD,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        print_r($dataD);
        $response2 = curl_exec($curl);
        //exec("php -f /home/home2/backend/api/src/imports/Slack/message.php 'BONOR3 BR " . $user ." ".$response2. "' '#virtualsoft-cron' > /dev/null & ");
    }
}

curl_close($curl);

sleep(5);

$dataD = array(
    "ExtUserId" => $user . 'U',
    "WalletCode" => "190582",
    "BonusCode" => "BONUSCAD5",
    "Deposit" => "500"
);

print_r($dataD);

$dataD = json_encode($dataD);

$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => $urlAltenar . '/api/Bonus/CreateBonusByCode/json',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 3000,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => $dataD,
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json'
    ),
));

$response2 = curl_exec($curl);

print_r($response2);
//exec("php -f /home/home2/backend/api/src/imports/Slack/message.php 'BONOR BR " . $user ." ".$response2. "' '#virtualsoft-cron' > /dev/null & ");
