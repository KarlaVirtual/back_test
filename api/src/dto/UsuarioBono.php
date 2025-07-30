<?php namespace Backend\dto;

use Backend\mysql\AuditoriaGeneralMySqlDAO;
use Backend\mysql\UsuarioBonoDetalleMySqlDAO;
use Backend\mysql\UsuarioBonoMySqlDAO;
use Backend\sql\Transaction;
use \CurlWrapper;
use Exception;

/**
 * Clase 'UsuarioBono'
 *
 * Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
 * para la tabla 'UsuarioBono'
 *
 * Ejemplo de uso:
 * $UsuarioBono = new UsuarioBono();
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class UsuarioBono
{

    /**
     * Representación de la columna 'usubonoId' de la tabla 'UsuarioBono'
     *
     * @var string
     */
    var $usubonoId;

    /**
     * Representación de la columna 'usuarioId' de la tabla 'UsuarioBono'
     *
     * @var string
     */
    var $usuarioId;

    /**
     * Representación de la columna 'bonoId' de la tabla 'UsuarioBono'
     *
     * @var string
     */
    var $bonoId;

    /**
     * Representación de la columna 'valor' de la tabla 'UsuarioBono'
     *
     * @var string
     */
    var $valor;

    /**
     * Representación de la columna 'valorBono' de la tabla 'UsuarioBono'
     *
     * @var string
     */
    var $valorBono;

    /**
     * Representación de la columna 'valorBase' de la tabla 'UsuarioBono'
     *
     * @var string
     */
    var $valorBase;

    /**
     * Representación de la columna 'fechaCrea' de la tabla 'UsuarioBono'
     *
     * @var string
     */
    var $fechaCrea;

    /**
     * Representación de la columna 'usucreaId' de la tabla 'UsuarioBono'
     *
     * @var string
     */
    var $usucreaId;

    /**
     * Representación de la columna 'fechaModif' de la tabla 'UsuarioBono'
     *
     * @var string
     */
    var $fechaModif;

    /**
     * Representación de la columna 'usumodifId' de la tabla 'UsuarioBono'
     *
     * @var string
     */
    var $usumodifId;

    /**
     * Representación de la columna 'estado' de la tabla 'UsuarioBono'
     *
     * @var string
     */
    var $estado;

    /**
     * Representación de la columna 'errorId' de la tabla 'UsuarioBono'
     *
     * @var string
     */
    var $errorId;

    /**
     * Representación de la columna 'idExterno' de la tabla 'UsuarioBono'
     *
     * @var string
     */
    var $idExterno;

    /**
     * Representación de la columna 'mandante' de la tabla 'UsuarioBono'
     *
     * @var string
     */
    var $mandante;

    /**
     * Representación de la columna 'version' de la tabla 'UsuarioBono'
     *
     * @var string
     */
    var $version;

    /**
     * Representación de la columna 'apostado' de la tabla 'UsuarioBono'
     *
     * @var string
     */
    var $apostado;

    /**
     * Representación de la columna 'rollowerRequerido' de la tabla 'UsuarioBono'
     *
     * @var string
     */
    var $rollowerRequerido;

    /**
     * Representación de la columna 'codigo' de la tabla 'UsuarioBono'
     *
     * @var string
     */
    var $codigo;

    /**
     * Representación de la columna 'externoId' de la tabla 'UsuarioBono'
     *
     * @var string
     */
    var $externoId;

    /**
    * Representación de la columna 'premio' de la tabla 'UsuarioBono'
    *
    * @var string
    */
    var $premio;

    /**
    * Representación de la columna 'usuidReferido' de la tabla 'UsuarioBono'
    *
    * @var string
    */
    var $usuidReferido;

    /**
    * Representación de la columna 'externoBono' de la tabla 'UsuarioBono'
    *
    * @var string
    */
    var $externoBono;

    /**
    * Representación de la columna 'fechaExpiracion' de la tabla 'UsuarioBono'
    *
    * @var string
    */
    var $fechaExpiracion;


    /**
     * Constructor de clase
     *
     *
     * @param String $usubonoId usubonoId
     * @param String $usuarioId usuarioId
     *
     * @return no
     * @throws Exception si UsuarioBono no existe
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function __construct($usubonoId = "", $usuarioId = "", $bonoId = "", $externoId = "", $estado = "")
    {
        if ($usubonoId != "") {

            $this->usubonoId = $usubonoId;

            $UsuarioBonoMySqlDAO = new UsuarioBonoMySqlDAO();

            $UsuarioBono = $UsuarioBonoMySqlDAO->load($usubonoId);

            if ($UsuarioBono != null && $UsuarioBono != "") {
                $this->usubonoId = $UsuarioBono->usubonoId;
                $this->usuarioId = $UsuarioBono->usuarioId;
                $this->bonoId = $UsuarioBono->bonoId;
                $this->valor = $UsuarioBono->valor;
                $this->valorBono = $UsuarioBono->valorBono;
                $this->valorBase = $UsuarioBono->valorBase;
                $this->fechaCrea = $UsuarioBono->fechaCrea;
                $this->usucreaId = $UsuarioBono->usucreaId;
                $this->fechaModif = $UsuarioBono->fechaModif;
                $this->usumodifId = $UsuarioBono->usumodifId;
                $this->estado = $UsuarioBono->estado;
                $this->errorId = $UsuarioBono->errorId;
                $this->idExterno = $UsuarioBono->idExterno;
                $this->mandante = $UsuarioBono->mandante;
                $this->version = $UsuarioBono->version;
                $this->apostado = $UsuarioBono->apostado;
                $this->rollowerRequerido = $UsuarioBono->rollowerRequerido;
                $this->codigo = $UsuarioBono->codigo;
                $this->externoId = $UsuarioBono->externoId;
                $this->premio = $UsuarioBono->premio;
                $this->fechaExpiracion = $UsuarioBono->fechaExpiracion;
                $this->usuidReferido = $UsuarioBono->usuidReferido;
                $this->externoBono = $UsuarioBono->externoBono;

            } else {
                throw new Exception("No existe " . get_class($this), "33");
            }
        } elseif ($usuarioId != "" && $bonoId != "") {
            $UsuarioBonoMySqlDAO = new UsuarioBonoMySqlDAO();

            $UsuarioBono = $UsuarioBonoMySqlDAO->queryByUsuarioIdAndBonoId($usuarioId, $bonoId);
            $UsuarioBono = $UsuarioBono[0];

            if ($UsuarioBono != null && $UsuarioBono != "") {
                $this->usubonoId = $UsuarioBono->usubonoId;
                $this->usuarioId = $UsuarioBono->usuarioId;
                $this->bonoId = $UsuarioBono->bonoId;
                $this->valor = $UsuarioBono->valor;
                $this->valorBono = $UsuarioBono->valorBono;
                $this->valorBase = $UsuarioBono->valorBase;
                $this->fechaCrea = $UsuarioBono->fechaCrea;
                $this->usucreaId = $UsuarioBono->usucreaId;
                $this->fechaModif = $UsuarioBono->fechaModif;
                $this->usumodifId = $UsuarioBono->usumodifId;
                $this->estado = $UsuarioBono->estado;
                $this->errorId = $UsuarioBono->errorId;
                $this->idExterno = $UsuarioBono->idExterno;
                $this->mandante = $UsuarioBono->mandante;
                $this->version = $UsuarioBono->version;
                $this->apostado = $UsuarioBono->apostado;
                $this->rollowerRequerido = $UsuarioBono->rollowerRequerido;
                $this->codigo = $UsuarioBono->codigo;
                $this->externoId = $UsuarioBono->externoId;
                $this->premio = $UsuarioBono->premio;
                $this->fechaExpiracion = $UsuarioBono->fechaExpiracion;
                $this->usuidReferido = $UsuarioBono->usuidReferido;
                $this->externoBono = $UsuarioBono->externoBono;
            } else {
                throw new Exception("No existe " . get_class($this), "33");
            }

        } elseif ($usuarioId != "" && $externoId != "" && $estado != "") {

            $UsuarioBonoMySqlDAO = new UsuarioBonoMySqlDAO();

            $UsuarioBono = $UsuarioBonoMySqlDAO->queryByUsuarioIdAndExternoIdAndEstado($usuarioId, $externoId, $estado);
            $UsuarioBono = $UsuarioBono[0];

            if ($UsuarioBono != null && $UsuarioBono != "") {

                $this->usubonoId = $UsuarioBono->usubonoId;
                $this->usuarioId = $UsuarioBono->usuarioId;
                $this->bonoId = $UsuarioBono->bonoId;
                $this->valor = $UsuarioBono->valor;
                $this->valorBono = $UsuarioBono->valorBono;
                $this->valorBase = $UsuarioBono->valorBase;
                $this->fechaCrea = $UsuarioBono->fechaCrea;
                $this->usucreaId = $UsuarioBono->usucreaId;
                $this->fechaModif = $UsuarioBono->fechaModif;
                $this->usumodifId = $UsuarioBono->usumodifId;
                $this->estado = $UsuarioBono->estado;
                $this->errorId = $UsuarioBono->errorId;
                $this->idExterno = $UsuarioBono->idExterno;
                $this->mandante = $UsuarioBono->mandante;
                $this->version = $UsuarioBono->version;
                $this->apostado = $UsuarioBono->apostado;
                $this->rollowerRequerido = $UsuarioBono->rollowerRequerido;
                $this->codigo = $UsuarioBono->codigo;
                $this->externoId = $UsuarioBono->externoId;
                $this->premio = $UsuarioBono->premio;
                $this->fechaExpiracion = $UsuarioBono->fechaExpiracion;
                $this->externoBono = $UsuarioBono->externoBono;
            } else {
                throw new Exception("No existe " . get_class($this), "33");
            }
        }
        /* elseif ( $usuarioId != "")
         {

             $UsuarioBonoMySqlDAO = new UsuarioBonoMySqlDAO();

             $UsuarioBono = $UsuarioBonoMySqlDAO->queryByExternoIdAndProveedorId($usuarioId);
             $UsuarioBono = $UsuarioBono[0];

             if ($UsuarioBono != null && $UsuarioBono != "")
             {

                 $this->usubonoId = $UsuarioBono->usubonoId;
                 $this->usuarioId = $UsuarioBono->usuarioId;
                 $this->bonoId = $UsuarioBono->bonoId;
                 $this->valor = $UsuarioBono->valor;
                 $this->valorBono = $UsuarioBono->valorBono;
                 $this->valorBase = $UsuarioBono->valorBase;
                 $this->fechaCrea = $UsuarioBono->fechaCrea;
                 $this->usucreaId = $UsuarioBono->usucreaId;
                 $this->fechaModif = $UsuarioBono->fechaModif;
                 $this->usumodifId = $UsuarioBono->usumodifId;
                 $this->estado = $UsuarioBono->estado;
                 $this->errorId = $UsuarioBono->errorId;
                 $this->idExterno = $UsuarioBono->idExterno;
                 $this->mandante = $UsuarioBono->mandante;
                 $this->version = $UsuarioBono->version;
                 $this->apostado = $UsuarioBono->apostado;
                 $this->rollowerRequerido = $UsuarioBono->rollowerRequerido;
                 $this->codigo = $UsuarioBono->codigo;
                 $this->externoId = $UsuarioBono->externoId;
                 $this->premio = $UsuarioBono->premio;
             }
             else
             {
                 throw new Exception("No existe " . get_class($this), "33");
             }
         }*/

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
     * Obtener el campo bonoId de un objeto
     *
     * @return String bonoId bonoId
     *
     */
    public function getBonoId()
    {
        return $this->bonoId;
    }

    /**
     * Modificar el campo 'bonoId' de un objeto
     *
     * @param String $bonoId bonoId
     *
     * @return no
     *
     */
    public function setBonoId($bonoId)
    {
        $this->bonoId = $bonoId;
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
     * Obtener el campo valorBono de un objeto
     *
     * @return String valorBono valorBono
     *
     */
    public function getValorBono()
    {
        return $this->valorBono;
    }

    /**
     * Modificar el campo 'valorBono' de un objeto
     *
     * @param String $valorBono valorBono
     *
     * @return no
     *
     */
    public function setValorBono($valorBono)
    {
        $this->valorBono = $valorBono;
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
     * Obtener el campo usubonoId de un objeto
     *
     * @return String usubonoId usubonoId
     *
     */
    public function getUsubonoId()
    {
        return $this->usubonoId;
    }

    /**
     * @return mixed
     */
    public function getUsuidReferido()
    {
        return $this->usuidReferido;
    }

    /**
     * @param mixed $usuidReferido
     */
    public function setUsuidReferido($usuidReferido)
    {
        $this->usuidReferido = $usuidReferido;
    }


    /**
     * Obtener el campo fechaCrea de un objeto
     *
     * @return String fechaCrea fechaCrea
     *
     */
    public function getExternoBono()
    {
        return $this->externoBono;
    }

    /**
     * Modificar el campo 'fechaCrea' de un objeto
     *
     * @param String $fechaCrea fechaCrea
     *
     * @return no
     *
     */
    public function setExternoBono($externoBono)
    {
        $this->externoBono = $externoBono;
    }


/**
 * Modificar el campo 'fechaExpiracion' de un objeto
 *
 * @param String $fechaExpiracion fechaExpiracion
 *
 * @return no
 */
public function setFechaExpiracion($fechaExpiracion){
    $this->fechaExpiracion = $fechaExpiracion;
}

/**
 * Obtener el campo fechaExpiracion de un objeto
 *
 * @return String fechaExpiracion fechaExpiracion
 */
public function getFechaExpiracion(){
    return $this->fechaExpiracion;
}


    /**
     * Realizar una consulta en la tabla de UsuarioBono 'UsuarioBono'
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
    public function getUsuarioBonosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping = "", $withIdBonoDetalle = "", $withIdBonoDetalle2 = "", $onlyCount = false)
    {

        $UsuarioBonoMySqlDAO = new UsuarioBonoMySqlDAO();

        $Productos = $UsuarioBonoMySqlDAO->queryUsuarioBonosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping, $withIdBonoDetalle, $withIdBonoDetalle2, $onlyCount);

        if ($Productos != null && $Productos != "") {
            return $Productos;
        } else {
            throw new Exception("No existe " . get_class($this), "01");
        }

    }

    /**
     * Realizar una consulta en la tabla de UsuarioBono 'UsuarioBono' devuelve los bonos que no esten ligados a altenar
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
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function getUsuarioBonosNoAltenarCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping = "", $withIdBonoDetalle = "", $withIdBonoDetalle2 = "", $onlyCount = false)
    {
        $UsuarioBonoMySqlDAO = new UsuarioBonoMySqlDAO();

        $Productos = $UsuarioBonoMySqlDAO->queryUsuarioBonosNoAltenarCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping, $withIdBonoDetalle, $withIdBonoDetalle2, $onlyCount);

        return $Productos;

    }



    /**
     * Obtiene una lista personalizada de bonos de usuario.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Índice de ordenación.
     * @param string $sord Orden de clasificación (ASC o DESC).
     * @param int $start Índice de inicio para la paginación.
     * @param int $limit Límite de registros para la paginación.
     * @param array $filters Filtros aplicados a la consulta.
     * @param bool $searchOn Indica si la búsqueda está activada.
     * @param string $grouping (Opcional) Agrupamiento de resultados.
     * @param string $withIdBonoDetalle (Opcional) Detalle del bono.
     * @param string $withIdBonoDetalle2 (Opcional) Segundo detalle del bono.
     * @return array Lista de bonos de usuario.
     * @throws Exception Si no existen bonos de usuario.
     */
    public function getUsuarioBonosCustom3($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping = "", $withIdBonoDetalle = "", $withIdBonoDetalle2 = "")
    {

        $UsuarioBonoMySqlDAO = new UsuarioBonoMySqlDAO();

        $Productos = $UsuarioBonoMySqlDAO->queryUsuarioBonosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping, $withIdBonoDetalle, $withIdBonoDetalle2);

        if ($Productos != null && $Productos != "") {
            return $Productos;
        } else {
            throw new Exception("No existe " . get_class($this), "01");
        }

    }

    /**
     * Obtiene un conjunto personalizado de datos de UsuarioBono.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Índice de ordenación.
     * @param string $sord Orden de clasificación (ascendente o descendente).
     * @param int $start Índice de inicio para la paginación.
     * @param int $limit Límite de registros a obtener.
     * @param string $filters Filtros aplicados a la consulta.
     * @param bool $searchOn Indica si la búsqueda está activada.
     * @param string $grouping (Opcional) Agrupamiento de los resultados.
     *
     * @return array Conjunto de datos de UsuarioBono.
     * @throws Exception Si no se encuentran resultados.
     */
    public function getUsuarioBonoCustom2($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping = "")
    {

        $UsuarioBonoMySqlDAO = new UsuarioBonoMySqlDAO();

        $Productos = $UsuarioBonoMySqlDAO->queryUsuarioBonoCustom2($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping);

        if ($Productos != null && $Productos != "") {
            return $Productos;
        } else {
            throw new Exception("No existe " . get_class($this), "01");
        }


    }


    /**
     * Obtiene los bonos de usuarios asociados a un bono específico.
     *
     * @param string $bono_id El ID del bono para el cual se desean obtener los usuarios bonos.
     * @return array|null Un array de usuarios bonos si existen, de lo contrario lanza una excepción.
     * @throws Exception Si no existen usuarios bonos asociados al bono proporcionado.
     */
    public function getUsuariosBonos($bono_id){
        if ($bono_id != "") {
            $UsuarioBonoMySqlDAO = new UsuarioBonoMySqlDAO();
            $UsuariosBonos = $UsuarioBonoMySqlDAO->queryByBonoIdAndEstado($bono_id, 'R');
            if ($UsuariosBonos != null && $UsuariosBonos != "") {
                return $UsuariosBonos;
            } else {
                throw new Exception("No existe " . get_class($this), "33");
            }
        }
    }

    /**
     * Realizar una consulta en la tabla de UsuarioBono 'UsuarioBono' y la Api de altenar para verificar si el usuario
     * tiene algún bono con rollover activo desde Altenar, además de que verifica si el bono está marcado con el condicional
     * 'REMOVEBONUSALTENAR', el cual permite eliminar el bono al crear notas de retiros.
     *
     * @param String $usuarioID Id del usuario a verificar
     * @param String $mandante Partner del usuario a verificar
     *
     * @return boolean Verdadero si tiene un bono activo desde Altenar con rollover y marcado con el condicional 'REMOVEBONUSALTENAR', falso en otro caso
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function existeBonoActivoAltenar($usuarioID, $mandante)
    {
        $Usuario = new Usuario($usuarioID);
        $Subproveedor = new Subproveedor("", "ITN");
        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Subproveedor->subproveedorId, $mandante, $Usuario->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());
        $urlAltenar = $Credentials->URL2;
        $walletCode = $Credentials->WALLET_CODE;

        $ConfigurationEnvironment = new ConfigurationEnvironment();

        $sqlUserBonus = "SELECT
                            bono_detalle.tipo,
                            bono_detalle.valor
                        FROM
                            usuario_bono
                        INNER JOIN bono_interno ON
                            (bono_interno.bono_id = usuario_bono.bono_id)
                        INNER JOIN bono_detalle bono_detalle2 ON
                            (bono_detalle2.bono_id = bono_interno.bono_id AND bono_detalle2.tipo IN ('REMOVEBONUSALTENAR'))
                        INNER JOIN bono_detalle ON
                            (bono_detalle.bono_id = bono_interno.bono_id AND bono_detalle.tipo IN ( 'BONUSPLANIDALTENAR'))
                        WHERE
                            usuario_bono.usuario_id = $1
                            AND usuario_bono.estado = 'A'
                            AND bono_interno.mandante = $2
                            AND bono_interno.tipo in (2, 3)
                            AND bono_detalle2.valor != ''
                            AND bono_detalle2.valor != '0' LIMIT 2";

        $sqlUserBonus = str_replace(['$1', '$2'], [$usuarioID, $mandante], $sqlUserBonus);

        $BonoInterno = new BonoInterno();
        $queryUserBonus = json_encode($BonoInterno->execQuery('', $sqlUserBonus));
        $queryUserBonus = json_decode($queryUserBonus, true);

        $userID = $usuarioID;
        $UsuarioPerfil = new UsuarioPerfil($userID);
        $Mandante = new Mandante($mandante);

        if (($UsuarioPerfil->perfilId === 'USUONLINE' && $userID > 73758) ||
            in_array($userID, [70363, 72098, 67393, 73497, 57196])
        ) {
            $userID .= 'U';
        } else if (($UsuarioPerfil->perfilId !== 'USUONLINE' && $userID > 140040) ||
            in_array($userID, [77491])
        ) {
            $userID .= 'P';
        } else {
            if ($ConfigurationEnvironment->isDevelopment()) $userID .= 'U';
        }

        $payload = [
            'ExtUserId' => $userID,
            'WalletCode' => $walletCode
        ];

        $CurlWrapper = new CurlWrapper("{$urlAltenar}/api/Bonus/GetClientBonusInfo/json");
        $CurlWrapper->setOptionsArray([
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 300,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json'
            ],
        ]);
        $responsecurl = $CurlWrapper->execute();
        $response = json_decode($responsecurl, true);

        $response = oldCount($response) > 0 ? $response['GetClientBonusInfoMessageResult'] : [];

        return  (int)$response['BonusPlanId']  === (int)$queryUserBonus[0]['bono_detalle.valor'] && $response['RolloverRequired'] > 0;
    }

    /**
     * Consume la api de Altenar para cancelar un bono activo del usuario específico
     *
     *
     * @param String $usuarioID Id del usuario a verificar
     * @param String $mandante Partner del usuario a verificar
     *
     * @return no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function cancelarBonoActivoAltenar($usuarioID, $mandante)
    {
        $Usuario = new Usuario($usuarioID);
        $Subproveedor = new Subproveedor("", "ITN");
        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Subproveedor->subproveedorId, $mandante, $Usuario->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());
        $urlAltenar = $Credentials->URL2;
        $walletCode = $Credentials->WALLET_CODE;

        $ConfigurationEnvironment = new ConfigurationEnvironment();

        $Mandante = new Mandante($mandante);

        $userID = $usuarioID;
        $UsuarioPerfil = new UsuarioPerfil($userID);

        if (($UsuarioPerfil->perfilId === 'USUONLINE' && $userID > 73758) ||
            in_array($userID, [70363, 72098, 67393, 73497, 57196])
        ) {
            $userID .= 'U';
        } else if (($UsuarioPerfil->perfilId !== 'USUONLINE' && $userID > 140040) ||
            in_array($userID, [77491])
        ) {
            $userID .= 'P';
        } else {
            if ($ConfigurationEnvironment->isDevelopment()) $userID .= 'U';
        }

        $payload = [
            'ExtUserId' => $userID,
            'WalletCode' => $walletCode
        ];

        $CurlWrapper = new CurlWrapper("{$urlAltenar}/api/Bonus/CancelClientBonus/json");
        $CurlWrapper->setOptionsArray([
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 300,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json'
            ],
        ]);

        $response = json_decode($CurlWrapper->execute(), true);

        // Se verifica si se procesó la cancelación del bono
        if (isset($response['CancelClientBonusMessageResult']['BonusStatus']) &&
            $response['CancelClientBonusMessageResult']['BonusStatus'] === 'Cancelled') {

            // Logs sobre el estado de la cancelación
            $Transaction = new Transaction();
            $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO($Transaction);

            $AuditoriaGeneral = new AuditoriaGeneral();
            $AuditoriaGeneral->valorAntes = "A"; //Estado del bono
            $AuditoriaGeneral->valorDespues = "I"; //Estado del bono
            $AuditoriaGeneral->observacion = "Cancelación exitosa del bono por parte de altenar al generar una nota de retiro"; //Mensaje de cancelación del bono
            $AuditoriaGeneral->estado = 'A';
            $AuditoriaGeneral->usuarioId = $usuarioID;
            $AuditoriaGeneral->usuariosolicitaId = $usuarioID;
            $AuditoriaGeneral->usuarioaprobarId = $usuarioID;
            $AuditoriaGeneral->usucreaId = $usuarioID;
            $AuditoriaGeneral->usumodifId = '0';
            $AuditoriaGeneral->tipo = 'CANCELAR BONO ALTENAR RETIRO'; //Tipo
            $AuditoriaGeneral->data = json_encode($response); //Objeto con la respuesta de altenar sobre la cancelación del bono

            $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);
            $Transaction->commit();

        }

    }

}

?>
