<?php namespace Backend\dto;
namespace Backend\dto;
use Backend\mysql\TransjuegoInfoMySqlDAO;
use Exception;
/** 
* Clase 'TransjuegoInfo'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'TransjuegoInfo'
* 
* Ejemplo de uso: 
* $TransjuegoInfo = new TransjuegoInfo();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class TransjuegoInfo
{

    /**
    * Representación de la columna 'transjuegoinfoId' de la tabla 'TransjuegoInfo'
    *
    * @var string
    */
    var $transjuegoinfoId;

    /**
    * Representación de la columna 'transjuegoId' de la tabla 'TransjuegoInfo'
    *
    * @var string
    */
    var $transjuegoId;

    /**
    * Representación de la columna 'tipo' de la tabla 'TransjuegoInfo'
    *
    * @var string
    */
    var $tipo;

    /**
    * Representación de la columna 'transaccionId' de la tabla 'TransjuegoInfo'
    *
    * @var string
    */
    var $transaccionId;

    /**
    * Representación de la columna 'descripcion' de la tabla 'TransjuegoInfo'
    *
    * @var string
    */
    var $descripcion;

    /**
    * Representación de la columna 'descripcion_txt' de la tabla 'TransjuegoInfo'
    *
    * @var string
    */
    var $descripcionTxt;

    /**
    * Representación de la columna 'fechaCrea' de la tabla 'TransjuegoInfo'
    *
    * @var string
    */
    var $fechaCrea;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'TransjuegoInfo'
    *
    * @var string
    */
    var $usucreaId;

    /**
    * Representación de la columna 'fechaModif' de la tabla 'TransjuegoInfo'
    *
    * @var string
    */
    var $fechaModif;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'TransjuegoInfo'
    *
    * @var string
    */
    var $usumodifId;

    /**
    * Representación de la columna 'valor' de la tabla 'TransjuegoInfo'
    *
    * @var string
    */
    var $valor;

    /**
    * Representación de la columna 'productoId' de la tabla 'TransjuegoInfo'
    *
    * @var string
    */
    var $productoId;

    /**
    * Representación de la columna 'transapiId' de la tabla 'TransjuegoInfo'
    *
    * @var string
    */
    var $transapiId;
    
    /**
    * Representación de la columna 'identificador' de la tabla 'TransjuegoInfo'
    *
    * @var string
    */
    var $identificador;





    /**
    * Constructor de clase
    *
    *
    * @param String $transjuegoinfoId transjuegoinfoId
    * @param String $transjuegoId transjuegoId
    * @param String $tipo tipo
    * @param String $transaccionId transaccionId
    * @param String $transapiId transapiId
    * @param String $identificador identificador
    *
    * @return no
    * @throws Exception si TransaccionJuegoInfo no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($transjuegoinfoId="",$transjuegoId="",$tipo="",$transaccionId="",$transapiId="",$identificador="")
    {
        $this->transjuegoinfoId = $transjuegoinfoId;

        if ($transjuegoId != "" && $transaccionId !="") {

            $this->transjuegoId = $transjuegoId;

            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();

            $TransaccionJuego = $TransjuegoInfoMySqlDAO->queryByTransjuegoIdAndTransaccionId($this->transjuegoId,$transaccionId);

            $TransaccionJuego=$TransaccionJuego[0];

            if ($TransaccionJuego != null && $TransaccionJuego != "") {
                $this->transjuegoinfoId = $TransaccionJuego->transjuegoinfoId;
                $this->transjuegoId = $TransaccionJuego->transjuegoId;
                $this->tipo = $TransaccionJuego->tipo;
                $this->transaccionId = $TransaccionJuego->transaccionId;
                $this->descripcion = $TransaccionJuego->descripcion;
                $this->descripcionTxt = $TransaccionJuego->descripcionTxt;
                $this->usucreaId = $TransaccionJuego->usucreaId;
                $this->usumodifId = $TransaccionJuego->usumodifId;
                $this->valor = $TransaccionJuego->valor;
                $this->productoId = $TransaccionJuego->productoId;
                $this->transapiId = $TransaccionJuego->transapiId;
                $this->identificador = $TransaccionJuego->identificador;
            }
            else {
                throw new Exception("No existe " . get_class($this), "28");
            }

        }elseif ($transjuegoId != "" && $tipo != "") {

            $this->transjuegoId = $transjuegoId;

            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();

            $TransaccionJuego = $TransjuegoInfoMySqlDAO->queryByTransjuegoIdAndTipo($this->transjuegoId,$tipo);

            $TransaccionJuego=$TransaccionJuego[0];

            if ($TransaccionJuego != null && $TransaccionJuego != "") {
                $this->transjuegoinfoId = $TransaccionJuego->transjuegoinfoId;
                $this->transjuegoId = $TransaccionJuego->transjuegoId;
                $this->tipo = $TransaccionJuego->tipo;
                $this->transaccionId = $TransaccionJuego->transaccionId;
                $this->descripcion = $TransaccionJuego->descripcion;
                $this->descripcionTxt = $TransaccionJuego->descripcionTxt;
                $this->usucreaId = $TransaccionJuego->usucreaId;
                $this->usumodifId = $TransaccionJuego->usumodifId;
                $this->valor = $TransaccionJuego->valor;
                $this->productoId = $TransaccionJuego->productoId;
                $this->transapiId = $TransaccionJuego->transapiId;
                $this->identificador = $TransaccionJuego->identificador;
            }
            else {
                throw new Exception("No existe " . get_class($this), "28");
            }

        }elseif ($transapiId != "" && $tipo != ""){
            $this->transjuegoId = $transjuegoId;

            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();

            $TransaccionJuego = $TransjuegoInfoMySqlDAO->queryByTransapiIdAndTipo($transapiId,$tipo);

            $TransaccionJuego=$TransaccionJuego[0];

            if ($TransaccionJuego != null && $TransaccionJuego != "") {
                $this->transjuegoinfoId = $TransaccionJuego->transjuegoinfoId;
                $this->transjuegoId = $TransaccionJuego->transjuegoId;
                $this->tipo = $TransaccionJuego->tipo;
                $this->transaccionId = $TransaccionJuego->transaccionId;
                $this->descripcion = $TransaccionJuego->descripcion;
                $this->descripcionTxt = $TransaccionJuego->descripcionTxt;
                $this->usucreaId = $TransaccionJuego->usucreaId;
                $this->usumodifId = $TransaccionJuego->usumodifId;
                $this->valor = $TransaccionJuego->valor;
                $this->productoId = $TransaccionJuego->productoId;
                $this->transapiId = $TransaccionJuego->transapiId;
                $this->identificador = $TransaccionJuego->identificador;
            }
            else {
                throw new Exception("No existe " . get_class($this), "28");
            }

        }elseif ($identificador != ""){
            $this->transjuegoId = $transjuegoId;

            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();

            $TransaccionJuego = $TransjuegoInfoMySqlDAO->queryByIdentificador($identificador);

            $TransaccionJuego=$TransaccionJuego[0];

            if ($TransaccionJuego != null && $TransaccionJuego != "") {
                $this->transjuegoinfoId = $TransaccionJuego->transjuegoinfoId;
                $this->transjuegoId = $TransaccionJuego->transjuegoId;
                $this->tipo = $TransaccionJuego->tipo;
                $this->transaccionId = $TransaccionJuego->transaccionId;
                $this->descripcion = $TransaccionJuego->descripcion;
                $this->descripcionTxt = $TransaccionJuego->descripcionTxt;
                $this->usucreaId = $TransaccionJuego->usucreaId;
                $this->usumodifId = $TransaccionJuego->usumodifId;
                $this->valor = $TransaccionJuego->valor;
                $this->productoId = $TransaccionJuego->productoId;
                $this->transapiId = $TransaccionJuego->transapiId;
                $this->identificador = $TransaccionJuego->identificador;
            }
            else {
                throw new Exception("No existe " . get_class($this), "28");
            }

        }
        elseif (!empty($transjuegoinfoId)) {
            $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();
            $TransjuegoInfo = $TransjuegoInfoMySqlDAO->load($transjuegoinfoId);

            if ($TransjuegoInfo == '') throw new Exception("No existe " . get_class($this), "28");

            //Toda propiedad definida en el readrow del MySqlDAO y en el dto será inicializada por el foreach
            foreach ($TransjuegoInfo as $propiedad => $valor) {
                $this->$propiedad = $valor;
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
     * Obtener la descripción de un objeto
     *
     * @return string Descripción
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Modificar la descripción de un objeto
     *
     * @param string $descripcion Descripción
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    /**
     * Obtener la descripción txt de un objeto
     *
     * @return string Descripción txt
     */
    public function getDescripcionTxt()
    {
        return $this->descripcionTxt;
    }

    /**
     * Modificar la descripción txt de un objeto
     *
     * @param string $descripcionTxt Descripción txt
     */
    public function setDescripcionTxt($descripcionTxt)
    {
        $this->descripcionTxt = $descripcionTxt;
    }

    /**
     * Obtener el ID del producto de un objeto
     *
     * @return string ID del producto
     */
    public function getProductoId()
    {
        return $this->productoId;
    }

    /**
     * Modificar el ID del producto de un objeto
     *
     * @param string $productoId ID del producto
     */
    public function setProductoId($productoId)
    {
        $this->productoId = $productoId;
    }

    /**
     * Obtener el ID de la API de transacción de un objeto
     *
     * @return string ID de la API de transacción
     */
    public function getTransapiId()
    {
        return $this->transapiId;
    }

    /**
     * Modificar el ID de la API de transacción de un objeto
     *
     * @param string $transapiId ID de la API de transacción
     */
    public function setTransapiId($transapiId)
    {
        $this->transapiId = $transapiId;
    }

    /**
     * Obtener el identificador de un objeto
     *
     * @return string Identificador
     */
    public function getIdentificador()
    {
        return $this->identificador;
    }

    /**
     * Modificar el identificador de un objeto
     *
     * @param string $identificador Identificador
     */
    public function setIdentificador($identificador)
    {
        $this->identificador = $identificador;
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
     * Obtener el campo descripcion de un objeto
     *
     * @return String descripcion descripcion
     * 
     */
    public function getTValue()
    {
        return $this->descripcion;
    }

    /**
     * Modificar el campo 'descripcion' de un objeto
     *
     * @param String $descripcion descripcion
     *
     * @return no
     *
     */
    public function setTValue($descripcion)
    {
        $this->descripcion = $descripcion;
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

        $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();

        $TransjuegoInfo = $TransjuegoInfoMySqlDAO->queryByTransaccionId($this->transaccionId);

        if (oldCount($TransjuegoInfo) > 0) {
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

        $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();

        $TransjuegoInfo = $TransjuegoInfoMySqlDAO->queryByTransjuegoId($this->transjuegoId);

        $contDebit = 0;
        $contCredit = 0;

        foreach ($TransjuegoInfo as $log) {
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

        $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO($transaction);

        return $TransjuegoInfoMySqlDAO->insert($this);

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
     * Obtener el campo transjuegoinfoId de un objeto
     *
     * @return String transjuegoinfoId transjuegoinfoId
     * 
     */
    public function getTransjuegologId()
    {
        return $this->transjuegoinfoId;
    }



    /**
    * Realizar una consulta en la tabla de TransJuegoInfo 'TransJuegoInfo'
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
    * @throws Exception si las transaciones no existen
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getTransjuegoInfosCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping)
    {

        $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();

        $transacciones = $TransjuegoInfoMySqlDAO->queryTransaccionesCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping);

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
     * Realizar una consulta personalizada en la tabla de TransJuegoInfo 'TransJuegoInfo'
     *
     * @param string $select Campos de consulta
     * @param string $sidx Columna para ordenar
     * @param string $sord Orden de los datos (asc | desc)
     * @param string $start Inicio de la consulta
     * @param string $limit Límite de la consulta
     * @param string $filters Condiciones de la consulta
     * @param boolean $searchOn Utilizar los filtros o no
     * @param string $grouping Columna para agrupar
     * @param string $mainTable Tabla principal
     * @param array $joins Tablas para unir
     * @param boolean $groupingCount Contar agrupaciones
     *
     * @return array Resultado de la consulta
     * @throws Exception Si las transacciones no existen
     */
    public function getSuperCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping, $mainTable, $joins = [], $groupingCount = false)
    {

        $TransjuegoInfoMySqlDAO = new TransjuegoInfoMySqlDAO();

        $transacciones = $TransjuegoInfoMySqlDAO->querySuperCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping, $mainTable, $joins, $groupingCount);

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