<?php namespace Backend\dto;
/** 
* Clase 'UsuarioPunto'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'UsuarioPunto'
* 
* Ejemplo de uso: 
* $UsuarioPunto = new UsuarioPunto();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class UsuarioPunto
{

    /**
    * Representación de la columna 'usupuntoId' de la tabla 'UsuarioPunto'
    *
    * @var string
    */	
	var $usupuntoId;

    /**
    * Representación de la columna 'usuarioId' de la tabla 'UsuarioPunto'
    *
    * @var string
    */
	var $usuarioId;

    /**
    * Representación de la columna 'puntoventaId' de la tabla 'UsuarioPunto'
    *
    * @var string
    */
	var $puntoventaId;

    /**
    * Representación de la columna 'mandante' de la tabla 'UsuarioPunto'
    *
    * @var string
    */
	var $mandante;

}
?>