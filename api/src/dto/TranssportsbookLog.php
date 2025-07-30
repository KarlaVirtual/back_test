<?php namespace Backend\dto;
namespace Backend\dto;
use Backend\mysql\TranssportsbookLogMySqlDAO;
use Exception;
/** 
* Clase 'TranssportsbookLog'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'TranssportsbookLog'
* 
* Ejemplo de uso: 
* $TranssportsbookLog = new TranssportsbookLog();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class TranssportsbookLog
{

    /**
    * Representación de la columna 'transsportlogId' de la tabla 'TranssportsbookLog'
    *
    * @var string
    */
    var $transsportlogId;

    /**
    * Representación de la columna 'transsportId' de la tabla 'TranssportsbookLog'
    *
    * @var string
    */
    var $transsportId;

    /**
    * Representación de la columna 'tipo' de la tabla 'TranssportsbookLog'
    *
    * @var string
    */
    var $tipo;

    /**
    * Representación de la columna 'transaccionId' de la tabla 'TranssportsbookLogMySqlDAO'
    *
    * @var string
    */
    var $transaccionId;

    /**
    * Representación de la columna 'tValue' de la tabla 'TranssportsbookLog'
    *
    * @var string
    */
    var $tValue;

    /**
    * Representación de la columna 'fechaCrea' de la tabla 'TranssportsbookLog'
    *
    * @var string
    */
    var $fechaCrea;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'TranssportsbookLog'
    *
    * @var string
    */
    var $usucreaId;

    /**
    * Representación de la columna 'fechaModif' de la tabla 'TranssportsbookLog'
    *
    * @var string
    */
    var $fechaModif;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'TranssportsbookLog'
    *
    * @var string
    */
    var $usumodifId;

    /**
    * Representación de la columna 'valor' de la tabla 'TranssportsbookLog'
    *
    * @var string
    */
    var $valor;

    /**
    * Representación de la columna 'usuarioId' de la tabla 'TranssportsbookLog'
    *
    * @var string
    */
    var $usuarioId;

    /**
    * Representación de la columna 'gameReference' de la tabla 'TranssportsbookLog'
    *
    * @var string
    */
    var $gameReference;
    
    /**
    * Representación de la columna 'betStatus' de la tabla 'TranssportsbookLog'
    *
    * @var string
    */
    var $betStatus;
    
    /**
    * Representación de la columna 'mandante' de la tabla 'TranssportsbookLog'
    *
    * @var string
    */
    var $mandante;





    /**
    * Constructor de clase
    *
    *
    * @param String $transsportlogId transsportlogId
    * @param String $transsportId transsportId
    * @param String $tipo tipo
    * @param String $transaccionId transaccionId
    *
    * @return no
    * @throws Exception si TranssportsbookLog no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($transsportlogId="",$transsportId="",$tipo="",$transaccionId="")
    {
        $this->transsportlogId = $transsportlogId;

        if ($transsportId != "" && $transaccionId !="") 
        {

            $this->transsportId = $transsportId;

            $TranssportsbookLogMySqlDAO = new TranssportsbookLogMySqlDAO();

            $TransaccionJuego = $TranssportsbookLogMySqlDAO->queryByTransjuegoIdAndTransaccionId($this->transsportId,$transaccionId);

            $TransaccionJuego=$TransaccionJuego[0];

            if ($TransaccionJuego != null && $TransaccionJuego != "") 
            {
                $this->transsportlogId = $TransaccionJuego->transsportlogId;
                $this->transsportId = $TransaccionJuego->transsportId;
                $this->tipo = $TransaccionJuego->tipo;
                $this->transaccionId = $TransaccionJuego->transaccionId;
                $this->tValue = $TransaccionJuego->tValue;
                $this->usucreaId = $TransaccionJuego->usucreaId;
                $this->usumodifId = $TransaccionJuego->usumodifId;
                $this->valor = $TransaccionJuego->valor;

                $this->usuarioId = $TransaccionJuego->usuarioId;
                $this->gameReference = $TransaccionJuego->gameReference;
                $this->betStatus = $TransaccionJuego->betStatus;
                $this->mandante = $TransaccionJuego->mandante;
            }
            else 
            {
                throw new Exception("No existe " . get_class($this), "65");
            }

        }
        elseif ($transsportId != "" && $tipo != "") 
        {

            $this->transsportId = $transsportId;

            $TranssportsbookLogMySqlDAO = new TranssportsbookLogMySqlDAO();

            $TransaccionJuego = $TranssportsbookLogMySqlDAO->queryByTransjuegoIdAndTipo($this->transsportId,$tipo);

            $TransaccionJuego=$TransaccionJuego[0];

            if ($TransaccionJuego != null && $TransaccionJuego != "") 
            {
            
                $this->transsportlogId = $TransaccionJuego->transsportlogId;
                $this->transsportId = $TransaccionJuego->transsportId;
                $this->tipo = $TransaccionJuego->tipo;
                $this->transaccionId = $TransaccionJuego->transaccionId;
                $this->tValue = $TransaccionJuego->tValue;
                $this->usucreaId = $TransaccionJuego->usucreaId;
                $this->usumodifId = $TransaccionJuego->usumodifId;
                $this->valor = $TransaccionJuego->valor;

                $this->usuarioId = $TransaccionJuego->usuarioId;
                $this->gameReference = $TransaccionJuego->gameReference;
                $this->betStatus = $TransaccionJuego->betStatus;
                $this->mandante = $TransaccionJuego->mandante;
            
            }
            else 
            {
                throw new Exception("No existe " . get_class($this), "65");
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
     * Obtener el campo transsportlogId de un objeto
     *
     * @return String transsportlogId transsportlogId
     * 
     */
    public function getTranssportlogId()
    {
        return $this->transsportlogId;
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
    public function getTranssportsbookLogsCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping)
    {

        $TranssportsbookLogMySqlDAO = new TranssportsbookLogMySqlDAO();

        $transacciones = $TranssportsbookLogMySqlDAO->queryTransaccionesCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping);

        if ($transacciones != null && $transacciones != "") 
        {
            return $transacciones;
        }
        else 
        {
            throw new Exception("No existe " . get_class($this), "65");
        }


    }

}
?>