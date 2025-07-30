<?php

/**
 * Clase Paycash para la integración de pagos.
 *
 * Esta clase maneja la lógica de integración con el proveedor de pagos Paycash,
 * incluyendo la gestión de transacciones y la confirmación de estados.
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
use Backend\dto\Usuario;
use Backend\dto\Pais;

/**
 * Clase que representa la integración con el proveedor de pagos Paycash.
 *
 * Esta clase contiene métodos y propiedades para gestionar transacciones
 * y confirmar estados de pago con el proveedor Paycash.
 */
class Paycash
{
    /**
     * ID público del proveedor de pagos.
     *
     * @var string
     */
    public $PublicId = "";

    /**
     * ID de la transacción en el sistema del comerciante.
     *
     * @var string
     */
    public $MerchantSalesID = "";

    /**
     * Monto de la transacción.
     *
     * @var string
     */
    public $Amount = "";

    /**
     * Estado de la transacción.
     *
     * @var string
     */
    public $status = "";

    /**
     * Constructor de la clase Paycash.
     *
     * @param string $PublicId      ID público del proveedor.
     * @param string $transaccionID ID de la transacción en el sistema del comerciante.
     * @param string $Amount        Monto de la transacción.
     * @param string $status        Estado de la transacción.
     */
    public function __construct($PublicId, $transaccionID, $Amount, $status)
    {
        $this->PublicId = $PublicId;
        $this->MerchantSalesID = $transaccionID;
        $this->Amount = $Amount;
        $this->status = $status;
    }

    /**
     * Obtiene el ID del país asociado a un usuario.
     *
     * @param integer $user_id ID del usuario.
     *
     * @return integer ID del país asociado al usuario.
     */
    public function paisGet($user_id)
    {
        $Usuario = new Usuario($user_id);
        $pais_id = $Usuario->paisId;

        $Pais = new Pais($pais_id);
        $Pais_nom = $Pais->paisNom;

        return $pais_id;
    }

    /**
     * Confirma el estado de una transacción según los datos proporcionados.
     *
     * @param array $data Datos proporcionados por el proveedor de pagos.
     *
     * @return mixed Resultado de la operación de confirmación.
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
            case '1':
                // Asignamos variables por tipo de transaccion
                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'E';
                // Comentario personalizado para el log
                $comentario = 'Pendiente por Paycash';
                // Obtenemos la transaccion
                $TransaccionProducto = new TransaccionProducto($transaccion_id);

                $return = $TransaccionProducto->setPendiente($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);
                return $return;
                break;

            case "0":
                // Asignamos variables por tipo de transaccion
                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'A';
                // Comentario personalizado para el log
                $comentario = 'Aprobada por Paycash';
                // Obtenemos la transaccion
                $TransaccionProducto = new TransaccionProducto($transaccion_id);
                $return = $TransaccionProducto->setAprobada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->PublicId);
                return $return;
                break;

            case "2":
                // Asignamos variables por tipo de transaccion
                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';
                // Comentario personalizado para el log
                $comentario = 'Rechazada por Paycash';
                // Obtenemos la transaccion
                $TransaccionProducto = new TransaccionProducto($transaccion_id);
                $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);
                return $return;
                break;
        }
    }
}