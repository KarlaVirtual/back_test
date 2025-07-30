<?php 
namespace Backend\dto;
use Backend\mysql\CuentaCobroEliminadaMySqlDAO;
use Exception;
/** 
* Clase 'CuentaCobroEliminada'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'CuentaCobroEliminada'
* 
* Ejemplo de uso: 
* $CuentaCobroEliminada = new CuentaCobroEliminada();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class CuentaCobroEliminada
{
		
    /**
    * Representación de la columna 'cuentaId' de la tabla 'CuentaCobroEliminada'
    *
    * @var string
    */  
	var $cuentaId;

    /**
    * Representación de la columna 'fechaCuenta' de la tabla 'CuentaCobroEliminada'
    *
    * @var string
    */  
	var $fechaCuenta;

    /**
    * Representación de la columna 'usuarioId' de la tabla 'CuentaCobroEliminada'
    *
    * @var string
    */  
	var $usuarioId;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'CuentaCobroEliminada'
    *
    * @var string
    */  
	var $usucreaId;

    /**
    * Representación de la columna 'fechaCrea' de la tabla 'CuentaCobroEliminada'
    *
    * @var string
    */  
	var $fechaCrea;

    /**
    * Representación de la columna 'valor' de la tabla 'CuentaCobroEliminada'
    *
    * @var string
    */  
	var $valor;

    /**
    * Representación de la columna 'observ' de la tabla 'CuentaCobroEliminada'
    *
    * @var string
    */  
	var $observ;

    /**
    * Representación de la columna 'mandante' de la tabla 'CuentaCobroEliminada'
    *
    * @var string
    */  
	var $mandante;

    /**
    * Constructor de clase
    *
    *
    * @param String $cuentaId cuentaId
    *
    * @return no
    * @throws no
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($cuentaId="")
    {
        $this->cuentaId = $cuentaId;
    }









    /**
     * Obtener el campo cuentaId de un objeto
     *
     * @return String cuentaId cuentaId
     * 
     */  
    public function getCuentaId()
    {
        return $this->cuentaId;
    }

    /**
     * Modificar el campo 'cuentaId' de un objeto
     *
     * @param String $cuentaId cuentaId
     *
     * @return no
     *
     */
    public function setCuentaId($cuentaId)
    {
        $this->cuentaId = $cuentaId;
    }


    /**
     * Obtener el campo fechaCuenta de un objeto
     *
     * @return String fechaCuenta fechaCuenta
     * 
     */  
    public function getFechaCuenta()
    {
        return $this->fechaCuenta;
    }

    /**
     * Modificar el campo 'fechaCuenta' de un objeto
     *
     * @param String $fechaCuenta fechaCuenta
     *
     * @return no
     *
     */
    public function setFechaCuenta($fechaCuenta)
    {
        $this->fechaCuenta = $fechaCuenta;
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
     * Obtener el campo fechaCrea de un objeto
     *
     * @return String fechaCrea fechaCrea
     * 
     */  
    public function getFechaCrea()
    {
        return $this->fechaCrea;
    }

    /**
     * Modificar el campo 'fechaCrea' de un objeto
     *
     * @param String $fechaCrea fechaCrea
     *
     * @return no
     *
     */
    public function setFechaCrea($fechaCrea)
    {
        $this->fechaCrea = $fechaCrea;
    }


    /**
     * Obtener el campo valor de un objeto
     *
     * @return String valor valor
     * 
     */  
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Modificar el campo 'valor' de un objeto
     *
     * @param String $valor valor
     *
     * @return no
     *
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
    }


    /**
     * Obtener el campo observ de un objeto
     *
     * @return String observ observ
     * 
     */  
    public function getObserv()
    {
        return $this->observ;
    }

    /**
     * Modificar el campo 'observ' de un objeto
     *
     * @param String $observ observ
     *
     * @return no
     *
     */
    public function setObserv($observ)
    {
        $this->observ = $observ;
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
     * @return no
     *
     */
    public function setMandante($mandante)
    {
        $this->mandante = $mandante;
    }


}
?>