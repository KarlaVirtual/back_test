<?php 
namespace Backend\dto;
use Backend\mysql\IntCompetenciaMySqlDAO;
use Exception;
/** 
* Clase 'IntCompetencia'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'IntCompetencia'
* 
* Ejemplo de uso: 
* $IntCompetencia = new IntCompetencia();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class IntCompetencia
{


    /**
    * Representación de la columna 'competenciaId' de la tabla 'IntCompetencia'
    *
    * @var string
    */		
	var $competenciaId;

    /**
    * Representación de la columna 'regionId' de la tabla 'IntCompetencia'
    *
    * @var string
    */
	var $regionId;

    /**
    * Representación de la columna 'nombre' de la tabla 'IntCompetencia'
    *
    * @var string
    */
	var $nombre;

    /**
    * Representación de la columna 'nombreTraduccion' de la tabla 'IntCompetencia'
    *
    * @var string
    */
	var $nombreTraduccion;

    /**
    * Representación de la columna 'nombreInternacional' de la tabla 'IntCompetencia'
    *
    * @var string
    */
	var $nombreInternacional;

    /**
    * Representación de la columna 'abreviado' de la tabla 'IntCompetencia'
    *
    * @var string
    */
	var $abreviado;

    /**
    * Representación de la columna 'estado' de la tabla 'IntCompetencia'
    *
    * @var string
    */
	var $estado;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'IntCompetencia'
    *
    * @var string
    */
	var $usucreaId;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'IntCompetencia'
    *
    * @var string
    */
	var $usumodifId;

    /**
    * Representación de la columna 'fechaCrea' de la tabla 'IntCompetencia'
    *
    * @var string
    */
	var $fechaCrea;

    /**
    * Representación de la columna 'fechaModif' de la tabla 'IntCompetencia'
    *
    * @var string
    */
	var $fechaModif;


    /**
    * Realizar una consulta en la tabla de detalles de competencias 'IntCompetencia'
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
    * @throws Exception si las competencias no existen
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getCompetenciasCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
        {

            $IntCompetenciaMySqlDAO = new IntCompetenciaMySqlDAO();

            $competencias = $IntCompetenciaMySqlDAO->queryCompetenciasCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

            if ($competencias != null && $competencias != "") 
            {
                return $competencias;
            } 
            else 
            {
                throw new Exception("No existe " . get_class($this), "01");
            }

        }

		
	
}

?>