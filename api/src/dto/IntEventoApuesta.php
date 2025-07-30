<?php namespace Backend\dto;
use Backend\mysql\IntEventoApuestaMySqlDAO;
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
class IntEventoApuesta
{

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
	var $eventoId;

    /**
    * Representación de la columna 'eventoId' de la tabla 'IntEquipo'
    *
    * @var string
    */ 
	var $apuestaId;

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
    * @param String $eventoapuestaId id del EventoApuesta
    *
    * @return no
    * @throws Exception si IntEventoApuesta no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($eventoapuestaId="")
    {
            if ($eventoapuestaId != "") 
            {


                $this->eventoapuestaId = $eventoapuestaId;

                $IntEventoApuestaMySqlDAO = new IntEventoApuestaMySqlDAO();

                $IntEventoApuesta = $IntEventoApuestaMySqlDAO->load($this->eventoapuestaId);

                $this->success = false;

                if ($IntEventoApuesta != null && $IntEventoApuesta != "") 
                {
                    $this->eventoapuestaId = $IntEventoApuesta->eventoapuestaId;
                    $this->eventoId = $IntEventoApuesta->eventoId;
                    $this->apuestaId = $IntEventoApuesta->apuestaId;
                    $this->nombre = $IntEventoApuesta->nombre;
                    $this->valor = $IntEventoApuesta->valor;
                    $this->estado = $IntEventoApuesta->estado;
                    $this->estadoApuesta = $IntEventoApuesta->estadoApuesta;
                    $this->usucreaId = $IntEventoApuesta->usucreaId;
                    $this->usumodifId = $IntEventoApuesta->usumodifId;
                    $this->fechaCrea = $IntEventoApuesta->fechaCrea;
                    $this->fechaModif = $IntEventoApuesta->fechaModif;
                    $this->success = true;
                }
                else 
                {
                    throw new Exception("No existe " . get_class($this), "22");
                }
            }

    }

    /**
     * Insert record to table
     *
     * @param IntEventoApuestaMySql intEventoApuesta
     */
    public function insert($transaction)
    {

            $IntEventoApuestaMySqlDAO = new IntEventoApuestaMySqlDAO($transaction);

            return $IntEventoApuestaMySqlDAO->insert($this);

    }

    /**
    * Realizar una consulta en la tabla de detalles de IntEventoApuesta 'IntEventoApuesta'
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
    public function getEventoApuestasCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

            $IntEventoApuestaMySqlDAO = new IntEventoApuestaMySqlDAO();

            $eventos = $IntEventoApuestaMySqlDAO->queryEventoApuestasCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

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