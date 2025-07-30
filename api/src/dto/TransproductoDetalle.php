<?php namespace Backend\dto;
/** 
* Clase 'TransproductoDetalle'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'TransproductoDetalle'
* 
* Ejemplo de uso: 
* $TransproductoDetalle = new TransproductoDetalle();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class TransproductoDetalle
{

    /**
    * Representación de la columna 'transproddetalleId' de la tabla 'TransproductoDetalle'
    *
    * @var string
    */		
	var $transproddetalleId;

    /**
    * Representación de la columna 'transproductoId' de la tabla 'TransproductoDetalle'
    *
    * @var string
    */
	var $transproductoId;

    /**
    * Representación de la columna 'tValue' de la tabla 'TransproductoDetalle'
    *
    * @var string
    */
	var $tValue;
		
}
?>