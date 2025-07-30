<?php

/**
 * Clase ClubPago
 *
 * Esta clase se encarga de manejar la integración con el proveedor de pagos ClubPago.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-23
 */

namespace Backend\integrations\payment;

use Backend\dto\TransaccionProducto;

/**
 * Clase ClubPago
 *
 * Esta clase se encarga de manejar la integración con el proveedor de pagos ClubPago.
 * Proporciona métodos para procesar y confirmar transacciones según el estado recibido
 * del proveedor.
 */
class ClubPago
{
    /**
     * ID de la factura asociada a la transacción.
     *
     * @var string
     */
    var $invoice;

    /**
     * ID del usuario que realiza la transacción.
     *
     * @var integer
     */
    var $usuario_id;

    /**
     * ID del documento relacionado con la transacción.
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
     * @var string
     */
    var $result;

    /**
     * Constructor de la clase ClubPago.
     *
     * @param string  $invoice      ID de la factura.
     * @param integer $usuario_id   ID del usuario.
     * @param integer $documento_id ID del documento.
     * @param float   $valor        Valor de la transacción.
     * @param string  $control      Código de control.
     * @param string  $result       Resultado de la transacción.
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
     * Procesa la confirmación de una transacción según el estado proporcionado por el proveedor.
     *
     * @param mixed $t_value Datos adicionales de la transacción para auditoría.
     *
     * @return mixed Resultado del procesamiento de la transacción.
     */
    public function confirmation($t_value)
    {
        // Convertimos a las variables de nuestro sistema

        // ID Transaccion en nuestro sistema
        $transaccion_id = $this->invoice;

        // Tipo que genera el log (A: Automático, M: Manual)
        $tipo_genera = 'A';

        $this->result = strtolower($this->result);
        // Valores que me trae el proveedor para auditoría
        $t_value = json_encode($t_value);

        switch ($this->result) {
            case 'pendiente':
                // Estado especial por proveedor (A: Aprobado, P: Pendiente, R: Rechazada)
                $estado = 'P';

                // Comentario personalizado para el log
                $comentario = 'Pendiente por ClubPago ';

                // Obtenemos la transacción
                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                // Procesamos la transacción como pendiente
                $return = $TransaccionProducto->setPendiente($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);
                return $return;

                break;

            case "aprobado":
                // Estado especial por proveedor (A: Aprobado, P: Pendiente, R: Rechazada)
                $estado = 'A';

                // Comentario personalizado para el log
                $comentario = 'Aprobada por ClubPago ';

                // Obtenemos la transacción
                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                // Procesamos la transacción como aprobada
                $return = $TransaccionProducto->setAprobada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->documento_id);
                return $return;

                break;

            case "rechazado":
                // Estado especial por proveedor (A: Aprobado, P: Pendiente, R: Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Rechazada por ClubPago ';

                // Obtenemos la transacción
                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                // Procesamos la transacción como rechazada
                $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);
                return $return;
                break;
        }
    }

}
