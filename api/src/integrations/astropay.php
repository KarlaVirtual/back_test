<?php

/**
 * Clase que representa la integración con Astropay.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-22
 */

namespace Project2\Integrations;

/**
 * Clase que representa la integración con Astropay.
 *
 * Esta clase permite manejar transacciones relacionadas con Astropay,
 * incluyendo la creación de logs, actualización de estados y procesamiento
 * de resultados según el estado de la transacción.
 */
class Astropay
{

    /**
     * Representación de una factura
     *
     * @var string
     */
    var $invoice;

    /**
     * Representación del id del usuario
     *
     * @var string
     */
    var $usuario_id;

    /**
     * Representación del id del documento
     *
     * @var string
     */
    var $documento_id;

    /**
     * Representación de valor
     *
     * @var float
     */
    var $valor;

    /**
     * Representación de control
     *
     * @var string
     */
    var $control;

    /**
     * Representación del resultado
     *
     * @var string
     */
    var $result;

    /**
     * Constructor de la clase Astropay.
     *
     * Inicializa los atributos de la clase con los valores proporcionados.
     *
     * @param string $invoice      Identificador de la factura.
     * @param string $usuario_id   Identificador del usuario.
     * @param string $documento_id Identificador del documento.
     * @param float  $valor        Valor de la transacción.
     * @param string $control      Código de control de la transacción.
     * @param string $result       Resultado de la transacción.
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
     * Procesa la transacción según el resultado.
     *
     * Dependiendo del valor del atributo `result`, se ejecuta una acción específica:
     * - 7: Marca la transacción como pendiente.
     * - 8: Marca la transacción como rechazada.
     * - 9: Marca la transacción como aprobada.
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
                $data[] = array(
                    'invoice' => $_POST['x_invoice'],
                    'usuario_id' => $_POST['x_iduser'],
                    'documento_id' => $_POST['x_document'],
                    'valor' => $_POST['x_amount'],
                    'control' => $_POST['x_control']
                );
                transaccionRechazada(json_encode($data));
                break;

            case 9:
                $data[] = array(
                    'invoice' => $_POST['x_invoice'],
                    'usuario_id' => $_POST['x_iduser'],
                    'documento_id' => $_POST['x_document'],
                    'valor' => $_POST['x_amount'],
                    'control' => $_POST['x_control']
                );
                transaccionAprobada(json_encode($data));
                break;
        }
    }

    /**
     * Marca la transacción como pendiente.
     *
     * Este método realiza las siguientes acciones:
     * - Crea un log de la transacción en la base de datos.
     * - Actualiza el estado de la transacción a "Pendiente".
     * - Realiza un commit o rollback según el resultado de las operaciones.
     *
     * @return void
     */
    public function pendiente()
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

            //Obtenemos la transaccion producto.

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
