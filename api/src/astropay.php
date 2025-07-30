<?php
namespace Backend;
/** 
* Clase 'astropay'
* 
* Esta clase provee datos de astropay
* 
* Ejemplo de uso: 
* $astropay = new astropay();
* 
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* @date 7.09.17
* 
*/
class astropay
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
    * Constructor de clase
    *
    *
    * @param String $invoice invoice
    * @param String $usuario_id usuario_id
    * @param String $documento_id documento_id
    * @param float $valor valor
    * @param String $control control
    * @param String $result result
    *
    * @return no
    * @throws no
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
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
    * Realizar un proceso de acuerdo al resultado
    *
    *
    * @param no
    *
    * @return no
    * @throws no
    *
    * @access public
    */
    public function process()
    {
        switch ($this->result) 
        {
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
    * Realizar una transacción Pendiente
    *
    *
    * @param no
    *
    * @return no
    * @throws no
    *
    * @access public
    */
    private function pendiente()
    {
        
        //Convertimos a las variables de nuestro sistema
        
        //ID Transaccion en nuestro sistema
        $transaccion_id = $this->invoice;

        //Tipo que genera el log (A: Automatico, M: Manual)
        $tipo_genera = 'A';

        //Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
        $estado = 'P';

        //Comentario personalizado para el log
        $comentario = 'Pendiente por Astropay ';

        //Valores que me trae el proveedor para auditoria
        $t_value = json_encode($this);

        //Creamos la Transacion de la base de datos
        $Transaction = new Transaction();

        try 
        {

            //Insertamos El log de la transaccion
            $TransprodLog = new TransprodLog($transaccion_id, $estado, $tipo_genera, $comentario, $t_value, 0, 0);
            $TransprodLogMysqlDAO = new TransprodLogMySqlDAO($Transaction);
            $TransprodLog_id = $TransprodLogMysqlDAO->insert($TransprodLog);

            //Actualizamos el estado de la transaccion de el producto
            $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO($Transaction);

            //Obtenemos la transaccion producto.
            $TransaccionProducto = $TransaccionProductoMySqlDAO->load($transaccion_id);
            $TransaccionProducto->setEstado($estado);
            $TransaccionProductoMySqlDAO->update($TransaccionProducto);
            
            //Commit de la transacción
            $Transaction ->commit();

        }
        catch(Exception $e)
        {
            $Transaction ->rollback();
        }


    }

}
