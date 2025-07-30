<?php

/**
 * Clase para gestionar la integración con el proveedor de pagos "PagosInteligentes".
 *
 * Este archivo contiene la implementación de la clase `PagosInteligentes`, que permite
 * procesar y confirmar transacciones realizadas a través del proveedor de pagos.
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

/**
 * Clase `PagosInteligentes` para gestionar la integración con el proveedor de pagos.
 *
 * Esta clase permite procesar y confirmar transacciones realizadas a través
 * del proveedor de pagos "PagosInteligentes".
 */
class PagosInteligentes
{
    /**
     * Identificador de la factura.
     *
     * @var mixed
     */
    var $invoice;

    /**
     * Identificador del usuario.
     *
     * @var mixed
     */
    var $usuario_id;

    /**
     * Identificador del documento.
     *
     * @var mixed
     */
    var $documento_id;

    /**
     * Valor de la transacción.
     *
     * @var mixed
     */
    var $valor;

    /**
     * Código de control de la transacción.
     *
     * @var mixed
     */
    var $control;

    /**
     * Resultado de la transacción (0: Pendiente, 1: Pendiente, 2: Aprobada, 3: Rechazada).
     *
     * @var mixed
     */
    var $result;

    /**
     * Constructor de la clase.
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
     * Procesa la confirmación de la transacción según el resultado proporcionado.
     *
     * Este metodo evalúa el resultado de la transacción y realiza las acciones
     * correspondientes, como establecer el estado de la transacción en pendiente,
     * aprobada o rechazada.
     *
     * @return void
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
            case 0:

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'P';

                // Comentario personalizado para el log
                $comentario = 'Pendiente por PagosInteligentes ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $TransaccionProducto->setPendiente($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);

                break;
            case 1:

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'P';

                // Comentario personalizado para el log
                $comentario = 'Pendiente por PagosInteligentes ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $TransaccionProducto->setPendiente($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);

                break;

            case 2:

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'A';

                // Comentario personalizado para el log
                $comentario = 'Aprobada por PagosInteligentes ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $TransaccionProducto->setAprobada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->documento_id);

                break;

            case 3:

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Rechazada por PagosInteligentes ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);

                break;
        }
    }

}
