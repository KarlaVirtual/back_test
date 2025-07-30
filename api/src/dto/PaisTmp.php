<?php namespace Backend\dto;
/** 
* Clase 'PaisTmp'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'PaisTmp'
* 
* Ejemplo de uso: 
* $PaisTmp = new PaisTmp();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class PaisTmp
{

    /**
    * Representación de la columna 'paisId' de la tabla 'PaisTmp'
    *
    * @var string
    */  	
	var $paisId;

    /**
    * Representación de la columna 'iso' de la tabla 'PaisTmp'
    *
    * @var string
    */  
	var $iso;

    /**
    * Representación de la columna 'paisNom' de la tabla 'PaisTmp'
    *
    * @var string
    */  
	var $paisNom;
		
}
?>