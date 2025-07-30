<?php

/**
 * Clase SmartFastPay
 *
 * Esta clase gestiona las integraciones de pago con el proveedor SmartFastPay.
 * Proporciona métodos para manejar diferentes estados de transacciones como
 * progreso, éxito, cancelación y reembolso.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-25
 */

namespace Backend\integrations\payment;

use Backend\dto\Usuario;
use Backend\dto\CuentaCobro;
use Backend\dto\PaisMandante;
use Backend\dto\TransaccionProducto;
use Exception;

/**
 * Clase que gestiona las integraciones de pago con el proveedor SmartFastPay.
 * Proporciona métodos para manejar diferentes estados de transacciones como
 * progreso, éxito, cancelación y reembolso.
 */
class SmartFastPay
{
    /**
     * ID de la transacción.
     *
     * @var string
     */
    var $transaccionId;

    /**
     * Estado de la transacción.
     *
     * @var string
     */
    var $status;

    /**
     * Valor de la transacción.
     *
     * @var string
     */
    var $valor;

    /**
     * ID externo proporcionado por el proveedor.
     *
     * @var string
     */
    var $externoId;

    /**
     * Constructor de la clase SmartFastPay.
     *
     * @param string $transaccionId ID de la transacción.
     * @param string $status        Estado de la transacción.
     * @param string $externoId     ID externo proporcionado por el proveedor.
     * @param string $valor         Valor de la transacción (opcional).
     */
    public function __construct($transaccionId, $status, $externoId, $valor = '')
    {
        $this->transaccionId = $transaccionId;
        $this->externoId = $externoId;
        $this->status = $status;
        $this->valor = $valor;
    }

    /**
     * Maneja la confirmación de la transacción según su estado.
     *
     * @param mixed $t_value Valores proporcionados por el proveedor para auditoría.
     *
     * @return mixed Resultado del manejo de la transacción.
     */
    public function confirmation($t_value)
    {
        // ID Transaccion en nuestro sistema
        $transaccion_id = $this->transaccionId;

        // Tipo que genera el log (A: Automatico, M: Manual)
        $tipo_genera = 'A';

        // Valores que me trae el proveedor para auditoria
        $t_value = json_encode($t_value);

        switch ($this->status) {
            case "PROGRESS":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'P';

                // Comentario personalizado para el log
                $comentario = 'Pendiente por SmartFastPay';

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
                $comentario = 'Aprobada por SmartFastPay';

                // Obtenemos la transaccion
                $TransaccionProducto = new TransaccionProducto($transaccion_id);


                $return = $TransaccionProducto->setAprobada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->externoId);
                return $return;

                break;

            case "CANCEL":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Rechazada por SmartFastPay';

                // Obtenemos la transaccion
                try {
                    $TransaccionProducto = new TransaccionProducto($transaccion_id);
                    $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->externoId);

                    return $return;
                } catch (Exception $e) {
                }

                break;

            case "REFUNDED":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Reembolsado por SmartFastPay';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->documento_id);

                return $return;
                break;
        }
    }

}
