<?php namespace Backend\dto;
/** 
* Clase 'PeriodicidadLiquida'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'PeriodicidadLiquida'
* 
* Ejemplo de uso: 
* $PeriodicidadLiquida = new PeriodicidadLiquida();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class PeriodicidadLiquida
{

    /**
    * Representación de la columna 'periodicidadId' de la tabla 'PeriodicidadLiquida'
    *
    * @var string
    */ 	
	var $periodicidadId;

    /**
    * Representación de la columna 'descripcion' de la tabla 'PeriodicidadLiquida'
    *
    * @var string
    */ 
	var $descripcion;

    /**
    * Representación de la columna 'dias' de la tabla 'PeriodicidadLiquida'
    *
    * @var string
    */ 
	var $dias;

    /**
    * Representación de la columna 'estado' de la tabla 'PeriodicidadLiquida'
    *
    * @var string
    */ 
	var $estado;
		
}

?>