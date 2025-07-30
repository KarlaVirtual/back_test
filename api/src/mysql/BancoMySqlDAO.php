<?php namespace Backend\mysql;
use Backend\dao\BancoDAO;
use Backend\dto\Banco;
use Backend\sql\QueryExecutor;
use Backend\sql\SqlQuery;
use Backend\sql\Transaction;
/** 
* Clase 'BancoMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'Bano'
* 
* Ejemplo de uso: 
* $BancoMySqlDAO = new BancoMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
*/
class BancoMySqlDAO implements BancoDAO{

    /**
    * Atributo Transaction transacción
    *
    * @var Objeto
    */
    private $transaction;

    /**
     * Obtener la transacción de un objeto
     *
     * @return Objeto Transaction transacción
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * Modificar el atributo transacción del objeto
     *
     * @param Objeto $transaction transacción
     *
     * @return void
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
    * @return void
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
     * @param string $id llave primaria
     *
     * @return array $ resultado de la consulta
     */
	public function load($id){
		$sql = 'SELECT * FROM banco WHERE banco_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($id);
		return $this->getRow($sqlQuery);
	}

    /**
     * Obtener todos los registros de la base datos
     *
     * @return array $ resultado de la consulta
     */
	public function queryAll(){
		$sql = 'SELECT * FROM banco';
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
    /**
     * Obtener todos los registros
     * ordenadas por el nombre de la columna 
     * que se pasa como parámetro
     *
     * @param string $orderColumn nombre de la columna
     *
     * @return array $ resultado de la consulta
     */
	public function queryAllOrderBy($orderColumn){
		$sql = 'SELECT * FROM banco ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param string $banco_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     */
	public function delete($banco_id){
		$sql = 'DELETE FROM banco WHERE banco_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($banco_id);
		return $this->executeUpdate($sqlQuery);
	}
	
    /**
     * Insertar un registro en la base de datos
     *
     * @param Objeto $banco Dto del banco que se registrará
     *
     * @return string $id resultado de la consulta
     */
	public function insert($banco){
		$sql = 'INSERT INTO banco (descripcion,pais_id,estado,producto_pago) VALUES (?,?,?,?)';
		$sqlQuery = new SqlQuery($sql);

        $sqlQuery->set($banco->descripcion);
        $sqlQuery->set($banco->paisId);
        $sqlQuery->set($banco->estado);
        $sqlQuery->set($banco->productoPago);

		$id = $this->executeInsert($sqlQuery);	
		$banco->bancoId = $id;
		return $id;
	}
	
    /**
     * Editar un registro en la base de datos
     *
     * @param Objeto $banco Dto del banco que se actualizará
     *
     * @return boolean resultado de la consulta
     */
	public function update($banco){
		$sql = 'UPDATE banco SET descripcion = ?,pais_id = ?, estado = ? , producto_pago = ?  WHERE banco_id = ?';
		$sqlQuery = new SqlQuery($sql);

        $sqlQuery->set($banco->descripcion);
        if($banco->paisId == ""){
            $sqlQuery->set(0);
        }else{
            $sqlQuery->set($banco->paisId);
        }
        $sqlQuery->set($banco->estado);
        if($banco->productoPago == ""){
            $sqlQuery->set(0);
        }else{
            $sqlQuery->set($banco->productoPago);
        }
		$sqlQuery->set($banco->bancoId);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todas los registros de la base de datos
     *
     * @return boolean resultado de la consulta
     */
	public function clean(){
		$sql = 'DELETE FROM banco';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}

    /**
    * Realizar una consulta en la tabla de bancos 'Banco'
    * de una manera personalizada
    *
    * @param string $select campos de consulta
    * @param string $sidx columna para ordenar
    * @param string $sord orden los datos asc | desc
    * @param string $start inicio de la consulta
    * @param string $limit limite de la consulta
    * @param string $filters condiciones de la consulta
    * @param boolean $searchOn utilizar los filtros o no
    *
    * @return array $json resultado de la consulta
    */
    public function queryBancosCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn)
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



        $sql = "SELECT count(*) count FROM banco INNER JOIN pais ON (banco.pais_id = pais.pais_id)  " . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
        $sql = "SELECT ".$select." FROM banco INNER JOIN pais ON (banco.pais_id = pais.pais_id) " . $where ." " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : '.json_encode($count). ', "data" : '.json_encode($result).'}';

        return  $json;
    }

