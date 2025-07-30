<?php namespace Backend\dto;

use Backend\mysql\BodegaInformeGerencialMySqlDAO;
use Exception;

/**
 * Object represents table 'producto'
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @date: 2017-09-06 18:54
 * @category    No
 * @package     No
 * @version     1.0
 */
class BodegaInformeGerencial
{
    /** Representa la columna 'usucreresumeid' de la tabla 'BodegaInformeGerencial'*/
    var $usurecresumeId;

    /** Representa la columna 'paisid' de la tabla 'BodegaInformeGerencial'*/
    var $paisId;

    /** Representa la columna 'mandante' de la tabla 'BodegaInformeGerencial'*/
    var $mandante;

    /** Representa la columna 'fecha' de la tabla 'BodegaInformeGerencial'*/
    var $fecha;

    /** Representa la columna 'saldoapuestas' de la tabla 'BodegaInformeGerencial'*/
    var $saldoApuestas;

    /** Representa la columna 'cantidad' de la tabla 'BodegaInformeGerencial'*/
    var $cantidad;

    /** Representa la columna 'primerosDepositos' de la tabla 'BodegaInformeGerencial' */
    var $primerosDepositos;

    /** Representa la columna 'usuariosRegistrados' de la tabla 'BodegaInformeGerencial' */
    var $usuariosRegistrados;

    /** Representa la columna 'saldoPremios' de la tabla 'BodegaInformeGerencial' */
    var $saldoPremios;

    /** Representa la columna 'saldoPremiosPendientes' de la tabla 'BodegaInformeGerencial' */
    var $saldoPremiosPendientes;

    /** Representa la columna 'saldoBono' de la tabla 'BodegaInformeGerencial' */
    var $saldoBono;

    /** Representa la columna 'tipoUsuario' de la tabla 'BodegaInformeGerencial' */
    var $tipoUsuario;

    /** Representa la columna 'tipoFecha' de la tabla 'BodegaInformeGerencial' */
    var $tipoFecha;

    /** Representa la columna 'pprimerosDepositos' de la tabla 'BodegaInformeGerencial' */
    var $pprimerosDepositos;

    /**
     * BodegaInformeGerencial constructor.
     * @param $usurecresumeId
     */
    public function __construct($usurecresumeId = "")
    {
        if ($usurecresumeId != "") {

            $BodegaInformeGerencialMySqlDAO = new BodegaInformeGerencialMySqlDAO();

            $BodegaInformeGerencial = $BodegaInformeGerencialMySqlDAO->load($usurecresumeId);

            if ($BodegaInformeGerencial != null && $BodegaInformeGerencial != "") {
                $this->usurecresumeId = $BodegaInformeGerencial->usurecresumeId;
                $this->paisId = $BodegaInformeGerencial->paisId;
                $this->mandante = $BodegaInformeGerencial->mandante;
                $this->fecha = $BodegaInformeGerencial->fecha;
                $this->saldoApuestas = $BodegaInformeGerencial->saldoApuestas;
                $this->cantidad = $BodegaInformeGerencial->cantidad;
                $this->primerosDepositos = $BodegaInformeGerencial->primerosDepositos;
                $this->usuariosRegistrados = $BodegaInformeGerencial->usuariosRegistrados;



                $this->saldoPremios = $BodegaInformeGerencial->saldoPremios;
                $this->saldoPremiosPendientes = $BodegaInformeGerencial->saldoPremiosPendientes;
                $this->saldoBono = $BodegaInformeGerencial->saldoBono;
                $this->tipoUsuario = $BodegaInformeGerencial->tipoUsuario;
                $this->tipoFecha = $BodegaInformeGerencial->tipoFecha;

            } else {
                throw new Exception("No existe " . get_class($this), "104");
            }
        }

    }

    /**
     * @return mixed
     */
    public function getUsuarioId()
    {
        return $this->paisId;
    }

    /**
     * @param mixed $paisId
     */
    public function setUsuarioId($paisId)
    {
        $this->paisId = $paisId;
    }

    /**
     * @return mixed
     */
    public function getMediopagoId()
    {
        return $this->mandante;
    }

    /**
     * @param mixed $mandante
     */
    public function setMediopagoId($mandante)
    {
        $this->mandante = $mandante;
    }

    /**
     * @return mixed
     */
    public function getEstado()
    {
        return $this->fecha;
    }

    /**
     * @param mixed $fecha
     */
    public function setEstado($fecha)
    {
        $this->fecha = $fecha;
    }

    /**
     * @return mixed
     */
    public function getValor()
    {
        return $this->saldoApuestas;
    }

    /**
     * @param mixed $saldoApuestas
     */
    public function setValor($saldoApuestas)
    {
        $this->saldoApuestas = $saldoApuestas;
    }

    /**
     * @return mixed
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * @param mixed $cantidad
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;
    }

    /**
     * @return mixed
     */
    public function getUsucreaId()
    {
        return $this->primerosDepositos;
    }

    /**
     * @param mixed $primerosDepositos
     */
    public function setUsucreaId($primerosDepositos)
    {
        $this->primerosDepositos = $primerosDepositos;
    }

    /**
     * @return mixed
     */
    public function getUsumodifId()
    {
        return $this->usuariosRegistrados;
    }

    /**
     * @param mixed $usuariosRegistrados
     */
    public function setUsumodifId($usuariosRegistrados)
    {
        $this->usuariosRegistrados = $usuariosRegistrados;
    }

    /**
     * @return mixed
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
    public function getBodegaInformeGerencialCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping = "",$innProducto=false,$innConcesionario=false)
    {

        $BodegaInformeGerencialMySqlDAO = new BodegaInformeGerencialMySqlDAO();

        $Productos = $BodegaInformeGerencialMySqlDAO->queryBodegaInformeGerencialsCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping,$innProducto,$innConcesionario);

        if ($Productos != null && $Productos != "") {

            return $Productos;

        } else {
            throw new Exception("No existe " . get_class($this), "104");
        }

    }



/**
     * Obtiene un informe gerencial personalizado de la bodega.
     *
     * @param string $ToDateLocal Fecha de inicio.
     * @param string $FromDateLocal Fecha de fin.
     * @param string $Wallet Cartera.
     * @param string $TypeBet Tipo de apuesta.
     * @param string $TypeUser Tipo de usuario.
     * @param string $country País.
     * @param string $Partner Socio.
     * @param string $order Orden.
     * @param int $start Inicio.
     * @param int $limit Límite.
     * @return array Productos obtenidos.
     * @throws Exception Si no existe el informe gerencial.
     */
    public function getBodegaInformeGerencialCustom2($ToDateLocal,$FromDateLocal,$Wallet,$TypeBet,$TypeUser,$country,$Partner,$order, $start, $limit)
    {

        $BodegaInformeGerencialMySqlDAO = new BodegaInformeGerencialMySqlDAO();

        $Productos = $BodegaInformeGerencialMySqlDAO->queryBodegaInformeGerencialsCustom2($ToDateLocal,$FromDateLocal,$Wallet,$TypeBet,$TypeUser,$country,$Partner,$order, $start, $limit);


        if ($Productos != null && $Productos != "") {

            return $Productos;

        } else {
            throw new Exception("No existe " . get_class($this), "104");
        }

    }

}

?>
