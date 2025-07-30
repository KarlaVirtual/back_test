<?php namespace mKomorowski\Betfair\Transaccion;
/** 
* Clase 'Transaction'
* 
* Esta clase provee un objeto de transacción a la base de datos
* 
* Ejemplo de uso: 
* $Transaction = new Transaction();
*   
* 
* @package mKomorowski\Betfair\Transaccion 
* @author DT
* @version ninguna
* @access public 
* @see no
* 
*/
class Transaccion implements TransaccionInterface
{


    /**
    * Representación de 'transproducto_id'
    *
    * @var objeto
    * @access private
    */
    private $transproducto_id;

    /**
    * Representación de 'producto_id'
    *
    * @var objeto
    * @access private
    */
    private $producto_id;

    /**
    * Representación de 'usuario_id'
    *
    * @var objeto
    * @access private
    */
    private $usuario_id;

    /**
    * Representación de 'valor'
    *
    * @var objeto
    * @access private
    */
    private $valor;

    /**
    * Representación de 'estado'
    *
    * @var objeto
    * @access private
    */
    private $estado;

    /**
    * Representación de 'tipo'
    *
    * @var objeto
    * @access private
    */
    private $tipo;

    /**
    * Representación de 'externo_id'
    *
    * @var objeto
    * @access private
    */
    private $externo_id;

    /**
    * Representación de 'estado_producto'
    *
    * @var objeto
    * @access private
    */
    private $estado_producto;

    /**
    * Representación de 'mandante'
    *
    * @var objeto
    * @access private
    */
    private $mandante;

    /**
    * Representación de 'final_id'
    *
    * @var objeto
    * @access private
    */
    private $final_id;

    /**
    * Representación de 'db'
    *
    * @var objeto
    * @access private
    */
    protected $db;

    /**
    * Constructor de clase
    *
    * @param Objeto $db database
    * 
    * @return no
    * @throws no
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */ 
    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * Método para obtener la conexión por medio de su id
     *
     * @param String $id id
     */
    public function get($id){


        $data = $this->db->find($id, 'users', 'User');;
        $database.getConnection();
    }

    /**
     * Método para obtener la id del producto
     *
     * @return String $producto_id producto_id
     */
    public function getProductoId()
    {
        return $this->producto_id;
    }

    /**
     * Método para establecer la id del producto
     *
     * @param String $producto_id producto_id
     */
    public function setProductoId($producto_id)
    {
        $this->producto_id = $producto_id;
    }

    /**
     * Método para obtener la id del usuario
     *
     * @return String usuario_id usuario_id
     */
    public function getUsuarioId()
    {
        return $this->usuario_id;
    }

    /**
     * Método para establecer la id del usuario
     *
     * @param String $usuario_id usuario_id
     */
    public function setUsuarioId($usuario_id)
    {
        $this->usuario_id = $usuario_id;
    }

    /**
     * Método para obtener el valor
     *
     * @return String valor valor
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Método para establecer el valor
     *
     * @param String $valor valor
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    /**
     * Método para obtener el estado
     *
     * @return String estado estado
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Método para establecer el estado
     *
     * @param String $estado estado
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    /**
     * Método para obtener el tipo
     *
     * @return String tipo tipo
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Método para establecer el tipo
     *
     * @param String $tipo tipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    /**
     * Método para obtener la id del externo
     *
     * @return String externo_id externo_id
     */
    public function getExternoId()
    {
        return $this->externo_id;
    }

    /**
     * Método para establecer la id del externo
     *
     * @param String $externo_id externo_id
     */
    public function setExternoId($externo_id)
    {
        $this->externo_id = $externo_id;
    }

    /**
     * Método para obtener el estado del producto
     *
     * @return String estado_producto estado_producto
     */
    public function getEstadoProducto()
    {
        return $this->estado_producto;
    }

    /**
     * Método para establecer el estado del producto
     *
     * @param String $estado_producto estado_producto
     */
    public function setEstadoProducto($estado_producto)
    {
        $this->estado_producto = $estado_producto;
    }

    /**
     * Método para obtener el mandante
     *
     * @return String mandante mandante
     */
    public function getMandante()
    {
        return $this->mandante;
    }

    /**
     * Método para establecer el mandante
     *
     * @param String $mandante mandante
     */
    public function setMandante($mandante)
    {
        $this->mandante = $mandante;
    }

    /**
     * Método para obtener la id del final
     *
     * @return String final_id final_id
     */
    public function getFinalId()
    {
        return $this->final_id;
    }

    /**
     * Método para establecer la id del final
     *
     * @param String $final_id final_id
     */
    public function setFinalId($final_id)
    {
        $this->final_id = $final_id;
    }


}