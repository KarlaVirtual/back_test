<?php namespace Backend\mysql;
use Backend\dao\AuditoriaGeneralDAO;
use Backend\dto\AuditoriaGeneral;
use Backend\dto\Helpers;
use Backend\sql\QueryExecutor;
use Backend\sql\SqlQuery;
use Backend\sql\Transaction;
/** 
* Clase 'AuditoriaGeneralMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'AuditoriaGeneral'
* 
* Ejemplo de uso: 
* $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO();
* 
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class AuditoriaGeneralMySqlDAO implements AuditoriaGeneralDAO
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
		$sql = 'SELECT * FROM auditoria_general WHERE auditoriageneral_id = ?';
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
        $sql = 'SELECT * FROM auditoria_general WHERE auditoriageneral_id = ? and estado = ? ';
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
		$sql = 'SELECT * FROM auditoria_general';
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
		$sql = 'SELECT * FROM auditoria_general ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $auditoriageneral_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function delete($auditoriageneral_id){
		$sql = 'DELETE FROM auditoria_general WHERE auditoriageneral_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($auditoriageneral_id);
		return $this->executeUpdate($sqlQuery);
	}
	
    /**
     * Insertar un registro en la base de datos
     *
     * @param Objeto AuditoriaGeneral AuditoriaGeneral
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($AuditoriaGeneral){

		$sql = 'INSERT INTO auditoria_general (usuario_id,usuarioaprobar_id,usuariosolicita_id,usuariosolicita_ip,usuario_ip,usuarioaprobar_ip, tipo,estado, valor_antes,valor_despues, usucrea_id, usumodif_id,dispositivo,soperativo,sversion,imagen,observacion,data,campo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?)';
		$sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($AuditoriaGeneral->usuarioId);
        $sqlQuery->setNumber($AuditoriaGeneral->usuarioaprobarId);
        $sqlQuery->setNumber($AuditoriaGeneral->usuariosolicitaId);
        $sqlQuery->set($AuditoriaGeneral->usuariosolicitaIp);

        $sqlQuery->set($AuditoriaGeneral->usuarioIp);
        $sqlQuery->set($AuditoriaGeneral->usuarioaprobarIp);
        $sqlQuery->set($AuditoriaGeneral->tipo);
        $sqlQuery->set($AuditoriaGeneral->estado);
        $sqlQuery->set($AuditoriaGeneral->valorAntes);

        $AuditoriaGeneral->valorDespues = substr($AuditoriaGeneral->valorDespues, 0, 250);

        $sqlQuery->set($AuditoriaGeneral->valorDespues);
		$sqlQuery->setNumber($AuditoriaGeneral->usucreaId);
        $sqlQuery->setNumber($AuditoriaGeneral->usumodifId);
        $sqlQuery->set($AuditoriaGeneral->dispositivo);
        $sqlQuery->set($AuditoriaGeneral->soperativo);
        $sqlQuery->set($AuditoriaGeneral->sversion);
        $sqlQuery->set($AuditoriaGeneral->imagen);
        $sqlQuery->set($AuditoriaGeneral->observacion);
        $sqlQuery->set($AuditoriaGeneral->data);

        $sqlQuery->set($AuditoriaGeneral->campo);


		$id = $this->executeInsert($sqlQuery);	
		$AuditoriaGeneral->auditoriageneralId = $id;
		return $id;
	}
	
    /**
     * Editar un registro en la base de datos
     *
     * @param Objeto AuditoriaGeneral AuditoriaGeneral
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($AuditoriaGeneral){
		$sql = 'UPDATE auditoria_general SET usuario_id = ?, usuarioaprobar_id = ?, usuariosolicita_id = ?, usuariosolicita_ip = ?,usuario_ip = ?, usuarioaprobar_ip = ?, tipo = ?, estado = ?, valor_antes = ?, valor_despues = ?, usucrea_id = ?, usumodif_id = ?,dispositivo=?,soperativo=?,sversion=?, observacion = ? WHERE auditoriageneral_id = ?';
		$sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($AuditoriaGeneral->usuarioId);
        $sqlQuery->setNumber($AuditoriaGeneral->usuarioaprobarId);
        $sqlQuery->setNumber($AuditoriaGeneral->usuariosolicitaId);
        $sqlQuery->set($AuditoriaGeneral->usuariosolicitaIp);
        $sqlQuery->set($AuditoriaGeneral->usuarioIp);
        $sqlQuery->set($AuditoriaGeneral->usuarioaprobarIp);
        $sqlQuery->set($AuditoriaGeneral->tipo);
        $sqlQuery->set($AuditoriaGeneral->estado);
        $sqlQuery->set($AuditoriaGeneral->valorAntes);
        $sqlQuery->set($AuditoriaGeneral->valorDespues);
		$sqlQuery->setNumber($AuditoriaGeneral->usucreaId);
		$sqlQuery->setNumber($AuditoriaGeneral->usumodifId);
        $sqlQuery->set($AuditoriaGeneral->dispositivo);
        $sqlQuery->set($AuditoriaGeneral->soperativo);
        $sqlQuery->set($AuditoriaGeneral->sversion);
        $sqlQuery->set($AuditoriaGeneral->observacion);

		$sqlQuery->setNumber($AuditoriaGeneral->auditoriageneralId);
		return $this->executeUpdate($sqlQuery);
	}





    /**
    * Realizar una consulta en la tabla de AuditoriaGeneral 'AuditoriaGeneral'
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
    public function queryAuditoriaGeneralCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
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


        $sql = 'SELECT count(*) count FROM auditoria_general LEFT OUTER JOIN usuario ON (usuario.usuario_id=auditoria_general.usuario_id) LEFT OUTER JOIN pais ON (usuario.pais_id=pais.pais_id)  LEFT OUTER JOIN clasificador ON (clasificador.clasificador_id=auditoria_general.tipo)  ' . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT ' .$select .'  FROM auditoria_general LEFT OUTER JOIN usuario ON (usuario.usuario_id=auditoria_general.usuario_id) LEFT OUTER JOIN pais ON (usuario.pais_id=pais.pais_id) LEFT OUTER JOIN clasificador ON (clasificador.clasificador_id=auditoria_general.tipo)  ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);

        $result = $Helpers->process_data($this->execute2($sqlQuery));

        for($i=0;$i<oldCount($result);$i++){
            $tmp = $result[$i];
            if($result[$i]['auditoria_general.imagen'] != ""){
                $image_data=($result[$i]['auditoria_general.imagen']);
                $tmp['auditoria_general.imagen'] = base64_encode($image_data);
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
		$sql = 'DELETE FROM auditoria_general';
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
		$sql = 'SELECT * FROM auditoria_general WHERE usuario_id = ?';
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
		$sql = 'SELECT * FROM auditoria_general WHERE usucrea_id = ?';
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
		$sql = 'SELECT * FROM auditoria_general WHERE usumodif_id = ?';
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
		$sql = 'SELECT * FROM auditoria_general WHERE fecha_crea = ?';
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
		$sql = 'SELECT * FROM auditoria_general WHERE fecha_modif = ?';
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
		$sql = 'DELETE FROM auditoria_general WHERE usuarioId = ?';
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
		$sql = 'DELETE FROM auditoria_general WHERE usucrea_id = ?';
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
		$sql = 'DELETE FROM auditoria_general WHERE usumodif_id = ?';
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
		$sql = 'DELETE FROM auditoria_general WHERE fecha_crea = ?';
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
		$sql = 'DELETE FROM auditoria_general WHERE fecha_modif = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}


	






    /**
     * Crear y devolver un objeto del tipo AuditoriaGeneral
     * con los valores de una consulta sql
     * 
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Objeto $AuditoriaGeneral AuditoriaGeneral
     *
     * @access protected
     *
     */
	protected function readRow($row){
		$AuditoriaGeneral = new AuditoriaGeneral();
		
		$AuditoriaGeneral->auditoriageneralId = $row['auditoriageneral_id'];
        $AuditoriaGeneral->usuarioId = $row['usuario_id'];
        $AuditoriaGeneral->usuarioaprobarId = $row['usuarioaprobar_id'];
        $AuditoriaGeneral->usuariosolicitaId = $row['usuariosolicita_id'];
        $AuditoriaGeneral->usuariosolicitaIp = $row['usuariosolicita_ip'];

        $AuditoriaGeneral->usuarioIp = $row['usuario_ip'];
        $AuditoriaGeneral->usuarioaprobarIp = $row['usuarioaprobar_ip'];
        $AuditoriaGeneral->tipo = $row['tipo'];
        $AuditoriaGeneral->estado = $row['estado'];
        $AuditoriaGeneral->valorAntes = $row['valor_antes'];
        $AuditoriaGeneral->valorDespues = $row['valor_despues'];
		$AuditoriaGeneral->usucreaId = $row['usucrea_id'];
		$AuditoriaGeneral->usumodifId = $row['usumodif_id'];
		$AuditoriaGeneral->fechaCrea = $row['fecha_crea'];
		$AuditoriaGeneral->fechaModif = $row['fecha_modif'];
        $AuditoriaGeneral->dispositivo = $row['dispositivo'];
        $AuditoriaGeneral->soperativo = $row['soperativo'];
        $AuditoriaGeneral->sversion = $row['sversion'];
        $AuditoriaGeneral->imagen = $row['imagen'];
        $AuditoriaGeneral->observacion = $row['observacion'];

        $AuditoriaGeneral->data = $row['data'];
        $AuditoriaGeneral->campo = $row['campo'];

		return $AuditoriaGeneral;
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
