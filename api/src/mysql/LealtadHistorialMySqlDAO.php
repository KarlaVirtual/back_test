<?php namespace Backend\mysql;
/**
 * Class that operate on table 'lealtadHistorial'. Database Mysql.
 *
 * @author: Daniel Tamayo
 * @date: 2017-09-06 18:55
 * @package ninguno
 * @version 1.0
 * @since desconocido
 */

use Backend\dao\LealtadHistorialDAO;
use Backend\dto\Helpers;
use Backend\dto\LealtadHistorial;
use Backend\sql\QueryExecutor;
use Backend\sql\SqlQuery;
use Backend\sql\Transaction;
use Backend\sql\ConnectionProperty;
use PDO;
class LealtadHistorialMySqlDAO implements LealtadHistorialDAO{


   private $transaction;

    /**
     * Gets the current transaction.
     *
     * @return mixed The current transaction.
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * Sets a new transaction.
     *
     * @param mixed $transaction The new transaction to set.
     */
    public function setTransaction($transaction)
    {
        $this->transaction = $transaction;
    }

	/**
	 * TransaccionProductoMySqlDAO constructor.
	 * @param $transaction
	 */

	/**
	 * Constructor for TransaccionProductoMySqlDAO.
	 *
	 * @param mixed $transaction The transaction to set. If not provided, a new Transaction will be created.
	 */
	public function __construct($transaction = "")
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
	 * @return LealtadHistorialMySql
	 */
	public function load($id){
		$sql = 'SELECT * FROM lealtad_historial WHERE lealtadhistorial_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($id);
		return $this->getRow($sqlQuery);
	}

	/**
	 * Get all records from table
	 */
	public function queryAll(){
		$sql = 'SELECT * FROM lealtad_historial';
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}

