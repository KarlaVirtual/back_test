<?php

/**
 * Clase CoinsPaid para manejar integraciones de pagos con CoinsPaid.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-23
 */

namespace Backend\integrations\payment;

use Backend\dto\TransaccionProducto;

/**
 * Clase para manejar la integración con el proveedor de pagos CoinsPaid.
 */
class CoinsPaid
{

    /**
     * Identificador de la factura o transacción.
     *
     * @var mixed
     */
    var $invoice;

    /**
     * Identificador del usuario asociado a la transacción.
     *
     * @var mixed
     */
    var $usuario_id;

    /**
     * Identificador del documento relacionado con la transacción.
     *
     * @var mixed
     */
    var $documento_id;

    /**
     * Valor monetario de la transacción.
     *
     * @var mixed
     */
    var $valor;

    /**
     * Código de control para la transacción.
     *
     * @var mixed
     */
    var $control;

    /**
     * Resultado del estado de la transacción.
     *
     * @var mixed
     */
    var $result;

    /**
     * Constructor de la clase CoinsPaid.
     *
     * @param mixed $invoice      Identificador de la factura o transacción.
     * @param mixed $usuario_id   Identificador del usuario asociado a la transacción.
     * @param mixed $documento_id Identificador del documento relacionado con la transacción.
     * @param mixed $valor        Valor monetario de la transacción.
     * @param mixed $control      Código de control para la transacción.
     * @param mixed $result       Resultado del estado de la transacción.
     */
    public function __construct($invoice, $usuario_id, $documento_id, $valor, $control, $result)
    {
        $this->invoice = $invoice;
        $this->usuario_id = $usuario_id;
        $this->documento_id = $documento_id;
        $this->valor = $valor;
        $this->control = $control;
        $this->result = $result;
    }

    /**
     * Maneja la confirmación de una transacción según el estado proporcionado por CoinsPaid.
     *
     * @param mixed $t_value Datos adicionales proporcionados por el proveedor para auditoría.
     *
     * @return mixed Resultado de la operación según el estado de la transacción.
     */
    public function confirmation($t_value)
    {
        // Convertimos a las variables de nuestro sistema

        // ID Transaccion en nuestro sistema
        $transaccion_id = $this->invoice;

        // Tipo que genera el log (A: Automatico, M: Manual)
        $tipo_genera = 'A';


        // Valores que me trae el proveedor para auditoria
        $t_value = json_encode($t_value);

        switch ($this->result) {
            case 'pending_waiting':

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'P';

                // Comentario personalizado para el log
                $comentario = 'Pendiente por CoinsPaid ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setPendiente($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);
                return $return;

                break;

            case "confirmed":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'A';

                // Comentario personalizado para el log
                $comentario = 'Aprobada por CoinsPaid ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setAprobada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->documento_id);
                return $return;

                break;

            case "cancelled":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Rechazada por CoinsPaid ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->documento_id);
                return $return;
                break;
        }
    }

}