    /**
     * Realizar una consulta en la tabla de bancos 'Banco'
     * de una manera personalizada
     *
     * @param string $select campos de consulta
     * @param string $sidx columna para ordenar
     * @param string $sord orden los datos asc | desc
     * @param string $start inicio de la consulta
     * @param string $limit limite de la consulta
     * @param string $filters condiciones de la consulta
     * @param boolean $searchOn utilizar los filtros o no
     *
     * @return array $json resultado de la consulta
     */
    public function queryBancosCustom3($select,$sidx,$sord,$start,$limit,$filters,$searchOn)
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



        $sql = "SELECT count(*) count FROM banco   " . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
        $sql = "SELECT ".$select." FROM banco  " . $where ." " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : '.json_encode($count). ', "data" : '.json_encode($result).'}';

        return  $json;
    }

    /**
     * Realizar una consulta en la tabla de bancos 'Banco'
     * de una manera personalizada
     *
     * @param string $select campos de consulta
     * @param string $sidx columna para ordenar
     * @param string $sord orden los datos asc | desc
     * @param string $start inicio de la consulta
     * @param string $limit limite de la consulta
     * @param string $filters condiciones de la consulta
     * @param boolean $searchOn utilizar los filtros o no
     *
     * @return array $json resultado de la consulta
     */
    public function queryBancosCustom2($select,$sidx,$sord,$start,$limit,$filters,$searchOn)
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



        $sql = "SELECT count(*) count FROM banco INNER JOIN pais ON (banco.pais_id = pais.pais_id)  " . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
        $sql = "SELECT ".$select." FROM banco" . $where ." " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;
        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : '.json_encode($count). ', "data" : '.json_encode($result).'}';

        return  $json;
    }



    /**
     * Obtener todos los registros donde se encuentre que
     * la columna Descripcion sea igual al valor pasado como parámetro
     *
     * @param string $value Descripcion requerido
     *
     * @return array $ resultado de la consulta
     */
    public function queryByDescripcion($value){
		$sql = 'SELECT * FROM banco WHERE descripcion = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna Descripcion sea igual al valor pasado como parámetro
     *
     * @param string $value Descripcion requerido
     *
     * @return boolean resultado de la ejecución
     */
	public function deleteByDescripcion($value){
		$sql = 'DELETE FROM banco WHERE descripcion = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}


    /**
     * Crear y devolver un objeto del tipo Banco
     * con los valores de una consulta sql
     * 
     *
     * @param array $row arreglo asociativo
     *
     * @return Objeto $Banco Banco
     *
     * @access protected
     */
	protected function readRow($row){
		$banco = new Banco();

		$banco->bancoId = $row['banco_id'];
        $banco->descripcion = $row['descripcion'];
        $banco->paisId = $row['pais_id'];
        $banco->estado = $row['estado'];
        $banco->productoPago = $row['producto_pago'];
        $banco->tipo = $row['tipo'];

		return $banco;
	}

    /**
     * Ejecutar una consulta sql y devolver los datos
     * como un arreglo asociativo 
     * 
     *
     * @param string $sqlQuery consulta sql
     *
     * @return array $ret arreglo asociativo
     *
     * @access protected
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
     * @param string $sqlQuery consulta sql
     *
     * @return array $ resultado de la ejecución
     *
     * @access protected
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
     * @param string $sqlQuery consulta sql
     *
     * @return array $ resultado de la ejecución
     *
     * @access protected
     */
	protected function execute($sqlQuery){
		return QueryExecutor::execute($this->transaction,$sqlQuery);
	}

    /**
     * Ejecutar una consulta sql
     * 
     *
     * @param string $sqlQuery consulta sql
     *
     * @return array $ resultado de la ejecución
     *
     * @access protected
     */
    protected function execute2($sqlQuery)
    {
        return QueryExecutor::execute2($this->transaction, $sqlQuery);
    }
        
    /**
     * Ejecutar una consulta sql como update
     * 
     *
     * @param string $sqlQuery consulta sql
     *
     * @return array $ resultado de la ejecución
     *
     * @access protected
     */
	protected function executeUpdate($sqlQuery){
		return QueryExecutor::executeUpdate($this->transaction,$sqlQuery);
	}

    /**
     * Ejecutar una consulta sql como select
     * 
     *
     * @param string $sqlQuery consulta sql
     *
     * @return array $ resultado de la ejecución
     *
     * @access protected
     */
	protected function querySingleResult($sqlQuery){
		return QueryExecutor::queryForString($this->transaction,$sqlQuery);
	}

    /**
     * Ejecutar una consulta sql como insert
     * 
     *
     * @param string $sqlQuery consulta sql
     *
     * @return array $ resultado de la ejecución
     *
     * @access protected
     */
	protected function executeInsert($sqlQuery){
		return QueryExecutor::executeInsert($this->transaction,$sqlQuery);
	}
}

?>