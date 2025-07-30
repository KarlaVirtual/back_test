<?php namespace Backend\dto;
/** 
* Clase 'CiudadCopy'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'ciudad_copy'
* 
* Ejemplo de uso: 
* $CiudadCopy = new CiudadCopy();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class CiudadCopy
{

    /**
    * Representación de la columna 'ciudadId' de la tabla 'CiudadCopy'
    *
    * @var string
    */  			
	var $ciudadId;

    /**
    * Representación de la columna 'ciudadCod' de la tabla 'CiudadCopy'
    *
    * @var string
    */  	
	var $ciudadCod;

    /**
    * Representación de la columna 'ciudadNom' de la tabla 'CiudadCopy'
    *
    * @var string
    */  	
	var $ciudadNom;

    /**
    * Representación de la columna 'deptoId' de la tabla 'CiudadCopy'
    *
    * @var string
    */  	
	var $deptoId;

    /**
    * Representación de la columna 'longitud' de la tabla 'CiudadCopy'
    *
    * @var string
    */  	
	var $longitud;

    /**
    * Representación de la columna 'latitud' de la tabla 'CiudadCopy'
    *
    * @var string
    */  	
	var $latitud;
		
}
?>