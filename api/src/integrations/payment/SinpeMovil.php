<?php

/**
 * Clase SinpeMovil para gestionar integraciones de pagos con Sinpe Móvil.
 *
 * Esta clase permite manejar transacciones de pago, verificar su estado y realizar
 * acciones como aprobar, rechazar o marcar como pendientes las transacciones.
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
 * Clase SinpeMovil.
 *
 * Esta clase gestiona las integraciones de pagos con Sinpe Móvil, permitiendo
 * realizar operaciones como confirmar el estado de transacciones, aprobarlas,
 * rechazarlas o marcarlas como pendientes.
 */
class SinpeMovil
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
     * Constructor de la clase SinpeMovil.
     *
     * @param string $transaccionId ID de la transacción.
     * @param string $status        Estado inicial de la transacción.
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
     * Confirma el estado de la transacción y realiza las acciones correspondientes.
     *
     * Este método verifica el estado de la transacción y, dependiendo de su estado,
     * realiza acciones como marcarla como pendiente, aprobarla o rechazarla.
     *
     * @param mixed $t_value Datos adicionales proporcionados por el proveedor.
     *
     * @return string Mensaje indicando el resultado de la operación.
     */
    public function confirmation($t_value)
    {
        // ID Transaccion en nuestro sistema
        $transaccion_id = $this->transaccionId;

        try {
            $TransaccionProducto = new TransaccionProducto($transaccion_id);

            if ($_ENV['debug'] == true) {
                print_r($TransaccionProducto);
            }

            $monto = $TransaccionProducto->valor;

            if ($monto != $this->valor) {
                $this->status = "R";
                $comentario = 'Rechazada por valor de transferencia diferente a valor de solicitud';
            }

            // Tipo que genera el log (A: Automatico, M: Manual)
            $tipo_genera = 'A';

            // Valores que me trae el proveedor para auditoria
            $t_value = json_encode($t_value);

            switch ($this->status) {
                case "P":

                    // Asignamos variables por tipo de transaccion

                    // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                    $estado = 'P';

                    // Comentario personalizado para el log
                    $comentario = 'Pendiente por SinpeMovil';

                    // Obtenemos la transaccion

                    $TransaccionProducto = new TransaccionProducto($transaccion_id);

                    $return = $TransaccionProducto->setPendiente($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);
                    return $comentario;

                    break;

                case "A":

                    // Asignamos variables por tipo de transaccion

                    // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                    $estado = 'A';

                    // Comentario personalizado para el log
                    $comentario = 'Aprobada por SinpeMovil';

                    // Obtenemos la transaccion
                    $TransaccionProducto = new TransaccionProducto($transaccion_id);


                    $return = $TransaccionProducto->setAprobada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->externoId);
                    return $comentario;

                    break;

                case "R":

                    // Asignamos variables por tipo de transaccion

                    // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                    $estado = 'R';

                    // Obtenemos la transaccion

                    $TransaccionProducto = new TransaccionProducto($transaccion_id);

                    $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->externoId);

                    return $comentario;

                    break;
            }
        } catch (Exception $e) {
            $return = 'Transaccion no encontrada';
            return $return;
        }
    }
}
