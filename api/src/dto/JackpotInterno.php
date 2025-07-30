<?php namespace Backend\dto;

use Backend\dao\TransjuegoInfoDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\ItTicketEnc;
use Backend\dto\TransjuegoLog;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioMensaje;
use Backend\dto\UsuarioMensajecampana;
use Backend\integrations\crm\EventsOptimove;
use Backend\mysql\BonoLogMySqlDAO;
use Backend\mysql\CiudadMySqlDAO;
use Backend\mysql\JackpotDetalleMySqlDAO;
use Backend\mysql\JackpotInternoMySqlDAO;
use Backend\mysql\LogRechazoSTBMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\UsuariojackpotGanadorMySqlDAO;
use Backend\dto\UsuariojackpotGanador;
use Backend\mysql\UsuarioMensajecampanaMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\UsuarioJackpotMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\sql\Connection;
use Backend\sql\ConnectionProperty;
use Backend\sql\SqlQuery;
use Backend\websocket\WebsocketUsuario;
use Exception;
use Backend\sql\Transaction;
use Backend\dto\TransjuegoInfo;
use Backend\mysql\TransjuegoInfoMySqlDAO;
use Backend\dto\ItTicketEncInfo1;
use Backend\mysql\ItTicketEncInfo1MySqlDAO;
use Backend\dto\TransaccionJuego;
use Backend\dto\JackpotDetalle;
use Backend\dto\ProductoMandante;
use Backend\dto\CategoriaProducto;
use Backend\dto\Producto;
use Backend\dto\UsuarioJackpot;
use Backend\dto\BonoLog;
use Backend\dto\LogRechazoSTB;
use PDO;


