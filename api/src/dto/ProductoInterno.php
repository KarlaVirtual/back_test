<?php namespace Backend\dto;
use Backend\mysql\ProductoInternoMySqlDAO;
use Exception;
/** 
* Clase 'ProductoInterno'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'ProductoInterno'
* 
* Ejemplo de uso: 
* $ProductoInterno = new ProductoInterno();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class ProductoInterno
{

    /**
    * Representación de la columna 'productointernoId' de la tabla 'ProductoInterno'
    *
    * @var string
    */ 
    var $productointernoId;

    /**
    * Representación de la columna 'descripcion' de la tabla 'ProductoInterno'
    *
    * @var string
    */ 
    var $descripcion;

    /**
    * Representación de la columna 'abreviado' de la tabla 'ProductoInterno'
    *
    * @var string
    */ 
    var $abreviado;

    /**
    * Representación de la columna 'estado' de la tabla 'ProductoInterno'
    *
    * @var string
    */ 
    var $estado;

    /**
    * Representación de la columna 'tipo' de la tabla 'ProductoInterno'
    *
    * @var string
    */ 
    var $tipo;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'ProductoInterno'
    *
    * @var string
    */ 
    var $usucreaId;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'ProductoInterno'
    *
    * @var string
    */ 
    var $usumodifId;

    /**
     * Constructor de clase
     *
     *
     * @param String productointernoId id de productointerno
     *
     *
    * @return no
    * @throws Exception si ProductoInterno no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($productointernoId="")
    {
        if ($productointernoId != "") 
        {

            $this->productointernoId = $productointernoId;

            $ProductoInternoMySqlDAO = new ProductoInternoMySqlDAO();

            $ProductoInterno = $ProductoInternoMySqlDAO->load($productointernoId);

            if ($ProductoInterno != null && $ProductoInterno != "") 
            {
                $this->productointernoId = $ProductoInterno->productointernoId;
                $this->descripcion = $ProductoInterno->descripcion;
                $this->abreviado = $ProductoInterno->abreviado;
                $this->estado = $ProductoInterno->estado;
                $this->tipo = $ProductoInterno->tipo;
                $this->usucreaId = $ProductoInterno->usucreaId;
                $this->usumodifId = $ProductoInterno->usumodifId;
            }
            else 
            {
                throw new Exception("No existe " . get_class($this), "26");
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
     * Obtener el campo abreviado de un objeto
     *
     * @return String abreviado abreviado
     * 
     */
    public function getAbreviado()
    {
        return $this->abreviado;
    }

    /**
     * Modificar el campo 'abreviado' de un objeto
     *
     * @param String $abreviado abreviado
     *
     * @return no
     *
     */
    public function setAbreviado($abreviado)
    {
        $this->abreviado = $abreviado;
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
     * Obtener el campo tipo de un objeto
     *
     * @return String tipo tipo
     * 
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Modificar el campo 'tipo' de un objeto
     *
     * @param String $tipo tipo
     *
     * @return no
     *
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }



    /**
    * Realizar una consulta en la tabla de ProductoInterno 'ProductoInterno'
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
    public function getProductoInternosAgenteCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $ProductoInternoMySqlDAO = new ProductoInternoMySqlDAO();

        $Productos = $ProductoInternoMySqlDAO->queryProductoInternosAgenteCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

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
