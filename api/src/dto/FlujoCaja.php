<?php

namespace Backend\dto;

use Backend\mysql\FlujoCajaMySqlDAO;

/** 
 * Clase 'FlujoCaja'
 * 
 * Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
 * para la tabla 'FlujoCaja'
 * 
 * Ejemplo de uso: 
 * $FlujoCaja = new FlujoCaja();
 *   
 * 
 * @package ninguno 
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public 
 * @see no
 * 
 */
class FlujoCaja
{

    /**
     * Representación de la columna 'flujocajaId' de la tabla 'FlujoCaja'
     *
     * @var string
     */
    var $flujocajaId;

    /**
     * Representación de la columna 'fechaCrea' de la tabla 'FlujoCaja'
     *
     * @var string
     */
    var $fechaCrea;

    /**
     * Representación de la columna 'horaCrea' de la tabla 'FlujoCaja'
     *
     * @var string
     */
    var $horaCrea;

    /**
     * Representación de la columna 'usucreaId' de la tabla 'FlujoCaja'
     *
     * @var string
     */
    var $usucreaId;

    /**
     * Representación de la columna 'tipomovId' de la tabla 'FlujoCaja'
     *
     * @var string
     */
    var $tipomovId;

    /**
     * Representación de la columna 'valor' de la tabla 'FlujoCaja'
     *
     * @var string
     */
    var $valor;

    /**
     * Representación de la columna 'ticketId' de la tabla 'FlujoCaja'
     *
     * @var string
     */
    var $ticketId;

    /**
     * Representación de la columna 'traslado' de la tabla 'FlujoCaja'
     *
     * @var string
     */
    var $traslado;

    /**
     * Representación de la columna 'doctrasladoId' de la tabla 'FlujoCaja'
     *
     * @var string
     */
    var $doctrasladoId;

    /**
     * Representación de la columna 'recargaId' de la tabla 'FlujoCaja'
     *
     * @var string
     */
    var $recargaId;

    /**
     * Representación de la columna 'formapago1Id' de la tabla 'FlujoCaja'
     *
     * @var string
     */
    var $formapago1Id;

    /**
     * Representación de la columna 'formapago2Id' de la tabla 'FlujoCaja'
     *
     * @var string
     */
    var $formapago2Id;

    /**
     * Representación de la columna 'valorForma1' de la tabla 'FlujoCaja'
     *
     * @var string
     */
    var $valorForma1;

    /**
     * Representación de la columna 'valorForma2' de la tabla 'FlujoCaja'
     *
     * @var string
     */
    var $valorForma2;

    /**
     * Representación de la columna 'devolucion' de la tabla 'FlujoCaja'
     *
     * @var string
     */
    var $devolucion;

    /**
     * Representación de la columna 'cuentaId' de la tabla 'FlujoCaja'
     *
     * @var string
     */
    var $cuentaId;

    /**
     * Representación de la columna 'mandante' de la tabla 'FlujoCaja'
     *
     * @var string
     */
    var $mandante;

    /**
     * Representación de la columna 'porcenIva' de la tabla 'FlujoCaja'
     *
     * @var string
     */
    var $porcenIva;

    /**
     * Representación de la columna 'valorIva' de la tabla 'FlujoCaja'
     *
     * @var string
     */
    var $valorIva;


    /**
     * Representación de la columna 'recarga_agente_id' de la tabla 'FlujoCaja'
     *
     * @var string
     */
    var $cupologId;
    /**
     * Constructor de clase
     *
     *
     * @param String $flujocajaId id del FlujoCaja
     * @param String $usucreaId id del usucrea
     * @param String $ticketId id del tiquete
     * @param String $recargaId id de la recarga
     *
     * @return no
     * @throws Exception si el usuario no existe
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function __construct($flujocajaId = "", $usucreaId = "", $ticketId = "", $recargaId = "", $cuentaId = "")
    {

        if ($flujocajaId != "") {

            $FlujoCajaMySqlDAO = new FlujoCajaMySqlDAO();
            $FlujoCaja = $FlujoCajaMySqlDAO->load($flujocajaId);

            $this->success = false;

            if ($FlujoCaja != null && $FlujoCaja != "") {

                $this->flujocajaId = $FlujoCaja->flujocajaId;
                $this->fechaCrea = $FlujoCaja->fechaCrea;
                $this->horaCrea = $FlujoCaja->horaCrea;
                $this->usucreaId = $FlujoCaja->usucreaId;
                $this->tipomovId = $FlujoCaja->tipomovId;
                $this->valor = $FlujoCaja->valor;
                $this->ticketId = $FlujoCaja->ticketId;
                $this->traslado = $FlujoCaja->traslado;
                $this->doctrasladoId = $FlujoCaja->doctrasladoId;
                $this->recargaId = $FlujoCaja->recargaId;
                $this->formapago1Id = $FlujoCaja->formapago1Id;
                $this->formapago2Id = $FlujoCaja->formapago2Id;
                $this->valorForma1 = $FlujoCaja->valorForma1;
                $this->valorForma2 = $FlujoCaja->valorForma2;
                $this->devolucion = $FlujoCaja->devolucion;
                $this->cuentaId = $FlujoCaja->cuentaId;
                $this->mandante = $FlujoCaja->mandante;
                $this->porcenIva = $FlujoCaja->porcenIva;
                $this->valorIva = $FlujoCaja->valorIva;
                $this->cupologId = $FlujoCaja->cupologId;

                $this->success = true;
            } else {
                throw new Exception("No existe " . get_class($this), "42");
            }
        } elseif ($usucreaId != "") {
        }
    }


    /**
     * Obtiene el valor de la columna 'flujocajaId' de la tabla 'FlujoCaja'
     * @return mixed
     */
    public function getFlujocajaId()
    {
        return $this->flujocajaId;
    }


