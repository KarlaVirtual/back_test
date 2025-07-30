<?php 
namespace Backend\dto;

use Backend\mysql\UsuarioCasinoResumenDetalleMySqlDAO;
use Backend\mysql\UsuarioCasinoResumenMySqlDAO;
use Exception;

/**
 * Object represents table 'producto'
 *
 * @author: DT
 * @date: 2017-09-06 18:54
 */
class UsuarioCasinoResumen
{

/**
     * @var string Representación de la columna 'usucasresume_id' en la tabla 'usuariocasino_resumen'
     */
    var $usucasresumeId;

    /**
     * @var string Representación de la columna 'usuario_id' en la tabla 'usuariocasino_resumen'
     */
    var $usuarioId;

    /**
     * @var string Representación de la columna 'tipo' en la tabla 'usuariocasino_resumen'
     */
    var $tipo;

    /**
     * @var string Representación de la columna 'estado' en la tabla 'usuariocasino_resumen'
     */
    var $estado;

    /**
     * @var string Representación de la columna 'valor' en la tabla 'usuariocasino_resumen'
     */
    var $valor;

    /**
     * @var string Representación de la columna 'cantidad' en la tabla 'usuariocasino_resumen'
     */
    var $cantidad;

    /**
     * @var string Representación de la columna 'usucrea_id' en la tabla 'usuariocasino_resumen'
     */
    var $usucreaId;

    /**
     * @var string Representación de la columna 'usumodif_id' en la tabla 'usuariocasino_resumen'
     */
    var $usumodifId;

    /**
     * @var string Representación de la columna 'valor_premios' en la tabla 'usuariocasino_resumen'
     */
    var $valorPremios;

    /**
     * UsuarioCasinoResumen constructor.
     * @param $usucasresumeId
     */
    public function __construct($usucasresumeId="")
    {
        if ($usucasresumeId != "") {

            $UsuarioCasinoResumenMySqlDAO = new UsuarioCasinoResumenMySqlDAO();

            $UsuarioCasinoResumen = $UsuarioCasinoResumenMySqlDAO->load($usucasresumeId);

            if ($UsuarioCasinoResumen != null && $UsuarioCasinoResumen != "") {
                $this->usucasresumeId = $UsuarioCasinoResumen->usucasresumeId;
                $this->usuarioId = $UsuarioCasinoResumen->usuarioId;
                $this->tipo = $UsuarioCasinoResumen->tipo;
                $this->estado = $UsuarioCasinoResumen->estado;
                $this->valor = $UsuarioCasinoResumen->valor;
                $this->cantidad = $UsuarioCasinoResumen->cantidad;
                $this->usucreaId = $UsuarioCasinoResumen->usucreaId;
                $this->usumodifId = $UsuarioCasinoResumen->usumodifId;
                $this->valorPremios = $UsuarioCasinoResumen->valorPremios;
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
     * Obtiene el ID del creador del usuario.
     *
     * @return string
     */
    public function getUsucreaId()
    {
        return $this->usucreaId;
    }

    /**
     * Establece el ID del creador del usuario.
     *
     * @param string $usucreaId
     */
    public function setUsucreaId($usucreaId)
    {
        $this->usucreaId = $usucreaId;
    }

    /**
     * Obtiene el ID del modificador del usuario.
     *
     * @return string
     */
    public function getUsumodifId()
    {
        return $this->usumodifId;
    }

    /**
     * Establece el ID del modificador del usuario.
     *
     * @param string $usumodifId
     */
    public function setUsumodifId($usumodifId)
    {
        $this->usumodifId = $usumodifId;
    }

    /**
     * Obtiene el valor de los premios.
     *
     * @return string
     */
    public function getValorPremios()
    {
        return $this->valorPremios;
    }

    /**
     * Establece el valor de los premios.
     *
     * @param string $valorPremios
     */
    public function setValorPremios($valorPremios)
    {
        $this->valorPremios = $valorPremios;
    }

    /**
     * Obtiene el ID del resumen del usuario en el casino.
     *
     * @return string
     */
    public function getUsucasresumeId()
    {
        return $this->usucasresumeId;
    }




    /**
     * Get Domain object by primry key
     *
     * @param
     * @return int
     */
    public function getUsuarioCasinoResumenCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$grouping="")
    {

        $UsuarioCasinoResumenMySqlDAO = new UsuarioCasinoResumenMySqlDAO();

        $Productos = $UsuarioCasinoResumenMySqlDAO->queryUsuarioCasinoResumensCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$grouping);

        if ($Productos != null && $Productos != "") {

            return $Productos;

        } else {
            throw new Exception("No existe " . get_class($this), "91");
        }

    }

}

?>
