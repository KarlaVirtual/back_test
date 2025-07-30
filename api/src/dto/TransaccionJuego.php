<?php namespace Backend\dto;
use Backend\mysql\RegistroMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Exception;
/** 
* Clase 'TransaccionJuego'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'TransaccionJuego'
* 
* Ejemplo de uso: 
* $TransaccionJuego = new TransaccionJuego();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class TransaccionJuego
{

    /**
    * Representación de la columna 'transjuegoId' de la tabla 'TransaccionJuego'
    *
    * @var string
    */
    var $transjuegoId;

    /**
    * Representación de la columna 'usuarioId' de la tabla 'TransaccionJuego'
    *
    * @var string
    */
    var $usuarioId;

    /**
    * Representación de la columna 'productoId' de la tabla 'TransaccionJuego'
    *
    * @var string
    */
    var $productoId;

    /**
    * Representación de la columna 'valorTicket' de la tabla 'TransaccionJuego'
    *
    * @var string
    */
    var $valorTicket;

    /**
    * Representación de la columna 'impuesto' de la tabla 'TransaccionJuego'
    *
    * @var string
    */
    var $impuesto;

    /**
    * Representación de la columna 'valorPremio' de la tabla 'TransaccionJuego'
    *
    * @var string
    */
    var $valorPremio;

    /**
    * Representación de la columna 'estado' de la tabla 'TransaccionJuego'
    *
    * @var string
    */
    var $estado;

    /**
    * Representación de la columna 'premiado' de la tabla 'TransaccionJuego'
    *
    * @var string
    */
    var $premiado;

    /**
    * Representación de la columna 'ticketId' de la tabla 'TransaccionJuego'
    *
    * @var string
    */
    var $ticketId;

    /**
    * Representación de la columna 'transaccionId' de la tabla 'TransaccionJuego'
    *
    * @var string
    */
    var $transaccionId;

    /**
    * Representación de la columna 'fechaPago' de la tabla 'TransaccionJuego'
    *
    * @var string
    */
    var $fechaPago;

    /**
    * Representación de la columna 'mandante' de la tabla 'TransaccionJuego'
    *
    * @var string
    */
    var $mandante;

    /**
    * Representación de la columna 'clave' de la tabla 'TransaccionJuego'
    *
    * @var string
    */
    var $clave;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'TransaccionJuego'
    *
    * @var string
    */
    var $usucreaId;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'TransaccionJuego'
    *
    * @var string
    */
    var $usumodifId;

    /**
    * Representación de la columna 'tipo' de la tabla 'TransaccionJuego'
    *
    * @var string
    */
    var $tipo;

    /**
     * Representación de la columna 'fechaPago' de la tabla 'TransaccionJuego'
     *
     * @var string
     */
    var $fechaCrea;

    /**
     * Representación de la columna 'fechaModif' de la tabla 'TransaccionJuego'
     *
     * @var string
     */
    var $fechaModif;

    /**
     * Representación de la columna 'valorPremio' de la tabla 'TransaccionJuego'
     *
     * @var string
     */
    var $valorGratis;

    /**
     * Representación de la columna 'premioPagado' de la tabla 'TransaccionJuego'
     *
     * @var string
     */
    var $premioPagado;

    public $transaction;


    /**
    * Constructor de clase
    *
    *
    * @param String $transjuegoId transjuegoId
    * @param String $ticketId id del ticket
    * @param String $transaccionId id de la transacción
    *
    * @return no
    * @throws Exception si TransaccionJuego no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($transjuegoId="", $ticketId="", $transaccionId="", $transaction = "")
    {
        $this->transaction = $transaction;

        if ($transjuegoId != "")
        {

            $this->transjuegoId = $transjuegoId;

            $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();

            $TransaccionJuego = $TransaccionJuegoMySqlDAO->load($this->transjuegoId);


            if ($TransaccionJuego != null && $TransaccionJuego != "") 
            {
                $this->transjuegoId = $TransaccionJuego->transjuegoId;
                $this->usuarioId = $TransaccionJuego->usuarioId;
                $this->productoId = $TransaccionJuego->productoId;
                $this->valorTicket = $TransaccionJuego->valorTicket;
                $this->impuesto = $TransaccionJuego->impuesto;
                $this->valorPremio = $TransaccionJuego->valorPremio;
                $this->tipo = $TransaccionJuego->tipo;
                $this->estado = $TransaccionJuego->estado;
                $this->premiado = $TransaccionJuego->premiado;
                $this->ticketId = $TransaccionJuego->ticketId;
                $this->transaccionId = $TransaccionJuego->transaccionId;
                $this->fechaPago = $TransaccionJuego->fechaPago;
                $this->mandante = $TransaccionJuego->mandante;
                $this->clave = $TransaccionJuego->clave;
                $this->usucreaId = $TransaccionJuego->usucreaId;
                $this->usumodifId = $TransaccionJuego->usumodifId;
                $this->valorGratis = $TransaccionJuego->valorGratis;
                $this->premioPagado = $TransaccionJuego->premioPagado;
            }
            else 
            {
                throw new Exception("No existe " . get_class($this), "28");
            }

        }
        elseif ($ticketId != "") 
        {

            $this->ticketId = $ticketId;

            $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO($this->transaction);

            $TransaccionJuego = $TransaccionJuegoMySqlDAO->queryByTicketId($this->ticketId);

            $TransaccionJuego = $TransaccionJuego[0];

            if ($TransaccionJuego != null && $TransaccionJuego != "") 
            {
                $this->transjuegoId = $TransaccionJuego->transjuegoId;
                $this->usuarioId = $TransaccionJuego->usuarioId;
                $this->productoId = $TransaccionJuego->productoId;
                $this->valorTicket = $TransaccionJuego->valorTicket;
                $this->impuesto = $TransaccionJuego->impuesto;
                $this->valorPremio = $TransaccionJuego->valorPremio;
                $this->tipo = $TransaccionJuego->tipo;
                $this->estado = $TransaccionJuego->estado;
                $this->premiado = $TransaccionJuego->premiado;
                $this->ticketId = $TransaccionJuego->ticketId;
                $this->transaccionId = $TransaccionJuego->transaccionId;
                $this->fechaPago = $TransaccionJuego->fechaPago;
                $this->mandante = $TransaccionJuego->mandante;
                $this->clave = $TransaccionJuego->clave;
                $this->usucreaId = $TransaccionJuego->usucreaId;
                $this->usumodifId = $TransaccionJuego->usumodifId;
                $this->valorGratis = $TransaccionJuego->valorGratis;
                $this->premioPagado = $TransaccionJuego->premioPagado;
            }
            else 
            {
                throw new Exception("No existe " . get_class($this), "28");
            }
        }
        elseif ($transaccionId != "") 
        {

            $this->transaccionId = $transaccionId;

            $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();

            $TransaccionJuego = $TransaccionJuegoMySqlDAO->queryByTransaccionId($this->transaccionId);

            $TransaccionJuego = $TransaccionJuego[0];

            if ($TransaccionJuego != null && $TransaccionJuego != "") 
            {
            
                $this->transjuegoId = $TransaccionJuego->transjuegoId;
                $this->usuarioId = $TransaccionJuego->usuarioId;
                $this->productoId = $TransaccionJuego->productoId;
                $this->valorTicket = $TransaccionJuego->valorTicket;
                $this->impuesto = $TransaccionJuego->impuesto;
                $this->valorPremio = $TransaccionJuego->valorPremio;
                $this->tipo = $TransaccionJuego->tipo;
                $this->estado = $TransaccionJuego->estado;
                $this->premiado = $TransaccionJuego->premiado;
                $this->ticketId = $TransaccionJuego->ticketId;
                $this->transaccionId = $TransaccionJuego->transaccionId;
                $this->fechaPago = $TransaccionJuego->fechaPago;
                $this->mandante = $TransaccionJuego->mandante;
                $this->clave = $TransaccionJuego->clave;
                $this->usucreaId = $TransaccionJuego->usucreaId;
                $this->usumodifId = $TransaccionJuego->usumodifId;
                $this->valorGratis = $TransaccionJuego->valorGratis;
                $this->premioPagado = $TransaccionJuego->premioPagado;
            
            }
            else 
            {
                throw new Exception("No existe " . get_class($this), "28");
            }
        }
    }





    /**
     * Obtener el campo transjuegoId de un objeto
     *
     * @return String transjuegoId transjuegoId
     * 
     */
    public function getTransjuegoId()
    {
        return $this->transjuegoId;
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
     * Obtener el campo valorTicket de un objeto
     *
     * @return String valorTicket valorTicket
     * 
     */
    public function getValorTicket()
    {
        return $this->valorTicket;
    }

    /**
     * Modificar el campo 'valorTicket' de un objeto
     *
     * @param String $valorTicket valorTicket
     *
     * @return no
     *
     */
    public function setValorTicket($valorTicket)
    {
        $this->valorTicket = $valorTicket;
    }

    /**
     * Obtener el campo impuesto de un objeto
     *
     * @return String impuesto impuesto
     * 
     */
    public function getImpuesto()
    {
        return $this->impuesto;
    }

    /**
     * Modificar el campo 'impuesto' de un objeto
     *
     * @param String $impuesto impuesto
     *
     * @return no
     *
     */
    public function setImpuesto($impuesto)
    {
        $this->impuesto = $impuesto;
    }

    /**
     * Obtener el campo valorPremio de un objeto
     *
     * @return String valorPremio valorPremio
     * 
     */
    public function getValorPremio()
    {
        return $this->valorPremio;
    }

    /**
     * Modificar el campo 'valorPremio' de un objeto
     *
     * @param String $valorPremio valorPremio
     *
     * @return no
     *
     */
    public function setValorPremio($valorPremio)
    {
        $this->valorPremio = $valorPremio;
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
     * @return string
     */
    public function getValorGratis()
    {
        return $this->valorGratis;
    }

    /**
     * @param string $valorGratis
     */
    public function setValorGratis($valorGratis)
    {
        $this->valorGratis = $valorGratis;
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

        $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO($transaction);

        return $TransaccionJuegoMySqlDAO->insert($this);

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

        $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO($transaction);

        return $TransaccionJuegoMySqlDAO->update($this);

    }

    /**
    * Actualizar un registro en la base de datos
    *
    *
    * @param Objeto $Mandante Mandante
    * @param Objeto $UsuarioMandante UsuarioMandante
    * @param Objeto $Registro Registro
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

        if ($Mandante->propio == "S") 
        {
            $Registro->setCreditosBase($Registro->getCreditosBase() - $this->valorTicket);
            $RegistroMySqlDAO = new RegistroMySqlDAO($transaction);
            $RegistroMySqlDAO->updateBalance($Registro,"",-$this->valorTicket,"","","");
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

        $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();

        $TransaccionJuego = $TransaccionJuegoMySqlDAO->queryByTransaccionId($this->transaccionId);

        if (oldCount($TransaccionJuego) > 0) 
        {
            return true;
        }

        return false;

    }

    /**
    * Consultar si existen registros
    *
    *
    * @param String $respuestaCODE respuestaCODE
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

        $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();

        $TransaccionJuego = $TransaccionJuegoMySqlDAO->existsTicketId($this->ticketId, $this->usuarioId);

        if (oldCount($TransaccionJuego) > 0) 
        {
            return true;
        }

        return false;

    }

    /**
    * Realizar una consulta en la tabla de transacciones 'TransaccionJuego'
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

        $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();

        $Transaccion = $TransaccionJuegoMySqlDAO->queryTransacciones($sidx,$sord,$start,$limit,$filters,$searchOn);

        if ($Transaccion != null && $Transaccion != "") 
        {
            return $Transaccion;
        }
        else 
        {
            throw new Exception("No existe " . get_class($this), "01");
        }


    }

    /**
    * Realizar una consulta en la tabla de transacciones 'TransaccionJuego'
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
    * @param String $having condición
    *
    * @return Array resultado de la consulta
    * @throws Exception si las transacciones no existen
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getTransaccionesCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping,$having="",$withCount=true,$forceTimeDimension=false)
    {

        $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();

        $transacciones = $TransaccionJuegoMySqlDAO->queryTransaccionesCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping,$having,$withCount,$forceTimeDimension);

        if ($transacciones != null && $transacciones != "") 
        {
            return $transacciones;
        }
        else 
        {
            throw new Exception("No existe " . get_class($this), "01");
        }


    }

    /**
     * Obtiene transacciones personalizadas basadas en los parámetros proporcionados.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Índice de ordenación.
     * @param string $sord Orden de clasificación (ascendente o descendente).
     * @param int $start Índice de inicio para la paginación.
     * @param int $limit Límite de registros a obtener.
     * @param string $filters Filtros aplicados a la consulta.
     * @param bool $searchOn Indica si la búsqueda está activada.
     * @param bool $grouping Indica si se debe agrupar los resultados.
     * @return array|null Retorna un array de transacciones si existen, de lo contrario lanza una excepción.
     * @throws Exception Si no existen transacciones, lanza una excepción con el mensaje correspondiente.
     */
    public function getTransaccionesCustom2($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping)
    {

        $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();

        $transacciones = $TransaccionJuegoMySqlDAO->queryTransaccionesCustom2($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping);

        if ($transacciones != null && $transacciones != "")
        {
            return $transacciones;
        }
        else
        {
            throw new Exception("No existe " . get_class($this), "01");
        }

    }

    /**
     * Obtiene las transacciones personalizadas de automatización.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Índice de ordenación.
     * @param string $sord Orden de clasificación (ascendente o descendente).
     * @param int $start Inicio de los resultados.
     * @param int $limit Límite de resultados.
     * @param array $filters Filtros a aplicar en la consulta.
     * @param bool $searchOn Indica si la búsqueda está activada.
     * @param string $grouping Agrupación de resultados.
     * @param string $having Condición HAVING para la consulta.
     * @return array|null Lista de transacciones obtenidas.
     * @throws Exception Si no existen transacciones.
     */
    public function getTransaccionesCustomAutomation($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping,$having="")
    {

        $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO($this->transaction);

        $transacciones = $TransaccionJuegoMySqlDAO->queryTransaccionesCustomAutomation($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping,$having);

        if ($transacciones != null && $transacciones != "")
        {
            return $transacciones;
        }
        else
        {
            throw new Exception("No existe " . get_class($this), "01");
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
    public function checkTicket($ticket, $clave)
    {

        $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();
        $ticket = $TransaccionJuegoMySqlDAO->checkTicket($ticket, $clave);

        return $ticket;

    }



}

?>
