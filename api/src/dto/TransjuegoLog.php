<?php namespace Backend\dto;
namespace Backend\dto;
use Backend\mysql\TransjuegoLogMySqlDAO;
use Exception;
/** 
* Clase 'TransjuegoLog'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'TransjuegoLog'
* 
* Ejemplo de uso: 
* $TransjuegoLog = new TransjuegoLog();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class TransjuegoLog
{

    /**
    * Representación de la columna 'transjuegologId' de la tabla 'TransjuegoLog'
    *
    * @var string
    */
    var $transjuegologId;

    /**
    * Representación de la columna 'transjuegoId' de la tabla 'TransjuegoLog'
    *
    * @var string
    */
    var $transjuegoId;

    /**
    * Representación de la columna 'tipo' de la tabla 'TransjuegoLog'
    *
    * @var string
    */
    var $tipo;

    /**
    * Representación de la columna 'transaccionId' de la tabla 'TransjuegoLog'
    *
    * @var string
    */
    var $transaccionId;

    /**
    * Representación de la columna 'tValue' de la tabla 'TransjuegoLog'
    *
    * @var string
    */
    var $tValue;

    /**
    * Representación de la columna 'fechaCrea' de la tabla 'TransjuegoLog'
    *
    * @var string
    */
    var $fechaCrea;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'TransjuegoLog'
    *
    * @var string
    */
    var $usucreaId;

    /**
    * Representación de la columna 'fechaModif' de la tabla 'TransjuegoLog'
    *
    * @var string
    */
    var $fechaModif;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'TransjuegoLog'
    *
    * @var string
    */
    var $usumodifId;

    /**
    * Representación de la columna 'valor' de la tabla 'TransjuegoLog'
    *
    * @var string
    */
    var $valor;

    /**
    * Representación de la columna 'saldoCreditos' de la tabla 'TransjuegoLog'
    *
    * @var string
    */
    var $proveedorId;

    /**
    * Representación de la columna 'saldoCreditos' de la tabla 'TransjuegoLog'
    *
    * @var string
    */
    var $productoId;

    /**
    * Representación de la columna 'saldoCreditos' de la tabla 'TransjuegoLog'
    *
    * @var string
    */
    var $saldoCreditos;

    /**
    * Representación de la columna 'saldoCreditosBase' de la tabla 'TransjuegoLog'
    *
    * @var string
    */
    var $saldoCreditosBase;

    /**
    * Representación de la columna 'saldoBonos' de la tabla 'TransjuegoLog'
    *
    * @var string
    */
    var $saldoBonos;

    /**
    * Representación de la columna 'saldoFree' de la tabla 'TransjuegoLog'
    *
    * @var string
    */
    var $saldoFree;




    /**
    * Constructor de clase
    *
    *
    * @param String $transjuegologId transjuegologId
    * @param String $transjuegoId transjuegoId
    * @param String $tipo tipo
    * @param String $transaccionId transaccionId
    *
    * @return no
    * @throws Exception si TransJuegoLog no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($transjuegologId="",$transjuegoId="",$tipo="",$transaccionId="",$proveedorId="",$productoId="")
    {
        $this->transjuegologId = $transjuegologId;

        if ($transjuegoId != "" && $transaccionId !="") 
        {

            $this->transjuegoId = $transjuegoId;

            $TransjuegoLogMySqlDAO = new TransjuegoLogMySqlDAO();

            $TransaccionJuego = $TransjuegoLogMySqlDAO->queryByTransjuegoIdAndTransaccionId($this->transjuegoId,$transaccionId);

            $TransaccionJuego=$TransaccionJuego[0];

            if ($TransaccionJuego != null && $TransaccionJuego != "") 
            {
                $this->transjuegologId = $TransaccionJuego->transjuegologId;
                $this->transjuegoId = $TransaccionJuego->transjuegoId;
                $this->tipo = $TransaccionJuego->tipo;
                $this->transaccionId = $TransaccionJuego->transaccionId;
                $this->tValue = $TransaccionJuego->tValue;
                $this->usucreaId = $TransaccionJuego->usucreaId;
                $this->usumodifId = $TransaccionJuego->usumodifId;
                $this->valor = $TransaccionJuego->valor;
                $this->saldoCreditos = $TransaccionJuego->saldoCreditos;
                $this->saldoCreditosBase = $TransaccionJuego->saldoCreditosBase;
                $this->saldoBonos = $TransaccionJuego->saldoBonos;
                $this->saldoFree = $TransaccionJuego->saldoFree;

                $this->fechaCrea = $TransaccionJuego->fechaCrea;
                $this->fechaModif = $TransaccionJuego->fechaModif;
            }
            else 
            {
                throw new Exception("No existe " . get_class($this), "28");
            }

        }
        elseif ($transjuegoId != "" && $tipo != "") 
        {

            $this->transjuegoId = $transjuegoId;

            $TransjuegoLogMySqlDAO = new TransjuegoLogMySqlDAO();

            $TransaccionJuego = $TransjuegoLogMySqlDAO->queryByTransjuegoIdAndTipo($this->transjuegoId,$tipo);

            $TransaccionJuego=$TransaccionJuego[0];

            if ($TransaccionJuego != null && $TransaccionJuego != "") 
            {
                $this->transjuegologId = $TransaccionJuego->transjuegologId;
                $this->transjuegoId = $TransaccionJuego->transjuegoId;
                $this->tipo = $TransaccionJuego->tipo;
                $this->transaccionId = $TransaccionJuego->transaccionId;
                $this->tValue = $TransaccionJuego->tValue;
                $this->usucreaId = $TransaccionJuego->usucreaId;
                $this->usumodifId = $TransaccionJuego->usumodifId;
                $this->valor = $TransaccionJuego->valor;
                $this->saldoCreditos = $TransaccionJuego->saldoCreditos;
                $this->saldoCreditosBase = $TransaccionJuego->saldoCreditosBase;
                $this->saldoBonos = $TransaccionJuego->saldoBonos;
                $this->saldoFree = $TransaccionJuego->saldoFree;

                $this->fechaCrea = $TransaccionJuego->fechaCrea;
                $this->fechaModif = $TransaccionJuego->fechaModif;
            }
            else 
            {
                throw new Exception("No existe " . get_class($this), "28");
            }

        }
        elseif ($transaccionId != "" && $proveedorId != "")
        {

            $this->transjuegoId = $transjuegoId;

            $TransjuegoLogMySqlDAO = new TransjuegoLogMySqlDAO();

            $TransaccionJuego = $TransjuegoLogMySqlDAO->queryByTransaccionIdAndProveedor($transaccionId,$proveedorId);

            $TransaccionJuego=$TransaccionJuego[0];

            if ($TransaccionJuego != null && $TransaccionJuego != "")
            {
                $this->transjuegologId = $TransaccionJuego->transjuegologId;
                $this->transjuegoId = $TransaccionJuego->transjuegoId;
                $this->tipo = $TransaccionJuego->tipo;
                $this->transaccionId = $TransaccionJuego->transaccionId;
                $this->tValue = $TransaccionJuego->tValue;
                $this->usucreaId = $TransaccionJuego->usucreaId;
                $this->usumodifId = $TransaccionJuego->usumodifId;
                $this->valor = $TransaccionJuego->valor;
                $this->saldoCreditos = $TransaccionJuego->saldoCreditos;
                $this->saldoCreditosBase = $TransaccionJuego->saldoCreditosBase;
                $this->saldoBonos = $TransaccionJuego->saldoBonos;
                $this->saldoFree = $TransaccionJuego->saldoFree;

                $this->fechaCrea = $TransaccionJuego->fechaCrea;
                $this->fechaModif = $TransaccionJuego->fechaModif;
            }
            else
            {
                throw new Exception("No existe " . get_class($this), "28");
            }

        }
        elseif ($transaccionId != "" && $productoId != "")
        {

            $this->transjuegoId = $transjuegoId;

            $TransjuegoLogMySqlDAO = new TransjuegoLogMySqlDAO();

            $TransaccionJuego = $TransjuegoLogMySqlDAO->queryByTransaccionIdAndProducto($transaccionId,$productoId);

            $TransaccionJuego=$TransaccionJuego[0];

            if ($TransaccionJuego != null && $TransaccionJuego != "")
            {
                $this->transjuegologId = $TransaccionJuego->transjuegologId;
                $this->transjuegoId = $TransaccionJuego->transjuegoId;
                $this->tipo = $TransaccionJuego->tipo;
                $this->transaccionId = $TransaccionJuego->transaccionId;
                $this->tValue = $TransaccionJuego->tValue;
                $this->usucreaId = $TransaccionJuego->usucreaId;
                $this->usumodifId = $TransaccionJuego->usumodifId;
                $this->valor = $TransaccionJuego->valor;
                $this->saldoCreditos = $TransaccionJuego->saldoCreditos;
                $this->saldoCreditosBase = $TransaccionJuego->saldoCreditosBase;
                $this->saldoBonos = $TransaccionJuego->saldoBonos;
                $this->saldoFree = $TransaccionJuego->saldoFree;

                $this->fechaCrea = $TransaccionJuego->fechaCrea;
                $this->fechaModif = $TransaccionJuego->fechaModif;
            }
            else
            {
                throw new Exception("No existe " . get_class($this), "28");
            }

        }
        elseif ($transjuegologId != "")
        {

            $this->transjuegoId = $transjuegoId;

            $TransjuegoLogMySqlDAO = new TransjuegoLogMySqlDAO();

            $TransaccionJuego = $TransjuegoLogMySqlDAO->load($transjuegologId);

            if ($TransaccionJuego != null && $TransaccionJuego != "")
            {
                $this->transjuegologId = $TransaccionJuego->transjuegologId;
                $this->transjuegoId = $TransaccionJuego->transjuegoId;
                $this->tipo = $TransaccionJuego->tipo;
                $this->transaccionId = $TransaccionJuego->transaccionId;
                $this->tValue = $TransaccionJuego->tValue;
                $this->usucreaId = $TransaccionJuego->usucreaId;
                $this->usumodifId = $TransaccionJuego->usumodifId;
                $this->valor = $TransaccionJuego->valor;
                $this->saldoCreditos = $TransaccionJuego->saldoCreditos;
                $this->saldoCreditosBase = $TransaccionJuego->saldoCreditosBase;
                $this->saldoBonos = $TransaccionJuego->saldoBonos;
                $this->saldoFree = $TransaccionJuego->saldoFree;

                $this->fechaCrea = $TransaccionJuego->fechaCrea;
                $this->fechaModif = $TransaccionJuego->fechaModif;
            }
            else
            {
                throw new Exception("No existe " . get_class($this), "28");
            }

        }
    }




    /**
     * Obtener el campo transjuegoId de un objeto
     *
     * @return String transjuegoId transjuegoId
     * 
     */
    public function getTransjuegoId()
    {
        return $this->transjuegoId;
    }

    /**
     * Modificar el campo 'transjuegoId' de un objeto
     *
     * @param String $transjuegoId transjuegoId
     *
     * @return no
     *
     */
    public function setTransjuegoId($transjuegoId)
    {
        $this->transjuegoId = $transjuegoId;
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
     * Obtener el campo fechaCrea de un objeto
     *
     * @return String fechaCrea fechaCrea
     * 
     */    public function getFechaCrea()
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
    * Consultar si existen registros con el id de transacción
    *
    *
    * @param no
    *
    * @return boolean $ resultado de la consulta
    * @throws no
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function existsTransaccionId()
    {

        $TransjuegoLogMySqlDAO = new TransjuegoLogMySqlDAO();

        $TransjuegoLog = $TransjuegoLogMySqlDAO->queryByTransaccionId($this->transaccionId);

        if (oldCount($TransjuegoLog) > 0) {
            return true;
        }

        return false;

    }

    /**
    * Consultar si 
    *
    *
    * @param no
    *
    * @return boolean $ resultado de la consulta
    * @throws no
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function isEqualsNewCredit()
    {

        $TransjuegoLogMySqlDAO = new TransjuegoLogMySqlDAO();

        $TransjuegoLog = $TransjuegoLogMySqlDAO->queryByTransjuegoId($this->transjuegoId);

        $contDebit = 0;
        $contCredit = 0;

        foreach ($TransjuegoLog as $log) {
            switch ($log->getTipo()) {
                case "CREDIT":
                    $contCredit++;
                    break;
                case "CREDITINSURANCE":
                    $contCredit++;
                    break;
                case "CREDITDOUBLE":
                    $contCredit++;
                    break;
                case "CREDITSPLIT":
                    $contCredit++;
                    break;
                case "CREDITANTE":
                    $contCredit++;
                    break;
                case "CREDITTABLE":
                    $contCredit++;
                    break;
                case "CREDITSPLITBB":
                    $contCredit++;
                    break;
                case "CREDITDOUBLEBB":
                    $contCredit++;
                    break;
                case "CREDITINSURANCEBB":
                    $contCredit++;
                    break;
                case "CREDITCALL":
                    $contCredit++;
                    break;
                case "DEBIT":
                    $contDebit++;
                    break;
                case "DEBITINSURANCE":
                    $contDebit++;
                    break;
                case "DEBITDOUBLE":
                    $contDebit++;
                    break;
                case "DEBITSPLIT":
                    $contDebit++;
                    break;
                case "DEBITANTE":
                    $contDebit++;
                    break;
                case "DEBITTABLE":
                    $contDebit++;
                    break;
                case "DEBITSPLITBB":
                    $contDebit++;
                    break;
                case "DEBITDOUBLEBB":
                    $contDebit++;
                    break;
                case "DEBITINSURANCEBB":
                    $contDebit++;
                    break;
                case "DEBITCALL":
                    $contDebit++;
                    break;

            }
        }
        if ($contDebit >= ($contCredit + 1)) {
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
    * @throws no
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function insert($transaction)
    {

        $TransjuegoLogMySqlDAO = new TransjuegoLogMySqlDAO($transaction);

        return $TransjuegoLogMySqlDAO->insert($this);

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
     * Obtener el campo transjuegologId de un objeto
     *
     * @return String transjuegologId transjuegologId
     * 
     */
    public function getTransjuegologId()
    {
        return $this->transjuegologId;
    }

    
/**
     * Obtener el campo saldoCreditos de un objeto
     *
     * @return String saldoCreditos saldoCreditos
     */
    public function getSaldoCreditos()
    {
        return $this->saldoCreditos;
    }

    /**
     * Modificar el campo 'saldoCreditos' de un objeto
     *
     * @param String $saldoCreditos saldoCreditos
     *
     * @return no
     */
    public function setSaldoCreditos($saldoCreditos)
    {
        $this->saldoCreditos = $saldoCreditos;
    }

    /**
     * Obtener el campo saldoCreditosBase de un objeto
     *
     * @return String saldoCreditosBase saldoCreditosBase
     */
    public function getSaldoCreditosBase()
    {
        return $this->saldoCreditosBase;
    }

    /**
     * Modificar el campo 'saldoCreditosBase' de un objeto
     *
     * @param String $saldoCreditosBase saldoCreditosBase
     *
     * @return no
     */
    public function setSaldoCreditosBase($saldoCreditosBase)
    {
        $this->saldoCreditosBase = $saldoCreditosBase;
    }

    /**
     * Obtener el campo saldoBonos de un objeto
     *
     * @return String saldoBonos saldoBonos
     */
    public function getSaldoBonos()
    {
        return $this->saldoBonos;
    }

    /**
     * Modificar el campo 'saldoBonos' de un objeto
     *
     * @param String $saldoBonos saldoBonos
     *
     * @return no
     */
    public function setSaldoBonos($saldoBonos)
    {
        $this->saldoBonos = $saldoBonos;
    }

    /**
     * Obtener el campo saldoFree de un objeto
     *
     * @return String saldoFree saldoFree
     */
    public function getSaldoFree()
    {
        return $this->saldoFree;
    }

    /**
     * Modificar el campo 'saldoFree' de un objeto
     *
     * @param String $saldoFree saldoFree
     *
     * @return no
     */
    public function setSaldoFree($saldoFree)
    {
        $this->saldoFree = $saldoFree;
    }

    /**
     * Obtener el campo proveedorId de un objeto
     *
     * @return String proveedorId proveedorId
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
     */
    public function setProveedorId($proveedorId)
    {
        $this->proveedorId = $proveedorId;
    }

    /**
     * Obtener el campo productoId de un objeto
     *
     * @return String productoId productoId
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
     */
    public function setProductoId($productoId)
    {
        $this->productoId = $productoId;
    }





    /**
    * Realizar una consulta en la tabla de TransJuegoLog 'TransJuegoLog'
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
    public function getTransjuegoLogsCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping)
    {

        $TransjuegoLogMySqlDAO = new TransjuegoLogMySqlDAO();

        $transacciones = $TransjuegoLogMySqlDAO->queryTransaccionesCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping);

        if ($transacciones != null && $transacciones != "") 
        {
            return $transacciones;
        }
        else 
        {
            throw new Exception("No existe " . get_class($this), "01");
        }


    }

/**
     * Realiza una consulta personalizada en la tabla de TransJuegoLog 'TransJuegoLog'
     *
     * @param string $select Campos de consulta
     * @param string $sidx Columna para ordenar
     * @param string $sord Orden de los datos (asc | desc)
     * @param string $start Inicio de la consulta
     * @param string $limit Límite de la consulta
     * @param string $filters Condiciones de la consulta
     * @param boolean $searchOn Utilizar los filtros o no
     * @param string $grouping Columna para agrupar
     *
     * @return array Resultado de la consulta
     * @throws Exception Si las transacciones no existen
     *
     * @access public
     */
    public function getTransjuegoLogsCustom2($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping)
    {

        $TransjuegoLogMySqlDAO = new TransjuegoLogMySqlDAO();

        $transacciones = $TransjuegoLogMySqlDAO->queryTransaccionesCustom2($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping);

        if ($transacciones != null && $transacciones != "")
        {
            return $transacciones;
        }
        else
        {
            throw new Exception("No existe " . get_class($this), "01");
        }

    }


/**
     * Realiza una consulta personalizada en la tabla de TransJuegoLog 'TransJuegoLog'
     *
     * @param string $select Campos de consulta
     * @param string $sidx Columna para ordenar
     * @param string $sord Orden de los datos (asc | desc)
     * @param string $start Inicio de la consulta
     * @param string $limit Límite de la consulta
     * @param string $filters Condiciones de la consulta
     * @param boolean $searchOn Utilizar los filtros o no
     * @param string $grouping Columna para agrupar
     *
     * @return array Resultado de la consulta
     * @throws Exception Si las transacciones no existen
     *
     * @access public
     */
    public function getTransjuegoLogsCustom3($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping)
    {

        $TransjuegoLogMySqlDAO = new TransjuegoLogMySqlDAO();

        $transacciones = $TransjuegoLogMySqlDAO->queryTransaccionesCustom3($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping);

        if ($transacciones != null && $transacciones != "")
        {
            return $transacciones;
        }
        else
        {
            throw new Exception("No existe " . get_class($this), "01");
        }
    }

/**
         * Ejecuta una consulta SQL personalizada en la base de datos.
         *
         * @param mixed $transaccion La transacción actual.
         * @param string $sql La consulta SQL a ejecutar.
         * @return mixed El resultado de la consulta.
         */
        public function execQuery($transaccion, $sql)
        {
            $TransjuegoLogMySqlDAO = new TransjuegoLogMySqlDAO($transaccion);
            $return = $TransjuegoLogMySqlDAO->querySQL($sql);

            //$return = json_decode(json_encode($return), FALSE);

            return $return;
        }
}
?>
