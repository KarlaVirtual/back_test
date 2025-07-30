<?php

/**
 * Clase Aninda para manejar integraciones de pagos con el proveedor Aninda.
 *
 * Este archivo contiene la implementación de la clase Aninda, que permite
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

use Exception;
use Backend\dto\TransaccionProducto;
use Backend\mysql\TransaccionProductoMySqlDAO;

/**
 * Clase Aninda.
 *
 * Esta clase maneja la integración con el proveedor de pagos Aninda,
 * permitiendo gestionar transacciones y sus estados.
 */
class Aninda
{

    /**
     * TransaccionId.
     *
     * @var string $transaccionId Relacionada con la solicitud deposito
     */
    var $transaccionId;

    /**
     * Resultado.
     *
     * @var string $status De la transacción proporcionado por el proveedor
     */
    var $status;

    /**
     * Valor.
     *
     * @var string $valor Relacionado con la solicitud deposito
     */
    var $valor;

    /**
     * ID externo.
     *
     * @var string $externoId Proporcionado por el proveedor
     */
    var $externoId;

    /**
     * Constructor de la clase Aninda.
     *
     * @param string $externoId     Identificador público del proveedor.
     * @param string $transaccionId Identificador de la transacción.
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
     * @param array $t_value Datos adicionales proporcionados por el proveedor.
     *
     * @return mixed Resultado de la operación de confirmación.
     */
    public function confirmation($t_value)
    {
        // ID Transaccion en nuestro sistema
        $transaccion_id = $this->transaccionId;

        if ($this->valor < 0) {
            $this->status = 'CANCEL';
        } else {
            $this->checkValue($transaccion_id);
        }

        // Tipo que genera el log (A: Automatico, M: Manual)
        $tipo_genera = 'A';

        // Valores que me trae el proveedor para auditoria
        $t_value = json_encode($t_value);

        switch ($this->status) {
            case "PROGRESS":
                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'P';

                // Comentario personalizado para el log
                $comentario = 'Pendiente por Aninda';

                // Obtenemos la transaccion
                $TransaccionProducto = new TransaccionProducto($transaccion_id);
                $return = $TransaccionProducto->setPendiente($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);
                return $return;
                break;

            case "SUCCESS":
                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'A';

                // Comentario personalizado para el log
                $comentario = 'Aprobada por Aninda';

                // Obtenemos la transaccion
                $TransaccionProducto = new TransaccionProducto($transaccion_id);
                $return = $TransaccionProducto->setAprobada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->externoId);
                return $return;
                break;

            case "CANCEL":
                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Rechazada por Aninda';

                // Obtenemos la transaccion
                $TransaccionProducto = new TransaccionProducto($transaccion_id);
                $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->externoId, true);
                return $return;
                break;
        }
    }

    /**
     * Verificar el valor de una transacción según los datos proporcionados.
     *
     * Este método actualiza el valor de una transacción durante la confirmación
     * dependiendo de si el valor es diferente al original y realiza las acciones
     * correspondientes en el sistema.
     *
     * @param array $transaccion_id Transaccion_id original.
     *
     * @return mixed Resultado de la operación de confirmación.
     */
    public function checkValue($transaccion_id)
    {
        $TransaccionProducto = new TransaccionProducto($transaccion_id);

        if ($TransaccionProducto->valor != $this->valor) {
            try {
                $valorOriginal = $TransaccionProducto->valor;
                $valorFinal = $TransaccionProducto->valor + $TransaccionProducto->impuesto;

                $diferencia = $valorFinal - $valorOriginal;
                $porcentaje = ($diferencia / $valorOriginal) * 100;

                $totalTax = $this->valor * ($porcentaje / 100);
                $valorTotal = $this->valor * (1 - $porcentaje / 100);
            } catch (Exception $e) {
                $totalTax = 0;
                $valorTotal = $this->valor;
            }

            $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
            $Transaction = $TransaccionProductoMySqlDAO->getTransaction();

            $TransaccionProducto->setValor($valorTotal);
            $TransaccionProducto->setImpuesto($totalTax);

            $transproductoId = $TransaccionProductoMySqlDAO->update($TransaccionProducto);
            $Transaction->commit();
        }
    }
}
