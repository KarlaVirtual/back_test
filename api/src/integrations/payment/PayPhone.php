<?php

/**
 * Clase PayPhone para la integración con el servicio de pagos PayPhone.
 *
 * Esta clase permite realizar confirmaciones de transacciones y manejar
 * los estados de las mismas según la respuesta del proveedor.
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
 * Clase principal para la integración con el servicio de pagos PayPhone.
 *
 * Esta clase contiene métodos para confirmar transacciones y manejar
 * los estados de las mismas según la respuesta del proveedor.
 */
class PayPhone
{
    /**
     * Identificador de la factura o transacción.
     *
     * @var mixed
     */
    var $invoice;

    /**
     * Identificador del usuario asociado a la transacción.
     *
     * @var mixed
     */
    var $usuario_id;

    /**
     * Identificador del documento relacionado con la transacción.
     *
     * @var mixed
     */
    var $documento_id;

    /**
     * Valor monetario de la transacción.
     *
     * @var mixed
     */
    var $valor;

    /**
     * Código de control de la transacción.
     *
     * @var mixed
     */
    var $control;

    /**
     * Resultado de la transacción.
     *
     * @var mixed
     */
    var $result;

    /**
     * Constructor de la clase PayPhone.
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
     * Confirma el estado de una transacción con el proveedor PayPhone.
     *
     * Este método realiza la validación del estado de la transacción
     * y actualiza el estado en el sistema según la respuesta del proveedor.
     *
     * @param mixed $t_value Datos adicionales de la transacción.
     *
     * @return mixed Resultado de la operación de confirmación.
     */
    public function confirmation($t_value)
    {
        // Convertimos a las variables de nuestro sistema

        // ID Transaccion en nuestro sistema
        $transaccion_id = $this->invoice;

        // Tipo que genera el log (A: Automatico, M: Manual)
        $tipo_genera = 'A';

        // Valores que me trae el proveedor para auditoría
        $t_value = json_encode($t_value);
        $PAYPHONESERVICES = new PAYPHONESERVICES();
        $response1 = $PAYPHONESERVICES->confirm($this->documento_id, $this->invoice);

        if ($_ENV['debug']) {
            print_r($response1);
        }

        $response = json_decode($response1);
        switch ($response->statusCode) {
            case 'pending':
                // Estado especial por proveedor (A: Aprobado, P: Pendiente, R: Rechazada)
                $estado = 'P';
                $comentario = 'Pendiente por PayPhone ';
                $TransaccionProducto = new TransaccionProducto($transaccion_id);
                $return = $TransaccionProducto->setPendiente($transaccion_id, $tipo_genera, $estado, $comentario, $response1);
                return $return;

                break;

            case "3":

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'A';

                // Comentario personalizado para el log
                $comentario = 'Aprobada por PayPhone ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setAprobada($transaccion_id, $tipo_genera, $estado, $comentario, $response1, $this->documento_id);
                return $return;

                break;

            case "2":

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Rechazada por PayPhone ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $response1);

                return $return;
                break;
        }
    }

}
