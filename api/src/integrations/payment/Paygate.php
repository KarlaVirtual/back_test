<?php

/**
 * Clase Paygate para la integración con un proveedor de pagos.
 *
 * Esta clase maneja la confirmación de transacciones de pago, asignando estados
 * y generando logs según el resultado proporcionado por el proveedor.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-25
 */

namespace Backend\integrations\payment;

use Backend\dto\TransaccionProducto;

/**
 * Clase principal para manejar la integración con el proveedor de pagos Paygate.
 */
class Paygate
{
    /**
     * ID de la transacción en el sistema.
     *
     * @var mixed
     */
    var $invoice;

    /**
     * ID del usuario asociado a la transacción.
     *
     * @var mixed
     */
    var $usuario_id;

    /**
     * ID del documento asociado a la transacción.
     *
     * @var mixed
     */
    var $documento_id;

    /**
     * Valor de la transacción.
     *
     * @var mixed
     */
    var $valor;

    /**
     * Código de control de la transacción.
     *
     * @var mixed
     */
    var $control;

    /**
     * Resultado de la transacción proporcionado por el proveedor.
     *
     * @var mixed
     */
    var $result;

    /**
     * Constructor de la clase Paygate.
     *
     * @param mixed $invoice      ID de la transacción en el sistema.
     * @param mixed $usuario_id   ID del usuario asociado a la transacción.
     * @param mixed $documento_id ID del documento asociado a la transacción.
     * @param mixed $valor        Valor de la transacción.
     * @param mixed $control      Código de control de la transacción.
     * @param mixed $result       Resultado de la transacción proporcionado por el proveedor.
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
     * Maneja la confirmación de la transacción según el resultado proporcionado.
     *
     * Este metodo asigna estados, genera comentarios personalizados y registra
     * la transacción en el sistema según el resultado proporcionado por el proveedor.
     *
     * @param mixed $t_value Valores adicionales proporcionados por el proveedor para auditoría.
     *
     * @return mixed Resultado del registro de la transacción en el sistema.
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
            case 'PENDING_WAITING':

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,C:Cancelado,R:Rechazada)
                $estado = 'P';

                // Comentario personalizado para el log
                $comentario = 'Pendiente por Paygate ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setPendiente($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);
                return $return;

                break;

            case "APPROVED":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'A';

                // Comentario personalizado para el log
                $comentario = 'Aprobada por Paygate ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setAprobada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->documento_id);
                return $return;

                break;

            case "CANCELED":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Rechazada por Paygate ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);

                return $return;
                break;
        }
    }

}
