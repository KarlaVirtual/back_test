<?php namespace Backend\mysql;

/**
 * Class that operate on table 'transapiusuario_log'. Database Mysql.
 *
 * @author: http://phpdao.com
 * @since: 2017-09-15 04:48
 * @category No
 * @package No
 * @version     1.0
 */

use Backend\dao\TransapiusuarioLogDAO;
use Backend\dto\TransapiusuarioLog;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;

class TransapiusuarioLogMySqlDAO implements TransapiusuarioLogDAO
{


	/** Objeto vincula una conexión de la base de datos con el objeto correspondiente
     * @var Transaction $transaction
     */
	private $transaction;

	
    /**
	 * Get the current transaction.
	 *
	 * @return Transaction The current transaction.
	 */
	public function getTransaction()
	{
		return $this->transaction;
	}

	/**
	 * Set the current transaction.
	 *
	 * @param Transaction $transaction The transaction to set.
	 */
	public function setTransaction($transaction)
	{
		$this->transaction = $transaction;
	}

	/**
	 * TransaccionProductoMySqlDAO constructor.
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
	 * @return TransapiusuarioLogMySql 
	 */
	public function load($id)
	{
		$sql = 'SELECT * FROM transapiusuario_log WHERE transapiusuariolog_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($id);
		return $this->getRow($sqlQuery);
	}

	/**
	 * Get all records from table
	 */
	public function queryAll()
	{
		$sql = 'SELECT * FROM transapiusuario_log';
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
		$sql = 'SELECT * FROM transapiusuario_log ORDER BY ' . $orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}

	/**
	 * Delete record from table
	 * @param transjuegoLog primary key
	 */
	public function delete($transapiusuariolog_id)
	{
		$sql = 'DELETE FROM transapiusuario_log WHERE transapiusuariolog_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($transapiusuariolog_id);
		return $this->executeUpdate($sqlQuery);
	}


