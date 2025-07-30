<?php

/**
 * Clase Report
 *
 * Esta clase se encarga de generar reportes relacionados con tickets, usuarios y puntos de venta.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-17
 */

namespace Backend\integrations\general\report;

use Backend\dto\ItTicketEnc;
use Backend\dto\Usuario;

/**
 * Clase Report
 *
 * Esta clase se encarga de generar reportes relacionados con tickets, usuarios y puntos de venta.
 */
class Report
{

    /**
     * Tipo de apuesta.
     *
     * @var integer
     */
    public $typeBet = 0;

    /**
     * Fecha de inicio en formato local.
     *
     * @var string
     */
    public $fromDateLocal;

    /**
     * Fecha de fin en formato local.
     *
     * @var string
     */
    public $toDateLocal;

    /**
     * Número máximo de filas a consultar.
     *
     * @var integer
     */
    public $maxRows;

    /**
     * Número de filas a omitir en la consulta.
     *
     * @var integer
     */
    public $skeepRows;

    /**
     * Constructor de la clase Report.
     *
     * Inicializa las propiedades de la clase con los datos proporcionados en la solicitud.
     *
     * @param object $request Objeto que contiene los datos de la solicitud.
     */
    function __construct($request)
    {
        $this->typeBet = $request->typeUser;
        $this->fromDateLocal = $request->fromDate;
        $this->toDateLocal = $request->toDate;
        $this->maxRows = $request->count;
        $this->skeepRows = $request->start;
        $this->method = $request->method;
        $this->id = $request->id;
        $this->type = $request->type;
        $this->mandante = $request->mandante;
    }