	/**
	 * Get all records from table ordered by field
	 *
	 * @param $orderColumn column name
	 */
	public function queryAllOrderBy($orderColumn){
		$sql = 'SELECT * FROM lealtad_historial ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}

	/**
	 * Delete record from table
	 * @param lealtadHistorial primary key
	 */
	public function delete($lealtadhistorial_id){
		$sql = 'DELETE FROM lealtad_historial WHERE lealtadhistorial_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($lealtadhistorial_id);
		return $this->executeUpdate($sqlQuery);
	}

	/**
	 * Insert record to table
	 *
	 * @param LealtadHistorialMySql lealtadHistorial
	 */
	public function insert($lealtadHistorial,$isPV = '0'){


	    if($isPV == '1'){
            $sql = 'INSERT INTO lealtad_historial (usuario_id, descripcion, movimiento,usucrea_id,usumodif_id, tipo, valor, externo_id,creditos,creditos_base,fecha_exp,fecha_exptime,mandante,pais_id) SELECT ?, ?, ?, ?, ?, ?, ?, ?,cupo_recarga,creditos_base,?,mandante,pais_id FROM punto_venta WHERE usuario_id ='.$lealtadHistorial->usuarioId;

        }else{
            $sql = 'INSERT INTO lealtad_historial (usuario_id, descripcion, movimiento,usucrea_id,usumodif_id, tipo, valor, externo_id,creditos,creditos_base,fecha_exp,fecha_exptime,mandante,pais_id) SELECT ?, ?, ?, ?, ?, ?, ?, ?,usuario_puntoslealtad.puntos_lealtad + usuario_puntoslealtad.puntos_aexpirar,0,?,?,usuario.mandante,usuario.pais_id FROM usuario  INNER JOIN usuario_puntoslealtad ON usuario.usuario_id = usuario_puntoslealtad.usuario_id WHERE usuario.usuario_id ='.$lealtadHistorial->usuarioId;

        }
		$sqlQuery = new SqlQuery($sql);

		$sqlQuery->set($lealtadHistorial->usuarioId);
		$sqlQuery->set($lealtadHistorial->descripcion);
		$sqlQuery->set($lealtadHistorial->movimiento);
        $sqlQuery->set($lealtadHistorial->usucreaId);
        $sqlQuery->set($lealtadHistorial->usumodifId);
        $sqlQuery->set($lealtadHistorial->tipo);
        $sqlQuery->set($lealtadHistorial->valor);
        $sqlQuery->set($lealtadHistorial->externoId);
        $sqlQuery->set($lealtadHistorial->fechaExp);
        $sqlQuery->set(strtotime($lealtadHistorial->fechaExp));

        $id = $this->executeInsert($sqlQuery);


		$lealtadHistorial->lealtadHistorialId = $id;

		return $id;
	}

	/**
	 * Updates a record in the 'lealtad_historial' table.
	 *
	 * @param LealtadHistorialMySql $lealtadHistorial The LealtadHistorial object containing the updated data.
	 * @return int The number of affected rows.
	 */
	public function update($lealtadHistorial){
		$sql = 'UPDATE lealtad_historial SET usuario_id = ?, descripcion = ?, movimiento = ?,usucrea_id = ?,usumodif_id = ?, tipo = ?, valor = ?, externo_id = ?, fecha_exp = ? WHERE lealtadhistorial_id = ?';
		$sqlQuery = new SqlQuery($sql);

		$sqlQuery->set($lealtadHistorial->usuarioId);
		$sqlQuery->set($lealtadHistorial->descripcion);
		$sqlQuery->set($lealtadHistorial->movimiento);
        $sqlQuery->set($lealtadHistorial->usucreaId);
        $sqlQuery->set($lealtadHistorial->usumodifId);
        $sqlQuery->set($lealtadHistorial->tipo);
        $sqlQuery->set($lealtadHistorial->valor);
        $sqlQuery->set($lealtadHistorial->externoId);
        $sqlQuery->set($lealtadHistorial->fechaExp);

		$sqlQuery->set($lealtadHistorial->lealtadHistorialId);
		return $this->executeUpdate($sqlQuery);
	}


	/**
	 * Executes a custom query on the 'lealtad_historial' table with various filters, sorting, and pagination options.
	 *
	 * @param string $select The columns to select in the query.
	 * @param string $sidx The column to sort by.
	 * @param string $sord The sort order (ASC or DESC).
	 * @param int $start The starting index for pagination.
	 * @param int $limit The number of records to return.
	 * @param string $filters JSON-encoded string containing the filters to apply.
	 * @param bool $searchOn Flag indicating whether to apply the filters.
	 * @param string $grouping The column(s) to group by.
	 * @param array $joins Optional array of join conditions.
	 * @param bool $groupingCount Flag indicating whether to count the grouped results.
	 * @return string JSON-encoded string containing the count of records and the data.
	 */
    public function queryLealtadHistorialeCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$grouping, $joins = [], $groupingCount = false)
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
                if (oldCount($whereArray) > 0) {
                    $where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
                } else {
                    $where = "";
                }
            }

        }

		/** Construyendo cadena de joins solicitados fuera de la petición */
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

		/** Modificando el count para los casos donde se usa un group by en la consulta con el fin de garantizar un correcto funcionamiento
		 *IMPORTANTE Estos 2 parámetros deben ir al inicio y final respectivamente de la consulta del count
		 */
		$groupByCountPrefix = "";
		$groupByCountSuffix = "";
		if (!empty($grouping) && $groupingCount) {
			$groupByCountPrefix = " SELECT COUNT(*) AS count FROM (";
			$groupByCountSuffix = " ) x";
		}

		if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ){
			$connOriginal = $_ENV["connectionGlobal"]->getConnection();




			$connDB5 = null;


			if($_ENV['ENV_TYPE'] =='prod') {

				$connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()
					, array(
						PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-bundle.crt',
						PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
					)
				);
			}else{

				$connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()

				);
			}

			$connDB5->exec("set names utf8");
			$connDB5->exec("set use_secondary_engine=off");

			try{

				if($_ENV["TIMEZONE"] !=null && $_ENV["TIMEZONE"] !='') {
					$connDB5->exec('SET time_zone = "'.$_ENV["TIMEZONE"].'";');
				}

				if($_ENV["NEEDINSOLATIONLEVEL"] != null && $_ENV["NEEDINSOLATIONLEVEL"] == '1') {
					$connDB5->exec('SET TRANSACTION ISOLATION LEVEL READ COMMITTED;');
				}
				if($_ENV["ENABLEDSETLOCKWAITTIMEOUT"] != null && $_ENV["ENABLEDSETLOCKWAITTIMEOUT"] == '1') {
					// $connDB5->exec('SET SESSION innodb_lock_wait_timeout = 5;');
				}
				if($_ENV["ENABLEDSETMAX_EXECUTION_TIME"] != null && $_ENV["ENABLEDSETMAX_EXECUTION_TIME"] == '1') {
					// $connDB5->exec('SET SESSION MAX_EXECUTION_TIME = 120000;');
				}
				if($_ENV["DBNEEDUTF8"] != null && $_ENV["DBNEEDUTF8"] == '1') {
					$connDB5->exec("SET NAMES utf8mb4");
				}
			}catch (\Exception $e){

			}
			$_ENV["connectionGlobal"]->setConnection($connDB5);
			$this->transaction->setConnection($_ENV["connectionGlobal"]);

		}


        $sql = $groupByCountPrefix . 'SELECT /*+ MAX_EXECUTION_TIME(50000) */  count(*) count FROM lealtad_historial INNER JOIN usuario ON (lealtad_historial.usuario_id = usuario.usuario_id) ' . $strJoins . $where . $groupByCountSuffix;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT /*+ MAX_EXECUTION_TIME(50000) */  ' .$select .'  FROM lealtad_historial INNER JOIN usuario ON (lealtad_historial.usuario_id = usuario.usuario_id)  ' . $strJoins . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

		$sqlQuery = new SqlQuery($sql);

		$result = $Helpers->process_data($this->execute2($sqlQuery));

		if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ) {
			$connDB5 = null;
			$_ENV["connectionGlobal"]->setConnection($connOriginal);
			$this->transaction->setConnection($_ENV["connectionGlobal"]);
		}
		$json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

		return $json;
	}

	/**
	 * Executes a custom query on the 'lealtad_historial' table with various filters, sorting, and pagination options.
	 *
	 * @param string $select The columns to select in the query.
	 * @param string $sidx The column to sort by.
	 * @param string $sord The sort order (ASC or DESC).
	 * @param int $start The starting index for pagination.
	 * @param int $limit The number of records to return.
	 * @param string $filters JSON-encoded string containing the filters to apply.
	 * @param bool $searchOn Flag indicating whether to apply the filters.
	 * @param string $grouping The column(s) to group by.
	 * @return string JSON-encoded string containing the count of records and the data.
	 */
	public function queryLealtadHistorialeCustom2($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$grouping)
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

		$sql = 'SELECT count(*) count FROM lealtad_historial INNER JOIN usuario ON (lealtad_historial.usuario_id = usuario.usuario_id) ' . $where;


		$sqlQuery = new SqlQuery($sql);

		$count = $this->execute2($sqlQuery);


		$sql = 'SELECT ' .$select .'  FROM lealtad_historial INNER JOIN usuario ON (lealtad_historial.usuario_id = usuario.usuario_id)  ' . $where. "" . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

		$sqlQuery = new SqlQuery($sql);

		$result = $Helpers->process_data($this->execute2($sqlQuery));

		$json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

		return $json;
	}

	/**
	 * Deletes all rows from the 'lealtad_historial' table.
	 *
	 * @return int The number of affected rows.
	 */
	public function clean(){
		$sql = 'DELETE FROM lealtad_historial';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}

	/**
	 * Retrieves records from the 'lealtad_historial' table by 'usuarioId'.
	 *
	 * @param mixed $value The value of 'usuarioId' to filter by.
	 * @return array The list of matching records.
	 */
	public function queryByTipo($value){
		$sql = 'SELECT * FROM lealtad_historial WHERE usuarioId = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
	 * Retrieves records from the 'lealtad_historial' table by 'descripcion'.
	 *
	 * @param mixed $value The value of 'descripcion' to filter by.
	 * @return array The list of matching records.
	 */
	public function queryByDescripcion($value){
		$sql = 'SELECT * FROM lealtad_historial WHERE descripcion = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
	 * Retrieves records from the 'lealtad_historial' table by 'movimiento'.
	 *
	 * @param mixed $value The value of 'movimiento' to filter by.
	 * @return array The list of matching records.
	 */
	public function queryByEstado($value){
		$sql = 'SELECT * FROM lealtad_historial WHERE movimiento = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
	 * Retrieves records from the 'lealtad_historial' table by 'mandante'.
	 *
	 * @param mixed $value The value of 'mandante' to filter by.
	 * @return array The list of matching records.
	 */
	public function queryByMandante($value){
		$sql = 'SELECT * FROM lealtad_historial WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
	 * Retrieves records from the 'lealtad_historial' table by 'codigo'.
	 *
	 * @param mixed $value The value of 'codigo' to filter by.
	 * @return array The list of matching records.
	 */

	public function queryByAbreviado($value){
		$sql = 'SELECT * FROM lealtad_historial WHERE codigo = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}


	/**
	 * Deletes records from the 'lealtad_historial' table by 'usuarioId'.
	 *
	 * @param mixed $value The value of 'usuarioId' to filter by.
	 * @return int The number of affected rows.
	 */
	public function deleteByTipo($value){
		$sql = 'DELETE FROM lealtad_historial WHERE usuarioId = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
	 * Deletes records from the 'lealtad_historial' table by 'descripcion'.
	 *
	 * @param mixed $value The value of 'descripcion' to filter by.
	 * @return int The number of affected rows.
	 */
	public function deleteByDescripcion($value){
		$sql = 'DELETE FROM lealtad_historial WHERE descripcion = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
	 * Deletes records from the 'lealtad_historial' table by 'movimiento'.
	 *
	 * @param mixed $value The value of 'movimiento' to filter by.
	 * @return int The number of affected rows.
	 */

	public function deleteByEstado($value){
		$sql = 'DELETE FROM lealtad_historial WHERE movimiento = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
	 * Deletes records from the 'lealtad_historial' table by 'mandante'.
	 *
	 * @param mixed $value The value of 'mandante' to filter by.
	 * @return int The number of affected rows.
	 */
	public function deleteByMandante($value){
		$sql = 'DELETE FROM lealtad_historial WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}



	/**
	 * Reads a row from the 'lealtad_historial' table and converts it into a LealtadHistorial object.
	 *
	 * @param array $row The database row to read.
	 * @return LealtadHistorial The corresponding LealtadHistorial object.
	 */
	protected function readRow($row){
		$lealtadHistorial = new LealtadHistorial();

		$lealtadHistorial->lealtadHistorial_id = $row['lealtadhistorial_id'];
		$lealtadHistorial->usuarioId = $row['usuario_id'];
		$lealtadHistorial->descripcion = $row['descripcion'];
		$lealtadHistorial->movimiento = $row['movimiento'];
		$lealtadHistorial->usucreaId = $row['usucrea_id'];
		$lealtadHistorial->usumodifId = $row['usumodif_id'];

		$lealtadHistorial->tipo = $row['tipo'];
		$lealtadHistorial->valor = $row['valor'];
		$lealtadHistorial->externoId = $row['externo_id'];
		$lealtadHistorial->fechaExp = $row['fecha_exp'];
		$lealtadHistorial->fechaExptime = $row['fecha_exptime'];
		$lealtadHistorial->paisId = $row['pais_id'];
		$lealtadHistorial->mandante = $row['mandante'];

		return $lealtadHistorial;
	}

	/**
	 * Retrieves a list of records from the 'lealtad_historial' table.
	 *
	 * @param SqlQuery $sqlQuery The SQL query to execute.
	 * @return array The list of LealtadHistorial objects.
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
	 * Retrieves a row from the 'lealtad_historial' table.
	 *
	 * @param SqlQuery $sqlQuery The SQL query to execute.
	 * @return LealtadHistorial|null The corresponding LealtadHistorial object, or null if no row is found.
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
