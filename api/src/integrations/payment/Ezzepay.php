<?php
/**
 * Clase Ezzepay para manejar integraciones de pagos con el proveedor Ezzepay.
 *
 * Este archivo contiene la implementación de la clase Ezzepay, que permite
 * gestionar transacciones de pago y su confirmación con diferentes estados.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-23
 */

namespace Backend\integrations\payment;

use Backend\dto\TransaccionProducto;

/**
 * Clase Ezzepay.
 *
 * Esta clase maneja la integración con el proveedor de pagos Ezzepay,
 * permitiendo gestionar transacciones y sus estados.
 */
class Ezzepay
{
    /**
     * Constructor de la clase Ezzepay.
     *
     * @param string $PublicId      Identificador público del proveedor.
     * @param string $TransactionId Identificador de la transacción.
     * @param float  $Amount        Monto de la transacción.
     * @param string $status        Estado inicial de la transacción.
     */
    public function __construct($PublicId, $TransactionId, $Amount, $status)
    {
        $this->PublicId = $PublicId;
        $this->TransactionId = $TransactionId;
        $this->Amount = $Amount;
        $this->status = $status;
    }

    /**
     * Confirma el estado de una transacción según los datos proporcionados.
     *
     * Este método procesa la confirmación de una transacción dependiendo del
     * estado actual (`PENDING`, `APPROVED`, `CANCELED`) y realiza las acciones
     * correspondientes en el sistema.
     *
     * @param array $data Datos adicionales proporcionados por el proveedor.
     *
     * @return mixed Resultado de la operación de confirmación.
     */
    public function confirmation($data)
    {
        // Convertimos a las variables de nuestro sistema

        // ID Transaccion en nuestro sistema
        $transaccion_id = $this->TransactionId;

        // Tipo que genera el log (A: Automático, M: Manual)
        $tipo_genera = 'A';

        // Valores que me trae el proveedor para auditoría
        $t_value = json_encode($data);

        switch ($this->status) {
            case 'PENDING':
                // Estado especial por proveedor (A: Aprobado, P: Pendiente, R: Rechazada)
                $estado = 'P';
                $comentario = 'Pendiente por ezzepay';

                // Obtenemos la transacción
                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                // Marcamos la transacción como pendiente
                $return = $TransaccionProducto->setPendiente($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);
                return $return;

                break;

            case "APPROVED":
                // Estado especial por proveedor (A: Aprobado, P: Pendiente, R: Rechazada)
                $estado = 'A';
                $comentario = 'Aprobada por ezzepay';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setAprobada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->PublicId);
                return $return;

                break;

            case
            "CANCELED":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Rechazada por ezzepay ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);

                return $return;
                break;
        }
    }

}
