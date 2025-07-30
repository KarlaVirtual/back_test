<?php namespace Backend\mysql;
use Backend\dao\PaisDAO;
use Backend\dto\Pais;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
/**
* Clase 'PaisMySqlDAO'
*
* Esta clase provee las consultas del modelo o tabla 'Pais'
*
* Ejemplo de uso:
* $PaisMySqlDAO = new PaisMySqlDAO();
*
*
* @package ninguno
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public
* @see no
*
*/
class PaisMySqlDAO implements PaisDAO
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
		$sql = 'SELECT * FROM pais WHERE pais_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($id);
		return $this->getRow($sqlQuery);
	}

    /**
	 * Get Domain object by primry key
	 *
	 * @param String $id primary key
	 * @return PaisMySql
	 */
	public function loadbyIso($id)
	{
		$sql = 'SELECT * FROM pais WHERE iso = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($id);
		return $this->getRow($sqlQuery);
	}/**
     * Obtener todos los registros de la base datos
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryAll()
	{
		$sql = 'SELECT * FROM pais';
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
		$sql = 'SELECT * FROM pais ORDER BY ' . $orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}

    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $pais_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function delete($pais_id)
	{
		$sql = 'DELETE FROM pais WHERE pais_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($pais_id);
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
		$sql = 'INSERT INTO pais (iso, pais_nom, moneda, estado, utc, idioma, req_cheque, req_doc, codigo_path,prefijo_celular) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);

		$sqlQuery->set($pais->iso);
		$sqlQuery->set($pais->paisNom);
		$sqlQuery->set($pais->moneda);
		$sqlQuery->set($pais->estado);
		$sqlQuery->set($pais->utc);
		$sqlQuery->set($pais->idioma);
		$sqlQuery->set($pais->reqCheque);
		$sqlQuery->set($pais->reqDoc);
        $sqlQuery->set($pais->codigoPath);
        $sqlQuery->set($pais->prefijoCelular);

		$id = $this->executeInsert($sqlQuery);
		$pais->paisId = $id;
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
	public function update($pais)
	{
		$sql = 'UPDATE pais SET iso = ?, pais_nom = ?, moneda = ?, estado = ?, utc = ?, idioma = ?, req_cheque = ?, req_doc = ?, codigo_path = ?, prefijo_celular = ? WHERE pais_id = ?';
		$sqlQuery = new SqlQuery($sql);

		$sqlQuery->set($pais->iso);
		$sqlQuery->set($pais->paisNom);
		$sqlQuery->set($pais->moneda);
		$sqlQuery->set($pais->estado);
		$sqlQuery->set($pais->utc);
		$sqlQuery->set($pais->idioma);
		$sqlQuery->set($pais->reqCheque);
		$sqlQuery->set($pais->reqDoc);
		$sqlQuery->set($pais->codigoPath);
        $sqlQuery->set($pais->prefijoCelular);

		$sqlQuery->set($pais->paisId);
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
		$sql = 'DELETE FROM pais';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}






    /**
     * Obtener todos los registros donde se encuentre que
     * la columna iso sea igual al valor pasado como parámetro
     *
     * @param String $value iso requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByIso($value)
	{
		$sql = 'SELECT * FROM pais WHERE iso = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna pais_nom sea igual al valor pasado como parámetro
     *
     * @param String $value pais_nom requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByPaisNom($value)
	{
		$sql = 'SELECT * FROM pais WHERE pais_nom = ?';
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
		$sql = 'SELECT * FROM pais WHERE moneda = ?';
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
		$sql = 'SELECT * FROM pais WHERE estado = ?';
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
		$sql = 'SELECT * FROM pais WHERE utc = ?';
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
		$sql = 'SELECT * FROM pais WHERE idioma = ?';
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
		$sql = 'SELECT * FROM pais WHERE req_cheque = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna req_doc sea igual al valor pasado como parámetro
     *
     * @param String $value req_doc requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByReqDoc($value)
	{
		$sql = 'SELECT * FROM pais WHERE req_doc = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna codigo_path sea igual al valor pasado como parámetro
     *
     * @param String $value codigo_path requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByCodigoPath($value)
	{
		$sql = 'SELECT * FROM pais WHERE codigo_path = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}





    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna iso sea igual al valor pasado como parámetro
     *
     * @param String $value iso requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByIso($value)
	{
		$sql = 'DELETE FROM pais WHERE iso = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna pais_nom sea igual al valor pasado como parámetro
     *
     * @param String $value pais_nom requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByPaisNom($value)
	{
		$sql = 'DELETE FROM pais WHERE pais_nom = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna moneda sea igual al valor pasado como parámetro
     *
     * @param String $value moneda requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByMoneda($value)
	{
		$sql = 'DELETE FROM pais WHERE moneda = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
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
		$sql = 'DELETE FROM pais WHERE estado = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna utc sea igual al valor pasado como parámetro
     *
     * @param String $value utc requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByUtc($value)
	{
		$sql = 'DELETE FROM pais WHERE utc = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna idioma sea igual al valor pasado como parámetro
     *
     * @param String $value idioma requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByIdioma($value)
	{
		$sql = 'DELETE FROM pais WHERE idioma = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna req_cheque sea igual al valor pasado como parámetro
     *
     * @param String $value req_cheque requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByReqCheque($value)
	{
		$sql = 'DELETE FROM pais WHERE req_cheque = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna req_doc sea igual al valor pasado como parámetro
     *
     * @param String $value req_doc requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByReqDoc($value)
	{
		$sql = 'DELETE FROM pais WHERE req_doc = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna codigo_path sea igual al valor pasado como parámetro
     *
     * @param String $value codigo_path requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByCodigoPath($value)
	{
		$sql = 'DELETE FROM pais WHERE codigo_path = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}






    /**
     * Crear y devolver un objeto del tipo Pais
     * con los valores de una consulta sql
     *
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Object $Pais Pais
     *
     * @access protected
     *
     */
	protected function readRow($row)
	{
		$pais = new Pais();

		$pais->paisId = $row['pais_id'];
		$pais->iso = $row['iso'];
		$pais->paisNom = $row['pais_nom'];
		$pais->moneda = $row['moneda'];
		$pais->estado = $row['estado'];
		$pais->utc = $row['utc'];
		$pais->idioma = $row['idioma'];
		$pais->reqCheque = $row['req_cheque'];
		$pais->reqDoc = $row['req_doc'];
        $pais->codigoPath = $row['codigo_path'];
        $pais->prefijoCelular = $row['prefijo_celular'];

		return $pais;
	}




    /**
    * Realizar una consulta en la tabla de Pais 'Pais'
    * de una manera personalizada
    *
    * @param String $select campos de consulta
    * @param String $sidx columna para ordenar
    * @param String $sord orden los datos asc | desc
    * @param String $start inicio de la consulta
    * @param String $limit limite de la consulta
    * @param String $filters condiciones de la consulta
    * @param boolean $searchOn utilizar los filtros o no
    *
    * @return Array resultado de la consulta
    *
    */
	public function queryPaises($sidx,$sord,$start,$limit,$filters,$searchOn,$mandante='',$grouping='')
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
					$where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
				}
				else
				{
					$where = "";
				}
			}

		}

		$innerMandante='';

		if ($mandante != ''){
			$innerMandante=  ' INNER JOIN pais_mandante ON (pais.pais_id = pais_mandante.pais_id AND pais_mandante.mandante IN ("'.$mandante.'")  ) ';
		}else{
			$innerMandante=  ' INNER JOIN pais_mandante ON (pais.pais_id = pais_mandante.pais_id  ) ';

		}

		$sql = 'SELECT count(*) count from ciudad  INNER JOIN departamento ON (ciudad.depto_id = departamento.depto_id) INNER JOIN  pais_moneda ON (pais_moneda.pais_id = departamento.pais_id) INNER JOIN pais ON(pais_moneda.pais_id=pais.pais_id) '.$innerMandante . $where ;


			$sqlQuery = new SqlQuery($sql);

			$count = $this->execute2($sqlQuery);


		if($grouping != ""){
			$where = $where . " GROUP BY " . $grouping;
		}


			$sql = 'SELECT  pais_mandante.*,ciudad.*,departamento.*,pais.*,pais_moneda.* from ciudad INNER JOIN departamento ON (ciudad.depto_id = departamento.depto_id) INNER JOIN  pais_moneda ON (pais_moneda.pais_id = departamento.pais_id) INNER JOIN pais ON(pais_moneda.pais_id=pais.pais_id)  '. $innerMandante . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

		if($_REQUEST['isDebug']=='1'){
			print_r($sql);
		}

			$sqlQuery = new SqlQuery($sql);

			$result = $this->execute2($sqlQuery);

			$json = '{ "count" : '.json_encode($count). ', "data" : '.json_encode($result).'}';

			return  $json;
	}





	/**
	 * Realizar una consulta en la tabla de Pais 'Pais' con Codigos postales
	 * de una manera personalizada
	 *
	 * @param String $select campos de consulta
	 * @param String $sidx columna para ordenar
	 * @param String $sord orden los datos asc | desc
	 * @param String $start inicio de la consulta
	 * @param String $limit limite de la consulta
	 * @param String $filters condiciones de la consulta
	 * @param boolean $searchOn utilizar los filtros o no
	 *
	 * @return Array resultado de la consulta
	 *
	 */
	public function queryPaisesCodigosPostales($sidx,$sord,$start,$limit,$filters,$searchOn)
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
					$where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
				}
				else
				{
					$where = "";
				}
			}

		}


		$sql = 'SELECT count(*) count from codigo_postal INNER JOIN ciudad ON (ciudad.ciudad_id = codigo_postal.ciudad_id )  INNER JOIN departamento ON (ciudad.depto_id = departamento.depto_id) INNER JOIN  pais_moneda ON (pais_moneda.pais_id = departamento.pais_id) INNER JOIN pais ON(pais_moneda.pais_id=pais.pais_id) ' . $where ;


		$sqlQuery = new SqlQuery($sql);

		$count = $this->execute2($sqlQuery);


		$sql = 'SELECT  ciudad.*,departamento.*,pais.*,pais_moneda.*,codigo_postal.* from codigo_postal INNER JOIN ciudad ON (ciudad.ciudad_id = codigo_postal.ciudad_id ) INNER JOIN departamento ON (ciudad.depto_id = departamento.depto_id) INNER JOIN  pais_moneda ON (pais_moneda.pais_id = departamento.pais_id) INNER JOIN pais ON(pais_moneda.pais_id=pais.pais_id) ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;


		$sqlQuery = new SqlQuery($sql);

		$result = $this->execute2($sqlQuery);

		$json = '{ "count" : '.json_encode($count). ', "data" : '.json_encode($result).'}';

		return  $json;
	}


	/**
	 * Realizar una consulta personalizada en la tabla pais
	 *
	 * @param string $sidx columna para ordenar
	 * @param string $sord orden de los datos asc | desc
	 * @param int $start inicio de la consulta
	 * @param int $limit límite de la consulta
	 * @param string $filters condiciones de la consulta
	 * @param boolean $searchOn utilizar los filtros o no
	 * @param string $mandante mandante para filtrar
	 *
	 * @return string JSON con el conteo y los datos de la consulta
	 */


	public function queryPaisesCustom($sidx,$sord,$start,$limit,$filters,$searchOn,$mandante='')
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
					$where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
				}
				else
				{
					$where = "";
				}
			}

		}

		$innerMandante='';

		if ($mandante != ''){
			$innerMandante=  ' INNER JOIN pais_mandante ON (pais.pais_id = pais_mandante.pais_id AND pais_mandante.mandante IN ("'.$mandante.'")  ) ';
		}else{
			$innerMandante=  ' INNER JOIN pais_mandante ON (pais.pais_id = pais_mandante.pais_id  ) ';

		}

		$sql = 'SELECT count(*) count  from pais INNER JOIN  pais_moneda ON (pais_moneda.pais_id = pais.pais_id) '.$innerMandante . $where ;


		$sqlQuery = new SqlQuery($sql);

		$count = $this->execute2($sqlQuery);


		$sql = 'SELECT  pais.* from pais INNER JOIN  pais_moneda ON (pais_moneda.pais_id = pais.pais_id) '.$innerMandante . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;


		$sqlQuery = new SqlQuery($sql);

		$result = $this->execute2($sqlQuery);

		$json = '{ "count" : '.json_encode($count). ', "data" : '.json_encode($result).'}';

		return  $json;
	}


	/**
	 * Realizar una consulta personalizada en la tabla pais
	 *
	 * @param string $sidx columna para ordenar
	 * @param string $sord orden de los datos asc | desc
	 * @param int $start inicio de la consulta
	 * @param int $limit límite de la consulta
	 * @param string $filters condiciones de la consulta
	 * @param boolean $searchOn utilizar los filtros o no
	 *
	 * @return string JSON con el conteo y los datos de la consulta
	 */

	public function queryPaisesCustom2($sidx,$sord,$start,$limit,$filters,$searchOn)
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
					$where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
				}
				else
				{
					$where = "";
				}
			}

		}


		$sql = 'SELECT count(*) count  from pais ' . $where ;


		$sqlQuery = new SqlQuery($sql);

		$count = $this->execute2($sqlQuery);


		$sql = 'SELECT  pais.* from pais ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;


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
