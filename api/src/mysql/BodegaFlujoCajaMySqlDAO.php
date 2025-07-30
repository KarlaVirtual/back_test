<?php namespace Backend\mysql;

/**
 * Class that operate on table 'bodega_flujo_caja'. Database Mysql.
 *
 * @author: DT
 * @date: 2017-09-06 18:57
 */

use Backend\dao\BodegaFlujoCajaDAO;
use Backend\dto\BodegaFlujoCaja;
use Backend\dto\Helpers;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Exception;

/**
 * Clase 'BodegaFlujoCajaMySqlDAO'
 *
 * Esta clase provee las consultas del modelo o tabla 'bodega_flujo_caja'
 *
 * Ejemplo de uso:
 * $BodegaFlujoCajaMySqlDAO = new BodegaFlujoCajaMySqlDAO();
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class BodegaFlujoCajaMySqlDAO implements BodegaFlujoCajaDAO
{

    /**
     * Atributo Transaction transacción
     *
     * @var Objeto
     */
    private $transaction;

    /**
     * @return mixed
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * @param mixed $transaction
     */
    public function setTransaction($transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Constructor de la clase
     *
     * TransaccionBodegaFlujoCajaMySqlDAO constructor.
     * @param $transaction
     */

    public function __construct($transaction="")
    {
        if ($transaction == "") {

            $transaction = new Transaction();
            $this->transaction = $transaction;

        } else {
            $this->transaction = $transaction;
        }
    }


    /**
     * Get Domain object by primary key
     *
     * @param String $id primary key
     * @return BodegaFlujoCajaMySql
     */
    public function load($id)
    {
        $sql = 'SELECT * FROM bodega_flujo_caja WHERE usurecresume_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($id);
        return $this->getRow($sqlQuery);
    }

    /**
     * Obtener todos los registros condicionados por la
     * llave primaria que se pasa como parámetro
     *
     * @param String $id llave primaria
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryAll()
    {
        $sql = 'SELECT * FROM bodega_flujo_caja';
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros
     * ordenadas por el nombre de la columna
     * que se pasa como parámetro
     *
     * @param String $orderColumn nombre de la columna
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryAllOrderBy($orderColumn)
    {
        $sql = 'SELECT * FROM bodega_flujo_caja ORDER BY ' . $orderColumn;
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }

    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $trnID llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function delete($usurecresume_id)
    {
        $sql = 'DELETE FROM bodega_flujo_caja WHERE usurecresume_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($usurecresume_id);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Insertar un registro en la base de datos
     *
     * @param Objeto $bodega_flujo_caja bodega_flujo_caja
     *
     * @return String $id resultado de la consulta
     *
     */
    public function insert($bodega_flujo_caja)
    {
        return '';
    }

    /**
     * Editar un registro en la base de datos
     *
     * @param Objeto $bodega_flujo_caja bodega_flujo_caja
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function update($bodega_flujo_caja)
    {
        return '';
    }


    /**
     * Crear y devolver un objeto del tipo bodega_flujo_caja
     * con los valores de una consulta sql
     *
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Objeto $bodega_flujo_cajan bodega_flujo_caja
     *
     * @access protected
     *
     */
    protected function readRow($row)
    {
        $bodega_flujo_caja = new BodegaFlujoCaja();

        $bodega_flujo_caja->paisId = $row['usuario_id'];
        $bodega_flujo_caja->mandante = $row['pais_id'];
        $bodega_flujo_caja->fecha = $row['fecha'];
        $bodega_flujo_caja->saldoApuestas = $row['valor_entrada_efectivo'];
        $bodega_flujo_caja->cantidad = $row['cantidad'];
        $bodega_flujo_caja->primerosDepositos = $row['primeros_depositos'];
        $bodega_flujo_caja->usuariosRegistrados = $row['usuarios_registrados'];

        $bodega_flujo_caja->saldoPremios = $row['saldo_premios'];
        $bodega_flujo_caja->saldoPremiosPendientes = $row['saldo_premios_pendientes'];
        $bodega_flujo_caja->saldoBono = $row['saldo_bono'];
        $bodega_flujo_caja->tipoUsuario = $row['tipo_usuario'];
        $bodega_flujo_caja->tipoFecha = $row['tipo_fecha'];
        
        return $bodega_flujo_caja;
    }

    /**
     * Realizar una consulta en la tabla de transacciones 'bodega_flujo_caja'
     * de una manera personalizada
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
     * @return Array $json resultado de la consulta
     *
     */
    public function queryBodegaFlujoCajasCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$grouping,$innProducto=false,$innConcesionario=false)
    {


        $where = " where 1=1 ";

        $Helpers = new Helpers();

        if ($searchOn) {
            // Construye el where
            $filters = json_decode($filters);
            $whereArray = array();
            $rules = $filters->rules;
            $groupOperation = $filters->groupOp;
            $cont = 0;

            foreach ($rules as $rule) {
                $fieldName = $Helpers->process_data($rule->field);
                $fieldData = $rule->data;
                

                $Helpers = new Helpers();

                if($fieldName == 'registro.cedula'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_DOCUMENT']);
                }
                if($fieldName == 'registro.celular'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_PHONE']);
                }
                if($fieldName == 'usuario.cedula'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_DOCUMENT']);
                }
                if($fieldName == 'usuario.login'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }
                if($fieldName == 'usuario_mandante.email'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }
                if($fieldName == 'punto_venta.email'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }
                if($fieldName == 'usuario_sitebuilder.login'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }
                switch ($rule->op) {
                    case "eq":
                        $fieldOperation = " = '" . $fieldData . "'";
                        break;
                    case "ne":
                        $fieldOperation = " != '" . $fieldData . "'";
                        break;
                    case "lt":
                        $fieldOperation = " < '" . $fieldData . "'";
                        break;
                    case "gt":
                        $fieldOperation = " > '" . $fieldData . "'";
                        break;
                    case "le":
                        $fieldOperation = " <= '" . $fieldData . "'";
                        break;
                    case "ge":
                        $fieldOperation = " >= '" . $fieldData . "'";
                        break;
                    case "nu":
                        $fieldOperation = " = ''";
                        break;
                    case "nn":
                        $fieldOperation = " != ''";
                        break;
                    case "in":
                        $fieldOperation = " IN (" . $fieldData . ")";
                        break;
                    case "ni":
                        $fieldOperation = " NOT IN '" . $fieldData . "'";
                        break;
                    case "bw":
                        $fieldOperation = " LIKE '" . $fieldData . "%'";
                        break;
                    case "bn":
                        $fieldOperation = " NOT LIKE '" . $fieldData . "%'";
                        break;
                    case "ew":
                        $fieldOperation = " LIKE '%" . $fieldData . "'";
                        break;
                    case "en":
                        $fieldOperation = " NOT LIKE '%" . $fieldData . "'";
                        break;
                    case "cn":
                        $fieldOperation = " LIKE '%" . $fieldData . "%'";
                        break;
                    case "nc":
                        $fieldOperation = " NOT LIKE '%" . $fieldData . "%'";
                        break;
                    default:
                        $fieldOperation = "";
                        break;
                }
                if ($fieldOperation != "") $whereArray[] = $fieldName . $fieldOperation;
                if (oldCount($whereArray) > 0) {
                    $where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
                } else {
                    $where = "";
                }
            }

        }

        if($grouping != ""){
            $where = $where . " GROUP BY " . $grouping;
        }

        $table_name=' bodega_flujo_caja ';

        if($_SESSION['usuario'] =='4089418' || $_SESSION['usuario'] =='4089493' || $_SESSION['usuario'] =='1578169' || $_SESSION['usuario'] =='6997123'){
            $table_name=' bodega_flujo_caja_rf bodega_flujo_caja ';
        }

        $sql = 'SELECT count(*) count FROM '.$table_name.'  INNER JOIN pais ON (pais.pais_id = bodega_flujo_caja.pais_id)  INNER JOIN mandante ON (mandante.mandante = bodega_flujo_caja.mandante)  INNER JOIN usuario ON (usuario.usuario_id = bodega_flujo_caja.usuario_id) INNER JOIN concesionario ON (concesionario.concesionario_id = bodega_flujo_caja.concesionario_id) LEFT OUTER JOIN punto_venta ON (usuario.puntoventa_id = punto_venta.usuario_id) LEFT OUTER JOIN usuario agente ON (agente.usuario_id = concesionario.usupadre_id)  LEFT OUTER JOIN usuario agente2 ON (agente2.usuario_id = concesionario.usupadre2_id)   LEFT OUTER JOIN usuario_perfil  ON (usuario.puntoventa_id = usuario_perfil.usuario_id)' . $where;

        if($_ENV["debugFixed2"]){
            print_r($sql);
        }

        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT ' .$select .' FROM  '.$table_name.'   INNER JOIN pais ON (pais.pais_id = bodega_flujo_caja.pais_id)  INNER JOIN mandante ON (mandante.mandante = bodega_flujo_caja.mandante)  INNER JOIN usuario ON (usuario.usuario_id = bodega_flujo_caja.usuario_id) INNER JOIN concesionario ON (concesionario.concesionario_id = bodega_flujo_caja.concesionario_id) LEFT OUTER JOIN punto_venta ON (usuario.puntoventa_id = punto_venta.usuario_id) LEFT OUTER JOIN usuario agente ON (agente.usuario_id = concesionario.usupadre_id)  LEFT OUTER JOIN usuario agente2 ON (agente2.usuario_id = concesionario.usupadre2_id)  LEFT OUTER JOIN usuario_perfil  ON (usuario.puntoventa_id = usuario_perfil.usuario_id)   
        
        ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        if($_REQUEST["isDebug"]=="1"){

            print_r($sql);
        }


        $sqlQuery = new SqlQuery($sql);

        $result = $Helpers->process_data($this->execute2($sqlQuery));

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }

    /**
     * Ejecutar una consulta sql y devolver los datos
     * como un arreglo asociativo
     *
     *
     * @param String $sqlQuery consulta sql
     *
     * @return Array $ret arreglo indexado
     *
     * @access protected
     *
     */
    protected function getList($sqlQuery)
    {
        $tab = QueryExecutor::execute($this->transaction, $sqlQuery);
        $ret = array();
        for ($i = 0; $i < oldCount($tab); $i++) {
            $ret[$i] = $this->readRow($tab[$i]);
        }
        return $ret;
    }

    /**
     * Ejecutar una consulta sql y devolver el resultado como un arreglo
     *
     *
     * @param String $sqlQuery consulta sql
     *
     * @return Array $ resultado de la ejecución
     *
     * @access protected
     *
     */
    protected function getRow($sqlQuery)
    {
        $tab = QueryExecutor::execute($this->transaction, $sqlQuery);
        if (oldCount($tab) == 0) {
            return null;
        }
        return $this->readRow($tab[0]);
    }

    /**
     * Ejecutar una consulta sql
     *
     *
     * @param String $sqlQuery consulta sql
     *
     * @return Array $ resultado de la ejecución
     *
     * @access protected
     *
     */
    protected function execute($sqlQuery)
    {
        return QueryExecutor::execute($this->transaction, $sqlQuery);
    }

    /**
     * Ejecutar una consulta sql 2
     *
     *
     * @param String $sqlQuery consulta sql
     *
     * @return Array $ resultado de la ejecución
     *
     * @access protected
     *
     */
    protected function execute2($sqlQuery)
    {
        return QueryExecutor::execute2($this->transaction, $sqlQuery);
    }

    /**
     * Ejecutar una consulta sql como update
     *
     *
     * @param String $sqlQuery consulta sql
     *
     * @return Array $ resultado de la ejecución
     *
     * @access protected
     *
     */
    protected function executeUpdate($sqlQuery)
    {
        return QueryExecutor::executeUpdate($this->transaction, $sqlQuery);
    }

    /**
     * Ejecutar una consulta sql como select
     *
     *
     * @param String $sqlQuery consulta sql
     *
     * @return Array $ resultado de la ejecución
     *
     * @access protected
     *
     */
    protected function querySingleResult($sqlQuery)
    {
        return QueryExecutor::queryForString($this->transaction, $sqlQuery);
    }

    /**
     * Ejecutar una consulta sql como insert
     *
     *
     * @param String $sqlQuery consulta sql
     *
     * @return Array $ resultado de la ejecución
     *
     * @access protected
     *
     */
    protected function executeInsert($sqlQuery)
    {
        return QueryExecutor::executeInsert($this->transaction, $sqlQuery);
    }
}

?>
