<?php

namespace Backend\dto;

use Backend\mysql\BonoLogMySqlDAO;

/**
 * Clase 'BonoInterno'
 *
 * Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
 * para la tabla 'BonoInterno'
 *
 * Ejemplo de uso:
 * $BonoInterno = new BonoInterno();
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class BonoLog
{

    /**
     * Representación de la columna 'bonologId' de la tabla 'BonoLog'
     *
     * @var string
     */
    var $bonologId;

    /**
     * Representación de la columna 'usuarioId' de la tabla 'BonoLog'
     *
     * @var string
     */
    var $usuarioId;

    /**
     * Representación de la columna 'tipo' de la tabla 'BonoLog'
     *
     * @var string
     */
    var $tipo;

    /**
     * Representación de la columna 'valor' de la tabla 'BonoLog'
     *
     * @var string
     */
    var $valor;

    /**
     * Representación de la columna 'fechaCrea' de la tabla 'BonoLog'
     *
     * @var string
     */
    var $fechaCrea;

    /**
     * Representación de la columna 'estado' de la tabla 'BonoLog'
     *
     * @var string
     */
    var $estado;

    /**
     * Representación de la columna 'errorId' de la tabla 'BonoLog'
     *
     * @var string
     */
    var $errorId;

    /**
     * Representación de la columna 'idExterno' de la tabla 'BonoLog'
     *
     * @var string
     */
    var $idExterno;

    /**
     * Representación de la columna 'mandante' de la tabla 'BonoLog'
     *
     * @var string
     */
    var $mandante;

    /**
     * Representación de la columna 'fechaCierre' de la tabla 'BonoLog'
     *
     * @var string
     */
    var $fechaCierre;

    /**
     * Representación de la columna 'transaccionId' de la tabla 'BonoLog'
     *
     * @var string
     */
    var $transaccionId;

    /**
     * Representación de la columna 'tipoBonoId' de la tabla 'BonoLog'
     *
     * @var string
     */
    var $tipobonoId;

    /**
     * Representación de la columna 'tipoSaldoId' de la tabla 'BonoLog'
     *
     * @var string
     */
    var $tiposaldoId;

    /**
     * BonoLog constructor.
     * @param $bonologId
     */
    public function __construct($bonologId = '')
    {
        $this->bonologId = $bonologId;
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
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * @param mixed $valor
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
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
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * @param mixed $estado
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    /**
     * @return mixed
     */
    public function getErrorId()
    {
        return $this->errorId;
    }

    /**
     * @param mixed $errorId
     */
    public function setErrorId($errorId)
    {
        $this->errorId = $errorId;
    }

    /**
     * @return mixed
     */
    public function getIdExterno()
    {
        return $this->idExterno;
    }

    /**
     * @param mixed $idExterno
     */
    public function setIdExterno($idExterno)
    {
        $this->idExterno = $idExterno;
    }

    /**
     * @return mixed
     */
    public function getMandante()
    {
        return $this->mandante;
    }

    /**
     * @param mixed $mandante
     */
    public function setMandante($mandante)
    {
        $this->mandante = $mandante;
    }

    /**
     * @return mixed
     */
    public function getFechaCierre()
    {
        return $this->fechaCierre;
    }

    /**
     * @param mixed $fechaCierre
     */
    public function setFechaCierre($fechaCierre)
    {
        $this->fechaCierre = $fechaCierre;
    }

    /**
     * @return mixed
     */
    public function getTransaccionId()
    {
        return $this->transaccionId;
    }

    /**
     * @param mixed $transaccionId
     */
    public function setTransaccionId($transaccionId)
    {
        $this->transaccionId = $transaccionId;
    }

    /**
     * @return mixed
     */
    public function getTipobonoId()
    {
        return $this->tipobonoId;
    }

    /**
     * @param mixed $tipobonoId
     */
    public function setTipobonoId($tipobonoId)
    {
        $this->tipobonoId = $tipobonoId;
    }

    /**
     * @return mixed
     */
    public function getTiposaldoId()
    {
        return $this->tiposaldoId;
    }

    /**
     * @param mixed $tiposaldoId
     */
    public function setTiposaldoId($tiposaldoId)
    {
        $this->tiposaldoId = $tiposaldoId;
    }
    /**
     * Realizar una consulta en la tabla de bonos 'bono_custom'
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
     * @param String $grouping columna para agrupar
     *
     * @return Array resultado de la consulta
     * @throws Exception si los bonos no existen
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function getBonoLogsCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping)
    {

        $BonoLogMySqlDAO = new BonoLogMySqlDAO();

        $bonos = $BonoLogMySqlDAO->queryBonoLogsCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping);

        if ($bonos != null && $bonos != "") {
            return $bonos;
        } else {
            throw new Exception("No existe " . get_class($this), "01");
        }
    }
}
