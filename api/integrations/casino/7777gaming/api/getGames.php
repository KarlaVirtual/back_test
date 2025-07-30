<?php
/**
 * Este archivo contiene un script para obtener información de juegos desde la API de casino '7777Gaming'
 * utilizando una solicitud CURL. El script procesa la respuesta y extrae los datos relevantes de los juegos.
 *
 * @category   API
 * @package    Integrations
 * @subpackage Casino
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var resource $curl     Recurso de CURL utilizado para realizar la solicitud a la API.
 * @var mixed    $response Respuesta obtenida de la API, procesada y decodificada.
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
 * Función recursiva para procesar un arreglo de datos de juegos y devolver una lista de juegos con su ID y título.
 *
 * @param array $array Arreglo de datos de juegos.
 *
 * @return string Lista de juegos con su ID y título en formato de texto.
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