<?php

/**
 * Clase Sagicor para manejar integraciones de pagos con el proveedor Sagicor.
 *
 * Este archivo contiene la implementación de la clase Sagicor, que permite procesar
 * transacciones de pago y registrar su estado en el sistema.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-25
 */

namespace Backend\integrations\payment;

use Backend\dto\TransaccionProducto;

/**
 * Clase Sagicor.
 *
 * Esta clase maneja la integración de pagos con el proveedor Sagicor,
 * permitiendo procesar transacciones y registrar su estado en el sistema.
 */
class Sagicor
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
     * Resultado de la transacción proporcionado por el proveedor.
     *
     * @var string
     */
    var $result;

    /**
     * Constructor de la clase Sagicor.
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
     * Este método evalúa el resultado de la transacción proporcionado por el proveedor
     * y actualiza el estado de la transacción en el sistema.
     *
     * @param mixed $t_value Datos adicionales de la transacción para auditoría.
     *
     * @return mixed Resultado del procesamiento de la transacción.
     */
    public function confirmation($t_value)
    {
        // Convertimos a las variables de nuestro sistema

        // ID Transaccion en nuestro sistema
        $transaccion_id = $this->invoice;

        // Tipo que genera el log (A: Automatico, M: Manual)
        $tipo_genera = 'A';

        $this->result = strtoupper($this->result);
        // Valores que me trae el proveedor para auditoria
        $t_value = json_encode($t_value);

        switch ($this->result) {
            case '1':

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'P';

                // Comentario personalizado para el log
                $comentario = 'Pendiente por Sagicor ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setPendiente($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);
                return $return;

                break;

            case "APPROVED":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'A';

                // Comentario personalizado para el log
                $comentario = 'Aprobada por Sagicor ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setAprobada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->documento_id);
                return $return;

                break;

            case "05":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Rechazada por Sagicor ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);

                return $return;
                break;
        }
    }

}
