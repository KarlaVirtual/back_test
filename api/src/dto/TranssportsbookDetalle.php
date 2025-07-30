<?php namespace Backend\dto;
use Backend\mysql\TranssportsbookDetalleMySqlDAO;
use Exception;
/** 
* Clase 'TranssportsbookDetalle'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'TranssportsbookDetalle'
* 
* Ejemplo de uso: 
* $TranssportsbookDetalle = new TranssportsbookDetalle();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class TranssportsbookDetalle
{

    /**
    * Representación de la columna 'transsportdetId' de la tabla 'TranssportsbookApi'
    *
    * @var string
    */
    var $transsportdetId;

    /**
    * Representación de la columna 'transsportId' de la tabla 'TranssportsbookApi'
    *
    * @var string
    */
    var $transsportId;

    /**
    * Representación de la columna 'apuesta' de la tabla 'TranssportsbookApi'
    *
    * @var string
    */
    var $apuesta;

    /**
    * Representación de la columna 'agrupador' de la tabla 'TranssportsbookApi'
    *
    * @var string
    */
    var $agrupador;

    /**
    * Representación de la columna 'logro' de la tabla 'TranssportsbookApi'
    *
    * @var string
    */
    var $logro;

    /**
    * Representación de la columna 'opcion' de la tabla 'TranssportsbookApi'
    *
    * @var string
    */
    var $opcion;

    /**
    * Representación de la columna 'apuestaId' de la tabla 'TranssportsbookApi'
    *
    * @var string
    */
    var $apuestaId;

    /**
    * Representación de la columna 'ticketId' de la tabla 'TranssportsbookApi'
    *
    * @var string
    */
    var $ticketId;

    /**
    * Representación de la columna 'agrupadorId' de la tabla 'TranssportsbookApi'
    *
    * @var string
    */
    var $agrupadorId;

    /**
    * Representación de la columna 'fechaEvento' de la tabla 'TranssportsbookApi'
    *
    * @var string
    */
    var $fechaEvento;

    /**
    * Representación de la columna 'mandante' de la tabla 'TranssportsbookApi'
    *
    * @var string
    */
    var $mandante;

    /**
    * Representación de la columna 'matchid' de la tabla 'TranssportsbookApi'
    *
    * @var string
    */
    var $matchid;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'TranssportsbookApi'
    *
    * @var string
    */
    var $usucreaId;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'TranssportsbookApi'
    *
    * @var string
    */
    var $usumodifId;

    /**
    * Representación de la columna 'sportid' de la tabla 'TranssportsbookApi'
    *
    * @var string
    */
    var $sportid;

    /**
    * Representación de la columna 'fechaCrea' de la tabla 'TranssportsbookApi'
    *
    * @var string
    */
    var $fechaCrea;

    /**
    * Representación de la columna 'fechaModif' de la tabla 'TranssportsbookApi'
    *
    * @var string
    */
    var $fechaModif;


    /**
    * Constructor de clase
    *
    *
    * @param String $transsportdetId transsportdetId
    * @param String $ticketId ticketId
    * @param String $agrupadorId agrupadorId
    *
    * @return no
    * @throws Exception si TranssportsbookDetalle no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($transsportdetId="", $ticketId="", $agrupadorId="")
    {
        if ($transsportdetId != "") {

            $this->transsportdetId = $transsportdetId;

            $TranssportsbookDetalleMySqlDAO = new TranssportsbookDetalleMySqlDAO();

            $TranssportsbookDetalle = $TranssportsbookDetalleMySqlDAO->load($this->transsportdetId);


            if ($TranssportsbookDetalle != null && $TranssportsbookDetalle != "") {
                $this->transsportdetId = $TranssportsbookDetalle->transsportdetId;
                $this->transsportId = $TranssportsbookDetalle->transsportId;
                $this->apuesta = $TranssportsbookDetalle->apuesta;
                $this->agrupador = $TranssportsbookDetalle->agrupador;
                $this->logro = $TranssportsbookDetalle->logro;
                $this->sportid = $TranssportsbookDetalle->sportid;
                $this->opcion = $TranssportsbookDetalle->opcion;
                $this->apuestaId = $TranssportsbookDetalle->apuestaId;
                $this->ticketId = $TranssportsbookDetalle->ticketId;
                $this->agrupadorId = $TranssportsbookDetalle->agrupadorId;
                $this->fechaEvento = $TranssportsbookDetalle->fechaEvento;
                $this->mandante = $TranssportsbookDetalle->mandante;
                $this->matchid = $TranssportsbookDetalle->matchid;
                $this->usucreaId = $TranssportsbookDetalle->usucreaId;
                $this->usumodifId = $TranssportsbookDetalle->usumodifId;
                $this->sportid = $TranssportsbookDetalle->sportid;
                $this->fechaCrea = $TranssportsbookDetalle->fechaCrea;
                $this->fechaModif = $TranssportsbookDetalle->fechaModif;
            }
            else {
                throw new Exception("No existe " . get_class($this), "64");
            }

        }
        elseif ($ticketId != "") {

            $this->ticketId = $ticketId;

            $TranssportsbookDetalleMySqlDAO = new TranssportsbookDetalleMySqlDAO();

            $TranssportsbookDetalle = $TranssportsbookDetalleMySqlDAO->queryByTicketId($this->ticketId);

            $TranssportsbookDetalle = $TranssportsbookDetalle[0];

            if ($TranssportsbookDetalle != null && $TranssportsbookDetalle != "") {
                $this->transsportdetId = $TranssportsbookDetalle->transsportdetId;
                $this->transsportId = $TranssportsbookDetalle->transsportId;
                $this->apuesta = $TranssportsbookDetalle->apuesta;
                $this->agrupador = $TranssportsbookDetalle->agrupador;
                $this->logro = $TranssportsbookDetalle->logro;
                $this->sportid = $TranssportsbookDetalle->sportid;
                $this->opcion = $TranssportsbookDetalle->opcion;
                $this->apuestaId = $TranssportsbookDetalle->apuestaId;
                $this->ticketId = $TranssportsbookDetalle->ticketId;
                $this->agrupadorId = $TranssportsbookDetalle->agrupadorId;
                $this->fechaEvento = $TranssportsbookDetalle->fechaEvento;
                $this->mandante = $TranssportsbookDetalle->mandante;
                $this->matchid = $TranssportsbookDetalle->matchid;
                $this->usucreaId = $TranssportsbookDetalle->usucreaId;
                $this->usumodifId = $TranssportsbookDetalle->usumodifId;
                $this->sportid = $TranssportsbookDetalle->sportid;
                $this->fechaCrea = $TranssportsbookDetalle->fechaCrea;
                $this->fechaModif = $TranssportsbookDetalle->fechaModif;
            }
            else {
                throw new Exception("No existe " . get_class($this), "64");
            }
        }
        elseif ($agrupadorId != "") {

            $this->agrupadorId = $agrupadorId;

            $TranssportsbookDetalleMySqlDAO = new TranssportsbookDetalleMySqlDAO();

            $TranssportsbookDetalle = $TranssportsbookDetalleMySqlDAO->queryByTransaccionId($this->agrupadorId);

            $TranssportsbookDetalle = $TranssportsbookDetalle[0];

            if ($TranssportsbookDetalle != null && $TranssportsbookDetalle != "") {
                $this->transsportdetId = $TranssportsbookDetalle->transsportdetId;
                $this->transsportId = $TranssportsbookDetalle->transsportId;
                $this->apuesta = $TranssportsbookDetalle->apuesta;
                $this->agrupador = $TranssportsbookDetalle->agrupador;
                $this->logro = $TranssportsbookDetalle->logro;
                $this->sportid = $TranssportsbookDetalle->sportid;
                $this->opcion = $TranssportsbookDetalle->opcion;
                $this->apuestaId = $TranssportsbookDetalle->apuestaId;
                $this->ticketId = $TranssportsbookDetalle->ticketId;
                $this->agrupadorId = $TranssportsbookDetalle->agrupadorId;
                $this->fechaEvento = $TranssportsbookDetalle->fechaEvento;
                $this->mandante = $TranssportsbookDetalle->mandante;
                $this->matchid = $TranssportsbookDetalle->matchid;
                $this->usucreaId = $TranssportsbookDetalle->usucreaId;
                $this->usumodifId = $TranssportsbookDetalle->usumodifId;
                $this->sportid = $TranssportsbookDetalle->sportid;
                $this->fechaCrea = $TranssportsbookDetalle->fechaCrea;
                $this->fechaModif = $TranssportsbookDetalle->fechaModif;
            }
            else {
                throw new Exception("No existe " . get_class($this), "64");
            }
        }
    }





    /**
     * Obtener el campo transsportId de un objeto
     *
     * @return String transsportId transsportId
     * 
     */
    public function getTranssportId()
    {
        return $this->transsportId;
    }

    /**
     * Modificar el campo 'transsportId' de un objeto
     *
     * @param String $transsportId transsportId
     *
     * @return no
     *
     */
    public function setTranssportId($transsportId)
    {
        $this->transsportId = $transsportId;
    }

    /**
     * Obtener el campo apuesta de un objeto
     *
     * @return String apuesta apuesta
     * 
     */
    public function getApuesta()
    {
        return $this->apuesta;
    }

    /**
     * Modificar el campo 'apuesta' de un objeto
     *
     * @param String $apuesta apuesta
     *
     * @return no
     *
     */
    public function setApuesta($apuesta)
    {
        $this->apuesta = $apuesta;
    }

    /**
     * Obtener el campo agrupador de un objeto
     *
     * @return String agrupador agrupador
     * 
     */
    public function getAgrupador()
    {
        return $this->agrupador;
    }

    /**
     * Modificar el campo 'agrupador' de un objeto
     *
     * @param String $agrupador agrupador
     *
     * @return no
     *
     */
    public function setAgrupador($agrupador)
    {
        $this->agrupador = $agrupador;
    }

    /**
     * Obtener el campo logro de un objeto
     *
     * @return String logro logro
     * 
     */
    public function getLogro()
    {
        return $this->logro;
    }

    /**
     * Modificar el campo 'logro' de un objeto
     *
     * @param String $logro logro
     *
     * @return no
     *
     */
    public function setLogro($logro)
    {
        $this->logro = $logro;
    }

    /**
     * Obtener el campo opcion de un objeto
     *
     * @return String opcion opcion
     * 
     */
    public function getOpcion()
    {
        return $this->opcion;
    }

    /**
     * Modificar el campo 'opcion' de un objeto
     *
     * @param String $opcion opcion
     *
     * @return no
     *
     */
    public function setOpcion($opcion)
    {
        $this->opcion = $opcion;
    }

    /**
     * Obtener el campo apuestaId de un objeto
     *
     * @return String apuestaId apuestaId
     * 
     */
    public function getApuestaId()
    {
        return $this->apuestaId;
    }

    /**
     * Modificar el campo 'apuestaId' de un objeto
     *
     * @param String $apuestaId apuestaId
     *
     * @return no
     *
     */
    public function setApuestaId($apuestaId)
    {
        $this->apuestaId = $apuestaId;
    }

    /**
     * Obtener el campo ticketId de un objeto
     *
     * @return String ticketId ticketId
     * 
     */
    public function getTicketId()
    {
        return $this->ticketId;
    }

    /**
     * Modificar el campo 'ticketId' de un objeto
     *
     * @param String $ticketId ticketId
     *
     * @return no
     *
     */
    public function setTicketId($ticketId)
    {
        $this->ticketId = $ticketId;
    }

    /**
     * Obtener el campo agrupadorId de un objeto
     *
     * @return String agrupadorId agrupadorId
     * 
     */
    public function getAgrupadorId()
    {
        return $this->agrupadorId;
    }

    /**
     * Modificar el campo 'agrupadorId' de un objeto
     *
     * @param String $agrupadorId agrupadorId
     *
     * @return no
     *
     */
    public function setAgrupadorId($agrupadorId)
    {
        $this->agrupadorId = $agrupadorId;
    }

    /**
     * Obtener el campo fechaEvento de un objeto
     *
     * @return String fechaEvento fechaEvento
     * 
     */
    public function getFechaEvento()
    {
        return $this->fechaEvento;
    }

    /**
     * Modificar el campo 'fechaEvento' de un objeto
     *
     * @param String $fechaEvento fechaEvento
     *
     * @return no
     *
     */
    public function setFechaEvento($fechaEvento)
    {
        $this->fechaEvento = $fechaEvento;
    }

    /**
     * Obtener el campo mandante de un objeto
     *
     * @return String mandante mandante
     * 
     */
    public function getMandante()
    {
        return $this->mandante;
    }

    /**
     * Modificar el campo 'mandante' de un objeto
     *
     * @param String $mandante mandante
     *
     * @return no
     *
     */
    public function setMandante($mandante)
    {
        $this->mandante = $mandante;
    }

    /**
     * Obtener el campo matchid de un objeto
     *
     * @return String matchid matchid
     * 
     */
    public function getMatchid()
    {
        return $this->matchid;
    }

    /**
     * Modificar el campo 'matchid' de un objeto
     *
     * @param String $matchid matchid
     *
     * @return no
     *
     */
    public function setMatchid($matchid)
    {
        $this->matchid = $matchid;
    }

    /**
     * Obtener el campo usucreaId de un objeto
     *
     * @return String usucreaId usucreaId
     * 
     */
    public function getUsucreaId()
    {
        return $this->usucreaId;
    }

    /**
     * Modificar el campo 'usucreaId' de un objeto
     *
     * @param String $usucreaId usucreaId
     *
     * @return no
     *
     */
    public function setUsucreaId($usucreaId)
    {
        $this->usucreaId = $usucreaId;
    }

    /**
     * Obtener el campo usumodifId de un objeto
     *
     * @return String usumodifId usumodifId
     * 
     */
    public function getUsumodifId()
    {
        return $this->usumodifId;
    }

    /**
     * Modificar el campo 'usumodifId' de un objeto
     *
     * @param String $usumodifId usumodifId
     *
     * @return no
     *
     */
    public function setUsumodifId($usumodifId)
    {
        $this->usumodifId = $usumodifId;
    }

    /**
     * Obtener el campo sportid de un objeto
     *
     * @return String sportid sportid
     * 
     */
    public function getSportid()
    {
        return $this->sportid;
    }

    /**
     * Modificar el campo 'sportid' de un objeto
     *
     * @param String $sportid sportid
     *
     * @return no
     *
     */
    public function setSportid($sportid)
    {
        $this->sportid = $sportid;
    }

    /**
     * Obtener el campo fechaCrea de un objeto
     *
     * @return String fechaCrea fechaCrea
     * 
     */
    public function getFechaCrea()
    {
        return $this->fechaCrea;
    }

    /**
     * Modificar el campo 'fechaCrea' de un objeto
     *
     * @param String $fechaCrea fechaCrea
     *
     * @return no
     *
     */
    public function setFechaCrea($fechaCrea)
    {
        $this->fechaCrea = $fechaCrea;
    }

    /**
     * Obtener el campo fechaModif de un objeto
     *
     * @return String fechaModif fechaModif
     * 
     */
    public function getFechaModif()
    {
        return $this->fechaModif;
    }

    /**
     * Modificar el campo 'fechaModif' de un objeto
     *
     * @param String $fechaModif fechaModif
     *
     * @return no
     *
     */    public function setFechaModif($fechaModif)
    {
        $this->fechaModif = $fechaModif;
    }

    /**
     * Obtener el campo transsportdetId de un objeto
     *
     * @return String transsportdetId transsportdetId
     * 
     */
    public function getTranssportdetId()
    {
        return $this->transsportdetId;
    }




    /**
    * Realizar una consulta en la tabla de TranssportsbookApi 'TranssportsbookApi'
    * de una manera personalizada
    *
    *
    * @param String $sidx columna para ordenar
    * @param String $sord orden los datos asc | desc
    * @param String $start inicio de la consulta
    * @param String $limit limite de la consulta
    * @param String $filters condiciones de la consulta 
    * @param boolean $searchOn utilizar los filtros o no
    *
    * @return Array resultado de la consulta
    * @throws Exception si las transacciones no existen
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getTransacciones($sidx,$sord,$start,$limit,$filters,$searchOn)
    {

        $TranssportsbookDetalleMySqlDAO = new TranssportsbookDetalleMySqlDAO();

        $Transaccion = $TranssportsbookDetalleMySqlDAO->queryTransacciones($sidx,$sord,$start,$limit,$filters,$searchOn);

        if ($Transaccion != null && $Transaccion != "") 
        {
            return $Transaccion;
        }
        else 
        {
            throw new Exception("No existe " . get_class($this), "64");
        }


    }

    /**
    * Realizar una consulta en la tabla de TranssportsbookApi 'TranssportsbookApi'
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
    * @throws Exception si las transacciones no existen
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getTransaccionesCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping="")
    {

        $TranssportsbookDetalleMySqlDAO = new TranssportsbookDetalleMySqlDAO();

        $transacciones = $TranssportsbookDetalleMySqlDAO->queryTransaccionesCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping);

        if ($transacciones != null && $transacciones != "") 
        {
            return $transacciones;
        }
        else 
        {
            throw new Exception("No existe " . get_class($this), "64");
        }


    }



}

?>