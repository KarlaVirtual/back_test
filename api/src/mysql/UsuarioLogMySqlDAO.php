<?php namespace Backend\mysql;
use Backend\dao\UsuarioLogDAO;
use Backend\dto\Helpers;
use Backend\dto\UsuarioLog;
use Backend\sql\QueryExecutor;
use Backend\sql\SqlQuery;
use Backend\sql\Transaction;
/** 
* Clase 'UsuarioLogMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'UsuarioLog'
* 
* Ejemplo de uso: 
* $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
* 
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class UsuarioLogMySqlDAO implements UsuarioLogDAO
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
		$sql = 'SELECT * FROM usuario_log WHERE usuariolog_id = ?';
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
        $sql = 'SELECT * FROM usuario_log WHERE usuariolog_id = ? and estadod = ? ';
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
		$sql = 'SELECT * FROM usuario_log';
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
		$sql = 'SELECT * FROM usuario_log ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $usuariolog_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function delete($usuariolog_id){
		$sql = 'DELETE FROM usuario_log WHERE usuariolog_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($usuariolog_id);
		return $this->executeUpdate($sqlQuery);
	}
	
    /**
     * Insertar un registro en la base de datos
     *
     * @param Objeto UsuarioLog UsuarioLog
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($UsuarioLog){
		$sql = 'INSERT INTO usuario_log (usuario_id,usuarioaprobar_id,usuariosolicita_id,usuariosolicita_ip,usuario_ip,usuarioaprobar_ip, tipo,estado, valor_antes,valor_despues, usucrea_id, usumodif_id,dispositivo,soperativo,sversion,imagen) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($UsuarioLog->usuarioId);
        $sqlQuery->setNumber($UsuarioLog->usuarioaprobarId);
        $sqlQuery->setNumber($UsuarioLog->usuariosolicitaId);
        $sqlQuery->set($UsuarioLog->usuariosolicitaIp);

        $sqlQuery->set($UsuarioLog->usuarioIp);
        $sqlQuery->set($UsuarioLog->usuarioaprobarIp);
        $sqlQuery->set($UsuarioLog->tipo);
        $sqlQuery->set($UsuarioLog->estado);
        $sqlQuery->set($UsuarioLog->valorAntes);
        $sqlQuery->set($UsuarioLog->valorDespues);
		$sqlQuery->setNumber($UsuarioLog->usucreaId);
        $sqlQuery->setNumber($UsuarioLog->usumodifId);
        $sqlQuery->set($UsuarioLog->dispositivo);
        $sqlQuery->set($UsuarioLog->soperativo);
        $sqlQuery->set($UsuarioLog->sversion);
        if($UsuarioLog->imagen != ''){
            $sqlQuery->setSIN("'".$UsuarioLog->imagen."'");
        }else{
            $sqlQuery->set("''");

        }

		$id = $this->executeInsert($sqlQuery);	
		$UsuarioLog->usuariologId = $id;
		return $id;
	}
	
    /**
     * Editar un registro en la base de datos
     *
     * @param Objeto UsuarioLog UsuarioLog
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($UsuarioLog){
		$sql = 'UPDATE usuario_log SET usuario_id = ?, usuarioaprobar_id = ?, usuariosolicita_id = ?, usuariosolicita_ip = ?,usuario_ip = ?, usuarioaprobar_ip = ?, tipo = ?, estado = ?, valor_antes = ?, valor_despues = ?, usucrea_id = ?, usumodif_id = ?,dispositivo=?,soperativo=?,sversion=? WHERE usuariolog_id = ?';
		$sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($UsuarioLog->usuarioId);
        $sqlQuery->setNumber($UsuarioLog->usuarioaprobarId);
        $sqlQuery->setNumber($UsuarioLog->usuariosolicitaId);
        $sqlQuery->set($UsuarioLog->usuariosolicitaIp);
        $sqlQuery->set($UsuarioLog->usuarioIp);
        $sqlQuery->set($UsuarioLog->usuarioaprobarIp);
        $sqlQuery->set($UsuarioLog->tipo);
        $sqlQuery->set($UsuarioLog->estado);
        $sqlQuery->set($UsuarioLog->valorAntes);
        $sqlQuery->set($UsuarioLog->valorDespues);
		$sqlQuery->setNumber($UsuarioLog->usucreaId);
		$sqlQuery->setNumber($UsuarioLog->usumodifId);
        $sqlQuery->set($UsuarioLog->dispositivo);
        $sqlQuery->set($UsuarioLog->soperativo);
        $sqlQuery->set($UsuarioLog->sversion);

		$sqlQuery->setNumber($UsuarioLog->usuariologId);
		return $this->executeUpdate($sqlQuery);
	}





    /**
    * Realizar una consulta en la tabla de UsuarioLog 'UsuarioLog'
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
    public function queryUsuarioLogsCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {


        $where = " where 1=1 ";

        $Helpers = new Helpers();

        if ($searchOn) {
            // Construye el where
            $filters = json_decode($filters);
            $whereArray = array();
            $rules = $filters->rules;
            $groupOperation = $filters->groupOp;
            $cont = 0;

            foreach ($rules as $rule) {
                $fieldName = $Helpers->set_custom_field($rule->field);
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
                    $where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
                } else {
                    $where = "";
                }
            }

        }


        $sql = 'SELECT count(*) count 
              FROM usuario_log inner join time_dimension 
                        on (time_dimension.dbtimestamp = usuario_log.fecha_crea) LEFT OUTER JOIN usuario ON (usuario.usuario_id=usuario_log.usuario_id) LEFT OUTER JOIN pais ON (usuario.pais_id=pais.pais_id)  LEFT OUTER JOIN clasificador ON (clasificador.clasificador_id=usuario_log.tipo)  ' . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT ' .$select .'  FROM usuario_log 
              LEFT OUTER JOIN usuario ON (usuario.usuario_id=usuario_log.usuario_id) LEFT OUTER JOIN pais ON (usuario.pais_id=pais.pais_id) LEFT OUTER JOIN clasificador ON (clasificador.clasificador_id=usuario_log.tipo)  ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        for($i=0;$i<oldCount($result);$i++){
            $tmp = $result[$i];
            if($result[$i]['usuario_log.imagen'] != ""){
                $image_data=($result[$i]['usuario_log.imagen']);
                $tmp['usuario_log.imagen'] = base64_encode($image_data);
                $tmp[17] = base64_encode($image_data);


            }
            $result[$i]=$tmp;

        }
        
        $result = $Helpers->process_data($result);
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
		$sql = 'DELETE FROM usuario_log';
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
		$sql = 'SELECT * FROM usuario_log WHERE usuario_id = ?';
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
		$sql = 'SELECT * FROM usuario_log WHERE usucrea_id = ?';
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
		$sql = 'SELECT * FROM usuario_log WHERE usumodif_id = ?';
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
		$sql = 'SELECT * FROM usuario_log WHERE fecha_crea = ?';
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
		$sql = 'SELECT * FROM usuario_log WHERE fecha_modif = ?';
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
		$sql = 'DELETE FROM usuario_log WHERE usuarioId = ?';
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
		$sql = 'DELETE FROM usuario_log WHERE usucrea_id = ?';
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
		$sql = 'DELETE FROM usuario_log WHERE usumodif_id = ?';
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
		$sql = 'DELETE FROM usuario_log WHERE fecha_crea = ?';
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
		$sql = 'DELETE FROM usuario_log WHERE fecha_modif = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}


	






    /**
     * Crear y devolver un objeto del tipo UsuarioLog
     * con los valores de una consulta sql
     * 
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Objeto $UsuarioLog UsuarioLog
     *
     * @access protected
     *
     */
	protected function readRow($row){
		$UsuarioLog = new UsuarioLog();
		
		$UsuarioLog->usuariologId = $row['usuariolog_id'];
        $UsuarioLog->usuarioId = $row['usuario_id'];
        $UsuarioLog->usuarioaprobarId = $row['usuarioaprobar_id'];
        $UsuarioLog->usuariosolicitaId = $row['usuariosolicita_id'];
        $UsuarioLog->usuariosolicitaIp = $row['usuariosolicita_ip'];

        $UsuarioLog->usuarioIp = $row['usuario_ip'];
        $UsuarioLog->usuarioaprobarIp = $row['usuarioaprobar_ip'];
        $UsuarioLog->tipo = $row['tipo'];
        $UsuarioLog->estado = $row['estado'];
        $UsuarioLog->valorAntes = $row['valor_antes'];
        $UsuarioLog->valorDespues = $row['valor_despues'];
		$UsuarioLog->usucreaId = $row['usucrea_id'];
		$UsuarioLog->usumodifId = $row['usumodif_id'];
		$UsuarioLog->fechaCrea = $row['fecha_crea'];
		$UsuarioLog->fechaModif = $row['fecha_modif'];
        $UsuarioLog->dispositivo = $row['dispositivo'];
        $UsuarioLog->soperativo = $row['soperativo'];
        $UsuarioLog->sversion = $row['sversion'];
        $UsuarioLog->imagen = $row['imagen'];

		return $UsuarioLog;
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



    public function querySQL($sql)
    {
        $sqlQuery = new SqlQuery($sql);
        return $this->execute2($sqlQuery);
    }

}
?>
