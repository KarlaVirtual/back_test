<?php

/**
 * Este archivo contiene un script para obtener juegos de la API de casino 'imoon'
 * mediante una petición CURL y procesar la respuesta obtenida.
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
 * @var resource $curl      Recurso CURL inicializado para realizar la petición.
 * @var mixed    $response  Variable que almacena la respuesta de la API, decodificada desde JSON.
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
$response=json_decode($response);
curl_close($curl);

print_r(base64_decode($response->app_data));