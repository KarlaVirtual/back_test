<?php namespace Backend\mysql;

use Backend\dao\ContactoComercialLogDAO;
use Backend\dto\ContactoComercialLog;
use Backend\sql\QueryExecutor;
use Backend\sql\SqlQuery;
use Backend\sql\Transaction;

/**
* Clase 'ContactoComercialLogMySqlDAO'
*
* Esta clase provee las consultas del modelo o tabla 'ContactoComercialLog'
*
* Ejemplo de uso:
* $ContactoComercialLogMySqlDAO = new ContactoComercialLogMySqlDAO();
*
*
* @package ninguno
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public
* @see no
*
*/
class ContactoComercialLogMySqlDAO implements ContactoComercialLogDAO{

    /** Objeto vincula una conexión de la base de datos con el objeto correspondiente
     * @var Transaction $transaction
     */
    private $transaction;


    /**
     * Obtener la transacción actual.
     *
     * @return Transaction La transacción actual.
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * Establecer una nueva transacción.
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
     * Obtener el registro condicionado por la
     * llave primaria que se pasa como parámetro
     *
     * @param String $id llave primaria
     *
     * @return Array resultado de la consulta
     *
     */
	public function load($id){
		$sql = 'SELECT * FROM contacto_comercial_log WHERE contactocomlog_id = ?';
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
	public function queryAll(){
		$sql = 'SELECT * FROM contacto_comercial_log';
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
	public function queryAllOrderBy($orderColumn){
		$sql = 'SELECT * FROM contacto_comercial_log ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $contactocomlog_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function delete($contactocomlog_id){
		$sql = 'DELETE FROM contacto_comercial_log WHERE contactocomlog_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($contactocomlog_id);
		return $this->executeUpdate($sqlQuery);
	}
	
    /**
     * Insertar un registro en la base de datos
     *
     * @param Object contactoComercialLog contactoComercialLog
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($contactoComercialLog){
		$sql = 'INSERT INTO contacto_comercial_log (contactocom_id, fecha, usuario_id, texto, mandante) VALUES (?, ?, ?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($contactoComercialLog->contactocomId);
		$sqlQuery->set($contactoComercialLog->fecha);
		$sqlQuery->set($contactoComercialLog->usuarioId);
		$sqlQuery->set($contactoComercialLog->texto);
		$sqlQuery->set($contactoComercialLog->mandante);

		$id = $this->executeInsert($sqlQuery);	
		$contactoComercialLog->contactocomlogId = $id;
		return $id;
	}
	
    /**
     * Editar un registro en la base de datos
     *
     * @param Object contactoComercialLog contactoComercialLog
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($contactoComercialLog){
		$sql = 'UPDATE contacto_comercial_log SET contactocom_id = ?, fecha = ?, usuario_id = ?, texto = ?, mandante = ? WHERE contactocomlog_id = ?';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($contactoComercialLog->contactocomId);
		$sqlQuery->set($contactoComercialLog->fecha);
		$sqlQuery->set($contactoComercialLog->usuarioId);
		$sqlQuery->set($contactoComercialLog->texto);
		$sqlQuery->set($contactoComercialLog->mandante);

		$sqlQuery->set($contactoComercialLog->contactocomlogId);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Realiza una consulta personalizada en la tabla contacto_comercial_log.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Campo por el cual se ordenarán los resultados.
     * @param string $sord Orden de los resultados (ASC o DESC).
     * @param int $start Índice de inicio para la paginación.
     * @param int $limit Número máximo de registros a devolver.
     * @param string $filters Filtros en formato JSON para aplicar en la consulta.
     * @param bool $searchOn Indica si se deben aplicar los filtros.
     * 
     * @return string JSON con el conteo de registros y los datos resultantes de la consulta.
     */
    public function queryContactoComercialLogsCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
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


        $sql = 'SELECT count(*) count FROM contacto_comercial_log ' . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT ' .$select .'  FROM contacto_comercial_log ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;


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
		$sql = 'DELETE FROM contacto_comercial_log';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}







    /**
     * Obtener todos los registros donde se encuentre que
     * la columna contactocom_id sea igual al valor pasado como parámetro
     *
     * @param String $value contactocom_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByContactocomId($value){
		$sql = 'SELECT * FROM contacto_comercial_log WHERE contactocom_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna fecha sea igual al valor pasado como parámetro
     *
     * @param String $value fecha requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByFecha($value){
		$sql = 'SELECT * FROM contacto_comercial_log WHERE fecha = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna usuario_id sea igual al valor pasado como parámetro
     *
     * @param String $value usuario_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByUsuarioId($value){
		$sql = 'SELECT * FROM contacto_comercial_log WHERE usuario_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna texto sea igual al valor pasado como parámetro
     *
     * @param String $value texto requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByTexto($value){
		$sql = 'SELECT * FROM contacto_comercial_log WHERE texto = ?';
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
     * @return Array resultado de la consulta
     *
     */
	public function queryByMandante($value){
		$sql = 'SELECT * FROM contacto_comercial_log WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}









    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna contactocom_id sea igual al valor pasado como parámetro
     *
     * @param String $value contactocom_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByContactocomId($value){
		$sql = 'DELETE FROM contacto_comercial_log WHERE contactocom_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna fecha sea igual al valor pasado como parámetro
     *
     * @param String $value fecha requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByFecha($value){
		$sql = 'DELETE FROM contacto_comercial_log WHERE fecha = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna usuario_id sea igual al valor pasado como parámetro
     *
     * @param String $value usuario_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByUsuarioId($value){
		$sql = 'DELETE FROM contacto_comercial_log WHERE usuario_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna texto sea igual al valor pasado como parámetro
     *
     * @param String $value texto requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByTexto($value){
		$sql = 'DELETE FROM contacto_comercial_log WHERE texto = ?';
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
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByMandante($value){
		$sql = 'DELETE FROM contacto_comercial_log WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}









    /**
     * Crear y devolver un objeto del tipo Competencia
     * con los valores de una consulta sql
     *
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Object $ContactoComercialLog ContactoComercialLog
     *
     * @access protected
     *
     */
	protected function readRow($row){
		$contactoComercialLog = new ContactoComercialLog();
		
		$contactoComercialLog->contactocomlogId = $row['contactocomlog_id'];
		$contactoComercialLog->contactocomId = $row['contactocom_id'];
		$contactoComercialLog->fecha = $row['fecha'];
		$contactoComercialLog->usuarioId = $row['usuario_id'];
		$contactoComercialLog->texto = $row['texto'];
		$contactoComercialLog->mandante = $row['mandante'];

		return $contactoComercialLog;
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
     * Ejecutar una consulta sql y devolver los datos
     * como un arreglo asociativo
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
     * Ejecutar una consulta sql y devolver el resultado como un arreglo
     *
     *
     * @param String $sqlQuery consulta sql
     *
     * @return Array $tab arreglo
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
	protected function executeUpdate($sqlQuery){
		return QueryExecutor::executeUpdate($this->transaction,$sqlQuery);
	}
	/**
     * Execute2 sql query
     */
    protected function execute2($sqlQuery)
    {
        return QueryExecutor::execute2($this->transaction, $sqlQuery);
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