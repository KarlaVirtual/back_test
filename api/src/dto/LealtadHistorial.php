<?php

namespace Backend\dto;

use Backend\mysql\LealtadHistorialMySqlDAO;
use Exception;

/**
 * Object represents table 'clasificador'
 *
 * @author: DT
 * @date: 2017-09-06 18:52
 */
class LealtadHistorial
{

    /**
     * Representación de la columna lealtadHistorialId en la tabla 'LealtadHistorial'
     * @var int
     */
    var $lealtadHistorialId;

    /**
     * Representación de la columna usuarioId en la tabla 'LealtadHistorial'
     * @var int
     */
    var $usuarioId;

    /**
     * Representación de la columna descripcion en la tabla 'LealtadHistorial'
     * @var string
     */
    var $descripcion;

    /**
     * Representación de la columna movimiento en la tabla 'LealtadHistorial'
     * @var string
     */
    var $movimiento;

    /**
     * Representación de la columna usucreaId en la tabla 'LealtadHistorial'
     * @var int
     */
    var $usucreaId;

    /**
     * Representación de la columna usumodifId en la tabla 'LealtadHistorial'
     * @var int
     */
    var $usumodifId;

    /**
     * Representación de la columna tipo en la tabla 'LealtadHistorial'
     * @var string
     */
    var $tipo;

    /**
     * Representación de la columna valor en la tabla 'LealtadHistorial'
     * @var float
     */
    var $valor;

    /**
     * Representación de la columna externoId en la tabla 'LealtadHistorial'
     * @var int
     */
    var $externoId;

    /**
     * Representación de la columna fechaExp en la tabla 'LealtadHistorial'
     * @var string
     */
    var $fechaExp;

    /**
     * Representación de la columna fechaExptime en la tabla 'LealtadHistorial'
     * @var string
     */
    var $fechaExptime;

    /**
     * Representación de la columna paisId en la tabla 'LealtadHistorial'
     * @var int
     */
    var $paisId;

    /**
     * Representación de la columna mandante en la tabla 'LealtadHistorial'
     * @var string
     */
    var $mandante;



    /**
     * LealtadHistorial constructor.
     * @param $lealtadHistorialId

     * @param $codigo
     */
    public function __construct($lealtadHistorialId = "", $codigo = "")
    {

        if ($lealtadHistorialId != "") {

            $LealtadHistorialMySqlDAO = new LealtadHistorialMySqlDAO();

            $LealtadHistorial = $LealtadHistorialMySqlDAO->load($lealtadHistorialId);


            if ($LealtadHistorial != null && $LealtadHistorial != "") {
                $this->lealtadHistorialId = $LealtadHistorial->lealtadHistorialId;
                $this->usuarioId = $LealtadHistorial->usuarioId;
                $this->descripcion = $LealtadHistorial->descripcion;
                $this->movimiento = $LealtadHistorial->movimiento;
                $this->usucreaId = $LealtadHistorial->usucreaId;
                $this->usumodifId = $LealtadHistorial->usumodifId;
                $this->tipo = $LealtadHistorial->tipo;
                $this->valor = $LealtadHistorial->valor;
                $this->externoId = $LealtadHistorial->externoId;
                $this->fechaExp = $LealtadHistorial->fechaExp;
                $this->fechaExptime = $LealtadHistorial->fechaExptime;
                $this->mandante = $LealtadHistorial->mandante;
                $this->paisId = $LealtadHistorial->paisId;
            } else {
                throw new Exception("No existe " . get_class($this), "80");
            }
        } elseif ($codigo != "") {
            $LealtadHistorialMySqlDAO = new LealtadHistorialMySqlDAO();

            $LealtadHistorial = $LealtadHistorialMySqlDAO->queryByAbreviado($codigo);

            $LealtadHistorial = $LealtadHistorial[0];

            if ($LealtadHistorial != null && $LealtadHistorial != "") {
                $this->lealtadHistorialId = $LealtadHistorial->lealtadHistorialId;
                $this->usuarioId = $LealtadHistorial->usuarioId;
                $this->descripcion = $LealtadHistorial->descripcion;
                $this->movimiento = $LealtadHistorial->movimiento;
                $this->usucreaId = $LealtadHistorial->usucreaId;
                $this->usumodifId = $LealtadHistorial->usumodifId;
                $this->tipo = $LealtadHistorial->tipo;
                $this->valor = $LealtadHistorial->valor;
                $this->externoId = $LealtadHistorial->externoId;
                $this->fechaExp = $LealtadHistorial->fechaExp;
                $this->fechaExptime = $LealtadHistorial->fechaExptime;
                $this->mandante = $LealtadHistorial->mandante;
                $this->paisId = $LealtadHistorial->paisId;
            } else {
                throw new Exception("No existe " . get_class($this), "80");
            }
        }
    }

    /**
     * Obtiene el valor de usuario_id
     * @return mixed
     */
    public function getUsuarioId()
    {
        return $this->usuarioId;
    }

    /**
     * Establece el valor de usuario_id
     * @param mixed $usuarioId
     * 
     */
    public function setUsuarioId($usuarioId)
    {
        $this->usuarioId = $usuarioId;
    }

