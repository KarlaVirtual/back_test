<?php namespace Backend\dto;
/** 
* Clase 'Sesione'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'Sesione'
* 
* Ejemplo de uso: 
* $Sesione = new Sesione();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class Sesione{

    /**
    * Representación de la columna 'id' de la tabla 'Sesione'
    *
    * @var string
    */		
	var $id;

    /**
    * Representación de la columna 'horario' de la tabla 'Sesione'
    *
    * @var string
    */
	var $horario;

    /**
    * Representación de la columna 'data' de la tabla 'Sesione'
    *
    * @var string
    */
	var $data;

    /**
    * Representación de la columna 'claveSesion' de la tabla 'Sesione'
    *
    * @var string
    */
	var $claveSesion;
		
}

?>