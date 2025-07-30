<?php namespace Backend\mysql;

/**
 * Class that operate on table 'usucasino_detalle_resumen'. Database Mysql.
 *
 * @author: Daniel Tamayo
 * @date: 2017-09-06 18:57
 * @package No
 * @category No
 * @version    1.0
 */


use Backend\dao\UsucasinoDetalleResumenDAO;
use Backend\dto\UsucasinoDetalleResumen;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Exception;

use Backend\dto\Helpers;

class UsucasinoDetalleResumenMySqlDAO implements UsucasinoDetalleResumenDAO
{


    /**
     * @var Transaction
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
     * TransaccionUsucasinoDetalleResumenMySqlDAO constructor.
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
     * Get Domain object by primry key
     *
     * @param String $id primary key
     * @return UsucasinoDetalleResumenMySql
     */
    public function load($id)
    {
        $sql = 'SELECT * FROM usucasino_detalle_resumen WHERE usucasdetresume_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($id);
        return $this->getRow($sqlQuery);
    }

    /**
     * Get all records from table
     */
    public function queryAll()
    {
        $sql = 'SELECT * FROM usucasino_detalle_resumen';
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }

    /**
     * Get all records from table ordered by field
     *
     * @param $orderColumn column name
     */
    public function queryAllOrderBy($orderColumn)
    {
        $sql = 'SELECT * FROM usucasino_detalle_resumen ORDER BY ' . $orderColumn;
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }

