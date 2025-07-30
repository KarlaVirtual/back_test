<?php 
namespace Backend\dto;

use Backend\mysql\ContactoComercialLogMySqlDAO;
use Backend\mysql\ContactoComercialMySqlDAO;
use Exception;

/**
 * Clase 'ContactoComercial'
 *
 * Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
 * para la tabla 'ContactoComercial'
 *
 * Ejemplo de uso:
 * $ContactoComercial = new ContactoComercial();
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class ContactoComercial
{
    /**
     * Representación de la columna 'contactocomId' de la tabla 'ContactoComercial'
     *
     * @var string
     */
    var $contactocomId;

    /**
     * Representación de la columna 'nombres' de la tabla 'ContactoComercial'
     *
     * @var string
     */
    var $nombres;

    /**
     * Representación de la columna 'apellidos' de la tabla 'ContactoComercial'
     *
     * @var string
     */
    var $apellidos;

    /**
     * Representación de la columna 'empresa' de la tabla 'ContactoComercial'
     *
     * @var string
     */
    var $empresa;

    /**
     * Representación de la columna 'email' de la tabla 'ContactoComercial'
     *
     * @var string
     */
    var $email;

    /**
     * Representación de la columna 'skype' de la tabla 'ContactoComercial'
     *
     * @var string
     */
    var $skype;

    /**
     * Representación de la columna 'paisId' de la tabla 'ContactoComercial'
     *
     * @var string
     */
    var $paisId;

    /**
     * Representación de la columna 'deptoId' de la tabla 'ContactoComercial'
     *
     * @var string
     */
    var $deptoId;

    /**
     * Representación de la columna 'direccion' de la tabla 'ContactoComercial'
     *
     * @var string
     */
    var $direccion;

    /**
     * Representación de la columna 'telefono' de la tabla 'ContactoComercial'
     *
     * @var string
     */
    var $telefono;

    /**
     * Representación de la columna 'observacion' de la tabla 'ContactoComercial'
     *
     * @var string
     */
    var $observacion;

    /**
     * Representación de la columna 'estado' de la tabla 'ContactoComercial'
     *
     * @var string
     */
    var $estado;

    /**
     * Representación de la columna 'fechaCrea' de la tabla 'ContactoComercial'
     *
     * @var string
     */
    var $fechaCrea;

    /**
     * Representación de la columna 'fechaModif' de la tabla 'ContactoComercial'
     *
     * @var string
     */
    var $fechaModif;

    /**
     * Representación de la columna 'usumodifId' de la tabla 'ContactoComercial'
     *
     * @var string
     */
    var $usumodifId;

    /**
     * Representación de la columna 'mandante' de la tabla 'ContactoComercial'
     *
     * @var string
     */
    var $mandante;

    /**
     * Representación de la columna 'tipo' de la tabla 'ContactoComercial'
     *
     * @var string
     */
    var $tipo;

    /**
     * ContactoComercial constructor.
     * @param $contactocomId
     */
    public function __construct($contactocomId = "")
    {

        if ($contactocomId != "") {

            $ContactoComercialMySqlDAO = new ContactoComercialMySqlDAO();

            $ContactoComercial = $ContactoComercialMySqlDAO->load($contactocomId);


            if ($ContactoComercial != null && $ContactoComercial != "") {
                $this->contactocomId = $ContactoComercial->contactocomId;
                $this->nombres = $ContactoComercial->nombres;
                $this->apellidos = $ContactoComercial->apellidos;
                $this->empresa = $ContactoComercial->empresa;
                $this->email = $ContactoComercial->email;
                $this->skype = $ContactoComercial->skype;
                $this->paisId = $ContactoComercial->paisId;
                $this->deptoId = $ContactoComercial->deptoId;
                $this->direccion = $ContactoComercial->direccion;
                $this->telefono = $ContactoComercial->telefono;
                $this->observacion = $ContactoComercial->observacion;
                $this->estado = $ContactoComercial->estado;
                $this->fechaCrea = $ContactoComercial->fechaCrea;
                $this->fechaModif = $ContactoComercial->fechaModif;
                $this->usumodifId = $ContactoComercial->usumodifId;
                $this->mandante = $ContactoComercial->mandante;
                $this->tipo = $ContactoComercial->tipo;
            } else {
                throw new Exception("No existe " . get_class($this), "82");
            }

        }


    }

    /**
     * @return mixed
     */
    public function getNombres()
    {
        return $this->nombres;
    }

    /**
     * @param mixed $nombres
     */
    public function setNombres($nombres)
    {
        $this->nombres = $nombres;
    }

    /**
     * @return mixed
     */
    public function getApellidos()
    {
        return $this->apellidos;
    }

    /**
     * @param mixed $apellidos
     */
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;
    }

    /**
     * @return mixed
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }

    /**
     * @param mixed $empresa
     */
    public function setEmpresa($empresa)
    {
        $this->empresa = $empresa;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getSkype()
    {
        return $this->skype;
    }

    /**
     * @param mixed $skype
     */
    public function setSkype($skype)
    {
        $this->skype = $skype;
    }

    /**
     * @return mixed
     */
    public function getPaisId()
    {
        return $this->paisId;
    }

    /**
     * @param mixed $paisId
     */
    public function setPaisId($paisId)
    {
        $this->paisId = $paisId;
    }

    /**
     * @return mixed
     */
    public function getDeptoId()
    {
        return $this->deptoId;
    }

    /**
     * @param mixed $deptoId
     */
    public function setDeptoId($deptoId)
    {
        $this->deptoId = $deptoId;
    }

    /**
     * @return mixed
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * @param mixed $direccion
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;
    }

    /**
     * @return mixed
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * @param mixed $telefono
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    }

    /**
     * @return mixed
     */
    public function getObservacion()
    {
        return $this->observacion;
    }

    /**
     * @param mixed $observacion
     */
    public function setObservacion($observacion)
    {
        $this->observacion = $observacion;
    }

    /**
     * @return mixed
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * @param mixed $estado
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    /**
     * @return mixed
     */
    public function getFechaCrea()
    {
        return $this->fechaCrea;
    }

    /**
     * @param mixed $fechaCrea
     */
    public function setFechaCrea($fechaCrea)
    {
        $this->fechaCrea = $fechaCrea;
    }

    /**
     * @return mixed
     */
    public function getFechaModif()
    {
        return $this->fechaModif;
    }

    /**
     * @param mixed $fechaModif
     */
    public function setFechaModif($fechaModif)
    {
        $this->fechaModif = $fechaModif;
    }

    /**
     * @return mixed
     */
    public function getUsumodifId()
    {
        return $this->usumodifId;
    }

    /**
     * @param mixed $usumodifId
     */
    public function setUsumodifId($usumodifId)
    {
        $this->usumodifId = $usumodifId;
    }

    /**
     * @return mixed
     */
    public function getMandante()
    {
        return $this->mandante;
    }

    /**
     * @param mixed $mandante
     */
    public function setMandante($mandante)
    {
        $this->mandante = $mandante;
    }

    /**
     * @return mixed
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @param mixed $tipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    /**
     * @return mixed
     */
    public function getContactocomId()
    {
        return $this->contactocomId;
    }


    /**
     * Get Domain object by primry key
     *
     * @param
     * @return int
     */
    public function getContactoComercialesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $ContactoComercialMySqlDAO = new ContactoComercialMySqlDAO();

        $contactos = $ContactoComercialMySqlDAO->queryContactoComercialesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($contactos != null && $contactos != "") {

            return $contactos;

        } else {
            throw new Exception("No existe " . get_class($this), "82");
        }

    }
}

?>