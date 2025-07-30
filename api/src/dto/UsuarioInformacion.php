<?php

namespace Backend\dto;

use Backend\mysql\UsuarioInformacionMySqlDAO;
use Exception;

/**
 * clase informacionUsuario
 *
 * esta clase provee una manera de instaciar a la tabla usuario_informacion
 *
 * ejemplo de uso:
 *
 * $usuario_informacion = new usuario_informacion();
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *

 *
 */



class UsuarioInformacion
{
    /**
     * representacion de la columna usuinformacion_id de la tabla usuario_informacion
     * @var string
     */ var $usuinformacionId;



    var $clasificadorId;

    /**
     * representacion de la columna clasificador_id de la tabla usuario_informacion
     */


    /**
     *
     * representacion de la columna usuario_id
     * @var string
     *
     *
     */

    var $usuarioId;


    /**
     *
     * representacion de la columna valor
     * @var string
     *
     *
     */

    var $valor;

    /**
     *
     * representacion de la columna mandante
     * @var string
     *
     *
     */
    var $mandante;

    /**
     *
     * representacion de la columna usumodifId
     * @var string
     *
     *
     */

    var $usumodifId;


    /**
     *
     * representacion de la columna fechaCrea
     * @var string
     *
     *
     */

    var $fechaCrea;

    /**
     *
     * representacion de la columna fechaModif
     * @var string
     *
     *
     */

    var $fechaModif;

    /**
     *
     * representacion de la columna usucreaId
     * @var string
     *
     *
     */

    var $usucreaId;


    /**
     * Constructor de la clase UsuarioInformacion.
     *
     * @param string $usuinformacionId ID de la información del usuario.
     * @param string $clasificadorId ID del clasificador.
     * @param string $usuarioId ID del usuario.
     * @param string $valor Valor de la información.
     * @param string $mandante Mandante de la información.
     *
     * @throws Exception Si no se encuentra la información del usuario.
     */
    public function __construct($usuinformacionId = "", $clasificadorId = "", $usuarioId = "", $valor = "", $mandante = "")
    {

        if ($usuinformacionId != "") {

            $this->usuinformacionId = $usuinformacionId;
            $InformacionUsuarioMySqlDAO = new UsuarioInformacionMySqlDAO();
            $Informacion = $InformacionUsuarioMySqlDAO->load($clasificadorId);
            if ($Informacion != "" and $Informacion != NULL) {
                $this->usuinformacionId = $Informacion->usuinformacionId;
                $this->clasificadorId = $Informacion->clasificadorId;
                $this->usuarioId = $Informacion->usuarioId;
                $this->valor = $Informacion->valor;
                $this->mandante = $Informacion->mandante;
                $this->fechaCrea = $Informacion->fechaCrea;
                $this->usucreaId = $Informacion->usucreaId;
                $this->fechaModif = $Informacion->fechaModif;
                $this->usumodifId = $Informacion->usumodifId;
            } else {
                throw new Exception("No existe" . get_class($this), "115");
            }
        } elseif ($clasificadorId != "" and $usuarioId != "" and $mandante != "") {

            $InformacionUsuarioMySqlDAO = new UsuarioInformacionMySqlDAO();
            $Informacion = $InformacionUsuarioMySqlDAO->queryData($clasificadorId, $usuarioId, $mandante);


            $Informacion = $Informacion[0];
            if ($Informacion != "" and $Informacion != NULL) {
                $this->clasificadorId = $Informacion->clasificadorId;
                $this->usuarioId = $Informacion->usuarioId;
                $this->valor = $Informacion->valor;
                $this->mandante = $Informacion->mandante;
                $this->fechaCrea = $Informacion->fechaCrea;
                $this->usucreaId = $Informacion->usucreaId;
                $this->fechaModif = $Informacion->fechaModif;
                $this->usumodifId = $Informacion->usumodifId;
            } else {
                throw new Exception("No existe" . get_class($this), "115");
            }
        } else if ($usuarioId != "") {
            $InformacionUsuarioMySqlDAO = new UsuarioInformacionMySqlDAO();
            $Informacion = $InformacionUsuarioMySqlDAO->queryByUsuario($usuarioId);
            $Informacion = $Informacion[0];

            if ($Informacion != "" and $Informacion != null and $Informacion != "null") {
                $this->clasificadorId = $Informacion->clasificadorId;
                $this->usuarioId = $Informacion->usuarioId;
                $this->valor = $Informacion->valor;
                $this->mandante = $Informacion->mandante;
                $this->fechaCrea = $Informacion->fechaCrea;
                $this->usucreaId = $Informacion->usucreaId;
                $this->fechaModif = $Informacion->fechaModif;
                $this->usumodifId = $Informacion->usumodifId;
            } else {
                throw new Exception("No existe" . get_class($this), "115");
            }
        } else if ($valor != "") {
            $InformacionUsuarioMySqlDAO = new UsuarioInformacionMySqlDAO();
            $Info = $InformacionUsuarioMySqlDAO->queryByVerifica($valor);
            $Info = $Info[0];

            if ($Info != "" and $Info != "null" and $Info != NULL) {
                $this->clasificadorId = $Info->clasificadorId;
                $this->usuarioId = $Info->usuarioId;
                $this->valor = $Info->valor;
                $this->mandante = $Info->mandante;
                $this->fechaCrea = $Info->fechaCrea;
                $this->usucreaId = $Info->usucreaId;
                $this->fechaModif = $Info->fechaModif;
            } else {
                throw new Exception("No existe" . get_class($this), "115");
            }
        } else if ($mandante != "") {
            $InformacionUsuarioMySqlDAO = new UsuarioInformacionMySqlDAO();
            $Info = $InformacionUsuarioMySqlDAO->queryByMandante($mandante);
            $Info1 = [0];

            if ($Info1 != "" and $Info1 != "null" and $Info1 != null) {
                $this->clasificadorId = $Info1->clasificadorId;
                $this->usuarioId = $Info1->usuarioId;
                $this->valor = $Info1->valor;
                $this->mandante = $Info1->mandante;
                $this->fechaCrea = $Info1->fechaCrea;
                $this->usucreaId = $Info1->usucreaId;
                $this->fechaModif = $Info1->fechaModif;
                $this->usumodifId = $Info1->usumodifId;
            } else {
                throw new Exception("No existe" . get_class($this), "115");
            }
        }
    }

