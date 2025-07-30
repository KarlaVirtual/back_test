<?php

/**
 * Clase Tupay para gestionar integraciones de pagos con TuPay.
 *
 * Esta clase permite manejar transacciones de pago, incluyendo la confirmación
 * de estados como pendiente, aprobado o rechazado, utilizando la integración
 * con TuPay.
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
 * Clase Tupay para gestionar integraciones de pagos con TuPay.
 *
 * Proporciona métodos para manejar transacciones de pago, incluyendo la confirmación
 * de estados como pendiente, aprobado o rechazado.
 */
class Tupay
{
    /**
     * Identificador de la factura o transacción.
     *
     * @var mixed
     */
    public $invoice;

    /**
     * Identificador del usuario asociado a la transacción.
     *
     * @var mixed
     */
    public $usuario_id;

    /**
     * Identificador del documento relacionado con la transacción.
     *
     * @var mixed
     */
    public $documento_id;

    /**
     * Valor monetario de la transacción.
     *
     * @var mixed
     */
    public $valor;

    /**
     * Código de control de la transacción.
     *
     * @var mixed
     */
    public $control;

    /**
     * Resultado del estado de la transacción.
     *
     * @var mixed
     */
    public $result;

    /**
     * Constructor de la clase Tupay.
     *
     * @param mixed $invoice      Identificador de la factura.
     * @param mixed $usuario_id   Identificador del usuario.
     * @param mixed $documento_id Identificador del documento.
     * @param mixed $valor        Valor de la transacción.
     * @param mixed $control      Código de control de la transacción.
     * @param mixed $result       Resultado de la transacción.
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
     * Confirma el estado de la transacción.
     *
     * Según el resultado de la transacción (`$this->result`), actualiza el estado
     * de la transacción como pendiente, aprobada o rechazada.
     *
     * @param mixed $t_value Información adicional de la transacción.
     *
     * @return mixed Resultado de la operación de confirmación.
     */
    public function confirmation($t_value)
    {
        $transaccion_id = $this->invoice;
        $tipo_genera = 'A'; // A: Automático
        $t_value = json_encode($t_value);
        $this->result = strtolower($this->result);

        switch ($this->result) {
            case 'Pending_Tupay':
                $estado = 'P'; // Pendiente
                $comentario = 'Pendiente por TuPay';
                $TransaccionProducto = new TransaccionProducto($transaccion_id);
                return $TransaccionProducto->setPendiente($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);

            case "paid":
                $estado = 'A'; // Aprobado
                $comentario = 'Aprobada por TuPay';
                $TransaccionProducto = new TransaccionProducto($transaccion_id);
                return $TransaccionProducto->setAprobada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->documento_id);

            case "failed":
                $estado = 'R'; // Rechazado
                $comentario = 'Rechazada por TuPay';
                $TransaccionProducto = new TransaccionProducto($transaccion_id);
                return $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);
        }
    }
}