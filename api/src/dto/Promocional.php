<?php namespace Backend\dto;
/** 
* Clase 'Promocional'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'Promocional'
* 
* Ejemplo de uso: 
* $Promocional = new Promocional();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class Promocional
{
		

    /**
    * Representación de la columna 'promocionalId' de la tabla 'Promocional'
    *
    * @var string
    */ 	
	var $promocionalId;

    /**
    * Representación de la columna 'descripcion' de la tabla 'Promocional'
    *
    * @var string
    */ 
	var $descripcion;

    /**
    * Representación de la columna 'estado' de la tabla 'Promocional'
    *
    * @var string
    */ 
	var $estado;

    /**
    * Representación de la columna 'mandante' de la tabla 'Promocional'
    *
    * @var string
    */ 
	var $mandante;

    /**
    * Representación de la columna 'fechaModif' de la tabla 'Promocional'
    *
    * @var string
    */ 
	var $fechaModif;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'Promocional'
    *
    * @var string
    */ 
	var $usumodifId;
		
}

?>