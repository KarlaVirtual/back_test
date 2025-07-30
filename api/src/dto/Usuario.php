<?php

namespace Backend\dto;

use Backend\mysql\PromocionalLogMySqlDAO;
use Backend\mysql\RegistroMySqlDAO;
use Backend\mysql\UsuarioConfiguracionMySqlDAO;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\mysql\UsuarioMandanteMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\sql\Transaction;
use Exception;

/**
 * Clase 'Usuario'
 *
 * Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
 * para la tabla 'Usuario'
 *
 * Ejemplo de uso:
 * $Usuario = new Usuario();
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class Usuario
{

    /**
     * Representación de la columna 'usuarioId' de la tabla 'Usuario'
     *
     * @var string
     */
    public $usuarioId;

    /**
     * Representación de la columna 'login' de la tabla 'Usuario'
     *
     * @var string
     */
    public $login;

    /**
     * Representación de la columna 'clave' de la tabla 'Usuario'
     *
     * @var string
     */
    public $clave;

    /**
     * Representación de la columna 'nombre' de la tabla 'Usuario'
     *
     * @var string
     */
    public $nombre;

    /**
     * Representación de la columna 'estado' de la tabla 'Usuario'
     *
     * @var string
     */
    public $estado;

    /**
     * Representación de la columna 'fechaUlt' de la tabla 'Usuario'
     *
     * @var string
     */
    public $fechaUlt;

    /**
     * Representación de la columna 'claveTv' de la tabla 'Usuario'
     *
     * @var string
     */
    public $claveTv;

    /**
     * Representación de la columna 'estadoAnt' de la tabla 'Usuario'
     *
     * @var string
     */
    public $estadoAnt;

    /**
     * Representación de la columna 'intentos' de la tabla 'Usuario'
     *
     * @var string
     */
    public $intentos;

    /**
     * Representación de la columna 'estadoEsp' de la tabla 'Usuario'
     *
     * @var string
     */
    public $estadoEsp;

    /**
     * Representación de la columna 'observ' de la tabla 'Usuario'
     *
     * @var string
     */
    public $observ;

    /**
     * Representación de la columna 'dirIp' de la tabla 'Usuario'
     *
     * @var string
     */
    public $dirIp;

    /**
     * Representación de la columna 'eliminado' de la tabla 'Usuario'
     *
     * @var string
     */
    public $eliminado;

    /**
     * Representación de la columna 'mandante' de la tabla 'Usuario'
     *
     * @var string
     */
    public $mandante;

    /**
     * Representación de la columna 'usucreaId' de la tabla 'Usuario'
     *
     * @var string
     */
    public $usucreaId;

    /**
     * Representación de la columna 'usumodifId' de la tabla 'Usuario'
     *
     * @var string
     */
    public $usumodifId;

    /**
     * Representación de la columna 'usumodifId' de la tabla 'Usuario'
     *
     * @var string
     */
    public $fechaModif;


    /**
     * Representación de la columna 'claveCasino' de la tabla 'Usuario'
     *
     * @var string
     */
    public $claveCasino;

    /**
     * Representación de la columna 'tokenItainment' de la tabla 'Usuario'
     *
     * @var string
     */
    public $tokenItainment;

    /**
     * Representación de la columna 'fechaClave' de la tabla 'Usuario'
     *
     * @var string
     */
    public $fechaClave;

    /**
     * Representación de la columna 'retirado' de la tabla 'Usuario'
     *
     * @var string
     */
    public $retirado;

    /**
     * Representación de la columna 'fechaRetiro' de la tabla 'Usuario'
     *
     * @var string
     */
    public $fechaRetiro;

    /**
     * Representación de la columna 'horaRetiro' de la tabla 'Usuario'
     *
     * @var string
     */
    public $horaRetiro;

    /**
     * Representación de la columna 'usuretiroId' de la tabla 'Usuario'
     *
     * @var string
     */
    public $usuretiroId;

    /**
     * Representación de la columna 'bloqueoVentas' de la tabla 'Usuario'
     *
     * @var string
     */
    public $bloqueoVentas;

    /**
     * Representación de la columna 'infoEquipo' de la tabla 'Usuario'
     *
     * @var string
     */
    public $infoEquipo;

    /**
     * Representación de la columna 'estadoJugador' de la tabla 'Usuario'
     *
     * @var string
     */
    public $estadoJugador;

    /**
     * Representación de la columna 'tokenCasino' de la tabla 'Usuario'
     *
     * @var string
     */
    public $tokenCasino;

    /**
     * Representación de la columna 'sponsorId' de la tabla 'Usuario'
     *
     * @var string
     */
    public $sponsorId;

    /**
     * Representación de la columna 'verifCorreo' de la tabla 'Usuario'
     *
     * @var string
     */
    public $verifCorreo;

    /**
     * Representación de la columna 'paisId' de la tabla 'Usuario'
     *
     * @var string
     */
    public $paisId;

    /**
     * Representación de la columna 'moneda' de la tabla 'Usuario'
     *
     * @var string
     */
    public $moneda;

    /**
     * Representación de la columna 'idioma' de la tabla 'Usuario'
     *
     * @var string
     */
    public $idioma;

    /**
     * Representación de la columna 'permiteActivareg' de la tabla 'Usuario'
     *
     * @var string
     */
    public $permiteActivareg;

    /**
     * Representación de la columna 'test' de la tabla 'Usuario'
     *
     * @var string
     */
    public $test;

    /**
     * Representación de la columna 'puntoventaId' de la tabla 'Usuario'
     *
     * @var string
     */
    public $puntoventaId;

    /**
     * Representación de la columna 'tiempoLimitedeposito' de la tabla 'Usuario'
     *
     * @var string
     */
    public $tiempoLimitedeposito;

    /**
     * Representación de la columna 'tiempoAutoexclusion' de la tabla 'Usuario'
     *
     * @var string
     */
    public $tiempoAutoexclusion;

    /**
     * Representación de la columna 'cambiosAprobacion' de la tabla 'Usuario'
     *
     * @var string
     */
    public $cambiosAprobacion;

    /**
     * Representación de la columna 'timezone' de la tabla 'Usuario'
     *
     * @var string
     */
    public $timezone;

    /**
     * Representación de la columna 'success' de la tabla 'Usuario'
     *
     * @var string
     */
    public $success;

    /**
     * Representación de la columna 'celular' de la tabla 'Usuario'
     *
     * @var string
     */
    public $celular;

    /**
     * Representación de la columna 'origen' de la tabla 'Usuario'
     *
     * @var string
     */
    public $origen;

    /**
     * Representación de la columna 'fechaActualizacion' de la tabla 'Usuario'
     *
     * @var string
     */
    public $fechaActualizacion;

    /**
     * Representación de la columna 'fechaCrea' de la tabla 'Usuario'
     *
     * @var string
     */
    public $fechaCrea;

    /**
     * Representación de la columna 'documentoValidado' de la tabla 'Usuario'
     *
     * @var string
     */
    public $documentoValidado;

    /**
     * Representación de la columna 'fechaDocvalido' de la tabla 'Usuario'
     *
     * @var string
     */
    public $fechaDocvalido;

    /**
     * Representación de la columna 'usuDocvalido' de la tabla 'Usuario'
     *
     * @var string
     */
    public $usuDocvalido;

    /**
     * Representación de la columna 'estadoValida' de la tabla 'Usuario'
     *
     * @var string
     */
    public $estadoValida;

    /**
     * Representación de la columna 'usuvalidaId' de la tabla 'Usuario'
     *
     * @var string
     */
    public $usuvalidaId;

    /**
     * Representación de la columna 'fechaValida' de la tabla 'Usuario'
     *
     * @var string
     */
    public $fechaValida;

    /**
     * Representación de la columna 'contingencia' de la tabla 'Usuario'
     *
     * @var string
     */
    public $contingencia;

    /**
     * Representación de la columna 'contingenciaDeportes' de la tabla 'Usuario'
     *
     * @var string
     */
    public $contingenciaDeportes;

    /**
     * Representación de la columna 'contingenciaCasino' de la tabla 'Usuario'
     *
     * @var string
     */
    public $contingenciaCasino;

    /**
     * Representación de la columna 'contingenciaCasvivo' de la tabla 'Usuario'
     *
     * @var string
     */
    public $contingenciaCasvivo;

    /**
     * Representación de la columna 'contingenciaVirtuales' de la tabla 'Usuario'
     *
     * @var string
     */
    public $contingenciaVirtuales;

    /**
     * Representación de la columna 'contingenciaPoker' de la tabla 'Usuario'
     *
     * @var string
     */
    public $contingenciaPoker;

    public $contingenciaRetiro;

    public $contingenciaDeposito;

    /**
     * Representación de la columna 'restriccionIp' de la tabla 'Usuario'
     *
     * @var string
     */
    public $restriccionIp;

    /**
     * Representación de la columna 'ubicacionLongitud' de la tabla 'Usuario'
     *
     * @var string
     */
    public $ubicacionLongitud;

    /**
     * Representación de la columna 'ubicacionLatitud' de la tabla 'Usuario'
     *
     * @var string
     */
    public $ubicacionLatitud;

    /**
     * Representación de la columna 'usuarioIp' de la tabla 'Usuario'
     *
     * @var string
     */
    public $usuarioIp;

    /**
     * Representación de la columna 'tokenGoogle' de la tabla 'Usuario'
     *
     * @var string
     */
    public $tokenGoogle;

    /**
     * Representación de la columna 'tokenLocal' de la tabla 'Usuario'
     *
     * @var string
     */
    public $tokenLocal;

    /**
     * Representación de la columna 'saltGoogle' de la tabla 'Usuario'
     *
     * @var string
     */
    public $saltGoogle;

    /**
     * Representación de la columna 'monedaReporte' de la tabla 'Usuario'
     *
     * @var string
     */
    public $monedaReporte;

    /**
     * Representación de la columna 'verifcedulaAnt' de la tabla 'Usuario'
     *
     * @var string
     */
    public $verifcedulaAnt;

    /**
     * Representación de la columna 'verifcedulaPost' de la tabla 'Usuario'
     *
     * @var string
     */
    public $verifcedulaPost;

    /**
     * Representación de la columna 'creditosAfiliacion' de la tabla 'Usuario'
     *
     * @var string
     */
    public $creditosAfiliacion;

    /**
     * Representación de la columna 'fechaCierrecaja' de la tabla 'Usuario'
     *
     * @var string
     */
    public $fechaCierrecaja;

    /**
     * Representación de la columna 'fechaPrimerdeposito' de la tabla 'Usuario'
     *
     * @var string
     */
    public $fechaPrimerdeposito;

    /**
     * Representación de la columna 'montoPrimerdeposito' de la tabla 'Usuario'
     *
     * @var string
     */
    public $montoPrimerdeposito;

    /**
     * Representación de la columna 'skype' de la tabla 'Usuario'
     *
     * @var string
     */
    public $skype;

    /**
     * Representación de la columna 'plataforma' de la tabla 'Usuario'
     *
     * @var string
     */
    public $plataforma;

    /**
     * Representación de la columna 'maximaComision' de la tabla 'Usuario'
     *
     * @var string
     */
    public $maximaComision;

    /**
     * Representación de la columna 'tiempoComision' de la tabla 'Usuario'
     *
     * @var string
     */
    public $tiempoComision;

    /**
     * Representación de la columna 'arrastraNegativo' de la tabla 'Usuario'
     *
     * @var string
     */
    public $arrastraNegativo;

    /**
     * Representación de la columna 'tokenQuisk' de la tabla 'Usuario'
     *
     * @var string
     */
    public $tokenQuisk;

    /**
     * Representación de la columna 'estadoImport' de la tabla 'Usuario'
     *
     * @var string
     */
    public $estadoImport;

    /**
     * Representación de la columna 'verifCelular' de la tabla 'Usuario'
     *
     * @var string
     */
    public $verifCelular;

    /**
     * Representación de la columna 'fechaVerifCelular' de la tabla 'Usuario'
     *
     * @var string
     */
    public $fechaVerifCelular;

    /**
     * Representación de la columna 'billeteraId' de la tabla 'Usuario'
     *
     * @var string
     */
    public $billeteraId;

    /**
     * Representación de la columna 'puntosLealtad' de la tabla 'Usuario'
     *
     * @var string
     */
    public $puntosLealtad;

    /**
     * Representación de la columna 'nivelLealtad' de la tabla 'Usuario'
     *
     * @var string
     */
    public $nivelLealtad;

    /**
     * Representación de la columna 'verifDomicilio' de la tabla 'Usuario'
     *
     * @var string
     */
    public $verifDomicilio;

    /**
     * Representación de la columna 'puntosAexpirar' de la tabla 'Usuario'
     *
     * @var string
     */
    public $puntosAexpirar;

    /**
     * Representación de la columna 'puntosExpirados' de la tabla 'Usuario'
     *
     * @var string
     */
    public $puntosExpirados;

    /**
     * Representación de la columna 'puntosRedimidos' de la tabla 'Usuario'
     *
     * @var string
     */
    public  $puntosRedimidos;

    /**
     * Representación de la columna 'pagoComisiones' de la tabla 'Usuario'
     *
     * @var string
     */
    public  $pagoComisiones;

    /**
     * Representación de la columna 'equipoId' de la tabla 'Usuario'
     *
     * @var string
     */
    public  $equipoId;

    /**
     * Representación de la columna 'verificado' de la tabla 'Usuario'
     *
     * @var string
     */
    public $verificado;

    /**
     * Representación de la columna 'fechaVerificador' de la tabla 'Usuario'
     *
     * @var string
     */
    public $fechaVerificado;

    /**
     * Representación de la columna 'cip' de la tabla 'Usuario'
     *
     * @var string
     */
    public $CIP;

    /**
     * Representación de la columna 'accountIdJumio' de la tabla 'Usuario'
     *
     * @var string
     */
    public $accountIdJumio;

    /**
     * Representación de la columna 'permite_enviarpublicidad' de la tabla 'Usuario'
     *
     * @var string
     */
    public $permite_enviarpublicidad;





    /**
     * Constructor de clase
     *
     *
     * @param String $usuarioId usuarioId
     * @param String $email email
     *
     * @return no
     * @throws Exception si el usuario no existe
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function __construct($usuarioId = "", $email = "", $plataforma = 0, $mandante = 0, $paisId = "")
    {
        if ($usuarioId != "") {

            $this->usuarioId = $usuarioId;

            $UsuarioMySqlDAO = new UsuarioMySqlDAO();
            $Usuario = $UsuarioMySqlDAO->load($usuarioId);

            $this->success = false;

            if ($Usuario != null && $Usuario != "") {

                $this->login = $Usuario->login;
                $this->nombre = $Usuario->nombre;
                $this->estado = $Usuario->estado;
                $this->fechaUlt = $Usuario->fechaUlt;
                $this->claveTv = $Usuario->claveTv;
                $this->estadoAnt = $Usuario->estadoAnt;
                $this->intentos = $Usuario->intentos;
                $this->estadoEsp = $Usuario->estadoEsp;
                $this->observ = $Usuario->observ;
                $this->dirIp = $Usuario->dirIp;
                $this->eliminado = $Usuario->eliminado;
                $this->mandante = $Usuario->mandante;
                $this->usucreaId = $Usuario->usucreaId;
                $this->usumodifId = $Usuario->usumodifId;
                $this->fechaModif = $Usuario->fechaModif;
                $this->claveCasino = $Usuario->claveCasino;
                $this->tokenItainment = $Usuario->tokenItainment;
                $this->fechaClave = $Usuario->fechaClave;
                $this->retirado = $Usuario->retirado;
                $this->fechaRetiro = $Usuario->fechaRetiro;
                $this->horaRetiro = $Usuario->horaRetiro;
                $this->usuretiroId = $Usuario->usuretiroId;
                $this->bloqueoVentas = $Usuario->bloqueoVentas;
                $this->infoEquipo = $Usuario->infoEquipo;
                $this->estadoJugador = $Usuario->estadoJugador;
                $this->tokenCasino = $Usuario->tokenCasino;
                $this->sponsorId = $Usuario->sponsorId;
                $this->verifCorreo = $Usuario->verifCorreo;
                $this->paisId = $Usuario->paisId;
                $this->moneda = $Usuario->moneda;
                $this->idioma = $Usuario->idioma;
                $this->permiteActivareg = $Usuario->permiteActivareg;
                $this->test = $Usuario->test;
                $this->puntoventaId = $Usuario->puntoventaId;
                $this->tiempoLimitedeposito = $Usuario->tiempoLimitedeposito;
                $this->tiempoAutoexclusion = $Usuario->tiempoAutoexclusion;
                $this->cambiosAprobacion = $Usuario->cambiosAprobacion;
                $this->timezone = $Usuario->timezone;
                $this->celular = $Usuario->celular;
                $this->origen = $Usuario->origen;
                $this->fechaActualizacion = $Usuario->fechaActualizacion;
                $this->fechaCrea = $Usuario->fechaCrea;
                $this->documentoValidado = $Usuario->documentoValidado;
                $this->fechaDocvalido = $Usuario->fechaDocvalido;
                $this->usuDocvalido = $Usuario->usuDocvalido;
                $this->estadoValida = $Usuario->estadoValida;
                $this->usuvalidaId = $Usuario->usuvalidaId;
                $this->fechaValida = $Usuario->fechaValida;
                $this->contingencia = $Usuario->contingencia;
                $this->contingenciaDeportes = $Usuario->contingenciaDeportes;
                $this->contingenciaCasino = $Usuario->contingenciaCasino;
                $this->contingenciaCasvivo = $Usuario->contingenciaCasvivo;
                $this->contingenciaVirtuales = $Usuario->contingenciaVirtuales;
                $this->contingenciaPoker = $Usuario->contingenciaPoker;
                $this->contingenciaRetiro = $Usuario->contingenciaRetiro;
                $this->contingenciaDeposito = $Usuario->contingenciaDeposito;
                $this->restriccionIp = $Usuario->restriccionIp;
                $this->ubicacionLatitud = $Usuario->ubicacionLatitud;
                $this->ubicacionLongitud = $Usuario->ubicacionLongitud;
                $this->usuarioIp = $Usuario->usuarioIp;
                $this->tokenGoogle = $Usuario->tokenGoogle;
                $this->tokenLocal = $Usuario->tokenLocal;
                $this->saltGoogle = $Usuario->saltGoogle;
                $this->monedaReporte = $Usuario->monedaReporte;
                $this->verifcedulaAnt = $Usuario->verifcedulaAnt;
                $this->verifcedulaPost = $Usuario->verifcedulaPost;
                $this->verifFotoUsuario = $Usuario->verifFotoUsuario;
                $this->creditosAfiliacion = $Usuario->creditosAfiliacion;
                $this->fechaCierrecaja = $Usuario->fechaCierrecaja;
                $this->fechaPrimerdeposito = $Usuario->fechaPrimerdeposito;
                $this->montoPrimerdeposito = $Usuario->montoPrimerdeposito;
                $this->skype = $Usuario->skype;
                $this->plataforma = $Usuario->plataforma;
                $this->maximaComision = $Usuario->maximaComision;
                $this->tiempoComision = $Usuario->tiempoComision;
                $this->arrastraNegativo = $Usuario->arrastraNegativo;
                $this->tokenQuisk = $Usuario->tokenQuisk;
                $this->estadoImport = $Usuario->estadoImport;
                $this->verifCelular = $Usuario->verifCelular;
                $this->fechaVerifCelular = $Usuario->fechaVerifCelular;
                $this->billeteraId = $Usuario->billeteraId;
                $this->puntosLealtad = $Usuario->puntosLealtad;
                $this->nivelLealtad = $Usuario->nivelLealtad;
                $this->verifDomicilio = $Usuario->verifDomicilio;
                $this->verificado = $Usuario->verificado;
                $this->fechaVerificador = $Usuario->fechaVerificado;
                $this->fechaVerificado = $Usuario->fechaVerificado;
                $this->permite_enviarpublicidad = $Usuario->permite_enviarpublicidad;


                if ($this->documentoValidado == "") {
                    $this->documentoValidado = "I";
                }

                if ($this->fechaDocvalido == "" || $this->fechaDocvalido == "0000-00-00 00:00:00") {
                    $this->fechaDocvalido = "1970-01-01 00:00:00";
                }

                if ($this->usuDocvalido == "") {
                    $this->usuDocvalido = "0";
                }


                if ($this->creditosAfiliacion == "") {
                    $this->creditosAfiliacion = "0";
                }

                $this->puntosAexpirar = $Usuario->puntosAexpirar;
                $this->puntosExpirados = $Usuario->puntosExpirados;
                $this->puntosRedimidos = $Usuario->puntosRedimidos;
                $this->pagoComisiones = $Usuario->pagoComisiones;
                $this->equipoId = $Usuario->equipoId;

                if ($this->accountIdJumio == "") {
                    $this->accountIdJumio = "";
                }
                $this->accountIdJumio = $Usuario->accountIdJumio;

                $this->success = true;
            } else {
                throw new Exception("No existe " . get_class($this), "24");
            }
        } elseif ($email != "") {

            $UsuarioMySqlDAO = new UsuarioMySqlDAO();
            $Usuario = $UsuarioMySqlDAO->queryByLogin($email, $plataforma, $mandante, $paisId);
            $Usuario = $Usuario[0];

            $this->success = false;

            if ($Usuario != null && $Usuario != "") {

                $this->login = $Usuario->login;
                $this->nombre = $Usuario->nombre;
                $this->estado = $Usuario->estado;
                $this->fechaUlt = $Usuario->fechaUlt;
                $this->claveTv = $Usuario->claveTv;
                $this->estadoAnt = $Usuario->estadoAnt;
                $this->intentos = $Usuario->intentos;
                $this->estadoEsp = $Usuario->estadoEsp;
                $this->observ = $Usuario->observ;
                $this->dirIp = $Usuario->dirIp;
                $this->eliminado = $Usuario->eliminado;
                $this->mandante = $Usuario->mandante;
                $this->usucreaId = $Usuario->usucreaId;
                $this->usumodifId = $Usuario->usumodifId;
                $this->fechaModif = $Usuario->fechaModif;
                $this->claveCasino = $Usuario->claveCasino;
                $this->tokenItainment = $Usuario->tokenItainment;
                $this->fechaClave = $Usuario->fechaClave;
                $this->retirado = $Usuario->retirado;
                $this->fechaRetiro = $Usuario->fechaRetiro;
                $this->horaRetiro = $Usuario->horaRetiro;
                $this->usuretiroId = $Usuario->usuretiroId;
                $this->bloqueoVentas = $Usuario->bloqueoVentas;
                $this->infoEquipo = $Usuario->infoEquipo;
                $this->estadoJugador = $Usuario->estadoJugador;
                $this->tokenCasino = $Usuario->tokenCasino;
                $this->sponsorId = $Usuario->sponsorId;
                $this->verifCorreo = $Usuario->verifCorreo;
                $this->paisId = $Usuario->paisId;
                $this->moneda = $Usuario->moneda;
                $this->idioma = $Usuario->idioma;
                $this->permiteActivareg = $Usuario->permiteActivareg;
                $this->test = $Usuario->test;
                $this->puntoventaId = $Usuario->puntoventaId;
                $this->tiempoLimitedeposito = $Usuario->tiempoLimitedeposito;
                $this->tiempoAutoexclusion = $Usuario->tiempoAutoexclusion;
                $this->cambiosAprobacion = $Usuario->cambiosAprobacion;
                $this->timezone = $Usuario->timezone;
                $this->celular = $Usuario->celular;
                $this->usuarioId = $Usuario->usuarioId;
                $this->origen = $Usuario->origen;
                $this->fechaActualizacion = $Usuario->fechaActualizacion;
                $this->fechaCrea = $Usuario->fechaCrea;
                $this->documentoValidado = $Usuario->documentoValidado;
                $this->fechaDocvalido = $Usuario->fechaDocvalido;
                $this->usuDocvalido = $Usuario->usuDocvalido;
                $this->estadoValida = $Usuario->estadoValida;
                $this->usuvalidaId = $Usuario->usuvalidaId;
                $this->fechaValida = $Usuario->fechaValida;
                $this->contingencia = $Usuario->contingencia;
                $this->contingenciaDeportes = $Usuario->contingenciaDeportes;
                $this->contingenciaCasino = $Usuario->contingenciaCasino;
                $this->contingenciaCasvivo = $Usuario->contingenciaCasvivo;
                $this->contingenciaVirtuales = $Usuario->contingenciaVirtuales;
                $this->contingenciaPoker = $Usuario->contingenciaPoker;
                $this->contingenciaRetiro = $Usuario->contingenciaRetiro;
                $this->contingenciaDeposito = $Usuario->contingenciaDeposito;
                $this->restriccionIp = $Usuario->restriccionIp;
                $this->ubicacionLatitud = $Usuario->ubicacionLatitud;
                $this->ubicacionLongitud = $Usuario->ubicacionLongitud;
                $this->usuarioIp = $Usuario->usuarioIp;
                $this->tokenGoogle = $Usuario->tokenGoogle;
                $this->tokenLocal = $Usuario->tokenLocal;
                $this->saltGoogle = $Usuario->saltGoogle;
                $this->monedaReporte = $Usuario->monedaReporte;
                $this->verifcedulaAnt = $Usuario->verifcedulaAnt;
                $this->verifcedulaPost = $Usuario->verifcedulaPost;
                $this->verifFotoUsuario = $Usuario->verifFotoUsuario;
                $this->creditosAfiliacion = $Usuario->creditosAfiliacion;
                $this->fechaCierrecaja = $Usuario->fechaCierrecaja;
                $this->fechaPrimerdeposito = $Usuario->fechaPrimerdeposito;
                $this->montoPrimerdeposito = $Usuario->montoPrimerdeposito;
                $this->skype = $Usuario->skype;
                $this->plataforma = $Usuario->plataforma;
                $this->maximaComision = $Usuario->maximaComision;
                $this->tiempoComision = $Usuario->tiempoComision;
                $this->arrastraNegativo = $Usuario->arrastraNegativo;
                $this->tokenQuisk = $Usuario->tokenQuisk;
                $this->estadoImport = $Usuario->estadoImport;
                $this->verifCelular = $Usuario->verifCelular;
                $this->fechaVerifCelular = $Usuario->fechaVerifCelular;
                $this->billeteraId = $Usuario->billeteraId;
                $this->puntosLealtad = $Usuario->puntosLealtad;
                $this->nivelLealtad = $Usuario->nivelLealtad;
                $this->verifDomicilio = $Usuario->verifDomicilio;

                $this->verificado = $Usuario->verificado;
                $this->fechaVerificador = $Usuario->fechaVerificado;
                $this->fechaVerificado = $Usuario->fechaVerificado;
                $this->permite_enviarpublicidad = $Usuario->permite_enviarpublicidad;

                if ($this->documentoValidado == "") {
                    $this->documentoValidado = "I";
                }

                if ($this->fechaDocvalido == "") {
                    $this->fechaDocvalido = "1970-01-01 00:00:00";
                }

                if ($this->usuDocvalido == "") {
                    $this->usuDocvalido = "0";
                }

                if ($this->creditosAfiliacion == "") {
                    $this->creditosAfiliacion = "0";
                }

                $this->puntosAexpirar = $Usuario->puntosAexpirar;
                $this->puntosExpirados = $Usuario->puntosExpirados;
                $this->puntosRedimidos = $Usuario->puntosRedimidos;
                $this->pagoComisiones = $Usuario->pagoComisiones;
                $this->equipoId = $Usuario->equipoId;
                if ($this->accountIdJumio == "") {
                    $this->accountIdJumio = "";
                }
                $this->accountIdJumio = $Usuario->accountIdJumio;
                $this->success = true;
            } else {
                throw new Exception("No existe " . get_class($this), "24");
            }
        }
    }


    /**
     * Obtener el balance financiero de un usuario
     *
     * @return float $ balance
     *
     */
    public function getBalance()
    {


        $Registro = new Registro("", $this->usuarioId);
        if ($Registro->success) {

            //return (($Registro->getCreditosBase() + $Registro->getCreditos()));
            return round((floatval(($Registro->getCreditosBase() * 100)) + floatval(($Registro->getCreditos() * 100))) / 100, 2);
            //return round(($Registro->getCreditosBase() + $Registro->getCreditos()),2,PHP_ROUND_HALF_DOWN);
        }
    }


    /**
     * Obtener los puntos de lealtad del usuario
     *
     * @return string
     */
    public function getPuntosLealtad()
    {
        return $this->puntosLealtad;
    }

    /**
     * Establecer los puntos de lealtad del usuario
     *
     * @param string $puntosLealtad
     */
    public function setPuntosLealtad($puntosLealtad)
    {
        $this->puntosLealtad = $puntosLealtad;
    }

    /**
     * Establecer los puntos a expirar del usuario
     *
     * @param string $puntosAexpirar
     */
    public function setPuntosAexpirar($puntosAexpirar)
    {
        $this->puntosAexpirar = $puntosAexpirar;
    }

    /**
     * Obtener los puntos a expirar del usuario
     *
     * @return string
     */
    public function getPuntosAexpirar()
    {
        return $this->puntosAexpirar;
    }

    /**
     * Establecer si se permite enviar publicidad al usuario
     *
     * @param string $value
     */
    public function setPermiteEnviarPublicidad($value)
    {
        $this->permite_enviarpublicidad = $value;
    }

    /**
     * Obtener si se permite enviar publicidad al usuario
     *
     * @return string
     */
    public function getPermiteEnviarPublicidad()
    {
        return $this->permite_enviarpublicidad;
    }

    /**
     * Establecer los puntos expirados del usuario
     *
     * @param string $puntosExpirados
     */
    public function setPuntosExpirados($puntosExpirados)
    {
        $this->puntosExpirados = $puntosExpirados;
    }

    /**
     * Obtener los puntos expirados del usuario
     *
     * @return string
     */
    public function getPuntosExpirados()
    {
        return $this->puntosExpirados;
    }

    /**
     * Establecer los puntos redimidos del usuario
     *
     * @param string $puntosRedimidos
     */
    public function setPuntosRedimidos($puntosRedimidos)
    {
        $this->puntosExpirados = $puntosRedimidos;
    }

    /**
     * Obtener los puntos redimidos del usuario
     *
     * @return string
     */
    public function getPuntosRedimidos()
    {
        return $this->puntosRedimidos;
    }

    /**
     * Obtener el ID de cuenta Jumio del usuario
     *
     * @return string
     */
    public function getAccountIdJumio()
    {
        return $this->accountIdJumio;
    }

    /**
     * Establecer el ID de cuenta Jumio del usuario
     *
     * @param string $accountIdJumio
     */
    public function setAccountIdJumio($accountIdJumio)
    {
        $this->accountIdJumio = $accountIdJumio;
    }

    /**
     * Obtener si el usuario está verificado
     *
     * @return string
     */
    public function getVerificado()
    {
        return $this->verificado;
    }

    /**
     * Establecer si el usuario está verificado
     *
     * @param string $verificado
     */
    public function setVerificado($verificado)
    {
        $this->verificado = $verificado;
    }

    /**
     * Obtener la fecha de verificación del usuario
     *
     * @return string
     */
    public function getFechaVerificado()
    {
        return $this->fechaVerificado;
    }

    /**
     * Establecer la fecha de verificación del usuario
     *
     * @param string $fechaVerificado
     */
    public function setFechaVerificado($fechaVerificado)
    {
        $this->fechaVerificado = $fechaVerificado;
    }

    /**
     * Obtener el ID del usuario
     *
     * @return string
     */
    public function getUsuarioId()
    {
        return $this->usuarioId;
    }

    /**
     * Establecer el ID del usuario
     *
     * @param string $usuarioId
     */
    public function setUsuarioId($usuarioId)
    {
        $this->usuarioId = $usuarioId;
    }

    /**
     * Obtener la verificación de cédula anterior del usuario
     *
     * @return string
     */
    public function getVerifcedulaAnt()
    {
        return $this->verifcedulaAnt;
    }

    /**
     * Establecer la verificación de cédula anterior del usuario
     *
     * @param string $verifcedulaAnt
     */
    public function setVerifcedulaAnt($verifcedulaAnt)
    {
        $this->verifcedulaAnt = $verifcedulaAnt;
    }

    /**
     * Obtener la verificación de cédula posterior del usuario
     *
     * @return string
     */
    public function getVerifcedulaPost()
    {
        return $this->verifcedulaPost;
    }

    /**
     * Establecer la verificación de cédula posterior del usuario
     *
     * @param string $verifcedulaPost
     */
    public function setVerifcedulaPost($verifcedulaPost)
    {
        $this->verifcedulaPost = $verifcedulaPost;
    }

    /**
     * Obtener la verificación de foto del usuario
     *
     * @return string
     */
    public function getVerifFotoUsuario()
    {
        return $this->verifFotoUsuario;
    }

    /**
     * Establecer la verificación de foto del usuario
     *
     * @param string $verifFotoUsuario
     */
    public function setVerifFotoUsuario($verifFotoUsuario)
    {
        $this->verifFotoUsuario = $verifFotoUsuario;
    }

    /**
     * Obtener el estado del usuario
     *
     * @return string
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Establecer el estado del usuario
     *
     * @param string $estado
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }


    /**
     * Consultar si la contraseña corresponde al uasuario en cuestión
     *
     *
     * @param String $clave contraseña
     *
     * @return no
     * @throws Exception si el usuario ingresado se encuentra inactivo
     * @throws Exception si el sitio se encuentra en mantenimiento
     * @throws Exception si el usuario ha sido bloqueado por exceso de intentos
     * @throws Exception si el usuario ingresado se encuentra inactivo
     * @throws Exception si se ha limitado el acceso a la plataforma
     * @throws Exception si se la clave ingresada es incorrecta
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function checkClave($clave)
    {
        //Obtenemos Usuario Perfil

        $UsuarioPerfil = new UsuarioPerfil($this->usuarioId);

        $Perfil = new Perfil($UsuarioPerfil->getPerfilId());

        if ($this->estadoEsp == "I") {

            //throw new Exception("El usuario ingresado se encuentra inactivo.  ", "20003");

        }

        if ($Perfil->getContingencia() == "S") {

            throw new Exception("En el momento nos encontramos en proceso de mantenimiento del sitio. ", "30004");
        }

        $ClasificadorLoginIncorrecto = new Clasificador("", "WRONGATTEMPTSLOGIN");
        $maximoIntentos = -1;

        try {
            $MandanteDetalle = new MandanteDetalle("", $this->mandante, $ClasificadorLoginIncorrecto->getClasificadorId(), $this->paisId, 'A');

            $maximoIntentos = $MandanteDetalle->getValor();
        } catch (Exception $e) {
            if ($e->getCode() == 34) {
            } else {
            }
        }


        if ($this->intentos >= $maximoIntentos && $maximoIntentos != -1) {

            throw new Exception("El usuario ha sido bloqueado por el sistema debido a que excedió el número de intentos permitidos con clave errónea.  ", "30001");
        }

        if ($this->estado == "I") {

            throw new Exception("El usuario ingresado se encuentra inactivo. ", "20003");
        }

        $UsuarioMySqlDAO = new UsuarioMySqlDAO();

        $UsuarioGeneral = $UsuarioMySqlDAO->queryForLogin($this, $clave);
        $UsuarioGeneral = $UsuarioGeneral[0];

        if ($UsuarioGeneral != "" && $UsuarioGeneral != null) {
            if ($UsuarioGeneral['pinagent'] == "S") {

                throw new Exception("Se ha limitado su acceso a la plataforma. Por favor verifique la URL a la que esta accediendo o contacte a su administrador.", "05");
            }

            //Devuelve mensaje satisfactorio

            $array['status'] = true;
            $array['message'] = 'Login Success';
            $array['auth_token'] = "ASDADSADSA";
            $array['user_id'] = $_SESSION["usuario"];

            $respuesta = json_decode(json_encode($array, true));

            return $respuesta;
        } else {
            throw new Exception("La clave ingresada es incorrecta.", "30007");
        }
    }

    /**
     * Chequear IP es de usuario
     *
     *
     * @param String $ip IP
     * @param String $perfil perfil opcional
     *
     * @return no
     * @throws Exception si el usuario ingresado se encuentra inactivo
     * @throws Exception si el sitio se encuentra en mantenimiento
     * @throws Exception si el usuario ha sido bloqueado por exceso de intentos
     * @throws Exception si el usuario ingresado se encuentra inactivo
     * @throws Exception si se ha limitado el acceso a la plataforma
     * @throws Exception si se la clave ingresada es incorrecta
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function checkIPUsuario($ip, $perfil = '', $mandante = "")
    {


        $UsuarioMySqlDAO = new UsuarioMySqlDAO();

        $Usuario = $UsuarioMySqlDAO->queryForIPAndPerfil($ip, $perfil, $mandante);
        $Usuario = $Usuario[0];

        return $Usuario;
    }


    /**
     * Obtener las estadistica de un usuario en concreto
     *
     *
     * @param String $clave contraseña
     *
     * @return no
     * @throws Exception si el usuario no existe
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function getEstadisticas()
    {

        $UsuarioMySqlDAO = new UsuarioMySqlDAO();
        $UsuarioEstadisticas = $UsuarioMySqlDAO->queryForEstadisticas($this->usuarioId, $this->mandante);
        $UsuarioEstadisticas = $UsuarioEstadisticas[0];

        if ($UsuarioEstadisticas != null && $UsuarioEstadisticas != "") {
            return $UsuarioEstadisticas;
        } else {
            throw new Exception("No existe " . get_class($this), "01");
        }
    }

    /**
     * Obtener los detalles de administración
     *
     *
     * @param String $clave contraseña
     *
     * @return no
     * @throws Exception si el UsuarioAdminInfo no existe
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function getAdminDetails()
    {

        $UsuarioMySqlDAO = new UsuarioMySqlDAO();
        $UsuarioAdminInfo = $UsuarioMySqlDAO->queryForAdminDetails($this->usuarioId, $this->mandante);
        $UsuarioAdminInfo = $UsuarioAdminInfo[0];
        if ($UsuarioAdminInfo != null && $UsuarioAdminInfo != "") {
            return $UsuarioAdminInfo;
        } else {
            throw new Exception("No existe " . get_class($this), "01");
        }
    }

    /**
     * Obtener el mensaje WS
     *
     *
     * @param String $sid sid
     *
     * @return no
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function getWSMessage($sid)
    {

        $UsuarioMandante = new UsuarioMandante("", $this->usuarioId, $this->mandante);

        $usuario_id = $UsuarioMandante->getUsumandanteId();
        $usuario_idPlatform = $UsuarioMandante->usuarioMandante;

        $fecha_ultima = "";
        $ip_ultima = "";

        /*
        $jsonMjs = '{"rules" : [{"field" : "usuario_mensaje.usuto_id", "data": "' . $UsuarioMandante->usumandanteId . '","op":"eq"},{"field" : "usuario_mensaje.is_read", "data": "0","op":"eq"},{"field" : "usuario_mensaje.tipo", "data": "MENSAJE","op":"eq"}] ,"groupOp" : "AND"}';
        $usuarioMensajes = (new UsuarioMensaje())->getUsuarioMensajesCustom(" usuario_mensaje.* ", "usuario_mensaje.usumensaje_id", "asc", 0, 10, $jsonMjs, true);
        $usuarioMensajes = json_decode($usuarioMensajes);
        $mensajes_no_leidos = $usuarioMensajes->count[0]->{".count"};


        $profile_id = array();
        $profile_id['id'] = $usuario_id;
        $profile_id['unique_id'] = $usuario_id;
        $profile_id['username'] = $usuario_id;
        $profile_id['name'] = '';
        $profile_id['first_name'] = '';
        $profile_id['last_name'] = '';
        $profile_id['gender'] = "";
        $profile_id['email'] = "";
        $profile_id['phone'] = "";
        $profile_id['reg_info_incomplete'] = false;
        $profile_id['address'] = "";

        $profile_id["reg_date"] = "";
        $profile_id["birth_date"] = "";
        $profile_id["doc_number"] = "";
        $profile_id["casino_promo"] = null;
        $profile_id["currency_name"] = $this->moneda;

        $profile_id["currency_id"] = $this->moneda;
        $profile_id["balance"] = $this->getBalance();
        $profile_id["casino_balance"] = $this->getBalance();
        $profile_id["exclude_date"] = null;
        $profile_id["bonus_id"] = -1;
        $profile_id["games"] = 0;
        $profile_id["super_bet"] = -1;
        $profile_id["country_code"] = $this->paisId;
        $profile_id["doc_issued_by"] = null;
        $profile_id["doc_issue_date"] = null;
        $profile_id["doc_issue_code"] = null;
        $profile_id["province"] = null;
        $profile_id["iban"] = null;
        $profile_id["active_step"] = null;
        $profile_id["active_step_state"] = null;
        $profile_id["subscribed_to_news"] = false;
        $profile_id["bonus_balance"] = 0.0;
        $profile_id["frozen_balance"] = 0.0;
        $profile_id["bonus_win_balance"] = 0.0;
        $profile_id["city"] = "Manizales";
        $profile_id["has_free_bets"] = false;
        $profile_id["loyalty_point"] = 0.0;
        $profile_id["loyalty_earned_points"] = 0.0;
        $profile_id["loyalty_exchanged_points"] = 0.0;
        $profile_id["loyalty_level_id"] = null;
        $profile_id["affiliate_id"] = null;
        $profile_id["is_verified"] = false;
        $profile_id["incorrect_fields"] = null;
        $profile_id["loyalty_point_usage_period"] = 0;
        $profile_id["loyalty_min_exchange_point"] = 0;
        $profile_id["loyalty_max_exchange_point"] = 0;
        $profile_id["active_time_in_casino"] = null;
        $profile_id["last_read_message"] = null;
        $profile_id["unread_count"] = $mensajes_no_leidos;
        $profile_id["last_login_date"] = 1506281782;
        $profile_id["swift_code"] = null;
        $profile_id["bonus_money"] = 0.0;
        $profile_id["loyalty_last_earned_points"] = 0.0;

*/


        $saldo = $UsuarioMandante->getSaldo();
        $moneda = $UsuarioMandante->getMoneda();
        $paisId = $UsuarioMandante->getPaisId();
        $usuario_id = $UsuarioMandante->getUsumandanteId();
        $usuario_idPlatform = $UsuarioMandante->getUsuarioMandante();

        $jsonMjs = '{"rules" : [{"field" : "usuario_mensaje.usuto_id", "data": "' . $UsuarioMandante->usumandanteId . '","op":"eq"},{"field" : "usuario_mensaje.is_read", "data": "0","op":"eq"},{"field" : "usuario_mensaje.tipo", "data": "MENSAJE","op":"eq"}] ,"groupOp" : "AND"}';
        $usuarioMensajes = (new UsuarioMensaje())->getUsuarioMensajesCustom(" usuario_mensaje.* ", "usuario_mensaje.usumensaje_id", "asc", 0, 10, $jsonMjs, true);
        $usuarioMensajes = json_decode($usuarioMensajes);
        $mensajes_no_leidos = $usuarioMensajes->count[0]->{".count"};


        $Mandante = new Mandante($UsuarioMandante->getMandante());

        if ($Mandante->propio === "S") {

            $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
            $Registro = new Registro("", $UsuarioMandante->getUsuarioMandante());
            $primer_nombre = "$Registro->nombre1";
            $segundo_nombre = $Registro->nombre2;
            $primer_apellido = $Registro->apellido1;
            $segundo_apellido = $Registro->apellido2;
            $celular = $Registro->celular;

            $fecha_ultima = $Usuario->fechaUlt;
            $ip_ultima = $Usuario->dirIp;


            $UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->usuarioMandante);


            switch ($UsuarioPerfil->getPerfilId()) {
                case "USUONLINE":

                    $saldo = $Usuario->getBalance();
                    $saldoRecargas = $Registro->getCreditosBase();
                    $saldoRetiros = $Registro->getCreditos();
                    $saldoBonos = $Registro->getCreditosBono();

                    break;

                case "MAQUINAANONIMA":


                    /*$PuntoVenta = new PuntoVenta("",$UsuarioMandante->usuarioMandante);

                    $SaldoRecargas = $PuntoVenta->getCupoRecarga();
                    $SaldoJuego = $PuntoVenta->getCreditosBase();

                    $saldo = $SaldoJuego;
                    $saldoRecargas = $SaldoJuego;
                    $saldoRetiros = $SaldoJuego;
                    $saldoBonos = $SaldoJuego;*/

                    $saldo = $Usuario->getBalance();
                    $saldoRecargas = $Registro->getCreditosBase();
                    $saldoRetiros = $Registro->getCreditos();
                    $saldoBonos = $Registro->getCreditosBono();


                    break;
            }

            $saldo = $saldo;
        }

        $response = array();

        $response['code'] = 0;

        $data = array();
        $profile = array();
        $profile_id = array();

        $min_bet_stakes = array();


        $profile_id['id'] = $usuario_id;
        $profile_id['id_platform'] = $usuario_idPlatform;
        $profile_id['unique_id'] = $usuario_id;
        $profile_id['username'] = $usuario_id;
        $profile_id['name'] = $UsuarioMandante->getNombres() . " " . $UsuarioMandante->getApellidos();
        $profile_id['first_name'] = $primer_nombre . " " . $segundo_nombre;
        $profile_id['last_name'] = $primer_apellido . " " . $segundo_apellido;
        $profile_id['gender'] = "";
        $profile_id['email'] = "";
        $profile_id['phone'] = $celular;
        $profile_id['reg_info_incomplete'] = false;
        $profile_id['address'] = "";


        $profile_id["reg_date"] = "";
        $profile_id["birth_date"] = "";
        $profile_id["doc_number"] = "";
        $profile_id["casino_promo"] = null;
        $profile_id["currency_name"] = $moneda;

        $profile_id["currency_id"] = $moneda;
        $profile_id["balance"] = $saldo;

        //$JOINSERVICES = new JOINSERVICES();

        // $response2 = $JOINSERVICES->getBalance2($UsuarioMandante->getUsumandanteId());

        //$saldoXML = new SimpleXMLElement($response2);

        //  if ($saldoXML->RESPONSE->RESULT != "KO") {
        //    $saldo = $saldoXML->RESPONSE->BALANCE->__toString();
        //  $profile_id["casino_balance"] = $saldo;

        //}


        //$profile_id["casino_balance"] = $saldo;
        $profile_id["exclude_date"] = null;
        $profile_id["bonus_id"] = -1;
        $profile_id["games"] = 0;
        $profile_id["super_bet"] = -1;
        $profile_id["country_code"] = $paisId;
        $profile_id["doc_issued_by"] = null;
        $profile_id["doc_issue_date"] = null;
        $profile_id["doc_issue_code"] = null;
        $profile_id["province"] = null;
        $profile_id["iban"] = null;
        $profile_id["active_step"] = null;
        $profile_id["active_step_state"] = null;
        $profile_id["subscribed_to_news"] = false;
        $profile_id["bonus_balance"] = 0.0;
        $profile_id["frozen_balance"] = 0.0;
        $profile_id["bonus_win_balance"] = 0.0;
        $profile_id["city"] = "city";


        $profile_id["has_free_bets"] = false;
        $profile_id["loyalty_point"] = 0.0;
        $profile_id["loyalty_earned_points"] = 0.0;
        $profile_id["loyalty_exchanged_points"] = 0.0;
        $profile_id["loyalty_level_id"] = null;
        $profile_id["affiliate_id"] = null;
        $profile_id["is_verified"] = false;
        $profile_id["incorrect_fields"] = null;
        $profile_id["loyalty_point_usage_period"] = 0;
        $profile_id["loyalty_min_exchange_point"] = 0;
        $profile_id["loyalty_max_exchange_point"] = 0;
        $profile_id["active_time_in_casino"] = null;
        $profile_id["last_read_message"] = null;
        $profile_id["unread_count"] = $mensajes_no_leidos;
        $profile_id["last_login_date"] = strtotime($fecha_ultima);
        $profile_id["last_login_ip"] = $ip_ultima;

        $profile_id["swift_code"] = null;
        $profile_id["bonus_money"] = 0.0;
        $profile_id["loyalty_last_earned_points"] = 0.0;


        $profile_id["state"] = 1;
        $profile_id["contingency"] = 0;
        $profile_id["contingencySports"] = 0;
        $profile_id["contingencyCasino"] = 0;
        $profile_id["contingencyLiveCasino"] = 0;
        $profile_id["contingencyVirtuals"] = 0;
        $profile_id["contingencyPoker"] = 0;


        if ($Usuario != "") {

            if ($Usuario->estado == "I") {
                $profile_id["state"] = 0;
            }
            if ($Usuario->contingencia == "A") {
                $profile_id["contingency"] = 1;
            }
            if ($Usuario->contingenciaDeportes == "A") {
                $profile_id["contingencySports"] = 1;
            }
            if ($Usuario->contingenciaCasino == "A") {
                $profile_id["contingencyCasino"] = 1;
            }
            if ($Usuario->contingenciaCasvivo == "A") {
                $profile_id["contingencyLiveCasino"] = 1;
            }
            if ($Usuario->contingenciaVirtuales == "A") {
                $profile_id["contingencyVirtuals"] = 1;
            }
            if ($Usuario->contingenciaPoker == "A") {
                $profile_id["contingencyPoker"] = 1;
            }
        }

        $profile_id["showMenuReadTickets"] = ($profile_id["contingencySports"] == 0 ? 1 : 0);
        $profile_id["showMenuWithdraw"] = 1;

        //Para maquina
        /*
         * 1 -> readticket
         *
         */

        $MaxRows = 1;
        $OrderedItem = 1;
        $SkeepRows = 0;


        $rules = [];

        array_push($rules, array("field" => "usuario_log.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
        array_push($rules, array("field" => "usuario_log.tipo", "data" => "ESTADOUSUARIO", "op" => "eq"));

        $filtro = array("rules" => $rules, "groupOp" => "AND");

        $json2 = json_encode($filtro);

        $select = " usuario_log.* ";


        $UsuarioLog = new UsuarioLog();
        $data2 = $UsuarioLog->getUsuarioLogsCustom($select, "usuario_log.fecha_crea", "desc", $SkeepRows, $MaxRows, $json2, true, "");
        $data2 = json_decode($data2);

        $stateM = 0;
        /*
                if($data2->data[0]->{"usuario_log.valor_antes"} =="READTICKET"){
                    if($data2->data[0]->{"usuario_log.estado"}=="A"){
                        $profile_id["message"] = array();
                        $profile_id["message"]["type"]="success";
                        $profile_id["message"]["title"]="Ticket leido";
                        $profile_id["message"]["content"]="Ticket leido satisfactoriamente";

                        $stateM=2;

                    }else{
                        $stateM=1;

                    }
                }

                if($data2->data[0]->{"usuario_log.valor_antes"} =="DEPOSIT"){
                    if($data2->data[0]->{"usuario_log.estado"}=="A"){
                        $profile_id["message"] = array();
                        $profile_id["message"]["type"]="success";
                        $profile_id["message"]["title"]="Deposito";
                        $profile_id["message"]["content"]="Deposito satisfactorio";

                        $stateM=2;

                    }else{
                        $stateM=1;

                    }
                }*/


        $profile_id["StateM"] = $stateM;


        $limites = array();

        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();

        $limitesArray = $UsuarioConfiguracionMySqlDAO->queryByUsuarioId($UsuarioMandante->getUsuarioMandante());

        foreach ($limitesArray as $item) {

            $tipo = "";

            switch ($item->getTipo()) {
                case "EXCTIME":
                    $profile_id["active_time_in_casino"] = intval($item->getValor());

                    break;
            }
        }

        $MaxRows = 1;
        $OrderedItem = 1;
        $SkeepRows = 0;


        $rules = [];

        array_push($rules, array("field" => "usuario_log.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
        array_push($rules, array("field" => "usuario_log.tipo", "data" => "ESTADOUSUARIO", "op" => "eq"));

        $filtro = array("rules" => $rules, "groupOp" => "AND");

        $json2 = json_encode($filtro);

        $select = " usuario_log.* ";


        $UsuarioLog = new UsuarioLog();
        $data2 = $UsuarioLog->getUsuarioLogsCustom($select, "usuario_log.fecha_crea", "desc", $SkeepRows, $MaxRows, $json2, true, "");
        $data2 = json_decode($data2);

        $stateM = 0;

        /*  if($data2->data[0]->{"usuario_log.valor_antes"} =="READTICKET"){
              if($data2->data[0]->{"usuario_log.estado"}=="A"){
                  $profile_id["message"] = array();
                  $profile_id["message"]["type"]="success";
                  $profile_id["message"]["title"]="Ticket leido";
                  $profile_id["message"]["content"]="Ticket leido satisfactoriamente";

                  $stateM=2;

              }else{
                  $stateM=1;

              }
          }

          if($data2->data[0]->{"usuario_log.valor_antes"} =="DEPOSIT"){
              if($data2->data[0]->{"usuario_log.estado"}=="A"){
                  $profile_id["message"] = array();
                  $profile_id["message"]["type"]="success";
                  $profile_id["message"]["title"]="Deposito";
                  $profile_id["message"]["content"]="Deposito satisfactorio";

                  $stateM=2;

              }else{
                  $stateM=1;

              }
          }*/


        $profile_id["StateM"] = $stateM;


        $data = array(
            "7040" . $sid . "1" => array(
                "profile" => array(
                    $usuario_id => $profile_id,
                ),
            ),
            "continueToFront" => 1,

        );

        return $data;
    }


    /**
     * Cambiar la contraseña de usuario
     *
     *
     * @param String $nueva_clave nueva contraseña
     *
     * @return no
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function changeClave($nueva_clave)
    {

        $UsuarioMySqlDAO = new UsuarioMySqlDAO();

        $UsuarioClave = $UsuarioMySqlDAO->updateClave($this, $nueva_clave);

        $UsuarioMySqlDAO->getTransaction()->commit();
    }


    /**
     * Iniciar sesión
     *
     *
     * @param String $username nombre de usuario
     * @param String $clave contraseña
     *
     * @return Array $respuesta respuesta
     * @throws Exception si el usuario ingresado se encuentra inactivo
     * @throws Exception si el sitio se encuentra en mantenimiento
     * @throws Exception si el usuario ha sido bloqueado por exceso de intentos
     * @throws Exception si el usuario ingresado se encuentra inactivo
     * @throws Exception si se ha limitado el acceso a la plataforma
     * @throws Exception si se la clave ingresada es incorrecta
     * @throws Exception si se la clave ingresada es incorrecta
     * @throws Exception si el usuario ingresado se encuentra con registro inactivo
     * @throws Exception si se ha limitado el acceso a la plataforma
     * @throws Exception si el usuario no existe
     * @throws Exception si el UsuarioGeneral no existe
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function login($username, $clave, $plataforma = '0', $mandante = '0', $jsonConfig = "", $external = false, $paisId = '')
    {

        $timeInit = time();
        if ($_REQUEST['testslow'] == '1'  || $_ENV['enabledSlowIntegrations'] === true) {
            print_r("SLOW 1" . ((time() - $timeInit) * 1000));
            print_r(PHP_EOL);
        }

        $UsuarioMySqlDAO = new UsuarioMySqlDAO();


        $Usuario = $UsuarioMySqlDAO->queryByLogin($username, $plataforma, $mandante, $paisId);
        $Usuario = $Usuario[0];

        if ($Usuario != null && $Usuario != "") {

            $this->login = $Usuario->login;
            $this->nombre = $Usuario->nombre;
            $this->estado = $Usuario->estado;
            $this->fechaUlt = $Usuario->fechaUlt;
            $this->claveTv = $Usuario->claveTv;
            $this->estadoAnt = $Usuario->estadoAnt;
            $this->intentos = $Usuario->intentos;
            $this->estadoEsp = $Usuario->estadoEsp;
            $this->observ = $Usuario->observ;
            $this->dirIp = $Usuario->dirIp;
            $this->eliminado = $Usuario->eliminado;
            $this->mandante = $Usuario->mandante;
            $this->usucreaId = $Usuario->usucreaId;
            $this->usumodifId = $Usuario->usumodifId;
            $this->fechaModif = $Usuario->fechaModif;
            $this->claveCasino = $Usuario->claveCasino;
            $this->tokenItainment = $Usuario->tokenItainment;
            $this->fechaClave = $Usuario->fechaClave;
            $this->retirado = $Usuario->retirado;
            $this->fechaRetiro = $Usuario->fechaRetiro;
            $this->horaRetiro = $Usuario->horaRetiro;
            $this->usuretiroId = $Usuario->usuretiroId;
            $this->bloqueoVentas = $Usuario->bloqueoVentas;
            $this->infoEquipo = $Usuario->infoEquipo;
            $this->estadoJugador = $Usuario->estadoJugador;
            $this->tokenCasino = $Usuario->tokenCasino;
            $this->sponsorId = $Usuario->sponsorId;
            $this->verifCorreo = $Usuario->verifCorreo;
            $this->paisId = $Usuario->paisId;
            $this->moneda = $Usuario->moneda;
            $this->idioma = $Usuario->idioma;
            $this->permiteActivareg = $Usuario->permiteActivareg;
            $this->test = $Usuario->test;
            $this->puntoventaId = $Usuario->puntoventaId;
            $this->tiempoLimitedeposito = $Usuario->tiempoLimitedeposito;
            $this->tiempoAutoexclusion = $Usuario->tiempoAutoexclusion;
            $this->cambiosAprobacion = $Usuario->cambiosAprobacion;
            $this->timezone = $Usuario->timezone;
            $this->celular = $Usuario->celular;
            $this->usuarioId = $Usuario->usuarioId;
            $this->origen = $Usuario->origen;
            $this->fechaActualizacion = $Usuario->fechaActualizacion;
            $this->fechaCrea = $Usuario->fechaCrea;
            $this->documentoValidado = $Usuario->documentoValidado;
            $this->fechaDocvalido = $Usuario->fechaDocvalido;
            $this->usuDocvalido = $Usuario->usuDocvalido;
            $this->estadoValida = $Usuario->estadoValida;
            $this->usuvalidaId = $Usuario->usuvalidaId;
            $this->fechaValida = $Usuario->fechaValida;
            $this->contingencia = $Usuario->contingencia;
            $this->contingenciaDeportes = $Usuario->contingenciaDeportes;
            $this->contingenciaCasino = $Usuario->contingenciaCasino;
            $this->contingenciaCasvivo = $Usuario->contingenciaCasvivo;
            $this->contingenciaVirtuales = $Usuario->contingenciaVirtuales;
            $this->contingenciaPoker = $Usuario->contingenciaPoker;
            $this->contingenciaRetiro = $Usuario->contingenciaRetiro;
            $this->contingenciaDeposito = $Usuario->contingenciaDeposito;
            $this->restriccionIp = $Usuario->restriccionIp;
            $this->ubicacionLatitud = $Usuario->ubicacionLatitud;
            $this->ubicacionLongitud = $Usuario->ubicacionLongitud;
            $this->usuarioIp = $Usuario->usuarioIp;
            $this->tokenGoogle = $Usuario->tokenGoogle;
            $this->tokenLocal = $Usuario->tokenLocal;
            $this->saltGoogle = $Usuario->saltGoogle;
            $this->monedaReporte = $Usuario->monedaReporte;
            $this->verifcedulaAnt = $Usuario->verifcedulaAnt;
            $this->verifcedulaPost = $Usuario->verifcedulaPost;
            $this->creditosAfiliacion = $Usuario->creditosAfiliacion;
            $this->fechaCierrecaja = $Usuario->fechaCierrecaja;
            $this->fechaPrimerdeposito = $Usuario->fechaPrimerdeposito;
            $this->montoPrimerdeposito = $Usuario->montoPrimerdeposito;
            $this->skype = $Usuario->skype;
            $this->plataforma = $Usuario->plataforma;
            $this->maximaComision = $Usuario->maximaComision;
            $this->tiempoComision = $Usuario->tiempoComision;
            $this->arrastraNegativo = $Usuario->arrastraNegativo;
            $this->tokenQuisk = $Usuario->tokenQuisk;
            $this->estadoImport = $Usuario->estadoImport;
            $this->verifCelular = $Usuario->verifCelular;
            $this->fechaVerifCelular = $Usuario->fechaVerifCelular;
            $this->billeteraId = $Usuario->billeteraId;
            $this->puntosLealtad = $Usuario->puntosLealtad;
            $this->nivelLealtad = $Usuario->nivelLealtad;
            $this->verifDomicilio = $Usuario->verifDomicilio;

            $this->verificado = $Usuario->verificado;
            $this->fechaVerificado = $Usuario->fechaVerificado;
            $this->permite_enviarpublicidad = $Usuario->permite_enviarpublicidad;

            if ($this->fechaActualizacion == "" || $this->fechaActualizacion == "0000-00-00 00:00:00") {
                $this->fechaActualizacion = $this->fechaCrea;
            }

            if ($this->documentoValidado == "") {
                $this->documentoValidado = "I";
            }

            if ($this->fechaDocvalido == "") {
                $this->fechaDocvalido = "1970-01-01 00:00:00";
            }

            if ($this->usuDocvalido == "") {
                $this->usuDocvalido = "0";
            }
            $this->puntosAexpirar = $Usuario->puntosAexpirar;
            $this->puntosExpirados = $Usuario->puntosExpirados;
            $this->puntosRedimidos = $Usuario->puntosRedimidos;
            //Obtenemos Usuario Perfil

            $UsuarioPerfil = new UsuarioPerfil($this->usuarioId);

            $Perfil = new Perfil($UsuarioPerfil->getPerfilId());

            if ($Perfil->getContingencia() == "S") {

                throw new Exception("En el momento nos encontramos en proceso de mantenimiento del sitio. ", "30004");
            }

            if ($this->retirado == "S") {

                throw new Exception("No puede iniciar sesion en el sitio. ", "30010");
            }
            if ($this->contingencia == "A") {

                throw new Exception("No puede iniciar sesion en el sitio. ", "30010");
            }
            if ($this->usuarioId == "82710") {

                throw new Exception("No puede iniciar sesion en el sitio. ", "30010");
            }
            if ($this->estadoEsp == "I") {

                //throw new Exception("El usuario ingresado se encuentra inactivo.  ", "20003");

            }


            if ($this->eliminado == "S") {

                throw new Exception("El usuario no esta registrado en la plataforma", "20029");
            }

            $ClasificadorLoginIncorrecto = new Clasificador("", "WRONGATTEMPTSLOGIN");
            $maximoIntentos = -1;

            try {
                $MandanteDetalle = new MandanteDetalle("", $this->mandante, $ClasificadorLoginIncorrecto->getClasificadorId(), $this->paisId, 'A');

                $maximoIntentos = $MandanteDetalle->getValor();
            } catch (Exception $e) {
                if ($e->getCode() == 34) {
                } else {
                }
            }


            if ($this->intentos >= $maximoIntentos && $maximoIntentos != -1 && $maximoIntentos != 0 && $maximoIntentos != "") {

                throw new Exception("El usuario ha sido bloqueado por el sistema debido a que excedió el número de intentos permitidos con clave errónea.  ", "30001");
            }

            if ($this->estado == "I") {

                throw new Exception("El usuario ingresado se encuentra inactivo. ", "20003");
            }

            if ($_REQUEST['testslow'] == '1'  || $_ENV['enabledSlowIntegrations'] === true) {
                print_r("SLOW 2" . ((time() - $timeInit) * 1000));
                print_r(PHP_EOL);
            }

            try {
                $Registro = new Registro("", $this->usuarioId);

                if ($Registro->estadoValida == "I") {
                    $ClasificadorRegistro = new Clasificador("", "REQREGACT");

                    try {
                        $MandanteDetalle = new MandanteDetalle("", $this->mandante, $ClasificadorRegistro->getClasificadorId(), $this->paisId);

                        if ($MandanteDetalle->valor == "A") {
                            throw new Exception("El usuario ingresado se encuentra con registro inactivo. ", "30005");
                        } else {
                        }
                    } catch (Exception $e) {
                        if ($e->getCode() == 52) {
                        }
                    }
                }
            } catch (Exception $e) {

                if ($e->getCode() == 52) {
                }
            }
            $ConfigurationEnvironment = new ConfigurationEnvironment();

            $UsuarioMySqlDAO = new UsuarioMySqlDAO();

            $UsuarioGeneral = $UsuarioMySqlDAO->queryForLogin($this, $clave, $plataforma);
            $UsuarioGeneral = $UsuarioGeneral[0];

            if ($external === true) $UsuarioGeneral = (array)$Usuario;


            if ($UsuarioGeneral != "" && $UsuarioGeneral != null) {

                if ($_REQUEST['testslow'] == '1'  || $_ENV['enabledSlowIntegrations'] === true) {
                    print_r("SLOW 3" . ((time() - $timeInit) * 1000));
                    print_r(PHP_EOL);
                }

                /*try{
                    $tipo = "EXCTIMEOUT";
                    $Tipo = new Clasificador("", $tipo);
                    $UsuarioConfiguracion = new UsuarioConfiguracion($this->usuarioId,'A', $Tipo->getClasificadorId());

                    if(strtotime($UsuarioConfiguracion->getValor()) > (time())){
                        throw new Exception("El usuario ingresado esta autoexcluido. ", "20027");
                    }

                }catch (Exception $e){
                    if($e->getCode() != '46'){
                        throw $e;
                    }

                }*/

                try {
                    $tipo = "EXCTOTAL";
                    $Tipo = new Clasificador("", $tipo);
                    $UsuarioConfiguracion = new UsuarioConfiguracion($this->usuarioId, 'A', $Tipo->getClasificadorId());

                    throw new Exception("El usuario ingresado esta autoexcluido. ", "20028");
                } catch (Exception $e) {
                    if ($e->getCode() != '46') {
                        throw $e;
                    }
                }


                if ($UsuarioGeneral['pinagent'] == "S") {

                    throw new Exception("Se ha limitado su acceso a la plataforma. Por favor verifique la URL a la que esta accediendo o contacte a su administrador.", "05");
                }
                if ($jsonConfig != '' && $jsonConfig != null) {

                    if ($UsuarioGeneral["b.perfil_id"] != 'USUONLINE') {
                        if ($UsuarioPerfil->mandante == '2') {
                            $responseBetshop = array();
                            $responseBetshop["code"] = 0;
                            $responseBetshop["rid"] = '';
                            $responseBetshop["redirectUrl"] = '/betshop/';

                            $responseBetshop["data"] = array(
                                "auth_token" => '',
                                "user_id" => '',
                                "id_platform" => '',
                                "channel_id" => '',

                                "redirectUrl" => '/betshop/',
                                "in_app" => ''
                            );
                            print_r(json_encode($responseBetshop));
                            exit();
                        }

                        if ($jsonConfig->params->typeApp == '1' && ($UsuarioGeneral["b.perfil_id"] == 'PUNTOVENTA' || $UsuarioGeneral["b.perfil_id"] == 'CAJERO')) {
                        } else {
                            throw new Exception("No existe ", "30002");
                        }
                    }
                }

                if ($_REQUEST['testslow'] == '1'  || $_ENV['enabledSlowIntegrations'] === true) {
                    print_r("SLOW 4" . ((time() - $timeInit) * 1000));
                    print_r(PHP_EOL);
                }

                $_SESSION["ultimo_incio"] = $this->fechaUlt;

                $_SESSION["logueado"] = true;
                $_SESSION["logueado2"] = true;
                $_SESSION["sistema"] = "D";
                $_SESSION["win_perfil"] = $UsuarioGeneral["b.perfil_id"];
                $_SESSION["win_perfil2"] = $UsuarioGeneral["b.perfil_id"];
                $_SESSION["nombre"] = $UsuarioGeneral["a.nombre"];
                $_SESSION["nombre2"] = $UsuarioGeneral["a.nombre"];
                $_SESSION["tipo_perfil"] = $UsuarioGeneral[".tipo_perfil"];
                $_SESSION["optimizar_parrilla"] = $UsuarioGeneral["d.optimizar_parrilla"];
                $_SESSION["texto_op1"] = $UsuarioGeneral["d.texto_op1"];
                $_SESSION["texto_op2"] = $UsuarioGeneral["d.texto_op2"];
                $_SESSION["url_op2"] = $UsuarioGeneral["d.url_op2"];
                $_SESSION["cant_lineas"] = $UsuarioGeneral["d.cant_lineas"];
                $_SESSION["parlay"] = $UsuarioGeneral[".parlay"];
                $_SESSION["ciudad_id"] = $UsuarioGeneral["ciudad_id"];
                $_SESSION["dir_ip"] = $_SERVER["HTTP_X_FORWARDED_FOR"];
                $_SESSION["pais"] = $UsuarioGeneral[".pais"];
                $_SESSION["pais_nom"] = $UsuarioGeneral["i.pais_nom"];
                $_SESSION["pais_id"] = $UsuarioGeneral["a.pais_id"];
                $_SESSION["moneda"] = $UsuarioGeneral["a.moneda"];
                $_SESSION["moneda_nom"] = $UsuarioGeneral[".moneda_nom"];
                $_SESSION["idioma"] = $UsuarioGeneral["a.idioma"];
                $_SESSION["utc"] = $UsuarioGeneral["i.utc"];
                $_SESSION["token"] = $UsuarioGeneral["a.token_itainment"];
                $_SESSION["req_cheque"] = $UsuarioGeneral["i.req_cheque"];
                $_SESSION["req_doc"] = $UsuarioGeneral["i.req_doc"];
                $_SESSION["permite_activareg"] = $UsuarioGeneral["a.permite_activareg"];
                $_SESSION["timezone"] = $UsuarioGeneral["a.timezone"];
                $_SESSION['ultimo_inicio'] = $Usuario->fechaUlt;


                $_SESSION["PaisCond"] = $UsuarioPerfil->pais;
                $_SESSION["Global"] = $UsuarioPerfil->global;
                $_SESSION["GlobalConfig"] = $UsuarioPerfil->global;
                $_SESSION["monedaReporte"] = $Usuario->monedaReporte;
                $_SESSION['mandante'] = $UsuarioPerfil->mandante;
                $_SESSION['mandanteLista'] = $UsuarioPerfil->mandanteLista;

                if ($_SESSION["Global"] == "S") {
                    $arrayMandante = explode(',', $_SESSION['mandanteLista']);

                    if (!in_array($UsuarioPerfil->globalMandante, $arrayMandante)) {
                        $_SESSION["Global"] = "N";
                        $_SESSION['mandante'] = '-1';
                    } else {
                        if ($UsuarioPerfil->globalMandante != "-1") {
                            $arrayMandante = explode(',', $_SESSION['mandanteLista']);

                            if (in_array($UsuarioPerfil->globalMandante, $arrayMandante)) {
                                $_SESSION["Global"] = "N";
                                $_SESSION['mandante'] = $UsuarioPerfil->globalMandante;
                            } else {
                                $_SESSION["Global"] = "N";
                                $_SESSION['mandante'] = '-1';
                            }
                        } else {
                            $_SESSION['mandante'] = '-1';
                        }
                    }
                }


                //Captura la dirección IP
                $dir_ip = $this->ObtenerIP();

                if ($jsonConfig->session->usuarioip != "" && $jsonConfig->session->usuarioip != null) {
                    $dir_ip = $jsonConfig->session->usuarioip;
                }
                //$dir_ip =substr($dir_ip, 0, 20) ;
                //Actualiza la información de login

                $tknCasino = md5(date("Y-m-d H:i:s") . $UsuarioGeneral["usuario_id"]);
                if ($_SESSION["tipo_perfil"] == "U") {
                    $_SESSION["tokenCasino"] = $tknCasino;
                } else {
                    $_SESSION["tokenCasino"] = "";
                }

                $this->dirIp = substr($dir_ip, 0, 20);
                $this->fechaUlt = Date('Y-m-d H:i:s');
                $this->tokenCasino = $tknCasino;
                $this->intentos = 0;

                if ($this->tiempoLimitedeposito == '') {
                    $this->tiempoLimitedeposito = 0;
                }
                if ($this->tiempoAutoexclusion == '') {
                    $this->tiempoAutoexclusion = 0;
                }
                if ($this->cambiosAprobacion == '') {
                    $this->cambiosAprobacion = 'S';
                }
                if ($this->tokenItainment == '' || $this->tokenItainment == '0') {
                    $this->tokenItainment = $this->usuarioId . GenerarClaveTicket2(12);
                }
                //$UsuarioMySqlDAO->updateLogin($this);

                $token = "";
                try {
                    $UsuarioMandante = new UsuarioMandante("", $this->usuarioId, $this->mandante);
                    throw new Exception("No existe ", "21");

                    $UsuarioToken = new UsuarioToken("", "0", $UsuarioMandante->getUsumandanteId());

                    if (in_array($this->mandante, array('0', '6', '8', '2', '12', 3, 4, 5, 6, 7)) || true) {
                        $UsuarioToken->setEstado('I');

                        $UsuarioTokenMySqlDAO = new \Backend\mysql\UsuarioTokenMySqlDAO();
                        $UsuarioTokenMySqlDAO->update($UsuarioToken);
                        $UsuarioTokenMySqlDAO->getTransaction()->commit();

                        throw new Exception("No existe ", "21");
                    }
                } catch (Exception $e) {

                    if ($e->getCode() == 22) {

                        $UsuarioMandante = new UsuarioMandante();

                        $UsuarioMandante->mandante = $this->mandante;
                        //$UsuarioMandante->dirIp = $dir_ip;
                        $UsuarioMandante->nombres = $this->nombre;
                        $UsuarioMandante->apellidos = $this->nombre;
                        $UsuarioMandante->estado = $this->estado;
                        $UsuarioMandante->email = $this->login;
                        $UsuarioMandante->moneda = $this->moneda;
                        $UsuarioMandante->paisId = $this->paisId;
                        $UsuarioMandante->saldo = 0;
                        $UsuarioMandante->usuarioMandante = $this->usuarioId;
                        $UsuarioMandante->usucreaId = 0;
                        $UsuarioMandante->usumodifId = 0;
                        $UsuarioMandante->propio = 'S';

                        $UsuarioMandanteMySqlDAO = new UsuarioMandanteMySqlDAO($UsuarioMySqlDAO->getTransaction());
                        $usuario_id = $UsuarioMandanteMySqlDAO->insert($UsuarioMandante);

                        $UsuarioToken = new UsuarioToken();

                        $UsuarioToken->setRequestId('');
                        $UsuarioToken->setProveedorId(0);
                        $UsuarioToken->setUsuarioId($UsuarioMandante->getUsumandanteId());
                        $UsuarioToken->setToken($UsuarioToken->createToken());
                        $UsuarioToken->setCookie($ConfigurationEnvironment->encrypt($UsuarioMandante->getUsumandanteId() . "#" . time()));
                        $UsuarioToken->setUsumodifId(0);
                        $UsuarioToken->setUsucreaId(0);
                        $UsuarioToken->setSaldo(0);

                        $UsuarioTokenMySqlDAO = new \Backend\mysql\UsuarioTokenMySqlDAO($UsuarioMySqlDAO->getTransaction());
                        $UsuarioTokenMySqlDAO->insert($UsuarioToken);


                        $_SESSION["usuario"] = $usuario_id;
                        $_SESSION["usuario2"] = $usuario_id;
                        $_SESSION["consultaAgente"] = $UsuarioPerfil->consultaAgente;

                        if ($_SESSION["consultaAgente"] == '') {
                            $_SESSION["consultaAgente"] = '0';
                        }
                        $_SESSION["regionperfil"] = $UsuarioPerfil->region;

                        if ($_SESSION["regionperfil"] == '') {
                            $_SESSION["regionperfil"] = '0';
                        }
                    }
                    if ($e->getCode() == "21") {

                        $UsuarioToken = new UsuarioToken();

                        $UsuarioToken->setRequestId('');
                        $UsuarioToken->setProveedorId('0');
                        $UsuarioToken->setUsuarioId($UsuarioMandante->getUsumandanteId());
                        $UsuarioToken->setToken($UsuarioToken->createToken());
                        //$UsuarioToken->setCookie(encrypt($UsuarioMandante->getUsumandanteId() . "#" . time()));
                        $UsuarioToken->setUsumodifId(0);
                        $UsuarioToken->setUsucreaId(0);
                        $UsuarioToken->setCookie('');
                        $UsuarioToken->setSaldo(0);




                        $UsuarioTokenMySqlDAO = new \Backend\mysql\UsuarioTokenMySqlDAO($UsuarioMySqlDAO->getTransaction());
                        $UsuarioTokenMySqlDAO->insert($UsuarioToken);
                    }
                }

                if ($_REQUEST['testslow'] == '1'  || $_ENV['enabledSlowIntegrations'] === true) {
                    print_r("SLOW 5" . ((time() - $timeInit) * 1000));
                    print_r(PHP_EOL);
                }

                $ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
                $ip = explode(",", $ip)[0];

                $tipoUsuariolog = "LOGIN";
                if ($jsonConfig->params->typeApp != "" && $jsonConfig->params->typeApp != null) {
                    if ($jsonConfig->params->typeApp == "0") {

                        $tipoUsuariolog = "LOGINAPP";
                    }
                }

                $plaform = strval($_SERVER['HTTP_SEC_CH_UA_PLATFORM']);
                $plaform = str_replace('"', "", $plaform);

                try {
                    $useragent = $_SERVER['HTTP_USER_AGENT'];
                    if ($useragent != '') {

                        $useragent = explode(")", $useragent);
                        $useragent = explode(")", $useragent[0]);
                        $useragent = explode("(", $useragent[0]);
                        $version = explode(";", $useragent[1]);

                        if ($plaform == '') {
                            $plaform = $version[0];
                        }
                        $version = $version[1];
                        //$version=$useragent;
                    }
                } catch (Exception $e) {
                }

                if ($version == '') {
                    $message = '*VERSION VACIA* ';
                    $Bbody = '';
                    // exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . base64_encode($message) . "' '#dev' '" . $_SERVER['SERVER_ADDR'] . "' 'base64' '" . base64_encode(json_encode($_SERVER)) . "' '" . base64_encode(json_encode($_SESSION)) . "' '" . base64_encode($Bbody) . "' > /dev/null & ");
                }


                if ($_REQUEST['testslow'] == '1'  || $_ENV['enabledSlowIntegrations'] === true) {
                    print_r("SLOW 6" . ((time() - $timeInit) * 1000));
                    print_r(PHP_EOL);
                }


                //$MobileDetect = new MobileDetect();


                $UsuarioMySqlDAO->getTransaction()->commit();


                $UsuarioLog = new UsuarioLog();
                $UsuarioLog->setUsuarioId($this->usuarioId);
                $UsuarioLog->setUsuarioIp($ip);
                $UsuarioLog->setUsuariosolicitaId($this->usuarioId);
                $UsuarioLog->setUsuariosolicitaIp($ip);
                $UsuarioLog->setUsuarioaprobarId(0);
                $UsuarioLog->setTipo($tipoUsuariolog);
                $UsuarioLog->setEstado("A");
                $UsuarioLog->setValorAntes($ip);
                $UsuarioLog->setValorDespues($ip);

                $UsuarioLog->setSoperativo($plaform);
                $UsuarioLog->setSversion($version);

                $UsuarioLog->setUsucreaId(0);
                $UsuarioLog->setUsumodifId(0);

                $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();

                $UsuarioLogMySqlDAO->insert($UsuarioLog);
                $UsuarioLogMySqlDAO->getTransaction()->commit();

                if (strpos($ip, '200.24.151.70') !== false && $this->mandante == 8) {
                    try {

                        $message = "Login desde 200.24.151.70 *Usuario:* " . $this->usuarioId;

                        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . $message . "' '#riesgo-ecuabet' > /dev/null & ");
                    } catch (Exception $e) {
                    }
                }
                if (strpos($ip, '67.205.142.16') !== false && $this->mandante == 8) {
                    try {

                        $message = "Login desde 67.205.142.16 *Usuario:* " . $this->usuarioId;

                        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . $message . "' '#riesgo-ecuabet' > /dev/null & ");
                    } catch (Exception $e) {
                    }
                }


                $_SESSION["usuario2"] = $UsuarioMandante->getUsumandanteId();
                $_SESSION["usuario"] = $UsuarioMandante->getUsuarioMandante();
                $_SESSION["consultaAgente"] = $UsuarioPerfil->consultaAgente;
                $_SESSION["regionperfil"] = $UsuarioPerfil->region;

                if ($_SESSION["regionperfil"] == '') {
                    $_SESSION["regionperfil"] = '0';
                }
                if ($_SESSION["consultaAgente"] == '') {
                    $_SESSION["consultaAgente"] = '0';
                }
                //Devuelve mensaje satisfactorio

                $array['status'] = true;
                $array['message'] = 'Login Success';
                $array['auth_token'] = $UsuarioToken->getToken();
                $array['token_itn'] = $this->tokenItainment;
                $array['user_id'] = $UsuarioMandante->getUsumandanteId();
                $array['user_id2'] = $UsuarioMandante->getUsuarioMandante();

                $respuesta = json_decode(json_encode($array, true));

                $useragent = $_SERVER['HTTP_USER_AGENT'];
                $jsonServer = json_encode($_SERVER);
                $serverCodif = base64_encode($jsonServer);


                $ismobile = '';

                if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {

                    $ismobile = '1';
                }
                //Detect special conditions devices
                $iPod = stripos($_SERVER['HTTP_USER_AGENT'], "iPod");
                $iPhone = stripos($_SERVER['HTTP_USER_AGENT'], "iPhone");
                $iPad = stripos($_SERVER['HTTP_USER_AGENT'], "iPad");
                $Android = stripos($_SERVER['HTTP_USER_AGENT'], "Android");
                $webOS = stripos($_SERVER['HTTP_USER_AGENT'], "webOS");



                //do something with this information
                if ($iPod || $iPhone) {
                    $ismobile = '1';
                } else if ($iPad) {
                    $ismobile = '1';
                } else if ($Android) {
                    $ismobile = '1';
                }


                if ($_REQUEST['testslow'] == '1'  || $_ENV['enabledSlowIntegrations'] === true) {
                    print_r("SLOW 7" . ((time() - $timeInit) * 1000));
                    print_r(PHP_EOL);
                }


                //  exec("php -f ". __DIR__ ."/../integrations/crm/AgregarCrm.php " . $Usuario->usuarioId . " " . "LOGINCRM" . " " . $Usuario->usuarioId . " " . $serverCodif . " " . $ismobile . " > /dev/null &");

                return $respuesta;
            } else {

                $ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
                $ip = explode(",", $ip)[0];

                $this->intentos = $this->intentos + 1;

                if ($maximoIntentos != -1) {
                    if ($this->intentos >= $maximoIntentos) {
                        $this->estado = 'I';
                    }
                }

                $plaform = strval($_SERVER['HTTP_SEC_CH_UA_PLATFORM']);
                $plaform = str_replace('"', "", $plaform);

                try {
                    $useragent = $_SERVER['HTTP_USER_AGENT'];
                    if ($useragent != '') {

                        $useragent = explode(")", $useragent);
                        $useragent = explode(")", $useragent[0]);
                        $useragent = explode("(", $useragent[0]);
                        $version = explode(";", $useragent[1]);
                        $version = $version[1];

                        if ($plaform == '') {
                            $version = $version[0];
                        }
                        //$version=$useragent;
                    }
                } catch (Exception $e) {
                }


                $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                $UsuarioMySqlDAO->update($this);
                $UsuarioMySqlDAO->getTransaction()->commit();

                $UsuarioLog = new UsuarioLog();
                $UsuarioLog->setUsuarioId($this->usuarioId);
                $UsuarioLog->setUsuarioIp($ip);
                $UsuarioLog->setUsuariosolicitaId($this->usuarioId);
                $UsuarioLog->setUsuariosolicitaIp($ip);
                $UsuarioLog->setUsuarioaprobarId(0);
                $UsuarioLog->setTipo("LOGININCORRECTO");
                $UsuarioLog->setEstado("A");
                $UsuarioLog->setValorAntes($ip);
                $UsuarioLog->setValorDespues($username);
                $UsuarioLog->setUsucreaId(0);
                $UsuarioLog->setUsumodifId(0);

                $UsuarioLog->setSoperativo($plaform);
                $UsuarioLog->setSversion($version);

                $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();

                $UsuarioLogMySqlDAO->insert($UsuarioLog);

                $UsuarioLogMySqlDAO->getTransaction()->commit();


                throw new Exception("Clave incorrecta ", "30003");
            }
        } else {


            throw new Exception("No existe ", "30002");
        }
    }

    public function login2($username, $clave, $plataforma = '0', $mandante = '0', $jsonConfig = "")
    {


        $UsuarioMySqlDAO = new UsuarioMySqlDAO();


        $Usuario = $UsuarioMySqlDAO->queryByLogin($username, $plataforma, $mandante);
        $Usuario = $Usuario[0];

        if ($Usuario != null && $Usuario != "") {

            $this->login = $Usuario->login;
            $this->nombre = $Usuario->nombre;
            $this->estado = $Usuario->estado;
            $this->fechaUlt = $Usuario->fechaUlt;
            $this->claveTv = $Usuario->claveTv;
            $this->estadoAnt = $Usuario->estadoAnt;
            $this->intentos = $Usuario->intentos;
            $this->estadoEsp = $Usuario->estadoEsp;
            $this->observ = $Usuario->observ;
            $this->dirIp = $Usuario->dirIp;
            $this->eliminado = $Usuario->eliminado;
            $this->mandante = $Usuario->mandante;
            $this->usucreaId = $Usuario->usucreaId;
            $this->usumodifId = $Usuario->usumodifId;
            $this->fechaModif = $Usuario->fechaModif;
            $this->claveCasino = $Usuario->claveCasino;
            $this->tokenItainment = $Usuario->tokenItainment;
            $this->fechaClave = $Usuario->fechaClave;
            $this->retirado = $Usuario->retirado;
            $this->fechaRetiro = $Usuario->fechaRetiro;
            $this->horaRetiro = $Usuario->horaRetiro;
            $this->usuretiroId = $Usuario->usuretiroId;
            $this->bloqueoVentas = $Usuario->bloqueoVentas;
            $this->infoEquipo = $Usuario->infoEquipo;
            $this->estadoJugador = $Usuario->estadoJugador;
            $this->tokenCasino = $Usuario->tokenCasino;
            $this->sponsorId = $Usuario->sponsorId;
            $this->verifCorreo = $Usuario->verifCorreo;
            $this->paisId = $Usuario->paisId;
            $this->moneda = $Usuario->moneda;
            $this->idioma = $Usuario->idioma;
            $this->permiteActivareg = $Usuario->permiteActivareg;
            $this->test = $Usuario->test;
            $this->puntoventaId = $Usuario->puntoventaId;
            $this->tiempoLimitedeposito = $Usuario->tiempoLimitedeposito;
            $this->tiempoAutoexclusion = $Usuario->tiempoAutoexclusion;
            $this->cambiosAprobacion = $Usuario->cambiosAprobacion;
            $this->timezone = $Usuario->timezone;
            $this->celular = $Usuario->celular;
            $this->usuarioId = $Usuario->usuarioId;
            $this->origen = $Usuario->origen;
            $this->fechaActualizacion = $Usuario->fechaActualizacion;
            $this->fechaCrea = $Usuario->fechaCrea;
            $this->documentoValidado = $Usuario->documentoValidado;
            $this->fechaDocvalido = $Usuario->fechaDocvalido;
            $this->usuDocvalido = $Usuario->usuDocvalido;
            $this->estadoValida = $Usuario->estadoValida;
            $this->usuvalidaId = $Usuario->usuvalidaId;
            $this->fechaValida = $Usuario->fechaValida;
            $this->contingencia = $Usuario->contingencia;
            $this->contingenciaDeportes = $Usuario->contingenciaDeportes;
            $this->contingenciaCasino = $Usuario->contingenciaCasino;
            $this->contingenciaCasvivo = $Usuario->contingenciaCasvivo;
            $this->contingenciaVirtuales = $Usuario->contingenciaVirtuales;
            $this->contingenciaPoker = $Usuario->contingenciaPoker;
            $this->contingenciaRetiro = $Usuario->contingenciaRetiro;
            $this->contingenciaDeposito = $Usuario->contingenciaDeposito;
            $this->restriccionIp = $Usuario->restriccionIp;
            $this->ubicacionLatitud = $Usuario->ubicacionLatitud;
            $this->ubicacionLongitud = $Usuario->ubicacionLongitud;
            $this->usuarioIp = $Usuario->usuarioIp;
            $this->tokenGoogle = $Usuario->tokenGoogle;
            $this->tokenLocal = $Usuario->tokenLocal;
            $this->saltGoogle = $Usuario->saltGoogle;
            $this->monedaReporte = $Usuario->monedaReporte;
            $this->verifcedulaAnt = $Usuario->verifcedulaAnt;
            $this->verifcedulaPost = $Usuario->verifcedulaPost;
            $this->creditosAfiliacion = $Usuario->creditosAfiliacion;
            $this->fechaCierrecaja = $Usuario->fechaCierrecaja;
            $this->fechaPrimerdeposito = $Usuario->fechaPrimerdeposito;
            $this->montoPrimerdeposito = $Usuario->montoPrimerdeposito;
            $this->skype = $Usuario->skype;
            $this->plataforma = $Usuario->plataforma;
            $this->maximaComision = $Usuario->maximaComision;
            $this->tiempoComision = $Usuario->tiempoComision;
            $this->arrastraNegativo = $Usuario->arrastraNegativo;
            $this->tokenQuisk = $Usuario->tokenQuisk;
            $this->estadoImport = $Usuario->estadoImport;
            $this->verifCelular = $Usuario->verifCelular;
            $this->fechaVerifCelular = $Usuario->fechaVerifCelular;
            $this->billeteraId = $Usuario->billeteraId;
            $this->puntosLealtad = $Usuario->puntosLealtad;
            $this->nivelLealtad = $Usuario->nivelLealtad;
            $this->verifDomicilio = $Usuario->verifDomicilio;


            if ($this->fechaActualizacion == "" || $this->fechaActualizacion == "0000-00-00 00:00:00") {
                $this->fechaActualizacion = $this->fechaCrea;
            }

            if ($this->documentoValidado == "") {
                $this->documentoValidado = "I";
            }

            if ($this->fechaDocvalido == "") {
                $this->fechaDocvalido = "1970-01-01 00:00:00";
            }

            if ($this->usuDocvalido == "") {
                $this->usuDocvalido = "0";
            }
            //Obtenemos Usuario Perfil

            $UsuarioPerfil = new UsuarioPerfil($this->usuarioId);

            $Perfil = new Perfil($UsuarioPerfil->getPerfilId());

            if ($Perfil->getContingencia() == "S") {

                throw new Exception("En el momento nos encontramos en proceso de mantenimiento del sitio. ", "30004");
            }
            if ($this->contingencia == "A") {

                throw new Exception("No puede iniciar sesion en el sitio. ", "30010");
            }
            if ($this->estadoEsp == "I") {

                //throw new Exception("El usuario ingresado se encuentra inactivo.  ", "20003");

            }


            if ($this->eliminado == "S") {

                throw new Exception("El usuario no esta registrado en la plataforma", "20029");
            }

            $ClasificadorLoginIncorrecto = new Clasificador("", "WRONGATTEMPTSLOGIN");
            $maximoIntentos = -1;

            try {
                $MandanteDetalle = new MandanteDetalle("", $this->mandante, $ClasificadorLoginIncorrecto->getClasificadorId(), $this->paisId, 'A');

                $maximoIntentos = $MandanteDetalle->getValor();
            } catch (Exception $e) {
                if ($e->getCode() == 34) {
                } else {
                }
            }


            if ($this->intentos >= $maximoIntentos && $maximoIntentos != -1 && $maximoIntentos != 0 && $maximoIntentos != "") {

                throw new Exception("El usuario ha sido bloqueado por el sistema debido a que excedió el número de intentos permitidos con clave errónea.  ", "30001");
            }

            if ($this->estado == "I") {

                throw new Exception("El usuario ingresado se encuentra inactivo. ", "20003");
            }

            try {
                $Registro = new Registro("", $this->usuarioId);

                if ($Registro->estadoValida == "I") {
                    $ClasificadorRegistro = new Clasificador("", "REQREGACT");

                    try {
                        $MandanteDetalle = new MandanteDetalle("", $this->mandante, $ClasificadorRegistro->getClasificadorId(), $this->paisId);

                        if ($MandanteDetalle->valor == "A") {
                            throw new Exception("El usuario ingresado se encuentra con registro inactivo. ", "30005");
                        } else {
                        }
                    } catch (Exception $e) {
                        if ($e->getCode() == 52) {
                        }
                    }
                }
            } catch (Exception $e) {

                if ($e->getCode() == 52) {
                }
            }
            $ConfigurationEnvironment = new ConfigurationEnvironment();

            $UsuarioMySqlDAO = new UsuarioMySqlDAO();

            $UsuarioGeneral = $UsuarioMySqlDAO->queryForLogin($this, $clave, $plataforma);
            $UsuarioGeneral = $UsuarioGeneral[0];


            if ($UsuarioGeneral != "" && $UsuarioGeneral != null) {

                /*try{
                    $tipo = "EXCTIMEOUT";
                    $Tipo = new Clasificador("", $tipo);
                    $UsuarioConfiguracion = new UsuarioConfiguracion($this->usuarioId,'A', $Tipo->getClasificadorId());

                    if(strtotime($UsuarioConfiguracion->getValor()) > (time())){
                        throw new Exception("El usuario ingresado esta autoexcluido. ", "20027");
                    }

                }catch (Exception $e){
                    if($e->getCode() != '46'){
                        throw $e;
                    }

                }*/

                try {
                    $tipo = "EXCTOTAL";
                    $Tipo = new Clasificador("", $tipo);
                    $UsuarioConfiguracion = new UsuarioConfiguracion($this->usuarioId, 'A', $Tipo->getClasificadorId());

                    throw new Exception("El usuario ingresado esta autoexcluido. ", "20028");
                } catch (Exception $e) {
                    if ($e->getCode() != '46') {
                        throw $e;
                    }
                }


                if ($UsuarioGeneral['pinagent'] == "S") {

                    throw new Exception("Se ha limitado su acceso a la plataforma. Por favor verifique la URL a la que esta accediendo o contacte a su administrador.", "05");
                }
                $ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
                $ip = explode(",", $ip)[0];

                $tipoUsuariolog = "LOGIN";

                $MobileDetect = new MobileDetect();


                $UsuarioLog = new UsuarioLog();
                $UsuarioLog->setUsuarioId($this->usuarioId);
                $UsuarioLog->setUsuarioIp($this->dirIp);
                $UsuarioLog->setUsuariosolicitaId($this->usuarioId);
                $UsuarioLog->setUsuariosolicitaIp($this->dirIp);
                $UsuarioLog->setUsuarioaprobarId(0);
                $UsuarioLog->setTipo($tipoUsuariolog);
                $UsuarioLog->setEstado("A");
                $UsuarioLog->setValorAntes($this->dirIp);
                $UsuarioLog->setValorDespues($this->dirIp);
                $UsuarioLog->setUsucreaId(0);
                $UsuarioLog->setUsumodifId(0);

                $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO($UsuarioMySqlDAO->getTransaction());

                $UsuarioLogMySqlDAO->insert($UsuarioLog);


                $UsuarioMySqlDAO->getTransaction()->commit();
            } else {


                $this->intentos = $this->intentos + 1;

                if ($maximoIntentos != -1) {
                    if ($this->intentos >= $maximoIntentos) {
                        $this->estado = 'I';
                    }
                }
                $ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
                $ip = explode(",", $ip)[0];

                $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                $UsuarioMySqlDAO->update($this);
                $UsuarioMySqlDAO->getTransaction()->commit();

                $UsuarioLog = new UsuarioLog();
                $UsuarioLog->setUsuarioId($this->usuarioId);
                $UsuarioLog->setUsuarioIp($ip);
                $UsuarioLog->setUsuariosolicitaId($this->usuarioId);
                $UsuarioLog->setUsuariosolicitaIp($ip);
                $UsuarioLog->setUsuarioaprobarId(0);
                $UsuarioLog->setTipo("LOGININCORRECTO");
                $UsuarioLog->setEstado("A");
                $UsuarioLog->setValorAntes($ip);
                $UsuarioLog->setValorDespues($ip);
                $UsuarioLog->setUsucreaId(0);
                $UsuarioLog->setUsumodifId(0);

                $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();

                $UsuarioLogMySqlDAO->insert($UsuarioLog);

                $UsuarioLogMySqlDAO->getTransaction()->commit();


                throw new Exception("Clave incorrecta ", "30003");
            }
        } else {


            throw new Exception("No existe ", "30002");
        }
    }


    public function loginPlaytech($username, $clave, $site_id)
    {
        $getOS = function ($userAgent) {
            $os = "Desconocido";
            if (stripos($userAgent, 'Windows') !== false) {
                $os = 'Windows';
            } elseif (stripos($userAgent, 'Linux') !== false) {
                $os = 'Linux';
            } elseif (stripos($userAgent, 'Macintosh') !== false || stripos($userAgent, 'Mac OS X') !== false || preg_match("#mac(\s)?Os#i", $userAgent)) {
                $os = 'MacOS';
            }

            return $os;
        };
        $reducirAbreviados = function (array $abreviados) {
            $concats = array_reduce($abreviados, function ($concat, $abreviado) {
                return $concat .= ($concat == null ? "" : ",") . "'{$abreviado}'";
            }, null);

            return $concats;
        };

        /** Obtención origen de la solicitud */
        $ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
        $ip = explode(",", $ip)[0];
        $platform = $_SERVER['HTTP_SEC_CH_UA_PLATFORM'];
        $sistemaOperativo = $getOS($platform);

        /** Verificando existencia del usuario */
        $UsuarioMySqlDAO = new UsuarioMySqlDAO();
        $UsuarioResp = $UsuarioMySqlDAO->queryByLogin($username, 0, $site_id);
        $UsuarioResp = $UsuarioResp[0];

        foreach ($UsuarioResp as $attribute => $value) {
            $this->$attribute = $value;
        }

        if (empty((array) $UsuarioResp)) throw new Exception("No existe ", "30002");

        /** Verificando estados del usuario */
        //verificadndo contingencia general
        if ($this->contingencia == 'A') {
            throw new Exception("No puede iniciar sesion en el sitio. ", "30010");
        }

        //Verificando usuario eliminado
        if ($this->eliminado == "S") {
            throw new Exception("El usuario no esta registrado en la plataforma", "20029");
        }

        //Verificando estado general del usuario
        if ($this->estado == "I") {
            throw new Exception("El usuario ingresado se encuentra inactivo. ", "20003");
        }

        //Verificando usuario retirado
        if ($this->retirado == "S") {
            throw new Exception("No puede iniciar sesion en el sitio. ", "30010");
        }

        /** validación clasificadores en MandanteDetalle */
        $clasificadores = [
            'WRONGATTEMPTSLOGIN',
            'REQREGACT'
        ];
        $clasificadoresString = $reducirAbreviados($clasificadores);

        $MandanteDetalle = new MandanteDetalle();
        $rules = ['field' => 'mandante_detalle.estado', 'data' => 'A', 'op' => 'eq'];
        $rules = ['field' => 'mandante_detalle.mandante', 'data' => $this->mandante, 'op' => 'eq'];
        $rules = ['field' => 'mandante_detalle.pais_id', 'data' => $this->paisId, 'op' => 'eq'];
        $rules = ['field' => 'mandante_detalle.tipo', 'data' => $clasificadoresString, 'op' => 'in'];
        $filters = ['rules' => $rules, 'groupOp' => 'AND'];

        $select = "mandante_detalle.manddetalle_id, mandante_detalle.tipo, mandante_detalle.valor, clasificador.abreviado";
        $sidx = "mandante_detalle.manddetalle_id";
        $sord = "DESC";
        $start = 0;
        $limit = count($clasificadores);

        $detallesResponse = $MandanteDetalle->getMandanteDetallesCustom($select, $sidx, $sord, $start, $limit, json_encode($filters), true);
        $detallesResponse = json_decode($detallesResponse)->data;

        $Registro = new Registro(null, $this->usuarioId);
        $UsuarioPerfil = new UsuarioPerfil($this->usuarioId);
        $Perfil = new Perfil($UsuarioPerfil->getPerfilId());
        foreach ($detallesResponse as $detalle) {
            switch ($detalle->{'clasificador.abreviado'}) {
                case "WRONGATTEMPTSLOGIN":
                    $maximoLoginIncorrectos = $detalle->{'mandante_detalle.valor'};
                    if ($maximoLoginIncorrectos == -1 || empty($maximoLoginIncorrectos)) continue 2;

                    if ($this->intentos >= $maximoLoginIncorrectos) {
                        throw new Exception("El usuario ha sido bloqueado por el sistema debido a que excedió el número de intentos permitidos con clave errónea.  ", "30001");
                    }
                    break;

                case 'REQREGACT':
                    $registroActivoRequired = $detalle->{'mandante_detalle.valor'};

                    if ($registroActivoRequired == 'A' && $Registro->estadoValida == 'I') {
                        throw new Exception("El usuario ingresado se encuentra con registro inactivo. ", "30005");
                    }
                    break;
            }
        }

        /** Validaciones del perfil */
        if ($UsuarioPerfil->getPerfilId() != "USUONLINE") {
            throw new Exception("No existe ", "30002");
        }

        if ($Perfil->contingencia == 'A') {
            throw new Exception("En el momento nos encontramos en proceso de mantenimiento del sitio. ", "30004");
        }


        /** Verificando clave del usuario */
        $UsuarioGeneral = $UsuarioMySqlDAO->queryForLogin($this, $clave);
        $UsuarioGeneral = $UsuarioGeneral[0];

        /** Generando usuarioLog y token */
        $Transaction = new Transaction();
        $resultadoLogin = ($UsuarioGeneral != null && $UsuarioGeneral != "") ? "LOGINCORRECTOPLAYTECHPOKER" : "LOGINFALLOPLAYTECHPOKER";

        $UsuarioLog = new UsuarioLog();
        $UsuarioLog->setUsuarioId($this->usuarioId);
        $UsuarioLog->setUsuarioIp($ip);
        $UsuarioLog->setUsuariosolicitaId($this->usuarioId);
        $UsuarioLog->setUsuariosolicitaIp($ip);
        $UsuarioLog->setUsuarioaprobarId(0);
        $UsuarioLog->setTipo($resultadoLogin);
        $UsuarioLog->setEstado("A");
        $UsuarioLog->setValorAntes($ip);
        $UsuarioLog->setValorDespues($ip);
        $UsuarioLog->setSoperativo($sistemaOperativo);
        $UsuarioLog->setUsucreaId(0);
        $UsuarioLog->setUsumodifId(0);

        $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO($Transaction);
        $UsuarioLogMySqlDAO->insert($UsuarioLog);

        if ($UsuarioGeneral != null && $UsuarioGeneral != "") {
            /** Generando TOKEN */
            $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO($Transaction);
            $UsuarioMandante = new UsuarioMandante(null, $this->usuarioId, $this->mandante);
            $Proveedor = new Proveedor("", "PLAYTECH");

            try {
                //Verificando e inactivando tokens viejos
                $UsuarioToken = new UsuarioToken('', $Proveedor->getProveedorId(), $UsuarioMandante->usumandanteId, null, null, null, null, 'A');
                $UsuarioToken->setEstado('I');

                $UsuarioTokenMySqlDAO->update($UsuarioToken);
            } catch (Exception $e) {
                if ($e->getCode() != 21) throw $e;
            }

            //Generando nuevo TOKEN
            $NewUsuarioToken = new UsuarioToken();
            $NewUsuarioToken->setProveedorId($Proveedor->getProveedorId());
            $NewUsuarioToken->setCookie('0');
            $NewUsuarioToken->setRequestId('0');
            $NewUsuarioToken->setUsucreaId(0);
            $NewUsuarioToken->setUsumodifId(0);
            $NewUsuarioToken->setUsuarioId($UsuarioMandante->usumandanteId);
            $NewUsuarioToken->setToken($NewUsuarioToken->createToken());
            $NewUsuarioToken->setSaldo(0);
            $NewUsuarioToken->setProductoId(0);

            $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO($Transaction);
            $UsuarioTokenMySqlDAO->insert($NewUsuarioToken);
            $Transaction->commit();

            return (object)['token' => $NewUsuarioToken->getToken(), 'usumandanteId' => $UsuarioMandante->getUsumandanteId(), 'balance' => $this->getBalance()];
        } else {
            $Transaction->commit();
            throw new Exception("Clave incorrecta ", "30003");
        }
    }


    /**
     * Obtener los menús
     *
     *
     * @param no
     *
     * @return $Usuario usuario
     * @throws Exception si el usuario no existe
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function getMenus()
    {

        $UsuarioMySqlDAO = new UsuarioMySqlDAO();
        $Usuario = $UsuarioMySqlDAO->queryMenus($this->usuarioId, $this->mandante);

        if ($Usuario != null && $Usuario != "") {
            return $Usuario;
        } else {
            throw new Exception("No existe " . get_class($this), "01");
        }
    }


    /**
     * Modificar el balance de un usuario
     *
     *
     * @param float $Balance balance
     * @param Objeto $transaccion transacción
     *
     * @return no
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function setBalance($Balance, $transaction)
    {

        $Registro = new Registro("", $this->usuarioId);

        if ($Registro->success) {
            $Registro->setCreditosBase($Balance);

            $RegistroMySqlDAO = new RegistroMySqlDAO($transaction);
            //$RegistroMySqlDAO->update($Registro);
            $RegistroMySqlDAO->updateBalance($Registro, "", $Balance, "", "", "");
        }
    }


    /**
     * Modificar la cuenta débito de un usuario
     *
     *
     * @param float $valor valor
     * @param Objeto $transaccion transacción
     *
     * @return no
     * @throws Exception si el usuario no tiene fondos suficientes
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function execQuery($transaccion, $sql)
    {

        $UsuarioMySqlDAO = new UsuarioMySqlDAO($transaccion);
        $return = $UsuarioMySqlDAO->querySQL($sql);

        //$return = json_decode(json_encode($return), FALSE);

        return $return;
    }


    /**
     * Debita una cantidad del balance del usuario.
     *
     * @param float $valor La cantidad a debitar.
     * @param mixed $transaction La transacción asociada.
     * @param int $tipo El tipo de débito (0 - sportsbook, 1 - casino). Por defecto es 0.
     * @param bool $withValidation Indica si se debe realizar la validación. Por defecto es false.
     * 
     * @throws Exception Si el usuario no tiene fondos suficientes.
     */
    public function debit($valor, $transaction, $tipo = 0, $withValidation = false)
    {

        //tipo
        // 0 - sportsbook
        // 1 - casino

        $Registro = new Registro("", $this->usuarioId);

        if ($Registro->success) {

            $Balance = $this->getBalance();
            $creditos_base = $Registro->getCreditosBase();

            $valor_free = 0;
            $saldo_free = 0;
            if ($tipo == 1) {
                //$saldoCasinoBonos = $Registro->getSaldoCasinoBonos();
                //$saldo_free = $saldoCasinoBonos;
            }


            if (($Balance + $saldo_free) < $valor) {

                throw new Exception("No tiene fondos suficientes", "20001");
            }


            //Calcula el valor de creditos base que tiene que restar de acuerdo al valor de la apuesta

            if ($saldo_free > 0) {
                if ($valor > $saldo_free) {
                    $valor_free = $saldo_free;
                    $valor = $valor - $valor_free;
                } else {
                    $valor_free = $valor;
                    $valor = 0;
                }
            }


            if ($valor > $creditos_base) {
                $valor_base = $creditos_base;
                $valor_adicional = $valor - $creditos_base;
            } else {
                $valor_base = $valor;
                $valor_adicional = 0;
            }
            $valor_base = round($valor_base, 5);
            $valor_adicional = round($valor_adicional, 5);

            $Registro->setCreditosBase("ROUND(creditos_base - " . $valor_base . ",2)");
            $Registro->setCreditos("ROUND(creditos - " . $valor_adicional . ",2)");
            if ($tipo == 1) {
                $Registro->setSaldoCasinoBonos("ROUND(saldo_casino_bonos - " . $valor_free . ",2)");
            }

            $RegistroMySqlDAO = new RegistroMySqlDAO($transaction);

            $update = $RegistroMySqlDAO->updateBalance($Registro, -$valor_adicional, -$valor_base, "", "", -$valor_free, $withValidation);
            //$RegistroMySqlDAO->update($Registro);

            if ($update <= 0 && $valor != 0) {
                throw new Exception("No tiene fondos suficientes", "20001");
            }
        }
    }


    /**
     * Modificar el crédito de un usuario
     *
     *
     * @param float $valor valor
     * @param Objeto $transaccion transacción
     *
     * @return no
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function credit($valor, $transaction, $withValidation = false)
    {

        $Registro = new Registro("", $this->usuarioId);

        if ($Registro->success) {
            $valor = round($valor, 5);
            $Balance = $Registro->getCreditosBase();
            $Registro->setCreditosBase("ROUND(creditos_base + " . $valor . ",2)");
            $RegistroMySqlDAO = new RegistroMySqlDAO($transaction);
            //$RegistroMySqlDAO->update($Registro);
            $return = $RegistroMySqlDAO->updateBalance($Registro, "", $valor, "", "", "", $withValidation);

            return $return;
        }
        return 0;
    }

    /**
     * Modificar los puntos de un usuario
     *
     *
     * @param float $valor valor
     * @param Objeto $transaccion transacción
     *
     * @return no
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function debitPoints($valor, $transaction, $tipo = 0, $withValidation = false)
    {
        $UsuarioMySqlDAO = new UsuarioMySqlDAO($transaction);

        $update = $UsuarioMySqlDAO->updateBalancePoints($this->usuarioId, "", -$valor, true);
        //$UsuarioMySqlDAO->getTransaction()->commit();
        if ($update <= 0 && $valor != 0) {
            throw new Exception("No tiene puntos suficientes", "20001");
        }
    }


    /**
     * Modificar los puntos de un usuario
     *
     *
     * @param float $valor valor
     * @param Objeto $transaccion transacción
     *
     * @return no
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function creditPoints($valor, $transaction)
    {
        $valor = round($valor, 0, PHP_ROUND_HALF_DOWN);

        $UsuarioMySqlDAO = new UsuarioMySqlDAO($transaction);
        $return = $UsuarioMySqlDAO->updateBalancePoints($this->usuarioId, "", $valor, "");

        return $return;
    }

    /**
     * Redime puntos de crédito del usuario.
     *
     * @param float $valor La cantidad de puntos a redimir.
     * @param mixed $transaction La transacción asociada a la redención de puntos.
     * 
     * @throws Exception Si el usuario no tiene suficientes puntos para redimir.
     */
    public function creditPointsRedeemed($valor, $transaction)
    {
        $UsuarioMySqlDAO = new UsuarioMySqlDAO($transaction);
        $update = $UsuarioMySqlDAO->updateBalancePointsRedeemed($this->usuarioId, "", $valor, "");

        if ($update <= 0 && $valor != 0) {
            throw new Exception("No tiene puntos suficientes", "20001");
        }
    }


    /**
     * Modificar el credito de un usuario si gana
     *
     *
     * @param float $valor valor
     * @param Objeto $transaccion transacción
     *
     * @return no
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function creditWin($valor, $transaction, $withValidation = false)
    {

        $Registro = new Registro("", $this->usuarioId);

        if ($Registro->success) {

            $Registro->setCreditos("ROUND(creditos + " . $valor . ",2)");
            $RegistroMySqlDAO = new RegistroMySqlDAO($transaction);
            //$RegistroMySqlDAO->update($Registro);
            $RegistroMySqlDAO->updateBalance($Registro, $valor, "", "", "", "", $withValidation);
        }
    }

    /**
     * Modificar el credito de un usuario si gana
     *
     *
     * @param float $valor valor
     * @param Objeto $transaccion transacción
     *
     * @return no
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function creditWin3($valor, $transaction, $withValidation = false, $valorBase = 0)
    {

        $Registro = new Registro("", $this->usuarioId);

        if ($Registro->success) {

            $Registro->setCreditos("ROUND(creditos + " . $valor . ",2)");
            $Registro->setCreditosBase("ROUND(creditos_base + " . $valorBase . ",2)");
            $RegistroMySqlDAO = new RegistroMySqlDAO($transaction);
            //$RegistroMySqlDAO->update($Registro);
            $RegistroMySqlDAO->updateBalance($Registro, $valor, $valorBase, "", "", "", $withValidation);
        }
    }

    /**
     * Acredita una cantidad de valor a los créditos del usuario.
     *
     * @param float $valor La cantidad de valor a acreditar.
     * @param mixed $transaction La transacción asociada.
     * @param bool $withValidation Indica si se debe realizar la validación durante la actualización del balance.
     * @return int Retorna 0 si la operación falla, de lo contrario, retorna el resultado de la actualización del balance.
     */
    public function creditWin2($valor, $transaction, $withValidation = false)
    {

        $Registro = new Registro("", $this->usuarioId);

        if ($Registro->success) {

            $Registro->setCreditos("ROUND(creditos + " . $valor . ",2)");
            $RegistroMySqlDAO = new RegistroMySqlDAO($transaction);
            //$RegistroMySqlDAO->update($Registro);
            return $RegistroMySqlDAO->updateBalance($Registro, $valor, "", "", "", "", $withValidation);
        }
        return 0;
    }

    /**
     * Gestionar la salida del sistema de un usuario
     *
     *
     * @param no
     *
     * @return no
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function exitsLogin($plataforma = 0)
    {

        $UsuarioMySqlDAO = new UsuarioMySqlDAO();
        $Usuario = $UsuarioMySqlDAO->queryByLogin($this->login, $plataforma, $this->mandante);

        if (oldCount($Usuario) > 0) {
            return true;
        }
        return false;
    }

    /**
     * Verificar el bono de un usuario
     *
     *
     * @param String $tipo tipo
     * @param String $producto producto
     * @param float $valor valor
     *
     * @return String $return resultado de la verificación
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function verificarBono($tipo, $producto, $valor)
    {
        $return = 0;
        if ($tipo == "casino") {
            $rules = [];

            array_push($rules, array("field" => "promocional_log.usuario_id", "data" => "$this->usuarioId", "op" => "eq"));
            array_push($rules, array("field" => "promocional_log.estado", "data" => "A", "op" => "eq"));

            /* if($PlayerExternalId != ""){
                 array_push($rules, array("field" => "promocional_log.usuario_id", "data" => $PlayerExternalId , "op" => "eq"));
             }*/

            $filtro = array("rules" => $rules, "groupOp" => "AND");

            $SkeepRows = 0;
            $OrderedItem = 1;
            $MaxRows = 1;

            $json = json_encode($filtro);

            $PromocionalLog = new PromocionalLog();

            $bonolog = $PromocionalLog->getPromocionalLogsCustom(" promocional_log.* ", "promocional_log.promolog_id", "desc", $SkeepRows, $MaxRows, $json, true);

            $bonolog = json_decode($bonolog);


            if (oldCount($bonolog->data) > 0) {
                $rules = [];

                $bonoid = $bonolog->data[0]->{"promocional_log.promocional_id"};
                array_push($rules, array("field" => "bono_detalle.bono_id", "data" => "$bonoid", "op" => "eq"));
                array_push($rules, array("field" => "bono_detalle.tipo", "data" => "CONDGAME" . $producto, "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                $json = json_encode($filtro);

                $BonoDetalle = new BonoDetalle();

                $bonodetalles = $BonoDetalle->getBonoDetallesCustom(" bono_detalle.* ", "bono_detalle.bonodetalle_id", "asc", $SkeepRows, $MaxRows, $json, true);

                $bonodetalles = json_decode($bonodetalles);


                if (oldCount($bonodetalles->data) > 0) {
                    $porcentajeproducto = $bonodetalles->data[0]->{"bono_detalle.valor"};

                    $valorrollower = ($porcentajeproducto / 100) * $valor;

                    $rollowerRequerido = 0;


                    $PromocionalLog2 = new PromocionalLog("", "", $bonolog->data[0]->{"promocional_log.promolog_id"});

                    $PromocionalLog2->apostado = " apostado + " . $valorrollower;

                    /*$rules = [];

                    array_push($rules, array("field" => "bono_detalle.bono_id", "data" => "$bonoid", "op" => "eq"));
                    array_push($rules, array("field" => "bono_detalle.tipo", "data" => "WFACTORBONO", "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $json = json_encode($filtro);

                    $RollowerBono = $BonoDetalle->getBonoDetallesCustom(" bono_detalle.* ", "bono_detalle.bonodetalle_id", "asc", $SkeepRows, $MaxRows, $json, true);

                    $RollowerBono = json_decode($RollowerBono);

                    if(oldCount($RollowerBono) >0) {
                        $rollowerRequerido = $rollowerRequerido + ($RollowerBono->data[0]->{"bono_detalle.valor"} * $bonolog->data[0]->{"promocional_log.valor"});
                    }

                    $rules = [];

                    array_push($rules, array("field" => "bono_detalle.bono_id", "data" => "$bonoid", "op" => "eq"));
                    array_push($rules, array("field" => "bono_detalle.tipo", "data" => "WFACTORDEPOSITO", "op" => "eq"));

                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $json = json_encode($filtro);

                    $RollowerDeposito = $BonoDetalle->getBonoDetallesCustom(" bono_detalle.* ", "bono_detalle.bonodetalle_id", "asc", $SkeepRows, $MaxRows, $json, true);

                    $RollowerDeposito = json_decode($RollowerDeposito);

                    if(oldCount($RollowerDeposito) >0) {
                        $rollowerRequerido = $rollowerRequerido + ($RollowerDeposito->data[0]->{"bono_detalle.valor"} *  $bonolog->data[0]->{"promocional_log.valor_base"});
                    }
                    */

                    $PromocionalLogMySqlDAO = new PromocionalLogMySqlDAO();
                    $PromocionalLogMySqlDAO->update($PromocionalLog2);
                    $PromocionalLogMySqlDAO->getTransaction()->commit();

                    $PromocionalLog2->verifyRollower();

                    $return = $bonolog->data[0]->{"promocional_log.promolog_id"};
                }
            }
        }

        return $return;
    }


    /**
     * Realizar una consulta en la tabla de TranssportsbookApi 'TranssportsbookApi'
     * de una manera personalizada
     *
     *
     * @param String $estado estado del usuario
     * @param Objeto $perfil perfil del usuario
     * @param String $sidx columna para ordenar
     * @param String $sord orden los datos asc | desc
     * @param String $start inicio de la consulta
     * @param String $limit limite de la consulta
     * @param String $filters condiciones de la consulta
     * @param boolean $searchOn utilizar los filtros o no
     *
     * @return Objeto $Usuario usuario
     * @throws Exception si el usuario no existe
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function getUsuarios($estado, $perfil, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $UsuarioMySqlDAO = new UsuarioMySqlDAO();

        $miperfil = "SA";
        $Usuario = $UsuarioMySqlDAO->queryUsuarios($this->usuarioId, 0, $miperfil, $estado, $perfil, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($Usuario != null && $Usuario != "") {
            return $Usuario;
        } else {
            throw new Exception("No existe " . get_class($this), "01");
        }
    }

    /**
     * Realizar una consulta en la tabla de usuarios 'Usuario'
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
     * @throws Exception si el usuario no existe
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function getUsuariosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping = "")
    {

        $UsuarioMySqlDAO = new UsuarioMySqlDAO();

        $Usuario = $UsuarioMySqlDAO->queryUsuariosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping);

        if ($Usuario != null && $Usuario != "") {

            return $Usuario;
        } else {
            throw new Exception("No existe " . get_class($this), "01");
        }
    }
    public function getUsuariosCustom2($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping = "")
    {

        $UsuarioMySqlDAO = new UsuarioMySqlDAO();

        $Usuario = $UsuarioMySqlDAO->queryUsuariosCustom2($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping);

        if ($Usuario != null && $Usuario != "") {

            return $Usuario;
        } else {
            throw new Exception("No existe " . get_class($this), "01");
        }
    }

    /**
     * Get Domain object by primry key
     *
     * @param
     * @return int
     */
    public function getUsuariosResumenAfiliadosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping = "")
    {

        $UsuarioMySqlDAO = new UsuarioMySqlDAO();

        $Usuario = $UsuarioMySqlDAO->queryUsuariosResumenAfiliadosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping);

        if ($Usuario != null && $Usuario != "") {

            return $Usuario;
        } else {
            throw new Exception("No existe " . get_class($this), "01");
        }
    }

    /**
     * Get Domain object by primry key
     *
     * @param
     * @return int
     */
    public function getUsuariosKPICustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping, $usuarioId)
    {

        $UsuarioMySqlDAO = new UsuarioMySqlDAO();

        $Usuario = $UsuarioMySqlDAO->queryUsuariosKPICustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping, $usuarioId);

        if ($Usuario != null && $Usuario != "") {
            return $Usuario;
        } else {
            throw new Exception("No existe " . get_class($this), "01");
        }
    }


    /**
     * Realizar una consulta en la tabla de usuarios super 'UsuariosSuper'
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
     * @return Array resultado de la consulta
     * @throws Exception si las transacciones no existen
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function getUsuariosSuperCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $UsuarioMySqlDAO = new UsuarioMySqlDAO();

        $Usuario = $UsuarioMySqlDAO->queryUsuariosSuperCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($Usuario != null && $Usuario != "") {
            return $Usuario;
        } else {
            throw new Exception("No existe " . get_class($this), "01");
        }
    }

    /**
     * OModificar el balance de un usuario
     *
     *
     * @param float $Balance balance
     * @param Objeto $transaccion transacción
     *
     * @return no
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function getMovimientosResume($fechaInicio, $fechaFin, $tipo)
    {

        $UsuarioMySqlDAO = new UsuarioMySqlDAO();
        $Usuario = $UsuarioMySqlDAO->queryUsuarioResume($fechaInicio, $fechaFin, $this->usuarioId, $tipo);

        return $Usuario;
    }


    /**
     * Obtener un resumen de los movimientos totales de un usuario
     *
     *
     * @param no
     *
     * @return Array $Usuario resultado de la consulta
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function getMovimientosTotalResume()
    {

        $UsuarioMySqlDAO = new UsuarioMySqlDAO();
        $Usuario = $UsuarioMySqlDAO->queryUsuarioTotalResume($this->usuarioId);
        return $Usuario;
    }

    /**
     * Obtener la ip de la conexión actual
     *
     *
     * @param no
     *
     * @return String $ipaddress ip
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function ObtenerIP()
    {
        $ipaddress = '';
        if ($_SERVER['HTTP_CLIENT_IP'])
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if ($_SERVER['HTTP_X_FORWARDED_FOR'])
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if ($_SERVER['HTTP_X_FORWARDED'])
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if ($_SERVER['HTTP_FORWARDED_FOR'])
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if ($_SERVER['HTTP_FORWARDED'])
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if ($_SERVER['REMOTE_ADDR'])
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';

        return $ipaddress;
    }

    /**
     * Genera una clave de ticket aleatoria de una longitud especificada.
     *
     * @param int $length La longitud de la clave de ticket a generar.
     * @return string La clave de ticket generada.
     */
    function GenerarClaveTicket2($length)
    {
        $characters = '0123456789';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    /**
     * Establece el nombre del usuario.
     *
     * @param string $nombre El nombre del usuario.
     */
    public function SetNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     * Obtiene el nombre del usuario.
     *
     * @return string El nombre del usuario.
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Establece el apellido del usuario.
     *
     * @param string $apellido El apellido del usuario.
     */
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;
    }

    /**
     * Obtiene el apellido del usuario.
     *
     * @return string El apellido del usuario.
     */
    public function getApellido()
    {
        return $this->apellido;
    }

    /**
     * Establece el login del usuario.
     *
     * @param string $email El email del usuario.
     */
    public function setLogin($email)
    {
        $this->login = $email;
    }

    /**
     * Obtiene el login del usuario.
     *
     * @return string El email del usuario.
     */
    public function getLogin()
    {
        return $this->email;
    }

    /**
     * Establece la nacionalidad del usuario.
     *
     * @param string $nacionalidad La nacionalidad del usuario.
     */
    public function setNacionalidad($nacionalidad)
    {
        $this->nacionalidad = $nacionalidad;
    }

    /**
     * Obtiene la nacionalidad del usuario.
     *
     * @return string La nacionalidad del usuario.
     */
    public function getNacionalidad()
    {
        return $this->nacionalidad;
    }

    /**
     * Establece el CIP del usuario.
     *
     * @param string $CIP El CIP del usuario.
     */
    public function setCPI($CIP)
    {
        $this->CIP = $CIP;
    }

    /**
     * Obtiene el CIP del usuario.
     *
     * @return string El CIP del usuario.
     */
    public function getCIP()
    {
        return $this->CIP;
    }

    /**
     * Establece el domicilio del usuario.
     *
     * @param string $domicilio El domicilio del usuario.
     */
    public function setHome($domicilio)
    {
        $this->domicilio = $domicilio;
    }

    /**
     * Establece la clave del usuario.
     *
     * @param string $clave La clave del usuario.
     */
    public function setClave($clave)
    {
        $this->clave = $clave;
    }

    /**
     * Establece la fecha de la clave del usuario.
     *
     * @param string $fechaClave La fecha de la clave del usuario.
     */
    public function setFechaClave($fechaClave)
    {
        $this->fechaClave = $fechaClave;
    }

    /**
     * Obtiene la fecha de la clave del usuario.
     *
     * @return string La fecha de la clave del usuario.
     */
    public function getFechaClave()
    {
        return $this->fechaClave;
    }

    /**
     * Obtiene el affiliationPath del proveedor Altenar.
     *
     * @return string El affiliationPath del proveedor Altenar.
     */
    public function getAffiliationPathAltenar()
    {
        $aff = "";

        $Mandante = new Mandante($this->mandante);
        $Pais = new Pais($this->paisId);

        $pathPartner = $Mandante->pathItainment;
        $pathFixed = $Pais->codigoPath;
        $usermoneda = $this->moneda;
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


            if ($Mandante->mandante == 0 && $this->paisId == '60') {
                $pathPartner = "1:doradobet,S0-60";
            }

            if ($Mandante->mandante == '0') {
                $pathPartner = "1:doradobet,S" . $Mandante->mandante . "-" . $this->paisId;
            }
            if ($Mandante->mandante == '8') {
                $pathPartner = "1:ecuabet,S" . $Mandante->mandante;
            }

            $ConfigurationEnvironment = new ConfigurationEnvironment();


            if ($ConfigurationEnvironment->isDevelopment()) {

                if ($Mandante->mandante == '0') {
                    $pathPartner = "1:doradobet,S" . '34';
                }
            } else {

                if ($Mandante->mandante == '0') {
                    $pathPartner = "1:doradobet,S0-" . $Pais->paisId;
                }
            }
        }


        if ($Mandante->mandante == '12') {
            $pathPartner = "1:powerbet,S16-" . $this->paisId;
        }


        if ($Mandante->mandante == '18') {
            $pathPartner = "1:gangabet,S22-" . $this->paisId;
            if ($this->paisId == '173') {
                $pathPartner = "1:gangabet,S22-" . $this->paisId;
            }
        }

        if ($Mandante->mandante == '19') {
            $pathPartner = "1:vfst,vfst";
        }

        $UsuarioPerfil = new UsuarioPerfil($this->usuarioId);
        $Perfil = new Perfil($UsuarioPerfil->perfilId);

        switch ($Perfil->getTipo()) {
            case 'U':
                $perfil = 'USER';
                break;
            case 'A':
                $perfil = 'ADMIN';
                break;
            case 'M':
                $perfil = 'MACHINE';
                break;
            default:
                $perfil = 'COMERCIAL';
                break;
        }

        if ($perfil == 'COMERCIAL') {
            $PuntoVenta = new PuntoVenta('', $this->puntoventaId);
            $suma = 50000 + intval($PuntoVenta->puntoventaId);
            $pathFixed = '2:Shop' . $suma . ',' . $suma;
        }

        $aff = '0:Betlatam,L3|' . $pathPartner . '|' . $pathFixed;

        if ($Mandante->mandante == '27') {
            if ($this->paisId == '94') {
                $aff = "0:Betlatam,L1|1:Ganaplay.gt,Ganaplay.gt|" . $pathFixed;
            }
            if ($this->paisId == '94') {
                $aff = "0:Betlatam,L1|1:Ganaplay.sv,Ganaplay.sv|" . $pathFixed;
            }
        }


        return $aff;
    }
}
