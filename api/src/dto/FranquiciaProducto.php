<?php namespace Backend\dto;
use Backend\mysql\FranquiciaProductoMySqlDAO;
use Backend\mysql\FranquiciaMySqlDAO;
use Backend\sql\SqlQuery;
use Exception;
/**
 * Clase 'Franquicia'
 *
 * Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
 * para la tabla 'Franquicia'
 *
 * Ejemplo de uso:
 * $Franquicia = new Franquicia();
 *
 *
 * @package ninguno
 * @author Juan Salazar <juan.salazar@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class FranquiciaProducto
{

    /**
     * Representación de la columna 'FranquiciaId' de la tabla 'Franquicia'
     *
     * @var string
     */
    var $franquiciaProductoId;
    var $franquiciaId;

    /**
     * Representación de la columna 'descripcion' de la tabla 'Franquicia'
     *
     * @var string
     */
    var $productoId;

    /**
     * Representación de la columna 'paisId' de la tabla 'Franquicia'
     *
     * @var string
     */
    var $paisId;

    /**
     * Representación de la columna 'estado' de la tabla 'Franquicia'
     *
     * @var string
     */
    var $mandante;

    var $estado;
    var $fechaCrea;
    var $fechaModif;
    var $usucreaId;
    var $usumodifId;
    var $abreviado;
    var $imagen;


    /**
     * Constructor de clase
     *
     *
     * @param String $Franquicia_id id del Franquicia
     *
     * @throws Exception si el Franquicia no existe
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function __construct($FranquiciaProductoId="")
    {

        if($FranquiciaProductoId != "")
        {

            $FranquiciaProductoMySqlDAO = new FranquiciaProductoMySqlDAO();

            $FranquiciaProducto = $FranquiciaProductoMySqlDAO->load($FranquiciaProductoId);


            if ($FranquiciaProducto != null && $FranquiciaProducto != "")
            {
                $this->franquiciaProductoId = $FranquiciaProducto->franquiciaProductoId;
                $this->franquiciaId = $FranquiciaProducto->franquiciaId;
                $this->productoId = $FranquiciaProducto->productoId;
                $this->paisId = $FranquiciaProducto->paisId;
                $this->mandante = $FranquiciaProducto->mandante;
                $this->estado = $FranquiciaProducto->estado;
                $this->fechaCrea = $FranquiciaProducto->fechaCrea;
                $this->fechaModif = $FranquiciaProducto->fechaModif;
                $this->usucreaId = $FranquiciaProducto->usucreaId;
                $this->usumodifId = $FranquiciaProducto->usumodifId;
                $this->abreviado = $FranquiciaProducto->abreviado;
                $this->imagen = $FranquiciaProducto->imagen;
            }
            else
            {
                throw new Exception("No existe " . get_class($this), "35");
            }

        }

    }

    /**
     * @return string
     */
    public function getFranquiciaProductoId()
    {
        return $this->franquiciaProductoId;
    }

    /**
     * @param string $FranquiciaProductoId
     */
    public function setFranquiciaProductoId($FranquiciaProductoId)
    {
        $this->franquiciaProductoId = $FranquiciaProductoId;
    }

    /**
     * @return string
     */
    public function getFranquiciaId()
    {
        return $this->franquiciaId;
    }

    /**
     * @param string $FranquiciaId
     */
    public function setFranquiciaId($FranquiciaId)
    {
        $this->franquiciaId = $FranquiciaId;
    }

    /**
     * @return string
     */
    public function getProductoId()
    {
        return $this->productoId;
    }

    /**
     * @param string $productoId
     */
    public function setProductoId($productoId)
    {
        $this->productoId = $productoId;
    }
    /**
     * @param string $abreviado
     */
    public function setAbreviado($abreviado)
    {
        $this->abreviado = $abreviado;
    }

    /**
     * @param string $abreviado
     */
    public function getAbreviado($abreviado)
    {
        return $this->abreviado;
    }

    /**
     * @param string $imagen
     */
    public function setImagen($imagen)
    {
        $this->imagen = $imagen;
    }

    /**
     * @param string $imagen
     */
    public function getImagen($imagen)
    {
        return $this->imagen ;
    }

    /**
     * @return string
     */
    public function getPaisId()
    {
        return $this->paisId;
    }

    /**
     * @param string $paisId
     */
    public function setPaisId($paisId)
    {
        $this->paisId = $paisId;
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
     * @return mixed
     */
    public function getFechaCrea()
    {
        return $this->fechaCrea;
    }

    /**
     * @param mixed $fechaCrea
     */
    public function setFechaCrea($fechaCrea)
    {
        $this->fechaCrea = $fechaCrea;
    }

    /**
     * @return mixed
     */
    public function getFechaModif()
    {
        return $this->fechaModif;
    }

    /**
     * @param mixed $fechaModif
     */
    public function setFechaModif($fechaModif)
    {
        $this->fechaModif = $fechaModif;
    }

    /**
     * @return mixed
     */
    public function getUsucreaId()
    {
        return $this->usucreaId;
    }

    /**
     * @param mixed $usucreaId
     */
    public function setUsucreaId($usucreaId)
    {
        $this->usucreaId = $usucreaId;
    }

    /**
     * @return mixed
     */
    public function getUsumodifId()
    {
        return $this->usumodifId;
    }

    /**
     * @param mixed $usumodifId
     */
    public function setUsumodifId($usumodifId)
    {
        $this->usumodifId = $usumodifId;
    }


    /**
     * Realizar una consulta en la tabla de Franquicia 'Franquicia'
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
     * @throws Exception si el bono no existe
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function queryFranquiciaProductosCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping="")
    {

        $FranquiciaProductoMySqlDAO= new FranquiciaProductoMySqlDAO();

        $FranquiciasProductos = $FranquiciaProductoMySqlDAO->queryFranquiciaProductosCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping);

        if ($FranquiciasProductos != null && $FranquiciasProductos != "")
        {
            return $FranquiciasProductos;
        }
        else
        {
            throw new Exception("No existe " . get_class($this), "35");
        }


    }

    /**
     * Realizar una consulta en la tabla de Franquicia 'Franquicia'
     * de una manera personalizada con consultas en la tabla 'Franquicia_mandante' o 'Franquicia'
     *
     *
     * @param String $select campos de consulta
     * @param String $sidx columna para ordenar
     * @param String $sord orden los datos asc | desc
     * @param String $start inicio de la consulta
     * @param String $limit limite de la consulta
     * @param String $filters condiciones de la consulta
     * @param boolean $searchOn utilizar los filtros o no
     * @param String $mandante para generar consulta por mandante
     * @param String $paisId para generar consulta por pais
     *
     * @return Array resultado de la consulta
     * @throws Exception si el bono no existe
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function getFranquiciasCustomMandante($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$mandante,$paisId='')
    {
        $FranquiciaProductoMySqlDAO = new FranquiciaProductoMySqlDAO();

        $Franquicias = $FranquiciaProductoMySqlDAO->queryFranquiciasCustomMandante($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$mandante,$paisId);

        if ($Franquicias != null && $Franquicias != "")
        {
            return $Franquicias;
        }
        else
        {
            throw new Exception("No existe " . get_class($this), "01");
        }

    }

    /**
     * Realizar una consulta en la tabla de Franquicia 'Franquicia_Producto'
     * de una manera personalizada con consultas en la tabla 'Franquicia_mandante', 'producto'
     *
     *
     * @param String $select campos de consulta
     * @param String $sidx columna para ordenar
     * @param String $sord orden los datos asc | desc
     * @param String $start inicio de la consulta
     * @param String $limit limite de la consulta
     * @param String $filters condiciones de la consulta
     * @param boolean $searchOn utilizar los filtros o no
     * @param String $mandante para generar consulta por mandante
     * @param String $paisId para generar consulta por pais
     *
     * @return Array resultado de la consulta
     * @throws Exception si el bono no existe
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function getFranquiciasMandanteProductosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$mandante,$paisId='')
    {
        $FranquiciaProductoMySqlDAO = new FranquiciaProductoMySqlDAO();

        $Franquicias = $FranquiciaProductoMySqlDAO->queryFranquiciasMandanteProductosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$mandante,$paisId);

        if ($Franquicias != null && $Franquicias != "")
        {
            return $Franquicias;
        }
        else
        {
            throw new Exception("No existen productos enlazados a la franquicia", "300161");
        }

    }

    /**
     * Ejecutar una consulta sql
     *
     *
     *
     * @param Objeto $transaccion transacción
     * @param String $sql consulta sql
     *
     * @return Array $ resultado de la ejecución
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function execQuery($transaccion, $sql)
    {

        $FranquiciaProductoMySqlDAO = new FranquiciaProductoMySqlDAO($transaccion);
        $return = $FranquiciaProductoMySqlDAO->querySQL($sql);
        $return = json_decode(json_encode($return), FALSE);

        return $return;

    }

}
?>