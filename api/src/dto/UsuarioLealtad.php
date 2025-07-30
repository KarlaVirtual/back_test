<?php namespace Backend\dto;
use Backend\mysql\UsuarioLealtadDetalleMySqlDAO;
use Backend\mysql\UsuarioLealtadMySqlDAO;
use Exception;
/**
 * Clase 'UsuarioLealtad'
 *
 * Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
 * para la tabla 'UsuarioLealtad'
 *
 * Ejemplo de uso:
 * $UsuarioLealtad = new UsuarioLealtad();
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class UsuarioLealtad
{

    /**
     * Representación de la columna 'usulealtadId' de la tabla 'UsuarioLealtad'
     *
     * @var string
     */
    var $usulealtadId;

    /**
     * Representación de la columna 'usuarioId' de la tabla 'UsuarioLealtad'
     *
     * @var string
     */
    var $usuarioId;

    /**
     * Representación de la columna 'lealtadId' de la tabla 'UsuarioLealtad'
     *
     * @var string
     */
    var $lealtadId;

    /**
     * Representación de la columna 'valor' de la tabla 'UsuarioLealtad'
     *
     * @var string
     */
    var $valor;

    /**
     * Representación de la columna 'valorLealtad' de la tabla 'UsuarioLealtad'
     *
     * @var string
     */
    var $valorLealtad;

    /**
     * Representación de la columna 'valorBase' de la tabla 'UsuarioLealtad'
     *
     * @var string
     */
    var $valorBase;

    /**
     * Representación de la columna 'fechaCrea' de la tabla 'UsuarioLealtad'
     *
     * @var string
     */
    var $fechaCrea;

    /**
     * Representación de la columna 'usucreaId' de la tabla 'UsuarioLealtad'
     *
     * @var string
     */
    var $usucreaId;

    /**
     * Representación de la columna 'fechaModif' de la tabla 'UsuarioLealtad'
     *
     * @var string
     */
    var $fechaModif;

    /**
     * Representación de la columna 'usumodifId' de la tabla 'UsuarioLealtad'
     *
     * @var string
     */
    var $usumodifId;

    /**
     * Representación de la columna 'estado' de la tabla 'UsuarioLealtad'
     *
     * @var string
     */
    var $estado;

    /**
     * Representación de la columna 'errorId' de la tabla 'UsuarioLealtad'
     *
     * @var string
     */
    var $errorId;

    /**
     * Representación de la columna 'idExterno' de la tabla 'UsuarioLealtad'
     *
     * @var string
     */
    var $idExterno;

    /**
     * Representación de la columna 'mandante' de la tabla 'UsuarioLealtad'
     *
     * @var string
     */
    var $mandante;

    /**
     * Representación de la columna 'version' de la tabla 'UsuarioLealtad'
     *
     * @var string
     */
    var $version;

    /**
     * Representación de la columna 'apostado' de la tabla 'UsuarioLealtad'
     *
     * @var string
     */
    var $apostado;

    /**
     * Representación de la columna 'rollowerRequerido' de la tabla 'UsuarioLealtad'
     *
     * @var string
     */
    var $rollowerRequerido;

    /**
     * Representación de la columna 'codigo' de la tabla 'UsuarioLealtad'
     *
     * @var string
     */
    var $codigo;

    /**
     * Representación de la columna 'externoId' de la tabla 'UsuarioLealtad'
     *
     * @var string
     */
    var $externoId;

    /**
     * Representación de la columna 'premio' de la tabla 'UsuarioLealtad'
     *
     * @var string
     */
    var $premio;

    /**
     * Representación de la columna 'observacion' de la tabla 'UsuarioLealtad'
     *
     * @var string
     */
    var $observacion;

    /**
     * Representación de la columna 'puntoventaentrega' de la tabla 'UsuarioLealtad'
     *
     * @var string
     */
    var $puntoventaentrega;

    /**
     * Representación de la columna 'nombreusuentrega' de la tabla 'UsuarioLealtad'
     *
     * @var string
     */
    var $nombreusuentrega;

    /**
     * Representación de la columna 'apellidousuentrega' de la tabla 'UsuarioLealtad'
     *
     * @var string
     */
    var $apellidousuentrega;

    /**
     * Representación de la columna 'cedulausuentrega' de la tabla 'UsuarioLealtad'
     *
     * @var string
     */
    var $cedulausuentrega;

    /**
     * Representación de la columna 'telefonousuentrega' de la tabla 'UsuarioLealtad'
     *
     * @var string
     */
    var $telefonousuentrega;

    /**
     * Representación de la columna 'ciudadusuentrega' de la tabla 'UsuarioLealtad'
     *
     * @var string
     */
    var $ciudadusuentrega;

    /**
     * Representación de la columna 'provinciausuentrega' de la tabla 'UsuarioLealtad'
     *
     * @var string
     */
    var $provinciausuentrega;

    /**
     * Representación de la columna 'direccionusuentrega' de la tabla 'UsuarioLealtad'
     *
     * @var string
     */
    var $direccionusuentrega;

    /**
     * Representación de la columna 'teamusuentrega' de la tabla 'UsuarioLealtad'
     *
     * @var string
     */
    var $teamusuentrega;





    /**
     * Constructor de clase
     *
     *
     * @param String $usulealtadId usulealtadId
     * @param String $usuarioId usuarioId
     *
     * @return no
     * @throws Exception si UsuarioLealtad no existe
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function __construct($usulealtadId="", $usuarioId="",$lealtadId="")
    {
        if ($usulealtadId != "")
        {

            $this->usulealtadId = $usulealtadId;

            $UsuarioLealtadMySqlDAO = new UsuarioLealtadMySqlDAO();

            $UsuarioLealtad = $UsuarioLealtadMySqlDAO->load($usulealtadId);

            if ($UsuarioLealtad != null && $UsuarioLealtad != "")
            {
                $this->usulealtadId = $UsuarioLealtad->usulealtadId;
                $this->usuarioId = $UsuarioLealtad->usuarioId;
                $this->lealtadId = $UsuarioLealtad->lealtadId;
                $this->valor = $UsuarioLealtad->valor;
                $this->valorLealtad = $UsuarioLealtad->valorLealtad;
                $this->valorBase = $UsuarioLealtad->valorBase;
                $this->fechaCrea = $UsuarioLealtad->fechaCrea;
                $this->usucreaId = $UsuarioLealtad->usucreaId;
                $this->fechaModif = $UsuarioLealtad->fechaModif;
                $this->usumodifId = $UsuarioLealtad->usumodifId;
                $this->estado = $UsuarioLealtad->estado;
                $this->errorId = $UsuarioLealtad->errorId;
                $this->idExterno = $UsuarioLealtad->idExterno;
                $this->mandante = $UsuarioLealtad->mandante;
                $this->version = $UsuarioLealtad->version;
                $this->apostado = $UsuarioLealtad->apostado;
                $this->rollowerRequerido = $UsuarioLealtad->rollowerRequerido;
                $this->codigo = $UsuarioLealtad->codigo;
                $this->externoId = $UsuarioLealtad->externoId;
                $this->observacion = $UsuarioLealtad->observacion;

                $this->puntoventaentrega=$UsuarioLealtad->puntoventaentrega;
                $this->nombreusuentrega=$UsuarioLealtad->nombreusuentrega;
                $this->apellidousuentrega=$UsuarioLealtad->apellidousuentrega;
                $this->cedulausuentrega=$UsuarioLealtad->cedulausuentrega;
                $this->telefonousuentrega=$UsuarioLealtad->telefonousuentrega;
                $this->ciudadusuentrega=$UsuarioLealtad->ciudadusuentrega;
                $this->provinciausuentrega=$UsuarioLealtad->provinciausuentrega;
                $this->direccionusuentrega=$UsuarioLealtad->direccionusuentrega;
                $this->teamusuentrega=$UsuarioLealtad->teamusuentrega;

            }
            else
            {
                throw new Exception("No existe " . get_class($this), "33");
            }
        }
        elseif ($usuarioId != "" && $bannerId != "")
        {
            $UsuarioLealtadMySqlDAO = new UsuarioLealtadMySqlDAO();

            $UsuarioLealtad = $UsuarioLealtadMySqlDAO->queryByUsuarioIdAndBannerId($usuarioId,$bannerId);
            $UsuarioLealtad = $UsuarioLealtad[0];

            if ($UsuarioLealtad != null && $UsuarioLealtad != "")
            {
                $this->usulealtadId = $UsuarioLealtad->usulealtadId;
                $this->usuarioId = $UsuarioLealtad->usuarioId;
                $this->lealtadId = $UsuarioLealtad->lealtadId;
                $this->valor = $UsuarioLealtad->valor;
                $this->valorLealtad = $UsuarioLealtad->valorLealtad;
                $this->valorBase = $UsuarioLealtad->valorBase;
                $this->fechaCrea = $UsuarioLealtad->fechaCrea;
                $this->usucreaId = $UsuarioLealtad->usucreaId;
                $this->fechaModif = $UsuarioLealtad->fechaModif;
                $this->usumodifId = $UsuarioLealtad->usumodifId;
                $this->estado = $UsuarioLealtad->estado;
                $this->errorId = $UsuarioLealtad->errorId;
                $this->idExterno = $UsuarioLealtad->idExterno;
                $this->mandante = $UsuarioLealtad->mandante;
                $this->version = $UsuarioLealtad->version;
                $this->apostado = $UsuarioLealtad->apostado;
                $this->rollowerRequerido = $UsuarioLealtad->rollowerRequerido;
                $this->codigo = $UsuarioLealtad->codigo;
                $this->externoId = $UsuarioLealtad->externoId;
                $this->observacion = $UsuarioLealtad->observacion;

                $this->puntoventaentrega=$UsuarioLealtad->puntoventaentrega;
                $this->nombreusuentrega=$UsuarioLealtad->nombreusuentrega;
                $this->apellidousuentrega=$UsuarioLealtad->apellidousuentrega;
                $this->cedulausuentrega=$UsuarioLealtad->cedulausuentrega;
                $this->telefonousuentrega=$UsuarioLealtad->telefonousuentrega;
                $this->ciudadusuentrega=$UsuarioLealtad->ciudadusuentrega;
                $this->provinciausuentrega=$UsuarioLealtad->provinciausuentrega;
                $this->direccionusuentrega=$UsuarioLealtad->direccionusuentrega;
                $this->teamusuentrega=$UsuarioLealtad->teamusuentrega;

            }
            else
            {
                throw new Exception("No existe " . get_class($this), "33");
            }

        }
        elseif ( $usuarioId != "" && $lealtadId != "")
        {

            $UsuarioLealtadMySqlDAO = new UsuarioLealtadMySqlDAO();

            $UsuarioLealtad = $UsuarioLealtadMySqlDAO->queryByUsuarioIdAndLealtadId($usuarioId,$lealtadId);


            if ($UsuarioLealtad != null && $UsuarioLealtad != "")
            {

                $this->usulealtadId = $UsuarioLealtad->usulealtadId;
                $this->usuarioId = $UsuarioLealtad->usuarioId;
                $this->lealtadId = $UsuarioLealtad->lealtadId;
                $this->valor = $UsuarioLealtad->valor;
                $this->valorLealtad = $UsuarioLealtad->valorLealtad;
                $this->valorBase = $UsuarioLealtad->valorBase;
                $this->fechaCrea = $UsuarioLealtad->fechaCrea;
                $this->usucreaId = $UsuarioLealtad->usucreaId;
                $this->fechaModif = $UsuarioLealtad->fechaModif;
                $this->usumodifId = $UsuarioLealtad->usumodifId;
                $this->estado = $UsuarioLealtad->estado;
                $this->errorId = $UsuarioLealtad->errorId;
                $this->idExterno = $UsuarioLealtad->idExterno;
                $this->mandante = $UsuarioLealtad->mandante;
                $this->version = $UsuarioLealtad->version;
                $this->apostado = $UsuarioLealtad->apostado;
                $this->rollowerRequerido = $UsuarioLealtad->rollowerRequerido;
                $this->codigo = $UsuarioLealtad->codigo;
                $this->externoId = $UsuarioLealtad->externoId;
                $this->premio = $UsuarioLealtad->premio;
                $this->observacion = $UsuarioLealtad->observacion;

                $this->puntoventaentrega=$UsuarioLealtad->puntoventaentrega;
                $this->nombreusuentrega=$UsuarioLealtad->nombreusuentrega;
                $this->apellidousuentrega=$UsuarioLealtad->apellidousuentrega;
                $this->cedulausuentrega=$UsuarioLealtad->cedulausuentrega;
                $this->telefonousuentrega=$UsuarioLealtad->telefonousuentrega;
                $this->ciudadusuentrega=$UsuarioLealtad->ciudadusuentrega;
                $this->provinciausuentrega=$UsuarioLealtad->provinciausuentrega;
                $this->direccionusuentrega=$UsuarioLealtad->direccionusuentrega;
                $this->teamusuentrega=$UsuarioLealtad->teamusuentrega;
            }
            else
            {
                throw new Exception("No existe " . get_class($this), "33");
            }
        }

    }





    /**
     * Obtener el campo usuarioId de un objeto
     *
     * @return String usuarioId usuarioId
     *
     */
    public function getUsuarioId()
    {
        return $this->usuarioId;
    }

    /**
     * Modificar el campo 'usuarioId' de un objeto
     *
     * @param String $usuarioId usuarioId
     *
     * @return no
     *
     */
    public function setUsuarioId($usuarioId)
    {
        $this->usuarioId = $usuarioId;
    }

    /**
     * Obtener el campo lealtadId de un objeto
     *
     * @return String lealtadId lealtadId
     *
     */
    public function getLealtadId()
    {
        return $this->lealtadId;
    }

    /**
     * Modificar el campo 'lealtadId' de un objeto
     *
     * @param String $lealtadId lealtadId
     *
     * @return no
     *
     */
    public function setLealtadId($lealtadId)
    {
        $this->lealtadId = $lealtadId;
    }

    /**
     * Obtener el campo valor de un objeto
     *
     * @return String valor valor
     *
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Modificar el campo 'valor' de un objeto
     *
     * @param String $valor valor
     *
     * @return no
     *
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    /**
     * Obtener el campo valorLealtad de un objeto
     *
     * @return String valorLealtad valorLealtad
     *
     */
    public function getValorLealtad()
    {
        return $this->valorLealtad;
    }

    /**
     * Modificar el campo 'valorLealtad' de un objeto
     *
     * @param String $valorLealtad valorLealtad
     *
     * @return no
     *
     */
    public function setValorLealtad($valorLealtad)
    {
        $this->valorLealtad = $valorLealtad;
    }

    /**
     * Obtener el campo valorBase de un objeto
     *
     * @return String valorBase valorBase
     *
     */
    public function getValorBase()
    {
        return $this->valorBase;
    }

    /**
     * Modificar el campo 'valorBase' de un objeto
     *
     * @param String $valorBase valorBase
     *
     * @return no
     *
     */
    public function setValorBase($valorBase)
    {
        $this->valorBase = $valorBase;
    }

    /**
     * Obtener el campo fechaCrea de un objeto
     *
     * @return String fechaCrea fechaCrea
     *
     */
    public function getFechaCrea()
    {
        return $this->fechaCrea;
    }

    /**
     * Modificar el campo 'fechaCrea' de un objeto
     *
     * @param String $fechaCrea fechaCrea
     *
     * @return no
     *
     */
    public function setFechaCrea($fechaCrea)
    {
        $this->fechaCrea = $fechaCrea;
    }

    /**
     * Obtener el campo usucreaId de un objeto
     *
     * @return String usucreaId usucreaId
     *
     */
    public function getUsucreaId()
    {
        return $this->usucreaId;
    }

    /**
     * Modificar el campo 'usucreaId' de un objeto
     *
     * @param String $usucreaId usucreaId
     *
     * @return no
     *
     */
    public function setUsucreaId($usucreaId)
    {
        $this->usucreaId = $usucreaId;
    }

    /**
     * Obtener el campo fechaModif de un objeto
     *
     * @return String fechaModif fechaModif
     *
     */
    public function getFechaModif()
    {
        return $this->fechaModif;
    }

    /**
     * Modificar el campo 'fechaModif' de un objeto
     *
     * @param String $fechaModif fechaModif
     *
     * @return no
     *
     */
    public function setFechaModif($fechaModif)
    {
        $this->fechaModif = $fechaModif;
    }

    /**
     * Obtener el campo usumodifId de un objeto
     *
     * @return String usumodifId usumodifId
     *
     */
    public function getUsumodifId()
    {
        return $this->usumodifId;
    }

    /**
     * Modificar el campo 'usumodifId' de un objeto
     *
     * @param String $usumodifId usumodifId
     *
     * @return no
     *
     */
    public function setUsumodifId($usumodifId)
    {
        $this->usumodifId = $usumodifId;
    }

    /**
     * Obtener el campo estado de un objeto
     *
     * @return String estado estado
     *
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Modificar el campo 'estado' de un objeto
     *
     * @param String $estado estado
     *
     * @return no
     *
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    /**
     * Obtener el campo errorId de un objeto
     *
     * @return String errorId errorId
     *
     */
    public function getErrorId()
    {
        return $this->errorId;
    }

    /**
     * Modificar el campo 'errorId' de un objeto
     *
     * @param String $errorId errorId
     *
     * @return no
     *
     */
    public function setErrorId($errorId)
    {
        $this->errorId = $errorId;
    }

    /**
     * Obtener el campo idExterno de un objeto
     *
     * @return String idExterno idExterno
     *
     */
    public function getIdExterno()
    {
        return $this->idExterno;
    }

    /**
     * Modificar el campo 'idExterno' de un objeto
     *
     * @param String $idExterno idExterno
     *
     * @return no
     *
     */
    public function setIdExterno($idExterno)
    {
        $this->idExterno = $idExterno;
    }

    /**
     * Obtener el campo mandante de un objeto
     *
     * @return String mandante mandante
     *
     */
    public function getMandante()
    {
        return $this->mandante;
    }

    /**
     * Modificar el campo 'mandante' de un objeto
     *
     * @param String $mandante mandante
     *
     * @return no
     *
     */
    public function setMandante($mandante)
    {
        $this->mandante = $mandante;
    }

    /**
     * Obtener el campo version de un objeto
     *
     * @return String version version
     *
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Modificar el campo 'version' de un objeto
     *
     * @param String $version version
     *
     * @return no
     *
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * Obtener el campo apostado de un objeto
     *
     * @return String apostado apostado
     *
     */
    public function getApostado()
    {
        return $this->apostado;
    }

    /**
     * Modificar el campo 'apostado' de un objeto
     *
     * @param String $apostado apostado
     *
     * @return no
     *
     */
    public function setApostado($apostado)
    {
        $this->apostado = $apostado;
    }

    /**
     * Obtener el campo rollowerRequerido de un objeto
     *
     * @return String rollowerRequerido rollowerRequerido
     *
     */
    public function getRollowerRequerido()
    {
        return $this->rollowerRequerido;
    }

    /**
     * Modificar el campo 'rollowerRequerido' de un objeto
     *
     * @param String $rollowerRequerido rollowerRequerido
     *
     * @return no
     *
     */
    public function setRollowerRequerido($rollowerRequerido)
    {
        $this->rollowerRequerido = $rollowerRequerido;
    }

    /**
     * Obtener el campo codigo de un objeto
     *
     * @return String codigo codigo
     *
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Modificar el campo 'codigo' de un objeto
     *
     * @param String $codigo codigo
     *
     * @return no
     *
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    /**
     * Obtener el campo externoId de un objeto
     *
     * @return String externoId externoId
     *
     */
    public function getExternoId()
    {
        return $this->externoId;
    }

    /**
     * Modificar el campo 'externoId' de un objeto
     *
     * @param String $externoId externoId
     *
     * @return no
     *
     */
    public function setExternoId($externoId)
    {
        $this->externoId = $externoId;
    }

