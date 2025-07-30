<?php namespace Backend\dto;
/** 
* Clase 'ItTicketDet'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'ItTicketDet'
* 
* Ejemplo de uso: 
* $ItTicketDet = new ItTicketDet();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class ItTicketDet
{

    /**
    * Representación de la columna 'itTicketdetId' de la tabla 'Egreso'
    *
    * @var string
    */ 
	var $itTicketdetId;

    /**
    * Representación de la columna 'ticketId' de la tabla 'Egreso'
    *
    * @var string
    */ 
	var $ticketId;

    /**
     * Representación de la columna 'ticketId' de la tabla 'Egreso'
     *
     * @var string
     */
    var $itTicketId;

    /**
    * Representación de la columna 'apuesta' de la tabla 'Egreso'
    *
    * @var string
    */ 
	var $apuesta;

    /**
    * Representación de la columna 'agrupador' de la tabla 'Egreso'
    *
    * @var string
    */ 
	var $agrupador;

    /**
    * Representación de la columna 'logro' de la tabla 'Egreso'
    *
    * @var string
    */ 
	var $logro;

    /**
    * Representación de la columna 'opcion' de la tabla 'Egreso'
    *
    * @var string
    */ 
	var $opcion;

    /**
    * Representación de la columna 'mandante' de la tabla 'Egreso'
    *
    * @var string
    */ 
	var $mandante;

    /**
    * Representación de la columna 'apuestaId' de la tabla 'Egreso'
    *
    * @var string
    */ 
	var $apuestaId;

    /**
    * Representación de la columna 'agrupadorId' de la tabla 'Egreso'
    *
    * @var string
    */ 
	var $agrupadorId;

    /**
    * Representación de la columna 'fechaEvento' de la tabla 'Egreso'
    *
    * @var string
    */ 
	var $fechaEvento;

    /**
    * Representación de la columna 'horaEvento' de la tabla 'Egreso'
    *
    * @var string
    */ 
	var $horaEvento;


    /**
     * Representación de la columna 'ligaid' de la tabla 'Egreso'
     *
     * @var string
     */
    var $ligaid;

    /**
     * Representación de la columna 'ligaid' de la tabla 'Egreso'
     *
     * @var string
     */
    var $sportid;

    /**
     * Representación de la columna 'ligaid' de la tabla 'Egreso'
     *
     * @var string
     */
    var $matchid;


    /**
     * Representación de la columna 'ligaid' de la tabla 'Egreso'
     *
     * @var string
     */
    var $usucreaId;


    /**
     * Representación de la columna 'ligaid' de la tabla 'Egreso'
     *
     * @var string
     */
    var $usumodifId;


    /**
     * Representación de la columna 'ligaid' de la tabla 'Egreso'
     *
     * @var string
     */
    var $fechaCrea;


    /**
     * Representación de la columna 'ligaid' de la tabla 'Egreso'
     *
     * @var string
     */
    var $fechaModif;












    /**
     * Obtiene el valor de la propiedad 'itTicketdet_id de la tabla 'ItTicketDet'
     * @return string
     */
    public function getItTicketdetId()
    {
        return $this->itTicketdetId;
    }

    /**
     * Establece el valor de la propiedad 'itTicketdet_id de la tabla 'ItTicketDet'
     * @param string $itTicketId
     */
    public function setItTicketId(string $itTicketId)
    {
        $this->itTicketId = $itTicketId;
    }

    /**
     * Obtiene el valor de la propiedad 'ticket_id de la tabla 'ItTicketDet'
     * @return string
     */
    public function getItTicketId()
    {
        return $this->itTicketId;
    }

    /**
     * Establece el valor de la propiedad 'itTicketdet_id de la tabla 'ItTicketDet'
     * @param string $itTicketdetId
     */
    public function setItTicketdetId(string $itTicketdetId)
    {
        $this->itTicketdetId = $itTicketdetId;
    }


    /**
     * Obtiene el valor de la propiedad 'ticket_id' de la tabla 'ItTicketDet'
     * @return string
     */
    public function getTicketId()
    {
        return $this->ticketId;
    }

    /**
     * Establece el valor de la propiedad 'ticket_id' de la tabla 'ItTicketDet'
     * @param string $ticketId
     */
    public function setTicketId(string $ticketId)
    {
        $this->ticketId = $ticketId;
    }

    /**
     * Obtiene el valor de la propiedad 'apuesta' de la tabla 'ItTicketDet'
     * @return string
     */
    public function getApuesta()
    {
        return $this->apuesta;
    }

    /**
     * Establece el valor de la propiedad 'apuesta' de la tabla 'ItTicketDet'
     * @param string $apuesta
     */
    public function setApuesta(string $apuesta)
    {
        $this->apuesta = $apuesta;
    }

    /**
     * Obtiene el valor de la propiedad 'agrupador' de la tabla 'ItTicketDet'
     * @return string
     */
    public function getAgrupador()
    {
        return $this->agrupador;
    }

    /**
     * Establece el valor de la propiedad 'agrupador' de la tabla 'ItTicketDet'
     * @param string $agrupador
     */
    public function setAgrupador(string $agrupador)
    {
        $this->agrupador = $agrupador;
    }

    /**
     * Obtiene el valor de la propiedad 'logro' de la tabla 'ItTicketDet'
     * @return string
     */
    public function getLogro()
    {
        return $this->logro;
    }

    /**
     * Establece el valor de la propiedad 'logro' de la tabla 'ItTicketDet'
     * @param string $logro
     */
    public function setLogro(string $logro)
    {
        $this->logro = $logro;
    }

    /**
     * Obtiene el valor de la propiedad 'opcion' de la tabla 'ItTicketDet'
     * @return string
     */
    public function getOpcion()
    {
        return $this->opcion;
    }

    /**
     * Establece el valor de la propiedad 'opcion' de la tabla 'ItTicketDet'
     * @param string $opcion
     */
    public function setOpcion(string $opcion)
    {
        $this->opcion = $opcion;
    }

    /**
     * Obtiene el valor de la propiedad 'mandante' de la tabla 'ItTicketDet'
     * @return string
     */
    public function getMandante()
    {
        return $this->mandante;
    }

    /**
     * Establece el valor de la propiedad 'mandante' de la tabla 'ItTicketDet'
     * @param string $mandante
     */
    public function setMandante(string $mandante)
    {
        $this->mandante = $mandante;
    }

    /**
     * Obtiene el valor de la propiedad 'apuestaId' de la tabla 'ItTicketDet'
     * @return string
     */
    public function getApuestaId()
    {
        return $this->apuestaId;
    }

    /**
     * Establece el valor de la propiedad 'apuestaId' de la tabla 'ItTicketDet'
     * @param string $apuestaId
     */
    public function setApuestaId(string $apuestaId)
    {
        $this->apuestaId = $apuestaId;
    }

    /**
     * Obtiene el valor de la propiedad 'agrupador_id' de la tabla 'ItTicketDet'
     * @return string
     */
    public function getAgrupadorId()
    {
        return $this->agrupadorId;
    }

    /**
     * Establece el valor de la propiedad 'agrupador_id' de la tabla 'It_ticket_det'
     * @param string $agrupadorId
     */
    public function setAgrupadorId(string $agrupadorId)
    {
        $this->agrupadorId = $agrupadorId;
    }

    /**
     * Obtiene el valor de la propiedad 'fechaEvento' de la tabla 'ItTicketDet'
     * @return string
     */
    public function getFechaEvento()
    {
        return $this->fechaEvento;
    }

    /**
     * Establece el valor de la propiedad 'fechaEvento' de la tabla 'ItTicketDet'
     * @param string $fechaEvento
     */
    public function setFechaEvento(string $fechaEvento)
    {
        $this->fechaEvento = $fechaEvento;
    }

    /**
     * Obtiene el valor de la propiedad 'horaEvento' de la tabla 'ItTicketDet'
     * @return string
     */
    public function getHoraEvento()
    {
        return $this->horaEvento;
    }

    /**
     * Establece el valor de la propiedad 'horaEvento' de la tabla 'ItTicketDet'
     * @param string $horaEvento
     */
    public function setHoraEvento(string $horaEvento)
    {
        $this->horaEvento = $horaEvento;
    }


    /**
     * Obtiene el valor de la propiedad 'liga_id' de la tabla 'ItTicketDet'
     * @return string
     */
    public function getLigaid()
    {
        return $this->ligaid;
    }

    /**
     * Establece el valor de la propiedad 'liga_id' de la tabla 'ItTicketDet'
     * @param string $Ligaid
     */
    public function setLigaid(string $ligaid)
    {
        $this->ligaid = $ligaid;
    }



    /**
     * Obtiene el valor de la propiedad 'sport_id' de la tabla 'ItTicketDet'
     * @return string
     */
    public function getSportid()
    {
        return $this->sportid;
    }

    /**
     * Establece el valor de la propiedad 'sport_id' de la tabla 'ItTicketDet'
     * @param string $Sportid
     */
    public function setSportid(string $sportid)
    {
        $this->sportid = $sportid;
    }


    /**
     * Obtiene el valor de la propiedad 'match_id' de la tabla 'ItTicketDet'
     * @return string
     */
    public function getMatchid()
    {
        return $this->matchid;
    }

    /**
     * Establece el valor de la propiedad 'match_id' de la tabla 'ItTicketDet'
     * @param string $Matchid
     */
    public function setMatchid(string $matchid)
    {
        $this->matchid = $matchid;
    }



    /**
     * Obtiene el valor de la propiedad 'usucrea_id' de la tabla 'ItTicketDet'
     * @return string
     */
    public function getUsucreaId()
    {
        return $this->usucreaId;
    }



    /**
     * Obtiene el valor de la propiedad 'usumodif_id' de la tabla 'ItTicketDet'
     * @param string $usucreaId
     */
    public function setUsucreaId(string $usucreaId)
    {
        $this->usucreaId = $usucreaId;
    }


    /**
     * Obtiene el valor de la propiedad 'usumodif_id' de la tabla 'ItTicketDet'
     * @return string
     */
    public function getUsumodifId()
    {
        return $this->usumodifId;


    }

    /**
     * Establece el valor de la propiedad 'usumodif_id' de la tabla 'ItTicketDet'
     * @param string $usucreaId
     */
    public function setUsumodifId(string $usumodifId)
    {
        $this->usumodifId = $usumodifId;
    }


    /**
     * Establece el valor de la propiedad 'fechaCrea' de la tabla 'ItTicketDet'
     * @param string $usucreaId
     */
    public function setFechaCrea($fechaCrea) {
        $this->fechaCrea = $fechaCrea;


    }

    /**
     * Obtiene el valor de la propiedad 'fechaCrea' de la tabla 'ItTicketDet'
     * @return string
     */
    public function getFechaCrea() {
        return $this->fechaCrea;
    }

    /**
     * Establece el valor de la propiedad 'fechaModif' de la tabla 'ItTicketDet'
     * @param string $usucreaId
     */
    public function setFechaModif($fechaModif) {
        $this->fechaModif = $fechaModif;


    }

    /**
     * Obtiene el valor de la propiedad 'fechaModif' de la tabla 'ItTicketDet'
     * @return string
     */
    public function getFechaModif() {
        return $this->fechaModif;
    }

}
?>