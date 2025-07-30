<?php namespace Backend\mysql;
use Backend\dao\ClasificadorDAO;
use Backend\dto\Clasificador;
use Backend\sql\QueryExecutor;
use Backend\sql\SqlQuery;
use Backend\sql\Transaction;
use Backend\utils\RedisConnectionTrait;

/**
* Clase 'ClasificadorMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'Clasificador'
* 
* Ejemplo de uso: 
* $ClasificadorMySqlDAO = new ClasificadorMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class ClasificadorMySqlDAO implements ClasificadorDAO{

    use RedisConnectionTrait;

    private $redisParam = ['ex' => 604800];

    private $redisPrefix = "Clasificador+";

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
        if (!$this->transaction){
            $transaction = new Transaction();
            $this->transaction = $transaction;
        }
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
        if ($transaction == "") {

            $transaction = new Transaction();
            $this->transaction = $transaction;

        } else {
            $this->transaction = $transaction;
        }
    }






    /**
     * Obtener el registro condicionado por la 
     * llave primaria que se pasa como parámetro
     *
     * @param String $id llave primaria
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function load($id){
        $cachedKey = $this->redisPrefix . $id;
        $cachedValue = json_decode($this->getKey($cachedKey));

        if(!empty($cachedValue)) {
            return $cachedValue;
        }
        $sql = 'SELECT * FROM clasificador WHERE clasificador_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($id);

		$result = $this->getRow($sqlQuery);
        $this->setKey($cachedKey, json_encode($result), $this->redisParam);
        return $result;
    }

    /**
     * Carga una lista de registros de la tabla 'clasificador' basados en los IDs proporcionados.
     *
     * @param array $ids Arreglo de IDs a buscar en la columna 'abreviado'.
     * @return array Lista de registros que coinciden con los IDs proporcionados.
     */
    public function loadSelected($ids){

        $in_values = implode("', '", $ids);

		$sql = "SELECT * FROM clasificador WHERE abreviado IN ('".$in_values."')";
		$sqlQuery = new SqlQuery($sql);

		return $this->getList($sqlQuery);
	}


    /**
     * Obtener todos los registros de la base datos
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryAll(){
		$sql = 'SELECT * FROM clasificador';
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
     * @return Array $ resultado de la consulta
     *
     */
	public function queryAllOrderBy($orderColumn){
		$sql = 'SELECT * FROM clasificador ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $clasificador_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function delete($clasificador_id){
		$sql = 'DELETE FROM clasificador WHERE clasificador_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($clasificador_id);
        $this->deleteKey($this->redisPrefix . $clasificador_id);
		return $this->executeUpdate($sqlQuery);
	}
	
    /**
     * Insertar un registro en la base de datos
     *
     * @param Object clasificador clasificador
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($clasificador){
		$sql = 'INSERT INTO clasificador (tipo, descripcion, estado, mandante,abreviado) VALUES (?, ?, ?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($clasificador->tipo);
		$sqlQuery->set($clasificador->descripcion);
		$sqlQuery->set($clasificador->estado);
        $sqlQuery->set($clasificador->mandante);
        $sqlQuery->set($clasificador->abreviado);

		$id = $this->executeInsert($sqlQuery);	
		$clasificador->clasificadorId = $id;
        $this->setKey($this->redisPrefix . $clasificador->clasificadorId, json_encode($clasificador), $this->redisParam);
        $this->setKey($this->redisPrefix . "Abreviado+" . $clasificador->abreviado, json_encode($clasificador), $this->redisParam);
        return $id;
	}
	
    /**
     * Editar un registro en la base de datos
     *
     * @param Object clasificador clasificador
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($clasificador){
		$sql = 'UPDATE clasificador SET tipo = ?, descripcion = ?, estado = ?, mandante = ?, abreviado = ? WHERE clasificador_id = ?';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($clasificador->tipo);
		$sqlQuery->set($clasificador->descripcion);
		$sqlQuery->set($clasificador->estado);
		$sqlQuery->set($clasificador->mandante);
        $sqlQuery->set($clasificador->abreviado);

		$sqlQuery->set($clasificador->clasificadorId);
		$result = $this->executeUpdate($sqlQuery);
        $this->updateCache($this->redisPrefix . $clasificador->clasificadorId, json_encode($clasificador), $this->redisParam);
        $this->updateCache($this->redisPrefix . "Abreviado+" . $clasificador->abreviado, json_encode($clasificador), $this->redisParam);
        return $result;
    }






    /**
    * Realizar una consulta en la tabla de clasificadores 'Clasificador'
    * de una manera personalizada
    *
    * @param String $select campos de consulta
    * @param String $sidx columna para ordenar
    * @param String $sord orden los datos asc | desc
    * @param String $start inicio de la consulta
    * @param String $limit limite de la consulta
    * @param String $filters condiciones de la consulta 
    * @param boolean $searchOn utilizar los filtros o no
    * @param String $grouping columna para agrupar
    *
    * @return Array $json resultado de la consulta
    *
    */
    public function queryClasificadoresCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
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


        $sql = 'SELECT count(*) count FROM clasificador ' . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT ' .$select .'  FROM clasificador ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;


        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
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
		$sql = 'DELETE FROM clasificador';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
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
	public function queryByTipo($value){
		$sql = 'SELECT * FROM clasificador WHERE tipo = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
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
	public function queryByDescripcion($value){
		$sql = 'SELECT * FROM clasificador WHERE descripcion = ?';
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
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByEstado($value){
		$sql = 'SELECT * FROM clasificador WHERE estado = ?';
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
		$sql = 'SELECT * FROM clasificador WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna abreviado sea igual al valor pasado como parámetro
     *
     * @param String $value abreviado requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByAbreviado($value){
        $cachedKey = $this->redisPrefix . "Abreviado+" . $value;
        $cachedValue = json_decode($this->getKey($cachedKey));

        if(!empty($cachedValue)) {
            return $cachedValue;
        }

        $sql = 'SELECT * FROM clasificador WHERE abreviado = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        $result = $this->getList($sqlQuery);
        $this->setKey($cachedKey, json_encode($result), $this->redisParam);
        return $result;
    }








    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna tipo sea igual al valor pasado como parámetro
     *
     * @param String $value tipo requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByTipo($value){
		$sql = 'DELETE FROM clasificador WHERE tipo = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna descripcion sea igual al valor pasado como parámetro
     *
     * @param String $value descripcion requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByDescripcion($value){
		$sql = 'DELETE FROM clasificador WHERE descripcion = ?';
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
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByEstado($value){
		$sql = 'DELETE FROM clasificador WHERE estado = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna mandante sea igual al valor pasado como parámetro
     *
     * @param String $value mandante requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByMandante($value){
		$sql = 'DELETE FROM clasificador WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}








    /**
     * Crear y devolver un objeto del tipo Clasificador
     * con los valores de una consulta sql
     * 
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Object $Clasificador Clasificador
     *
     * @access protected
     *
     */
	protected function readRow($row){
		$clasificador = new Clasificador();
		
		$clasificador->clasificadorId = $row['clasificador_id'];
		$clasificador->tipo = $row['tipo'];
		$clasificador->descripcion = $row['descripcion'];
		$clasificador->estado = $row['estado'];
        $clasificador->mandante = $row['mandante'];
        $clasificador->abreviado = $row['abreviado'];

		return $clasificador;
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
		$tab = QueryExecutor::execute($this->getTransaction(),$sqlQuery);
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
		$tab = QueryExecutor::execute($this->getTransaction(),$sqlQuery);
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
		return QueryExecutor::execute($this->getTransaction(),$sqlQuery);
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
        return QueryExecutor::execute2($this->getTransaction(), $sqlQuery);
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
		return QueryExecutor::executeUpdate($this->getTransaction(),$sqlQuery);
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
		return QueryExecutor::queryForString($this->getTransaction(),$sqlQuery);
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
		return QueryExecutor::executeInsert($this->getTransaction(),$sqlQuery);
	}
}
?>