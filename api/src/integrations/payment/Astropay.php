<?php

/**
 * Clase Astropay para manejar integraciones de pagos con el proveedor Astropay.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-23
 */

namespace Backend\Integrations\Payment;

use Backend\dto\TransaccionProducto;

/**
 * Clase Astropay para manejar integraciones de pagos con el proveedor Astropay.
 *
 * Este archivo contiene la implementación de la clase Astropay, que permite procesar
 * transacciones de pago y registrar su estado en el sistema.
 */
class Astropay
{
    /**
     * ID de la factura asociada a la transacción.
     *
     * @var mixed
     */
    var $invoice;

    /**
     * ID del usuario asociado a la transacción.
     *
     * @var mixed
     */
    var $usuario_id;

    /**
     * ID del documento asociado a la transacción.
     *
     * @var mixed
     */
    var $documento_id;

    /**
     * Valor de la transacción.
     *
     * @var mixed
     */
    var $valor;

    /**
     * Código de control para la transacción.
     *
     * @var mixed
     */
    var $control;

    /**
     * Resultado de la transacción (7: Pendiente, 8: Rechazada, 9: Aprobada).
     *
     * @var mixed
     */
    var $result;

    /**
     * Constructor de la clase Astropay.
     *
     * @param mixed $invoice      ID de la factura.
     * @param mixed $usuario_id   ID del usuario.
     * @param mixed $documento_id ID del documento.
     * @param mixed $valor        Valor de la transacción.
     * @param mixed $control      Código de control.
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
     * Procesa la confirmación de la transacción según el resultado proporcionado.
     *
     * Este metodo evalúa el resultado de la transacción y actualiza su estado
     * en el sistema, registrando los datos necesarios para auditoría.
     *
     * @return void
     */
    public function confirmation()
    {
        // ID Transacción en nuestro sistema
        $transaccion_id = $this->invoice;

        // Tipo que genera el log (A: Automático, M: Manual)
        $tipo_genera = 'A';

        // Valores que me trae el proveedor para auditoría
        $t_value = json_encode($this);

        switch ($this->result) {
            case 7:
                // Estado especial por proveedor (A: Aprobado, P: Pendiente, R: Rechazada)
                $estado = 'P';
                // Comentario personalizado para el log
                $comentario = 'Pendiente por Astropay ';
                // Obtenemos la transacción
                $TransaccionProducto = new TransaccionProducto($transaccion_id);
                $TransaccionProducto->setPendiente($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);
                break;

            case 9:
                // Estado especial por proveedor (A: Aprobado, P: Pendiente, R: Rechazada)
                $estado = 'A';
                // Comentario personalizado para el log
                $comentario = 'Aprobada por Astropay ';
                // Obtenemos la transacción
                $TransaccionProducto = new TransaccionProducto($transaccion_id);
                $TransaccionProducto->setAprobada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->documento_id);
                break;

            case 8:
                // Estado especial por proveedor (A: Aprobado, P: Pendiente, R: Rechazada)
                $estado = 'R';
                // Comentario personalizado para el log
                $comentario = 'Rechazada por Astropay ';
                // Obtenemos la transacción
                $TransaccionProducto = new TransaccionProducto($transaccion_id);
                $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);
                break;
        }
    }

}
