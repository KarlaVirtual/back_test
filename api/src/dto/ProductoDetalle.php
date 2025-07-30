<?php namespace Backend\dto;
use Backend\mysql\ProductoDetalleMySqlDAO;
use Exception;
/** 
* Clase 'ProductoDetalle'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'ProductoDetalle'
* 
* Ejemplo de uso: 
* $ProductoDetalle = new ProductoDetalle();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class ProductoDetalle
{

    /**
    * Representación de la columna 'productodetalleId' de la tabla 'ProductoDetalle'
    *
    * @var string
    */ 
	var $productodetalleId;

    /**
    * Representación de la columna 'productoId' de la tabla 'ProductoDetalle'
    *
    * @var string
    */ 
	var $productoId;

    /**
    * Representación de la columna 'pKey' de la tabla 'ProductoDetalle'
    *
    * @var string
    */ 
	var $pKey;

    /**
    * Representación de la columna 'pValue' de la tabla 'ProductoDetalle'
    *
    * @var string
    */ 
	var $pValue;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'ProductoDetalle'
    *
    * @var string
    */ 
	var $usucreaId;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'ProductoDetalle'
    *
    * @var string
    */ 
	var $usumodifId;

    /**
    * Constructor de clase
    *
    *
    * @param String productodetalleId id de ProdcutoDetalle
    * @param String productoId id del producto
    * @param String pKey pKey
    *
    *
    * @return no
    * @throws no
    *
    * @access public
    * @see no
    * @since Exception si ProductoDetalle no existe
    * @deprecated no
    */
    public function __construct($productodetalleId="",$productoId="",$pKey="")
    {

        if ($productoId != "" && $pKey != "") 
        {

            $ProductoDetalleMySqlDAO = new ProductoDetalleMySqlDAO();

            $ProductoDetalle = $ProductoDetalleMySqlDAO->queryByProductoIdANDPKey($productoId,$pKey);
            $ProductoDetalle = $ProductoDetalle[0];

            if ($ProductoDetalle != null && $ProductoDetalle != "") 
            {
                $this->productodetalleId = $ProductoDetalle->productodetalleId;
                $this->productoId = $ProductoDetalle->productoId;
                $this->pKey = $ProductoDetalle->pKey;
                $this->pValue = $ProductoDetalle->pValue;
                $this->usucreaId = $ProductoDetalle->usucreaId;
                $this->usumodifId = $ProductoDetalle->usumodifId;
            } 
            else 
            {
                throw new Exception("No existe " . get_class($this), "01");
            }
        }else if ($productoId != ""){

            $ProductoDetalleMySqlDAO = new ProductoDetalleMySqlDAO();

            $ProductoDetalle = $ProductoDetalleMySqlDAO->queryByProductoId($productoId);
            $ProductoDetalle = $ProductoDetalle[0];
            if ($ProductoDetalle != null && $ProductoDetalle != "")
            {
                $this->productodetalleId = $ProductoDetalle->productodetalleId;
                $this->productoId = $ProductoDetalle->productoId;
                $this->pKey = $ProductoDetalle->pKey;
                $this->pValue = $ProductoDetalle->pValue;
                $this->usucreaId = $ProductoDetalle->usucreaId;
                $this->usumodifId = $ProductoDetalle->usumodifId;
            } else
            {
                throw new Exception("No existe " . get_class($this), "01");
            }
        }else if($productodetalleId != ""){

            $ProductoDetalleMySqlDAO = new ProductoDetalleMySqlDAO();
            $ProductoDetalle = $ProductoDetalleMySqlDAO->load($productodetalleId);
            if ($ProductoDetalle != null && $ProductoDetalle != "")
            {
                $this->productodetalleId = $ProductoDetalle->productodetalleId;
                $this->productoId = $ProductoDetalle->productoId;
                $this->pKey = $ProductoDetalle->pKey;
                $this->pValue = $ProductoDetalle->pValue;
                $this->usucreaId = $ProductoDetalle->usucreaId;
                $this->usumodifId = $ProductoDetalle->usumodifId;
            } else{
                throw new Exception("No existe " . get_class($this), "01");
            }

        }

    }





    /**
     * Obtener el campo productodetalleId de un objeto
     *
     * @return String productodetalleId productodetalleId
     * 
     */
    public function getProductodetalleId()
    {
        return $this->productodetalleId;
    }

    /**
     * Modificar el campo 'productodetalleId' de un objeto
     *
     * @param String $productodetalleId productodetalleId
     *
     * @return no
     *
     */
    public function setProductodetalleId($productodetalleId)
    {
        $this->productodetalleId = $productodetalleId;
    }

    /**
     * Obtener el campo productoId de un objeto
     *
     * @return String productoId productoId
     * 
     */
    public function getProductoId()
    {
        return $this->productoId;
    }

    /**
     * Modificar el campo 'productoId' de un objeto
     *
     * @param String $productoId productoId
     *
     * @return no
     *
     */
    public function setProductoId($productoId)
    {
        $this->productoId = $productoId;
    }

    /**
     * Obtener el campo pKey de un objeto
     *
     * @return String pKey pKey
     * 
     */
    public function getPKey()
    {
        return $this->pKey;
    }

    /**
     * Modificar el campo 'pKey' de un objeto
     *
     * @param String $pKey pKey
     *
     * @return no
     *
     */
    public function setPKey($pKey)
    {
        $this->pKey = $pKey;
    }

    /**
     * Obtener el campo pValue de un objeto
     *
     * @return String pValue pValue
     * 
     */
    public function getPValue()
    {
        return $this->pValue;
    }

    /**
     * Modificar el campo 'pValue' de un objeto
     *
     * @param String $pValue pValue
     *
     * @return no
     *
     */
    public function setPValue($pValue)
    {
        $this->pValue = $pValue;
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
    * Realizar una insersccion en la base de datos
    *
    *
    * @param Objecto $transaction transacción
    *
    * @return Array resultado de la consulta
    */
    public function insert($transaction)
    {

        $ProductoDetalleMySqlDAO = new ProductoDetalleMySqlDAO($transaction);

        return $ProductoDetalleMySqlDAO->insert($this);

    }
    /**
     * Realizar una consulta en la tabla de producto_detalles 'ProductoDetalle'
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
    public function getProductoDetallesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $ProductoDetalleMySqlDAO = new ProductoDetalleMySqlDAO();

        $Productos = $ProductoDetalleMySqlDAO->queryProductoDetallesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

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