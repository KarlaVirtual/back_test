<?php

/**
 * Clase Intern para manejar integraciones de pago.
 *
 * Esta clase se encarga de gestionar las confirmaciones de transacciones
 * realizadas a través de un proveedor de servicios de pago.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-23
 */

namespace Backend\integrations\payment;

use Backend\dto\TransaccionProducto;

/**
 * Clase que representa la integración de pagos interna.
 *
 * Esta clase se utiliza para manejar las confirmaciones de transacciones
 * realizadas a través de un proveedor de servicios de pago.
 */
class Intern
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
     * @var integer
     */
    var $usuario_id;

    /**
     * Identificador del documento relacionado con la transacción.
     *
     * @var integer
     */
    var $documento_id;

    /**
     * Valor monetario de la transacción.
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
     * Resultado de la transacción.
     *
     * @var string
     */
    var $result;

    /**
     * Constructor de la clase Intern.
     *
     * Inicializa los valores de la transacción, incluyendo el identificador de la factura,
     * el usuario asociado, el documento relacionado, el valor monetario, el código de control
     * y el resultado de la transacción.
     *
     * @param string  $invoice      Identificador de la factura o transacción.
     * @param integer $usuario_id   Identificador del usuario asociado a la transacción.
     * @param integer $documento_id Identificador del documento relacionado con la transacción.
     * @param float   $valor        Valor monetario de la transacción.
     * @param string  $control      Código de control para la transacción.
     * @param string  $result       Resultado de la transacción.
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
     * Confirma el estado de una transacción con el proveedor de servicios de pago.
     *
     * Este método procesa la respuesta del proveedor de servicios de pago y actualiza
     * el estado de la transacción en el sistema según el estado recibido.
     *
     * @param mixed $t_value Datos adicionales proporcionados por el proveedor para auditoría.
     *
     * @return mixed Resultado de la operación de confirmación, dependiendo del estado de la transacción.
     */
    public function confirmation($t_value)
    {
        // Convertimos a las variables de nuestro sistema

        // ID Transaccion en nuestro sistema
        $transaccion_id = $this->invoice;

        // Tipo que genera el log (A: Automatico, M: Manual)
        $tipo_genera = 'A';


        // Valores que me trae el proveedor para auditoria
        $t_value = json_encode($t_value);
        $PAYPHONESERVICES = new PAYPHONESERVICES();
        $response1 = $PAYPHONESERVICES->confirm($this->documento_id, $this->invoice);

        if ($_ENV['debug']) {
            print_r($response1);
        }

        $response = json_decode($response1);
        switch ($response->statusCode) {
            case 'pending':

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'P';

                // Comentario personalizado para el log
                $comentario = 'Pendiente por PayPhone ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setPendiente($transaccion_id, $tipo_genera, $estado, $comentario, $response1);
                return $return;

                break;

            case "3":

                // Asignamos variables por tipo de transaccion

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

                // Asignamos variables por tipo de transaccion

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
