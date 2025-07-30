<?php

/**
 * Clase LPG para manejar integraciones de pagos con el proveedor LPG.
 *
 * Esta clase permite procesar confirmaciones de transacciones de pago
 * y registrar los estados correspondientes en el sistema.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-23
 */

namespace Backend\Integrations\payment;

use Backend\dto\TransaccionProducto;

/**
 * Clase LPG para manejar integraciones de pagos con el proveedor LPG.
 *
 * Proporciona métodos para procesar confirmaciones de transacciones
 * y registrar los estados correspondientes en el sistema.
 */
class LPG
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
     * Código de control para la transacción.
     *
     * @var string
     */
    var $control;

    /**
     * Resultado del estado de la transacción.
     *
     * @var mixed
     */
    var $result;

    /**
     * Constructor de la clase LPG.
     *
     * Inicializa los atributos de la clase con los valores proporcionados.
     *
     * @param string  $invoice      Identificador de la factura o transacción.
     * @param integer $usuario_id   Identificador del usuario asociado a la transacción.
     * @param integer $documento_id Identificador del documento relacionado con la transacción.
     * @param float   $valor        Valor monetario de la transacción.
     * @param string  $control      Código de control para la transacción.
     * @param mixed   $result       Resultado del estado de la transacción.
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
     * Procesa la confirmación de una transacción de pago.
     *
     * Este método evalúa el resultado de la transacción proporcionado por el proveedor
     * y actualiza el estado correspondiente en el sistema, registrando la información
     * necesaria para auditoría.
     *
     * @return mixed Resultado del procesamiento de la transacción.
     */
    public function confirmation()
    {
        // Convertimos a las variables de nuestro sistema

        // ID Transaccion en nuestro sistema
        $transaccion_id = $this->invoice;

        // Tipo que genera el log (A: Automatico, M: Manual)
        $tipo_genera = 'A';


        // Valores que me trae el proveedor para auditoria
        $t_value = json_encode($this);

        switch ($this->result) {
            case 7:

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'P';

                // Comentario personalizado para el log
                $comentario = 'Pendiente por LPG ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setPendiente($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);
                return $return;

                break;

            case "approved":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'A';

                // Comentario personalizado para el log
                $comentario = 'Aprobada por LPG ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setAprobada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->documento_id);
                return $return;

                break;

            case "cancelled":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Rechazada por LPG ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);

                return $return;
                break;
        }
    }

}
