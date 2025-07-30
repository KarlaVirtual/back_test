<?php 
namespace Backend\dto;
use Backend\mysql\ContactoComercialLogMySqlDAO;
use Exception;/**
* Clase 'ContactoComercialLog'
*
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'ContactoComercialLog'
*
* Ejemplo de uso:
* $ContactoComercialLog = new ContactoComercialLog();
*
*
* @package ninguno
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public
* @see no
*
*/
class ContactoComercialLog
{

    /**
    * Representación de la columna 'contactocomlogId' de la tabla 'ContactoComercialLog'
    *
    * @var string
    */
	var $contactocomlogId;

    /**
    * Representación de la columna 'contactocomId' de la tabla 'ContactoComercialLog'
    *
    * @var string
    */
	var $contactocomId;

    /**
    * Representación de la columna 'fecha' de la tabla 'ContactoComercialLog'
    *
    * @var string
    */
	var $fecha;

    /**
    * Representación de la columna 'usuarioId' de la tabla 'ContactoComercialLog'
    *
    * @var string
    */
	var $usuarioId;

    /**
    * Representación de la columna 'texto' de la tabla 'ContactoComercialLog'
    *
    * @var string
    */
	var $texto;

    /**
    * Representación de la columna 'mandante' de la tabla 'ContactoComercialLog'
    *
    * @var string
    */
	var $mandante;
    /**
* ContactoComercialLog constructor.
     * @param $contactocomlogId
     */
    public function __construct($contactocomlogId)
    {
        if ($contactocomlogId != "") {

            $ContactoComercialLogMySqlDAO = new ContactoComercialLogMySqlDAO();

            $ContactoComercial = $ContactoComercialLogMySqlDAO->load($contactocomlogId);


            if ($ContactoComercial != null && $ContactoComercial != "") {
                $this->contactocomlogId = $ContactoComercial->contactocomlogId;
                $this->contactocomId = $ContactoComercial->contactocomId;
                $this->fecha = $ContactoComercial->fecha;
                $this->usuarioId = $ContactoComercial->usuarioId;
                $this->texto = $ContactoComercial->texto;
                $this->mandante = $ContactoComercial->mandante;
            } else {
                throw new Exception("No existe " . get_class($this), "83");
            }

        }
    }

    /**
     * @return mixed
     */
    public function getContactocomId()
    {
        return $this->contactocomId;
    }

    /**
     * @param mixed $contactocomId
     */
    public function setContactocomId($contactocomId)
    {
        $this->contactocomId = $contactocomId;
    }

    /**
     * @return mixed
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * @param mixed $fecha
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }

    /**
     * @return mixed
     */
    public function getUsuarioId()
    {
        return $this->usuarioId;
    }

    /**
     * @param mixed $usuarioId
     */
    public function setUsuarioId($usuarioId)
    {
        $this->usuarioId = $usuarioId;
    }

    /**
     * @return mixed
     */
    public function getTexto()
    {
        return $this->texto;
    }

    /**
     * @param mixed $texto
     */
    public function setTexto($texto)
    {
        $this->texto = $texto;
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
    public function getContactocomlogId()
    {
        return $this->contactocomlogId;
    }



    /**
     * Get Domain object by primry key
     *
     * @param
     * @return int
     */
    public function getContactoComercialesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $ContactoComercialLogMySqlDAO = new ContactoComercialLogMySqlDAO();

        $contactos = $ContactoComercialLogMySqlDAO->queryContactoComercialLogsCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($contactos != null && $contactos != "") {

            return $contactos;

        } else {
            throw new Exception("No existe " . get_class($this), "83");
        }

    }

}
?>