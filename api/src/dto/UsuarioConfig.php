<?php namespace Backend\dto;
use Backend\mysql\UsuarioConfigMySqlDAO;
use Exception;
/** 
* Clase 'UsuarioConfig'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'UsuarioConfig'
* 
* Ejemplo de uso: 
* $UsuarioConfig = new UsuarioConfig();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class UsuarioConfig
{

    /**
    * Representación de la columna 'usuarioconfigId' de la tabla 'UsuarioConfig'
    *
    * @var string
    */
	var $usuarioconfigId;

    /**
    * Representación de la columna 'usuarioId' de la tabla 'UsuarioConfig'
    *
    * @var string
    */
	var $usuarioId;

    /**
    * Representación de la columna 'permiteRecarga' de la tabla 'UsuarioConfig'
    *
    * @var string
    */
	var $permiteRecarga;

    /**
    * Representación de la columna 'mandante' de la tabla 'UsuarioConfig'
    *
    * @var string
    */
	var $mandante;

    /**
    * Representación de la columna 'pinagent' de la tabla 'UsuarioConfig'
    *
    * @var string
    */
	var $pinagent;

    /**
    * Representación de la columna 'reciboCaja' de la tabla 'UsuarioConfig'
    *
    * @var string
    */
	var $reciboCaja;

    /**
     * Representación de la columna 'maxpagoPremio' de la tabla 'UsuarioConfig'
     *
     * @var string
     */
    var $maxpagoRetiro;

    /**
     * Representación de la columna 'maxpagoRetiro' de la tabla 'UsuarioConfig'
     *
     * @var string
     */
    var $maxpagoPremio;

    /**
    * Constructor de clase
    *
    *
    * @param String $usuarioId usuarioId
    *
    * @return no
    * @throws Exception si UsuarioConfig no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
	public function __construct($usuarioId="")
	{
		if ($usuarioId != "") 
		{

			$UsuarioConfigMySqlDAO = new UsuarioConfigMySqlDAO();
			$UsuarioConfig = $UsuarioConfigMySqlDAO->queryByUsuarioId($usuarioId);

			$UsuarioConfig = $UsuarioConfig[0];

			$this->success = false;

			if ($UsuarioConfig != null && $UsuarioConfig != "") 
			{

				$this->usuarioconfigId = $UsuarioConfig->usuarioconfigId;
				$this->usuarioId = $UsuarioConfig->usuarioId;
				$this->permiteRecarga = $UsuarioConfig->permiteRecarga;
				$this->mandante = $UsuarioConfig->mandante;
				$this->pinagent = $UsuarioConfig->pinagent;
				$this->reciboCaja = $UsuarioConfig->reciboCaja;
                $this->maxpagoPremio = $UsuarioConfig->maxpagoPremio;
                $this->maxpagoRetiro = $UsuarioConfig->maxpagoRetiro;
                $this->fechaModifPassword = $UsuarioConfig->fechaModifPassword;
                $this->fechaModifEstadoUsuario = $UsuarioConfig->fechaModifEstadoUsuario;
                $this->success = true;
			}
			else 
			{
				throw new Exception("No existe " . get_class($this), "24");

			}
		}

	}

    /**
     * Obtiene la fecha de modificación de la contraseña.
     *
     * @return string
     */
    public function getFechaModifPassword()
    {
        return $this->fechaModifPassword;
    }

    /**
     * Establece la fecha de modificación de la contraseña.
     *
     * @param string $fechaModifPassword
     */
    public function setFechaModifPassword($fechaModifPassword)
    {
        $this->fechaModifPassword = $fechaModifPassword;
    }

    /**
     * Obtiene la fecha de modificación del estado del usuario.
     *
     * @return string
     */
    public function getFechaModifEstadoUsuario()
    {
        return $this->fechaModifEstadoUsuario;
    }

    /**
     * Establece la fecha de modificación del estado del usuario.
     *
     * @param string $fechaModifEstadoUsuario
     */
    public function setFechaModifEstadoUsuario($fechaModifEstadoUsuario)
    {
        $this->fechaModifEstadoUsuario = $fechaModifEstadoUsuario;
    }
}
?>