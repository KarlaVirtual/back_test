<?php

/**
 * Clase para la integración con el proveedor de pagos Western Union.
 *
 * Esta clase permite manejar las transacciones realizadas a través de Western Union,
 * incluyendo la confirmación de pagos y la actualización de estados en el sistema.
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
 * Clase WesternUnion.
 *
 * Esta clase maneja la integración con el proveedor de pagos Western Union,
 * permitiendo la gestión de transacciones como confirmación de pagos y actualización de estados.
 */
class WesternUnion
{
    /**
     * Identificador de la factura o transacción.
     *
     * @var string
     */
    var $invoice;

    /**
     * Identificador del usuario asociado a la transacción.
     *
     * @var integer
     */
    var $usuario_id;

    /**
     * Identificador del documento relacionado con la transacción.
     *
     * @var integer
     */
    var $documento_id;

    /**
     * Valor monetario de la transacción.
     *
     * @var float
     */
    var $valor;

    /**
     * Código de control de la transacción.
     *
     * @var string
     */
    var $control;

    /**
     * Resultado de la transacción proporcionado por el proveedor.
     *
     * @var string
     */
    var $result;

    /**
     * Constructor de la clase WesternUnion.
     *
     * @param string  $invoice      Identificador de la factura.
     * @param integer $usuario_id   Identificador del usuario.
     * @param integer $documento_id Identificador del documento.
     * @param float   $valor        Valor de la transacción.
     * @param string  $control      Código de control de la transacción.
     * @param string  $result       Resultado de la transacción proporcionado por el proveedor.
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
     * Confirma el estado de una transacción basada en el resultado proporcionado por el proveedor.
     *
     * Este método actualiza el estado de la transacción en el sistema dependiendo del resultado
     * recibido de Western Union (pendiente, aprobado o rechazado).
     *
     * @param mixed $t_value Datos adicionales de la transacción para auditoría.
     *
     * @return boolean Resultado de la operación de confirmación.
     */
    public function confirmation($t_value)
    {
        // Convertimos a las variables de nuestro sistema

        // ID Transaccion en nuestro sistema
        $transaccion_id = $this->invoice;

        // Tipo que genera el log (A: Automatico, M: Manual)
        $tipo_genera = 'A';

        $this->result = strtolower($this->result);
        // Valores que me trae el proveedor para auditoria
        $t_value = json_encode($t_value);

        switch ($this->result) {
            case 'pending_payment':

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'P';

                // Comentario personalizado para el log
                $comentario = 'Pendiente por WesternUnion ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setPendiente($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);
                return $return;

                break;

            case "paid":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'A';

                // Comentario personalizado para el log
                $comentario = 'Aprobada por WesternUnion ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setAprobada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->documento_id);
                return $return;

                break;

            case "error":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Rechazada por WesternUnion ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);

                return $return;
                break;
        }
    }

}
