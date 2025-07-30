<?php
/**
 * Clase Monnet para la integración con el sistema de pagos.
 *
 * Esta clase gestiona la confirmación de transacciones de pago
 * y su registro en el sistema interno.
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
 * Clase Monnet
 *
 * Esta clase se utiliza para gestionar la integración con el sistema de pagos Monnet.
 */
class Monnet
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
     * Resultado del estado de la transacción.
     *
     * @var integer
     */
    var $result;

    /**
     * Identificador del documento relacionado con la transacción.
     *
     * @var string
     */
    var $documento_id;

    /**
     * Constructor de la clase Monnet.
     *
     * Inicializa los valores de la transacción, incluyendo el identificador de la factura,
     * el valor de la transacción, el resultado del estado y el identificador del documento.
     *
     * @param string  $invoice Identificador de la factura o transacción.
     * @param float   $valor   Valor asociado a la transacción.
     * @param integer $result  Resultado del estado de la transacción.
     * @param string  $uid     Identificador del documento relacionado con la transacción.
     */
    public function __construct($invoice, $valor, $result, $uid)
    {
        $this->invoice = $invoice;
        $this->documento_id = $uid;
        $this->valor = $valor;
        $this->result = $result;
    }

    /**
     * Confirma el estado de una transacción y actualiza su registro en el sistema.
     *
     * Este método procesa el estado de la transacción recibido desde el proveedor
     * y realiza las acciones correspondientes en el sistema interno, como marcar
     * la transacción como pendiente, aprobada o rechazada.
     *
     * @param mixed $t_value Datos adicionales proporcionados por el proveedor para auditoría.
     *
     * @return mixed Resultado de la operación realizada en la transacción.
     */
    public function confirmation($t_value)
    {
        // Convertimos a las variables de nuestro sistema

        // Estados posibles de la transacción:
        // 1 - Creado: El pago ha sido creado.
        // 2 - Pendiente de pago: El pago está pendiente.
        // 3 - Expirado: El pago ha expirado.
        // 5 - Autorizado: El pago se ha completado exitosamente.
        // 6 - Denegado: El pago ha sido completado o rechazado.

        // ID Transaccion en nuestro sistema
        $transaccion_id = $this->invoice;

        // Tipo que genera el log (A: Automatico, M: Manual)
        $tipo_genera = 'A';

        // Valores que me trae el proveedor para auditoria
        $t_value = json_encode($t_value);


        switch ($this->result) {
            case 2:

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'P';

                // Comentario personalizado para el log
                $comentario = 'Pendiente por Monnet ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setPendiente($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);
                return $return;

                break;

            case 5:

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'A';

                // Comentario personalizado para el log
                $comentario = 'Aprobada por Monnet ';

                // Obtenemos la transaccion
                $this->documento_id = $transaccion_id;
                $TransaccionProducto = new TransaccionProducto($transaccion_id);


                $return = $TransaccionProducto->setAprobada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->documento_id);
                return $return;

                break;

            case 6:

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Rechazada por Monnet ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);

                return $return;
                break;
        }
    }

}
