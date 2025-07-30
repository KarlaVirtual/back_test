<?php namespace Backend\mysql;
/**
 * Class that operate on table 'cuenta_contable'. Database Mysql.
 *
 * @author: Daniel Tamayo
 * @date: 2017-09-06 18:55
 * @category No
 * @package No
 * @version     1.0
 */

use Backend\dao\CuentaContableDAO;
use Backend\dto\CuentaContable;
use Backend\sql\QueryExecutor;
use Backend\sql\SqlQuery;
use Backend\sql\Transaction;
class CuentaContableMySqlDAO implements CuentaContableDAO{

	/** Objeto vincula una conexión de la base de datos con el objeto correspondiente
     * @var Transaction $transaction
     */
    private $transaction;

    /**
     * Obtiene la transacción actual.
     *
     * @return Transaction La transacción actual.
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * Establece una nueva transacción.
     *
     * @param Transaction $transaction La nueva transacción.
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
	 * @return CuentaContableMySql 
	 */
	public function load($id){
		$sql = 'SELECT * FROM cuenta_contable WHERE cuentacontable_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($id);
		return $this->getRow($sqlQuery);
	}

	/**
	 * Get all records from table
	 */
	public function queryAll(){
		$sql = 'SELECT * FROM cuenta_contable';
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
	/**
	 * Get all records from table ordered by field
	 *
	 * @param $orderColumn column name
	 */
	public function queryAllOrderBy($orderColumn){
		$sql = 'SELECT * FROM cuenta_contable ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
	/**
 	 * Delete record from table
 	 * @param cuenta_contable primary key
 	 */
	public function delete($cuentacontable_id){
		$sql = 'DELETE FROM cuenta_contable WHERE cuentacontable_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($cuentacontable_id);
		return $this->executeUpdate($sqlQuery);
	}
	
	/**
 	 * Insert record to table
 	 *
 	 * @param CuentaContableMySql cuenta_contable
 	 */
	public function insert($cuenta_contable){
		$sql = 'INSERT INTO cuenta_contable ( descripcion, estado,usucrea_id,usumodif_id,referencia) VALUES (?, ?, ?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($cuenta_contable->descripcion);
		$sqlQuery->set($cuenta_contable->estado);
        $sqlQuery->set($cuenta_contable->usucreaId);
        $sqlQuery->set($cuenta_contable->usumodifId);
        $sqlQuery->set($cuenta_contable->referencia);

		$id = $this->executeInsert($sqlQuery);	
		$cuenta_contable->cuentacontableId = $id;
		return $id;
	}
	
	/**
 	 * Update record in table
 	 *
 	 * @param CuentaContableMySql cuenta_contable
 	 */
	public function update($cuenta_contable){
		$sql = 'UPDATE cuenta_contable SET descripcion = ?, estado = ?,usucrea_id = ?,usumodif_id = ?, referencia = ? WHERE cuentacontable_id = ?';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($cuenta_contable->descripcion);
		$sqlQuery->set($cuenta_contable->estado);
        $sqlQuery->set($cuenta_contable->usucreaId);
        $sqlQuery->set($cuenta_contable->usumodifId);
        $sqlQuery->set($cuenta_contable->referencia);

		$sqlQuery->set($cuenta_contable->cuentacontableId);
		return $this->executeUpdate($sqlQuery);
	}

	/**
	 * Realiza una consulta personalizada en la tabla cuenta_contable.
	 *
	 * @param string $select Columnas a seleccionar en la consulta.
	 * @param string $sidx Columna por la cual se ordenarán los resultados.
	 * @param string $sord Orden de los resultados (ASC o DESC).
	 * @param int $start Índice de inicio para la paginación.
	 * @param int $limit Número de registros a obtener.
	 * @param string $filters Filtros en formato JSON para construir la cláusula WHERE.
	 * @param bool $searchOn Indica si se deben aplicar los filtros.
	 * @return string JSON con el conteo de registros y los datos obtenidos.
	 */
    public function queryCuentaContableesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
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


        $sql = 'SELECT count(*) count FROM cuenta_contable ' . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT ' .$select .'  FROM cuenta_contable ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;


        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }



    /**
 	 * Delete all rows
 	 */
	public function clean(){
		$sql = 'DELETE FROM cuenta_contable';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Consulta registros por tipo.
     *
     * @param mixed $value Valor del tipo.
     * @return array Lista de registros.
     */
    public function queryByTipo($value)
    {
        $sql = 'SELECT * FROM cuenta_contable WHERE tipoId = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Consulta registros por descripción.
     *
     * @param mixed $value Valor de la descripción.
     * @return array Lista de registros.
     */
    public function queryByDescripcion($value)
    {
        $sql = 'SELECT * FROM cuenta_contable WHERE descripcion = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Consulta registros por estado.
     *
     * @param mixed $value Valor del estado.
     * @return array Lista de registros.
     */
    public function queryByEstado($value)
    {
        $sql = 'SELECT * FROM cuenta_contable WHERE estado = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Consulta registros por mandante.
     *
     * @param mixed $value Valor del mandante.
     * @return array Lista de registros.
     */
    public function queryByMandante($value)
    {
        $sql = 'SELECT * FROM cuenta_contable WHERE mandante = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Consulta registros por código abreviado.
     *
     * @param mixed $value Valor del código.
     * @return array Lista de registros.
     */
    public function queryByAbreviado($value)
    {
        $sql = 'SELECT * FROM cuenta_contable WHERE codigo = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Elimina registros por tipo.
     *
     * @param mixed $value Valor del tipo.
     * @return int Número de registros eliminados.
     */
    public function deleteByTipo($value)
    {
        $sql = 'DELETE FROM cuenta_contable WHERE tipoId = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Elimina registros por descripción.
     *
     * @param mixed $value Valor de la descripción.
     * @return int Número de registros eliminados.
     */
    public function deleteByDescripcion($value)
    {
        $sql = 'DELETE FROM cuenta_contable WHERE descripcion = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Elimina registros por estado.
     *
     * @param mixed $value Valor del estado.
     * @return int Número de registros eliminados.
     */
    public function deleteByEstado($value)
    {
        $sql = 'DELETE FROM cuenta_contable WHERE estado = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Elimina registros por mandante.
     *
     * @param mixed $value Valor del mandante.
     * @return int Número de registros eliminados.
     */
    public function deleteByMandante($value)
    {
        $sql = 'DELETE FROM cuenta_contable WHERE mandante = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }


	
	/**
	 * Read row
	 *
	 * @return CuentaContableMySql 
	 */
	protected function readRow($row){
		$cuenta_contable = new CuentaContable();
		
		$cuenta_contable->cuentacontableId = $row['cuentacontable_id'];
		$cuenta_contable->descripcion = $row['descripcion'];
		$cuenta_contable->estado = $row['estado'];
        $cuenta_contable->usucreaId = $row['usucrea_id'];
        $cuenta_contable->usumodifId = $row['usumodif_id'];
        $cuenta_contable->referencia = $row['referencia'];

		return $cuenta_contable;
	}
	
    /**
	 * Obtiene una lista de objetos de dominio a partir de una consulta SQL.
	 *
	 * @param SqlQuery $sqlQuery La consulta SQL a ejecutar.
	 * @return array Lista de objetos de dominio.
	 */
	protected function getList($sqlQuery){
	    $tab = QueryExecutor::execute($this->transaction, $sqlQuery);
	    $ret = array();
	    for($i = 0; $i < oldCount($tab); $i++){
	        $ret[$i] = $this->readRow($tab[$i]);
	    }
	    return $ret;
	}

	/**
	 * Get row
	 *
	 * @return CuentaContableMySql 
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