<?php
/**
 * Este archivo contiene un script para procesar y obtener información de juegos
 * desde una API externa, decodificar los datos y generar un informe con los
 * detalles de los juegos, como sus IDs y títulos.
 *
 * @category   API
 * @package    Integrations
 * @subpackage Casino
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-02-06
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
 * Procesa recursivamente un arreglo para extraer información de juegos.
 *
 * Esta función recorre una estructura de arreglo anidada para buscar y devolver
 * detalles de los juegos, como el ID del juego y el título. Si se encuentran las claves
 * requeridas ('en_title' y 'game_id'), la información se formatea y se agrega a la cadena
 * de resultados. Si se encuentra un arreglo anidado, la función se llama a sí misma
 * recursivamente.
 *
 * @param array $array El arreglo de entrada que contiene los datos de los juegos.
 *
 * @return string Una cadena formateada con los IDs y títulos de los juegos.
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