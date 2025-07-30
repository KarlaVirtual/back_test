<?php namespace Backend\dto;
use Backend\mysql\IngresoMySqlDAO;
use Exception;
/**
* Clase 'Ingreso'
*
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'Ingreso'
*
* Ejemplo de uso:
* $Ingreso = new Ingreso();
*
*
* @package ninguno
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public
* @see no
*
*/
class Ingreso
{

    /**
    * Representación de la columna 'ingresoId' de la tabla 'Ingreso'
    *
    * @var string
    */
    var $ingresoId;

    /**
    * Representación de la columna 'tipoId' de la tabla 'Ingreso'
    *
    * @var string
    */
    var $tipoId;

    /**
    * Representación de la columna 'descripcion' de la tabla 'Ingreso'
    *
    * @var string
    */
    var $descripcion;

    /**
    * Representación de la columna 'estado' de la tabla 'Ingreso'
    *
    * @var string
    */
    var $estado;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'Ingreso'
    *
    * @var string
    */
    var $usucreaId;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'Ingreso'
    *
    * @var string
    */
    var $usumodifId;

    /**
    * Representación de la columna 'centrocostoId' de la tabla 'Ingreso'
    *
    * @var string
    */
    var $centrocostoId;

    /**
    * Representación de la columna 'documento' de la tabla 'Ingreso'
    *
    * @var string
    */
    var $documento;

    /**
    * Representación de la columna 'valor' de la tabla 'Ingreso'
    *
    * @var string
    */
    var $valor;

    /**
    * Representación de la columna 'impuesto' de la tabla 'Ingreso'
    *
    * @var string
    */
    var $impuesto;

    /**
    * Representación de la columna 'retraccion' de la tabla 'Ingreso'
    *
    * @var string
    */
    var $retraccion;

    /**
    * Representación de la columna 'usuarioId' de la tabla 'Ingreso'
    *
    * @var string
    */
    var $usuarioId;

    /**
    * Representación de la columna 'conceptoId' de la tabla 'Ingreso'
    *
    * @var string
    */
    var $conceptoId;

    /** Representación de la columna 'productotercId' de la tabla 'Ingreso' */
    var $productotercId;

    /** Representación de la columna 'usucajeroId' de la tabla 'Ingreso' */
    var $usucajeroId;

    /** Representación de la columna 'proveedortercId' de la tabla 'Ingreso' */
    var $proveedortercId;

    /** Representación de la columna 'consecutivo' de la tabla 'Ingreso' */
    var $consecutivo;

    /** Representación de la columna 'fechaCrea' de la tabla 'Ingreso' */
    var $fechaCrea;

    /**
    * Constructor de clase
    *
    *
    * @param String $ingresoId id del ingreso
    * @param String $codigo codigo
    *
    * @return no
    * @throws Exception si el ingre no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($ingresoId = "", $codigo = "")
    {

        if ($ingresoId != "")
        {

            $IngresoMySqlDAO = new IngresoMySqlDAO();

            $Ingreso = $IngresoMySqlDAO->load($ingresoId);


            if ($Ingreso != null && $Ingreso != "")
            {
                $this->ingresoId = $Ingreso->ingresoId;
                $this->tipoId = $Ingreso->tipoId;
                $this->descripcion = $Ingreso->descripcion;
                $this->estado = $Ingreso->estado;
                $this->usucreaId = $Ingreso->usucreaId;
                $this->usumodifId = $Ingreso->usumodifId;
                $this->centrocostoId = $Ingreso->centrocostoId;;
                $this->documento = $Ingreso->documento;
                $this->valor = $Ingreso->valor;
                $this->impuesto = $Ingreso->retraccion;
                $this->conceptoId = $Ingreso->conceptoId;
                $this->productotercId = $Ingreso->productotercId;
                $this->proveedortercId = $Ingreso->proveedortercId;
                $this->consecutivo = $Ingreso->consecutivo;
                $this->fechaCrea = $Ingreso->fechaCrea;
                $this->usuarioId = $Ingreso->usuarioId;

            }
            else
            {
                throw new Exception("No existe " . get_class($this), "79");
            }

        } elseif ($codigo != "")
        {

            $IngresoMySqlDAO = new IngresoMySqlDAO();

            $Ingreso = $IngresoMySqlDAO->queryByAbreviado($codigo);

            $Ingreso = $Ingreso[0];

            if ($Ingreso != null && $Ingreso != "")
            {
                $this->ingresoId = $Ingreso->ingresoId;
                $this->tipoId = $Ingreso->tipoId;
                $this->descripcion = $Ingreso->descripcion;
                $this->estado = $Ingreso->estado;
                $this->usucreaId = $Ingreso->usucreaId;
                $this->usumodifId = $Ingreso->usumodifId;
                $this->centrocostoId = $Ingreso->centrocostoId;;
                $this->documento = $Ingreso->documento;
                $this->valor = $Ingreso->valor;
                $this->impuesto = $Ingreso->retraccion;
                $this->conceptoId = $Ingreso->conceptoId;
                $this->productotercId = $Ingreso->productotercId;
                $this->proveedortercId = $Ingreso->proveedortercId;
                $this->consecutivo = $Ingreso->consecutivo;
                $this->fechaCrea = $Ingreso->fechaCrea;
                $this->usuarioId = $Ingreso->usuarioId;
            }
            else
            {
                throw new Exception("No existe " . get_class($this), "79");
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
     * Obtener el campo centrocostoId de un objeto
     *
     * @return String centrocostoId centrocostoId
     *
     */
    public function getCentrocostoId()
    {
        return $this->centrocostoId;
    }

