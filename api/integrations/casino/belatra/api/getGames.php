<?php

/**
 * Este archivo contiene un script para obtener juegos de casino desde una API externa
 * utilizando una solicitud CURL.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    ninguna
 * @since      2025-05-09
 */

/**
 * Configuración y ejecución de una solicitud CURL para obtener juegos de casino.
 *
 * @var resource $ch     Recurso CURL inicializado para realizar la solicitud.
 * @var string   $result Resultado de la ejecución de la solicitud CURL.
 * @var array    $data   Datos enviados en el cuerpo de la solicitud POST.
 */

$ch = curl_init("https://api-br0.pragmaticplay.net/IntegrationService/v3/http/CasinoGameAPI/getCasinoGames?secureLogin=drb_doradobet&hash=070c0f1beb050325017dd0b5ba32439c");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 300);
curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
$result = (curl_exec($ch));
print_r("TEST");
print_r($result);