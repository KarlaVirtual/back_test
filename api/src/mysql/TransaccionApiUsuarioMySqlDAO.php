<?php namespace Backend\mysql;

/**
 * Class that operate on table 'transaccion_api_usuario'. Database Mysql.
 *
 * @author: http://phpdao.com
 * @date: 2017-09-15 04:48
 * @category No
 * @package No
 * @version 1.0
 */

use Backend\dao\TransaccionApiUsuarioDAO;
use Backend\dto\TransaccionApiUsuario;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;

class TransaccionApiUsuarioMySqlDAO implements TransaccionApiUsuarioDAO
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
	 * @return TransaccionApiUsuarioMySql 
	 */
	public function load($id)
	{
		$sql = 'SELECT * FROM transaccion_api_usuario WHERE transapiusuario_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($id);
		return $this->getRow($sqlQuery);
	}

	/**
	 * Get all records from table
	 */
	public function queryAll()
	{
		$sql = 'SELECT * FROM transaccion_api_usuario';
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
		$sql = 'SELECT * FROM transaccion_api_usuario ORDER BY ' . $orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}

	/**
	 * Executes a custom query to retrieve transactions based on various filters and sorting options.
	 *
	 * @param string $select The columns to select in the query.
	 * @param string $sidx The column by which to sort the results.
	 * @param string $sord The sorting order (ASC or DESC).
	 * @param int $start The starting index for the results.
	 * @param int $limit The maximum number of results to return.
	 * @param string $filters JSON encoded string containing the filters to apply.
	 * @param bool $searchOn Flag indicating whether to apply the filters.
	 * @param string $grouping The column(s) by which to group the results.
	 *
	 * @return string JSON encoded string containing the count of results and the data.
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


        $sql = "SELECT count(*) count FROM transaccion_api_usuario  " . $where;

        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
        $sql = "SELECT ".$select." FROM transaccion_api_usuario  " . $where ." " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : '.json_encode($count). ', "data" : '.json_encode($result).'}';

        return  $json;
    }



    /**
	 * Delete record from table
	 * @param transaccionApi primary key
	 */
	public function delete($transapiusuario_id)
	{
		$sql = 'DELETE FROM transaccion_api_usuario WHERE transapiusuario_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($transapiusuario_id);
		return $this->executeUpdate($sqlQuery);
	}

	/**
	 * Insert record to table
	 *
	 * @param TransaccionApiUsuarioMySql transaccionApi
	 */
	public function insert($transaccionApi)
	{
		$sql = 'INSERT INTO transaccion_api_usuario (usuariogenera_id,usuario_id, tipo, transaccion_id, t_value,valor,identificador,respuesta_codigo,respuesta, usucrea_id, usumodif_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);

		$sqlQuery->setNumber($transaccionApi->usuariogeneraId);
		$sqlQuery->setNumber($transaccionApi->usuarioId);
		$sqlQuery->set($transaccionApi->tipo);
		$sqlQuery->setString($transaccionApi->transaccionId);
        $sqlQuery->set($transaccionApi->tValue);
        $sqlQuery->set($transaccionApi->valor);
		$sqlQuery->set($transaccionApi->identificador);
		$sqlQuery->set($transaccionApi->respuestaCodigo);
		$sqlQuery->set($transaccionApi->respuesta);
		$sqlQuery->setNumber($transaccionApi->usucreaId);
        $sqlQuery->setNumber($transaccionApi->usumodifId);

		$id = $this->executeInsert($sqlQuery);
		$transaccionApi->transapiusuarioId = $id;
		return $id;
	}

	/**
	 * Update record in table
	 *
	 * @param TransaccionApiUsuarioMySql transaccionApi
	 */
	public function update($transaccionApi)
	{
		$sql = 'UPDATE transaccion_api_usuario SET usuariogenera_id = ?,usuario_id=?, tipo = ?, transaccion_id = ?, t_value = ?,valor=?, identificador=?, respuesta_codigo = ?, respuesta = ?, usucrea_id = ?, usumodif_id = ? WHERE transapiusuario_id = ?';
		$sqlQuery = new SqlQuery($sql);

		$sqlQuery->setNumber($transaccionApi->usuariogeneraId);
		$sqlQuery->setNumber($transaccionApi->usuarioId);
		$sqlQuery->set($transaccionApi->tipo);
		$sqlQuery->setString($transaccionApi->transaccionId);
        $sqlQuery->set($transaccionApi->tValue);
        $sqlQuery->set($transaccionApi->valor);
		$sqlQuery->set($transaccionApi->identificador);
		$sqlQuery->set($transaccionApi->respuestaCodigo);
		$sqlQuery->set($transaccionApi->respuesta);
		$sqlQuery->setNumber($transaccionApi->usucreaId);
		$sqlQuery->setNumber($transaccionApi->usumodifId);

		$sqlQuery->setNumber($transaccionApi->transapiusuarioId);
		return $this->executeUpdate($sqlQuery);
	}

	/**
	 * Delete all rows
	 */
	public function clean()
	{
		$sql = 'DELETE FROM transaccion_api_usuario';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}

