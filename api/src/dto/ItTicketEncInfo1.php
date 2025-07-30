<?php 
namespace Backend\dto;
use Backend\mysql\ItTicketEncInfo1MySqlDAO;
use Exception;
/** 
* Clase 'ItTicketEncInfo1'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'ItTicketEncInfo1'
* 
* Ejemplo de uso: 
* $ItTicketEncInfo1 = new ItTicketEncInfo1();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class ItTicketEncInfo1
{


    /**
    * Representación de la columna 'itTicket2Id' de la tabla 'ItTicketEncInfo1'
    *
    * @var string
    */ 		
	var $itTicket2Id;

    /**
    * Representación de la columna 'tipo' de la tabla 'ItTicketEncInfo1'
    *
    * @var string
    */ 
	var $tipo;

    /**
    * Representación de la columna 'ticketId' de la tabla 'ItTicketEncInfo1'
    *
    * @var string
    */ 
	var $ticketId;

    /**
    * Representación de la columna 'valor' de la tabla 'ItTicketEncInfo1'
    *
    * @var string
    */ 
	var $valor;

    /**
    * Representación de la columna 'valor2' de la tabla 'ItTicketEncInfo1'
    *
    * @var string
    */
	var $valor2;


    /**
    * Representación de la columna 'fechaCrea' de la tabla 'ItTicketEncInfo1'
    *
    * @var string
    */ 
	var $fechaCrea;


    /**
    * Representación de la columna 'fechaModif' de la tabla 'ItTicketEncInfo1'
    *
    * @var string
    */ 
	var $fechaModif;


  
    /**
    * Constructor de clase
    *
    *
    *
    * @return no
    * @throws no
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($itTicket2Id = null, $TicketId="", $tipo="")
    {
        $ItTicketEncInfo1MySqlDAO = new ItTicketEncInfo1MySqlDAO();
        $ItTicketEncInfo1 = null;

        if($TicketId != "" && $tipo !=""){

            $ItTicketEncInfo1 = $ItTicketEncInfo1MySqlDAO->queryByTicketIdAndTipo($TicketId, $tipo);
            if (empty((array)$ItTicketEncInfo1)) Throw new Exception('No existe ' . get_class($this), 300044);

        }else if(!empty($itTicket2Id)) {

            $ItTicketEncInfo1 = $ItTicketEncInfo1MySqlDAO->load($itTicket2Id);
            if (empty((array)$ItTicketEncInfo1)) Throw new Exception('No existe ' . get_class($this), 300044);

        }

        //Todas las propiedades definidas en el readRow y el DTO serán cargadas por el foreach
        foreach ($ItTicketEncInfo1 as $propiedad => $valor) {
            $this->$propiedad = $valor;
        }
    }


    /**
     * Chequear el ticket con clave y id para
     * retornar el objeto entero
     *
     *
     * @param String ticket id del ticket
     * @param String clave clave
     *
     *
     * @return Objeto ticket Ticket
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */  
    public function checkTicket($ticket,$clave){
        $ItTicketEncInfo1MySqlDAO = new ItTicketEncInfo1MySqlDAO();
        $ticket = $ItTicketEncInfo1MySqlDAO->checkTicket($ticket,$clave);

        return $ticket;

    }


    /**
    * Realizar una consulta en la tabla de tiquetes 'Ticket'
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
    * @param String $grouping columna para agrupar
    *
    * @return Array resultado de la consulta
    * @throws Exception si los tickets no existen
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getTicketsCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping="", $joins = [], $groupingCount = false)
    {

        $ItTicketEncInfo1MySqlDAO = new ItTicketEncInfo1MySqlDAO();

        $tickets = $ItTicketEncInfo1MySqlDAO->queryTicketsCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping, $joins, $groupingCount);

        if ($tickets != null && $tickets != "") 
        {
            return $tickets;
        }
        else 
        {
            throw new Exception("No existe " . get_class($this), "01");
        }


    }
		
}
?>