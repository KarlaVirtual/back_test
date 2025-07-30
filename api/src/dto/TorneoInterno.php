<?php namespace Backend\dto;
use Backend\dao\TransjuegoInfoDAO;
use Backend\mysql\TorneoInternoMySqlDAO;
use Backend\mysql\TransjuegoInfoMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\UsuarioTorneoMySqlDAO;
use Backend\websocket\WebsocketUsuario;
use Exception;
/** 
* Clase 'TorneoInterno'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'TorneoInterno'
* 
* Ejemplo de uso: 
* $TorneoInterno = new TorneoInterno();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class TorneoInterno
{

    /**
    * Representación de la columna 'torneoId' de la tabla 'TorneoInterno'
    *
    * @var string
    */
    var $torneoId;

    /**
    * Representación de la columna 'fechaInicio' de la tabla 'TorneoInterno'
    *
    * @var string
    */
    var $fechaInicio;

    /**
    * Representación de la columna 'fechaFin' de la tabla 'TorneoInterno'
    *
    * @var string
    */
    var $fechaFin;

    /**
    * Representación de la columna 'descripcion' de la tabla 'TorneoInterno'
    *
    * @var string
    */
    var $descripcion;
    
    /**
    * Representación de la columna 'nombre' de la tabla 'TorneoInterno'
    *
    * @var string
    */
    var $nombre;

    /**
    * Representación de la columna 'tipo' de la tabla 'TorneoInterno'
    *
    * @var string
    */
    var $tipo;

    /**
    * Representación de la columna 'estado' de la tabla 'TorneoInterno'
    *
    * @var string
    */
    var $estado;

    /**
    * Representación de la columna 'fechaModif' de la tabla 'TorneoInterno'
    *
    * @var string
    */
    var $fechaModif;

    /**
    * Representación de la columna 'fechaCrea' de la tabla 'TorneoInterno'
    *
    * @var string
    */
    var $fechaCrea;

    /**
    * Representación de la columna 'mandante' de la tabla 'TorneoInterno'
    *
    * @var string
    */
    var $mandante;

    /**
    * Representación de la columna 'condicional' de la tabla 'TorneoInterno'
    *
    * @var string
    */
    var $condicional;

    /**
    * Representación de la columna 'orden' de la tabla 'TorneoInterno'
    *
    * @var string
    */
    var $orden;
    
    /**
    * Representación de la columna 'cupoActual' de la tabla 'TorneoInterno'
    *
    * @var string
    */
    var $cupoActual;
    
    /**
    * Representación de la columna 'cupoMaximo' de la tabla 'TorneoInterno'
    *
    * @var string
    */
    var $cupoMaximo;
    
    /**
    * Representación de la columna 'cantidadTorneos' de la tabla 'TorneoInterno'
    *
    * @var string
    */
    var $cantidadTorneos;
    
    /**
    * Representación de la columna 'maximoTorneos' de la tabla 'TorneoInterno'
    *
    * @var string
    */
    var $maximoTorneos;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'TorneoInterno'
    *
    * @var string
    */
    var $usucreaId;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'TorneoInterno'
    *
    * @var string
    */
    var $usumodifId;

    /**
    * Representación de la columna 'codigo' de la tabla 'TorneoInterno'
    *
    * @var string
    */
    var $codigo;

    /**
    * Representación de la columna 'reglas' de la tabla 'TorneoInterno'
    *
    * @var string
    */
    var $reglas;

    /**
    * Representación de la columna 'jsonTemp' de la tabla 'TorneoInterno'
    *
    * @var string
    */
    var $jsonTemp;



    /**
    * Constructor de clase
    *
    *
    * @param String $torneoId id del torneo interno
    *
    * @return no
    * @throws Exception si TorneoInterno no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($torneoId="")
    {
        if ($torneoId != "") {

            $this->torneoId = $torneoId;

            $TorneoInternoMySqlDAO = new TorneoInternoMySqlDAO();

            $TorneoInterno = $TorneoInternoMySqlDAO->load($this->torneoId);


            if ($TorneoInterno != null && $TorneoInterno != "") {
                $this->torneoId = $TorneoInterno->torneoId;
                $this->fechaInicio = $TorneoInterno->fechaInicio;
                $this->fechaFin = $TorneoInterno->fechaFin;
                $this->descripcion = $TorneoInterno->descripcion;
                $this->nombre = $TorneoInterno->nombre;
                $this->tipo = $TorneoInterno->tipo;
                $this->estado = $TorneoInterno->estado;
                $this->fechaModif = $TorneoInterno->fechaModif;
                $this->fechaCrea = $TorneoInterno->fechaCrea;
                $this->mandante = $TorneoInterno->mandante;
                $this->usucreaId = $TorneoInterno->usucreaId;
                $this->usumodifId = $TorneoInterno->usumodifId;
                $this->condicional = $TorneoInterno->condicional;
                $this->orden = $TorneoInterno->orden;
                $this->cupoActual = $TorneoInterno->cupoActual;
                $this->cupoMaximo = $TorneoInterno->cupoMaximo;
                $this->cantidadTorneos = $TorneoInterno->cantidadTorneos;
                $this->maximoTorneos = $TorneoInterno->maximoTorneos;
                $this->codigo = $TorneoInterno->codigo;
                $this->reglas = $TorneoInterno->reglas;
                $this->jsonTemp = $TorneoInterno->jsonTemp;
            }
            else {
                throw new Exception("No existe " . get_class($this), "28");
            }

        }
    }




    /**
    * Realizar una consulta en la tabla de torneos 'TorneoInterno'
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
    * @throws Exception si los torneos no existen
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getTorneosCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn)
    {

        $TorneoInternoMySqlDAO = new TorneoInternoMySqlDAO();

        $torneos = $TorneoInternoMySqlDAO->queryTorneosCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn);

        if ($torneos != null && $torneos != "") 
        {
            return $torneos;
        }
        else 
        {
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

        $TorneoInternoMySqlDAO = new TorneoInternoMySqlDAO($transaction);
        return $TorneoInternoMySqlDAO->insert($this);

    }

    /**
    * Agregar un torneo en la base de datos
    *
    *
    * @param String tipoTorneo tipoTorneo
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
    public function agregarTorneo($tipoTorneo, $usuarioId, $mandante, $detalles,$transaccion)
    {

        //Verificamos Detalles a tomar en cuenta al momento de verificar si puede ganar un torneo
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
        $torneoElegido = 0;
        $torneoTieneRollower = false;
        $rollowerTorneo = 0;
        $rollowerDeposito = 0;

        //Obtenemos todos los torneos disponibles
        $sqlTorneos = "select a.torneo_id torneo_id,a.tipo,a.fecha_inicio,a.fecha_fin,now() test from torneo_interno a where a.mandante=" . $mandante . " and now()  between (a.fecha_inicio) and (a.fecha_fin) and a.estado='A' ORDER BY a.orden DESC,a.fecha_crea ASC ";

        if($CodePromo != ""){
            $sqlTorneos = "select a.torneo_id,a.tipo,a.fecha_inicio,a.fecha_fin from torneo_interno a INNER JOIN torneo_detalle b ON (a.torneo_id=b.torneo_id AND b.tipo='CODEPROMO' AND b.valor='".$CodePromo."') where a.mandante=" . $mandante . " and now()  between (a.fecha_inicio) and (a.fecha_fin) and a.estado='A' ORDER BY a.orden DESC,a.fecha_crea ASC ";

        }

        $torneosDisponibles = $this->execQuery($transaccion,$sqlTorneos);



        foreach ($torneosDisponibles as $torneo) {



            if (!$cumpleCondiciones) {

                //Obtenemos todos los detalles del torneo
                $sqlDetalleTorneo = "select * from torneo_detalle a where a.torneo_id='" . $torneo->{"a.torneo_id"} . "' AND (moneda='' OR moneda='PEN') ";
                $torneoDetalles = $this->execQuery($transaccion,$sqlDetalleTorneo);

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

                $puederepetirTorneo=false ;
                $ganaTorneoId=0;


                $maximopago = 0;
                $maximodeposito = 0;
                $minimodeposito = 0;
                $valortorneo = 0;
                $tipoproducto = 0;
                $tipotorneo = "";
                $torneoTieneRollower = false;
                $tiposaldo=-1;

                if($tipoTorneo != $torneo->{"a.tipo"}){
                    $cumpleCondiciones = false;

                }


                foreach ($torneoDetalles as $torneoDetalle) {

                    switch ($torneoDetalle->{"a.tipo"}) {


                        case "TIPOPRODUCTO":
                            $tipoproducto = $torneoDetalle->{"a.valor"};


                            break;

                        case "CANTDEPOSITOS":
                            if ($detalleDepositos != ($torneoDetalle->{"a.valor"} - 1) && $torneo->{"a.tipo"}==2) {

                                $cumpleCondiciones = false;

                            }

                            break;

                        case "CONDEFECTIVO":
                            if ($detalleDepositoEfectivo) {
                                if (($torneoDetalle->{"a.valor"} == "true")) {
                                    $condicionmetodoPago = true;
                                }
                            } else {
                                if (($torneoDetalle->{"a.valor"} != "true")) {
                                    $condicionmetodoPago = true;
                                }
                            }
                            $condicionmetodoPagocount++;


                            break;


                        case "PORCENTAJE":
                            $tipotorneo = "PORCENTAJE";
                            $valortorneo = $torneoDetalle->{"a.valor"};

                            break;


                        case "NUMERODEPOSITO":

                            break;

                        case "MAXJUGADORES":

                            break;


                        case "MAXPAGO":

                            if($torneoDetalle->{"a.moneda"} == $detalleMonedaUSER) {

                                $maximopago = $torneoDetalle->{"a.valor"};

                            }
                            break;

                        case "MAXDEPOSITO":

                            $maximodeposito = $torneoDetalle->{"a.valor"};
                            if($torneoDetalle->{"a.moneda"} == $detalleMonedaUSER) {

                                if ($detalleValorDeposito > $maximodeposito) {
                                    $cumpleCondiciones = false;
                                }
                            }

                            break;

                        case "MINDEPOSITO":

                            $minimodeposito = $torneoDetalle->{"a.valor"};

                            if($torneoDetalle->{"a.moneda"} == $detalleMonedaUSER) {

                                if ($detalleValorDeposito < $minimodeposito) {
                                    $cumpleCondiciones = false;
                                }
                            }

                            break;

                        case "VALORBONO":
                            if($torneoDetalle->{"a.moneda"} == $detalleMonedaUSER) {

                                $valortorneo = $torneoDetalle->{"a.valor"};
                                $tipotorneo = "VALOR";
                            }
                            break;

                        case "CONDPAYMENT":

                            if ($detalleDepositoMetodoPago == $torneoDetalle->{"a.valor"}) {
                                $condicionmetodoPago = true;
                            }
                            $condicionmetodoPagocount++;

                            break;

                        case "CONDPAISPV":

                            $condicionPaisPVcount = $condicionPaisPVcount + 1;
                            if ($torneoDetalle->{"a.valor"} == $detallePaisPV) {
                                $condicionPaisPV = true;
                            }

                            break;

                        case "CONDDEPARTAMENTOPV":

                            $condicionDepartamentoPVcount = $condicionDepartamentoPVcount + 1;
                            if ($torneoDetalle->{"a.valor"} == $detalleDepartamentoPV) {
                                $condicionDepartamentoPV = true;
                            }

                            break;

                        case "CONDCIUDADPV":

                            $condicionCiudadPVcount = $condicionCiudadPVcount + 1;

                            if ($torneoDetalle->{"a.valor"} == $detalleCiudadPV) {
                                $condicionCiudadPV = true;
                            }

                            break;

                        case "CONDPAISUSER":

                            $condicionPaisUSERcount = $condicionPaisUSERcount + 1;
                            if ($torneoDetalle->{"a.valor"} == $detallePaisUSER) {
                                $condicionPaisUSER = true;
                            }

                            break;

                        case "CONDDEPARTAMENTOUSER":

                            $condicionDepartamentoUSERcount = $condicionDepartamentoUSERcount + 1;
                            if ($torneoDetalle->{"a.valor"} == $detalleDepartamentoUSER) {
                                $condicionDepartamentoUSER = true;
                            }

                            break;

                        case "CONDCIUDADUSER":

                            $condicionCiudadUSERcount = $condicionCiudadUSERcount + 1;

                            if ($torneoDetalle->{"a.valor"} == $detalleCiudadUSER) {
                                $condicionCiudadUSER = true;
                            }

                            break;

                        case "CONDPUNTOVENTA":

                            $condicionPuntoVentacount = $condicionPuntoVentacount + 1;

                            if ($torneoDetalle->{"a.valor"} == $detallePuntoVenta) {
                                $condicionPuntoVenta = true;
                            }

                            break;

                        case "EXPDIA":

                            break;

                        case "EXPFECHA":

                            break;

                        case "WFACTORBONO":
                            $torneoTieneRollower = true;

                            $rollowerTorneo = $torneoDetalle->{"a.valor"};

                            break;

                        case "WFACTORDEPOSITO":
                            $torneoTieneRollower = true;
                            $rollowerDeposito = $torneoDetalle->{"a.valor"};

                            break;

                        case "VALORROLLOWER":
                            if($torneoDetalle->{"a.moneda"} == $detalleMonedaUSER) {

                                $torneoTieneRollower = true;
                                $rollowerValor = $torneoDetalle->{"a.valor"};
                            }
                            break;
                        case "REPETIRBONO":

                            if($torneoDetalle->{"a.valor"} ){
                                $puederepetirTorneo = true;
                            }

                            break;

                        case "WINBONOID":
                            $ganaTorneoId = $torneoDetalle->{"a.valor"};
                            $tipotorneo = "WINBONOID";
                            $valor_torneo=0;

                            break;

                        case "TIPOSALDO":
                            $tiposaldo = $torneoDetalle->{"a.valor"};

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

                            if($CodePromo != ""){
                                if( $CodePromo != $torneoDetalle->{"a.valor"}){
                                    $condicionTrigger = false;

                                }
                            }else{

                                if($tipoTorneo==2){
                                    $sqlDetalleTorneoPendiente = "SELECT a.usutorneo_id FROM usuario_torneo a WHERE a.torneo_id='" . $torneo->{"a.torneo_id"} . "' AND a.usuario_id='".$usuarioId."' AND a.estado='P'";
                                    $torneoDetallesPendiente = $this->execQuery($transaccion,$sqlDetalleTorneoPendiente);

                                    if(oldCount($torneoDetallesPendiente)>0){
                                        $condicionTriggerPosterior=$torneoDetallesPendiente[0]->usutorneo_id;

                                    }else{
                                        $condicionTrigger = false;

                                    }

                                }else{
                                    $condicionTrigger = false;

                                }

                            }

                            break;

                        default:

                            //   if (stristr($torneodetalle->{'torneo_detalle.tipo'}, 'CONDGAME')) {
                            //
                            // }
                            //
                            //   if (stristr($torneodetalle->{'torneo_detalle.tipo'}, 'ITAINMENT')) {
                            //
                            //
                            //
                            //   }
                            break;
                    }
                }


                if(!$condicionTrigger){
                    $cumpleCondiciones = false;
                }

                if($CodePromo == "") {

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
                if($CodePromo == "") {

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


                    if ($puederepetirTorneo) {
                        $torneoElegido = $torneo->{"a.torneo_id"};

                    } else {
                        $sqlRepiteTorneo = "select * from usuario_torneo a where a.torneo_id='" . $torneo->{"a.torneo_id"} . "' AND a.usuario_id = '" . $usuarioId . "'";
                        $repiteTorneo = $this->execQuery($transaccion,$sqlRepiteTorneo);

                        if ((!$puederepetirTorneo && oldCount($repiteTorneo) == 0)) {
                            $torneoElegido = $torneo->{"a.torneo_id"};
                        } else {
                            $cumpleCondiciones = false;
                        }

                    }


                }

                if($cumpleCondiciones){
                    if($transaccion != ''){
                        if ($tipotorneo == "PORCENTAJE") {

                            $valor_torneo = ($detalleValorDeposito) * ($valortorneo) / 100;

                            if ($valor_torneo > $maximopago) {
                                $valor_torneo = $maximopago;
                            }

                        } elseif ($tipotorneo == "VALOR") {

                            $valor_torneo = $valortorneo;

                        }

                        if($condicionTriggerPosterior > 0){
                            $strsql = "UPDATE torneo_interno SET torneo_interno.cupo_actual =torneo_interno.cupo_actual + ".$valor_torneo. " WHERE torneo_interno.cupo_maximo >= (torneo_interno.cupo_actual + ".$valor_torneo. ") AND torneo_interno.torneo_id ='".$torneoElegido."'";

                        }else{
                            $strsql = "UPDATE torneo_interno SET torneo_interno.cupo_actual =torneo_interno.cupo_actual + ".$valor_torneo. ",torneo_interno.cantidad_torneos=torneo_interno.cantidad_torneos+1 WHERE (torneo_interno.cupo_maximo >= (torneo_interno.cupo_actual + ".$valor_torneo. ") OR torneo_interno.cupo_maximo = 0) AND ((torneo_interno.maximo_torneos >= (torneo_interno.cantidad_torneos+1)) OR torneo_interno.maximo_torneos=0) AND torneo_interno.torneo_id ='".$torneoElegido."'";

                        }
                        if($usuarioId==886){
                            print_r("TEST".$cumpleCondiciones);
                            print_r($strsql);
                        }

                        $resp = $this->execUpdate($transaccion,$strsql);
                        if($usuarioId==886){
                            print_r($resp);
                        }
                        if($resp>0){
                            $cumpleCondiciones=true;
                        }else{

                            $cumpleCondiciones=false;
                            $torneoElegido=0;

                            if($condicionTriggerPosterior > 0) {
                                $strsql = "UPDATE usuario_torneo SET usuario_torneo.estado = 'E',usuario_torneo.error_id='1' WHERE usuario_torneo.usutorneo_id ='".$condicionTriggerPosterior."'";
                                $resp = $this->execUpdate($transaccion,$strsql);

                            }

                        }

                    }

                }

            }

        }

        $respuesta = array();
        $respuesta["Torneo"] = 0;
        $respuesta["WinBonus"] = false;


        if ($torneoElegido != 0 && $tipotorneo != "") {

            if($tipoTorneo == 2) {
                if ($tipotorneo == "PORCENTAJE") {

                    $valor_torneo = ($detalleValorDeposito) * ($valortorneo) / 100;



                    if ($valor_torneo > $maximopago) {
                        $valor_torneo = $maximopago;
                    }

                } elseif ($tipotorneo == "VALOR") {

                    $valor_torneo = $valortorneo;

                }



                $valorBase = $detalleValorDeposito;

                $strSql = array();
                $contSql = 0;
                $estadoTorneo = 'A';
                $rollowerRequerido = 0;
                $SumoSaldo = false;

                if (!$torneoTieneRollower) {

                    if ($CodePromo != "" && $tipotorneo == 2) {
                        $estadoTorneo = 'P';

                    } else {
                        if ($ganaTorneoId == 0) {
                            $tipoTorneoS = 'D';
                            switch ($tiposaldo) {
                                case 0:


                                    $contSql = $contSql + 1;
                                    $strSql[$contSql] = "update registro,torneo_interno set registro.creditos_base_ant=registro.creditos_base,registro.creditos_base=registro.creditos_base+" . $valor_torneo . " where registro.mandante=" . $mandante . " and registro.usuario_id=" . $usuarioId . " AND torneo_id ='" . $torneoElegido . "'";
                                    $estadoTorneo = 'R';
                                    $SumoSaldo = true;

                                    break;

                                case 1:
                                    $contSql = $contSql + 1;
                                    $strSql[$contSql] = "update registro,torneo_interno set registro.creditos_ant=registro.creditos,registro.creditos=registro.creditos+" . $valor_torneo . " where registro.mandante=" . $mandante . " and registro.usuario_id=" . $usuarioId . " AND torneo_id ='" . $torneoElegido . "'";
                                    $estadoTorneo = 'R';
                                    $SumoSaldo = true;

                                    break;

                                case 2:
                                    $contSql = $contSql + 1;
                                    $strSql[$contSql] = "update registro,torneo_interno set registro.saldo_especial=registro.saldo_especial+" . $valor_torneo . " where registro.mandante=" . $mandante . " and registro.usuario_id=" . $usuarioId . " AND torneo_id ='" . $torneoElegido . "'";
                                    $estadoTorneo = 'R';
                                    $SumoSaldo = true;

                                    break;

                            }

                        } else {

                            $resp = $this->agregarTorneoFree($ganaTorneoId, $usuarioId, $mandante, $detalles, '', '', $transaccion);

                            if ($transaccion == "") {
                                foreach ($resp->queries as $val) {
                                    $contSql = $contSql + 1;
                                    $strSql[$contSql] = $val;
                                }
                            }

                            $estadoTorneo = 'R';

                        }
                    }


                } else {

                    if ($CodePromo != "" && $tipotorneo == 2) {
                        $estadoTorneo = 'P';

                    } else {
                        //$rollowerDeposito && $ganaTorneoId == 0
                        if ($rollowerDeposito) {
                            $rollowerRequerido = $rollowerRequerido + ($rollowerDeposito * $detalleValorDeposito);
                        }

                        if ($rollowerTorneo) {
                            $rollowerRequerido = $rollowerRequerido + ($rollowerTorneo * $valor_torneo);

                        }
                        if ($rollowerValor) {
                            $rollowerRequerido = $rollowerRequerido + ($rollowerValor);

                        }

                        $contSql = $contSql + 1;
                        $strSql[$contSql] = "update registro,torneo_interno set registro.creditos_torneo=registro.creditos_torneo+" . $valor_torneo . " where registro.mandante=" . $mandante . " and registro.usuario_id=" . $usuarioId . "  AND torneo_id ='" . $torneoElegido . "'";
                    }


                }

                if ($condicionTriggerPosterior > 0) {


                    $contSql = $contSql + 1;
                    $strSql[$contSql] = "UPDATE usuario_torneo,torneo_interno SET usuario_torneo.valor='" . $valor_torneo . "',usuario_torneo.valor_torneo='" . $valortorneo . "',usuario_torneo.valor_base='" . $valorBase . "',usuario_torneo.estado='" . $estadoTorneo . "',usuario_torneo.error_id='0',usuario_torneo.externo_id='0',usuario_torneo.mandante='" . $mandante . "',usuario_torneo.rollower_requerido='" . $rollowerRequerido . "' WHERE usuario_torneo.usutorneo_id = '" . $condicionTriggerPosterior . "' AND usuario_torneo.torneo_id ='" . $torneoElegido . "' AND torneo_interno.torneo_id ='" . $torneoElegido . "'  AND torneo_interno.torneo_id ='" . $torneoElegido . "'";


                    $contSql = $contSql + 1;
                    $strSql[$contSql] = "UPDATE torneo_interno SET torneo_interno.cupo_actual =torneo_interno.cupo_actual + " . $valor_torneo . " WHERE torneo_interno.torneo_id ='" . $torneoElegido . "'";

                } else {
                    $contSql = $contSql + 1;
                    $strSql[$contSql] = "insert into usuario_torneo (usuario_id,torneo_id,valor,valor_torneo,valor_base,fecha_crea,estado,error_id,externo_id,mandante,rollower_requerido,usucrea_id,usumodif_id) SELECT " . $usuarioId . "," . $torneoElegido . "," . $valor_torneo . "," . $valortorneo . " ," . $valorBase . " ,'" . date('Y-m-d H:i:s') . "','" . $estadoTorneo . "','0',0," . $mandante . ", $rollowerRequerido,0,0 FROM torneo_interno WHERE  torneo_id ='" . $torneoElegido . "'";


                    $contSql = $contSql + 1;
                    $strSql[$contSql] = "UPDATE torneo_interno SET torneo_interno.cupo_actual =torneo_interno.cupo_actual + " . $valor_torneo . ",torneo_interno.cantidad_torneos=torneo_interno.cantidad_torneos+1 WHERE torneo_interno.torneo_id ='" . $torneoElegido . "'";
                }

                if ($transaccion != "") {

                    foreach ($strSql as $val) {

                        $resp = $this->execUpdate($transaccion, $val);

                        if ($SumoSaldo && (strpos($val, 'insert into usuario_torneo') !== false)) {
                            $last_insert_id = $resp;
                            $tibodetorneo = 'F';

                            if ($tipoTorneo == 2) {
                                $tibodetorneo = 'D';

                            }


                            if ($last_insert_id != "" && is_numeric($last_insert_id)) {
                                $sql2 = "insert into torneo_log (usuario_id,tipo,valor,estado,id_externo,mandante,transaccion_id,tipotorneo_id) values (" . $usuarioId . ",'" . $tibodetorneo . "','" . $valor_torneo . "','L','" . $last_insert_id . "','0',0,4)";
                                $resp2 = $this->execUpdate($transaccion, $sql2);
                            }

                        }


                    }

                }


                // $contSql = $contSql + 1;
                // $strSql[$contSql] = "insert into torneo_log (usuario_id,tipo,valor,estado,id_externo,mandante,transaccion_id,tipotorneo_id) values (" . $usuarioId . ",'" . $tipoTorneoS . "','" . $valor_torneo  . "','L','0'," . $mandante . ",0,4)";


                $respuesta["WinBonus"] = true;
                $respuesta["SumoSaldo"] = $SumoSaldo;
                $respuesta["Torneo"] = $torneoElegido;
                $respuesta["Valor"] = $valor_torneo;
                $respuesta["queries"] = $strSql;
            }

            if($tipoTorneo == 3){
                $resp=$this->agregarTorneoFree($torneoElegido,$usuarioId,$mandante,$detalles,'','',$transaccion);

                if($transaccion == ''){
                    foreach ($resp->queries as $val) {
                        $contSql = $contSql + 1;
                        $strSql[$contSql]=$val;
                    }
                }
            }

            if($tipoTorneo == 6){

                $resp=$this->agregarTorneoFree($torneoElegido,$usuarioId,$mandante,$detalles,'','',$transaccion);

                if($transaccion == ''){
                    foreach ($resp->queries as $val) {
                        $contSql = $contSql + 1;
                        $strSql[$contSql]=$val;
                    }
                }

            }
        }

        return json_decode(json_encode($respuesta));

    }

    /**
    * Agregar un torneo en la base de datos
    *
    *
    * @param String tipoTorneo tipoTorneo
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
    public function agregarTorneoFree($torneoid, $usuarioId, $mandante, $detalles,$ejecutarSQL,$codebonus,$transaccion)
    {

        //Verificamos Detalles a tomar en cuenta al momento de verificar si puede ganar un torneo
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
        $torneoElegido = 0;
        $torneoTieneRollower = false;
        $rollowerTorneo = 0;
        $rollowerDeposito = 0;

        //Obtenemos todos los torneos disponibles
        $sqlTorneos = "select a.torneo_id,a.tipo from torneo_interno a where a.mandante=" . $mandante . " and  a.estado='A' and a.torneo_id='".$torneoid."'";

        $torneosDisponibles = $this->execQuery($transaccion,$sqlTorneos);

        foreach ($torneosDisponibles as $torneo) {

            if (!$cumpleCondiciones ) {

                //Obtenemos todos los detalles del torneo
                $sqlDetalleTorneo = "select * from torneo_detalle a where a.torneo_id='" . $torneo->{"a.torneo_id"} . "' AND (moneda='' OR moneda='PEN') ";

                $torneoDetalles = $this->execQuery($transaccion,$sqlDetalleTorneo);

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

                $puederepetirTorneo=false ;
                $ganaTorneoId=0;


                $maximopago = 0;
                $maximodeposito = 0;
                $minimodeposito = 0;
                $valortorneo = 0;
                $tipoproducto = 0;
                $tipotorneo = "";
                $tipotorneo2=$torneo->{"a.tipo"};
                $torneoTieneRollower = false;



                foreach ($torneoDetalles as $torneoDetalle) {


                    switch ($torneoDetalle->{"a.tipo"}) {


                        case "TIPOPRODUCTO":
                            $tipoproducto = $torneoDetalle->{"a.valor"};

                            break;

                        case "CANTDEPOSITOS":
                            if ($detalleDepositos != ($torneoDetalle->{"a.valor"} - 1) && $torneo->{"a.tipo"}==2) {

                                $cumpleCondiciones = false;

                            }

                            break;

                        case "CONDEFECTIVO":
                            if ($detalleDepositoEfectivo) {
                                if (($torneoDetalle->{"a.valor"} == "true")) {
                                    $condicionmetodoPago = true;
                                }
                            } else {
                                if (($torneoDetalle->{"a.valor"} != "true")) {
                                    $condicionmetodoPago = true;
                                }
                            }
                            $condicionmetodoPagocount++;


                            break;


                        case "PORCENTAJE":
                            $tipotorneo = "PORCENTAJE";
                            $valortorneo = $torneoDetalle->{"a.valor"};

                            break;


                        case "NUMERODEPOSITO":

                            break;

                        case "MAXJUGADORES":

                            break;


                        case "MAXPAGO":
                            $maximopago = $torneoDetalle->{"a.valor"};

                            break;

                        case "MAXDEPOSITO":

                            $maximodeposito = $torneoDetalle->{"a.valor"};
                            if($torneoDetalle->{"a.moneda"} == $detalleMonedaUSER) {

                                if ($detalleValorDeposito > $maximodeposito) {
                                    $cumpleCondiciones = false;
                                }
                            }
                            break;

                        case "MINDEPOSITO":

                            $minimodeposito = $torneoDetalle->{"a.valor"};

                            if($torneoDetalle->{"a.moneda"} == $detalleMonedaUSER) {

                                if ($detalleValorDeposito < $minimodeposito) {
                                    $cumpleCondiciones = false;
                                }
                            }

                            break;

                        case "VALORBONO":

                            if($torneoDetalle->{"a.moneda"} == $detalleMonedaUSER) {

                                $valortorneo = $torneoDetalle->{"a.valor"};
                                $tipotorneo = "VALOR";
                            }
                            break;

                        case "CONDPAYMENT":

                            if ($detalleDepositoMetodoPago == $torneoDetalle->{"a.valor"}) {
                                $condicionmetodoPago = true;
                            }
                            $condicionmetodoPagocount++;

                            break;

                        case "CONDPAISPV":

                            $condicionPaisPVcount = $condicionPaisPVcount + 1;
                            if ($torneoDetalle->{"a.valor"} == $detallePaisPV) {
                                $condicionPaisPV = true;
                            }

                            break;

                        case "CONDDEPARTAMENTOPV":

                            $condicionDepartamentoPVcount = $condicionDepartamentoPVcount + 1;
                            if ($torneoDetalle->{"a.valor"} == $detalleDepartamentoPV) {
                                $condicionDepartamentoPV = true;
                            }

                            break;

                        case "CONDCIUDADPV":

                            $condicionCiudadPVcount = $condicionCiudadPVcount + 1;

                            if ($torneoDetalle->{"a.valor"} == $detalleCiudadPV) {
                                $condicionCiudadPV = true;
                            }

                            break;

                        case "CONDPAISUSER":

                            $condicionPaisUSERcount = $condicionPaisUSERcount + 1;

                            if ($torneoDetalle->{"a.valor"} == $detallePaisUSER) {

                                $condicionPaisUSER = true;
                            }

                            break;

                        case "CONDDEPARTAMENTOUSER":

                            $condicionDepartamentoUSERcount = $condicionDepartamentoUSERcount + 1;
                            if ($torneoDetalle->{"a.valor"} == $detalleDepartamentoUSER) {
                                $condicionDepartamentoUSER = true;
                            }

                            break;

                        case "CONDCIUDADUSER":

                            $condicionCiudadUSERcount = $condicionCiudadUSERcount + 1;

                            if ($torneoDetalle->{"a.valor"} == $detalleCiudadUSER) {
                                $condicionCiudadUSER = true;
                            }

                            break;

                        case "CONDPUNTOVENTA":

                            $condicionPuntoVentacount = $condicionPuntoVentacount + 1;

                            if ($torneoDetalle->{"a.valor"} == $detallePuntoVenta) {
                                $condicionPuntoVenta = true;
                            }

                            break;

                        case "EXPDIA":

                            break;

                        case "EXPFECHA":

                            break;

                        case "WFACTORBONO":
                            $torneoTieneRollower = true;

                            $rollowerTorneo = $torneoDetalle->{"a.valor"};

                            break;

                        case "WFACTORDEPOSITO":
                            $torneoTieneRollower = true;
                            $rollowerDeposito = $torneoDetalle->{"a.valor"};

                            break;



                        case "VALORROLLOWER":
                            if($torneoDetalle->{"a.moneda"} == $detalleMonedaUSER) {

                                $torneoTieneRollower = true;
                                $rollowerValor = $torneoDetalle->{"a.valor"};
                            }
                            break;

                        case "REPETIRBONO":

                            if($torneoDetalle->{"a.valor"} ){
                                $puederepetirTorneo = true;
                            }

                            break;

                        case "WINBONOID":
                            $ganaTorneoId = $torneoDetalle->{"a.valor"};

                            break;


                        case "TIPOSALDO":
                            $tiposaldo = $torneoDetalle->{"a.valor"};

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

                            //   if (stristr($torneodetalle->{'torneo_detalle.tipo'}, 'CONDGAME')) {
                            //
                            // }
                            //
                            //   if (stristr($torneodetalle->{'torneo_detalle.tipo'}, 'ITAINMENT')) {
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

                    if($puederepetirTorneo){

                        $torneoElegido = $torneo->{"a.torneo_id"};

                    }else{
                        $sqlRepiteTorneo = "select * from usuario_torneo a where a.torneo_id='" . $torneo->{"a.torneo_id"} . "' AND a.usuario_id = '" .$usuarioId ."'";
                        $repiteTorneo = $this->execQuery($transaccion,$sqlRepiteTorneo);

                        if((!$puederepetirTorneo && oldCount($repiteTorneo) == 0)){
                            $torneoElegido = $torneo->{"a.torneo_id"};
                        }else{
                            $cumpleCondiciones = false;
                        }
                    }


                }



            }


        }

        $respuesta = array();
        $respuesta["Torneo"] = 0;
        $respuesta["WinBonus"] = false;


        if ($torneoElegido != 0 && $tipotorneo2 != "") {

            if ($tipotorneo == "PORCENTAJE") {
                $valor_torneo = ($detalleValorDeposito) * ($valortorneo) / 100;

                if ($valor_torneo > $maximopago) {
                    $valor_torneo = $maximopago;
                }

            } elseif ($tipotorneo == "VALOR") {

                $valor_torneo = $valortorneo;

            }

            $valorBase = $detalleValorDeposito;

            $strSql = array();
            $contSql = 0;
            $estadoTorneo = 'A';
            $rollowerRequerido = 0;

            if (!$torneoTieneRollower) {




            } else {
                if ($rollowerDeposito ) {
                    $rollowerRequerido = $rollowerRequerido + ($rollowerDeposito * $detalleValorDeposito);
                }

                if ($rollowerTorneo ) {
                    $rollowerRequerido = $rollowerRequerido + ($rollowerTorneo * $valor_torneo);

                }
                if($rollowerValor){
                    $rollowerRequerido = $rollowerRequerido + ($rollowerValor);


                }

            }

            $strCodeBonus="";

            if($codebonus != ""){
                $strCodeBonus=" AND a.codigo ='" .$codebonus."'";

            }


            if ($tipotorneo2 == "5"){

                $sqlTorneosFree = "select a.torneo_id,a.usuario_id,a.estado from usuario_torneo a INNER JOIN torneo_interno b ON(a.torneo_id = b.torneo_id) where  a.estado='L' and a.torneo_id='".$torneoid ."'" . $strCodeBonus;

                $torneosFree = $this->execQuery($transaccion,$sqlTorneosFree);

                $ganoTorneoBool=false;

                foreach ($torneosFree as $torneoF) {

                    $sqlTorneosFree = "select a.usutorneo_id from usuario_torneo a INNER JOIN torneo_interno b ON(a.torneo_id = b.torneo_id) where  a.estado='L' and a.torneo_id='".$torneoid ."'". $strCodeBonus;
                    $torneosFreeLibres = $this->execQuery($transaccion,$sqlTorneosFree);
                    foreach ($torneosFreeLibres as $torneoLibre) {
                        if(!$ganoTorneoBool){
                            if($transaccion==""){
                                $contSql = $contSql + 1;
                                $strSql[$contSql] = "UPDATE usuario_torneo a SET a.usuario_id='".$usuarioId."',a.fecha_crea = '".date('Y-m-d H:i:s')."',a.estado='A' WHERE a.usuario_id='0' AND a.estado='L' AND a.usutorneo_id='".$torneoLibre->usutorneo_id."'";
                                $ganoTorneoBool=true;

                            }else{
                                $sqlstr= "UPDATE usuario_torneo a SET a.usuario_id='".$usuarioId."',a.fecha_crea = '".date('Y-m-d H:i:s')."',a.estado='A' WHERE a.usuario_id='0' AND a.estado='L' AND a.usutorneo_id='".$torneoLibre->usutorneo_id."'";

                                $q = $this->execUpdate($transaccion,$sqlstr);
                                if($q>0){
                                    $ganoTorneoBool=true;

                                }else{
                                    $ganoTorneoBool=false;

                                }

                            }

                        }
                    }

                }
            }elseif ($tipotorneo2 == "6"){
                $sqlTorneosFree = "select a.torneo_id from usuario_torneo a INNER JOIN torneo_interno b ON(a.torneo_id = b.torneo_id) where  a.estado='L' and a.torneo_id='".$torneoid ."'". $strCodeBonus;
                $torneosFree = $this->execQuery($transaccion,$sqlTorneosFree);

                $ganoTorneoBool=false;

                foreach ($torneosFree as $torneoF) {

                    $sqlTorneosFree = "select a.usutorneo_id from usuario_torneo a INNER JOIN torneo_interno b ON(a.torneo_id = b.torneo_id) where  a.estado='L' and a.torneo_id='".$torneoid ."'". $strCodeBonus;
                    $torneosFreeLibres = $this->execQuery($transaccion,$sqlTorneosFree);
                    foreach ($torneosFreeLibres as $torneoLibre) {
                        if(!$ganoTorneoBool){
                            if($transaccion=="") {

                                $contSql = $contSql + 1;
                                $strSql[$contSql] = "UPDATE usuario_torneo a SET a.usuario_id='" . $usuarioId . "',a.fecha_crea = '" . date('Y-m-d H:i:s') . "', a.estado='A' WHERE a.usuario_id='0' AND a.estado='L' AND a.usutorneo_id='" . $torneoLibre->usutorneo_id . "'";
                                $ganoTorneoBool = true;
                            }else{
                                $sqlstr= "UPDATE usuario_torneo a SET a.usuario_id='" . $usuarioId . "',a.fecha_crea = '" . date('Y-m-d H:i:s') . "', a.estado='A' WHERE a.usuario_id='0' AND a.estado='L' AND a.usutorneo_id='" . $torneoLibre->usutorneo_id . "'";

                                $q = $this->execUpdate($transaccion,$sqlstr);
                                if($q>0){
                                    $ganoTorneoBool=true;

                                }else{
                                    $ganoTorneoBool=false;

                                }

                            }

                        }
                    }

                }
            }elseif ($tipotorneo2 == "3"){

                $valor_torneo = $maximopago;


                $ganoTorneoBool=false;


                    $sqlTorneosFree = "select a.usutorneo_id from usuario_torneo a INNER JOIN torneo_interno b ON(a.torneo_id = b.torneo_id) where  a.estado='L' and a.torneo_id='".$torneoid ."'". $strCodeBonus;
                    $torneosFreeLibres = $this->execQuery($transaccion,$sqlTorneosFree);

                foreach ($torneosFreeLibres as $torneoLibre) {
                        if(!$ganoTorneoBool){

                            if (!$torneoTieneRollower) {
                                $estadoTorneo = 'R';
                            }else {
                                if ($rollowerDeposito) {
                                    $rollowerRequerido = $rollowerRequerido + ($rollowerDeposito * $detalleValorDeposito);
                                }

                                if ($rollowerTorneo) {
                                    $rollowerRequerido = $rollowerRequerido + ($rollowerTorneo * $valor_torneo);

                                }
                                if($rollowerValor){
                                    $rollowerRequerido = $rollowerRequerido + ($rollowerValor);


                                }

                            }



                            if($transaccion == '') {

                                if (!$torneoTieneRollower) {


                                    if($ganaTorneoId ==0){
                                        switch ($tiposaldo){
                                            case 0:
                                                $contSql = $contSql + 1;
                                                $strSql[$contSql] = "update registro set creditos_base_ant=creditos_base,creditos_base=creditos_base+" . $valor_torneo . " where mandante=" . $mandante . " and usuario_id=" . $usuarioId;


                                                $estadoTorneo = 'R';

                                                break;

                                            case 1:
                                                $contSql = $contSql + 1;
                                                $strSql[$contSql] = "update registro set creditos_ant=creditos,creditos=creditos+" . $valor_torneo . " where mandante=" . $mandante . " and usuario_id=" . $usuarioId;


                                                $estadoTorneo = 'R';

                                                break;

                                            case 2:
                                                $contSql = $contSql + 1;
                                                $strSql[$contSql] = "update registro set saldo_especial=saldo_especial+" . $valor_torneo . " where mandante=" . $mandante . " and usuario_id=" . $usuarioId;

                                                $estadoTorneo = 'R';
                                                $SumoSaldo=true;

                                                break;

                                        }

                                    }else{

                                        $resp=$this->agregarTorneoFree($ganaTorneoId,$usuarioId,$mandante,$detalles,'','',$transaccion);

                                        if($transaccion == ""){
                                            foreach ($resp->queries as $val) {
                                                $contSql = $contSql + 1;
                                                $strSql[$contSql]=$val;
                                            }
                                        }

                                        $estadoTorneo = 'R';

                                    }

                                } else {
                                    if ($rollowerDeposito) {
                                        $rollowerRequerido = $rollowerRequerido + ($rollowerDeposito * $detalleValorDeposito);
                                    }

                                    if ($rollowerTorneo) {
                                        $rollowerRequerido = $rollowerRequerido + ($rollowerTorneo * $valor_torneo);

                                        }
                                    if($rollowerValor){
                                        $rollowerRequerido = $rollowerRequerido + ($rollowerValor);


                                    }
                                    $contSql = $contSql + 1;
                                    $strSql[$contSql] = "update registro,torneo_interno set registro.creditos_torneo=registro.creditos_torneo+" . $valor_torneo . " where registro.mandante=" . $mandante . " and registro.usuario_id=" . $usuarioId." AND torneo_id ='".$torneoElegido."'";

                                }

                                $contSql = $contSql + 1;
                                $strSql[$contSql] = "UPDATE usuario_torneo a SET a.usuario_id='" . $usuarioId . "',a.fecha_crea = '" . date('Y-m-d H:i:s') . "',a.rollower_requerido='" . $rollowerRequerido . "',a.valor_torneo='" . $valor_torneo . "',a.valor='" . $valor_torneo . "', a.estado='" . $estadoTorneo . "' WHERE a.usuario_id='0' AND a.estado='L' AND a.usutorneo_id='" . $torneoLibre->usutorneo_id . "'";

                                $contSql = $contSql + 1;
                                $strSql[$contSql] = "INSERT INTO torneo_log (usuario_id,tipo,valor,estado,id_externo,mandante,transaccion_id,tipotorneo_id,fecha_crea,fecha_cierre) SELECT a.usuario_id,CASE WHEN b.tipo = 2 THEN 'D' ELSE 'F' END,a.valor,'L',a.usutorneo_id,0,'0',4,now(),now()  FROM  usuario_torneo a INNER JOIN torneo_interno  b ON (b.torneo_id = a.torneo_id)  WHERE a.usutorneo_id = " . $torneoLibre->usutorneo_id . " AND a.apostado >= a.rollower_requerido";

                                $ganoTorneoBool = true;

                            } else{
                                $sqlstr= "UPDATE usuario_torneo a SET a.usuario_id='" . $usuarioId . "',a.fecha_crea = '" . date('Y-m-d H:i:s') . "',a.rollower_requerido='" . $rollowerRequerido . "',a.valor_torneo='" . $valor_torneo . "',a.valor='" . $valor_torneo . "', a.estado='" . $estadoTorneo . "' WHERE a.usuario_id='0' AND a.estado='L' AND a.usutorneo_id='" . $torneoLibre->usutorneo_id . "'";

                                $q = $this->execUpdate($transaccion,$sqlstr);
                                if($q>0) {

                                    $sqlstr= "INSERT INTO torneo_log (usuario_id,tipo,valor,estado,id_externo,mandante,transaccion_id,tipotorneo_id,fecha_crea,fecha_cierre) SELECT a.usuario_id,CASE WHEN b.tipo = 2 THEN 'D' ELSE 'F' END,a.valor,'L',a.usutorneo_id,0,'0',4,now(),now()  FROM  usuario_torneo a INNER JOIN torneo_interno  b ON (b.torneo_id = a.torneo_id)  WHERE a.usutorneo_id = " . $torneoLibre->usutorneo_id . " AND a.apostado >= a.rollower_requerido";

                                    $q = $this->execUpdate($transaccion,$sqlstr);


                                    if (!$torneoTieneRollower) {


                                        if($ganaTorneoId ==0){
                                            switch ($tiposaldo){
                                                case 0:
                                                    $sqlstr = "update registro set creditos_base_ant=creditos_base,creditos_base=creditos_base+" . $valor_torneo . " where mandante=" . $mandante . " and usuario_id=" . $usuarioId;
                                                    $q = $this->execUpdate($transaccion,$sqlstr);


                                                    $estadoTorneo = 'R';

                                                    break;

                                                case 1:
                                                    $sqlstr = "update registro set creditos_ant=creditos,creditos=creditos+" . $valor_torneo . " where mandante=" . $mandante . " and usuario_id=" . $usuarioId;

                                                    $q = $this->execUpdate($transaccion,$sqlstr);

                                                    $estadoTorneo = 'R';

                                                    break;

                                                case 2:
                                                    $sqlstr = "update registro set saldo_especial=saldo_especial+" . $valor_torneo . " where mandante=" . $mandante . " and usuario_id=" . $usuarioId;
                                                    $q = $this->execUpdate($transaccion,$sqlstr);

                                                    $estadoTorneo = 'R';
                                                    $SumoSaldo=true;

                                                    break;

                                            }

                                        }else{

                                            $resp=$this->agregarTorneoFree($ganaTorneoId,$usuarioId,$mandante,$detalles,'','',$transaccion);

                                            if($transaccion == ""){
                                                foreach ($resp->queries as $val) {
                                                    $contSql = $contSql + 1;
                                                    $strSql[$contSql]=$val;
                                                }
                                            }

                                            $estadoTorneo = 'R';

                                        }

                                    } else {
                                        if ($rollowerDeposito) {
                                            $rollowerRequerido = $rollowerRequerido + ($rollowerDeposito * $detalleValorDeposito);
                                        }

                                        if ($rollowerTorneo) {
                                            $rollowerRequerido = $rollowerRequerido + ($rollowerTorneo * $valor_torneo);

                                        }
                                        if($rollowerValor){
                                            $rollowerRequerido = $rollowerRequerido + ($rollowerValor);


                                        }

                                        $sqlstr = "update registro,torneo_interno set registro.creditos_torneo=registro.creditos_torneo+" . $valor_torneo . " where registro.mandante=" . $mandante . " and registro.usuario_id=" . $usuarioId." AND torneo_id ='".$torneoElegido."'";

                                        $q = $this->execUpdate($transaccion,$sqlstr);

                                    }

                                    $ganoTorneoBool=true;


                                }else{
                                    $ganoTorneoBool=false;

                                }
                            }


                        }
                    }

            }





            $respuesta["WinBonus"] = true;
            $respuesta["Torneo"] = $torneoElegido;
            $respuesta["queries"] = $strSql;



            if($transaccion != ""){

            }else{
                if($ejecutarSQL){
                    foreach ($respuesta["queries"] as $querie){

                        $transaccionNueva=false;
                        if($transaccion == ''){
                            $TorneoInternoMySqlDAO = new TorneoInternoMySqlDAO();
                            $transaccion= $TorneoInternoMySqlDAO->getTransaction();
                            $transaccionNueva=true;

                        }
                        foreach ($respuesta["queries"] as $querie){

                            $this->execUpdate($transaccion,$querie);


                        }

                        if($transaccionNueva){
                            $transaccion->commit();
                        }

                    }
                }
            }
        }

        return json_decode(json_encode($respuesta));

    }

    /**
    * Verficiar rollower en el torneo
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
    public function verificarTorneoRollower($usuarioId, $detalles,$tipoProducto,$ticketId)
    {

        //Verificamos Detalles a tomar en cuenta al momento de verificar y aumentar rollower
        $detalleTipoApuesta = $detalles->TipoApuesta;
        $detalleSelecciones = $detalles->Selecciones;
        $detalleJuegosCasino = $detalles->JuegosCasino;
        $detalleValorApuesta = $detalles->ValorApuesta;
        $detalleCuotaTotal = 1;

        $respuesta = array();
        $respuesta["Torneo"] = 0;
        $respuesta["WinBonus"] = false;



        if(($tipoProducto == "SPORT" || $tipoProducto == "CASINO" ) && $usuarioId != ""){
            $torneoid=0;
            $usutorneo_id=0;
            $valorASumar=0;

            //Obtenemos todos los torneos disponibles
            $sqlTorneo = "select a.usutorneo_id,a.torneo_id,a.apostado,a.rollower_requerido,a.fecha_crea,torneo_interno.condicional,torneo_interno.tipo from usuario_torneo a INNER JOIN torneo_interno ON (torneo_interno.torneo_id = a.torneo_id ) where  a.estado='A' AND (torneo_interno.tipo = 2 OR torneo_interno.tipo = 3) AND a.usuario_id='" . $usuarioId . "'";
            $torneosDisponibles = $this->execQuery($sqlTorneo);

            if(oldCount($torneosDisponibles) > 0){
                if($tipoProducto == "SPORT") {

                    //$sqlSport = "select te.betmode,te.vlr_apuesta,td.ticket_id,td.apuesta,td.agrupador,td.logro,td.opcion,td.apuesta_id,td.agrupador_id,td.fecha_evento,td.hora_evento,te.usuario_id,td.sportid,td.matchid,td.ligaid from it_ticket_det td INNER JOIN it_ticket_enc te ON (td.ticket_id = te.ticket_id ) where  te.ticket_id='".$ticketId."' ";
                    $sqlSport = "select te.betmode,te.vlr_apuesta,td.ticket_id,td.apuesta,td.agrupador,td.logro,td.opcion,td.apuesta_id,td.agrupador_id,td.fecha_evento,td.hora_evento,te.usuario_id from it_ticket_det td INNER JOIN it_ticket_enc te ON (td.ticket_id = te.ticket_id ) where  te.ticket_id='".$ticketId."' ";

                    $detalleTicket = $this->execQuery($sqlSport);

                    $array = array();


                    foreach ($detalleTicket as $detalle) {
                        $detalles= array(
                           // "Deporte"=>$detalle->sportid,
                           // "Liga"=>$detalle->ligaid,
                           // "Evento"=>$detalle->apuesta_id,
                          //  "Cuota"=>$detalle->logro

                        );
                        $detalleValorApuesta =$detalle->vlr_apuesta;


                        array_push($array,$detalles);

                        $usuarioId =$detalle->usuario_id;
                        $betmode=$detalle->betmode;
                        $detalleCuotaTotal=$detalleCuotaTotal*$detalle->logro;

                    }
                    $detallesFinal= json_decode(json_encode($array));

                    $detalleSelecciones= $detallesFinal;


                }

                if($tipoProducto == "CASINO") {
                    $sqlSport = "select ct.tipo,ct.monto,ct.juego_id,ct.id,ct.usuario_id from casino_transaccion ct where  ct.id='".$ticketId."' ";
                    $detalleTicket = $this->execQuery($sqlSport);

                    $array = array();


                    foreach ($detalleTicket as $detalle) {
                        $detalles= array(
                            "Id"=>$detalle->juego_id
                        );
                        $detalleValorApuesta =$detalle->monto;

                        array_push($array,$detalles);

                        $usuarioId =$detalle->usuario_id;
                    }
                    $detallesFinal= json_decode(json_encode($array));

                    $detalleJuegosCasino= $detallesFinal;

                }

            }
            $tipoProducto="";

            foreach ($torneosDisponibles as $torneo) {
                if ($torneoid == 0) {

                    //Obtenemos todos los detalles del torneo
                    $sqlDetalleTorneo = "select * from torneo_detalle a where a.torneo_id='" . $torneo->{"a.torneo_id"} . "' AND (moneda='' OR moneda='PEN') ";
                    $torneoDetalles = $this->execQuery($sqlDetalleTorneo);




                    //Inicializamos variables
                    $cumplecondicion = true;
                    $cumplecondicionproducto = false;
                    $condicionesproducto = 0;
                    $torneoid = 0;
                    $valorapostado = 0;
                    $valorrequerido = 0;
                    $valorASumar = 0;

                    $sePuedeSimples=0;
                    $sePuedeCombinadas=0;
                    $minselcount=0;

                    $ganaTorneoId=0;
                    $tipotorneo="";
                    $ganaTorneoId=0;

                    if ($torneo->{"a.condicional"} == 'NA' || $torneo->{"a.condicional"} == '') {
                        $tipocomparacion = "OR";

                    } else {
                        $tipocomparacion = $torneo->{"a.condicional"};

                    }


                    foreach ($torneoDetalles as $torneoDetalle) {

                        switch ($torneoDetalle->{"a.tipo"}) {

                            case "TIPOPRODUCTO":

                                $tipoProducto = $torneoDetalle->{"a.valor"};
                                break;

                            case "EXPDIA":
                                $fechaTorneo = date('Y-m-d H:i:ss', strtotime($torneo->{"fecha_crea"} . ' + ' . $torneoDetalle->{"a.valor"} . ' days'));
                                $fecha_actual = date("Y-m-d H:i:ss", time());

                                if ($fechaTorneo < $fecha_actual) {
                                    $cumplecondicion = false;
                                }

                                break;

                            case "EXPFECHA":
                                $fechaTorneo = date('Y-m-d H:i:ss', strtotime($torneoDetalle->{"a.valor"}));
                                $fecha_actual = strtotime(date("Y-m-d H:i:ss", time()));

                                if ($fechaTorneo < $fecha_actual) {
                                    $cumplecondicion = false;
                                }
                                break;


                            case "LIVEORPREMATCH":


                                if ($torneoDetalle->{"a.valor"} == 2) {
                                    if($betmode == "PreLive") {
                                        $cumplecondicionproducto = true;

                                    }else{
                                        $cumplecondicionproducto = false;


                                    }

                                }

                                if ($torneoDetalle->{"a.valor"} == 1) {
                                    if($betmode == "Live") {
                                        $cumplecondicionproducto = true;

                                    }else{
                                        $cumplecondicionproducto = false;


                                    }

                                }

                                if ($torneoDetalle->{"a.valor"} == 0) {
                                    /*if($betmode == "Mixed") {
                                        $cumplecondicionproducto = true;

                                    }else{
                                        $cumplecondicionproducto = false;


                                    }*/

                                }

                                break;

                            case "MINSELCOUNT":
                                $minselcount=$torneoDetalle->{"a.valor"};

                                if ($torneoDetalle->{"a.valor"} > oldCount($detalleSelecciones)) {
                                    //$cumplecondicion = false;

                                }

                                break;

                            case "MINSELPRICE":

                                foreach ($detalleSelecciones as $item) {
                                    if ($torneoDetalle->{"a.valor"} > $item->Cuota) {
                                        $cumplecondicion = false;

                                    }
                                }


                                break;


                            case "MINSELPRICETOTAL":

                                if ($torneoDetalle->{"a.valor"} > $detalleCuotaTotal) {
                                    $cumplecondicion = false;

                                }


                                break;

                            case "MINBETPRICE":


                                if ($torneoDetalle->{"a.valor"} > $detalleValorApuesta) {
                                    $cumplecondicion = false;

                                }

                                break;

                            case "WINBONOID":
                                $ganaTorneoId = $torneoDetalle->{"a.valor"};
                                $tipotorneo = "WINBONOID";
                                $valor_torneo=0;

                                break;

                            case "TIPOSALDO":
                                $tiposaldo = $torneoDetalle->{"a.valor"};

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
                                $cumplecondicionproductotmp=false;
                                $condicionesproductotmp=0;

                                foreach ($detalleSelecciones as $item) {
                                    if ($tipocomparacion == "OR") {
                                        if ($torneoDetalle->{"a.valor"} == $item->Deporte) {
                                            $cumplecondicionproductotmp = true;

                                        }
                                    } elseif ($tipocomparacion == "AND") {
                                        if ($torneoDetalle->{"a.valor"} != $item->Deporte) {
                                            $cumplecondicionproductotmp = false;


                                        }

                                        if ($condicionesproductotmp == 0) {
                                            if ($torneoDetalle->{"a.valor"} == $item->Deporte) {
                                                $cumplecondicionproductotmp = true;
                                            }
                                        } else {
                                            if ($torneoDetalle->{"a.valor"} == $item->Deporte && $cumplecondicionproductotmp) {
                                                $cumplecondicionproducto = true;

                                            }
                                        }

                                    }
                                                                $condicionesproductotmp++;

                                }
                                if ($tipocomparacion == "OR") {
                                    if ($cumplecondicionproductotmp) {
                                        $cumplecondicionproducto = true;
                                    }
                                }elseif ($tipocomparacion == "AND") {
                                    if ($condicionesproducto == 0) {
                                        if ($cumplecondicionproductotmp) {
                                            $cumplecondicionproducto = true;
                                        }
                                    } else {
                                        if (!$cumplecondicionproductotmp) {
                                            $cumplecondicionproducto = false;
                                        }
                                    }
                                }

                                $condicionesproducto++;
                                break;

                            case "ITAINMENT3":
                                $cumplecondicionproductotmp=false;
                                $condicionesproductotmp=0;

                                foreach ($detalleSelecciones as $item) {
                                    if ($tipocomparacion == "OR") {
                                        if ($torneoDetalle->{"a.valor"} == $item->Liga) {
                                            $cumplecondicionproductotmp = true;

                                        }
                                    } elseif ($tipocomparacion == "AND") {
                                        if ($torneoDetalle->{"a.valor"} != $item->Liga) {
                                            $cumplecondicionproductotmp = false;

                                        }

                                        if ($condicionesproductotmp == 0) {
                                            if ($torneoDetalle->{"a.valor"} == $item->Liga) {
                                                $cumplecondicionproductotmp = true;
                                            }
                                        } else {
                                            if ($torneoDetalle->{"a.valor"} == $item->Liga && $cumplecondicionproductotmp) {
                                                $cumplecondicionproducto = true;

                                            }
                                        }

                                    }
                                    $condicionesproductotmp++;

                                }
                                if ($tipocomparacion == "OR") {
                                    if ($cumplecondicionproductotmp) {
                                        $cumplecondicionproducto = true;
                                    }
                                }elseif ($tipocomparacion == "AND") {
                                    if ($condicionesproducto == 0) {
                                        if ($cumplecondicionproductotmp) {
                                            $cumplecondicionproducto = true;
                                        }
                                    } else {
                                        if (!$cumplecondicionproductotmp) {
                                            $cumplecondicionproducto = false;
                                        }
                                    }
                                }

                                $condicionesproducto++;

                                break;
                            case "ITAINMENT4":
                                $cumplecondicionproductotmp=false;
                                $condicionesproductotmp=0;



                                foreach ($detalleSelecciones as $item) {
                                    if ($tipocomparacion == "OR") {
                                        if ($torneoDetalle->{"a.valor"} == $item->Evento) {
                                            $cumplecondicionproductotmp = true;

                                        }
                                    } elseif ($tipocomparacion == "AND") {
                                        if ($torneoDetalle->{"a.valor"} != $item->Evento) {
                                            $cumplecondicionproductotmp = false;

                                        }

                                        if ($condicionesproductotmp == 0) {

                                            if ($torneoDetalle->{"a.valor"} == $item->Evento) {
                                                $cumplecondicionproductotmp = true;
                                            }
                                        } else {

                                            if ($torneoDetalle->{"a.valor"} == $item->Evento && $cumplecondicionproductotmp) {
                                                $cumplecondicionproductotmp = true;

                                            }
                                        }

                                    }
                                    $condicionesproductotmp++;

                                }
                                if ($tipocomparacion == "OR") {
                                    if ($cumplecondicionproductotmp) {
                                        $cumplecondicionproducto = true;
                                    }
                                }elseif ($tipocomparacion == "AND") {
                                    if ($condicionesproducto == 0) {
                                        if ($cumplecondicionproductotmp) {
                                            $cumplecondicionproducto = true;
                                        }
                                    } else {
                                        if (!$cumplecondicionproductotmp) {
                                            $cumplecondicionproducto = false;
                                        }
                                    }
                                }

                                $condicionesproducto++;

                                break;
                            case "ITAINMENT5":
                                $cumplecondicionproductotmp=false;
                                $condicionesproductotmp=0;



                                foreach ($detalleSelecciones as $item) {
                                    if ($tipocomparacion == "OR") {
                                        if ($torneoDetalle->{"a.valor"} == $item->DeporteMercado) {
                                            $cumplecondicionproductotmp = true;

                                        }
                                    } elseif ($tipocomparacion == "AND") {
                                        if ($torneoDetalle->{"a.valor"} != $item->DeporteMercado) {
                                            $cumplecondicionproductotmp = false;

                                        }

                                        if ($condicionesproductotmp == 0) {
                                            if ($torneoDetalle->{"a.valor"} == $item->DeporteMercado) {
                                                $cumplecondicionproductotmp = true;
                                            }
                                        } else {
                                            if ($torneoDetalle->{"a.valor"} == $item->DeporteMercado && $cumplecondicionproductotmp) {
                                                $cumplecondicionproductotmp = true;

                                            }
                                        }

                                    }
                                    $condicionesproductotmp++;

                                }
                                if ($tipocomparacion == "OR") {
                                    if ($cumplecondicionproductotmp) {
                                        $cumplecondicionproducto = true;
                                    }
                                }elseif ($tipocomparacion == "AND") {
                                    if ($condicionesproducto == 0) {
                                        if ($cumplecondicionproductotmp) {
                                            $cumplecondicionproducto = true;
                                        }
                                    } else {
                                        if (!$cumplecondicionproductotmp) {
                                            $cumplecondicionproducto = false;
                                        }
                                    }
                                }


                                $condicionesproducto++;

                                break;

                            case "ITAINMENT82":

                                if($torneoDetalle->{"a.valor"} ==1){
                                    $sePuedeSimples =1;

                                }
                                if($torneoDetalle->{"a.valor"} ==2){
                                    $sePuedeCombinadas =1;

                                }
                                break;

                            default:
                                if (stristr($torneoDetalle->{"a.tipo"}, 'CONDGAME')) {

                                    $idGame = explode("CONDGAME", $torneoDetalle->{"a.tipo"})[1];

                                    foreach ($detalleJuegosCasino as $item) {
                                        if ($idGame == $item->Id) {
                                            $cumplecondicionproducto = true;

                                            $valorASumar = $valorASumar + (($detalleValorApuesta * $torneoDetalle->{"a.valor"}) / 100);

                                        }

                                    }

                                    $condicionesproducto++;
                                }

                                break;
                        }


                    }

                    if($sePuedeCombinadas != 0 || $sePuedeSimples != 0){

                        if(oldCount($detalleSelecciones)==1 && !$sePuedeSimples){
                            $cumplecondicion = false;
                        }

                        if(oldCount($detalleSelecciones)>1 && !$sePuedeCombinadas){
                            $cumplecondicion = false;
                        }

                        if($sePuedeCombinadas){
                            if(oldCount($detalleSelecciones)>1 && oldCount($detalleSelecciones)<$minselcount){
                                $cumplecondicion = false;

                            }
                        }
                    }else{
                        if(oldCount($detalleSelecciones)>1 && oldCount($detalleSelecciones)<$minselcount){
                            $cumplecondicion = false;

                        }
                    }

                    if ($cumplecondicion && ($cumplecondicionproducto || $condicionesproducto ==0)) {

                        $torneoid = $torneo->{"a.torneo_id"};
                        $usutorneo_id = $torneo->{"usutorneo_id"};
                        $valorapostado = $torneo->{"apostado"};
                        $valorrequerido = $torneo->{"rollower_requerido"};

                    }
                }

            }

            if ($torneoid != 0) {



                if($tipoProducto == 2){
                    $valorASumar=$detalleValorApuesta;

                }



                if (($valorapostado + $detalleValorApuesta) >= $valorrequerido) {
                    $winBonus = true;
                }

                $strSql = array();
                $contSql = 0;

                $contSql = $contSql + 1;
                $strSql[$contSql] = "UPDATE usuario_torneo SET apostado = apostado + " . ($valorASumar) . " WHERE usutorneo_id =" . $usutorneo_id;

                $contSql = $contSql + 1;
                $strSql[$contSql] = "INSERT INTO it_ticket_enc_info1 (ticket_id,tipo,valor)  VALUES ( " . $ticketId . ",'ROLLOWER'," . $usutorneo_id . ") ";


                if($ganaTorneoId == 0){
                    switch ($tiposaldo) {
                        case 0:
                            $contSql = $contSql + 1;
                            $strSql[$contSql] = "UPDATE usuario_torneo,registro SET usuario_torneo.estado = 'R',registro.creditos_base_ant=registro.creditos_base,registro.creditos_base=registro.creditos_base + usuario_torneo.valor,registro.creditos_torneo=registro.creditos_torneo - usuario_torneo.valor   WHERE  registro.usuario_id= usuario_torneo.usuario_id AND usuario_torneo.apostado >= usuario_torneo.rollower_requerido AND usuario_torneo.usutorneo_id = " . $usutorneo_id . " AND usuario_torneo.estado='A'";
                            $contSql = $contSql +1;
                            $strSql[$contSql] = "INSERT INTO torneo_log (usuario_id,tipo,valor,estado,id_externo,mandante,transaccion_id,tipotorneo_id,fecha_crea,fecha_cierre) SELECT a.usuario_id,CASE WHEN b.tipo = 2 THEN 'D' ELSE 'F' END,a.valor,'L',a.usutorneo_id,0,'0',4,now(),now()  FROM  usuario_torneo a INNER JOIN torneo_interno  b ON (b.torneo_id = a.torneo_id)  WHERE a.usutorneo_id = " . $usutorneo_id . " AND a.apostado >= a.rollower_requerido AND a.estado='R'";

                            break;

                        case 1:
                            $contSql = $contSql + 1;
                            $strSql[$contSql] = "UPDATE usuario_torneo,registro SET usuario_torneo.estado = 'R',registro.creditos_ant=registro.creditos,registro.creditos=registro.creditos + usuario_torneo.valor,registro.creditos_torneo=registro.creditos_torneo - usuario_torneo.valor   WHERE  registro.usuario_id= usuario_torneo.usuario_id AND usuario_torneo.apostado >= usuario_torneo.rollower_requerido AND usuario_torneo.usutorneo_id = " . $usutorneo_id . " AND usuario_torneo.estado='A'";
                            $estadoTorneo = 'R';
                            $contSql = $contSql +1;
                            $strSql[$contSql] = "INSERT INTO torneo_log (usuario_id,tipo,valor,estado,id_externo,mandante,transaccion_id,tipotorneo_id,fecha_crea,fecha_cierre) SELECT a.usuario_id,CASE WHEN b.tipo = 2 THEN 'D' ELSE 'F' END,a.valor,'L',a.usutorneo_id,0,'0',4,now(),now()  FROM  usuario_torneo a  INNER JOIN torneo_interno  b ON (b.torneo_id = a.torneo_id)  WHERE a.usutorneo_id = " . $usutorneo_id . " AND a.apostado >= a.rollower_requerido AND a.estado='R'";

                            break;

                        case 2:

                            $contSql = $contSql + 1;
                            $strSql[$contSql] = "UPDATE usuario_torneo,registro SET usuario_torneo.estado = 'R',registro.saldo_especial=registro.saldo_especial + usuario_torneo.valor,registro.creditos_torneo=registro.creditos_torneo - usuario_torneo.valor   WHERE  registro.usuario_id= usuario_torneo.usuario_id AND usuario_torneo.apostado >= usuario_torneo.rollower_requerido AND usuario_torneo.usutorneo_id = " . $usutorneo_id . " AND usuario_torneo.estado='A'";
                            $estadoTorneo = 'R';
                            $contSql = $contSql +1;
                            $strSql[$contSql] = "INSERT INTO torneo_log (usuario_id,tipo,valor,estado,id_externo,mandante,transaccion_id,tipotorneo_id,fecha_crea,fecha_cierre) SELECT a.usuario_id,CASE WHEN b.tipo = 2 THEN 'D' ELSE 'F' END,a.valor,'L',a.usutorneo_id,0,'0',4,now(),now()  FROM  usuario_torneo a  INNER JOIN torneo_interno  b ON (b.torneo_id = a.torneo_id)  WHERE a.usutorneo_id = " . $usutorneo_id . " AND a.apostado >= a.rollower_requerido AND a.estado='R'";

                            break;

                    }


                }




                $respuesta["WinBonus"] = true;
                $respuesta["Torneo"] = $torneoid;
                $respuesta["UsuarioTorneo"] = $usutorneo_id;
                $respuesta["queries"] = $strSql;

                foreach ($respuesta["queries"] as $querie){
                    $this->execQuery($querie);
                }

                if($ganaTorneoId != 0) {
                    $sqlTorneo2 = "select a.usuario_id,a.usutorneo_id,a.torneo_id,a.apostado,a.rollower_requerido,a.fecha_crea,torneo_interno.condicional,torneo_interno.tipo from usuario_torneo a INNER JOIN torneo_interno ON (torneo_interno.torneo_id = a.torneo_id ) where  a.estado='A' AND (torneo_interno.tipo = 2 OR torneo_interno.tipo = 3) AND a.usutorneo_id='" . $usutorneo_id . "'";

                    $torneosDisponibles2 = $this->execQuery($sqlTorneo2);

                    $rollower_requerido=$torneosDisponibles2[0]->rollower_requerido;
                    $apostado=$torneosDisponibles2[0]->apostado;

                    if($apostado >= $rollower_requerido){
                        try{
                            $usuarioSql = "select ciudad.ciudad_id,ciudad.depto_id,pais.pais_id FROM registro
  INNER JOIN ciudad ON (ciudad.ciudad_id = registro.ciudad_id)
  INNER JOIN departamento ON (ciudad.depto_id = departamento.depto_id)
  INNER JOIN pais ON (pais.pais_id = departamento.pais_id) WHERE registro.usuario_id='" . $torneosDisponibles2[0]->usuario_id . "'";

                            $Usuario = $this->execQuery($usuarioSql);

                            $dataUsuario = $Usuario;
                            $detalles = array(
                                "PaisUSER" => $dataUsuario['pais_id'],
                                "DepartamentoUSER" => $dataUsuario['depto_id'],
                                "CiudadUSER" => $dataUsuario['ciudad_id'],

                            );
                            $detalles=json_decode(json_encode($detalles));

                            $respuesta2=$this->agregarTorneoFree($ganaTorneoId,$torneosDisponibles2[0]->usuario_id,"0",$detalles,true);

                            $contSql = 1;
                            $strSql=array();
                            $strSql[$contSql] = "UPDATE usuario_torneo SET usuario_torneo.estado = 'R'   WHERE usuario_torneo.usutorneo_id = " . $usutorneo_id . " AND usuario_torneo.estado='A'";

                            //  $contSql = $contSql +1;
                            //   $strSql[$contSql] = "INSERT INTO torneo_log (usuario_id,tipo,valor,estado,id_externo,mandante,transaccion_id,tipotorneo_id) SELECT a.usuario_id,'D',a.valor,'L',a.usutorneo_id,0,'0',4  FROM  usuario_torneo a   WHERE a.usutorneo_id = " . $usutorneo_id . " AND a.apostado >= a.rollower_requerido";
                        }catch (Exception $e){

                        }



                        $respuesta["WinBonus"] = true;
                        $respuesta["Torneo"] = $torneoid;
                        $respuesta["UsuarioTorneo"] = $usutorneo_id;
                        $respuesta["queries"] = $strSql;

                        foreach ($respuesta["queries"] as $querie){
                            $this->execQuery($querie);
                        }
                    }

                }

            }

        }



        return json_decode(json_encode($respuesta));

    }

    /**
    * Verficiar rollower en el torneo
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
    public function verificarTorneoUsuarioPremio($usuarioId, $detalles,$tipoProducto,$ticketId){

        switch($tipoProducto){


            case "CASINO":

                $TransaccionApi = new TransaccionApi($ticketId);
                //$UsuarioMandante = new UsuarioMandante($TransaccionApi->usuarioId);


                $rules = [];

                array_push($rules, array("field" => "transaccion_api.identificador", "data" => "$TransaccionApi->identificador", "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                $json = json_encode($filtro);



                $TransjuegoInfo = new TransjuegoInfo("","","","","",$TransaccionApi->identificador);


                if($TransjuegoInfo->transjuegoinfoId >0){

                    $UsuarioTorneo = new UsuarioTorneo($TransjuegoInfo->descripcion);

                    $UsuarioTorneo->setValorPremio('valor_premio + ' . $TransaccionApi->getValor());

                    $UsuarioTorneoMySqlDAO = new UsuarioTorneoMySqlDAO();
                    $UsuarioTorneoMySqlDAO->update($UsuarioTorneo);
                    $UsuarioTorneoMySqlDAO->getTransaction()->commit();
                }


                break;


            case "LIVECASINO":

                $TransaccionApi = new TransaccionApi($ticketId);
                //$UsuarioMandante = new UsuarioMandante($TransaccionApi->usuarioId);


                $rules = [];

                array_push($rules, array("field" => "transaccion_api.identificador", "data" => "$TransaccionApi->identificador", "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                $json = json_encode($filtro);



                $TransjuegoInfo = new TransjuegoInfo("","","","","",$TransaccionApi->identificador);


                if($TransjuegoInfo->transjuegoinfoId >0){

                    $UsuarioTorneo = new UsuarioTorneo($TransjuegoInfo->descripcion);

                    $UsuarioTorneo->setValorPremio('valor_premio + ' . $TransaccionApi->getValor());

                    $UsuarioTorneoMySqlDAO = new UsuarioTorneoMySqlDAO();
                    $UsuarioTorneoMySqlDAO->update($UsuarioTorneo);
                    $UsuarioTorneoMySqlDAO->getTransaction()->commit();
                }


                break;


            case "VIRTUAL":

                $TransaccionApi = new TransaccionApi($ticketId);
                //$UsuarioMandante = new UsuarioMandante($TransaccionApi->usuarioId);


                $rules = [];

                array_push($rules, array("field" => "transaccion_api.identificador", "data" => "$TransaccionApi->identificador", "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                $json = json_encode($filtro);



                $TransjuegoInfo = new TransjuegoInfo("","","","","",$TransaccionApi->identificador);


                if($TransjuegoInfo->transjuegoinfoId >0){

                    $UsuarioTorneo = new UsuarioTorneo($TransjuegoInfo->descripcion);

                    $UsuarioTorneo->setValorPremio('valor_premio + ' . $TransaccionApi->getValor());

                    $UsuarioTorneoMySqlDAO = new UsuarioTorneoMySqlDAO();
                    $UsuarioTorneoMySqlDAO->update($UsuarioTorneo);
                    $UsuarioTorneoMySqlDAO->getTransaction()->commit();
                }


                break;


        }
    }

    /**
    * Verficiar torneo
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
    public function verificarTorneoUsuario($usuarioId, $detalles,$tipoProducto,$ticketId,$ticketId2=''){

        switch($tipoProducto){


            case "CASINO":

                $valorTicket=0;
                $IdProducto=0;
                if($ticketId2 != ''){

                    $TransjuegoLog = new TransjuegoLog($ticketId2);
                    $TransaccionJuego = new TransaccionJuego($TransjuegoLog->getTransjuegoId());
                    $UsuarioMandante = new UsuarioMandante($TransaccionJuego->usuarioId);
                    $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

                    $ProductoMandante = new ProductoMandante('','',$TransaccionJuego->productoId);


                    if($ProductoMandante->paisId != $UsuarioMandante->paisId){
                        $ProductoMandante = new ProductoMandante($ProductoMandante->productoId,$UsuarioMandante->mandante,'',$UsuarioMandante->paisId);
                    }


                    $Producto = new Producto($ProductoMandante->productoId);
                    $valorTicket =$TransjuegoLog->getValor();
                    $IdProducto = $Producto->getProductoId();
                    $rules = [];

                    array_push($rules, array("field" => "usuario_torneo.usuario_id", "data" => "$TransaccionJuego->usuarioId", "op" => "eq"));
                    array_push($rules, array("field" => "torneo_interno.estado", "data" => "A", "op" => "eq"));
                    array_push($rules, array("field" => "torneo_interno.fecha_inicio", "data" => "$TransjuegoLog->fechaCrea" , "op" => "le"));
                    array_push($rules, array("field" => "torneo_interno.fecha_fin", "data" => "$TransjuegoLog->fechaCrea", "op" => "ge"));
                    array_push($rules, array("field" => "torneo_interno.tipo", "data" => "2", "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                }else{

                    $TransaccionApi = new TransaccionApi($ticketId);
                    $UsuarioMandante = new UsuarioMandante($TransaccionApi->usuarioId);
                    $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

                    $ProductoMandante = new ProductoMandante('','',$TransaccionApi->productoId);

                    if($ProductoMandante->paisId != $UsuarioMandante->paisId){
                        $ProductoMandante = new ProductoMandante($ProductoMandante->productoId,$UsuarioMandante->mandante,'',$UsuarioMandante->paisId);
                    }

                    $Producto = new Producto($ProductoMandante->productoId);

                    $valorTicket =$TransaccionApi->getValor();
                    $IdProducto = $Producto->getProductoId();
                    $rules = [];

                    array_push($rules, array("field" => "usuario_torneo.usuario_id", "data" => "$TransaccionApi->usuarioId", "op" => "eq"));
                    array_push($rules, array("field" => "torneo_interno.estado", "data" => "A", "op" => "eq"));
                    array_push($rules, array("field" => "torneo_interno.fecha_inicio", "data" => "$TransaccionApi->fechaCrea" , "op" => "le"));
                    array_push($rules, array("field" => "torneo_interno.fecha_fin", "data" => "$TransaccionApi->fechaCrea", "op" => "ge"));
                    array_push($rules, array("field" => "torneo_interno.tipo", "data" => "2", "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                }


                $json = json_encode($filtro);


                $UsuarioTorneo = new UsuarioTorneo();
                $data = $UsuarioTorneo->getUsuarioTorneosCustomWithoutPosition("usuario_torneo.*,usuario_mandante.nombres,torneo_interno.*", "usuario_torneo.valor", "DESC", 0, 100, $json, true, '');


                $data = json_decode($data);

                $final = [];

                $pos = 1;
                $torneosAnalizados='';

                $cumpleCondicion=false;
                foreach ($data->data as $key => $value) {


                    $array = [];
                    $array["Position"] = $pos;
                    $array["Id"] = $value->{"usuario_torneo.usutorneo_id"};

                    $torneosAnalizados = $torneosAnalizados . $value->{"usuario_torneo.torneo_id"} . ",";
                    $TorneoInterno = new TorneoInterno();
                    $TorneoDetalle = new TorneoDetalle();

                    //$bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
                    //$bonos = json_decode($bonos);


                    $rules = [];

                    array_push($rules, array("field" => "torneo_interno.torneo_id", "data" => $value->{"usuario_torneo.torneo_id"}, "op" => "eq"));
                    array_push($rules, array("field" => "torneo_interno.estado", "data" => "A", "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $json = json_encode($filtro);

                    $torneodetalles = $TorneoDetalle->getTorneoDetallesCustom(" torneo_detalle.*,torneo_interno.* ", "torneo_interno.torneo_id", "asc", 0, 1000, $json, TRUE);

                    $torneodetalles = json_decode($torneodetalles);

                    $final = [];

                    $creditosConvert=0;

                    $cumpleCondicionPais=false;
                    $cumpleCondicionCont=0;

                    $condicionesProducto=0;

                    foreach ($torneodetalles->data as $key2 => $value2) {

                        switch ($value2->{"torneo_detalle.tipo"}){
                            case "RANK":
                                if($value2->{"torneo_detalle.moneda"} == $UsuarioMandante->moneda){
                                    if($valorTicket >= $value2->{"torneo_detalle.valor"}){
                                        if($valorTicket <= $value2->{"torneo_detalle.valor2"}){
                                            $creditosConvert=$value2->{"torneo_detalle.valor3"};
                                        }

                                    }
                                }

                                break;


                            case "CONDPAISUSER":

                                if($value2->{"torneo_detalle.valor"} ==$Usuario->paisId){
                                    $cumpleCondicionPais=true;
                                }
                                $cumpleCondicionCont++;

                                break;

                            default:

                                if (stristr($value2->{"torneo_detalle.tipo"}, 'CONDGAME')) {

                                    $idGame = explode("CONDGAME", $value2->{"torneo_detalle.tipo"})[1];

                                    if($ProductoMandante->prodmandanteId == $idGame){
                                        $cumpleCondicion=true;
                                    }else{

                                    }
                                    $condicionesProducto++;
                                }

                                if (stristr($value2->{"torneo_detalle.tipo"}, 'CONDPROVIDER')) {

                                    $idProvider = explode("CONDPROVIDER", $value2->{"torneo_detalle.tipo"})[1];

                                    if($Producto->proveedorId == $idProvider){
                                        $cumpleCondicion=true;
                                    }
                                    $condicionesProducto++;
                                }

                                if (stristr($value2->{"torneo_detalle.tipo"}, 'CONDSUBPROVIDER')) {

                                    $idProvider = explode("CONDSUBPROVIDER", $value2->{"torneo_detalle.tipo"})[1];

                                    if($Producto->subproveedorId == $idProvider){
                                        $cumpleCondicion=true;
                                    }
                                    $condicionesProducto++;
                                }

                                break;
                        }

                    }

                    if($condicionesProducto==0 && !$cumpleCondicion){
                        $cumpleCondicion=true;
                    }

                    if(!$cumpleCondicionPais && $cumpleCondicionCont>0){
                        $cumpleCondicion=false;
                    }

                    if($cumpleCondicion) {
                        if ($creditosConvert == 0) {
                            $cumpleCondicion = false;
                        }
                    }
                    if($cumpleCondicion){
                        print_r('cumpleCondicion');
                        $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();

                        $UsuarioTorneo = new UsuarioTorneo( $value->{"usuario_torneo.usutorneo_id"});
                        $UsuarioTorneo->valor=$UsuarioTorneo->valor + $creditosConvert;
                        $UsuarioTorneo->valorBase=($UsuarioTorneo->valorBase + $valorTicket);

                        if($TransaccionJuego == null){
                            $TransaccionJuego = new TransaccionJuego("", $TransaccionApi->getIdentificador());

                        }

                        $UsuarioTorneoMySqlDAO = new UsuarioTorneoMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                        $UsuarioTorneoMySqlDAO->update($UsuarioTorneo);

                        $TransjuegoInfo = new TransjuegoInfo();
                        $TransjuegoInfo->productoId=$ProductoMandante->prodmandanteId;
                        if($TransjuegoLog != null){
                            $TransjuegoInfo->transaccionId=$TransjuegoLog->transaccionId;

                        }else{
                            $TransjuegoInfo->transaccionId=$TransaccionApi->transaccionId;

                        }
                        $TransjuegoInfo->tipo="TORNEO";
                        $TransjuegoInfo->descripcion=$value->{"usuario_torneo.usutorneo_id"};
                        $TransjuegoInfo->valor=$creditosConvert;
                        if($TransaccionApi != null){
                            $TransjuegoInfo->transapiId=$TransaccionApi->transapiId;

                        }else{
                            $TransjuegoInfo->transapiId=$TransjuegoLog->transjuegologId;
                        }
                        $TransjuegoInfo->usucreaId=0;
                        $TransjuegoInfo->usumodifId=0;
                        $TransjuegoInfo->identificador=$TransaccionJuego->ticketId;
                        $TransjuegoInfo->transjuegoId=$TransaccionJuego->transjuegoId;

                        $title = '';
                        $messageBody = '';
                        $tournamentName = $value->{"torneo_interno.nombre"};

                        switch(strtolower($Usuario->idioma)) {
                            case 'es':
                                $title = 'Notificacion';
                                $messageBody = "¡ Bien :thumbsup: ! Sumaste {$creditosConvert} puntos en {$tournamentName} :clap:";
                                break;
                            case 'en':
                                $title = 'Notification';
                                $messageBody = "¡ Great :thumbsup: ! You added{$creditosConvert} points in the {$tournamentName} :clap:";
                                break;
                            case 'pt':
                                $title = 'Notificação';
                                $messageBody = "Olha só! :thumbsup: Você somou {$creditosConvert} pontos em {$tournamentName} :clap:";
                                break;
                        }

                        $UsuarioMensaje = new UsuarioMensaje();
                        $UsuarioMensaje->usufromId = 0;
                        $UsuarioMensaje->usutoId = $UsuarioTorneo->getUsuarioId();
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


                        $mensajesRecibidos=[];
                        $array = [];

                        $array["body"] = $messageBody;

                        array_push($mensajesRecibidos, $array);
                        $data = array();
                        $data["messages"] = $mensajesRecibidos;
                        //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

                       /* $UsuarioSession = new UsuarioSession();
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

                        if(in_array($UsuarioMandante->mandante,array('0',8,6))){

                            $dataSend = $data;
                            $WebsocketUsuario = new WebsocketUsuario('','');
                            $WebsocketUsuario->sendWSPieSocket($UsuarioMandante,$dataSend);

                        }

                        break;
                    }


                }

                if( !$cumpleCondicion) {

                    if($torneosAnalizados != ""){
                        $torneosAnalizados=$torneosAnalizados .'0';
                    }

                    $TorneoInterno = new TorneoInterno();

                    $rules = [];

                    if($torneosAnalizados != ''){
                        array_push($rules, array("field" => "torneo_interno.torneo_id", "data" => "$torneosAnalizados", "op" => "ni"));
                    }
                    if($TransjuegoLog != null){

                        array_push($rules, array("field" => "torneo_interno.estado", "data" => "A", "op" => "eq"));
                        array_push($rules, array("field" => "torneo_interno.fecha_inicio", "data" => "$TransjuegoLog->fechaCrea" , "op" => "le"));
                        array_push($rules, array("field" => "torneo_interno.fecha_fin", "data" => "$TransjuegoLog->fechaCrea", "op" => "ge"));
                        array_push($rules, array("field" => "torneo_interno.tipo", "data" => "2", "op" => "eq"));
                        array_push($rules, array("field" => "torneo_interno.mandante", "data" => $UsuarioMandante->mandante, "op" => "eq"));

                    }else{

                        array_push($rules, array("field" => "torneo_interno.estado", "data" => "A", "op" => "eq"));
                        array_push($rules, array("field" => "torneo_interno.fecha_inicio", "data" => "$TransaccionApi->fechaCrea" , "op" => "le"));
                        array_push($rules, array("field" => "torneo_interno.fecha_fin", "data" => "$TransaccionApi->fechaCrea", "op" => "ge"));
                        array_push($rules, array("field" => "torneo_interno.tipo", "data" => "2", "op" => "eq"));
                        array_push($rules, array("field" => "torneo_interno.mandante", "data" => $UsuarioMandante->mandante, "op" => "eq"));

                    }

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $json = json_encode($filtro);


                    $data = $TorneoInterno->getTorneosCustom("torneo_interno.*", "torneo_interno.orden", "ASC", 0, 1000, $json, true, '');

                    print_r(" data2 ");
                    print_r($data);

                    $data = json_decode($data);

                    $final = [];

                    $pos = 1;
                    $torneosAnalizados='';

                    foreach ($data->data as $key => $value) {
                        print_r($value);


                        $TorneoDetalle = new TorneoDetalle();

                        //$bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
                        //$bonos = json_decode($bonos);


                        $rules = [];

                        array_push($rules, array("field" => "torneo_interno.torneo_id", "data" => $value->{"torneo_interno.torneo_id"}, "op" => "eq"));
                        array_push($rules, array("field" => "torneo_interno.estado", "data" => "A", "op" => "eq"));

                        $filtro = array("rules" => $rules, "groupOp" => "AND");

                        $json = json_encode($filtro);

                        $torneodetalles = $TorneoDetalle->getTorneoDetallesCustom(" torneo_detalle.*,torneo_interno.* ", "torneo_interno.torneo_id", "asc", 0, 1000, $json, TRUE);

                        $torneodetalles = json_decode($torneodetalles);

                        $final = [];

                        $creditosConvert=0;

                        $cumpleCondicion=false;
                        $needSubscribe=false;

                        $cumpleCondicionPais=false;
                        $cumpleCondicionCont=0;

                        $condicionesProducto=0;

                        foreach ($torneodetalles->data as $key2 => $value2) {

                            switch ($value2->{"torneo_detalle.tipo"}){
                                case "RANK":
                                    if($value2->{"torneo_detalle.moneda"} == $UsuarioMandante->moneda){
                                        if($valorTicket >= $value2->{"torneo_detalle.valor"}){
                                            if($valorTicket <= $value2->{"torneo_detalle.valor2"}){
                                                $creditosConvert=$value2->{"torneo_detalle.valor3"};
                                            }

                                        }
                                    }

                                    break;

                                case "USERSUBSCRIBE":

                                    if($value2->{"torneo_detalle.valor"} ==0){

                                    }else{
                                        $needSubscribe=true;
                                    }

                                    break;

                                case "VISIBILIDAD":

                                    if($value2->{"torneo_detalle.valor"} ==1){
                                        $needSubscribe=true;

                                    }else{
                                    }

                                    break;


                                case "CONDPAISUSER":

                                    if($value2->{"torneo_detalle.valor"} ==$Usuario->paisId){
                                        $cumpleCondicionPais=true;
                                    }
                                    $cumpleCondicionCont++;

                                    break;

                                default:

                                    if (stristr($value2->{"torneo_detalle.tipo"}, 'CONDGAME')) {

                                        $idGame = explode("CONDGAME", $value2->{"torneo_detalle.tipo"})[1];

                                        if($ProductoMandante->prodmandanteId == $idGame){
                                            $cumpleCondicion=true;
                                        }else{

                                        }
                                        $condicionesProducto++;
                                    }

                                    if (stristr($value2->{"torneo_detalle.tipo"}, 'CONDPROVIDER')) {

                                        $idProvider = explode("CONDPROVIDER", $value2->{"torneo_detalle.tipo"})[1];

                                        if($Producto->proveedorId == $idProvider){
                                            $cumpleCondicion=true;
                                        }
                                        $condicionesProducto++;
                                    }

                                    if (stristr($value2->{"torneo_detalle.tipo"}, 'CONDSUBPROVIDER')) {

                                        $idProvider = explode("CONDSUBPROVIDER", $value2->{"torneo_detalle.tipo"})[1];

                                        if($Producto->subproveedorId == $idProvider){
                                            $cumpleCondicion=true;
                                        }
                                        $condicionesProducto++;
                                    }

                                    break;
                            }

                        }

                        if($condicionesProducto==0 && !$cumpleCondicion){
                            $cumpleCondicion=true;
                        }

                        if(!$cumpleCondicionPais && $cumpleCondicionCont>0){

                            $cumpleCondicion=false;
                        }


                        if($cumpleCondicion) {
                            if ($creditosConvert == 0) {
                                $cumpleCondicion = false;
                            }
                        }

                        if($cumpleCondicion && !$needSubscribe && $creditosConvert>0){
                            print_r("CUMPLECONDICION");

                            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();

                            $UsuarioTorneo = new UsuarioTorneo();
                            $UsuarioTorneo->usuarioId=$UsuarioMandante->usumandanteId;
                            $UsuarioTorneo->torneoId=$value->{"torneo_interno.torneo_id"};
                            $UsuarioTorneo->valor=0;
                            $UsuarioTorneo->posicion=0;
                            $UsuarioTorneo->valorBase=0;
                            $UsuarioTorneo->usucreaId=0;
                            $UsuarioTorneo->usumodifId=0;
                            $UsuarioTorneo->estado="A";
                            $UsuarioTorneo->errorId=0;
                            $UsuarioTorneo->idExterno=0;
                            $UsuarioTorneo->mandante=0;
                            $UsuarioTorneo->version=0;
                            $UsuarioTorneo->apostado=0;
                            $UsuarioTorneo->codigo=0;
                            $UsuarioTorneo->externoId=0;
                            $UsuarioTorneo->valor=$UsuarioTorneo->valor + $creditosConvert;
                            $UsuarioTorneo->valorBase=($UsuarioTorneo->valorBase + $valorTicket);

                            $UsuarioTorneoMySqlDAO = new UsuarioTorneoMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                            $idUsuTorneo=$UsuarioTorneoMySqlDAO->insert($UsuarioTorneo);

                            $TransjuegoInfo = new TransjuegoInfo();
                            $TransjuegoInfo->productoId=$ProductoMandante->prodmandanteId;

                            if($TransjuegoLog != null){
                                $TransjuegoInfo->transaccionId=$TransjuegoLog->transaccionId;

                            }else{
                                $TransjuegoInfo->transaccionId=$TransaccionApi->transaccionId;

                            }

                            $TransjuegoInfo->tipo="TORNEO";
                            $TransjuegoInfo->descripcion=$idUsuTorneo;
                            $TransjuegoInfo->valor=$creditosConvert;

                            if($TransaccionApi != null){
                                $TransjuegoInfo->transapiId=$TransaccionApi->transapiId;

                            }else{
                                $TransjuegoInfo->transapiId=$TransjuegoLog->transjuegologId;
                            }
                            $TransjuegoInfo->usucreaId=0;
                            $TransjuegoInfo->usumodifId=0;

                            $title = '';
                            $messageBody = '';
                            $tournamentName = $value->{"torneo_interno.nombre"};

                            switch(strtolower($Usuario->idioma)) {
                                case 'es':
                                    $title = 'Notificacion';
                                    $messageBody = "¡ Bien :thumbsup: ! Sumaste {$creditosConvert} puntos en {$tournamentName} :clap:";
                                    break;
                                case 'en':
                                    $title = 'Notification';
                                    $messageBody = "¡ Great :thumbsup: ! You added{$creditosConvert} points in the {$tournamentName} :clap:";
                                    break;
                                case 'pt':
                                    $title = 'Notificação';
                                    $messageBody = "Olha só! :thumbsup: Você somou {$creditosConvert} pontos em {$tournamentName} :clap:";
                                    break;
                            }

                            $UsuarioMensaje = new UsuarioMensaje();
                            $UsuarioMensaje->usufromId = 0;
                            $UsuarioMensaje->usutoId = $UsuarioTorneo->getUsuarioId();
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


                            $mensajesRecibidos=[];
                            $array = [];

                            $array["body"] = $messageBody;

                            array_push($mensajesRecibidos, $array);
                            $data = array();
                            $data["messages"] = $mensajesRecibidos;
                            //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

                           /* $UsuarioSession = new UsuarioSession();
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

                            if(in_array($UsuarioMandante->mandante,array('0',8,6))){

                                $dataSend = $data;
                                $WebsocketUsuario = new WebsocketUsuario('','');
                                $WebsocketUsuario->sendWSPieSocket($UsuarioMandante,$dataSend);

                            }
                            break;
                        }


                    }


                }



                    break;


            case "LIVECASINO":

                $valorTicket=0;
                $IdProducto=0;
                if($ticketId2 != ''){

                    $TransjuegoLog = new TransjuegoLog($ticketId2);
                    $TransaccionJuego = new TransaccionJuego($TransjuegoLog->getTransjuegoId());
                    $UsuarioMandante = new UsuarioMandante($TransaccionJuego->usuarioId);
                    $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

                    $ProductoMandante = new ProductoMandante('','',$TransaccionJuego->productoId);
                    $Producto = new Producto($ProductoMandante->productoId);
                    $valorTicket =$TransjuegoLog->getValor();
                    $IdProducto = $Producto->getProductoId();
                    $rules = [];

                    array_push($rules, array("field" => "usuario_torneo.usuario_id", "data" => "$TransaccionJuego->usuarioId", "op" => "eq"));
                    array_push($rules, array("field" => "torneo_interno.estado", "data" => "A", "op" => "eq"));
                    array_push($rules, array("field" => "torneo_interno.fecha_inicio", "data" => "$TransjuegoLog->fechaCrea" , "op" => "le"));
                    array_push($rules, array("field" => "torneo_interno.fecha_fin", "data" => "$TransjuegoLog->fechaCrea", "op" => "ge"));
                    array_push($rules, array("field" => "torneo_interno.tipo", "data" => "3", "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                }else{

                    $TransaccionApi = new TransaccionApi($ticketId);
                    $UsuarioMandante = new UsuarioMandante($TransaccionApi->usuarioId);
                    $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

                    $ProductoMandante = new ProductoMandante('','',$TransaccionApi->productoId);
                    $Producto = new Producto($ProductoMandante->productoId);

                    $valorTicket =$TransaccionApi->getValor();
                    $IdProducto = $Producto->getProductoId();
                    $rules = [];

                    array_push($rules, array("field" => "usuario_torneo.usuario_id", "data" => "$TransaccionApi->usuarioId", "op" => "eq"));
                    array_push($rules, array("field" => "torneo_interno.estado", "data" => "A", "op" => "eq"));
                    array_push($rules, array("field" => "torneo_interno.fecha_inicio", "data" => "$TransaccionApi->fechaCrea" , "op" => "le"));
                    array_push($rules, array("field" => "torneo_interno.fecha_fin", "data" => "$TransaccionApi->fechaCrea", "op" => "ge"));
                    array_push($rules, array("field" => "torneo_interno.tipo", "data" => "3", "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                }


                $json = json_encode($filtro);


                $UsuarioTorneo = new UsuarioTorneo();
                $data = $UsuarioTorneo->getUsuarioTorneosCustomWithoutPosition("usuario_torneo.*,usuario_mandante.nombres,torneo_interno.*", "usuario_torneo.valor", "DESC", 0, 100, $json, true, '');

                print_r($data);

                $data = json_decode($data);

                $final = [];

                $pos = 1;
                $torneosAnalizados='';

                $cumpleCondicion=false;
                foreach ($data->data as $key => $value) {

                    $array = [];
                    $array["Position"] = $pos;
                    $array["Id"] = $value->{"usuario_torneo.usutorneo_id"};

                    $torneosAnalizados = $torneosAnalizados . $value->{"usuario_torneo.torneo_id"} . ",";
                    $TorneoInterno = new TorneoInterno();
                    $TorneoDetalle = new TorneoDetalle();

                    //$bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
                    //$bonos = json_decode($bonos);


                    $rules = [];

                    array_push($rules, array("field" => "torneo_interno.torneo_id", "data" => $value->{"usuario_torneo.torneo_id"}, "op" => "eq"));
                    array_push($rules, array("field" => "torneo_interno.estado", "data" => "A", "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $json = json_encode($filtro);

                    $torneodetalles = $TorneoDetalle->getTorneoDetallesCustom(" torneo_detalle.*,torneo_interno.* ", "torneo_interno.torneo_id", "asc", 0, 1000, $json, TRUE);

                    $torneodetalles = json_decode($torneodetalles);

                    $final = [];

                    $creditosConvert=0;

                    $cumpleCondicionPais=false;
                    $cumpleCondicionCont=0;

                    $condicionesProducto=0;

                    foreach ($torneodetalles->data as $key2 => $value2) {

                        switch ($value2->{"torneo_detalle.tipo"}){
                            case "RANK":
                                if($value2->{"torneo_detalle.moneda"} == $UsuarioMandante->moneda){
                                    if($valorTicket >= $value2->{"torneo_detalle.valor"}){
                                        if($valorTicket <= $value2->{"torneo_detalle.valor2"}){
                                            $creditosConvert=$value2->{"torneo_detalle.valor3"};
                                        }

                                    }
                                }

                                break;


                            case "CONDPAISUSER":

                                if($value2->{"torneo_detalle.valor"} ==$Usuario->paisId){
                                    $cumpleCondicionPais=true;
                                }
                                $cumpleCondicionCont++;

                                break;

                            default:

                                if (stristr($value2->{"torneo_detalle.tipo"}, 'CONDGAME')) {

                                    $idGame = explode("CONDGAME", $value2->{"torneo_detalle.tipo"})[1];

                                    if($ProductoMandante->prodmandanteId == $idGame){
                                        $cumpleCondicion=true;
                                    }else{


                                    }
                                    $condicionesProducto++;
                                }

                                if (stristr($value2->{"torneo_detalle.tipo"}, 'CONDPROVIDER')) {

                                    $idProvider = explode("CONDPROVIDER", $value2->{"torneo_detalle.tipo"})[1];

                                    if($Producto->proveedorId == $idProvider){
                                        $cumpleCondicion=true;
                                    }
                                    $condicionesProducto++;
                                }

                                if (stristr($value2->{"torneo_detalle.tipo"}, 'CONDSUBPROVIDER')) {

                                    $idProvider = explode("CONDSUBPROVIDER", $value2->{"torneo_detalle.tipo"})[1];

                                    if($Producto->subproveedorId == $idProvider){
                                        $cumpleCondicion=true;
                                    }
                                    $condicionesProducto++;
                                }

                                break;
                        }

                    }

                    if($condicionesProducto==0 && !$cumpleCondicion){
                        $cumpleCondicion=true;
                    }

                    if(!$cumpleCondicionPais && $cumpleCondicionCont>0){
                        $cumpleCondicion=false;
                    }

                    if($cumpleCondicion) {
                        if ($creditosConvert == 0) {
                            $cumpleCondicion = false;
                        }
                    }
                    if($cumpleCondicion){
                        $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();

                        $UsuarioTorneo = new UsuarioTorneo( $value->{"usuario_torneo.usutorneo_id"});
                        $UsuarioTorneo->valor=$UsuarioTorneo->valor + $creditosConvert;
                        $UsuarioTorneo->valorBase=($UsuarioTorneo->valorBase + $valorTicket);

                        if($TransaccionJuego == null){
                            $TransaccionJuego = new TransaccionJuego("", $TransaccionApi->getIdentificador());

                        }

                        $UsuarioTorneoMySqlDAO = new UsuarioTorneoMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                        $UsuarioTorneoMySqlDAO->update($UsuarioTorneo);

                        $TransjuegoInfo = new TransjuegoInfo();
                        $TransjuegoInfo->productoId=$ProductoMandante->prodmandanteId;
                        if($TransjuegoLog != null){
                            $TransjuegoInfo->transaccionId=$TransjuegoLog->transaccionId;

                        }else{
                            $TransjuegoInfo->transaccionId=$TransaccionApi->transaccionId;

                        }
                        $TransjuegoInfo->tipo="TORNEO";
                        $TransjuegoInfo->descripcion=$value->{"usuario_torneo.usutorneo_id"};
                        $TransjuegoInfo->valor=$creditosConvert;
                        if($TransaccionApi != null){
                            $TransjuegoInfo->transapiId=$TransaccionApi->transapiId;

                        }else{
                            $TransjuegoInfo->transapiId=$TransjuegoLog->transjuegologId;
                        }
                        $TransjuegoInfo->usucreaId=0;
                        $TransjuegoInfo->usumodifId=0;
                        $TransjuegoInfo->identificador=$TransaccionJuego->ticketId;
                        $TransjuegoInfo->transjuegoId=$TransaccionJuego->transjuegoId;

                        $title = '';
                        $messageBody = '';
                        $tournamentName = $value->{"torneo_interno.nombre"};

                        switch(strtolower($Usuario->idioma)) {
                            case 'es':
                                $title = 'Notificacion';
                                $messageBody = "¡ Bien :thumbsup: ! Sumaste {$creditosConvert} puntos en {$tournamentName} :clap:";
                                break;
                            case 'en':
                                $title = 'Notification';
                                $messageBody = "¡ Great :thumbsup: ! You added{$creditosConvert} points in the {$tournamentName} :clap:";
                                break;
                            case 'pt':
                                $title = 'Notificação';
                                $messageBody = "Olha só! :thumbsup: Você somou {$creditosConvert} pontos em {$tournamentName} :clap:";
                                break;
                        }

                        $UsuarioMensaje = new UsuarioMensaje();
                        $UsuarioMensaje->usufromId = 0;
                        $UsuarioMensaje->usutoId = $UsuarioTorneo->getUsuarioId();
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


                        $mensajesRecibidos=[];
                        $array = [];

                        $array["body"] = $messageBody;

                        array_push($mensajesRecibidos, $array);
                        $data = array();
                        $data["messages"] = $mensajesRecibidos;
                        //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

                        /* $UsuarioSession = new UsuarioSession();
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

                        if(in_array($UsuarioMandante->mandante,array('0',8,6))){

                            $dataSend = $data;
                            $WebsocketUsuario = new WebsocketUsuario('','');
                            $WebsocketUsuario->sendWSPieSocket($UsuarioMandante,$dataSend);

                        }

                        break;
                    }


                }

                if( !$cumpleCondicion) {

                    if($torneosAnalizados != ""){
                        $torneosAnalizados=$torneosAnalizados .'0';
                    }

                    $TorneoInterno = new TorneoInterno();

                    $rules = [];

                    if($torneosAnalizados != ''){
                        array_push($rules, array("field" => "torneo_interno.torneo_id", "data" => "$torneosAnalizados", "op" => "ni"));
                    }
                    if($TransjuegoLog != null){

                        array_push($rules, array("field" => "torneo_interno.estado", "data" => "A", "op" => "eq"));
                        array_push($rules, array("field" => "torneo_interno.fecha_inicio", "data" => "$TransjuegoLog->fechaCrea" , "op" => "le"));
                        array_push($rules, array("field" => "torneo_interno.fecha_fin", "data" => "$TransjuegoLog->fechaCrea", "op" => "ge"));
                        array_push($rules, array("field" => "torneo_interno.tipo", "data" => "3", "op" => "eq"));
                        array_push($rules, array("field" => "torneo_interno.mandante", "data" => $UsuarioMandante->mandante, "op" => "eq"));

                    }else{

                        array_push($rules, array("field" => "torneo_interno.estado", "data" => "A", "op" => "eq"));
                        array_push($rules, array("field" => "torneo_interno.fecha_inicio", "data" => "$TransaccionApi->fechaCrea" , "op" => "le"));
                        array_push($rules, array("field" => "torneo_interno.fecha_fin", "data" => "$TransaccionApi->fechaCrea", "op" => "ge"));
                        array_push($rules, array("field" => "torneo_interno.tipo", "data" => "3", "op" => "eq"));
                        array_push($rules, array("field" => "torneo_interno.mandante", "data" => $UsuarioMandante->mandante, "op" => "eq"));

                    }

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $json = json_encode($filtro);


                    $data = $TorneoInterno->getTorneosCustom("torneo_interno.*", "torneo_interno.orden", "ASC", 0, 1000, $json, true, '');

                    print_r(" data2 ");
                    print_r($data);

                    $data = json_decode($data);

                    $final = [];

                    $pos = 1;
                    $torneosAnalizados='';

                    foreach ($data->data as $key => $value) {


                        $TorneoDetalle = new TorneoDetalle();

                        //$bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
                        //$bonos = json_decode($bonos);


                        $rules = [];

                        array_push($rules, array("field" => "torneo_interno.torneo_id", "data" => $value->{"torneo_interno.torneo_id"}, "op" => "eq"));
                        array_push($rules, array("field" => "torneo_interno.estado", "data" => "A", "op" => "eq"));

                        $filtro = array("rules" => $rules, "groupOp" => "AND");

                        $json = json_encode($filtro);

                        $torneodetalles = $TorneoDetalle->getTorneoDetallesCustom(" torneo_detalle.*,torneo_interno.* ", "torneo_interno.torneo_id", "asc", 0, 1000, $json, TRUE);

                        $torneodetalles = json_decode($torneodetalles);

                        $final = [];

                        $creditosConvert=0;

                        $cumpleCondicion=false;
                        $needSubscribe=false;

                        $cumpleCondicionPais=false;
                        $cumpleCondicionCont=0;

                        $condicionesProducto=0;

                        foreach ($torneodetalles->data as $key2 => $value2) {

                            switch ($value2->{"torneo_detalle.tipo"}){
                                case "RANK":
                                    if($value2->{"torneo_detalle.moneda"} == $UsuarioMandante->moneda){
                                        if($valorTicket >= $value2->{"torneo_detalle.valor"}){
                                            if($valorTicket <= $value2->{"torneo_detalle.valor2"}){
                                                $creditosConvert=$value2->{"torneo_detalle.valor3"};
                                            }

                                        }
                                    }

                                    break;

                                case "USERSUBSCRIBE":

                                    if($value2->{"torneo_detalle.valor"} ==0){

                                    }else{
                                        $needSubscribe=true;
                                    }

                                    break;

                                case "VISIBILIDAD":

                                    if($value2->{"torneo_detalle.valor"} ==1){
                                        $needSubscribe=true;

                                    }else{
                                    }

                                    break;


                                case "CONDPAISUSER":

                                    if($value2->{"torneo_detalle.valor"} ==$Usuario->paisId){
                                        $cumpleCondicionPais=true;
                                    }
                                    $cumpleCondicionCont++;

                                    break;

                                default:

                                    if (stristr($value2->{"torneo_detalle.tipo"}, 'CONDGAME')) {

                                        $idGame = explode("CONDGAME", $value2->{"torneo_detalle.tipo"})[1];

                                        if($ProductoMandante->prodmandanteId == $idGame){
                                            $cumpleCondicion=true;
                                        }else{


                                        }
                                        $condicionesProducto++;
                                    }

                                    if (stristr($value2->{"torneo_detalle.tipo"}, 'CONDPROVIDER')) {

                                        $idProvider = explode("CONDPROVIDER", $value2->{"torneo_detalle.tipo"})[1];

                                        if($Producto->proveedorId == $idProvider){
                                            $cumpleCondicion=true;
                                        }
                                        $condicionesProducto++;
                                    }

                                    if (stristr($value2->{"torneo_detalle.tipo"}, 'CONDSUBPROVIDER')) {

                                        $idProvider = explode("CONDSUBPROVIDER", $value2->{"torneo_detalle.tipo"})[1];

                                        if($Producto->subproveedorId == $idProvider){
                                            $cumpleCondicion=true;
                                        }
                                        $condicionesProducto++;
                                    }

                                    break;
                            }

                        }

                        if($condicionesProducto==0 && !$cumpleCondicion){
                            $cumpleCondicion=true;
                        }

                        if(!$cumpleCondicionPais && $cumpleCondicionCont>0){

                            $cumpleCondicion=false;
                        }


                        if($cumpleCondicion) {
                            if ($creditosConvert == 0) {
                                $cumpleCondicion = false;
                            }
                        }

                        if($cumpleCondicion && !$needSubscribe && $creditosConvert>0){
                            print_r("CUMPLECONDICION");

                            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();

                            $UsuarioTorneo = new UsuarioTorneo();
                            $UsuarioTorneo->usuarioId=$UsuarioMandante->usumandanteId;
                            $UsuarioTorneo->torneoId=$value->{"torneo_interno.torneo_id"};
                            $UsuarioTorneo->valor=0;
                            $UsuarioTorneo->posicion=0;
                            $UsuarioTorneo->valorBase=0;
                            $UsuarioTorneo->usucreaId=0;
                            $UsuarioTorneo->usumodifId=0;
                            $UsuarioTorneo->estado="A";
                            $UsuarioTorneo->errorId=0;
                            $UsuarioTorneo->idExterno=0;
                            $UsuarioTorneo->mandante=0;
                            $UsuarioTorneo->version=0;
                            $UsuarioTorneo->apostado=0;
                            $UsuarioTorneo->codigo=0;
                            $UsuarioTorneo->externoId=0;
                            $UsuarioTorneo->valor=$UsuarioTorneo->valor + $creditosConvert;
                            $UsuarioTorneo->valorBase=($UsuarioTorneo->valorBase + $valorTicket);

                            $UsuarioTorneoMySqlDAO = new UsuarioTorneoMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                            $idUsuTorneo=$UsuarioTorneoMySqlDAO->insert($UsuarioTorneo);

                            $TransjuegoInfo = new TransjuegoInfo();
                            $TransjuegoInfo->productoId=$ProductoMandante->prodmandanteId;

                            if($TransjuegoLog != null){
                                $TransjuegoInfo->transaccionId=$TransjuegoLog->transaccionId;

                            }else{
                                $TransjuegoInfo->transaccionId=$TransaccionApi->transaccionId;

                            }

                            $TransjuegoInfo->tipo="TORNEO";
                            $TransjuegoInfo->descripcion=$idUsuTorneo;
                            $TransjuegoInfo->valor=$creditosConvert;

                            if($TransaccionApi != null){
                                $TransjuegoInfo->transapiId=$TransaccionApi->transapiId;

                            }else{
                                $TransjuegoInfo->transapiId=$TransjuegoLog->transjuegologId;
                            }
                            $TransjuegoInfo->usucreaId=0;
                            $TransjuegoInfo->usumodifId=0;

                            $title = '';
                            $messageBody = '';
                            $tournamentName = $value->{"torneo_interno.nombre"};

                            switch(strtolower($Usuario->idioma)) {
                                case 'es':
                                    $title = 'Notificacion';
                                    $messageBody = "¡ Bien :thumbsup: ! Sumaste {$creditosConvert} puntos en {$tournamentName} :clap:";
                                    break;
                                case 'en':
                                    $title = 'Notification';
                                    $messageBody = "¡ Great :thumbsup: ! You added{$creditosConvert} points in the {$tournamentName} :clap:";
                                    break;
                                case 'pt':
                                    $title = 'Notificação';
                                    $messageBody = "Olha só! :thumbsup: Você somou {$creditosConvert} pontos em {$tournamentName} :clap:";
                                    break;
                            }

                            $UsuarioMensaje = new UsuarioMensaje();
                            $UsuarioMensaje->usufromId = 0;
                            $UsuarioMensaje->usutoId = $UsuarioTorneo->getUsuarioId();
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


                            $mensajesRecibidos=[];
                            $array = [];

                            $array["body"] = $messageBody;

                            array_push($mensajesRecibidos, $array);
                            $data = array();
                            $data["messages"] = $mensajesRecibidos;
                            //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

                            /* $UsuarioSession = new UsuarioSession();
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

                            if(in_array($UsuarioMandante->mandante,array('0',8,6))){

                                $dataSend = $data;
                                $WebsocketUsuario = new WebsocketUsuario('','');
                                $WebsocketUsuario->sendWSPieSocket($UsuarioMandante,$dataSend);

                            }
                            break;
                        }


                    }


                }




                break;

            case "VIRTUAL":


                $valorTicket=0;
                $IdProducto=0;
                if($ticketId2 != ''){

                    $TransjuegoLog = new TransjuegoLog($ticketId2);
                    $TransaccionJuego = new TransaccionJuego($TransjuegoLog->getTransjuegoId());
                    $UsuarioMandante = new UsuarioMandante($TransaccionJuego->usuarioId);
                    $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

                    $ProductoMandante = new ProductoMandante('','',$TransaccionJuego->productoId);
                    $Producto = new Producto($ProductoMandante->productoId);
                    $valorTicket =$TransjuegoLog->getValor();
                    $IdProducto = $Producto->getProductoId();
                    $rules = [];

                    array_push($rules, array("field" => "usuario_torneo.usuario_id", "data" => "$TransaccionJuego->usuarioId", "op" => "eq"));
                    array_push($rules, array("field" => "torneo_interno.estado", "data" => "A", "op" => "eq"));
                    array_push($rules, array("field" => "torneo_interno.fecha_inicio", "data" => "$TransjuegoLog->fechaCrea" , "op" => "le"));
                    array_push($rules, array("field" => "torneo_interno.fecha_fin", "data" => "$TransjuegoLog->fechaCrea", "op" => "ge"));
                    array_push($rules, array("field" => "torneo_interno.tipo", "data" => "4", "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                }else{

                    $TransaccionApi = new TransaccionApi($ticketId);
                    $UsuarioMandante = new UsuarioMandante($TransaccionApi->usuarioId);
                    $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

                    $ProductoMandante = new ProductoMandante('','',$TransaccionApi->productoId);
                    $Producto = new Producto($ProductoMandante->productoId);

                    $valorTicket =$TransaccionApi->getValor();
                    $IdProducto = $Producto->getProductoId();
                    $rules = [];

                    array_push($rules, array("field" => "usuario_torneo.usuario_id", "data" => "$TransaccionApi->usuarioId", "op" => "eq"));
                    array_push($rules, array("field" => "torneo_interno.estado", "data" => "A", "op" => "eq"));
                    array_push($rules, array("field" => "torneo_interno.fecha_inicio", "data" => "$TransaccionApi->fechaCrea" , "op" => "le"));
                    array_push($rules, array("field" => "torneo_interno.fecha_fin", "data" => "$TransaccionApi->fechaCrea", "op" => "ge"));
                    array_push($rules, array("field" => "torneo_interno.tipo", "data" => "4", "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                }


                $json = json_encode($filtro);


                $UsuarioTorneo = new UsuarioTorneo();
                $data = $UsuarioTorneo->getUsuarioTorneosCustomWithoutPosition("usuario_torneo.*,usuario_mandante.nombres,torneo_interno.*", "usuario_torneo.valor", "DESC", 0, 100, $json, true, '');

                print_r($data);

                $data = json_decode($data);

                $final = [];

                $pos = 1;
                $torneosAnalizados='';

                $cumpleCondicion=false;
                foreach ($data->data as $key => $value) {

                    $array = [];
                    $array["Position"] = $pos;
                    $array["Id"] = $value->{"usuario_torneo.usutorneo_id"};

                    $torneosAnalizados = $torneosAnalizados . $value->{"usuario_torneo.torneo_id"} . ",";
                    $TorneoInterno = new TorneoInterno();
                    $TorneoDetalle = new TorneoDetalle();

                    //$bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
                    //$bonos = json_decode($bonos);


                    $rules = [];

                    array_push($rules, array("field" => "torneo_interno.torneo_id", "data" => $value->{"usuario_torneo.torneo_id"}, "op" => "eq"));
                    array_push($rules, array("field" => "torneo_interno.estado", "data" => "A", "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $json = json_encode($filtro);

                    $torneodetalles = $TorneoDetalle->getTorneoDetallesCustom(" torneo_detalle.*,torneo_interno.* ", "torneo_interno.torneo_id", "asc", 0, 1000, $json, TRUE);

                    $torneodetalles = json_decode($torneodetalles);

                    $final = [];

                    $creditosConvert=0;

                    $cumpleCondicionPais=false;
                    $cumpleCondicionCont=0;

                    $condicionesProducto=0;

                    foreach ($torneodetalles->data as $key2 => $value2) {

                        switch ($value2->{"torneo_detalle.tipo"}){
                            case "RANK":
                                if($value2->{"torneo_detalle.moneda"} == $UsuarioMandante->moneda){
                                    if($valorTicket >= $value2->{"torneo_detalle.valor"}){
                                        if($valorTicket <= $value2->{"torneo_detalle.valor2"}){
                                            $creditosConvert=$value2->{"torneo_detalle.valor3"};
                                        }

                                    }
                                }

                                break;


                            case "CONDPAISUSER":

                                if($value2->{"torneo_detalle.valor"} ==$Usuario->paisId){
                                    $cumpleCondicionPais=true;
                                }
                                $cumpleCondicionCont++;

                                break;

                            default:

                                if (stristr($value2->{"torneo_detalle.tipo"}, 'CONDGAME')) {

                                    $idGame = explode("CONDGAME", $value2->{"torneo_detalle.tipo"})[1];

                                    if($ProductoMandante->prodmandanteId == $idGame){
                                        $cumpleCondicion=true;
                                    }else{


                                    }
                                    $condicionesProducto++;
                                }

                                if (stristr($value2->{"torneo_detalle.tipo"}, 'CONDPROVIDER')) {

                                    $idProvider = explode("CONDPROVIDER", $value2->{"torneo_detalle.tipo"})[1];

                                    if($Producto->proveedorId == $idProvider){
                                        $cumpleCondicion=true;
                                    }
                                    $condicionesProducto++;
                                }

                                if (stristr($value2->{"torneo_detalle.tipo"}, 'CONDSUBPROVIDER')) {

                                    $idProvider = explode("CONDSUBPROVIDER", $value2->{"torneo_detalle.tipo"})[1];

                                    if($Producto->subproveedorId == $idProvider){
                                        $cumpleCondicion=true;
                                    }
                                    $condicionesProducto++;
                                }

                                break;
                        }

                    }

                    if($condicionesProducto==0 && !$cumpleCondicion){
                        $cumpleCondicion=true;
                    }

                    if(!$cumpleCondicionPais && $cumpleCondicionCont>0){
                        $cumpleCondicion=false;
                    }

                    if($cumpleCondicion) {
                        if ($creditosConvert == 0) {
                            $cumpleCondicion = false;
                        }
                    }
                    if($cumpleCondicion){
                        $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();

                        $UsuarioTorneo = new UsuarioTorneo( $value->{"usuario_torneo.usutorneo_id"});
                        $UsuarioTorneo->valor=$UsuarioTorneo->valor + $creditosConvert;
                        $UsuarioTorneo->valorBase=($UsuarioTorneo->valorBase + $valorTicket);

                        if($TransaccionJuego == null){
                            $TransaccionJuego = new TransaccionJuego("", $TransaccionApi->getIdentificador());

                        }

                        $UsuarioTorneoMySqlDAO = new UsuarioTorneoMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                        $UsuarioTorneoMySqlDAO->update($UsuarioTorneo);

                        $TransjuegoInfo = new TransjuegoInfo();
                        $TransjuegoInfo->productoId=$ProductoMandante->prodmandanteId;
                        if($TransjuegoLog != null){
                            $TransjuegoInfo->transaccionId=$TransjuegoLog->transaccionId;

                        }else{
                            $TransjuegoInfo->transaccionId=$TransaccionApi->transaccionId;

                        }
                        $TransjuegoInfo->tipo="TORNEO";
                        $TransjuegoInfo->descripcion=$value->{"usuario_torneo.usutorneo_id"};
                        $TransjuegoInfo->valor=$creditosConvert;
                        if($TransaccionApi != null){
                            $TransjuegoInfo->transapiId=$TransaccionApi->transapiId;

                        }else{
                            $TransjuegoInfo->transapiId=$TransjuegoLog->transjuegologId;
                        }
                        $TransjuegoInfo->usucreaId=0;
                        $TransjuegoInfo->usumodifId=0;
                        $TransjuegoInfo->identificador=$TransaccionJuego->ticketId;
                        $TransjuegoInfo->transjuegoId=$TransaccionJuego->transjuegoId;

                        $title = '';
                        $messageBody = '';
                        $tournamentName = $value->{"torneo_interno.nombre"};

                        switch(strtolower($Usuario->idioma)) {
                            case 'es':
                                $title = 'Notificacion';
                                $messageBody = "¡ Bien :thumbsup: ! Sumaste {$creditosConvert} puntos en {$tournamentName} :clap:";
                                break;
                            case 'en':
                                $title = 'Notification';
                                $messageBody = "¡ Great :thumbsup: ! You added{$creditosConvert} points in the {$tournamentName} :clap:";
                                break;
                            case 'pt':
                                $title = 'Notificação';
                                $messageBody = "Olha só! :thumbsup: Você somou {$creditosConvert} pontos em {$tournamentName} :clap:";
                                break;
                        }

                        $UsuarioMensaje = new UsuarioMensaje();
                        $UsuarioMensaje->usufromId = 0;
                        $UsuarioMensaje->usutoId = $UsuarioTorneo->getUsuarioId();
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


                        $mensajesRecibidos=[];
                        $array = [];

                        $array["body"] = $messageBody;

                        array_push($mensajesRecibidos, $array);
                        $data = array();
                        $data["messages"] = $mensajesRecibidos;
                        //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

                        /* $UsuarioSession = new UsuarioSession();
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

                        if(in_array($UsuarioMandante->mandante,array('0',8,6))){

                            $dataSend = $data;
                            $WebsocketUsuario = new WebsocketUsuario('','');
                            $WebsocketUsuario->sendWSPieSocket($UsuarioMandante,$dataSend);

                        }

                        break;
                    }


                }

                if( !$cumpleCondicion) {

                    if($torneosAnalizados != ""){
                        $torneosAnalizados=$torneosAnalizados .'0';
                    }

                    $TorneoInterno = new TorneoInterno();

                    $rules = [];

                    if($torneosAnalizados != ''){
                        array_push($rules, array("field" => "torneo_interno.torneo_id", "data" => "$torneosAnalizados", "op" => "ni"));
                    }
                    if($TransjuegoLog != null){

                        array_push($rules, array("field" => "torneo_interno.estado", "data" => "A", "op" => "eq"));
                        array_push($rules, array("field" => "torneo_interno.fecha_inicio", "data" => "$TransjuegoLog->fechaCrea" , "op" => "le"));
                        array_push($rules, array("field" => "torneo_interno.fecha_fin", "data" => "$TransjuegoLog->fechaCrea", "op" => "ge"));
                        array_push($rules, array("field" => "torneo_interno.tipo", "data" => "4", "op" => "eq"));
                        array_push($rules, array("field" => "torneo_interno.mandante", "data" => $UsuarioMandante->mandante, "op" => "eq"));

                    }else{

                        array_push($rules, array("field" => "torneo_interno.estado", "data" => "A", "op" => "eq"));
                        array_push($rules, array("field" => "torneo_interno.fecha_inicio", "data" => "$TransaccionApi->fechaCrea" , "op" => "le"));
                        array_push($rules, array("field" => "torneo_interno.fecha_fin", "data" => "$TransaccionApi->fechaCrea", "op" => "ge"));
                        array_push($rules, array("field" => "torneo_interno.tipo", "data" => "4", "op" => "eq"));
                        array_push($rules, array("field" => "torneo_interno.mandante", "data" => $UsuarioMandante->mandante, "op" => "eq"));

                    }

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $json = json_encode($filtro);


                    $data = $TorneoInterno->getTorneosCustom("torneo_interno.*", "torneo_interno.orden", "ASC", 0, 1000, $json, true, '');

                    print_r(" data2 ");
                    print_r($data);

                    $data = json_decode($data);

                    $final = [];

                    $pos = 1;
                    $torneosAnalizados='';

                    foreach ($data->data as $key => $value) {


                        $TorneoDetalle = new TorneoDetalle();

                        //$bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
                        //$bonos = json_decode($bonos);


                        $rules = [];

                        array_push($rules, array("field" => "torneo_interno.torneo_id", "data" => $value->{"torneo_interno.torneo_id"}, "op" => "eq"));
                        array_push($rules, array("field" => "torneo_interno.estado", "data" => "A", "op" => "eq"));

                        $filtro = array("rules" => $rules, "groupOp" => "AND");

                        $json = json_encode($filtro);

                        $torneodetalles = $TorneoDetalle->getTorneoDetallesCustom(" torneo_detalle.*,torneo_interno.* ", "torneo_interno.torneo_id", "asc", 0, 1000, $json, TRUE);

                        $torneodetalles = json_decode($torneodetalles);

                        $final = [];

                        $creditosConvert=0;

                        $cumpleCondicion=false;
                        $needSubscribe=false;

                        $cumpleCondicionPais=false;
                        $cumpleCondicionCont=0;

                        $condicionesProducto=0;

                        foreach ($torneodetalles->data as $key2 => $value2) {

                            switch ($value2->{"torneo_detalle.tipo"}){
                                case "RANK":
                                    if($value2->{"torneo_detalle.moneda"} == $UsuarioMandante->moneda){
                                        if($valorTicket >= $value2->{"torneo_detalle.valor"}){
                                            if($valorTicket <= $value2->{"torneo_detalle.valor2"}){
                                                $creditosConvert=$value2->{"torneo_detalle.valor3"};
                                            }

                                        }
                                    }

                                    break;

                                case "USERSUBSCRIBE":

                                    if($value2->{"torneo_detalle.valor"} ==0){

                                    }else{
                                        $needSubscribe=true;
                                    }

                                    break;

                                case "VISIBILIDAD":

                                    if($value2->{"torneo_detalle.valor"} ==1){
                                        $needSubscribe=true;

                                    }else{
                                    }

                                    break;


                                case "CONDPAISUSER":

                                    if($value2->{"torneo_detalle.valor"} ==$Usuario->paisId){
                                        $cumpleCondicionPais=true;
                                    }
                                    $cumpleCondicionCont++;

                                    break;

                                default:

                                    if (stristr($value2->{"torneo_detalle.tipo"}, 'CONDGAME')) {

                                        $idGame = explode("CONDGAME", $value2->{"torneo_detalle.tipo"})[1];

                                        if($ProductoMandante->prodmandanteId == $idGame){
                                            $cumpleCondicion=true;
                                        }else{


                                        }
                                        $condicionesProducto++;
                                    }

                                    if (stristr($value2->{"torneo_detalle.tipo"}, 'CONDPROVIDER')) {

                                        $idProvider = explode("CONDPROVIDER", $value2->{"torneo_detalle.tipo"})[1];

                                        if($Producto->proveedorId == $idProvider){
                                            $cumpleCondicion=true;
                                        }
                                        $condicionesProducto++;
                                    }

                                    if (stristr($value2->{"torneo_detalle.tipo"}, 'CONDSUBPROVIDER')) {

                                        $idProvider = explode("CONDSUBPROVIDER", $value2->{"torneo_detalle.tipo"})[1];

                                        if($Producto->subproveedorId == $idProvider){
                                            $cumpleCondicion=true;
                                        }
                                        $condicionesProducto++;
                                    }

                                    break;
                            }

                        }

                        if($condicionesProducto==0 && !$cumpleCondicion){
                            $cumpleCondicion=true;
                        }

                        if(!$cumpleCondicionPais && $cumpleCondicionCont>0){

                            $cumpleCondicion=false;
                        }


                        if($cumpleCondicion) {
                            if ($creditosConvert == 0) {
                                $cumpleCondicion = false;
                            }
                        }

                        if($cumpleCondicion && !$needSubscribe && $creditosConvert>0){
                            print_r("CUMPLECONDICION");

                            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();

                            $UsuarioTorneo = new UsuarioTorneo();
                            $UsuarioTorneo->usuarioId=$UsuarioMandante->usumandanteId;
                            $UsuarioTorneo->torneoId=$value->{"torneo_interno.torneo_id"};
                            $UsuarioTorneo->valor=0;
                            $UsuarioTorneo->posicion=0;
                            $UsuarioTorneo->valorBase=0;
                            $UsuarioTorneo->usucreaId=0;
                            $UsuarioTorneo->usumodifId=0;
                            $UsuarioTorneo->estado="A";
                            $UsuarioTorneo->errorId=0;
                            $UsuarioTorneo->idExterno=0;
                            $UsuarioTorneo->mandante=0;
                            $UsuarioTorneo->version=0;
                            $UsuarioTorneo->apostado=0;
                            $UsuarioTorneo->codigo=0;
                            $UsuarioTorneo->externoId=0;
                            $UsuarioTorneo->valor=$UsuarioTorneo->valor + $creditosConvert;
                            $UsuarioTorneo->valorBase=($UsuarioTorneo->valorBase + $valorTicket);

                            $UsuarioTorneoMySqlDAO = new UsuarioTorneoMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                            $idUsuTorneo=$UsuarioTorneoMySqlDAO->insert($UsuarioTorneo);

                            $TransjuegoInfo = new TransjuegoInfo();
                            $TransjuegoInfo->productoId=$ProductoMandante->prodmandanteId;

                            if($TransjuegoLog != null){
                                $TransjuegoInfo->transaccionId=$TransjuegoLog->transaccionId;

                            }else{
                                $TransjuegoInfo->transaccionId=$TransaccionApi->transaccionId;

                            }

                            $TransjuegoInfo->tipo="TORNEO";
                            $TransjuegoInfo->descripcion=$idUsuTorneo;
                            $TransjuegoInfo->valor=$creditosConvert;

                            if($TransaccionApi != null){
                                $TransjuegoInfo->transapiId=$TransaccionApi->transapiId;

                            }else{
                                $TransjuegoInfo->transapiId=$TransjuegoLog->transjuegologId;
                            }
                            $TransjuegoInfo->usucreaId=0;
                            $TransjuegoInfo->usumodifId=0;

                            $title = '';
                            $messageBody = '';
                            $tournamentName = $value->{"torneo_interno.nombre"};

                            switch(strtolower($Usuario->idioma)) {
                                case 'es':
                                    $title = 'Notificacion';
                                    $messageBody = "¡ Bien :thumbsup: ! Sumaste {$creditosConvert} puntos en {$tournamentName} :clap:";
                                    break;
                                case 'en':
                                    $title = 'Notification';
                                    $messageBody = "¡ Great :thumbsup: ! You added{$creditosConvert} points in the {$tournamentName} :clap:";
                                    break;
                                case 'pt':
                                    $title = 'Notificação';
                                    $messageBody = "Olha só! :thumbsup: Você somou {$creditosConvert} pontos em {$tournamentName} :clap:";
                                    break;
                            }

                            $UsuarioMensaje = new UsuarioMensaje();
                            $UsuarioMensaje->usufromId = 0;
                            $UsuarioMensaje->usutoId = $UsuarioTorneo->getUsuarioId();
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


                            $mensajesRecibidos=[];
                            $array = [];

                            $array["body"] = $messageBody;

                            array_push($mensajesRecibidos, $array);
                            $data = array();
                            $data["messages"] = $mensajesRecibidos;
                            //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

                            /* $UsuarioSession = new UsuarioSession();
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

                            if(in_array($UsuarioMandante->mandante,array('0',8,6))){

                                $dataSend = $data;
                                $WebsocketUsuario = new WebsocketUsuario('','');
                                $WebsocketUsuario->sendWSPieSocket($UsuarioMandante,$dataSend);

                            }
                            break;
                        }


                    }


                }





                break;

        }
    }


    /**
     * Verficiar torneo
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
    public function verificarTorneoUsuarioConTransaccionJuego($usuarioId, $detalles,$tipoProducto,$ticketId){

        switch($tipoProducto){


            case "CASINO":

                $TransaccionJuego = new TransaccionJuego($ticketId);
                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->usuarioId);
                $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());


                $ProductoMandante = new ProductoMandante('','',$TransaccionJuego->productoId);
                $Producto = new Producto($ProductoMandante->productoId);

                print_r($TransaccionJuego);

                $rules = [];

                array_push($rules, array("field" => "usuario_torneo.usuario_id", "data" => "$TransaccionJuego->usuarioId", "op" => "eq"));
                array_push($rules, array("field" => "torneo_interno.estado", "data" => "A", "op" => "eq"));
                // array_push($rules, array("field" => "torneo_interno.fecha_inicio", "data" => "$TransaccionJuego->fechaCrea" , "op" => "le"));
                // array_push($rules, array("field" => "torneo_interno.fecha_fin", "data" => "$TransaccionJuego->fechaCrea", "op" => "ge"));
                array_push($rules, array("field" => "torneo_interno.tipo", "data" => "2", "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                $json = json_encode($filtro);


                $UsuarioTorneo = new UsuarioTorneo();
                $data = $UsuarioTorneo->getUsuarioTorneosCustomWithoutPosition("usuario_torneo.*,usuario_mandante.nombres,torneo_interno.*", "usuario_torneo.valor", "DESC", 0, 100, $json, true, '');

                print_r($data);

                $data = json_decode($data);

                $final = [];

                $pos = 1;
                $torneosAnalizados='';

                $cumpleCondicion=false;
                foreach ($data->data as $key => $value) {

                    $array = [];
                    $array["Position"] = $pos;
                    $array["Id"] = $value->{"usuario_torneo.usutorneo_id"};

                    $torneosAnalizados = $torneosAnalizados . $value->{"usuario_torneo.torneo_id"} . ",";
                    $TorneoInterno = new TorneoInterno();
                    $TorneoDetalle = new TorneoDetalle();

                    //$bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
                    //$bonos = json_decode($bonos);


                    $rules = [];

                    array_push($rules, array("field" => "torneo_interno.torneo_id", "data" => $value->{"usuario_torneo.torneo_id"}, "op" => "eq"));
                    array_push($rules, array("field" => "torneo_interno.estado", "data" => "A", "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $json = json_encode($filtro);

                    $torneodetalles = $TorneoDetalle->getTorneoDetallesCustom(" torneo_detalle.*,torneo_interno.* ", "torneo_interno.torneo_id", "asc", 0, 1000, $json, TRUE);

                    $torneodetalles = json_decode($torneodetalles);

                    $final = [];

                    $creditosConvert=0;

                    $cumpleCondicionPais=false;
                    $cumpleCondicionCont=0;

                    $condicionesProducto=0;

                    print_r($torneodetalles);

                    foreach ($torneodetalles->data as $key2 => $value2) {

                        switch ($value2->{"torneo_detalle.tipo"}){
                            case "RANK":
                                if($value2->{"torneo_detalle.moneda"} == $UsuarioMandante->moneda){
                                    if($TransaccionJuego->valorTicket >= $value2->{"torneo_detalle.valor"}){
                                        if($TransaccionJuego->valorTicket <= $value2->{"torneo_detalle.valor2"}){
                                            $creditosConvert=$value2->{"torneo_detalle.valor3"};
                                        }

                                    }
                                }

                                break;


                            case "CONDPAISUSER":

                                if($value2->{"torneo_detalle.valor"} ==$Usuario->paisId){
                                    $cumpleCondicionPais=true;
                                }
                                $cumpleCondicionCont++;

                                break;

                            default:

                                if (stristr($value2->{"torneo_detalle.tipo"}, 'CONDGAME')) {

                                    $idGame = explode("CONDGAME", $value2->{"torneo_detalle.tipo"})[1];

                                    if($TransaccionJuego->productoId == $idGame){
                                        $cumpleCondicion=true;
                                    }
                                    $condicionesProducto++;
                                }

                                if (stristr($value2->{"torneo_detalle.tipo"}, 'CONDPROVIDER')) {
/*
                                    $idProvider = explode("CONDPROVIDER", $value2->{"torneo_detalle.tipo"})[1];

                                    if($TransaccionJuego->proveedorId == $idProvider){
                                        $cumpleCondicion=true;
                                    }
                                    $condicionesProducto++;*/
                                }

                                break;
                        }

                    }

                    if($condicionesProducto==0 && !$cumpleCondicion){
                        $cumpleCondicion=true;
                    }

                    if(!$cumpleCondicionPais && $cumpleCondicionCont>0){
                        $cumpleCondicion=false;
                    }
                    print_r("creditosConvert: ".$creditosConvert);

                    if($cumpleCondicion) {
                        if ($creditosConvert == 0) {
                            $cumpleCondicion = false;
                        }
                    }
                    if($cumpleCondicion){
                        $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();

                        $UsuarioTorneo = new UsuarioTorneo( $value->{"usuario_torneo.usutorneo_id"});
                        $UsuarioTorneo->valor=$UsuarioTorneo->valor + $creditosConvert;
                        $UsuarioTorneo->valorBase=($UsuarioTorneo->valorBase + $TransaccionJuego->valorTicket);

                        print_r($UsuarioTorneo);
                        $UsuarioTorneoMySqlDAO = new UsuarioTorneoMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                        $UsuarioTorneoMySqlDAO->update($UsuarioTorneo);

                        $TransjuegoInfo = new TransjuegoInfo();
                        $TransjuegoInfo->productoId=$TransaccionJuego->productoId;
                        $TransjuegoInfo->transaccionId=$TransaccionJuego->transaccionId;
                        $TransjuegoInfo->tipo="TORNEO";
                        $TransjuegoInfo->descripcion=$value->{"usuario_torneo.usutorneo_id"};
                        $TransjuegoInfo->valor=$creditosConvert;
                        $TransjuegoInfo->transapiId=0;
                        $TransjuegoInfo->usucreaId=0;
                        $TransjuegoInfo->usumodifId=0;
                        $TransjuegoInfo->identificador=$TransaccionJuego->ticketId;

                        $title = '';
                        $messageBody = '';
                        $tournamentName = $value->{"torneo_interno.nombre"};

                        switch(strtolower($Usuario->idioma)) {
                            case 'es':
                                $title = 'Notificacion';
                                $messageBody = "¡ Bien :thumbsup: ! Sumaste {$creditosConvert} puntos en {$tournamentName} :clap:";
                                break;
                            case 'en':
                                $title = 'Notification';
                                $messageBody = "¡ Great :thumbsup: ! You added{$creditosConvert} points in the {$tournamentName} :clap:";
                                break;
                            case 'pt':
                                $title = 'Notificação';
                                $messageBody = "Olha só! :thumbsup: Você somou {$creditosConvert} pontos em {$tournamentName} :clap:";
                                break;
                        }

                        $UsuarioMensaje = new UsuarioMensaje();
                        $UsuarioMensaje->usufromId = 0;
                        $UsuarioMensaje->usutoId = $UsuarioTorneo->getUsuarioId();
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


                        $mensajesRecibidos=[];
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

                        if(in_array($UsuarioMandante->mandante,array('0',8))){

                            $dataSend = $data;
                            $WebsocketUsuario = new WebsocketUsuario('','');
                            $WebsocketUsuario->sendWSPieSocket($UsuarioMandante,$dataSend);

                        }
                        break;
                    }


                }

                if( !$cumpleCondicion) {

                    if($torneosAnalizados != ""){
                        $torneosAnalizados=$torneosAnalizados .'0';
                    }

                    $TorneoInterno = new TorneoInterno();

                    $rules = [];

                    if($torneosAnalizados != ''){
                        array_push($rules, array("field" => "torneo_interno.torneo_id", "data" => "$torneosAnalizados", "op" => "ni"));
                    }

                    array_push($rules, array("field" => "torneo_interno.estado", "data" => "A", "op" => "eq"));
                    array_push($rules, array("field" => "torneo_interno.fecha_inicio", "data" => "$TransaccionJuego->fechaCrea" , "op" => "le"));
                    array_push($rules, array("field" => "torneo_interno.fecha_fin", "data" => "$TransaccionJuego->fechaCrea", "op" => "ge"));
                    array_push($rules, array("field" => "torneo_interno.tipo", "data" => "2", "op" => "eq"));
                    array_push($rules, array("field" => "torneo_interno.mandante", "data" => $UsuarioMandante->mandante, "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $json = json_encode($filtro);


                    $data = $TorneoInterno->getTorneosCustom("torneo_interno.*", "torneo_interno.orden", "ASC", 0, 1000, $json, true, '');


                    $data = json_decode($data);

                    $final = [];

                    $pos = 1;
                    $torneosAnalizados='';

                    foreach ($data->data as $key => $value) {


                        $TorneoDetalle = new TorneoDetalle();

                        //$bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
                        //$bonos = json_decode($bonos);


                        $rules = [];

                        array_push($rules, array("field" => "torneo_interno.torneo_id", "data" => $value->{"torneo_interno.torneo_id"}, "op" => "eq"));
                        array_push($rules, array("field" => "torneo_interno.estado", "data" => "A", "op" => "eq"));

                        $filtro = array("rules" => $rules, "groupOp" => "AND");

                        $json = json_encode($filtro);

                        $torneodetalles = $TorneoDetalle->getTorneoDetallesCustom(" torneo_detalle.*,torneo_interno.* ", "torneo_interno.torneo_id", "asc", 0, 1000, $json, TRUE);

                        $torneodetalles = json_decode($torneodetalles);

                        $final = [];

                        $creditosConvert=0;

                        $cumpleCondicion=false;
                        $needSubscribe=false;

                        $cumpleCondicionPais=false;
                        $cumpleCondicionCont=0;

                        $condicionesProducto=0;
                        print_r($torneodetalles);

                        foreach ($torneodetalles->data as $key2 => $value2) {

                            switch ($value2->{"torneo_detalle.tipo"}){
                                case "RANK":
                                    if($value2->{"torneo_detalle.moneda"} == $UsuarioMandante->moneda){
                                        if($TransaccionJuego->valorTicket >= $value2->{"torneo_detalle.valor"}){
                                            if($TransaccionJuego->valorTicket <= $value2->{"torneo_detalle.valor2"}){
                                                $creditosConvert=$value2->{"torneo_detalle.valor3"};
                                            }

                                        }
                                    }

                                    break;

                                case "USERSUBSCRIBE":

                                    if($value2->{"torneo_detalle.valor"} ==0){

                                    }else{
                                        $needSubscribe=true;
                                    }

                                    break;

                                case "VISIBILIDAD":

                                    if($value2->{"torneo_detalle.valor"} ==1){
                                        $needSubscribe=true;

                                    }else{
                                    }

                                    break;


                                case "CONDPAISUSER":

                                    if($value2->{"torneo_detalle.valor"} ==$Usuario->paisId){
                                        $cumpleCondicionPais=true;
                                    }
                                    $cumpleCondicionCont++;

                                    break;

                                default:

                                    if (stristr($value2->{"torneo_detalle.tipo"}, 'CONDGAME')) {

                                        $idGame = explode("CONDGAME", $value2->{"torneo_detalle.tipo"})[1];

                                        if($TransaccionJuego->productoId == $idGame){

                                            $cumpleCondicion=true;
                                        }
                                        $condicionesProducto++;
                                    }
                                    if (stristr($value2->{"torneo_detalle.tipo"}, 'CONDPROVIDER')) {

                                        $idProvider = explode("CONDPROVIDER", $value2->{"torneo_detalle.tipo"})[1];

                                        if($Producto->proveedorId == $idProvider){
                                            $cumpleCondicion=true;
                                        }
                                        $condicionesProducto++;
                                    }

                                    if (stristr($value2->{"torneo_detalle.tipo"}, 'CONDSUBPROVIDER')) {

                                        $idProvider = explode("CONDSUBPROVIDER", $value2->{"torneo_detalle.tipo"})[1];

                                        if($Producto->subproveedorId == $idProvider){
                                            $cumpleCondicion=true;
                                        }
                                        $condicionesProducto++;
                                    }

                                    /*if (stristr($value2->{"torneo_detalle.tipo"}, 'CONDPROVIDER')) {

                                        $idProvider = explode("CONDPROVIDER", $value2->{"torneo_detalle.tipo"})[1];

                                        if($TransaccionJuego->proveedorId == $idProvider){
                                            $cumpleCondicion=true;
                                        }
                                        $condicionesProducto++;
                                    }*/

                                    break;
                            }

                        }

                        if($condicionesProducto==0 && !$cumpleCondicion){
                            $cumpleCondicion=true;
                        }

                        if(!$cumpleCondicionPais && $cumpleCondicionCont>0){

                            $cumpleCondicion=false;
                        }
                        print_r("creditosConvert: ".$creditosConvert);



                        if($cumpleCondicion) {
                            if ($creditosConvert == 0) {
                                $cumpleCondicion = false;
                            }
                        }

                        if($cumpleCondicion && !$needSubscribe && $creditosConvert>0){
                            print_r("CUMPLECONDICION");

                            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();

                            $UsuarioTorneo = new UsuarioTorneo();
                            $UsuarioTorneo->usuarioId=$UsuarioMandante->usumandanteId;
                            $UsuarioTorneo->torneoId=$value->{"torneo_interno.torneo_id"};
                            $UsuarioTorneo->valor=0;
                            $UsuarioTorneo->posicion=0;
                            $UsuarioTorneo->valorBase=0;
                            $UsuarioTorneo->usucreaId=0;
                            $UsuarioTorneo->usumodifId=0;
                            $UsuarioTorneo->estado="A";
                            $UsuarioTorneo->errorId=0;
                            $UsuarioTorneo->idExterno=0;
                            $UsuarioTorneo->mandante=0;
                            $UsuarioTorneo->version=0;
                            $UsuarioTorneo->apostado=0;
                            $UsuarioTorneo->codigo=0;
                            $UsuarioTorneo->externoId=0;
                            $UsuarioTorneo->valor=$UsuarioTorneo->valor + $creditosConvert;
                            $UsuarioTorneo->valorBase=($UsuarioTorneo->valorBase + $TransaccionJuego->valorTicket);

                            $UsuarioTorneoMySqlDAO = new UsuarioTorneoMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                            $idUsuTorneo=$UsuarioTorneoMySqlDAO->insert($UsuarioTorneo);

                            $TransjuegoInfo = new TransjuegoInfo();
                            $TransjuegoInfo->productoId=$TransaccionJuego->productoId;
                            $TransjuegoInfo->transaccionId=$TransaccionJuego->transaccionId;
                            $TransjuegoInfo->tipo="TORNEO";
                            $TransjuegoInfo->descripcion=$idUsuTorneo;
                            $TransjuegoInfo->valor=$creditosConvert;
                            $TransjuegoInfo->transapiId=0;
                            $TransjuegoInfo->usucreaId=0;
                            $TransjuegoInfo->usumodifId=0;

                            $title = '';
                            $messageBody = '';
                            $tournamentName = $value->{"torneo_interno.nombre"};

                            switch(strtolower($Usuario->idioma)) {
                                case 'es':
                                    $title = 'Notificacion';
                                    $messageBody = "¡ Bien :thumbsup: ! Sumaste {$creditosConvert} puntos en {$tournamentName} :clap:";
                                    break;
                                case 'en':
                                    $title = 'Notification';
                                    $messageBody = "¡ Great :thumbsup: ! You added{$creditosConvert} points in the {$tournamentName} :clap:";
                                    break;
                                case 'pt':
                                    $title = 'Notificação';
                                    $messageBody = "Olha só! :thumbsup: Você somou {$creditosConvert} pontos em {$tournamentName} :clap:";
                                    break;
                            }

                            $UsuarioMensaje = new UsuarioMensaje();
                            $UsuarioMensaje->usufromId = 0;
                            $UsuarioMensaje->usutoId = $UsuarioTorneo->getUsuarioId();
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


                            $mensajesRecibidos=[];
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

                            if(in_array($UsuarioMandante->mandante,array('0',8))){

                                $dataSend = $data;
                                $WebsocketUsuario = new WebsocketUsuario('','');
                                $WebsocketUsuario->sendWSPieSocket($UsuarioMandante,$dataSend);

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


                $ProductoMandante = new ProductoMandante('','',$TransaccionApi->productoId);
                $Producto = new Producto($ProductoMandante->productoId);

                print_r($TransaccionApi);
                print_r($ticketId);

                $rules = [];

                array_push($rules, array("field" => "usuario_torneo.usuario_id", "data" => "$TransaccionApi->usuarioId", "op" => "eq"));
                array_push($rules, array("field" => "torneo_interno.estado", "data" => "A", "op" => "eq"));
                array_push($rules, array("field" => "torneo_interno.fecha_inicio", "data" => "$TransaccionApi->fechaCrea" , "op" => "le"));
                array_push($rules, array("field" => "torneo_interno.fecha_fin", "data" => "$TransaccionApi->fechaCrea", "op" => "ge"));
                array_push($rules, array("field" => "torneo_interno.tipo", "data" => "3", "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                $json = json_encode($filtro);


                $UsuarioTorneo = new UsuarioTorneo();
                $data = $UsuarioTorneo->getUsuarioTorneosCustomWithoutPosition("usuario_torneo.*,usuario_mandante.nombres,torneo_interno.*", "usuario_torneo.valor", "DESC", 0, 100, $json, true, '');

                print_r($data);

                $data = json_decode($data);

                $final = [];

                $pos = 1;
                $torneosAnalizados='';

                $cumpleCondicion=false;
                foreach ($data->data as $key => $value) {

                    $array = [];
                    $array["Position"] = $pos;
                    $array["Id"] = $value->{"usuario_torneo.usutorneo_id"};

                    $torneosAnalizados = $torneosAnalizados . $value->{"usuario_torneo.torneo_id"} . ",";
                    $TorneoInterno = new TorneoInterno();
                    $TorneoDetalle = new TorneoDetalle();

                    //$bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
                    //$bonos = json_decode($bonos);


                    $rules = [];

                    array_push($rules, array("field" => "torneo_interno.torneo_id", "data" => $value->{"usuario_torneo.torneo_id"}, "op" => "eq"));
                    array_push($rules, array("field" => "torneo_interno.estado", "data" => "A", "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $json = json_encode($filtro);

                    $torneodetalles = $TorneoDetalle->getTorneoDetallesCustom(" torneo_detalle.*,torneo_interno.* ", "torneo_interno.torneo_id", "asc", 0, 1000, $json, TRUE);

                    $torneodetalles = json_decode($torneodetalles);

                    $final = [];

                    $creditosConvert=0;

                    $cumpleCondicionPais=false;
                    $cumpleCondicionCont=0;

                    $condicionesProducto=0;

                    foreach ($torneodetalles->data as $key2 => $value2) {

                        switch ($value2->{"torneo_detalle.tipo"}){
                            case "RANK":
                                if($value2->{"torneo_detalle.moneda"} == $UsuarioMandante->moneda){
                                    if($TransaccionApi->valor >= $value2->{"torneo_detalle.valor"}){
                                        if($TransaccionApi->valor <= $value2->{"torneo_detalle.valor2"}){
                                            $creditosConvert=$value2->{"torneo_detalle.valor3"};
                                        }

                                    }
                                }

                                break;


                            case "CONDPAISUSER":

                                if($value2->{"torneo_detalle.valor"} ==$Usuario->paisId){
                                    $cumpleCondicionPais=true;
                                }
                                $cumpleCondicionCont++;

                                break;

                            default:

                                if (stristr($value2->{"torneo_detalle.tipo"}, 'CONDGAME')) {

                                    $idGame = explode("CONDGAME", $value2->{"torneo_detalle.tipo"})[1];

                                    if($TransaccionApi->productoId == $idGame){
                                        $cumpleCondicion=true;
                                    }
                                    $condicionesProducto++;
                                }

                                if (stristr($value2->{"torneo_detalle.tipo"}, 'CONDPROVIDER')) {

                                    $idProvider = explode("CONDPROVIDER", $value2->{"torneo_detalle.tipo"})[1];

                                    if($TransaccionApi->proveedorId == $idProvider){
                                        $cumpleCondicion=true;
                                    }
                                    $condicionesProducto++;
                                }

                                if (stristr($value2->{"torneo_detalle.tipo"}, 'CONDSUBPROVIDER')) {

                                    $idProvider = explode("CONDSUBPROVIDER", $value2->{"torneo_detalle.tipo"})[1];

                                    if($Producto->subproveedorId == $idProvider){
                                        $cumpleCondicion=true;
                                    }
                                    $condicionesProducto++;
                                }

                                break;
                        }

                    }

                    if($condicionesProducto==0 && !$cumpleCondicion){
                        $cumpleCondicion=true;
                    }

                    if(!$cumpleCondicionPais && $cumpleCondicionCont>0){
                        $cumpleCondicion=false;
                    }

                    if($cumpleCondicion){
                        $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();

                        $UsuarioTorneo = new UsuarioTorneo( $value->{"usuario_torneo.usutorneo_id"});
                        $UsuarioTorneo->valor=$UsuarioTorneo->valor + $creditosConvert;
                        $UsuarioTorneo->valorBase=($UsuarioTorneo->valorBase + $TransaccionApi->valor);
                        print_r($TransaccionApi);

                        print_r($UsuarioTorneo);
                        $UsuarioTorneoMySqlDAO = new UsuarioTorneoMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                        $UsuarioTorneoMySqlDAO->update($UsuarioTorneo);

                        $TransjuegoInfo = new TransjuegoInfo();
                        $TransjuegoInfo->productoId=$TransaccionApi->productoId;
                        $TransjuegoInfo->transaccionId=$TransaccionApi->transaccionId;
                        $TransjuegoInfo->tipo="TORNEO";
                        $TransjuegoInfo->descripcion=$value->{"usuario_torneo.usutorneo_id"};
                        $TransjuegoInfo->valor=$creditosConvert;
                        $TransjuegoInfo->transapiId=$TransaccionApi->transapiId;
                        $TransjuegoInfo->usucreaId=0;
                        $TransjuegoInfo->usumodifId=0;
                        $TransjuegoInfo->identificador=$TransaccionApi->identificador;

                        $title = '';
                        $messageBody = '';
                        $tournamentName = $value->{"torneo_interno.nombre"};

                        switch(strtolower($Usuario->idioma)) {
                            case 'es':
                                $title = 'Notificacion';
                                $messageBody = "¡ Bien :thumbsup: ! Sumaste {$creditosConvert} puntos en {$tournamentName} :clap:";
                                break;
                            case 'en':
                                $title = 'Notification';
                                $messageBody = "¡ Great :thumbsup: ! You added{$creditosConvert} points in the {$tournamentName} :clap:";
                                break;
                            case 'pt':
                                $title = 'Notificação';
                                $messageBody = "Olha só! :thumbsup: Você somou {$creditosConvert} pontos em {$tournamentName} :clap:";
                                break;
                        }

                        $UsuarioMensaje = new UsuarioMensaje();
                        $UsuarioMensaje->usufromId = 0;
                        $UsuarioMensaje->usutoId = $UsuarioTorneo->getUsuarioId();
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

                        $mensajesRecibidos=[];
                        $array = [];

                        $array["body"] = $messageBody;

                        array_push($mensajesRecibidos, $array);
                        $data = array();
                        $data["messages"] = $mensajesRecibidos;


                        /*$WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
                        $WebsocketUsuario->sendWSMessage();
*/

                        if(in_array($UsuarioMandante->mandante,array('0',8))){

                            $dataSend = $data;
                            $WebsocketUsuario = new WebsocketUsuario('','');
                            $WebsocketUsuario->sendWSPieSocket($UsuarioMandante,$dataSend);

                        }
                        break;
                    }


                }

                if( !$cumpleCondicion) {

                    if($torneosAnalizados != ""){
                        $torneosAnalizados=$torneosAnalizados .'0';
                    }

                    $TorneoInterno = new TorneoInterno();

                    $rules = [];

                    if($torneosAnalizados != ''){
                        array_push($rules, array("field" => "torneo_interno.torneo_id", "data" => "$torneosAnalizados", "op" => "ni"));
                    }

                    array_push($rules, array("field" => "torneo_interno.estado", "data" => "A", "op" => "eq"));
                    array_push($rules, array("field" => "torneo_interno.fecha_inicio", "data" => "$TransaccionApi->fechaCrea" , "op" => "le"));
                    array_push($rules, array("field" => "torneo_interno.fecha_fin", "data" => "$TransaccionApi->fechaCrea", "op" => "ge"));
                    array_push($rules, array("field" => "torneo_interno.tipo", "data" => "3", "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $json = json_encode($filtro);


                    $data = $TorneoInterno->getTorneosCustom("torneo_interno.*", "torneo_interno.orden", "ASC", 0, 1000, $json, true, '');


                    $data = json_decode($data);
                    print_r($data);

                    $final = [];

                    $pos = 1;
                    $torneosAnalizados='';

                    foreach ($data->data as $key => $value) {


                        $TorneoDetalle = new TorneoDetalle();

                        //$bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
                        //$bonos = json_decode($bonos);


                        $rules = [];

                        array_push($rules, array("field" => "torneo_interno.torneo_id", "data" => $value->{"torneo_interno.torneo_id"}, "op" => "eq"));
                        array_push($rules, array("field" => "torneo_interno.estado", "data" => "A", "op" => "eq"));

                        $filtro = array("rules" => $rules, "groupOp" => "AND");

                        $json = json_encode($filtro);

                        $torneodetalles = $TorneoDetalle->getTorneoDetallesCustom(" torneo_detalle.*,torneo_interno.* ", "torneo_interno.torneo_id", "asc", 0, 1000, $json, TRUE);

                        $torneodetalles = json_decode($torneodetalles);

                        $final = [];

                        $creditosConvert=0;

                        $cumpleCondicion=false;
                        $needSubscribe=false;

                        $cumpleCondicionPais=false;
                        $cumpleCondicionCont=0;

                        $condicionesProducto=0;

                        foreach ($torneodetalles->data as $key2 => $value2) {

                            switch ($value2->{"torneo_detalle.tipo"}){
                                case "RANK":
                                    if($value2->{"torneo_detalle.moneda"} == $UsuarioMandante->moneda){
                                        if($TransaccionApi->valor >= $value2->{"torneo_detalle.valor"}){
                                            if($TransaccionApi->valor <= $value2->{"torneo_detalle.valor2"}){
                                                $creditosConvert=$value2->{"torneo_detalle.valor3"};
                                            }

                                        }
                                    }

                                    break;

                                case "USERSUBSCRIBE":

                                    if($value2->{"torneo_detalle.valor"} ==0){

                                    }else{
                                        $needSubscribe=true;
                                    }

                                    break;

                                case "VISIBILIDAD":

                                    if($value2->{"torneo_detalle.valor"} ==1){
                                        $needSubscribe=true;

                                    }else{
                                    }

                                    break;


                                case "CONDPAISUSER":

                                    if($value2->{"torneo_detalle.valor"} ==$Usuario->paisId){
                                        $cumpleCondicionPais=true;
                                    }
                                    $cumpleCondicionCont++;

                                    break;

                                default:

                                    if (stristr($value2->{"torneo_detalle.tipo"}, 'CONDGAME')) {

                                        $idGame = explode("CONDGAME", $value2->{"torneo_detalle.tipo"})[1];

                                        if($TransaccionApi->productoId == $idGame){

                                            $cumpleCondicion=true;
                                        }
                                        $condicionesProducto++;
                                    }

                                    if (stristr($value2->{"torneo_detalle.tipo"}, 'CONDPROVIDER')) {

                                        $idProvider = explode("CONDPROVIDER", $value2->{"torneo_detalle.tipo"})[1];

                                        if($TransaccionApi->proveedorId == $idProvider){
                                            $cumpleCondicion=true;
                                        }
                                        $condicionesProducto++;
                                    }

                                    if (stristr($value2->{"torneo_detalle.tipo"}, 'CONDSUBPROVIDER')) {

                                        $idProvider = explode("CONDSUBPROVIDER", $value2->{"torneo_detalle.tipo"})[1];

                                        if($Producto->subproveedorId == $idProvider){
                                            $cumpleCondicion=true;
                                        }
                                        $condicionesProducto++;
                                    }

                                    break;
                            }

                        }

                        if($condicionesProducto==0 && !$cumpleCondicion){
                            $cumpleCondicion=true;
                        }

                        if(!$cumpleCondicionPais && $cumpleCondicionCont>0){

                            $cumpleCondicion=false;
                        }

                        if($cumpleCondicion && !$needSubscribe && $creditosConvert>0){
                            print_r("CUMPLECONDICION");

                            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();

                            $UsuarioTorneo = new UsuarioTorneo();
                            $UsuarioTorneo->usuarioId=$UsuarioMandante->usumandanteId;
                            $UsuarioTorneo->torneoId=$value->{"torneo_interno.torneo_id"};
                            $UsuarioTorneo->valor=0;
                            $UsuarioTorneo->posicion=0;
                            $UsuarioTorneo->valorBase=0;
                            $UsuarioTorneo->usucreaId=0;
                            $UsuarioTorneo->usumodifId=0;
                            $UsuarioTorneo->estado="A";
                            $UsuarioTorneo->errorId=0;
                            $UsuarioTorneo->idExterno=0;
                            $UsuarioTorneo->mandante=0;
                            $UsuarioTorneo->version=0;
                            $UsuarioTorneo->apostado=0;
                            $UsuarioTorneo->codigo=0;
                            $UsuarioTorneo->externoId=0;
                            $UsuarioTorneo->valor=$UsuarioTorneo->valor + $creditosConvert;
                            $UsuarioTorneo->valorBase=($UsuarioTorneo->valorBase + $TransaccionApi->valor);

                            $UsuarioTorneoMySqlDAO = new UsuarioTorneoMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                            $idUsuTorneo=$UsuarioTorneoMySqlDAO->insert($UsuarioTorneo);

                            $TransjuegoInfo = new TransjuegoInfo();
                            $TransjuegoInfo->productoId=$TransaccionApi->productoId;
                            $TransjuegoInfo->transaccionId=$TransaccionApi->transaccionId;
                            $TransjuegoInfo->tipo="TORNEO";
                            $TransjuegoInfo->descripcion=$idUsuTorneo;
                            $TransjuegoInfo->valor=$creditosConvert;
                            $TransjuegoInfo->transapiId=$TransaccionApi->transapiId;
                            $TransjuegoInfo->usucreaId=0;
                            $TransjuegoInfo->usumodifId=0;

                            $title = '';
                            $messageBody = '';
                            $tournamentName = $value->{"torneo_interno.nombre"};

                            switch(strtolower($Usuario->idioma)) {
                                case 'es':
                                    $title = 'Notificacion';
                                    $messageBody = "¡ Bien :thumbsup: ! Sumaste {$creditosConvert} puntos en {$tournamentName} :clap:";
                                    break;
                                case 'en':
                                    $title = 'Notification';
                                    $messageBody = "¡ Great :thumbsup: ! You added{$creditosConvert} points in the {$tournamentName} :clap:";
                                    break;
                                case 'pt':
                                    $title = 'Notificação';
                                    $messageBody = "Olha só! :thumbsup: Você somou {$creditosConvert} pontos em {$tournamentName} :clap:";
                                    break;
                            }

                            $UsuarioMensaje = new UsuarioMensaje();
                            $UsuarioMensaje->usufromId = 0;
                            $UsuarioMensaje->usutoId = $UsuarioTorneo->getUsuarioId();
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

                            $mensajesRecibidos=[];
                            $array = [];

                            $array["body"] = $messageBody;

                            array_push($mensajesRecibidos, $array);
                            $data = array();
                            $data["messages"] = $mensajesRecibidos;


                            /*$WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
                            $WebsocketUsuario->sendWSMessage();*/


                            if(in_array($UsuarioMandante->mandante,array('0',8))){

                                $dataSend = $data;
                                $WebsocketUsuario = new WebsocketUsuario('','');
                                $WebsocketUsuario->sendWSPieSocket($UsuarioMandante,$dataSend);

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

                print_r($TransaccionApi);
                print_r($ticketId);

                $rules = [];

                array_push($rules, array("field" => "usuario_torneo.usuario_id", "data" => "$TransaccionApi->usuarioId", "op" => "eq"));
                array_push($rules, array("field" => "torneo_interno.estado", "data" => "A", "op" => "eq"));
                array_push($rules, array("field" => "torneo_interno.fecha_inicio", "data" => "$TransaccionApi->fechaCrea" , "op" => "le"));
                array_push($rules, array("field" => "torneo_interno.fecha_fin", "data" => "$TransaccionApi->fechaCrea", "op" => "ge"));
                array_push($rules, array("field" => "torneo_interno.tipo", "data" => "4", "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                $json = json_encode($filtro);


                $UsuarioTorneo = new UsuarioTorneo();
                $data = $UsuarioTorneo->getUsuarioTorneosCustomWithoutPosition("usuario_torneo.*,usuario_mandante.nombres,torneo_interno.*", "usuario_torneo.valor", "DESC", 0, 100, $json, true, '');

                print_r($data);

                $data = json_decode($data);

                $final = [];

                $pos = 1;
                $torneosAnalizados='';

                $cumpleCondicion=false;
                foreach ($data->data as $key => $value) {

                    $array = [];
                    $array["Position"] = $pos;
                    $array["Id"] = $value->{"usuario_torneo.usutorneo_id"};

                    $torneosAnalizados = $torneosAnalizados . $value->{"usuario_torneo.torneo_id"} . ",";
                    $TorneoInterno = new TorneoInterno();
                    $TorneoDetalle = new TorneoDetalle();

                    //$bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
                    //$bonos = json_decode($bonos);


                    $rules = [];

                    array_push($rules, array("field" => "torneo_interno.torneo_id", "data" => $value->{"usuario_torneo.torneo_id"}, "op" => "eq"));
                    array_push($rules, array("field" => "torneo_interno.estado", "data" => "A", "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $json = json_encode($filtro);

                    $torneodetalles = $TorneoDetalle->getTorneoDetallesCustom(" torneo_detalle.*,torneo_interno.* ", "torneo_interno.torneo_id", "asc", 0, 1000, $json, TRUE);

                    $torneodetalles = json_decode($torneodetalles);

                    $final = [];

                    $creditosConvert=0;

                    $cumpleCondicionPais=false;
                    $cumpleCondicionCont=0;

                    $condicionesProducto=0;

                    foreach ($torneodetalles->data as $key2 => $value2) {

                        switch ($value2->{"torneo_detalle.tipo"}){
                            case "RANK":
                                if($value2->{"torneo_detalle.moneda"} == $UsuarioMandante->moneda){
                                    if($TransaccionApi->valor >= $value2->{"torneo_detalle.valor"}){
                                        if($TransaccionApi->valor <= $value2->{"torneo_detalle.valor2"}){
                                            $creditosConvert=$value2->{"torneo_detalle.valor3"};
                                        }

                                    }
                                }

                                break;


                            case "CONDPAISUSER":

                                if($value2->{"torneo_detalle.valor"} ==$Usuario->paisId){
                                    $cumpleCondicionPais=true;
                                }
                                $cumpleCondicionCont++;

                                break;

                            default:

                                if (stristr($value2->{"torneo_detalle.tipo"}, 'CONDGAME')) {

                                    $idGame = explode("CONDGAME", $value2->{"torneo_detalle.tipo"})[1];

                                    if($TransaccionApi->productoId == $idGame){
                                        $cumpleCondicion=true;
                                    }
                                    $condicionesProducto++;
                                }

                                if (stristr($value2->{"torneo_detalle.tipo"}, 'CONDPROVIDER')) {

                                    $idProvider = explode("CONDPROVIDER", $value2->{"torneo_detalle.tipo"})[1];

                                    if($TransaccionApi->proveedorId == $idProvider){
                                        $cumpleCondicion=true;
                                    }
                                    $condicionesProducto++;
                                }
                                if (stristr($value2->{"torneo_detalle.tipo"}, 'CONDSUBPROVIDER')) {

                                    $idProvider = explode("CONDSUBPROVIDER", $value2->{"torneo_detalle.tipo"})[1];

                                    if($Producto->subproveedorId == $idProvider){
                                        $cumpleCondicion=true;
                                    }
                                    $condicionesProducto++;
                                }


                                break;
                        }

                    }

                    if($condicionesProducto==0 && !$cumpleCondicion){
                        $cumpleCondicion=true;
                    }

                    if(!$cumpleCondicionPais && $cumpleCondicionCont>0){
                        $cumpleCondicion=false;
                    }

                    if($cumpleCondicion){
                        $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();

                        $UsuarioTorneo = new UsuarioTorneo( $value->{"usuario_torneo.usutorneo_id"});
                        $UsuarioTorneo->valor=$UsuarioTorneo->valor + $creditosConvert;
                        $UsuarioTorneo->valorBase=($UsuarioTorneo->valorBase + $TransaccionApi->valor);
                        print_r($TransaccionApi);

                        print_r($UsuarioTorneo);
                        $UsuarioTorneoMySqlDAO = new UsuarioTorneoMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                        $UsuarioTorneoMySqlDAO->update($UsuarioTorneo);

                        $TransjuegoInfo = new TransjuegoInfo();
                        $TransjuegoInfo->productoId=$TransaccionApi->productoId;
                        $TransjuegoInfo->transaccionId=$TransaccionApi->transaccionId;
                        $TransjuegoInfo->tipo="TORNEO";
                        $TransjuegoInfo->descripcion=$value->{"usuario_torneo.usutorneo_id"};
                        $TransjuegoInfo->valor=$creditosConvert;
                        $TransjuegoInfo->transapiId=$TransaccionApi->transapiId;
                        $TransjuegoInfo->usucreaId=0;
                        $TransjuegoInfo->usumodifId=0;
                        $TransjuegoInfo->identificador=$TransaccionApi->identificador;

                        $title = '';
                        $messageBody = '';
                        $tournamentName = $value->{"torneo_interno.nombre"};

                        switch(strtolower($Usuario->idioma)) {
                            case 'es':
                                $title = 'Notificacion';
                                $messageBody = "¡ Bien :thumbsup: ! Sumaste {$creditosConvert} puntos en {$tournamentName} :clap:";
                                break;
                            case 'en':
                                $title = 'Notification';
                                $messageBody = "¡ Great :thumbsup: ! You added{$creditosConvert} points in the {$tournamentName} :clap:";
                                break;
                            case 'pt':
                                $title = 'Notificação';
                                $messageBody = "Olha só! :thumbsup: Você somou {$creditosConvert} pontos em {$tournamentName} :clap:";
                                break;
                        }

                        $UsuarioMensaje = new UsuarioMensaje();
                        $UsuarioMensaje->usufromId = 0;
                        $UsuarioMensaje->usutoId = $UsuarioTorneo->getUsuarioId();
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

                        $mensajesRecibidos=[];
                        $array = [];

                        $array["body"] = $messageBody;

                        array_push($mensajesRecibidos, $array);
                        $data = array();
                        $data["messages"] = $mensajesRecibidos;


                        /*$WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
                        $WebsocketUsuario->sendWSMessage();*/


                        if(in_array($UsuarioMandante->mandante,array('0',8))){

                            $dataSend = $data;
                            $WebsocketUsuario = new WebsocketUsuario('','');
                            $WebsocketUsuario->sendWSPieSocket($UsuarioMandante,$dataSend);

                        }
                        break;
                    }


                }

                if( !$cumpleCondicion) {

                    if($torneosAnalizados != ""){
                        $torneosAnalizados=$torneosAnalizados .'0';
                    }

                    $TorneoInterno = new TorneoInterno();

                    $rules = [];

                    if($torneosAnalizados != ''){
                        array_push($rules, array("field" => "torneo_interno.torneo_id", "data" => "$torneosAnalizados", "op" => "ni"));
                    }

                    array_push($rules, array("field" => "torneo_interno.estado", "data" => "A", "op" => "eq"));
                    array_push($rules, array("field" => "torneo_interno.fecha_inicio", "data" => "$TransaccionApi->fechaCrea" , "op" => "le"));
                    array_push($rules, array("field" => "torneo_interno.fecha_fin", "data" => "$TransaccionApi->fechaCrea", "op" => "ge"));
                    array_push($rules, array("field" => "torneo_interno.tipo", "data" => "4", "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $json = json_encode($filtro);


                    $data = $TorneoInterno->getTorneosCustom("torneo_interno.*", "torneo_interno.orden", "ASC", 0, 1000, $json, true, '');


                    $data = json_decode($data);

                    $final = [];

                    $pos = 1;
                    $torneosAnalizados='';

                    foreach ($data->data as $key => $value) {


                        $TorneoDetalle = new TorneoDetalle();

                        //$bonos = $BonoInterno->getBonosCustom(" bono_interno.* ", "bono_interno.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
                        //$bonos = json_decode($bonos);


                        $rules = [];

                        array_push($rules, array("field" => "torneo_interno.torneo_id", "data" => $value->{"torneo_interno.torneo_id"}, "op" => "eq"));
                        array_push($rules, array("field" => "torneo_interno.estado", "data" => "A", "op" => "eq"));

                        $filtro = array("rules" => $rules, "groupOp" => "AND");

                        $json = json_encode($filtro);

                        $torneodetalles = $TorneoDetalle->getTorneoDetallesCustom(" torneo_detalle.*,torneo_interno.* ", "torneo_interno.torneo_id", "asc", 0, 1000, $json, TRUE);

                        $torneodetalles = json_decode($torneodetalles);

                        $final = [];

                        $creditosConvert=0;

                        $cumpleCondicion=false;
                        $needSubscribe=false;

                        $cumpleCondicionPais=false;
                        $cumpleCondicionCont=0;

                        $condicionesProducto=0;

                        foreach ($torneodetalles->data as $key2 => $value2) {

                            switch ($value2->{"torneo_detalle.tipo"}){
                                case "RANK":
                                    if($value2->{"torneo_detalle.moneda"} == $UsuarioMandante->moneda){
                                        if($TransaccionApi->valor >= $value2->{"torneo_detalle.valor"}){
                                            if($TransaccionApi->valor <= $value2->{"torneo_detalle.valor2"}){
                                                $creditosConvert=$value2->{"torneo_detalle.valor3"};
                                            }

                                        }
                                    }

                                    break;

                                case "USERSUBSCRIBE":

                                    if($value2->{"torneo_detalle.valor"} ==0){

                                    }else{
                                        $needSubscribe=true;
                                    }

                                    break;

                                case "VISIBILIDAD":

                                    if($value2->{"torneo_detalle.valor"} ==1){
                                        $needSubscribe=true;

                                    }else{
                                    }

                                    break;


                                case "CONDPAISUSER":

                                    if($value2->{"torneo_detalle.valor"} ==$Usuario->paisId){
                                        $cumpleCondicionPais=true;
                                    }
                                    $cumpleCondicionCont++;

                                    break;

                                default:

                                    if (stristr($value2->{"torneo_detalle.tipo"}, 'CONDGAME')) {

                                        $idGame = explode("CONDGAME", $value2->{"torneo_detalle.tipo"})[1];

                                        if($TransaccionApi->productoId == $idGame){

                                            $cumpleCondicion=true;
                                        }
                                        $condicionesProducto++;
                                    }

                                    if (stristr($value2->{"torneo_detalle.tipo"}, 'CONDPROVIDER')) {

                                        $idProvider = explode("CONDPROVIDER", $value2->{"torneo_detalle.tipo"})[1];

                                        if($TransaccionApi->proveedorId == $idProvider){
                                            $cumpleCondicion=true;
                                        }
                                        $condicionesProducto++;
                                    }
                                    if (stristr($value2->{"torneo_detalle.tipo"}, 'CONDSUBPROVIDER')) {

                                        $idProvider = explode("CONDSUBPROVIDER", $value2->{"torneo_detalle.tipo"})[1];

                                        if($Producto->subproveedorId == $idProvider){
                                            $cumpleCondicion=true;
                                        }
                                        $condicionesProducto++;
                                    }

                                    break;
                            }

                        }

                        if($condicionesProducto==0 && !$cumpleCondicion){
                            $cumpleCondicion=true;
                        }

                        if(!$cumpleCondicionPais && $cumpleCondicionCont>0){

                            $cumpleCondicion=false;
                        }

                        if($cumpleCondicion && !$needSubscribe && $creditosConvert>0){
                            print_r("CUMPLECONDICION");

                            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();

                            $UsuarioTorneo = new UsuarioTorneo();
                            $UsuarioTorneo->usuarioId=$UsuarioMandante->usumandanteId;
                            $UsuarioTorneo->torneoId=$value->{"torneo_interno.torneo_id"};
                            $UsuarioTorneo->valor=0;
                            $UsuarioTorneo->posicion=0;
                            $UsuarioTorneo->valorBase=0;
                            $UsuarioTorneo->usucreaId=0;
                            $UsuarioTorneo->usumodifId=0;
                            $UsuarioTorneo->estado="A";
                            $UsuarioTorneo->errorId=0;
                            $UsuarioTorneo->idExterno=0;
                            $UsuarioTorneo->mandante=0;
                            $UsuarioTorneo->version=0;
                            $UsuarioTorneo->apostado=0;
                            $UsuarioTorneo->codigo=0;
                            $UsuarioTorneo->externoId=0;
                            $UsuarioTorneo->valor=$UsuarioTorneo->valor + $creditosConvert;
                            $UsuarioTorneo->valorBase=($UsuarioTorneo->valorBase + $TransaccionApi->valor);

                            $UsuarioTorneoMySqlDAO = new UsuarioTorneoMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                            $idUsuTorneo=$UsuarioTorneoMySqlDAO->insert($UsuarioTorneo);

                            $TransjuegoInfo = new TransjuegoInfo();
                            $TransjuegoInfo->productoId=$TransaccionApi->productoId;
                            $TransjuegoInfo->transaccionId=$TransaccionApi->transaccionId;
                            $TransjuegoInfo->tipo="TORNEO";
                            $TransjuegoInfo->descripcion=$idUsuTorneo;
                            $TransjuegoInfo->valor=$creditosConvert;
                            $TransjuegoInfo->transapiId=$TransaccionApi->transapiId;
                            $TransjuegoInfo->usucreaId=0;
                            $TransjuegoInfo->usumodifId=0;

                            $title = '';
                            $messageBody = '';
                            $tournamentName = $value->{"torneo_interno.nombre"};

                            switch(strtolower($Usuario->idioma)) {
                                case 'es':
                                    $title = 'Notificacion';
                                    $messageBody = "¡ Bien :thumbsup: ! Sumaste {$creditosConvert} puntos en {$tournamentName} :clap:";
                                    break;
                                case 'en':
                                    $title = 'Notification';
                                    $messageBody = "¡ Great :thumbsup: ! You added{$creditosConvert} points in the {$tournamentName} :clap:";
                                    break;
                                case 'pt':
                                    $title = 'Notificação';
                                    $messageBody = "Olha só! :thumbsup: Você somou {$creditosConvert} pontos em {$tournamentName} :clap:";
                                    break;
                            }

                            $UsuarioMensaje = new UsuarioMensaje();
                            $UsuarioMensaje->usufromId = 0;
                            $UsuarioMensaje->usutoId = $UsuarioTorneo->getUsuarioId();
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

                            $mensajesRecibidos=[];
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


                            if(in_array($UsuarioMandante->mandante,array('0',8))){

                                $dataSend = $data;
                                $WebsocketUsuario = new WebsocketUsuario('','');
                                $WebsocketUsuario->sendWSPieSocket($UsuarioMandante,$dataSend);

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
    public function execQuery($transaccion,$sql){

        $TorneoInternoMySqlDAO = new TorneoInternoMySqlDAO($transaccion);
        $return = $TorneoInternoMySqlDAO->querySQL($sql);
        $return=json_decode(json_encode($return), FALSE);

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
    public function execUpdate($transaccion,$sql){

        $TorneoInternoMySqlDAO = new TorneoInternoMySqlDAO($transaccion);
        $return = $TorneoInternoMySqlDAO->queryUpdate($sql);

        return $return;

    }




}

?>
