<?php namespace Backend\dto;
use Backend\mysql\RegistroMySqlDAO;
/**
* Clase 'Registro'
*
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'Registro'
*
* Ejemplo de uso:
* $Registro = new Registro();
*
*
* @package ninguno
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public
* @see no
*
*/
class Registro
{

    /**
    * Representación de la columna 'registroId' de la tabla 'Registro'
    *
    * @var string
    */
    var $registroId;

    /**
    * Representación de la columna 'nombre' de la tabla 'Registro'
    *
    * @var string
    */
    var $nombre;

    /**
    * Representación de la columna 'email' de la tabla 'Registro'
    *
    * @var string
    */
    var $email;

    /**
    * Representación de la columna 'puntoventaId' de la tabla 'Registro'
    *
    * @var string
    */
    var $puntoventaId;

    /**
    * Representación de la columna 'estado' de la tabla 'Registro'
    *
    * @var string
    */
    var $estado;

    /**
    * Representación de la columna 'usuarioId' de la tabla 'Registro'
    *
    * @var string
    */
    var $usuarioId;

    /**
    * Representación de la columna 'claveActiva' de la tabla 'Registro'
    *
    * @var string
    */
    var $claveActiva;

    /**
    * Representación de la columna 'creditos' de la tabla 'Registro'
    *
    * @var string
    */
    var $creditos;

    /**
    * Representación de la columna 'creditosBase' de la tabla 'Registro'
    *
    * @var string
    */
    var $creditosBase;

    /**
    * Representación de la columna 'celular' de la tabla 'Registro'
    *
    * @var string
    */
    var $celular;

    /**
    * Representación de la columna 'ciudad' de la tabla 'Registro'
    *
    * @var string
    */
    var $ciudad;

    /**
    * Representación de la columna 'creditosAnt' de la tabla 'Registro'
    *
    * @var string
    */
    var $creditosAnt;

    /**
    * Representación de la columna 'creditosBaseAnt' de la tabla 'Registro'
    *
    * @var string
    */
    var $creditosBaseAnt;

    /**
    * Representación de la columna 'ciudadId' de la tabla 'Registro'
    *
    * @var string
    */
    var $ciudadId;

    /**
    * Representación de la columna 'mandante' de la tabla 'Registro'
    *
    * @var string
    */
    var $mandante;

    /**
    * Representación de la columna 'casino' de la tabla 'Registro'
    *
    * @var string
    */
    var $casino;

    /**
    * Representación de la columna 'casinoBase' de la tabla 'Registro'
    *
    * @var string
    */
    var $casinoBase;

    /**
    * Representación de la columna 'fechaCasino' de la tabla 'Registro'
    *
    * @var string
    */
    var $fechaCasino;

    /**
    * Representación de la columna 'preregistroId' de la tabla 'Registro'
    *
    * @var string
    */
    var $preregistroId;

    /**
    * Representación de la columna 'creditosBono' de la tabla 'Registro'
    *
    * @var string
    */
    var $creditosBono;

    /**
    * Representación de la columna 'creditosBonoAnt' de la tabla 'Registro'
    *
    * @var string
    */
    var $creditosBonoAnt;

    /**
    * Representación de la columna 'ocupacion' de la tabla 'Registro'
    *
    * @var string
    */
    var $ocupacion;

    /**
    * Representación de la columna 'rangoingresoId' de la tabla 'Registro'
    *
    * @var string
    */
    var $rangoingresoId;

    /**
    * Representación de la columna 'origenFondos' de la tabla 'Registro'
    *
    * @var string
    */
    var $origenFondos;

    /**
    * Representación de la columna 'paisnacimId' de la tabla 'Registro'
    *
    * @var string
    */
    var $paisnacimId;

    /**
    * Representación de la columna 'cedula' de la tabla 'Registro'
    *
    * @var string
    */
    var $cedula;

    /**
    * Representación de la columna 'nombre1' de la tabla 'Registro'
    *
    * @var string
    */
    var $nombre1;

    /**
    * Representación de la columna 'nombre2' de la tabla 'Registro'
    *
    * @var string
    */
    var $nombre2;

    /**
    * Representación de la columna 'apellido1' de la tabla 'Registro'
    *
    * @var string
    */
    var $apellido1;

    /**
    * Representación de la columna 'apellido2' de la tabla 'Registro'
    *
    * @var string
    */
    var $apellido2;

    /**
    * Representación de la columna 'sexo' de la tabla 'Registro'
    *
    * @var string
    */
    var $sexo;

    /**
    * Representación de la columna 'direccion' de la tabla 'Registro'
    *
    * @var string
    */
    var $direccion;

    /**
    * Representación de la columna 'telefono' de la tabla 'Registro'
    *
    * @var string
    */
    var $telefono;

    /**
    * Representación de la columna 'ciudnacimId' de la tabla 'Registro'
    *
    * @var string
    */
    var $ciudnacimId;

    /**
    * Representación de la columna 'nacionalidadId' de la tabla 'Registro'
    *
    * @var string
    */
    var $nacionalidadId;

    /**
    * Representación de la columna 'estadoValida' de la tabla 'Registro'
    *
    * @var string
    */
    var $estadoValida;

    /**
    * Representación de la columna 'usuvalidaId' de la tabla 'Registro'
    *
    * @var string
    */
    var $usuvalidaId;

    /**
    * Representación de la columna 'fechaValida' de la tabla 'Registro'
    *
    * @var string
    */
    var $fechaValida;

    /**
    * Representación de la columna 'dirIp' de la tabla 'Registro'
    *
    * @var string
    */
    var $dirIp;

