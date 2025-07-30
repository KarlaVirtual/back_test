<?php
/**
 * Este archivo contiene un script para procesar y registrar datos de solicitudes
 * recibidas en la API del casino 'virtualg'.
 *
 * @category   Casino
 * @package    API
 * @subpackage Integrations
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-11
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed  $_REQUEST Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var string $log      Variable que almacena el registro de datos procesados, incluyendo solicitudes y entradas.
 */

$log = $log . "\r\n" . "-------------------------" . "\r\n";
$log = $log . json_encode($_REQUEST);
$log = $log . trim(file_get_contents('php://input'));