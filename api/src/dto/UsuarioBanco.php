<?php namespace Backend\dto;
use Backend\mysql\UsuarioBancoDetalleMySqlDAO;
use Backend\mysql\UsuarioBancoMySqlDAO;
use Exception;
/** 
* Clase 'UsuarioBanco'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'UsuarioBanco'
* 
* Ejemplo de uso: 
* $UsuarioBanco = new UsuarioBanco();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
*/
class UsuarioBanco
{

    /**
    * Representación de la columna 'usubancoId' de la tabla 'UsuarioBanco'
    *
    * @var string
    */
    var $usubancoId;

    /**
    * Representación de la columna 'usuarioId' de la tabla 'UsuarioBanco'
    *
    * @var string
    */
    var $usuarioId;

    /**
    * Representación de la columna 'bancoId' de la tabla 'UsuarioBanco'
    *
    * @var string
    */
    var $bancoId;

    /**
    * Representación de la columna 'cuenta' de la tabla 'UsuarioBanco'
    *
    * @var string
    */
    var $cuenta;

    /**
    * Representación de la columna 'tipoCuenta' de la tabla 'UsuarioBanco'
    *
    * @var string
    */
    var $tipoCuenta;

    /**
    * Representación de la columna 'tipoCliente' de la tabla 'UsuarioBanco'
    *
    * @var string
    */
    var $tipoCliente;

    /**
    * Representación de la columna 'fechaCrea' de la tabla 'UsuarioBanco'
    *
    * @var string
    */
    var $fechaCrea;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'UsuarioBanco'
    *
    * @var string
    */
    var $usucreaId;

    /**
    * Representación de la columna 'fechaModif' de la tabla 'UsuarioBanco'
    *
    * @var string
    */
    var $fechaModif;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'UsuarioBanco'
    *
    * @var string
    */
    var $usumodifId;

    /**
    * Representación de la columna 'estado' de la tabla 'UsuarioBanco'
    *
    * @var string
    */
    var $estado;

    /**
    * Representación de la columna 'codigo' de la tabla 'UsuarioBanco'
    *
    * @var string
    */
    var $codigo;

    /**
    * Representación de la columna 'token' de la tabla 'UsuarioBanco'
    *
    * @var string
    */
    var $token;

    /**
    * Representación de la columna 'token' de la tabla 'UsuarioBanco'
    *
    * @var string
    */
    var $productoId;

    /**
    * Representación de la tabla 'UsuarioBanco'
    *
    * @var string
    */
    var $UsuarioBancos;

    /**
     * Constructor de clase
     *
     *
     * @param String $usubancoId usubancoId
     * @param String $usuarioId usuarioId
     * @param String $tipoCuenta tipoCuenta
     *
     * @return void
     * @throws Exception si UsuarioBanco no existe
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function __construct($usubancoId="", $usuarioId="", $tipoCuenta="")
    {
        if ($usubancoId != "") 
        {

            $this->usubancoId = $usubancoId;

            $UsuarioBancoMySqlDAO = new UsuarioBancoMySqlDAO();

            $UsuarioBanco = $UsuarioBancoMySqlDAO->load($usubancoId);

            if ($UsuarioBanco != null && $UsuarioBanco != "") 
            {
                $this->usubancoId = $UsuarioBanco->usubancoId;
                $this->usuarioId = $UsuarioBanco->usuarioId;
                $this->bancoId = $UsuarioBanco->bancoId;
                $this->cuenta = $UsuarioBanco->cuenta;
                $this->tipoCuenta = $UsuarioBanco->tipoCuenta;
                $this->tipoCliente = $UsuarioBanco->tipoCliente;
                $this->fechaCrea = $UsuarioBanco->fechaCrea;
                $this->usucreaId = $UsuarioBanco->usucreaId;
                $this->fechaModif = $UsuarioBanco->fechaModif;
                $this->usumodifId = $UsuarioBanco->usumodifId;
                $this->estado = $UsuarioBanco->estado;
                $this->codigo = $UsuarioBanco->codigo;
                $this->token = $UsuarioBanco->token;
                $this->productoId = $UsuarioBanco->productoId;

            }
            else 
            {
                throw new Exception("No existe " . get_class($this), "67");
            }
        }else if ($usuarioId != "" && $tipoCuenta != "" )
        {

            $this->usubancoId = $usubancoId;

            $UsuarioBancoMySqlDAO = new UsuarioBancoMySqlDAO();

            $UsuarioBancos = $UsuarioBancoMySqlDAO->loadUserIdAndTypeAcount($usuarioId, $tipoCuenta);

            if ($UsuarioBancos != null && $UsuarioBancos != "")
            {
                $this->UsuarioBancos = $UsuarioBancos;
            }
            else
            {
                throw new Exception("No existe " . get_class($this), "67");
            }
        }

    }

    /**
     * Realizar una consulta en la tabla de UsuarioBanco 'UsuarioBanco'
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
     * @param array $joins uniones con otras tablas (Opcional)
     *
     * @return Array resultado de la consulta
     * @throws Exception si las los productos no existen
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function getUsuarioBancosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $joins = [])
    {

        $UsuarioBancoMySqlDAO = new UsuarioBancoMySqlDAO();

        $Productos = $UsuarioBancoMySqlDAO->queryUsuarioBancosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $joins);

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
     * Obtener el campo usuarioId de un objeto
     *
     * @return String usuarioId usuarioId
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
     * @return void
     */
    public function setUsuarioId($usuarioId)
    {
        $this->usuarioId = $usuarioId;
    }

