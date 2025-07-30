<?php namespace Backend\dto;
namespace Backend\dto;
use Backend\mysql\TransaccionApiMySqlDAO;
use Exception;
/** 
* Clase 'TransaccionApi'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'TransaccionApi'
* 
* Ejemplo de uso: 
* $TransaccionApi = new TransaccionApi();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class TransaccionApi
{

    /**
    * Representación de la columna 'transapiId' de la tabla 'TransaccionApi'
    *
    * @var string
    */
    var $transapiId;

    /**
    * Representación de la columna 'proveedorId' de la tabla 'TransaccionApi'
    *
    * @var string
    */
    var $proveedorId;

    /**
    * Representación de la columna 'productoId' de la tabla 'TransaccionApi'
    *
    * @var string
    */
    var $productoId;
    
    /**
    * Representación de la columna 'usuarioId' de la tabla 'TransaccionApi'
    *
    * @var string
    */
    var $usuarioId;

    /**
    * Representación de la columna 'tipo' de la tabla 'TransaccionApi'
    *
    * @var string
    */
    var $tipo;

    /**
    * Representación de la columna 'transaccionId' de la tabla 'TransaccionApi'
    *
    * @var string
    */
    var $transaccionId;

    /**
    * Representación de la columna 'identificador' de la tabla 'TransaccionApi'
    *
    * @var string
    */
    var $identificador;
    
    /**
    * Representación de la columna 'tValue' de la tabla 'TransaccionApi'
    *
    * @var string
    */
    var $tValue;

    /**
    * Representación de la columna 'respuestaCodigo' de la tabla 'TransaccionApi'
    *
    * @var string
    */
    var $respuestaCodigo;
    
    /**
    * Representación de la columna 'respuesta' de la tabla 'TransaccionApi'
    *
    * @var string
    */
    var $respuesta;

    /**
    * Representación de la columna 'fechaCrea' de la tabla 'TransaccionApi'
    *
    * @var string
    */
    var $fechaCrea;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'TransaccionApi'
    *
    * @var string
    */
    var $usucreaId;

    /**
    * Representación de la columna 'fechaModif' de la tabla 'TransaccionApi'
    *
    * @var string
    */
    var $fechaModif;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'TransaccionApi'
    *
    * @var string
    */
    var $usumodifId;

    /**
    * Representación de la columna 'valor' de la tabla 'TransaccionApi'
    *
    * @var string
    */
    var $valor;


    /**
    * Constructor de clase
    *
    *
    * @param String $transapiId transapiId
    * @param String $transaccionId transaccionId
    * @param String $ProveedorId ProveedorId
    * @param String $respuestaCODE respuestaCODE
    *
    * @return no
    * @throws Exception si TransaccionApi no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($transapiId="", $transaccionId="", $ProveedorId="", $respuestaCODE="")
    {
        
        $this->transapiId = $transapiId;

        if ($transaccionId != "" && $ProveedorId != "") 
        {

            if ($respuestaCODE === "" || $respuestaCODE === null) 
            {
                $respuestaCODE = "OK";
            }
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();

            $TransaccionApi = $TransaccionApiMySqlDAO->queryByTransaccionIdAndProveedor($transaccionId, $ProveedorId, $respuestaCODE);

            $TransaccionApi = $TransaccionApi[0];

            if ($TransaccionApi != "" && $TransaccionApi != null) 
            {
                $this->transapiId = $TransaccionApi->transapiId;
                $this->proveedorId = $TransaccionApi->proveedorId;
                $this->productoId = $TransaccionApi->productoId;
                $this->usuarioId = $TransaccionApi->usuarioId;
                $this->tipo = $TransaccionApi->tipo;
                $this->transaccionId = $TransaccionApi->transaccionId;
                $this->identificador = $TransaccionApi->identificador;
                $this->tValue = $TransaccionApi->tValue;
                $this->respuesta = $TransaccionApi->respuesta;
                $this->respuestaCodigo = $TransaccionApi->respuestaCodigo;
                $this->fechaCrea = $TransaccionApi->fechaCrea;
                $this->usucreaId = $TransaccionApi->usucreaId;
                $this->fechaModif = $TransaccionApi->fechaModif;
                $this->usumodifId = $TransaccionApi->usumodifId;
                $this->valor = $TransaccionApi->valor;

            } 
            else 
            {
                throw new Exception("No existe " . get_class($this), "29");

            }

        }
        elseif ($transapiId != "")
        {
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();

            $TransaccionApi = $TransaccionApiMySqlDAO->load($transapiId);


            if ($TransaccionApi != "" && $TransaccionApi != null) 
            {
                $this->transapiId = $TransaccionApi->transapiId;
                $this->proveedorId = $TransaccionApi->proveedorId;
                $this->productoId = $TransaccionApi->productoId;
                $this->usuarioId = $TransaccionApi->usuarioId;
                $this->tipo = $TransaccionApi->tipo;
                $this->transaccionId = $TransaccionApi->transaccionId;
                $this->identificador = $TransaccionApi->identificador;
                $this->tValue = $TransaccionApi->tValue;
                $this->respuesta = $TransaccionApi->respuesta;
                $this->respuestaCodigo = $TransaccionApi->respuestaCodigo;
                $this->fechaCrea = $TransaccionApi->fechaCrea;
                $this->usucreaId = $TransaccionApi->usucreaId;
                $this->fechaModif = $TransaccionApi->fechaModif;
                $this->usumodifId = $TransaccionApi->usumodifId;
                $this->valor = $TransaccionApi->valor;

            } 
            else 
            {
                throw new Exception("No existe " . get_class($this), "29");

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
     * Obtener el campo descripcion de un objeto
     *
     * @return String descripcion descripcion
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
     * Obtener el campo transapiId de un objeto
     *
     * @return String transapiId transapiId
     * 
     */
    public function getTransapiId()
    {
        return $this->transapiId;
    }



    /**
    * Consultar si existen registros con el código de respuesta
    * pasado como parámetro
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

        $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();

        $TransaccionApi = $TransaccionApiMySqlDAO->queryByTransaccionIdAndProveedor($this->transaccionId, $this->proveedorId, $respuestaCODE);

        if (oldCount($TransaccionApi) > 0)
        {
            return true;
        }

        return false;

    }

    /**
    * Insertar un registro en la base de datos 
    *
    *
    * @param Objeto $transaction transacción
    *
    * @return boolean $ resultado de la consulta
    * @throws Exception si la transacción en cuestión ya fue procesada
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function insert($transaction)
    {

        if ($this->existsTransaccionIdAndProveedor()) 
        {
            throw new Exception("TransaccionId ya fue procesada", "02");
        }

        $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO($transaction);

        return $TransaccionApiMySqlDAO->insert($this);

    }


    /**
    * Realizar una consulta en la tabla de transacciones 'TransaccionApi'
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
    public function getTransaccionesCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping, $joins = [], $groupingCount = false)
    {

        $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();

        $transacciones = $TransaccionApiMySqlDAO->queryTransaccionesCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping, $joins, $groupingCount);

        if ($transacciones != null && $transacciones != "") 
        {
            return $transacciones;
        }
        else 
        {
            throw new Exception("No existe " . get_class($this), "01");
        }


    }




}
?>