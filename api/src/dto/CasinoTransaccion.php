<?php namespace Backend\dto;
/** 
* Clase 'CasinoTransaccion'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'CasinoTransaccion'
* 
* Ejemplo de uso: 
* $CasinoTransaccion = new CasinoTransaccion();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class CasinoTransaccion
{
		
    /**
    * Representación de la columna 'transaccionId' de la tabla 'CasinoTransaccion'
    *
    * @var string
    */  
	var $transaccionId;

    /**
    * Representación de la columna 'idUser' de la tabla 'CasinoTransaccion'
    *
    * @var string
    */  
	var $idUser;

    /**
    * Representación de la columna 'idOperacion' de la tabla 'CasinoTransaccion'
    *
    * @var string
    */  
	var $idOperacion;

    /**
    * Representación de la columna 'valor' de la tabla 'CasinoTransaccion'
    *
    * @var string
    */  
	var $valor;

    /**
    * Representación de la columna 'fechaTrans' de la tabla 'CasinoTransaccion'
    *
    * @var string
    */  
	var $fechaTrans;

    /**
    * Representación de la columna 'tipo' de la tabla 'CasinoTransaccion'
    *
    * @var string
    */  
	var $tipo;

    /**
    * Representación de la columna 'mandante' de la tabla 'CasinoTransaccion'
    *
    * @var string
    */  
	var $mandante;


}

?>