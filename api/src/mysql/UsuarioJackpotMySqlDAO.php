<?php namespace Backend\mysql;
 use Backend\dao\LealtadHistorialDAO;
 use Backend\dao\UsuarioJackpotDAO;
 use Backend\dto\Helpers;
 use Backend\dto\LealtadHistorial;
 use Backend\dto\UsuarioJackpot;
 use Backend\sql\QueryExecutor;
 use Backend\sql\SqlQuery;
 use Backend\sql\Transaction;

/**
 * Class that operate on table 'lealtadHistorial'. Database Mysql.
 *
 * @author: Daniel Tamayo
 * @date: 2017-09-06 18:55
 * @package No
 * @category No
 * @version    1.0
 */
class UsuarioJackpotMySqlDAO implements UsuarioJackpotDAO {


	/**
	 * Objeto contiene la relación entre la conexión a la base de datos y UsuarioJackpotMySqlDAO
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
	 * @return UsuarioJackpotMySql
	 */
	public function load($id){
		$sql = 'SELECT * FROM usuario_jackpot WHERE usujackpot_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($id);
		return $this->getRow($sqlQuery);
	}

	/**
	 * Load a record from the usuario_jackpot table based on the given jackpot ID and user ID.
	 *
	 * @param int $jackpotId The ID of the jackpot.
	 * @param int $usuarioId The ID of the user.
	 * @return array The row from the usuario_jackpot table that matches the given jackpot ID and user ID.
	 */
	public function loadByJackpotIdAndUserId($jackpotId, $usuarioId) {
		$sql = 'SELECT * FROM usuario_jackpot WHERE jackpot_id = ? AND usuario_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($jackpotId);
		$sqlQuery->set($usuarioId);
		return $this->getRow($sqlQuery);
	}

	/**
	 * Get all records from table
	 */
	public function queryAll(){
		$sql = 'SELECT * FROM usuario_jackpot';
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}

