<?php namespace Backend\dto;
/** 
* Clase 'ReciboCaja'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'ReciboCaja'
* 
* Ejemplo de uso: 
* $ReciboCaja = new ReciboCaja();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class ReciboCaja
{

    /**
    * Representación de la columna 'reciboId' de la tabla 'ReciboCaja'
    *
    * @var string
    */		
	var $reciboId;

    /**
    * Representación de la columna 'fechaCrea' de la tabla 'ReciboCaja'
    *
    * @var string
    */
	var $fechaCrea;

    /**
    * Representación de la columna 'horaCrea' de la tabla 'ReciboCaja'
    *
    * @var string
    */
	var $horaCrea;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'ReciboCaja'
    *
    * @var string
    */
	var $usucreaId;

    /**
    * Representación de la columna 'depositanteId' de la tabla 'ReciboCaja'
    *
    * @var string
    */
	var $depositanteId;

    /**
    * Representación de la columna 'valor' de la tabla 'ReciboCaja'
    *
    * @var string
    */
	var $valor;

    /**
    * Representación de la columna 'mandante' de la tabla 'ReciboCaja'
    *
    * @var string
    */
	var $mandante;

		
}

?>