<?php

/**
 * Este archivo se utiliza para obtener los juegos de casino desde la API de AmigoGaming
 * mediante una solicitud CURL.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Daniel Tamayo
 * @version    ninguna
 * @since      2025-05-09
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