/**
     * Obtener el campo premio de un objeto
     *
     * @return String premio premio
     */
    public function getPremio()
    {
        return $this->premio;
    }

    /**
     * Modificar el campo 'premio' de un objeto
     *
     * @param String $premio premio
     *
     * @return no
     */
    public function setPremio($premio)
    {
        $this->premio = $premio;
    }

    /**
     * Obtener el campo observacion de un objeto
     *
     * @return String observacion observacion
     */
    public function getObservacion()
    {
        return $this->observacion;
    }

    /**
     * Modificar el campo 'observacion' de un objeto
     *
     * @param String $observacion observacion
     *
     * @return no
     */
    public function setObservacion($observacion)
    {
        $this->observacion = $observacion;
    }

    /**
     * Obtener el campo teamusuentrega de un objeto
     *
     * @return String teamusuentrega teamusuentrega
     */
    public function getTeamusuentrega()
    {
        return $this->teamusuentrega;
    }

    /**
     * Modificar el campo 'teamusuentrega' de un objeto
     *
     * @param String $teamusuentrega teamusuentrega
     *
     * @return no
     */
    public function setTeamusuentrega($teamusuentrega)
    {
        $this->teamusuentrega = $teamusuentrega;
    }

    /**
     * Obtener el campo direccionusuentrega de un objeto
     *
     * @return String direccionusuentrega direccionusuentrega
     */
    public function getDireccionusuentrega()
    {
        return $this->direccionusuentrega;
    }

    /**
     * Modificar el campo 'direccionusuentrega' de un objeto
     *
     * @param String $direccionusuentrega direccionusuentrega
     *
     * @return no
     */
    public function setDireccionusuentrega($direccionusuentrega)
    {
        $this->direccionusuentrega = $direccionusuentrega;
    }

    /**
     * Obtener el campo provinciausuentrega de un objeto
     *
     * @return String provinciausuentrega provinciausuentrega
     */
    public function getProvinciausuentrega()
    {
        return $this->provinciausuentrega;
    }

    /**
     * Modificar el campo 'provinciausuentrega' de un objeto
     *
     * @param String $provinciausuentrega provinciausuentrega
     *
     * @return no
     */
    public function setProvinciausuentrega($provinciausuentrega)
    {
        $this->provinciausuentrega = $provinciausuentrega;
    }

    /**
     * Obtener el campo ciudadusuentrega de un objeto
     *
     * @return String ciudadusuentrega ciudadusuentrega
     */
    public function getCiudadusuentrega()
    {
        return $this->ciudadusuentrega;
    }

    /**
     * Modificar el campo 'ciudadusuentrega' de un objeto
     *
     * @param String $ciudadusuentrega ciudadusuentrega
     *
     * @return no
     */
    public function setCiudadusuentrega($ciudadusuentrega)
    {
        $this->ciudadusuentrega = $ciudadusuentrega;
    }

    /**
     * Obtener el campo telefonousuentrega de un objeto
     *
     * @return String telefonousuentrega telefonousuentrega
     */
    public function getTelefonousuentrega()
    {
        return $this->telefonousuentrega;
    }

    /**
     * Modificar el campo 'telefonousuentrega' de un objeto
     *
     * @param String $telefonousuentrega telefonousuentrega
     *
     * @return no
     */
    public function setTelefonousuentrega($telefonousuentrega)
    {
        $this->telefonousuentrega = $telefonousuentrega;
    }

    /**
     * Obtener el campo cedulausuentrega de un objeto
     *
     * @return String cedulausuentrega cedulausuentrega
     */
    public function getCedulausuentrega()
    {
        return $this->cedulausuentrega;
    }

    /**
     * Modificar el campo 'cedulausuentrega' de un objeto
     *
     * @param String $cedulausuentrega cedulausuentrega
     *
     * @return no
     */
    public function setCedulausuentrega($cedulausuentrega)
    {
        $this->cedulausuentrega = $cedulausuentrega;
    }

    /**
     * Obtener el campo apellidousuentrega de un objeto
     *
     * @return String apellidousuentrega apellidousuentrega
     */
    public function getApellidousuentrega()
    {
        return $this->apellidousuentrega;
    }

    /**
     * Modificar el campo 'apellidousuentrega' de un objeto
     *
     * @param String $apellidousuentrega apellidousuentrega
     *
     * @return no
     */
    public function setApellidousuentrega($apellidousuentrega)
    {
        $this->apellidousuentrega = $apellidousuentrega;
    }

    /**
     * Obtener el campo nombreusuentrega de un objeto
     *
     * @return String nombreusuentrega nombreusuentrega
     */
    public function getNombreusuentrega()
    {
        return $this->nombreusuentrega;
    }

    /**
     * Modificar el campo 'nombreusuentrega' de un objeto
     *
     * @param String $nombreusuentrega nombreusuentrega
     *
     * @return no
     */
    public function setNombreusuentrega($nombreusuentrega)
    {
        $this->nombreusuentrega = $nombreusuentrega;
    }

    /**
     * Obtener el campo puntoventaentrega de un objeto
     *
     * @return String puntoventaentrega puntoventaentrega
     */
    public function getPuntoventaentrega()
    {
        return $this->puntoventaentrega;
    }

    /**
     * Modificar el campo 'puntoventaentrega' de un objeto
     *
     * @param String $puntoventaentrega puntoventaentrega
     *
     * @return no
     */
    public function setPuntoventaentrega($puntoventaentrega)
    {
        $this->puntoventaentrega = $puntoventaentrega;
    }
    /**
     * Modificar el campo 'externoId' de un objeto
     *
     * @param String $externoId externoId
     *
     * @return no
     *
     */

    /**
     * Obtener el campo usulealtadId de un objeto
     *
     * @return String usulealtadId usulealtadId
     *
     */
    public function getUsulealtadId()
    {
        return $this->usulealtadId;
    }





    /**
     * Realizar una consulta en la tabla de UsuarioLealtad 'UsuarioLealtad'
     * de una manera personalizada
     *
     *
     * @param String $select campos de consulta
     * @param String $sidx columna para ordenar
     * @param String $sord orden los datos asc | desc
     * @param String $start inicio de la consulta
     * @param String $limit limite de la consulta
     * @param String $filters condiciones de la consulta
     * @param boolean $searchOn utilizar los filtros o no
     * @param String $grouping columna para agrupar
     *
     * @return Array resultado de la consulta
     * @throws Exception si los productos no existen
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function getUsuarioLealtadCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$grouping="",$withIdLealtadDetalle="",$withIdLealtadDetalle2="")
    {

        $UsuarioLealtadMySqlDAO = new UsuarioLealtadMySqlDAO();

        $Productos = $UsuarioLealtadMySqlDAO->queryUsuarioLealtadCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$grouping,$withIdLealtadDetalle,$withIdLealtadDetalle2);

        if ($Productos != null && $Productos != "")
        {
            return $Productos;
        }
        else
        {
            throw new Exception("No existe " . get_class($this), "01");
        }

    }


}

?>