	/**
	 * Get all records from table ordered by field
	 *
	 * @param $orderColumn column name
	 */
	public function queryAllOrderBy($orderColumn){
		$sql = 'SELECT * FROM usuario_jackpot ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}

	/**
	 * Delete record from table
	 * @param lealtadHistorial primary key
	 */
	public function delete($usujackpot_id){
		$sql = 'DELETE FROM usuario_jackpot WHERE usujackpot_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($usujackpot_id);
		return $this->executeUpdate($sqlQuery);
	}

	/**
	 * Insert record to table
	 *
	 * @param UsuarioJackpotMySql lealtadHistorial
	 */
	public function insert($usuarioJackpot){

		$sql = 'INSERT INTO usuario_jackpot (jackpot_id,usuario_id, valor, usucrea_id,usumodif_id, externa_id,apostado,valor_premio) VALUES (?,?, ?, ?, ?, ?, ?, ?)';

		$sqlQuery = new SqlQuery($sql);

		$sqlQuery->set($usuarioJackpot->jackpotId);
		$sqlQuery->set($usuarioJackpot->usuarioId);
		$sqlQuery->set($usuarioJackpot->valor);
		$sqlQuery->set($usuarioJackpot->usucreaId);
		$sqlQuery->set($usuarioJackpot->usumodifId);
		$sqlQuery->set($usuarioJackpot->externoId);
		$sqlQuery->set($usuarioJackpot->apostado);
		if($usuarioJackpot->valorPremio == ""){
			$usuarioJackpot->valorPremio = 0;
		}
		$sqlQuery->set($usuarioJackpot->valorPremio);

        $id = $this->executeInsert($sqlQuery);


		$usuarioJackpot->usujackpotId = $id;

		return $id;
	}

	/**
 	 * Update record in table
 	 *
 	 * @param UsuarioJackpotMySql lealtadHistorial
 	 */
	public function update($usuarioJackpot){
		$sql = 'UPDATE usuario_jackpot SET jackpot_id = ?,usuario_id = ?, valor = ?,usucrea_id = ?,usumodif_id = ?, externa_id = ?, apostado = ?,valor_premio = ? WHERE usujackpot_id = ?';
		$sqlQuery = new SqlQuery($sql);

		$sqlQuery->set($usuarioJackpot->jackpotId);
		$sqlQuery->set($usuarioJackpot->usuarioId);
		$sqlQuery->set($usuarioJackpot->valor);
		$sqlQuery->set($usuarioJackpot->usucreaId);
		$sqlQuery->set($usuarioJackpot->usumodifId);
		$sqlQuery->set($usuarioJackpot->externoId);
		$sqlQuery->set($usuarioJackpot->apostado);
		if($usuarioJackpot->valorPremio == ""){
			$usuarioJackpot->valorPremio = 0;
		}
		$sqlQuery->set($usuarioJackpot->valorPremio);

		$sqlQuery->set($usuarioJackpot->usujackpotId);
		return $this->executeUpdate($sqlQuery);
	}

	/**
	 * Executes a custom query on the `usuario_jackpot` table with various filters, sorting, and pagination options.
	 *
	 * @param string $select The columns to select in the query.
	 * @param string $sidx The column by which to sort the results.
	 * @param string $sord The sorting order (ASC or DESC).
	 * @param int $start The starting index for pagination.
	 * @param int $limit The number of records to return for pagination.
	 * @param string $filters JSON-encoded string containing the filters to apply.
	 * @param bool $searchOn Flag indicating whether to apply the filters.
	 * @param string $grouping The column(s) by which to group the results.
	 * @param array $joins An array of join conditions to apply to the query.
	 * 
	 * @return string JSON-encoded string containing the count of records and the data.
	 */
    public function queryUsuarioJackpotCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$grouping, $joins = [])
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
                if (count($whereArray) > 0) {
                    $where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
                } else {
                    $where = "";
                }
            }

        }

		/** Construyendo cadena de joins solicitados fuera de la petición JOINS DINÁMICOS*/
		$strJoins = " ";
		if (!empty($joins)) {
			foreach ($joins as $join) {
				/**
				 *Ejemplo estructura $join
				 *{
				 *     "type": "INNER" | "LEFT" | "RIGHT",
				 *     "table": "usuario_puntoslealtad"
				 *     "on": "usuario.usuario_id = usuario_puntoslealtad.usuario_id"
				 *}
				 */
				$allowedJoins = ["INNER", "LEFT", "RIGHT"];
				if (in_array($join->type, $allowedJoins)) {
					//Estructurando cadena de joins
					$strJoins .= " " . strtoupper($join->type) . " JOIN " . $join->table . " ON (" . $join->on . ") ";
				}
			}
		}


		if($grouping != ""){
			$where = $where . " GROUP BY " . $grouping;
		}

        $sql = 'SELECT count(*) count FROM usuario_jackpot INNER JOIN usuario ON (usuario_jackpot.usuario_id = usuario.usuario_id) INNER JOIN usuario_mandante ON (usuario.usuario_id = usuario_mandante.usuario_mandante) INNER JOIN jackpot_interno ON (usuario_jackpot.jackpot_id = jackpot_interno.jackpot_id)  ' . $strJoins  . $where  ;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT ' .$select .'  FROM usuario_jackpot INNER JOIN usuario ON (usuario_jackpot.usuario_id = usuario.usuario_id)INNER JOIN usuario_mandante ON (usuario.usuario_id = usuario_mandante.usuario_mandante) INNER JOIN jackpot_interno ON (usuario_jackpot.jackpot_id = jackpot_interno.jackpot_id) ' . $strJoins . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

		$sqlQuery = new SqlQuery($sql);

		$result = $Helpers->process_data($this->execute2($sqlQuery));

		$json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

		return $json;
	}

	/**
	 * Executes a custom query on the `usuario_jackpot` table with various filtering, sorting, and grouping options.
	 *
	 * @param string $select The columns to select in the query.
	 * @param string $sidx The column by which to sort the results.
	 * @param string $sord The sort order (ASC or DESC).
	 * @param int $start The starting index for the results.
	 * @param int $limit The maximum number of results to return.
	 * @param string $filters JSON-encoded string containing the filters to apply.
	 * @param bool $searchOn Flag indicating whether to apply the filters.
	 * @param string $grouping The column by which to group the results.
	 * 
	 * @return string JSON-encoded string containing the count of results and the data.
	 */
	public function queryUsuarioJackpotCustom2($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$grouping)
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
				if (count($whereArray) > 0) {
					$where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
				} else {
					$where = "";
				}
			}

		}
		if($grouping != ""){
			$where = $where . " GROUP BY " . $grouping;
		}

		$sql = 'SELECT count(*) count FROM usuario_jackpot INNER JOIN usuario ON (usuario_jackpot.usuario_id = usuario.usuario_id) ' . $where;


		$sqlQuery = new SqlQuery($sql);

		$count = $this->execute2($sqlQuery);


		$sql = 'SELECT ' .$select .'  FROM usuario_jackpot INNER JOIN usuario ON (usuario_jackpot.usuario_id = usuario.usuario_id)  ' . $where. "" . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

		$sqlQuery = new SqlQuery($sql);

		$result = $Helpers->process_data($this->execute2($sqlQuery));

		$json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

		return $json;
	}

	/**
	 * Delete all rows
	 */
	public function clean(){
		$sql = 'DELETE FROM usuario_jackpot';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}

/**
 * Query records from the `usuario_jackpot` table by `usuarioId`.
 *
 * @param mixed $value The value to filter by `usuarioId`.
 * @return array The list of matching records.
 */
public function queryByTipo($value){
	$sql = 'SELECT * FROM usuario_jackpot WHERE usuarioId = ?';
	$sqlQuery = new SqlQuery($sql);
	$sqlQuery->set($value);
	return $this->getList($sqlQuery);
}

