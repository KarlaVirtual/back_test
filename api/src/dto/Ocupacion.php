<?php namespace Backend\dto;
/** 
* Clase 'Ocupacion'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'Ocupacion'
* 
* Ejemplo de uso: 
* $Ocupacion = new Ocupacion();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class Ocupacion
{

    /**
    * Representación de la columna 'ocupacionId' de la tabla 'Ocupacion'
    *
    * @var string
    */  	
	var $ocupacionId;

    /**
    * Representación de la columna 'descripcion' de la tabla 'Ocupacion'
    *
    * @var string
    */  
	var $descripcion;

    /**
    * Representación de la columna 'estado' de la tabla 'Ocupacion'
    *
    * @var string
    */  
	var $estado;

    /**
    * Representación de la columna 'mandante' de la tabla 'Ocupacion'
    *
    * @var string
    */  
	var $mandante;

    /**
    * Representación de la columna 'orden' de la tabla 'Ocupacion'
    *
    * @var string
    */  
	var $orden;
		
}
?>