<?php namespace Backend\mysql;
/**
 * Class that operate on table 'usuario_token_interno'. Database Mysql.
 *
 * @author: http://phpdao.com
 * @date: 2018-02-11 17:49
 * @package No
 * @category No
 * @version    1.0
 */

use Backend\dao\UsuarioTokenInternoDAO;
use Backend\dto\Helpers;
use Backend\dto\UsuarioTokenInterno;
use Backend\sql\QueryExecutor;
use Backend\sql\SqlQuery;
use Backend\sql\Transaction;
class UsuarioTokenInternoMySqlDAO implements UsuarioTokenInternoDAO{

    /**
     * Objeto Transacción correspondiente a la conexión entre la tabla y el objeto MySqlDAO
     * @var Transaction
     */
    private $transaction;

	/**
     * Get the current transaction object.
     *
     * @return Transaction The current transaction object.
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * Set the transaction object.
     *
     * @param Transaction $transaction The transaction object to set.
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
	 * @return UsuarioTokenInternoMySql 
	 */
	public function load($id){
		$sql = 'SELECT * FROM usuario_token_interno WHERE usutokeninterno_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($id);
		return $this->getRow($sqlQuery);
	}

	/**
	 * Get all records from table
	 */
	public function queryAll(){
		$sql = 'SELECT * FROM usuario_token_interno';
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
	/**
	 * Get all records from table ordered by field
	 *
	 * @param $orderColumn column name
	 */
	public function queryAllOrderBy($orderColumn){
		$sql = 'SELECT * FROM usuario_token_interno ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
	/**
 	 * Delete record from table
 	 * @param UsuarioTokenInterno primary key
 	 */
	public function delete($usutokeninterno_id){
		$sql = 'DELETE FROM usuario_token_interno WHERE usutokeninterno_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($usutokeninterno_id);
		return $this->executeUpdate($sqlQuery);
	}
	
	/**
 	 * Insert record to table
 	 *
 	 * @param UsuarioTokenInternoMySql UsuarioTokenInterno
 	 */
	public function insert($UsuarioTokenInterno){
		$sql = 'INSERT INTO usuario_token_interno (usuario_id,usuarioaprobar_id,usuariosolicita_id,usuariosolicita_ip,usuario_ip,usuarioaprobar_ip, tipo,estado, valor, usucrea_id, usumodif_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($UsuarioTokenInterno->usuarioId);
        $sqlQuery->setNumber($UsuarioTokenInterno->usuarioaprobarId);
        $sqlQuery->setNumber($UsuarioTokenInterno->usuariosolicitaId);
        $sqlQuery->set($UsuarioTokenInterno->usuariosolicitaIp);

        $sqlQuery->set($UsuarioTokenInterno->usuarioIp);
        $sqlQuery->set($UsuarioTokenInterno->usuarioaprobarIp);
        $sqlQuery->set($UsuarioTokenInterno->tipo);
        $sqlQuery->set($UsuarioTokenInterno->estado);
        $sqlQuery->set($UsuarioTokenInterno->valor);
		$sqlQuery->setNumber($UsuarioTokenInterno->usucreaId);
        $sqlQuery->setNumber($UsuarioTokenInterno->usumodifId);

		$id = $this->executeInsert($sqlQuery);	
		$UsuarioTokenInterno->usutokeninternoId = $id;
		return $id;
	}
	
	/**
 	 * Update record in table
 	 *
 	 * @param UsuarioTokenInternoMySql UsuarioTokenInterno
 	 */
	public function update($UsuarioTokenInterno){
		$sql = 'UPDATE usuario_token_interno SET usuario_id = ?, usuarioaprobar_id = ?, usuariosolicita_id = ?, usuariosolicita_ip = ?,usuario_ip = ?, usuarioaprobar_ip = ?, tipo = ?, estado = ?, valor = ?, usucrea_id = ?, usumodif_id = ? WHERE usutokeninterno_id = ?';
		$sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($UsuarioTokenInterno->usuarioId);
        $sqlQuery->setNumber($UsuarioTokenInterno->usuarioaprobarId);
        $sqlQuery->setNumber($UsuarioTokenInterno->usuariosolicitaId);
        $sqlQuery->set($UsuarioTokenInterno->usuariosolicitaIp);
        $sqlQuery->set($UsuarioTokenInterno->usuarioIp);
        $sqlQuery->set($UsuarioTokenInterno->usuarioaprobarIp);
        $sqlQuery->set($UsuarioTokenInterno->tipo);
        $sqlQuery->set($UsuarioTokenInterno->estado);
        $sqlQuery->set($UsuarioTokenInterno->valor);

		$sqlQuery->setNumber($UsuarioTokenInterno->usucreaId);
		$sqlQuery->setNumber($UsuarioTokenInterno->usumodifId);


		$sqlQuery->setNumber($UsuarioTokenInterno->usutokeninternoId);
		return $this->executeUpdate($sqlQuery);
	}


	/**
	 * Executes a custom query to retrieve and filter user tokens from the database.
	 *
	 * @param string $select The columns to select in the query.
	 * @param string $sidx The column to sort by.
	 * @param string $sord The sort order (ASC or DESC).
	 * @param int $start The starting index for the query limit.
	 * @param int $limit The maximum number of records to return.
	 * @param string $filters JSON encoded string containing the filter rules.
	 * @param bool $searchOn Flag indicating whether to apply filters.
	 *
	 * @return string JSON encoded string containing the count of records and the filtered data.
	 */
    public function queryUsuarioTokenInternosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
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
                $fieldName = $Helpers->set_custom_field($rule->field);
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


        $sql = 'SELECT count(*) count FROM usuario_token_interno LEFT OUTER JOIN usuario ON (usuario.usuario_id=usuario_token_interno.usuario_id)  ' . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT ' .$select .'  FROM usuario_token_interno LEFT OUTER JOIN usuario ON (usuario.usuario_id=usuario_token_interno.usuario_id)  ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;


        $sqlQuery = new SqlQuery($sql);

        $result = $Helpers->process_data($this->execute2($sqlQuery));

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }


    /**
 	 * Delete all rows
 	 */
	public function clean(){
		$sql = 'DELETE FROM usuario_token_interno';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}

/**
	 * Query records by usuario_id.
	 *
	 * @param mixed $value The value to query by.
	 * @return array The list of matching records.
	 */
	public function queryByUsuarioId($value){
		$sql = 'SELECT * FROM usuario_token_interno WHERE usuario_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
	 * Query records by usucrea_id.
	 *
	 * @param int $value The value to query by.
	 * @return array The list of matching records.
	 */
	public function queryByUsucreaId($value){
		$sql = 'SELECT * FROM usuario_token_interno WHERE usucrea_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

	/**
	 * Query records by usumodif_id.
	 *
	 * @param int $value The value to query by.
	 * @return array The list of matching records.
	 */
	public function queryByUsumodifId($value){
		$sql = 'SELECT * FROM usuario_token_interno WHERE usumodif_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

	/**
	 * Query records by fecha_crea.
	 *
	 * @param mixed $value The value to query by.
	 * @return array The list of matching records.
	 */
	public function queryByFechaCrea($value){
		$sql = 'SELECT * FROM usuario_token_interno WHERE fecha_crea = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
	 * Query records by fecha_modif.
	 *
	 * @param mixed $value The value to query by.
	 * @return array The list of matching records.
	 */
	public function queryByFechaModif($value){
		$sql = 'SELECT * FROM usuario_token_interno WHERE fecha_modif = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
	 * Delete records by usuarioId.
	 *
	 * @param mixed $value The value to delete by.
	 * @return int The number of affected rows.
	 */
	public function deleteByNombre($value){
		$sql = 'DELETE FROM usuario_token_interno WHERE usuarioId = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
	 * Delete records by usucrea_id.
	 *
	 * @param int $value The value to delete by.
	 * @return int The number of affected rows.
	 */
	public function deleteByUsucreaId($value){
		$sql = 'DELETE FROM usuario_token_interno WHERE usucrea_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
	 * Delete records by usumodif_id.
	 *
	 * @param int $value The value to delete by.
	 * @return int The number of affected rows.
	 */
	public function deleteByUsumodifId($value){
		$sql = 'DELETE FROM usuario_token_interno WHERE usumodif_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
	 * Delete records by fecha_crea.
	 *
	 * @param mixed $value The value to delete by.
	 * @return int The number of affected rows.
	 */
	public function deleteByFechaCrea($value){
		$sql = 'DELETE FROM usuario_token_interno WHERE fecha_crea = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
	 * Delete records by fecha_modif.
	 *
	 * @param mixed $value The value to delete by.
	 * @return int The number of affected rows.
	 */
	public function deleteByFechaModif($value){
		$sql = 'DELETE FROM usuario_token_interno WHERE fecha_modif = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}


	
	/**
	 * Read row
	 *
	 * @return UsuarioTokenInternoMySql 
	 */
	protected function readRow($row){
		$UsuarioTokenInterno = new UsuarioTokenInterno();
		
		$UsuarioTokenInterno->usutokeninternoId = $row['usutokeninterno_id'];
        $UsuarioTokenInterno->usuarioId = $row['usuario_id'];
        $UsuarioTokenInterno->usuarioaprobarId = $row['usuarioaprobar_id'];
        $UsuarioTokenInterno->usuariosolicitaId = $row['usuariosolicita_id'];
        $UsuarioTokenInterno->usuariosolicitaIp = $row['usuariosolicita_ip'];

        $UsuarioTokenInterno->usuarioIp = $row['usuario_ip'];
        $UsuarioTokenInterno->usuarioaprobarIp = $row['usuarioaprobar_ip'];
        $UsuarioTokenInterno->tipo = $row['tipo'];
        $UsuarioTokenInterno->estado = $row['estado'];
        $UsuarioTokenInterno->valor = $row['valor'];
		$UsuarioTokenInterno->usucreaId = $row['usucrea_id'];
		$UsuarioTokenInterno->usumodifId = $row['usumodif_id'];
		$UsuarioTokenInterno->fechaCrea = $row['fecha_crea'];
		$UsuarioTokenInterno->fechaModif = $row['fecha_modif'];


		return $UsuarioTokenInterno;
	}
	
	/**
	 * Retrieves a list of rows from the database based on the provided SQL query.
	 *
	 * @param SqlQuery $sqlQuery The SQL query to be executed.
	 * @return array An array of objects representing the rows retrieved from the database.
	 */
	protected function getList($sqlQuery){
		$tab = QueryExecutor::execute($this->transaction,$sqlQuery);
		$ret = array();
		for($i=0;$i<oldCount($tab);$i++){
			$ret[$i] = $this->readRow($tab[$i]);
		}
		return $ret;
	}
	
	/**
	 * Get row
	 *
	 * @return UsuarioTokenInternoMySql 
	 */
	protected function getRow($sqlQuery){
		$tab = QueryExecutor::execute($this->transaction,$sqlQuery);
		if(oldCount($tab)==0){
			return null;
		}
		return $this->readRow($tab[0]);		
	}
	
	/**
	 * Execute sql query
	 */
	protected function execute($sqlQuery){
		return QueryExecutor::execute($this->transaction,$sqlQuery);
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
	protected function executeUpdate($sqlQuery){
		return QueryExecutor::executeUpdate($this->transaction,$sqlQuery);
	}

	/**
	 * Query for one row and one column
	 */
	protected function querySingleResult($sqlQuery){
		return QueryExecutor::queryForString($this->transaction,$sqlQuery);
	}

	/**
	 * Insert row to table
	 */
	protected function executeInsert($sqlQuery){
		return QueryExecutor::executeInsert($this->transaction,$sqlQuery);
	}
}
?>