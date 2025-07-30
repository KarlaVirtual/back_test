<?php namespace Backend\dto;
use Backend\mysql\IntRegionMySqlDAO;
use Exception;
/** 
* Clase 'IntRegion'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'IntRegion'
* 
* Ejemplo de uso: 
* $IntRegion = new IntRegion();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class IntRegion
{
		

    /**
    * Representación de la columna 'regionId' de la tabla 'IntRegion'
    *
    * @var string
    */ 
	var $regionId;

    /**
    * Representación de la columna 'deporteId' de la tabla 'IntRegion'
    *
    * @var string
    */ 
	var $deporteId;

    /**
    * Representación de la columna 'nombre' de la tabla 'IntRegion'
    *
    * @var string
    */ 
	var $nombre;

    /**
    * Representación de la columna 'nombreTraduccion' de la tabla 'IntRegion'
    *
    * @var string
    */ 
	var $nombreTraduccion;

    /**
    * Representación de la columna 'nombreInternacional' de la tabla 'IntRegion'
    *
    * @var string
    */ 
	var $nombreInternacional;

    /**
    * Representación de la columna 'abreviado' de la tabla 'IntRegion'
    *
    * @var string
    */ 
	var $abreviado;

    /**
    * Representación de la columna 'estado' de la tabla 'IntRegion'
    *
    * @var string
    */ 
	var $estado;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'IntRegion'
    *
    * @var string
    */ 
	var $usucreaId;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'IntRegion'
    *
    * @var string
    */ 
	var $usumodifId;

    /**
    * Representación de la columna 'fechaCrea' de la tabla 'IntRegion'
    *
    * @var string
    */ 
	var $fechaCrea;

    /**
    * Representación de la columna 'fechaModif' de la tabla 'IntRegion'
    *
    * @var string
    */ 
	var $fechaModif;


    /**
    * Realizar una consulta en la tabla de IntRegion 'IntRegion'
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
    * @throws Exception si las regiones no existen
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getRegionesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $IntRegionMySqlDAO = new IntRegionMySqlDAO();

        $regiones = $IntRegionMySqlDAO->queryRegionesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($regiones != null && $regiones != "") 
        {
            return $regiones;
        } 
        else 
        {
            throw new Exception("No existe " . get_class($this), "01");
        }

    }



    }
?>