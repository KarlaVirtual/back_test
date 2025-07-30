<?php

/**
 * Clase Pagadito para la integración con el sistema de pagos Pagadito.
 *
 * Proporciona métodos para la verificación de firmas y la confirmación de transacciones
 * basadas en los resultados proporcionados por el proveedor de pagos.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-25
 */

namespace Backend\integrations\payment;

use Backend\dto\TransaccionProducto;

/**
 * Clase que representa la integración con el sistema de pagos Pagadito.
 * Proporciona métodos para verificar firmas y confirmar transacciones.
 */
class Pagadito
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
     * Resultado del estado de la transacción.
     *
     * @var string
     */
    var $result;

    /**
     * Constructor de la clase Pagadito.
     *
     * @param string  $invoice      ID de la factura.
     * @param integer $usuario_id   ID del usuario.
     * @param integer $documento_id ID del documento.
     * @param float   $valor        Valor de la transacción.
     * @param string  $control      Código de control.
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
     * Verifica la firma de una notificación recibida desde Pagadito.
     *
     * @param array  $headers Encabezados de la notificación.
     * @param string $data    Cuerpo de la notificación en formato JSON.
     *
     * @return boolean True si la firma es válida, False en caso contrario.
     */
    public function signature($headers, $data)
    {
        $notification_id = $headers['PAGADITO_NOTIFICATION_ID'];
        $notification_timestamp = $headers['PAGADITO_NOTIFICATION_TIMESTAMP'];
        $auth_algo = $headers['PAGADITO_AUTH_ALGO'];
        $cert_url = $headers['PAGADITO_CERT_URL'];
        $notification_signature = base64_decode($headers['PAGADITO_SIGNATURE']);

        // obtener id evento
        $array_data = json_decode($data, true);
        $event_id = $array_data['id'];

        $wsk = '';

        // generar cadena para confirmar firma
        $data_signed = $notification_id . '|' . $notification_timestamp . '|' . $event_id . '|' . crc32($data) . '|' . $wsk;

        // opciones de peticiones http para generar el stream context para obtener el certificado
        $http_options = array(
            'http' => array(
                'protocol_version' => '1.1',
                'method' => 'GET',
                'header' => array(
                    'Connection: close'
                ),
            )
        );
        $cert_stream_context = stream_context_create($http_options);
        $cert_content = file_get_contents($cert_url, false, $cert_stream_context);

        // obtener llave publica
        $pubkeyid = openssl_pkey_get_public($cert_content);

        // verificar firma
        $resultado = openssl_verify($data_signed, $notification_signature, $pubkeyid, $auth_algo);

        // liberar llave publica
        openssl_free_key($pubkeyid);

        // verificacion
        if ($resultado == 1) {
            return true;
            echo 'verificación de la firma exitosa';
        } elseif ($resultado == 0) {
            return false;
            echo 'verificación de la firma invalida';
        } else {
            return true;
            echo 'error realizando la verificación de la firma';
        }
    }

    /**
     * Confirma el estado de una transacción en el sistema.
     *
     * @param mixed $t_value          Valor de la transacción proporcionado por el proveedor.
     * @param float $valor_commission Comisión asociada a la transacción (opcional).
     *
     * @return mixed Resultado de la operación de confirmación.
     */
    public function confirmation($t_value, $valor_commission = 0)
    {
        // ID Transaccion en nuestro sistema
        $transaccion_id = $this->invoice;

        // Tipo que genera el log (A: Automatico, M: Manual)
        $tipo_genera = 'A';

        $this->result = strtolower($this->result);

        if (is_object($t_value)) {
            $t_value = json_encode($t_value);
        }

        switch ($this->result) {
            case 'pendiente':

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'P';

                // Comentario personalizado para el log
                $comentario = 'Pendiente por Pagadito';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setPendiente($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);
                return $return;

                break;

            case "aprobado":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'A';

                // Comentario personalizado para el log
                $comentario = 'Aprobada por Pagadito';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setAprobada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->documento_id, $valor_commission);
                return $return;

                break;

            case "rechazado":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Rechazada por Pagadito';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);

                return $return;
                break;
        }
    }
}
