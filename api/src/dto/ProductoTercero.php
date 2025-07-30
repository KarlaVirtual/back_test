<?php namespace Backend\dto;
use Backend\mysql\ProductoTerceroMySqlDAO;
use Exception;
/**
* Clase 'ProductoTercero'
*
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'ProductoTercero'
*
* Ejemplo de uso:
* $ProductoTercero = new ProductoTercero();
*
*
* @package ninguno
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public
* @see no
*
*/
class ProductoTercero
{

    /**
    * Representación de la columna 'productotercId' de la tabla 'ProductoTercero'
    *
    * @var string
    */
    var $productotercId;

    /**
    * Representación de la columna 'proveedortercId' de la tabla 'ProductoTercero'
    *
    * @var string
    */
    var $proveedortercId;

    /**
    * Representación de la columna 'descripcion' de la tabla 'ProductoTercero'
    *
    * @var string
    */
    var $descripcion;

    /**
    * Representación de la columna 'estado' de la tabla 'ProductoTercero'
    *
    * @var string
    */
    var $estado;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'ProductoTercero'
    *
    * @var string
    */
    var $usucreaId;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'ProductoTercero'
    *
    * @var string
    */
    var $usumodifId;

    /**
    * Representación de la columna 'cuentacontableId' de la tabla 'ProductoTercero'
    *
    * @var string
    */
    var $cuentacontableId;

    /**
    * Representación de la columna 'cuentacontableegresoId' de la tabla 'ProductoTercero'
    *
    * @var string
    */
    var $cuentacontableegresoId;

    /**
    * Representación de la columna 'interno' de la tabla 'ProductoTercero'
    *
    * @var string
    */
    var $interno;

    /**
    * Representación de la columna 'tipoId' de la tabla 'ProductoTercero'
    *
    * @var string
    */
    var $tipoId;

    /**
    * Representación de la columna 'tieneCupo' de la tabla 'ProductoTercero'
    *
    * @var string
    */
    var $tieneCupo;

    /**
    * Representación de la columna 'tipoAgente' de la tabla 'ProductoTercero'
    *
    * @var string
    */
    var $tipoAgente;

    /**
    * Constructor de clase
    *
    *
    * @param String $productotercId id del producto tercero
    * @param String $codigo codigo
    *
    * @return no
    * @throws Exception si ProductoTercero no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($productotercId = "", $codigo = "")
    {

        if ($productotercId != "")
        {

            $ProductoTerceroMySqlDAO = new ProductoTerceroMySqlDAO();

            $ProductoTercero = $ProductoTerceroMySqlDAO->load($productotercId);


            if ($ProductoTercero != null && $ProductoTercero != "")
            {
                $this->productotercId = $ProductoTercero->productotercId;
                $this->proveedortercId = $ProductoTercero->proveedortercId;
                $this->descripcion = $ProductoTercero->descripcion;
                $this->estado = $ProductoTercero->estado;
                $this->usucreaId = $ProductoTercero->usucreaId;
                $this->usumodifId = $ProductoTercero->usumodifId;
                $this->cuentacontableId = $ProductoTercero->cuentacontableId;
                $this->cuentacontableegresoId = $ProductoTercero->cuentacontableegresoId;
                $this->interno = $ProductoTercero->interno;
                $this->tipoId = $ProductoTercero->tipoId;
                $this->tieneCupo = $ProductoTercero->tieneCupo;
                $this->tipoAgente = $ProductoTercero->tipoAgente;
            }
            else
            {
                throw new Exception("No existe " . get_class($this), "76");
            }

        }
        elseif ($codigo != "")
        {
            $ProductoTerceroMySqlDAO = new ProductoTerceroMySqlDAO();

            $ProductoTercero = $ProductoTerceroMySqlDAO->queryByAbreviado($codigo);

            $ProductoTercero = $ProductoTercero[0];

            if ($ProductoTercero != null && $ProductoTercero != "")
            {
                $this->productotercId = $ProductoTercero->productotercId;
                $this->proveedortercId = $ProductoTercero->proveedortercId;
                $this->descripcion = $ProductoTercero->descripcion;
                $this->estado = $ProductoTercero->estado;
                $this->usucreaId = $ProductoTercero->usucreaId;
                $this->usumodifId = $ProductoTercero->usumodifId;
                $this->cuentacontableId = $ProductoTercero->cuentacontableId;
                $this->cuentacontableegresoId = $ProductoTercero->cuentacontableegresoId;
                $this->interno = $ProductoTercero->interno;
                $this->tipoId = $ProductoTercero->tipoId;
                $this->tieneCupo = $ProductoTercero->tieneCupo;
                $this->tipoAgente = $ProductoTercero->tipoAgente;
            }
            else
            {
                throw new Exception("No existe " . get_class($this), "76");
            }
        }
    }



    /**
     * Obtener el campo ProveedorTercId del objeto ProductoTercero
     * @return mixed
     */
    public function getProveedortercId()
    {
        return $this->proveedortercId;
    }

