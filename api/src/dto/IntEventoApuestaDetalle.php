<?php 
namespace Backend\dto;
use Backend\mysql\IntApuestaDetalleMySqlDAO;
use Backend\mysql\IntEventoApuestaDetalleMySqlDAO;
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
class IntEventoApuestaDetalle
{

    /**
    * Representación de la columna 'eventoId' de la tabla 'IntEquipo'
    *
    * @var string
    */ 
	var $eventapudetalleId;

    /**
    * Representación de la columna 'eventoId' de la tabla 'IntEquipo'
    *
    * @var string
    */ 
	var $eventoapuestaId;

    /**
    * Representación de la columna 'eventoId' de la tabla 'IntEquipo'
    *
    * @var string
    */ 
	var $apuestadetalleId;

    /**
    * Representación de la columna 'eventoId' de la tabla 'IntEquipo'
    *
    * @var string
    */ 
	var $nombre;

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
	var $estado;

    /**
    * Representación de la columna 'eventoId' de la tabla 'IntEquipo'
    *
    * @var string
    */ 
	var $estadoApuesta;

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
    * Constructor de clase
    *
    *
    * @param String $eventapudetalleId id del EventoApuestaDetalle
    *
    * @return no
    * @throws Exception si EventoApuestaDetalle no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($eventapudetalleId="")
    {
            if ($eventapudetalleId != "") 
            {


                $this->eventapudetalleId = $eventapudetalleId;

                $IntEventoApuestaDetalleMySqlDAO = new IntEventoApuestaDetalleMySqlDAO();

                $IntEventoApuestaDetalle = $IntEventoApuestaDetalleMySqlDAO->load($this->eventapudetalleId);

                $this->success = false;

                if ($IntEventoApuestaDetalle != null && $IntEventoApuestaDetalle != "") 
                {
                    $this->eventapudetalleId = $IntEventoApuestaDetalle->eventapudetalleId;
                    $this->eventoapuestaId = $IntEventoApuestaDetalle->eventoapuestaId;
                    $this->apuestadetalleId = $IntEventoApuestaDetalle->apuestadetalleId;
                    $this->nombre = $IntEventoApuestaDetalle->nombre;
                    $this->valor = $IntEventoApuestaDetalle->valor;
                    $this->estado = $IntEventoApuestaDetalle->estado;
                    $this->estadoApuesta = $IntEventoApuestaDetalle->estadoApuesta;
                    $this->usucreaId = $IntEventoApuestaDetalle->usucreaId;
                    $this->usumodifId = $IntEventoApuestaDetalle->usumodifId;
                    $this->fechaCrea = $IntEventoApuestaDetalle->fechaCrea;
                    $this->fechaModif = $IntEventoApuestaDetalle->fechaModif;
                    $this->success = true;
                }
                else 
                {
                    throw new Exception("No existe " . get_class($this), "22");
                }
            }

    }


    /**
     * Ejecutar una consulta sql como insert
     * 
     *
     *
     * @param Objeto transaccion Transaccion
     *
     * @return Array resultado de la consulta
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */  
    public function insert($transaction)
    {

            $IntEventoApuestaDetalleMySqlDAO = new IntEventoApuestaDetalleMySqlDAO($transaction);

            return $IntEventoApuestaDetalleMySqlDAO->insert($this);

    }

    /**
    * Realizar una consulta en la tabla de detalles de IntEventoDetalleApuestaDetalle 'IntEventoDetalleApuestaDetalle'
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
    public function getEventoApuestaDetallesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

            $IntEventoApuestaDetalleMySqlDAO = new IntEventoApuestaDetalleMySqlDAO();

            $eventos = $IntEventoApuestaDetalleMySqlDAO->queryEventoApuestaDetallesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

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