/**
 * Clase 'JackpotInterno'
 *
 * Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
 * para la tabla 'JackpotInterno'
 *
 * Ejemplo de uso:
 * $JackpotInterno = new JackpotInterno();
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class JackpotInterno
{

    /**
     * Representación de la columna 'Jackpot' de la tabla 'JackpotInterno'
     *
     * @var string
     */
    var $jackpotId;

    var $jackpotPadre;

    /**
     * Representación de la columna 'fechaInicio' de la tabla 'JackpotInterno'
     *
     * @var string
     */
    var $fechaInicio;

    /**
     * Representación de la columna 'fechaFin' de la tabla 'JackpotInterno'
     *
     * @var string
     */
    var $fechaFin;

    /**
     * Representación de la columna 'descripcion' de la tabla 'JackpotInterno'
     *
     * @var string
     */
    var $descripcion;

    /**
     * Representación de la columna 'nombre' de la tabla 'JackpotInterno'
     *
     * @var string
     */
    var $nombre;

    /**
     * Representación de la columna 'tipo' de la tabla 'JackpotInterno'
     *
     * @var string
     */
    var $tipo;

    var $reinicio;

    /**
     * Representación de la columna 'estado' de la tabla 'JackpotInterno'
     *
     * @var string
     */
    var $estado;

    /**
     * Representación de la columna 'fechaModif' de la tabla 'JackpotInterno'
     *
     * @var string
     */
    var $fechaModif;

    /**
     * Representación de la columna 'fechaCrea' de la tabla 'JackpotInterno'
     *
     * @var string
     */
    var $fechaCrea;

    /**
     * Representación de la columna 'mandante' de la tabla 'JackpotInterno'
     *
     * @var string
     */
    var $mandante;

    /**
     * Representación de la columna 'condicional' de la tabla 'JackpotInterno'
     *
     * @var string
     */
    var $paisId;

    var $valorActual;

    /**
     * Representación de la columna 'puntos' de la tabla 'JackpotInterno'
     *
     * @var string
     */
    var $orden;

    /**
     * Representación de la columna 'cupoActual' de la tabla 'JackpotInterno'
     *
     * @var string
     */
    var $valorBase;

    /**
     * Representación de la columna 'cupoMaximo' de la tabla 'JackpotInterno'
     *
     * @var string
     */
    var $valorMaximo;

    /**
     * Representación de la columna 'cantidadJackpot' de la tabla 'JackpotInterno'
     *
     * @var string
     */
    var $minimoTicket;

    /**
     * Representación de la columna 'maximoJackpots' de la tabla 'JackpotInterno'
     *
     * @var string
     */
    var $maximoTicket;

    /**
     * Representación de la columna 'usucreaId' de la tabla 'JackpotInterno'
     *
     * @var string
     */
    var $usucreaId;

    /**
     * Representación de la columna 'usumodifId' de la tabla 'JackpotInterno'
     *
     * @var string
     */
    var $usumodifId;

    /**
     * Representación de la columna 'codigo' de la tabla 'JackpotInterno'
     *
     * @var string
     */
    var $cantidadApuestamax;

    /**
     * Representación de la columna 'porcentajeApuestas' de la tabla 'JackpotInterno'
     * @var string
     */
    var $porcentajeApuestas;

    /** @var string Representación de la columna 'imagen' de la tabla 'JackpotInterno' */
    var $imagen;

    /** @var string Representación de la columna 'imagen2' de la tabla 'JackpotInterno' */
    var $imagen2;

    /** @var string Representación de la columna 'gif' de la tabla 'JackpotInterno' */
    var $gif;

    /** @var string Representación de la columna 'gif2' de la tabla 'JackpotInterno' */
    var $gif2;

    /**
     * Representación de la columna 'reglas' de la tabla 'JackpotInterno'
     *
     * @var string
     */
    var $reglas;

    /**
     * Representación de la columna 'videoMobile' de la tabla 'JackpotInterno'
     *
     * @var string
     */
    var $videoMobile;

    /**
     * Representación de la columna 'videoDesktop' de la tabla 'JackpotInterno'
     *
     * @var string
     */
    var $videoDesktop;

    /**
     * Representación de la columna 'notas' de la tabla 'JackpotInterno'
     *
     * @var string
     */
    var $notas;

    /**
     * Representación de la columna 'informacion' de la tabla 'JackpotInterno'
     *
     * @var string
     */
    var $informacion;

    /**
     * Constructor de la clase que inicializa una nueva instancia con los datos correspondientes de un jackpot.
     *
     * @param string $jackpotId El identificador del jackpot a cargar, en caso de ser proporcionado.
     * @return void
     * @throws Exception Si el jackpot con el ID proporcionado no existe.
     */
    public function __construct($jackpotId = "")
    {
        if ($jackpotId != "") {


            $this->jackpotId = $jackpotId;

            $JackpotInternoMySqlDAO = new JackpotInternoMySqlDAO();

            $JackpotInterno = $JackpotInternoMySqlDAO->load($this->jackpotId);


            if ($JackpotInterno != null && $JackpotInterno != "") {
                $this->jackpotId = $JackpotInterno->jackpotId;
                $this->jackpotPadre = $JackpotInterno->jackpotPadre;
                $this->fechaInicio = $JackpotInterno->fechaInicio;
                $this->fechaFin = $JackpotInterno->fechaFin;
                $this->descripcion = $JackpotInterno->descripcion;
                $this->tipo = $JackpotInterno->tipo;
                $this->reinicio = $JackpotInterno->reinicio;
                $this->nombre = $JackpotInterno->nombre;
                $this->estado = $JackpotInterno->estado;
                $this->mandante = $JackpotInterno->mandante;
                $this->paisId = $JackpotInterno->paisId;
                $this->valorActual = $JackpotInterno->valorActual;
                $this->usucreaId = $JackpotInterno->usucreaId;
                $this->fechaCrea = $JackpotInterno->fechaCrea;
                $this->usumodifId = $JackpotInterno->usumodifId;
                $this->fechaModif = $JackpotInterno->fechaModif;
                $this->orden = $JackpotInterno->orden;
                $this->valorBase = $JackpotInterno->valorBase;
                $this->valorMaximo = $JackpotInterno->valorMaximo;
                $this->minimoTicket = $JackpotInterno->minimoTicket;
                $this->maximoTicket = $JackpotInterno->maximoTicket;
                $this->cantidadApuesta = $JackpotInterno->cantidadApuesta;
                $this->cantidadApuestamax = $JackpotInterno->cantidadApuestamax;
                $this->porcentajeApuestas = $JackpotInterno->porcentajeApuestas;
                $this->imagen = $JackpotInterno->imagen;
                $this->imagen2 = $JackpotInterno->imagen2;
                $this->gif = $JackpotInterno->gif;
                $this->gif2 = $JackpotInterno->gif2;
                $this->reglas = $JackpotInterno->reglas;
                $this->videoMobile = $JackpotInterno->videoMobile;
                $this->videoDesktop = $JackpotInterno->videoDesktop;
                $this->notas = $JackpotInterno->notas;
                $this->informacion = $JackpotInterno->informacion;

            } else {
                throw new Exception("No existe " . get_class($this), "28");
            }

        }
    }

    /**
     * Obtener el valor de la columna 'Jackpot_id' de la tabla 'JackpotInterno'
     * @return String
     */
    public function getJackpotId()
    {
        return $this->jackpotId;
    }

    /**
     * Establecer el valor de la propiedad 'jackpotId'.
     * @param int $jackpotId El nuevo valor para la propiedad 'jackpotId'.
     * @return void
     */
    public function setJackpotId($jackpotId)
    {
        $this->jackpotId = $jackpotId;
    }

    /**
     * Devuelve el valor de la propiedad 'jackpotPadre'.
     *
     * @return mixed El valor actual de 'jackpotPadre'.
     */
    public function getJackpotPadre()
    {
        return $this->jackpotPadre;
    }

    /**
     * Establece el valor de la propiedad 'jackpotPadre'.
     *
     * @param mixed $jackpotPadre El valor a asignar a 'jackpotPadre'.
     * @return void
     */
    public function setJackpotPadre($jackpotPadre): void
    {
        $this->jackpotPadre = $jackpotPadre;
    }

    /**
     * Obtener el valor de la columna 'fechaInicio' de la tabla 'JackpotInterno'
     * @return string
     */
    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }

    /**
     * Establecer el valor de la propiedad 'fechaInicio'
     * @param mixed $fechaInicio El valor a asignar a 'fechaInicio'
     * @return void
     */
    public function setFechaInicio($fechaInicio)
    {
        $this->fechaInicio = $fechaInicio;
    }

    /**
     * Obtener el valor de la columna 'fechaFin' de la tabla 'JackpotInterno'
     * @return string
     */
    public function getFechaFin()
    {
        return $this->fechaFin;
    }

    /**
     * Establece el valor de la columna 'fechaFin' de la tabla 'JackpotInterno'
     * @param mixed $fechaFin Valor a asignar a 'fechaFin'
     * @return void
     */
    public function setFechaFin($fechaFin)
    {
        $this->fechaFin = $fechaFin;
    }

    /**
     * Obtener el valor de la columna 'descripcion' de la tabla 'JackpotInterno'
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Establecer el valor de la propiedad 'descripcion'
     * @param string $descripcion El valor a asignar a la propiedad 'descripcion'
     * @return void
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    /**
     * Obtener el valor de la columna 'nombre' de la tabla 'JackpotInterno'
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Establecer el valor de la columna 'nombre'
     * @param string $nombre El nuevo valor para la propiedad 'nombre'
     * @return void
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     * Obtener el valor de la columna 'tipo' de la tabla 'JackpotInterno'
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Establecer el valor de la columna 'tipo' de la tabla 'JackpotInterno'
     * @param mixed $tipo El valor a asignar a 'tipo'
     * @return void
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    /**
     * Obtener el valor de la columna 'reinicio' de la tabla correspondiente.
     * @return mixed
     */
    public function getReinicio()
    {
        return $this->reinicio;
    }

    /**
     * Establecer el valor de la columna 'reinicio' de la tabla 'JackpotInterno'
     * @param mixed $reinicio El nuevo valor para 'reinicio'
     * @return void
     */
    public function setReinicio($reinicio): void
    {
        $this->reinicio = $reinicio;
    }

    /**
     * Devuelve el estado actual del objeto.
     *
     * @return mixed El valor del estado.
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Establece el valor de la propiedad 'estado'.
     *
     * @param mixed $estado El nuevo valor para 'estado'.
     * @return void
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    /**
     * Obtiene el valor de la propiedad 'fechaModif'.
     *
     * @return mixed El valor actual de 'fechaModif'.
     */
    public function getFechaModif()
    {
        return $this->fechaModif;
    }

    /**
     * Establece el valor de la propiedad 'fechaModif'.
     *
     * @param mixed $fechaModif El nuevo valor para 'fechaModif'.
     * @return void
     */
    public function setFechaModif($fechaModif)
    {
        $this->fechaModif = $fechaModif;
    }

    /**
     * Obtiene el valor de la propiedad 'fechaCrea'.
     *
     * @return mixed El valor actual de 'fechaCrea'.
     */
    public function getFechaCrea()
    {
        return $this->fechaCrea;
    }

    /**
     * Establece el valor de la propiedad 'fechaCrea'.
     *
     * @param mixed $fechaCrea El nuevo valor para 'fechaCrea'.
     * @return void
     */
    public function setFechaCrea($fechaCrea)
    {
        $this->fechaCrea = $fechaCrea;
    }

    /**
     * Recupera el valor de la propiedad 'mandante'.
     *
     * @return mixed El valor actual de 'mandante'.
     */
    public function getMandante()
    {
        return $this->mandante;
    }

    /**
     * Establece el valor de la propiedad 'mandante'.
     *
     * @param mixed $mandante El nuevo valor para 'mandante'.
     * @return void
     */
    public function setMandante($mandante)
    {
        $this->mandante = $mandante;
    }

    /**
     * Obtiene el valor de la propiedad 'paisId'.
     *
     * @return mixed El valor actual de 'paisId'.
     */
    public function getPaisId()
    {
        return $this->paisId;
    }

    /**
     * Establece el valor de la propiedad 'paisId'.
     *
     * @param mixed $paisId El nuevo valor para 'paisId'.
     * @return void
     */
    public function setPaisId($paisId)
    {
        $this->paisId = $paisId;
    }

    /**
     * Obtiene el valor de la propiedad 'valorActual'.
     *
     * @return mixed El valor actual de 'valorActual'.
     */
    public function getValorActual()
    {
        return $this->valorActual;
    }

    /**
     * Establece el valor de la propiedad 'valorActual'.
     *
     * @param mixed $valorActual El nuevo valor para 'valorActual'.
     * @return void
     */
    public function setValorActual($valorActual)
    {
        $this->valorActual = $valorActual;
    }

    /**
     * Obtiene el valor de la propiedad 'orden'.
     *
     * @return mixed El valor actual de 'orden'.
     */
    public function getOrden()
    {
        return $this->orden;
    }

    /**
     * Establece el valor de la propiedad 'orden'.
     *
     * @param mixed $orden El nuevo valor para la propiedad 'orden'.
     * @return void
     */
    public function setOrden($orden)
    {
        $this->orden = $orden;
    }

    /**
     * Obtiene el valor de la propiedad 'valorBase'.
     *
     * @return mixed El valor actual de 'valorBase'.
     */
    public function getValorBase()
    {
        return $this->valorBase;
    }

    /**
     * Establece el valor de la propiedad 'valorBase'.
     *
     * @param mixed $valorBase El nuevo valor para la propiedad 'valorBase'.
     * @return void
     */
    public function setValorBase($valorBase)
    {
        $this->valorBase = $valorBase;
    }

    /**
     * Obtiene el valor de la propiedad 'valorMaximo'.
     *
     * @return mixed El valor actual de 'valorMaximo'.
     */
    public function getValorMaximo()
    {
        return $this->valorMaximo;
    }

    /**
     * Establece el valor de la propiedad 'valorMaximo'.
     *
     * @param mixed $valorMaximo El nuevo valor para 'valorMaximo'.
     * @return void
     */
    public function setValorMaximo($valorMaximo)
    {
        $this->valorMaximo = $valorMaximo;
    }

    /**
     * Obtiene el valor de la propiedad 'minimoTicket'.
     *
     * @return mixed El valor actual de 'minimoTicket'.
     */
    public function getMinimoTicket()
    {
        return $this->minimoTicket;
    }

    /**
     * Establece el valor de la propiedad 'minimoTicket'.
     *
     * @param mixed $minimoTicket El nuevo valor para la propiedad 'minimoTicket'.
     * @return void
     */
    public function setMinimoTicket($minimoTicket)
    {
        $this->minimoTicket = $minimoTicket;
    }

    /**
     * Obtiene el valor de la propiedad 'maximoTicket'.
     *
     * @return mixed El valor actual de 'maximoTicket'.
     */
    public function getMaximoTicket()
    {
        return $this->maximoTicket;
    }

    /**
     * Establece el valor de la propiedad 'maximoTicket'.
     *
     * @param mixed $maximoTicket El nuevo valor para 'maximoTicket'.
     * @return void
     */
    public function setMaximoTicket($maximoTicket)
    {
        $this->maximoTicket = $maximoTicket;
    }

    /**
     * Obtiene el valor de la propiedad 'usucreaId'.
     *
     * @return mixed El valor actual de 'usucreaId'.
     */
    public function getUsucreaId()
    {
        return $this->usucreaId;
    }

    /**
     * Establece el valor de la propiedad 'usucreaId'.
     *
     * @param mixed $usucreaId El nuevo valor para 'usucreaId'.
     * @return void
     */
    public function setUsucreaId($usucreaId)
    {
        $this->usucreaId = $usucreaId;
    }

    /**
     * Obtiene el valor de la propiedad 'usumodifId'.
     *
     * @return mixed El valor actual de 'usumodifId'.
     */
    public function getUsumodifId()
    {
        return $this->usumodifId;
    }

    /**
     * Establece el valor de la propiedad 'usumodifId'.
     *
     * @param mixed $usumodifId El nuevo valor para 'usumodifId'.
     * @return void
     */
    public function setUsumodifId($usumodifId)
    {
        $this->usumodifId = $usumodifId;
    }

    /**
     * Obtiene el valor de la propiedad 'cantidadApuestamax'.
     *
     * @return mixed El valor actual de 'cantidadApuestamax'.
     */
    public function getCantidadApuestamax()
    {
        return $this->cantidadApuestamax;
    }

    /**
     * Establece el valor de la propiedad 'cantidadApuestamax'.
     *
     * @param mixed $cantidadApuestamax El nuevo valor para 'cantidadApuestamax'.
     * @return void
     */
    public function setCantidadApuestamax($cantidadApuestamax)
    {
        $this->cantidadApuestamax = $cantidadApuestamax;
    }

    /**
     * Obtiene el valor de la propiedad 'porcentajeApuestas'.
     *
     * @return mixed El valor actual de 'porcentajeApuestas'.
     */
    public function getPorcentajeApuestas()
    {
        return $this->porcentajeApuestas;
    }

    /**
     * Establece el valor de la propiedad 'porcentajeApuestas'.
     *
     * @param mixed $porcentajeApuestas El nuevo valor para la propiedad 'porcentajeApuestas'.
     * @return void
     */
    public function setPorcentajeApuestas($porcentajeApuestas)
    {
        $this->porcentajeApuestas = $porcentajeApuestas;
    }

    /**
     * Obtiene el valor de la propiedad 'imagen'.
     *
     * @return mixed El valor actual de 'imagen'.
     */
    public function getImagen()
    {
        return $this->imagen;
    }

    /**
     * Establece el valor de la propiedad 'imagen'.
     *
     * @param mixed $imagen El nuevo valor para la propiedad 'imagen'.
     * @return void
     */
    public function setImagen($imagen)
    {
        $this->imagen = $imagen;
    }

    /**
     * Devuelve el valor de la propiedad 'imagen2'.
     *
     * @return mixed El valor actual de 'imagen2'.
     */
    public function getImagen2()
    {
        return $this->imagen2;
    }

    /**
     * Establece el valor de la propiedad 'imagen2'.
     *
     * @param mixed $imagen2 El nuevo valor para 'imagen2'.
     * @return void
     */
    public function setImagen2($imagen2)
    {
        $this->imagen2 = $imagen2;
    }

    /**
     * Obtiene el valor de la propiedad 'gif'.
     *
     * @return mixed El valor actual de 'gif'.
     */
    public function getGif()
    {
        return $this->gif;
    }

    /**
     * Establece el valor de la propiedad 'gif'.
     *
     * @param mixed $gif El nuevo valor para la propiedad 'gif'.
     * @return void
     */
    public function setGif($gif)
    {
        $this->gif = $gif;
    }

    /**
     * Obtiene el valor de la propiedad 'gif2'.
     *
     * @return mixed El valor actual de 'gif2'.
     */
    public function getGif2()
    {
        return $this->gif2;
    }

    /**
     * Establece el valor de la propiedad 'gif2'.
     *
     * @param mixed $gif2 El nuevo valor a asignar a 'gif2'.
     * @return void
     */
    public function setGif2($gif2)
    {
        $this->gif2 = $gif2;
    }


    /**
     * Obtiene el valor de la propiedad 'reglas'.
     *
     * @return mixed El valor actual de 'reglas'.
     */
    public function getReglas()
    {
        return $this->reglas;
    }

    /**
     * Establece el valor de la propiedad 'reglas'.
     *
     * @param mixed $reglas El nuevo valor para 'reglas'.
     * @return void
     */
    public function setReglas($reglas)
    {
        $this->reglas = $reglas;
    }

    /**
     * Obtiene el valor de la propiedad 'videoMobile'.
     *
     * @return mixed El valor actual de 'videoMobile'.
     */
    public function getVideoMobile()
    {
        return $this->videoMobile;
    }

    /**
     * Establece el valor de la propiedad 'videoMobile'.
     *
     * @param mixed $videoMobile El nuevo valor para 'videoMobile'.
     * @return void
     */
    public function setVideoMobile($videoMobile)
    {
        $this->videoMobile = $videoMobile;
    }

    /**
     * Obtiene el valor de la propiedad 'videoDesktop'.
     *
     * @return mixed El valor actual de 'videoDesktop'.
     */
    public function getVideoDesktop()
    {
        return $this->videoDesktop;
    }

    /**
     * Establece el valor de la propiedad 'videoDesktop'.
     *
     * @param mixed $videoDesktop El valor a asignar a 'videoDesktop'.
     * @return void No retorna ningún valor.
     */
    public function setVideoDesktop($videoDesktop): void
    {
        $this->videoDesktop = $videoDesktop;
    }


    /**
     * Obtiene el valor de la propiedad 'cantidadApuesta'.
     *
     * @return mixed El valor actual de 'cantidadApuesta'.
     */
    public function getCantidadApuesta()
    {
        return $this->cantidadApuesta;
    }

    /**
     * Establece el valor de la propiedad 'cantidadApuesta'.
     *
     * @param mixed $cantidadApuesta El nuevo valor para 'cantidadApuesta'.
     * @return void
     */
    public function setCantidadApuesta($cantidadApuesta)
    {
        $this->cantidadApuesta = $cantidadApuesta;
    }

    /**
     * Obtiene el valor de la propiedad 'notas'.
     *
     * @return mixed El valor actual de 'notas'.
     */
    public function getNotas()
    {
        return $this->notas;
    }

    /**
     * Establece el valor de la propiedad 'notas'.
     *
     * @param mixed $notas El nuevo valor para la propiedad 'notas'.
     * @return void No retorna ningún valor.
     */
    public function setNotas($notas): void
    {
        $this->notas = $notas;
    }

    /**
     * Obtiene el valor de la propiedad 'informacion'.
     *
     * @return mixed El valor actual de 'informacion'.
     */
    public function getInformacion()
    {
        return $this->informacion;
    }

    /**
     * Establece el valor de la propiedad 'informacion'.
     *
     * @param mixed $informacion El nuevo valor para 'informacion'.
     * @return void
     */
    public function setInformacion($informacion): void
    {
        $this->informacion = $informacion;
    }


    /**
     * Obtiene los registros personalizados de 'jackpot' basado en los parámetros proporcionados.
     *
     * @param string $select Especifica las columnas que se desean seleccionar.
     * @param string $sidx Columna por la cual se desea ordenar los datos.
     * @param string $sord Orden de los datos (ascendente o descendente).
     * @param int $start Índice inicial para la paginación.
     * @param int $limit Límite de registros a recuperar.
     * @param array $filters Filtros aplicados para la búsqueda de registros.
     * @param bool $searchOn Indica si la búsqueda está activa.
     * @param array $joins (Opcional) Contiene las uniones adicionales para la consulta.
     *
     * @return mixed Retorna los datos personalizados de 'jackpot' según el criterio de búsqueda.
     *
     * @throws Exception Si no se encuentra ningún resultado que coincida con los parámetros.
     */
    public function getJackpotCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $joins = [])
    {

        $JackpotInternoMySqlDAO = new JackpotInternoMySqlDAO();

        $jackpot = $JackpotInternoMySqlDAO->queryJackpotCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $joins);

        if ($jackpot != null && $jackpot != "") {
            return $jackpot;
        } else {
            throw new Exception("No existe " . get_class($this), "01");
        }


    }

    /**
     * Inserta la instancia actual utilizando la transacción proporcionada.
     *
     * @param mixed $transaction La transacción que se utilizará para la inserción.
     * @return mixed El resultado de la operación de inserción.
     */
    public function insert($transaction)
    {

        $JackpotInternoMySqlDAO = new JackpotInternoMySqlDAO($transaction);
        return $JackpotInternoMySqlDAO->insert($this);

    }

    /**
     * Agrega un registro de log de casino para un jackpot.
     *
     * @param float $valorAcreditado El valor acreditado en el jackpot.
     * @param UsuarioJackpot $UsuarioJackpot El usuario asociado al jackpot.
     * @param TransjuegoLog $TransjuegoLog El log de la transacción del juego.
     * @param Transaction $Transaction La transacción actual.
     * @return int El autoincremental bajo el cual quedó registrado el log
     */
    public function agregarLogCasino(float $valorAcreditado, UsuarioJackpot $UsuarioJackpot, TransjuegoLog $TransjuegoLog, Transaction $Transaction)
    {
        $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO($Transaction);

        $Transjuegoinfo = new TransjuegoInfo();

        $Transjuegoinfo->setTipo('JACKPOT');
        $Transjuegoinfo->setProductoId($TransjuegoLog->productoId);
        $Transjuegoinfo->setTransaccionId($TransjuegoLog->transaccionId);
        $Transjuegoinfo->setTransapiId($TransjuegoLog->transjuegologId);
        $Transjuegoinfo->setDescripcion($UsuarioJackpot->usujackpotId);
        $Transjuegoinfo->setValor($valorAcreditado);
        $Transjuegoinfo->setUsucreaId(0);
        $Transjuegoinfo->setUsumodifId(0);

        return $TransjuegoInfoMySqlDAO->insert($Transjuegoinfo);
    }


    /**
     * Agrega un registro de log para Sportbook relacionado con un Jackpot.
     *
     * @param float $valorAcreditado El valor acreditado en el Jackpot.
     * @param UsuarioJackpot $UsuarioJackpot El usuario asociado al Jackpot.
     * @param ItTicketEnc $ItTicketEnc El ticket asociado al Jackpot.
     * @param Transaction $Transaction La transacción actual.
     * @return mixed El resultado de la inserción del registro en la base de datos.
     */
    public function agregarLogSportbook(float $valorAcreditado, UsuarioJackpot $UsuarioJackpot, ItTicketEnc $ItTicketEnc, Transaction $Transaction)
    {
        $ItTicketEncInfo1MySqlDAO = new ItTicketEncInfo1MySqlDAO($Transaction);

        $ItTicketEncInfo1 = new ItTicketEncInfo1();

        $ItTicketEncInfo1->ticketId = $ItTicketEnc->getTicketId();
        $ItTicketEncInfo1->tipo = 'JACKPOT';
        $ItTicketEncInfo1->valor = $UsuarioJackpot->getUsujackpotId();
        $ItTicketEncInfo1->valor2 = $valorAcreditado;
        $ItTicketEncInfo1->fechaCrea = date('Y-m-d H:i:s');
        $ItTicketEncInfo1->fechaModif = date('Y-m-d H:i:s');

        return $ItTicketEncInfo1MySqlDAO->insert($ItTicketEncInfo1);
    }


    /**
     * Valida las condiciones para determinar si una transacción cumple con los criterios de un Jackpot específico.
     *
     * @param string $tipoTransaccion Tipo de transacción a validar (ej. 'CASINO', 'SPORTBOOK').
     * @param JackpotInterno $JackpotInterno Objeto que representa el Jackpot interno con sus detalles.
     * @param UsuarioMandante $UsuarioMandante Objeto que contiene información del usuario asociado a la transacción.
     * @param TransjuegoLog|null $TransjuegoLog Objeto que representa el log de la transacción en el contexto de juego (opcional).
     * @param ItTicketEnc|null $ItTicketEnc Objeto que representa los tickets en transacciones deportivas (opcional).
     * @param array &$condicionesFallidas Referencia a un array para almacenar las condiciones que no se cumplieron.
     * @param object|null $infoAdicionalUsuario Objeto que contiene información adicional del usuario como parámetros externos para la validación (opcional).
     *
     * @return bool Retorna true si todas las condiciones se cumplen para el Jackpot; de lo contrario, retorna false.
     */
    public function validarCondicionesJackpot(string $tipoTransaccion, JackpotInterno $JackpotInterno, UsuarioMandante $UsuarioMandante, TransjuegoLog $TransjuegoLog = null, ItTicketEnc $ItTicketEnc = null, array &$condicionesFallidas = [], object $infoAdicionalUsuario = null)
    {
        /** Verificando estado del Jackpot */
        if ($JackpotInterno->estado != 'A') {
            $condicionesFallidas[] = 'INACTIVEJACKPOT';
            return false;
        }

        $casinoGroup = self::getCasinoGroup();

        /** Normalizando parámetros entre verticales de la transaccion */
        if (in_array($tipoTransaccion, $casinoGroup)) {
            $betCreationDate = date('Y-m-d H:i:s', strtotime($TransjuegoLog->fechaCrea)); //Fecha creación de la apuesta
            $betAmount = $TransjuegoLog->valor; //Valor de la apuesta
        } elseif ($tipoTransaccion == 'SPORTBOOK') {
            $betCreationDate = date('Y-m-d H:i:s', strtotime($ItTicketEnc->fechaCreaTime ?? $ItTicketEnc->fechaCrea . ' ' . $ItTicketEnc->horaCrea)); //Fecha creación de la apuesta
            $betAmount = $ItTicketEnc->vlrApuesta; //Valor de la apuesta
        }

        if ($JackpotInterno->fechaInicio > $betCreationDate) {
            //$condicionesFallidas[] = 'FAILSFECHAINICIO';
        }
        if ($JackpotInterno->reinicio == 0 && $JackpotInterno->fechaFin < $betCreationDate) {
            //$condicionesFallidas[] = 'FAILSFECHAFIN';
        }
        if (count($condicionesFallidas) > 0) return false;


        /** Validando contingencia abusador de bonos desde información externa del usuario */
        $isBonusAbuser = false;
        if ($infoAdicionalUsuario == null) {
            //En caso de que el objeto infoAdicionalUsuario no sea definido la validación para abusador de bonos se consulta desde el interior de la función
            $UsuarioConfiguracion = new UsuarioConfiguracion();
            $UsuarioConfiguracion = $UsuarioConfiguracion->getUserBondABuser($UsuarioMandante->getUsuarioMandante());

            if (!empty((array)$UsuarioConfiguracion)) {
                $isBonusAbuser = $UsuarioConfiguracion->estado == 'A';
            }
        }
        else $isBonusAbuser = $infoAdicionalUsuario->isBonusAbuser;

        if ($isBonusAbuser) {
            $condicionesFallidas[] = 'BONDABUSER';
            return false;
        }


        /** Cargando detalles del Jackpot */
        $JackpotDetalle = new JackpotDetalle();
        $details = $JackpotDetalle->cargarDetallesJackpot($JackpotInterno->jackpotId);


        /** Verificando vertical de la apuesta respecto vertical admitidas en el jackpot */
        $currentVertical = 'JACKPOTINITVALUE_' . $tipoTransaccion;
        $jackpotVertical = $JackpotDetalle->encontrarDetalle($details, $currentVertical);
        if (count($jackpotVertical) < 1) {
            $condicionesFallidas[] = 'FAILSVERTICAL';
            return false;
        }


        /** Verifcando país del usuario */
        $autorizedCountryGroup = $JackpotDetalle->encontrarDetalle($details, 'CONDPAISUSER');
        $rightCountry = false;
        foreach ($autorizedCountryGroup as $countryDetail) {
            if ($countryDetail->valor == $UsuarioMandante->paisId) {
                $rightCountry = true;
                break;
            }
        }

        if (!$rightCountry) {
            $condicionesFallidas[] = 'CONDPAISUSER';
            return false;
        }


        /** Obtener valor mínimo y máximo aceptados por el Jackpot */
        $currentVerticalMinAmount = 'MINAMOUNT_' . $tipoTransaccion;
        $jackpotMinAmount = $JackpotDetalle->encontrarDetalle($details, $currentVerticalMinAmount)[0];
        if ($jackpotMinAmount->moneda != $UsuarioMandante->moneda || $jackpotMinAmount->valor > $betAmount) {
            $condicionesFallidas[] = $currentVerticalMinAmount;
            return false;
        }

        $currentVerticalMaxAmount = 'MAXAMOUNT_' . $tipoTransaccion;
        $jackpotMaxAmount = $JackpotDetalle->encontrarDetalle($details, $currentVerticalMaxAmount)[0];
        if ($jackpotMaxAmount->moneda != $UsuarioMandante->moneda || $jackpotMaxAmount->valor < $betAmount) {
            $condicionesFallidas[] = $currentVerticalMaxAmount;
            return false;
        }


        /** Verificando condiciones propias de las verticales de casino */
        if (in_array($tipoTransaccion, $casinoGroup)) {
            /** Verificando que apuesta no haya sumando anteriormente al Jackpot */
            $alreadyProcessedTransactionSql = "Select transjuego_info.transjuegoinfo_id
            from transjuego_log
            inner join transjuego_info on (transjuego_info.transapi_id = transjuego_log.transjuegolog_id)
            inner join usuario_jackpot on (transjuego_info.descripcion = usuario_jackpot.usujackpot_id)
            where
            usuario_jackpot.jackpot_id = {$JackpotInterno->jackpotId}
            AND transjuego_info.transapi_id = {$TransjuegoLog->transjuegologId}";
            $alreadyProcessedTransaction = $this->execQuery('', $alreadyProcessedTransactionSql);

            if (count($alreadyProcessedTransaction) > 0) {
                $condicionesFallidas[] = 'ALREADYPROCESSED';
                return false;
            }

            /** Cargando juego vinculado a la transacción */
            $TransaccionJuego = new TransaccionJuego($TransjuegoLog->transjuegoId);
            $ProductoMandante = new ProductoMandante(null, null, $TransaccionJuego->productoId);
            $Producto = new Producto($ProductoMandante->productoId);


            /** Verificando categorías permitidos y excluídas de la apuesta */
            $casinoExcludedCategories = $JackpotDetalle->encontrarDetalle($details, 'EXCLUDED_CONDCATEGORY', "/^{$tipoTransaccion}_\d+/");
            $casinoCategories = $JackpotDetalle->encontrarDetalle($details, 'CONDCATEGORY', "/^{$tipoTransaccion}_\d+/");

            if ((count($casinoCategories) + count($casinoExcludedCategories)) > 0) {
                $productCategories = $ProductoMandante->getCategoriasIds($ProductoMandante);
                $rightCategories = true;

                //Verificando categorías excluídas
                if (count($casinoExcludedCategories) > 0) {
                    $rightCategories = true;

                    foreach ($casinoExcludedCategories as $excludedCategory) {
                        $categoryId = explode('_', $excludedCategory->valor)[1];
                        if (in_array($categoryId, $productCategories)) {
                            $rightCategories = false;
                            $condicionesFallidas[] = $excludedCategory->tipo . " " . $excludedCategory->valor;
                            break;
                        }
                    }
                }

                //Verificando categorías incluidas
                if (count($casinoCategories) > 0 && $rightCategories) {
                    $rightCategories = false;

                    foreach ($casinoCategories as $category) {
                        $categoryId = explode('_', $category->valor)[1];
                        if (in_array($categoryId, $productCategories)) {
                            $rightCategories = true;
                            break;
                        }
                    }

                    if (!$rightCategories) {
                        $condicionesFallidas[] = 'CONDCATEGORY';
                    }
                }

                if (!$rightCategories) return false;
            }


            /** Verificando proveedores permitidos y excluídos de las apuestas */
            $casinoExcludedProviders = $JackpotDetalle->encontrarDetalle($details, 'EXCLUDED_CONDPROVIDER', "/^{$tipoTransaccion}_\d+/");
            $casinoProviders = $JackpotDetalle->encontrarDetalle($details, 'CONDPROVIDER', "/^{$tipoTransaccion}_\d+/");

            if ((count($casinoProviders) + count($casinoExcludedProviders)) > 0) {
                $productProviderId = $Producto->subproveedorId;
                $rightProvider = true;

                //Verificando proveedores excluídos
                if (count($casinoExcludedProviders) > 0) {
                    $rightProvider = true;

                    foreach ($casinoExcludedProviders as $excludedProvider) {
                        $providerId = explode('_', $excludedProvider->valor)[1];
                        if ($providerId == $productProviderId) {
                            $rightProvider = false;
                            $condicionesFallidas[] = $excludedProvider->tipo . " " . $excludedProvider->valor;
                            break;
                        }
                    }
                }

                //Verificando proveedores incluídos
                if (count($casinoProviders) > 0 && $rightProvider) {
                    $rightProvider = false;

                    foreach ($casinoProviders as $provider) {
                        $providerId = explode('_', $provider->valor)[1];
                        if ($providerId == $productProviderId) {
                            $rightProvider = true;
                            break;
                        }
                    }

                    if (!$rightProvider) {
                        $condicionesFallidas[] = 'CONDPROVIDER';
                    }
                }

                if (!$rightProvider) return false;
            }


            /** Verificando juegos permitidos y excluídos de las apuestas */
            $casinoExcludedGames = $JackpotDetalle->encontrarDetalle($details, 'EXCLUDED_CONDGAME', "/^{$tipoTransaccion}_\d+/");
            $casinoGames = $JackpotDetalle->encontrarDetalle($details, 'CONDGAME', "/^{$tipoTransaccion}_\d+/");

            if ((count($casinoGames) + count($casinoExcludedGames)) > 0) {
                $productId = $ProductoMandante->prodmandanteId;
                $rightProduct = true;

                //Verificando juegos excluídos
                if (count($casinoExcludedGames) > 0) {
                    $rightProduct = true;

                    foreach ($casinoExcludedGames as $excludedGame) {
                        $gameId = explode('_', $excludedGame->valor)[1];

                        if ($gameId == $productId) {
                            $rightProduct = false;
                            $condicionesFallidas[] = $excludedGame->tipo . " " . $excludedGame->valor;
                            break;
                        }
                    }
                }

                //Verificando juegos incluídos
                if (count($casinoGames) > 0 && $rightProduct) {
                    $rightProduct = false;

                    foreach ($casinoGames as $game) {
                        $gameId = explode('_', $game->valor)[1];
                        if ($gameId == $productId) {
                            $rightProduct = true;
                            break;
                        }
                    }

                    if (!$rightProduct) {
                        $condicionesFallidas[] = 'CONDGAME';
                    }
                }

                if (!$rightProduct) return false;
            }
        } elseif ($tipoTransaccion == 'SPORTBOOK') {
            /** Verificando que apuestas no haya sumado anteriormente al jackpot */
            $alreadyProcessedTransactionSql = "select it_ticket_enc_info1.it_ticket2_id
            from it_ticket_enc_info1
            where it_ticket_enc_info1.valor = {$JackpotInterno->jackpotId}
            AND    it_ticket_enc_info1.tipo = 'JACKPOT'
            and it_ticket_enc_info1.ticket_id = {$ItTicketEnc->ticketId}";
            $alreadyProcessedTransaction = $this->execQuery('', $alreadyProcessedTransactionSql);

            if (count($alreadyProcessedTransaction) > 0) {
                $condicionesFallidas[] = 'ALREADYPROCESSED';
                return false;
            }

            /** Consultando deportes, ligas, eventos, deporteMercado incluídos y excluídos del Jackpot */
            $queriesToItTicketDet = 0;

            //Deportes
            $jackpotSports = $JackpotDetalle->encontrarDetalle($details, 'ITAINMENT1');
            $jackpotExcludedSports = $JackpotDetalle->encontrarDetalle($details, 'EXCLUDED_ITAINMENT1');
            $queriesToItTicketDet += (count($jackpotSports) + count($jackpotExcludedSports));

            //Ligas
            $jackpotLeagues = $JackpotDetalle->encontrarDetalle($details, 'ITAINMENT3');
            $jackpotExcludedLeagues = $JackpotDetalle->encontrarDetalle($details, 'EXCLUDED_ITAINMENT3');
            $queriesToItTicketDet += (count($jackpotLeagues) + count($jackpotExcludedLeagues));

            //Eventos
            $jackpotEvents = $JackpotDetalle->encontrarDetalle($details, 'ITAINMENT4');
            $jackpotExcludedEvents = $JackpotDetalle->encontrarDetalle($details, 'EXCLUDED_ITAINMENT4');
            $queriesToItTicketDet += (count($jackpotEvents) + count($jackpotExcludedEvents));

            //deporteMercados
            $jackpotMarkets = $JackpotDetalle->encontrarDetalle($details, 'ITAINMENT5');
            $jackpotExcludedMarkets = $JackpotDetalle->encontrarDetalle($details, 'EXCLUDED_ITAINMENT5');
            $queriesToItTicketDet += (count($jackpotMarkets) + count($jackpotExcludedMarkets));


            /** Solicitando deportes, ligas, eventos, deporteMercado del ticket */
            if ($queriesToItTicketDet > 0) {
                $betLinesSql = "select te.vlr_apuesta,td.ticket_id,td.apuesta,td.agrupador,td.logro,td.opcion,td.matchid,td.agrupador_id,td.sportid,td.ligaid,td.fecha_evento
                            ,td.hora_evento,te.usuario_id,te.bet_mode betmode from it_ticket_det td INNER JOIN it_ticket_enc te ON (td.ticket_id = te.ticket_id ) where  te.ticket_id='" . $ItTicketEnc->ticketId . "' ";
                $betLines = $this->execQuery('', $betLinesSql);

                $betSports = [];
                $betLeagues = [];
                $betEvents = [];
                $betMarkets = [];
                foreach ($betLines as $line) {
                    $betSports[] = $line->{'td.sportid'};
                    $betLeagues[] = $line->{'td.ligaid'};
                    $betEvents[] = $line->{'td.matchid'};
                    $betMarkets[] = $line->{'td.sportid'} . 'M' . $line->{'td.agrupador_id'};
                }
            }


            /** Contrastando deportes de la apuesta vs el jackpot */
            if ((count($jackpotSports) + count($jackpotExcludedSports)) > 0) {
                $rightSports = true;

                //Verificando deportes excluídos
                if (count($jackpotExcludedSports) > 0) {
                    $rightSports = true;

                    foreach ($jackpotExcludedSports as $excludedSport) {
                        if (in_array($excludedSport->valor, $betSports)) {
                            $rightSports = false;
                            $condicionesFallidas[] = $excludedSport->tipo . " " . $excludedSport->valor;
                            break;
                        }
                    }
                }

                //Verificando deportes incluídos
                if (count($jackpotSports) > 0 && $rightSports) {
                    $rightSports = false;

                    foreach ($jackpotSports as $jackpotSport) {
                        if (in_array($jackpotSport->valor, $betSports)) {
                            $rightSports = true;
                            break;
                        }
                    }

                    if (!$rightSports) {
                        $condicionesFallidas[] = 'ITAINMENT1';
                    }
                }

                if (!$rightSports) return false;
            }


            /** Contrastando ligas de la apuesta vs el Jackpot */
            if ((count($jackpotLeagues) + count($jackpotExcludedLeagues)) > 0) {
                $rightLeagues = true;

                //Verificando ligas excluídas
                if (count($jackpotExcludedLeagues) > 0) {
                    $rightLeagues = true;

                    foreach ($jackpotExcludedLeagues as $excludedLeague) {
                        if (in_array($excludedLeague->valor, $betLeagues)) {
                            $rightLeagues = false;
                            $condicionesFallidas[] = $excludedLeague->tipo . " " . $excludedLeague->valor;
                            break;
                        }
                    }
                }

                //Verificando ligas incluídas
                if (count($jackpotLeagues) > 0 && $rightLeagues) {
                    $rightLeagues = false;

                    foreach ($jackpotLeagues as $league) {
                        if (in_array($league->valor, $betLeagues)) {
                            $rightLeagues = true;
                            break;
                        }
                    }

                    if (!$rightLeagues) {
                        $condicionesFallidas[] = 'ITAINMENT3';
                    }
                }

                if (!$rightLeagues) return false;
            }

            //Contrastando eventos de las apuestas VS el Jackpot
            if ((count($jackpotEvents) + count($jackpotExcludedEvents)) > 0) {
                $rightEvents = true;

                //Verificando eventos excluídos
                if (count($jackpotExcludedEvents) > 0) {
                    $rightEvents = true;

                    foreach ($jackpotExcludedEvents as $excludedEvent) {
                        if (in_array($excludedEvent->valor, $betEvents)) {
                            $rightEvents = false;
                            $condicionesFallidas[] = $excludedEvent->tipo . " " . $excludedEvent->valor;
                            break;
                        }
                    }
                }

                //Verificando eventos incluídos
                if (count($jackpotEvents) > 0 && $rightEvents) {
                    $rightEvents = false;

                    foreach ($jackpotEvents as $event) {
                        if (in_array($event->valor, $betEvents)) {
                            $rightEvents = true;
                            break;
                        }
                    }

                    if (!$rightEvents) {
                        $condicionesFallidas[] = 'ITAINMENT4';
                    };
                }

                if (!$rightEvents) return false;
            }

            //Contrastando deportesMercado de la apuesta vs Jackpot
            if ((count($jackpotMarkets) + count($jackpotExcludedMarkets)) > 0) {
                $rightMarkets = true;

                //Verificando deportesMercado excluídos
                if (count($jackpotExcludedMarkets) > 0) {
                    $rightMarkets = true;

                    foreach ($jackpotExcludedMarkets as $excludedMarket) {
                        if (in_array($excludedMarket->valor, $betMarkets)) {
                            $rightMarkets = false;
                            $condicionesFallidas[] = $excludedMarket->tipo . " " . $excludedMarket->valor;
                            break;
                        }
                    }
                }

                //Verificando deportesMercado incluídos
                if (count($jackpotMarkets) > 0 && $rightMarkets) {
                    $rightMarkets = false;

                    foreach ($jackpotMarkets as $market) {
                        if (in_array($market->valor, $betMarkets)) {
                            $rightMarkets = true;
                            break;
                        }
                    }

                    if (!$rightMarkets) {
                        $condicionesFallidas[] = 'ITAINMENT5';
                    }
                }

                if (!$rightMarkets) return false;
            }
        }

        return true;
    }


    /** Esta función suma la apuesta en el jackpot, el usuarioJackpot y el saco de usuariojackpot_ganador y hace commit de la transacción
     * @param string $tipoTransaccion Tipo de transacción a validar
     * @param string $logId Identificador que indetificará a la transacción dentro del pozo jackpot
     * @param JackpotInterno $JackpotInterno Jackpot interno donde se acreditará la apuesta
     * @param UsuarioJackpot $UsuarioJackpot Usuario asociado al jackpot
     * @param float $valorSumarAlPozo Valor a sumar al pozo del jackpot
     * @param float $valorApostado Valor apostado por el usuario
     * @param Transaction $Transaction Transacción actual
     * @param bool $jackpotWinner Referencia que indica si el jackpot fue ganado
     * @return bool Retorna true si la transacción fue exitosa, false en caso contrario
    */
    private function acreditarApuesta(string $tipoTransaccion, string $logId, JackpotInterno $JackpotInterno, UsuarioJackpot $UsuarioJackpot, float $valorSumarAlPozo, float $valorApostado, Transaction $Transaction, bool &$jackpotWinner)
    {
        //Definiendo saldo objetivo de la apuesta
        $targetIncome = match ($tipoTransaccion) {
            'CASINO' => 'INCOME_CASINO',
            'LIVECASINO' => 'INCOME_LIVECASINO',
            'VIRTUAL' => 'INCOME_VIRTUAL',
            'SPORTBOOK' => 'INCOME_SPORTBOOK'
        };

        $sqlAccreditBet = "UPDATE usuario_jackpot
                                SET
                                usuario_jackpot.valor               = usuario_jackpot.valor + {$valorSumarAlPozo},
                                usuario_jackpot.apostado            = usuario_jackpot.apostado + {$valorApostado}

                                WHERE  
                                    usuario_jackpot.usujackpot_id = {$UsuarioJackpot->usujackpotId}
                                ";
        $sqlResult = $this->execUpdate($Transaction, $sqlAccreditBet);
        $Transaction->commit();

        /** Comenzando proceso de acreditación */
        $UsuarioJackpotMySqlDAO2 = new UsuarioJackpotMySqlDAO();
        $Transaction= $UsuarioJackpotMySqlDAO2->getTransaction();


        $sqlAccreditBet = "UPDATE usuariojackpot_ganador
                                SET
                                usuariojackpot_ganador.valor_premio = usuariojackpot_ganador.valor_premio + {$valorSumarAlPozo}

                                WHERE usuariojackpot_ganador.jackpot_id = {$JackpotInterno->jackpotId}
                                AND usuariojackpot_ganador.tipo = '{$targetIncome}'";
        $sqlResult3 = $this->execUpdate($Transaction, $sqlAccreditBet);
        $Transaction->commit();

        return $sqlResult > 0;

        //Obteniendo apuesta ganadora
        $JackpotDetalle = new JackpotDetalle();

        $winnerBet = null;
        if ($JackpotInterno->reinicio == 0 && date('Y-m-d', strtotime($JackpotInterno->fechaFin)) == date('Y-m-d')) {
            $winnerBet = $JackpotDetalle->cargarDetallesJackpot($JackpotInterno->jackpotId, 'FALLCRITERIA_LASTDAYWINNERBET')[0];
        }
        if (empty((array)$winnerBet)) {
            $winnerBet = $JackpotDetalle->cargarDetallesJackpot($JackpotInterno->jackpotId, 'FALLCRITERIA_WINNERBET')[0];
        }

        $winnerBet = $winnerBet->valor;
        if (!is_numeric(intval($winnerBet)) || $winnerBet <= 0) throw new Exception('Apuesta de caída configurada para el jackpot es inválida', 300043);



        $sqlAccreditBet = "UPDATE jackpot_interno
                                SET
                                jackpot_interno.estado              = CASE
                                                                          WHEN (jackpot_interno.cantidad_apuesta + 1) = {$winnerBet} THEN 'G'
                                                                          ELSE jackpot_interno.estado END,
                                jackpot_interno.cantidad_apuesta = jackpot_interno.cantidad_apuesta + 1,
                                jackpot_interno.valor_actual = jackpot_interno.valor_actual + {$valorSumarAlPozo},
                                jackpot_interno.notas = '{$logId}'
                                WHERE 
                                 
                                    jackpot_interno.jackpot_id = {$JackpotInterno->jackpotId}
                                AND jackpot_interno.estado = 'A'

                                ";
        $sqlResult2 = $this->execUpdate($Transaction, $sqlAccreditBet);
        $Transaction->commit();

        //En caso de no actualizarse nada se retorna false -- La transacción falló
        if ($sqlResult == 0) return false;

        $JackpotInternoVerificacion = new JackpotInterno($JackpotInterno->jackpotId);
        if ($JackpotInternoVerificacion->estado == 'G' && $JackpotInternoVerificacion->notas == $logId && $JackpotInternoVerificacion->cantidadApuesta == $winnerBet) {
            $jackpotWinner = true;
        } else $jackpotWinner = false;

        return $sqlResult > 0;
    }

    /**
     * Paga el Jackpot al usuario ganador.
     *
     * @param int $jackpotId ID del Jackpot.
     * @param int $usujackpotId ID del usuario ganador del Jackpot.
     * @param Transaction $Transaction Objeto de transacción para la operación.
     *
     * @throws Exception Si el Jackpot no está en estado 'G'.
     * @throws Exception Si el usuario ganador del Jackpot no está identificado.
     * @throws Exception Si el saldo objetivo para acreditar el Jackpot es desconocido.
     * @throws Exception Si no es posible verificar al ganador del Jackpot.
     * @throws Exception Si el usuario no coincide con el ganador del Jackpot.
     * @throws Exception Si la vertical no es reconocida para acreditar el Jackpot.
     * @throws Exception Si el valor consolidado del Jackpot presenta diferencias.
     *
     * @return bool Retorna true si el pago del Jackpot fue exitoso.
     */
    public function pagarJackpot(int $jackpotId, int $usujackpotId, Transaction $Transaction)
    {
        /** Validando que se cumplan condiciones para pago del Jackpot*/
        $JackpotInterno = new JackpotInterno($jackpotId);
        $UsuarioJackpot = new UsuarioJackpot($usujackpotId);
        $Usuario = new Usuario($UsuarioJackpot->usuarioId);

        if ($JackpotInterno->estado != 'G') throw new Exception('Solo puede pagar Jackpots en estado G', 300046);
        if (empty($UsuarioJackpot->usuarioId)) throw new Exception('Usuario ganador del jackpot no identificado', 300047);

        /** Solicitando saldo al cual se acreditará el dinero al usuario */
        $possibleUserBalanceDestiny = [
            1 => 'RECARGA',
            2 => 'RETIRO'
        ];
        $JackpotDetalle = new JackpotDetalle();

        /**
         * Propósito: la funcion cargarDetallesJackpot con tipos EMAILHTML,EMAILSUBJECT permite traer los detalles del correo que se va a enviar al ganador de un jackpot
         *    - $ValorcontenidoHtml: contenido del correo que le llegara al usuario al momento de ganar el Jackpot
         *    - $SubjectHtml: titulo o asunto del correo que le llegara al usuario al momento de ganar el Jackpot
         */
        try {
            $ContenidoHtml = $JackpotDetalle->cargarDetallesJackpot($JackpotInterno->jackpotId, 'EMAILHTML')[0];
            $ValorcontenidoHtml = $ContenidoHtml->valor;

            if ($ValorcontenidoHtml != "") {
                $SubjectHtml = $JackpotDetalle->cargarDetallesJackpot($JackpotInterno->jackpotId, 'EMAILSUBJECT')[0];
                $SubjectHtml = $SubjectHtml->valor;
            }


            $ConfigurationEnviroment = new ConfigurationEnvironment();
            if ($ValorcontenidoHtml  != "" && $SubjectHtml  != "") {
                $envio = $ConfigurationEnviroment->EnviarCorreoVersion3($Usuario->login, 'noreply@doradobet.com', 'Doradobet', $SubjectHtml, 'mail_registro.php', $SubjectHtml, $ValorcontenidoHtml, "", "", "", $Usuario->mandante);
            }
        } catch (Exception $e) {
            syslog(LOG_ERR, " ERRORENVIOEMAILJACKPOT : " . $e->getCode() . " - " . $e->getMessage() . "Línea: " . $e->getLine() . "Archivo : " . $e->getFile());
        }

        /**
         * Propósito: la funcion EnviarCorreoVersion3 permite enviar el asunto y contenido del correo electronico configurado al ganador del jackpot
         * Descripción de variables:
         *    - envio: en esta variable se instancia la funcion enviar correo de la clase configurationEnvironment y se envia el correo con los parametros necesarios
         */

        // enviar mensaje tipo inbox

        $Inbox = $JackpotDetalle->cargarDetallesJackpot($JackpotInterno->jackpotId, 'INBOXHTML')[0];
        $valorInbox = $Inbox->valor;

        $InboxUrl = $JackpotDetalle->cargarDetallesJackpot($JackpotInterno->jackpotId, 'INBOXURL')[0];
        $valorInboxUrl = $InboxUrl->valor;

        /**
         * Propósito: la funcion cargarDetallesJackpot con tipos INBOXHTML,INBOXURL permite traer los detalles del mensaje de tipo inbox que le va a llegar al ganador del jackpot
         *    - $valorInbox: contenido del inbox que le llegara al usuario a la bandeja de entrada;
         *    - $valorInboxUrl: titulo del inbox que le llegara al usuario a la bandeja de entrada;
         */


        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);

        $usutoId = $UsuarioMandante->usumandanteId;



        $proveedorId = '0';
        if ($_SESSION["Global"] == 'N') {
            $proveedorId = $_SESSION["mandante"];
        }

        //comentar desde aca


        $fecha_anterior = new \DateTime();
        $fecha_anterior->setTime(0, 0);
        $fecha_anterior->modify('-7 days');
        $fecha_anterior->format('Y-m-d');

        // Obtener la fecha de hoy en formato 'Y-m-d'
        $fechaProxima = new \DateTime();
        $fechaProxima->setTime(0, 0);
        $fechaProxima->modify('+7 days');
        $fechaProxima->format('Y-m-d H:i:s'); // Ejemplo de salida: 2024-11-14


        /**
         * Propósito:  clase UsuarioMensaje que permite guardar en la base de datos una campañana con un mensaje que se enviara al usuario
         * Descripción de variables:
         *    $UsuarioMensajecampana->body = $valorInbox; contiene el texto que le llegara como inbox
         */
        if ($valorInbox != "" and $valorInboxUrl != "" and $valorInbox != "NULL" and $valorInboxUrl != "NULL") {

            $fechaProxima = new \DateTime();
            $fechaProxima->setTime(0, 0);
            $fechaProxima->modify('+7 days');
            $fechaProxima->format('Y-m-d H:i:s');

        $UsuarioMensajecampana = new UsuarioMensajecampana();
            $UsuarioMensajecampana->usufromId = 0;
            $UsuarioMensajecampana->usutoId = "-1";
            $UsuarioMensajecampana->isRead = 0;
            $UsuarioMensajecampana->body = $valorInbox;
            $UsuarioMensajecampana->msubject = "";
            $UsuarioMensajecampana->parentId = 0;
            $UsuarioMensajecampana->proveedorId = $proveedorId;
            $UsuarioMensajecampana->tipo = "MENSAJE";
            $UsuarioMensajecampana->estado = "A";
            $UsuarioMensajecampana->paisId = $Usuario->paisId;
            $UsuarioMensajecampana->fechaExpiracion = $fechaProxima->format('Y-m-d H:i:s');
            $UsuarioMensajecampana->usucreaId = $_SESSION["usuario"];
            $UsuarioMensajecampana->usumodifId = $_SESSION["usuario"];;
            $UsuarioMensajecampana->nombre = "JACKPOT INBOX";
            $UsuarioMensajecampana->descripcion = "Jackpot Ganador";
            $UsuarioMensajecampana->t_value = "";
            $UsuarioMensajecampana->mandante = $Usuario->mandante;
            $UsuarioMensajecampana->fechaEnvio = $fecha_anterior->format('Y-m-d H:i:s');
            //$UsuarioMensajecampana->estado = 'A';

            $Title = "Jackpot Ganador";

            $UsuarioMensajecampanaMySqlDAO = new UsuarioMensajecampanaMySqlDAO();

            $UsuarioMensajecampanaMySqlDAO->insert($UsuarioMensajecampana);
            $UsuarioMensajecampanaMySqlDAO->getTransaction()->commit();

            $usumencampanaId = $UsuarioMensajecampana->usumencampanaId; /*descripcion de la variable: contiene el id de la campaña del mensaje*/



            /* proposito de la funcion o clase: clase UsuarioMensaje contiene todos los detalles que son necesarios para mostrarle al usuario el mensaje como inbox*/

            /**
             * Propósito: Descripción: clase UsuarioMensaje contiene todos los detalles que son necesarios para mostrarle al usuario el mensaje como inbox
             * Descripción de variables:
             *    - $usutoId: esta variable permite que el sistema tenga en cuenta que el mensaje aun no se ha leido
             *    - $valorInboxUrl: contiene el titulo o el asunto del inbox
             *    - $valorInbox: contiene el mensaje del inbox
             */


            $UsuarioMensaje = new UsuarioMensaje();
            $UsuarioMensaje->usufromId = 0;
            $UsuarioMensaje->usutoId = $usutoId;
            $UsuarioMensaje->isRead = 0;
            $UsuarioMensaje->body = $valorInbox;
            $UsuarioMensaje->msubject = $valorInboxUrl;
            $UsuarioMensaje->parentId = 0;
            $UsuarioMensaje->proveedorId = $Usuario->mandante;
            $UsuarioMensaje->tipo = "MENSAJE";
            $UsuarioMensaje->paisId = $Usuario->paisId;
            $UsuarioMensaje->fechaExpiracion = $fechaProxima->format('Y-m-d H:i:s');
            $UsuarioMensaje->usumencampanaId = $usumencampanaId;

            $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
            $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
            $UsuarioMensajeMySqlDAO->getTransaction()->commit();

            $UsuarioMensajecampana->usumensajeId = $UsuarioMensaje->usumensajeId;

            $UsuarioMensajecampanaMySqlDAO = new UsuarioMensajecampanaMySqlDAO();
            $UsuarioMensajecampanaMySqlDAO->update($UsuarioMensajecampana);
            $UsuarioMensajecampanaMySqlDAO->getTransaction()->commit();
            // hasta aca esta la funcion de envio de inbox con Jackpot

        }

        /**
         * Propósito: la funcion cargarDetallesJackpot con tipos POPUPTEXT,POPUPURL permite traer los detalles del mensaje de tipo pop up que le va a llegar al ganador del jackpot
         *    - POPUPTEXT: contenido del pop up que le saldra al usuario al momento de ganarse un jackpot
         *    - POPUPURL: url del pop up que le saldra al usuario al momento de ganarse un jackpot
         */


        $valorPopUP = $JackpotDetalle->cargarDetallesJackpot($JackpotInterno->jackpotId, 'POPUPTEXT')[0];
        $valorPopUP = $valorPopUP->valor;

        $valorPopUPUrl = $JackpotDetalle->cargarDetallesJackpot($JackpotInterno->jackpotId, "POPUPURL")[0];
        $valorPopUPUrl = $valorPopUPUrl->valor;

        /**
         * Propósito: clase usuario mensaje permite crear una campaña y asociarla a un mensaje
         * Descripción de variables:
         *    - $valorPopUP: contenido del pop up
         *    - $valorPopUPUrl: url del pop up que le saldra al ganador
         *    - isRead: esta variable permite que se conozca si el mensaje ha sido leido
         */

        if ($valorPopUP != "" and $valorPopUPUrl) {


            // SECCION DE POPUP
            $UsuarioMensajecampana = new UsuarioMensajecampana();
            $UsuarioMensajecampana->usufromId = 0;
            $UsuarioMensajecampana->usutoId = "-1";
            $UsuarioMensajecampana->isRead = 0;
            $UsuarioMensajecampana->body = $valorPopUP;
            $UsuarioMensajecampana->msubject = $valorPopUPUrl;
            $UsuarioMensajecampana->parentId = 0;
            $UsuarioMensajecampana->proveedorId = $proveedorId;
            $UsuarioMensajecampana->tipo = "MESSAGEINV";
            $UsuarioMensajecampana->estado = "A";
            $UsuarioMensajecampana->paisId = $Usuario->paisId;
            $UsuarioMensajecampana->fechaExpiracion = $fechaProxima->format('Y-m-d H:i:s');
            $UsuarioMensajecampana->usucreaId = $_SESSION["usuario"];
            $UsuarioMensajecampana->usumodifId = $_SESSION["usuario"];;
            $UsuarioMensajecampana->nombre = "JACKPOT POPUP";
            $UsuarioMensajecampana->descripcion = "Jackpot Ganador";
            $UsuarioMensajecampana->t_value = $valorPopUPUrl;
            $UsuarioMensajecampana->mandante = $Usuario->mandante;
            $UsuarioMensajecampana->fechaEnvio = $fecha_anterior->format("Y-m-d H:i:s");
            //$UsuarioMensajecampana->estado = 'A';

            $UsuarioMensajecampanaMySqlDAO = new UsuarioMensajecampanaMySqlDAO();

            $UsuarioMensajecampanaMySqlDAO->insert($UsuarioMensajecampana);
            $UsuarioMensajecampanaMySqlDAO->getTransaction()->commit();


            $usumencampanaId = $UsuarioMensajecampana->usumencampanaId;
            /**
             * Descripción de variables:
             *    - $usumencampanaId: contiene el id de la campaña que fue registrada para el pop up
             */


            /**
             * Propósito: Descripción: clase UsuarioMensaje contiene todos los detalles que son necesarios para mostrarle al usuario el mensaje como pop up
             * Descripción de variables:
             *    - $usutoId: esta variable permite que el sistema tenga en cuenta que el mensaje aun no se ha leido
             *    - $valorPopUP: contiene el titulo o el asunto del inbox
             *    - $valorPopUPUrl: contiene el mensaje del inbox
             */


            $UsuarioMensaje = new UsuarioMensaje();
            $UsuarioMensaje->usufromId = 0;
            $UsuarioMensaje->usutoId = $usutoId;
            $UsuarioMensaje->isRead = 0;
            $UsuarioMensaje->body = $valorPopUP;
            $UsuarioMensaje->msubject = $valorPopUPUrl;
            $UsuarioMensaje->parentId = 0;
            $UsuarioMensaje->proveedorId = $Usuario->mandante;
            $UsuarioMensaje->tipo = "MESSAGEINV";
            $UsuarioMensaje->paisId = $Usuario->paisId;
            $UsuarioMensaje->fechaExpiracion = $fechaProxima->format('Y-m-d H:i:s');
            $UsuarioMensaje->usumencampanaId = $usumencampanaId;

            $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
            $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);

            $UsuarioMensajeMySqlDAO->getTransaction()->commit();

            $UsuarioMensajecampana->usumensajeId = $UsuarioMensaje->usumensajeId;

            $UsuarioMensajecampanaMySqlDAO = new UsuarioMensajecampanaMySqlDAO();
            $UsuarioMensajecampanaMySqlDAO->update($UsuarioMensajecampana);
            $UsuarioMensajecampanaMySqlDAO->getTransaction()->commit();


            $ParentId = $UsuarioMensaje->usumensajeId;
        }

        //hasta aca termina lo de pop up

        //envio de mensaje de texto


        $ValorTexto = $JackpotDetalle->cargarDetallesJackpot($JackpotInterno->jackpotId, 'SMSTEXT')[0];
        $ValorTexto = $ValorTexto->valor;

        /**
         * Propósito: Descripción: clase cargarDetallesJackpot contiene todos los detalles que son necesarios para mostrarle al usuario el mensaje de texto
         * Descripción de variables:
         *    - $usutoId: esta variable permite que el sistema tenga en cuenta que el mensaje aun no se ha leido
         *    - $ValorTexto: contiene el mensaje que le llegara al celular
         */

        $fechaProxima = new \DateTime();
        $fechaProxima->setTime(0, 0);
        $fechaProxima->modify('+7 days');
        $fechaProxima->format('Y-m-d'); //

        if ($ValorTexto != "" and $ValorTexto != "NULL") {

            $UsuarioMensajecampana = new UsuarioMensajecampana();
            $UsuarioMensajecampana->usufromId = 0;
            $UsuarioMensajecampana->usutoId = "-1";
            $UsuarioMensajecampana->isRead = 0;
            $UsuarioMensajecampana->body = $ValorTexto;
            $UsuarioMensajecampana->msubject = "";
            $UsuarioMensajecampana->parentId = 0;
            $UsuarioMensajecampana->proveedorId = $proveedorId;
            $UsuarioMensajecampana->tipo = "SMS";
            $UsuarioMensajecampana->estado = "A";
            $UsuarioMensajecampana->paisId = $Usuario->paisId;
            $UsuarioMensajecampana->fechaExpiracion = $fechaProxima->format('Y-m-d H:i:s');
            $UsuarioMensajecampana->usucreaId = $_SESSION["usuario"];
            $UsuarioMensajecampana->usumodifId = $_SESSION["usuario"];
            $UsuarioMensajecampana->nombre = "JACKPOT SMS";
            $UsuarioMensajecampana->descripcion = "Jackpot Ganador";
            $UsuarioMensajecampana->t_value = $ValorTexto;
            $UsuarioMensajecampana->mandante = $Usuario->mandante;
            $UsuarioMensajecampana->fechaEnvio = $fecha_anterior->format("Y-m-d H:i:s");
            //$UsuarioMensajecampana->estado = 'A';

            $UsuarioMensajecampanaMySqlDAO = new UsuarioMensajecampanaMySqlDAO();

            $UsuarioMensajecampanaMySqlDAO->insert($UsuarioMensajecampana);
            $UsuarioMensajecampanaMySqlDAO->getTransaction()->commit();


            $usumencampanaId = $UsuarioMensajecampana->usumencampanaId;


            $UsuarioMensaje = new UsuarioMensaje();
            $UsuarioMensaje->usufromId = 0;
            $UsuarioMensaje->usutoId = $UsuarioMandante->usumandanteId;
            $UsuarioMensaje->isRead = 0;
            $UsuarioMensaje->body = $ValorTexto;
            $UsuarioMensaje->msubject = 'Mensaje';
            $UsuarioMensaje->tipo = "MENSAJE";
            $UsuarioMensaje->parentId = 0;
            $UsuarioMensaje->proveedorId = $Usuario->mandante;
            $UsuarioMensaje->tipo = "SMS";
            $UsuarioMensaje->usumencampanaId = $usumencampanaId;

            $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
            $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
            $UsuarioMensajeMySqlDAO->getTransaction()->commit();

            $UsuarioMensajecampana->usumensajeId = $UsuarioMensaje->usumensajeId;

            $UsuarioMensajecampanaMySqlDAO = new UsuarioMensajecampanaMySqlDAO();
            $UsuarioMensajecampanaMySqlDAO->update($UsuarioMensajecampana);
            $UsuarioMensajecampanaMySqlDAO->getTransaction()->commit();

            $Registro = new Registro("", $Usuario->usuarioId);

            $envio = $ConfigurationEnviroment->EnviarMensajeTexto($ValorTexto, '', $Registro->celular, 0, $UsuarioMandante);
        }

        /**
         * Propósito: funcion enviar MensajeTexto esta funcion permite crear una campaña y usar diferentes proveedores de mensajeria que estan configurados desde partner ajustes
         * Descripción de variables:
         *    - variable1: la variable $envio lo que esta haciendo es instanciar de la clase configuration enviroment esta funcion
         */
        //--------------------------------------------------------

        $targetUserBalance = $JackpotDetalle->cargarDetallesJackpot($JackpotInterno->jackpotId, 'TIPOSALDO')[0];
        $targetUserBalance = $targetUserBalance->valor;

        if (!empty($possibleUserBalanceDestiny[$targetUserBalance])) {
            $targetUserBalance = $possibleUserBalanceDestiny[$targetUserBalance];
        } else throw new Exception('Saldo objetivo para acreditar jackpot es desconocido', 300048);


        /** Ratificando inscripción ganadora */
        $winnerLogId = explode('_', $JackpotInterno->notas);
        if ($winnerLogId[0] == 'S') {
            try {
                $ItTicketEncInfo1 = new ItTicketEncInfo1($winnerLogId[1]);
            } catch (Exception $e) {
                if ($e->getCode() != 300044) throw $e;
                throw new Exception('No fue posible verificar al ganador del jackpot', 300049);
            }

            $winnerUsuJackpotId = $ItTicketEncInfo1->valor;
        } elseif ($winnerLogId[0] == 'C') {
            try {
                $TransjuegoInfo = new TransjuegoInfo($winnerLogId[1]);
            } catch (Exception $e) {
                if ($e->getCode() != 28) throw $e;
                throw new Exception('No fue posible verificar al ganador del jackpot', 300049);
            }

            $winnerUsuJackpotId = $TransjuegoInfo->descripcion;
        }

        if ($winnerUsuJackpotId != $usujackpotId) throw new Exception('Usuario no coincide con ganador del jackpot', 300050);


        /** Pagando Jackpot */
        $JackpotInternoMySqlDAO = new JackpotInternoMySqlDAO($Transaction);
        $UsuarioJackpotMySqlDAO = new UsuarioJackpotMySqlDAO($Transaction);
        $UsuarioJackpotGanadorMySqlDAO = new UsuarioJackpotGanadorMySqlDAO($Transaction);

        //Inactivando Jackpot
        $JackpotInterno->estado = 'I';
        $JackpotInternoMySqlDAO->update($JackpotInterno);

        //Acreditando ganancia en la inscripción del usuario
        $UsuarioJackpot->valorPremio = $JackpotInterno->valorActual;
        $UsuarioJackpotMySqlDAO->update($UsuarioJackpot);

        //Solicitando usuarioJackpot ganadores y asignándolos a nombre del ganador
        $rules = [];
        $rules[] = ['field' => 'usuariojackpot_ganador.jackpot_id', 'data' => $JackpotInterno->jackpotId, 'op' => 'eq'];
        $rules[] = ['field' => 'jackpot_detalle.tipo', 'data' => 'TIPOSALDO', 'op' => 'eq']; //Con este filtro se evita duplicidad de registros
        $filters = ['rules' => $rules, 'groupOp' => 'AND'];
        $select = 'usuariojackpot_ganador.usujackpotganador_id';
        $order = 'usuariojackpot_ganador.usujackpotganador_id';

        $joins = [];
        $joins[] = (object)['type' => 'INNER', 'table' => 'usuariojackpot_ganador', 'on' => 'jackpot_interno.jackpot_id = usuariojackpot_ganador.jackpot_id'];
        $usuJackpots = $this->getJackpotCustom($select, $order, 'DESC', 0, 4, json_encode($filters), true, $joins);
        $usuJackpots = json_decode($usuJackpots)->data;

        $jackpotVerticalsValue = [];
        $totalIncome = 0;
        foreach ($usuJackpots as $usuJackpotGanador) {
            $UsuarioJackpotGanador = new UsuariojackpotGanador($usuJackpotGanador->{'usuariojackpot_ganador.usujackpotganador_id'});
            $UsuarioJackpotGanador->usujackpotId = $UsuarioJackpot->usujackpotId;
            $UsuarioJackpotGanador->usuarioId = $Usuario->usuarioId;
            $UsuarioJackpotGanador->estado = 'I';
            $UsuarioJackpotGanadorMySqlDAO->update($UsuarioJackpotGanador);
            $totalIncome += $UsuarioJackpotGanador->valorPremio;

            //Almacenando vertical para reporte de log (Casino e informe gerencial)
            $logType = match ($UsuarioJackpotGanador->tipo) {
                'INCOME_CASINO' => 'JS',
                'INCOME_LIVECASINO' => 'JL',
                'INCOME_VIRTUAL' => 'JV',
                'INCOME_SPORTBOOK' => 'JD',
                default => 'error'
            };

            if ($logType == 'error') throw new Exception('Vertical no reconocida para acreditar jackpot', 300045);

            $jackpotVerticalsValue[] = (object)['type' => $logType, 'value' => $UsuarioJackpotGanador->valorPremio];
        }
        $totalIncome=round($totalIncome,2);
        $JackpotInterno->valorActual=round($totalIncome,2);

        if ($totalIncome != $JackpotInterno->valorActual) throw new Exception('Valor consolidado del jackpot presenta diferencias', 300051);


        /** Dejando logs por caída del jackpot, entregando saldo al usuario */
        $BonoLogMySqlDAO = new BonoLogMySqlDAO($Transaction);
        $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
        $typeBalanceId = $targetUserBalance == 'RECARGA' ? 0 : 1; //Si es saldo recarga en el log va 0, si es saldo retiro, va 1
        foreach ($jackpotVerticalsValue as $verticalValue) {
            if ($targetUserBalance == 'RECARGA') {
                $Usuario->credit(floatval($verticalValue->value), $Transaction);
            } elseif ($targetUserBalance == 'RETIRO') {
                $Usuario->creditWin(floatval($verticalValue->value), $Transaction);
            }

            $BonoLog = new BonoLog();
            $BonoLog->setUsuarioId($Usuario->usuarioId);
            $BonoLog->setTipo($verticalValue->type);
            $BonoLog->setValor($verticalValue->value);
            $BonoLog->setFechaCrea(date('Y-m-d H:i:s'));
            $BonoLog->setEstado('L');
            $BonoLog->setErrorId(0);
            $BonoLog->setIdExterno($UsuarioJackpot->usujackpotId);
            $BonoLog->setMandante($Usuario->mandante);
            $BonoLog->setFechaCierre('');
            $BonoLog->setTransaccionId('');
            $BonoLog->setTipobonoId(4);
            $BonoLog->setTiposaldoId($typeBalanceId);

            $BonoLogMySqlDAO->insert($BonoLog);

            $UsuarioHistorial = new UsuarioHistorial();
            $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
            $UsuarioHistorial->setDescripcion('');
            $UsuarioHistorial->setMovimiento('E');
            $UsuarioHistorial->setUsucreaId($Usuario->usuarioId);
            $UsuarioHistorial->setUsumodifId(0);
            $UsuarioHistorial->setTipo(50);
            $UsuarioHistorial->setValor($verticalValue->value);
            $UsuarioHistorial->setExternoId($BonoLog->bonologId);

            $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);
        }

        // intentando verificar si tiene notificaciones por Correo electronico


        try {
            $ContenidoHTML = $JackpotDetalle->cargarDetallesJackpot($jackpotId, "EMAILHTML");

        } catch (Exception $e) {

        }


        /** Notificando al usuario */
        $UsuarioMandante = new UsuarioMandante(null, $Usuario->usuarioId, $Usuario->mandante);
        $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($Transaction);
        $UsuarioMensaje = new UsuarioMensaje();
        $UsuarioMensaje->usufromId = 0;
        $UsuarioMensaje->usutoId = $UsuarioMandante->getUsumandanteId();
        $UsuarioMensaje->isRead = 0;
        $UsuarioMensaje->body = $JackpotInterno->jackpotId;
        $UsuarioMensaje->msubject = 'Jackpot Winner - JackpotId ' . $JackpotInterno->jackpotId;
        $UsuarioMensaje->parentId = 0;
        $UsuarioMensaje->proveedorId = $UsuarioMandante->getMandante();
        $UsuarioMensaje->tipo = "JACKPOTWINNER";
        $UsuarioMensaje->paisId = $UsuarioMandante->paisId;
        $UsuarioMensaje->fechaExpiracion = $fechaProxima->format('Y-m-d H:i:s');
        $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);

        $UsuarioMensaje->parentId = $UsuarioMensaje->usumensajeId;
        $UsuarioMensajeMySqlDAO->update($UsuarioMensaje);

        /** Envio de evento CRM por Jackpot Caido */
        if (!empty($UsuarioJackpotGanador)){

            //Parametros importantes para Optimove
            $useragent = $_SERVER['HTTP_USER_AGENT'];
            $jsonServer = json_encode($_SERVER);
            $serverCodif = base64_encode($jsonServer);
            $ismobile = '';

            //Detectando dispositivos
            if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match(
                    '/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4)
                )) {
                $ismobile = '1';
            }

            $iPod = stripos($_SERVER['HTTP_USER_AGENT'], "iPod");
            $iPhone = stripos($_SERVER['HTTP_USER_AGENT'], "iPhone");
            $iPad = stripos($_SERVER['HTTP_USER_AGENT'], "iPad");
            $Android = stripos($_SERVER['HTTP_USER_AGENT'], "Android");
            $webOS = stripos($_SERVER['HTTP_USER_AGENT'], "webOS");

            if ($iPod || $iPhone) {
                $ismobile = '1';
            } elseif ($iPad) {
                $ismobile = '1';
            } elseif ($Android) {
                $ismobile = '1';
            }
            // Envio de evento a CRM
            exec("php -f " . __DIR__ . "/../integrations/crm/AgregarCrm.php " . $UsuarioJackpotGanador->usuarioId . " " . "FALLJACKPOTCRM" . " " . $UsuarioJackpotGanador->usujackpotganadorId . " " . $serverCodif . " " . $ismobile . " > /dev/null &");


        }


        return true;
    }


    /**
     * Clona un jackpot existente y genera una nueva serie basada en éste.
     *
     * Este método valida el estado y las condiciones del jackpot a clonar,
     * solicita los detalles relacionados, verifica si es posible realizar una clonación
     * y genera un nuevo jackpot con datos inicializados en base a las configuraciones existentes.
     * Asimismo, realiza la clonación de detalles estándar y de gestión especial,
     * establece valores iniciales para el jackpot y actualiza la base de datos.
     *
     * @param int $jackpotId Identificador del jackpot a clonar.
     * @return bool Devuelve true si la clonación del jackpot se realizó correctamente.
     * @throws Exception Si el jackpot no puede ser clonado por sus condiciones actuales.
     */
    public function clonarJakcpotNextSerie(int $jackpotId)
    {
        /** Validando que Jackpot pueda clonarse */
        $JackpotInterno = new JackpotInterno($jackpotId);
        if ($JackpotInterno->estado != 'I') throw new Exception('No es posible clonar este Jackpot', 300045);
        elseif (!$JackpotInterno->reinicio) throw new Exception('No es posible clonar este Jackpot', 300045);

        /** Solicitando detalles del jackpot */
        $JackpotDetalle = new JackpotDetalle();

        //Validando total series
        $totalSeries = 1;
        $details = $JackpotDetalle->cargarDetallesJackpot($JackpotInterno->jackpotId, 'TOTALSERIES');
        $totalSeriesDetail = $JackpotDetalle->encontrarDetalle($details, 'TOTALSERIES')[0];
        if (empty((array)$totalSeriesDetail)) throw new Exception('No es posible clonar este Jackpot', 300045);
        $totalSeries = $totalSeriesDetail->valor;

        //Validando última serie
        $lastSerie = 1;
        $details = $JackpotDetalle->cargarDetallesJackpot($JackpotInterno->jackpotId, 'CURRENTSERIE');
        $lastSerieDetail = $JackpotDetalle->encontrarDetalle($details, 'CURRENTSERIE')[0];
        if (empty((array)$lastSerieDetail)) throw new Exception('No es posible clonar este Jackpot', 300045);
        $lastSerie = $lastSerieDetail->valor;

        //Validando si es posible volver a clonar el jackpot
        if ($totalSeries != 0 && $totalSeries < ($lastSerie + 1)) throw new Exception('No es posible clonar este Jackpot', 300045);

        /** Clonando JackpotInterno con base en el JackpotPadre (O en si mismo) */
        $JackpotInterno = new JackpotInterno($JackpotInterno->jackpotPadre ?: $JackpotInterno->jackpotId);
        $Transaction = new Transaction();

        $notCloneableProperties = [
            'jackpotId' => null,
            'jackpotPadre' => $JackpotInterno->jackpotPadre ?: $JackpotInterno->jackpotId,
            'estado' => 'A',
            'fechaInicio' => date('Y-m-d H:i:s'),
            'valorActual' => 0,
            'valorBase' => 0,
            'cantidadApuesta' => 0,
            'usucreaId' => 0,
            'usumodifId' => 0,
            'notas' => null
        ];
        $NewJackpotInterno = new JackpotInterno();
        foreach ($JackpotInterno as $property => $value) {
            if (in_array($property, array_keys($notCloneableProperties))) {
                $NewJackpotInterno->$property = $notCloneableProperties[$property];
                continue;
            }

            $NewJackpotInterno->$property = $value;
        }

        $JackpotInternoMySqlDAO = new JackpotInternoMySqlDAO($Transaction);
        $JackpotInternoMySqlDAO->insert($NewJackpotInterno);

        /** Solicitando detalles del jackpotPadre para su clonación mediante el método standard */
        $details = $JackpotDetalle->cargarDetallesJackpot($JackpotInterno->jackpotId);
        $inheritableDetails = $JackpotDetalle->getTiposGeneralesHeredablesJackpot();

        $detailsToStandardCloning = [];
        $detailsToSpecialCloning = [];
        foreach ($inheritableDetails as $type => $method) {
            if ($method != 'clonarCondicionStandard') {
                $detailsToSpecialCloning[$type] = $method;
                continue;
            }

            $detailsPerTypePackage = $JackpotDetalle->encontrarDetalle($details, $type);
            $detailsToStandardCloning = array_merge($detailsToStandardCloning, $detailsPerTypePackage);
        }


        $detailsNewJackpot = $JackpotDetalle->clonarCondicionesStandard($NewJackpotInterno, $detailsToStandardCloning, $Transaction);


        /** Clonando detalles de gestión especial */
        $detailsResponse = [];
        if (in_array('CURRENTSERIE', array_keys($detailsToSpecialCloning))) {
            $detailCurrentSeries[] = (object)['type' => 'CURRENTSERIE', 'value' => ($lastSerie + 1)];
            $JackpotDetalle->insertarDetallesJackpot($NewJackpotInterno->jackpotId, $detailCurrentSeries, $detailsNewJackpot[0]->moneda, $Transaction, 0, $detailsResponse);
            $detailsNewJackpot = array_merge($detailsNewJackpot, $detailsResponse);
        }

        $detailsResponse = $JackpotDetalle->clonarCondicionEspecial($NewJackpotInterno, $detailsToSpecialCloning, $details, $detailsNewJackpot, $Transaction);
        $detailsNewJackpot = array_merge($detailsNewJackpot, $detailsResponse);


        /** Definiendo valor inicial del jackpot */
        $UsuarioJackpotGanadorMySqlDAO = new UsuariojackpotGanadorMySqlDAO($Transaction);

        $verticals = [
            'JACKPOTINITVALUE_CASINO',
            'JACKPOTINITVALUE_LIVECASINO',
            'JACKPOTINITVALUE_VIRTUAL',
            'JACKPOTINITVALUE_SPORTBOOK',
        ];
        $totalIncome = 0;
        foreach ($verticals as $vertical) {
            $incomeDetail = $JackpotDetalle->encontrarDetalle($detailsNewJackpot, $vertical);
            if (empty($incomeDetail)) continue;

            $incomeType = match ($vertical) {
                'JACKPOTINITVALUE_CASINO' => 'INCOME_CASINO',
                'JACKPOTINITVALUE_LIVECASINO' => 'INCOME_LIVECASINO',
                'JACKPOTINITVALUE_VIRTUAL' => 'INCOME_VIRTUAL',
                'JACKPOTINITVALUE_SPORTBOOK' => 'INCOME_SPORTBOOK',
                default => 'INCOME_ERROR'
            };
            $incomeValue = $incomeDetail[0]->valor;

            $UsuarioJackpotGanador = new UsuariojackpotGanador();

            $UsuarioJackpotGanador->usujackpotId = 0;
            $UsuarioJackpotGanador->jackpotId = $NewJackpotInterno->getJackpotId();
            $UsuarioJackpotGanador->tipo = $incomeType;
            $UsuarioJackpotGanador->usuarioId = 0;
            $UsuarioJackpotGanador->valorPremio = (float)$incomeValue;
            $UsuarioJackpotGanador->estado = 'A';
            $UsuarioJackpotGanador->usucreaId = 0;
            $UsuarioJackpotGanador->usumodifId = 0;

            $UsuarioJackpotGanadorMySqlDAO->insert($UsuarioJackpotGanador);
            $totalIncome += $incomeValue;
        }

        $NewJackpotInterno->setValorBase($totalIncome);
        $NewJackpotInterno->setValorActual($totalIncome);

        $JackpotInternoMySqlDAO->update($NewJackpotInterno);
        $Transaction->commit();

        return true;
    }


    /** Intentar acreditar la apuesta entregada en los jackpot disponibles en el país y partner al que pertenece el usuario que realizó la transacción
     * Adicionalmente, paga el jackpot en caso de que este caiga y notifica al usuario ganador
     * @param string $tipoTransaccion Tipo de transacción a acred
     * @param int $transaccionId ID de la transacción a acreditar
     * @return bool Indica si la apuesta fue acreditada
     */
    public function intentarAcreditarApuesta(string $tipoTransaccion, $transaccionId)
    {
        $Conection = new Connection();

        if ($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null) {

            /* Conecta a la base de datos usando la configuración almacenada en el entorno. */
            $connOriginal = $_ENV["connectionGlobal"]->getConnection();

            try {
                /* Conexión a la base de datos en producción utilizando PDO y SSL. */
                $connDB5 = null;

                if ($_ENV['ENV_TYPE'] == 'prod') {

                    $connDB5 = new \PDO(
                        "mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(),
                        ConnectionProperty::getUser(),
                        ConnectionProperty::getPassword(),
                        array(
                            PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-bundle.crt',
                            PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
                        )
                    );
                } else {
                    /* Conecta a una base de datos MySQL usando PDO y variables de entorno. */

                    $connDB5 = new \PDO(
                        "mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(),
                        ConnectionProperty::getUser(),
                        ConnectionProperty::getPassword()
                    );
                }

                /* Configura la conexión a la base de datos con codificación UTF-8 y zona horaria. */
                $connDB5->exec("set names utf8");

                if ($_ENV["TIMEZONE"] != null && $_ENV["TIMEZONE"] != '') {
                    $connDB5->exec('SET time_zone = "' . $_ENV["TIMEZONE"] . '";');
                }

                /* Configura el nivel de aislamiento y tiempo de espera de bloqueo en una conexión a base de datos. */
                if ($_ENV["NEEDINSOLATIONLEVEL"] != null && $_ENV["NEEDINSOLATIONLEVEL"] == '1') {
                    $connDB5->exec('SET TRANSACTION ISOLATION LEVEL READ COMMITTED;');
                }

                if ($_ENV["ENABLEDSETLOCKWAITTIMEOUT"] != null && $_ENV["ENABLEDSETLOCKWAITTIMEOUT"] == '1') {
                    // $connDB5->exec('SET SESSION innodb_lock_wait_timeout = 5;');
                }

                /* configura tiempo de ejecución y codificación UTF-8 en la base de datos. */
                if ($_ENV["ENABLEDSETMAX_EXECUTION_TIME"] != null && $_ENV["ENABLEDSETMAX_EXECUTION_TIME"] == '1') {
                    // $connDB5->exec('SET SESSION MAX_EXECUTION_TIME = 120000;');
                }

                if ($_ENV["DBNEEDUTF8"] != null && $_ENV["DBNEEDUTF8"] == '1') {
                    $connDB5->exec("SET NAMES utf8mb4");
                }

                /* Establece una conexión a la base de datos utilizando una variable de entorno. */
                $_ENV["connectionGlobal"]->setConnection($connDB5);
            } catch (\Exception $e) {
                /* captura excepciones en PHP, evitando interrupciones en la ejecución. */
            }
        }

        /** Instanciando la transaccion entregada y el usuario correspondiente */
        $casinoGroup = self::getCasinoGroup();
        if (in_array($tipoTransaccion, $casinoGroup)) {
            $TransjuegoLog = new TransjuegoLog($transaccionId);
            $TransaccionJuego = new TransaccionJuego($TransjuegoLog->transjuegoId);
            $UsuarioMandante = new UsuarioMandante($TransaccionJuego->usuarioId);

            //Definiendo parámetros comunes
            $country = $UsuarioMandante->getPaisId();
            $currency = $UsuarioMandante->moneda;
            $partner = $UsuarioMandante->mandante;
            $betCreationDate = date('Y-m-d H:i:s', strtotime($TransjuegoLog->fechaCrea));
            $betValue = floatval($TransjuegoLog->valor);
        } elseif ($tipoTransaccion == 'SPORTBOOK') {
            $ItTicketEnc = new ItTicketEnc($transaccionId);
            $Usuario = new Usuario($ItTicketEnc->usuarioId);
            $UsuarioMandante = new UsuarioMandante(null, $Usuario->usuarioId, $Usuario->mandante);

            //Definiendo parámetros comunes
            $country = $Usuario->paisId;
            $currency = $Usuario->moneda;
            $partner = $Usuario->mandante;
            $betCreationDate = date('Y-m-d H:i:s', strtotime($ItTicketEnc->fechaCreaTime ?? $ItTicketEnc->fechaCrea . ' ' . $ItTicketEnc->horaCrea));
            $betValue = floatval($ItTicketEnc->vlrApuesta);
        } else return false; //Responde false porque la apuesta no se acreditará si no es de alguna de las verticales descritas anteriormente

        if ($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null && $connOriginal != null) {
            $connDB5 = null;

            $_ENV["connectionGlobal"]->setConnection($connOriginal);
        }


        /** Consultando contingencia por abusador de bonos para el usuario bajo revisión */
        $UsuarioConfiguracion = new UsuarioConfiguracion();
        $UsuarioConfiguracion = $UsuarioConfiguracion->getUserBondABuser($UsuarioMandante->usuarioMandante);
        $isBonusAbuser = false;
        if (!empty((array)$UsuarioConfiguracion)) {
            $isBonusAbuser = $UsuarioConfiguracion->estado == 'A';
        }

        /** Solicitando los Jackpot disponibles */
        $select = "jackpot_interno.jackpot_id, jackpot_interno.reinicio, jackpot_interno.fecha_fin, jackpot_interno.mandante, jackpot_detalle.tipo, jackpot_detalle.moneda, jackpot_detalle.valor";
        $order = "jackpot_interno.orden";

        $rules = [];
        $rules[] = ['field' => 'jackpot_detalle.tipo', 'data' => 'CONDPAISUSER', 'op' => 'eq'];
        $rules[] = ['field' => 'jackpot_detalle.moneda', 'data' => $currency, 'op' => 'eq'];
        $rules[] = ['field' => 'jackpot_detalle.valor', 'data' => $country, 'op' => 'eq'];
        $rules[] = ['field' => 'jackpot_interno.mandante', 'data' => $partner, 'op' => 'eq'];
        $rules[] = ['field' => 'jackpot_interno.estado', 'data' => 'A', 'op' => 'eq'];
        $rules[] = ['field' => 'jackpot_interno.fecha_inicio', 'data' => date('Y-m-d H:i:s'), 'op' => 'le'];
        $filters = ['rules' => $rules, 'groupOp' => 'AND'];

        $JackpotDetalle = new JackpotDetalle();
        $count = $JackpotDetalle->getJackpotDetalleCustom($select, $order, 'DESC', 0, 1, json_encode($filters), true, true);
        $count = json_decode($count)->count;
        $count = $count[0]->{'.count'};

        $jackpots = $JackpotDetalle->getJackpotDetalleCustom($select, $order, 'DESC', 0, $count, json_encode($filters), true);
        $jackpots = json_decode($jackpots)->data;




        foreach ($jackpots as $jackpot) {
            try {
                //Verificando si Jackpot tiene fecha fin
                if ($jackpot->{'jackpot_interno.reinicio'} == 0) {
                    if ($jackpot->{'jackpot_interno.fecha_fin'} < $betCreationDate) continue;
                }

                /** @var  $userAdditionalData
                 * Objeto de transferencia de datos utilizado para pasar información concerniente al usuario a la función validarCondicionesJackpot.
                 * Procure obtener la info en parámetros externos y definir todas las propiedades del objeto a continuación en inglés
                 *
                 * ATENCIÓN
                 * La función validarCondicionesJackpot debe ser capaz de consultar las mismas contingencias que se envían por el parámetro en
                 * caso de que este objeto no se envíe; la función del objeto es reducir el total de consultas a base de datos, NO de generar mayor
                 * acoplamiento por parte de la función
                 */
                $userAdditionalData = (object) [
                    'isBonusAbuser' => $isBonusAbuser,
                ];

                //Validando condiciones del Jackpot
                $approvedBet = false;
                $failedConditions = [];
                $JackpotInterno = new JackpotInterno($jackpot->{'jackpot_interno.jackpot_id'});
                if (in_array($tipoTransaccion, $casinoGroup)) {
                    $approvedBet = $this->validarCondicionesJackpot($tipoTransaccion, $JackpotInterno, $UsuarioMandante, $TransjuegoLog, null, $failedConditions, $userAdditionalData);
                } elseif ($tipoTransaccion == 'SPORTBOOK') {
                    $approvedBet = $this->validarCondicionesJackpot($tipoTransaccion, $JackpotInterno, $UsuarioMandante, null, $ItTicketEnc, $failedConditions, $userAdditionalData);
                }

                if (!$approvedBet) {
                    //Registrando logs de condiciones fallidas
                    $LogRechazoSTB = new LogRechazoSTB();
                    $registeredLogs = $LogRechazoSTB->logRechazosJackpot($failedConditions, $tipoTransaccion, $JackpotInterno, $UsuarioMandante, $TransjuegoLog ?: null, $ItTicketEnc ?: null);

                    //Saltando al siguiente jackpot
                    continue;
                }

                //Consultando porcentaje por sumar al Jackpot
                $currentVerticalPercentage = 'JACKPOTPERCENTAGE_' . $tipoTransaccion;
                $jackpotPercentage = $JackpotDetalle->cargarDetallesJackpot($JackpotInterno->jackpotId, $currentVerticalPercentage)[0];
                $jackpotPercentage = floatval($jackpotPercentage->valor);
                if ($jackpotPercentage <= 0 || $jackpotPercentage > 100) return false;

                //Calculando valor a sumar al jackpot
                $valueToWell = round((($betValue * $jackpotPercentage) / 100), 3, PHP_ROUND_HALF_DOWN);

                if($valueToWell<=0){
                    return false;
                }

                /** Comenzando proceso de acreditación */
                $UsuarioJackpotMySqlDAO = new UsuarioJackpotMySqlDAO();

                //Buscando inscripción del usuario o inscribiendo usuario en el jackpot
                try {
                    $UsuarioJackpot = new UsuarioJackpot(null, $JackpotInterno->jackpotId, $UsuarioMandante->usuarioMandante);
                } catch (Exception $e) {
                    if ($e->getCode() != 80) throw $e;

                    $UsuarioJackpot = new UsuarioJackpot();

                    $UsuarioJackpot->jackpotId = $JackpotInterno->jackpotId;
                    $UsuarioJackpot->usuarioId = $UsuarioMandante->usuarioMandante;
                    $UsuarioJackpot->valor = 0;
                    $UsuarioJackpot->usucreaId = 0;
                    $UsuarioJackpot->usumodifId = 0;
                    $UsuarioJackpot->externoId = 0;
                    $UsuarioJackpot->apostado = 0;
                    $UsuarioJackpot->valorPremio = 0;

                    $UsuarioJackpotMySqlDAO->insert($UsuarioJackpot);
                }
                $UsuarioJackpotMySqlDAO->getTransaction()->commit();

                /** Comenzando proceso de acreditación */
                $UsuarioJackpotMySqlDAO = new UsuarioJackpotMySqlDAO();
                $Transaction= $UsuarioJackpotMySqlDAO->getTransaction();


                //Dejando log correspondiente
                if (in_array($tipoTransaccion, $casinoGroup)) {
                    $logId = $this->agregarLogCasino($valueToWell, $UsuarioJackpot, $TransjuegoLog, $Transaction);
                    $logId = 'C_' . $logId;
                } elseif ($tipoTransaccion == 'SPORTBOOK') {
                    $logId = $this->agregarLogSportbook($valueToWell, $UsuarioJackpot, $ItTicketEnc, $Transaction);
                    $logId = 'S_' . $logId;
                }


                //¡¡Acreditando apuesta!! --Se hace commit a la transacción dentro de la función acreditarApuesta
                $jackpotWinner = false;
                $accreditationResponse = $this->acreditarApuesta($tipoTransaccion, $logId, $JackpotInterno, $UsuarioJackpot, $valueToWell, $betValue, $Transaction, $jackpotWinner);


                /** Entregando premio en caso de haber caído el jackpot */
                if ($jackpotWinner == true) {
                    try {
                        $this->pagarJackpot($JackpotInterno->jackpotId, $UsuarioJackpot->usujackpotId, $Transaction);
                    } catch (Exception $e) {
                        syslog(LOG_ERR, " ERRORPAGOJACKPOT : " . $e->getCode() . " - " . $e->getMessage(). "Línea: ". $e->getLine(). "Archivo : ".$e->getFile());
                        $Transaction->rollback();
                        continue;
                    }

                    $Transaction->commit();


                    try {
                        /** Se verifica si es necesario reiniciar el jackpot La función utilizada lanza excepciones*/
                        if ($JackpotInterno->reinicio) $this->clonarJakcpotNextSerie($JackpotInterno->jackpotId);
                    } catch (Exception $e) {
                    }

                    try {
                        sleep(30);

                        $rules = [];
                        array_push($rules, array("field" => "usuario_mensaje.proveedor_id", "data" => $UsuarioMandante->getMandante(), "op" => "eq"));
                        array_push($rules, array("field" => "usuario_mensaje2.usuto_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));
                        array_push($rules, array("field" => "usuario_mensaje2.body", "data" => $JackpotInterno->jackpotId, "op" => "eq"));
                        array_push($rules, array("field" => "usuario_mensaje.tipo", "data" => "JACKPOTWINNER", "op" => "eq"));
                        array_push($rules, array("field" => "usuario_mensaje.is_read", "data" => 0, "op" => "eq"));
                        $filtroM = array("rules" => $rules, "groupOp" => "AND");
                        $json2 = json_encode($filtroM);

                        $UsuarioMensaje = new UsuarioMensaje();
                        $usuarios = $UsuarioMensaje->getUsuarioMensajesCustom(" usuario_mensaje.* ", "usuario_mensaje.usumensaje_id", "asc", 0, 1, $json2, true, $UsuarioMandante->usumandanteId);
                        $usuarios = json_decode($usuarios)->data;

                        $messagesToDesactivate = [];
                        foreach ($usuarios as $jackpotWinnerMessage) {
                            try {
                                $JackpotInterno = new JackpotInterno($jackpotWinnerMessage->{'usuario_mensaje.body'});
                            } catch (Exception $e) {
                                break;
                            }

                            $dropedJackpotData = [[
                                'uid' => $jackpotWinnerMessage->{'usuario_mensaje.usumensaje_id'},
                                'id' => $JackpotInterno->jackpotId,
                                'videoMobile' => $JackpotInterno->videoMobile,
                                'video' => $JackpotInterno->videoDesktop,
                                'gif' => $JackpotInterno->gif,
                                'imagen' => $JackpotInterno->imagen,
                                'imagen2' => $JackpotInterno->imagen2,
                                'monto' => $JackpotInterno->valorActual
                            ]];
                        }


                        $dataSend = array();
                        $dataSend["loyalty_price"] = $dropedJackpotData;

                        $WebsocketUsuario = new WebsocketUsuario('', '');
                        $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend);
                    } catch (Exception $e) {
                    }
                }
            } catch (Exception $e) {

            }
        }

        return true;
    }


    /**
     * Acredita un monto al jackpot especificado.
     *
     * @param float $valor El valor a acreditar al jackpot, redondeado a dos decimales.
     * @param mixed $transaction La transacción asociada para realizar la operación.
     * @param int $jackpotId El identificador único del jackpot al que se acreditará el monto.
     *
     * @return mixed El resultado de la operación de actualización del monto del jackpot.
     */
    public function creditJackpot($valor, $transaction, $jackpotId)
    {
        $valor = round($valor, 2);

        $JackpotInternoMySqlDAO = new JackpotInternoMySqlDAO($transaction);

        $return = $JackpotInternoMySqlDAO->updateAmountJackpot($jackpotId, $valor, "");

        return $return;

    }


    /**
     * Ejecuta una consulta SQL utilizando un DAO específico.
     *
     * @param mixed $transaccion La transacción activa que se utilizará para la consulta.
     * @param string $sql La consulta SQL que se desea ejecutar.
     * @return mixed El resultado de la consulta, decodificado como un objeto PHP.
     */
    public function execQuery($transaccion, $sql)
    {

        $JackpotInternoMySqlDAO = new JackpotInternoMySqlDAO($transaccion);
        $return = $JackpotInternoMySqlDAO->querySQL($sql);
        $return = json_decode(json_encode($return), FALSE);

        return $return;

    }

    /**
     * Ejecuta una actualización en la base de datos utilizando una transacción y una consulta SQL proporcionada.
     *
     * @param mixed $transaccion La transacción activa que se utilizará para la operación.
     * @param string $sql La consulta SQL que se ejecutará para realizar la actualización.
     * @return mixed El resultado de la operación de actualización, retornado por el método queryUpdate.
     */
    public function execUpdate($transaccion, $sql)
    {

        $JackpotInternoMySqlDAO = new JackpotInternoMySqlDAO($transaccion);
        $return = $JackpotInternoMySqlDAO->queryUpdate($sql);

        return $return;

    }

    /**
     * Obtiene el reporte de de los jackpots ganadores
     * @param int $start Inicio de la paginación
     * @param int $limit Limite de la paginación
     * @param array $filters Filtros de la consulta
     * @param int $country Pais del usuario
     * @return array $result resultado de la verificación
     */
    public function getJackpotWinners($start, $limit, $filters, $country)
    {

        $JackpotInternoMySqlDAO = new JackpotInternoMySqlDAO();
        return $JackpotInternoMySqlDAO->getJackpotWinners($start, $limit, $filters, $country);
    }

    /** Función retorna un array con todos los tipos que un jackpot puede llegar a dejar en bono_log
     * @param string $verticalGroup Grupo vertical de la cual se requieren los tipos
     * @return array Tipos de jackpot que pueden ser registrados en bono_log
     */
    public static function getJackpotTypesForBonoLog($verticalGroup = null): array
    {
        $sportbookTypes = ['JD'];
        $casinoTypes = [
            'JS',
            'JL',
            'JV'
        ];

        if ($verticalGroup == 'CASINO') return $casinoTypes;
        elseif ($verticalGroup == 'DEPORTIVAS') return $sportbookTypes;
        else return array_merge($sportbookTypes, $casinoTypes);
    }

    /**
     * Obtiene los tipos de jackpot en formato de cadena concatenada para el registro de bono.
     *
     * @param mixed $verticalGroup Identificador opcional para un grupo vertical específico.
     * Puede ser null si no se necesita especificar un grupo.
     * @return string|null Una cadena concatenada de tipos de jackpot entre comillas simples,
     * separados por comas, o null si no hay tipos disponibles.
     */
    public static function getJackpotTypesForBonoLogString($verticalGroup = null): ?string
    {
        $types = self::getJackpotTypesForBonoLog($verticalGroup);

        $concatTypes = array_reduce($types, function ($stringTypes, $type) {
            $stringTypes .= ($stringTypes == null ? "" : ",") . "'{$type}'";
            return $stringTypes;
        }, null);

        return $concatTypes;
    }


    /**
     * Devuelve el grupo de categorías relacionadas con casinos.
     *
     * @return array Lista de categorías de casinos.
     */
    public static function getCasinoGroup() : array
    {
        $casinoGroup = [
            'CASINO',
            'LIVECASINO',
            'VIRTUAL'
        ];

        return $casinoGroup;
    }
}
?>
