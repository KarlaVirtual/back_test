<?php
namespace Backend\dto;
use Backend\mysql\ItTransaccionMySqlDAO;
use Exception;
/** 
* Clase 'ItTransaccion'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'ItTransaccion'
* 
* Ejemplo de uso: 
* $ItTransaccion = new ItTransaccion();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class ItTransaccion
{
		

    /**
    * Representación de la columna 'itCuentatransId' de la tabla 'ItTransaccion'
    *
    * @var string
    */ 	
	var $itCuentatransId;

    /**
    * Representación de la columna 'transaccionId' de la tabla 'ItTransaccion'
    *
    * @var string
    */ 
	var $transaccionId;

    /**
    * Representación de la columna 'ticketId' de la tabla 'ItTransaccion'
    *
    * @var string
    */ 
	var $ticketId;

    /**
    * Representación de la columna 'valor' de la tabla 'ItTransaccion'
    *
    * @var string
    */ 
	var $valor;

    /**
    * Representación de la columna 'usuarioId' de la tabla 'ItTransaccion'
    *
    * @var string
    */ 
	var $usuarioId;

    /**
    * Representación de la columna 'gameReference' de la tabla 'ItTransaccion'
    *
    * @var string
    */ 
	var $gameReference;

    /**
    * Representación de la columna 'betStatus' de la tabla 'ItTransaccion'
    *
    * @var string
    */ 
	var $betStatus;

    /**
    * Representación de la columna 'fechaCrea' de la tabla 'ItTransaccion'
    *
    * @var string
    */ 
	var $fechaCrea;

    /**
    * Representación de la columna 'horaCrea' de la tabla 'ItTransaccion'
    *
    * @var string
    */ 
	var $horaCrea;

    /**
    * Representación de la columna 'mandante' de la tabla 'ItTransaccion'
    *
    * @var string
    */ 
	var $mandante;

    /**
    * Representación de la columna 'tipo' de la tabla 'ItTransaccion'
    *
    * @var string
    */ 
	var $tipo;




    /**
     * Constructor de clase
     *
     *
     * @param String $itCuentatransId itCuentatransId
     * @param String $ticketId ticketId
     * @param String $tipo tipo
     * @param String $transaccionId transaccionId
     *
     * @return no
     * @throws Exception si ItTransaccion no existe
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function __construct($itCuentatransId="",$ticketId="",$tipo="",$transaccionId="")
    {
        $this->itCuentatransId = $itCuentatransId;

        if ($transaccionId !="")
        {

            $this->ticketId = $ticketId;

            $ItTransaccionMySqlDAO = new ItTransaccionMySqlDAO();

            $ItTransaccion = $ItTransaccionMySqlDAO->queryByTransaccionId($transaccionId);

            $ItTransaccion=$ItTransaccion[0];

            if ($ItTransaccion != null && $ItTransaccion != "")
            {
                $this->itCuentatransId = $ItTransaccion->itCuentatransId;
                $this->ticketId = $ItTransaccion->ticketId;
                $this->tipo = $ItTransaccion->tipo;
                $this->transaccionId = $ItTransaccion->transaccionId;
                $this->tValue = $ItTransaccion->tValue;
                $this->usucreaId = $ItTransaccion->usucreaId;
                $this->usumodifId = $ItTransaccion->usumodifId;
                $this->valor = $ItTransaccion->valor;

                $this->usuarioId = $ItTransaccion->usuarioId;
                $this->gameReference = $ItTransaccion->gameReference;
                $this->betStatus = $ItTransaccion->betStatus;
                $this->mandante = $ItTransaccion->mandante;
            }
            else
            {
                throw new Exception("No existe " . get_class($this), "115");
            }

        }
        elseif ($itCuentatransId != "")
        {

            $this->ticketId = $ticketId;

            $ItTransaccionMySqlDAO = new ItTransaccionMySqlDAO();

            $ItTransaccion = $ItTransaccionMySqlDAO->load($itCuentatransId);


            if ($ItTransaccion != null && $ItTransaccion != "")
            {

                $this->itCuentatransId = $ItTransaccion->itCuentatransId;
                $this->ticketId = $ItTransaccion->ticketId;
                $this->tipo = $ItTransaccion->tipo;
                $this->transaccionId = $ItTransaccion->transaccionId;
                $this->tValue = $ItTransaccion->tValue;
                $this->usucreaId = $ItTransaccion->usucreaId;
                $this->usumodifId = $ItTransaccion->usumodifId;
                $this->valor = $ItTransaccion->valor;

                $this->usuarioId = $ItTransaccion->usuarioId;
                $this->gameReference = $ItTransaccion->gameReference;
                $this->betStatus = $ItTransaccion->betStatus;
                $this->mandante = $ItTransaccion->mandante;

            }
            else
            {
                throw new Exception("No existe " . get_class($this), "115");
            }

        }
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
     * Obtener el campo tipo de un objeto
     *
     * @return String tipo tipo
     *
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Modificar el campo 'tipo' de un objeto
     *
     * @param String $tipo tipo
     *
     * @return no
     *
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    /**
     * Obtener el campo transaccionId de un objeto
     *
     * @return String transaccionId transaccionId
     *
     */
    public function getTransaccionId()
    {
        return $this->transaccionId;
    }

    /**
     * Modificar el campo 'transaccionId' de un objeto
     *
     * @param String $transaccionId transaccionId
     *
     * @return no
     *
     */
    public function setTransaccionId($transaccionId)
    {
        $this->transaccionId = $transaccionId;
    }

    /**
     * Obtener el campo tValue de un objeto
     *
     * @return String tValue tValue
     *
     */
    public function getTValue()
    {
        return $this->tValue;
    }

    /**
     * Modificar el campo 'tValue' de un objeto
     *
     * @param String $tValue tValue
     *
     * @return no
     *
     */
    public function setTValue($tValue)
    {
        $this->tValue = $tValue;
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
     */
    public function setFechaModif($fechaModif)
    {
        $this->fechaModif = $fechaModif;
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
     * Obtener el campo valor de un objeto
     *
     * @return String valor valor
     *
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Modificar el campo 'valor' de un objeto
     *
     * @param String $valor valor
     *
     * @return no
     *
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    /**
     * Obtener el campo usuarioId de un objeto
     *
     * @return String usuarioId usuarioId
     *
     */
    public function getUsuarioId()
    {
        return $this->usuarioId;
    }

    /**
     * Modificar el campo 'usuarioId' de un objeto
     *
     * @param String $usuarioId usuarioId
     *
     * @return no
     *
     */
    public function setUsuarioId($usuarioId)
    {
        $this->usuarioId = $usuarioId;
    }

    /**
     * Obtener el campo gameReference de un objeto
     *
     * @return String gameReference gameReference
     *
     */
    public function getGameReference()
    {
        return $this->gameReference;
    }

    /**
     * Modificar el campo 'gameReference' de un objeto
     *
     * @param String $gameReference gameReference
     *
     * @return no
     *
     */
    public function setGameReference($gameReference)
    {
        $this->gameReference = $gameReference;
    }

    /**
     * Obtener el campo betStatus de un objeto
     *
     * @return String betStatus betStatus
     *
     */
    public function getBetStatus()
    {
        return $this->betStatus;
    }

    /**
     * Modificar el campo 'betStatus' de un objeto
     *
     * @param String $betStatus betStatus
     *
     * @return no
     *
     */
    public function setBetStatus($betStatus)
    {
        $this->betStatus = $betStatus;
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
     * Obtener el campo itCuentatransId de un objeto
     *
     * @return String itCuentatransId itCuentatransId
     *
     */
    public function getitCuentatransId()
    {
        return $this->itCuentatransId;
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
    public function getItTransaccionsCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping)
    {

        $ItTransaccionMySqlDAO = new ItTransaccionMySqlDAO();

        $transacciones = $ItTransaccionMySqlDAO->queryTransaccionesCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping);

        if ($transacciones != null && $transacciones != "")
        {
            return $transacciones;
        }
        else
        {
            throw new Exception("No existe " . get_class($this), "115");
        }


    }


}
?>