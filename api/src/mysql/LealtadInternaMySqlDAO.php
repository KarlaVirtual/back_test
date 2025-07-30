<?php namespace Backend\mysql;
use Backend\dao\LealtadInternaDAO;
use Backend\dto\LealtadInterna;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Exception;
/**
 * Clase 'LealtadInternaMySqlDAO'
 *
 * Esta clase provee las consultas del modelo o tabla 'LealtadInterna'
 *
 * Ejemplo de uso:
 * $LealtadInternaMySqlDAO = new LealtadInternaMySqlDAO();
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class LealtadInternaMySqlDAO implements LealtadInternaDAO{


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
	 * Obtener todos los registros condicionados por la
	 * llave primaria que se pasa como parámetro
	 *
	 * @param String $id llave primaria
	 *
	 * @return Array $ resultado de la consulta
	 *
	 */
	public function load($id){
		$sql = 'SELECT * FROM lealtad_interna WHERE lealtad_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($id);
		return $this->getRow($sqlQuery);
	}

	/**
	 * Obtener todos los registros de la base datos
	 *
	 * @return Array $ resultado de la consulta
	 *
	 */
	public function queryAll(){
		$sql = 'SELECT * FROM lealtad_interna';
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}

	/**
	 * Ejecutar una consulta sql
	 *
	 *
	 * @param String $sql consulta sql
	 *
	 * @return Array $ resultado de la ejecución
	 *
	 * @access protected
	 *
	 */
	public function querySQL($sql)
	{
		$sqlQuery = new SqlQuery($sql);
		return $this->execute2($sqlQuery);
	}

	/**
	 * Obtener todos los registros
	 * puntosadas por el nombre de la columna
	 * que se pasa como parámetro
	 *
	 * @param String $orderColumn nombre de la columna
	 *
	 * @return Array $ resultado de la consulta
	 *
	 */
	public function queryAllOrderBy($orderColumn){
		$sql = 'SELECT * FROM lealtad_interna ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}


	/**
	 * Eliminar todos los registros condicionados
	 * por la llave primaria
	 *
	 * @param String $lealtad_id llave primaria
	 *
	 * @return boolean $ resultado de la consulta
	 *
	 */
	public function delete($lealtad_id){
		$sql = 'DELETE FROM lealtad_interna WHERE lealtad_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($lealtad_id);
		return $this->executeUpdate($sqlQuery);
	}

	/**
	 * Insertar un registro en la base de datos
	 *
	 * @param Objeto LealtadInterna LealtadInterna
	 *
	 * @return String $id resultado de la consulta
	 *
	 */
	public function insert($LealtadInterna){

		$sql = 'INSERT INTO lealtad_interna (fecha_inicio, fecha_fin, descripcion,nombre, tipo, estado, mandante, usucrea_id, usumodif_id,condicional, puntos, orden, cupo_actual,cupo_maximo,cantidad_lealtad,maximo_lealtad,codigo,reglas,bono_id,tipo_premio, puntoventa_propio, m_body, m_subject) VALUES (?, ?, ?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?)';


		$sqlQuery = new SqlQuery($sql);

		$sqlQuery->set($LealtadInterna->fechaInicio);
		$sqlQuery->set($LealtadInterna->fechaFin);
		$sqlQuery->set($LealtadInterna->descripcion);
		$sqlQuery->set($LealtadInterna->nombre);
		$sqlQuery->set($LealtadInterna->tipo);
		$sqlQuery->set($LealtadInterna->estado);
		$sqlQuery->setNumber($LealtadInterna->mandante);
		$sqlQuery->setNumber($LealtadInterna->usucreaId);
		$sqlQuery->setNumber($LealtadInterna->usumodifId);
		$sqlQuery->set($LealtadInterna->condicional);
		$sqlQuery->set($LealtadInterna->puntos);
		if($LealtadInterna->orden==''){
			$LealtadInterna->orden='0';
		}
		$sqlQuery->setNumber($LealtadInterna->orden);
		$sqlQuery->set($LealtadInterna->cupoActual);
		$sqlQuery->set($LealtadInterna->cupoMaximo);
		$sqlQuery->set($LealtadInterna->cantidadLealtad);
		$sqlQuery->set($LealtadInterna->maximoLealtad);
		$sqlQuery->set($LealtadInterna->codigo);
		$sqlQuery->set($LealtadInterna->reglas);
		$sqlQuery->set($LealtadInterna->bonoId);
		$sqlQuery->set($LealtadInterna->tipoPremio);
		$sqlQuery->set($LealtadInterna->puntoventaPropio);
		$sqlQuery->set($LealtadInterna->mBody);
		$sqlQuery->set($LealtadInterna->mSubject);


		$id = $this->executeInsert($sqlQuery);
		$LealtadInterna->lealtadId = $id;
		return $id;
	}

	/**
	 * Editar un registro en la base de datos
	 *
	 * @param Objeto LealtadInterna LealtadInterna
	 *
	 * @return boolean $ resultado de la consulta
	 *
	 */
	public function update($LealtadInterna){
		$sql = 'UPDATE lealtad_interna SET fecha_inicio = ?, fecha_fin = ?, descripcion = ?,nombre=?, tipo = ?, estado = ?,  mandante = ?, usucrea_id = ?, usumodif_id = ?, condicional = ?,puntos = ?, orden = ?, cupo_actual = ?, cupo_maximo = ?, cantidad_lealtad = ?, maximo_lealtad = ?, codigo = ?, reglas = ?, bono_id = ?, tipo_premio = ?, puntoventa_propio = ?, m_body = ?, m_subject = ? WHERE lealtad_id = ?';
		$sqlQuery = new SqlQuery($sql);

		$sqlQuery->set($LealtadInterna->fechaInicio);
		$sqlQuery->set($LealtadInterna->fechaFin);
		$sqlQuery->set($LealtadInterna->descripcion);
		$sqlQuery->set($LealtadInterna->nombre);
		$sqlQuery->set($LealtadInterna->tipo);
		$sqlQuery->set($LealtadInterna->estado);
		$sqlQuery->set($LealtadInterna->mandante);
		$sqlQuery->setNumber($LealtadInterna->usucreaId);
		$sqlQuery->setNumber($LealtadInterna->usumodifId);
		$sqlQuery->set($LealtadInterna->condicional);
		$sqlQuery->set($LealtadInterna->puntos);
		if($LealtadInterna->orden==''){
			$LealtadInterna->orden='0';
		}
		$sqlQuery->setNumber($LealtadInterna->orden);
		$sqlQuery->set($LealtadInterna->cupoActual);
		$sqlQuery->set($LealtadInterna->cupoMaximo);
		$sqlQuery->set($LealtadInterna->cantidadLealtad);
		$sqlQuery->set($LealtadInterna->maximoLealtad);
		$sqlQuery->set($LealtadInterna->codigo);

		if($LealtadInterna->reglas != ""){
			$LealtadInterna->reglas = str_replace("'","\'",$LealtadInterna->reglas);
		}

		$sqlQuery->set($LealtadInterna->reglas);
		$sqlQuery->set($LealtadInterna->bonoId);
		$sqlQuery->set($LealtadInterna->tipoPremio);
		$sqlQuery->set($LealtadInterna->puntoventaPropio);
		$sqlQuery->set($LealtadInterna->mBody);
		$sqlQuery->set($LealtadInterna->mSubject);

		$sqlQuery->setNumber($LealtadInterna->lealtadId);
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
	public function clean(){
		$sql = 'DELETE FROM lealtad_interna';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}








	/**
	 * Obtener todos los registros donde se encuentre que
	 * la columna fecha_inicio sea igual al valor pasado como parámetro
	 *
	 * @param String $value fecha_inicio requerido
	 *
	 * @return Array $ resultado de la consulta
	 *
	 */
	public function queryByUsuarioId($value){
		$sql = 'SELECT * FROM lealtad_interna WHERE fecha_inicio = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

	/**
	 * Obtener todos los registros donde se encuentre que
	 * la columna fecha_fin sea igual al valor pasado como parámetro
	 *
	 * @param String $value fecha_fin requerido
	 *
	 * @return Array $ resultado de la consulta
	 *
	 */
	public function queryByProductoId($value){
		$sql = 'SELECT * FROM lealtad_interna WHERE fecha_fin = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

	/**
	 * Obtener todos los registros donde se encuentre que
	 * la columna descripcion sea igual al valor pasado como parámetro
	 *
	 * @param String $value descripcion requerido
	 *
	 * @return Array $ resultado de la consulta
	 *
	 */
	public function queryByValorTicket($value){
		$sql = 'SELECT * FROM lealtad_interna WHERE descripcion = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

	/**
	 * Obtener todos los registros donde se encuentre que
	 * la columna tipo sea igual al valor pasado como parámetro
	 *
	 * @param String $value tipo requerido
	 *
	 * @return Array $ resultado de la consulta
	 *
	 */
	public function queryByValorPremio($value){
		$sql = 'SELECT * FROM lealtad_interna WHERE tipo = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

	/**
	 * Obtener todos los registros donde se encuentre que
	 * la columna estado sea igual al valor pasado como parámetro
	 *
	 * @param String $value estado requerido
	 *
	 * @return Array $ resultado de la consulta
	 *
	 */
	public function queryByEstado($value){
		$sql = 'SELECT * FROM lealtad_interna WHERE estado = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
	 * Obtener todos los registros donde se encuentre que
	 * la columna fecha_modif sea igual al valor pasado como parámetro
	 *
	 * @param String $value fecha_modif requerido
	 *
	 * @return Array $ resultado de la consulta
	 *
	 */
	public function queryByTransaccionId($value){
		$sql = 'SELECT * FROM lealtad_interna WHERE fecha_modif = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setString($value);
		return $this->getList($sqlQuery);
	}

	/**
	 * Obtener todos los registros donde se encuentre que
	 * la columna fecha_crea sea igual al valor pasado como parámetro
	 *
	 * @param String $value fecha_crea requerido
	 *
	 * @return Array $ resultado de la consulta
	 *
	 */
	public function queryByFechaPago($value){
		$sql = 'SELECT * FROM lealtad_interna WHERE fecha_crea = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
	 * Obtener todos los registros donde se encuentre que
	 * la columna mandante sea igual al valor pasado como parámetro
	 *
	 * @param String $value mandante requerido
	 *
	 * @return Array $ resultado de la consulta
	 *
	 */
	public function queryByMandante($value){
		$sql = 'SELECT * FROM lealtad_interna WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

	/**
	 * Obtener todos los registros donde se encuentre que
	 * la columna usucrea_id sea igual al valor pasado como parámetro
	 *
	 * @param String $value usucrea_id requerido
	 *
	 * @return Array $ resultado de la consulta
	 *
	 */
	public function queryByUsucreaId($value){
		$sql = 'SELECT * FROM lealtad_interna WHERE usucrea_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

	/**
	 * Obtener todos los registros donde se encuentre que
	 * la columna usumodif_id sea igual al valor pasado como parámetro
	 *
	 * @param String $value usumodif_id requerido
	 *
	 * @return Array $ resultado de la consulta
	 *
	 */
	public function queryByUsumodifId($value){
		$sql = 'SELECT * FROM lealtad_interna WHERE usumodif_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}






	/**
	 * Realizar una consulta en la tabla de LealtadInterna 'LealtadInterna'
	 * de una manera personalizada
	 *
	 * @param String $select campos de consulta
	 * @param String $sidx columna para puntosar
	 * @param String $sord puntos los datos asc | desc
	 * @param String $start inicio de la consulta
	 * @param String $limit limite de la consulta
	 * @param String $filters condiciones de la consulta
	 * @param boolean $searchOn utilizar los filtros o no
	 *
	 * @return Array resultado de la consulta
	 *
	 */
	public function queryLealtadsCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn)
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
						$fieldOperation = " NOT IN (".$fieldData.")";
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
					$where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
				}
				else
				{
					$where = "";
				}
			}

		}



		$sql = "SELECT count(*) count FROM lealtad_interna INNER JOIN mandante ON (lealtad_interna.mandante = mandante.mandante) " . $where;


		$sqlQuery = new SqlQuery($sql);

		$count = $this->execute2($sqlQuery);
		$sql = "SELECT ".$select." FROM lealtad_interna INNER JOIN mandante ON (lealtad_interna.mandante = mandante.mandante)  " . $where ." " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

		$sqlQuery = new SqlQuery($sql);

		$result = $this->execute2($sqlQuery);

		$json = '{ "count" : '.json_encode($count). ', "data" : '.json_encode($result).'}';

		return  $json;
	}


	/**
	 * Ejecutar una consulta sql como update
	 *
	 *
	 * @param String $sql consulta sql
	 *
	 * @return Array $ resultado de la ejecución
	 *
	 * @access protected
	 *
	 */
	public function queryUpdate($sql){
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}











	/**
	 * Crear y devolver un objeto del tipo LealtadInterna
	 * con los valores de una consulta sql
	 *
	 *
	 * @param Arreglo $row arreglo asociativo
	 *
	 * @return Objeto $LealtadInterna LealtadInterna
	 *
	 * @access protected
	 *
	 */
	protected function readRow($row){
		$LealtadInterna = new LealtadInterna();

		$LealtadInterna->lealtadId = $row['lealtad_id'];
		$LealtadInterna->fechaInicio = $row['fecha_inicio'];
		$LealtadInterna->fechaFin = $row['fecha_fin'];
		$LealtadInterna->descripcion = $row['descripcion'];
		$LealtadInterna->nombre = $row['nombre'];
		$LealtadInterna->tipo = $row['tipo'];
		$LealtadInterna->estado = $row['estado'];
		$LealtadInterna->mandante = $row['mandante'];
		$LealtadInterna->usucreaId = $row['usucrea_id'];
		$LealtadInterna->fechaCrea = $row['fecha_crea'];
		$LealtadInterna->usumodifId = $row['usumodif_id'];
		$LealtadInterna->fechaModif = $row['fecha_modif'];
		$LealtadInterna->puntos = $row['puntos'];
		$LealtadInterna->orden = $row['orden'];
		$LealtadInterna->condicional = $row['condicional'];
		$LealtadInterna->cupoActual = $row['cupo_actual'];
		$LealtadInterna->cupoMaximo = $row['cupo_maximo'];
		$LealtadInterna->cantidadLealtad = $row['cantidad_lealtad'];
		$LealtadInterna->maximoLealtad = $row['maximo_lealtad'];
		$LealtadInterna->codigo = $row['codigo'];
		$LealtadInterna->reglas = $row['reglas'];
		$LealtadInterna->bonoId = $row['bono_id'];
		$LealtadInterna->tipoPremio = $row['tipo_premio'];
		$LealtadInterna->puntoventaPropio = $row['puntoventa_propio'];
		$LealtadInterna->mSubject = $row['m_subject'];
		$LealtadInterna->mBody = $row['m_body'];


		return $LealtadInterna;
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
	protected function getList($sqlQuery){
		$tab = QueryExecutor::execute($this->transaction,$sqlQuery);
		$ret = array();
		for($i=0;$i<oldCount($tab);$i++){
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
	protected function getRow($sqlQuery){
		$tab = QueryExecutor::execute($this->transaction,$sqlQuery);
		if(oldCount($tab)==0){
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
	protected function execute($sqlQuery){
		return QueryExecutor::execute($this->transaction,$sqlQuery);
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
	protected function executeUpdate($sqlQuery){
		return QueryExecutor::executeUpdate($this->transaction,$sqlQuery);
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
	protected function querySingleResult($sqlQuery){
		return QueryExecutor::queryForString($this->transaction,$sqlQuery);
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
	protected function executeInsert($sqlQuery){
		return QueryExecutor::executeInsert($this->transaction,$sqlQuery);
	}
}
?>
