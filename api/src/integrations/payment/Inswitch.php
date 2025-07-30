<?php

/**
 * Clase Inswitch para manejar integraciones de pagos con el proveedor Inswitch.
 *
 * Este archivo contiene la implementación de la clase Inswitch, que permite
 * gestionar transacciones de pago, incluyendo confirmaciones de estado como
 * "pendiente", "aprobada" o "rechazada". La clase utiliza objetos relacionados
 * con transacciones, productos y proveedores para realizar estas operaciones.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-23
 */

namespace Backend\integrations\payment;

use Backend\dto\PaisMandante;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\Subproveedor;
use Backend\dto\TransaccionProducto;
use Backend\dto\Usuario;

/**
 * Clase Inswitch
 *
 * Esta clase maneja la integración de pagos con el proveedor Inswitch.
 * Proporciona métodos para gestionar transacciones de pago y sus estados.
 */
class Inswitch
{
    /**
     * Identificador público de la transacción.
     *
     * @var string
     */
    var $PublicId;

    /**
     * Identificador de la venta del comerciante.
     *
     * @var string
     */
    var $MerchantSalesID;

    /**
     * Monto de la transacción.
     *
     * @var float
     */
    var $Amount;

    /**
     * Estado de la transacción (e.g., pendiente, aprobada, rechazada).
     *
     * @var string
     */
    var $status;

    /**
     * Indica si se utiliza un método específico.
     *
     * @var boolean
     */
    var $metodo;

    /**
     * Constructor de la clase Inswitch.
     *
     * Inicializa una nueva instancia de la clase Inswitch con los datos proporcionados.
     *
     * @param string  $PublicId        Identificador público de la transacción.
     * @param string  $MerchantSalesID Identificador de la venta del comerciante.
     * @param float   $Amount          Monto de la transacción.
     * @param string  $status          Estado de la transacción (e.g., pendiente, aprobada, rechazada).
     * @param boolean $metodo          Indica si se utiliza un método específico (opcional, por defecto false).
     */
    public function __construct($PublicId, $MerchantSalesID, $Amount, $status, $metodo = false)
    {
        $this->PublicId = $PublicId;
        $this->MerchantSalesID = $MerchantSalesID;
        $this->Amount = $Amount;
        $this->status = $status;
        $this->metodo = $metodo;
    }

    /**
     * Confirma el estado de una transacción según los datos proporcionados.
     *
     * Este método procesa la confirmación de una transacción basándose en su estado actual
     * y los datos proporcionados por el proveedor. Dependiendo del estado de la transacción
     * ("waiting", "finished", "declined"), se realizan diferentes operaciones y se actualiza
     * el estado de la transacción en el sistema.
     *
     * @param array  $data       Datos proporcionados por el proveedor para la transacción.
     * @param string $PayMethods Métodos de pago utilizados, separados por guiones (opcional).
     *
     * @return string|null Resultado de la operación en formato JSON o null en caso de error.
     */
    public function confirmation($data, $PayMethods = '')
    {
        $PayMethod = explode('-', $PayMethods);

        // Convertimos a las variables de nuestro sistema
        // ID Transaccion en nuestro sistema
        $transaccion_id = $this->MerchantSalesID;

        // Tipo que genera el log (A: Automatico, M: Manual)
        $tipo_genera = 'A';

        // Valores que me trae el proveedor para auditoria
        $t_value = json_encode($data);

        switch ($this->status) {
            case 'waiting':
                // Asignamos variables por tipo de transaccion
                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'P';
                // Comentario personalizado para el log
                $comentario = 'Pendiente por Inswitch';

                /*$Proveedor = new Proveedor("", "INSWITCH");
                if($this->metodo == true){
                    $TransaccionProducto = new TransaccionProducto($transaccion_id);
                }else{
                    if ($PayMethod[0] == 'binancepayin') {
                        $Prod = 'BIN_PAY';
                    }else {
                        $Prod = 'INSWITCH';
                    }

                    try{
                        $Producto = new Producto('',$Prod,$Proveedor->getProveedorId());

                        // Obtenemos la transaccion
                        $TransaccionProducto = new TransaccionProducto('',$transaccion_id,$Producto->getProductoId());

                    }catch (\Exception $e){
                        $Producto = new Producto('','DBR_PIX',$Proveedor->getProveedorId());

                        // Obtenemos la transaccion
                        $TransaccionProducto = new TransaccionProducto('',$transaccion_id,$Producto->getProductoId());

                    }

                }
                $transaccion_id = $TransaccionProducto->transproductoId;*/

                //$return = $TransaccionProducto->setPendiente($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);
                $return = array("result" => "success");
                return json_encode($return);
                break;

            case "finished":
                // Asignamos variables por tipo de transaccion
                // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                $estado = 'A';
                // Comentario personalizado para el log
                $comentario = 'Aprobada por Inswitch';

                $Proveedor = new Proveedor("", "INSWITCH");
                if ($this->metodo == true) {
                    $TransaccionProducto = new TransaccionProducto($transaccion_id);
                } else {
                    if ($PayMethod[0] == 'binancepayin') {
                        $Prod = 'BIN_PAY';
                    } else {
                        $Prod = 'INSWITCH';
                    }

                    try {
                        $Producto = new Producto('', $Prod, $Proveedor->getProveedorId());

                        // Obtenemos la transaccion
                        $TransaccionProducto = new TransaccionProducto('', $transaccion_id, $Producto->getProductoId());
                    } catch (\Exception $e) {
                        $Producto = new Producto('', 'DBR_PIX', $Proveedor->getProveedorId());

                        // Obtenemos la transaccion
                        $TransaccionProducto = new TransaccionProducto('', $transaccion_id, $Producto->getProductoId());
                    }
                }

                $transaccion_id = $TransaccionProducto->transproductoId;

                if ($transaccion_id != '') {
                    $return = $TransaccionProducto->setAprobada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->PublicId);
                }

                return $return;
                break;

            case "declined":
                try {
                    // Asignamos variables por tipo de transaccion
                    // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                    $estado = 'R';
                    // Comentario personalizado para el log
                    $comentario = 'Rechazada por Inswitch';
                    $Proveedor = new Proveedor("", "INSWITCH");

                    if ($this->metodo == true) {
                        $TransaccionProducto = new TransaccionProducto($transaccion_id);
                    } else {
                        if ($PayMethod[0] == 'binancepayin') {
                            $Prod = 'BIN_PAY';
                        } else {
                            $Prod = 'INSWITCH';
                        }

                        try {
                            $Producto = new Producto('', $Prod, $Proveedor->getProveedorId());

                            // Obtenemos la transaccion
                            $TransaccionProducto = new TransaccionProducto('', $transaccion_id, $Producto->getProductoId());
                        } catch (\Exception $e) {
                            $Producto = new Producto('', 'DBR_PIX', $Proveedor->getProveedorId());

                            // Obtenemos la transaccion
                            $TransaccionProducto = new TransaccionProducto('', $transaccion_id, $Producto->getProductoId());
                        }
                    }
                    $transaccion_id = $TransaccionProducto->transproductoId;

                    if ($transaccion_id != '') {
                        $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->PublicId);
                    }
                    return $return;
                } catch (\Exception $e) {
                    return null;
                }
                break;
        }
    }
}