    /**
    * Representación de la columna 'tipoDoc' de la tabla 'Registro'
    *
    * @var string
    */
    var $tipoDoc;

    /**
    * Representación de la columna 'ciudexpedId' de la tabla 'Registro'
    *
    * @var string
    */
    var $ciudexpedId;

    /**
    * Representación de la columna 'fechaExped' de la tabla 'Registro'
    *
    * @var string
    */
    var $fechaExped;

    /**
    * Representación de la columna 'codigoPostal' de la tabla 'Registro'
    *
    * @var string
    */
    var $codigoPostal;

    /**
    * Representación de la columna 'ocupacionId' de la tabla 'Registro'
    *
    * @var string
    */
    var $ocupacionId;

    /**
    * Representación de la columna 'origenfondosId' de la tabla 'Registro'
    *
    * @var string
    */
    var $origenfondosId;

    /**
    * Representación de la columna 'afiliadorId' de la tabla 'Registro'
    *
    * @var string
    */
    var $afiliadorId;

    /**
     * Representación de la columna 'saldoBonosLiberados' de la tabla 'Registro'
     * @var string
     */
    var $saldoBonosLiberados;

    /**
     * Representación de la columna 'saldoCasinoBonos' de la tabla 'Registro'
     * @var string
     */
    var $saldoCasinoBonos;

    /**
     * Representación de la columna 'bannerId' de la tabla 'Registro'
     * @var string
     */
    var $success;

    /**
     * Representación de la columna 'bannerId' de la tabla 'Registro'
     * @var string
     */
    var $bannerId;

    /**
     * Representación de la columna 'linkId' de la tabla 'Registro'
     * @var string
     */
    var $linkId;

    /**
     * Representación de la columna 'codpromocionalId' de la tabla 'Registro'
     * @var string
     */
    var $codpromocionalId;





    /**
    * Constructor de clase
    *
    * @param String $registroId id del registro
    * @param String $usuarioId id del usuario
    */
    public function __construct($registroId="", $usuarioId="")
    {
        $this->success = false;

        if ($registroId != "")
        {

            $this->registroId = $registroId;

            $RegistroMySqlDAO = new RegistroMySqlDAO();
            $Registro = $RegistroMySqlDAO->load($registroId);

            if ($Registro != "" && $Registro != null)
            {


                $this->registroId = $Registro->registroId;
                $this->nombre = $Registro->nombre;
                $this->email = $Registro->email;
                $this->puntoventaId = $Registro->puntoventaId;
                $this->estado = $Registro->estado;
                $this->usuarioId = $Registro->usuarioId;
                $this->claveActiva = $Registro->claveActiva;
                $this->creditos = $Registro->creditos;
                $this->creditosBase = $Registro->creditosBase;
                $this->celular = $Registro->celular;
                $this->ciudad = $Registro->ciudad;
                $this->creditosAnt = $Registro->creditosAnt;
                $this->creditosBaseAnt = $Registro->creditosBaseAnt;
                $this->ciudadId = $Registro->ciudadId;
                $this->mandante = $Registro->mandante;
                $this->casino = $Registro->casino;
                $this->casinoBase = $Registro->casinoBase;
                $this->fechaCasino = $Registro->fechaCasino;
                $this->preregistroId = $Registro->preregistroId;
                $this->creditosBono = $Registro->creditosBono;
                $this->creditosBonoAnt = $Registro->creditosBonoAnt;
                $this->ocupacion = $Registro->ocupacion;
                $this->rangoingresoId = $Registro->rangoingresoId;
                $this->origenFondos = $Registro->origenFondos;
                $this->paisnacimId = $Registro->paisnacimId;
                $this->cedula = $Registro->cedula;
                $this->nombre1 = $Registro->nombre1;
                $this->nombre2 = $Registro->nombre2;
                $this->apellido1 = $Registro->apellido1;
                $this->apellido2 = $Registro->apellido2;
                $this->sexo = $Registro->sexo;
                $this->direccion = $Registro->direccion;
                $this->telefono = $Registro->telefono;
                $this->ciudnacimId = $Registro->ciudnacimId;
                $this->nacionalidadId = $Registro->nacionalidadId;
                $this->estadoValida = $Registro->estadoValida;
                $this->usuvalidaId = $Registro->usuvalidaId;
                $this->fechaValida = $Registro->fechaValida;
                $this->dirIp = $Registro->dirIp;
                $this->tipoDoc = $Registro->tipoDoc;
                $this->ciudexpedId = $Registro->ciudexpedId;
                $this->fechaExped = $Registro->fechaExped;
                $this->codigoPostal = $Registro->codigoPostal;
                $this->ocupacionId = $Registro->ocupacionId;
                $this->origenfondosId = $Registro->origenfondosId;
                $this->afiliadorId = $Registro->afiliadorId;
                $this->saldoBonosLiberados = $Registro->saldoBonosLiberados;
                $this->saldoCasinoBonos = $Registro->saldoCasinoBonos;

                $this->bannerId = $Registro->bannerId;
                $this->linkId = $Registro->linkId;

                $this->codpromocionalId = $Registro->codpromocionalId;

                if($this->usuvalidaId == "")
                {
                    $this->usuvalidaId=0;
                }

                if($this->fechaValida == "")
                {
                    $this->fechaValida=date('Y-m-d H:i:s');
                }

                $this->success = true;
            }


        }
        elseif ($usuarioId != "")
        {

            $this->registroId = $registroId;

            $RegistroMySqlDAO = new RegistroMySqlDAO();
            $Registro = $RegistroMySqlDAO->queryByUsuarioId($usuarioId);
            $Registro = $Registro[0];


            if ($Registro != "" && $Registro != null)
            {


                $this->registroId = $Registro->registroId;
                $this->nombre = $Registro->nombre;
                $this->email = $Registro->email;
                $this->puntoventaId = $Registro->puntoventaId;
                $this->estado = $Registro->estado;
                $this->usuarioId = $Registro->usuarioId;
                $this->claveActiva = $Registro->claveActiva;
                $this->creditos = $Registro->creditos;
                $this->creditosBase = $Registro->creditosBase;
                $this->celular = $Registro->celular;
                $this->ciudad = $Registro->ciudad;
                $this->creditosAnt = $Registro->creditosAnt;
                $this->creditosBaseAnt = $Registro->creditosBaseAnt;
                $this->ciudadId = $Registro->ciudadId;
                $this->mandante = $Registro->mandante;
                $this->casino = $Registro->casino;
                $this->casinoBase = $Registro->casinoBase;
                $this->fechaCasino = $Registro->fechaCasino;
                $this->preregistroId = $Registro->preregistroId;
                $this->creditosBono = $Registro->creditosBono;
                $this->creditosBonoAnt = $Registro->creditosBonoAnt;
                $this->ocupacion = $Registro->ocupacion;
                $this->rangoingresoId = $Registro->rangoingresoId;
                $this->origenFondos = $Registro->origenFondos;
                $this->paisnacimId = $Registro->paisnacimId;
                $this->cedula = $Registro->cedula;
                $this->nombre1 = $Registro->nombre1;
                $this->nombre2 = $Registro->nombre2;
                $this->apellido1 = $Registro->apellido1;
                $this->apellido2 = $Registro->apellido2;
                $this->sexo = $Registro->sexo;
                $this->direccion = $Registro->direccion;
                $this->telefono = $Registro->telefono;
                $this->ciudnacimId = $Registro->ciudnacimId;
                $this->nacionalidadId = $Registro->nacionalidadId;
                $this->estadoValida = $Registro->estadoValida;
                $this->usuvalidaId = $Registro->usuvalidaId;
                $this->fechaValida = $Registro->fechaValida;
                $this->dirIp = $Registro->dirIp;
                $this->tipoDoc = $Registro->tipoDoc;
                $this->ciudexpedId = $Registro->ciudexpedId;
                $this->fechaExped = $Registro->fechaExped;
                $this->codigoPostal = $Registro->codigoPostal;
                $this->ocupacionId = $Registro->ocupacionId;
                $this->origenfondosId = $Registro->origenfondosId;
                $this->afiliadorId = $Registro->afiliadorId;
                $this->saldoBonosLiberados = $Registro->saldoBonosLiberados;
                $this->saldoCasinoBonos = $Registro->saldoCasinoBonos;

                $this->bannerId = $Registro->bannerId;
                $this->linkId = $Registro->linkId;

                $this->codpromocionalId = $Registro->codpromocionalId;

                if($this->usuvalidaId == "")
                {
                    $this->usuvalidaId=0;
                }

                if($this->fechaValida == "")
                {
                    $this->fechaValida=date('Y-m-d H:i:s');
                }

                $this->success = true;
            }

        }


    }





