<?php namespace Backend\dto;
/** 
* Clase 'FormaPago'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'FormaPago'
* 
* Ejemplo de uso: 
* $FormaPago = new FormaPago();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class FormaPago
{

    /**
    * Representación de la columna 'formapagoId' de la tabla 'FormaPago'
    *
    * @var string
    */ 			
	var $formapagoId;

    /**
    * Representación de la columna 'descripcion' de la tabla 'FormaPago'
    *
    * @var string
    */ 		
	var $descripcion;

    /**
    * Representación de la columna 'tipo' de la tabla 'FormaPago'
    *
    * @var string
    */ 		
	var $tipo;
		
}
?>