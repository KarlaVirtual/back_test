<?php namespace Backend\dto;

use Backend\mysql\ItTicketEncMySqlDAO;
use Exception;

/**
 * Clase 'ItTicketEnc'
 *
 * Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
 * para la tabla 'ItTicketEnc'
 *
 * Ejemplo de uso:
 * $ItTicketEnc = new ItTicketEnc();
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class ItTicketEnc
{

    /**
     * Representación de la columna 'itTicketId' de la tabla 'ItTicketEnc'
     *
     * @var string
     */
    var $itTicketId;

    /**
     * Representación de la columna 'transaccionId' de la tabla 'ItTicketEnc'
     *
     * @var string
     */
    var $transaccionId;

    /**
     * Representación de la columna 'ticketId' de la tabla 'ItTicketEnc'
     *
     * @var string
     */
    var $ticketId;

    /**
     * Representación de la columna 'vlrApuesta' de la tabla 'ItTicketEnc'
     *
     * @var string
     */
    var $vlrApuesta;

    /**
     * Representación de la columna 'impuestoApuesta' de la tabla 'ItTicketEnc'
     *
     * @var string
     */
    var $impuestoApuesta;

    /**
     * Representación de la columna 'vlrPremio' de la tabla 'ItTicketEnc'
     *
     * @var string
     */
    var $vlrPremio;

    /**
     * Representación de la columna 'vlrPremio' de la tabla 'ItTicketEnc'
     *
     * @var string
     */
    var $usuarioId;

    /**
     * Representación de la columna 'gameReference' de la tabla 'ItTicketEnc'
     *
     * @var string
     */
    var $gameReference;

    /**
     * Representación de la columna 'egresoId' de la tabla 'ItTicketEnc'
     *
     * @var string
     */
    var $betStatus;

    /**
     * Representación de la columna 'cantLineas' de la tabla 'ItTicketEnc'
     *
     * @var string
     */
    var $cantLineas;

    /**
     * Representación de la columna 'fechaCrea' de la tabla 'ItTicketEnc'
     *
     * @var string
     */
    var $fechaCrea;

    /**
     * Representación de la columna 'fechaCrea' de la tabla 'ItTicketEnc'
     *
     * @var string
     */
    var $fechaCreaTime;

    /**
     * Representación de la columna 'horaCrea' de la tabla 'ItTicketEnc'
     *
     * @var string
     */
    var $horaCrea;

    /**
     * Representación de la columna 'premiado' de la tabla 'ItTicketEnc'
     *
     * @var string
     */
    var $premiado;

    /**
     * Representación de la columna 'premioPagado' de la tabla 'ItTicketEnc'
     *
     * @var string
     */
    var $premioPagado;

    /**
     * Representación de la columna 'fechaPago' de la tabla 'ItTicketEnc'
     *
     * @var string
     */
    var $fechaPago;

    /**
     * Representación de la columna 'horaPago' de la tabla 'ItTicketEnc'
     *
     * @var string
     */
    var $horaPago;

    /**
     * Representación de la columna 'mandante' de la tabla 'ItTicketEnc'
     *
     * @var string
     */
    var $mandante;

    /**
     * Representación de la columna 'estado' de la tabla 'ItTicketEnc'
     *
     * @var string
     */
    var $estado;

    /**
     * Representación de la columna 'eliminado' de la tabla 'ItTicketEnc'
     *
     * @var string
     */
    var $eliminado;

    /**
     * Representación de la columna 'fechaMaxpago' de la tabla 'ItTicketEnc'
     *
     * @var string
     */
    var $fechaMaxpago;

    /**
     * Representación de la columna 'fechaCierre' de la tabla 'ItTicketEnc'
     *
     * @var string
     */
    var $fechaCierre;

    /**
     * Representación de la columna 'horaCierre' de la tabla 'ItTicketEnc'
     *
     * @var string
     */
    var $horaCierre;

    /**
     * Representación de la columna 'clave' de la tabla 'ItTicketEnc'
     *
     * @var string
     */
    var $clave;

    /**
     * Representación de la columna 'usumodificaId' de la tabla 'ItTicketEnc'
     *
     * @var string
     */
    var $usumodificaId;


    /**
     * Representación de la columna 'usumodificaId' de la tabla 'ItTicketEnc'
     *
     * @var string
     */

    var $usucreaId;

    /**
     * Representación de la columna 'fechaModifica' de la tabla 'ItTicketEnc'
     *
     * @var string
     */
    var $fechaModifica;

    /**
     * Representación de la columna 'beneficiarioId' de la tabla 'ItTicketEnc'
     *
     * @var string
     */
    var $beneficiarioId;

    /**
     * Representación de la columna 'tipoBeneficiario' de la tabla 'ItTicketEnc'
     *
     * @var string
     */
    var $tipoBeneficiario;

    /**
     * Representación de la columna 'dirIp' de la tabla 'ItTicketEnc'
     *
     * @var string
     */
    var $dirIp;

    /**
     * Representación de la columna 'dirIp' de la tabla 'ItTicketEnc'
     *
     * @var string
     */
    var $freebet;

    /**
     * Representación de la columna 'dirIp' de la tabla 'ItTicketEnc'
     *
     * @var string
     */
    var $impuesto;

    /**
     * Representación de la columna 'dirIp' de la tabla 'ItTicketEnc'
     *
     * @var string
     */
    var $betmode;

    /**
     * Representación de la columna 'dirIp' de la tabla 'ItTicketEnc'
     *
     * @var string
     */
    var $transaccionWallet;

    /**
     * Representación de la columna 'dirIp' de la tabla 'ItTicketEnc'
     *
     * @var string
     */
    var $wallet;

    /**
     * Representación de la columna 'dirIp' de la tabla 'ItTicketEnc'
     *
     * @var string
     */
    var $valorPremioPrevio;

    /**
     * Representación de la columna 'saldo_creditos' de la tabla 'ItTicketEnc'
     * @var
     */
    var $saldoCreditos;
    /**
     * Representación de la columna 'saldo_creditos_base' de la tabla 'ItTicketEnc'
     * @var
     */
    var $saldoCreditosBase;
    /**
     * Representación de la columna 'saldo_bonos' de la tabla 'ItTicketEnc'
     * @var
     */
    var $saldoBonos;
    /**
     * Representación de la columna 'saldo_free' de la tabla 'ItTicketEnc'
     * @var
     */
    var $saldoFree;


    /**
     * Representación de la columna 'dirIp' de la tabla 'ItTicketEnc'
     *
     * @var string
     */

    public $transaction;

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
    public function __construct($ticketId = "", $transaction = "")
    {
        $this->transaction = $transaction;

        if ($ticketId != "") {

            $ItTicketEncMySqlDAO = new ItTicketEncMySqlDAO();
            $Ticket = $ItTicketEncMySqlDAO->loadByTicketId($ticketId);

            $this->success = false;

            if ($Ticket != null && $Ticket != "") {

                $this->itTicketId = $Ticket->itTicketId;
                $this->transaccionId = $Ticket->transaccionId;
                $this->ticketId = $Ticket->ticketId;
                $this->vlrApuesta = $Ticket->vlrApuesta;
                $this->impuestoApuesta = $Ticket->impuestoApuesta;
                $this->vlrPremio = $Ticket->vlrPremio;
                $this->usuarioId = $Ticket->usuarioId;
                $this->gameReference = $Ticket->gameReference;
                $this->betStatus = $Ticket->betStatus;
                $this->cantLineas = $Ticket->cantLineas;
                $this->fechaCrea = $Ticket->fechaCrea;
                $this->fechaCreaTime = $Ticket->fechaCreaTime;
                $this->horaCrea = $Ticket->horaCrea;
                $this->premiado = $Ticket->premiado;
                $this->premioPagado = $Ticket->premioPagado;
                $this->fechaPago = $Ticket->fechaPago;
                $this->horaPago = $Ticket->horaPago;
                $this->mandante = $Ticket->mandante;
                $this->estado = $Ticket->estado;
                $this->eliminado = $Ticket->eliminado;
                $this->fechaMaxpago = $Ticket->fechaMaxpago;
                $this->fechaCierre = $Ticket->fechaCierre;
                $this->horaCierre = $Ticket->horaCierre;
                $this->clave = $Ticket->clave;
                $this->usumodificaId = $Ticket->usumodificaId;
                $this->fechaModifica = $Ticket->fechaModifica;
                $this->beneficiarioId = $Ticket->beneficiarioId;
                $this->tipoBeneficiario = $Ticket->tipoBeneficiario;
                $this->dirIp = $Ticket->dirIp;
                $this->betmode = $Ticket->betmode;
                $this->impuesto = $Ticket->impuesto;
                $this->saldoCreditos = $Ticket->saldoCreditos;
                $this->saldoCreditosBase = $Ticket->saldoCreditosBase;
                $this->saldoBonos = $Ticket->saldoBonos;
                $this->saldoFree = $Ticket->saldoFree;
                $this->freebet = $Ticket->freebet;

            } else {
                throw new Exception("No existe " . get_class($this), "24");

            }
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

        $ItTicketEncMySqlDAO = new ItTicketEncMySqlDAO();
        $ticket = $ItTicketEncMySqlDAO->checkTicket($ticket, $clave);

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
     * @param String $having condición
     *
     * @return Array resultado de la consulta
     * @throws Exception si los tickets no existen
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function getTicketsCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping = "", $having = "", $withCount = true, $daydimensionFecha = 0, $forceTimeDimension = false, $typeDimension = 0, $isInnerConce = false)
    {

        $ItTicketEncMySqlDAO = new ItTicketEncMySqlDAO();

        $tickets = $ItTicketEncMySqlDAO->queryTicketsCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping, $having, $withCount, $daydimensionFecha, $forceTimeDimension, $typeDimension, $isInnerConce);

        if ($tickets != null && $tickets != "") {
            return $tickets;
        } else {
            throw new Exception("No existe " . get_class($this), "01");
        }


    }


    /**
     * Obtiene tickets personalizados con varios parámetros de filtrado y ordenación.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Índice de ordenación.
     * @param string $sord Orden de clasificación (ASC o DESC).
     * @param int $start Índice de inicio para la paginación.
     * @param int $limit Límite de registros para la paginación.
     * @param array $filters Filtros aplicados a la consulta.
     * @param bool $searchOn Indica si la búsqueda está activada.
     * @param string $grouping Agrupación de resultados.
     * @param string $having Condiciones de agrupación.
     * @param bool $withCount Indica si se debe incluir el conteo de registros.
     * @param int $daydimensionFecha Dimensión de fecha para el día.
     * @param bool $forceTimeDimension Forzar dimensión de tiempo.
     * @param int $typeDimension Tipo de dimensión.
     * @param bool $isInnerConce Indica si es una consulta interna.
     * @param array $ArrayTransaction Transacciones adicionales.
     * @return array|null Retorna los tickets obtenidos o lanza una excepción si no existen.
     * @throws Exception Si no existen tickets.
     */
    public function getTicketsCustom3($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping = "", $having = "", $withCount = true, $daydimensionFecha = 0, $forceTimeDimension = false, $typeDimension = 0, $isInnerConce = false, $ArrayTransaction)
    {

        $ItTicketEncMySqlDAO = new ItTicketEncMySqlDAO();

        $tickets = $ItTicketEncMySqlDAO->queryTicketsCustom3($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping, $having, $withCount, $daydimensionFecha, $forceTimeDimension, $typeDimension, $isInnerConce, $ArrayTransaction);

        if ($tickets != null && $tickets != "") {
            return $tickets;
        } else {
            throw new Exception("No existe " . get_class($this), "01");
        }


    }

    /**
     * Obtiene los tickets personalizados con automatización.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Índice de ordenación.
     * @param string $sord Orden de clasificación (ascendente o descendente).
     * @param int $start Posición inicial para la paginación.
     * @param int $limit Número de registros a obtener.
     * @param array $filters Filtros a aplicar en la consulta.
     * @param bool $searchOn Indica si la búsqueda está activada.
     * @param string $grouping (Opcional) Agrupación de resultados.
     * @param string $having (Opcional) Condiciones de agrupación.
     * @return array Tickets obtenidos de la consulta.
     * @throws Exception Si no se encuentran tickets.
     */
    public function getTicketsCustomAutomation($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping = "", $having = "")
    {

        $ItTicketEncMySqlDAO = new ItTicketEncMySqlDAO($this->transaction);

        $tickets = $ItTicketEncMySqlDAO->queryTicketsCustomAutomation($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping, $having);

        if ($tickets != null && $tickets != "") {
            return $tickets;
        } else {
            throw new Exception("No existe " . get_class($this), "01");
        }


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
     * @param String $having condición
     * @param boolean $withNull condición de nulos
     *
     * @return Array resultado de la consulta
     * @throws Exception si los tickets no existen
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function getTicketsCustom2($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping = "", $having = "", $withNull = false)
    {

        $ItTicketEncMySqlDAO = new ItTicketEncMySqlDAO();

        $tickets = $ItTicketEncMySqlDAO->queryTicketsCustom2($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping, $having, $withNull);

        if ($tickets != null && $tickets != "") {
            return $tickets;
        } else {
            throw new Exception("No existe " . get_class($this), "01");
        }


    }


    /**
     * Realizar una consulta en la tabla de TicketsGGRC 'TicketsGGRC'
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
     * @throws Exception si los tickets no existen
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function getTicketsGGRCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping = "", $having = "")
    {

        $ItTicketEncMySqlDAO = new ItTicketEncMySqlDAO();

        $tickets = $ItTicketEncMySqlDAO->queryGGRCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping, $having);

        if ($tickets != null && $tickets != "") {
            return $tickets;
        } else {
            throw new Exception("No existe " . get_class($this), "01");
        }


    }

    /**
     * Realizar una consulta en la tabla de TicketDetalles 'TicketDetalles'
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
    public function getTicketDetallesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping = "")
    {

        $ItTicketEncMySqlDAO = new ItTicketEncMySqlDAO();

        $tickets = $ItTicketEncMySqlDAO->queryTicketDetallesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping);

        if ($tickets != null && $tickets != "") {
            return $tickets;
        } else {
            throw new Exception("No existe " . get_class($this), "01");
        }

    }

    /**
     * Realizar una consulta en la tabla de transacciones 'Transaction'
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
     * @throws Exception si los tickets no existen
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function getTicketTransactionsCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping = "", $having = "")
    {

        $ItTicketEncMySqlDAO = new ItTicketEncMySqlDAO();

        $tickets = $ItTicketEncMySqlDAO->queryTicketTransactionsCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping, $having);

        if ($tickets != null && $tickets != "") {
            return $tickets;
        } else {
            throw new Exception("No existe " . get_class($this), "01");
        }


    }

    /**
     * Ejecuta una consulta SQL utilizando una transacción específica.
     *
     * @param mixed $transaccion La transacción a utilizar para la consulta.
     * @param string $sql La consulta SQL a ejecutar.
     * @return mixed El resultado de la consulta SQL.
     */
    public function execQuery($transaccion, $sql)
    {

        $ItTicketEncMySqlDAO = new ItTicketEncMySqlDAO($transaccion);
        $return = $ItTicketEncMySqlDAO->querySQL($sql);

        //$return = json_decode(json_encode($return), FALSE);

        return $return;

    }

    /**
     * Verifica si existe un ticket con el ID especificado.
     *
     * @return bool true si el ticket existe, false en caso contrario.
     */
    public function existsTicketId()
    {

        $ItTicketEncMySqlDAO = new ItTicketEncMySqlDAO();

        $ItTicketEnc = $ItTicketEncMySqlDAO->existsTicketId($this->ticketId, $this->usuarioId);

        if (count($ItTicketEnc) > 0) {
            return true;
        }

        return false;

    }


    /**
     * Obtiene el campo 'itTicketId' de la tabla 'ItTicketEnc'
     * @return string
     */
    public function getItTicketId()
    {
        return $this->itTicketId;
    }

    /**
     * Establece el campo 'itTicketId' de la tabla 'ItTicketEnc'
     * @param string $itTicketId
     */
    public function setItTicketId(string $itTicketId)
    {
        $this->itTicketId = $itTicketId;
    }

    /**
     * Obtiene el campo 'transaccionId' de la tabla 'ItTicketEnc'
     * @return string
     */
    public function getTransaccionId()
    {
        return $this->transaccionId;
    }

    /**
     * Establece el campo 'transaccionId' de la tabla 'ItTicketEnc'
     * @param string $transaccionId
     */
    public function setTransaccionId(string $transaccionId)
    {
        $this->transaccionId = $transaccionId;
    }

    /**
     * Obtiene el campo 'ticketId' de la tabla 'ItTicketEnc'
     * @return string
     */
    public function getTicketId()
    {
        return $this->ticketId;
    }

    /**
     * Establece el campo 'ticketId' de la tabla 'ItTicketEnc'
     * @param string $ticketId
     */
    public function setTicketId(string $ticketId)
    {
        $this->ticketId = $ticketId;
    }

    /**
     * Obtiene el campo 'vlrApuesta' de la tabla 'ItTicketEnc'
     * @return string
     */
    public function getVlrApuesta()
    {
        return $this->vlrApuesta;
    }

    /**
     * Establece el campo 'vlrApuesta' de la tabla 'ItTicketEnc'
     * @param string $vlrApuesta
     */
    public function setVlrApuesta(string $vlrApuesta)
    {
        $this->vlrApuesta = $vlrApuesta;
    }

    /**
     * Obtiene el campo 'impuestoApuesta' de la tabla 'ItTicketEnc'
     * @return string
     */
    public function getImpuestoApuesta()
    {
        return $this->impuestoApuesta;
    }

    /**
     * Establece el campo 'impuestoApuesta' de la tabla 'ItTicketEnc'
     * @param string $impuestoApuesta
     */
    public function setImpuestoApuesta(string $impuestoApuesta)
    {
        $this->impuestoApuesta = $impuestoApuesta;
    }

    /**
     * Obtiene el campo 'vlrPremio' de la tabla 'ItTicketEnc'
     * @return string
     */
    public function getVlrPremio()
    {
        return $this->vlrPremio;
    }

    /**
     * Establece el campo 'vlrPremio' de la tabla 'ItTicketEnc'
     * @param string $vlrPremio
     */
    public function setVlrPremio(string $vlrPremio)
    {
        $this->vlrPremio = $vlrPremio;
    }

    /**
     * Obtiene el campo 'usuarioId' de la tabla 'ItTicketEnc'
     * @return string
     */
    public function getUsuarioId()
    {
        return $this->usuarioId;
    }

    /**
     * Establece el campo 'usuarioId' de la tabla 'ItTicketEnc'
     * @param string $usuarioId
     */
    public function setUsuarioId(string $usuarioId)
    {
        $this->usuarioId = $usuarioId;
    }

    /**
     * Obtiene el campo 'gameReference' de la tabla 'ItTicketEnc'
     * @return string
     */
    public function getGameReference()
    {
        return $this->gameReference;
    }

    /**
     * Establece el campo 'gameReference' de la tabla 'ItTicketEnc'
     * @param string $gameReference
     */
    public function setGameReference(string $gameReference)
    {
        $this->gameReference = $gameReference;
    }

    /**
     * Obtiene el campo 'betStatus' de la tabla 'ItTicketEnc'
     * @return string
     */
    public function getBetStatus()
    {
        return $this->betStatus;
    }

    /**
     * Establece el campo 'betStatus' de la tabla 'ItTicketEnc'
     * @param string $betStatus
     */
    public function setBetStatus(string $betStatus)
    {
        $this->betStatus = $betStatus;
    }

    /**
     * Obtiene el campo 'cantLineas' de la tabla 'ItTicketEnc'
     * @return string
     */
    public function getCantLineas()
    {
        return $this->cantLineas;
    }

    /**
     * Establece el campo 'cantLineas' de la tabla 'ItTicketEnc'
     * @param string $cantLineas
     */
    public function setCantLineas(string $cantLineas)
    {
        $this->cantLineas = $cantLineas;
    }

    /**
     * Obtiene el campo 'fechaCrea' de la tabla 'ItTicketEnc'
     * @return string
     */
    public function getFechaCrea()
    {
        return $this->fechaCrea;
    }

    /**
     * Establece el campo 'fechaCrea' de la tabla 'ItTicketEnc'
     * @param string $fechaCrea
     */
    public function setFechaCrea(string $fechaCrea)
    {
        $this->fechaCrea = $fechaCrea;
    }

    /**
     * Obtiene el campo 'fechaCreaTime' de la tabla 'ItTicketEnc'
     * @return string
     */
    public function getHoraCrea()
    {
        return $this->horaCrea;
    }

    /**
     * Establece el campo 'horaCrea' de la tabla 'ItTicketEnc'
     * @param string $horaCrea
     */
    public function setHoraCrea(string $horaCrea)
    {
        $this->horaCrea = $horaCrea;
    }

    /**
     * Obtiene el campo 'premiado' de la tabla 'ItTicketEnc'
     * @return string
     */
    public function getPremiado()
    {
        return $this->premiado;
    }

    /**
     * Establece el campo 'premiado' de la tabla 'ItTicketEnc'
     * @param string $premiado
     */
    public function setPremiado(string $premiado)
    {
        $this->premiado = $premiado;
    }

    /**
     * Obtiene el campo 'premioPagado' de la tabla 'ItTicketEnc'
     * @return string
     */
    public function getPremioPagado()
    {
        return $this->premioPagado;
    }

    /**
     * Establece el campo 'premioPagado' de la tabla 'ItTicketEnc'
     * @param string $premioPagado
     */
    public function setPremioPagado(string $premioPagado)
    {
        $this->premioPagado = $premioPagado;
    }

    /**
     * Obtiene el campo 'fechaPago' de la tabla 'ItTicketEnc'
     * @return string
     */
    public function getFechaPago()
    {
        return $this->fechaPago;
    }

    /**
     * Establece el campo 'fechaPago' de la tabla 'ItTicketEnc'
     * @param string $fechaPago
     */
    public function setFechaPago(string $fechaPago)
    {
        $this->fechaPago = $fechaPago;
    }

    /**
     * Obtiene el campo 'horaPago' de la tabla 'ItTicketEnc'
     * @return string
     */
    public function getHoraPago()
    {
        return $this->horaPago;
    }

    /**
     * Establece el campo 'horaPago' de la tabla 'ItTicketEnc'
     * @param string $horaPago
     */
    public function setHoraPago(string $horaPago)
    {
        $this->horaPago = $horaPago;
    }

    /**
     * Obtiene el campo 'mandante' de la tabla 'ItTicketEnc'
     * @return string
     */
    public function getMandante()
    {
        return $this->mandante;
    }

    /**
     *  Establece el campo 'mandante' de la tabla 'ItTicketEnc'
     * @param string $mandante
     */
    public function setMandante(string $mandante)
    {
        $this->mandante = $mandante;
    }

    /**
     * Obtiene el campo 'estado' de la tabla 'ItTicketEnc'
     * @return string
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Establece el campo 'estado' de la tabla 'ItTicketEnc'
     * @param string $estado
     */
    public function setEstado(string $estado)
    {
        $this->estado = $estado;
    }

    /**
     * Obtiene el campo 'eliminado' de la tabla 'ItTicketEnc'
     * @return string
     */
    public function getEliminado()
    {
        return $this->eliminado;
    }

    /**
     * Establece el campo 'eliminado' de la tabla 'ItTicketEnc'
     * @param string $eliminado
     */
    public function setEliminado(string $eliminado)
    {
        $this->eliminado = $eliminado;
    }

    /**
     * Obtiene el campo 'fechaMaxpago' de la tabla 'ItTicketEnc'
     * @return string
     */
    public function getFechaMaxpago()
    {
        return $this->fechaMaxpago;
    }

    /**
     * Establece el campo 'fechaMaxpago' de la tabla 'ItTicketEnc'
     * @param string $fechaMaxpago
     */
    public function setImpuesto(string $impuesto)
    {
        $this->impuesto = $impuesto;
    }


    /**
     * Obtiene el campo 'impuesto' de la tabla 'ItTicketEnc'
     * @return string
     */
    public function getImpuesto()
    {
        return $this->impuesto;
    }


    /**
     * Establece el campo 'fechaMaxpago' de la tabla 'ItTicketEnc'
     * @param string $fechaMaxpago
     */
    public function setFechaMaxpago(string $fechaMaxpago)
    {
        $this->fechaMaxpago = $fechaMaxpago;
    }


    /**
     * Obtiene el campo 'fechaCierre' de la tabla 'ItTicketEnc'
     * @return string
     */
    public function getFechaCierre()
    {
        return $this->fechaCierre;
    }

    /**
     * Establece el campo 'fechaCierre' de la tabla 'ItTicketEnc'
     * @param string $fechaCierre
     */
    public function setFechaCierre(string $fechaCierre)
    {
        $this->fechaCierre = $fechaCierre;
    }

    /**
     *  Obtiene el campo 'horaCierre' de la tabla 'ItTicketEnc'
     * @return string
     */
    public function getHoraCierre()
    {
        return $this->horaCierre;
    }

    /**
     * Establece el campo 'horaCierre' de la tabla 'ItTicketEnc'
     * @param string $horaCierre
     */
    public function setHoraCierre(string $horaCierre)
    {
        $this->horaCierre = $horaCierre;
    }

    /**
     * Obtiene el campo 'clave' de la tabla 'ItTicketEnc'
     * @return string
     */
    public function getClave()
    {
        return $this->clave;
    }

    /**
     * Establece el campo 'clave' de la tabla 'ItTicketEnc'
     * @param string $clave
     */
    public function setClave(string $clave)
    {
        $this->clave = $clave;
    }

    /**
     * Obtiene el campo 'usumodificaId' de la tabla 'ItTicketEnc'
     * @return string
     */
    public function getUsumodifId()
    {
        return $this->usumodificaId;
    }

    /**
     * Establece el campo 'usumodificaId' de la tabla 'ItTicketEnc'
     * @param string $usumodificaId
     */
    public function setUsumodifId(string $usumodificaId)
    {
        $this->usumodificaId = $usumodificaId;
    }


    /**
     * Obtiene el campo 'fechaModifica' de la tabla 'ItTicketEnc'
     * @return string
     */
    public function getUsucreaId()
    {
        return $this->usucreaId;
    }

    /**
     * Establece el campo 'usucreaId' de la tabla 'ItTicketEnc'
     * @param string $usumodificaId
     */
    public function setUsucreaId(string $usucreaId)
    {
        $this->usucreaId = $usucreaId;
    }

    /**
     * Obtiene el campo 'fechaModifica' de la tabla 'ItTicketEnc'
     * @return string
     * 
     */
    public function getFechaModifica()
    {
        return $this->fechaModifica;
    }

    /**
     * Establece el campo 'fechaModifica' de la tabla 'ItTicketEnc'
     * @param string $fechaModifica
     */
    public function setFechaModifica(string $fechaModifica)
    {
        $this->fechaModifica = $fechaModifica;
    }

    /**
     * Obtiene el campo 'beneficiarioId' de la tabla 'ItTicketEnc'
     * @return string
     */
    public function getBeneficiarioId()
    {
        return $this->beneficiarioId;
    }

    /**
     * Establece el campo 'beneficiarioId' de la tabla 'ItTicketEnc'
     * @param string $beneficiarioId
     */
    public function setBeneficiarioId(string $beneficiarioId)
    {
        $this->beneficiarioId = $beneficiarioId;
    }

    /**
     * Obtiene el campo 'tipoBeneficiario' de la tabla 'ItTicketEnc'
     * @return string
     */
    public function getTipoBeneficiario()
    {
        return $this->tipoBeneficiario;
    }

    
    /**
     * Obtiene el campo 'freebet' de la tabla 'ItTicketEnc'
     * @param string $tipoBeneficiario
     */
    public function setTipoBeneficiario(string $tipoBeneficiario)
    {
        $this->tipoBeneficiario = $tipoBeneficiario;
    }

    /**
     * Obtiene el campo 'dirIp' de la tabla 'ItTicketEnc'
     * @return string
     */
    public function getDirIp()
    {
        return $this->dirIp;
    }

    /**
     * Obtiene el campo 'freebet' de la tabla 'ItTicketEnc'
     * @param string $dirIp
     */
    public function setDirIp(string $dirIp)
    {
        $this->dirIp = $dirIp;
    }

    /**
     * Obtiene el campo 'freebet' de la tabla 'ItTicketEnc'
     * @param string $dirIp
     */
    public function setFreebet(string $freebet)
    {
        $this->freebet = $freebet;
    }


    /**
     * Obtiene el campo 'freebet' de la tabla 'ItTicketEnc'
     * @return string
     */
    public function getBetMode()
    {
        return $this->betmode;
    }

    /**
     * Obtiene el campo 'freebet' de la tabla 'ItTicketEnc'
     * @param string $dirIp
     */
    public function setBetMode(string $betMode)
    {
        $this->betmode = $betMode;
    }

    /**
     * Obtiene el campo 'freebet' de la tabla 'ItTicketEnc'
     * @return mixed|string
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * Define el campo 'transaction' de la tabla 'ItTicketEnc'
     * @param mixed|string $transaction
     */
    public function setTransaction($transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Obtiene el campo 'freebet' de la tabla 'ItTicketEnc'
     * @return mixed|string
     */
    public function getValorPremioPrevio()
    {
        return $this->valorPremioPrevio;
    }

    /**
     * Define el campo 'transaction' de la tabla 'ItTicketEnc'
     * @param mixed|string $transaction
     */
    public function setValorPremioPrevio($valorPremioPrevio)
    {
        $this->valorPremioPrevio = $valorPremioPrevio;
    }

    /**
     * Obtiene el campo 'freebet' de la tabla 'ItTicketEnc'
     * @return mixed|string
     */
    public function getTransaccionWallet()
    {
        return $this->transaccionWallet;
    }

    /**
     * Define el campo 'transaction' de la tabla 'ItTicketEnc'
     * @param mixed|string $transaction
     */
    public function setTransaccionWallet($transaccionWallet)
    {
        $this->transaccionWallet = $transaccionWallet;
    }

    /**
     * Obtiene el campo 'freebet' de la tabla 'ItTicketEnc'
     * @return mixed|string
     */
    public function getWallet()
    {
        return $this->wallet;
    }

    /**
     * Define el campo 'transaction' de la tabla 'ItTicketEnc'
     * @param mixed|string $transaction
     */
    public function setWallet($wallet)
    {
        $this->wallet = $wallet;
    }

    /**
     * Obtiene el campo 'freebet' de la tabla 'ItTicketEnc'
     * @return mixed
     */
    public function getSaldoCreditos()
    {
        return $this->saldoCreditos;
    }

    /**
     * Define el campo 'transaction' de la tabla 'ItTicketEnc'
     * @param mixed $saldoCreditos
     */
    public function setSaldoCreditos($saldoCreditos)
    {
        $this->saldoCreditos = $saldoCreditos;
    }

    /**
     * Obtiene el campo 'freebet' de la tabla 'ItTicketEnc'
     * @return mixed
     */
    public function getSaldoCreditosBase()
    {
        return $this->saldoCreditosBase;
    }

    /**
     * Define el campo 'transaction' de la tabla 'ItTicketEnc'
     * @param mixed $saldoCreditosBase
     */
    public function setSaldoCreditosBase($saldoCreditosBase)
    {
        $this->saldoCreditosBase = $saldoCreditosBase;
    }

    /**
     * Obtiene el campo 'freebet' de la tabla 'ItTicketEnc'
     * @return mixed
     */
    public function getSaldoBonos()
    {
        return $this->saldoBonos;
    }

    /**
     * Define el campo 'transaction' de la tabla 'ItTicketEnc'
     * @param mixed $saldoBonos
     */
    public function setSaldoBonos($saldoBonos)
    {
        $this->saldoBonos = $saldoBonos;
    }

    /**
     * Obtiene el campo 'freebet' de la tabla 'ItTicketEnc'
     * @return mixed
     */
    public function getSaldoFree()
    {
        return $this->saldoFree;
    }

    /**
     * Define el campo 'transaction' de la tabla 'ItTicketEnc'
     * @param mixed $saldoFree
     */
    public function setSaldoFree($saldoFree)
    {
        $this->saldoFree = $saldoFree;
    }


}

?>
