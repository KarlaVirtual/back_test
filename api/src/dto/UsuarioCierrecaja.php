<?php 
namespace Backend\dto;

use Backend\mysql\UsuarioCierrecajaMySqlDAO;
use Exception;
/**
 * Object represents table 'clasificador'
 *
 * @author: DT
 * @date: 2017-09-06 18:52
 */
class UsuarioCierrecaja
{

/**
 * @var string Representación de la columna 'usucierrecaja_id' en la tabla 'usuarioCierre_caja'
 */
var $usucierrecaja_id;

/**
 * @var string Representación de la columna 'usuario_id' en la tabla 'usuarioCierre_caja'
 */
var $usuario_id;

/**
 * @var string Representación de la columna 'fecha_cierre' en la tabla 'usuarioCierre_caja'
 */
var $fecha_cierre;

/**
 * @var string Representación de la columna 'ingresos_propios' en la tabla 'usuarioCierre_caja'
 */
var $ingresos_propios;

/**
 * @var string Representación de la columna 'egresos_propios' en la tabla 'usuarioCierre_caja'
 */
var $egresos_propios;

/**
 * @var string Representación de la columna 'ingresos_productos' en la tabla 'usuarioCierre_caja'
 */
var $ingresos_productos;

/**
 * @var string Representación de la columna 'egresos_productos' en la tabla 'usuarioCierre_caja'
 */
var $egresos_productos;

/**
 * @var string Representación de la columna 'ingresos_otros' en la tabla 'usuarioCierre_caja'
 */
var $ingresos_otros;

/**
 * @var string Representación de la columna 'egresos_otros' en la tabla 'usuarioCierre_caja'
 */
var $egresos_otros;

/**
 * @var string Representación de la columna 'usucrea_id' en la tabla 'usuarioCierre_caja'
 */
var $usucrea_id;

/**
 * @var string Representación de la columna 'usumodif_id' en la tabla 'usuarioCierre_caja'
 */
var $usumodif_id;

/**
 * @var string Representación de la columna 'dinero_inicial' en la tabla 'usuarioCierre_caja'
 */
var $dinero_inicial;

/**
 * @var string Representación de la columna 'ingresos_tarjetacredito' en la tabla 'usuarioCierre_caja'
 */
var $ingresos_tarjetacredito;
    /**
     * UsuarioCierrecaja constructor.
     * @param $usucierrecajaId

     * @param $codigo
     */
    public function __construct($usucierrecajaId = "", $codigo = "")
    {

        if ($usucierrecajaId != "") {

            $UsuarioCierrecajaMySqlDAO = new UsuarioCierrecajaMySqlDAO();

            $UsuarioCierrecaja = $UsuarioCierrecajaMySqlDAO->load($usucierrecajaId);


            if ($UsuarioCierrecaja != null && $UsuarioCierrecaja != "") {
                $this->usucierrecajaId = $UsuarioCierrecaja->usucierrecajaId;
                $this->usuarioId = $UsuarioCierrecaja->usuarioId;
                $this->fechaCierre = $UsuarioCierrecaja->fechaCierre;
                $this->ingresosPropios = $UsuarioCierrecaja->ingresosPropios;
                $this->usucreaId = $UsuarioCierrecaja->usucreaId;
                $this->usumodifId = $UsuarioCierrecaja->usumodifId;

                $this->egresosPropios = $UsuarioCierrecaja->egresosPropios;
                $this->ingresosOtros = $UsuarioCierrecaja->ingresosOtros;
                $this->egresosOtros = $UsuarioCierrecaja->egresosOtros;
                $this->ingresosProductos = $UsuarioCierrecaja->ingresosProductos;
                $this->egresosProductos = $UsuarioCierrecaja->egresosProductos;
                $this->dineroInicial = $UsuarioCierrecaja->dineroInicial;
                $this->ingresosTarjetacredito = $UsuarioCierrecaja->ingresosTarjetacredito;


            } else {
                throw new Exception("No existe " . get_class($this), "84");
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
     * Obtiene la fecha de cierre.
     *
     * @return string
     */
    public function getFechaCierre()
    {
        return $this->fechaCierre;
    }

    /**
     * Establece la fecha de cierre.
     *
     * @param string $fechaCierre
     */
    public function setFechaCierre($fechaCierre)
    {
        $this->fechaCierre = $fechaCierre;
    }

    /**
     * Obtiene los ingresos propios.
     *
     * @return string
     */
    public function getIngresosPropios()
    {
        return $this->ingresosPropios;
    }

    /**
     * Establece los ingresos propios.
     *
     * @param string $ingresosPropios
     */
    public function setIngresosPropios($ingresosPropios)
    {
        $this->ingresosPropios = $ingresosPropios;
    }

    /**
     * Obtiene los egresos propios.
     *
     * @return string
     */
    public function getEgresosPropios()
    {
        return $this->egresosPropios;
    }

    /**
     * Establece los egresos propios.
     *
     * @param string $egresosPropios
     */
    public function setEgresosPropios($egresosPropios)
    {
        $this->egresosPropios = $egresosPropios;
    }

    /**
     * Obtiene los ingresos por productos.
     *
     * @return string
     */
    public function getIngresosProductos()
    {
        return $this->ingresosProductos;
    }

    /**
     * Establece los ingresos por productos.
     *
     * @param string $ingresosProductos
     */
    public function setIngresosProductos($ingresosProductos)
    {
        $this->ingresosProductos = $ingresosProductos;
    }

    /**
     * Obtiene los egresos por productos.
     *
     * @return string
     */
    public function getEgresosProductos()
    {
        return $this->egresosProductos;
    }

    /**
     * Establece los egresos por productos.
     *
     * @param string $egresosProductos
     */
    public function setEgresosProductos($egresosProductos)
    {
        $this->egresosProductos = $egresosProductos;
    }

    /**
     * Obtiene los ingresos otros.
     *
     * @return string
     */
    public function getIngresosOtros()
    {
        return $this->ingresosOtros;
    }

    /**
     * Establece los ingresos otros.
     *
     * @param string $ingresosOtros
     */
    public function setIngresosOtros($ingresosOtros)
    {
        $this->ingresosOtros = $ingresosOtros;
    }

    /**
     * Obtiene los egresos otros.
     *
     * @return string
     */
    public function getEgresosOtros()
    {
        return $this->egresosOtros;
    }

    /**
     * Establece los egresos otros.
     *
     * @param string $egresosOtros
     */
    public function setEgresosOtros($egresosOtros)
    {
        $this->egresosOtros = $egresosOtros;
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
     * Obtiene el dinero inicial.
     *
     * @return string
     */
    public function getDineroInicial()
    {
        return $this->dineroInicial;
    }

    /**
     * Establece el dinero inicial.
     *
     * @param string $dineroInicial
     */
    public function setDineroInicial($dineroInicial)
    {
        $this->dineroInicial = $dineroInicial;
    }

    /**
     * Obtiene el ID del cierre de caja del usuario.
     *
     * @return string
     */
    public function getUsucierrecajaId()
    {
        return $this->usucierrecajaId;
    }

    /**
     * Obtiene los ingresos por tarjeta de crédito.
     *
     * @return string
     */
    public function getIngresosTarjetacredito()
    {
        return $this->ingresosTarjetacredito;
    }

    /**
     * Establece los ingresos por tarjeta de crédito.
     *
     * @param string $ingresosTarjetacredito
     */
    public function setIngresosTarjetacredito($ingresosTarjetacredito)
    {
        $this->ingresosTarjetacredito = $ingresosTarjetacredito;
    }




    /**
     * Get Domain object by primry key
     *
     * @param
     * @return int
     */
    public function getUsuarioCierrecajasCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $UsuarioCierrecajaMySqlDAO = new UsuarioCierrecajaMySqlDAO();

        $clasificadores = $UsuarioCierrecajaMySqlDAO->queryUsuarioCierrecajaesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($clasificadores != null && $clasificadores != "") {

            return $clasificadores;

        } else {
            throw new Exception("No existe " . get_class($this), "84");
        }

    }


}

?>