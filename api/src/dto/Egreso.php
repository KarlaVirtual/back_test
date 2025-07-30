<?php 
namespace Backend\dto;
use Backend\mysql\EgresoMySqlDAO;
use Exception;
/**
* Clase 'Egreso'
*
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'Egreso'
*
* Ejemplo de uso:
* $Egreso = new Egreso();
*
*
* @package ninguno
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public
* @see no
*
*/
class Egreso
{


    /**
    * Representación de la columna 'egresoId' de la tabla 'Egreso'
    *
    * @var string
    */
    var $egresoId;

    /**
    * Representación de la columna 'tipoId' de la tabla 'Egreso'
    *
    * @var string
    */
    var $tipoId;

    /**
    * Representación de la columna 'descripcion' de la tabla 'Egreso'
    *
    * @var string
    */
    var $descripcion;

    /**
    * Representación de la columna 'estado' de la tabla 'Egreso'
    *
    * @var string
    */
    var $estado;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'Egreso'
    *
    * @var string
    */
    var $usucreaId;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'Egreso'
    *
    * @var string
    */
    var $usumodifId;

    /**
    * Representación de la columna 'centrocostoId' de la tabla 'Egreso'
    *
    * @var string
    */
    var $centrocostoId;

    /**
    * Representación de la columna 'documento' de la tabla 'Egreso'
    *
    * @var string
    */
    var $documento;

    /**
    * Representación de la columna 'valor' de la tabla 'Egreso'
    *
    * @var string
    */
    var $valor;

    /**
    * Representación de la columna 'impuesto' de la tabla 'Egreso'
    *
    * @var string
    */
    var $impuesto;

    /**
    * Representación de la columna 'retraccion' de la tabla 'Egreso'
    *
    * @var string
    */
    var $retraccion;

    /**
    * Representación de la columna 'usuarioId' de la tabla 'Egreso'
    *
    * @var string
    */
    var $usuarioId;

    /**
    * Representación de la columna 'conceptoId' de la tabla 'Egreso'
    *
    * @var string
    */
    var $conceptoId;

    /**
     * Representación de la columna 'productotercId' de la tabla 'egreso'
     *
     * @var string
     */
    var $productotercId;

    /**
     * Representación de la columna 'usucajero_id' de la tabla 'egreso'
     *
     * @var string
     */
    var $usucajeroId;

    /**
     * Representación de la columna 'proveedortercId' de la tabla 'egreso'
     *
     * @var string
     */
    var $proveedortercId;

    /**
     * Representación de la columna 'consecutivo' de la tabla 'egreso'
     *
     * @var string
     */
    var $consecutivo;

    /**
     * Representación de la columna 'fecha_crea' de la tabla 'egreso'
     *
     * @var string
     */
    var $fechaCrea;

    /**
     * Representación de la columna 'tipo_documento' de la tabla 'egreso'
     *
     * @var string
     */
    var $tipoDocumento;

    /**
     * Representación de la columna 'serie' de la tabla 'egreso'
     *
     * @var string
     */
    var $serie;