    /**
     * Delete record from table
     * @param usucasino_detalle_resumen primary key
     */
    public function delete($usucasdetresume_id)
    {
        $sql = 'DELETE FROM usucasino_detalle_resumen WHERE usucasdetresume_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($usucasdetresume_id);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Insert record to table
     *
     * @param UsucasinoDetalleResumenMySql usucasino_detalle_resumen
     */
    public function insert($usucasino_detalle_resumen)
    {
        $sql = 'INSERT INTO usucasino_detalle_resumen (usuario_id, tipo, estado, valor,cantidad, usucrea_id, usumodif_id, valor_premios, producto_id) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($usucasino_detalle_resumen->usuarioId);
        $sqlQuery->set($usucasino_detalle_resumen->tipo);
        $sqlQuery->set($usucasino_detalle_resumen->estado);
        $sqlQuery->set($usucasino_detalle_resumen->valor);
        $sqlQuery->set($usucasino_detalle_resumen->cantidad);
        $sqlQuery->setNumber($usucasino_detalle_resumen->usucreaId);
        $sqlQuery->setNumber($usucasino_detalle_resumen->usumodifId);
        $sqlQuery->set($usucasino_detalle_resumen->valorPremios);
        $sqlQuery->set($usucasino_detalle_resumen->productoId);

        $id = $this->executeInsert($sqlQuery);
        $usucasino_detalle_resumen->usucasdetresumeId = $id;
        return $id;
    }

    /**
     * Update record in table
     *
     * @param UsucasinoDetalleResumenMySql usucasino_detalle_resumen
     */
    public function update($usucasino_detalle_resumen)
    {
        $sql = 'UPDATE usucasino_detalle_resumen SET usuario_id = ?, tipo = ?, estado = ?, valor = ?, cantidad = ?, usucrea_id = ?, usumodif_id = ?, valor_premios = ?, producto_id = ? WHERE usucasdetresume_id = ?';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($usucasino_detalle_resumen->usuarioId);
        $sqlQuery->set($usucasino_detalle_resumen->tipo);
        $sqlQuery->set($usucasino_detalle_resumen->estado);
        $sqlQuery->set($usucasino_detalle_resumen->valor);
        $sqlQuery->set($usucasino_detalle_resumen->cantidad);
        $sqlQuery->setNumber($usucasino_detalle_resumen->usucreaId);
        $sqlQuery->setNumber($usucasino_detalle_resumen->usumodifId);
        $sqlQuery->set($usucasino_detalle_resumen->valorPremios);
        $sqlQuery->set($usucasino_detalle_resumen->productoId);


        $sqlQuery->setNumber($usucasino_detalle_resumen->usucasdetresumeId);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Delete all rows
     */
    public function clean()
    {
        $sql = 'DELETE FROM usucasino_detalle_resumen';
        $sqlQuery = new SqlQuery($sql);
        return $this->executeUpdate($sqlQuery);
    }


    /**
     * Query records by 'fecha_crea' field.
     *
     * @param mixed $value The value to search for.
     * @return array The list of matching records.
     */
    public function queryByFechaCrea($value)
    {
        $sql = 'SELECT * FROM usucasino_detalle_resumen WHERE fecha_crea = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Query records by 'usucrea_id' field.
     *
     * @param mixed $value The value to search for.
     * @return array The list of matching records.
     */
    public function queryByUsucreaId($value)
    {
        $sql = 'SELECT * FROM usucasino_detalle_resumen WHERE usucrea_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Query records by 'usumodif_id' field.
     *
     * @param mixed $value The value to search for.
     * @return array The list of matching records.
     */
    public function queryByUsumodifId($value)
    {
        $sql = 'SELECT * FROM usucasino_detalle_resumen WHERE usumodif_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Delete records by 'tipo' field.
     *
     * @param mixed $value The value to search for.
     * @return int The number of affected rows.
     */
    public function deleteByExternoId($value)
    {
        $sql = 'DELETE FROM usucasino_detalle_resumen WHERE tipo = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Delete records by 'valor' field.
     *
     * @param mixed $value The value to search for.
     * @return int The number of affected rows.
     */
    public function deleteByEstado($value)
    {
        $sql = 'DELETE FROM usucasino_detalle_resumen WHERE valor = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Delete records by 'fecha_crea' field.
     *
     * @param mixed $value The value to search for.
     * @return int The number of affected rows.
     */
    public function deleteByFechaCrea($value)
    {
        $sql = 'DELETE FROM usucasino_detalle_resumen WHERE fecha_crea = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Delete records by 'usucrea_id' field.
     *
     * @param mixed $value The value to search for.
     * @return int The number of affected rows.
     */
    public function deleteByUsucreaId($value)
    {
        $sql = 'DELETE FROM usucasino_detalle_resumen WHERE usucrea_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Delete records by 'usumodif_id' field.
     *
     * @param mixed $value The value to search for.
     * @return int The number of affected rows.
     */
    public function deleteByUsumodifId($value)
    {
        $sql = 'DELETE FROM usucasino_detalle_resumen WHERE usumodif_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->executeUpdate($sqlQuery);
    }


    /**
     * Read row
     *
     * @return UsucasinoDetalleResumenMySql
     */
    protected function readRow($row)
    {
        $usucasino_detalle_resumen = new UsucasinoDetalleResumen();

        $usucasino_detalle_resumen->usucasdetresumeId = $row['usucasdetresume_id'];
        $usucasino_detalle_resumen->usuarioId = $row['usuario_id'];
        $usucasino_detalle_resumen->tipo = $row['tipo'];
        $usucasino_detalle_resumen->estado = $row['estado'];
        $usucasino_detalle_resumen->valor = $row['valor'];
        $usucasino_detalle_resumen->cantidad = $row['cantidad'];
        $usucasino_detalle_resumen->usucreaId = $row['usucrea_id'];
        $usucasino_detalle_resumen->usumodifId = $row['usumodif_id'];
        $usucasino_detalle_resumen->valorPremios = $row['valor_premios'];
        $usucasino_detalle_resumen->productoId = $row['producto_id'];

        return $usucasino_detalle_resumen;
    }


    /**
     * Executes a custom query to retrieve and filter data from the usucasino_detalle_resumen table.
     *
     * @param string $select The columns to select in the query.
     * @param string $sidx The column by which to sort the results.
     * @param string $sord The sort order (ASC or DESC).
     * @param int $start The starting point for the results (used for pagination).
     * @param int $limit The maximum number of results to return (used for pagination).
     * @param string $filters JSON encoded string containing the filters to apply.
     * @param bool $searchOn Flag indicating whether to apply filters.
     * @param string $grouping The column by which to group the results.
     *
     * @return string JSON encoded string containing the count of results and the filtered data.
     */
    public function queryUsucasinoDetalleResumensCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$grouping)
    {


        $where = " where 1=1 ";


        if ($searchOn) {
            // Construye el where
            $filters = json_decode($filters);
            $whereArray = array();
            $rules = $filters->rules;
            $groupOperation = $filters->groupOp;
            $cont = 0;

            foreach ($rules as $rule) {
                $fieldName = $rule->field;
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
                        $fieldOperation = " NOT IN (" . $fieldData . ")";
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
        $sql = 'SELECT count(*) count FROM usucasino_detalle_resumen 
           INNER JOIN producto_mandante ON (usucasino_detalle_resumen.producto_id = producto_mandante.prodmandante_id)
          INNER JOIN producto ON (producto.producto_id = producto_mandante.producto_id)
          INNER JOIN proveedor ON (producto.proveedor_id = proveedor.proveedor_id)
          INNER JOIN subproveedor ON (producto.subproveedor_id = subproveedor.subproveedor_id)
          INNER JOIN usuario_mandante ON (usuario_mandante.usumandante_id = usucasino_detalle_resumen.usuario_id)

 ' . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT ' .$select .'  FROM usucasino_detalle_resumen 
          INNER JOIN producto_mandante ON (usucasino_detalle_resumen.producto_id = producto_mandante.prodmandante_id)
          INNER JOIN producto ON (producto.producto_id = producto_mandante.producto_id)
          INNER JOIN proveedor ON (producto.proveedor_id = proveedor.proveedor_id)
          INNER JOIN subproveedor ON (producto.subproveedor_id = subproveedor.subproveedor_id)
          INNER JOIN usuario_mandante ON (usuario_mandante.usumandante_id = usucasino_detalle_resumen.usuario_id)
          
          ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        if($_ENV["debugFixed2"] == '1'){
            print_r($sql);
           // exit();
        }

        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }

    /**
     * Retrieves a list of rows from the database based on the provided SQL query.
     *
     * @param SqlQuery $sqlQuery The SQL query to execute.
     * @return array An array of rows retrieved from the database.
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
     * Get row
     *
     * @return UsucasinoDetalleResumenMySql
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
     * Execute sql query
     */
    protected function execute($sqlQuery)
    {
        return QueryExecutor::execute($this->transaction, $sqlQuery);
    }

    /**
     * Execute2 sql query
     */
    protected function execute2($sqlQuery)
    {
        return QueryExecutor::execute2($this->transaction, $sqlQuery);
    }

    /**
     * Execute sql query
     */
    protected function executeUpdate($sqlQuery)
    {
        return QueryExecutor::executeUpdate($this->transaction, $sqlQuery);
    }

    /**
     * Query for one row and one column
     */
    protected function querySingleResult($sqlQuery)
    {
        return QueryExecutor::queryForString($this->transaction, $sqlQuery);
    }

    /**
     * Insert row to table
     */
    protected function executeInsert($sqlQuery)
    {
        return QueryExecutor::executeInsert($this->transaction, $sqlQuery);
    }
}

?>
