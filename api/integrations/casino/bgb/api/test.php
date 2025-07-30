<?php

/**
 * Este archivo contiene un script para probar la integración con la API del casino 'bgb'.
 * Realiza operaciones como obtener la lista de juegos, verificar la existencia de un jugador,
 * crear un jugador, iniciar sesión y obtener información de un juego específico.
 *
 * @category   Integración
 * @package    CasinoAPI
 * @subpackage BGB
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-09
 */

error_reporting(E_ALL);
ini_set('display_errors', 'ON');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\BGBSERVICES;


/* Procesamos */

$BGBSERVICES = new BGBSERVICES();
print_r($BGBSERVICES->GetDynamicGamesList());

$seguir = true;
if ($BGBSERVICES->playerExists(1)) {
    print_r("EXITS");
} else {
    print_r(" NOT EXITS");
    $response = ($BGBSERVICES->createPlayer(1));

    if ($response->Message->Message == "OK") {
    } else {
        $seguir = false;
    }
}

if ($seguir) {
    $response = ($BGBSERVICES->loginPlayer(1));
    if ($response->Message->Message == "OK") {
        $token = $response->Response;

        print_r($BGBSERVICES->GetSingleGame(311, $token));
    } else {
    }
}