    /**
     * Modificar el campo 'proveedortercId' de un objeto
     *
     * @param String $proveedortercId proveedortercId
     *
     * @return no
     *
     */
    public function setProveedortercId($proveedortercId)
    {
        $this->proveedortercId = $proveedortercId;
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
     */    public function setUsucreaId($usucreaId)
    {
        $this->usucreaId = $usucreaId;
    }

    /**
     * @return mixed
     */
    public function getCuentacontableId()
    {
        return $this->cuentacontableId;
    }

    /**
     * Definir el campo cuentacontableId de un objeto
     * @param mixed $cuentacontableId
     */
    public function setCuentacontableId($cuentacontableId)
    {
        $this->cuentacontableId = $cuentacontableId;
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
     * Obtener el campo productotercId de un objeto
     * @return mixed
     */
    public function getProductotercId()
    {
        return $this->productotercId;
    }

    /**
     * Obtener el campo 'productotercId' de un objeto
     * @return mixed
     */
    public function getInterno()
    {
        return $this->interno;
    }

    /**
     * Modificar el campo 'interno' de un objeto
     * @param mixed $interno
     */
    public function setInterno($interno)
    {
        $this->interno = $interno;
    }

    /**
     * Obtener el campo 'tipoId' de un objeto
     * @return mixed
     */
    public function getTipoId()
    {
        return $this->tipoId;
    }

    /**
     * Modificar el campo 'tipoId' de un objeto
     * @param mixed $tipoId
     */
    public function setTipoId($tipoId)
    {
        $this->tipoId = $tipoId;
    }

    /**
     * Obtener el campo 'tieneCupo' de un objeto
     * @return mixed
     */
    public function getTieneCupo()
    {
        return $this->tieneCupo;
    }

    /**
     * Modificar el campo 'tieneCupo' de un objeto
     * @param mixed $tieneCupo
     */
    public function setTieneCupo($tieneCupo)
    {
        $this->tieneCupo = $tieneCupo;
    }


    /**
     * Obtener el campo 'cuentacontableegresoId' de un objeto  
     * @return mixed
     */
    public function getCuentacontableegresoId()
    {
        return $this->cuentacontableegresoId;
    }

    /**
     * Modificar el campo 'cuentacontableegresoId' de un objeto
     * @param mixed $cuentacontableegresoId
     */
    public function setCuentacontableegresoId($cuentacontableegresoId)
    {
        $this->cuentacontableegresoId = $cuentacontableegresoId;
    }

    /**
     * Obtener el campo 'tipoAgente' de un objeto
     * @return mixed
     */
    public function getTipoAgente()
    {
        return $this->tipoAgente;
    }

    /**
     * Modificar el campo 'tipoAgente' de un objeto
     * @param mixed $tipoAgente
     */
    public function setTipoAgente($tipoAgente)
    {
        $this->tipoAgente = $tipoAgente;
    }








    /**
    * Realizar una consulta en la tabla de ProductoTercero 'ProductoTercero'
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
    * @throws Exception si los clasificadores no existen
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getProductoTercerosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $ProductoTerceroMySqlDAO = new ProductoTerceroMySqlDAO();

        $clasificadores = $ProductoTerceroMySqlDAO->queryProductoTerceroesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($clasificadores != null && $clasificadores != "") {

            return $clasificadores;

        } else {
            throw new Exception("No existe " . get_class($this), "76");
        }

    }

    /**
     * Get Domain object by primry key
     *
     * @param
     * @return int
     */
    public function getProductoTercerosXUsuarioCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $ProductoTerceroMySqlDAO = new ProductoTerceroMySqlDAO();

        $clasificadores = $ProductoTerceroMySqlDAO->queryProductoTercerosXUsuarioCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($clasificadores != null && $clasificadores != "") {

            return $clasificadores;
        }
        else
        {
            throw new Exception("No existe " . get_class($this), "76");
        }

    }



}

?>