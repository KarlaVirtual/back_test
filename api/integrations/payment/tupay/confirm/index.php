<?php
/**
 * Endpoint de confirmación para transacciones de TuPay
 *
 * - Recibe y procesa notificaciones de cambio de estado de pagos
 * - Gestiona datos provenientes tanto de POST body como de query parameters
 * - Registra logs detallados con timestamp para auditoría
 * - Valída la estructura básica de los datos recibidos
 * - Deriva el procesamiento a la clase Tupay para confirmaciones
 *
 * @category API
 * @package servicios\pagos\
 * @author Karla Ramirez <karla.ramirez@virtualsoft.tech>
 * @versión 1.0
 * @since 01/04/2025
 */



// Habilitar modo debug con clave de seguridad
if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\payment\Tupay;

// Captura de datos de entrada (POST body o query parameters)
$data = json_encode($_REQUEST);

if ($data == "[]") {
    $data = file_get_contents('php://input');
}

// Configuración del sistema de logging
$logContent = "/" . time() . "\r\n" .
    date("Y-m-d H:i:s") . "-------------------------\r\n" .
    json_encode($_REQUEST) .
    trim(file_get_contents('php://input'));

$data = json_decode($data);

// Procesamiento de la confirmación
if (isset($data)) {
    $confirm = ($data);

    // Extracción de parámetros estándar
    $invoice = $confirm->customId;         // ID de la orden
    $result = $confirm->status;         // Estado de la transacción
    $documento_id = $confirm->reference; // Referencia interna
    $valor = $confirm->amount;          // Monto de la transacción
    $UserId = $confirm->userId;

    $Tupay = new Tupay($invoice, $UserId, $documento_id, $valor, "", $result);
    $Tupay->confirmation(json_encode($data));
}