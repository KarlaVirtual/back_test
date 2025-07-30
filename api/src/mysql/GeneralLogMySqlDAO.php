<?php namespace Backend\mysql;
use Backend\dao\GeneralLogDAO;
use Backend\dto\GeneralLog;
use Backend\sql\QueryExecutor;
use Backend\sql\SqlQuery;
use Backend\sql\Transaction;
/** 
* Clase 'GeneralLogMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'GeneralLog'
* 
* Ejemplo de uso: 
* $GeneralLogMySqlDAO = new GeneralLogMySqlDAO();
* 
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class GeneralLogMySqlDAO implements GeneralLogDAO
{

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
		$sql = 'SELECT * FROM general_log WHERE generallog_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($id);
		return $this->getRow($sqlQuery);
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
    public function loadIdAndEstado($id,$estado){
        $sql = 'SELECT * FROM general_log WHERE generallog_id = ? and estado = ? ';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($id);
        $sqlQuery->setString($estado);
        return $this->getRow($sqlQuery);
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
    public function queryAll(){
		$sql = 'SELECT * FROM general_log';
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
		$sql = 'SELECT * FROM general_log ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $generallog_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function delete($generallog_id){
		$sql = 'DELETE FROM general_log WHERE generallog_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($generallog_id);
		return $this->executeUpdate($sqlQuery);
	}
	
    /**
     * Insertar un registro en la base de datos
     *
     * @param Objeto GeneralLog GeneralLog
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($GeneralLog){
		$sql = 'INSERT INTO general_log (usuario_id,usuarioaprobar_id,usuariosolicita_id,usuariosolicita_ip,usuario_ip,usuarioaprobar_ip, tipo,estado, valor_antes,valor_despues, usucrea_id, usumodif_id,dispositivo,soperativo,sversion,imagen,externo_id,campo,tabla,explicacion,mandante) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($GeneralLog->usuarioId);
        $sqlQuery->setNumber($GeneralLog->usuarioaprobarId);
        $sqlQuery->setNumber($GeneralLog->usuariosolicitaId);
        $sqlQuery->set($GeneralLog->usuariosolicitaIp);

        $sqlQuery->set($GeneralLog->usuarioIp);
        $sqlQuery->set($GeneralLog->usuarioaprobarIp);
        $sqlQuery->set($GeneralLog->tipo);
        $sqlQuery->set($GeneralLog->estado);
        $sqlQuery->set($GeneralLog->valorAntes);
        $sqlQuery->set($GeneralLog->valorDespues);
        if($_SESSION["usuario2"] != ''){
            $GeneralLog->usucreaId=$_SESSION["usuario2"];
        }
		$sqlQuery->setNumber($GeneralLog->usucreaId);
        if($_SESSION["usuario2"] != ''){
            $GeneralLog->usumodifId=$_SESSION["usuario2"];
        }
        $sqlQuery->setNumber($GeneralLog->usumodifId);

        $sqlQuery->set($GeneralLog->dispositivo);
        $sqlQuery->set($GeneralLog->soperativo);
        $sqlQuery->set($GeneralLog->sversion);
        $sqlQuery->set($GeneralLog->imagen);
        if($GeneralLog->externoId == ""){
            $GeneralLog->externoId=0;
        }
        $sqlQuery->set($GeneralLog->externoId);
        $sqlQuery->set($GeneralLog->campo);
        $sqlQuery->set($GeneralLog->tabla);
        $sqlQuery->set($GeneralLog->explicacion);

        if($GeneralLog->mandante == ""){
            $GeneralLog->mandante=-1;
        }
        $sqlQuery->set($GeneralLog->mandante);

		$id = $this->executeInsert($sqlQuery);	
		$GeneralLog->generallogId = $id;
		return $id;
	}
	
    /**
     * Editar un registro en la base de datos
     *
     * @param Objeto GeneralLog GeneralLog
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($GeneralLog){
		$sql = 'UPDATE general_log SET usuario_id = ?, usuarioaprobar_id = ?, usuariosolicita_id = ?, usuariosolicita_ip = ?,usuario_ip = ?, usuarioaprobar_ip = ?, tipo = ?, estado = ?, valor_antes = ?, valor_despues = ?, usucrea_id = ?, usumodif_id = ?,dispositivo=?,soperativo=?,sversion=?,externo_id=?,campo=?,tabla=?,explicacion=?,mandante=? WHERE generallog_id = ?';
		$sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($GeneralLog->usuarioId);
        $sqlQuery->setNumber($GeneralLog->usuarioaprobarId);
        $sqlQuery->setNumber($GeneralLog->usuariosolicitaId);
        $sqlQuery->set($GeneralLog->usuariosolicitaIp);
        $sqlQuery->set($GeneralLog->usuarioIp);
        $sqlQuery->set($GeneralLog->usuarioaprobarIp);
        $sqlQuery->set($GeneralLog->tipo);
        $sqlQuery->set($GeneralLog->estado);
        $sqlQuery->set($GeneralLog->valorAntes);
        $sqlQuery->set($GeneralLog->valorDespues);
        if($_SESSION["usuario2"] != ''){
            $GeneralLog->usucreaId=$_SESSION["usuario2"];
        }
		$sqlQuery->setNumber($GeneralLog->usucreaId);
        if($_SESSION["usuario2"] != ''){
            $GeneralLog->usumodifId=$_SESSION["usuario2"];
        }
		$sqlQuery->setNumber($GeneralLog->usumodifId);
        $sqlQuery->set($GeneralLog->dispositivo);
        $sqlQuery->set($GeneralLog->soperativo);
        $sqlQuery->set($GeneralLog->sversion);
        if($GeneralLog->externoId == ""){
            $GeneralLog->externoId=0;
        }
        $sqlQuery->set($GeneralLog->externoId);
        $sqlQuery->set($GeneralLog->campo);
        $sqlQuery->set($GeneralLog->tabla);
        $sqlQuery->set($GeneralLog->explicacion);

        if($GeneralLog->mandante == ""){
            $GeneralLog->mandante=-1;
        }
        $sqlQuery->set($GeneralLog->mandante);


        $sqlQuery->setNumber($GeneralLog->generallogId);
		return $this->executeUpdate($sqlQuery);
	}





    /**
    * Realizar una consulta en la tabla de GeneralLog 'GeneralLog'
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
    * @return Array $json resultado de la consulta
    *
    */
    public function queryGeneralLogsCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
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


        $sql = 'SELECT count(*) count FROM general_log LEFT OUTER JOIN usuario_mandante ON (usuario_mandante.usumandante_id=general_log.usuariosolicita_id)  ' . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT ' .$select .'  FROM general_log LEFT OUTER JOIN usuario_mandante ON (usuario_mandante.usumandante_id=general_log.usuariosolicita_id)  ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        for($i=0;$i<oldCount($result);$i++){
            $tmp = $result[$i];
            if($result[$i]['general_log.imagen'] != ""){
                $image_data=($result[$i]['general_log.imagen']);
                $tmp['general_log.imagen'] = base64_encode($image_data);
                $tmp[17] = base64_encode($image_data);


            }
            $result[$i]=$tmp;

        }
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
		$sql = 'DELETE FROM general_log';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}






    /**
     * Obtener todos los registros donde se encuentre que
     * la columna usuario_id sea igual al valor pasado como parámetro
     *
     * @param String $value usuario_id requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByUsuarioId($value){
		$sql = 'SELECT * FROM general_log WHERE usuario_id = ?';
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
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByUsucreaId($value){
		$sql = 'SELECT * FROM general_log WHERE usucrea_id = ?';
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
		$sql = 'SELECT * FROM general_log WHERE usumodif_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
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
	public function queryByFechaCrea($value){
		$sql = 'SELECT * FROM general_log WHERE fecha_crea = ?';
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
	public function queryByFechaModif($value){
		$sql = 'SELECT * FROM general_log WHERE fecha_modif = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}












    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna usuarioId sea igual al valor pasado como parámetro
     *
     * @param String $value usuarioId requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByNombre($value){
		$sql = 'DELETE FROM general_log WHERE usuarioId = ?';
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
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByUsucreaId($value){
		$sql = 'DELETE FROM general_log WHERE usucrea_id = ?';
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
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByUsumodifId($value){
		$sql = 'DELETE FROM general_log WHERE usumodif_id = ?';
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
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByFechaCrea($value){
		$sql = 'DELETE FROM general_log WHERE fecha_crea = ?';
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
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByFechaModif($value){
		$sql = 'DELETE FROM general_log WHERE fecha_modif = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}


	






    /**
     * Crear y devolver un objeto del tipo GeneralLog
     * con los valores de una consulta sql
     * 
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Objeto $GeneralLog GeneralLog
     *
     * @access protected
     *
     */
	protected function readRow($row){
		$GeneralLog = new GeneralLog();
		
		$GeneralLog->generallogId = $row['generallog_id'];
        $GeneralLog->usuarioId = $row['usuario_id'];
        $GeneralLog->usuarioaprobarId = $row['usuarioaprobar_id'];
        $GeneralLog->usuariosolicitaId = $row['usuariosolicita_id'];
        $GeneralLog->usuariosolicitaIp = $row['usuariosolicita_ip'];

        $GeneralLog->usuarioIp = $row['usuario_ip'];
        $GeneralLog->usuarioaprobarIp = $row['usuarioaprobar_ip'];
        $GeneralLog->tipo = $row['tipo'];
        $GeneralLog->estado = $row['estado'];
        $GeneralLog->valorAntes = $row['valor_antes'];
        $GeneralLog->valorDespues = $row['valor_despues'];
		$GeneralLog->usucreaId = $row['usucrea_id'];
		$GeneralLog->usumodifId = $row['usumodif_id'];
		$GeneralLog->fechaCrea = $row['fecha_crea'];
		$GeneralLog->fechaModif = $row['fecha_modif'];
        $GeneralLog->dispositivo = $row['dispositivo'];
        $GeneralLog->soperativo = $row['soperativo'];
        $GeneralLog->sversion = $row['sversion'];
        $GeneralLog->imagen = $row['imagen'];
        $GeneralLog->externoId = $row['externo_id'];
        $GeneralLog->campo = $row['campo'];
        $GeneralLog->tabla = $row['tabla'];
        $GeneralLog->explicacion = $row['explicacion'];
        $GeneralLog->mandante = $row['mandante'];


        return $GeneralLog;
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