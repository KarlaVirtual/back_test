<?php namespace Backend\dto;
use Backend\mysql\IntEventoDetalleMySqlDAO;
use Exception;
/** 
* Clase 'IntEvento'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'IntEvento'
* 
* Ejemplo de uso: 
* $IntEvento = new IntEvento();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class IntEventoDetalle
{

    /**
    * Representación de la columna 'eventoId' de la tabla 'IntEquipo'
    *
    * @var string
    */ 	
	var $eventodetalleId;

    /**
    * Representación de la columna 'eventoId' de la tabla 'IntEquipo'
    *
    * @var string
    */ 
	var $eventoId;

    /**
    * Representación de la columna 'eventoId' de la tabla 'IntEquipo'
    *
    * @var string
    */ 
	var $tipo;

    /**
    * Representación de la columna 'eventoId' de la tabla 'IntEquipo'
    *
    * @var string
    */ 
	var $id;

    /**
    * Representación de la columna 'eventoId' de la tabla 'IntEquipo'
    *
    * @var string
    */ 
	var $valor;

    /**
    * Representación de la columna 'eventoId' de la tabla 'IntEquipo'
    *
    * @var string
    */ 
	var $usucreaId;

    /**
    * Representación de la columna 'eventoId' de la tabla 'IntEquipo'
    *
    * @var string
    */ 
	var $usumodifId;

    /**
    * Representación de la columna 'eventoId' de la tabla 'IntEquipo'
    *
    * @var string
    */ 
	var $fechaCrea;

    /**
    * Representación de la columna 'eventoId' de la tabla 'IntEquipo'
    *
    * @var string
    */ 
	var $fechaModif;

    /**
    * Realizar una consulta en la tabla de detalles de IntEventoDetalle 'IntEventoDetalle'
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
    * @throws Exception si los eventos no existen
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getEventoDetallesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

            $IntEventoDetalleMySqlDAO = new IntEventoDetalleMySqlDAO();

            $eventos = $IntEventoDetalleMySqlDAO->queryEventoDetallesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

            if ($eventos != null && $eventos != "") 
            {
                return $eventos;
            } 
            else 
            {
                throw new Exception("No existe " . get_class($this), "01");
            }

    }
		

}

?>