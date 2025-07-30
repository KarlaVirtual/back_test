<?php namespace Backend\mysql;
use Backend\dao\PaisMandanteDAO;
use Backend\dto\PaisMandante;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
/**
 * Clase 'PaisMandanteMySqlDAO'
 *
 * Esta clase provee las consultas del modelo o tabla 'PaisMandante'
 *
 * Ejemplo de uso:
 * $PaisMandanteMySqlDAO = new PaisMandanteMySqlDAO();
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class PaisMandanteMySqlDAO implements PaisMandanteDAO
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
	 * @param String $id llave primaria
	 *
	 * @return Array resultado de la consulta
	 *
	 */
	public function load($id)
	{
		$sql = 'SELECT * FROM pais_mandante WHERE paismandante_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($id);
		return $this->getRow($sqlQuery);
	}

	/**
	 * Obtener el registro condicionado por el mandante y el paisId
	 *
	 * @param String $id llave primaria
	 *
	 * @return Array resultado de la consulta
	 *
	 */
	public function loadByMandanteAndPaisId($mandante,$paisId)
	{
		$sql = 'SELECT * FROM pais_mandante WHERE mandante = ? AND pais_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($mandante);
		$sqlQuery->set($paisId);
		return $this->getRow($sqlQuery);
	}
	public function loadByMandante($mandante)
	{
		$sql = 'SELECT * FROM pais_mandante WHERE mandante = ? AND estado="A" ';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($mandante);
		return $this->getList($sqlQuery);
	}
	/**
	 * Obtener todos los registros de la base datos
	 *
	 * @return Array resultado de la consulta
	 *
	 */
	public function queryAll()
	{
		$sql = 'SELECT * FROM pais_mandante';
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
		$sql = 'SELECT * FROM pais_mandante ORDER BY ' . $orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}

	/**
	 * Eliminar todos los registros condicionados
	 * por la llave primaria
	 *
	 * @param String $paismandante_id llave primaria
	 *
	 * @return boolean $ resultado de la consulta
	 *
	 */
	public function delete($paismandante_id)
	{
		$sql = 'DELETE FROM pais_mandante WHERE paismandante_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($paismandante_id);
		return $this->executeUpdate($sqlQuery);
	}


	/**
	 * Insertar un registro en la base de datos
	 *
	 * @param Object pais pais
	 *
	 * @return String $id resultado de la consulta
	 *
	 */
	public function insert($pais)
	{
		$sql = 'INSERT INTO pais_mandante (mandante, pais_id, estado, fecha_crea, fecha_modif, usucrea_id, usumodif_id, trm_nio, trm_mxn, trm_pen, trm_brl, trm_clp, trm_crc, trm_usd, trm_gtq, trm_gyd, trm_jmd, trm_ves, moneda, base_url, email_noreply) VALUES (?, ?, ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';

		$sqlQuery = new SqlQuery($sql);

		$sqlQuery->set($pais->mandante);
		$sqlQuery->set($pais->paisId);
		$sqlQuery->set($pais->estado);

		if($pais->fechaCrea == ''){
			$sqlQuery->set(date("Y-m-d H:i:s"));
		}else{
			$sqlQuery->set($pais->fechaCrea);
		}


		if($pais->fechaModif == ''){
			$sqlQuery->set(date("Y-m-d H:i:s"));
		}else{
			$sqlQuery->set($pais->fechaModif);
		}

		$sqlQuery->setNumber($pais->usucreaId);
		$sqlQuery->setNumber($pais->usumodifId);

		if($pais->trmNio == ''){
			$sqlQuery->setSIN('null');
		}else{
			$sqlQuery->set($pais->trmNio);
		}

		if($pais->trmMxn == ''){
			$sqlQuery->setSIN('null');
		}else{
			$sqlQuery->set($pais->trmMxn);
		}

		if($pais->trmPen == ''){
			$sqlQuery->setSIN('null');
		}else{
			$sqlQuery->set($pais->trmPen);
		}

		if($pais->trmBrl == ''){
			$sqlQuery->setSIN('null');
		}else{
			$sqlQuery->set($pais->trmBrl);
		}

		if($pais->trmClp == ''){
			$sqlQuery->setSIN('null');
		}else{
			$sqlQuery->set($pais->trmClp);
		}

		if($pais->trmCrc == ''){
			$sqlQuery->setSIN('null');
		}else{
			$sqlQuery->set($pais->trmCrc);
		}

		if($pais->trmUsd == ''){
			$sqlQuery->setSIN('null');
		}else{
			$sqlQuery->set($pais->trmUsd);
		}

		if($pais->trmGtq == ''){
			$sqlQuery->setSIN('null');
		}else{
			$sqlQuery->set($pais->trmGtq);
		}

		if($pais->trmGyd == ''){
			$sqlQuery->setSIN('null');
		}else{
			$sqlQuery->set($pais->trmGyd);
		}

		if($pais->trmJmd == ''){
			$sqlQuery->setSIN('null');
		}else{
			$sqlQuery->set($pais->trmJmd);
		}

		if($pais->trmVes == ''){
			$sqlQuery->setSIN('null');
		}else{
			$sqlQuery->set($pais->trmVes);
		}

		if(empty($pais->moneda)) $sqlQuery->setSIN('null');
		else $sqlQuery->set($pais->moneda);

		if(empty($pais->baseUrl)) $sqlQuery->setSIN('null');
		else $sqlQuery->set($pais->baseUrl);

		if(empty($pais->emailNoreply)) $sqlQuery->setSIN('null');
		else $sqlQuery->set($pais->emailNoreply);

		$id = $this->executeInsert($sqlQuery);
		$pais->paismandanteId = $id;
		return $id;
	}

	/**
	 * Editar un registro en la base de datos
	 *
	 * @param Object pais pais
	 *
	 * @return boolean $ resultado de la consulta
	 *
	 */
	public function update($paisMandate)
	{

		$sql = 'UPDATE pais_mandante SET mandante = ?, pais_id = ?, estado = ?, fecha_crea= ?, fecha_modif= ?, 
                           usucrea_id= ?, usumodif_id= ?, trm_nio= ?, trm_mxn= ?, trm_pen= ?, trm_brl= ?, 
                           trm_clp= ?, trm_crc= ?, trm_usd= ?, trm_gtq= ?, trm_gyd= ?, trm_jmd= ?, trm_ves= ?, base_url= ?, email_noreply= ? WHERE paismandante_id = ?';
		$sqlQuery = new SqlQuery($sql);

		$sqlQuery->set($paisMandate->mandante);
		$sqlQuery->set($paisMandate->paisId);
		$sqlQuery->set($paisMandate->estado);

		if($paisMandate->fechaCrea == ''){
			$sqlQuery->set(date("Y-m-d H:i:s"));
		}else{
			$sqlQuery->set($paisMandate->fechaCrea);
		}


		if($paisMandate->fechaModif == ''){
			$sqlQuery->set(date("Y-m-d H:i:s"));
		}else{
			$sqlQuery->set($paisMandate->fechaModif);
		}

		$sqlQuery->setNumber($paisMandate->usucreaId);
		$sqlQuery->setNumber($paisMandate->usumodifId);
		if($paisMandate->trmNio == ''){
			$sqlQuery->setSIN('null');
		}else{
			$sqlQuery->set($paisMandate->trmNio);
		}

		if($paisMandate->trmMxn == ''){
			$sqlQuery->setSIN('null');
		}else{
			$sqlQuery->set($paisMandate->trmMxn);
		}

		if($paisMandate->trmPen == ''){
			$sqlQuery->setSIN('null');
		}else{
			$sqlQuery->set($paisMandate->trmPen);
		}

		if($paisMandate->trmBrl == ''){
			$sqlQuery->setSIN('null');
		}else{
			$sqlQuery->set($paisMandate->trmBrl);
		}

		if($paisMandate->trmClp == ''){
			$sqlQuery->setSIN('null');
		}else{
			$sqlQuery->set($paisMandate->trmClp);
		}

		if($paisMandate->trmCrc == ''){
			$sqlQuery->setSIN('null');
		}else{
			$sqlQuery->set($paisMandate->trmCrc);
		}

		if($paisMandate->trmUsd == ''){
			$sqlQuery->setSIN('null');
		}else{
			$sqlQuery->set($paisMandate->trmUsd);
		}

		if($paisMandate->trmGtq == ''){
			$sqlQuery->setSIN('null');
		}else{
			$sqlQuery->set($paisMandate->trmGtq);
		}

		if($paisMandate->trmGyd == ''){
			$sqlQuery->setSIN('null');
		}else{
			$sqlQuery->set($paisMandate->trmGyd);
		}

		if($paisMandate->trmJmd == ''){
			$sqlQuery->setSIN('null');
		}else{
			$sqlQuery->set($paisMandate->trmJmd);
		}

		if($paisMandate->trmVes == ''){
			$sqlQuery->setSIN('null');
		}else{
			$sqlQuery->set($paisMandate->trmVes);
		}

		if(empty($paisMandate->baseUrl)) $sqlQuery->setSIN('null');
		else $sqlQuery->set($paisMandate->baseUrl);

		if(empty($paisMandate->emailNoreply)) $sqlQuery->setSIN('null');
		else $sqlQuery->set($paisMandate->emailNoreply);

		$sqlQuery->set($paisMandate->paismandanteId);
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
		$sql = 'DELETE FROM pais_mandante';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}






	/**
	 * Obtener todos los registros donde se encuentre que
	 * la columna mandante sea igual al valor pasado como parámetro
	 *
	 * @param String $value mandante requerido
	 *
	 * @return Array resultado de la consulta
	 *
	 */
	public function queryByIso($value)
	{
		$sql = 'SELECT * FROM pais_mandante WHERE mandante = ?';
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
	public function queryByPaisMandanteNom($value)
	{
		$sql = 'SELECT * FROM pais_mandante WHERE pais_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
	 * Obtener todos los registros donde se encuentre que
	 * la columna moneda sea igual al valor pasado como parámetro
	 *
	 * @param String $value moneda requerido
	 *
	 * @return Array resultado de la consulta
	 *
	 */
	public function queryByMoneda($value)
	{
		$sql = 'SELECT * FROM pais_mandante WHERE moneda = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
	 * Obtener todos los registros donde se encuentre que
	 * la columna estado sea igual al valor pasado como parámetro
	 *
	 * @param String $value estado requerido
	 *
	 * @return Array resultado de la consulta
	 *
	 */
	public function queryByEstado($value)
	{
		$sql = 'SELECT * FROM pais_mandante WHERE estado = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
	 * Obtener todos los registros donde se encuentre que
	 * la columna utc sea igual al valor pasado como parámetro
	 *
	 * @param String $value utc requerido
	 *
	 * @return Array resultado de la consulta
	 *
	 */
	public function queryByUtc($value)
	{
		$sql = 'SELECT * FROM pais_mandante WHERE utc = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
	 * Obtener todos los registros donde se encuentre que
	 * la columna idioma sea igual al valor pasado como parámetro
	 *
	 * @param String $value idioma requerido
	 *
	 * @return Array resultado de la consulta
	 *
	 */
	public function queryByIdioma($value)
	{
		$sql = 'SELECT * FROM pais_mandante WHERE idioma = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
	 * Obtener todos los registros donde se encuentre que
	 * la columna req_cheque sea igual al valor pasado como parámetro
	 *
	 * @param String $value req_cheque requerido
	 *
	 * @return Array resultado de la consulta
	 *
	 */
	public function queryByReqCheque($value)
	{
		$sql = 'SELECT * FROM pais_mandante WHERE req_cheque = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
	 * Eliminar todos los registros donde se encuentre que
	 * la columna estado sea igual al valor pasado como parámetro
	 *
	 * @param String $value estado requerido
	 *
	 * @return boolean resultado de la ejecución
	 *
	 */
	public function deleteByEstado($value)
	{
		$sql = 'DELETE FROM pais_mandante WHERE estado = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}



	/**
	 * Crear y devolver un objeto del tipo PaisMandante
	 * con los valores de una consulta sql
	 *
	 *
	 * @param Arreglo $row arreglo asociativo
	 *
	 * @return Object $PaisMandante PaisMandante
	 *
	 * @access protected
	 *
	 */
	protected function readRow($row)
	{
		$pais = new PaisMandante();

		$pais->paismandanteId = $row['paismandante_id'];
		$pais->mandante = $row['mandante'];
		$pais->paisId = $row['pais_id'];
		$pais->estado = $row['estado'];
		$pais->fechaCrea = $row['fecha_crea'];
		$pais->fechaModif = $row['fecha_modif'];
		$pais->usucreaId = $row['usucrea_id'];
		$pais->usumodifId = $row['usumodif_id'];
		$pais->trmNio = $row['trm_nio'];
		$pais->trmMxn = $row['trm_mxn'];
		$pais->trmPen = $row['trm_pen'];
		$pais->trmBrl = $row['trm_brl'];
		$pais->trmClp = $row['trm_clp'];
		$pais->trmCrc = $row['trm_crc'];
		$pais->trmUsd = $row['trm_usd'];
		$pais->trmGtq = $row['trm_gtq'];
		$pais->trmGyd = $row['trm_gyd'];
		$pais->trmJmd = $row['trm_jmd'];
		$pais->trmVes = $row['trm_ves'];

		$pais->skinItainment = $row['skin_itainment'];
		$pais->urlSkinitainment = $row['url_skinitainment'];
		$pais->moneda = $row['moneda'];
		$pais->baseUrl = $row['base_url'];
		$pais->emailNoreply = $row['email_noreply'];

		return $pais;
	}


	/**
	 * Realizar una consulta personalizada en la tabla pais_mandante
	 *
	 * @param string $select campos de consulta
	 * @param string $sidx columna para ordenar
	 * @param string $sord orden de los datos asc | desc
	 * @param int $start inicio de la consulta
	 * @param int $limit límite de la consulta
	 * @param string $filters condiciones de la consulta
	 * @param boolean $searchOn utilizar los filtros o no
	 *
	 * @return string JSON con el conteo y los datos de la consulta
	 */

	public function queryPaisMandantesCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn)
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


		$sql = 'SELECT count(*) count  from pais_mandante INNER JOIN  pais ON (pais_mandante.pais_id = pais.pais_id) ' . $where ;


		$sqlQuery = new SqlQuery($sql);

		$count = $this->execute2($sqlQuery);


		$sql = 'SELECT  '.$select.' from pais_mandante INNER JOIN  pais ON (pais_mandante.pais_id = pais.pais_id) ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;


		$sqlQuery = new SqlQuery($sql);

		$result = $this->execute2($sqlQuery);

		$json = '{ "count" : '.json_encode($count). ', "data" : '.json_encode($result).'}';

		return  $json;
	}


	/**
	 * Realizar una consulta personalizada en la tabla pais\_mandante
	 *
	 * @param string $select campos de consulta
	 * @param string $sidx columna para ordenar
	 * @param string $sord orden de los datos asc | desc
	 * @param int $start inicio de la consulta
	 * @param int $limit límite de la consulta
	 * @param string $filters condiciones de la consulta
	 * @param boolean $searchOn utilizar los filtros o no
	 *
	 * @return string JSON con el conteo y los datos de la consulta
	 */

	public function queryPaisMandantesCustom2($select,$sidx,$sord,$start,$limit,$filters,$searchOn)
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


		$sql = 'SELECT count(*) count  from pais_mandante INNER JOIN  pais ON (pais_mandante.pais_id = pais.pais_id) INNER JOIN  pais_moneda ON (pais_moneda.pais_id = pais.pais_id) ' . $where ;


		$sqlQuery = new SqlQuery($sql);

		$count = $this->execute2($sqlQuery);


		$sql = 'SELECT  '.$select.' from pais_mandante INNER JOIN  pais ON (pais_mandante.pais_id = pais.pais_id) INNER JOIN  pais_moneda ON (pais_moneda.pais_id = pais.pais_id) ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;


		$sqlQuery = new SqlQuery($sql);

		$result = $this->execute2($sqlQuery);

		$json = '{ "count" : '.json_encode($count). ', "data" : '.json_encode($result).'}';

		return  $json;
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
