<?php

/**
 * Clase Wepay4u
 *
 * Esta clase se encarga de manejar la integración con el proveedor de pagos Wepay4u.
 * Proporciona métodos para procesar confirmaciones de transacciones y actualizar
 * el estado de las mismas en el sistema.
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
 * Clase que representa la integración con el proveedor de pagos Wepay4u.
 * Proporciona métodos para manejar transacciones y actualizar su estado.
 */
class Wepay4u
{
    /**
     * ID Public proporcionado por el proveedor.
     *
     * @var string
     */
    public $PublicId = "";

    /**
     * ID de la transacción en el sistema.
     *
     * @var string
     */
    public $MerchantSalesID = "";

    /**
     * Codigo de la transacción.
     *
     * @var string
     */
    public $PaymentCode = "";

    /**
     * Monto de la transacción.
     *
     * @var string
     */
    public $Amount = "";

    /**
     * hash de la transacción.
     *
     * @var string
     */
    public $hash = "";

    /**
     * Estado de la transacción.
     *
     * @var string
     */
    public $status = "";

    /**
     * Constructor de la clase Wepay4u.
     *
     * Inicializa los datos necesarios para procesar una transacción.
     *
     * @param string $PublicId        Identificador público del proveedor.
     * @param string $MerchantSalesID ID de la transacción en el sistema del comercio.
     * @param string $PaymentCode     Código de pago proporcionado por el proveedor.
     * @param float  $Amount          Monto de la transacción.
     * @param string $hash            Hash de seguridad para la transacción.
     * @param string $status          Estado inicial de la transacción.
     */
    public function __construct($PublicId, $MerchantSalesID, $PaymentCode, $Amount, $hash, $status)
    {
        $this->PublicId = $PublicId;
        $this->MerchantSalesID = $MerchantSalesID;
        $this->PaymentCode = $PaymentCode;
        $this->Amount = $Amount;
        $this->hash = $hash;
        $this->status = $status;
    }

    /**
     * Procesa la confirmación de una transacción.
     *
     * Este método actualiza el estado de una transacción en el sistema
     * dependiendo del estado proporcionado por el proveedor de pagos.
     *
     * @param array $data Datos adicionales proporcionados por el proveedor.
     *
     * @return mixed Resultado del procesamiento de la transacción.
     */
    public function confirmation($data)
    {
        // Convertimos a las variables de nuestro sistema

        // ID Transaccion en nuestro sistema
        $transaccion_id = $this->MerchantSalesID;

        // Tipo que genera el log (A: Automatico, M: Manual)
        $tipo_genera = 'A';


        // Valores que me trae el proveedor para auditoria
        $t_value = json_encode($data);

        switch ($this->status) {
            case 'PENDING_WAITING':

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'P';

                // Comentario personalizado para el log
                $comentario = 'Pendiente por Wepay';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setPendiente($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);
                return $return;

                break;

            case "A":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'A';

                // Comentario personalizado para el log
                $comentario = 'Aprobada por Wepay';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setAprobada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->PublicId);
                return $return;

                break;

            case "CANCELLED":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Rechazada por Wepay ';

                // Obtenemos la transaccion

                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);

                return $return;
                break;
        }
    }
}
