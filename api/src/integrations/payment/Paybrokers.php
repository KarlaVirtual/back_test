<?php

/**
 * Clase Paybrokers para la integración con el proveedor de pagos Paybrokers.
 *
 * Esta clase permite manejar las transacciones realizadas a través de Paybrokers,
 * incluyendo la confirmación de pagos y la actualización de estados en el sistema.
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
 * Clase Paybrokers.
 *
 * Esta clase se utiliza para manejar integraciones de pagos con el proveedor Paybrokers.
 */
class Paybrokers
{
    /**
     * Constructor de la clase Paybrokers.
     *
     * @param string $PublicId        Identificador público del proveedor.
     * @param string $MerchantSalesID ID de la transacción en el sistema del comercio.
     * @param float  $Amount          Monto de la transacción.
     * @param string $status          Estado de la transacción proporcionado por el proveedor.
     */
    public function __construct($PublicId, $MerchantSalesID, $Amount, $status)
    {
        $this->PublicId = $PublicId;
        $this->MerchantSalesID = $MerchantSalesID;
        $this->Amount = $Amount;
        $this->status = $status;
    }

    /**
     * Confirma el estado de una transacción y actualiza los registros en el sistema.
     *
     * Este metodo procesa los datos proporcionados por el proveedor de pagos y
     * actualiza el estado de la transacción en el sistema según el estado recibido.
     *
     * @param array $data Datos adicionales proporcionados por el proveedor para auditoría.
     *
     * @return mixed Resultado de la operación de actualización del estado.
     */
    public function confirmation($data)
    {
        // Convertimos a las variables de nuestro sistema

        // ID Transaccion en nuestro sistema
        $transaccion_id = $this->MerchantSalesID;

        // Tipo que genera el log (A: Automatico, M: Manual)
        $tipo_genera = 'A';


        // Valores que me trae el proveedor para auditoria
        $t_value = json_encode($data);

        switch ($this->status) {
            case 'WAITING':

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'P';

                // Comentario personalizado para el log
                $comentario = 'Pendiente por Paybrokers';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setPendiente($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);
                return $return;

                break;

            case "PAID":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'A';

                // Comentario personalizado para el log
                $comentario = 'Aprobada por Paybrokers';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setAprobada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->PublicId);
                return $return;

                break;

            case "CANCELED":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Rechazada por Paybrokers ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);

                return $return;
                break;
        }
    }

}
