<?php namespace Backend\mysql;
use Backend\dao\TorneoInternoDAO;
use Backend\dto\TorneoInterno;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Exception;
/** 
* Clase 'TorneoInternoMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'TorneoInterno'
* 
* Ejemplo de uso: 
* $TorneoInternoMySqlDAO = new TorneoInternoMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class TorneoInternoMySqlDAO implements TorneoInternoDAO{


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
		$sql = 'SELECT * FROM torneo_interno WHERE torneo_id = ?';
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
		$sql = 'SELECT * FROM torneo_interno';
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
	 * ordenadas por el nombre de la columna 
	 * que se pasa como parámetro
	 *
	 * @param String $orderColumn nombre de la columna
	 *
	 * @return Array $ resultado de la consulta
     *
	 */
	public function queryAllOrderBy($orderColumn){
		$sql = 'SELECT * FROM torneo_interno ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}

	
	/**
 	 * Eliminar todos los registros condicionados
 	 * por la llave primaria
 	 *
 	 * @param String $torneo_id llave primaria
 	 *
	 * @return boolean $ resultado de la consulta
     *
 	 */
	public function delete($torneo_id){
		$sql = 'DELETE FROM torneo_interno WHERE torneo_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($torneo_id);
		return $this->executeUpdate($sqlQuery);
	}
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param Objeto TorneoInterno TorneoInterno
 	 *
	 * @return String $id resultado de la consulta
     *
 	 */
	public function insert($TorneoInterno){
		$sql = 'INSERT INTO torneo_interno (fecha_inicio, fecha_fin, descripcion,nombre, tipo, estado, mandante, usucrea_id, usumodif_id,condicional, orden,cupo_actual,cupo_maximo,cantidad_torneos,maximo_torneos,codigo,reglas,json_temp) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)';


		$sqlQuery = new SqlQuery($sql);

        $sqlQuery->set($TorneoInterno->fechaInicio);
		$sqlQuery->set($TorneoInterno->fechaFin);
        $sqlQuery->set($TorneoInterno->descripcion);
        $sqlQuery->set($TorneoInterno->nombre);
		$sqlQuery->set($TorneoInterno->tipo);
		$sqlQuery->set($TorneoInterno->estado);
		$sqlQuery->setNumber($TorneoInterno->mandante);
		$sqlQuery->setNumber($TorneoInterno->usucreaId);
        $sqlQuery->setNumber($TorneoInterno->usumodifId);
        $sqlQuery->set($TorneoInterno->condicional);
        $sqlQuery->set($TorneoInterno->orden);
        $sqlQuery->set($TorneoInterno->cupoActual);
        $sqlQuery->set($TorneoInterno->cupoMaximo);
        $sqlQuery->set($TorneoInterno->cantidadTorneos);
        $sqlQuery->set($TorneoInterno->maximoTorneos);
        $sqlQuery->set($TorneoInterno->codigo);
        $sqlQuery->set($TorneoInterno->reglas);
        $sqlQuery->set($TorneoInterno->jsonTemp);

		$id = $this->executeInsert($sqlQuery);
		$TorneoInterno->torneoId = $id;
		return $id;
	}
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Objeto TorneoInterno TorneoInterno
 	 *
	 * @return boolean $ resultado de la consulta
     *
 	 */
	public function update($TorneoInterno){
		$sql = 'UPDATE torneo_interno SET fecha_inicio = ?, fecha_fin = ?, descripcion = ?,nombre=?, tipo = ?, estado = ?,  mandante = ?, usucrea_id = ?, usumodif_id = ?, condicional = ?,orden = ?, cupo_actual = ?, cupo_maximo = ?, cantidad_torneos = ?, maximo_torneos = ?, codigo = ?, reglas = ?,json_temp = ? WHERE torneo_id = ?';
		$sqlQuery = new SqlQuery($sql);

		$sqlQuery->set($TorneoInterno->fechaInicio);
		$sqlQuery->set($TorneoInterno->fechaFin);
        $sqlQuery->set($TorneoInterno->descripcion);
        $sqlQuery->set($TorneoInterno->nombre);
		$sqlQuery->set($TorneoInterno->tipo);
		$sqlQuery->set($TorneoInterno->estado);
		$sqlQuery->set($TorneoInterno->mandante);
		$sqlQuery->setNumber($TorneoInterno->usucreaId);
        $sqlQuery->setNumber($TorneoInterno->usumodifId);
        $sqlQuery->set($TorneoInterno->condicional);
        $sqlQuery->set($TorneoInterno->orden);
        $sqlQuery->set($TorneoInterno->cupoActual);
        $sqlQuery->set($TorneoInterno->cupoMaximo);
        $sqlQuery->set($TorneoInterno->cantidadTorneos);
        $sqlQuery->set($TorneoInterno->maximoTorneos);
        $sqlQuery->set($TorneoInterno->codigo);
        if($TorneoInterno->reglas != ""){
            $TorneoInterno->reglas = str_replace("'","\'",$TorneoInterno->reglas);
        }

        $sqlQuery->set($TorneoInterno->reglas);
        $sqlQuery->set($TorneoInterno->jsonTemp);


		$sqlQuery->setNumber($TorneoInterno->torneoId);
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
		$sql = 'DELETE FROM torneo_interno';
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
		$sql = 'SELECT * FROM torneo_interno WHERE fecha_inicio = ?';
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
		$sql = 'SELECT * FROM torneo_interno WHERE fecha_fin = ?';
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
		$sql = 'SELECT * FROM torneo_interno WHERE descripcion = ?';
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
		$sql = 'SELECT * FROM torneo_interno WHERE tipo = ?';
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
		$sql = 'SELECT * FROM torneo_interno WHERE estado = ?';
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
		$sql = 'SELECT * FROM torneo_interno WHERE fecha_modif = ?';
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
		$sql = 'SELECT * FROM torneo_interno WHERE fecha_crea = ?';
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
		$sql = 'SELECT * FROM torneo_interno WHERE mandante = ?';
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
		$sql = 'SELECT * FROM torneo_interno WHERE usucrea_id = ?';
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
		$sql = 'SELECT * FROM torneo_interno WHERE usumodif_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}






    /**
    * Realizar una consulta en la tabla de TorneoInterno 'TorneoInterno'
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
	public function queryTorneosCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn)
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



		  $sql = "SELECT count(*) count FROM torneo_interno INNER JOIN mandante ON (torneo_interno.mandante = mandante.mandante) " . $where;


		  $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
		$sql = "SELECT ".$select." FROM torneo_interno INNER JOIN mandante ON (torneo_interno.mandante = mandante.mandante)  " . $where ." " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

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
     * Crear y devolver un objeto del tipo TorneoInterno
     * con los valores de una consulta sql
     * 
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Objeto $TorneoInterno TorneoInterno
     *
     * @access protected
     *
     */
	protected function readRow($row){
		$TorneoInterno = new TorneoInterno();

		$TorneoInterno->torneoId = $row['torneo_id'];
		$TorneoInterno->fechaInicio = $row['fecha_inicio'];
		$TorneoInterno->fechaFin = $row['fecha_fin'];
        $TorneoInterno->descripcion = $row['descripcion'];
        $TorneoInterno->nombre = $row['nombre'];
		$TorneoInterno->tipo = $row['tipo'];
		$TorneoInterno->estado = $row['estado'];
		$TorneoInterno->mandante = $row['mandante'];
		$TorneoInterno->usucreaId = $row['usucrea_id'];
		$TorneoInterno->fechaCrea = $row['fecha_crea'];
		$TorneoInterno->usumodifId = $row['usumodif_id'];
        $TorneoInterno->fechaModif = $row['fecha_modif'];
        $TorneoInterno->orden = $row['orden'];
        $TorneoInterno->condicional = $row['condicional'];
        $TorneoInterno->cupoActual = $row['cupo_actual'];
        $TorneoInterno->cupoMaximo = $row['cupo_maximo'];
        $TorneoInterno->cantidadTorneos = $row['cantidad_torneos'];
        $TorneoInterno->maximoTorneos = $row['maximo_torneos'];
        $TorneoInterno->codigo = $row['codigo'];
        $TorneoInterno->reglas = $row['reglas'];
        $TorneoInterno->jsonTemp = $row['json_temp'];

		return $TorneoInterno;
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