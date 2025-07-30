<?php
/**
 * Clase ApcoPay para manejar integraciones de pagos con el proveedor ApcoPay.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-23
 */

namespace Backend\integrations\payment;

use Backend\dto\TransaccionProducto;

/**
 * Clase ApcoPay para manejar integraciones de pagos con el proveedor ApcoPay.
 *
 * Esta clase permite procesar confirmaciones de transacciones y registrar
 * los estados correspondientes en el sistema.
 */
class ApcoPay
{
    /**
     * ID de la factura proporcionada por el proveedor.
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
     * ID del documento relacionado con la transacción.
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
     * Resultado de la transacción proporcionado por el proveedor.
     *
     * @var mixed
     */
    var $result;

    /**
     * Constructor de la clase ApcoPay.
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
     * Procesa la confirmación de una transacción.
     *
     * Según el resultado proporcionado por el proveedor, actualiza el estado
     * de la transacción en el sistema y registra un log correspondiente.
     *
     * @return mixed Resultado del procesamiento de la transacción.
     */
    public function confirmation()
    {
        print_r($this);

        // Convertimos a las variables de nuestro sistema
        $transaccion_id = $this->invoice; // ID Transacción en nuestro sistema
        $tipo_genera = 'A'; // Tipo que genera el log (A: Automático, M: Manual)
        $t_value = json_encode($this); // Valores que me trae el proveedor para auditoría

        switch ($this->result) {
            case 7:

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'P';

                // Comentario personalizado para el log
                $comentario = 'Pendiente por ApcoPay ';

                // Obtenemos la transaccion
                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setPendiente($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);
                return $return;

                break;

            case "OK":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'A';

                // Comentario personalizado para el log
                $comentario = 'Aprobada por ApcoPay ';

                // Obtenemos la transaccion
                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setAprobada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->documento_id);

                return $return;

                break;

            case "DECLINED":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Rechazada por ApcoPay ';

                // Obtenemos la transaccion
                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);

                return $return;
                break;
        }
    }

}
