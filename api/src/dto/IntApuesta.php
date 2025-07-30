<?php 
namespace Backend\dto;
use Backend\mysql\IntApuestaMySqlDAO;
use Exception;
/** 
* Clase 'IntApuesta'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'IntApuesta'
* 
* Ejemplo de uso: 
* $IntApuesta = new IntApuesta();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class IntApuesta
{

    /**
    * Representación de la columna 'apuestaId' de la tabla 'Ingreso'
    *
    * @var string
    */
	var $apuestaId;

    /**
    * Representación de la columna 'nombre' de la tabla 'Ingreso'
    *
    * @var string
    */
	var $nombre;

    /**
    * Representación de la columna 'nombreTraduccion' de la tabla 'Ingreso'
    *
    * @var string
    */
	var $nombreTraduccion;

    /**
    * Representación de la columna 'nombreInternacional' de la tabla 'Ingreso'
    *
    * @var string
    */
	var $nombreInternacional;

    /**
    * Representación de la columna 'abreviado' de la tabla 'Ingreso'
    *
    * @var string
    */
	var $abreviado;

    /**
    * Representación de la columna 'estado' de la tabla 'Ingreso'
    *
    * @var string
    */
	var $estado;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'Ingreso'
    *
    * @var string
    */
	var $usucreaId;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'Ingreso'
    *
    * @var string
    */
	var $usumodifId;

    /**
    * Representación de la columna 'fechaCrea' de la tabla 'Ingreso'
    *
    * @var string
    */
	var $fechaCrea;

    /**
    * Representación de la columna 'fechaModif' de la tabla 'Ingreso'
    *
    * @var string
    */
	var $fechaModif;


    /**
    * Realizar una consulta en la tabla de apuestas 'IntApuesta'
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
    * @throws Exception si las apuestas no existen
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getApuestasCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $IntApuestaMySqlDAO = new IntApuestaMySqlDAO();

        $apuestas = $IntApuestaMySqlDAO->queryApuestasCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($apuestas != null && $apuestas != "") 
        {
            return $apuestas;
        } 
        else 
        {
            throw new Exception("No existe " . get_class($this), "01");
        }

    }

		
	}
?>