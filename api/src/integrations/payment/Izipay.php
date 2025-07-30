<?php
/**
 * Clase para la integración con el proveedor de pagos Izipay.
 *
 * Esta clase permite manejar transacciones de pago realizadas a través de Izipay,
 * incluyendo la confirmación de transacciones y la asignación de estados específicos
 * según la respuesta del proveedor.
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
 * Clase Izipay.
 *
 * Esta clase representa la integración con el proveedor de pagos Izipay,
 * permitiendo manejar transacciones de pago y sus estados.
 */
class Izipay
{
    /**
     * ID externo proporcionado por el proveedor.
     *
     * @var string
     */
    public $externoId = "";

    /**
     * ID de la transacción en el sistema.
     *
     * @var string
     */
    public $TransactionId = "";

    /**
     * Monto de la transacción.
     *
     * @var string
     */
    public $Amount = "";

    /**
     * Estado de la transacción.
     *
     * @var string
     */
    public $status = "";

    /**
     * Constructor de la clase Izipay.
     *
     * @param string $externoId     ID externo proporcionado por el proveedor.
     * @param string $TransactionId ID de la transacción en el sistema.
     * @param string $Amount        Monto de la transacción.
     * @param string $status        Estado de la transacción.
     */
    public function __construct($externoId, $TransactionId, $Amount, $status)
    {
        $this->externoId = $externoId;
        $this->TransactionId = $TransactionId;
        $this->Amount = $Amount;
        $this->status = $status;
    }

    /**
     * Confirma el estado de una transacción según la respuesta del proveedor.
     *
     * Este método procesa los datos proporcionados por el proveedor y actualiza
     * el estado de la transacción en el sistema, generando los logs correspondientes.
     *
     * @param array $data Datos proporcionados por el proveedor para la transacción.
     *
     * @return mixed Resultado de la operación de confirmación.
     */
    public function confirmation($data)
    {
        // Convertimos a las variables de nuestro sistema

        // ID Transaccion en nuestro sistema
        $transaccion_id = $this->TransactionId;

        // Tipo que genera el log (A: Automatico, M: Manual)
        $tipo_genera = 'A';

        // Valores que me trae el proveedor para auditoria
        $t_value = json_encode($data);

        switch ($this->status) {
            case 'PENDING':

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'P';

                // Comentario personalizado para el log
                $comentario = 'Pendiente por Izipay';

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
                $comentario = 'Aprobada por Izipay';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setAprobada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->externoId);
                return $return;

                break;

            case "CANCELED":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Rechazada por Izipay ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);

                return $return;
                break;
        }
    }

}
