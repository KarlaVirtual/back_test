<?php namespace Backend\mysql;
use Backend\dao\PromocionalLogDAO;
use Backend\dto\PromocionalLog;
use Backend\sql\QueryExecutor;
use Backend\sql\SqlQuery;
use Backend\sql\Transaction;
/** 
* Clase 'PromocionalLogMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'PromocionalLog'
* 
* Ejemplo de uso: 
* $PromocionalLogMySqlDAO = new PromocionalLogMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class PromocionalLogMySqlDAO implements PromocionalLogDAO{


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
		$sql = 'SELECT * FROM promocional_log WHERE promolog_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($id);
		return $this->getRow($sqlQuery);
	}

    /**
     * Obtener todos los registros de la base datos
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryAll(){
		$sql = 'SELECT * FROM promocional_log';
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
		$sql = 'SELECT * FROM promocional_log ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
    
    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $promolog_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function delete($promolog_id){
		$sql = 'DELETE FROM promocional_log WHERE promolog_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($promolog_id);
		return $this->executeUpdate($sqlQuery);
	}
    
    /**
     * Insertar un registro en la base de datos
     *
     * @param Object PromocionalLog PromocionalLog
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($PromocionalLog){
		$sql = 'INSERT INTO promocional_log (usuario_id, promocional_id, valor,valor_promocional,valor_base,estado,error_id,id_externo,mandante,version, usucrea_id, usumodif_id,apostado,rollower_requerido,codigo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->setNumber($PromocionalLog->usuarioId);
		$sqlQuery->set($PromocionalLog->promocionalId);
        $sqlQuery->set($PromocionalLog->valor);
        $sqlQuery->set($PromocionalLog->valorPromocional);
        $sqlQuery->set($PromocionalLog->valorBase);
        $sqlQuery->set($PromocionalLog->estado);
        $sqlQuery->set($PromocionalLog->errorId);
        $sqlQuery->set($PromocionalLog->idExterno);
        $sqlQuery->set($PromocionalLog->mandante);
        $sqlQuery->set($PromocionalLog->version);



		$sqlQuery->setNumber($PromocionalLog->usucreaId);
		$sqlQuery->setNumber($PromocionalLog->usumodifId);
        $sqlQuery->setSIN($PromocionalLog->apostado);
        $sqlQuery->set($PromocionalLog->rollowerRequerido);
        $sqlQuery->set($PromocionalLog->codigo);


        $id = $this->executeInsert($sqlQuery);
		$PromocionalLog->promologId = $id;
		return $id;
	}
	
    /**
     * Editar un registro en la base de datos
     *
     * @param Object PromocionalLog PromocionalLog
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($PromocionalLog){

		$sql = 'UPDATE promocional_log SET usuario_id = ?, promocional_id = ?, valor = ?, valor_promocional= ?,valor_base= ?,estado= ?,error_id= ?,id_externo= ?,mandante= ?, version= ?, usucrea_id = ?, usumodif_id = ? , apostado = ?,rollower_requerido =?,codigo =? WHERE promolog_id = ?';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->setNumber($PromocionalLog->usuarioId);
		$sqlQuery->set($PromocionalLog->promocionalId);

        $sqlQuery->set($PromocionalLog->valor);
        $sqlQuery->set($PromocionalLog->valorPromocional);
        $sqlQuery->set($PromocionalLog->valorBase);
        $sqlQuery->set($PromocionalLog->estado);
        $sqlQuery->set($PromocionalLog->errorId);
        $sqlQuery->set($PromocionalLog->idExterno);
        $sqlQuery->set($PromocionalLog->mandante);
        $sqlQuery->set($PromocionalLog->version);

		$sqlQuery->setNumber($PromocionalLog->usucreaId);
        $sqlQuery->setNumber($PromocionalLog->usumodifId);
        $sqlQuery->setSIN($PromocionalLog->apostado);
        $sqlQuery->setSIN($PromocionalLog->rollowerRequerido);
        $sqlQuery->set($PromocionalLog->codigo);

		$sqlQuery->setNumber($PromocionalLog->promologId);
		return $this->executeUpdate($sqlQuery);
	}





    /**
    * Verificar rollower
    *
    * @param String $PromocionalLog PromocionalLog
    *
    * @return boolean resultado de la consulta
    *
    */
    public function verifyRollower($PromocionalLog){

        //$sql = 'UPDATE promocional_log,usuario_recarga,registro SET promocional_log.estado = "I", usuario_recarga.valor_promocional =promocional_log.valor,registro.creditos_base_ant=registro.creditos_base,registro.creditos_base=registro.creditos_base + promocional_log.valor   WHERE  usuario_recarga.promocional_id = promocional_log.promocional_id AND usuario_recarga.usuario_id = promocional_log.usuario_id AND registro.usuario_id= promocional_log.usuario_id AND promocional_log.apostado >= promocional_log.rollower_requerido AND promocional_log.promolog_id = ? AND promocional_log.estado="A" ';
        $sql = 'UPDATE promocional_log,usuario_recarga,registro SET promocional_log.estado = "I",registro.creditos_base_ant=registro.creditos_base,registro.creditos_base=registro.creditos_base + promocional_log.valor   WHERE  registro.usuario_id= promocional_log.usuario_id AND promocional_log.apostado >= promocional_log.rollower_requerido AND promocional_log.promolog_id = ? AND promocional_log.estado="P" ';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($PromocionalLog->promologId);
        return $this->executeUpdate($sqlQuery);
    }


    /**
    * Realizar una consulta en la tabla de PromocionalLog 'PromocionalLog'
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
    public function queryDeportesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
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


        $sql = 'SELECT count(*) count FROM promocional_log ' . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT ' .$select .'  FROM promocional_log LEFT OUTER JOIN bono_interno ON (promocional_log.promocional_id = bono_interno.bono_id) ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

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
		$sql = 'DELETE FROM promocional_log';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}






    /**
     * Obtener todos los registros donde se encuentre que
     * la columna productoId sea igual al valor pasado como parámetro
     *
     * @param String $value productoId requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByUsuarioId($value){
		$sql = 'SELECT * FROM promocional_log WHERE usuario_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna productoId sea igual al valor pasado como parámetro
     *
     * @param String $value productoId requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByUsucreaId($value){
		$sql = 'SELECT * FROM promocional_log WHERE usucrea_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna productoId sea igual al valor pasado como parámetro
     *
     * @param String $value productoId requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByUsumodifId($value){
		$sql = 'SELECT * FROM promocional_log WHERE usumodif_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna productoId sea igual al valor pasado como parámetro
     *
     * @param String $value productoId requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByFechaCrea($value){
		$sql = 'SELECT * FROM promocional_log WHERE fecha_crea = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna productoId sea igual al valor pasado como parámetro
     *
     * @param String $value productoId requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByFechaModif($value){
		$sql = 'SELECT * FROM promocional_log WHERE fecha_modif = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna productoId sea igual al valor pasado como parámetro
     *
     * @param String $value productoId requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByPromocionalIdAndVersion($promocionalId,$version){
        $sql = 'SELECT * FROM promocional_log WHERE promocional_id = ? AND version=?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($promocionalId);
        $sqlQuery->set($version);
        return $this->getList($sqlQuery);
    }





    /**
    * Realizar una consulta en la tabla de PromocionalLog 'PromocionalLog'
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
    public function queryPromocionalLogsCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
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


        $sql = 'SELECT count(*) count FROM promocional_log ' . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT ' .$select .'  FROM promocional_log ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }





    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna usuarioId sea igual al valor pasado como parámetro
     *
     * @param String $value usuarioId requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByNombre($value){
		$sql = 'DELETE FROM promocional_log WHERE usuarioId = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna usucrea_id sea igual al valor pasado como parámetro
     *
     * @param String $value usucrea_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByUsucreaId($value){
		$sql = 'DELETE FROM promocional_log WHERE usucrea_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna usumodif_id sea igual al valor pasado como parámetro
     *
     * @param String $value usumodif_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByUsumodifId($value){
		$sql = 'DELETE FROM promocional_log WHERE usumodif_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna fecha_crea sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_crea requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByFechaCrea($value){
		$sql = 'DELETE FROM promocional_log WHERE fecha_crea = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna fecha_modif sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_modif requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByFechaModif($value){
		$sql = 'DELETE FROM promocional_log WHERE fecha_modif = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}


	


    /**
     * Crear y devolver un objeto del tipo PromocionalLog
     * con los valores de una consulta sql
     * 
     *  
     * @param Arreglo $row arreglo asociativo
     *
     * @return Object $PromocionalLog PromocionalLog
     *
     * @access protected
     *
     */
	protected function readRow($row){
		$PromocionalLog = new PromocionalLog();
		
		$PromocionalLog->promologId = $row['promolog_id'];
		$PromocionalLog->usuarioId = $row['usuario_id'];
		$PromocionalLog->promocionalId = $row['promocional_id'];
		$PromocionalLog->valor = $row['valor'];

		$PromocionalLog->valorPromocional= $row['valor_promocional'];
        $PromocionalLog->valorBase= $row['valor_base'];
        $PromocionalLog->estado= $row['estado'];
        $PromocionalLog->errorId= $row['error_id'];
        $PromocionalLog->idExterno= $row['id_externo'];
        $PromocionalLog->mandante= $row['mandante'];
        $PromocionalLog->version= $row['version'];

		$PromocionalLog->usucreaId = $row['usucrea_id'];
		$PromocionalLog->usumodifId = $row['usumodif_id'];
		$PromocionalLog->fechaCrea = $row['fecha_crea'];
        $PromocionalLog->fechaModif = $row['fecha_modif'];
        $PromocionalLog->apostado = $row['apostado'];
        $PromocionalLog->rollowerRequerido = $row['rollower_requerido'];
        $PromocionalLog->codigo = $row['codigo'];

		return $PromocionalLog;
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