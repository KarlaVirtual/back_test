<?php

/**
 * Clase para la integración con el proveedor de pagos Unlimint.
 *
 * Esta clase maneja la lógica de confirmación de transacciones y la interacción
 * con las entidades relacionadas, como usuarios, productos de transacción y países mandantes.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-25
 */

namespace Backend\integrations\payment;

use Backend\dto\Usuario;
use Backend\dto\PaisMandante;
use Backend\dto\TransaccionProducto;

/**
 * Clase Unlimint.
 *
 * Esta clase representa la integración con el proveedor de pagos Unlimint.
 * Contiene métodos para manejar la confirmación de transacciones y la lógica
 * relacionada con los usuarios, productos de transacción y países mandantes.
 */
class Unlimint
{
    /**
     * Identificador de la factura o transacción.
     *
     * @var mixed
     */
    var $invoice;

    /**
     * Resultado del estado de la transacción.
     *
     * @var string
     */
    var $result;

    /**
     * Monto de la transacción.
     *
     * @var float|string
     */
    var $amount;

    /**
     * Identificador del documento asociado.
     *
     * @var mixed
     */
    var $documento_id;

    /**
     * Constructor de la clase Unlimint.
     *
     * @param mixed        $invoice ID de la factura.
     * @param string       $result  Resultado inicial de la transacción.
     * @param mixed        $uid     ID del documento asociado.
     * @param float|string $amount  Monto de la transacción (opcional).
     */
    public function __construct($invoice, $result, $uid, $amount = '')
    {
        $this->invoice = $invoice;
        $this->documento_id = $uid;
        $this->result = $result;
        $this->amount = $amount;
    }

    /**
     * Método para confirmar el estado de una transacción.
     *
     * Este método valida el monto de la transacción, ajusta valores según la moneda,
     * y actualiza el estado de la transacción en función de los resultados obtenidos.
     *
     * @param array $t_value Datos adicionales proporcionados por el proveedor para auditoría.
     *
     * @return mixed Resultado de la operación de confirmación.
     */
    public function confirmation($t_value)
    {
        // ID Transaccion en nuestro sistema
        $transaccion_id = $this->invoice;

        if ($this->amount != '') {

            $TransaccionProducto = new TransaccionProducto($transaccion_id);
            $Usuario = new Usuario($TransaccionProducto->usuarioId);
            $moneda = $Usuario->moneda;

            $valor = $TransaccionProducto->valor;

            if ($moneda == 'GTQ' || $moneda == 'CRC') {
                $PaisMandante = new PaisMandante("", $Usuario->mandante, $Usuario->paisId);
                $this->amount = ($this->amount / $PaisMandante->trmUsd);

                $tolerancia = 0.09;
                if (abs($valor - $this->amount) > $tolerancia) {
                    $this->result = "CANCEL";
                }
            } else {

                if ($valor > $this->amount || $valor < $this->amount) {
                    $this->result = "CANCEL";
                }
            }
        }

        // Tipo que genera el log (A: Automatico, M: Manual)
        $tipo_genera = 'A';

        // Valores que me trae el proveedor para auditoria
        $t_value = json_encode($t_value);

        switch ($this->result) {
            case "PROGRESS_PENDING":

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'P';

                // Comentario personalizado para el log
                $comentario = 'Pendiente por Unlimint';

                // Obtenemos la transaccion
                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setPendiente($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);
                return $return;

                break;

            case "SUCCESS":

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'A';

                // Comentario personalizado para el log
                $comentario = 'Aprobada por Unlimint';

                // Obtenemos la transaccion
                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setAprobada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->documento_id);
                return $return;

                break;

            case "CANCEL":

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Rechazada por Unlimint';

                // Obtenemos la transaccion
                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->documento_id);

                return $return;

                break;

            case "REFUNDED":

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Reembolsado por Unlimint';

                // Obtenemos la transaccion
                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->documento_id);

                return $return;
                break;
        }
    }
}
