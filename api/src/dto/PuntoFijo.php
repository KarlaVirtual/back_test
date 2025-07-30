<?php namespace Backend\dto;
/** 
* Clase 'PuntoFijo'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'PuntoFijo'
* 
* Ejemplo de uso: 
* $PuntoFijo = new PuntoFijo();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class PuntoFijo
{
		

    /**
    * Representación de la columna 'puntofijoId' de la tabla 'PuntoFijo'
    *
    * @var string
    */
	var $puntofijoId;

    /**
    * Representación de la columna 'nodoId' de la tabla 'PuntoFijo'
    *
    * @var string
    */
	var $nodoId;

    /**
    * Representación de la columna 'puntoventaId' de la tabla 'PuntoFijo'
    *
    * @var string
    */
	var $puntoventaId;

    /**
    * Representación de la columna 'mandante' de la tabla 'PuntoFijo'
    *
    * @var string
    */
	var $mandante;
			
}
?>