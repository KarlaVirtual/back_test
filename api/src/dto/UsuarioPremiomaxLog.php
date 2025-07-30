<?php namespace Backend\dto;
/** 
* Clase 'UsuarioPremiomaxLog'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'UsuarioPremiomaxLog'
* 
* Ejemplo de uso: 
* $UsuarioPremiomaxLog = new UsuarioPremiomaxLog();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class UsuarioPremiomaxLog
{

    /**
    * Representación de la columna 'premiomaxlogId' de la tabla 'UsuarioPremiomaxLog'
    *
    * @var string
    */
	var $premiomaxlogId;

    /**
    * Representación de la columna 'usuarioId' de la tabla 'UsuarioPremiomaxLog'
    *
    * @var string
    */
	var $usuarioId;

    /**
    * Representación de la columna 'fecha' de la tabla 'UsuarioPremiomaxLog'
    *
    * @var string
    */
	var $fecha;

    /**
    * Representación de la columna 'valor' de la tabla 'UsuarioPremiomaxLog'
    *
    * @var string
    */
	var $valor;

    /**
    * Representación de la columna 'mandante' de la tabla 'UsuarioPremiomaxLog'
    *
    * @var string
    */
	var $mandante;
		
}
?>