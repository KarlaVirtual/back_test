<?php

/**
 * Este script se utiliza para analizar y probar la conexión con el sistema H2HBCP.
 * Además, ejecuta comandos relacionados con la integración de pagos.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $H2HBCP   Variable relacionada con el sistema H2HBCP, utilizado para la comunicación entre sistemas.
 * @var mixed $response Esta variable almacena la respuesta generada por una operación o petición.
 */

use Backend\integrations\payout\H2HBCPSERVICES;

require(__DIR__ . '../../../../../vendor/autoload.php');

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

$H2HBCP = new H2HBCPSERVICES();

$response = $H2HBCP->probarConexion();

print_r($response);
exit();

print_r(__DIR__ . "/index.php");

exec("php -f " . __DIR__ . "/index.php" . " > /dev/null &");
