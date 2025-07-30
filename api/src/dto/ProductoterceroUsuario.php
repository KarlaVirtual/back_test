<?php namespace Backend\dto;
use Backend\mysql\ProductoterceroUsuarioMySqlDAO;
use Exception;
/**
* Clase 'Ingreso'
*
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'Ingreso'
*
* Ejemplo de uso:
* $ProductoterceroUsuario = new Ingreso();
*
*
* @package ninguno
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public
* @see no
*
*/
class ProductoterceroUsuario
{

    /**
    * Representación de la columna 'prodtercusuarioId' de la tabla 'ProductoterceroUsuario'
    *
    * @var string
    */
    var $prodtercusuarioId;

    /**
    * Representación de la columna 'productoId' de la tabla 'ProductoterceroUsuario'
    *
    * @var string
    */
    var $productoId;

    /**
    * Representación de la columna 'descripcion' de la tabla 'ProductoterceroUsuario'
    *
    * @var string
    */
    var $estado;

    /**
    * Representación de la columna 'usuarioId' de la tabla 'ProductoterceroUsuario'
    *
    * @var string
    */
    var $usuarioId;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'ProductoterceroUsuario'
    *
    * @var string
    */
    var $usucreaId;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'ProductoterceroUsuario'
    *
    * @var string
    */
    var $usumodifId;

    /**
    * Representación de la columna 'cuentacontableId' de la tabla 'ProductoterceroUsuario'
    *
    * @var string
    */
    var $cuentacontableId;

    /**
    * Representación de la columna 'cuentacontableegresoId' de la tabla 'ProductoterceroUsuario'
    *
    * @var string
    */
    var $cuentacontableegresoId;

    /**
    * Representación de la columna 'cupo' de la tabla 'ProductoterceroUsuario'
    *
    * @var string
    */
    var $cupo;

    /**
    * Constructor de clase
    *
    *
    * @param String $prodtercusuarioId prodtercusuarioId
    * @param String $codigo codigo
    *
    * @return no
    * @throws Exception si ProductoterceroUsuario no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($prodtercusuarioId = "", $codigo = "",$usuarioId,$productoId)
    {

        if ($prodtercusuarioId != "")
        {

            $ProductoterceroUsuarioMySqlDAO = new ProductoterceroUsuarioMySqlDAO();

            $ProductoterceroUsuario = $ProductoterceroUsuarioMySqlDAO->load($prodtercusuarioId);


            if ($ProductoterceroUsuario != null && $ProductoterceroUsuario != "")
            {
                $this->prodtercusuarioId = $ProductoterceroUsuario->prodtercusuarioId;
                $this->productoId = $ProductoterceroUsuario->productoId;
                $this->usuarioId = $ProductoterceroUsuario->usuarioId;
                $this->usucreaId = $ProductoterceroUsuario->usucreaId;
                $this->usumodifId = $ProductoterceroUsuario->usumodifId;
                $this->estado = $ProductoterceroUsuario->estado ;
                $this->cuentacontableId = $ProductoterceroUsuario->cuentacontableId;
                $this->cuentacontableegresoId = $ProductoterceroUsuario->cuentacontableegresoId;
                $this->cupo = $ProductoterceroUsuario->cupo;
            }
            else
            {
                throw new Exception("No existe " . get_class($this), "77");
            }

        }
        elseif ($codigo != "")
        {
            $ProductoterceroUsuarioMySqlDAO = new ProductoterceroUsuarioMySqlDAO();

            $ProductoterceroUsuario = $ProductoterceroUsuarioMySqlDAO->queryByAbreviado($codigo);

            $ProductoterceroUsuario = $ProductoterceroUsuario[0];

            if ($ProductoterceroUsuario != null && $ProductoterceroUsuario != "")
            {
                $this->prodtercusuarioId = $ProductoterceroUsuario->prodtercusuarioId;
                $this->productoId = $ProductoterceroUsuario->productoId;
                $this->usuarioId = $ProductoterceroUsuario->usuarioId;
                $this->usucreaId = $ProductoterceroUsuario->usucreaId;
                $this->usumodifId = $ProductoterceroUsuario->usumodifId;
                $this->cuentacontableId = $ProductoterceroUsuario->cuentacontableId;
                $this->estado = $ProductoterceroUsuario->estado ;
                $this->cuentacontableegresoId = $ProductoterceroUsuario->cuentacontableegresoId;
                $this->cupo = $ProductoterceroUsuario->cupo;

            }
            else
            {
                throw new Exception("No existe " . get_class($this), "77");
            }
        }
        elseif ($productoId != "" && $usuarioId != '')
        {
            $ProductoterceroUsuarioMySqlDAO = new ProductoterceroUsuarioMySqlDAO();

            $ProductoterceroUsuario = $ProductoterceroUsuarioMySqlDAO->queryByProductoIdAndUsuarioId($productoId,$usuarioId);

            $ProductoterceroUsuario = $ProductoterceroUsuario[0];

            if ($ProductoterceroUsuario != null && $ProductoterceroUsuario != "")
            {
                $this->prodtercusuarioId = $ProductoterceroUsuario->prodtercusuarioId;
                $this->productoId = $ProductoterceroUsuario->productoId;
                $this->usuarioId = $ProductoterceroUsuario->usuarioId;
                $this->usucreaId = $ProductoterceroUsuario->usucreaId;
                $this->usumodifId = $ProductoterceroUsuario->usumodifId;
                $this->cuentacontableId = $ProductoterceroUsuario->cuentacontableId;
                $this->estado = $ProductoterceroUsuario->estado ;
                $this->cuentacontableegresoId = $ProductoterceroUsuario->cuentacontableegresoId;
                $this->cupo = $ProductoterceroUsuario->cupo;

            }
            else
            {
                throw new Exception("No existe " . get_class($this), "77");
            }
        }
    }





    /**
     * Obtener el campo prodtercusuarioId de un objeto
     *
     * @return String prodtercusuarioId prodtercusuarioId
     *
     */
    public function getProdtercusuarioId()
    {
        return $this->prodtercusuarioId;
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
     * Obtener el campo descripcion de un objeto
     *
     * @return String descripcion descripcion
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
     * Obtener el campo cuentacontableId de un objeto
     * @return mixed
     */
    public function getCuentacontableId()
    {
        return $this->cuentacontableId;
    }

    /**
     * Modificar el campo 'cuentacontableId' de un objeto
     * @param mixed $cuentacontableId
     */
    public function setCuentacontableId($cuentacontableId)
    {
        $this->cuentacontableId = $cuentacontableId;
    }

    /**
     * Obtener el campo estado de un objeto
     * @return mixed
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Modificar el campo 'estado' de un objeto
     * @param mixed $estado
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    /**
     * Obtener el campo cuentacontableegresoId de un objeto
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
     * Obtener el campo cupo de un objeto
     * @return mixed
     */
    public function getCupo()
    {
        return $this->cupo;
    }

    /**
     * Modificar el campo 'cupo' de un objeto
     * @param mixed $cupo
     */
    public function setCupo($cupo)
    {
        $this->cupo = $cupo;
    }









    /**
    * Realizar una consulta en la tabla de ProductoterceroUsuario 'ProductoterceroUsuario'
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
    public function getProductoterceroUsuariosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $ProductoterceroUsuarioMySqlDAO = new ProductoterceroUsuarioMySqlDAO();

        $clasificadores = $ProductoterceroUsuarioMySqlDAO->queryProductoterceroUsuarioesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($clasificadores != null && $clasificadores != "")
        {
            return $clasificadores;
        }
        else
        {
            throw new Exception("No existe " . get_class($this), "77");
        }

    }


}

?>