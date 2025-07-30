<?php
/**
 * Este archivo contiene un script para cancelar giros gratis en la API de casino 'Softswiss'
 * mediante una petición CURL, utilizando un identificador de casino y un identificador de emisión.
 *
 * @category   API
 * @package    Integrations
 * @subpackage Casino
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-11
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var array  $data      Arreglo que contiene los datos necesarios para la petición, como el ID del casino y el ID de emisión.
 * @var string $AutchSign Firma generada mediante HMAC-SHA256 para autenticar la petición.
 * @var mixed  $curl      Recurso CURL inicializado para realizar la petición HTTP.
 * @var mixed  $response  Respuesta obtenida de la API tras ejecutar la petición CURL.
 */

$data = array();
$data['casino_id'] = 'virtualsoft-stg';
$data['issue_id'] = '24611_10380_acceptance:test_USD';

$AutchSign = hash_hmac('sha256', json_encode($data), 'cux3727d1vxudma4');


$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://casino.int.a8r.games/freespins/cancel',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => json_encode($data),
    CURLOPT_HTTPHEADER => array(
        'X-REQUEST-SIGN: ' . $AutchSign,
        'Content-Type: application/json'
    ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
