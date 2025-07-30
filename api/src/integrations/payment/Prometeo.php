<?php

/**
 * Clase Prometeo para gestionar integraciones de pagos con el proveedor Prometeo.
 *
 * Este archivo contiene la implementación de la clase Prometeo, que se encarga de manejar
 * las confirmaciones de transacciones de pago y su respectivo estado en el sistema.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-25
 */

namespace Backend\integrations\payment;

use Backend\dto\TransaccionProducto;

/**
 * Clase Prometeo.
 *
 * Esta clase gestiona las integraciones de pagos con el proveedor Prometeo,
 * permitiendo manejar confirmaciones de transacciones y actualizar su estado
 * en el sistema.
 */
class Prometeo
{
    /**
     * Identificador de la factura o transacción.
     *
     * @var mixed
     */
    var $invoice;

    /**
     * Resultado de la transacción proporcionado por el proveedor.
     *
     * @var mixed
     */
    var $result;

    /**
     * Identificador único del documento asociado a la transacción.
     *
     * @var mixed
     */
    var $documento_id;

    /**
     * Constructor de la clase Prometeo.
     *
     * @param mixed $invoice Identificador de la factura.
     * @param mixed $result  Resultado de la transacción.
     * @param mixed $uid     Identificador único del documento.
     */
    public function __construct($invoice, $result, $uid)
    {
        $this->invoice = $invoice;
        $this->documento_id = $uid;
        $this->result = $result;
    }

    /**
     * Maneja la confirmación de una transacción de pago.
     *
     * Según el resultado de la transacción proporcionado por el proveedor, actualiza
     * el estado de la transacción en el sistema y registra un log correspondiente.
     *
     * @param mixed $t_value Valores adicionales proporcionados por el proveedor para auditoría.
     *
     * @return mixed Resultado de la operación de actualización de la transacción.
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
            case "payment.error":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'P';

                // Comentario personalizado para el log
                $comentario = 'Pendiente por Prometeo ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setPendiente($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);
                return $return;

                break;

            case "payment.success":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'A';

                // Comentario personalizado para el log
                $comentario = 'Aprobada por Prometeo ';

                // Obtenemos la transaccion
                $TransaccionProducto = new TransaccionProducto($transaccion_id);


                $return = $TransaccionProducto->setAprobada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->documento_id);
                return $return;

                break;

            case "payment.rejected":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Rechazada por Prometeo';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->documento_id);

                return $return;
                break;

            case "payment.cancelled":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Rechazada por Prometeo';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->documento_id);

                return $return;
                break;
        }
    }

}