    /**
     * Obtiene el valor de lealtad_id
     * @return mixed
     */
    public function getLealtadId()
    {
        return $this->usuarioId;
    }

    /**
     * Establece el valor de lealtad_id
     * @param mixed $usuarioId
     */
    public function setLealtadId($usuarioId)
    {
        $this->usuarioId = $usuarioId;
    }

    /**
     * Obtiene el valor de descripcion
     * @return mixed
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Establece el valor de descripcion
     * @param mixed $descripcion
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    /**
     * Obtiene el valor de movimiento
     * @return mixed
     */
    public function getMovimiento()
    {
        return $this->movimiento;
    }

    /**
     * Establece el valor de movimiento
     * @param mixed $movimiento
     */
    public function setMovimiento($movimiento)
    {
        $this->movimiento = $movimiento;
    }

    /**
     * Obtiene el valor de usucrea_id
     * @return mixed
     */
    public function getUsucreaId()
    {
        return $this->usucreaId;
    }

    /**
     * Establece el valor de usucrea_id
     * @param mixed $usucreaId
     */
    public function setUsucreaId($usucreaId)
    {
        $this->usucreaId = $usucreaId;
    }

    /**
     * Obtiene el valor de usumodif_id
     * @return mixed
     */
    public function getUsumodifId()
    {
        return $this->usumodifId;
    }

    /**
     * Establece el valor de usumodif_id
     * @param mixed $usumodifId
     */
    public function setUsumodifId($usumodifId)
    {
        $this->usumodifId = $usumodifId;
    }

    /**
     * Obtiene el valor de tipo
     * @return mixed
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Establece el valor de tipo
     * @param mixed $tipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    /**
     * Obtiene el valor de valor
     * @return mixed
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Establece el valor de valor
     * @param mixed $valor
     */
    public function setValor($valor)
    {
        //$valor = (int)$valor;
        if ($valor == 0) {
            throw new Exception("No se puede crear con valor 0 ", "80");
        }

        $this->valor = $valor;
    }

    /**
     * Obtiene el valor de externo_id
     * @return mixed
     */
    public function getExternoId()
    {
        return $this->externoId;
    }

    /**
     * Establece el valor de externo_id
     * @param mixed $externoId
     */
    public function setExternoId($externoId)
    {
        $this->externoId = $externoId;
    }

    /**
     * Obtiene el valor de fecha_exp
     * @return mixed
     */
    public function getFechaExp()
    {
        return $this->fechaExp;
    }

    /**
     * Establece el valor de fecha_exp
     * @param mixed $fechaExp
     */
    public function setFechaExp($fechaExp)
    {
        $this->fechaExp = $fechaExp;
    }

    /**
     * Obtiene el valor de fecha_exptime
     * @return mixed
     */
    public function getFechaExptime()
    {
        return $this->fechaExptime;
    }

    /**
     * Establece el valor de fecha_exptime
     * @param mixed $fechaExptime
     */
    public function setFechaExptime($fechaExptime)
    {
        $this->fechaExptime = $fechaExptime;
    }

    /**
     * Obtiene el valor de pais_id
     * @return mixed
     */
    public function getPaisId()
    {
        return $this->paisId;
    }

    /**
     * Establece el valor de pais_id
     * @param mixed $paisId
     */
    public function setPaisId($paisId)
    {
        $this->paisId = $paisId;
    }

    /**
     * Obtiene el valor de mandante
     * @return mixed
     */
    public function getMandante()
    {
        return $this->mandante;
    }

    /**
     * Establece el valor de mandante
     * @param mixed $mandante
     */
    public function setMandante($mandante)
    {
        $this->mandante = $mandante;
    }



    /**
     * Obtiene el valor de lealtadHistorialId
     * @return mixed
     */
    public function getUsuHistorialId()
    {
        return $this->lealtadHistorialId;
    }



    /**
     * Obtiene un historial de lealtad personalizado basado en los parámetros proporcionados.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Índice de ordenación.
     * @param string $sord Orden de clasificación (ASC o DESC).
     * @param int $start Índice de inicio para la paginación.
     * @param int $limit Límite de registros a devolver.
     * @param array $filters Filtros a aplicar en la consulta.
     * @param bool $searchOn Indica si la búsqueda está activada.
     * @param array $grouping Agrupaciones a aplicar en la consulta.
     * @param array $joins (Opcional) Joins adicionales a incluir en la consulta.
     * @param bool $groupingCount (Opcional) Indica si se debe contar el número de agrupaciones.
     * 
     * @return array|null Resultados de la consulta personalizada.
     * @throws Exception Si no se encuentran resultados.
     */
    public function getLealtadHistorialCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping, $joins = [], $groupingCount = false)
    {

        $LealtadHistorialMySqlDAO = new LealtadHistorialMySqlDAO();

        $clasificadores = $LealtadHistorialMySqlDAO->queryLealtadHistorialeCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping, $joins, $groupingCount);

        if ($clasificadores != null && $clasificadores != "") {

            return $clasificadores;
        } else {
            throw new Exception("No existe " . get_class($this), "80");
        }
    }
}
