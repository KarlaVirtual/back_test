<?php namespace Backend\dto;
use Backend\mysql\ConceptoMySqlDAO;
use Exception;
/**
* Clase 'Concepto'
*
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'Concepto'
*
* Ejemplo de uso:
* $Concepto = new Concepto();
*
*
* @package ninguno
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public
* @see no
*
*/
class Concepto
{

    /**
    * Representación de la columna 'conceptoId' de la tabla 'Concepto'
    *
    * @var string
    */
    var $conceptoId;

    /**
    * Representación de la columna 'plancuentaId' de la tabla 'Concepto'
    *
    * @var string
    */
    var $cuentacontableId;

    /**
    * Representación de la columna 'descripcion' de la tabla 'Concepto'
    *
    * @var string
    */
    var $descripcion;

    /**
    * Representación de la columna 'referencia' de la tabla 'Concepto'
    *
    * @var string
    */
    var $referencia;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'Concepto'
    *
    * @var string
    */
    var $usucreaId;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'Concepto'
    *
    * @var string
    */
    var $usumodifId;

    var $tipo;

    var $usuarioId;

    /**
     * Concepto constructor.
     * @param $conceptoId

    /**
    * Constructor de clase
    *
    *
    * @param String $conceptoId
    * @param String $codigo
    *
    * @return no
    * @throws Exception si el concepto no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($conceptoId = "", $codigo = "")
    {

        if ($conceptoId != "")
        {

            $ConceptoMySqlDAO = new ConceptoMySqlDAO();

            $Concepto = $ConceptoMySqlDAO->load($conceptoId);


            if ($Concepto != null && $Concepto != "")
            {
                $this->conceptoId = $Concepto->conceptoId;
                $this->cuentacontableId = $Concepto->cuentacontableId;
                $this->descripcion = $Concepto->descripcion;
                $this->referencia = $Concepto->referencia;
                $this->usucreaId = $Concepto->usucreaId;
                $this->usumodifId = $Concepto->usumodifId;
                $this->tipo = $Concepto->tipo;
                $this->usuarioId = $Concepto->usuarioId;
            }
            else
            {
                throw new Exception("No existe " . get_class($this), "74");
            }

        }
        elseif ($codigo != "")
        {
            $ConceptoMySqlDAO = new ConceptoMySqlDAO();

            $Concepto = $ConceptoMySqlDAO->queryByAbreviado($codigo);

            $Concepto = $Concepto[0];

            if ($Concepto != null && $Concepto != "")
            {
                $this->conceptoId = $Concepto->conceptoId;
                $this->cuentacontableId = $Concepto->cuentacontableId;
                $this->descripcion = $Concepto->descripcion;
                $this->referencia = $Concepto->referencia;
                $this->usucreaId = $Concepto->usucreaId;
                $this->usumodifId = $Concepto->usumodifId;
                $this->tipo = $Concepto->tipo;
                $this->usuarioId = $Concepto->usuarioId;
            }
            else
            {
                throw new Exception("No existe " . get_class($this), "74");
            }
        }
    }






    /**
     * Obtener el campo plancuentaId de un objeto
     *
     * @return String plancuentaId plancuentaId
     *
     */
    public function getPlancuentaId()
    {
        return $this->cuentacontableId;
    }

    /**
     * Modificar el campo 'plancuentaId' de un objeto
     *
     * @param String $plancuentaId plancuentaId
     *
     * @return no
     *
     */
    public function setPlancuentaId($cuentacontableId)
    {
        $this->cuentacontableId = $cuentacontableId;
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
     * Obtener el campo referencia de un objeto
     *
     * @return String referencia referencia
     *
     */
    public function getReferencia()
    {
        return $this->referencia;
    }

    /**
     * Modificar el campo 'referencia' de un objeto
     *
     * @param String $referencia referencia
     *
     * @return no
     *
     */
    public function setReferencia($referencia)
    {
        $this->referencia = $referencia;
    }

    /**
     * Obtener el campo usucreaId de un objeto
     *
     * @return String usucreaId compusucreaIdpuntoId
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
     * Obtener el campo conceptoId de un objeto
     *
     * @return String conceptoId conceptoId
     *
     */
    public function getConceptoId()
    {
        return $this->conceptoId;
    }

    /**
     * @return mixed
     */
    public function getCuentacontableId()
    {
        return $this->cuentacontableId;
    }

    /**
     * @param mixed $cuentacontableId
     */
    public function setCuentacontableId($cuentacontableId)
    {
        $this->cuentacontableId = $cuentacontableId;
    }

    /**
     * @return mixed
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @param mixed $tipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    /**
     * @return mixed
     */
    public function getUsuarioId()
    {
        return $this->usuarioId;
    }

    /**
     * @param mixed $usuarioId
     */
    public function setUsuarioId($usuarioId)
    {
        $this->usuarioId = $usuarioId;
    }








    /**
    * Realizar una consulta en la tabla de conceptos 'Concepto'
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
    public function getConceptosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $ConceptoMySqlDAO = new ConceptoMySqlDAO();

        $clasificadores = $ConceptoMySqlDAO->queryConceptoesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($clasificadores != null && $clasificadores != "")
        {
            return $clasificadores;
        }
        else
        {
            throw new Exception("No existe " . get_class($this), "74");
        }

    }


}

?>