    /**
     * Obtener el campo nombre de un objeto
     *
     * @return String nombre nombre
     *
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Modificar el campo 'nombre' de un objeto
     *
     * @param String $nombre nombre.
     * @return void función que retorna el nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     * Obtener el campo email de un objeto
     *
     * @return String email email
     *
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Modificar el campo 'email' de un objeto
     *
     * @param String $email email
     * @return void función que retorna el email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Obtener el campo puntoventaId de un objeto
     *
     * @return String puntoventaId puntoventaId
     *
     */
    public function getPuntoventaId()
    {
        return $this->puntoventaId;
    }

    /**
     * Modificar el campo 'puntoventaId' de un objeto
     *
     * @param String $puntoventaId puntoventaId
     * @return void función que retorna el puntoVentaId
     */
    public function setPuntoventaId($puntoventaId)
    {
        $this->puntoventaId = $puntoventaId;
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
     * @return void función que retorna el estado
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    /**
     * Obtener el campo claveActiva de un objeto
     *
     * @return String claveActiva claveActiva
     *
     */
    public function getClaveActiva()
    {
        return $this->claveActiva;
    }

    /**
     * Modificar el campo 'claveActiva' de un objeto
     *
     * @param String $claveActiva claveActiva
     * @return void función que retorna la claveActiva
     */
    public function setClaveActiva($claveActiva)
    {
        $this->claveActiva = $claveActiva;
    }

    /**
     * Obtener el campo creditos de un objeto
     *
     * @return String creditos creditos
     *
     */
    public function getCreditos()
    {
        return $this->creditos;
    }

    /**
     * Modificar el campo 'creditos' de un objeto
     *
     * @param String $creditos creditos
     * @return void función que retorna los creditos
     */
    public function setCreditos($creditos)
    {
        $this->creditos = $creditos;
    }

    /**
     * Obtener el campo creditosBase de un objeto
     *
     * @return String creditosBase creditosBase
     *
     */
    public function getCreditosBase()
    {
        return $this->creditosBase;
    }

    /**
     * Modificar el campo 'creditosBase' de un objeto
     *
     * @param String $creditosBase creditosBase
     * @return void función que retorna el creditoBase
     */
    public function setCreditosBase($creditosBase)
    {
        $this->creditosBase = $creditosBase;
    }

    /**
     * Obtener el campo celular de un objeto
     *
     * @return String celular celular
     *
     */
    public function getCelular()
    {
        return $this->celular;
    }

    /**
     * Modificar el campo 'celular' de un objeto
     *
     * @param String $celular celular
     * @return void función que retorna el celular
     */
    public function setCelular($celular)
    {
        $this->celular = $celular;
    }

    /**
     * Obtener el campo ciudad de un objeto
     *
     * @return String ciudad ciudad
     *
     */
    public function getCiudad()
    {
        return $this->ciudad;
    }

    /**
     * Modificar el campo 'ciudad' de un objeto
     *
     * @param String $ciudad ciudad
     *
     * @return void función que retorna la ciudad
     *
     */
    public function setCiudad($ciudad)
    {
        $this->ciudad = $ciudad;
    }

    /**
     * Obtener el campo creditosAnt de un objeto
     *
     * @return String creditosAnt creditosAnt
     *
     */
    public function getCreditosAnt()
    {
        return $this->creditosAnt;
    }

    /**
     * Modificar el campo 'creditosAnt' de un objeto
     *
     * @param String $creditosAnt creditosAnt
     *
     * @return void función que retorna los creditosAnt
     *
     */
    public function setCreditosAnt($creditosAnt)
    {
        $this->creditosAnt = $creditosAnt;
    }

    /**
     * Obtener el campo creditosBaseAnt de un objeto
     *
     * @return String creditosBaseAnt creditosBaseAnt
     *
     */
    public function getCreditosBaseAnt()
    {
        return $this->creditosBaseAnt;
    }

    /**
     * Modificar el campo 'creditosBaseAnt' de un objeto
     *
     * @param String $creditosBaseAnt creditosBaseAnt
     *
     * @return void función que retorna el creditoBaseAnt
     *
     */
    public function setCreditosBaseAnt($creditosBaseAnt)
    {
        $this->creditosBaseAnt = $creditosBaseAnt;
    }

    /**
     * Obtener el campo ciudadId de un objeto
     *
     * @return String ciudadId ciudadId
     *
     */
    public function getCiudadId()
    {
        return $this->ciudadId;
    }

    /**
     * Modificar el campo 'ciudadId' de un objeto
     *
     * @param String $ciudadId ciudadId
     *
     * @return void función que retorna la ciudadId
     *
     */
    public function setCiudadId($ciudadId)
    {
        $this->ciudadId = $ciudadId;
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
     * @return void función que retorna el mandante
     *
     */
    public function setMandante($mandante)
    {
        $this->mandante = $mandante;
    }

    /**
     * Obtener el campo casino de un objeto
     *
     * @return String casino casino
     *
     */
    public function getCasino()
    {
        return $this->casino;
    }

    /**
     * Modificar el campo 'casino' de un objeto
     *
     * @param String $casino casino
     *
     * @return void función que retorna el casino
     *
     */
    public function setCasino($casino)
    {
        $this->casino = $casino;
    }

    /**
     * Obtener el campo getCasinoBase de un objeto
     *
     * @return String getCasinoBase getCasinoBase
     *
     */
    public function getCasinoBase()
    {
        return $this->getCasinoBase;
    }

    /**
     * Modificar el campo 'casinoBase' de un objeto
     *
     * @param String $casinoBase casinoBase
     *
     * @return void función que retorna el casinoBase
     *
     */
    public function setCasinoBase($casinoBase)
    {
        $this->casinoBase = $casinoBase;
    }

    /**
     * Obtener el campo fechaCasino de un objeto
     *
     * @return String fechaCasino fechaCasino
     *
     */
    public function getFechaCasino()
    {
        return $this->fechaCasino;
    }

    /**
     * Modificar el campo 'fechaCasino' de un objeto
     *
     * @param String $fechaCasino fechaCasino
     *
     * @return void función que retorna la fechaCasino
     *
     */
    public function setFechaCasino($fechaCasino)
    {
        $this->fechaCasino = $fechaCasino;
    }

    /**
     * Obtener el campo preregistroId de un objeto
     *
     * @return String preregistroId preregistroId
     *
     */
    public function getPreregistroId()
    {
        return $this->preregistroId;
    }

    /**
     * Modificar el campo 'preregistroId' de un objeto
     *
     * @param String $preregistroId preregistroId
     *
     * @return void función que retorna el preregistroId
     *
     */
    public function setPreregistroId($preregistroId)
    {
        $this->preregistroId = $preregistroId;
    }

    /**
     * Obtener el campo creditosBono de un objeto
     *
     * @return String creditosBono creditosBono
     *
     */
    public function getCreditosBono()
    {
        return $this->creditosBono;
    }

    /**
     * Modificar el campo 'creditosBono' de un objeto
     *
     * @param String $creditosBono creditosBono
     *
     * @return void función que retorna el creditosBono
     *
     */
    public function setCreditosBono($creditosBono)
    {
        $this->creditosBono = $creditosBono;
    }

    /**
     * Obtener el campo creditosBonoAnt de un objeto
     *
     * @return String creditosBonoAnt creditosBonoAnt
     *
     */
    public function getCreditosBonoAnt()
    {
        return $this->creditosBonoAnt;
    }

    /**
     * Modificar el campo 'creditosBonoAnt' de un objeto
     *
     * @param String $creditosBonoAnt creditosBonoAnt
     *
     * @return void función que retorna el creditosBonoAnt
     *
     */
    public function setCreditosBonoAnt($creditosBonoAnt)
    {
        $this->creditosBonoAnt = $creditosBonoAnt;
    }

    /**
     * Obtener el campo ocupacion de un objeto
     *
     * @return String ocupacion ocupacion
     *
     */
    public function getOcupacion()
    {
        return $this->ocupacion;
    }

    /**
     * Modificar el campo 'ocupacion' de un objeto
     *
     * @param String $ocupacion ocupacion
     *
     * @return void función que retorna ocupación
     *
     */
    public function setOcupacion($ocupacion)
    {
        $this->ocupacion = $ocupacion;
    }

    /**
     * Obtener el campo rangoingresoId de un objeto
     *
     * @return String rangoingresoId rangoingresoId
     *
     */
    public function getRangoingresoId()
    {
        return $this->rangoingresoId;
    }

    /**
     * Modificar el campo 'rangoingresoId' de un objeto
     *
     * @param String $rangoingresoId rangoingresoId
     *
     * @return void función que retorna rangoingresoId
     *
     */
    public function setRangoingresoId($rangoingresoId)
    {
        $this->rangoingresoId = $rangoingresoId;
    }

    /**
     * Obtener el campo origenFondos de un objeto
     *
     * @return String origenFondos origenFondos
     *
     */
    public function getOrigenFondos()
    {
        return $this->origenFondos;
    }

    /**
     * Modificar el campo 'origenFondos' de un objeto
     *
     * @param String $origenFondos origenFondos
     *
     * @return void función que retorna origenFondos
     *
     */
    public function setOrigenFondos($origenFondos)
    {
        $this->origenFondos = $origenFondos;
    }

    /**
     * Obtener el campo paisnacimId de un objeto
     *
     * @return String paisnacimId paisnacimId
     *
     */
    public function getPaisnacimId()
    {
        return $this->paisnacimId;
    }

    /**
     * Modificar el campo 'paisnacimId' de un objeto
     *
     * @param String $paisnacimId paisnacimIds
     *
     * @return void función que retorna paisnacimIds
     *
     */
    public function setPaisnacimId($paisnacimId)
    {
        $this->paisnacimId = $paisnacimId;
    }

    /**
     * Obtener el campo cedula de un objeto
     *
     * @return String cedula cedula
     *
     */
    public function getCedula()
    {
        return $this->cedula;
    }

    /**
     * Modificar el campo 'cedula' de un objeto
     *
     * @param String $cedula cedula
     *
     * @return void función que retorna cedula
     *
     */
    public function setCedula($cedula)
    {
        $this->cedula = $cedula;
    }

    /**
     * Obtener el campo nombre1 de un objeto
     *
     * @return String nombre1 nombre1
     *
     */
    public function getNombre1()
    {
        return $this->nombre1;
    }

    /**
     * Modificar el campo 'nombre1' de un objeto
     *
     * @param String $nombre1 nombre1
     *
     * @return void función que retorna nombre1
     *
     */
    public function setNombre1($nombre1)
    {
        $this->nombre1 = $nombre1;
    }

    /**
     * Obtener el campo nombre2 de un objeto
     *
     * @return String nombre2 nombre2
     *
     */
    public function getNombre2()
    {
        return $this->nombre2;
    }

    /**
     * Modificar el campo 'nombre2' de un objeto
     *
     * @param String $nombre2 nombre2
     *
     * @return void función que retorna nombre2
     *
     */
    public function setNombre2($nombre2)
    {
        $this->nombre2 = $nombre2;
    }

    /**
     * Obtener el campo apellido1 de un objeto
     *
     * @return String apellido1 apellido1
     *
     */
    public function getApellido1()
    {
        return $this->apellido1;
    }

    /**
     * Modificar el campo 'apellido1' de un objeto
     *
     * @param String $apellido1 apellido1
     *
     * @return void función que retorna apellido1
     *
     */
    public function setApellido1($apellido1)
    {
        $this->apellido1 = $apellido1;
    }

    /**
     * Obtener el campo apellido2 de un objeto
     *
     * @return String apellido2 apellido2
     *
     */
    public function getApellido2()
    {
        return $this->apellido2;
    }

    /**
     * Modificar el campo 'apellido2' de un objeto
     *
     * @param String $apellido2 apellido2
     *
     * @return void función que retorna apellido2
     *
     */
    public function setApellido2($apellido2)
    {
        $this->apellido2 = $apellido2;
    }

    /**
     * Obtener el campo sexo de un objeto
     *
     * @return String sexo sexo
     *
     */
    public function getSexo()
    {
        return $this->sexo;
    }

    /**
     * Modificar el campo 'sexo' de un objeto
     *
     * @param String $sexo sexo
     *
     * @return void función que retorna sexo
     *
     */
    public function setSexo($sexo)
    {
        $this->sexo = $sexo;
    }

    /**
     * Obtener el campo direccion de un objeto
     *
     * @return String direccion direccion
     *
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Modificar el campo 'direccion' de un objeto
     *
     * @param String $direccion direccion
     *
     * @return void función que retorna direccion
     *
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;
    }

    /**
     * Obtener el campo telefono de un objeto
     *
     * @return String telefono telefono
     *
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Modificar el campo 'telefono' de un objeto
     *
     * @param String $telefono telefono
     *
     * @return void función que retorna telefono
     *
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    }

    /**
     * Obtener el campo ciudnacimId de un objeto
     *
     * @return String ciudnacimId ciudnacimId
     *
     */
    public function getCiudnacimId()
    {
        return $this->ciudnacimId;
    }

    /**
     * Modificar el campo 'ciudnacimId' de un objeto
     *
     * @param String $ciudnacimId ciudnacimId
     *
     * @return void función que retorna ciudnacimId
     *
     */
    public function setCiudnacimId($ciudnacimId)
    {
        $this->ciudnacimId = $ciudnacimId;
    }

    /**
     * Obtener el campo nacionalidadId de un objeto
     *
     * @return String nacionalidadId nacionalidadId
     *
     */
    public function getNacionalidadId()
    {
        return $this->nacionalidadId;
    }

    /**
     * Modificar el campo 'nacionalidadId' de un objeto
     *
     * @param String $nacionalidadId nacionalidadId
     *
     * @return void función que retorna nacionalidadId
     *
     */
    public function setNacionalidadId($nacionalidadId)
    {
        $this->nacionalidadId = $nacionalidadId;
    }

    /**
     * Obtener el campo estadoValida de un objeto
     *
     * @return String estadoValida estadoValida
     *
     */
    public function getEstadoValida()
    {
        return $this->estadoValida;
    }

    /**
     * Modificar el campo 'estadoValida' de un objeto
     *
     * @param String $estadoValida estadoValida
     *
     * @return void función que retorna estadoValida
     *
     */
    public function setEstadoValida($estadoValida)
    {
        $this->estadoValida = $estadoValida;
    }

    /**
     * Obtener el campo usuvalidaId de un objeto
     *
     * @return String usuvalidaId usuvalidaId
     *
     */
    public function getUsuvalidaId()
    {
        return $this->usuvalidaId;
    }

    /**
     * Modificar el campo 'usuvalidaId' de un objeto
     *
     * @param String $usuvalidaId usuvalidaId
     *
     * @return void función que retorna usuvalidaId
     *
     */
    public function setUsuvalidaId($usuvalidaId)
    {
        $this->usuvalidaId = $usuvalidaId;
    }

    /**
     * Obtener el campo fechaValida de un objeto
     *
     * @return String fechaValida fechaValida
     *
     */
    public function getFechaValida()
    {
        return $this->fechaValida;
    }

    /**
     * Modificar el campo 'fechaValida' de un objeto
     *
     * @param String $fechaValida fechaValida
     *
     * @return void función que retorna fechaValida
     *
     */
    public function setFechaValida($fechaValida)
    {
        $this->fechaValida = $fechaValida;
    }

    /**
     * Obtener el campo dirIp de un objeto
     *
     * @return String dirIp dirIp
     *
     */
    public function getDirIp()
    {
        return $this->dirIp;
    }

    /**
     * Modificar el campo 'dirIp' de un objeto
     *
     * @param String $dirIp dirIp
     *
     * @return void función que retorna dirIp
     *
     */
    public function setDirIp($dirIp)
    {
        $this->dirIp = $dirIp;
    }

    /**
     * Obtener el campo tipoDoc de un objeto
     *
     * @return String tipoDoc tipoDoc
     *
     */
    public function getTipoDoc()
    {
        return $this->tipoDoc;
    }

    /**
     * Modificar el campo 'tipoDoc' de un objeto
     *
     * @param String $tipoDoc tipoDoc
     *
     * @return void función que retorna tipoDoc
     *
     */
    public function setTipoDoc($tipoDoc)
    {
        $this->tipoDoc = $tipoDoc;
    }

    /**
     * Obtener el campo ciudexpedId de un objeto
     *
     * @return String ciudexpedId ciudexpedId
     *
     */
    public function getCiudexpedId()
    {
        return $this->ciudexpedId;
    }

    /**
     * Modificar el campo 'ciudexpedId' de un objeto
     *
     * @param String $ciudexpedId ciudexpedId
     *
     * @return void función que retorna ciudexpeId
     *
     */
    public function setCiudexpedId($ciudexpedId)
    {
        $this->ciudexpedId = $ciudexpedId;
    }

    /**
     * Obtener el campo fechaExped de un objeto
     *
     * @return String fechaExped fechaExped
     *
     */
    public function getFechaExped()
    {
        return $this->fechaExped;
    }

    /**
     * Modificar el campo 'fechaExped' de un objeto
     *
     * @param String $fechaExped fechaExped
     *
     * @return void función que retorna fechaExped
     *
     */
    public function setFechaExped($fechaExped)
    {
        $this->fechaExped = $fechaExped;
    }

    /**
     * Obtener el campo codigoPostal de un objeto
     *
     * @return String codigoPostal codigoPostal
     *
     */
    public function getCodigoPostal()
    {
        return $this->codigoPostal;
    }

    /**
     * Modificar el campo 'codigoPostal' de un objeto
     *
     * @param String $codigoPostal codigoPostal
     *
     * @return void función que retorna codigoPostal
     *
     */
    public function setCodigoPostal($codigoPostal)
    {
        $this->codigoPostal = $codigoPostal;
    }

    /**
     * Obtener el campo ocupacionId de un objeto
     *
     * @return String ocupacionId ocupacionId
     *
     */
    public function getOcupacionId()
    {
        return $this->ocupacionId;
    }

    /**
     * Modificar el campo 'ocupacionId' de un objeto
     *
     * @param String $ocupacionId ocupacionId
     *
     * @return void función que retorna ocupacionId
     *
     */
    public function setOcupacionId($ocupacionId)
    {
        $this->ocupacionId = $ocupacionId;
    }

    /**
     * Obtener el campo origenfondosId de un objeto
     *
     * @return String origenfondosId origenfondosId
     *
     */
    public function getOrigenfondosId()
    {
        return $this->origenfondosId;
    }

    /**
     * Obtener el campo afiliadorId de un objeto
     *
     * @return String afiliadorId afiliadorId
     *
     */    public function getAfiliadorId()
    {
        return $this->afiliadorId;
    }

    /**
     * Modificar el campo 'afiliadorId' de un objeto
     *
     * @param String $afiliadorId afiliadorId
     *
     * @return void función que retorna afiliadorId
     *
     */
    public function setAfiliadorId($afiliadorId)
    {
        $this->afiliadorId = $afiliadorId;
    }

    /**
     * Obtener el campo saldoBonosLiberados de un objeto Registro
     * @return mixed
     */
    public function getSaldoBonosLiberados()
    {
        return $this->saldoBonosLiberados;
    }

    /**
     * Modificar el campo 'saldoBonosLiberados' de un objeto Registro
     * @param mixed $saldoBonosLiberados esta variable corresponde al saldo liberado de los bonos
     * @return void función que retorna saldoBonosLiberados
     */
    public function setSaldoBonosLiberados($saldoBonosLiberados)
    {
        $this->saldoBonosLiberados = $saldoBonosLiberados;
    }

    /**
     * Obtener el campo saldoCasinoBonos de un objeto Registro
     * @return mixed
     */
    public function getSaldoCasinoBonos()
    {
        return $this->saldoCasinoBonos;
    }

    /**
     * Modificar el campo 'saldoCasinoBonos' de un objeto Registro
     * @param mixed $saldoCasinoBonos esta variable corresponde al saldo de los bonos de casino
     *
     * @return void función que retorna saldoCasinosBonos
     */
    public function setSaldoCasinoBonos($saldoCasinoBonos)
    {
        $this->saldoCasinoBonos = $saldoCasinoBonos;
    }

    /**
     * obtener el campo bannerId de un objeto Registro
     * @return mixed
     */
    public function getBannerId()
    {
        return $this->bannerId;
    }

    /**
     * Modificar el campo 'bannerId' de un objeto Registro
     * @param mixed $bannerId esta variable corresponde a bannerId
     *
     * @return void función que retorna bannerId
     */
    public function setBannerId($bannerId)
    {
        $this->bannerId = $bannerId;
    }

    /**
     * obtener el campo linkId de un objeto Registro
     * @return mixed
     */
    public function getLinkId()
    {
        return $this->linkId;
    }

    /**
     * Modificar el campo 'linkId' de un objeto Registro
     * @param mixed $linkId esta variable corresponde a linkId
     *
     * @return void función que retorna linkId
     */
    public function setLinkId($linkId)
    {
        $this->linkId = $linkId;
    }

    /**
     * obtener el campo codpromocionalId de un objeto Registro
     * @return mixed
     */
    public function getCodpromocionalId()
    {
        return $this->codpromocionalId;
    }

    /**
     * Modificar el campo 'codpromocionalId' de un objeto Registro
     * @param mixed $codpromocionalId esta variable corresponde al id de un código promocional
     *
     * @return void función que retorna codpromocionalId
     */
    public function setCodpromocionalId($codpromocionalId)
    {
        $this->codpromocionalId = $codpromocionalId;
    }





    /**
     * Modificar el campo 'origenfondosId' de un objeto
     *
     * @param String $origenfondosId origenfondosId
     *
     * @return void función que retorna origenfondosId
     *
     */
    public function setOrigenfondosId($origenfondosId)
    {
        $this->origenfondosId = $origenfondosId;
    }

    /**
     * Consultar en las base de datos si la cédula del registro ya existe
     *
     * @return boolean result
     *
     */
    public function existeCedula()
    {
        if($this->mandante == '8'){
            $arrayCedulas= array('0917821886','0704657865','0703277350','1103382618','0705297026','1105124463','1716567530','0952257194','1105794901','1102758776','0915615942','1103259840','0951881168','0950744144','0704170604','1104776081','1105797318','1105962094','1105684508','1102766050','0803196591','1716784218','1103092514','0950511865','1104909419','1714143086','1103636526','1715815856','470130127','1106069832','1104135056','102473392','1709908477','1103623987','1103283980','2300261662','1150214615','1104315104','1711820637','1103229587','1715219950','1103967285','1104227911','1150117198','1104680556','1105214785','1102986443','0702082785','763088630','1103549117','0955899109','2100461058','0919149334','0533722113','1104789415','1102174370','0702076449','1105581795','1752165140','2100679246','1310031123','0928828896','1308997731','0954644343','1094929359','1113305034','11060167927','1104582091','1755662315','1309432928','1708777121','2150150874','2136436902','0801207662','0951908235','1150120903','29814066','79946451','2100806880','2100525322','0704848563','1103180228','0953240819','43972432','1045140881','94282978','2100905237','1000759668','1719525760','1717337990','2100508304','0703101626','0915859508','2100160726','1113594278','66661395','1087647306','1003562178','1100619541','1002048252','0920268109','2221295415','0924133002','1104582406','1103094932','80800509','1113302771','0914048822','10697696','1116248534','94368512','31436909','1006492408','1105149999','1104809130','1105839185','1103139539','0926979303','1026151735','6429333','1114119908','59652202','0930563986','0923363345','1150438867','1104201254','15957430','94308657','6138070','0919416347','0929245009','6452593','1116240698','31419201','1113307297','1113305403','51736391','1004369733','1113309958','38756050','66770260','1112778738','14896237','31657534','1094918179','1007798863','94150633','66804120','1116244454','16230367','41893077','2659544','1006327207','94165066','0102059912','0102898012','1716276132','0301071163','1104500747','66882241','66930974','41699490','51691521','66661766','31178822','31997764','38886189','1116251570','1116258573','31156869','1111787959','80718773','1014187186','1103619373','1104041965','1106219825','1719654475','1726629056','0914826466','1150020798','1314749035','1002961041','1722543368','0801611005','0105154827','0400818860','1003653845','0102452976','1716451057','0701258113','1310563083','0801831124','1725853095','0103340337','1064426346','0921075685','1002968285','1713853156','65751346','0921159729','1301904015','0603314576','1724831175','1311780470','1206559278','0931389019','1720763893','1723097653','0916543309','6116311','16549897','29678259','1112303102','98216070','1112101639','1116256988','16511441','1113639748','1112629257','6138987','1112227088','1112783517','94151726','94145081','66710891','1115193379','6161332','1087555569','66684458','1074184756','1720574068','1722082558','1313128626','0704687920','1716306996','0106025208','0222288864','1307062983','1717166613','0501777569','0104854195','1717443772','1722535599','0955793005','1315852416','0906326327','1712195237','0702707068','1350807598','0940023883','0302722723','0930385315','4684450085','1310639990','0302862693','1105775215','1723205827','1304570193','1716040017','1600622466','0923493399','0955223813','31569564','6134969','94480822','0604519785','9845079','14676925','1720949070','1113622670','29667662','0803020601','1850089150','0604404665','1205608373','1024516403','10013873','1116255124','0401767272','0401450572','0401297742','1309384343','1715608855','0922994967','1716908643','0925705790','1104298649','1106174368','1722977343','1718430349','10010510180','0704012616','1104516735','0802312835','1201775929','1722702568','1711108652','1719962642','1713436366','1802814994','1804666346','1722341086','1001271459','1204286734','1706035977','1003657416','0704258834','1709670796','0201769759','1717872830','29307408','6213048','48656308','29309331','0941834939','1200844718','0912519022','1804416145','1724827967','1113304472','0917750846','18397096','1151958255','1004995526','42164867','1113037459','30354263','1726813734','0802552208','0954099362','1717872822','1757636574','1144151948','38757182','0915310106','1715688253','1207798420','0604217646','0705181675','0706684883','0911312460','0701583189','0706344660','0705514537','0923163554','1033657616','1711782838','1105076655','29819614','0401013297','1003072590','1708229099','1003972856','1719148221','0401583000','0503662868','1721008033','1719879023','1720214293','0951686724','1115182947','1716461080','1112965995','2515141','0913588404','1722575147','0951526425','0931448633','1115190038','1089935991','0953455516','1006493906','0928718576','0927293191','1722950050','1307163228','1722514005','1116726097','0606043693','0904020411','0106160979','1711443301','0561623362','0705716660','0953581600','0944321363','1006320965','1225090156','1004521093','1104211915','1225090448','1079092211','1193226351','94386266','1098337470','1088315264','0940195530','1088353089','19329117','50918175','1704867256','0705328268','1309761029','0930734041','0918004292','0941086027','1716547433','94390077','1116233406','1113640840','1055272575','94394591','14996516','16549990','36985094','0705214278','1094945249','0104050331','10690614','1314610674','29817133','34679126','16344097','15958361','1112302919','1701214140','1112303055','1316762772','1312322790','1716075716','1088292193','0958882271','1088318552','14957125','1103011155','1104605751','52464571','0502333321','0928399674','1716959844','66723619','1003214055','1306958347','1715430862','0706465192','0926351594','0922312707','6466158','1750221057','1713660969','1313834903','1722688101','2162226828','0502874993','94451196','1113311610','0956633366','1802713337','0106074884','1107085554','94229734','1717926362','79903306','14441224','94371697','1112303072','1089718908','94153352','1111776981','1115083658','6458739','94257047','48629574','1112301064','29659825','94392614','1113635180','2120126959','1202495642','1729490904','1112298582','2400303687','2104346466','1503304155','1203335359','0954557770','0924338515','0700366855','2100546601','0704664812','0918356338','1112303060','1113643547','1208001593','0704667658','1112303053','1720702769','0502970981','1717998755','0106100977');

            if(in_array($this->cedula,$arrayCedulas)){
                return true;
            }
        }

        $RegistroMySqlDAO = new RegistroMySqlDAO();
        $Registro = $RegistroMySqlDAO->queryByCedula($this->cedula,$this->mandante);


        if (oldCount($Registro) > 0) {
            return $Registro[0];
        }
        return false;

    }
/**
     * Consultar en la base de datos si el celular del registro ya existe
     *
     * @return boolean true si el celular existe, false en caso contrario
     */
    public function existeCelular()
    {
        $RegistroMySqlDAO = new RegistroMySqlDAO();
        $Registro = $RegistroMySqlDAO->queryByCelular($this->celular, $this->mandante);

        if (oldCount($Registro) > 0) {
            return $Registro[0];
        }
        return false;
    }


/**
     * Obtener registros personalizados de la base de datos.
     *
     * @param string $select Campos a seleccionar.
     * @param string $sidx Índice de ordenación.
     * @param string $sord Orden de clasificación (ascendente o descendente).
     * @param int $start Inicio de los registros.
     * @param int $limit Límite de registros.
     * @param array $filters Filtros a aplicar.
     * @param bool $searchOn Indica si la búsqueda está activada.
     * @param string $grouping Agrupación de registros (opcional).
     *
     * @return array Registros obtenidos.
     * @throws \Exception Si no existen registros.
     */
    public function getRegistroCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping = "")
    {

        $RegistroMySqlDAO = new RegistroMySqlDAO();

        $Registro = $RegistroMySqlDAO->queryRegistroCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping);

        if ($Registro != null && $Registro != "") {

            return $Registro;

        } else {
            throw new \Exception("No existe " . get_class($this), "01");
        }

    }






}

?>