    /**
     * Obtiene el valor de la columna 'fechaCrea' de la tabla 'FlujoCaja'
     * @return mixed
     */
    public function getFechaCrea()
    {
        return $this->fechaCrea;
    }

    /**
     * Define el valor de la columna 'fechaCrea' de la tabla 'FlujoCaja'
     * @param mixed $fechaCrea
     */
    public function setFechaCrea($fechaCrea)
    {
        $this->fechaCrea = $fechaCrea;
    }

    /**
     * Obtiene el valor de la columna 'horaCrea' de la tabla 'FlujoCaja'
     * @return mixed
     */
    public function getHoraCrea()
    {
        return $this->horaCrea;
    }

    /**
     * Define el valor de la columna 'horaCrea' de la tabla 'FlujoCaja'
     * @param mixed $horaCrea
     */
    public function setHoraCrea($horaCrea)
    {
        $this->horaCrea = $horaCrea;
    }

    /**
     * Obtiene el valor de la columna 'usucreaId' de la tabla 'FlujoCaja'
     * @return mixed
     */
    public function getUsucreaId()
    {
        return $this->usucreaId;
    }

    /**
     * Define el valor de la columna 'usucreaId' de la tabla 'FlujoCaja'
     * @param mixed $usucreaId
     */
    public function setUsucreaId($usucreaId)
    {
        $this->usucreaId = $usucreaId;
    }

    /**
     * Obtiene el valor de la columna 'tipomovId' de la tabla 'FlujoCaja'
     * @return mixed
     */
    public function getTipomovId()
    {
        return $this->tipomovId;
    }

    /**
     * Define el valor de la columna 'tipomovId' de la tabla 'FlujoCaja'
     * @param mixed $tipomovId
     */
    public function setTipomovId($tipomovId)
    {
        $this->tipomovId = $tipomovId;
    }

    /**
     * Obtiene el valor de la columna 'valor' de la tabla 'FlujoCaja'
     * @return mixed
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Define el valor de la columna 'valor' de la tabla 'FlujoCaja'
     * @param mixed $valor
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    /**
     * Obtiene el valor de la columna 'ticketId' de la tabla 'FlujoCaja'
     * @return mixed
     */
    public function getTicketId()
    {
        return $this->ticketId;
    }

    /**
     * Define el valor de la columna 'ticketId' de la tabla 'FlujoCaja'
     * @param mixed $ticketId
     */
    public function setTicketId($ticketId)
    {
        $this->ticketId = $ticketId;
    }

    /**
     * Obtiene el valor de la columna 'traslado' de la tabla 'FlujoCaja'
     * @return mixed
     */
    public function getTraslado()
    {
        return $this->traslado;
    }

    /**
     * Define el valor de la columna 'traslado' de la tabla 'FlujoCaja'
     * @param mixed $traslado
     */
    public function setTraslado($traslado)
    {
        $this->traslado = $traslado;
    }

    /**
     * Obtiene el valor de la columna 'doctrasladoId' de la tabla 'FlujoCaja'
     * @return mixed
     */
    public function getDoctrasladoId()
    {
        return $this->doctrasladoId;
    }

    /**
     * Define el valor de la columna 'doctrasladoId' de la tabla 'FlujoCaja'
     * @param mixed $doctrasladoId
     */
    public function setDoctrasladoId($doctrasladoId)
    {
        $this->doctrasladoId = $doctrasladoId;
    }

    /**
     * Obtiene el valor de la columna 'recargaId' de la tabla 'FlujoCaja'
     * @return mixed
     */
    public function getRecargaId()
    {
        return $this->recargaId;
    }

