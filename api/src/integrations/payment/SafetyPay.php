<?php

/**
 * Clase SafetyPay para manejar integraciones de pagos con el proveedor SafetyPay.
 *
 * Esta clase contiene propiedades y métodos para gestionar transacciones,
 * incluyendo la creación, actualización y confirmación de estados de transacciones.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-25
 */

namespace Backend\integrations\payment;

use Backend\dto\TransaccionProducto;

/**
 * Clase SafetyPay para manejar integraciones de pagos con el proveedor SafetyPay.
 */
class SafetyPay
{
    /**
     * Fecha y hora de creación de la transacción.
     *
     * @var string
     */
    var $CreationDateTime;

    /**
     * ID de la operación proporcionado por SafetyPay.
     *
     * @var string
     */
    var $OperationID;

    /**
     * ID de la transacción en el sistema del comerciante.
     *
     * @var string
     */
    var $MerchantSalesID;

    /**
     * ID del pedido en el sistema del comerciante.
     *
     * @var string
     */
    var $MerchantOrderID;

    /**
     * Estado de la operación proporcionado por SafetyPay.
     *
     * @var integer
     */
    var $OperationStatus;

    /**
     * Constructor de la clase SafetyPay.
     */
    public function __construct()
    {
    }

    /**
     * Obtiene la fecha y hora de creación de la transacción.
     *
     * @return string
     */
    public function getCreationDateTime()
    {
        return $this->CreationDateTime;
    }

    /**
     * Establece la fecha y hora de creación de la transacción.
     *
     * @param string $CreationDateTime Fecha y hora de creación de la transacción.
     *
     * @return void
     */
    public function setCreationDateTime($CreationDateTime)
    {
        $this->CreationDateTime = $CreationDateTime;
    }

    /**
     * Obtiene el ID de la operación.
     *
     * @return string
     */
    public function getOperationID()
    {
        return $this->OperationID;
    }

    /**
     * Establece el ID de la operación proporcionado por SafetyPay.
     *
     * @param string $OperationID El ID de la operación.
     *
     * @return void
     */
    public function setOperationID($OperationID)
    {
        $this->OperationID = $OperationID;
    }

    /**
     * Obtiene el ID de la transacción en el sistema del comerciante.
     *
     * @return string
     */
    public function getMerchantSalesID()
    {
        return $this->MerchantSalesID;
    }

    /**
     * Establece el ID de la transacción en el sistema del comerciante.
     *
     * @param string $MerchantSalesID El ID de la transacción en el sistema del comerciante.
     *
     * @return void
     */
    public function setMerchantSalesID($MerchantSalesID)
    {
        $this->MerchantSalesID = $MerchantSalesID;
    }

    /**
     * Obtiene el ID del pedido en el sistema del comerciante.
     *
     * @return string
     */
    public function getMerchantOrderID()
    {
        return $this->MerchantOrderID;
    }

    /**
     * Establece el ID del pedido en el sistema del comerciante.
     *
     * @param string $MerchantOrderID El ID del pedido en el sistema del comerciante.
     *
     * @return void
     */
    public function setMerchantOrderID($MerchantOrderID)
    {
        $this->MerchantOrderID = $MerchantOrderID;
    }

    /**
     * Obtiene el estado de la operación.
     *
     * @return integer
     */
    public function getOperationStatus()
    {
        return $this->OperationStatus;
    }

    /**
     * Establece el estado de la operación.
     *
     * @param integer $OperationStatus El estado de la operación proporcionado por SafetyPay.
     *
     * @return void
     */
    public function setOperationStatus($OperationStatus)
    {
        $this->OperationStatus = $OperationStatus;
    }

    /**
     * Confirma el estado de la transacción basado en la información proporcionada por SafetyPay.
     *
     * Este método procesa los estados de la transacción y actualiza los registros
     * en el sistema según el estado recibido.
     *
     * @return void
     */
    public function confirmation()
    {
        // Convertimos a las variables de nuestro sistema

        // ID Transaccion en nuestro sistema
        $transaccion_id = $this->MerchantSalesID;

        // Tipo que genera el log (A: Automatico, M: Manual)
        $tipo_genera = 'A';

        // Valores que me trae el proveedor para auditoria
        $t_value = json_encode($this);

        switch ($this->OperationStatus) {
            case 101:
                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'P';

                // Comentario personalizado para el log
                $comentario = 'Pendiente por SafetyPay ';

                // Obtenemos la transaccion
                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $TransaccionProducto->setPendiente($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);

                break;

            case 102:

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'A';

                // Comentario personalizado para el log
                $comentario = 'Aprobada por SafetyPay ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $TransaccionProducto->setAprobada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->OperationID);

                break;
        }
    }


}