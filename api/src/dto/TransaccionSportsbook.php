<?php namespace Backend\dto;
use Backend\mysql\RegistroMySqlDAO;
use Backend\mysql\TransaccionSportsbookMySqlDAO;
use Exception;
/** 
* Clase 'TransaccionSportsbook'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'TransaccionSportsbook'
* 
* Ejemplo de uso: 
* $TransaccionSportsbook = new TransaccionSportsbook();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class TransaccionSportsbook
{

    /**
    * Representación de la columna 'transsportId' de la tabla 'TransaccionSportsbook'
    *
    * @var string
    */
    var $transsportId;

    /**
    * Representación de la columna 'usuarioId' de la tabla 'TransaccionSportsbook'
    *
    * @var string
    */
    var $usuarioId;

    /**
    * Representación de la columna 'productoId' de la tabla 'TransaccionSportsbook'
    *
    * @var string
    */
    var $productoId;

    /**
    * Representación de la columna 'vlrApuesta' de la tabla 'TransaccionSportsbook'
    *
    * @var string
    */
    var $vlrApuesta;

    /**
    * Representación de la columna 'vlrPremio' de la tabla 'TransaccionSportsbook'
    *
    * @var string
    */
    var $vlrPremio;

    /**
    * Representación de la columna 'estado' de la tabla 'TransaccionSportsbook'
    *
    * @var string
    */
    var $estado;

    /**
    * Representación de la columna 'premiado' de la tabla 'TransaccionSportsbook'
    *
    * @var string
    */
    var $premiado;

    /**
    * Representación de la columna 'ticketId' de la tabla 'TransaccionSportsbook'
    *
    * @var string
    */
    var $ticketId;

    /**
    * Representación de la columna 'transaccionId' de la tabla 'TransaccionSportsbook'
    *
    * @var string
    */
    var $transaccionId;

    /**
    * Representación de la columna 'fechaPago' de la tabla 'TransaccionSportsbook'
    *
    * @var string
    */
    var $fechaPago;

    /**
    * Representación de la columna 'mandante' de la tabla 'TransaccionSportsbook'
    *
    * @var string
    */
    var $mandante;

    /**
    * Representación de la columna 'clave' de la tabla 'TransaccionSportsbook'
    *
    * @var string
    */
    var $clave;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'TransaccionSportsbook'
    *
    * @var string
    */
    var $usucreaId;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'TransaccionSportsbook'
    *
    * @var string
    */
    var $usumodifId;

    /**
    * Representación de la columna 'tipo' de la tabla 'TransaccionSportsbook'
    *
    * @var string
    */
    var $tipo;

    /**
    * Representación de la columna 'gameReference' de la tabla 'TransaccionSportsbook'
    *
    * @var string
    */
    var $gameReference;

    /**
    * Representación de la columna 'cantLineas' de la tabla 'TransaccionSportsbook'
    *
    * @var string
    */
    var $cantLineas;

    /**
    * Representación de la columna 'betStatus' de la tabla 'TransaccionSportsbook'
    *
    * @var string
    */
    var $betStatus;

    /**
    * Representación de la columna 'premioPagado' de la tabla 'TransaccionSportsbook'
    *
    * @var string
    */
    var $premioPagado;

    /**
    * Representación de la columna 'dirIp' de la tabla 'TransaccionSportsbook'
    *
    * @var string
    */
    var $dirIp;

    /**
    * Representación de la columna 'eliminado' de la tabla 'TransaccionSportsbook'
    *
    * @var string
    */
    var $eliminado;

    /**
    * Representación de la columna 'freebet' de la tabla 'TransaccionSportsbook'
    *
    * @var string
    */
    var $freebet;

    /**
    * Representación de la columna 'betmode' de la tabla 'TransaccionSportsbook'
    *
    * @var string
    */
    var $betmode;

    /**
    * Representación de la columna 'fechaCierre' de la tabla 'TransaccionSportsbook'
    *
    * @var string
    */
    var $fechaCierre;

    /**
    * Representación de la columna 'fechaMaxpago' de la tabla 'TransaccionSportsbook'
    *
    * @var string
    */
    var $fechaMaxpago;

    /**
    * Representación de la columna 'fechaCrea' de la tabla 'TransaccionSportsbook'
    *
    * @var string
    */
    var $fechaCrea;

    /**
    * Representación de la columna 'fechaModif' de la tabla 'TransaccionSportsbook'
    *
    * @var string
    */
    var $fechaModif;

    /**
    * Representación de la columna 'session' de la tabla 'TransaccionSportsbook'
    *
    * @var string
    */
    var $session;



    /**
     * Representación de la columna 'transaccion_wallet' de la tabla 'TransaccionSportsbook'
     *
     * @var string
     */
    var $transaccionWallet;


    /**
    * Constructor de clase
    *
    *
    * @param String $transjuegoId transjuegoId
    * @param String $ticketId id del ticket
    * @param String $transaccionId id de la transacción
    *
    * @return no
    * @throws Exception si TransaccionSportsbook no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($transsportId="", $ticketId="", $transaccionId="")
    {
        if ($transsportId != "") 
        {

            $this->transsportId = $transsportId;

            $TransaccionSportsbookMySqlDAO = new TransaccionSportsbookMySqlDAO();

            $TransaccionSportsbook = $TransaccionSportsbookMySqlDAO->load($this->transsportId);


            if ($TransaccionSportsbook != null && $TransaccionSportsbook != "") 
            {
            
                $this->transsportId = $TransaccionSportsbook->transsportId;
                $this->usuarioId = $TransaccionSportsbook->usuarioId;
                $this->productoId = $TransaccionSportsbook->productoId;
                $this->vlrApuesta = $TransaccionSportsbook->vlrApuesta;
                $this->vlrPremio = $TransaccionSportsbook->vlrPremio;
                $this->tipo = $TransaccionSportsbook->tipo;
                $this->estado = $TransaccionSportsbook->estado;
                $this->premiado = $TransaccionSportsbook->premiado;
                $this->ticketId = $TransaccionSportsbook->ticketId;
                $this->transaccionId = $TransaccionSportsbook->transaccionId;
                $this->fechaPago = $TransaccionSportsbook->fechaPago;
                $this->mandante = $TransaccionSportsbook->mandante;
                $this->clave = $TransaccionSportsbook->clave;
                $this->usucreaId = $TransaccionSportsbook->usucreaId;
                $this->usumodifId = $TransaccionSportsbook->usumodifId;

                $this->gameReference = $TransaccionSportsbook->gameReference;
                $this->cantLineas = $TransaccionSportsbook->cantLineas;
                $this->betStatus = $TransaccionSportsbook->betStatus;
                $this->premioPagado = $TransaccionSportsbook->premioPagado;
                $this->dirIp = $TransaccionSportsbook->dirIp;
                $this->eliminado = $TransaccionSportsbook->eliminado;
                $this->freebet = $TransaccionSportsbook->freebet;
                $this->betmode = $TransaccionSportsbook->betmode;
                $this->fechaCierre = $TransaccionSportsbook->fechaCierre;
                $this->fechaMaxpago = $TransaccionSportsbook->fechaMaxpago;
                $this->fechaCrea = $TransaccionSportsbook->fechaCrea;
                $this->fechaModif = $TransaccionSportsbook->fechaModif;
                $this->session = $TransaccionSportsbook->session;
                $this->transaccionWallet = $TransaccionSportsbook->transaccionWallet;
            }
            else 
            {
                throw new Exception("No existe " . get_class($this), "63");
            }

        }
        elseif ($ticketId != "") 
        {

            $this->ticketId = $ticketId;

            $TransaccionSportsbookMySqlDAO = new TransaccionSportsbookMySqlDAO();

            $TransaccionSportsbook = $TransaccionSportsbookMySqlDAO->queryByTicketId($this->ticketId);

            $TransaccionSportsbook = $TransaccionSportsbook[0];

            if ($TransaccionSportsbook != null && $TransaccionSportsbook != "") 
            {
            
                $this->transsportId = $TransaccionSportsbook->transsportId;
                $this->usuarioId = $TransaccionSportsbook->usuarioId;
                $this->productoId = $TransaccionSportsbook->productoId;
                $this->vlrApuesta = $TransaccionSportsbook->vlrApuesta;
                $this->vlrPremio = $TransaccionSportsbook->vlrPremio;
                $this->tipo = $TransaccionSportsbook->tipo;
                $this->estado = $TransaccionSportsbook->estado;
                $this->premiado = $TransaccionSportsbook->premiado;
                $this->ticketId = $TransaccionSportsbook->ticketId;
                $this->transaccionId = $TransaccionSportsbook->transaccionId;
                $this->fechaPago = $TransaccionSportsbook->fechaPago;
                $this->mandante = $TransaccionSportsbook->mandante;
                $this->clave = $TransaccionSportsbook->clave;
                $this->usucreaId = $TransaccionSportsbook->usucreaId;
                $this->usumodifId = $TransaccionSportsbook->usumodifId;

                $this->gameReference = $TransaccionSportsbook->gameReference;
                $this->cantLineas = $TransaccionSportsbook->cantLineas;
                $this->betStatus = $TransaccionSportsbook->betStatus;
                $this->premioPagado = $TransaccionSportsbook->premioPagado;
                $this->dirIp = $TransaccionSportsbook->dirIp;
                $this->eliminado = $TransaccionSportsbook->eliminado;
                $this->freebet = $TransaccionSportsbook->freebet;
                $this->betmode = $TransaccionSportsbook->betmode;
                $this->fechaCierre = $TransaccionSportsbook->fechaCierre;
                $this->fechaMaxpago = $TransaccionSportsbook->fechaMaxpago;
                $this->fechaCrea = $TransaccionSportsbook->fechaCrea;
                $this->fechaModif = $TransaccionSportsbook->fechaModif;
                $this->session = $TransaccionSportsbook->session;
                $this->transaccionWallet = $TransaccionSportsbook->transaccionWallet;
          
            }
            else 
            {
                throw new Exception("No existe " . get_class($this), "63");
            }
        
        }
        elseif ($transaccionId != "") 
        {

            $this->transaccionId = $transaccionId;

            $TransaccionSportsbookMySqlDAO = new TransaccionSportsbookMySqlDAO();

            $TransaccionSportsbook = $TransaccionSportsbookMySqlDAO->queryByTransaccionId($this->transaccionId);

            $TransaccionSportsbook = $TransaccionSportsbook[0];

            if ($TransaccionSportsbook != null && $TransaccionSportsbook != "") 
            {
            
                $this->transsportId = $TransaccionSportsbook->transsportId;
                $this->usuarioId = $TransaccionSportsbook->usuarioId;
                $this->productoId = $TransaccionSportsbook->productoId;
                $this->vlrApuesta = $TransaccionSportsbook->vlrApuesta;
                $this->vlrPremio = $TransaccionSportsbook->vlrPremio;
                $this->tipo = $TransaccionSportsbook->tipo;
                $this->estado = $TransaccionSportsbook->estado;
                $this->premiado = $TransaccionSportsbook->premiado;
                $this->ticketId = $TransaccionSportsbook->ticketId;
                $this->transaccionId = $TransaccionSportsbook->transaccionId;
                $this->fechaPago = $TransaccionSportsbook->fechaPago;
                $this->mandante = $TransaccionSportsbook->mandante;
                $this->clave = $TransaccionSportsbook->clave;
                $this->usucreaId = $TransaccionSportsbook->usucreaId;
                $this->usumodifId = $TransaccionSportsbook->usumodifId;

                $this->gameReference = $TransaccionSportsbook->gameReference;
                $this->cantLineas = $TransaccionSportsbook->cantLineas;
                $this->betStatus = $TransaccionSportsbook->betStatus;
                $this->premioPagado = $TransaccionSportsbook->premioPagado;
                $this->dirIp = $TransaccionSportsbook->dirIp;
                $this->eliminado = $TransaccionSportsbook->eliminado;
                $this->freebet = $TransaccionSportsbook->freebet;
                $this->betmode = $TransaccionSportsbook->betmode;
                $this->fechaCierre = $TransaccionSportsbook->fechaCierre;
                $this->fechaMaxpago = $TransaccionSportsbook->fechaMaxpago;
                $this->fechaCrea = $TransaccionSportsbook->fechaCrea;
                $this->fechaModif = $TransaccionSportsbook->fechaModif;
                $this->session = $TransaccionSportsbook->session;
                $this->transaccionWallet = $TransaccionSportsbook->transaccionWallet;

            }
            else 
            {
                throw new Exception("No existe " . get_class($this), "63");
            }
        }

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
     * Obtener el campo productoId de un objeto
     *
     * @return String productoId productoId
     * 
     */
    public function getProductoId()
    {
        return $this->productoId;
    }

    /**
     * Modificar el campo 'productoId' de un objeto
     *
     * @param String $productoId productoId
     *
     * @return no
     *
     */
    public function setProductoId($productoId)
    {
        $this->productoId = $productoId;
    }

    /**
     * Obtener el campo vlrApuesta de un objeto
     *
     * @return String vlrApuesta vlrApuesta
     * 
     */
    public function getVlrApuesta()
    {
        return $this->vlrApuesta;
    }

    /**
     * Modificar el campo 'vlrApuesta' de un objeto
     *
     * @param String $vlrApuesta vlrApuesta
     *
     * @return no
     *
     */
    public function setVlrApuesta($vlrApuesta)
    {
        $this->vlrApuesta = $vlrApuesta;
    }

    /**
     * Obtener el campo vlrPremio de un objeto
     *
     * @return String vlrPremio vlrPremio
     * 
     */
    public function getVlrPremio()
    {
        return $this->vlrPremio;
    }

    /**
     * Modificar el campo 'vlrPremio' de un objeto
     *
     * @param String $vlrPremio vlrPremio
     *
     * @return no
     *
     */
    public function setVlrPremio($vlrPremio)
    {
        $this->vlrPremio = $vlrPremio;
    }

    /**
     * Obtener el campo estado de un objeto
     *
     * @return String estado estado
     * 
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Modificar el campo 'estado' de un objeto
     *
     * @param String $estado estado
     *
     * @return no
     *
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    /**
     * Obtener el campo premiado de un objeto
     *
     * @return String premiado premiado
     * 
     */
    public function getPremiado()
    {
        return $this->premiado;
    }

    /**
     * Modificar el campo 'premiado' de un objeto
     *
     * @param String $premiado premiado
     *
     * @return no
     *
     */
    public function setPremiado($premiado)
    {
        $this->premiado = $premiado;
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
     * Obtener el campo transjuegoId de un objeto
     *
     * @return String transjuegoId transjuegoId
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
     * Obtener el campo fechaPago de un objeto
     *
     * @return String fechaPago fechaPago
     * 
     */
    public function getFechaPago()
    {
        return $this->fechaPago;
    }

    /**
     * Modificar el campo 'fechaPago' de un objeto
     *
     * @param String $fechaPago fechaPago
     *
     * @return no
     *
     */
    public function setFechaPago($fechaPago)
    {
        $this->fechaPago = $fechaPago;
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
     * Obtener el campo clave de un objeto
     *
     * @return String clave clave
     * 
     */
    public function getClave()
    {
        return $this->clave;
    }

    /**
     * Modificar el campo 'clave' de un objeto
     *
     * @param String $clave clave
     *
     * @return no
     *
     */
    public function setClave($clave)
    {
        $this->clave = $clave;
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
     * Obtener el campo cantLineas de un objeto
     *
     * @return String cantLineas cantLineas
     * 
     */
    public function getCantLineas()
    {
        return $this->cantLineas;
    }

    /**
     * Modificar el campo 'cantLineas' de un objeto
     *
     * @param String $cantLineas cantLineas
     *
     * @return no
     *
     */
    public function setCantLineas($cantLineas)
    {
        $this->cantLineas = $cantLineas;
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
     * Obtener el campo premioPagado de un objeto
     *
     * @return String premioPagado premioPagado
     * 
     */
    public function getPremioPagado()
    {
        return $this->premioPagado;
    }

    /**
     * Modificar el campo 'premioPagado' de un objeto
     *
     * @param String $premioPagado premioPagado
     *
     * @return no
     *
     */
    public function setPremioPagado($premioPagado)
    {
        $this->premioPagado = $premioPagado;
    }

    /**
     * Obtener el campo dirIp de un objeto
     *
     * @return String dirIp dirIp
     * 
     */
    public function getDirIp()
    {
        return $this->dirIp;
    }

    /**
     * Modificar el campo 'dirIp' de un objeto
     *
     * @param String $dirIp dirIp
     *
     * @return no
     *
     */
    public function setDirIp($dirIp)
    {
        $this->dirIp = $dirIp;
    }

    /**
     * Obtener el campo eliminado de un objeto
     *
     * @return String eliminado eliminado
     * 
     */
    public function getEliminado()
    {
        return $this->eliminado;
    }

    /**
     * Modificar el campo 'eliminado' de un objeto
     *
     * @param String $eliminado eliminado
     *
     * @return no
     *
     */
    public function setEliminado($eliminado)
    {
        $this->eliminado = $eliminado;
    }

    /**
     * Obtener el campo freebet de un objeto
     *
     * @return String freebet freebet
     * 
     */
    public function getFreebet()
    {
        return $this->freebet;
    }

    /**
     * Modificar el campo 'freebet' de un objeto
     *
     * @param String $freebet freebet
     *
     * @return no
     *
     */
    public function setFreebet($freebet)
    {
        $this->freebet = $freebet;
    }

    /**
     * Obtener el campo betmode de un objeto
     *
     * @return String betmode betmode
     * 
     */
    public function getBetMode()
    {
        return $this->betmode;
    }

    /**
     * Modificar el campo 'betmode' de un objeto
     *
     * @param String $betmode betmode
     *
     * @return no
     *
     */
    public function setBetMode($betmode)
    {
        $this->betmode = $betmode;
    }

    /**
     * Obtener el campo fechaCierre de un objeto
     *
     * @return String fechaCierre fechaCierre
     * 
     */
    public function getFechaCierre()
    {
        return $this->fechaCierre;
    }

    /**
     * Modificar el campo 'fechaCierre' de un objeto
     *
     * @param String $fechaCierre fechaCierre
     *
     * @return no
     *
     */
    public function setFechaCierre($fechaCierre)
    {
        $this->fechaCierre = $fechaCierre;
    }

    /**
     * Obtener el campo fechaMaxpago de un objeto
     *
     * @return String fechaMaxpago fechaMaxpago
     * 
     */
    public function getFechaMaxpago()
    {
        return $this->fechaMaxpago;
    }

    /**
     * Modificar el campo 'fechaMaxpago' de un objeto
     *
     * @param String $fechaMaxpago fechaMaxpago
     *
     * @return no
     *
     */
    public function setFechaMaxpago($fechaMaxpago)
    {
        $this->fechaMaxpago = $fechaMaxpago;
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
     */
    public function setFechaModif($fechaModif)
    {
        $this->fechaModif = $fechaModif;
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
     * Obtener el campo session de un objeto
     *
     * @return String session session
     * 
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * Modificar el campo 'session' de un objeto
     *
     * @param String $session session
     *
     * @return no
     *
     */
    public function setSession($session)
    {
        $this->session = $session;
    }

    /**
     * @return string
     */
    public function getTransaccionWallet()
    {
        return $this->transaccionWallet;
    }

    /**
     * @param string $transaccionWallet
     */
    public function setTransaccionWallet($transaccionWallet)
    {
        $this->transaccionWallet = $transaccionWallet;
    }




    /**
    * Insertar un registro en la base de datos 
    *
    *
    * @param Objeto $transaction transacción
    *
    * @return boolean $ resultado de la consulta
    * @throws no
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function insert($transaction)
    {

        $TransaccionSportsbookMySqlDAO = new TransaccionSportsbookMySqlDAO($transaction);

        return $TransaccionSportsbookMySqlDAO->insert($this);

    }

    /**
    * Actualizar un registro en la base de datos 
    *
    *
    * @param Objeto $transaction transacción
    *
    * @return boolean $ resultado de la consulta
    * @throws no
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function update($transaction)
    {

        $TransaccionSportsbookMySqlDAO = new TransaccionSportsbookMySqlDAO($transaction);

        return $TransaccionSportsbookMySqlDAO->update($this);

    }


    /**
    * Actualizar un registro en la base de datos
    *
    *
    * @param Objeto $transaction transacción
    *
    * @return boolean $ resultado de la consulta
    * @throws no
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function Debit(Mandante $Mandante, UsuarioMandante $UsuarioMandante, Registro $Registro, $transaction)
    {

        if ($Mandante->propio == "S") {

            $Registro->setCreditosBase($Registro->getCreditosBase() - $this->vlrApuesta);
            $RegistroMySqlDAO = new RegistroMySqlDAO($transaction);
            //$RegistroMySqlDAO->update($Registro);
            $RegistroMySqlDAO->updateBalance($Registro,"",-$this->vlrApuesta,"","","");

        }
        else {

        }
    }

    /**
    * Consultar si existen registros
    *
    *
    * @param no
    *
    * @return boolean $ resultado de la consulta
    * @throws no
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function existsTransaccionId()
    {

        $TransaccionSportsbookMySqlDAO = new TransaccionSportsbookMySqlDAO();

        $TransaccionSportsbook = $TransaccionSportsbookMySqlDAO->queryByTransaccionId($this->transaccionId);

        if (oldCount($TransaccionSportsbook) > 0) {
            return true;
        }

        return false;

    }

    /**
    * Consultar si existen registros de tiquetes
    *
    *
    * @param String no
    *
    * @return boolean $ resultado de la consulta
    * @throws no
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function existsTicketId()
    {

        $TransaccionSportsbookMySqlDAO = new TransaccionSportsbookMySqlDAO();

        $TransaccionSportsbook = $TransaccionSportsbookMySqlDAO->existsTicketId($this->ticketId, $this->usuarioId);

        if (oldCount($TransaccionSportsbook) > 0) {
            return true;
        }

        return false;

    }

    /**
    * Pagarle el premio al usuario pasado como parámetro
    *
    *
    * @param Objeto $UsuarioMandante UsuarioMandante
    *
    * @return boolean $ resultado de la consulta
    * @throws no
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function pagar($UsuarioMandante)
    {
        if ($this->getPremiado() == "S") {

            if ($this->getPremioPagado() == "N") {

                $Mandante = new Mandante($UsuarioMandante->mandante);

                if($Mandante->propio == "S"){

                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                    $UsuarioPerfil = new UsuarioPerfil($Usuario->usuarioId);

                    $PuntoVenta = new PuntoVenta("",$UsuarioMandante->usuarioMandante);
                    $TransaccionSportsbookMySqlDAO = new TransaccionSportsbookMySqlDAO();
                    $Transaction = $TransaccionSportsbookMySqlDAO->getTransaction();

                    $this->setPremioPagado("S");
                    $this->setFechaPago(date('Y-m-d H:i:s', time()));

                    $TransaccionSportsbookMySqlDAO->update($this);

                    switch ($UsuarioPerfil->perfilId){

                        case "MAQUINAANONIMA":

                            $PuntoVenta->setBalanceCreditosBase($this->getVlrPremio(),$Transaction);

                            break;
                    }

                    $Transaction->commit();

                    return true;

                }else{

                }

            }
        }else{
        }
        return false;

    }

    /**
    * Realizar una consulta en la tabla de transacciones 'TransaccionSportsbook'
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
    * @throws Exception si la transacción no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getTransacciones($sidx,$sord,$start,$limit,$filters,$searchOn)
    {

        $TransaccionSportsbookMySqlDAO = new TransaccionSportsbookMySqlDAO();

        $Transaccion = $TransaccionSportsbookMySqlDAO->queryTransacciones($sidx,$sord,$start,$limit,$filters,$searchOn);

        if ($Transaccion != null && $Transaccion != "") {

            return $Transaccion;


        }
        else {
            throw new Exception("No existe " . get_class($this), "63");
        }


    }

    /**
    * Realizar una consulta en la tabla de transacciones 'TransaccionSportsbook'
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
    * @throws Exception si la transacción no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getTransaccionesCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping)
    {

        $TransaccionSportsbookMySqlDAO = new TransaccionSportsbookMySqlDAO();

        $transacciones = $TransaccionSportsbookMySqlDAO->queryTransaccionesCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping);

        if ($transacciones != null && $transacciones != "") {

            return $transacciones;


        }
        else {
            throw new Exception("No existe " . get_class($this), "63");
        }


    }



}

?>