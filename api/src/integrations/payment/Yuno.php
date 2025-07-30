<?php

/**
 * Clase Yuno para manejar integraciones de pagos.
 *
 * Esta clase se encarga de gestionar las transacciones de pago
 * y su estado a través de diferentes métodos.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-25
 */

namespace Backend\integrations\payment;

use Backend\dto\TransaccionProducto;

/**
 * Clase que representa la integración con el proveedor de pagos Yuno.
 *
 * Esta clase permite gestionar transacciones de pago, incluyendo su creación,
 * actualización de estado y confirmación, utilizando los métodos proporcionados
 * por el proveedor.
 */
class Yuno
{
    /**
     * ID de la transacción.
     *
     * @var string
     */
    var $transaccion_id;

    /**
     * Estado de la transacción.
     *
     * @var string
     */
    var $status;

    /**
     * ID externo proporcionado por el proveedor.
     *
     * @var string
     */
    var $externo_Id;

    /**
     * Constructor de la clase Yuno.
     *
     * @param string $transaccion_id ID de la transacción.
     * @param string $status         Estado de la transacción.
     * @param string $externo_Id     ID externo proporcionado por el proveedor.
     */
    public function __construct($transaccion_id, $status, $externo_Id)
    {
        $this->transaccion_id = $transaccion_id;
        $this->externo_Id = $externo_Id;
        $this->result = $status;
    }

    /**
     * Método para confirmar el estado de una transacción.
     *
     * Este método procesa el estado de la transacción y realiza
     * las acciones correspondientes según el estado recibido.
     *
     * @param array $t_value Valores proporcionados por el proveedor para auditoría.
     *
     * @return mixed Resultado de la operación según el estado de la transacción.
     */
    public function confirmation($t_value)
    {
        // ID Transaccion en nuestro sistema
        $transaccion_id = $this->transaccion_id;

        // Tipo que genera el log (A: Automatico, M: Manual)
        $tipo_genera = 'A';

        // Valores que me trae el proveedor para auditoria
        $t_value = json_encode($t_value);

        switch ($this->result) {
            case "PROGRESS":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'P';

                // Comentario personalizado para el log
                $comentario = 'Pendiente por Yuno';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setPendiente($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);
                return $return;

                break;

            case "SUCCESS":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'A';

                // Comentario personalizado para el log
                $comentario = 'Aprobada por Yuno';

                // Obtenemos la transaccion
                $TransaccionProducto = new TransaccionProducto($transaccion_id);


                $return = $TransaccionProducto->setAprobada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->externo_Id);
                return $return;

                break;

            case "CANCEL":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Rechazada por Yuno';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->externo_Id);

                return $return;

                break;
        }
    }

}