/**
	 * Query records by `usuariogenera_id`.
	 *
	 * @param int $value The `usuariogenera_id` value to query.
	 * @return array The list of matching records.
	 */
	public function queryByProveedorId($value)
	{
		$sql = 'SELECT * FROM transaccion_api_usuario WHERE usuariogenera_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

	/**
	 * Query records by `producto_id`.
	 *
	 * @param int $value The `producto_id` value to query.
	 * @return array The list of matching records.
	 */
	public function queryByProductoId($value)
	{
		$sql = 'SELECT * FROM transaccion_api_usuario WHERE producto_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

	/**
	 * Query records by `usuario_id`.
	 *
	 * @param int $value The `usuario_id` value to query.
	 * @return array The list of matching records.
	 */
	public function queryByUsuarioId($value)
	{
		$sql = 'SELECT * FROM transaccion_api_usuario WHERE usuario_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

	/**
	 * Query records by `tipo`.
	 *
	 * @param string $value The `tipo` value to query.
	 * @return array The list of matching records.
	 */
	public function queryByTipo($value)
	{
		$sql = 'SELECT * FROM transaccion_api_usuario WHERE tipo = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
	 * Query records by `transaccion_id`.
	 *
	 * @param string $value The `transaccion_id` value to query.
	 * @return array The list of matching records.
	 */
	public function queryByTransaccionId($value)
	{
		$sql = 'SELECT * FROM transaccion_api_usuario WHERE transaccion_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setString($value);
		return $this->getList($sqlQuery);
	}

	/**
	 * Query records by `transaccion_id`, `usuariogenera_id`, and `respuesta_codigo`.
	 *
	 * @param string $value The `transaccion_id` value to query.
	 * @param int $usuariogeneraId The `usuariogenera_id` value to query.
	 * @param string $respuestaCodigo The `respuesta_codigo` value to query.
	 * @return array The list of matching records.
	 */
	public function queryByTransaccionIdAndProveedor($value, $usuariogeneraId, $respuestaCodigo)
	{
		$sql = 'SELECT * FROM transaccion_api_usuario WHERE transaccion_id = ? AND usuariogenera_id = ? AND respuesta_codigo = ? ';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setString($value);
		$sqlQuery->setNumber($usuariogeneraId);
		$sqlQuery->setString($respuestaCodigo);

		return $this->getList($sqlQuery);
	}

	/**
	 * Query records by `transaccion_id`, `usuariogenera_id`, and `tipo`.
	 *
	 * @param string $transaccionId The `transaccion_id` value to query.
	 * @param int $usuariogeneraId The `usuariogenera_id` value to query.
	 * @param string $tipo The `tipo` value to query.
	 * @return array The list of matching records.
	 */
	public function queryByTransaccionIdAndUsuariogeneraIdAndTipo($transaccionId="", $usuariogeneraId = "", $tipo="")
	{
		$sql = 'SELECT * FROM transaccion_api_usuario WHERE transaccion_id = ? AND usuariogenera_id = ? AND tipo = ? ';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setString($transaccionId);
		$sqlQuery->setNumber($usuariogeneraId);
		$sqlQuery->setString($tipo);

		return $this->getList($sqlQuery);
	}

	/**
	 * Query records by `t_value`.
	 *
	 * @param string $value The `t_value` value to query.
	 * @return array The list of matching records.
	 */
	public function queryByTValue($value)
	{
		$sql = 'SELECT * FROM transaccion_api_usuario WHERE t_value = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
	 * Query records by `identificador`.
	 *
	 * @param string $value The `identificador` value to query.
	 * @return array The list of matching records.
	 */
	public function queryByIdentificador($value)
	{
		$sql = 'SELECT * FROM transaccion_api_usuario WHERE identificador = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
	 * Query records by `fecha_crea`.
	 *
	 * @param string $value The `fecha_crea` value to query.
	 * @return array The list of matching records.
	 */
	public function queryByFechaCrea($value)
	{
		$sql = 'SELECT * FROM transaccion_api_usuario WHERE fecha_crea = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
	 * Query records by `usucrea_id`.
	 *
	 * @param int $value The `usucrea_id` value to query.
	 * @return array The list of matching records.
	 */
	public function queryByUsucreaId($value)
	{
		$sql = 'SELECT * FROM transaccion_api_usuario WHERE usucrea_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

	/**
	 * Query records by `fecha_modif`.
	 *
	 * @param string $value The `fecha_modif` value to query.
	 * @return array The list of matching records.
	 */
	public function queryByFechaModif($value)
	{
		$sql = 'SELECT * FROM transaccion_api_usuario WHERE fecha_modif = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
	 * Query records by `usumodif_id`.
	 *
	 * @param int $value The `usumodif_id` value to query.
	 * @return array The list of matching records.
	 */
	public function queryByUsumodifId($value)
	{
		$sql = 'SELECT * FROM transaccion_api_usuario WHERE usumodif_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

	/**
	 * Query records by `transapi_id`.
	 *
	 * @param int $value The `transapi_id` value to query.
	 * @return array The list of matching records.
	 */
	public function queryByTransapiId($value)
	{
		$sql = 'SELECT * FROM transaccion_api_usuario WHERE transapi_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

	/**
	 * Delete records by `usuariogenera_id`.
	 *
	 * @param int $value The `usuariogenera_id` value to delete.
	 * @return int The number of affected rows.
	 */
	public function deleteByProveedorId($value)
	{
		$sql = 'DELETE FROM transaccion_api_usuario WHERE usuariogenera_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
	 * Delete records by `producto_id`.
	 *
	 * @param int $value The `producto_id` value to delete.
	 * @return int The number of affected rows.
	 */
	public function deleteByProductoId($value)
	{
		$sql = 'DELETE FROM transaccion_api_usuario WHERE producto_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
	 * Delete records by `usuario_id`.
	 *
	 * @param int $value The `usuario_id` value to delete.
	 * @return int The number of affected rows.
	 */
	public function deleteByUsuarioId($value)
	{
		$sql = 'DELETE FROM transaccion_api_usuario WHERE usuario_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
	 * Delete records by `tipo`.
	 *
	 * @param string $value The `tipo` value to delete.
	 * @return int The number of affected rows.
	 */
	public function deleteByTipo($value)
	{
		$sql = 'DELETE FROM transaccion_api_usuario WHERE tipo = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
	 * Delete records by `transaccion_id`.
	 *
	 * @param int $value The `transaccion_id` value to delete.
	 * @return int The number of affected rows.
	 */
	public function deleteByTransaccionId($value)
	{
		$sql = 'DELETE FROM transaccion_api_usuario WHERE transaccion_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
	 * Delete records by `t_value`.
	 *
	 * @param string $value The `t_value` value to delete.
	 * @return int The number of affected rows.
	 */
	public function deleteByTValue($value)
	{
		$sql = 'DELETE FROM transaccion_api_usuario WHERE t_value = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
	 * Delete records by `fecha_crea`.
	 *
	 * @param string $value The `fecha_crea` value to delete.
	 * @return int The number of affected rows.
	 */
	public function deleteByFechaCrea($value)
	{
		$sql = 'DELETE FROM transaccion_api_usuario WHERE fecha_crea = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
	 * Delete records by `usucrea_id`.
	 *
	 * @param int $value The `usucrea_id` value to delete.
	 * @return int The number of affected rows.
	 */
	public function deleteByUsucreaId($value)
	{
		$sql = 'DELETE FROM transaccion_api_usuario WHERE usucrea_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
	 * Delete records by `fecha_modif`.
	 *
	 * @param string $value The `fecha_modif` value to delete.
	 * @return int The number of affected rows.
	 */
	public function deleteByFechaModif($value)
	{
		$sql = 'DELETE FROM transaccion_api_usuario WHERE fecha_modif = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
	 * Delete records by `usumodif_id`.
	 *
	 * @param int $value The `usumodif_id` value to delete.
	 * @return int The number of affected rows.
	 */
	public function deleteByUsumodifId($value)
	{
		$sql = 'DELETE FROM transaccion_api_usuario WHERE usumodif_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}



	/**
	 * Read row
	 *
	 * @return TransaccionApiUsuarioMySql 
	 */
	protected function readRow($row)
	{
		$transaccionApi = new TransaccionApiUsuario();

		$transaccionApi->transapiusuarioId = $row['transapiusuario_id'];
		$transaccionApi->usuariogeneraId = $row['usuariogenera_id'];
		$transaccionApi->usuarioId = $row['usuario_id'];
		$transaccionApi->tipo = $row['tipo'];
		$transaccionApi->transaccionId = $row['transaccion_id'];
        $transaccionApi->tValue = $row['t_value'];
        $transaccionApi->valor = $row['valor'];
		$transaccionApi->identificador = $row['identificador'];
		$transaccionApi->respuesta = $row['respuesta'];
		$transaccionApi->respuestaCodigo = $row['respuesta_codigo'];
		$transaccionApi->fechaCrea = $row['fecha_crea'];
		$transaccionApi->usucreaId = $row['usucrea_id'];
		$transaccionApi->fechaModif = $row['fecha_modif'];
        $transaccionApi->usumodifId = $row['usumodif_id'];

		return $transaccionApi;
	}

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
	 * @return TransaccionApiUsuarioMySql 
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
     * Execute2 sql query
     */
    protected function execute2($sqlQuery)
    {
        return QueryExecutor::execute2($this->transaction, $sqlQuery);
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