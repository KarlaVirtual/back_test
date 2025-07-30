<?php namespace Backend\mysql;
use Backend\dto\JackpotInterno;
use Backend\dao\JackpotInternoDAO;
use Backend\dto\LealtadInterna;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Exception;
/**
 * Clase 'JackpotInternoMySqlDAO'
 *
 * Esta clase provee las consultas del modelo o tabla 'LealtadInterna'
 *
 * Ejemplo de uso:
 * $JackpotInternoMySqlDAO = new JackpotInternoMySqlDAO();
 *
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class JackpotInternoMySqlDAO implements JackpotInternoDAO {


	/**
	 * Atributo Transaction transacción
	 *
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
		$sql = 'SELECT * FROM jackpot_interno WHERE jackpot_id = ?';
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
		$sql = 'SELECT * FROM jackpot_interno';
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
		$sql = 'SELECT * FROM jackpot_interno ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}


	/**
	 * Eliminar todos los registros condicionados
	 * por la llave primaria
	 *
	 * @param String $jackpot_id llave primaria
	 *
	 * @return boolean $ resultado de la consulta
	 *
	 */
	public function delete($jackpot_id){
		$sql = 'DELETE FROM jackpot_interno WHERE jackpot_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($jackpot_id);
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
	public function insert($JackpotInterno){

		$sql = 'INSERT INTO jackpot_interno (jackpot_padre, fecha_inicio, fecha_fin, descripcion,tipo, reinicio, nombre, estado, mandante, pais_id,valor_actual,usucrea_id, usumodif_id,orden, valor_base,valor_maximo,minimo_ticket,maximo_ticket,cantidad_apuesta,cantidad_apuestamax,porcentaje_apuestas,imagen,imagen2,gif, gif2,reglas,video_mobile, video_desktop, notas, informacion) VALUES (?,?, ?, ?, ?, ?, ?, ?, ?, ?,?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?,?,?,?,?, ?, ?, ?, ?)';


		$sqlQuery = new SqlQuery($sql);

		$sqlQuery->setNumber($JackpotInterno->jackpotPadre);
		$sqlQuery->set($JackpotInterno->fechaInicio);
		if (empty($JackpotInterno->fechaFin)) {
			$sqlQuery->setSIN('null');
		}
		else {
			$sqlQuery->set($JackpotInterno->fechaFin);
		}
		$sqlQuery->set($JackpotInterno->descripcion);
		$sqlQuery->set($JackpotInterno->tipo);
		$sqlQuery->set($JackpotInterno->reinicio);
		$sqlQuery->set($JackpotInterno->nombre);
		$sqlQuery->set($JackpotInterno->estado);
		$sqlQuery->setNumber($JackpotInterno->mandante);
		$sqlQuery->setNumber($JackpotInterno->paisId);
		$sqlQuery->setNumber($JackpotInterno->valorActual);
		$sqlQuery->setNumber($JackpotInterno->usucreaId);
		$sqlQuery->setNumber($JackpotInterno->usumodifId);
		$sqlQuery->set($JackpotInterno->orden);
		$sqlQuery->setNumber($JackpotInterno->valorBase);
		$sqlQuery->setNumber($JackpotInterno->valorMaximo);
		$sqlQuery->setNumber($JackpotInterno->minimoTicket);
		$sqlQuery->setNumber($JackpotInterno->maximoTicket);
		if($JackpotInterno->cantidadApuesta ==''){
			$JackpotInterno->cantidadApuesta='0';
		}
		$sqlQuery->setNumber($JackpotInterno->cantidadApuesta);
		$sqlQuery->setNumber($JackpotInterno->cantidadApuestamax);
		$sqlQuery->setNumber($JackpotInterno->porcentajeApuestas);
		$sqlQuery->set($JackpotInterno->imagen);
		$sqlQuery->set($JackpotInterno->imagen2);
		$sqlQuery->set($JackpotInterno->gif);
		$sqlQuery->set($JackpotInterno->gif2);
		$sqlQuery->set($JackpotInterno->reglas);
		$sqlQuery->set($JackpotInterno->videoMobile);
		$sqlQuery->set($JackpotInterno->videoDesktop);
		if (empty($JackpotInterno->notas)) {
			$sqlQuery->setSIN('null');
		}
		else {
			$sqlQuery->set($JackpotInterno->notas);
		}
		if (empty($JackpotInterno->informacion)) {
			$sqlQuery->setSIN('null');
		}
		else {
			$sqlQuery->set($JackpotInterno->informacion);
		}

		$id = $this->executeInsert($sqlQuery);
		$JackpotInterno->jackpotId = $id;
		return $id;
	}

	/**
	 * Función utilizada para obtener la sentencia de actualización de un jackpot,
	 * se ubica en una función independiente para centralizar los múltiples updates existentes para un jackpot.
	 *
	 * @param  array $paramsToExclude  Colección de columnas que serán excluidas de la sentencia de update.
	 * @param  array $onlyUpdateParams Colección de columnas que serán incluidas en la sentencia de update.
	 *
	 * @return string Sentencia de actualización
	 */
	protected function getUpdateSentence (array $paramsToExclude = [], array $onlyUpdateParams = [])
	{
		/*Variable con todos los parámetros existentes en la tabla JackpotInterno, si se agregan columnas nuevas agregarlas aquí también*/
		$jackpotInternoParams = [
			"jackpot_padre" => "?",
			"fecha_inicio" => "?",
			"fecha_fin" => "?",
			"descripcion" => "?",
			"tipo" => "?",
			"reinicio" => "?",
			"nombre" => "?",
			"estado" => "?",
			"mandante" => "?",
			"pais_id" => "?",
			"valor_actual" => "?",
			"usucrea_id" => "?",
			"usumodif_id" => "?",
			"orden" => "?",
			"valor_base" => "?",
			"valor_maximo" => "?",
			"minimo_ticket" => "?",
			"maximo_ticket" => "?",
			"cantidad_apuesta" => "?",
			"cantidad_apuestamax" => "?",
			"porcentaje_apuestas" => "?",
			"imagen" => "?",
			"imagen2" => "?",
			"gif" => "?",
			"gif2" => "?",
			"reglas" => "?",
			"video_mobile" => "?",
			"video_desktop" => "?",
			"notas" => "?",
			"informacion" => "?"
		];

		/*Sentencia básica de actualización*/
		$updateSentence = 'UPDATE jackpot_interno SET #ParamsToUpdate#
		WHERE 
		jackpot_id = ?';

		/*asignación de parámetros que se actualizarán / Exclusión de parámetros no deseados en el update*/
		$paramsToUpdateSentence = "";
		foreach ($jackpotInternoParams as $param => $value) {
			if (in_array($param, $paramsToExclude)) continue;
			if (!empty($onlyUpdateParams) && !in_array($param, $onlyUpdateParams)) continue;

			$paramsToUpdateSentence .= (!empty($paramsToUpdateSentence) ? ", " : "") . $param . " = " . $value;
		}
		$updateSentence = str_replace('#ParamsToUpdate#', $paramsToUpdateSentence, $updateSentence);

		return $updateSentence;
	}

	/**
	 * Editar un registro en la base de datos.
	 *
	 * @param object $JackpotInterno Jackpot interno a actualizar.
	 *
	 * @return boolean $resultado de la consulta.
	 */
	public function update($JackpotInterno){
		$sql = $this->getUpdateSentence();
		$sqlQuery = new SqlQuery($sql);

		$sqlQuery->setNumber($JackpotInterno->jackpotPadre ?: 0);
		$sqlQuery->set($JackpotInterno->fechaInicio);
		if (empty($JackpotInterno->fechaFin)) {
			$sqlQuery->setSIN('null');
		}
		else {
			$sqlQuery->set($JackpotInterno->fechaFin);
		}
		$sqlQuery->set($JackpotInterno->descripcion);
		$sqlQuery->set($JackpotInterno->tipo);
		$sqlQuery->setNumber($JackpotInterno->reinicio);
		$sqlQuery->set($JackpotInterno->nombre);
		$sqlQuery->set($JackpotInterno->estado);
		$sqlQuery->setNumber($JackpotInterno->mandante);
		$sqlQuery->setNumber($JackpotInterno->paisId);
		$sqlQuery->set($JackpotInterno->valorActual);
		$sqlQuery->setNumber($JackpotInterno->usucreaId);
		$sqlQuery->setNumber($JackpotInterno->usumodifId);
		$sqlQuery->set($JackpotInterno->orden);
		$sqlQuery->set($JackpotInterno->valorBase);
		$sqlQuery->set($JackpotInterno->valorMaximo ?: 0);
		$sqlQuery->set($JackpotInterno->minimoTicket ?: 0);
		$sqlQuery->set($JackpotInterno->maximoTicket ?: 0);
		if($JackpotInterno->cantidadApuesta ==''){
			$JackpotInterno->cantidadApuesta='0';
		}
		$sqlQuery->set($JackpotInterno->cantidadApuesta);
		$sqlQuery->set($JackpotInterno->cantidadApuestamax ?: 0);
		$sqlQuery->set($JackpotInterno->porcentajeApuestas ?: 0);
		$sqlQuery->set($JackpotInterno->imagen);
		$sqlQuery->set($JackpotInterno->imagen2);
		$sqlQuery->set($JackpotInterno->gif);
		$sqlQuery->set($JackpotInterno->gif2);

		if($JackpotInterno->reglas != ""){
			$JackpotInterno->reglas = str_replace("'","\'",$JackpotInterno->reglas);
		}

		$sqlQuery->set($JackpotInterno->reglas);
		$sqlQuery->set($JackpotInterno->videoMobile);
		$sqlQuery->set($JackpotInterno->videoDesktop);
		if (empty($JackpotInterno->notas)) {
			$sqlQuery->setSIN('null');
		}
		else {
			$sqlQuery->set($JackpotInterno->notas);
		}
		if (empty($JackpotInterno->informacion)) {
			$sqlQuery->setSIN('null');
		}
		else {
			$sqlQuery->set($JackpotInterno->informacion);
		}

		$sqlQuery->setNumber($JackpotInterno->jackpotId);
		return $this->executeUpdate($sqlQuery);
	}


	/**
	 * Actualización de jackpot evitando los valores transaccionales
	 * Este metodo de actualización no actualiza los campos utilizados activamente en
	 * el proceso transaccional de un jackpot, esto como una medida de prevención a incidentes.
	 * (Campos actuales no actualizados:
	 * fecha_inicio,
	 * fecha_fin,
	 * reinicio,
	 * estado,
	 * valor_actual,
	 * orden,
	 * valor_base,
	 * valor_maximo,
	 * minimo_ticket,
	 * maximo_ticket,
	 * cantidad_apuesta,
	 * cantidad_apuestamax,
	 * porcentaje_apuestas,
	 * notas)
	 *
	 * @param JackpotInterno $JackpotInterno Objeto JackpotInterno con los datos a actualizar.
	 *
	 * @return int Total filas afectadas.
	 */
	public function notTransactionalUpdate(JackpotInterno $JackpotInterno) {
		/*Colección de parámetros transaccionales excluídos --NO eliminar parámetros de esta lista*/
 		$attributesToExclude = [
			'fecha_inicio',
			'fecha_fin',
			'reinicio',
			'estado',
			'valor_actual',
			'orden',
			'valor_base',
			'valor_maximo',
			'minimo_ticket',
			'maximo_ticket',
			'cantidad_apuesta',
			'cantidad_apuestamax',
			'porcentaje_apuestas',
			'notas'
		];


		/*Colección parámetros que se actualizan mediante este metodo*/
		$attributesToInclude = [
			"imagen",
			"imagen2",
			"gif",
			"reglas",
			"video_mobile",
			"video_desktop",
			"informacion"
		];

		$sql = $this->getUpdateSentence($attributesToExclude, $attributesToInclude);
		$sqlQuery = new SqlQuery($sql);

		$sqlQuery->set($JackpotInterno->imagen);
		$sqlQuery->set($JackpotInterno->imagen2);
		$sqlQuery->set($JackpotInterno->gif);
		if($JackpotInterno->reglas != ""){
			$JackpotInterno->reglas = str_replace("'","\'",$JackpotInterno->reglas);
		}
		$sqlQuery->set($JackpotInterno->reglas);
		$sqlQuery->set($JackpotInterno->videoMobile);
		$sqlQuery->set($JackpotInterno->videoDesktop);
		if (empty($JackpotInterno->informacion)) {
			$sqlQuery->setSIN('null');
		}
		else {
			$sqlQuery->set($JackpotInterno->informacion);
		}

		$sqlQuery->setNumber($JackpotInterno->jackpotId);
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
		$sql = 'DELETE FROM jackpot_interno';
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
		$sql = 'SELECT * FROM jackpot_interno WHERE fecha_inicio = ?';
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
		$sql = 'SELECT * FROM jackpot_interno WHERE fecha_fin = ?';
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
		$sql = 'SELECT * FROM jackpot_interno WHERE descripcion = ?';
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
		$sql = 'SELECT * FROM jackpot_interno WHERE tipo = ?';
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
		$sql = 'SELECT * FROM jackpot_interno WHERE estado = ?';
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
		$sql = 'SELECT * FROM jackpot_interno WHERE fecha_modif = ?';
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
		$sql = 'SELECT * FROM jackpot_interno WHERE fecha_crea = ?';
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
		$sql = 'SELECT * FROM jackpot_interno WHERE mandante = ?';
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
		$sql = 'SELECT * FROM jackpot_interno WHERE usucrea_id = ?';
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
		$sql = 'SELECT * FROM jackpot_interno WHERE usumodif_id = ?';
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
	public function queryJackpotCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn, $joins = [])
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
					case "gefield":
						$fieldOperation = " >= ".$fieldData."";
						break;
					case "nu":
						$fieldOperation = " = ''";
						break;
					case "iu":
						$fieldOperation = " IS NULL";
						break;
					case "dn":
						$fieldOperation = "IS NOT NULL";
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
				if (count($whereArray)>0)
				{
					$where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . ($fieldOperation);
				}
				else
				{
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


		$sql = "SELECT count(*) count FROM jackpot_interno INNER JOIN mandante ON (jackpot_interno.mandante = mandante.mandante) INNER JOIN jackpot_detalle ON (jackpot_interno.jackpot_id = jackpot_detalle.jackpot_id) " . $strJoins . $where;


		$sqlQuery = new SqlQuery($sql);

		$count = $this->execute2($sqlQuery);
		$sql = "SELECT ".$select." FROM jackpot_interno INNER JOIN mandante ON (jackpot_interno.mandante = mandante.mandante) INNER JOIN jackpot_detalle ON (jackpot_interno.jackpot_id = jackpot_detalle.jackpot_id)  " . $strJoins . $where ." " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

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
	 * @return Objeto $JackpotInterno LealtadInterna
	 *
	 * @access protected
	 *
	 */
	protected function readRow($row){
		$JackpotInterno = new JackpotInterno();

		$JackpotInterno->jackpotId = $row['jackpot_id'];
		$JackpotInterno->jackpotPadre = $row['jackpot_padre'];
		$JackpotInterno->fechaInicio = $row['fecha_inicio'];
		$JackpotInterno->fechaFin = $row['fecha_fin'];
		$JackpotInterno->descripcion = $row['descripcion'];
		$JackpotInterno->tipo = $row['tipo'];
		$JackpotInterno->reinicio = $row['reinicio'];
		$JackpotInterno->nombre = $row['nombre'];
		$JackpotInterno->estado = $row['estado'];
		$JackpotInterno->mandante = $row['mandante'];
		$JackpotInterno->paisId = $row['pais_id'];
		$JackpotInterno->valorActual = $row['valor_actual'];
		$JackpotInterno->usucreaId = $row['usucrea_id'];
		$JackpotInterno->fechaCrea = $row['fecha_crea'];
		$JackpotInterno->usumodifId = $row['usumodif_id'];
		$JackpotInterno->fechaModif = $row['fecha_modif'];
		$JackpotInterno->orden = $row['orden'];
		$JackpotInterno->valorBase = $row['valor_base'];
		$JackpotInterno->valorMaximo = $row['valor_maximo'];
		$JackpotInterno->minimoTicket = $row['minimo_ticket'];
		$JackpotInterno->maximoTicket = $row['maximo_ticket'];
		$JackpotInterno->cantidadApuesta = $row['cantidad_apuesta'];
		$JackpotInterno->cantidadApuestamax = $row['cantidad_apuestamax'];
		$JackpotInterno->porcentajeApuestas = $row['porcentaje_apuestas'];
		$JackpotInterno->imagen = $row['imagen'];
		$JackpotInterno->imagen2 = $row['imagen2'];
		$JackpotInterno->gif = $row['gif'];
		$JackpotInterno->gif2 = $row['gif2'];
		$JackpotInterno->reglas = $row['reglas'];
		$JackpotInterno->videoMobile = $row['video_mobile'];
		$JackpotInterno->videoDesktop = $row['video_desktop'];
		$JackpotInterno->notas = $row['notas'];
		$JackpotInterno->informacion = $row['informacion'];



		return $JackpotInterno;
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
		for($i=0;$i<count($tab);$i++){
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
		if(count($tab)==0){
			return null;
		}
		return $this->readRow($tab[0]);
	}


	public function updateAmountJackpot($jackpotId,$valor_actual="",$withValidation=false)
	{


		$fields="1=1";
		$where ="";


		if($valor_actual != ""){
			$fields .= ", valor_actual = CASE 
           WHEN valor_actual + $valor_actual  > valor_maximo THEN valor_maximo
           ELSE   valor_actual+'".$valor_actual ." 'END "; //case when si valor actual + variable > valor maximo then valor maximo else

			$fields .= ", valor_maximo = CASE 
           WHEN cantidad_apuesta >= cantidad_apuestamax  THEN valor_actual
           ELSE   valor_maximo END ";


			$fields .= ", cantidad_apuesta=cantidad_apuesta + 1";

			if($withValidation){
				$where .= " AND valor_actual +'".$valor_actual."' >= -0.01 ";
			}
		}


		if($fields != "1=1"){
			$fields = str_replace("1=1,","",$fields);
			$sql = 'UPDATE jackpot_interno SET ' . $fields.' WHERE jackpot_id = ? '.$where;

			$sqlQuery = new SqlQuery($sql);

			$sqlQuery->set($jackpotId);
			return $this->executeUpdate($sqlQuery);
		}
		return false;
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

	public function getJackpotWinners($start,$limit,$filters, $country = null) {

		try {
			$bonoLogAllowedTypes = array_reduce(JackpotInterno::getJackpotTypesForBonoLog(), function ($typesString, $typeItem) {
				$typesString .= ($typesString == null ? "" : ",") . "'{$typeItem}'";
				return $typesString;
			}, null);

			$where = " WHERE ujg.estado = 'I' ";

			$filters = json_decode($filters);
			$whereArray = array();
			$rules = $filters->rules;
			$groupOperation = $filters->groupOp;

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
					case "gefield":
						$fieldOperation = " >= ".$fieldData."";
						break;
					case "nu":
						$fieldOperation = " = ''";
						break;
					case "iu":
						$fieldOperation = " IS NULL";
						break;
					case "dn":
						$fieldOperation = "IS NOT NULL";
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
				if (count($whereArray)>0)
				{
					$where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . ($fieldOperation);
				}
				else
				{
					$where = " WHERE ujg.estado = 'I' ";
				}
			}

			if (!empty($country)){
				$where = $where . " AND EXISTS (
					SELECT 1 
					FROM jackpot_detalle jd2 
					WHERE jd2.jackpot_id = ji.jackpot_id 
					AND jd2.tipo = 'CONDPAISUSER' 
					AND jd2.valor = $country
				)";
			}
			
			$sql = "SELECT COUNT(*) AS total_ganadores FROM (
				SELECT 
				ji.jackpot_id AS 'id_jackpot',
				ji.nombre AS 'nombre',
				ji.fecha_inicio AS 'fecha_inicio',
				bl.fecha_crea   AS 'fecha_caida',
				COALESCE(
					CASE 
						WHEN LEFT(ji.notas, 1) = 'C' THEN tj.transapi_id
						WHEN LEFT(ji.notas, 1) = 'S' THEN tt.ticket_id
					END, 
					'N/A'
				) AS 'numero_apuesta',
				uj.valor_premio AS 'valor_premio',
				jd.moneda AS 'moneda',
				CASE 
					WHEN jd.valor = 0 THEN 'Todos'
					WHEN jd.valor = 1 THEN 'Depositos'
					WHEN jd.valor = 2 THEN 'Retiros'
				END AS 'tipo_saldo',
				CASE 
					WHEN COUNT(DISTINCT ujg.tipo) > 1 THEN 'varios'
					ELSE MAX(ujg.tipo)
				END AS 'verticales',
				uj.usuario_id AS 'usuario_id'
				FROM 
					jackpot_interno ji
				LEFT JOIN usuario_jackpot uj ON ji.jackpot_id = uj.jackpot_id
				LEFT JOIN jackpot_detalle jd ON ji.jackpot_id = jd.jackpot_id AND jd.tipo = 'TIPOSALDO'
				LEFT JOIN usuariojackpot_ganador ujg ON uj.usujackpot_id = ujg.usujackpot_id
				LEFT JOIN transjuego_info tj ON LEFT(ji.notas, 1) = 'C' AND tj.transjuegoinfo_id = SUBSTRING(ji.notas, 3)
				LEFT JOIN it_ticket_enc_info1 tt ON LEFT(ji.notas, 1) = 'S' AND tt.it_ticket2_id = SUBSTRING(ji.notas, 3)
				LEFT JOIN bono_log bl ON (ujg.usujackpot_id = bl.id_externo AND bl.tipo IN ({$bonoLogAllowedTypes})) 
				LEFT JOIN usuario u ON (u.usuario_id = ujg.usuario_id)
				    $where
				GROUP BY ji.jackpot_id, ji.nombre, ji.fecha_inicio, uj.fecha_crea, uj.valor_premio, jd.moneda, jd.valor, tj.transapi_id, tt.ticket_id
				HAVING COUNT(DISTINCT ujg.tipo) <= 1 OR MAX(ujg.tipo) IS NOT NULL
			) AS subconsulta;";

			$sqlQuery = new SqlQuery($sql);

			$count = $this->execute2($sqlQuery);

			$query = "SELECT 
			ji.jackpot_id AS 'id_jackpot',
			ji.nombre AS 'nombre',
			ji.fecha_inicio AS 'fecha_inicio',
			bl.fecha_crea   AS 'fecha_caida',
			COALESCE(
				CASE 
					WHEN LEFT(ji.notas, 1) = 'C' THEN tj.transapi_id
					WHEN LEFT(ji.notas, 1) = 'S' THEN tt.ticket_id
				END, 
				'N/A'
			) AS 'numero_apuesta',
			uj.valor_premio AS 'valor_premio',
			jd.moneda AS 'moneda',
			CASE 
				WHEN jd.valor = 0 THEN 'Todos'
				WHEN jd.valor = 1 THEN 'Depositos'
				WHEN jd.valor = 2 THEN 'Retiros'
			END AS 'tipo_saldo',
			CASE 
				WHEN COUNT(DISTINCT ujg.tipo) > 1 THEN 'Varias'
				WHEN ujg.tipo = 'INCOME_CASINO' THEN 'Casino'
				WHEN ujg.tipo = 'INCOME_SPORTBOOK' THEN 'Deportivas'
				WHEN ujg.tipo = 'INCOME_LIVECASINO' THEN 'Casino en vivo'
				WHEN ujg.tipo = 'INCOME_VIRTUAL' THEN 'Virtuales'
				ELSE MAX(ujg.tipo)
			END AS 'verticales',
			uj.usuario_id AS 'usuario_id'
			FROM 
				jackpot_interno ji
			LEFT JOIN usuario_jackpot uj ON ji.jackpot_id = uj.jackpot_id
			LEFT JOIN jackpot_detalle jd ON ji.jackpot_id = jd.jackpot_id AND jd.tipo = 'TIPOSALDO'
			LEFT JOIN usuariojackpot_ganador ujg ON uj.usujackpot_id = ujg.usujackpot_id
			LEFT JOIN transjuego_info tj ON LEFT(ji.notas, 1) = 'C' AND tj.transjuegoinfo_id = SUBSTRING(ji.notas, 3)
			LEFT JOIN it_ticket_enc_info1 tt ON LEFT(ji.notas, 1) = 'S' AND tt.it_ticket2_id = SUBSTRING(ji.notas, 3)
			LEFT JOIN bono_log bl ON (ujg.usujackpot_id = bl.id_externo AND bl.tipo IN ({$bonoLogAllowedTypes}
)) 
			    LEFT JOIN usuario u ON (u.usuario_id = ujg.usuario_id)
			$where
			GROUP BY ji.jackpot_id, ji.nombre, ji.fecha_inicio, uj.fecha_crea, uj.valor_premio, jd.moneda, jd.valor, tj.transapi_id, tt.ticket_id
			HAVING COUNT(DISTINCT ujg.tipo) <= 1 OR MAX(ujg.tipo) IS NOT NULL
			ORDER BY ji.jackpot_id DESC LIMIT $start, $limit;";

			$sqlQuery = new SqlQuery($query);
			$data = $this->execute2($sqlQuery);
			return ['count' => $count[0]['.total_ganadores'], 'data' => $data];
		} catch (\Throwable $th) {
			throw $th;
		}
	}
}
?>