    /**
     * Obtiene información personalizada del usuario.
     *
     * @param string $select Campos a seleccionar.
     * @param string $sidx Índice de ordenación.
     * @param string $sord Orden de clasificación (ascendente o descendente).
     * @param int $start Inicio de los registros.
     * @param int $limit Límite de registros.
     * @param string $filters Filtros aplicados a la consulta.
     * @param bool $searchOn Indica si la búsqueda está activada.
     * @param bool $grouping Indica si se agrupan los resultados.
     * @return array Datos obtenidos de la consulta.
     * @throws Exception Si no existen datos.
     */
    public function getusuarioInformacionCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping)
    {

        $UsuarioInformacion = new UsuarioInformacionMySqlDAO();

        $datos = $UsuarioInformacion->queryUsuarioInformacionCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping);

        if ($datos != "" and $datos != null and $datos != "null") {
            return $datos;
        } else {
            throw new Exception("No existe" . get_class($this), "115");
        }
    }


    /**
     * Verifica si un usuario ya existe en la base de datos.
     *
     * @param string $clasificador El clasificador que se utilizará para la verificación 
     * @param mixed $valor El valor del clasificador que se va a verificar 
     * @param int $usuariomandante El ID del usuario mandante que realiza la verificación.
     * @return bool Retorna true si el usuario ya existe, de lo contrario retorna false.
     */
    public function verificacionUsuarioExistente($clasificador, $valor, $usuariomandante)
    {
        $UsuarioInformacion = new UsuarioInformacionMySqlDAO();
        $datos = $UsuarioInformacion->verificarUsuario($clasificador, $valor, $usuariomandante);

        // Si $datos es verdadero, significa que el usuario ya existe
        if ($datos) {
            return true;
        } else {
            return false;
        }
    }





/**
     * Obtiene el ID de la información del usuario.
     *
     * @return string
     */
    public function getIduserInfo()
    {
        return $this->usuinformacionId;
    }

    /**
     * Establece el ID de la información del usuario.
     *
     * @param string $usuinformacionId
     */
    public function setIduserInfo($usuinformacionId)
    {
        $this->usuinformacionId = $usuinformacionId;
    }

    /**
     * Establece el ID del clasificador.
     *
     * @param string $clasificadorId
     */
    public function setClasificadorId($clasificadorId)
    {
        $this->clasificadorId = $clasificadorId;
    }

    /**
     * Obtiene el ID del clasificador.
     *
     * @return string
     */
    public function getClasificadorId()
    {
        return $this->clasificadorId;
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
     * Obtiene el valor de la información.
     *
     * @return string
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Establece el valor de la información.
     *
     * @param string $valor
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    /**
     * Obtiene el mandante de la información.
     *
     * @return string
     */
    public function getMandante()
    {
        return $this->mandante;
    }

    /**
     * Establece el mandante de la información.
     *
     * @param string $mandante
     */
    public function setMandante($mandante)
    {
        $this->mandante = $mandante;
    }

    /**
     * Obtiene la fecha de creación de la información.
     *
     * @return string
     */
    public function getfechaCrea()
    {
        return $this->fechaCrea;
    }

    /**
     * Obtiene la fecha de modificación de la información.
     *
     * @return string
     */
    public function getfechaModif()
    {
        return $this->fechaModif;
    }

    /**
     * Establece el ID del usuario que modificó la información.
     *
     * @param string $usumodifica
     */
    public function setUsuModif($usumodifica)
    {
        $this->usumodifId = $usumodifica;
    }

    /**
     * Obtiene el ID del usuario que modificó la información.
     *
     * @return string
     */
    public function getUsuModif()
    {
        return $this->usumodifId;
    }

    /**
     * Establece el ID del usuario que creó la información.
     *
     * @param string $UsuCreaId
     */
    public function setUsuCreaId($UsuCreaId)
    {
        $this->usucreaId = $UsuCreaId;
    }

    /**
     * Obtiene el ID del usuario que creó la información.
     *
     * @return string
     */
    public function getUsuCreaId()
    {
        return $this->usucreaId;
    }
}
