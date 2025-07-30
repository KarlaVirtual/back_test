<?php

/**
 * Clase CoinPay para gestionar transacciones de pago.
 *
 * Este archivo contiene la implementación de la clase CoinPay.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-23
 */

namespace Backend\integrations\payment;

use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransprodLog;
use Backend\dto\TransproductoDetalle;
use Backend\dto\Usuario;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\mysql\TransproductoDetalleMySqlDAO;

/**
 * Clase CoinPay para gestionar transacciones de pago.
 *
 * Este archivo contiene la implementación de la clase CoinPay, que se utiliza para manejar
 * transacciones de pago, incluyendo la confirmación de estados de transacciones y la interacción
 * con diferentes entidades relacionadas como productos, usuarios y logs.
 */
class CoinPay
{
    /**
     * ID de la factura asociada a la transacción.
     *
     * @var string
     */
    var $invoice;

    /**
     * ID del usuario que realiza la transacción.
     *
     * @var integer
     */
    var $usuario_id;

    /**
     * ID del documento relacionado con la transacción.
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
     * Resultado del estado de la transacción.
     *
     * @var string
     */
    var $result;

    /**
     * Constructor de la clase CoinPay.
     *
     * Inicializa una nueva instancia de la clase CoinPay con los valores proporcionados.
     *
     * @param string  $invoice      ID de la factura asociada a la transacción.
     * @param integer $usuario_id   ID del usuario que realiza la transacción.
     * @param integer $documento_id ID del documento relacionado con la transacción.
     * @param float   $valor        Valor monetario de la transacción.
     * @param string  $control      Código de control para la transacción.
     * @param string  $result       Resultado del estado de la transacción.
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
     * Metodo para confirmar el estado de una transacción.
     *
     * Este metodo gestiona la confirmación de una transacción en función de su estado actual
     * y el resultado proporcionado por el proveedor. Realiza las siguientes acciones:
     * - Verifica el estado de la transacción.
     * - Actualiza el estado de la transacción según el resultado recibido.
     * - Registra logs y detalles relacionados con la transacción.
     *
     * @param mixed $t_value Valores adicionales proporcionados por el proveedor para auditoría.
     *
     * @return mixed Resultado de la operación de confirmación.
     */
    public function confirmation($t_value)
    {
        $TransaccionProducto = new TransaccionProducto($this->invoice);

        if ($TransaccionProducto->getEstado() == 'A') {
            // ID Transaccion en nuestro sistema
            $transaccion_id = $this->invoice;

            // Tipo que genera el log (A: Automatico, M: Manual)
            $tipo_genera = 'A';

            $this->result = strtolower($this->result);

            // Valores que me trae el proveedor para auditoria
            $t_value = json_encode($t_value);


            switch ($this->result) {
                case 'pending_waiting':

                    // Asignamos variables por tipo de transaccion

                    // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                    $estado = 'P';

                    // Comentario personalizado para el log
                    $comentario = 'Pendiente por CoinPay';

                    // Obtenemos la transaccion

                    $TransaccionProducto = new TransaccionProducto($transaccion_id);

                    $return = $TransaccionProducto->setPendiente($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);
                    return $return;

                    break;

                case "approved":

                    // Asignamos variables por tipo de transaccion

                    // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                    $estado = 'A';

                    // Comentario personalizado para el log
                    $comentario = 'Aprobada por CoinPay';

                    // Obtenemos la transaccion

                    $TransaccionProducto = new TransaccionProducto($transaccion_id);

                    $return = $TransaccionProducto->setAprobada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->documento_id);
                    return $return;

                    break;

                case "rejected":

                    // Asignamos variables por tipo de transaccion

                    // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                    $estado = 'R';

                    // Comentario personalizado para el log
                    $comentario = 'Rechazada por CoinPay';

                    // Obtenemos la transaccion

                    $TransaccionProducto = new TransaccionProducto($transaccion_id);

                    $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);

                    return $return;
                    break;
            }
        } else {
            if ($TransaccionProducto->getExternoId() != $this->documento_id) {
                $this->usuario_id = $TransaccionProducto->getUsuarioId();
                $Producto = new Producto($TransaccionProducto->getProductoId());
                $producto_id = $Producto->productoId;
                $Usuario = new Usuario($this->usuario_id);
                $usuarioId = $Usuario->usuarioId;
                $mandante = $Usuario->mandante;
                $estado = 'A';
                $estado_producto = 'E';
                $tipo = 'T';

                $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
                $Transaction = $TransaccionProductoMySqlDAO->getTransaction();

                $sql = "SELECT * FROM transaccion_producto WHERE externo_id =" . $this->documento_id;

                $resultRespuesta = $TransaccionProducto->execQuery($Transaction, $sql);

                if (oldCount($resultRespuesta) == 1) {
                    return "Transacción ya Procesada";
                } else {
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
                    // Convertimos a las variables de nuestro sistema

                    // Tipo que genera el log (A: Automatico, M: Manual)
                    $tipo_genera = 'A';

                    $this->result = strtolower($this->result);

                    // Valores que me trae el proveedor para auditoria
                    $t_value = json_encode($t_value);


                    switch ($this->result) {
                        case 'pending':

                            // Asignamos variables por tipo de transaccion

                            // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                            $estado = 'P';

                            // Comentario personalizado para el log
                            $comentario = 'Pendiente por CoinPay';

                            // Obtenemos la transaccion

                            $TransaccionProducto = new TransaccionProducto($transaccion_id);

                            $return = $TransaccionProducto->setPendiente($transaccion_id, $tipo_genera, $estado, $comentario, $t_value);
                            return $return;

                            break;

                        case "approved":

                            // Asignamos variables por tipo de transaccion

                            // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                            $estado = 'A';

                            // Comentario personalizado para el log
                            $comentario = 'Aprobada por CoinPay';

                            // Obtenemos la transaccion

                            $TransaccionProducto = new TransaccionProducto($transaccion_id);

                            $return = $TransaccionProducto->setAprobada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->documento_id);
                            return $return;

                            break;

                        case "rejected":


                            // Asignamos variables por tipo de transaccion

                            // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                            $estado = 'R';

                            // Comentario personalizado para el log
                            $comentario = 'Rechazada por CoinPay';

                            // Obtenemos la transaccion

                            $TransaccionProducto = new TransaccionProducto($transaccion_id);

                            $return = $TransaccionProducto->setRechazada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $this->documento_id);

                            return $return;
                            break;
                    }
                }
            }
        }
    }

}
