<?php

/**
 * Clase PayCIPS para manejar la integración con el proveedor de pagos PayCIPS.
 *
 * Este archivo contiene la lógica para procesar confirmaciones de transacciones
 * y registrar los resultados en el sistema. La clase incluye métodos para manejar
 * diferentes estados de transacciones y registrar auditorías.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations\Payment
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-25
 */

namespace Backend\Integrations\Payment;

use Backend\dto\TransaccionProducto;
use Exception;

/**
 * Clase PayCIPS
 *
 * Esta clase maneja la integración con el proveedor de pagos PayCIPS,
 * permitiendo procesar transacciones y registrar auditorías.
 */
class PayCIPS
{
    /**
     * Identificador de la factura o transacción.
     *
     * @var string
     */
    var $invoice;

    /**
     * Identificador del usuario asociado a la transacción.
     *
     * @var string
     */
    var $usuario_id;

    /**
     * Identificador del documento relacionado con la transacción.
     *
     * @var string
     */
    var $documento_id;

    /**
     * Valor de la transacción.
     *
     * @var float
     */
    var $valor;

    /**
     * Código de control para la transacción.
     *
     * @var string
     */
    var $control;

    /**
     * Resultado de la transacción proporcionado por el proveedor.
     *
     * @var string
     */
    var $result;

    /**
     * Constructor de la clase PayCIPS.
     *
     * @param mixed $invoice      ID de la factura o transacción.
     * @param mixed $usuario_id   ID del usuario asociado a la transacción.
     * @param mixed $documento_id ID del documento relacionado con la transacción.
     * @param mixed $valor        Valor de la transacción.
     * @param mixed $control      Código de control para la transacción.
     * @param mixed $result       Resultado de la transacción proporcionado por el proveedor.
     */
    public function __construct($invoice, $usuario_id, $documento_id, $valor, $control, $result)
    {
        $this->invoice = $invoice;
        $this->usuario_id = $usuario_id;
        $this->documento_id = $documento_id;
        $this->valor = $valor;
        $this->control = $control;
        $this->result = $result;
    }

    /**
     * Procesa la confirmación de una transacción.
     *
     * Este metodo evalúa el resultado de la transacción proporcionado por el proveedor
     * y actualiza el estado de la transacción en el sistema. Maneja diferentes casos
     * según el código de resultado recibido.
     *
     * @return void
     */
    public function confirmation()
    {
        // ID Transaccion en nuestro sistema
        $transaccion_id = $this->invoice;

        // Tipo que genera el log (A: Automatico, M: Manual)
        $tipo_genera = 'A';


        // Valores que me trae el proveedor para auditoria
        $t_value = json_encode($this);


        if (strtolower($this->result) === "00") {
            $this->result = 'AprobadaVS';
        }

        switch ($this->result) {
            case '51':

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Rechazado por PayCIPS, Fondos insuficientes ';


                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);

                break;

            case 'AprobadaVS':

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'A';

                // Comentario personalizado para el log
                $comentario = 'Aprobada por PayCIPS ';

                // Obtenemos la transaccion
                try {
                    $TransaccionProducto = new TransaccionProducto($transaccion_id);

                    if ($TransaccionProducto->getValor() != $this->valor) {
                        $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario . ' VALOR DE API DIFERENTE AL VALOR REGISTRADO ', $t_value, $this->documento_id);
                    } else {
                        $TransaccionProducto->setAprobada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->documento_id);
                    }
                } catch (Exception $e) {
                }

                break;

            case '01':

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Rechazada por PayCIPS ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);

                break;

            default:

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Rechazada por PayCIPS ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);

                break;
        }
    }

}
