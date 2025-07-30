<?php namespace Backend\dto;

use Backend\mysql\UsucasinoDetalleResumenMySqlDAO;
use Exception;

/**
 * Object represents table 'producto'
 *
 * @author: Daniel Tamayo
 * @date: 2017-09-06 18:54
 * @category No
 * @package No
 * @version     1.0
 */
class UsucasinoDetalleResumen
{
/**
     * @var string Representación de la columna 'usucasdetresume_id' en la tabla 'usucasino_detalle_resumen'
     */
    var $usucasdetresumeId;

    /**
     * @var string Representación de la columna 'usuario_id' en la tabla 'usucasino_detalle_resumen'
     */
    var $usuarioId;

    /**
     * @var string Representación de la columna 'tipo' en la tabla 'usucasino_detalle_resumen'
     */
    var $tipo;

    /**
     * @var string Representación de la columna 'estado' en la tabla 'usucasino_detalle_resumen'
     */
    var $estado;

    /**
     * @var string Representación de la columna 'valor' en la tabla 'usucasino_detalle_resumen'
     */
    var $valor;

    /**
     * @var string Representación de la columna 'cantidad' en la tabla 'usucasino_detalle_resumen'
     */
    var $cantidad;

    /**
     * @var string Representación de la columna 'usucrea_id' en la tabla 'usucasino_detalle_resumen'
     */
    var $usucreaId;

    /**
     * @var string Representación de la columna 'usumodif_id' en la tabla 'usucasino_detalle_resumen'
     */
    var $usumodifId;

    /**
     * @var string Representación de la columna 'valor_premios' en la tabla 'usucasino_detalle_resumen'
     */
    var $valorPremios;

    /**
     * @var string Representación de la columna 'producto_id' en la tabla 'usucasino_detalle_resumen'
     */
    var $productoId;

    /**
     * UsucasinoDetalleResumen constructor.
     * @param $usucasdetresumeId
     */
    public function __construct($usucasdetresumeId="")
    {
        if ($usucasdetresumeId != "") {

            $UsucasinoDetalleResumenMySqlDAO = new UsucasinoDetalleResumenMySqlDAO();

            $UsucasinoDetalleResumen = $UsucasinoDetalleResumenMySqlDAO->load($usucasdetresumeId);

            if ($UsucasinoDetalleResumen != null && $UsucasinoDetalleResumen != "") {
                $this->usucasdetresumeId = $UsucasinoDetalleResumen->usucasdetresumeId;
                $this->usuarioId = $UsucasinoDetalleResumen->usuarioId;
                $this->tipo = $UsucasinoDetalleResumen->tipo;
                $this->estado = $UsucasinoDetalleResumen->estado;
                $this->valor = $UsucasinoDetalleResumen->valor;
                $this->cantidad = $UsucasinoDetalleResumen->cantidad;
                $this->usucreaId = $UsucasinoDetalleResumen->usucreaId;
                $this->usumodifId = $UsucasinoDetalleResumen->usumodifId;
                $this->valorPremios = $UsucasinoDetalleResumen->valorPremios;
                $this->productoId = $UsucasinoDetalleResumen->productoId;
            }
            else {
                throw new Exception("No existe " . get_class($this), "93");
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
     * Obtiene el ID del producto.
     *
     * @return string
     */
    public function getProductoId()
    {
        return $this->productoId;
    }

    /**
     * Establece el ID del producto.
     *
     * @param string $productoId
     */
    public function setProductoId($productoId)
    {
        $this->productoId = $productoId;
    }

    /**
     * Obtiene el ID del resumen del detalle del casino.
     *
     * @return string
     */
    public function getUsucasdetresumeId()
    {
        return $this->usucasdetresumeId;
    }





    /**
     * Get Domain object by primry key
     *
     * @param
     * @return int
     */
    public function getUsucasinoDetalleResumenCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$grouping="")
    {

        $UsucasinoDetalleResumenMySqlDAO = new UsucasinoDetalleResumenMySqlDAO();

        $Productos = $UsucasinoDetalleResumenMySqlDAO->queryUsucasinoDetalleResumensCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$grouping);

        if ($Productos != null && $Productos != "") {

            return $Productos;

        } else {
            throw new Exception("No existe " . get_class($this), "93");
        }

    }

}

?>
