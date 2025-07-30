<?php namespace Backend\mysql;
use Backend\dao\RuletaInternoDAO;
use Backend\dto\RuletaInterno;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Exception;
/** 
* Clase 'RuletaInternoMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'RuletaInterno'
* 
* Ejemplo de uso: 
* $RuletaInternoMySqlDAO = new RuletaInternoMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class RuletaInternoMySqlDAO implements RuletaInternoDAO{


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
		$sql = 'SELECT * FROM ruleta_interno WHERE ruleta_id = ?';
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
		$sql = 'SELECT * FROM ruleta_interno';
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
		$sql = 'SELECT * FROM ruleta_interno ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}

	
	/**
 	 * Eliminar todos los registros condicionados
 	 * por la llave primaria
 	 *
 	 * @param String $ruleta_id llave primaria
 	 *
	 * @return boolean $ resultado de la consulta
     *
 	 */
	public function delete($ruleta_id){
		$sql = 'DELETE FROM ruleta_interno WHERE ruleta_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($ruleta_id);
		return $this->executeUpdate($sqlQuery);
	}
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param Objeto RuletaInterno RuletaInterno
 	 *
	 * @return String $id resultado de la consulta
     *
 	 */
	public function insert($RuletaInterno){
		$sql = 'INSERT INTO ruleta_interno (fecha_inicio, fecha_fin, descripcion,nombre, tipo, estado, mandante, usucrea_id, usumodif_id,condicional, orden,cupo_actual,cupo_maximo,cantidad_ruletas,maximo_ruletas,codigo,reglas) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';


		$sqlQuery = new SqlQuery($sql);

        $sqlQuery->set($RuletaInterno->fechaInicio);
		$sqlQuery->set($RuletaInterno->fechaFin);
        $sqlQuery->set($RuletaInterno->descripcion);
        $sqlQuery->set($RuletaInterno->nombre);
		$sqlQuery->set($RuletaInterno->tipo);
		$sqlQuery->set($RuletaInterno->estado);
		$sqlQuery->setNumber($RuletaInterno->mandante);
		$sqlQuery->setNumber($RuletaInterno->usucreaId);
        $sqlQuery->setNumber($RuletaInterno->usumodifId);
        $sqlQuery->set($RuletaInterno->condicional);
        $sqlQuery->set($RuletaInterno->orden);
        $sqlQuery->set($RuletaInterno->cupoActual);
        $sqlQuery->set($RuletaInterno->cupoMaximo);
        $sqlQuery->set($RuletaInterno->cantidadRuletas);
        $sqlQuery->set($RuletaInterno->maximoRuletas);
        $sqlQuery->set($RuletaInterno->codigo);
        $sqlQuery->set($RuletaInterno->reglas);

		$id = $this->executeInsert($sqlQuery);
		$RuletaInterno->ruletaId = $id;
		return $id;
	}
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Objeto RuletaInterno RuletaInterno
 	 *
	 * @return boolean $ resultado de la consulta
     *
 	 */
	public function update($RuletaInterno){
		$sql = 'UPDATE ruleta_interno SET fecha_inicio = ?, fecha_fin = ?, descripcion = ?,nombre=?, tipo = ?, estado = ?,  mandante = ?, usucrea_id = ?, usumodif_id = ?, condicional = ?,orden = ?, cupo_actual = ?, cupo_maximo = ?, cantidad_ruletas = ?, maximo_ruletas = ?, codigo = ?, reglas = ? WHERE ruleta_id = ?';
		$sqlQuery = new SqlQuery($sql);

		$sqlQuery->set($RuletaInterno->fechaInicio);
		$sqlQuery->set($RuletaInterno->fechaFin);
        $sqlQuery->set($RuletaInterno->descripcion);
        $sqlQuery->set($RuletaInterno->nombre);
		$sqlQuery->set($RuletaInterno->tipo);
		$sqlQuery->set($RuletaInterno->estado);
		$sqlQuery->set($RuletaInterno->mandante);
		$sqlQuery->setNumber($RuletaInterno->usucreaId);
        $sqlQuery->setNumber($RuletaInterno->usumodifId);
        $sqlQuery->set($RuletaInterno->condicional);
        $sqlQuery->set($RuletaInterno->orden);
        $sqlQuery->set($RuletaInterno->cupoActual);
        $sqlQuery->set($RuletaInterno->cupoMaximo);
        $sqlQuery->set($RuletaInterno->cantidadRuletas);
        $sqlQuery->set($RuletaInterno->maximoRuletas);
        $sqlQuery->set($RuletaInterno->codigo);

        if($RuletaInterno->reglas != ""){
            $RuletaInterno->reglas = str_replace("'","\'",$RuletaInterno->reglas);
        }

        $sqlQuery->set($RuletaInterno->reglas);

		$sqlQuery->setNumber($RuletaInterno->ruletaId);
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
		$sql = 'DELETE FROM ruleta_interno';
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
		$sql = 'SELECT * FROM ruleta_interno WHERE fecha_inicio = ?';
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
		$sql = 'SELECT * FROM ruleta_interno WHERE fecha_fin = ?';
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
		$sql = 'SELECT * FROM ruleta_interno WHERE descripcion = ?';
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
		$sql = 'SELECT * FROM ruleta_interno WHERE tipo = ?';
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
		$sql = 'SELECT * FROM ruleta_interno WHERE estado = ?';
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
		$sql = 'SELECT * FROM ruleta_interno WHERE fecha_modif = ?';
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
		$sql = 'SELECT * FROM ruleta_interno WHERE fecha_crea = ?';
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
		$sql = 'SELECT * FROM ruleta_interno WHERE mandante = ?';
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
		$sql = 'SELECT * FROM ruleta_interno WHERE usucrea_id = ?';
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
		$sql = 'SELECT * FROM ruleta_interno WHERE usumodif_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}






    /**
    * Realizar una consulta en la tabla de RuletaInterno 'RuletaInterno'
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
	public function queryRuletasCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn)
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



		  $sql = "SELECT count(*) count FROM ruleta_interno INNER JOIN mandante ON (ruleta_interno.mandante = mandante.mandante) " . $where;


		  $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
		$sql = "SELECT ".$select." FROM ruleta_interno INNER JOIN mandante ON (ruleta_interno.mandante = mandante.mandante)  " . $where ." " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

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
     * Crear y devolver un objeto del tipo RuletaInterno
     * con los valores de una consulta sql
     * 
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Objeto $RuletaInterno RuletaInterno
     *
     * @access protected
     *
     */
	protected function readRow($row){
		$RuletaInterno = new RuletaInterno();

		$RuletaInterno->ruletaId = $row['ruleta_id'];
		$RuletaInterno->fechaInicio = $row['fecha_inicio'];
		$RuletaInterno->fechaFin = $row['fecha_fin'];
        $RuletaInterno->descripcion = $row['descripcion'];
        $RuletaInterno->nombre = $row['nombre'];
		$RuletaInterno->tipo = $row['tipo'];
		$RuletaInterno->estado = $row['estado'];
		$RuletaInterno->mandante = $row['mandante'];
		$RuletaInterno->usucreaId = $row['usucrea_id'];
		$RuletaInterno->fechaCrea = $row['fecha_crea'];
		$RuletaInterno->usumodifId = $row['usumodif_id'];
        $RuletaInterno->fechaModif = $row['fecha_modif'];
        $RuletaInterno->orden = $row['orden'];
        $RuletaInterno->condicional = $row['condicional'];
        $RuletaInterno->cupoActual = $row['cupo_actual'];
        $RuletaInterno->cupoMaximo = $row['cupo_maximo'];
        $RuletaInterno->cantidadRuletas = $row['cantidad_ruletas'];
        $RuletaInterno->maximoRuletas = $row['maximo_ruletas'];
        $RuletaInterno->codigo = $row['codigo'];
        $RuletaInterno->reglas = $row['reglas'];

		return $RuletaInterno;
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