<?php
/**
 * Clase Greenpay para manejar integraciones de pagos con el proveedor Greenpay.
 *
 * Este archivo contiene la implementación de la clase Greenpay, que permite
 * gestionar transacciones de pago y registrar sus estados en el sistema.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-23
 */

namespace Backend\integrations\payment;

use Backend\dto\TransaccionProducto;

/**
 * Clase principal para manejar la integración con el proveedor de pagos Greenpay.
 */
class Greenpay
{
    /**
     * ID de la transacción en el sistema.
     *
     * @var string
     */
    var $transaccion_id;

    /**
     * Resultado de la transacción proporcionado por el proveedor.
     *
     * @var string
     */
    var $result;

    /**
     * ID externo proporcionado por el proveedor.
     *
     * @var string
     */
    var $externo_Id;

    /**
     * Constructor de la clase Greenpay.
     *
     * @param string $transaccion_id ID de la transacción en el sistema.
     * @param string $result         Resultado de la transacción proporcionado por el proveedor.
     * @param string $externo_Id     ID externo proporcionado por el proveedor.
     */
    public function __construct($transaccion_id, $result, $externo_Id)
    {
        $this->transaccion_id = $transaccion_id;
        $this->externo_Id = $externo_Id;
        $this->result = $result;
    }

    /**
     * Procesa la confirmación de una transacción según el resultado proporcionado.
     *
     * Este método registra el estado de la transacción en el sistema dependiendo
     * del resultado recibido del proveedor (PROGRESS, SUCCESS, CANCEL, REFUNDED).
     *
     * @param mixed $t_value Datos adicionales proporcionados por el proveedor para auditoría.
     *
     * @return mixed Resultado del registro de la transacción.
     */
    public function confirmation($t_value)
    {
        // ID Transaccion en nuestro sistema
        $transaccion_id = $this->transaccion_id;

        // Tipo que genera el log (A: Automatico, M: Manual)
        $tipo_genera = 'A';

        // Valores que me trae el proveedor para auditoria
        $t_value = json_encode($t_value);

        switch ($this->result) {
            case "PROGRESS":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'P';

                // Comentario personalizado para el log
                $comentario = 'Pendiente por Greenpay';

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
                $comentario = 'Aprobada por Greenpay';

                // Obtenemos la transaccion
                $TransaccionProducto = new TransaccionProducto($transaccion_id);


                $return = $TransaccionProducto->setAprobada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->externo_Id);
                return $return;

                break;

            case "CANCEL":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Rechazada por Greenpay';

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
                $comentario = 'Reembolsado por Greenpay';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->documento_id);

                return $return;
                break;
        }
    }

}
