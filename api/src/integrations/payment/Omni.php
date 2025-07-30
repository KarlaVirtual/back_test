<?php

/**
 * Clase Omni para gestionar integraciones de pagos con el proveedor OMNI.
 *
 * Este archivo contiene la implementación de la clase Omni, que se encarga de manejar
 * las transacciones de productos, registros de logs y detalles de transacciones
 * relacionadas con el proveedor OMNI.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-25
 */

namespace Backend\Integrations\payment;

use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransprodLog;
use Backend\dto\TransproductoDetalle;
use Backend\dto\Usuario;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\mysql\TransproductoDetalleMySqlDAO;
use Exception;

/**
 * Clase Omni.
 *
 * Esta clase gestiona las integraciones de pagos con el proveedor OMNI,
 * permitiendo la creación y confirmación de transacciones.
 */
class Omni
{


    /**
     * Identificador de la factura o transacción.
     *
     * @var mixed
     */
    var $invoice;

    /**
     * Identificador del usuario asociado con la transacción.
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
     * Resultado de la transacción (e.g., PENDING, OK, DENIED).
     *
     * @var string
     */
    var $result;

    /**
     * Constructor de la clase Omni.
     *
     * @param mixed   $invoice      Factura asociada.
     * @param integer $usuario_id   ID del usuario.
     * @param integer $documento_id ID del documento.
     * @param float   $valor        Valor de la transacción.
     * @param string  $control      Código de control.
     * @param string  $result       Resultado de la transacción.
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
     * Método para confirmar una transacción.
     *
     * Este método realiza las operaciones necesarias para registrar una transacción
     * en el sistema, incluyendo la creación de logs y detalles de la transacción.
     *
     * @param mixed $t_value Valores adicionales para la transacción.
     *
     * @return string|array Mensaje o datos de la transacción según el resultado.
     * @throws Exception Si ocurre un error durante la transacción.
     */
    public function confirmation($t_value)
    {
        $Usuario = new Usuario($this->usuario_id);
        $usuarioId = $Usuario->usuarioId;
        $mandante = $Usuario->mandante;
        $Proveedor = new Proveedor("", "OMNI");
        $Producto = new Producto("", "0", $Proveedor->proveedorId);

        $producto_id = $Producto->productoId;


        try {
            $TransaccionProducto = new TransaccionProducto("", $this->documento_id, $producto_id);
            return "Transacción ya registrada";
        } catch (Exception $e) {
            $Usuario = new Usuario($this->usuario_id);
            $usuarioId = $Usuario->usuarioId;
            $mandante = $Usuario->mandante;
            $estado = 'A';
            $estado_producto = 'E';
            $tipo = 'T';

            $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
            $Transaction = $TransaccionProductoMySqlDAO->getTransaction();

            $TransaccionProducto = new TransaccionProducto();
            $TransaccionProducto->setProductoId($producto_id);
            $TransaccionProducto->setUsuarioId($usuarioId);
            $TransaccionProducto->setValor($this->valor);
            $TransaccionProducto->setEstado($estado);
            $TransaccionProducto->setTipo($tipo);
            $TransaccionProducto->setEstadoProducto($estado_producto);
            $TransaccionProducto->setMandante($mandante);
            $TransaccionProducto->setFinalId(0);
            $TransaccionProducto->setExternoId(0);
            $transproductoId = $TransaccionProductoMySqlDAO->insert($TransaccionProducto);

            $TransproductoDetalle = new TransproductoDetalle();
            $TransproductoDetalle->transproductoId = $transproductoId;
            $TransproductoDetalle->tValue = json_encode(($t_value));

            $TransproductoDetalleMySqlDAO = new TransproductoDetalleMySqlDAO($Transaction);
            $TransproductoDetalleMySqlDAO->insert($TransproductoDetalle);

            $TransprodLog = new TransprodLog();
            $TransprodLog->setTransproductoId($transproductoId);
            $TransprodLog->setEstado('E');
            $TransprodLog->setTipoGenera('A');
            $TransprodLog->setComentario('Envio Solicitud de deposito');
            $TransprodLog->setTValue($t_value);
            $TransprodLog->setUsucreaId(0);
            $TransprodLog->setUsumodifId(0);

            $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
            $TransprodLogMySqlDAO->insert($TransprodLog);

            $Transaction->commit();
            // Convertimos a las variables de nuestro sistema

            // ID Transaccion en nuestro sistema
            $transaccion_id = $transproductoId;

            // Tipo que genera el log (A: Automatico, M: Manual)
            $tipo_genera = 'A';


            // Valores que me trae el proveedor para auditoria
            $t_value = json_encode($t_value);

            switch ($this->result) {
                case 'PENDING':

                    // Asignamos variables por tipo de transaccion

                    // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                    $estado = 'P';

                    // Comentario personalizado para el log
                    $comentario = 'Pendiente por Omni ';

                    // Obtenemos la transaccion

                    $TransaccionProducto = new TransaccionProducto($transaccion_id);

                    $return = $TransaccionProducto->setPendiente($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);
                    return $return;

                    break;

                case "OK":

                    // Asignamos variables por tipo de transaccion

                    // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                    $estado = 'A';

                    // Comentario personalizado para el log
                    $comentario = 'Aprobada por Omni ';

                    // Obtenemos la transaccion

                    $TransaccionProducto = new TransaccionProducto($transaccion_id);

                    $return = $TransaccionProducto->setAprobada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->documento_id);


                    $data = array(
                        "tagTransactionId" => $this->documento_id,
                        "merchantTransactionId" => $transproductoId,
                        "description" => "Transaction Updated",
                        "status" => "OK"
                    );
                    return json_encode($data);
                    break;

                case "DENIED":

                    // Asignamos variables por tipo de transaccion

                    // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                    $estado = 'R';

                    // Comentario personalizado para el log
                    $comentario = 'Rechazada por Omni ';

                    // Obtenemos la transaccion

                    $TransaccionProducto = new TransaccionProducto($transaccion_id);

                    $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);

                    return $return;
                    break;


                case "REJECTED":

                    // Asignamos variables por tipo de transaccion

                    // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                    $estado = 'R';

                    // Comentario personalizado para el log
                    $comentario = 'Rechazada por Omni ';

                    // Obtenemos la transaccion

                    $TransaccionProducto = new TransaccionProducto($transaccion_id);

                    $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);

                    return $return;
                    break;
            }
        }
    }

}
