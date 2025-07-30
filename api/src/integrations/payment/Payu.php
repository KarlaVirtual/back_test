<?php

/**
 * Clase para la integración con el proveedor de pagos PayU.
 *
 * Esta clase permite generar firmas para transacciones, manejar confirmaciones
 * de pagos y realizar operaciones relacionadas con el estado de las transacciones.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-25
 */

namespace Backend\integrations\payment;

use Backend\dto\Producto;
use Backend\dto\SubproveedorMandantePais;
use Backend\dto\Usuario;
use Backend\dto\UsuarioMandante;
use Backend\dto\TransaccionProducto;

/**
 * Clase Payu.
 *
 * Esta clase gestiona la integración con el proveedor de pagos PayU,
 * permitiendo la generación de firmas, manejo de confirmaciones de pagos
 * y operaciones relacionadas con transacciones.
 */
class Payu
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
     * Constructor de la clase Payu.
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
     * Genera la firma para una transacción.
     *
     * @param float  $new_value Nuevo valor de la transacción.
     * @param string $currency  Moneda de la transacción.
     * @param string $state_pol Estado de la transacción en el proveedor.
     *
     * @return string Firma generada.
     */
    public function getSign($new_value, $currency, $state_pol)
    {
        $TransaccionProducto = new TransaccionProducto($this->invoice);

        $Producto = new Producto($TransaccionProducto->productoId);
        $Usuario = new Usuario($TransaccionProducto->usuarioId);

        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $Usuario->mandante, $Usuario->paisId);
        $credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $API_KEY = $credentials->API_KEY;
        $ID_COMERCIO = $credentials->ID_COMERCIO;

        $signature = md5($API_KEY . "~" . $ID_COMERCIO . "~" . $this->invoice . "~" . $new_value . "~" . $currency . "~" . $state_pol);

        syslog(LOG_WARNING, "PAYU CREDENTIALS: " . json_encode($credentials));

        return $signature;
    }

    /**
     * Maneja la confirmación de una transacción según el resultado proporcionado por el proveedor.
     *
     * Este método procesa el resultado de la transacción y actualiza su estado en el sistema
     * dependiendo del código de resultado recibido. Los estados posibles son:
     * - '7': Pendiente
     * - '4': Aprobada
     * - '6': Rechazada
     *
     * @param mixed $t_value Datos adicionales proporcionados por el proveedor para auditoría.
     *
     * @return mixed Resultado de la operación de actualización del estado de la transacción.
     */
    public function confirmation($t_value)
    {
        // Convertimos a las variables de nuestro sistema

        // ID Transaccion en nuestro sistema
        $transaccion_id = $this->invoice;

        // Tipo que genera el log (A: Automatico, M: Manual)
        $tipo_genera = 'A';

        $this->result = strtolower($this->result);
        // Valores que me trae el proveedor para auditoria
        $t_value = json_encode($t_value);

        switch ($this->result) {
            case '7':

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'P';

                // Comentario personalizado para el log
                $comentario = 'Pendiente por Payu';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setPendiente($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);
                return $return;

                break;

            case "4":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'A';

                // Comentario personalizado para el log
                $comentario = 'Aprobada por Payu';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setAprobada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->documento_id);
                return $return;

                break;

            case "6":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Rechazada por Payu';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);

                return $return;
                break;
        }
    }
}
