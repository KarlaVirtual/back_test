<?php namespace Backend\dto;
namespace Backend\dto;
use Backend\mysql\TransaccionApiMandanteMySqlDAO;
use Exception;
/** 
* Clase 'TransaccionApiMandante'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'TransaccionApiMandante'
* 
* Ejemplo de uso: 
* $TransaccionApiMandante = new TransaccionApiMandante();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class TransaccionApiMandante
{

    /**
    * Representación de la columna 'transapimandanteId' de la tabla 'TransaccionApiMandante'
    *
    * @var string
    */
    var $transapimandanteId;

    /**
    * Representación de la columna 'proveedorId' de la tabla 'TransaccionApiMandante'
    *
    * @var string
    */
    var $proveedorId;

    /**
    * Representación de la columna 'productoId' de la tabla 'TransaccionApiMandante'
    *
    * @var string
    */
    var $productoId;
    
    /**
    * Representación de la columna 'usuarioId' de la tabla 'TransaccionApiMandante'
    *
    * @var string
    */
    var $usuarioId;

    /**
    * Representación de la columna 'tipo' de la tabla 'TransaccionApiMandante'
    *
    * @var string
    */
    var $tipo;

    /**
    * Representación de la columna 'transaccionId' de la tabla 'TransaccionApiMandante'
    *
    * @var string
    */
    var $transaccionId;

    /**
    * Representación de la columna 'identificador' de la tabla 'TransaccionApiMandante'
    *
    * @var string
    */
    var $identificador;
    
    /**
    * Representación de la columna 'tValue' de la tabla 'TransaccionApiMandante'
    *
    * @var string
    */
    var $tValue;

    /**
    * Representación de la columna 'respuestaCodigo' de la tabla 'TransaccionApiMandante'
    *
    * @var string
    */
    var $respuestaCodigo;
    
    /**
    * Representación de la columna 'respuesta' de la tabla 'TransaccionApiMandante'
    *
    * @var string
    */
    var $respuesta;

    /**
    * Representación de la columna 'fechaCrea' de la tabla 'TransaccionApiMandante'
    *
    * @var string
    */
    var $fechaCrea;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'TransaccionApiMandante'
    *
    * @var string
    */
    var $usucreaId;

    /**
    * Representación de la columna 'fechaModif' de la tabla 'TransaccionApiMandante'
    *
    * @var string
    */
    var $fechaModif;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'TransaccionApiMandante'
    *
    * @var string
    */
    var $usumodifId;

    /**
    * Representación de la columna 'valor' de la tabla 'TransaccionApiMandante'
    *
    * @var string
    */
    var $valor;

    /**
    * Representación de la columna 'transapiId' de la tabla 'TransaccionApiMandante'
    *
    * @var string
    */
    var $transapiId;


    /**
    * Constructor de clase
    *
    *
    * @param String $transapimandanteId transapimandanteId
    * @param String $transaccionId transaccionId
    * @param String $ProveedorId ProveedorId
    * @param String $respuestaCODE respuestaCODE
    * @param String $transapiId transapiId
    *
    * @return no
    * @throws Exception si TransaccionApiMandante no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($transapimandanteId="", $transaccionId="", $ProveedorId="", $respuestaCODE="",$transapiId="")
    {
        $this->transapimandanteId = $transapimandanteId;
        
        if ($transapiId != "") 
        {

            $TransaccionApiMandanteMySqlDAO = new TransaccionApiMandanteMySqlDAO();

            $TransaccionApiMandante = $TransaccionApiMandanteMySqlDAO->queryByTransapiId($transapiId);
            $TransaccionApiMandante = $TransaccionApiMandante[0];


            if ($TransaccionApiMandante != "" && $TransaccionApiMandante != null)

            {
                $this->transapimandanteId = $TransaccionApiMandante->transapimandanteId;
                $this->proveedorId = $TransaccionApiMandante->proveedorId;
                $this->productoId = $TransaccionApiMandante->productoId;
                $this->usuarioId = $TransaccionApiMandante->usuarioId;
                $this->tipo = $TransaccionApiMandante->tipo;
                $this->transaccionId = $TransaccionApiMandante->transaccionId;
                $this->identificador = $TransaccionApiMandante->identificador;
                $this->tValue = $TransaccionApiMandante->tValue;
                $this->respuesta = $TransaccionApiMandante->respuesta;
                $this->respuestaCodigo = $TransaccionApiMandante->respuestaCodigo;
                $this->fechaCrea = $TransaccionApiMandante->fechaCrea;
                $this->usucreaId = $TransaccionApiMandante->usucreaId;
                $this->fechaModif = $TransaccionApiMandante->fechaModif;
                $this->usumodifId = $TransaccionApiMandante->usumodifId;
                $this->valor = $TransaccionApiMandante->valor;
                $this->transapiId = $TransaccionApiMandante->transapiId;

                if($this->transapiId =='')
                {
                    $this->transapiId=0;
                }

            }
            else
            {
                throw new Exception("No existe " . get_class($this), "62");

            }
        }
        elseif ($transaccionId != "" && $ProveedorId != "") 
        {

            if ($respuestaCODE === "" || $respuestaCODE === null) 
            {
                $respuestaCODE = "OK";
            }
            
            $TransaccionApiMandanteMySqlDAO = new TransaccionApiMandanteMySqlDAO();

            $TransaccionApiMandante = $TransaccionApiMandanteMySqlDAO->queryByTransaccionIdAndProveedor($transaccionId, $ProveedorId, $respuestaCODE);

            $TransaccionApiMandante = $TransaccionApiMandante[0];

            if ($TransaccionApiMandante != "" && $TransaccionApiMandante != null) 

            {
                $this->transapimandanteId = $TransaccionApiMandante->transapimandanteId;
                $this->proveedorId = $TransaccionApiMandante->proveedorId;
                $this->productoId = $TransaccionApiMandante->productoId;
                $this->usuarioId = $TransaccionApiMandante->usuarioId;
                $this->tipo = $TransaccionApiMandante->tipo;
                $this->transaccionId = $TransaccionApiMandante->transaccionId;
                $this->identificador = $TransaccionApiMandante->identificador;
                $this->tValue = $TransaccionApiMandante->tValue;
                $this->respuesta = $TransaccionApiMandante->respuesta;
                $this->respuestaCodigo = $TransaccionApiMandante->respuestaCodigo;
                $this->fechaCrea = $TransaccionApiMandante->fechaCrea;
                $this->usucreaId = $TransaccionApiMandante->usucreaId;
                $this->fechaModif = $TransaccionApiMandante->fechaModif;
                $this->usumodifId = $TransaccionApiMandante->usumodifId;
                $this->valor = $TransaccionApiMandante->valor;
                $this->transapiId = $TransaccionApiMandante->transapiId;

                if($this->transapiId =='')
                {
                    $this->transapiId=0;
                }

            } 
            else 
            {
                throw new Exception("No existe " . get_class($this), "62");

            }

        }

    }





    /**
     * Obtener el campo transapimandanteId de un objeto
     *
     * @return String transapimandanteId transapimandanteId
     * 
     */
    public function getTransapimandanteId()
    {
        return $this->transapimandanteId;
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
     * Modificar el campo 'transapiId' de un objeto
     *
     * @param String $transapiId transapiId
     *
     * @return no
     *
     */
    public function setTransapiId($transapiId)
    {
        $this->transapiId = $transapiId;
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

        $TransaccionApiMandanteMySqlDAO = new TransaccionApiMandanteMySqlDAO();

        $TransaccionApiMandante = $TransaccionApiMandanteMySqlDAO->queryByTransaccionIdAndProveedor($this->transaccionId, $this->proveedorId, $respuestaCODE);

        if (oldCount($TransaccionApiMandante) > 0)
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

        $TransaccionApiMandanteMySqlDAO = new TransaccionApiMandanteMySqlDAO($transaction);

        return $TransaccionApiMandanteMySqlDAO->insert($this);

    }


    /**
    * Realizar una consulta en la tabla de transacciones 'TransaccionApiMandante'
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

        $TransaccionApiMandanteMySqlDAO = new TransaccionApiMandanteMySqlDAO();

        $transacciones = $TransaccionApiMandanteMySqlDAO->queryTransaccionesCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping);

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