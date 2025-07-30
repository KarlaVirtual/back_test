<?php namespace Backend\dto;
use Backend\mysql\UsuariojackpotGanadorMySqlDAO;
use Exception;
/** 
* Clase 'UsuarioBono'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'UsuarioBono'
* 
* Ejemplo de uso: 
* $UsuarioBono = new UsuarioBono();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class UsuariojackpotGanador
{

    /**
    * Representación de la columna 'usujackpotganadorId' de la tabla 'UsuariojackpotGanador'
    *
    * @var string
    */
    var $usujackpotganadorId;

    /**
    * Representación de la columna 'usujackpotId' de la tabla 'UsuariojackpotGanador'
    *
    * @var string
    */
    var $usujackpotId;
    
    /**
    * Representación de la columna 'jackpotId' de la tabla 'UsuariojackpotGanador'
    *
    * @var string
    */
    var $jackpotId;

    /**
    * Representación de la columna 'tipo' de la tabla 'UsuariojackpotGanador'
    *
    * @var string
    */
    var $tipo;
    /**
    * Representación de la columna 'usuarioId' de la tabla 'UsuariojackpotGanador'
    *
    * @var string
    */		
	var $usuarioId;

    /**
    * Representación de la columna 'fechaNacim' de la tabla 'UsuariojackpotGanador'
    *
    * @var string
    */
	var $valorPremio;

    var $estado;

    /**
     * Representación de la columna 'usucreaId' de la tabla 'Bono'
     *
     * @var string
     */
    var $usucreaId;

    /**
     * Representación de la columna 'fechaCrea' de la tabla 'Bono'
     *
     * @var string
     */
    var $fechaCrea;

    /**
     * Representación de la columna 'usumodifId' de la tabla 'Bono'
     *
     * @var string
     */
    var $usumodifId;

    /**
     * Representación de la columna 'fechaModif' de la tabla 'Bono'
     *
     * @var string
     */
    var $fechaModif;

    /**
    * Constructor de clase
    *
    *
    * @param String $usuarioId usuarioId
    *
    * @return no
    * @throws Exception si UsuariojackpotGanador no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($usuarioId="", $usujackpotId="", $jackpotId="", $tipo="")
    {
        if($usuarioId != ""){
            $UsuariojackpotGanadorMySqlDAO = new UsuariojackpotGanadorMySqlDAO();

            $UsuariojackpotGanador = $UsuariojackpotGanadorMySqlDAO->load($usuarioId);

            if ($UsuariojackpotGanador != null && $UsuariojackpotGanador != "") 
            {

                $this->usujackpotganadorId = $UsuariojackpotGanador->usujackpotganadorId;
                $this->jackpotId = $UsuariojackpotGanador->jackpotId;
                $this->usujackpotId = $UsuariojackpotGanador->usujackpotId;
                $this->tipo = $UsuariojackpotGanador->tipo;
                $this->usuarioId = $UsuariojackpotGanador->usuarioId;
                $this->valorPremio = $UsuariojackpotGanador->valorPremio;
                $this->estado = $UsuariojackpotGanador->estado;
                $this->fechaCrea = $UsuariojackpotGanador->fechaCrea;
                $this->usucreaId = $UsuariojackpotGanador->usucreaId;
                $this->usumodifId = $UsuariojackpotGanador->usumodifId;
                $this->fechaModif = $UsuariojackpotGanador->fechaModif;

            }
            else 
            {
                throw new Exception("No existe " . get_class($this), "53");    
            }
        }elseif($usujackpotId != "" && $jackpotId != "" && $tipo != ""){
            $UsuariojackpotGanadorMySqlDAO = new UsuariojackpotGanadorMySqlDAO();

            $UsuariojackpotGanador = $UsuariojackpotGanadorMySqlDAO->UsujackpotIdAndJackpotIdAndTipo($usujackpotId, $jackpotId, $tipo);

            if ($UsuariojackpotGanador != null && $UsuariojackpotGanador != "") 
            {

                $this->usujackpotganadorId = $UsuariojackpotGanador->usujackpotganadorId;
                $this->jackpotId = $UsuariojackpotGanador->jackpotId;
                $this->usujackpotId = $UsuariojackpotGanador->usujackpotId;
                $this->tipo = $UsuariojackpotGanador->tipo;
                $this->usuarioId = $UsuariojackpotGanador->usuarioId;
                $this->valorPremio = $UsuariojackpotGanador->valorPremio;
                $this->estado = $UsuariojackpotGanador->estado;
                $this->fechaCrea = $UsuariojackpotGanador->fechaCrea;
                $this->usucreaId = $UsuariojackpotGanador->usucreaId;
                $this->usumodifId = $UsuariojackpotGanador->usumodifId;
                $this->fechaModif = $UsuariojackpotGanador->fechaModif;

            }
            else 
            {
                throw new Exception("No existe " . get_class($this), "53");    
            }
        }

    }


/**
     * Obtiene el ID del ganador del jackpot de usuario.
     *
     * @return string
     */
    public function getUsujackpotganadorId()
    {
        return $this->usujackpotganadorId;
    }

    /**
     * Establece el ID del ganador del jackpot de usuario.
     *
     * @param string $usujackpotganadorId
     */
    public function setUsujackpotganadorId($usujackpotganadorId)
    {
        $this->usujackpotganadorId = $usujackpotganadorId;
    }

    /**
     * Obtiene el ID del jackpot de usuario.
     *
     * @return string
     */
    public function getUsujackpotId()
    {
        return $this->usujackpotId;
    }

    /**
     * Establece el ID del jackpot de usuario.
     *
     * @param string $usujackpotId
     */
    public function setUsujackpotId($usujackpotId)
    {
        $this->usujackpotId = $usujackpotId;
    }

    /**
     * Obtiene el tipo.
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Establece el tipo.
     *
     * @param string $tipo
     */
    public function setTipo($tipo): void
    {
        $this->tipo = $tipo;
    }

    /**
     * Obtiene el estado.
     *
     * @return string
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Establece el estado.
     *
     * @param string $estado
     */
    public function setEstado($estado): void
    {
        $this->estado = $estado;
    }

    /**
     * Obtiene el ID del jackpot.
     *
     * @return string
     */
    public function getJackpotId()
    {
        return $this->jackpotId;
    }

    /**
     * Establece el ID del jackpot.
     *
     * @param string $jackpotId
     */
    public function setJackpotId($jackpotId)
    {
        $this->jackpotId = $jackpotId;
    }

    /**
     * Obtiene el ID del usuario.
     *
     * @return string
     */
    public function getUsuarioId()
    {
        return $this->usuarioId;
    }

    /**
     * Establece el ID del usuario.
     *
     * @param string $usuarioId
     */
    public function setUsuarioId($usuarioId)
    {
        $this->usuarioId = $usuarioId;
    }

    /**
     * Obtiene el valor del premio.
     *
     * @return string
     */
    public function getValorPremio()
    {
        return $this->valorPremio;
    }

    /**
     * Establece el valor del premio.
     *
     * @param string $valorPremio
     */
    public function setValorPremio($valorPremio)
    {
        $this->valorPremio = $valorPremio;
    }

    /**
     * Obtiene el ID del creador del usuario.
     *
     * @return string
     */
    public function getUsucreaId()
    {
        return $this->usucreaId;
    }

    /**
     * Establece el ID del creador del usuario.
     *
     * @param string $usucreaId
     */
    public function setUsucreaId($usucreaId)
    {
        $this->usucreaId = $usucreaId;
    }

    /**
     * Obtiene la fecha de creación.
     *
     * @return string
     */
    public function getFechaCrea()
    {
        return $this->fechaCrea;
    }

    /**
     * Establece la fecha de creación.
     *
     * @param string $fechaCrea
     */
    public function setFechaCrea($fechaCrea)
    {
        $this->fechaCrea = $fechaCrea;
    }

    /**
     * Obtiene el ID del modificador del usuario.
     *
     * @return string
     */
    public function getUsumodifId()
    {
        return $this->usumodifId;
    }

    /**
     * Establece el ID del modificador del usuario.
     *
     * @param string $usumodifId
     */
    public function setUsumodifId($usumodifId)
    {
        $this->usumodifId = $usumodifId;
    }

    /**
     * Obtiene la fecha de modificación.
     *
     * @return string
     */
    public function getFechaModif()
    {
        return $this->fechaModif;
    }

    /**
     * Establece la fecha de modificación.
     *
     * @param string $fechaModif
     */
    public function setFechaModif($fechaModif)
    {
        $this->fechaModif = $fechaModif;
    }

    
}
?>