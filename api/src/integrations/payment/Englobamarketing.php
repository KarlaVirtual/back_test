<?php

/**
 * Clase Englobamarketing
 *
 * Esta clase se encarga de gestionar la integración con el servicio de Engloba Marketing
 * para la confirmación de transacciones y la actualización de su estado.
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
use Backend\integrations\payment\ENGLOBASERVICES;

/**
 * Clase Englobamarketing
 *
 * Esta clase representa la integración con el proveedor de pagos Engloba Marketing.
 * Permite gestionar transacciones y realizar confirmaciones de estado.
 */
class Englobamarketing
{

    /**
     * Identificador de la factura o transacción.
     *
     * @var string
     */
    var $invoice;

    /**
     * Resultado de la operación o transacción.
     *
     * @var string
     */
    var $result;

    /**
     * Identificador externo asociado a la transacción.
     *
     * @var string
     */
    var $externo_id;

    /**
     * Constructor de la clase Englobamarketing.
     *
     * Inicializa una nueva instancia de la clase con los valores proporcionados.
     *
     * @param string $invoice Identificador de la factura o transacción.
     * @param string $result  Resultado de la operación o transacción.
     * @param string $uid     Identificador externo asociado a la transacción.
     */
    public function __construct($invoice, $result, $uid)
    {
        $this->invoice = $invoice;
        $this->externo_id = $uid;
        $this->result = $result;
    }


    /**
     * Confirma el estado de una transacción y realiza las acciones correspondientes
     * según el resultado obtenido.
     *
     * @param mixed   $t_value Valores proporcionados por el proveedor para auditoría.
     * @param boolean $get     Indica si se debe realizar una consulta al proveedor para obtener el estado.
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
            $status = 'PROGRESS';
            if ($TransaccionProducto->estado != 'I') {
                $data = array();
                $data['id'] = $this->externo_id;
                $data['clientTxId'] = $transaccion_id;

                $path = '/api/button/V2/Confirm';
                $ENGLOBAMARKETINGSERVICES = new ENGLOBASERVICES();
                $respuesta = $ENGLOBAMARKETINGSERVICES->connectionPOST($data, $path, $Usuario->mandante, $Usuario->paisId);

                $data = json_decode($respuesta);
                $status = $data->transactionStatus;
            }

            if ($status == 'Approved') {
                $status = 'SUCCESS';
            } elseif ($status == 'Canceled') {
                $status = 'CANCEL';
            } else {
                $status = 'PROGRESS';
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

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'A';

                // Comentario personalizado para el log
                $comentario = 'Aprobada por Engloba Marketing';

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
                $comentario = 'Rechazada por Engloba Marketing';

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
                $comentario = 'Reembolsado por Engloba Marketing';

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
