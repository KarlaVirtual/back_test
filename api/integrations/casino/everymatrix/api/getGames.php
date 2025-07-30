<?php

/**
 * Este archivo contiene un script para obtener y procesar información de juegos
 * desde una API externa, decodificar los datos y generar un informe de juegos.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var resource $curl     Recurso cURL utilizado para realizar solicitudes HTTP.
 * @var mixed    $response Variable que almacena la respuesta de la API externa.
 */

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://ngt-mrk-virtsof-dev-api.e-devel.eu/general/get_games_info/?json=%7B%22app_data%22:%22eyJBY2Nlc3NJZCI6IjQiLCJBY2Nlc3NDaGVja3N1bSI6ImRjODBjZWUyYmUyOWRjMTUxMDRhYWRkY2U3ZWNmY2ZmZTVkZjI2NmYifQ==%22,%20%22signature%22:%223b381fa55566ab8d2995ac0d460919bdecba7a08b336c356b9610b603c3058bf%22%7D',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
));

$response = curl_exec($curl);
$response = json_decode($response);
curl_close($curl);

$response = base64_decode($response->app_data);

header('Content-Type: text/HTML');

error_reporting(E_ALL);
ini_set('display_errors', 'OFF');

/**
 * Recursively processes an array to extract game information.
 *
 * This function traverses a nested array structure to find elements
 * containing 'en_title' and 'game_id' keys. It concatenates the game
 * information into a string format.
 *
 * @param array $array The input array to process.
 *
 * @return string A formatted string containing game IDs and titles.
 */
function returnGames($array)
{
    $games = '';
    foreach ($array as $key => $value) {
        if (array_key_exists('en_title', $value) && array_key_exists('game_id', $value)) {
            $ID = $value['game_id'];
            $title = $value['en_title'];

            $games .= "ID: {$ID} \n Title: {$title} \n\n";
        } elseif (is_array($value)) {
            $games .= returnGames($value);
        }
    }

    return $games;
}


print_r(returnGames(json_decode($response, true)));