    /**
     * Obtener el campo bancoId de un objeto
     *
     * @return String bancoId
     */
    public function getBancoId()
    {
        return $this->bancoId;
    }

    /**
     * Modificar el campo 'bancoId' de un objeto
     *
     * @param String $bancoId bancoId
     *
     * @return void
     */
    public function setBancoId($bancoId)
    {
        $this->bancoId = $bancoId;
    }

    /**
     * Obtener el campo cuenta de un objeto
     *
     * @return String cuenta cuenta
     */
    public function getCuenta()
    {
        return $this->cuenta;
    }

    /**
     * Modificar el campo 'cuenta' de un objeto
     *
     * @param String $cuenta cuenta
     *
     * @return void
     */    public function setCuenta($cuenta)
    {
        $this->cuenta = $cuenta;
    }

    /**
     * Obtener el campo tipoCuenta de un objeto
     *
     * @return String tipoCuenta tipoCuenta
     */
    public function getTipoCuenta()
    {
        return $this->tipoCuenta;
    }

    /**
     * Modificar el campo 'tipoCuenta' de un objeto
     *
     * @param String $tipoCuenta tipoCuenta
     *
     * @return void
     */
    public function setTipoCuenta($tipoCuenta)
    {
        $this->tipoCuenta = $tipoCuenta;
    }

    /**
     * Obtener el campo tipoCliente de un objeto
     *
     * @return String tipoCliente tipoCliente
     */
    public function getTipoCliente()
    {
        return $this->tipoCliente;
    }

    /**
     * Modificar el campo 'tipoCliente' de un objeto
     *
     * @param String $tipoCliente tipoCliente
     *
     * @return void
     */
    public function setTipoCliente($tipoCliente)
    {
        $this->tipoCliente = $tipoCliente;
    }

    /**
     * Obtener el campo fechaCrea de un objeto
     *
     * @return String fechaCrea fechaCrea
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
     * @return void
     */
    public function setFechaCrea($fechaCrea)
    {
        $this->fechaCrea = $fechaCrea;
    }

    /**
     * Obtener el campo usucreaId de un objeto
     *
     * @return String usucreaId usucreaId
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
     * @return void
     */
    public function setUsucreaId($usucreaId)
    {
        $this->usucreaId = $usucreaId;
    }

    /**
     * Obtener el campo fechaModif de un objeto
     *
     * @return String fechaModif fechaModif
     */
    public function getFechaModif()
    {
        return $this->fechaModif;
    }

    /**
     * Modificar el campo 'fechaModif' de un objeto
     *
     * @param String $fechaModif fechaModif
     *
     * @return void
     */
    public function setFechaModif($fechaModif)
    {
        $this->fechaModif = $fechaModif;
    }

    /**
     * Obtener el campo usumodifId de un objeto
     *
     * @return String usumodifId usumodifId
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
     * @return void
     */
    public function setUsumodifId($usumodifId)
    {
        $this->usumodifId = $usumodifId;
    }

    /**
     * Obtener el campo estado de un objeto
     *
     * @return String estado estado
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Modificar el campo 'estado' de un objeto
     *
     * @param String $estado estado
     *
     * @return void
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    /**
     * Obtener el campo codigo de un objeto
     *
     * @return String codigo codigo
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Modificar el campo 'codigo' de un objeto
     *
     * @param String $codigo codigo
     *
     * @return void
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    /**
     * Obtener el campo usubancoId de un objeto
     *
     * @return string usubancoId
     */
    public function getUsubancoId()
    {
        return $this->usubancoId;
    }

    /**
     * Obtener el campo token de un objeto
     *
     * @return string token
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Modificar el campo 'token' de un objeto
     *
     * @param string $token token
     * @return void
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * Obtener el campo productoId de un objeto
     *
     * @return string productoId
     */
    public function getProductoId()
    {
        return $this->productoId;
    }

    /**
     * Modificar el campo 'productoId' de un objeto
     *
     * @param string $productoId productoId
     * @return void
     */
    public function setProductoId($productoId)
    {
        $this->productoId = $productoId;
    }
}
?>
