<?php

/**
 * Clase Pagsmile para manejar integraciones de pagos con el proveedor Pagsmile.
 *
 * Este archivo contiene la implementación de la clase Pagsmile, que se utiliza
 * para procesar y manejar transacciones de pago con diferentes estados, como
 * pendiente, aprobada, rechazada, reembolsada, etc.
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
 * Clase Pagsmile.
 *
 * Esta clase se utiliza para manejar integraciones de pagos con el proveedor Pagsmile.
 */
class Pagsmile
{
    /**
     * ID de la factura asociada a la transacción.
     *
     * @var mixed
     */
    var $invoice;

    /**
     * Resultado del estado de la transacción proporcionado por el proveedor.
     *
     * @var mixed
     */
    var $result;

    /**
     * ID del documento asociado a la transacción.
     *
     * @var mixed
     */
    var $documento_id;

    /**
     * Constructor de la clase Pagsmile.
     *
     * @param mixed $invoice ID de la factura.
     * @param mixed $result  Resultado del estado de la transacción.
     * @param mixed $uid     ID del documento asociado.
     */
    public function __construct($invoice, $result, $uid)
    {
        $this->invoice = $invoice;
        $this->documento_id = $uid;
        $this->result = $result;
    }

    /**
     * Procesa la confirmación de una transacción según el estado proporcionado.
     *
     * @param array $t_value Valores adicionales proporcionados por el proveedor para auditoría.
     *
     * @return mixed Resultado del procesamiento de la transacción.
     */
    public function confirmation($t_value)
    {
        // ID Transaccion en nuestro sistema
        $transaccion_id = $this->invoice;

        // Tipo que genera el log (A: Automatico, M: Manual)
        $tipo_genera = 'A';

        // Valores que me trae el proveedor para auditoria
        $t_value = json_encode($t_value);


        switch ($this->result) {
            case "PROCESSING_PENDING":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'P';

                // Comentario personalizado para el log
                $comentario = 'Pendiente por Pagsmile';

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
                $comentario = 'Aprobada por Pagsmile';

                // Obtenemos la transaccion
                $TransaccionProducto = new TransaccionProducto($transaccion_id);


                $return = $TransaccionProducto->setAprobada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->documento_id);
                return $return;

                break;

            case "CANCEL":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Rechazada por Pagsmile';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->documento_id);

                return $return;
                break;

            case "REFUSED":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Rechazada por Pagsmile';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->documento_id);

                return $return;
                break;

            case "REFUNDED":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Reembolsado por Pagsmile';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->documento_id);

                return $return;
                break;
        }
    }

}
