<?php namespace Backend\dto;

use Backend\mysql\UsuarioRetiroResumenDetalleMySqlDAO;
use Backend\mysql\UsuarioRetiroResumenMySqlDAO;
use Exception;

/**
 * Object represents table 'producto'
 *
 * @author: Daniel Tamayo
 * @date: 2017-09-06 18:54
 * @package No
 * @category No
 * @version 1.0
 */
class UsuarioRetiroResumen
{

/**
     * @var string Representación de la columna 'usuretresume_id' en la tabla 'usuario_retiro_resumen'
     */
    var $usuretresumeId;

    /**
     * @var string Representación de la columna 'usuario_id' en la tabla 'usuario_retiro_resumen'
     */
    var $usuarioId;

    /**
     * @var string Representación de la columna 'producto_id' en la tabla 'usuario_retiro_resumen'
     */
    var $productoId;

    /**
     * @var string Representación de la columna 'estado' en la tabla 'usuario_retiro_resumen'
     */
    var $estado;

    /**
     * @var string Representación de la columna 'valor' en la tabla 'usuario_retiro_resumen'
     */
    var $valor;

    /**
     * @var string Representación de la columna 'cantidad' en la tabla 'usuario_retiro_resumen'
     */
    var $cantidad;

    /**
     * @var string Representación de la columna 'usucrea_id' en la tabla 'usuario_retiro_resumen'
     */
    var $usucreaId;

    /**
     * @var string Representación de la columna 'usumodif_id' en la tabla 'usuario_retiro_resumen'
     */
    var $usumodifId;


    /**
     * UsuarioRetiroResumen constructor.
     * @param $usuretresumeId
     */
    public function __construct($usuretresumeId="")
    {
        if ($usuretresumeId != "") {

            $UsuarioRetiroResumenMySqlDAO = new UsuarioRetiroResumenMySqlDAO();

            $UsuarioRetiroResumen = $UsuarioRetiroResumenMySqlDAO->load($usuretresumeId);

            if ($UsuarioRetiroResumen != null && $UsuarioRetiroResumen != "") {
                $this->usuretresumeId = $UsuarioRetiroResumen->usuretresumeId;
                $this->usuarioId = $UsuarioRetiroResumen->usuarioId;
                $this->productoId = $UsuarioRetiroResumen->productoId;
                $this->estado = $UsuarioRetiroResumen->estado;
                $this->valor = $UsuarioRetiroResumen->valor;
                $this->cantidad = $UsuarioRetiroResumen->cantidad;
                $this->usucreaId = $UsuarioRetiroResumen->usucreaId;
                $this->usumodifId = $UsuarioRetiroResumen->usumodifId;
            }
            else {
                throw new Exception("No existe " . get_class($this), "90");
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
     * Obtiene el ID del usuario creador.
     *
     * @return string
     */
    public function getUsucreaId()
    {
        return $this->usucreaId;
    }

    /**
     * Establece el ID del usuario creador.
     *
     * @param string $usucreaId
     */
    public function setUsucreaId($usucreaId)
    {
        $this->usucreaId = $usucreaId;
    }

    /**
     * Obtiene el ID del usuario modificador.
     *
     * @return string
     */
    public function getUsumodifId()
    {
        return $this->usumodifId;
    }

    /**
     * Establece el ID del usuario modificador.
     *
     * @param string $usumodifId
     */
    public function setUsumodifId($usumodifId)
    {
        $this->usumodifId = $usumodifId;
    }

    /**
     * Obtiene el ID del resumen de retiro del usuario.
     *
     * @return string
     */
    public function getUsuretresumeId()
    {
        return $this->usuretresumeId;
    }




    /**
     * Get Domain object by primry key
     *
     * @param
     * @return int
     */
    public function getUsuarioRetiroResumenCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$grouping="",$innProducto=false,$innConcesionario=false)
    {

        $UsuarioRetiroResumenMySqlDAO = new UsuarioRetiroResumenMySqlDAO();

        $Productos = $UsuarioRetiroResumenMySqlDAO->queryUsuarioRetiroResumensCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$grouping,$innProducto,$innConcesionario);

        if ($Productos != null && $Productos != "") {

            return $Productos;

        } else {
            throw new Exception("No existe " . get_class($this), "90");
        }

    }

}

?>