    /**
     * Modificar el campo 'centrocostoId' de un objeto
     *
     * @param String $centrocostoId centrocostoId
     *
     * @return no
     *
     */
    public function setCentrocostoId($centrocostoId)
    {
        $this->centrocostoId = $centrocostoId;
    }

    /**
     * Obtener el campo documento de un objeto
     *
     * @return String documento documento
     *
     */
    public function getDocumento()
    {
        return $this->documento;
    }

    /**
     * Modificar el campo 'documento' de un objeto
     *
     * @param String $documento documento
     *
     * @return no
     *
     */
    public function setDocumento($documento)
    {
        $this->documento = $documento;
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
     * Obtener el campo impuesto de un objeto
     *
     * @return String impuesto impuesto
     *
     */
    public function getImpuesto()
    {
        return $this->impuesto;
    }

    /**
     * Modificar el campo 'impuesto' de un objeto
     *
     * @param String $impuesto impuesto
     *
     * @return no
     *
     */
    public function setImpuesto($impuesto)
    {
        $this->impuesto = $impuesto;
    }

    /**
     * Obtener el campo retraccion de un objeto
     *
     * @return String retraccion retraccion
     *
     */
    public function getRetraccion()
    {
        return $this->retraccion;
    }

    /**
     * Modificar el campo 'retraccion' de un objeto
     *
     * @param String $retraccion retraccion
     *
     * @return no
     *
     */
    public function setRetraccion($retraccion)
    {
        $this->retraccion = $retraccion;
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
     * Modificar el campo 'conceptoId' de un objeto
     *
     * @param String $conceptoId conceptoId
     *
     * @return no
     *
     */
    public function setConceptoId($conceptoId)
    {
        $this->conceptoId = $conceptoId;
    }

    /**
     * Obtener el campo ingresoId de un objeto
     *
     * @return String ingresoId ingresoId
     *
     */
    public function getIngresoId()
    {
        return $this->ingresoId;
    }

    /**
     * Obtiene el campo productotercId del Ingreso
     * @return mixed
     */
    public function getProductotercId()
    {
        return $this->productotercId;
    }

    /**
     * Modifica el campo productotercId del Ingreso
     * @param mixed $productotercId
     */
    public function setProductotercId($productotercId)
    {
        $this->productotercId = $productotercId;
    }

    /**
     * Obtiene el campo usucajeroId del Ingreso
     * @return mixed
     */
    public function getUsucajeroId()
    {
        return $this->usucajeroId;
    }

    /**
     * Modifica el campo usucajeroId del Ingreso
     * @param mixed $usucajeroId
     */
    public function setUsucajeroId($usucajeroId)
    {
        $this->usucajeroId = $usucajeroId;
    }

    /**
     * Obtiene el campo proveedortercId del Ingreso
     * @return mixed
     */
    public function getProveedortercId()
    {
        return $this->proveedortercId;
    }

    /**
     * Modifica el campo proveedortercId del Ingreso
     * @param mixed $proveedortercId
     */
    public function setProveedortercId($proveedortercId)
    {
        $this->proveedortercId = $proveedortercId;
    }

    /**
     * Obtiene el campo consecutivo del Ingreso
     * @return mixed
     */
    public function getConsecutivo()
    {
        return $this->consecutivo;
    }

    /**
     * Modifica el campo consecutivo del Ingreso
     * @param mixed $consecutivo
     */
    public function setConsecutivo($consecutivo)
    {
        $this->consecutivo = $consecutivo;
    }

    /**
     * Obtiene el campo fechaCrea del Ingreso
     * @return mixed
     */
    public function getFechaCrea()
    {
        return $this->fechaCrea;
    }



    /**
    * Realizar una consulta en la tabla de ingresos 'Ingreso'
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
    public function getIngresosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$grouping="")
    {

        $IngresoMySqlDAO = new IngresoMySqlDAO();

        $clasificadores = $IngresoMySqlDAO->queryIngresoesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$grouping);

        if ($clasificadores != null && $clasificadores != "")
        {
            return $clasificadores;
        }
        else
        {
            throw new Exception("No existe " . get_class($this), "79");
        }

    }


}

?>