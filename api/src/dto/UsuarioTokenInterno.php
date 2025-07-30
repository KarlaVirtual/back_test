<?php namespace Backend\dto;


use Backend\mysql\UsuarioTokenInternoMySqlDAO;
use Exception;

/**
 * Object represents table 'int_deporte'
 *
 * @author: http://phpdao.com
 * @date: 2018-02-11 17:49
 * @category No
 * @package No
 * @version     1.0
 */
class UsuarioTokenInterno
{

/**
     * @var string Representación de la columna 'usutokeninterno_id' en la tabla 'usuario_token_interno'
     */
    var $usutokeninternoId;

    /**
     * @var string Representación de la columna 'usuario_id' en la tabla 'usuario_token_interno'
     */
    var $usuarioId;

    /**
     * @var string Representación de la columna 'usuarioaprobar_id' en la tabla 'usuario_token_interno'
     */
    var $usuarioaprobarId;

    /**
     * @var string Representación de la columna 'usuario_ip' en la tabla 'usuario_token_interno'
     */
    var $usuarioIp;

    /**
     * @var string Representación de la columna 'usuarioaprobar_ip' en la tabla 'usuario_token_interno'
     */
    var $usuarioaprobarIp;

    /**
     * @var string Representación de la columna 'usuariosolicita_id' en la tabla 'usuario_token_interno'
     */
    var $usuariosolicitaId;

    /**
     * @var string Representación de la columna 'usuariosolicita_ip' en la tabla 'usuario_token_interno'
     */
    var $usuariosolicitaIp;

    /**
     * @var string Representación de la columna 'tipo' en la tabla 'usuario_token_interno'
     */
    var $tipo;

    /**
     * @var string Representación de la columna 'estado' en la tabla 'usuario_token_interno'
     */
    var $estado;

    /**
     * @var string Representación de la columna 'valor' en la tabla 'usuario_token_interno'
     */
    var $valor;

    /**
     * @var string Representación de la columna 'usucrea_id' en la tabla 'usuario_token_interno'
     */
    var $usucreaId;

    /**
     * @var string Representación de la columna 'usumodif_id' en la tabla 'usuario_token_interno'
     */
    var $usumodifId;

    /**
     * @var string Representación de la columna 'fecha_crea' en la tabla 'usuario_token_interno'
     */
    var $fechaCrea;

    /**
     * @var string Representación de la columna 'fecha_modif' en la tabla 'usuario_token_interno'
     */
    var $fechaModif;


    /**
     * UsuarioTokenInterno constructor.
     * @param $usuarioId
     * @param $tipo
     */
    public function __construct($usutokeninternoId="")
    {

        if ($usutokeninternoId != "" ) {


            $UsuarioTokenInternoMySqlDAO = new UsuarioTokenInternoMySqlDAO();

            $UsuarioTokenInterno = $UsuarioTokenInternoMySqlDAO->load($usutokeninternoId);

            if ($UsuarioTokenInterno != null && $UsuarioTokenInterno != "") {
                $this->usutokeninternoId = $UsuarioTokenInterno->usutokeninternoId;
                $this->usuarioId = $UsuarioTokenInterno->usuarioId;
                $this->usuarioaprobarId = $UsuarioTokenInterno->usuarioaprobarId;
                $this->usuariosolicitaIp = $UsuarioTokenInterno->usuariosolicitaIp;
                $this->usuariosolicitaId = $UsuarioTokenInterno->usuariosolicitaId;

                $this->usuarioIp = $UsuarioTokenInterno->usuarioIp;
                $this->usuarioaprobarIp = $UsuarioTokenInterno->usuarioaprobarIp;
                $this->tipo = $UsuarioTokenInterno->tipo;
                $this->valor = $UsuarioTokenInterno->valor;
                $this->usucreaId = $UsuarioTokenInterno->usucreaId;
                $this->usumodifId = $UsuarioTokenInterno->usumodifId;

            } else {
                throw new Exception("No existe " . get_class($this), "86");
            }
        }



    }

