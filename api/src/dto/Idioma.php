<?php namespace Backend\dto;
/** 
* Clase 'Idioma'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'Idioma'
* 
* Ejemplo de uso: 
* $Idioma = new Idioma();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class Idioma
{

    /**
    * Representación de la columna 'idioma' de la tabla 'Idioma'
    *
    * @var string
    */ 	
	var $idioma;

    /**
    * Representación de la columna 'descripcion' de la tabla 'Idioma'
    *
    * @var string
    */ 
	var $descripcion;

    /**
    * Representación de la columna 'estado' de la tabla 'Idioma'
    *
    * @var string
    */ 
	var $estado;
		
}

?>