	/**
	 * Executes a custom query to retrieve transaction logs based on various filters and sorting options.
	 *
	 * @param string $select The columns to select in the query.
	 * @param string $sidx The column to sort by.
	 * @param string $sord The sort order (ASC or DESC).
	 * @param int $start The starting index for the result set.
	 * @param int $limit The maximum number of records to return.
	 * @param string $filters JSON encoded string containing the filter rules.
	 * @param bool $searchOn Flag indicating whether to apply filters.
	 * @param string $grouping The column(s) to group by.
	 *
	 * @return string JSON encoded string containing the count of records and the data.
	 */
    public function queryTransaccionesCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping)
    {


        $where = " where 1=1 ";


        if($searchOn) {
            // Construye el where
            $filters = json_decode($filters);
            $whereArray = array();
            $rules = $filters->rules;
            $groupOperation = $filters->groupOp;
            $cont = 0;

            foreach($rules as $rule)
            {
                $fieldName = $rule->field;
                $fieldData = $rule->data;
                switch ($rule->op) {
                    case "eq":
                        $fieldOperation = " = '".$fieldData."'";
                        break;
                    case "ne":
                        $fieldOperation = " != '".$fieldData."'";
                        break;
                    case "lt":
                        $fieldOperation = " < '".$fieldData."'";
                        break;
                    case "gt":
                        $fieldOperation = " > '".$fieldData."'";
                        break;
                    case "le":
                        $fieldOperation = " <= '".$fieldData."'";
                        break;
                    case "ge":
                        $fieldOperation = " >= '".$fieldData."'";
                        break;
                    case "nu":
                        $fieldOperation = " = ''";
                        break;
                    case "nn":
                        $fieldOperation = " != ''";
                        break;
                    case "in":
                        $fieldOperation = " IN (".$fieldData.")";
                        break;
                    case "ni":
                        $fieldOperation = " NOT IN '".$fieldData."'";
                        break;
                    case "bw":
                        $fieldOperation = " LIKE '".$fieldData."%'";
                        break;
                    case "bn":
                        $fieldOperation = " NOT LIKE '".$fieldData."%'";
                        break;
                    case "ew":
                        $fieldOperation = " LIKE '%".$fieldData."'";
                        break;
                    case "en":
                        $fieldOperation = " NOT LIKE '%".$fieldData."'";
                        break;
                    case "cn":
                        $fieldOperation = " LIKE '%".$fieldData."%'";
                        break;
                    case "nc":
                        $fieldOperation = " NOT LIKE '%".$fieldData."%'";
                        break;
                    default:
                        $fieldOperation = "";
                        break;
                }
                if($fieldOperation != "") $whereArray[] = $fieldName.$fieldOperation;
                if (oldCount($whereArray)>0)
                {
                    $where = $where . " " . $groupOperation . " UCASE(CAST(" . $fieldName . " AS CHAR)) " . strtoupper($fieldOperation);
                }
                else
                {
                    $where = "";
                }
            }

        }


        if($grouping != ""){
            $where = $where . " GROUP BY " . $grouping;
        }


        $sql = "SELECT count(*) count FROM transapiusuario_log  " . $where;

        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
        $sql = "SELECT ".$select." FROM transapiusuario_log " . $where ." " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : '.json_encode($count). ', "data" : '.json_encode($result).'}';

        return  $json;
    }


    /**
	 * Insert record to table
	 *
	 * @param TransapiusuarioLogMySql transjuegoLog
	 */
	public function insert($transjuegoLog)
	{
		$sql = 'INSERT INTO transapiusuario_log ( tipo, transaccion_id, t_value,valor, usucrea_id, usumodif_id, usuario_id, usuariogenera_id,identificador) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);

		$sqlQuery->set($transjuegoLog->tipo);
		$sqlQuery->setString($transjuegoLog->transaccionId);
        $sqlQuery->set($transjuegoLog->tValue);
        $sqlQuery->set($transjuegoLog->valor);
		$sqlQuery->setNumber($transjuegoLog->usucreaId);
        $sqlQuery->setNumber($transjuegoLog->usumodifId);
        $sqlQuery->setNumber($transjuegoLog->usuarioId);
        $sqlQuery->setNumber($transjuegoLog->usuariogeneraId);
        $sqlQuery->set($transjuegoLog->identificador);

		$id = $this->executeInsert($sqlQuery);
		$transjuegoLog->transapiusuariologId = $id;
		return $id;
	}

	/**
	 * Update record in table
	 *
	 * @param TransapiusuarioLogMySql transjuegoLog
	 */
	public function update($transjuegoLog)
	{
		$sql = 'UPDATE transapiusuario_log SET  tipo = ?, transaccion_id = ?, t_value = ?, valor = ?,usucrea_id = ?, usumodif_id = ?, usuario_id = ?, usuariogenera_id = ?, identificador = ? WHERE transapiusuariolog_id = ?';
		$sqlQuery = new SqlQuery($sql);

		$sqlQuery->setNumber($transjuegoLog->transjuegoId);
		$sqlQuery->set($transjuegoLog->tipo);
		$sqlQuery->setString($transjuegoLog->transaccionId);
        $sqlQuery->set($transjuegoLog->tValue);
        $sqlQuery->set($transjuegoLog->valor);
		$sqlQuery->setNumber($transjuegoLog->usucreaId);
		$sqlQuery->setNumber($transjuegoLog->usumodifId);
        $sqlQuery->setNumber($transjuegoLog->usuarioId);
        $sqlQuery->setNumber($transjuegoLog->usuariogeneraId);
        $sqlQuery->set($transjuegoLog->identificador);

		$sqlQuery->setNumber($transjuegoLog->transapiusuariologId);
		return $this->executeUpdate($sqlQuery);
	}

	/**
	 * Delete all rows
	 */
	public function clean()
	{
		$sql = 'DELETE FROM transapiusuario_log';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}

    /**
	 * Retrieve records from the table based on the transjuego_id.
	 *
	 * @param int $value The transjuego_id to filter by.
	 * @return array The list of records matching the transjuego_id.
	 */
	public function queryByTransjuegoId($value)
	{
		$sql = 'SELECT * FROM transapiusuario_log WHERE transjuego_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

	/**
	 * Retrieve records from the table based on the identificador and tipo.
	 *
	 * @param int $value The identificador to filter by.
	 * @param string $tipo The tipo to filter by.
	 * @return array The list of records matching the identificador and tipo.
	 */
	public function queryByIdentificadorAndTipo($value, $tipo)
	{
		$sql = 'SELECT * FROM transapiusuario_log WHERE identificador = ? AND tipo=?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		$sqlQuery->set($tipo);
		return $this->getList($sqlQuery);
	}

	/**
	 * Retrieve records from the table based on the transjuego_id and transaccion_id.
	 *
	 * @param int $value The transjuego_id to filter by.
	 * @param string $transaccionId The transaccion_id to filter by.
	 * @return array The list of records matching the transjuego_id and transaccion_id.
	 */
	public function queryByTransjuegoIdAndTransaccionId($value, $transaccionId)
	{
		$sql = 'SELECT * FROM transapiusuario_log WHERE transjuego_id = ? AND transaccion_id=?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		$sqlQuery->set($transaccionId);
		return $this->getList($sqlQuery);
	}

	/**
	 * Retrieve records from the table based on the tipo.
	 *
	 * @param string $value The tipo to filter by.
	 * @return array The list of records matching the tipo.
	 */
	public function queryByTipo($value)
	{
		$sql = 'SELECT * FROM transapiusuario_log WHERE tipo = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
	 * Retrieve records from the table based on the transaccion_id.
	 *
	 * @param string $value The transaccion_id to filter by.
	 * @return array The list of records matching the transaccion_id.
	 */
	public function queryByTransaccionId($value)
	{
		$sql = 'SELECT * FROM transapiusuario_log WHERE transaccion_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setString($value);
		return $this->getList($sqlQuery);
	}

	/**
	 * Retrieve records from the table based on the t_value.
	 *
	 * @param string $value The t_value to filter by.
	 * @return array The list of records matching the t_value.
	 */
	public function queryByTValue($value)
	{
		$sql = 'SELECT * FROM transapiusuario_log WHERE t_value = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
	 * Retrieve records from the table based on the fecha_crea.
	 *
	 * @param string $value The fecha_crea to filter by.
	 * @return array The list of records matching the fecha_crea.
	 */
	public function queryByFechaCrea($value)
	{
		$sql = 'SELECT * FROM transapiusuario_log WHERE fecha_crea = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
	 * Retrieve records from the table based on the usucrea_id.
	 *
	 * @param int $value The usucrea_id to filter by.
	 * @return array The list of records matching the usucrea_id.
	 */
	public function queryByUsucreaId($value)
	{
		$sql = 'SELECT * FROM transapiusuario_log WHERE usucrea_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

	/**
	 * Retrieve records from the table based on the fecha_modif.
	 *
	 * @param string $value The fecha_modif to filter by.
	 * @return array The list of records matching the fecha_modif.
	 */
	public function queryByFechaModif($value)
	{
		$sql = 'SELECT * FROM transapiusuario_log WHERE fecha_modif = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
	 * Retrieve records from the table based on the usumodif_id.
	 *
	 * @param int $value The usumodif_id to filter by.
	 * @return array The list of records matching the usumodif_id.
	 */
	public function queryByUsumodifId($value)
	{
		$sql = 'SELECT * FROM transapiusuario_log WHERE usumodif_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

	/**
	 * Delete records from the table based on the transjuego_id.
	 *
	 * @param int $value The transjuego_id to filter by.
	 * @return int The number of rows affected.
	 */
	public function deleteByTransjuegoId($value)
	{
		$sql = 'DELETE FROM transapiusuario_log WHERE transjuego_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
	 * Delete records from the table based on the tipo.
	 *
	 * @param string $value The tipo to filter by.
	 * @return int The number of rows affected.
	 */
	public function deleteByTipo($value)
	{
		$sql = 'DELETE FROM transapiusuario_log WHERE tipo = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
	 * Delete records from the table based on the transaccion_id.
	 *
	 * @param int $value The transaccion_id to filter by.
	 * @return int The number of rows affected.
	 */
	public function deleteByTransaccionId($value)
	{
		$sql = 'DELETE FROM transapiusuario_log WHERE transaccion_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
	 * Delete records from the table based on the t_value.
	 *
	 * @param string $value The t_value to filter by.
	 * @return int The number of rows affected.
	 */
	public function deleteByTValue($value)
	{
		$sql = 'DELETE FROM transapiusuario_log WHERE t_value = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
	 * Delete records from the table based on the fecha_crea.
	 *
	 * @param string $value The fecha_crea to filter by.
	 * @return int The number of rows affected.
	 */
	public function deleteByFechaCrea($value)
	{
		$sql = 'DELETE FROM transapiusuario_log WHERE fecha_crea = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
	 * Delete records from the table based on the usucrea_id.
	 *
	 * @param int $value The usucrea_id to filter by.
	 * @return int The number of rows affected.
	 */
	public function deleteByUsucreaId($value)
	{
		$sql = 'DELETE FROM transapiusuario_log WHERE usucrea_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
	 * Delete records from the table based on the fecha_modif.
	 *
	 * @param string $value The fecha_modif to filter by.
	 * @return int The number of rows affected.
	 */
	public function deleteByFechaModif($value)
	{
		$sql = 'DELETE FROM transapiusuario_log WHERE fecha_modif = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
	 * Delete records from the table based on the usumodif_id.
	 *
	 * @param int $value The usumodif_id to filter by.
	 * @return int The number of rows affected.
	 */
	public function deleteByUsumodifId($value)
	{
		$sql = 'DELETE FROM transapiusuario_log WHERE usumodif_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}



	/**
	 * Read row
	 *
	 * @return TransapiusuarioLogMySql 
	 */
	protected function readRow($row)
	{
		$transjuegoLog = new TransapiusuarioLog();

		$transjuegoLog->transapiusuariologId = $row['transapiusuariolog_id'];
		$transjuegoLog->tipo = $row['tipo'];
		$transjuegoLog->transaccionId = $row['transaccion_id'];
        $transjuegoLog->tValue = $row['t_value'];
        $transjuegoLog->valor = $row['valor'];
		$transjuegoLog->fechaCrea = $row['fecha_crea'];
		$transjuegoLog->usucreaId = $row['usucrea_id'];
		$transjuegoLog->fechaModif = $row['fecha_modif'];
        $transjuegoLog->usumodifId = $row['usumodif_id'];
        $transjuegoLog->usuarioId = $row['usuario_id'];
        $transjuegoLog->usuariogeneraId = $row['usuariogenera_id'];
        $transjuegoLog->identificador = $row['identificador'];

		return $transjuegoLog;
	}

	/**
	 * Retrieves a list of rows from the database based on the provided SQL query.
	 *
	 * @param SqlQuery $sqlQuery The SQL query to execute.
	 * @return array An array of objects representing the rows retrieved from the database.
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
	 * @return TransapiusuarioLogMySql 
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
	 * Execute sql query
	 */
	protected function executeUpdate($sqlQuery)
	{
		return QueryExecutor::executeUpdate($this->transaction, $sqlQuery);
	}


    /**
     * Execute2 sql query
     */
    protected function execute2($sqlQuery)
    {
        return QueryExecutor::execute2($this->transaction, $sqlQuery);
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