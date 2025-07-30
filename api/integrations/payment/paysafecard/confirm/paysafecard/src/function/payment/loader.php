<?php

/**
 * Este archivo contiene las inclusiones necesarias para manejar
 * las operaciones de pago con Paysafecard, incluyendo la ejecución,
 * cancelación y validación de pagos.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones\Paysafecard
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */


include_once __DIR__ . '/payment.php';
include_once __DIR__ . '/payment_execute.php';
include_once __DIR__ . '/payment_cancel.php';
include_once __DIR__ . '/validation/validate.php';