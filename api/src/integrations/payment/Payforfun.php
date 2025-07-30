<?php

/**
 * Clase Payforfun para manejar integraciones de pagos con el proveedor Payforfun.
 *
 * Este archivo contiene la lógica para procesar confirmaciones de transacciones
 * y actualizar el estado de las mismas en el sistema.
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
 * Clase Payforfun para manejar integraciones de pagos con el proveedor Payforfun.
 * Contiene métodos para procesar confirmaciones de transacciones y actualizar su estado.
 */
class Payforfun
{
    /**
     * ID de la factura asociada a la transacción.
     *
     * @var mixed
     */
    var $invoice;

    /**
     * Resultado de la transacción proporcionado por el proveedor.
     *
     * @var mixed
     */
    var $result;

    /**
     * ID del documento asociado a la transacción.
     *
     * @var mixed
     */
    var $documento_id;

    /**
     * Constructor de la clase Payforfun.
     *
     * @param mixed $invoice ID de la factura.
     * @param mixed $result  Resultado de la transacción.
     * @param mixed $uid     ID del documento asociado.
     */
    public function __construct($invoice, $result, $uid)
    {
        $this->invoice = $invoice;
        $this->documento_id = $uid;
        $this->result = $result;
    }

    /**
     * Procesa la confirmación de una transacción y actualiza su estado en el sistema.
     *
     * @param array $t_value Valores proporcionados por el proveedor para auditoría.
     *
     * @return mixed Resultado de la operación de actualización.
     */
    public function confirmation($t_value)
    {
        // ID Transaccion en nuestro sistema
        $transaccion_id = $this->invoice;

        // Tipo que genera el log (A: Automatico, M: Manual)
        $tipo_genera = 'A';

        // Valores que me trae el proveedor para auditoria
        $t_value = json_encode($t_value);


        switch ($this->result) {
            case "102":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'P';

                // Comentario personalizado para el log
                $comentario = 'Pendiente por Payforfun';
                try {
                    // Obtenemos la transaccion
                    $TransaccionProducto = new TransaccionProducto($transaccion_id);
                    $return = $TransaccionProducto->setPendiente($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);
                    return $return;
                } catch (Exception $e) {
                }


                break;

            case "201":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'A';

                // Comentario personalizado para el log
                $comentario = 'Aprobada por Payforfun';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setAprobada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->documento_id);
                return $return;


                break;

            case "failed":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Rechazada por Payforfun';
                try {
                    // Obtenemos la transaccion

                    $TransaccionProducto = new TransaccionProducto($transaccion_id);

                    $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->documento_id);

                    return $return;
                } catch (Exception $e) {
                }

                break;
        }
    }

}
