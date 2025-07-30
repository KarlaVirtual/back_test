<?php namespace Backend\dto;
namespace Backend\dto;
use Backend\mysql\TranssportsbookApiMySqlDAO;
use Exception;
/** 
* Clase 'TranssportsbookApi'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'TranssportsbookApi'
* 
* Ejemplo de uso: 
* $TranssportsbookApi = new TranssportsbookApi();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class TranssportsbookApi
{

    /**
    * Representación de la columna 'transsportapiId' de la tabla 'TranssportsbookApi'
    *
    * @var string
    */
    var $transsportapiId;

    /**
    * Representación de la columna 'proveedorId' de la tabla 'TranssportsbookApi'
    *
    * @var string
    */
    var $proveedorId;

    /**
    * Representación de la columna 'productoId' de la tabla 'TranssportsbookApi'
    *
    * @var string
    */
    var $productoId;
    
    /**
    * Representación de la columna 'usuarioId' de la tabla 'TranssportsbookApi'
    *
    * @var string
    */
    var $usuarioId;

    /**
    * Representación de la columna 'tipo' de la tabla 'TranssportsbookApi'
    *
    * @var string
    */
    var $tipo;

    /**
    * Representación de la columna 'transaccionId' de la tabla 'TranssportsbookApi'
    *
    * @var string
    */
    var $transaccionId;

    /**
    * Representación de la columna 'identificador' de la tabla 'TranssportsbookApi'
    *
    * @var string
    */
    var $identificador;
    
    /**
    * Representación de la columna 'tValue' de la tabla 'TranssportsbookApi'
    *
    * @var string
    */
    var $tValue;

    /**
    * Representación de la columna 'respuestaCodigo' de la tabla 'TranssportsbookApi'
    *
    * @var string
    */
    var $respuestaCodigo;
    
    /**
    * Representación de la columna 'respuesta' de la tabla 'TranssportsbookApi'
    *
    * @var string
    */
    var $respuesta;

    /**
    * Representación de la columna 'fechaCrea' de la tabla 'TranssportsbookApi'
    *
    * @var string
    */
    var $fechaCrea;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'TranssportsbookApi'
    *
    * @var string
    */
    var $usucreaId;

    /**
    * Representación de la columna 'fechaModif' de la tabla 'TranssportsbookApi'
    *
    * @var string
    */
    var $fechaModif;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'TranssportsbookApi'
    *
    * @var string
    */
    var $usumodifId;

    /**
    * Representación de la columna 'valor' de la tabla 'TranssportsbookApi'
    *
    * @var string
    */
    var $valor;

    /**
    * Representación de la columna 'gameReference' de la tabla 'TranssportsbookApi'
    *
    * @var string
    */
    var $gameReference;
    
    /**
    * Representación de la columna 'transsportId' de la tabla 'TranssportsbookApi'
    *
    * @var string
    */
    var $transsportId;
    
    /**
    * Representación de la columna 'betStatus' de la tabla 'TranssportsbookApi'
    *
    * @var string
    */
    var $betStatus;
    
    /**
    * Representación de la columna 'mandante' de la tabla 'TranssportsbookApi'
    *
    * @var string
    */
    var $mandante;


    /**
    * Constructor de clase
    *
    *
    * @param String $transsportapiId transsportapiId
    * @param String $transaccionId transaccionId
    * @param String $ProveedorId ProveedorId
    * @param String $respuestaCODE respuestaCODE
    *
    * @return no
    * @throws Exception si TranssportsbookApi no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($transsportapiId="", $transaccionId="", $ProveedorId="", $respuestaCODE="")
    {
        $this->transsportapiId = $transsportapiId;

        if ($transaccionId != "" && $ProveedorId != "") {

            if ($respuestaCODE === "" || $respuestaCODE === null) {
                $respuestaCODE = "OK";
            }
            $TranssportsbookApiMySqlDAO = new TranssportsbookApiMySqlDAO();

            $TranssportsbookApi = $TranssportsbookApiMySqlDAO->queryByTransaccionIdAndProveedor($transaccionId, $ProveedorId, $respuestaCODE);

            $TranssportsbookApi = $TranssportsbookApi[0];

            if ($TranssportsbookApi != "" && $TranssportsbookApi != null) {
                $this->transsportapiId = $TranssportsbookApi->transsportapiId;
                $this->proveedorId = $TranssportsbookApi->proveedorId;
                $this->productoId = $TranssportsbookApi->productoId;
                $this->usuarioId = $TranssportsbookApi->usuarioId;
                $this->tipo = $TranssportsbookApi->tipo;
                $this->transaccionId = $TranssportsbookApi->transaccionId;
                $this->identificador = $TranssportsbookApi->identificador;
                $this->tValue = $TranssportsbookApi->tValue;
                $this->respuesta = $TranssportsbookApi->respuesta;
                $this->respuestaCodigo = $TranssportsbookApi->respuestaCodigo;
                $this->fechaCrea = $TranssportsbookApi->fechaCrea;
                $this->usucreaId = $TranssportsbookApi->usucreaId;
                $this->fechaModif = $TranssportsbookApi->fechaModif;
                $this->usumodifId = $TranssportsbookApi->usumodifId;
                $this->valor = $TranssportsbookApi->valor;

                $this->gameReference = $TranssportsbookApi->gameReference;
                $this->transsportId = $TranssportsbookApi->transsportId;
                $this->betStatus = $TranssportsbookApi->betStatus;
                $this->mandante = $TranssportsbookApi->mandante;

            } else {
                throw new Exception("No existe " . get_class($this), "66");

            }

        }

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
     * Obtener el campo productoId de un objeto
     *
     * @return String productoId productoId
     * 
     */
    public function getProductoId()
    {
        return $this->productoId;
    }

    /**
     * Modificar el campo 'productoId' de un objeto
     *
     * @param String $productoId productoId
     *
     * @return no
     *
     */
    public function setProductoId($productoId)
    {
        $this->productoId = $productoId;
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
     * Obtener el campo transaccionId de un objeto
     *
     * @return String transaccionId transaccionId
     * 
     */
    public function getTransaccionId()
    {
        return $this->transaccionId;
    }

    /**
     * Modificar el campo 'transaccionId' de un objeto
     *
     * @param String $transaccionId transaccionId
     *
     * @return no
     *
     */
    public function setTransaccionId($transaccionId)
    {
        $this->transaccionId = $transaccionId;
    }

    /**
     * Obtener el campo identificador de un objeto
     *
     * @return String identificador identificador
     * 
     */
    public function getIdentificador()
    {
        return $this->identificador;
    }

    /**
     * Modificar el campo 'identificador' de un objeto
     *
     * @param String $identificador identificador
     *
     * @return no
     *
     */
    public function setIdentificador($identificador)
    {
        $this->identificador = $identificador;
    }

    /**
     * Obtener el campo tValue de un objeto
     *
     * @return String tValue tValue
     * 
     */
    public function getTValue()
    {
        return $this->tValue;
    }

    /**
     * Modificar el campo 'tValue' de un objeto
     *
     * @param String $tValue tValue
     *
     * @return no
     *
     */
    public function setTValue($tValue)
    {
        $this->tValue = $tValue;
    }

    /**
     * Obtener el campo respuestaCodigo de un objeto
     *
     * @return String respuestaCodigo respuestaCodigo
     * 
     */
    public function getRespuestaCodigo()
    {
        return $this->respuestaCodigo;
    }

    /**
     * Modificar el campo 'respuestaCodigo' de un objeto
     *
     * @param String $respuestaCodigo respuestaCodigo
     *
     * @return no
     *
     */
    public function setRespuestaCodigo($respuestaCodigo)
    {
        $this->respuestaCodigo = $respuestaCodigo;
    }

    /**
     * Obtener el campo respuesta de un objeto
     *
     * @return String respuesta respuesta
     * 
     */
    public function getRespuesta()
    {
        return $this->respuesta;
    }

    /**
     * Modificar el campo 'respuesta' de un objeto
     *
     * @param String $respuesta respuesta
     *
     * @return no
     *
     */
    public function setRespuesta($respuesta)
    {
        $this->respuesta = $respuesta;
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
     * Obtener el campo fechaModif de un objeto
     *
     * @return String fechaModif fechaModif
     * 
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
     * @return no
     *
     */
    public function setFechaModif($fechaModif)
    {
        $this->fechaModif = $fechaModif;
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
     * Obtener el campo gameReference de un objeto
     *
     * @return String gameReference gameReference
     * 
     */
    public function getGameReference()
    {
        return $this->gameReference;
    }

    /**
     * Modificar el campo 'gameReference' de un objeto
     *
     * @param String $gameReference gameReference
     *
     * @return no
     *
     */
    public function setGameReference($gameReference)
    {
        $this->gameReference = $gameReference;
    }
    
    /**
     * Obtener el campo transsportId de un objeto
     *
     * @return String transsportId transsportId
     * 
     */
    public function getTranssportId()
    {
        return $this->transsportId;
    }

    /**
     * Modificar el campo 'transsportId' de un objeto
     *
     * @param String $transsportId transsportId
     *
     * @return no
     *
     */
    public function setTranssportId($transsportId)
    {
        $this->transsportId = $transsportId;
    }

    /**
     * Obtener el campo betStatus de un objeto
     *
     * @return String betStatus betStatus
     * 
     */
    public function getBetStatus()
    {
        return $this->betStatus;
    }

    /**
     * Modificar el campo 'betStatus' de un objeto
     *
     * @param String $betStatus betStatus
     *
     * @return no
     *
     */
    public function setBetStatus($betStatus)
    {
        $this->betStatus = $betStatus;
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

    /**
     * Obtener el campo transsportapiId de un objeto
     *
     * @return String transsportapiId transsportapiId
     * 
     */
    public function getTranssportapiId()
    {
        return $this->transsportapiId;
    }








    /**
    * Realizar una consulta en la tabla de TranssportsbookApi 'TranssportsbookApi'
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
    * @param String $grouping columna para agrupar
    *
    * @return Array resultado de la consulta
    * @throws Exception si las transacciones no existen
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getTransaccionesCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping)
    {

        $TranssportsbookApiMySqlDAO = new TranssportsbookApiMySqlDAO();

        $transacciones = $TranssportsbookApiMySqlDAO->queryTransaccionesCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping);

        if ($transacciones != null && $transacciones != "") 
        {
            return $transacciones;
        }
        else 
        {
            throw new Exception("No existe " . get_class($this), "66");
        }


    }

    /**
    * Consultar si existen registros con el id de transacción y el proveedor
    *
    *
    * @param String $respuestaCODE respuestaCODE
    *
    * @return boolean $ resultado de la consulta
    * @throws no
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function existsTransaccionIdAndProveedor($respuestaCODE)
    {

        $TranssportsbookApiMySqlDAO = new TranssportsbookApiMySqlDAO();

        $TransaccionApi = $TranssportsbookApiMySqlDAO->queryByTransaccionIdAndProveedorV1($this->transaccionId, $this->proveedorId, $respuestaCODE);

        if (oldCount($TransaccionApi) > 0) {
            return true;
        }

        return false;

    }





}
?>