    /**
     * Obtiene los tickets según los filtros aplicados.
     *
     * @return array Respuesta con los datos de los tickets.
     */
    public function getTickets()
    {
        try {
            if ($this->validateData([$this->fromDateLocal, $this->toDateLocal, $this->typeBet, $this->mandante])) {
                return $this->objectResponse("Faltan campos por diligenciar");
            }

            $ticket = new ItTicketEnc();
            $rules = [];

            // Agrega reglas de filtro según el tipo de apuesta

            if ($this->typeBet == 2) {
                array_push(
                    $rules,
                    array("field" => "(it_ticket_enc.fecha_cierre)", "data" => $this->fromDateLocal, "op" => "ge")
                );
            } else {
                if ($this->typeBet == 3) {
                    array_push(
                        $rules,
                        array("field" => "(it_ticket_enc.fecha_pago)", "data" => $this->fromDateLocal, "op" => "ge")
                    );
                } else {
                    array_push(
                        $rules,
                        array("field" => "(it_ticket_enc.fecha_crea)", "data" => $this->fromDateLocal, "op" => "ge")
                    );
                }
            }

            if ($this->typeBet == 2) {
                array_push(
                    $rules,
                    array("field" => "(it_ticket_enc.fecha_cierre)", "data" => $this->toDateLocal, "op" => "le")
                );
            } else {
                if ($this->typeBet == 3) {
                    array_push(
                        $rules,
                        array("field" => "(it_ticket_enc.fecha_pago)", "data" => $this->toDateLocal, "op" => "le")
                    );
                } else {
                    array_push(
                        $rules,
                        array("field" => "(it_ticket_enc.fecha_crea)", "data" => $this->toDateLocal, "op" => "le")
                    );
                }
            }

            array_push($rules, array(
                "field" => "usuario.mandante",
                "data" => $this->mandante,
                "op" => "eq"
            ));

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);

            $select = "
                    it_ticket_enc.ticket_id,
                    it_ticket_enc.usuario_id,
                    it_ticket_enc.ticket_id,
                    it_ticket_enc.bet_status,
                    it_ticket_enc.fecha_crea,
                    usuario.nombre,
                    it_ticket_enc.fecha_cierre,
                    it_ticket_enc.fecha_pago,
                    usuario_punto_pago.nombre,
                    it_ticket_enc.fecha_modifica,
                    it_ticket_enc.fecha_cierre,
                    it_ticket_enc.fecha_maxpago,
                    it_ticket_enc.vlr_apuesta,
                    it_ticket_enc.vlr_premio,
                    it_ticket_enc.vlr_premio,
                    usuario.usuario_id
                ";
            $tickets = $ticket->getTicketsCustom(
                $select,
                "it_ticket_enc.ticket_id",
                "desc",
                $this->skeepRows,
                $this->maxRows,
                $json,
                true,
                ''
            );
            $tickets = json_decode($tickets);

            $final = [];
            // Procesa los datos de los tickets

            foreach ($tickets->data as $key => $value) {
                $array = [];
                $statusInit = $value->{"it_ticket_enc.bet_status"};
                $statusEnd = "";

                switch ($statusInit) {
                    case 'N':
                        $statusEnd = "Loser";
                        break;
                    case 'S':
                        $statusEnd = "Won";
                        if ($value->{"it_ticket_enc.fecha_maxpago"} > date("Y-m-d")) {
                            $statusEnd = "Expired";
                        }
                        break;
                    case 'T':
                        $statusEnd = "Cashed";
                        break;
                    case 'J':
                        $statusEnd = "Cancel";
                        break;
                    case 'M':
                        $statusEnd = "Cancel";
                        break;
                    case 'D':
                        $statusEnd = "Cancel";
                        break;
                    case 'A':
                        $statusEnd = "Expired";
                        break;

                    default:
                        $statusEnd = "Sold";
                        break;
                }

                $array["TicketNumber"] = $value->{"it_ticket_enc.ticket_id"};
                $array["LocationId"] = $value->{"it_ticket_enc.usuario_id"};
                $array["ETSN"] = $value->{"it_ticket_enc.ticket_id"};
                $array["TicketStatus"] = $statusEnd;
                $array["TicketType"] = $value->{"it_ticket_enc.estado"};
                $array["SoldDate"] = $value->{"it_ticket_enc.fecha_crea"};
                $array["SoldTerminal"] = $value->{"usuario.nombre"};
                $array["SettledDate"] = $value->{"it_ticket_enc.fecha_cierre"};
                $array["CashedDate"] = $value->{"it_ticket_enc.fecha_pago"};
                $array["CashedTerminal"] = $value->{"usuario_punto_pago.nombre"};
                $array["VoidedDate"] = $value->{"it_ticket_enc.fecha_modifica"};
                $array["ExpiredDate"] = $value->{"it_ticket_enc.fecha_maxpago"};
                $array["SoldValue"] = null;
                $array["RefundValue"] = $value->{""};
                $array["SettledValue"] = $value->{""};
                $array["BetTaxRate"] = null;
                $array["BetTaxValue"] = null;
                $array["WinTaxType"] = P;
                $array["WinTaxRate"] = 7;
                $array["WinTaxValue"] = $value->{""};
                $array["MontoPremio"] = $value->{"vlr_premio"};
                $array["BetType"] = $value->{""};
                $array["JugadorId"] = $value->{"usuario.usuario_id"};

                array_push($final, $array);
            }

            return $this->objectResponse("success", $final, $tickets->count[0]);
        } catch (Exception $e) {
            return $this->objectResponse($e);
        }
    }

    /**
     * Valida si los datos requeridos están completos.
     *
     * @param array $data Datos a validar.
     *
     * @return boolean True si falta algún dato, de lo contrario False.
     */
    public function validateData($data)
    {
        foreach ($data as $key) {
            if ($key == "") {
                return true;
            }
        }
        return false;
    }

    /**
     * Genera una respuesta estructurada.
     *
     * @param string   $message Mensaje de respuesta.
     * @param mixed    $data    Datos de la respuesta.
     * @param int|null $count   Número total de registros.
     *
     * @return array Respuesta estructurada.
     */
    public function objectResponse($message = "Success", $data = null, $count = null)
    {
        if (is_null($data)) {
            $error = true;
            $message = $message;
        } else {
            $error = false;
        }

        return [
            "Error" => $error,
            "Message" => $message,
            "TotalCount" => $count->{".count"},
            "Data" => $data
        ];
    }
}