<?php namespace Backend\dto;
/**
* Clase 'SaldoTraslado'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'SaldoTraslado'
* 
* Ejemplo de uso: 
* $SaldoTraslado = new SaldoTraslado();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class SaldoTraslado
{

    /**
    * Representación de la columna 'trasladoId' de la tabla 'SaldoTraslado'
    *
    * @var string
    */		
	var $trasladoId;

    /**
    * Representación de la columna 'usuarioId' de la tabla 'SaldoTraslado'
    *
    * @var string
    */
	var $usuarioId;

    /**
    * Representación de la columna 'fecha' de la tabla 'SaldoTraslado'
    *
    * @var string
    */
	var $fecha;
	
    /**
    * Representación de la columna 'valor' de la tabla 'SaldoTraslado'
    *
    * @var string
    */
	var $valor;

    /**
    * Representación de la columna 'origen' de la tabla 'SaldoTraslado'
    *
    * @var string
    */
	var $origen;

    /**
    * Representación de la columna 'destino' de la tabla 'SaldoTraslado'
    *
    * @var string
    */
	var $destino;

    /**
    * Representación de la columna 'mandante' de la tabla 'SaldoTraslado'
    *
    * @var string
    */
	var $mandante;
	
}
?>