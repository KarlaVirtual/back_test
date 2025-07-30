<?php

/**
 * Clase para la integración con el proveedor de pagos Nuvei.
 *
 * Este archivo contiene la implementación de la clase `Nuvei`, que se utiliza para manejar
 * las transacciones de pago realizadas a través del proveedor Nuvei. Incluye métodos
 * para confirmar el estado de las transacciones y registrar los resultados en el sistema.
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
 * Clase Nuvei
 *
 * Maneja las transacciones de pago realizadas a través del proveedor Nuvei.
 */
class Nuvei
{


    /**
     * Identificador de la factura o transacción.
     *
     * @var string
     */
    var $invoice;

    /**
     * Identificador del usuario que realiza la transacción.
     *
     * @var integer
     */
    var $usuario_id;

    /**
     * Identificador del documento asociado a la transacción.
     *
     * @var integer
     */
    var $documento_id;

    /**
     * Valor de la transacción.
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
     * Resultado inicial de la transacción.
     *
     * @var string
     */
    var $result;


    /**
     * Constructor de la clase Nuvei.
     *
     * @param string  $invoice      ID de la factura asociada a la transacción.
     * @param integer $usuario_id   ID del usuario que realiza la transacción.
     * @param integer $documento_id ID del documento asociado a la transacción.
     * @param float   $valor        Valor de la transacción.
     * @param string  $control      Código de control para la transacción.
     * @param string  $result       Resultado inicial de la transacción.
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
     * Confirma el estado de una transacción y registra el resultado en el sistema.
     *
     * @param array $t_value Datos adicionales proporcionados por el proveedor para auditoría.
     *
     * @return mixed Resultado del registro de la transacción en el sistema.
     */
    public function confirmation($t_value)
    {
        // Convertimos a las variables de nuestro sistema

        // Estados posibles de las transacciones según el proveedor Nuvei:
        // 1 - Creado: El pago ha sido creado.
        // 2 - Pendiente de pago: El pago está pendiente.
        // 3 - Expirado: El pago ha expirado.
        // 5 - Autorizado: El pago se ha completado exitosamente.
        // 6 - Denegado: El pago ha sido completado o rechazado.

        // ID Transaccion en nuestro sistema
        $transaccion_id = $this->invoice;

        // Tipo que genera el log (A: Automatico, M: Manual)
        $tipo_genera = 'A';

        // Valores que me trae el proveedor para auditoria
        $t_value = json_encode($t_value);


        switch ($this->result) {
            case "PENDING":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'P';

                // Comentario personalizado para el log
                $comentario = 'Pendiente por Nuvei ';

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
                $comentario = 'Aprobada por Nuvei ';

                // Obtenemos la transaccion
                //$this->documento_id=$transaccion_id;
                $TransaccionProducto = new TransaccionProducto($transaccion_id);


                $return = $TransaccionProducto->setAprobada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->documento_id);
                return $return;

                break;

            case "DECLINED":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Rechazada por Nuvei ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->documento_id);

                return $return;
                break;


            case "ERROR":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Error por Nuvei ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);
                $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->documento_id);

                return $return;
                break;
        }
    }
}



