<?php

/**
 * Clase R4CONECTA para manejar integraciones de pagos con el proveedor R4CONECTA.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-23
 */

namespace Backend\integrations\payment;

use Backend\dto\TransaccionProducto;

/**
 * Clase R4CONECTA para manejar integraciones de pagos con el proveedor R4CONECTA.
 *
 * Este archivo contiene la implementación de la clase R4CONECTA, que permite
 * procesar transacciones de pago y registrar su estado en el sistema.
 */
class R4CONECTA
{

    /**
     * Identificador de la factura asociada a la transacción.
     *
     * @var mixed
     */
    var $transactionID;

    /**
     * Identificador del usuario que realiza la transacción.
     *
     * @var mixed
     */
    var $Referencia;

    /**
     * Valor monetario de la transacción.
     *
     * @var mixed
     */
    var $valor;

    /**
     * Resultado del estado de la transacción.
     *
     * @var mixed
     */
    var $result;

    /**
     * Constructor de la clase R4CONECTA.
     *
     * Inicializa los valores de la transacción con los datos proporcionados.
     *
     * @param mixed $transactionID      Identificador de la factura asociada a la transacción.
     * @param mixed $Referencia Identificador del documento relacionado con la transacción.
     * @param mixed $valor        Valor monetario de la transacción.
     * @param mixed $result       Resultado del estado de la transacción.
     */
    public function __construct($transactionID, $Referencia, $valor, $result)
    {
        $this->transactionID = $transactionID;
        $this->Referencia = $Referencia;
        $this->valor = $valor;
        $this->result = $result;
    }

    /**
     * Procesa la confirmación de una transacción basada en el estado proporcionado por el proveedor.
     *
     * Este método utiliza el estado de la transacción (`$this->result`) para determinar
     * las acciones a realizar, como establecer el estado de la transacción en pendiente,
     * aprobada o rechazada, y registrar los detalles en el sistema.
     *
     * @param mixed $t_value Datos adicionales proporcionados por el proveedor para auditoría.
     *
     * @return mixed Resultado de la operación realizada sobre la transacción.
     */
    public function confirmation($t_value)
    {
        // Convertimos a las variables de nuestro sistema

        // ID Transaccion en nuestro sistema
        $transaccion_id = $this->transactionID;

        // Tipo que genera el log (A: Automatico, M: Manual)
        $tipo_genera = 'A';

        $this->result = strtolower($this->result);
        // Valores que me trae el proveedor para auditoria
        $t_value = json_encode($t_value);

        switch ($this->result) {
            case '01':

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'P';

                // Comentario personalizado para el log
                $comentario = 'Pendiente por R4CONECTA ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setPendiente($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);
                return $return;

                break;

            case "00":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'A';

                // Comentario personalizado para el log
                $comentario = 'Aprobada por R4CONECTA ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setAprobada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->Referencia);
                return $return;

                break;

            case "02":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Rechazada por R4CONECTA ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);

                return $return;
                break;
        }
    }

}
