<?php

/**
 * Clase para la integración con el proveedor de pagos N1CO.
 *
 * Este archivo contiene la implementación de la clase N1CO, que se utiliza para manejar
 * las transacciones de pago realizadas a través del proveedor N1CO. Proporciona métodos
 * para confirmar el estado de las transacciones y registrar los resultados en el sistema.
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
 * Clase N1CO
 *
 * Esta clase gestiona las transacciones de pago realizadas a través del proveedor N1CO.
 * Proporciona métodos para confirmar el estado de las transacciones y registrar los
 * resultados en el sistema.
 */
class N1CO
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
     * Identificador del documento asociado a la transacción.
     *
     * @var string
     */
    var $documento_id;

    /**
     * Valor monetario de la transacción.
     *
     * @var float
     */
    var $valor;

    /**
     * Código de control para la transacción.
     *
     * @var string
     */
    var $control;

    /**
     * Resultado de la transacción (PENDING, SUCCEEDED, FAILED).
     *
     * @var string
     */
    var $result;

    /**
     * Constructor de la clase N1CO.
     *
     * Inicializa los atributos de la clase con los valores proporcionados.
     *
     * @param string $invoice      ID de la factura.
     * @param string $documento_id ID del documento asociado.
     * @param float  $valor        Valor de la transacción.
     * @param string $result       Resultado de la transacción (PENDING, SUCCEEDED, FAILED).
     */
    public function __construct($invoice, $documento_id, $valor, $result)
    {
        $this->invoice = $invoice;
        $this->documento_id = $documento_id;
        $this->valor = $valor;
        $this->result = $result;
    }

    /**
     * Confirma el estado de una transacción.
     *
     * Este método procesa el resultado de la transacción proporcionado por el proveedor
     * y actualiza el estado de la transacción en el sistema.
     *
     * @param array $t_value Datos adicionales proporcionados por el proveedor para auditoría.
     *
     * @return mixed Resultado de la operación de confirmación.
     */
    public function confirmation($t_value)
    {
        // Convertimos a las variables de nuestro sistema

        // ID Transaccion en nuestro sistema
        $transaccion_id = $this->invoice;

        // Tipo que genera el log (A: Automatico, M: Manual)
        $tipo_genera = 'A';

        $this->result = strtoupper($this->result);
        // Valores que me trae el proveedor para auditoria
        $t_value = json_encode($t_value);

        switch ($this->result) {
            case 'PENDING':

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'P';

                // Comentario personalizado para el log
                $comentario = 'Pendiente por n1co ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setPendiente($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);
                return $return;

                break;

            case "SUCCEEDED":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'A';

                // Comentario personalizado para el log
                $comentario = 'Aprobada por n1co ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setAprobada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->documento_id);
                return $return;

                break;

            case "FAILED":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Rechazada por n1co ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);

                return $return;
                break;
        }
    }

}
