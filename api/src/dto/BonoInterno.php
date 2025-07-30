<?php
namespace Backend\dto;

use PDO;
use Exception;
use Throwable;
use \CurlWrapper;
use Backend\sql\Transaction;
use Backend\mysql\BonoLogMySqlDAO;
use Backend\sql\ConnectionProperty;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\mysql\UsuarioBonoMySqlDAO;
use Backend\dto\SubproveedorMandantePais;
use Backend\mysql\TransjuegoInfoMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\integrations\casino\EGTSERVICES;
use Backend\integrations\casino\RAWSERVICES;
use Backend\integrations\casino\BRAGGSERVICES;
use Backend\integrations\casino\LAMBDASERVICES;
use Backend\integrations\casino\MASCOTSERVICES;
use Backend\integrations\casino\MERKURSERVICES;
use Backend\integrations\casino\PASCALSERVICES;
use Backend\integrations\casino\PGSOFTSERVICES;
use Backend\integrations\casino\AIRDICESERVICES;
use Backend\integrations\casino\RFRANCOSERVICES;
use Backend\integrations\casino\AMUSNETSERVICES;
use Backend\integrations\casino\BELATRASERVICES;
use Backend\integrations\casino\BOOMINGSERVICES;
use Backend\integrations\casino\EVOPLAYSERVICES;
use Backend\integrations\casino\EXPANSESERVICES;
use Backend\integrations\casino\MANCALASERVICES;
use Backend\integrations\casino\PLAYNGOSERVICES;
use Backend\integrations\casino\PLAYSONSERVICES;
use Backend\integrations\casino\TOMHORNSERVICES;
use Backend\integrations\casino\CTGAMINGSERVICES;
use Backend\integrations\casino\GALAXSYSSERVICES;
use Backend\integrations\casino\KAGAMINGSERVICES;
use Backend\integrations\casino\ONLYPLAYSERVICES;
use Backend\integrations\casino\PLATIPUSSERVICES;
use Backend\integrations\casino\PLAYTECHSERVICES;
use Backend\integrations\casino\RUBYPLAYSERVICES;
use Backend\integrations\casino\PRAGMATICSERVICES;
use Backend\integrations\casino\SMARTSOFTSERVICES;
use Backend\integrations\casino\SOFTSWISSSERVICES;
use Backend\integrations\casino\ENDORPHINASERVICES;
use Backend\integrations\casino\SPINOMENALSERVICES;
use Backend\integrations\casino\TADAGAMINGSERVICES;
use Backend\integrations\casino\AMIGOGAMINGSERVICES;
use Backend\integrations\casino\G7777GAMINGSERVICES;
use Backend\integrations\casino\GAMESGLOBALSERVICES;
use Backend\integrations\casino\REDRAKESERVICESBONUS;

/**
 * Clase 'BonoInterno'
 *
 * Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
 * para la tabla 'BonoInterno'
 *
 * Ejemplo de uso:
 * $BonoInterno = new BonoInterno();
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class BonoInterno
{

    /**
     * Representación de la columna 'bonoId' de la tabla 'BonoInterno'
     *
     * @var string
     */
    var $bonoId;

    /**
     * Representación de la columna 'fechaInicio' de la tabla 'BonoInterno'
     *
     * @var string
     */
    var $fechaInicio;

    /**
     * Representación de la columna 'fechaFin' de la tabla 'BonoInterno'
     *
     * @var string
     */
    var $fechaFin;

    /**
     * Representación de la columna 'descripcion' de la tabla 'BonoInterno'
     *
     * @var string
     */
    var $descripcion;

    /**
     * Representación de la columna 'nombre' de la tabla 'BonoInterno'
     *
     * @var string
     */
    var $nombre;

    /**
     * Representación de la columna 'tipo' de la tabla 'BonoInterno'
     *
     * @var string
     */
    var $tipo;

    /**
     * Representación de la columna 'estado' de la tabla 'BonoInterno'
     *
     * @var string
     */
    var $estado;

    /**
     * Representación de la columna 'fechaModif' de la tabla 'BonoInterno'
     *
     * @var string
     */
    var $fechaModif;

    /**
     * Representación de la columna 'fechaCrea' de la tabla 'BonoInterno'
     *
     * @var string
     */
    var $fechaCrea;

    /**
     * Representación de la columna 'mandante' de la tabla 'BonoInterno'
     *
     * @var string
     */
    var $mandante;

    /**
     * Representación de la columna 'condicional' de la tabla 'BonoInterno'
     *
     * @var string
     */
    var $condicional;

    /**
     * Representación de la columna 'orden' de la tabla 'BonoInterno'
     *
     * @var string
     */
    var $orden;

    /**
     * Representación de la columna 'cupoActual' de la tabla 'BonoInterno'
     *
     * @var string
     */
    var $cupoActual;

    /**
     * Representación de la columna 'cupoMaximo' de la tabla 'BonoInterno'
     *
     * @var string
     */
    var $cupoMaximo;

    /**
     * Representación de la columna 'cantidadBonos' de la tabla 'BonoInterno'
     *
     * @var string
     */
    var $cantidadBonos;

    /**
     * Representación de la columna 'maximoBonos' de la tabla 'BonoInterno'
     *
     * @var string
     */
    var $maximoBonos;

    /**
     * Representación de la columna 'usucreaId' de la tabla 'BonoInterno'
     *
     * @var string
     */
    var $usucreaId;

    /**
     * Representación de la columna 'usumodifId' de la tabla 'BonoInterno'
     *
     * @var string
     */
    var $usumodifId;

    /**
     * Representación de la columna 'imagen' de la tabla 'BonoInterno'
     *
     * @var string
     */
    var $imagen;

    /**
     * Representación de la columna 'reglas' de la tabla 'BonoInterno'
     *
     * @var string
     */
    var $reglas;
    var $codigo;
    var $publico;
    var $permiteBonos;
    var $perteneceCrm;

    var $categoriaCampaña;

    var $detallesCampaña;

    var $tipoAccion;

    var $jsonTemp;

    /**
     * Constructor de clase
     *
     *
     * @param String $bonoId id del bono
     *
     * @throws Exception si el BonoInterno no existe
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function __construct($bonoId = "")
    {
        if ($bonoId != "") {

            $this->bonoId = $bonoId;

            $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();

            $BonoInterno = $BonoInternoMySqlDAO->load($this->bonoId);


            if ($BonoInterno != null && $BonoInterno != "") {
                $this->bonoId = $BonoInterno->bonoId;
                $this->fechaInicio = $BonoInterno->fechaInicio;
                $this->fechaFin = $BonoInterno->fechaFin;
                $this->descripcion = $BonoInterno->descripcion;
                $this->nombre = $BonoInterno->nombre;
                $this->tipo = $BonoInterno->tipo;
                $this->estado = $BonoInterno->estado;
                $this->fechaModif = $BonoInterno->fechaModif;
                $this->fechaCrea = $BonoInterno->fechaCrea;
                $this->mandante = $BonoInterno->mandante;
                $this->usucreaId = $BonoInterno->usucreaId;
                $this->usumodifId = $BonoInterno->usumodifId;
                $this->condicional = $BonoInterno->condicional;
                $this->orden = $BonoInterno->orden;
                $this->cupoActual = $BonoInterno->cupoActual;
                $this->cupoMaximo = $BonoInterno->cupoMaximo;
                $this->cantidadBonos = $BonoInterno->cantidadBonos;
                $this->maximoBonos = $BonoInterno->maximoBonos;
                $this->imagen = $BonoInterno->imagen;
                $this->reglas = $BonoInterno->reglas;
                $this->codigo = $BonoInterno->codigo;
                $this->publico = $BonoInterno->publico;
                $this->permiteBonos = $BonoInterno->permiteBonos;
                $this->perteneceCrm = $BonoInterno->perteneceCrm;
                $this->categoriaCampaña = $BonoInterno->categoriaCampaña;
                $this->detallesCampaña = $BonoInterno->detallesCampaña;
                $this->tipoAccion = $BonoInterno->tipoAccion;
                $this->jsonTemp = $BonoInterno->jsonTemp;


            } else {
                throw new Exception("No existe " . get_class($this), "28");
            }

        }
    }


    /**
     * Realizar una consulta en la tabla de bonos 'bono_custom'
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
     * @throws Exception si los bonos no existen
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function getBonosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();

        $bonos = $BonoInternoMySqlDAO->queryBonosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($bonos != null && $bonos != "") {
            return $bonos;
        } else {
            throw new Exception("No existe " . get_class($this), "01");
        }

    }

    /**
     * Ejecutar un insert
     *
     *
     *
     * @param Objeto $transaction transaccion
     *
     * @return boolean $ resultado de la ejecución
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function insert($transaction)
    {

        $BonoInternoMySqlDAO = new BonoInternoMySqlDAO($transaction);

        return $BonoInternoMySqlDAO->insert($this);

    }

    /**
     *
     * Propósito: Funcion para validar condicionales alojados en bono detalles para saber si cliente puede redimir un bono
     *
     * Descripción de variables:
     *
     *
     * - $bonoDetalles: array que contiene la respuesta de la consulta a iterar como detalles del bono
     * - $detalles: array con los detalles del bono
     * - $tipoProducto: string que contiene el tipo de producto ya sea Sport o Casino para rollower
     * - $usuarioId: string que contiene el id del usuario
     * - $isForLealtad: Si el bono es por lealtad  booleano
     * - $cumpleCondiciones: boleano indica la inicialización del cumple condiciones
     * - $transaccion: string transaccion relacionada a la seguridad del proceso
     * - $bono: array con el bono elegido en bonos disponibles
     * - $tipoBono: string  tipo de bono 2 (Deposito),3 (No deposito),5 (FreeCasino),6(FreeBet),8 (FreeSpin)
     * - $extra: bool  true para agregarbono() verifica detalles de bono deposito
     *
     * @param String $bonoDetalles
     * @param String $detalles
     * @param String $tipoProducto
     * @param String $usuarioId
     * @param String $transaccion
     * @param String $bono
     * @param String $tipoBono
     * @param String $extra
     *
     * @return Array resultado de la consulta
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function validarCondiciones($bonoDetalles, $detalles, $tipoProducto, $usuarioId, $isForLealtad, $cumpleCondiciones, $transaccion, $bono, $tipoBono, $extra = false, $isForRollover = false)
    {
        $respuesta = array();
        $valorbono = 0;
        $ValorBono = 0;
        $prefix = '';
        $rollowerValor = 0;
        $CONDSUBPROVIDER = array();
        $CONDGAME = array();
        //Verificamos Detalles a tomar en cuenta al momento de verificar si puede ganar un bono
        $detalleDepositos = $detalles->Depositos;
        $detalleValorApuesta = $detalles->ValorApuesta;
        $detalleDepositoEfectivo = $detalles->DepositoEfectivo;
        $detalleDepositoMetodoPago = $detalles->MetodoPago;
        $detalleValorDeposito = $detalles->ValorDeposito;
        $detallePaisPV = $detalles->PaisPV;
        $detalleDepartamentoPV = $detalles->DepartamentoPV;
        $detalleCiudadPV = $detalles->CiudadPV;
        $detalleSelecciones = $detalles->Selecciones;
        $detalleJuegosCasino = $detalles->JuegosCasino;
        $detalleReferidoId = $detalles->ReferidoId;

        $cumplecondicionproductoTipo = true;
        $CodePromo = $detalles->CodePromo;
        $cumplecondicion = true;

        //Inicializamos variables
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

        $condicionBonoReferente = false;
        $condicionBonoReferenteCount = 0;

        $detallePaisUSER = $detalles->PaisUSER;
        $detalleDepartamentoUSER = $detalles->DepartamentoUSER;
        $detalleCiudadUSER = $detalles->CiudadUSER;
        $detalleMonedaUSER = $detalles->MonedaUSER;
        if ($detalleMonedaUSER == null) $detalleMonedaUSER = '';
        $betmode = $detalles->BetMode;

        $detallePuntoVenta = $detalles->PuntoVenta;
        $detalleCuotaTotal = $detalles->CuotaTotal;

        $condicionesproducto = 0;
        $valorASumar = 0;


        $condicionTrigger = true;
        $tipobono = "";
        $puederepetirBono = false;
        $ganaBonoId = 0;
        $rollowerBono = 0;
        $rollowerDeposito = 0;


        $maximopago = 0;
        $minimodeposito = 0;
        $tipoProductoGlobal = $tipoProducto;
        $tipoProducto = null;
        $bonoTieneRollower = false;
        $tiposaldo = -1;

        $expDia = '';
        $expFecha = '';

        $AMOUNTBONUSMAXSPIN = 0;
        $valorMaximoASumar = 0;

        $bonusPlanIdAltenar = '';
        $bonusCodeAltenar = '';

        if ($bono->condicional == 'NA' || $bono->condicional == '') {
            $tipocomparacion = "OR";

        } else {
            $tipocomparacion = $bono->condicional;

        }
        //Recorremos la tabla bono_detalles donde se alojan detalles de los bonos
        // y almacenamos variables a tener en cuenta
        foreach ($bonoDetalles as $bonoDetalle) {
            $bonoDetalle->tipo = $bonoDetalle->{'a.tipo'};
            $bonoDetalle->valor = $bonoDetalle->{'a.valor'};
            $bonoDetalle->moneda = $bonoDetalle->{'a.moneda'};

            //Discriminamos por tipo de bono
            switch ($bonoDetalle->tipo) {
                //TIPOPRODUCTO = Indica el tipo de producto relacionado con el bono
                // Guardamos lo que existe en la columna "valor" de la tabla bono_detalle en $tipoproducto
                case "TIPOPRODUCTO":

                    $tipoProducto = $tipoProducto === null ? $bonoDetalle->valor : $tipoProducto;


                    break;

                // CANTDEPOSITOS = Indica el número de depósitos requeridos para calificar o recibir el bono
                case "CANTDEPOSITOS":
                    //Verificamos si lo ingresado por detalles no coincide con lo que hay en la tabla bono_detalle, que no estén vacios y que el tipo de bono sea 2 (Deposito)
                    if ($detalleDepositos != ($bonoDetalle->valor - 1) && $bonoDetalle->valor != 0 && $bono->tipo == 2 && $extra) {
                        //Desactivamos cumpleCondiciones
                        $cumpleCondiciones = false;

                        //$BonoInterno = new BonoInterno();
                        //Creamos SQL para usuarios de bonos que no cumplen condiciones
                        $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                    VALUES ('{$usuarioId}','BONO','{$bono->bono_id}','{$tipoBono}','1','CANTDEPOSITOS')";
                        //$BonoInterno->execQuery($transaccion, $sqlLog);

                    }

                    break;

                // Evalua si existe o no un deposito o algún valor en la  columna "valor" de la tabla bono_detalle
                // si lo hay $condicionmetodoPago = true y aumenta el contador de este condicional
                case "CONDEFECTIVO":
                    if ($detalleDepositoEfectivo) {
                        if (($bonoDetalle->valor == "true")) {
                            $condicionmetodoPago = true;
                        }
                    } else {
                        if (($bonoDetalle->valor != "true")) {
                            $condicionmetodoPago = false;
                            //Creamos una nueva instancia de la clase BonoInterno
                            //$BonoInterno = new BonoInterno();
                            //Creamos SQL para usuarios de bonos que no cumplen condiciones
                            $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                        VALUES ('{$usuarioId}','BONO','{$bono->bono_id}','{$tipoBono}','1','CONDEFECTIVO')";
                            //*** SQL no se ejecuta
                            //$BonoInterno->execQuery($transaccion, $sqlLog);
                        }
                    }
                    $condicionmetodoPagocount++;


                    break;

                //Define el tipo de bono como "Porcentaje"
                // Guardamos lo que existe en la columna "valor" de la tabla bono_detalle en $valorbono
                case "PORCENTAJE":
                    $tipobono = "PORCENTAJE";
                    $valorbono = $bonoDetalle->valor;

                    break;


                case "NUMBERCARTONS":
                    $cantidadCartones = $bonoDetalle->valor;
                    break;

                //"MAXJUGADORES": número máximo de jugadores permitidos para ganarse el bono.
                case "MAXJUGADORES":
                    //Verificamos si el tipo de bono es Deposito y que no sea cero
                    if ($bono->tipo == 2 && $bonoDetalle->valor != '0' && $extra) {
                        //Seleccionamos usubono_id de la tabla usuario_bono  donde
                        // el bono_id debe ser el $bono->bono_id de la tabla bono_interno
                        // usuario_id debe ser la variable $usuarioId
                        // y el estado debe ser 'P'
                        $sqlDetallebonoPendiente = "SELECT a.usubono_id FROM usuario_bono a WHERE a.bono_id='" . $bono->bono_id . "' AND a.usuario_id='" . $usuarioId . "' AND a.estado='P'";

                        //Ejecutamos SQL
                        $bonoDetallesPendiente = $this->execQuery($transaccion, $sqlDetallebonoPendiente);


                        if (oldCount($bonoDetallesPendiente) > 0) {
                            //Guardamos condicion de la consulta
                            $condicionTriggerPosterior = $bonoDetallesPendiente[0]->{'a.usubono_id'};
                            //Si la condicion vino vacia
                            if ($condicionTriggerPosterior == "" || $condicionTriggerPosterior == 0) {
                                //Negamos cumpleCondiciones
                                $cumpleCondiciones = false;
                                //$BonoInterno = new BonoInterno();
                                $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                            VALUES ('{$usuarioId}','BONO','{$bono->bono_id}','{$tipobono}','1','MAXJUGADORES')";
                                //$BonoInterno->execQuery($transaccion, $sqlLog);
                            }
                        } else {
                            //Negamos cumpleCondiciones
                            $cumpleCondiciones = false;
                            //$BonoInterno = new BonoInterno();
                            $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                            VALUES ('{$usuarioId}','BONO','{$bono->bono_id}','{$tipobono}','1','MAXJUGADORES')";
                            //$BonoInterno->execQuery($transaccion, $sqlLog);
                            /* $sqlDetalleBonoPendiente = "SELECT a.usubono_id FROM usuario_bono a WHERE a.bono_id='" . $bono->bono_id . "' AND a.usuario_id='0' AND a.estado='L'";
                                 $bonoDetallesPendiente = $this->execQuery($transaccion, $sqlDetalleBonoPendiente);

                                 if (oldCount($bonoDetallesPendiente) > 0) {
                                     $condicionTriggerPosterior=$bonoDetallesPendiente[0]->{'a.usubono_id'};
                                     if($condicionTriggerPosterior == "" || $condicionTriggerPosterior==0){
                                         $cumpleCondiciones = false;

                                     }

                                 }else{
                                     $cumpleCondiciones = false;

                                 }
    */
                        }
                    }
                    break;

                //MAXPAGO Representa el valor máximo de pago permitido
                case "MAXPAGO":
                    // si moneda de la tabla bono_detalles es igual a la de detalles
                    if ($bonoDetalle->moneda == $detalleMonedaUSER) {
                        // Guardamos lo que existe en la columna "valor" de la tabla bono_detalle en $maximopago
                        $maximopago = $bonoDetalle->valor;

                    }
                    //$maximopago = $bonoDetalle->valor; // BonoFree

                    break;

                //MAXDEPOSITO monto máximo de depósito permitido para calificar o recibir el bono.
                case "MAXDEPOSITO":
                    if ($extra) {
                        //Guardamos lo que existe en la columna "valor" de la tabla bono_detalle en $maximodeposito.
                        $maximodeposito = $bonoDetalle->valor;
                        // si moneda de la tabla bono_detalles es igual a la de detalles
                        if ($bonoDetalle->moneda == $detalleMonedaUSER) {
                            //Si existen incoherencias entre detalleValorDeposito  y maximodeposito
                            if ($detalleValorDeposito > $maximodeposito) {
                                $cumpleCondiciones = false;
                                //Creamos una nueva instancia de la clase BonoInterno
                                //$BonoInterno = new BonoInterno();
                                //Creamos SQL para usuarios de bonos que no cumplen condiciones
                                $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                            VALUES ('{$usuarioId}','BONO','{$bono->bono_id}','{$tipoBono}','1','MAXDEPOSITO')";
                                //**** SQL no se ejecuta
                                //$BonoInterno->execQuery($transaccion, $sqlLog);
                            }
                        }
                    }

                    break;
                //MINDEPOSITO monto mínimo de depósito requerido para ser elegible para el bono
                case "MINDEPOSITO":
                    if ($extra) {
                        //Guardamos lo que existe en la columna "valor" de la tabla bono_detalle en $minimodeposito.
                        $minimodeposito = $bonoDetalle->valor;
                        // si moneda de la tabla bono_detalles es igual a la de detalles
                        if ($bonoDetalle->moneda == $detalleMonedaUSER) {

                            if ($detalleValorDeposito < $minimodeposito) {
                                $cumpleCondiciones = false;
                                //Creamos una nueva instancia de la clase BonoInterno
                                //$BonoInterno = new BonoInterno();
                                //Creamos SQL para usuarios de bonos que no cumplen condiciones
                                $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                                    VALUES ('{$usuarioId}','BONO','{$bono->bono_id}','{$tipoBono}','1','MINDEPOSITO')";
                                //**** SQL no se ejecuta
                                //$BonoInterno->execQuery($transaccion, $sqlLog);
                            }
                        }
                    }
                    break;

                // VALORBONO Indica el valor del bono otorgado al usuario.
                case "VALORBONO":
                    // si moneda de la tabla bono_detalles es igual a la de detalles
                    if ($bonoDetalle->moneda == $detalleMonedaUSER) {
                        //Guardamos lo que existe en la columna "valor" de la tabla bono_detalle en $valorbono.
                        $valorbono = $bonoDetalle->valor;
                        //Actualizamos tipobono
                        $tipobono = "VALOR";
                    }
                    break;

                //CONDPAYMENT Representa los sistemas de pago específicos.
                case "CONDPAYMENT":
                    //Verificamos si lo que viene por detalles y lo que hay en la tabla $bonoDetalle->valor son iguales y si bonodetalle no viene vacio
                    if ($extra) {
                        if ($detalleDepositoMetodoPago == $bonoDetalle->valor && $bonoDetalle->valor != '') {
                            //Activamos condicionmetodopago
                            $condicionmetodoPago = true;

                        }
                        //Verificamos que bonodetalle no viene vacio
                        if ($bonoDetalle->valor != '') {
                            $condicionmetodoPagocount++;
                        }
                    }
                    break;

                // CONDPAISPV Establece condiciones de participación por país para el bono (puede restringir)
                case "CONDPAISPV":
                    //Suma al contador, valida si la tabla bono_detalle contiene lo mismo que el valor ingresado en detalles
                    $condicionPaisPVcount = $condicionPaisPVcount + 1;
                    if ($bonoDetalle->valor == $detallePaisPV) {
                        //condicionPaisPV se activa
                        $condicionPaisPV = true;
                    }

                    if ($condicionPaisPV == false) {
                        //Creamos una nueva instancia de la clase BonoInterno
                        //$BonoInterno = new BonoInterno();
                        $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                            VALUES ('{$usuarioId}','BONO','{$bono->bono_id}','{$tipobono}','1','CONDPAISPV')";
                        //$BonoInterno->execQuery($transaccion, $sqlLog);
                    }

                    break;

                // CONDDEPARTAMENTOPV Representa los departamentos específicos dentro de un país que califican para el bono.
                //Suma al contador, valida si la base de datos contiene lo mismo que el valor ingresado en detalles
                // si es correcto la condicionDepartamentoPV se activa
                case "CONDDEPARTAMENTOPV":

                    $condicionDepartamentoPVcount = $condicionDepartamentoPVcount + 1;
                    if ($bonoDetalle->valor == $detalleDepartamentoPV) {
                        $condicionDepartamentoPV = true;
                    }

                    if ($condicionDepartamentoPV == false) {
                        //$BonoInterno = new BonoInterno();
                        $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                        VALUES ('{$usuarioId}','BONO','{$bono->bono_id}','{$tipobono}','1','CONDDEPARTAMENTOPV')";
                        //$BonoInterno->execQuery($transaccion, $sqlLog);
                    }
                    break;

                // CONDCIUDADPV Indica las ciudades específicas qu ecalifican pararecibir el bono.
                //Suma al contador, valida si la base de datos contiene lo mismo que el valor ingresado en detalles
                // si es correcto la condicionCiudadPV se activa
                case "CONDCIUDADPV":

                    $condicionCiudadPVcount = $condicionCiudadPVcount + 1;

                    if ($bonoDetalle->valor == $detalleCiudadPV) {
                        $condicionCiudadPV = true;
                    }

                    if ($condicionCiudadPV == false) {
                        //$BonoInterno = new BonoInterno();
                        $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                        VALUES ('{$usuarioId}','BONO','{$bono->bono_id}','{$tipobono}','1','CONDCIUDADPV')";
                        // $BonoInterno->execQuery($transaccion, $sqlLog);
                    }

                    break;

                //CONDPAISUSER Define las condiciones de participación por paísdelusuario.
                //Suma al contador, valida si la base de datos contiene lo mismo que el valor ingresado en detalles
                // si es correcto la condicionPaisUSER se activa
                case "CONDPAISUSER":

                    $condicionPaisUSERcount = $condicionPaisUSERcount + 1;

                    if ($bonoDetalle->valor == $detallePaisUSER) {

                        $condicionPaisUSER = true;
                    }

                    if ($condicionPaisUSER == false) {
                        //$BonoInterno = new BonoInterno();
                        $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                        VALUES ('{$usuarioId}','BONO','{$bono->bono_id}','{$tipobono}','1','CONDPAISUSER')";
                        //$BonoInterno->execQuery($transaccion, $sqlLog);
                    }

                    break;

                // CONDDEPARTAMENTOUSER Especifica las condiciones de participación pordepartamentodelusuario.
                //Suma al contador, valida si la base de datos contiene lo mismo que el valor ingresado en detalles
                //si es correcto la condicionDepartamentoUSER se activa
                case "CONDDEPARTAMENTOUSER":

                    $condicionDepartamentoUSERcount = $condicionDepartamentoUSERcount + 1;
                    if ($bonoDetalle->valor == $detalleDepartamentoUSER) {
                        $condicionDepartamentoUSER = true;
                    }

                    if ($condicionDepartamentoUSER == false) {
                        //$BonoInterno = new BonoInterno();
                        $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                        VALUES ('{$usuarioId}','BONO','{$bono->bono_id}','{$tipobono}','1','CONDDEPARTAMENTOUSER')";
                        //$BonoInterno->execQuery($transaccion, $sqlLog);
                    }
                    break;

                //CONDCIUDADUSER Asigna las condiciones de participación por ciudad del usuario.
                //Suma al contador, valida si la base de datos contiene lo mismo que el valor ingresado en detalles
                //si es correcto la condicionCiudadUSER se activa
                case "CONDCIUDADUSER":

                    $condicionCiudadUSERcount = $condicionCiudadUSERcount + 1;

                    if ($bonoDetalle->valor == $detalleCiudadUSER) {
                        $condicionCiudadUSER = true;
                    }

                    if ($condicionCiudadUSER == false) {
                        //$BonoInterno = new BonoInterno();
                        $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                        VALUES ('{$usuarioId}','BONO','{$bono->bono_id}','{$tipobono}','1','CONDCIUDADUSER')";
                        //$BonoInterno->execQuery($transaccion, $sqlLog);
                    }


                    break;

                //CONDPUNTOVENTA Establece las condiciones relacionadas con los puntosdeventa
                // Suma al contador, valida si la base de datos contiene lo mismo que el valor ingresado en detalles
                // si es correcto la condicionPuntoVenta se activa
                case "CONDPUNTOVENTA":

                    $condicionPuntoVentacount = $condicionPuntoVentacount + 1;

                    if ($bonoDetalle->valor == $detallePuntoVenta) {
                        $condicionPuntoVenta = true;
                    }

                    if ($condicionPuntoVenta == false) {
                        //$BonoInterno = new BonoInterno();
                        $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                        VALUES ('{$usuarioId}','BONO','{$bono->bono_id}','{$tipobono}','1','CONDPUNTOVENTA')";
                        //$BonoInterno->execQuery($transaccion, $sqlLog);
                    }
                    break;

                case 'PREFIX':
                    $prefix = $bonoDetalle->valor;
                    break;

                // EXPDIA Determina la duración en días que un bono permanecerá activo antes de expirar
                // Guardamos lo que existe en la columna "valor" de la tabla bono_detalle en $expDia, expFecha
                // lo inicializamos vacio
                case "EXPDIA":
                    $fechabono = date('Y-m-d H:i:s', strtotime($bono->fecha_crea . ' + ' . $bonoDetalle->valor . ' days'));
                    $fecha_actual = date("Y-m-d H:i:s", time());

                    if ($fechabono < $fecha_actual) {
                        //$cumplecondicion = false;
                    }
                    $expDia = $bonoDetalle->valor;
                    $expFecha = '';
                    break;

                // EXPFECHA Indica la fecha de vencimiento asociada con el bono.
                // Guardamos lo que existe en la columna "valor" de la tabla bono_detalle en $expFecha, expDia
                // lo inicializamos vacio
                case "EXPFECHA":
                    $fechabono = date('Y-m-d H:i:s', strtotime($bonoDetalle->valor));
                    $fecha_actual = strtotime(date("Y-m-d H:i:s", time()));

                    if ($fechabono < $fecha_actual) {
                        //$cumplecondicion = false;
                    }
                    $expDia = '';
                    $expFecha = $bonoDetalle->valor;

                    break;

                // Evaluamos el valor en la base de datos, si este valor es diferente de cero, activamos
                //bonoTieneRollower y guardamos lo que existe en la columna "valor" de la tabla bono_detalle en
                // $rollowerBono
                case "WFACTORBONO":
                    if ($bonoDetalle->valor != '0') {
                        $bonoTieneRollower = true;

                        $rollowerBono = $bonoDetalle->valor;
                    }
                    break;

                // Evaluamos el valor en la base de datos, si este valor es diferente de cero, activamos
                //bonoTieneRollower y guardamos lo que existe en la columna "valor" de la tabla bono_detalle en
                // $rollowerDeposito
                case "WFACTORDEPOSITO":
                    if ($bonoDetalle->valor != '0') {

                        $bonoTieneRollower = true;
                        $rollowerDeposito = $bonoDetalle->valor;
                    }
                    break;

                //VALORROLLOWER Representa el valor del rollover requerido para el bono.
                // Verifica si en la base de datos la moneda es la misma que la de detalles ingresado y si la
                // columna "valor" en esa posición no valga 0 si se cumple  activamos bonoTieneRollower y guardamos
                // lo que existe en la columna "valor" de la tabla bono_detalle en $rollowerValor
                case "VALORROLLOWER":
                    if ($bonoDetalle->moneda == $detalleMonedaUSER && $bonoDetalle->valor != '0') {

                        $bonoTieneRollower = true;
                        $rollowerValor = $bonoDetalle->valor;
                    }

                    break;
                // REPETIRBONO Indica si se puede repetir el bono.
                // Verificamos si hay dato en la tabla en la columna valor, si si activamos $puederepetirBono
                case "REPETIRBONO":
                    if ($bonoDetalle->valor) {
                        $puederepetirBono = true;
                    }
                    break;

                // Guardamos lo que existe en la columna "valor" de la tabla bono_detalle en $ganaBonoId
                // actualizamos tipobono y valor_bono
                case "WINBONOID":

                    $ganaBonoId = $bonoDetalle->valor;
                    $tipobono = "WINBONOID";
                    $valor_bono = 0;

                    break;

                //TIPOSALDO Representa el tipo de saldo
                //Guardamos lo que existe en la columna "valor" de la tabla bono_detalle en $tiposaldo
                case "TIPOSALDO":
                    $tiposaldo = $bonoDetalle->valor;

                    break;

                //Si las apuestas son por PreLive o Live
                case "LIVEORPREMATCH":
                    if (!$isForRollover){
                        continue;
                    }
                    if ($bonoDetalle->valor == 2) {
                        if ($betmode == "PreLive") {
                            $cumplecondicionproductoTipo = true;

                        } else {
                            $cumplecondicionproductoTipo = false;


                        }

                    }

                    if ($bonoDetalle->valor == 1) {
                        if ($betmode == "Live") {
                            $cumplecondicionproductoTipo = true;

                        } else {
                            $cumplecondicionproductoTipo = false;


                        }

                    }

                    if ($bonoDetalle->valor == 0) {
                        /*if($betmode == "Mixed") {
                            $cumplecondicionproductoTipo = true;

                        }else{
                            $cumplecondicionproductoTipo = false;


                        }*/

                    }

                    break;

                // Establece el número mínimo de selecciones requeridas para realizar una apuesta
                case "MINSELCOUNT":
                    if (!$isForRollover){
                        continue;
                    }

                    $minselcount = $bonoDetalle->valor;

                    if ($bonoDetalle->valor > oldCount($detalleSelecciones)) {
                        $cumplecondicion = false;

                    }

                    break;

                //Define el precio mínimo de selección para realizar una apuesta
                case "MINSELPRICE":
                    if (!$isForRollover){
                        continue;
                    }
                    foreach ($detalleSelecciones as $item) {
                        if ($bonoDetalle->valor > $item->Cuota) {
                            $cumplecondicion = false;

                        }
                    }

                    break;
                // Define el precio total mínimo de selección para realizar una apuesta
                case "MINSELPRICETOTAL":
                    if (!$isForRollover){
                        continue;
                    }
                    if ($bonoDetalle->valor > $detalleCuotaTotal) {
                        $cumplecondicion = false;

                    }
                    break;

                // Establece el monto mínimo de apuesta especificado en diferentes monedas si es aplicable.
                case "MINBETPRICE":
                    if (!$isForRollover){
                        continue;
                    }
                    if ($bonoDetalle->valor > $detalleValorApuesta) {
                        $cumplecondicion = false;

                    }
                    break;

                //Minimo monto para rollower
                case 'MINBETAMOUNTROLLOVER':
                    if (!$isForRollover){
                        continue;
                    }
                    if ($bonoDetalle->valor > $detalleValorApuesta) {
                        $cumplecondicion = false;
                    }
                    break;

                case "AMOUNTBONUSMAXSPIN":
                    $AMOUNTBONUSMAXSPIN = $bonoDetalle->valor;

                    break;

                //Minimo monto permitido
                case "MINAMOUNT":
                    if ($bonoDetalle->moneda == $detalleMonedaUSER) {
                        $ValorBono = $bonoDetalle->valor;
                    }
                    break;

                //Maximo monto permitido
                case "MAXAMOUNT":
                    if ($bonoDetalle->moneda == $detalleMonedaUSER) {
                        $Valorbono2 = $bonoDetalle->valor;
                    }
                    break;

                // Si el bono viene por Altenar
                case "BONUSPLANIDALTENAR":
                    $bonusPlanIdAltenar = $bonoDetalle->valor;

                    break;

                // Si el bono es por un referente
                case "BONOREFERENTE" :
                    $condicionBonoReferenteCount += 1;
                    $condicionBonoReferente = $bonoDetalle->valor;
                    break;

                // BONUSCODEALTENAR Codigo del bono si tiene altenar
                // Guardamos lo que existe en la columna "valor" de la tabla bono_detalle en $bonusCodeAltenar
                case "BONUSCODEALTENAR":
                    $bonusCodeAltenar = $bonoDetalle->valor;

                    break;

                //Indica si el bono es para el programa de lealtad. Puede ser otorgado a usuarios que
                // son leales a un producto durante un período de tiempo determinado.
                case "BONOLEALTAD":
                    if (!$isForLealtad) {
                        $cumpleCondiciones = false;
                    }
                    break;

                case 'MAXBETAMOUNTROLLOVER':
                    if (!$isForRollover){
                        break;
                    }
                    $maximoValorApuestaAceptada = $bonoDetalle->valor;
                    break;

                case "AMOUNTBONUSMAXROLLOVER":
                    if (!$isForRollover){
                        break;
                    }
                    $valorMaximoASumar = $bonoDetalle->valor;

                    break;

                // Establece las condiciones relacionadas con eventos deportivos
                case "ITAINMENT1":
                    if (!$isForRollover){
                        break;
                    }
                    $cumplecondicionproductotmp = false;
                    $condicionesproductotmp = 0;

                    foreach ($detalleSelecciones as $item) {


                        if ($tipocomparacion == "OR") {
                            if (trim($bonoDetalle->valor) == trim($item->Deporte)) {
                                $cumplecondicionproductotmp = true;


                            }
                        } elseif ($tipocomparacion == "AND") {
                            if ($bonoDetalle->valor != trim($item->Deporte)) {
                                $cumplecondicionproductotmp = false;


                            }

                            if ($condicionesproductotmp == 0) {
                                if ($bonoDetalle->valor == trim($item->Deporte)) {
                                    $cumplecondicionproductotmp = true;
                                }
                            } else {
                                if ($bonoDetalle->valor == trim($item->Deporte) && $cumplecondicionproductotmp) {
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
                    } elseif ($tipocomparacion == "AND") {
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

                //Define las condiciones específicas relacionadas con las ligas deportivas
                case "ITAINMENT3":
                    if (!$isForRollover){
                        break;
                    }
                    $cumplecondicionproductotmp = false;
                    $condicionesproductotmp = 0;

                    foreach ($detalleSelecciones as $item) {

                        if ($tipocomparacion == "OR") {
                            if ($bonoDetalle->valor == trim($item->Liga)) {
                                $cumplecondicionproductotmp = true;

                            }
                        } elseif ($tipocomparacion == "AND") {
                            if ($bonoDetalle->valor != trim($item->Liga)) {
                                $cumplecondicionproductotmp = false;

                            }

                            if ($condicionesproductotmp == 0) {
                                if ($bonoDetalle->valor == trim($item->Liga)) {
                                    $cumplecondicionproductotmp = true;
                                }
                            } else {
                                if ($bonoDetalle->valor == trim($item->Liga) && $cumplecondicionproducto) {
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
                    } elseif ($tipocomparacion == "AND") {
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

                //Establece las condiciones relacionadas con los partidos deportivos
                case "ITAINMENT4":
                    if (!$isForRollover){
                        continue;
                    }
                    $cumplecondicionproductotmp = false;
                    $condicionesproductotmp = 0;


                    foreach ($detalleSelecciones as $item) {
                        if ($tipocomparacion == "OR") {
                            if ($bonoDetalle->valor == trim($item->Evento)) {
                                $cumplecondicionproductotmp = true;

                            }
                        } elseif ($tipocomparacion == "AND") {
                            if ($bonoDetalle->valor != trim($item->Evento)) {
                                $cumplecondicionproductotmp = false;

                            }

                            if ($condicionesproductotmp == 0) {

                                if ($bonoDetalle->valor == trim($item->Evento)) {
                                    $cumplecondicionproductotmp = true;
                                }
                            } else {

                                if ($bonoDetalle->valor == trim($item->Evento) && $cumplecondicionproductotmp) {
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
                    } elseif ($tipocomparacion == "AND") {
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

                // Define las condiciones específicas relacionadas con los mercados de apuestas deportivas
                case "ITAINMENT5":
                    if (!$isForRollover){
                        break;
                    }
                    $cumplecondicionproductotmp = false;
                    $condicionesproductotmp = 0;


                    foreach ($detalleSelecciones as $item) {
                        if ($tipocomparacion == "OR") {
                            if ($bonoDetalle->valor == trim($item->DeporteMercado)) {
                                $cumplecondicionproductotmp = true;


                            }
                        } elseif ($tipocomparacion == "AND") {
                            if ($bonoDetalle->valor != trim($item->DeporteMercado)) {
                                $cumplecondicionproductotmp = false;

                            }

                            if ($condicionesproductotmp == 0) {
                                if ($bonoDetalle->valor == trim($item->DeporteMercado)) {
                                    $cumplecondicionproductotmp = true;
                                }
                            } else {
                                if ($bonoDetalle->valor == trim($item->DeporteMercado) && $cumplecondicionproductotmp) {
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
                    } elseif ($tipocomparacion == "AND") {
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

                // activa condicionales de se pueden simples y se pueden combinadas
                case "ITAINMENT82":
                    if (!$isForRollover){
                        break;
                    }
                    if ($bonoDetalle->valor == 1) {
                        $sePuedeSimples = 1;

                    }
                    if ($bonoDetalle->valor == 2) {
                        $sePuedeCombinadas = 1;

                    }
                    break;

                //Permite ingresar un código promocional asociado con el bono
                case "CODEPROMO":

                    if ($bonoDetalle->valor != '0') {

                        if ($CodePromo != "") {
                            if ($CodePromo != $bonoDetalle->valor) {
                                $condicionTrigger = false;

                            }
                        } else {
                            //Verificamos que el bono sea de tipo Deposito
                            if ($tipoBono == 2) {
                                //Seleccionamos usubono_id de la tabla usuario_bono  donde
                                // el bono_id debe ser el $bono->bono_id de la tabla bono_interno
                                // usuario_id debe ser la variable $usuarioId
                                // y el estado debe ser 'P'
                                $sqlDetallebonoPendiente = "SELECT a.usubono_id FROM usuario_bono a WHERE a.bono_id='" . $bonoDetalles->bono_id . "' AND a.usuario_id='" . $usuarioId . "' AND a.estado='P'";
                                $bonoDetallesPendiente = $this->execQuery($transaccion, $sqlDetallebonoPendiente);
                                //Ejecutamos SQL
                                if (count($bonoDetallesPendiente) > 0) {
                                    //Guardamos condicion de la consulta
                                    $condicionTriggerPosterior = $bonoDetallesPendiente[0]->usubono_id;

                                } else {
                                    //Negamos condicion
                                    $condicionTrigger = false;

                                }

                            } else {
                                //Negamos condicion
                                $condicionTrigger = false;

                            }

                        }
                    }

                    break;

                default:

                    //if ($BonoIn == 5) {
                    //CONDGAME Define las condiciones del juego para el bono
                    //Buscamos CONDGAME en tabla bono_detalle si existe guardamos la identidad del juego
                    // en el array CONDGAME
                    if (stristr($bonoDetalle->tipo, 'CONDGAME')) {

                        $idGame = explode("CONDGAME", $bonoDetalle->tipo)[1];
                        array_push($CONDGAME, $idGame);

                        foreach ($detalleJuegosCasino as $item) {
                            if ($idGame == $item->Id) {
                                $cumplecondicionproducto = true;

                                $valorASumar = $valorASumar + (($detalleValorApuesta * $bonoDetalle->valor) / 100);

                            }

                        }

                        $condicionesproducto++;
                    }

                    if (stristr($bonoDetalle->tipo, 'CONDPROVIDER')) {

                        $idGame = explode("CONDPROVIDER", $bonoDetalle->tipo)[1];

                        foreach ($detalleJuegosCasino as $item) {
                            if ($idGame == $item->proveedorId) {
                                $cumplecondicionproducto = true;

                                $valorASumar = $valorASumar + (($detalleValorApuesta * $bonoDetalle->valor) / 100);

                            }

                        }

                        $condicionesproducto++;
                    }

                    if (stristr($bonoDetalle->tipo, 'CONDSUBPROVIDER')) {

                        $idGame = explode("CONDSUBPROVIDER", $bonoDetalle->tipo)[1];
                        array_push($CONDSUBPROVIDER, $idGame);

                        foreach ($detalleJuegosCasino as $item) {
                            if ($idGame == $item->subproveedorId) {
                                $cumplecondicionproducto = true;

                                $valorASumar = $valorASumar + (($detalleValorApuesta * $bonoDetalle->valor) / 100);

                            }

                        }

                        $condicionesproducto++;
                    }
                    if (stristr($bonoDetalle->tipo, 'CONDCATEGORY')) {

                        $idGame = explode("CONDCATEGORY", $bonoDetalle->tipo)[1];


                        foreach ($detalleJuegosCasino as $item) {


                            if (strpos($item->categoryId, ',' . $idGame) !== false) {
                                $cumplecondicionproducto = true;

                                $valorASumar = $valorASumar + (($detalleValorApuesta * $bonoDetalle->valor) / 100);

                            }

                        }

                        $condicionesproducto++;
                    }


                    //   if (stristr($bonodetalle->{'bono_detalle.tipo'}, 'CONDGAME')) {
                    //
                    // }
                    //
                    //   if (stristr($bonodetalle->{'bono_detalle.tipo'}, 'ITAINMENT')) {
                    //
                    //
                    //
                    //   }


                    break;
            }
        }

        //Verificamos condicional
        if (!$condicionTrigger) {

            //Negamos el cumplecondiciones
            $cumpleCondiciones = false;
            //$BonoInterno = new BonoInterno();
            //Creamos SQL para usuarios de bonos que no cumplen condiciones
            //$sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
            //                                VALUES ('{$usuarioId}','BONO','{$bono->bono_id}','{$tipoBono}','1','TRIGGER')";
            //*** SQL no se ejecuta
            // $BonoInterno->execQuery($transaccion, $sqlLog);
        }

        if ($CodePromo == "") {
            //verificamos que contador pais comenzara
            if ($condicionPaisPVcount > 0) {
                //si hay incoherencias
                if (!$condicionPaisPV && $detalleDepositoEfectivo && $extra) {
                    //negamos cumplecondiciones
                    $cumpleCondiciones = false;
                }

            }
            //verificamos que contador Departamento  comenzara
            if ($condicionDepartamentoPVcount > 0) {
                //si condicion departamente esta negada
                if (!$condicionDepartamentoPV) {
                    //negamos cumplecondiciones
                    $cumpleCondiciones = false;
                }

            }

            //verificamos que contador ciudad comenzara
            if ($condicionCiudadPVcount > 0) {
                //si condicion ciudad esta negada
                if (!$condicionCiudadPV) {
                    //negamos cumplecondiciones
                    $cumpleCondiciones = false;
                }

            }
        }



        // verificamos que contador pais user comenzara
        if ($condicionPaisUSERcount > 0) {

            //si condicional de pais user esta negado
            if (!$condicionPaisUSER) {

                //negamos cumple condiciones
                $cumpleCondiciones = false;
            }

        }


        // verificamos que contador Departamento user comenzara
        if ($condicionDepartamentoUSERcount > 0) {
            //si condicional de Departamento user esta negado
            if (!$condicionDepartamentoUSER) {
                //negamos cumple condiciones
                $cumpleCondiciones = false;
            }

        }

        // verificamos que condicional ciudad user no este vacio
        if ($condicionCiudadUSERcount > 0) {
            //si condicional de ciudad user esta negado
            if (!$condicionCiudadUSER) {
                //negamos cumple condiciones
                $cumpleCondiciones = false;
            }

        }

        //Verificamos si existe incoherencia entre condicionBonoReferente, su contador y el contenido de detalleREferido esta vacio;
        // si esto sucede negamos cumpleCondiciones
        if ($condicionBonoReferenteCount > 0) {
            if (!$condicionBonoReferente || empty($detalleReferidoId)) {
                $cumpleCondiciones = false;
            }
        }

        //verificamos si CodePromo esta vacio
        if ($CodePromo == "") {
            //verificamos que contador PuntoVenta comenzara
            if ($condicionPuntoVentacount > 0) {
                //verificamos que condicion este negada
                if (!$condicionPuntoVenta) {
                    //negamos cumplecondiciones
                    $cumpleCondiciones = false;
                }
            }
            //Verificamos que el contador no este vacio y que ya halla comenzado
            if ($condicionmetodoPagocount != '' && $condicionmetodoPagocount > 0 && $extra) {
                //verificamos que condicional este negado
                if (!$condicionmetodoPago) {
                    //negamos cumple condiciones
                    $cumpleCondiciones = false;
                }

            }

        }

        if (!$cumplecondicionproductoTipo) {
            $cumplecondicionproducto = false;

        }

        if ($tipoProductoGlobal != null) {
            if ($tipoProducto !== null) {
                $tipoProdGruposAceptados = [
                    'SPORT' => [0, 2],
                    'LIVECASINO' => [0, 1, 3, 4],
                    'CASINO' => [0, 1, 3, 4],
                    'VIRTUAL' => [0, 1, 3, 4]
                ];

                if (!in_array($tipoProducto, $tipoProdGruposAceptados[$tipoProductoGlobal])) {
                    $cumplecondicion = false;
                }
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

        if (!empty($maximoValorApuestaAceptada) && is_numeric($maximoValorApuestaAceptada)) {
            if ($detalleValorApuesta > $maximoValorApuestaAceptada) $cumplecondicion = false;
        }

        if ($bonusPlanIdAltenar != '' && $bonusCodeAltenar != '') {
            $cumplecondicion = false;
        }
//Array con valores


        //Usado en VerifBonoRollower()
        $respuesta["tipoProducto"] = $tipoProducto;
        //Usado en todos los agregarbono para calcular el valor del bono base en "PORCENTAJE" o "VALOR"
        $respuesta["valorbono"] = $valorbono;
        //Usado en AGREGAR BONO FREE para SQL
        $respuesta["ValorBono"] = $ValorBono;
        // Contiene tipo de bono según tabla bono_detalles tipo
        $respuesta["tipobono"] = $tipobono;
        //Usado en EstadoQ y agregarBono como un id de usuario
        $respuesta["condicionTriggerPosterior"] = $condicionTriggerPosterior;
        //Muy importante usado en casi todos menos en Verificaciones
        $respuesta["cumpleCondiciones"] = $cumpleCondiciones;
        //Usado en verificaciones
        $respuesta["cumplecondicion"] = $cumplecondicion;
        // Condicion de no desborde
        $respuesta["maximopago"] = $maximopago;
        //Dias de expiración
        $respuesta["expDia"] = $expDia;
        // Fecha de expiración
        $respuesta["expFecha"] = $expFecha;
        //Informacion sobre rollower
        $respuesta["bonoTieneRollower"] = $bonoTieneRollower;
        //Informacion sobre rollower
        $respuesta["rollowerBono"] = $rollowerBono;
        //Informacion sobre rollower
        $respuesta["rollowerDeposito"] = $rollowerDeposito;
        //Informacion sobre rollower
        $respuesta["rollowerValor"] = $rollowerValor;
        // Informacion sobre repite Bono
        $respuesta["puederepetirBono"] = $puederepetirBono;
        // Condicional sobre WINBONOID de la tabla bono_detalles usado para bono no deposito
        $respuesta["ganaBonoId"] = $ganaBonoId;
        //condicionBonoReferente usada en BonoFree
        $respuesta["condicionBonoReferente"] = $condicionBonoReferente;
        //Importante en Verificaciones
        $respuesta["cumplecondicionproducto"] = $cumplecondicionproducto;
        //Variable usada en bono no deposito para actualizar la tabla registro segun el contenido de esta
        $respuesta["tiposaldo"] = $tiposaldo;
        //* Solo se usa en WINBONOID, y solo se inicializa
        // Sugiero verificar tipobono a la salida de la función si es WINBONOID, hacer valor_bono = 0
        $respuesta["valor_bono"] = $valor_bono;
        // Si el bono viene por Altenar
        $respuesta["bonusPlanIdAltenar"] = $bonusPlanIdAltenar;
        // Codigo de Altenar
        $respuesta["bonusCodeAltenar"] = $bonusCodeAltenar;
        // Condicional para subproveedor IESGAMES en Bono FreeSpin
        $respuesta["cantidadCartones"] = $cantidadCartones;
        //Importante en verificaciones
        $respuesta["condicionesproducto"] = $condicionesproducto;
        //Importante en verificaciones
        $respuesta["valorASumar"] = $valorASumar;
        //Usada en verificarBonoRollower
        $respuesta["valorMaximoASumar"] = $valorMaximoASumar;
        //Usada en verificarBonoRollower
        $respuesta["AMOUNTBONUSMAXSPIN"] = $AMOUNTBONUSMAXSPIN;
        //Usada en agregarbonofree
        $respuesta["prefix"] = $prefix;
        $respuesta["CONDGAME"] = $CONDGAME;
        $respuesta["CONDSUBPROVIDER"] = $CONDSUBPROVIDER;
        /*if ($AMOUNTBONUSMAXSPIN == '1') {

            if (floatval($valorASumar) > floatval($valorDelBono)) {
                $valorASumar = $valorDelBono;
            }
        }*/

        return json_decode(json_encode($respuesta));

    }


    /**
     * Agregar un bono
     *
     *
     * @param String $tipoBono
     * @param String $usuarioId
     * @param String $mandante
     * @param String $detalles
     * @param String $transaccion
     *
     * @return Array resultado de la consulta
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function agregarBonoEstadoQ($tipoBono, $usuarioId, $mandante, $detalles, $transaccion, $isForCRM = false, $isForSelect = "")
    {
        $Usuario = new Usuario($usuarioId);
        $Subproveedor = new Subproveedor("", "ITN");
        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Subproveedor->subproveedorId, $mandante, $Usuario->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());
        $urlAltenar = $Credentials->URL;
        $walletCode = $Credentials->WALLET_CODE;

        $respuesta = array();

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

        $cumpleCondiciones = false;
        $bonoElegido = 0;
        $bonoTieneRollower = false;
        $rollowerBono = 0;
        $rollowerDeposito = 0;


        //Obtenemos todos los bonos disponibles
        $sqlBonos = "select a.bono_id bono_id,a.tipo,a.fecha_inicio,a.fecha_fin,now() test, a.permite_bono permite_bonos,a.pertenece_crm from bono_interno a where a.mandante=" . $mandante . "  and now()  between (a.fecha_inicio) and (a.fecha_fin) and a.estado='A' ORDER BY a.orden DESC,a.fecha_crea ASC ";

        if ($CodePromo != "") {
            $sqlBonos = "select a.bono_id,a.tipo,a.fecha_inicio,a.fecha_fin, a.permite_bono permite_bonos, a.pertenece_crm from bono_interno a INNER JOIN bono_detalle b ON (a.bono_id=b.bono_id AND b.tipo='CODEPROMO' AND b.valor='" . $CodePromo . "') where a.mandante=" . $mandante . "  and now()  between (a.fecha_inicio) and (a.fecha_fin) and a.estado='A' " . " ORDER BY a.orden DESC,a.fecha_crea ASC ";

        }

        if ($isForSelect != "") {

            $sqlBonos = "select a.bono_id bono_id,a.tipo,a.fecha_inicio,a.fecha_fin,now() test, a.permite_bono permite_bonos from bono_interno a where a.mandante=" . $mandante . " and a.tipo_accion='" . $isForSelect . "'  and now()  between (a.fecha_inicio) and (a.fecha_fin) and a.estado='A' ORDER BY a.orden DESC,a.fecha_crea ASC ";

        }

        if ($_ENV['debug']) {

            print_r(PHP_EOL);
            print_r($sqlBonos);
        }
        $respuesta["sql"] = $sqlBonos;

        $bonosDisponibles = $this->execQuery($transaccion, $sqlBonos);


        $continue = true;


        $BonosPosiblesXGanar = array();

        //Este es el nuevo FOREACH que sirve para contar los posibles entre los cuales el usuario podra escoger

        foreach ($bonosDisponibles as $bono) {

            $bono->bono_id = $bono->{'a.bono_id'};
            $bono->tipo = $bono->{'a.tipo'};
            $bono->fecha_inicio = $bono->{'a.fecha_inicio'};
            $bono->fecha_fin = $bono->{'a.fecha_fin'};
            $PermiteBonos = $bono->{'a.permite_bonos'};
            $pertenece_crm = $bono->{'a.pertenece_crm'};


            if ((!$cumpleCondiciones && ($tipoBono == $bono->tipo || $CodePromo != "" || $isForSelect != "")) || ($cumpleCondiciones && $continue && ($tipoBono == $bono->tipo || $CodePromo != ""))) {


                //Obtenemos todos los detalles del bono
                $sqlDetalleBono = "select * from bono_detalle a where a.bono_id='" . $bono->bono_id . "' AND (moneda='' OR moneda='" . $detalleMonedaUSER . "') ";
                $bonoDetalles = $this->execQuery($transaccion, $sqlDetalleBono);

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
                $puederepetirBono = false;
                $ganaBonoId = 0;


                $maximopago = 0;
                $maximodeposito = 0;
                $minimodeposito = 0;
                $valorbono = 0;
                $tipoproducto = 0;
                $tipobono = "";
                $bonoTieneRollower = false;
                $tiposaldo = -1;


                $bonusPlanIdAltenar = '';
                $bonusCodeAltenar = '';

                if ($tipoBono != $bono->tipo && $CodePromo == "" && $isForSelect == "") {
                    $cumpleCondiciones = false;

                }

                if (($CodePromo != "" && $tipoBono == '') || ($isForSelect != "" && $tipoBono == '')) {
                    $tipoBono = $bono->tipo;

                }

                $expDia = '';
                $expFecha = '';

                if ($pertenece_crm == 'S' && !$isForCRM) {

                    $sqlUsuarioBonos = "SELECT a.usubono_id FROM usuario_bono a WHERE a.bono_id='" . $bono->bono_id . "' AND a.usuario_id='" . $usuarioId . "' AND a.estado='P'";

                    $respuesta["sql"] = $sqlBonos;

                    $bonosUsuarioDisponibles = $this->execQuery($transaccion, $sqlUsuarioBonos);

                    if (count($bonosUsuarioDisponibles) == 0) {
                        break;
                    }

                }

                foreach ($bonoDetalles as $bonoDetalle) {


                    $bonoDetalle->tipo = $bonoDetalle->{'a.tipo'};
                    $bonoDetalle->valor = $bonoDetalle->{'a.valor'};
                    $bonoDetalle->moneda = $bonoDetalle->{'a.moneda'};

                    switch ($bonoDetalle->tipo) {

                        case "TIPOPRODUCTO":
                            $tipoproducto = $bonoDetalle->valor;


                            break;

                        case "CANTDEPOSITOS":
                            if ($detalleDepositos != ($bonoDetalle->valor - 1) && $bonoDetalle->valor != 0 && $bono->tipo == 2) {

                                $cumpleCondiciones = false;

                            }

                            break;

                        case "CONDEFECTIVO":
                            if ($detalleDepositoEfectivo) {
                                if (($bonoDetalle->valor == "true")) {
                                    $condicionmetodoPago = true;
                                }
                            } else {
                                if (($bonoDetalle->valor != "true")) {
                                    $condicionmetodoPago = false;
                                }
                            }
                            $condicionmetodoPagocount++;


                            break;

                        case "PORCENTAJE":
                            $tipobono = "PORCENTAJE";
                            $valorbono = $bonoDetalle->valor;

                            break;

                        case "NUMERODEPOSITO":

                            break;

                        case "MAXJUGADORES":


                            if ($bono->tipo == 2 && $bonoDetalle->valor != '0') {

                                $sqlDetalleBonoPendiente = "SELECT a.usubono_id FROM usuario_bono a WHERE a.bono_id='" . $bono->bono_id . "' AND a.usuario_id='" . $usuarioId . "' AND a.estado='P'";

                                $bonoDetallesPendiente = $this->execQuery($transaccion, $sqlDetalleBonoPendiente);

                                if (count($bonoDetallesPendiente) > 0) {
                                    $condicionTriggerPosterior = $bonoDetallesPendiente[0]->{'a.usubono_id'};
                                    if ($condicionTriggerPosterior == "" || $condicionTriggerPosterior == 0) {
                                        $cumpleCondiciones = false;

                                    }

                                } else {
                                    $cumpleCondiciones = false;

                                    /* $sqlDetalleBonoPendiente = "SELECT a.usubono_id FROM usuario_bono a WHERE a.bono_id='" . $bono->bono_id . "' AND a.usuario_id='0' AND a.estado='L'";
                                     $bonoDetallesPendiente = $this->execQuery($transaccion, $sqlDetalleBonoPendiente);

                                     if (count($bonoDetallesPendiente) > 0) {
                                         $condicionTriggerPosterior=$bonoDetallesPendiente[0]->{'a.usubono_id'};
                                         if($condicionTriggerPosterior == "" || $condicionTriggerPosterior==0){
                                             $cumpleCondiciones = false;

                                         }

                                     }else{
                                         $cumpleCondiciones = false;

                                     }
 */
                                }

                            }

                            break;


                        case "MAXPAGO":

                            if ($bonoDetalle->moneda == $detalleMonedaUSER) {

                                $maximopago = $bonoDetalle->valor;

                            }
                            break;

                        case "MAXDEPOSITO":

                            $maximodeposito = $bonoDetalle->valor;
                            if ($bonoDetalle->moneda == $detalleMonedaUSER) {

                                if ($detalleValorDeposito > $maximodeposito && $maximodeposito != 0) {
                                    $cumpleCondiciones = false;
                                }
                            }

                            break;

                        case "MINDEPOSITO":

                            $minimodeposito = $bonoDetalle->valor;

                            if ($bonoDetalle->moneda == $detalleMonedaUSER) {

                                if ($detalleValorDeposito < $minimodeposito) {
                                    $cumpleCondiciones = false;
                                }
                            }

                            break;

                        case "VALORBONO":
                            if ($bonoDetalle->moneda == $detalleMonedaUSER) {

                                $valorbono = $bonoDetalle->valor;
                                $tipobono = "VALOR";
                            }
                            break;

                        case "CONDPAYMENT":

                            if ($detalleDepositoMetodoPago == $bonoDetalle->valor && $bonoDetalle->valor != '') {
                                $condicionmetodoPago = true;

                            }
                            if ($bonoDetalle->valor != '') {
                                $condicionmetodoPagocount++;
                            }

                            break;

                        case "CONDPAISPV":

                            $condicionPaisPVcount = $condicionPaisPVcount + 1;
                            if ($bonoDetalle->valor == $detallePaisPV) {
                                $condicionPaisPV = true;
                            }

                            break;

                        case "CONDDEPARTAMENTOPV":

                            $condicionDepartamentoPVcount = $condicionDepartamentoPVcount + 1;
                            if ($bonoDetalle->valor == $detalleDepartamentoPV) {
                                $condicionDepartamentoPV = true;
                            }

                            break;

                        case "CONDCIUDADPV":

                            $condicionCiudadPVcount = $condicionCiudadPVcount + 1;

                            if ($bonoDetalle->valor == $detalleCiudadPV) {
                                $condicionCiudadPV = true;
                            }

                            break;

                        case "CONDPAISUSER":

                            $condicionPaisUSERcount = $condicionPaisUSERcount + 1;
                            if ($bonoDetalle->valor == $detallePaisUSER) {
                                $condicionPaisUSER = true;
                            }

                            break;

                        case "CONDDEPARTAMENTOUSER":

                            $condicionDepartamentoUSERcount = $condicionDepartamentoUSERcount + 1;
                            if ($bonoDetalle->valor == $detalleDepartamentoUSER) {
                                $condicionDepartamentoUSER = true;
                            }

                            break;

                        case "CONDCIUDADUSER":

                            $condicionCiudadUSERcount = $condicionCiudadUSERcount + 1;

                            if ($bonoDetalle->valor == $detalleCiudadUSER) {
                                $condicionCiudadUSER = true;
                            }

                            break;

                        case "CONDPUNTOVENTA":

                            $condicionPuntoVentacount = $condicionPuntoVentacount + 1;

                            if ($bonoDetalle->valor == $detallePuntoVenta) {
                                $condicionPuntoVenta = true;
                            }

                            break;

                        case "EXPDIA":
                            $expDia = $bonoDetalle->valor;
                            $expFecha = '';

                            break;

                        case "EXPFECHA":
                            $expDia = '';
                            $expFecha = $bonoDetalle->valor;

                            break;

                        case "WFACTORBONO":
                            if ($bonoDetalle->valor != '0') {
                                $bonoTieneRollower = true;

                                $rollowerBono = $bonoDetalle->valor;
                            }
                            break;

                        case "WFACTORDEPOSITO":
                            if ($bonoDetalle->valor != '0') {

                                $bonoTieneRollower = true;
                                $rollowerDeposito = $bonoDetalle->valor;
                            }
                            break;

                        case "VALORROLLOWER":
                            if ($bonoDetalle->moneda == $detalleMonedaUSER) {

                                $bonoTieneRollower = true;
                                $rollowerValor = $bonoDetalle->valor;
                            }
                            break;
                        case "REPETIRBONO":

                            if ($bonoDetalle->valor) {
                                $puederepetirBono = true;
                            }

                            break;

                        case "WINBONOID":
                            $ganaBonoId = $bonoDetalle->valor;
                            $tipobono = "WINBONOID";
                            $valor_bono = 0;

                            break;

                        case "TIPOSALDO":
                            $tiposaldo = $bonoDetalle->valor;

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

                        case "AMOUNTBONUSMAXSPIN":
                            $AMOUNTBONUSMAXSPIN = $bonoDetalle->valor;

                            break;

                        case "BONUSPLANIDALTENAR":
                            $bonusPlanIdAltenar = $bonoDetalle->valor;

                            break;

                        case "BONUSCODEALTENAR":
                            $bonusCodeAltenar = $bonoDetalle->valor;

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

                            if ($bonoDetalle->valor != '0') {


                                if ($CodePromo != "") {
                                    if ($CodePromo != $bonoDetalle->valor) {
                                        $condicionTrigger = false;

                                    }
                                } else {

                                    if ($tipoBono == 2) {
                                        $sqlDetalleBonoPendiente = "SELECT a.usubono_id FROM usuario_bono a WHERE a.bono_id='" . $bono->bono_id . "' AND a.usuario_id='" . $usuarioId . "' AND a.estado='P'";
                                        $bonoDetallesPendiente = $this->execQuery($transaccion, $sqlDetalleBonoPendiente);

                                        if (count($bonoDetallesPendiente) > 0) {
                                            $condicionTriggerPosterior = $bonoDetallesPendiente[0]->usubono_id;

                                        } else {
                                            $condicionTrigger = false;

                                        }

                                    } else {
                                        $condicionTrigger = false;

                                    }

                                }
                            }

                            break;

                        default:

                            break;
                    }

                    if ($_ENV['debug']) {

                        print_r($bonoDetalle);
                        print_r(PHP_EOL);
                        print_r($cumpleCondiciones);
                    }
                }


                if (!$condicionTrigger) {

                    $cumpleCondiciones = false;

                }


                if ($CodePromo == "") {


                    if ($condicionPaisPVcount > 0) {
                        if (!$condicionPaisPV && $detalleDepositoEfectivo) {
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


                    if ($condicionTriggerPosterior == 0) {

                        if ($puederepetirBono) {
                            $bonoElegido = $bono->bono_id;

                        } else {

                            $sqlRepiteBono = "select * from usuario_bono a where a.bono_id='" . $bono->bono_id . "' AND a.usuario_id = '" . $usuarioId . "'";
                            $repiteBono = $this->execQuery($transaccion, $sqlRepiteBono);

                            if ((!$puederepetirBono && count($repiteBono) == 0)) {
                                $bonoElegido = $bono->bono_id;
                            } else {
                                $cumpleCondiciones = false;
                            }

                        }
                    } else {
                        $bonoElegido = $bono->bono_id;
                    }


                    if ($bonoElegido !== 0) {
                        array_push($BonosPosiblesXGanar, $bonoElegido);

                    }

                }


                if ($cumpleCondiciones) {
                    if ($PermiteBonos == 1) {
                        $continue = true;
                    } else {
                        $continue = false;
                    }
                }


            }

        }

        $cantidadBonosXGanar = count($BonosPosiblesXGanar);

        if ($_ENV['debug']) {

            print_r(PHP_EOL);
            print_r(PHP_EOL);
            print_r(PHP_EOL);
            print_r(PHP_EOL);
            print_r(PHP_EOL);
            print_r('cantidadBonosXGanar');
            print_r($cantidadBonosXGanar);
            print_r(PHP_EOL);
            print_r(PHP_EOL);
            print_r(PHP_EOL);
            print_r(PHP_EOL);
            print_r(PHP_EOL);
        }

        //print_r('Estos son los bonos por ganar');


        //print_r($cantidadBonosXGanar);

        //print_r('*****************');


        if ($cantidadBonosXGanar > 1) {


            //Este Foreach asigna los bonos y los pone en estado 'Q' pero no suma saldo.
            //Es casi el mismo FOREACH original pero con modificaciones.

            foreach ($bonosDisponibles as $bono) {

                $bono->bono_id = $bono->{'a.bono_id'};
                $bono->tipo = $bono->{'a.tipo'};
                $bono->fecha_inicio = $bono->{'a.fecha_inicio'};
                $bono->fecha_fin = $bono->{'a.fecha_fin'};
                $PermiteBonos = $bono->{'a.permite_bonos'};
                $pertenece_crm = $bono->{'a.pertenece_crm'};


                if ((!$cumpleCondiciones && ($tipoBono == $bono->tipo || $CodePromo != "")) || ($cumpleCondiciones && $continue && ($tipoBono == $bono->tipo || $CodePromo != ""))) {


                    //Obtenemos todos los detalles del bono
                    $sqlDetalleBono = "select * from bono_detalle a where a.bono_id='" . $bono->bono_id . "' AND (moneda='' OR moneda='" . $detalleMonedaUSER . "') ";
                    $bonoDetalles = $this->execQuery($transaccion, $sqlDetalleBono);

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

                    $puederepetirBono = false;
                    $ganaBonoId = 0;


                    $maximopago = 0;
                    $maximodeposito = 0;
                    $minimodeposito = 0;
                    $valorbono = 0;
                    $tipoproducto = 0;
                    $tipobono = "";
                    $bonoTieneRollower = false;
                    $tiposaldo = -1;


                    $bonusPlanIdAltenar = '';
                    $bonusCodeAltenar = '';

                    if ($tipoBono != $bono->tipo && $CodePromo == "") {
                        $cumpleCondiciones = false;

                    }

                    if ($CodePromo != "" && $tipoBono == '') {
                        $tipoBono = $bono->tipo;

                    }

                    $expDia = '';
                    $expFecha = '';

                    if ($pertenece_crm == 'S' && !$isForCRM) {

                        $sqlUsuarioBonos = "SELECT a.usubono_id FROM usuario_bono a WHERE a.bono_id='" . $bono->bono_id . "' AND a.usuario_id='" . $usuarioId . "' AND a.estado='P'";

                        $respuesta["sql"] = $sqlBonos;

                        $bonosUsuarioDisponibles = $this->execQuery($transaccion, $sqlUsuarioBonos);

                        if (count($bonosUsuarioDisponibles) == 0) {
                            break;
                        }

                    }

                    foreach ($bonoDetalles as $bonoDetalle) {


                        $bonoDetalle->tipo = $bonoDetalle->{'a.tipo'};
                        $bonoDetalle->valor = $bonoDetalle->{'a.valor'};
                        $bonoDetalle->moneda = $bonoDetalle->{'a.moneda'};

                        switch ($bonoDetalle->tipo) {


                            case "TIPOPRODUCTO":
                                $tipoproducto = $bonoDetalle->valor;


                                break;

                            case "CANTDEPOSITOS":
                                if ($detalleDepositos != ($bonoDetalle->valor - 1) && $bonoDetalle->valor != 0 && $bono->tipo == 2) {

                                    $cumpleCondiciones = false;

                                }

                                break;

                            case "CONDEFECTIVO":
                                if ($detalleDepositoEfectivo) {
                                    if (($bonoDetalle->valor == "true")) {
                                        $condicionmetodoPago = true;
                                    }
                                } else {
                                    if (($bonoDetalle->valor != "true")) {
                                        $condicionmetodoPago = false;
                                    }
                                }
                                $condicionmetodoPagocount++;


                                break;


                            case "PORCENTAJE":
                                $tipobono = "PORCENTAJE";
                                $valorbono = $bonoDetalle->valor;

                                break;


                            case "NUMERODEPOSITO":

                                break;

                            case "MAXJUGADORES":


                                if ($bono->tipo == 2 && $bonoDetalle->valor != '0') {

                                    $sqlDetalleBonoPendiente = "SELECT a.usubono_id FROM usuario_bono a WHERE a.bono_id='" . $bono->bono_id . "' AND a.usuario_id='" . $usuarioId . "' AND a.estado='P'";

                                    $bonoDetallesPendiente = $this->execQuery($transaccion, $sqlDetalleBonoPendiente);

                                    if (count($bonoDetallesPendiente) > 0) {
                                        $condicionTriggerPosterior = $bonoDetallesPendiente[0]->{'a.usubono_id'};
                                        if ($condicionTriggerPosterior == "" || $condicionTriggerPosterior == 0) {
                                            $cumpleCondiciones = false;

                                        }

                                    } else {
                                        $cumpleCondiciones = false;

                                        /* $sqlDetalleBonoPendiente = "SELECT a.usubono_id FROM usuario_bono a WHERE a.bono_id='" . $bono->bono_id . "' AND a.usuario_id='0' AND a.estado='L'";
                                         $bonoDetallesPendiente = $this->execQuery($transaccion, $sqlDetalleBonoPendiente);

                                         if (count($bonoDetallesPendiente) > 0) {
                                             $condicionTriggerPosterior=$bonoDetallesPendiente[0]->{'a.usubono_id'};
                                             if($condicionTriggerPosterior == "" || $condicionTriggerPosterior==0){
                                                 $cumpleCondiciones = false;

                                             }

                                         }else{
                                             $cumpleCondiciones = false;

                                         }
                                        */
                                    }

                                }

                                break;


                            case "MAXPAGO":

                                if ($bonoDetalle->moneda == $detalleMonedaUSER) {

                                    $maximopago = $bonoDetalle->valor;

                                }
                                break;

                            case "MAXDEPOSITO":

                                $maximodeposito = $bonoDetalle->valor;
                                if ($bonoDetalle->moneda == $detalleMonedaUSER) {

                                    if ($detalleValorDeposito > $maximodeposito && $maximodeposito != 0) {
                                        $cumpleCondiciones = false;
                                    }
                                }

                                break;

                            case "MINDEPOSITO":

                                $minimodeposito = $bonoDetalle->valor;

                                if ($bonoDetalle->moneda == $detalleMonedaUSER) {

                                    if ($detalleValorDeposito < $minimodeposito) {
                                        $cumpleCondiciones = false;
                                    }
                                }

                                break;

                            case "VALORBONO":
                                if ($bonoDetalle->moneda == $detalleMonedaUSER) {

                                    $valorbono = $bonoDetalle->valor;
                                    $tipobono = "VALOR";
                                }
                                break;

                            case "CONDPAYMENT":

                                if ($detalleDepositoMetodoPago == $bonoDetalle->valor && $bonoDetalle->valor != '') {
                                    $condicionmetodoPago = true;

                                }
                                if ($bonoDetalle->valor != '') {
                                    $condicionmetodoPagocount++;
                                }

                                break;

                            case "CONDPAISPV":

                                $condicionPaisPVcount = $condicionPaisPVcount + 1;
                                if ($bonoDetalle->valor == $detallePaisPV) {
                                    $condicionPaisPV = true;
                                }

                                break;

                            case "CONDDEPARTAMENTOPV":

                                $condicionDepartamentoPVcount = $condicionDepartamentoPVcount + 1;
                                if ($bonoDetalle->valor == $detalleDepartamentoPV) {
                                    $condicionDepartamentoPV = true;
                                }

                                break;

                            case "CONDCIUDADPV":

                                $condicionCiudadPVcount = $condicionCiudadPVcount + 1;

                                if ($bonoDetalle->valor == $detalleCiudadPV) {
                                    $condicionCiudadPV = true;
                                }

                                break;

                            case "CONDPAISUSER":

                                $condicionPaisUSERcount = $condicionPaisUSERcount + 1;
                                if ($bonoDetalle->valor == $detallePaisUSER) {
                                    $condicionPaisUSER = true;
                                }

                                break;

                            case "CONDDEPARTAMENTOUSER":

                                $condicionDepartamentoUSERcount = $condicionDepartamentoUSERcount + 1;
                                if ($bonoDetalle->valor == $detalleDepartamentoUSER) {
                                    $condicionDepartamentoUSER = true;
                                }

                                break;

                            case "CONDCIUDADUSER":

                                $condicionCiudadUSERcount = $condicionCiudadUSERcount + 1;

                                if ($bonoDetalle->valor == $detalleCiudadUSER) {
                                    $condicionCiudadUSER = true;
                                }

                                break;

                            case "CONDPUNTOVENTA":

                                $condicionPuntoVentacount = $condicionPuntoVentacount + 1;

                                if ($bonoDetalle->valor == $detallePuntoVenta) {
                                    $condicionPuntoVenta = true;
                                }

                                break;

                            case "EXPDIA":
                                $expDia = $bonoDetalle->valor;
                                $expFecha = '';

                                break;

                            case "EXPFECHA":
                                $expDia = '';
                                $expFecha = $bonoDetalle->valor;

                                break;

                            case "WFACTORBONO":
                                if ($bonoDetalle->valor != '0') {
                                    $bonoTieneRollower = true;

                                    $rollowerBono = $bonoDetalle->valor;
                                }
                                break;

                            case "WFACTORDEPOSITO":
                                if ($bonoDetalle->valor != '0') {

                                    $bonoTieneRollower = true;
                                    $rollowerDeposito = $bonoDetalle->valor;
                                }
                                break;

                            case "VALORROLLOWER":
                                if ($bonoDetalle->moneda == $detalleMonedaUSER) {

                                    $bonoTieneRollower = true;
                                    $rollowerValor = $bonoDetalle->valor;
                                }
                                break;
                            case "REPETIRBONO":

                                if ($bonoDetalle->valor) {
                                    $puederepetirBono = true;
                                }

                                break;

                            case "WINBONOID":
                                $ganaBonoId = $bonoDetalle->valor;
                                $tipobono = "WINBONOID";
                                $valor_bono = 0;

                                break;

                            case "TIPOSALDO":
                                $tiposaldo = $bonoDetalle->valor;

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

                            case "AMOUNTBONUSMAXSPIN":
                                $AMOUNTBONUSMAXSPIN = $bonoDetalle->valor;

                                break;

                            case "BONUSPLANIDALTENAR":
                                $bonusPlanIdAltenar = $bonoDetalle->valor;

                                break;

                            case "BONUSCODEALTENAR":
                                $bonusCodeAltenar = $bonoDetalle->valor;

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

                                if ($bonoDetalle->valor != '0') {


                                    if ($CodePromo != "") {
                                        if ($CodePromo != $bonoDetalle->valor) {
                                            $condicionTrigger = false;

                                        }
                                    } else {

                                        if ($tipoBono == 2) {

                                            //Analizar bien esta parte del CODIGO -> Creo que se esta cumpliendo el ROLLOWER

                                            $sqlDetalleBonoPendiente = "SELECT a.usubono_id FROM usuario_bono a WHERE a.bono_id='" . $bono->bono_id . "' AND a.usuario_id='" . $usuarioId . "' AND a.estado='P'";
                                            $bonoDetallesPendiente = $this->execQuery($transaccion, $sqlDetalleBonoPendiente);

                                            if (count($bonoDetallesPendiente) > 0) {
                                                $condicionTriggerPosterior = $bonoDetallesPendiente[0]->usubono_id;

                                            } else {
                                                $condicionTrigger = false;

                                            }

                                        } else {
                                            $condicionTrigger = false;

                                        }

                                    }
                                }

                                break;

                            default:
                                break;
                        }

                        if ($_ENV['debug']) {

                            print_r($bonoDetalle);
                            print_r(PHP_EOL);
                            print_r($cumpleCondiciones);
                        }


                    }


                    if (!$condicionTrigger) {

                        $cumpleCondiciones = false;
                    }

                    if ($CodePromo == "") {


                        if ($condicionPaisPVcount > 0) {
                            if (!$condicionPaisPV && $detalleDepositoEfectivo) {
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


                        if ($condicionTriggerPosterior == 0) {

                            if ($puederepetirBono) {
                                $bonoElegido = $bono->bono_id;

                            } else {

                                $sqlRepiteBono = "select * from usuario_bono a where a.bono_id='" . $bono->bono_id . "' AND a.usuario_id = '" . $usuarioId . "'";
                                $repiteBono = $this->execQuery($transaccion, $sqlRepiteBono);

                                if ((!$puederepetirBono && count($repiteBono) == 0)) {
                                    $bonoElegido = $bono->bono_id;
                                } else {
                                    $cumpleCondiciones = false;
                                }

                            }
                        } else {
                            $bonoElegido = $bono->bono_id;
                        }


                    }


                    if ($cumpleCondiciones) {

                        if ($transaccion != '') {

                            if ($tipobono == "PORCENTAJE") {

                                $valor_bono = ($detalleValorDeposito) * ($valorbono) / 100;

                                if ($valor_bono > $maximopago) {
                                    $valor_bono = $maximopago;
                                }
                            } elseif ($tipobono == "VALOR") {

                                $valor_bono = $valorbono;

                            }


                            //Esta parte del codigo debe quitarse ya que al ponerse un BONO en estado 'Q'
                            //Aun no se sabe si es el bono que el usuario va a redimir y por lo tanto no debe afectar
                            //Ni el saldo ni el cupo actual.

                            //Esto debe tenerse en cuenta en el POPUP cuando el usuario elija el bono final.

                            //Se ajustaron las SQLs para que no hagan ningun efecto.

                            //Igual que el bloque de codigo similar a este que se ubica mas arriba no debe realizarse ya que
                            //Hasta no saber cual bono el usuario va a elegir no se puede comprobar el cupo.

                            /*$valor_bono_new = 0;

                            if ($condicionTriggerPosterior > 0) {

                                //$strsql = "UPDATE bono_interno SET bono_interno.cupo_actual =bono_interno.cupo_actual + " . $valor_bono . ",bono_interno.cantidad_bonos=bono_interno.cantidad_bonos+1 WHERE (bono_interno.cupo_maximo >= (bono_interno.cupo_actual + " . $valor_bono . ") OR bono_interno.cupo_maximo = 0) AND bono_interno.bono_id ='" . $bonoElegido . "'";

                                $strsql = "UPDATE bono_interno SET bono_interno.cupo_actual =bono_interno.cupo_actual + " . $valor_bono_new . ",bono_interno.cantidad_bonos=bono_interno.cantidad_bonos+0 WHERE (bono_interno.cupo_maximo >= (bono_interno.cupo_actual + " . $valor_bono_new . ") OR bono_interno.cupo_maximo = 0) AND bono_interno.bono_id ='" . $bonoElegido . "'";

                            } else {

                                //$strsql = "UPDATE bono_interno SET bono_interno.cupo_actual =bono_interno.cupo_actual + " . $valor_bono . ",bono_interno.cantidad_bonos=bono_interno.cantidad_bonos+1 WHERE (bono_interno.cupo_maximo >= (bono_interno.cupo_actual + " . $valor_bono . ") OR bono_interno.cupo_maximo = 0) AND ((bono_interno.maximo_bonos >= (bono_interno.cantidad_bonos+1)) OR bono_interno.maximo_bonos=0) AND bono_interno.bono_id ='" . $bonoElegido . "'";

                                $strsql = "UPDATE bono_interno SET bono_interno.cupo_actual =bono_interno.cupo_actual + " . $valor_bono_new . ",bono_interno.cantidad_bonos=bono_interno.cantidad_bonos+0 WHERE (bono_interno.cupo_maximo >= (bono_interno.cupo_actual + " . $valor_bono_new . ") OR bono_interno.cupo_maximo = 0) AND ((bono_interno.maximo_bonos >= (bono_interno.cantidad_bonos+1)) OR bono_interno.maximo_bonos=0) AND bono_interno.bono_id ='" . $bonoElegido . "'";

                            }

                            $resp = $this->execUpdate($transaccion, $strsql);


                            if ($resp > 0) {
                                $cumpleCondiciones = true;
                            } else {

                                $cumpleCondiciones = false;

                                $bonoElegido = 0;

                                if ($condicionTriggerPosterior > 0) {
                                    syslog(LOG_WARNING, 'BONOERROR ' . ($strsql) . ' USUBONO ' . $condicionTriggerPosterior);

                                    $strsql = "UPDATE usuario_bono SET usuario_bono.estado = 'E',usuario_bono.error_id='1' WHERE usuario_bono.usubono_id ='" . $condicionTriggerPosterior . "'";
                                    $resp = $this->execUpdate($transaccion, $strsql);

                                }

                            }*/

                        }

                    }


                    //Este if de cumpleCondiciones ya es donde se van a agregar los bonos con estado 'Q' segun sea el caso

                    if ($cumpleCondiciones) {


                        //Se agrega estas lineas referentes a fecha de expiracion necesarias para insertar un usuario_bono con estado 'Q'

                        $fechaExpiracion = '';

                        if ($expFecha != '') {
                            $fechaExpiracion = date('Y-m-d H:i:s', strtotime($expFecha));

                        }
                        if ($expDia != '') {
                            $fechaExpiracion = date('Y-m-d H:i:s', strtotime(' + ' . $expDia . ' days'));

                        }


                        if ($PermiteBonos == 1) {
                            $continue = true;
                        } else {
                            $continue = false;
                        }

                        //Si se ha llegado hasta este punto es porque el bono actual cumple condiciones y
                        //Y sigue agregarlo asignarlo, a diferencia del mismo fragmento que se encuentra mas abajo
                        //Este fragmento se encuentra dentro del foreach.
                        if ($bonoElegido != 0 && $tipobono != "") {

                            //El if de Altenar que se debe hacer con este?
                            //Se propone agregar a Usuario Bono y en caso el usuario lo elija entonces se
                            //Realiza este procedimiento posteriormente en el POPUP

                            if ($bonusPlanIdAltenar != '' && $bonusCodeAltenar != '') {

                                $estadoBono = 'Q';

                                $rollowerRequerido = 0;

                                $strSql = "insert into usuario_bono (usuario_id,bono_id,valor,valor_bono,valor_base,fecha_crea,estado,error_id,externo_id,mandante,rollower_requerido,usucrea_id,usumodif_id,fecha_expiracion) SELECT " . $usuarioId . "," . $bonoElegido . "," . $valor_bono . "," . $valorbono . " ," . $valorBase . " ,'" . date('Y-m-d H:i:s') . "','" . $estadoBono . "','0',0," . $mandante . ", $rollowerRequerido,0,0,'" . $fechaExpiracion . "' FROM bono_interno WHERE  bono_id ='" . $bonoElegido . "'";

                                $respNew = $this->execQuery($transaccion, $strSql);

                            } else {


                                if ($tipoBono == 2 || $tipoBono == 3) {

                                    if ($tipobono == "PORCENTAJE") {

                                        $valor_bono = ($detalleValorDeposito) * ($valorbono) / 100;

                                        if ($valor_bono > $maximopago && $maximopago != '0') {
                                            $valor_bono = $maximopago;
                                        }

                                    } elseif ($tipobono == "VALOR") {

                                        $valor_bono = $valorbono;

                                    }


                                    $valorBase = $detalleValorDeposito;

                                    //$strSql = array();
                                    $contSql = 0;
                                    $estadoBono = 'A';
                                    $rollowerRequerido = 0;
                                    $SumoSaldo = false;


                                    if (!$bonoTieneRollower) { //Si no tiene ROLLOWER

                                        $estadoBono = 'Q';

                                        $strSql = "insert into usuario_bono (usuario_id,bono_id,valor,valor_bono,valor_base,fecha_crea,estado,error_id,externo_id,mandante,rollower_requerido,usucrea_id,usumodif_id,fecha_expiracion) SELECT " . $usuarioId . "," . $bonoElegido . "," . $valor_bono . "," . $valorbono . " ," . $valorBase . " ,'" . date('Y-m-d H:i:s') . "','" . $estadoBono . "','0',0," . $mandante . ", $rollowerRequerido,0,0,'" . $fechaExpiracion . "' FROM bono_interno WHERE  bono_id ='" . $bonoElegido . "'";

                                        $respNew = $this->execQuery($transaccion, $strSql);


                                    } else { //Si el bono tiene ROLLOWER

                                        if ($CodePromo != "" && $tipobono == 2) {
                                            $estadoBono = 'P';

                                        } else {

                                            //$rollowerDeposito && $ganaBonoId == 0
                                            if ($rollowerDeposito) {
                                                $rollowerRequerido = $rollowerRequerido + ($rollowerDeposito * $detalleValorDeposito);
                                            }

                                            if ($rollowerBono) {
                                                $rollowerRequerido = $rollowerRequerido + ($rollowerBono * $valor_bono);

                                            }

                                            if ($rollowerValor) {
                                                $rollowerRequerido = $rollowerRequerido + ($rollowerValor);

                                            }


                                            $estadoBono = 'Q';

                                            $strSql = "insert into usuario_bono (usuario_id,bono_id,valor,valor_bono,valor_base,fecha_crea,estado,error_id,externo_id,mandante,rollower_requerido,usucrea_id,usumodif_id,fecha_expiracion) SELECT " . $usuarioId . "," . $bonoElegido . "," . $valor_bono . "," . $valorbono . " ," . $valorBase . " ,'" . date('Y-m-d H:i:s') . "','" . $estadoBono . "','0',0," . $mandante . ", $rollowerRequerido,0,0,'" . $fechaExpiracion . "' FROM bono_interno WHERE  bono_id ='" . $bonoElegido . "'";

                                            $respNew = $this->execQuery($transaccion, $strSql);


                                            //Esto es lo que estaba antes
                                            //$valor_bono_new = 0;
                                            ////$contSql = $contSql + 1;
                                            //$strSql[$contSql] = "update registro,bono_interno set registro.creditos_bono=registro.creditos_bono+" . $valor_bono . " where registro.mandante=" . $mandante . " and registro.usuario_id=" . $usuarioId . "  AND bono_id ='" . $bonoElegido . "'";
                                            //$strSql[$contSql] = "update registro,bono_interno set registro.creditos_bono=registro.creditos_bono+" . $valor_bono_new . " where registro.mandante=" . $mandante . " and registro.usuario_id=" . $usuarioId . "  AND bono_id ='" . $bonoElegido . "'";
                                            //$strSql = "update registro,bono_interno set registro.creditos_bono=registro.creditos_bono+" . $valor_bono_new . " where registro.mandante=" . $mandante . " and registro.usuario_id=" . $usuarioId . "  AND bono_id ='" . $bonoElegido . "'";
                                            //$respNew = $this->execUpdate($transaccion, $strSql);


                                        }


                                    }

                                    if ($condicionTriggerPosterior > 0) {


                                        $estadoBono = 'Q';


                                        $strSql = "insert into usuario_bono (usuario_id,bono_id,valor,valor_bono,valor_base,fecha_crea,estado,error_id,externo_id,mandante,rollower_requerido,usucrea_id,usumodif_id,fecha_expiracion) SELECT " . $usuarioId . "," . $bonoElegido . "," . $valor_bono . "," . $valorbono . " ," . $valorBase . " ,'" . date('Y-m-d H:i:s') . "','" . $estadoBono . "','0',0," . $mandante . ", $rollowerRequerido,0,0,'" . $fechaExpiracion . "' FROM bono_interno WHERE  bono_id ='" . $bonoElegido . "'";

                                        $respNew = $this->execQuery($transaccion, $strSql);


                                        //Esto es lo que habia antes
                                        //$contSql = $contSql + 1;
                                        //$strSql[$contSql] = "UPDATE usuario_bono,bono_interno SET usuario_bono.valor='" . $valor_bono . "',usuario_bono.valor_bono='" . $valorbono . "',usuario_bono.valor_base='" . $valorBase . "',usuario_bono.estado='" . $estadoBono . "',usuario_bono.error_id='0',usuario_bono.externo_id='0',usuario_bono.mandante='" . $mandante . "',usuario_bono.rollower_requerido='" . $rollowerRequerido . "',usuario_bono.fecha_crea='" . date('Y-m-d H:i:s') . "' WHERE usuario_bono.usubono_id = '" . $condicionTriggerPosterior . "' AND usuario_bono.bono_id ='" . $bonoElegido . "' AND bono_interno.bono_id ='" . $bonoElegido . "'  AND bono_interno.bono_id ='" . $bonoElegido . "'";


                                        //$contSql = $contSql + 1;
                                        //$strSql[$contSql] = "UPDATE bono_interno SET bono_interno.cupo_actual =bono_interno.cupo_actual + " . $valor_bono . " WHERE bono_interno.bono_id ='" . $bonoElegido . "'";


                                        $strSql = "UPDATE usuario_bono,bono_interno SET usuario_bono.valor='" . $valor_bono . "',usuario_bono.valor_bono='" . $valorbono . "',usuario_bono.valor_base='" . $valorBase . "',usuario_bono.estado='" . $estadoBono . "',usuario_bono.error_id='0',usuario_bono.externo_id='0',usuario_bono.mandante='" . $mandante . "',usuario_bono.rollower_requerido='" . $rollowerRequerido . "',usuario_bono.fecha_crea='" . date('Y-m-d H:i:s') . "' WHERE usuario_bono.usubono_id = '" . $condicionTriggerPosterior . "' AND usuario_bono.bono_id ='" . $bonoElegido . "' AND bono_interno.bono_id ='" . $bonoElegido . "'  AND bono_interno.bono_id ='" . $bonoElegido . "'";

                                        $respNew = $this->execUpdate($transaccion, $strSql);


                                        $strSql = "UPDATE bono_interno SET bono_interno.cupo_actual =bono_interno.cupo_actual + " . $valor_bono . " WHERE bono_interno.bono_id ='" . $bonoElegido . "'";

                                        $respNew = $this->execUpdate($transaccion, $strSql);


                                    } else {

                                        if ($estadoBono == 'A') {

                                            $fechaExpiracion = '';

                                            if ($expFecha != '') {
                                                $fechaExpiracion = date('Y-m-d H:i:s', strtotime($expFecha));

                                            }
                                            if ($expDia != '') {
                                                $fechaExpiracion = date('Y-m-d H:i:s', strtotime(' + ' . $expDia . ' days'));

                                            }

                                            //Esto es lo que habia antes
                                            //$contSql = $contSql + 1;
                                            //$strSql[$contSql] = "insert into usuario_bono (usuario_id,bono_id,valor,valor_bono,valor_base,fecha_crea,estado,error_id,externo_id,mandante,rollower_requerido,usucrea_id,usumodif_id,fecha_expiracion) SELECT " . $usuarioId . "," . $bonoElegido . "," . $valor_bono . "," . $valorbono . " ," . $valorBase . " ,'" . date('Y-m-d H:i:s') . "','" . $estadoBono . "','0',0," . $mandante . ", $rollowerRequerido,0,0,'" . $fechaExpiracion . "' FROM bono_interno WHERE  bono_id ='" . $bonoElegido . "'";

                                            $strSql = "insert into usuario_bono (usuario_id,bono_id,valor,valor_bono,valor_base,fecha_crea,estado,error_id,externo_id,mandante,rollower_requerido,usucrea_id,usumodif_id,fecha_expiracion) SELECT " . $usuarioId . "," . $bonoElegido . "," . $valor_bono . "," . $valorbono . " ," . $valorBase . " ,'" . date('Y-m-d H:i:s') . "','" . $estadoBono . "','0',0," . $mandante . ", $rollowerRequerido,0,0,'" . $fechaExpiracion . "' FROM bono_interno WHERE  bono_id ='" . $bonoElegido . "'";

                                            $respNew = $this->execQuery($transaccion, $strSql);


                                        } else {


                                            //Esto es lo que habia antes
                                            //$contSql = $contSql + 1;
                                            //$strSql[$contSql] = "insert into usuario_bono (usuario_id,bono_id,valor,valor_bono,valor_base,fecha_crea,estado,error_id,externo_id,mandante,rollower_requerido,usucrea_id,usumodif_id) SELECT " . $usuarioId . "," . $bonoElegido . "," . $valor_bono . "," . $valorbono . " ," . $valorBase . " ,'" . date('Y-m-d H:i:s') . "','" . $estadoBono . "','0',0," . $mandante . ", $rollowerRequerido,0,0 FROM bono_interno WHERE  bono_id ='" . $bonoElegido . "'";

                                            //$strSql = "insert into usuario_bono (usuario_id,bono_id,valor,valor_bono,valor_base,fecha_crea,estado,error_id,externo_id,mandante,rollower_requerido,usucrea_id,usumodif_id) SELECT " . $usuarioId . "," . $bonoElegido . "," . $valor_bono . "," . $valorbono . " ," . $valorBase . " ,'" . date('Y-m-d H:i:s') . "','" . $estadoBono . "','0',0," . $mandante . ", $rollowerRequerido,0,0 FROM bono_interno WHERE  bono_id ='" . $bonoElegido . "'";

                                            //$respNew = $this->execQuery($transaccion, $strSql);
                                        }

                                        //Esto es lo que habia antes
                                        //$contSql = $contSql + 1;
                                        //$strSql[$contSql] = "UPDATE bono_interno SET bono_interno.cupo_actual =bono_interno.cupo_actual + " . $valor_bono . ",bono_interno.cantidad_bonos=bono_interno.cantidad_bonos+1 WHERE bono_interno.bono_id ='" . $bonoElegido . "'";

                                        $strSql = "UPDATE bono_interno SET bono_interno.cupo_actual =bono_interno.cupo_actual + " . $valor_bono . ",bono_interno.cantidad_bonos=bono_interno.cantidad_bonos+1 WHERE bono_interno.bono_id ='" . $bonoElegido . "'";

                                        $respNew = $this->execUpdate($transaccion, $strSql);


                                    }

                                    if ($transaccion != "") {


                                        foreach ($strSql as $val) {

                                            $resp = $this->execUpdate($transaccion, $val);

                                            if ($SumoSaldo && (strpos($val, 'insert into usuario_bono') !== false)) {
                                                $last_insert_id = $resp;
                                                $tibodebono = 'F';

                                                if ($tipoBono == 2) {
                                                    $tibodebono = 'D';


                                                }

                                                if ($tipoBono == 3) {
                                                    $tibodebono = 'ND';

                                                }


                                                if ($last_insert_id != "" && is_numeric($last_insert_id) && $valor_bono != 0) {

                                                    $sql2 = "insert into bono_log (usuario_id,tipo,valor,estado,id_externo,mandante,transaccion_id,tipobono_id,fecha_crea) values (" . $usuarioId . ",'" . $tibodebono . "','" . $valor_bono . "','L','" . $last_insert_id . "','0',0,4,'" . date('Y-m-d H:i:s') . "')";
                                                    $resp2 = $this->execUpdate($transaccion, $sql2);
                                                }

                                            }


                                        }
                                        if ($condicionTriggerPosterior > 0 && $SumoSaldo) {
                                            $tibodebono = 'F';

                                            if ($tipoBono == 2) {
                                                $tibodebono = 'D';

                                            }

                                            if ($tipoBono == 3) {
                                                $tibodebono = 'ND';

                                            }


                                            $sql2 = "insert into bono_log (usuario_id,tipo,valor,estado,id_externo,mandante,transaccion_id,tipobono_id,fecha_crea) values (" . $usuarioId . ",'" . $tibodebono . "','" . $valor_bono . "','L','" . $condicionTriggerPosterior . "','0',0,4,'" . date('Y-m-d H:i:s') . "')";
                                            $resp2 = $this->execUpdate($transaccion, $sql2);

                                        }


                                    }


                                    // $contSql = $contSql + 1;
                                    // $strSql[$contSql] = "insert into bono_log (usuario_id,tipo,valor,estado,id_externo,mandante,transaccion_id,tipobono_id) values (" . $usuarioId . ",'" . $tipoBonoS . "','" . $valor_bono  . "','L','0'," . $mandante . ",0,4)";


                                    $respuesta["WinBonus"] = true;
                                    $respuesta["SumoSaldo"] = $SumoSaldo;
                                    $respuesta["Bono"] = $bonoElegido;
                                    $respuesta["Valor"] = $valor_bono;
                                    $respuesta["queries"] = $strSql;
                                }

                                if ($tipoBono == 3) {


                                    $resp = $this->agregarBonoFree($bonoElegido, $usuarioId, $mandante, $detalles, '', '', $transaccion);

                                    if ($transaccion == '') {
                                        foreach ($resp->queries as $val) {
                                            $contSql = $contSql + 1;
                                            $strSql[$contSql] = $val;
                                        }
                                    }
                                }

                                if ($tipoBono == 6) {


                                    $resp = $this->agregarBonoFree($bonoElegido, $usuarioId, $mandante, $detalles, '', '', $transaccion);

                                    if ($transaccion == '') {
                                        foreach ($resp->queries as $val) {
                                            $contSql = $contSql + 1;
                                            $strSql[$contSql] = $val;
                                        }
                                    }
                                    return $resp;


                                }
                            }
                        }

                    }

                }

            }


        } elseif ($cantidadBonosXGanar <= 1) {

            //Entro a este caso cuando el usuario solo puede ganar un bono entre los bonos activos


            $cumpleCondiciones = false;
            $bonoElegido = 0;
            $bonoTieneRollower = false;
            $rollowerBono = 0;
            $rollowerDeposito = 0;
            $continue = true;


            //Este es el FOREACH que habia antes y estaba unico

            foreach ($bonosDisponibles as $bono) {

                $bono->bono_id = $bono->{'a.bono_id'};
                $bono->tipo = $bono->{'a.tipo'};
                $bono->fecha_inicio = $bono->{'a.fecha_inicio'};
                $bono->fecha_fin = $bono->{'a.fecha_fin'};
                $PermiteBonos = $bono->{'a.permite_bonos'};
                $pertenece_crm = $bono->{'a.pertenece_crm'};


                if ((!$cumpleCondiciones && ($tipoBono == $bono->tipo || $CodePromo != "")) || ($cumpleCondiciones && $continue && ($tipoBono == $bono->tipo || $CodePromo != ""))) {


                    //Obtenemos todos los detalles del bono
                    $sqlDetalleBono = "select * from bono_detalle a where a.bono_id='" . $bono->bono_id . "' AND (moneda='' OR moneda='" . $detalleMonedaUSER . "') ";
                    $bonoDetalles = $this->execQuery($transaccion, $sqlDetalleBono);

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

                    $puederepetirBono = false;
                    $ganaBonoId = 0;


                    $maximopago = 0;
                    $maximodeposito = 0;
                    $minimodeposito = 0;
                    $valorbono = 0;
                    $tipoproducto = 0;
                    $tipobono = "";
                    $bonoTieneRollower = false;
                    $tiposaldo = -1;


                    $bonusPlanIdAltenar = '';
                    $bonusCodeAltenar = '';

                    if ($tipoBono != $bono->tipo && $CodePromo == "") {
                        $cumpleCondiciones = false;

                    }

                    if ($CodePromo != "" && $tipoBono == '') {
                        $tipoBono = $bono->tipo;

                    }

                    $expDia = '';
                    $expFecha = '';

                    if ($pertenece_crm == 'S' && !$isForCRM) {

                        $sqlUsuarioBonos = "SELECT a.usubono_id FROM usuario_bono a WHERE a.bono_id='" . $bono->bono_id . "' AND a.usuario_id='" . $usuarioId . "' AND a.estado='P'";

                        $respuesta["sql"] = $sqlBonos;

                        $bonosUsuarioDisponibles = $this->execQuery($transaccion, $sqlUsuarioBonos);

                        if (count($bonosUsuarioDisponibles) == 0) {
                            break;
                        }

                    }

                    foreach ($bonoDetalles as $bonoDetalle) {


                        $bonoDetalle->tipo = $bonoDetalle->{'a.tipo'};
                        $bonoDetalle->valor = $bonoDetalle->{'a.valor'};
                        $bonoDetalle->moneda = $bonoDetalle->{'a.moneda'};

                        switch ($bonoDetalle->tipo) {


                            case "TIPOPRODUCTO":
                                $tipoproducto = $bonoDetalle->valor;


                                break;

                            case "CANTDEPOSITOS":
                                if ($detalleDepositos != ($bonoDetalle->valor - 1) && $bonoDetalle->valor != 0 && $bono->tipo == 2) {

                                    $cumpleCondiciones = false;

                                }

                                break;

                            case "CONDEFECTIVO":
                                if ($detalleDepositoEfectivo) {
                                    if (($bonoDetalle->valor == "true")) {
                                        $condicionmetodoPago = true;
                                    }
                                } else {
                                    if (($bonoDetalle->valor != "true")) {
                                        $condicionmetodoPago = false;
                                    }
                                }
                                $condicionmetodoPagocount++;


                                break;


                            case "PORCENTAJE":
                                $tipobono = "PORCENTAJE";
                                $valorbono = $bonoDetalle->valor;

                                break;


                            case "NUMERODEPOSITO":

                                break;

                            case "MAXJUGADORES":


                                if ($bono->tipo == 2 && $bonoDetalle->valor != '0') {

                                    $sqlDetalleBonoPendiente = "SELECT a.usubono_id FROM usuario_bono a WHERE a.bono_id='" . $bono->bono_id . "' AND a.usuario_id='" . $usuarioId . "' AND a.estado='P'";

                                    $bonoDetallesPendiente = $this->execQuery($transaccion, $sqlDetalleBonoPendiente);

                                    if (count($bonoDetallesPendiente) > 0) {
                                        $condicionTriggerPosterior = $bonoDetallesPendiente[0]->{'a.usubono_id'};
                                        if ($condicionTriggerPosterior == "" || $condicionTriggerPosterior == 0) {
                                            $cumpleCondiciones = false;

                                        }

                                    } else {
                                        $cumpleCondiciones = false;

                                        /* $sqlDetalleBonoPendiente = "SELECT a.usubono_id FROM usuario_bono a WHERE a.bono_id='" . $bono->bono_id . "' AND a.usuario_id='0' AND a.estado='L'";
                                         $bonoDetallesPendiente = $this->execQuery($transaccion, $sqlDetalleBonoPendiente);

                                         if (count($bonoDetallesPendiente) > 0) {
                                             $condicionTriggerPosterior=$bonoDetallesPendiente[0]->{'a.usubono_id'};
                                             if($condicionTriggerPosterior == "" || $condicionTriggerPosterior==0){
                                                 $cumpleCondiciones = false;

                                             }

                                         }else{
                                             $cumpleCondiciones = false;

                                         }
     */
                                    }

                                }

                                break;


                            case "MAXPAGO":

                                if ($bonoDetalle->moneda == $detalleMonedaUSER) {

                                    $maximopago = $bonoDetalle->valor;

                                }
                                break;

                            case "MAXDEPOSITO":

                                $maximodeposito = $bonoDetalle->valor;
                                if ($bonoDetalle->moneda == $detalleMonedaUSER) {

                                    if ($detalleValorDeposito > $maximodeposito && $maximodeposito != 0) {
                                        $cumpleCondiciones = false;
                                    }
                                }

                                break;

                            case "MINDEPOSITO":

                                $minimodeposito = $bonoDetalle->valor;

                                if ($bonoDetalle->moneda == $detalleMonedaUSER) {

                                    if ($detalleValorDeposito < $minimodeposito) {
                                        $cumpleCondiciones = false;
                                    }
                                }

                                break;

                            case "VALORBONO":
                                if ($bonoDetalle->moneda == $detalleMonedaUSER) {

                                    $valorbono = $bonoDetalle->valor;
                                    $tipobono = "VALOR";
                                }
                                break;

                            case "CONDPAYMENT":

                                if ($detalleDepositoMetodoPago == $bonoDetalle->valor && $bonoDetalle->valor != '') {
                                    $condicionmetodoPago = true;

                                }
                                if ($bonoDetalle->valor != '') {
                                    $condicionmetodoPagocount++;
                                }

                                break;

                            case "CONDPAISPV":

                                $condicionPaisPVcount = $condicionPaisPVcount + 1;
                                if ($bonoDetalle->valor == $detallePaisPV) {
                                    $condicionPaisPV = true;
                                }

                                break;

                            case "CONDDEPARTAMENTOPV":

                                $condicionDepartamentoPVcount = $condicionDepartamentoPVcount + 1;
                                if ($bonoDetalle->valor == $detalleDepartamentoPV) {
                                    $condicionDepartamentoPV = true;
                                }

                                break;

                            case "CONDCIUDADPV":

                                $condicionCiudadPVcount = $condicionCiudadPVcount + 1;

                                if ($bonoDetalle->valor == $detalleCiudadPV) {
                                    $condicionCiudadPV = true;
                                }

                                break;

                            case "CONDPAISUSER":

                                $condicionPaisUSERcount = $condicionPaisUSERcount + 1;
                                if ($bonoDetalle->valor == $detallePaisUSER) {
                                    $condicionPaisUSER = true;
                                }

                                break;

                            case "CONDDEPARTAMENTOUSER":

                                $condicionDepartamentoUSERcount = $condicionDepartamentoUSERcount + 1;
                                if ($bonoDetalle->valor == $detalleDepartamentoUSER) {
                                    $condicionDepartamentoUSER = true;
                                }

                                break;

                            case "CONDCIUDADUSER":

                                $condicionCiudadUSERcount = $condicionCiudadUSERcount + 1;

                                if ($bonoDetalle->valor == $detalleCiudadUSER) {
                                    $condicionCiudadUSER = true;
                                }

                                break;

                            case "CONDPUNTOVENTA":

                                $condicionPuntoVentacount = $condicionPuntoVentacount + 1;

                                if ($bonoDetalle->valor == $detallePuntoVenta) {
                                    $condicionPuntoVenta = true;
                                }

                                break;

                            case "EXPDIA":
                                $expDia = $bonoDetalle->valor;
                                $expFecha = '';

                                break;

                            case "EXPFECHA":
                                $expDia = '';
                                $expFecha = $bonoDetalle->valor;

                                break;

                            case "WFACTORBONO":
                                if ($bonoDetalle->valor != '0') {
                                    $bonoTieneRollower = true;

                                    $rollowerBono = $bonoDetalle->valor;
                                }
                                break;

                            case "WFACTORDEPOSITO":
                                if ($bonoDetalle->valor != '0') {

                                    $bonoTieneRollower = true;
                                    $rollowerDeposito = $bonoDetalle->valor;
                                }
                                break;

                            case "VALORROLLOWER":
                                if ($bonoDetalle->moneda == $detalleMonedaUSER) {

                                    $bonoTieneRollower = true;
                                    $rollowerValor = $bonoDetalle->valor;
                                }
                                break;
                            case "REPETIRBONO":

                                if ($bonoDetalle->valor) {
                                    $puederepetirBono = true;
                                }

                                break;

                            case "WINBONOID":
                                $ganaBonoId = $bonoDetalle->valor;
                                $tipobono = "WINBONOID";
                                $valor_bono = 0;

                                break;

                            case "TIPOSALDO":
                                $tiposaldo = $bonoDetalle->valor;

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

                            case "AMOUNTBONUSMAXSPIN":
                                $AMOUNTBONUSMAXSPIN = $bonoDetalle->valor;

                                break;

                            case "BONUSPLANIDALTENAR":
                                $bonusPlanIdAltenar = $bonoDetalle->valor;

                                break;

                            case "BONUSCODEALTENAR":
                                $bonusCodeAltenar = $bonoDetalle->valor;

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

                                if ($bonoDetalle->valor != '0') {


                                    if ($CodePromo != "") {
                                        if ($CodePromo != $bonoDetalle->valor) {
                                            $condicionTrigger = false;

                                        }
                                    } else {

                                        if ($tipoBono == 2) {
                                            $sqlDetalleBonoPendiente = "SELECT a.usubono_id FROM usuario_bono a WHERE a.bono_id='" . $bono->bono_id . "' AND a.usuario_id='" . $usuarioId . "' AND a.estado='P'";
                                            $bonoDetallesPendiente = $this->execQuery($transaccion, $sqlDetalleBonoPendiente);

                                            if (count($bonoDetallesPendiente) > 0) {
                                                $condicionTriggerPosterior = $bonoDetallesPendiente[0]->usubono_id;

                                            } else {
                                                $condicionTrigger = false;

                                            }

                                        } else {
                                            $condicionTrigger = false;

                                        }

                                    }
                                }

                                break;

                            default:

                                //   if (stristr($bonodetalle->{'bono_detalle.tipo'}, 'CONDGAME')) {
                                //
                                // }
                                //
                                //   if (stristr($bonodetalle->{'bono_detalle.tipo'}, 'ITAINMENT')) {
                                //
                                //
                                //
                                //   }
                                break;
                        }


                        if ($_ENV['debug']) {

                            print_r($bonoDetalle);
                            print_r(PHP_EOL);
                            print_r($cumpleCondiciones);
                        }


                    }


                    if (!$condicionTrigger) {

                        $cumpleCondiciones = false;
                    }

                    if ($CodePromo == "") {


                        if ($condicionPaisPVcount > 0) {
                            if (!$condicionPaisPV && $detalleDepositoEfectivo) {
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


                        if ($condicionTriggerPosterior == 0) {

                            if ($puederepetirBono) {
                                $bonoElegido = $bono->bono_id;

                            } else {

                                $sqlRepiteBono = "select * from usuario_bono a where a.bono_id='" . $bono->bono_id . "' AND a.usuario_id = '" . $usuarioId . "'";
                                $repiteBono = $this->execQuery($transaccion, $sqlRepiteBono);

                                if ((!$puederepetirBono && count($repiteBono) == 0)) {
                                    $bonoElegido = $bono->bono_id;
                                } else {
                                    $cumpleCondiciones = false;
                                }

                            }
                        } else {
                            $bonoElegido = $bono->bono_id;
                        }


                    }


                    if ($cumpleCondiciones) {


                        if ($transaccion != '') {


                            if ($tipobono == "PORCENTAJE") {

                                $valor_bono = ($detalleValorDeposito) * ($valorbono) / 100;

                                if ($valor_bono > $maximopago) {
                                    $valor_bono = $maximopago;
                                }
                            } elseif ($tipobono == "VALOR") {

                                $valor_bono = $valorbono;

                            }

                            if ($condicionTriggerPosterior > 0) {
                                $strsql = "UPDATE bono_interno SET bono_interno.cupo_actual =bono_interno.cupo_actual + " . $valor_bono . ",bono_interno.cantidad_bonos=bono_interno.cantidad_bonos+1 WHERE (bono_interno.cupo_maximo >= (bono_interno.cupo_actual + " . $valor_bono . ") OR bono_interno.cupo_maximo = 0) AND bono_interno.bono_id ='" . $bonoElegido . "'";

                            } else {
                                $strsql = "UPDATE bono_interno SET bono_interno.cupo_actual =bono_interno.cupo_actual + " . $valor_bono . ",bono_interno.cantidad_bonos=bono_interno.cantidad_bonos+1 WHERE (bono_interno.cupo_maximo >= (bono_interno.cupo_actual + " . $valor_bono . ") OR bono_interno.cupo_maximo = 0) AND ((bono_interno.maximo_bonos >= (bono_interno.cantidad_bonos+1)) OR bono_interno.maximo_bonos=0) AND bono_interno.bono_id ='" . $bonoElegido . "'";

                            }


                            $resp = $this->execUpdate($transaccion, $strsql);


                            if ($resp > 0) {
                                $cumpleCondiciones = true;
                            } else {

                                $cumpleCondiciones = false;
                                $bonoElegido = 0;

                                if ($condicionTriggerPosterior > 0) {
                                    syslog(LOG_WARNING, 'BONOERROR ' . ($strsql) . ' USUBONO ' . $condicionTriggerPosterior);

                                    $strsql = "UPDATE usuario_bono SET usuario_bono.estado = 'E',usuario_bono.error_id='1' WHERE usuario_bono.usubono_id ='" . $condicionTriggerPosterior . "'";
                                    $resp = $this->execUpdate($transaccion, $strsql);


                                }

                            }

                        }

                    }

                    if ($cumpleCondiciones) {
                        if ($PermiteBonos == 1) {
                            $continue = true;
                        } else {
                            $continue = false;
                        }
                    }


                }

            }

            $respuesta["Bono"] = 0;
            $respuesta["WinBonus"] = false;

            if ($_ENV['debug']) {

                print_r(PHP_EOL);
                print_r($respuesta);
            }

            //Este if se encarga de pagar el bono elegido, es donde se altera los saldos y se cambian estados
            //A diferencia del caso anterior respecto a la cantidad de bonos que el usuario puede ganarse
            //No se encuentra incluido dentro del Forech

            if ($bonoElegido != 0 && $tipobono != "") {

                if ($bonusPlanIdAltenar != '' && $bonusCodeAltenar != '') {

                    if ($mandante == '14' || $mandante == '17') {

                        $valorBase = $detalleValorDeposito;

                        $strSql = array();
                        $contSql = 0;
                        $estadoBono = 'A';
                        $rollowerRequerido = 0;
                        $SumoSaldo = false;

                        try {
                            if ($tipobono == "PORCENTAJE") {

                                $valor_bono = ($detalleValorDeposito) * ($valorbono) / 100;

                                if ($maximopago != 0 && $maximopago != '' && $valor_bono > $maximopago) {
                                    $valor_bono = $maximopago;
                                }
                            } elseif ($tipobono == "VALOR") {

                                $valor_bono = $valorbono;

                            }


                            $IdUsuarioAltenar = $usuarioId;
                            if ((intval($usuarioId) > 73758) || (in_array(intval($usuarioId), array(70363, 72098, 67393, 73497, 57196)))) {
                                $IdUsuarioAltenar = $usuarioId . "U";
                            }

                            $dataD = array(
                                "ExtUserId" => $IdUsuarioAltenar,
                                "WalletCode" => $walletCode,
                                "BonusPlanId" => $bonusPlanIdAltenar,
                                "Deposit" => floatval($valor_bono) * 100
                            );

                            $dataD = json_encode($dataD);

                            // Inicializar la clase CurlWrapper
                            $curl = new CurlWrapper($urlAltenar . '/api/Bonus/CreateBonusByDeposit/json');

                            // Configurar opciones
                            $curl->setOptionsArray([
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_ENCODING => '',
                                CURLOPT_MAXREDIRS => 10,
                                CURLOPT_TIMEOUT => 300,
                                CURLOPT_FOLLOWLOCATION => true,
                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                CURLOPT_CUSTOMREQUEST => 'POST',
                                CURLOPT_POSTFIELDS => $dataD,
                                CURLOPT_HTTPHEADER => array(
                                    'Content-Type: application/json'
                                ),
                            ]);

                            // Ejecutar la solicitud
                            $response = $curl->execute();

                            $response = json_decode($response);

                            if ($response != null) {

                                if ($response->CreateBonusByDepositMessageResult != null) {
                                    if ($response->CreateBonusByDepositMessageResult->Error == 'ClientNotFound') {
                                        $Usuario = new Usuario($usuarioId);
                                        $Registro = new Registro('', $usuarioId);
                                        $Pais = new Pais($Usuario->paisId);

                                        $Mandante = new Mandante($Usuario->mandante);
                                        $pathPartner = $Mandante->pathItainment;
                                        $pathFixed = $Pais->codigoPath;
                                        $usermoneda = $Usuario->moneda;
                                        $userpath = $pathFixed;

                                        $pathFixed = '2:Web ' . $usermoneda . ',' . $userpath;

                                        if ($Mandante->mandante != '') {
                                            if (is_numeric($Mandante->mandante)) {
                                                if (intval($Mandante->mandante) > 2) {

                                                    $pathFixed = '2:Web' . $Mandante->mandante . ' ' . $usermoneda . ',' . $Mandante->mandante . $userpath;
                                                    if (intval($Mandante->mandante) == 9) {

                                                        $pathFixed = '2:Web' . $Mandante->mandante . ' ' . $usermoneda . ',' . $Mandante->mandante . 'W' . $userpath;

                                                    }

                                                }
                                            }
                                        }


                                        if ($Mandante->mandante == '12') {
                                            $pathPartner = "1:powerbet,S16-" . $Usuario->paisId;
                                        }


                                        if ($Mandante->mandante == '18') {
                                            $pathPartner = "1:gangabet,S22-" . $Usuario->paisId;
                                            if ($Usuario->paisId == '173') {
                                                $pathPartner = "1:gangabet,S22-" . $Usuario->paisId;
                                            }
                                        }

                                        if ($mandante == '0' && $detallePaisUSER == 60) {
                                            $walletCode = "160124";
                                        }

                                        if ($mandante == '0' && $detallePaisUSER == 2) {
                                            $walletCode = "160124";
                                        }

                                        //Filtramos por id Usuario capturado en CreateBonus
                                        $IdUsuarioAltenar = $Usuario->usuarioId;
                                        if ((intval($usuarioId) > 73758) || (in_array(intval($usuarioId), array(70363, 72098, 67393, 73497, 57196)))) {
                                            $IdUsuarioAltenar = $Usuario->usuarioId . "U";
                                        }

                                        $dataD = array(
                                            "ExtUser" => array(
                                                "LoginName" => $Usuario->nombre,
                                                "Currency" => $Usuario->moneda,
                                                "Country" => $Pais->iso,
                                                "ExternalUserId" => $IdUsuarioAltenar,
                                                "AffiliationPath" => $Usuario->getAffiliationPathAltenar(),
                                                "UserCode" => "3",
                                                "FirstName" => $Registro->nombre1,
                                                "LastName" => $Registro->apellido1,
                                                "UserBalance" => "0"),
                                            "WalletCode" => $walletCode
                                        );

                                        $dataD = json_encode($dataD);


                                        // Inicializar la clase CurlWrapper
                                        $curl = new CurlWrapper($urlAltenar . '/api/Bonus/CreateUser/json');

                                        // Configurar opciones
                                        $curl->setOptionsArray([
                                            CURLOPT_RETURNTRANSFER => true,
                                            CURLOPT_ENCODING => '',
                                            CURLOPT_MAXREDIRS => 10,
                                            CURLOPT_TIMEOUT => 300,
                                            CURLOPT_FOLLOWLOCATION => true,
                                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                            CURLOPT_CUSTOMREQUEST => 'POST',
                                            CURLOPT_POSTFIELDS => $dataD,
                                            CURLOPT_HTTPHEADER => array(
                                                'Content-Type: application/json'
                                            ),
                                        ]);

                                        // Ejecutar la solicitud
                                        $response = $curl->execute();
                                        sleep(1);

                                        $IdUsuarioAltenar = $usuarioId;
                                        if ((intval($usuarioId) > 73758) || (in_array(intval($usuarioId), array(70363, 72098, 67393, 73497, 57196)))) {
                                            $IdUsuarioAltenar = $usuarioId . "U";
                                        }

                                        $dataD = array(
                                            "ExtUserId" => $IdUsuarioAltenar,
                                            "WalletCode" => $walletCode,
                                            "BonusPlanId" => $bonusPlanIdAltenar,
                                            "Deposit" => floatval($valor_bono) * 100
                                        );

                                        $dataD = json_encode($dataD);

                                        // Inicializar la clase CurlWrapper
                                        $curl = new CurlWrapper($urlAltenar . '/api/Bonus/CreateBonusByDeposit/json');

                                        // Configurar opciones
                                        $curl->setOptionsArray([
                                            CURLOPT_RETURNTRANSFER => true,
                                            CURLOPT_ENCODING => '',
                                            CURLOPT_MAXREDIRS => 10,
                                            CURLOPT_TIMEOUT => 300,
                                            CURLOPT_FOLLOWLOCATION => true,
                                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                            CURLOPT_CUSTOMREQUEST => 'POST',
                                            CURLOPT_POSTFIELDS => $dataD,
                                            CURLOPT_HTTPHEADER => array(
                                                'Content-Type: application/json'
                                            ),
                                        ]);

                                        // Ejecutar la solicitud
                                        $response = $curl->execute();

                                    }
                                }

                            }

                        } catch (Exception $e) {

                        }

                        if (false) {

                            $contSql = $contSql + 1;
                            $strSql[$contSql] = "insert into usuario_bono (usuario_id,bono_id,valor,valor_bono,valor_base,fecha_crea,estado,error_id,externo_id,mandante,rollower_requerido,usucrea_id,usumodif_id) SELECT " . $usuarioId . "," . $bonoElegido . "," . $valor_bono . "," . $valorbono . " ," . $valorBase . " ,'" . date('Y-m-d H:i:s') . "','" . $estadoBono . "','0',0," . $mandante . ", $rollowerRequerido,0,0 FROM bono_interno WHERE  bono_id ='" . $bonoElegido . "'";


                            $contSql = $contSql + 1;
                            $strSql[$contSql] = "UPDATE bono_interno SET bono_interno.cupo_actual =bono_interno.cupo_actual + " . $valor_bono . ",bono_interno.cantidad_bonos=bono_interno.cantidad_bonos+1 WHERE bono_interno.bono_id ='" . $bonoElegido . "'";

                            if ($transaccion != "") {


                                foreach ($strSql as $val) {

                                    $resp = $this->execUpdate($transaccion, $val);

                                    if ($SumoSaldo && (strpos($val, 'insert into usuario_bono') !== false)) {
                                        $last_insert_id = $resp;
                                        $tibodebono = 'F';

                                        if ($tipoBono == 2) {
                                            $tibodebono = 'D';

                                        }

                                        if ($tipoBono == 3) {
                                            $tibodebono = 'ND';

                                        }

                                        if ($last_insert_id != "" && is_numeric($last_insert_id)) {
                                            $sql2 = "insert into bono_log (usuario_id,tipo,valor,estado,id_externo,mandante,transaccion_id,tipobono_id,fecha_crea) values (" . $usuarioId . ",'" . $tibodebono . "','" . $valor_bono . "','L','" . $last_insert_id . "','0',0,4,'" . date('Y-m-d H:i:s') . "')";
                                            $resp2 = $this->execUpdate($transaccion, $sql2);
                                        }

                                    }


                                }
                                if ($condicionTriggerPosterior > 0 && $SumoSaldo) {
                                    $tibodebono = 'F';

                                    if ($tipoBono == 2) {
                                        $tibodebono = 'D';

                                    }

                                    if ($tipoBono == 3) {
                                        $tibodebono = 'ND';

                                    }

                                    $sql2 = "insert into bono_log (usuario_id,tipo,valor,estado,id_externo,mandante,transaccion_id,tipobono_id,fecha_crea) values (" . $usuarioId . ",'" . $tibodebono . "','" . $valor_bono . "','L','" . $condicionTriggerPosterior . "','0',0,4,'" . date('Y-m-d H:i:s') . "')";
                                    $resp2 = $this->execUpdate($transaccion, $sql2);

                                }


                            }
                        }


                        $respuesta["WinBonus"] = true;
                        $ganoBonoBool = true;
                        $respuesta["SumoSaldo"] = $SumoSaldo;
                        $respuesta["Bono"] = $bonoElegido;
                        $respuesta["Valor"] = $valor_bono;
                        $respuesta["queries"] = $strSql;


                    }

                    if (true) {

                        $valorBase = $detalleValorDeposito;

                        $strSql = array();
                        $contSql = 0;
                        $estadoBono = 'A';
                        $rollowerRequerido = 0;
                        $SumoSaldo = false;

                        try {
                            if ($tipobono == "PORCENTAJE") {

                                $valor_bono = ($detalleValorDeposito) * ($valorbono) / 100;

                                if ($valor_bono > $maximopago) {
                                    $valor_bono = $maximopago;
                                }
                            } elseif ($tipobono == "VALOR") {

                                $valor_bono = $valorbono;

                            }

                            $Mandante = new Mandante($mandante);

                            if ($mandante == '0' && $detallePaisUSER == 60) {
                                $walletCode = "160124";
                            }

                            if ($mandante == '0' && $detallePaisUSER == 2) {
                                $walletCode = "160124";
                            }

                            $IdUsuarioAltenar = $usuarioId;
                            if ((intval($usuarioId) > 73758) || (in_array(intval($usuarioId), array(70363, 72098, 67393, 73497, 57196)))) {
                                $IdUsuarioAltenar = $usuarioId . "U";
                            }

                            $dataD = array(
                                "ExtUserId" => $IdUsuarioAltenar,
                                "WalletCode" => $walletCode,
                                "BonusCode" => $bonusCodeAltenar,
                                "Deposit" => floatval($valor_bono) * 100
                            );

                            $IdUsuarioAltenar = $usuarioId;
                            if ((intval($usuarioId) > 73758) || (in_array(intval($usuarioId), array(70363, 72098, 67393, 73497, 57196)))) {
                                $IdUsuarioAltenar = $usuarioId . "U";
                            }

                            $dataD = array(
                                "ExtUserId" => $IdUsuarioAltenar,
                                "WalletCode" => $walletCode,
                                "BonusCode" => $bonusCodeAltenar,
                                "Deposit" => floatval($valor_bono) * 100
                            );

                            $dataD = json_encode($dataD);


                            // Inicializar la clase CurlWrapper
                            $curl = new CurlWrapper($urlAltenar . '/api/Bonus/CreateBonusByCode/json');

                            // Configurar opciones
                            $curl->setOptionsArray([
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_ENCODING => '',
                                CURLOPT_MAXREDIRS => 10,
                                CURLOPT_TIMEOUT => 300,
                                CURLOPT_FOLLOWLOCATION => true,
                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                CURLOPT_CUSTOMREQUEST => 'POST',
                                CURLOPT_POSTFIELDS => $dataD,
                                CURLOPT_HTTPHEADER => array(
                                    'Content-Type: application/json'
                                ),
                            ]);

                            // Ejecutar la solicitud
                            $response = $curl->execute();


                            $response = json_decode($response);

                        } catch (Exception $e) {

                        }

                        if (false) {

                            $contSql = $contSql + 1;
                            $strSql[$contSql] = "insert into usuario_bono (usuario_id,bono_id,valor,valor_bono,valor_base,fecha_crea,estado,error_id,externo_id,mandante,rollower_requerido,usucrea_id,usumodif_id) SELECT " . $usuarioId . "," . $bonoElegido . "," . $valor_bono . "," . $valorbono . " ," . $valorBase . " ,'" . date('Y-m-d H:i:s') . "','" . $estadoBono . "','0',0," . $mandante . ", $rollowerRequerido,0,0 FROM bono_interno WHERE  bono_id ='" . $bonoElegido . "'";


                            $contSql = $contSql + 1;
                            $strSql[$contSql] = "UPDATE bono_interno SET bono_interno.cupo_actual =bono_interno.cupo_actual + " . $valor_bono . ",bono_interno.cantidad_bonos=bono_interno.cantidad_bonos+1 WHERE bono_interno.bono_id ='" . $bonoElegido . "'";

                            if ($transaccion != "") {


                                foreach ($strSql as $val) {

                                    $resp = $this->execUpdate($transaccion, $val);

                                    if ($SumoSaldo && (strpos($val, 'insert into usuario_bono') !== false)) {
                                        $last_insert_id = $resp;
                                        $tibodebono = 'F';

                                        if ($tipoBono == 2) {
                                            $tibodebono = 'D';

                                        }

                                        if ($tipoBono == 3) {
                                            $tibodebono = 'ND';

                                        }

                                        if ($last_insert_id != "" && is_numeric($last_insert_id)) {
                                            $sql2 = "insert into bono_log (usuario_id,tipo,valor,estado,id_externo,mandante,transaccion_id,tipobono_id,fecha_crea) values (" . $usuarioId . ",'" . $tibodebono . "','" . $valor_bono . "','L','" . $last_insert_id . "','0',0,4,'" . date('Y-m-d H:i:s') . "')";
                                            $resp2 = $this->execUpdate($transaccion, $sql2);
                                        }

                                    }


                                }
                                if ($condicionTriggerPosterior > 0 && $SumoSaldo) {
                                    $tibodebono = 'F';

                                    if ($tipoBono == 2) {
                                        $tibodebono = 'D';

                                    }

                                    if ($tipoBono == 3) {
                                        $tibodebono = 'ND';

                                    }

                                    $sql2 = "insert into bono_log (usuario_id,tipo,valor,estado,id_externo,mandante,transaccion_id,tipobono_id,fecha_crea) values (" . $usuarioId . ",'" . $tibodebono . "','" . $valor_bono . "','L','" . $condicionTriggerPosterior . "','0',0,4,'" . date('Y-m-d H:i:s') . "')";
                                    $resp2 = $this->execUpdate($transaccion, $sql2);

                                }


                            }
                        }


                        $respuesta["WinBonus"] = true;
                        $ganoBonoBool = true;
                        $respuesta["SumoSaldo"] = $SumoSaldo;
                        $respuesta["Bono"] = $bonoElegido;
                        $respuesta["Valor"] = $valor_bono;
                        $respuesta["queries"] = $strSql;

                        //Asignación bonos adicionales vinculados a bono de Altenar
                        if ($respuesta["WinBonus"] && !empty($ganaBonoId)) {
                            try {
                                $BonoInterno = new BonoInterno($ganaBonoId);

                                //Retirando data específica para el bono vinculado
                                $detalles->CodePromo = null;
                                $responseGanaBonoId = $this->agregarBonoFree($ganaBonoId, $usuarioId, $mandante, $detalles, false, null, $transaccion);
                            } catch (Exception $e) {
                            }
                        }
                    }


                } else {


                    if ($tipoBono == 2) {
                        if ($tipobono == "PORCENTAJE") {

                            $valor_bono = ($detalleValorDeposito) * ($valorbono) / 100;


                            if ($valor_bono > $maximopago && $maximopago != '0') {
                                $valor_bono = $maximopago;
                            }

                        } elseif ($tipobono == "VALOR") {

                            $valor_bono = $valorbono;

                        }


                        $valorBase = $detalleValorDeposito;

                        $strSql = array();
                        $contSql = 0;
                        $estadoBono = 'A';
                        $rollowerRequerido = 0;
                        $SumoSaldo = false;

                        if (!$bonoTieneRollower) {


                            if ($CodePromo != "" && $tipobono == 2) {
                                $estadoBono = 'P';

                            } else {
                                if ($ganaBonoId == 0) {
                                    $tipoBonoS = 'D';
                                    switch ($tiposaldo) {
                                        case 0:


                                            $contSql = $contSql + 1;
                                            $strSql[$contSql] = "update registro,bono_interno set registro.creditos_base_ant=registro.creditos_base,registro.creditos_base=registro.creditos_base+" . $valor_bono . " where registro.mandante=" . $mandante . " and registro.usuario_id=" . $usuarioId . " AND bono_id ='" . $bonoElegido . "'";
                                            $estadoBono = 'R';
                                            $SumoSaldo = true;

                                            break;

                                        case 1:
                                            $contSql = $contSql + 1;
                                            $strSql[$contSql] = "update registro,bono_interno set registro.creditos_ant=registro.creditos,registro.creditos=registro.creditos+" . $valor_bono . " where registro.mandante=" . $mandante . " and registro.usuario_id=" . $usuarioId . " AND bono_id ='" . $bonoElegido . "'";
                                            $estadoBono = 'R';
                                            $SumoSaldo = true;

                                            break;

                                        case 2:
                                            $contSql = $contSql + 1;
                                            $strSql[$contSql] = "update registro,bono_interno set registro.saldo_especial=registro.saldo_especial+" . $valor_bono . " where registro.mandante=" . $mandante . " and registro.usuario_id=" . $usuarioId . " AND bono_id ='" . $bonoElegido . "'";
                                            $estadoBono = 'R';
                                            $SumoSaldo = true;

                                            break;

                                    }

                                } else {

                                    $resp = $this->agregarBonoFree($ganaBonoId, $usuarioId, $mandante, $detalles, '', '', $transaccion);

                                    if ($transaccion == "") {
                                        foreach ($resp->queries as $val) {
                                            $contSql = $contSql + 1;
                                            $strSql[$contSql] = $val;
                                        }
                                    }

                                    $estadoBono = 'R';

                                }
                            }


                        } else {

                            if ($CodePromo != "" && $tipobono == 2) {
                                $estadoBono = 'P';

                            } else {
                                //$rollowerDeposito && $ganaBonoId == 0
                                if ($rollowerDeposito) {
                                    $rollowerRequerido = $rollowerRequerido + ($rollowerDeposito * $detalleValorDeposito);
                                }

                                if ($rollowerBono) {
                                    $rollowerRequerido = $rollowerRequerido + ($rollowerBono * $valor_bono);

                                }
                                if ($rollowerValor) {
                                    $rollowerRequerido = $rollowerRequerido + ($rollowerValor);

                                }

                                $contSql = $contSql + 1;
                                $strSql[$contSql] = "update registro,bono_interno set registro.creditos_bono=registro.creditos_bono+" . $valor_bono . " where registro.mandante=" . $mandante . " and registro.usuario_id=" . $usuarioId . "  AND bono_id ='" . $bonoElegido . "'";
                            }


                        }

                        if ($condicionTriggerPosterior > 0) {


                            $contSql = $contSql + 1;
                            $strSql[$contSql] = "UPDATE usuario_bono,bono_interno SET usuario_bono.valor='" . $valor_bono . "',usuario_bono.valor_bono='" . $valorbono . "',usuario_bono.valor_base='" . $valorBase . "',usuario_bono.estado='" . $estadoBono . "',usuario_bono.error_id='0',usuario_bono.externo_id='0',usuario_bono.mandante='" . $mandante . "',usuario_bono.rollower_requerido='" . $rollowerRequerido . "',usuario_bono.fecha_crea='" . date('Y-m-d H:i:s') . "' WHERE usuario_bono.usubono_id = '" . $condicionTriggerPosterior . "' AND usuario_bono.bono_id ='" . $bonoElegido . "' AND bono_interno.bono_id ='" . $bonoElegido . "'  AND bono_interno.bono_id ='" . $bonoElegido . "'";


                            $contSql = $contSql + 1;
                            $strSql[$contSql] = "UPDATE bono_interno SET bono_interno.cupo_actual =bono_interno.cupo_actual + " . $valor_bono . " WHERE bono_interno.bono_id ='" . $bonoElegido . "'";

                        } else {
                            if ($estadoBono == 'A') {
                                $fechaExpiracion = '';

                                if ($expFecha != '') {
                                    $fechaExpiracion = date('Y-m-d H:i:s', strtotime($expFecha));

                                }
                                if ($expDia != '') {
                                    $fechaExpiracion = date('Y-m-d H:i:s', strtotime(' + ' . $expDia . ' days'));

                                }
                                $contSql = $contSql + 1;
                                $strSql[$contSql] = "insert into usuario_bono (usuario_id,bono_id,valor,valor_bono,valor_base,fecha_crea,estado,error_id,externo_id,mandante,rollower_requerido,usucrea_id,usumodif_id,fecha_expiracion) SELECT " . $usuarioId . "," . $bonoElegido . "," . $valor_bono . "," . $valorbono . " ," . $valorBase . " ,'" . date('Y-m-d H:i:s') . "','" . $estadoBono . "','0',0," . $mandante . ", $rollowerRequerido,0,0,'" . $fechaExpiracion . "' FROM bono_interno WHERE  bono_id ='" . $bonoElegido . "'";

                            } else {

                                $contSql = $contSql + 1;
                                $strSql[$contSql] = "insert into usuario_bono (usuario_id,bono_id,valor,valor_bono,valor_base,fecha_crea,estado,error_id,externo_id,mandante,rollower_requerido,usucrea_id,usumodif_id) SELECT " . $usuarioId . "," . $bonoElegido . "," . $valor_bono . "," . $valorbono . " ," . $valorBase . " ,'" . date('Y-m-d H:i:s') . "','" . $estadoBono . "','0',0," . $mandante . ", $rollowerRequerido,0,0 FROM bono_interno WHERE  bono_id ='" . $bonoElegido . "'";

                            }


                            $contSql = $contSql + 1;
                            $strSql[$contSql] = "UPDATE bono_interno SET bono_interno.cupo_actual =bono_interno.cupo_actual + " . $valor_bono . ",bono_interno.cantidad_bonos=bono_interno.cantidad_bonos+1 WHERE bono_interno.bono_id ='" . $bonoElegido . "'";
                        }

                        if ($transaccion != "") {


                            foreach ($strSql as $val) {

                                $resp = $this->execUpdate($transaccion, $val);

                                if ($SumoSaldo && (strpos($val, 'insert into usuario_bono') !== false)) {
                                    $last_insert_id = $resp;
                                    $tibodebono = 'F';

                                    if ($tipoBono == 2) {
                                        $tibodebono = 'D';


                                    }

                                    if ($tipoBono == 3) {
                                        $tibodebono = 'ND';

                                    }


                                    if ($last_insert_id != "" && is_numeric($last_insert_id) && $valor_bono != 0) {

                                        $sql2 = "insert into bono_log (usuario_id,tipo,valor,estado,id_externo,mandante,transaccion_id,tipobono_id,fecha_crea) values (" . $usuarioId . ",'" . $tibodebono . "','" . $valor_bono . "','L','" . $last_insert_id . "','0',0,4,'" . date('Y-m-d H:i:s') . "')";
                                        $resp2 = $this->execUpdate($transaccion, $sql2);

                                        //Insertamos registros en la tabla  usuario_historial,con los filtros:
                                        // Filas donde "usuario_bono.estado" es 'R'
                                        // Filas donde "registro.usuario_id"  es la variable $usuarioId
                                        // Filas donde "usuario_bono.bono_id" es la variable $bonoElegido
                                        // Ademas de la unión de usuario_bono con registro  donde registro.usuario_id = usuario_bono.usuario_id
                                        //
                                        //Las actalizaciones se haran en las columnas
                                        //   usuario_id, descripcion, movimiento, usucrea_id, usumodif_id, tipo, valor, externo_id, creditos, creditos_base
                                        //
                                        // Se harán de la siguiente manera
                                        // usuario_id
                                        //   Seleccionamos "usuario_bono.usuario_id" de la tabla "usuario_bono"
                                        // descripcion
                                        //   Valor vacio: ' '
                                        // movimiento
                                        //   Establecemos valor 'E'
                                        // usucrea_id
                                        //   Establecemos valor '0'
                                        // usumodif_id
                                        //   Establecemos valor '0'
                                        // tipo
                                        //   Establecemos valor '50'
                                        // valor
                                        //   Seleccionamos "valor" de la tabla "usuario_bono"
                                        // externo_id
                                        //   Seleccionamos "usubono_id" de la tabla "usuario_bono".
                                        // creditos
                                        //   Seleccionamos "creditos" de la tabla "registro".
                                        // creditos_base
                                        //   Seleccionamos "creditos_base" de la tabla "registro".


                                        $sql2 = "insert into usuario_historial (usuario_id, descripcion, movimiento,usucrea_id,usumodif_id, tipo, valor, externo_id,creditos,creditos_base) SELECT usuario_bono.usuario_id,'','E',0,0,50,usuario_bono.valor,usuario_bono.usubono_id,creditos,creditos_base FROM usuario_bono INNER JOIN registro ON (registro.usuario_id = usuario_bono.usuario_id) WHERE usuario_bono.estado='R' and registro.usuario_id='" . $usuarioId . "' AND usuario_bono.bono_id ='" . $bonoElegido . "'";
                                        //Ejecutamos la SQL
                                        $resp2 = $this->execUpdate($transaccion, $sql2);

                                    }

                                }


                            }
                            if ($condicionTriggerPosterior > 0 && $SumoSaldo) {
                                $tibodebono = 'F';

                                if ($tipoBono == 2) {
                                    $tibodebono = 'D';

                                }

                                if ($tipoBono == 3) {
                                    $tibodebono = 'ND';

                                }


                                $sql2 = "insert into bono_log (usuario_id,tipo,valor,estado,id_externo,mandante,transaccion_id,tipobono_id,fecha_crea) values (" . $usuarioId . ",'" . $tibodebono . "','" . $valor_bono . "','L','" . $condicionTriggerPosterior . "','0',0,4,'" . date('Y-m-d H:i:s') . "')";
                                $resp2 = $this->execUpdate($transaccion, $sql2);

                                //Insertamos registros en la tabla  usuario_historial,con los filtros:
                                // Filas donde "usuario_bono.estado" es 'R'
                                // Filas donde "registro.usuario_id"  es la variable $usuarioId
                                // Filas donde "usuario_bono.bono_id" es la variable $bonoElegido
                                // Ademas de la unión de usuario_bono con registro  donde registro.usuario_id = usuario_bono.usuario_id
                                //
                                //Las actalizaciones se haran en las columnas
                                //   usuario_id, descripcion, movimiento, usucrea_id, usumodif_id, tipo, valor, externo_id, creditos, creditos_base
                                //
                                // Se harán de la siguiente manera
                                // usuario_id
                                //   Seleccionamos "usuario_bono.usuario_id" de la tabla "usuario_bono"
                                // descripcion
                                //   Valor vacio: ' '
                                // movimiento
                                //   Establecemos valor 'E'
                                // usucrea_id
                                //   Establecemos valor '0'
                                // usumodif_id
                                //   Establecemos valor '0'
                                // tipo
                                //   Establecemos valor '50'
                                // valor
                                //   Seleccionamos "valor" de la tabla "usuario_bono"
                                // externo_id
                                //   Seleccionamos "usubono_id" de la tabla "usuario_bono".
                                // creditos
                                //   Seleccionamos "creditos" de la tabla "registro".
                                // creditos_base
                                //   Seleccionamos "creditos_base" de la tabla "registro".


                                $sql2 = "insert into usuario_historial (usuario_id, descripcion, movimiento,usucrea_id,usumodif_id, tipo, valor, externo_id,creditos,creditos_base) SELECT usuario_bono.usuario_id,'','E',0,0,50,usuario_bono.valor,usuario_bono.usubono_id,creditos,creditos_base FROM usuario_bono INNER JOIN registro ON (registro.usuario_id = usuario_bono.usuario_id) WHERE usuario_bono.estado='R' and registro.usuario_id='" . $usuarioId . "' AND usuario_bono.bono_id ='" . $bonoElegido . "'";
                                //Ejecutamos la SQL
                                $resp2 = $this->execUpdate($transaccion, $sql2);

                            }


                        }


                        // $contSql = $contSql + 1;
                        // $strSql[$contSql] = "insert into bono_log (usuario_id,tipo,valor,estado,id_externo,mandante,transaccion_id,tipobono_id) values (" . $usuarioId . ",'" . $tipoBonoS . "','" . $valor_bono  . "','L','0'," . $mandante . ",0,4)";


                        $respuesta["WinBonus"] = true;
                        $ganoBonoBool = true;
                        $respuesta["SumoSaldo"] = $SumoSaldo;
                        $respuesta["Bono"] = $bonoElegido;
                        $respuesta["Valor"] = $valor_bono;
                        $respuesta["queries"] = $strSql;
                    }

                    if ($tipoBono == 3) {


                        $resp = $this->agregarBonoFree($bonoElegido, $usuarioId, $mandante, $detalles, '', '', $transaccion);

                        if ($transaccion == '') {
                            foreach ($resp->queries as $val) {
                                $contSql = $contSql + 1;
                                $strSql[$contSql] = $val;
                            }
                        }
                    }

                    if ($tipoBono == 6) {


                        $resp = $this->agregarBonoFree($bonoElegido, $usuarioId, $mandante, $detalles, '', '', $transaccion);

                        if ($transaccion == '') {
                            foreach ($resp->queries as $val) {
                                $contSql = $contSql + 1;
                                $strSql[$contSql] = $val;
                            }
                        }
                        return $resp;


                    }
                }
            }

        }


        try {
            if ($respuesta != null && $respuesta["WinBonus"] == true) {
                if ($bonosUsuarioDisponibles != null && is_array($bonosUsuarioDisponibles)) {

                    foreach ($bonosUsuarioDisponibles as $bonosUsuarioDisponible) {
                        $sqlUpdate = "UPDATE usuario_bono SET estado='R'  WHERE usubono_id='" . $bonosUsuarioDisponible->usubono_id . "' AND estado='P' ";

                        $bonosUsuarioDisponiblesUpdate = $this->execQuery($transaccion, $sqlUpdate);

                    }
                }
            }
        } catch (Exception $e) {
        }


        return json_decode(json_encode($respuesta));

    }


    /**
     * Propósito: Agregar un bono
     *
     *  Descripción de variables:
     *
     *           - $tipoBono: string  tipo de bono 2 (Deposito),3 (No deposito),5 (FreeCasino),6(FreeBet),8 (FreeSpin)
     *           - $usuarioId: string Identidad del usuario a quien pertenece el bono
     *           - $mandante: string Partner
     *           - $detalles: array con los detalles del bono
     *           - $transaccion: string transaccion relacionada a la seguridad del proceso
     *           - $isForCRM = Si el bono es para CRM false, bool
     *
     * @param String $tipoBono
     * @param String $usuarioId
     * @param String $mandante
     * @param String $detalles
     * @param String $transaccion
     *
     * @return Array resultado de la consulta
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function agregarBono($tipoBono, $usuarioId, $mandante, $detalles, $transaccion, $isForCRM = false)
    {
        $Usuario = new Usuario($usuarioId);
        $Subproveedor = new Subproveedor("", "ITN");
        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Subproveedor->subproveedorId, $mandante, $Usuario->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());
        $urlAltenar = $Credentials->URL2;
        $walletCode = $Credentials->WALLET_CODE;

        /** Verificacion de estado de usuario para redencion de bono */
        // Se verifica si el usuario tiene activa la contingencia abusador de bonos
        $UsuarioConfiguracion = new UsuarioConfiguracion();
        $UsuarioConfiguracion = $UsuarioConfiguracion->getUserBondABuser($usuarioId);
        if ($UsuarioConfiguracion->usuconfigId != '' && $UsuarioConfiguracion->usuconfigId != null) {
            $bloqueado = true;
        } else {
            $bloqueado = false;
        }

        $respuesta = array();

        //Verificamos Detalles a tomar en cuenta al momento de verificar si puede ganar un bono
        $detalleDepositos = $detalles->Depositos;
        $detalleValorDeposito = $detalles->ValorDeposito;

        $CodePromo = $detalles->CodePromo;

        $detallePaisUSER = $detalles->PaisUSER;
        $detalleMonedaUSER = $detalles->MonedaUSER;

        $cumpleCondiciones = false;
        $bonoElegido = 0;
        $bonoTieneRollower = false;
        $rollowerBono = 0;
        $rollowerDeposito = 0;

        $sqlIsForCRM = "  AND (a.pertenece_crm = '' or a.pertenece_crm IS NULL  or a.pertenece_crm ='N' ) ";
        if ($isForCRM) {
            $sqlIsForCRM = "  AND a.pertenece_crm = 'S' ";

        }

        //Obtenemos todos los bonos disponibles
        ////Seleccionamos a.bono_id bono_id,a.tipo,a.fecha_inicio,a.fecha_fin,now() test, a.permite_bono permite_bonos de la tabla bono_interno donde
        //  La mandante debe ser igual a la variable $mandante
        //  Filtramos los registros donde la fecha y hora actual (NOW()) están entre fecha_inicio y fecha_fin.
        //  El estado debe ser igual a 'A'
        //  bono_id debe ser igual a la variable $bono_id
        //  Ordenamos los resultados primero por "orden" en descendente y luego por "fecha_crea" en ascendente

        $sqlBonos = "select a.bono_id bono_id,a.tipo,a.fecha_inicio,a.fecha_fin,now() test, a.permite_bono permite_bonos from bono_interno a where a.mandante=" . $mandante . "  and now()  between (a.fecha_inicio) and (a.fecha_fin) and a.estado='A' ORDER BY a.orden DESC,a.fecha_crea ASC ";


        if ($CodePromo != "") {
            ////Seleccionamos a.bono_id ,a.tipo,a.fecha_inicio,a.fecha_fin, a.permite_bono permite_bonos de la tabla bono_interno donde:
            //  La mandante debe ser igual a la variable $mandante
            //  Filtramos los registros donde la fecha y hora actual (NOW()) están entre fecha_inicio y fecha_fin.
            //  El estado debe ser igual a 'A'
            // Adem{as se hace un inner join con bono_detalle donde bono_id deben ser iguales en ambas, tipo = CODEPROMO y valor = a la variable $CodePromo
            //  Ordenamos los resultados primero por "orden" en descendente y luego por "fecha_crea" en ascendente
            $sqlBonos = "select a.bono_id,a.tipo,a.fecha_inicio,a.fecha_fin, a.permite_bono permite_bonos from bono_interno a INNER JOIN bono_detalle b ON (a.bono_id=b.bono_id AND b.tipo='CODEPROMO' AND b.valor='" . $CodePromo . "') where a.mandante=" . $mandante . "  and now()  between (a.fecha_inicio) and (a.fecha_fin) and a.estado='A'  ORDER BY a.orden DESC,a.fecha_crea ASC ";

        }
        $respuesta["sql"] = $sqlBonos;

        //Ejecutamos la SQL
        $bonosDisponibles = $this->execQuery($transaccion, $sqlBonos);

        $continue = true;

        //Recorremos la tabla "bono_interno" donde se alojan los bonos disponibles y almacenamos variables a
        //tener en cuenta
        foreach ($bonosDisponibles as $bono) {
            $bono->bono_id = $bono->{'a.bono_id'};
            $bono->tipo = $bono->{'a.tipo'};
            $bono->fecha_inicio = $bono->{'a.fecha_inicio'};
            $bono->fecha_fin = $bono->{'a.fecha_fin'};
            $PermiteBonos = $bono->{'a.permite_bonos'};
            $pertenece_crm = $bono->{'a.pertenece_crm'};

            //Verificamos condiciones para seguir iterando
            if ((!$cumpleCondiciones && ($tipoBono == $bono->tipo || $CodePromo != "")) || ($cumpleCondiciones && $continue && ($tipoBono == $bono->tipo || $CodePromo != ""))) {
                //Obtenemos todos los detalles del bono
                //Seleccionamos todos dentro de la tabla bono_detalle, filtrando por bono_id debe ser igual a $bono->bono_id (bono_interno)
                // y moneda debe estar vacio o valer lo que vale la variable $detalleMonedaUser
                $sqlDetalleBono = "select * from bono_detalle a where a.bono_id='" . $bono->bono_id . "' AND (moneda='' OR moneda='" . $detalleMonedaUSER . "') ";
                $bonoDetalles = $this->execQuery($transaccion, $sqlDetalleBono);

                //Inicializamos variables
                $cumpleCondiciones = true;
                $ganaBonoId = 0;
                $maximopago = 0;
                $valorbono = 0;
                $tipobono = "";
                $bonoTieneRollower = false;
                $tiposaldo = -1;


                $bonusPlanIdAltenar = '';
                $bonusCodeAltenar = '';

                //Verificamos si $tipoBono es diferente a tipo bono de la tabla bono_interno y codePromo esta vacio
                if ($tipoBono != $bono->tipo && $CodePromo == "") {
                    //negamos el cumplecondiciones
                    $cumpleCondiciones = false;

                }
                //Verificamos si codePromo esta vacio y tipoBono esta vacio
                if ($CodePromo != "" && $tipoBono == '') {
                    //igualamos tipoBono a tipo bono de la tabla bono_interno
                    $tipoBono = $bono->tipo;

                }

                //Inicializamos variables
                $expDia = '';
                $expFecha = '';

                //Verificamos si CRM no esta activo
                if ($pertenece_crm == 'S' && !$isForCRM) {

                    //Seleccionamos usubono_id de la tabla usuario_bono  donde
                    // el bono_id debe ser el $bono->bono_id de la tabla bono_interno
                    // usuario_id debe ser la variable $usuarioId
                    // y el estado debe ser 'P'
                    $sqlUsuarioBonos = "SELECT a.usubono_id FROM usuario_bono a WHERE a.bono_id='" . $bono->bono_id . "' AND a.usuario_id='" . $usuarioId . "' AND a.estado='P'";

                    //Guardamos la sql
                    $respuesta["sql"] = $sqlBonos;
                    //Ejecutamos la SQL
                    $bonosUsuarioDisponibles = $this->execQuery($transaccion, $sqlUsuarioBonos);

                    //Verificamos si $bonosUsuarioDisponibles esta vacia
                    if (count($bonosUsuarioDisponibles) == 0) {
                        break;
                    }

                }

                // Uso de la nueva funcionalidad

                $detalles = json_decode(json_encode($detalles));
                $validate = $this->validarCondiciones($bonoDetalles, $detalles, $tipoProducto = '', $usuarioId, $isForLealtad = false, $cumpleCondiciones, $transaccion, $bono, $tipoBono, $extra = true);
                //Variables usadas en el codigo y que provee Validar condiciones
                $valorbono = $validate->valorbono;
                $tipobono = $validate->tipobono;
                $condicionTriggerPosterior = $validate->condicionTriggerPosterior;
                $cumpleCondiciones = $validate->cumpleCondiciones;
                $maximopago = $validate->maximopago;
                $expDia = $validate->expDia;
                $expFecha = $validate->expFecha;
                $bonoTieneRollower = $validate->bonoTieneRollower;
                $rollowerBono = $validate->rollowerBono;
                $rollowerDeposito = $validate->rollowerDeposito;
                $rollowerValor = $validate->rollowerValor;
                $puederepetirBono = $validate->puederepetirBono;
                $ganaBonoId = $validate->ganaBonoId;
                $tiposaldo = $validate->tiposaldo;
                $valor_bono = $validate->valor_bono;
                $bonusPlanIdAltenar = $validate->bonusPlanIdAltenar;
                $bonusCodeAltenar = $validate->bonusCodeAltenar;

                //Verificamos que cumple condiciones
                if ($cumpleCondiciones) {

                    // Si condicion es cero
                    if ($condicionTriggerPosterior == 0) {
                        // si esta activa puederepetirbono
                        if ($puederepetirBono) {
                            //Guardamos en bonoElegido el bono_id en tabla bono_interno
                            $bonoElegido = $bono->bono_id;

                        } else {

                            //Hacemos un select de todos desde la tabla usuario_bono
                            //donde bono_id coincide con el valor de bono_id de la tabla bono_interno
                            // y usuario_id sea igual a la variable $usuarioId
                            $sqlRepiteBono = "select * from usuario_bono a where a.bono_id='" . $bono->bono_id . "' AND a.usuario_id = '" . $usuarioId . "'";
                            //Ejecutamos SQL
                            $repiteBono = $this->execQuery($transaccion, $sqlRepiteBono);

                            //Si no puede repetir bono y repitebono esta vacio
                            if ((!$puederepetirBono && oldCount($repiteBono) == 0)) {
                                //Guardamos ne bonoElegido lo que hay en bono_id de la tabla bono_interno
                                $bonoElegido = $bono->bono_id;
                            } else {
                                //negamos cumple condiciones
                                $cumpleCondiciones = false;
                            }

                        }
                    } else {
                        //Guardamos ne bonoElegido lo que hay en bono_id de la tabla bono_interno
                        $bonoElegido = $bono->bono_id;
                    }


                }

                if ($cumpleCondiciones) {

                    //Si transaccion esta vacia
                    if ($transaccion != '') {
                        //Determinamos el valor del bono base en porcentaje/valor si el bono es de tipo PORCENTAJE
                        if ($tipobono == "PORCENTAJE") {

                            $valor_bono = ($detalleValorDeposito) * ($valorbono) / 100;

                            //Evitamos desborde bono
                            if ($valor_bono > $maximopago) {
                                $valor_bono = $maximopago;
                            }
                        } elseif ($tipobono == "VALOR") {

                            // Guardamos el valor del bono entero
                            $valor_bono = $valorbono;

                        }
                    }

                }


                if ($cumpleCondiciones) {
                    if ($PermiteBonos == 1) {
                        $continue = true;
                    } else {
                        $continue = false;
                    }
                }
            }

        }

        // Inicializamos variables del array respuesta
        $respuesta["Bono"] = 0;
        $respuesta["WinBonus"] = false;

        //Si el bonoElegido existe y el tipo de bono existe
        if ($bonoElegido != 0 && $tipobono != "") {

            // si bonusPlanIdAltenar y bonusCodeAltenar no estan vacios comenzamos petición de redención con Altenar
            if ($bonusPlanIdAltenar != '' && $bonusCodeAltenar != '') {

                /** Si el usuario se encuentra bloqueado por abusador de bonos se bloquea la redencion del bono */
                if ($bloqueado) {
                    $BonoInterno = new BonoInterno();
                    $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                    VALUES ('{$usuarioId}','BONO','{$bono->bono_id}','ALTENAR','{$tipobono}','BONDABUSER')";
                    $BonoInterno->execQuery($transaccion, $sqlLog);
                } /** Flujo normal bonos de altenar*/
                else {
                    if (($mandante == '17' || $mandante == '0' || $mandante == '19' || $mandante == '8' || $mandante == '13' || $mandante != '14') || ($mandante == '14' && $bonoElegido != '32289')) {

                        // Guardamos variables
                        $valorBase = $detalleValorDeposito;

                        $strSql = array();
                        $contSql = 0;
                        $estadoBono = 'A';
                        $rollowerRequerido = 0;
                        $SumoSaldo = false;

                        try {
                            //Determinamos el valor del bono base en porcentaje/valor si el bono es de tipo PORCENTAJE
                            if ($tipobono == "PORCENTAJE") {

                                $valor_bono = ($detalleValorDeposito) * ($valorbono) / 100;
                                //Evitamos desborde bono
                                if ($maximopago != 0 && $maximopago != '' && $valor_bono > $maximopago) {
                                    $valor_bono = $maximopago;
                                }
                            } elseif ($tipobono == "VALOR") {

                                $valor_bono = $valorbono;

                            }
                            // Definimos walletCode por Pais de usuario si el mandante  es 0
                            $Mandante = new Mandante($mandante);

                            if ($mandante == '0' && $detallePaisUSER == 60) {
                                $walletCode = "160124";
                            }

                            if ($mandante == '0' && $detallePaisUSER == 2) {
                                $walletCode = "160124";
                            }

                            $IdUsuarioAltenar = $usuarioId;
                            if ((intval($usuarioId) > 73758) || (in_array(intval($usuarioId), array(70363, 72098, 67393, 73497, 57196)))) {
                                $IdUsuarioAltenar = $usuarioId . "U";
                            }
                            // Guardamos cambios en dataD

                            $dataD = array(
                                "ExtUserId" => $IdUsuarioAltenar,
                                "WalletCode" => $walletCode,
                                "BonusPlanId" => $bonusPlanIdAltenar,
                                "Deposit" => intval(floatval($valor_bono) * 100)
                            );

                            $dataD = json_encode($dataD);

                            $urlAPI = $urlAltenar . '/api/Bonus/CreateBonusByDeposit/json';

                            // Inicializar la clase CurlWrapper
                            $curl = new CurlWrapper($urlAPI);

                            // Configurar opciones
                            $curl->setOptionsArray([
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_ENCODING => '',
                                CURLOPT_MAXREDIRS => 10,
                                CURLOPT_TIMEOUT => 300,
                                CURLOPT_FOLLOWLOCATION => true,
                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                CURLOPT_CUSTOMREQUEST => 'POST',
                                CURLOPT_POSTFIELDS => $dataD,
                                CURLOPT_HTTPHEADER => array(
                                    'Content-Type: application/json'
                                ),
                            ]);

                            // Ejecutar la solicitud
                            $response = $curl->execute();


                            //Guardamos la respuesta decodificada
                            $response = json_decode($response);

                            //si la respuesta no viene vacia
                            if ($response != null) {

                                //Verificamos respuesta de la ejecución, si la respuesta CreateBonusByCodeMessageResult está vacia
                                if ($response->CreateBonusByDepositMessageResult != null) {
                                    //Y si hay error por cliente no existente
                                    if ($response->CreateBonusByDepositMessageResult->Error == 'ClientNotFound') {
                                        //Creamos nuevo usuario con sus parametros
                                        $Usuario = new Usuario($usuarioId);
                                        $Registro = new Registro('', $usuarioId);
                                        $Pais = new Pais($Usuario->paisId);

                                        $Mandante = new Mandante($Usuario->mandante);
                                        $pathPartner = $Mandante->pathItainment;
                                        $pathFixed = $Pais->codigoPath;
                                        $usermoneda = $Usuario->moneda;
                                        $userpath = $pathFixed;

                                        $pathFixed = '2:Web ' . $usermoneda . ',' . $userpath;

                                        //Configuramos la mandante
                                        if ($Mandante->mandante != '') {
                                            if (is_numeric($Mandante->mandante)) {
                                                if (intval($Mandante->mandante) > 2) {

                                                    $pathFixed = '2:Web' . $Mandante->mandante . ' ' . $usermoneda . ',' . $Mandante->mandante . $userpath;
                                                    if (intval($Mandante->mandante) == 9) {

                                                        $pathFixed = '2:Web' . $Mandante->mandante . ' ' . $usermoneda . ',' . $Mandante->mandante . 'W' . $userpath;

                                                    }

                                                }
                                            }
                                        }
                                        //Configuramos Partner si esta vacio
                                        if ($pathPartner == '') {

                                            $pathPartner = "1:colombia,S3";

                                            //Configuramos Partner por mandante
                                            if ($Mandante->mandante == 1) {
                                                $pathPartner = "1:ibet,S1";
                                            }


                                            if ($Mandante->mandante == 2) {
                                                $pathPartner = "1:justbetja,S2";
                                            }


                                            if ($Mandante->mandante == 3) {
                                                $pathPartner = "1:miravalle,S7";
                                            }


                                            if ($Mandante->mandante == 4) {
                                                $pathPartner = "1:casinogranpalacio,S20";
                                            }


                                            if ($Mandante->mandante == 5) {
                                                $pathPartner = "1:casinointercontinental,S9";
                                            }


                                            if ($Mandante->mandante == 6) {
                                                $pathPartner = "1:netabet,S10";
                                            }


                                            if ($Mandante->mandante == 7) {
                                                $pathPartner = "1:casinoastoria,S11";
                                            }


                                            if ($Mandante->mandante == 8) {
                                                $pathPartner = "1:ecuabet,S12";
                                            }

                                            if ($Mandante->mandante == 9) {
                                                $pathPartner = "1:winbet,S13";
                                            }


                                            if ($Mandante->mandante == 0 && $Usuario->paisId == '60') {
                                                $pathPartner = "1:doradobet,S0-60";
                                            }

                                            if ($Mandante->mandante == '0') {
                                                $pathPartner = "1:doradobet,S" . $Mandante->mandante . "-" . $Usuario->paisId;
                                            }
                                            if ($Mandante->mandante == '8') {
                                                $pathPartner = "1:ecuabet,S" . $Mandante->mandante;
                                            }


                                        }


                                        if ($Mandante->mandante == '12') {
                                            $pathPartner = "1:powerbet,S16-" . $Usuario->paisId;
                                        }


                                        if ($Mandante->mandante == '18') {
                                            $pathPartner = "1:gangabet,S22-" . $Usuario->paisId;
                                            if ($Usuario->paisId == '173') {
                                                $pathPartner = "1:gangabet,S22-" . $Usuario->paisId;
                                            }
                                        }

                                        if ($mandante == '0' && $detallePaisUSER == 60) {
                                            $walletCode = "160124";
                                        }

                                        if ($mandante == '0' && $detallePaisUSER == 2) {
                                            $walletCode = "160124";
                                        }

                                        //Filtramos por id Usuario capturado en CreateBonus
                                        $IdUsuarioAltenar = $Usuario->usuarioId;
                                        if ((intval($usuarioId) > 73758) || (in_array(intval($usuarioId), array(70363, 72098, 67393, 73497, 57196)))) {
                                            $IdUsuarioAltenar = $Usuario->usuarioId . "U";
                                        }
                                        //Almacenamos los cambios en array dataD
                                        $dataD = array(
                                            "ExtUser" => array(
                                                "LoginName" => $Usuario->nombre,
                                                "Currency" => $Usuario->moneda,
                                                "Country" => $Pais->iso,
                                                "ExternalUserId" => $IdUsuarioAltenar,
                                                "AffiliationPath" => $Usuario->getAffiliationPathAltenar(),
                                                "UserCode" => "3",
                                                "FirstName" => $Registro->nombre1,
                                                "LastName" => $Registro->apellido1,
                                                "UserBalance" => "0"),
                                            "WalletCode" => $walletCode
                                        );

                                        $dataD = json_encode($dataD);


                                        // Inicializar la clase CurlWrapper
                                        $curl = new CurlWrapper($urlAltenar . '/api/Bonus/CreateUser/json');

                                        // Configurar opciones
                                        $curl->setOptionsArray([
                                            CURLOPT_RETURNTRANSFER => true,
                                            CURLOPT_ENCODING => '',
                                            CURLOPT_MAXREDIRS => 10,
                                            CURLOPT_TIMEOUT => 300,
                                            CURLOPT_FOLLOWLOCATION => true,
                                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                            CURLOPT_CUSTOMREQUEST => 'POST',
                                            CURLOPT_POSTFIELDS => $dataD,
                                            CURLOPT_HTTPHEADER => array(
                                                'Content-Type: application/json'
                                            ),
                                        ]);

                                        // Ejecutar la solicitud
                                        $response = $curl->execute();
                                        sleep(1);

                                        $IdUsuarioAltenar = $usuarioId;
                                        if ((intval($usuarioId) > 73758) || (in_array(intval($usuarioId), array(70363, 72098, 67393, 73497, 57196)))) {
                                            $IdUsuarioAltenar = $usuarioId . "U";
                                        }

                                        //Almacenamos datos en array dataD
                                        $dataD = array(
                                            "ExtUserId" => $IdUsuarioAltenar,
                                            "WalletCode" => $walletCode,
                                            "BonusPlanId" => $bonusPlanIdAltenar,
                                            "Deposit" => intval(floatval($valor_bono) * 100)
                                        );

                                        $dataD = json_encode($dataD);


                                        // Inicializar la clase CurlWrapper
                                        $curl = new CurlWrapper($urlAPI);

                                        // Configurar opciones
                                        $curl->setOptionsArray([
                                            CURLOPT_URL => $urlAPI,
                                            CURLOPT_RETURNTRANSFER => true,
                                            CURLOPT_ENCODING => '',
                                            CURLOPT_MAXREDIRS => 10,
                                            CURLOPT_TIMEOUT => 300,
                                            CURLOPT_FOLLOWLOCATION => true,
                                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                            CURLOPT_CUSTOMREQUEST => 'POST',
                                            CURLOPT_POSTFIELDS => $dataD,
                                            CURLOPT_HTTPHEADER => array(
                                                'Content-Type: application/json'
                                            ),
                                        ]);

                                        // Ejecutar la solicitud
                                        $response = $curl->execute();

                                        //Guardamos la respuesta decodificada
                                        $response = json_decode($response);

                                        if($response->CreateBonusByDepositMessageResult->Error == 'NoError'){

                                            //Guardamos confirmación de bono ganado con su valor y demas variables a usar
                                            $respuesta["WinBonus"] = true;

                                        }

                                    }else if($response->CreateBonusByDepositMessageResult->Error == 'NoError'){

                                        //Guardamos confirmación de bono ganado con su valor y demas variables a usar
                                        $respuesta["WinBonus"] = true;

                                    }
                                }

                            }

                        } catch (Exception $e) {

                        }

                        //Guardamos confirmación de bono ganado con su valor y demas variables a usar
                        $respuesta["SumoSaldo"] = $SumoSaldo;
                        $respuesta["Bono"] = $bonoElegido;
                        $respuesta["Valor"] = $valor_bono;
                        $respuesta["queries"] = $strSql;

                        try {
                            //Enviamos mensaje/notificacion al usuario
                            if ($mandante == 19) {
                                //Configuramos el mensaje para el bono asociado a una campaña o no
                                if ($respuesta['Bono']) {
                                    $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
                                    $BonodetalleM = $BonoDetalleMySqlDAO->querybyBonoIdAndTipo($respuesta['Bono'], 'MARKETINGCAMPAING');
                                    if ($BonodetalleM) {
                                        $UsuarioMandante = new UsuarioMandante('', $usuarioId, $mandante);
                                        //Recorremos la consulta y configuramos parametros a mostrar en mensaje/notificación
                                        foreach ($BonodetalleM as $mensaje) {
                                            $Campaing = new UsuarioMensajecampana($mensaje->valor);

                                            $UsuarioMensaje = new UsuarioMensaje();
                                            $UsuarioMensaje->body = $Campaing->body;
                                            $UsuarioMensaje->msubject = $Campaing->msubject;
                                            $UsuarioMensaje->usufromId = 0;
                                            $UsuarioMensaje->usutoId = $UsuarioMandante->usumandanteId;
                                            $UsuarioMensaje->isRead = 0;
                                            $UsuarioMensaje->msubject = $Campaing->nombre;
                                            $UsuarioMensaje->parentId = $mandante;
                                            $UsuarioMensaje->proveedorId = $mandante;
                                            $UsuarioMensaje->tipo = $Campaing->tipo;
                                            $UsuarioMensaje->paisId = $Campaing->paisId;
                                            $UsuarioMensaje->fechaExpiracion = $Campaing->fechaExpiracion;
                                            $UsuarioMensaje->usumencampanaId = $mensaje->valor;


                                            $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($transaccion);
                                            //Enviamos los parametros para el mensaje a la tabla usuario_mensaje
                                            $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);

                                        }
                                    }
                                }

                            }
                        } catch (Exception $e) {

                        }


                    }

                    if ((true) || ($mandante == '14' && $bonoElegido == '32289')) {

                        // Guardamos variables
                        $valorBase = $detalleValorDeposito;

                        $strSql = array();
                        $contSql = 0;
                        $estadoBono = 'A';
                        $rollowerRequerido = 0;
                        $SumoSaldo = false;

                        try {
                            //Determinamos el valor del bono base en porcentaje/valor si el bono es de tipo PORCENTAJE
                            if ($tipobono == "PORCENTAJE") {
                                //Evitamos desborde bono
                                $valor_bono = ($detalleValorDeposito) * ($valorbono) / 100;

                                if ($valor_bono > $maximopago) {
                                    $valor_bono = $maximopago;
                                }
                            } elseif ($tipobono == "VALOR") {
                                // Guardamos el valor del bono entero
                                $valor_bono = $valorbono;

                            }

                            // Definimos walletCode por Pais de usuario si el mandante  es 0
                            $Mandante = new Mandante($mandante);
                            
                            if ($mandante == '0' && $detallePaisUSER == 60) {
                                $walletCode = "160124";
                            }

                            if ($mandante == '0' && $detallePaisUSER == 2) {
                                $walletCode = "160124";
                            }

                            $IdUsuarioAltenar = $usuarioId;
                            if ((intval($usuarioId) > 73758) || (in_array(intval($usuarioId), array(70363, 72098, 67393, 73497, 57196)))) {
                                $IdUsuarioAltenar = $usuarioId . "U";
                            }
                            // Guardamos cambios en dataD
                            $dataD = array(
                                "ExtUserId" => $IdUsuarioAltenar,
                                "WalletCode" => $walletCode,
                                "BonusCode" => $bonusCodeAltenar,
                                "Deposit" => intval(floatval($valor_bono) * 100)
                            );

                            $dataD = json_encode($dataD);


                            // Inicializar la clase CurlWrapper
                            $curl = new CurlWrapper($urlAltenar . '/api/Bonus/CreateBonusByCode/json');

                            // Configurar opciones
                            $curl->setOptionsArray([
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_ENCODING => '',
                                CURLOPT_MAXREDIRS => 10,
                                CURLOPT_TIMEOUT => 300,
                                CURLOPT_FOLLOWLOCATION => true,
                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                CURLOPT_CUSTOMREQUEST => 'POST',
                                CURLOPT_POSTFIELDS => $dataD,
                                CURLOPT_HTTPHEADER => array(
                                    'Content-Type: application/json'
                                ),
                            ]);

                            // Ejecutar la solicitud
                            $response = $curl->execute();

                            //Verificamos respuesta de la ejecución, si la respuesta CreateBonusByCodeMessageResult está vacia
                            $response = json_decode($response);
                            if ($response->CreateBonusByCodeMessageResult != null) {
                                //Y si hay error por cliente no existente
                                if ($response->CreateBonusByCodeMessageResult->Error == 'ClientNotFound') {
                                    //Creamos nuevo usuario con sus parametros
                                    $Usuario = new Usuario($usuarioId);
                                    $Registro = new Registro('', $usuarioId);
                                    $Pais = new Pais($Usuario->paisId);

                                    $Mandante = new Mandante($Usuario->mandante);
                                    $pathPartner = $Mandante->pathItainment;
                                    $pathFixed = $Pais->codigoPath;
                                    $usermoneda = $Usuario->moneda;
                                    $userpath = $pathFixed;

                                    $pathFixed = '2:Web ' . $usermoneda . ',' . $userpath;

                                    //Configuramos la mandante
                                    if ($Mandante->mandante != '') {
                                        if (is_numeric($Mandante->mandante)) {
                                            if (intval($Mandante->mandante) > 2) {

                                                $pathFixed = '2:Web' . $Mandante->mandante . ' ' . $usermoneda . ',' . $Mandante->mandante . $userpath;
                                                if (intval($Mandante->mandante) == 9) {

                                                    $pathFixed = '2:Web' . $Mandante->mandante . ' ' . $usermoneda . ',' . $Mandante->mandante . 'W' . $userpath;

                                                }

                                            }
                                        }
                                    }
                                    //Configuramos Partner si esta vacio
                                    if ($pathPartner == '') {

                                        $pathPartner = "1:colombia,S3";

                                        //Configuramos Partner por mandante
                                        if ($Mandante->mandante == 1) {
                                            $pathPartner = "1:ibet,S1";
                                        }


                                        if ($Mandante->mandante == 2) {
                                            $pathPartner = "1:justbetja,S2";
                                        }


                                        if ($Mandante->mandante == 3) {
                                            $pathPartner = "1:miravalle,S7";
                                        }


                                        if ($Mandante->mandante == 4) {
                                            $pathPartner = "1:casinogranpalacio,S20";
                                        }


                                        if ($Mandante->mandante == 5) {
                                            $pathPartner = "1:casinointercontinental,S9";
                                        }


                                        if ($Mandante->mandante == 6) {
                                            $pathPartner = "1:netabet,S10";
                                        }


                                        if ($Mandante->mandante == 7) {
                                            $pathPartner = "1:casinoastoria,S11";
                                        }


                                        if ($Mandante->mandante == 8) {
                                            $pathPartner = "1:ecuabet,S12";
                                        }

                                        if ($Mandante->mandante == 9) {
                                            $pathPartner = "1:winbet,S13";
                                        }


                                        if ($Mandante->mandante == 0 && $Usuario->paisId == '60') {
                                            $pathPartner = "1:doradobet,S0-60";
                                        }

                                        if ($Mandante->mandante == '0') {
                                            $pathPartner = "1:doradobet,S" . $Mandante->mandante . "-" . $Usuario->paisId;
                                        }
                                        if ($Mandante->mandante == '8') {
                                            $pathPartner = "1:ecuabet,S" . $Mandante->mandante;
                                        }


                                    }


                                    if ($Mandante->mandante == '12') {
                                        $pathPartner = "1:powerbet,S16-" . $Usuario->paisId;
                                    }


                                    if ($Mandante->mandante == '18') {
                                        $pathPartner = "1:gangabet,S22-" . $Usuario->paisId;
                                        if ($Usuario->paisId == '173') {
                                            $pathPartner = "1:gangabet,S22-" . $Usuario->paisId;
                                        }
                                    }

                                    // Definimos walletCode por Pais de usuario si el mandante  es 0
                                    
                                    if ($mandante == '0' && $detallePaisUSER == 60) {
                                        $walletCode = "160124";
                                    }

                                    if ($mandante == '0' && $detallePaisUSER == 2) {
                                        $walletCode = "160124";
                                    }

                                    //Filtramos por id Usuario capturado en CreateBonus
                                    $IdUsuarioAltenar = $Usuario->usuarioId;
                                    if ((intval($usuarioId) > 73758) || (in_array(intval($usuarioId), array(70363, 72098, 67393, 73497, 57196)))) {
                                        $IdUsuarioAltenar = $Usuario->usuarioId . "U";
                                    }
                                    //Almacenamos los cambios en array dataD
                                    $dataD = array(
                                        "ExtUser" => array(
                                            "LoginName" => $Usuario->nombre,
                                            "Currency" => $Usuario->moneda,
                                            "Country" => $Pais->iso,
                                            "ExternalUserId" => $IdUsuarioAltenar,
                                            "AffiliationPath" => $Usuario->getAffiliationPathAltenar(),
                                            "UserCode" => "3",
                                            "FirstName" => $Registro->nombre1,
                                            "LastName" => $Registro->apellido1,
                                            "UserBalance" => "0"),
                                        "WalletCode" => $walletCode
                                    );

                                    $dataD = json_encode($dataD);


                                    // Inicializar la clase CurlWrapper
                                    $curl = new CurlWrapper($urlAltenar . '/api/Bonus/CreateUser/json');

                                    // Configurar opciones
                                    $curl->setOptionsArray([
                                        CURLOPT_RETURNTRANSFER => true,
                                        CURLOPT_ENCODING => '',
                                        CURLOPT_MAXREDIRS => 10,
                                        CURLOPT_TIMEOUT => 300,
                                        CURLOPT_FOLLOWLOCATION => true,
                                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                        CURLOPT_CUSTOMREQUEST => 'POST',
                                        CURLOPT_POSTFIELDS => $dataD,
                                        CURLOPT_HTTPHEADER => array(
                                            'Content-Type: application/json'
                                        ),
                                    ]);

                                    // Ejecutar la solicitud
                                    $response = $curl->execute();
                                    sleep(1);

                                    $IdUsuarioAltenar = $usuarioId;
                                    if ((intval($usuarioId) > 73758) || (in_array(intval($usuarioId), array(70363, 72098, 67393, 73497, 57196)))) {
                                        $IdUsuarioAltenar = $usuarioId . "U";
                                    }

                                    //Almacenamos datos en array dataD
                                    $dataD = array(
                                        "ExtUserId" => $IdUsuarioAltenar,
                                        "WalletCode" => $Mandante->mandante,
                                        "BonusCode" => $bonusCodeAltenar,
                                        "Deposit" => intval(floatval($valor_bono) * 100)
                                    );

                                    $dataD = json_encode($dataD);

                                    // Inicializar la clase CurlWrapper
                                    $curl = new CurlWrapper($urlAltenar . '/api/Bonus/CreateBonusByCode/json');

                                    // Configurar opciones
                                    $curl->setOptionsArray([
                                        CURLOPT_RETURNTRANSFER => true,
                                        CURLOPT_ENCODING => '',
                                        CURLOPT_MAXREDIRS => 10,
                                        CURLOPT_TIMEOUT => 300,
                                        CURLOPT_FOLLOWLOCATION => true,
                                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                        CURLOPT_CUSTOMREQUEST => 'POST',
                                        CURLOPT_POSTFIELDS => $dataD,
                                        CURLOPT_HTTPHEADER => array(
                                            'Content-Type: application/json'
                                        ),
                                    ]);

                                    // Ejecutar la solicitud
                                    $response = $curl->execute();

                                    //Guardamos la respuesta decodificada
                                    $response = json_decode($response);

                                    if($response->CreateBonusByCodeMessageResult->Error == 'NoError'){

                                        //Guardamos confirmación de bono ganado con su valor y demas variables a usar
                                        $respuesta["WinBonus"] = true;

                                    }

                                }else if($response->CreateBonusByCodeMessageResult->Error == 'NoError'){

                                    //Guardamos confirmación de bono ganado con su valor y demas variables a usar
                                    $respuesta["WinBonus"] = true;

                                }
                            }

                        } catch (Exception $e) {

                        }

                        //Guardamos confirmación de bono ganado con su valor y demas variables a usar
                        //$respuesta["WinBonus"] = true;

                        $respuesta["SumoSaldo"] = $SumoSaldo;
                        $respuesta["Bono"] = $bonoElegido;
                        $respuesta["Valor"] = $valor_bono;
                        $respuesta["queries"] = $strSql;

                        try {

                            //Enviamos mensaje/notificacion al usuario
                            if ($mandante == 19) {
                                //Configuramos el mensaje para el bono asociado a una campaña o no
                                if ($respuesta['Bono']) {
                                    $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
                                    $BonodetalleM = $BonoDetalleMySqlDAO->querybyBonoIdAndTipo($respuesta['Bono'], 'MARKETINGCAMPAING');
                                    if ($BonodetalleM) {
                                        $UsuarioMandante = new UsuarioMandante('', $usuarioId, $mandante);
                                        //Recorremos la consulta y configuramos parametros a mostrar en mensaje/notificación
                                        foreach ($BonodetalleM as $mensaje) {
                                            $Campaing = new UsuarioMensajecampana($mensaje->valor);

                                            $UsuarioMensaje = new UsuarioMensaje();
                                            $UsuarioMensaje->body = $Campaing->body;
                                            $UsuarioMensaje->msubject = $Campaing->msubject;
                                            $UsuarioMensaje->usufromId = 0;
                                            $UsuarioMensaje->usutoId = $UsuarioMandante->usumandanteId;
                                            $UsuarioMensaje->isRead = 0;
                                            $UsuarioMensaje->msubject = $Campaing->nombre;
                                            $UsuarioMensaje->parentId = $mandante;
                                            $UsuarioMensaje->proveedorId = $mandante;
                                            $UsuarioMensaje->tipo = $Campaing->tipo;
                                            $UsuarioMensaje->paisId = $Campaing->paisId;
                                            $UsuarioMensaje->fechaExpiracion = $Campaing->fechaExpiracion;
                                            $UsuarioMensaje->usumencampanaId = $mensaje->valor;


                                            $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($transaccion);
                                            //Enviamos los parametros para el mensaje a la tabla usuario_mensaje
                                            $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);

                                        }
                                    }
                                }

                            }
                        } catch (Exception $e) {

                        }


                    }

                    //Asignación bonos adicionales vinculados a bono de Altenar
                    if ($respuesta["WinBonus"] && !empty($ganaBonoId)) {
                        try {
                            $BonoInterno = new BonoInterno($ganaBonoId);

                            //Retirando data específica para el bono vinculado
                            $detalles->CodePromo = null;
                            $responseGanaBonoId = $this->agregarBonoFree($ganaBonoId, $usuarioId, $mandante, $detalles, false, null, $transaccion);
                        } catch (Exception $e) {
                        }
                    }
                }

            } else {

                // Si el tipo de bono es bono depósito
                if ($tipoBono == 2) {
                    /** Si el usuario se encuentra bloqueado por abusador de bonos se bloquea la redencion del bono */
                    if ($bloqueado) {
                        $BonoInterno = new BonoInterno();
                        $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                    VALUES ('{$usuarioId}','BONO','{$bono->bono_id}','DEPOSITO','{$tipobono}','BONDABUSER')";
                        $BonoInterno->execQuery($transaccion, $sqlLog);
                    } /** Flujo normal bonos Deposito */
                    else {
                        if ($tipobono == "PORCENTAJE") {

                            //Determinamos el valor del bono base en porcentaje/valor si el bono es de tipo PORCENTAJE
                            $valor_bono = ($detalleValorDeposito) * ($valorbono) / 100;
                            //Evitamos desborde bono
                            if ($valor_bono > $maximopago && $maximopago != '0') {
                                $valor_bono = $maximopago;
                            }

                        } elseif ($tipobono == "VALOR") {
                            // Guardamos el valor del bono entero
                            $valor_bono = $valorbono;

                        }

                        //Guardamos variables
                        $valorBase = $detalleValorDeposito;

                        $strSql = array();
                        $contSql = 0;
                        $estadoBono = 'A';
                        $rollowerRequerido = 0;
                        $SumoSaldo = false;

                        // si el bono no tiene rollower
                        if (!$bonoTieneRollower) {
                            //Si el codepromo no viene vacio y tipo de bono es deposito
                            if ($CodePromo != "" && $tipobono == 2) {
                                $estadoBono = 'P'; // Actualizamos estadobono

                                // si el bono tiene rollower
                            } else {
                                //si ganaBonoId es cero
                                if ($ganaBonoId == 0) {
                                    $tipoBonoS = 'D';// actualizamos variable

                                    //iteramos sobre tiposaldo de la columna "valor" de la tabla bono_detalle
                                    switch ($tiposaldo) {
                                        case 0:
                                            //Sumamos al contador y generamos consulta SQL
                                            $contSql = $contSql + 1;

                                            //Actualizamos la tabla registro y bono interno con los filtros:
                                            // Filas donde "registro.mandante" es $mandante
                                            // Filas donde "registro.usuario_id" es $usuarioId
                                            // Filas donde "bono_id" es $bonoElegido
                                            //Las actalizaciones son las siguientes
                                            //registro.creditos_base_ant: Se establece con el valor  registro.creditos_base.
                                            //registro.creditos_base = registro.creditos_base + " . $valor_bono . ": Incrementa el valor de registro.creditos_base sumando el valor de la variable  $valor_bono.

                                            $strSql[$contSql] = "update registro,bono_interno set registro.creditos_base_ant=registro.creditos_base,registro.creditos_base=registro.creditos_base+" . $valor_bono . " where registro.mandante=" . $mandante . " and registro.usuario_id=" . $usuarioId . " AND bono_id ='" . $bonoElegido . "'";
                                            //Actualizamos estadoBono
                                            $estadoBono = 'R';
                                            //Actualizamos sumoSaldo
                                            $SumoSaldo = true;

                                            //Sumamos al contador y generamos consulta SQL
                                            $contSql = $contSql + 1;

                                            //Insertamos registros en la tabla  usuario_historial,con los filtros:
                                            // Filas donde "usuario_bono.estado" es 'R'
                                            // Filas donde "registro.usuario_id"  es la variable $usuarioId
                                            // Filas donde "usuario_bono.bono_id" es la variable $bonoElegido
                                            // Ademas de la unión de usuario_bono con registro  donde registro.usuario_id = usuario_bono.usuario_id
                                            //
                                            //Las actalizaciones se haran en las columnas
                                            //   usuario_id, descripcion, movimiento, usucrea_id, usumodif_id, tipo, valor, externo_id, creditos, creditos_base
                                            //
                                            // Se harán de la siguiente manera
                                            // usuario_id
                                            //   Seleccionamos "usuario_bono.usuario_id" de la tabla "usuario_bono"
                                            // descripcion
                                            //   Valor vacio: ' '
                                            // movimiento
                                            //   Establecemos valor 'E'
                                            // usucrea_id
                                            //   Establecemos valor '0'
                                            // usumodif_id
                                            //   Establecemos valor '0'
                                            // tipo
                                            //   Establecemos valor '50'
                                            // valor
                                            //   Seleccionamos "valor" de la tabla "usuario_bono"
                                            // externo_id
                                            //   Seleccionamos "usubono_id" de la tabla "usuario_bono".
                                            // creditos
                                            //   Seleccionamos "creditos" de la tabla "registro".
                                            // creditos_base
                                            //   Seleccionamos "creditos_base" de la tabla "registro".


                                            $strSql[$contSql] = "insert into usuario_historial (usuario_id, descripcion, movimiento,usucrea_id,usumodif_id, tipo, valor, externo_id,creditos,creditos_base) SELECT usuario_bono.usuario_id,'','E',0,0,50,usuario_bono.valor,usuario_bono.usubono_id,creditos,creditos_base FROM usuario_bono INNER JOIN registro ON (registro.usuario_id = usuario_bono.usuario_id) WHERE usuario_bono.estado='R' and registro.usuario_id='" . $usuarioId . "' AND usuario_bono.bono_id ='" . $bonoElegido . "'";


                                            break;

                                        case 1:
                                            //Sumamos al contador y generamos consulta SQL
                                            $contSql = $contSql + 1;

                                            //Actualizamos la tabla registro y bono interno con los filtros:
                                            // Filas donde "registro.mandante" es $mandante
                                            // Filas donde "registro.usuario_id" es $usuarioId
                                            // Filas donde "bono_id" es $bonoElegido
                                            //Las actalizaciones son las siguientes
                                            //registro.creditos_ant: Se establece con el valor  registro.creditos.
                                            //registro.creditos = registro.creditos_base + " . $valor_bono . ": Incrementa el valor de registro.creditos sumando el valor de la variable  $valor_bono.

                                            $strSql[$contSql] = "update registro,bono_interno set registro.creditos_ant=registro.creditos,registro.creditos=registro.creditos+" . $valor_bono . " where registro.mandante=" . $mandante . " and registro.usuario_id=" . $usuarioId . " AND bono_id ='" . $bonoElegido . "'";
                                            //Actualizamos estadoBono
                                            $estadoBono = 'R';
                                            //Actualizamos sumoSaldo
                                            $SumoSaldo = true;

                                            //Sumamos al contador y generamos consulta SQL
                                            $contSql = $contSql + 1;

                                            //Insertamos registros en la tabla  usuario_historial,con los filtros:
                                            // Filas donde "usuario_bono.estado" es 'R'
                                            // Filas donde "registro.usuario_id"  es la variable $usuarioId
                                            // Filas donde "usuario_bono.bono_id" es la variable $bonoElegido
                                            // Ademas de la unión de usuario_bono con registro  donde registro.usuario_id = usuario_bono.usuario_id
                                            //
                                            //Las actalizaciones se haran en las columnas
                                            //   usuario_id, descripcion, movimiento, usucrea_id, usumodif_id, tipo, valor, externo_id, creditos, creditos_base
                                            //
                                            // Se harán de la siguiente manera
                                            // usuario_id
                                            //   Seleccionamos "usuario_bono.usuario_id" de la tabla "usuario_bono"
                                            // descripcion
                                            //   Valor vacio: ' '
                                            // movimiento
                                            //   Establecemos valor 'E'
                                            // usucrea_id
                                            //   Establecemos valor '0'
                                            // usumodif_id
                                            //   Establecemos valor '0'
                                            // tipo
                                            //   Establecemos valor '50'
                                            // valor
                                            //   Seleccionamos "valor" de la tabla "usuario_bono"
                                            // externo_id
                                            //   Seleccionamos "usubono_id" de la tabla "usuario_bono".
                                            // creditos
                                            //   Seleccionamos "creditos" de la tabla "registro".
                                            // creditos_base
                                            //   Seleccionamos "creditos_base" de la tabla "registro".

                                            $strSql[$contSql] = "insert into usuario_historial (usuario_id, descripcion, movimiento,usucrea_id,usumodif_id, tipo, valor, externo_id,creditos,creditos_base) SELECT usuario_bono.usuario_id,'','E',0,0,50,usuario_bono.valor,usuario_bono.usubono_id,creditos,creditos_base FROM usuario_bono INNER JOIN registro ON (registro.usuario_id = usuario_bono.usuario_id) WHERE usuario_bono.estado='R' and registro.usuario_id='" . $usuarioId . "' AND usuario_bono.bono_id ='" . $bonoElegido . "'";

                                            break;

                                        case 2:
                                            //Sumamos al contador y generamos consulta SQL
                                            $contSql = $contSql + 1;

                                            //Actualizamos la tabla registro y bono interno con los filtros:
                                            // Filas donde "registro.mandante" es $mandante
                                            // Filas donde "registro.usuario_id" es $usuarioId
                                            // Filas donde "bono_id" es $bonoElegido
                                            //Las actalizaciones son las siguientes
                                            //registro.saldo_especial = registro.saldo_especial + " . $valor_bono . ": Incrementa el valor de registro.creditos sumando el valor de la variable  $valor_bono.

                                            $strSql[$contSql] = "update registro,bono_interno set registro.saldo_especial=registro.saldo_especial+" . $valor_bono . " where registro.mandante=" . $mandante . " and registro.usuario_id=" . $usuarioId . " AND bono_id ='" . $bonoElegido . "'";
                                            //Actualizamos estadoBono
                                            $estadoBono = 'R';
                                            //Actualizamos sumoSaldo
                                            $SumoSaldo = true;

                                            //Sumamos al contador y generamos consulta SQL
                                            $contSql = $contSql + 1;

                                            //Insertamos registros en la tabla  usuario_historial,con los filtros:
                                            // Filas donde "usuario_bono.estado" es 'R'
                                            // Filas donde "registro.usuario_id"  es la variable $usuarioId
                                            // Filas donde "usuario_bono.bono_id" es la variable $bonoElegido
                                            // Ademas de la unión de usuario_bono con registro  donde registro.usuario_id = usuario_bono.usuario_id
                                            //
                                            //Las actalizaciones se haran en las columnas
                                            //   usuario_id, descripcion, movimiento, usucrea_id, usumodif_id, tipo, valor, externo_id, creditos, creditos_base
                                            //
                                            // Se harán de la siguiente manera
                                            // usuario_id
                                            //   Seleccionamos "usuario_bono.usuario_id" de la tabla "usuario_bono"
                                            // descripcion
                                            //   Valor vacio: ' '
                                            // movimiento
                                            //   Establecemos valor 'E'
                                            // usucrea_id
                                            //   Establecemos valor '0'
                                            // usumodif_id
                                            //   Establecemos valor '0'
                                            // tipo
                                            //   Establecemos valor '50'
                                            // valor
                                            //   Seleccionamos "valor" de la tabla "usuario_bono"
                                            // externo_id
                                            //   Seleccionamos "usubono_id" de la tabla "usuario_bono".
                                            // creditos
                                            //   Seleccionamos "creditos" de la tabla "registro".
                                            // creditos_base
                                            //   Seleccionamos "creditos_base" de la tabla "registro".

                                            $strSql[$contSql] = "insert into usuario_historial (usuario_id, descripcion, movimiento,usucrea_id,usumodif_id, tipo, valor, externo_id,creditos,creditos_base) SELECT usuario_bono.usuario_id,'','E',0,0,50,usuario_bono.valor,usuario_bono.usubono_id,creditos,creditos_base FROM usuario_bono INNER JOIN registro ON (registro.usuario_id = usuario_bono.usuario_id) WHERE usuario_bono.estado='R' and registro.usuario_id='" . $usuarioId . "' AND usuario_bono.bono_id ='" . $bonoElegido . "'";

                                            break;

                                    }

                                } else {

                                    //Hacemos llamado a la funcion agregarBonoFree
                                    $resp = $this->agregarBonoFree($ganaBonoId, $usuarioId, $mandante, $detalles, '', '', $transaccion);

                                    //Actualizamos el estado del bono
                                    $estadoBono = 'R';

                                }
                            }


                        } else {
                            //Si hay codeBono y el tipo de bono es deposito
                            if ($CodePromo != "" && $tipobono == 2) {
                                //Actualizamos estado del bono
                                $estadoBono = 'P';

                            } else {
                                //$rollowerDeposito && $ganaBonoId == 0
                                if ($rollowerDeposito) {
                                    //Calculamos valor del deposito del rollower requerido para ganar bono
                                    $rollowerRequerido = $rollowerRequerido + ($rollowerDeposito * $detalleValorDeposito);
                                }

                                if ($rollowerBono) {
                                    //Calculamos valor del bono del rollower requerido para ganar bono
                                    $rollowerRequerido = $rollowerRequerido + ($rollowerBono * $valor_bono);

                                }
                                if ($rollowerValor) {
                                    //Calculamos valor del rollower requerido para ganar bono
                                    $rollowerRequerido = $rollowerRequerido + ($rollowerValor);

                                }

                                //Sumamos al contador y guardamos la SQL
                                $contSql = $contSql + 1;

                                //Actualizamos la tabla registro y bono interno con los filtros:
                                // Filas donde "registro.mandante" es $mandante
                                // Filas donde "registro.usuario_id" es $usuarioId
                                // Filas donde "bono_id" es $bonoElegido
                                //Las actalizaciones son las siguientes
                                //registro.creditos_bono= registro.creditos_bono + " . $valor_bono . ": Incrementa el valor de registro.creditos sumando el valor de la variable  $valor_bono.

                                $strSql[$contSql] = "update registro,bono_interno set registro.creditos_bono=registro.creditos_bono+" . $valor_bono . " where registro.mandante=" . $mandante . " and registro.usuario_id=" . $usuarioId . "  AND bono_id ='" . $bonoElegido . "'";
                            }


                        }

                        // Si el condicional no se encuentra vacio
                        if ($condicionTriggerPosterior > 0) {

                            //Sumamos al contador y guardamos la SQL
                            $contSql = $contSql + 1;

                            //Actualizamos la tabla usuario_bono, bono interno con los filtros:
                            // Filas donde "usuario_bono.usubono_id" es igual a la variable $condicionTriggerPosterior
                            //Filas donde "usuario_bono.bono_id " es igual a la variable $bonoElegido
                            //Filas donde "bono_interno.bono_id" es igual a la variable $bonoElegido
                            //Filas donde "bono_interno.bono_id" es igual a la variable $bonoElegido  //*** si, se repite

                            //Las actalizaciones son las siguientes
                            //usuario_bono.valor: Se establece con el valor de la variable $valor_bono
                            //usuario_bono.valor_bono: Se establece con el valor de la variable $valorbono
                            //usuario_bono.valor_base: Se establece con el valor de la variable $valorBase
                            //usuario_bono.estado: Se establece con el valor de la variable $estadoBono.
                            //usuario_bono.error_id: Se establece en '0'
                            //usuario_bono.externo_id: Se establece en '0'
                            //usuario_bono.mandante: Se establece con el valor de la variable $mandante
                            //usuario_bono.rollower_requerido: Se establece con el valor de la variable $rollowerRequerido
                            //usuario_bono.fecha_crea: Se establece con la fecha y hora actual (date('Y-m-d H:i:s')).

                            $strSql[$contSql] = "UPDATE usuario_bono,bono_interno SET usuario_bono.valor='" . $valor_bono . "',usuario_bono.valor_bono='" . $valorbono . "',usuario_bono.valor_base='" . $valorBase . "',usuario_bono.estado='" . $estadoBono . "',usuario_bono.error_id='0',usuario_bono.externo_id='0',usuario_bono.mandante='" . $mandante . "',usuario_bono.rollower_requerido='" . $rollowerRequerido . "',usuario_bono.fecha_crea='" . date('Y-m-d H:i:s') . "' WHERE usuario_bono.usubono_id = '" . $condicionTriggerPosterior . "' AND usuario_bono.bono_id ='" . $bonoElegido . "' AND bono_interno.bono_id ='" . $bonoElegido . "'  AND bono_interno.bono_id ='" . $bonoElegido . "'";

                        } else {
                            //Verificamos estadoBono
                            if ($estadoBono == 'A') {
                                //Reiniciamos variable
                                $fechaExpiracion = '';

                                //Verificamos si no viene vacia
                                if ($expFecha != '') {
                                    //Configuramos fecha de expiracion
                                    $fechaExpiracion = date('Y-m-d H:i:s', strtotime($expFecha));

                                }
                                if ($expDia != '') {
                                    //Configuramos fecha de expiracion con dias
                                    $fechaExpiracion = date('Y-m-d H:i:s', strtotime(' + ' . $expDia . ' days'));

                                }
                                //Sumamos al contador y guardamos la SQL
                                $contSql = $contSql + 1;

                                //Insertamos registros en la tabla  usuario_bono,con los filtros:
                                // Filas donde "bono_id" es $bonoElegido
                                //
                                //Las actalizaciones se haran en las columnas
                                //   usuario_id, bono_id , valor , valor_bono , valor_base , fecha_crea , estado , error_id , externo_id , mandante , rollower_requerido , usucrea_id , usumodif_id , fecha_expiracion
                                //
                                // Se harán de la siguiente manera
                                // usuario_id
                                //   Establecemo $usuarioId
                                // bono_id
                                //    Establecemo $bonoElegido
                                // valor
                                //   Establecemo  $valor_bono
                                // valor_bono
                                //   Establecemos $valorbono
                                // valor_base
                                //   Establecemos $valorBase
                                // fecha_crea
                                //   Establecemos la fecha y hora actual
                                // estado
                                //   Establecemos $estadoBono
                                // error_id
                                //   Establecemos valor '0'
                                // externo_id
                                //   Establecemos valor 0
                                // mandante
                                //   Establecemos $mandante
                                // rollower_requerido
                                //   Establecemos  $rollowerRequerido
                                // usucrea_id
                                //   Establecemos valor 0
                                // usumodif_id
                                //   Establecemos valor 0
                                // fecha_expiracion
                                //   Establecemos  $fechaExpiracion

                                $strSql[$contSql] = "insert into usuario_bono (usuario_id,bono_id,valor,valor_bono,valor_base,fecha_crea,estado,error_id,externo_id,mandante,rollower_requerido,usucrea_id,usumodif_id,fecha_expiracion) SELECT " . $usuarioId . "," . $bonoElegido . "," . $valor_bono . "," . $valorbono . " ," . $valorBase . " ,'" . date('Y-m-d H:i:s') . "','" . $estadoBono . "','0',0," . $mandante . ", $rollowerRequerido,0,0,'" . $fechaExpiracion . "' FROM bono_interno WHERE  bono_id ='" . $bonoElegido . "'";

                            } else {

                                //Sumamos al contador y guardamos la SQL
                                $contSql = $contSql + 1;

                                //Insertamos registros en la tabla  usuario_bono,con los filtros:
                                // Filas donde "bono_id" es $bonoElegido
                                //
                                //Las actalizaciones se haran en las columnas
                                //   usuario_id, bono_id , valor , valor_bono , valor_base , fecha_crea , estado , error_id , externo_id , mandante , rollower_requerido , usucrea_id , usumodif_id
                                //
                                // Se harán de la siguiente manera
                                // usuario_id
                                //   Establecemo $usuarioId
                                // bono_id
                                //    Establecemo $bonoElegido
                                // valor
                                //   Establecemo  $valor_bono
                                // valor_bono
                                //   Establecemos $valorbono
                                // valor_base
                                //   Establecemos $valorBase
                                // fecha_crea
                                //   Establecemos la fecha y hora actual
                                // estado
                                //   Establecemos $estadoBono
                                // error_id
                                //   Establecemos valor '0'
                                // externo_id
                                //   Establecemos valor 0
                                // mandante
                                //   Establecemos $mandante

                                // rollower_requerido
                                //   Establecemos  $rollowerRequerido
                                // usucrea_id
                                //   Establecemos valor 0
                                // usumodif_id
                                //   Establecemos valor 0

                                $strSql[$contSql] = "insert into usuario_bono (usuario_id,bono_id,valor,valor_bono,valor_base,fecha_crea,estado,error_id,externo_id,mandante,rollower_requerido,usucrea_id,usumodif_id) SELECT " . $usuarioId . "," . $bonoElegido . "," . $valor_bono . "," . $valorbono . " ," . $valorBase . " ,'" . date('Y-m-d H:i:s') . "','" . $estadoBono . "','0',0," . $mandante . ", $rollowerRequerido,0,0 FROM bono_interno WHERE  bono_id ='" . $bonoElegido . "'";

                            }

                        }

                        // si la transaccion no esta vacia
                        if ($transaccion != "") {

                            //Recorremos las sql
                            foreach ($strSql as $val) {
                                if ($SumoSaldo && (strpos($val, 'insert into') !== false)) {

                                    //ejecutamos las SQL
                                    $resp = $this->execUpdate($transaccion, $val, 'insert');
                                }else{
                                    //ejecutamos las SQL
                                    $resp = $this->execUpdate($transaccion, $val);

                                }

                                //Si sumo saldo es verdadero y en $val encuentra insert into usuario_bono
                                if ($SumoSaldo && (strpos($val, 'insert into usuario_bono') !== false)) {
                                    //Guardamos respuesta
                                    $last_insert_id = $resp;
                                    $tibodebono = 'F';// actualizamos tipo de bono

                                    // Si tipo de bono es deposito
                                    if ($tipoBono == 2) {
                                        //Actualizamos tipo de bono
                                        $tibodebono = 'D';

                                    }

                                    // Si tipo de bono es no deposito
                                    if ($tipoBono == 3) {
                                        //Actualizamos tipo de bono
                                        $tibodebono = 'ND';

                                    }

                                    //Si es diferente de vacio y es numerico
                                    if ($last_insert_id != "" && is_numeric($last_insert_id)) {

                                        //Insertamos registros para seguimiento en la tabla  bono_log
                                        //
                                        //Las actalizaciones se haran en las columnas
                                        //   usuario_id, tipo , valor , estado , id_externo , mandante , transaccion_id , tipobono_id , fecha_crea
                                        //
                                        // Se harán de la siguiente manera
                                        // usuario_id
                                        //   Establecemos $usuarioId
                                        // tipo
                                        //   Establecemos $tibodebono
                                        // valor
                                        //   Establecemos $valor_bono
                                        // estado
                                        //   Establecemos valor 'L'
                                        // id_externo
                                        //   Establecemos $last_insert_id
                                        // mandante
                                        //   Establecemos valor '0'
                                        // transaccion_id
                                        //   Establecemos valor 0
                                        // tipobono_id
                                        //   Establecemos valor 4
                                        // fecha_crea
                                        //   Establecemos la fecha y hora actual

                                        $sql2 = "insert into bono_log (usuario_id,tipo,valor,estado,id_externo,mandante,transaccion_id,tipobono_id,fecha_crea) values (" . $usuarioId . ",'" . $tibodebono . "','" . $valor_bono . "','L','" . $last_insert_id . "','0',0,4,'" . date('Y-m-d H:i:s') . "')";
                                        //Ejecutamos la SQL
                                        $resp2 = $this->execUpdate($transaccion, $sql2);


                                        //Insertamos registros en la tabla  usuario_historial,con los filtros:
                                        // Filas donde "usuario_bono.estado" es 'R'
                                        // Filas donde "registro.usuario_id"  es la variable $usuarioId
                                        // Filas donde "usuario_bono.bono_id" es la variable $bonoElegido
                                        // Ademas de la unión de usuario_bono con registro  donde registro.usuario_id = usuario_bono.usuario_id
                                        //
                                        //Las actalizaciones se haran en las columnas
                                        //   usuario_id, descripcion, movimiento, usucrea_id, usumodif_id, tipo, valor, externo_id, creditos, creditos_base
                                        //
                                        // Se harán de la siguiente manera
                                        // usuario_id
                                        //   Seleccionamos "usuario_bono.usuario_id" de la tabla "usuario_bono"
                                        // descripcion
                                        //   Valor vacio: ' '
                                        // movimiento
                                        //   Establecemos valor 'E'
                                        // usucrea_id
                                        //   Establecemos valor '0'
                                        // usumodif_id
                                        //   Establecemos valor '0'
                                        // tipo
                                        //   Establecemos valor '50'
                                        // valor
                                        //   Seleccionamos "valor" de la tabla "usuario_bono"
                                        // externo_id
                                        //   Seleccionamos "usubono_id" de la tabla "usuario_bono".
                                        // creditos
                                        //   Seleccionamos "creditos" de la tabla "registro".
                                        // creditos_base
                                        //   Seleccionamos "creditos_base" de la tabla "registro".


                                        $sql2 = "insert into usuario_historial (usuario_id, descripcion, movimiento,usucrea_id,usumodif_id, tipo, valor, externo_id,creditos,creditos_base) SELECT usuario_bono.usuario_id,'','E',0,0,50,usuario_bono.valor,usuario_bono.usubono_id,creditos,creditos_base FROM usuario_bono INNER JOIN registro ON (registro.usuario_id = usuario_bono.usuario_id) WHERE usuario_bono.estado='R' and registro.usuario_id='" . $usuarioId . "' AND usuario_bono.bono_id ='" . $bonoElegido . "'";
                                        //Ejecutamos la SQL
                                        $resp2 = $this->execUpdate($transaccion, $sql2);

                                    }

                                }


                            }
                            //Si el condicional no es vacio y sumoSaldo es verdadero
                            if ($condicionTriggerPosterior > 0 && $SumoSaldo) {
                                //Actualizamos tipo de bono
                                $tibodebono = 'F';

                                // Si tipo de bono es deposito
                                if ($tipoBono == 2) {
                                    //Actualizamos tipo de bono
                                    $tibodebono = 'D';

                                }

                                // Si tipo de bono es no deposito
                                if ($tipoBono == 3) {
                                    //Actualizamos tipo de bono
                                    $tibodebono = 'ND';

                                }

                                //Insertamos registros para seguimiento en la tabla  bono_log
                                //
                                //Las actalizaciones se haran en las columnas
                                //   usuario_id, tipo , valor , estado , id_externo , mandante , transaccion_id , tipobono_id , fecha_crea
                                //
                                // Se harán de la siguiente manera
                                // usuario_id
                                //   Establecemos $usuarioId
                                // tipo
                                //   Establecemos $tibodebono
                                // valor
                                //   Establecemos $valor_bono
                                // estado
                                //   Establecemos valor 'L'
                                // id_externo
                                //   Establecemos $condicionTriggerPosterior
                                // mandante
                                //   Establecemos valor '0'
                                // transaccion_id
                                //   Establecemos valor 0
                                // tipobono_id
                                //   Establecemos valor 4
                                // fecha_crea
                                //   Establecemos la fecha y hora actual

                                $sql2 = "insert into bono_log (usuario_id,tipo,valor,estado,id_externo,mandante,transaccion_id,tipobono_id,fecha_crea) values (" . $usuarioId . ",'" . $tibodebono . "','" . $valor_bono . "','L','" . $condicionTriggerPosterior . "','0',0,4,'" . date('Y-m-d H:i:s') . "')";
                                //Ejecutamos SQL
                                $resp2 = $this->execUpdate($transaccion, $sql2);


                                //Insertamos registros en la tabla  usuario_historial,con los filtros:
                                // Filas donde "usuario_bono.estado" es 'R'
                                // Filas donde "registro.usuario_id"  es la variable $usuarioId
                                // Filas donde "usuario_bono.bono_id" es la variable $bonoElegido
                                // Ademas de la unión de usuario_bono con registro  donde registro.usuario_id = usuario_bono.usuario_id
                                //
                                //Las actalizaciones se haran en las columnas
                                //   usuario_id, descripcion, movimiento, usucrea_id, usumodif_id, tipo, valor, externo_id, creditos, creditos_base
                                //
                                // Se harán de la siguiente manera
                                // usuario_id
                                //   Seleccionamos "usuario_bono.usuario_id" de la tabla "usuario_bono"
                                // descripcion
                                //   Valor vacio: ' '
                                // movimiento
                                //   Establecemos valor 'E'
                                // usucrea_id
                                //   Establecemos valor '0'
                                // usumodif_id
                                //   Establecemos valor '0'
                                // tipo
                                //   Establecemos valor '50'
                                // valor
                                //   Seleccionamos "valor" de la tabla "usuario_bono"
                                // externo_id
                                //   Seleccionamos "usubono_id" de la tabla "usuario_bono".
                                // creditos
                                //   Seleccionamos "creditos" de la tabla "registro".
                                // creditos_base
                                //   Seleccionamos "creditos_base" de la tabla "registro".

                                $sql2 = "insert into usuario_historial (usuario_id, descripcion, movimiento,usucrea_id,usumodif_id, tipo, valor, externo_id,creditos,creditos_base) SELECT usuario_bono.usuario_id,'','E',0,0,50,usuario_bono.valor,usuario_bono.usubono_id,creditos,creditos_base FROM usuario_bono INNER JOIN registro ON (registro.usuario_id = usuario_bono.usuario_id) WHERE usuario_bono.estado='R' and registro.usuario_id='" . $usuarioId . "' AND usuario_bono.bono_id ='" . $bonoElegido . "'";
                                //Ejecutamos la SQL
                                $resp2 = $this->execUpdate($transaccion, $sql2);

                            }


                        }


                        // $contSql = $contSql + 1;
                        // $strSql[$contSql] = "insert into bono_log (usuario_id,tipo,valor,estado,id_externo,mandante,transaccion_id,tipobono_id) values (" . $usuarioId . ",'" . $tipoBonoS . "','" . $valor_bono  . "','L','0'," . $mandante . ",0,4)";

                        //Guardamos confirmación de bono ganado con su valor y demas variables a usar
                        $respuesta["WinBonus"] = true;
                        $respuesta["SumoSaldo"] = $SumoSaldo;
                        $respuesta["Bono"] = $bonoElegido;
                        $respuesta["Valor"] = $valor_bono;
                        $respuesta["queries"] = $strSql;

                        try {
                            //Enviamos mensaje/notificacion al usuario
                            if ($mandante == 19) {
                                //Configuramos el mensaje para el bono asociado a una campaña o no
                                if ($respuesta['Bono']) {
                                    $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
                                    $BonodetalleM = $BonoDetalleMySqlDAO->querybyBonoIdAndTipo($respuesta['Bono'], 'MARKETINGCAMPAING');
                                    if ($BonodetalleM) {
                                        $UsuarioMandante = new UsuarioMandante('', $usuarioId, $mandante);
                                        //Recorremos la consulta y configuramos parametros a mostrar en mensaje/notificación
                                        foreach ($BonodetalleM as $mensaje) {
                                            $Campaing = new UsuarioMensajecampana($mensaje->valor);


                                            $UsuarioMensaje = new UsuarioMensaje();
                                            $UsuarioMensaje->body = $Campaing->body;
                                            $UsuarioMensaje->msubject = $Campaing->msubject;
                                            $UsuarioMensaje->usufromId = 0;
                                            $UsuarioMensaje->usutoId = $UsuarioMandante->usumandanteId;
                                            $UsuarioMensaje->isRead = 0;
                                            $UsuarioMensaje->msubject = $Campaing->nombre;
                                            $UsuarioMensaje->parentId = $mandante;
                                            $UsuarioMensaje->proveedorId = $mandante;
                                            $UsuarioMensaje->tipo = $Campaing->tipo;
                                            $UsuarioMensaje->paisId = $Campaing->paisId;
                                            $UsuarioMensaje->fechaExpiracion = $Campaing->fechaExpiracion;
                                            $UsuarioMensaje->usumencampanaId = $mensaje->valor;


                                            $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($transaccion);
                                            //Enviamos los parametros para el mensaje a la tabla usuario_mensaje
                                            $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);

                                        }
                                    }
                                }

                            }
                        } catch (Exception $e) {

                        }
                    }
                }

                //Si el tipo de bono es no deposito
                if ($tipoBono == 3) {
                    //Hacemos llamado a la funcion agregarBonoFree
                    $resp = $this->agregarBonoFree($bonoElegido, $usuarioId, $mandante, $detalles, '', '', $transaccion);
                    return $resp; //Retornamos respuesta de agregarBonoFree

                }

                //Si tipo de bono es FreeCasino
                if ($tipoBono == 5) {

                    //Hacemos llamado a la funcion agregarBonoFree
                    $resp = $this->agregarBonoFree($bonoElegido, $usuarioId, $mandante, $detalles, '', '', $transaccion);

                    return $resp; //Retornamos respuesta de agregarBonoFree

                }
                //Si tipo de bono es FreeBet
                if ($tipoBono == 6) {

                    //Hacemos llamado a la funcion agregarBonoFree
                    $resp = $this->agregarBonoFree($bonoElegido, $usuarioId, $mandante, $detalles, '', '', $transaccion);

                    return $resp; //Retornamos respuesta de agregarBonoFree
                }
            }
        }

        try {
            if ($respuesta != null && $respuesta["WinBonus"] == true) {
                if ($bonosUsuarioDisponibles != null && is_array($bonosUsuarioDisponibles)) {

                    foreach ($bonosUsuarioDisponibles as $bonosUsuarioDisponible) {
                        $sqlUpdate = "UPDATE usuario_bono SET estado='R'  WHERE usubono_id='" . $bonosUsuarioDisponible->usubono_id . "' AND estado='P' ";

                        $bonosUsuarioDisponiblesUpdate = $this->execQuery($transaccion, $sqlUpdate);

                    }
                }

                if ($condicionTriggerPosterior != null) {

                        $sqlUpdate = "UPDATE usuario_bono SET estado='R'  WHERE usubono_id='" . $condicionTriggerPosterior . "' AND estado='P' ";

                        $bonosUsuarioDisponiblesUpdate = $this->execQuery($transaccion, $sqlUpdate);

                }
            }
        } catch (Exception $e) {
        }

        return json_decode(json_encode($respuesta));

    }

    /**
     * Funcion global para CRM, FreeSpin y Referidos
     *
     *
     * @param String $bonoid
     * @param String $usuarioId
     * @param String $mandante
     * @param String $detalles
     * @param String $ejecutarSQL
     * @param String $codebonus
     * @param String $transaccion
     *
     * @return Array resultado de la consulta
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function bonoGlobal($Proveedor, $bonoElegido, $CONDGAME, $mandante, $usuarioId, $transaccion, $detalleValorDeposito, $isCreateB = false, $detalleReferidoId = 0, $Name = "", $Prefix = "", $MaxplayersCount = "")
    {
        try {
            if ($_ENV['debug']) {
                print_r(PHP_EOL);
                print_r(' ENTRO AQUI BONOGLOBAL ');
                print_r(PHP_EOL);
            }

            $respuesta = array();
            $ganoBonoBool = true;

            if ($isCreateB) {
                $users = $usuarioId;
            } else {
                $users = array($usuarioId);
            }

            try {
                $BonoDetalleROUNDSFREE = new BonoDetalle('', $bonoElegido, 'REPETIRBONO');
                $repetirBono = $BonoDetalleROUNDSFREE->valor;
            } catch (Exception $e) {
                $repetirBono = 0;
            }

            $BonoInterno = new BonoInterno($bonoElegido);
            $IsCRM = $BonoInterno->perteneceCrm;

            $userCannotRepeatBonus = "";
            if (!$repetirBono) {
                $users = array_unique($users);
                $userWithBonus = [];
                $userWithoutBonus = [];

                foreach ($users as $user) {
                    try {
                        $VerifUsuarioBono = new UsuarioBono('', $user, $bonoElegido);
                        $userWithBonus[] = $user;
                    } catch (Exception $e) {
                        $userWithoutBonus[] = $user;
                    }
                }

                $users = $userWithoutBonus;
                $userCannotRepeatBonus = implode(", ", $userWithBonus);
            }

            if (!$repetirBono && oldCount($userWithBonus) > 0 && oldCount($users) == 1) {
                $respuesta["ganoBonoBool"] = false;
                $respuesta["bonoElegido"] = $bonoElegido;
                $respuesta["status"] = 'ERROR';
                $respuesta["abreviado"] = $Proveedor->abreviado;
                $respuesta["detail_response"] = 'Usuario(s) no puede repetir bono: ' . $userCannotRepeatBonus;

            } elseif ($users[0] == '' && $Proveedor->abreviado != "IESGAMES") {
                $respuesta["ganoBonoBool"] = $ganoBonoBool;
                $respuesta["bonoElegido"] = $bonoElegido;
                $respuesta["status"] = 'OK';
                $respuesta["abreviado"] = $Proveedor->abreviado;
                $respuesta["detail_response"] = 'Usuario(s) no puede repetir bono: ' . $userCannotRepeatBonus;

            } else {
                $games = array();
                
                foreach ($CONDGAME as $value) {
                    if ($isCreateB) {
                        $Idgames = $value->Id;
                    } else {
                        $Idgames = $value;
                    }

                    $productoMandante = new ProductoMandante("", $mandante, $Idgames);
                    $producto = new Producto($productoMandante->productoId, "", $Proveedor->getProveedorId());
                    $gamesExId = $producto->externoId;
                    array_push($games, $gamesExId);
                }

                $producto = new Producto('', $games[0], $Proveedor->getProveedorId());
                $subProveedorId = $producto->subproveedorId;

                $roundsFree = '';
                $roundsValue = '';
                $Multiplier = '';
                $GoldenChip = false;
                $TemplateCode = '';

                $BonoInterno = new BonoInterno($bonoElegido);

                try {
                    $BonoDetalleROUNDSFREE = new BonoDetalle('', $bonoElegido, 'ROUNDSFREE');
                    $roundsFree = $BonoDetalleROUNDSFREE->valor;
                } catch (Exception $e) {}

                try {
                    $BonoDetalleROUNDSVALUE = new BonoDetalle('', $bonoElegido, 'ROUNDSVALUE');
                    $roundsValue = $BonoDetalleROUNDSVALUE->valor;
                } catch (Exception $e) {}

                try {
                    $BonoDetalleMULTIPLIER = new BonoDetalle('', $bonoElegido, 'MULTIPLIER');
                    $Multiplier = $BonoDetalleMULTIPLIER->valor;
                } catch (Exception $e) {}

                try {
                    $BonoDetalleGOLDENCHIP = new BonoDetalle('', $bonoElegido, 'GOLDENCHIP');
                    $GoldenChip = $BonoDetalleGOLDENCHIP->valor;
                } catch (Exception $e) {
                    $GoldenChip = false;
                }

                try {
                    $BonoDetalleTEMPLATECODE = new BonoDetalle('', $bonoElegido, 'TEMPLATECODE');
                    $TemplateCode = $BonoDetalleTEMPLATECODE->valor;
                } catch (Exception $e) {}

                switch ($Proveedor->abreviado) {
                    case "IESGAMES":
                        if ($users[0] != "" && $users[0] != null) {
                            foreach ($users as $value) {

                                $usuarioId = $value;
                                $estado = 'L';
                                $valor = '0';
                                $valor_bono = '0';
                                $valor_base = '0';
                                $errorId = '0';
                                $idExterno = '0';
                                $mandante = '0';
                                $usucreaId = '0';
                                $usumodifId = '0';
                                $codigosarray = array();

                                $codigo = GenerarClaveTicket(4);
                                $apostado = '0';
                                $rollowerRequerido = '0';
                                $codigo = $Prefix . $codigo;

                                $UsuarioBono = new UsuarioBono();

                                $UsuarioBono->setUsuarioId($usuarioId);
                                $UsuarioBono->setBonoId($bonoElegido);
                                $UsuarioBono->setValor($valor);
                                $UsuarioBono->setValorBono($valor_bono);
                                $UsuarioBono->setValorBase($valor_base);
                                $UsuarioBono->setEstado($estado);
                                $UsuarioBono->setErrorId($errorId);
                                $UsuarioBono->setIdExterno($idExterno);
                                $UsuarioBono->setMandante($mandante);
                                $UsuarioBono->setUsucreaId($usucreaId);
                                $UsuarioBono->setUsumodifId($usumodifId);
                                $UsuarioBono->setApostado($apostado);
                                $UsuarioBono->setRollowerRequerido($rollowerRequerido);
                                $UsuarioBono->setCodigo($codigo);
                                $UsuarioBono->setVersion(0);
                                $UsuarioBono->setExternoId(0);

                                $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($transaccion);

                                $inse = $UsuarioBonoMysqlDAO->insert($UsuarioBono);
                            }

                        } else {

                            for ($i = 0; $i < $MaxplayersCount; $i++) {
                                $usuarioId = 0;
                                $estado = 'L';

                                $valor = '0';

                                $valor_bono = '0';

                                $valor_base = '0';

                                $errorId = '0';

                                $idExterno = '0';

                                $mandante = '0';


                                $usucreaId = '0';

                                $usumodifId = '0';

                                $codigosarray = array();

                                $codigo = GenerarClaveTicket(4);
                                $apostado = '0';
                                $rollowerRequerido = '0';
                                $codigo = $Prefix . $codigo;

                                $UsuarioBono = new UsuarioBono();

                                $UsuarioBono->setUsuarioId($usuarioId);
                                $UsuarioBono->setBonoId($bonoElegido);
                                $UsuarioBono->setValor($valor);
                                $UsuarioBono->setValorBono($valor_bono);
                                $UsuarioBono->setValorBase($valor_base);
                                $UsuarioBono->setEstado($estado);
                                $UsuarioBono->setErrorId($errorId);
                                $UsuarioBono->setIdExterno($idExterno);
                                $UsuarioBono->setMandante($mandante);
                                $UsuarioBono->setUsucreaId($usucreaId);
                                $UsuarioBono->setUsumodifId($usumodifId);
                                $UsuarioBono->setApostado($apostado);
                                $UsuarioBono->setRollowerRequerido($rollowerRequerido);
                                $UsuarioBono->setCodigo($codigo);
                                $UsuarioBono->setVersion(0);
                                $UsuarioBono->setExternoId(0);

                                $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($transaccion);

                                $inse = $UsuarioBonoMysqlDAO->insert($UsuarioBono);
                            }
                        }
                        break;


                    case "7777GAMING":
                        $G7777GAMINGSERVICES = new G7777GAMINGSERVICES();

                        $response2 = $G7777GAMINGSERVICES->AddFreespins($bonoElegido, $roundsFree, $BonoInterno->fechaInicio, $BonoInterno->fechaFin, $users, $games, rand(1, 10000));

                        if ($response2["code"] === 0) {
                            $userBonusIds = self::createUserBonusRelation($users, $BonoInterno, $detalleReferidoId, $transaccion, $subProveedorId);
                            $status = "OK";
                        } else {
                            $status = "ERROR";
                        }

                        $ganoBonoBool = true;

                        break;


                    case "BOOMING":
                        $BOOMINGSERVICES = new BOOMINGSERVICES();

                        $usersSegmented = self::segmentArrayByLimit($users, 3000);

                        $creationResponse = [];
                        foreach ($usersSegmented as $segmentUser) {
                            $creationResponse[] = $BOOMINGSERVICES->AddFreespins($bonoElegido, $roundsFree, $roundsValue, $BonoInterno->fechaInicio, $BonoInterno->fechaFin, $segmentUser, $games, rand(1, 10000), $BonoInterno->nombre);

                            ['code' => $code] = end($creationResponse);
                            if ($code === 0) $userBonusIds = self::createUserBonusRelation($segmentUser, $BonoInterno, $detalleReferidoId, $transaccion, $subProveedorId);
                        }

                        $status = self::verifyStatusResponse($creationResponse);
                        $response2 = $creationResponse;
                        $ganoBonoBool = true;

                        break;

                    case "TADAGAMING":
                        $TADAGAMINGSERVICES = new TADAGAMINGSERVICES();

                        $usersSegmented = self::segmentArrayByLimit($users, 200);
                        
                        $creationResponse = [];
                        foreach ($usersSegmented as $segmentUser) {
                            $creationResponse[] = $TADAGAMINGSERVICES->AddFreespins($bonoElegido, $roundsFree, $roundsValue, $BonoInterno->fechaInicio, $BonoInterno->fechaFin, $segmentUser, $games, rand(1, 10000), $BonoInterno->nombre);

                            ['code' => $code] = end($creationResponse);
                            if ($code === 0) $userBonusIds = self::createUserBonusRelation($segmentUser, $BonoInterno, $detalleReferidoId, $transaccion, $subProveedorId);
                        }
                    
                        $status = self::verifyStatusResponse($creationResponse);
                        $ganoBonoBool = true;
                        $response2 = $creationResponse;

                        break;

                    case "EXPANSE":

                        $EXPANSESERVICES = new EXPANSESERVICES();

                        $usersSegmented = self::segmentArrayByLimit($users, 3000);
                        
                        $creationResponse = [];
                        foreach($usersSegmented as $segmentUser){
                            $creationResponse[] = $EXPANSESERVICES->AddFreespins($bonoElegido, $roundsFree, $roundsValue, $BonoInterno->fechaInicio, $BonoInterno->fechaFin, $segmentUser, $games, rand(1, 10000), $BonoInterno->nombre, $Multiplier);
                            
                            ['code' => $code] = end($creationResponse);
                            if ($code === 0) $userBonusIds = self::createUserBonusRelation($segmentUser, $BonoInterno, $detalleReferidoId, $transaccion, $subProveedorId);
                        }

                        $status = self::verifyStatusResponse($creationResponse);
                        $ganoBonoBool = true;
                        $response2 = $creationResponse;

                        break;


                    case "KAGAMING":
                        $KAGAMINGSERVICES = new KAGAMINGSERVICES();

                        foreach ($users as $user) {
                            $UsuarioBono = new UsuarioBono();
                            $UsuarioBono->setUsuarioId($user);
                            $UsuarioBono->setBonoId($BonoInterno->bonoId);
                            $UsuarioBono->setValor(0);
                            $UsuarioBono->setValorBono(0);
                            $UsuarioBono->setValorBase(0);
                            $UsuarioBono->setEstado('R');
                            $UsuarioBono->setErrorId('0');
                            $UsuarioBono->setIdExterno('0');
                            $UsuarioBono->setMandante($BonoInterno->mandante);
                            $UsuarioBono->setUsucreaId('0');
                            $UsuarioBono->setUsumodifId('0');
                            $UsuarioBono->setApostado('0');
                            $UsuarioBono->setVersion('2');
                            $UsuarioBono->setRollowerRequerido('0');
                            $UsuarioBono->setCodigo('');
                            $UsuarioBono->setExternoId($Proveedor->subproveedorId);

                            if (!empty($detalleReferidoId)) $UsuarioBono->setUsuidReferido($detalleReferidoId);
                            $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($transaccion);
                            $usubonoId = $UsuarioBonoMysqlDAO->insert($UsuarioBono);

                            $response2 = $KAGAMINGSERVICES->AddFreespins($roundsFree, $roundsValue, $BonoInterno->fechaInicio, $BonoInterno->fechaFin, $user, $games);

                            if ($response2["code"] === 0) {
                                $repBnoID = $response2["response_code"];

                                $UsuarioBono->setExternoBono($repBnoID);
                                $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($transaccion);
                                $usubonoId = $UsuarioBonoMysqlDAO->update($UsuarioBono);
                            }
                        }

                        if ($response2["code"] === 0) {
                            $status = "OK";
                        } else {
                            $status = "ERROR";
                        }

                        $ganoBonoBool = true;

                        break;

                    case "BRAGG":
                        $BRAGGSERVICES = new BRAGGSERVICES();

                        $creationResponse = [];
                        foreach ($users as $user) {
                            $creationResponse[] = $BRAGGSERVICES->AddFreespins(rand(1, 10000), $roundsFree, $roundsValue, $BonoInterno->fechaInicio, $BonoInterno->fechaFin, $user, $games, $bonoElegido); 

                            ['code' => $code] = end($creationResponse);
                            if ($code === 0) $userBonusIds = self::createUserBonusRelation([$user], $BonoInterno, $detalleReferidoId, $transaccion, $subProveedorId);
                        }

                        $status = self::verifyStatusResponse($creationResponse);
                        $response2 = $creationResponse;
                        $ganoBonoBool = true;

                        break;

                    case "EVOPLAY":
                        $EVOPLAYSERVICES = new EVOPLAYSERVICES();

                        $creationResponse = [];
                        foreach ($users as $key => $user) {
                            $creationResponse[] = $EVOPLAYSERVICES->AddFreespins($bonoElegido, $roundsFree, $roundsValue, $BonoInterno->fechaInicio, $BonoInterno->fechaFin, $user, $games, rand(1, 10000), $BonoInterno->nombre);

                            ['code' => $code] = end($creationResponse);
                            if ($code === 0) $userBonusIds = self::createUserBonusRelation([$user], $BonoInterno, $detalleReferidoId, $transaccion, $subProveedorId);
                        }

                        $status = self::verifyStatusResponse($creationResponse);
                        $response2 = $creationResponse;
                        $ganoBonoBool = true;

                        break;

                    case "LAMBDA":
                        $LAMBDASERVICES = new LAMBDASERVICES();

                        $creationResponse = [];
                        foreach ($users as $key => $user) {
                            $creationResponse[] = $LAMBDASERVICES->AddFreespins($bonoElegido . rand(1, 10000), $roundsFree, $user, $games, $BonoInterno->fechaInicio, $BonoInterno->fechaFin);

                            ['code' => $code] = end($creationResponse);
                            if ($code === 0) $userBonusIds = self::createUserBonusRelation([$user], $BonoInterno, $detalleReferidoId, $transaccion, $subProveedorId);
                        }

                        $status = self::verifyStatusResponse($creationResponse);
                        $response2 = $creationResponse;
                        $ganoBonoBool = true;

                        break;

                    case "ONLYPLAY":
                        $ONLYPLAYSERVICES = new ONLYPLAYSERVICES();

                        $creationResponse = [];
                        foreach ($users as $key => $user) {
                            $creationResponse[] = $ONLYPLAYSERVICES->AddFreespins($bonoElegido . rand(1, 10000), $roundsFree, $roundsValue, $BonoInterno->fechaFin, $user, $games);

                            ['code' => $code] = end($creationResponse);
                            if ($code === 0) $userBonusIds = self::createUserBonusRelation([$user], $BonoInterno, $detalleReferidoId, $transaccion, $subProveedorId);
                        }

                        $status = self::verifyStatusResponse($creationResponse);
                        $response2 = $creationResponse;
                        $ganoBonoBool = true;

                        break;

                    case "BELATRA":
                        $BELATRASERVICES = new BELATRASERVICES();

                        $creationResponse = [];
                        foreach ($users as $key => $user) {
                            $creationResponse[] = $BELATRASERVICES->AddFreespins($bonoElegido, $roundsFree, $roundsValue, $BonoInterno->fechaInicio, $BonoInterno->fechaFin, $user, $games, rand(1, 10000));

                            ['code' => $code] = end($creationResponse);
                            if ($code === 0) $userBonusIds = self::createUserBonusRelation([$user], $BonoInterno, $detalleReferidoId, $transaccion, $subProveedorId);
                        }

                        $status = self::verifyStatusResponse($creationResponse);
                        $response2 = $creationResponse;
                        $ganoBonoBool = true;

                        break;

                    case "EGT":
                        $EGTSERVICES = new EGTSERVICES();
                        
                        $response2 = $EGTSERVICES->AddFreespins($bonoElegido, $roundsFree, $roundsValue, $BonoInterno->fechaInicio, $BonoInterno->fechaFin, $users, $games);
                        
                        if ($response2["code"] === 0) {
                            $userBonusIds = self::createUserBonusRelation($users, $BonoInterno, $detalleReferidoId, $transaccion, $subProveedorId);
                            $status = "OK";
                        } else {
                            $status = "ERROR";
                        }

                        $ganoBonoBool = true;
                        
                        break;

                    case "MERKUR":
                        $MERKURSERVICES = new MERKURSERVICES();

                        $response2 = $MERKURSERVICES->AddFreespins($bonoElegido . rand(1, 10000), $roundsFree, $roundsValue, $BonoInterno->fechaInicio, $BonoInterno->fechaFin, $users, $games);
                        
                        if ($response2["code"] === 0) {
                            $userBonusIds = self::createUserBonusRelation($users, $BonoInterno, $detalleReferidoId, $transaccion, $subProveedorId);
                            $status = "OK";
                        } else {
                            $status = "ERROR";
                        }

                        $ganoBonoBool = true;

                        break;

                    case "PLAYTECHLIVE":
                    case "PLAYTECH":
                        $PLAYTECHSERVICES = new PLAYTECHSERVICES();

                        $games = array();
                        $games2 = array();
                        foreach ($CONDGAME as $key => $value) {

                            if ($isCreateB) {
                                $Idgames = $value->Id;
                            } else {
                                $Idgames = $value;
                            }

                            $productoMandante = new ProductoMandante("", $mandante, $Idgames);
                            $Producto = new Producto($productoMandante->productoId, "", $Proveedor->getProveedorId());
                            $ProductoDetalle = new ProductoDetalle('', $Producto->productoId, 'GAMEID');
                            $prod = $ProductoDetalle->pValue;

                            $Subproveedor = new Subproveedor($Producto->subproveedorId);

                            if ($Subproveedor->tipo == 'LIVECASINO') {
                                $game = explode(";", $prod);
                                $gamesExId = $game[0];
                                $gamesExId2 = $Producto->externoId;
                            } else {
                                $gamesExId = $Producto->externoId;
                                $gamesExId2 = $Producto->externoId;
                            }

                            array_push($games, $gamesExId);
                            array_push($games2, $gamesExId2);
                        }

                        $creationResponse = [];
                        foreach ($users as $key => $user) {
                            $creationResponse[] = $PLAYTECHSERVICES->givefreespins($bonoElegido, $roundsFree, $roundsValue, $BonoInterno->fechaFin, $user, $games, rand(1, 10000), $GoldenChip, $TemplateCode, $games2);

                            ['code' => $code] = end($creationResponse);
                            if ($code === 0) $userBonusIds = self::createUserBonusRelation([$user], $BonoInterno, $detalleReferidoId, $transaccion, $subProveedorId);
                        }

                        $status = self::verifyStatusResponse($creationResponse);
                        $response2 = $creationResponse;
                        $ganoBonoBool = true;

                        break;

                    case "REDRAKE":
                        $REDRAKESERVICESBONUS = new REDRAKESERVICESBONUS();

                        $usersSegmented = self::segmentArrayByLimit($users, 2000);
                        $creationResponse = [];
                        foreach($usersSegmented as $segmentUser){
                            $creationResponse[] = $REDRAKESERVICESBONUS->awardFRBonus($bonoElegido, $roundsFree, $roundsValue, $BonoInterno->fechaInicio, $BonoInterno->fechaFin, '', $segmentUser, $games, rand(1, 100000));

                            ['code' => $code] = end($creationResponse);
                            if ($code === 0) $userBonusIds = self::createUserBonusRelation($segmentUser, $BonoInterno, $detalleReferidoId, $transaccion, $subProveedorId);
                        }

                        $status = self::verifyStatusResponse($creationResponse);
                        $ganoBonoBool = true;
                        $response2 = $creationResponse;

                        break;

                    case "PRAGMATIC":
                        $PRAGMATICSERVICES = new PRAGMATICSERVICES();


                        $response2 = $PRAGMATICSERVICES->AddFreespins($bonoElegido, $roundsFree, $roundsValue, $BonoInterno->fechaInicio, $BonoInterno->fechaFin, $users, $games, rand(1, 10000));
                        
                        if ($response2["code"] === 0) {
                            $userBonusIds = self::createUserBonusRelation($users, $BonoInterno, $detalleReferidoId, $transaccion, $subProveedorId);
                            $status = "OK";
                        } else {
                            $status = "ERROR";
                        }

                        $ganoBonoBool = true;

                        break;

                    case "AMIGOGAMING":
                        $AMIGOGAMINGSERVICES = new AMIGOGAMINGSERVICES();

                        $creationResponse = [];
                        foreach ($users as $key => $user) {
                            $creationResponse[] = $AMIGOGAMINGSERVICES->AddFreespins($bonoElegido, $roundsFree, $roundsValue, $BonoInterno->fechaInicio, $BonoInterno->fechaFin, $user, $games, rand(1, 10000));

                            ['code' => $code] = end($creationResponse);
                            if ($code === 0) $userBonusIds = self::createUserBonusRelation([$user], $BonoInterno, $detalleReferidoId, $transaccion, $subProveedorId);
                        }

                        $status = self::verifyStatusResponse($creationResponse);
                        $response2 = $creationResponse;
                        $ganoBonoBool = true;

                        break;

                    case "ENPH":
                        $ENDORPHINASERVICES = new ENDORPHINASERVICES();

                        $creationResponse = [];
                        foreach ($users as $key => $user) {
                            $creationResponse[] = $ENDORPHINASERVICES->AddFreespins($bonoElegido, $Name, $roundsFree, $roundsValue, $BonoInterno->fechaInicio, $BonoInterno->fechaFin, $user, $games, rand(1, 10000));

                            ['code' => $code] = end($creationResponse);
                            if ($code === 0) $userBonusIds = self::createUserBonusRelation([$user], $BonoInterno, $detalleReferidoId, $transaccion, $subProveedorId);
                        }

                        $status = self::verifyStatusResponse($creationResponse);
                        $response2 = $creationResponse;
                        $ganoBonoBool = true;

                        break;

                    case "RUBYPLAY":
                        $RUBYPLAYSERVICES = new RUBYPLAYSERVICES();
                        
                        $response2 = $RUBYPLAYSERVICES->FreeRounds($bonoElegido, $Name, $roundsFree, $roundsValue, $BonoInterno->fechaInicio, $BonoInterno->fechaFin, '', $users, $games, $IsCRM);
                        
                        if ($response2["code"] === 0) {
                            $userBonusIds = self::createUserBonusRelation($users, $BonoInterno, $detalleReferidoId, $transaccion, $subProveedorId);
                            $status = "OK";
                        } else {
                            $status = "ERROR";
                        }

                        $ganoBonoBool = true;

                        break;

                    case "SOFTSWISS":
                        $SOFTSWISSSERVICES = new SOFTSWISSSERVICES();

                        $creationResponse = [];
                        foreach ($users as $key => $user) {
                            $creationResponse[] = $SOFTSWISSSERVICES->AddFreespins($bonoElegido, $roundsFree, $roundsValue, $BonoInterno->fechaInicio, $user, $games, rand(1, 10000));

                            ['code' => $code] = end($creationResponse);
                            if ($code === 0) $userBonusIds = self::createUserBonusRelation([$user], $BonoInterno, $detalleReferidoId, $transaccion, $subProveedorId);
                        }

                        $status = self::verifyStatusResponse($creationResponse);
                        $response2 = $creationResponse;
                        $ganoBonoBool = true;

                        break;

                    case "SMARTSOFT":
                        $SMARTSOFTSERVICES = new SMARTSOFTSERVICES();

                        $creationResponse = [];
                        foreach ($users as $key => $user) {
                            $creationResponse[] = $SMARTSOFTSERVICES->AddFreespins($bonoElegido, $roundsFree, $roundsValue, $BonoInterno->fechaInicio, $BonoInterno->fechaFin, $user, $games, rand(1, 10000));

                            ['code' => $code] = end($creationResponse);
                            if ($code === 0) $userBonusIds = self::createUserBonusRelation([$user], $BonoInterno, $detalleReferidoId, $transaccion, $subProveedorId);
                        }

                        $status = self::verifyStatusResponse($creationResponse);
                        $response2 = $creationResponse;
                        $ganoBonoBool = true;

                        break;

                    case "SPINOMENAL":
                        $SPINOMENALSERVICES = new SPINOMENALSERVICES();

                        $creationResponse = [];
                        foreach ($users as $key => $user) {
                            $creationResponse[] = $SPINOMENALSERVICES->AddFreespins($bonoElegido, $roundsFree, $roundsValue, $BonoInterno->fechaInicio, $BonoInterno->fechaFin, $user, $games, rand(1, 10000), $BonoInterno->nombre);

                            ['code' => $code] = end($creationResponse);
                            if ($code === 0) $userBonusIds = self::createUserBonusRelation([$user], $BonoInterno, $detalleReferidoId, $transaccion, $subProveedorId);
                        }

                        $status = self::verifyStatusResponse($creationResponse);
                        $response2 = $creationResponse;
                        $ganoBonoBool = true;

                        break;

                    case "MANCALA":
                        $MANCALASERVICES = new MANCALASERVICES();

                        $usersSegmented = self::segmentArrayByLimit($users, 3000);

                        $creationResponse = [];
                        foreach ($usersSegmented as $segmentUser) {
                            $creationResponse[] = $MANCALASERVICES->AddFreespins($bonoElegido, $roundsFree, $roundsValue, $BonoInterno->fechaInicio, $BonoInterno->fechaFin, $segmentUser, $games, rand(1, 10000), $BonoInterno->nombre);

                            ['code' => $code] = end($creationResponse);
                            if ($code === 0) $userBonusIds = self::createUserBonusRelation($segmentUser, $BonoInterno, $detalleReferidoId, $transaccion, $subProveedorId);
                        }

                        $status = self::verifyStatusResponse($creationResponse);
                        $response2 = $creationResponse;
                        $ganoBonoBool = true;

                        break;

                    case "GALAXSYS":
                        $GALAXSYSSERVICES = new GALAXSYSSERVICES();

                        $response2 = $GALAXSYSSERVICES->AddFreespins($bonoElegido, $roundsFree, $roundsValue, $BonoInterno->fechaInicio, $BonoInterno->fechaFin, $users, $games, rand(1, 10000), $BonoInterno->nombre);
                        
                        if ($response2["code"] === 0) {
                            $userBonusIds = self::createUserBonusRelation($users, $BonoInterno, $detalleReferidoId, $transaccion, $subProveedorId);
                            $status = "OK";
                        } else {
                            $status = "ERROR";
                        }

                        $ganoBonoBool = true;

                        break;

                    case "PGSOFT":
                        $PGSOFTSERVICES = new PGSOFTSERVICES();

                        $creationResponse = [];
                        foreach ($users as $key => $user) {
                            $creationResponse[] = $PGSOFTSERVICES->AddFreespins($bonoElegido, $roundsFree, $roundsValue, $BonoInterno->fechaInicio, $BonoInterno->fechaFin, $user, $games, rand(1, 10000));

                            ['code' => $code] = end($creationResponse);
                            if ($code === 0) $userBonusIds = self::createUserBonusRelation([$user], $BonoInterno, $detalleReferidoId, $transaccion, $subProveedorId);
                        }

                        $status = self::verifyStatusResponse($creationResponse);
                        $response2 = $creationResponse;
                        $ganoBonoBool = true;

                        break;

                    case "GAMESGLOBAL":
                        $GAMESGLOBALSERVICES = new GAMESGLOBALSERVICES();

                        $response2 = $GAMESGLOBALSERVICES->AddFreespins($bonoElegido, $Name, $roundsFree, $roundsValue, $BonoInterno->fechaInicio, $BonoInterno->fechaFin, $users, $games, rand(1, 10000));
                        
                        if ($response2["code"] === 0) {
                            $userBonusIds = self::createUserBonusRelation($users, $BonoInterno, $detalleReferidoId, $transaccion, $subProveedorId);
                            $status = "OK";
                        } else {
                            $status = "ERROR";
                        }

                        $ganoBonoBool = true;

                        break;

                    case "AMUSNET":
                        $AMUSNETSERVICES = new AMUSNETSERVICES();

                        $usersSegmented = self::segmentArrayByLimit($users, 3000);

                        $creationResponse = [];
                        foreach($usersSegmented as $segmentUser){
                            $creationResponse[] = $AMUSNETSERVICES->AddFreespins($bonoElegido, $Name, $roundsFree, $roundsValue, $BonoInterno->fechaInicio, $BonoInterno->fechaFin, $segmentUser, $games, rand(1, 10000));

                            ['code' => $code] = end($creationResponse);
                            if ($code === 0) $userBonusIds = self::createUserBonusRelation($segmentUser, $BonoInterno, $detalleReferidoId, $transaccion, $subProveedorId);
                        }
                        
                        $status = self::verifyStatusResponse($creationResponse);
                        $ganoBonoBool = true;
                        $response2 = $creationResponse;

                        break;

                    case "PLATIPUS":
                        $PLATIPUSSERVICES = new PLATIPUSSERVICES();

                        $creationResponse = [];
                        foreach ($users as $key => $user) {
                            $creationResponse[] = $PLATIPUSSERVICES->AddFreespins($bonoElegido, $roundsFree, $roundsValue, $BonoInterno->fechaInicio, $BonoInterno->fechaFin, $user, $games, rand(1, 10000));

                            ['code' => $code] = end($creationResponse);
                            if ($code === 0) $userBonusIds = self::createUserBonusRelation([$user], $BonoInterno, $detalleReferidoId, $transaccion, $subProveedorId);
                        }

                        $status = self::verifyStatusResponse($creationResponse);
                        $response2 = $creationResponse;
                        $ganoBonoBool = true;

                        break;

                    case "PASCAL":
                        $PASCALSERVICES = new PASCALSERVICES();

                        $usersSegmented = self::segmentArrayByLimit($users, 800);

                        $creationResponse = [];
                        foreach ($usersSegmented as $segmentUser) {
                            $creationResponse[] = $PASCALSERVICES->AddFreespins($bonoElegido, $Name, $roundsFree, $roundsValue, $BonoInterno->fechaInicio, $BonoInterno->fechaFin, $segmentUser, $games, rand(1, 10000));

                            ['code' => $code] = end($creationResponse);
                            if ($code === 0) $userBonusIds = self::createUserBonusRelation($segmentUser, $BonoInterno, $detalleReferidoId, $transaccion, $subProveedorId); 
                        }

                        $status = self::verifyStatusResponse($creationResponse);
                        $ganoBonoBool = true;
                        $response2 = $creationResponse;

                        break;

                    case "PLAYSON":
                        $PLAYSONSERVICES = new PLAYSONSERVICES();

                        $creationResponse = [];
                        foreach ($users as $key => $user) {
                            $creationResponse[] = $PLAYSONSERVICES->createOffer($bonoElegido, $games, $roundsFree, $roundsValue, 0, $BonoInterno->fechaInicio, $BonoInterno->fechaFin, $user, rand(1, 10000));
                            
                            ['code' => $code] = end($creationResponse);
                            if ($code === 0) $userBonusIds = self::createUserBonusRelation([$user], $BonoInterno, $detalleReferidoId, $transaccion, $subProveedorId);
                        }

                        $status = self::verifyStatusResponse($creationResponse);
                        $response2 = $creationResponse;
                        $ganoBonoBool = true;

                        break;


                    case "MASCOT":
                        $MASCOTSERVICES = new MASCOTSERVICES();

                        $response2 = $MASCOTSERVICES->SetBonus($bonoElegido, $roundsFree, $users, $games);
                        
                        if ($response2["code"] === 0) {
                            $userBonusIds = self::createUserBonusRelation($users, $BonoInterno, $detalleReferidoId, $transaccion, $subProveedorId);
                            $status = "OK";
                        } else {
                            $status = "ERROR";
                        }

                        $ganoBonoBool = true;

                        break;

                    case "TOMHORN":
                        $TOMHORNSERVICES = new TOMHORNSERVICES();

                        $usersSegmented = self::segmentArrayByLimit($users, 10);

                        $creationResponse = [];
                        $campaignCode = null;
                        
                        foreach ($usersSegmented as $segmentUser) {
                            $creationResponse[] = $TOMHORNSERVICES->CreateFreespin($games[0], $Name, $roundsFree, $roundsValue, $BonoInterno->fechaInicio, $BonoInterno->fechaFin, $segmentUser, $bonoElegido, $mandante, rand(1, 100000), $campaignCode);

                            ['code' => $code, 'campaignCode' => $campaignCode] = end($creationResponse);
                            if ($code === 0) $userBonusIds = self::createUserBonusRelation($segmentUser, $BonoInterno, $detalleReferidoId, $transaccion, $subProveedorId);
                        }

                        $status = self::verifyStatusResponse($creationResponse);
                        $ganoBonoBool = true;
                        $response2 = $creationResponse;
                        
                        break;

                    case "CTGAMING":
                        $CTGAMINGSERVICES = new CTGAMINGSERVICES();

                        $userBonusIds = self::createUserBonusRelation($users, $BonoInterno, $detalleReferidoId, $transaccion, $subProveedorId);

                        //$response2 = $CTGAMINGSERVICES->CreateFreespin($games, $Name, $roundsFree, $roundsValue, $BonoInterno->fechaInicio, $BonoInterno->fechaFin, $users, $bonoElegido, $mandante);

                        //if ($response2["code"] === 0) {
                        $status = "OK";
                        //} else {
                        //   $status = "ERROR";
                        //}
                        $ganoBonoBool = true;


                        break;

                    case "AIRDICE":
                        $AIRDICESERVICES = new AIRDICESERVICES();

                        $creationResponse = [];
                        foreach ($users as $key => $user) {
                            $creationResponse[] = $AIRDICESERVICES->AddFreespins($bonoElegido, $roundsFree, $roundsValue, $BonoInterno->fechaInicio, $BonoInterno->fechaFin, $user, $games, rand(1, 10000));

                            ['code' => $code] = end($creationResponse);
                            if ($code === 0) $userBonusIds = self::createUserBonusRelation([$user], $BonoInterno, $detalleReferidoId, $transaccion, $subProveedorId);
                        }

                        $status = self::verifyStatusResponse($creationResponse);
                        $response2 = $creationResponse;
                        $ganoBonoBool = true;

                        break;

                    case "PLAYNGO":

                        $PLAYNGOSERVICES = new PLAYNGOSERVICES();

                        $games = array();
                        foreach ($CONDGAME as $key => $value) {
                            if ($isCreateB) {
                                $Idgames = $value->Id;
                            } else {
                                $Idgames = $value;
                            }
                            $productoMandante = new ProductoMandante("", $mandante, $Idgames);
                            $producto = new Producto($productoMandante->productoId);
                            $gamesExId = $producto->externoId;
                            array_push($games, $gamesExId);
                        }

                        $BonoInterno = new BonoInterno($bonoElegido);
                        $EndDate = $BonoInterno->fechaFin;

                        $roundvalue = '';
                        $roundsFree = '';
                        $TemplateCode = '';
                        $ganoBonoBool = true;
                        $ValorBonoSTR = '';

                        try {
                            $BonoDetalleTEMPLATECODE = new BonoDetalle('', $bonoElegido, 'TEMPLATECODE');
                            $TemplateCode = $BonoDetalleTEMPLATECODE->valor;
                        } catch (Exception $e) {

                        }
                        try {
                            $BonoDetalleROUNDSFREE = new BonoDetalle('', $bonoElegido, 'ROUNDSFREE');
                            $roundsFree = $BonoDetalleROUNDSFREE->valor;
                            $ValorBonoSTR = $roundsFree;
                        } catch (Exception $e) {

                        }
                        try {
                            $BonoDetalleROUNDSVALUE = new BonoDetalle('', $bonoElegido, 'ROUNDSVALUE');
                            $roundsValue = $BonoDetalleROUNDSVALUE->valor;
                        } catch (Exception $e) {

                        }

                        if ($bonoElegido == '29188') {
                            $TemplateCode = 13106;

                            if ($detalleValorDeposito >= 10 && $detalleValorDeposito < 11) {
                                $ValorBonoSTR = 10;
                            }
                            if ($detalleValorDeposito >= 11 && $detalleValorDeposito < 12) {
                                $ValorBonoSTR = 11;
                            }
                            if ($detalleValorDeposito >= 12 && $detalleValorDeposito < 13) {
                                $ValorBonoSTR = 12;
                            }
                            if ($detalleValorDeposito >= 13 && $detalleValorDeposito < 14) {
                                $ValorBonoSTR = 13;
                            }
                            if ($detalleValorDeposito >= 14 && $detalleValorDeposito < 15) {
                                $ValorBonoSTR = 14;
                            }
                            if ($detalleValorDeposito >= 15 && $detalleValorDeposito < 16) {
                                $ValorBonoSTR = 15;
                            }
                            if ($detalleValorDeposito >= 16 && $detalleValorDeposito < 17) {
                                $ValorBonoSTR = 16;
                            }
                            if ($detalleValorDeposito >= 17 && $detalleValorDeposito < 18) {
                                $ValorBonoSTR = 17;
                            }
                            if ($detalleValorDeposito >= 18 && $detalleValorDeposito < 19) {
                                $ValorBonoSTR = 18;
                            }
                            if ($detalleValorDeposito >= 19 && $detalleValorDeposito < 20) {
                                $ValorBonoSTR = 19;
                            }
                            if ($detalleValorDeposito >= 20 && $detalleValorDeposito < 21) {
                                $ValorBonoSTR = 20;
                            }
                            if ($detalleValorDeposito >= 21 && $detalleValorDeposito < 22) {
                                $ValorBonoSTR = 21;
                            }
                            if ($detalleValorDeposito >= 22 && $detalleValorDeposito < 23) {
                                $ValorBonoSTR = 22;
                            }
                            if ($detalleValorDeposito >= 23 && $detalleValorDeposito < 24) {
                                $ValorBonoSTR = 23;
                            }
                            if ($detalleValorDeposito >= 24 && $detalleValorDeposito < 25) {
                                $ValorBonoSTR = 24;
                            }
                            if ($detalleValorDeposito >= 25 && $detalleValorDeposito < 26) {
                                $ValorBonoSTR = 25;
                            }
                            if ($detalleValorDeposito >= 26 && $detalleValorDeposito < 27) {
                                $ValorBonoSTR = 26;
                            }
                            if ($detalleValorDeposito >= 27 && $detalleValorDeposito < 28) {
                                $ValorBonoSTR = 27;
                            }
                            if ($detalleValorDeposito >= 28 && $detalleValorDeposito < 29) {
                                $ValorBonoSTR = 28;
                            }
                            if ($detalleValorDeposito >= 29 && $detalleValorDeposito < 30) {
                                $ValorBonoSTR = 29;
                            }
                            if ($detalleValorDeposito >= 30 && $detalleValorDeposito < 31) {
                                $ValorBonoSTR = 30;
                            }
                            if ($detalleValorDeposito >= 31 && $detalleValorDeposito < 32) {
                                $ValorBonoSTR = 31;
                            }
                            if ($detalleValorDeposito >= 32 && $detalleValorDeposito < 33) {
                                $ValorBonoSTR = 32;
                            }
                            if ($detalleValorDeposito >= 33 && $detalleValorDeposito < 34) {
                                $ValorBonoSTR = 33;
                            }
                            if ($detalleValorDeposito >= 34 && $detalleValorDeposito < 35) {
                                $ValorBonoSTR = 34;
                            }
                            if ($detalleValorDeposito >= 35 && $detalleValorDeposito < 36) {
                                $ValorBonoSTR = 35;
                            }
                            if ($detalleValorDeposito >= 36 && $detalleValorDeposito < 37) {
                                $ValorBonoSTR = 36;
                            }
                            if ($detalleValorDeposito >= 37 && $detalleValorDeposito < 38) {
                                $ValorBonoSTR = 37;
                            }
                            if ($detalleValorDeposito >= 38 && $detalleValorDeposito < 39) {
                                $ValorBonoSTR = 38;
                            }
                            if ($detalleValorDeposito >= 39 && $detalleValorDeposito < 40) {
                                $ValorBonoSTR = 39;
                            }
                            if ($detalleValorDeposito >= 40 && $detalleValorDeposito < 41) {
                                $ValorBonoSTR = 40;
                            }
                            if ($detalleValorDeposito < 10) {
                                $ganoBonoBool = false;
                            }
                            if ($detalleValorDeposito >= 41) {
                                $ganoBonoBool = false;
                            }
                        }

                        //DECU
                        if ($bonoElegido == '29192') {
                            $TemplateCode = 13107;
                            if ($detalleValorDeposito >= 5 && $detalleValorDeposito < 6) {
                                $ValorBonoSTR = 10;
                            }
                            if ($detalleValorDeposito >= 6 && $detalleValorDeposito < 7) {
                                $ValorBonoSTR = 12;
                            }
                            if ($detalleValorDeposito >= 7 && $detalleValorDeposito < 8) {
                                $ValorBonoSTR = 14;
                            }
                            if ($detalleValorDeposito >= 8 && $detalleValorDeposito < 9) {
                                $ValorBonoSTR = 16;
                            }
                            if ($detalleValorDeposito >= 9 && $detalleValorDeposito < 10) {
                                $ValorBonoSTR = 18;
                            }
                            if ($detalleValorDeposito >= 10 && $detalleValorDeposito < 11) {
                                $ValorBonoSTR = 20;
                            }

                            if ($detalleValorDeposito < 5) {
                                $ganoBonoBool = false;
                            }
                            if ($detalleValorDeposito >= 11) {
                                $ganoBonoBool = false;
                            }

                        }

                        //CL
                        if ($bonoElegido == '29196') {
                            $TemplateCode = 13109;
                            if ($detalleValorDeposito >= 5000 && $detalleValorDeposito < 6000) {
                                $ValorBonoSTR = 10;
                            }
                            if ($detalleValorDeposito >= 6000 && $detalleValorDeposito < 7000) {
                                $ValorBonoSTR = 12;
                            }
                            if ($detalleValorDeposito >= 7000 && $detalleValorDeposito < 8000) {
                                $ValorBonoSTR = 14;
                            }
                            if ($detalleValorDeposito >= 8000 && $detalleValorDeposito < 9000) {
                                $ValorBonoSTR = 16;
                            }
                            if ($detalleValorDeposito >= 9000 && $detalleValorDeposito < 10000) {
                                $ValorBonoSTR = 18;
                            }
                            if ($detalleValorDeposito >= 10000 && $detalleValorDeposito < 11000) {
                                $ValorBonoSTR = 20;
                            }


                            if ($detalleValorDeposito < 5000) {
                                $ganoBonoBool = false;
                            }

                            if ($detalleValorDeposito >= 11000) {
                                $ganoBonoBool = false;
                            }
                        }

                        //ECU
                        if ($bonoElegido == '29194') {
                            $TemplateCode = 13108;
                            if ($detalleValorDeposito >= 5 && $detalleValorDeposito < 6) {
                                $ValorBonoSTR = 10;
                            }
                            if ($detalleValorDeposito >= 6 && $detalleValorDeposito < 7) {
                                $ValorBonoSTR = 12;
                            }
                            if ($detalleValorDeposito >= 7 && $detalleValorDeposito < 8) {
                                $ValorBonoSTR = 14;
                            }
                            if ($detalleValorDeposito >= 8 && $detalleValorDeposito < 9) {
                                $ValorBonoSTR = 16;
                            }
                            if ($detalleValorDeposito >= 9 && $detalleValorDeposito < 10) {
                                $ValorBonoSTR = 18;
                            }
                            if ($detalleValorDeposito >= 10 && $detalleValorDeposito < 11) {
                                $ValorBonoSTR = 20;
                            }
                            if ($detalleValorDeposito >= 11 && $detalleValorDeposito < 12) {
                                $ValorBonoSTR = 22;
                            }
                            if ($detalleValorDeposito >= 12 && $detalleValorDeposito < 13) {
                                $ValorBonoSTR = 24;
                            }
                            if ($detalleValorDeposito >= 13 && $detalleValorDeposito < 14) {
                                $ValorBonoSTR = 26;
                            }
                            if ($detalleValorDeposito >= 14 && $detalleValorDeposito < 15) {
                                $ValorBonoSTR = 28;
                            }
                            if ($detalleValorDeposito >= 15 && $detalleValorDeposito < 16) {
                                $ValorBonoSTR = 30;
                            }
                            if ($detalleValorDeposito >= 16 && $detalleValorDeposito < 17) {
                                $ValorBonoSTR = 32;
                            }
                            if ($detalleValorDeposito >= 17 && $detalleValorDeposito < 18) {
                                $ValorBonoSTR = 34;
                            }
                            if ($detalleValorDeposito >= 18 && $detalleValorDeposito < 19) {
                                $ValorBonoSTR = 36;
                            }
                            if ($detalleValorDeposito >= 19 && $detalleValorDeposito < 20) {
                                $ValorBonoSTR = 38;
                            }
                            if ($detalleValorDeposito >= 20 && $detalleValorDeposito < 21) {
                                $ValorBonoSTR = 40;
                            }


                            if ($detalleValorDeposito < 5) {
                                $ganoBonoBool = false;
                            }


                            if ($detalleValorDeposito >= 21) {
                                $ganoBonoBool = false;
                            }
                        }

                        //BR
                        if ($bonoElegido == '29208') {
                            $TemplateCode = 13111;
                            if ($detalleValorDeposito >= 10 && $detalleValorDeposito < 11) {
                                $ValorBonoSTR = 10;
                            }
                            if ($detalleValorDeposito >= 11 && $detalleValorDeposito < 12) {
                                $ValorBonoSTR = 11;
                            }
                            if ($detalleValorDeposito >= 12 && $detalleValorDeposito < 13) {
                                $ValorBonoSTR = 12;
                            }
                            if ($detalleValorDeposito >= 13 && $detalleValorDeposito < 14) {
                                $ValorBonoSTR = 13;
                            }
                            if ($detalleValorDeposito >= 14 && $detalleValorDeposito < 15) {
                                $ValorBonoSTR = 14;
                            }
                            if ($detalleValorDeposito >= 15 && $detalleValorDeposito < 16) {
                                $ValorBonoSTR = 15;
                            }
                            if ($detalleValorDeposito >= 16 && $detalleValorDeposito < 17) {
                                $ValorBonoSTR = 16;
                            }
                            if ($detalleValorDeposito >= 17 && $detalleValorDeposito < 18) {
                                $ValorBonoSTR = 17;
                            }
                            if ($detalleValorDeposito >= 18 && $detalleValorDeposito < 19) {
                                $ValorBonoSTR = 18;
                            }
                            if ($detalleValorDeposito >= 19 && $detalleValorDeposito < 20) {
                                $ValorBonoSTR = 19;
                            }
                            if ($detalleValorDeposito >= 20 && $detalleValorDeposito < 21) {
                                $ValorBonoSTR = 20;
                            }
                            if ($detalleValorDeposito >= 21 && $detalleValorDeposito < 22) {
                                $ValorBonoSTR = 21;
                            }
                            if ($detalleValorDeposito >= 22 && $detalleValorDeposito < 23) {
                                $ValorBonoSTR = 22;
                            }
                            if ($detalleValorDeposito >= 23 && $detalleValorDeposito < 24) {
                                $ValorBonoSTR = 23;
                            }
                            if ($detalleValorDeposito >= 24 && $detalleValorDeposito < 25) {
                                $ValorBonoSTR = 24;
                            }
                            if ($detalleValorDeposito >= 25 && $detalleValorDeposito < 26) {
                                $ValorBonoSTR = 25;
                            }
                            if ($detalleValorDeposito >= 26 && $detalleValorDeposito < 27) {
                                $ValorBonoSTR = 26;
                            }
                            if ($detalleValorDeposito >= 27 && $detalleValorDeposito < 28) {
                                $ValorBonoSTR = 27;
                            }
                            if ($detalleValorDeposito >= 28 && $detalleValorDeposito < 29) {
                                $ValorBonoSTR = 28;
                            }
                            if ($detalleValorDeposito >= 29 && $detalleValorDeposito < 30) {
                                $ValorBonoSTR = 29;
                            }
                            if ($detalleValorDeposito >= 30 && $detalleValorDeposito < 31) {
                                $ValorBonoSTR = 30;
                            }

                            if ($detalleValorDeposito < 10) {
                                $ganoBonoBool = false;
                            }


                            if ($detalleValorDeposito >= 31) {
                                $ganoBonoBool = false;
                            }
                        }

                        //NIC
                        if ($bonoElegido == '29198') {
                            $TemplateCode = 13110;
                            if ($detalleValorDeposito >= 10 && $detalleValorDeposito < 11) {
                                $ValorBonoSTR = 10;
                            }
                            if ($detalleValorDeposito >= 11 && $detalleValorDeposito < 12) {
                                $ValorBonoSTR = 11;
                            }
                            if ($detalleValorDeposito >= 12 && $detalleValorDeposito < 13) {
                                $ValorBonoSTR = 12;
                            }
                            if ($detalleValorDeposito >= 13 && $detalleValorDeposito < 14) {
                                $ValorBonoSTR = 13;
                            }
                            if ($detalleValorDeposito >= 14 && $detalleValorDeposito < 15) {
                                $ValorBonoSTR = 14;
                            }
                            if ($detalleValorDeposito >= 15 && $detalleValorDeposito < 16) {
                                $ValorBonoSTR = 15;
                            }
                            if ($detalleValorDeposito >= 16 && $detalleValorDeposito < 17) {
                                $ValorBonoSTR = 16;
                            }
                            if ($detalleValorDeposito >= 17 && $detalleValorDeposito < 18) {
                                $ValorBonoSTR = 17;
                            }
                            if ($detalleValorDeposito >= 18 && $detalleValorDeposito < 19) {
                                $ValorBonoSTR = 18;
                            }
                            if ($detalleValorDeposito >= 19 && $detalleValorDeposito < 20) {
                                $ValorBonoSTR = 19;
                            }
                            if ($detalleValorDeposito >= 20 && $detalleValorDeposito < 21) {
                                $ValorBonoSTR = 20;
                            }
                            if ($detalleValorDeposito >= 21 && $detalleValorDeposito < 22) {
                                $ValorBonoSTR = 21;
                            }
                            if ($detalleValorDeposito >= 22 && $detalleValorDeposito < 23) {
                                $ValorBonoSTR = 22;
                            }
                            if ($detalleValorDeposito >= 23 && $detalleValorDeposito < 24) {
                                $ValorBonoSTR = 23;
                            }
                            if ($detalleValorDeposito >= 24 && $detalleValorDeposito < 25) {
                                $ValorBonoSTR = 24;
                            }
                            if ($detalleValorDeposito >= 25 && $detalleValorDeposito < 26) {
                                $ValorBonoSTR = 25;
                            }
                            if ($detalleValorDeposito >= 26 && $detalleValorDeposito < 27) {
                                $ValorBonoSTR = 26;
                            }
                            if ($detalleValorDeposito >= 27 && $detalleValorDeposito < 28) {
                                $ValorBonoSTR = 27;
                            }
                            if ($detalleValorDeposito >= 28 && $detalleValorDeposito < 29) {
                                $ValorBonoSTR = 28;
                            }
                            if ($detalleValorDeposito >= 29 && $detalleValorDeposito < 30) {
                                $ValorBonoSTR = 29;
                            }
                            if ($detalleValorDeposito >= 30 && $detalleValorDeposito < 31) {
                                $ValorBonoSTR = 30;
                            }

                            if ($detalleValorDeposito < 10) {
                                $ganoBonoBool = false;
                            }


                            if ($detalleValorDeposito >= 31) {
                                $ganoBonoBool = false;
                            }
                        }


                        if ($bonoElegido == '29206') {
                            $TemplateCode = 13114;
                            if ($detalleValorDeposito >= 5000 && $detalleValorDeposito < 6000) {
                                $ValorBonoSTR = 10;
                            }
                            if ($detalleValorDeposito >= 6000 && $detalleValorDeposito < 7000) {
                                $ValorBonoSTR = 12;
                            }
                            if ($detalleValorDeposito >= 7000 && $detalleValorDeposito < 8000) {
                                $ValorBonoSTR = 14;
                            }
                            if ($detalleValorDeposito >= 8000 && $detalleValorDeposito < 9000) {
                                $ValorBonoSTR = 16;
                            }
                            if ($detalleValorDeposito >= 9000 && $detalleValorDeposito < 10000) {
                                $ValorBonoSTR = 18;
                            }
                            if ($detalleValorDeposito >= 10000 && $detalleValorDeposito < 11000) {
                                $ValorBonoSTR = 20;
                            }

                            if ($detalleValorDeposito < 5000) {
                                $ganoBonoBool = false;
                            }

                            if ($detalleValorDeposito >= 11000) {
                                $ganoBonoBool = false;
                            }
                        }


                        if ($bonoElegido == '29204') {
                            $TemplateCode = 13112;
                            if ($detalleValorDeposito >= 50 && $detalleValorDeposito < 60) {
                                $ValorBonoSTR = 5;
                            }
                            if ($detalleValorDeposito >= 60 && $detalleValorDeposito < 70) {
                                $ValorBonoSTR = 7;
                            }
                            if ($detalleValorDeposito >= 70 && $detalleValorDeposito < 80) {
                                $ValorBonoSTR = 9;
                            }
                            if ($detalleValorDeposito >= 80 && $detalleValorDeposito < 90) {
                                $ValorBonoSTR = 11;
                            }
                            if ($detalleValorDeposito >= 90 && $detalleValorDeposito < 100) {
                                $ValorBonoSTR = 13;
                            }

                            if ($detalleValorDeposito < 50) {
                                $ganoBonoBool = false;
                            }
                            if ($detalleValorDeposito >= 100) {
                                $ganoBonoBool = false;
                            }

                        }


                        if ($bonoElegido == '29210') {
                            $TemplateCode = 13113;
                            if ($detalleValorDeposito >= 100 && $detalleValorDeposito < 110) {
                                $ValorBonoSTR = 10;
                            }
                            if ($detalleValorDeposito >= 110 && $detalleValorDeposito < 120) {
                                $ValorBonoSTR = 11;
                            }
                            if ($detalleValorDeposito >= 120 && $detalleValorDeposito < 130) {
                                $ValorBonoSTR = 12;
                            }
                            if ($detalleValorDeposito >= 130 && $detalleValorDeposito < 1400) {
                                $ValorBonoSTR = 13;
                            }
                            if ($detalleValorDeposito >= 140 && $detalleValorDeposito < 150) {
                                $ValorBonoSTR = 14;
                            }
                            if ($detalleValorDeposito >= 150 && $detalleValorDeposito < 160) {
                                $ValorBonoSTR = 15;
                            }
                            if ($detalleValorDeposito >= 160 && $detalleValorDeposito < 170) {
                                $ValorBonoSTR = 16;
                            }
                            if ($detalleValorDeposito >= 170 && $detalleValorDeposito < 180) {
                                $ValorBonoSTR = 17;
                            }
                            if ($detalleValorDeposito >= 18 && $detalleValorDeposito < 190) {
                                $ValorBonoSTR = 18;
                            }
                            if ($detalleValorDeposito >= 190 && $detalleValorDeposito < 200) {
                                $ValorBonoSTR = 19;
                            }
                            if ($detalleValorDeposito >= 200 && $detalleValorDeposito < 210) {
                                $ValorBonoSTR = 20;
                            }

                            if ($detalleValorDeposito < 100) {
                                $ganoBonoBool = false;
                            }
                            if ($detalleValorDeposito >= 210) {
                                $ganoBonoBool = false;
                            }

                        }

                        if ($ganoBonoBool) {
                            $roundsFree = $ValorBonoSTR;

                            foreach ($users as $user) {
                                $UsuarioBono = new UsuarioBono();
                                $UsuarioBono->setUsuarioId($user);
                                $UsuarioBono->setBonoId($BonoInterno->bonoId);
                                $UsuarioBono->setValor(0);
                                $UsuarioBono->setValorBono(0);
                                $UsuarioBono->setValorBase(0);
                                $UsuarioBono->setEstado('R');
                                $UsuarioBono->setErrorId('0');
                                $UsuarioBono->setIdExterno('0');
                                $UsuarioBono->setMandante($BonoInterno->mandante);
                                $UsuarioBono->setUsucreaId('0');
                                $UsuarioBono->setUsumodifId('0');
                                $UsuarioBono->setApostado('0');
                                $UsuarioBono->setVersion('2');
                                $UsuarioBono->setRollowerRequerido('0');
                                $UsuarioBono->setCodigo('');
                                $UsuarioBono->setExternoId('0');

                                $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($transaccion);
                                $usubonoId = $UsuarioBonoMysqlDAO->insert($UsuarioBono);

                                if ($TemplateCode != "" && $user != "" && $roundsFree != "") {
                                    $response2 = $PLAYNGOSERVICES->AddFreegameOffers($bonoElegido, $roundsFree, $roundvalue, $EndDate, $user, $games, $usubonoId, $TemplateCode);
                                }
                            }
                        }

                        if ($response2["code"] === 0) {
                            $status = "OK";
                        } else {
                            $status = "ERROR";
                        }
                        $ganoBonoBool = true;

                        break;

                    case "RAW":
                        $rawServices = new RAWSERVICES();

                        $creationResponse = [];
                        foreach ($users as $key => $user) {
                            $creationResponse[] = $rawServices->createBonusFreeSpin($bonoElegido, $roundsFree, $roundsValue, $BonoInterno->fechaInicio, $BonoInterno->fechaFin, $user, $games, rand(1, 10000));

                            ['code' => $code] = end($creationResponse);
                            if ($code === 0) $userBonusIds = self::createUserBonusRelation([$user], $BonoInterno, $detalleReferidoId, $transaccion, $subProveedorId);
                        }

                        $status = self::verifyStatusResponse($creationResponse);
                        $response2 = $creationResponse;
                        $ganoBonoBool = true;

                        break;

                    case 'RFRANCO':
                        $rfrancoServices = new RFRANCOSERVICES();
                        $usersSegmented = self::segmentArrayByLimit($users, 3000);

                        $creationResponse = [];
                        
                        foreach ($usersSegmented as $segmentUser) {
                            $creationResponse[] = $rfrancoServices->createBonusFreeSpin($bonoElegido, $roundsFree, $roundsValue, $BonoInterno, $segmentUser, $games, rand(1, 100000));

                            ['code' => $code] = end($creationResponse);
                            if ($code === 0) $userBonusIds = self::createUserBonusRelation($segmentUser, $BonoInterno, $detalleReferidoId, $transaccion, $subProveedorId);
                        }
                        
                        $status = self::verifyStatusResponse($creationResponse);
                        $ganoBonoBool = true;
                        $response2 = $creationResponse;
                    
                        break;
                }

                $respuesta["WinBonus"] = $ganoBonoBool;
                $respuesta["ganoBonoBool"] = $ganoBonoBool;
                $respuesta["bonoElegido"] = $bonoElegido;
                $respuesta["status"] = $status;
                $respuesta["abreviado"] = $Subproveedor->abreviado;
                $respuesta["repetirBono"] = $userCannotRepeatBonus;

                if (is_array(($response2))) {
                    $respuesta["detalleRespuesta"] = json_encode($response2);
                }
            }

            return $respuesta;

        } catch (Throwable $th) {
            $response2 = self::buildErrorResponse($th);

            $respuesta["WinBonus"] = $ganoBonoBool ?? true;
            $respuesta["ganoBonoBool"] = $ganoBonoBool ?? true;
            $respuesta["bonoElegido"] = $bonoElegido;
            $respuesta["status"] = $status ?? 'ERROR';
            $respuesta["abreviado"] = $Proveedor->abreviado;
            $respuesta["repetirBono"] = $userCannotRepeatBonus;

            if (is_array(($response2))) {
                $respuesta["detalleRespuesta"] = json_encode($response2);
            }

            syslog(E_ERROR, "Bono Interno Response: " . json_encode($response2));
        }
    }

    /**
     * Define la estructura de error que se debe usar en caso de excepciones.
     * 
     * @param Throwable $th Excepción que se ha producido, contiene los siguientes métodos.
     *        - getCode() = Es el código de la excepción.
     *        - getMessage() = Es el mensaje de la excepción.
     * 
     * @return array Retorna un array con la estructura de error.
     */
    private function buildErrorResponse(Throwable $th) :array {
        return [
            "code" => 1,
            "response_code" => $th->getCode(),
            "response_message" => $th->getMessage()
        ];
    }

    /**
     * Verifica los estados de las respuestas de la creación de los bonos.
     * 
     * @param array $responseToValidate Respuestas a validar.
     * 
     * @return string Retorna 'OK' si alguna de las respuestas es correcta o error en caso de que ninguna lo sea 'ERROR'.
     * @throws Throwable Si ocurre algún error durante la validación de la respuesta.
     */
    private function verifyStatusResponse(array $responseToValidate) :string {
        try {
            $statusCodes = [];
            foreach ($responseToValidate as $value) {
                ['code' => $code] = $value;
                $statusCodes[] = $code;
            }

            if (in_array(0, $statusCodes)) {
                $status = 'OK';
            } else {
                $status = 'ERROR';
            }
            
            return $status;

        } catch (Throwable $th) {
            throw $th;
        }
    }

    /**
     * Crea el registro en UsuarioBono para cada usuario.
     * 
     * @param array  $users             Array de usuarios a los que se les asociará el bono
     * @param object $bonoInterno       Objeto del bono interno
     * @param mixed  $detalleReferidoId Objeto del detalle referido
     * @param mixed  $transaccion       Es la transaccion del commit antes de hacer inserción a la base de datos.
     * @param object $proveedor         Objeto del proveedor
     * 
     * @return array Retorna un array con los ids de los bonos creados.
     * @throws Throwable Si ocurre algún error durante la creación de la relación entre el usuario y el bono.
     */
    private function createUserBonusRelation(array $users, object &$bonoInterno, &$detalleReferidoId, $transaccion, $subProveedorId) :array {
        try {
            $userBonusIds = [];
            foreach ($users as $user) {
                $UsuarioBono = new UsuarioBono();
                $UsuarioBono->setUsuarioId($user);
                $UsuarioBono->setBonoId($bonoInterno->bonoId);
                $UsuarioBono->setValor(0);
                $UsuarioBono->setValorBono(0);
                $UsuarioBono->setValorBase(0);
                $UsuarioBono->setEstado('R');
                $UsuarioBono->setErrorId('0');
                $UsuarioBono->setIdExterno('0');
                $UsuarioBono->setMandante($bonoInterno->mandante);
                $UsuarioBono->setUsucreaId('0');
                $UsuarioBono->setUsumodifId('0');
                $UsuarioBono->setApostado('0');
                $UsuarioBono->setVersion('2');
                $UsuarioBono->setRollowerRequerido('0');
                $UsuarioBono->setCodigo('');
                $UsuarioBono->setExternoId($subProveedorId);
                $UsuarioBono->setUsuidReferido($detalleReferidoId);

                $UsuarioBonoMysqlDAO = new UsuarioBonoMySqlDAO($transaccion);
                if (!empty($detalleReferidoId)) $UsuarioBono->setUsuidReferido($detalleReferidoId);
                $userBonusIds[] = $UsuarioBonoMysqlDAO->insert($UsuarioBono);
            }

            return $userBonusIds;

        } catch (Throwable $th) {
            throw $th;
        }
    }

    /**
     * Segmenta un array en cantidades iguales a '$segmentLength' si es posible, sino, el último
     * segmento del array será la cantidad restante que este en el array original.
     * 
     * @param array $arrayToBeSegmented Es la instancia del array original a segmentar.
     * @param int   $segmentLength      Indica cuantas posiciones debe tener cada array segmentado.
     * 
     * @return array Retorna un array con los segmentos obtenidos.
     * @throws Throwable Si ocurre algún error durante la segmentación del array.
     */
    private function segmentArrayByLimit(array &$arrayToBeSegmented, int $segmentLength) :array {
        try {
            $extractedSegments = [];

            while (count($arrayToBeSegmented) > 0){
                $valueExtrated = array_splice($arrayToBeSegmented, 0, $segmentLength);
                $extractedSegments[] = $valueExtrated;
            }
            
            return $extractedSegments;

        } catch (Throwable $th) {
            throw $th;
        }
    }

    /**
     * Propósito: Agregar un bono gratis
     *
     *  Autor:
     *
     *   Descripción de variables:
     *
     *           - $bonoid: string  Identidad del bono obtenida previamente
     *           - $usuarioId: string Identidad del usario a quien pertenece el bono
     *           - $mandante: string Partner
     *           - $detalles: array con los detalles del bono
     *           - $ejecutarSQL: bool para ejecutar queries relacionadas con subir datos a la base de datos
     *           - $codebonus: string codigo del bono
     *           - $transaccion: string transaccion relacionada a la seguridad del proceso
     *           - $isForCRM = false, bool
     *           - $isForLealtad = false, bool indica si el bono es para lealtad
     *
     *
     * @param String $bonoid
     * @param String $usuarioId
     * @param String $mandante
     * @param String $detalles
     * @param String $ejecutarSQL
     * @param String $codebonus
     * @param String $transaccion
     *
     * @return Array resultado de la consulta
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function agregarBonoFree($bonoid, $usuarioId, $mandante, $detalles, $ejecutarSQL, $codebonus, $transaccion, $isForCRM = false, $isForLealtad = false)
    {
        $Usuario = new Usuario($usuarioId);
        $Subproveedor = new Subproveedor("", "ITN");
        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Subproveedor->subproveedorId, $mandante, $Usuario->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());
        $urlAltenar = $Credentials->URL2;
        $walletCode = $Credentials->WALLET_CODE;

        /** Verificacion de estado de usuario para redencion de bono */
        // Se verifica si el usuario tiene activa la contingencia abusador de bonos
        $UsuarioConfiguracion = new UsuarioConfiguracion();
        $UsuarioConfiguracion = $UsuarioConfiguracion->getUserBondABuser($usuarioId);
        if ($UsuarioConfiguracion->usuconfigId != '' && $UsuarioConfiguracion->usuconfigId != null) {
            $bloqueado = true;
        } else {
            $bloqueado = false;
        }

        $respuesta = array();


        //Verificamos Detalles a tomar en cuenta al momento de verificar si puede ganar un bono

        $detalleValorDeposito = $detalles->ValorDeposito;
        $detallePaisUSER = $detalles->PaisUSER;
        $detalleMonedaUSER = $detalles->MonedaUSER;
        $detalleReferidoId = $detalles->ReferidoId;

        //Inicializamos variables a tener en cuenta
        $cumpleCondiciones = false;
        $bonoElegido = 0;
        $bonoTieneRollower = false;
        $rollowerBono = 0;
        $rollowerDeposito = 0;


        $sqlIsForCRM = "  AND (a.pertenece_crm = '' or a.pertenece_crm IS NULL  or a.pertenece_crm ='N' )  ";
        if ($isForCRM) {
            $sqlIsForCRM = "  AND a.pertenece_crm = 'S' ";

        }

        //Obtenemos todos los bonos disponibles
        //Seleccionamos bono_id,tipo,fecha_inicio,fecha_fin de la tabla bono_interno donde
        //  La mandante debe ser igual a la variable $mandante
        //  Filtramos los registros donde la fecha y hora actual (NOW()) están entre fecha_inicio y fecha_fin.
        //  El estado debe ser igual a 'A'
        //  bono_id debe ser igual a la variable $bono_id
        $sqlBonos = "select a.bono_id,a.tipo,a.fecha_inicio,a.fecha_fin from bono_interno a where a.mandante=" . $mandante . " and now()  between (a.fecha_inicio) and (a.fecha_fin) and  a.estado='A'  and a.bono_id='" . $bonoid . "'";

        $respuesta["sql"] = $sqlBonos;
        //Ejecutamos la SQL
        $bonosDisponibles = $this->execQuery($transaccion, $sqlBonos);

        //Recorremos la tabla "bono_interno" donde se alojan los bonos disponibles y almacenamos variables a
        //tener en cuenta
        foreach ($bonosDisponibles as $bono) {
            $bono->bono_id = $bono->{'a.bono_id'};
            $bono->tipo = $bono->{'a.tipo'};
            $bono->fecha_inicio = $bono->{'a.fecha_inicio'};
            $bono->fecha_fin = $bono->{'a.fecha_fin'};

            //Verificamos condiciones para seguir iterando
            if (!$cumpleCondiciones) {

                //Obtenemos todos los detalles del bono
                //Seleccionamos todos dentro de la tabla bono_detalle, filtrando por bono_id debe ser igual a $bono->bono_id (bono_interno)
                // y moneda debe estar vacio o valer lo que vale la variable $detalleMonedaUser
                $sqlDetalleBono = "select * from bono_detalle a where a.bono_id='" . $bono->bono_id . "' AND (moneda='' OR moneda='" . $detalleMonedaUSER . "') ";

                $bonoDetalles = $this->execQuery($transaccion, $sqlDetalleBono);
                $respuesta["sql2"] = $bono;

                //Inicializamos variables
                $cumpleCondiciones = true;
                $condicionBonoReferente = false;
                $CONDSUBPROVIDER = array();
                $CONDGAME = array();


                $tipobono2 = $bono->tipo;
                $ValorBono = 0;

                // Uso de la nueva funcionalidad
                $detalles = json_decode(json_encode($detalles));
                $validate = $this->validarCondiciones($bonoDetalles, $detalles, '', $usuarioId, $isForLealtad, $cumpleCondiciones, $transaccion, $bono, '');

                foreach ($bonoDetalles as $bonoDetalle) {

                    $bonoDetalle->tipo = $bonoDetalle->{'a.tipo'};
                    $bonoDetalle->valor = $bonoDetalle->{'a.valor'};
                    $bonoDetalle->moneda = $bonoDetalle->{'a.moneda'};


                    switch ($bonoDetalle->tipo) {
                        default:


                            if (stristr($bonoDetalle->tipo, 'CONDSUBPROVIDER')) {

                                $idGame = explode("CONDSUBPROVIDER", $bonoDetalle->tipo)[1];
                                array_push($CONDSUBPROVIDER, $idGame);

                            }

                            if (stristr($bonoDetalle->tipo, 'CONDGAME')) {

                                $idGame = explode("CONDGAME", $bonoDetalle->tipo)[1];
                                array_push($CONDGAME, $idGame);

                            }

                            break;
                    }


                }
                //Variables usadas en el codigo y que provee Validar condiciones
                $valorbono = $validate->valorbono;
                $ValorBono = $validate->ValorBono;
                $tipobono = $validate->tipobono;
                $cumpleCondiciones = $validate->cumpleCondiciones;
                $maximopago = $validate->maximopago;
                $expDia = $validate->expDia;
                $expFecha = $validate->expFecha;
                $bonoTieneRollower = $validate->bonoTieneRollower;
                $rollowerBono = $validate->rollowerBono;
                $rollowerDeposito = $validate->rollowerDeposito;
                $rollowerValor = $validate->rollowerValor;
                $puederepetirBono = $validate->puederepetirBono;
                $ganaBonoId = $validate->ganaBonoId;
                $tiposaldo = $validate->tiposaldo;
                $valor_bono = $validate->valor_bono;
                $bonusPlanIdAltenar = $validate->bonusPlanIdAltenar;
                $bonusCodeAltenar = $validate->bonusCodeAltenar;
                $cantidadCartones = $validate->cantidadCartones;
                $condicionBonoReferente = $validate->condicionBonoReferente;
                $prefix = $validate->prefix;
                $CONDGAME = $validate->CONDGAME;
                $CONDSUBPROVIDER = $validate->CONDSUBPROVIDER;

                // Se verifica si cumple condiciones
                if ($cumpleCondiciones) {
                    //Verificamos si puede repetir bono, si puede guardamos id bono de la tabla bono_interno en bonoElegido
                    if ($puederepetirBono) {

                        $bonoElegido = $bono->bono_id;
                        // si no, seleccionamos de la tabla usuario_bono los datos de usuario según bonoid y usuarioid
                    } else {
                        //Seleccionamos todos de usuario_bono donde bono_id es igual a bono_id desde tabla bono_interno y usuario_id sea igual a $usuarioId
                        $sqlRepiteBono = "select * from usuario_bono a where a.bono_id='" . $bono->bono_id . "' AND a.usuario_id = '" . $usuarioId . "'";
                        $repiteBono = $this->execQuery($transaccion, $sqlRepiteBono);
                        // si no puede repetir bono y el repiteBono esta vacio entonces guardamos id bono de la tabla bono_interno en bonoElegido
                        if ((!$puederepetirBono && oldCount($repiteBono) == 0)) {
                            $bonoElegido = $bono->bono_id;
                            // si no, negamos cumpleCondiciones
                        } else {
                            $cumpleCondiciones = false;
                        }
                    }
                }
            }
        }
        // Inicializamos variables del array respuesta
        $respuesta["Bono"] = 0;
        $respuesta["WinBonus"] = false;
        $SumoSaldo = false;
        $SumoSaldoValor = 0;

        //Si el bonoElegido existe y el tipo de bono existe
        if ($bonoElegido != 0 && $tipobono2 != "") {
            // si bonusPlanIdAltenar y bonusCodeAltenar no estan vacios comenzamos petición de redención con Altenar
            if ($bonusPlanIdAltenar != '' && $bonusCodeAltenar != '' && $tipobono2 != "2") {
                /** Si el usuario se encuentra bloqueado por abusador de bonos se bloquea la redencion del bono */
                if ($bloqueado) {
                    $BonoInterno = new BonoInterno();
                    $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                    VALUES ('{$usuarioId}','BONO','{$bono->bono_id}','ALTENAR','{$tipobono2}','BONDABUSER')";
                    $BonoInterno->execQuery($transaccion, $sqlLog);
                } /** Flujo normal altenar */
                else {
                    // Verificamos mandante y bono_id de tabla bono_interno
                    if ($mandante == '14' && $bonoElegido != '32289') {
                        // Guardamos variables
                        $valorBase = $detalleValorDeposito;
                        $strSql = array();
                        $contSql = 0;
                        $estadoBono = 'A';
                        $rollowerRequerido = 0;
                        $SumoSaldo = false;

                        try {
                            //Determinamos el valor del bono base en porcentaje/valor si el bono es de tipo PORCENTAJE
                            if ($tipobono == "PORCENTAJE") {
                                $valor_bono = ($detalleValorDeposito) * ($valorbono) / 100;
                                //Evitamos desborde bono
                                if ($valor_bono > $maximopago && $maximopago != '0') {
                                    $valor_bono = $maximopago;
                                }
                            } elseif ($tipobono == "VALOR") {

                                $valor_bono = $valorbono;

                            }
                            // Definimos walletCode por Pais de usuario si el mandante  es 0
                            $Mandante = new Mandante($mandante);

                            if ($mandante == '0' && $detallePaisUSER == 60) {
                                $walletCode = "160124";
                            }

                            if ($mandante == '0' && $detallePaisUSER == 2) {
                                $walletCode = "160124";
                            }

                            $IdUsuarioAltenar = $usuarioId;
                            if ((intval($usuarioId) > 73758) || (in_array(intval($usuarioId), array(70363, 72098, 67393, 73497, 57196)))) {
                                $IdUsuarioAltenar = $usuarioId . "U";
                            }

                            // Guardamos cambios en dataD
                            $dataD = array(
                                "ExtUserId" => $IdUsuarioAltenar,
                                "WalletCode" => $walletCode,
                                "BonusPlanId" => $bonusPlanIdAltenar,
                                "Deposit" => intval(floatval($valor_bono) * 100)
                            );

                            $dataD = json_encode($dataD);

                            // Inicializar la clase CurlWrapper
                            $curl = new CurlWrapper($urlAltenar . '/api/Bonus/CreateBonusByDeposit/json');

                            // Configurar opciones
                            $curl->setOptionsArray([
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_ENCODING => '',
                                CURLOPT_MAXREDIRS => 10,
                                CURLOPT_TIMEOUT => 300,
                                CURLOPT_FOLLOWLOCATION => true,
                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                CURLOPT_CUSTOMREQUEST => 'POST',
                                CURLOPT_POSTFIELDS => $dataD,
                                CURLOPT_HTTPHEADER => array(
                                    'Content-Type: application/json'
                                ),
                            ]);

                            // Ejecutar la solicitud
                            $response = $curl->execute();


                        } catch (Exception $e) {

                        }


                        //Guardamos confirmación de bono ganado con su valor y demas variables a usar en redención
                        $respuesta["WinBonus"] = true;
                        $ganoBonoBool = true;
                        $respuesta["SumoSaldo"] = $SumoSaldo;
                        $respuesta["Bono"] = $bonoElegido;
                        $respuesta["Valor"] = $valor_bono;
                        $respuesta["queries"] = $strSql;

                        try {

                            //Enviamos mensaje/notificacion al usuario
                            if ($mandante == 19) {
                                //Configuramos el mensaje para el bono asociado a una campaña o no
                                if ($respuesta['Bono']) {
                                    $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
                                    $BonodetalleM = $BonoDetalleMySqlDAO->querybyBonoIdAndTipo($respuesta['Bono'], 'MARKETINGCAMPAING');
                                    if ($BonodetalleM) {
                                        $UsuarioMandante = new UsuarioMandante('', $usuarioId, $mandante);
                                        //Recorremos la consulta y configuramos parametros a mostrar en mensaje/notificación
                                        foreach ($BonodetalleM as $mensaje) {
                                            $Campaing = new UsuarioMensajecampana($mensaje->valor);

                                            $UsuarioMensaje = new UsuarioMensaje();
                                            $UsuarioMensaje->body = $Campaing->body;
                                            $UsuarioMensaje->msubject = $Campaing->msubject;
                                            $UsuarioMensaje->usufromId = 0;
                                            $UsuarioMensaje->usutoId = $UsuarioMandante->usumandanteId;
                                            $UsuarioMensaje->isRead = 0;
                                            $UsuarioMensaje->msubject = $Campaing->nombre;
                                            $UsuarioMensaje->parentId = $mandante;
                                            $UsuarioMensaje->proveedorId = $mandante;
                                            $UsuarioMensaje->tipo = $Campaing->tipo;
                                            $UsuarioMensaje->paisId = $Campaing->paisId;
                                            $UsuarioMensaje->fechaExpiracion = $Campaing->fechaExpiracion;
                                            $UsuarioMensaje->usumencampanaId = $mensaje->valor;


                                            $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($transaccion);
                                            //Enviamos los parametros para el mensaje a la tabla usuario_mensaje
                                            $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);

                                        }
                                    }
                                }

                            }
                        } catch (Exception $e) {

                        }


                    }

                    if (true || ($mandante == '14' && $bonoElegido == '32289')) {

                        // Guardamos variables
                        $valorBase = $detalleValorDeposito;

                        $strSql = array();
                        $contSql = 0;
                        $estadoBono = 'A';
                        $rollowerRequerido = 0;
                        $SumoSaldo = false;

                        try {
                            //Determinamos el valor del bono base en porcentaje/valor si el bono es de tipo PORCENTAJE
                            if ($tipobono == "PORCENTAJE") {

                                $valor_bono = ($detalleValorDeposito) * ($valorbono) / 100;
                                //Evitamos desborde bono
                                if ($valor_bono > $maximopago) {
                                    $valor_bono = $maximopago;
                                }
                            } elseif ($tipobono == "VALOR") {
                                // Guardamos el valor del bono entero
                                $valor_bono = $valorbono;

                            }
                            // Definimos walletCode por Pais de usuario si el mandante  es 0
                            $Mandante = new Mandante($mandante);

                            if ($mandante == '0' && $detallePaisUSER == 60) {
                                $walletCode = "160124";
                            }

                            if ($mandante == '0' && $detallePaisUSER == 2) {
                                $walletCode = "160124";
                            }

                            // Guardamos cambios en dataD
                            $dataD = array(
                                "ExtUserId" => $usuarioId,
                                "WalletCode" => $walletCode,
                                "BonusCode" => $bonusCodeAltenar,
                                "Deposit" => intval(floatval($valor_bono) * 100)
                            );

                            //Filtramos por id Usuario capturado en CreateBonus
                            $IdUsuarioAltenar = $usuarioId;
                            if ((intval($usuarioId) > 73758) || (in_array(intval($usuarioId), array(70363, 72098, 67393, 73497, 57196)))) {
                                $IdUsuarioAltenar = $usuarioId . "U";
                            }

                            // Guardamos cambios en dataD
                            $dataD = array(
                                "ExtUserId" => $IdUsuarioAltenar,
                                "WalletCode" => $walletCode,
                                "BonusCode" => $bonusCodeAltenar,
                                "Deposit" => intval(floatval($valor_bono) * 100)
                            );

                            $dataD = json_encode($dataD);


                            // Inicializar la clase CurlWrapper
                            $curl = new CurlWrapper($urlAltenar . '/api/Bonus/CreateBonusByCode/json');

                            // Configurar opciones
                            $curl->setOptionsArray([
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_ENCODING => '',
                                CURLOPT_MAXREDIRS => 10,
                                CURLOPT_TIMEOUT => 300,
                                CURLOPT_FOLLOWLOCATION => true,
                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                CURLOPT_CUSTOMREQUEST => 'POST',
                                CURLOPT_POSTFIELDS => $dataD,
                                CURLOPT_HTTPHEADER => array(
                                    'Content-Type: application/json'
                                ),
                            ]);

                            // Ejecutar la solicitud
                            $response = $curl->execute();

                            //Guardamos la respuesta decodificada
                            $response = json_decode($response);


                            //Verificamos respuesta de la ejecución, si la respuesta CreateBonusByCodeMessageResult no está vacia
                            if ($response->CreateBonusByCodeMessageResult != null) {
                                //Y si hay error por cliente no existente
                                if ($response->CreateBonusByCodeMessageResult->Error == 'ClientNotFound') {
                                    //Creamos nuevo usuario con sus parametros
                                    $Usuario = new Usuario($usuarioId);
                                    $Registro = new Registro('', $usuarioId);
                                    $Pais = new Pais($Usuario->paisId);

                                    $Mandante = new Mandante($Usuario->mandante);
                                    $pathPartner = $Mandante->pathItainment;
                                    $pathFixed = $Pais->codigoPath;
                                    $usermoneda = $Usuario->moneda;
                                    $userpath = $pathFixed;

                                    $pathFixed = '2:Web ' . $usermoneda . ',' . $userpath;

                                    //Configuramos la mandante
                                    if ($Mandante->mandante != '') {
                                        if (is_numeric($Mandante->mandante)) {
                                            if (intval($Mandante->mandante) > 2) {

                                                $pathFixed = '2:Web' . $Mandante->mandante . ' ' . $usermoneda . ',' . $Mandante->mandante . $userpath;
                                                if (intval($Mandante->mandante) == 9) {

                                                    $pathFixed = '2:Web' . $Mandante->mandante . ' ' . $usermoneda . ',' . $Mandante->mandante . 'W' . $userpath;

                                                }

                                            }
                                        }
                                    }
                                    //Configuramos Partner si esta vacio
                                    if ($pathPartner == '') {

                                        $pathPartner = "1:colombia,S3";

                                        //Configuramos Partner por mandante
                                        if ($Mandante->mandante == 1) {
                                            $pathPartner = "1:ibet,S1";
                                        }


                                        if ($Mandante->mandante == 2) {
                                            $pathPartner = "1:justbetja,S2";
                                        }


                                        if ($Mandante->mandante == 3) {
                                            $pathPartner = "1:miravalle,S7";
                                        }


                                        if ($Mandante->mandante == 4) {
                                            $pathPartner = "1:casinogranpalacio,S20";
                                        }


                                        if ($Mandante->mandante == 5) {
                                            $pathPartner = "1:casinointercontinental,S9";
                                        }


                                        if ($Mandante->mandante == 6) {
                                            $pathPartner = "1:netabet,S10";
                                        }


                                        if ($Mandante->mandante == 7) {
                                            $pathPartner = "1:casinoastoria,S11";
                                        }


                                        if ($Mandante->mandante == 8) {
                                            $pathPartner = "1:ecuabet,S12";
                                        }

                                        if ($Mandante->mandante == 9) {
                                            $pathPartner = "1:winbet,S13";
                                        }


                                        if ($Mandante->mandante == 0 && $Usuario->paisId == '60') {
                                            $pathPartner = "1:doradobet,S0-60";
                                        }

                                        if ($Mandante->mandante == '0') {
                                            $pathPartner = "1:doradobet,S" . $Mandante->mandante . "-" . $Usuario->paisId;
                                        }
                                        if ($Mandante->mandante == '8') {
                                            $pathPartner = "1:ecuabet,S" . $Mandante->mandante;
                                        }


                                    }


                                    if ($Mandante->mandante == '12') {
                                        $pathPartner = "1:powerbet,S16-" . $Usuario->paisId;
                                    }


                                    if ($Mandante->mandante == '18') {
                                        $pathPartner = "1:gangabet,S22-" . $Usuario->paisId;
                                        //Verificamos pais de usuario para definir Partner
                                        if ($Usuario->paisId == '173') {
                                            $pathPartner = "1:gangabet,S22-" . $Usuario->paisId;
                                        }
                                    }

                                    // Definimos walletCode por Pais de usuario si el mandante  es 0
                                    if ($mandante == '0' && $detallePaisUSER == 60) {
                                        $walletCode = "160124";
                                    }

                                    if ($mandante == '0' && $detallePaisUSER == 2) {
                                        $walletCode = "160124";
                                    }

                                    //Filtramos por id Usuario capturado en CreateBonus
                                    $IdUsuarioAltenar = $Usuario->usuarioId;
                                    if ((intval($usuarioId) > 73758) || (in_array(intval($usuarioId), array(70363, 72098, 67393, 73497, 57196)))) {
                                        $IdUsuarioAltenar = $Usuario->usuarioId . "U";
                                    }

                                    //Almacenamos los cambios en array dataD
                                    $dataD = array(
                                        "ExtUser" => array(
                                            "LoginName" => $Usuario->nombre,
                                            "Currency" => $Usuario->moneda,
                                            "Country" => $Pais->iso,
                                            "ExternalUserId" => $IdUsuarioAltenar,
                                            "AffiliationPath" => $Usuario->getAffiliationPathAltenar(),
                                            "UserCode" => "3",
                                            "FirstName" => $Registro->nombre1,
                                            "LastName" => $Registro->apellido1,
                                            "UserBalance" => "0"),
                                        "WalletCode" => $walletCode
                                    );

                                    $dataD = json_encode($dataD);

                                    // Inicializar la clase CurlWrapper
                                    $curl = new CurlWrapper($urlAltenar . '/api/Bonus/CreateUser/json');

                                    // Configurar opciones
                                    $curl->setOptionsArray([
                                        CURLOPT_RETURNTRANSFER => true,
                                        CURLOPT_ENCODING => '',
                                        CURLOPT_MAXREDIRS => 10,
                                        CURLOPT_TIMEOUT => 300,
                                        CURLOPT_FOLLOWLOCATION => true,
                                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                        CURLOPT_CUSTOMREQUEST => 'POST',
                                        CURLOPT_POSTFIELDS => $dataD,
                                        CURLOPT_HTTPHEADER => array(
                                            'Content-Type: application/json'
                                        ),
                                    ]);

                                    // Ejecutar la solicitud
                                    $response = $curl->execute();
                                    sleep(1);

                                    // Definimos walletCode por Pais de usuario si el mandante  es 0
                                    if ($mandante == '0' && $detallePaisUSER == 60) {
                                        $walletCode = "160124";
                                    }

                                    if ($mandante == '0' && $detallePaisUSER == 2) {
                                        $walletCode = "160124";
                                    }


                                    //Almacenamos datos en array dataD
                                    $dataD = array(
                                        "ExtUserId" => $usuarioId,
                                        "WalletCode" => $walletCode,
                                        "BonusCode" => $bonusCodeAltenar,
                                        "Deposit" => intval(floatval($valor_bono) * 100)
                                    );

                                    $IdUsuarioAltenar = $usuarioId;
                                    if ((intval($usuarioId) > 73758) || (in_array(intval($usuarioId), array(70363, 72098, 67393, 73497, 57196)))) {
                                        $IdUsuarioAltenar = $usuarioId . "U";
                                    }

                                    // Guardamos cambios en dataD
                                    $dataD = array(
                                        "ExtUserId" => $IdUsuarioAltenar,
                                        "WalletCode" => $walletCode,
                                        "BonusCode" => $bonusCodeAltenar,
                                        "Deposit" => intval(floatval($valor_bono) * 100)
                                    );

                                    $dataD = json_encode($dataD);

                                    // Inicializar la clase CurlWrapper
                                    $curl = new CurlWrapper($urlAltenar . '/api/Bonus/CreateBonusByCode/json');

                                    // Configurar opciones
                                    $curl->setOptionsArray([
                                        CURLOPT_RETURNTRANSFER => true,
                                        CURLOPT_ENCODING => '',
                                        CURLOPT_MAXREDIRS => 10,
                                        CURLOPT_TIMEOUT => 300,
                                        CURLOPT_FOLLOWLOCATION => true,
                                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                        CURLOPT_CUSTOMREQUEST => 'POST',
                                        CURLOPT_POSTFIELDS => $dataD,
                                        CURLOPT_HTTPHEADER => array(
                                            'Content-Type: application/json'
                                        ),
                                    ]);

                                    // Ejecutar la solicitud
                                    $response = $curl->execute();


                                }

                            }


                        } catch (Exception $e) {

                        }

                        //Guardamos confirmación de bono ganado con su valor y demas variables a usar
                        $ganoBonoBool = true;
                        $respuesta["WinBonus"] = $ganoBonoBool;
                        $respuesta["WinBonus"] = true;
                        $ganoBonoBool = true;
                        $respuesta["SumoSaldo"] = $SumoSaldo;
                        $respuesta["Bono"] = $bonoElegido;
                        $respuesta["Valor"] = $valor_bono;
                        $respuesta["queries"] = $strSql;

                        try {

                            //Enviamos mensaje/notificacion al usuario
                            if ($mandante == 19) {
                                //Configuramos el mensaje para el bono asociado a una campaña o no
                                if ($respuesta["WinBonus"]) {
                                    $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
                                    $BonodetalleM = $BonoDetalleMySqlDAO->querybyBonoIdAndTipo($respuesta['Bono'], 'MARKETINGCAMPAING');
                                    if ($BonodetalleM) {
                                        $UsuarioMandante = new UsuarioMandante('', $usuarioId, $mandante);
                                        //Recorremos la consulta y configuramos parametros a mostrar en mensaje/notificación
                                        foreach ($BonodetalleM as $mensaje) {
                                            $Campaing = new UsuarioMensajecampana($mensaje->valor);

                                            $UsuarioMensaje = new UsuarioMensaje();
                                            $UsuarioMensaje->body = $Campaing->body;
                                            $UsuarioMensaje->msubject = $Campaing->msubject;
                                            $UsuarioMensaje->usufromId = 0;
                                            $UsuarioMensaje->usutoId = $UsuarioMandante->usumandanteId;
                                            $UsuarioMensaje->isRead = 0;
                                            $UsuarioMensaje->msubject = $Campaing->nombre;
                                            $UsuarioMensaje->parentId = $mandante;
                                            $UsuarioMensaje->proveedorId = $mandante;
                                            $UsuarioMensaje->tipo = $Campaing->tipo;
                                            $UsuarioMensaje->paisId = $Campaing->paisId;
                                            $UsuarioMensaje->fechaExpiracion = $Campaing->fechaExpiracion;
                                            $UsuarioMensaje->usumencampanaId = $mensaje->valor;


                                            $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($transaccion);
                                            //Enviamos los parametros para el mensaje a la tabla usuario_mensaje
                                            $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);

                                        }
                                    }
                                }

                            }
                        } catch (Exception $e) {

                        }
                    }

                    //Asignación bonos adicionales vinculados a bono de Altenar
                    if ($respuesta["WinBonus"] && !empty($ganaBonoId)) {
                        try {
                            $BonoInterno = new BonoInterno($ganaBonoId);

                            //Retirando data específica para el bono vinculado
                            $detalles->CodePromo = null;
                            $responseGanaBonoId = $this->agregarBonoFree($ganaBonoId, $usuarioId, $mandante, $detalles, false, null, $transaccion);
                        } catch (Exception $e) {
                        }
                    }


                    //En caso de brindarse un $codebonus se inactiva el registro
                    if ($respuesta["WinBonus"] && $bonusPlanIdAltenar != '' && !empty($codebonus)) {
                        $rules = [];
                        $rules[] = ['field' => 'usuario_bono.codigo', 'data' => $codebonus, 'op' => 'eq'];
                        $rules[] = ['field' => 'usuario_bono.bono_id', 'data' => $bonoElegido, 'op' => 'eq'];
                        $rules[] = ['field' => 'usuario_bono.estado', 'data' => 'L', 'op' => 'eq'];
                        $filters = ['rules' => $rules, 'groupOp' => 'AND'];

                        $select = 'usuario_bono.usubono_id';
                        $sidx = $select;

                        $UsuarioBonoMySqlDAO = new UsuarioBonoMySqlDAO($transaccion);
                        $UsuarioBono = new UsuarioBono();
                        $bonusCoupon = $UsuarioBono->getUsuarioBonosCustom($select, $sidx, 'DESC', 0, 1, json_encode($filters), true);
                        $bonusCoupon = json_decode($bonusCoupon)->data;

                        foreach ($bonusCoupon as $coupon) {
                            $couponId = $coupon->{'usuario_bono.usubono_id'};
                            $UsuarioBono = new UsuarioBono($couponId);
                            $UsuarioBono->estado = 'I';

                            $UsuarioBonoMySqlDAO->update($UsuarioBono);
                        }
                    }

                }
                // Si no existe Plan de Altenar
            } else {
                //Determinamos el valor del bono base en porcentaje/valor si el bono es de tipo PORCENTAJE
                if ($tipobono == "PORCENTAJE") {
                    $valor_bono = floatval($detalleValorDeposito) * floatval($valorbono) / 100;
                    //Evitamos desborde bono
                    if ($valor_bono > $maximopago) {
                        $valor_bono = $maximopago;
                    }

                } elseif ($tipobono == "VALOR") {
                    // Guardamos el valor del bono entero
                    $valor_bono = $valorbono;
                    $maximopago = $valor_bono;

                }

                //Guardamos variables
                $valorBase = $detalleValorDeposito;

                $strSql = array();
                $contSql = 0;
                $estadoBono = 'A';
                $rollowerRequerido = 0;

                if (!$bonoTieneRollower) {

                    // si el bono tiene rollower
                } else {
                    if ($rollowerDeposito) {
                        //Calculamos valor del deposito del rollower requerido para ganar bono
                        $rollowerRequerido = $rollowerRequerido + ($rollowerDeposito * $detalleValorDeposito);
                    }

                    if ($rollowerBono) {
                        //Calculamos valor del bono del rollower requerido para ganar bono
                        $rollowerRequerido = $rollowerRequerido + ($rollowerBono * $valor_bono);

                    }
                    if ($rollowerValor) {
                        //Calculamos valor del rollower requerido para ganar bono
                        $rollowerRequerido = $rollowerRequerido + ($rollowerValor);


                    }

                }

                $strCodeBonus = "";

                //Determinamos codigo del bono si aplica
                if ($codebonus != "") {
                    $strCodeBonus = " AND a.codigo ='" . $codebonus . "'";

                }

                $fechaExpiracion = " NULL ";

                // Determinamos fecha de expiración si aplica
                if ($expFecha != '') {
                    $fechaExpiracion = "'" . date('Y-m-d H:i:s', strtotime($expFecha)) . "'";

                }
                if ($expDia != '') {
                    $fechaExpiracion = "'" . date('Y-m-d H:i:s', strtotime(' + ' . $expDia . ' days')) . "'";

                }

                //////////////////// Comenzamos a iterar por tipo de bono según tabla bono_interno->tipo ////////////////////////////////////////

                // Tipo de bono "FreeCasino"
                if ($tipobono2 == "5") {
                    /** Si el usuario se encuentra bloqueado por abusador de bonos se bloquea la redencion del bono */
                    if ($bloqueado) {
                        $BonoInterno = new BonoInterno();
                        $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                    VALUES ('{$usuarioId}','BONO','{$bono->bono_id}','FREECASINO','{$tipobono2}','BONDABUSER')";
                        $BonoInterno->execQuery($transaccion, $sqlLog);
                    } /** Flujo normal bonos FreeCasino */
                    else {
                        //Inicializamos variables
                        $ganoBonoBool = false;

                        if ($strCodeBonus != '') {
                            $strCodeBonus .= ' ';
                        }

                        //Seleccionamos usubono_id de las tablas usuario_bono y bono_interno con el mismo bono_id donde
                        // el estado debe ser "L" y el bono_id debe ser el $bonoid ingresado en la función, ademas del
                        // filtro contenido en strCodeBonus (si hay codigo del bono) y limitamos la fila a 1
                        $sqlBonosFree = "select a.usubono_id from usuario_bono a INNER JOIN bono_interno b ON(a.bono_id = b.bono_id) where  a.estado='L' and a.bono_id='" . $bonoid . "'" . $strCodeBonus . " ORDER BY RAND()  LIMIT 1  ";
                        //Obtenemos los bonos Libres
                        $bonosFreeLibres = $this->execQuery($transaccion, $sqlBonosFree);

                        //Recorremos bonos libres
                        foreach ($bonosFreeLibres as $bonoLibre) {
                            //Guardamos la info de la columna usubono_id en $bonoLibre->usubono_id
                            $bonoLibre->usubono_id = $bonoLibre->{'a.usubono_id'};
                            //si la condicion ganoBonoBool es falsa
                            if (!$ganoBonoBool) {
                                //si la transaccion no esta vacia, hacemos un select de todos desde la tabla usuario_bono
                                //donde usubono_id coincide con el valor de usubono_id desde la consulta SQL bonosFreeLibres y estado sea = L.
                                //FOR UPDATE bloquea las filas seleccionadas hasta que la transaccion se complete
                                $bonoLibreLocked = $this->execQuery($transaccion, "SELECT * FROM usuario_bono WHERE usubono_id={$bonoLibre->usubono_id} AND estado = 'L' FOR UPDATE");
                                if (oldCount($bonoLibreLocked) <= 0) {
                                    continue;
                                }

                                //Actualizamos la tabla usuario_bono,con los filtros:
                                // Filas donde "usuario_id" es '0'
                                // Filas donde "estado" es 'L'.
                                // Filas donde usubono_id coincide con el valor de $bonoLibre->usubono_id.
                                //Las actalizaciones son las siguientes
                                //a.usuario_id: Se establece con el valor de la variable  $usuarioId.
                                //a.valor_base: Se establece con el valor de la variable  $ValorBono.
                                //a.fecha_crea: Se establece con la fecha y hora actual (date('Y-m-d H:i:s')).
                                //a.fecha_expiracion: Se establece con el valor de la variable  $fechaExpiracion.
                                //a.estado: Se establece como 'A'.

                                $sqlstr = "UPDATE usuario_bono a SET a.usuario_id='" . $usuarioId . "',a.valor_base = '" . $ValorBono . "',a.fecha_crea = '" . date('Y-m-d H:i:s') . "',a.fecha_expiracion = " . $fechaExpiracion . ",a.estado='A' WHERE a.usuario_id='0' AND a.estado='L' AND a.usubono_id='" . $bonoLibre->usubono_id . "'";
                                //Se ejecuta la SQL
                                $q = $this->execUpdate($transaccion, $sqlstr);
                                //Si la ejecución se logra
                                if ($q > 0) {
                                    //activamos gano bono
                                    $ganoBonoBool = true;

                                } else {
                                    //desactivamos gano bono
                                    $ganoBonoBool = false;

                                }
                            }


                            /** Redención de bono linkeado al FreeCasino */
                            if ($ganoBonoBool && !empty($ganaBonoId)) {
                                try {
                                    $BonoInterno = new BonoInterno($ganaBonoId);

                                    //Retirando data específica para el bono vinculado
                                    $detalles->CodePromo = null;
                                    $responseGanaBonoId = $this->agregarBonoFree($ganaBonoId, $usuarioId, $mandante, $detalles, false, null, $transaccion);
                                } catch (Exception $e) {
                                }
                            }
                        }
                    }
                    // Tipo de bono "FreeSpin"
                } elseif ($tipobono2 == "8") {
                    /** Si el usuario se encuentra bloqueado por abusador de bonos se bloquea la redencion del bono */
                    if ($bloqueado) {
                        $BonoInterno = new BonoInterno();
                        $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                    VALUES ('{$usuarioId}','BONO','{$bono->bono_id}','FREESPIN','{$tipobono2}','BONDABUSER')";
                        $BonoInterno->execQuery($transaccion, $sqlLog);
                    } /** Flujo normal bonos FreeSpin */
                    else {
                        //Inicializamos variables
                        $ganoBonoBool = false;
                        //Se genera un LOG
                        syslog(LOG_WARNING, " ENTRODARBONO :ANTES " . $bonoid . " " . json_encode($CONDSUBPROVIDER));

                        //solicitamos un subproveedor con el guardado en $CONDSUBPROVIDER[0]
                        $Subproveedor = new Subproveedor($CONDSUBPROVIDER[0]);
                        //Se genera un LOG
                        syslog(LOG_WARNING, " ENTRODARBONO :" . " " . $Subproveedor->subproveedorId);
                        //Si el subproveedor esta entre los subproveedores aceptados y este es diferente a IESGAMES
                        if (in_array($Subproveedor->subproveedorId, $CONDSUBPROVIDER) && $Subproveedor->abreviado != "IESGAMES") {
                            syslog(LOG_WARNING, " ENTRODARBONO ANTES 1 :" . " " . $usuarioId);
                            syslog(LOG_WARNING, " ENTRODARBONO DESPUES 1 :" . " " . $Subproveedor->subproveedorId);
                            //Generamos una instancia de la clase BonoInterno con id de bono
                            $Proveedor = new Proveedor($Subproveedor->proveedorId);
                            $BonoInterno = new BonoInterno($bonoid);
                            //Usamos una de las instancias de la clase para verificar si ha ganado un bono
                            syslog(LOG_WARNING, " ENTRODARBONO DESPUES 2 :" . " " . $Subproveedor->subproveedorId);
                            $responseBonoGlobal = $this->bonoGlobal($Proveedor, $bonoElegido, $CONDGAME, $BonoInterno->mandante, $usuarioId, $transaccion, $detalleValorDeposito, false, $detalleReferidoId, $BonoInterno->nombre, $prefix, "");
                            //Verificamos si ganó un bono si ganó activamos ganoBonoBool
                            if ($responseBonoGlobal["status"] == 'OK' && $responseBonoGlobal["ganoBonoBool"]) $ganoBonoBool = true;
                            //Seleccionamos el bono elegido
                            $bonoElegido = $responseBonoGlobal["bonoElegido"];

                            //Condicion para solo IESGAMES
                        } else if ($Subproveedor->abreviado == "IESGAMES") {

                            //Negamos ganoBonoBool
                            $ganoBonoBool = false;

                            //Seleccionamos  usubono_id y usuario_id de las tablas usuario_bono y bono_interno con el mismo bono_id donde
                            // el estado debe ser "L" y el bono_id debe ser el $bonoid ingresado en la función, ademas del
                            // filtro contenido en strCodeBonus (si hay codigo del bono) y limitamos la fila a 1

                            $sqlBonosFree = "select a.usubono_id, a.usuario_id from usuario_bono a INNER JOIN bono_interno b ON(a.bono_id = b.bono_id) where  a.estado='L' and a.bono_id='" . $bonoid . "'" . $strCodeBonus;

                            //Ejecutamos la SQL
                            $bonosFreeLibres = $this->execQuery($transaccion, $sqlBonosFree);
                            //Recorremos bonos libres

                            foreach ($bonosFreeLibres as $bonoLibre) {
                                //Guardamos la info de la columna usubono_id en $bonoLibre->usubono_id
                                $bonoLibre->usubono_id = $bonoLibre->{'a.usubono_id'};
                                //si la condicion ganoBonoBool es falsa
                                if (!$ganoBonoBool) {
                                    //si la transaccion no esta vacia, hacemos un select de todos desde la tabla usuario_bono
                                    //donde usubono_id coincide con el valor de usubono_id desde la consulta SQL bonosFreeLibres y estado sea = L.
                                    //FOR UPDATE bloquea las filas seleccionadas hasta que la transaccion se complete
                                    $bonoLibreLocked = $this->execQuery($transaccion, "SELECT * FROM usuario_bono WHERE usubono_id={$bonoLibre->usubono_id} AND estado = 'L' FOR UPDATE");
                                    if (oldCount($bonoLibreLocked) <= 0) {
                                        continue;
                                    }
                                    // if usuario_id en la consulta es cero
                                    if ($bonoLibre->{'a.usuario_id'} == '0') {

                                        //Actualizamos la tabla usuario_bono,con los filtros:
                                        // Filas donde "usuario_id" es '0'
                                        // Filas donde "estado" es 'L'.
                                        // Filas donde usubono_id coincide con el valor de $bonoLibre->usubono_id.
                                        //Las actalizaciones son las siguientes
                                        //a.usuario_id: Se establece con el valor de la variable  $usuarioId.
                                        //a.fecha_crea: Se establece con la fecha y hora actual (date('Y-m-d H:i:s')).
                                        //a.estado: Se establece como 'R'

                                        $sqlstr = "UPDATE usuario_bono a SET a.usuario_id='" . $usuarioId . "',a.fecha_crea = '" . date('Y-m-d H:i:s') . "', a.estado='R' WHERE a.usuario_id='0' AND a.estado='L' AND a.usubono_id='" . $bonoLibre->usubono_id . "'";
                                    } else {

                                        //Actualizamos la tabla usuario_bono,con los filtros:
                                        // Filas donde "usuario_id" es $usuarioId
                                        // Filas donde "estado" es 'L'.
                                        // Filas donde usubono_id coincide con el valor de $bonoLibre->usubono_id.
                                        //Las actalizaciones son las siguientes
                                        //a.usuario_id: Se establece con el valor de la variable  $usuarioId.
                                        //a.fecha_crea: Se establece con la fecha y hora actual (date('Y-m-d H:i:s')).
                                        //a.estado: Se establece como 'R'

                                        $sqlstr = "UPDATE usuario_bono a SET a.usuario_id='" . $usuarioId . "',a.fecha_crea = '" . date('Y-m-d H:i:s') . "', a.estado='R' WHERE a.usuario_id='$usuarioId' AND a.estado='L' AND a.usubono_id='" . $bonoLibre->usubono_id . "'";
                                    }
                                    //Se ejecuta la SQL
                                    $q = $this->execUpdate($transaccion, $sqlstr);
                                    //Si la ejecución se logra
                                    if ($q > 0) {
                                        //activamos gano bono
                                        $ganoBonoBool = true;

                                    } else {
                                        //desactivamos gano bono
                                        $ganoBonoBool = false;

                                    }

                                }
                            }
                            //Agregamos a respuesta $cantidadCartones
                            $respuesta["CantidadCarton"] = $cantidadCartones;

                        }
                    }
                    // Tipo de bono "FreeBet"
                } elseif ($tipobono2 == "6") {
                    /** Si el usuario se encuentra bloqueado por abusador de bonos se bloquea la redencion del bono */
                    if ($bloqueado) {
                        $BonoInterno = new BonoInterno();
                        $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                    VALUES ('{$usuarioId}','BONO','{$bono->bono_id}','FREEBET','{$tipobono2}','BONDABUSER')";
                        $BonoInterno->execQuery($transaccion, $sqlLog);
                    } /** Flujo normal bonos FreeBet */
                    else {
                        $ValorBonoSTR = '';
                        //Nagamos ganoBonoBool
                        $ganoBonoBool = false;

                        //Verificaciones con variables de entorno
                        if ($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null) {
                            $connOriginal = $_ENV["connectionGlobal"]->getConnection();


                            $connDB5 = null;


                            if ($_ENV['ENV_TYPE'] == 'prod') {

                                $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()
                                    , array(
                                        PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-bundle.crt',
                                        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
                                    )
                                );
                            } else {

                                $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()

                                );
                            }

                            $connDB5->exec("set names utf8");

                            try {

                                if ($_ENV["TIMEZONE"] != null && $_ENV["TIMEZONE"] != '') {
                                    $connDB5->exec('SET time_zone = "' . $_ENV["TIMEZONE"] . '";');
                                }

                                if ($_ENV["NEEDINSOLATIONLEVEL"] != null && $_ENV["NEEDINSOLATIONLEVEL"] == '1') {
                                    $connDB5->exec('SET TRANSACTION ISOLATION LEVEL READ COMMITTED;');
                                }
                                if ($_ENV["ENABLEDSETLOCKWAITTIMEOUT"] != null && $_ENV["ENABLEDSETLOCKWAITTIMEOUT"] == '1') {
                                    // $connDB5->exec('SET SESSION innodb_lock_wait_timeout = 5;');
                                }
                                if ($_ENV["ENABLEDSETMAX_EXECUTION_TIME"] != null && $_ENV["ENABLEDSETMAX_EXECUTION_TIME"] == '1') {
                                    // $connDB5->exec('SET SESSION MAX_EXECUTION_TIME = 120000;');
                                }
                                if ($_ENV["DBNEEDUTF8"] != null && $_ENV["DBNEEDUTF8"] == '1') {
                                    $connDB5->exec("SET NAMES utf8mb4");
                                }
                            } catch (\Exception $e) {

                            }
                            $_ENV["connectionGlobal"]->setConnection($connDB5);
                            $transaccion->setConnection($_ENV["connectionGlobal"]);

                        }

                        //Seleccionamos usubono_id de las tablas usuario_bono y bono_interno con el mismo bono_id donde
                        // el estado debe ser "L" y el bono_id debe ser el $bonoid ingresado en la función, ademas del
                        // filtro contenido en strCodeBonus (si hay codigo del bono) y limitamos la fila a 1

                        $sqlBonosFree = "select a.usubono_id from usuario_bono a INNER JOIN bono_interno b ON(a.bono_id = b.bono_id) where  a.estado='L' and a.bono_id='" . $bonoid . "'" . $strCodeBonus . " ORDER BY RAND() LIMIT 1  ";
                        //Obtenemos los bonos Libres
                        $bonosFreeLibres = $this->execQuery($transaccion, $sqlBonosFree);

                        //Verificaciones con variables de entorno
                        if ($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null) {
                            $connDB5 = null;
                            $_ENV["connectionGlobal"]->setConnection($connOriginal);
                            $transaccion->setConnection($_ENV["connectionGlobal"]);
                        }
                        //Recorremos bonos libres
                        foreach ($bonosFreeLibres as $bonoLibre) {
                            //Guardamos la info de la columna usubono_id en $bonoLibre->usubono_id
                            $bonoLibre->usubono_id = $bonoLibre->{'a.usubono_id'};
                            //si la condicion ganoBonoBool es falsa
                            if (!$ganoBonoBool) {
                                //si la transaccion no esta vacia, reiniciamos variables
                                $ValorBonoSTR2 = '';
                                $ValorReferido = '';
                                if ($ValorBonoSTR != '') {
                                    //Asignamos valor en string a $ValorBonoSTR2 con $ValorBonoSTR para SQL
                                    $ValorBonoSTR2 = ', apostado=' . $ValorBonoSTR;
                                }
                                if ($condicionBonoReferente) {
                                    //Asignamos id usuario referido en string a $ValorReferido con $detalleReferidoId para SQL
                                    $ValorReferido = ', a.usuid_referido=' . $detalleReferidoId;
                                }
                                //Hacemos un select de todos desde la tabla usuario_bono
                                //donde usubono_id coincide con el valor de usubono_id desde la consulta SQL bonosFreeLibres y estado sea = L.
                                //FOR UPDATE bloquea las filas seleccionadas hasta que la transaccion se complete
                                $bonoLibreLocked = $this->execQuery($transaccion, "SELECT * FROM usuario_bono WHERE usubono_id={$bonoLibre->usubono_id} AND estado = 'L' FOR UPDATE");
                                if (oldCount($bonoLibreLocked) <= 0) {
                                    continue;
                                }

                                //Actualizamos la tabla usuario_bono,con los filtros:
                                // Filas donde "usuario_id" es '0'
                                // Filas donde "estado" es 'L'.
                                // Filas donde usubono_id coincide con el valor de $bonoLibre->usubono_id.
                                //Las actalizaciones son las siguientes
                                //a.usuario_id: Se establece con el valor de la variable  $usuarioId.
                                //a.fecha_crea: Se establece con la fecha y hora actual (date('Y-m-d H:i:s')).
                                //a.fecha_expiracion: Se establece con el valor de la variable  $fechaExpiracion.
                                //a.estado: Se establece como 'A'.
                                //a.apostado: Se establece con el valor de la variable $ValorBonoSTR ($ValorBonoSTR2)
                                //a.usuid_referido: Se establece con el valor de la variable $detalleReferidoId ($ValorReferido)

                                $sqlstr = "UPDATE usuario_bono a SET a.usuario_id='" . $usuarioId . "',a.fecha_crea = '" . date('Y-m-d H:i:s') . "',a.fecha_expiracion = " . $fechaExpiracion . ", a.estado='A'" . $ValorBonoSTR2 . " " . $ValorReferido . " WHERE a.usuario_id='0' AND a.estado='L' AND a.usubono_id='" . $bonoLibre->usubono_id . "'";

                                //Se ejecuta la SQL
                                $q = $this->execUpdate($transaccion, $sqlstr);
                                //Si la ejecución se logra
                                if ($q > 0) {
                                    //activamos gano bono
                                    $ganoBonoBool = true;

                                    //Desactivamos gano bono
                                } else {
                                    $ganoBonoBool = false;

                                }
                            }


                            /** Redención de bono linkeado al FreeBet */
                            if ($ganoBonoBool && !empty($ganaBonoId)) {
                                try {
                                    $BonoInterno = new BonoInterno($ganaBonoId);

                                    //Retirando data específica para el bono vinculado
                                    $detalles->CodePromo = null;
                                    $responseGanaBonoId = $this->agregarBonoFree($ganaBonoId, $usuarioId, $mandante, $detalles, false, null, $transaccion);
                                } catch (Exception $e) {
                                }
                            }
                        }

                    }

                    // Tipo de bono "Deposito"
                } elseif ($tipobono2 == "2") {
                    /** Si el usuario se encuentra bloqueado por abusador de bonos se bloquea la redencion del bono */
                    if ($bloqueado) {
                        $BonoInterno = new BonoInterno();
                        $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                    VALUES ('{$usuarioId}','BONO','{$bono->bono_id}','DEPOSITO','{$tipobono2}','BONDABUSER')";
                        $BonoInterno->execQuery($transaccion, $sqlLog);
                    } /** Flujo normal bonos Deposito */
                    else {
                        //desactivamos gano bono
                        $ganoBonoBool = false;

                        //Verificaciones con variables de entorno
                        if ($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null) {
                            $connOriginal = $_ENV["connectionGlobal"]->getConnection();


                            $connDB5 = null;


                            if ($_ENV['ENV_TYPE'] == 'prod') {

                                $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()
                                    , array(
                                        PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-bundle.crt',
                                        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
                                    )
                                );
                            } else {

                                $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()

                                );
                            }

                            $connDB5->exec("set names utf8");

                            try {

                                if ($_ENV["TIMEZONE"] != null && $_ENV["TIMEZONE"] != '') {
                                    $connDB5->exec('SET time_zone = "' . $_ENV["TIMEZONE"] . '";');
                                }

                                if ($_ENV["NEEDINSOLATIONLEVEL"] != null && $_ENV["NEEDINSOLATIONLEVEL"] == '1') {
                                    $connDB5->exec('SET TRANSACTION ISOLATION LEVEL READ COMMITTED;');
                                }
                                if ($_ENV["ENABLEDSETLOCKWAITTIMEOUT"] != null && $_ENV["ENABLEDSETLOCKWAITTIMEOUT"] == '1') {
                                    // $connDB5->exec('SET SESSION innodb_lock_wait_timeout = 5;');
                                }
                                if ($_ENV["ENABLEDSETMAX_EXECUTION_TIME"] != null && $_ENV["ENABLEDSETMAX_EXECUTION_TIME"] == '1') {
                                    // $connDB5->exec('SET SESSION MAX_EXECUTION_TIME = 120000;');
                                }
                                if ($_ENV["DBNEEDUTF8"] != null && $_ENV["DBNEEDUTF8"] == '1') {
                                    $connDB5->exec("SET NAMES utf8mb4");
                                }
                            } catch (\Exception $e) {

                            }
                            $_ENV["connectionGlobal"]->setConnection($connDB5);
                            $transaccion->setConnection($_ENV["connectionGlobal"]);

                        }

                        //Seleccionamos usubono_id de las tablas usuario_bono y bono_interno con el mismo bono_id donde
                        // el estado debe ser "L" y el bono_id debe ser el $bonoid ingresado en la función, ademas del
                        // filtro contenido en strCodeBonus (si hay codigo del bono) y limitamos la fila a 1
                        $sqlBonosFree = "select a.usubono_id from usuario_bono a INNER JOIN bono_interno b ON(a.bono_id = b.bono_id) where  a.estado='L' and a.bono_id='" . $bonoid . "'" . $strCodeBonus . " ORDER BY RAND() LIMIT 1  ";
                        //Obtenemos los bonos Libres
                        $bonosFreeLibres = $this->execQuery($transaccion, $sqlBonosFree);

                        //Verificaciones con variables de entorno
                        if ($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null) {
                            $connDB5 = null;
                            $_ENV["connectionGlobal"]->setConnection($connOriginal);
                            $transaccion->setConnection($_ENV["connectionGlobal"]);
                        }

                        //Recorremos bonos libres
                        foreach ($bonosFreeLibres as $bonoLibre) {
                            //Guardamos la info de la columna usubono_id en $bonoLibre->usubono_id
                            $bonoLibre->usubono_id = $bonoLibre->{'a.usubono_id'};
                            //si la condicion ganoBonoBool es falsa
                            if (!$ganoBonoBool) {
                                //si la transaccion no esta vacia, hacemos un select de todos desde la tabla usuario_bono
                                //donde usubono_id coincide con el valor de usubono_id desde la consulta SQL bonosFreeLibres y estado sea = L.
                                //FOR UPDATE bloquea las filas seleccionadas hasta que la transaccion se complete
                                $bonoLibreLocked = $this->execQuery($transaccion, "SELECT * FROM usuario_bono WHERE usubono_id={$bonoLibre->usubono_id} AND estado = 'L' FOR UPDATE");
                                if (oldCount($bonoLibreLocked) <= 0) {
                                    continue;
                                }

                                //Actualizamos la tabla usuario_bono,con los filtros:
                                // Filas donde "usuario_id" es '0'
                                // Filas donde "estado" es 'L'.
                                // Filas donde usubono_id coincide con el valor de $bonoLibre->usubono_id.
                                //Las actalizaciones son las siguientes
                                //a.usuario_id: Se establece con el valor de la variable  $usuarioId.
                                //a.fecha_crea: Se establece con la fecha y hora actual (date('Y-m-d H:i:s')).
                                //a.fecha_expiracion: Se establece con el valor de la variable  $fechaExpiracion.
                                //a.estado: Se establece como 'P'.

                                $sqlstr = "UPDATE usuario_bono a SET a.usuario_id='" . $usuarioId . "',a.fecha_crea = '" . date('Y-m-d H:i:s') . "',a.fecha_expiracion = " . $fechaExpiracion . ", a.estado='P' WHERE a.usuario_id='0' AND a.estado='L' AND a.usubono_id='" . $bonoLibre->usubono_id . "'";
                                //Se ejecuta la SQL
                                $q = $this->execUpdate($transaccion, $sqlstr);
                                //Si se generó la SQL
                                if ($q > 0) {
                                    //activamos gano bono
                                    $ganoBonoBool = true;

                                } else {
                                    //desactivamos gano bono
                                    $ganoBonoBool = false;

                                }
                            }
                        }
                    }
                    // Tipo de bono "No Deposito"
                } elseif ($tipobono2 == "3") {
                    /** Si el usuario se encuentra bloqueado por abusador de bonos se bloquea la redencion del bono */
                    if ($bloqueado) {
                        $BonoInterno = new BonoInterno();
                        $sqlLog = "INSERT INTO log_rechazo_STB (usuario_id,tipo,tipo_id,transaccion,transaccion_id,descripcion)
                                    VALUES ('{$usuarioId}','BONO','{$bono->bono_id}','NODEPOSITO','{$tipobono2}','BONDABUSER')";
                        $BonoInterno->execQuery($transaccion, $sqlLog);
                    } /** Flujo normal bonos No Deposito */
                    else {
                        //Igualamos el valor del bono al maximo pago del mismo
                        $valor_bono = $maximopago;
                        //Desactivamos ganoBonoBool
                        $ganoBonoBool = false;

                        //Verificaciones con variables de entorno
                        if ($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null) {
                            $connOriginal = $_ENV["connectionGlobal"]->getConnection();


                            $connDB5 = null;


                            if ($_ENV['ENV_TYPE'] == 'prod') {

                                $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()
                                    , array(
                                        PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-bundle.crt',
                                        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
                                    )
                                );
                            } else {

                                $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()

                                );
                            }

                            $connDB5->exec("set names utf8");

                            try {

                                if ($_ENV["TIMEZONE"] != null && $_ENV["TIMEZONE"] != '') {
                                    $connDB5->exec('SET time_zone = "' . $_ENV["TIMEZONE"] . '";');
                                }

                                if ($_ENV["NEEDINSOLATIONLEVEL"] != null && $_ENV["NEEDINSOLATIONLEVEL"] == '1') {
                                    $connDB5->exec('SET TRANSACTION ISOLATION LEVEL READ COMMITTED;');
                                }
                                if ($_ENV["ENABLEDSETLOCKWAITTIMEOUT"] != null && $_ENV["ENABLEDSETLOCKWAITTIMEOUT"] == '1') {
                                    // $connDB5->exec('SET SESSION innodb_lock_wait_timeout = 5;');
                                }
                                if ($_ENV["ENABLEDSETMAX_EXECUTION_TIME"] != null && $_ENV["ENABLEDSETMAX_EXECUTION_TIME"] == '1') {
                                    // $connDB5->exec('SET SESSION MAX_EXECUTION_TIME = 120000;');
                                }
                                if ($_ENV["DBNEEDUTF8"] != null && $_ENV["DBNEEDUTF8"] == '1') {
                                    $connDB5->exec("SET NAMES utf8mb4");
                                }
                            } catch (\Exception $e) {

                            }
                            $_ENV["connectionGlobal"]->setConnection($connDB5);
                            $transaccion->setConnection($_ENV["connectionGlobal"]);

                        }

                        //Seleccionamos usubono_id de las tablas usuario_bono y bono_interno con el mismo bono_id donde
                        // el estado debe ser "L" y el bono_id debe ser el $bonoid ingresado en la función, ademas del
                        // filtro contenido en strCodeBonus (si hay codigo del bono) y limitamos la fila a 1
                        $sqlBonosFree = "select a.usubono_id from usuario_bono a INNER JOIN bono_interno b ON(a.bono_id = b.bono_id) where  a.estado='L' and a.bono_id='" . $bonoid . "'" . $strCodeBonus . " ORDER BY RAND() LIMIT 1  ";
                        //Obtenemos los bonos Libres
                        $bonosFreeLibres = $this->execQuery($transaccion, $sqlBonosFree);

                        //Verificaciones con variables de entorno
                        if ($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null) {
                            $connDB5 = null;
                            $_ENV["connectionGlobal"]->setConnection($connOriginal);
                            $transaccion->setConnection($_ENV["connectionGlobal"]);
                        }
                        //Recorremos bonos libres
                        foreach ($bonosFreeLibres as $bonoLibre) {
                            //Guardamos la info de la columna usubono_id en $bonoLibre->usubono_id
                            $bonoLibre->usubono_id = $bonoLibre->{'a.usubono_id'};
                            //si la condicion ganoBonoBool es falsa
                            if (!$ganoBonoBool) {
                                if (!$bonoTieneRollower) {
                                    //Si bono no tiene Rollower
                                    $estadoBono = 'R';
                                } else {
                                    if ($rollowerDeposito) {
                                        //$rollowerRequerido = $rollowerRequerido + ($rollowerDeposito * $detalleValorDeposito);
                                    }

                                    if ($rollowerBono) {
                                        //$rollowerRequerido = $rollowerRequerido + ($rollowerBono * $valor_bono);

                                    }
                                    if ($rollowerValor) {
                                        //$rollowerRequerido = $rollowerRequerido + ($rollowerValor);


                                    }

                                }

                                //si la transaccion no esta vacia, hacemos un select de todos desde la tabla usuario_bono
                                //donde usubono_id coincide con el valor de usubono_id desde la consulta SQL bonosFreeLibres y estado sea = L.
                                //FOR UPDATE bloquea las filas seleccionadas hasta que la transaccion se complete
                                $bonoLibreLocked = $this->execQuery($transaccion, "SELECT * FROM usuario_bono WHERE usubono_id={$bonoLibre->usubono_id} AND estado = 'L' FOR UPDATE");
                                if (oldCount($bonoLibreLocked) <= 0) {
                                    continue;
                                }

                                //Actualizamos la tabla usuario_bono,con los filtros:
                                // Filas donde "usuario_id" es '0'
                                // Filas donde "estado" es 'L'.
                                // Filas donde usubono_id coincide con el valor de $bonoLibre->usubono_id.
                                //Las actalizaciones son las siguientes
                                //a.usuario_id: Se establece con el valor de la variable  $usuarioId.
                                //a.fecha_crea: Se establece con la fecha y hora actual (date('Y-m-d H:i:s')).
                                //,a.rollower_requerido: Se establece con el valor de la variable $rollowerRequerido
                                //a.valor_bono: Se establece con el valor de la variable $valor_bono
                                //a.valor: Se establece con el valor de la variable $valor_bono
                                //a.estado: Se establece con el valor de la variable $estadoBono.
                                //a.fecha_expiracion: Se establece con el valor de la variable  $fechaExpiracion.


                                $sqlstr = "UPDATE usuario_bono a SET a.usuario_id='" . $usuarioId . "',a.fecha_crea = '" . date('Y-m-d H:i:s') . "',a.rollower_requerido='" . $rollowerRequerido . "',a.valor_bono='" . $valor_bono . "',a.valor='" . $valor_bono . "', a.estado='" . $estadoBono . "',a.fecha_expiracion = " . $fechaExpiracion . " WHERE a.usuario_id='0' AND a.estado='L' AND a.usubono_id='" . $bonoLibre->usubono_id . "'";

                                //Se ejecuta la SQL
                                $q = $this->execUpdate($transaccion, $sqlstr);
                                //Si se generó la SQL
                                if ($q > 0) {

                                    //Insertamos registros para seguimiento en la tabla  bono_log,con los filtros:
                                    // Filas donde "usubono_id" es $bonoLibre->usubono_id
                                    // Filas donde "apostado" es mayor o igual a a.rollower_requerido.
                                    // Ademas de la unión de usuario_bono con bono_interno donde bono_id = bono_id
                                    //
                                    //Las actalizaciones se haran en las columnas
                                    //   usuario_id, tipo, valor, estado, id_externo, mandante, transaccion_id, tipobono_id, fecha_crea, fecha_cierre
                                    //
                                    // Se harán de la siguiente manera
                                    // usuario_id
                                    //   Seleccionamos "usuario_id" de la tabla "usuario_bono"
                                    // tipo
                                    //   si tipo = 2 entonces devuelve D (Deposito)
                                    //   si tipo = 3 entonces devuelve ND (No Deposito)
                                    //   en cualquier otro caso devuelve F
                                    // valor
                                    //   Seleccionamos "valor" de la tabla "usuario_bono".
                                    // estado
                                    //   Establecemos valor 'L'
                                    // id_externo
                                    //   Seleccionamos "usubono_id" de la tabla "usuario_bono".
                                    // mandante
                                    //   Establecemos valor '0'
                                    // transaccion_id
                                    //   Establecemos valor '0'
                                    // tipobono_id
                                    //   Establecemos valor '4'
                                    // fecha_crea
                                    //   Establecemos la fecha y hora actual
                                    // fecha_cierre
                                    //   Establecemos la fecha y hora actual


                                    $sqlstr = "INSERT INTO bono_log (usuario_id,tipo,valor,estado,id_externo,mandante,transaccion_id,tipobono_id,fecha_crea,fecha_cierre) SELECT a.usuario_id,CASE WHEN b.tipo = 2 THEN 'D' WHEN b.tipo = 3 THEN 'ND' ELSE 'F' END,a.valor,'L',a.usubono_id,0,'0',4,now(),now()  FROM  usuario_bono a INNER JOIN bono_interno  b ON (b.bono_id = a.bono_id)  WHERE a.usubono_id = " . $bonoLibre->usubono_id . " AND a.apostado >= a.rollower_requerido";

                                    //Se ejecuta la SQL
                                    $q = $this->execUpdate($transaccion, $sqlstr);

                                    //Si bono no tiene rollower
                                    if (!$bonoTieneRollower) {
                                        // si no ha ganado bono
                                        if ($ganaBonoId == 0) {
                                            //iteramos sobre tiposaldo de la columna "valor" de la tabla bono_detalle
                                            switch ($tiposaldo) {
                                                case 0:
                                                    //Actualizamos la tabla registro,con los filtros:
                                                    // Filas donde "mandante" es $mandante
                                                    // Filas donde "usuario_id" es $usuarioId
                                                    //Las actalizaciones son las siguientes
                                                    //creditos_base_ant: Se establece con el valor  creditos_base.
                                                    //creditos_base = creditos_base + " . $valor_bono . ": Incrementa el valor de creditos_base sumando el valor de la variable  $valor_bono.
                                                    $sqlstr = "update registro set creditos_base_ant=creditos_base,creditos_base=creditos_base+" . $valor_bono . " where mandante=" . $mandante . " and usuario_id=" . $usuarioId;
                                                    //Se ejecuta la SQL
                                                    $q = $this->execUpdate($transaccion, $sqlstr);

                                                    //Reescribimos variables
                                                    $estadoBono = 'R';
                                                    $SumoSaldo = true;
                                                    $SumoSaldoValor = $valor_bono;

                                                    break;

                                                case 1:

                                                    //Actualizamos la tabla registro,con los filtros:
                                                    // Filas donde "mandante" es $mandante
                                                    // Filas donde "usuario_id" es $usuarioId
                                                    //Las actalizaciones son las siguientes
                                                    //creditos_ant: Se establece con el valor  creditos.
                                                    //creditos= creditos + " . $valor_bono . ": Incrementa el valor de creditos sumando el valor de la variable  $valor_bono.


                                                    $sqlstr = "update registro set creditos_ant=creditos,creditos=creditos+" . $valor_bono . " where mandante=" . $mandante . " and usuario_id=" . $usuarioId;

                                                    //Se ejecuta la SQL
                                                    $q = $this->execUpdate($transaccion, $sqlstr);

                                                    //Reescribimos variables
                                                    $estadoBono = 'R';
                                                    $SumoSaldo = true;
                                                    $SumoSaldoValor = $valor_bono;

                                                    break;

                                                case 2:

                                                    //Actualizamos la tabla registro,con los filtros:
                                                    // Filas donde "mandante" es $mandante
                                                    // Filas donde "usuario_id" es $usuarioId
                                                    //Las actalizaciones son las siguientes
                                                    //saldo_especial= saldo_especia + " . $valor_bono . ": Incrementa el valor de creditos sumando el valor de la variable  $valor_bono.

                                                    $sqlstr = "update registro set saldo_especial=saldo_especial+" . $valor_bono . " where mandante=" . $mandante . " and usuario_id=" . $usuarioId;
                                                    //Ejecutamos y guardamos la SQL
                                                    $q = $this->execUpdate($transaccion, $sqlstr);

                                                    //Actualizamos variables
                                                    $estadoBono = 'R';
                                                    $SumoSaldo = true;
                                                    $SumoSaldoValor = $valor_bono;

                                                    break;

                                            }

                                            //Si gano bono
                                        } else {

                                            //Hacemos recall a la funcion
                                            $resp = $this->agregarBonoFree($ganaBonoId, $usuarioId, $mandante, $detalles, '', '', $transaccion);

                                            //Actualizamos el estado del bono
                                            $estadoBono = 'R';

                                        }

                                        //Si el bono tiene rollower
                                    } else {
                                        if ($rollowerDeposito) {
                                            //$rollowerRequerido = $rollowerRequerido + ($rollowerDeposito * $detalleValorDeposito);
                                        }

                                        if ($rollowerBono) {
                                            //$rollowerRequerido = $rollowerRequerido + ($rollowerBono * $valor_bono);

                                        }
                                        if ($rollowerValor) {
                                            //$rollowerRequerido = $rollowerRequerido + ($rollowerValor);


                                        }

                                        //Actualizamos la tabla registro y bono_interno,con los filtros:
                                        // Filas donde "registro.mandante" es $mandante
                                        // Filas donde "registro.usuario_id" es $usuarioId
                                        // Filas donde "bono_id" es $bonoElegido
                                        //Las actalizaciones son las siguientes
                                        //registro.creditos_bono = registro.creditos_bono+" . $valor_bono  . ": Incrementa el valor de registro.creditos_bono sumando el valor de la variable  $valor_bono.

                                        $sqlstr = "update registro,bono_interno set registro.creditos_bono=registro.creditos_bono+" . $valor_bono . " where registro.mandante=" . $mandante . " and registro.usuario_id=" . $usuarioId . " AND bono_id ='" . $bonoElegido . "'";

                                        $q = $this->execUpdate($transaccion, $sqlstr);

                                    }

                                    //Insertamos registros en la tabla  usuario_historial,con los filtros:
                                    // Filas donde "usuario_bono.estado" es 'R'
                                    // Filas donde "usuario_bono.usubono_id" es la varable $bonoLibre->usubono_id
                                    // Ademas de la unión de usuario_bono con registro  donde registro.usuario_id = usuario_bono.usuario_id
                                    //
                                    //Las actalizaciones se haran en las columnas
                                    //   usuario_id, descripcion, movimiento, usucrea_id, usumodif_id, tipo, valor, externo_id, creditos, creditos_base
                                    //
                                    // Se harán de la siguiente manera
                                    // usuario_id
                                    //   Seleccionamos "usuario_bono.usuario_id" de la tabla "usuario_bono"
                                    // descripcion
                                    //   Valor vacio: ' '
                                    // movimiento
                                    //   Establecemos valor 'E'
                                    // usucrea_id
                                    //   Establecemos valor '0'
                                    // usumodif_id
                                    //   Establecemos valor '0'
                                    // tipo
                                    //   Establecemos valor '50'
                                    // valor
                                    //   Seleccionamos "valor" de la tabla "usuario_bono"
                                    // externo_id
                                    //   Seleccionamos "usubono_id" de la tabla "usuario_bono".
                                    // creditos
                                    //   Seleccionamos "creditos" de la tabla "registro".
                                    // creditos_base
                                    //   Seleccionamos "creditos_base" de la tabla "registro".

                                    $sqlstr = "insert into usuario_historial (usuario_id, descripcion, movimiento,usucrea_id,usumodif_id, tipo, valor, externo_id,creditos,creditos_base) SELECT usuario_bono.usuario_id,'','E',0,0,50,usuario_bono.valor,usuario_bono.usubono_id,creditos,creditos_base FROM usuario_bono INNER JOIN registro ON (registro.usuario_id = usuario_bono.usuario_id) AND usuario_bono.estado='R' AND usuario_bono.usubono_id = '" . $bonoLibre->usubono_id . "' ";

                                    //Se ejecuta la SQL
                                    $q = $this->execUpdate($transaccion, $sqlstr);

                                    //Activamos ganoBonoBool
                                    $ganoBonoBool = true;

                                    //Si la SQL no se realizó
                                } else {
                                    //Desactivamos ganoBonoBool
                                    $ganoBonoBool = false;

                                }
                            }
                        }
                    }
                }
            }

            //Guardamos variables finales
            $respuesta["WinBonus"] = $ganoBonoBool;
            $respuesta["Bono"] = $bonoElegido;
            $respuesta["SumoSaldo"] = $SumoSaldo;
            $respuesta["SumoSaldoValor"] = $SumoSaldoValor;
            $respuesta["Valor"] = $ValorBono;
            $respuesta["queries"] = $strSql;

            //Enviamos mensaje/notificacion al usuario
            //Configuramos el mensaje para el bono asociado a una campaña o no


            if ($respuesta["WinBonus"]) {
                $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
                $BonodetalleM = $BonoDetalleMySqlDAO->querybyBonoIdAndTipo($respuesta['Bono'], 'MARKETINGCAMPAING');

                if ($BonodetalleM) {
                    $UsuarioMandante = new UsuarioMandante('', $usuarioId, $mandante);
                    //Recorremos la consulta y configuramos parametros a mostrar en mensaje/notificación


                    //aca se configura el envio
                    foreach ($BonodetalleM as $mensaje) {
                        $Campaing = new UsuarioMensajecampana($mensaje->valor);

                        $UsuarioMensaje = new UsuarioMensaje();
                        $UsuarioMensaje->body = $Campaing->body;
                        $UsuarioMensaje->msubject = $Campaing->msubject;
                        $UsuarioMensaje->usufromId = 0;
                        $UsuarioMensaje->usutoId = $UsuarioMandante->usumandanteId;
                        $UsuarioMensaje->isRead = 0;
                        $UsuarioMensaje->msubject = $Campaing->nombre;
                        $UsuarioMensaje->parentId = $mandante;
                        $UsuarioMensaje->proveedorId = $mandante;
                        $UsuarioMensaje->tipo = $Campaing->tipo;
                        $UsuarioMensaje->paisId = $Campaing->paisId;
                        $UsuarioMensaje->fechaExpiracion = $Campaing->fechaExpiracion;
                        $UsuarioMensaje->usumencampanaId = $mensaje->valor;


                        $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($transaccion);
                        //Enviamos los parametros para el mensaje a la tabla usuario_mensaje
                        $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);

                    }
                }
            }


            //En caso de brindarse un $codebonus se inactiva el registro
            if ($respuesta["WinBonus"] && $bonusPlanIdAltenar == '' && !empty($codebonus) && $tipobono2 == '8') {
                $rules = [];
                $rules[] = ['field' => 'usuario_bono.codigo', 'data' => $codebonus, 'op' => 'eq'];
                $rules[] = ['field' => 'usuario_bono.bono_id', 'data' => $bonoElegido, 'op' => 'eq'];
                $rules[] = ['field' => 'usuario_bono.estado', 'data' => 'L', 'op' => 'eq'];
                $filters = ['rules' => $rules, 'groupOp' => 'AND'];

                $select = 'usuario_bono.usubono_id';
                $sidx = $select;

                $UsuarioBonoMySqlDAO = new UsuarioBonoMySqlDAO($transaccion);
                $UsuarioBono = new UsuarioBono();
                $bonusCoupon = $UsuarioBono->getUsuarioBonosCustom($select, $sidx, 'DESC', 0, 1, json_encode($filters), true);
                $bonusCoupon = json_decode($bonusCoupon)->data;

                foreach ($bonusCoupon as $coupon) {
                    $couponId = $coupon->{'usuario_bono.usubono_id'};
                    $UsuarioBono = new UsuarioBono($couponId);
                    $UsuarioBono->estado = 'I';

                    $UsuarioBonoMySqlDAO->update($UsuarioBono);
                }
            }



        }

        try {
            if ($respuesta != null && $respuesta["WinBonus"] == true) {
                if ($bonosUsuarioDisponibles != null && is_array($bonosUsuarioDisponibles)) {

                    foreach ($bonosUsuarioDisponibles as $bonosUsuarioDisponible) {
                        $sqlUpdate = "UPDATE usuario_bono SET estado='R'  WHERE usubono_id='" . $bonosUsuarioDisponible->usubono_id . "' AND estado='P' ";

                        $bonosUsuarioDisponiblesUpdate = $this->execQuery($transaccion, $sqlUpdate);

                    }
                }
            }
        } catch (Exception $e) {
        }


        return json_decode(json_encode($respuesta));

    }

    /** Función encargada de verificar la disponibilidad de bonos FreeBet y FreeCasino vinculados al usuario en el momento de acreditarse una transacción, con el fin de definir el saldo Free y saldo real que será acreditado en la compra del usuario
     * @param UsuarioMandante $UsuarioMandante
     * @param  object $detalles
     * @param  int $tipoProducto
     * @param  Transaction $Transaction
     * @param TransaccionApi $TransaccionApi
     * @param TransaccionJuego|null $TransaccionJuego
     *
     * @return object $respuesta
     * @return int respuesta["Bono"]
     * @return bool respuesta["WinBonus"]
     * @return int respuesta["AmountDebit"]
     * @return int respuesta["AmountBonus"]
     */
    public function verificarBonoFree($UsuarioMandante, $detalles, $tipoProducto, $Transaction, $TransaccionApi, $TransaccionJuego = null, $ProductoMandante = null, $Producto = null, $Usuario = null)
    {

        //Verificamos Detalles a tomar en cuenta al momento de verificar y aumentar rollower
        $detalleTipoApuesta = $detalles->TipoApuesta;
        $detalleSelecciones = $detalles->Selecciones;
        $detalleCategoriasCasino = $detalles->CategoriasCasino;
        $detalleProveedoresCasino = $detalles->ProveedoresCasino;
        $detalleJuegosCasino = $detalles->JuegosCasino;
        $detalleValorApuesta = $detalles->ValorApuesta;
        //$TransaccionApi = $detalles->TransaccionApi;
        $detalleCuotaTotal = $detalles->CuotaTotal;
        $betmode = $detalles->BetMode;

        if ($_ENV['debug']) {
            print_r('entroCOND2');
            print_r($detalles);
        }


        $respuesta = array();
        $respuesta["Bono"] = 0;
        $respuesta["WinBonus"] = false;

        switch ($tipoProducto) {


            case "CASINO":
                if ($Usuario == null) {
                    $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
                }

                if ($ProductoMandante == null) {
                    $ProductoMandante = new ProductoMandante("", "", $TransaccionApi->productoId);
                }
                if ($Producto == null) {
                    $Producto = new Producto($ProductoMandante->productoId);
                }

                $rules = [];

                array_push($rules, array("field" => "usuario_bono.usuario_id", "data" => "$Usuario->usuarioId", "op" => "eq"));
                array_push($rules, array("field" => "usuario_bono.estado", "data" => "A", "op" => "eq"));
                // array_push($rules, array("field" => "bono_interno.fecha_inicio", "data" => "$TransaccionApi->fechaCrea", "op" => "le"));
                //  array_push($rules, array("field" => "bono_interno.fecha_fin", "data" => "$TransaccionApi->fechaCrea", "op" => "ge"));
                array_push($rules, array("field" => "bono_interno.tipo", "data" => "5", "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                $json = json_encode($filtro);


                $UsuarioBono = new UsuarioBono();
                $data = $UsuarioBono->getUsuarioBonosCustom("usuario_bono.*", "bono_interno.orden", "DESC", 0, 100, $json, true, '');


                $data = json_decode($data);

                $final = [];

                $pos = 1;
                $torneosAnalizados = '';

                $cumpleCondicion = false;
                foreach ($data->data as $key => $value) {

                    if (!$cumpleCondicion) {


                        $BonoInterno = new BonoInterno();
                        $BonoDetalle = new BonoDetalle();


                        $rules = [];

                        array_push($rules, array("field" => "bono_interno.bono_id", "data" => $value->{"usuario_bono.bono_id"}, "op" => "eq"));
                        // array_push($rules, array("field" => "bono_interno.estado", "data" => "A", "op" => "eq"));

                        $filtro = array("rules" => $rules, "groupOp" => "AND");

                        $json = json_encode($filtro);

                        $torneodetalles = $BonoDetalle->getBonoDetallesCustom(" bono_detalle.*,bono_interno.* ", "bono_interno.bono_id", "asc", 0, 1000, $json, TRUE);

                        $torneodetalles = json_decode($torneodetalles);

                        $final = [];

                        $creditosConvert = 0;

                        $cumpleCondicionPais = false;
                        $cumpleCondicionCont = 0;
                        $porcentajeBono = 0;

                        $cumpleCondicion = true;
                        $cumpleCondicionGame = false;
                        $cumpleCondicionGameCont = 0;
                        $CONDSUBPROVIDER = array();

                        $expDia = '';
                        $expFecha = '';
                        foreach ($torneodetalles->data as $key2 => $value2) {

                            switch ($value2->{"bono_detalle.tipo"}) {

                                case "EXPDIA":
                                    $fechaBono = date('Y-m-d H:i:s', strtotime($value->{"usuario_bono.fecha_crea"} . ' + ' . $value2->{"bono_detalle.valor"} . ' days'));
                                    $fecha_actual = date("Y-m-d H:i:s", time());

                                    if ($fechaBono < $fecha_actual) {
                                        $cumpleCondicion = false;
                                    }

                                    $expDia = $value2->valor;
                                    $expFecha = '';

                                    break;

                                case "EXPFECHA":
                                    $fechaBono = date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $value2->{"bono_detalle.valor"})));
                                    $fecha_actual = (date("Y-m-d H:i:s", time()));

                                    if ($fechaBono < $fecha_actual) {
                                        $cumpleCondicion = false;
                                    }

                                    $expDia = '';
                                    $expFecha = $value2->valor;
                                    break;

                                case "CONDPAISUSER":

                                    if ($value2->{"bono_detalle.valor"} == $Usuario->paisId) {
                                        $cumpleCondicionPais = true;
                                    }
                                    $cumpleCondicionCont++;

                                    break;

                                case "CONDBALANCE":

                                    if ($value2->{"bono_detalle.valor"} == 0) {
                                        if (floatval($Usuario->getBalance()) > 0.3) {
                                            $cumpleCondicion = false;
                                        }
                                        /*if ($value2->{"bono_detalle.moneda"} == $Usuario->moneda) {
                                        }*/
                                    }

                                    break;


                                case "MAXAMOUNT":
                                    if ($value2->{"bono_detalle.moneda"} == $Usuario->moneda) {
                                        $ValorBono2 = $value2->{"bono_detalle.valor"};
                                    }

                                    break;

                                default:

                                    if (stristr($value2->{"bono_detalle.tipo"}, 'CONDGAME')) {

                                        $idGame = explode("CONDGAME", $value2->{"bono_detalle.tipo"})[1];

                                        if ($ProductoMandante->prodmandanteId == $idGame) {
                                            $cumpleCondicionGame = true;
                                            $porcentajeBono = $value2->{"bono_detalle.valor"};

                                        }
                                        $cumpleCondicionGameCont++;
                                    }

                                    if (stristr($value2->{"bono_detalle.tipo"}, 'CONDPROVIDER')) {

                                        $idGame = explode("CONDPROVIDER", $value2->{"bono_detalle.tipo"})[1];


                                        if ($Producto->proveedorId == $idGame) {
                                            $cumpleCondicionGame = true;
                                            $porcentajeBono = $value2->{"bono_detalle.valor"};
                                        }
                                        $cumpleCondicionGameCont++;
                                    }

                                    if (stristr($value2->{"bono_detalle.tipo"}, 'CONDSUBPROVIDER')) {

                                        $idGame = explode("CONDSUBPROVIDER", $value2->{"bono_detalle.tipo"})[1];
                                        array_push($CONDSUBPROVIDER, $idGame);

                                        if ($Producto->subproveedorId == $idGame) {
                                            $cumpleCondicionGame = true;
                                            $porcentajeBono = $value2->{"bono_detalle.valor"};
                                        }
                                        $cumpleCondicionGameCont++;
                                    }

                                    if (stristr($value2->{"bono_detalle.tipo"}, 'CONDCATEGORY')) {

                                        $idGame = explode("CONDCATEGORY", $value2->{"bono_detalle.tipo"})[1];

                                        try {
                                            $CategoriaProducto = new CategoriaProducto('', $Producto->productoId, '', $idGame, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
                                            $cumpleCondicionGame = true;
                                            $porcentajeBono = $value2->{"bono_detalle.valor"};

                                        } catch (Exception $e) {

                                        }
                                        $cumpleCondicionGameCont++;
                                    }

                                    break;
                            }

                        }

                        if (!$cumpleCondicionPais && $cumpleCondicionCont > 0) {
                            $cumpleCondicion = false;
                        }
                        if (!$cumpleCondicionGame && $cumpleCondicionGameCont > 0) {
                            $cumpleCondicion = false;
                        }

                        if ($cumpleCondicion) {

                            $valorBono = ($TransaccionApi->valor * (($porcentajeBono) / 100));
                            if ($ValorBono2 != '' && $ValorBono2 != '0') {

                                if ($valorBono > $ValorBono2) {
                                    $valorBono = $ValorBono2;
                                }
                            }


                            $UsuarioBono = new UsuarioBono($value->{"usuario_bono.usubono_id"});

                            if ($UsuarioBono->valorBase < ($UsuarioBono->valor + $valorBono)) {
                                $valorBono = $UsuarioBono->valorBase - $UsuarioBono->valor;

                            }

                            $valorDebit = $TransaccionApi->valor - $valorBono;

                            $UsuarioBono->valor = ($UsuarioBono->valor) + $valorBono;

                            if ($UsuarioBono->valor == $UsuarioBono->valorBase) {
                                $UsuarioBono->estado = 'R';
                            }


                            $UsuarioBonoMySqlDAO = new UsuarioBonoMySqlDAO($Transaction);
                            $UsuarioBonoMySqlDAO->update($UsuarioBono);

                            $BonoLog = new BonoLog();
                            $BonoLog->setUsuarioId($Usuario->usuarioId);
                            $BonoLog->setTipo('FC');
                            $BonoLog->setValor($valorBono);
                            $BonoLog->setFechaCrea(date('Y-m-d H:i:s'));
                            $BonoLog->setEstado('L');
                            $BonoLog->setErrorId(0);
                            $BonoLog->setIdExterno($UsuarioBono->usubonoId);
                            $BonoLog->setTransaccionId($UsuarioBono->usuarioId);
                            $BonoLog->setMandante($Usuario->mandante);
                            $BonoLog->setFechaCierre('');
                            $BonoLog->setTransaccionId('');
                            $BonoLog->setTipobonoId(4);
                            $BonoLog->setTiposaldoId('1');


                            $BonoLogMySqlDAO = new BonoLogMySqlDAO($Transaction);
                            $BonoLogMySqlDAO->insert($BonoLog);

                            /*$TransjuegoInfo = new TransjuegoInfo();
                            $TransjuegoInfo->productoId=$TransaccionApi->productoId;
                            $TransjuegoInfo->transaccionId=$TransaccionApi->transaccionId;
                            $TransjuegoInfo->tipo="FREECASH";
                            $TransjuegoInfo->descripcion='';
                            $TransjuegoInfo->valor=$valorBono;
                            $TransjuegoInfo->transapiId=$TransaccionApi->transapiId;
                            $TransjuegoInfo->usucreaId=0;
                            $TransjuegoInfo->usumodifId=$UsuarioBono->usubonoId;
                            $TransjuegoInfo->identificador=$TransaccionApi->identificador;
                            if($TransaccionJuego != null){
                                $TransjuegoInfo->transjuegoId=$TransaccionJuego->transjuegoId;
                            }

                            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO($Transaction);
                            $TransjuegoInfoMySqlDAO->insert($TransjuegoInfo);*/


                            $respuesta["Bono"] = $value->{"usuario_bono.usubono_id"};
                            $respuesta["WinBonus"] = true;
                            $respuesta["AmountDebit"] = $valorDebit;
                            $respuesta["AmountBonus"] = $valorBono;
                            break;


                            /*                        $TransjuegoInfo = new TransjuegoInfo();
                                                    $TransjuegoInfo->productoId=$TransaccionApi->productoId;
                                                    $TransjuegoInfo->transaccionId=$TransaccionApi->transaccionId;
                                                    $TransjuegoInfo->tipo="TORNEO";
                                                    $TransjuegoInfo->descripcion=$value->{"usuario_bono.usubono_id"};
                                                    $TransjuegoInfo->valor=$creditosConvert;
                                                    $TransjuegoInfo->transapiId=$TransaccionApi->transapiId;
                                                    $TransjuegoInfo->usucreaId=0;
                                                    $TransjuegoInfo->usumodifId=0;
                                                    $TransjuegoInfo->identificador=$TransaccionApi->identificador;



                                                    $TransjuegoInfoMySqlDAO->insert($TransjuegoInfo);
                                                    $TransjuegoInfoMySqlDAO->getTransaction()->commit();*/
                        }
                    }
                }
                break;
            case "LIVECASINO":

                if ($Usuario == null) {
                    $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
                }

                if ($ProductoMandante == null) {
                    $ProductoMandante = new ProductoMandante("", "", $TransaccionApi->productoId);
                }
                if ($Producto == null) {
                    $Producto = new Producto($ProductoMandante->productoId);
                }

                $rules = [];

                array_push($rules, array("field" => "usuario_bono.usuario_id", "data" => "$Usuario->usuarioId", "op" => "eq"));
                array_push($rules, array("field" => "usuario_bono.estado", "data" => "A", "op" => "eq"));
                // array_push($rules, array("field" => "bono_interno.fecha_inicio", "data" => "$TransaccionApi->fechaCrea", "op" => "le"));
                //  array_push($rules, array("field" => "bono_interno.fecha_fin", "data" => "$TransaccionApi->fechaCrea", "op" => "ge"));
                array_push($rules, array("field" => "bono_interno.tipo", "data" => "5", "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                $json = json_encode($filtro);


                $UsuarioBono = new UsuarioBono();
                $data = $UsuarioBono->getUsuarioBonosCustom("usuario_bono.*", "bono_interno.orden", "DESC", 0, 100, $json, true, '');


                $data = json_decode($data);

                $final = [];

                $pos = 1;
                $torneosAnalizados = '';

                $cumpleCondicion = false;
                foreach ($data->data as $key => $value) {

                    if (!$cumpleCondicion) {


                        $BonoInterno = new BonoInterno();
                        $BonoDetalle = new BonoDetalle();


                        $rules = [];

                        array_push($rules, array("field" => "bono_interno.bono_id", "data" => $value->{"usuario_bono.bono_id"}, "op" => "eq"));
                        // array_push($rules, array("field" => "bono_interno.estado", "data" => "A", "op" => "eq"));

                        $filtro = array("rules" => $rules, "groupOp" => "AND");

                        $json = json_encode($filtro);

                        $torneodetalles = $BonoDetalle->getBonoDetallesCustom(" bono_detalle.*,bono_interno.* ", "bono_interno.bono_id", "asc", 0, 1000, $json, TRUE);

                        $torneodetalles = json_decode($torneodetalles);

                        $final = [];

                        $creditosConvert = 0;

                        $cumpleCondicionPais = false;
                        $cumpleCondicionCont = 0;
                        $porcentajeBono = 0;

                        $cumpleCondicion = true;
                        $cumpleCondicionGame = false;
                        $cumpleCondicionGameCont = 0;
                        $CONDSUBPROVIDER = array();

                        $expDia = '';
                        $expFecha = '';
                        foreach ($torneodetalles->data as $key2 => $value2) {

                            switch ($value2->{"bono_detalle.tipo"}) {

                                case "EXPDIA":
                                    $fechaBono = date('Y-m-d H:i:s', strtotime($value->{"usuario_bono.fecha_crea"} . ' + ' . $value2->{"bono_detalle.valor"} . ' days'));
                                    $fecha_actual = date("Y-m-d H:i:s", time());

                                    if ($fechaBono < $fecha_actual) {
                                        $cumpleCondicion = false;
                                    }

                                    $expDia = $value2->valor;
                                    $expFecha = '';

                                    break;

                                case "EXPFECHA":
                                    $fechaBono = date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $value2->{"bono_detalle.valor"})));
                                    $fecha_actual = (date("Y-m-d H:i:s", time()));

                                    if ($fechaBono < $fecha_actual) {
                                        $cumpleCondicion = false;
                                    }

                                    $expDia = '';
                                    $expFecha = $value2->valor;
                                    break;

                                case "CONDPAISUSER":

                                    if ($value2->{"bono_detalle.valor"} == $Usuario->paisId) {
                                        $cumpleCondicionPais = true;
                                    }
                                    $cumpleCondicionCont++;

                                    break;

                                case "CONDBALANCE":

                                    if ($value2->{"bono_detalle.valor"} == 0) {
                                        if (floatval($Usuario->getBalance()) > 0.3) {
                                            $cumpleCondicion = false;
                                        }
                                        /*if ($value2->{"bono_detalle.moneda"} == $Usuario->moneda) {
                                        }*/
                                    }

                                    break;


                                case "MAXAMOUNT":
                                    if ($value2->{"bono_detalle.moneda"} == $Usuario->moneda) {
                                        $ValorBono2 = $value2->{"bono_detalle.valor"};
                                    }

                                    break;

                                default:

                                    if (stristr($value2->{"bono_detalle.tipo"}, 'CONDGAME')) {

                                        $idGame = explode("CONDGAME", $value2->{"bono_detalle.tipo"})[1];

                                        if ($ProductoMandante->prodmandanteId == $idGame) {
                                            $cumpleCondicionGame = true;
                                            $porcentajeBono = $value2->{"bono_detalle.valor"};

                                        }
                                        $cumpleCondicionGameCont++;
                                    }

                                    if (stristr($value2->{"bono_detalle.tipo"}, 'CONDPROVIDER')) {

                                        $idGame = explode("CONDPROVIDER", $value2->{"bono_detalle.tipo"})[1];


                                        if ($Producto->proveedorId == $idGame) {
                                            $cumpleCondicionGame = true;
                                            $porcentajeBono = $value2->{"bono_detalle.valor"};
                                        }
                                        $cumpleCondicionGameCont++;
                                    }

                                    if (stristr($value2->{"bono_detalle.tipo"}, 'CONDSUBPROVIDER')) {

                                        $idGame = explode("CONDSUBPROVIDER", $value2->{"bono_detalle.tipo"})[1];
                                        array_push($CONDSUBPROVIDER, $idGame);

                                        if ($Producto->subproveedorId == $idGame) {
                                            $cumpleCondicionGame = true;
                                            $porcentajeBono = $value2->{"bono_detalle.valor"};
                                        }
                                        $cumpleCondicionGameCont++;
                                    }

                                    if (stristr($value2->{"bono_detalle.tipo"}, 'CONDCATEGORY')) {

                                        $idGame = explode("CONDCATEGORY", $value2->{"bono_detalle.tipo"})[1];

                                        try {
                                            $CategoriaProducto = new CategoriaProducto('', $Producto->productoId, '', $idGame, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
                                            $cumpleCondicionGame = true;
                                            $porcentajeBono = $value2->{"bono_detalle.valor"};

                                        } catch (Exception $e) {

                                        }
                                        $cumpleCondicionGameCont++;
                                    }

                                    break;
                            }

                        }

                        if (!$cumpleCondicionPais && $cumpleCondicionCont > 0) {
                            $cumpleCondicion = false;
                        }
                        if (!$cumpleCondicionGame && $cumpleCondicionGameCont > 0) {
                            $cumpleCondicion = false;
                        }

                        if ($cumpleCondicion) {

                            $valorBono = ($TransaccionApi->valor * (($porcentajeBono) / 100));
                            if ($ValorBono2 != '' && $ValorBono2 != '0') {

                                if ($valorBono > $ValorBono2) {
                                    $valorBono = $ValorBono2;
                                }
                            }


                            $UsuarioBono = new UsuarioBono($value->{"usuario_bono.usubono_id"});

                            if ($UsuarioBono->valorBase < ($UsuarioBono->valor + $valorBono)) {
                                $valorBono = $UsuarioBono->valorBase - $UsuarioBono->valor;

                            }

                            $valorDebit = $TransaccionApi->valor - $valorBono;

                            $UsuarioBono->valor = ($UsuarioBono->valor) + $valorBono;

                            if ($UsuarioBono->valor == $UsuarioBono->valorBase) {
                                $UsuarioBono->estado = 'R';
                            }


                            $UsuarioBonoMySqlDAO = new UsuarioBonoMySqlDAO($Transaction);
                            $UsuarioBonoMySqlDAO->update($UsuarioBono);

                            $BonoLog = new BonoLog();
                            $BonoLog->setUsuarioId($Usuario->usuarioId);
                            $BonoLog->setTipo('FC');
                            $BonoLog->setValor($valorBono);
                            $BonoLog->setFechaCrea(date('Y-m-d H:i:s'));
                            $BonoLog->setEstado('L');
                            $BonoLog->setErrorId(0);
                            $BonoLog->setIdExterno($UsuarioBono->usubonoId);
                            $BonoLog->setTransaccionId($UsuarioBono->usuarioId);
                            $BonoLog->setMandante($Usuario->mandante);
                            $BonoLog->setFechaCierre('');
                            $BonoLog->setTransaccionId('');
                            $BonoLog->setTipobonoId(4);
                            $BonoLog->setTiposaldoId('1');


                            $BonoLogMySqlDAO = new BonoLogMySqlDAO($Transaction);
                            $BonoLogMySqlDAO->insert($BonoLog);

                            /*$TransjuegoInfo = new TransjuegoInfo();
                            $TransjuegoInfo->productoId=$TransaccionApi->productoId;
                            $TransjuegoInfo->transaccionId=$TransaccionApi->transaccionId;
                            $TransjuegoInfo->tipo="FREECASH";
                            $TransjuegoInfo->descripcion='';
                            $TransjuegoInfo->valor=$valorBono;
                            $TransjuegoInfo->transapiId=$TransaccionApi->transapiId;
                            $TransjuegoInfo->usucreaId=0;
                            $TransjuegoInfo->usumodifId=$UsuarioBono->usubonoId;
                            $TransjuegoInfo->identificador=$TransaccionApi->identificador;
                            if($TransaccionJuego != null){
                                $TransjuegoInfo->transjuegoId=$TransaccionJuego->transjuegoId;
                            }

                            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO($Transaction);
                            $TransjuegoInfoMySqlDAO->insert($TransjuegoInfo);*/


                            $respuesta["Bono"] = $value->{"usuario_bono.usubono_id"};
                            $respuesta["WinBonus"] = true;
                            $respuesta["AmountDebit"] = $valorDebit;
                            $respuesta["AmountBonus"] = $valorBono;
                            break;


                            /*                        $TransjuegoInfo = new TransjuegoInfo();
                                                    $TransjuegoInfo->productoId=$TransaccionApi->productoId;
                                                    $TransjuegoInfo->transaccionId=$TransaccionApi->transaccionId;
                                                    $TransjuegoInfo->tipo="TORNEO";
                                                    $TransjuegoInfo->descripcion=$value->{"usuario_bono.usubono_id"};
                                                    $TransjuegoInfo->valor=$creditosConvert;
                                                    $TransjuegoInfo->transapiId=$TransaccionApi->transapiId;
                                                    $TransjuegoInfo->usucreaId=0;
                                                    $TransjuegoInfo->usumodifId=0;
                                                    $TransjuegoInfo->identificador=$TransaccionApi->identificador;



                                                    $TransjuegoInfoMySqlDAO->insert($TransjuegoInfo);
                                                    $TransjuegoInfoMySqlDAO->getTransaction()->commit();*/
                        }
                    }
                }
                break;
            case "VIRTUAL":

                if ($Usuario == null) {
                    $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
                }

                if ($ProductoMandante == null) {
                    $ProductoMandante = new ProductoMandante("", "", $TransaccionApi->productoId);
                }
                if ($Producto == null) {
                    $Producto = new Producto($ProductoMandante->productoId);
                }

                $rules = [];

                array_push($rules, array("field" => "usuario_bono.usuario_id", "data" => "$Usuario->usuarioId", "op" => "eq"));
                array_push($rules, array("field" => "usuario_bono.estado", "data" => "A", "op" => "eq"));
                // array_push($rules, array("field" => "bono_interno.fecha_inicio", "data" => "$TransaccionApi->fechaCrea", "op" => "le"));
                //  array_push($rules, array("field" => "bono_interno.fecha_fin", "data" => "$TransaccionApi->fechaCrea", "op" => "ge"));
                array_push($rules, array("field" => "bono_interno.tipo", "data" => "5", "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                $json = json_encode($filtro);


                $UsuarioBono = new UsuarioBono();
                $data = $UsuarioBono->getUsuarioBonosCustom("usuario_bono.*", "bono_interno.orden", "DESC", 0, 100, $json, true, '');


                $data = json_decode($data);

                $final = [];

                $pos = 1;
                $torneosAnalizados = '';

                $cumpleCondicion = false;
                foreach ($data->data as $key => $value) {

                    if (!$cumpleCondicion) {


                        $BonoInterno = new BonoInterno();
                        $BonoDetalle = new BonoDetalle();


                        $rules = [];

                        array_push($rules, array("field" => "bono_interno.bono_id", "data" => $value->{"usuario_bono.bono_id"}, "op" => "eq"));
                        // array_push($rules, array("field" => "bono_interno.estado", "data" => "A", "op" => "eq"));

                        $filtro = array("rules" => $rules, "groupOp" => "AND");

                        $json = json_encode($filtro);

                        $torneodetalles = $BonoDetalle->getBonoDetallesCustom(" bono_detalle.*,bono_interno.* ", "bono_interno.bono_id", "asc", 0, 1000, $json, TRUE);

                        $torneodetalles = json_decode($torneodetalles);

                        $final = [];

                        $creditosConvert = 0;

                        $cumpleCondicionPais = false;
                        $cumpleCondicionCont = 0;
                        $porcentajeBono = 0;

                        $cumpleCondicion = true;
                        $cumpleCondicionGame = false;
                        $cumpleCondicionGameCont = 0;
                        $CONDSUBPROVIDER = array();

                        $expDia = '';
                        $expFecha = '';
                        foreach ($torneodetalles->data as $key2 => $value2) {

                            switch ($value2->{"bono_detalle.tipo"}) {

                                case "EXPDIA":
                                    $fechaBono = date('Y-m-d H:i:s', strtotime($value->{"usuario_bono.fecha_crea"} . ' + ' . $value2->{"bono_detalle.valor"} . ' days'));
                                    $fecha_actual = date("Y-m-d H:i:s", time());

                                    if ($fechaBono < $fecha_actual) {
                                        $cumpleCondicion = false;
                                    }

                                    $expDia = $value2->valor;
                                    $expFecha = '';

                                    break;

                                case "EXPFECHA":
                                    $fechaBono = date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $value2->{"bono_detalle.valor"})));
                                    $fecha_actual = (date("Y-m-d H:i:s", time()));

                                    if ($fechaBono < $fecha_actual) {
                                        $cumpleCondicion = false;
                                    }

                                    $expDia = '';
                                    $expFecha = $value2->valor;
                                    break;

                                case "CONDPAISUSER":

                                    if ($value2->{"bono_detalle.valor"} == $Usuario->paisId) {
                                        $cumpleCondicionPais = true;
                                    }
                                    $cumpleCondicionCont++;

                                    break;

                                case "CONDBALANCE":

                                    if ($value2->{"bono_detalle.valor"} == 0) {
                                        if (floatval($Usuario->getBalance()) > 0.3) {
                                            $cumpleCondicion = false;
                                        }
                                        /*if ($value2->{"bono_detalle.moneda"} == $Usuario->moneda) {
                                        }*/
                                    }

                                    break;


                                case "MAXAMOUNT":
                                    if ($value2->{"bono_detalle.moneda"} == $Usuario->moneda) {
                                        $ValorBono2 = $value2->{"bono_detalle.valor"};
                                    }

                                    break;

                                default:

                                    if (stristr($value2->{"bono_detalle.tipo"}, 'CONDGAME')) {

                                        $idGame = explode("CONDGAME", $value2->{"bono_detalle.tipo"})[1];

                                        if ($ProductoMandante->prodmandanteId == $idGame) {
                                            $cumpleCondicionGame = true;
                                            $porcentajeBono = $value2->{"bono_detalle.valor"};

                                        }
                                        $cumpleCondicionGameCont++;
                                    }

                                    if (stristr($value2->{"bono_detalle.tipo"}, 'CONDPROVIDER')) {

                                        $idGame = explode("CONDPROVIDER", $value2->{"bono_detalle.tipo"})[1];


                                        if ($Producto->proveedorId == $idGame) {
                                            $cumpleCondicionGame = true;
                                            $porcentajeBono = $value2->{"bono_detalle.valor"};
                                        }
                                        $cumpleCondicionGameCont++;
                                    }

                                    if (stristr($value2->{"bono_detalle.tipo"}, 'CONDSUBPROVIDER')) {

                                        $idGame = explode("CONDSUBPROVIDER", $value2->{"bono_detalle.tipo"})[1];
                                        array_push($CONDSUBPROVIDER, $idGame);

                                        if ($Producto->subproveedorId == $idGame) {
                                            $cumpleCondicionGame = true;
                                            $porcentajeBono = $value2->{"bono_detalle.valor"};
                                        }
                                        $cumpleCondicionGameCont++;
                                    }

                                    if (stristr($value2->{"bono_detalle.tipo"}, 'CONDCATEGORY')) {

                                        $idGame = explode("CONDCATEGORY", $value2->{"bono_detalle.tipo"})[1];

                                        try {
                                            $CategoriaProducto = new CategoriaProducto('', $Producto->productoId, '', $idGame, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
                                            $cumpleCondicionGame = true;
                                            $porcentajeBono = $value2->{"bono_detalle.valor"};

                                        } catch (Exception $e) {

                                        }
                                        $cumpleCondicionGameCont++;
                                    }

                                    break;
                            }

                        }

                        if (!$cumpleCondicionPais && $cumpleCondicionCont > 0) {
                            $cumpleCondicion = false;
                        }
                        if (!$cumpleCondicionGame && $cumpleCondicionGameCont > 0) {
                            $cumpleCondicion = false;
                        }

                        if ($cumpleCondicion) {

                            $valorBono = ($TransaccionApi->valor * (($porcentajeBono) / 100));
                            if ($ValorBono2 != '' && $ValorBono2 != '0') {

                                if ($valorBono > $ValorBono2) {
                                    $valorBono = $ValorBono2;
                                }
                            }


                            $UsuarioBono = new UsuarioBono($value->{"usuario_bono.usubono_id"});

                            if ($UsuarioBono->valorBase < ($UsuarioBono->valor + $valorBono)) {
                                $valorBono = $UsuarioBono->valorBase - $UsuarioBono->valor;

                            }

                            $valorDebit = $TransaccionApi->valor - $valorBono;

                            $UsuarioBono->valor = ($UsuarioBono->valor) + $valorBono;

                            if ($UsuarioBono->valor == $UsuarioBono->valorBase) {
                                $UsuarioBono->estado = 'R';
                            }


                            $UsuarioBonoMySqlDAO = new UsuarioBonoMySqlDAO($Transaction);
                            $UsuarioBonoMySqlDAO->update($UsuarioBono);

                            $BonoLog = new BonoLog();
                            $BonoLog->setUsuarioId($Usuario->usuarioId);
                            $BonoLog->setTipo('FC');
                            $BonoLog->setValor($valorBono);
                            $BonoLog->setFechaCrea(date('Y-m-d H:i:s'));
                            $BonoLog->setEstado('L');
                            $BonoLog->setErrorId(0);
                            $BonoLog->setIdExterno($UsuarioBono->usubonoId);
                            $BonoLog->setTransaccionId($UsuarioBono->usuarioId);
                            $BonoLog->setMandante($Usuario->mandante);
                            $BonoLog->setFechaCierre('');
                            $BonoLog->setTransaccionId('');
                            $BonoLog->setTipobonoId(4);
                            $BonoLog->setTiposaldoId('1');


                            $BonoLogMySqlDAO = new BonoLogMySqlDAO($Transaction);
                            $BonoLogMySqlDAO->insert($BonoLog);

                            /*$TransjuegoInfo = new TransjuegoInfo();
                            $TransjuegoInfo->productoId=$TransaccionApi->productoId;
                            $TransjuegoInfo->transaccionId=$TransaccionApi->transaccionId;
                            $TransjuegoInfo->tipo="FREECASH";
                            $TransjuegoInfo->descripcion='';
                            $TransjuegoInfo->valor=$valorBono;
                            $TransjuegoInfo->transapiId=$TransaccionApi->transapiId;
                            $TransjuegoInfo->usucreaId=0;
                            $TransjuegoInfo->usumodifId=$UsuarioBono->usubonoId;
                            $TransjuegoInfo->identificador=$TransaccionApi->identificador;
                            if($TransaccionJuego != null){
                                $TransjuegoInfo->transjuegoId=$TransaccionJuego->transjuegoId;
                            }

                            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO($Transaction);
                            $TransjuegoInfoMySqlDAO->insert($TransjuegoInfo);*/


                            $respuesta["Bono"] = $value->{"usuario_bono.usubono_id"};
                            $respuesta["WinBonus"] = true;
                            $respuesta["AmountDebit"] = $valorDebit;
                            $respuesta["AmountBonus"] = $valorBono;
                            break;


                            /*                        $TransjuegoInfo = new TransjuegoInfo();
                                                    $TransjuegoInfo->productoId=$TransaccionApi->productoId;
                                                    $TransjuegoInfo->transaccionId=$TransaccionApi->transaccionId;
                                                    $TransjuegoInfo->tipo="TORNEO";
                                                    $TransjuegoInfo->descripcion=$value->{"usuario_bono.usubono_id"};
                                                    $TransjuegoInfo->valor=$creditosConvert;
                                                    $TransjuegoInfo->transapiId=$TransaccionApi->transapiId;
                                                    $TransjuegoInfo->usucreaId=0;
                                                    $TransjuegoInfo->usumodifId=0;
                                                    $TransjuegoInfo->identificador=$TransaccionApi->identificador;



                                                    $TransjuegoInfoMySqlDAO->insert($TransjuegoInfo);
                                                    $TransjuegoInfoMySqlDAO->getTransaction()->commit();*/
                        }
                    }
                }
                break;


            case "SPORT":

                if ($Usuario == null) {
                    $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
                }
                if ($ProductoMandante == null) {
                    $ProductoMandante = new ProductoMandante("", "", $TransaccionApi->productoId);
                }
                if ($Producto == null) {
                    $Producto = new Producto($ProductoMandante->productoId);
                }


                $rules = [];

                array_push($rules, array("field" => "usuario_bono.usuario_id", "data" => "$Usuario->usuarioId", "op" => "eq"));
                array_push($rules, array("field" => "usuario_bono.estado", "data" => "A", "op" => "eq"));
                //array_push($rules, array("field" => "usuario_bono.estado", "data" => "A", "op" => "eq"));
                // array_push($rules, array("field" => "bono_interno.fecha_inicio", "data" => "$TransaccionApi->fechaCrea", "op" => "le"));
                //  array_push($rules, array("field" => "bono_interno.fecha_fin", "data" => "$TransaccionApi->fechaCrea", "op" => "ge"));
                array_push($rules, array("field" => "bono_interno.tipo", "data" => "6", "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                $json = json_encode($filtro);


                $UsuarioBono = new UsuarioBono();
                $data = $UsuarioBono->getUsuarioBonosCustom("usuario_bono.*,bono_interno.*", "bono_interno.orden", "DESC", 0, 100, $json, true, '');

                $data = json_decode($data);

                $final = [];

                $pos = 1;
                $torneosAnalizados = '';

                foreach ($data->data as $key => $value) {

                    $BonoInterno = new BonoInterno();
                    $BonoDetalle = new BonoDetalle();


                    $rules = [];

                    array_push($rules, array("field" => "bono_interno.bono_id", "data" => $value->{"usuario_bono.bono_id"}, "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $json = json_encode($filtro);

                    $torneodetalles = $BonoDetalle->getBonoDetallesCustom(" bono_detalle.*,bono_interno.* ", "bono_interno.bono_id", "asc", 0, 1000, $json, TRUE);

                    $torneodetalles = json_decode($torneodetalles);

                    $final = [];

                    $creditosConvert = 0;

                    $cumpleCondicionPais = false;
                    $cumpleCondicionCont = 0;
                    $cumpleCondicion = true;
                    $ValorBono1 = 0;
                    $ValorBono2 = 0;
                    $porcentajeBono = 0;


                    $cumplecondicionproductoTipo = true;
                    $cumplecondicionproducto = false;
                    $condicionesproducto = 0;
                    $sePuedeSimples = 0;
                    $sePuedeCombinadas = 0;
                    $minselcount = 0;

                    if ($value->{"bono_interno.condicional"} == 'NA' || $value->{"bono_interno.condicional"} == '') {
                        $tipocomparacion = "OR";

                    } else {
                        $tipocomparacion = $value->{"bono_interno.condicional"};

                    }
                    if ($_ENV['debug']) {
                        print_r('entro3');
                        print_r($torneodetalles);
                    }
                    foreach ($torneodetalles->data as $key2 => $value2) {

                        switch ($value2->{"bono_detalle.tipo"}) {

                            case "CONDPAISUSER":

                                if ($value2->{"bono_detalle.valor"} == $Usuario->paisId) {
                                    $cumpleCondicionPais = true;
                                }
                                $cumpleCondicionCont++;

                                break;

                            case "ITAINMENT1":
                                $cumplecondicionproductotmp = false;
                                $condicionesproductotmp = 0;

                                foreach ($detalleSelecciones as $item) {


                                    if ($tipocomparacion == "OR") {
                                        if (trim($value2->{"bono_detalle.valor"}) == trim($item->Deporte)) {
                                            $cumplecondicionproductotmp = true;


                                        }
                                    } elseif ($tipocomparacion == "AND") {
                                        if ($value2->{"bono_detalle.valor"} != trim($item->Deporte)) {
                                            $cumplecondicionproductotmp = false;


                                        }

                                        if ($condicionesproductotmp == 0) {
                                            if ($value2->{"bono_detalle.valor"} == trim($item->Deporte)) {
                                                $cumplecondicionproductotmp = true;
                                            }
                                        } else {
                                            if ($value2->{"bono_detalle.valor"} == trim($item->Deporte) && $cumplecondicionproductotmp) {
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
                                } elseif ($tipocomparacion == "AND") {
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
                                $cumplecondicionproductotmp = false;
                                $condicionesproductotmp = 0;

                                foreach ($detalleSelecciones as $item) {
                                    if ($tipocomparacion == "OR") {
                                        if ($value2->{"bono_detalle.valor"} == trim($item->Liga)) {
                                            $cumplecondicionproductotmp = true;

                                        }
                                    } elseif ($tipocomparacion == "AND") {
                                        if ($value2->{"bono_detalle.valor"} != trim($item->Liga)) {
                                            $cumplecondicionproductotmp = false;

                                        }

                                        if ($condicionesproductotmp == 0) {
                                            if ($value2->{"bono_detalle.valor"} == trim($item->Liga)) {
                                                $cumplecondicionproductotmp = true;
                                            }
                                        } else {
                                            if ($value2->{"bono_detalle.valor"} == trim($item->Liga) && $cumplecondicionproducto) {
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
                                } elseif ($tipocomparacion == "AND") {
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
                                $cumplecondicionproductotmp = false;
                                $condicionesproductotmp = 0;


                                foreach ($detalleSelecciones as $item) {
                                    if ($tipocomparacion == "OR") {
                                        if ($value2->{"bono_detalle.valor"} == trim($item->Evento)) {
                                            $cumplecondicionproductotmp = true;

                                        }
                                    } elseif ($tipocomparacion == "AND") {
                                        if ($value2->{"bono_detalle.valor"} != trim($item->Evento)) {
                                            $cumplecondicionproductotmp = false;

                                        }

                                        if ($condicionesproductotmp == 0) {

                                            if ($value2->{"bono_detalle.valor"} == trim($item->Evento)) {
                                                $cumplecondicionproductotmp = true;
                                            }
                                        } else {

                                            if ($value2->{"bono_detalle.valor"} == trim($item->Evento) && $cumplecondicionproductotmp) {
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
                                } elseif ($tipocomparacion == "AND") {
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
                                $cumplecondicionproductotmp = false;
                                $condicionesproductotmp = 0;


                                if ($_ENV['debug']) {
                                    print_r('detalleSelecciones');
                                    print_r($detalleSelecciones);
                                    print_r(PHP_EOL);
                                    print_r($value2->{"bono_detalle.valor"});
                                }

                                foreach ($detalleSelecciones as $item) {
                                    if ($tipocomparacion == "OR") {
                                        if ($value2->{"bono_detalle.valor"} == trim($item->DeporteMercado)) {
                                            $cumplecondicionproductotmp = true;


                                        }
                                    } elseif ($tipocomparacion == "AND") {
                                        if ($value2->{"bono_detalle.valor"} != trim($item->DeporteMercado)) {
                                            $cumplecondicionproductotmp = false;

                                        }

                                        if ($condicionesproductotmp == 0) {
                                            if ($value2->{"bono_detalle.valor"} == trim($item->DeporteMercado)) {
                                                $cumplecondicionproductotmp = true;
                                            }
                                        } else {
                                            if ($value2->{"bono_detalle.valor"} == trim($item->DeporteMercado) && $cumplecondicionproductotmp) {
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
                                } elseif ($tipocomparacion == "AND") {
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

                                if ($value2->{"bono_detalle.valor"} == 1) {
                                    $sePuedeSimples = 1;

                                }
                                if ($value2->{"bono_detalle.valor"} == 2) {
                                    $sePuedeCombinadas = 1;

                                }
                                break;


                            case "LIVEORPREMATCH":


                                if ($value2->{"bono_detalle.valor"} == 2) {
                                    if ($betmode == "PreLive") {
                                        $cumplecondicionproductoTipo = true;

                                    } else {
                                        $cumplecondicionproductoTipo = false;


                                    }

                                }

                                if ($value2->{"bono_detalle.valor"} == 1) {
                                    if ($betmode == "Live") {
                                        $cumplecondicionproductoTipo = true;

                                    } else {
                                        $cumplecondicionproductoTipo = false;


                                    }

                                }

                                if ($value2->{"bono_detalle.valor"} == 0) {
                                    /*if($betmode == "Mixed") {
                                        $cumplecondicionproductoTipo = true;

                                    }else{
                                        $cumplecondicionproductoTipo = false;


                                    }*/

                                }

                                break;

                            case "MINSELCOUNT":
                                $minselcount = $value2->{"bono_detalle.valor"};

                                if ($value2->{"bono_detalle.valor"} > oldCount($detalleSelecciones)) {
                                    //$cumpleCondicion = false;

                                }

                                break;

                            case "MINSELPRICE":

                                foreach ($detalleSelecciones as $item) {
                                    if ($value2->{"bono_detalle.valor"} > $item->Cuota) {
                                        $cumpleCondicion = false;

                                    }
                                }


                                break;


                            case "MINSELPRICETOTAL":

                                if ($value2->{"bono_detalle.valor"} > $detalleCuotaTotal) {
                                    $cumpleCondicion = false;

                                }


                                break;

                            case "MINBETPRICE":


                                if ($value2->{"bono_detalle.valor"} > $detalleValorApuesta) {
                                    $cumpleCondicion = false;

                                }

                                break;


                            case "MINAMOUNT":
                                if ($value2->{"bono_detalle.moneda"} == $Usuario->moneda) {
                                    $ValorBono1 = $value2->{"bono_detalle.valor"};
                                }

                                break;
                            case "MAXAMOUNT":
                                if ($value2->{"bono_detalle.moneda"} == $Usuario->moneda) {
                                    $ValorBono2 = $value2->{"bono_detalle.valor"};
                                }

                                break;


                            default:

                                break;
                        }

                        if ($_ENV['debug']) {
                            print_r('entroCOND');
                            print_r(PHP_EOL);
                            print_r($cumpleCondicion);
                            print_r(PHP_EOL);
                            print_r($value2);
                        }

                    }

                    if ($_ENV['debug']) {
                        print_r('entro10-1');
                        print_r($cumpleCondicion);
                        print_r(PHP_EOL);
                    }
                    if (!$cumplecondicionproductoTipo) {
                        $cumplecondicionproducto = false;

                    }
                    if ($_ENV['debug']) {
                        print_r('entro110-1');
                        print_r($cumpleCondicion);
                        print_r(PHP_EOL);
                        print_r($cumplecondicionproductoTipo);
                        print_r(PHP_EOL);
                        print_r($cumplecondicionproducto);
                        print_r(PHP_EOL);
                        print_r($condicionesproducto);
                        print_r(PHP_EOL);
                    }
                    if ($cumplecondicionproductoTipo && ($cumplecondicionproducto || $condicionesproducto == 0)) {

                    } else {
                        $cumpleCondicion = false;

                    }


                    if ($_ENV['debug']) {
                        print_r('entro0-1');
                        print_r($cumpleCondicion);
                        print_r(PHP_EOL);
                    }

                    if ($sePuedeCombinadas != 0 || $sePuedeSimples != 0) {

                        if (oldCount($detalleSelecciones) == 1 && !$sePuedeSimples) {
                            $cumpleCondicion = false;
                        }

                        if (oldCount($detalleSelecciones) > 1 && !$sePuedeCombinadas) {
                            $cumpleCondicion = false;
                        }

                        if ($sePuedeCombinadas) {
                            if ($minselcount > 1 && oldCount($detalleSelecciones) < $minselcount) {
                                $cumpleCondicion = false;

                            }
                        }
                    } else {
                        if ($minselcount > 1 && oldCount($detalleSelecciones) < $minselcount) {
                            $cumpleCondicion = false;

                        }
                    }

                    if ($_ENV['debug']) {
                        print_r('entro1-1');
                        print_r($cumpleCondicion);
                        print_r(PHP_EOL);
                    }
                    if (!$cumpleCondicionPais && $cumpleCondicionCont > 0) {
                        $cumpleCondicion = false;

                    }
                    if ($_ENV['debug']) {
                        print_r('entro1-2');
                        print_r($cumpleCondicion);
                        print_r(PHP_EOL);
                    }
                    if ($cumpleCondicion) {

                        $porcentajeBono = 100;
                        $valorBono = ($TransaccionApi->valor * (($porcentajeBono) / 100));
                        if ($ValorBono1 > $valorBono) {
                            $cumpleCondicion = false;
                        }
                        if ($ValorBono2 < $valorBono) {
                            $cumpleCondicion = false;
                        }
                    }

                    if ($_ENV['debug']) {
                        print_r('entro2');
                        print_r($cumpleCondicion);
                        print_r(PHP_EOL);
                        print_r($cumpleCondicion);
                        exit();
                    }
                    if ($cumpleCondicion) {


                        $UsuarioBono = new UsuarioBono($value->{"usuario_bono.usubono_id"});

                        $valorDebit = $TransaccionApi->valor - $valorBono;

                        $UsuarioBono->externoId = $TransaccionApi->getIdentificador();
                        $UsuarioBono->valor = ($UsuarioBono->valor) + $valorBono;
                        $UsuarioBono->estado = 'R';

                        $UsuarioBonoMySqlDAO = new UsuarioBonoMySqlDAO($Transaction);
                        $UsuarioBonoMySqlDAO->update($UsuarioBono);

                        $respuesta["Bono"] = $value->{"usuario_bono.usubono_id"};
                        $respuesta["WinBonus"] = true;
                        $respuesta["AmountDebit"] = $valorDebit;
                        $respuesta["AmountBonus"] = $valorBono;
                        return json_decode(json_encode($respuesta));


                        /*                        $TransjuegoInfo = new TransjuegoInfo();
                                                $TransjuegoInfo->productoId=$TransaccionApi->productoId;
                                                $TransjuegoInfo->transaccionId=$TransaccionApi->transaccionId;
                                                $TransjuegoInfo->tipo="TORNEO";
                                                $TransjuegoInfo->descripcion=$value->{"usuario_bono.usubono_id"};
                                                $TransjuegoInfo->valor=$creditosConvert;
                                                $TransjuegoInfo->transapiId=$TransaccionApi->transapiId;
                                                $TransjuegoInfo->usucreaId=0;
                                                $TransjuegoInfo->usumodifId=0;
                                                $TransjuegoInfo->identificador=$TransaccionApi->identificador;



                                                $TransjuegoInfoMySqlDAO->insert($TransjuegoInfo);
                                                $TransjuegoInfoMySqlDAO->getTransaction()->commit();*/
                    }
                }
                break;


        }


        return json_decode(json_encode($respuesta));

    }

    function undoRolloverProcess(Transaction $Transaction, array &$responseObject)
    {
        $Transaction->rollback();
        $Transaction->getConnection()->close();
        $responseObject["WinBonus"] = false;
        $responseObject["Bono"] = 0;
        $responseObject["UsuarioBono"] = 0;
    }

    /**
     * Verificar si el usuario puede retirar el dinero del bono
     *
     *
     * @param String $usuarioId
     * @param String $detalles
     * @param String $tipoProducto
     * @param String $ticketId
     *
     * @return Array resultado de la consulta
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function verificarBonoRollower($usuarioId, $detalles, $tipoProducto, $ticketId, $ticketId2 = '')
    {
        /**
         * Revierte el proceso de rollover en caso de error.
         *
         * Realiza un rollback en la transacción, cierra la conexión y actualiza el objeto de respuesta
         * para indicar que el bono no fue ganado y que se debe reintentar la operación.
         *
         * @param Transaction $Transaction La transacción activa a revertir.
         * @param array &$responseObject Referencia al objeto de respuesta que será modificado.
         */


        $timeInit = microtime(true);

        if ($ticketId2 == '' && $ticketId != '') {
            $ticketId2 = $ticketId;
        }

        $tipoProductoGlobal = $tipoProducto;
        //Verificamos Detalles a tomar en cuenta al momento de verificar y aumentar rollower
        $detalleTipoApuesta = $detalles->TipoApuesta;
        $detalleSelecciones = $detalles->Selecciones;
        $detalleJuegosCasino = $detalles->JuegosCasino;
        $detalleValorApuesta = $detalles->ValorApuesta;
        $detalleCuotaTotal = 1;

        //Iniciamos array de respuesta negando la obtencion del bono y sin identificar ninguno
        $respuesta = array();
        $respuesta["Bono"] = 0;
        $respuesta["WinBonus"] = false;

        $slackMessage = 'ROLLOVERSLOW PASO 1 ' . $ticketId2 . " - " . ((microtime(true) - $timeInit));


        if (($tipoProducto == "SPORT" || $tipoProducto == "CASINO" || $tipoProducto == "LIVECASINO" || $tipoProducto == "VIRTUAL") && $usuarioId != "") {
            $bonoid = 0;
            $usubono_id = 0;
            $valorBono = 0;
            $valorASumar = 0;
            $valorDelBono = 0;

            //Obtenemos todos los bonos disponibles desde bono interno
            $sqlBono = "select a.usubono_id,a.bono_id,a.apostado,a.valor,a.rollower_requerido,a.fecha_crea,bono_interno.condicional,bono_interno.tipo,a.valor from usuario_bono a INNER JOIN bono_interno ON (bono_interno.bono_id = a.bono_id ) where  a.estado='A' AND (bono_interno.tipo = 2 OR bono_interno.tipo = 3) AND a.usuario_id='" . $usuarioId . "'";

            //ejecutamos las consultas
            $bonosDisponibles = $this->execQuery('', $sqlBono);
            $slackMessage .= ' PASO 2 ' . " -c " . oldCount($bonosDisponibles) . ' ' . ((microtime(true) - $timeInit));

            // Si se encontraron bonos disponibles
            if (oldCount($bonosDisponibles) > 0) {
                // Separamos la logica de sport y casino
                //Discriminamos por tipo sport y con ticket
                if ($tipoProducto == "SPORT" && $ticketId != '') {
                    $ConfigurationEnvironment = new ConfigurationEnvironment();
                    if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ){
                        $connOriginal = $_ENV["connectionGlobal"]->getConnection();




                        $connDB5 = null;


                        if($_ENV['ENV_TYPE'] =='prod') {

                            $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()
                                , array(
                                    PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-bundle.crt',
                                    PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
                                )
                            );
                        }else{

                            $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()

                            );
                        }

                        $connDB5->exec("set names utf8");
                        $connDB5->exec("set use_secondary_engine=off");

                        try{

                            if($_ENV["TIMEZONE"] !=null && $_ENV["TIMEZONE"] !='') {
                                $connDB5->exec('SET time_zone = "'.$_ENV["TIMEZONE"].'";');
                            }

                            if($_ENV["NEEDINSOLATIONLEVEL"] != null && $_ENV["NEEDINSOLATIONLEVEL"] == '1') {
                                $connDB5->exec('SET TRANSACTION ISOLATION LEVEL READ COMMITTED;');
                            }
                            if($_ENV["ENABLEDSETLOCKWAITTIMEOUT"] != null && $_ENV["ENABLEDSETLOCKWAITTIMEOUT"] == '1') {
                                // $connDB5->exec('SET SESSION innodb_lock_wait_timeout = 5;');
                            }
                            if($_ENV["ENABLEDSETMAX_EXECUTION_TIME"] != null && $_ENV["ENABLEDSETMAX_EXECUTION_TIME"] == '1') {
                                // $connDB5->exec('SET SESSION MAX_EXECUTION_TIME = 120000;');
                            }
                            if($_ENV["DBNEEDUTF8"] != null && $_ENV["DBNEEDUTF8"] == '1') {
                                $connDB5->exec("SET NAMES utf8mb4");
                            }
                        }catch (\Exception $e){

                        }
                        $_ENV["connectionGlobal"]->setConnection($connDB5);

                    }

                    //$sqlSport = "select te.betmode,te.vlr_apuesta,td.ticket_id,td.apuesta,td.agrupador,td.logro,td.opcion,td.apuesta_id,td.agrupador_id,td.fecha_evento,td.hora_evento,te.usuario_id,td.sportid,td.matchid,td.ligaid from it_ticket_det td INNER JOIN it_ticket_enc te ON (td.ticket_id = te.ticket_id ) where  te.ticket_id='".$ticketId."' ";
                    $sqlSport = "select te.vlr_apuesta,td.ticket_id,td.apuesta,td.agrupador,td.logro,td.opcion,td.apuesta_id,td.agrupador_id,td.sportid,td.ligaid,td.fecha_evento,td.hora_evento,te.usuario_id,te.bet_mode betmode from it_ticket_det td INNER JOIN it_ticket_enc te ON (td.ticket_id = te.ticket_id ) where  te.ticket_id='" . $ticketId . "' ";


                    $detalleTicket = $this->execQuery('', $sqlSport);


                    if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ) {
                        $connDB5 = null;
                        $_ENV["connectionGlobal"]->setConnection($connOriginal);
                    }


                    $array = array();

                    //Recorremos los detalles de los tickets
                    foreach ($detalleTicket as $detalle) {
                        //guardamos variables a iterar de los detalles de los tickets
                        $detalle->sportid = $detalle->{'td.sportid'};
                        $detalle->ligaid = $detalle->{'td.ligaid'};
                        $detalle->agrupador_id = $detalle->{'td.agrupador_id'};
                        $detalle->logro = $detalle->{'td.logro'};
                        $detalle->vlr_apuesta = $detalle->{'te.vlr_apuesta'};

                        //Generamos un array para guardar detalles importantes de los tickets
                        $detalles = array(
                            "DeporteMercado" => $detalle->sportid . "M" . $detalle->agrupador_id,
                            "Deporte" => $detalle->sportid,
                            "Liga" => $detalle->ligaid,
                            "Evento" => $detalle->apuesta_id,
                            "Cuota" => $detalle->logro

                        );
                        //Reescribimos detalleValor apuesta desde detalles sql a detalles usuario
                        $detalleValorApuesta = $detalle->vlr_apuesta;

                        //ingresamos detalles en el array
                        array_push($array, $detalles);

                        if (!$ConfigurationEnvironment->isDevelopment()) {

                            $usuarioId = $detalle->{'te.usuario_id'};
                        } else {

                        }
                        $betmode = $detalle->{'te.betmode'};

                        //Obtenemos el detalleCuotaTotal calculandolo con el inicializado y el ubicado en tickets detalles
                        $detalleCuotaTotal = $detalleCuotaTotal * $detalle->logro;
                    }
                    //Convertimos en objeto detalles
                    $detallesFinal = json_decode(json_encode($array));

                    // Asignamos detallesFinal a detallesSelecciones
                    $detalleSelecciones = $detallesFinal;


                }

                // Discriminamos por tipo Casino
                if ($tipoProducto == "CASINO") {

                    //Si se ingresaron tickets por el input ticket2
                    if ($ticketId2 != '') {

                        if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ){
                            $connOriginal = $_ENV["connectionGlobal"]->getConnection();




                            $connDB5 = null;


                            if($_ENV['ENV_TYPE'] =='prod') {

                                $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()
                                    , array(
                                        PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-bundle.crt',
                                        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
                                    )
                                );
                            }else{

                                $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()

                                );
                            }

                            $connDB5->exec("set names utf8");
                            $connDB5->exec("set use_secondary_engine=off");

                            try{

                                if($_ENV["TIMEZONE"] !=null && $_ENV["TIMEZONE"] !='') {
                                    $connDB5->exec('SET time_zone = "'.$_ENV["TIMEZONE"].'";');
                                }

                                if($_ENV["NEEDINSOLATIONLEVEL"] != null && $_ENV["NEEDINSOLATIONLEVEL"] == '1') {
                                    $connDB5->exec('SET TRANSACTION ISOLATION LEVEL READ COMMITTED;');
                                }
                                if($_ENV["ENABLEDSETLOCKWAITTIMEOUT"] != null && $_ENV["ENABLEDSETLOCKWAITTIMEOUT"] == '1') {
                                    // $connDB5->exec('SET SESSION innodb_lock_wait_timeout = 5;');
                                }
                                if($_ENV["ENABLEDSETMAX_EXECUTION_TIME"] != null && $_ENV["ENABLEDSETMAX_EXECUTION_TIME"] == '1') {
                                    // $connDB5->exec('SET SESSION MAX_EXECUTION_TIME = 120000;');
                                }
                                if($_ENV["DBNEEDUTF8"] != null && $_ENV["DBNEEDUTF8"] == '1') {
                                    $connDB5->exec("SET NAMES utf8mb4");
                                }
                            }catch (\Exception $e){

                            }
                            $_ENV["connectionGlobal"]->setConnection($connDB5);

                        }


                        // Obtenemos detalles de los tickets desde transjuego_log, transaccion_juego y usuario_mandante

                        $sqlSport = "select transjuego_log.* ,usuario_mandante.usuario_mandante from transjuego_log  
    INNER JOIN transaccion_juego ON (transaccion_juego.transjuego_id=transjuego_log.transjuego_id) 
    INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id=usuario_mandante.usumandante_id) where  transjuego_log.transjuegolog_id='" . $ticketId2 . "' ";
                        //Ejecutamos Consulta y guardamos los detalles de los tickets
                        $detalleTicket = $this->execQuery('', $sqlSport);


                        if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ) {
                            $connDB5 = null;
                            $_ENV["connectionGlobal"]->setConnection($connOriginal);
                        }

                        //Inicializamos array
                        $array = array();

                        //Recorremos los detalles de los tickets
                        foreach ($detalleTicket as $detalle) {
                            //Inicializamos variables
                            $categoryId = '';
                            $subproveedorId = '';

                            try {
                                //Intentamos obtener datos del producto
                                $ProductoMandante = new ProductoMandante('', '', $detalle->{'transjuego_log.producto_id'});

                                $Producto = new Producto($ProductoMandante->productoId);

                                $subproveedorId = $Producto->subproveedorId;

                                //Inicializamos array reglas
                                $rules = [];

                                // Almacenamos en el array los datos de Producto mandante seleccionados
                                array_push($rules, array("field" => "categoria_producto.mandante", "data" => $ProductoMandante->mandante, "op" => "eq"));

                                array_push($rules, array("field" => "producto.producto_id", "data" => $ProductoMandante->productoId, "op" => "eq"));

                                array_push($rules, array("field" => "categoria_producto.pais_id", "data" => $ProductoMandante->paisId, "op" => "eq"));

                                array_push($rules, array("field" => "categoria_producto.estado", "data" => "A", "op" => "eq"));

                                //Almacenamos variables para consulta
                                $orden = "producto.descripcion";
                                $ordenTipo = "asc";

                                //Guardamos reglas en array para filtrar en la consulta
                                $filtro = array("rules" => $rules, "groupOp" => "AND");
                                $jsonfiltro = json_encode($filtro);

                                $CategoriaProducto = new CategoriaProducto();
                                //Generamos consulta
                                $productos = $CategoriaProducto->getCategoriaProductosCustom(" categoria_producto.* ", $orden, $ordenTipo, 0, 10000, $jsonfiltro, true);

                                $productos = json_decode($productos);


                                //Recorremos consulta
                                foreach ($productos->data as $key => $value) {
                                    //Guardamos id de categoria en los diferentes iterables
                                    $categoryId = $categoryId . "," . $value->{"categoria_producto.categoria_id"};
                                }

                            } catch (Exception $e) {

                            }
                            //Generamos array detalles con los datos obtenidos
                            $detalles = array(
                                "Id" => $ProductoMandante->prodmandanteId,
                                "proveedorId" => $Producto->proveedorId,
                                "subproveedorId" => $subproveedorId,
                                "categoryId" => $categoryId
                            );
                            //Reescribimos Valor apuesta desde detalles usuario por detalle ticket
                            $detalleValorApuesta = $detalle->{'transjuego_log.valor'};

                            //Actualizamos array con los detalles
                            array_push($array, $detalles);
                            //Guardamos id de usuario desde detalles ticket
                            $usuarioId = $detalle->{'usuario_mandante.usuario_mandante'};
                        }
                        //Guardamos detalles desde el array convirtiendolo en un objeto
                        $detallesFinal = json_decode(json_encode($array));

                        //Guardamos el objeto en detalleJuegos Casino para diferenciarlo
                        $detalleJuegosCasino = $detallesFinal;

                        //Si el ticket2 viene vacio
                    } else {

                        if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ){
                            $connOriginal = $_ENV["connectionGlobal"]->getConnection();




                            $connDB5 = null;


                            if($_ENV['ENV_TYPE'] =='prod') {

                                $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()
                                    , array(
                                        PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-bundle.crt',
                                        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
                                    )
                                );
                            }else{

                                $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()

                                );
                            }

                            $connDB5->exec("set names utf8");
                            $connDB5->exec("set use_secondary_engine=off");

                            try{

                                if($_ENV["TIMEZONE"] !=null && $_ENV["TIMEZONE"] !='') {
                                    $connDB5->exec('SET time_zone = "'.$_ENV["TIMEZONE"].'";');
                                }

                                if($_ENV["NEEDINSOLATIONLEVEL"] != null && $_ENV["NEEDINSOLATIONLEVEL"] == '1') {
                                    $connDB5->exec('SET TRANSACTION ISOLATION LEVEL READ COMMITTED;');
                                }
                                if($_ENV["ENABLEDSETLOCKWAITTIMEOUT"] != null && $_ENV["ENABLEDSETLOCKWAITTIMEOUT"] == '1') {
                                    // $connDB5->exec('SET SESSION innodb_lock_wait_timeout = 5;');
                                }
                                if($_ENV["ENABLEDSETMAX_EXECUTION_TIME"] != null && $_ENV["ENABLEDSETMAX_EXECUTION_TIME"] == '1') {
                                    // $connDB5->exec('SET SESSION MAX_EXECUTION_TIME = 120000;');
                                }
                                if($_ENV["DBNEEDUTF8"] != null && $_ENV["DBNEEDUTF8"] == '1') {
                                    $connDB5->exec("SET NAMES utf8mb4");
                                }
                            }catch (\Exception $e){

                            }
                            $_ENV["connectionGlobal"]->setConnection($connDB5);

                        }

                        //Seleccionamos detalles de tickets desde transaccio_api y usuario_mandante

                        //$sqlSport = "select ct.tipo,ct.monto,ct.juego_id,ct.id,ct.usuario_id from casino_transaccion ct where  ct.id='".$ticketId."' ";
                        $sqlSport = "select transaccion_api.* ,usuario_mandante.usuario_mandante from transaccion_api  INNER JOIN usuario_mandante ON (transaccion_api.usuario_id=usuario_mandante.usumandante_id) where  transaccion_api.transapi_id='" . $ticketId . "' ";
                        $detalleTicket = $this->execQuery('', $sqlSport);

                        if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ) {
                            $connDB5 = null;
                            $_ENV["connectionGlobal"]->setConnection($connOriginal);
                        }

                        //Inicializamos array
                        $array = array();

                        //Recorremos los detalles del ticket
                        foreach ($detalleTicket as $detalle) {
                            //Inicializamos variables a utilizar
                            $categoryId = '';
                            $subproveedorId = '';
                            try {
                                //Intentamos obtener datos del producto
                                $ProductoMandante = new ProductoMandante('', '', $detalle->{'transaccion_api.producto_id'});

                                $Producto = new Producto($ProductoMandante->productoId);

                                $subproveedorId = $Producto->subproveedorId;

                                //Inicializamos array reglas
                                $rules = [];


                                array_push($rules, array("field" => "categoria_producto.mandante", "data" => $ProductoMandante->mandante, "op" => "eq"));

                                array_push($rules, array("field" => "producto.producto_id", "data" => $ProductoMandante->productoId, "op" => "eq"));

                                array_push($rules, array("field" => "categoria_producto.pais_id", "data" => $ProductoMandante->paisId, "op" => "eq"));

                                array_push($rules, array("field" => "categoria_producto.estado", "data" => "A", "op" => "eq"));

                                //Almacenamos variables para consulta
                                $orden = "producto.descripcion";
                                $ordenTipo = "asc";

                                //Guardamos reglas en array para filtrar en la consulta
                                $filtro = array("rules" => $rules, "groupOp" => "AND");
                                $jsonfiltro = json_encode($filtro);

                                $CategoriaProducto = new CategoriaProducto();
                                $productos = $CategoriaProducto->getCategoriaProductosCustom(" categoria_producto.* ", $orden, $ordenTipo, 0, 10000, $jsonfiltro, true);

                                $productos = json_decode($productos);

                                $productosString = '##';

                                //Recorremos consulta
                                foreach ($productos->data as $key => $value) {
                                    //Guardamos id de categoria en los diferentes iterables
                                    $categoryId = $categoryId . "," . $value->{"categoria_producto.categoria_id"};
                                }

                            } catch (Exception $e) {

                            }
                            //Generamos array detalles con algunos datos obtenidos
                            $detalles = array(
                                "Id" => $detalle->{'transaccion_api.producto_id'},
                                "proveedorId" => $detalle->{'transaccion_api.proveedor_id'},
                                "subproveedorId" => $subproveedorId,
                                "categoryId" => $categoryId
                            );
                            //Reescribimos Valor apuesta desde detalles usuario por detalle ticket
                            $detalleValorApuesta = $detalle->{'transaccion_api.valor'};

                            //Actualizamos array con los detalles
                            array_push($array, $detalles);
                            //Guardamos id de usuario desde detalles ticket
                            $usuarioId = $detalle->{'usuario_mandante.usuario_mandante'};
                        }
                        //Guardamos detalles desde el array convirtiendolo en un objeto
                        $detallesFinal = json_decode(json_encode($array));

                        //Guardamos el objeto en detalleJuegos Casino para diferenciarlo
                        $detalleJuegosCasino = $detallesFinal;
                    }

                }


                //Sanitizando parámetros
                $patronDenegado = "/\D/";
                if (preg_match($patronDenegado, $ticketId)) $ticketIdSeguro = false;
                else $ticketIdSeguro = true;

                if (preg_match($patronDenegado, $ticketId2)) $ticketId2Seguro = false;
                else $ticketId2Seguro = true;


                //Determinando vertical
                $vertical = null;
                if ($tipoProductoGlobal == 'SPORT') $vertical = 'SPORT';
                elseif (empty($vertical) && !empty($ticketId) && $ticketIdSeguro) {
                    //Solicitando vertical
                    $sqlVertical = "select proveedor.tipo from transaccion_api inner join proveedor on (transaccion_api.proveedor_id = proveedor.proveedor_id) where transapi_id = " . $ticketId;
                    $sqlVerticalResultado = $this->execQuery('', $sqlVertical);
                    $vertical = $sqlVerticalResultado[0]->{'proveedor.tipo'};
                } elseif (empty($vertical) && !empty($ticketId2) && $ticketId2Seguro) {
                    //Solicitando vertical
                    $sqlVertical = "select proveedor.tipo from transjuego_log inner join transaccion_juego on (transjuego_log.transjuego_id = transaccion_juego.transjuego_id) inner join producto_mandante on (transaccion_juego.producto_id = producto_mandante.prodmandante_id) inner join producto on (producto_mandante.producto_id = producto.producto_id) inner join proveedor on (producto.proveedor_id = proveedor.proveedor_id) where transjuegolog_id = " . $ticketId2;
                    $sqlVerticalResultado = $this->execQuery('', $sqlVertical);
                    $vertical = $sqlVerticalResultado[0]->{'proveedor.tipo'};
                }

            }
            // - Fin de obtener detalles por tipo de apuesta (Casino o Bet) -

            //Recorremos los bonos disponibles
            foreach ($bonosDisponibles as $bono) {

                //Guardamos variables desde bono_interno
                $bono->bono_id = $bono->{'a.bono_id'};
                $bono->tipo = $bono->{'a.tipo'};
                $bono->valor = $bono->{'a.valor'};
                $bono->tipo = $bono->{'bono_interno.tipo'};
                $bono->fecha_inicio = $bono->{'a.fecha_inicio'};
                $bono->fecha_fin = $bono->{'a.fecha_fin'};
                $bono->usubono_id = $bono->{'a.usubono_id'};
                $bono->fecha_crea = $bono->{'a.fecha_crea'};
                $bono->apostado = $bono->{'a.apostado'};
                $bono->rollower_requerido = $bono->{'a.rollower_requerido'};

                // Si el bono no se ha seleccionado
                if ($bonoid == 0) {

                    //Obtenemos todos los detalles del bono
                    $sqlDetalleBono = "select * from bono_detalle a where a.bono_id='" . $bono->bono_id . "' 
  AND (a.tipo IN (
                  'TIPOPRODUCTO',
                  'EXPDIA',
                  'EXPFECHA',
                  'LIVEORPREMATCH',
                  'MINSELCOUNT',
                  'MINSELPRICE',
                  'MINSELPRICETOTAL',
                  'MINBETPRICE',
                  'MINBETAMOUNTROLLOVER',
                  'WINBONOID',
                  'TIPOSALDO',
                  'AMOUNTBONUSMAXSPIN',
                  'MAXBETAMOUNTROLLOVER',
                  'AMOUNTBONUSMAXROLLOVER',
                  'ITAINMENT1',
                  'ITAINMENT3',
                  'ITAINMENT4',
                  'ITAINMENT5',
                  'ITAINMENT82',
                  'BONUSPLANIDALTENAR',
                  'BONUSCODEALTENAR'
    ) or a.tipo like '%CONDGAME%' or a.tipo like '%CONDPROVIDER%' or a.tipo like '%CONDSUBPROVIDER%' or a.tipo like '%CONDCATEGORY%')  ";
                    $bonoDetalles = $this->execQuery('', $sqlDetalleBono);
                    $slackMessage .= ' PASO 3 ' . " - " . ((microtime(true) - $timeInit));


                    //Inicializamos variables

                    $bonoid = 0;
                    $valorapostado = 0;
                    $valorrequerido = 0;


                    ////////////////////////////////////////////////////////////////////////////////////////////////

                    // Uso de la nueva funcionalidad

                    if (!is_array($detalles)) {
                        if ($detalles == '') {
                            $detalles = [];
                        } else {
                            $detalles = get_object_vars($detalles);
                        }
                    }

                    if ($detalleValorApuesta != null) $detalles['ValorApuesta'] = $detalleValorApuesta;
                    if ($detalleJuegosCasino != null) $detalles['JuegosCasino'] = $detalleJuegosCasino;
                    if ($detalleSelecciones != null) $detalles['Selecciones'] = $detalleSelecciones;
                    if ($detalleCuotaTotal != null) $detalles['CuotaTotal'] = $detalleCuotaTotal;
                    if ($betmode != null) $detalles['BetMode'] = $betmode;

                    $detalles = json_decode(json_encode($detalles));

                    $tipoProducto = $tipoProductoGlobal;
                    $validate = $this->validarCondiciones($bonoDetalles, $detalles, $tipoProducto, $usuarioId, $isForLealtad = false, $cumpleCondiciones = '', $transaccion = '', $bono, $tipoBono = '', false,true);
                    $slackMessage .= ' PASO 4 ' . " - " . ((microtime(true) - $timeInit));

                    //Variables usadas en el codigo y que provee Validar condiciones
                    $tipoProducto = $validate->tipoProducto;
                    $cumplecondicion = $validate->cumplecondicion;
                    $ganaBonoId = $validate->ganaBonoId;
                    $cumplecondicionproducto = $validate->cumplecondicionproducto;
                    $tiposaldo = $validate->tiposaldo;
                    $condicionesproducto = $validate->condicionesproducto;
                    $valorASumar = $validate->valorASumar;
                    $AMOUNTBONUSMAXSPIN = $validate->AMOUNTBONUSMAXSPIN;
                    $valorMaximoASumar = $validate->valorMaximoASumar;

                    // - Comenzamos discriminación por tipoProducto ingresado por el input tipoProducto -
                    //Si este viene por Casino
                    if ($tipoProductoGlobal == "CASINO") {

                        //Si se cumple condicones de bono_detalles
                        if ($cumplecondicion) {
                            $transApi = ($ticketId != 0 && $ticketId != "''" && $ticketId != "") ? $ticketId : $ticketId2;

                            //Obtenemos rollower a analizar del bono
                            $sqlROLLOWER = "
                         SELECT t.*
FROM transjuego_info t
WHERE tipo = 'ROLLOWER' AND transapi_id='" . $transApi . "' ";
                            $sqlROLLOWER = $this->execQuery('', $sqlROLLOWER);

                            if ((oldCount($sqlROLLOWER) == 0)) {
                            } else {
                                //Si se encuentra el rollower ejecutado
                                $cumplecondicion = false;
                            }
                        }
                    }

                    // Si este viene por Sport
                    if ($tipoProductoGlobal == "SPORT") {

                        //Si se cumple condicones de bono_detalles
                        if ($cumplecondicion) {
                            //Obtenemos rollower a analizar del bono
                            $sqlROLLOWER = "
                         SELECT t.*
FROM it_ticket_enc_info1 t
WHERE t.tipo = 'ROLLOWER'  AND ticket_id='" . $ticketId . "' ";

                            $sqlROLLOWER = $this->execQuery('', $sqlROLLOWER);
                            $slackMessage .= ' PASO 5 ' . " - " . ((microtime(true) - $timeInit));


                            if ((oldCount($sqlROLLOWER) == 0)) {
                            } else {
                                //Si se encuentra el bono
                                $cumplecondicion = false;
                            }
                        }
                    }
                    // - Finalizamos discriminación por tipoProducto ingresado por el input tipoProducto -

                    //Verificamos condiciones para analizar rollower del bono
                    if ($cumplecondicion && ($cumplecondicionproducto || $condicionesproducto == 0)) {


                        //Capturamos datos a tener en cuenta desde bono interno
                        $bonoid = $bono->bono_id;
                        $usubono_id = $bono->usubono_id;
                        $valorBono = $bono->valor;
                        $valorapostado = $bono->apostado;
                        $valorrequerido = $bono->rollower_requerido;


                        $valorDelBono = $bono->valor;
                        $bonotipo = $bono->tipo;
                    }

                }

            }
            $slackMessage .= ' PASO 95 ' . " - " . ((microtime(true) - $timeInit));

            //Comenzamos logica de verificacion de rollower del bono
            // Si no existe bonoid nos dirigimos a la respuesta de la función con bono = 0 y win bonus = false
            if ($bonoid != 0) {
                /*Registrando inicio de procesamiento*/
                $logCronType = "ROLLOVER_{$tipoProductoGlobal}";
                $transApi = ($ticketId != 0 && $ticketId != "''" && $ticketId != "") ? $ticketId : $ticketId2;
                $cronLogPreValidationSql = "SELECT logcron_id FROM log_cron WHERE tipo = '".$logCronType."' AND usuario_id = {$usuarioId} AND valor_id2 = {$transApi} AND estado LIKE 'INIT%' limit 1";
                $preLogCronData = $this->execQuery("", $cronLogPreValidationSql);

                $logCronId = 0;
                if (!empty($preLogCronData) && oldCount($preLogCronData)>0) {
                    $logCronId = ($preLogCronData[0])->{'log_cron.logcron_id'};
                } else {
                    $logCronId = $this->createLog($logCronType, $usuarioId, $usubono_id, $transApi, 0, "", "", "INIT");
                }


                //Zona de redención o actualización del bono
                $accreditationTransaction = new Transaction();


                //Verificamos tipo de producto de bono detalles si este es dos guardamos el valor a sumar capturado
                // desde detalles usuario o desde la logica de tickets
                if ($tipoProducto == 2) {
                    $valorASumar = $detalleValorApuesta;

                }

                if ($AMOUNTBONUSMAXSPIN == '1') {
                    //Configuramos no rebose del valor a sumar
                    if (floatval($valorASumar) > floatval($valorDelBono)) {
                        $valorASumar = $valorDelBono;
                    }
                }

                //Gana bono si cumple que el valor apostado de bono interno mas valor apuesta desde ticket o desde
                // detalles usuario es mayor al valor del rollower capturado en bono interno
                if (($valorapostado + $detalleValorApuesta) >= $valorrequerido) {
                    $winBonus = true;
                }


                //Configuramos no rebose del valor a sumar
                if ($valorMaximoASumar == '1' && floatval($valorASumar) > floatval($valorBono)) {
                    $valorASumar = $valorBono;
                }

                //Configuramos array de las consultas y reiniciamos contador
                $strSql = array();
                $contSql = 0;

                //Configuramos valor a sumar nulo
                if ($valorASumar == '') {
                    $valorASumar = 0;
                }

                // SQL que actualiza el valor apostado de la tabla usuario_bono
                $contSql = $contSql + 1;
                $strSql[$contSql] = "UPDATE usuario_bono SET apostado = apostado + " . ($valorASumar) . " WHERE usubono_id = " . $usubono_id . " AND apostado < rollower_requerido AND estado = 'A'";
                $initialUpdateSql =  end($strSql);

                try {
                    $updatedRows = 0;
                    $updatedRows = $this->execUpdate($accreditationTransaction, $initialUpdateSql);
                } catch (Exception $e) {
                    try{
                        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php 'ROLL1 " . $usuarioId . " ". $tipoProducto. " ". $ticketId. " ". $ticketId2 . " ' '#dev2' > /dev/null & ");

                    }catch (Exception $exception){

                    }
                    $this->undoRolloverProcess($accreditationTransaction, $respuesta);
                    goto RolloverResponseSection;
                }

                if ($updatedRows < 1) {

                    try{
                        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php 'ROLL2 " . $usuarioId . " ". $tipoProducto. " ". $ticketId. " ". $ticketId2 . " ' '#dev2' > /dev/null & ");

                    }catch (Exception $exception){

                    }
                    $this->undoRolloverProcess($accreditationTransaction, $respuesta);
                    goto RolloverResponseSection;
                }

                //Discrimnamos por tipo de producto ingresado en el input tipoProducto
                if ($tipoProductoGlobal == "SPORT") {
                    //Verificamos que ticket no venga vacio
                    if ($ticketId != '') {
                        $contSql = $contSql + 1;
                        //SQL que inserta informacion sobre el rollower del ticket con su valor a sumar, usuario y el ticket especifico
                        $strSql[$contSql] = "INSERT INTO it_ticket_enc_info1 (ticket_id,tipo,valor,valor2)  VALUES ( " . $ticketId . ",'ROLLOWER'," . $usubono_id . ",'" . $valorASumar . "') ";
                        $logInsertSql =  end($strSql);
                        $updatedRows = 0;
                        $updatedRows = $this->execUpdate($accreditationTransaction, $logInsertSql);

                        if ($updatedRows < 1) {
                            try{
                                exec("php -f /home/home2/backend/api/src/imports/Slack/message.php 'ROLL3 " . $usuarioId . " ". $tipoProducto. " ". $ticketId. " ". $ticketId2 . " ' '#dev2' > /dev/null & ");

                            }catch (Exception $exception){

                            }
                            $this->undoRolloverProcess($accreditationTransaction, $respuesta);
                            goto RolloverResponseSection;
                        }
                    }
                }

                //Discrimnamos por tipo de producto ingresado en el input tipoProducto
                if ($tipoProductoGlobal == "CASINO") {
                    //Verificamos que tickete no venga vacio
                    if ($ticketId == '') {
                        $ticketId = 0;
                    }
                    if ($ticketId2 == '') {
                        $ticketId2 = '0';
                    }

                    //Variable para la consulta dependiendo del ticket
                    $transApi = ($ticketId != 0 && $ticketId != "''") ? $ticketId : $ticketId2;

                    $contSql = $contSql + 1;
                    //SQL que inserta informacion sobre el rollower del ticket con su valor a sumar, usuario y el ticket especifico
                    $strSql[$contSql] = "INSERT INTO transjuego_info (tipo,transaccion_id,descripcion,valor,producto_id,transapi_id,identificador,usucrea_id,usumodif_id)  VALUES ( 'ROLLOWER','','" . $usubono_id . "','" . $valorASumar . "','0','" . $transApi . "','',0,0) ";
                    $logInsertSql =  end($strSql);
                    $updatedRows = 0;
                    $updatedRows = $this->execUpdate($accreditationTransaction, $logInsertSql);

                    if ($updatedRows < 1) {
                        try{
                            exec("php -f /home/home2/backend/api/src/imports/Slack/message.php 'ROLL4 " . $usuarioId . " ". $tipoProducto. " ". $ticketId. " ". $ticketId2 . " ' '#dev2' > /dev/null & ");

                        }catch (Exception $exception){

                        }
                        $this->undoRolloverProcess($accreditationTransaction, $respuesta);
                        goto RolloverResponseSection;
                    }
                }

                #Verificando la vertical a la cual corresponde la apuesta acumulada en el rollover
                $tipoRollover = null;


                $slackMessage .= ' PASO 20 ' . " - " . ((microtime(true) - $timeInit));

                //Determinando tipo de Log
                $bonosAceptados = [2, 3]; //Solo se realiza log para bonos depósito (2) y NO depósito (3)
                if (in_array($bonotipo, $bonosAceptados)) {
                    $verticalesCasino = ['CASINO', 'LIVECASINO', 'VIRTUAL'];
                    if ($vertical == 'SPORT') {
                        //Configurando tipos para la vertical de deportivas
                        if ($bonotipo == 2) $tipoRollover = 'D';
                        elseif ($bonotipo == 3) $tipoRollover = 'ND';
                    } elseif (in_array($vertical, $verticalesCasino)) {
                        //Configurando tipos según la vertical de casino
                        if ($bonotipo == 2) $tipoRollover = 'D';
                        elseif ($bonotipo == 3) $tipoRollover = 'N';

                        $tipoRollover .= $vertical[0];
                    }
                }

                if ($tipoRollover == null) $tipoRollover = 'F';

                // Si no vino ninguna notificacion de que gano bono por bono_detalles
                if ($ganaBonoId == 0) {
                    //Comenzamos cambio de estados en la base de datos sobre el bono, este pasará a redimido si usuario_bono.apostado >= usuario_bono.rollower_requerido
                    switch ($tiposaldo) {
                        case 0:
                            $contSql = $contSql + 1;
                            //si usuario_bono.apostado >= usuario_bono.rollower_requerido actualizamos estado del bono de A -> R
                            $strSql[$contSql] = "UPDATE usuario_bono,registro SET usuario_bono.estado = 'R',registro.creditos_base_ant=registro.creditos_base,registro.creditos_base=registro.creditos_base + usuario_bono.valor,registro.creditos_bono=registro.creditos_bono - usuario_bono.valor   WHERE  registro.usuario_id= usuario_bono.usuario_id AND usuario_bono.apostado >= usuario_bono.rollower_requerido AND usuario_bono.usubono_id = " . $usubono_id . " AND usuario_bono.estado='A'";
                            $bonusRedeemLogSql =  end($strSql);
                            $updatedRows = 0;
                            $updatedRows = $this->execUpdate($accreditationTransaction, $bonusRedeemLogSql);

                            //Inserta usuario historial
                            $contSql = $contSql + 1;
                            $strSql[$contSql] = "insert into usuario_historial (usuario_id, descripcion, movimiento,usucrea_id,usumodif_id, tipo, valor, externo_id,creditos,creditos_base) SELECT usuario_bono.usuario_id,'','E',0,0,50,usuario_bono.valor,usuario_bono.usubono_id,creditos,creditos_base FROM usuario_bono INNER JOIN registro ON (registro.usuario_id = usuario_bono.usuario_id) WHERE usuario_bono.estado='R' AND usuario_bono.usubono_id = " . $usubono_id;
                            $balanceHistoryUpdateSql =  end($strSql);
                            $updatedRows = 0;
                            $updatedRows = $this->execUpdate($accreditationTransaction, $balanceHistoryUpdateSql);

                            break;

                        case 1:
                            $contSql = $contSql + 1;
                            //si usuario_bono.apostado >= usuario_bono.rollower_requerido actualizamos estado del bono de A -> R
                            $strSql[$contSql] = "UPDATE usuario_bono,registro SET usuario_bono.estado = 'R',registro.creditos_ant=registro.creditos,registro.creditos=registro.creditos + usuario_bono.valor,registro.creditos_bono=registro.creditos_bono - usuario_bono.valor   WHERE  registro.usuario_id= usuario_bono.usuario_id AND usuario_bono.apostado >= usuario_bono.rollower_requerido AND usuario_bono.usubono_id = " . $usubono_id . " AND usuario_bono.estado='A'";
                            $estadoBono = 'R';
                            $bonusRedeemLogSql =  end($strSql);
                            $updatedRows = 0;
                            $updatedRows = $this->execUpdate($accreditationTransaction, $bonusRedeemLogSql);

                            //Inserta usuario historial
                            $contSql = $contSql + 1;
                            $strSql[$contSql] = "insert into usuario_historial (usuario_id, descripcion, movimiento,usucrea_id,usumodif_id, tipo, valor, externo_id,creditos,creditos_base) SELECT usuario_bono.usuario_id,'','E',0,0,50,usuario_bono.valor,usuario_bono.usubono_id,creditos,creditos_base FROM usuario_bono INNER JOIN registro ON (registro.usuario_id = usuario_bono.usuario_id) WHERE usuario_bono.estado='R' AND usuario_bono.usubono_id = " . $usubono_id;
                            $balanceHistoryUpdateSql =  end($strSql);
                            $updatedRows = 0;
                            $updatedRows = $this->execUpdate($accreditationTransaction, $balanceHistoryUpdateSql);
                            break;

                        case 2:

                            $contSql = $contSql + 1;
                            //si usuario_bono.apostado >= usuario_bono.rollower_requerido actualizamos estado del bono de A -> R
                            $strSql[$contSql] = "UPDATE usuario_bono,registro SET usuario_bono.estado = 'R',registro.saldo_especial=registro.saldo_especial + usuario_bono.valor,registro.creditos_bono=registro.creditos_bono - usuario_bono.valor   WHERE  registro.usuario_id= usuario_bono.usuario_id AND usuario_bono.apostado >= usuario_bono.rollower_requerido AND usuario_bono.usubono_id = " . $usubono_id . " AND usuario_bono.estado='A'";
                            $estadoBono = 'R';
                            $bonusRedeemLogSql =  end($strSql);
                            $updatedRows = 0;
                            $updatedRows = $this->execUpdate($accreditationTransaction, $bonusRedeemLogSql);

                            //Inserta usuario historial
                            $contSql = $contSql + 1;
                            $strSql[$contSql] = "insert into usuario_historial (usuario_id, descripcion, movimiento,usucrea_id,usumodif_id, tipo, valor, externo_id,creditos,creditos_base) SELECT usuario_bono.usuario_id,'','E',0,0,50,usuario_bono.valor,usuario_bono.usubono_id,creditos,creditos_base FROM usuario_bono INNER JOIN registro ON (registro.usuario_id = usuario_bono.usuario_id) WHERE usuario_bono.estado='R' AND usuario_bono.usubono_id = " . $usubono_id;
                            $balanceHistoryUpdateSql =  end($strSql);
                            $updatedRows = 0;
                            $updatedRows = $this->execUpdate($accreditationTransaction, $balanceHistoryUpdateSql);
                            break;

                    }

                    $contSql++;
                    $strSql[$contSql] = "INSERT INTO bono_log (usuario_id,tipo,valor,estado,id_externo,mandante,transaccion_id,tipobono_id,fecha_crea,fecha_cierre) SELECT a.usuario_id,'" . $tipoRollover . "',a.valor,'L',a.usubono_id,usuario.mandante,'0',4,now(),now()  FROM  usuario_bono a INNER JOIN bono_interno  b ON (b.bono_id = a.bono_id) inner join usuario on (a.usuario_id = usuario.usuario_id)  WHERE a.usubono_id = " . $usubono_id . " AND a.apostado >= a.rollower_requerido AND a.estado='R'";
                    $bonoLogInsertionSql =  end($strSql);
                    $updatedRows = 0;
                    $updatedRows = $this->execUpdate($accreditationTransaction, $bonoLogInsertionSql);
                }
                $slackMessage .= ' PASO 30 ' . " - " . ((microtime(true) - $timeInit));

                //Devolvemos valores referentes a que se ha ganado el bono
                $respuesta["WinBonus"] = true;
                $respuesta["Bono"] = $bonoid;
                $respuesta["UsuarioBono"] = $usubono_id;
                $respuesta["queries"] = $strSql;

                //Generamos el commit de las transacciones modificando la base de datos
                $slackMessage .= ' PASO 40 ' . " - " . ((microtime(true) - $timeInit));

                //Logica referente a WINBONUS por bono_detalles tiene indicada la información de que ha ganado el bono
                if ($ganaBonoId != 0) {
                    //Seleccionamos todos los bonos no depositos y depositos que cumplan con que el estado sea A, y el usubono_id sea el mismo
                    $sqlBono2 = "select a.usuario_id, usuario.mandante,a.usubono_id,a.bono_id,a.apostado,a.rollower_requerido,a.fecha_crea,bono_interno.condicional,bono_interno.tipo from usuario_bono a INNER JOIN bono_interno ON (bono_interno.bono_id = a.bono_id ) INNER JOIN usuario ON (a.usuario_id = usuario.usuario_id) where  a.estado='A' AND (bono_interno.tipo = 2 OR bono_interno.tipo = 3) AND a.usubono_id='" . $usubono_id . "'";

                    //Guardamos los bonos disponibles
                    $bonosDisponibles2 = $this->execQuery($accreditationTransaction, $sqlBono2);
                    $slackMessage .= ' PASO 50 ' . " - " . ((microtime(true) - $timeInit));

                    //Guardamos valor del rollower que se requiere para ganar el bono
                    $rollower_requerido = $bonosDisponibles2[0]->{'a.rollower_requerido'};
                    //Guardamos lo que se ha registrado como apostado por parte de las transferencias del usuario
                    $apostado = $bonosDisponibles2[0]->{'a.apostado'};


                    //Si el valor de apostado es mayor al rollower comenzamos logica de redención del bono mediante agregarBonoFree()
                    if ($apostado >= $rollower_requerido) {
                        try {

                            $usuarioSql = "select ciudad.ciudad_id,ciudad.depto_id,pais.pais_id FROM registro
                            INNER JOIN ciudad ON (ciudad.ciudad_id = registro.ciudad_id)
                            INNER JOIN departamento ON (ciudad.depto_id = departamento.depto_id)
                            INNER JOIN pais ON (pais.pais_id = departamento.pais_id) WHERE registro.usuario_id='" . $bonosDisponibles2[0]->{'a.usuario_id'} . "'";

                            $dataUsuario = $this->execQuery('', $usuarioSql);
                            $slackMessage .= ' PASO 60 ' . " - " . ((microtime(true) - $timeInit));

                            // Obtenemos toda la info del usuario para obtener el valor de la moneda ya que la otra consulta no lo permite
                            $Usuario2 = new Usuario($bonosDisponibles2[0]->{'a.usuario_id'});

                            $detalles = array(
                                "PaisUSER" => $dataUsuario['pais_id'] ?? $dataUsuario[0]->{'pais.pais_id'},
                                "DepartamentoUSER" => $dataUsuario['depto_id'] ?? $dataUsuario[0]->{'ciudad.depto_id'},
                                "CiudadUSER" => $dataUsuario['ciudad_id'] ?? $dataUsuario[0]->{'ciudad.ciudad_id'},
                                "MonedaUSER" => $Usuario2->moneda, //Agregado nuevo para poder que al cliente se le entregue el saldo
                            );
                            $detalles = json_decode(json_encode($detalles));

                            // Se agrega ejecutarSQL = true para que se puede asignar el bono correctamente
                            $respuesta2 = $this->agregarBonoFree($ganaBonoId, $bonosDisponibles2[0]->{'a.usuario_id'}, $bonosDisponibles2[0]->{'usuario.mandante'}, $detalles, '', '', $accreditationTransaction);

                            //Cambiamos el estado de A -> R en usuario_bono del bono seleccionado

                            $querie = "UPDATE usuario_bono SET usuario_bono.estado = 'R'   WHERE usuario_bono.usubono_id = " . $usubono_id . " AND usuario_bono.estado='A'";
                            $updatedRows = 0;
                            $updatedRows = $this->execUpdate($accreditationTransaction, $querie);
                            if ($updatedRows < 1) {
                                try{
                                    exec("php -f /home/home2/backend/api/src/imports/Slack/message.php 'ROLL5 " . $usuarioId . " ". $tipoProducto. " ". $ticketId. " ". $ticketId2 . " ' '#dev2' > /dev/null & ");

                                }catch (Exception $exception){

                                }
                                $this->undoRolloverProcess($accreditationTransaction, $respuesta);
                                goto RolloverResponseSection;
                            }


                            //Guardamos logs
                            $querie = "INSERT INTO bono_log (usuario_id,tipo,valor,estado,id_externo,mandante,transaccion_id,tipobono_id,fecha_crea,fecha_cierre) SELECT a.usuario_id,'" . $tipoRollover . "',a.valor,'L',a.usubono_id,usuario.mandante,'0',4,now(),now()  FROM  usuario_bono a INNER JOIN bono_interno  b ON (b.bono_id = a.bono_id) inner join usuario on (a.usuario_id = usuario.usuario_id)  WHERE a.usubono_id = " . $usubono_id . " AND a.apostado >= a.rollower_requerido AND a.estado='R'";
                            $updatedRows = 0;
                            $updatedRows = $this->execUpdate($accreditationTransaction, $querie);
                            if ($updatedRows < 1) {
                                try{
                                    exec("php -f /home/home2/backend/api/src/imports/Slack/message.php 'ROLL6 " . $usuarioId . " ". $tipoProducto. " ". $ticketId. " ". $ticketId2 . " ' '#dev2' > /dev/null & ");

                                }catch (Exception $exception){

                                }
                                $this->undoRolloverProcess($accreditationTransaction, $respuesta);
                                goto RolloverResponseSection;
                            }

                            $slackMessage .= ' PASO 70 ' . " - " . ((microtime(true) - $timeInit));
                        } catch (Exception $e) {

                        }

                        //Devolvemos respuesta sobre la redención del bono por parte de la función con la info del bono y las consultas
                        $respuesta["WinBonus"] = true;
                        $respuesta["Bono"] = $bonoid;
                        $respuesta["UsuarioBono"] = $usubono_id;
                        $respuesta["queries"] = $strSql;

                    }

                }

                $accreditationTransaction->commit();

                /*Se actualiza registro de procesamiento*/
                try {
                    if($usuarioId =='10243965' || $usuarioId =='8290198'){
                        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php 'FINAL" . $usuarioId . " ". $tipoProducto. " ". $ticketId. " ". $ticketId2 .$slackMessage. " ' '#dev2' > /dev/null & ");

                    }

                    $this->updateLog($logCronId, "COMPLETE");
                } catch (Exception $e) {
                }

            }
            $slackMessage .= ' PASO 98 ' . " - " . ((microtime(true) - $timeInit));
        }
        $slackMessage .= ' PASO 100 ' . " - " . ((microtime(true) - $timeInit));


        /*Etiqueta zona de respuesta, evite ubicar lógica de negocio después de esta zona */
        RolloverResponseSection:
        return json_decode(json_encode($respuesta)); //Respuesta de la función

    }


    function createLog($tipo, $usuario_id, $valor_id1, $valor_id2, $valor_id3, $valor1, $valor2, $estado)
    {
        $BonoInterno = new BonoInterno();
        $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();
        $transaction = $BonoInternoMySqlDAO->getTransaction();

        if (strpos($valor_id1, '==') !== false) {
            $valor_id1 = base64_decode($valor_id2);
        }

        if (strpos($valor_id2, '==') !== false) {
            $valor_id2 = base64_decode($valor_id2);
        }

        $sql = "
INSERT INTO log_cron (tipo, usuario_id, valor_id1, valor_id2, valor_id3, valor1, valor2, fecha_crea, fecha_modif,
                             estado)
VALUES ('$tipo', '" . ($usuario_id != '' ? $usuario_id : '0') . "', '$valor_id1', '$valor_id2', '$valor_id3', '$valor1', '$valor2', DEFAULT, DEFAULT, '$estado');

";
        $resultsql = $BonoInterno->execUpdate($transaction, $sql, 1);
        $transaction->commit();
        return $resultsql;
    }

    function updateLog($logcron_id, $estado, $valor1 = '', $valor2 = '')
    {

        $BonoInterno = new BonoInterno();
        $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();
        $transaction = $BonoInternoMySqlDAO->getTransaction();

        $sql = "
            UPDATE log_cron SET estado='$estado'
    ";
        if ($valor1 != '') {
            $sql .= ",valor1='" . str_replace("'", '"', $valor1) . "' ";
        }
        if ($valor2 != '') {
            $sql .= ",valor2='" . str_replace("'", '"', $valor2) . "' ";

        }
        $sql .= " WHERE logcron_id=$logcron_id; ";
        $resultsql = $BonoInterno->execUpdate($transaction, $sql, 1);
        $transaction->commit();
        return $resultsql;
    }





    /**
     * RetarGiros actualiza los giros de un usuario de bono.
     *
     * @param float $valor El valor a redondear y actualizar.
     * @param mixed $transaction La transacción actual.
     * @param mixed $UsuarioBono El usuario del bono a actualizar.
     * @return mixed El resultado de la actualización de giros.
     */
    public function RetarGiros($valor, $transaction, $UsuarioBono)
    {
        $valor = round($valor, 0, PHP_ROUND_HALF_DOWN);

        $UsuarioBonoMySqlDAO = new UsuarioBonoMySqlDAO($transaction);

        $return = $UsuarioBonoMySqlDAO->updateGiros($UsuarioBono, $valor, "");

        return $return;

    }


    /**
     * ContadorBonosFreespin
     *
     * Esta función cuenta el número de bonos de tipo 'freespin' asociados a un usuario específico y un subproveedor.
     *
     * @param int $UsuarioId El ID del usuario para el cual se cuentan los bonos.
     * @param int $SubProveedorId El ID del subproveedor asociado a los bonos.
     * @return int El número de bonos de tipo 'freespin' encontrados.
     */
    public function ContadorBonosFreespin($UsuarioId, $SubProveedorId)
    {

        $sql =
            "SELECT count(*) count 
FROM usuario_bono
         INNER JOIN bono_interno ON (bono_interno.bono_id = usuario_bono.bono_id)
         INNER JOIN bono_detalle ON (bono_detalle.bono_id = bono_interno.bono_id)
         LEFT OUTER JOIN usuario ON (usuario.usuario_id = usuario_bono.usuario_id)
         LEFT OUTER JOIN bono_log ON (bono_log.id_externo = usuario_bono.usubono_id AND bono_log.estado = 'W')
WHERE 1 = 1
  AND usuario_bono.usuario_id = $UsuarioId
  AND bono_interno.tipo = '8'
  AND bono_detalle.tipo IN ('CODESUBPROVIDER')
  AND bono_detalle.valor = $SubProveedorId
  AND usuario_bono.estado = 'A'


         ";


        $UsuarioBonoMySqlDAO = new \Backend\mysql\UsuarioBonoMySqlDAO();
        $transaccion = $UsuarioBonoMySqlDAO->getTransaction();

        $Count = $this->execQuery($transaccion, $sql);

        return $Count;
    }

    /**
     * Obtiene los datos de los bonos de freespin para un usuario específico.
     *
     * @param int $UsuarioId El ID del usuario para el cual se obtendrán los datos de los bonos.
     * @return array Un array con los datos de los bonos de freespin del usuario.
     */
    public function DataBonosFreespin($UsuarioId, $Moneda, $GameId)
    {

        $sql =
            "
SELECT usuario_bono.*, bono_detalle.*
FROM usuario_bono
         INNER JOIN bono_interno ON (bono_interno.bono_id = usuario_bono.bono_id)
         INNER JOIN bono_detalle ON (bono_detalle.bono_id = bono_interno.bono_id  AND bono_detalle.tipo='MAXPAGO'  AND bono_detalle.moneda='{$Moneda}')
         INNER JOIN bono_detalle bdetalle2 ON (bdetalle2.bono_id = bono_interno.bono_id AND bdetalle2.tipo='CONDGAME{$GameId}')
         INNER JOIN (select bd.bono_id, bd.tipo, bd.valor
                     from bono_detalle bd
                     where 1 = 1
                       and (tipo = 'CODESUBPROVIDER')) bd on bd.bono_id = usuario_bono.bono_id
         join subproveedor sub on sub.subproveedor_id = bd.valor
WHERE 1 = 1
  AND bono_interno.tipo = '8'
  AND bono_interno.estado = 'A'
  AND usuario_bono.estado = 'A'
  AND sub.abreviado = 'MASCOT'
  AND usuario_id = {$UsuarioId}
ORDER BY usubono_id ASC
LIMIT 1
;
";
        if ($_ENV['debug']) {
            print_r($sql);
        }


        $BonoInterno = new BonoInterno();

        $UsuarioBonoMySqlDAO = new \Backend\mysql\UsuarioBonoMySqlDAO();
        $transaccion = $UsuarioBonoMySqlDAO->getTransaction();

        $Bonos = $BonoInterno->execQuery($transaccion, $sql);

        return $Bonos;
    }

    /**
     * Ejecutar una consulta sql
     *
     *
     *
     * @param Objeto $transaccion transacción
     * @param String $sql consulta sql
     *
     * @return Array $ resultado de la ejecución
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function execQuery($transaccion, $sql)
    {

        $BonoInternoMySqlDAO = new BonoInternoMySqlDAO($transaccion);
        $return = $BonoInternoMySqlDAO->querySQL($sql);
        $return = json_decode(json_encode($return), FALSE);

        return $return;

    }


    /**
     * Ejecutar una consulta sql
     *
     *
     *
     * @param Objeto $transaccion transacción
     * @param String $sql consulta sql
     *
     * @return Array $ resultado de la ejecución
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function execUpdate($transaccion, $sql, $insert = "")
    {

        $BonoInternoMySqlDAO = new BonoInternoMySqlDAO($transaccion);
        $return = $BonoInternoMySqlDAO->queryUpdate($sql, $insert);

        return $return;

    }


}

?>
