<?php 
namespace Backend\dto;
use Backend\mysql\ContactoMySqlDAO;
use Exception;

/**
* Clase 'Contacto'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'Contacto'
* 
* Ejemplo de uso: 
* $Contacto = new Contacto();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class Contacto
{
		
    /**
    * Representación de la columna 'contactoId' de la tabla 'Contacto'
    *
    * @var string
    */ 
	var $contactoId;
		
    /**
    * Representación de la columna 'nombre' de la tabla 'Contacto'
    *
    * @var string
    */ 
	var $nombre;
		
    /**
    * Representación de la columna 'email' de la tabla 'Contacto'
    *
    * @var string
    */ 
	var $email;
		
    /**
    * Representación de la columna 'telefono' de la tabla 'Contacto'
    *
    * @var string
    */ 
	var $telefono;
		
    /**
    * Representación de la columna 'mensaje' de la tabla 'Contacto'
    *
    * @var string
    */ 
	var $mensaje;
		
    /**
    * Representación de la columna 'fechaCrea' de la tabla 'Contacto'
    *
    * @var string
    */ 
	var $fechaCrea;
		
    /**
    * Representación de la columna 'mandante' de la tabla 'Contacto'
    *
    * @var string
    */
	var $mandante;

    /**
     * Representación de la columna 'tipo' de la tabla 'Contacto'
     *
     * @var string
     */
    var $tipo;

    /**
     * Representación de la columna 'tipo' de la tabla 'Contacto'
     *
     * @var string
     */
    var $paisId;

    /**
     * Contacto constructor.
     * @param string $contactoId
     */
    public function __construct($contactoId=0)
    {
        $this->contactoId = $contactoId;
    }

    /**
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param string $nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * @param string $telefono
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    }

    /**
     * @return string
     */
    public function getMensaje()
    {
        return $this->mensaje;
    }

    /**
     * @param string $mensaje
     */
    public function setMensaje($mensaje)
    {
        $this->mensaje = $mensaje;
    }

    /**
     * @return string
     */
    public function getFechaCrea()
    {
        return $this->fechaCrea;
    }

    /**
     * @param string $fechaCrea
     */
    public function setFechaCrea($fechaCrea)
    {
        $this->fechaCrea = $fechaCrea;
    }

    /**
     * @return string
     */
    public function getMandante()
    {
        return $this->mandante;
    }

    /**
     * @param string $mandante
     */
    public function setMandante($mandante)
    {
        $this->mandante = $mandante;
    }

    /**
     * @return string
     */
    public function getContactoId()
    {
        return $this->contactoId;
    }

    /**
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @param string $tipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    /**
     * @return string
     */
    public function getPaisId()
    {
        return $this->paisId;
    }

    /**
     * @param string $paisId
     */
    public function setPaisId($paisId)
    {
        $this->paisId = $paisId;
    }




    /**
     * Get Domain object by primry key
     *
     * @param
     * @return int
     */
    public function getContactosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $ContactoMySqlDAO = new ContactoMySqlDAO();

        $contactos = $ContactoMySqlDAO->queryContactosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($contactos != null && $contactos != "") {

            return $contactos;

        } else {
            throw new Exception("No existe " . get_class($this), "102");
        }

    }
		
}
?>
