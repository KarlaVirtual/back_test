<?php

/**
 * Clase para la integración con el proveedor de pagos PayRetailers.
 *
 * Esta clase maneja la confirmación de transacciones y su estado
 * en el sistema, utilizando la información proporcionada por el proveedor.
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
 * Clase PayRetailers.
 *
 * Esta clase representa la integración con el proveedor de pagos PayRetailers.
 * Proporciona métodos para manejar la confirmación de transacciones y su estado.
 */
class PayRetailers
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
     * Valor de la transacción.
     *
     * @var float
     */
    var $valor;

    /**
     * Resultado de la transacción proporcionado por el proveedor.
     *
     * @var string
     */
    var $result;

    /**
     * Identificador único del documento asociado a la transacción.
     *
     * @var string
     */
    var $documento_id;

    /**
     * Constructor de la clase PayRetailers.
     *
     * @param mixed $invoice    Identificador de la factura.
     * @param mixed $usuario_id Identificador del usuario.
     * @param mixed $valor      Valor de la transacción.
     * @param mixed $result     Resultado de la transacción proporcionado por el proveedor.
     * @param mixed $uid        Identificador único del documento.
     */
    public function __construct($invoice, $usuario_id, $valor, $result, $uid)
    {
        $this->invoice = $invoice;
        $this->usuario_id = $usuario_id;
        $this->documento_id = $uid;
        $this->valor = $valor;
        $this->result = $result;
    }

    /**
     * Procesa la confirmación de una transacción según el resultado proporcionado por el proveedor.
     *
     * @param mixed $t_value Datos adicionales proporcionados por el proveedor para auditoría.
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

        // Valores que me trae el proveedor para auditoria
        $t_value = json_encode($t_value);


        switch ($this->result) {
            case 'PENDING_WAITING':

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'P';

                // Comentario personalizado para el log
                $comentario = 'Pendiente por PayRetailers ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setPendiente($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);
                return $return;

                break;

            case "APPROVED":

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'A';

                // Comentario personalizado para el log
                $comentario = 'Aprobada por PayRetailers ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setAprobada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->documento_id);
                return $return;

                break;

            case "REJECTED":

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Rechazada por PayRetailers';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);

                return $return;
                break;

            case "EXPIRED":

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Rechazada por PayRetailers';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);

                return $return;
                break;

            case "FAILED":

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Rechazada por PayRetailers';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);

                return $return;
                break;
        }
    }

}
