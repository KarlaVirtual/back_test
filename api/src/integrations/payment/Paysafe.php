<?php

/**
 * Clase Paysafe para manejar integraciones de pagos con el proveedor Paysafe.
 *
 * Este archivo contiene la implementación de la clase Paysafe, que se utiliza para procesar
 * transacciones de pago y registrar los estados correspondientes en el sistema.
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
 * Clase Paysafe para manejar integraciones de pagos con el proveedor Paysafe.
 */
class Paysafe
{

    /**
     * Identificador de la factura o transacción.
     *
     * @var mixed
     */
    var $invoice;

    /**
     * Resultado de la transacción.
     *
     * @var mixed
     */
    var $result;

    /**
     * Identificador externo proporcionado por Paysafe.
     *
     * @var mixed
     */
    var $external_id;

    /**
     * Constructor de la clase Paysafe.
     *
     * @param mixed $invoice   Identificador de la factura.
     * @param mixed $result    Resultado de la transacción.
     * @param mixed $externoId Identificador externo proporcionado por Paysafe.
     */
    public function __construct($invoice, $result, $externoId)
    {
        $this->invoice = $invoice;
        $this->external_id = $externoId;
        $this->result = $result;
    }

    /**
     * Procesa la confirmación de una transacción según el resultado proporcionado por Paysafe.
     *
     * @param mixed $t_value Valores adicionales proporcionados por el proveedor para auditoría.
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
            case "PROCESSING":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'P';

                // Comentario personalizado para el log
                $comentario = 'Pendiente por Paysafe';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setPendiente($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);
                return $return;

                break;

            case "PAYABLE":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'A';

                // Comentario personalizado para el log
                $comentario = 'Aprobada por Paysafe';

                // Obtenemos la transaccion
                $TransaccionProducto = new TransaccionProducto($transaccion_id);


                $return = $TransaccionProducto->setAprobada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->external_id);
                return $return;

                break;

            case "FAILED":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Rechazada por Paysafe';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->external_id);

                return $return;
                break;

            case "EXPIRED":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Rechazada por Paysafe';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->external_id);

                return $return;
                break;

            case "REFUNDED":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Reembolsado por Paysafe';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->external_id);

                return $return;
                break;
        }
    }

}
