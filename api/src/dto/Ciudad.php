<?php 
namespace Backend\dto;
use Backend\mysql\CiudadMySqlDAO;
use Exception;

/**
* Clase 'Ciudad'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'ciudad'
* 
* Ejemplo de uso: 
* $Ciudad = new Ciudad();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class Ciudad
{

    public function __construct($ciudadId='')
    {
        if ($ciudadId != "")
        {


            $CiudadMySqlDAO = new CiudadMySqlDAO();

            $Ciudad = $CiudadMySqlDAO->load($ciudadId);


            if ($Ciudad != null && $Ciudad != "")
            {


                $this->ciudadId = $Ciudad->ciudadId;
                $this->ciudadCod = $Ciudad->ciudadCod;
                $this->ciudadNom = $Ciudad->ciudadNom;
                $this->deptoId = $Ciudad->deptoId;
                $this->longitud = $Ciudad->longitud;
                $this->latitud = $Ciudad->latitud;


            } else {
                throw new Exception("No existe " . get_class($this), "30");

            }

        }


    }

    /**
    * Representación de la columna 'ciudadId' de la tabla 'Ciudad'
    *
    * @var string
    */  			
	var $ciudadId;

    /**
    * Representación de la columna 'ciudadCod' de la tabla 'Ciudad'
    *
    * @var string
    */  	
	var $ciudadCod;

    /**
    * Representación de la columna 'ciudadNom' de la tabla 'Ciudad'
    *
    * @var string
    */  	
	var $ciudadNom;

    /**
    * Representación de la columna 'deptoId' de la tabla 'Ciudad'
    *
    * @var string
    */  	
	var $deptoId;

    /**
    * Representación de la columna 'longitud' de la tabla 'Ciudad'
    *
    * @var string
    */  	
	var $longitud;

    /**
    * Representación de la columna 'latitud' de la tabla 'Ciudad'
    *
    * @var string
    */  	
	var $latitud;

		
	}
?>