<?php namespace Backend\dto;

use Backend\mysql\UsuarioDeporteResumenDetalleMySqlDAO;
use Backend\mysql\UsuarioDeporteResumenMySqlDAO;
use Exception;

/**
 * Object represents table 'producto'
 *
 * @author: DT
 * @date: 2017-09-06 18:54
 */
class UsuarioDeporteResumen
{

/**
     * @var string Representación de la columna 'usudepresumeId' en la tabla 'usuariocasino_resumen'
     */
    var $usudepresumeId;

    /**
     * @var string Representación de la columna 'usuarioId' en la tabla 'usuariocasino_resumen'
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
     * @var string Representación de la columna 'usucreaId' en la tabla 'usuariocasino_resumen'
     */
    var $usucreaId;

    /**
     * @var string Representación de la columna 'usumodifId' en la tabla 'usuariocasino_resumen'
     */
    var $usumodifId;


    /**
     * UsuarioDeporteResumen constructor.
     * @param $usudepresumeId
     */
    public function __construct($usudepresumeId="")
    {
        if ($usudepresumeId != "") {

            $UsuarioDeporteResumenMySqlDAO = new UsuarioDeporteResumenMySqlDAO();

            $UsuarioDeporteResumen = $UsuarioDeporteResumenMySqlDAO->load($usudepresumeId);

            if ($UsuarioDeporteResumen != null && $UsuarioDeporteResumen != "") {
                $this->usudepresumeId = $UsuarioDeporteResumen->usudepresumeId;
                $this->usuarioId = $UsuarioDeporteResumen->usuarioId;
                $this->tipo = $UsuarioDeporteResumen->tipo;
                $this->estado = $UsuarioDeporteResumen->estado;
                $this->valor = $UsuarioDeporteResumen->valor;
                $this->cantidad = $UsuarioDeporteResumen->cantidad;
                $this->usucreaId = $UsuarioDeporteResumen->usucreaId;
                $this->usumodifId = $UsuarioDeporteResumen->usumodifId;
            }
            else {
                throw new Exception("No existe " . get_class($this), "92");
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
     * Obtiene el ID del resumen del bono del usuario.
     *
     * @return string
     */
    public function getUsubonoresumeId()
    {
        return $this->usudepresumeId;
    }




    /**
     * Get Domain object by primry key
     *
     * @param
     * @return int
     */
    public function getUsuarioDeporteResumenCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$grouping="",$select2="",$grouping2="")
    {

        $UsuarioDeporteResumenMySqlDAO = new UsuarioDeporteResumenMySqlDAO();

        $Productos = $UsuarioDeporteResumenMySqlDAO->queryUsuarioDeporteResumensCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$grouping,$select2,$grouping2);

        if ($Productos != null && $Productos != "") {

            return $Productos;

        } else {
            throw new Exception("No existe " . get_class($this), "92");
        }

    }

}

?>
