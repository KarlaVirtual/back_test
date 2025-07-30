<?php namespace Backend\mysql;

use Backend\dto\Registro2;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Backend\sql\Transaction;


class Registro2MySqlDAO {
      /**
    * Atributo Transaction transacción
    *
    * @var object
    */
    private $transaction;

    /**
     * 
     * Obtener la transaccion de un objeto
     * 
     * @return Objeto Transaction transaccion
     * 
     * 
     */

    public function getTransaction(){
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

    public function setTransaction($transaction){
        $this->transaction = $transaction;
    }

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
    

    public function load($id){
        $sql = 'SELECT * FROM registro2 where registro2_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($id);
        return $this->getRows($sqlQuery);
    }

     /**
     * Obtener todos los registros de la base de datos
     * 
     * @return Array resultado de la consulta
     * 
     * 
     * 
     */

     public function queryAll(){
        $sql = 'SELECT * FROM registro2';
        $sqlQuery = NEW SqlQuery($sql);
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
        $sql = 'SELECT * FROM registro2 ORDER BY $orderColumn';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($orderColumn);
        return $this->getList($sqlQuery);
    }


    public function delete($id){
        $sql = 'DELETE FROM registro2 WHERE registro2_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($id);
        $sqlQuery->set($sql);
    }

    
    /**
     * 
     * Insertar un registro en la base de datos
     * 
     * @param Object
     * 
     * @return String $id resultado de la consulta
     * 
     */


     public function INSERT($datos){
        $sql = 'INSERT INTO registro2 (cedula,nombre,apellido,telefono,email,tipo) VALUES (?,?,?,?,?,?)';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($datos->cedula);
        $sqlQuery->set($datos->nombre);
        $sqlQuery->set($datos->apellido);
        $sqlQuery->set($datos->telefono);
        $sqlQuery->set($datos->email);
        $sqlQuery->set($datos->tipo);

        $id = $this->executeInsert($sqlQuery);
        $datos->Registro2_id = $id;
        return $id;

     }


     public function Update($datos1){
        $sql = 'UPDATE registro2 SET cedula = ?, nombre = ?, apellido = ?, telefono = ?,email = ?, tipo=? WHERE registro2_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($datos1->cedula);
        $sqlQuery->set($datos1->nombre);
        $sqlQuery->set($datos1->apellido);
        $sqlQuery->set($datos1->telefono);
        $sqlQuery->set($datos1->email);
        $sqlQuery->set($datos1->tipo);
        $sqlQuery->set($datos1->Registro2_id);

        return $this->executeUpdate($sqlQuery);

     }


     public function queryByDocument($docnumber){
      
        $sql = 'SELECT * FROM registro2 WHERE cedula = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($docnumber);
        return $this->getList($sqlQuery);
     }

    //  public function checkDocument($datos){
    //     $sql = "SELECT  registro2_id FROM Registro2 where cedula = ?";
    //     $sqlQuery = new SqlQuery($sql);
    //     $sqlQuery->set($datos);
    //     return $this->getList($sqlQuery);
    //  }


     public function queryByPhone($Phone){
        $sql = 'SELECT * FROM registro2 where telefono = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($Phone);
        return $this->getList($sqlQuery);
     }


     /** 
     * realizar una consulta a la tabla usuario_informacion de una manera personalizada
     * 
     * @param String $select campos de consulta
     * @param String $sidx columna para ordenar
     * @param String $sord columna para oden de los datos ASC|DESC
     * @param String $start inicio de la consulta
     * @param String $limit limite de la consulta
     * @param String $filters condiciones de la consulta
     * @param boolean $searchOn utilizar o no los filtros
     * @param String $grouping columna para agrupar
     * 
     */


     public function queryRegistros2Custom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping){
           
        $where = "where 1= 1";

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
                
                if($fieldOperation != ""){
                    $whereArray[] = $fieldName. $fieldOperation;
                }

                if(count($whereArray)>0){
                    $where = $where . " " . $groupOperation . " UCASE(CAST(" . $fieldName . " AS CHAR)) " . strtoupper($fieldOperation);
                }else{
                    $where = "";
                }
            }
        }

        $sql = 'SELECT COUNT(*) FROM registro2'.' '.$where;

        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);

        
        $sql = "SELECT".' '.$select.' '."FROM registro2".$where . " " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;

    }


    protected function readRow($row){

        $usuarioInformacion = new Registro2();
        $usuarioInformacion->Registro2Id = $row['registro2_id'];
        $usuarioInformacion->cedula = $row['cedula'];
        $usuarioInformacion->nombre = $row['nombre'];
        $usuarioInformacion->apellido = $row['apellido'];
        $usuarioInformacion->telefono = $row['telefono'];
        $usuarioInformacion->email = $row['email'];
        $usuarioInformacion->tipo = $row['tipo'];
        $usuarioInformacion->fechaCrea = $row['fecha_crea'];
        $usuarioInformacion->fechaModif = $row['fecha_modif'];

        return $usuarioInformacion;
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

     protected function getRows($sqlQuery){
		$tab = QueryExecutor::execute($this->transaction,$sqlQuery);
		if(count($tab)==0){
			return null;
		}
		return $this->readRow($tab[0]);		
	}

     protected function getList($sqlQuery){
		$tab = QueryExecutor::execute($this->transaction,$sqlQuery);
		$ret = array();
		for($i=0;$i<count($tab);$i++){
			$ret[$i] = $this->readRow($tab[$i]);
		}
		return $ret;
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