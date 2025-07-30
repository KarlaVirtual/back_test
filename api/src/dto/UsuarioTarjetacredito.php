<?php namespace Backend\dto;
use Backend\mysql\UsuarioTarjetaDetalleMySqlDAO;
use Backend\mysql\UsuarioTarjetacreditoMySqlDAO;
use Exception;
/** 
* Clase 'UsuarioTarjeta'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'UsuarioTarjeta'
* 
* Ejemplo de uso: 
* $UsuarioTarjeta = new UsuarioTarjeta();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class UsuarioTarjetacredito
{

    /**
    * Representación de la columna 'usutarjetacreditoId' de la tabla 'UsuarioTarjeta'
    *
    * @var string
    */
    var $usutarjetacreditoId;

    /**
    * Representación de la columna 'usuarioId' de la tabla 'UsuarioTarjeta'
    *
    * @var string
    */
    var $usuarioId;

    /**
    * Representación de la columna 'proveedorId' de la tabla 'UsuarioTarjeta'
    *
    * @var string
    */
    var $proveedorId;

    /**
    * Representación de la columna 'cuenta' de la tabla 'UsuarioTarjeta'
    *
    * @var string
    */
    var $cuenta;

    /**
    * Representación de la columna 'cvv' de la tabla 'UsuarioTarjeta'
    *
    * @var string
    */
    var $cvv;

    /**
    * Representación de la columna 'fechaExpiracion' de la tabla 'UsuarioTarjeta'
    *
    * @var string
    */
    var $fechaExpiracion;

    /**
    * Representación de la columna 'fechaCrea' de la tabla 'UsuarioTarjeta'
    *
    * @var string
    */
    var $fechaCrea;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'UsuarioTarjeta'
    *
    * @var string
    */
    var $usucreaId;

    /**
    * Representación de la columna 'fechaModif' de la tabla 'UsuarioTarjeta'
    *
    * @var string
    */
    var $fechaModif;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'UsuarioTarjeta'
    *
    * @var string
    */
    var $usumodifId;

    /**
    * Representación de la columna 'estado' de la tabla 'UsuarioTarjeta'
    *
    * @var string
    */
    var $estado;

    /**
    * Representación de la columna 'token' de la tabla 'UsuarioTarjeta'
    *
    * @var string
    */
    var $token;

    /**
     * Representación de la columna 'descripcion' de la tabla 'UsuarioTarjeta'
     *
     * @var string
     */
    var $descripcion;
    


    /**
    * Constructor de clase
    *
    *
    * @param String $usutarjetacreditoId usutarjetacreditoId
    * @param String $usuarioId usuarioId
    *
    * @return no
    * @throws Exception si UsuarioTarjeta no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($usutarjetacreditoId="", $usuarioId="")
    {
        if ($usutarjetacreditoId != "") 
        {

            $this->usutarjetacreditoId = $usutarjetacreditoId;

            $UsuarioTarjetacreditoMySqlDAO = new UsuarioTarjetacreditoMySqlDAO();

            $UsuarioTarjeta = $UsuarioTarjetacreditoMySqlDAO->load($usutarjetacreditoId);

            if ($UsuarioTarjeta != null && $UsuarioTarjeta != "") 
            {
                $this->usutarjetacreditoId = $UsuarioTarjeta->usutarjetacreditoId;
                $this->usuarioId = $UsuarioTarjeta->usuarioId;
                $this->proveedorId = $UsuarioTarjeta->proveedorId;
                $this->cuenta = $UsuarioTarjeta->cuenta;
                $this->cvv = $UsuarioTarjeta->cvv;
                $this->fechaExpiracion = $UsuarioTarjeta->fechaExpiracion;
                $this->fechaCrea = $UsuarioTarjeta->fechaCrea;
                $this->usucreaId = $UsuarioTarjeta->usucreaId;
                $this->fechaModif = $UsuarioTarjeta->fechaModif;
                $this->usumodifId = $UsuarioTarjeta->usumodifId;
                $this->estado = $UsuarioTarjeta->estado;
                $this->token = $UsuarioTarjeta->token;

            }
            else 
            {
                throw new Exception("No existe " . get_class($this), "67");
            }
        }

    }

    /**
    * Realizar una consulta en la tabla de UsuarioTarjeta 'UsuarioTarjeta'
    * de una manera personalizada
    *
    *
    * @param String $select campos de consulta
    * @param String $sidx columna para ordenar
    * @param String $sord orden los datos asc | desc
    * @param String $start inicio de la consulta
    * @param String $limit limite de la consulta
    * @param String $filters condiciones de la consulta 
    * @param boolean $searchOn utilizar los filtros o no
    *
    * @return Array resultado de la consulta
    * @throws Exception si las los productos no existen
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getUsuarioTarjetasCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $UsuarioTarjetacreditoMySqlDAO = new UsuarioTarjetacreditoMySqlDAO();

        $Productos = $UsuarioTarjetacreditoMySqlDAO->queryUsuarioTarjetasCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($Productos != null && $Productos != "") 
        {
            return $Productos;
        } 
        else 
        {
            throw new Exception("No existe " . get_class($this), "01");
        }

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
     * Obtiene el ID del proveedor.
     *
     * @return string
     */
    public function getProveedorId()
    {
        return $this->proveedorId;
    }

    /**
     * Establece el ID del proveedor.
     *
     * @param string $proveedorId
     */
    public function setProveedorId($proveedorId)
    {
        $this->proveedorId = $proveedorId;
    }

    /**
     * Obtiene la cuenta.
     *
     * @return string
     */
    public function getCuenta()
    {
        return $this->cuenta;
    }

    /**
     * Establece la cuenta.
     *
     * @param string $cuenta
     */
    public function setCuenta($cuenta)
    {
        $this->cuenta = $cuenta;
    }

    /**
     * Obtiene el CVV.
     *
     * @return string
     */
    public function getCvv()
    {
        return $this->cvv;
    }

    /**
     * Establece el CVV.
     *
     * @param string $cvv
     */
    public function setCvv($cvv)
    {
        $this->cvv = $cvv;
    }

    /**
     * Obtiene la fecha de expiración.
     *
     * @return string
     */
    public function getFechaExpiracion()
    {
        return $this->fechaExpiracion;
    }

    /**
     * Establece la fecha de expiración.
     *
     * @param string $fechaExpiracion
     */
    public function setFechaExpiracion($fechaExpiracion)
    {
        $this->fechaExpiracion = $fechaExpiracion;
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
     * Obtiene el ID del usuario que creó.
     *
     * @return string
     */
    public function getUsucreaId()
    {
        return $this->usucreaId;
    }

    /**
     * Establece el ID del usuario que creó.
     *
     * @param string $usucreaId
     */
    public function setUsucreaId($usucreaId)
    {
        $this->usucreaId = $usucreaId;
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

    /**
     * Obtiene el ID del usuario que modificó.
     *
     * @return string
     */
    public function getUsumodifId()
    {
        return $this->usumodifId;
    }

    /**
     * Establece el ID del usuario que modificó.
     *
     * @param string $usumodifId
     */
    public function setUsumodifId($usumodifId)
    {
        $this->usumodifId = $usumodifId;
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
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    /**
     * Obtiene el token.
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Establece el token.
     *
     * @param string $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * Obtiene la descripción.
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Establece la descripción.
     *
     * @param string $descripcion
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    /**
     * Obtiene el ID de la tarjeta de crédito del usuario.
     *
     * @return string
     */
    public function getUsutarjetacreditoId()
    {
        return $this->usutarjetacreditoId;
    }


}

?>
