<?php

/**
 * Clase Starpago para gestionar integraciones de pagos con el proveedor Starpago.
 *
 * Este archivo contiene la implementación de la clase Starpago, que permite manejar
 * transacciones de pago, incluyendo su confirmación y actualización de estado.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-25
 */

namespace Backend\integrations\payment;

use Backend\dto\Usuario;
use Backend\dto\CuentaCobro;
use Backend\dto\PaisMandante;
use Backend\dto\TransaccionProducto;
use Exception;

/**
 * Clase que representa la integración con el proveedor de pagos Starpago.
 *
 * Esta clase permite gestionar transacciones de pago, incluyendo su confirmación
 * y actualización de estado, según los datos proporcionados por el proveedor.
 */
class Starpago
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
     * Constructor de la clase Starpago.
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
     * Confirma el estado de la transacción y actualiza su registro.
     *
     * Este método procesa la confirmación de una transacción según el estado
     * proporcionado por el proveedor y actualiza el registro correspondiente
     * en el sistema.
     *
     * @param mixed $t_value Datos adicionales proporcionados por el proveedor.
     *
     * @return mixed Resultado de la operación de confirmación.
     * @throws Exception Si ocurre un error durante el proceso.
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
            case "PROGRESS_PENDING":

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'P';

                // Comentario personalizado para el log
                $comentario = 'Pendiente por Starpago';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setPendiente($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);
                return $return;

                break;

            case "SUCCESS":

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'A';

                // Comentario personalizado para el log
                $comentario = 'Aprobada por Starpago';

                // Obtenemos la transaccion
                $TransaccionProducto = new TransaccionProducto($transaccion_id);


                $return = $TransaccionProducto->setAprobada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->externoId);
                return $return;

                break;

            case "CANCEL":

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Rechazada por Starpago';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->externoId);

                return $return;

                break;

            case "REFUNDED":

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Reembolsado por Starpago';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->documento_id);

                return $return;
                break;
        }
    }

}
