<?php namespace Backend\mysql;
use Backend\dao\DepartamentoDAO;
use Backend\dto\Departamento;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
/** 
* Clase 'DepartamentoMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'Departamento'
* 
* Ejemplo de uso: 
* $DepartamentoMySqlDAO = new DepartamentoMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class DepartamentoMySqlDAO implements DepartamentoDAO
{

    /**
    * Atributo Transaction transacción
    *
    * @var object
    */
    private $transaction;

    /**
     * Obtener la transacción de un objeto
     *
     * @return Objeto Transaction transacción
     *
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * Modificar el atributo transacción del objeto
     *
     * @param Objeto $Transaction transacción
     *
     * @return no
     *
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
    * Constructor de clase
    *
    *
    * @param Objeto $transaction transaccion
    *
    * @return no
    * @throws no
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
	public function __construct($transaction="")
	{
		if ($transaction == "") 
		{
			$transaction = new Transaction();
			$this->transaction = $transaction;
		}
		else 
		{
			$this->transaction = $transaction;
		}
	}






    /**
     * Obtener el registro condicionado por la 
     * llave primaria que se pasa como parámetro
     *
     * @param String $cupolog_id llave primaria
     *
     * @return Array resultado de la consulta
     *
     */
	public function load($id)
	{
		$sql = 'SELECT * FROM departamento WHERE depto_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($id);
		return $this->getRow($sqlQuery);
	}

    /**
     * Obtener todos los registros de la base datos
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryAll()
	{
		$sql = 'SELECT * FROM departamento';
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
     * @return Array resultado de la consulta
     *
     */
	public function queryAllOrderBy($orderColumn)
	{
		$sql = 'SELECT * FROM departamento ORDER BY ' . $orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}

    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $depto_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function delete($depto_id)
	{
		$sql = 'DELETE FROM departamento WHERE depto_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($depto_id);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Insertar un registro en la base de datos
     *
     * @param Object departamento departamento
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($departamento)
	{
		$sql = 'INSERT INTO departamento (depto_cod, depto_nom, pais_id) VALUES (?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);

		$sqlQuery->set($departamento->deptoCod);
		$sqlQuery->set($departamento->deptoNom);
		$sqlQuery->set($departamento->paisId);

		$id = $this->executeInsert($sqlQuery);
		$departamento->deptoId = $id;
		return $id;
	}

    /**
     * Editar un registro en la base de datos
     *
     * @param Object departamento departamento
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($departamento)
	{
		$sql = 'UPDATE departamento SET depto_cod = ?, depto_nom = ?, pais_id = ? WHERE depto_id = ?';
		$sqlQuery = new SqlQuery($sql);

		$sqlQuery->set($departamento->deptoCod);
		$sqlQuery->set($departamento->deptoNom);
		$sqlQuery->set($departamento->paisId);

		$sqlQuery->set($departamento->deptoId);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todas los registros de la base de datos
     *
     * @param no
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function clean()
	{
		$sql = 'DELETE FROM departamento';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}

	/**
	 * Consulta personalizada de departamentos con filtros y paginación.
	 *
	 * @param string $sidx Columna por la cual se ordenarán los resultados.
	 * @param string $sord Orden de los resultados (ascendente o descendente).
	 * @param int $start Índice de inicio para la paginación.
	 * @param int $limit Número de registros a devolver.
	 * @param string $filters Filtros en formato JSON para aplicar a la consulta.
	 * @param bool $searchOn Indica si se deben aplicar los filtros.
	 * @param string $mandante (Opcional) Mandante para filtrar los resultados.
	 * @return string JSON con el conteo total de registros y los datos de los departamentos.
	 */
    public function queryDepartamentosCustom($sidx, $sord, $start, $limit, $filters, $searchOn, $mandante = '') {
		$where = " where 1=1 ";

		if($searchOn) {
			// Construye el where
			$filters = json_decode($filters);
			$whereArray = array();
			$rules = $filters->rules;
			$groupOperation = $filters->groupOp;
			$cont = 0;

			foreach($rules as $rule) {
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
                
				if (oldCount($whereArray)>0) {
					$where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
				} else {
					$where = "";
				}
			}
		}

		$innerMandante='';

		if ($mandante != '') {
			$innerMandante=  ' INNER JOIN pais_mandante ON (departamento.pais_id = pais_mandante.pais_id AND pais_mandante.mandante IN ("'.$mandante.'")  ) ';
		}

		$sql = 'SELECT count(*) count from departamento INNER JOIN pais_moneda ON (pais_moneda.pais_id = departamento.pais_id) '.$innerMandante . $where ;

		$sqlQuery = new SqlQuery($sql);

		$count = $this->execute2($sqlQuery);

		$sql = 'SELECT departamento.* from departamento INNER JOIN pais_moneda ON (pais_moneda.pais_id = departamento.pais_id) '.$innerMandante . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

		$sqlQuery = new SqlQuery($sql);

		$result = $this->execute2($sqlQuery);

		$json = '{"count" : ' . json_encode($count) . ', "data" : '. json_encode($result) . '}';

		return  $json;
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna depto_cod sea igual al valor pasado como parámetro
     *
     * @param String $value depto_cod requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByDeptoCod($value)
	{
		$sql = 'SELECT * FROM departamento WHERE depto_cod = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna depto_nom sea igual al valor pasado como parámetro
     *
     * @param String $value depto_nom requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByDeptoNom($value)
	{
		$sql = 'SELECT * FROM departamento WHERE depto_nom = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna pais_id sea igual al valor pasado como parámetro
     *
     * @param String $value pais_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByPaisId($value)
	{
		$sql = 'SELECT * FROM departamento WHERE pais_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		
		return $this->getList($sqlQuery);
	}







    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna depto_cod sea igual al valor pasado como parámetro
     *
     * @param String $value depto_cod requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByDeptoCod($value)
	{
		$sql = 'DELETE FROM departamento WHERE depto_cod = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna depto_nom sea igual al valor pasado como parámetro
     *
     * @param String $value depto_nom requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByDeptoNom($value)
	{
		$sql = 'DELETE FROM departamento WHERE depto_nom = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna pais_id sea igual al valor pasado como parámetro
     *
     * @param String $value pais_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByPaisId($value)
	{
		$sql = 'DELETE FROM departamento WHERE pais_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}







    /**
     * Crear y devolver un objeto del tipo Departamento
     * con los valores de una consulta sql
     * 
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Object $Departamento Departamento
     *
     * @access protected
     *
     */
	protected function readRow($row)
	{
		$departamento = new Departamento();

		$departamento->deptoId = $row['depto_id'];
		$departamento->deptoCod = $row['depto_cod'];
		$departamento->deptoNom = $row['depto_nom'];
		$departamento->paisId = $row['pais_id'];

		return $departamento;
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
	 * Ejecuta una consulta SQL utilizando el ejecutor de consultas.
	 *
	 * @param SqlQuery $sqlQuery La consulta SQL a ejecutar.
	 * @return mixed El resultado de la ejecución de la consulta.
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