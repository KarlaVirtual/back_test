<?php

/**
 * Clase para la integración con el proveedor de pagos Directa24.
 *
 * Este archivo contiene la implementación de la clase `Directa24`, que gestiona
 * la confirmación de transacciones de pago realizadas a través del proveedor Directa24.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-23
 */

namespace Backend\Integrations\Payment;

use Backend\dto\TransaccionProducto;

/**
 * Clase Directa24
 *
 * Esta clase representa la integración con el proveedor de pagos Directa24.
 * Permite gestionar transacciones y realizar confirmaciones de estado.
 */
class Directa24
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
     * Código de control para validar la transacción.
     *
     * @var string
     */
    var $control;

    /**
     * Resultado del estado de la transacción.
     * Puede ser un código numérico o una cadena que representa el estado.
     *
     * @var integer|string
     */
    var $result;

    /**
     * Constructor de la clase Directa24.
     *
     * Inicializa una nueva instancia de la clase con los datos proporcionados.
     *
     * @param string         $invoice      Identificador de la factura o transacción.
     * @param integer        $usuario_id   Identificador del usuario asociado a la transacción.
     * @param integer        $documento_id Identificador del documento relacionado con la transacción.
     * @param float          $valor        Valor monetario de la transacción.
     * @param string         $control      Código de control para validar la transacción.
     * @param integer|string $result       Resultado del estado de la transacción.
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
     * Confirma el estado de la transacción basado en el resultado proporcionado.
     *
     * Este metodo procesa el resultado de la transacción y actualiza su estado
     * en el sistema según los valores proporcionados por el proveedor Directa24.
     *
     * @return void
     */
    public function confirmation()
    {
        // ID Transaccion en nuestro sistema
        $transaccion_id = $this->invoice;

        // Tipo que genera el log (A: Automatico, M: Manual)
        $tipo_genera = 'A';

        // Valores que me trae el proveedor para auditoria
        $t_value = json_encode($this);

        switch ($this->result) {
            case 7:
            case 'PENDING':

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'P';

                // Comentario personalizado para el log
                $comentario = 'Pendiente por Directa24 ';

                // Obtenemos la transaccion
                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $TransaccionProducto->setPendiente($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);

                break;

            case 9:
            case 'COMPLETED':

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'A';

                // Comentario personalizado para el log
                $comentario = 'Aprobada por Directa24 ';

                // Obtenemos la transaccion
                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $TransaccionProducto->setAprobada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->documento_id);

                break;

            case 8:
            case 'CANCELLED':

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Rechazada por Directa24 ';

                // Obtenemos la transaccion
                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);

                break;
        }
    }
}
