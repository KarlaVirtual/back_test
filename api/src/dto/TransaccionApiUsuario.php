<?php namespace Backend\dto;

namespace Backend\dto;

use Backend\mysql\TransaccionApiUsuarioMySqlDAO;

use Exception;
/**
 * Object represents table 'transaccion_api'
 *
 * @author: http://phpdao.com
 * @date: 2017-09-15 04:48	 
 */
class TransaccionApiUsuario
{
/**
 * @var string Representación de la columna 'transapiusuario_id' de la tabla 'transaccionapi_usuario'
 */
var $transapiusuarioId;

/**
 * @var string Representación de la columna 'usuariogenera_id' de la tabla 'transaccionapi_usuario'
 */
var $usuariogeneraId;

/**
 * @var string Representación de la columna 'usuario_id' de la tabla 'transaccionapi_usuario'
 */
var $usuarioId;

/**
 * @var string Representación de la columna 'tipo' de la tabla 'transaccionapi_usuario'
 */
var $tipo;

/**
 * @var string Representación de la columna 'transaccion_id' de la tabla 'transaccionapi_usuario'
 */
var $transaccionId;

/**
 * @var string Representación de la columna 'identificador' de la tabla 'transaccionapi_usuario'
 */
var $identificador;

/**
 * @var string Representación de la columna 't_value' de la tabla 'transaccionapi_usuario'
 */
var $tValue;

/**
 * @var string Representación de la columna 'respuesta_codigo' de la tabla 'transaccionapi_usuario'
 */
var $respuestaCodigo;

/**
 * @var string Representación de la columna 'respuesta' de la tabla 'transaccionapi_usuario'
 */
var $respuesta;

/**
 * @var string Representación de la columna 'fecha_crea' de la tabla 'transaccionapi_usuario'
 */
var $fechaCrea;

/**
 * @var string Representación de la columna 'usucrea_id' de la tabla 'transaccionapi_usuario'
 */
var $usucreaId;

/**
 * @var string Representación de la columna 'fecha_modif' de la tabla 'transaccionapi_usuario'
 */
var $fechaModif;

/**
 * @var string Representación de la columna 'usumodif_id' de la tabla 'transaccionapi_usuario'
 */
var $usumodifId;

/**
 * @var string Representación de la columna 'valor' de la tabla 'transaccionapi_usuario'
 */
var $valor;


    /**
     * TransaccionApiUsuario constructor.
     * @param $transapiusuarioId
     * @param $transaccionId
     * @param $usuariogeneraId
     */
    public function __construct($transapiusuarioId="", $transaccionId="", $usuariogeneraId = "", $tipo="")
    {
        if ($transaccionId != "" && $transaccionId != "" &&  $tipo != "") {

            $TransaccionApiUsuarioMySqlDAO = new TransaccionApiUsuarioMySqlDAO();

            $TransaccionApiUsuario = $TransaccionApiUsuarioMySqlDAO->queryByTransaccionIdAndUsuariogeneraIdAndTipo($transaccionId, $usuariogeneraId, $tipo);

            $TransaccionApiUsuario = $TransaccionApiUsuario[0];

            if ($TransaccionApiUsuario != "" && $TransaccionApiUsuario != null) {
                $this->transapiusuarioId = $TransaccionApiUsuario->transapiusuarioId;
                $this->usuariogeneraId = $TransaccionApiUsuario->usuariogeneraId;
                $this->usuarioId = $TransaccionApiUsuario->usuarioId;
                $this->tipo = $TransaccionApiUsuario->tipo;
                $this->transaccionId = $TransaccionApiUsuario->transaccionId;
                $this->identificador = $TransaccionApiUsuario->identificador;
                $this->tValue = $TransaccionApiUsuario->tValue;
                $this->respuesta = $TransaccionApiUsuario->respuesta;
                $this->respuestaCodigo = $TransaccionApiUsuario->respuestaCodigo;
                $this->fechaCrea = $TransaccionApiUsuario->fechaCrea;
                $this->usucreaId = $TransaccionApiUsuario->usucreaId;
                $this->fechaModif = $TransaccionApiUsuario->fechaModif;
                $this->usumodifId = $TransaccionApiUsuario->usumodifId;
                $this->valor = $TransaccionApiUsuario->valor;

            } else {
                throw new Exception("No existe " . get_class($this), "87");

            }

        }

    }