    /**
     * Get Domain object by primry key
     *
     * @param
     * @return int
     */
    public function getUsuarioTokenInternosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $UsuarioTokenInternoMySqlDAO = new UsuarioTokenInternoMySqlDAO();

        $Usuario = $UsuarioTokenInternoMySqlDAO->queryUsuarioTokenInternosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($Usuario != null && $Usuario != "") {

            return $Usuario;

        } else {
            throw new Exception("No existe " . get_class($this), "01");
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
     * Obtiene el ID del usuario a aprobar.
     *
     * @return string
     */
    public function getUsuarioaprobarId()
    {
        return $this->usuarioaprobarId;
    }

    /**
     * Establece el ID del usuario a aprobar.
     *
     * @param string $usuarioaprobarId
     */
    public function setUsuarioaprobarId($usuarioaprobarId)
    {
        $this->usuarioaprobarId = $usuarioaprobarId;
    }

    /**
     * Obtiene la IP del usuario.
     *
     * @return string
     */
    public function getUsuarioIp()
    {
        return $this->usuarioIp;
    }

    /**
     * Establece la IP del usuario.
     *
     * @param string $usuarioIp
     */
    public function setUsuarioIp($usuarioIp)
    {
        $this->usuarioIp = $usuarioIp;
    }

    /**
     * Obtiene la IP del usuario a aprobar.
     *
     * @return string
     */
    public function getUsuarioaprobarIp()
    {
        return $this->usuarioaprobarIp;
    }

    /**
     * Establece la IP del usuario a aprobar.
     *
     * @param string $usuarioaprobarIp
     */
    public function setUsuarioaprobarIp($usuarioaprobarIp)
    {
        $this->usuarioaprobarIp = $usuarioaprobarIp;
    }

    /**
     * Obtiene el ID del usuario que solicita.
     *
     * @return string
     */
    public function getUsuariosolicitaId()
    {
        return $this->usuariosolicitaId;
    }

    /**
     * Establece el ID del usuario que solicita.
     *
     * @param string $usuariosolicitaId
     */
    public function setUsuariosolicitaId($usuariosolicitaId)
    {
        $this->usuariosolicitaId = $usuariosolicitaId;
    }

    /**
     * Obtiene la IP del usuario que solicita.
     *
     * @return string
     */
    public function getUsuariosolicitaIp()
    {
        return $this->usuariosolicitaIp;
    }

    /**
     * Establece la IP del usuario que solicita.
     *
     * @param string $usuariosolicitaIp
     */
    public function setUsuariosolicitaIp($usuariosolicitaIp)
    {
        $this->usuariosolicitaIp = $usuariosolicitaIp;
    }

    /**
     * Obtiene el tipo de token.
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Establece el tipo de token.
     *
     * @param string $tipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    /**
     * Obtiene el estado del token.
     *
     * @return string
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Establece el estado del token.
     *
     * @param string $estado
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    /**
     * Obtiene el valor del token.
     *
     * @return string
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Establece el valor del token.
     *
     * @param string $valor
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
    }

/**
     * Obtiene el ID del usuario que crea.
     *
     * @return string
     */
    public function getUsucreaId()
    {
        return $this->usucreaId;
    }

    /**
     * Establece el ID del usuario que crea.
     *
     * @param string $usucreaId
     */
    public function setUsucreaId($usucreaId)
    {
        $this->usucreaId = $usucreaId;
    }

    /**
     * Obtiene el ID del usuario que modifica.
     *
     * @return string
     */
    public function getUsumodifId()
    {
        return $this->usumodifId;
    }

    /**
     * Establece el ID del usuario que modifica.
     *
     * @param string $usumodifId
     */
    public function setUsumodifId($usumodifId)
    {
        $this->usumodifId = $usumodifId;
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
     * Obtiene la fecha de modificación.
     *
     * @return string
     */
    public function getFechaModif()
    {
        return $this->fechaModif;
    }

    /**
     * Obtiene el ID del token interno del usuario.
     *
     * @return string
     */
    public function getUsutokeninternoId()
    {
        return $this->usutokeninternoId;
    }



}

?>