<?php

/**
 * Clase Internpay para la integración con el sistema de pagos Internpay.
 *
 * Esta clase maneja la confirmación de transacciones y la actualización de estados
 * en el sistema interno basado en las respuestas del proveedor de pagos.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-23
 */

namespace Backend\integrations\payment;

use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\TransaccionProducto;
use Backend\integrations\payment\INTERNPAYSERVICES;

/**
 * Clase Internpay para manejar la integración con el sistema de pagos Internpay.
 *
 * Esta clase incluye métodos para confirmar transacciones y actualizar estados
 * en el sistema interno basado en las respuestas del proveedor de pagos.
 */
class Internpay
{


    /**
     * Identificador de la factura o transacción.
     *
     * @var string
     */
    var $invoice;

    /**
     * Resultado de la transacción.
     *
     * @var string
     */
    var $result;

    /**
     * Identificador externo proporcionado por el sistema de pagos.
     *
     * @var string
     */
    var $externo_id;

    /**
     * Constructor de la clase Internpay.
     *
     * Inicializa los valores de la factura, el resultado de la transacción
     * y el identificador externo proporcionado por el sistema de pagos.
     *
     * @param string $invoice Identificador de la factura o transacción.
     * @param string $result  Resultado de la transacción.
     * @param string $uid     Identificador externo proporcionado por el sistema de pagos.
     */
    public function __construct($invoice, $result, $uid)
    {
        $this->invoice = $invoice;
        $this->externo_id = $uid;
        $this->result = $result;
    }

    /**
     * Confirma una transacción y actualiza su estado en el sistema interno.
     *
     * Este método interactúa con el sistema de pagos Internpay para confirmar
     * el estado de una transacción y actualiza los registros internos en base
     * a la respuesta del proveedor.
     *
     * @param mixed   $t_value Valores adicionales proporcionados por el proveedor para auditoría.
     * @param boolean $get     Indica si se debe realizar una solicitud GET al proveedor para confirmar el estado.
     *
     * @return string JSON con el resultado de la operación y la URL de redirección.
     */
    public function confirmation($t_value, $get = false)
    {
        // ID Transaccion en nuestro sistema
        $transaccion_id = $this->invoice;

        $TransaccionProducto = new TransaccionProducto($transaccion_id);
        $Usuario = new Usuario($TransaccionProducto->usuarioId);
        $Mandante = new Mandante($Usuario->mandante);

        $returnPath = '';

        if ($get) {
            $data = array();
            $data['id'] = $this->externo_id;
            $data['clientTxId'] = $transaccion_id;

            $path = '/api/button/V2/Confirm';
            $INTERNPAYSERVICES = new INTERNPAYSERVICES();
            $respuesta = $INTERNPAYSERVICES->connectionPOST($data, $path, $Usuario->mandante, $Usuario->paisId);

            $data = json_decode($respuesta);
            $status = $data->transactionStatus;

            if ($status == 'Approved') {
                $status = 'SUCCESS';
            } else {
                if ($status == 'Canceled') {
                    $status = 'CANCEL';
                } else {
                    $status = 'PROGRESS';
                }
            }

            $this->result = $status;

            if (isset($data->messageCode) && $data->messageCode == 16) {
                $returnPath = '?id=300038';
            }
        }

        // Tipo que genera el log (A: Automatico, M: Manual)
        $tipo_genera = 'A';

        // Valores que me trae el proveedor para auditoria
        $t_value = json_encode($t_value);

        switch ($this->result) {
            case "PROGRESS_PENDING":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'P';

                // Comentario personalizado para el log
                $comentario = 'Pendiente por Internpay';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setPendiente($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);
                $return = json_decode($return);

                $data = array();
                $data['result'] = $return->result;
                $data['redirect'] = $Mandante->baseUrl . "gestion/deposito/pendiente";

                return json_encode($data);

                break;

            case "SUCCESS":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'A';

                // Comentario personalizado para el log
                $comentario = 'Aprobada por Internpay';

                // Obtenemos la transaccion
                $TransaccionProducto = new TransaccionProducto($transaccion_id);


                $return = $TransaccionProducto->setAprobada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->externo_id);
                $return = json_decode($return);

                $data = array();
                $data['result'] = $return->result;
                $data['redirect'] = $Mandante->baseUrl . "gestion/deposito/correcto";

                return json_encode($data);

                break;

            case "CANCEL":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Rechazada por Internpay';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->externo_id);
                $return = json_decode($return);

                $data = array();
                $data['result'] = $return->result;
                $data['redirect'] = $Mandante->baseUrl . "gestion/deposito/error" . $returnPath;

                return json_encode($data);

                break;

            case "REFUNDED":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Reembolsado por Internpay';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->externo_id);
                $return = json_decode($return);

                $data = array();
                $data['result'] = $return->result;
                $data['redirect'] = $Mandante->baseUrl . "gestion/deposito";

                return json_encode($data);

                break;
        }
    }

}
