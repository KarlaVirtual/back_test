<?php namespace Backend\mysql;
use Backend\dao\CupoLogDAO;
use Backend\dto\CupoLog;
use Backend\dto\Helpers;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Psr\Log\NullLogger;

/**
* Clase 'CupoLogMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'CupoLog'
* 
* Ejemplo de uso: 
* $CupoLogMySqlDAO = new CupoLogMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class CupoLogMySqlDAO implements CupoLogDAO{

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
     * @param String $cupolog_id llave primaria
     *
     * @return Array resultado de la consulta
     *
     */
	public function load($id){
		$sql = 'SELECT * FROM cupo_log WHERE cupolog_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($id);
		return $this->getRow($sqlQuery);
	}



    /**
     * Consulta registros en la tabla `cupo_log` por el ID de transacción.
     *
     * @param string $transaction_id El ID de la transacción a buscar.
     * @return array Lista de registros que coinciden con el ID de transacción.
     */
    public function queryByTransactionId($transaction_id){
        $sql = 'SELECT * FROM cupo_log WHERE numero_transaccion = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($transaction_id);
        return $this->getList($sqlQuery);
    }






    /**
     * Obtener todos los registros de la base datos
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryAll(){
		$sql = 'SELECT * FROM cupo_log';
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
		$sql = 'SELECT * FROM cupo_log ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $cupolog_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function delete($cupolog_id){
		$sql = 'DELETE FROM cupo_log WHERE cupolog_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($cupolog_id);
		return $this->executeUpdate($sqlQuery);
	}
	
    /**
     * Insertar un registro en la base de datos
     *
     * @param Object cuentaCobro cuentaCobro
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($cupoLog){
		$sql = 'INSERT INTO cupo_log (usuario_id, fecha_crea, tipo_id, valor, usucrea_id, mandante, tipocupo_id,observacion,recarga_id,numero_transaccion,nombre_banco2) VALUES (?, ?, ?, ?, ?, ?, ?,?,?,?,?)';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($cupoLog->usuarioId);
		$sqlQuery->set($cupoLog->fechaCrea);
		$sqlQuery->set($cupoLog->tipoId);
		$sqlQuery->set($cupoLog->valor);
		$sqlQuery->set($cupoLog->usucreaId);
		$sqlQuery->set($cupoLog->mandante);
        $sqlQuery->set($cupoLog->tipocupoId);
        $sqlQuery->set($cupoLog->observacion);
        if($cupoLog->recargaId =='' || $cupoLog->recargaId == null){
            $cupoLog->recargaId='0';
        }
        $sqlQuery->set($cupoLog->recargaId);

        if($cupoLog->numeroTransaccion == ""){
            $sqlQuery->set("0");
        } else{
            $sqlQuery->set($cupoLog->numeroTransaccion);
        }

        if($cupoLog->nombreBanco2 == ""){
            $sqlQuery->set("null");
        }else{
            $sqlQuery->set($cupoLog->nombreBanco2);
        }

		$id = $this->executeInsert($sqlQuery);	
		$cupoLog->cupologId = $id;
		return $id;
	}
	
    /**
     * Editar un registro en la base de datos
     *
     * @param Object cuentaCobro CuentaCobro
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($cupoLog){
		$sql = 'UPDATE cupo_log SET usuario_id = ?, fecha_crea = ?, tipo_id = ?, valor = ?, usucrea_id = ?, mandante = ?, tipocupo_id = ?, observacion = ?, recarga_id = ?,numero_transaccion = ?,nombre_banco2 = ? WHERE cupolog_id = ?';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($cupoLog->usuarioId);
		$sqlQuery->set($cupoLog->fechaCrea);
		$sqlQuery->set($cupoLog->tipoId);
		$sqlQuery->set($cupoLog->valor);
		$sqlQuery->set($cupoLog->usucreaId);
		$sqlQuery->set($cupoLog->mandante);
        $sqlQuery->set($cupoLog->tipocupoId);
        $sqlQuery->set($cupoLog->observacion);
        if($cupoLog->recargaId =='' || $cupoLog->recargaId == null){
            $cupoLog->recargaId='0';
        }

        $sqlQuery->set($cupoLog->recargaId);

        if($cupoLog->numeroTransaccion == ""){
            $sqlQuery->set("Null");
        }else{
            $sqlQuery->set($cupoLog->numeroTransaccion);
        }
        $sqlQuery->set($cupoLog->nombreBanco2);
		$sqlQuery->set($cupoLog->cupologId);
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
		$sql = 'DELETE FROM cupo_log';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
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
		$sql = 'SELECT * FROM cupo_log WHERE usuario_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna fecha_crea sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_crea requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByFechaCrea($value){
		$sql = 'SELECT * FROM cupo_log WHERE fecha_crea = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna tipo_id sea igual al valor pasado como parámetro
     *
     * @param String $value tipo_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByTipoId($value){
		$sql = 'SELECT * FROM cupo_log WHERE tipo_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna valor sea igual al valor pasado como parámetro
     *
     * @param String $value valor requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByValor($value){
		$sql = 'SELECT * FROM cupo_log WHERE valor = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna usucrea_id sea igual al valor pasado como parámetro
     *
     * @param String $value usucrea_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByUsucreaId($value){
		$sql = 'SELECT * FROM cupo_log WHERE usucrea_id = ?';
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
		$sql = 'SELECT * FROM cupo_log WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna tipocupo_id sea igual al valor pasado como parámetro
     *
     * @param String $value tipocupo_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByTipocupoId($value){
		$sql = 'SELECT * FROM cupo_log WHERE tipocupo_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}






    /**
    * Realizar una consulta en la tabla de CupoLogs 'CupoLog'
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
    public function queryCupoLogsCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping)
    {


        $where = " where 1=1 ";

        $Helpers = new Helpers();

        if($searchOn) {
            // Construye el where
            $filters = json_decode($filters);
            $whereArray = array();
            $rules = $filters->rules;
            $groupOperation = $filters->groupOp;
            $cont = 0;

            foreach($rules as $rule)
            {
                $fieldName = $Helpers->set_custom_field($rule->field);
                $fieldData = $rule->data;
                

                $Helpers = new Helpers();

                if($fieldName == 'registro.cedula'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_DOCUMENT']);
                }
                if($fieldName == 'registro.celular'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_PHONE']);
                }
                if($fieldName == 'usuario.cedula'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_DOCUMENT']);
                }
                if($fieldName == 'usuario.login'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }
                if($fieldName == 'usuario_mandante.email'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }
                if($fieldName == 'punto_venta.email'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }
                if($fieldName == 'usuario_sitebuilder.login'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }
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


        if($grouping != ""){
            $where = $where . " GROUP BY " . $grouping;
        }

        $sql = "SELECT count(*) count FROM cupo_log INNER JOIN usuario ON (cupo_log.usuario_id=usuario.usuario_id) LEFT OUTER JOIN usuario usuario2 ON (cupo_log.usucrea_id=usuario2.usuario_id) LEFT OUTER JOIN usuario_perfil usuario_perfil2 ON (usuario_perfil2.usuario_id=usuario2.usuario_id) LEFT OUTER JOIN concesionario ON (concesionario.usuhijo_id = usuario.usuario_id AND prodinterno_id=0 AND concesionario.estado='A') LEFT OUTER JOIN usuario_perfil  ON (usuario.puntoventa_id = usuario_perfil.usuario_id)  " . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
        $sql = "SELECT ".$select." FROM cupo_log INNER JOIN usuario ON (cupo_log.usuario_id=usuario.usuario_id) LEFT OUTER JOIN usuario usuario2 ON (cupo_log.usucrea_id=usuario2.usuario_id) LEFT OUTER JOIN usuario_perfil usuario_perfil2 ON (usuario_perfil2.usuario_id=usuario2.usuario_id) LEFT OUTER JOIN concesionario ON (concesionario.usuhijo_id = usuario.usuario_id AND prodinterno_id=0 AND concesionario.estado='A') LEFT OUTER JOIN usuario_perfil  ON (usuario.puntoventa_id = usuario_perfil.usuario_id)   " . $where ." " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        if($_ENV["debugFixed2"] == '1'){
            print_r($sql);
        }

        $sqlQuery = new SqlQuery($sql);

        $result = $Helpers->process_data($this->execute2($sqlQuery));

        $json = '{ "count" : '.json_encode($count). ', "data" : '.json_encode($result).'}';


        return  $json;
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
		$sql = 'DELETE FROM cupo_log WHERE usuario_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
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
		$sql = 'DELETE FROM cupo_log WHERE fecha_crea = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna tipo_id sea igual al valor pasado como parámetro
     *
     * @param String $value tipo_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByTipoId($value){
		$sql = 'DELETE FROM cupo_log WHERE tipo_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna valor sea igual al valor pasado como parámetro
     *
     * @param String $value valor requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByValor($value){
		$sql = 'DELETE FROM cupo_log WHERE valor = ?';
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
		$sql = 'DELETE FROM cupo_log WHERE usucrea_id = ?';
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
		$sql = 'DELETE FROM cupo_log WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna tipocupo_id sea igual al valor pasado como parámetro
     *
     * @param String $value tipocupo_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByTipocupoId($value){
		$sql = 'DELETE FROM cupo_log WHERE tipocupo_id = ?';
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
     * @return Object $CupoLog CupoLog
     *
     * @access protected
     *
     */
	protected function readRow($row){
		$cupoLog = new CupoLog();
		
		$cupoLog->cupologId = $row['cupolog_id'];
		$cupoLog->usuarioId = $row['usuario_id'];
		$cupoLog->fechaCrea = $row['fecha_crea'];
		$cupoLog->tipoId = $row['tipo_id'];
		$cupoLog->valor = $row['valor'];
		$cupoLog->usucreaId = $row['usucrea_id'];
		$cupoLog->mandante = $row['mandante'];
        $cupoLog->tipocupoId = $row['tipocupo_id'];
        $cupoLog->observacion = $row['observacion'];
        $cupoLog->recargaId = $row['recarga_id'];
        $cupoLog->numeroTransaccion = $row['numero_transaccion'];
        $cupoLog->nombreBanco2 = $row['nombre_banco2'];


		return $cupoLog;
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