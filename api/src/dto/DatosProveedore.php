<?php namespace Backend\dto;
/** 
* Clase 'DatosProveedore'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'DatosProveedore'
* 
* Ejemplo de uso: 
* $DatosProveedore = new DatosProveedore();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class DatosProveedore
{
    /**
    * Representación de la columna 'id' de la tabla 'DatosProveedore'
    *
    * @var string
    */  
	var $id;

    /**
    * Representación de la columna 'localID' de la tabla 'DatosProveedore'
    *
    * @var string
    */  
	var $localID;

    /**
    * Representación de la columna 'remoteID' de la tabla 'DatosProveedore'
    *
    * @var string
    */  
	var $remoteID;

    /**
    * Representación de la columna 'request' de la tabla 'DatosProveedore'
    *
    * @var string
    */  
	var $request;

    /**
    * Representación de la columna 'response' de la tabla 'DatosProveedore'
    *
    * @var string
    */  
	var $response;

    /**
    * Representación de la columna 'timestamp' de la tabla 'DatosProveedore'
    *
    * @var string
    */  
	var $timestamp;
		
}

?>