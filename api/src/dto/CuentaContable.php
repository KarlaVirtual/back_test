<?php

namespace Backend\dto;

use Backend\mysql\CuentaContableMySqlDAO;
use Exception;

/**
 * Object represents table 'clasificador'
 *
 * @author: Daniel Tamayo
 * @date: 2017-09-06 18:52
 * @category No
 * @package No
 * @version 1.0
 */
class CuentaContable
{

    /**
     * Representación de la columna 'cuentacontable_id' de la tabla 'cuenta_contable'
     * @var int
     */
    var $cuentacontableId;

    /**
     * Representación de la columna 'descripcion' de la tabla 'cuenta_contable'
     * @var string
     */
    var $descripcion;

    /**
     * Representación de la columna 'estado' de la tabla 'cuenta_contable'
     * @var string
     */
    var $estado;

    /**
     * Representación de la columna 'usucrea_id' de la tabla 'cuenta_contable'
     * @var int
     */
    var $usucreaId;

    /**
     * Representación de la columna 'usumodif_id' de la tabla 'cuenta_contable'
     * @var int
     */
    var $usumodifId;

    /**
     * Representación de la columna 'referencia' de la tabla 'cuenta_contable'
     * @var string
     */
    var $referencia;


    /**
     * CuentaContable constructor.
     * @param $cuentacontableId

     * @param $codigo
     */
    public function __construct($cuentacontableId = "", $codigo = "")
    {

        if ($cuentacontableId != "") {

            $CuentaContableMySqlDAO = new CuentaContableMySqlDAO();

            $CuentaContable = $CuentaContableMySqlDAO->load($cuentacontableId);


            if ($CuentaContable != null && $CuentaContable != "") {
                $this->cuentacontableId = $CuentaContable->cuentacontableId;
                $this->descripcion = $CuentaContable->descripcion;
                $this->estado = $CuentaContable->estado;
                $this->usucreaId = $CuentaContable->usucreaId;
                $this->usumodifId = $CuentaContable->usumodifId;
                $this->referencia = $CuentaContable->referencia;
            } else {
                throw new Exception("No existe " . get_class($this), "81");
            }
        }
    }


    /**
     * @return mixed
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * @param mixed $descripcion
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
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
    public function getCuentaContableId()
    {
        return $this->cuentacontableId;
    }

    /**
     * @return mixed
     */
    public function getReferencia()
    {
        return $this->referencia;
    }

    /**
     * @param mixed $referencia
     */
    public function setReferencia($referencia)
    {
        $this->referencia = $referencia;
    }





    /**
     * Obtiene una lista personalizada de cuentas contables.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Índice de ordenación.
     * @param string $sord Orden de clasificación (ascendente o descendente).
     * @param int $start Posición inicial para la consulta.
     * @param int $limit Número máximo de registros a devolver.
     * @param array $filters Filtros a aplicar en la consulta.
     * @param bool $searchOn Indica si la búsqueda está activada.
     * 
     * @return object Lista de cuentas contables que cumplen con los criterios especificados.
     * @throws Exception Si no se encuentran cuentas contables.
     */
    public function getCuentaContablesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $CuentaContableMySqlDAO = new CuentaContableMySqlDAO();

        $cuentas = $CuentaContableMySqlDAO->queryCuentaContableesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($cuentas != null && $cuentas != "") {

            return $cuentas;
        } else {
            throw new Exception("No existe " . get_class($this), "81");
        }
    }
}
