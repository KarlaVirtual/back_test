<?php

/**
 * Index de la API de casino 'IESGAMESSERVICES'.
 *
 * Este archivo actúa como punto de entrada para manejar las solicitudes relacionadas
 * con la integración de servicios de casino. Procesa diferentes rutas y delega
 * las operaciones a los métodos correspondientes de la clase `IESGAMESSERVICES`.
 *
 * @package ninguno
 * @autor   Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @acceso  público
 * @fecha   18.10.17
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

header('Content-type: application/json');

$URI = $_SERVER['REQUEST_URI'];

$body = file_get_contents('php://input');

if ($body != "") {
    $data = json_decode($body);

    if (strpos($URI, "GetRooms") !== false) {
        /* Procesamos */
        $IESGAMESSERVICES = new \Backend\integrations\casino\IESGAMESSERVICES();
        $response = $IESGAMESSERVICES->GetRooms($data);
    }

    if (strpos($URI, "GetRaffles") !== false) {
        /* Procesamos */
        $IESGAMESSERVICES = new \Backend\integrations\casino\IESGAMESSERVICES();
        $response = $IESGAMESSERVICES->GetRaffles($data);
    }

    if (strpos($URI, "GetAllRaffles") !== false) {
        /* Procesamos */
        $IESGAMESSERVICES = new \Backend\integrations\casino\IESGAMESSERVICES();
        $response = $IESGAMESSERVICES->GetAllRaffles($data);
    }

    if (strpos($URI, "GetBasicRaffles") !== false) {
        /* Procesamos */
        $IESGAMESSERVICES = new \Backend\integrations\casino\IESGAMESSERVICES();
        $response = $IESGAMESSERVICES->GetBasicRaffles($data);
    }

    if (strpos($URI, "Historical") !== false) {
        /* Procesamos */
        $IESGAMESSERVICES = new \Backend\integrations\casino\IESGAMESSERVICES();
        $response = $IESGAMESSERVICES->Historical($data);
    }

    if (strpos($URI, "MyRaffles") !== false) {
        /* Procesamos */
        $IESGAMESSERVICES = new \Backend\integrations\casino\IESGAMESSERVICES();
        $response = $IESGAMESSERVICES->MyRaffles($data);
    }

    if (strpos($URI, "PlayerTransactions") !== false) {
        /* Procesamos */
        $IESGAMESSERVICES = new \Backend\integrations\casino\IESGAMESSERVICES();
        $response = $IESGAMESSERVICES->PlayerTransactions($data);
    }

    if (strpos($URI, "GetCompleteRafflesInfo") !== false) {
        /* Procesamos */
        $IESGAMESSERVICES = new \Backend\integrations\casino\IESGAMESSERVICES();
        $response = $IESGAMESSERVICES->GetCompleteRafflesInfo($data);
    }

    if (strpos($URI, "bet") !== false) {
        /* Procesamos */
        $IESGAMESSERVICES = new \Backend\integrations\casino\IESGAMESSERVICES();
        $response = $IESGAMESSERVICES->ExternalBet($data);
    }

    if (strpos($URI, "registrybet") !== false) {
        /* Procesamos */
        $IESGAMESSERVICES = new \Backend\integrations\casino\IESGAMESSERVICES();
        $response = $IESGAMESSERVICES->SecuencialExternalBet($data);
    }

    if (strpos($URI, "betdata") !== false) {
        /* Procesamos */
        $IESGAMESSERVICES = new \Backend\integrations\casino\IESGAMESSERVICES();
        $response = $IESGAMESSERVICES->QueryResultExternalBet($data);
    }

    if (strpos($URI, "operator") !== false) {

        /* Procesamos */
        $IESGAMESSERVICES = new \Backend\integrations\casino\IESGAMESSERVICES();
        $response = $IESGAMESSERVICES->QueryOperatorResultExternalBet($data);
    }

    print_r($response);
}
