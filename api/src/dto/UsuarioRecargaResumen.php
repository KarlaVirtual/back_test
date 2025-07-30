<?php namespace Backend\dto;

use Backend\mysql\UsuarioRecargaResumenDetalleMySqlDAO;
use Backend\mysql\UsuarioRecargaResumenMySqlDAO;
use Exception;

/**
 * Object represents table 'producto'
 *
 * @author: Daniel Tamayo
 * @date: 2017-09-06 18:54
 * @package No
 * @category No
 * @version    1.0
 */
class UsuarioRecargaResumen
{

/**
     * @var string Representación de la columna 'usurecresume_id' en la tabla 'usuario_recarga_resumen'
     */
    var $usurecresumeId;

    /**
     * @var string Representación de la columna 'usuario_id' en la tabla 'usuario_recarga_resumen'
     */
    var $usuarioId;

    /**
     * @var string Representación de la columna 'mediopago_id' en la tabla 'usuario_recarga_resumen'
     */
    var $mediopagoId;

    /**
     * @var string Representación de la columna 'estado' en la tabla 'usuario_recarga_resumen'
     */
    var $estado;

    /**
     * @var string Representación de la columna 'valor' en la tabla 'usuario_recarga_resumen'
     */
    var $valor;

    /**
     * @var string Representación de la columna 'cantidad' en la tabla 'usuario_recarga_resumen'
     */
    var $cantidad;

    /**
     * @var string Representación de la columna 'usucrea_id' en la tabla 'usuario_recarga_resumen'
     */
    var $usucreaId;

    /**
     * @var string Representación de la columna 'usumodif_id' en la tabla 'usuario_recarga_resumen'
     */
    var $usumodifId;


    /**
     * UsuarioRecargaResumen constructor.
     * @param $usurecresumeId
     */
    public function __construct($usurecresumeId = "")
    {
        if ($usurecresumeId != "") {

            $UsuarioRecargaResumenMySqlDAO = new UsuarioRecargaResumenMySqlDAO();

            $UsuarioRecargaResumen = $UsuarioRecargaResumenMySqlDAO->load($usurecresumeId);

            if ($UsuarioRecargaResumen != null && $UsuarioRecargaResumen != "") {
                $this->usurecresumeId = $UsuarioRecargaResumen->usurecresumeId;
                $this->usuarioId = $UsuarioRecargaResumen->usuarioId;
                $this->mediopagoId = $UsuarioRecargaResumen->mediopagoId;
                $this->estado = $UsuarioRecargaResumen->estado;
                $this->valor = $UsuarioRecargaResumen->valor;
                $this->cantidad = $UsuarioRecargaResumen->cantidad;
                $this->usucreaId = $UsuarioRecargaResumen->usucreaId;
                $this->usumodifId = $UsuarioRecargaResumen->usumodifId;
            } else {
                throw new Exception("No existe " . get_class($this), "89");
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
     * Obtiene el ID del medio de pago.
     *
     * @return string
     */
    public function getMediopagoId()
    {
        return $this->mediopagoId;
    }

    /**
     * Establece el ID del medio de pago.
     *
     * @param string $mediopagoId
     */
    public function setMediopagoId($mediopagoId)
    {
        $this->mediopagoId = $mediopagoId;
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
     * Obtiene el ID del resumen de recarga del usuario.
     *
     * @return string
     */
    public function getUsurecresumeId()
    {
        return $this->usurecresumeId;
    }


    /**
     * Get Domain object by primry key
     *
     * @param
     * @return int
     */
    public function getUsuarioRecargaResumenCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping = "",$innProducto=false,$innConcesionario=false)
    {

        $UsuarioRecargaResumenMySqlDAO = new UsuarioRecargaResumenMySqlDAO();

        $Productos = $UsuarioRecargaResumenMySqlDAO->queryUsuarioRecargaResumensCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping,$innProducto,$innConcesionario);

        if ($Productos != null && $Productos != "") {

            return $Productos;

        } else {
            throw new Exception("No existe " . get_class($this), "89");
        }

    }


}

?>
