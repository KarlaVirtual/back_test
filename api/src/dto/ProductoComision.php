<?php namespace Backend\dto;
use Backend\mysql\ProductoComisionMySqlDAO;
use Exception;
/** 
* Clase 'ProductoComision'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'ProductoComision'
* 
* Ejemplo de uso: 
* $ProductoComision = new ProductoComision();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class ProductoComision
{

    /**
    * Representación de la columna 'prodcomisionId' de la tabla 'ProductoComision'
    *
    * @var string
    */ 
    var $prodcomisionId;

    /**
    * Representación de la columna 'productointernoId' de la tabla 'ProductoComision'
    *
    * @var string
    */ 
    var $productointernoId;

    /**
    * Representación de la columna 'usuarioId' de la tabla 'ProductoComision'
    *
    * @var string
    */ 
    var $usuarioId;

    /**
    * Representación de la columna 'valor' de la tabla 'ProductoComision'
    *
    * @var string
    */ 
    var $valor;

    /**
    * Representación de la columna 'descripcion' de la tabla 'ProductoComision'
    *
    * @var string
    */ 
    var $descripcion;

    /**
    * Representación de la columna 'estado' de la tabla 'ProductoComision'
    *
    * @var string
    */ 
    var $estado;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'ProductoComision'
    *
    * @var string
    */ 
    var $usucreaId;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'ProductoComision'
    *
    * @var string
    */ 
    var $usumodifId;


    /**
     * Constructor de clase
     *
     *
     * @param String prodcomisionId prodcomisionId
     * @param String usuarioId usuarioId
     * @param String productointernoId productointernoId
     *
     *
    * @return no
    * @throws Exception si ProductoComision no existen
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($prodcomisionId="",$usuarioId="",$productointernoId="")
    {
        if ($prodcomisionId != "") 
        {

            $ProductoComisionMySqlDAO = new ProductoComisionMySqlDAO();

            $ProductoComision = $ProductoComisionMySqlDAO->load($prodcomisionId);

            if ($ProductoComision != null && $ProductoComision != "") 
            {
                $this->prodcomisionId = $ProductoComision->prodcomisionId;
                $this->descripcion = $ProductoComision->descripcion;
                $this->productointernoId = $ProductoComision->productointernoId;
                $this->usuarioId = $ProductoComision->usuarioId;
                $this->valor = $ProductoComision->valor;
                $this->estado = $ProductoComision->estado;
                $this->usucreaId = $ProductoComision->usucreaId;
                $this->usumodifId = $ProductoComision->usumodifId;
            }
            else 
            {
                throw new Exception("No existe " . get_class($this), "31");
            }

        }
        elseif ($usuarioId != "" && $productointernoId != "")
        {

            $ProductoComisionMySqlDAO = new ProductoComisionMySqlDAO();

            $ProductoComision = $ProductoComisionMySqlDAO->loadByusuarioIdAndproductointernoId($usuarioId,$productointernoId);

            if ($ProductoComision != null && $ProductoComision != "") 
            {
                $this->prodcomisionId = $ProductoComision->prodcomisionId;
                $this->descripcion = $ProductoComision->descripcion;
                $this->productointernoId = $ProductoComision->productointernoId;
                $this->usuarioId = $ProductoComision->usuarioId;
                $this->valor = $ProductoComision->valor;
                $this->estado = $ProductoComision->estado;
                $this->usucreaId = $ProductoComision->usucreaId;
                $this->usumodifId = $ProductoComision->usumodifId;
            }
            else 
            {
                throw new Exception("No existe " . get_class($this), "31");
            }
        }

    }

    /**
     * Obtener el campo descripcion de un objeto
     *
     * @return String descripcion descripcion
     * 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Modificar el campo 'descripcion' de un objeto
     *
     * @param String $descripcion descripcion
     *
     * @return no
     *
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    /**
     * Obtener el campo estado de un objeto
     *
     * @return String estado estado
     * 
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Modificar el campo 'estado' de un objeto
     *
     * @param String $estado estado
     *
     * @return no
     *
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    /**
     * Obtener el campo usucreaId de un objeto
     *
     * @return String usucreaId usucreaId
     * 
     */
    public function getUsucreaId()
    {
        return $this->usucreaId;
    }

    /**
     * Modificar el campo 'usucreaId' de un objeto
     *
     * @param String $usucreaId usucreaId
     *
     * @return no
     *
     */
    public function setUsucreaId($usucreaId)
    {
        $this->usucreaId = $usucreaId;
    }

    /**
     * Obtener el campo usumodifId de un objeto
     *
     * @return String usumodifId usumodifId
     * 
     */
    public function getUsumodifId()
    {
        return $this->usumodifId;
    }

    /**
     * Modificar el campo 'usumodifId' de un objeto
     *
     * @param String $usumodifId usumodifId
     *
     * @return no
     *
     */
    public function setUsumodifId($usumodifId)
    {
        $this->usumodifId = $usumodifId;
    }

    /**
     * Obtener el campo prodcomisionId de un objeto
     *
     * @return String prodcomisionId prodcomisionId
     * 
     */
    public function getProdcomisionId()
    {
        return $this->prodcomisionId;
    }

    /**
     * Obtener el campo productointernoId de un objeto
     *
     * @return String productointernoId productointernoId
     * 
     */
    public function getProductointernoId()
    {
        return $this->productointernoId;
    }

    /**
     * Modificar el campo 'productointernoId' de un objeto
     *
     * @param String $productointernoId productointernoId
     *
     * @return no
     *
     */
    public function setProductointernoId($productointernoId)
    {
        $this->productointernoId = $productointernoId;
    }

    /**
     * Obtener el campo usuarioId de un objeto
     *
     * @return String usuarioId usuarioId
     * 
     */
    public function getUsuarioId()
    {
        return $this->usuarioId;
    }

    /**
     * Modificar el campo 'usuarioId' de un objeto
     *
     * @param String $usuarioId usuarioId
     *
     * @return no
     *
     */
    public function setUsuarioId($usuarioId)
    {
        $this->usuarioId = $usuarioId;
    }

    /**
     * Obtener el campo valor de un objeto
     *
     * @return String valor valor
     * 
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Modificar el campo 'valor' de un objeto
     *
     * @param String $valor valor
     *
     * @return no
     *
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
    }





    /**
    * Realizar una consulta en la tabla de ProductoComision 'ProductoComision'
    * de una manera personalizada
    *
    *
    * @param String $select campos de consulta
    * @param String $sidx columna para ordenar
    * @param String $sord orden los datos asc | desc
    * @param String $start inicio de la consulta
    * @param String $limit limite de la consulta
    * @param String $filters condiciones de la consulta 
    * @param boolean $searchOn utilizar los filtros o no
    *
    * @return Array resultado de la consulta
    * @throws Exception si los productos no existen
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getProductoComisionsAgenteCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $ProductoComisionMySqlDAO = new ProductoComisionMySqlDAO();

        $Productos = $ProductoComisionMySqlDAO->queryProductoComisionsAgenteCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($Productos != null && $Productos != "") 
        {
            return $Productos;
        } 
        else 
        {
            throw new Exception("No existe " . get_class($this), "01");
        }

    }



}

?>
