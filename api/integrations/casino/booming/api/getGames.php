<?php

/**
 * Este archivo contiene un script para obtener juegos de la API de casino 'booming'.
 * utilizando una petición CURL.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var string   $data   Variable que almacena los datos enviados en la petición CURL.
 * @var resource $ch     Recurso CURL inicializado para realizar la petición.
 * @var mixed    $result Variable que almacena el resultado de la ejecución de la petición CURL.
 */

$data = '';

$ch = curl_init("https://api-dk2.pragmaticplay.net/IntegrationService/v3/http/CasinoGameAPI/getCasinoGames?secureLogin=drb_doradobet&hash=070c0f1beb050325017dd0b5ba32439c");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 300);
curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
$result = (curl_exec($ch));
print_r("TEST");
print_r($result);