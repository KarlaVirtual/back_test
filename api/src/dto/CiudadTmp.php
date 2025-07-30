<?php namespace Backend\dto;
/** 
* Clase 'CiudadTmp'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'ciudad_tmp'
* 
* Ejemplo de uso: 
* $CiudadTmp = new CiudadTmp();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class CiudadTmp
{

    /**
    * Representación de la columna 'ciudadId' de la tabla 'CiudadTmp'
    *
    * @var string
    */	
	var $ciudadId;

    /**
    * Representación de la columna 'ciudadCod' de la tabla 'CiudadTmp'
    *
    * @var string
    */
	var $ciudadCod;

    /**
    * Representación de la columna 'ciudadNom' de la tabla 'CiudadTmp'
    *
    * @var string
    */
	var $ciudadNom;

    /**
    * Representación de la columna 'deptoId' de la tabla 'CiudadTmp'
    *
    * @var string
    */
	var $deptoId;

    /**
    * Representación de la columna 'longitud' de la tabla 'CiudadTmp'
    *
    * @var string
    */
	var $longitud;

    /**
    * Representación de la columna 'latitud' de la tabla 'CiudadTmp'
    *
    * @var string
    */
	var $latitud;
		
}

?>