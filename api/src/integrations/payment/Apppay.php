<?php
/**
 * Clase Apppay para gestionar integraciones de pagos.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-23
 */

namespace Backend\integrations\payment;

use Backend\dto\TransaccionProducto;

/**
 * Clase Apppay para gestionar integraciones de pagos.
 *
 * Esta clase maneja la lógica de confirmación de transacciones
 * con diferentes estados (PROGRESS, SUCCESS, CANCEL) y utiliza
 * la clase TransaccionProducto para registrar los cambios en el sistema.
 */
class Apppay
{
    /**
     * ID de la transacción en el sistema.
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
     * Constructor de la clase Apppay.
     *
     * @param string $transaccion_id ID de la transacción.
     * @param string $status         Estado inicial de la transacción.
     * @param string $externo_Id     ID externo del proveedor.
     */
    public function __construct($transaccion_id, $status, $externo_Id)
    {
        $this->transaccion_id = $transaccion_id;
        $this->externo_Id = $externo_Id;
        $this->result = $status;
    }

    /**
     * Confirma el estado de una transacción.
     *
     * Este metodo procesa la confirmación de una transacción según su estado
     * (PROGRESS, SUCCESS, CANCEL) y registra los cambios en el sistema.
     *
     * @param mixed $t_value Datos adicionales proporcionados por el proveedor.
     *
     * @return mixed Resultado del registro de la transacción.
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
                $comentario = 'Pendiente por Apppay';

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
                $comentario = 'Aprobada por Apppay';

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
                $comentario = 'Rechazada por Apppay';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->externo_Id);

                return $return;

                break;
        }
    }

}
