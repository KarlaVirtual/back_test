<?php namespace Backend\dto;

use Backend\dao\TransjuegoInfoDAO;
use Backend\mysql\ItTicketEncInfo1MySqlDAO;
use Backend\mysql\PreUsuarioSorteoMySqlDAO;
use Backend\mysql\SorteoDetalleMySqlDAO;
use Backend\mysql\SorteoInternoMySqlDAO;
use Backend\mysql\TransjuegoInfoMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\UsuarioSorteoMySqlDAO;
use Backend\websocket\WebsocketUsuario;
use Exception;

/**
 * Clase 'SorteoInterno'
 *
 * Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
 * para la tabla 'SorteoInterno'
 *
 * Ejemplo de uso:
 * $SorteoInterno = new SorteoInterno();
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class SorteoInterno
{

    /**
     * Representación de la columna 'sorteoId' de la tabla 'SorteoInterno'
     *
     * @var string
     */
    var $sorteoId;

    /**
     * Representación de la columna 'fechaInicio' de la tabla 'SorteoInterno'
     *
     * @var string
     */
    var $fechaInicio;

    /**
     * Representación de la columna 'fechaFin' de la tabla 'SorteoInterno'
     *
     * @var string
     */
    var $fechaFin;

    /**
     * Representación de la columna 'descripcion' de la tabla 'SorteoInterno'
     *
     * @var string
     */
    var $descripcion;

    /**
     * Representación de la columna 'nombre' de la tabla 'SorteoInterno'
     *
     * @var string
     */
    var $nombre;

    /**
     * Representación de la columna 'tipo' de la tabla 'SorteoInterno'
     *
     * @var string
     */
    var $tipo;

    /**
     * Representación de la columna 'estado' de la tabla 'SorteoInterno'
     *
     * @var string
     */
    var $estado;

    /**
     * Representación de la columna 'fechaModif' de la tabla 'SorteoInterno'
     *
     * @var string
     */
    var $fechaModif;

    /**
     * Representación de la columna 'fechaCrea' de la tabla 'SorteoInterno'
     *
     * @var string
     */
    var $fechaCrea;

    /**
     * Representación de la columna 'mandante' de la tabla 'SorteoInterno'
     *
     * @var string
     */
    var $mandante;

    /**
     * Representación de la columna 'condicional' de la tabla 'SorteoInterno'
     *
     * @var string
     */
    var $condicional;

    /**
     * Representación de la columna 'orden' de la tabla 'SorteoInterno'
     *
     * @var string
     */
    var $orden;

    /**
     * Representación de la columna 'cupoActual' de la tabla 'SorteoInterno'
     *
     * @var string
     */
    var $cupoActual;

    /**
     * Representación de la columna 'cupoMaximo' de la tabla 'SorteoInterno'
     *
     * @var string
     */
    var $cupoMaximo;

    /**
     * Representación de la columna 'cantidadSorteos' de la tabla 'SorteoInterno'
     *
     * @var string
     */
    var $cantidadSorteos;

    /**
     * Representación de la columna 'maximoSorteos' de la tabla 'SorteoInterno'
     *
     * @var string
     */
    var $maximoSorteos;

    /**
     * Representación de la columna 'usucreaId' de la tabla 'SorteoInterno'
     *
     * @var string
     */
    var $usucreaId;

    /**
     * Representación de la columna 'usumodifId' de la tabla 'SorteoInterno'
     *
     * @var string
     */
    var $usumodifId;

    /**
     * Representación de la columna 'codigo' de la tabla 'SorteoInterno'
     *
     * @var string
     */
    var $codigo;

    /**
     * Representación de la columna 'reglas' de la tabla 'SorteoInterno'
     *
     * @var string
     */
    var $reglas;

    /**
     * Representación de la columna 'pegatinas' de la tabla 'SorteoInterno'
     *
     * @var string
     */

    var $pegatinas;


    /**
     * Representación de la columna 'jsonTemp' de la tabla 'SorteoInterno'
     *
     * @var string
     */

    var $jsonTemp;



    /**
     * Representación de la columna '$habilita_casino' de la tabla 'SorteoInterno'
     *
     * @var string
     */

    var $habilitaCasino;

    /**
     * Representación de la columna '$habilita_deposito' de la tabla 'SorteoInterno'
     *
     * @var string
     */

    var $habilitaDeposito;

    /**
     * Representación de la columna '$habilita_deportivas' de la tabla 'SorteoInterno'
     *
     * @var string
     */

    var $habilitaDeportivas;



    /**
     * Constructor de clase
     *
     *
     * @param String $sorteoId id del sorteo interno
     *
     * @return no
     * @throws Exception si SorteoInterno no existe
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function __construct($sorteoId = "")
    {
        if ($sorteoId != "") {

            $this->sorteoId = $sorteoId;

            $SorteoInternoMySqlDAO = new SorteoInternoMySqlDAO();

            $SorteoInterno = $SorteoInternoMySqlDAO->load($this->sorteoId);


            if ($SorteoInterno != null && $SorteoInterno != "") {
                $this->sorteoId = $SorteoInterno->sorteoId;
                $this->fechaInicio = $SorteoInterno->fechaInicio;
                $this->fechaFin = $SorteoInterno->fechaFin;
                $this->descripcion = $SorteoInterno->descripcion;
                $this->nombre = $SorteoInterno->nombre;
                $this->tipo = $SorteoInterno->tipo;
                $this->estado = $SorteoInterno->estado;
                $this->fechaModif = $SorteoInterno->fechaModif;
                $this->fechaCrea = $SorteoInterno->fechaCrea;
                $this->mandante = $SorteoInterno->mandante;
                $this->usucreaId = $SorteoInterno->usucreaId;
                $this->usumodifId = $SorteoInterno->usumodifId;
                $this->condicional = $SorteoInterno->condicional;
                $this->orden = $SorteoInterno->orden;
                $this->cupoActual = $SorteoInterno->cupoActual;
                $this->cupoMaximo = $SorteoInterno->cupoMaximo;
                $this->cantidadSorteos = $SorteoInterno->cantidadSorteos;
                $this->maximoSorteos = $SorteoInterno->maximoSorteos;
                $this->codigo = $SorteoInterno->codigo;
                $this->reglas = $SorteoInterno->reglas;
                $this->pegatinas = $SorteoInterno->pegatinas;
                $this->jsonTemp = $SorteoInterno->jsonTemp;
                $this->habilitaCasino = $SorteoInterno->habilitaCasino;
                $this->habilitaDeposito = $SorteoInterno->habilitaDeposito;
                $this->habilitaDeportivas = $SorteoInterno->habilitaDeportivas;
            } else {
                throw new Exception("No existe " . get_class($this), "28");
            }

        }
    }


    /**
     * Realizar una consulta en la tabla de sorteos 'SorteoInterno'
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
     * @throws Exception si los sorteos no existen
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function getSorteosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $SorteoInternoMySqlDAO = new SorteoInternoMySqlDAO();

        $sorteos = $SorteoInternoMySqlDAO->querySorteosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($sorteos != null && $sorteos != "") {
            return $sorteos;
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

        $SorteoInternoMySqlDAO = new SorteoInternoMySqlDAO($transaction);
        return $SorteoInternoMySqlDAO->insert($this);

    }

    /**
     * Actualiza la información del objeto SorteoInterno en la base de datos.
     *
     * @param mixed $transaction La transacción activa que se utilizará para la actualización.
     * @return bool Devuelve true si la actualización fue exitosa, false en caso contrario.
     */
    public function update($transaction) {
        $SorteoInternoMySqlDAO = new SorteoInternoMySqlDAO($transaction);
        return $SorteoInternoMySqlDAO->update($this);
    }

    /**
     * Agregar un sorteo en la base de datos
     *
     *
     * @param String tipoSorteo tipoSorteo
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
    public function agregarSorteo($tipoSorteo, $usuarioId, $mandante, $detalles, $transaccion)
    {

        //Verificamos Detalles a tomar en cuenta al momento de verificar si puede ganar un sorteo
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
        $sorteoElegido = 0;
        $sorteoTieneRollower = false;
        $rollowerSorteo = 0;
        $rollowerDeposito = 0;

        //Obtenemos todos los sorteos disponibles
        $sqlSorteos = "select a.sorteo_id sorteo_id,a.tipo,a.fecha_inicio,a.fecha_fin,now() test from sorteo_interno a where a.mandante=" . $mandante . " and now()  between (a.fecha_inicio) and (a.fecha_fin) and a.estado='A' ORDER BY a.orden DESC,a.fecha_crea ASC ";

        if ($CodePromo != "") {
            $sqlSorteos = "select a.sorteo_id,a.tipo,a.fecha_inicio,a.fecha_fin from sorteo_interno a INNER JOIN sorteo_detalle b ON (a.sorteo_id=b.sorteo_id AND b.tipo='CODEPROMO' AND b.valor='" . $CodePromo . "') where a.mandante=" . $mandante . " and now()  between (a.fecha_inicio) and (a.fecha_fin) and a.estado='A' ORDER BY a.orden DESC,a.fecha_crea ASC ";

        }

        $sorteosDisponibles = $this->execQuery($transaccion, $sqlSorteos);


        foreach ($sorteosDisponibles as $sorteo) {


            if (!$cumpleCondiciones) {

                //Obtenemos todos los detalles del sorteo
                $sqlDetalleSorteo = "select * from sorteo_detalle a where a.sorteo_id='" . $sorteo->{"a.sorteo_id"} . "' AND (moneda='' OR moneda='PEN') ";
                $sorteoDetalles = $this->execQuery($transaccion, $sqlDetalleSorteo);

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

                $puederepetirSorteo = false;
                $ganaSorteoId = 0;


                $maximopago = 0;
                $maximodeposito = 0;
                $minimodeposito = 0;
                $valorsorteo = 0;
                $tipoproducto = 0;
                $tiposorteo = "";
                $sorteoTieneRollower = false;
                $tiposaldo = -1;

                if ($tipoSorteo != $sorteo->{"a.tipo"}) {
                    $cumpleCondiciones = false;

                }


                foreach ($sorteoDetalles as $sorteoDetalle) {


                    switch ($sorteoDetalle->{"a.tipo"}) {


                        case "TIPOPRODUCTO":
                            $tipoproducto = $sorteoDetalle->{"a.valor"};


                            break;

                        case "CANTDEPOSITOS":
                            if ($detalleDepositos != ($sorteoDetalle->{"a.valor"} - 1) && $sorteo->{"a.tipo"} == 2) {

                                $cumpleCondiciones = false;

                            }

                            break;

                        case "CONDEFECTIVO":
                            if ($detalleDepositoEfectivo) {
                                if (($sorteoDetalle->{"a.valor"} == "true")) {
                                    $condicionmetodoPago = true;
                                }
                            } else {
                                if (($sorteoDetalle->{"a.valor"} != "true")) {
                                    $condicionmetodoPago = true;
                                }
                            }
                            $condicionmetodoPagocount++;


                            break;


                        case "PORCENTAJE":
                            $tiposorteo = "PORCENTAJE";
                            $valorsorteo = $sorteoDetalle->{"a.valor"};

                            break;


                        case "NUMERODEPOSITO":

                            break;

                        case "MAXJUGADORES":

                            break;


                        case "MAXPAGO":

                            if ($sorteoDetalle->{"a.moneda"} == $detalleMonedaUSER) {

                                $maximopago = $sorteoDetalle->{"a.valor"};

                            }
                            break;

                        case "MAXDEPOSITO":

                            $maximodeposito = $sorteoDetalle->{"a.valor"};
                            if ($sorteoDetalle->{"a.moneda"} == $detalleMonedaUSER) {

                                if ($detalleValorDeposito > $maximodeposito) {
                                    $cumpleCondiciones = false;
                                }
                            }

                            break;

                        case "MINDEPOSITO":

                            $minimodeposito = $sorteoDetalle->{"a.valor"};

                            if ($sorteoDetalle->{"a.moneda"} == $detalleMonedaUSER) {

                                if ($detalleValorDeposito < $minimodeposito) {
                                    $cumpleCondiciones = false;
                                }
                            }

                            break;

                        case "VALORBONO":
                            if ($sorteoDetalle->{"a.moneda"} == $detalleMonedaUSER) {

                                $valorsorteo = $sorteoDetalle->{"a.valor"};
                                $tiposorteo = "VALOR";
                            }
                            break;

                        case "CONDPAYMENT":



                            if ($detalleDepositoMetodoPago == $sorteoDetalle->{"a.valor"}) {
                                $condicionmetodoPago = true;
                            }
                            $condicionmetodoPagocount++;

                            break;

                        case "CONDPAISPV":

                            $condicionPaisPVcount = $condicionPaisPVcount + 1;
                            if ($sorteoDetalle->{"a.valor"} == $detallePaisPV) {
                                $condicionPaisPV = true;
                            }

                            break;

                        case "CONDDEPARTAMENTOPV":

                            $condicionDepartamentoPVcount = $condicionDepartamentoPVcount + 1;
                            if ($sorteoDetalle->{"a.valor"} == $detalleDepartamentoPV) {
                                $condicionDepartamentoPV = true;
                            }

                            break;

                        case "CONDCIUDADPV":

                            $condicionCiudadPVcount = $condicionCiudadPVcount + 1;

                            if ($sorteoDetalle->{"a.valor"} == $detalleCiudadPV) {
                                $condicionCiudadPV = true;
                            }

                            break;

                        case "CONDPAISUSER":

                            $condicionPaisUSERcount = $condicionPaisUSERcount + 1;
                            if ($sorteoDetalle->{"a.valor"} == $detallePaisUSER) {
                                $condicionPaisUSER = true;
                            }

                            break;

                        case "CONDDEPARTAMENTOUSER":

                            $condicionDepartamentoUSERcount = $condicionDepartamentoUSERcount + 1;
                            if ($sorteoDetalle->{"a.valor"} == $detalleDepartamentoUSER) {
                                $condicionDepartamentoUSER = true;
                            }

                            break;

                        case "CONDCIUDADUSER":

                            $condicionCiudadUSERcount = $condicionCiudadUSERcount + 1;

                            if ($sorteoDetalle->{"a.valor"} == $detalleCiudadUSER) {
                                $condicionCiudadUSER = true;
                            }

                            break;

                        case "CONDPUNTOVENTA":

                            $condicionPuntoVentacount = $condicionPuntoVentacount + 1;

                            if ($sorteoDetalle->{"a.valor"} == $detallePuntoVenta) {
                                $condicionPuntoVenta = true;
                            }

                            break;

                        case "EXPDIA":

                            break;

                        case "EXPFECHA":

                            break;

                        case "WFACTORBONO":
                            $sorteoTieneRollower = true;

                            $rollowerSorteo = $sorteoDetalle->{"a.valor"};

                            break;

                        case "WFACTORDEPOSITO":
                            $sorteoTieneRollower = true;
                            $rollowerDeposito = $sorteoDetalle->{"a.valor"};

                            break;

                        case "VALORROLLOWER":
                            if ($sorteoDetalle->{"a.moneda"} == $detalleMonedaUSER) {

                                $sorteoTieneRollower = true;
                                $rollowerValor = $sorteoDetalle->{"a.valor"};
                            }
                            break;
                        case "REPETIRBONO":

                            if ($sorteoDetalle->{"a.valor"} == '1') {
                                $puederepetirSorteo = true;
                            }

                            break;

                        case "WINBONOID":
                            $ganaSorteoId = $sorteoDetalle->{"a.valor"};
                            $tiposorteo = "WINBONOID";
                            $valor_sorteo = 0;

                            break;

                        case "TIPOSALDO":
                            $tiposaldo = $sorteoDetalle->{"a.valor"};

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
                                if ($CodePromo != $sorteoDetalle->{"a.valor"}) {
                                    $condicionTrigger = false;

                                }
                            } else {

                                if ($tipoSorteo == 2) {
                                    $sqlDetalleSorteoPendiente = "SELECT a.ususorteo_id FROM usuario_sorteo a WHERE a.sorteo_id='" . $sorteo->{"a.sorteo_id"} . "' AND a.usuario_id='" . $usuarioId . "' AND a.estado='P'";
                                    $sorteoDetallesPendiente = $this->execQuery($transaccion, $sqlDetalleSorteoPendiente);

                                    if (oldCount($sorteoDetallesPendiente) > 0) {
                                        $condicionTriggerPosterior = $sorteoDetallesPendiente[0]->ususorteo_id;

                                    } else {
                                        $condicionTrigger = false;

                                    }

                                } else {
                                    $condicionTrigger = false;

                                }

                            }

                            break;

                        default:

                            //   if (stristr($sorteodetalle->{'sorteo_detalle.tipo'}, 'CONDGAME')) {
                            //
                            // }
                            //
                            //   if (stristr($sorteodetalle->{'sorteo_detalle.tipo'}, 'ITAINMENT')) {
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


                    if ($puederepetirSorteo) {
                        $sorteoElegido = $sorteo->{"a.sorteo_id"};

                    } else {
                        $sqlRepiteSorteo = "select * from usuario_sorteo a where a.sorteo_id='" . $sorteo->{"a.sorteo_id"} . "' AND a.usuario_id = '" . $usuarioId . "'";
                        $repiteSorteo = $this->execQuery($transaccion, $sqlRepiteSorteo);

                        if ((!$puederepetirSorteo && oldCount($repiteSorteo) == 0)) {
                            $sorteoElegido = $sorteo->{"a.sorteo_id"};
                        } else {
                            $cumpleCondiciones = false;
                        }

                    }


                }

                if ($cumpleCondiciones) {
                    if ($transaccion != '') {
                        if ($tiposorteo == "PORCENTAJE") {

                            $valor_sorteo = ($detalleValorDeposito) * ($valorsorteo) / 100;

                            if ($valor_sorteo > $maximopago) {
                                $valor_sorteo = $maximopago;
                            }

                        } elseif ($tiposorteo == "VALOR") {

                            $valor_sorteo = $valorsorteo;

                        }

                        if ($condicionTriggerPosterior > 0) {
                            $strsql = "UPDATE sorteo_interno SET sorteo_interno.cupo_actual =sorteo_interno.cupo_actual + " . $valor_sorteo . " WHERE sorteo_interno.cupo_maximo >= (sorteo_interno.cupo_actual + " . $valor_sorteo . ") AND sorteo_interno.sorteo_id ='" . $sorteoElegido . "'";

                        } else {
                            $strsql = "UPDATE sorteo_interno SET sorteo_interno.cupo_actual =sorteo_interno.cupo_actual + " . $valor_sorteo . ",sorteo_interno.cantidad_sorteos=sorteo_interno.cantidad_sorteos+1 WHERE (sorteo_interno.cupo_maximo >= (sorteo_interno.cupo_actual + " . $valor_sorteo . ") OR sorteo_interno.cupo_maximo = 0) AND ((sorteo_interno.maximo_sorteos >= (sorteo_interno.cantidad_sorteos+1)) OR sorteo_interno.maximo_sorteos=0) AND sorteo_interno.sorteo_id ='" . $sorteoElegido . "'";

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
                            $sorteoElegido = 0;

                            if ($condicionTriggerPosterior > 0) {
                                $strsql = "UPDATE usuario_sorteo SET usuario_sorteo.estado = 'E',usuario_sorteo.error_id='1' WHERE usuario_sorteo.ususorteo_id ='" . $condicionTriggerPosterior . "'";
                                $resp = $this->execUpdate($transaccion, $strsql);

                            }

                        }

                    }

                }

            }

        }

        $respuesta = array();
        $respuesta["Sorteo"] = 0;
        $respuesta["WinBonus"] = false;


        if ($sorteoElegido != 0 && $tiposorteo != "") {

            if ($tipoSorteo == 2) {
                if ($tiposorteo == "PORCENTAJE") {

                    $valor_sorteo = ($detalleValorDeposito) * ($valorsorteo) / 100;


                    if ($valor_sorteo > $maximopago) {
                        $valor_sorteo = $maximopago;
                    }

                } elseif ($tiposorteo == "VALOR") {

                    $valor_sorteo = $valorsorteo;

                }


                $valorBase = $detalleValorDeposito;

                $strSql = array();
                $contSql = 0;
                $estadoSorteo = 'A';
                $rollowerRequerido = 0;
                $SumoSaldo = false;

                if (!$sorteoTieneRollower) {

                    if ($CodePromo != "" && $tiposorteo == 2) {
                        $estadoSorteo = 'P';

                    } else {
                        if ($ganaSorteoId == 0) {
                            $tipoSorteoS = 'D';
                            switch ($tiposaldo) {
                                case 0:


                                    $contSql = $contSql + 1;
                                    $strSql[$contSql] = "update registro,sorteo_interno set registro.creditos_base_ant=registro.creditos_base,registro.creditos_base=registro.creditos_base+" . $valor_sorteo . " where registro.mandante=" . $mandante . " and registro.usuario_id=" . $usuarioId . " AND sorteo_id ='" . $sorteoElegido . "'";
                                    $estadoSorteo = 'R';
                                    $SumoSaldo = true;

                                    break;

                                case 1:
                                    $contSql = $contSql + 1;
                                    $strSql[$contSql] = "update registro,sorteo_interno set registro.creditos_ant=registro.creditos,registro.creditos=registro.creditos+" . $valor_sorteo . " where registro.mandante=" . $mandante . " and registro.usuario_id=" . $usuarioId . " AND sorteo_id ='" . $sorteoElegido . "'";
                                    $estadoSorteo = 'R';
                                    $SumoSaldo = true;

                                    break;

                                case 2:
                                    $contSql = $contSql + 1;
                                    $strSql[$contSql] = "update registro,sorteo_interno set registro.saldo_especial=registro.saldo_especial+" . $valor_sorteo . " where registro.mandante=" . $mandante . " and registro.usuario_id=" . $usuarioId . " AND sorteo_id ='" . $sorteoElegido . "'";
                                    $estadoSorteo = 'R';
                                    $SumoSaldo = true;

                                    break;

                            }

                        } else {

                            $resp = $this->agregarSorteoFree($ganaSorteoId, $usuarioId, $mandante, $detalles, '', '', $transaccion);

                            if ($transaccion == "") {
                                foreach ($resp->queries as $val) {
                                    $contSql = $contSql + 1;
                                    $strSql[$contSql] = $val;
                                }
                            }

                            $estadoSorteo = 'R';

                        }
                    }


                } else {

                    if ($CodePromo != "" && $tiposorteo == 2) {
                        $estadoSorteo = 'P';

                    } else {
                        //$rollowerDeposito && $ganaSorteoId == 0
                        if ($rollowerDeposito) {
                            $rollowerRequerido = $rollowerRequerido + ($rollowerDeposito * $detalleValorDeposito);
                        }

                        if ($rollowerSorteo) {
                            $rollowerRequerido = $rollowerRequerido + ($rollowerSorteo * $valor_sorteo);

                        }
                        if ($rollowerValor) {
                            $rollowerRequerido = $rollowerRequerido + ($rollowerValor);

                        }

                        $contSql = $contSql + 1;
                        $strSql[$contSql] = "update registro,sorteo_interno set registro.creditos_sorteo=registro.creditos_sorteo+" . $valor_sorteo . " where registro.mandante=" . $mandante . " and registro.usuario_id=" . $usuarioId . "  AND sorteo_id ='" . $sorteoElegido . "'";
                    }


                }

                if ($condicionTriggerPosterior > 0) {


                    $contSql = $contSql + 1;
                    $strSql[$contSql] = "UPDATE usuario_sorteo,sorteo_interno SET usuario_sorteo.valor='" . $valor_sorteo . "',usuario_sorteo.valor_sorteo='" . $valorsorteo . "',usuario_sorteo.valor_base='" . $valorBase . "',usuario_sorteo.estado='" . $estadoSorteo . "',usuario_sorteo.error_id='0',usuario_sorteo.externo_id='0',usuario_sorteo.mandante='" . $mandante . "',usuario_sorteo.rollower_requerido='" . $rollowerRequerido . "' WHERE usuario_sorteo.ususorteo_id = '" . $condicionTriggerPosterior . "' AND usuario_sorteo.sorteo_id ='" . $sorteoElegido . "' AND sorteo_interno.sorteo_id ='" . $sorteoElegido . "'  AND sorteo_interno.sorteo_id ='" . $sorteoElegido . "'";


                    $contSql = $contSql + 1;
                    $strSql[$contSql] = "UPDATE sorteo_interno SET sorteo_interno.cupo_actual =sorteo_interno.cupo_actual + " . $valor_sorteo . " WHERE sorteo_interno.sorteo_id ='" . $sorteoElegido . "'";

                } else {
                    $contSql = $contSql + 1;
                    $strSql[$contSql] = "insert into usuario_sorteo (usuario_id,sorteo_id,valor,valor_sorteo,valor_base,fecha_crea,estado,error_id,externo_id,mandante,rollower_requerido,usucrea_id,usumodif_id) SELECT " . $usuarioId . "," . $sorteoElegido . "," . $valor_sorteo . "," . $valorsorteo . " ," . $valorBase . " ,'" . date('Y-m-d H:i:s') . "','" . $estadoSorteo . "','0',0," . $mandante . ", $rollowerRequerido,0,0 FROM sorteo_interno WHERE  sorteo_id ='" . $sorteoElegido . "'";


                    $contSql = $contSql + 1;
                    $strSql[$contSql] = "UPDATE sorteo_interno SET sorteo_interno.cupo_actual =sorteo_interno.cupo_actual + " . $valor_sorteo . ",sorteo_interno.cantidad_sorteos=sorteo_interno.cantidad_sorteos+1 WHERE sorteo_interno.sorteo_id ='" . $sorteoElegido . "'";
                }

                if ($transaccion != "") {

                    foreach ($strSql as $val) {

                        $resp = $this->execUpdate($transaccion, $val);

                        if ($SumoSaldo && (strpos($val, 'insert into usuario_sorteo') !== false)) {
                            $last_insert_id = $resp;
                            $tibodesorteo = 'F';

                            if ($tipoSorteo == 2) {
                                $tibodesorteo = 'D';

                            }


                            if ($last_insert_id != "" && is_numeric($last_insert_id)) {
                                $sql2 = "insert into sorteo_log (usuario_id,tipo,valor,estado,id_externo,mandante,transaccion_id,tiposorteo_id) values (" . $usuarioId . ",'" . $tibodesorteo . "','" . $valor_sorteo . "','L','" . $last_insert_id . "','0',0,4)";
                                $resp2 = $this->execUpdate($transaccion, $sql2);
                            }

                        }


                    }

                }


                // $contSql = $contSql + 1;
                // $strSql[$contSql] = "insert into sorteo_log (usuario_id,tipo,valor,estado,id_externo,mandante,transaccion_id,tiposorteo_id) values (" . $usuarioId . ",'" . $tipoSorteoS . "','" . $valor_sorteo  . "','L','0'," . $mandante . ",0,4)";


                $respuesta["WinBonus"] = true;
                $respuesta["SumoSaldo"] = $SumoSaldo;
                $respuesta["Sorteo"] = $sorteoElegido;
                $respuesta["Valor"] = $valor_sorteo;
                $respuesta["queries"] = $strSql;
            }

            if ($tipoSorteo == 3) {
                $resp = $this->agregarSorteoFree($sorteoElegido, $usuarioId, $mandante, $detalles, '', '', $transaccion);

                if ($transaccion == '') {
                    foreach ($resp->queries as $val) {
                        $contSql = $contSql + 1;
                        $strSql[$contSql] = $val;
                    }
                }
            }

            if ($tipoSorteo == 6) {

                $resp = $this->agregarSorteoFree($sorteoElegido, $usuarioId, $mandante, $detalles, '', '', $transaccion);

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

    /**
     * Agregar un sorteo en la base de datos
     *
     *
     * @param String tipoSorteo tipoSorteo
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
    public function agregarSorteoFree($sorteoid, $usuarioId, $mandante, $detalles, $ejecutarSQL, $codebonus, $transaccion)
    {

        //Verificamos Detalles a tomar en cuenta al momento de verificar si puede ganar un sorteo
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
        $sorteoElegido = 0;
        $sorteoTieneRollower = false;
        $rollowerSorteo = 0;
        $rollowerDeposito = 0;

        //Obtenemos todos los sorteos disponibles
        $sqlSorteos = "select a.sorteo_id,a.tipo from sorteo_interno a where a.mandante=" . $mandante . " and  a.estado='A' and a.sorteo_id='" . $sorteoid . "'";

        $sorteosDisponibles = $this->execQuery($transaccion, $sqlSorteos);

        foreach ($sorteosDisponibles as $sorteo) {

            if (!$cumpleCondiciones) {

                //Obtenemos todos los detalles del sorteo
                $sqlDetalleSorteo = "select * from sorteo_detalle a where a.sorteo_id='" . $sorteo->{"a.sorteo_id"} . "' AND (moneda='' OR moneda='PEN') ";

                $sorteoDetalles = $this->execQuery($transaccion, $sqlDetalleSorteo);

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

                $puederepetirSorteo = false;
                $ganaSorteoId = 0;


                $maximopago = 0;
                $maximodeposito = 0;
                $minimodeposito = 0;
                $valorsorteo = 0;
                $tipoproducto = 0;
                $tiposorteo = "";
                $tiposorteo2 = $sorteo->{"a.tipo"};
                $sorteoTieneRollower = false;


                foreach ($sorteoDetalles as $sorteoDetalle) {


                    switch ($sorteoDetalle->{"a.tipo"}) {


                        case "TIPOPRODUCTO":
                            $tipoproducto = $sorteoDetalle->{"a.valor"};

                            break;

                        case "CANTDEPOSITOS":
                            if ($detalleDepositos != ($sorteoDetalle->{"a.valor"} - 1) && $sorteo->{"a.tipo"} == 2) {

                                $cumpleCondiciones = false;

                            }

                            break;

                        case "CONDEFECTIVO":
                            if ($detalleDepositoEfectivo) {
                                if (($sorteoDetalle->{"a.valor"} == "true")) {
                                    $condicionmetodoPago = true;
                                }
                            } else {
                                if (($sorteoDetalle->{"a.valor"} != "true")) {
                                    $condicionmetodoPago = true;
                                }
                            }
                            $condicionmetodoPagocount++;


                            break;


                        case "PORCENTAJE":
                            $tiposorteo = "PORCENTAJE";
                            $valorsorteo = $sorteoDetalle->{"a.valor"};

                            break;


                        case "NUMERODEPOSITO":

                            break;

                        case "MAXJUGADORES":

                            break;


                        case "MAXPAGO":
                            $maximopago = $sorteoDetalle->{"a.valor"};

                            break;

                        case "MAXDEPOSITO":

                            $maximodeposito = $sorteoDetalle->{"a.valor"};
                            if ($sorteoDetalle->{"a.moneda"} == $detalleMonedaUSER) {

                                if ($detalleValorDeposito > $maximodeposito) {
                                    $cumpleCondiciones = false;
                                }
                            }
                            break;

                        case "MINDEPOSITO":

                            $minimodeposito = $sorteoDetalle->{"a.valor"};

                            if ($sorteoDetalle->{"a.moneda"} == $detalleMonedaUSER) {

                                if ($detalleValorDeposito < $minimodeposito) {
                                    $cumpleCondiciones = false;
                                }
                            }

                            break;

                        case "VALORBONO":

                            if ($sorteoDetalle->{"a.moneda"} == $detalleMonedaUSER) {

                                $valorsorteo = $sorteoDetalle->{"a.valor"};
                                $tiposorteo = "VALOR";
                            }
                            break;

                        case "CONDPAYMENT":

                            if ($detalleDepositoMetodoPago == $sorteoDetalle->{"a.valor"}) {
                                $condicionmetodoPago = true;
                            }
                            $condicionmetodoPagocount++;

                            break;

                        case "CONDPAISPV":

                            $condicionPaisPVcount = $condicionPaisPVcount + 1;
                            if ($sorteoDetalle->{"a.valor"} == $detallePaisPV) {
                                $condicionPaisPV = true;
                            }

                            break;

                        case "CONDDEPARTAMENTOPV":

                            $condicionDepartamentoPVcount = $condicionDepartamentoPVcount + 1;
                            if ($sorteoDetalle->{"a.valor"} == $detalleDepartamentoPV) {
                                $condicionDepartamentoPV = true;
                            }

                            break;

                        case "CONDCIUDADPV":

                            $condicionCiudadPVcount = $condicionCiudadPVcount + 1;

                            if ($sorteoDetalle->{"a.valor"} == $detalleCiudadPV) {
                                $condicionCiudadPV = true;
                            }

                            break;

                        case "CONDPAISUSER":

                            $condicionPaisUSERcount = $condicionPaisUSERcount + 1;

                            if ($sorteoDetalle->{"a.valor"} == $detallePaisUSER) {

                                $condicionPaisUSER = true;
                            }

                            break;

                        case "CONDDEPARTAMENTOUSER":

                            $condicionDepartamentoUSERcount = $condicionDepartamentoUSERcount + 1;
                            if ($sorteoDetalle->{"a.valor"} == $detalleDepartamentoUSER) {
                                $condicionDepartamentoUSER = true;
                            }

                            break;

                        case "CONDCIUDADUSER":

                            $condicionCiudadUSERcount = $condicionCiudadUSERcount + 1;

                            if ($sorteoDetalle->{"a.valor"} == $detalleCiudadUSER) {
                                $condicionCiudadUSER = true;
                            }

                            break;

                        case "CONDPUNTOVENTA":

                            $condicionPuntoVentacount = $condicionPuntoVentacount + 1;

                            if ($sorteoDetalle->{"a.valor"} == $detallePuntoVenta) {
                                $condicionPuntoVenta = true;
                            }

                            break;

                        case "EXPDIA":

                            break;

                        case "EXPFECHA":

                            break;

                        case "WFACTORBONO":
                            $sorteoTieneRollower = true;

                            $rollowerSorteo = $sorteoDetalle->{"a.valor"};

                            break;

                        case "WFACTORDEPOSITO":
                            $sorteoTieneRollower = true;
                            $rollowerDeposito = $sorteoDetalle->{"a.valor"};

                            break;


                        case "VALORROLLOWER":
                            if ($sorteoDetalle->{"a.moneda"} == $detalleMonedaUSER) {

                                $sorteoTieneRollower = true;
                                $rollowerValor = $sorteoDetalle->{"a.valor"};
                            }
                            break;

                        case "REPETIRBONO":

                            if ($sorteoDetalle->{"a.valor"} == '1') {
                                $puederepetirSorteo = true;
                            }

                            break;

                        case "WINBONOID":
                            $ganaSorteoId = $sorteoDetalle->{"a.valor"};

                            break;


                        case "TIPOSALDO":
                            $tiposaldo = $sorteoDetalle->{"a.valor"};

                            break;

                        case "LIVEORPREMATCH":

                            break;

                        case "MINSELCOUNT":

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

                        default:

                            //   if (stristr($sorteodetalle->{'sorteo_detalle.tipo'}, 'CONDGAME')) {
                            //
                            // }
                            //
                            //   if (stristr($sorteodetalle->{'sorteo_detalle.tipo'}, 'ITAINMENT')) {
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

                    if ($puederepetirSorteo) {

                        $sorteoElegido = $sorteo->{"a.sorteo_id"};

                    } else {
                        $sqlRepiteSorteo = "select * from usuario_sorteo a where a.sorteo_id='" . $sorteo->{"a.sorteo_id"} . "' AND a.usuario_id = '" . $usuarioId . "'";
                        $repiteSorteo = $this->execQuery($transaccion, $sqlRepiteSorteo);

                        if ((!$puederepetirSorteo && oldCount($repiteSorteo) == 0)) {
                            $sorteoElegido = $sorteo->{"a.sorteo_id"};
                        } else {
                            $cumpleCondiciones = false;
                        }
                    }


                }


            }


        }

        $respuesta = array();
        $respuesta["Sorteo"] = 0;
        $respuesta["WinBonus"] = false;


        if ($sorteoElegido != 0 && $tiposorteo2 != "") {

            if ($tiposorteo == "PORCENTAJE") {
                $valor_sorteo = ($detalleValorDeposito) * ($valorsorteo) / 100;

                if ($valor_sorteo > $maximopago) {
                    $valor_sorteo = $maximopago;
                }

            } elseif ($tiposorteo == "VALOR") {

                $valor_sorteo = $valorsorteo;

            }

            $valorBase = $detalleValorDeposito;

            $strSql = array();
            $contSql = 0;
            $estadoSorteo = 'A';
            $rollowerRequerido = 0;

            if (!$sorteoTieneRollower) {


            } else {
                if ($rollowerDeposito) {
                    $rollowerRequerido = $rollowerRequerido + ($rollowerDeposito * $detalleValorDeposito);
                }

                if ($rollowerSorteo) {
                    $rollowerRequerido = $rollowerRequerido + ($rollowerSorteo * $valor_sorteo);

                }
                if ($rollowerValor) {
                    $rollowerRequerido = $rollowerRequerido + ($rollowerValor);


                }

            }

            $strCodeBonus = "";

            if ($codebonus != "") {
                $strCodeBonus = " AND a.codigo ='" . $codebonus . "'";

            }


            if ($tiposorteo2 == "5") {

                $sqlSorteosFree = "select a.sorteo_id,a.usuario_id,a.estado from usuario_sorteo a INNER JOIN sorteo_interno b ON(a.sorteo_id = b.sorteo_id) where  a.estado='L' and a.sorteo_id='" . $sorteoid . "'" . $strCodeBonus;

                $sorteosFree = $this->execQuery($transaccion, $sqlSorteosFree);

                $ganoSorteoBool = false;

                foreach ($sorteosFree as $sorteoF) {

                    $sqlSorteosFree = "select a.ususorteo_id from usuario_sorteo a INNER JOIN sorteo_interno b ON(a.sorteo_id = b.sorteo_id) where  a.estado='L' and a.sorteo_id='" . $sorteoid . "'" . $strCodeBonus;
                    $sorteosFreeLibres = $this->execQuery($transaccion, $sqlSorteosFree);
                    foreach ($sorteosFreeLibres as $sorteoLibre) {
                        if (!$ganoSorteoBool) {
                            if ($transaccion == "") {
                                $contSql = $contSql + 1;
                                $strSql[$contSql] = "UPDATE usuario_sorteo a SET a.usuario_id='" . $usuarioId . "',a.fecha_crea = '" . date('Y-m-d H:i:s') . "',a.estado='A' WHERE a.usuario_id='0' AND a.estado='L' AND a.ususorteo_id='" . $sorteoLibre->ususorteo_id . "'";
                                $ganoSorteoBool = true;

                            } else {
                                $sqlstr = "UPDATE usuario_sorteo a SET a.usuario_id='" . $usuarioId . "',a.fecha_crea = '" . date('Y-m-d H:i:s') . "',a.estado='A' WHERE a.usuario_id='0' AND a.estado='L' AND a.ususorteo_id='" . $sorteoLibre->ususorteo_id . "'";

                                $q = $this->execUpdate($transaccion, $sqlstr);
                                if ($q > 0) {
                                    $ganoSorteoBool = true;

                                } else {
                                    $ganoSorteoBool = false;

                                }

                            }

                        }
                    }

                }
            } elseif ($tiposorteo2 == "6") {
                $sqlSorteosFree = "select a.sorteo_id from usuario_sorteo a INNER JOIN sorteo_interno b ON(a.sorteo_id = b.sorteo_id) where  a.estado='L' and a.sorteo_id='" . $sorteoid . "'" . $strCodeBonus;
                $sorteosFree = $this->execQuery($transaccion, $sqlSorteosFree);

                $ganoSorteoBool = false;

                foreach ($sorteosFree as $sorteoF) {

                    $sqlSorteosFree = "select a.ususorteo_id from usuario_sorteo a INNER JOIN sorteo_interno b ON(a.sorteo_id = b.sorteo_id) where  a.estado='L' and a.sorteo_id='" . $sorteoid . "'" . $strCodeBonus;
                    $sorteosFreeLibres = $this->execQuery($transaccion, $sqlSorteosFree);
                    foreach ($sorteosFreeLibres as $sorteoLibre) {
                        if (!$ganoSorteoBool) {
                            if ($transaccion == "") {

                                $contSql = $contSql + 1;
                                $strSql[$contSql] = "UPDATE usuario_sorteo a SET a.usuario_id='" . $usuarioId . "',a.fecha_crea = '" . date('Y-m-d H:i:s') . "', a.estado='A' WHERE a.usuario_id='0' AND a.estado='L' AND a.ususorteo_id='" . $sorteoLibre->ususorteo_id . "'";
                                $ganoSorteoBool = true;
                            } else {
                                $sqlstr = "UPDATE usuario_sorteo a SET a.usuario_id='" . $usuarioId . "',a.fecha_crea = '" . date('Y-m-d H:i:s') . "', a.estado='A' WHERE a.usuario_id='0' AND a.estado='L' AND a.ususorteo_id='" . $sorteoLibre->ususorteo_id . "'";

                                $q = $this->execUpdate($transaccion, $sqlstr);
                                if ($q > 0) {
                                    $ganoSorteoBool = true;

                                } else {
                                    $ganoSorteoBool = false;

                                }

                            }

                        }
                    }

                }
            } elseif ($tiposorteo2 == "3") {

                $valor_sorteo = $maximopago;


                $ganoSorteoBool = false;


                $sqlSorteosFree = "select a.ususorteo_id from usuario_sorteo a INNER JOIN sorteo_interno b ON(a.sorteo_id = b.sorteo_id) where  a.estado='L' and a.sorteo_id='" . $sorteoid . "'" . $strCodeBonus;
                $sorteosFreeLibres = $this->execQuery($transaccion, $sqlSorteosFree);

                foreach ($sorteosFreeLibres as $sorteoLibre) {
                    if (!$ganoSorteoBool) {

                        if (!$sorteoTieneRollower) {
                            $estadoSorteo = 'R';
                        } else {
                            if ($rollowerDeposito) {
                                $rollowerRequerido = $rollowerRequerido + ($rollowerDeposito * $detalleValorDeposito);
                            }

                            if ($rollowerSorteo) {
                                $rollowerRequerido = $rollowerRequerido + ($rollowerSorteo * $valor_sorteo);

                            }
                            if ($rollowerValor) {
                                $rollowerRequerido = $rollowerRequerido + ($rollowerValor);


                            }

                        }


                        if ($transaccion == '') {

                            if (!$sorteoTieneRollower) {


                                if ($ganaSorteoId == 0) {
                                    switch ($tiposaldo) {
                                        case 0:
                                            $contSql = $contSql + 1;
                                            $strSql[$contSql] = "update registro set creditos_base_ant=creditos_base,creditos_base=creditos_base+" . $valor_sorteo . " where mandante=" . $mandante . " and usuario_id=" . $usuarioId;


                                            $estadoSorteo = 'R';

                                            break;

                                        case 1:
                                            $contSql = $contSql + 1;
                                            $strSql[$contSql] = "update registro set creditos_ant=creditos,creditos=creditos+" . $valor_sorteo . " where mandante=" . $mandante . " and usuario_id=" . $usuarioId;


                                            $estadoSorteo = 'R';

                                            break;

                                        case 2:
                                            $contSql = $contSql + 1;
                                            $strSql[$contSql] = "update registro set saldo_especial=saldo_especial+" . $valor_sorteo . " where mandante=" . $mandante . " and usuario_id=" . $usuarioId;

                                            $estadoSorteo = 'R';
                                            $SumoSaldo = true;

                                            break;

                                    }

                                } else {

                                    $resp = $this->agregarSorteoFree($ganaSorteoId, $usuarioId, $mandante, $detalles, '', '', $transaccion);

                                    if ($transaccion == "") {
                                        foreach ($resp->queries as $val) {
                                            $contSql = $contSql + 1;
                                            $strSql[$contSql] = $val;
                                        }
                                    }

                                    $estadoSorteo = 'R';

                                }

                            } else {
                                if ($rollowerDeposito) {
                                    $rollowerRequerido = $rollowerRequerido + ($rollowerDeposito * $detalleValorDeposito);
                                }

                                if ($rollowerSorteo) {
                                    $rollowerRequerido = $rollowerRequerido + ($rollowerSorteo * $valor_sorteo);

                                }
                                if ($rollowerValor) {
                                    $rollowerRequerido = $rollowerRequerido + ($rollowerValor);


                                }
                                $contSql = $contSql + 1;
                                $strSql[$contSql] = "update registro,sorteo_interno set registro.creditos_sorteo=registro.creditos_sorteo+" . $valor_sorteo . " where registro.mandante=" . $mandante . " and registro.usuario_id=" . $usuarioId . " AND sorteo_id ='" . $sorteoElegido . "'";

                            }

                            $contSql = $contSql + 1;
                            $strSql[$contSql] = "UPDATE usuario_sorteo a SET a.usuario_id='" . $usuarioId . "',a.fecha_crea = '" . date('Y-m-d H:i:s') . "',a.rollower_requerido='" . $rollowerRequerido . "',a.valor_sorteo='" . $valor_sorteo . "',a.valor='" . $valor_sorteo . "', a.estado='" . $estadoSorteo . "' WHERE a.usuario_id='0' AND a.estado='L' AND a.ususorteo_id='" . $sorteoLibre->ususorteo_id . "'";

                            $contSql = $contSql + 1;
                            $strSql[$contSql] = "INSERT INTO sorteo_log (usuario_id,tipo,valor,estado,id_externo,mandante,transaccion_id,tiposorteo_id,fecha_crea,fecha_cierre) SELECT a.usuario_id,CASE WHEN b.tipo = 2 THEN 'D' ELSE 'F' END,a.valor,'L',a.ususorteo_id,0,'0',4,now(),now()  FROM  usuario_sorteo a INNER JOIN sorteo_interno  b ON (b.sorteo_id = a.sorteo_id)  WHERE a.ususorteo_id = " . $sorteoLibre->ususorteo_id . " AND a.apostado >= a.rollower_requerido";

                            $ganoSorteoBool = true;

                        } else {
                            $sqlstr = "UPDATE usuario_sorteo a SET a.usuario_id='" . $usuarioId . "',a.fecha_crea = '" . date('Y-m-d H:i:s') . "',a.rollower_requerido='" . $rollowerRequerido . "',a.valor_sorteo='" . $valor_sorteo . "',a.valor='" . $valor_sorteo . "', a.estado='" . $estadoSorteo . "' WHERE a.usuario_id='0' AND a.estado='L' AND a.ususorteo_id='" . $sorteoLibre->ususorteo_id . "'";

                            $q = $this->execUpdate($transaccion, $sqlstr);
                            if ($q > 0) {

                                $sqlstr = "INSERT INTO sorteo_log (usuario_id,tipo,valor,estado,id_externo,mandante,transaccion_id,tiposorteo_id,fecha_crea,fecha_cierre) SELECT a.usuario_id,CASE WHEN b.tipo = 2 THEN 'D' ELSE 'F' END,a.valor,'L',a.ususorteo_id,0,'0',4,now(),now()  FROM  usuario_sorteo a INNER JOIN sorteo_interno  b ON (b.sorteo_id = a.sorteo_id)  WHERE a.ususorteo_id = " . $sorteoLibre->ususorteo_id . " AND a.apostado >= a.rollower_requerido";

                                $q = $this->execUpdate($transaccion, $sqlstr);


                                if (!$sorteoTieneRollower) {


                                    if ($ganaSorteoId == 0) {
                                        switch ($tiposaldo) {
                                            case 0:
                                                $sqlstr = "update registro set creditos_base_ant=creditos_base,creditos_base=creditos_base+" . $valor_sorteo . " where mandante=" . $mandante . " and usuario_id=" . $usuarioId;
                                                $q = $this->execUpdate($transaccion, $sqlstr);


                                                $estadoSorteo = 'R';

                                                break;

                                            case 1:
                                                $sqlstr = "update registro set creditos_ant=creditos,creditos=creditos+" . $valor_sorteo . " where mandante=" . $mandante . " and usuario_id=" . $usuarioId;

                                                $q = $this->execUpdate($transaccion, $sqlstr);

                                                $estadoSorteo = 'R';

                                                break;

                                            case 2:
                                                $sqlstr = "update registro set saldo_especial=saldo_especial+" . $valor_sorteo . " where mandante=" . $mandante . " and usuario_id=" . $usuarioId;
                                                $q = $this->execUpdate($transaccion, $sqlstr);

                                                $estadoSorteo = 'R';
                                                $SumoSaldo = true;

                                                break;

                                        }

                                    } else {

                                        $resp = $this->agregarSorteoFree($ganaSorteoId, $usuarioId, $mandante, $detalles, '', '', $transaccion);

                                        if ($transaccion == "") {
                                            foreach ($resp->queries as $val) {
                                                $contSql = $contSql + 1;
                                                $strSql[$contSql] = $val;
                                            }
                                        }

                                        $estadoSorteo = 'R';

                                    }

                                } else {
                                    if ($rollowerDeposito) {
                                        $rollowerRequerido = $rollowerRequerido + ($rollowerDeposito * $detalleValorDeposito);
                                    }

                                    if ($rollowerSorteo) {
                                        $rollowerRequerido = $rollowerRequerido + ($rollowerSorteo * $valor_sorteo);

                                    }
                                    if ($rollowerValor) {
                                        $rollowerRequerido = $rollowerRequerido + ($rollowerValor);


                                    }

                                    $sqlstr = "update registro,sorteo_interno set registro.creditos_sorteo=registro.creditos_sorteo+" . $valor_sorteo . " where registro.mandante=" . $mandante . " and registro.usuario_id=" . $usuarioId . " AND sorteo_id ='" . $sorteoElegido . "'";

                                    $q = $this->execUpdate($transaccion, $sqlstr);

                                }

                                $ganoSorteoBool = true;


                            } else {
                                $ganoSorteoBool = false;

                            }
                        }


                    }
                }

            }


            $respuesta["WinBonus"] = true;
            $respuesta["Sorteo"] = $sorteoElegido;
            $respuesta["queries"] = $strSql;


            if ($transaccion != "") {

            } else {
                if ($ejecutarSQL) {
                    foreach ($respuesta["queries"] as $querie) {

                        $transaccionNueva = false;
                        if ($transaccion == '') {
                            $SorteoInternoMySqlDAO = new SorteoInternoMySqlDAO();
                            $transaccion = $SorteoInternoMySqlDAO->getTransaction();
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
     * Verficiar rollower en el sorteo
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
    public function verificarSorteoRollower($usuarioId, $detalles, $tipoProducto, $ticketId)
    {

        //Verificamos Detalles a tomar en cuenta al momento de verificar y aumentar rollower
        $detalleTipoApuesta = $detalles->TipoApuesta;
        $detalleSelecciones = $detalles->Selecciones;
        $detalleJuegosCasino = $detalles->JuegosCasino;
        $detalleValorApuesta = $detalles->ValorApuesta;
        $detalleCuotaTotal = 1;

        $respuesta = array();
        $respuesta["Sorteo"] = 0;
        $respuesta["WinBonus"] = false;


        if (($tipoProducto == "SPORT" || $tipoProducto == "CASINO") && $usuarioId != "") {
            $sorteoid = 0;
            $ususorteo_id = 0;
            $valorASumar = 0;

            //Obtenemos todos los sorteos disponibles
            $sqlSorteo = "select a.ususorteo_id,a.sorteo_id,a.apostado,a.rollower_requerido,a.fecha_crea,sorteo_interno.condicional,sorteo_interno.tipo from usuario_sorteo a INNER JOIN sorteo_interno ON (sorteo_interno.sorteo_id = a.sorteo_id ) where  a.estado='A' AND (sorteo_interno.tipo = 2 OR sorteo_interno.tipo = 3) AND a.usuario_id='" . $usuarioId . "'";
            $sorteosDisponibles = $this->execQuery($sqlSorteo);

            if (oldCount($sorteosDisponibles) > 0) {
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

            foreach ($sorteosDisponibles as $sorteo) {
                if ($sorteoid == 0) {

                    //Obtenemos todos los detalles del sorteo
                    $sqlDetalleSorteo = "select * from sorteo_detalle a where a.sorteo_id='" . $sorteo->{"a.sorteo_id"} . "' AND (moneda='' OR moneda='PEN') ";
                    $sorteoDetalles = $this->execQuery($sqlDetalleSorteo);


                    //Inicializamos variables
                    $cumplecondicion = true;
                    $cumplecondicionproducto = false;
                    $condicionesproducto = 0;
                    $sorteoid = 0;
                    $valorapostado = 0;
                    $valorrequerido = 0;
                    $valorASumar = 0;

                    $sePuedeSimples = 0;
                    $sePuedeCombinadas = 0;
                    $minselcount = 0;

                    $ganaSorteoId = 0;
                    $tiposorteo = "";
                    $ganaSorteoId = 0;

                    if ($sorteo->{"a.condicional"} == 'NA' || $sorteo->{"a.condicional"} == '') {
                        $tipocomparacion = "OR";

                    } else {
                        $tipocomparacion = $sorteo->{"a.condicional"};

                    }


                    foreach ($sorteoDetalles as $sorteoDetalle) {

                        switch ($sorteoDetalle->{"a.tipo"}) {

                            case "TIPOPRODUCTO":

                                $tipoProducto = $sorteoDetalle->{"a.valor"};
                                break;

                            case "EXPDIA":
                                $fechaSorteo = date('Y-m-d H:i:ss', strtotime($sorteo->{"fecha_crea"} . ' + ' . $sorteoDetalle->{"a.valor"} . ' days'));
                                $fecha_actual = date("Y-m-d H:i:ss", time());

                                if ($fechaSorteo < $fecha_actual) {
                                    $cumplecondicion = false;
                                }

                                break;

                            case "EXPFECHA":
                                $fechaSorteo = date('Y-m-d H:i:ss', strtotime($sorteoDetalle->{"a.valor"}));
                                $fecha_actual = strtotime(date("Y-m-d H:i:ss", time()));

                                if ($fechaSorteo < $fecha_actual) {
                                    $cumplecondicion = false;
                                }
                                break;


                            case "LIVEORPREMATCH":


                                if ($sorteoDetalle->{"a.valor"} == 2) {
                                    if ($betmode == "PreLive") {
                                        $cumplecondicionproducto = true;

                                    } else {
                                        $cumplecondicionproducto = false;


                                    }

                                }

                                if ($sorteoDetalle->{"a.valor"} == 1) {
                                    if ($betmode == "Live") {
                                        $cumplecondicionproducto = true;

                                    } else {
                                        $cumplecondicionproducto = false;


                                    }

                                }

                                if ($sorteoDetalle->{"a.valor"} == 0) {
                                    /*if($betmode == "Mixed") {
                                        $cumplecondicionproducto = true;

                                    }else{
                                        $cumplecondicionproducto = false;


                                    }*/

                                }

                                break;

                            case "MINSELCOUNT":
                                $minselcount = $sorteoDetalle->{"a.valor"};

                                if ($sorteoDetalle->{"a.valor"} > oldCount($detalleSelecciones)) {
                                    //$cumplecondicion = false;

                                }

                                break;

                            case "MINSELPRICE":

                                foreach ($detalleSelecciones as $item) {
                                    if ($sorteoDetalle->{"a.valor"} > $item->Cuota) {
                                        $cumplecondicion = false;

                                    }
                                }


                                break;


                            case "MINSELPRICETOTAL":

                                if ($sorteoDetalle->{"a.valor"} > $detalleCuotaTotal) {
                                    $cumplecondicion = false;

                                }


                                break;

                            case "MINBETPRICE":


                                if ($sorteoDetalle->{"a.valor"} > $detalleValorApuesta) {
                                    $cumplecondicion = false;

                                }

                                break;

                            case "WINBONOID":
                                $ganaSorteoId = $sorteoDetalle->{"a.valor"};
                                $tiposorteo = "WINBONOID";
                                $valor_sorteo = 0;

                                break;

                            case "TIPOSALDO":
                                $tiposaldo = $sorteoDetalle->{"a.valor"};

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
                                        if ($sorteoDetalle->{"a.valor"} == $item->Deporte || $sorteoDetalle->{"a.valor"} == '') {
                                            $cumplecondicionproducto = true;

                                        }
                                    } elseif ($tipocomparacion == "AND") {
                                        if ($sorteoDetalle->{"a.valor"} != $item->Deporte &&  $sorteoDetalle->{"a.valor"} != '') {
                                            $cumplecondicionproducto = false;


                                        }

                                        if ($condicionesproducto == 0) {
                                            if ($sorteoDetalle->{"a.valor"} == $item->Deporte || $sorteoDetalle->{"a.valor"} == '') {
                                                $cumplecondicionproducto = true;
                                            }
                                        } else {
                                            if (($sorteoDetalle->{"a.valor"} == $item->Deporte  || $sorteoDetalle->{"a.valor"} == '') && $cumplecondicionproducto) {
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
                                        if ($sorteoDetalle->{"a.valor"} == $item->Liga || $sorteoDetalle->{"a.valor"} == '') {
                                            $cumplecondicionproducto = true;

                                        }
                                    } elseif ($tipocomparacion == "AND") {
                                        if ($sorteoDetalle->{"a.valor"} != $item->Liga && $sorteoDetalle->{"a.valor"} != '') {
                                            $cumplecondicionproducto = false;

                                        }

                                        if ($condicionesproducto == 0) {
                                            if ($sorteoDetalle->{"a.valor"} == $item->Liga  || $sorteoDetalle->{"a.valor"} == '') {
                                                $cumplecondicionproducto = true;
                                            }
                                        } else {
                                            if (($sorteoDetalle->{"a.valor"} == $item->Liga  || $sorteoDetalle->{"a.valor"} == '') && $cumplecondicionproducto) {
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
                                        if ($sorteoDetalle->{"a.valor"} == $item->Evento  || $sorteoDetalle->{"a.valor"} == '') {
                                            $cumplecondicionproducto = true;

                                        }
                                    } elseif ($tipocomparacion == "AND") {
                                        if ($sorteoDetalle->{"a.valor"} != $item->Evento  && $sorteoDetalle->{"a.valor"} != '') {
                                            $cumplecondicionproducto = false;

                                        }

                                        if ($condicionesproducto == 0) {

                                            if ($sorteoDetalle->{"a.valor"} == $item->Evento  || $sorteoDetalle->{"a.valor"} == '') {
                                                $cumplecondicionproducto = true;
                                            }
                                        } else {

                                            if (($sorteoDetalle->{"a.valor"} == $item->Evento  || $sorteoDetalle->{"a.valor"} == '') && $cumplecondicionproducto) {
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
                                        if ($sorteoDetalle->{"a.valor"} == $item->DeporteMercado || $sorteoDetalle->{"a.valor"} == '') {
                                            $cumplecondicionproducto = true;

                                        }
                                    } elseif ($tipocomparacion == "AND") {
                                        if ($sorteoDetalle->{"a.valor"} != $item->DeporteMercado  && $sorteoDetalle->{"a.valor"} != '') {
                                            $cumplecondicionproducto = false;

                                        }

                                        if ($condicionesproducto == 0) {
                                            if ($sorteoDetalle->{"a.valor"} == $item->DeporteMercado  || $sorteoDetalle->{"a.valor"} == '') {
                                                $cumplecondicionproducto = true;
                                            }
                                        } else {
                                            if (($sorteoDetalle->{"a.valor"} == $item->DeporteMercado  || $sorteoDetalle->{"a.valor"} == '') && $cumplecondicionproducto) {
                                                $cumplecondicionproducto = true;

                                            }
                                        }

                                    }

                                }

                                $condicionesproducto++;

                                break;

                            case "ITAINMENT82":

                                if ($sorteoDetalle->{"a.valor"} == 1) {
                                    $sePuedeSimples = 1;

                                }
                                if ($sorteoDetalle->{"a.valor"} == 2) {
                                    $sePuedeCombinadas = 1;

                                }
                                break;

                            default:
                                if (stristr($sorteoDetalle->{"a.tipo"}, 'CONDGAME')) {

                                    $idGame = explode("CONDGAME", $sorteoDetalle->{"a.tipo"})[1];

                                    foreach ($detalleJuegosCasino as $item) {
                                        if ($idGame == $item->Id) {
                                            $cumplecondicionproducto = true;

                                            $valorASumar = $valorASumar + (($detalleValorApuesta * $sorteoDetalle->{"a.valor"}) / 100);

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

                        $sorteoid = $sorteo->{"a.sorteo_id"};
                        $ususorteo_id = $sorteo->{"ususorteo_id"};
                        $valorapostado = $sorteo->{"apostado"};
                        $valorrequerido = $sorteo->{"rollower_requerido"};

                    }
                }

            }

            if ($sorteoid != 0) {


                if ($tipoProducto == 2) {
                    $valorASumar = $detalleValorApuesta;

                }


                if (($valorapostado + $detalleValorApuesta) >= $valorrequerido) {
                    $winBonus = true;
                }

                $strSql = array();
                $contSql = 0;

                $contSql = $contSql + 1;
                $strSql[$contSql] = "UPDATE usuario_sorteo SET apostado = apostado + " . ($valorASumar) . " WHERE ususorteo_id =" . $ususorteo_id;

                $contSql = $contSql + 1;
                $strSql[$contSql] = "INSERT INTO it_ticket_enc_info1 (ticket_id,tipo,valor)  VALUES ( " . $ticketId . ",'ROLLOWER'," . $ususorteo_id . ") ";


                if ($ganaSorteoId == 0) {
                    switch ($tiposaldo) {
                        case 0:
                            $contSql = $contSql + 1;
                            $strSql[$contSql] = "UPDATE usuario_sorteo,registro SET usuario_sorteo.estado = 'R',registro.creditos_base_ant=registro.creditos_base,registro.creditos_base=registro.creditos_base + usuario_sorteo.valor,registro.creditos_sorteo=registro.creditos_sorteo - usuario_sorteo.valor   WHERE  registro.usuario_id= usuario_sorteo.usuario_id AND usuario_sorteo.apostado >= usuario_sorteo.rollower_requerido AND usuario_sorteo.ususorteo_id = " . $ususorteo_id . " AND usuario_sorteo.estado='A'";
                            $contSql = $contSql + 1;
                            $strSql[$contSql] = "INSERT INTO sorteo_log (usuario_id,tipo,valor,estado,id_externo,mandante,transaccion_id,tiposorteo_id,fecha_crea,fecha_cierre) SELECT a.usuario_id,CASE WHEN b.tipo = 2 THEN 'D' ELSE 'F' END,a.valor,'L',a.ususorteo_id,0,'0',4,now(),now()  FROM  usuario_sorteo a INNER JOIN sorteo_interno  b ON (b.sorteo_id = a.sorteo_id)  WHERE a.ususorteo_id = " . $ususorteo_id . " AND a.apostado >= a.rollower_requerido AND a.estado='R'";

                            break;

                        case 1:
                            $contSql = $contSql + 1;
                            $strSql[$contSql] = "UPDATE usuario_sorteo,registro SET usuario_sorteo.estado = 'R',registro.creditos_ant=registro.creditos,registro.creditos=registro.creditos + usuario_sorteo.valor,registro.creditos_sorteo=registro.creditos_sorteo - usuario_sorteo.valor   WHERE  registro.usuario_id= usuario_sorteo.usuario_id AND usuario_sorteo.apostado >= usuario_sorteo.rollower_requerido AND usuario_sorteo.ususorteo_id = " . $ususorteo_id . " AND usuario_sorteo.estado='A'";
                            $estadoSorteo = 'R';
                            $contSql = $contSql + 1;
                            $strSql[$contSql] = "INSERT INTO sorteo_log (usuario_id,tipo,valor,estado,id_externo,mandante,transaccion_id,tiposorteo_id,fecha_crea,fecha_cierre) SELECT a.usuario_id,CASE WHEN b.tipo = 2 THEN 'D' ELSE 'F' END,a.valor,'L',a.ususorteo_id,0,'0',4,now(),now()  FROM  usuario_sorteo a  INNER JOIN sorteo_interno  b ON (b.sorteo_id = a.sorteo_id)  WHERE a.ususorteo_id = " . $ususorteo_id . " AND a.apostado >= a.rollower_requerido AND a.estado='R'";

                            break;

                        case 2:

                            $contSql = $contSql + 1;
                            $strSql[$contSql] = "UPDATE usuario_sorteo,registro SET usuario_sorteo.estado = 'R',registro.saldo_especial=registro.saldo_especial + usuario_sorteo.valor,registro.creditos_sorteo=registro.creditos_sorteo - usuario_sorteo.valor   WHERE  registro.usuario_id= usuario_sorteo.usuario_id AND usuario_sorteo.apostado >= usuario_sorteo.rollower_requerido AND usuario_sorteo.ususorteo_id = " . $ususorteo_id . " AND usuario_sorteo.estado='A'";
                            $estadoSorteo = 'R';
                            $contSql = $contSql + 1;
                            $strSql[$contSql] = "INSERT INTO sorteo_log (usuario_id,tipo,valor,estado,id_externo,mandante,transaccion_id,tiposorteo_id,fecha_crea,fecha_cierre) SELECT a.usuario_id,CASE WHEN b.tipo = 2 THEN 'D' ELSE 'F' END,a.valor,'L',a.ususorteo_id,0,'0',4,now(),now()  FROM  usuario_sorteo a  INNER JOIN sorteo_interno  b ON (b.sorteo_id = a.sorteo_id)  WHERE a.ususorteo_id = " . $ususorteo_id . " AND a.apostado >= a.rollower_requerido AND a.estado='R'";

                            break;

                    }


                }


                $respuesta["WinBonus"] = true;
                $respuesta["Sorteo"] = $sorteoid;
                $respuesta["UsuarioSorteo"] = $ususorteo_id;
                $respuesta["queries"] = $strSql;

                foreach ($respuesta["queries"] as $querie) {
                    $this->execQuery($querie);
                }

                if ($ganaSorteoId != 0) {
                    $sqlSorteo2 = "select a.usuario_id,a.ususorteo_id,a.sorteo_id,a.apostado,a.rollower_requerido,a.fecha_crea,sorteo_interno.condicional,sorteo_interno.tipo from usuario_sorteo a INNER JOIN sorteo_interno ON (sorteo_interno.sorteo_id = a.sorteo_id ) where  a.estado='A' AND (sorteo_interno.tipo = 2 OR sorteo_interno.tipo = 3) AND a.ususorteo_id='" . $ususorteo_id . "'";

                    $sorteosDisponibles2 = $this->execQuery($sqlSorteo2);

                    $rollower_requerido = $sorteosDisponibles2[0]->rollower_requerido;
                    $apostado = $sorteosDisponibles2[0]->apostado;

                    if ($apostado >= $rollower_requerido) {
                        try {
                            $usuarioSql = "select ciudad.ciudad_id,ciudad.depto_id,pais.pais_id FROM registro
  INNER JOIN ciudad ON (ciudad.ciudad_id = registro.ciudad_id)
  INNER JOIN departamento ON (ciudad.depto_id = departamento.depto_id)
  INNER JOIN pais ON (pais.pais_id = departamento.pais_id) WHERE registro.usuario_id='" . $sorteosDisponibles2[0]->usuario_id . "'";

                            $Usuario = $this->execQuery($usuarioSql);

                            $dataUsuario = $Usuario;
                            $detalles = array(
                                "PaisUSER" => $dataUsuario['pais_id'],
                                "DepartamentoUSER" => $dataUsuario['depto_id'],
                                "CiudadUSER" => $dataUsuario['ciudad_id'],

                            );
                            $detalles = json_decode(json_encode($detalles));

                            $respuesta2 = $this->agregarSorteoFree($ganaSorteoId, $sorteosDisponibles2[0]->usuario_id, "0", $detalles, true);

                            $contSql = 1;
                            $strSql = array();
                            $strSql[$contSql] = "UPDATE usuario_sorteo SET usuario_sorteo.estado = 'R'   WHERE usuario_sorteo.ususorteo_id = " . $ususorteo_id . " AND usuario_sorteo.estado='A'";

                            //  $contSql = $contSql +1;
                            //   $strSql[$contSql] = "INSERT INTO sorteo_log (usuario_id,tipo,valor,estado,id_externo,mandante,transaccion_id,tiposorteo_id) SELECT a.usuario_id,'D',a.valor,'L',a.ususorteo_id,0,'0',4  FROM  usuario_sorteo a   WHERE a.ususorteo_id = " . $ususorteo_id . " AND a.apostado >= a.rollower_requerido";
                        } catch (Exception $e) {

                        }


                        $respuesta["WinBonus"] = true;
                        $respuesta["Sorteo"] = $sorteoid;
                        $respuesta["UsuarioSorteo"] = $ususorteo_id;
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
     * Verficiar rollower en el sorteo
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
    public function verificarSorteoUsuarioPremio($usuarioId, $detalles, $tipoProducto, $ticketId)
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

                    $UsuarioSorteo = new UsuarioSorteo($TransjuegoInfo->descripcion);

                    $UsuarioSorteo->setValorPremio('valor_premio + ' . $TransaccionApi->getValor());

                    $UsuarioSorteoMySqlDAO = new UsuarioSorteoMySqlDAO();
                    $UsuarioSorteoMySqlDAO->update($UsuarioSorteo);
                    $UsuarioSorteoMySqlDAO->getTransaction()->commit();
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

                    $UsuarioSorteo = new UsuarioSorteo($TransjuegoInfo->descripcion);

                    $UsuarioSorteo->setValorPremio('valor_premio + ' . $TransaccionApi->getValor());

                    $UsuarioSorteoMySqlDAO = new UsuarioSorteoMySqlDAO();
                    $UsuarioSorteoMySqlDAO->update($UsuarioSorteo);
                    $UsuarioSorteoMySqlDAO->getTransaction()->commit();
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

                    $UsuarioSorteo = new UsuarioSorteo($TransjuegoInfo->descripcion);

                    $UsuarioSorteo->setValorPremio('valor_premio + ' . $TransaccionApi->getValor());

                    $UsuarioSorteoMySqlDAO = new UsuarioSorteoMySqlDAO();
                    $UsuarioSorteoMySqlDAO->update($UsuarioSorteo);
                    $UsuarioSorteoMySqlDAO->getTransaction()->commit();
                }


                break;


        }
    }

    /**
     * Verficiar sorteo
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
    public function verificarSorteoUsuario($usuarioId, $detalles, $tipoProducto, $ticketId, $ticketId2="")
    {

        //print_r("Hola");
        //print_r($usuarioId);
        //print_r($detalles);
        //print_r($tipoProducto);
        //print_r($ticketId);
        //print_r("Chao");

        switch ($tipoProducto) {

            case "CASINO":

                $valorTicket=0;

                if($ticketId2 != ''){

                    $TransjuegoLog = new TransjuegoLog($ticketId2);
                    $TransaccionJuego = new TransaccionJuego($TransjuegoLog->getTransjuegoId());
                    $UsuarioMandante = new UsuarioMandante($TransaccionJuego->usuarioId);
                    $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

                    $ProductoMandante = new ProductoMandante('','',$TransaccionJuego->productoId);
                    $Producto = new Producto($ProductoMandante->productoId);

                    $valorTicket =$TransjuegoLog->getValor();
                    print_r($Producto);
                    print_r($valorTicket);
                }else{

                    $TransaccionApi = new TransaccionApi($ticketId);
                    $UsuarioMandante = new UsuarioMandante($TransaccionApi->usuarioId);
                    $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

                    $ProductoMandante = new ProductoMandante('','',$TransaccionApi->productoId);
                    $Producto = new Producto($ProductoMandante->productoId);

                    $valorTicket =$TransaccionApi->getValor();
                    $cumpleCondicion = false;
                    $sorteosAnalizados = '';
                }



                if (false) {
                    //print_r("entro");
                    $rules = [];

                    array_push($rules, array("field" => "usuario_sorteo.usuario_id", "data" => "$TransaccionApi->usuarioId", "op" => "eq"));
                    array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));
                    array_push($rules, array("field" => "sorteo_interno.fecha_inicio", "data" => "$TransaccionApi->fechaCrea", "op" => "le"));
                    array_push($rules, array("field" => "sorteo_interno.fecha_fin", "data" => "$TransaccionApi->fechaCrea", "op" => "ge"));
                    array_push($rules, array("field" => "sorteo_interno.tipo", "data" => "2", "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $json = json_encode($filtro);


                    $UsuarioSorteo = new UsuarioSorteo();
                    $data = $UsuarioSorteo->getUsuarioSorteosCustomWithoutPosition("usuario_sorteo.*,usuario_mandante.nombres,sorteo_interno.*", "usuario_sorteo.valor", "DESC", 0, 100, $json, true, '');

                    //print_r($data);

                    $data = json_decode($data);

                    $final = [];

                    $pos = 1;

                    $cumpleCondicion = false;
                    foreach ($data->data as $key => $value) {

                        $array = [];
                        $array["Position"] = $pos;
                        $array["Id"] = $value->{"usuario_sorteo.ususorteo_id"};

                        $sorteosAnalizados = $sorteosAnalizados . $value->{"usuario_sorteo.sorteo_id"} . ",";
                        $SorteoInterno = new SorteoInterno();
                        $SorteoDetalle = new SorteoDetalle();

                        //$bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
                        //$bonos = json_decode($bonos);


                        $rules = [];

                        array_push($rules, array("field" => "sorteo_interno.sorteo_id", "data" => $value->{"usuario_sorteo.sorteo_id"}, "op" => "eq"));
                        array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));

                        $filtro = array("rules" => $rules, "groupOp" => "AND");

                        $json = json_encode($filtro);

                        $sorteodetalles = $SorteoDetalle->getSorteoDetallesCustom(" sorteo_detalle.*,sorteo_interno.* ", "sorteo_interno.orden", "desc", 0, 1000, $json, TRUE);

                        $sorteodetalles = json_decode($sorteodetalles);

                        $final = [];

                        $creditosConvert = 0;

                        $cumpleCondicionPais = false;
                        $cumpleCondicionCont = 0;

                        $condicionesProducto = 0;

                        foreach ($sorteodetalles->data as $key2 => $value2) {

                            switch ($value2->{"sorteo_detalle.tipo"}) {
                                case "RANK":
                                    if ($value2->{"sorteo_detalle.moneda"} == $UsuarioMandante->moneda) {
                                        if ($TransaccionApi->valor >= $value2->{"sorteo_detalle.valor"}) {
                                            if ($TransaccionApi->valor <= $value2->{"sorteo_detalle.valor2"}) {
                                                $creditosConvert = $value2->{"sorteo_detalle.valor3"};
                                            }

                                        }
                                    }

                                    break;


                                case "CONDPAISUSER":

                                    if ($value2->{"sorteo_detalle.valor"} == $Usuario->paisId) {
                                        $cumpleCondicionPais = true;
                                    }
                                    $cumpleCondicionCont++;

                                    break;

                                default:

                                    if (stristr($value2->{"sorteo_detalle.tipo"}, 'CONDGAME')) {

                                        $idGame = explode("CONDGAME", $value2->{"sorteo_detalle.tipo"})[1];

                                        if ($TransaccionApi->productoId == $idGame) {
                                            $cumpleCondicion = true;
                                        }
                                        $condicionesProducto++;
                                    }

                                    if (stristr($value2->{"sorteo_detalle.tipo"}, 'CONDPROVIDER')) {

                                        $idProvider = explode("CONDPROVIDER", $value2->{"sorteo_detalle.tipo"})[1];

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
                            //print_r("entro2");
                            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();

                            $UsuarioSorteo = new UsuarioSorteo($value->{"usuario_sorteo.ususorteo_id"});
                            $UsuarioSorteo->valor = $UsuarioSorteo->valor + $creditosConvert;
                            $UsuarioSorteo->valorBase = ($UsuarioSorteo->valorBase + $TransaccionApi->valor);
                            //print_r($TransaccionApi);

                            //print_r($UsuarioSorteo);
                            $TransaccionJuego = new TransaccionJuego("", $TransaccionApi->getIdentificador());

                            $UsuarioSorteoMySqlDAO = new UsuarioSorteoMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                            $UsuarioSorteoMySqlDAO->update($UsuarioSorteo);

                            $TransjuegoInfo = new TransjuegoInfo();
                            $TransjuegoInfo->productoId = $TransaccionApi->productoId;
                            $TransjuegoInfo->transaccionId = $TransaccionApi->transaccionId;
                            $TransjuegoInfo->tipo = "SORTEO";
                            $TransjuegoInfo->descripcion = $value->{"usuario_sorteo.ususorteo_id"};
                            $TransjuegoInfo->valor = $creditosConvert;
                            $TransjuegoInfo->transapiId = $TransaccionApi->transapiId;
                            $TransjuegoInfo->usucreaId = 0;
                            $TransjuegoInfo->usumodifId = 0;
                            $TransjuegoInfo->identificador = $TransaccionApi->identificador;
                            $TransjuegoInfo->transjuegoId = $TransaccionJuego->transjuegoId;

                            $title = '';
                            $messageBody = '';
                            $lotteryName = $value->{"sorteo_interno.nombre"};

                            switch(strtolower($Usuario->idioma)) {
                                case 'es':
                                    $title = 'Notificacion';
                                    $messageBody = "¡ Bien :thumbsup: ! Estas participando en el sorteo {$lotteryName} :clap:";
                                    break;
                                case 'en':
                                    $title = 'Notification';
                                    $messageBody = "¡ Great :thumbsup: ! You are participating in the Raffle {$lotteryName} :clap:";
                                    break;
                                case 'pt':
                                    $title = 'Notificação';
                                    $messageBody = "Mandou bem! :thumbsup: Você está participando do {$lotteryName} :clap:";
                                    break;
                            }

                            $UsuarioMensaje = new UsuarioMensaje();
                            $UsuarioMensaje->usufromId = 0;
                            $UsuarioMensaje->usutoId = $UsuarioSorteo->getUsuarioId();
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

                            /*
                                                        $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);
                            
                                                        $usuarios = json_decode($usuarios);
                            
                                                        $usuariosFinal = [];
                            
                                                        foreach ($usuarios->data as $key => $value) {
                            
                                                            $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data);
                                                            $WebsocketUsuario->sendWSMessage();
                            
                                                        }*/

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
                    //print_r("entro3");
                    if ($sorteosAnalizados != "") {
                        $sorteosAnalizados = $sorteosAnalizados . '0';
                    }

                    $SorteoInterno = new SorteoInterno();

                    $rules = [];

                    //Traemos todos los sorteos internos que se encuentren en estado activo A
                    if ($sorteosAnalizados != '') {
                        array_push($rules, array("field" => "sorteo_interno.sorteo_id", "data" => "$sorteosAnalizados", "op" => "ni"));
                    }

                    if($TransjuegoLog != null){
                        array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));
                        array_push($rules, array("field" => "sorteo_interno.fecha_inicio", "data" => "$TransjuegoLog->fechaCrea", "op" => "le"));
                        array_push($rules, array("field" => "sorteo_interno.fecha_fin", "data" => "$TransjuegoLog->fechaCrea", "op" => "ge"));
                        //array_push($rules, array("field" => "sorteo_interno.tipo", "data" => "2", "op" => "eq"));
                        array_push($rules, array("field" => "sorteo_interno.habilita_casino", "data" => "1", "op" => "eq"));
                        array_push($rules, array("field" => "sorteo_interno.mandante", "data" => $UsuarioMandante->mandante, "op" => "eq"));

                        print_r($rules);
                        print_r($TransjuegoLog);
                    }else{
                        array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));
                        array_push($rules, array("field" => "sorteo_interno.fecha_inicio", "data" => "$TransaccionApi->fechaCrea", "op" => "le"));
                        array_push($rules, array("field" => "sorteo_interno.fecha_fin", "data" => "$TransaccionApi->fechaCrea", "op" => "ge"));
                        //array_push($rules, array("field" => "sorteo_interno.tipo", "data" => "2", "op" => "eq"));
                        array_push($rules, array("field" => "sorteo_interno.habilita_casino", "data" => "1", "op" => "eq"));
                        array_push($rules, array("field" => "sorteo_interno.mandante", "data" => $UsuarioMandante->mandante, "op" => "eq"));

                    }

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $json = json_encode($filtro);


                    $data = $SorteoInterno->getSorteosCustom("sorteo_interno.*", "sorteo_interno.orden", "DESC", 0, 1000, $json, true, '');


                    $data = json_decode($data);

                    $final = [];

                    $pos = 1;
                    $sorteosAnalizados = '';

                    foreach ($data->data as $key => $value) {

                        $pegatinas=$value->{"sorteo_interno.pegatinas"};

                        $SorteoDetalle = new SorteoDetalle();

                        //$bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
                        //$bonos = json_decode($bonos);


                        $rules = [];

                        array_push($rules, array("field" => "sorteo_interno.sorteo_id", "data" => $value->{"sorteo_interno.sorteo_id"}, "op" => "eq"));
                        array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));
                        array_push($rules, array("field" => "sorteo_interno.mandante", "data" => $UsuarioMandante->mandante, "op" => "eq"));


                        $filtro = array("rules" => $rules, "groupOp" => "AND");

                        $json = json_encode($filtro);

                        $sorteodetalles = $SorteoDetalle->getSorteoDetallesCustom(" sorteo_detalle.*,sorteo_interno.* ", "sorteo_interno.orden", "desc", 0, 1000, $json, TRUE);

                        $sorteodetalles = json_decode($sorteodetalles);

                        $final = [];

                        $creditosConvert = 0;

                        $cumpleCondicion = false;
                        $needSubscribe = false;

                        $cumpleCondicionPais = false;
                        $cumpleCondicionCont = 0;

                        $condicionesProducto = 0;
                        $puederepetirBono = false;

                        $minBetPrice = 0;
                        $minBetPrice2 = 0;
                        $NUMBERCASINOSTICKERS=0;
                        $NUMBERDEPOSITSTICKERS=0;
                        $NUMBERSPORTSBOOKSTICKERS=0;




                        foreach ($sorteodetalles->data as $key2 => $value2) {

                            switch ($value2->{"sorteo_detalle.tipo"}) {
                                case "RANK":
                                    if ($value2->{"sorteo_detalle.moneda"} == $UsuarioMandante->moneda) {
                                        if ($valorTicket >= $value2->{"sorteo_detalle.valor"}) {
                                            if ($valorTicket <= $value2->{"sorteo_detalle.valor2"}) {
                                                $creditosConvert = $value2->{"sorteo_detalle.valor3"};
                                            }

                                        }
                                    }

                                    break;

                                case "USERSUBSCRIBE":

                                    if($value2->{"sorteo_detalle.valor"} ==0){

                                    }else{
                                        $needSubscribe=true;
                                    }


                                    break;

                                case "MINBETPRICECASINO":

                                    if ($value2->{"sorteo_detalle.moneda"} == $UsuarioMandante->moneda) {

                                        $minBetPrice=floatval($value2->{"sorteo_detalle.valor"});
                                    }

                                    break;




                                case "MINBETPRICE2CASINO":

                                    if ($value2->{"sorteo_detalle.moneda"} == $UsuarioMandante->moneda) {

                                        $minBetPrice2=floatval($value2->{"sorteo_detalle.valor"});
                                    }

                                    //print_r("MINBETPRICE2CASINO***");

                                    //print_r($minBetPrice2);

                                    //print_r("MINBETPRICE2CASINO");

                                    break;

                                case "VISIBILIDAD":

                                    if ($value2->{"sorteo_detalle.valor"} == 1) {
                                        $needSubscribe = true;

                                    } else {
                                    }

                                    break;


                                case "CONDPAISUSER":

                                    if ($value2->{"sorteo_detalle.valor"} == $Usuario->paisId) {
                                        $cumpleCondicionPais = true;
                                    }
                                    $cumpleCondicionCont++;

                                    break;

                                case "REPETIRSORTEO":

                                    if ($value2->{"sorteo_detalle.valor"} == '1') {

                                        $puederepetirBono = true;
                                    }

                                    break;
                                case "NUMBERCASINOSTICKERS":

                                    if ($value2->{"sorteo_detalle.moneda"} == $UsuarioMandante->moneda) {

                                        $NUMBERCASINOSTICKERS = $value2->{"sorteo_detalle.valor"};
                                    }
                                    break;
                                case "NUMBERDEPOSITSTICKERS":

                                    if ($value2->{"sorteo_detalle.moneda"} == $UsuarioMandante->moneda) {

                                        $NUMBERDEPOSITSTICKERS = $value2->{"sorteo_detalle.valor"};
                                    }
                                    break;
                                case "NUMBERSPORTSBOOKSTICKERS":

                                    if ($value2->{"sorteo_detalle.moneda"} == $UsuarioMandante->moneda) {

                                        $NUMBERSPORTSBOOKSTICKERS = $value2->{"sorteo_detalle.valor"};
                                    }
                                    break;

                                default:

                                    if (stristr($value2->{"sorteo_detalle.tipo"}, 'CONDGAME')) {

                                        $idGame = explode("CONDGAME", $value2->{"sorteo_detalle.tipo"})[1];

                                        if ($ProductoMandante->prodmandanteId == $idGame) {
                                            $cumpleCondicion = true;
                                        }elseif($idGame=="_ALL"){

                                            //print_r("Si es como esperaba");
                                            $cumpleCondicion = true;
                                        }

                                        $condicionesProducto++;
                                    }



                                    if (stristr($value2->{"sorteo_detalle.tipo"}, 'CONDPROVIDER')) {

                                        $idProvider = explode("CONDPROVIDER", $value2->{"sorteo_detalle.tipo"})[1];

                                        if ($Producto->proveedorId == $idProvider) {
                                            $cumpleCondicion = true;
                                        }elseif($idProvider=="_ALL"){
                                            $cumpleCondicion = true;
                                        }

                                        $condicionesProducto++;
                                    }



                                    break;
                            }
                            if($_ENV['debug']){
                                print_r($cumpleCondicion);
                            }

                        }

                        if ($condicionesProducto == 0 && !$cumpleCondicion) {
                            $cumpleCondicion = true;
                        }

                        if (!$cumpleCondicionPais && $cumpleCondicionCont > 0) {

                            $cumpleCondicion = false;
                        }

                        if ($minBetPrice2 > floatval($valorTicket)) {
                            $cumpleCondicion = false;
                        }

                        //print_r("cumplecondicion");



                        if ($needSubscribe) {

                            $sqlRepiteSorteo = "select * from usuario_sorteo a where a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"}  . "' AND a.usuario_id = '" . $UsuarioMandante->usumandanteId  . "' AND a.estado = 'I'";
                            $repiteSorteo = $this->execQuery('',$sqlRepiteSorteo);
                            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();

                            if(oldCount($repiteSorteo) == 0){
                                $cumpleCondicion=false;
                            }
                        }


                        if ($cumpleCondicion) {

                            if ($puederepetirBono) {


                            } else {

                                $sqlRepiteSorteo = "select * from usuario_sorteo a where a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.usuario_id = '" . $UsuarioMandante->usumandanteId . "'";
                                $repiteSorteo = $this->execQuery('',$sqlRepiteSorteo);

                                if ((!$puederepetirBono && oldCount($repiteSorteo) == 0)) {

                                } else {
                                    $cumpleCondicion = false;
                                }

                            }
                        }

                        if ($cumpleCondicion ) {
                            print_r("CUMPLECONDICION y DEBERIA ENTRAR");


                            $messageNot='';
                            if($pegatinas==1){
                                $estado = "P";
                                $sqlRepiteSorteo = "select * from preusuario_sorteo a where a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"}  . "' AND a.usuario_id = '" . $UsuarioMandante->usumandanteId  . "' AND a.tipo='1' AND a.estado = '". $estado . "'";
                                $repiteSorteo = $this->execQuery('',$sqlRepiteSorteo);
                                $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();

                                //print_r("Repiteeeee");

                                //print_r($sqlRepiteSorteo);

                                //print_r("Repiteeeee");

                                if(oldCount($repiteSorteo) == 0){
                                    //print_r('entro 2');
                                    $BonoInterno = new BonoInterno();
                                    $PreUsuarioSorteo = new PreUsuarioSorteo();
                                    $PreUsuarioSorteo->usuarioId = $UsuarioMandante->usumandanteId;
                                    $PreUsuarioSorteo->sorteoId = $value->{"sorteo_interno.sorteo_id"};
                                    $PreUsuarioSorteo->valor = 0;
                                    $PreUsuarioSorteo->posicion = 0;
                                    $PreUsuarioSorteo->valorBase = $minBetPrice;
                                    $PreUsuarioSorteo->usucreaId = 0;
                                    $PreUsuarioSorteo->usumodifId = 0;
                                    $PreUsuarioSorteo->mandante = $UsuarioMandante->mandante;
                                    $PreUsuarioSorteo->tipo = 1;
                                    if($valorTicket < $minBetPrice){
                                        $PreUsuarioSorteo->estado = "P";

                                    }else{
                                        $PreUsuarioSorteo->estado = "A";
                                    }
                                    $PreUsuarioSorteo->errorId = 0;
                                    $PreUsuarioSorteo->idExterno = 0;
                                    $PreUsuarioSorteo->version = 0;
                                    $PreUsuarioSorteo->apostado =  $valorTicket;
                                    $PreUsuarioSorteo->codigo = 0;
                                    $PreUsuarioSorteo->externoId = 0;
                                    $PreUsuarioSorteo->valor = $PreUsuarioSorteo->valor + $creditosConvert;
                                    //$UsuarioSorteo->valorBase = ($UsuarioSorteo->valorBase + $TransaccionApi->valor);

                                    $PreUsuarioSorteoMySqlDAO = new PreUsuarioSorteoMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                                    $idUsuSorteo = $PreUsuarioSorteoMySqlDAO->insert($PreUsuarioSorteo);



                                }else{

                                    //print_r("No repite");
                                    //print_r($TransaccionApi);
                                    $ususorteoId = $repiteSorteo[0]->{"a.preususorteo_id"};
                                    $BonoInterno = new BonoInterno();
                                    $idUsuSorteo=$ususorteoId;

                                    $transaccion = $TransjuegoInfoMySqlDAO->getTransaction();
                                    $sql= "UPDATE preusuario_sorteo SET apostado = apostado + " . (floatval($valorTicket)) . " WHERE preususorteo_id =" . $ususorteoId;
                                    $BonoInterno->execQuery($transaccion, $sql);

                                    $sql= "UPDATE preusuario_sorteo SET estado='A'  WHERE apostado >= valor_base and estado = 'P' AND preususorteo_id =" . $ususorteoId;
                                    $BonoInterno->execQuery($transaccion, $sql);



                                }
                                $messageNot='¡ Bien :thumbsup: ! Estas sumando stickers para el Sorteo ' . $value->{"sorteo_interno.nombre"} . ' :clap:';

                            }else{
                                $estado = "P";
                                $sqlRepiteSorteo = "select * from usuario_sorteo a where a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"}  . "' AND a.usuario_id = '" . $UsuarioMandante->usumandanteId  . "' AND a.estado = '". $estado . "'";
                                $repiteSorteo = $this->execQuery('',$sqlRepiteSorteo);
                                $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();

                                if(oldCount($repiteSorteo) == 0){
                                    $UsuarioSorteo = new UsuarioSorteo();
                                    $UsuarioSorteo->usuarioId = $UsuarioMandante->usumandanteId;
                                    $UsuarioSorteo->sorteoId = $value->{"sorteo_interno.sorteo_id"};
                                    $UsuarioSorteo->valor = 0;
                                    $UsuarioSorteo->posicion = 0;
                                    $UsuarioSorteo->valorBase = $minBetPrice;
                                    $UsuarioSorteo->usucreaId = 0;
                                    $UsuarioSorteo->usumodifId = 0;
                                    $UsuarioSorteo->mandante = $UsuarioMandante->mandante;
                                    if($valorTicket < $minBetPrice){
                                        $UsuarioSorteo->estado = "P";

                                    }else{
                                        $UsuarioSorteo->estado = "A";
                                    }
                                    $UsuarioSorteo->errorId = 0;
                                    $UsuarioSorteo->idExterno = 0;
                                    $UsuarioSorteo->version = 0;
                                    $UsuarioSorteo->apostado =  $valorTicket;
                                    $UsuarioSorteo->codigo = 0;
                                    $UsuarioSorteo->externoId = 0;
                                    $UsuarioSorteo->valor = $UsuarioSorteo->valor + $creditosConvert;
                                    //$UsuarioSorteo->valorBase = ($UsuarioSorteo->valorBase + $TransaccionApi->valor);

                                    $UsuarioSorteoMySqlDAO = new UsuarioSorteoMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                                    $idUsuSorteo = $UsuarioSorteoMySqlDAO->insert($UsuarioSorteo);
                                }else{
                                    $ususorteoId = $repiteSorteo[0]->{"a.ususorteo_id"};
                                    $BonoInterno = new BonoInterno();
                                    $idUsuSorteo = $ususorteoId;

                                    $transaccion = $TransjuegoInfoMySqlDAO->getTransaction();

                                    $sql= "UPDATE usuario_sorteo SET apostado = apostado + " . (floatval($valorTicket)) . " WHERE ususorteo_id =" . $ususorteoId;
                                    $BonoInterno->execQuery($transaccion, $sql);

                                    $sql= "UPDATE usuario_sorteo SET estado='A'  WHERE apostado >= valor_base  and estado = 'P'  AND ususorteo_id =" . $ususorteoId;
                                    $BonoInterno->execQuery($transaccion, $sql);

                                }

                                $messageNot='¡ Bien :thumbsup: ! Estas participando en el sorteo ' . $value->{"sorteo_interno.nombre"} . ' :clap:';

                            }







                            /*$UsuarioMensaje = new UsuarioMensaje();
                            $UsuarioMensaje->usufromId = 0;
                            $UsuarioMensaje->usutoId = $UsuarioSorteo->getUsuarioId();
                            $UsuarioMensaje->isRead = 0;
                            $UsuarioMensaje->body = $messageNot;
                            $UsuarioMensaje->msubject = 'Notificacion';
                            $UsuarioMensaje->parentId = 0;
                            $UsuarioMensaje->proveedorId = 0;
                            $UsuarioMensaje->tipo = "PUSHNOTIFICACION";
                            $UsuarioMensaje->paisId = 0;
                            $UsuarioMensaje->fechaExpiracion = '';

                            $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                            $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);*/

                            $Usuario = new Usuario($UsuarioSorteo->usuarioId);
                            $lotteryName = $value->{"sorteo_interno.nombre"};
                            switch(strtolower($Usuario->idioma)) {
                                case 'es':
                                    $title = 'Notificacion';
                                    $messageBody = "¡ Bien :thumbsup: ! Estas participando en el sorteo {$lotteryName} :clap:";
                                    break;
                                case 'en':
                                    $title = 'Notification';
                                    $messageBody = "¡ Great :thumbsup: ! You are participating in the Raffle {$lotteryName} :clap:";
                                    break;
                                case 'pt':
                                    $title = 'Notificação';
                                    $messageBody = "Mandou bem! :thumbsup: Você está participando do {$lotteryName} :clap:";
                                    break;
                            }

                            $TransjuegoInfo = new TransjuegoInfo();
                            $TransjuegoInfo->productoId = $ProductoMandante->prodmandanteId;
                            if($TransaccionApi != null){
                                $TransjuegoInfo->transaccionId = $TransaccionApi->transaccionId;

                            }else{
                                $TransjuegoInfo->transaccionId = $TransjuegoLog->transaccionId;

                            }
                            $TransjuegoInfo->tipo = "SORTEO";
                            $TransjuegoInfo->descripcion = $idUsuSorteo;
                            $TransjuegoInfo->valor = $creditosConvert;
                            if($TransjuegoLog != null){
                                $TransjuegoInfo->transapiId = $TransjuegoLog->getTransjuegologId();

                            }else{
                                $TransjuegoInfo->transapiId = $TransaccionApi->transapiId;

                            }
                            $TransjuegoInfo->usucreaId = 0;
                            $TransjuegoInfo->usumodifId = 0;



                            $TransjuegoInfoMySqlDAO->insert($TransjuegoInfo);
                            $TransjuegoInfoMySqlDAO->getTransaction()->commit();

                            print_r($TransjuegoInfo);

                            $mensajesRecibidos = [];
                            $array = [];

                            $array["body"] = $messageNot;

                            array_push($mensajesRecibidos, $array);
                            $data = array();
                            $data["messages"] = $mensajesRecibidos;
                            //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());
                            $ConfigurationEnvironment = new ConfigurationEnvironment();

                            if (!$ConfigurationEnvironment->isDevelopment() && false) {

                                if(false) {

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

            case "VIRTUAL":


                $valorTicket=0;

                if($ticketId2 != ''){

                    $TransjuegoLog = new TransjuegoLog($ticketId2);
                    $TransaccionJuego = new TransaccionJuego($TransjuegoLog->getTransjuegoId());
                    $UsuarioMandante = new UsuarioMandante($TransaccionJuego->usuarioId);
                    $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

                    $ProductoMandante = new ProductoMandante('','',$TransaccionJuego->productoId);
                    $Producto = new Producto($ProductoMandante->productoId);

                    $valorTicket =$TransjuegoLog->getValor();
                }else{

                    $TransaccionApi = new TransaccionApi($ticketId);
                    $UsuarioMandante = new UsuarioMandante($TransaccionApi->usuarioId);
                    $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

                    $ProductoMandante = new ProductoMandante('','',$TransaccionApi->productoId);
                    $Producto = new Producto($ProductoMandante->productoId);

                    $valorTicket =$TransaccionApi->getValor();
                    $cumpleCondicion = false;
                    $sorteosAnalizados = '';
                }



                if (false) {
                    //print_r("entro");
                    $rules = [];

                    array_push($rules, array("field" => "usuario_sorteo.usuario_id", "data" => "$TransaccionApi->usuarioId", "op" => "eq"));
                    array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));
                    array_push($rules, array("field" => "sorteo_interno.fecha_inicio", "data" => "$TransaccionApi->fechaCrea", "op" => "le"));
                    array_push($rules, array("field" => "sorteo_interno.fecha_fin", "data" => "$TransaccionApi->fechaCrea", "op" => "ge"));
                    array_push($rules, array("field" => "sorteo_interno.tipo", "data" => "2", "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $json = json_encode($filtro);


                    $UsuarioSorteo = new UsuarioSorteo();
                    $data = $UsuarioSorteo->getUsuarioSorteosCustomWithoutPosition("usuario_sorteo.*,usuario_mandante.nombres,sorteo_interno.*", "usuario_sorteo.valor", "DESC", 0, 100, $json, true, '');

                    //print_r($data);

                    $data = json_decode($data);

                    $final = [];

                    $pos = 1;

                    $cumpleCondicion = false;
                    foreach ($data->data as $key => $value) {

                        $array = [];
                        $array["Position"] = $pos;
                        $array["Id"] = $value->{"usuario_sorteo.ususorteo_id"};

                        $sorteosAnalizados = $sorteosAnalizados . $value->{"usuario_sorteo.sorteo_id"} . ",";
                        $SorteoInterno = new SorteoInterno();
                        $SorteoDetalle = new SorteoDetalle();

                        //$bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
                        //$bonos = json_decode($bonos);


                        $rules = [];

                        array_push($rules, array("field" => "sorteo_interno.sorteo_id", "data" => $value->{"usuario_sorteo.sorteo_id"}, "op" => "eq"));
                        array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));

                        $filtro = array("rules" => $rules, "groupOp" => "AND");

                        $json = json_encode($filtro);

                        $sorteodetalles = $SorteoDetalle->getSorteoDetallesCustom(" sorteo_detalle.*,sorteo_interno.* ", "sorteo_interno.orden", "desc", 0, 1000, $json, TRUE);

                        $sorteodetalles = json_decode($sorteodetalles);

                        $final = [];

                        $creditosConvert = 0;

                        $cumpleCondicionPais = false;
                        $cumpleCondicionCont = 0;

                        $condicionesProducto = 0;

                        foreach ($sorteodetalles->data as $key2 => $value2) {

                            switch ($value2->{"sorteo_detalle.tipo"}) {
                                case "RANK":
                                    if ($value2->{"sorteo_detalle.moneda"} == $UsuarioMandante->moneda) {
                                        if ($TransaccionApi->valor >= $value2->{"sorteo_detalle.valor"}) {
                                            if ($TransaccionApi->valor <= $value2->{"sorteo_detalle.valor2"}) {
                                                $creditosConvert = $value2->{"sorteo_detalle.valor3"};
                                            }

                                        }
                                    }

                                    break;


                                case "CONDPAISUSER":

                                    if ($value2->{"sorteo_detalle.valor"} == $Usuario->paisId) {
                                        $cumpleCondicionPais = true;
                                    }
                                    $cumpleCondicionCont++;

                                    break;

                                default:

                                    if (stristr($value2->{"sorteo_detalle.tipo"}, 'CONDGAME')) {

                                        $idGame = explode("CONDGAME", $value2->{"sorteo_detalle.tipo"})[1];

                                        if ($TransaccionApi->productoId == $idGame) {
                                            $cumpleCondicion = true;
                                        }elseif($idGame=="_ALL"){

                                            //print_r("Si es como esperaba");
                                            $cumpleCondicion = true;
                                        }
                                        $condicionesProducto++;
                                    }

                                    if (stristr($value2->{"sorteo_detalle.tipo"}, 'CONDPROVIDER')) {

                                        $idProvider = explode("CONDPROVIDER", $value2->{"sorteo_detalle.tipo"})[1];

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
                            //print_r("entro2");
                            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();

                            $UsuarioSorteo = new UsuarioSorteo($value->{"usuario_sorteo.ususorteo_id"});
                            $UsuarioSorteo->valor = $UsuarioSorteo->valor + $creditosConvert;
                            $UsuarioSorteo->valorBase = ($UsuarioSorteo->valorBase + $TransaccionApi->valor);
                            //print_r($TransaccionApi);

                            //print_r($UsuarioSorteo);
                            $TransaccionJuego = new TransaccionJuego("", $TransaccionApi->getIdentificador());

                            $UsuarioSorteoMySqlDAO = new UsuarioSorteoMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                            $UsuarioSorteoMySqlDAO->update($UsuarioSorteo);

                            $TransjuegoInfo = new TransjuegoInfo();
                            $TransjuegoInfo->productoId = $TransaccionApi->productoId;
                            $TransjuegoInfo->transaccionId = $TransaccionApi->transaccionId;
                            $TransjuegoInfo->tipo = "SORTEO";
                            $TransjuegoInfo->descripcion = $value->{"usuario_sorteo.ususorteo_id"};
                            $TransjuegoInfo->valor = $creditosConvert;
                            $TransjuegoInfo->transapiId = $TransaccionApi->transapiId;
                            $TransjuegoInfo->usucreaId = 0;
                            $TransjuegoInfo->usumodifId = 0;
                            $TransjuegoInfo->identificador = $TransaccionApi->identificador;
                            $TransjuegoInfo->transjuegoId = $TransaccionJuego->transjuegoId;

                            $title = '';
                            $messageBody = '';
                            $lotteryName = $value->{"sorteo_interno.nombre"};

                            switch(strtolower($Usuario->idioma)) {
                                case 'es':
                                    $title = 'Notificacion';
                                    $messageBody = "¡ Bien :thumbsup: ! Estas participando en el sorteo {$lotteryName} :clap:";
                                    break;
                                case 'en':
                                    $title = 'Notification';
                                    $messageBody = "¡ Great :thumbsup: ! You are participating in the Raffle {$lotteryName} :clap:";
                                    break;
                                case 'pt':
                                    $title = 'Notificação';
                                    $messageBody = "Mandou bem! :thumbsup: Você está participando do {$lotteryName} :clap:";
                                    break;
                            }

                            $UsuarioMensaje = new UsuarioMensaje();
                            $UsuarioMensaje->usufromId = 0;
                            $UsuarioMensaje->usutoId = $UsuarioSorteo->getUsuarioId();
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

                            /*
                                                        $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

                                                        $usuarios = json_decode($usuarios);

                                                        $usuariosFinal = [];

                                                        foreach ($usuarios->data as $key => $value) {

                                                            $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data);
                                                            $WebsocketUsuario->sendWSMessage();

                                                        }*/

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
                    //print_r("entro3");
                    if ($sorteosAnalizados != "") {
                        $sorteosAnalizados = $sorteosAnalizados . '0';
                    }

                    $SorteoInterno = new SorteoInterno();

                    $rules = [];

                    //Traemos todos los sorteos internos que se encuentren en estado activo A
                    if ($sorteosAnalizados != '') {
                        array_push($rules, array("field" => "sorteo_interno.sorteo_id", "data" => "$sorteosAnalizados", "op" => "ni"));
                    }

                    if($TransjuegoLog != null){
                        array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));
                        array_push($rules, array("field" => "sorteo_interno.fecha_inicio", "data" => "$TransjuegoLog->fechaCrea", "op" => "le"));
                        array_push($rules, array("field" => "sorteo_interno.fecha_fin", "data" => "$TransjuegoLog->fechaCrea", "op" => "ge"));
                        //array_push($rules, array("field" => "sorteo_interno.tipo", "data" => "2", "op" => "eq"));
                        array_push($rules, array("field" => "sorteo_interno.habilita_casino", "data" => "1", "op" => "eq"));
                        array_push($rules, array("field" => "sorteo_interno.mandante", "data" => $UsuarioMandante->mandante, "op" => "eq"));

                        print_r($rules);
                    }else{
                        array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));
                        array_push($rules, array("field" => "sorteo_interno.fecha_inicio", "data" => "$TransaccionApi->fechaCrea", "op" => "le"));
                        array_push($rules, array("field" => "sorteo_interno.fecha_fin", "data" => "$TransaccionApi->fechaCrea", "op" => "ge"));
                        //array_push($rules, array("field" => "sorteo_interno.tipo", "data" => "2", "op" => "eq"));
                        array_push($rules, array("field" => "sorteo_interno.habilita_casino", "data" => "1", "op" => "eq"));
                        array_push($rules, array("field" => "sorteo_interno.mandante", "data" => $UsuarioMandante->mandante, "op" => "eq"));

                    }

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $json = json_encode($filtro);


                    $data = $SorteoInterno->getSorteosCustom("sorteo_interno.*", "sorteo_interno.orden", "DESC", 0, 1000, $json, true, '');


                    $data = json_decode($data);

                    $final = [];

                    $pos = 1;
                    $sorteosAnalizados = '';

                    foreach ($data->data as $key => $value) {

                        $pegatinas=$value->{"sorteo_interno.pegatinas"};

                        $SorteoDetalle = new SorteoDetalle();

                        //$bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
                        //$bonos = json_decode($bonos);


                        $rules = [];

                        array_push($rules, array("field" => "sorteo_interno.sorteo_id", "data" => $value->{"sorteo_interno.sorteo_id"}, "op" => "eq"));
                        array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));
                        array_push($rules, array("field" => "sorteo_interno.mandante", "data" => $UsuarioMandante->mandante, "op" => "eq"));


                        $filtro = array("rules" => $rules, "groupOp" => "AND");

                        $json = json_encode($filtro);

                        $sorteodetalles = $SorteoDetalle->getSorteoDetallesCustom(" sorteo_detalle.*,sorteo_interno.* ", "sorteo_interno.orden", "desc", 0, 1000, $json, TRUE);

                        $sorteodetalles = json_decode($sorteodetalles);

                        $final = [];

                        $creditosConvert = 0;

                        $cumpleCondicion = false;
                        $needSubscribe = false;

                        $cumpleCondicionPais = false;
                        $cumpleCondicionCont = 0;

                        $condicionesProducto = 0;
                        $puederepetirBono = false;

                        $minBetPrice = 0;
                        $minBetPrice2 = 0;
                        $NUMBERCASINOSTICKERS=0;
                        $NUMBERDEPOSITSTICKERS=0;
                        $NUMBERSPORTSBOOKSTICKERS=0;




                        foreach ($sorteodetalles->data as $key2 => $value2) {

                            switch ($value2->{"sorteo_detalle.tipo"}) {
                                case "RANK":
                                    if ($value2->{"sorteo_detalle.moneda"} == $UsuarioMandante->moneda) {
                                        if ($valorTicket >= $value2->{"sorteo_detalle.valor"}) {
                                            if ($valorTicket <= $value2->{"sorteo_detalle.valor2"}) {
                                                $creditosConvert = $value2->{"sorteo_detalle.valor3"};
                                            }

                                        }
                                    }

                                    break;

                                case "USERSUBSCRIBE":

                                    if($value2->{"sorteo_detalle.valor"} ==0){

                                    }else{
                                        $needSubscribe=true;
                                    }


                                    break;

                                case "MINBETPRICECASINO":

                                    if ($value2->{"sorteo_detalle.moneda"} == $UsuarioMandante->moneda) {

                                        $minBetPrice=floatval($value2->{"sorteo_detalle.valor"});
                                    }

                                    break;




                                case "MINBETPRICE2CASINO":

                                    if ($value2->{"sorteo_detalle.moneda"} == $UsuarioMandante->moneda) {

                                        $minBetPrice2=floatval($value2->{"sorteo_detalle.valor"});
                                    }

                                    //print_r("MINBETPRICE2CASINO***");

                                    //print_r($minBetPrice2);

                                    //print_r("MINBETPRICE2CASINO");

                                    break;

                                case "VISIBILIDAD":

                                    if ($value2->{"sorteo_detalle.valor"} == 1) {
                                        $needSubscribe = true;

                                    } else {
                                    }

                                    break;


                                case "CONDPAISUSER":

                                    if ($value2->{"sorteo_detalle.valor"} == $Usuario->paisId) {
                                        $cumpleCondicionPais = true;
                                    }
                                    $cumpleCondicionCont++;

                                    break;

                                case "REPETIRSORTEO":

                                    if ($value2->{"sorteo_detalle.valor"} == '1') {

                                        $puederepetirBono = true;
                                    }

                                    break;
                                case "NUMBERCASINOSTICKERS":

                                    if ($value2->{"sorteo_detalle.moneda"} == $UsuarioMandante->moneda) {

                                        $NUMBERCASINOSTICKERS = $value2->{"sorteo_detalle.valor"};
                                    }
                                    break;
                                case "NUMBERDEPOSITSTICKERS":

                                    if ($value2->{"sorteo_detalle.moneda"} == $UsuarioMandante->moneda) {

                                        $NUMBERDEPOSITSTICKERS = $value2->{"sorteo_detalle.valor"};
                                    }
                                    break;
                                case "NUMBERSPORTSBOOKSTICKERS":

                                    if ($value2->{"sorteo_detalle.moneda"} == $UsuarioMandante->moneda) {

                                        $NUMBERSPORTSBOOKSTICKERS = $value2->{"sorteo_detalle.valor"};
                                    }
                                    break;

                                default:

                                    if (stristr($value2->{"sorteo_detalle.tipo"}, 'CONDGAME')) {

                                        $idGame = explode("CONDGAME", $value2->{"sorteo_detalle.tipo"})[1];

                                        if ($ProductoMandante->prodmandanteId == $idGame) {
                                            $cumpleCondicion = true;
                                        }elseif($idGame=="_ALL"){

                                            //print_r("Si es como esperaba");
                                            $cumpleCondicion = true;
                                        }

                                        $condicionesProducto++;
                                    }



                                    if (stristr($value2->{"sorteo_detalle.tipo"}, 'CONDPROVIDER')) {

                                        $idProvider = explode("CONDPROVIDER", $value2->{"sorteo_detalle.tipo"})[1];

                                        if ($Producto->proveedorId == $idProvider) {
                                            $cumpleCondicion = true;
                                        }elseif($idProvider=="_ALL"){
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

                        if ($minBetPrice2 > floatval($valorTicket)) {
                            $cumpleCondicion = false;
                        }

                        //print_r("cumplecondicion");



                        if ($cumpleCondicion) {

                            if ($puederepetirBono) {


                            } else {

                                $sqlRepiteSorteo = "select * from usuario_sorteo a where a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.usuario_id = '" . $UsuarioMandante->usumandanteId . "'";
                                $repiteSorteo = $this->execQuery('',$sqlRepiteSorteo);

                                if ((!$puederepetirBono && oldCount($repiteSorteo) == 0)) {

                                } else {
                                    $cumpleCondicion = false;
                                }

                            }
                        }



                        if ($needSubscribe) {

                            $sqlRepiteSorteo = "select * from usuario_sorteo a where a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"}  . "' AND a.usuario_id = '" . $UsuarioMandante->usumandanteId  . "' AND a.estado = 'I'";
                            $repiteSorteo = $this->execQuery('',$sqlRepiteSorteo);
                            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();

                            if(oldCount($repiteSorteo) == 0){
                                $cumpleCondicion=false;
                            }
                        }


                        if ($cumpleCondicion && !$needSubscribe) {
                            print_r("CUMPLECONDICION y DEBERIA ENTRAR");


                            $messageNot='';
                            if($pegatinas==1){
                                $estado = "P";
                                $sqlRepiteSorteo = "select * from preusuario_sorteo a where a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"}  . "' AND a.usuario_id = '" . $UsuarioMandante->usumandanteId  . "' AND a.tipo='1' AND a.estado = '". $estado . "'";
                                $repiteSorteo = $this->execQuery('',$sqlRepiteSorteo);
                                $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();

                                //print_r("Repiteeeee");

                                //print_r($sqlRepiteSorteo);

                                //print_r("Repiteeeee");

                                if(oldCount($repiteSorteo) == 0){
                                    //print_r('entro 2');
                                    $BonoInterno = new BonoInterno();
                                    $PreUsuarioSorteo = new PreUsuarioSorteo();
                                    $PreUsuarioSorteo->usuarioId = $UsuarioMandante->usumandanteId;
                                    $PreUsuarioSorteo->sorteoId = $value->{"sorteo_interno.sorteo_id"};
                                    $PreUsuarioSorteo->valor = 0;
                                    $PreUsuarioSorteo->posicion = 0;
                                    $PreUsuarioSorteo->valorBase = $minBetPrice;
                                    $PreUsuarioSorteo->usucreaId = 0;
                                    $PreUsuarioSorteo->usumodifId = 0;
                                    $PreUsuarioSorteo->mandante = $UsuarioMandante->mandante;
                                    $PreUsuarioSorteo->tipo = 1;
                                    if($valorTicket < $minBetPrice){
                                        $PreUsuarioSorteo->estado = "P";

                                    }else{
                                        $PreUsuarioSorteo->estado = "A";
                                    }
                                    $PreUsuarioSorteo->errorId = 0;
                                    $PreUsuarioSorteo->idExterno = 0;
                                    $PreUsuarioSorteo->version = 0;
                                    $PreUsuarioSorteo->apostado =  $valorTicket;
                                    $PreUsuarioSorteo->codigo = 0;
                                    $PreUsuarioSorteo->externoId = 0;
                                    $PreUsuarioSorteo->valor = $PreUsuarioSorteo->valor + $creditosConvert;
                                    //$UsuarioSorteo->valorBase = ($UsuarioSorteo->valorBase + $TransaccionApi->valor);

                                    $PreUsuarioSorteoMySqlDAO = new PreUsuarioSorteoMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                                    $idUsuSorteo = $PreUsuarioSorteoMySqlDAO->insert($PreUsuarioSorteo);



                                }else{

                                    //print_r("No repite");
                                    //print_r($TransaccionApi);
                                    $ususorteoId = $repiteSorteo[0]->{"a.preususorteo_id"};
                                    $BonoInterno = new BonoInterno();
                                    $idUsuSorteo=$ususorteoId;

                                    $transaccion = $TransjuegoInfoMySqlDAO->getTransaction();
                                    $sql= "UPDATE preusuario_sorteo SET apostado = apostado + " . (floatval($valorTicket)) . " WHERE preususorteo_id =" . $ususorteoId;
                                    $BonoInterno->execQuery($transaccion, $sql);

                                    $sql= "UPDATE preusuario_sorteo SET estado='A'  WHERE apostado >= valor_base and estado = 'P'  AND preususorteo_id =" . $ususorteoId;
                                    $BonoInterno->execQuery($transaccion, $sql);



                                }
                                $messageNot='¡ Bien :thumbsup: ! Estas sumando stickers para el Sorteo ' . $value->{"sorteo_interno.nombre"} . ' :clap:';

                            }else{
                                $estado = "P";
                                $sqlRepiteSorteo = "select * from usuario_sorteo a where a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"}  . "' AND a.usuario_id = '" . $UsuarioMandante->usumandanteId  . "' AND a.estado = '". $estado . "'";
                                $repiteSorteo = $this->execQuery('',$sqlRepiteSorteo);
                                $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();

                                if(oldCount($repiteSorteo) == 0){
                                    $UsuarioSorteo = new UsuarioSorteo();
                                    $UsuarioSorteo->usuarioId = $UsuarioMandante->usumandanteId;
                                    $UsuarioSorteo->sorteoId = $value->{"sorteo_interno.sorteo_id"};
                                    $UsuarioSorteo->valor = 0;
                                    $UsuarioSorteo->posicion = 0;
                                    $UsuarioSorteo->valorBase = $minBetPrice;
                                    $UsuarioSorteo->usucreaId = 0;
                                    $UsuarioSorteo->usumodifId = 0;
                                    $UsuarioSorteo->mandante = $UsuarioMandante->mandante;
                                    if($valorTicket < $minBetPrice){
                                        $UsuarioSorteo->estado = "P";

                                    }else{
                                        $UsuarioSorteo->estado = "A";
                                    }
                                    $UsuarioSorteo->errorId = 0;
                                    $UsuarioSorteo->idExterno = 0;
                                    $UsuarioSorteo->version = 0;
                                    $UsuarioSorteo->apostado =  $valorTicket;
                                    $UsuarioSorteo->codigo = 0;
                                    $UsuarioSorteo->externoId = 0;
                                    $UsuarioSorteo->valor = $UsuarioSorteo->valor + $creditosConvert;
                                    //$UsuarioSorteo->valorBase = ($UsuarioSorteo->valorBase + $TransaccionApi->valor);

                                    $UsuarioSorteoMySqlDAO = new UsuarioSorteoMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                                    $idUsuSorteo = $UsuarioSorteoMySqlDAO->insert($UsuarioSorteo);
                                }else{
                                    $ususorteoId = $repiteSorteo[0]->{"a.ususorteo_id"};
                                    $BonoInterno = new BonoInterno();
                                    $idUsuSorteo = $ususorteoId;

                                    $transaccion = $TransjuegoInfoMySqlDAO->getTransaction();

                                    $sql= "UPDATE usuario_sorteo SET apostado = apostado + " . (floatval($valorTicket)) . " WHERE ususorteo_id =" . $ususorteoId;
                                    $BonoInterno->execQuery($transaccion, $sql);

                                    $sql= "UPDATE usuario_sorteo SET estado='A'  WHERE apostado >= valor_base and estado = 'P'  AND ususorteo_id =" . $ususorteoId;
                                    $BonoInterno->execQuery($transaccion, $sql);

                                }

                                $messageNot='¡ Bien :thumbsup: ! Estas participando en el sorteo ' . $value->{"sorteo_interno.nombre"} . ' :clap:';

                            }







                            /*$UsuarioMensaje = new UsuarioMensaje();
                            $UsuarioMensaje->usufromId = 0;
                            $UsuarioMensaje->usutoId = $UsuarioSorteo->getUsuarioId();
                            $UsuarioMensaje->isRead = 0;
                            $UsuarioMensaje->body = $messageNot;
                            $UsuarioMensaje->msubject = 'Notificacion';
                            $UsuarioMensaje->parentId = 0;
                            $UsuarioMensaje->proveedorId = 0;
                            $UsuarioMensaje->tipo = "PUSHNOTIFICACION";
                            $UsuarioMensaje->paisId = 0;
                            $UsuarioMensaje->fechaExpiracion = '';

                            $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                            $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);*/





                            $TransjuegoInfo = new TransjuegoInfo();
                            $TransjuegoInfo->productoId = $ProductoMandante->prodmandanteId;
                            if($TransaccionApi != null){
                                $TransjuegoInfo->transaccionId = $TransaccionApi->transaccionId;

                            }else{
                                $TransjuegoInfo->transaccionId = $TransjuegoLog->transaccionId;

                            }
                            $TransjuegoInfo->tipo = "SORTEO";
                            $TransjuegoInfo->descripcion = $idUsuSorteo;
                            $TransjuegoInfo->valor = $creditosConvert;
                            if($TransjuegoLog != null){
                                $TransjuegoInfo->transapiId = $TransjuegoLog->getTransjuegologId();

                            }else{
                                $TransjuegoInfo->transapiId = $TransaccionApi->transapiId;

                            }
                            $TransjuegoInfo->usucreaId = 0;
                            $TransjuegoInfo->usumodifId = 0;



                            $TransjuegoInfoMySqlDAO->insert($TransjuegoInfo);
                            $TransjuegoInfoMySqlDAO->getTransaction()->commit();

                            print_r($TransjuegoInfo);

                            $mensajesRecibidos = [];
                            $array = [];

                            $array["body"] = $messageNot;

                            array_push($mensajesRecibidos, $array);
                            $data = array();
                            $data["messages"] = $mensajesRecibidos;
                            //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());
                            $ConfigurationEnvironment = new ConfigurationEnvironment();

                            if (!$ConfigurationEnvironment->isDevelopment() && false) {

                                if(false) {

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


                $valorTicket=0;

                if($ticketId2 != ''){

                    $TransjuegoLog = new TransjuegoLog($ticketId2);
                    $TransaccionJuego = new TransaccionJuego($TransjuegoLog->getTransjuegoId());
                    $UsuarioMandante = new UsuarioMandante($TransaccionJuego->usuarioId);
                    $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

                    $ProductoMandante = new ProductoMandante('','',$TransaccionJuego->productoId);
                    $Producto = new Producto($ProductoMandante->productoId);

                    $valorTicket =$TransjuegoLog->getValor();
                }else{

                    $TransaccionApi = new TransaccionApi($ticketId);
                    $UsuarioMandante = new UsuarioMandante($TransaccionApi->usuarioId);
                    $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

                    $ProductoMandante = new ProductoMandante('','',$TransaccionApi->productoId);
                    $Producto = new Producto($ProductoMandante->productoId);

                    $valorTicket =$TransaccionApi->getValor();
                    $cumpleCondicion = false;
                    $sorteosAnalizados = '';
                }



                if (false) {
                    //print_r("entro");
                    $rules = [];

                    array_push($rules, array("field" => "usuario_sorteo.usuario_id", "data" => "$TransaccionApi->usuarioId", "op" => "eq"));
                    array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));
                    array_push($rules, array("field" => "sorteo_interno.fecha_inicio", "data" => "$TransaccionApi->fechaCrea", "op" => "le"));
                    array_push($rules, array("field" => "sorteo_interno.fecha_fin", "data" => "$TransaccionApi->fechaCrea", "op" => "ge"));
                    array_push($rules, array("field" => "sorteo_interno.tipo", "data" => "2", "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $json = json_encode($filtro);


                    $UsuarioSorteo = new UsuarioSorteo();
                    $data = $UsuarioSorteo->getUsuarioSorteosCustomWithoutPosition("usuario_sorteo.*,usuario_mandante.nombres,sorteo_interno.*", "usuario_sorteo.valor", "DESC", 0, 100, $json, true, '');

                    //print_r($data);

                    $data = json_decode($data);

                    $final = [];

                    $pos = 1;

                    $cumpleCondicion = false;
                    foreach ($data->data as $key => $value) {

                        $array = [];
                        $array["Position"] = $pos;
                        $array["Id"] = $value->{"usuario_sorteo.ususorteo_id"};

                        $sorteosAnalizados = $sorteosAnalizados . $value->{"usuario_sorteo.sorteo_id"} . ",";
                        $SorteoInterno = new SorteoInterno();
                        $SorteoDetalle = new SorteoDetalle();

                        //$bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
                        //$bonos = json_decode($bonos);


                        $rules = [];

                        array_push($rules, array("field" => "sorteo_interno.sorteo_id", "data" => $value->{"usuario_sorteo.sorteo_id"}, "op" => "eq"));
                        array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));

                        $filtro = array("rules" => $rules, "groupOp" => "AND");

                        $json = json_encode($filtro);

                        $sorteodetalles = $SorteoDetalle->getSorteoDetallesCustom(" sorteo_detalle.*,sorteo_interno.* ", "sorteo_interno.orden", "desc", 0, 1000, $json, TRUE);

                        $sorteodetalles = json_decode($sorteodetalles);

                        $final = [];

                        $creditosConvert = 0;

                        $cumpleCondicionPais = false;
                        $cumpleCondicionCont = 0;

                        $condicionesProducto = 0;

                        foreach ($sorteodetalles->data as $key2 => $value2) {

                            switch ($value2->{"sorteo_detalle.tipo"}) {
                                case "RANK":
                                    if ($value2->{"sorteo_detalle.moneda"} == $UsuarioMandante->moneda) {
                                        if ($TransaccionApi->valor >= $value2->{"sorteo_detalle.valor"}) {
                                            if ($TransaccionApi->valor <= $value2->{"sorteo_detalle.valor2"}) {
                                                $creditosConvert = $value2->{"sorteo_detalle.valor3"};
                                            }

                                        }
                                    }

                                    break;


                                case "CONDPAISUSER":

                                    if ($value2->{"sorteo_detalle.valor"} == $Usuario->paisId) {
                                        $cumpleCondicionPais = true;
                                    }
                                    $cumpleCondicionCont++;

                                    break;

                                default:

                                    if (stristr($value2->{"sorteo_detalle.tipo"}, 'CONDGAME')) {

                                        $idGame = explode("CONDGAME", $value2->{"sorteo_detalle.tipo"})[1];

                                        if ($TransaccionApi->productoId == $idGame) {
                                            $cumpleCondicion = true;
                                        }elseif($idGame=="_ALL"){

                                            //print_r("Si es como esperaba");
                                            $cumpleCondicion = true;
                                        }
                                        $condicionesProducto++;
                                    }

                                    if (stristr($value2->{"sorteo_detalle.tipo"}, 'CONDPROVIDER')) {

                                        $idProvider = explode("CONDPROVIDER", $value2->{"sorteo_detalle.tipo"})[1];

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
                            //print_r("entro2");
                            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();

                            $UsuarioSorteo = new UsuarioSorteo($value->{"usuario_sorteo.ususorteo_id"});
                            $UsuarioSorteo->valor = $UsuarioSorteo->valor + $creditosConvert;
                            $UsuarioSorteo->valorBase = ($UsuarioSorteo->valorBase + $TransaccionApi->valor);
                            //print_r($TransaccionApi);

                            //print_r($UsuarioSorteo);
                            $TransaccionJuego = new TransaccionJuego("", $TransaccionApi->getIdentificador());

                            $UsuarioSorteoMySqlDAO = new UsuarioSorteoMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                            $UsuarioSorteoMySqlDAO->update($UsuarioSorteo);

                            $TransjuegoInfo = new TransjuegoInfo();
                            $TransjuegoInfo->productoId = $TransaccionApi->productoId;
                            $TransjuegoInfo->transaccionId = $TransaccionApi->transaccionId;
                            $TransjuegoInfo->tipo = "SORTEO";
                            $TransjuegoInfo->descripcion = $value->{"usuario_sorteo.ususorteo_id"};
                            $TransjuegoInfo->valor = $creditosConvert;
                            $TransjuegoInfo->transapiId = $TransaccionApi->transapiId;
                            $TransjuegoInfo->usucreaId = 0;
                            $TransjuegoInfo->usumodifId = 0;
                            $TransjuegoInfo->identificador = $TransaccionApi->identificador;
                            $TransjuegoInfo->transjuegoId = $TransaccionJuego->transjuegoId;

                            $title = '';
                            $messageBody = '';
                            $lotteryName = $value->{"sorteo_interno.nombre"};

                            switch(strtolower($Usuario->idioma)) {
                                case 'es':
                                    $title = 'Notificacion';
                                    $messageBody = "¡ Bien :thumbsup: ! Estas participando en el sorteo {$lotteryName} :clap:";
                                    break;
                                case 'en':
                                    $title = 'Notification';
                                    $messageBody = "¡ Great :thumbsup: ! You are participating in the Raffle {$lotteryName} :clap:";
                                    break;
                                case 'pt':
                                    $title = 'Notificação';
                                    $messageBody = "Mandou bem! :thumbsup: Você está participando do {$lotteryName} :clap:";
                                    break;
                            }

                            $UsuarioMensaje = new UsuarioMensaje();
                            $UsuarioMensaje->usufromId = 0;
                            $UsuarioMensaje->usutoId = $UsuarioSorteo->getUsuarioId();
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

                            /*
                                                        $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

                                                        $usuarios = json_decode($usuarios);

                                                        $usuariosFinal = [];

                                                        foreach ($usuarios->data as $key => $value) {

                                                            $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data);
                                                            $WebsocketUsuario->sendWSMessage();

                                                        }*/

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
                    //print_r("entro3");
                    if ($sorteosAnalizados != "") {
                        $sorteosAnalizados = $sorteosAnalizados . '0';
                    }

                    $SorteoInterno = new SorteoInterno();

                    $rules = [];

                    //Traemos todos los sorteos internos que se encuentren en estado activo A
                    if ($sorteosAnalizados != '') {
                        array_push($rules, array("field" => "sorteo_interno.sorteo_id", "data" => "$sorteosAnalizados", "op" => "ni"));
                    }

                    if($TransjuegoLog != null){
                        array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));
                        array_push($rules, array("field" => "sorteo_interno.fecha_inicio", "data" => "$TransjuegoLog->fechaCrea", "op" => "le"));
                        array_push($rules, array("field" => "sorteo_interno.fecha_fin", "data" => "$TransjuegoLog->fechaCrea", "op" => "ge"));
                        //array_push($rules, array("field" => "sorteo_interno.tipo", "data" => "2", "op" => "eq"));
                        array_push($rules, array("field" => "sorteo_interno.habilita_casino", "data" => "1", "op" => "eq"));
                        array_push($rules, array("field" => "sorteo_interno.mandante", "data" => $UsuarioMandante->mandante, "op" => "eq"));

                    }else{
                        array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));
                        array_push($rules, array("field" => "sorteo_interno.fecha_inicio", "data" => "$TransaccionApi->fechaCrea", "op" => "le"));
                        array_push($rules, array("field" => "sorteo_interno.fecha_fin", "data" => "$TransaccionApi->fechaCrea", "op" => "ge"));
                        //array_push($rules, array("field" => "sorteo_interno.tipo", "data" => "2", "op" => "eq"));
                        array_push($rules, array("field" => "sorteo_interno.habilita_casino", "data" => "1", "op" => "eq"));
                        array_push($rules, array("field" => "sorteo_interno.mandante", "data" => $UsuarioMandante->mandante, "op" => "eq"));

                    }

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $json = json_encode($filtro);


                    $data = $SorteoInterno->getSorteosCustom("sorteo_interno.*", "sorteo_interno.orden", "DESC", 0, 1000, $json, true, '');


                    $data = json_decode($data);

                    $final = [];

                    $pos = 1;
                    $sorteosAnalizados = '';

                    foreach ($data->data as $key => $value) {

                        $pegatinas=$value->{"sorteo_interno.pegatinas"};

                        $SorteoDetalle = new SorteoDetalle();

                        //$bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
                        //$bonos = json_decode($bonos);


                        $rules = [];

                        array_push($rules, array("field" => "sorteo_interno.sorteo_id", "data" => $value->{"sorteo_interno.sorteo_id"}, "op" => "eq"));
                        array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));
                        array_push($rules, array("field" => "sorteo_interno.mandante", "data" => $UsuarioMandante->mandante, "op" => "eq"));


                        $filtro = array("rules" => $rules, "groupOp" => "AND");

                        $json = json_encode($filtro);

                        $sorteodetalles = $SorteoDetalle->getSorteoDetallesCustom(" sorteo_detalle.*,sorteo_interno.* ", "sorteo_interno.orden", "desc", 0, 1000, $json, TRUE);

                        $sorteodetalles = json_decode($sorteodetalles);

                        $final = [];

                        $creditosConvert = 0;

                        $cumpleCondicion = false;
                        $needSubscribe = false;

                        $cumpleCondicionPais = false;
                        $cumpleCondicionCont = 0;

                        $condicionesProducto = 0;
                        $puederepetirBono = false;

                        $minBetPrice = 0;
                        $minBetPrice2 = 0;
                        $NUMBERCASINOSTICKERS=0;
                        $NUMBERDEPOSITSTICKERS=0;
                        $NUMBERSPORTSBOOKSTICKERS=0;




                        foreach ($sorteodetalles->data as $key2 => $value2) {

                            switch ($value2->{"sorteo_detalle.tipo"}) {
                                case "RANK":
                                    if ($value2->{"sorteo_detalle.moneda"} == $UsuarioMandante->moneda) {
                                        if ($valorTicket >= $value2->{"sorteo_detalle.valor"}) {
                                            if ($valorTicket <= $value2->{"sorteo_detalle.valor2"}) {
                                                $creditosConvert = $value2->{"sorteo_detalle.valor3"};
                                            }

                                        }
                                    }

                                    break;

                                case "USERSUBSCRIBE":

                                    if($value2->{"sorteo_detalle.valor"} ==0){

                                    }else{
                                        $needSubscribe=true;
                                    }


                                    break;

                                case "MINBETPRICECASINO":

                                    if ($value2->{"sorteo_detalle.moneda"} == $UsuarioMandante->moneda) {

                                        $minBetPrice=floatval($value2->{"sorteo_detalle.valor"});
                                    }

                                    break;




                                case "MINBETPRICE2CASINO":

                                    if ($value2->{"sorteo_detalle.moneda"} == $UsuarioMandante->moneda) {

                                        $minBetPrice2=floatval($value2->{"sorteo_detalle.valor"});
                                    }

                                    //print_r("MINBETPRICE2CASINO***");

                                    //print_r($minBetPrice2);

                                    //print_r("MINBETPRICE2CASINO");

                                    break;

                                case "VISIBILIDAD":

                                    if ($value2->{"sorteo_detalle.valor"} == 1) {
                                        $needSubscribe = true;

                                    } else {
                                    }

                                    break;


                                case "CONDPAISUSER":

                                    if ($value2->{"sorteo_detalle.valor"} == $Usuario->paisId) {
                                        $cumpleCondicionPais = true;
                                    }
                                    $cumpleCondicionCont++;

                                    break;

                                case "REPETIRSORTEO":

                                    if ($value2->{"sorteo_detalle.valor"} == '1') {

                                        $puederepetirBono = true;
                                    }

                                    break;
                                case "NUMBERCASINOSTICKERS":

                                    if ($value2->{"sorteo_detalle.moneda"} == $UsuarioMandante->moneda) {

                                        $NUMBERCASINOSTICKERS = $value2->{"sorteo_detalle.valor"};
                                    }
                                    break;
                                case "NUMBERDEPOSITSTICKERS":

                                    if ($value2->{"sorteo_detalle.moneda"} == $UsuarioMandante->moneda) {

                                        $NUMBERDEPOSITSTICKERS = $value2->{"sorteo_detalle.valor"};
                                    }
                                    break;
                                case "NUMBERSPORTSBOOKSTICKERS":

                                    if ($value2->{"sorteo_detalle.moneda"} == $UsuarioMandante->moneda) {

                                        $NUMBERSPORTSBOOKSTICKERS = $value2->{"sorteo_detalle.valor"};
                                    }
                                    break;

                                default:

                                    if (stristr($value2->{"sorteo_detalle.tipo"}, 'CONDGAME')) {

                                        $idGame = explode("CONDGAME", $value2->{"sorteo_detalle.tipo"})[1];

                                        if ($ProductoMandante->prodmandanteId == $idGame) {
                                            $cumpleCondicion = true;
                                        }elseif($idGame=="_ALL"){

                                            //print_r("Si es como esperaba");
                                            $cumpleCondicion = true;
                                        }

                                        $condicionesProducto++;
                                    }



                                    if (stristr($value2->{"sorteo_detalle.tipo"}, 'CONDPROVIDER')) {

                                        $idProvider = explode("CONDPROVIDER", $value2->{"sorteo_detalle.tipo"})[1];

                                        if ($Producto->proveedorId == $idProvider) {
                                            $cumpleCondicion = true;
                                        }elseif($idProvider=="_ALL"){
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

                        if ($minBetPrice2 > floatval($valorTicket)) {
                            $cumpleCondicion = false;
                        }

                        //print_r("cumplecondicion");



                        if ($needSubscribe) {

                            $sqlRepiteSorteo = "select * from usuario_sorteo a where a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"}  . "' AND a.usuario_id = '" . $UsuarioMandante->usumandanteId  . "' AND a.estado = 'I'";
                            $repiteSorteo = $this->execQuery('',$sqlRepiteSorteo);
                            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();

                            if(oldCount($repiteSorteo) == 0){
                                $cumpleCondicion=false;
                            }
                        }


                        if ($cumpleCondicion) {

                            if ($puederepetirBono) {


                            } else {

                                $sqlRepiteSorteo = "select * from usuario_sorteo a where a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.usuario_id = '" . $UsuarioMandante->usumandanteId . "'";
                                $repiteSorteo = $this->execQuery('',$sqlRepiteSorteo);

                                if ((!$puederepetirBono && oldCount($repiteSorteo) == 0)) {

                                } else {
                                    $cumpleCondicion = false;
                                }

                            }
                        }

                        if ($cumpleCondicion && !$needSubscribe) {
                            print_r("CUMPLECONDICION y DEBERIA ENTRAR");


                            $messageNot='';
                            if($pegatinas==1){
                                $estado = "P";
                                $sqlRepiteSorteo = "select * from preusuario_sorteo a where a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"}  . "' AND a.usuario_id = '" . $UsuarioMandante->usumandanteId  . "' AND a.tipo='1' AND a.estado = '". $estado . "'";
                                $repiteSorteo = $this->execQuery('',$sqlRepiteSorteo);
                                $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();

                                //print_r("Repiteeeee");

                                //print_r($sqlRepiteSorteo);

                                //print_r("Repiteeeee");

                                if(oldCount($repiteSorteo) == 0){
                                    //print_r('entro 2');
                                    $BonoInterno = new BonoInterno();
                                    $PreUsuarioSorteo = new PreUsuarioSorteo();
                                    $PreUsuarioSorteo->usuarioId = $UsuarioMandante->usumandanteId;
                                    $PreUsuarioSorteo->sorteoId = $value->{"sorteo_interno.sorteo_id"};
                                    $PreUsuarioSorteo->valor = 0;
                                    $PreUsuarioSorteo->posicion = 0;
                                    $PreUsuarioSorteo->valorBase = $minBetPrice;
                                    $PreUsuarioSorteo->usucreaId = 0;
                                    $PreUsuarioSorteo->usumodifId = 0;
                                    $PreUsuarioSorteo->mandante = $UsuarioMandante->mandante;
                                    $PreUsuarioSorteo->tipo = 1;
                                    if($valorTicket < $minBetPrice){
                                        $PreUsuarioSorteo->estado = "P";

                                    }else{
                                        $PreUsuarioSorteo->estado = "A";
                                    }
                                    $PreUsuarioSorteo->errorId = 0;
                                    $PreUsuarioSorteo->idExterno = 0;
                                    $PreUsuarioSorteo->version = 0;
                                    $PreUsuarioSorteo->apostado =  $valorTicket;
                                    $PreUsuarioSorteo->codigo = 0;
                                    $PreUsuarioSorteo->externoId = 0;
                                    $PreUsuarioSorteo->valor = $PreUsuarioSorteo->valor + $creditosConvert;
                                    //$UsuarioSorteo->valorBase = ($UsuarioSorteo->valorBase + $TransaccionApi->valor);

                                    $PreUsuarioSorteoMySqlDAO = new PreUsuarioSorteoMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                                    $idUsuSorteo = $PreUsuarioSorteoMySqlDAO->insert($PreUsuarioSorteo);



                                }else{

                                    //print_r("No repite");
                                    //print_r($TransaccionApi);
                                    $ususorteoId = $repiteSorteo[0]->{"a.preususorteo_id"};
                                    $BonoInterno = new BonoInterno();
                                    $idUsuSorteo=$ususorteoId;

                                    $transaccion = $TransjuegoInfoMySqlDAO->getTransaction();
                                    $sql= "UPDATE preusuario_sorteo SET apostado = apostado + " . (floatval($valorTicket)) . " WHERE preususorteo_id =" . $ususorteoId;
                                    $BonoInterno->execQuery($transaccion, $sql);

                                    $sql= "UPDATE preusuario_sorteo SET estado='A'  WHERE apostado >= valor_base and estado = 'P'  AND preususorteo_id =" . $ususorteoId;
                                    $BonoInterno->execQuery($transaccion, $sql);



                                }
                                $messageNot='¡ Bien :thumbsup: ! Estas sumando stickers para el Sorteo ' . $value->{"sorteo_interno.nombre"} . ' :clap:';

                            }else{
                                $estado = "P";
                                $sqlRepiteSorteo = "select * from usuario_sorteo a where a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"}  . "' AND a.usuario_id = '" . $UsuarioMandante->usumandanteId  . "' AND a.estado = '". $estado . "'";
                                $repiteSorteo = $this->execQuery('',$sqlRepiteSorteo);
                                $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();

                                if(oldCount($repiteSorteo) == 0){
                                    $UsuarioSorteo = new UsuarioSorteo();
                                    $UsuarioSorteo->usuarioId = $UsuarioMandante->usumandanteId;
                                    $UsuarioSorteo->sorteoId = $value->{"sorteo_interno.sorteo_id"};
                                    $UsuarioSorteo->valor = 0;
                                    $UsuarioSorteo->posicion = 0;
                                    $UsuarioSorteo->valorBase = $minBetPrice;
                                    $UsuarioSorteo->usucreaId = 0;
                                    $UsuarioSorteo->usumodifId = 0;
                                    $UsuarioSorteo->mandante = $UsuarioMandante->mandante;
                                    if($valorTicket < $minBetPrice){
                                        $UsuarioSorteo->estado = "P";

                                    }else{
                                        $UsuarioSorteo->estado = "A";
                                    }
                                    $UsuarioSorteo->errorId = 0;
                                    $UsuarioSorteo->idExterno = 0;
                                    $UsuarioSorteo->version = 0;
                                    $UsuarioSorteo->apostado =  $valorTicket;
                                    $UsuarioSorteo->codigo = 0;
                                    $UsuarioSorteo->externoId = 0;
                                    $UsuarioSorteo->valor = $UsuarioSorteo->valor + $creditosConvert;
                                    //$UsuarioSorteo->valorBase = ($UsuarioSorteo->valorBase + $TransaccionApi->valor);

                                    $UsuarioSorteoMySqlDAO = new UsuarioSorteoMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                                    $idUsuSorteo = $UsuarioSorteoMySqlDAO->insert($UsuarioSorteo);
                                }else{
                                    $ususorteoId = $repiteSorteo[0]->{"a.ususorteo_id"};
                                    $BonoInterno = new BonoInterno();
                                    $idUsuSorteo = $ususorteoId;

                                    $transaccion = $TransjuegoInfoMySqlDAO->getTransaction();

                                    $sql= "UPDATE usuario_sorteo SET apostado = apostado + " . (floatval($valorTicket)) . " WHERE ususorteo_id =" . $ususorteoId;
                                    $BonoInterno->execQuery($transaccion, $sql);

                                    $sql= "UPDATE usuario_sorteo SET estado='A'  WHERE apostado >= valor_base and estado = 'P'  AND ususorteo_id =" . $ususorteoId;
                                    $BonoInterno->execQuery($transaccion, $sql);

                                }

                                $messageNot='¡ Bien :thumbsup: ! Estas participando en el sorteo ' . $value->{"sorteo_interno.nombre"} . ' :clap:';

                            }







                            /*$UsuarioMensaje = new UsuarioMensaje();
                            $UsuarioMensaje->usufromId = 0;
                            $UsuarioMensaje->usutoId = $UsuarioSorteo->getUsuarioId();
                            $UsuarioMensaje->isRead = 0;
                            $UsuarioMensaje->body = $messageNot;
                            $UsuarioMensaje->msubject = 'Notificacion';
                            $UsuarioMensaje->parentId = 0;
                            $UsuarioMensaje->proveedorId = 0;
                            $UsuarioMensaje->tipo = "PUSHNOTIFICACION";
                            $UsuarioMensaje->paisId = 0;
                            $UsuarioMensaje->fechaExpiracion = '';

                            $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                            $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);*/





                            $TransjuegoInfo = new TransjuegoInfo();
                            $TransjuegoInfo->productoId = $ProductoMandante->prodmandanteId;
                            if($TransaccionApi != null){
                                $TransjuegoInfo->transaccionId = $TransaccionApi->transaccionId;

                            }else{
                                $TransjuegoInfo->transaccionId = $TransjuegoLog->transaccionId;

                            }
                            $TransjuegoInfo->tipo = "SORTEO";
                            $TransjuegoInfo->descripcion = $idUsuSorteo;
                            $TransjuegoInfo->valor = $creditosConvert;
                            if($TransjuegoLog != null){
                                $TransjuegoInfo->transapiId = $TransjuegoLog->getTransjuegologId();
                                $TransjuegoInfo->transjuegoId = $TransaccionJuego->transjuegoId;

                            }else{
                                $TransjuegoInfo->transapiId = $TransaccionApi->transapiId;

                            }
                            $TransjuegoInfo->usucreaId = 0;
                            $TransjuegoInfo->usumodifId = 0;



                            $TransjuegoInfoMySqlDAO->insert($TransjuegoInfo);
                            $TransjuegoInfoMySqlDAO->getTransaction()->commit();

                            print_r($TransjuegoInfo);

                            $mensajesRecibidos = [];
                            $array = [];

                            $array["body"] = $messageNot;

                            array_push($mensajesRecibidos, $array);
                            $data = array();
                            $data["messages"] = $mensajesRecibidos;
                            //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());
                            $ConfigurationEnvironment = new ConfigurationEnvironment();

                            if (!$ConfigurationEnvironment->isDevelopment() && false) {

                                if(false) {

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

            case "SPORTBOOK":

                $ItTicketEnc = new ItTicketEnc($ticketId);


                $Usuario = new Usuario($ItTicketEnc->usuarioId);
                $UsuarioMandante = new UsuarioMandante('',$Usuario->usuarioId,$Usuario->mandante);

                $cumpleCondicion = false;
                $sorteosAnalizados = '';

                if (!$cumpleCondicion) {
                    //print_r("entro3");
                    if ($sorteosAnalizados != "") {
                        $sorteosAnalizados = $sorteosAnalizados . '0';
                    }

                    $detalleCuotaTotal = 1;

                    $ConfigurationEnvironment = new ConfigurationEnvironment();

                    if(!$ConfigurationEnvironment->isDevelopment() || true){
                        //$sqlSport = "select te.betmode,te.vlr_apuesta,td.ticket_id,td.apuesta,td.agrupador,td.logro,td.opcion,td.apuesta_id,td.agrupador_id,td.fecha_evento,td.hora_evento,te.usuario_id,td.sportid,td.matchid,td.ligaid from it_ticket_det td INNER JOIN it_ticket_enc te ON (td.ticket_id = te.ticket_id ) where  te.ticket_id='".$ticketId."' ";
                        $sqlSport = "select te.vlr_apuesta,td.ticket_id,td.apuesta,td.agrupador,td.logro,td.opcion,td.apuesta_id,td.agrupador_id,td.sportid,td.fecha_evento,td.hora_evento,te.usuario_id from it_ticket_det td INNER JOIN it_ticket_enc te ON (td.ticket_id = te.ticket_id ) where  te.ticket_id='" . $ticketId . "' ";

                    }else{
                        $sqlSport = "select te.vlr_apuesta,te.ticket_id,td.apuesta,td.agrupador,td.logro,td.opcion,td.apuesta_id,td.agrupador_id,td.sportid,td.fecha_evento,te.usuario_id from transsportsbook_detalle td INNER JOIN transaccion_sportsbook te ON (td.transsport_id = te.transsport_id ) where  te.ticket_id='" . $ticketId . "' ";
                    }

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

                        if(!$ConfigurationEnvironment->isDevelopment()) {

                            $usuarioId = $detalle->usuario_id;
                        }else{

                        }
                        $betmode = $detalle->betmode;
                        $detalleCuotaTotal = $detalleCuotaTotal * $detalle->logro;

                    }
                    $detallesFinal = json_decode(json_encode($array));

                    $detalleSelecciones = $detallesFinal;

                    $SorteoInterno = new SorteoInterno();

                    $rules = [];

                    //Traemos todos los sorteos internos que se encuentren en estado activo A
                    if ($sorteosAnalizados != '') {
                        array_push($rules, array("field" => "sorteo_interno.sorteo_id", "data" => "$sorteosAnalizados", "op" => "ni"));
                    }

                    array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));
                    //array_push($rules, array("field" => "sorteo_interno.fecha_inicio", "data" => "$ItTicketEnc->fechaCrea", "op" => "le"));
                    //array_push($rules, array("field" => "sorteo_interno.fecha_fin", "data" => "$ItTicketEnc->fechaCrea", "op" => "ge"));
                    //array_push($rules, array("field" => "sorteo_interno.tipo", "data" => "2", "op" => "eq"));
                    array_push($rules, array("field" => "sorteo_interno.habilita_deportivas", "data" => "1", "op" => "eq"));
                    array_push($rules, array("field" => "sorteo_interno.mandante", "data" => $UsuarioMandante->mandante, "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $json = json_encode($filtro);
                    print_r('entro');
                    print_r($rules);


                    $data = $SorteoInterno->getSorteosCustom("sorteo_interno.*", "sorteo_interno.orden", "DESC", 0, 1000, $json, true, '');


                    $data = json_decode($data);

                    $final = [];

                    $pos = 1;
                    $sorteosAnalizados = '';

                    foreach ($data->data as $key => $value) {

                        $pegatinas=$value->{"sorteo_interno.pegatinas"};

                        $SorteoDetalle = new SorteoDetalle();

                        //$bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
                        //$bonos = json_decode($bonos);


                        $rules = [];

                        array_push($rules, array("field" => "sorteo_interno.sorteo_id", "data" => $value->{"sorteo_interno.sorteo_id"}, "op" => "eq"));
                        array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));
                        array_push($rules, array("field" => "sorteo_interno.mandante", "data" => $UsuarioMandante->mandante, "op" => "eq"));

                        $filtro = array("rules" => $rules, "groupOp" => "AND");

                        $json = json_encode($filtro);

                        $sorteodetalles = $SorteoDetalle->getSorteoDetallesCustom(" sorteo_detalle.*,sorteo_interno.* ", "sorteo_interno.orden", "desc", 0, 1000, $json, TRUE);

                        $sorteodetalles = json_decode($sorteodetalles);

                        $final = [];

                        $creditosConvert = 0;

                        $cumpleCondicion = false;
                        $needSubscribe = false;

                        $cumpleCondicionPais = false;
                        $cumpleCondicionCont = 0;

                        $condicionesProducto = 0;
                        $puederepetirBono = false;

                        $minBetPrice = 0;
                        $minBetPrice2 = 0;


                        $cumplecondicionproducto = false;
                        $condicionesproducto = 0;

                        $valorMaximoASumar = 0;
                        $valorapostado = 0;
                        $valorrequerido = 0;


                        $sePuedeSimples = 0;
                        $sePuedeCombinadas = 0;
                        $minselcount = 0;

                        if ($value->condicional == 'NA' || $value->condicional == '') {
                            $tipocomparacion = "OR";

                        } else {
                            $tipocomparacion = $value->condicional;

                        }

                        $NUMBERCASINOSTICKERS=0;
                        $NUMBERDEPOSITSTICKERS=0;
                        $NUMBERSPORTSBOOKSTICKERS=0;
                        foreach ($sorteodetalles->data as $key2 => $value2) {

                            switch ($value2->{"sorteo_detalle.tipo"}) {


                                case "USERSUBSCRIBE":

                                    if($value2->{"sorteo_detalle.valor"} ==0){

                                    }else{
                                        $needSubscribe=true;
                                    }


                                    break;

                                case "MINBETPRICESPORTSBOOK":

                                    if ($value2->{"sorteo_detalle.moneda"} == $UsuarioMandante->moneda) {

                                        $minBetPrice=floatval($value2->{"sorteo_detalle.valor"});
                                    }


                                    break;

                                case "VISIBILIDAD":

                                    if ($value2->{"sorteo_detalle.valor"} == 1) {
                                        $needSubscribe = true;

                                    } else {
                                    }

                                    break;


                                case "CONDPAISUSER":

                                    if ($value2->{"sorteo_detalle.valor"} == $Usuario->paisId) {
                                        $cumpleCondicionPais = true;
                                    }
                                    $cumpleCondicionCont++;

                                    break;

                                case "REPETIRSORTEO":

                                    if ($value2->{"sorteo_detalle.valor"} == '1') {

                                        $puederepetirBono = true;
                                    }

                                    break;
                                case "NUMBERCASINOSTICKERS":

                                    if ($value2->{"sorteo_detalle.moneda"} == $UsuarioMandante->moneda) {

                                        $NUMBERCASINOSTICKERS = $value2->{"sorteo_detalle.valor"};
                                    }
                                    break;
                                case "NUMBERDEPOSITSTICKERS":

                                    if ($value2->{"sorteo_detalle.moneda"} == $UsuarioMandante->moneda) {

                                        $NUMBERDEPOSITSTICKERS = $value2->{"sorteo_detalle.valor"};
                                    }
                                    break;
                                case "NUMBERSPORTSBOOKSTICKERS":

                                    if ($value2->{"sorteo_detalle.moneda"} == $UsuarioMandante->moneda) {

                                        $NUMBERSPORTSBOOKSTICKERS = $value2->{"sorteo_detalle.valor"};
                                    }
                                    break;

                                case "MINBETPRICE2SPORTSBOOK":

                                    if ($value2->{"sorteo_detalle.moneda"} == $UsuarioMandante->moneda) {

                                        $minBetPrice2=floatval($value2->{"sorteo_detalle.valor"});
                                    }


                                    break;


                                case "LIVEORPREMATCH":


                                    if ($value2->{"sorteo_detalle.valor"}  == 2) {
                                        if ($betmode == "PreLive") {
                                            $cumplecondicionproducto = true;

                                        } else {
                                            $cumplecondicionproducto = false;


                                        }

                                    }

                                    if ($value2->{"sorteo_detalle.valor"} == 1) {
                                        if ($betmode == "Live") {
                                            $cumplecondicionproducto = true;

                                        } else {
                                            $cumplecondicionproducto = false;


                                        }

                                    }

                                    if ($value2->{"sorteo_detalle.valor"}  == 0) {
                                        /*if($betmode == "Mixed") {
                                            $cumplecondicionproducto = true;

                                        }else{
                                            $cumplecondicionproducto = false;


                                        }*/

                                    }

                                    break;

                                case "MINSELCOUNT":
                                    $minselcount = $value2->{"sorteo_detalle.valor"} ;

                                    if ($value2->{"sorteo_detalle.valor"}  > oldCount($detalleSelecciones)) {
                                        //$cumpleCondicion = false;

                                    }

                                    break;

                                case "MINSELPRICE":

                                    foreach ($detalleSelecciones as $item) {
                                        if ($value2->{"sorteo_detalle.valor"}  > $item->Cuota) {
                                            $cumpleCondicion = false;

                                        }
                                    }


                                    break;


                                case "MINSELPRICETOTAL":

                                    if ($value2->{"sorteo_detalle.valor"}  > $detalleCuotaTotal) {
                                        $cumpleCondicion = false;

                                    }


                                    break;

                                /*case "MINBETPRICE":


                                    if ($value2->{"sorteo_detalle.valor"}  > $detalleValorApuesta) {
                                        $cumpleCondicion = false;

                                    }

                                    break;*/

                                case "ITAINMENT1":

                                    foreach ($detalleSelecciones as $item) {


                                        if ($tipocomparacion == "OR") {
                                            if (($value2->{"sorteo_detalle.valor"} ) == ($item->Deporte)) {
                                                $cumplecondicionproducto = true;


                                            }
                                        } elseif ($tipocomparacion == "AND") {
                                            if ($value2->{"sorteo_detalle.valor"}  != $item->Deporte) {
                                                $cumplecondicionproducto = false;


                                            }

                                            if ($condicionesproducto == 0) {
                                                if ($value2->{"sorteo_detalle.valor"}  == $item->Deporte) {
                                                    $cumplecondicionproducto = true;
                                                }
                                            } else {
                                                if ($value2->{"sorteo_detalle.valor"}  == $item->Deporte && $cumplecondicionproducto) {
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
                                            if ($value2->{"sorteo_detalle.valor"}  == $item->Liga) {
                                                $cumplecondicionproducto = true;

                                            }
                                        } elseif ($tipocomparacion == "AND") {
                                            if ($value2->{"sorteo_detalle.valor"} != $item->Liga) {
                                                $cumplecondicionproducto = false;

                                            }

                                            if ($condicionesproducto == 0) {
                                                if ($value2->{"sorteo_detalle.valor"} == $item->Liga) {
                                                    $cumplecondicionproducto = true;
                                                }
                                            } else {
                                                if ($value2->{"sorteo_detalle.valor"} == $item->Liga && $cumplecondicionproducto) {
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
                                            if ($value2->{"sorteo_detalle.valor"} == $item->Evento) {
                                                $cumplecondicionproducto = true;

                                            }
                                        } elseif ($tipocomparacion == "AND") {
                                            if ($value2->{"sorteo_detalle.valor"} != $item->Evento) {
                                                $cumplecondicionproducto = false;

                                            }

                                            if ($condicionesproducto == 0) {

                                                if ($value2->{"sorteo_detalle.valor"} == $item->Evento) {
                                                    $cumplecondicionproducto = true;
                                                }
                                            } else {

                                                if ($value2->{"sorteo_detalle.valor"} == $item->Evento && $cumplecondicionproducto) {
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
                                            if ($value2->{"sorteo_detalle.valor"} == $item->DeporteMercado) {
                                                $cumplecondicionproducto = true;


                                            }
                                        } elseif ($tipocomparacion == "AND") {
                                            if ($value2->{"sorteo_detalle.valor"} != $item->DeporteMercado) {
                                                $cumplecondicionproducto = false;

                                            }

                                            if ($condicionesproducto == 0) {
                                                if ($value2->{"sorteo_detalle.valor"} == $item->DeporteMercado) {
                                                    $cumplecondicionproducto = true;
                                                }
                                            } else {
                                                if ($value2->{"sorteo_detalle.valor"} == $item->DeporteMercado && $cumplecondicionproducto) {
                                                    $cumplecondicionproducto = true;

                                                }
                                            }

                                        }

                                    }

                                    $condicionesproducto++;

                                    break;

                                case "ITAINMENT82":

                                    if ($value2->{"sorteo_detalle.valor"} == 1) {
                                        $sePuedeSimples = 1;

                                    }
                                    if ($value2->{"sorteo_detalle.valor"} == 2) {
                                        $sePuedeCombinadas = 1;

                                    }
                                    break;

                                default:


                                    break;
                            }

                        }

                        if ($condicionesProducto == 0 && !$cumpleCondicion) {
                            $cumpleCondicion = true;
                        }

                        if (!$cumpleCondicionPais && $cumpleCondicionCont > 0) {

                            $cumpleCondicion = false;

                        }

                        if ($minBetPrice2 > floatval($ItTicketEnc->vlrApuesta)) {
                            $cumpleCondicion = false;
                        }

                        if ($sePuedeCombinadas != 0 || $sePuedeSimples != 0) {

                            if (oldCount($detalleSelecciones) == 1 && !$sePuedeSimples) {
                                $cumpleCondicion = false;
                            }

                            if (oldCount($detalleSelecciones) > 1 && !$sePuedeCombinadas) {
                                $cumpleCondicion = false;
                            }

                            if ($sePuedeCombinadas) {
                                if (oldCount($detalleSelecciones) > 1 && oldCount($detalleSelecciones) < $minselcount) {
                                    $cumpleCondicion = false;

                                }
                            }
                        } else {
                            if (oldCount($detalleSelecciones) > 1 && oldCount($detalleSelecciones) < $minselcount) {
                                $cumpleCondicion = false;

                            }
                        }


                        if ($needSubscribe) {

                            $sqlRepiteSorteo = "select * from usuario_sorteo a where a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"}  . "' AND a.usuario_id = '" . $UsuarioMandante->usumandanteId  . "' AND a.estado = 'I'";
                            $repiteSorteo = $this->execQuery('',$sqlRepiteSorteo);
                            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();

                            if(oldCount($repiteSorteo) == 0){
                                $cumpleCondicion=false;
                            }
                        }


                        if ($cumpleCondicion) {

                            if ($puederepetirBono) {


                            } else {

                                $sqlRepiteSorteo = "select * from usuario_sorteo a where a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.usuario_id = '" . $UsuarioMandante->usumandanteId . "'";
                                $repiteSorteo = $this->execQuery('',$sqlRepiteSorteo);

                                if ((!$puederepetirBono && oldCount($repiteSorteo) == 0)) {

                                } else {
                                    $cumpleCondicion = false;
                                }

                            }
                        }

                        if ($cumpleCondicion && !$needSubscribe) {
                            //print_r("CUMPLECONDICION");
                            $ItTicketEncInfo1MySqlDAO = new ItTicketEncInfo1MySqlDAO();


                            if($pegatinas==1){
                                $estado = "P";
                                $sqlRepiteSorteo = "select * from preusuario_sorteo a where a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"}  . "' AND  tipo='2'  AND a.usuario_id = '" . $UsuarioMandante->usumandanteId  . "' AND a.estado = '". $estado . "'";
                                $repiteSorteo = $this->execQuery('',$sqlRepiteSorteo);
                                print_r('entro2');

                                if(oldCount($repiteSorteo) == 0){
                                    $BonoInterno = new BonoInterno();
                                    $PreUsuarioSorteo = new PreUsuarioSorteo();
                                    $PreUsuarioSorteo->usuarioId = $UsuarioMandante->usumandanteId;
                                    $PreUsuarioSorteo->sorteoId = $value->{"sorteo_interno.sorteo_id"};
                                    $PreUsuarioSorteo->valor = 0;
                                    $PreUsuarioSorteo->posicion = 0;
                                    $PreUsuarioSorteo->valorBase = $minBetPrice;
                                    $PreUsuarioSorteo->usucreaId = 0;
                                    $PreUsuarioSorteo->usumodifId = 0;
                                    $PreUsuarioSorteo->mandante = $UsuarioMandante->mandante;
                                    $PreUsuarioSorteo->tipo = 2;
                                    if($ItTicketEnc->vlrApuesta < $minBetPrice){
                                        $PreUsuarioSorteo->estado = "P";

                                    }else{
                                        $PreUsuarioSorteo->estado = "A";
                                    }
                                    $PreUsuarioSorteo->errorId = 0;
                                    $PreUsuarioSorteo->idExterno = 0;
                                    $PreUsuarioSorteo->version = 0;
                                    $PreUsuarioSorteo->apostado =  $ItTicketEnc->vlrApuesta ;
                                    $PreUsuarioSorteo->codigo = 0;
                                    $PreUsuarioSorteo->externoId = 0;
                                    $PreUsuarioSorteo->valor = $PreUsuarioSorteo->valor + $creditosConvert;

                                    $PreUsuarioSorteoMySqlDAO = new PreUsuarioSorteoMySqlDAO($ItTicketEncInfo1MySqlDAO->getTransaction());
                                    $idUsuSorteo = $PreUsuarioSorteoMySqlDAO->insert($PreUsuarioSorteo);

                                }else{
                                    //print_r($ItTicketEnc);
                                    $ususorteoId = $repiteSorteo[0]->{"a.preususorteo_id"};
                                    $BonoInterno = new BonoInterno();
                                    $idUsuSorteo=$ususorteoId;

                                    $transaccion = $ItTicketEncInfo1MySqlDAO->getTransaction();
                                    $sql= "UPDATE preusuario_sorteo SET apostado = apostado + " . (floatval($ItTicketEnc->vlrApuesta)) . " WHERE preususorteo_id =" . $ususorteoId;
                                    $BonoInterno->execQuery($transaccion, $sql);


                                }
                            }else{
                                $estado = "P";
                                $sqlRepiteSorteo = "select * from usuario_sorteo a where a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"}  . "' AND a.usuario_id = '" . $UsuarioMandante->usumandanteId  . "' AND a.estado = '". $estado . "'";
                                $repiteSorteo = $this->execQuery('',$sqlRepiteSorteo);




                                if(oldCount($repiteSorteo) == 0){
                                    $UsuarioSorteo = new UsuarioSorteo();
                                    $UsuarioSorteo->usuarioId = $UsuarioMandante->usumandanteId;
                                    $UsuarioSorteo->sorteoId = $value->{"sorteo_interno.sorteo_id"};
                                    $UsuarioSorteo->valor = 0;
                                    $UsuarioSorteo->posicion = 0;
                                    $UsuarioSorteo->valorBase = $minBetPrice;
                                    $UsuarioSorteo->usucreaId = 0;
                                    $UsuarioSorteo->usumodifId = 0;

                                    if($ItTicketEnc->vlrApuesta < $minBetPrice){
                                        $UsuarioSorteo->estado = "P";

                                    }else{
                                        $UsuarioSorteo->estado = "A";
                                    }

                                    $UsuarioSorteo->errorId = 0;
                                    $UsuarioSorteo->idExterno = 0;
                                    $UsuarioSorteo->mandante = 0;
                                    $UsuarioSorteo->version = 0;
                                    $UsuarioSorteo->apostado =  $ItTicketEnc->vlrApuesta;
                                    $UsuarioSorteo->codigo = 0;
                                    $UsuarioSorteo->externoId = 0;
                                    $UsuarioSorteo->valor = $UsuarioSorteo->valor + $creditosConvert;
                                    //$UsuarioSorteo->valorBase = ($UsuarioSorteo->valorBase + $TransaccionApi->valor);

                                    $UsuarioSorteoMySqlDAO = new UsuarioSorteoMySqlDAO($ItTicketEncInfo1MySqlDAO->getTransaction());
                                    $ususorteoId = $UsuarioSorteoMySqlDAO->insert($UsuarioSorteo);

                                }else{
                                    $ususorteoId = $repiteSorteo[0]->{"a.ususorteo_id"};
                                    $BonoInterno = new BonoInterno();

                                    $transaccion = $ItTicketEncInfo1MySqlDAO->getTransaction();

                                    $sql= "UPDATE usuario_sorteo SET apostado = apostado + " . (floatval($ItTicketEnc->vlrApuesta)) . " WHERE ususorteo_id =" . $ususorteoId;
                                    $BonoInterno->execQuery($transaccion, $sql);

                                    $sql= "UPDATE usuario_sorteo SET estado='A'  WHERE apostado >= valor_base and estado = 'P'  AND ususorteo_id =" . $ususorteoId;
                                    $BonoInterno->execQuery($transaccion, $sql);

                                }


                            }







                            /*$UsuarioMensaje = new UsuarioMensaje();
                            $UsuarioMensaje->usufromId = 0;
                            $UsuarioMensaje->usutoId = $UsuarioSorteo->getUsuarioId();
                            $UsuarioMensaje->isRead = 0;
                            $UsuarioMensaje->body = '¡ Bien :thumbsup: ! Estas participando en el sorteo ' . $value->{"sorteo_interno.nombre"} . ' :clap:';
                            $UsuarioMensaje->msubject = 'Notificacion';
                            $UsuarioMensaje->parentId = 0;
                            $UsuarioMensaje->proveedorId = 0;
                            $UsuarioMensaje->tipo = "PUSHNOTIFICACION";
                            $UsuarioMensaje->paisId = 0;
                            $UsuarioMensaje->fechaExpiracion = '';

                            $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($ItTicketEncInfo1MySqlDAO->getTransaction());
                            $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);*/





                            $ItTicketEncInfo1 = new ItTicketEncInfo1();
                            $ItTicketEncInfo1->ticketId = $ItTicketEnc->ticketId;
                            $ItTicketEncInfo1->tipo = "SORTEOSTICKER";

                            $ItTicketEncInfo1->valor = $idUsuSorteo;
                            $ItTicketEncInfo1->fechaCrea = date('Y-m-d H:i:s');
                            $ItTicketEncInfo1->fechaModif = date('Y-m-d H:i:s');
                            //$ItTicketEncInfo1->valor = $creditosConvert;
                            print_r($ItTicketEncInfo1);

                            $ItTicketEncInfo1MySqlDAO->insert($ItTicketEncInfo1);
                            $ItTicketEncInfo1MySqlDAO->getTransaction()->commit();


                            /*$mensajesRecibidos = [];
                            $array = [];

                            $array["body"] = '¡ Bien :thumbsup: ! Estas participando en el sorteo ' . $value->{"sorteo_interno.nombre"} . ' :clap:';

                            array_push($mensajesRecibidos, $array);
                            $data = array();
                            $data["messages"] = $mensajesRecibidos;
                            //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());
                            $ConfigurationEnvironment = new ConfigurationEnvironment();

                            if (!$ConfigurationEnvironment->isDevelopment()) {

                                if(false) {

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
                            }*/
                            break;
                        }


                    }


                }


                break;

            case "DEPOSIT":

                //print_r("condicion1");
                //print_r($ticketId);
                //print_r("condicion2");
                //print_r($detalles);



                try {
                    $UsuarioRecarga = new UsuarioRecarga($ticketId);
                    //print_r("este es el UsuarioMandante");
                    //print_r($UsuarioRecarga->usuarioId);
                    $UsuarioMandante = new UsuarioMandante("", $UsuarioRecarga->usuarioId, $UsuarioRecarga->mandante);
                    $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
                }catch (Exception $e){
                    //print_r($e);
                }
                //print_r($UsuarioRecarga);


                $cumpleCondicion = false;
                $sorteosAnalizados = '';

                //print_r("condicion2");
                //var_dump(!$cumpleCondicion);


                if (!$cumpleCondicion) {
                    //print_r("Entre por aqui");
                    //Verificamos Detalles a tomar en cuenta al momento de verificar si puede ganar un bono
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
                    //print_r("entro32");
                    if ($sorteosAnalizados != "") {
                        $sorteosAnalizados = $sorteosAnalizados . '0';
                    }

                    $SorteoInterno = new SorteoInterno();

                    $rules = [];

                    //Traemos todos los sorteos internos que se encuentren en estado activo A
                    if ($sorteosAnalizados != '') {
                        array_push($rules, array("field" => "sorteo_interno.sorteo_id", "data" => "$sorteosAnalizados", "op" => "ni"));
                    }

                    $fechaCreaa=$UsuarioRecarga->fechaCrea;

                    if($fechaCreaa ==""){
                        $fechaCreaa = date('Y-m-d H:i:s');
                    }

                    array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));
                    array_push($rules, array("field" => "sorteo_interno.fecha_inicio", "data" => $fechaCreaa, "op" => "le"));
                    array_push($rules, array("field" => "sorteo_interno.fecha_fin", "data" => $fechaCreaa, "op" => "ge"));
                    //array_push($rules, array("field" => "sorteo_interno.tipo", "data" => "2", "op" => "eq"));
                    array_push($rules, array("field" => "sorteo_interno.habilita_deposito", "data" => "1", "op" => "eq"));
                    array_push($rules, array("field" => "sorteo_interno.mandante", "data" => $UsuarioMandante->mandante, "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $json = json_encode($filtro);

                    //print_r($json);



                    $data = $SorteoInterno->getSorteosCustom("sorteo_interno.*", "sorteo_interno.orden", "desc", 0, 1000, $json, true, '');


                    //print_r("Esta es la lista de sorteos");

                    //print_r($data);

                    $data = json_decode($data);



                    $final = [];

                    $pos = 1;
                    $sorteosAnalizados = '';

                    foreach ($data->data as $key => $value) {

                        $pegatinas=$value->{"sorteo_interno.pegatinas"};

                        $SorteoDetalle = new SorteoDetalle();

                        //$bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
                        //$bonos = json_decode($bonos);


                        $rules = [];

                        array_push($rules, array("field" => "sorteo_interno.sorteo_id", "data" => $value->{"sorteo_interno.sorteo_id"}, "op" => "eq"));
                        array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));
                        array_push($rules, array("field" => "sorteo_interno.mandante", "data" => $UsuarioMandante->mandante, "op" => "eq"));

                        $filtro = array("rules" => $rules, "groupOp" => "AND");

                        $json = json_encode($filtro);

                        $sorteodetalles = $SorteoDetalle->getSorteoDetallesCustom(" sorteo_detalle.*,sorteo_interno.* ", "sorteo_interno.orden", "desc", 0, 1000, $json, TRUE);

                        $sorteodetalles = json_decode($sorteodetalles);

                        $final = [];

                        $creditosConvert = 0;

                        $cumpleCondicion = false;
                        $needSubscribe = false;

                        $cumpleCondicionPais = false;
                        $cumpleCondicionCont = 0;

                        $condicionesProducto = 0;
                        $puederepetirBono = false;

                        $minBetPrice = 0;
                        $minBetPrice2=0;

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

                        $needSubscribe=false;

                        //print_r("condicion 3");
                        //print_r($sorteodetalles);

                        $NUMBERCASINOSTICKERS=0;
                        $NUMBERDEPOSITSTICKERS=0;
                        $NUMBERSPORTSBOOKSTICKERS=0;


                        foreach ($sorteodetalles->data as $key2 => $value2) {

                            switch ($value2->{"sorteo_detalle.tipo"}) {


                                case "USERSUBSCRIBE":

                                    if($value2->{"sorteo_detalle.valor"} ==0){

                                    }else{
                                        $needSubscribe=true;
                                    }

                                    break;

                                case "MINBETPRICEDEPOSIT":

                                    if ($value2->{"sorteo_detalle.moneda"} == $UsuarioMandante->moneda) {

                                        $minBetPrice=floatval($value2->{"sorteo_detalle.valor"});
                                    }


                                    break;


                                case "MINBETPRICE2DEPOSIT":

                                    if ($value2->{"sorteo_detalle.moneda"} == $UsuarioMandante->moneda) {

                                        $minBetPrice2=floatval($value2->{"sorteo_detalle.valor"});
                                    }
                                    break;


                                case "VISIBILIDAD":


                                    //print_r("esta es la visibilidad");
                                    //print_r($value2->{"sorteo_detalle.valor"});
                                    //print_r("fin de a visibilidad");

                                    if ($value2->{"sorteo_detalle.valor"} == 1) {
                                        $needSubscribe = true;

                                    } else {
                                    }

                                    break;


                                /*case "CANTDEPOSITOS":
                                    if ($detalleDepositos != ($value2->{"sorteo_detalle.valor"} - 1) && $value2->{"sorteo_detalle.valor"} != 0 && $bono->tipo == 2) {

                                        $cumpleCondicion = false;

                                    }

                                    break;*/

                                case "CONDEFECTIVO":
                                    if ($detalleDepositoEfectivo) {
                                        if (($value2->{"sorteo_detalle.valor"} == "true")) {
                                            $condicionmetodoPago = true;
                                        }
                                    } else {
                                        if (($value2->{"sorteo_detalle.valor"} != "true")) {
                                            $condicionmetodoPago = false;
                                        }
                                    }
                                    $condicionmetodoPagocount++;


                                    break;

                                case "CONDPAISUSER":

                                    if ($value2->{"sorteo_detalle.valor"} == $Usuario->paisId) {
                                        $cumpleCondicionPais = true;
                                    }
                                    $cumpleCondicionCont++;

                                    break;

                                case "REPETIRSORTEO":

                                    if ($value2->{"sorteo_detalle.valor"} == '1') {

                                        $puederepetirBono = true;
                                    }

                                    break;

                                case "MAXDEPOSITO":

                                    $maximodeposito = $value2->valor;
                                    if ($value2->{"sorteo_detalle.moneda"} == $detalleMonedaUSER) {

                                        if ($detalleValorDeposito > $maximodeposito && $maximodeposito != 0) {
                                            $cumpleCondicion = false;
                                        }
                                    }

                                    break;

                                case "MINDEPOSITO":

                                    $minimodeposito = $value2->{"sorteo_detalle.valor"};

                                    if ($value2->{"sorteo_detalle.moneda"} == $detalleMonedaUSER) {

                                        if ($detalleValorDeposito < $minimodeposito) {
                                            $cumpleCondicion = false;
                                        }
                                    }

                                    break;

                                case "CONDPAYMENT":

                                    //print_r("este es el detalleDepositoMetodoPago");

                                    //print_r($detalleDepositoMetodoPago);

                                    //print_r("este es el sorteo detalle");


                                    //print_r($value2->{"sorteo_detalle.valor"});

                                        if ($detalleDepositoMetodoPago == $value2->{"sorteo_detalle.valor"} && $value2->{"sorteo_detalle.valor"} != '') {
                                            $condicionmetodoPago = true;
                                        } elseif ($value2->{"sorteo_detalle.valor"} == "ALL") {
                                            $condicionmetodoPago = true;
                                        }

                                        if ($value2->{"sorteo_detalle.valor"} != '') {
                                            $condicionmetodoPagocount++;
                                        }


                                    break;

                                case "CONDPAISPV":

                                    $condicionPaisPVcount = $condicionPaisPVcount + 1;
                                    if ($value2->{"sorteo_detalle.valor"} == $detallePaisPV) {
                                        $condicionPaisPV = true;
                                    }

                                    break;

                                case "CONDDEPARTAMENTOPV":

                                    $condicionDepartamentoPVcount = $condicionDepartamentoPVcount + 1;
                                    if ($value2->{"sorteo_detalle.valor"} == $detalleDepartamentoPV) {
                                        $condicionDepartamentoPV = true;
                                    }

                                    break;

                                case "CONDCIUDADPV":

                                    $condicionCiudadPVcount = $condicionCiudadPVcount + 1;

                                    if ($value2->{"sorteo_detalle.valor"} == $detalleCiudadPV) {
                                        $condicionCiudadPV = true;
                                    }

                                    break;


                                case "CONDDEPARTAMENTOUSER":

                                    $condicionDepartamentoUSERcount = $condicionDepartamentoUSERcount + 1;
                                    if ($value2->{"sorteo_detalle.valor"} == $detalleDepartamentoUSER) {
                                        $condicionDepartamentoUSER = true;
                                    }

                                    break;

                                case "CONDCIUDADUSER":

                                    $condicionCiudadUSERcount = $condicionCiudadUSERcount + 1;

                                    if ($value2->{"sorteo_detalle.valor"} == $detalleCiudadUSER) {
                                        $condicionCiudadUSER = true;
                                    }

                                    break;

                                case "CONDPUNTOVENTA":

                                    $condicionPuntoVentacount = $condicionPuntoVentacount + 1;

                                    if ($value2->{"sorteo_detalle.valor"} == $detallePuntoVenta) {
                                        $condicionPuntoVenta = true;
                                    }

                                    break;
                                case "NUMBERCASINOSTICKERS":

                                    if ($value2->{"sorteo_detalle.moneda"} == $UsuarioMandante->moneda) {

                                        $NUMBERCASINOSTICKERS = $value2->{"sorteo_detalle.valor"};
                                    }
                                    break;
                                case "NUMBERDEPOSITSTICKERS":

                                    if ($value2->{"sorteo_detalle.moneda"} == $UsuarioMandante->moneda) {

                                        $NUMBERDEPOSITSTICKERS = $value2->{"sorteo_detalle.valor"};
                                    }
                                    break;
                                case "NUMBERSPORTSBOOKSTICKERS":

                                    if ($value2->{"sorteo_detalle.moneda"} == $UsuarioMandante->moneda) {

                                        $NUMBERSPORTSBOOKSTICKERS = $value2->{"sorteo_detalle.valor"};
                                    }
                                    break;

                                default:

                                    /*if (stristr($value2->{"sorteo_detalle.tipo"}, 'CONDPROVIDER')) {

                                        $idProvider = explode("CONDPROVIDER", $value2->{"sorteo_detalle.tipo"})[1];

                                        if ($TransaccionApi->proveedorId == $idProvider) {
                                            $cumpleCondicion = true;
                                        }
                                        $condicionesProducto++;
                                    }*/

                                    break;
                            }

                        }




                        if ($condicionPaisPVcount > 0) {
                            if (!$condicionPaisPV && $detalleDepositoEfectivo) {
                                $cumpleCondicion = false;
                            }

                        }

                        if ($condicionDepartamentoPVcount > 0) {
                            if (!$condicionDepartamentoPV) {
                                $cumpleCondicion = false;
                            }

                        }


                        if ($condicionCiudadPVcount > 0) {
                            if (!$condicionCiudadPV) {
                                $cumpleCondicion = false;
                            }

                        }

                        if ($condicionesProducto == 0 && !$cumpleCondicion) {
                            $cumpleCondicion = true;
                        }



                        if ($condicionPuntoVentacount > 0) {
                            if (!$condicionPuntoVenta) {
                                $cumpleCondicion = false;
                            }
                        }

                        if ($condicionmetodoPagocount > 0) {
                            if (!$condicionmetodoPago) {

                                $cumpleCondicion = false;
                            }

                        }
                        if (!$cumpleCondicionPais && $cumpleCondicionCont > 0) {

                            $cumpleCondicion = false;
                        }


                        //print_r("Esto es lo que estoy comparando");
                        //print_r($minBetPrice2);

                        //print_r("Esto es lo que estoy generando");
                        //print_r(floatval($UsuarioRecarga->valor));


                        if ($minBetPrice2 > floatval($UsuarioRecarga->valor)){
                            $cumpleCondicion = false;
                        }


                        if ($cumpleCondicion) {

                            if ($puederepetirBono) {


                            } else {

                                $sqlRepiteSorteo = "select * from usuario_sorteo a where a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"} . "' AND a.usuario_id = '" . $UsuarioMandante->usumandanteId . "'";
                                $repiteSorteo = $this->execQuery('',$sqlRepiteSorteo);

                                if ((!$puederepetirBono && oldCount($repiteSorteo) == 0)) {

                                } else {
                                    $cumpleCondicion = false;
                                }

                            }
                        }

                        //print_r("CUMPLECONDICION 2");

                        //var_dump($cumpleCondicion);

                        //print_r("CUMPLECONDICION 3");

                        //var_dump($needSubscribe);

                        if ($needSubscribe) {

                                $sqlRepiteSorteo = "select * from usuario_sorteo a where a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"}  . "' AND a.usuario_id = '" . $UsuarioMandante->usumandanteId  . "' AND a.estado = 'I'";
                                $repiteSorteo = $this->execQuery('',$sqlRepiteSorteo);
                                $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();

                                if(oldCount($repiteSorteo) == 0){
                                    $cumpleCondicion=false;
                                }
                        }



                        if ($cumpleCondicion && !$needSubscribe) {
                            //print_r("CUMPLECONDICION");


                            if($pegatinas==1){
                                //print_r("Si se que hacer");
                                $estado = "P";
                                $sqlRepiteSorteo = "select * from preusuario_sorteo a where a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"}  . "' AND a.usuario_id = '" . $UsuarioMandante->usumandanteId  . "' AND  tipo='3' AND a.estado = '". $estado . "'";
                                $repiteSorteo = $this->execQuery('',$sqlRepiteSorteo);
                                $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();

                                if(oldCount($repiteSorteo) == 0){
                                    //print_r('entro');
                                    $BonoInterno = new BonoInterno();
                                    $PreUsuarioSorteo = new PreUsuarioSorteo();
                                    $PreUsuarioSorteo->usuarioId = $UsuarioMandante->usumandanteId;
                                    $PreUsuarioSorteo->sorteoId = $value->{"sorteo_interno.sorteo_id"};
                                    $PreUsuarioSorteo->valor = 0;
                                    $PreUsuarioSorteo->posicion = 0;
                                    $PreUsuarioSorteo->valorBase = $minBetPrice;
                                    $PreUsuarioSorteo->usucreaId = 0;
                                    $PreUsuarioSorteo->usumodifId = 0;
                                    $PreUsuarioSorteo->mandante = $UsuarioMandante->mandante;
                                    $PreUsuarioSorteo->tipo = 3;


                                    if($UsuarioRecarga->valor < $minBetPrice){
                                        $PreUsuarioSorteo->estado = "P";
                                    }else{
                                        $PreUsuarioSorteo->estado = "A";
                                    }
                                    $PreUsuarioSorteo->errorId = 0;
                                    $PreUsuarioSorteo->idExterno = 0;
                                    $PreUsuarioSorteo->version = 0;
                                    $PreUsuarioSorteo->apostado =  $UsuarioRecarga->valor;
                                    $PreUsuarioSorteo->codigo = 0;
                                    $PreUsuarioSorteo->externoId = 0;
                                    $PreUsuarioSorteo->valor = $PreUsuarioSorteo->valor + $creditosConvert;
                                    //$UsuarioSorteo->valorBase = ($UsuarioSorteo->valorBase + $TransaccionApi->valor);

                                    $PreUsuarioSorteoMySqlDAO = new PreUsuarioSorteoMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                                    $idUsuSorteo = $PreUsuarioSorteoMySqlDAO->insert($PreUsuarioSorteo);
                                    //print_r($PreUsuarioSorteo);

                                }else{
                                    //print_r($UsuarioRecarga);
                                    $ususorteoId = $repiteSorteo[0]->{"a.preususorteo_id"};
                                    $BonoInterno = new BonoInterno();
                                    $idUsuSorteo=$ususorteoId;

                                    $transaccion = $TransjuegoInfoMySqlDAO->getTransaction();
                                    $sql= "UPDATE preusuario_sorteo SET apostado = apostado + " . (floatval($UsuarioRecarga->valor)) . " WHERE preususorteo_id =" . $ususorteoId;
                                    $BonoInterno->execQuery($transaccion, $sql);

                                    $sql= "UPDATE preusuario_sorteo SET estado='A'  WHERE apostado >= valor_base and estado = 'P'  AND preususorteo_id =" . $ususorteoId;
                                    $BonoInterno->execQuery($transaccion, $sql);


                                }
                            }else{
                                $estado = "P";
                                $sqlRepiteSorteo = "select * from usuario_sorteo a where a.sorteo_id='" . $value->{"sorteo_interno.sorteo_id"}  . "' AND a.usuario_id = '" . $UsuarioMandante->usumandanteId  . "' AND a.estado = '". $estado . "'";
                                $repiteSorteo = $this->execQuery('',$sqlRepiteSorteo);
                                $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();

                                if(oldCount($repiteSorteo) == 0){
                                    $UsuarioSorteo = new UsuarioSorteo();
                                    $UsuarioSorteo->usuarioId = $UsuarioMandante->usumandanteId;
                                    $UsuarioSorteo->sorteoId = $value->{"sorteo_interno.sorteo_id"};
                                    $UsuarioSorteo->valor = 0;
                                    $UsuarioSorteo->posicion = 0;
                                    $UsuarioSorteo->valorBase = $minBetPrice;
                                    $UsuarioSorteo->usucreaId = 0;
                                    $UsuarioSorteo->usumodifId = 0;
                                    $UsuarioSorteo->mandante = $UsuarioMandante->mandante;
                                    if($UsuarioRecarga->valor < $minBetPrice){
                                        $UsuarioSorteo->estado = "P";
                                    }else{
                                        $UsuarioSorteo->estado = "A";
                                    }
                                    $UsuarioSorteo->errorId = 0;
                                    $UsuarioSorteo->idExterno = 0;
                                    $UsuarioSorteo->version = 0;
                                    $UsuarioSorteo->apostado =  $UsuarioRecarga->valor;
                                    $UsuarioSorteo->codigo = 0;
                                    $UsuarioSorteo->externoId = 0;
                                    $UsuarioSorteo->valor = $UsuarioSorteo->valor + $creditosConvert;
                                    //$UsuarioSorteo->valorBase = ($UsuarioSorteo->valorBase + $TransaccionApi->valor);

                                    $UsuarioSorteoMySqlDAO = new UsuarioSorteoMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                                    $idUsuSorteo = $UsuarioSorteoMySqlDAO->insert($UsuarioSorteo);
                                }else{
                                    $ususorteoId = $repiteSorteo[0]->{"a.ususorteo_id"};
                                    $BonoInterno = new BonoInterno();

                                    $transaccion = $TransjuegoInfoMySqlDAO->getTransaction();

                                    $sql= "UPDATE usuario_sorteo SET apostado = apostado + " . (floatval($UsuarioRecarga->valor)) . " WHERE ususorteo_id =" . $ususorteoId;
                                    $BonoInterno->execQuery($transaccion, $sql);

                                    $sql= "UPDATE usuario_sorteo SET estado='A'  WHERE apostado >= valor_base AND ususorteo_id =" . $ususorteoId;
                                    $BonoInterno->execQuery($transaccion, $sql);

                                }


                            }







                            /*$UsuarioMensaje = new UsuarioMensaje();
                            $UsuarioMensaje->usufromId = 0;
                            $UsuarioMensaje->usutoId = $UsuarioSorteo->getUsuarioId();
                            $UsuarioMensaje->isRead = 0;
                            $UsuarioMensaje->body = '¡ Bien :thumbsup: ! Estas participando en el sorteo ' . $value->{"sorteo_interno.nombre"} . ' :clap:';
                            $UsuarioMensaje->msubject = 'Notificacion';
                            $UsuarioMensaje->parentId = 0;
                            $UsuarioMensaje->proveedorId = 0;
                            $UsuarioMensaje->tipo = "PUSHNOTIFICACION";
                            $UsuarioMensaje->paisId = 0;
                            $UsuarioMensaje->fechaExpiracion = '';

                            $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                            $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);*/





                            /*  $TransjuegoInfo = new TransjuegoInfo();
                              $TransjuegoInfo->productoId = $TransaccionApi->productoId;
                              $TransjuegoInfo->transaccionId = $TransaccionApi->transaccionId;
                              $TransjuegoInfo->tipo = "SORTEO";
                              $TransjuegoInfo->descripcion = $idUsuSorteo;
                              $TransjuegoInfo->valor = $creditosConvert;
                              $TransjuegoInfo->transapiId = $TransaccionApi->transapiId;
                              $TransjuegoInfo->usucreaId = 0;
                              $TransjuegoInfo->usumodifId = 0;
  
  
  
                              $TransjuegoInfoMySqlDAO->insert($TransjuegoInfo);*/
                            $TransjuegoInfoMySqlDAO->getTransaction()->commit();


                            /*$mensajesRecibidos = [];
                            $array = [];

                            $array["body"] = '¡ Bien :thumbsup: ! Estas participando en el sorteo ' . $value->{"sorteo_interno.nombre"} . ' :clap:';

                            array_push($mensajesRecibidos, $array);
                            $data = array();
                            $data["messages"] = $mensajesRecibidos;
                            //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());
                            $ConfigurationEnvironment = new ConfigurationEnvironment();

                            if (!$ConfigurationEnvironment->isDevelopment()) {

                                if(false) {

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
                            }*/
                            break;
                        }


                    }


                }


                break;

        }
    }


    /**
     * Verficiar sorteo
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
    public function verificarSorteoUsuarioConTransaccionJuego($usuarioId, $detalles, $tipoProducto, $ticketId)
    {

        switch ($tipoProducto) {


            case "CASINO":

                $TransaccionJuego = new TransaccionJuego($ticketId);
                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->usuarioId);
                $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

                //print_r($TransaccionJuego);

                $rules = [];

                array_push($rules, array("field" => "usuario_sorteo.usuario_id", "data" => "$TransaccionJuego->usuarioId", "op" => "eq"));
                array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));
                // array_push($rules, array("field" => "sorteo_interno.fecha_inicio", "data" => "$TransaccionJuego->fechaCrea" , "op" => "le"));
                // array_push($rules, array("field" => "sorteo_interno.fecha_fin", "data" => "$TransaccionJuego->fechaCrea", "op" => "ge"));
                array_push($rules, array("field" => "sorteo_interno.tipo", "data" => "2", "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                $json = json_encode($filtro);


                $UsuarioSorteo = new UsuarioSorteo();
                $data = $UsuarioSorteo->getUsuarioSorteosCustomWithoutPosition("usuario_sorteo.*,usuario_mandante.nombres,sorteo_interno.*", "usuario_sorteo.valor", "DESC", 0, 100, $json, true, '');

                //print_r($data);

                $data = json_decode($data);

                $final = [];

                $pos = 1;
                $sorteosAnalizados = '';

                $cumpleCondicion = false;
                foreach ($data->data as $key => $value) {

                    $array = [];
                    $array["Position"] = $pos;
                    $array["Id"] = $value->{"usuario_sorteo.ususorteo_id"};

                    $sorteosAnalizados = $sorteosAnalizados . $value->{"usuario_sorteo.sorteo_id"} . ",";
                    $SorteoInterno = new SorteoInterno();
                    $SorteoDetalle = new SorteoDetalle();

                    //$bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
                    //$bonos = json_decode($bonos);


                    $rules = [];

                    array_push($rules, array("field" => "sorteo_interno.sorteo_id", "data" => $value->{"usuario_sorteo.sorteo_id"}, "op" => "eq"));
                    array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $json = json_encode($filtro);

                    $sorteodetalles = $SorteoDetalle->getSorteoDetallesCustom(" sorteo_detalle.*,sorteo_interno.* ", "sorteo_interno.sorteo_id", "asc", 0, 1000, $json, TRUE);

                    $sorteodetalles = json_decode($sorteodetalles);

                    $final = [];

                    $creditosConvert = 0;

                    $cumpleCondicionPais = false;
                    $cumpleCondicionCont = 0;

                    $condicionesProducto = 0;

                    //print_r($sorteodetalles);

                    foreach ($sorteodetalles->data as $key2 => $value2) {

                        switch ($value2->{"sorteo_detalle.tipo"}) {
                            case "RANK":
                                if ($value2->{"sorteo_detalle.moneda"} == $UsuarioMandante->moneda) {
                                    if ($TransaccionJuego->valorTicket >= $value2->{"sorteo_detalle.valor"}) {
                                        if ($TransaccionJuego->valorTicket <= $value2->{"sorteo_detalle.valor2"}) {
                                            $creditosConvert = $value2->{"sorteo_detalle.valor3"};
                                        }

                                    }
                                }

                                break;


                            case "CONDPAISUSER":

                                if ($value2->{"sorteo_detalle.valor"} == $Usuario->paisId) {
                                    $cumpleCondicionPais = true;
                                }
                                $cumpleCondicionCont++;

                                break;

                            default:

                                if (stristr($value2->{"sorteo_detalle.tipo"}, 'CONDGAME')) {

                                    $idGame = explode("CONDGAME", $value2->{"sorteo_detalle.tipo"})[1];

                                    if ($TransaccionJuego->productoId == $idGame) {
                                        $cumpleCondicion = true;
                                    }elseif($idGame=="_ALL"){

                                        //print_r("Si es como esperaba");
                                        $cumpleCondicion = true;
                                    }
                                    $condicionesProducto++;
                                }

                                if (stristr($value2->{"sorteo_detalle.tipo"}, 'CONDPROVIDER')) {
                                    /*
                                                                        $idProvider = explode("CONDPROVIDER", $value2->{"sorteo_detalle.tipo"})[1];

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

                        $UsuarioSorteo = new UsuarioSorteo($value->{"usuario_sorteo.ususorteo_id"});
                        $UsuarioSorteo->valor = $UsuarioSorteo->valor + $creditosConvert;
                        $UsuarioSorteo->valorBase = ($UsuarioSorteo->valorBase + $TransaccionJuego->valorTicket);

                        //print_r($UsuarioSorteo);
                        $UsuarioSorteoMySqlDAO = new UsuarioSorteoMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                        $UsuarioSorteoMySqlDAO->update($UsuarioSorteo);

                        $TransjuegoInfo = new TransjuegoInfo();
                        $TransjuegoInfo->productoId = $TransaccionJuego->productoId;
                        $TransjuegoInfo->transaccionId = $TransaccionJuego->transaccionId;
                        $TransjuegoInfo->tipo = "SORTEO";
                        $TransjuegoInfo->descripcion = $value->{"usuario_sorteo.ususorteo_id"};
                        $TransjuegoInfo->valor = $creditosConvert;
                        $TransjuegoInfo->transapiId = 0;
                        $TransjuegoInfo->usucreaId = 0;
                        $TransjuegoInfo->usumodifId = 0;
                        $TransjuegoInfo->identificador = $TransaccionJuego->ticketId;

                        $title = '';
                        $messageBody = '';
                        $lotteryName = $value->{"sorteo_interno.nombre"};

                        switch(strtolower($Usuario->idioma)) {
                            case 'es':
                                $title = 'Notificacion';
                                $messageBody = "¡ Bien :thumbsup: ! Sumaste {$creditosConvert} puntos en {$lotteryName} :clap:";
                                break;
                            case 'en':
                                $title = 'Notification';
                                $messageBody = "¡ Great :thumbsup: ! You added{$creditosConvert} points in the {$lotteryName} :clap:";
                                break;
                            case 'pt':
                                $title = 'Notificação';
                                $messageBody = "Olha só! :thumbsup: Você somou {$creditosConvert} pontos em {$lotteryName} :clap:";
                                break;
                        }

                        $UsuarioMensaje = new UsuarioMensaje();
                        $UsuarioMensaje->usufromId = 0;
                        $UsuarioMensaje->usutoId = $UsuarioSorteo->getUsuarioId();
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

                        /*$UsuarioSession = new UsuarioSession();
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

                        }*/

                        if (in_array($UsuarioMandante->mandante, array('0', 8))) {

                            $dataSend = $data;
                            $WebsocketUsuario = new WebsocketUsuario('', '');
                            $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend);

                        }
                        break;
                    }


                }

                if (!$cumpleCondicion) {

                    if ($sorteosAnalizados != "") {
                        $sorteosAnalizados = $sorteosAnalizados . '0';
                    }

                    $SorteoInterno = new SorteoInterno();

                    $rules = [];

                    if ($sorteosAnalizados != '') {
                        array_push($rules, array("field" => "sorteo_interno.sorteo_id", "data" => "$sorteosAnalizados", "op" => "ni"));
                    }

                    array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));
                    array_push($rules, array("field" => "sorteo_interno.fecha_inicio", "data" => "$TransaccionJuego->fechaCrea", "op" => "le"));
                    array_push($rules, array("field" => "sorteo_interno.fecha_fin", "data" => "$TransaccionJuego->fechaCrea", "op" => "ge"));
                    array_push($rules, array("field" => "sorteo_interno.tipo", "data" => "2", "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $json = json_encode($filtro);


                    $data = $SorteoInterno->getSorteosCustom("sorteo_interno.*", "sorteo_interno.orden", "DESC", 0, 1000, $json, true, '');


                    $data = json_decode($data);

                    $final = [];

                    $pos = 1;
                    $sorteosAnalizados = '';

                    foreach ($data->data as $key => $value) {


                        $SorteoDetalle = new SorteoDetalle();

                        //$bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
                        //$bonos = json_decode($bonos);


                        $rules = [];

                        array_push($rules, array("field" => "sorteo_interno.sorteo_id", "data" => $value->{"sorteo_interno.sorteo_id"}, "op" => "eq"));
                        array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));

                        $filtro = array("rules" => $rules, "groupOp" => "AND");

                        $json = json_encode($filtro);

                        $sorteodetalles = $SorteoDetalle->getSorteoDetallesCustom(" sorteo_detalle.*,sorteo_interno.* ", "sorteo_interno.sorteo_id", "asc", 0, 1000, $json, TRUE);

                        $sorteodetalles = json_decode($sorteodetalles);

                        $final = [];

                        $creditosConvert = 0;

                        $cumpleCondicion = false;
                        $needSubscribe = false;

                        $cumpleCondicionPais = false;
                        $cumpleCondicionCont = 0;

                        $condicionesProducto = 0;
                        //print_r($sorteodetalles);

                        foreach ($sorteodetalles->data as $key2 => $value2) {

                            switch ($value2->{"sorteo_detalle.tipo"}) {
                                case "RANK":
                                    if ($value2->{"sorteo_detalle.moneda"} == $UsuarioMandante->moneda) {
                                        if ($TransaccionJuego->valorTicket >= $value2->{"sorteo_detalle.valor"}) {
                                            if ($TransaccionJuego->valorTicket <= $value2->{"sorteo_detalle.valor2"}) {
                                                $creditosConvert = $value2->{"sorteo_detalle.valor3"};
                                            }

                                        }
                                    }

                                    break;

                                case "USERSUBSCRIBE":

                                    if ($value2->{"sorteo_detalle.valor"} == 0) {

                                    } else {
                                        $needSubscribe = true;
                                    }

                                    break;

                                case "VISIBILIDAD":

                                    if ($value2->{"sorteo_detalle.valor"} == 1) {
                                        $needSubscribe = true;

                                    } else {
                                    }

                                    break;


                                case "CONDPAISUSER":

                                    if ($value2->{"sorteo_detalle.valor"} == $Usuario->paisId) {
                                        $cumpleCondicionPais = true;
                                    }
                                    $cumpleCondicionCont++;

                                    break;

                                default:

                                    if (stristr($value2->{"sorteo_detalle.tipo"}, 'CONDGAME')) {

                                        $idGame = explode("CONDGAME", $value2->{"sorteo_detalle.tipo"})[1];

                                        if ($TransaccionJuego->productoId == $idGame) {

                                            $cumpleCondicion = true;
                                        }elseif($idGame=="_ALL"){

                                            //print_r("Si es como esperaba");
                                            $cumpleCondicion = true;
                                        }
                                        $condicionesProducto++;
                                    }

                                    /*if (stristr($value2->{"sorteo_detalle.tipo"}, 'CONDPROVIDER')) {

                                        $idProvider = explode("CONDPROVIDER", $value2->{"sorteo_detalle.tipo"})[1];

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

                            $UsuarioSorteo = new UsuarioSorteo();
                            $UsuarioSorteo->usuarioId = $UsuarioMandante->usumandanteId;
                            $UsuarioSorteo->sorteoId = $value->{"sorteo_interno.sorteo_id"};
                            $UsuarioSorteo->valor = 0;
                            $UsuarioSorteo->posicion = 0;
                            $UsuarioSorteo->valorBase = 0;
                            $UsuarioSorteo->usucreaId = 0;
                            $UsuarioSorteo->usumodifId = 0;
                            $UsuarioSorteo->estado = "A";
                            $UsuarioSorteo->errorId = 0;
                            $UsuarioSorteo->idExterno = 0;
                            $UsuarioSorteo->mandante = $UsuarioMandante->mandante;
                            $UsuarioSorteo->version = 0;
                            $UsuarioSorteo->apostado = 0;
                            $UsuarioSorteo->codigo = 0;
                            $UsuarioSorteo->externoId = 0;
                            $UsuarioSorteo->valor = $UsuarioSorteo->valor + $creditosConvert;
                            $UsuarioSorteo->valorBase = ($UsuarioSorteo->valorBase + $TransaccionJuego->valorTicket);

                            $UsuarioSorteoMySqlDAO = new UsuarioSorteoMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                            $idUsuSorteo = $UsuarioSorteoMySqlDAO->insert($UsuarioSorteo);

                            $TransjuegoInfo = new TransjuegoInfo();
                            $TransjuegoInfo->productoId = $TransaccionJuego->productoId;
                            $TransjuegoInfo->transaccionId = $TransaccionJuego->transaccionId;
                            $TransjuegoInfo->tipo = "SORTEO";
                            $TransjuegoInfo->descripcion = $idUsuSorteo;
                            $TransjuegoInfo->valor = $creditosConvert;
                            $TransjuegoInfo->transapiId = 0;
                            $TransjuegoInfo->usucreaId = 0;
                            $TransjuegoInfo->usumodifId = 0;

                            $title = '';
                            $messageBody = '';
                            $lotteryName = $value->{"sorteo_interno.nombre"};

                            switch(strtolower($Usuario->idioma)) {
                                case 'es':
                                    $title = 'Notificacion';
                                    $messageBody = "¡ Bien :thumbsup: ! Sumaste {$creditosConvert} puntos en {$lotteryName} :clap:";
                                    break;
                                case 'en':
                                    $title = 'Notification';
                                    $messageBody = "¡ Great :thumbsup: ! You added{$creditosConvert} points in the {$lotteryName} :clap:";
                                    break;
                                case 'pt':
                                    $title = 'Notificação';
                                    $messageBody = "Olha só! :thumbsup: Você somou {$creditosConvert} pontos em {$lotteryName} :clap:";
                                    break;
                            }

                            $UsuarioMensaje = new UsuarioMensaje();
                            $UsuarioMensaje->usufromId = 0;
                            $UsuarioMensaje->usutoId = $UsuarioSorteo->getUsuarioId();
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

                            /*$UsuarioSession = new UsuarioSession();
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

                            }*/

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

                array_push($rules, array("field" => "usuario_sorteo.usuario_id", "data" => "$TransaccionApi->usuarioId", "op" => "eq"));
                array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));
                array_push($rules, array("field" => "sorteo_interno.fecha_inicio", "data" => "$TransaccionApi->fechaCrea", "op" => "le"));
                array_push($rules, array("field" => "sorteo_interno.fecha_fin", "data" => "$TransaccionApi->fechaCrea", "op" => "ge"));
                array_push($rules, array("field" => "sorteo_interno.tipo", "data" => "3", "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                $json = json_encode($filtro);


                $UsuarioSorteo = new UsuarioSorteo();
                $data = $UsuarioSorteo->getUsuarioSorteosCustomWithoutPosition("usuario_sorteo.*,usuario_mandante.nombres,sorteo_interno.*", "usuario_sorteo.valor", "DESC", 0, 100, $json, true, '');

                //print_r($data);

                $data = json_decode($data);

                $final = [];

                $pos = 1;
                $sorteosAnalizados = '';

                $cumpleCondicion = false;
                foreach ($data->data as $key => $value) {

                    $array = [];
                    $array["Position"] = $pos;
                    $array["Id"] = $value->{"usuario_sorteo.ususorteo_id"};

                    $sorteosAnalizados = $sorteosAnalizados . $value->{"usuario_sorteo.sorteo_id"} . ",";
                    $SorteoInterno = new SorteoInterno();
                    $SorteoDetalle = new SorteoDetalle();

                    //$bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
                    //$bonos = json_decode($bonos);


                    $rules = [];

                    array_push($rules, array("field" => "sorteo_interno.sorteo_id", "data" => $value->{"usuario_sorteo.sorteo_id"}, "op" => "eq"));
                    array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $json = json_encode($filtro);

                    $sorteodetalles = $SorteoDetalle->getSorteoDetallesCustom(" sorteo_detalle.*,sorteo_interno.* ", "sorteo_interno.sorteo_id", "asc", 0, 1000, $json, TRUE);

                    $sorteodetalles = json_decode($sorteodetalles);

                    $final = [];

                    $creditosConvert = 0;

                    $cumpleCondicionPais = false;
                    $cumpleCondicionCont = 0;

                    $condicionesProducto = 0;

                    foreach ($sorteodetalles->data as $key2 => $value2) {

                        switch ($value2->{"sorteo_detalle.tipo"}) {
                            case "RANK":
                                if ($value2->{"sorteo_detalle.moneda"} == $UsuarioMandante->moneda) {
                                    if ($TransaccionApi->valor >= $value2->{"sorteo_detalle.valor"}) {
                                        if ($TransaccionApi->valor <= $value2->{"sorteo_detalle.valor2"}) {
                                            $creditosConvert = $value2->{"sorteo_detalle.valor3"};
                                        }

                                    }
                                }

                                break;


                            case "CONDPAISUSER":

                                if ($value2->{"sorteo_detalle.valor"} == $Usuario->paisId) {
                                    $cumpleCondicionPais = true;
                                }
                                $cumpleCondicionCont++;

                                break;

                            default:

                                if (stristr($value2->{"sorteo_detalle.tipo"}, 'CONDGAME')) {

                                    $idGame = explode("CONDGAME", $value2->{"sorteo_detalle.tipo"})[1];

                                    if ($TransaccionApi->productoId == $idGame) {
                                        $cumpleCondicion = true;
                                    }elseif($idGame=="_ALL"){

                                        //print_r("Si es como esperaba");
                                        $cumpleCondicion = true;
                                    }
                                    $condicionesProducto++;
                                }

                                if (stristr($value2->{"sorteo_detalle.tipo"}, 'CONDPROVIDER')) {

                                    $idProvider = explode("CONDPROVIDER", $value2->{"sorteo_detalle.tipo"})[1];

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

                        $UsuarioSorteo = new UsuarioSorteo($value->{"usuario_sorteo.ususorteo_id"});
                        $UsuarioSorteo->valor = $UsuarioSorteo->valor + $creditosConvert;
                        $UsuarioSorteo->valorBase = ($UsuarioSorteo->valorBase + $TransaccionApi->valor);
                        //print_r($TransaccionApi);

                        //print_r($UsuarioSorteo);
                        $UsuarioSorteoMySqlDAO = new UsuarioSorteoMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                        $UsuarioSorteoMySqlDAO->update($UsuarioSorteo);

                        $TransjuegoInfo = new TransjuegoInfo();
                        $TransjuegoInfo->productoId = $TransaccionApi->productoId;
                        $TransjuegoInfo->transaccionId = $TransaccionApi->transaccionId;
                        $TransjuegoInfo->tipo = "SORTEO";
                        $TransjuegoInfo->descripcion = $value->{"usuario_sorteo.ususorteo_id"};
                        $TransjuegoInfo->valor = $creditosConvert;
                        $TransjuegoInfo->transapiId = $TransaccionApi->transapiId;
                        $TransjuegoInfo->usucreaId = 0;
                        $TransjuegoInfo->usumodifId = 0;
                        $TransjuegoInfo->identificador = $TransaccionApi->identificador;

                        $title = '';
                        $messageBody = '';
                        $lotteryName = $value->{"sorteo_interno.nombre"};

                        switch(strtolower($Usuario->idioma)) {
                            case 'es':
                                $title = 'Notificacion';
                                $messageBody = "¡ Bien :thumbsup: ! Sumaste {$creditosConvert} puntos en {$lotteryName} :clap:";
                                break;
                            case 'en':
                                $title = 'Notification';
                                $messageBody = "¡ Great :thumbsup: ! You added{$creditosConvert} points in the {$lotteryName} :clap:";
                                break;
                            case 'pt':
                                $title = 'Notificação';
                                $messageBody = "Olha só! :thumbsup: Você somou {$creditosConvert} pontos em {$lotteryName} :clap:";
                                break;
                        }

                        $UsuarioMensaje = new UsuarioMensaje();
                        $UsuarioMensaje->usufromId = 0;
                        $UsuarioMensaje->usutoId = $UsuarioSorteo->getUsuarioId();
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
                        /*
                        
                                                $WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
                                                $WebsocketUsuario->sendWSMessage();*/


                        if (in_array($UsuarioMandante->mandante, array('0', 8))) {

                            $dataSend = $data;
                            $WebsocketUsuario = new WebsocketUsuario('', '');
                            $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend);

                        }
                        break;
                    }


                }

                if (!$cumpleCondicion) {

                    if ($sorteosAnalizados != "") {
                        $sorteosAnalizados = $sorteosAnalizados . '0';
                    }

                    $SorteoInterno = new SorteoInterno();

                    $rules = [];

                    if ($sorteosAnalizados != '') {
                        array_push($rules, array("field" => "sorteo_interno.sorteo_id", "data" => "$sorteosAnalizados", "op" => "ni"));
                    }

                    array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));
                    array_push($rules, array("field" => "sorteo_interno.fecha_inicio", "data" => "$TransaccionApi->fechaCrea", "op" => "le"));
                    array_push($rules, array("field" => "sorteo_interno.fecha_fin", "data" => "$TransaccionApi->fechaCrea", "op" => "ge"));
                    array_push($rules, array("field" => "sorteo_interno.tipo", "data" => "3", "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $json = json_encode($filtro);


                    $data = $SorteoInterno->getSorteosCustom("sorteo_interno.*", "sorteo_interno.orden", "DESC", 0, 1000, $json, true, '');


                    $data = json_decode($data);

                    $final = [];

                    $pos = 1;
                    $sorteosAnalizados = '';

                    foreach ($data->data as $key => $value) {


                        $SorteoDetalle = new SorteoDetalle();

                        //$bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
                        //$bonos = json_decode($bonos);


                        $rules = [];

                        array_push($rules, array("field" => "sorteo_interno.sorteo_id", "data" => $value->{"sorteo_interno.sorteo_id"}, "op" => "eq"));
                        array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));

                        $filtro = array("rules" => $rules, "groupOp" => "AND");

                        $json = json_encode($filtro);

                        $sorteodetalles = $SorteoDetalle->getSorteoDetallesCustom(" sorteo_detalle.*,sorteo_interno.* ", "sorteo_interno.sorteo_id", "asc", 0, 1000, $json, TRUE);

                        $sorteodetalles = json_decode($sorteodetalles);

                        $final = [];

                        $creditosConvert = 0;

                        $cumpleCondicion = false;
                        $needSubscribe = false;

                        $cumpleCondicionPais = false;
                        $cumpleCondicionCont = 0;

                        $condicionesProducto = 0;

                        foreach ($sorteodetalles->data as $key2 => $value2) {

                            switch ($value2->{"sorteo_detalle.tipo"}) {
                                case "RANK":
                                    if ($value2->{"sorteo_detalle.moneda"} == $UsuarioMandante->moneda) {
                                        if ($TransaccionApi->valor >= $value2->{"sorteo_detalle.valor"}) {
                                            if ($TransaccionApi->valor <= $value2->{"sorteo_detalle.valor2"}) {
                                                $creditosConvert = $value2->{"sorteo_detalle.valor3"};
                                            }

                                        }
                                    }

                                    break;

                                case "USERSUBSCRIBE":

                                    if ($value2->{"sorteo_detalle.valor"} == 0) {

                                    } else {
                                        $needSubscribe = true;
                                    }

                                    break;

                                case "VISIBILIDAD":

                                    if ($value2->{"sorteo_detalle.valor"} == 1) {
                                        $needSubscribe = true;

                                    } else {
                                    }

                                    break;


                                case "CONDPAISUSER":

                                    if ($value2->{"sorteo_detalle.valor"} == $Usuario->paisId) {
                                        $cumpleCondicionPais = true;
                                    }
                                    $cumpleCondicionCont++;

                                    break;

                                default:

                                    if (stristr($value2->{"sorteo_detalle.tipo"}, 'CONDGAME')) {

                                        $idGame = explode("CONDGAME", $value2->{"sorteo_detalle.tipo"})[1];

                                        if ($TransaccionApi->productoId == $idGame) {

                                            $cumpleCondicion = true;
                                        }elseif($idGame=="_ALL"){

                                            //print_r("Si es como esperaba");
                                            $cumpleCondicion = true;
                                        }
                                        $condicionesProducto++;
                                    }

                                    if (stristr($value2->{"sorteo_detalle.tipo"}, 'CONDPROVIDER')) {

                                        $idProvider = explode("CONDPROVIDER", $value2->{"sorteo_detalle.tipo"})[1];

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

                            $UsuarioSorteo = new UsuarioSorteo();
                            $UsuarioSorteo->usuarioId = $UsuarioMandante->usumandanteId;
                            $UsuarioSorteo->sorteoId = $value->{"sorteo_interno.sorteo_id"};
                            $UsuarioSorteo->valor = 0;
                            $UsuarioSorteo->posicion = 0;
                            $UsuarioSorteo->valorBase = 0;
                            $UsuarioSorteo->usucreaId = 0;
                            $UsuarioSorteo->usumodifId = 0;
                            $UsuarioSorteo->estado = "A";
                            $UsuarioSorteo->errorId = 0;
                            $UsuarioSorteo->idExterno = 0;
                            $UsuarioSorteo->mandante = 0;
                            $UsuarioSorteo->version = 0;
                            $UsuarioSorteo->apostado = 0;
                            $UsuarioSorteo->codigo = 0;
                            $UsuarioSorteo->externoId = 0;
                            $UsuarioSorteo->valor = $UsuarioSorteo->valor + $creditosConvert;
                            $UsuarioSorteo->valorBase = ($UsuarioSorteo->valorBase + $TransaccionApi->valor);

                            $UsuarioSorteoMySqlDAO = new UsuarioSorteoMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                            $idUsuSorteo = $UsuarioSorteoMySqlDAO->insert($UsuarioSorteo);

                            $TransjuegoInfo = new TransjuegoInfo();
                            $TransjuegoInfo->productoId = $TransaccionApi->productoId;
                            $TransjuegoInfo->transaccionId = $TransaccionApi->transaccionId;
                            $TransjuegoInfo->tipo = "SORTEO";
                            $TransjuegoInfo->descripcion = $idUsuSorteo;
                            $TransjuegoInfo->valor = $creditosConvert;
                            $TransjuegoInfo->transapiId = $TransaccionApi->transapiId;
                            $TransjuegoInfo->usucreaId = 0;
                            $TransjuegoInfo->usumodifId = 0;

                            $title = '';
                            $messageBody = '';
                            $lotteryName = $value->{"sorteo_interno.nombre"};

                            switch(strtolower($Usuario->idioma)) {
                                case 'es':
                                    $title = 'Notificacion';
                                    $messageBody = "¡ Bien :thumbsup: ! Sumaste {$creditosConvert} puntos en {$lotteryName} :clap:";
                                    break;
                                case 'en':
                                    $title = 'Notification';
                                    $messageBody = "¡ Great :thumbsup: ! You added{$creditosConvert} points in the {$lotteryName} :clap:";
                                    break;
                                case 'pt':
                                    $title = 'Notificação';
                                    $messageBody = "Olha só! :thumbsup: Você somou {$creditosConvert} pontos em {$lotteryName} :clap:";
                                    break;
                            }

                            $UsuarioMensaje = new UsuarioMensaje();
                            $UsuarioMensaje->usufromId = 0;
                            $UsuarioMensaje->usutoId = $UsuarioSorteo->getUsuarioId();
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


                            /* $WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
                             $WebsocketUsuario->sendWSMessage();*/


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

                array_push($rules, array("field" => "usuario_sorteo.usuario_id", "data" => "$TransaccionApi->usuarioId", "op" => "eq"));
                array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));
                array_push($rules, array("field" => "sorteo_interno.fecha_inicio", "data" => "$TransaccionApi->fechaCrea", "op" => "le"));
                array_push($rules, array("field" => "sorteo_interno.fecha_fin", "data" => "$TransaccionApi->fechaCrea", "op" => "ge"));
                array_push($rules, array("field" => "sorteo_interno.tipo", "data" => "4", "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                $json = json_encode($filtro);


                $UsuarioSorteo = new UsuarioSorteo();
                $data = $UsuarioSorteo->getUsuarioSorteosCustomWithoutPosition("usuario_sorteo.*,usuario_mandante.nombres,sorteo_interno.*", "usuario_sorteo.valor", "DESC", 0, 100, $json, true, '');

                //print_r($data);

                $data = json_decode($data);

                $final = [];

                $pos = 1;
                $sorteosAnalizados = '';

                $cumpleCondicion = false;
                foreach ($data->data as $key => $value) {

                    $array = [];
                    $array["Position"] = $pos;
                    $array["Id"] = $value->{"usuario_sorteo.ususorteo_id"};

                    $sorteosAnalizados = $sorteosAnalizados . $value->{"usuario_sorteo.sorteo_id"} . ",";
                    $SorteoInterno = new SorteoInterno();
                    $SorteoDetalle = new SorteoDetalle();

                    //$bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
                    //$bonos = json_decode($bonos);


                    $rules = [];

                    array_push($rules, array("field" => "sorteo_interno.sorteo_id", "data" => $value->{"usuario_sorteo.sorteo_id"}, "op" => "eq"));
                    array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $json = json_encode($filtro);

                    $sorteodetalles = $SorteoDetalle->getSorteoDetallesCustom(" sorteo_detalle.*,sorteo_interno.* ", "sorteo_interno.sorteo_id", "asc", 0, 1000, $json, TRUE);

                    $sorteodetalles = json_decode($sorteodetalles);

                    $final = [];

                    $creditosConvert = 0;

                    $cumpleCondicionPais = false;
                    $cumpleCondicionCont = 0;

                    $condicionesProducto = 0;

                    foreach ($sorteodetalles->data as $key2 => $value2) {

                        switch ($value2->{"sorteo_detalle.tipo"}) {
                            case "RANK":
                                if ($value2->{"sorteo_detalle.moneda"} == $UsuarioMandante->moneda) {
                                    if ($TransaccionApi->valor >= $value2->{"sorteo_detalle.valor"}) {
                                        if ($TransaccionApi->valor <= $value2->{"sorteo_detalle.valor2"}) {
                                            $creditosConvert = $value2->{"sorteo_detalle.valor3"};
                                        }

                                    }
                                }

                                break;


                            case "CONDPAISUSER":

                                if ($value2->{"sorteo_detalle.valor"} == $Usuario->paisId) {
                                    $cumpleCondicionPais = true;
                                }
                                $cumpleCondicionCont++;

                                break;

                            default:

                                if (stristr($value2->{"sorteo_detalle.tipo"}, 'CONDGAME')) {

                                    $idGame = explode("CONDGAME", $value2->{"sorteo_detalle.tipo"})[1];

                                    if ($TransaccionApi->productoId == $idGame) {
                                        $cumpleCondicion = true;
                                    }
                                    $condicionesProducto++;
                                }

                                if (stristr($value2->{"sorteo_detalle.tipo"}, 'CONDPROVIDER')) {

                                    $idProvider = explode("CONDPROVIDER", $value2->{"sorteo_detalle.tipo"})[1];

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

                        $UsuarioSorteo = new UsuarioSorteo($value->{"usuario_sorteo.ususorteo_id"});
                        $UsuarioSorteo->valor = $UsuarioSorteo->valor + $creditosConvert;
                        $UsuarioSorteo->valorBase = ($UsuarioSorteo->valorBase + $TransaccionApi->valor);
                        //print_r($TransaccionApi);

                        //print_r($UsuarioSorteo);
                        $UsuarioSorteoMySqlDAO = new UsuarioSorteoMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                        $UsuarioSorteoMySqlDAO->update($UsuarioSorteo);

                        $TransjuegoInfo = new TransjuegoInfo();
                        $TransjuegoInfo->productoId = $TransaccionApi->productoId;
                        $TransjuegoInfo->transaccionId = $TransaccionApi->transaccionId;
                        $TransjuegoInfo->tipo = "SORTEO";
                        $TransjuegoInfo->descripcion = $value->{"usuario_sorteo.ususorteo_id"};
                        $TransjuegoInfo->valor = $creditosConvert;
                        $TransjuegoInfo->transapiId = $TransaccionApi->transapiId;
                        $TransjuegoInfo->usucreaId = 0;
                        $TransjuegoInfo->usumodifId = 0;
                        $TransjuegoInfo->identificador = $TransaccionApi->identificador;

                        $title = '';
                        $messageBody = '';
                        $lotteryName = $value->{"sorteo_interno.nombre"};

                        switch(strtolower($Usuario->idioma)) {
                            case 'es':
                                $title = 'Notificacion';
                                $messageBody = "¡ Bien :thumbsup: ! Sumaste {$creditosConvert} puntos en {$lotteryName} :clap:";
                                break;
                            case 'en':
                                $title = 'Notification';
                                $messageBody = "¡ Great :thumbsup: ! You added{$creditosConvert} points in the {$lotteryName} :clap:";
                                break;
                            case 'pt':
                                $title = 'Notificação';
                                $messageBody = "Olha só! :thumbsup: Você somou {$creditosConvert} pontos em {$lotteryName} :clap:";
                                break;
                        }

                        $UsuarioMensaje = new UsuarioMensaje();
                        $UsuarioMensaje->usufromId = 0;
                        $UsuarioMensaje->usutoId = $UsuarioSorteo->getUsuarioId();
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


                        /*$WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
                        $WebsocketUsuario->sendWSMessage();*/


                        if (in_array($UsuarioMandante->mandante, array('0', 8))) {

                            $dataSend = $data;
                            $WebsocketUsuario = new WebsocketUsuario('', '');
                            $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend);

                        }
                        break;
                    }


                }

                if (!$cumpleCondicion) {

                    if ($sorteosAnalizados != "") {
                        $sorteosAnalizados = $sorteosAnalizados . '0';
                    }

                    $SorteoInterno = new SorteoInterno();

                    $rules = [];

                    if ($sorteosAnalizados != '') {
                        array_push($rules, array("field" => "sorteo_interno.sorteo_id", "data" => "$sorteosAnalizados", "op" => "ni"));
                    }

                    array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));
                    array_push($rules, array("field" => "sorteo_interno.fecha_inicio", "data" => "$TransaccionApi->fechaCrea", "op" => "le"));
                    array_push($rules, array("field" => "sorteo_interno.fecha_fin", "data" => "$TransaccionApi->fechaCrea", "op" => "ge"));
                    array_push($rules, array("field" => "sorteo_interno.tipo", "data" => "4", "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $json = json_encode($filtro);


                    $data = $SorteoInterno->getSorteosCustom("sorteo_interno.*", "sorteo_interno.orden", "DESC", 0, 1000, $json, true, '');


                    $data = json_decode($data);

                    $final = [];

                    $pos = 1;
                    $sorteosAnalizados = '';

                    foreach ($data->data as $key => $value) {


                        $SorteoDetalle = new SorteoDetalle();

                        //$bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
                        //$bonos = json_decode($bonos);


                        $rules = [];

                        array_push($rules, array("field" => "sorteo_interno.sorteo_id", "data" => $value->{"sorteo_interno.sorteo_id"}, "op" => "eq"));
                        array_push($rules, array("field" => "sorteo_interno.estado", "data" => "A", "op" => "eq"));

                        $filtro = array("rules" => $rules, "groupOp" => "AND");

                        $json = json_encode($filtro);

                        $sorteodetalles = $SorteoDetalle->getSorteoDetallesCustom(" sorteo_detalle.*,sorteo_interno.* ", "sorteo_interno.sorteo_id", "asc", 0, 1000, $json, TRUE);

                        $sorteodetalles = json_decode($sorteodetalles);

                        $final = [];

                        $creditosConvert = 0;

                        $cumpleCondicion = false;
                        $needSubscribe = false;

                        $cumpleCondicionPais = false;
                        $cumpleCondicionCont = 0;

                        $condicionesProducto = 0;

                        foreach ($sorteodetalles->data as $key2 => $value2) {

                            switch ($value2->{"sorteo_detalle.tipo"}) {
                                case "RANK":
                                    if ($value2->{"sorteo_detalle.moneda"} == $UsuarioMandante->moneda) {
                                        if ($TransaccionApi->valor >= $value2->{"sorteo_detalle.valor"}) {
                                            if ($TransaccionApi->valor <= $value2->{"sorteo_detalle.valor2"}) {
                                                $creditosConvert = $value2->{"sorteo_detalle.valor3"};
                                            }

                                        }
                                    }

                                    break;

                                case "USERSUBSCRIBE":

                                    if ($value2->{"sorteo_detalle.valor"} == 0) {

                                    } else {
                                        $needSubscribe = true;
                                    }

                                    break;

                                case "VISIBILIDAD":

                                    if ($value2->{"sorteo_detalle.valor"} == 1) {
                                        $needSubscribe = true;

                                    } else {
                                    }

                                    break;


                                case "CONDPAISUSER":

                                    if ($value2->{"sorteo_detalle.valor"} == $Usuario->paisId) {
                                        $cumpleCondicionPais = true;
                                    }
                                    $cumpleCondicionCont++;

                                    break;

                                default:

                                    if (stristr($value2->{"sorteo_detalle.tipo"}, 'CONDGAME')) {

                                        $idGame = explode("CONDGAME", $value2->{"sorteo_detalle.tipo"})[1];

                                        if ($TransaccionApi->productoId == $idGame) {

                                            $cumpleCondicion = true;
                                        }
                                        $condicionesProducto++;
                                    }

                                    if (stristr($value2->{"sorteo_detalle.tipo"}, 'CONDPROVIDER')) {

                                        $idProvider = explode("CONDPROVIDER", $value2->{"sorteo_detalle.tipo"})[1];

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

                            $UsuarioSorteo = new UsuarioSorteo();
                            $UsuarioSorteo->usuarioId = $UsuarioMandante->usumandanteId;
                            $UsuarioSorteo->sorteoId = $value->{"sorteo_interno.sorteo_id"};
                            $UsuarioSorteo->valor = 0;
                            $UsuarioSorteo->posicion = 0;
                            $UsuarioSorteo->valorBase = 0;
                            $UsuarioSorteo->usucreaId = 0;
                            $UsuarioSorteo->usumodifId = 0;
                            $UsuarioSorteo->estado = "A";
                            $UsuarioSorteo->errorId = 0;
                            $UsuarioSorteo->idExterno = 0;
                            $UsuarioSorteo->mandante = 0;
                            $UsuarioSorteo->version = 0;
                            $UsuarioSorteo->apostado = 0;
                            $UsuarioSorteo->codigo = 0;
                            $UsuarioSorteo->externoId = 0;
                            $UsuarioSorteo->valor = $UsuarioSorteo->valor + $creditosConvert;
                            $UsuarioSorteo->valorBase = ($UsuarioSorteo->valorBase + $TransaccionApi->valor);

                            $UsuarioSorteoMySqlDAO = new UsuarioSorteoMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                            $idUsuSorteo = $UsuarioSorteoMySqlDAO->insert($UsuarioSorteo);

                            $TransjuegoInfo = new TransjuegoInfo();
                            $TransjuegoInfo->productoId = $TransaccionApi->productoId;
                            $TransjuegoInfo->transaccionId = $TransaccionApi->transaccionId;
                            $TransjuegoInfo->tipo = "SORTEO";
                            $TransjuegoInfo->descripcion = $idUsuSorteo;
                            $TransjuegoInfo->valor = $creditosConvert;
                            $TransjuegoInfo->transapiId = $TransaccionApi->transapiId;
                            $TransjuegoInfo->usucreaId = 0;
                            $TransjuegoInfo->usumodifId = 0;

                            $title = '';
                            $messageBody = '';
                            $lotteryName = $value->{"sorteo_interno.nombre"};

                            switch(strtolower($Usuario->idioma)) {
                                case 'es':
                                    $title = 'Notificacion';
                                    $messageBody = "¡ Bien :thumbsup: ! Sumaste {$creditosConvert} puntos en {$lotteryName} :clap:";
                                    break;
                                case 'en':
                                    $title = 'Notification';
                                    $messageBody = "¡ Great :thumbsup: ! You added{$creditosConvert} points in the {$lotteryName} :clap:";
                                    break;
                                case 'pt':
                                    $title = 'Notificação';
                                    $messageBody = "Olha só! :thumbsup: Você somou {$creditosConvert} pontos em {$lotteryName} :clap:";
                                    break;
                            }

                            $UsuarioMensaje = new UsuarioMensaje();
                            $UsuarioMensaje->usufromId = 0;
                            $UsuarioMensaje->usutoId = $UsuarioSorteo->getUsuarioId();
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

                            /*$UsuarioSession = new UsuarioSession();
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

                            }*/


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

        $SorteoInternoMySqlDAO = new SorteoInternoMySqlDAO($transaccion);
        $return = $SorteoInternoMySqlDAO->querySQL($sql);
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

        $SorteoInternoMySqlDAO = new SorteoInternoMySqlDAO($transaccion);
        $return = $SorteoInternoMySqlDAO->queryUpdate($sql);

        return $return;

    }


}

?>
