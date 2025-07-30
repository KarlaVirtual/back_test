<?php namespace Backend\mysql;



use Backend\dao\UsuarioBonoResumenDAO;
use Backend\dto\UsuarioBonoResumen;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Exception;

/**
 * Class that operate on table 'usuario_bono_resumen'. Database Mysql.
 *
 * @author: Daniel Tamayo
 * @since: 2017-09-06 18:57
 * @category No
 * @package No
 * @version     1.0
 */

class UsuarioBonoResumenMySqlDAO implements UsuarioBonoResumenDAO
{

    /** Objeto vincula una conexión de la base de datos con el objeto correspondiente
     * @var Transaction $transaction
     */
    private $transaction;


/**
 * Get the current transaction.
 *
 * @return Transaction The current transaction object.
 */
public function getTransaction()
{
    return $this->transaction;
}

/**
 * Set the transaction.
 *
 * @param Transaction $transaction The transaction object to set.
 */
public function setTransaction($transaction)
{
    $this->transaction = $transaction;
}

    /**
     * TransaccionUsuarioBonoResumenMySqlDAO constructor.
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
     * @return UsuarioBonoResumenMySql
     */
    public function load($id)
    {
        $sql = 'SELECT * FROM usuario_bono_resumen WHERE usubonoresume_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($id);
        return $this->getRow($sqlQuery);
    }

    /**
     * Get all records from table
     */
    public function queryAll()
    {
        $sql = 'SELECT * FROM usuario_bono_resumen';
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
        $sql = 'SELECT * FROM usuario_bono_resumen ORDER BY ' . $orderColumn;
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }

