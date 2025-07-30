<?php

/**
 * Clase Digitalfemsa para manejar integraciones de pagos con el proveedor Digitalfemsa.
 *
 * Este archivo contiene la implementación de la clase Digitalfemsa, que permite procesar
 * transacciones de pago y registrar su estado en el sistema.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-23
 */

namespace Backend\integrations\payment;

use Backend\dto\TransaccionProducto;

/**
 * Clase Digitalfemsa para manejar integraciones de pagos con el proveedor Digitalfemsa.
 *
 * Esta clase proporciona métodos para gestionar transacciones de pago y registrar su estado
 * en el sistema, utilizando la API del proveedor Digitalfemsa.
 */
class Digitalfemsa
{

    /**
     * Identificador único de la factura asociada a la transacción.
     *
     * @var string
     */
    var $invoice;

    /**
     * Identificador del usuario que realiza la transacción.
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
     * Resultado del estado de la transacción (e.g., pendiente, aprobada, rechazada).
     *
     * @var string
     */
    var $result;

    /**
     * Constructor de la clase Digitalfemsa.
     *
     * Este constructor inicializa los atributos de la clase con los valores proporcionados
     * para manejar la transacción de pago.
     *
     * @param string  $invoice      Identificador único de la factura.
     * @param integer $usuario_id   Identificador del usuario que realiza la transacción.
     * @param integer $documento_id Identificador del documento relacionado con la transacción.
     * @param float   $valor        Valor monetario de la transacción.
     * @param string  $control      Código de control para validar la transacción.
     * @param string  $result       Resultado del estado de la transacción.
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
     * Confirma el estado de una transacción y actualiza su registro en el sistema.
     *
     * Este metodo procesa el estado de la transacción recibido desde el proveedor Digitalfemsa
     * y actualiza el registro correspondiente en el sistema, dependiendo del estado reportado.
     *
     * @param mixed $t_value Datos adicionales proporcionados por el proveedor para auditoría.
     *
     * @return mixed Resultado de la operación de confirmación, dependiendo del estado de la transacción.
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
                $comentario = 'Pendiente por Digitalfemsa';

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
                $comentario = 'Aprobada por Digitalfemsa ';

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
                $comentario = 'Rechazada por Digitalfemsa ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);

                return $return;
                break;
        }
    }

}
