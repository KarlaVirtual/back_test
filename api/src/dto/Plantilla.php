<?php namespace Backend\dto;
use Backend\mysql\PlantillaMySqlDAO;
use Exception;
/** 
* Clase 'Plantilla'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'plantilla'
* 
* Ejemplo de uso: 
* $Plantilla = new Plantilla();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class Plantilla
{

    /**
    * Representación de la columna 'plantillaId' de la tabla 'Plantilla'
    *
    * @var string
    */
    var $plantillaId;

    /**
    * Representación de la columna 'tipo' de la tabla 'Plantilla'
    *
    * @var string
    */
    var $tipo;

    /**
    * Representación de la columna 'descripcion' de la tabla 'Plantilla'
    *
    * @var string
    */
    var $descripcion;

    /**
    * Representación de la columna 'seccion' de la tabla 'Plantilla'
    *
    * @var string
    */
    var $seccion;

    /**
    * Representación de la columna 'mandante' de la tabla 'Plantilla'
    *
    * @var string
    */
    var $mandante;

    /**
    * Representación de la columna 'plantilla' de la tabla 'Plantilla'
    *
    * @var string
    */
    var $plantilla;

    /**
     * Representación de la columna 'idioma' de la tabla 'Plantilla'
     *
     * @var string
     */
    var $idioma;

    /**
     * Representación de la columna 'idioma' de la tabla 'Plantilla'
     *
     * @var string
     */
    var $usucreaId;

    /**
     * Representación de la columna 'idioma' de la tabla 'Plantilla'
     *
     * @var string
     */
    var $usumodifId;

    /**
    * Constructor de clase
    *
    *
    * @param String $plantillaId
    * @param String $plantilla    
    *
    * @return no
    * @throws Exception si el plantilla no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($plantillaId = "", $seccion = "",$tipo="",$mandante="",$idioma="")
    {
        if ($plantillaId != "")
        {

            $PlantillaMySqlDAO = new PlantillaMySqlDAO();

            $Plantilla = $PlantillaMySqlDAO->load($plantillaId);


            if ($Plantilla != null && $Plantilla != "") 
            {
                $this->plantillaId = $Plantilla->plantillaId;
                $this->tipo = $Plantilla->tipo;
                $this->descripcion = $Plantilla->descripcion;
                $this->seccion = $Plantilla->seccion;
                $this->mandante = $Plantilla->mandante;
                $this->plantilla = $Plantilla->plantilla;
                $this->idioma = $Plantilla->idioma;
                $this->usucreaId = $Plantilla->usucreaId;
                $this->usumodifId = $Plantilla->usumodifId;
            } 
            else 
            {
                throw new Exception("No existe " . get_class($this), "110");
            }

        } 
        elseif ($seccion != "" && $tipo != "" && $mandante != "" && $idioma != "" )
        {
            $PlantillaMySqlDAO = new PlantillaMySqlDAO();

            $Plantilla = $PlantillaMySqlDAO->queryForMandante($seccion,$tipo,$mandante,$idioma);
            $Plantilla = $Plantilla[0];

            if ($Plantilla != null && $Plantilla != "") 
            {
                $this->plantillaId = $Plantilla->plantillaId;
                $this->tipo = $Plantilla->tipo;
                $this->descripcion = $Plantilla->descripcion;
                $this->seccion = $Plantilla->seccion;
                $this->mandante = $Plantilla->mandante;
                $this->plantilla = $Plantilla->plantilla;
                $this->idioma = $Plantilla->idioma;
                $this->usucreaId = $Plantilla->usucreaId;
                $this->usumodifId = $Plantilla->usumodifId;
            } 
            else 
            {
                throw new Exception("No existe " . get_class($this), "110");
            }
        }
    }







    /**
     * Obtener el campo tipo de un objeto
     *
     * @return String tipo tipo
     * 
     */    
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Modificar el campo 'tipo' de un objeto
     *
     * @param String $tipo tipo
     *
     * @return no
     *
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
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
     * Obtener el campo seccion de un objeto
     *
     * @return String seccion seccion
     * 
     */
    public function getEstado()
    {
        return $this->seccion;
    }

    /**
     * Modificar el campo 'seccion' de un objeto
     *
     * @param String $seccion seccion
     *
     * @return no
     *
     */
    public function setEstado($seccion)
    {
        $this->seccion = $seccion;
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
     * Obtener el campo plantilla de un objeto
     *
     * @return String plantilla plantilla
     * 
     */
    public function getAbreviado()
    {
        return $this->plantilla;
    }

    /**
     * Modificar el campo 'plantilla' de un objeto
     *
     * @param String $plantilla plantilla
     *
     * @return no
     *
     */
    public function setAbreviado($plantilla)
    {
        $this->plantilla = $plantilla;
    }

    /**
     * Obtener el campo plantillaId de un objeto
     *
     * @return String plantillaId plantillaId
     * 
     */
    public function getPlantillaId()
    {
        return $this->plantillaId;
    }

    /**
     * Obtener el campo seccion del objeto plantilla
     * @return string
     */
    public function getSeccion()
    {
        return $this->seccion;
    }

    /**
     * Modificar el campo 'seccion' de un objeto
     * @param string $seccion
     */
    public function setSeccion($seccion)
    {
        $this->seccion = $seccion;
    }

    /**
     * Obtener el campo plantilla del objeto Plantilla
     * @return string
     */
    public function getPlantilla()
    {
        return $this->plantilla;
    }

    /**
     * Modificar el campo 'plantilla' de un objeto Plantilla
     * @param string $plantilla
     */
    public function setPlantilla($plantilla)
    {
        $this->plantilla = $plantilla;
    }

    /**
     * Obtener el campo idioma del objeto Plantilla
     * @return string
     */
    public function getIdioma()
    {
        return $this->idioma;
    }

    /**
     * Modificar el campo 'idioma' de un objeto Plantilla
     * @param string $idioma
     */
    public function setIdioma($idioma)
    {
        $this->idioma = $idioma;
    }

    /**
     * Obtener el campo usucreaId del objeto Plantilla
     * @return string
     */
    public function getUsucreaId()
    {
        return $this->usucreaId;
    }

    /**
     * Modificar el campo 'usucreaId' de un objeto Plantilla
     * @param string $usucreaId
     */
    public function setUsucreaId($usucreaId)
    {
        $this->usucreaId = $usucreaId;
    }

    /**
     * Obtener el campo usumodifId del objeto Plantilla
     * @return string
     */
    public function getUsumodifId()
    {
        return $this->usumodifId;
    }

    /**
     * Modificar el campo 'usumodifId' de un objeto Plantilla
     * @param string $usumodifId
     */
    public function setUsumodifId($usumodifId)
    {
        $this->usumodifId = $usumodifId;
    }








    /**
    * Realizar una consulta en la tabla de plantillaes 'Plantilla'
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
    * @throws Exception si los plantillaes no existen
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getPlantillaesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $PlantillaMySqlDAO = new PlantillaMySqlDAO();

        $plantillaes = $PlantillaMySqlDAO->queryPlantillaesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($plantillaes != null && $plantillaes != "") 
        {
            return $plantillaes;
        }
        else
        {
            throw new Exception("No existe " . get_class($this), "110");
        }

    }


}

?>