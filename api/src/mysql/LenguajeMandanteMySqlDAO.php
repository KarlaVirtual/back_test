<?php namespace Backend\mysql;
use Backend\dao\LenguajeMandanteDAO;
use Backend\dto\LenguajeMandante;
use Backend\sql\QueryExecutor;
use Backend\sql\SqlQuery;
use Backend\sql\Transaction;
/** 
* Clase 'LenguajeMandanteMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'LenguajeMandante'
* 
* Ejemplo de uso: 
* $LenguajeMandanteMySqlDAO = new LenguajeMandanteMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class LenguajeMandanteMySqlDAO implements LenguajeMandanteDAO{

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
	public function load($id){
		$sql = 'SELECT * FROM lenguaje_mandante WHERE lengmandante_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($id);
		return $this->getRow($sqlQuery);
	}

    /**
     * Obtener el registro condicionado por el lenguaje
     * y el valor que se pasa como parámetro
     *
     * @param String $lenguaje lenguaje
     * @param float $valor valor
     *
     * @return Array resultado de la consulta
     *
     */
    public function loadByLenguajeAndValor($lenguaje,$valor){
        $sql = 'SELECT * FROM lenguaje_mandante WHERE lenguaje = ? AND valor = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($lenguaje);
        $sqlQuery->set($valor);
        return $this->getRow($sqlQuery);
    }

    /**
     * Obtener todos los registros de la base datos
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryAll(){
		$sql = 'SELECT * FROM lenguaje_mandante';
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
		$sql = 'SELECT * FROM lenguaje_mandante ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $lengmandante_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function delete($lengmandante_id){
		$sql = 'DELETE FROM lenguaje_mandante WHERE lengmandante_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($lengmandante_id);
		return $this->executeUpdate($sqlQuery);
	}
	
    /**
     * Insertar un registro en la base de datos
     *
     * @param Object lenguaje_mandante lenguaje_mandante
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($lenguaje_mandante){
		$sql = 'INSERT INTO lenguaje_mandante (lenguaje,mandante,estado,valor,traducido,usucrea_id,usumodif_id) VALUES (?, ?, ?, ?, ?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);

        $sqlQuery->set($lenguaje_mandante->lenguaje);
        $sqlQuery->set($lenguaje_mandante->mandante);
        $sqlQuery->set($lenguaje_mandante->estado);
        $sqlQuery->set($lenguaje_mandante->valor);
        $sqlQuery->set($lenguaje_mandante->traducido);
        $sqlQuery->set($lenguaje_mandante->usucreaId);
        $sqlQuery->set($lenguaje_mandante->usumodifId);
        
		$id = $this->executeInsert($sqlQuery);	
		$lenguaje_mandante->lengmandanteId = $id;
		return $id;
	}
	
    /**
     * Editar un registro en la base de datos
     *
     * @param Object lenguaje_mandante lenguaje_mandante
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($lenguaje_mandante){
		$sql = 'UPDATE lenguaje_mandante SET lenguaje = ?,mandante = ?, estado = ?,valor = ?,traducido = ?,usucrea_id = ?,usumodif_id = ?  WHERE lengmandante_id = ?';
		$sqlQuery = new SqlQuery($sql);

        $sqlQuery->set($lenguaje_mandante->lenguaje);
        $sqlQuery->set($lenguaje_mandante->mandante);
        $sqlQuery->set($lenguaje_mandante->estado);
        $sqlQuery->set($lenguaje_mandante->valor);
        $sqlQuery->set($lenguaje_mandante->traducido);
        $sqlQuery->set($lenguaje_mandante->usucreaId);
        $sqlQuery->set($lenguaje_mandante->usumodifId);

		$sqlQuery->set($lenguaje_mandante->lengmandanteId);
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
		$sql = 'DELETE FROM lenguaje_mandante';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}





    /**
    * Realizar una consulta en la tabla de LenguajeMandante 'LenguajeMandante'
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
    public function queryLenguajeMandantesCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn)
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



        $sql = "SELECT count(*) count FROM lenguaje_mandante  INNER JOIN lenguaje_palabra ON (lenguaje_mandante.valor = lenguaje_palabra.lengpalabra_id)  " . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
        $sql = "SELECT ".$select." FROM lenguaje_mandante INNER JOIN lenguaje_palabra ON (lenguaje_mandante.valor = lenguaje_palabra.lengpalabra_id)  " . $where ." " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : '.json_encode($count). ', "data" : '.json_encode($result).'}';

        return  $json;
    }

    /**
    * Realizar una consulta en la tabla de LenguajeMandante 'LenguajeMandante'
    * de una manera personalizada
    *
    * @param String $select campos de consulta
    * @param String $sidx columna para ordenar
    * @param String $sord orden los datos asc | desc
    * @param String $start inicio de la consulta
    * @param String $limit limite de la consulta
    * @param String $filters condiciones de la consulta 
    * @param boolean $searchOn utilizar los filtros o no
    * @param String $leng largo
    *
    * @return Array resultado de la consulta
    *
    */
    public function queryLenguajeMandantesFromPalabraCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$leng)
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



        $sql = "SELECT count(*) count FROM lenguaje_palabra LEFT OUTER JOIN lenguaje_mandante ON (lenguaje_palabra.lengpalabra_id = lenguaje_mandante.valor AND lenguaje_mandante.lenguaje='".$leng."')   " . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
        $sql = "SELECT ".$select." FROM lenguaje_palabra LEFT OUTER JOIN lenguaje_mandante ON (lenguaje_palabra.lengpalabra_id = lenguaje_mandante.valor AND lenguaje_mandante.lenguaje='".$leng."' )   " . $where ." " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;


        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : '.json_encode($count). ', "data" : '.json_encode($result).'}';

        return  $json;
    }







    /**
     * Obtener todos los registros donde se encuentre que
     * la columna lenguajes sea igual al valor pasado como parámetro
     *
     * @param String $value lenguaje requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByLenguaje($value){
		$sql = 'SELECT * FROM lenguaje_mandante WHERE lenguaje = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}


    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna lenguaje sea igual al valor pasado como parámetro
     *
     * @param String $value lenguaje requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByDescripcion($value){
		$sql = 'DELETE FROM lenguaje_mandante WHERE lenguaje = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}





	
    /**
     * Crear y devolver un objeto del tipo LenguajeMandante
     * con los valores de una consulta sql
     * 
     *  
     * @param Arreglo $row arreglo asociativo
     *
     * @return Object $LenguajeMandante LenguajeMandante
     *
     * @access protected
     *
     */
	protected function readRow($row){
		$lenguaje_mandante = new LenguajeMandante();

		$lenguaje_mandante->lengmandanteId = $row['lengmandante_id'];
        $lenguaje_mandante->lenguaje = $row['lenguaje'];
        $lenguaje_mandante->mandante = $row['mandante'];
        $lenguaje_mandante->estado = $row['estado'];
        $lenguaje_mandante->valor = $row['valor'];
        $lenguaje_mandante->traducido = $row['traducido'];
        $lenguaje_mandante->usucreaId = $row['usucrea_id'];
        $lenguaje_mandante->usumodifId = $row['usumodif_id'];

		return $lenguaje_mandante;
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