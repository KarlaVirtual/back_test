<?php

/**
 * Clase para manejar la integración con el proveedor de pagos Astropaycard.
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
use Exception;

/**
 * Clase Astropaycard
 *
 * Maneja las transacciones de pago realizadas a través del proveedor Astropaycard.
 */
class Astropaycard
{

    /**
     * ID de la factura asociada a la transacción.
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
     * Constructor de la clase Astropaycard.
     *
     * @param string  $invoice      ID de la factura.
     * @param integer $usuario_id   ID del usuario.
     * @param integer $documento_id ID del documento.
     * @param float   $valor        Monto de la transacción.
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
     * Procesa la confirmación de la transacción.
     *
     * Según el resultado proporcionado por el proveedor, actualiza el estado
     * de la transacción en el sistema y registra los datos necesarios para auditoría.
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
            case 'PENDING_WAITING':

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'P';

                // Comentario personalizado para el log
                $comentario = 'Pendiente por Astropaycard ';

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
                $comentario = 'Aprobada por Astropaycard ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setAprobada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->documento_id);
                return $return;

                break;

            case "CANCELLED":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';
                // Comentario personalizado para el log
                $comentario = 'Rechazada por Astropaycard ';
                // Obtenemos la transaccion
                try {
                    $TransaccionProducto = new TransaccionProducto($transaccion_id);

                    $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);

                    return $return;
                } catch (Exception $e) {
                }

                break;
        }
    }

}
