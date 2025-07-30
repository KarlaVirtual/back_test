<?php namespace Backend\mysql;
use Backend\dao\EmpleadoDAO;
use Backend\dto\Empleado;
use Backend\sql\QueryExecutor;
use Backend\sql\SqlQuery;
use Backend\sql\Transaction;
/** 
* Clase 'EmpleadoMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'Empleado'
* 
* Ejemplo de uso: 
* $EmpleadoMySqlDAO = new EmpleadoMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class EmpleadoMySqlDAO implements EmpleadoDAO{


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
     * @param String $descarga_id llave primaria
     *
     * @return Array resultado de la consulta
     *
     */
	public function load($id){
		$sql = 'SELECT * FROM empleado WHERE empleado_id = ?';
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
		$sql = 'SELECT * FROM empleado';
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
		$sql = 'SELECT * FROM empleado ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $empleado_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function delete($empleado_id){
		$sql = 'DELETE FROM empleado WHERE empleado_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($empleado_id);
		return $this->executeUpdate($sqlQuery);
	}
	
    /**
     * Insertar un registro en la base de datos
     *
     * @param Object empleado empleado
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($empleado){
		$sql = 'INSERT INTO empleado (nombre, apellido, estado,usucrea_id,usumodif_id,tipodoc_id,documento,cargo_id,area_id,salario,mandante) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($empleado->nombre);
		$sqlQuery->set($empleado->apellido);
		$sqlQuery->set($empleado->estado);
        $sqlQuery->set($empleado->usucreaId);
        $sqlQuery->set($empleado->usumodifId);

        $sqlQuery->set($empleado->tipodocId);
        $sqlQuery->set($empleado->documento);
        $sqlQuery->set($empleado->cargoId);
        $sqlQuery->set($empleado->areaId);
        $sqlQuery->set($empleado->salario);
        $sqlQuery->set($empleado->mandante);



        $id = $this->executeInsert($sqlQuery);
		$empleado->empleadoId = $id;
		return $id;
	}
	
    /**
     * Editar un registro en la base de datos
     *
     * @param Object empleado empleado
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($empleado){
		$sql = 'UPDATE empleado SET nombre = ?, apellido = ?, estado = ?,usucrea_id = ?,usumodif_id = ?, tipodoc_id = ?,documento = ?,cargo_id = ?,area_id = ?,salario = ?,mandante = ? WHERE empleado_id = ?';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($empleado->nombre);
		$sqlQuery->set($empleado->apellido);
		$sqlQuery->set($empleado->estado);
        $sqlQuery->set($empleado->usucreaId);
        $sqlQuery->set($empleado->usumodifId);

        $sqlQuery->set($empleado->tipodocId);
        $sqlQuery->set($empleado->documento);
        $sqlQuery->set($empleado->cargoId);
        $sqlQuery->set($empleado->areaId);
        $sqlQuery->set($empleado->salario);
        $sqlQuery->set($empleado->mandante);

        $sqlQuery->set($empleado->empleadoId);
		return $this->executeUpdate($sqlQuery);
	}

    /**
    * Realizar una consulta en la tabla de egresos 'Egreso'
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
    * @return Array resultado de la consulta
    *
    */
    public function queryEmpleadoesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
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


        $sql = 'SELECT count(*) count FROM empleado ' . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT ' .$select .'  FROM empleado ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;


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
		$sql = 'DELETE FROM empleado';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}







    /**
     * Obtener todos los registros donde se encuentre que
     * la columna empleadoId sea igual al valor pasado como parámetro
     *
     * @param String $value empleadoId requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByTipo($value){
		$sql = 'SELECT * FROM empleado WHERE empleadoId = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna apellido sea igual al valor pasado como parámetro
     *
     * @param String $value apellido requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByDescripcion($value){
		$sql = 'SELECT * FROM empleado WHERE apellido = ?';
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
	public function queryByEstado($value){
		$sql = 'SELECT * FROM empleado WHERE estado = ?';
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
		$sql = 'SELECT * FROM empleado WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna codigo sea igual al valor pasado como parámetro
     *
     * @param String $value codigo requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByAbreviado($value){
        $sql = 'SELECT * FROM empleado WHERE codigo = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }







    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna empleadoId sea igual al valor pasado como parámetro
     *
     * @param String $value empleadoId requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByTipo($value){
		$sql = 'DELETE FROM empleado WHERE empleadoId = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna apellido sea igual al valor pasado como parámetro
     *
     * @param String $value apellido requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByDescripcion($value){
		$sql = 'DELETE FROM empleado WHERE apellido = ?';
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
	public function deleteByEstado($value){
		$sql = 'DELETE FROM empleado WHERE estado = ?';
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
		$sql = 'DELETE FROM empleado WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}








    /**
     * Crear y devolver un objeto del tipo CupoLog
     * con los valores de una consulta sql
     * 
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Object $Empleado Empleado
     *
     * @access protected
     *
     */
	protected function readRow($row){
		$empleado = new Empleado();
		
		$empleado->empleadoId = $row['empleado_id'];
		$empleado->nombre = $row['nombre'];
		$empleado->apellido = $row['apellido'];
		$empleado->estado = $row['estado'];
        $empleado->usucreaId = $row['usucrea_id'];
        $empleado->usumodifId = $row['usumodif_id'];

        $empleado->tipodocId = $row['tipodoc_id'];;
        $empleado->documento = $row['documento'];;
        $empleado->cargoId = $row['cargo_id'];;
        $empleado->areaId = $row['area_id'];;
        $empleado->salario = $row['salario'];;
        $empleado->mandante = $row['mandante'];;


        return $empleado;
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