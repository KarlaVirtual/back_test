<?php namespace Backend\dto;
use Backend\mysql\UsuarioNotasMySqlDAO;
use Exception;
/** 
* Clase 'UsuarioNotas'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'UsuarioNotas'
* 
* Ejemplo de uso: 
* $UsuarioNotas = new UsuarioNotas();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class UsuarioNotas
{

    /**
    * Representación de la columna 'transsportlogId' de la tabla 'TranssportsbookLog'
    *
    * @var string
    */
    var $usunotaId;

    /**
    * Representación de la columna 'transsportlogId' de la tabla 'TranssportsbookLog'
    *
    * @var string
    */
    var $nota;

    /**
    * Representación de la columna 'transsportlogId' de la tabla 'TranssportsbookLog'
    *
    * @var string
    */
    var $usuarioId;

    /**
    * Representación de la columna 'transsportlogId' de la tabla 'TranssportsbookLog'
    *
    * @var string
    */
    var $usucreaId;

    /**
    * Representación de la columna 'transsportlogId' de la tabla 'TranssportsbookLog'
    *
    * @var string
    */
    var $usumodifId;


    /**
    * Constructor de clase
    *
    *
    * @param String $usunotaId usunotaId
    *
    * @return no
    * @throws Exception si UsuarioNotas no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($usunotaId="")
    {
        if ($usunotaId != "") 
        {

            $this->usunotaId = $usunotaId;

            $UsuarioNotasMySqlDAO = new UsuarioNotasMySqlDAO();

            $UsuarioNotas = $UsuarioNotasMySqlDAO->load($usunotaId);

            if ($UsuarioNotas != null && $UsuarioNotas != "") 
            {
                $this->usunotaId = $UsuarioNotas->usunotaId;
                $this->nota = $UsuarioNotas->nota;
                $this->usuarioId = $UsuarioNotas->usuarioId;
                $this->usucreaId = $UsuarioNotas->usucreaId;
                $this->usumodifId = $UsuarioNotas->usumodifId;
            }
            else 
            {
                throw new Exception("No existe " . get_class($this), "26");
            }
        }

    }





    /**
     * Obtener el campo nota de un objeto
     *
     * @return String nota nota
     * 
     */
    public function getNota()
    {
        return $this->nota;
    }

    /**
     * Modificar el campo 'nota' de un objeto
     *
     * @param String $nota nota
     *
     * @return no
     *
     */
    public function setNota($nota)
    {
        $this->nota = $nota;
    }

    /**
     * Obtener el campo usuarioId de un objeto
     *
     * @return String usuarioId usuarioId
     * 
     */
    public function getUsuarioId()
    {
        return $this->usuarioId;
    }

    /**
     * Modificar el campo 'usuarioId' de un objeto
     *
     * @param String $usuarioId usuarioId
     *
     * @return no
     *
     */
    public function setUsuarioId($usuarioId)
    {
        $this->usuarioId = $usuarioId;
    }

    /**
     * Obtener el campo usucreaId de un objeto
     *
     * @return String usucreaId usucreaId
     * 
     */
    public function getUsucreaId()
    {
        return $this->usucreaId;
    }

    /**
     * Modificar el campo 'usucreaId' de un objeto
     *
     * @param String $usucreaId usucreaId
     *
     * @return no
     *
     */
    public function setUsucreaId($usucreaId)
    {
        $this->usucreaId = $usucreaId;
    }

    /**
     * Obtener el campo usumodifId de un objeto
     *
     * @return String usumodifId usumodifId
     * 
     */

    public function getUsumodifId()
    {
        return $this->usumodifId;
    }

    /**
     * Modificar el campo 'usumodifId' de un objeto
     *
     * @param String $usumodifId usumodifId
     *
     * @return no
     *
     */
    public function setUsumodifId($usumodifId)
    {
        $this->usumodifId = $usumodifId;
    }


}

?>