    /**
    * Constructor de clase
    *
    *
    * @param String $egresoId id del egreso
    * @param String $codigo codigo
    *
    * @return no
    * @throws Exception si el egreso no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($egresoId = "", $codigo = "")
    {

        if ($egresoId != "") {

            $EgresoMySqlDAO = new EgresoMySqlDAO();

            $Egreso = $EgresoMySqlDAO->load($egresoId);


            if ($Egreso != null && $Egreso != "") {
                $this->egresoId = $Egreso->egresoId;
                $this->tipoId = $Egreso->tipoId;
                $this->descripcion = $Egreso->descripcion;
                $this->estado = $Egreso->estado;
                $this->usucreaId = $Egreso->usucreaId;
                $this->usumodifId = $Egreso->usumodifId;
                $this->centrocostoId = $Egreso->centrocostoId;;
                $this->documento = $Egreso->documento;
                $this->valor = $Egreso->valor;
                $this->impuesto = $Egreso->retraccion;
                $this->conceptoId = $Egreso->conceptoId;
                $this->productotercId = $Egreso->productotercId;
                $this->proveedortercId = $Egreso->proveedortercId;
                $this->usucajeroId = $Egreso->usucajeroId;
                $this->consecutivo = $Egreso->consecutivo;
                $this->fechaCrea = $Egreso->fechaCrea;
                $this->usuarioId = $Egreso->usuarioId;
                $this->tipoDocumento = $Egreso->tipoDocumento;
                $this->serie = $Egreso->serie;

            } else {
                throw new Exception("No existe " . get_class($this), "78");
            }

        } elseif ($codigo != "") {
            $EgresoMySqlDAO = new EgresoMySqlDAO();

            $Egreso = $EgresoMySqlDAO->queryByAbreviado($codigo);

            $Egreso = $Egreso[0];

            if ($Egreso != null && $Egreso != "") {
                $this->egresoId = $Egreso->egresoId;
                $this->tipoId = $Egreso->tipoId;
                $this->descripcion = $Egreso->descripcion;
                $this->estado = $Egreso->estado;
                $this->usucreaId = $Egreso->usucreaId;
                $this->usumodifId = $Egreso->usumodifId;
                $this->centrocostoId = $Egreso->centrocostoId;;
                $this->documento = $Egreso->documento;
                $this->valor = $Egreso->valor;
                $this->impuesto = $Egreso->retraccion;
                $this->conceptoId = $Egreso->conceptoId;
                $this->productotercId = $Egreso->productotercId;
                $this->proveedortercId = $Egreso->proveedortercId;
                $this->usucajeroId = $Egreso->usucajeroId;
                $this->consecutivo = $Egreso->consecutivo;
                $this->fechaCrea = $Egreso->fechaCrea;
                $this->usuarioId = $Egreso->usuarioId;
                $this->serie = $Egreso->serie;
            } else {
                throw new Exception("No existe " . get_class($this), "78");
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
     * Obtener productotercId
     * @return mixed
     */
    public function getProductotercId()
    {
        return $this->productotercId;
    }

    /**
     * Define productotercId
     * @param mixed $productotercId
     */
    public function setProductotercId($productotercId)
    {
        $this->productotercId = $productotercId;
    }

    /**
     * * Obtener UsucajeroId
     * @return mixed
     */
    public function getUsucajeroId()
    {
        return $this->usucajeroId;
    }

    /**
     * Define UsucajeroId
     * @param mixed $usucajeroId
     */
    public function setUsucajeroId($usucajeroId)
    {
        $this->usucajeroId = $usucajeroId;
    }

    /**
     * Obtener proveedortercId
     * @return mixed
     */
    public function getProveedortercId()
    {
        return $this->proveedortercId;
    }

    /**
     * Define proveedortercId
     * @param mixed $proveedortercId
     */
    public function setProveedortercId($proveedortercId)
    {
        $this->proveedortercId = $proveedortercId;
    }


    /**
     * Obtener consecutivo
     * @return mixed
     */
    public function getConsecutivo()
    {
        return $this->consecutivo;
    }

    /**
     * Define consecutivo
     * @param mixed $consecutivo
     */
    public function setConsecutivo($consecutivo)
    {
        $this->consecutivo = $consecutivo;
    }

    /**
     * obtiene fechaCrea
     * @return mixed
     */
    public function getFechaCrea()
    {
        return $this->fechaCrea;
    }



    /**
     * Obtener el campo egresoId de un objeto
     *
     * @return String egresoId egresoId
     *
     */
    public function getEgresoId()
    {
        return $this->egresoId;
    }

    /**
     * Obtener tipoDocumento
     * @return mixed
     */
    public function getTipoDocumento()
    {
        return $this->tipoDocumento;
    }

    /**
     * Definir tipoDocumento
     * @param mixed $tipoDocumento
     */
    public function setTipoDocumento($tipoDocumento)
    {
        $this->tipoDocumento = $tipoDocumento;
    }

    /**
     * Obtener serie
     * @return mixed
     */
    public function getSerie()
    {
        return $this->serie;
    }

    /**
     * Definir serie
     * @param mixed $serie
     */
    public function setSerie($serie)
    {
        $this->serie = $serie;
    }








    /**
    * Realizar una consulta en la tabla de agresos 'Egreso'
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
    public function getEgresosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$grouping="")
    {

        $EgresoMySqlDAO = new EgresoMySqlDAO();

        $clasificadores = $EgresoMySqlDAO->queryEgresoesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$grouping);

        if ($clasificadores != null && $clasificadores != "") {

            return $clasificadores;

        } else {
            throw new Exception("No existe " . get_class($this), "78");
        }

    }


}

?>