<?php namespace Backend\dto;
use Backend\mysql\BonoMySqlDAO;
/** 
* Clase 'Bono'
* Bono
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'Bono'
* 
* Ejemplo de uso: 
* $Bono = new Bono();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class Bono
{
	
    /**
    * Representación de la columna 'bonoId' de la tabla 'Bono'
    *
    * @var string
    */  	
	var $bonoId;

    /**
    * Representación de la columna 'codigo' de la tabla 'Bono'
    *
    * @var string
    */  
	var $codigo;

    /**
    * Representación de la columna 'bonusplanid' de la tabla 'Bono'
    *
    * @var string
    */  
	var $bonusplanid;

    /**
    * Representación de la columna 'fechaIni' de la tabla 'Bono'
    *
    * @var string
    */  
	var $fechaIni;

    /**
    * Representación de la columna 'fechaFin' de la tabla 'Bono'
    *
    * @var string
    */  
	var $fechaFin;

    /**
    * Representación de la columna 'tipo' de la tabla 'Bono'
    *
    * @var string
    */  
	var $tipo;

    /**
    * Representación de la columna 'descripcion' de la tabla 'Bono'
    *
    * @var string
    */  
	var $descripcion;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'Bono'
    *
    * @var string
    */  
	var $usucreaId;

    /**
    * Representación de la columna 'fechaCrea' de la tabla 'Bono'
    *
    * @var string
    */  
	var $fechaCrea;

    /**
    * Representación de la columna 'mandante' de la tabla 'Bono'
    *
    * @var string
    */  
	var $mandante;

    /**
    * Representación de la columna 'diasExpira' de la tabla 'Bono'
    *
    * @var string
    */  
	var $diasExpira;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'Bono'
    *
    * @var string
    */  
	var $usumodifId;

    /**
    * Representación de la columna 'fechaModif' de la tabla 'Bono'
    *
    * @var string
    */  
	var $fechaModif;

    /**
    * Representación de la columna 'owner' de la tabla 'Bono'
    *
    * @var string
    */  
	var $owner;


    /**
    * Realizar una consulta en la tabla de bonos 'Bono'
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
    * @throws Exception si los bonos no existen
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getBonosCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping)
    {

        $BonoMySqlDAO = new BonoMySqlDAO();

        $bonos = $BonoMySqlDAO->queryBonosCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping);

        if ($bonos != null && $bonos != "")
        {
			return $bonos;
        }
        else 
        {
            throw new Exception("No existe " . get_class($this), "01");
        }

	}

}
?>