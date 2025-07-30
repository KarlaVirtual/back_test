<?php

/**
 * Clase Paymentez para la integración con el sistema de pagos Paymentez.
 *
 * Este archivo contiene la implementación de la clase Paymentez, que se utiliza
 * para manejar las transacciones de pago y su confirmación en el sistema.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-25
 */

namespace Backend\integrations\payment;

use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Mandante;
use Backend\dto\TransaccionProducto;
use Backend\dto\Usuario;

/**
 * Clase principal para la integración con el sistema de pagos Paymentez.
 *
 * Esta clase maneja las transacciones de pago, su confirmación y otros
 * procesos relacionados con el proveedor Paymentez.
 */
class Paymentez
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
     * Código de control de la transacción.
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
     * Constructor de la clase Paymentez.
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
     * Confirma el estado de una transacción en el sistema.
     *
     * Este método procesa el resultado de una transacción y actualiza su estado
     * en el sistema según el resultado proporcionado por el proveedor Paymentez.
     *
     * @param mixed $t_value Datos adicionales de la transacción.
     *
     * @return mixed Resultado del procesamiento de la transacción.
     */
    public function confirmation($t_value)
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        // Convertimos a las variables de nuestro sistema

        // ID Transaccion en nuestro sistema
        $transaccion_id = $this->invoice;

        // Tipo que genera el log (A: Automatico, M: Manual)
        $tipo_genera = 'A';

        $this->result = strtolower($this->result);
        // Valores que me trae el proveedor para auditoria
        $t_value = json_encode($t_value);

        switch ($this->result) {
            case 'pending_waiting':

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'P';

                // Comentario personalizado para el log
                $comentario = 'Pendiente por Paymentez ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setPendiente($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);
                return $return;

                break;

            case "approved":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'A';

                // Comentario personalizado para el log
                $comentario = 'Aprobada por Paymentez ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setAprobada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->documento_id);
                return $return;

                break;

            case "failure":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Rechazada por Paymentez ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);

                return $return;
                break;

            case "1":

                if ($this->control === "6") {
                    // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                    $estado = 'A';

                    // Comentario personalizado para el log
                    $comentario = 'Aprobada por Paymentez ';

                    // Obtenemos la transaccion

                    $TransaccionProducto = new TransaccionProducto($transaccion_id);

                    $return = $TransaccionProducto->setAprobada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->documento_id);
                    return $return;
                }
                if ($this->control === "0") {
                    // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                    $estado = 'A';

                    // Comentario personalizado para el log
                    $comentario = 'Aprobada por Paymentez ';

                    // Obtenemos la transaccion

                    $TransaccionProducto = new TransaccionProducto($transaccion_id);

                    $return = $TransaccionProducto->setAprobada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->documento_id);
                    $t_value = json_decode($t_value);

                    $usuarioId = $t_value->user->id;
                    $email = $t_value->user->email;
                    $fechaTransaccion = $t_value->transaction->paid_date;
                    $Usuario = new Usuario($usuarioId);
                    $Mandante = new Mandante($Usuario->mandante);
                    $transaccionEstado = "Aprobada";
                    //Arma el mensaje para el usuario que deposita
                    $mensaje_txt = "¡Hola, " . $Usuario->nombre;
                    $mensaje_txt = $mensaje_txt . "Gracias por utilizar nuestros servicios. <br><br> Los siguientes son los datos de tu transacción<br>";
                    $mensaje_txt = $mensaje_txt . "Estado de la Transacción: " . $transaccionEstado . "<br>";
                    $mensaje_txt = $mensaje_txt . "Numero de transacción: " . $this->documento_id . "<br><br>";
                    $mensaje_txt = $mensaje_txt . "Valor de la Transacción: " . $this->valor . "<br><br>";
                    $mensaje_txt = $mensaje_txt . "Fecha de Transacción: " . $fechaTransaccion . "<br><br>";


                    $mtitle = 'Transacción realizada desde ' . $Mandante->nombre;
                    $msubjetc = 'Transacción realizada desde ' . $Mandante->nombre;
                    //$data = array();
                    //Destinatarios
                    $destinatarios = "david.polomu@gmail.com"; //$email;

                    if ($ConfigurationEnvironment->isDevelopment()) {
                        //Envia el mensaje de correo
                        $envio = $ConfigurationEnvironment->EnviarCorreoVersion2($destinatarios, '', '', $msubjetc, '', $mtitle, $mensaje_txt, "", "", "", $Mandante->mandante);
                    }
                    if ($ConfigurationEnvironment->isProduction()) {
                        //Envia el mensaje de correo
                        $envio = $ConfigurationEnvironment->EnviarCorreoVersion2($destinatarios, '', '', $msubjetc, '', $mtitle, $mensaje_txt, "", "", "", $Mandante->mandante);
                    }

                    return $return;
                }
                break;
            case "4":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Rechazada por Paymentez ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);

                return $return;
                break;
        }
    }

}
