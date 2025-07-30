<?php namespace Backend\dto;

namespace Backend\dto;

use Backend\mysql\TransapiusuarioLogMySqlDAO;

use Exception;
/**
 * Object represents table 'transjuego_log'
 *
 * @author: http://phpdao.com
 * @date: 2017-09-15 04:48	 
 */
class TransapiusuarioLog
{

/**
 * @var string Representación de la columna 'transapiusuariologId' de la tabla 'transapi_usuario_log'
 */
var $transapiusuariologId;

/**
 * @var string Representación de la columna 'tipo' de la tabla 'transapi_usuario_log'
 */
var $tipo;

/**
 * @var string Representación de la columna 'transaccionId' de la tabla 'transapi_usuario_log'
 */
var $transaccionId;

/**
 * @var string Representación de la columna 'tValue' de la tabla 'transapi_usuario_log'
 */
var $tValue;

/**
 * @var string Representación de la columna 'fechaCrea' de la tabla 'transapi_usuario_log'
 */
var $fechaCrea;

/**
 * @var string Representación de la columna 'usucreaId' de la tabla 'transapi_usuario_log'
 */
var $usucreaId;

/**
 * @var string Representación de la columna 'fechaModif' de la tabla 'transapi_usuario_log'
 */
var $fechaModif;

/**
 * @var string Representación de la columna 'usumodifId' de la tabla 'transapi_usuario_log'
 */
var $usumodifId;

/**
 * @var string Representación de la columna 'valor' de la tabla 'transapi_usuario_log'
 */
var $valor;

/**
 * @var string Representación de la columna 'usuariogeneraId' de la tabla 'transapi_usuario_log'
 */
var $usuariogeneraId;

/**
 * @var string Representación de la columna 'usuarioId' de la tabla 'transapi_usuario_log'
 */
var $usuarioId;

/**
 * @var string Representación de la columna 'identificador' de la tabla 'transapi_usuario_log'
 */
var $identificador;


    /**
     * TransapiusuarioLog constructor.
     * @param $transapiusuariologId
     */
    public function __construct($transapiusuariologId="",$identificador="",$tipo="",$transaccionId="")
    {

        if ($transapiusuariologId !="") {


            $TransapiusuarioLogMySqlDAO = new TransapiusuarioLogMySqlDAO();

            $TransaccionJuego = $TransapiusuarioLogMySqlDAO->load($transapiusuariologId);

            $TransaccionJuego=$TransaccionJuego[0];

            if ($TransaccionJuego != null && $TransaccionJuego != "") {
                $this->transapiusuariologId = $TransaccionJuego->transapiusuariologId;
                $this->tipo = $TransaccionJuego->tipo;
                $this->transaccionId = $TransaccionJuego->transaccionId;
                $this->tValue = $TransaccionJuego->tValue;
                $this->usucreaId = $TransaccionJuego->usucreaId;
                $this->usumodifId = $TransaccionJuego->usumodifId;
                $this->valor = $TransaccionJuego->valor;
                $this->identificador = $TransaccionJuego->identificador;
            }
            else {
                throw new Exception("No existe " . get_class($this), "88");
            }

        }elseif ($identificador != "" && $tipo != "") {

            $TransapiusuarioLogMySqlDAO = new TransapiusuarioLogMySqlDAO();

            $TransaccionJuego = $TransapiusuarioLogMySqlDAO->queryByIdentificadorAndTipo($identificador,$tipo);

            $TransaccionJuego=$TransaccionJuego[0];

            if ($TransaccionJuego != null && $TransaccionJuego != "") {
                $this->transapiusuariologId = $TransaccionJuego->transapiusuariologId;
                $this->tipo = $TransaccionJuego->tipo;
                $this->transaccionId = $TransaccionJuego->transaccionId;
                $this->tValue = $TransaccionJuego->tValue;
                $this->usucreaId = $TransaccionJuego->usucreaId;
                $this->usumodifId = $TransaccionJuego->usumodifId;
                $this->valor = $TransaccionJuego->valor;
                $this->identificador = $TransaccionJuego->identificador;
            }
            else {
                throw new Exception("No existe " . get_class($this), "88");
            }

        }
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
     * Obtiene el ID de la transacción.
     *
     * @return string
     */
    public function getTransaccionId()
    {
        return $this->transaccionId;
    }

    /**
     * Establece el ID de la transacción.
     *
     * @param string $transaccionId
     */
    public function setTransaccionId($transaccionId)
    {
        $this->transaccionId = $transaccionId;
    }

    /**
     * Obtiene el valor de la transacción.
     *
     * @return string
     */
    public function getTValue()
    {
        return $this->tValue;
    }

    /**
     * Establece el valor de la transacción.
     *
     * @param string $tValue
     */
    public function setTValue($tValue)
    {
        $this->tValue = $tValue;
    }

    /**
     * Obtiene la fecha de creación.
     *
     * @return string
     */
    public function getFechaCrea()
    {
        return $this->fechaCrea;
    }

    /**
     * Establece la fecha de creación.
     *
     * @param string $fechaCrea
     */
    public function setFechaCrea($fechaCrea)
    {
        $this->fechaCrea = $fechaCrea;
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
     * Obtiene la fecha de modificación.
     *
     * @return string
     */
    public function getFechaModif()
    {
        return $this->fechaModif;
    }

    /**
     * Establece la fecha de modificación.
     *
     * @param string $fechaModif
     */
    public function setFechaModif($fechaModif)
    {
        $this->fechaModif = $fechaModif;
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
     * Obtiene el ID del usuario que generó.
     *
     * @return string
     */
    public function getUsuariogeneraId()
    {
        return $this->usuariogeneraId;
    }

    /**
     * Establece el ID del usuario que generó.
     *
     * @param string $usuariogeneraId
     */
    public function setUsuariogeneraId($usuariogeneraId)
    {
        $this->usuariogeneraId = $usuariogeneraId;
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
     * Obtiene el identificador.
     *
     * @return string
     */
    public function getIdentificador()
    {
        return $this->identificador;
    }

    /**
     * Establece el identificador.
     *
     * @param string $identificador
     */
    public function setIdentificador($identificador)
    {
        $this->identificador = $identificador;
    }

    /**
     * Obtiene el ID del log de usuario de la API.
     *
     * @return string
     */
    public function getTransapiusuariologId()
    {
        return $this->transapiusuariologId;
    }




    /**
     * Get Domain object by primry key
     *
     * @param
     * @return int
     */
    public function getTransapiusuarioLogsCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping)
    {

        $TransapiusuarioLogMySqlDAO = new TransapiusuarioLogMySqlDAO();

        $transacciones = $TransapiusuarioLogMySqlDAO->queryTransaccionesCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping);

        if ($transacciones != null && $transacciones != "") {

            return $transacciones;


        }
        else {
            throw new Exception("No existe " . get_class($this), "01");
        }


    }

}
?>