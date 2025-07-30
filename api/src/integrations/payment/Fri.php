<?php

/**
 * Clase Fri para la integración con el sistema de pagos.
 *
 * Esta clase maneja la lógica de confirmación de transacciones
 * provenientes del proveedor de pagos Fri, asignando estados
 * específicos según el resultado de la transacción.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-23
 */

namespace Backend\Integrations\payment;

use Backend\dto\TransaccionProducto;

/**
 * Clase Fri para manejar la integración con el sistema de pagos Fri.
 * Proporciona métodos para confirmar transacciones y asignar estados
 * según el resultado de las mismas.
 */
class Fri
{

    /**
     * Identificador de la factura o transacción.
     *
     * @var string
     */
    var $invoice;

    /**
     * Valor asociado a la transacción.
     *
     * @var float
     */
    var $valor;

    /**
     * Resultado de la transacción.
     *
     * @var string
     */
    var $result;

    /**
     * Identificador del documento relacionado con la transacción.
     *
     * @var integer
     */
    var $documento_id;

    /**
     * Constructor de la clase Fri.
     *
     * Inicializa los valores de la transacción, incluyendo el identificador
     * de la factura, el valor, el resultado y el identificador del documento.
     *
     * @param string  $invoice Identificador de la factura o transacción.
     * @param float   $valor   Valor asociado a la transacción.
     * @param string  $result  Resultado de la transacción.
     * @param integer $uid     Identificador del documento relacionado.
     */
    public function __construct($invoice, $valor, $result, $uid)
    {
        $this->invoice = $invoice;
        $this->documento_id = $uid;
        $this->valor = $valor;
        $this->result = $result;
    }

    /**
     * Confirma el estado de una transacción y asigna un estado específico
     * basado en el resultado proporcionado por el proveedor de pagos.
     *
     * @param mixed $t_value Valores adicionales proporcionados por el proveedor
     *                       para auditoría y registro.
     *
     * @return mixed Retorna el resultado de la operación según el estado
     *               de la transacción.
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
                $comentario = 'Pendiente por Fri';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setPendiente($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);
                return $return;

                break;

            case 'completed':

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'A';

                // Comentario personalizado para el log
                $comentario = 'Aprobada por Fri';

                // Obtenemos la transaccion
                //$this->documento_id=$transaccion_id;

                $TransaccionProducto = new TransaccionProducto($transaccion_id);


                $return = $TransaccionProducto->setAprobada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->documento_id);
                return $return;

                break;

            case 'rejected':


                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Rechazada por Fri';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);

                return $return;
                break;
        }
    }

}
