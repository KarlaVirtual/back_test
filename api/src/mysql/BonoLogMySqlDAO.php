<?php namespace Backend\mysql;
use Backend\dao\BonoLogDAO;
use Backend\dto\BonoLog;
use Backend\dto\Helpers;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Exception;
/**
* Clase 'BonoLogMySqlDAO'
*
* Esta clase provee las consultas del modelo o tabla 'BonoLog'
*
* Ejemplo de uso:
* $BonoLogMySqlDAO = new BonoLogMySqlDAO();
*
*
* @package ninguno
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public
* @see no
*
*/
class BonoLogMySqlDAO implements BonoLogDAO{

    /**
     * Atributo Transaction transacción
     *
     * @var Objeto
     */
    private $transaction;

    /**
     * @return mixed
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * @param mixed $transaction
     */
    public function setTransaction($transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * TransaccionBannerMySqlDAO constructor.
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
    }/**
     * Obtener el registro condicionado por la
     * llave primaria que se pasa como parámetro
     *
     * @param String $id llave primaria
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function load($id){
		$sql = 'SELECT * FROM bono_log WHERE bonolog_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($id);
		return $this->getRow($sqlQuery);
	}

    /**
     * Obtener todos los registros de la base datos
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryAll(){
		$sql = 'SELECT * FROM bono_log';
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
		$sql = 'SELECT * FROM bono_log ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $bonolog_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function delete($bonolog_id){
		$sql = 'DELETE FROM bono_log WHERE bonolog_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($bonolog_id);
		return $this->executeUpdate($sqlQuery);
	}
	
    /**
     * Insertar un registro en la base de datos
     *
     * @param Objeto BonoLog bonoLog
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($bonoLog){
		$sql = 'INSERT INTO bono_log (usuario_id, tipo, valor, fecha_crea, estado, error_id, id_externo, mandante, fecha_cierre, transaccion_id,tipobono_id,tiposaldo_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($bonoLog->usuarioId);
		$sqlQuery->set($bonoLog->tipo);
		$sqlQuery->set($bonoLog->valor);
		$sqlQuery->set($bonoLog->fechaCrea);
		$sqlQuery->set($bonoLog->estado);
		$sqlQuery->set($bonoLog->errorId);
		$sqlQuery->set($bonoLog->idExterno);
		$sqlQuery->set($bonoLog->mandante);

        if($bonoLog->fechaCierre == ''){
            $sqlQuery->setSIN('null');
        }else{
            $sqlQuery->set($bonoLog->fechaCierre);
        }




        $sqlQuery->set($bonoLog->transaccionId);
        $sqlQuery->set($bonoLog->tipobonoId);

        if($bonoLog->tiposaldoId == ""){
            $bonoLog->tiposaldoId=1;
        }

        $sqlQuery->set($bonoLog->tiposaldoId);

		$id = $this->executeInsert($sqlQuery);	
		$bonoLog->bonologId = $id;
		return $id;
	}
	
    /**
     * Editar un registro en la base de datos
     *
     * @param Objeto BonoLog bonoLog
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($bonoLog){
		$sql = 'UPDATE bono_log SET usuario_id = ?, tipo = ?, valor = ?, fecha_crea = ?, estado = ?, error_id = ?, id_externo = ?, mandante = ?, fecha_cierre = ?, transaccion_id = ?, tipobono_id = ?, tiposaldo_id = ? WHERE bonolog_id = ?';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($bonoLog->usuarioId);
		$sqlQuery->set($bonoLog->tipo);
		$sqlQuery->set($bonoLog->valor);
		$sqlQuery->set($bonoLog->fechaCrea);
		$sqlQuery->set($bonoLog->estado);
		$sqlQuery->set($bonoLog->errorId);
		$sqlQuery->set($bonoLog->idExterno);
		$sqlQuery->set($bonoLog->mandante);


        if($bonoLog->fechaCierre == ''){
            $sqlQuery->setSIN('null');
        }else{
            $sqlQuery->set($bonoLog->fechaCierre);
        }


        $sqlQuery->set($bonoLog->transaccionId);
        $sqlQuery->set($bonoLog->tipobonoId);

        if($bonoLog->tiposaldoId == ""){
            $bonoLog->tiposaldoId=1;
        }
        $sqlQuery->set($bonoLog->tiposaldoId);

        $sqlQuery->set($bonoLog->bonologId);
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
		$sql = 'DELETE FROM bono_log';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}





    /**
     * Obtener todos los registros donde se encuentre que
     * la columna UsuarioId sea igual al valor pasado como parámetro
     *
     * @param String $value UsuarioId requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByUsuarioId($value){
		$sql = 'SELECT * FROM bono_log WHERE usuario_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna Tipo sea igual al valor pasado como parámetro
     *
     * @param String $value Tipo requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByTipo($value){
		$sql = 'SELECT * FROM bono_log WHERE tipo = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna Valor sea igual al valor pasado como parámetro
     *
     * @param String $value Valor requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByValor($value){
		$sql = 'SELECT * FROM bono_log WHERE valor = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna FechaCrea sea igual al valor pasado como parámetro
     *
     * @param String $value FechaCrea requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByFechaCrea($value){
		$sql = 'SELECT * FROM bono_log WHERE fecha_crea = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna Estado sea igual al valor pasado como parámetro
     *
     * @param String $value Estado requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByEstado($value){
		$sql = 'SELECT * FROM bono_log WHERE estado = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna ErrorId sea igual al valor pasado como parámetro
     *
     * @param String $value ErrorId requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByErrorId($value){
		$sql = 'SELECT * FROM bono_log WHERE error_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna IdExterno sea igual al valor pasado como parámetro
     *
     * @param String $value IdExterno requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByIdExterno($value){
		$sql = 'SELECT * FROM bono_log WHERE id_externo = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna Mandante sea igual al valor pasado como parámetro
     *
     * @param String $value Mandante requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByMandante($value){
		$sql = 'SELECT * FROM bono_log WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna FechaCierra sea igual al valor pasado como parámetro
     *
     * @param String $value FechaCierra requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByFechaCierre($value){
		$sql = 'SELECT * FROM bono_log WHERE fecha_cierre = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna TransaccionId sea igual al valor pasado como parámetro
     *
     * @param String $value TransaccionId requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByTransaccionId($value){
		$sql = 'SELECT * FROM bono_log WHERE transaccion_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
    * Realizar una consulta en la tabla de bonos 'BonoLogs'
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
    * @return Array $json resultado de la consulta
    *
    */
    public function queryBonoLogsCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping)
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



        $sql = "SELECT count(*) count FROM bono_log  INNER JOIN usuario ON (usuario.usuario_id = bono_log.usuario_id) LEFT OUTER JOIN usuario_bono ON (usuario_bono.usubono_id = bono_log.id_externo AND bono_log.tipo IN ('F','PD','D')) LEFT OUTER JOIN bono_interno ON (bono_interno.bono_id = usuario_bono.bono_id) " . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        if($grouping != ""){
            $where = $where . " GROUP BY " . $grouping;
        }
        $sql = "SELECT ".$select." FROM bono_log  INNER JOIN usuario ON (usuario.usuario_id = bono_log.usuario_id) LEFT OUTER JOIN usuario_bono ON (usuario_bono.usubono_id = bono_log.id_externo AND bono_log.tipo IN ('F','PD','D')) LEFT OUTER JOIN bono_interno ON (bono_interno.bono_id = usuario_bono.bono_id)  " . $where ." " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

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
     * la columna UsuarioId sea igual al valor pasado como parámetro
     *
     * @param String $value UsuarioId requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByUsuarioId($value){
		$sql = 'DELETE FROM bono_log WHERE usuario_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna Tipo sea igual al valor pasado como parámetro
     *
     * @param String $value Tipo requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByTipo($value){
		$sql = 'DELETE FROM bono_log WHERE tipo = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna Valor sea igual al valor pasado como parámetro
     *
     * @param String $value Valor requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByValor($value){
		$sql = 'DELETE FROM bono_log WHERE valor = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna FechaCrea sea igual al valor pasado como parámetro
     *
     * @param String $value FechaCrea requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByFechaCrea($value){
		$sql = 'DELETE FROM bono_log WHERE fecha_crea = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna Estado sea igual al valor pasado como parámetro
     *
     * @param String $value Estado requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByEstado($value){
		$sql = 'DELETE FROM bono_log WHERE estado = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna ErrorId sea igual al valor pasado como parámetro
     *
     * @param String $value ErrorId requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByErrorId($value){
		$sql = 'DELETE FROM bono_log WHERE error_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna IdExterno sea igual al valor pasado como parámetro
     *
     * @param String $value IdExterno requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByIdExterno($value){
		$sql = 'DELETE FROM bono_log WHERE id_externo = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna Mandante sea igual al valor pasado como parámetro
     *
     * @param String $value Mandante requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByMandante($value){
		$sql = 'DELETE FROM bono_log WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna FechaCierre sea igual al valor pasado como parámetro
     *
     * @param String $value FechaCierre requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByFechaCierre($value){
		$sql = 'DELETE FROM bono_log WHERE fecha_cierre = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna TransaccionId sea igual al valor pasado como parámetro
     *
     * @param String $value TransaccionId requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByTransaccionId($value){
		$sql = 'DELETE FROM bono_log WHERE transaccion_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}





    /**
     * Crear y devolver un objeto del tipo BonoLog
     * con los valores de una consulta sql
     *
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Objeto $BonoLog BonoLog
     *
     * @access protected
     *
     */
	protected function readRow($row){
		$bonoLog = new BonoLog();
		
		$bonoLog->bonologId = $row['bonolog_id'];
		$bonoLog->usuarioId = $row['usuario_id'];
		$bonoLog->tipo = $row['tipo'];
		$bonoLog->valor = $row['valor'];
		$bonoLog->fechaCrea = $row['fecha_crea'];
		$bonoLog->estado = $row['estado'];
		$bonoLog->errorId = $row['error_id'];
		$bonoLog->idExterno = $row['id_externo'];
		$bonoLog->mandante = $row['mandante'];
		$bonoLog->fechaCierre = $row['fecha_cierre'];
		$bonoLog->transaccionId = $row['transaccion_id'];
        $bonoLog->tipobonoId = $row['tipobono_id'];

		return $bonoLog;
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
