<?php 
namespace Backend\dto;
use Backend\mysql\CategoriaMySqlDAO;
use Backend\mysql\CategoriaProductoMySqlDAO;
use Exception;
/** 
* Clase 'CategoriaProducto'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'categoria_producto'
* 
* Ejemplo de uso: 
* $CategoriaProducto = new CategoriaProducto();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class CategoriaProducto
{

    /**
    * Representación de la columna 'catprodId' de la tabla 'CategoriaProducto'
    *
    * @var string
    */  		
	var $catprodId;

    /**
    * Representación de la columna 'categoriaId' de la tabla 'CategoriaProducto'
    *
    * @var string
    */  
	var $categoriaId;

    /**
    * Representación de la columna 'productoId' de la tabla 'CategoriaProducto'
    *
    * @var string
    */  
	var $productoId;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'CategoriaProducto'
    *
    * @var string
    */  
	var $usucreaId;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'CategoriaProducto'
    *
    * @var string
    */  
	var $usumodifId;

    /**
     * Representación de la columna 'usumodifId' de la tabla 'CategoriaProducto'
     *
     * @var string
     */
    var $estado;

    /**
     * Representación de la columna 'usumodifId' de la tabla 'CategoriaProducto'
     *
     * @var string
     */
    var $orden;


    /**
     * Representación de la columna 'usumodifId' de la tabla 'CategoriaProducto'
     *
     * @var string
     */
    var $mandante;


    var $paisId;


    /**
    * Constructor de clase
    *
    *
    * @param String $catprodId id de la tabla categoria_producto
    * @param String $productoId codigo del producto
    * @param String $tipo codigo del tipo
    *
    * @return no
    * @throws Exception si CategoriaProducto no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
        public function __construct($catprodId="",$productoId="",$tipo="",$categoriaId="",$mandante="",$paisId="", $estado="")
        {
            if($catprodId != "")
            {
                $CategoriaProductoMySqlDAO = new CategoriaProductoMySqlDAO();

                $CategoriaProducto = $CategoriaProductoMySqlDAO->load($catprodId);
                if ($CategoriaProducto != null && $CategoriaProducto != "")
                {
                    $this->catprodId = $CategoriaProducto->catprodId;
                    $this->productoId = $CategoriaProducto->productoId;
                    $this->categoriaId = $CategoriaProducto->categoriaId;
                    $this->usucreaId = $CategoriaProducto->usucreaId;
                    $this->usumodifId = $CategoriaProducto->usumodifId;
                    $this->estado = $CategoriaProducto->estado;
                    $this->orden = $CategoriaProducto->orden;
                    $this->mandante = $CategoriaProducto->mandante;
                }
                else
                {
                    throw new Exception("No existe " . get_class($this), "49");
                }

            }
            elseif ($productoId != "" && $tipo != "")
            {
                $CategoriaProductoMySqlDAO = new CategoriaProductoMySqlDAO();

                $CategoriaProducto = $CategoriaProductoMySqlDAO->queryByProductoIdAndTipoCategoria($productoId,$tipo);
                $CategoriaProducto = $CategoriaProducto[0];
                if ($CategoriaProducto != null && $CategoriaProducto != "") 
                {
                    $this->catprodId = $CategoriaProducto->catprodId;
                    $this->productoId = $CategoriaProducto->productoId;
                    $this->categoriaId = $CategoriaProducto->categoriaId;
                    $this->usucreaId = $CategoriaProducto->usucreaId;
                    $this->usumodifId = $CategoriaProducto->usumodifId;
                    $this->estado = $CategoriaProducto->estado;
                    $this->orden = $CategoriaProducto->orden;
                    $this->mandante = $CategoriaProducto->mandante;
                    $this->paisId = $CategoriaProducto->paisId;
                }
                else 
                {
                    throw new Exception("No existe " . get_class($this), "49");
                }
            }
            elseif ($productoId != "" && $categoriaId != '' && $mandante != '' && $paisId != '' && $estado!= '')
            {
                $CategoriaProductoMySqlDAO = new CategoriaProductoMySqlDAO();

                $CategoriaProducto = $CategoriaProductoMySqlDAO->queryByProductoIdAndCategoriaIdMandanteAndPaisIdAndEstado($productoId,$categoriaId,$mandante,$paisId,$estado);
                $CategoriaProducto = $CategoriaProducto[0];

                if ($CategoriaProducto != null && $CategoriaProducto != "")
                {
                    $this->catprodId = $CategoriaProducto->catprodId;
                    $this->productoId = $CategoriaProducto->productoId;
                    $this->categoriaId = $CategoriaProducto->categoriaId;
                    $this->usucreaId = $CategoriaProducto->usucreaId;
                    $this->usumodifId = $CategoriaProducto->usumodifId;
                    $this->estado = $CategoriaProducto->estado;
                    $this->orden = $CategoriaProducto->orden;
                    $this->mandante = $CategoriaProducto->mandante;
                    $this->paisId = $CategoriaProducto->paisId;
                }
                else
                {
                    throw new Exception("No existe " . get_class($this), "49");
                }
            }

            elseif ($productoId != "" && $categoriaId != '' && $mandante != '' && $paisId != '')
            {
                $CategoriaProductoMySqlDAO = new CategoriaProductoMySqlDAO();

                $CategoriaProducto = $CategoriaProductoMySqlDAO->queryByProductoIdAndCategoriaIdMandanteAndPaisId($productoId,$categoriaId,$mandante,$paisId);
                $CategoriaProducto = $CategoriaProducto[0];

                if ($CategoriaProducto != null && $CategoriaProducto != "")
                {
                    $this->catprodId = $CategoriaProducto->catprodId;
                    $this->productoId = $CategoriaProducto->productoId;
                    $this->categoriaId = $CategoriaProducto->categoriaId;
                    $this->usucreaId = $CategoriaProducto->usucreaId;
                    $this->usumodifId = $CategoriaProducto->usumodifId;
                    $this->estado = $CategoriaProducto->estado;
                    $this->orden = $CategoriaProducto->orden;
                    $this->mandante = $CategoriaProducto->mandante;
                    $this->paisId = $CategoriaProducto->paisId;
                }
                else
                {
                    throw new Exception("No existe " . get_class($this), "49");
                }
            }


            elseif ($productoId != "" && $categoriaId != '' && $mandante != '')
            {
                $CategoriaProductoMySqlDAO = new CategoriaProductoMySqlDAO();

                $CategoriaProducto = $CategoriaProductoMySqlDAO->queryByProductoIdAndCategoriaIdMandante($productoId,$categoriaId,$mandante);
                $CategoriaProducto = $CategoriaProducto[0];

                if ($CategoriaProducto != null && $CategoriaProducto != "")
                {
                    $this->catprodId = $CategoriaProducto->catprodId;
                    $this->productoId = $CategoriaProducto->productoId;
                    $this->categoriaId = $CategoriaProducto->categoriaId;
                    $this->usucreaId = $CategoriaProducto->usucreaId;
                    $this->usumodifId = $CategoriaProducto->usumodifId;
                    $this->estado = $CategoriaProducto->estado;
                    $this->orden = $CategoriaProducto->orden;
                    $this->mandante = $CategoriaProducto->mandante;
                    $this->paisId = $CategoriaProducto->paisId;
                }
                else
                {
                    throw new Exception("No existe " . get_class($this), "49");
                }
            }
            elseif ($productoId != "")
            {
                $CategoriaProductoMySqlDAO = new CategoriaProductoMySqlDAO();

                $CategoriaProducto = $CategoriaProductoMySqlDAO->queryByProductoId($productoId);
                $CategoriaProducto = $CategoriaProducto[0];
                
                if ($CategoriaProducto != null && $CategoriaProducto != "") 
                {
                    $this->catprodId = $CategoriaProducto->catprodId;
                    $this->productoId = $CategoriaProducto->productoId;
                    $this->categoriaId = $CategoriaProducto->categoriaId;
                    $this->usucreaId = $CategoriaProducto->usucreaId;
                    $this->usumodifId = $CategoriaProducto->usumodifId;
                    $this->estado = $CategoriaProducto->estado;
                    $this->orden = $CategoriaProducto->orden;
                    $this->mandante = $CategoriaProducto->mandante;
                    $this->paisId = $CategoriaProducto->paisId;
                }
                else 
                {
                    throw new Exception("No existe " . get_class($this), "49");
                }
            }
        }





    /**
     * Obtener el campo categoriaId de un objeto
     *
     * @return String categoriaId categoriaId
     * 
     */
    public function getCategoriaId()
    {
        return $this->categoriaId;
    }

    /**
     * Modificar el campo 'categoriaId' de un objeto
     *
     * @param String $categoriaId categoriaId
     *
     * @return no
     *
     */
    public function setCategoriaId($categoriaId)
    {
        $this->categoriaId = $categoriaId;
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
     * @return string
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * @param string $estado
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    /**
     * @return string
     */
    public function getOrden()
    {
        return $this->orden;
    }

    /**
     * @param string $orden
     */
    public function setOrden($orden)
    {
        $this->orden = $orden;
    }

    /**
     * @return string
     */
    public function getMandante()
    {
        return $this->mandante;
    }

    /**
     * @param string $mandante
     */
    public function setMandante($mandante)
    {
        $this->mandante = $mandante;
    }

    /**
     * @return string
     */
    public function getCatprodId()
    {
        return $this->catprodId;
    }








    /**
     * Ejecutar una consulta sql
     * 
     *
     *
     * @param Objeto transaccion Transaccion
     *
     * @return Array resultado de la consulta
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */    
    public function insert($transaction)
    {

        $CategoriaProductoMySqlDAO = new CategoriaProductoMySqlDAO($transaction);
        return $CategoriaProductoMySqlDAO->insert($this);

    }

    /**
    * Realizar una consulta en la tabla de categoria_pruducto 'CategoriaProducto'
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
    public function getCategoriaProductosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $CategoriaProductoMySqlDAO = new CategoriaProductoMySqlDAO();

        $Productos = $CategoriaProductoMySqlDAO->queryCategoriaProductosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($Productos != null && $Productos != "")
        {
            return $Productos;
        } 
        else 
        {
            throw new Exception("No existe " . get_class($this), "01");
        }

    }

    /**
     * Obtiene una lista de productos de categoría personalizados para un mandante.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Índice de ordenación.
     * @param string $sord Orden de clasificación (ASC o DESC).
     * @param int $start Posición inicial para la consulta.
     * @param int $limit Número de registros a devolver.
     * @param array $filters Filtros a aplicar en la consulta.
     * @param bool $searchOn Indica si la búsqueda está activada.
     * @param string $mandanteSelect (Opcional) Selección específica del mandante.
     * 
     * @return array Lista de productos de categoría personalizados.
     * @throws Exception Si no existen productos de categoría.
     */
    public function getCategoriaProductosMandanteCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$mandanteSelect="") {

        $CategoriaProductoMySqlDAO = new CategoriaProductoMySqlDAO();

        $Productos = $CategoriaProductoMySqlDAO->queryCategoriaProductosMandanteCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$mandanteSelect);

        if ($Productos == '') throw new Exception("No existe " . get_class($this), "01");
        return $Productos;
    }

    /**
     * Ejecuta una consulta SQL utilizando la transacción y el SQL proporcionados.
     *
     * @param Transaction $transaccion La transacción a utilizar para la consulta.
     * @param string $sql La consulta SQL a ejecutar.
     * @return mixed El resultado de la consulta SQL.
     */
    public function execQuery($transaccion, $sql)
    {

        $CategoriaProductoMySqlDAO= new CategoriaProductoMySqlDAO($transaccion);
        $return = $CategoriaProductoMySqlDAO->querySQL($sql);

        //$return = json_decode(json_encode($return), FALSE);

        return $return;

    }
}

?>
