<?php 
namespace Backend\dto;
use Backend\mysql\CentroCostoMySqlDAO;
use Exception;
/**
* Clase 'CentroCosto'
*
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'centro_costo'
*
* Ejemplo de uso:
* $CentroCosto = new CentroCosto();
*
*
* @package ninguno
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public
* @see no
*
*/
class CentroCosto
{

    /**
    * Representación de la columna 'centrocostoId' de la tabla 'CentroCosto'
    *
    * @var string
    */
    var $centrocostoId;

    /**
    * Representación de la columna 'tipoId' de la tabla 'CentroCosto'
    *
    * @var string
    */
    var $tipoId;

    /**
    * Representación de la columna 'descripcion' de la tabla 'CentroCosto'
    *
    * @var string
    */
    var $descripcion;

    /**
    * Representación de la columna 'estado' de la tabla 'CentroCosto'
    *
    * @var string
    */
    var $estado;

    /**
    * Representación de la columna 'mandante' de la tabla 'CentroCosto'
    *
    * @var string
    */
    var $mandante;

    /**
    * Representación de la columna 'codigo' de la tabla 'CentroCosto'
    *
    * @var string
    */
    var $codigo;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'CentroCosto'
    *
    * @var string
    */
    var $usucreaId;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'CentroCosto'
    *
    * @var string
    */
    var $usumodifId;

    /**
    * Constructor de clase
    *
    *
    * @param String $centrocostoId centrocostoId
    * @param String $codigo codigo
    *
    * @return no
    * @throws Exception si CentroCosto no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($centrocostoId = "", $codigo = "")
    {

        if ($centrocostoId != "")
        {

            $CentroCostoMySqlDAO = new CentroCostoMySqlDAO();

            $CentroCosto = $CentroCostoMySqlDAO->load($centrocostoId);


            if ($CentroCosto != null && $CentroCosto != "")
            {
                $this->centrocostoId = $CentroCosto->centrocostoId;
                $this->tipoId = $CentroCosto->tipoId;
                $this->descripcion = $CentroCosto->descripcion;
                $this->estado = $CentroCosto->estado;
                $this->mandante = $CentroCosto->mandante;
                $this->codigo = $CentroCosto->codigo;
                $this->usucreaId = $CentroCosto->usucreaId;
                $this->usumodifId = $CentroCosto->usumodifId;
            }
            else
            {
                throw new Exception("No existe " . get_class($this), "70");
            }

        }
        elseif ($codigo != "")
        {
            $CentroCostoMySqlDAO = new CentroCostoMySqlDAO();

            $CentroCosto = $CentroCostoMySqlDAO->queryByAbreviado($codigo);

            $CentroCosto = $CentroCosto[0];

            if ($CentroCosto != null && $CentroCosto != "")
            {
                $this->centrocostoId = $CentroCosto->centrocostoId;
                $this->tipoId = $CentroCosto->tipoId;
                $this->descripcion = $CentroCosto->descripcion;
                $this->estado = $CentroCosto->estado;
                $this->mandante = $CentroCosto->mandante;
                $this->codigo = $CentroCosto->codigo;
                $this->usucreaId = $CentroCosto->usucreaId;
                $this->usumodifId = $CentroCosto->usumodifId;
            }
            else
            {
                throw new Exception("No existe " . get_class($this), "70");
            }
        }
    }





    /**
     * Obtener el campo tipoId de un objeto
     *
     * @return String tipoId tipoId
     *
     */
    public function getTipoId()
    {
        return $this->tipoId;
    }

    /**
     * Modificar el campo 'tipoId' de un objeto
     *
     * @param String $tipoId tipoId
     *
     * @return no
     *
     */
    public function setTipoId($tipoId)
    {
        $this->tipoId = $tipoId;
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
     * Obtener el campo mandante de un objeto
     *
     * @return String mandante mandante
     *
     */
    public function getMandante()
    {
        return $this->mandante;
    }

    /**
     * Modificar el campo 'mandante' de un objeto
     *
     * @param String $mandante mandante
     *
     * @return no
     *
     */
    public function setMandante($mandante)
    {
        $this->mandante = $mandante;
    }

    /**
     * Obtener el campo codigo de un objeto
     *
     * @return String codigo codigo
     *
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Modificar el campo 'codigo' de un objeto
     *
     * @param String $codigo codigo
     *
     * @return no
     *
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    /**
     * Obtener el campo centrocostoId de un objeto
     *
     * @return String centrocostoId centrocostoId
     *
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
     * @return mixed
     */
    public function getCentrocostoId()
    {
        return $this->centrocostoId;
    }






    /**
    * Realizar una consulta en la tabla de centro_costo 'CentroCosto'
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
    public function getCentroCostosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $CentroCostoMySqlDAO = new CentroCostoMySqlDAO();

        $clasificadores = $CentroCostoMySqlDAO->queryCentroCostoesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($clasificadores != null && $clasificadores != "")
        {
            return $clasificadores;
        }
        else
        {
            throw new Exception("No existe " . get_class($this), "70");
        }

    }


}

?>