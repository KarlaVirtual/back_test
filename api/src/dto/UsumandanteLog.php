<?php namespace Backend\dto;
use Backend\mysql\UsumandanteLogMySqlDAO;
use Exception;
/** 
* Clase 'UsumandanteLog'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'UsumandanteLog'
* 
* Ejemplo de uso: 
* $UsumandanteLog = new UsumandanteLog();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class UsumandanteLog
{

    /**
    * Representación de la columna 'usutokenId' de la tabla 'UsuarioToken'
    *
    * @var string
    */
    var $usumandantelogId;

    /**
    * Representación de la columna 'usutokenId' de la tabla 'UsuarioToken'
    *
    * @var string
    */
    var $usuarioId;

    /**
    * Representación de la columna 'usutokenId' de la tabla 'UsuarioToken'
    *
    * @var string
    */
    var $proveedorId;

    /**
    * Representación de la columna 'usutokenId' de la tabla 'UsuarioToken'
    *
    * @var string
    */
    var $tipo;

    /**
    * Representación de la columna 'usutokenId' de la tabla 'UsuarioToken'
    *
    * @var string
    */
    var $valorAntes;

    /**
    * Representación de la columna 'usutokenId' de la tabla 'UsuarioToken'
    *
    * @var string
    */
    var $valorDespues;

    /**
    * Representación de la columna 'usutokenId' de la tabla 'UsuarioToken'
    *
    * @var string
    */
    var $usucreaId;

    /**
    * Representación de la columna 'usutokenId' de la tabla 'UsuarioToken'
    *
    * @var string
    */
    var $usumodifId;

    /**
    * Representación de la columna 'usutokenId' de la tabla 'UsuarioToken'
    *
    * @var string
    */
    var $fechaCrea;

    /**
    * Representación de la columna 'usutokenId' de la tabla 'UsuarioToken'
    *
    * @var string
    */
    var $fechaModif;





    /**
    * Constructor de clase
    *
    *
    * @param String $usuarioId id del usuario
    * @param String $proveedorId id del proveedor
    * @param String $tipo tipo
    * @param String $valorAntes valorAntes
    *
    * @return no
    * @throws Exception si UsumandanteLog no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($usuarioId="",$proveedorId="", $tipo="",$valorAntes="")
    {
        $this->usuarioId = $usuarioId;
        $this->tipo = $tipo;

        if ($usuarioId != "" && $tipo != "" && $proveedorId != "") 
        {

            $UsumandanteLogMySqlDAO = new UsumandanteLogMySqlDAO();

            $UsumandanteLog = $UsumandanteLogMySqlDAO->queryByUsuarioIdAndProveedorAndTipo($usuarioId,$proveedorId, $tipo);
            $UsumandanteLog=$UsumandanteLog[0];

            if ($UsumandanteLog != null && $UsumandanteLog != "") 
            {
            
                $this->usumandantelogId = $UsumandanteLog->usumandantelogId;
                $this->usuarioId = $UsumandanteLog->usuarioId;
                $this->proveedorId = $UsumandanteLog->proveedorId;
                $this->tipo = $UsumandanteLog->tipo;
                $this->valorAntes = $UsumandanteLog->valorAntes;
                $this->valorDespues = $UsumandanteLog->valorDespues;
                $this->usucreaId = $UsumandanteLog->usucreaId;
                $this->usumodifId = $UsumandanteLog->usumodifId;
            
            } 
            else 
            {
                throw new Exception("No existe " . get_class($this), "30");
            }
        }

    }

    /**
    * Obtener los logs por el ValorAntes
    *
    *
    * @param no
    * @return no
    * @throws Exception si UsumandanteLog no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getLogsByWithValorAntes(){


        $UsumandanteLogMySqlDAO = new UsumandanteLogMySqlDAO();

        $UsumandanteLog = $UsumandanteLogMySqlDAO->queryByProveedorAndTipoAndValorAntes($this->proveedorId, $this->tipo,$this->valorAntes);

        if ($UsumandanteLog != null && $UsumandanteLog != "") 
        {
            return $UsumandanteLog;
        } 
        else 
        {
            throw new Exception("No existe " . get_class($this), "01");
        }
    }

    /**
    * Obtener los logs por un usuario
    *
    *
    * @param no
    *
    * @return no
    * @throws Exception si UsumandanteLog no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getLogsByUsuario(){


        $UsumandanteLogMySqlDAO = new UsumandanteLogMySqlDAO();

        $UsumandanteLog = $UsumandanteLogMySqlDAO->queryByProveedorAndTipoAndUsuarioId($this->usuarioId,$this->proveedorId, $this->tipo);

        if ($UsumandanteLog != null && $UsumandanteLog != "") 
        {
            return $UsumandanteLog;
        } 
        else 
        {
            throw new Exception("No existe " . get_class($this), "01");
        }
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
     * Obtener el campo proveedorId de un objeto
     *
     * @return String proveedorId proveedorId
     * 
     */
    public function getProveedorId()
    {
        return $this->proveedorId;
    }

    /**
     * Modificar el campo 'proveedorId' de un objeto
     *
     * @param String $proveedorId proveedorId
     *
     * @return no
     *
     */
    public function setProveedorId($proveedorId)
    {
        $this->proveedorId = $proveedorId;
    }

    /**
     * Obtener el campo tipo de un objeto
     *
     * @return String tipo tipo
     * 
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Modificar el campo 'tipo' de un objeto
     *
     * @param String $tipo tipo
     *
     * @return no
     *
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    /**
     * Obtener el campo valorAntes de un objeto
     *
     * @return String valorAntes valorAntes
     * 
     */
    public function getValorAntes()
    {
        return $this->valorAntes;
    }
    
    /**
     * Modificar el campo 'valorAntes' de un objeto
     *
     * @param String $valorAntes valorAntes
     *
     * @return no
     *
     */
    public function setValorAntes($valorAntes)
    {
        $this->valorAntes = $valorAntes;
    }

    /**
     * Obtener el campo valorDespues de un objeto
     *
     * @return String valorDespues valorDespues
     * 
     */
    public function getValorDespues()
    {
        return $this->valorDespues;
    }

    /**
     * Modificar el campo 'valorDespues' de un objeto
     *
     * @param String $valorDespues valorDespues
     *
     * @return no
     *
     */
    public function setValorDespues($valorDespues)
    {
        $this->valorDespues = $valorDespues;
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