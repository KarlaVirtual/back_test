<?php
namespace Backend\dto;
use Backend\mysql\UsuarioAutomationMySqlDAO;
use Exception;
/** 
* Clase 'UsuarioAutomation'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'UsuarioAutomation'
* 
* Ejemplo de uso: 
* $UsuarioAutomation = new UsuarioAutomation();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class UsuarioAutomation
{

    /**
    * Representación de la columna 'usuautomationId' de la tabla 'UsuarioAutomation'
    *
    * @var string
    */
    var $usuautomationId;

    /**
    * Representación de la columna 'usuarioId' de la tabla 'UsuarioAutomation'
    *
    * @var string
    */
    var $usuarioId;

    /**
    * Representación de la columna 'tipo' de la tabla 'UsuarioAutomation'
    *
    * @var string
    */
    var $tipo;

    /**
    * Representación de la columna 'valor' de la tabla 'UsuarioAutomation'
    *
    * @var string
    */
    var $valor;

    /**
    * Representación de la columna 'fechaCrea' de la tabla 'UsuarioAutomation'
    *
    * @var string
    */
    var $fechaCrea;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'UsuarioAutomation'
    *
    * @var string
    */
    var $usucreaId;

    /**
    * Representación de la columna 'fechaModif' de la tabla 'UsuarioAutomation'
    *
    * @var string
    */
    var $fechaModif;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'UsuarioAutomation'
    *
    * @var string
    */
    var $usumodifId;

    /**
    * Representación de la columna 'estado' de la tabla 'UsuarioAutomation'
    *
    * @var string
    */
    var $estado;

    /**
    * Representación de la columna 'automationId' de la tabla 'UsuarioAutomation'
    *
    * @var string
    */
    var $automationId;

    /**
     * Representación de la columna 'nivel' de la tabla 'UsuarioAutomation'
     *
     * @var string
     */
    var $nivel;

    /**
     * Representación de la columna 'observacion' de la tabla 'UsuarioAutomation'
     *
     * @var string
     */
    var $observacion;

    /**
     * Representación de la columna 'fecha_expiracion' de la tabla 'UsuarioAutomation'
     *
     * @var string
     */
    var $usuaccionId;

    /**
     * Representación de la columna 'fecha_inicio' de la tabla 'UsuarioAutomation'
     *
     * @var string
     */
    var $fechaAccion;

    /**
     * Representación de la columna 'tipo_tiempo' de la tabla 'UsuarioAutomation'
     *
     * @var string
     */
    var $externoId;
    

    /**
    * Constructor de clase
    *
    *
    * @param String $usuautomationId usuautomationId
    * @param String $usuarioId usuarioId
    *
    * @return no
    * @throws Exception si UsuarioAutomation no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($usuautomationId="", $usuarioId="")
    {
        if ($usuautomationId != "") 
        {

            $this->usuautomationId = $usuautomationId;

            $UsuarioAutomationMySqlDAO = new UsuarioAutomationMySqlDAO();

            $UsuarioAutomation = $UsuarioAutomationMySqlDAO->load($usuautomationId);

            if ($UsuarioAutomation != null && $UsuarioAutomation != "") 
            {
                $this->usuautomationId = $UsuarioAutomation->usuautomationId;
                $this->usuarioId = $UsuarioAutomation->usuarioId;
                $this->tipo = $UsuarioAutomation->tipo;
                $this->valor = $UsuarioAutomation->valor;
                $this->fechaCrea = $UsuarioAutomation->fechaCrea;
                $this->usucreaId = $UsuarioAutomation->usucreaId;
                $this->fechaModif = $UsuarioAutomation->fechaModif;
                $this->usumodifId = $UsuarioAutomation->usumodifId;
                $this->estado = $UsuarioAutomation->estado;
                $this->automationId = $UsuarioAutomation->automationId;
                $this->nivel = $UsuarioAutomation->nivel;
                $this->observacion = $UsuarioAutomation->observacion;
                $this->usuaccionId = $UsuarioAutomation->usuaccionId;
                $this->externoId = $UsuarioAutomation->externoId;

            }
            else 
            {
                throw new Exception("No existe " . get_class($this), "94");
            }
        }

    }

    /**
    * Realizar una consulta en la tabla de UsuarioAutomations 'UsuarioAutomations'
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
    *
    * @return Array resultado de la consulta
    * @throws Exception si los productos no existen
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getUsuarioAutomationsCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $UsuarioAutomationMySqlDAO = new UsuarioAutomationMySqlDAO();

        $Productos = $UsuarioAutomationMySqlDAO->queryUsuarioAutomationsCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($Productos != null && $Productos != "") 
        {
            return $Productos;
        } 
        else 
        {
            throw new Exception("No existe " . get_class($this), "94");
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
 * Obtiene la fecha de creación.
 *
 * @return string
 */
public function getFechaCrea()
{
    return $this->fechaCrea;
}

/**
 * Obtiene el ID del usuario que creó.
 *
 * @return string
 */
public function getUsucreaId()
{
    return $this->usucreaId;
}

/**
 * Establece el ID del usuario que creó.
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
 * Obtiene el ID de automatización.
 *
 * @return string
 */
public function getUsuautomationId()
{
    return $this->automationId;
}

/**
 * Establece el ID de automatización.
 *
 * @param string $automationId
 */
public function setUsuautomationId($automationId)
{
    $this->automationId = $automationId;
}

/**
 * Obtiene el nivel.
 *
 * @return string
 */
public function getNivel()
{
    return $this->nivel;
}

/**
 * Establece el nivel.
 *
 * @param string $nivel
 */
public function setNivel($nivel)
{
    $this->nivel = $nivel;
}

/**
 * Obtiene la observación.
 *
 * @return string
 */
public function getObservacion()
{
    return $this->observacion;
}

/**
 * Establece la observación.
 *
 * @param string $observacion
 */
public function setObservacion($observacion)
{
    $this->observacion = $observacion;
}

/**
 * Obtiene el ID de la acción del usuario.
 *
 * @return string
 */
public function getUsuaccionId()
{
    return $this->usuaccionId;
}

/**
 * Establece el ID de la acción del usuario.
 *
 * @param string $usuaccionId
 */
public function setUsuaccionId($usuaccionId)
{
    $this->usuaccionId = $usuaccionId;
}

/**
 * Obtiene la fecha de la acción.
 *
 * @return string
 */
public function getFechaAccion()
{
    return $this->fechaAccion;
}

/**
 * Establece la fecha de la acción.
 *
 * @param string $fechaAccion
 */
public function setFechaAccion($fechaAccion)
{
    $this->fechaAccion = $fechaAccion;
}

/**
 * Obtiene el ID externo.
 *
 * @return string
 */
public function getExternoId()
{
    return $this->externoId;
}

/**
 * Establece el ID externo.
 *
 * @param string $externoId
 */
public function setExternoId($externoId)
{
    $this->externoId = $externoId;
}

/**
 * Obtiene el ID de automatización del usuario.
 *
 * @return string
 */
public function getUsuusuautomationId()
{
    return $this->usuautomationId;
}

/**
 * Obtiene el ID de automatización.
 *
 * @return string
 */
public function getAutomationId()
{
    return $this->automationId;
}

/**
 * Establece el ID de automatización.
 *
 * @param string $automationId
 */
public function setAutomationId($automationId)
{
    $this->automationId = $automationId;
}
}

?>
