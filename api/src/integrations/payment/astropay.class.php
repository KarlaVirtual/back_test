<?php

/**
 * Clase para la integración con el sistema de pagos Astropay.
 *
 * Esta clase maneja la lógica para procesar transacciones de pago
 * utilizando el proveedor Astropay. Incluye métodos para manejar
 * diferentes estados de las transacciones, como pendiente, aprobada
 * o rechazada.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-23
 */

/**
 * Clase IntAstropay
 *
 * Representa la integración con el sistema de pagos Astropay.
 */
class IntAstropay
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
     * Código de control para validar la transacción.
     *
     * @var string
     */
    var $control;

    /**
     * Resultado de la transacción (estado).
     *
     * @var integer
     */
    var $result;

    /**
     * Constructor de la clase IntAstropay.
     *
     * @param string  $invoice      ID de la factura.
     * @param integer $usuario_id   ID del usuario.
     * @param integer $documento_id ID del documento.
     * @param float   $valor        Valor de la transacción.
     * @param string  $control      Código de control.
     * @param integer $result       Resultado de la transacción.
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
     * Procesa la transacción según el resultado proporcionado.
     *
     * Dependiendo del valor de `$this->result`, se ejecuta la lógica
     * correspondiente para manejar el estado de la transacción.
     *
     * @return void
     */
    public function process()
    {
        switch ($this->result) {
            case 7:
                $this->pendiente();
                break;

            case 8:
                $data[] = array('invoice' => $_POST['x_invoice'], 'usuario_id' => $_POST['x_iduser'], 'documento_id' => $_POST['x_document'], 'valor' => $_POST['x_amount'], 'control' => $_POST['x_control']);
                transaccionRechazada(json_encode($data));
                break;

            case 9:
                $data[] = array('invoice' => $_POST['x_invoice'], 'usuario_id' => $_POST['x_iduser'], 'documento_id' => $_POST['x_document'], 'valor' => $_POST['x_amount'], 'control' => $_POST['x_control']);
                transaccionAprobada(json_encode($data));
                break;
        }
    }

    /**
     * Maneja el estado pendiente de una transacción.
     *
     * Este metodo realiza las operaciones necesarias para registrar
     * y actualizar el estado de una transacción como pendiente en
     * el sistema, incluyendo la creación de logs y la actualización
     * de la base de datos.
     *
     * @return void
     */
    private function pendiente()
    {
        // Convertimos a las variables de nuestro sistema

        // ID Transaccion en nuestro sistema
        $transaccion_id = $this->invoice;

        // Tipo que genera el log (A: Automatico, M: Manual)
        $tipo_genera = 'A';

        // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
        $estado = 'P';

        // Comentario personalizado para el log
        $comentario = 'Pendiente por Astropay ';

        // Valores que me trae el proveedor para auditoria
        $t_value = json_encode($this);

        // Creamos la Transacion de la base de datos

        $Transaction = new Transaction();

        try {
            // Insertamos El log de la transaccion

            $TransprodLog = new TransprodLog($transaccion_id, $estado, $tipo_genera, $comentario, $t_value, 0, 0);

            $TransprodLogMysqlDAO = new TransprodLogMySqlDAO($Transaction);

            $TransprodLog_id = $TransprodLogMysqlDAO->insert($TransprodLog);


            // Actualizamos el estado de la transaccion de el producto

            $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO($Transaction);

            // Obtenemos la transaccion producto.
            $TransaccionProducto = $TransaccionProductoMySqlDAO->load($transaccion_id);

            $TransaccionProducto->setEstado($estado);

            $TransaccionProductoMySqlDAO->update($TransaccionProducto);

            // COMMIT de la transacción
            $Transaction->commit();
        } catch (Exception $e) {
            $Transaction->rollback();
        }
    }

}

?>