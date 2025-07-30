<?php namespace Backend\dto;
use Backend\mysql\IntApuestaDetalleMySqlDAO;
use Exception;
/** 
* Clase 'IntApuestaDetalle'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'IntApuestaDetalle'
* 
* Ejemplo de uso: 
* $IntApuestaDetalle = new IntApuestaDetalle();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class IntApuestaDetalle
{

    /**
    * Representación de la columna 'apuestadetalleId' de la tabla 'IntApuestaDetalle'
    *
    * @var string
    */
	var $apuestadetalleId;

    /**
    * Representación de la columna 'apuestaId' de la tabla 'IntApuestaDetalle'
    *
    * @var string
    */
	var $apuestaId;

    /**
    * Representación de la columna 'opcionId' de la tabla 'IntApuestaDetalle'
    *
    * @var string
    */
	var $opcionId;

    /**
    * Representación de la columna 'opcion' de la tabla 'IntApuestaDetalle'
    *
    * @var string
    */
	var $opcion;

    /**
    * Representación de la columna 'estado' de la tabla 'IntApuestaDetalle'
    *
    * @var string
    */
	var $estado;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'IntApuestaDetalle'
    *
    * @var string
    */
	var $usucreaId;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'IntApuestaDetalle'
    *
    * @var string
    */
	var $usumodifId;

    /**
    * Representación de la columna 'fechaCrea' de la tabla 'IntApuestaDetalle'
    *
    * @var string
    */
	var $fechaCrea;

    /**
    * Representación de la columna 'fechaModif' de la tabla 'IntApuestaDetalle'
    *
    * @var string
    */
	var $fechaModif;

    /**
    * Realizar una consulta en la tabla de detalles de apuestas 'IntApuestaDetalle'
    * de una manera personalizada
    *
    *
    * @param String $select campos de consulta
    * @param String $sidx columna para ordenar
    * @param String $sord orden los datos asc | desc
    * @param String $start inicio de la consulta
    * @param String $limit limite de la consulta
    * @param String $filters condiciones de la consulta 
    * @param boolean $searchOn utilizar los filtros o no
    *
    * @return Array resultado de la consulta
    * @throws Exception si las apuestas no existen
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getApuestaDetallesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $IntApuestaDetalleMySqlDAO = new IntApuestaDetalleMySqlDAO();

        $apuestas = $IntApuestaDetalleMySqlDAO->queryApuestaDetallesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($apuestas != null && $apuestas != "") 
        {

            return $apuestas;
        } 
        else 
        {
            throw new Exception("No existe " . get_class($this), "01");
        }

    }

}
?>