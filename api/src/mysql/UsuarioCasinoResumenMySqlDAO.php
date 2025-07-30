<?php namespace Backend\mysql;

/**
 * Class that operate on table 'usuario_casino_resumen'. Database Mysql.
 *
 * @author: Daniel Tamayo
 * @date: 2017-09-06 18:57
 * @package No
 * @category No
 * @version    1.0
 */

use Backend\dao\UsuarioCasinoResumenDAO;
use Backend\dto\UsuarioCasinoResumen;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Exception;

class UsuarioCasinoResumenMySqlDAO implements UsuarioCasinoResumenDAO
{


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
     * TransaccionUsuarioCasinoResumenMySqlDAO constructor.
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
     * @return UsuarioCasinoResumenMySql
     */
    public function load($id)
    {
        $sql = 'SELECT * FROM usuario_casino_resumen WHERE usucasresume_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($id);
        return $this->getRow($sqlQuery);
    }

    /**
     * Get all records from table
     */
    public function queryAll()
    {
        $sql = 'SELECT * FROM usuario_casino_resumen';
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
        $sql = 'SELECT * FROM usuario_casino_resumen ORDER BY ' . $orderColumn;
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }

    /**
     * Delete record from table
     * @param usuario_casino_resumen primary key
     */
    public function delete($usucasresume_id)
    {
        $sql = 'DELETE FROM usuario_casino_resumen WHERE usucasresume_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($usucasresume_id);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Insert record to table
     *
     * @param UsuarioCasinoResumenMySql usuario_casino_resumen
     */
    public function insert($usuario_casino_resumen)
    {
        $sql = 'INSERT INTO usuario_casino_resumen (usuario_id, tipo, estado, valor,cantidad, usucrea_id, usumodif_id, valor_premios) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($usuario_casino_resumen->usuarioId);
        $sqlQuery->set($usuario_casino_resumen->tipo);
        $sqlQuery->set($usuario_casino_resumen->estado);
        $sqlQuery->set($usuario_casino_resumen->valor);
        $sqlQuery->set($usuario_casino_resumen->cantidad);
        $sqlQuery->setNumber($usuario_casino_resumen->usucreaId);
        $sqlQuery->setNumber($usuario_casino_resumen->usumodifId);
        $sqlQuery->set($usuario_casino_resumen->valorPremios);

        $id = $this->executeInsert($sqlQuery);
        $usuario_casino_resumen->usucasresumeId = $id;
        return $id;
    }

    /**
     * Update record in table
     *
     * @param UsuarioCasinoResumenMySql usuario_casino_resumen
     */
    public function update($usuario_casino_resumen)
    {
        $sql = 'UPDATE usuario_casino_resumen SET usuario_id = ?, tipo = ?, estado = ?, valor = ?, cantidad = ?, usucrea_id = ?, usumodif_id = ?, valor_premios = ? WHERE usucasresume_id = ?';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($usuario_casino_resumen->usuarioId);
        $sqlQuery->set($usuario_casino_resumen->tipo);
        $sqlQuery->set($usuario_casino_resumen->estado);
        $sqlQuery->set($usuario_casino_resumen->valor);
        $sqlQuery->set($usuario_casino_resumen->cantidad);
        $sqlQuery->setNumber($usuario_casino_resumen->usucreaId);
        $sqlQuery->setNumber($usuario_casino_resumen->usumodifId);
        $sqlQuery->set($usuario_casino_resumen->valorPremios);

        $sqlQuery->setNumber($usuario_casino_resumen->usucasresumeId);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Delete all rows
     */
    public function clean()
    {
        $sql = 'DELETE FROM usuario_casino_resumen';
        $sqlQuery = new SqlQuery($sql);
        return $this->executeUpdate($sqlQuery);
    }


/**
     * Query records by `fecha_crea` field.
     *
     * @param string $value The value of `fecha_crea` to filter by.
     * @return array The list of `UsuarioCasinoResumen` objects.
     */
    public function queryByFechaCrea($value)
    {
        $sql = 'SELECT * FROM usuario_casino_resumen WHERE fecha_crea = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Query records by `usucrea_id` field.
     *
     * @param int $value The value of `usucrea_id` to filter by.
     * @return array The list of `UsuarioCasinoResumen` objects.
     */
    public function queryByUsucreaId($value)
    {
        $sql = 'SELECT * FROM usuario_casino_resumen WHERE usucrea_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);
    }

/**
     * Query records by `usumodif_id` field.
     *
     * @param int $value The value of `usumodif_id` to filter by.
     * @return array The list of `UsuarioCasinoResumen` objects.
     */
    public function queryByUsumodifId($value)
    {
        $sql = 'SELECT * FROM usuario_casino_resumen WHERE usumodif_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Delete records by `tipo` field.
     *
     * @param string $value The value of `tipo` to filter by.
     * @return int The number of affected rows.
     */
    public function deleteByExternoId($value)
    {
        $sql = 'DELETE FROM usuario_casino_resumen WHERE tipo = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Delete records by `valor` field.
     *
     * @param string $value The value of `valor` to filter by.
     * @return int The number of affected rows.
     */
    public function deleteByEstado($value)
    {
        $sql = 'DELETE FROM usuario_casino_resumen WHERE valor = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Delete records by `fecha_crea` field.
     *
     * @param string $value The value of `fecha_crea` to filter by.
     * @return int The number of affected rows.
     */
    public function deleteByFechaCrea($value)
    {
        $sql = 'DELETE FROM usuario_casino_resumen WHERE fecha_crea = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Delete records by `usucrea_id` field.
     *
     * @param int $value The value of `usucrea_id` to filter by.
     * @return int The number of affected rows.
     */
    public function deleteByUsucreaId($value)
    {
        $sql = 'DELETE FROM usuario_casino_resumen WHERE usucrea_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Delete records by `usumodif_id` field.
     *
     * @param int $value The value of `usumodif_id` to filter by.
     * @return int The number of affected rows.
     */
    public function deleteByUsumodifId($value)
    {
        $sql = 'DELETE FROM usuario_casino_resumen WHERE usumodif_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->executeUpdate($sqlQuery);
    }


    /**
     * Read row
     *
     * @return UsuarioCasinoResumenMySql
     */
    protected function readRow($row)
    {
        $usuario_casino_resumen = new UsuarioCasinoResumen();

        $usuario_casino_resumen->usucasresumeId = $row['usucasresume_id'];
        $usuario_casino_resumen->usuarioId = $row['usuario_id'];
        $usuario_casino_resumen->tipo = $row['tipo'];
        $usuario_casino_resumen->estado = $row['estado'];
        $usuario_casino_resumen->valor = $row['valor'];
        $usuario_casino_resumen->cantidad = $row['cantidad'];
        $usuario_casino_resumen->usucreaId = $row['usucrea_id'];
        $usuario_casino_resumen->usumodifId = $row['usumodif_id'];
        $usuario_casino_resumen->valorPremios = $row['valor_premios'];

        return $usuario_casino_resumen;
    }

    /**
     * Executes a custom query on the `usuario_casino_resumen` table with various filtering, sorting, and grouping options.
     *
     * @param string $select The columns to select in the query.
     * @param string $sidx The column to sort by.
     * @param string $sord The sort order (ASC or DESC).
     * @param int $start The starting point for the LIMIT clause.
     * @param int $limit The number of records to return.
     * @param string $filters JSON encoded string containing the filters to apply.
     * @param bool $searchOn Flag indicating whether to apply filters.
     * @param string $grouping The column(s) to group by.
     * 
     * @return string JSON encoded string containing the count of records and the data.
     */
    public function queryUsuarioCasinoResumensCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$grouping)
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
                    $where = $where . " " . $groupOperation . " UCASE(CAST(" . $fieldName . " AS CHAR)) " . strtoupper($fieldOperation);
                } else {
                    $where = "";
                }
            }

        }

        if($grouping != ""){
            $where = $where . " GROUP BY " . $grouping;
        }
        $sql = 'SELECT count(*) count FROM usuario_casino_resumen  ' . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT ' .$select .'  FROM usuario_casino_resumen  ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;



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
     * @return UsuarioCasinoResumenMySql
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