    /**
     * Get Domain object by primry key
     *
     * @param
     * @return int
     */
    public function getTransaccionesCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping)
    {

        $TransaccionApiUsuarioMySqlDAO = new TransaccionApiUsuarioMySqlDAO();

        $transacciones = $TransaccionApiUsuarioMySqlDAO->queryTransaccionesCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping);

        if ($transacciones != null && $transacciones != "") {

            return $transacciones;


        }
        else {
            throw new Exception("No existe " . get_class($this), "01");
        }


    }

/**
     * Obtiene el ID del usuario que genera la transacción.
     *
     * @return string
     */
    public function getUsuariogeneraId()
    {
        return $this->usuariogeneraId;
    }

    /**
     * Establece el ID del usuario que genera la transacción.
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
     * Obtiene el tipo de transacción.
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Establece el tipo de transacción.
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
     * Obtiene el identificador de la transacción.
     *
     * @return string
     */
    public function getIdentificador()
    {
        return $this->identificador;
    }

    /**
     * Establece el identificador de la transacción.
     *
     * @param string $identificador
     */
    public function setIdentificador($identificador)
    {
        $this->identificador = $identificador;
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
     * Obtiene el código de respuesta de la transacción.
     *
     * @return string
     */
    public function getRespuestaCodigo()
    {
        return $this->respuestaCodigo;
    }

    /**
     * Establece el código de respuesta de la transacción.
     *
     * @param string $respuestaCodigo
     */
    public function setRespuestaCodigo($respuestaCodigo)
    {
        $this->respuestaCodigo = $respuestaCodigo;
    }

    /**
     * Obtiene la respuesta de la transacción.
     *
     * @return string
     */
    public function getRespuesta()
    {
        return $this->respuesta;
    }

    /**
     * Establece la respuesta de la transacción.
     *
     * @param string $respuesta
     */
    public function setRespuesta($respuesta)
    {
        $this->respuesta = $respuesta;
    }

    /**
     * Obtiene la fecha de creación de la transacción.
     *
     * @return string
     */
    public function getFechaCrea()
    {
        return $this->fechaCrea;
    }

    /**
     * Obtiene el ID del usuario que crea la transacción.
     *
     * @return string
     */
    public function getUsucreaId()
    {
        return $this->usucreaId;
    }

    /**
     * Establece el ID del usuario que crea la transacción.
     *
     * @param string $usucreaId
     */
    public function setUsucreaId($usucreaId)
    {
        $this->usucreaId = $usucreaId;
    }

    /**
     * Obtiene la fecha de modificación de la transacción.
     *
     * @return string
     */
    public function getFechaModif()
    {
        return $this->fechaModif;
    }

    /**
     * Obtiene el ID del usuario que modifica la transacción.
     *
     * @return string
     */
    public function getUsumodifId()
    {
        return $this->usumodifId;
    }

    /**
     * Establece el ID del usuario que modifica la transacción.
     *
     * @param string $usumodifId
     */
    public function setUsumodifId($usumodifId)
    {
        $this->usumodifId = $usumodifId;
    }

    /**
     * Obtiene el valor de la transacción.
     *
     * @return string
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Establece el valor de la transacción.
     *
     * @param string $valor
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    /**
     * Obtiene el ID de la transacción API usuario.
     *
     * @return string
     */
    public function getTransapiusuarioId()
    {
        return $this->transapiusuarioId;
    }


    /**
     * Consultar si existen registros con el código de respuesta
     * pasado como parámetro
     *
     *
     * @param String $respuestaCODE respuestaCODE
     *
     * @return boolean $ resultado de la consulta
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function existsTransaccionIdAndProveedor($respuestaCODE)
    {

        $TransaccionApiUsuarioMySqlDAO = new TransaccionApiUsuarioMySqlDAO();

        $TransaccionApi = $TransaccionApiUsuarioMySqlDAO->queryByTransaccionIdAndProveedor($this->transaccionId, $this->usuariogeneraId, $respuestaCODE);

        if (oldCount($TransaccionApi) > 0)
        {
            return true;
        }

        return false;

    }


}
?>