    /**
     * Define el valor de la columna 'recargaId' de la tabla 'FlujoCaja'
     * @param mixed $recargaId
     */
    public function setRecargaId($recargaId)
    {
        $this->recargaId = $recargaId;
    }

    /**
     * Obtiene el valor de la columna 'formapago1Id' de la tabla 'FlujoCaja'
     * @return mixed
     */
    public function getFormapago1Id()
    {
        return $this->formapago1Id;
    }

    /**
     * Define el valor de la columna 'formapago1Id' de la tabla 'FlujoCaja'
     * @param mixed $formapago1Id
     */
    public function setFormapago1Id($formapago1Id)
    {
        $this->formapago1Id = $formapago1Id;
    }

    /**
     * Obtiene el valor de la columna 'formapago2Id' de la tabla 'FlujoCaja'
     * @return mixed
     */
    public function getFormapago2Id()
    {
        return $this->formapago2Id;
    }

    /**
     * Define el valor de la columna 'formapago2Id' de la tabla 'FlujoCaja'
     * @param mixed $formapago2Id
     */
    public function setFormapago2Id($formapago2Id)
    {
        $this->formapago2Id = $formapago2Id;
    }

    /**
     * Obtiene el valor de la columna 'valorForma1' de la tabla 'FlujoCaja'
     * @return mixed
     */
    public function getValorForma1()
    {
        return $this->valorForma1;
    }

    /**
     * Define el valor de la columna 'valorForma1' de la tabla 'FlujoCaja'
     * @param mixed $valorForma1
     */
    public function setValorForma1($valorForma1)
    {
        $this->valorForma1 = $valorForma1;
    }

    /**
     * Obtiene el valor de la columna 'valorForma2' de la tabla 'FlujoCaja'
     * @return mixed
     */
    public function getValorForma2()
    {
        return $this->valorForma2;
    }

    /**
     * Define el valor de la columna 'valorForma2' de la tabla 'FlujoCaja'
     * @param mixed $valorForma2
     */
    public function setValorForma2($valorForma2)
    {
        $this->valorForma2 = $valorForma2;
    }

    /**
     * Obtiene el valor de la columna 'devolucion' de la tabla 'FlujoCaja'
     * @return mixed
     */
    public function getDevolucion()
    {
        return $this->devolucion;
    }

    /**
     *  Define el valor de la columna 'devolucion' de la tabla 'FlujoCaja'
     * @param mixed $devolucion
     */
    public function setDevolucion($devolucion)
    {
        $this->devolucion = $devolucion;
    }

    /**
     * Obtiene el valor de la columna 'cuentaId' de la tabla 'FlujoCaja'
     * @return mixed
     */
    public function getCuentaId()
    {
        return $this->cuentaId;
    }

    /**
     * Define el valor de la columna 'cuentaId' de la tabla 'FlujoCaja'
     * @param mixed $cuentaId
     */
    public function setCuentaId($cuentaId)
    {
        $this->cuentaId = $cuentaId;
    }

    /**
     * Obtiene el valor de la columna 'mandante' de la tabla 'FlujoCaja'
     * @return mixed
     */
    public function getMandante()
    {
        return $this->mandante;
    }

    /**
     * Define el valor de la columna 'mandante' de la tabla 'FlujoCaja'
     * @param mixed $mandante
     */
    public function setMandante($mandante)
    {
        $this->mandante = $mandante;
    }

    /**
     * Obtiene el valor de la columna 'porcenIva' de la tabla 'FlujoCaja'
     * @return mixed
     */
    public function getPorcenIva()
    {
        return $this->porcenIva;
    }

    /**
     * Define el valor de la columna 'porcenIva' de la tabla 'FlujoCaja'
     * @param mixed $porcenIva
     */
    public function setPorcenIva($porcenIva)
    {
        $this->porcenIva = $porcenIva;
    }

    /**
     * Obtiene el valor de la columna 'valorIva' de la tabla 'FlujoCaja'
     * @return mixed
     */
    public function getValorIva()
    {
        return $this->valorIva;
    }

    /**
     * Define el valor de la columna 'valorIva' de la tabla 'FlujoCaja'
     * @param mixed $valorIva
     */
    public function setValorIva($valorIva)
    {
        $this->valorIva = $valorIva;
    }

    /**
     * Obtiene el valor de la columna 'cupologId' de la tabla 'FlujoCaja'
     * @return string
     */
    public function getcupologId()
    {
        return $this->cupologId;
    }

    /**
     * Define el valor de la columna 'cupologId' de la tabla 'FlujoCaja'
     * @param string $cupologId
     */
    public function setcupologId($cupologId)
    {
        $this->cupologId = $cupologId;
    }
}
