<?php

/**
 * Clase Totalpago para manejar integraciones de pagos con el proveedor Totalpago.
 *
 * Esta clase permite gestionar transacciones de productos, incluyendo la confirmación
 * de estados como pendiente, aprobada, cancelada o reembolsada.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-25
 */

namespace Backend\integrations\payment;

use Backend\dto\TransaccionProducto;

/**
 * Clase Totalpago para manejar integraciones de pagos con el proveedor Totalpago.
 *
 * Esta clase permite gestionar transacciones de productos, incluyendo la confirmación
 * de estados como pendiente, aprobada, cancelada o reembolsada.
 */
class Totalpago
{
    /**
     * ID del producto de la transacción.
     *
     * @var integer|string
     */
    var $transproducto_id;

    /**
     * Estado actual de la transacción.
     *
     * @var string
     */
    var $status;

    /**
     * ID externo proporcionado por el proveedor.
     *
     * @var string
     */
    var $externo_id;

    /**
     * Constructor de la clase Totalpago.
     *
     * @param integer|string $transproducto_id ID del producto de la transacción.
     * @param string         $status           Estado inicial de la transacción.
     * @param string         $uid              ID externo proporcionado por el proveedor.
     */
    public function __construct($transproducto_id, $status, $uid)
    {
        $this->transproducto_id = $transproducto_id;
        $this->externo_id = $uid;
        $this->status = $status;
    }

    /**
     * Confirma el estado de la transacción basado en el estado actual.
     *
     * Este método procesa la transacción según el estado actual y realiza
     * las acciones correspondientes, como marcarla como pendiente, aprobada,
     * cancelada o reembolsada.
     *
     * @param array $t_value Datos adicionales proporcionados por el proveedor.
     *
     * @return mixed Resultado de la operación realizada en la transacción.
     */
    public function confirmation($t_value)
    {
        // ID Transaccion en nuestro sistema
        $transaccion_id = $this->transproducto_id;

        // Tipo que genera el log (A: Automatico, M: Manual)
        $tipo_genera = 'A';

        // Valores que me trae el proveedor para auditoria
        $t_value = json_encode($t_value);

        switch ($this->status) {
            case "PROGRESS_PENDING":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'P';

                // Comentario personalizado para el log
                $comentario = 'Pendiente por Totalpago';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setPendiente($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);
                return $return;

                break;

            case "SUCCESS":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'A';

                // Comentario personalizado para el log
                $comentario = 'Aprobada por Totalpago';

                // Obtenemos la transaccion
                $TransaccionProducto = new TransaccionProducto($transaccion_id);


                $return = $TransaccionProducto->setAprobada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->externo_id);
                return $return;

                break;

            case "CANCEL":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Rechazada por Totalpago';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->externo_id, true);

                return $return;

                break;

            case "REFUNDED":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Reembolsado por Totalpago';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->externo_id, true);

                return $return;
                break;
        }
    }

}
