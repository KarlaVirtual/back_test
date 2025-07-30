<?php namespace Backend\dto;
/** 
* Clase 'RangoIngreso'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'RangoIngreso'
* 
* Ejemplo de uso: 
* $RangoIngreso = new RangoIngreso();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class RangoIngreso
{

    /**
    * Representación de la columna 'rangoingresoId' de la tabla 'RangoIngreso'
    *
    * @var string
    */	
	var $rangoingresoId;

    /**
    * Representación de la columna 'descripcion' de la tabla 'RangoIngreso'
    *
    * @var string
    */
	var $descripcion;

    /**
    * Representación de la columna 'estado' de la tabla 'RangoIngreso'
    *
    * @var string
    */
	var $estado;

    /**
    * Representación de la columna 'orden' de la tabla 'RangoIngreso'
    *
    * @var string
    */
	var $orden;
		
}
?>