<?php 
namespace Backend\dto;
use Backend\mysql\CompetenciaMySqlDAO;
use Exception;
/** 
* Clase 'Competencia'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'Competencia'
* 
* Ejemplo de uso: 
* $Competencia = new Competencia();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class Competencia
{

    /**
    * Representación de la columna 'paisId' de la tabla 'Competencia'
    *
    * @var string
    */
    var $paisId;

    /**
    * Representación de la columna 'competenciaId' de la tabla 'Competencia'
    *
    * @var string
    */
    var $competenciaId;

    /**
    * Representación de la columna 'nombre' de la tabla 'Competencia'
    *
    * @var string
    */
    var $nombre;

    /**
    * Representación de la columna 'descripcion' de la tabla 'Competencia'
    *
    * @var string
    */
    var $descripcion;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'Competencia'
    *
    * @var string
    */
    var $usucreaId;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'Competencia'
    *
    * @var string
    */
    var $usumodifId;

    /**
    * Representación de la columna 'fechaCrea' de la tabla 'Competencia'
    *
    * @var string
    */
    var $fechaCrea;

    /**
    * Representación de la columna 'fechaModif' de la tabla 'Competencia'
    *
    * @var string
    */
    var $fechaModif;

    /**
     * Representación de la columna 'mandante' de la tabla 'Competencia'
     *
     * @var string
     */
    var $mandante;


    /**
    * Constructor de clase
    *
    *
    * @param String $paisId id del pais
    *
    * @return no
    * @throws Exception si la competencia no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($paisId)
    {

        if ($paisId != "") 
        {

            $CompetenciaMySqlDAO = new CompetenciaMySqlDAO();

            $Competencia = $CompetenciaMySqlDAO->load($paisId);

            if ($Competencia != null && $Competencia != "") 
            {
                $this->paisId = $Competencia->paisId;
                $this->competenciaId = $Competencia->competenciaId;
                $this->nombre = $Competencia->nombre;
                $this->descripcion = $Competencia->descripcion;
                $this->usucreaId = $Competencia->usucreaId;
                $this->usumodifId = $Competencia->usumodifId;
                $this->mandante = $Competencia->mandante;
            } 
            else 
            {
                throw new Exception("No existe " . get_class($this), "46");
            }


        }
    }








    /**
    * Realizar una consulta en la tabla de competencias 'Competencia'
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
    public function getCompetenciasCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $CompetenciaMySqlDAO = new CompetenciaMySqlDAO();

        $deportes = $CompetenciaMySqlDAO->queryCompetenciaCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

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