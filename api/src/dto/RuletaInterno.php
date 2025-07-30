<?php

namespace Backend\dto;

use Backend\dao\TransjuegoInfoDAO;
use Backend\dto\RuletaDetalle;
use Backend\dto\UsuarioRuleta;
use Backend\dto\UsuarioPerfil;
use Backend\dto\PuntoVenta;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\dto\CuentaCobro;
use Backend\dto\ItTicketEnc;
use Backend\dto\LealtadInterna;
use Backend\dto\Registro;
use Backend\dto\UsuarioRecarga;
use Backend\mysql\AuditoriaGeneralMySqlDAO;
use Backend\mysql\RuletaInternoMySqlDAO;
use Backend\mysql\TransjuegoInfoMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\UsuarioBonoMySqlDAO;
use Backend\mysql\UsuarioRuletaMySqlDAO;
use Backend\websocket\WebsocketUsuario;
use Backend\mysql\BonoLogMySqlDAO;
use Backend\mysql\RuletaDetalleMySqlDAO;
use Backend\mysql\CiudadMySqlDAO;
use Backend\mysql\WebsocketNotificacionMySqlDAO;
use Exception;
use stdClass;


//error_reporting(E_ALL);
//ini_set("display_errors", "ON");

/**
 * Clase 'RuletaInterno'
 *
 * Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
 * para la tabla 'RuletaInterno'
 *
 * Ejemplo de uso:
 * $RuletaInterno = new RuletaInterno();
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class RuletaInterno
{

    /**
     * Representación de la columna 'ruletaId' de la tabla 'RuletaInterno'
     *
     * @var string
     */
    var $ruletaId;

    /**
     * Representación de la columna 'fechaInicio' de la tabla 'RuletaInterno'
     *
     * @var string
     */
    var $fechaInicio;

    /**
     * Representación de la columna 'fechaFin' de la tabla 'RuletaInterno'
     *
     * @var string
     */
    var $fechaFin;

    /**
     * Representación de la columna 'descripcion' de la tabla 'RuletaInterno'
     *
     * @var string
     */
    var $descripcion;

    /**
     * Representación de la columna 'nombre' de la tabla 'RuletaInterno'
     *
     * @var string
     */
    var $nombre;

    /**
     * Representación de la columna 'tipo' de la tabla 'RuletaInterno'
     *
     * @var string
     */
    var $tipo;

    /**
     * Representación de la columna 'estado' de la tabla 'RuletaInterno'
     *
     * @var string
     */
    var $estado;

    /**
     * Representación de la columna 'fechaModif' de la tabla 'RuletaInterno'
     *
     * @var string
     */
    var $fechaModif;

    /**
     * Representación de la columna 'fechaCrea' de la tabla 'RuletaInterno'
     *
     * @var string
     */
    var $fechaCrea;

    /**
     * Representación de la columna 'mandante' de la tabla 'RuletaInterno'
     *
     * @var string
     */
    var $mandante;

    /**
     * Representación de la columna 'condicional' de la tabla 'RuletaInterno'
     *
     * @var string
     */
    var $condicional;

    /**
     * Representación de la columna 'orden' de la tabla 'RuletaInterno'
     *
     * @var string
     */
    var $orden;

    /**
     * Representación de la columna 'cupoActual' de la tabla 'RuletaInterno'
     *
     * @var string
     */
    var $cupoActual;

    /**
     * Representación de la columna 'cupoMaximo' de la tabla 'RuletaInterno'
     *
     * @var string
     */
    var $cupoMaximo;

    /**
     * Representación de la columna 'cantidadRuletas' de la tabla 'RuletaInterno'
     *
     * @var string
     */
    var $cantidadRuletas;

    /**
     * Representación de la columna 'maximoRuletas' de la tabla 'RuletaInterno'
     *
     * @var string
     */
    var $maximoRuletas;

    /**
     * Representación de la columna 'usucreaId' de la tabla 'RuletaInterno'
     *
     * @var string
     */
    var $usucreaId;

    /**
     * Representación de la columna 'usumodifId' de la tabla 'RuletaInterno'
     *
     * @var string
     */
    var $usumodifId;

    /**
     * Representación de la columna 'codigo' de la tabla 'RuletaInterno'
     *
     * @var string
     */
    var $codigo;

    /**
     * Representación de la columna 'reglas' de la tabla 'RuletaInterno'
     *
     * @var string
     */
    var $reglas;


    /**
     * Constructor de clase
     *
     *
     * @param String $ruletaId id del ruleta interno
     *
     * @return no
     * @throws Exception si RuletaInterno no existe
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function __construct($ruletaId = "")
    {
        if ($ruletaId != "") {

            $this->ruletaId = $ruletaId;

            $RuletaInternoMySqlDAO = new RuletaInternoMySqlDAO();

            $RuletaInterno = $RuletaInternoMySqlDAO->load($this->ruletaId);


            if ($RuletaInterno != null && $RuletaInterno != "") {
                $this->ruletaId = $RuletaInterno->ruletaId;
                $this->fechaInicio = $RuletaInterno->fechaInicio;
                $this->fechaFin = $RuletaInterno->fechaFin;
                $this->descripcion = $RuletaInterno->descripcion;
                $this->nombre = $RuletaInterno->nombre;
                $this->tipo = $RuletaInterno->tipo;
                $this->estado = $RuletaInterno->estado;
                $this->fechaModif = $RuletaInterno->fechaModif;
                $this->fechaCrea = $RuletaInterno->fechaCrea;
                $this->mandante = $RuletaInterno->mandante;
                $this->usucreaId = $RuletaInterno->usucreaId;
                $this->usumodifId = $RuletaInterno->usumodifId;
                $this->condicional = $RuletaInterno->condicional;
                $this->orden = $RuletaInterno->orden;
                $this->cupoActual = $RuletaInterno->cupoActual;
                $this->cupoMaximo = $RuletaInterno->cupoMaximo;
                $this->cantidadRuletas = $RuletaInterno->cantidadRuletas;
                $this->maximoRuletas = $RuletaInterno->maximoRuletas;
                $this->codigo = $RuletaInterno->codigo;
                $this->reglas = $RuletaInterno->reglas;
            } else {
                throw new Exception("No existe " . get_class($this), "28");
            }

        }
    }


    /**
     * Realizar una consulta en la tabla de ruletas 'RuletaInterno'
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
     * @throws Exception si los ruletas no existen
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function getRuletasCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $RuletaInternoMySqlDAO = new RuletaInternoMySqlDAO();

        $ruletas = $RuletaInternoMySqlDAO->queryRuletasCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($ruletas != null && $ruletas != "") {
            return $ruletas;
        } else {
            throw new Exception("No existe " . get_class($this), "01");
        }


    }

    /**
     * Realizar una insercción
     *
     *
     * @param Objeto Transaction transacción
     *
     * @return boolean $ resultado de la inserción
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function insert($transaction)
    {

        $RuletaInternoMySqlDAO = new RuletaInternoMySqlDAO($transaction);
        return $RuletaInternoMySqlDAO->insert($this);

    }

    /**
     * Agregar un ruleta en la base de datos
     *
     *
     * @param String tipoRuleta tipoRuleta
     * @param String usuarioId id del usuario
     * @param String mandante mandante
     * @param String detalles detalles
     * @param Objeto Transaction transacción
     *
     * @return boolean $ resultado de la transacción
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */


    //Agrega Ruletas Dependiendo del tipo
    public function agregarRuleta($paisId = "", $usumandanteId = "", $amount = "", $tipo = "", $categoriaId = "", $subproveedorId = "", $prodmandanteId = "", $ticketId = "")
    {

        $arg1 = $paisId;
        $arg2 = $usumandanteId;
        $arg3 = $amount;
        $arg4 = $tipo;
        $arg5 = $categoriaId;
        $arg6 = $subproveedorId;
        $arg7 = $prodmandanteId;
        $arg8 = $ticketId;


        $UsuarioMandante = new UsuarioMandante($arg2);
        $mandante = $UsuarioMandante->mandante;
        $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
        $Registro = new Registro('', $Usuario->usuarioId);

        $CiudadMySqlDAO = new CiudadMySqlDAO();
        $Ciudad = $CiudadMySqlDAO->load($Registro->ciudadId);
        $detalleDepartamentoUSER = $Ciudad->deptoId;
        $detalleCiudadUSER = $Ciudad->ciudadId;
        $detalleMonedaUSER = $Usuario->moneda;
        $cumpleCondicionesGlobal = false;


        /*$UsuarioMandante = new UsuarioMandante($arg2);
        $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
        $detalleMonedaUSER = $Usuario->moneda;

        $UsuarioPerfil = new UsuarioPerfil($Usuario->usuarioId);
        $CiudadMySqlDAO = new CiudadMySqlDAO();

        if($UsuarioPerfil->perfilId=="PUNTOVENTA"){
            $PuntoVenta = new PuntoVenta($Usuario->usuarioId);
            $Ciudad = $CiudadMySqlDAO->load($PuntoVenta->ciudadId);
            $detalleDepartamentoUSERPV = $Ciudad->deptoId;
            $detalleCiudadUSERPV = $Ciudad->ciudadId;
        }elseif($UsuarioPerfil->perfilId=="USUONLINE"){
            $Registro = new Registro('', $Usuario->usuarioId);
            $Ciudad = $CiudadMySqlDAO->load($Registro->ciudadId);
            $detalleDepartamentoUSER = $Ciudad->deptoId;
            $detalleCiudadUSER = $Ciudad->ciudadId;
        }*/


        switch ($arg4) {

            case 1: //SPORTSBOOK

                if (true) {

                    $SkeepRows = 0;
                    $MaxRows = 10;

                    $rules = [];
                    array_push($rules, array("field" => "ruleta_interno.estado", "data" => "A", "op" => "eq"));
                    array_push($rules, array("field" => "ruleta_interno.fecha_inicio", "data" => date("Y-m-d H:i:s"), "op" => "le"));
                    array_push($rules, array("field" => "ruleta_interno.fecha_fin", "data" => date("Y-m-d H:i:s"), "op" => "ge"));
                    array_push($rules, array("field" => "ruleta_interno.tipo", "data" => intval($arg4), "op" => "eq"));
                    array_push($rules, array("field" => "ruleta_interno.mandante", "data" => $mandante, "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                    $json2 = json_encode($filtro);

                    $Ruletas = $this->getRuletasCustom("ruleta_interno.*", "ruleta_interno.orden,ruleta_interno.ruleta_id", "desc", $SkeepRows, $MaxRows, $json2, true);

                    $Ruletas = json_decode($Ruletas);


                    $final = array();
                    foreach ($Ruletas->data as $key => $value) {
                        $array = [];

                        $array["ruletaId"] = $value->{"ruleta_interno.ruleta_id"};

                        array_push($final, $array);
                    }


                    foreach ($final as $key => $value) {

                        if (!$cumpleCondicionesGlobal) {


                            $ruletaId = $value["ruletaId"];

                            $SkeepRows = 0;
                            $MaxRows = 10;
                            $rules = [];

                            array_push($rules, array("field" => "ruleta_interno.estado", "data" => "A", "op" => "eq"));
                            array_push($rules, array("field" => "ruleta_interno.ruleta_id", "data" => $ruletaId, "op" => "eq"));
                            array_push($rules, array("field" => "ruleta_detalle.tipo", "data" => "'TIPOPRODUCTO','MINBETPRICE','MAXDAILYSPINS', 'REPETIRSORTEO', 'CONDPAISUSER', 'CONDDEPARTAMENTOUSER',  'CONDCIUDADUSER', 'ITAINMENT1', 'ITAINMENT3', 'ITAINMENT4', 'ITAINMENT5', 'ITAINMENT82','MINSELCOUNT', 'MINSELPRICE','MINSELPRICETOTAL','CSVREGISTRADO','PARAPRIMERLOGINDIA','ESPARAREGISTRO'", "op" => "in"));

                            $filtro = array("rules" => $rules, "groupOp" => "AND");
                            $json2 = json_encode($filtro);


                            $RuletaDetalle = new RuletaDetalle();
                            $RuletaDetalles = $RuletaDetalle->getRuletaDetallesCustom2("ruleta_detalle.*,ruleta_interno.*", "ruleta_detalle.ruletadetalle_id", "desc", $SkeepRows, $MaxRows, $json2, true, 'ruleta_detalle.ruletadetalle_id');
                            $RuletaDetalles = json_decode($RuletaDetalles);


                            $UsuarioMandante = new UsuarioMandante($arg2);
                            $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
                            $Registro = new Registro('', $Usuario->usuarioId);

                            $CiudadMySqlDAO = new CiudadMySqlDAO();
                            $Ciudad = $CiudadMySqlDAO->load($Registro->ciudadId);
                            $detalleDepartamentoUSER = $Ciudad->deptoId;
                            $detalleCiudadUSER = $Ciudad->ciudadId;

                            $cumpleCondiciones = true;
                            $MINBETPRICE = 0;
                            $fechaExpiracion = '';
                            $expiracionDias = '';
                            $condicionPaisUSERcount = 0;
                            $MAXDAILYSPINS = 0;
                            $condicionDepartamentoUSERcount = 0;
                            $condicionDepartamentoUSER = false;
                            $condicionCiudadUSERcount = 0;
                            $condicionCiudadUSER = false;
                            $condicionesPayment = 0;
                            $cumpleCondicionesPayment = false;
                            $cumplecondicionproducto = false;
                            $csvUsuarios = '';
                            $PARAPRIMERLOGINDIA = '';
                            $ESPARAREGISTRO = '';


                            if ($ticketId != '') {

                                $sqlSport = "select te.vlr_apuesta,td.ticket_id,td.apuesta,td.agrupador,td.logro,td.opcion,td.apuesta_id,td.agrupador_id,td.sportid,td.fecha_evento,td.hora_evento,te.usuario_id from it_ticket_det td INNER JOIN it_ticket_enc te ON (td.ticket_id = te.ticket_id ) where  te.ticket_id='" . $ticketId . "' ";

                                $detalleTicket = $this->execQuery('', $sqlSport);

                                $array = array();


                                foreach ($detalleTicket as $detalle) {
                                    $detalle->sportid = $detalle->{'td.sportid'};
                                    $detalle->agrupador_id = $detalle->{'td.agrupador_id'};
                                    $detalle->logro = $detalle->{'td.logro'};
                                    $detalle->vlr_apuesta = $detalle->{'te.vlr_apuesta'};

                                    $detalles = array(
                                        "DeporteMercado" => $detalle->sportid . "M" . $detalle->agrupador_id,
                                        "Deporte" => $detalle->sportid,
                                        // "Liga"=>$detalle->ligaid,
                                        // "Evento"=>$detalle->apuesta_id,
                                        "Cuota" => $detalle->logro

                                    );
                                    $detalleValorApuesta = $detalle->vlr_apuesta;


                                    array_push($array, $detalles);

                                    $betmode = $detalle->betmode;
                                    $detalleCuotaTotal = (int)$detalle->logro;

                                }
                                $detallesFinal = json_decode(json_encode($array));

                                $detalleSelecciones = $detallesFinal;


                            }

                            if ($value->condicional == 'NA' || $value->condicional == '') {
                                $tipocomparacion = "OR";

                            } else {
                                $tipocomparacion = $value->condicional;

                            }


                            foreach ($RuletaDetalles->data as $key1 => $value1) {

                                switch ($value1->{"ruleta_detalle.tipo"}) {


                                    case "MINSELCOUNT":
                                        $minselcount = $value1->{"ruleta_detalle.valor"};

                                        if ($value1->{"ruleta_detalle.valor"} > oldCount($detalleSelecciones)) {
                                            //$cumpleCondiciones = false;

                                        }

                                        break;

                                    case "MINSELPRICE":

                                        foreach ($detalleSelecciones as $item) {
                                            if ($value1->{"ruleta_detalle.valor"} > $item->Cuota) {
                                                $cumpleCondiciones = false;

                                            }
                                        }


                                        break;


                                    case "MINSELPRICETOTAL":

                                        if ($value1->{"ruleta_detalle.valor"} > $detalleCuotaTotal) {
                                            $cumpleCondiciones = false;

                                        }


                                        break;


                                    case "ITAINMENT1":

                                        foreach ($detalleSelecciones as $item) {


                                            if ($tipocomparacion == "OR") {
                                                if (($value1->{"ruleta_detalle.valor"}) == ($item->Deporte)) {
                                                    $cumplecondicionproducto = true;


                                                }
                                            } elseif ($tipocomparacion == "AND") {
                                                if ($value1->{"ruleta_detalle.valor"} != $item->Deporte) {
                                                    $cumplecondicionproducto = false;


                                                }

                                                if ($condicionesproducto == 0) {
                                                    if ($value1->{"ruleta_detalle.valor"} == $item->Deporte) {
                                                        $cumplecondicionproducto = true;
                                                    }
                                                } else {
                                                    if ($value1->{"ruleta_detalle.valor"} == $item->Deporte && $cumplecondicionproducto) {
                                                        $cumplecondicionproducto = true;

                                                    }
                                                }

                                            }

                                        }

                                        $condicionesproducto++;
                                        break;

                                    case "ITAINMENT3":


                                        foreach ($detalleSelecciones as $item) {
                                            if ($tipocomparacion == "OR") {
                                                if ($value1->{"ruleta_detalle.valor"} == $item->Liga) {
                                                    $cumplecondicionproducto = true;

                                                }
                                            } elseif ($tipocomparacion == "AND") {
                                                if ($value1->{"ruleta_detalle.valor"} != $item->Liga) {
                                                    $cumplecondicionproducto = false;

                                                }

                                                if ($condicionesproducto == 0) {
                                                    if ($value1->{"ruleta_detalle.valor"} == $item->Liga) {
                                                        $cumplecondicionproducto = true;
                                                    }
                                                } else {
                                                    if ($value1->{"ruleta_detalle.valor"} == $item->Liga && $cumplecondicionproducto) {
                                                        $cumplecondicionproducto = true;

                                                    }
                                                }

                                            }

                                        }

                                        $condicionesproducto++;

                                        break;
                                    case "ITAINMENT4":


                                        foreach ($detalleSelecciones as $item) {
                                            if ($tipocomparacion == "OR") {
                                                if ($value1->{"ruleta_detalle.valor"} == $item->Evento) {
                                                    $cumplecondicionproducto = true;

                                                }
                                            } elseif ($tipocomparacion == "AND") {
                                                if ($value1->{"ruleta_detalle.valor"} != $item->Evento) {
                                                    $cumplecondicionproducto = false;

                                                }

                                                if ($condicionesproducto == 0) {

                                                    if ($value1->{"ruleta_detalle.valor"} == $item->Evento) {
                                                        $cumplecondicionproducto = true;
                                                    }
                                                } else {

                                                    if ($value1->{"ruleta_detalle.valor"} == $item->Evento && $cumplecondicionproducto) {
                                                        $cumplecondicionproducto = true;

                                                    }
                                                }

                                            }

                                        }

                                        $condicionesproducto++;

                                        break;
                                    case "ITAINMENT5":


                                        foreach ($detalleSelecciones as $item) {
                                            if ($tipocomparacion == "OR") {
                                                if ($value1->{"ruleta_detalle.valor"} == $item->DeporteMercado) {
                                                    $cumplecondicionproducto = true;


                                                }
                                            } elseif ($tipocomparacion == "AND") {
                                                if ($value1->{"ruleta_detalle.valor"} != $item->DeporteMercado) {
                                                    $cumplecondicionproducto = false;

                                                }

                                                if ($condicionesproducto == 0) {
                                                    if ($value1->{"ruleta_detalle.valor"} == $item->DeporteMercado) {
                                                        $cumplecondicionproducto = true;
                                                    }
                                                } else {
                                                    if ($value1->{"ruleta_detalle.valor"} == $item->DeporteMercado && $cumplecondicionproducto) {
                                                        $cumplecondicionproducto = true;

                                                    }
                                                }

                                            }

                                        }

                                        $condicionesproducto++;

                                        break;

                                    case "ITAINMENT82":

                                        if ($value1->{"ruleta_detalle.valor"} == 1) {
                                            $sePuedeSimples = 1;

                                        }
                                        if ($value1->{"ruleta_detalle.valor"} == 2) {
                                            $sePuedeCombinadas = 1;

                                        }
                                        break;


                                    case "TIPOPRODUCTO":

                                        $TIPOPRODUCTO = $value1->{"ruleta_detalle.valor"};

                                        break;


                                    case "MINBETPRICE":


                                        $MINBETPRICE = $value1->{"ruleta_detalle.valor"};


                                        break;


                                    case "MAXDAILYSPINS":

                                        $MAXDAILYSPINS = $value1->{"ruleta_detalle.valor"};

                                        break;


                                    case "REPETIRSORTEO":

                                        if ($value1->{"ruleta_detalle.valor"} == '1') {

                                            $puederepetirRuleta = true;
                                        }

                                        break;


                                    case "CONDDEPARTAMENTOUSER":

                                        $condicionDepartamentoUSERcount = $condicionDepartamentoUSERcount + 1;
                                        if ($value1->{"ruleta_detalle.valor"} == $detalleDepartamentoUSER) {
                                            $condicionDepartamentoUSER = true;
                                        }
                                        break;


                                    case "CONDCIUDADUSER":

                                        $condicionCiudadUSERcount = $condicionCiudadUSERcount + 1;

                                        if ($value1->{"ruleta_detalle.valor"} == $detalleCiudadUSER) {

                                            $condicionCiudadUSER = true;
                                        }
                                        break;


                                    case "CONDPAISUSER":

                                        $condicionPaisUSERcount = $condicionPaisUSERcount + 1;

                                        if ($value1->{"ruleta_detalle.valor"} == $arg1) {
                                            $condicionPaisUSER = true;
                                        }

                                        break;

                                    case "EXPIRACIONFECHA":

                                        $fechaExpiracion = $value1->{"ruleta_detalle.valor"};
                                        break;

                                    case "EXPIRACIONDIA":

                                        $expiracionDias = $value1->{"ruleta_detalle.valor"};
                                        break;
                                    case "CSVREGISTRADO":

                                        $csvUsuarios = $value1->{"ruleta_detalle.valor"};
                                        break;
                                    case "PARAPRIMERLOGINDIA":

                                        $PARAPRIMERLOGINDIA = $value1->{"ruleta_detalle.valor"};
                                        break;
                                    case "ESPARAREGISTRO":

                                        $ESPARAREGISTRO = $value1->{"ruleta_detalle.valor"};
                                        break;

                                    default:

                                        break;
                                }

                            }


                            if ($condicionPaisUSERcount > 0 && !$condicionPaisUSER) {
                                $cumpleCondiciones = false;
                            }

                            if ($condicionesPayment > 0 && !$cumpleCondicionesPayment) {
                                $cumpleCondiciones = false;
                            }

                            if ($condicionDepartamentoUSERcount > 0 && !$condicionDepartamentoUSER) {
                                $cumpleCondiciones = false;

                            }

                            if ($condicionCiudadUSERcount > 0 && !$condicionCiudadUSER) {
                                $cumpleCondiciones = false;
                            }


                            if ($cumpleCondiciones) {
                                if (!$puederepetirRuleta) {

                                    $SkeepRows = 0;
                                    $MaxRows = 10;
                                    $rules = [];


                                    array_push($rules, array("field" => "usuario_ruleta.estado", "data" => "'PR'", "op" => "ni"));
                                    array_push($rules, array("field" => "usuario_ruleta.usuario_id", "data" => $arg2, "op" => "eq"));
                                    array_push($rules, array("field" => "usuario_ruleta.ruleta_id", "data" => $ruletaId, "op" => "eq"));
                                    array_push($rules, array("field" => "ruleta_interno.tipo", "data" => intval($arg4), "op" => "eq"));

                                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                                    $json2 = json_encode($filtro);


                                    $UsuarioRuleta = new UsuarioRuleta();

                                    $UsuariosRuletas = $UsuarioRuleta->getUsuarioRuletaCustom("usuario_ruleta.*", "usuario_ruleta.usuruleta_id", "desc", $SkeepRows, $MaxRows, $json2, true);
                                    $UsuariosRuletas = json_decode($UsuariosRuletas);


                                    if ($UsuariosRuletas->count[0]->{".count"} > 0) {
                                        $cumpleCondiciones = false;
                                    }
                                }
                            }

                            // Se valida que el usuario no tenga contingencia de abusador de bonos activa
                            $UsuarioConfiguracion = new UsuarioConfiguracion();
                            $UsuarioConfiguracion = $UsuarioConfiguracion->getUserBondABuser($UsuarioMandante->usuarioMandante);
                            if (!empty($UsuarioConfiguracion->usuconfigId)) {
                                $BonoInterno = new BonoInterno();
                                $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
                                $transaccion = $BonoDetalleMySqlDAO->getTransaction();
                                //Registro de log al intentar asignar premio de ruleta a un usuario con contingencia activa
                                $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                VALUES ('$UsuarioMandante->usuarioMandante', 'RULETA', '$ruletaId', '$arg4', '$arg8', 'Contingencia activa: Abusador de bonos')";
                                $BonoInterno->execQuery($transaccion, $sqlLog);
                                $transaccion->commit();
                                $cumpleCondiciones = false;
                            }

                            if ($cumpleCondiciones) {

                                $SkeepRows = 0;
                                $MaxRows = 10;
                                $rules = [];

                                /* POSIBLES ESTADOS
                                    PR: Pendiente Rollover
                                    P: Pendiente
                                    L: Libre
                                    A: Activa
                                    NR: No redimida. Es el mismo estado de PR
                                */
                                array_push($rules, array("field" => "usuario_ruleta.estado", "data" => "'PR'", "op" => "in")); //usuario_ruleta estado A   limit 1
                                array_push($rules, array("field" => "usuario_ruleta.usuario_id", "data" => $arg2, "op" => "eq"));
                                array_push($rules, array("field" => "usuario_ruleta.ruleta_id", "data" => $ruletaId, "op" => "eq"));
                                //array_push($rules, array("field" => "usuario_ruleta.fecha_crea", "data" => date("Y-m-d 00:00:00"), "op" => "ge"));
                                //array_push($rules, array("field" => "usuario_ruleta.fecha_crea", "data" => date("Y-m-d 23:59:59"), "op" => "le"));
                                array_push($rules, array("field" => "ruleta_interno.tipo", "data" => intval($arg4), "op" => "eq"));

                                $filtro = array("rules" => $rules, "groupOp" => "AND");
                                $json2 = json_encode($filtro);


                                $UsuarioRuleta = new UsuarioRuleta();

                                $UsuariosRuletas = $UsuarioRuleta->getUsuarioRuletaCustom("usuario_ruleta.*", "usuario_ruleta.usuruleta_id", "desc", $SkeepRows, $MaxRows, $json2, true);
                                $UsuariosRuletas = json_decode($UsuariosRuletas);

                                if (!empty($fechaExpiracion) && strtotime($fechaExpiracion) < time()) {
                                    continue;
                                }

                                if ($UsuariosRuletas->count[0]->{".count"} == 0) {

                                    if ($csvUsuarios != '1' && $PARAPRIMERLOGINDIA !='1' && $ESPARAREGISTRO !='1') {

                                        $SkeepRows = 0;
                                        $MaxRows = 10;
                                        $rules = [];
                                        //array_push($rules, array("field" => "usuario_ruleta.estado", "data" => "'A','P','R','PR'", "op" => "in"));
                                        array_push($rules, array("field" => "usuario_ruleta.estado", "data" => "'A','P','R','PR','NR','PP'", "op" => "in"));
                                        array_push($rules, array("field" => "usuario_ruleta.usuario_id", "data" => $arg2, "op" => "eq"));
                                        array_push($rules, array("field" => "usuario_ruleta.ruleta_id", "data" => $ruletaId, "op" => "eq"));
                                        array_push($rules, array("field" => "usuario_ruleta.fecha_crea", "data" => date("Y-m-d 00:00:00"), "op" => "ge"));
                                        array_push($rules, array("field" => "usuario_ruleta.fecha_crea", "data" => date("Y-m-d 23:59:59"), "op" => "le"));
                                        array_push($rules, array("field" => "ruleta_interno.tipo", "data" => intval($arg4), "op" => "eq"));

                                        $filtro = array("rules" => $rules, "groupOp" => "AND");
                                        $json2 = json_encode($filtro);

                                        $UsuariosRuletas = $UsuarioRuleta->getUsuarioRuletaCustom("usuario_ruleta.*", "usuario_ruleta.usuruleta_id", "desc", $SkeepRows, $MaxRows, $json2, true);
                                        $UsuariosRuletas = json_decode($UsuariosRuletas);

                                        if ($UsuariosRuletas->count[0]->{".count"} < $MAXDAILYSPINS) {

                                            $UsuarioRuleta = new UsuarioRuleta();

                                            $RuletaDetalleMySqlDAO = new RuletaDetalleMySqlDAO();
                                            $transaccion = $RuletaDetalleMySqlDAO->getTransaction();
                                            $UsuarioRuleta = new UsuarioRuleta();
                                            $UsuarioRuleta->ruletaId = $ruletaId;
                                            $UsuarioRuleta->usuarioId = $arg2;
                                            $UsuarioRuleta->valor = 0;
                                            $UsuarioRuleta->posicion = 0;
                                            $UsuarioRuleta->valorBase = $MINBETPRICE;
                                            $UsuarioRuleta->fechaCrea = 0;
                                            $UsuarioRuleta->usucreaId = 0;
                                            $UsuarioRuleta->fechaModif = 0;
                                            $UsuarioRuleta->usumodifId = 0;

                                            $fechaExpiracionTmp=$fechaExpiracion;
                                            if(!empty($expiracionDias)){
                                                $fechaExpiracionTmp = date("Y-m-d H:i:s", strtotime('+ '. $expiracionDias .' days'));
                                            }
                                            $UsuarioRuleta->fechaExpiracion = $fechaExpiracionTmp;


                                            if ($arg3 < $MINBETPRICE) {

                                                $UsuarioRuleta->estado = "PR";

                                            } else {

                                                $UsuarioRuleta->estado = "A";
                                            }
                                            $UsuarioRuleta->errorId = 0;
                                            $UsuarioRuleta->idExterno = 0;
                                            $UsuarioRuleta->mandante = 0;
                                            $UsuarioRuleta->version = 0;
                                            $UsuarioRuleta->apostado = floatval($arg3);
                                            $UsuarioRuleta->codigo = 0;
                                            $UsuarioRuleta->externoId = 0;
                                            $UsuarioRuleta->valorPremio = 0;
                                            $UsuarioRuleta->premio = "";
                                            $UsuarioRuletaMySqlDAO = new UsuarioRuletaMySqlDAO($transaccion);
                                            $UsuruletaId=$UsuarioRuletaMySqlDAO->insert($UsuarioRuleta);

                                            //Se valida si la ruleta está activa para enviar por web socket al front
                                            $transaccion->commit();
                                            if ($UsuarioRuleta->estado == "A") {
                                                $websocketNotification = new WebsocketNotificacion();
                                                $websocketNotificacionMySqlDAO = new WebsocketNotificacionMySqlDAO();
                                                $transaccion = $websocketNotificacionMySqlDAO->getTransaction();

                                                $websocketNotification->setTipo('ruleta');
                                                $websocketNotification->setCanal('');

                                                $ruleta = $UsuarioRuleta->getRuletasUsuarioMandante($UsuarioMandante,$UsuarioRuleta->usuruletaId);
                                                $ruleta = !empty($ruleta) ? json_encode(['roulette' => $ruleta]) : '';

                                                $websocketNotification->setMensaje($ruleta);
                                                $websocketNotification->setEstado('P');
                                                $websocketNotification->setUsuarioId($UsuarioMandante->getUsuarioMandante());
                                                $websocketNotification->setValor($UsuarioRuleta->usuruletaId);
                                                $websocketNotification->setFecha_exp(date('Y-m-d H:i:s', strtotime('+6 hour')));

                                                $websocketNotificacionMySqlDAO->insert($websocketNotification);
                                                $transaccion->commit();
                                            }


                                        } else {
                                            $cumpleCondiciones = false;
                                        }
                                    }else{
                                        $cumpleCondiciones = false;

                                    }

                                } else {

                                    foreach ($UsuariosRuletas->data as $keys => $values) {
                                        $RuletaDetalleMySqlDAO = new RuletaDetalleMySqlDAO();
                                        $transaccion = $RuletaDetalleMySqlDAO->getTransaction();

                                        $usuruletaId = $values->{"usuario_ruleta.usuruleta_id"};

                                        $UsuarioRuleta = new UsuarioRuleta($usuruletaId);

                                        if ($UsuarioRuleta->valorBase == 0) {


                                            $UsuarioRuleta->setValorBase($MINBETPRICE);

                                            $UsuarioRuletaMySqlDAO = new UsuarioRuletaMySqlDAO($transaccion);
                                            $UsuarioRuletaMySqlDAO->update($UsuarioRuleta);

                                        }

                                        $BonoInterno = new BonoInterno();


                                        $sql = "UPDATE usuario_ruleta SET apostado = apostado + " . (floatval($arg3)) . " WHERE usuruleta_id =" . $usuruletaId;
                                        $BonoInterno->execQuery($transaccion, $sql);

                                        $transaccion->commit();

                                    }


                                }

                                if ($cumpleCondiciones) {

                                    $BonoInterno = new BonoInterno();
                                    $RuletaDetalleMySqlDAO = new RuletaDetalleMySqlDAO();
                                    $transaccion = $RuletaDetalleMySqlDAO->getTransaction();

                                    $conndicionDiasExpiracion = (!empty($expiracionDias)) ? " AND DATE_ADD(usuario_ruleta.fecha_crea, INTERVAL " . $expiracionDias . " DAY) >= NOW()" : '';

                                    $sql = "SELECT ur.usuruleta_id FROM usuario_ruleta ur WHERE ur.ruleta_id =" . $ruletaId . " AND ur.usuario_id=" . $arg2 . " AND ur.estado='PR'  AND ur.apostado >= ur.valor_base $conndicionDiasExpiracion";

                                    $usuruletaId = $this->execQuery($transaccion, $sql);

                                    $sql = "UPDATE usuario_ruleta SET estado = 'A'  WHERE ruleta_id =" . $ruletaId . " AND usuario_id=" . $arg2 . " AND estado='PR'  AND usuario_ruleta.apostado >= usuario_ruleta.valor_base $conndicionDiasExpiracion";

                                    $result = $BonoInterno->execQuery($transaccion, $sql);

                                    //Se valida si hay ruletas para enviar por web socket
                                    if ($result > 0) {
                                        if (!empty($usuruletaId)) {
                                            $usuruletaId = $usuruletaId[0]->{"ur.usuruleta_id"};

                                            $websocketNotification = new WebsocketNotificacion();
                                            $websocketNotificacionMySqlDAO = new WebsocketNotificacionMySqlDAO($transaccion);

                                            $websocketNotification->setTipo('ruleta');
                                            $websocketNotification->setCanal('');

                                            $ruleta = $UsuarioRuleta->getRuletasUsuarioMandante($UsuarioMandante,$usuruletaId);
                                            $ruleta = !empty($ruleta) ? json_encode(['roulette' => $ruleta]) : '';

                                            $websocketNotification->setMensaje($ruleta);
                                            $websocketNotification->setEstado('P');
                                            $websocketNotification->setUsuarioId($UsuarioMandante->getUsuarioMandante());
                                            $websocketNotification->setValor($usuruletaId);
                                            $websocketNotification->setFecha_exp(date('Y-m-d H:i:s', strtotime('+6 hour')));
                                            $websocketNotificacionMySqlDAO->insert($websocketNotification);
                                        }
                                        $transaccion->commit();
                                        $cumpleCondicionesGlobal = true;
                                    } else {
                                        continue;
                                    }
                                }
                            }
                        }
                    }
                }


                break;
            case 2: //CASINO

                if (true) {

                    $SkeepRows = 0;
                    $MaxRows = 1000;

                    $rules = [];
                    array_push($rules, array("field" => "ruleta_interno.estado", "data" => "A", "op" => "eq"));
                    array_push($rules, array("field" => "ruleta_interno.fecha_inicio", "data" => date("Y-m-d H:i:s"), "op" => "le"));
                    array_push($rules, array("field" => "ruleta_interno.fecha_fin", "data" => date("Y-m-d H:i:s"), "op" => "ge"));
                    array_push($rules, array("field" => "ruleta_interno.tipo", "data" => intval($arg4), "op" => "eq"));
                    array_push($rules, array("field" => "ruleta_interno.mandante", "data" => $mandante, "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                    $json2 = json_encode($filtro);

                    $Ruletas = $this->getRuletasCustom("ruleta_interno.*", "ruleta_interno.orden,ruleta_interno.ruleta_id", "desc", $SkeepRows, $MaxRows, $json2, true);

                    $Ruletas = json_decode($Ruletas);


                    $final = array();
                    foreach ($Ruletas->data as $key => $value) {
                        $array = [];

                        $array["ruletaId"] = $value->{"ruleta_interno.ruleta_id"};

                        array_push($final, $array);
                    }


                    foreach ($final as $key => $value) {
                        if (!$cumpleCondicionesGlobal) {
                            $ruletaId = $value["ruletaId"];

                            $SkeepRows = 0;
                            $MaxRows = 1000;
                            $rules = [];

                            array_push($rules, array("field" => "ruleta_interno.estado", "data" => "A", "op" => "eq"));
                            array_push($rules, array("field" => "ruleta_interno.ruleta_id", "data" => $ruletaId, "op" => "eq"));
                            array_push($rules, array("field" => "ruleta_detalle.tipo", "data" => "'TIPOPRODUCTO','MINBETPRICE','MAXDAILYSPINS', 'REPETIRSORTEO', 'CONDPAISUSER', 'CONDDEPARTAMENTOUSER',  'CONDCIUDADUSER', 'CONDGAME', 'CONDSUBPROVIDER','CONDCATEGORY','CSVREGISTRADO','PARAPRIMERLOGINDIA','ESPARAREGISTRO'", "op" => "in"));

                            $filtro = array("rules" => $rules, "groupOp" => "AND");
                            $json2 = json_encode($filtro);


                            $RuletaDetalle = new RuletaDetalle();
                            $RuletaDetalles = $RuletaDetalle->getRuletaDetallesCustom2("ruleta_detalle.*,ruleta_interno.*", "ruleta_detalle.ruletadetalle_id", "desc", $SkeepRows, $MaxRows, $json2, true, 'ruleta_detalle.ruletadetalle_id');
                            $RuletaDetalles = json_decode($RuletaDetalles);


                            $cumpleCondiciones = true;
                            $TIPOPRODUCTO = 0;
                            $MINBETPRICE = 0;
                            $fechaExpiracion = '';
                            $expiracionDias = '';
                            $condicionPaisUSERcount = 0;
                            $MAXDAILYSPINS = 0;
                            $puederepetirRuleta = false;
                            $condicionDepartamentoUSERcount = 0;
                            $condicionDepartamentoUSER = false;
                            $condicionCiudadUSERcount = 0;
                            $condicionCiudadUSER = false;
                            $condicionesProducto = 0;
                            $condicionesSubprovider = 0;
                            $condicionesCategory = 0;
                            $cumpleCondicionesProd = false;
                            $cumpleCondicionesSubProveedor = false;
                            $cumpleCondicionCategory = false;
                            $csvUsuarios = '';
                            $PARAPRIMERLOGINDIA = '';
                            $ESPARAREGISTRO = '';


                            foreach ($RuletaDetalles->data as $key1 => $value1) {

                                switch ($value1->{"ruleta_detalle.tipo"}) {


                                    case "TIPOPRODUCTO":

                                        $TIPOPRODUCTO = $value1->{"ruleta_detalle.valor"};

                                        break;


                                    case "MINBETPRICE":
                                        if ($value1->{"ruleta_detalle.moneda"} == $detalleMonedaUSER) {


                                            $MINBETPRICE = $value1->{"ruleta_detalle.valor"};
                                        }

                                        break;


                                    case "MAXDAILYSPINS":
                                        if ($value1->{"ruleta_detalle.moneda"} == $detalleMonedaUSER) {

                                            $MAXDAILYSPINS = $value1->{"ruleta_detalle.valor"};
                                        }
                                        break;


                                    case "REPETIRSORTEO":

                                        if ($value1->{"ruleta_detalle.valor"} == '1') {

                                            $puederepetirRuleta = true;
                                        }

                                        break;


                                    case "CONDDEPARTAMENTOUSER":

                                        $condicionDepartamentoUSERcount = $condicionDepartamentoUSERcount + 1;
                                        if ($value1->{"ruleta_detalle.valor"} == $detalleDepartamentoUSER) {
                                            $condicionDepartamentoUSER = true;
                                        }
                                        break;


                                    case "CONDCIUDADUSER":

                                        $condicionCiudadUSERcount = $condicionCiudadUSERcount + 1;

                                        if ($value1->{"ruleta_detalle.valor"} == $detalleCiudadUSER) {

                                            $condicionCiudadUSER = true;
                                        }
                                        break;


                                    case "CONDGAME":


                                        if ($arg7 == $value1->{"ruleta_detalle.valor"}) {

                                            $cumpleCondicionesProd = true;
                                        }

                                        $condicionesProducto++;
                                        break;


                                    case "CONDPAISUSER":

                                        $condicionPaisUSERcount = $condicionPaisUSERcount + 1;

                                        if ($value1->{"ruleta_detalle.valor"} == $arg1) {
                                            $condicionPaisUSER = true;
                                        }

                                        break;


                                    case "CONDSUBPROVIDER":

                                        if ($arg6 == $value1->{"ruleta_detalle.valor"}) {
                                            $cumpleCondicionesSubProveedor = true;
                                        }

                                        $condicionesSubprovider++;
                                        break;

                                    case "CONDCATEGORY":

                                        if ($arg5 == $value1->{"ruleta_detalle.valor"}) {
                                            $cumpleCondicionCategory = true;
                                        }

                                        $condicionesCategory++;
                                        break;

                                    case "EXPIRACIONFECHA":

                                        $fechaExpiracion = $value1->{"ruleta_detalle.valor"};
                                        break;

                                    case "EXPIRACIONDIA":

                                        $expiracionDias = $value1->{"ruleta_detalle.valor"};
                                        break;

                                    case "CSVREGISTRADO":

                                        $csvUsuarios = $value1->{"ruleta_detalle.valor"};
                                        break;
                                    case "PARAPRIMERLOGINDIA":

                                        $PARAPRIMERLOGINDIA = $value1->{"ruleta_detalle.valor"};
                                        break;
                                    case "ESPARAREGISTRO":

                                        $ESPARAREGISTRO = $value1->{"ruleta_detalle.valor"};
                                        break;

                                    default:

                                        break;
                                }

                            }

                            if ($condicionPaisUSERcount > 0 && !$condicionPaisUSER) {
                                $cumpleCondiciones = false;
                            }

                            if ($condicionesProducto > 0 && !$cumpleCondicionesProd) {
                                $cumpleCondiciones = false;
                            }

                            if ($condicionDepartamentoUSERcount > 0 && !$condicionDepartamentoUSER) {
                                $cumpleCondiciones = false;

                            }

                            if ($condicionCiudadUSERcount > 0 && !$condicionCiudadUSER) {
                                $cumpleCondiciones = false;
                            }


                            if ($condicionesSubprovider > 0 && !$cumpleCondicionesSubProveedor) {
                                $cumpleCondiciones = false;
                            }

                            if ($condicionesCategory > 0 && !$cumpleCondicionCategory) {
                                $cumpleCondiciones = false;
                            }


                            if ($cumpleCondiciones) {
                                if (!$puederepetirRuleta) {

                                    $SkeepRows = 0;
                                    $MaxRows = 10;
                                    $rules = [];


                                    array_push($rules, array("field" => "usuario_ruleta.estado", "data" => "'PR'", "op" => "ni"));
                                    array_push($rules, array("field" => "usuario_ruleta.usuario_id", "data" => $arg2, "op" => "eq"));
                                    array_push($rules, array("field" => "usuario_ruleta.ruleta_id", "data" => $ruletaId, "op" => "eq"));
                                    array_push($rules, array("field" => "ruleta_interno.tipo", "data" => intval($arg4), "op" => "eq"));

                                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                                    $json2 = json_encode($filtro);


                                    $UsuarioRuleta = new UsuarioRuleta();

                                    $UsuariosRuletas = $UsuarioRuleta->getUsuarioRuletaCustom("usuario_ruleta.*", "usuario_ruleta.usuruleta_id", "desc", $SkeepRows, $MaxRows, $json2, true);
                                    $UsuariosRuletas = json_decode($UsuariosRuletas);


                                    if ($UsuariosRuletas->count[0]->{".count"} > 0) {
                                        $cumpleCondiciones = false;
                                    }
                                }
                            }

                            // Se valida que el usuario no tenga contingencia de abusador de bonos activa
                            $UsuarioConfiguracion = new UsuarioConfiguracion();
                            $UsuarioConfiguracion = $UsuarioConfiguracion->getUserBondABuser($UsuarioMandante->usuarioMandante);
                            if (!empty($UsuarioConfiguracion->usuconfigId)) {
                                $BonoInterno = new BonoInterno();
                                $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
                                $transaccion = $BonoDetalleMySqlDAO->getTransaction();
                                //Registro de log al intentar asignar premio de ruleta a un usuario con contingencia activa
                                $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                VALUES ('$UsuarioMandante->usuarioMandante', 'RULETA', '$ruletaId', '$arg4', '$arg8', 'Contingencia activa: Abusador de bonos')";
                                $BonoInterno->execQuery($transaccion, $sqlLog);
                                $transaccion->commit();
                                $cumpleCondiciones = false;
                            }

                            if ($cumpleCondiciones) {

                                $SkeepRows = 0;
                                $MaxRows = 1;
                                $rules = [];

                                /* POSIBLES ESTADOS
                                    PR: Pendiente Rollover
                                    P: Pendiente
                                    L: Libre
                                    A: Activa
                                    NR: No redimida. Es el mismo estado de PR
                                */
                                array_push($rules, array("field" => "usuario_ruleta.estado", "data" => "PR", "op" => "eq")); //usuario_ruleta estado A   limit 1
                                array_push($rules, array("field" => "usuario_ruleta.usuario_id", "data" => $arg2, "op" => "eq"));
                                array_push($rules, array("field" => "usuario_ruleta.ruleta_id", "data" => $ruletaId, "op" => "eq"));
                                array_push($rules, array("field" => "ruleta_interno.tipo", "data" => intval($arg4), "op" => "eq"));

                                $filtro = array("rules" => $rules, "groupOp" => "AND");
                                $json2 = json_encode($filtro);


                                $UsuarioRuleta = new UsuarioRuleta();

                                $UsuariosRuletas = $UsuarioRuleta->getUsuarioRuletaCustom("usuario_ruleta.*", "usuario_ruleta.usuruleta_id", "desc", $SkeepRows, $MaxRows, $json2, true);
                                $UsuariosRuletas = json_decode($UsuariosRuletas);

                                if (!empty($fechaExpiracion) && strtotime($fechaExpiracion) < time()) {
                                    continue;
                                }

                                if ($UsuariosRuletas->count[0]->{".count"} == 0) {

                                    if ($csvUsuarios != '1' && $PARAPRIMERLOGINDIA !='1' && $ESPARAREGISTRO !='1') {

                                        $SkeepRows = 0;
                                        $MaxRows = 10;
                                        $rules = [];
                                        //array_push($rules, array("field" => "usuario_ruleta.estado", "data" => "'A','P','R','PR'", "op" => "in"));
                                        array_push($rules, array("field" => "usuario_ruleta.estado", "data" => "'A','P','R','PR','NR','PP'", "op" => "in"));
                                        array_push($rules, array("field" => "usuario_ruleta.usuario_id", "data" => $arg2, "op" => "eq"));
                                        array_push($rules, array("field" => "usuario_ruleta.ruleta_id", "data" => $ruletaId, "op" => "eq"));
                                        array_push($rules, array("field" => "usuario_ruleta.fecha_crea", "data" => date("Y-m-d 00:00:00"), "op" => "ge"));
                                        array_push($rules, array("field" => "usuario_ruleta.fecha_crea", "data" => date("Y-m-d 23:59:59"), "op" => "le"));
                                        array_push($rules, array("field" => "ruleta_interno.tipo", "data" => intval($arg4), "op" => "eq"));

                                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                                    $json2 = json_encode($filtro);

                                    $UsuariosRuletas = $UsuarioRuleta->getUsuarioRuletaCustom("usuario_ruleta.*", "usuario_ruleta.usuruleta_id", "desc", $SkeepRows, $MaxRows, $json2, true);
                                    $UsuariosRuletas = json_decode($UsuariosRuletas);

                                    if ($UsuariosRuletas->count[0]->{".count"} < $MAXDAILYSPINS) {

                                        $UsuarioRuleta = new UsuarioRuleta();

                                        $RuletaDetalleMySqlDAO = new RuletaDetalleMySqlDAO();
                                        $transaccion = $RuletaDetalleMySqlDAO->getTransaction();
                                        $UsuarioRuleta = new UsuarioRuleta();
                                        $UsuarioRuleta->ruletaId = $ruletaId;
                                        $UsuarioRuleta->usuarioId = $arg2;
                                        $UsuarioRuleta->valor = 0;
                                        $UsuarioRuleta->posicion = 0;
                                        $UsuarioRuleta->valorBase = $MINBETPRICE;
                                        $UsuarioRuleta->fechaCrea = 0;
                                        $UsuarioRuleta->usucreaId = 0;
                                        $UsuarioRuleta->fechaModif = 0;
                                        $UsuarioRuleta->usumodifId = 0;
                                        if ($arg3 < $MINBETPRICE) {

                                            $UsuarioRuleta->estado = "PR";

                                        } else {

                                            $UsuarioRuleta->estado = "A";
                                        }


                                        $fechaExpiracionTmp=$fechaExpiracion;
                                        if(!empty($expiracionDias)){
                                            $fechaExpiracionTmp = date("Y-m-d H:i:s", strtotime('+ '. $expiracionDias .' days'));
                                        }
                                        $UsuarioRuleta->fechaExpiracion = $fechaExpiracionTmp;

                                        $UsuarioRuleta->errorId = 0;
                                        $UsuarioRuleta->idExterno = 0;
                                        $UsuarioRuleta->mandante = 0;
                                        $UsuarioRuleta->version = 0;
                                        $UsuarioRuleta->apostado = floatval($arg3);
                                        $UsuarioRuleta->codigo = 0;
                                        $UsuarioRuleta->externoId = 0;
                                        $UsuarioRuleta->valorPremio = 0;
                                        $UsuarioRuleta->premio = "";
                                        $UsuarioRuletaMySqlDAO = new UsuarioRuletaMySqlDAO($transaccion);
                                        $UsuarioRuletaMySqlDAO->insert($UsuarioRuleta);
                                        $transaccion->commit();

                                        //Se valida si la ruleta está activa para enviar por web socket al front
                                        if ($UsuarioRuleta->estado == "A") {
                                            $websocketNotification = new WebsocketNotificacion();
                                            $websocketNotificacionMySqlDAO = new WebsocketNotificacionMySqlDAO();
                                            $transaccion = $websocketNotificacionMySqlDAO->getTransaction();

                                            $websocketNotification->setTipo('ruleta');
                                            $websocketNotification->setCanal('');

                                            $ruleta = $UsuarioRuleta->getRuletasUsuarioMandante($UsuarioMandante,$UsuarioRuleta->usuruletaId);
                                            $ruleta = !empty($ruleta) ? json_encode(['roulette' => $ruleta]) : '';

                                            $websocketNotification->setMensaje($ruleta,$UsuarioRuleta->usuruletaId);
                                            $websocketNotification->setEstado('P');
                                            $websocketNotification->setUsuarioId($UsuarioMandante->getUsuarioMandante());
                                            $websocketNotification->setValor($UsuarioRuleta->usuruletaId);
                                            $websocketNotification->setFecha_exp(date('Y-m-d H:i:s', strtotime('+6 hour')));

                                            $websocketNotificacionMySqlDAO->insert($websocketNotification);
                                            $transaccion->commit();
                                        }


                                        } else {
                                            $cumpleCondiciones = false;
                                        }
                                    }else{
                                        $cumpleCondiciones = false;

                                    }

                                } else {


                                    foreach ($UsuariosRuletas->data as $keys => $values) {
                                        $RuletaDetalleMySqlDAO = new RuletaDetalleMySqlDAO();
                                        $transaccion = $RuletaDetalleMySqlDAO->getTransaction();

                                        $usuruletaId = $values->{"usuario_ruleta.usuruleta_id"};

                                        $UsuarioRuleta = new UsuarioRuleta($usuruletaId);

                                        if ($UsuarioRuleta->valorBase == 0) {


                                            $UsuarioRuleta->setValorBase($MINBETPRICE);

                                            $UsuarioRuletaMySqlDAO = new UsuarioRuletaMySqlDAO($transaccion);
                                            $UsuarioRuletaMySqlDAO->update($UsuarioRuleta);

                                        }

                                        $BonoInterno = new BonoInterno();


                                        $sql = "UPDATE usuario_ruleta SET apostado = apostado + " . (floatval($arg3)) . " WHERE usuruleta_id =" . $usuruletaId;
                                        $BonoInterno->execQuery($transaccion, $sql);

                                        $transaccion->commit();

                                    }


                                }

                                if ($cumpleCondiciones) {

                                    $BonoInterno = new BonoInterno();
                                    $RuletaDetalleMySqlDAO = new RuletaDetalleMySqlDAO();
                                    $transaccion = $RuletaDetalleMySqlDAO->getTransaction();

                                    $conndicionDiasExpiracion = (!empty($expiracionDias)) ? " AND DATE_ADD(usuario_ruleta.fecha_crea, INTERVAL " . $expiracionDias . " DAY) >= NOW()" : '';

                                    $sql = "SELECT ur.usuruleta_id FROM usuario_ruleta ur WHERE ur.ruleta_id =" . $ruletaId . " AND ur.usuario_id=" . $arg2 . " AND ur.estado='PR'  AND ur.apostado >= ur.valor_base $conndicionDiasExpiracion";

                                    $usuruletaId = $this->execQuery($transaccion, $sql);

                                    $sql = "UPDATE usuario_ruleta SET estado = 'A'  WHERE ruleta_id =" . $ruletaId . " AND usuario_id=" . $arg2 . " AND estado='PR'  AND usuario_ruleta.apostado >= usuario_ruleta.valor_base $conndicionDiasExpiracion";

                                    $result = $BonoInterno->execQuery($transaccion, $sql);

                                    //Se valida si hay ruletas para enviar por web socket
                                    if ($result > 0) {
                                        if (!empty($usuruletaId)) {
                                            $usuruletaId = $usuruletaId[0]->{"ur.usuruleta_id"};

                                            $websocketNotification = new WebsocketNotificacion();
                                            $websocketNotificacionMySqlDAO = new WebsocketNotificacionMySqlDAO($transaccion);

                                            $websocketNotification->setTipo('ruleta');
                                            $websocketNotification->setCanal('');

                                            $ruleta = $UsuarioRuleta->getRuletasUsuarioMandante($UsuarioMandante,$usuruletaId);
                                            $ruleta = !empty($ruleta) ? json_encode(['roulette' => $ruleta]) : '';

                                            $websocketNotification->setMensaje($ruleta);
                                            $websocketNotification->setEstado('P');
                                            $websocketNotification->setUsuarioId($UsuarioMandante->getUsuarioMandante());
                                            $websocketNotification->setValor($usuruletaId);
                                            $websocketNotification->setFecha_exp(date('Y-m-d H:i:s', strtotime('+6 hour')));
                                            $websocketNotificacionMySqlDAO->insert($websocketNotification);
                                        }
                                        $transaccion->commit();
                                        $cumpleCondicionesGlobal = true;
                                    } else {
                                        continue;
                                    }
                                }


                            }

                        }
                    }

                }


                break;
            case 3: //LIVECASINO

                if (true) {

                    $SkeepRows = 0;
                    $MaxRows = 1000;

                    $rules = [];
                    array_push($rules, array("field" => "ruleta_interno.estado", "data" => "A", "op" => "eq"));
                    array_push($rules, array("field" => "ruleta_interno.fecha_inicio", "data" => date("Y-m-d H:i:s"), "op" => "le"));
                    array_push($rules, array("field" => "ruleta_interno.fecha_fin", "data" => date("Y-m-d H:i:s"), "op" => "ge"));
                    array_push($rules, array("field" => "ruleta_interno.tipo", "data" => intval($arg4), "op" => "eq"));
                    array_push($rules, array("field" => "ruleta_interno.mandante", "data" => $mandante, "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                    $json2 = json_encode($filtro);

                    $Ruletas = $this->getRuletasCustom("ruleta_interno.*", "ruleta_interno.orden,ruleta_interno.ruleta_id", "desc", $SkeepRows, $MaxRows, $json2, true);

                    $Ruletas = json_decode($Ruletas);


                    $final = array();
                    foreach ($Ruletas->data as $key => $value) {
                        $array = [];

                        $array["ruletaId"] = $value->{"ruleta_interno.ruleta_id"};

                        array_push($final, $array);
                    }


                    foreach ($final as $key => $value) {
                        if (!$cumpleCondicionesGlobal) {
                            $ruletaId = $value["ruletaId"];

                            $SkeepRows = 0;
                            $MaxRows = 1000;
                            $rules = [];

                            array_push($rules, array("field" => "ruleta_interno.estado", "data" => "A", "op" => "eq"));
                            array_push($rules, array("field" => "ruleta_interno.ruleta_id", "data" => $ruletaId, "op" => "eq"));
                            array_push($rules, array("field" => "ruleta_detalle.tipo", "data" => "'TIPOPRODUCTO','MINBETPRICE','MAXDAILYSPINS', 'REPETIRSORTEO', 'CONDPAISUSER', 'CONDDEPARTAMENTOUSER',  'CONDCIUDADUSER', 'CONDGAME', 'CONDSUBPROVIDER','CONDCATEGORY','CSVREGISTRADO','PARAPRIMERLOGINDIA','ESPARAREGISTRO'", "op" => "in"));

                            $filtro = array("rules" => $rules, "groupOp" => "AND");
                            $json2 = json_encode($filtro);


                            $RuletaDetalle = new RuletaDetalle();
                            $RuletaDetalles = $RuletaDetalle->getRuletaDetallesCustom2("ruleta_detalle.*,ruleta_interno.*", "ruleta_detalle.ruletadetalle_id", "desc", $SkeepRows, $MaxRows, $json2, true, 'ruleta_detalle.ruletadetalle_id');
                            $RuletaDetalles = json_decode($RuletaDetalles);


                            $cumpleCondiciones = true;
                            $TIPOPRODUCTO = 0;
                            $MINBETPRICE = 0;

                            $condicionPaisUSERcount = 0;
                            $MAXDAILYSPINS = 0;
                            $fechaExpiracion = '';
                            $expiracionDias = '';
                            $puederepetirRuleta = false;
                            $condicionDepartamentoUSERcount = 0;
                            $condicionDepartamentoUSER = false;
                            $condicionCiudadUSERcount = 0;
                            $condicionCiudadUSER = false;
                            $condicionesProducto = 0;
                            $condicionesSubprovider = 0;
                            $condicionesCategory = 0;
                            $cumpleCondicionesProd = false;
                            $cumpleCondicionesSubProveedor = false;
                            $cumpleCondicionCategory = false;
                            $csvUsuarios = '';
                            $PARAPRIMERLOGINDIA = '';
                            $ESPARAREGISTRO = '';


                            foreach ($RuletaDetalles->data as $key1 => $value1) {

                                switch ($value1->{"ruleta_detalle.tipo"}) {


                                    case "TIPOPRODUCTO":

                                        $TIPOPRODUCTO = $value1->{"ruleta_detalle.valor"};

                                        break;


                                    case "MINBETPRICE":
                                        if ($value1->{"ruleta_detalle.moneda"} == $detalleMonedaUSER) {


                                            $MINBETPRICE = $value1->{"ruleta_detalle.valor"};
                                        }

                                        break;


                                    case "MAXDAILYSPINS":
                                        if ($value1->{"ruleta_detalle.moneda"} == $detalleMonedaUSER) {

                                            $MAXDAILYSPINS = $value1->{"ruleta_detalle.valor"};
                                        }
                                        break;


                                    case "REPETIRSORTEO":

                                        if ($value1->{"ruleta_detalle.valor"} == '1') {

                                            $puederepetirRuleta = true;
                                        }

                                        break;


                                    case "CONDDEPARTAMENTOUSER":

                                        $condicionDepartamentoUSERcount = $condicionDepartamentoUSERcount + 1;
                                        if ($value1->{"ruleta_detalle.valor"} == $detalleDepartamentoUSER) {
                                            $condicionDepartamentoUSER = true;
                                        }
                                        break;


                                    case "CONDCIUDADUSER":

                                        $condicionCiudadUSERcount = $condicionCiudadUSERcount + 1;

                                        if ($value1->{"ruleta_detalle.valor"} == $detalleCiudadUSER) {

                                            $condicionCiudadUSER = true;
                                        }
                                        break;


                                    case "CONDGAME":


                                        if ($arg7 == $value1->{"ruleta_detalle.valor"}) {

                                            $cumpleCondicionesProd = true;
                                        }

                                        $condicionesProducto++;
                                        break;


                                    case "CONDPAISUSER":

                                        $condicionPaisUSERcount = $condicionPaisUSERcount + 1;

                                        if ($value1->{"ruleta_detalle.valor"} == $arg1) {
                                            $condicionPaisUSER = true;
                                        }

                                        break;


                                    case "CONDSUBPROVIDER":

                                        if ($arg6 == $value1->{"ruleta_detalle.valor"}) {
                                            $cumpleCondicionesSubProveedor = true;
                                        }

                                        $condicionesSubprovider++;
                                        break;

                                    case "CONDCATEGORY":

                                        if ($arg5 == $value1->{"ruleta_detalle.valor"}) {
                                            $cumpleCondicionCategory = true;
                                        }

                                        $condicionesCategory++;
                                        break;

                                    case "CSVREGISTRADO":

                                        $csvUsuarios = $value1->{"ruleta_detalle.valor"};
                                        break;
                                    case "PARAPRIMERLOGINDIA":

                                        $PARAPRIMERLOGINDIA = $value1->{"ruleta_detalle.valor"};
                                        break;
                                    case "ESPARAREGISTRO":

                                        $ESPARAREGISTRO = $value1->{"ruleta_detalle.valor"};
                                        break;

                                    case "EXPIRACIONFECHA":

                                        $fechaExpiracion = $value1->{"ruleta_detalle.valor"};
                                        break;

                                    case "EXPIRACIONDIA":

                                        $expiracionDias = $value1->{"ruleta_detalle.valor"};
                                        break;

                                    default:

                                        break;
                                }

                            }

                            if ($condicionPaisUSERcount > 0 && !$condicionPaisUSER) {
                                $cumpleCondiciones = false;
                            }

                            if ($condicionesProducto > 0 && !$cumpleCondicionesProd) {
                                $cumpleCondiciones = false;
                            }

                            if ($condicionDepartamentoUSERcount > 0 && !$condicionDepartamentoUSER) {
                                $cumpleCondiciones = false;

                            }

                            if ($condicionCiudadUSERcount > 0 && !$condicionCiudadUSER) {
                                $cumpleCondiciones = false;
                            }


                            if ($condicionesSubprovider > 0 && !$cumpleCondicionesSubProveedor) {
                                $cumpleCondiciones = false;
                            }

                            if ($condicionesCategory > 0 && !$cumpleCondicionCategory) {
                                $cumpleCondiciones = false;
                            }


                            if ($cumpleCondiciones) {
                                if (!$puederepetirRuleta) {

                                    $SkeepRows = 0;
                                    $MaxRows = 10;
                                    $rules = [];


                                    array_push($rules, array("field" => "usuario_ruleta.estado", "data" => "'PR'", "op" => "ni"));
                                    array_push($rules, array("field" => "usuario_ruleta.usuario_id", "data" => $arg2, "op" => "eq"));
                                    array_push($rules, array("field" => "usuario_ruleta.ruleta_id", "data" => $ruletaId, "op" => "eq"));
                                    array_push($rules, array("field" => "ruleta_interno.tipo", "data" => intval($arg4), "op" => "eq"));

                                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                                    $json2 = json_encode($filtro);


                                    $UsuarioRuleta = new UsuarioRuleta();

                                    $UsuariosRuletas = $UsuarioRuleta->getUsuarioRuletaCustom("usuario_ruleta.*", "usuario_ruleta.usuruleta_id", "desc", $SkeepRows, $MaxRows, $json2, true);
                                    $UsuariosRuletas = json_decode($UsuariosRuletas);


                                    if ($UsuariosRuletas->count[0]->{".count"} > 0) {
                                        $cumpleCondiciones = false;
                                    }
                                }
                            }

                            // Se valida que el usuario no tenga contingencia de abusador de bonos activa
                            $UsuarioConfiguracion = new UsuarioConfiguracion();
                            $UsuarioConfiguracion = $UsuarioConfiguracion->getUserBondABuser($UsuarioMandante->usuarioMandante);
                            if (!empty($UsuarioConfiguracion->usuconfigId)) {
                                $BonoInterno = new BonoInterno();
                                $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
                                $transaccion = $BonoDetalleMySqlDAO->getTransaction();
                                //Registro de log al intentar asignar premio de ruleta a un usuario con contingencia activa
                                $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                VALUES ('$UsuarioMandante->usuarioMandante', 'RULETA', '$ruletaId', '$arg4', '$arg8', 'Contingencia activa: Abusador de bonos')";
                                $BonoInterno->execQuery($transaccion, $sqlLog);
                                $transaccion->commit();
                                $cumpleCondiciones = false;
                            }

                            if ($cumpleCondiciones) {

                                $SkeepRows = 0;
                                $MaxRows = 10;
                                $rules = [];

                                /* POSIBLES ESTADOS
                                    PR: Pendiente Rollover
                                    P: Pendiente
                                    L: Libre
                                    A: Activa
                                    NR: No redimida. Es el mismo estado de PR
                                */
                                array_push($rules, array("field" => "usuario_ruleta.estado", "data" => "PR", "op" => "eq")); //usuario_ruleta estado A   limit 1
                                array_push($rules, array("field" => "usuario_ruleta.usuario_id", "data" => $arg2, "op" => "eq"));
                                array_push($rules, array("field" => "usuario_ruleta.ruleta_id", "data" => $ruletaId, "op" => "eq"));
                                array_push($rules, array("field" => "ruleta_interno.tipo", "data" => intval($arg4), "op" => "eq"));

                                $filtro = array("rules" => $rules, "groupOp" => "AND");
                                $json2 = json_encode($filtro);

                                if (!empty($fechaExpiracion) && strtotime($fechaExpiracion) < time()) {
                                    continue;
                                }

                                $UsuarioRuleta = new UsuarioRuleta();

                                $UsuariosRuletas = $UsuarioRuleta->getUsuarioRuletaCustom("usuario_ruleta.*", "usuario_ruleta.usuruleta_id", "desc", $SkeepRows, $MaxRows, $json2, true);
                                $UsuariosRuletas = json_decode($UsuariosRuletas);


                                if ($UsuariosRuletas->count[0]->{".count"} == 0) {

                                    if ($csvUsuarios != '1' && $PARAPRIMERLOGINDIA !='1' && $ESPARAREGISTRO !='1') {

                                        $SkeepRows = 0;
                                        $MaxRows = 10;
                                        $rules = [];
                                        //array_push($rules, array("field" => "usuario_ruleta.estado", "data" => "'A','P','R','PR'", "op" => "in"));
                                        array_push($rules, array("field" => "usuario_ruleta.estado", "data" => "'A','P','R','PR','NR','PP'", "op" => "in"));
                                        array_push($rules, array("field" => "usuario_ruleta.usuario_id", "data" => $arg2, "op" => "eq"));
                                        array_push($rules, array("field" => "usuario_ruleta.ruleta_id", "data" => $ruletaId, "op" => "eq"));
                                        array_push($rules, array("field" => "usuario_ruleta.fecha_crea", "data" => date("Y-m-d 00:00:00"), "op" => "ge"));
                                        array_push($rules, array("field" => "usuario_ruleta.fecha_crea", "data" => date("Y-m-d 23:59:59"), "op" => "le"));
                                        array_push($rules, array("field" => "ruleta_interno.tipo", "data" => intval($arg4), "op" => "eq"));

                                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                                    $json2 = json_encode($filtro);

                                    $UsuariosRuletas = $UsuarioRuleta->getUsuarioRuletaCustom("usuario_ruleta.*", "usuario_ruleta.usuruleta_id", "desc", $SkeepRows, $MaxRows, $json2, true);
                                    $UsuariosRuletas = json_decode($UsuariosRuletas);

                                    if ($UsuariosRuletas->count[0]->{".count"} < $MAXDAILYSPINS) {

                                        $UsuarioRuleta = new UsuarioRuleta();

                                        $RuletaDetalleMySqlDAO = new RuletaDetalleMySqlDAO();
                                        $transaccion = $RuletaDetalleMySqlDAO->getTransaction();
                                        $UsuarioRuleta = new UsuarioRuleta();
                                        $UsuarioRuleta->ruletaId = $ruletaId;
                                        $UsuarioRuleta->usuarioId = $arg2;
                                        $UsuarioRuleta->valor = 0;
                                        $UsuarioRuleta->posicion = 0;
                                        $UsuarioRuleta->valorBase = $MINBETPRICE;
                                        $UsuarioRuleta->fechaCrea = 0;
                                        $UsuarioRuleta->usucreaId = 0;
                                        $UsuarioRuleta->fechaModif = 0;
                                        $UsuarioRuleta->usumodifId = 0;
                                        if ($arg3 < $MINBETPRICE) {

                                            $UsuarioRuleta->estado = "PR";

                                        } else {

                                            $UsuarioRuleta->estado = "A";
                                        }

                                        $fechaExpiracionTmp=$fechaExpiracion;
                                        if(!empty($expiracionDias)){
                                            $fechaExpiracionTmp = date("Y-m-d H:i:s", strtotime('+ '. $expiracionDias .' days'));
                                        }
                                        $UsuarioRuleta->fechaExpiracion = $fechaExpiracionTmp;


                                        $UsuarioRuleta->errorId = 0;
                                        $UsuarioRuleta->idExterno = 0;
                                        $UsuarioRuleta->mandante = 0;
                                        $UsuarioRuleta->version = 0;
                                        $UsuarioRuleta->apostado = floatval($arg3);
                                        $UsuarioRuleta->codigo = 0;
                                        $UsuarioRuleta->externoId = 0;
                                        $UsuarioRuleta->valorPremio = 0;
                                        $UsuarioRuleta->premio = "";
                                        $UsuarioRuletaMySqlDAO = new UsuarioRuletaMySqlDAO($transaccion);
                                        $UsuarioRuletaMySqlDAO->insert($UsuarioRuleta);
                                        $transaccion->commit();

                                        //Se valida si la ruleta está activa para enviar por web socket al front
                                        if ($UsuarioRuleta->estado == "A") {
                                            $websocketNotification = new WebsocketNotificacion();
                                            $websocketNotificacionMySqlDAO = new WebsocketNotificacionMySqlDAO();
                                            $transaccion = $websocketNotificacionMySqlDAO->getTransaction();

                                            $websocketNotification->setTipo('ruleta');
                                            $websocketNotification->setCanal('');

                                            $ruleta = $UsuarioRuleta->getRuletasUsuarioMandante($UsuarioMandante,$UsuarioRuleta->usuruletaId);
                                            $ruleta = !empty($ruleta) ? json_encode(['roulette' => $ruleta]) : '';

                                            $websocketNotification->setMensaje($ruleta);
                                            $websocketNotification->setEstado('P');
                                            $websocketNotification->setUsuarioId($UsuarioMandante->getUsuarioMandante());
                                            $websocketNotification->setValor($UsuarioRuleta->usuruletaId);
                                            $websocketNotification->setFecha_exp(date('Y-m-d H:i:s', strtotime('+6 hour')));

                                            $websocketNotificacionMySqlDAO->insert($websocketNotification);
                                            $transaccion->commit();
                                        }


                                        } else {
                                            $cumpleCondiciones = false;
                                        }
                                    }else{
                                        $cumpleCondiciones = false;

                                    }

                                } else {


                                    foreach ($UsuariosRuletas->data as $keys => $values) {
                                        $RuletaDetalleMySqlDAO = new RuletaDetalleMySqlDAO();
                                        $transaccion = $RuletaDetalleMySqlDAO->getTransaction();

                                        $usuruletaId = $values->{"usuario_ruleta.usuruleta_id"};

                                        $UsuarioRuleta = new UsuarioRuleta($usuruletaId);

                                        if ($UsuarioRuleta->valorBase == 0) {


                                            $UsuarioRuleta->setValorBase($MINBETPRICE);

                                            $UsuarioRuletaMySqlDAO = new UsuarioRuletaMySqlDAO($transaccion);
                                            $UsuarioRuletaMySqlDAO->update($UsuarioRuleta);

                                        }

                                        $BonoInterno = new BonoInterno();


                                        $sql = "UPDATE usuario_ruleta SET apostado = apostado + " . (floatval($arg3)) . " WHERE usuruleta_id =" . $usuruletaId;
                                        $BonoInterno->execQuery($transaccion, $sql);

                                        $transaccion->commit();

                                    }


                                }

                                if ($cumpleCondiciones) {

                                    $BonoInterno = new BonoInterno();
                                    $RuletaDetalleMySqlDAO = new RuletaDetalleMySqlDAO();
                                    $transaccion = $RuletaDetalleMySqlDAO->getTransaction();

                                    $conndicionDiasExpiracion = (!empty($expiracionDias)) ? " AND DATE_ADD(usuario_ruleta.fecha_crea, INTERVAL " . $expiracionDias . " DAY) >= NOW()" : '';

                                    $sql = "SELECT ur.usuruleta_id FROM usuario_ruleta ur WHERE ur.ruleta_id =" . $ruletaId . " AND ur.usuario_id=" . $arg2 . " AND ur.estado='PR'  AND ur.apostado >= ur.valor_base $conndicionDiasExpiracion";

                                    $usuruletaId = $this->execQuery($transaccion, $sql);

                                    $sql = "UPDATE usuario_ruleta SET estado = 'A'  WHERE ruleta_id =" . $ruletaId . " AND usuario_id=" . $arg2 . " AND estado='PR'  AND usuario_ruleta.apostado >= usuario_ruleta.valor_base $conndicionDiasExpiracion";

                                    $result = $BonoInterno->execQuery($transaccion, $sql);

                                    //Se valida si hay ruletas para enviar por web socket
                                    if ($result > 0) {
                                        if (!empty($usuruletaId)) {
                                            $usuruletaId = $usuruletaId[0]->{"ur.usuruleta_id"};

                                            $websocketNotification = new WebsocketNotificacion();
                                            $websocketNotificacionMySqlDAO = new WebsocketNotificacionMySqlDAO($transaccion);

                                            $websocketNotification->setTipo('ruleta');
                                            $websocketNotification->setCanal('');

                                            $ruleta = $UsuarioRuleta->getRuletasUsuarioMandante($UsuarioMandante,$usuruletaId);
                                            $ruleta = !empty($ruleta) ? json_encode(['roulette' => $ruleta]) : '';

                                            $websocketNotification->setMensaje($ruleta);
                                            $websocketNotification->setEstado('P');
                                            $websocketNotification->setUsuarioId($UsuarioMandante->getUsuarioMandante());
                                            $websocketNotification->setValor($usuruletaId);
                                            $websocketNotification->setFecha_exp(date('Y-m-d H:i:s', strtotime('+6 hour')));
                                            $websocketNotificacionMySqlDAO->insert($websocketNotification);
                                        }
                                        $transaccion->commit();
                                        $cumpleCondicionesGlobal = true;
                                    } else {
                                        continue;
                                    }
                                }


                            }

                        }
                    }

                }


                break;
            case 4: //VIRTUALES

                if (true) {

                    $SkeepRows = 0;
                    $MaxRows = 1000;

                    $rules = [];
                    array_push($rules, array("field" => "ruleta_interno.estado", "data" => "A", "op" => "eq"));
                    array_push($rules, array("field" => "ruleta_interno.fecha_inicio", "data" => date("Y-m-d H:i:s"), "op" => "le"));
                    array_push($rules, array("field" => "ruleta_interno.fecha_fin", "data" => date("Y-m-d H:i:s"), "op" => "ge"));
                    array_push($rules, array("field" => "ruleta_interno.tipo", "data" => intval($arg4), "op" => "eq"));
                    array_push($rules, array("field" => "ruleta_interno.mandante", "data" => $mandante, "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                    $json2 = json_encode($filtro);

                    $Ruletas = $this->getRuletasCustom("ruleta_interno.*", "ruleta_interno.orden,ruleta_interno.ruleta_id", "desc", $SkeepRows, $MaxRows, $json2, true);

                    $Ruletas = json_decode($Ruletas);


                    $final = array();
                    foreach ($Ruletas->data as $key => $value) {
                        $array = [];

                        $array["ruletaId"] = $value->{"ruleta_interno.ruleta_id"};

                        array_push($final, $array);
                    }


                    foreach ($final as $key => $value) {
                        if (!$cumpleCondicionesGlobal) {
                            $ruletaId = $value["ruletaId"];

                            $SkeepRows = 0;
                            $MaxRows = 1000;
                            $rules = [];

                            array_push($rules, array("field" => "ruleta_interno.estado", "data" => "A", "op" => "eq"));
                            array_push($rules, array("field" => "ruleta_interno.ruleta_id", "data" => $ruletaId, "op" => "eq"));
                            array_push($rules, array("field" => "ruleta_detalle.tipo", "data" => "'TIPOPRODUCTO','MINBETPRICE','MAXDAILYSPINS', 'REPETIRSORTEO', 'CONDPAISUSER', 'CONDDEPARTAMENTOUSER',  'CONDCIUDADUSER', 'CONDGAME', 'CONDSUBPROVIDER','CONDCATEGORY','CSVREGISTRADO','PARAPRIMERLOGINDIA','ESPARAREGISTRO'", "op" => "in"));

                            $filtro = array("rules" => $rules, "groupOp" => "AND");
                            $json2 = json_encode($filtro);


                            $RuletaDetalle = new RuletaDetalle();
                            $RuletaDetalles = $RuletaDetalle->getRuletaDetallesCustom2("ruleta_detalle.*,ruleta_interno.*", "ruleta_detalle.ruletadetalle_id", "desc", $SkeepRows, $MaxRows, $json2, true, 'ruleta_detalle.ruletadetalle_id');
                            $RuletaDetalles = json_decode($RuletaDetalles);


                            $cumpleCondiciones = true;
                            $TIPOPRODUCTO = 0;
                            $MINBETPRICE = 0;

                            $condicionPaisUSERcount = 0;
                            $MAXDAILYSPINS = 0;
                            $fechaExpiracion = '';
                            $expiracionDias = '';
                            $puederepetirRuleta = false;
                            $condicionDepartamentoUSERcount = 0;
                            $condicionDepartamentoUSER = false;
                            $condicionCiudadUSERcount = 0;
                            $condicionCiudadUSER = false;
                            $condicionesProducto = 0;
                            $condicionesSubprovider = 0;
                            $condicionesCategory = 0;
                            $cumpleCondicionesProd = false;
                            $cumpleCondicionesSubProveedor = false;
                            $cumpleCondicionCategory = false;
                            $csvUsuarios = '';
                            $PARAPRIMERLOGINDIA = '';
                            $ESPARAREGISTRO = '';


                            foreach ($RuletaDetalles->data as $key1 => $value1) {

                                switch ($value1->{"ruleta_detalle.tipo"}) {


                                    case "TIPOPRODUCTO":

                                        $TIPOPRODUCTO = $value1->{"ruleta_detalle.valor"};

                                        break;


                                    case "MINBETPRICE":
                                        if ($value1->{"ruleta_detalle.moneda"} == $detalleMonedaUSER) {


                                            $MINBETPRICE = $value1->{"ruleta_detalle.valor"};
                                        }

                                        break;


                                    case "MAXDAILYSPINS":
                                        if ($value1->{"ruleta_detalle.moneda"} == $detalleMonedaUSER) {

                                            $MAXDAILYSPINS = $value1->{"ruleta_detalle.valor"};
                                        }
                                        break;


                                    case "REPETIRSORTEO":

                                        if ($value1->{"ruleta_detalle.valor"} == '1') {

                                            $puederepetirRuleta = true;
                                        }

                                        break;


                                    case "CONDDEPARTAMENTOUSER":

                                        $condicionDepartamentoUSERcount = $condicionDepartamentoUSERcount + 1;
                                        if ($value1->{"ruleta_detalle.valor"} == $detalleDepartamentoUSER) {
                                            $condicionDepartamentoUSER = true;
                                        }
                                        break;


                                    case "CONDCIUDADUSER":

                                        $condicionCiudadUSERcount = $condicionCiudadUSERcount + 1;

                                        if ($value1->{"ruleta_detalle.valor"} == $detalleCiudadUSER) {

                                            $condicionCiudadUSER = true;
                                        }
                                        break;


                                    case "CONDGAME":


                                        if ($arg7 == $value1->{"ruleta_detalle.valor"}) {

                                            $cumpleCondicionesProd = true;
                                        }

                                        $condicionesProducto++;
                                        break;


                                    case "CONDPAISUSER":

                                        $condicionPaisUSERcount = $condicionPaisUSERcount + 1;

                                        if ($value1->{"ruleta_detalle.valor"} == $arg1) {
                                            $condicionPaisUSER = true;
                                        }

                                        break;


                                    case "CONDSUBPROVIDER":

                                        if ($arg6 == $value1->{"ruleta_detalle.valor"}) {
                                            $cumpleCondicionesSubProveedor = true;
                                        }

                                        $condicionesSubprovider++;
                                        break;

                                    case "CONDCATEGORY":

                                        if ($arg5 == $value1->{"ruleta_detalle.valor"}) {
                                            $cumpleCondicionCategory = true;
                                        }

                                        $condicionesCategory++;
                                        break;

                                    case "CSVREGISTRADO":

                                        $csvUsuarios = $value1->{"ruleta_detalle.valor"};
                                        break;
                                    case "PARAPRIMERLOGINDIA":

                                        $PARAPRIMERLOGINDIA = $value1->{"ruleta_detalle.valor"};
                                        break;
                                    case "ESPARAREGISTRO":

                                        $ESPARAREGISTRO = $value1->{"ruleta_detalle.valor"};
                                        break;

                                    case "EXPIRACIONFECHA":

                                        $fechaExpiracion = $value1->{"ruleta_detalle.valor"};
                                        break;

                                    case "EXPIRACIONDIA":

                                        $expiracionDias = $value1->{"ruleta_detalle.valor"};
                                        break;

                                    default:

                                        break;
                                }

                            }

                            if ($condicionPaisUSERcount > 0 && !$condicionPaisUSER) {
                                $cumpleCondiciones = false;
                            }

                            if ($condicionesProducto > 0 && !$cumpleCondicionesProd) {
                                $cumpleCondiciones = false;
                            }

                            if ($condicionDepartamentoUSERcount > 0 && !$condicionDepartamentoUSER) {
                                $cumpleCondiciones = false;

                            }

                            if ($condicionCiudadUSERcount > 0 && !$condicionCiudadUSER) {
                                $cumpleCondiciones = false;
                            }


                            if ($condicionesSubprovider > 0 && !$cumpleCondicionesSubProveedor) {
                                $cumpleCondiciones = false;
                            }

                            if ($condicionesCategory > 0 && !$cumpleCondicionCategory) {
                                $cumpleCondiciones = false;
                            }


                            if ($cumpleCondiciones) {
                                if (!$puederepetirRuleta) {

                                    $SkeepRows = 0;
                                    $MaxRows = 10;
                                    $rules = [];


                                    array_push($rules, array("field" => "usuario_ruleta.estado", "data" => "'PR'", "op" => "ni"));
                                    array_push($rules, array("field" => "usuario_ruleta.usuario_id", "data" => $arg2, "op" => "eq"));
                                    array_push($rules, array("field" => "usuario_ruleta.ruleta_id", "data" => $ruletaId, "op" => "eq"));
                                    array_push($rules, array("field" => "ruleta_interno.tipo", "data" => intval($arg4), "op" => "eq"));

                                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                                    $json2 = json_encode($filtro);

                                    if (!empty($fechaExpiracion) && strtotime($fechaExpiracion) < time()) {
                                        continue;
                                    }

                                    $UsuarioRuleta = new UsuarioRuleta();

                                    $UsuariosRuletas = $UsuarioRuleta->getUsuarioRuletaCustom("usuario_ruleta.*", "usuario_ruleta.usuruleta_id", "desc", $SkeepRows, $MaxRows, $json2, true);
                                    $UsuariosRuletas = json_decode($UsuariosRuletas);


                                    if ($UsuariosRuletas->count[0]->{".count"} > 0) {
                                        $cumpleCondiciones = false;
                                    }
                                }
                            }

                            // Se valida que el usuario no tenga contingencia de abusador de bonos activa
                            $UsuarioConfiguracion = new UsuarioConfiguracion();
                            $UsuarioConfiguracion = $UsuarioConfiguracion->getUserBondABuser($UsuarioMandante->usuarioMandante);
                            if (!empty($UsuarioConfiguracion->usuconfigId)) {
                                $BonoInterno = new BonoInterno();
                                $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
                                $transaccion = $BonoDetalleMySqlDAO->getTransaction();
                                //Registro de log al intentar asignar premio de ruleta a un usuario con contingencia activa
                                $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                VALUES ('$UsuarioMandante->usuarioMandante', 'RULETA', '$ruletaId', '$arg4', '$arg8', 'Contingencia activa: Abusador de bonos')";
                                $BonoInterno->execQuery($transaccion, $sqlLog);
                                $transaccion->commit();
                                $cumpleCondiciones = false;
                            }

                            if ($cumpleCondiciones) {

                                $SkeepRows = 0;
                                $MaxRows = 10;
                                $rules = [];

                                /* POSIBLES ESTADOS
                                    PR: Pendiente Rollover
                                    P: Pendiente
                                    L: Libre
                                    A: Activa
                                    NR: No redimida. Es el mismo estado de PR
                                */
                                array_push($rules, array("field" => "usuario_ruleta.estado", "data" => "PR", "op" => "eq")); //usuario_ruleta estado A   limit 1
                                array_push($rules, array("field" => "usuario_ruleta.usuario_id", "data" => $arg2, "op" => "eq"));
                                array_push($rules, array("field" => "usuario_ruleta.ruleta_id", "data" => $ruletaId, "op" => "eq"));
                                array_push($rules, array("field" => "ruleta_interno.tipo", "data" => intval($arg4), "op" => "eq"));

                                $filtro = array("rules" => $rules, "groupOp" => "AND");
                                $json2 = json_encode($filtro);


                                $UsuarioRuleta = new UsuarioRuleta();

                                $UsuariosRuletas = $UsuarioRuleta->getUsuarioRuletaCustom("usuario_ruleta.*", "usuario_ruleta.usuruleta_id", "desc", $SkeepRows, $MaxRows, $json2, true);
                                $UsuariosRuletas = json_decode($UsuariosRuletas);


                                if ($UsuariosRuletas->count[0]->{".count"} == 0) {

                                    if ($csvUsuarios != '1' && $PARAPRIMERLOGINDIA !='1' && $ESPARAREGISTRO !='1') {

                                        $SkeepRows = 0;
                                        $MaxRows = 10;
                                        $rules = [];
                                        //array_push($rules, array("field" => "usuario_ruleta.estado", "data" => "'A','P','R','PR'", "op" => "in"));
                                        array_push($rules, array("field" => "usuario_ruleta.estado", "data" => "'A','P','R','PR','NR','PP'", "op" => "in"));
                                        array_push($rules, array("field" => "usuario_ruleta.usuario_id", "data" => $arg2, "op" => "eq"));
                                        array_push($rules, array("field" => "usuario_ruleta.ruleta_id", "data" => $ruletaId, "op" => "eq"));
                                        array_push($rules, array("field" => "usuario_ruleta.fecha_crea", "data" => date("Y-m-d 00:00:00"), "op" => "ge"));
                                        array_push($rules, array("field" => "usuario_ruleta.fecha_crea", "data" => date("Y-m-d 23:59:59"), "op" => "le"));
                                        array_push($rules, array("field" => "ruleta_interno.tipo", "data" => intval($arg4), "op" => "eq"));

                                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                                    $json2 = json_encode($filtro);

                                    $UsuariosRuletas = $UsuarioRuleta->getUsuarioRuletaCustom("usuario_ruleta.*", "usuario_ruleta.usuruleta_id", "desc", $SkeepRows, $MaxRows, $json2, true);
                                    $UsuariosRuletas = json_decode($UsuariosRuletas);

                                    if ($UsuariosRuletas->count[0]->{".count"} < $MAXDAILYSPINS) {

                                        $UsuarioRuleta = new UsuarioRuleta();

                                        $RuletaDetalleMySqlDAO = new RuletaDetalleMySqlDAO();
                                        $transaccion = $RuletaDetalleMySqlDAO->getTransaction();
                                        $UsuarioRuleta = new UsuarioRuleta();
                                        $UsuarioRuleta->ruletaId = $ruletaId;
                                        $UsuarioRuleta->usuarioId = $arg2;
                                        $UsuarioRuleta->valor = 0;
                                        $UsuarioRuleta->posicion = 0;
                                        $UsuarioRuleta->valorBase = $MINBETPRICE;
                                        $UsuarioRuleta->fechaCrea = 0;
                                        $UsuarioRuleta->usucreaId = 0;
                                        $UsuarioRuleta->fechaModif = 0;
                                        $UsuarioRuleta->usumodifId = 0;
                                        if ($arg3 < $MINBETPRICE) {

                                            $UsuarioRuleta->estado = "PR";

                                        } else {

                                            $UsuarioRuleta->estado = "A";
                                        }

                                        $fechaExpiracionTmp=$fechaExpiracion;
                                        if(!empty($expiracionDias)){
                                            $fechaExpiracionTmp = date("Y-m-d H:i:s", strtotime('+ '. $expiracionDias .' days'));
                                        }
                                        $UsuarioRuleta->fechaExpiracion = $fechaExpiracionTmp;


                                        $UsuarioRuleta->errorId = 0;
                                        $UsuarioRuleta->idExterno = 0;
                                        $UsuarioRuleta->mandante = 0;
                                        $UsuarioRuleta->version = 0;
                                        $UsuarioRuleta->apostado = floatval($arg3);
                                        $UsuarioRuleta->codigo = 0;
                                        $UsuarioRuleta->externoId = 0;
                                        $UsuarioRuleta->valorPremio = 0;
                                        $UsuarioRuleta->premio = "";
                                        $UsuarioRuletaMySqlDAO = new UsuarioRuletaMySqlDAO($transaccion);
                                        $UsuarioRuletaMySqlDAO->insert($UsuarioRuleta);
                                        $transaccion->commit();

                                        //Se valida si la ruleta está activa para enviar por web socket al front
                                        if ($UsuarioRuleta->estado == "A") {
                                            $websocketNotification = new WebsocketNotificacion();
                                            $websocketNotificacionMySqlDAO = new WebsocketNotificacionMySqlDAO();
                                            $transaccion = $websocketNotificacionMySqlDAO->getTransaction();

                                            $websocketNotification->setTipo('ruleta');
                                            $websocketNotification->setCanal('');

                                            $ruleta = $UsuarioRuleta->getRuletasUsuarioMandante($UsuarioMandante,$UsuarioRuleta->usuruletaId);
                                            $ruleta = !empty($ruleta) ? json_encode(['roulette' => $ruleta]) : '';

                                            $websocketNotification->setMensaje($ruleta);
                                            $websocketNotification->setEstado('P');
                                            $websocketNotification->setUsuarioId($UsuarioMandante->getUsuarioMandante());
                                            $websocketNotification->setValor($UsuarioRuleta->usuruletaId);
                                            $websocketNotification->setFecha_exp(date('Y-m-d H:i:s', strtotime('+6 hour')));

                                            $websocketNotificacionMySqlDAO->insert($websocketNotification);
                                            $transaccion->commit();
                                        }


                                        } else {
                                            $cumpleCondiciones = false;
                                        }
                                    }else{
                                        $cumpleCondiciones = false;

                                    }

                                } else {


                                    foreach ($UsuariosRuletas->data as $keys => $values) {
                                        $RuletaDetalleMySqlDAO = new RuletaDetalleMySqlDAO();
                                        $transaccion = $RuletaDetalleMySqlDAO->getTransaction();

                                        $usuruletaId = $values->{"usuario_ruleta.usuruleta_id"};

                                        $UsuarioRuleta = new UsuarioRuleta($usuruletaId);

                                        if ($UsuarioRuleta->valorBase == 0) {


                                            $UsuarioRuleta->setValorBase($MINBETPRICE);

                                            $UsuarioRuletaMySqlDAO = new UsuarioRuletaMySqlDAO($transaccion);
                                            $UsuarioRuletaMySqlDAO->update($UsuarioRuleta);

                                        }

                                        $BonoInterno = new BonoInterno();


                                        $sql = "UPDATE usuario_ruleta SET apostado = apostado + " . (floatval($arg3)) . " WHERE usuruleta_id =" . $usuruletaId;
                                        $BonoInterno->execQuery($transaccion, $sql);

                                        $transaccion->commit();

                                    }


                                }

                                if ($cumpleCondiciones) {

                                    $BonoInterno = new BonoInterno();
                                    $RuletaDetalleMySqlDAO = new RuletaDetalleMySqlDAO();
                                    $transaccion = $RuletaDetalleMySqlDAO->getTransaction();

                                    $conndicionDiasExpiracion = (!empty($expiracionDias)) ? " AND DATE_ADD(usuario_ruleta.fecha_crea, INTERVAL " . $expiracionDias . " DAY) >= NOW()" : '';

                                    $sql = "SELECT ur.usuruleta_id FROM usuario_ruleta ur WHERE ur.ruleta_id =" . $ruletaId . " AND ur.usuario_id=" . $arg2 . " AND ur.estado='PR'  AND ur.apostado >= ur.valor_base $conndicionDiasExpiracion";

                                    $usuruletaId = $this->execQuery($transaccion, $sql);

                                    $sql = "UPDATE usuario_ruleta SET estado = 'A'  WHERE ruleta_id =" . $ruletaId . " AND usuario_id=" . $arg2 . " AND estado='PR'  AND usuario_ruleta.apostado >= usuario_ruleta.valor_base $conndicionDiasExpiracion";

                                    $result = $BonoInterno->execQuery($transaccion, $sql);

                                    //Se valida si hay ruletas para enviar por web socket
                                    $result = $BonoInterno->execQuery($transaccion, $sql);
                                    if ($result > 0) {
                                        if (!empty($usuruletaId)) {
                                            $usuruletaId = $usuruletaId[0]->{"ur.usuruleta_id"};

                                            $websocketNotification = new WebsocketNotificacion();
                                            $websocketNotificacionMySqlDAO = new WebsocketNotificacionMySqlDAO($transaccion);

                                            $websocketNotification->setTipo('ruleta');
                                            $websocketNotification->setCanal('');

                                            $ruleta = $UsuarioRuleta->getRuletasUsuarioMandante($UsuarioMandante,$usuruletaId);
                                            $ruleta = !empty($ruleta) ? json_encode(['roulette' => $ruleta]) : '';

                                            $websocketNotification->setMensaje($ruleta);
                                            $websocketNotification->setEstado('P');
                                            $websocketNotification->setUsuarioId($UsuarioMandante->getUsuarioMandante());
                                            $websocketNotification->setValor($usuruletaId);
                                            $websocketNotification->setFecha_exp(date('Y-m-d H:i:s', strtotime('+6 hour')));
                                            $websocketNotificacionMySqlDAO->insert($websocketNotification);
                                        }
                                        $transaccion->commit();
                                        $cumpleCondicionesGlobal = true;
                                    } else {
                                        continue;
                                    }
                                }


                            }

                        }
                    }

                }


                break;
            case 5: //DEPOSITOS

                if (true) {

                    if ($ticketId != "" && $ticketId != NULL) {

                        $UsuarioRecarga = new UsuarioRecarga($arg8);

                        $PuntoVentaId = $UsuarioRecarga->puntoventaId;

                        if ($PuntoVentaId != 0) {
                            $UsuarioPuntoVenta = new  Usuario($PuntoVentaId);
                            $PuntoVenta = new PuntoVenta('',$UsuarioPuntoVenta->puntoventaId);
                            $Ciudad = $CiudadMySqlDAO->load($PuntoVenta->ciudadId);
                            $detalleDepartamentoUSERPV = $Ciudad->deptoId;
                            $detalleCiudadUSERPV = $Ciudad->ciudadId;
                            $PaisPV = $UsuarioPuntoVenta->paisId;
                            $detalleDepositoEfectivo = true;
                        }else{
                            $detalleDepositoEfectivo = false;
                        }

                    }


                    $SkeepRows = 0;
                    $MaxRows = 1000;

                    $rules = [];
                    array_push($rules, array("field" => "ruleta_interno.estado", "data" => "A", "op" => "eq"));
                    array_push($rules, array("field" => "ruleta_interno.fecha_inicio", "data" => date("Y-m-d H:i:s"), "op" => "le"));
                    array_push($rules, array("field" => "ruleta_interno.fecha_fin", "data" => date("Y-m-d H:i:s"), "op" => "ge"));
                    array_push($rules, array("field" => "ruleta_interno.tipo", "data" => intval($arg4), "op" => "eq"));
                    array_push($rules, array("field" => "ruleta_interno.mandante", "data" => $mandante, "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                    $json2 = json_encode($filtro);

                    $Ruletas = $this->getRuletasCustom("ruleta_interno.*", "ruleta_interno.orden,ruleta_interno.ruleta_id", "desc", $SkeepRows, $MaxRows, $json2, true);

                    $Ruletas = json_decode($Ruletas);


                    $final = array();
                    foreach ($Ruletas->data as $key => $value) {
                        $array = [];

                        $array["ruletaId"] = $value->{"ruleta_interno.ruleta_id"};

                        array_push($final, $array);
                    }


                    foreach ($final as $key => $value) {
                        if (!$cumpleCondicionesGlobal) {
                            $ruletaId = $value["ruletaId"];

                            $SkeepRows = 0;
                            $MaxRows = 1000;
                            $rules = [];

                            array_push($rules, array("field" => "ruleta_interno.estado", "data" => "A", "op" => "eq"));
                            array_push($rules, array("field" => "ruleta_interno.ruleta_id", "data" => $ruletaId, "op" => "eq"));
                            array_push($rules, array("field" => "ruleta_detalle.tipo", "data" => "'TIPOPRODUCTO','MINBETPRICE','MAXDAILYSPINS', 'REPETIRSORTEO', 'CONDPAISUSER', 'CONDDEPARTAMENTOUSER',  'CONDCIUDADUSER', 'CONDPAYMENT', 'CONDPAISPV', 'CONDDEPARTAMENTOPV', 'CONDCIUDADPV', 'CONDEFECTIVO'", "op" => "in"));

                            $filtro = array("rules" => $rules, "groupOp" => "AND");
                            $json2 = json_encode($filtro);


                            $RuletaDetalle = new RuletaDetalle();
                            $RuletaDetalles = $RuletaDetalle->getRuletaDetallesCustom2("ruleta_detalle.*,ruleta_interno.*", "ruleta_detalle.ruletadetalle_id", "desc", $SkeepRows, $MaxRows, $json2, true, 'ruleta_detalle.ruletadetalle_id');
                            $RuletaDetalles = json_decode($RuletaDetalles);

                            $cumpleCondiciones = true;
                            $TIPOPRODUCTO = 0;
                            $MINBETPRICE = 0;

                            $condicionPaisUSERcount = 0;
                            $condicionPaisUSERcountPV = 0;
                            $MAXDAILYSPINS = 0;
                            $fechaExpiracion = '';
                            $expiracionDias = '';
                            $puederepetirRuleta = false;
                            $condicionmetodoPagocount = 0;
                            $condicionDepartamentoUSERcount = 0;
                            $condicionDepartamentoUSERcountPV = 0;
                            $condicionDepartamentoUSER = false;
                            $condicionDepartamentoUSERPV = false;
                            $condicionCiudadUSERcount = 0;
                            $condicionCiudadUSER = false;
                            $condicionesPayment = 0;
                            $condicionmetodoPago = false;
                            $cumpleCondicionesPayment = false;
                            $condicionPaisUSER = false;
                            $condicionPaisUSERPV = false;
                            $csvUsuarios = '';
                            $PARAPRIMERLOGINDIA = '';
                            $ESPARAREGISTRO = '';


                            foreach ($RuletaDetalles->data as $key1 => $value1) {

                                switch ($value1->{"ruleta_detalle.tipo"}) {


                                    case "TIPOPRODUCTO":

                                        $TIPOPRODUCTO = $value1->{"ruleta_detalle.valor"};

                                        break;


                                    case "MINBETPRICE":
                                        if ($value1->{"ruleta_detalle.moneda"} == $detalleMonedaUSER) {


                                            $MINBETPRICE = $value1->{"ruleta_detalle.valor"};
                                        }

                                        break;


                                    case "MAXDAILYSPINS":
                                        if ($value1->{"ruleta_detalle.moneda"} == $detalleMonedaUSER) {

                                            $MAXDAILYSPINS = $value1->{"ruleta_detalle.valor"};
                                        }
                                        break;


                                    case "REPETIRSORTEO":

                                        if ($value1->{"ruleta_detalle.valor"} == '1') {

                                            $puederepetirRuleta = true;
                                        }

                                        break;


                                    case "CONDDEPARTAMENTOUSER":

                                        $condicionDepartamentoUSERcount = $condicionDepartamentoUSERcount + 1;
                                        if ($value1->{"ruleta_detalle.valor"} == $detalleDepartamentoUSER) {
                                            $condicionDepartamentoUSER = true;
                                        }
                                        break;


                                    case "CONDDEPARTAMENTOPV":

                                        $condicionDepartamentoUSERcountPV = $condicionDepartamentoUSERcountPV + 1;
                                        if ($value1->{"ruleta_detalle.valor"} == $detalleDepartamentoUSERPV) {
                                            $condicionDepartamentoUSERPV = true;
                                        }
                                        break;


                                    case "CONDCIUDADUSER":

                                        $condicionCiudadUSERcount = $condicionCiudadUSERcount + 1;

                                        if ($value1->{"ruleta_detalle.valor"} == $detalleCiudadUSER) {

                                            $condicionCiudadUSER = true;
                                        }
                                        break;


                                    case "CONDCIUDADPV":

                                        $condicionCiudadUSERcountPV = $condicionCiudadUSERcountPV + 1;

                                        if ($value1->{"ruleta_detalle.valor"} == $detalleCiudadUSERPV) {

                                            $condicionCiudadUSERPV = true;
                                        }
                                        break;

                                    case "CONDEFECTIVO":
                                        if ($detalleDepositoEfectivo) {
                                            if (($value1->{"ruleta_detalle.valor"} == "1")) {
                                                $condicionmetodoPago = true;
                                            }
                                        }
                                        $condicionmetodoPagocount++;

                                        break;


                                    case "CONDPAYMENT":

                                        if ($arg7 == $value1->{"ruleta_detalle.valor"}) {
                                            $condicionmetodoPago = true;
                                        }
                                        $condicionmetodoPagocount++;

                                        break;


                                    case "CONDGAME":


                                        if ($arg7 == $value1->{"ruleta_detalle.valor"}) {

                                            $cumpleCondicionesPayment = true;
                                        }

                                        $condicionesPayment++;
                                        break;


                                    case "CONDPAISUSER":

                                        $condicionPaisUSERcount = $condicionPaisUSERcount + 1;

                                        if ($value1->{"ruleta_detalle.valor"} == $arg1) {
                                            $condicionPaisUSER = true;
                                        }

                                        break;

                                    case "CONDPAISPV":

                                        $condicionPaisUSERcountPV = $condicionPaisUSERcountPV + 1;

                                        if ($value1->{"ruleta_detalle.valor"} == $PaisPV) {
                                            $condicionPaisUSERPV = true;
                                        }

                                        break;

                                    case "CSVREGISTRADO":

                                        $csvUsuarios = $value1->{"ruleta_detalle.valor"};
                                        break;
                                    case "PARAPRIMERLOGINDIA":

                                        $PARAPRIMERLOGINDIA = $value1->{"ruleta_detalle.valor"};
                                        break;
                                    case "ESPARAREGISTRO":

                                        $ESPARAREGISTRO = $value1->{"ruleta_detalle.valor"};
                                        break;

                                    case "EXPIRACIONFECHA":

                                        $fechaExpiracion = $value1->{"ruleta_detalle.valor"};
                                        break;

                                    case "EXPIRACIONDIA":

                                        $expiracionDias = $value1->{"ruleta_detalle.valor"};
                                        break;

                                    default:

                                        break;
                                }

                            }

                            if ($condicionPaisUSERcount > 0 && !$condicionPaisUSER) {
                                $cumpleCondiciones = false;
                            }


                            if ($condicionPaisUSERcountPV > 0 && !$condicionPaisUSERPV) {
                                $cumpleCondiciones = false;
                            }

                            if ($condicionesPayment > 0 && !$cumpleCondicionesPayment) {
                                $cumpleCondiciones = false;
                            }

                            if ($condicionDepartamentoUSERcount > 0 && !$condicionDepartamentoUSER) {
                                $cumpleCondiciones = false;

                            }

                            if ($condicionDepartamentoUSERcountPV > 0 && !$condicionDepartamentoUSERPV) {
                                $cumpleCondiciones = false;
                            }

                            if ($condicionCiudadUSERcount > 0 && !$condicionCiudadUSER) {
                                $cumpleCondiciones = false;
                            }

                            if ($condicionCiudadUSERcountPV > 0 && !$condicionCiudadUSERPV) {
                                $cumpleCondiciones = false;
                            }
                            if (!$condicionmetodoPago && $condicionmetodoPagocount > 0) {
                                $cumpleCondiciones = false;
                            }


                            if ($cumpleCondiciones) {
                                if (!$puederepetirRuleta) {

                                    $SkeepRows = 0;
                                    $MaxRows = 10;
                                    $rules = [];


                                    array_push($rules, array("field" => "usuario_ruleta.estado", "data" => "'PR'", "op" => "ni"));
                                    array_push($rules, array("field" => "usuario_ruleta.usuario_id", "data" => $arg2, "op" => "eq"));
                                    array_push($rules, array("field" => "usuario_ruleta.ruleta_id", "data" => $ruletaId, "op" => "eq"));
                                    array_push($rules, array("field" => "ruleta_interno.tipo", "data" => intval($arg4), "op" => "eq"));

                                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                                    $json2 = json_encode($filtro);


                                    $UsuarioRuleta = new UsuarioRuleta();

                                    $UsuariosRuletas = $UsuarioRuleta->getUsuarioRuletaCustom("usuario_ruleta.*", "usuario_ruleta.usuruleta_id", "desc", $SkeepRows, $MaxRows, $json2, true);
                                    $UsuariosRuletas = json_decode($UsuariosRuletas);

                                    if (!empty($fechaExpiracion) && strtotime($fechaExpiracion) < time()) {
                                        continue;
                                    }

                                    if ($UsuariosRuletas->count[0]->{".count"} > 0) {
                                        $cumpleCondiciones = false;
                                    }
                                }
                            }

                            // Se valida que el usuario no tenga contingencia de abusador de bonos activa
                            $UsuarioConfiguracion = new UsuarioConfiguracion();
                            $UsuarioConfiguracion = $UsuarioConfiguracion->getUserBondABuser($UsuarioMandante->usuarioMandante);
                            if (!empty($UsuarioConfiguracion->usuconfigId)) {
                                $BonoInterno = new BonoInterno();
                                $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
                                $transaccion = $BonoDetalleMySqlDAO->getTransaction();
                                //Registro de log al intentar asignar premio de ruleta a un usuario con contingencia activa
                                $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                VALUES ('$UsuarioMandante->usuarioMandante', 'RULETA', '$ruletaId', '$arg4', '$arg8', 'Contingencia activa: Abusador de bonos')";
                                $BonoInterno->execQuery($transaccion, $sqlLog);
                                $transaccion->commit();
                                $cumpleCondiciones = false;
                            }

                            if ($cumpleCondiciones) {

                                $SkeepRows = 0;
                                $MaxRows = 10;
                                $rules = [];

                                /* POSIBLES ESTADOS
                                    PR: Pendiente Rollover
                                    P: Pendiente
                                    L: Libre
                                    A: Activa
                                    NR: No redimida. Es el mismo estado de PR
                                */
                                array_push($rules, array("field" => "usuario_ruleta.estado", "data" => "PR", "op" => "eq")); //usuario_ruleta estado A   limit 1
                                array_push($rules, array("field" => "usuario_ruleta.usuario_id", "data" => $arg2, "op" => "eq"));
                                array_push($rules, array("field" => "usuario_ruleta.ruleta_id", "data" => $ruletaId, "op" => "eq"));
                                array_push($rules, array("field" => "ruleta_interno.tipo", "data" => intval($arg4), "op" => "eq"));

                                $filtro = array("rules" => $rules, "groupOp" => "AND");
                                $json2 = json_encode($filtro);


                                $UsuarioRuleta = new UsuarioRuleta();

                                $UsuariosRuletas = $UsuarioRuleta->getUsuarioRuletaCustom("usuario_ruleta.*", "usuario_ruleta.usuruleta_id", "desc", $SkeepRows, $MaxRows, $json2, true);
                                $UsuariosRuletas = json_decode($UsuariosRuletas);

                                if ($UsuariosRuletas->count[0]->{".count"} == 0) {

                                    if ($csvUsuarios != '1' && $PARAPRIMERLOGINDIA !='1' && $ESPARAREGISTRO !='1') {

                                        $SkeepRows = 0;
                                        $MaxRows = 10;
                                        $rules = [];
                                        //array_push($rules, array("field" => "usuario_ruleta.estado", "data" => "'A','P','R','PR'", "op" => "in"));
                                        array_push($rules, array("field" => "usuario_ruleta.estado", "data" => "'A','P','R','PR','NR','PP'", "op" => "in"));
                                        array_push($rules, array("field" => "usuario_ruleta.usuario_id", "data" => $arg2, "op" => "eq"));
                                        array_push($rules, array("field" => "usuario_ruleta.ruleta_id", "data" => $ruletaId, "op" => "eq"));
                                        array_push($rules, array("field" => "usuario_ruleta.fecha_crea", "data" => date("Y-m-d 00:00:00"), "op" => "ge"));
                                        array_push($rules, array("field" => "usuario_ruleta.fecha_crea", "data" => date("Y-m-d 23:59:59"), "op" => "le"));
                                        array_push($rules, array("field" => "ruleta_interno.tipo", "data" => intval($arg4), "op" => "eq"));

                                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                                    $json2 = json_encode($filtro);

                                    $UsuariosRuletas = $UsuarioRuleta->getUsuarioRuletaCustom("usuario_ruleta.*", "usuario_ruleta.usuruleta_id", "desc", $SkeepRows, $MaxRows, $json2, true);
                                    $UsuariosRuletas = json_decode($UsuariosRuletas);

                                    if ($UsuariosRuletas->count[0]->{".count"} < $MAXDAILYSPINS) {

                                        $UsuarioRuleta = new UsuarioRuleta();

                                        $RuletaDetalleMySqlDAO = new RuletaDetalleMySqlDAO();
                                        $transaccion = $RuletaDetalleMySqlDAO->getTransaction();
                                        $UsuarioRuleta = new UsuarioRuleta();
                                        $UsuarioRuleta->ruletaId = $ruletaId;
                                        $UsuarioRuleta->usuarioId = $arg2;
                                        $UsuarioRuleta->valor = 0;
                                        $UsuarioRuleta->posicion = 0;
                                        $UsuarioRuleta->valorBase = $MINBETPRICE;
                                        $UsuarioRuleta->fechaCrea = 0;
                                        $UsuarioRuleta->usucreaId = 0;
                                        $UsuarioRuleta->fechaModif = 0;
                                        $UsuarioRuleta->usumodifId = 0;
                                        if ($arg3 < $MINBETPRICE) {

                                            $UsuarioRuleta->estado = "PR";

                                        } else {

                                            $UsuarioRuleta->estado = "A";
                                        }

                                        $fechaExpiracionTmp=$fechaExpiracion;
                                        if(!empty($expiracionDias)){
                                            $fechaExpiracionTmp = date("Y-m-d H:i:s", strtotime('+ '. $expiracionDias .' days'));
                                        }
                                        $UsuarioRuleta->fechaExpiracion = $fechaExpiracionTmp;


                                        $UsuarioRuleta->errorId = 0;
                                        $UsuarioRuleta->idExterno = 0;
                                        $UsuarioRuleta->mandante = 0;
                                        $UsuarioRuleta->version = 0;
                                        $UsuarioRuleta->apostado = floatval($arg3);
                                        $UsuarioRuleta->codigo = 0;
                                        $UsuarioRuleta->externoId = 0;
                                        $UsuarioRuleta->valorPremio = 0;
                                        $UsuarioRuleta->premio = "";
                                        $UsuarioRuletaMySqlDAO = new UsuarioRuletaMySqlDAO($transaccion);
                                        $UsuarioRuletaMySqlDAO->insert($UsuarioRuleta);
                                        $transaccion->commit();

                                        //Se valida si la ruleta está activa para enviar por web socket al front
                                        if ($UsuarioRuleta->estado == "A") {
                                            $websocketNotification = new WebsocketNotificacion();
                                            $websocketNotificacionMySqlDAO = new WebsocketNotificacionMySqlDAO();
                                            $transaccion = $websocketNotificacionMySqlDAO->getTransaction();

                                            $websocketNotification->setTipo('ruleta');
                                            $websocketNotification->setCanal('');

                                            $ruleta = $UsuarioRuleta->getRuletasUsuarioMandante($UsuarioMandante,$UsuarioRuleta->usuruletaId);
                                            $ruleta = !empty($ruleta) ? json_encode(['roulette' => $ruleta]) : '';

                                            $websocketNotification->setMensaje($ruleta);
                                            $websocketNotification->setEstado('P');
                                            $websocketNotification->setUsuarioId($UsuarioMandante->getUsuarioMandante());
                                            $websocketNotification->setValor($UsuarioRuleta->usuruletaId);
                                            $websocketNotification->setFecha_exp(date('Y-m-d H:i:s', strtotime('+6 hour')));

                                            $websocketNotificacionMySqlDAO->insert($websocketNotification);
                                            $transaccion->commit();
                                        }


                                        } else {
                                            $cumpleCondiciones = false;
                                        }
                                    }else{
                                        $cumpleCondiciones = false;

                                    }

                                } else {


                                    foreach ($UsuariosRuletas->data as $keys => $values) {
                                        $RuletaDetalleMySqlDAO = new RuletaDetalleMySqlDAO();
                                        $transaccion = $RuletaDetalleMySqlDAO->getTransaction();

                                        $usuruletaId = $values->{"usuario_ruleta.usuruleta_id"};

                                        $UsuarioRuleta = new UsuarioRuleta($usuruletaId);

                                        if ($UsuarioRuleta->valorBase == 0) {


                                            $UsuarioRuleta->setValorBase($MINBETPRICE);

                                            $UsuarioRuletaMySqlDAO = new UsuarioRuletaMySqlDAO($transaccion);
                                            $UsuarioRuletaMySqlDAO->update($UsuarioRuleta);

                                        }

                                        $BonoInterno = new BonoInterno();


                                        $sql = "UPDATE usuario_ruleta SET apostado = apostado + " . (floatval($arg3)) . " WHERE usuruleta_id =" . $usuruletaId;
                                        $BonoInterno->execQuery($transaccion, $sql);

                                        $transaccion->commit();

                                    }


                                }

                                if ($cumpleCondiciones) {

                                    $BonoInterno = new BonoInterno();
                                    $RuletaDetalleMySqlDAO = new RuletaDetalleMySqlDAO();
                                    $transaccion = $RuletaDetalleMySqlDAO->getTransaction();

                                    $conndicionDiasExpiracion = (!empty($expiracionDias)) ? " AND DATE_ADD(usuario_ruleta.fecha_crea, INTERVAL " . $expiracionDias . " DAY) >= NOW()" : '';

                                    $sql = "SELECT ur.usuruleta_id FROM usuario_ruleta ur WHERE ur.ruleta_id =" . $ruletaId . " AND ur.usuario_id=" . $arg2 . " AND ur.estado='PR'  AND ur.apostado >= ur.valor_base $conndicionDiasExpiracion";

                                    $usuruletaId = $this->execQuery($transaccion, $sql);

                                    $sql = "UPDATE usuario_ruleta SET estado = 'A'  WHERE ruleta_id =" . $ruletaId . " AND usuario_id=" . $arg2 . " AND estado='PR'  AND usuario_ruleta.apostado >= usuario_ruleta.valor_base $conndicionDiasExpiracion";

                                    $result = $BonoInterno->execQuery($transaccion, $sql);

                                    //Se valida si hay ruletas para enviar por web socket
                                    if ($result > 0) {
                                        if (!empty($usuruletaId)) {
                                            $usuruletaId = $usuruletaId[0]->{"ur.usuruleta_id"};

                                            $websocketNotification = new WebsocketNotificacion();
                                            $websocketNotificacionMySqlDAO = new WebsocketNotificacionMySqlDAO($transaccion);

                                            $websocketNotification->setTipo('ruleta');
                                            $websocketNotification->setCanal('');

                                            $ruleta = $UsuarioRuleta->getRuletasUsuarioMandante($UsuarioMandante,$usuruletaId);
                                            $ruleta = !empty($ruleta) ? json_encode(['roulette' => $ruleta]) : '';

                                            $websocketNotification->setMensaje($ruleta);
                                            $websocketNotification->setEstado('P');
                                            $websocketNotification->setUsuarioId($UsuarioMandante->getUsuarioMandante());
                                            $websocketNotification->setValor($usuruletaId);
                                            $websocketNotification->setFecha_exp(date('Y-m-d H:i:s', strtotime('+6 hour')));
                                            $websocketNotificacionMySqlDAO->insert($websocketNotification);
                                        }
                                        $transaccion->commit();
                                        $cumpleCondicionesGlobal = true;
                                    } else {
                                        continue;
                                    }
                                }
                            }

                        }
                    }

                }


                break;

        }


    }


    /**
     * Agrega una ruleta interna para un usuario específico basado en varios criterios y condiciones.
     *
     * @param int $tipoRuleta El tipo de ruleta.
     * @param int $usuarioId El ID del usuario.
     * @param int $mandante El mandante.
     * @param object $detalles Un objeto que contiene varios detalles necesarios para la verificación.
     * @param string $transaccion La transacción actual.
     * @return object Un objeto que contiene la respuesta con información sobre la ruleta elegida y el estado del bono.
     */
    public function agregarRuleta2($tipoRuleta, $usuarioId, $mandante, $detalles, $transaccion)
    {

        //Verificamos Detalles a tomar en cuenta al momento de verificar si puede ganar un ruleta
        $detalleDepositos = $detalles->Depositos;
        $detalleDepositoEfectivo = $detalles->DepositoEfectivo;
        $detalleDepositoMetodoPago = $detalles->MetodoPago;
        $detalleValorDeposito = $detalles->ValorDeposito;
        $detallePaisPV = $detalles->PaisPV;
        $detalleDepartamentoPV = $detalles->DepartamentoPV;
        $detalleCiudadPV = $detalles->CiudadPV;

        $CodePromo = $detalles->CodePromo;

        $detallePaisUSER = $detalles->PaisUSER;
        $detalleDepartamentoUSER = $detalles->DepartamentoUSER;
        $detalleCiudadUSER = $detalles->CiudadUSER;
        $detalleMonedaUSER = $detalles->MonedaUSER;

        $detallePuntoVenta = $detalles->PuntoVenta;

        $cumpleCondiciones = false;
        $ruletaElegido = 0;
        $ruletaTieneRollower = false;
        $rollowerRuleta = 0;
        $rollowerDeposito = 0;

        //Obtenemos todos los ruletas disponibles
        $sqlRuletas = "select a.ruleta_id ruleta_id,a.tipo,a.fecha_inicio,a.fecha_fin,now() test from ruleta_interno a where a.mandante=" . $mandante . " and now()  between (a.fecha_inicio) and (a.fecha_fin) and a.estado='A' ORDER BY a.orden DESC,a.fecha_crea ASC ";

        if ($CodePromo != "") {
            $sqlRuletas = "select a.ruleta_id,a.tipo,a.fecha_inicio,a.fecha_fin from ruleta_interno a INNER JOIN ruleta_detalle b ON (a.ruleta_id=b.ruleta_id AND b.tipo='CODEPROMO' AND b.valor='" . $CodePromo . "') where a.mandante=" . $mandante . " and now()  between (a.fecha_inicio) and (a.fecha_fin) and a.estado='A' ORDER BY a.orden DESC,a.fecha_crea ASC ";

        }

        $ruletasDisponibles = $this->execQuery($transaccion, $sqlRuletas);


        foreach ($ruletasDisponibles as $ruleta) {


            if (!$cumpleCondiciones) {

                //Obtenemos todos los detalles del ruleta
                $sqlDetalleRuleta = "select * from ruleta_detalle a where a.ruleta_id='" . $ruleta->{"a.ruleta_id"} . "' AND (moneda='' OR moneda='PEN') ";
                $ruletaDetalles = $this->execQuery($transaccion, $sqlDetalleRuleta);

                //Inicializamos variables
                $cumpleCondiciones = true;
                $condicionmetodoPago = false;
                $condicionmetodoPagocount = 0;

                $condicionPaisPV = false;
                $condicionPaisPVcount = 0;
                $condicionDepartamentoPV = false;
                $condicionDepartamentoPVcount = 0;
                $condicionCiudadPV = false;
                $condicionCiudadPVcount = 0;
                $condicionPuntoVenta = false;
                $condicionPuntoVentacount = 0;

                $condicionPaisUSER = false;
                $condicionPaisUSERcount = 0;
                $condicionDepartamentoUSER = false;
                $condicionDepartamentoUSERcount = 0;
                $condicionCiudadUSER = false;
                $condicionCiudadUSERcount = 0;

                $condicionTrigger = true;

                $puederepetirRuleta = false;
                $ganaRuletaId = 0;


                $maximopago = 0;
                $maximodeposito = 0;
                $minimodeposito = 0;
                $valorruleta = 0;
                $tipoproducto = 0;
                $tiporuleta = "";
                $ruletaTieneRollower = false;
                $tiposaldo = -1;

                if ($tipoRuleta != $ruleta->{"a.tipo"}) {
                    $cumpleCondiciones = false;

                }


                foreach ($ruletaDetalles as $ruletaDetalle) {

                    switch ($ruletaDetalle->{"a.tipo"}) {


                        case "TIPOPRODUCTO":
                            $tipoproducto = $ruletaDetalle->{"a.valor"};


                            break;

                        case "CANTDEPOSITOS":
                            if ($detalleDepositos != ($ruletaDetalle->{"a.valor"} - 1) && $ruleta->{"a.tipo"} == 2) {

                                $cumpleCondiciones = false;

                            }

                            break;

                        case "CONDEFECTIVO":
                            if ($detalleDepositoEfectivo) {
                                if (($ruletaDetalle->{"a.valor"} == "true")) {
                                    $condicionmetodoPago = true;
                                }
                            } else {
                                if (($ruletaDetalle->{"a.valor"} != "true")) {
                                    $condicionmetodoPago = true;
                                }
                            }
                            $condicionmetodoPagocount++;


                            break;


                        case "PORCENTAJE":
                            $tiporuleta = "PORCENTAJE";
                            $valorruleta = $ruletaDetalle->{"a.valor"};

                            break;


                        case "NUMERODEPOSITO":

                            break;

                        case "MAXJUGADORES":

                            break;


                        case "MAXPAGO":

                            if ($ruletaDetalle->{"a.moneda"} == $detalleMonedaUSER) {

                                $maximopago = $ruletaDetalle->{"a.valor"};

                            }
                            break;

                        case "MAXDEPOSITO":

                            $maximodeposito = $ruletaDetalle->{"a.valor"};
                            if ($ruletaDetalle->{"a.moneda"} == $detalleMonedaUSER) {

                                if ($detalleValorDeposito > $maximodeposito) {
                                    $cumpleCondiciones = false;
                                }
                            }

                            break;

                        case "MINDEPOSITO":

                            $minimodeposito = $ruletaDetalle->{"a.valor"};

                            if ($ruletaDetalle->{"a.moneda"} == $detalleMonedaUSER) {

                                if ($detalleValorDeposito < $minimodeposito) {
                                    $cumpleCondiciones = false;
                                }
                            }

                            break;

                        case "VALORBONO":
                            if ($ruletaDetalle->{"a.moneda"} == $detalleMonedaUSER) {

                                $valorruleta = $ruletaDetalle->{"a.valor"};
                                $tiporuleta = "VALOR";
                            }
                            break;

                        case "CONDPAYMENT":

                            if ($detalleDepositoMetodoPago == $ruletaDetalle->{"a.valor"}) {
                                $condicionmetodoPago = true;
                            }
                            $condicionmetodoPagocount++;

                            break;

                        case "CONDPAISPV":

                            $condicionPaisPVcount = $condicionPaisPVcount + 1;
                            if ($ruletaDetalle->{"a.valor"} == $detallePaisPV) {
                                $condicionPaisPV = true;
                            }

                            break;

                        case "CONDDEPARTAMENTOPV":

                            $condicionDepartamentoPVcount = $condicionDepartamentoPVcount + 1;
                            if ($ruletaDetalle->{"a.valor"} == $detalleDepartamentoPV) {
                                $condicionDepartamentoPV = true;
                            }

                            break;

                        case "CONDCIUDADPV":

                            $condicionCiudadPVcount = $condicionCiudadPVcount + 1;

                            if ($ruletaDetalle->{"a.valor"} == $detalleCiudadPV) {
                                $condicionCiudadPV = true;
                            }

                            break;

                        case "CONDPAISUSER":

                            $condicionPaisUSERcount = $condicionPaisUSERcount + 1;
                            if ($ruletaDetalle->{"a.valor"} == $detallePaisUSER) {
                                $condicionPaisUSER = true;
                            }

                            break;

                        case "CONDDEPARTAMENTOUSER":

                            $condicionDepartamentoUSERcount = $condicionDepartamentoUSERcount + 1;
                            if ($ruletaDetalle->{"a.valor"} == $detalleDepartamentoUSER) {
                                $condicionDepartamentoUSER = true;
                            }

                            break;

                        case "CONDCIUDADUSER":

                            $condicionCiudadUSERcount = $condicionCiudadUSERcount + 1;

                            if ($ruletaDetalle->{"a.valor"} == $detalleCiudadUSER) {
                                $condicionCiudadUSER = true;
                            }

                            break;

                        case "CONDPUNTOVENTA":

                            $condicionPuntoVentacount = $condicionPuntoVentacount + 1;

                            if ($ruletaDetalle->{"a.valor"} == $detallePuntoVenta) {
                                $condicionPuntoVenta = true;
                            }

                            break;

                        case "EXPDIA":

                            break;

                        case "EXPFECHA":

                            break;

                        case "WFACTORBONO":
                            $ruletaTieneRollower = true;

                            $rollowerRuleta = $ruletaDetalle->{"a.valor"};

                            break;

                        case "WFACTORDEPOSITO":
                            $ruletaTieneRollower = true;
                            $rollowerDeposito = $ruletaDetalle->{"a.valor"};

                            break;

                        case "VALORROLLOWER":
                            if ($ruletaDetalle->{"a.moneda"} == $detalleMonedaUSER) {

                                $ruletaTieneRollower = true;
                                $rollowerValor = $ruletaDetalle->{"a.valor"};
                            }
                            break;
                        case "REPETIRSORTEO":

                            if ($ruletaDetalle->{"a.valor"} == '1') {
                                $puederepetirRuleta = true;
                            }

                            break;

                        case "WINBONOID":
                            $ganaRuletaId = $ruletaDetalle->{"a.valor"};
                            $tiporuleta = "WINBONOID";
                            $valor_ruleta = 0;

                            break;

                        case "TIPOSALDO":
                            $tiposaldo = $ruletaDetalle->{"a.valor"};

                            break;

                        case "LIVEORPREMATCH":

                            break;

                        case "MINSELCOUNT":

                            break;

                        case "LIVEORPREMATCH":

                            break;

                        case "MINSELPRICE":

                            break;

                        case "MINBETPRICE":

                            break;

                        case "FROZEWALLET":

                            break;

                        case "SUPPRESSWITHDRAWAL":

                            break;

                        case "SCHEDULECOUNT":

                            break;

                        case "SCHEDULENAME":

                            break;

                        case "SCHEDULEPERIOD":

                            break;


                        case "SCHEDULEPERIODTYPE":

                            break;

                        case "CODEPROMO":

                            if ($CodePromo != "") {
                                if ($CodePromo != $ruletaDetalle->{"a.valor"}) {
                                    $condicionTrigger = false;

                                }
                            } else {

                                if ($tipoRuleta == 2) {
                                    $sqlDetalleRuletaPendiente = "SELECT a.usuruleta_id FROM usuario_ruleta a WHERE a.ruleta_id='" . $ruleta->{"a.ruleta_id"} . "' AND a.usuario_id='" . $usuarioId . "' AND a.estado='P'";
                                    $ruletaDetallesPendiente = $this->execQuery($transaccion, $sqlDetalleRuletaPendiente);

                                    if (oldCount($ruletaDetallesPendiente) > 0) {
                                        $condicionTriggerPosterior = $ruletaDetallesPendiente[0]->usuruleta_id;

                                    } else {
                                        $condicionTrigger = false;

                                    }

                                } else {
                                    $condicionTrigger = false;

                                }

                            }

                            break;

                        default:

                            //   if (stristr($ruletadetalle->{'ruleta_detalle.tipo'}, 'CONDGAME')) {
                            //
                            // }
                            //
                            //   if (stristr($ruletadetalle->{'ruleta_detalle.tipo'}, 'ITAINMENT')) {
                            //
                            //
                            //
                            //   }
                            break;
                    }
                }


                if (!$condicionTrigger) {
                    $cumpleCondiciones = false;
                }

                if ($CodePromo == "") {

                    if ($condicionPaisPVcount > 0) {
                        if (!$condicionPaisPV) {
                            $cumpleCondiciones = false;
                        }

                    }

                    if ($condicionDepartamentoPVcount > 0) {
                        if (!$condicionDepartamentoPV) {
                            $cumpleCondiciones = false;
                        }

                    }

                    if ($condicionCiudadPVcount > 0) {
                        if (!$condicionCiudadPV) {
                            $cumpleCondiciones = false;
                        }

                    }
                }

                if ($condicionPaisUSERcount > 0) {
                    if (!$condicionPaisUSER) {
                        $cumpleCondiciones = false;
                    }

                }

                if ($condicionDepartamentoUSERcount > 0) {
                    if (!$condicionDepartamentoUSER) {
                        $cumpleCondiciones = false;
                    }

                }

                if ($condicionCiudadUSERcount > 0) {
                    if (!$condicionCiudadUSER) {
                        $cumpleCondiciones = false;
                    }

                }
                if ($CodePromo == "") {

                    if ($condicionPuntoVentacount > 0) {
                        if (!$condicionPuntoVenta) {
                            $cumpleCondiciones = false;
                        }
                    }

                    if ($condicionmetodoPagocount > 0) {
                        if (!$condicionmetodoPago) {
                            $cumpleCondiciones = false;
                        }

                    }
                }

                if ($cumpleCondiciones) {


                    if ($puederepetirRuleta) {
                        $ruletaElegido = $ruleta->{"a.ruleta_id"};

                    } else {
                        $sqlRepiteRuleta = "select * from usuario_ruleta a where a.ruleta_id='" . $ruleta->{"a.ruleta_id"} . "' AND a.usuario_id = '" . $usuarioId . "'";
                        $repiteRuleta = $this->execQuery($transaccion, $sqlRepiteRuleta);

                        if ((!$puederepetirRuleta && oldCount($repiteRuleta) == 0)) {
                            $ruletaElegido = $ruleta->{"a.ruleta_id"};
                        } else {
                            $cumpleCondiciones = false;
                        }

                    }


                }

                if ($cumpleCondiciones) {
                    if ($transaccion != '') {
                        if ($tiporuleta == "PORCENTAJE") {

                            $valor_ruleta = ($detalleValorDeposito) * ($valorruleta) / 100;

                            if ($valor_ruleta > $maximopago) {
                                $valor_ruleta = $maximopago;
                            }

                        } elseif ($tiporuleta == "VALOR") {

                            $valor_ruleta = $valorruleta;

                        }

                        if ($condicionTriggerPosterior > 0) {
                            $strsql = "UPDATE ruleta_interno SET ruleta_interno.cupo_actual =ruleta_interno.cupo_actual + " . $valor_ruleta . " WHERE ruleta_interno.cupo_maximo >= (ruleta_interno.cupo_actual + " . $valor_ruleta . ") AND ruleta_interno.ruleta_id ='" . $ruletaElegido . "'";

                        } else {
                            $strsql = "UPDATE ruleta_interno SET ruleta_interno.cupo_actual =ruleta_interno.cupo_actual + " . $valor_ruleta . ",ruleta_interno.cantidad_ruletas=ruleta_interno.cantidad_ruletas+1 WHERE (ruleta_interno.cupo_maximo >= (ruleta_interno.cupo_actual + " . $valor_ruleta . ") OR ruleta_interno.cupo_maximo = 0) AND ((ruleta_interno.maximo_ruletas >= (ruleta_interno.cantidad_ruletas+1)) OR ruleta_interno.maximo_ruletas=0) AND ruleta_interno.ruleta_id ='" . $ruletaElegido . "'";

                        }
                        if ($usuarioId == 886) {
                            //print_r("TEST" . $cumpleCondiciones);
                            //print_r($strsql);
                        }

                        $resp = $this->execUpdate($transaccion, $strsql);
                        if ($usuarioId == 886) {
                            //print_r($resp);
                        }
                        if ($resp > 0) {
                            $cumpleCondiciones = true;
                        } else {

                            $cumpleCondiciones = false;
                            $ruletaElegido = 0;

                            if ($condicionTriggerPosterior > 0) {
                                $strsql = "UPDATE usuario_ruleta SET usuario_ruleta.estado = 'E',usuario_ruleta.error_id='1' WHERE usuario_ruleta.usuruleta_id ='" . $condicionTriggerPosterior . "'";
                                $resp = $this->execUpdate($transaccion, $strsql);

                            }

                        }

                    }

                }

            }

        }

        $respuesta = array();
        $respuesta["Ruleta"] = 0;
        $respuesta["WinBonus"] = false;


        if ($ruletaElegido != 0 && $tiporuleta != "") {

            if ($tipoRuleta == 2) {
                if ($tiporuleta == "PORCENTAJE") {

                    $valor_ruleta = ($detalleValorDeposito) * ($valorruleta) / 100;


                    if ($valor_ruleta > $maximopago) {
                        $valor_ruleta = $maximopago;
                    }

                } elseif ($tiporuleta == "VALOR") {

                    $valor_ruleta = $valorruleta;

                }


                $valorBase = $detalleValorDeposito;

                $strSql = array();
                $contSql = 0;
                $estadoRuleta = 'A';
                $rollowerRequerido = 0;
                $SumoSaldo = false;

                if (!$ruletaTieneRollower) {

                    if ($CodePromo != "" && $tiporuleta == 2) {
                        $estadoRuleta = 'P';

                    } else {
                        if ($ganaRuletaId == 0) {
                            $tipoRuletaS = 'D';
                            switch ($tiposaldo) {
                                case 0:


                                    $contSql = $contSql + 1;
                                    $strSql[$contSql] = "update registro,ruleta_interno set registro.creditos_base_ant=registro.creditos_base,registro.creditos_base=registro.creditos_base+" . $valor_ruleta . " where registro.mandante=" . $mandante . " and registro.usuario_id=" . $usuarioId . " AND ruleta_id ='" . $ruletaElegido . "'";
                                    $estadoRuleta = 'R';
                                    $SumoSaldo = true;

                                    break;

                                case 1:
                                    $contSql = $contSql + 1;
                                    $strSql[$contSql] = "update registro,ruleta_interno set registro.creditos_ant=registro.creditos,registro.creditos=registro.creditos+" . $valor_ruleta . " where registro.mandante=" . $mandante . " and registro.usuario_id=" . $usuarioId . " AND ruleta_id ='" . $ruletaElegido . "'";
                                    $estadoRuleta = 'R';
                                    $SumoSaldo = true;

                                    break;

                                case 2:
                                    $contSql = $contSql + 1;
                                    $strSql[$contSql] = "update registro,ruleta_interno set registro.saldo_especial=registro.saldo_especial+" . $valor_ruleta . " where registro.mandante=" . $mandante . " and registro.usuario_id=" . $usuarioId . " AND ruleta_id ='" . $ruletaElegido . "'";
                                    $estadoRuleta = 'R';
                                    $SumoSaldo = true;

                                    break;

                            }

                        } else {

                            $resp = $this->agregarRuletaFree($ganaRuletaId, $usuarioId, $mandante, $detalles, '', '', $transaccion);

                            if ($transaccion == "") {
                                foreach ($resp->queries as $val) {
                                    $contSql = $contSql + 1;
                                    $strSql[$contSql] = $val;
                                }
                            }

                            $estadoRuleta = 'R';

                        }
                    }


                } else {

                    if ($CodePromo != "" && $tiporuleta == 2) {
                        $estadoRuleta = 'P';

                    } else {
                        //$rollowerDeposito && $ganaRuletaId == 0
                        if ($rollowerDeposito) {
                            $rollowerRequerido = $rollowerRequerido + ($rollowerDeposito * $detalleValorDeposito);
                        }

                        if ($rollowerRuleta) {
                            $rollowerRequerido = $rollowerRequerido + ($rollowerRuleta * $valor_ruleta);

                        }
                        if ($rollowerValor) {
                            $rollowerRequerido = $rollowerRequerido + ($rollowerValor);

                        }

                        $contSql = $contSql + 1;
                        $strSql[$contSql] = "update registro,ruleta_interno set registro.creditos_ruleta=registro.creditos_ruleta+" . $valor_ruleta . " where registro.mandante=" . $mandante . " and registro.usuario_id=" . $usuarioId . "  AND ruleta_id ='" . $ruletaElegido . "'";
                    }


                }

                if ($condicionTriggerPosterior > 0) {


                    $contSql = $contSql + 1;
                    $strSql[$contSql] = "UPDATE usuario_ruleta,ruleta_interno SET usuario_ruleta.valor='" . $valor_ruleta . "',usuario_ruleta.valor_ruleta='" . $valorruleta . "',usuario_ruleta.valor_base='" . $valorBase . "',usuario_ruleta.estado='" . $estadoRuleta . "',usuario_ruleta.error_id='0',usuario_ruleta.externo_id='0',usuario_ruleta.mandante='" . $mandante . "',usuario_ruleta.rollower_requerido='" . $rollowerRequerido . "' WHERE usuario_ruleta.usuruleta_id = '" . $condicionTriggerPosterior . "' AND usuario_ruleta.ruleta_id ='" . $ruletaElegido . "' AND ruleta_interno.ruleta_id ='" . $ruletaElegido . "'  AND ruleta_interno.ruleta_id ='" . $ruletaElegido . "'";


                    $contSql = $contSql + 1;
                    $strSql[$contSql] = "UPDATE ruleta_interno SET ruleta_interno.cupo_actual =ruleta_interno.cupo_actual + " . $valor_ruleta . " WHERE ruleta_interno.ruleta_id ='" . $ruletaElegido . "'";

                } else {
                    $contSql = $contSql + 1;
                    $strSql[$contSql] = "insert into usuario_ruleta (usuario_id,ruleta_id,valor,valor_ruleta,valor_base,fecha_crea,estado,error_id,externo_id,mandante,rollower_requerido,usucrea_id,usumodif_id) SELECT " . $usuarioId . "," . $ruletaElegido . "," . $valor_ruleta . "," . $valorruleta . " ," . $valorBase . " ,'" . date('Y-m-d H:i:s') . "','" . $estadoRuleta . "','0',0," . $mandante . ", $rollowerRequerido,0,0 FROM ruleta_interno WHERE  ruleta_id ='" . $ruletaElegido . "'";


                    $contSql = $contSql + 1;
                    $strSql[$contSql] = "UPDATE ruleta_interno SET ruleta_interno.cupo_actual =ruleta_interno.cupo_actual + " . $valor_ruleta . ",ruleta_interno.cantidad_ruletas=ruleta_interno.cantidad_ruletas+1 WHERE ruleta_interno.ruleta_id ='" . $ruletaElegido . "'";
                }

                if ($transaccion != "") {

                    foreach ($strSql as $val) {

                        $resp = $this->execUpdate($transaccion, $val);

                        if ($SumoSaldo && (strpos($val, 'insert into usuario_ruleta') !== false)) {
                            $last_insert_id = $resp;
                            $tiboderuleta = 'F';

                            if ($tipoRuleta == 2) {
                                $tiboderuleta = 'D';

                            }


                            if ($last_insert_id != "" && is_numeric($last_insert_id)) {
                                $sql2 = "insert into ruleta_log (usuario_id,tipo,valor,estado,id_externo,mandante,transaccion_id,tiporuleta_id) values (" . $usuarioId . ",'" . $tiboderuleta . "','" . $valor_ruleta . "','L','" . $last_insert_id . "','0',0,4)";
                                $resp2 = $this->execUpdate($transaccion, $sql2);
                            }

                        }


                    }

                }


                // $contSql = $contSql + 1;
                // $strSql[$contSql] = "insert into ruleta_log (usuario_id,tipo,valor,estado,id_externo,mandante,transaccion_id,tiporuleta_id) values (" . $usuarioId . ",'" . $tipoRuletaS . "','" . $valor_ruleta  . "','L','0'," . $mandante . ",0,4)";


                $respuesta["WinBonus"] = true;
                $respuesta["SumoSaldo"] = $SumoSaldo;
                $respuesta["Ruleta"] = $ruletaElegido;
                $respuesta["Valor"] = $valor_ruleta;
                $respuesta["queries"] = $strSql;
            }

            if ($tipoRuleta == 3) {
                $resp = $this->agregarRuletaFree($ruletaElegido, $usuarioId, $mandante, $detalles, '', '', $transaccion);

                if ($transaccion == '') {
                    foreach ($resp->queries as $val) {
                        $contSql = $contSql + 1;
                        $strSql[$contSql] = $val;
                    }
                }
            }

            if ($tipoRuleta == 6) {

                $resp = $this->agregarRuletaFree($ruletaElegido, $usuarioId, $mandante, $detalles, '', '', $transaccion);

                if ($transaccion == '') {
                    foreach ($resp->queries as $val) {
                        $contSql = $contSql + 1;
                        $strSql[$contSql] = $val;
                    }
                }

            }
        }

        return json_decode(json_encode($respuesta));

    }

    public function agregarPremioRuleta($usuruletaId, $UsuarioMandante, $accion = null)
    {


        if ($usuruletaId != '') {

            $ClientId = $UsuarioMandante->getUsuarioMandante();

            $SkeepRows = "";
            $OrderedItem = "";
            $MaxRows = "";


            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }

            if ($OrderedItem == "") {
                $OrderedItem = 1;
            }

            if ($MaxRows == "") {
                $MaxRows = 10;
            }

            $rules = [];

            if ($usuruletaId != "" && $usuruletaId != null) {
                array_push($rules, array("field" => "usuario_ruleta.estado", "data" => "P", "op" => "eq"));
                array_push($rules, array("field" => "usuario_ruleta.usuruleta_id", "data" => $usuruletaId, "op" => "eq"));
                array_push($rules, array("field" => "usuario_ruleta.usuario_id", "data" => $UsuarioMandante->usumandanteId, "op" => "eq"));
                array_push($rules, array("field" => "ruleta_detalle.tipo", "data" => "'RANKAWARDMAT','RANKAWARD','BONO','RANKAWARDFREESPIN'", "op" => "in"));
            }
            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json2 = json_encode($filtro);


            $RuletaDetalle = new RuletaDetalle();
            $Ruletas = $RuletaDetalle->getRuletaDetallesCustom3("ruleta_detalle.*,ruleta_interno.*,usuario_ruleta.*", "ruleta_detalle.ruleta_id", "desc", $SkeepRows, $MaxRows, $json2, true);
            $Ruletas = json_decode($Ruletas);


            $roulette = array();

            if (oldCount($Ruletas->data) == 0) {
                throw new Exception("Inusual Detected0", "100001");

            }

            foreach ($Ruletas->data as $key => $value) {

                $Id = $value->{"usuario_ruleta.ruleta_id"};
                $Name = $value->{"ruleta_interno.descripcion"};
                $prizes = new stdClass();

                // Se valida que el usuario no tenga contingencia de abusador de bonos activa
                $UsuarioConfiguracion = new UsuarioConfiguracion();
                $UsuarioConfiguracion = $UsuarioConfiguracion->getUserBondABuser($UsuarioMandante->usuarioMandante);
                if (!empty($UsuarioConfiguracion->usuconfigId)) {
                    $BonoInterno = new BonoInterno();
                    $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
                    $transaccion = $BonoDetalleMySqlDAO->getTransaction();
                    //Registro de log al intentar asignar premio de ruleta a un usuario con contingencia activa
                    $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,descripcion)
                    VALUES ('$UsuarioMandante->usuarioMandante', 'RULETA', ' " . $value->{"usuario_ruleta.ruleta_id"} . " ', 'Contingencia activa: Abusador de bonos')";
                    $BonoInterno->execQuery($transaccion, $sqlLog);
                    $transaccion->commit();
                }

                if (isset($roulette[$Id])) {
                    $id = $value->{"ruleta_detalle.ruletadetalle_id"};
                    $image = $value->{"ruleta_detalle.valor2"};
                    $prizeWinImageURL = $value->{"ruleta_detalle.valor3"};
                    $porcentaje = $value->{"ruleta_detalle.porcentaje"};

                    if ($value->{"ruleta_detalle.tipo"} == "RANKAWARDMAT") {
                        $description = $value->{"ruleta_detalle.descripcion"};
                    } elseif ($value->{"ruleta_detalle.tipo"} == "BONO") {
                        $description = $value->{"ruleta_detalle.descripcion"};
                        $BonoId = $value->{"ruleta_detalle.valor"};
                    } elseif ($value->{"ruleta_detalle.tipo"} == "RANKAWARDFREESPIN") {
                        $description = $value->{"ruleta_detalle.descripcion"};
                    }

                    if ($value->{"ruleta_detalle.tipo"} == "RANKAWARD") {
                        if ($value->{"ruleta_detalle.descripcion"} == 'Saldo Creditos') {
                            $tipoSaldo = '0'; //Tipo Saldo Creditos
                        } elseif ($value->{"ruleta_detalle.descripcion"} == 'Saldo Premios') {
                            $tipoSaldo = '1'; //Tipo Saldo Premios
                        }
                        $description = $value->{"ruleta_detalle.valor"} . " " . $value->{"ruleta_detalle.moneda"} . " " . $value->{"ruleta_detalle.descripcion"};
                        $AmountWin = $value->{"ruleta_detalle.valor"};
                        $prizes->AmountWin = $AmountWin;

                    }
                    if (!isset($categoriasData[$Id]->prizes[$id])) {
                        $prizes->id = $id;
                        $prizes->image = $image;
                        $prizes->prizeWinImageURL = $prizeWinImageURL;
                        $prizes->text = $description;
                        if ($tipoSaldo != null && $tipoSaldo != "") {
                            $prizes->tipoSaldo = $tipoSaldo;
                        } else {
                            $prizes->bonoId = $BonoId;
                        }
                        $prizes->percentage = $porcentaje;
                    }
                } else {
                    $roulette[$Id] = new stdClass();
                    $roulette[$Id]->Id = $Id;
                    $roulette[$Id]->Name = $Name;
                    $roulette[$Id]->prizes = array();
                    $id = $value->{"ruleta_detalle.ruletadetalle_id"};
                    $img = $value->{"ruleta_detalle.valor2"};
                    $prizeWinImageURL = $value->{"ruleta_detalle.valor3"};
                    $porcentaje = $value->{"ruleta_detalle.porcentaje"};
                    if ($value->{"ruleta_detalle.tipo"} == "RANKAWARDMAT") {
                        $description = $value->{"ruleta_detalle.descripcion"};
                    } elseif ($value->{"ruleta_detalle.tipo"} == "BONO") {
                        $description = $value->{"ruleta_detalle.descripcion"};
                        $BonoId = $value->{"ruleta_detalle.valor"};
                    } elseif ($value->{"ruleta_detalle.tipo"} == "RANKAWARDFREESPIN") {
                        $description = $value->{"ruleta_detalle.descripcion"};
                    }

                    if ($value->{"ruleta_detalle.tipo"} == "RANKAWARD") {
                        if ($value->{"ruleta_detalle.descripcion"} == 'Saldo Creditos') {
                            $tipoSaldo = '0'; //Tipo Saldo Creditos
                        } elseif ($value->{"ruleta_detalle.descripcion"} == 'Saldo Premios') {
                            $tipoSaldo = '1'; //Tipo Saldo Premios
                        }
                        $description = $value->{"ruleta_detalle.valor"} . " " . $value->{"ruleta_detalle.moneda"} . " " . $value->{"ruleta_detalle.descripcion"};
                        $AmountWin = $value->{"ruleta_detalle.valor"};

                    }


                    $prizes = new stdClass();
                    $prizes->id = $id;
                    $prizes->image = $img;
                    $prizes->prizeWinImageURL = $prizeWinImageURL;
                    $prizes->text = $description;
                    $prizes->AmountWin = $AmountWin;

                    if ($tipoSaldo != null && $tipoSaldo != "") {
                        $prizes->tipoSaldo = $tipoSaldo;
                    } else {
                        $prizes->bonoId = $BonoId;
                    }

                    $prizes->percentage = $porcentaje;

                }

                array_push($roulette[$Id]->prizes, $prizes);

            }


            $rouletteDatanew = array();
            foreach ($roulette as $c) {
                $cnew = array();
                $cnew['Id'] = $c->Id;
                $cnew['Name'] = $c->Name;
                $cnew['prizes'] = array();
                foreach ($c->prizes as $subc) {
                    array_push($cnew['prizes'], $subc);
                }
                array_push($rouletteDatanew, $cnew);

            }

            $prizes = $rouletteDatanew[0]["prizes"];

            $weightedValues = array();
            foreach ($prizes as $key) {
                array_push($weightedValues, $key->{"percentage"});
            }


            function getRandomWeightedElement(array $weightedValues)
            {
                $rand = mt_rand(1, (int)array_sum($weightedValues));

                foreach ($weightedValues as $key => $value) {
                    $rand -= $value;
                    if ($rand <= 0) {
                        return $key;
                    }
                }
            }


            $winner = getRandomWeightedElement($weightedValues);
            $winner = $prizes[$winner];

            $jsonWinner = json_encode($winner);

            $UsuarioRuleta = new UsuarioRuleta($usuruletaId, $UsuarioMandante->getUsumandanteId());
            $RuletaInterno = new RuletaInterno($UsuarioRuleta->ruletaId);
            $tipoRuleta = $RuletaInterno->tipo;

            $UsuarioRuletaMySqlDAO = new UsuarioRuletaMySqlDAO();

            if ($winner->text != "Giro extra") {
                $UsuarioRuleta->setEstado("R");
                $UsuarioRuleta->setPremio($jsonWinner);
                $result = $UsuarioRuletaMySqlDAO->update($UsuarioRuleta, " AND estado = 'P'");
                if ($result > 0) {
                    $UsuarioRuletaMySqlDAO->getTransaction()->commit();
                } else {
                    return;
                }
            }

            //cambiar estado de usuario_ruleta de 'P' a R'.
            //Guardar el premio en usuario_ruleta.premio.
            //Validar tipo de bono  hacer el respectivo llamado a las funciones para asignación del bono

            if ($winner->bonoId != "" && $winner->bonoId != null) {

                $BonoInterno = new BonoInterno($winner->bonoId);

                if ($BonoInterno->tipo == 2) {

                    $UsuarioBono = new UsuarioBono();
                    $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
                    $transaccion = $BonoDetalleMySqlDAO->getTransaction();
                    $UsuarioBono->setUsuarioId(0);
                    $UsuarioBono->setBonoId($BonoInterno->bonoId);
                    $UsuarioBono->setValor(0);
                    $UsuarioBono->setValorBono(0);
                    $UsuarioBono->setValorBase(0);
                    $UsuarioBono->setEstado("L");
                    $UsuarioBono->setErrorId(0);
                    $UsuarioBono->setIdExterno(0);
                    $UsuarioBono->setMandante($UsuarioMandante->mandante);
                    $UsuarioBono->setUsucreaId(0);
                    $UsuarioBono->setUsumodifId(0);
                    $UsuarioBono->setApostado(0);
                    $UsuarioBono->setRollowerRequerido(0);
                    $UsuarioBono->setCodigo("");
                    $UsuarioBono->setVersion(0);
                    $UsuarioBono->setExternoId(0);

                    $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($transaccion);

                    $inse = $UsuarioBonoMysqlDAO->insert($UsuarioBono);
                    $transaccion->commit();
                }
                if ($BonoInterno->tipo == 3) {

                    $UsuarioBono = new UsuarioBono();
                    $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
                    $transaccion = $BonoDetalleMySqlDAO->getTransaction();
                    $UsuarioBono->setUsuarioId(0);
                    $UsuarioBono->setBonoId($BonoInterno->bonoId);
                    $UsuarioBono->setValor(0);
                    $UsuarioBono->setValorBono(0);
                    $UsuarioBono->setValorBase(0);
                    $UsuarioBono->setEstado("L");
                    $UsuarioBono->setErrorId(0);
                    $UsuarioBono->setIdExterno(0);
                    $UsuarioBono->setMandante($UsuarioMandante->mandante);
                    $UsuarioBono->setUsucreaId(0);
                    $UsuarioBono->setUsumodifId(0);
                    $UsuarioBono->setApostado(0);
                    $UsuarioBono->setRollowerRequerido(0);
                    $UsuarioBono->setCodigo("");
                    $UsuarioBono->setVersion(0);
                    $UsuarioBono->setExternoId(0);

                    $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($transaccion);

                    $inse = $UsuarioBonoMysqlDAO->insert($UsuarioBono);
                    $transaccion->commit();
                }

                if ($BonoInterno->tipo == 5) {

                    $UsuarioBono = new UsuarioBono();
                    $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
                    $transaccion = $BonoDetalleMySqlDAO->getTransaction();
                    $UsuarioBono->setUsuarioId(0);
                    $UsuarioBono->setBonoId($BonoInterno->bonoId);
                    $UsuarioBono->setValor(0);
                    $UsuarioBono->setValorBono(0);
                    $UsuarioBono->setValorBase(0);
                    $UsuarioBono->setEstado("L");
                    $UsuarioBono->setErrorId(0);
                    $UsuarioBono->setIdExterno(0);
                    $UsuarioBono->setMandante($UsuarioMandante->mandante);
                    $UsuarioBono->setUsucreaId(0);
                    $UsuarioBono->setUsumodifId(0);
                    $UsuarioBono->setApostado(0);
                    $UsuarioBono->setRollowerRequerido(0);
                    $UsuarioBono->setCodigo("");
                    $UsuarioBono->setVersion(0);
                    $UsuarioBono->setExternoId(0);

                    $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($transaccion);

                    $inse = $UsuarioBonoMysqlDAO->insert($UsuarioBono);
                    $transaccion->commit();
                }

                if ($BonoInterno->tipo == 6) {
                    $UsuarioBono = new UsuarioBono();
                    $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
                    $transaccion = $BonoDetalleMySqlDAO->getTransaction();
                    $UsuarioBono->setUsuarioId(0);
                    $UsuarioBono->setBonoId($BonoInterno->bonoId);
                    $UsuarioBono->setValor(0);
                    $UsuarioBono->setValorBono(0);
                    $UsuarioBono->setValorBase(0);
                    $UsuarioBono->setEstado("L");
                    $UsuarioBono->setErrorId(0);
                    $UsuarioBono->setIdExterno(0);
                    $UsuarioBono->setMandante($UsuarioMandante->mandante);
                    $UsuarioBono->setUsucreaId(0);
                    $UsuarioBono->setUsumodifId(0);
                    $UsuarioBono->setApostado(0);
                    $UsuarioBono->setRollowerRequerido(0);
                    $UsuarioBono->setCodigo("");
                    $UsuarioBono->setVersion(0);
                    $UsuarioBono->setExternoId(0);

                    $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($transaccion);

                    $inse = $UsuarioBonoMysqlDAO->insert($UsuarioBono);
                    $transaccion->commit();
                }
                $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
                $transaccion = $BonoDetalleMySqlDAO->getTransaction();
                $transaccion->getConnection()->beginTransaction();
                $usuarioSql = "select ciudad.ciudad_id,ciudad.depto_id,pais.pais_id,usuario.mandante, usuario.moneda FROM registro
    INNER JOIN usuario ON (usuario.usuario_id = registro.usuario_id)
  INNER JOIN pais ON (pais.pais_id = usuario.pais_id)
    LEFT OUTER JOIN ciudad ON (ciudad.ciudad_id = registro.ciudad_id)
  LEFT OUTER JOIN departamento ON (ciudad.depto_id = departamento.depto_id)
   WHERE registro.usuario_id='" . $UsuarioMandante->getUsuarioMandante() . "'";

                $Usuario = $BonoInterno->execQuery($transaccion, $usuarioSql);


                $dataUsuario = $Usuario;
                $detalles = array(
                    "PaisUSER" => $dataUsuario[0]->{'pais.pais_id'},
                    "DepartamentoUSER" => $dataUsuario[0]->{'ciudad.depto_id'},
                    "CiudadUSER" => $dataUsuario[0]->{'ciudad.ciudad_id'},
                    "MonedaUSER" => $dataUsuario[0]->{'usuario.moneda'}

                );
                $detalles = json_decode(json_encode($detalles));

                $respuesta = $BonoInterno->agregarBonoFree($BonoInterno->bonoId, $UsuarioMandante->getUsuarioMandante(), $dataUsuario[0]->{'usuario.mandante'}, $detalles, true, "", $transaccion);

                $transaccion->commit();

            } elseif ($winner->tipoSaldo != "" && $winner->tipoSaldo != null) {


                $Type = $tipoRuleta;

                if ($Type == '2') {
                    $Type = "RC";
                }

                if ($Type == '1') {
                    $Type = "CD";
                }


                if ($Type == '4') {
                    $Type = "RV";
                }

                if ($Type == '3') {
                    $Type = "RL";
                }


                if ($ClientId != "" && $ClientId != "0") {


                    $Usuario = new Usuario($ClientId);

                    $winner->AmountWin = !empty($winner->AmountWin) ? $winner->AmountWin : 0;

                    $BonoLog = new BonoLog();
                    $BonoLog->setUsuarioId($Usuario->usuarioId);
                    $BonoLog->setTipo($Type);
                    $BonoLog->setValor($winner->AmountWin);
                    $BonoLog->setFechaCrea(date('Y-m-d H:i:s'));
                    $BonoLog->setEstado('L');
                    $BonoLog->setErrorId(0);
                    $BonoLog->setIdExterno($UsuarioRuleta->usuruletaId);
                    $BonoLog->setMandante($Usuario->mandante);
                    $BonoLog->setFechaCierre('');
                    $BonoLog->setTransaccionId('');
                    $BonoLog->setTipobonoId(4);
                    $BonoLog->setTiposaldoId($winner->tipoSaldo);


                    $BonoLogMySqlDAO = new BonoLogMySqlDAO();

                    $Transaction = $BonoLogMySqlDAO->getTransaction();

                    $bonologId = $BonoLogMySqlDAO->insert($BonoLog);

                    if ($tipoSaldo == 0) {

                        $Usuario->credit($winner->AmountWin, $Transaction); //Creditos

                    } elseif ($tipoSaldo == 1) {
                        $Usuario->creditWin($winner->AmountWin, $Transaction); //Retiros

                    }

                    $UsuarioHistorial = new UsuarioHistorial();
                    $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                    $UsuarioHistorial->setDescripcion('');
                    $UsuarioHistorial->setMovimiento('E');
                    $UsuarioHistorial->setUsucreaId(0);
                    $UsuarioHistorial->setUsumodifId(0);
                    $UsuarioHistorial->setTipo(50);
                    $UsuarioHistorial->setValor($winner->AmountWin);
                    $UsuarioHistorial->setExternoId($bonologId);

                    $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                    $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);


                    $Transaction->commit();


                }

            }

            if (($accion == "Giro Extra" && $winner->text != "Giro extra") || $winner->text == "Giro extra") {

                $transaccion = $UsuarioRuletaMySqlDAO->getTransaction();
                $auditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO($transaccion);
                $auditoriaGeneral = (object)[
                    'usuarioId' => $UsuarioMandante->usuarioMandante,
                    'usuariosolicitaId' => $UsuarioMandante->usuarioMandante,
                    'tipo' => 'ASIGNACION_GIRO_EXTRA_RULETA',
                    'estado' => 'A',
                    'valorAntes' => 'No usado',
                    'valorDespues' => 'Usado',
                    'usucreaId' => $UsuarioMandante->usuarioMandante,
                    'observacion' => json_encode($winner),
                    'campo' => null,
                    'usuarioaprobarId' => null,
                    'usuariosolicitaIp' => null,
                    'usuarioIp' => null,
                    'usuarioaprobarIp' => null,
                    'usumodifId' => null,
                    'dispositivo' => '',
                    'soperativo' => '',
                    'sversion' => '',
                    'imagen' => '',
                    'data' => ''
                ];

                $auditoriaGeneralMySqlDAO->insert($auditoriaGeneral);
                $transaccion->commit();
            }

            $response = array();

            $response["winRuleta"] = true;

            $response["data"] = array(
                "winner" => $winner
            );


        } else {
            $response = array();

            $response["winRuleta"] = false;

        }


        return $response;


    }

    /**
     * Agregar un ruleta en la base de datos
     *
     *
     * @param String tipoRuleta tipoRuleta
     * @param String usuarioId id del usuario
     * @param String mandante mandante
     * @param String detalles detalles
     * @param String ejecutarSQL ejecutarSQL
     * @param String codebonus codebonus
     * @param Objeto Transaction transacción
     *
     * @return boolean $ resultado de la insercción
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */

    public function agregarRuletaFree($ruletaid, $usuarioId, $mandante, $detalles, $ejecutarSQL, $codebonus, $transaccion)
    {

        //Verificamos Detalles a tomar en cuenta al momento de verificar si puede ganar un ruleta
        $detalleDepositos = $detalles->Depositos;
        $detalleDepositoEfectivo = $detalles->DepositoEfectivo;
        $detalleDepositoMetodoPago = $detalles->MetodoPago;
        $detalleValorDeposito = $detalles->ValorDeposito;
        $detallePaisPV = $detalles->PaisPV;
        $detalleDepartamentoPV = $detalles->DepartamentoPV;
        $detalleCiudadPV = $detalles->CiudadPV;

        $detallePaisUSER = $detalles->PaisUSER;
        $detalleDepartamentoUSER = $detalles->DepartamentoUSER;
        $detalleCiudadUSER = $detalles->CiudadUSER;
        $detalleMonedaUSER = $detalles->MonedaUSER;

        $detallePuntoVenta = $detalles->PuntoVenta;

        $cumpleCondiciones = false;
        $ruletaElegido = 0;
        $ruletaTieneRollower = false;
        $rollowerRuleta = 0;
        $rollowerDeposito = 0;

        //Obtenemos todos los ruletas disponibles
        $sqlRuletas = "select a.ruleta_id,a.tipo from ruleta_interno a where a.mandante=" . $mandante . " and  a.estado='A' and a.ruleta_id='" . $ruletaid . "'";

        $ruletasDisponibles = $this->execQuery($transaccion, $sqlRuletas);

        foreach ($ruletasDisponibles as $ruleta) {

            if (!$cumpleCondiciones) {

                //Obtenemos todos los detalles del ruleta
                $sqlDetalleRuleta = "select * from ruleta_detalle a where a.ruleta_id='" . $ruleta->{"a.ruleta_id"} . "' AND (moneda='' OR moneda='PEN') ";

                $ruletaDetalles = $this->execQuery($transaccion, $sqlDetalleRuleta);

                //Inicializamos variables
                $cumpleCondiciones = true;
                $condicionmetodoPago = false;
                $condicionmetodoPagocount = 0;

                $condicionPaisPV = false;
                $condicionPaisPVcount = 0;
                $condicionDepartamentoPV = false;
                $condicionDepartamentoPVcount = 0;
                $condicionCiudadPV = false;
                $condicionCiudadPVcount = 0;
                $condicionPuntoVenta = false;
                $condicionPuntoVentacount = 0;

                $condicionPaisUSER = false;
                $condicionPaisUSERcount = 0;
                $condicionDepartamentoUSER = false;
                $condicionDepartamentoUSERcount = 0;
                $condicionCiudadUSER = false;
                $condicionCiudadUSERcount = 0;

                $puederepetirRuleta = false;
                $ganaRuletaId = 0;


                $maximopago = 0;
                $maximodeposito = 0;
                $minimodeposito = 0;
                $valorruleta = 0;
                $tipoproducto = 0;
                $tiporuleta = "";
                $tiporuleta2 = $ruleta->{"a.tipo"};
                $ruletaTieneRollower = false;


                foreach ($ruletaDetalles as $ruletaDetalle) {


                    switch ($ruletaDetalle->{"a.tipo"}) {


                        case "TIPOPRODUCTO":
                            $tipoproducto = $ruletaDetalle->{"a.valor"};

                            break;

                        case "CANTDEPOSITOS":
                            if ($detalleDepositos != ($ruletaDetalle->{"a.valor"} - 1) && $ruleta->{"a.tipo"} == 2) {

                                $cumpleCondiciones = false;

                            }

                            break;

                        case "CONDEFECTIVO":
                            if ($detalleDepositoEfectivo) {
                                if (($ruletaDetalle->{"a.valor"} == "true")) {
                                    $condicionmetodoPago = true;
                                }
                            } else {
                                if (($ruletaDetalle->{"a.valor"} != "true")) {
                                    $condicionmetodoPago = true;
                                }
                            }
                            $condicionmetodoPagocount++;


                            break;


                        case "PORCENTAJE":
                            $tiporuleta = "PORCENTAJE";
                            $valorruleta = $ruletaDetalle->{"a.valor"};

                            break;


                        case "NUMERODEPOSITO":

                            break;

                        case "MAXJUGADORES":

                            break;

                        case "RANKAWARD":
                            $maximopago = $ruletaDetalle->{"a.valor"};

                            break;


                        case "MAXDEPOSITO":

                            $maximodeposito = $ruletaDetalle->{"a.valor"};
                            if ($ruletaDetalle->{"a.moneda"} == $detalleMonedaUSER) {

                                if ($detalleValorDeposito > $maximodeposito) {
                                    $cumpleCondiciones = false;
                                }
                            }
                            break;

                        case "MINDEPOSITO":

                            $minimodeposito = $ruletaDetalle->{"a.valor"};

                            if ($ruletaDetalle->{"a.moneda"} == $detalleMonedaUSER) {

                                if ($detalleValorDeposito < $minimodeposito) {
                                    $cumpleCondiciones = false;
                                }
                            }

                            break;

                        case "VALORBONO":

                            if ($ruletaDetalle->{"a.moneda"} == $detalleMonedaUSER) {

                                $valorruleta = $ruletaDetalle->{"a.valor"};
                                $tiporuleta = "VALOR";
                            }
                            break;

                        case "CONDPAYMENT":

                            if ($detalleDepositoMetodoPago == $ruletaDetalle->{"a.valor"}) {
                                $condicionmetodoPago = true;
                            }
                            $condicionmetodoPagocount++;

                            break;

                        case "CONDPAISPV":

                            $condicionPaisPVcount = $condicionPaisPVcount + 1;
                            if ($ruletaDetalle->{"a.valor"} == $detallePaisPV) {
                                $condicionPaisPV = true;
                            }

                            break;

                        case "CONDDEPARTAMENTOPV":

                            $condicionDepartamentoPVcount = $condicionDepartamentoPVcount + 1;
                            if ($ruletaDetalle->{"a.valor"} == $detalleDepartamentoPV) {
                                $condicionDepartamentoPV = true;
                            }

                            break;

                        case "CONDCIUDADPV":

                            $condicionCiudadPVcount = $condicionCiudadPVcount + 1;

                            if ($ruletaDetalle->{"a.valor"} == $detalleCiudadPV) {
                                $condicionCiudadPV = true;
                            }

                            break;

                        case "CONDPAISUSER":

                            $condicionPaisUSERcount = $condicionPaisUSERcount + 1;

                            if ($ruletaDetalle->{"a.valor"} == $detallePaisUSER) {

                                $condicionPaisUSER = true;
                            }

                            break;

                        case "CONDDEPARTAMENTOUSER":

                            $condicionDepartamentoUSERcount = $condicionDepartamentoUSERcount + 1;
                            if ($ruletaDetalle->{"a.valor"} == $detalleDepartamentoUSER) {
                                $condicionDepartamentoUSER = true;
                            }

                            break;

                        case "CONDCIUDADUSER":

                            $condicionCiudadUSERcount = $condicionCiudadUSERcount + 1;

                            if ($ruletaDetalle->{"a.valor"} == $detalleCiudadUSER) {
                                $condicionCiudadUSER = true;
                            }

                            break;

                        case "CONDPUNTOVENTA":

                            $condicionPuntoVentacount = $condicionPuntoVentacount + 1;

                            if ($ruletaDetalle->{"a.valor"} == $detallePuntoVenta) {
                                $condicionPuntoVenta = true;
                            }

                            break;

                        case "EXPDIA":

                            break;

                        case "EXPFECHA":

                            break;

                        case "WFACTORBONO":
                            $ruletaTieneRollower = true;

                            $rollowerRuleta = $ruletaDetalle->{"a.valor"};

                            break;

                        case "WFACTORDEPOSITO":
                            $ruletaTieneRollower = true;
                            $rollowerDeposito = $ruletaDetalle->{"a.valor"};

                            break;


                        case "VALORROLLOWER":
                            if ($ruletaDetalle->{"a.moneda"} == $detalleMonedaUSER) {

                                $ruletaTieneRollower = true;
                                $rollowerValor = $ruletaDetalle->{"a.valor"};
                            }
                            break;

                        case "REPETIRSORTEO":

                            if ($ruletaDetalle->{"a.valor"} == '1') {
                                $puederepetirRuleta = true;
                            }

                            break;

                        case "WINBONOID":
                            $ganaRuletaId = $ruletaDetalle->{"a.valor"};

                            break;


                        case "TIPOSALDO":
                            $tiposaldo = $ruletaDetalle->{"a.valor"};

                            //print_r("ENTRE A TIPO SALDOOO");

                            //print_r($tiposaldo);

                            //print_r("ENTRE A TIPO SALDOOO");


                            break;

                        case "LIVEORPREMATCH":

                            break;

                        case "MINSELCOUNT":

                            break;


                        case "MINSELPRICE":

                            break;

                        case "BONO":


                            $BonoId = $ruletaDetalle->{"a.valor"};
                            $description = $ruletaDetalle->{"a.descripcion"};

                            break;

                        case "FROZEWALLET":

                            break;

                        case "SUPPRESSWITHDRAWAL":

                            break;

                        case "SCHEDULECOUNT":

                            break;

                        case "SCHEDULENAME":

                            break;

                        case "SCHEDULEPERIOD":

                            break;


                        case "SCHEDULEPERIODTYPE":

                            break;

                        default:

                            //   if (stristr($ruletadetalle->{'ruleta_detalle.tipo'}, 'CONDGAME')) {
                            //
                            // }
                            //
                            //   if (stristr($ruletadetalle->{'ruleta_detalle.tipo'}, 'ITAINMENT')) {
                            //
                            //
                            //
                            //   }
                            break;
                    }


                }

                if ($condicionPaisPVcount > 0) {
                    if (!$condicionPaisPV) {
                        $cumpleCondiciones = false;
                    }

                }

                if ($condicionDepartamentoPVcount > 0) {
                    if (!$condicionDepartamentoPV) {
                        $cumpleCondiciones = false;
                    }

                }

                if ($condicionCiudadPVcount > 0) {
                    if (!$condicionCiudadPV) {
                        $cumpleCondiciones = false;
                    }

                }

                if ($condicionPaisUSERcount > 0) {
                    if (!$condicionPaisUSER) {
                        $cumpleCondiciones = false;
                    }

                }

                if ($condicionDepartamentoUSERcount > 0) {
                    if (!$condicionDepartamentoUSER) {
                        $cumpleCondiciones = false;
                    }

                }

                if ($condicionCiudadUSERcount > 0) {
                    if (!$condicionCiudadUSER) {
                        $cumpleCondiciones = false;
                    }

                }

                if ($condicionPuntoVentacount > 0) {
                    if (!$condicionPuntoVenta) {
                        $cumpleCondiciones = false;
                    }
                }

                if ($condicionmetodoPagocount > 0) {
                    if (!$condicionmetodoPago) {
                        $cumpleCondiciones = false;
                    }

                }

                if ($cumpleCondiciones) {

                    if ($puederepetirRuleta) {

                        //print_r("CUMPLE CONDICIONES 2");


                        $ruletaElegido = $ruleta->{"a.ruleta_id"};

                    } else {

                        //print_r("CUMPLE CONDICIONES 3");

                        $sqlRepiteRuleta = "select * from usuario_ruleta a where a.ruleta_id='" . $ruleta->{"a.ruleta_id"} . "' AND a.usuario_id = '" . $usuarioId . "'";
                        $repiteRuleta = $this->execQuery($transaccion, $sqlRepiteRuleta);

                        if ((!$puederepetirRuleta && oldCount($repiteRuleta) == 0)) {
                            $ruletaElegido = $ruleta->{"a.ruleta_id"};
                        } else {
                            $cumpleCondiciones = false;
                        }
                    }


                }


            }


        }

        $respuesta = array();
        $respuesta["Ruleta"] = 0;
        $respuesta["WinBonus"] = false;


        if ($ruletaElegido != 0 && $tiporuleta2 != "") {
            //print_r("BUENO Y AHORA? 3");

            //print_r($tiporuleta);

            //print_r("Este es el tipo");

            if ($tiporuleta == "PORCENTAJE") {

                //print_r("No entre ni por aqui ");
                $valor_ruleta = ($detalleValorDeposito) * ($valorruleta) / 100;

                if ($valor_ruleta > $maximopago) {
                    $valor_ruleta = $maximopago;
                }

            } elseif ($tiporuleta == "VALOR") {
                //print_r("TAMpoco entre por aqui");

                $valor_ruleta = $valorruleta;

            }

            $valorBase = $detalleValorDeposito;

            $strSql = array();
            $contSql = 0;
            $estadoRuleta = 'A';
            $rollowerRequerido = 0;

            if (!$ruletaTieneRollower) {

                //print_r(" 5555 TAMpoco entre por aqui 333");

            } else {

                //print_r(" 1111 TAMpoco entre por aqui 222");

                if ($rollowerDeposito) {
                    $rollowerRequerido = $rollowerRequerido + ($rollowerDeposito * $detalleValorDeposito);
                }

                if ($rollowerRuleta) {
                    $rollowerRequerido = $rollowerRequerido + ($rollowerRuleta * $valor_ruleta);

                }
                if ($rollowerValor) {
                    $rollowerRequerido = $rollowerRequerido + ($rollowerValor);


                }

            }

            //print_r("JULIAN");


            $strCodeBonus = "";

            if ($codebonus != "") {
                $strCodeBonus = " AND a.codigo ='" . $codebonus . "'";

            }


            //print_r("ANDRES");


            //print_r($tiporuleta2);

            //print_r("MUÑOX");


            if ($tiporuleta2 == "5") {

                $sqlRuletasFree = "select a.ruleta_id,a.usuario_id,a.estado from usuario_ruleta a INNER JOIN ruleta_interno b ON(a.ruleta_id = b.ruleta_id) where  a.estado='L' and a.ruleta_id='" . $ruletaid . "'" . $strCodeBonus;

                $ruletasFree = $this->execQuery($transaccion, $sqlRuletasFree);

                $ganoRuletaBool = false;

                foreach ($ruletasFree as $ruletaF) {

                    $sqlRuletasFree = "select a.usuruleta_id from usuario_ruleta a INNER JOIN ruleta_interno b ON(a.ruleta_id = b.ruleta_id) where  a.estado='L' and a.ruleta_id='" . $ruletaid . "'" . $strCodeBonus;
                    $ruletasFreeLibres = $this->execQuery($transaccion, $sqlRuletasFree);
                    foreach ($ruletasFreeLibres as $ruletaLibre) {
                        if (!$ganoRuletaBool) {
                            if ($transaccion == "") {
                                $contSql = $contSql + 1;
                                $strSql[$contSql] = "UPDATE usuario_ruleta a SET a.usuario_id='" . $usuarioId . "',a.fecha_crea = '" . date('Y-m-d H:i:s') . "',a.estado='A' WHERE a.usuario_id='0' AND a.estado='L' AND a.usuruleta_id='" . $ruletaLibre->usuruleta_id . "'";
                                $ganoRuletaBool = true;

                            } else {
                                $sqlstr = "UPDATE usuario_ruleta a SET a.usuario_id='" . $usuarioId . "',a.fecha_crea = '" . date('Y-m-d H:i:s') . "',a.estado='A' WHERE a.usuario_id='0' AND a.estado='L' AND a.usuruleta_id='" . $ruletaLibre->usuruleta_id . "'";

                                $q = $this->execUpdate($transaccion, $sqlstr);
                                if ($q > 0) {
                                    $ganoRuletaBool = true;

                                } else {
                                    $ganoRuletaBool = false;

                                }

                            }

                        }
                    }

                }
            } elseif ($tiporuleta2 == "6") {
                $sqlRuletasFree = "select a.ruleta_id from usuario_ruleta a INNER JOIN ruleta_interno b ON(a.ruleta_id = b.ruleta_id) where  a.estado='L' and a.ruleta_id='" . $ruletaid . "'" . $strCodeBonus;
                $ruletasFree = $this->execQuery($transaccion, $sqlRuletasFree);

                $ganoRuletaBool = false;

                foreach ($ruletasFree as $ruletaF) {

                    $sqlRuletasFree = "select a.usuruleta_id from usuario_ruleta a INNER JOIN ruleta_interno b ON(a.ruleta_id = b.ruleta_id) where  a.estado='L' and a.ruleta_id='" . $ruletaid . "'" . $strCodeBonus;
                    $ruletasFreeLibres = $this->execQuery($transaccion, $sqlRuletasFree);
                    foreach ($ruletasFreeLibres as $ruletaLibre) {
                        if (!$ganoRuletaBool) {
                            if ($transaccion == "") {

                                $contSql = $contSql + 1;
                                $strSql[$contSql] = "UPDATE usuario_ruleta a SET a.usuario_id='" . $usuarioId . "',a.fecha_crea = '" . date('Y-m-d H:i:s') . "', a.estado='A' WHERE a.usuario_id='0' AND a.estado='L' AND a.usuruleta_id='" . $ruletaLibre->usuruleta_id . "'";
                                $ganoRuletaBool = true;
                            } else {
                                $sqlstr = "UPDATE usuario_ruleta a SET a.usuario_id='" . $usuarioId . "',a.fecha_crea = '" . date('Y-m-d H:i:s') . "', a.estado='A' WHERE a.usuario_id='0' AND a.estado='L' AND a.usuruleta_id='" . $ruletaLibre->usuruleta_id . "'";

                                $q = $this->execUpdate($transaccion, $sqlstr);
                                if ($q > 0) {
                                    $ganoRuletaBool = true;

                                } else {
                                    $ganoRuletaBool = false;

                                }

                            }

                        }
                    }

                }
            } elseif ($tiporuleta2 == "3") {

                $valor_ruleta = $maximopago;


                $ganoRuletaBool = false;


                $sqlRuletasFree = "select a.usuruleta_id from usuario_ruleta a INNER JOIN ruleta_interno b ON(a.ruleta_id = b.ruleta_id) where  a.estado='L' and a.ruleta_id='" . $ruletaid . "'" . $strCodeBonus;
                $ruletasFreeLibres = $this->execQuery($transaccion, $sqlRuletasFree);

                foreach ($ruletasFreeLibres as $ruletaLibre) {
                    if (!$ganoRuletaBool) {

                        if (!$ruletaTieneRollower) {
                            $estadoRuleta = 'R';
                        } else {
                            if ($rollowerDeposito) {
                                $rollowerRequerido = $rollowerRequerido + ($rollowerDeposito * $detalleValorDeposito);
                            }

                            if ($rollowerRuleta) {
                                $rollowerRequerido = $rollowerRequerido + ($rollowerRuleta * $valor_ruleta);

                            }
                            if ($rollowerValor) {
                                $rollowerRequerido = $rollowerRequerido + ($rollowerValor);


                            }

                        }


                        if ($transaccion == '') {

                            if (!$ruletaTieneRollower) {

                                if ($ganaRuletaId == 0) {
                                    switch ($tiposaldo) {
                                        case 0:
                                            $contSql = $contSql + 1;
                                            $strSql[$contSql] = "update registro set creditos_base_ant=creditos_base,creditos_base=creditos_base+" . $valor_ruleta . " where mandante=" . $mandante . " and usuario_id=" . $usuarioId;


                                            $estadoRuleta = 'R';

                                            break;

                                        case 1:
                                            $contSql = $contSql + 1;
                                            $strSql[$contSql] = "update registro set creditos_ant=creditos,creditos=creditos+" . $valor_ruleta . " where mandante=" . $mandante . " and usuario_id=" . $usuarioId;


                                            $estadoRuleta = 'R';

                                            break;

                                        case 2:
                                            $contSql = $contSql + 1;
                                            $strSql[$contSql] = "update registro set saldo_especial=saldo_especial+" . $valor_ruleta . " where mandante=" . $mandante . " and usuario_id=" . $usuarioId;

                                            $estadoRuleta = 'R';
                                            $SumoSaldo = true;

                                            break;

                                    }

                                } else {

                                    $resp = $this->agregarRuletaFree($ganaRuletaId, $usuarioId, $mandante, $detalles, '', '', $transaccion);

                                    if ($transaccion == "") {
                                        foreach ($resp->queries as $val) {
                                            $contSql = $contSql + 1;
                                            $strSql[$contSql] = $val;
                                        }
                                    }

                                    $estadoRuleta = 'R';

                                }

                            } else {
                                if ($rollowerDeposito) {
                                    $rollowerRequerido = $rollowerRequerido + ($rollowerDeposito * $detalleValorDeposito);
                                }

                                if ($rollowerRuleta) {
                                    $rollowerRequerido = $rollowerRequerido + ($rollowerRuleta * $valor_ruleta);

                                }
                                if ($rollowerValor) {
                                    $rollowerRequerido = $rollowerRequerido + ($rollowerValor);


                                }
                                $contSql = $contSql + 1;
                                $strSql[$contSql] = "update registro,ruleta_interno set registro.creditos_ruleta=registro.creditos_ruleta+" . $valor_ruleta . " where registro.mandante=" . $mandante . " and registro.usuario_id=" . $usuarioId . " AND ruleta_id ='" . $ruletaElegido . "'";

                            }

                            $contSql = $contSql + 1;
                            $strSql[$contSql] = "UPDATE usuario_ruleta a SET a.usuario_id='" . $usuarioId . "',a.fecha_crea = '" . date('Y-m-d H:i:s') . "',a.rollower_requerido='" . $rollowerRequerido . "',a.valor_ruleta='" . $valor_ruleta . "',a.valor='" . $valor_ruleta . "', a.estado='" . $estadoRuleta . "' WHERE a.usuario_id='0' AND a.estado='L' AND a.usuruleta_id='" . $ruletaLibre->usuruleta_id . "'";

                            $contSql = $contSql + 1;
                            $strSql[$contSql] = "INSERT INTO ruleta_log (usuario_id,tipo,valor,estado,id_externo,mandante,transaccion_id,tiporuleta_id,fecha_crea,fecha_cierre) SELECT a.usuario_id,CASE WHEN b.tipo = 2 THEN 'D' ELSE 'F' END,a.valor,'L',a.usuruleta_id,0,'0',4,now(),now()  FROM  usuario_ruleta a INNER JOIN ruleta_interno  b ON (b.ruleta_id = a.ruleta_id)  WHERE a.usuruleta_id = " . $ruletaLibre->usuruleta_id . " AND a.apostado >= a.rollower_requerido";

                            $ganoRuletaBool = true;

                        } else {

                            //print_r("AHORA SI ESTOY POR AQUI  2");

                            $sqlstr = "UPDATE usuario_ruleta a SET a.usuario_id='" . $usuarioId . "',a.fecha_crea = '" . date('Y-m-d H:i:s') . "',a.rollower_requerido='" . $rollowerRequerido . "',a.valor_ruleta='" . $valor_ruleta . "',a.valor='" . $valor_ruleta . "', a.estado='" . $estadoRuleta . "' WHERE a.usuario_id='0' AND a.estado='L' AND a.usuruleta_id='" . $ruletaLibre->usuruleta_id . "'";

                            $q = $this->execUpdate($transaccion, $sqlstr);
                            if ($q > 0) {

                                $sqlstr = "INSERT INTO ruleta_log (usuario_id,tipo,valor,estado,id_externo,mandante,transaccion_id,tiporuleta_id,fecha_crea,fecha_cierre) SELECT a.usuario_id,CASE WHEN b.tipo = 2 THEN 'D' ELSE 'F' END,a.valor,'L',a.usuruleta_id,0,'0',4,now(),now()  FROM  usuario_ruleta a INNER JOIN ruleta_interno  b ON (b.ruleta_id = a.ruleta_id)  WHERE a.usuruleta_id = " . $ruletaLibre->usuruleta_id . " AND a.apostado >= a.rollower_requerido";

                                $q = $this->execUpdate($transaccion, $sqlstr);


                                if (!$ruletaTieneRollower) {

                                    //print_r("AHORA SI ESTOY POR AQUI");
                                    if ($ganaRuletaId == 0) {

                                        //print_r("AHORA SI ESTOY POR AQUI 4");

                                        switch ($tiposaldo) {
                                            case 0:
                                                $sqlstr = "update registro set creditos_base_ant=creditos_base,creditos_base=creditos_base+" . $valor_ruleta . " where mandante=" . $mandante . " and usuario_id=" . $usuarioId;
                                                $q = $this->execUpdate($transaccion, $sqlstr);


                                                $estadoRuleta = 'R';

                                                break;

                                            case 1:
                                                $sqlstr = "update registro set creditos_ant=creditos,creditos=creditos+" . $valor_ruleta . " where mandante=" . $mandante . " and usuario_id=" . $usuarioId;

                                                $q = $this->execUpdate($transaccion, $sqlstr);

                                                $estadoRuleta = 'R';

                                                break;

                                            case 2:
                                                $sqlstr = "update registro set saldo_especial=saldo_especial+" . $valor_ruleta . " where mandante=" . $mandante . " and usuario_id=" . $usuarioId;
                                                $q = $this->execUpdate($transaccion, $sqlstr);

                                                $estadoRuleta = 'R';
                                                $SumoSaldo = true;

                                                break;

                                        }

                                    } else {

                                        $resp = $this->agregarRuletaFree($ganaRuletaId, $usuarioId, $mandante, $detalles, '', '', $transaccion);

                                        if ($transaccion == "") {
                                            foreach ($resp->queries as $val) {
                                                $contSql = $contSql + 1;
                                                $strSql[$contSql] = $val;
                                            }
                                        }

                                        $estadoRuleta = 'R';

                                    }

                                } else {
                                    if ($rollowerDeposito) {
                                        $rollowerRequerido = $rollowerRequerido + ($rollowerDeposito * $detalleValorDeposito);
                                    }

                                    if ($rollowerRuleta) {
                                        $rollowerRequerido = $rollowerRequerido + ($rollowerRuleta * $valor_ruleta);

                                    }
                                    if ($rollowerValor) {
                                        $rollowerRequerido = $rollowerRequerido + ($rollowerValor);


                                    }

                                    $sqlstr = "update registro,ruleta_interno set registro.creditos_ruleta=registro.creditos_ruleta+" . $valor_ruleta . " where registro.mandante=" . $mandante . " and registro.usuario_id=" . $usuarioId . " AND ruleta_id ='" . $ruletaElegido . "'";

                                    $q = $this->execUpdate($transaccion, $sqlstr);

                                }

                                $ganoRuletaBool = true;


                            } else {
                                $ganoRuletaBool = false;

                            }
                        }


                    }
                }

            } elseif ($tiporuleta2 == "2") {


                //print_r("LOGICAAAA");


                //print_r("Este es el maximo pago");

                //print_r($maximopago);


                $valor_ruleta = $maximopago;


                $ganoRuletaBool = false;


                $sqlRuletasFree = "select a.usuruleta_id from usuario_ruleta a INNER JOIN ruleta_interno b ON(a.ruleta_id = b.ruleta_id) where  a.estado='P' and a.ruleta_id='" . $ruletaid . "'" . $strCodeBonus;
                $ruletasFreeLibres = $this->execQuery($transaccion, $sqlRuletasFree);

                foreach ($ruletasFreeLibres as $ruletaLibre) {
                    if (!$ganoRuletaBool) {

                        if (!$ruletaTieneRollower) {
                            $estadoRuleta = 'R';
                        } else {
                            if ($rollowerDeposito) {
                                $rollowerRequerido = $rollowerRequerido + ($rollowerDeposito * $detalleValorDeposito);
                            }

                            if ($rollowerRuleta) {
                                $rollowerRequerido = $rollowerRequerido + ($rollowerRuleta * $valor_ruleta);

                            }
                            if ($rollowerValor) {
                                $rollowerRequerido = $rollowerRequerido + ($rollowerValor);


                            }

                        }

                        //print_r("Este es el tipo de saldo ");

                        //print_r($tiposaldo);


                        if ($transaccion == '') {


                            if (!$ruletaTieneRollower) {

                                if ($ganaRuletaId == 0) {


                                    switch ($tiposaldo) {
                                        case 0:


                                            $contSql = $contSql + 1;
                                            $strSql[$contSql] = "update registro set creditos_base_ant=creditos_base,creditos_base=creditos_base+" . $valor_ruleta . " where mandante=" . $mandante . " and usuario_id=" . $usuarioId;


                                            $estadoRuleta = 'R';

                                            break;

                                        case 1:
                                            $contSql = $contSql + 1;
                                            $strSql[$contSql] = "update registro set creditos_ant=creditos,creditos=creditos+" . $valor_ruleta . " where mandante=" . $mandante . " and usuario_id=" . $usuarioId;


                                            $estadoRuleta = 'R';

                                            break;

                                        case 2:
                                            $contSql = $contSql + 1;
                                            $strSql[$contSql] = "update registro set saldo_especial=saldo_especial+" . $valor_ruleta . " where mandante=" . $mandante . " and usuario_id=" . $usuarioId;

                                            $estadoRuleta = 'R';
                                            $SumoSaldo = true;

                                            break;

                                    }

                                } else {

                                    $resp = $this->agregarRuletaFree($ganaRuletaId, $usuarioId, $mandante, $detalles, '', '', $transaccion);

                                    if ($transaccion == "") {
                                        foreach ($resp->queries as $val) {
                                            $contSql = $contSql + 1;
                                            $strSql[$contSql] = $val;
                                        }
                                    }

                                    $estadoRuleta = 'R';

                                }

                            } else {
                                if ($rollowerDeposito) {
                                    $rollowerRequerido = $rollowerRequerido + ($rollowerDeposito * $detalleValorDeposito);
                                }

                                if ($rollowerRuleta) {
                                    $rollowerRequerido = $rollowerRequerido + ($rollowerRuleta * $valor_ruleta);

                                }
                                if ($rollowerValor) {
                                    $rollowerRequerido = $rollowerRequerido + ($rollowerValor);


                                }
                                $contSql = $contSql + 1;
                                $strSql[$contSql] = "update registro,ruleta_interno set registro.creditos_ruleta=registro.creditos_ruleta+" . $valor_ruleta . " where registro.mandante=" . $mandante . " and registro.usuario_id=" . $usuarioId . " AND ruleta_id ='" . $ruletaElegido . "'";

                            }

                            $contSql = $contSql + 1;
                            $strSql[$contSql] = "UPDATE usuario_ruleta a SET a.usuario_id='" . $usuarioId . "',a.fecha_crea = '" . date('Y-m-d H:i:s') . "',a.rollower_requerido='" . $rollowerRequerido . "',a.valor_ruleta='" . $valor_ruleta . "',a.valor='" . $valor_ruleta . "', a.estado='" . $estadoRuleta . "' WHERE a.usuario_id='0' AND a.estado='L' AND a.usuruleta_id='" . $ruletaLibre->usuruleta_id . "'";

                            $contSql = $contSql + 1;
                            $strSql[$contSql] = "INSERT INTO ruleta_log (usuario_id,tipo,valor,estado,id_externo,mandante,transaccion_id,tiporuleta_id,fecha_crea,fecha_cierre) SELECT a.usuario_id,CASE WHEN b.tipo = 2 THEN 'D' ELSE 'F' END,a.valor,'L',a.usuruleta_id,0,'0',4,now(),now()  FROM  usuario_ruleta a INNER JOIN ruleta_interno  b ON (b.ruleta_id = a.ruleta_id)  WHERE a.usuruleta_id = " . $ruletaLibre->usuruleta_id . " AND a.apostado >= a.rollower_requerido";

                            $ganoRuletaBool = true;

                        } else {

                            //print_r("AHORA SI ESTOY POR AQUI  2");

                            //$sqlstr = "UPDATE usuario_ruleta a SET a.usuario_id='" . $usuarioId . "',a.fecha_crea = '" . date('Y-m-d H:i:s') . "',a.rollower_requerido='" . $rollowerRequerido . "',a.valor_ruleta='" . $valor_ruleta . "',a.valor='" . $valor_ruleta . "', a.estado='" . $estadoRuleta . "' WHERE a.usuario_id='0' AND a.estado='L' AND a.usuruleta_id='" . $ruletaLibre->usuruleta_id . "'";


                            /*try{
                                $q = $this->execUpdate($transaccion, $sqlstr);

                            }catch(Exception $e){
                                //print_r($e);
                            }*/


                            //print_r("AVANCEEEEEEEEEEEEE");

                            if (true) {

                                //$sqlstr = "INSERT INTO ruleta_log (usuario_id,tipo,valor,estado,id_externo,mandante,transaccion_id,tiporuleta_id,fecha_crea,fecha_cierre) SELECT a.usuario_id,CASE WHEN b.tipo = 2 THEN 'D' ELSE 'F' END,a.valor,'L',a.usuruleta_id,0,'0',4,now(),now()  FROM  usuario_ruleta a INNER JOIN ruleta_interno  b ON (b.ruleta_id = a.ruleta_id)  WHERE a.usuruleta_id = " . $ruletaLibre->usuruleta_id . " AND a.apostado >= a.rollower_requerido";

                                //$q = $this->execUpdate($transaccion, $sqlstr);


                                if (!$ruletaTieneRollower) {


                                    //print_r("AHORA SI ESTOY POR AQUI");

                                    if ($ganaRuletaId == 0) {


                                        if ($BonoId != "" && $BonoId != null) {


                                            if (true) {


                                                $BonoInterno = new BonoInterno($BonoId);


                                                if ($BonoInterno->tipo == 2) {

                                                    $UsuarioBono = new UsuarioBono();
                                                    $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
                                                    $transaccion5 = $BonoDetalleMySqlDAO->getTransaction();
                                                    $UsuarioBono->setUsuarioId(0);
                                                    $UsuarioBono->setBonoId($BonoInterno->bonoId);
                                                    $UsuarioBono->setValor(0);
                                                    $UsuarioBono->setValorBono(0);
                                                    $UsuarioBono->setValorBase(0);
                                                    $UsuarioBono->setEstado("L");
                                                    $UsuarioBono->setErrorId(0);
                                                    $UsuarioBono->setIdExterno(0);
                                                    $UsuarioBono->setMandante($mandante);
                                                    $UsuarioBono->setUsucreaId(0);
                                                    $UsuarioBono->setUsumodifId(0);
                                                    $UsuarioBono->setApostado(0);
                                                    $UsuarioBono->setRollowerRequerido(0);
                                                    $UsuarioBono->setCodigo("");
                                                    $UsuarioBono->setVersion(0);
                                                    $UsuarioBono->setExternoId(0);

                                                    $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($transaccion5);

                                                    $inse = $UsuarioBonoMysqlDAO->insert($UsuarioBono);
                                                    $transaccion5->commit();
                                                }
                                                if ($BonoInterno->tipo == 3) {

                                                    $UsuarioBono = new UsuarioBono();
                                                    $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
                                                    $transaccion5 = $BonoDetalleMySqlDAO->getTransaction();
                                                    $UsuarioBono->setUsuarioId(0);
                                                    $UsuarioBono->setBonoId($BonoInterno->bonoId);
                                                    $UsuarioBono->setValor(0);
                                                    $UsuarioBono->setValorBono(0);
                                                    $UsuarioBono->setValorBase(0);
                                                    $UsuarioBono->setEstado("L");
                                                    $UsuarioBono->setErrorId(0);
                                                    $UsuarioBono->setIdExterno(0);
                                                    $UsuarioBono->setMandante($mandante);
                                                    $UsuarioBono->setUsucreaId(0);
                                                    $UsuarioBono->setUsumodifId(0);
                                                    $UsuarioBono->setApostado(0);
                                                    $UsuarioBono->setRollowerRequerido(0);
                                                    $UsuarioBono->setCodigo("");
                                                    $UsuarioBono->setVersion(0);
                                                    $UsuarioBono->setExternoId(0);

                                                    $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($transaccion5);

                                                    $inse = $UsuarioBonoMysqlDAO->insert($UsuarioBono);
                                                    $transaccion5->commit();
                                                }

                                                if ($BonoInterno->tipo == 5) {

                                                    //print_r("VOY POR BUEN CAMINO 222");


                                                    $UsuarioBono = new UsuarioBono();
                                                    $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
                                                    $transaccion5 = $BonoDetalleMySqlDAO->getTransaction();
                                                    $UsuarioBono->setUsuarioId(0);
                                                    $UsuarioBono->setBonoId($BonoInterno->bonoId);
                                                    $UsuarioBono->setValor(0);
                                                    $UsuarioBono->setValorBono(0);
                                                    $UsuarioBono->setValorBase(0);
                                                    $UsuarioBono->setEstado("L");
                                                    $UsuarioBono->setErrorId(0);
                                                    $UsuarioBono->setIdExterno(0);
                                                    $UsuarioBono->setMandante($mandante);
                                                    $UsuarioBono->setUsucreaId(0);
                                                    $UsuarioBono->setUsumodifId(0);
                                                    $UsuarioBono->setApostado(0);
                                                    $UsuarioBono->setRollowerRequerido(0);
                                                    $UsuarioBono->setCodigo("");
                                                    $UsuarioBono->setVersion(0);
                                                    $UsuarioBono->setExternoId(0);


                                                    //print_r($UsuarioBono);

                                                    $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($transaccion5);

                                                    $inse = $UsuarioBonoMysqlDAO->insert($UsuarioBono);
                                                    $transaccion5->commit();
                                                }


                                                if ($BonoInterno->tipo == 6) {
                                                    $UsuarioBono = new UsuarioBono();
                                                    $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
                                                    $transaccion5 = $BonoDetalleMySqlDAO->getTransaction();
                                                    $UsuarioBono->setUsuarioId(0);
                                                    $UsuarioBono->setBonoId($BonoInterno->bonoId);
                                                    $UsuarioBono->setValor(0);
                                                    $UsuarioBono->setValorBono(0);
                                                    $UsuarioBono->setValorBase(0);
                                                    $UsuarioBono->setEstado("L");
                                                    $UsuarioBono->setErrorId(0);
                                                    $UsuarioBono->setIdExterno(0);
                                                    $UsuarioBono->setMandante($mandante);
                                                    $UsuarioBono->setUsucreaId(0);
                                                    $UsuarioBono->setUsumodifId(0);
                                                    $UsuarioBono->setApostado(0);
                                                    $UsuarioBono->setRollowerRequerido(0);
                                                    $UsuarioBono->setCodigo("");
                                                    $UsuarioBono->setVersion(0);
                                                    $UsuarioBono->setExternoId(0);

                                                    $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($transaccion5);

                                                    $inse = $UsuarioBonoMysqlDAO->insert($UsuarioBono);
                                                    $transaccion5->commit();
                                                }


                                                $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
                                                $transaccion4 = $BonoDetalleMySqlDAO->getTransaction();
                                                $transaccion4->getConnection()->beginTransaction();
                                                $usuarioSql = "select ciudad.ciudad_id,ciudad.depto_id,pais.pais_id,usuario.mandante FROM registro
        INNER JOIN usuario ON (usuario.usuario_id = registro.usuario_id)
      INNER JOIN pais ON (pais.pais_id = usuario.pais_id)
        LEFT OUTER JOIN ciudad ON (ciudad.ciudad_id = registro.ciudad_id)
      LEFT OUTER JOIN departamento ON (ciudad.depto_id = departamento.depto_id)
       WHERE registro.usuario_id='" . $usuarioId . "'";

                                                $Usuario = $BonoInterno->execQuery($transaccion4, $usuarioSql);


                                                $dataUsuario = $Usuario;
                                                $detalles = array(
                                                    "PaisUSER" => $dataUsuario[0]->{'pais.pais_id'},
                                                    "DepartamentoUSER" => $dataUsuario[0]->{'ciudad.depto_id'},
                                                    "CiudadUSER" => $dataUsuario[0]->{'ciudad.ciudad_id'},

                                                );


                                                $detalles = json_decode(json_encode($detalles));


                                                $respuestaBONO = $BonoInterno->agregarBonoFree($BonoInterno->bonoId, $usuarioId, $dataUsuario[0]->{'usuario.mandante'}, $detalles, true, "", $transaccion);

                                                $transaccion4->commit();

                                                //print_r_r($respuestaBONO);


                                            }


                                        } else {

                                            switch ($tiposaldo) {
                                                case 0:

                                                    //print_r("Estoy en tipo saldo CEROOOO");


                                                    try {
                                                        //print_r($valor_ruleta);
                                                        //print_r("Lo anterior es el valor de la ruleta");
                                                        $sqlstr = "update registro set creditos_base_ant=creditos_base,creditos_base=creditos_base+" . $valor_ruleta . " where mandante=" . $mandante . " and usuario_id=" . $usuarioId;
                                                        $q = $this->execUpdate($transaccion, $sqlstr);

                                                    } catch (Exception $e) {
                                                        //print_r($e);
                                                    }


                                                    //print_r($q);


                                                    //print_r("QUE PASOOOO");


                                                    $estadoRuleta = 'R';

                                                    break;

                                                case 1:

                                                    //print_r("ENTRE POR DONDE ES");
                                                    $sqlstr = "update registro set creditos_ant=creditos,creditos=creditos+" . $valor_ruleta . " where mandante=" . $mandante . " and usuario_id=" . $usuarioId;

                                                    $q = $this->execUpdate($transaccion, $sqlstr);

                                                    $estadoRuleta = 'R';

                                                    break;

                                                case 2:
                                                    $sqlstr = "update registro set saldo_especial=saldo_especial+" . $valor_ruleta . " where mandante=" . $mandante . " and usuario_id=" . $usuarioId;
                                                    $q = $this->execUpdate($transaccion, $sqlstr);

                                                    $estadoRuleta = 'R';
                                                    $SumoSaldo = true;

                                                    break;

                                            }

                                        }


                                    } else {

                                        $resp = $this->agregarRuletaFree($ganaRuletaId, $usuarioId, $mandante, $detalles, '', '', $transaccion);

                                        if ($transaccion == "") {
                                            foreach ($resp->queries as $val) {
                                                $contSql = $contSql + 1;
                                                $strSql[$contSql] = $val;
                                            }
                                        }

                                        $estadoRuleta = 'R';

                                    }

                                } else {
                                    if ($rollowerDeposito) {
                                        $rollowerRequerido = $rollowerRequerido + ($rollowerDeposito * $detalleValorDeposito);
                                    }

                                    if ($rollowerRuleta) {
                                        $rollowerRequerido = $rollowerRequerido + ($rollowerRuleta * $valor_ruleta);

                                    }
                                    if ($rollowerValor) {
                                        $rollowerRequerido = $rollowerRequerido + ($rollowerValor);


                                    }

                                    $sqlstr = "update registro,ruleta_interno set registro.creditos_ruleta=registro.creditos_ruleta+" . $valor_ruleta . " where registro.mandante=" . $mandante . " and registro.usuario_id=" . $usuarioId . " AND ruleta_id ='" . $ruletaElegido . "'";

                                    $q = $this->execUpdate($transaccion, $sqlstr);

                                }

                                $ganoRuletaBool = true;


                            } else {
                                $ganoRuletaBool = false;

                            }


                        }


                    }
                }


                $TypeBalance = "1";
                $Amount = floatval($valor_ruleta);

                $BonoLog = new BonoLog();
                $BonoLog->setUsuarioId($usuarioId);
                $BonoLog->setTipo("E");
                $BonoLog->setValor($Amount);
                $BonoLog->setFechaCrea(date('Y-m-d H:i:s'));
                $BonoLog->setEstado('L');
                $BonoLog->setErrorId(0);


                $BonoLog->setIdExterno($ruletaid);
                $BonoLog->setMandante($mandante);
                $BonoLog->setFechaCierre('');
                $BonoLog->setTransaccionId('');
                $BonoLog->setTipobonoId(4);
                $BonoLog->setTiposaldoId($TypeBalance);


                $BonoLogMySqlDAO = new BonoLogMySqlDAO();
                $Transaction2 = $BonoLogMySqlDAO->getTransaction();


                try {

                    $bonologId = $BonoLogMySqlDAO->insert($BonoLog);
                    $Transaction2->commit();

                } catch (Exception $e) {
                    //print_r($e);
                }


                /*$UsuarioHistorial = new UsuarioHistorial();
                $UsuarioHistorial->setUsuarioId($usuarioId);
                $UsuarioHistorial->setDescripcion('');
                $UsuarioHistorial->setMovimiento("E");
                $UsuarioHistorial->setUsucreaId(0);
                $UsuarioHistorial->setUsumodifId(0);
                $UsuarioHistorial->setTipo(50);


                $UsuarioHistorial->setValor($Amount);
                $UsuarioHistorial->setExternoId($bonologId);



                $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO();
                $Transaction3 = $UsuarioHistorialMySqlDAO->getTransaction();

                try{

                    $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);

                }catch (Exception $e){
                   //print_r($e);
                }


                $Transaction3->commit();*/

            }


            $respuesta["WinBonus"] = true;
            $respuesta["Ruleta"] = $ruletaElegido;
            $respuesta["queries"] = $strSql;


            if ($transaccion != "") {

            } else {
                if ($ejecutarSQL) {
                    foreach ($respuesta["queries"] as $querie) {

                        $transaccionNueva = false;
                        if ($transaccion == '') {
                            $RuletaInternoMySqlDAO = new RuletaInternoMySqlDAO();
                            $transaccion = $RuletaInternoMySqlDAO->getTransaction();
                            $transaccionNueva = true;

                        }
                        foreach ($respuesta["queries"] as $querie) {

                            $this->execUpdate($transaccion, $querie);


                        }

                        if ($transaccionNueva) {
                            $transaccion->commit();
                        }

                    }
                }
            }
        }

        return json_decode(json_encode($respuesta));

    }

    /**
     * Verficiar rollower en el ruleta
     *
     *
     * @param String usuarioId id del usuario
     * @param String detalles detalles
     * @param String tipoProducto tipoProducto
     * @param String ticketId id del tiquete
     *
     * @return Array $result resultado de la verificación
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function verificarRuletaRollower($usuarioId, $detalles, $tipoProducto, $ticketId)
    {

        //Verificamos Detalles a tomar en cuenta al momento de verificar y aumentar rollower
        $detalleTipoApuesta = $detalles->TipoApuesta;
        $detalleSelecciones = $detalles->Selecciones;
        $detalleJuegosCasino = $detalles->JuegosCasino;
        $detalleValorApuesta = $detalles->ValorApuesta;
        $detalleCuotaTotal = 1;

        $respuesta = array();
        $respuesta["Ruleta"] = 0;
        $respuesta["WinBonus"] = false;


        if (($tipoProducto == "SPORT" || $tipoProducto == "CASINO") && $usuarioId != "") {
            $ruletaid = 0;
            $usuruleta_id = 0;
            $valorASumar = 0;

            //Obtenemos todos los ruletas disponibles
            $sqlRuleta = "select a.usuruleta_id,a.ruleta_id,a.apostado,a.rollower_requerido,a.fecha_crea,ruleta_interno.condicional,ruleta_interno.tipo from usuario_ruleta a INNER JOIN ruleta_interno ON (ruleta_interno.ruleta_id = a.ruleta_id ) where  a.estado='A' AND (ruleta_interno.tipo = 2 OR ruleta_interno.tipo = 3) AND a.usuario_id='" . $usuarioId . "'";
            $ruletasDisponibles = $this->execQuery($sqlRuleta);

            if (oldCount($ruletasDisponibles) > 0) {
                if ($tipoProducto == "SPORT") {

                    //$sqlSport = "select te.betmode,te.vlr_apuesta,td.ticket_id,td.apuesta,td.agrupador,td.logro,td.opcion,td.apuesta_id,td.agrupador_id,td.fecha_evento,td.hora_evento,te.usuario_id,td.sportid,td.matchid,td.ligaid from it_ticket_det td INNER JOIN it_ticket_enc te ON (td.ticket_id = te.ticket_id ) where  te.ticket_id='".$ticketId."' ";
                    $sqlSport = "select te.betmode,te.vlr_apuesta,td.ticket_id,td.apuesta,td.agrupador,td.logro,td.opcion,td.apuesta_id,td.agrupador_id,td.fecha_evento,td.hora_evento,te.usuario_id from it_ticket_det td INNER JOIN it_ticket_enc te ON (td.ticket_id = te.ticket_id ) where  te.ticket_id='" . $ticketId . "' ";

                    $detalleTicket = $this->execQuery($sqlSport);

                    $array = array();


                    foreach ($detalleTicket as $detalle) {
                        $detalles = array(
                            // "Deporte"=>$detalle->sportid,
                            // "Liga"=>$detalle->ligaid,
                            // "Evento"=>$detalle->apuesta_id,
                            //  "Cuota"=>$detalle->logro

                        );
                        $detalleValorApuesta = $detalle->vlr_apuesta;


                        array_push($array, $detalles);

                        $usuarioId = $detalle->usuario_id;
                        $betmode = $detalle->betmode;
                        $detalleCuotaTotal = $detalleCuotaTotal * $detalle->logro;

                    }
                    $detallesFinal = json_decode(json_encode($array));

                    $detalleSelecciones = $detallesFinal;


                }

                if ($tipoProducto == "CASINO") {
                    $sqlSport = "select ct.tipo,ct.monto,ct.juego_id,ct.id,ct.usuario_id from casino_transaccion ct where  ct.id='" . $ticketId . "' ";
                    $detalleTicket = $this->execQuery($sqlSport);

                    $array = array();


                    foreach ($detalleTicket as $detalle) {
                        $detalles = array(
                            "Id" => $detalle->juego_id
                        );
                        $detalleValorApuesta = $detalle->monto;

                        array_push($array, $detalles);

                        $usuarioId = $detalle->usuario_id;
                    }
                    $detallesFinal = json_decode(json_encode($array));

                    $detalleJuegosCasino = $detallesFinal;

                }

            }
            $tipoProducto = "";

            foreach ($ruletasDisponibles as $ruleta) {
                if ($ruletaid == 0) {

                    //Obtenemos todos los detalles del ruleta
                    $sqlDetalleRuleta = "select * from ruleta_detalle a where a.ruleta_id='" . $ruleta->{"a.ruleta_id"} . "' AND (moneda='' OR moneda='PEN') ";
                    $ruletaDetalles = $this->execQuery($sqlDetalleRuleta);


                    //Inicializamos variables
                    $cumplecondicion = true;
                    $cumplecondicionproducto = false;
                    $condicionesproducto = 0;
                    $ruletaid = 0;
                    $valorapostado = 0;
                    $valorrequerido = 0;
                    $valorASumar = 0;

                    $sePuedeSimples = 0;
                    $sePuedeCombinadas = 0;
                    $minselcount = 0;

                    $ganaRuletaId = 0;
                    $tiporuleta = "";
                    $ganaRuletaId = 0;

                    if ($ruleta->{"a.condicional"} == 'NA' || $ruleta->{"a.condicional"} == '') {
                        $tipocomparacion = "OR";

                    } else {
                        $tipocomparacion = $ruleta->{"a.condicional"};

                    }


                    foreach ($ruletaDetalles as $ruletaDetalle) {

                        switch ($ruletaDetalle->{"a.tipo"}) {

                            case "TIPOPRODUCTO":

                                $tipoProducto = $ruletaDetalle->{"a.valor"};
                                break;

                            case "EXPDIA":
                                $fechaRuleta = date('Y-m-d H:i:ss', strtotime($ruleta->{"fecha_crea"} . ' + ' . $ruletaDetalle->{"a.valor"} . ' days'));
                                $fecha_actual = date("Y-m-d H:i:ss", time());

                                if ($fechaRuleta < $fecha_actual) {
                                    $cumplecondicion = false;
                                }

                                break;

                            case "EXPFECHA":
                                $fechaRuleta = date('Y-m-d H:i:ss', strtotime($ruletaDetalle->{"a.valor"}));
                                $fecha_actual = strtotime(date("Y-m-d H:i:ss", time()));

                                if ($fechaRuleta < $fecha_actual) {
                                    $cumplecondicion = false;
                                }
                                break;


                            case "LIVEORPREMATCH":


                                if ($ruletaDetalle->{"a.valor"} == 2) {
                                    if ($betmode == "PreLive") {
                                        $cumplecondicionproducto = true;

                                    } else {
                                        $cumplecondicionproducto = false;


                                    }

                                }

                                if ($ruletaDetalle->{"a.valor"} == 1) {
                                    if ($betmode == "Live") {
                                        $cumplecondicionproducto = true;

                                    } else {
                                        $cumplecondicionproducto = false;


                                    }

                                }

                                if ($ruletaDetalle->{"a.valor"} == 0) {
                                    /*if($betmode == "Mixed") {
                                        $cumplecondicionproducto = true;

                                    }else{
                                        $cumplecondicionproducto = false;


                                    }*/

                                }

                                break;

                            case "MINSELCOUNT":
                                $minselcount = $ruletaDetalle->{"a.valor"};

                                if ($ruletaDetalle->{"a.valor"} > oldCount($detalleSelecciones)) {
                                    //$cumplecondicion = false;

                                }

                                break;

                            case "MINSELPRICE":

                                foreach ($detalleSelecciones as $item) {
                                    if ($ruletaDetalle->{"a.valor"} > $item->Cuota) {
                                        $cumplecondicion = false;

                                    }
                                }


                                break;


                            case "MINSELPRICETOTAL":

                                if ($ruletaDetalle->{"a.valor"} > $detalleCuotaTotal) {
                                    $cumplecondicion = false;

                                }


                                break;

                            case "MINBETPRICE":


                                if ($ruletaDetalle->{"a.valor"} > $detalleValorApuesta) {
                                    $cumplecondicion = false;

                                }

                                break;

                            case "WINBONOID":
                                $ganaRuletaId = $ruletaDetalle->{"a.valor"};
                                $tiporuleta = "WINBONOID";
                                $valor_ruleta = 0;

                                break;

                            case "TIPOSALDO":
                                $tiposaldo = $ruletaDetalle->{"a.valor"};

                                break;

                            case "FROZEWALLET":

                                break;

                            case "SUPPRESSWITHDRAWAL":

                                break;

                            case "SCHEDULECOUNT":

                                break;

                            case "SCHEDULENAME":

                                break;

                            case "SCHEDULEPERIOD":

                                break;


                            case "SCHEDULEPERIODTYPE":

                                break;

                            case "ITAINMENT1":

                                foreach ($detalleSelecciones as $item) {
                                    if ($tipocomparacion == "OR") {
                                        if ($ruletaDetalle->{"a.valor"} == $item->Deporte) {
                                            $cumplecondicionproducto = true;

                                        }
                                    } elseif ($tipocomparacion == "AND") {
                                        if ($ruletaDetalle->{"a.valor"} != $item->Deporte) {
                                            $cumplecondicionproducto = false;


                                        }

                                        if ($condicionesproducto == 0) {
                                            if ($ruletaDetalle->{"a.valor"} == $item->Deporte) {
                                                $cumplecondicionproducto = true;
                                            }
                                        } else {
                                            if ($ruletaDetalle->{"a.valor"} == $item->Deporte && $cumplecondicionproducto) {
                                                $cumplecondicionproducto = true;

                                            }
                                        }

                                    }

                                }

                                $condicionesproducto++;
                                break;

                            case "ITAINMENT3":


                                foreach ($detalleSelecciones as $item) {
                                    if ($tipocomparacion == "OR") {
                                        if ($ruletaDetalle->{"a.valor"} == $item->Liga) {
                                            $cumplecondicionproducto = true;

                                        }
                                    } elseif ($tipocomparacion == "AND") {
                                        if ($ruletaDetalle->{"a.valor"} != $item->Liga) {
                                            $cumplecondicionproducto = false;

                                        }

                                        if ($condicionesproducto == 0) {
                                            if ($ruletaDetalle->{"a.valor"} == $item->Liga) {
                                                $cumplecondicionproducto = true;
                                            }
                                        } else {
                                            if ($ruletaDetalle->{"a.valor"} == $item->Liga && $cumplecondicionproducto) {
                                                $cumplecondicionproducto = true;

                                            }
                                        }

                                    }

                                }

                                $condicionesproducto++;

                                break;
                            case "ITAINMENT4":


                                foreach ($detalleSelecciones as $item) {
                                    if ($tipocomparacion == "OR") {
                                        if ($ruletaDetalle->{"a.valor"} == $item->Evento) {
                                            $cumplecondicionproducto = true;

                                        }
                                    } elseif ($tipocomparacion == "AND") {
                                        if ($ruletaDetalle->{"a.valor"} != $item->Evento) {
                                            $cumplecondicionproducto = false;

                                        }

                                        if ($condicionesproducto == 0) {

                                            if ($ruletaDetalle->{"a.valor"} == $item->Evento) {
                                                $cumplecondicionproducto = true;
                                            }
                                        } else {

                                            if ($ruletaDetalle->{"a.valor"} == $item->Evento && $cumplecondicionproducto) {
                                                $cumplecondicionproducto = true;

                                            }
                                        }

                                    }

                                }

                                $condicionesproducto++;

                                break;
                            case "ITAINMENT5":


                                foreach ($detalleSelecciones as $item) {
                                    if ($tipocomparacion == "OR") {
                                        if ($ruletaDetalle->{"a.valor"} == $item->DeporteMercado) {
                                            $cumplecondicionproducto = true;

                                        }
                                    } elseif ($tipocomparacion == "AND") {
                                        if ($ruletaDetalle->{"a.valor"} != $item->DeporteMercado) {
                                            $cumplecondicionproducto = false;

                                        }

                                        if ($condicionesproducto == 0) {
                                            if ($ruletaDetalle->{"a.valor"} == $item->DeporteMercado) {
                                                $cumplecondicionproducto = true;
                                            }
                                        } else {
                                            if ($ruletaDetalle->{"a.valor"} == $item->DeporteMercado && $cumplecondicionproducto) {
                                                $cumplecondicionproducto = true;

                                            }
                                        }

                                    }

                                }

                                $condicionesproducto++;

                                break;

                            case "ITAINMENT82":

                                if ($ruletaDetalle->{"a.valor"} == 1) {
                                    $sePuedeSimples = 1;

                                }
                                if ($ruletaDetalle->{"a.valor"} == 2) {
                                    $sePuedeCombinadas = 1;

                                }
                                break;

                            default:
                                if (stristr($ruletaDetalle->{"a.tipo"}, 'CONDGAME')) {

                                    $idGame = explode("CONDGAME", $ruletaDetalle->{"a.tipo"})[1];

                                    foreach ($detalleJuegosCasino as $item) {
                                        if ($idGame == $item->Id) {
                                            $cumplecondicionproducto = true;

                                            $valorASumar = $valorASumar + (($detalleValorApuesta * $ruletaDetalle->{"a.valor"}) / 100);

                                        }

                                    }

                                    $condicionesproducto++;
                                }

                                break;
                        }


                    }

                    if ($sePuedeCombinadas != 0 || $sePuedeSimples != 0) {

                        if (oldCount($detalleSelecciones) == 1 && !$sePuedeSimples) {
                            $cumplecondicion = false;
                        }

                        if (oldCount($detalleSelecciones) > 1 && !$sePuedeCombinadas) {
                            $cumplecondicion = false;
                        }

                        if ($sePuedeCombinadas) {
                            if (oldCount($detalleSelecciones) > 1 && oldCount($detalleSelecciones) < $minselcount) {
                                $cumplecondicion = false;

                            }
                        }
                    } else {
                        if (oldCount($detalleSelecciones) > 1 && oldCount($detalleSelecciones) < $minselcount) {
                            $cumplecondicion = false;

                        }
                    }

                    if ($cumplecondicion && ($cumplecondicionproducto || $condicionesproducto == 0)) {

                        $ruletaid = $ruleta->{"a.ruleta_id"};
                        $usuruleta_id = $ruleta->{"usuruleta_id"};
                        $valorapostado = $ruleta->{"apostado"};
                        $valorrequerido = $ruleta->{"rollower_requerido"};

                    }
                }

            }

            if ($ruletaid != 0) {


                if ($tipoProducto == 2) {
                    $valorASumar = $detalleValorApuesta;

                }


                if (($valorapostado + $detalleValorApuesta) >= $valorrequerido) {
                    $winBonus = true;
                }

                $strSql = array();
                $contSql = 0;

                $contSql = $contSql + 1;
                $strSql[$contSql] = "UPDATE usuario_ruleta SET apostado = apostado + " . ($valorASumar) . " WHERE usuruleta_id =" . $usuruleta_id;

                $contSql = $contSql + 1;
                $strSql[$contSql] = "INSERT INTO it_ticket_enc_info1 (ticket_id,tipo,valor)  VALUES ( " . $ticketId . ",'ROLLOWER'," . $usuruleta_id . ") ";


                if ($ganaRuletaId == 0) {
                    switch ($tiposaldo) {
                        case 0:
                            $contSql = $contSql + 1;
                            $strSql[$contSql] = "UPDATE usuario_ruleta,registro SET usuario_ruleta.estado = 'R',registro.creditos_base_ant=registro.creditos_base,registro.creditos_base=registro.creditos_base + usuario_ruleta.valor,registro.creditos_ruleta=registro.creditos_ruleta - usuario_ruleta.valor   WHERE  registro.usuario_id= usuario_ruleta.usuario_id AND usuario_ruleta.apostado >= usuario_ruleta.rollower_requerido AND usuario_ruleta.usuruleta_id = " . $usuruleta_id . " AND usuario_ruleta.estado='A'";
                            $contSql = $contSql + 1;
                            $strSql[$contSql] = "INSERT INTO ruleta_log (usuario_id,tipo,valor,estado,id_externo,mandante,transaccion_id,tiporuleta_id,fecha_crea,fecha_cierre) SELECT a.usuario_id,CASE WHEN b.tipo = 2 THEN 'D' ELSE 'F' END,a.valor,'L',a.usuruleta_id,0,'0',4,now(),now()  FROM  usuario_ruleta a INNER JOIN ruleta_interno  b ON (b.ruleta_id = a.ruleta_id)  WHERE a.usuruleta_id = " . $usuruleta_id . " AND a.apostado >= a.rollower_requerido AND a.estado='R'";

                            break;

                        case 1:
                            $contSql = $contSql + 1;
                            $strSql[$contSql] = "UPDATE usuario_ruleta,registro SET usuario_ruleta.estado = 'R',registro.creditos_ant=registro.creditos,registro.creditos=registro.creditos + usuario_ruleta.valor,registro.creditos_ruleta=registro.creditos_ruleta - usuario_ruleta.valor   WHERE  registro.usuario_id= usuario_ruleta.usuario_id AND usuario_ruleta.apostado >= usuario_ruleta.rollower_requerido AND usuario_ruleta.usuruleta_id = " . $usuruleta_id . " AND usuario_ruleta.estado='A'";
                            $estadoRuleta = 'R';
                            $contSql = $contSql + 1;
                            $strSql[$contSql] = "INSERT INTO ruleta_log (usuario_id,tipo,valor,estado,id_externo,mandante,transaccion_id,tiporuleta_id,fecha_crea,fecha_cierre) SELECT a.usuario_id,CASE WHEN b.tipo = 2 THEN 'D' ELSE 'F' END,a.valor,'L',a.usuruleta_id,0,'0',4,now(),now()  FROM  usuario_ruleta a  INNER JOIN ruleta_interno  b ON (b.ruleta_id = a.ruleta_id)  WHERE a.usuruleta_id = " . $usuruleta_id . " AND a.apostado >= a.rollower_requerido AND a.estado='R'";

                            break;

                        case 2:

                            $contSql = $contSql + 1;
                            $strSql[$contSql] = "UPDATE usuario_ruleta,registro SET usuario_ruleta.estado = 'R',registro.saldo_especial=registro.saldo_especial + usuario_ruleta.valor,registro.creditos_ruleta=registro.creditos_ruleta - usuario_ruleta.valor   WHERE  registro.usuario_id= usuario_ruleta.usuario_id AND usuario_ruleta.apostado >= usuario_ruleta.rollower_requerido AND usuario_ruleta.usuruleta_id = " . $usuruleta_id . " AND usuario_ruleta.estado='A'";
                            $estadoRuleta = 'R';
                            $contSql = $contSql + 1;
                            $strSql[$contSql] = "INSERT INTO ruleta_log (usuario_id,tipo,valor,estado,id_externo,mandante,transaccion_id,tiporuleta_id,fecha_crea,fecha_cierre) SELECT a.usuario_id,CASE WHEN b.tipo = 2 THEN 'D' ELSE 'F' END,a.valor,'L',a.usuruleta_id,0,'0',4,now(),now()  FROM  usuario_ruleta a  INNER JOIN ruleta_interno  b ON (b.ruleta_id = a.ruleta_id)  WHERE a.usuruleta_id = " . $usuruleta_id . " AND a.apostado >= a.rollower_requerido AND a.estado='R'";

                            break;

                    }


                }


                $respuesta["WinBonus"] = true;
                $respuesta["Ruleta"] = $ruletaid;
                $respuesta["UsuarioRuleta"] = $usuruleta_id;
                $respuesta["queries"] = $strSql;

                foreach ($respuesta["queries"] as $querie) {
                    $this->execQuery($querie);
                }

                if ($ganaRuletaId != 0) {
                    $sqlRuleta2 = "select a.usuario_id,a.usuruleta_id,a.ruleta_id,a.apostado,a.rollower_requerido,a.fecha_crea,ruleta_interno.condicional,ruleta_interno.tipo from usuario_ruleta a INNER JOIN ruleta_interno ON (ruleta_interno.ruleta_id = a.ruleta_id ) where  a.estado='A' AND (ruleta_interno.tipo = 2 OR ruleta_interno.tipo = 3) AND a.usuruleta_id='" . $usuruleta_id . "'";

                    $ruletasDisponibles2 = $this->execQuery($sqlRuleta2);

                    $rollower_requerido = $ruletasDisponibles2[0]->rollower_requerido;
                    $apostado = $ruletasDisponibles2[0]->apostado;

                    if ($apostado >= $rollower_requerido) {
                        try {
                            $usuarioSql = "select ciudad.ciudad_id,ciudad.depto_id,pais.pais_id FROM registro
  INNER JOIN ciudad ON (ciudad.ciudad_id = registro.ciudad_id)
  INNER JOIN departamento ON (ciudad.depto_id = departamento.depto_id)
  INNER JOIN pais ON (pais.pais_id = departamento.pais_id) WHERE registro.usuario_id='" . $ruletasDisponibles2[0]->usuario_id . "'";

                            $Usuario = $this->execQuery($usuarioSql);

                            $dataUsuario = $Usuario;
                            $detalles = array(
                                "PaisUSER" => $dataUsuario['pais_id'],
                                "DepartamentoUSER" => $dataUsuario['depto_id'],
                                "CiudadUSER" => $dataUsuario['ciudad_id'],

                            );
                            $detalles = json_decode(json_encode($detalles));

                            $respuesta2 = $this->agregarRuletaFree($ganaRuletaId, $ruletasDisponibles2[0]->usuario_id, "0", $detalles, true);

                            $contSql = 1;
                            $strSql = array();
                            $strSql[$contSql] = "UPDATE usuario_ruleta SET usuario_ruleta.estado = 'R'   WHERE usuario_ruleta.usuruleta_id = " . $usuruleta_id . " AND usuario_ruleta.estado='A'";

                            //  $contSql = $contSql +1;
                            //   $strSql[$contSql] = "INSERT INTO ruleta_log (usuario_id,tipo,valor,estado,id_externo,mandante,transaccion_id,tiporuleta_id) SELECT a.usuario_id,'D',a.valor,'L',a.usuruleta_id,0,'0',4  FROM  usuario_ruleta a   WHERE a.usuruleta_id = " . $usuruleta_id . " AND a.apostado >= a.rollower_requerido";
                        } catch (Exception $e) {

                        }


                        $respuesta["WinBonus"] = true;
                        $respuesta["Ruleta"] = $ruletaid;
                        $respuesta["UsuarioRuleta"] = $usuruleta_id;
                        $respuesta["queries"] = $strSql;

                        foreach ($respuesta["queries"] as $querie) {
                            $this->execQuery($querie);
                        }
                    }

                }

            }

        }


        return json_decode(json_encode($respuesta));

    }

    /**
     * Verficiar rollower en el ruleta
     *
     *
     * @param String usuarioId id del usuario
     * @param String detalles detalles
     * @param String tipoProducto tipoProducto
     * @param String ticketId id del tiquete
     *
     * @return Array $result resultado de la verificación
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function verificarRuletaUsuarioPremio($usuarioId, $detalles, $tipoProducto, $ticketId)
    {

        switch ($tipoProducto) {


            case "CASINO":

                $TransaccionApi = new TransaccionApi($ticketId);
                //$UsuarioMandante = new UsuarioMandante($TransaccionApi->usuarioId);


                $rules = [];

                array_push($rules, array("field" => "transaccion_api.identificador", "data" => "$TransaccionApi->identificador", "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                $json = json_encode($filtro);


                $TransjuegoInfo = new TransjuegoInfo("", "", "", "", "", $TransaccionApi->identificador);


                if ($TransjuegoInfo->transjuegoinfoId > 0) {

                    $UsuarioRuleta = new UsuarioRuleta($TransjuegoInfo->descripcion);

                    $UsuarioRuleta->setValorPremio('valor_premio + ' . $TransaccionApi->getValor());

                    $UsuarioRuletaMySqlDAO = new UsuarioRuletaMySqlDAO();
                    $UsuarioRuletaMySqlDAO->update($UsuarioRuleta);
                    $UsuarioRuletaMySqlDAO->getTransaction()->commit();
                }


                break;


            case "LIVECASINO":

                $TransaccionApi = new TransaccionApi($ticketId);
                //$UsuarioMandante = new UsuarioMandante($TransaccionApi->usuarioId);


                $rules = [];

                array_push($rules, array("field" => "transaccion_api.identificador", "data" => "$TransaccionApi->identificador", "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                $json = json_encode($filtro);


                $TransjuegoInfo = new TransjuegoInfo("", "", "", "", "", $TransaccionApi->identificador);


                if ($TransjuegoInfo->transjuegoinfoId > 0) {

                    $UsuarioRuleta = new UsuarioRuleta($TransjuegoInfo->descripcion);

                    $UsuarioRuleta->setValorPremio('valor_premio + ' . $TransaccionApi->getValor());

                    $UsuarioRuletaMySqlDAO = new UsuarioRuletaMySqlDAO();
                    $UsuarioRuletaMySqlDAO->update($UsuarioRuleta);
                    $UsuarioRuletaMySqlDAO->getTransaction()->commit();
                }


                break;


            case "VIRTUAL":

                $TransaccionApi = new TransaccionApi($ticketId);
                //$UsuarioMandante = new UsuarioMandante($TransaccionApi->usuarioId);


                $rules = [];

                array_push($rules, array("field" => "transaccion_api.identificador", "data" => "$TransaccionApi->identificador", "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                $json = json_encode($filtro);


                $TransjuegoInfo = new TransjuegoInfo("", "", "", "", "", $TransaccionApi->identificador);


                if ($TransjuegoInfo->transjuegoinfoId > 0) {

                    $UsuarioRuleta = new UsuarioRuleta($TransjuegoInfo->descripcion);

                    $UsuarioRuleta->setValorPremio('valor_premio + ' . $TransaccionApi->getValor());

                    $UsuarioRuletaMySqlDAO = new UsuarioRuletaMySqlDAO();
                    $UsuarioRuletaMySqlDAO->update($UsuarioRuleta);
                    $UsuarioRuletaMySqlDAO->getTransaction()->commit();
                }


                break;


        }
    }

    /**
     * Verficiar ruleta
     *
     *
     * @param String usuarioId id del usuario
     * @param String detalles detalles
     * @param String tipoProducto tipoProducto
     * @param String ticketId id del tiquete
     *
     * @return Array $result resultado de la verificación
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function verificarRuletaUsuario($usuarioId, $detalles, $tipoProducto, $ticketId)
    {

        switch ($tipoProducto) {


            case "CASINO":

                $TransaccionApi = new TransaccionApi($ticketId);
                $UsuarioMandante = new UsuarioMandante($TransaccionApi->usuarioId);
                $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

                $cumpleCondicion = false;
                $ruletasAnalizados = '';


                if (false) {
                    $rules = [];

                    array_push($rules, array("field" => "usuario_ruleta.usuario_id", "data" => "$TransaccionApi->usuarioId", "op" => "eq"));
                    array_push($rules, array("field" => "ruleta_interno.estado", "data" => "A", "op" => "eq"));
                    array_push($rules, array("field" => "ruleta_interno.fecha_inicio", "data" => "$TransaccionApi->fechaCrea", "op" => "le"));
                    array_push($rules, array("field" => "ruleta_interno.fecha_fin", "data" => "$TransaccionApi->fechaCrea", "op" => "ge"));
                    array_push($rules, array("field" => "ruleta_interno.tipo", "data" => "2", "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $json = json_encode($filtro);


                    $UsuarioRuleta = new UsuarioRuleta();
                    $data = $UsuarioRuleta->getUsuarioRuletasCustomWithoutPosition("usuario_ruleta.*,usuario_mandante.nombres,ruleta_interno.*", "usuario_ruleta.valor", "DESC", 0, 1, $json, true, '');

                    //print_r($data);

                    $data = json_decode($data);

                    $final = [];

                    $pos = 1;

                    $cumpleCondicion = false;
                    foreach ($data->data as $key => $value) {

                        $array = [];
                        $array["Position"] = $pos;
                        $array["Id"] = $value->{"usuario_ruleta.usuruleta_id"};

                        $ruletasAnalizados = $ruletasAnalizados . $value->{"usuario_ruleta.ruleta_id"} . ",";
                        $RuletaInterno = new RuletaInterno();
                        $RuletaDetalle = new RuletaDetalle();

                        //$bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
                        //$bonos = json_decode($bonos);


                        $rules = [];

                        array_push($rules, array("field" => "ruleta_interno.ruleta_id", "data" => $value->{"usuario_ruleta.ruleta_id"}, "op" => "eq"));
                        array_push($rules, array("field" => "ruleta_interno.estado", "data" => "A", "op" => "eq"));

                        $filtro = array("rules" => $rules, "groupOp" => "AND");

                        $json = json_encode($filtro);

                        $ruletadetalles = $RuletaDetalle->getRuletaDetallesCustom(" ruleta_detalle.*,ruleta_interno.* ", "ruleta_interno.ruleta_id", "asc", 0, 1000, $json, TRUE);

                        $ruletadetalles = json_decode($ruletadetalles);

                        $final = [];

                        $creditosConvert = 0;

                        $cumpleCondicionPais = false;
                        $cumpleCondicionCont = 0;

                        $condicionesProducto = 0;

                        foreach ($ruletadetalles->data as $key2 => $value2) {

                            switch ($value2->{"ruleta_detalle.tipo"}) {
                                case "RANK":
                                    if ($value2->{"ruleta_detalle.moneda"} == $UsuarioMandante->moneda) {
                                        if ($TransaccionApi->valor >= $value2->{"ruleta_detalle.valor"}) {
                                            if ($TransaccionApi->valor <= $value2->{"ruleta_detalle.valor2"}) {
                                                $creditosConvert = $value2->{"ruleta_detalle.valor3"};
                                            }

                                        }
                                    }

                                    break;


                                case "CONDPAISUSER":

                                    if ($value2->{"ruleta_detalle.valor"} == $Usuario->paisId) {
                                        $cumpleCondicionPais = true;
                                    }
                                    $cumpleCondicionCont++;

                                    break;

                                default:

                                    if (stristr($value2->{"ruleta_detalle.tipo"}, 'CONDGAME')) {

                                        $idGame = explode("CONDGAME", $value2->{"ruleta_detalle.tipo"})[1];

                                        if ($TransaccionApi->productoId == $idGame) {
                                            $cumpleCondicion = true;
                                        }
                                        $condicionesProducto++;
                                    }

                                    if (stristr($value2->{"ruleta_detalle.tipo"}, 'CONDPROVIDER')) {

                                        $idProvider = explode("CONDPROVIDER", $value2->{"ruleta_detalle.tipo"})[1];

                                        if ($TransaccionApi->proveedorId == $idProvider) {
                                            $cumpleCondicion = true;
                                        }
                                        $condicionesProducto++;
                                    }

                                    break;
                            }

                        }

                        if ($condicionesProducto == 0 && !$cumpleCondicion) {
                            $cumpleCondicion = true;
                        }

                        if (!$cumpleCondicionPais && $cumpleCondicionCont > 0) {
                            $cumpleCondicion = false;
                        }

                        if ($cumpleCondicion) {
                            if ($creditosConvert == 0) {
                                $cumpleCondicion = false;
                            }
                        }
                        if ($cumpleCondicion) {
                            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();

                            $UsuarioRuleta = new UsuarioRuleta($value->{"usuario_ruleta.usuruleta_id"});
                            $UsuarioRuleta->valor = $UsuarioRuleta->valor + $creditosConvert;
                            $UsuarioRuleta->valorBase = ($UsuarioRuleta->valorBase + $TransaccionApi->valor);
                            //print_r($TransaccionApi);

                            //print_r($UsuarioRuleta);
                            $TransaccionJuego = new TransaccionJuego("", $TransaccionApi->getIdentificador());

                            $UsuarioRuletaMySqlDAO = new UsuarioRuletaMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                            $UsuarioRuletaMySqlDAO->update($UsuarioRuleta);

                            $TransjuegoInfo = new TransjuegoInfo();
                            $TransjuegoInfo->productoId = $TransaccionApi->productoId;
                            $TransjuegoInfo->transaccionId = $TransaccionApi->transaccionId;
                            $TransjuegoInfo->tipo = "RULETA";
                            $TransjuegoInfo->descripcion = $value->{"usuario_ruleta.usuruleta_id"};
                            $TransjuegoInfo->valor = $creditosConvert;
                            $TransjuegoInfo->transapiId = $TransaccionApi->transapiId;
                            $TransjuegoInfo->usucreaId = 0;
                            $TransjuegoInfo->usumodifId = 0;
                            $TransjuegoInfo->identificador = $TransaccionApi->identificador;
                            $TransjuegoInfo->transjuegoId = $TransaccionJuego->transjuegoId;

                            $title = '';
                            $messageBody = '';
                            $rouletteName = $value->{"ruleta_interno.nombre"};

                            switch (strtolower($Usuario->idioma)) {
                                case 'es':
                                    $title = 'Notificacion';
                                    $messageBody = "¡ Bien :thumbsup: ! Estas participando en el ruleta {$rouletteName} :clap:";
                                    break;
                                case 'en':
                                    $title = 'Notification';
                                    $messageBody = "¡ Great :thumbsup: ! You are participating in the roulette {$rouletteName} :clap:";
                                    break;
                                case 'pt':
                                    $title = 'Notificação';
                                    $messageBody = "Eaí! :thumbsup: Você está participando da roleta {$rouletteName} :clap:";
                                    break;
                            }

                            $UsuarioMensaje = new UsuarioMensaje();
                            $UsuarioMensaje->usufromId = 0;
                            $UsuarioMensaje->usutoId = $UsuarioRuleta->getUsuarioId();
                            $UsuarioMensaje->isRead = 0;
                            $UsuarioMensaje->body = $messageBody;
                            $UsuarioMensaje->msubject = $title;
                            $UsuarioMensaje->parentId = 0;
                            $UsuarioMensaje->proveedorId = 0;
                            $UsuarioMensaje->tipo = "PUSHNOTIFICACION";
                            $UsuarioMensaje->paisId = 0;
                            $UsuarioMensaje->fechaExpiracion = '';

                            $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                            $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);

                            $TransjuegoInfoMySqlDAO->insert($TransjuegoInfo);
                            $TransjuegoInfoMySqlDAO->getTransaction()->commit();


                            $mensajesRecibidos = [];
                            $array = [];

                            $array["body"] = $title;

                            array_push($mensajesRecibidos, $array);
                            $data = array();
                            $data["messages"] = $mensajesRecibidos;
                            //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

                            $UsuarioSession = new UsuarioSession();
                            $rules = [];

                            array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                            array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

                            $filtro = array("rules" => $rules, "groupOp" => "AND");
                            $json = json_encode($filtro);


                            $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

                            $usuarios = json_decode($usuarios);

                            $usuariosFinal = [];

                            foreach ($usuarios->data as $key => $value) {

                                $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data);
                                $WebsocketUsuario->sendWSMessage();

                            }

                            if (in_array($UsuarioMandante->mandante, array('0', 8, 6))) {

                                $dataSend = $data;
                                $WebsocketUsuario = new WebsocketUsuario('', '');
                                $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend);

                            }

                            break;
                        }


                    }
                }
                if (!$cumpleCondicion) {

                    if ($ruletasAnalizados != "") {
                        $ruletasAnalizados = $ruletasAnalizados . '0';
                    }

                    $RuletaInterno = new RuletaInterno();

                    $rules = [];

                    if ($ruletasAnalizados != '') {
                        array_push($rules, array("field" => "ruleta_interno.ruleta_id", "data" => "$ruletasAnalizados", "op" => "ni"));
                    }

                    array_push($rules, array("field" => "ruleta_interno.estado", "data" => "A", "op" => "eq"));
                    array_push($rules, array("field" => "ruleta_interno.fecha_inicio", "data" => "$TransaccionApi->fechaCrea", "op" => "le"));
                    array_push($rules, array("field" => "ruleta_interno.fecha_fin", "data" => "$TransaccionApi->fechaCrea", "op" => "ge"));
                    array_push($rules, array("field" => "ruleta_interno.tipo", "data" => "2", "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $json = json_encode($filtro);


                    $data = $RuletaInterno->getRuletasCustom("ruleta_interno.*", "ruleta_interno.orden", "ASC", 0, 1, $json, true, '');


                    $data = json_decode($data);

                    $final = [];

                    $pos = 1;
                    $ruletasAnalizados = '';

                    foreach ($data->data as $key => $value) {


                        $RuletaDetalle = new RuletaDetalle();

                        //$bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
                        //$bonos = json_decode($bonos);


                        $rules = [];

                        array_push($rules, array("field" => "ruleta_interno.ruleta_id", "data" => $value->{"ruleta_interno.ruleta_id"}, "op" => "eq"));
                        array_push($rules, array("field" => "ruleta_interno.estado", "data" => "A", "op" => "eq"));

                        $filtro = array("rules" => $rules, "groupOp" => "AND");

                        $json = json_encode($filtro);

                        $ruletadetalles = $RuletaDetalle->getRuletaDetallesCustom(" ruleta_detalle.*,ruleta_interno.* ", "ruleta_interno.ruleta_id", "asc", 0, 1000, $json, TRUE);

                        $ruletadetalles = json_decode($ruletadetalles);

                        $final = [];

                        $creditosConvert = 0;

                        $cumpleCondicion = false;
                        $needSubscribe = false;

                        $cumpleCondicionPais = false;
                        $cumpleCondicionCont = 0;

                        $condicionesProducto = 0;
                        $puederepetirBono = false;

                        $minBetPrice = 0;

                        foreach ($ruletadetalles->data as $key2 => $value2) {

                            switch ($value2->{"ruleta_detalle.tipo"}) {
                                case "RANK":
                                    if ($value2->{"ruleta_detalle.moneda"} == $UsuarioMandante->moneda) {
                                        if ($TransaccionApi->valor >= $value2->{"ruleta_detalle.valor"}) {
                                            if ($TransaccionApi->valor <= $value2->{"ruleta_detalle.valor2"}) {
                                                $creditosConvert = $value2->{"ruleta_detalle.valor3"};
                                            }

                                        }
                                    }

                                    break;

                                case "USERSUBSCRIBE":


                                    break;

                                case "MINBETPRICE":

                                    if ($value2->{"ruleta_detalle.moneda"} == $UsuarioMandante->moneda) {

                                        $minBetPrice = floatval($value2->{"ruleta_detalle.valor"});
                                    }


                                    break;

                                case "VISIBILIDAD":

                                    if ($value2->{"ruleta_detalle.valor"} == 1) {
                                        $needSubscribe = true;

                                    } else {
                                    }

                                    break;


                                case "CONDPAISUSER":

                                    if ($value2->{"ruleta_detalle.valor"} == $Usuario->paisId) {
                                        $cumpleCondicionPais = true;
                                    }
                                    $cumpleCondicionCont++;

                                    break;

                                case "REPETIRSORTEO":

                                    if ($value2->{"ruleta_detalle.valor"} == '1') {

                                        $puederepetirBono = true;
                                    }

                                    break;

                                default:

                                    if (stristr($value2->{"ruleta_detalle.tipo"}, 'CONDGAME')) {

                                        $idGame = explode("CONDGAME", $value2->{"ruleta_detalle.tipo"})[1];

                                        if ($TransaccionApi->productoId == $idGame) {

                                            $cumpleCondicion = true;
                                        }
                                        $condicionesProducto++;
                                    }

                                    if (stristr($value2->{"ruleta_detalle.tipo"}, 'CONDPROVIDER')) {

                                        $idProvider = explode("CONDPROVIDER", $value2->{"ruleta_detalle.tipo"})[1];

                                        if ($TransaccionApi->proveedorId == $idProvider) {
                                            $cumpleCondicion = true;
                                        }
                                        $condicionesProducto++;
                                    }

                                    break;
                            }

                        }

                        if ($condicionesProducto == 0 && !$cumpleCondicion) {
                            $cumpleCondicion = true;
                        }

                        if (!$cumpleCondicionPais && $cumpleCondicionCont > 0) {

                            $cumpleCondicion = false;
                        }

                        if ($minBetPrice > floatval($TransaccionApi->getValor())) {
                            $cumpleCondicion = false;

                        }


                        if ($cumpleCondicion) {

                            if ($puederepetirBono) {


                            } else {

                                $sqlRepiteRuleta = "select * from usuario_ruleta a where a.ruleta_id='" . $value->{"ruleta_interno.ruleta_id"} . "' AND a.usuario_id = '" . $UsuarioMandante->usumandanteId . "'";
                                $repiteRuleta = $this->execQuery('', $sqlRepiteRuleta);

                                if ((!$puederepetirBono && oldCount($repiteRuleta) == 0)) {

                                } else {
                                    $cumpleCondicion = false;
                                }

                            }
                        }

                        if ($cumpleCondicion && !$needSubscribe) {
                            //print_r("CUMPLECONDICION");

                            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();

                            $UsuarioRuleta = new UsuarioRuleta();
                            $UsuarioRuleta->usuarioId = $UsuarioMandante->usumandanteId;
                            $UsuarioRuleta->ruletaId = $value->{"ruleta_interno.ruleta_id"};
                            $UsuarioRuleta->valor = 0;
                            $UsuarioRuleta->posicion = 0;
                            $UsuarioRuleta->valorBase = 0;
                            $UsuarioRuleta->usucreaId = 0;
                            $UsuarioRuleta->usumodifId = 0;
                            $UsuarioRuleta->estado = "A";
                            $UsuarioRuleta->errorId = 0;
                            $UsuarioRuleta->idExterno = 0;
                            $UsuarioRuleta->mandante = 0;
                            $UsuarioRuleta->version = 0;
                            $UsuarioRuleta->apostado = 0;
                            $UsuarioRuleta->codigo = 0;
                            $UsuarioRuleta->externoId = 0;
                            $UsuarioRuleta->valor = $UsuarioRuleta->valor + $creditosConvert;
                            $UsuarioRuleta->valorBase = ($UsuarioRuleta->valorBase + $TransaccionApi->valor);

                            $UsuarioRuletaMySqlDAO = new UsuarioRuletaMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                            $idUsuRuleta = $UsuarioRuletaMySqlDAO->insert($UsuarioRuleta);

                            $TransjuegoInfo = new TransjuegoInfo();
                            $TransjuegoInfo->productoId = $TransaccionApi->productoId;
                            $TransjuegoInfo->transaccionId = $TransaccionApi->transaccionId;
                            $TransjuegoInfo->tipo = "SORTEO";
                            $TransjuegoInfo->descripcion = $idUsuRuleta;
                            $TransjuegoInfo->valor = $creditosConvert;
                            $TransjuegoInfo->transapiId = $TransaccionApi->transapiId;
                            $TransjuegoInfo->usucreaId = 0;
                            $TransjuegoInfo->usumodifId = 0;

                            $title = '';
                            $messageBody = '';
                            $rouletteName = $value->{"ruleta_interno.nombre"};

                            switch (strtolower($Usuario->idioma)) {
                                case 'es':
                                    $title = 'Notificacion';
                                    $messageBody = "¡ Bien :thumbsup: ! Estas participando en el ruleta {$rouletteName} :clap:";
                                    break;
                                case 'en':
                                    $title = 'Notification';
                                    $messageBody = "¡ Great :thumbsup: ! You are participating in the roulette {$rouletteName} :clap:";
                                    break;
                                case 'pt':
                                    $title = 'Notificação';
                                    $messageBody = "Eaí! :thumbsup: Você está participando da roleta {$rouletteName} :clap:";
                                    break;
                            }

                            $UsuarioMensaje = new UsuarioMensaje();
                            $UsuarioMensaje->usufromId = 0;
                            $UsuarioMensaje->usutoId = $UsuarioRuleta->getUsuarioId();
                            $UsuarioMensaje->isRead = 0;
                            $UsuarioMensaje->body = $messageBody;
                            $UsuarioMensaje->msubject = $title;
                            $UsuarioMensaje->parentId = 0;
                            $UsuarioMensaje->proveedorId = 0;
                            $UsuarioMensaje->tipo = "PUSHNOTIFICACION";
                            $UsuarioMensaje->paisId = 0;
                            $UsuarioMensaje->fechaExpiracion = '';

                            $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                            $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);

                            $TransjuegoInfoMySqlDAO->insert($TransjuegoInfo);
                            $TransjuegoInfoMySqlDAO->getTransaction()->commit();


                            $mensajesRecibidos = [];
                            $array = [];

                            $array["body"] = $title;

                            array_push($mensajesRecibidos, $array);
                            $data = array();
                            $data["messages"] = $mensajesRecibidos;
                            //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());
                            $ConfigurationEnvironment = new ConfigurationEnvironment();

                            if (!$ConfigurationEnvironment->isDevelopment()) {

                                if (false) {

                                    $UsuarioSession = new UsuarioSession();
                                    $rules = [];

                                    array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                                    array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

                                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                                    $json = json_encode($filtro);


                                    $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

                                    $usuarios = json_decode($usuarios);

                                    $usuariosFinal = [];

                                    foreach ($usuarios->data as $key => $value) {

                                        $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data);
                                        $WebsocketUsuario->sendWSMessage();

                                    }
                                }

                                if (in_array($UsuarioMandante->mandante, array('0', 8, 6))) {

                                    $dataSend = $data;
                                    $WebsocketUsuario = new WebsocketUsuario('', '');
                                    $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend);

                                }
                            }
                            break;
                        }


                    }


                }


                break;


            case "LIVECASINO":

                $TransaccionApi = new TransaccionApi($ticketId);
                $UsuarioMandante = new UsuarioMandante($TransaccionApi->usuarioId);
                $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

                $cumpleCondicion = false;
                $ruletasAnalizados = '';


                if (false) {
                    $rules = [];

                    array_push($rules, array("field" => "usuario_ruleta.usuario_id", "data" => "$TransaccionApi->usuarioId", "op" => "eq"));
                    array_push($rules, array("field" => "ruleta_interno.estado", "data" => "A", "op" => "eq"));
                    array_push($rules, array("field" => "ruleta_interno.fecha_inicio", "data" => "$TransaccionApi->fechaCrea", "op" => "le"));
                    array_push($rules, array("field" => "ruleta_interno.fecha_fin", "data" => "$TransaccionApi->fechaCrea", "op" => "ge"));
                    array_push($rules, array("field" => "ruleta_interno.tipo", "data" => "3", "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $json = json_encode($filtro);


                    $UsuarioRuleta = new UsuarioRuleta();
                    $data = $UsuarioRuleta->getUsuarioRuletasCustomWithoutPosition("usuario_ruleta.*,usuario_mandante.nombres,ruleta_interno.*", "usuario_ruleta.valor", "DESC", 0, 100, $json, true, '');

                    //print_r($data);

                    $data = json_decode($data);

                    $final = [];

                    $pos = 1;

                    $cumpleCondicion = false;
                    foreach ($data->data as $key => $value) {

                        $array = [];
                        $array["Position"] = $pos;
                        $array["Id"] = $value->{"usuario_ruleta.usuruleta_id"};

                        $ruletasAnalizados = $ruletasAnalizados . $value->{"usuario_ruleta.ruleta_id"} . ",";
                        $RuletaInterno = new RuletaInterno();
                        $RuletaDetalle = new RuletaDetalle();

                        //$bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
                        //$bonos = json_decode($bonos);


                        $rules = [];

                        array_push($rules, array("field" => "ruleta_interno.ruleta_id", "data" => $value->{"usuario_ruleta.ruleta_id"}, "op" => "eq"));
                        array_push($rules, array("field" => "ruleta_interno.estado", "data" => "A", "op" => "eq"));

                        $filtro = array("rules" => $rules, "groupOp" => "AND");

                        $json = json_encode($filtro);

                        $ruletadetalles = $RuletaDetalle->getRuletaDetallesCustom(" ruleta_detalle.*,ruleta_interno.* ", "ruleta_interno.ruleta_id", "asc", 0, 1000, $json, TRUE);

                        $ruletadetalles = json_decode($ruletadetalles);

                        $final = [];

                        $creditosConvert = 0;

                        $cumpleCondicionPais = false;
                        $cumpleCondicionCont = 0;

                        $condicionesProducto = 0;

                        foreach ($ruletadetalles->data as $key2 => $value2) {

                            switch ($value2->{"ruleta_detalle.tipo"}) {
                                case "RANK":
                                    if ($value2->{"ruleta_detalle.moneda"} == $UsuarioMandante->moneda) {
                                        if ($TransaccionApi->valor >= $value2->{"ruleta_detalle.valor"}) {
                                            if ($TransaccionApi->valor <= $value2->{"ruleta_detalle.valor2"}) {
                                                $creditosConvert = $value2->{"ruleta_detalle.valor3"};
                                            }

                                        }
                                    }

                                    break;


                                case "CONDPAISUSER":

                                    if ($value2->{"ruleta_detalle.valor"} == $Usuario->paisId) {
                                        $cumpleCondicionPais = true;
                                    }
                                    $cumpleCondicionCont++;

                                    break;

                                default:

                                    if (stristr($value2->{"ruleta_detalle.tipo"}, 'CONDGAME')) {

                                        $idGame = explode("CONDGAME", $value2->{"ruleta_detalle.tipo"})[1];

                                        if ($TransaccionApi->productoId == $idGame) {
                                            $cumpleCondicion = true;
                                        }
                                        $condicionesProducto++;
                                    }

                                    if (stristr($value2->{"ruleta_detalle.tipo"}, 'CONDPROVIDER')) {

                                        $idProvider = explode("CONDPROVIDER", $value2->{"ruleta_detalle.tipo"})[1];

                                        if ($TransaccionApi->proveedorId == $idProvider) {
                                            $cumpleCondicion = true;
                                        }
                                        $condicionesProducto++;
                                    }

                                    break;
                            }

                        }

                        if ($condicionesProducto == 0 && !$cumpleCondicion) {
                            $cumpleCondicion = true;
                        }

                        if (!$cumpleCondicionPais && $cumpleCondicionCont > 0) {
                            $cumpleCondicion = false;
                        }

                        if ($cumpleCondicion) {
                            if ($creditosConvert == 0) {
                                $cumpleCondicion = false;
                            }
                        }
                        if ($cumpleCondicion) {
                            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();

                            $UsuarioRuleta = new UsuarioRuleta($value->{"usuario_ruleta.usuruleta_id"});
                            $UsuarioRuleta->valor = $UsuarioRuleta->valor + $creditosConvert;
                            $UsuarioRuleta->valorBase = ($UsuarioRuleta->valorBase + $TransaccionApi->valor);
                            //print_r($TransaccionApi);

                            //print_r($UsuarioRuleta);
                            $TransaccionJuego = new TransaccionJuego("", $TransaccionApi->getIdentificador());

                            $UsuarioRuletaMySqlDAO = new UsuarioRuletaMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                            $UsuarioRuletaMySqlDAO->update($UsuarioRuleta);

                            $TransjuegoInfo = new TransjuegoInfo();
                            $TransjuegoInfo->productoId = $TransaccionApi->productoId;
                            $TransjuegoInfo->transaccionId = $TransaccionApi->transaccionId;
                            $TransjuegoInfo->tipo = "SORTEO";
                            $TransjuegoInfo->descripcion = $value->{"usuario_ruleta.usuruleta_id"};
                            $TransjuegoInfo->valor = $creditosConvert;
                            $TransjuegoInfo->transapiId = $TransaccionApi->transapiId;
                            $TransjuegoInfo->usucreaId = 0;
                            $TransjuegoInfo->usumodifId = 0;
                            $TransjuegoInfo->identificador = $TransaccionApi->identificador;
                            $TransjuegoInfo->transjuegoId = $TransaccionJuego->transjuegoId;

                            $title = '';
                            $messageBody = '';
                            $rouletteName = $value->{"ruleta_interno.nombre"};

                            switch (strtolower($Usuario->idioma)) {
                                case 'es':
                                    $title = 'Notificacion';
                                    $messageBody = "¡ Bien :thumbsup: ! Estas participando en el ruleta {$rouletteName} :clap:";
                                    break;
                                case 'en':
                                    $title = 'Notification';
                                    $messageBody = "¡ Great :thumbsup: ! You are participating in the roulette {$rouletteName} :clap:";
                                    break;
                                case 'pt':
                                    $title = 'Notificação';
                                    $messageBody = "Eaí! :thumbsup: Você está participando da roleta {$rouletteName} :clap:";
                                    break;
                            }

                            $UsuarioMensaje = new UsuarioMensaje();
                            $UsuarioMensaje->usufromId = 0;
                            $UsuarioMensaje->usutoId = $UsuarioRuleta->getUsuarioId();
                            $UsuarioMensaje->isRead = 0;
                            $UsuarioMensaje->body = $messageBody;
                            $UsuarioMensaje->msubject = $title;
                            $UsuarioMensaje->parentId = 0;
                            $UsuarioMensaje->proveedorId = 0;
                            $UsuarioMensaje->tipo = "PUSHNOTIFICACION";
                            $UsuarioMensaje->paisId = 0;
                            $UsuarioMensaje->fechaExpiracion = '';

                            $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                            $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);


                            $TransjuegoInfoMySqlDAO->insert($TransjuegoInfo);
                            $TransjuegoInfoMySqlDAO->getTransaction()->commit();


                            $mensajesRecibidos = [];
                            $array = [];

                            $array["body"] = $messageBody;

                            array_push($mensajesRecibidos, $array);
                            $data = array();
                            $data["messages"] = $mensajesRecibidos;
                            //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

                            $UsuarioSession = new UsuarioSession();
                            $rules = [];

                            array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                            array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

                            $filtro = array("rules" => $rules, "groupOp" => "AND");
                            $json = json_encode($filtro);


                            $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

                            $usuarios = json_decode($usuarios);

                            $usuariosFinal = [];

                            foreach ($usuarios->data as $key => $value) {

                                $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data);
                                $WebsocketUsuario->sendWSMessage();

                            }

                            if (in_array($UsuarioMandante->mandante, array('0', 8, 6))) {

                                $dataSend = $data;
                                $WebsocketUsuario = new WebsocketUsuario('', '');
                                $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend);

                            }

                            break;
                        }


                    }
                }
                if (!$cumpleCondicion) {

                    if ($ruletasAnalizados != "") {
                        $ruletasAnalizados = $ruletasAnalizados . '0';
                    }

                    $RuletaInterno = new RuletaInterno();

                    $rules = [];

                    if ($ruletasAnalizados != '') {
                        array_push($rules, array("field" => "ruleta_interno.ruleta_id", "data" => "$ruletasAnalizados", "op" => "ni"));
                    }

                    array_push($rules, array("field" => "ruleta_interno.estado", "data" => "A", "op" => "eq"));
                    array_push($rules, array("field" => "ruleta_interno.fecha_inicio", "data" => "$TransaccionApi->fechaCrea", "op" => "le"));
                    array_push($rules, array("field" => "ruleta_interno.fecha_fin", "data" => "$TransaccionApi->fechaCrea", "op" => "ge"));
                    array_push($rules, array("field" => "ruleta_interno.tipo", "data" => "3", "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $json = json_encode($filtro);


                    $data = $RuletaInterno->getRuletasCustom("ruleta_interno.*", "ruleta_interno.orden", "ASC", 0, 1000, $json, true, '');


                    $data = json_decode($data);

                    $final = [];

                    $pos = 1;
                    $ruletasAnalizados = '';

                    foreach ($data->data as $key => $value) {


                        $RuletaDetalle = new RuletaDetalle();

                        //$bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
                        //$bonos = json_decode($bonos);


                        $rules = [];

                        array_push($rules, array("field" => "ruleta_interno.ruleta_id", "data" => $value->{"ruleta_interno.ruleta_id"}, "op" => "eq"));
                        array_push($rules, array("field" => "ruleta_interno.estado", "data" => "A", "op" => "eq"));

                        $filtro = array("rules" => $rules, "groupOp" => "AND");

                        $json = json_encode($filtro);

                        $ruletadetalles = $RuletaDetalle->getRuletaDetallesCustom(" ruleta_detalle.*,ruleta_interno.* ", "ruleta_interno.ruleta_id", "asc", 0, 1000, $json, TRUE);

                        $ruletadetalles = json_decode($ruletadetalles);

                        $final = [];

                        $creditosConvert = 0;

                        $cumpleCondicion = false;
                        $needSubscribe = false;

                        $cumpleCondicionPais = false;
                        $cumpleCondicionCont = 0;

                        $condicionesProducto = 0;
                        $puederepetirBono = false;

                        $minBetPrice = 0;

                        foreach ($ruletadetalles->data as $key2 => $value2) {

                            switch ($value2->{"ruleta_detalle.tipo"}) {
                                case "RANK":
                                    if ($value2->{"ruleta_detalle.moneda"} == $UsuarioMandante->moneda) {
                                        if ($TransaccionApi->valor >= $value2->{"ruleta_detalle.valor"}) {
                                            if ($TransaccionApi->valor <= $value2->{"ruleta_detalle.valor2"}) {
                                                $creditosConvert = $value2->{"ruleta_detalle.valor3"};
                                            }

                                        }
                                    }

                                    break;

                                case "USERSUBSCRIBE":


                                    break;

                                case "MINBETPRICE":

                                    if ($value2->{"ruleta_detalle.moneda"} == $UsuarioMandante->moneda) {

                                        $minBetPrice = floatval($value2->{"ruleta_detalle.valor"});
                                    }


                                    break;

                                case "VISIBILIDAD":

                                    if ($value2->{"ruleta_detalle.valor"} == 1) {
                                        $needSubscribe = true;

                                    } else {
                                    }

                                    break;


                                case "CONDPAISUSER":

                                    if ($value2->{"ruleta_detalle.valor"} == $Usuario->paisId) {
                                        $cumpleCondicionPais = true;
                                    }
                                    $cumpleCondicionCont++;

                                    break;

                                case "REPETIRSORTEO":
                                    //print_r('emtrl');
                                    if ($value2->{"ruleta_detalle.valor"} == '1') {
                                        //print_r('emtrl2');
                                        $puederepetirBono = true;
                                    }

                                    break;

                                default:

                                    if (stristr($value2->{"ruleta_detalle.tipo"}, 'CONDGAME')) {

                                        $idGame = explode("CONDGAME", $value2->{"ruleta_detalle.tipo"})[1];

                                        if ($TransaccionApi->productoId == $idGame) {

                                            $cumpleCondicion = true;
                                        }
                                        $condicionesProducto++;
                                    }

                                    if (stristr($value2->{"ruleta_detalle.tipo"}, 'CONDPROVIDER')) {

                                        $idProvider = explode("CONDPROVIDER", $value2->{"ruleta_detalle.tipo"})[1];

                                        if ($TransaccionApi->proveedorId == $idProvider) {
                                            $cumpleCondicion = true;
                                        }
                                        $condicionesProducto++;
                                    }

                                    break;
                            }

                        }

                        if ($condicionesProducto == 0 && !$cumpleCondicion) {
                            $cumpleCondicion = true;
                        }

                        if (!$cumpleCondicionPais && $cumpleCondicionCont > 0) {

                            $cumpleCondicion = false;
                        }

                        if ($minBetPrice > floatval($TransaccionApi->getValor())) {
                            $cumpleCondicion = false;

                        }


                        if ($cumpleCondicion) {

                            if ($puederepetirBono) {


                            } else {

                                $sqlRepiteRuleta = "select * from usuario_ruleta a where a.ruleta_id='" . $value->{"ruleta_interno.ruleta_id"} . "' AND a.usuario_id = '" . $UsuarioMandante->usumandanteId . "'";
                                $repiteRuleta = $this->execQuery('', $sqlRepiteRuleta);

                                if ((!$puederepetirBono && oldCount($repiteRuleta) == 0)) {

                                } else {
                                    $cumpleCondicion = false;
                                }

                            }
                        }

                        if ($cumpleCondicion && !$needSubscribe) {
                            //print_r("CUMPLECONDICION");

                            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();

                            $UsuarioRuleta = new UsuarioRuleta();
                            $UsuarioRuleta->usuarioId = $UsuarioMandante->usumandanteId;
                            $UsuarioRuleta->ruletaId = $value->{"ruleta_interno.ruleta_id"};
                            $UsuarioRuleta->valor = 0;
                            $UsuarioRuleta->posicion = 0;
                            $UsuarioRuleta->valorBase = 0;
                            $UsuarioRuleta->usucreaId = 0;
                            $UsuarioRuleta->usumodifId = 0;
                            $UsuarioRuleta->estado = "A";
                            $UsuarioRuleta->errorId = 0;
                            $UsuarioRuleta->idExterno = 0;
                            $UsuarioRuleta->mandante = 0;
                            $UsuarioRuleta->version = 0;
                            $UsuarioRuleta->apostado = 0;
                            $UsuarioRuleta->codigo = 0;
                            $UsuarioRuleta->externoId = 0;
                            $UsuarioRuleta->valor = $UsuarioRuleta->valor + $creditosConvert;
                            $UsuarioRuleta->valorBase = ($UsuarioRuleta->valorBase + $TransaccionApi->valor);

                            $UsuarioRuletaMySqlDAO = new UsuarioRuletaMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                            $idUsuRuleta = $UsuarioRuletaMySqlDAO->insert($UsuarioRuleta);

                            $TransjuegoInfo = new TransjuegoInfo();
                            $TransjuegoInfo->productoId = $TransaccionApi->productoId;
                            $TransjuegoInfo->transaccionId = $TransaccionApi->transaccionId;
                            $TransjuegoInfo->tipo = "SORTEO";
                            $TransjuegoInfo->descripcion = $idUsuRuleta;
                            $TransjuegoInfo->valor = $creditosConvert;
                            $TransjuegoInfo->transapiId = $TransaccionApi->transapiId;
                            $TransjuegoInfo->usucreaId = 0;
                            $TransjuegoInfo->usumodifId = 0;

                            $title = '';
                            $messageBody = '';
                            $rouletteName = $value->{"ruleta_interno.nombre"};

                            switch (strtolower($Usuario->idioma)) {
                                case 'es':
                                    $title = 'Notificacion';
                                    $messageBody = "¡ Bien :thumbsup: ! Estas participando en el ruleta {$rouletteName} :clap:";
                                    break;
                                case 'en':
                                    $title = 'Notification';
                                    $messageBody = "¡ Great :thumbsup: ! You are participating in the roulette {$rouletteName} :clap:";
                                    break;
                                case 'pt':
                                    $title = 'Notificação';
                                    $messageBody = "Eaí! :thumbsup: Você está participando da roleta {$rouletteName} :clap:";
                                    break;
                            }

                            $UsuarioMensaje = new UsuarioMensaje();
                            $UsuarioMensaje->usufromId = 0;
                            $UsuarioMensaje->usutoId = $UsuarioRuleta->getUsuarioId();
                            $UsuarioMensaje->isRead = 0;
                            $UsuarioMensaje->body = $messageBody;
                            $UsuarioMensaje->msubject = $title;
                            $UsuarioMensaje->parentId = 0;
                            $UsuarioMensaje->proveedorId = 0;
                            $UsuarioMensaje->tipo = "PUSHNOTIFICACION";
                            $UsuarioMensaje->paisId = 0;
                            $UsuarioMensaje->fechaExpiracion = '';

                            $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                            $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);

                            $TransjuegoInfoMySqlDAO->insert($TransjuegoInfo);
                            $TransjuegoInfoMySqlDAO->getTransaction()->commit();


                            $mensajesRecibidos = [];
                            $array = [];

                            $array["body"] = $title;

                            array_push($mensajesRecibidos, $array);
                            $data = array();
                            $data["messages"] = $mensajesRecibidos;
                            //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());
                            $ConfigurationEnvironment = new ConfigurationEnvironment();

                            if (!$ConfigurationEnvironment->isDevelopment()) {

                                $UsuarioSession = new UsuarioSession();
                                $rules = [];

                                array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                                array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

                                $filtro = array("rules" => $rules, "groupOp" => "AND");
                                $json = json_encode($filtro);


                                $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

                                $usuarios = json_decode($usuarios);

                                $usuariosFinal = [];

                                foreach ($usuarios->data as $key => $value) {

                                    $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data);
                                    $WebsocketUsuario->sendWSMessage();

                                }

                                if (in_array($UsuarioMandante->mandante, array('0', 8, 6))) {

                                    $dataSend = $data;
                                    $WebsocketUsuario = new WebsocketUsuario('', '');
                                    $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend);

                                }
                            }
                            break;
                        }


                    }


                }


                break;

            case "VIRTUAL":

                $TransaccionApi = new TransaccionApi($ticketId);
                $UsuarioMandante = new UsuarioMandante($TransaccionApi->usuarioId);
                $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

                $cumpleCondicion = false;
                $ruletasAnalizados = '';

                //print_r($TransaccionApi);
                //print_r($ticketId);

                if (false) {
                    $rules = [];

                    array_push($rules, array("field" => "usuario_ruleta.usuario_id", "data" => "$TransaccionApi->usuarioId", "op" => "eq"));
                    array_push($rules, array("field" => "ruleta_interno.estado", "data" => "A", "op" => "eq"));
                    array_push($rules, array("field" => "ruleta_interno.fecha_inicio", "data" => "$TransaccionApi->fechaCrea", "op" => "le"));
                    array_push($rules, array("field" => "ruleta_interno.fecha_fin", "data" => "$TransaccionApi->fechaCrea", "op" => "ge"));
                    array_push($rules, array("field" => "ruleta_interno.tipo", "data" => "4", "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $json = json_encode($filtro);


                    $UsuarioRuleta = new UsuarioRuleta();
                    $data = $UsuarioRuleta->getUsuarioRuletasCustomWithoutPosition("usuario_ruleta.*,usuario_mandante.nombres,ruleta_interno.*", "usuario_ruleta.valor", "DESC", 0, 100, $json, true, '');

                    //print_r($data);

                    $data = json_decode($data);

                    $final = [];

                    $pos = 1;
                    $ruletasAnalizados = '';

                    $cumpleCondicion = false;
                    foreach ($data->data as $key => $value) {

                        $array = [];
                        $array["Position"] = $pos;
                        $array["Id"] = $value->{"usuario_ruleta.usuruleta_id"};

                        $ruletasAnalizados = $ruletasAnalizados . $value->{"usuario_ruleta.ruleta_id"} . ",";
                        $RuletaInterno = new RuletaInterno();
                        $RuletaDetalle = new RuletaDetalle();

                        //$bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
                        //$bonos = json_decode($bonos);


                        $rules = [];

                        array_push($rules, array("field" => "ruleta_interno.ruleta_id", "data" => $value->{"usuario_ruleta.ruleta_id"}, "op" => "eq"));
                        array_push($rules, array("field" => "ruleta_interno.estado", "data" => "A", "op" => "eq"));

                        $filtro = array("rules" => $rules, "groupOp" => "AND");

                        $json = json_encode($filtro);

                        $ruletadetalles = $RuletaDetalle->getRuletaDetallesCustom(" ruleta_detalle.*,ruleta_interno.* ", "ruleta_interno.ruleta_id", "asc", 0, 1000, $json, TRUE);

                        $ruletadetalles = json_decode($ruletadetalles);

                        $final = [];

                        $creditosConvert = 0;

                        $cumpleCondicionPais = false;
                        $cumpleCondicionCont = 0;

                        $condicionesProducto = 0;

                        foreach ($ruletadetalles->data as $key2 => $value2) {

                            switch ($value2->{"ruleta_detalle.tipo"}) {
                                case "RANK":
                                    if ($value2->{"ruleta_detalle.moneda"} == $UsuarioMandante->moneda) {
                                        if ($TransaccionApi->valor >= $value2->{"ruleta_detalle.valor"}) {
                                            if ($TransaccionApi->valor <= $value2->{"ruleta_detalle.valor2"}) {
                                                $creditosConvert = $value2->{"ruleta_detalle.valor3"};
                                            }

                                        }
                                    }

                                    break;


                                case "CONDPAISUSER":

                                    if ($value2->{"ruleta_detalle.valor"} == $Usuario->paisId) {
                                        $cumpleCondicionPais = true;
                                    }
                                    $cumpleCondicionCont++;

                                    break;

                                default:

                                    if (stristr($value2->{"ruleta_detalle.tipo"}, 'CONDGAME')) {

                                        $idGame = explode("CONDGAME", $value2->{"ruleta_detalle.tipo"})[1];

                                        if ($TransaccionApi->productoId == $idGame) {
                                            $cumpleCondicion = true;
                                        }
                                        $condicionesProducto++;
                                    }

                                    if (stristr($value2->{"ruleta_detalle.tipo"}, 'CONDPROVIDER')) {

                                        $idProvider = explode("CONDPROVIDER", $value2->{"ruleta_detalle.tipo"})[1];

                                        if ($TransaccionApi->proveedorId == $idProvider) {
                                            $cumpleCondicion = true;
                                        }
                                        $condicionesProducto++;
                                    }


                                    break;
                            }

                        }

                        if ($condicionesProducto == 0 && !$cumpleCondicion) {
                            $cumpleCondicion = true;
                        }

                        if (!$cumpleCondicionPais && $cumpleCondicionCont > 0) {
                            $cumpleCondicion = false;
                        }

                        if ($cumpleCondicion) {
                            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();

                            $UsuarioRuleta = new UsuarioRuleta($value->{"usuario_ruleta.usuruleta_id"});
                            $UsuarioRuleta->valor = $UsuarioRuleta->valor + $creditosConvert;
                            $UsuarioRuleta->valorBase = ($UsuarioRuleta->valorBase + $TransaccionApi->valor);
                            //print_r($TransaccionApi);

                            //print_r($UsuarioRuleta);
                            $UsuarioRuletaMySqlDAO = new UsuarioRuletaMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                            $UsuarioRuletaMySqlDAO->update($UsuarioRuleta);

                            $TransjuegoInfo = new TransjuegoInfo();
                            $TransjuegoInfo->productoId = $TransaccionApi->productoId;
                            $TransjuegoInfo->transaccionId = $TransaccionApi->transaccionId;
                            $TransjuegoInfo->tipo = "SORTEO";
                            $TransjuegoInfo->descripcion = $value->{"usuario_ruleta.usuruleta_id"};
                            $TransjuegoInfo->valor = $creditosConvert;
                            $TransjuegoInfo->transapiId = $TransaccionApi->transapiId;
                            $TransjuegoInfo->usucreaId = 0;
                            $TransjuegoInfo->usumodifId = 0;
                            $TransjuegoInfo->identificador = $TransaccionApi->identificador;

                            $title = '';
                            $messageBody = '';
                            $rouletteName = $value->{"ruleta_interno.nombre"};

                            switch (strtolower($Usuario->idioma)) {
                                case 'es':
                                    $title = 'Notificacion';
                                    $messageBody = "¡ Bien :thumbsup: ! Estas participando en el ruleta {$rouletteName} :clap:";
                                    break;
                                case 'en':
                                    $title = 'Notification';
                                    $messageBody = "¡ Great :thumbsup: ! You are participating in the roulette {$rouletteName} :clap:";
                                    break;
                                case 'pt':
                                    $title = 'Notificação';
                                    $messageBody = "Eaí! :thumbsup: Você está participando da roleta {$rouletteName} :clap:";
                                    break;
                            }

                            $UsuarioMensaje = new UsuarioMensaje();
                            $UsuarioMensaje->usufromId = 0;
                            $UsuarioMensaje->usutoId = $UsuarioRuleta->getUsuarioId();
                            $UsuarioMensaje->isRead = 0;
                            $UsuarioMensaje->body = $messageBody;
                            $UsuarioMensaje->msubject = $title;
                            $UsuarioMensaje->parentId = 0;
                            $UsuarioMensaje->proveedorId = 0;
                            $UsuarioMensaje->tipo = "PUSHNOTIFICACION";
                            $UsuarioMensaje->paisId = 0;
                            $UsuarioMensaje->fechaExpiracion = '';

                            $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                            $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);


                            $TransjuegoInfoMySqlDAO->insert($TransjuegoInfo);
                            $TransjuegoInfoMySqlDAO->getTransaction()->commit();


                            $mensajesRecibidos = [];
                            $array = [];

                            $array["body"] = $messageBody;

                            array_push($mensajesRecibidos, $array);
                            $data = array();
                            $data["messages"] = $mensajesRecibidos;
                            //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

                            $UsuarioSession = new UsuarioSession();
                            $rules = [];

                            array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                            array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

                            $filtro = array("rules" => $rules, "groupOp" => "AND");
                            $json = json_encode($filtro);


                            $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

                            $usuarios = json_decode($usuarios);

                            $usuariosFinal = [];

                            foreach ($usuarios->data as $key => $value) {

                                $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data);
                                $WebsocketUsuario->sendWSMessage();

                            }

                            if (in_array($UsuarioMandante->mandante, array('0', 8))) {

                                $dataSend = $data;
                                $WebsocketUsuario = new WebsocketUsuario('', '');
                                $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend);

                            }
                            break;
                        }


                    }
                }
                if (!$cumpleCondicion) {

                    if ($ruletasAnalizados != "") {
                        $ruletasAnalizados = $ruletasAnalizados . '0';
                    }

                    $RuletaInterno = new RuletaInterno();

                    $rules = [];

                    if ($ruletasAnalizados != '') {
                        array_push($rules, array("field" => "ruleta_interno.ruleta_id", "data" => "$ruletasAnalizados", "op" => "ni"));
                    }

                    array_push($rules, array("field" => "ruleta_interno.estado", "data" => "A", "op" => "eq"));
                    array_push($rules, array("field" => "ruleta_interno.fecha_inicio", "data" => "$TransaccionApi->fechaCrea", "op" => "le"));
                    array_push($rules, array("field" => "ruleta_interno.fecha_fin", "data" => "$TransaccionApi->fechaCrea", "op" => "ge"));
                    array_push($rules, array("field" => "ruleta_interno.tipo", "data" => "4", "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $json = json_encode($filtro);


                    $data = $RuletaInterno->getRuletasCustom("ruleta_interno.*", "ruleta_interno.orden", "ASC", 0, 1000, $json, true, '');


                    $data = json_decode($data);

                    $final = [];

                    $pos = 1;
                    $ruletasAnalizados = '';

                    foreach ($data->data as $key => $value) {


                        $RuletaDetalle = new RuletaDetalle();

                        //$bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
                        //$bonos = json_decode($bonos);


                        $rules = [];

                        array_push($rules, array("field" => "ruleta_interno.ruleta_id", "data" => $value->{"ruleta_interno.ruleta_id"}, "op" => "eq"));
                        array_push($rules, array("field" => "ruleta_interno.estado", "data" => "A", "op" => "eq"));

                        $filtro = array("rules" => $rules, "groupOp" => "AND");

                        $json = json_encode($filtro);

                        $ruletadetalles = $RuletaDetalle->getRuletaDetallesCustom(" ruleta_detalle.*,ruleta_interno.* ", "ruleta_interno.ruleta_id", "asc", 0, 1000, $json, TRUE);

                        $ruletadetalles = json_decode($ruletadetalles);

                        $final = [];

                        $creditosConvert = 0;

                        $cumpleCondicion = false;
                        $needSubscribe = false;

                        $cumpleCondicionPais = false;
                        $cumpleCondicionCont = 0;

                        $condicionesProducto = 0;

                        foreach ($ruletadetalles->data as $key2 => $value2) {

                            switch ($value2->{"ruleta_detalle.tipo"}) {
                                case "RANK":
                                    if ($value2->{"ruleta_detalle.moneda"} == $UsuarioMandante->moneda) {
                                        if ($TransaccionApi->valor >= $value2->{"ruleta_detalle.valor"}) {
                                            if ($TransaccionApi->valor <= $value2->{"ruleta_detalle.valor2"}) {
                                                $creditosConvert = $value2->{"ruleta_detalle.valor3"};
                                            }

                                        }
                                    }

                                    break;

                                case "USERSUBSCRIBE":

                                    if ($value2->{"ruleta_detalle.valor"} == 0) {

                                    } else {
                                        $needSubscribe = true;
                                    }

                                    break;

                                case "VISIBILIDAD":

                                    if ($value2->{"ruleta_detalle.valor"} == 1) {
                                        $needSubscribe = true;

                                    } else {
                                    }

                                    break;


                                case "CONDPAISUSER":

                                    if ($value2->{"ruleta_detalle.valor"} == $Usuario->paisId) {
                                        $cumpleCondicionPais = true;
                                    }
                                    $cumpleCondicionCont++;

                                    break;

                                default:

                                    if (stristr($value2->{"ruleta_detalle.tipo"}, 'CONDGAME')) {

                                        $idGame = explode("CONDGAME", $value2->{"ruleta_detalle.tipo"})[1];

                                        if ($TransaccionApi->productoId == $idGame) {

                                            $cumpleCondicion = true;
                                        }
                                        $condicionesProducto++;
                                    }

                                    if (stristr($value2->{"ruleta_detalle.tipo"}, 'CONDPROVIDER')) {

                                        $idProvider = explode("CONDPROVIDER", $value2->{"ruleta_detalle.tipo"})[1];

                                        if ($TransaccionApi->proveedorId == $idProvider) {
                                            $cumpleCondicion = true;
                                        }
                                        $condicionesProducto++;
                                    }

                                    break;
                            }

                        }

                        if ($condicionesProducto == 0 && !$cumpleCondicion) {
                            $cumpleCondicion = true;
                        }

                        if (!$cumpleCondicionPais && $cumpleCondicionCont > 0) {

                            $cumpleCondicion = false;
                        }

                        if ($cumpleCondicion && !$needSubscribe) {
                            //print_r("CUMPLECONDICION");

                            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();

                            $UsuarioRuleta = new UsuarioRuleta();
                            $UsuarioRuleta->usuarioId = $UsuarioMandante->usumandanteId;
                            $UsuarioRuleta->ruletaId = $value->{"ruleta_interno.ruleta_id"};
                            $UsuarioRuleta->valor = 0;
                            $UsuarioRuleta->posicion = 0;
                            $UsuarioRuleta->valorBase = 0;
                            $UsuarioRuleta->usucreaId = 0;
                            $UsuarioRuleta->usumodifId = 0;
                            $UsuarioRuleta->estado = "A";
                            $UsuarioRuleta->errorId = 0;
                            $UsuarioRuleta->idExterno = 0;
                            $UsuarioRuleta->mandante = 0;
                            $UsuarioRuleta->version = 0;
                            $UsuarioRuleta->apostado = 0;
                            $UsuarioRuleta->codigo = 0;
                            $UsuarioRuleta->externoId = 0;
                            $UsuarioRuleta->valor = $UsuarioRuleta->valor + $creditosConvert;
                            $UsuarioRuleta->valorBase = ($UsuarioRuleta->valorBase + $TransaccionApi->valor);

                            $UsuarioRuletaMySqlDAO = new UsuarioRuletaMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                            $idUsuRuleta = $UsuarioRuletaMySqlDAO->insert($UsuarioRuleta);

                            $TransjuegoInfo = new TransjuegoInfo();
                            $TransjuegoInfo->productoId = $TransaccionApi->productoId;
                            $TransjuegoInfo->transaccionId = $TransaccionApi->transaccionId;
                            $TransjuegoInfo->tipo = "SORTEO";
                            $TransjuegoInfo->descripcion = $idUsuRuleta;
                            $TransjuegoInfo->valor = $creditosConvert;
                            $TransjuegoInfo->transapiId = $TransaccionApi->transapiId;
                            $TransjuegoInfo->usucreaId = 0;
                            $TransjuegoInfo->usumodifId = 0;

                            $title = '';
                            $messageBody = '';
                            $rouletteName = $value->{"ruleta_interno.nombre"};

                            switch (strtolower($Usuario->idioma)) {
                                case 'es':
                                    $title = 'Notificacion';
                                    $messageBody = "¡ Bien :thumbsup: ! Estas participando en el ruleta {$rouletteName} :clap:";
                                    break;
                                case 'en':
                                    $title = 'Notification';
                                    $messageBody = "¡ Great :thumbsup: ! You are participating in the roulette {$rouletteName} :clap:";
                                    break;
                                case 'pt':
                                    $title = 'Notificação';
                                    $messageBody = "Eaí! :thumbsup: Você está participando da roleta {$rouletteName} :clap:";
                                    break;
                            }

                            $UsuarioMensaje = new UsuarioMensaje();
                            $UsuarioMensaje->usufromId = 0;
                            $UsuarioMensaje->usutoId = $UsuarioRuleta->getUsuarioId();
                            $UsuarioMensaje->isRead = 0;
                            $UsuarioMensaje->body = $messageBody;
                            $UsuarioMensaje->msubject = $title;
                            $UsuarioMensaje->parentId = 0;
                            $UsuarioMensaje->proveedorId = 0;
                            $UsuarioMensaje->tipo = "PUSHNOTIFICACION";
                            $UsuarioMensaje->paisId = 0;
                            $UsuarioMensaje->fechaExpiracion = '';

                            $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                            $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);


                            $TransjuegoInfoMySqlDAO->insert($TransjuegoInfo);
                            $TransjuegoInfoMySqlDAO->getTransaction()->commit();


                            $mensajesRecibidos = [];
                            $array = [];

                            $array["body"] = $messageBody;

                            array_push($mensajesRecibidos, $array);
                            $data = array();
                            $data["messages"] = $mensajesRecibidos;
                            //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

                            $UsuarioSession = new UsuarioSession();
                            $rules = [];

                            array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                            array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

                            $filtro = array("rules" => $rules, "groupOp" => "AND");
                            $json = json_encode($filtro);


                            $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

                            $usuarios = json_decode($usuarios);

                            $usuariosFinal = [];

                            foreach ($usuarios->data as $key => $value) {

                                $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data);
                                $WebsocketUsuario->sendWSMessage();

                            }

                            if (in_array($UsuarioMandante->mandante, array('0', 8))) {

                                $dataSend = $data;
                                $WebsocketUsuario = new WebsocketUsuario('', '');
                                $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend);

                            }
                            break;
                        }


                    }


                }


                break;

        }
    }


    /**
     * Verficiar ruleta
     *
     *
     * @param String usuarioId id del usuario
     * @param String detalles detalles
     * @param String tipoProducto tipoProducto
     * @param String ticketId id del tiquete
     *
     * @return Array $result resultado de la verificación
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function verificarRuletaUsuarioConTransaccionJuego($usuarioId, $detalles, $tipoProducto, $ticketId)
    {

        switch ($tipoProducto) {


            case "CASINO":

                $TransaccionJuego = new TransaccionJuego($ticketId);
                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->usuarioId);
                $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

                //print_r($TransaccionJuego);

                $rules = [];

                array_push($rules, array("field" => "usuario_ruleta.usuario_id", "data" => "$TransaccionJuego->usuarioId", "op" => "eq"));
                array_push($rules, array("field" => "ruleta_interno.estado", "data" => "A", "op" => "eq"));
                // array_push($rules, array("field" => "ruleta_interno.fecha_inicio", "data" => "$TransaccionJuego->fechaCrea" , "op" => "le"));
                // array_push($rules, array("field" => "ruleta_interno.fecha_fin", "data" => "$TransaccionJuego->fechaCrea", "op" => "ge"));
                array_push($rules, array("field" => "ruleta_interno.tipo", "data" => "2", "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                $json = json_encode($filtro);


                $UsuarioRuleta = new UsuarioRuleta();
                $data = $UsuarioRuleta->getUsuarioRuletasCustomWithoutPosition("usuario_ruleta.*,usuario_mandante.nombres,ruleta_interno.*", "usuario_ruleta.valor", "DESC", 0, 100, $json, true, '');

                //print_r($data);

                $data = json_decode($data);

                $final = [];

                $pos = 1;
                $ruletasAnalizados = '';

                $cumpleCondicion = false;
                foreach ($data->data as $key => $value) {

                    $array = [];
                    $array["Position"] = $pos;
                    $array["Id"] = $value->{"usuario_ruleta.usuruleta_id"};

                    $ruletasAnalizados = $ruletasAnalizados . $value->{"usuario_ruleta.ruleta_id"} . ",";
                    $RuletaInterno = new RuletaInterno();
                    $RuletaDetalle = new RuletaDetalle();

                    //$bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
                    //$bonos = json_decode($bonos);


                    $rules = [];

                    array_push($rules, array("field" => "ruleta_interno.ruleta_id", "data" => $value->{"usuario_ruleta.ruleta_id"}, "op" => "eq"));
                    array_push($rules, array("field" => "ruleta_interno.estado", "data" => "A", "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $json = json_encode($filtro);

                    $ruletadetalles = $RuletaDetalle->getRuletaDetallesCustom(" ruleta_detalle.*,ruleta_interno.* ", "ruleta_interno.ruleta_id", "asc", 0, 1000, $json, TRUE);

                    $ruletadetalles = json_decode($ruletadetalles);

                    $final = [];

                    $creditosConvert = 0;

                    $cumpleCondicionPais = false;
                    $cumpleCondicionCont = 0;

                    $condicionesProducto = 0;

                    //print_r($ruletadetalles);

                    foreach ($ruletadetalles->data as $key2 => $value2) {

                        switch ($value2->{"ruleta_detalle.tipo"}) {
                            case "RANK":
                                if ($value2->{"ruleta_detalle.moneda"} == $UsuarioMandante->moneda) {
                                    if ($TransaccionJuego->valorTicket >= $value2->{"ruleta_detalle.valor"}) {
                                        if ($TransaccionJuego->valorTicket <= $value2->{"ruleta_detalle.valor2"}) {
                                            $creditosConvert = $value2->{"ruleta_detalle.valor3"};
                                        }

                                    }
                                }

                                break;


                            case "CONDPAISUSER":

                                if ($value2->{"ruleta_detalle.valor"} == $Usuario->paisId) {
                                    $cumpleCondicionPais = true;
                                }
                                $cumpleCondicionCont++;

                                break;

                            default:

                                if (stristr($value2->{"ruleta_detalle.tipo"}, 'CONDGAME')) {

                                    $idGame = explode("CONDGAME", $value2->{"ruleta_detalle.tipo"})[1];

                                    if ($TransaccionJuego->productoId == $idGame) {
                                        $cumpleCondicion = true;
                                    }
                                    $condicionesProducto++;
                                }

                                if (stristr($value2->{"ruleta_detalle.tipo"}, 'CONDPROVIDER')) {
                                    /*
                                                                        $idProvider = explode("CONDPROVIDER", $value2->{"ruleta_detalle.tipo"})[1];

                                                                        if($TransaccionJuego->proveedorId == $idProvider){
                                                                            $cumpleCondicion=true;
                                                                        }
                                                                        $condicionesProducto++;*/
                                }

                                break;
                        }

                    }

                    if ($condicionesProducto == 0 && !$cumpleCondicion) {
                        $cumpleCondicion = true;
                    }

                    if (!$cumpleCondicionPais && $cumpleCondicionCont > 0) {
                        $cumpleCondicion = false;
                    }
                    //print_r("creditosConvert: " . $creditosConvert);

                    if ($cumpleCondicion) {
                        if ($creditosConvert == 0) {
                            $cumpleCondicion = false;
                        }
                    }
                    if ($cumpleCondicion) {
                        $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();

                        $UsuarioRuleta = new UsuarioRuleta($value->{"usuario_ruleta.usuruleta_id"});
                        $UsuarioRuleta->valor = $UsuarioRuleta->valor + $creditosConvert;
                        $UsuarioRuleta->valorBase = ($UsuarioRuleta->valorBase + $TransaccionJuego->valorTicket);

                        //print_r($UsuarioRuleta);
                        $UsuarioRuletaMySqlDAO = new UsuarioRuletaMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                        $UsuarioRuletaMySqlDAO->update($UsuarioRuleta);

                        $TransjuegoInfo = new TransjuegoInfo();
                        $TransjuegoInfo->productoId = $TransaccionJuego->productoId;
                        $TransjuegoInfo->transaccionId = $TransaccionJuego->transaccionId;
                        $TransjuegoInfo->tipo = "SORTEO";
                        $TransjuegoInfo->descripcion = $value->{"usuario_ruleta.usuruleta_id"};
                        $TransjuegoInfo->valor = $creditosConvert;
                        $TransjuegoInfo->transapiId = 0;
                        $TransjuegoInfo->usucreaId = 0;
                        $TransjuegoInfo->usumodifId = 0;
                        $TransjuegoInfo->identificador = $TransaccionJuego->ticketId;

                        $title = '';
                        $messageBody = '';
                        $rouletteName = $value->{"ruleta_interno.nombre"};

                        switch (strtolower($Usuario->idioma)) {
                            case 'es':
                                $title = 'Notificacion';
                                $messageBody = "¡ Bien :thumbsup: ! Sumaste {$creditosConvert} puntos en {$rouletteName} :clap:";
                                break;
                            case 'en':
                                $title = 'Notification';
                                $messageBody = "¡ Great :thumbsup: ! You added {$creditosConvert} points in {$rouletteName}  :clap:";
                                break;
                            case 'pt':
                                $title = 'Notificação';
                                $messageBody = "Se liga! :thumbsup: Você somou {$creditosConvert} pontos em {$rouletteName} :clap:";
                                break;
                        }

                        $UsuarioMensaje = new UsuarioMensaje();
                        $UsuarioMensaje->usufromId = 0;
                        $UsuarioMensaje->usutoId = $UsuarioRuleta->getUsuarioId();
                        $UsuarioMensaje->isRead = 0;
                        $UsuarioMensaje->body = $messageBody;
                        $UsuarioMensaje->msubject = $title;
                        $UsuarioMensaje->parentId = 0;
                        $UsuarioMensaje->proveedorId = 0;
                        $UsuarioMensaje->tipo = "PUSHNOTIFICACION";
                        $UsuarioMensaje->paisId = 0;
                        $UsuarioMensaje->fechaExpiracion = '';

                        $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                        $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);


                        $TransjuegoInfoMySqlDAO->insert($TransjuegoInfo);
                        $TransjuegoInfoMySqlDAO->getTransaction()->commit();


                        $mensajesRecibidos = [];
                        $array = [];

                        $array["body"] = $messageBody;

                        array_push($mensajesRecibidos, $array);
                        $data = array();
                        $data["messages"] = $mensajesRecibidos;
                        //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

                        $UsuarioSession = new UsuarioSession();
                        $rules = [];

                        array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                        array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

                        $filtro = array("rules" => $rules, "groupOp" => "AND");
                        $json = json_encode($filtro);


                        $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

                        $usuarios = json_decode($usuarios);

                        $usuariosFinal = [];

                        foreach ($usuarios->data as $key => $value) {

                            $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data);
                            $WebsocketUsuario->sendWSMessage();

                        }

                        if (in_array($UsuarioMandante->mandante, array('0', 8))) {

                            $dataSend = $data;
                            $WebsocketUsuario = new WebsocketUsuario('', '');
                            $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend);

                        }
                        break;
                    }


                }

                if (!$cumpleCondicion) {

                    if ($ruletasAnalizados != "") {
                        $ruletasAnalizados = $ruletasAnalizados . '0';
                    }

                    $RuletaInterno = new RuletaInterno();

                    $rules = [];

                    if ($ruletasAnalizados != '') {
                        array_push($rules, array("field" => "ruleta_interno.ruleta_id", "data" => "$ruletasAnalizados", "op" => "ni"));
                    }

                    array_push($rules, array("field" => "ruleta_interno.estado", "data" => "A", "op" => "eq"));
                    array_push($rules, array("field" => "ruleta_interno.fecha_inicio", "data" => "$TransaccionJuego->fechaCrea", "op" => "le"));
                    array_push($rules, array("field" => "ruleta_interno.fecha_fin", "data" => "$TransaccionJuego->fechaCrea", "op" => "ge"));
                    array_push($rules, array("field" => "ruleta_interno.tipo", "data" => "2", "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $json = json_encode($filtro);


                    $data = $RuletaInterno->getRuletasCustom("ruleta_interno.*", "ruleta_interno.orden", "ASC", 0, 1000, $json, true, '');


                    $data = json_decode($data);

                    $final = [];

                    $pos = 1;
                    $ruletasAnalizados = '';

                    foreach ($data->data as $key => $value) {


                        $RuletaDetalle = new RuletaDetalle();

                        //$bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
                        //$bonos = json_decode($bonos);


                        $rules = [];

                        array_push($rules, array("field" => "ruleta_interno.ruleta_id", "data" => $value->{"ruleta_interno.ruleta_id"}, "op" => "eq"));
                        array_push($rules, array("field" => "ruleta_interno.estado", "data" => "A", "op" => "eq"));

                        $filtro = array("rules" => $rules, "groupOp" => "AND");

                        $json = json_encode($filtro);

                        $ruletadetalles = $RuletaDetalle->getRuletaDetallesCustom(" ruleta_detalle.*,ruleta_interno.* ", "ruleta_interno.ruleta_id", "asc", 0, 1000, $json, TRUE);

                        $ruletadetalles = json_decode($ruletadetalles);

                        $final = [];

                        $creditosConvert = 0;

                        $cumpleCondicion = false;
                        $needSubscribe = false;

                        $cumpleCondicionPais = false;
                        $cumpleCondicionCont = 0;

                        $condicionesProducto = 0;
                        //print_r($ruletadetalles);

                        foreach ($ruletadetalles->data as $key2 => $value2) {

                            switch ($value2->{"ruleta_detalle.tipo"}) {
                                case "RANK":
                                    if ($value2->{"ruleta_detalle.moneda"} == $UsuarioMandante->moneda) {
                                        if ($TransaccionJuego->valorTicket >= $value2->{"ruleta_detalle.valor"}) {
                                            if ($TransaccionJuego->valorTicket <= $value2->{"ruleta_detalle.valor2"}) {
                                                $creditosConvert = $value2->{"ruleta_detalle.valor3"};
                                            }

                                        }
                                    }

                                    break;

                                case "USERSUBSCRIBE":

                                    if ($value2->{"ruleta_detalle.valor"} == 0) {

                                    } else {
                                        $needSubscribe = true;
                                    }

                                    break;

                                case "VISIBILIDAD":

                                    if ($value2->{"ruleta_detalle.valor"} == 1) {
                                        $needSubscribe = true;

                                    } else {
                                    }

                                    break;


                                case "CONDPAISUSER":

                                    if ($value2->{"ruleta_detalle.valor"} == $Usuario->paisId) {
                                        $cumpleCondicionPais = true;
                                    }
                                    $cumpleCondicionCont++;

                                    break;

                                default:

                                    if (stristr($value2->{"ruleta_detalle.tipo"}, 'CONDGAME')) {

                                        $idGame = explode("CONDGAME", $value2->{"ruleta_detalle.tipo"})[1];

                                        if ($TransaccionJuego->productoId == $idGame) {

                                            $cumpleCondicion = true;
                                        }
                                        $condicionesProducto++;
                                    }

                                    /*if (stristr($value2->{"ruleta_detalle.tipo"}, 'CONDPROVIDER')) {

                                        $idProvider = explode("CONDPROVIDER", $value2->{"ruleta_detalle.tipo"})[1];

                                        if($TransaccionJuego->proveedorId == $idProvider){
                                            $cumpleCondicion=true;
                                        }
                                        $condicionesProducto++;
                                    }*/

                                    break;
                            }

                        }

                        if ($condicionesProducto == 0 && !$cumpleCondicion) {
                            $cumpleCondicion = true;
                        }

                        if (!$cumpleCondicionPais && $cumpleCondicionCont > 0) {

                            $cumpleCondicion = false;
                        }
                        //print_r("creditosConvert: " . $creditosConvert);


                        if ($cumpleCondicion) {
                            if ($creditosConvert == 0) {
                                $cumpleCondicion = false;
                            }
                        }

                        if ($cumpleCondicion && !$needSubscribe && $creditosConvert > 0) {
                            //print_r("CUMPLECONDICION");

                            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();

                            $UsuarioRuleta = new UsuarioRuleta();
                            $UsuarioRuleta->usuarioId = $UsuarioMandante->usumandanteId;
                            $UsuarioRuleta->ruletaId = $value->{"ruleta_interno.ruleta_id"};
                            $UsuarioRuleta->valor = 0;
                            $UsuarioRuleta->posicion = 0;
                            $UsuarioRuleta->valorBase = 0;
                            $UsuarioRuleta->usucreaId = 0;
                            $UsuarioRuleta->usumodifId = 0;
                            $UsuarioRuleta->estado = "A";
                            $UsuarioRuleta->errorId = 0;
                            $UsuarioRuleta->idExterno = 0;
                            $UsuarioRuleta->mandante = 0;
                            $UsuarioRuleta->version = 0;
                            $UsuarioRuleta->apostado = 0;
                            $UsuarioRuleta->codigo = 0;
                            $UsuarioRuleta->externoId = 0;
                            $UsuarioRuleta->valor = $UsuarioRuleta->valor + $creditosConvert;
                            $UsuarioRuleta->valorBase = ($UsuarioRuleta->valorBase + $TransaccionJuego->valorTicket);

                            $UsuarioRuletaMySqlDAO = new UsuarioRuletaMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                            $idUsuRuleta = $UsuarioRuletaMySqlDAO->insert($UsuarioRuleta);

                            $TransjuegoInfo = new TransjuegoInfo();
                            $TransjuegoInfo->productoId = $TransaccionJuego->productoId;
                            $TransjuegoInfo->transaccionId = $TransaccionJuego->transaccionId;
                            $TransjuegoInfo->tipo = "SORTEO";
                            $TransjuegoInfo->descripcion = $idUsuRuleta;
                            $TransjuegoInfo->valor = $creditosConvert;
                            $TransjuegoInfo->transapiId = 0;
                            $TransjuegoInfo->usucreaId = 0;
                            $TransjuegoInfo->usumodifId = 0;

                            $title = '';
                            $messageBody = '';
                            $rouletteName = $value->{"ruleta_interno.nombre"};

                            switch (strtolower($Usuario->idioma)) {
                                case 'es':
                                    $title = 'Notificacion';
                                    $messageBody = "¡ Bien :thumbsup: ! Sumaste {$creditosConvert} puntos en {$rouletteName} :clap:";
                                    break;
                                case 'en':
                                    $title = 'Notification';
                                    $messageBody = "¡ Great :thumbsup: ! You added {$creditosConvert} points in {$rouletteName}  :clap:";
                                    break;
                                case 'pt':
                                    $title = 'Notificação';
                                    $messageBody = "Se liga! :thumbsup: Você somou {$creditosConvert} pontos em {$rouletteName} :clap:";
                                    break;
                            }

                            $UsuarioMensaje = new UsuarioMensaje();
                            $UsuarioMensaje->usufromId = 0;
                            $UsuarioMensaje->usutoId = $UsuarioRuleta->getUsuarioId();
                            $UsuarioMensaje->isRead = 0;
                            $UsuarioMensaje->body = $messageBody;
                            $UsuarioMensaje->msubject = $title;
                            $UsuarioMensaje->parentId = 0;
                            $UsuarioMensaje->proveedorId = 0;
                            $UsuarioMensaje->tipo = "PUSHNOTIFICACION";
                            $UsuarioMensaje->paisId = 0;
                            $UsuarioMensaje->fechaExpiracion = '';

                            $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                            $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);


                            $TransjuegoInfoMySqlDAO->insert($TransjuegoInfo);
                            $TransjuegoInfoMySqlDAO->getTransaction()->commit();


                            $mensajesRecibidos = [];
                            $array = [];

                            $array["body"] = $messageBody;

                            array_push($mensajesRecibidos, $array);
                            $data = array();
                            $data["messages"] = $mensajesRecibidos;
                            //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

                            $UsuarioSession = new UsuarioSession();
                            $rules = [];

                            array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                            array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

                            $filtro = array("rules" => $rules, "groupOp" => "AND");
                            $json = json_encode($filtro);


                            $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

                            $usuarios = json_decode($usuarios);

                            $usuariosFinal = [];

                            foreach ($usuarios->data as $key => $value) {

                                $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data);
                                $WebsocketUsuario->sendWSMessage();

                            }

                            if (in_array($UsuarioMandante->mandante, array('0', 8))) {

                                $dataSend = $data;
                                $WebsocketUsuario = new WebsocketUsuario('', '');
                                $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend);

                            }
                            break;
                        }


                    }


                }


                break;


            case "LIVECASINO":

                $TransaccionApi = new TransaccionApi($ticketId);
                $UsuarioMandante = new UsuarioMandante($TransaccionApi->usuarioId);
                $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

                //print_r($TransaccionApi);
                //print_r($ticketId);

                $rules = [];

                array_push($rules, array("field" => "usuario_ruleta.usuario_id", "data" => "$TransaccionApi->usuarioId", "op" => "eq"));
                array_push($rules, array("field" => "ruleta_interno.estado", "data" => "A", "op" => "eq"));
                array_push($rules, array("field" => "ruleta_interno.fecha_inicio", "data" => "$TransaccionApi->fechaCrea", "op" => "le"));
                array_push($rules, array("field" => "ruleta_interno.fecha_fin", "data" => "$TransaccionApi->fechaCrea", "op" => "ge"));
                array_push($rules, array("field" => "ruleta_interno.tipo", "data" => "3", "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                $json = json_encode($filtro);


                $UsuarioRuleta = new UsuarioRuleta();
                $data = $UsuarioRuleta->getUsuarioRuletasCustomWithoutPosition("usuario_ruleta.*,usuario_mandante.nombres,ruleta_interno.*", "usuario_ruleta.valor", "DESC", 0, 100, $json, true, '');

                //print_r($data);

                $data = json_decode($data);

                $final = [];

                $pos = 1;
                $ruletasAnalizados = '';

                $cumpleCondicion = false;
                foreach ($data->data as $key => $value) {

                    $array = [];
                    $array["Position"] = $pos;
                    $array["Id"] = $value->{"usuario_ruleta.usuruleta_id"};

                    $ruletasAnalizados = $ruletasAnalizados . $value->{"usuario_ruleta.ruleta_id"} . ",";
                    $RuletaInterno = new RuletaInterno();
                    $RuletaDetalle = new RuletaDetalle();

                    //$bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
                    //$bonos = json_decode($bonos);


                    $rules = [];

                    array_push($rules, array("field" => "ruleta_interno.ruleta_id", "data" => $value->{"usuario_ruleta.ruleta_id"}, "op" => "eq"));
                    array_push($rules, array("field" => "ruleta_interno.estado", "data" => "A", "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $json = json_encode($filtro);

                    $ruletadetalles = $RuletaDetalle->getRuletaDetallesCustom(" ruleta_detalle.*,ruleta_interno.* ", "ruleta_interno.ruleta_id", "asc", 0, 1000, $json, TRUE);

                    $ruletadetalles = json_decode($ruletadetalles);

                    $final = [];

                    $creditosConvert = 0;

                    $cumpleCondicionPais = false;
                    $cumpleCondicionCont = 0;

                    $condicionesProducto = 0;

                    foreach ($ruletadetalles->data as $key2 => $value2) {

                        switch ($value2->{"ruleta_detalle.tipo"}) {
                            case "RANK":
                                if ($value2->{"ruleta_detalle.moneda"} == $UsuarioMandante->moneda) {
                                    if ($TransaccionApi->valor >= $value2->{"ruleta_detalle.valor"}) {
                                        if ($TransaccionApi->valor <= $value2->{"ruleta_detalle.valor2"}) {
                                            $creditosConvert = $value2->{"ruleta_detalle.valor3"};
                                        }

                                    }
                                }

                                break;


                            case "CONDPAISUSER":

                                if ($value2->{"ruleta_detalle.valor"} == $Usuario->paisId) {
                                    $cumpleCondicionPais = true;
                                }
                                $cumpleCondicionCont++;

                                break;

                            default:

                                if (stristr($value2->{"ruleta_detalle.tipo"}, 'CONDGAME')) {

                                    $idGame = explode("CONDGAME", $value2->{"ruleta_detalle.tipo"})[1];

                                    if ($TransaccionApi->productoId == $idGame) {
                                        $cumpleCondicion = true;
                                    }
                                    $condicionesProducto++;
                                }

                                if (stristr($value2->{"ruleta_detalle.tipo"}, 'CONDPROVIDER')) {

                                    $idProvider = explode("CONDPROVIDER", $value2->{"ruleta_detalle.tipo"})[1];

                                    if ($TransaccionApi->proveedorId == $idProvider) {
                                        $cumpleCondicion = true;
                                    }
                                    $condicionesProducto++;
                                }

                                break;
                        }

                    }

                    if ($condicionesProducto == 0 && !$cumpleCondicion) {
                        $cumpleCondicion = true;
                    }

                    if (!$cumpleCondicionPais && $cumpleCondicionCont > 0) {
                        $cumpleCondicion = false;
                    }

                    if ($cumpleCondicion) {
                        $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();

                        $UsuarioRuleta = new UsuarioRuleta($value->{"usuario_ruleta.usuruleta_id"});
                        $UsuarioRuleta->valor = $UsuarioRuleta->valor + $creditosConvert;
                        $UsuarioRuleta->valorBase = ($UsuarioRuleta->valorBase + $TransaccionApi->valor);
                        //print_r($TransaccionApi);

                        //print_r($UsuarioRuleta);
                        $UsuarioRuletaMySqlDAO = new UsuarioRuletaMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                        $UsuarioRuletaMySqlDAO->update($UsuarioRuleta);

                        $TransjuegoInfo = new TransjuegoInfo();
                        $TransjuegoInfo->productoId = $TransaccionApi->productoId;
                        $TransjuegoInfo->transaccionId = $TransaccionApi->transaccionId;
                        $TransjuegoInfo->tipo = "SORTEO";
                        $TransjuegoInfo->descripcion = $value->{"usuario_ruleta.usuruleta_id"};
                        $TransjuegoInfo->valor = $creditosConvert;
                        $TransjuegoInfo->transapiId = $TransaccionApi->transapiId;
                        $TransjuegoInfo->usucreaId = 0;
                        $TransjuegoInfo->usumodifId = 0;
                        $TransjuegoInfo->identificador = $TransaccionApi->identificador;

                        $title = '';
                        $messageBody = '';
                        $rouletteName = $value->{"ruleta_interno.nombre"};

                        switch (strtolower($Usuario->idioma)) {
                            case 'es':
                                $title = 'Notificacion';
                                $messageBody = "¡ Bien :thumbsup: ! Sumaste {$creditosConvert} puntos en {$rouletteName} :clap:";
                                break;
                            case 'en':
                                $title = 'Notification';
                                $messageBody = "¡ Great :thumbsup: ! You added {$creditosConvert} points in {$rouletteName}  :clap:";
                                break;
                            case 'pt':
                                $title = 'Notificação';
                                $messageBody = "Se liga! :thumbsup: Você somou {$creditosConvert} pontos em {$rouletteName} :clap:";
                                break;
                        }

                        $UsuarioMensaje = new UsuarioMensaje();
                        $UsuarioMensaje->usufromId = 0;
                        $UsuarioMensaje->usutoId = $UsuarioRuleta->getUsuarioId();
                        $UsuarioMensaje->isRead = 0;
                        $UsuarioMensaje->body = $messageBody;
                        $UsuarioMensaje->msubject = $title;
                        $UsuarioMensaje->parentId = 0;
                        $UsuarioMensaje->proveedorId = 0;
                        $UsuarioMensaje->tipo = "PUSHNOTIFICACION";
                        $UsuarioMensaje->paisId = 0;
                        $UsuarioMensaje->fechaExpiracion = '';

                        $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                        $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);


                        $TransjuegoInfoMySqlDAO->insert($TransjuegoInfo);
                        $TransjuegoInfoMySqlDAO->getTransaction()->commit();

                        $UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

                        //  Enviamos el mensaje Websocket al Usuario para que actualice el saldo

                        $mensajesRecibidos = [];
                        $array = [];

                        $array["body"] = $messageBody;

                        array_push($mensajesRecibidos, $array);
                        $data = array();
                        $data["messages"] = $mensajesRecibidos;


                        $WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
                        $WebsocketUsuario->sendWSMessage();


                        if (in_array($UsuarioMandante->mandante, array('0', 8))) {

                            $dataSend = $data;
                            $WebsocketUsuario = new WebsocketUsuario('', '');
                            $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend);

                        }
                        break;
                    }


                }

                if (!$cumpleCondicion) {

                    if ($ruletasAnalizados != "") {
                        $ruletasAnalizados = $ruletasAnalizados . '0';
                    }

                    $RuletaInterno = new RuletaInterno();

                    $rules = [];

                    if ($ruletasAnalizados != '') {
                        array_push($rules, array("field" => "ruleta_interno.ruleta_id", "data" => "$ruletasAnalizados", "op" => "ni"));
                    }

                    array_push($rules, array("field" => "ruleta_interno.estado", "data" => "A", "op" => "eq"));
                    array_push($rules, array("field" => "ruleta_interno.fecha_inicio", "data" => "$TransaccionApi->fechaCrea", "op" => "le"));
                    array_push($rules, array("field" => "ruleta_interno.fecha_fin", "data" => "$TransaccionApi->fechaCrea", "op" => "ge"));
                    array_push($rules, array("field" => "ruleta_interno.tipo", "data" => "3", "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $json = json_encode($filtro);


                    $data = $RuletaInterno->getRuletasCustom("ruleta_interno.*", "ruleta_interno.orden", "ASC", 0, 1000, $json, true, '');


                    $data = json_decode($data);

                    $final = [];

                    $pos = 1;
                    $ruletasAnalizados = '';

                    foreach ($data->data as $key => $value) {


                        $RuletaDetalle = new RuletaDetalle();

                        //$bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
                        //$bonos = json_decode($bonos);


                        $rules = [];

                        array_push($rules, array("field" => "ruleta_interno.ruleta_id", "data" => $value->{"ruleta_interno.ruleta_id"}, "op" => "eq"));
                        array_push($rules, array("field" => "ruleta_interno.estado", "data" => "A", "op" => "eq"));

                        $filtro = array("rules" => $rules, "groupOp" => "AND");

                        $json = json_encode($filtro);

                        $ruletadetalles = $RuletaDetalle->getRuletaDetallesCustom(" ruleta_detalle.*,ruleta_interno.* ", "ruleta_interno.ruleta_id", "asc", 0, 1000, $json, TRUE);

                        $ruletadetalles = json_decode($ruletadetalles);

                        $final = [];

                        $creditosConvert = 0;

                        $cumpleCondicion = false;
                        $needSubscribe = false;

                        $cumpleCondicionPais = false;
                        $cumpleCondicionCont = 0;

                        $condicionesProducto = 0;

                        foreach ($ruletadetalles->data as $key2 => $value2) {

                            switch ($value2->{"ruleta_detalle.tipo"}) {
                                case "RANK":
                                    if ($value2->{"ruleta_detalle.moneda"} == $UsuarioMandante->moneda) {
                                        if ($TransaccionApi->valor >= $value2->{"ruleta_detalle.valor"}) {
                                            if ($TransaccionApi->valor <= $value2->{"ruleta_detalle.valor2"}) {
                                                $creditosConvert = $value2->{"ruleta_detalle.valor3"};
                                            }

                                        }
                                    }

                                    break;

                                case "USERSUBSCRIBE":

                                    if ($value2->{"ruleta_detalle.valor"} == 0) {

                                    } else {
                                        $needSubscribe = true;
                                    }

                                    break;

                                case "VISIBILIDAD":

                                    if ($value2->{"ruleta_detalle.valor"} == 1) {
                                        $needSubscribe = true;

                                    } else {
                                    }

                                    break;


                                case "CONDPAISUSER":

                                    if ($value2->{"ruleta_detalle.valor"} == $Usuario->paisId) {
                                        $cumpleCondicionPais = true;
                                    }
                                    $cumpleCondicionCont++;

                                    break;

                                default:

                                    if (stristr($value2->{"ruleta_detalle.tipo"}, 'CONDGAME')) {

                                        $idGame = explode("CONDGAME", $value2->{"ruleta_detalle.tipo"})[1];

                                        if ($TransaccionApi->productoId == $idGame) {

                                            $cumpleCondicion = true;
                                        }
                                        $condicionesProducto++;
                                    }

                                    if (stristr($value2->{"ruleta_detalle.tipo"}, 'CONDPROVIDER')) {

                                        $idProvider = explode("CONDPROVIDER", $value2->{"ruleta_detalle.tipo"})[1];

                                        if ($TransaccionApi->proveedorId == $idProvider) {
                                            $cumpleCondicion = true;
                                        }
                                        $condicionesProducto++;
                                    }

                                    break;
                            }

                        }

                        if ($condicionesProducto == 0 && !$cumpleCondicion) {
                            $cumpleCondicion = true;
                        }

                        if (!$cumpleCondicionPais && $cumpleCondicionCont > 0) {

                            $cumpleCondicion = false;
                        }

                        if ($cumpleCondicion && !$needSubscribe && $creditosConvert > 0) {
                            //print_r("CUMPLECONDICION");

                            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();

                            $UsuarioRuleta = new UsuarioRuleta();
                            $UsuarioRuleta->usuarioId = $UsuarioMandante->usumandanteId;
                            $UsuarioRuleta->ruletaId = $value->{"ruleta_interno.ruleta_id"};
                            $UsuarioRuleta->valor = 0;
                            $UsuarioRuleta->posicion = 0;
                            $UsuarioRuleta->valorBase = 0;
                            $UsuarioRuleta->usucreaId = 0;
                            $UsuarioRuleta->usumodifId = 0;
                            $UsuarioRuleta->estado = "A";
                            $UsuarioRuleta->errorId = 0;
                            $UsuarioRuleta->idExterno = 0;
                            $UsuarioRuleta->mandante = 0;
                            $UsuarioRuleta->version = 0;
                            $UsuarioRuleta->apostado = 0;
                            $UsuarioRuleta->codigo = 0;
                            $UsuarioRuleta->externoId = 0;
                            $UsuarioRuleta->valor = $UsuarioRuleta->valor + $creditosConvert;
                            $UsuarioRuleta->valorBase = ($UsuarioRuleta->valorBase + $TransaccionApi->valor);

                            $UsuarioRuletaMySqlDAO = new UsuarioRuletaMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                            $idUsuRuleta = $UsuarioRuletaMySqlDAO->insert($UsuarioRuleta);

                            $TransjuegoInfo = new TransjuegoInfo();
                            $TransjuegoInfo->productoId = $TransaccionApi->productoId;
                            $TransjuegoInfo->transaccionId = $TransaccionApi->transaccionId;
                            $TransjuegoInfo->tipo = "SORTEO";
                            $TransjuegoInfo->descripcion = $idUsuRuleta;
                            $TransjuegoInfo->valor = $creditosConvert;
                            $TransjuegoInfo->transapiId = $TransaccionApi->transapiId;
                            $TransjuegoInfo->usucreaId = 0;
                            $TransjuegoInfo->usumodifId = 0;

                            $title = '';
                            $messageBody = '';
                            $rouletteName = $value->{"ruleta_interno.nombre"};

                            switch (strtolower($Usuario->idioma)) {
                                case 'es':
                                    $title = 'Notificacion';
                                    $messageBody = "¡ Bien :thumbsup: ! Sumaste {$creditosConvert} puntos en {$rouletteName} :clap:";
                                    break;
                                case 'en':
                                    $title = 'Notification';
                                    $messageBody = "¡ Great :thumbsup: ! You added {$creditosConvert} points in {$rouletteName}  :clap:";
                                    break;
                                case 'pt':
                                    $title = 'Notificação';
                                    $messageBody = "Se liga! :thumbsup: Você somou {$creditosConvert} pontos em {$rouletteName} :clap:";
                                    break;
                            }

                            $UsuarioMensaje = new UsuarioMensaje();
                            $UsuarioMensaje->usufromId = 0;
                            $UsuarioMensaje->usutoId = $UsuarioRuleta->getUsuarioId();
                            $UsuarioMensaje->isRead = 0;
                            $UsuarioMensaje->body = $messageBody;
                            $UsuarioMensaje->msubject = $title;
                            $UsuarioMensaje->parentId = 0;
                            $UsuarioMensaje->proveedorId = 0;
                            $UsuarioMensaje->tipo = "PUSHNOTIFICACION";
                            $UsuarioMensaje->paisId = 0;
                            $UsuarioMensaje->fechaExpiracion = '';

                            $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                            $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);


                            $TransjuegoInfoMySqlDAO->insert($TransjuegoInfo);
                            $TransjuegoInfoMySqlDAO->getTransaction()->commit();

                            $UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

                            //  Enviamos el mensaje Websocket al Usuario para que actualice el saldo

                            $mensajesRecibidos = [];
                            $array = [];

                            $array["body"] = $messageBody;

                            array_push($mensajesRecibidos, $array);
                            $data = array();
                            $data["messages"] = $mensajesRecibidos;


                            $WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
                            $WebsocketUsuario->sendWSMessage();


                            if (in_array($UsuarioMandante->mandante, array('0', 8))) {

                                $dataSend = $data;
                                $WebsocketUsuario = new WebsocketUsuario('', '');
                                $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend);

                            }
                            break;
                        }


                    }


                }


                break;

            case "VIRTUAL":

                $TransaccionApi = new TransaccionApi($ticketId);
                $UsuarioMandante = new UsuarioMandante($TransaccionApi->usuarioId);
                $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

                //print_r($TransaccionApi);
                //print_r($ticketId);

                $rules = [];

                array_push($rules, array("field" => "usuario_ruleta.usuario_id", "data" => "$TransaccionApi->usuarioId", "op" => "eq"));
                array_push($rules, array("field" => "ruleta_interno.estado", "data" => "A", "op" => "eq"));
                array_push($rules, array("field" => "ruleta_interno.fecha_inicio", "data" => "$TransaccionApi->fechaCrea", "op" => "le"));
                array_push($rules, array("field" => "ruleta_interno.fecha_fin", "data" => "$TransaccionApi->fechaCrea", "op" => "ge"));
                array_push($rules, array("field" => "ruleta_interno.tipo", "data" => "4", "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                $json = json_encode($filtro);


                $UsuarioRuleta = new UsuarioRuleta();
                $data = $UsuarioRuleta->getUsuarioRuletasCustomWithoutPosition("usuario_ruleta.*,usuario_mandante.nombres,ruleta_interno.*", "usuario_ruleta.valor", "DESC", 0, 100, $json, true, '');

                //print_r($data);

                $data = json_decode($data);

                $final = [];

                $pos = 1;
                $ruletasAnalizados = '';

                $cumpleCondicion = false;
                foreach ($data->data as $key => $value) {

                    $array = [];
                    $array["Position"] = $pos;
                    $array["Id"] = $value->{"usuario_ruleta.usuruleta_id"};

                    $ruletasAnalizados = $ruletasAnalizados . $value->{"usuario_ruleta.ruleta_id"} . ",";
                    $RuletaInterno = new RuletaInterno();
                    $RuletaDetalle = new RuletaDetalle();

                    //$bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
                    //$bonos = json_decode($bonos);


                    $rules = [];

                    array_push($rules, array("field" => "ruleta_interno.ruleta_id", "data" => $value->{"usuario_ruleta.ruleta_id"}, "op" => "eq"));
                    array_push($rules, array("field" => "ruleta_interno.estado", "data" => "A", "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $json = json_encode($filtro);

                    $ruletadetalles = $RuletaDetalle->getRuletaDetallesCustom(" ruleta_detalle.*,ruleta_interno.* ", "ruleta_interno.ruleta_id", "asc", 0, 1000, $json, TRUE);

                    $ruletadetalles = json_decode($ruletadetalles);

                    $final = [];

                    $creditosConvert = 0;

                    $cumpleCondicionPais = false;
                    $cumpleCondicionCont = 0;

                    $condicionesProducto = 0;

                    foreach ($ruletadetalles->data as $key2 => $value2) {

                        switch ($value2->{"ruleta_detalle.tipo"}) {
                            case "RANK":
                                if ($value2->{"ruleta_detalle.moneda"} == $UsuarioMandante->moneda) {
                                    if ($TransaccionApi->valor >= $value2->{"ruleta_detalle.valor"}) {
                                        if ($TransaccionApi->valor <= $value2->{"ruleta_detalle.valor2"}) {
                                            $creditosConvert = $value2->{"ruleta_detalle.valor3"};
                                        }

                                    }
                                }

                                break;


                            case "CONDPAISUSER":

                                if ($value2->{"ruleta_detalle.valor"} == $Usuario->paisId) {
                                    $cumpleCondicionPais = true;
                                }
                                $cumpleCondicionCont++;

                                break;

                            default:

                                if (stristr($value2->{"ruleta_detalle.tipo"}, 'CONDGAME')) {

                                    $idGame = explode("CONDGAME", $value2->{"ruleta_detalle.tipo"})[1];

                                    if ($TransaccionApi->productoId == $idGame) {
                                        $cumpleCondicion = true;
                                    }
                                    $condicionesProducto++;
                                }

                                if (stristr($value2->{"ruleta_detalle.tipo"}, 'CONDPROVIDER')) {

                                    $idProvider = explode("CONDPROVIDER", $value2->{"ruleta_detalle.tipo"})[1];

                                    if ($TransaccionApi->proveedorId == $idProvider) {
                                        $cumpleCondicion = true;
                                    }
                                    $condicionesProducto++;
                                }


                                break;
                        }

                    }

                    if ($condicionesProducto == 0 && !$cumpleCondicion) {
                        $cumpleCondicion = true;
                    }

                    if (!$cumpleCondicionPais && $cumpleCondicionCont > 0) {
                        $cumpleCondicion = false;
                    }

                    if ($cumpleCondicion) {
                        $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();

                        $UsuarioRuleta = new UsuarioRuleta($value->{"usuario_ruleta.usuruleta_id"});
                        $UsuarioRuleta->valor = $UsuarioRuleta->valor + $creditosConvert;
                        $UsuarioRuleta->valorBase = ($UsuarioRuleta->valorBase + $TransaccionApi->valor);
                        //print_r($TransaccionApi);

                        //print_r($UsuarioRuleta);
                        $UsuarioRuletaMySqlDAO = new UsuarioRuletaMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                        $UsuarioRuletaMySqlDAO->update($UsuarioRuleta);

                        $TransjuegoInfo = new TransjuegoInfo();
                        $TransjuegoInfo->productoId = $TransaccionApi->productoId;
                        $TransjuegoInfo->transaccionId = $TransaccionApi->transaccionId;
                        $TransjuegoInfo->tipo = "SORTEO";
                        $TransjuegoInfo->descripcion = $value->{"usuario_ruleta.usuruleta_id"};
                        $TransjuegoInfo->valor = $creditosConvert;
                        $TransjuegoInfo->transapiId = $TransaccionApi->transapiId;
                        $TransjuegoInfo->usucreaId = 0;
                        $TransjuegoInfo->usumodifId = 0;
                        $TransjuegoInfo->identificador = $TransaccionApi->identificador;

                        $title = '';
                        $messageBody = '';
                        $rouletteName = $value->{"ruleta_interno.nombre"};

                        switch (strtolower($Usuario->idioma)) {
                            case 'es':
                                $title = 'Notificacion';
                                $messageBody = "¡ Bien :thumbsup: ! Sumaste {$creditosConvert} puntos en {$rouletteName} :clap:";
                                break;
                            case 'en':
                                $title = 'Notification';
                                $messageBody = "¡ Great :thumbsup: ! You added {$creditosConvert} points in {$rouletteName}  :clap:";
                                break;
                            case 'pt':
                                $title = 'Notificação';
                                $messageBody = "Se liga! :thumbsup: Você somou {$creditosConvert} pontos em {$rouletteName} :clap:";
                                break;
                        }

                        $UsuarioMensaje = new UsuarioMensaje();
                        $UsuarioMensaje->usufromId = 0;
                        $UsuarioMensaje->usutoId = $UsuarioRuleta->getUsuarioId();
                        $UsuarioMensaje->isRead = 0;
                        $UsuarioMensaje->body = $messageBody;
                        $UsuarioMensaje->msubject = $title;
                        $UsuarioMensaje->parentId = 0;
                        $UsuarioMensaje->proveedorId = 0;
                        $UsuarioMensaje->tipo = "PUSHNOTIFICACION";
                        $UsuarioMensaje->paisId = 0;
                        $UsuarioMensaje->fechaExpiracion = '';

                        $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                        $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);


                        $TransjuegoInfoMySqlDAO->insert($TransjuegoInfo);
                        $TransjuegoInfoMySqlDAO->getTransaction()->commit();

                        $UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

                        //  Enviamos el mensaje Websocket al Usuario para que actualice el saldo

                        $mensajesRecibidos = [];
                        $array = [];

                        $array["body"] = $messageBody;

                        array_push($mensajesRecibidos, $array);
                        $data = array();
                        $data["messages"] = $mensajesRecibidos;


                        $WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
                        $WebsocketUsuario->sendWSMessage();


                        if (in_array($UsuarioMandante->mandante, array('0', 8))) {

                            $dataSend = $data;
                            $WebsocketUsuario = new WebsocketUsuario('', '');
                            $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend);

                        }
                        break;
                    }


                }

                if (!$cumpleCondicion) {

                    if ($ruletasAnalizados != "") {
                        $ruletasAnalizados = $ruletasAnalizados . '0';
                    }

                    $RuletaInterno = new RuletaInterno();

                    $rules = [];

                    if ($ruletasAnalizados != '') {
                        array_push($rules, array("field" => "ruleta_interno.ruleta_id", "data" => "$ruletasAnalizados", "op" => "ni"));
                    }

                    array_push($rules, array("field" => "ruleta_interno.estado", "data" => "A", "op" => "eq"));
                    array_push($rules, array("field" => "ruleta_interno.fecha_inicio", "data" => "$TransaccionApi->fechaCrea", "op" => "le"));
                    array_push($rules, array("field" => "ruleta_interno.fecha_fin", "data" => "$TransaccionApi->fechaCrea", "op" => "ge"));
                    array_push($rules, array("field" => "ruleta_interno.tipo", "data" => "4", "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $json = json_encode($filtro);


                    $data = $RuletaInterno->getRuletasCustom("ruleta_interno.*", "ruleta_interno.orden", "ASC", 0, 1000, $json, true, '');


                    $data = json_decode($data);

                    $final = [];

                    $pos = 1;
                    $ruletasAnalizados = '';

                    foreach ($data->data as $key => $value) {


                        $RuletaDetalle = new RuletaDetalle();

                        //$bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
                        //$bonos = json_decode($bonos);


                        $rules = [];

                        array_push($rules, array("field" => "ruleta_interno.ruleta_id", "data" => $value->{"ruleta_interno.ruleta_id"}, "op" => "eq"));
                        array_push($rules, array("field" => "ruleta_interno.estado", "data" => "A", "op" => "eq"));

                        $filtro = array("rules" => $rules, "groupOp" => "AND");

                        $json = json_encode($filtro);

                        $ruletadetalles = $RuletaDetalle->getRuletaDetallesCustom(" ruleta_detalle.*,ruleta_interno.* ", "ruleta_interno.ruleta_id", "asc", 0, 1000, $json, TRUE);

                        $ruletadetalles = json_decode($ruletadetalles);

                        $final = [];

                        $creditosConvert = 0;

                        $cumpleCondicion = false;
                        $needSubscribe = false;

                        $cumpleCondicionPais = false;
                        $cumpleCondicionCont = 0;

                        $condicionesProducto = 0;

                        foreach ($ruletadetalles->data as $key2 => $value2) {

                            switch ($value2->{"ruleta_detalle.tipo"}) {
                                case "RANK":
                                    if ($value2->{"ruleta_detalle.moneda"} == $UsuarioMandante->moneda) {
                                        if ($TransaccionApi->valor >= $value2->{"ruleta_detalle.valor"}) {
                                            if ($TransaccionApi->valor <= $value2->{"ruleta_detalle.valor2"}) {
                                                $creditosConvert = $value2->{"ruleta_detalle.valor3"};
                                            }

                                        }
                                    }

                                    break;

                                case "USERSUBSCRIBE":

                                    if ($value2->{"ruleta_detalle.valor"} == 0) {

                                    } else {
                                        $needSubscribe = true;
                                    }

                                    break;

                                case "VISIBILIDAD":

                                    if ($value2->{"ruleta_detalle.valor"} == 1) {
                                        $needSubscribe = true;

                                    } else {
                                    }

                                    break;


                                case "CONDPAISUSER":

                                    if ($value2->{"ruleta_detalle.valor"} == $Usuario->paisId) {
                                        $cumpleCondicionPais = true;
                                    }
                                    $cumpleCondicionCont++;

                                    break;

                                default:

                                    if (stristr($value2->{"ruleta_detalle.tipo"}, 'CONDGAME')) {

                                        $idGame = explode("CONDGAME", $value2->{"ruleta_detalle.tipo"})[1];

                                        if ($TransaccionApi->productoId == $idGame) {

                                            $cumpleCondicion = true;
                                        }
                                        $condicionesProducto++;
                                    }

                                    if (stristr($value2->{"ruleta_detalle.tipo"}, 'CONDPROVIDER')) {

                                        $idProvider = explode("CONDPROVIDER", $value2->{"ruleta_detalle.tipo"})[1];

                                        if ($TransaccionApi->proveedorId == $idProvider) {
                                            $cumpleCondicion = true;
                                        }
                                        $condicionesProducto++;
                                    }

                                    break;
                            }

                        }

                        if ($condicionesProducto == 0 && !$cumpleCondicion) {
                            $cumpleCondicion = true;
                        }

                        if (!$cumpleCondicionPais && $cumpleCondicionCont > 0) {

                            $cumpleCondicion = false;
                        }

                        if ($cumpleCondicion && !$needSubscribe && $creditosConvert > 0) {
                            //print_r("CUMPLECONDICION");

                            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();

                            $UsuarioRuleta = new UsuarioRuleta();
                            $UsuarioRuleta->usuarioId = $UsuarioMandante->usumandanteId;
                            $UsuarioRuleta->ruletaId = $value->{"ruleta_interno.ruleta_id"};
                            $UsuarioRuleta->valor = 0;
                            $UsuarioRuleta->posicion = 0;
                            $UsuarioRuleta->valorBase = 0;
                            $UsuarioRuleta->usucreaId = 0;
                            $UsuarioRuleta->usumodifId = 0;
                            $UsuarioRuleta->estado = "A";
                            $UsuarioRuleta->errorId = 0;
                            $UsuarioRuleta->idExterno = 0;
                            $UsuarioRuleta->mandante = 0;
                            $UsuarioRuleta->version = 0;
                            $UsuarioRuleta->apostado = 0;
                            $UsuarioRuleta->codigo = 0;
                            $UsuarioRuleta->externoId = 0;
                            $UsuarioRuleta->valor = $UsuarioRuleta->valor + $creditosConvert;
                            $UsuarioRuleta->valorBase = ($UsuarioRuleta->valorBase + $TransaccionApi->valor);

                            $UsuarioRuletaMySqlDAO = new UsuarioRuletaMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                            $idUsuRuleta = $UsuarioRuletaMySqlDAO->insert($UsuarioRuleta);

                            $TransjuegoInfo = new TransjuegoInfo();
                            $TransjuegoInfo->productoId = $TransaccionApi->productoId;
                            $TransjuegoInfo->transaccionId = $TransaccionApi->transaccionId;
                            $TransjuegoInfo->tipo = "SORTEO";
                            $TransjuegoInfo->descripcion = $idUsuRuleta;
                            $TransjuegoInfo->valor = $creditosConvert;
                            $TransjuegoInfo->transapiId = $TransaccionApi->transapiId;
                            $TransjuegoInfo->usucreaId = 0;
                            $TransjuegoInfo->usumodifId = 0;

                            $title = '';
                            $messageBody = '';
                            $rouletteName = $value->{"ruleta_interno.nombre"};

                            switch (strtolower($Usuario->idioma)) {
                                case 'es':
                                    $title = 'Notificacion';
                                    $messageBody = "¡ Bien :thumbsup: ! Sumaste {$creditosConvert} puntos en {$rouletteName} :clap:";
                                    break;
                                case 'en':
                                    $title = 'Notification';
                                    $messageBody = "¡ Great :thumbsup: ! You added {$creditosConvert} points in {$rouletteName}  :clap:";
                                    break;
                                case 'pt':
                                    $title = 'Notificação';
                                    $messageBody = "Se liga! :thumbsup: Você somou {$creditosConvert} pontos em {$rouletteName} :clap:";
                                    break;
                            }

                            $UsuarioMensaje = new UsuarioMensaje();
                            $UsuarioMensaje->usufromId = 0;
                            $UsuarioMensaje->usutoId = $UsuarioRuleta->getUsuarioId();
                            $UsuarioMensaje->isRead = 0;
                            $UsuarioMensaje->body = $messageBody;
                            $UsuarioMensaje->msubject = $title;
                            $UsuarioMensaje->parentId = 0;
                            $UsuarioMensaje->proveedorId = 0;
                            $UsuarioMensaje->tipo = "PUSHNOTIFICACION";
                            $UsuarioMensaje->paisId = 0;
                            $UsuarioMensaje->fechaExpiracion = '';

                            $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                            $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);


                            $TransjuegoInfoMySqlDAO->insert($TransjuegoInfo);
                            $TransjuegoInfoMySqlDAO->getTransaction()->commit();

                            $mensajesRecibidos = [];
                            $array = [];

                            $array["body"] = $messageBody;

                            array_push($mensajesRecibidos, $array);
                            $data = array();
                            $data["messages"] = $mensajesRecibidos;
                            //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

                            $UsuarioSession = new UsuarioSession();
                            $rules = [];

                            array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                            array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

                            $filtro = array("rules" => $rules, "groupOp" => "AND");
                            $json = json_encode($filtro);


                            $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

                            $usuarios = json_decode($usuarios);

                            $usuariosFinal = [];

                            foreach ($usuarios->data as $key => $value) {

                                $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data);
                                $WebsocketUsuario->sendWSMessage();

                            }


                            if (in_array($UsuarioMandante->mandante, array('0', 8))) {

                                $dataSend = $data;
                                $WebsocketUsuario = new WebsocketUsuario('', '');
                                $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend);

                            }

                            break;
                        }


                    }


                }


                break;

        }
    }

    /**
     * Ejecutar un query
     *
     *
     * @param Objeto transaccion transaccion
     * @param String sql sql
     *
     * @return Array $result resultado de la verificación
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function execQuery($transaccion, $sql)
    {

        $RuletaInternoMySqlDAO = new RuletaInternoMySqlDAO($transaccion);
        $return = $RuletaInternoMySqlDAO->querySQL($sql);
        $return = json_decode(json_encode($return), FALSE);

        return $return;

    }

    /**
     * Ejecutar un update
     *
     *
     * @param Objeto transaccion transaccion
     * @param String sql sql
     *
     * @return Array $result resultado de la verificación
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function execUpdate($transaccion, $sql)
    {

        $RuletaInternoMySqlDAO = new RuletaInternoMySqlDAO($transaccion);
        $return = $RuletaInternoMySqlDAO->queryUpdate($sql);

        return $return;

    }


}

?>
