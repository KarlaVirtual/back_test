<?php

/**
 * Clase PagoEfectivo2
 *
 * Esta clase maneja la integración con el proveedor de pagos PagoEfectivo.
 * Proporciona métodos para procesar confirmaciones de transacciones y
 * realizar acciones específicas según el estado de las mismas.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-25
 */

namespace Backend\Integrations\payment;

use Backend\dto\TransaccionProducto;
use Ratchet\Wamp\Exception;

/**
 * Clase PagoEfectivo2
 *
 * Esta clase maneja la integración con el proveedor de pagos PagoEfectivo.
 * Proporciona métodos para procesar confirmaciones de transacciones y
 * realizar acciones específicas según el estado de las mismas.
 */
class PagoEfectivo2
{
    /**
     * Constructor de la clase PagoEfectivo2.
     * Inicializa la clase sin parámetros adicionales.
     */
    public function __construct()
    {
    }

    /**
     * Procesa la confirmación de una transacción recibida desde PagoEfectivo.
     *
     * @param object $data Objeto que contiene los datos de la transacción
     *                     enviados por el proveedor.
     *
     * @return void
     */
    public function confirmation($data)
    {
        // ID Transaccion en nuestro sistema
        $transaccion_id = intval($data->data->transactionCode);

        $OrdenID = intval($data->data->cip);

        // Estado del evento recibido
        $estado = ($data->eventType);

        // Tipo que genera el log (A: Automático, M: Manual)
        $tipo_genera = 'A';


        $t_value = '';
        try {
            // Valores que me trae el proveedor para auditoria
            $t_value = json_encode($data);
        } catch (Exception $e) {
            // Manejo de excepciones (vacío en este caso)
        }

        // Procesa el estado del evento
        switch ($estado) {
            case "cip.paid":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'A';

                // Comentario personalizado para el log
                $comentario = 'Aprobada por PagoEfectivo ';

                // Obtenemos la transacción
                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $TransaccionProducto->setAprobada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $OrdenID);

                break;
        }
    }

}
