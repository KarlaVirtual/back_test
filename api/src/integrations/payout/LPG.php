<?php

/**
 * Esta clase maneja la integración con el proveedor LPG.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-19
 */

namespace Backend\integrations\payout;

use Backend\dto\TransaccionProducto;

/**
 * LPG
 *
 * Esta clase maneja la integración con el proveedor LPG para la gestión de transacciones.
 */
class LPG
{

    /**
     * ID de la factura.
     *
     * @var string
     */
    var $invoice;

    /**
     * ID del usuario.
     *
     * @var string
     */
    var $usuario_id;

    /**
     * ID del documento.
     *
     * @var string
     */
    var $documento_id;

    /**
     * Valor de la transacción.
     *
     * @var float
     */
    var $valor;

    /**
     * Control de la transacción.
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
     * Constructor de la clase LPG.
     *
     * Inicializa los valores necesarios para manejar la transacción con el proveedor LPG.
     *
     * @param string $invoice      ID de la factura.
     * @param string $usuario_id   ID del usuario.
     * @param string $documento_id ID del documento.
     * @param float  $valor        Valor de la transacción.
     * @param string $control      Control de la transacción.
     * @param string $result       Resultado de la transacción.
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
     * Maneja la confirmación de la transacción con el proveedor LPG.
     *
     * Este metodo se encarga de procesar la respuesta del proveedor y actualizar el estado de la transacción
     * en el sistema según el resultado recibido.
     *
     * @return mixed El resultado de la operación de actualización de la transacción.
     */
    public function confirmation()
    {
        // Convertimos a las variables de nuestro sistema

        // ID Transaccion en nuestro sistema
        $transaccion_id = $this->invoice;

        // Tipo que genera el log (A: Automatico, M: Manual)
        $tipo_genera = 'A';


        // Valores que me trae el proveedor para auditoria
        $t_value = json_encode($this);

        switch ($this->result) {
            case 7:

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'P';

                // Comentario personalizado para el log
                $comentario = 'Pendiente por LPG ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setPendiente(
                    $transaccion_id,
                    $tipo_genera,
                    $estado,
                    $comentario,
                    $t_value
                );
                return $return;

                break;

            case "approved":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'A';

                // Comentario personalizado para el log
                $comentario = 'Aprobada por LPG ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setAprobada(
                    $transaccion_id,
                    $tipo_genera,
                    $estado,
                    $comentario,
                    $t_value,
                    $this->documento_id
                );
                return $return;

                break;

            case "cancelled":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Rechazada por LPG ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setRechazada(
                    $transaccion_id,
                    $tipo_genera,
                    $estado,
                    $comentario,
                    $t_value
                );

                return $return;
                break;
        }
    }

}
