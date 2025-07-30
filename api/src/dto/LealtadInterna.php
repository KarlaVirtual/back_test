<?php namespace Backend\dto;
use Backend\dao\TransjuegoInfoDAO;
use Backend\mysql\BonoLogMySqlDAO;
use Backend\mysql\CiudadMySqlDAO;
use Backend\mysql\LealtadHistorialMySqlDAO;
use Backend\mysql\LealtadInternaMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransjuegoInfoMySqlDAO;
use Backend\mysql\UsuarioBonoMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\UsuarioLealtadMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\websocket\WebsocketUsuario;
use Exception;
/**
 * Clase 'LealtadInterna'
 *
 * Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
 * para la tabla 'LealtadInterna'
 *
 * Ejemplo de uso:
 * $LealtadInterna = new LealtadInterna();
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class LealtadInterna
{

    /**
     * Representación de la columna 'Lealtad' de la tabla 'LealtadInterna'
     *
     * @var string
     */
    var $lealtadId;

    /**
     * Representación de la columna 'fechaInicio' de la tabla 'LealtadInterna'
     *
     * @var string
     */
    var $fechaInicio;

    /**
     * Representación de la columna 'fechaFin' de la tabla 'LealtadInterna'
     *
     * @var string
     */
    var $fechaFin;

    /**
     * Representación de la columna 'descripcion' de la tabla 'LealtadInterna'
     *
     * @var string
     */
    var $descripcion;

    /**
     * Representación de la columna 'nombre' de la tabla 'LealtadInterna'
     *
     * @var string
     */
    var $nombre;

    /**
     * Representación de la columna 'tipo' de la tabla 'LealtadInterna'
     *
     * @var string
     */
    var $tipo;

    /**
     * Representación de la columna 'estado' de la tabla 'LealtadInterna'
     *
     * @var string
     */
    var $estado;

    /**
     * Representación de la columna 'fechaModif' de la tabla 'LealtadInterna'
     *
     * @var string
     */
    var $fechaModif;

    /**
     * Representación de la columna 'fechaCrea' de la tabla 'LealtadInterna'
     *
     * @var string
     */
    var $fechaCrea;

    /**
     * Representación de la columna 'mandante' de la tabla 'LealtadInterna'
     *
     * @var string
     */
    var $mandante;

    /**
     * Representación de la columna 'condicional' de la tabla 'LealtadInterna'
     *
     * @var string
     */
    var $condicional;

    /**
     * Representación de la columna 'puntos' de la tabla 'LealtadInterna'
     *
     * @var string
     */
    var $puntos;

    /**
     * Representación de la columna 'cupoActual' de la tabla 'LealtadInterna'
     *
     * @var string
     */
    var $cupoActual;

    /**
     * Representación de la columna 'cupoMaximo' de la tabla 'LealtadInterna'
     *
     * @var string
     */
    var $cupoMaximo;

    /**
     * Representación de la columna 'cantidadLealtad' de la tabla 'LealtadInterna'
     *
     * @var string
     */
    var $cantidadLealtad;

    /**
     * Representación de la columna 'maximoLealtads' de la tabla 'LealtadInterna'
     *
     * @var string
     */
    var $maximoLealtad;

    /**
     * Representación de la columna 'usucreaId' de la tabla 'LealtadInterna'
     *
     * @var string
     */
    var $usucreaId;

    /**
     * Representación de la columna 'usumodifId' de la tabla 'LealtadInterna'
     *
     * @var string
     */
    var $usumodifId;

    /**
     * Representación de la columna 'codigo' de la tabla 'LealtadInterna'
     *
     * @var string
     */
    var $codigo;

    /**
     * Representación de la columna 'reglas' de la tabla 'LealtadInterna'
     *
     * @var string
     */
    var $reglas;

    /**
     * Representación de la columna 'orden' de la tabla 'LealtadInterna'
     *
     * @var string
     */
    var $orden;


    /**
     * Representación de la columna 'bono_id' de la tabla 'LealtadInterna'
     *
     * @var mixed
     */
    var $bonoId;

    /**
     * Representación de la columna 'tipo_premio' de la tabla 'LealtadInterna'
     *
     * @var mixed
     */
    var $tipoPremio;

    /**
     * Representación de la columna 'puntoventa_propio' de la tabla 'LealtadInterna'
     *
     * @var mixed
     */
    var $puntoventaPropio;

    /**
     * Representación de la columna 'm_body' de la tabla 'LealtadInterna'
     *
     * @var string
     */
    var $mBody;

    /**
     * Representación de la columna 'm_subject' de la tabla 'LealtadInterna'
     *
     * @var string
     */
    var $mSubject;


    /**
     * Constructor de clase
     *
     *
     * @param String $lealtadId id del lealtad interna
     *
     * @return no
     * @throws Exception si LealtadInterna no existe
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function __construct($lealtadId="")
    {
        if ($lealtadId != "") {

            $this->lealtadId = $lealtadId;

            $LealtadInternaMySqlDAO = new LealtadInternaMySqlDAO();

            $LealtadInterna = $LealtadInternaMySqlDAO->load($this->lealtadId);


            if ($LealtadInterna != null && $LealtadInterna != "") {
                $this->lealtadId = $LealtadInterna->lealtadId;
                $this->fechaInicio = $LealtadInterna->fechaInicio;
                $this->fechaFin = $LealtadInterna->fechaFin;
                $this->descripcion = $LealtadInterna->descripcion;
                $this->nombre = $LealtadInterna->nombre;
                $this->tipo = $LealtadInterna->tipo;
                $this->estado = $LealtadInterna->estado;
                $this->fechaModif = $LealtadInterna->fechaModif;
                $this->fechaCrea = $LealtadInterna->fechaCrea;
                $this->mandante = $LealtadInterna->mandante;
                $this->usucreaId = $LealtadInterna->usucreaId;
                $this->usumodifId = $LealtadInterna->usumodifId;
                $this->condicional = $LealtadInterna->condicional;
                $this->puntos = $LealtadInterna->puntos;
                $this->orden = $LealtadInterna->orden;
                $this->cupoActual = $LealtadInterna->cupoActual;
                $this->cupoMaximo = $LealtadInterna->cupoMaximo;
                $this->cantidadLealtad = $LealtadInterna->cantidadLealtad;
                $this->maximoLealtad = $LealtadInterna->maximoLealtad;
                $this->codigo = $LealtadInterna->codigo;
                $this->reglas = $LealtadInterna->reglas;
                $this->bonoId = $LealtadInterna->bonoId;
                $this->tipoPremio = $LealtadInterna->tipoPremio;
                $this->puntoventaPropio = $LealtadInterna->puntoventaPropio;
                $this->mBody = $LealtadInterna->mBody;
                $this->mSubject = $LealtadInterna->mSubject;
            }
            else {
                throw new Exception("No existe " . get_class($this), "28");
            }

        }
    }




    /**
     * Realizar una consulta en la tabla de lealtad 'LealtadInterna'
     * de una manera personalizada
     *
     *
     * @param String $select campos de consulta
     * @param String $sidx columna para puntosar
     * @param String $sord puntos los datos asc | desc
     * @param String $start inicio de la consulta
     * @param String $limit limite de la consulta
     * @param String $filters condiciones de la consulta
     * @param boolean $searchOn utilizar los filtros o no
     *
     * @return Array resultado de la consulta
     * @throws Exception si los lealtads no existen
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function getLealtadCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn)
    {

        $LealtadInternaMySqlDAO = new LealtadInternaMySqlDAO();

        $lealtad = $LealtadInternaMySqlDAO->queryLealtadsCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn);

        if ($lealtad != null && $lealtad != "")
        {
            return $lealtad;
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

        $LealtadInternaMySqlDAO = new LealtadInternaMySqlDAO($transaction);
        return $LealtadInternaMySqlDAO->insert($this);

    }

    /**
     * Agregar un registro de lealtad en la base de datos
     *
     *
     * @param String tipoLealtad tipoLealtad
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
    public function agregarLealtad($lealtadId,$tipoLealtad, $usuarioId, $mandante, $detalles,$transaccion)
    {

        //Verificamos Detalles a tomar en cuenta al momento de verificar si puede ganar un lealtad
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

        $Form = $detalles->Form;
        $betShopId = $detalles->betShopId;
        $Names = $detalles->Names;
        $Surnames = $detalles->Surnames;
        $Identification = $detalles->Identification;
        $Phone = $detalles->Phone;
        $City = $detalles->City;
        $Province = $detalles->Province;
        $Address = $detalles->Address;
        $Team = $detalles->Team;

        $cumpleCondiciones = false;
        $lealtadElegido = 0;
        $lealtadTieneRollower = false;
        $rollowerLealtad = 0;
        $rollowerDeposito = 0;
        $Usuario = new Usuario($usuarioId);
        $UsuarioMandante = new UsuarioMandante("",$Usuario->usuarioId,$Usuario->mandante);

        $this->validarTiempoMinimoEntreCanjes($usuarioId, $lealtadId, $transaccion);

        //Obtenemos todos los registros de lealtad disponibles
        $sqlLealtad = "select a.lealtad_id ,a.tipo,a.fecha_inicio,a.fecha_fin,now() test from lealtad_interna a where a.lealtad_id='" .$lealtadId . "' AND a.mandante=" . $mandante . " and now()  between (a.fecha_inicio) and (a.fecha_fin) and a.estado='A' ORDER BY a.puntos DESC,a.fecha_crea ASC ";

        if($CodePromo != ""){
            //$sqlLealtad = "select a.lealtad_id,a.tipo,a.fecha_inicio,a.fecha_fin from lealtad_interna a INNER JOIN lealtad_detalle b ON (a.lealtad_id=b.lealtad_id AND b.tipo='CODEPROMO' AND b.valor='".$CodePromo."') where a.mandante=" . $mandante . " and now()  between (a.fecha_inicio) and (a.fecha_fin) and a.estado='A' ORDER BY a.puntos DESC,a.fecha_crea ASC ";

        }

        $lealtadDisponibles = $this->execQuery($transaccion,$sqlLealtad);

        $respuesta = array();
        $respuesta["lealtad"] = 0;
        $respuesta["WinLealtad"] = false;

        foreach ($lealtadDisponibles as $lealtad) {



            if (!$cumpleCondiciones) {

                //Obtenemos todos los detalles de lealtad
                $sqlDetalleLealtad = "select * from lealtad_detalle a where a.lealtad_id='" . $lealtad->{"a.lealtad_id"}. "' AND (moneda='' OR moneda='".$detalleMonedaUSER."') ";
                $lealtadDetalles = $this->execQuery($transaccion,$sqlDetalleLealtad);

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

                $puederepetirLealtad=false ;
                $ganaLealtadId=0;
                $puntos = $Usuario->puntosLealtad;
                $puntosAexpirar = $Usuario->getPuntosAexpirar();
                $puntosTotales = $puntos + $puntosAexpirar;
                $PuntosLealtad = "";
                $maximopago = 0;
                $maximodeposito = 0;
                $minimodeposito = 0;
                $valorlealtad = 0;
                $tipoproducto = 0;
                $tipolealtad = "";
                $lealtadTieneRollower = false;
                $tiposaldo=-1;

                $cantFalse=0;


                foreach ($lealtadDetalles as $lealtadDetalle) {

                    switch ($lealtadDetalle->{"a.tipo"}) {


                        case "CONDPAISUSER":

                            $condicionPaisUSERcount = $condicionPaisUSERcount + 1;
                            if ($lealtadDetalle->{"a.valor"} == $detallePaisUSER) {
                                $condicionPaisUSER = true;
                            }

                            break;

                        case "CONDDEPARTAMENTOUSER":

                            $condicionDepartamentoUSERcount = $condicionDepartamentoUSERcount + 1;
                            if ($lealtadDetalle->{"a.valor"} == $detalleDepartamentoUSER) {
                                $condicionDepartamentoUSER = true;
                            }

                            break;

                        case "CONDCIUDADUSER":

                            $condicionCiudadUSERcount = $condicionCiudadUSERcount + 1;

                            if ($lealtadDetalle->{"a.valor"} == $detalleCiudadUSER) {
                                $condicionCiudadUSER = true;
                            }

                            break;

                        case "EXPDIA":

                            break;

                        case "EXPFECHA":

                            break;
                        case "REPETIRBONO":

                            if($lealtadDetalle->{"a.valor"} ){
                                print_r("aqui");
                                $puederepetirLealtad = true;
                                // $cumpleCondiciones = false;
                            }

                            break;

                        case "WINBONOID":
                            $ganaLealtadId = $lealtadDetalle->{"a.valor"};
                            $tipolealtad = "WINBONOID";
                            $valor_lealtad=0;

                            break;
                        case "PUNTOS":

                            if($lealtadDetalle->{"a.moneda"} == $Usuario->moneda){

                                $PuntosLealtad = floatval($lealtadDetalle->{"a.valor"});

                                if($puntosTotales < $PuntosLealtad ){
                                    $cumpleCondiciones = false;
                                }

                            }
                            break;
                        case "TIPOSALDO":
                            $tiposaldo = $lealtadDetalle->{"a.valor"};

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

                        default:

                            //   if (stristr($lealtadDetalle->{'lealtad_detalle.tipo'}, 'CONDGAME')) {
                            //
                            // }
                            //
                            //   if (stristr($lealtadDetalle->{'lealtad_detalle.tipo'}, 'ITAINMENT')) {
                            //
                            //
                            //
                            //   }
                            break;
                    }
                }


                if(!$condicionTrigger){
                    $cumpleCondiciones = false;
                    syslog(LOG_WARNING, "NO CUMPLE LEALTAD:".$lealtadId. ' USUARIO'.$usuarioId. " condicionTrigger ");
                }

                if($CodePromo == "") {

                    if ($condicionPaisPVcount > 0) {
                        if (!$condicionPaisPV) {
                            $cumpleCondiciones = false;
                            syslog(LOG_WARNING, "NO CUMPLE LEALTAD:".$lealtadId. ' USUARIO'.$usuarioId. " PAISPV ");
                        }

                    }

                    if ($condicionDepartamentoPVcount > 0) {
                        if (!$condicionDepartamentoPV) {
                            $cumpleCondiciones = false;
                            syslog(LOG_WARNING, "NO CUMPLE LEALTAD:".$lealtadId. ' USUARIO'.$usuarioId. " DEPARTAMENTOPV ");
                        }

                    }

                    if ($condicionCiudadPVcount > 0) {
                        if (!$condicionCiudadPV) {
                            $cumpleCondiciones = false;
                            syslog(LOG_WARNING, "NO CUMPLE LEALTAD:".$lealtadId. ' USUARIO'.$usuarioId. " CIUDADPV ");
                        }

                    }
                }

                if ($condicionPaisUSERcount > 0) {
                    if (!$condicionPaisUSER) {
                        $cumpleCondiciones = false;
                        syslog(LOG_WARNING, "NO CUMPLE LEALTAD:".$lealtadId. ' USUARIO'.$usuarioId. " PAISUSER ");
                    }

                }

                if ($condicionDepartamentoUSERcount > 0) {
                    if (!$condicionDepartamentoUSER) {
                        $cumpleCondiciones = false;
                        syslog(LOG_WARNING, "NO CUMPLE LEALTAD:".$lealtadId. ' USUARIO'.$usuarioId. " DEPARTAMENTOUSER ");
                    }

                }

                if ($condicionCiudadUSERcount > 0) {
                    if (!$condicionCiudadUSER) {
                        $cumpleCondiciones = false;
                        syslog(LOG_WARNING, "NO CUMPLE LEALTAD:".$lealtadId. ' USUARIO'.$usuarioId. " CIUDADUSER ");
                    }

                }
                if($CodePromo == "") {

                    if ($condicionPuntoVentacount > 0) {
                        if (!$condicionPuntoVenta) {
                            $cumpleCondiciones = false;
                            syslog(LOG_WARNING, "NO CUMPLE LEALTAD:".$lealtadId. ' USUARIO'.$usuarioId. " condicionPuntoVenta ");
                        }
                    }

                    if ($condicionmetodoPagocount > 0) {
                        if (!$condicionmetodoPago) {
                            $cumpleCondiciones = false;
                            syslog(LOG_WARNING, "NO CUMPLE LEALTAD:".$lealtadId. ' USUARIO'.$usuarioId. " condicionmetodoPago ");
                        }

                    }
                }

                if ($cumpleCondiciones) {
                    syslog(LOG_WARNING, "CUMPLE LEALTAD:".$lealtadId. ' USUARIO'.$usuarioId. "  ");


                    $Clasificador = new Clasificador("", "LOYALTYEXPIRATIONDATE");
                    $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(),$Clasificador->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
                    $diasExpiracion = $MandanteDetalle->valor;

                    //restar puntos al usuario
                    $valor_lealtad='0';
                    $estado = 'R';

                    $errorId = '0';

                    $idExterno = '0';

                    $mandante = '0';

                    $usucreaId = '0';
                    $externoId = '0';
                    $usumodifId = '0';
                    $version = '0';
                    $apostado = '0';
                    $rollowerRequerido = '0';
                    $this->lealtadId = $lealtadId;


                    $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();
                    $Transaction = $TransaccionJuegoMySqlDAO->getTransaction();

                    $LealtadInternaMySqlDAO = new LealtadInternaMySqlDAO();

                    $LealtadInterna = $LealtadInternaMySqlDAO->load($this->lealtadId);
                    $bonoId = $LealtadInterna->bonoId;
                    if($LealtadInterna->bonoId == 0){
                        $premio = $LealtadInterna->nombre;


                        $UsuarioLealtad = new UsuarioLealtad();

                        $UsuarioLealtad->setUsuarioId($usuarioId);
                        $UsuarioLealtad->setLealtadId($lealtadId);
                        $UsuarioLealtad->setValor($valor_lealtad);
                        $UsuarioLealtad->setValorLealtad($valor_lealtad);
                        $UsuarioLealtad->setValorBase($valor_lealtad);
                        $UsuarioLealtad->setEstado($estado);
                        $UsuarioLealtad->setErrorId($errorId);
                        $UsuarioLealtad->setIdExterno($idExterno);
                        $UsuarioLealtad->setMandante(intval($mandante));
                        $UsuarioLealtad->setUsucreaId(intval($usucreaId));
                        $UsuarioLealtad->setUsumodifId(intval($usumodifId));
                        $UsuarioLealtad->setVersion($version);
                        $UsuarioLealtad->setApostado($apostado);
                        $UsuarioLealtad->setRollowerRequerido($rollowerRequerido);
                        $UsuarioLealtad->setCodigo('0');
                        $UsuarioLealtad->setExternoId($externoId);
                        $UsuarioLealtad->setPremio($premio);

                        if($Form==1){
                            $UsuarioLealtad->setPuntoventaentrega($betShopId);

                        }elseif ($Form==2){
                            $UsuarioLealtad->setPuntoventaentrega(0);
                            $UsuarioLealtad->setNombreusuentrega($Names);
                            $UsuarioLealtad->setApellidousuentrega($Surnames);
                            $UsuarioLealtad->setCedulausuentrega($Identification);
                            $UsuarioLealtad->setTelefonousuentrega($Phone);
                            $UsuarioLealtad->setCiudadusuentrega($City);
                            $UsuarioLealtad->setProvinciausuentrega($Province);
                            $UsuarioLealtad->setDireccionusuentrega($Address);
                            $UsuarioLealtad->setTeamusuentrega($Team);
                        }



                        $UsuarioLealtadMysqlDAO = new UsuarioLealtadMySqlDAO($Transaction);

                        $UsuarioLealtad_Id = $UsuarioLealtadMysqlDAO->insert($UsuarioLealtad);

                        $Usuario->debitPoints($PuntosLealtad, $Transaction);

                        $Usuario->creditPointsRedeemed($PuntosLealtad,$Transaction);

                        $LealtadHistorial = new LealtadHistorial();
                        $LealtadHistorial->setUsuarioId($Usuario->usuarioId);
                        $LealtadHistorial->setDescripcion('');
                        $LealtadHistorial->setMovimiento('S');
                        $LealtadHistorial->setUsucreaId(0);
                        $LealtadHistorial->setUsumodifId(0);
                        $LealtadHistorial->setTipo(50);
                        $LealtadHistorial->setValor($PuntosLealtad);
                        $LealtadHistorial->setExternoId($UsuarioLealtad_Id);
                        $LealtadHistorial->setFechaExp(date("Y-m-d H:i:s",strtotime("+".$diasExpiracion." days")));
                        $LealtadHistorialMySqlDAO = new LealtadHistorialMySqlDAO($Transaction);
                        $LealtadHistorialMySqlDAO->insert($LealtadHistorial);
                        $Transaction->commit();
                        $respuesta["WinLealtad"] = true;
                        $respuesta["WinLealtadId"] = $UsuarioLealtad_Id;

                        $useragent = $_SERVER['HTTP_USER_AGENT'];
                        $jsonServer = json_encode($_SERVER);
                        $serverCodif = base64_encode($jsonServer);
                        $ismobile = '';
                        if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {
                            $ismobile = '1';
                        }
//Detect special conditions devices
                        $iPod = stripos($_SERVER['HTTP_USER_AGENT'],"iPod");
                        $iPhone = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
                        $iPad = stripos($_SERVER['HTTP_USER_AGENT'],"iPad");
                        $Android = stripos($_SERVER['HTTP_USER_AGENT'],"Android");
                        $webOS = stripos($_SERVER['HTTP_USER_AGENT'],"webOS");

//do something with this information
                        if( $iPod || $iPhone ){
                            $ismobile = '1';
                        }else if($iPad){
                            $ismobile = '1';
                        }else if($Android){
                            $ismobile = '1';
                        }
                         exec("php -f " . __DIR__ . "/../crm/AgregarCrm.php " . $UsuarioMandante->usuarioMandante . " " . "LEALTADCRM" . " " . $UsuarioLealtad_Id . " " . $serverCodif . " " . $ismobile . " > /dev/null &");

                    }else {
                        $premio = "Id de Bono:" . $bonoId;


                        $Registro = new Registro('', $Usuario->usuarioId);

                        $CiudadMySqlDAO = new CiudadMySqlDAO();
                        $Ciudad = $CiudadMySqlDAO->load($Registro->ciudadId);
                        $detalles = array(
                            "Depositos" => 0,
                            "DepositoEfectivo" => false,
                            "MetodoPago" => 0,
                            "ValorDeposito" => 0,
                            "PaisPV" => 0,
                            "DepartamentoPV" => 0,
                            "CiudadPV" => 0,
                            "PuntoVenta" => 0,
                            "PaisUSER" => $Usuario->paisId,
                            "DepartamentoUSER" => $Ciudad->deptoId,
                            "CiudadUSER" => $Registro->ciudadId,
                            "MonedaUSER" => $Usuario->moneda,

                        );

                        $BonoInterno = new BonoInterno();

                        $detalles = json_decode(json_encode($detalles));

                        if($bonoId != '' && $bonoId != '0'){
                            $UsuarioBono = new UsuarioBono();

                            $UsuarioBono->setUsuarioId(0);
                            $UsuarioBono->setBonoId($bonoId);
                            $UsuarioBono->setValor(0);
                            $UsuarioBono->setValorBono(0);
                            $UsuarioBono->setValorBase(0);
                            $UsuarioBono->setEstado('L');
                            $UsuarioBono->setErrorId(0);
                            $UsuarioBono->setIdExterno(0);
                            $UsuarioBono->setMandante($Usuario->mandante);
                            $UsuarioBono->setUsucreaId(0);
                            $UsuarioBono->setUsumodifId(0);
                            $UsuarioBono->setApostado(0);
                            $UsuarioBono->setRollowerRequerido(0);
                            $UsuarioBono->setCodigo('');
                            $UsuarioBono->setVersion('2');
                            $UsuarioBono->setExternoId(0);

                            $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($Transaction);

                            $UsuarioBonoMysqlDAO->insert($UsuarioBono);

                        }
                        //print_r($bonoId);
                        $responseBonus = $BonoInterno->agregarBonoFree($bonoId, $Usuario->usuarioId, $Usuario->mandante, $detalles, '', '', $Transaction,false,true);




                        if($responseBonus->WinBonus == true){


                            $UsuarioLealtad = new UsuarioLealtad();

                            $UsuarioLealtad->setUsuarioId($usuarioId);
                            $UsuarioLealtad->setLealtadId($lealtadId);
                            $UsuarioLealtad->setValor($valor_lealtad);
                            $UsuarioLealtad->setValorLealtad($valor_lealtad);
                            $UsuarioLealtad->setValorBase($valor_lealtad);
                            $UsuarioLealtad->setEstado('D');
                            $UsuarioLealtad->setErrorId($errorId);
                            $UsuarioLealtad->setIdExterno($idExterno);
                            $UsuarioLealtad->setMandante(intval($mandante));
                            $UsuarioLealtad->setUsucreaId(intval($usucreaId));
                            $UsuarioLealtad->setUsumodifId(intval($usumodifId));
                            $UsuarioLealtad->setVersion($version);
                            $UsuarioLealtad->setApostado($apostado);
                            $UsuarioLealtad->setRollowerRequerido($rollowerRequerido);
                            $UsuarioLealtad->setCodigo('0');
                            $UsuarioLealtad->setExternoId($externoId);
                            $UsuarioLealtad->setPremio($premio);

                            if($Form==1){
                                $UsuarioLealtad->setPuntoventaentrega($betShopId);

                            }elseif ($Form==2){
                                $UsuarioLealtad->setPuntoventaentrega(0);
                                $UsuarioLealtad->setNombreusuentrega($Names);
                                $UsuarioLealtad->setApellidousuentrega($Surnames);
                                $UsuarioLealtad->setCedulausuentrega($Identification);
                                $UsuarioLealtad->setTelefonousuentrega($Phone);
                                $UsuarioLealtad->setCiudadusuentrega($City);
                                $UsuarioLealtad->setProvinciausuentrega($Province);
                                $UsuarioLealtad->setDireccionusuentrega($Address);
                                $UsuarioLealtad->setTeamusuentrega($Team);
                            }


                            $UsuarioLealtadMysqlDAO = new UsuarioLealtadMySqlDAO($Transaction);

                            $UsuarioLealtad_Id = $UsuarioLealtadMysqlDAO->insert($UsuarioLealtad);
                            $LealtadHistorial = new LealtadHistorial();
                            $LealtadHistorial->setUsuarioId($Usuario->usuarioId);
                            $LealtadHistorial->setDescripcion('');
                            $LealtadHistorial->setMovimiento('S');
                            $LealtadHistorial->setUsucreaId(0);
                            $LealtadHistorial->setUsumodifId(0);
                            $LealtadHistorial->setTipo(50);
                            $LealtadHistorial->setValor($PuntosLealtad);
                            $LealtadHistorial->setExternoId($UsuarioLealtad_Id);
                            $LealtadHistorial->setFechaExp(date("Y-m-d H:i:s", strtotime("+" . $diasExpiracion . " days")));
                            $LealtadHistorialMySqlDAO = new LealtadHistorialMySqlDAO($Transaction);
                            $LealtadHistorialMySqlDAO->insert($LealtadHistorial);
                            $respuesta["WinLealtad"] = true;

                            $Usuario->debitPoints($PuntosLealtad, $Transaction);

                            $Usuario->creditPointsRedeemed($PuntosLealtad,$Transaction);

                            $Transaction->commit();
                            $respuesta["WinLealtad"] = true;
                            $respuesta["WinLealtadId"] = $UsuarioLealtad_Id;

                            $useragent = $_SERVER['HTTP_USER_AGENT'];
                            $jsonServer = json_encode($_SERVER);
                            $serverCodif = base64_encode($jsonServer);
                            $ismobile = '';
                            if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {
                                $ismobile = '1';
                            }
//Detect special conditions devices
                            $iPod = stripos($_SERVER['HTTP_USER_AGENT'],"iPod");
                            $iPhone = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
                            $iPad = stripos($_SERVER['HTTP_USER_AGENT'],"iPad");
                            $Android = stripos($_SERVER['HTTP_USER_AGENT'],"Android");
                            $webOS = stripos($_SERVER['HTTP_USER_AGENT'],"webOS");

//do something with this information
                            if( $iPod || $iPhone ){
                                $ismobile = '1';
                            }else if($iPad){
                                $ismobile = '1';
                            }else if($Android){
                                $ismobile = '1';
                            }
                            exec("php -f " . __DIR__ . "/../crm/AgregarCrm.php " . $UsuarioMandante->usuarioMandante . " " . "LEALTADCRM" . " " . $UsuarioLealtad_Id . " " . $serverCodif . " " . $ismobile . " > /dev/null &");

                        }else{
                            if($Transaction->getConnection()->inTransaction()){
                                $Transaction->rollback();
                            }

                        }
                    }



                    /* if ($puederepetirLealtad) {
                         $lealtadElegido = $lealtad->{"a.lealtad_id"};

                     } else {
                         $sqlRepiteLealtad = "select * from usuario_lealtad a where a.lealtad_id='" . $lealtad->{"a.lealtad_id"} . "' AND a.usuario_id = '" . $usuarioId . "'";
                         $repiteLealtad = $this->execQuery($transaccion,$sqlRepiteLealtad);

                         if ((!$puederepetirLealtad && oldCount($repiteLealtad) == 0)) {
                             $lealtadElegido = $lealtad->{"a.lealtad_id"};
                         } else {
                             $cumpleCondiciones = false;
                         }

                     }*/


                }

            }

        }





        return json_decode(json_encode($respuesta));

    }

    /**
     * Agregar un lealtad en la base de datos
     *
     *
     * @param String tipoLealtad tipoLealtad
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
    public function agregarLealtadFree($lealtadid, $usuarioId, $mandante, $detalles,$ejecutarSQL,$codebonus,$transaccion)
    {

        //Verificamos Detalles a tomar en cuenta al momento de verificar si puede ganar un lealtad
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
        $lealtadElegido = 0;
        $lealtadTieneRollower = false;
        $rollowerLealtad = 0;
        $rollowerDeposito = 0;

        //Obtenemos todos los lealtads disponibles
        $sqlLealtads = "select a.lealtad_id,a.tipo from lealtad_interna a where a.mandante=" . $mandante . " and  a.estado='A' and a.lealtad_id='".$lealtadid."'";

        $lealtadsDisponibles = $this->execQuery($transaccion,$sqlLealtads);

        foreach ($lealtadsDisponibles as $lealtad) {

            if (!$cumpleCondiciones ) {

                //Obtenemos todos los detalles del lealtad
                $sqlDetalleLealtad = "select * from lealtad_detalle a where a.lealtad_id='" . $lealtad->{"a.lealtad_id"} . "' AND (moneda='' OR moneda='".$detalleMonedaUSER."') ";

                $lealtadDetalles = $this->execQuery($transaccion,$sqlDetalleLealtad);

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

                $puederepetirLealtad=false ;
                $ganaLealtadId=0;
                $Usuario = new Usuario($usuarioId);
                $puntos = $Usuario->puntosLealtad;
                $puntosAexpirar = $Usuario->getPuntosAexpirar();
                $puntosTotales = $puntos + $puntosAexpirar;
                $PuntosLealtad = "";
                $maximopago = 0;
                $maximodeposito = 0;
                $minimodeposito = 0;
                $valorlealtad = 0;
                $tipoproducto = 0;
                $tipolealtad = "";
                $tipolealtad2= $lealtad->{"a.tipo"};
                $lealtadTieneRollower = false;

                $PuntosLealtad = "";



                foreach ($lealtadDetalles as $lealtadDetalle) {


                    switch ($lealtadDetalle->{"a.tipo"}) {


                        case "TIPOPRODUCTO":
                            $tipoproducto = $lealtadDetalle->{"a.valor"};

                            break;

                        case "CANTDEPOSITOS":
                            if ($detalleDepositos != ($lealtadDetalle->{"a.valor"} - 1) && $lealtad->{"a.tipo"}==2) {

                                $cumpleCondiciones = false;

                            }

                            break;

                        case "CONDEFECTIVO":
                            if ($detalleDepositoEfectivo) {
                                if (($lealtadDetalle->{"a.valor"} == "true")) {
                                    $condicionmetodoPago = true;
                                }
                            } else {
                                if (($lealtadDetalle->{"a.valor"} != "true")) {
                                    $condicionmetodoPago = true;
                                }
                            }
                            $condicionmetodoPagocount++;


                            break;


                        case "PORCENTAJE":
                            $tipolealtad = "PORCENTAJE";
                            $valorlealtad = $lealtadDetalle->{"a.valor"};

                            break;


                        case "NUMERODEPOSITO":

                            break;

                        case "MAXJUGADORES":

                            break;


                        case "MAXPAGO":
                            $maximopago = $lealtadDetalle->{"a.valor"};

                            break;

                        case "MAXDEPOSITO":

                            $maximodeposito = $lealtadDetalle->{"a.valor"};
                            if($lealtadDetalle->{"a.moneda"} == $detalleMonedaUSER) {

                                if ($detalleValorDeposito > $maximodeposito) {
                                    $cumpleCondiciones = false;
                                }
                            }
                            break;

                        case "MINDEPOSITO":

                            $minimodeposito = $lealtadDetalle->{"a.valor"};

                            if($lealtadDetalle->{"a.moneda"} == $detalleMonedaUSER) {

                                if ($detalleValorDeposito < $minimodeposito) {
                                    $cumpleCondiciones = false;
                                }
                            }

                            break;

                        case "VALORBONO":

                            if($lealtadDetalle->{"a.moneda"} == $detalleMonedaUSER) {

                                $valorlealtad = $lealtadDetalle->{"a.valor"};
                                $tipolealtad = "VALOR";
                            }
                            break;

                        case "CONDPAYMENT":

                            if ($detalleDepositoMetodoPago == $lealtadDetalle->{"a.valor"}) {
                                $condicionmetodoPago = true;
                            }
                            $condicionmetodoPagocount++;

                            break;

                        case "CONDPAISPV":

                            $condicionPaisPVcount = $condicionPaisPVcount + 1;
                            if ($lealtadDetalle->{"a.valor"} == $detallePaisPV) {
                                $condicionPaisPV = true;
                            }

                            break;

                        case "CONDDEPARTAMENTOPV":

                            $condicionDepartamentoPVcount = $condicionDepartamentoPVcount + 1;
                            if ($lealtadDetalle->{"a.valor"} == $detalleDepartamentoPV) {
                                $condicionDepartamentoPV = true;
                            }

                            break;

                        case "CONDCIUDADPV":

                            $condicionCiudadPVcount = $condicionCiudadPVcount + 1;

                            if ($lealtadDetalle->{"a.valor"} == $detalleCiudadPV) {
                                $condicionCiudadPV = true;
                            }

                            break;

                        case "CONDPAISUSER":

                            $condicionPaisUSERcount = $condicionPaisUSERcount + 1;

                            if ($lealtadDetalle->{"a.valor"} == $detallePaisUSER) {

                                $condicionPaisUSER = true;
                            }

                            break;

                        case "CONDDEPARTAMENTOUSER":

                            $condicionDepartamentoUSERcount = $condicionDepartamentoUSERcount + 1;
                            if ($lealtadDetalle->{"a.valor"} == $detalleDepartamentoUSER) {
                                $condicionDepartamentoUSER = true;
                            }

                            break;

                        case "CONDCIUDADUSER":

                            $condicionCiudadUSERcount = $condicionCiudadUSERcount + 1;

                            if ($lealtadDetalle->{"a.valor"} == $detalleCiudadUSER) {
                                $condicionCiudadUSER = true;
                            }

                            break;

                        case "CONDPUNTOVENTA":

                            $condicionPuntoVentacount = $condicionPuntoVentacount + 1;

                            if ($lealtadDetalle->{"a.valor"} == $detallePuntoVenta) {
                                $condicionPuntoVenta = true;
                            }

                            break;

                        case "EXPDIA":

                            break;

                        case "EXPFECHA":

                            break;

                        case "WFACTORBONO":
                            $lealtadTieneRollower = true;

                            $rollowerLealtad = $lealtadDetalle->{"a.valor"};

                            break;

                        case "WFACTORDEPOSITO":
                            $lealtadTieneRollower = true;
                            $rollowerDeposito = $lealtadDetalle->{"a.valor"};

                            break;



                        case "VALORROLLOWER":
                            if($lealtadDetalle->{"a.moneda"} == $detalleMonedaUSER) {

                                $lealtadTieneRollower = true;
                                $rollowerValor = $lealtadDetalle->{"a.valor"};
                            }
                            break;

                        case "REPETIRBONO":

                            if($lealtadDetalle->{"a.valor"} ){
                                $puederepetirLealtad = true;
                            }

                            break;

                        case "WINBONOID":
                            $ganaLealtadId = $lealtadDetalle->{"a.valor"};

                            break;

                        case "PUNTOS":
                            if($lealtadDetalle->{"a.moneda"} == $Usuario->moneda ){

                                $PuntosLealtad = intval($lealtadDetalle->{"a.valor"});

                                if($puntosTotales < $PuntosLealtad){
                                    $cumpleCondiciones = false;
                                }
                            }
                            break;

                        case "TIPOSALDO":
                            $tiposaldo = $lealtadDetalle->{"a.valor"};

                            break;

                        case "LIVEORPREMATCH":

                            break;

                        case "MINSELCOUNT":

                            break;

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

                            //   if (stristr($lealtadDetalle->{'lealtad_detalle.tipo'}, 'CONDGAME')) {
                            //
                            // }
                            //
                            //   if (stristr($lealtadDetalle->{'lealtad_detalle.tipo'}, 'ITAINMENT')) {
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

                    if($puederepetirLealtad){

                        $lealtadElegido = $lealtad->{"a.lealtad_id"};

                    }else{
                        $sqlRepiteLealtad = "select * from usuario_lealtad a where a.lealtad_id='" . $lealtad->{"a.lealtad_id"} . "' AND a.usuario_id = '" .$usuarioId ."'";
                        $repiteLealtad = $this->execQuery($transaccion,$sqlRepiteLealtad);

                        if((!$puederepetirLealtad && oldCount($repiteLealtad) == 0)){
                            $lealtadElegido = $lealtad->{"a.lealtad_id"};
                        }else{
                            $cumpleCondiciones = false;
                        }
                    }


                }



            }


        }

        $respuesta = array();
        $respuesta["Lealtad"] = 0;
        $respuesta["WinLealtad"] = false;



        if ($lealtadElegido != 0 && $tipolealtad2 != "") {


        }
        if ($tipolealtad == "PORCENTAJE") {
            $valor_lealtad = ($detalleValorDeposito) * ($valorlealtad) / 100;

            if ($valor_lealtad > $maximopago) {
                $valor_lealtad = $maximopago;
            }

        } elseif ($tipolealtad == "VALOR") {

            $valor_lealtad = $valorlealtad;

        }

        $valorBase = $detalleValorDeposito;

        $strSql = array();
        $contSql = 0;
        $estadoLealtad = 'A';
        $rollowerRequerido = 0;

        if (!$lealtadTieneRollower) {




        } else {
            if ($rollowerDeposito ) {
                $rollowerRequerido = $rollowerRequerido + ($rollowerDeposito * $detalleValorDeposito);
            }

            if ($rollowerLealtad ) {
                $rollowerRequerido = $rollowerRequerido + ($rollowerLealtad * $valor_lealtad);

            }
            if($rollowerValor){
                $rollowerRequerido = $rollowerRequerido + ($rollowerValor);


            }

        }

        $strCodeLealtad="";

        if($codebonus != ""){
            $strCodeLealtad=" AND a.codigo ='" .$codebonus."'";

        }


        if ($tipolealtad2 == "5"){

            $sqlLealtadsFree = "select a.lealtad_id,a.usuario_id,a.estado from usuario_lealtad a INNER JOIN lealtad_interna b ON(a.lealtad_id = b.lealtad_id) where  a.estado='L' and a.lealtad_id='".$lealtadid ."'" . $strCodeLealtad;

            $lealtadsFree = $this->execQuery($transaccion,$sqlLealtadsFree);

            $ganoLealtadBool=false;

            foreach ($lealtadsFree as $lealtadF) {

                $sqlLealtadsFree = "select a.usulealtad_id from usuario_lealtad a INNER JOIN lealtad_interna b ON(a.lealtad_id = b.lealtad_id) where  a.estado='L' and a.lealtad_id='".$lealtadid ."'". $strCodeLealtad;
                $lealtadsFreeLibres = $this->execQuery($transaccion,$sqlLealtadsFree);
                foreach ($lealtadsFreeLibres as $lealtadLibre) {
                    if(!$ganoLealtadBool){
                        if($transaccion==""){
                            $contSql = $contSql + 1;
                            $strSql[$contSql] = "UPDATE usuario_lealtad a SET a.usuario_id='".$usuarioId."',a.fecha_crea = '".date('Y-m-d H:i:s')."',a.estado='A' WHERE a.usuario_id='0' AND a.estado='L' AND a.usulealtad_id='".$lealtadLibre->usulealtad_id."'";
                            $ganoLealtadBool=true;

                        }else{
                            $sqlstr= "UPDATE usuario_lealtad a SET a.usuario_id='".$usuarioId."',a.fecha_crea = '".date('Y-m-d H:i:s')."',a.estado='A' WHERE a.usuario_id='0' AND a.estado='L' AND a.usulealtad_id='".$lealtadLibre->usulealtad_id."'";

                            $q = $this->execUpdate($transaccion,$sqlstr);
                            if($q>0){
                                $ganoLealtadBool=true;

                            }else{
                                $ganoLealtadBool=false;

                            }

                        }

                    }
                }

            }
        }elseif ($tipolealtad2 == "6"){
            $sqlLealtadsFree = "select a.lealtad_id from usuario_lealtad a INNER JOIN lealtad_interna b ON(a.lealtad_id = b.lealtad_id) where  a.estado='L' and a.lealtad_id='".$lealtadid ."'". $strCodeLealtad;
            $lealtadsFree = $this->execQuery($transaccion,$sqlLealtadsFree);

            $ganoLealtadBool=false;

            foreach ($lealtadsFree as $lealtadF) {

                $sqlLealtadsFree = "select a.usulealtad_id from usuario_lealtad a INNER JOIN lealtad_interna b ON(a.lealtad_id = b.lealtad_id) where  a.estado='L' and a.lealtad_id='".$lealtadid ."'". $strCodeLealtad;
                $lealtadsFreeLibres = $this->execQuery($transaccion,$sqlLealtadsFree);
                foreach ($lealtadsFreeLibres as $lealtadLibre) {
                    if(!$ganoLealtadBool){
                        if($transaccion=="") {

                            $contSql = $contSql + 1;
                            $strSql[$contSql] = "UPDATE usuario_lealtad a SET a.usuario_id='" . $usuarioId . "',a.fecha_crea = '" . date('Y-m-d H:i:s') . "', a.estado='A' WHERE a.usuario_id='0' AND a.estado='L' AND a.usulealtad_id='" . $lealtadLibre->usulealtad_id . "'";
                            $ganoLealtadBool = true;
                        }else{
                            $sqlstr= "UPDATE usuario_lealtad a SET a.usuario_id='" . $usuarioId . "',a.fecha_crea = '" . date('Y-m-d H:i:s') . "', a.estado='A' WHERE a.usuario_id='0' AND a.estado='L' AND a.usulealtad_id='" . $lealtadLibre->usulealtad_id . "'";

                            $q = $this->execUpdate($transaccion,$sqlstr);
                            if($q>0){
                                $ganoLealtadBool=true;

                            }else{
                                $ganoLealtadBool=false;

                            }

                        }

                    }
                }

            }
        }elseif ($tipolealtad2 == "3"){

            $valor_lealtad = $maximopago;


            $ganoLealtadBool=false;


            $sqlLealtadsFree = "select a.usulealtad_id from usuario_lealtad a INNER JOIN lealtad_interna b ON(a.lealtad_id = b.lealtad_id) where  a.estado='L' and a.lealtad_id='".$lealtadid ."'". $strCodeLealtad;
            $lealtadsFreeLibres = $this->execQuery($transaccion,$sqlLealtadsFree);

            foreach ($lealtadsFreeLibres as $lealtadLibre) {
                if(!$ganoLealtadBool){

                    if (!$lealtadTieneRollower) {
                        $estadoLealtad = 'R';
                    }else {
                        if ($rollowerDeposito) {
                            $rollowerRequerido = $rollowerRequerido + ($rollowerDeposito * $detalleValorDeposito);
                        }

                        if ($rollowerLealtad) {
                            $rollowerRequerido = $rollowerRequerido + ($rollowerLealtad * $valor_lealtad);

                        }
                        if($rollowerValor){
                            $rollowerRequerido = $rollowerRequerido + ($rollowerValor);


                        }

                    }



                    if($transaccion == '') {

                        if (!$lealtadTieneRollower) {


                            if($ganaLealtadId ==0){
                                switch ($tiposaldo){
                                    case 0:
                                        $contSql = $contSql + 1;
                                        $strSql[$contSql] = "update registro set creditos_base_ant=creditos_base,creditos_base=creditos_base+" . $valor_lealtad . " where mandante=" . $mandante . " and usuario_id=" . $usuarioId;


                                        $estadoLealtad = 'R';

                                        break;

                                    case 1:
                                        $contSql = $contSql + 1;
                                        $strSql[$contSql] = "update registro set creditos_ant=creditos,creditos=creditos+" . $valor_lealtad . " where mandante=" . $mandante . " and usuario_id=" . $usuarioId;


                                        $estadoLealtad = 'R';

                                        break;

                                    case 2:
                                        $contSql = $contSql + 1;
                                        $strSql[$contSql] = "update registro set saldo_especial=saldo_especial+" . $valor_lealtad . " where mandante=" . $mandante . " and usuario_id=" . $usuarioId;

                                        $estadoLealtad = 'R';
                                        $SumoSaldo=true;

                                        break;

                                }

                            }else{

                                $resp=$this->agregarLealtadFree($ganaLealtadId,$usuarioId,$mandante,$detalles,'','',$transaccion);

                                if($transaccion == ""){
                                    foreach ($resp->queries as $val) {
                                        $contSql = $contSql + 1;
                                        $strSql[$contSql]=$val;
                                    }
                                }

                                $estadoLealtad = 'R';

                            }

                        } else {
                            if ($rollowerDeposito) {
                                $rollowerRequerido = $rollowerRequerido + ($rollowerDeposito * $detalleValorDeposito);
                            }

                            if ($rollowerLealtad) {
                                $rollowerRequerido = $rollowerRequerido + ($rollowerLealtad * $valor_lealtad);

                            }
                            if($rollowerValor){
                                $rollowerRequerido = $rollowerRequerido + ($rollowerValor);


                            }
                            $contSql = $contSql + 1;
                            $strSql[$contSql] = "update registro,lealtad_interna set registro.creditos_lealtad=registro.creditos_lealtad+" . $valor_lealtad . " where registro.mandante=" . $mandante . " and registro.usuario_id=" . $usuarioId." AND lealtad_id ='".$lealtadElegido."'";

                        }

                        $contSql = $contSql + 1;
                        $strSql[$contSql] = "UPDATE usuario_lealtad a SET a.usuario_id='" . $usuarioId . "',a.fecha_crea = '" . date('Y-m-d H:i:s') . "',a.rollower_requerido='" . $rollowerRequerido . "',a.valor_lealtad='" . $valor_lealtad . "',a.valor='" . $valor_lealtad . "', a.estado='" . $estadoLealtad . "' WHERE a.usuario_id='0' AND a.estado='L' AND a.usulealtad_id='" . $lealtadLibre->usulealtad_id . "'";

                        $contSql = $contSql + 1;
                        $strSql[$contSql] = "INSERT INTO lealtad_log (usuario_id,tipo,valor,estado,id_externo,mandante,transaccion_id,tipolealtad_id,fecha_crea,fecha_cierre) SELECT a.usuario_id,CASE WHEN b.tipo = 2 THEN 'D' ELSE 'F' END,a.valor,'L',a.usulealtad_id,0,'0',4,now(),now()  FROM  usuario_lealtad a INNER JOIN lealtad_interna  b ON (b.lealtad_id = a.lealtad_id)  WHERE a.usulealtad_id = " . $lealtadLibre->usulealtad_id . " AND a.apostado >= a.rollower_requerido";

                        $ganoLealtadBool = true;

                    } else{
                        $sqlstr= "UPDATE usuario_lealtad a SET a.usuario_id='" . $usuarioId . "',a.fecha_crea = '" . date('Y-m-d H:i:s') . "',a.rollower_requerido='" . $rollowerRequerido . "',a.valor_lealtad='" . $valor_lealtad . "',a.valor='" . $valor_lealtad . "', a.estado='" . $estadoLealtad . "' WHERE a.usuario_id='0' AND a.estado='L' AND a.usulealtad_id='" . $lealtadLibre->usulealtad_id . "'";

                        $q = $this->execUpdate($transaccion,$sqlstr);
                        if($q>0) {

                            $sqlstr= "INSERT INTO lealtad_log (usuario_id,tipo,valor,estado,id_externo,mandante,transaccion_id,tipolealtad_id,fecha_crea,fecha_cierre) SELECT a.usuario_id,CASE WHEN b.tipo = 2 THEN 'D' ELSE 'F' END,a.valor,'L',a.usulealtad_id,0,'0',4,now(),now()  FROM  usuario_lealtad a INNER JOIN lealtad_interna  b ON (b.lealtad_id = a.lealtad_id)  WHERE a.usulealtad_id = " . $lealtadLibre->usulealtad_id . " AND a.apostado >= a.rollower_requerido";

                            $q = $this->execUpdate($transaccion,$sqlstr);


                            if (!$lealtadTieneRollower) {


                                if($ganaLealtadId ==0){
                                    switch ($tiposaldo){
                                        case 0:
                                            $sqlstr = "update registro set creditos_base_ant=creditos_base,creditos_base=creditos_base+" . $valor_lealtad . " where mandante=" . $mandante . " and usuario_id=" . $usuarioId;
                                            $q = $this->execUpdate($transaccion,$sqlstr);


                                            $estadoLealtad = 'R';

                                            break;

                                        case 1:
                                            $sqlstr = "update registro set creditos_ant=creditos,creditos=creditos+" . $valor_lealtad . " where mandante=" . $mandante . " and usuario_id=" . $usuarioId;

                                            $q = $this->execUpdate($transaccion,$sqlstr);

                                            $estadoLealtad = 'R';

                                            break;

                                        case 2:
                                            $sqlstr = "update registro set saldo_especial=saldo_especial+" . $valor_lealtad . " where mandante=" . $mandante . " and usuario_id=" . $usuarioId;
                                            $q = $this->execUpdate($transaccion,$sqlstr);

                                            $estadoLealtad = 'R';
                                            $SumoSaldo=true;

                                            break;

                                    }

                                }else{

                                    $resp=$this->agregarLealtadFree($ganaLealtadId,$usuarioId,$mandante,$detalles,'','',$transaccion);

                                    if($transaccion == ""){
                                        foreach ($resp->queries as $val) {
                                            $contSql = $contSql + 1;
                                            $strSql[$contSql]=$val;
                                        }
                                    }

                                    $estadoLealtad = 'R';

                                }

                            } else {
                                if ($rollowerDeposito) {
                                    $rollowerRequerido = $rollowerRequerido + ($rollowerDeposito * $detalleValorDeposito);
                                }

                                if ($rollowerLealtad) {
                                    $rollowerRequerido = $rollowerRequerido + ($rollowerLealtad * $valor_lealtad);

                                }
                                if($rollowerValor){
                                    $rollowerRequerido = $rollowerRequerido + ($rollowerValor);


                                }

                                $sqlstr = "update registro,lealtad_interna set registro.creditos_lealtad=registro.creditos_lealtad+" . $valor_lealtad . " where registro.mandante=" . $mandante . " and registro.usuario_id=" . $usuarioId." AND lealtad_id ='".$lealtadElegido."'";

                                $q = $this->execUpdate($transaccion,$sqlstr);

                            }

                            $ganoLealtadBool=true;


                        }else{
                            $ganoLealtadBool=false;

                        }
                    }



                }

            }

            $respuesta["WinLealtad"] = true;
            $respuesta["Lealtad"] = $lealtadElegido;
            $respuesta["queries"] = $strSql;
            $respuesta["estado"] = $estadoLealtad;



            if($transaccion != ""){

            }else{
                if($ejecutarSQL){
                    foreach ($respuesta["queries"] as $querie){

                        $transaccionNueva=false;
                        if($transaccion == ''){
                            $LealtadInternaMySqlDAO = new LealtadInternaMySqlDAO();
                            $transaccion= $LealtadInternaMySqlDAO->getTransaction();
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
     * Verficiar rollower en el lealtad
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
    public function verificarLealtadRollower($usuarioId, $detalles,$tipoProducto,$ticketId)
    {

        //Verificamos Detalles a tomar en cuenta al momento de verificar y aumentar rollower
        $detalleTipoApuesta = $detalles->TipoApuesta;
        $detalleSelecciones = $detalles->Selecciones;
        $detalleJuegosCasino = $detalles->JuegosCasino;
        $detalleValorApuesta = $detalles->ValorApuesta;
        $detalleCuotaTotal = 1;

        $respuesta = array();
        $respuesta["Lealtad"] = 0;
        $respuesta["WinLealtad"] = false;



        if(($tipoProducto == "SPORT" || $tipoProducto == "CASINO" ) && $usuarioId != ""){
            $lealtadid=0;
            $usulealtad_id=0;
            $valorASumar=0;

            //Obtenemos todos los lealtads disponibles
            $sqlLealtad = "select a.usulealtad_id,a.lealtad_id,a.apostado,a.rollower_requerido,a.fecha_crea,lealtad_interna.condicional,lealtad_interna.tipo from usuario_lealtad a INNER JOIN lealtad_interna ON (lealtad_interna.lealtad_id = a.lealtad_id ) where  a.estado='A' AND (lealtad_interna.tipo = 2 OR lealtad_interna.tipo = 3) AND a.usuario_id='" . $usuarioId . "'";
            $lealtadsDisponibles = $this->execQuery($sqlLealtad);

            if(oldCount($lealtadsDisponibles) > 0){
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

            foreach ($lealtadsDisponibles as $lealtad) {
                if ($lealtadid == 0) {

                    //Obtenemos todos los detalles del lealtad
                    $sqlDetalleLealtad = "select * from lealtad_detalle a where a.lealtad_id='" . $lealtad->{"a.lealtad_id"} . "' AND (moneda='' OR moneda='".$detalleMonedaUSER."') ";
                    $lealtadDetalles = $this->execQuery($sqlDetalleLealtad);




                    //Inicializamos variables
                    $cumplecondicion = true;
                    $cumplecondicionproducto = false;
                    $condicionesproducto = 0;
                    $lealtadid = 0;
                    $valorapostado = 0;
                    $valorrequerido = 0;
                    $valorASumar = 0;

                    $sePuedeSimples=0;
                    $sePuedeCombinadas=0;
                    $minselcount=0;

                    $ganaLealtadId=0;
                    $tipolealtad="";
                    $ganaLealtadId=0;

                    if ($lealtad->{"a.condicional"} == 'NA' || $lealtad->{"a.condicional"} == '') {
                        $tipocomparacion = "OR";

                    } else {
                        $tipocomparacion = $lealtad->{"a.condicional"};

                    }


                    foreach ($lealtadDetalles as $lealtadDetalle) {

                        switch ($lealtadDetalle->{"a.tipo"}) {

                            case "TIPOPRODUCTO":

                                $tipoProducto = $lealtadDetalle->{"a.valor"};
                                break;

                            case "EXPDIA":
                                $fechaLealtad = date('Y-m-d H:i:ss', strtotime($lealtad->{"fecha_crea"} . ' + ' . $lealtadDetalle->{"a.valor"} . ' days'));
                                $fecha_actual = date("Y-m-d H:i:ss", time());

                                if ($fechaLealtad < $fecha_actual) {
                                    $cumplecondicion = false;
                                }

                                break;

                            case "EXPFECHA":
                                $fechaLealtad = date('Y-m-d H:i:ss', strtotime($lealtadDetalle->{"a.valor"}));
                                $fecha_actual = strtotime(date("Y-m-d H:i:ss", time()));

                                if ($fechaLealtad < $fecha_actual) {
                                    $cumplecondicion = false;
                                }
                                break;


                            case "LIVEORPREMATCH":


                                if ($lealtadDetalle->{"a.valor"} == 2) {
                                    if($betmode == "PreLive") {
                                        $cumplecondicionproducto = true;

                                    }else{
                                        $cumplecondicionproducto = false;


                                    }

                                }

                                if ($lealtadDetalle->{"a.valor"} == 1) {
                                    if($betmode == "Live") {
                                        $cumplecondicionproducto = true;

                                    }else{
                                        $cumplecondicionproducto = false;


                                    }

                                }

                                if ($lealtadDetalle->{"a.valor"} == 0) {
                                    /*if($betmode == "Mixed") {
                                        $cumplecondicionproducto = true;

                                    }else{
                                        $cumplecondicionproducto = false;


                                    }*/

                                }

                                break;

                            case "MINSELCOUNT":
                                $minselcount=$lealtadDetalle->{"a.valor"};

                                if ($lealtadDetalle->{"a.valor"} > oldCount($detalleSelecciones)) {
                                    //$cumplecondicion = false;

                                }

                                break;

                            case "MINSELPRICE":

                                foreach ($detalleSelecciones as $item) {
                                    if ($lealtadDetalle->{"a.valor"} > $item->Cuota) {
                                        $cumplecondicion = false;

                                    }
                                }


                                break;


                            case "MINSELPRICETOTAL":

                                if ($lealtadDetalle->{"a.valor"} > $detalleCuotaTotal) {
                                    $cumplecondicion = false;

                                }


                                break;

                            case "MINBETPRICE":


                                if ($lealtadDetalle->{"a.valor"} > $detalleValorApuesta) {
                                    $cumplecondicion = false;

                                }

                                break;

                            case "WINBONOID":
                                $ganaLealtadId = $lealtadDetalle->{"a.valor"};
                                $tipolealtad = "WINBONOID";
                                $valor_lealtad=0;

                                break;

                            case "TIPOSALDO":
                                $tiposaldo = $lealtadDetalle->{"a.valor"};

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
                                        if ($lealtadDetalle->{"a.valor"} == $item->Deporte) {
                                            $cumplecondicionproducto = true;

                                        }
                                    } elseif ($tipocomparacion == "AND") {
                                        if ($lealtadDetalle->{"a.valor"} != $item->Deporte) {
                                            $cumplecondicionproducto = false;


                                        }

                                        if ($condicionesproducto == 0) {
                                            if ($lealtadDetalle->{"a.valor"} == $item->Deporte) {
                                                $cumplecondicionproducto = true;
                                            }
                                        } else {
                                            if ($lealtadDetalle->{"a.valor"} == $item->Deporte && $cumplecondicionproducto) {
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
                                        if ($lealtadDetalle->{"a.valor"} == $item->Liga) {
                                            $cumplecondicionproducto = true;

                                        }
                                    } elseif ($tipocomparacion == "AND") {
                                        if ($lealtadDetalle->{"a.valor"} != $item->Liga) {
                                            $cumplecondicionproducto = false;

                                        }

                                        if ($condicionesproducto == 0) {
                                            if ($lealtadDetalle->{"a.valor"} == $item->Liga) {
                                                $cumplecondicionproducto = true;
                                            }
                                        } else {
                                            if ($lealtadDetalle->{"a.valor"} == $item->Liga && $cumplecondicionproducto) {
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
                                        if ($lealtadDetalle->{"a.valor"} == $item->Evento) {
                                            $cumplecondicionproducto = true;

                                        }
                                    } elseif ($tipocomparacion == "AND") {
                                        if ($lealtadDetalle->{"a.valor"} != $item->Evento) {
                                            $cumplecondicionproducto = false;

                                        }

                                        if ($condicionesproducto == 0) {

                                            if ($lealtadDetalle->{"a.valor"} == $item->Evento) {
                                                $cumplecondicionproducto = true;
                                            }
                                        } else {

                                            if ($lealtadDetalle->{"a.valor"} == $item->Evento && $cumplecondicionproducto) {
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
                                        if ($lealtadDetalle->{"a.valor"} == $item->DeporteMercado) {
                                            $cumplecondicionproducto = true;

                                        }
                                    } elseif ($tipocomparacion == "AND") {
                                        if ($lealtadDetalle->{"a.valor"} != $item->DeporteMercado) {
                                            $cumplecondicionproducto = false;

                                        }

                                        if ($condicionesproducto == 0) {
                                            if ($lealtadDetalle->{"a.valor"} == $item->DeporteMercado) {
                                                $cumplecondicionproducto = true;
                                            }
                                        } else {
                                            if ($lealtadDetalle->{"a.valor"} == $item->DeporteMercado && $cumplecondicionproducto) {
                                                $cumplecondicionproducto = true;

                                            }
                                        }

                                    }

                                }

                                $condicionesproducto++;

                                break;

                            case "ITAINMENT82":

                                if($lealtadDetalle->{"a.valor"} ==1){
                                    $sePuedeSimples =1;

                                }
                                if($lealtadDetalle->{"a.valor"} ==2){
                                    $sePuedeCombinadas =1;

                                }
                                break;

                            default:
                                if (stristr($lealtadDetalle->{"a.tipo"}, 'CONDGAME')) {

                                    $idGame = explode("CONDGAME", $lealtadDetalle->{"a.tipo"})[1];

                                    foreach ($detalleJuegosCasino as $item) {
                                        if ($idGame == $item->Id) {
                                            $cumplecondicionproducto = true;

                                            $valorASumar = $valorASumar + (($detalleValorApuesta * $lealtadDetalle->{"a.valor"}) / 100);

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

                        $lealtadid = $lealtad->{"a.lealtad_id"};
                        $usulealtad_id = $lealtad->{"usulealtad_id"};
                        $valorapostado = $lealtad->{"apostado"};
                        $valorrequerido = $lealtad->{"rollower_requerido"};

                    }
                }

            }

            if ($lealtadid != 0) {



                if($tipoProducto == 2){
                    $valorASumar=$detalleValorApuesta;

                }



                if (($valorapostado + $detalleValorApuesta) >= $valorrequerido) {
                    $winLealtad = true;
                }

                $strSql = array();
                $contSql = 0;

                $contSql = $contSql + 1;
                $strSql[$contSql] = "UPDATE usuario_lealtad SET apostado = apostado + " . ($valorASumar) . " WHERE usulealtad_id =" . $usulealtad_id;

                $contSql = $contSql + 1;
                $strSql[$contSql] = "INSERT INTO it_ticket_enc_info1 (ticket_id,tipo,valor)  VALUES ( " . $ticketId . ",'ROLLOWER'," . $usulealtad_id . ") ";


                if($ganaLealtadId == 0){
                    switch ($tiposaldo) {
                        case 0:
                            $contSql = $contSql + 1;
                            $strSql[$contSql] = "UPDATE usuario_lealtad,registro SET usuario_lealtad.estado = 'R',registro.creditos_base_ant=registro.creditos_base,registro.creditos_base=registro.creditos_base + usuario_lealtad.valor,registro.creditos_lealtad=registro.creditos_lealtad - usuario_lealtad.valor   WHERE  registro.usuario_id= usuario_lealtad.usuario_id AND usuario_lealtad.apostado >= usuario_lealtad.rollower_requerido AND usuario_lealtad.usulealtad_id = " . $usulealtad_id . " AND usuario_lealtad.estado='A'";
                            $contSql = $contSql +1;
                            $strSql[$contSql] = "INSERT INTO lealtad_log (usuario_id,tipo,valor,estado,id_externo,mandante,transaccion_id,tipolealtad_id,fecha_crea,fecha_cierre) SELECT a.usuario_id,CASE WHEN b.tipo = 2 THEN 'D' ELSE 'F' END,a.valor,'L',a.usulealtad_id,0,'0',4,now(),now()  FROM  usuario_lealtad a INNER JOIN lealtad_interna  b ON (b.lealtad_id = a.lealtad_id)  WHERE a.usulealtad_id = " . $usulealtad_id . " AND a.apostado >= a.rollower_requerido AND a.estado='R'";

                            break;

                        case 1:
                            $contSql = $contSql + 1;
                            $strSql[$contSql] = "UPDATE usuario_lealtad,registro SET usuario_lealtad.estado = 'R',registro.creditos_ant=registro.creditos,registro.creditos=registro.creditos + usuario_lealtad.valor,registro.creditos_lealtad=registro.creditos_lealtad - usuario_lealtad.valor   WHERE  registro.usuario_id= usuario_lealtad.usuario_id AND usuario_lealtad.apostado >= usuario_lealtad.rollower_requerido AND usuario_lealtad.usulealtad_id = " . $usulealtad_id . " AND usuario_lealtad.estado='A'";
                            $estadoLealtad = 'R';
                            $contSql = $contSql +1;
                            $strSql[$contSql] = "INSERT INTO lealtad_log (usuario_id,tipo,valor,estado,id_externo,mandante,transaccion_id,tipolealtad_id,fecha_crea,fecha_cierre) SELECT a.usuario_id,CASE WHEN b.tipo = 2 THEN 'D' ELSE 'F' END,a.valor,'L',a.usulealtad_id,0,'0',4,now(),now()  FROM  usuario_lealtad a  INNER JOIN lealtad_interna  b ON (b.lealtad_id = a.lealtad_id)  WHERE a.usulealtad_id = " . $usulealtad_id . " AND a.apostado >= a.rollower_requerido AND a.estado='R'";

                            break;

                        case 2:

                            $contSql = $contSql + 1;
                            $strSql[$contSql] = "UPDATE usuario_lealtad,registro SET usuario_lealtad.estado = 'R',registro.saldo_especial=registro.saldo_especial + usuario_lealtad.valor,registro.creditos_lealtad=registro.creditos_lealtad - usuario_lealtad.valor   WHERE  registro.usuario_id= usuario_lealtad.usuario_id AND usuario_lealtad.apostado >= usuario_lealtad.rollower_requerido AND usuario_lealtad.usulealtad_id = " . $usulealtad_id . " AND usuario_lealtad.estado='A'";
                            $estadoLealtad = 'R';
                            $contSql = $contSql +1;
                            $strSql[$contSql] = "INSERT INTO lealtad_log (usuario_id,tipo,valor,estado,id_externo,mandante,transaccion_id,tipolealtad_id,fecha_crea,fecha_cierre) SELECT a.usuario_id,CASE WHEN b.tipo = 2 THEN 'D' ELSE 'F' END,a.valor,'L',a.usulealtad_id,0,'0',4,now(),now()  FROM  usuario_lealtad a  INNER JOIN lealtad_interna  b ON (b.lealtad_id = a.lealtad_id)  WHERE a.usulealtad_id = " . $usulealtad_id . " AND a.apostado >= a.rollower_requerido AND a.estado='R'";

                            break;

                    }


                }




                $respuesta["WinLealtad"] = true;
                $respuesta["Lealtad"] = $lealtadid;
                $respuesta["UsuarioLealtad"] = $usulealtad_id;
                $respuesta["queries"] = $strSql;

                foreach ($respuesta["queries"] as $querie){
                    $this->execQuery($querie);
                }

                if($ganaLealtadId != 0) {
                    $sqlLealtad2 = "select a.usuario_id,a.usulealtad_id,a.lealtad_id,a.apostado,a.rollower_requerido,a.fecha_crea,lealtad_interna.condicional,lealtad_interna.tipo from usuario_lealtad a INNER JOIN lealtad_interna ON (lealtad_interna.lealtad_id = a.lealtad_id ) where  a.estado='A' AND (lealtad_interna.tipo = 2 OR lealtad_interna.tipo = 3) AND a.usulealtad_id='" . $usulealtad_id . "'";

                    $lealtadsDisponibles2 = $this->execQuery($sqlLealtad2);

                    $rollower_requerido=$lealtadsDisponibles2[0]->rollower_requerido;
                    $apostado=$lealtadsDisponibles2[0]->apostado;

                    if($apostado >= $rollower_requerido){
                        try{
                            $usuarioSql = "select ciudad.ciudad_id,ciudad.depto_id,pais.pais_id FROM registro
  INNER JOIN ciudad ON (ciudad.ciudad_id = registro.ciudad_id)
  INNER JOIN departamento ON (ciudad.depto_id = departamento.depto_id)
  INNER JOIN pais ON (pais.pais_id = departamento.pais_id) WHERE registro.usuario_id='" . $lealtadsDisponibles2[0]->usuario_id . "'";

                            $Usuario = $this->execQuery($usuarioSql);

                            $dataUsuario = $Usuario;
                            $detalles = array(
                                "PaisUSER" => $dataUsuario['pais_id'],
                                "DepartamentoUSER" => $dataUsuario['depto_id'],
                                "CiudadUSER" => $dataUsuario['ciudad_id'],

                            );
                            $detalles=json_decode(json_encode($detalles));

                            $respuesta2=$this->agregarLealtadFree($ganaLealtadId,$lealtadsDisponibles2[0]->usuario_id,"0",$detalles,true);

                            $contSql = 1;
                            $strSql=array();
                            $strSql[$contSql] = "UPDATE usuario_lealtad SET usuario_lealtad.estado = 'R'   WHERE usuario_lealtad.usulealtad_id = " . $usulealtad_id . " AND usuario_lealtad.estado='A'";

                            //  $contSql = $contSql +1;
                            //   $strSql[$contSql] = "INSERT INTO lealtad_log (usuario_id,tipo,valor,estado,id_externo,mandante,transaccion_id,tipolealtad_id) SELECT a.usuario_id,'D',a.valor,'L',a.usulealtad_id,0,'0',4  FROM  usuario_lealtad a   WHERE a.usulealtad_id = " . $usulealtad_id . " AND a.apostado >= a.rollower_requerido";
                        }catch (Exception $e){

                        }



                        $respuesta["WinLealtad"] = true;
                        $respuesta["Lealtad"] = $lealtadid;
                        $respuesta["UsuarioLealtad"] = $usulealtad_id;
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
     * Verficiar rollower en el lealtad
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
    public function verificarLealtadUsuarioPremio($usuarioId, $detalles,$tipoProducto,$ticketId){

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

                    $UsuarioLealtad = new UsuarioLealtad($TransjuegoInfo->descripcion);

                    $UsuarioLealtad->setValorPremio('valor_premio + ' . $TransaccionApi->getValor());

                    $UsuarioLealtadMySqlDAO = new UsuarioLealtadMySqlDAO();
                    $UsuarioLealtadMySqlDAO->update($UsuarioLealtad);
                    $UsuarioLealtadMySqlDAO->getTransaction()->commit();
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

                    $UsuarioLealtad = new UsuarioLealtad($TransjuegoInfo->descripcion);

                    $UsuarioLealtad->setValorPremio('valor_premio + ' . $TransaccionApi->getValor());

                    $UsuarioLealtadMySqlDAO = new UsuarioLealtadMySqlDAO();
                    $UsuarioLealtadMySqlDAO->update($UsuarioLealtad);
                    $UsuarioLealtadMySqlDAO->getTransaction()->commit();
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

                    $UsuarioLealtad = new UsuarioLealtad($TransjuegoInfo->descripcion);

                    $UsuarioLealtad->setValorPremio('valor_premio + ' . $TransaccionApi->getValor());

                    $UsuarioLealtadMySqlDAO = new UsuarioLealtadMySqlDAO();
                    $UsuarioLealtadMySqlDAO->update($UsuarioLealtad);
                    $UsuarioLealtadMySqlDAO->getTransaction()->commit();
                }


                break;


        }
    }

    /**
     * Verficiar lealtad
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
    public function verificarLealtadUsuario($usuarioId, $detalles,$tipoProducto,$ticketId){

        switch($tipoProducto){


            case "CASINO":

                $TransaccionApi = new TransaccionApi($ticketId);
                $UsuarioMandante = new UsuarioMandante($TransaccionApi->usuarioId);
                $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

                print_r($TransaccionApi);
                print_r($ticketId);

                $rules = [];

                array_push($rules, array("field" => "usuario_lealtad.usuario_id", "data" => "$TransaccionApi->usuarioId", "op" => "eq"));
                array_push($rules, array("field" => "lealtad_interna.estado", "data" => "A", "op" => "eq"));
                array_push($rules, array("field" => "lealtad_interna.fecha_inicio", "data" => "$TransaccionApi->fechaCrea" , "op" => "le"));
                array_push($rules, array("field" => "lealtad_interna.fecha_fin", "data" => "$TransaccionApi->fechaCrea", "op" => "ge"));
                array_push($rules, array("field" => "lealtad_interna.tipo", "data" => "2", "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                $json = json_encode($filtro);


                $UsuarioLealtad = new UsuarioLealtad();
                $data = $UsuarioLealtad->getUsuarioLealtadsCustomWithoutPosition("usuario_lealtad.*,usuario_mandante.nombres,lealtad_interna.*", "usuario_lealtad.valor", "DESC", 0, 100, $json, true, '');



                $data = json_decode($data);

                $final = [];

                $pos = 1;
                $lealtadsAnalizados='';

                $cumpleCondicion=false;
                foreach ($data->data as $key => $value) {

                    $array = [];
                    $array["Position"] = $pos;
                    $array["Id"] = $value->{"usuario_lealtad.usulealtad_id"};

                    $lealtadsAnalizados = $lealtadsAnalizados . $value->{"usuario_lealtad.lealtad_id"} . ",";
                    $LealtadInterna = new LealtadInterna();
                    $LealtadDetalle = new LealtadDetalle();

                    //$bonos = $BonoInterna->getBonosCustom(" bono_interna.* ", "bono_interna.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
                    //$bonos = json_decode($bonos);


                    $rules = [];

                    array_push($rules, array("field" => "lealtad_interna.lealtad_id", "data" => $value->{"usuario_lealtad.lealtad_id"}, "op" => "eq"));
                    array_push($rules, array("field" => "lealtad_interna.estado", "data" => "A", "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $json = json_encode($filtro);

                    $lealtadDetalles = $LealtadDetalle->getLealtadDetallesCustom(" lealtad_detalle.*,lealtad_interna.* ", "lealtad_interna.lealtad_id", "asc", 0, 1000, $json, TRUE);

                    $lealtadDetalles = json_decode($lealtadDetalles);

                    $final = [];

                    $creditosConvert=0;

                    $cumpleCondicionPais=false;
                    $cumpleCondicionCont=0;

                    $condicionesProducto=0;

                    foreach ($lealtadDetalles->data as $key2 => $value2) {

                        switch ($value2->{"lealtad_detalle.tipo"}){
                            case "RANK":
                                if($value2->{"lealtad_detalle.moneda"} == $UsuarioMandante->moneda){
                                    if($TransaccionApi->valor >= $value2->{"lealtad_detalle.valor"}){
                                        if($TransaccionApi->valor <= $value2->{"lealtad_detalle.valor2"}){
                                            $creditosConvert=$value2->{"lealtad_detalle.valor3"};
                                        }

                                    }
                                }

                                break;


                            case "CONDPAISUSER":

                                if($value2->{"lealtad_detalle.valor"} ==$Usuario->paisId){
                                    $cumpleCondicionPais=true;
                                }
                                $cumpleCondicionCont++;

                                break;

                            default:

                                if (stristr($value2->{"lealtad_detalle.tipo"}, 'CONDGAME')) {

                                    $idGame = explode("CONDGAME", $value2->{"lealtad_detalle.tipo"})[1];

                                    if($TransaccionApi->productoId == $idGame){
                                        $cumpleCondicion=true;
                                    }
                                    $condicionesProducto++;
                                }

                                if (stristr($value2->{"lealtad_detalle.tipo"}, 'CONDPROVIDER')) {

                                    $idProvider = explode("CONDPROVIDER", $value2->{"lealtad_detalle.tipo"})[1];

                                    if($TransaccionApi->proveedorId == $idProvider){
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

                        $UsuarioLealtad = new UsuarioLealtad( $value->{"usuario_lealtad.usulealtad_id"});
                        $UsuarioLealtad->valor=$UsuarioLealtad->valor + $creditosConvert;
                        $UsuarioLealtad->valorBase=($UsuarioLealtad->valorBase + $TransaccionApi->valor);
                        print_r($TransaccionApi);

                        print_r($UsuarioLealtad);
                        $UsuarioLealtadMySqlDAO = new UsuarioLealtadMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                        $UsuarioLealtadMySqlDAO->update($UsuarioLealtad);

                        $TransjuegoInfo = new TransjuegoInfo();
                        $TransjuegoInfo->productoId=$TransaccionApi->productoId;
                        $TransjuegoInfo->transaccionId=$TransaccionApi->transaccionId;
                        $TransjuegoInfo->tipo="TORNEO";
                        $TransjuegoInfo->descripcion=$value->{"usuario_lealtad.usulealtad_id"};
                        $TransjuegoInfo->valor=$creditosConvert;
                        $TransjuegoInfo->transapiId=$TransaccionApi->transapiId;
                        $TransjuegoInfo->usucreaId=0;
                        $TransjuegoInfo->usumodifId=0;
                        $TransjuegoInfo->identificador=$TransaccionApi->identificador;

                        $title = '';
                        $messageBody = '';
                        $loyaltyName = $value->{"lealtad_interna.nombre"};

                        switch(strtolower($Usuario->idioma)) {
                            case 'es':
                                $title = 'Notificacion';
                                $messageBody = "¡ Bien :thumbsup: ! Sumaste {$creditosConvert} puntos en {$loyaltyName} :clap:";
                                break;
                            case 'en':
                                $title = 'Notification';
                                $messageBody = "¡ Great :thumbsup: ! You added {$creditosConvert} points in {$loyaltyName} :clap:";
                                break;
                            case 'pt':
                                $title = 'Notificação';
                                $messageBody = "Se liga! :thumbsup: Você somou {$creditosConvert} pontos em {$loyaltyName}  :clap:";
                                break;
                        }


                        $UsuarioMensaje = new UsuarioMensaje();
                        $UsuarioMensaje->usufromId = 0;
                        $UsuarioMensaje->usutoId = $UsuarioLealtad->getUsuarioId();
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

                        $UsuarioSession = new UsuarioSession();
                        $rules = [];

                        array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                        array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

                        $filtro = array("rules" => $rules, "groupOp" => "AND");
                        $json = json_encode($filtro);


                        $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

                        $usuarios = json_decode($usuarios);

                        $usuariosFinal = [];

                       /* foreach ($usuarios->data as $key => $value) {

                            $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data);
                            $WebsocketUsuario->sendWSMessage();

                        }*/

                        break;
                    }


                }

                if( !$cumpleCondicion) {

                    if($lealtadsAnalizados != ""){
                        $lealtadsAnalizados=$lealtadsAnalizados .'0';
                    }

                    $LealtadInterna = new LealtadInterna();

                    $rules = [];

                    if($lealtadsAnalizados != ''){
                        array_push($rules, array("field" => "lealtad_interna.lealtad_id", "data" => "$lealtadsAnalizados", "op" => "ni"));
                    }

                    array_push($rules, array("field" => "lealtad_interna.estado", "data" => "A", "op" => "eq"));
                    array_push($rules, array("field" => "lealtad_interna.fecha_inicio", "data" => "$TransaccionApi->fechaCrea" , "op" => "le"));
                    array_push($rules, array("field" => "lealtad_interna.fecha_fin", "data" => "$TransaccionApi->fechaCrea", "op" => "ge"));
                    array_push($rules, array("field" => "lealtad_interna.tipo", "data" => "2", "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $json = json_encode($filtro);


                    $data = $LealtadInterna->getLealtadsCustom("lealtad_interna.*", "lealtad_interna.puntos", "ASC", 0, 1000, $json, true, '');


                    $data = json_decode($data);

                    $final = [];

                    $pos = 1;
                    $lealtadsAnalizados='';

                    foreach ($data->data as $key => $value) {


                        $LealtadDetalle = new LealtadDetalle();

                        //$bonos = $BonoInterna->getBonosCustom(" bono_interna.* ", "bono_interna.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
                        //$bonos = json_decode($bonos);


                        $rules = [];

                        array_push($rules, array("field" => "lealtad_interna.lealtad_id", "data" => $value->{"lealtad_interna.lealtad_id"}, "op" => "eq"));
                        array_push($rules, array("field" => "lealtad_interna.estado", "data" => "A", "op" => "eq"));

                        $filtro = array("rules" => $rules, "groupOp" => "AND");

                        $json = json_encode($filtro);

                        $lealtadDetalles = $LealtadDetalle->getLealtadDetallesCustom(" lealtad_detalle.*,lealtad_interna.* ", "lealtad_interna.lealtad_id", "asc", 0, 1000, $json, TRUE);

                        $lealtadDetalles = json_decode($lealtadDetalles);

                        $final = [];

                        $creditosConvert=0;

                        $cumpleCondicion=false;
                        $needSubscribe=false;

                        $cumpleCondicionPais=false;
                        $cumpleCondicionCont=0;

                        $condicionesProducto=0;

                        foreach ($lealtadDetalles->data as $key2 => $value2) {

                            switch ($value2->{"lealtad_detalle.tipo"}){
                                case "RANK":
                                    if($value2->{"lealtad_detalle.moneda"} == $UsuarioMandante->moneda){
                                        if($TransaccionApi->valor >= $value2->{"lealtad_detalle.valor"}){
                                            if($TransaccionApi->valor <= $value2->{"lealtad_detalle.valor2"}){
                                                $creditosConvert=$value2->{"lealtad_detalle.valor3"};
                                            }

                                        }
                                    }

                                    break;

                                case "USERSUBSCRIBE":

                                    if($value2->{"lealtad_detalle.valor"} ==0){

                                    }else{
                                        $needSubscribe=true;
                                    }

                                    break;

                                case "VISIBILIDAD":

                                    if($value2->{"lealtad_detalle.valor"} ==1){
                                        $needSubscribe=true;

                                    }else{
                                    }

                                    break;


                                case "CONDPAISUSER":

                                    if($value2->{"lealtad_detalle.valor"} ==$Usuario->paisId){
                                        $cumpleCondicionPais=true;
                                    }
                                    $cumpleCondicionCont++;

                                    break;

                                default:

                                    if (stristr($value2->{"lealtad_detalle.tipo"}, 'CONDGAME')) {

                                        $idGame = explode("CONDGAME", $value2->{"lealtad_detalle.tipo"})[1];

                                        if($TransaccionApi->productoId == $idGame){

                                            $cumpleCondicion=true;
                                        }
                                        $condicionesProducto++;
                                    }

                                    if (stristr($value2->{"lealtad_detalle.tipo"}, 'CONDPROVIDER')) {

                                        $idProvider = explode("CONDPROVIDER", $value2->{"lealtad_detalle.tipo"})[1];

                                        if($TransaccionApi->proveedorId == $idProvider){
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

                            $UsuarioLealtad = new UsuarioLealtad();
                            $UsuarioLealtad->usuarioId=$UsuarioMandante->usumandanteId;
                            $UsuarioLealtad->lealtadId=$value->{"lealtad_interna.lealtad_id"};
                            $UsuarioLealtad->valor=0;
                            $UsuarioLealtad->posicion=0;
                            $UsuarioLealtad->valorBase=0;
                            $UsuarioLealtad->usucreaId=0;
                            $UsuarioLealtad->usumodifId=0;
                            $UsuarioLealtad->estado="A";
                            $UsuarioLealtad->errorId=0;
                            $UsuarioLealtad->idExterno=0;
                            $UsuarioLealtad->mandante=0;
                            $UsuarioLealtad->version=0;
                            $UsuarioLealtad->apostado=0;
                            $UsuarioLealtad->codigo=0;
                            $UsuarioLealtad->externoId=0;
                            $UsuarioLealtad->valor=$UsuarioLealtad->valor + $creditosConvert;
                            $UsuarioLealtad->valorBase=($UsuarioLealtad->valorBase + $TransaccionApi->valor);

                            $UsuarioLealtadMySqlDAO = new UsuarioLealtadMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                            $idUsuLealtad=$UsuarioLealtadMySqlDAO->insert($UsuarioLealtad);

                            $TransjuegoInfo = new TransjuegoInfo();
                            $TransjuegoInfo->productoId=$TransaccionApi->productoId;
                            $TransjuegoInfo->transaccionId=$TransaccionApi->transaccionId;
                            $TransjuegoInfo->tipo="TORNEO";
                            $TransjuegoInfo->descripcion=$idUsuLealtad;
                            $TransjuegoInfo->valor=$creditosConvert;
                            $TransjuegoInfo->transapiId=$TransaccionApi->transapiId;
                            $TransjuegoInfo->usucreaId=0;
                            $TransjuegoInfo->usumodifId=0;

                            $title = '';
                            $messageBody = '';
                            $loyaltyName = $value->{"lealtad_interna.nombre"};

                            switch(strtolower($Usuario->idioma)) {
                                case 'es':
                                    $title = 'Notificacion';
                                    $messageBody = "¡ Bien :thumbsup: ! Sumaste {$creditosConvert} puntos en {$loyaltyName} :clap:";
                                    break;
                                case 'en':
                                    $title = 'Notification';
                                    $messageBody = "¡ Great :thumbsup: ! You added {$creditosConvert} points in {$loyaltyName} :clap:";
                                    break;
                                case 'pt':
                                    $title = 'Notificação';
                                    $messageBody = "Se liga! :thumbsup: Você somou {$creditosConvert} pontos em {$loyaltyName}  :clap:";
                                    break;
                            }

                            $UsuarioMensaje = new UsuarioMensaje();
                            $UsuarioMensaje->usufromId = 0;
                            $UsuarioMensaje->usutoId = $UsuarioLealtad->getUsuarioId();
                            $UsuarioMensaje->isRead = 0;
                            $UsuarioMensaje->body =  $messageBody;
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

                            $UsuarioSession = new UsuarioSession();
                            $rules = [];

                            array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                            array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

                            $filtro = array("rules" => $rules, "groupOp" => "AND");
                            $json = json_encode($filtro);


                            $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

                            $usuarios = json_decode($usuarios);

                            $usuariosFinal = [];

                          /*  foreach ($usuarios->data as $key => $value) {

                                $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data);
                                $WebsocketUsuario->sendWSMessage();

                            }*/

                            break;
                        }


                    }


                }



                break;


            case "LIVECASINO":

                $TransaccionApi = new TransaccionApi($ticketId);
                $UsuarioMandante = new UsuarioMandante($TransaccionApi->usuarioId);
                $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

                print_r($TransaccionApi);
                print_r($ticketId);

                $rules = [];

                array_push($rules, array("field" => "usuario_lealtad.usuario_id", "data" => "$TransaccionApi->usuarioId", "op" => "eq"));
                array_push($rules, array("field" => "lealtad_interna.estado", "data" => "A", "op" => "eq"));
                array_push($rules, array("field" => "lealtad_interna.fecha_inicio", "data" => "$TransaccionApi->fechaCrea" , "op" => "le"));
                array_push($rules, array("field" => "lealtad_interna.fecha_fin", "data" => "$TransaccionApi->fechaCrea", "op" => "ge"));
                array_push($rules, array("field" => "lealtad_interna.tipo", "data" => "3", "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                $json = json_encode($filtro);


                $UsuarioLealtad = new UsuarioLealtad();
                $data = $UsuarioLealtad->getUsuarioLealtadsCustomWithoutPosition("usuario_lealtad.*,usuario_mandante.nombres,lealtad_interna.*", "usuario_lealtad.valor", "DESC", 0, 100, $json, true, '');

                print_r($data);

                $data = json_decode($data);

                $final = [];

                $pos = 1;
                $lealtadsAnalizados='';

                $cumpleCondicion=false;
                foreach ($data->data as $key => $value) {

                    $array = [];
                    $array["Position"] = $pos;
                    $array["Id"] = $value->{"usuario_lealtad.usulealtad_id"};

                    $lealtadsAnalizados = $lealtadsAnalizados . $value->{"usuario_lealtad.lealtad_id"} . ",";
                    $LealtadInterna = new LealtadInterna();
                    $LealtadDetalle = new LealtadDetalle();

                    //$bonos = $BonoInterna->getBonosCustom(" bono_interna.* ", "bono_interna.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
                    //$bonos = json_decode($bonos);


                    $rules = [];

                    array_push($rules, array("field" => "lealtad_interna.lealtad_id", "data" => $value->{"usuario_lealtad.lealtad_id"}, "op" => "eq"));
                    array_push($rules, array("field" => "lealtad_interna.estado", "data" => "A", "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $json = json_encode($filtro);

                    $lealtadDetalles = $LealtadDetalle->getLealtadDetallesCustom(" lealtad_detalle.*,lealtad_interna.* ", "lealtad_interna.lealtad_id", "asc", 0, 1000, $json, TRUE);

                    $lealtadDetalles = json_decode($lealtadDetalles);

                    $final = [];

                    $creditosConvert=0;

                    $cumpleCondicionPais=false;
                    $cumpleCondicionCont=0;

                    $condicionesProducto=0;

                    foreach ($lealtadDetalles->data as $key2 => $value2) {

                        switch ($value2->{"lealtad_detalle.tipo"}){
                            case "RANK":
                                if($value2->{"lealtad_detalle.moneda"} == $UsuarioMandante->moneda){
                                    if($TransaccionApi->valor >= $value2->{"lealtad_detalle.valor"}){
                                        if($TransaccionApi->valor <= $value2->{"lealtad_detalle.valor2"}){
                                            $creditosConvert=$value2->{"lealtad_detalle.valor3"};
                                        }

                                    }
                                }

                                break;


                            case "CONDPAISUSER":

                                if($value2->{"lealtad_detalle.valor"} ==$Usuario->paisId){
                                    $cumpleCondicionPais=true;
                                }
                                $cumpleCondicionCont++;

                                break;

                            default:

                                if (stristr($value2->{"lealtad_detalle.tipo"}, 'CONDGAME')) {

                                    $idGame = explode("CONDGAME", $value2->{"lealtad_detalle.tipo"})[1];

                                    if($TransaccionApi->productoId == $idGame){
                                        $cumpleCondicion=true;
                                    }
                                    $condicionesProducto++;
                                }

                                if (stristr($value2->{"lealtad_detalle.tipo"}, 'CONDPROVIDER')) {

                                    $idProvider = explode("CONDPROVIDER", $value2->{"lealtad_detalle.tipo"})[1];

                                    if($TransaccionApi->proveedorId == $idProvider){
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

                        $UsuarioLealtad = new UsuarioLealtad( $value->{"usuario_lealtad.usulealtad_id"});
                        $UsuarioLealtad->valor=$UsuarioLealtad->valor + $creditosConvert;
                        $UsuarioLealtad->valorBase=($UsuarioLealtad->valorBase + $TransaccionApi->valor);
                        print_r($TransaccionApi);

                        print_r($UsuarioLealtad);
                        $UsuarioLealtadMySqlDAO = new UsuarioLealtadMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                        $UsuarioLealtadMySqlDAO->update($UsuarioLealtad);

                        $TransjuegoInfo = new TransjuegoInfo();
                        $TransjuegoInfo->productoId=$TransaccionApi->productoId;
                        $TransjuegoInfo->transaccionId=$TransaccionApi->transaccionId;
                        $TransjuegoInfo->tipo="TORNEO";
                        $TransjuegoInfo->descripcion=$value->{"usuario_lealtad.usulealtad_id"};
                        $TransjuegoInfo->valor=$creditosConvert;
                        $TransjuegoInfo->transapiId=$TransaccionApi->transapiId;
                        $TransjuegoInfo->usucreaId=0;
                        $TransjuegoInfo->usumodifId=0;
                        $TransjuegoInfo->identificador=$TransaccionApi->identificador;

                        $title = '';
                        $messageBody = '';
                        $loyaltyName = $value->{"lealtad_interna.nombre"};

                        switch(strtolower($Usuario->idioma)) {
                            case 'es':
                                $title = 'Notificacion';
                                $messageBody = "¡ Bien :thumbsup: ! Sumaste {$creditosConvert} puntos en {$loyaltyName} :clap:";
                                break;
                            case 'en':
                                $title = 'Notification';
                                $messageBody = "¡ Great :thumbsup: ! You added {$creditosConvert} points in {$loyaltyName} :clap:";
                                break;
                            case 'pt':
                                $title = 'Notificação';
                                $messageBody = "Se liga! :thumbsup: Você somou {$creditosConvert} pontos em {$loyaltyName}  :clap:";
                                break;
                        }

                        $UsuarioMensaje = new UsuarioMensaje();
                        $UsuarioMensaje->usufromId = 0;
                        $UsuarioMensaje->usutoId = $UsuarioLealtad->getUsuarioId();
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

                        $UsuarioSession = new UsuarioSession();
                        $rules = [];

                        array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                        array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

                        $filtro = array("rules" => $rules, "groupOp" => "AND");
                        $json = json_encode($filtro);


                        $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);
                        print_r($usuarios);
                        $usuarios = json_decode($usuarios);

                        $usuariosFinal = [];

                      /*  foreach ($usuarios->data as $key => $value) {

                            $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data);
                            $WebsocketUsuario->sendWSMessage();

                        }*/
                        break;
                    }


                }

                if( !$cumpleCondicion) {

                    if($lealtadsAnalizados != ""){
                        $lealtadsAnalizados=$lealtadsAnalizados .'0';
                    }

                    $LealtadInterna = new LealtadInterna();

                    $rules = [];

                    if($lealtadsAnalizados != ''){
                        array_push($rules, array("field" => "lealtad_interna.lealtad_id", "data" => "$lealtadsAnalizados", "op" => "ni"));
                    }

                    array_push($rules, array("field" => "lealtad_interna.estado", "data" => "A", "op" => "eq"));
                    array_push($rules, array("field" => "lealtad_interna.fecha_inicio", "data" => "$TransaccionApi->fechaCrea" , "op" => "le"));
                    array_push($rules, array("field" => "lealtad_interna.fecha_fin", "data" => "$TransaccionApi->fechaCrea", "op" => "ge"));
                    array_push($rules, array("field" => "lealtad_interna.tipo", "data" => "3", "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $json = json_encode($filtro);


                    $data = $LealtadInterna->getLealtadsCustom("lealtad_interna.*", "lealtad_interna.puntos", "ASC", 0, 1000, $json, true, '');


                    $data = json_decode($data);

                    $final = [];

                    $pos = 1;
                    $lealtadsAnalizados='';

                    foreach ($data->data as $key => $value) {


                        $LealtadDetalle = new LealtadDetalle();

                        //$bonos = $BonoInterna->getBonosCustom(" bono_interna.* ", "bono_interna.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
                        //$bonos = json_decode($bonos);


                        $rules = [];

                        array_push($rules, array("field" => "lealtad_interna.lealtad_id", "data" => $value->{"lealtad_interna.lealtad_id"}, "op" => "eq"));
                        array_push($rules, array("field" => "lealtad_interna.estado", "data" => "A", "op" => "eq"));

                        $filtro = array("rules" => $rules, "groupOp" => "AND");

                        $json = json_encode($filtro);

                        $lealtadDetalles = $LealtadDetalle->getLealtadDetallesCustom(" lealtad_detalle.*,lealtad_interna.* ", "lealtad_interna.lealtad_id", "asc", 0, 1000, $json, TRUE);

                        $lealtadDetalles = json_decode($lealtadDetalles);

                        $final = [];

                        $creditosConvert=0;

                        $cumpleCondicion=false;
                        $needSubscribe=false;

                        $cumpleCondicionPais=false;
                        $cumpleCondicionCont=0;

                        $condicionesProducto=0;

                        foreach ($lealtadDetalles->data as $key2 => $value2) {

                            switch ($value2->{"lealtad_detalle.tipo"}){
                                case "RANK":
                                    if($value2->{"lealtad_detalle.moneda"} == $UsuarioMandante->moneda){
                                        if($TransaccionApi->valor >= $value2->{"lealtad_detalle.valor"}){
                                            if($TransaccionApi->valor <= $value2->{"lealtad_detalle.valor2"}){
                                                $creditosConvert=$value2->{"lealtad_detalle.valor3"};
                                            }

                                        }
                                    }

                                    break;

                                case "USERSUBSCRIBE":

                                    if($value2->{"lealtad_detalle.valor"} ==0){

                                    }else{
                                        $needSubscribe=true;
                                    }

                                    break;

                                case "VISIBILIDAD":

                                    if($value2->{"lealtad_detalle.valor"} ==1){
                                        $needSubscribe=true;

                                    }else{
                                    }

                                    break;


                                case "CONDPAISUSER":

                                    if($value2->{"lealtad_detalle.valor"} ==$Usuario->paisId){
                                        $cumpleCondicionPais=true;
                                    }
                                    $cumpleCondicionCont++;

                                    break;

                                default:

                                    if (stristr($value2->{"lealtad_detalle.tipo"}, 'CONDGAME')) {

                                        $idGame = explode("CONDGAME", $value2->{"lealtad_detalle.tipo"})[1];

                                        if($TransaccionApi->productoId == $idGame){

                                            $cumpleCondicion=true;
                                        }
                                        $condicionesProducto++;
                                    }

                                    if (stristr($value2->{"lealtad_detalle.tipo"}, 'CONDPROVIDER')) {

                                        $idProvider = explode("CONDPROVIDER", $value2->{"lealtad_detalle.tipo"})[1];

                                        if($TransaccionApi->proveedorId == $idProvider){
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

                            $UsuarioLealtad = new UsuarioLealtad();
                            $UsuarioLealtad->usuarioId=$UsuarioMandante->usumandanteId;
                            $UsuarioLealtad->lealtadId=$value->{"lealtad_interna.lealtad_id"};
                            $UsuarioLealtad->valor=0;
                            $UsuarioLealtad->posicion=0;
                            $UsuarioLealtad->valorBase=0;
                            $UsuarioLealtad->usucreaId=0;
                            $UsuarioLealtad->usumodifId=0;
                            $UsuarioLealtad->estado="A";
                            $UsuarioLealtad->errorId=0;
                            $UsuarioLealtad->idExterno=0;
                            $UsuarioLealtad->mandante=0;
                            $UsuarioLealtad->version=0;
                            $UsuarioLealtad->apostado=0;
                            $UsuarioLealtad->codigo=0;
                            $UsuarioLealtad->externoId=0;
                            $UsuarioLealtad->valor=$UsuarioLealtad->valor + $creditosConvert;
                            $UsuarioLealtad->valorBase=($UsuarioLealtad->valorBase + $TransaccionApi->valor);

                            $UsuarioLealtadMySqlDAO = new UsuarioLealtadMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                            $idUsuLealtad=$UsuarioLealtadMySqlDAO->insert($UsuarioLealtad);

                            $TransjuegoInfo = new TransjuegoInfo();
                            $TransjuegoInfo->productoId=$TransaccionApi->productoId;
                            $TransjuegoInfo->transaccionId=$TransaccionApi->transaccionId;
                            $TransjuegoInfo->tipo="TORNEO";
                            $TransjuegoInfo->descripcion=$idUsuLealtad;
                            $TransjuegoInfo->valor=$creditosConvert;
                            $TransjuegoInfo->transapiId=$TransaccionApi->transapiId;
                            $TransjuegoInfo->usucreaId=0;
                            $TransjuegoInfo->usumodifId=0;


                            $title = '';
                            $messageBody = '';
                            $loyaltyName = $value->{"lealtad_interna.nombre"};

                            switch(strtolower($Usuario->idioma)) {
                                case 'es':
                                    $title = 'Notificacion';
                                    $messageBody = "¡ Bien :thumbsup: ! Sumaste {$creditosConvert} puntos en {$loyaltyName} :clap:";
                                    break;
                                case 'en':
                                    $title = 'Notification';
                                    $messageBody = "¡ Great :thumbsup: ! You added {$creditosConvert} points in {$loyaltyName} :clap:";
                                    break;
                                case 'pt':
                                    $title = 'Notificação';
                                    $messageBody = "Se liga! :thumbsup: Você somou {$creditosConvert} pontos em {$loyaltyName}  :clap:";
                                    break;
                            }

                            $UsuarioMensaje = new UsuarioMensaje();
                            $UsuarioMensaje->usufromId = 0;
                            $UsuarioMensaje->usutoId = $UsuarioLealtad->getUsuarioId();
                            $UsuarioMensaje->isRead = 0;
                            $UsuarioMensaje->body =  $messageBody;
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

                            $UsuarioSession = new UsuarioSession();
                            $rules = [];

                            array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                            array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

                            $filtro = array("rules" => $rules, "groupOp" => "AND");
                            $json = json_encode($filtro);


                            $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

                            $usuarios = json_decode($usuarios);

                            $usuariosFinal = [];

                            /*foreach ($usuarios->data as $key => $value) {

                                $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data);
                                $WebsocketUsuario->sendWSMessage();

                            }*/

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

                array_push($rules, array("field" => "usuario_lealtad.usuario_id", "data" => "$TransaccionApi->usuarioId", "op" => "eq"));
                array_push($rules, array("field" => "lealtad_interna.estado", "data" => "A", "op" => "eq"));
                array_push($rules, array("field" => "lealtad_interna.fecha_inicio", "data" => "$TransaccionApi->fechaCrea" , "op" => "le"));
                array_push($rules, array("field" => "lealtad_interna.fecha_fin", "data" => "$TransaccionApi->fechaCrea", "op" => "ge"));
                array_push($rules, array("field" => "lealtad_interna.tipo", "data" => "4", "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                $json = json_encode($filtro);


                $UsuarioLealtad = new UsuarioLealtad();
                $data = $UsuarioLealtad->getUsuarioLealtadsCustomWithoutPosition("usuario_lealtad.*,usuario_mandante.nombres,lealtad_interna.*", "usuario_lealtad.valor", "DESC", 0, 100, $json, true, '');

                print_r($data);

                $data = json_decode($data);

                $final = [];

                $pos = 1;
                $lealtadsAnalizados='';

                $cumpleCondicion=false;
                foreach ($data->data as $key => $value) {

                    $array = [];
                    $array["Position"] = $pos;
                    $array["Id"] = $value->{"usuario_lealtad.usulealtad_id"};

                    $lealtadsAnalizados = $lealtadsAnalizados . $value->{"usuario_lealtad.lealtad_id"} . ",";
                    $LealtadInterna = new LealtadInterna();
                    $LealtadDetalle = new LealtadDetalle();

                    //$bonos = $BonoInterna->getBonosCustom(" bono_interna.* ", "bono_interna.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
                    //$bonos = json_decode($bonos);


                    $rules = [];

                    array_push($rules, array("field" => "lealtad_interna.lealtad_id", "data" => $value->{"usuario_lealtad.lealtad_id"}, "op" => "eq"));
                    array_push($rules, array("field" => "lealtad_interna.estado", "data" => "A", "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $json = json_encode($filtro);

                    $lealtadDetalles = $LealtadDetalle->getLealtadDetallesCustom(" lealtad_detalle.*,lealtad_interna.* ", "lealtad_interna.lealtad_id", "asc", 0, 1000, $json, TRUE);

                    $lealtadDetalles = json_decode($lealtadDetalles);

                    $final = [];

                    $creditosConvert=0;

                    $cumpleCondicionPais=false;
                    $cumpleCondicionCont=0;

                    $condicionesProducto=0;

                    foreach ($lealtadDetalles->data as $key2 => $value2) {

                        switch ($value2->{"lealtad_detalle.tipo"}){
                            case "RANK":
                                if($value2->{"lealtad_detalle.moneda"} == $UsuarioMandante->moneda){
                                    if($TransaccionApi->valor >= $value2->{"lealtad_detalle.valor"}){
                                        if($TransaccionApi->valor <= $value2->{"lealtad_detalle.valor2"}){
                                            $creditosConvert=$value2->{"lealtad_detalle.valor3"};
                                        }

                                    }
                                }

                                break;


                            case "CONDPAISUSER":

                                if($value2->{"lealtad_detalle.valor"} ==$Usuario->paisId){
                                    $cumpleCondicionPais=true;
                                }
                                $cumpleCondicionCont++;

                                break;

                            default:

                                if (stristr($value2->{"lealtad_detalle.tipo"}, 'CONDGAME')) {

                                    $idGame = explode("CONDGAME", $value2->{"lealtad_detalle.tipo"})[1];

                                    if($TransaccionApi->productoId == $idGame){
                                        $cumpleCondicion=true;
                                    }
                                    $condicionesProducto++;
                                }

                                if (stristr($value2->{"lealtad_detalle.tipo"}, 'CONDPROVIDER')) {

                                    $idProvider = explode("CONDPROVIDER", $value2->{"lealtad_detalle.tipo"})[1];

                                    if($TransaccionApi->proveedorId == $idProvider){
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

                        $UsuarioLealtad = new UsuarioLealtad( $value->{"usuario_lealtad.usulealtad_id"});
                        $UsuarioLealtad->valor=$UsuarioLealtad->valor + $creditosConvert;
                        $UsuarioLealtad->valorBase=($UsuarioLealtad->valorBase + $TransaccionApi->valor);
                        print_r($TransaccionApi);

                        print_r($UsuarioLealtad);
                        $UsuarioLealtadMySqlDAO = new UsuarioLealtadMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                        $UsuarioLealtadMySqlDAO->update($UsuarioLealtad);

                        $TransjuegoInfo = new TransjuegoInfo();
                        $TransjuegoInfo->productoId=$TransaccionApi->productoId;
                        $TransjuegoInfo->transaccionId=$TransaccionApi->transaccionId;
                        $TransjuegoInfo->tipo="TORNEO";
                        $TransjuegoInfo->descripcion=$value->{"usuario_lealtad.usulealtad_id"};
                        $TransjuegoInfo->valor=$creditosConvert;
                        $TransjuegoInfo->transapiId=$TransaccionApi->transapiId;
                        $TransjuegoInfo->usucreaId=0;
                        $TransjuegoInfo->usumodifId=0;
                        $TransjuegoInfo->identificador=$TransaccionApi->identificador;

                        $title = '';
                        $messageBody = '';
                        $loyaltyName = $value->{"lealtad_interna.nombre"};

                        switch(strtolower($Usuario->idioma)) {
                            case 'es':
                                $title = 'Notificacion';
                                $messageBody = "¡ Bien :thumbsup: ! Sumaste {$creditosConvert} puntos en {$loyaltyName} :clap:";
                                break;
                            case 'en':
                                $title = 'Notification';
                                $messageBody = "¡ Great :thumbsup: ! You added {$creditosConvert} points in {$loyaltyName} :clap:";
                                break;
                            case 'pt':
                                $title = 'Notificação';
                                $messageBody = "Se liga! :thumbsup: Você somou {$creditosConvert} pontos em {$loyaltyName}  :clap:";
                                break;
                        }

                        $UsuarioMensaje = new UsuarioMensaje();
                        $UsuarioMensaje->usufromId = 0;
                        $UsuarioMensaje->usutoId = $UsuarioLealtad->getUsuarioId();
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

                        $UsuarioSession = new UsuarioSession();
                        $rules = [];

                        array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                        array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

                        $filtro = array("rules" => $rules, "groupOp" => "AND");
                        $json = json_encode($filtro);


                        $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

                        $usuarios = json_decode($usuarios);

                        $usuariosFinal = [];

                        /*foreach ($usuarios->data as $key => $value) {

                            $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data);
                            $WebsocketUsuario->sendWSMessage();

                        }*/

                        break;
                    }


                }

                if( !$cumpleCondicion) {

                    if($lealtadsAnalizados != ""){
                        $lealtadsAnalizados=$lealtadsAnalizados .'0';
                    }

                    $LealtadInterna = new LealtadInterna();

                    $rules = [];

                    if($lealtadsAnalizados != ''){
                        array_push($rules, array("field" => "lealtad_interna.lealtad_id", "data" => "$lealtadsAnalizados", "op" => "ni"));
                    }

                    array_push($rules, array("field" => "lealtad_interna.estado", "data" => "A", "op" => "eq"));
                    array_push($rules, array("field" => "lealtad_interna.fecha_inicio", "data" => "$TransaccionApi->fechaCrea" , "op" => "le"));
                    array_push($rules, array("field" => "lealtad_interna.fecha_fin", "data" => "$TransaccionApi->fechaCrea", "op" => "ge"));
                    array_push($rules, array("field" => "lealtad_interna.tipo", "data" => "4", "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $json = json_encode($filtro);


                    $data = $LealtadInterna->getLealtadsCustom("lealtad_interna.*", "lealtad_interna.puntos", "ASC", 0, 1000, $json, true, '');


                    $data = json_decode($data);

                    $final = [];

                    $pos = 1;
                    $lealtadsAnalizados='';

                    foreach ($data->data as $key => $value) {


                        $LealtadDetalle = new LealtadDetalle();

                        //$bonos = $BonoInterna->getBonosCustom(" bono_interna.* ", "bono_interna.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
                        //$bonos = json_decode($bonos);


                        $rules = [];

                        array_push($rules, array("field" => "lealtad_interna.lealtad_id", "data" => $value->{"lealtad_interna.lealtad_id"}, "op" => "eq"));
                        array_push($rules, array("field" => "lealtad_interna.estado", "data" => "A", "op" => "eq"));

                        $filtro = array("rules" => $rules, "groupOp" => "AND");

                        $json = json_encode($filtro);

                        $lealtadDetalles = $LealtadDetalle->getLealtadDetallesCustom(" lealtad_detalle.*,lealtad_interna.* ", "lealtad_interna.lealtad_id", "asc", 0, 1000, $json, TRUE);

                        $lealtadDetalles = json_decode($lealtadDetalles);

                        $final = [];

                        $creditosConvert=0;

                        $cumpleCondicion=false;
                        $needSubscribe=false;

                        $cumpleCondicionPais=false;
                        $cumpleCondicionCont=0;

                        $condicionesProducto=0;

                        foreach ($lealtadDetalles->data as $key2 => $value2) {

                            switch ($value2->{"lealtad_detalle.tipo"}){
                                case "RANK":
                                    if($value2->{"lealtad_detalle.moneda"} == $UsuarioMandante->moneda){
                                        if($TransaccionApi->valor >= $value2->{"lealtad_detalle.valor"}){
                                            if($TransaccionApi->valor <= $value2->{"lealtad_detalle.valor2"}){
                                                $creditosConvert=$value2->{"lealtad_detalle.valor3"};
                                            }

                                        }
                                    }

                                    break;

                                case "USERSUBSCRIBE":

                                    if($value2->{"lealtad_detalle.valor"} ==0){

                                    }else{
                                        $needSubscribe=true;
                                    }

                                    break;

                                case "VISIBILIDAD":

                                    if($value2->{"lealtad_detalle.valor"} ==1){
                                        $needSubscribe=true;

                                    }else{
                                    }

                                    break;


                                case "CONDPAISUSER":

                                    if($value2->{"lealtad_detalle.valor"} ==$Usuario->paisId){
                                        $cumpleCondicionPais=true;
                                    }
                                    $cumpleCondicionCont++;

                                    break;

                                default:

                                    if (stristr($value2->{"lealtad_detalle.tipo"}, 'CONDGAME')) {

                                        $idGame = explode("CONDGAME", $value2->{"lealtad_detalle.tipo"})[1];

                                        if($TransaccionApi->productoId == $idGame){

                                            $cumpleCondicion=true;
                                        }
                                        $condicionesProducto++;
                                    }

                                    if (stristr($value2->{"lealtad_detalle.tipo"}, 'CONDPROVIDER')) {

                                        $idProvider = explode("CONDPROVIDER", $value2->{"lealtad_detalle.tipo"})[1];

                                        if($TransaccionApi->proveedorId == $idProvider){
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

                            $UsuarioLealtad = new UsuarioLealtad();
                            $UsuarioLealtad->usuarioId=$UsuarioMandante->usumandanteId;
                            $UsuarioLealtad->lealtadId=$value->{"lealtad_interna.lealtad_id"};
                            $UsuarioLealtad->valor=0;
                            $UsuarioLealtad->posicion=0;
                            $UsuarioLealtad->valorBase=0;
                            $UsuarioLealtad->usucreaId=0;
                            $UsuarioLealtad->usumodifId=0;
                            $UsuarioLealtad->estado="A";
                            $UsuarioLealtad->errorId=0;
                            $UsuarioLealtad->idExterno=0;
                            $UsuarioLealtad->mandante=0;
                            $UsuarioLealtad->version=0;
                            $UsuarioLealtad->apostado=0;
                            $UsuarioLealtad->codigo=0;
                            $UsuarioLealtad->externoId=0;
                            $UsuarioLealtad->valor=$UsuarioLealtad->valor + $creditosConvert;
                            $UsuarioLealtad->valorBase=($UsuarioLealtad->valorBase + $TransaccionApi->valor);

                            $UsuarioLealtadMySqlDAO = new UsuarioLealtadMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                            $idUsuLealtad=$UsuarioLealtadMySqlDAO->insert($UsuarioLealtad);

                            $TransjuegoInfo = new TransjuegoInfo();
                            $TransjuegoInfo->productoId=$TransaccionApi->productoId;
                            $TransjuegoInfo->transaccionId=$TransaccionApi->transaccionId;
                            $TransjuegoInfo->tipo="TORNEO";
                            $TransjuegoInfo->descripcion=$idUsuLealtad;
                            $TransjuegoInfo->valor=$creditosConvert;
                            $TransjuegoInfo->transapiId=$TransaccionApi->transapiId;
                            $TransjuegoInfo->usucreaId=0;
                            $TransjuegoInfo->usumodifId=0;

                            $title = '';
                            $messageBody = '';
                            $loyaltyName = $value->{"lealtad_interna.nombre"};

                            switch(strtolower($Usuario->idioma)) {
                                case 'es':
                                    $title = 'Notificacion';
                                    $messageBody = "¡ Bien :thumbsup: ! Sumaste {$creditosConvert} puntos en {$loyaltyName} :clap:";
                                    break;
                                case 'en':
                                    $title = 'Notification';
                                    $messageBody = "¡ Great :thumbsup: ! You added {$creditosConvert} points in {$loyaltyName} :clap:";
                                    break;
                                case 'pt':
                                    $title = 'Notificação';
                                    $messageBody = "Se liga! :thumbsup: Você somou {$creditosConvert} pontos em {$loyaltyName}  :clap:";
                                    break;
                            }

                            $UsuarioMensaje = new UsuarioMensaje();
                            $UsuarioMensaje->usufromId = 0;
                            $UsuarioMensaje->usutoId = $UsuarioLealtad->getUsuarioId();
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

                            $UsuarioSession = new UsuarioSession();
                            $rules = [];

                            array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                            array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

                            $filtro = array("rules" => $rules, "groupOp" => "AND");
                            $json = json_encode($filtro);


                            $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

                            $usuarios = json_decode($usuarios);

                            $usuariosFinal = [];

                            /*foreach ($usuarios->data as $key => $value) {

                                $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data);
                                $WebsocketUsuario->sendWSMessage();

                            }*/

                            break;
                        }


                    }


                }



                break;

        }
    }


    /**
     * Verficiar lealtad
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
    public function verificarLealtadUsuarioConTransaccionJuego($usuarioId, $detalles,$tipoProducto,$ticketId){

        switch($tipoProducto){


            case "CASINO":

                $TransaccionJuego = new TransaccionJuego($ticketId);
                $UsuarioMandante = new UsuarioMandante($TransaccionJuego->usuarioId);
                $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

                print_r($TransaccionJuego);

                $rules = [];

                array_push($rules, array("field" => "usuario_lealtad.usuario_id", "data" => "$TransaccionJuego->usuarioId", "op" => "eq"));
                array_push($rules, array("field" => "lealtad_interna.estado", "data" => "A", "op" => "eq"));
                // array_push($rules, array("field" => "lealtad_interna.fecha_inicio", "data" => "$TransaccionJuego->fechaCrea" , "op" => "le"));
                // array_push($rules, array("field" => "lealtad_interna.fecha_fin", "data" => "$TransaccionJuego->fechaCrea", "op" => "ge"));
                array_push($rules, array("field" => "lealtad_interna.tipo", "data" => "2", "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                $json = json_encode($filtro);


                $UsuarioLealtad = new UsuarioLealtad();
                $data = $UsuarioLealtad->getUsuarioLealtadsCustomWithoutPosition("usuario_lealtad.*,usuario_mandante.nombres,lealtad_interna.*", "usuario_lealtad.valor", "DESC", 0, 100, $json, true, '');

                print_r($data);

                $data = json_decode($data);

                $final = [];

                $pos = 1;
                $lealtadsAnalizados='';

                $cumpleCondicion=false;
                foreach ($data->data as $key => $value) {

                    $array = [];
                    $array["Position"] = $pos;
                    $array["Id"] = $value->{"usuario_lealtad.usulealtad_id"};

                    $lealtadsAnalizados = $lealtadsAnalizados . $value->{"usuario_lealtad.lealtad_id"} . ",";
                    $LealtadInterna = new LealtadInterna();
                    $LealtadDetalle = new LealtadDetalle();

                    //$bonos = $BonoInterna->getBonosCustom(" bono_interna.* ", "bono_interna.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
                    //$bonos = json_decode($bonos);


                    $rules = [];

                    array_push($rules, array("field" => "lealtad_interna.lealtad_id", "data" => $value->{"usuario_lealtad.lealtad_id"}, "op" => "eq"));
                    array_push($rules, array("field" => "lealtad_interna.estado", "data" => "A", "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $json = json_encode($filtro);

                    $lealtadDetalles = $LealtadDetalle->getLealtadDetallesCustom(" lealtad_detalle.*,lealtad_interna.* ", "lealtad_interna.lealtad_id", "asc", 0, 1000, $json, TRUE);

                    $lealtadDetalles = json_decode($lealtadDetalles);

                    $final = [];

                    $creditosConvert=0;

                    $cumpleCondicionPais=false;
                    $cumpleCondicionCont=0;

                    $condicionesProducto=0;

                    print_r($lealtadDetalles);

                    foreach ($lealtadDetalles->data as $key2 => $value2) {

                        switch ($value2->{"lealtad_detalle.tipo"}){
                            case "RANK":
                                if($value2->{"lealtad_detalle.moneda"} == $UsuarioMandante->moneda){
                                    if($TransaccionJuego->valorTicket >= $value2->{"lealtad_detalle.valor"}){
                                        if($TransaccionJuego->valorTicket <= $value2->{"lealtad_detalle.valor2"}){
                                            $creditosConvert=$value2->{"lealtad_detalle.valor3"};
                                        }

                                    }
                                }

                                break;


                            case "CONDPAISUSER":

                                if($value2->{"lealtad_detalle.valor"} ==$Usuario->paisId){
                                    $cumpleCondicionPais=true;
                                }
                                $cumpleCondicionCont++;

                                break;

                            default:

                                if (stristr($value2->{"lealtad_detalle.tipo"}, 'CONDGAME')) {

                                    $idGame = explode("CONDGAME", $value2->{"lealtad_detalle.tipo"})[1];

                                    if($TransaccionJuego->productoId == $idGame){
                                        $cumpleCondicion=true;
                                    }
                                    $condicionesProducto++;
                                }

                                if (stristr($value2->{"lealtad_detalle.tipo"}, 'CONDPROVIDER')) {
/*
                                    $idProvider = explode("CONDPROVIDER", $value2->{"lealtad_detalle.tipo"})[1];

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

                        $UsuarioLealtad = new UsuarioLealtad( $value->{"usuario_lealtad.usulealtad_id"});
                        $UsuarioLealtad->valor=$UsuarioLealtad->valor + $creditosConvert;
                        $UsuarioLealtad->valorBase=($UsuarioLealtad->valorBase + $TransaccionJuego->valorTicket);

                        print_r($UsuarioLealtad);
                        $UsuarioLealtadMySqlDAO = new UsuarioLealtadMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                        $UsuarioLealtadMySqlDAO->update($UsuarioLealtad);

                        $TransjuegoInfo = new TransjuegoInfo();
                        $TransjuegoInfo->productoId=$TransaccionJuego->productoId;
                        $TransjuegoInfo->transaccionId=$TransaccionJuego->transaccionId;
                        $TransjuegoInfo->tipo="TORNEO";
                        $TransjuegoInfo->descripcion=$value->{"usuario_lealtad.usulealtad_id"};
                        $TransjuegoInfo->valor=$creditosConvert;
                        $TransjuegoInfo->transapiId=0;
                        $TransjuegoInfo->usucreaId=0;
                        $TransjuegoInfo->usumodifId=0;
                        $TransjuegoInfo->identificador=$TransaccionJuego->ticketId;


                        $title = '';
                        $messageBody = '';
                        $loyaltyName = $value->{"lealtad_interna.nombre"};

                        switch(strtolower($Usuario->idioma)) {
                            case 'es':
                                $title = 'Notificacion';
                                $messageBody = "¡ Bien :thumbsup: ! Sumaste {$creditosConvert} puntos en {$loyaltyName} :clap:";
                                break;
                            case 'en':
                                $title = 'Notification';
                                $messageBody = "¡ Great :thumbsup: ! You added {$creditosConvert} points in {$loyaltyName} :clap:";
                                break;
                            case 'pt':
                                $title = 'Notificação';
                                $messageBody = "Se liga! :thumbsup: Você somou {$creditosConvert} pontos em {$loyaltyName}  :clap:";
                                break;
                        }

                        $UsuarioMensaje = new UsuarioMensaje();
                        $UsuarioMensaje->usufromId = 0;
                        $UsuarioMensaje->usutoId = $UsuarioLealtad->getUsuarioId();
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

                        $UsuarioSession = new UsuarioSession();
                        $rules = [];

                        array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                        array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

                        $filtro = array("rules" => $rules, "groupOp" => "AND");
                        $json = json_encode($filtro);


                        $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

                        $usuarios = json_decode($usuarios);

                        $usuariosFinal = [];

                        /*foreach ($usuarios->data as $key => $value) {

                            $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data);
                            $WebsocketUsuario->sendWSMessage();

                        }*/

                        break;
                    }


                }

                if( !$cumpleCondicion) {

                    if($lealtadsAnalizados != ""){
                        $lealtadsAnalizados=$lealtadsAnalizados .'0';
                    }

                    $LealtadInterna = new LealtadInterna();

                    $rules = [];

                    if($lealtadsAnalizados != ''){
                        array_push($rules, array("field" => "lealtad_interna.lealtad_id", "data" => "$lealtadsAnalizados", "op" => "ni"));
                    }

                    array_push($rules, array("field" => "lealtad_interna.estado", "data" => "A", "op" => "eq"));
                    array_push($rules, array("field" => "lealtad_interna.fecha_inicio", "data" => "$TransaccionJuego->fechaCrea" , "op" => "le"));
                    array_push($rules, array("field" => "lealtad_interna.fecha_fin", "data" => "$TransaccionJuego->fechaCrea", "op" => "ge"));
                    array_push($rules, array("field" => "lealtad_interna.tipo", "data" => "2", "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $json = json_encode($filtro);


                    $data = $LealtadInterna->getLealtadsCustom("lealtad_interna.*", "lealtad_interna.puntos", "ASC", 0, 1000, $json, true, '');


                    $data = json_decode($data);

                    $final = [];

                    $pos = 1;
                    $lealtadsAnalizados='';

                    foreach ($data->data as $key => $value) {


                        $LealtadDetalle = new LealtadDetalle();

                        //$bonos = $BonoInterna->getBonosCustom(" bono_interna.* ", "bono_interna.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
                        //$bonos = json_decode($bonos);


                        $rules = [];

                        array_push($rules, array("field" => "lealtad_interna.lealtad_id", "data" => $value->{"lealtad_interna.lealtad_id"}, "op" => "eq"));
                        array_push($rules, array("field" => "lealtad_interna.estado", "data" => "A", "op" => "eq"));

                        $filtro = array("rules" => $rules, "groupOp" => "AND");

                        $json = json_encode($filtro);

                        $lealtadDetalles = $LealtadDetalle->getLealtadDetallesCustom(" lealtad_detalle.*,lealtad_interna.* ", "lealtad_interna.lealtad_id", "asc", 0, 1000, $json, TRUE);

                        $lealtadDetalles = json_decode($lealtadDetalles);

                        $final = [];

                        $creditosConvert=0;

                        $cumpleCondicion=false;
                        $needSubscribe=false;

                        $cumpleCondicionPais=false;
                        $cumpleCondicionCont=0;

                        $condicionesProducto=0;
                        print_r($lealtadDetalles);

                        foreach ($lealtadDetalles->data as $key2 => $value2) {

                            switch ($value2->{"lealtad_detalle.tipo"}){
                                case "RANK":
                                    if($value2->{"lealtad_detalle.moneda"} == $UsuarioMandante->moneda){
                                        if($TransaccionJuego->valorTicket >= $value2->{"lealtad_detalle.valor"}){
                                            if($TransaccionJuego->valorTicket <= $value2->{"lealtad_detalle.valor2"}){
                                                $creditosConvert=$value2->{"lealtad_detalle.valor3"};
                                            }

                                        }
                                    }

                                    break;

                                case "USERSUBSCRIBE":

                                    if($value2->{"lealtad_detalle.valor"} ==0){

                                    }else{
                                        $needSubscribe=true;
                                    }

                                    break;

                                case "VISIBILIDAD":

                                    if($value2->{"lealtad_detalle.valor"} ==1){
                                        $needSubscribe=true;

                                    }else{
                                    }

                                    break;


                                case "CONDPAISUSER":

                                    if($value2->{"lealtad_detalle.valor"} ==$Usuario->paisId){
                                        $cumpleCondicionPais=true;
                                    }
                                    $cumpleCondicionCont++;

                                    break;

                                default:

                                    if (stristr($value2->{"lealtad_detalle.tipo"}, 'CONDGAME')) {

                                        $idGame = explode("CONDGAME", $value2->{"lealtad_detalle.tipo"})[1];

                                        if($TransaccionJuego->productoId == $idGame){

                                            $cumpleCondicion=true;
                                        }
                                        $condicionesProducto++;
                                    }

                                    /*if (stristr($value2->{"lealtad_detalle.tipo"}, 'CONDPROVIDER')) {

                                        $idProvider = explode("CONDPROVIDER", $value2->{"lealtad_detalle.tipo"})[1];

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

                            $UsuarioLealtad = new UsuarioLealtad();
                            $UsuarioLealtad->usuarioId=$UsuarioMandante->usumandanteId;
                            $UsuarioLealtad->lealtadId=$value->{"lealtad_interna.lealtad_id"};
                            $UsuarioLealtad->valor=0;
                            $UsuarioLealtad->posicion=0;
                            $UsuarioLealtad->valorBase=0;
                            $UsuarioLealtad->usucreaId=0;
                            $UsuarioLealtad->usumodifId=0;
                            $UsuarioLealtad->estado="A";
                            $UsuarioLealtad->errorId=0;
                            $UsuarioLealtad->idExterno=0;
                            $UsuarioLealtad->mandante=0;
                            $UsuarioLealtad->version=0;
                            $UsuarioLealtad->apostado=0;
                            $UsuarioLealtad->codigo=0;
                            $UsuarioLealtad->externoId=0;
                            $UsuarioLealtad->valor=$UsuarioLealtad->valor + $creditosConvert;
                            $UsuarioLealtad->valorBase=($UsuarioLealtad->valorBase + $TransaccionJuego->valorTicket);

                            $UsuarioLealtadMySqlDAO = new UsuarioLealtadMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                            $idUsuLealtad=$UsuarioLealtadMySqlDAO->insert($UsuarioLealtad);

                            $TransjuegoInfo = new TransjuegoInfo();
                            $TransjuegoInfo->productoId=$TransaccionJuego->productoId;
                            $TransjuegoInfo->transaccionId=$TransaccionJuego->transaccionId;
                            $TransjuegoInfo->tipo="TORNEO";
                            $TransjuegoInfo->descripcion=$idUsuLealtad;
                            $TransjuegoInfo->valor=$creditosConvert;
                            $TransjuegoInfo->transapiId=0;
                            $TransjuegoInfo->usucreaId=0;
                            $TransjuegoInfo->usumodifId=0;


                            $title = '';
                            $messageBody = '';
                            $loyaltyName = $value->{"lealtad_interna.nombre"};

                            switch(strtolower($Usuario->idioma)) {
                                case 'es':
                                    $title = 'Notificacion';
                                    $messageBody = "¡ Bien :thumbsup: ! Sumaste {$creditosConvert} puntos en {$loyaltyName} :clap:";
                                    break;
                                case 'en':
                                    $title = 'Notification';
                                    $messageBody = "¡ Great :thumbsup: ! You added {$creditosConvert} points in {$loyaltyName} :clap:";
                                    break;
                                case 'pt':
                                    $title = 'Notificação';
                                    $messageBody = "Se liga! :thumbsup: Você somou {$creditosConvert} pontos em {$loyaltyName}  :clap:";
                                    break;
                            }

                            $UsuarioMensaje = new UsuarioMensaje();
                            $UsuarioMensaje->usufromId = 0;
                            $UsuarioMensaje->usutoId = $UsuarioLealtad->getUsuarioId();
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

                            $UsuarioSession = new UsuarioSession();
                            $rules = [];

                            array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                            array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

                            $filtro = array("rules" => $rules, "groupOp" => "AND");
                            $json = json_encode($filtro);


                            $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

                            $usuarios = json_decode($usuarios);

                            $usuariosFinal = [];

                            /*foreach ($usuarios->data as $key => $value) {

                                $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data);
                                $WebsocketUsuario->sendWSMessage();

                            }*/

                            break;
                        }


                    }


                }



                break;


            case "LIVECASINO":

                $TransaccionApi = new TransaccionApi($ticketId);
                $UsuarioMandante = new UsuarioMandante($TransaccionApi->usuarioId);
                $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

                print_r($TransaccionApi);
                print_r($ticketId);

                $rules = [];

                array_push($rules, array("field" => "usuario_lealtad.usuario_id", "data" => "$TransaccionApi->usuarioId", "op" => "eq"));
                array_push($rules, array("field" => "lealtad_interna.estado", "data" => "A", "op" => "eq"));
                array_push($rules, array("field" => "lealtad_interna.fecha_inicio", "data" => "$TransaccionApi->fechaCrea" , "op" => "le"));
                array_push($rules, array("field" => "lealtad_interna.fecha_fin", "data" => "$TransaccionApi->fechaCrea", "op" => "ge"));
                array_push($rules, array("field" => "lealtad_interna.tipo", "data" => "3", "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                $json = json_encode($filtro);


                $UsuarioLealtad = new UsuarioLealtad();
                $data = $UsuarioLealtad->getUsuarioLealtadsCustomWithoutPosition("usuario_lealtad.*,usuario_mandante.nombres,lealtad_interna.*", "usuario_lealtad.valor", "DESC", 0, 100, $json, true, '');

                print_r($data);

                $data = json_decode($data);

                $final = [];

                $pos = 1;
                $lealtadsAnalizados='';

                $cumpleCondicion=false;
                foreach ($data->data as $key => $value) {

                    $array = [];
                    $array["Position"] = $pos;
                    $array["Id"] = $value->{"usuario_lealtad.usulealtad_id"};

                    $lealtadsAnalizados = $lealtadsAnalizados . $value->{"usuario_lealtad.lealtad_id"} . ",";
                    $LealtadInterna = new LealtadInterna();
                    $LealtadDetalle = new LealtadDetalle();

                    //$bonos = $BonoInterna->getBonosCustom(" bono_interna.* ", "bono_interna.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
                    //$bonos = json_decode($bonos);


                    $rules = [];

                    array_push($rules, array("field" => "lealtad_interna.lealtad_id", "data" => $value->{"usuario_lealtad.lealtad_id"}, "op" => "eq"));
                    array_push($rules, array("field" => "lealtad_interna.estado", "data" => "A", "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $json = json_encode($filtro);

                    $lealtadDetalles = $LealtadDetalle->getLealtadDetallesCustom(" lealtad_detalle.*,lealtad_interna.* ", "lealtad_interna.lealtad_id", "asc", 0, 1000, $json, TRUE);

                    $lealtadDetalles = json_decode($lealtadDetalles);

                    $final = [];

                    $creditosConvert=0;

                    $cumpleCondicionPais=false;
                    $cumpleCondicionCont=0;

                    $condicionesProducto=0;

                    foreach ($lealtadDetalles->data as $key2 => $value2) {

                        switch ($value2->{"lealtad_detalle.tipo"}){
                            case "RANK":
                                if($value2->{"lealtad_detalle.moneda"} == $UsuarioMandante->moneda){
                                    if($TransaccionApi->valor >= $value2->{"lealtad_detalle.valor"}){
                                        if($TransaccionApi->valor <= $value2->{"lealtad_detalle.valor2"}){
                                            $creditosConvert=$value2->{"lealtad_detalle.valor3"};
                                        }

                                    }
                                }

                                break;


                            case "CONDPAISUSER":

                                if($value2->{"lealtad_detalle.valor"} ==$Usuario->paisId){
                                    $cumpleCondicionPais=true;
                                }
                                $cumpleCondicionCont++;

                                break;

                            default:

                                if (stristr($value2->{"lealtad_detalle.tipo"}, 'CONDGAME')) {

                                    $idGame = explode("CONDGAME", $value2->{"lealtad_detalle.tipo"})[1];

                                    if($TransaccionApi->productoId == $idGame){
                                        $cumpleCondicion=true;
                                    }
                                    $condicionesProducto++;
                                }

                                if (stristr($value2->{"lealtad_detalle.tipo"}, 'CONDPROVIDER')) {

                                    $idProvider = explode("CONDPROVIDER", $value2->{"lealtad_detalle.tipo"})[1];

                                    if($TransaccionApi->proveedorId == $idProvider){
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

                        $UsuarioLealtad = new UsuarioLealtad( $value->{"usuario_lealtad.usulealtad_id"});
                        $UsuarioLealtad->valor=$UsuarioLealtad->valor + $creditosConvert;
                        $UsuarioLealtad->valorBase=($UsuarioLealtad->valorBase + $TransaccionApi->valor);
                        print_r($TransaccionApi);

                        print_r($UsuarioLealtad);
                        $UsuarioLealtadMySqlDAO = new UsuarioLealtadMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                        $UsuarioLealtadMySqlDAO->update($UsuarioLealtad);

                        $TransjuegoInfo = new TransjuegoInfo();
                        $TransjuegoInfo->productoId=$TransaccionApi->productoId;
                        $TransjuegoInfo->transaccionId=$TransaccionApi->transaccionId;
                        $TransjuegoInfo->tipo="TORNEO";
                        $TransjuegoInfo->descripcion=$value->{"usuario_lealtad.usulealtad_id"};
                        $TransjuegoInfo->valor=$creditosConvert;
                        $TransjuegoInfo->transapiId=$TransaccionApi->transapiId;
                        $TransjuegoInfo->usucreaId=0;
                        $TransjuegoInfo->usumodifId=0;
                        $TransjuegoInfo->identificador=$TransaccionApi->identificador;


                        $title = '';
                        $messageBody = '';
                        $loyaltyName = $value->{"lealtad_interna.nombre"};

                        switch(strtolower($Usuario->idioma)) {
                            case 'es':
                                $title = 'Notificacion';
                                $messageBody = "¡ Bien :thumbsup: ! Sumaste {$creditosConvert} puntos en {$loyaltyName} :clap:";
                                break;
                            case 'en':
                                $title = 'Notification';
                                $messageBody = "¡ Great :thumbsup: ! You added {$creditosConvert} points in {$loyaltyName} :clap:";
                                break;
                            case 'pt':
                                $title = 'Notificação';
                                $messageBody = "Se liga! :thumbsup: Você somou {$creditosConvert} pontos em {$loyaltyName}  :clap:";
                                break;
                        }

                        $UsuarioMensaje = new UsuarioMensaje();
                        $UsuarioMensaje->usufromId = 0;
                        $UsuarioMensaje->usutoId = $UsuarioLealtad->getUsuarioId();
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
                        break;
                    }


                }

                if( !$cumpleCondicion) {

                    if($lealtadsAnalizados != ""){
                        $lealtadsAnalizados=$lealtadsAnalizados .'0';
                    }

                    $LealtadInterna = new LealtadInterna();

                    $rules = [];

                    if($lealtadsAnalizados != ''){
                        array_push($rules, array("field" => "lealtad_interna.lealtad_id", "data" => "$lealtadsAnalizados", "op" => "ni"));
                    }

                    array_push($rules, array("field" => "lealtad_interna.estado", "data" => "A", "op" => "eq"));
                    array_push($rules, array("field" => "lealtad_interna.fecha_inicio", "data" => "$TransaccionApi->fechaCrea" , "op" => "le"));
                    array_push($rules, array("field" => "lealtad_interna.fecha_fin", "data" => "$TransaccionApi->fechaCrea", "op" => "ge"));
                    array_push($rules, array("field" => "lealtad_interna.tipo", "data" => "3", "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $json = json_encode($filtro);


                    $data = $LealtadInterna->getLealtadsCustom("lealtad_interna.*", "lealtad_interna.puntos", "ASC", 0, 1000, $json, true, '');


                    $data = json_decode($data);

                    $final = [];

                    $pos = 1;
                    $lealtadsAnalizados='';

                    foreach ($data->data as $key => $value) {


                        $LealtadDetalle = new LealtadDetalle();

                        //$bonos = $BonoInterna->getBonosCustom(" bono_interna.* ", "bono_interna.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
                        //$bonos = json_decode($bonos);


                        $rules = [];

                        array_push($rules, array("field" => "lealtad_interna.lealtad_id", "data" => $value->{"lealtad_interna.lealtad_id"}, "op" => "eq"));
                        array_push($rules, array("field" => "lealtad_interna.estado", "data" => "A", "op" => "eq"));

                        $filtro = array("rules" => $rules, "groupOp" => "AND");

                        $json = json_encode($filtro);

                        $lealtadDetalles = $LealtadDetalle->getLealtadDetallesCustom(" lealtad_detalle.*,lealtad_interna.* ", "lealtad_interna.lealtad_id", "asc", 0, 1000, $json, TRUE);

                        $lealtadDetalles = json_decode($lealtadDetalles);

                        $final = [];

                        $creditosConvert=0;

                        $cumpleCondicion=false;
                        $needSubscribe=false;

                        $cumpleCondicionPais=false;
                        $cumpleCondicionCont=0;

                        $condicionesProducto=0;

                        foreach ($lealtadDetalles->data as $key2 => $value2) {

                            switch ($value2->{"lealtad_detalle.tipo"}){
                                case "RANK":
                                    if($value2->{"lealtad_detalle.moneda"} == $UsuarioMandante->moneda){
                                        if($TransaccionApi->valor >= $value2->{"lealtad_detalle.valor"}){
                                            if($TransaccionApi->valor <= $value2->{"lealtad_detalle.valor2"}){
                                                $creditosConvert=$value2->{"lealtad_detalle.valor3"};
                                            }

                                        }
                                    }

                                    break;

                                case "USERSUBSCRIBE":

                                    if($value2->{"lealtad_detalle.valor"} ==0){

                                    }else{
                                        $needSubscribe=true;
                                    }

                                    break;

                                case "VISIBILIDAD":

                                    if($value2->{"lealtad_detalle.valor"} ==1){
                                        $needSubscribe=true;

                                    }else{
                                    }

                                    break;


                                case "CONDPAISUSER":

                                    if($value2->{"lealtad_detalle.valor"} ==$Usuario->paisId){
                                        $cumpleCondicionPais=true;
                                    }
                                    $cumpleCondicionCont++;

                                    break;

                                default:

                                    if (stristr($value2->{"lealtad_detalle.tipo"}, 'CONDGAME')) {

                                        $idGame = explode("CONDGAME", $value2->{"lealtad_detalle.tipo"})[1];

                                        if($TransaccionApi->productoId == $idGame){

                                            $cumpleCondicion=true;
                                        }
                                        $condicionesProducto++;
                                    }

                                    if (stristr($value2->{"lealtad_detalle.tipo"}, 'CONDPROVIDER')) {

                                        $idProvider = explode("CONDPROVIDER", $value2->{"lealtad_detalle.tipo"})[1];

                                        if($TransaccionApi->proveedorId == $idProvider){
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

                            $UsuarioLealtad = new UsuarioLealtad();
                            $UsuarioLealtad->usuarioId=$UsuarioMandante->usumandanteId;
                            $UsuarioLealtad->lealtadId=$value->{"lealtad_interna.lealtad_id"};
                            $UsuarioLealtad->valor=0;
                            $UsuarioLealtad->posicion=0;
                            $UsuarioLealtad->valorBase=0;
                            $UsuarioLealtad->usucreaId=0;
                            $UsuarioLealtad->usumodifId=0;
                            $UsuarioLealtad->estado="A";
                            $UsuarioLealtad->errorId=0;
                            $UsuarioLealtad->idExterno=0;
                            $UsuarioLealtad->mandante=0;
                            $UsuarioLealtad->version=0;
                            $UsuarioLealtad->apostado=0;
                            $UsuarioLealtad->codigo=0;
                            $UsuarioLealtad->externoId=0;
                            $UsuarioLealtad->valor=$UsuarioLealtad->valor + $creditosConvert;
                            $UsuarioLealtad->valorBase=($UsuarioLealtad->valorBase + $TransaccionApi->valor);

                            $UsuarioLealtadMySqlDAO = new UsuarioLealtadMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                            $idUsuLealtad=$UsuarioLealtadMySqlDAO->insert($UsuarioLealtad);

                            $TransjuegoInfo = new TransjuegoInfo();
                            $TransjuegoInfo->productoId=$TransaccionApi->productoId;
                            $TransjuegoInfo->transaccionId=$TransaccionApi->transaccionId;
                            $TransjuegoInfo->tipo="TORNEO";
                            $TransjuegoInfo->descripcion=$idUsuLealtad;
                            $TransjuegoInfo->valor=$creditosConvert;
                            $TransjuegoInfo->transapiId=$TransaccionApi->transapiId;
                            $TransjuegoInfo->usucreaId=0;
                            $TransjuegoInfo->usumodifId=0;


                            $title = '';
                            $messageBody = '';
                            $loyaltyName = $value->{"lealtad_interna.nombre"};

                            switch(strtolower($Usuario->idioma)) {
                                case 'es':
                                    $title = 'Notificacion';
                                    $messageBody = "¡ Bien :thumbsup: ! Sumaste {$creditosConvert} puntos en {$loyaltyName} :clap:";
                                    break;
                                case 'en':
                                    $title = 'Notification';
                                    $messageBody = "¡ Great :thumbsup: ! You added {$creditosConvert} points in {$loyaltyName} :clap:";
                                    break;
                                case 'pt':
                                    $title = 'Notificação';
                                    $messageBody = "Se liga! :thumbsup: Você somou {$creditosConvert} pontos em {$loyaltyName}  :clap:";
                                    break;
                            }

                            $UsuarioMensaje = new UsuarioMensaje();
                            $UsuarioMensaje->usufromId = 0;
                            $UsuarioMensaje->usutoId = $UsuarioLealtad->getUsuarioId();
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

                array_push($rules, array("field" => "usuario_lealtad.usuario_id", "data" => "$TransaccionApi->usuarioId", "op" => "eq"));
                array_push($rules, array("field" => "lealtad_interna.estado", "data" => "A", "op" => "eq"));
                array_push($rules, array("field" => "lealtad_interna.fecha_inicio", "data" => "$TransaccionApi->fechaCrea" , "op" => "le"));
                array_push($rules, array("field" => "lealtad_interna.fecha_fin", "data" => "$TransaccionApi->fechaCrea", "op" => "ge"));
                array_push($rules, array("field" => "lealtad_interna.tipo", "data" => "4", "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                $json = json_encode($filtro);


                $UsuarioLealtad = new UsuarioLealtad();
                $data = $UsuarioLealtad->getUsuarioLealtadsCustomWithoutPosition("usuario_lealtad.*,usuario_mandante.nombres,lealtad_interna.*", "usuario_lealtad.valor", "DESC", 0, 100, $json, true, '');

                print_r($data);

                $data = json_decode($data);

                $final = [];

                $pos = 1;
                $lealtadsAnalizados='';

                $cumpleCondicion=false;
                foreach ($data->data as $key => $value) {

                    $array = [];
                    $array["Position"] = $pos;
                    $array["Id"] = $value->{"usuario_lealtad.usulealtad_id"};

                    $lealtadsAnalizados = $lealtadsAnalizados . $value->{"usuario_lealtad.lealtad_id"} . ",";
                    $LealtadInterna = new LealtadInterna();
                    $LealtadDetalle = new LealtadDetalle();

                    //$bonos = $BonoInterna->getBonosCustom(" bono_interna.* ", "bono_interna.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
                    //$bonos = json_decode($bonos);


                    $rules = [];

                    array_push($rules, array("field" => "lealtad_interna.lealtad_id", "data" => $value->{"usuario_lealtad.lealtad_id"}, "op" => "eq"));
                    array_push($rules, array("field" => "lealtad_interna.estado", "data" => "A", "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $json = json_encode($filtro);

                    $lealtadDetalles = $LealtadDetalle->getLealtadDetallesCustom(" lealtad_detalle.*,lealtad_interna.* ", "lealtad_interna.lealtad_id", "asc", 0, 1000, $json, TRUE);

                    $lealtadDetalles = json_decode($lealtadDetalles);

                    $final = [];

                    $creditosConvert=0;

                    $cumpleCondicionPais=false;
                    $cumpleCondicionCont=0;

                    $condicionesProducto=0;

                    foreach ($lealtadDetalles->data as $key2 => $value2) {

                        switch ($value2->{"lealtad_detalle.tipo"}){
                            case "RANK":
                                if($value2->{"lealtad_detalle.moneda"} == $UsuarioMandante->moneda){
                                    if($TransaccionApi->valor >= $value2->{"lealtad_detalle.valor"}){
                                        if($TransaccionApi->valor <= $value2->{"lealtad_detalle.valor2"}){
                                            $creditosConvert=$value2->{"lealtad_detalle.valor3"};
                                        }

                                    }
                                }

                                break;


                            case "CONDPAISUSER":

                                if($value2->{"lealtad_detalle.valor"} ==$Usuario->paisId){
                                    $cumpleCondicionPais=true;
                                }
                                $cumpleCondicionCont++;

                                break;

                            default:

                                if (stristr($value2->{"lealtad_detalle.tipo"}, 'CONDGAME')) {

                                    $idGame = explode("CONDGAME", $value2->{"lealtad_detalle.tipo"})[1];

                                    if($TransaccionApi->productoId == $idGame){
                                        $cumpleCondicion=true;
                                    }
                                    $condicionesProducto++;
                                }

                                if (stristr($value2->{"lealtad_detalle.tipo"}, 'CONDPROVIDER')) {

                                    $idProvider = explode("CONDPROVIDER", $value2->{"lealtad_detalle.tipo"})[1];

                                    if($TransaccionApi->proveedorId == $idProvider){
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

                        $UsuarioLealtad = new UsuarioLealtad( $value->{"usuario_lealtad.usulealtad_id"});
                        $UsuarioLealtad->valor=$UsuarioLealtad->valor + $creditosConvert;
                        $UsuarioLealtad->valorBase=($UsuarioLealtad->valorBase + $TransaccionApi->valor);
                        print_r($TransaccionApi);

                        print_r($UsuarioLealtad);
                        $UsuarioLealtadMySqlDAO = new UsuarioLealtadMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                        $UsuarioLealtadMySqlDAO->update($UsuarioLealtad);

                        $TransjuegoInfo = new TransjuegoInfo();
                        $TransjuegoInfo->productoId=$TransaccionApi->productoId;
                        $TransjuegoInfo->transaccionId=$TransaccionApi->transaccionId;
                        $TransjuegoInfo->tipo="TORNEO";
                        $TransjuegoInfo->descripcion=$value->{"usuario_lealtad.usulealtad_id"};
                        $TransjuegoInfo->valor=$creditosConvert;
                        $TransjuegoInfo->transapiId=$TransaccionApi->transapiId;
                        $TransjuegoInfo->usucreaId=0;
                        $TransjuegoInfo->usumodifId=0;
                        $TransjuegoInfo->identificador=$TransaccionApi->identificador;


                        $title = '';
                        $messageBody = '';
                        $loyaltyName = $value->{"lealtad_interna.nombre"};

                        switch(strtolower($Usuario->idioma)) {
                            case 'es':
                                $title = 'Notificacion';
                                $messageBody = "¡ Bien :thumbsup: ! Sumaste {$creditosConvert} puntos en {$loyaltyName} :clap:";
                                break;
                            case 'en':
                                $title = 'Notification';
                                $messageBody = "¡ Great :thumbsup: ! You added {$creditosConvert} points in {$loyaltyName} :clap:";
                                break;
                            case 'pt':
                                $title = 'Notificação';
                                $messageBody = "Se liga! :thumbsup: Você somou {$creditosConvert} pontos em {$loyaltyName}  :clap:";
                                break;
                        }

                        $UsuarioMensaje = new UsuarioMensaje();
                        $UsuarioMensaje->usufromId = 0;
                        $UsuarioMensaje->usutoId = $UsuarioLealtad->getUsuarioId();
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
                        break;
                    }


                }

                if( !$cumpleCondicion) {

                    if($lealtadsAnalizados != ""){
                        $lealtadsAnalizados=$lealtadsAnalizados .'0';
                    }

                    $LealtadInterna = new LealtadInterna();

                    $rules = [];

                    if($lealtadsAnalizados != ''){
                        array_push($rules, array("field" => "lealtad_interna.lealtad_id", "data" => "$lealtadsAnalizados", "op" => "ni"));
                    }

                    array_push($rules, array("field" => "lealtad_interna.estado", "data" => "A", "op" => "eq"));
                    array_push($rules, array("field" => "lealtad_interna.fecha_inicio", "data" => "$TransaccionApi->fechaCrea" , "op" => "le"));
                    array_push($rules, array("field" => "lealtad_interna.fecha_fin", "data" => "$TransaccionApi->fechaCrea", "op" => "ge"));
                    array_push($rules, array("field" => "lealtad_interna.tipo", "data" => "4", "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $json = json_encode($filtro);


                    $data = $LealtadInterna->getLealtadsCustom("lealtad_interna.*", "lealtad_interna.puntos", "ASC", 0, 1000, $json, true, '');


                    $data = json_decode($data);

                    $final = [];

                    $pos = 1;
                    $lealtadsAnalizados='';

                    foreach ($data->data as $key => $value) {


                        $LealtadDetalle = new LealtadDetalle();

                        //$bonos = $BonoInterna->getBonosCustom(" bono_interna.* ", "bono_interna.bono_id", "asc", $SkeepRows, $MaxRows, $json, false);
                        //$bonos = json_decode($bonos);


                        $rules = [];

                        array_push($rules, array("field" => "lealtad_interna.lealtad_id", "data" => $value->{"lealtad_interna.lealtad_id"}, "op" => "eq"));
                        array_push($rules, array("field" => "lealtad_interna.estado", "data" => "A", "op" => "eq"));

                        $filtro = array("rules" => $rules, "groupOp" => "AND");

                        $json = json_encode($filtro);

                        $lealtadDetalles = $LealtadDetalle->getLealtadDetallesCustom(" lealtad_detalle.*,lealtad_interna.* ", "lealtad_interna.lealtad_id", "asc", 0, 1000, $json, TRUE);

                        $lealtadDetalles = json_decode($lealtadDetalles);

                        $final = [];

                        $creditosConvert=0;

                        $cumpleCondicion=false;
                        $needSubscribe=false;

                        $cumpleCondicionPais=false;
                        $cumpleCondicionCont=0;

                        $condicionesProducto=0;

                        foreach ($lealtadDetalles->data as $key2 => $value2) {

                            switch ($value2->{"lealtad_detalle.tipo"}){
                                case "RANK":
                                    if($value2->{"lealtad_detalle.moneda"} == $UsuarioMandante->moneda){
                                        if($TransaccionApi->valor >= $value2->{"lealtad_detalle.valor"}){
                                            if($TransaccionApi->valor <= $value2->{"lealtad_detalle.valor2"}){
                                                $creditosConvert=$value2->{"lealtad_detalle.valor3"};
                                            }

                                        }
                                    }

                                    break;

                                case "USERSUBSCRIBE":

                                    if($value2->{"lealtad_detalle.valor"} ==0){

                                    }else{
                                        $needSubscribe=true;
                                    }

                                    break;

                                case "VISIBILIDAD":

                                    if($value2->{"lealtad_detalle.valor"} ==1){
                                        $needSubscribe=true;

                                    }else{
                                    }

                                    break;


                                case "CONDPAISUSER":

                                    if($value2->{"lealtad_detalle.valor"} ==$Usuario->paisId){
                                        $cumpleCondicionPais=true;
                                    }
                                    $cumpleCondicionCont++;

                                    break;

                                default:

                                    if (stristr($value2->{"lealtad_detalle.tipo"}, 'CONDGAME')) {

                                        $idGame = explode("CONDGAME", $value2->{"lealtad_detalle.tipo"})[1];

                                        if($TransaccionApi->productoId == $idGame){

                                            $cumpleCondicion=true;
                                        }
                                        $condicionesProducto++;
                                    }

                                    if (stristr($value2->{"lealtad_detalle.tipo"}, 'CONDPROVIDER')) {

                                        $idProvider = explode("CONDPROVIDER", $value2->{"lealtad_detalle.tipo"})[1];

                                        if($TransaccionApi->proveedorId == $idProvider){
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

                            $UsuarioLealtad = new UsuarioLealtad();
                            $UsuarioLealtad->usuarioId=$UsuarioMandante->usumandanteId;
                            $UsuarioLealtad->lealtadId=$value->{"lealtad_interna.lealtad_id"};
                            $UsuarioLealtad->valor=0;
                            $UsuarioLealtad->posicion=0;
                            $UsuarioLealtad->valorBase=0;
                            $UsuarioLealtad->usucreaId=0;
                            $UsuarioLealtad->usumodifId=0;
                            $UsuarioLealtad->estado="A";
                            $UsuarioLealtad->errorId=0;
                            $UsuarioLealtad->idExterno=0;
                            $UsuarioLealtad->mandante=0;
                            $UsuarioLealtad->version=0;
                            $UsuarioLealtad->apostado=0;
                            $UsuarioLealtad->codigo=0;
                            $UsuarioLealtad->externoId=0;
                            $UsuarioLealtad->valor=$UsuarioLealtad->valor + $creditosConvert;
                            $UsuarioLealtad->valorBase=($UsuarioLealtad->valorBase + $TransaccionApi->valor);

                            $UsuarioLealtadMySqlDAO = new UsuarioLealtadMySqlDAO($TransjuegoInfoMySqlDAO->getTransaction());
                            $idUsuLealtad=$UsuarioLealtadMySqlDAO->insert($UsuarioLealtad);

                            $TransjuegoInfo = new TransjuegoInfo();
                            $TransjuegoInfo->productoId=$TransaccionApi->productoId;
                            $TransjuegoInfo->transaccionId=$TransaccionApi->transaccionId;
                            $TransjuegoInfo->tipo="TORNEO";
                            $TransjuegoInfo->descripcion=$idUsuLealtad;
                            $TransjuegoInfo->valor=$creditosConvert;
                            $TransjuegoInfo->transapiId=$TransaccionApi->transapiId;
                            $TransjuegoInfo->usucreaId=0;
                            $TransjuegoInfo->usumodifId=0;

                            $title = '';
                            $messageBody = '';
                            $loyaltyName = $value->{"lealtad_interna.nombre"};

                            switch(strtolower($Usuario->idioma)) {
                                case 'es':
                                    $title = 'Notificacion';
                                    $messageBody = "¡ Bien :thumbsup: ! Sumaste {$creditosConvert} puntos en {$loyaltyName} :clap:";
                                    break;
                                case 'en':
                                    $title = 'Notification';
                                    $messageBody = "¡ Great :thumbsup: ! You added {$creditosConvert} points in {$loyaltyName} :clap:";
                                    break;
                                case 'pt':
                                    $title = 'Notificação';
                                    $messageBody = "Se liga! :thumbsup: Você somou {$creditosConvert} pontos em {$loyaltyName}  :clap:";
                                    break;
                            }

                            $UsuarioMensaje = new UsuarioMensaje();
                            $UsuarioMensaje->usufromId = 0;
                            $UsuarioMensaje->usutoId = $UsuarioLealtad->getUsuarioId();
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

                            $UsuarioSession = new UsuarioSession();
                            $rules = [];

                            array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                            array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

                            $filtro = array("rules" => $rules, "groupOp" => "AND");
                            $json = json_encode($filtro);

                            $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

                            $usuarios = json_decode($usuarios);

                            $usuariosFinal = [];

                            /*foreach ($usuarios->data as $key => $value) {

                                $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data);
                                $WebsocketUsuario->sendWSMessage();

                            }*/



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

        $LealtadInternaMySqlDAO = new LealtadInternaMySqlDAO($transaccion);
        $return = $LealtadInternaMySqlDAO->querySQL($sql);
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

        $LealtadInternaMySqlDAO = new LealtadInternaMySqlDAO($transaccion);
        $return = $LealtadInternaMySqlDAO->queryUpdate($sql);

        return $return;

    }

    /** Realiza el ajuste de puntos lealtad al usuario indicado con base en los parámetros entregados
     * @param UsuarioMandante $UsuarioMandante Usuario al que se le ajustarán los puntos
     * @param String $Movimiento Indica si el movimiento es de entrada o salida de puntos para el usuario
     * @param Integer $Tipo Tipo del movimiento
     * @param Integer $Identificador ExternoId vinculado a proceso externo que genera este cambio
     * @param Integer $Valor Valor a ajustar
     * @return void
     */
    public function AgregarPuntos($UsuarioMandante,$Movimiento,$Tipo,$Identificador,$Valor){

        /*
        10	Recargas	E/S
        15	Ajuste de Saldo E/S
        20	Apuestas Deportivas	E/S/C
        30	Apuestas Casino	E/S/C
        40	Nota de retiro Creada	E/S/C
        41	Nota de retiro Pagada
        50	Bono Redimido	E/S/C
        60	Aumento de cupo	E/S
        */
        $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());


        // Obtenemos la Transaccion de la BD para crear y registrar el Debito con esta transaccion
        $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();
        $Transaction = $TransaccionJuegoMySqlDAO->getTransaction();

        $Clasificador = new Clasificador("", "LOYALTYEXPIRATIONDATE");
        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(),$Clasificador->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
        $diasExpiracion = $MandanteDetalle->valor;

        try {


            switch ($Tipo) {
                case 10:
                    exit();
                    $Clasificador = new Clasificador("", "LOYALTYDEPOSIT");
                    $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
                    $Valorx = floatval($MandanteDetalle->valor);

                    if ($Movimiento == "E") {
                        $Clasificador = new Clasificador("", "LOYALTYBASEVALUE");
                        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
                        $valor_base = floatval($MandanteDetalle->valor);

                        $puntos = ((floatval($Valor) * $valor_base) / $Valorx);

                        $puntos = explode(".",$puntos)[0];

                        $LealtadHistorial = new LealtadHistorial();
                        $Usuario->creditPoints($puntos, $Transaction);

                        $LealtadHistorial->setUsuarioId($Usuario->usuarioId);
                        $LealtadHistorial->setDescripcion('');
                        $LealtadHistorial->setMovimiento("E");
                        $LealtadHistorial->setUsucreaId(0);
                        $LealtadHistorial->setUsumodifId(0);
                        $LealtadHistorial->setTipo($Tipo);
                        $LealtadHistorial->setValor($puntos);
                        $LealtadHistorial->setExternoId($Identificador);
                        $LealtadHistorial->setFechaExp(date("Y-m-d H:i:s", strtotime("+" . $diasExpiracion . " days")));


                        $LealtadHistorialMySqlDAO = new LealtadHistorialMySqlDAO($Transaction);
                        $LealtadHistorialMySqlDAO->insert($LealtadHistorial);

                    }
                    if ($Movimiento == "S") {

                        $Clasificador = new Clasificador("", "LOYALTYBASEVALUE");

                        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
                        $valor_base = floatval($MandanteDetalle->valor);
                        $puntos = ((floatval($Valor) * $valor_base) / $Valorx);

                        $puntos = explode(".",$puntos)[0];

                        $LealtadHistorial = new LealtadHistorial();
                        $Usuario->creditPoints($puntos, $Transaction);
                        $LealtadHistorial->setUsuarioId($Usuario->usuarioId);
                        $LealtadHistorial->setDescripcion('');
                        $LealtadHistorial->setMovimiento("E");
                        $LealtadHistorial->setUsucreaId(0);
                        $LealtadHistorial->setUsumodifId(0);
                        $LealtadHistorial->setTipo($Tipo);
                        $LealtadHistorial->setValor($puntos);
                        $LealtadHistorial->setExternoId($Identificador);
                        $LealtadHistorial->setFechaExp(date("Y-m-d H:i:s", strtotime("+" . $diasExpiracion . " days")));


                        $LealtadHistorialMySqlDAO = new LealtadHistorialMySqlDAO($Transaction);
                        $LealtadHistorialMySqlDAO->insert($LealtadHistorial);
                    }
                    break;
                case 15:
                    /** Ajustando saldo de puntos del usuario */
                    if ($Movimiento == 'E') $Usuario->creditPoints($Valor, $Transaction);
                    if ($Movimiento == 'S') $Usuario->debitPoints($Valor, $Transaction);

                    /** Dejando log en usuario_historial */
                    $LealtadHistorial = new LealtadHistorial();
                    $LealtadHistorialMySqlDAO = new LealtadHistorialMySqlDAO($Transaction);

                    $LealtadHistorial->setUsuarioId($Usuario->usuarioId);
                    $LealtadHistorial->setDescripcion('');
                    $LealtadHistorial->setMovimiento($Movimiento);
                    $LealtadHistorial->setUsucreaId($_SESSION["usuario"]);
                    $LealtadHistorial->setUsumodifId($_SESSION["usuario"]);
                    $LealtadHistorial->setTipo($Tipo);
                    $LealtadHistorial->setValor($Valor);
                    $LealtadHistorial->setExternoId($Identificador);
                    $LealtadHistorial->setFechaExp(date("Y-m-d H:i:s", strtotime("+" . $diasExpiracion . " days")));

                    $LealtadHistorialMySqlDAO->insert($LealtadHistorial);
                    break;
                case 20:
                    $it = new ItTicketEnc($Identificador);
                    if ($it->cantLineas == 1) {
                        $Clasificador = new Clasificador("", "LOYALTYBETTINGSPORTSSIMPLE");
                        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
                        $Valorx = floatval($MandanteDetalle->valor);

                        if ($Movimiento == "E") {
                            $Clasificador = new Clasificador("", "LOYALTYBASEVALUE");

                            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
                            $valor_base = floatval($MandanteDetalle->valor);
                            $puntos = ((floatval($Valor) * $valor_base) / $Valorx);

                            $puntos = explode(".",$puntos)[0];

                            $LealtadHistorial = new LealtadHistorial();
                            $Usuario->creditPoints($puntos, $Transaction);
                            $LealtadHistorial->setUsuarioId($Usuario->usuarioId);
                            $LealtadHistorial->setDescripcion('');
                            $LealtadHistorial->setMovimiento("E");
                            $LealtadHistorial->setUsucreaId(0);
                            $LealtadHistorial->setUsumodifId(0);
                            $LealtadHistorial->setTipo($Tipo);
                            $LealtadHistorial->setValor($puntos);
                            $LealtadHistorial->setExternoId($Identificador);
                            $LealtadHistorial->setFechaExp(date("Y-m-d H:i:s",   strtotime($it->fechaCierre. ' '.$it->horaCierre." +" . $diasExpiracion . " days")));


                            $LealtadHistorialMySqlDAO = new LealtadHistorialMySqlDAO($Transaction);
                            $LealtadHistorialMySqlDAO->insert($LealtadHistorial);

                        }
                        if ($Movimiento == "S") {

                            $Clasificador = new Clasificador("", "LOYALTYBASEVALUE");

                            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
                            $valor_base = floatval($MandanteDetalle->valor);
                            $puntos = ((floatval($Valor) * $valor_base) / $Valorx);

                            $puntos = explode(".",$puntos)[0];

                            $LealtadHistorial = new LealtadHistorial();
                            $Usuario->creditPoints($puntos, $Transaction);
                            $LealtadHistorial->setUsuarioId($Usuario->usuarioId);
                            $LealtadHistorial->setDescripcion('');
                            $LealtadHistorial->setMovimiento("E");
                            $LealtadHistorial->setUsucreaId(0);
                            $LealtadHistorial->setUsumodifId(0);
                            $LealtadHistorial->setTipo($Tipo);
                            $LealtadHistorial->setValor($puntos);
                            $LealtadHistorial->setExternoId($Identificador);
                            $LealtadHistorial->setFechaExp(date("Y-m-d H:i:s",   strtotime($it->fechaCierre. ' '.$it->horaCierre." +" . $diasExpiracion . " days")));


                            $LealtadHistorialMySqlDAO = new LealtadHistorialMySqlDAO($Transaction);
                            $LealtadHistorialMySqlDAO->insert($LealtadHistorial);
                        }
                    }
                    if ($it->cantLineas == 2) {
                        $Clasificador = new Clasificador("", "LOYALTYBETTINGSPORTSCOMBINEDTWO");
                        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
                        $Valorx = floatval($MandanteDetalle->valor);

                        if ($Movimiento == "E") {
                            $Clasificador = new Clasificador("", "LOYALTYBASEVALUE");

                            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
                            $valor_base = floatval($MandanteDetalle->valor);
                            $puntos = ((floatval($Valor) * $valor_base) / $Valorx);

                            $puntos = explode(".",$puntos)[0];

                            $LealtadHistorial = new LealtadHistorial();
                            $Usuario->creditPoints($puntos, $Transaction);
                            $LealtadHistorial->setUsuarioId($Usuario->usuarioId);
                            $LealtadHistorial->setDescripcion('');
                            $LealtadHistorial->setMovimiento("E");
                            $LealtadHistorial->setUsucreaId(0);
                            $LealtadHistorial->setUsumodifId(0);
                            $LealtadHistorial->setTipo($Tipo);
                            $LealtadHistorial->setValor($puntos);
                            $LealtadHistorial->setExternoId($Identificador);
                            $LealtadHistorial->setFechaExp(date("Y-m-d H:i:s",   strtotime($it->fechaCierre. ' '.$it->horaCierre." +" . $diasExpiracion . " days")));


                            $LealtadHistorialMySqlDAO = new LealtadHistorialMySqlDAO($Transaction);
                            $LealtadHistorialMySqlDAO->insert($LealtadHistorial);

                        }
                        if ($Movimiento == "S") {

                            $Clasificador = new Clasificador("", "LOYALTYBASEVALUE");

                            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
                            $valor_base = floatval($MandanteDetalle->valor);
                            $puntos = ((floatval($Valor) * $valor_base) / $Valorx);

                            $puntos = explode(".",$puntos)[0];

                            $LealtadHistorial = new LealtadHistorial();
                            $Usuario->creditPoints($puntos, $Transaction);
                            $LealtadHistorial->setUsuarioId($Usuario->usuarioId);
                            $LealtadHistorial->setDescripcion('');
                            $LealtadHistorial->setMovimiento("E");
                            $LealtadHistorial->setUsucreaId(0);
                            $LealtadHistorial->setUsumodifId(0);
                            $LealtadHistorial->setTipo($Tipo);
                            $LealtadHistorial->setValor($puntos);
                            $LealtadHistorial->setExternoId($Identificador);
                            $LealtadHistorial->setFechaExp(date("Y-m-d H:i:s",   strtotime($it->fechaCierre. ' '.$it->horaCierre." +" . $diasExpiracion . " days")));


                            $LealtadHistorialMySqlDAO = new LealtadHistorialMySqlDAO($Transaction);
                            $LealtadHistorialMySqlDAO->insert($LealtadHistorial);
                        }
                        print_r($LealtadHistorial);

                    }
                    if ($it->cantLineas == 3) {
                        $Clasificador = new Clasificador("", "LOYALTYBETTINGSPORTSCOMBINEDTHREE");
                        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
                        $Valorx = floatval($MandanteDetalle->valor);

                        if ($Movimiento == "E") {
                            $Clasificador = new Clasificador("", "LOYALTYBASEVALUE");

                            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
                            $valor_base = floatval($MandanteDetalle->valor);
                            $puntos = ((floatval($Valor) * $valor_base) / $Valorx);

                            $puntos = explode(".",$puntos)[0];

                            $LealtadHistorial = new LealtadHistorial();
                            $Usuario->creditPoints($puntos, $Transaction);
                            $LealtadHistorial->setUsuarioId($Usuario->usuarioId);
                            $LealtadHistorial->setDescripcion('');
                            $LealtadHistorial->setMovimiento("E");
                            $LealtadHistorial->setUsucreaId(0);
                            $LealtadHistorial->setUsumodifId(0);
                            $LealtadHistorial->setTipo($Tipo);
                            $LealtadHistorial->setValor($puntos);
                            $LealtadHistorial->setExternoId($Identificador);
                            $LealtadHistorial->setFechaExp(date("Y-m-d H:i:s",   strtotime($it->fechaCierre. ' '.$it->horaCierre." +" . $diasExpiracion . " days")));


                            $LealtadHistorialMySqlDAO = new LealtadHistorialMySqlDAO($Transaction);
                            $LealtadHistorialMySqlDAO->insert($LealtadHistorial);

                        }
                        if ($Movimiento == "S") {

                            $Clasificador = new Clasificador("", "LOYALTYBASEVALUE");

                            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
                            $valor_base = floatval($MandanteDetalle->valor);
                            $puntos = ((floatval($Valor) * $valor_base) / $Valorx);

                            $puntos = explode(".",$puntos)[0];

                            $LealtadHistorial = new LealtadHistorial();
                            $Usuario->creditPoints($puntos, $Transaction);
                            $LealtadHistorial->setUsuarioId($Usuario->usuarioId);
                            $LealtadHistorial->setDescripcion('');
                            $LealtadHistorial->setMovimiento("E");
                            $LealtadHistorial->setUsucreaId(0);
                            $LealtadHistorial->setUsumodifId(0);
                            $LealtadHistorial->setTipo($Tipo);
                            $LealtadHistorial->setValor($puntos);
                            $LealtadHistorial->setExternoId($Identificador);
                            $LealtadHistorial->setFechaExp(date("Y-m-d H:i:s",   strtotime($it->fechaCierre. ' '.$it->horaCierre." +" . $diasExpiracion . " days")));


                            $LealtadHistorialMySqlDAO = new LealtadHistorialMySqlDAO($Transaction);
                            $LealtadHistorialMySqlDAO->insert($LealtadHistorial);
                        }
                    }
                    if ($it->cantLineas == 4) {
                        $Clasificador = new Clasificador("", "LOYALTYBETTINGSPORTSCOMBINEDFOUR");
                        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
                        $Valorx = floatval($MandanteDetalle->valor);

                        if ($Movimiento == "E") {
                            $Clasificador = new Clasificador("", "LOYALTYBASEVALUE");

                            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
                            $valor_base = floatval($MandanteDetalle->valor);
                            $puntos = ((floatval($Valor) * $valor_base) / $Valorx);

                            $puntos = explode(".",$puntos)[0];

                            $LealtadHistorial = new LealtadHistorial();
                            $Usuario->creditPoints($puntos, $Transaction);
                            $LealtadHistorial->setUsuarioId($Usuario->usuarioId);
                            $LealtadHistorial->setDescripcion('');
                            $LealtadHistorial->setMovimiento("E");
                            $LealtadHistorial->setUsucreaId(0);
                            $LealtadHistorial->setUsumodifId(0);
                            $LealtadHistorial->setTipo($Tipo);
                            $LealtadHistorial->setValor($puntos);
                            $LealtadHistorial->setExternoId($Identificador);
                            $LealtadHistorial->setFechaExp(date("Y-m-d H:i:s",   strtotime($it->fechaCierre. ' '.$it->horaCierre." +" . $diasExpiracion . " days")));


                            $LealtadHistorialMySqlDAO = new LealtadHistorialMySqlDAO($Transaction);
                            $LealtadHistorialMySqlDAO->insert($LealtadHistorial);

                        }
                        if ($Movimiento == "S") {

                            $Clasificador = new Clasificador("", "LOYALTYBASEVALUE");

                            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
                            $valor_base = floatval($MandanteDetalle->valor);
                            $puntos = ((floatval($Valor) * $valor_base) / $Valorx);

                            $puntos = explode(".",$puntos)[0];

                            $LealtadHistorial = new LealtadHistorial();
                            $Usuario->creditPoints($puntos, $Transaction);
                            $LealtadHistorial->setUsuarioId($Usuario->usuarioId);
                            $LealtadHistorial->setDescripcion('');
                            $LealtadHistorial->setMovimiento("E");
                            $LealtadHistorial->setUsucreaId(0);
                            $LealtadHistorial->setUsumodifId(0);
                            $LealtadHistorial->setTipo($Tipo);
                            $LealtadHistorial->setValor($puntos);
                            $LealtadHistorial->setExternoId($Identificador);
                            $LealtadHistorial->setFechaExp(date("Y-m-d H:i:s",   strtotime($it->fechaCierre. ' '.$it->horaCierre." +" . $diasExpiracion . " days")));


                            $LealtadHistorialMySqlDAO = new LealtadHistorialMySqlDAO($Transaction);
                            $LealtadHistorialMySqlDAO->insert($LealtadHistorial);
                        }
                    }
                    if ($it->cantLineas == 5) {
                        $Clasificador = new Clasificador("", "LOYALTYBETTINGSPORTSCOMBINEDFIVE");
                        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
                        $Valorx = floatval($MandanteDetalle->valor);

                        if ($Movimiento == "E") {
                            $Clasificador = new Clasificador("", "LOYALTYBASEVALUE");

                            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
                            $valor_base = floatval($MandanteDetalle->valor);
                            $puntos = ((floatval($Valor) * $valor_base) / $Valorx);

                            $puntos = explode(".",$puntos)[0];

                            $LealtadHistorial = new LealtadHistorial();
                            $Usuario->creditPoints($puntos, $Transaction);
                            $LealtadHistorial->setUsuarioId($Usuario->usuarioId);
                            $LealtadHistorial->setDescripcion('');
                            $LealtadHistorial->setMovimiento("E");
                            $LealtadHistorial->setUsucreaId(0);
                            $LealtadHistorial->setUsumodifId(0);
                            $LealtadHistorial->setTipo($Tipo);
                            $LealtadHistorial->setValor($puntos);
                            $LealtadHistorial->setExternoId($Identificador);
                            $LealtadHistorial->setFechaExp(date("Y-m-d H:i:s",   strtotime($it->fechaCierre. ' '.$it->horaCierre." +" . $diasExpiracion . " days")));


                            $LealtadHistorialMySqlDAO = new LealtadHistorialMySqlDAO($Transaction);
                            $LealtadHistorialMySqlDAO->insert($LealtadHistorial);

                        }
                        if ($Movimiento == "S") {

                            $Clasificador = new Clasificador("", "LOYALTYBASEVALUE");

                            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
                            $valor_base = floatval($MandanteDetalle->valor);
                            $puntos = ((floatval($Valor) * $valor_base) / $Valorx);

                            $puntos = explode(".",$puntos)[0];

                            $LealtadHistorial = new LealtadHistorial();
                            $Usuario->creditPoints($puntos, $Transaction);
                            $LealtadHistorial->setUsuarioId($Usuario->usuarioId);
                            $LealtadHistorial->setDescripcion('');
                            $LealtadHistorial->setMovimiento("E");
                            $LealtadHistorial->setUsucreaId(0);
                            $LealtadHistorial->setUsumodifId(0);
                            $LealtadHistorial->setTipo($Tipo);
                            $LealtadHistorial->setValor($puntos);
                            $LealtadHistorial->setExternoId($Identificador);
                            $LealtadHistorial->setFechaExp(date("Y-m-d H:i:s",   strtotime($it->fechaCierre. ' '.$it->horaCierre." +" . $diasExpiracion . " days")));


                            $LealtadHistorialMySqlDAO = new LealtadHistorialMySqlDAO($Transaction);
                            $LealtadHistorialMySqlDAO->insert($LealtadHistorial);
                        }
                    }
                    if ($it->cantLineas == 6) {
                        $Clasificador = new Clasificador("", "LOYALTYBETTINGSPORTSCOMBINEDSIX");
                        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
                        $Valorx = floatval($MandanteDetalle->valor);

                        if ($Movimiento == "E") {
                            $Clasificador = new Clasificador("", "LOYALTYBASEVALUE");

                            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
                            $valor_base = floatval($MandanteDetalle->valor);
                            $puntos = ((floatval($Valor) * $valor_base) / $Valorx);

                            $puntos = explode(".",$puntos)[0];

                            $LealtadHistorial = new LealtadHistorial();
                            $Usuario->creditPoints($puntos, $Transaction);
                            $LealtadHistorial->setUsuarioId($Usuario->usuarioId);
                            $LealtadHistorial->setDescripcion('');
                            $LealtadHistorial->setMovimiento("E");
                            $LealtadHistorial->setUsucreaId(0);
                            $LealtadHistorial->setUsumodifId(0);
                            $LealtadHistorial->setTipo($Tipo);
                            $LealtadHistorial->setValor($puntos);
                            $LealtadHistorial->setExternoId($Identificador);
                            $LealtadHistorial->setFechaExp(date("Y-m-d H:i:s",   strtotime($it->fechaCierre. ' '.$it->horaCierre." +" . $diasExpiracion . " days")));


                            $LealtadHistorialMySqlDAO = new LealtadHistorialMySqlDAO($Transaction);
                            $LealtadHistorialMySqlDAO->insert($LealtadHistorial);

                        }
                        if ($Movimiento == "S") {

                            $Clasificador = new Clasificador("", "LOYALTYBASEVALUE");

                            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
                            $valor_base = floatval($MandanteDetalle->valor);
                            $puntos = ((floatval($Valor) * $valor_base) / $Valorx);

                            $puntos = explode(".",$puntos)[0];

                            $LealtadHistorial = new LealtadHistorial();
                            $Usuario->creditPoints($puntos, $Transaction);
                            $LealtadHistorial->setUsuarioId($Usuario->usuarioId);
                            $LealtadHistorial->setDescripcion('');
                            $LealtadHistorial->setMovimiento("E");
                            $LealtadHistorial->setUsucreaId(0);
                            $LealtadHistorial->setUsumodifId(0);
                            $LealtadHistorial->setTipo($Tipo);
                            $LealtadHistorial->setValor($puntos);
                            $LealtadHistorial->setExternoId($Identificador);
                            $LealtadHistorial->setFechaExp(date("Y-m-d H:i:s",   strtotime($it->fechaCierre. ' '.$it->horaCierre." +" . $diasExpiracion . " days")));


                            $LealtadHistorialMySqlDAO = new LealtadHistorialMySqlDAO($Transaction);
                            $LealtadHistorialMySqlDAO->insert($LealtadHistorial);
                        }
                    }
                    if ($it->cantLineas == 7) {
                        $Clasificador = new Clasificador("", "LOYALTYBETTINGSPORTSCOMBINEDSEVEN");
                        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
                        $Valorx = floatval($MandanteDetalle->valor);

                        if ($Movimiento == "E") {
                            $Clasificador = new Clasificador("", "LOYALTYBASEVALUE");

                            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
                            $valor_base = floatval($MandanteDetalle->valor);
                            $puntos = ((floatval($Valor) * $valor_base) / $Valorx);

                            $puntos = explode(".",$puntos)[0];

                            $LealtadHistorial = new LealtadHistorial();
                            $Usuario->creditPoints($puntos, $Transaction);
                            $LealtadHistorial->setUsuarioId($Usuario->usuarioId);
                            $LealtadHistorial->setDescripcion('');
                            $LealtadHistorial->setMovimiento("E");
                            $LealtadHistorial->setUsucreaId(0);
                            $LealtadHistorial->setUsumodifId(0);
                            $LealtadHistorial->setTipo($Tipo);
                            $LealtadHistorial->setValor($puntos);
                            $LealtadHistorial->setExternoId($Identificador);
                            $LealtadHistorial->setFechaExp(date("Y-m-d H:i:s",   strtotime($it->fechaCierre. ' '.$it->horaCierre." +" . $diasExpiracion . " days")));


                            $LealtadHistorialMySqlDAO = new LealtadHistorialMySqlDAO($Transaction);
                            $LealtadHistorialMySqlDAO->insert($LealtadHistorial);

                        }
                        if ($Movimiento == "S") {

                            $Clasificador = new Clasificador("", "LOYALTYBASEVALUE");

                            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
                            $valor_base = floatval($MandanteDetalle->valor);
                            $puntos = ((floatval($Valor) * $valor_base) / $Valorx);

                            $puntos = explode(".",$puntos)[0];

                            $LealtadHistorial = new LealtadHistorial();
                            $Usuario->creditPoints($puntos, $Transaction);
                            $LealtadHistorial->setUsuarioId($Usuario->usuarioId);
                            $LealtadHistorial->setDescripcion('');
                            $LealtadHistorial->setMovimiento("E");
                            $LealtadHistorial->setUsucreaId(0);
                            $LealtadHistorial->setUsumodifId(0);
                            $LealtadHistorial->setTipo($Tipo);
                            $LealtadHistorial->setValor($puntos);
                            $LealtadHistorial->setExternoId($Identificador);
                            $LealtadHistorial->setFechaExp(date("Y-m-d H:i:s",   strtotime($it->fechaCierre. ' '.$it->horaCierre." +" . $diasExpiracion . " days")));


                            $LealtadHistorialMySqlDAO = new LealtadHistorialMySqlDAO($Transaction);
                            $LealtadHistorialMySqlDAO->insert($LealtadHistorial);
                        }
                    }
                    if ($it->cantLineas == 8) {
                        $Clasificador = new Clasificador("", "LOYALTYBETTINGSPORTSCOMBINEDEIGHT");
                        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
                        $Valorx = floatval($MandanteDetalle->valor);

                        if ($Movimiento == "E") {
                            $Clasificador = new Clasificador("", "LOYALTYBASEVALUE");

                            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
                            $valor_base = floatval($MandanteDetalle->valor);
                            $puntos = ((floatval($Valor) * $valor_base) / $Valorx);

                            $puntos = explode(".",$puntos)[0];

                            $LealtadHistorial = new LealtadHistorial();
                            $Usuario->creditPoints($puntos, $Transaction);
                            $LealtadHistorial->setUsuarioId($Usuario->usuarioId);
                            $LealtadHistorial->setDescripcion('');
                            $LealtadHistorial->setMovimiento("E");
                            $LealtadHistorial->setUsucreaId(0);
                            $LealtadHistorial->setUsumodifId(0);
                            $LealtadHistorial->setTipo($Tipo);
                            $LealtadHistorial->setValor($puntos);
                            $LealtadHistorial->setExternoId($Identificador);
                            $LealtadHistorial->setFechaExp(date("Y-m-d H:i:s",   strtotime($it->fechaCierre. ' '.$it->horaCierre." +" . $diasExpiracion . " days")));


                            $LealtadHistorialMySqlDAO = new LealtadHistorialMySqlDAO($Transaction);
                            $LealtadHistorialMySqlDAO->insert($LealtadHistorial);

                        }
                        if ($Movimiento == "S") {

                            $Clasificador = new Clasificador("", "LOYALTYBASEVALUE");

                            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
                            $valor_base = floatval($MandanteDetalle->valor);
                            $puntos = ((floatval($Valor) * $valor_base) / $Valorx);

                            $puntos = explode(".",$puntos)[0];

                            $LealtadHistorial = new LealtadHistorial();
                            $Usuario->creditPoints($puntos, $Transaction);
                            $LealtadHistorial->setUsuarioId($Usuario->usuarioId);
                            $LealtadHistorial->setDescripcion('');
                            $LealtadHistorial->setMovimiento("E");
                            $LealtadHistorial->setUsucreaId(0);
                            $LealtadHistorial->setUsumodifId(0);
                            $LealtadHistorial->setTipo($Tipo);
                            $LealtadHistorial->setValor($puntos);
                            $LealtadHistorial->setExternoId($Identificador);
                            $LealtadHistorial->setFechaExp(date("Y-m-d H:i:s",   strtotime($it->fechaCierre. ' '.$it->horaCierre." +" . $diasExpiracion . " days")));


                            $LealtadHistorialMySqlDAO = new LealtadHistorialMySqlDAO($Transaction);
                            $LealtadHistorialMySqlDAO->insert($LealtadHistorial);

                        }
                        if ($Movimiento == "S") {

                            $Clasificador = new Clasificador("", "LOYALTYBASEVALUE");

                            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
                            $valor_base = floatval($MandanteDetalle->valor);
                            $puntos = ((floatval($Valor) * $valor_base) / $Valorx);

                            $puntos = explode(".",$puntos)[0];

                            $LealtadHistorial = new LealtadHistorial();
                            $Usuario->creditPoints($puntos, $Transaction);
                            $LealtadHistorial->setUsuarioId($Usuario->usuarioId);
                            $LealtadHistorial->setDescripcion('');
                            $LealtadHistorial->setMovimiento("E");
                            $LealtadHistorial->setUsucreaId(0);
                            $LealtadHistorial->setUsumodifId(0);
                            $LealtadHistorial->setTipo($Tipo);
                            $LealtadHistorial->setValor($puntos);
                            $LealtadHistorial->setExternoId($Identificador);
                            $LealtadHistorial->setFechaExp(date("Y-m-d H:i:s",   strtotime($it->fechaCierre. ' '.$it->horaCierre." +" . $diasExpiracion . " days")));


                            $LealtadHistorialMySqlDAO = new LealtadHistorialMySqlDAO($Transaction);
                            $LealtadHistorialMySqlDAO->insert($LealtadHistorial);

                        }
                        if ($Movimiento == "S") {

                            $Clasificador = new Clasificador("", "LOYALTYBASEVALUE");

                            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
                            $valor_base = floatval($MandanteDetalle->valor);
                            $puntos = ((floatval($Valor) * $valor_base) / $Valorx);

                            $puntos = explode(".",$puntos)[0];

                            $LealtadHistorial = new LealtadHistorial();
                            $Usuario->creditPoints($puntos, $Transaction);
                            $LealtadHistorial->setUsuarioId($Usuario->usuarioId);
                            $LealtadHistorial->setDescripcion('');
                            $LealtadHistorial->setMovimiento("E");
                            $LealtadHistorial->setUsucreaId(0);
                            $LealtadHistorial->setUsumodifId(0);
                            $LealtadHistorial->setTipo($Tipo);
                            $LealtadHistorial->setValor($puntos);
                            $LealtadHistorial->setExternoId($Identificador);
                            $LealtadHistorial->setFechaExp(date("Y-m-d H:i:s",   strtotime($it->fechaCierre. ' '.$it->horaCierre." +" . $diasExpiracion . " days")));


                            $LealtadHistorialMySqlDAO = new LealtadHistorialMySqlDAO($Transaction);
                            $LealtadHistorialMySqlDAO->insert($LealtadHistorial);
                        }
                    }
                    if ($it->cantLineas == 9) {
                        $Clasificador = new Clasificador("", "LOYALTYBETTINGSPORTSCOMBINEDNINE");
                        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
                        $Valorx = floatval($MandanteDetalle->valor);

                        if ($Movimiento == "E") {
                            $Clasificador = new Clasificador("", "LOYALTYBASEVALUE");

                            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
                            $valor_base = floatval($MandanteDetalle->valor);
                            $puntos = ((floatval($Valor) * $valor_base) / $Valorx);

                            $puntos = explode(".",$puntos)[0];

                            $LealtadHistorial = new LealtadHistorial();
                            $Usuario->creditPoints($puntos, $Transaction);
                            $LealtadHistorial->setUsuarioId($Usuario->usuarioId);
                            $LealtadHistorial->setDescripcion('');
                            $LealtadHistorial->setMovimiento("E");
                            $LealtadHistorial->setUsucreaId(0);
                            $LealtadHistorial->setUsumodifId(0);
                            $LealtadHistorial->setTipo($Tipo);
                            $LealtadHistorial->setValor($puntos);
                            $LealtadHistorial->setExternoId($Identificador);
                            $LealtadHistorial->setFechaExp(date("Y-m-d H:i:s",   strtotime($it->fechaCierre. ' '.$it->horaCierre." +" . $diasExpiracion . " days")));


                            $LealtadHistorialMySqlDAO = new LealtadHistorialMySqlDAO($Transaction);
                            $LealtadHistorialMySqlDAO->insert($LealtadHistorial);

                        }
                        if ($Movimiento == "S") {

                            $Clasificador = new Clasificador("", "LOYALTYBASEVALUE");

                            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
                            $valor_base = floatval($MandanteDetalle->valor);
                            $puntos = ((floatval($Valor) * $valor_base) / $Valorx);

                            $puntos = explode(".",$puntos)[0];

                            $LealtadHistorial = new LealtadHistorial();
                            $Usuario->creditPoints($puntos, $Transaction);
                            $LealtadHistorial->setUsuarioId($Usuario->usuarioId);
                            $LealtadHistorial->setDescripcion('');
                            $LealtadHistorial->setMovimiento("E");
                            $LealtadHistorial->setUsucreaId(0);
                            $LealtadHistorial->setUsumodifId(0);
                            $LealtadHistorial->setTipo($Tipo);
                            $LealtadHistorial->setValor($puntos);
                            $LealtadHistorial->setExternoId($Identificador);
                            $LealtadHistorial->setFechaExp(date("Y-m-d H:i:s",   strtotime($it->fechaCierre. ' '.$it->horaCierre." +" . $diasExpiracion . " days")));


                            $LealtadHistorialMySqlDAO = new LealtadHistorialMySqlDAO($Transaction);
                            $LealtadHistorialMySqlDAO->insert($LealtadHistorial);
                        }
                    }
                    if ($it->cantLineas == 10) {
                        $Clasificador = new Clasificador("", "LOYALTYBETTINGSPORTSCOMBINEDTEN");
                        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
                        $Valorx = floatval($MandanteDetalle->valor);

                        if ($Movimiento == "E") {
                            $Clasificador = new Clasificador("", "LOYALTYBASEVALUE");

                            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
                            $valor_base = floatval($MandanteDetalle->valor);
                            $puntos = ((floatval($Valor) * $valor_base) / $Valorx);

                            $puntos = explode(".",$puntos)[0];

                            $LealtadHistorial = new LealtadHistorial();
                            $Usuario->creditPoints($puntos, $Transaction);
                            $LealtadHistorial->setUsuarioId($Usuario->usuarioId);
                            $LealtadHistorial->setDescripcion('');
                            $LealtadHistorial->setMovimiento("E");
                            $LealtadHistorial->setUsucreaId(0);
                            $LealtadHistorial->setUsumodifId(0);
                            $LealtadHistorial->setTipo($Tipo);
                            $LealtadHistorial->setValor($puntos);
                            $LealtadHistorial->setExternoId($Identificador);
                            $LealtadHistorial->setFechaExp(date("Y-m-d H:i:s",   strtotime($it->fechaCierre. ' '.$it->horaCierre." +" . $diasExpiracion . " days")));


                            $LealtadHistorialMySqlDAO = new LealtadHistorialMySqlDAO($Transaction);
                            $LealtadHistorialMySqlDAO->insert($LealtadHistorial);

                        }
                        if ($Movimiento == "S") {

                            $Clasificador = new Clasificador("", "LOYALTYBASEVALUE");

                            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
                            $valor_base = floatval($MandanteDetalle->valor);
                            $puntos = ((floatval($Valor) * $valor_base) / $Valorx);

                            $puntos = explode(".",$puntos)[0];

                            $LealtadHistorial = new LealtadHistorial();
                            $Usuario->creditPoints($puntos, $Transaction);
                            $LealtadHistorial->setUsuarioId($Usuario->usuarioId);
                            $LealtadHistorial->setDescripcion('');
                            $LealtadHistorial->setMovimiento("E");
                            $LealtadHistorial->setUsucreaId(0);
                            $LealtadHistorial->setUsumodifId(0);
                            $LealtadHistorial->setTipo($Tipo);
                            $LealtadHistorial->setValor($puntos);
                            $LealtadHistorial->setExternoId($Identificador);
                            $LealtadHistorial->setFechaExp(date("Y-m-d H:i:s",   strtotime($it->fechaCierre. ' '.$it->horaCierre." +" . $diasExpiracion . " days")));


                            $LealtadHistorialMySqlDAO = new LealtadHistorialMySqlDAO($Transaction);
                            $LealtadHistorialMySqlDAO->insert($LealtadHistorial);
                        }
                    }
                    break;
                case 30:

                    $Clasificador = new Clasificador("", "LOYALTYBETCASINO");

                    $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
                    $Valorx = floatval($MandanteDetalle->valor);

                    if ($Movimiento == "E") {


                        $Clasificador = new Clasificador("", "LOYALTYBASEVALUE");

                        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
                        $valor_base = floatval($MandanteDetalle->valor);
                        $puntos = ((floatval($Valor) * $valor_base) / $Valorx);

                        $puntos = explode(".",$puntos)[0];

                        $LealtadHistorial = new LealtadHistorial();
                        $Usuario->creditPoints($puntos, $Transaction);
                        $LealtadHistorial->setUsuarioId($Usuario->usuarioId);
                        $LealtadHistorial->setDescripcion('');
                        $LealtadHistorial->setMovimiento("E");
                        $LealtadHistorial->setUsucreaId(0);
                        $LealtadHistorial->setUsumodifId(0);
                        $LealtadHistorial->setTipo($Tipo);
                        $LealtadHistorial->setValor($puntos);
                        $LealtadHistorial->setExternoId($Identificador);
                        $LealtadHistorial->setFechaExp(date("Y-m-d H:i:s", strtotime("+" . $diasExpiracion . " days")));

                        $LealtadHistorialMySqlDAO = new LealtadHistorialMySqlDAO($Transaction);
                        $LealtadHistorialMySqlDAO->insert($LealtadHistorial);

                    }
                    if ($Movimiento == "S") {


                        $Clasificador = new Clasificador("", "LOYALTYBASEVALUE");

                        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
                        $valor_base = floatval($MandanteDetalle->valor);

                        $puntos = ((floatval($Valor) * $valor_base) / $Valorx);

                        $puntos = explode(".",$puntos)[0];

                        $LealtadHistorial = new LealtadHistorial();
                        $Usuario->creditPoints($puntos, $Transaction);

                        $LealtadHistorial->setUsuarioId($Usuario->usuarioId);
                        $LealtadHistorial->setDescripcion('');
                        $LealtadHistorial->setMovimiento('E');
                        $LealtadHistorial->setUsucreaId(0);
                        $LealtadHistorial->setUsumodifId(0);
                        $LealtadHistorial->setTipo($Tipo);
                        $LealtadHistorial->setValor($puntos);
                        $LealtadHistorial->setExternoId($Identificador);
                        $LealtadHistorial->setFechaExp(date("Y-m-d H:i:s", strtotime("+" . $diasExpiracion . " days")));

                        $LealtadHistorialMySqlDAO = new LealtadHistorialMySqlDAO($Transaction);
                        $LealtadHistorialMySqlDAO->insert($LealtadHistorial);

                    }
                    break;

                case 31:

                    $Clasificador = new Clasificador("", "LOYALTYBETCASINOVIVO");

                    $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
                    $Valorx = floatval($MandanteDetalle->valor);

                    if ($Movimiento == "E") {


                        $Clasificador = new Clasificador("", "LOYALTYBASEVALUE");

                        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
                        $valor_base = floatval($MandanteDetalle->valor);
                        $puntos = ((floatval($Valor) * $valor_base) / $Valorx);

                        $puntos = explode(".",$puntos)[0];

                        $LealtadHistorial = new LealtadHistorial();
                        $Usuario->creditPoints($puntos, $Transaction);
                        $LealtadHistorial->setUsuarioId($Usuario->usuarioId);
                        $LealtadHistorial->setDescripcion('');
                        $LealtadHistorial->setMovimiento("E");
                        $LealtadHistorial->setUsucreaId(0);
                        $LealtadHistorial->setUsumodifId(0);
                        $LealtadHistorial->setTipo($Tipo);
                        $LealtadHistorial->setValor($puntos);
                        $LealtadHistorial->setExternoId($Identificador);
                        $LealtadHistorial->setFechaExp(date("Y-m-d H:i:s", strtotime("+" . $diasExpiracion . " days")));

                        $LealtadHistorialMySqlDAO = new LealtadHistorialMySqlDAO($Transaction);
                        $LealtadHistorialMySqlDAO->insert($LealtadHistorial);

                    }
                    if ($Movimiento == "S") {


                        $Clasificador = new Clasificador("", "LOYALTYBASEVALUE");

                        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
                        $valor_base = floatval($MandanteDetalle->valor);

                        $puntos = ((floatval($Valor) * $valor_base) / $Valorx);

                        $puntos = explode(".",$puntos)[0];

                        $LealtadHistorial = new LealtadHistorial();
                        $Usuario->creditPoints($puntos, $Transaction);

                        $LealtadHistorial->setUsuarioId($Usuario->usuarioId);
                        $LealtadHistorial->setDescripcion('');
                        $LealtadHistorial->setMovimiento('E');
                        $LealtadHistorial->setUsucreaId(0);
                        $LealtadHistorial->setUsumodifId(0);
                        $LealtadHistorial->setTipo($Tipo);
                        $LealtadHistorial->setValor($puntos);
                        $LealtadHistorial->setExternoId($Identificador);
                        $LealtadHistorial->setFechaExp(date("Y-m-d H:i:s", strtotime("+" . $diasExpiracion . " days")));

                        $LealtadHistorialMySqlDAO = new LealtadHistorialMySqlDAO($Transaction);
                        $LealtadHistorialMySqlDAO->insert($LealtadHistorial);

                    }
                    break;

                case 41:
                    exit();

                    $Clasificador = new Clasificador("", "LOYALTYWITHDRAWAL");
                    $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
                    $Valorx = floatval($MandanteDetalle->valor);

                    if ($Movimiento == "E") {
                        $Clasificador = new Clasificador("", "LOYALTYBASEVALUE");

                        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
                        $valor_base = floatval($MandanteDetalle->valor);
                        $puntos = ((floatval($Valor) * $valor_base) / $Valorx);

                        $puntos = explode(".",$puntos)[0];

                        $LealtadHistorial = new LealtadHistorial();
                        $Usuario->creditPoints($puntos, $Transaction);
                        $LealtadHistorial->setUsuarioId($Usuario->usuarioId);
                        $LealtadHistorial->setDescripcion('');
                        $LealtadHistorial->setMovimiento("E");
                        $LealtadHistorial->setUsucreaId(0);
                        $LealtadHistorial->setUsumodifId(0);
                        $LealtadHistorial->setTipo($Tipo);
                        $LealtadHistorial->setValor($puntos);
                        $LealtadHistorial->setExternoId($Identificador);
                        $LealtadHistorial->setFechaExp(date("Y-m-d H:i:s", strtotime("+" . $diasExpiracion . " days")));


                        $LealtadHistorialMySqlDAO = new LealtadHistorialMySqlDAO($Transaction);
                        $LealtadHistorialMySqlDAO->insert($LealtadHistorial);

                    }
                    if ($Movimiento == "S") {

                        $Clasificador = new Clasificador("", "LOYALTYBASEVALUE");

                        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
                        $valor_base = floatval($MandanteDetalle->valor);
                        $puntos = ((floatval($Valor) * $valor_base) / $Valorx);

                        $puntos = explode(".",$puntos)[0];

                        $LealtadHistorial = new LealtadHistorial();
                        $Usuario->creditPoints($puntos, $Transaction);
                        $LealtadHistorial->setUsuarioId($Usuario->usuarioId);
                        $LealtadHistorial->setDescripcion('');
                        $LealtadHistorial->setMovimiento("E");
                        $LealtadHistorial->setUsucreaId(0);
                        $LealtadHistorial->setUsumodifId(0);
                        $LealtadHistorial->setTipo($Tipo);
                        $LealtadHistorial->setValor($puntos);
                        $LealtadHistorial->setExternoId($Identificador);
                        $LealtadHistorial->setFechaExp(date("Y-m-d H:i:s", strtotime("+" . $diasExpiracion . " days")));


                        $LealtadHistorialMySqlDAO = new LealtadHistorialMySqlDAO($Transaction);
                        $LealtadHistorialMySqlDAO->insert($LealtadHistorial);

                    }
                    break;

            }

            $Transaction->commit();

        }catch (Exception $e){
            if($_ENV['debug']){
                print_r($e);
            }
           //print_r($e);
            //$Transaction->rollback();
        }

    }

    /**
     * Valida la configuración de tiempo minimo entre canjes de regalo
     * @author David Alvarez
     * @param string $usuarioId Usuario ID
     * @param string $lealtadId Lealtad ID
     * @param Transaction $transaccion Transacción de la sql
     * @return void
     * @throws Exception
     */
    public function validarTiempoMinimoEntreCanjes($usuarioId, $lealtadId, $transaccion) {
        $Usuario = new Usuario($usuarioId);
        $UsuarioMandante = new UsuarioMandante("",$Usuario->usuarioId,$Usuario->mandante);

        // Validar tiempo entre canjes de cualquier regalo
        $this->validarLapsoTiempoCanje(
            $UsuarioMandante,
            $transaccion,
            'MINIMUMTIMEBETWEENANYEXCHANGES',
            'TYPEOFTIMEFOREXCHANGEGIFTGENERAL',
            'TIMEFOREXCHANGEGIFTGENERAL',
            "SELECT * FROM usuario_lealtad WHERE usuario_id = '$usuarioId' ORDER BY fecha_crea DESC LIMIT 1"
        );

        // Validar tiempo entre canjes del mismo regalo
        $this->validarLapsoTiempoCanje(
            $UsuarioMandante,
            $transaccion,
            'EXCHANGEGIFTEVERYXTIME',
            'TYPEOFTIMEFOREXCHANGEGIFT',
            'TIMEFOREXCHANGEGIFT',
            "SELECT * FROM usuario_lealtad WHERE usuario_id = '$usuarioId' AND lealtad_id = '$lealtadId' ORDER BY fecha_crea DESC LIMIT 1"
        );
    }

    /**
     * Valida el lapso de tiempo configurado para canjes de regalos
     * @author David Alvarez
     * @param UsuarioMandante $UsuarioMandante Usuario Mandante de la plataforma
     * @param Transaction $transaccion Transacción de la sql
     * @param string $clasificadorActivo Clasificador de la configuración de partner ajustes para la validación de tiempo
     * @param string $clasificadorTipo Clasificador de la configuración de partner ajustes para el tipo de tiempo
     * @param string $clasificadorTiempo Clasificador de la configuración de partner ajustes para el tiempo
     * @param string $sql SQL para obtener el último canje de regalo
     * @return void
     * @throws Exception
     */
    private function validarLapsoTiempoCanje($UsuarioMandante, $transaccion, $clasificadorActivo, $clasificadorTipo, $clasificadorTiempo, $sql) {
        try {
            $Clasificador = new Clasificador('', $clasificadorActivo);
            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');

            if($MandanteDetalle->getValor() == "A") {
                // Tipo de tiempo (Días, Horas, Minutos)
                $Clasificador = new Clasificador('', $clasificadorTipo);
                $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
                $tipoTiempo = $MandanteDetalle->getValor();

                // Tiempo configurado
                $Clasificador = new Clasificador('', $clasificadorTiempo);
                $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
                $tiempo = $MandanteDetalle->getValor();

                $lealtadUsuario = $this->execQuery($transaccion, $sql);

                if(!empty($lealtadUsuario)) {
                    $lealtadUsuario = $lealtadUsuario[0];
                    $fechaCrea = $lealtadUsuario->{'usuario_lealtad.fecha_crea'};
                    $fechaCrea = date('Y-m-d H:i:s', strtotime($fechaCrea));
                    $fechaActual = date('Y-m-d H:i:s');

                    $lapsoTiempo = match($tipoTiempo) {
                        "D" => date('Y-m-d H:i:s', strtotime($fechaCrea . " +{$tiempo} days")),
                        "H" => date('Y-m-d H:i:s', strtotime($fechaCrea . " +{$tiempo} hours")),
                        "M" => date('Y-m-d H:i:s', strtotime($fechaCrea . " +{$tiempo} minutes"))
                    };
                    if($fechaActual < $lapsoTiempo) {
                        $date1 = date_create($fechaActual);
                        $date2 = date_create($lapsoTiempo);
                        $diff = date_diff($date1, $date2);
                        $dias = $diff->d;
                        $horas = $diff->h;
                        $minutos = $diff->i;
                        $diferencia = "";
                        if ($dias > 0) {
                            $diferencia .= "$dias día" . ($dias > 1 ? "s" : "");
                        }
                        if ($horas > 0) {
                            $diferencia .= ($diferencia ? ", " : "") . "$horas hora" . ($horas > 1 ? "s" : "");
                        }
                        if ($minutos > 0 || ($dias == 0 && $horas == 0)) {
                            $diferencia .= ($diferencia ? " y " : "") . "$minutos minuto" . ($minutos > 1 ? "s" : "");
                        }
                        if ($clasificadorActivo == 'EXCHANGEGIFTEVERYXTIME') {
                            throw new Exception("Este regalo estará disponible nuevamente en $diferencia", 300194);
                        }
                            throw new Exception("Por favor espera $diferencia antes de hacer otro canje", 300156);

                    }
                }
            }
        } catch (Exception $e) {
            if($e->getCode() == 300156 || $e->getCode() == 300194) {
                throw $e;
            }
        }
    }
}

?>