    /**
     * Delete record from table
     * @param usuario_bono_resumen primary key
     */
    public function delete($usubonoresume_id)
    {
        $sql = 'DELETE FROM usuario_bono_resumen WHERE usubonoresume_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($usubonoresume_id);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Insert record to table
     *
     * @param UsuarioBonoResumenMySql usuario_bono_resumen
     */
    public function insert($usuario_bono_resumen)
    {
        $sql = 'INSERT INTO usuario_bono_resumen (usuario_id, tipo, estado, valor,cantidad, usucrea_id, usumodif_id) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?)';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($usuario_bono_resumen->usuarioId);
        $sqlQuery->set($usuario_bono_resumen->tipo);
        $sqlQuery->set($usuario_bono_resumen->estado);
        $sqlQuery->set($usuario_bono_resumen->valor);
        $sqlQuery->set($usuario_bono_resumen->cantidad);
        $sqlQuery->setNumber($usuario_bono_resumen->usucreaId);
        $sqlQuery->setNumber($usuario_bono_resumen->usumodifId);

        $id = $this->executeInsert($sqlQuery);
        $usuario_bono_resumen->usubonoresumeId = $id;
        return $id;
    }

    /**
     * Update record in table
     *
     * @param UsuarioBonoResumenMySql usuario_bono_resumen
     */
    public function update($usuario_bono_resumen)
    {
        $sql = 'UPDATE usuario_bono_resumen SET usuario_id = ?, tipo = ?, estado = ?, valor = ?, cantidad = ?, usucrea_id = ?, usumodif_id = ? WHERE usubonoresume_id = ?';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($usuario_bono_resumen->usuarioId);
        $sqlQuery->set($usuario_bono_resumen->tipo);
        $sqlQuery->set($usuario_bono_resumen->estado);
        $sqlQuery->set($usuario_bono_resumen->valor);
        $sqlQuery->set($usuario_bono_resumen->cantidad);
        $sqlQuery->setNumber($usuario_bono_resumen->usucreaId);
        $sqlQuery->setNumber($usuario_bono_resumen->usumodifId);

        $sqlQuery->setNumber($usuario_bono_resumen->usubonoresumeId);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Delete all rows
     */
    public function clean()
    {
        $sql = 'DELETE FROM usuario_bono_resumen';
        $sqlQuery = new SqlQuery($sql);
        return $this->executeUpdate($sqlQuery);
    }


/**
     * Query records by 'fecha_crea' field.
     *
     * @param string $value The value to filter by.
     * @return array The list of matching records.
     */
    public function queryByFechaCrea($value)
    {
        $sql = 'SELECT * FROM usuario_bono_resumen WHERE fecha_crea = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Query records by 'usucrea_id' field.
     *
     * @param int $value The value to filter by.
     * @return array The list of matching records.
     */
    public function queryByUsucreaId($value)
    {
        $sql = 'SELECT * FROM usuario_bono_resumen WHERE usucrea_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Query records by 'usumodif_id' field.
     *
     * @param int $value The value to filter by.
     * @return array The list of matching records.
     */
    public function queryByUsumodifId($value)
    {
        $sql = 'SELECT * FROM usuario_bono_resumen WHERE usumodif_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Delete records by 'tipo' field.
     *
     * @param string $value The value to filter by.
     * @return int The number of affected rows.
     */
    public function deleteByExternoId($value)
    {
        $sql = 'DELETE FROM usuario_bono_resumen WHERE tipo = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Delete records by 'valor' field.
     *
     * @param string $value The value to filter by.
     * @return int The number of affected rows.
     */
    public function deleteByEstado($value)
    {
        $sql = 'DELETE FROM usuario_bono_resumen WHERE valor = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Delete records by 'fecha_crea' field.
     *
     * @param string $value The value to filter by.
     * @return int The number of affected rows.
     */
    public function deleteByFechaCrea($value)
    {
        $sql = 'DELETE FROM usuario_bono_resumen WHERE fecha_crea = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Delete records by 'usucrea_id' field.
     *
     * @param int $value The value to filter by.
     * @return int The number of affected rows.
     */
    public function deleteByUsucreaId($value)
    {
        $sql = 'DELETE FROM usuario_bono_resumen WHERE usucrea_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Delete records by 'usumodif_id' field.
     *
     * @param int $value The value to filter by.
     * @return int The number of affected rows.
     */
    public function deleteByUsumodifId($value)
    {
        $sql = 'DELETE FROM usuario_bono_resumen WHERE usumodif_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->executeUpdate($sqlQuery);
    }


    /**
     * Read row
     *
     * @return UsuarioBonoResumenMySql
     */
    protected function readRow($row)
    {
        $usuario_bono_resumen = new UsuarioBonoResumen();

        $usuario_bono_resumen->usubonoresumeId = $row['usubonoresume_id'];
        $usuario_bono_resumen->usuarioId = $row['usuario_id'];
        $usuario_bono_resumen->tipo = $row['tipo'];
        $usuario_bono_resumen->estado = $row['estado'];
        $usuario_bono_resumen->valor = $row['valor'];
        $usuario_bono_resumen->cantidad = $row['cantidad'];
        $usuario_bono_resumen->usucreaId = $row['usucrea_id'];
        $usuario_bono_resumen->usumodifId = $row['usumodif_id'];

        return $usuario_bono_resumen;
    }


    /**
     * Executes a custom query on the `usuario_bono_resumen` table with various filtering, sorting, and grouping options.
     *
     * @param string $select The columns to select in the query.
     * @param string $sidx The column by which to sort the results.
     * @param string $sord The sort order (ASC or DESC).
     * @param int $start The starting point for the results (used for pagination).
     * @param int $limit The maximum number of results to return (used for pagination).
     * @param string $filters JSON-encoded string containing the filters to apply.
     * @param bool $searchOn Flag indicating whether to apply the filters.
     * @param string $grouping The column(s) by which to group the results.
     *
     * @return string JSON-encoded string containing the count of results and the data.
     */
    public function queryUsuarioBonoResumensCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$grouping)
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
                    $where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
                } else {
                    $where = "";
                }
            }

        }

        if($grouping != ""){
            $where = $where . " GROUP BY " . $grouping;
        }
        $sql = 'SELECT count(*) count FROM usuario_bono_resumen  ' . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT ' .$select .'  FROM usuario_bono_resumen  ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;



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
     * @return UsuarioBonoResumenMySql
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
