<?php

/**
 * Clase Eukapay para manejar integraciones de pagos con el proveedor Eukapay.
 *
 * Este archivo contiene la implementación de la clase Eukapay, que permite
 * gestionar transacciones de pago y su confirmación con diferentes estados.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @author     Davison Valencia <davison.valencia@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-04-23
 */

namespace Backend\integrations\payment;

use Backend\dto\TransaccionProducto;

/**
 * Clase Eukapay.
 *
 * Esta clase maneja la integración con el proveedor de pagos Eukapay,
 * permitiendo gestionar transacciones y sus estados.
 */
class Eukapay
{

    /**
     * transaccionId relacionada con la solicitud deposito.
     *
     * @var string
     */
    var $transaccionId;

    /**
     * Resultado de la transacción proporcionado por el proveedor.
     *
     * @var string
     */
    var $status;

    /**
     * valor relacionado con la solicitud deposito.
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
     * Constructor de la clase Ezzepay.
     *
     * @param string $externoId     Identificador público del proveedor.
     * @param string $TransactionId Identificador de la transacción.
     * @param float  $valor         Monto de la transacción.
     * @param string $status        Estado inicial de la transacción.
     */
    public function __construct($transaccionId, $status, $externoId, $valor = '')
    {
        $this->transaccionId = $transaccionId;
        $this->externoId = $externoId;
        $this->status = $status;
        $this->valor = $valor;
    }

    /**
     * Confirma el estado de una transacción según los datos proporcionados.
     *
     * Este método procesa la confirmación de una transacción dependiendo del
     * estado actual (`PROGRESS`, `SUCCESS`, `CANCEL`) y realiza las acciones
     * correspondientes en el sistema.
     *
     * @param array $data Datos adicionales proporcionados por el proveedor.
     *
     * @return mixed Resultado de la operación de confirmación.
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
                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'P';

                // Comentario personalizado para el log
                $comentario = 'Pendiente por Eukapay';

                // Obtenemos la transaccion
                $TransaccionProducto = new TransaccionProducto($transaccion_id);
                $return = $TransaccionProducto->setPendiente($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);
                return $return;
                break;

            case "SUCCESS":
                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'A';

                // Comentario personalizado para el log
                $comentario = 'Aprobada por Eukapay';

                // Obtenemos la transaccion
                $TransaccionProducto = new TransaccionProducto($transaccion_id);
                $return = $TransaccionProducto->setAprobada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->externoId);
                return $return;
                break;

            case "CANCEL":
                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Rechazada por Eukapay';

                // Obtenemos la transaccion
                $TransaccionProducto = new TransaccionProducto($transaccion_id);
                $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->externoId);
                return $return;
                break;
        }
    }
}
