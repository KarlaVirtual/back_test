<?php

/**
 * Clase Coincaex para manejar integraciones de pagos con el proveedor Coincaex.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-23
 */

namespace Backend\integrations\payment;

use Backend\dto\PaisMandante;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\Subproveedor;
use Backend\dto\TransaccionProducto;
use Backend\dto\Usuario;
use Exception;

/**
 * Clase Coincaex para manejar integraciones de pagos con el proveedor Coincaex.
 *
 * Este archivo contiene la implementación de la clase Coincaex, que permite
 * gestionar transacciones de pago con diferentes estados (nuevo, completado,
 * inválido, expirado) y registrar los datos correspondientes en el sistema.
 */
class Coincaex
{
    /**
     * Constructor de la clase Coincaex.
     *
     * @param string $MerchantSalesID ID de la transacción proporcionado por el proveedor.
     * @param float  $Amount          Monto de la transacción.
     * @param string $status          Estado inicial de la transacción.
     */
    public function __construct($MerchantSalesID, $Amount, $status)
    {
        $this->MerchantSalesID = $MerchantSalesID;
        $this->Amount = $Amount;
        $this->status = $status;
    }

    /**
     * Procesa la confirmación de una transacción según su estado.
     *
     * Este metodo maneja diferentes estados de una transacción (nuevo, completado,
     * inválido, expirado) y realiza las acciones correspondientes, como registrar
     * los datos en el sistema y generar logs.
     *
     * @param array $data Datos adicionales proporcionados por el proveedor.
     *
     * @return mixed Resultado del procesamiento de la transacción.
     * @throws Exception Si el estado de la transacción no es reconocido.
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
            case 'new':

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'P';

                // Comentario personalizado para el log
                $comentario = 'Pendiente por Coincaex';

                $Proveedor = new Proveedor("", "COINCAEX");

                try {
                    $Producto = new Producto('', 'C00', $Proveedor->getProveedorId());
                    $TransaccionProducto = new TransaccionProducto('', $transaccion_id, $Producto->getProductoId());
                } catch (Exception $e) {
                    $Producto = new Producto('', 'C01', $Proveedor->getProveedorId());
                    $TransaccionProducto = new TransaccionProducto('', $transaccion_id, $Producto->getProductoId());
                }

                $transaccion_id_ = $TransaccionProducto->transproductoId;

                $return = $TransaccionProducto->setPendiente($transaccion_id_, $tipo_genera, $estado, $comentario, $t_value);

                return $return;

                break;

            case "complete":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'A';

                // Comentario personalizado para el log
                $comentario = 'Aprobada por Coincaex';

                $Proveedor = new Proveedor("", "COINCAEX");

                try {
                    $Producto = new Producto('', 'C00', $Proveedor->getProveedorId());
                    $TransaccionProducto = new TransaccionProducto('', $transaccion_id, $Producto->getProductoId());
                } catch (Exception $e) {
                    $Producto = new Producto('', 'C01', $Proveedor->getProveedorId());
                    $TransaccionProducto = new TransaccionProducto('', $transaccion_id, $Producto->getProductoId());
                }

                $transaccion_id_ = $TransaccionProducto->transproductoId;

                $return = $TransaccionProducto->setAprobada($transaccion_id_, $tipo_genera, $estado, $comentario, $t_value, $transaccion_id);
                return $return;

                break;

            case "invalid":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Rechazada por Coincaex ';

                $Proveedor = new Proveedor("", "COINCAEX");

                try {
                    $Producto = new Producto('', 'C00', $Proveedor->getProveedorId());
                    $TransaccionProducto = new TransaccionProducto('', $transaccion_id, $Producto->getProductoId());
                } catch (Exception $e) {
                    $Producto = new Producto('', 'C01', $Proveedor->getProveedorId());
                    $TransaccionProducto = new TransaccionProducto('', $transaccion_id, $Producto->getProductoId());
                }

                $transaccion_id_ = $TransaccionProducto->transproductoId;

                $return = $TransaccionProducto->setRechazada($transaccion_id_, $tipo_genera, $estado, $comentario, $t_value);

                return $return;
                break;

            case "expired":

                // Asignamos variables por tipo de transaccion

                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'R';

                // Comentario personalizado para el log
                $comentario = 'Expirada por Coincaex';

                $Proveedor = new Proveedor("", "COINCAEX");

                try {
                    $Producto = new Producto('', 'C00', $Proveedor->getProveedorId());
                    $TransaccionProducto = new TransaccionProducto('', $transaccion_id, $Producto->getProductoId());
                } catch (Exception $e) {
                    $Producto = new Producto('', 'C01', $Proveedor->getProveedorId());
                    $TransaccionProducto = new TransaccionProducto('', $transaccion_id, $Producto->getProductoId());
                }

                $transaccion_id_ = $TransaccionProducto->transproductoId;

                $return = $TransaccionProducto->setRechazada($transaccion_id_, $tipo_genera, $estado, $comentario, $t_value);

                return $return;
                break;
            default:
                throw new \Exception('Unexpected value');
        }
    }

}
