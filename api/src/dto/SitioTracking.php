<?php namespace Backend\dto;
use Backend\mysql\SitioTrackingMySqlDAO;
use Exception;
/** 
* Clase 'SitioTracking'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'SitioTracking'
* 
* Ejemplo de uso: 
* $SitioTracking = new SitioTracking();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class SitioTracking
{

    /**
    * Representación de la columna 'sitiotrackingId' de la tabla 'SitioTracking'
    *
    * @var string
    */
    var $sitiotrackingId;

    /**
    * Representación de la columna 'tabla' de la tabla 'SitioTracking'
    *
    * @var string
    */
    var $tabla;

    /**
    * Representación de la columna 'tipo' de la tabla 'SitioTracking'
    *
    * @var string
    */
    var $tipo;

    /**
    * Representación de la columna 'tablaId' de la tabla 'SitioTracking'
    *
    * @var string
    */
    var $tablaId;

    /**
    * Representación de la columna 'tvalue' de la tabla 'SitioTracking'
    *
    * @var string
    */
    var $tvalue;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'SitioTracking'
    *
    * @var string
    */
    var $usucreaId;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'SitioTracking'
    *
    * @var string
    */
    var $usumodifId;

    /**
    * Representación de la columna 'valueInd' de la tabla 'SitioTracking'
    *
    * @var string
    */
    var $valueInd;

    /**
    * Constructor de clase
    *
    *
    * @param String $sitiotrackingId id del proveedor
    *
    * @return no
    * @throws Exception si el proveedor no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($sitiotrackingId="")
    {
        if ($sitiotrackingId != "") 
        {

            $this->sitiotrackingId = $sitiotrackingId;

            $SitioTrackingMySqlDAO = new SitioTrackingMySqlDAO();

            $SitioTracking = $SitioTrackingMySqlDAO->load($sitiotrackingId);

            if ($SitioTracking != null && $SitioTracking != "") 
            {
                $this->tabla = $SitioTracking->tabla;
                $this->tipo = $SitioTracking->tipo;
                $this->tablaId = $SitioTracking->tablaId;
                $this->tvalue = $SitioTracking->tvalue;
                $this->usucreaId = $SitioTracking->usucreaId;
                $this->usumodifId = $SitioTracking->usumodifId;
                $this->valueInd = $SitioTracking->valueInd;
            }
            else 
            {
                throw new Exception("No existe " . get_class($this), "105");
            }

        }

    }

    /**
     * Obtener el valor de la columna 'tabla' de la tabla 'SitioTracking'
     * @return string
     */
    public function getTabla()
    {
        return $this->tabla;
    }

    /**
     * Definir el valor de la columna 'tabla' de la tabla 'SitioTracking'
     * @param string $tabla
     */
    public function setTabla($tabla)
    {
        $this->tabla = $tabla;
    }

    /**
     * Obtener el valor de la columna 'tipo' de la tabla 'SitioTracking'
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Definir el valor de la columna 'tipo' de la tabla 'SitioTracking'
     * @param string $tipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    /**
     * Obtener el valor de la columna 'tablaId' de la tabla 'SitioTracking'
     * @return string
     */
    public function getTablaId()
    {
        return $this->tablaId;
    }

    /**
     * Definir el valor de la columna 'tablaId' de la tabla 'SitioTracking'
     * @param string $tablaId
     */
    public function setTablaId($tablaId)
    {
        $this->tablaId = $tablaId;
    }

    /**
     * Obtener el valor de la columna 'tvalue' de la tabla 'SitioTracking'
     * @return string
     */
    public function getTvalue()
    {
        return $this->tvalue;
    }

    /**
     * Definir el valor de la columna 'tvalue' de la tabla 'SitioTracking'
     * @param string $tvalue
     */
    public function setTvalue($tvalue)
    {
        $this->tvalue = $tvalue;
    }


    /**
     * Obtener el valor de la columna 'usucrea_id' de la tabla 'SitioTracking'
     * @return string
     */
    public function getUsucreaId()
    {
        return $this->usucreaId;
    }

    /**
     * Definir el valor de la columna 'usucrea_id' de la tabla 'SitioTracking'
     * @param string $usucreaId
     */
    public function setUsucreaId($usucreaId)
    {
        $this->usucreaId = $usucreaId;
    }

    /**
     * Obtener el valor de la columna 'usumodif_id' de la tabla 'SitioTracking'
     * @return string
     */
    public function getUsumodifId()
    {
        return $this->usumodifId;
    }

    /**
     * Definir el valor de la columna 'usumodif_id' de la tabla 'SitioTracking'
     * @param string $usumodifId
     */
    public function setUsumodifId($usumodifId)
    {
        $this->usumodifId = $usumodifId;
    }

    /**
     * Obtener el valor de la columna 'sitiotracking_id' de la tabla 'SitioTracking'
     * @return string
     */
    public function getSitiotrackingId()
    {
        return $this->sitiotrackingId;
    }



    /**
    * Realizar una consulta en la tabla de proveedores 'SitioTracking'
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
    * @throws Exception si los proveedores no existen
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getSitioTrackingesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$joinUsuarios = false)
    {

        $SitioTrackingMySqlDAO = new SitioTrackingMySqlDAO();

        $sitio_trackinges = $SitioTrackingMySqlDAO->querySitioTrackingesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$joinUsuarios);

        if ($sitio_trackinges != null && $sitio_trackinges != "") 
        {
            return $sitio_trackinges;
        } 
        else 
        {
            throw new Exception("No existe " . get_class($this), "105");
        }

    }


  
}
?>
