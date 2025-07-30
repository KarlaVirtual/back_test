<?php namespace Backend\dto;
use Backend\mysql\CompetenciaPuntosMySqlDAO;
use Exception;
/** 
* Clase 'CompetenciaPuntos'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'CompetenciaPuntos'
* 
* Ejemplo de uso: 
* $CompetenciaPuntos = new CompetenciaPuntos();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class CompetenciaPuntos
{

    /**
    * Representación de la columna 'comppuntoId' de la tabla 'CompetenciaPuntos'
    *
    * @var string
    */
    var $comppuntoId;

    /**
    * Representación de la columna 'competenciaId' de la tabla 'CompetenciaPuntos'
    *
    * @var string
    */
    var $competenciaId;

    /**
    * Representación de la columna 'nombre' de la tabla 'CompetenciaPuntos'
    *
    * @var string
    */
    var $nombre;

    /**
    * Representación de la columna 'descripcion' de la tabla 'CompetenciaPuntos'
    *
    * @var string
    */
    var $descripcion;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'CompetenciaPuntos'
    *
    * @var string
    */
    var $usucreaId;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'CompetenciaPuntos'
    *
    * @var string
    */
    var $usumodifId;

    /**
    * Representación de la columna 'fechaCrea' de la tabla 'CompetenciaPuntos'
    *
    * @var string
    */
    var $fechaCrea;

    /**
    * Representación de la columna 'fechaModif' de la tabla 'CompetenciaPuntos'
    *
    * @var string
    */
    var $fechaModif;

    /**
    * Representación de la columna 'longitud' de la tabla 'CompetenciaPuntos'
    *
    * @var string
    */
    var $longitud;

    /**
    * Representación de la columna 'latitud' de la tabla 'CompetenciaPuntos'
    *
    * @var string
    */
    var $latitud;

    /**
    * Representación de la columna 'direccion' de la tabla 'CompetenciaPuntos'
    *
    * @var string
    */
    var $direccion;

    /**
    * Representación de la columna 'ciudadId' de la tabla 'CompetenciaPuntos'
    *
    * @var string
    */
    var $ciudadId;

    /**
    * Representación de la columna 'estado' de la tabla 'CompetenciaPuntos'
    *
    * @var string
    */
    var $estado;


    /**
    * Constructor de clase
    *
    *
    * @param String $comppuntoId comppuntoId
    *
    * @return no
    * @throws Exception si CompetenciaPuntos no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($comppuntoId = '')
    {

        if ($comppuntoId != "") 
        {

            $CompetenciaPuntosMySqlDAO = new CompetenciaPuntosMySqlDAO();

            $CompetenciaPuntos = $CompetenciaPuntosMySqlDAO->load($comppuntoId);

            if ($CompetenciaPuntos != null && $CompetenciaPuntos != "") 
            {
                $this->comppuntoId = $CompetenciaPuntos->comppuntoId;
                $this->competenciaId = $CompetenciaPuntos->competenciaId;
                $this->nombre = $CompetenciaPuntos->nombre;
                $this->descripcion = $CompetenciaPuntos->descripcion;
                $this->usucreaId = $CompetenciaPuntos->usucreaId;
                $this->usumodifId = $CompetenciaPuntos->usumodifId;
                $this->longitud = $CompetenciaPuntos->longitud;
                $this->estado = $CompetenciaPuntos->estado;
                $this->latitud = $CompetenciaPuntos->latitud;
                $this->direccion = $CompetenciaPuntos->direccion;
                $this->ciudadId = $CompetenciaPuntos->ciudadId;
            } 
            else 
            {
                throw new Exception("No existe " . get_class($this), "46");
            }


        }
    }







    /**
     * Obtener el campo comppuntoId de un objeto
     *
     * @return String comppuntoId comppuntoId
     * 
     */
    public function getCompupuntoId()
    {
        return $this->comppuntoId;
    }

    /**
     * Obtener el campo competenciaId de un objeto
     *
     * @return String competenciaId competenciaId
     * 
     */
    public function getCompetenciaId()
    {
        return $this->competenciaId;
    }

    /**
     * Modificar el campo 'competenciaId' de un objeto
     *
     * @param String $competenciaId competenciaId
     *
     * @return no
     *
     */
    public function setCompetenciaId($competenciaId)
    {
        $this->competenciaId = $competenciaId;
    }

    /**
     * Obtener el campo nombre de un objeto
     *
     * @return String nombre nombre
     * 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Modificar el campo 'nombre' de un objeto
     *
     * @param String $nombre nombre
     *
     * @return no
     *
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     * Obtener el campo descripcion de un objeto
     *
     * @return String descripcion descripcion
     * 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Modificar el campo 'descripcion' de un objeto
     *
     * @param String $descripcion descripcion
     *
     * @return no
     *
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
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
     * Obtener el campo longitud de un objeto
     *
     * @return String longitud longitud
     * 
     */
    public function getLongitud()
    {
        return $this->longitud;
    }

    /**
     * Modificar el campo 'longitud' de un objeto
     *
     * @param String $longitud longitud
     *
     * @return no
     *
     */
    public function setLongitud($longitud)
    {
        $this->longitud = $longitud;
    }

    /**
     * Obtener el campo latitud de un objeto
     *
     * @return String latitud latitud
     * 
     */
    public function getLatitud()
    {
        return $this->latitud;
    }

    /**
     * Modificar el campo 'latitud' de un objeto
     *
     * @param String $latitud latitud
     *
     * @return no
     *
     */
    public function setLatitud($latitud)
    {
        $this->latitud = $latitud;
    }

    /**
     * Obtener el campo direccion de un objeto
     *
     * @return String direccion direccion
     * 
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Modificar el campo 'direccion' de un objeto
     *
     * @param String $direccion direccion
     *
     * @return no
     *
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;
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
     * Obtener el campo ciudadId de un objeto
     *
     * @return String ciudadId ciudadId
     * 
     */
    public function getCiudadId()
    {
        return $this->ciudadId;
    }

    /**
     * Modificar el campo 'ciudadId' de un objeto
     *
     * @param String $ciudadId ciudadId
     *
     * @return no
     *
     */
    public function setCiudadId($ciudadId)
    {
        $this->ciudadId = $ciudadId;
    }







    /**
    * Realizar una consulta en la tabla de CompetenciaPuntos 'CompetenciaPuntos'
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
    * @throws Exception si los deportes no existen
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getCompetenciaPuntosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $CompetenciaPuntosMySqlDAO = new CompetenciaPuntosMySqlDAO();

        $deportes = $CompetenciaPuntosMySqlDAO->queryCompetenciaPuntosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($deportes != null && $deportes != "") 
        {
            return $deportes;
        } 
        else 
        {
            throw new Exception("No existe " . get_class($this), "46");
        }

    }


}

?>