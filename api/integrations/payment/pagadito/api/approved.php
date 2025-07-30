<?php

/**
 * Este archivo maneja la integración de pagos con Pagadito.
 * Procesa la confirmación de pagos aprobados y realiza las acciones necesarias
 * para registrar la transacción en el sistema.
 *
 * @category   Integraciones
 * @package    API
 * @subpackage Pagos
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0
 * @since      2017-10-18
 */

/**
 * Configuración inicial del script:
 * - Desactiva la visualización de errores.
 * - Carga las dependencias necesarias.
 * - Configura el límite de memoria.
 * - Establece el tipo de contenido de la respuesta.
 */
ini_set('display_errors', 'OFF');
require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\payment\Pagadito;

ini_set('memory_limit', '-1');
header('Content-type: application/json; charset=utf-8');

/**
 * Variables globales utilizadas en el script:
 *
 * @var string $invoice      Número de factura recibido como argumento.
 * @var string $documento_id ID del documento asociado a la transacción.
 * @var float  $valor        Monto de la transacción.
 * @var string $data         Datos codificados en base64.
 */
$_REQUEST['test'] = '1';
$invoice = $argv[1];
$documento_id = $argv[2];
$valor = $argv[3];
$data = $argv[4];

if (isset($invoice)) {
    /**
     * Variables locales para el procesamiento de la transacción:
     *
     * @var string $result     Resultado inicial de la transacción (APROBADO).
     * @var string $usuario_id ID del usuario (vacío por defecto).
     * @var string $control    Código de control (vacío por defecto).
     */
    $result = "APROBADO";
    $usuario_id = "";
    $control = "";

    /**
     * Procesa la confirmación del pago utilizando la clase Pagadito.
     *
     * @param string $invoice      Número de factura.
     * @param string $usuario_id   ID del usuario.
     * @param string $documento_id ID del documento asociado.
     * @param float  $valor        Monto de la transacción.
     * @param string $control      Código de control.
     * @param string $result       Resultado de la transacción.
     */
    $Pagadito = new Pagadito($invoice, $usuario_id, $documento_id, $valor, $control, $result);

    /**
     * Confirma la transacción decodificando los datos recibidos.
     *
     * @param string $data Datos codificados en base64.
     */
    $Pagadito->confirmation(base64_decode($data));
}
