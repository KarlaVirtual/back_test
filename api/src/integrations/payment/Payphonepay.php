<?php

/**
 * Clase Payphonepay
 *
 * Esta clase maneja la integración con el servicio de pagos Payphonepay.
 * Proporciona métodos para confirmar transacciones y gestionar su estado.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-25
 */

namespace Backend\integrations\payment;

use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\TransaccionProducto;
use Backend\integrations\payment\PAYPHONEPAYSERVICES;

/**
 * Clase principal para la integración con el servicio de pagos Payphonepay.
 * Proporciona métodos para gestionar transacciones y confirmar su estado.
 */
class Payphonepay
{

    /**
     * Identificador de la factura o transacción.
     *
     * @var string
     */
    var $invoice;

    /**
     * Resultado inicial de la transacción.
     *
     * @var string
     */
    var $result;

    /**
     * Identificador externo del usuario.
     *
     * @var string
     */
    var $externo_id;

    /**
     * Constructor de la clase Payphonepay.
     *
     * @param mixed $invoice Identificador de la factura.
     * @param mixed $result  Resultado inicial de la transacción.
     * @param mixed $uid     Identificador externo del usuario.
     */
    public function __construct($invoice, $result, $uid)
    {
        $this->invoice = $invoice;
        $this->externo_id = $uid;
        $this->result = $result;
    }

    /**
     * Confirma el estado de una transacción con el servicio Payphonepay.
     *
     * Este método realiza la validación del estado de la transacción y actualiza
     * su estado en el sistema según la respuesta del proveedor.
     *
     * @param array   $t_value Valores proporcionados por el proveedor para auditoría.
     * @param boolean $get     Indica si se debe realizar una consulta al proveedor.
     *
     * @return string JSON con el resultado y la redirección correspondiente.
     */
    public function confirmation($t_value, $get = false)
    {
        // ID Transaccion en nuestro sistema
        $transaccion_id = $this->invoice;

        $TransaccionProducto = new TransaccionProducto($transaccion_id);
        $Usuario = new Usuario($TransaccionProducto->usuarioId);
        $Mandante = new Mandante($Usuario->mandante);

        if ($Usuario->mandante == '27' && $Usuario->paisId == '68') {
            $Mandante->baseUrl = 'https://ganaplay.sv/';
        }
        $returnPath = '';

        if ($get) {
            $status = 'PROGRESS';
            if ($TransaccionProducto->estado != 'I') {
                $data = array();
                $data['id'] = $this->externo_id;
                $data['clientTxId'] = $transaccion_id;

                $path = '/api/button/V2/Confirm';
                $PAYPHONEPAYSERVICES = new PAYPHONEPAYSERVICES();
                $respuesta = $PAYPHONEPAYSERVICES->connectionPOST($data, $path, $Usuario->mandante, $Usuario->paisId);

                $data = json_decode($respuesta);
                $status = $data->transactionStatus;
            }

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
            case "PROGRESS":

                $data = array();
                $data['redirect'] = $Mandante->baseUrl . "gestion/deposito/pendiente";

                return json_encode($data);

                break;

            case "SUCCESS":

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'A';
                $comentario = 'Aprobada por Payphonepay';

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

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Rechazada por Payphonepay';

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

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Reembolsado por Payphonepay';

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
