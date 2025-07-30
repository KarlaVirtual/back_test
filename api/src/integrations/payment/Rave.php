<?php

/**
 * Clase Rave para manejar integraciones de pagos con el proveedor Rave.
 *
 * Este archivo contiene la implementación de la clase Rave, que permite
 * gestionar transacciones de pago y registrar su estado en el sistema.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations\Payment
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-25
 */

namespace Backend\Integrations\Payment;

use Backend\dto\TransaccionProducto;

/**
 * Clase Rave.
 *
 * Esta clase se utiliza para manejar integraciones de pagos con el proveedor Rave.
 */
class Rave
{
    /**
     * Identificador de la factura o transacción.
     *
     * @var mixed
     */
    var $invoice;

    /**
     * Identificador del usuario asociado a la transacción.
     *
     * @var mixed
     */
    var $usuario_id;

    /**
     * Identificador del documento relacionado con la transacción.
     *
     * @var mixed
     */
    var $documento_id;

    /**
     * Valor monetario de la transacción.
     *
     * @var mixed
     */
    var $valor;

    /**
     * Código de control para la transacción.
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
     * Constructor de la clase Rave.
     *
     * @param mixed $invoice      ID de la factura.
     * @param mixed $usuario_id   ID del usuario.
     * @param mixed $documento_id ID del documento.
     * @param mixed $valor        Valor de la transacción.
     * @param mixed $control      Código de control.
     * @param mixed $result       Resultado de la transacción.
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
     * Procesa la confirmación de una transacción.
     *
     * Este metodo evalúa el resultado de la transacción y actualiza su estado
     * en el sistema según el código de resultado proporcionado.
     *
     * @param array $data Datos adicionales proporcionados por el proveedor.
     *
     * @return void
     */
    public function confirmation($data)
    {
        // ID Transacción en nuestro sistema
        $transaccion_id = $this->invoice;

        // Tipo que genera el log (A: Automatico, M: Manual)
        $tipo_genera = 'A';

        // Valores que me trae el proveedor para auditoría
        $t_value = json_encode($data);

        switch ($this->result) {
            case 7:

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'P';

                // Comentario personalizado para el log
                $comentario = 'Pendiente por Rave ';


                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $TransaccionProducto->setPendiente($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);

                break;

            case 9:

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'A';

                // Comentario personalizado para el log
                $comentario = 'Aprobada por Rave ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $TransaccionProducto->setAprobada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->documento_id);

                break;

            case 8:

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Rechazada por Rave ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);

                break;
        }
    }

}