/**
 * Query records from the `usuario_jackpot` table by `descripcion`.
 *
 * @param mixed $value The value to filter by `descripcion`.
 * @return array The list of matching records.
 */
public function queryByDescripcion($value){
	$sql = 'SELECT * FROM usuario_jackpot WHERE descripcion = ?';
	$sqlQuery = new SqlQuery($sql);
	$sqlQuery->set($value);
	return $this->getList($sqlQuery);
}

/**
 * Query records from the `usuario_jackpot` table by `estado`.
 *
 * @param mixed $value The value to filter by `estado`.
 * @return array The list of matching records.
 */
public function queryByEstado($value){
	$sql = 'SELECT * FROM usuario_jackpot WHERE estado = ?';
	$sqlQuery = new SqlQuery($sql);
	$sqlQuery->set($value);
	return $this->getList($sqlQuery);
}

/**
 * Query records from the `usuario_jackpot` table by `mandante`.
 *
 * @param mixed $value The value to filter by `mandante`.
 * @return array The list of matching records.
 */
public function queryByMandante($value){
	$sql = 'SELECT * FROM usuario_jackpot WHERE mandante = ?';
	$sqlQuery = new SqlQuery($sql);
	$sqlQuery->set($value);
	return $this->getList($sqlQuery);
}

/**
 * Query records from the `usuario_jackpot` table by `codigo`.
 *
 * @param mixed $value The value to filter by `codigo`.
 * @return array The list of matching records.
 */
public function queryByAbreviado($value){
	$sql = 'SELECT * FROM usuario_jackpot WHERE codigo = ?';
	$sqlQuery = new SqlQuery($sql);
	$sqlQuery->set($value);
	return $this->getList($sqlQuery);
}

/**
 * Delete records from the `usuario_jackpot` table by `usuarioId`.
 *
 * @param mixed $value The value to filter by `usuarioId`.
 * @return int The number of rows affected.
 */
public function deleteByTipo($value){
	$sql = 'DELETE FROM usuario_jackpot WHERE usuarioId = ?';
	$sqlQuery = new SqlQuery($sql);
	$sqlQuery->set($value);
	return $this->executeUpdate($sqlQuery);
}

/**
 * Delete records from the `usuario_jackpot` table by `descripcion`.
 *
 * @param mixed $value The value to filter by `descripcion`.
 * @return int The number of rows affected.
 */
public function deleteByDescripcion($value){
	$sql = 'DELETE FROM usuario_jackpot WHERE descripcion = ?';
	$sqlQuery = new SqlQuery($sql);
	$sqlQuery->set($value);
	return $this->executeUpdate($sqlQuery);
}

/**
 * Delete records from the `usuario_jackpot` table by `estado`.
 *
 * @param mixed $value The value to filter by `estado`.
 * @return int The number of rows affected.
 */
public function deleteByEstado($value){
	$sql = 'DELETE FROM usuario_jackpot WHERE estado = ?';
	$sqlQuery = new SqlQuery($sql);
	$sqlQuery->set($value);
	return $this->executeUpdate($sqlQuery);
}

/**
 * Delete records from the `usuario_jackpot` table by `mandante`.
 *
 * @param mixed $value The value to filter by `mandante`.
 * @return int The number of rows affected.
 */
public function deleteByMandante($value){
	$sql = 'DELETE FROM usuario_jackpot WHERE mandante = ?';
	$sqlQuery = new SqlQuery($sql);
	$sqlQuery->set($value);
	return $this->executeUpdate($sqlQuery);
}


	/**
	 * Read row
	 *
	 * @return UsuarioJackpotMySql
	 */
	protected function readRow($row){
		$usuarioJackpot = new UsuarioJackpot();
		$usuarioJackpot->usujackpotId = $row['usujackpot_id'];
		$usuarioJackpot->jackpotId = $row['jackpot_id'];
		$usuarioJackpot->usuarioId = $row['usuario_id'];
		$usuarioJackpot->usucreaId = $row['usucrea_id'];
		$usuarioJackpot->usumodifId = $row['usumodif_id'];
		$usuarioJackpot->valor = $row['valor'];
		$usuarioJackpot->externoId = $row['externo_id'];
		$usuarioJackpot->apostado = $row['apostado'];
		$usuarioJackpot->valorPremio = $row['valor_premio'];

		return $usuarioJackpot;
	}

	protected function getList($sqlQuery){
		$tab = QueryExecutor::execute($this->transaction,$sqlQuery);
		$ret = array();
		for($i=0;$i<count($tab);$i++){
			$ret[$i] = $this->readRow($tab[$i]);
		}
		return $ret;
	}

	/**
	 * Get row
	 *
	 * @return UsuarioJackpotMySql
	 */
	protected function getRow($sqlQuery){
		$tab = QueryExecutor::execute($this->transaction,$sqlQuery);
		if(count($tab)==0){
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
