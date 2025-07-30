<?php namespace Backend\dto;

use Backend\mysql\UsuarioBonoResumenDetalleMySqlDAO;
use Backend\mysql\UsuarioBonoResumenMySqlDAO;
use Exception;

/**
 * Object represents table 'producto'
 *
 * @author: DT
 * @date: 2017-09-06 18:54
 */
class UsuarioBonoResumen
{

/**
     * @var string Representación de la columna 'usubonoresumeId' en la tabla usuariobono_resumen
     */
    var $usubonoresumeId;

    /**
     * @var string Representación de la columna 'usuarioId' en la tabla usuariobono_resumen
     */
    var $usuarioId;

    /**
     * @var string Representación de la columna 'tipo' en la tabla usuariobono_resumen
     */
    var $tipo;

    /**
     * @var string Representación de la columna 'estado' en la tabla usuariobono_resumen
     */
    var $estado;

    /**
     * @var string Representación de la columna 'valor' en la tabla usuariobono_resumen
     */
    var $valor;

    /**
     * @var string Representación de la columna 'cantidad' en la tabla usuariobono_resumen
     */
    var $cantidad;

    /**
     * @var string Representación de la columna 'usucreaId' en la tabla usuariobono_resumen
     */
    var $usucreaId;

    /**
     * @var string Representación de la columna 'usumodifId' en la tabla usuariobono_resumen
     */
    var $usumodifId;


    /**
     * UsuarioBonoResumen constructor.
     * @param $usubonoresumeId
     */
    public function __construct($usubonoresumeId="")
    {
        if ($usubonoresumeId != "") {

            $UsuarioBonoResumenMySqlDAO = new UsuarioBonoResumenMySqlDAO();

            $UsuarioBonoResumen = $UsuarioBonoResumenMySqlDAO->load($usubonoresumeId);

            if ($UsuarioBonoResumen != null && $UsuarioBonoResumen != "") {
                $this->usubonoresumeId = $UsuarioBonoResumen->usubonoresumeId;
                $this->usuarioId = $UsuarioBonoResumen->usuarioId;
                $this->tipo = $UsuarioBonoResumen->tipo;
                $this->estado = $UsuarioBonoResumen->estado;
                $this->valor = $UsuarioBonoResumen->valor;
                $this->cantidad = $UsuarioBonoResumen->cantidad;
                $this->usucreaId = $UsuarioBonoResumen->usucreaId;
                $this->usumodifId = $UsuarioBonoResumen->usumodifId;
            }
            else {
                throw new Exception("No existe " . get_class($this), "91");
            }
        }

    }

/**
     * Obtiene el ID del usuario.
     *
     * @return string
     */
    public function getUsuarioId()
    {
        return $this->usuarioId;
    }

    /**
     * Establece el ID del usuario.
     *
     * @param string $usuarioId
     */
    public function setUsuarioId($usuarioId)
    {
        $this->usuarioId = $usuarioId;
    }

    /**
     * Obtiene el tipo.
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Establece el tipo.
     *
     * @param string $tipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    /**
     * Obtiene el estado.
     *
     * @return string
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Establece el estado.
     *
     * @param string $estado
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    /**
     * Obtiene el valor.
     *
     * @return string
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Establece el valor.
     *
     * @param string $valor
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    /**
     * Obtiene la cantidad.
     *
     * @return string
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Establece la cantidad.
     *
     * @param string $cantidad
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;
    }

    /**
     * Obtiene el ID del usuario que creó.
     *
     * @return string
     */
    public function getUsucreaId()
    {
        return $this->usucreaId;
    }

    /**
     * Establece el ID del usuario que creó.
     *
     * @param string $usucreaId
     */
    public function setUsucreaId($usucreaId)
    {
        $this->usucreaId = $usucreaId;
    }

    /**
     * Obtiene el ID del usuario que modificó.
     *
     * @return string
     */
    public function getUsumodifId()
    {
        return $this->usumodifId;
    }

    /**
     * Establece el ID del usuario que modificó.
     *
     * @param string $usumodifId
     */
    public function setUsumodifId($usumodifId)
    {
        $this->usumodifId = $usumodifId;
    }

    /**
     * Obtiene el ID del resumen del bono del usuario.
     *
     * @return string
     */
    public function getUsubonoresumeId()
    {
        return $this->usubonoresumeId;
    }


    /**
     * Get Domain object by primry key
     *
     * @param
     * @return int
     */
    public function getUsuarioBonoResumenCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$grouping="")
    {

        $UsuarioBonoResumenMySqlDAO = new UsuarioBonoResumenMySqlDAO();

        $Productos = $UsuarioBonoResumenMySqlDAO->queryUsuarioBonoResumensCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$grouping);

        if ($Productos != null && $Productos != "") {

            return $Productos;

        } else {
            throw new Exception("No existe " . get_class($this), "91");
        }

    }

}

?>
