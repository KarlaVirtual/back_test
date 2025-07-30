<?php

/**
 * Clase PagoEfectivo
 *
 * Esta clase maneja la integración con el servicio de PagoEfectivo para procesar
 * notificaciones de pagos y actualizar el estado de las transacciones en el sistema.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations\Payment
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-25
 */

namespace Backend\Integrations\Payment;

use Backend\dto\TransaccionProducto;
use Ratchet\Wamp\Exception;

/**
 * Clase que representa la integración con el servicio de PagoEfectivo.
 * Proporciona métodos para procesar notificaciones de pago y actualizar
 * el estado de las transacciones en el sistema.
 */
class PagoEfectivo
{
    /**
     * Constructor de la clase PagoEfectivo.
     */
    public function __construct()
    {
    }

    /**
     * Procesa la confirmación de pago recibida desde PagoEfectivo.
     *
     * @param string $data Datos encriptados recibidos desde PagoEfectivo.
     *
     * @return void
     */
    public function confirmation($data)
    {
        require_once(__DIR__ . '/../../imports/PagoEfectivo/lib_pagoefectivo/code/PagoEfectivo.php');
        require_once(__DIR__ . '/../../imports/PagoEfectivo/lib_pagoefectivo/code/be/be_notificacion.php');

        $pagoefectivo = new \App_Service_PagoEfectivo();
        $BE_Notificacion = new \BE_Notificacion();


        $paymentResponse = simplexml_load_string($pagoefectivo->desencriptarData($data));


        // Convertimos a las variables de nuestro sistema

        // ID Transaccion en nuestro sistema
        $transaccion_id = intval($paymentResponse->CIP->OrderIdComercio);

        $OrdenID = intval($paymentResponse->CIP->IdOrdenPago);

        $estado = intval($paymentResponse->Estado);

        // Tipo que genera el log (A: Automatico, M: Manual)
        $tipo_genera = 'A';


        $t_value = '';
        try {
            // Valores que me trae el proveedor para auditoria
            $t_value = json_encode($paymentResponse);
        } catch (Exception $e) {
        }

        switch ($estado) {
            case $BE_Notificacion->Extornado:
                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Extornado por PagoEfectivo ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $OrdenID);
                break;

            case $BE_Notificacion->Pagado:

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'A';

                // Comentario personalizado para el log
                $comentario = 'Aprobada por PagoEfectivo ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $TransaccionProducto->setAprobada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $OrdenID);

                break;

            case $BE_Notificacion->Expirado:

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'EX';

                // Comentario personalizado para el log
                $comentario = 'Expirado por PagoEfectivo ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $OrdenID);

                break;
        }
    }

}
