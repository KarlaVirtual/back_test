<?php
/**
 * Clase Kashio para manejar integraciones de pagos.
 *
 * Esta clase se utiliza para gestionar transacciones de pago y su confirmación
 * a través de diferentes estados como "PROGRESS", "SUCCESS", "CANCEL" y "REFUNDED".
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-23
 */

namespace Backend\integrations\payment;

use Backend\dto\CuentaCobro;
use Backend\dto\TransaccionProducto;
use Exception;

/**
 * Clase Kashio.
 *
 * Esta clase gestiona las integraciones de pagos, incluyendo la confirmación
 * de transacciones y el manejo de diferentes estados de las mismas.
 */
class Kashio
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
     * Constructor de la clase Kashio.
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
     * Confirma el estado de una transacción.
     *
     * Este método verifica si la transacción es un pago saliente (payout) o
     * procesa la transacción según su estado actual.
     *
     * @param mixed $t_value Datos adicionales proporcionados por el proveedor.
     *
     * @return string Resultado de la confirmación en formato JSON.
     */
    public function confirmation($t_value)
    {
        // ID Transacción en nuestro sistema
        $transaccion_id = $this->transaccionId;

        $isPayout = false;
        try {
            $CuentaCobro = new CuentaCobro("", $transaccion_id);
            $isPayout = true;
        } catch (Exception $e) {
            $isPayout = false;
        }

        if ($isPayout) {
            $return = array(
                "result" => 'ISPAYOUT'
            );
            return json_encode($return);
        } else {
            // Tipo que genera el log (A: Automático, M: Manual)
            $tipo_genera = 'A';

            // Valores que me trae el proveedor para auditoría
            $t_value = json_encode($t_value);

            switch ($this->status) {
                case "PROGRESS":

                    // Asignamos variables por tipo de transaccion

                    // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                    $estado = 'P';

                    // Comentario personalizado para el log
                    $comentario = 'Pendiente por Kashio';

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
                    $comentario = 'Aprobada por Kashio';

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
                    $comentario = 'Rechazada por Kashio';

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
                    $comentario = 'Reembolsado por Kashio';

                    // Obtenemos la transaccion

                    $TransaccionProducto = new TransaccionProducto($transaccion_id);

                    $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->externoId);

                    return $return;
                    break;
            }
        }
    }
}
