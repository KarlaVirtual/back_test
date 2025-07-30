<?php

/**
 * Clase CoinPayments
 *
 * Esta clase se encarga de manejar la integración con el proveedor de pagos CoinPayments.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-23
 */

namespace Backend\Integrations\payment;

use Backend\dto\TransaccionProducto;

/**
 * Clase CoinPayments
 *
 * Esta clase se encarga de manejar la integración con el proveedor de pagos CoinPayments.
 */
class CoinPayments
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
     * Resultado de la transacción.
     *
     * @var string
     */
    var $result;

    /**
     * Constructor de la clase CoinPayments.
     *
     * @param string  $invoice      ID de la factura asociada a la transacción.
     * @param integer $usuario_id   ID del usuario que realiza la transacción.
     * @param integer $documento_id ID del documento relacionado con la transacción.
     * @param float   $valor        Valor monetario de la transacción.
     * @param string  $control      Código de control para la transacción.
     * @param string  $result       Resulatdo de la transaccion.
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
     * Procesa la confirmación de una transacción según su estado.
     *
     * Este metodo maneja diferentes estados de una transacción (nuevo, completado,
     * inválido, expirado) y realiza las acciones correspondientes, como registrar
     * los datos en el sistema y generar logs.
     *
     * @param array $t_value Datos adicionales proporcionados por el proveedor.
     *
     * @return mixed Resultado del procesamiento de la transacción.
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
            case '0':

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'P';

                // Comentario personalizado para el log
                $comentario = 'Pendiente por CoinPayments ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setPendiente($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);
                return $return;

                break;

            case "100":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'A';

                // Comentario personalizado para el log
                $comentario = 'Aprobada por CoinPayments ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setAprobada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->documento_id);
                return $return;

                break;

            case "-1":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Rechazada por CoinPayments ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);

                return $return;
                break;
        }
    }

}
