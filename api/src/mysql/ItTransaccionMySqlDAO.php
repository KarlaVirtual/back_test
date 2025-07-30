<?php

namespace Backend\mysql;
use Backend\dao\ItTransaccionDAO;
use Backend\dto\ItTransaccion;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
/** 
* Clase 'ItTransaccionMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'ItTransaccion'
* 
* Ejemplo de uso: 
* $ItTransaccionMySqlDAO = new ItTransaccionMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class ItTransaccionMySqlDAO implements ItTransaccionDAO{

    /**
     * Obtener el registro condicionado por la 
     * llave primaria que se pasa como parámetro
     *
     * @param String $id llave primaria
     *
     * @return Array resultado de la consulta
     *
     */

    /**
     * Atributo Transaction transacción
     *
     * @var object
     */
    private $transaction;

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
    public function __construct($transaction = "")
    {
        if ($transaction == "") {
            $transaction = new Transaction();
            $this->transaction = $transaction;
        } else {
            $this->transaction = $transaction;
        }
    }

	public function load($id){
		$sql = 'SELECT * FROM it_transaccion WHERE it_cuentatrans_id = ?';
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
		$sql = 'SELECT * FROM it_transaccion';
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
		$sql = 'SELECT * FROM it_transaccion ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $it_cuentatrans_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function delete($it_cuentatrans_id){
		$sql = 'DELETE FROM it_transaccion WHERE it_cuentatrans_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($it_cuentatrans_id);
		return $this->executeUpdate($sqlQuery);
	}

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
     * Realizar una consulta en la tabla de ItTransaccion 'ItTransaccion'
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
    public function getItTransaccionsCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping)
    {


      /*  $where = " where 1=1 ";


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
                if (count($whereArray)>0)
                {
                    $where = $where . " " . $groupOperation . " UCASE(CAST(" . $fieldName . " AS CHAR)) " . strtoupper($fieldOperation);
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


        $sql = "SELECT count(*) count FROM it_transaccion      INNER JOIN transaccion_juego ON (transaccion_juego.transsport_id = transsportsbook_log.transsport_id) INNER JOIN transaccion_api ON (transaccion_juego.ticket_id = transaccion_api.identificador) " . $where;

        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
        $sql = "SELECT ".$select." FROM transsportsbook_log  INNER JOIN transaccion_juego ON (transaccion_juego.transsport_id = transsportsbook_log.transsport_id) INNER JOIN transaccion_api ON (transaccion_juego.ticket_id = transaccion_api.identificador) " . $where ." " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : '.json_encode($count). ', "data" : '.json_encode($result).'}';

        return  $json;*/
    }

    /**
     * Insertar un registro en la base de datos
     *
     * @param Object itTransaccion itTransaccion
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($itTransaccion){

        $sql = 'INSERT INTO it_transaccion (transaccion_id, ticket_id, valor, usuario_id, game_reference, bet_status, fecha_crea,
                            hora_crea, mandante, tipo, saldo_creditos, saldo_creditos_base, saldo_bonos, saldo_free,
                            fecha_crea_time) 
 VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';


        $sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($itTransaccion->transaccionId);
		$sqlQuery->set($itTransaccion->ticketId);
		$sqlQuery->set($itTransaccion->valor);
		$sqlQuery->set($itTransaccion->usuarioId);
		$sqlQuery->set($itTransaccion->gameReference);
		$sqlQuery->set($itTransaccion->betStatus);
		$sqlQuery->set(date('Y-m-d', time()));
		$sqlQuery->set(date('H:i:s', time()));
		$sqlQuery->set($itTransaccion->mandante);
		$sqlQuery->set($itTransaccion->tipo);
        $sqlQuery->set('0');
        $sqlQuery->set('0');
        $sqlQuery->set('0');
        $sqlQuery->set('0');
        $sqlQuery->set(date('Y-m-d H:i:s', time()));

		$id = $this->executeInsert($sqlQuery);	
		$itTransaccion->itCuentatransId = $id;
		return $id;
	}
	
    /**
     * Editar un registro en la base de datos
     *
     * @param Object itTransaccion itTransaccion
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($itTransaccion){
		$sql = 'UPDATE it_transaccion SET transaccion_id = ?, ticket_id = ?, valor = ?, usuario_id = ?, game_reference = ?, bet_status = ?, fecha_crea = ?, hora_crea = ?, mandante = ?, tipo = ? WHERE it_cuentatrans_id = ?';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($itTransaccion->transaccionId);
		$sqlQuery->set($itTransaccion->ticketId);
		$sqlQuery->set($itTransaccion->valor);
		$sqlQuery->set($itTransaccion->usuarioId);
		$sqlQuery->set($itTransaccion->gameReference);
		$sqlQuery->set($itTransaccion->betStatus);
		$sqlQuery->set($itTransaccion->fechaCrea);
		$sqlQuery->set($itTransaccion->horaCrea);
		$sqlQuery->set($itTransaccion->mandante);
		$sqlQuery->set($itTransaccion->tipo);

		$sqlQuery->set($itTransaccion->itCuentatransId);
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
		$sql = 'DELETE FROM it_transaccion';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna transaccion_id sea igual al valor pasado como parámetro
     *
     * @param String $value transaccion_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByTransaccionId($value){
		$sql = 'SELECT * FROM it_transaccion WHERE transaccion_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna ticket_id sea igual al valor pasado como parámetro
     *
     * @param String $value ticket_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByTicketId($value){
		$sql = 'SELECT * FROM it_transaccion WHERE ticket_id = ?';
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
		$sql = 'SELECT * FROM it_transaccion WHERE valor = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
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
		$sql = 'SELECT * FROM it_transaccion WHERE usuario_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna game_reference sea igual al valor pasado como parámetro
     *
     * @param String $value game_reference requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByGameReference($value){
		$sql = 'SELECT * FROM it_transaccion WHERE game_reference = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna bet_status sea igual al valor pasado como parámetro
     *
     * @param String $value bet_status requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByBetStatus($value){
		$sql = 'SELECT * FROM it_transaccion WHERE bet_status = ?';
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
		$sql = 'SELECT * FROM it_transaccion WHERE fecha_crea = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna hora_crea sea igual al valor pasado como parámetro
     *
     * @param String $value hora_crea requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByHoraCrea($value){
		$sql = 'SELECT * FROM it_transaccion WHERE hora_crea = ?';
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
		$sql = 'SELECT * FROM it_transaccion WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna tipo sea igual al valor pasado como parámetro
     *
     * @param String $value tipo requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByTipo($value){
		$sql = 'SELECT * FROM it_transaccion WHERE tipo = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}





    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna transaccion_id sea igual al valor pasado como parámetro
     *
     * @param String $value transaccion_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByTransaccionId($value){
		$sql = 'DELETE FROM it_transaccion WHERE transaccion_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna ticket_id sea igual al valor pasado como parámetro
     *
     * @param String $value ticket_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByTicketId($value){
		$sql = 'DELETE FROM it_transaccion WHERE ticket_id = ?';
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
		$sql = 'DELETE FROM it_transaccion WHERE valor = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
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
		$sql = 'DELETE FROM it_transaccion WHERE usuario_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna game_reference sea igual al valor pasado como parámetro
     *
     * @param String $value game_reference requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByGameReference($value){
		$sql = 'DELETE FROM it_transaccion WHERE game_reference = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna bet_status sea igual al valor pasado como parámetro
     *
     * @param String $value bet_status requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByBetStatus($value){
		$sql = 'DELETE FROM it_transaccion WHERE bet_status = ?';
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
		$sql = 'DELETE FROM it_transaccion WHERE fecha_crea = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna hora_crea sea igual al valor pasado como parámetro
     *
     * @param String $value hora_crea requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByHoraCrea($value){
		$sql = 'DELETE FROM it_transaccion WHERE hora_crea = ?';
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
		$sql = 'DELETE FROM it_transaccion WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna tipo sea igual al valor pasado como parámetro
     *
     * @param String $value tipo requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByTipo($value){
		$sql = 'DELETE FROM it_transaccion WHERE tipo = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}




	
    /**
     * Crear y devolver un objeto del tipo ItTransaccion
     * con los valores de una consulta sql
     * 
     *	
     * @param Arreglo $row arreglo asociativo
     *
     * @return Object $ItTransaccion ItTransaccion
     *
     * @access protected
     *
     */
	protected function readRow($row){
		$itTransaccion = new ItTransaccion();
		
		$itTransaccion->itCuentatransId = $row['it_cuentatrans_id'];
		$itTransaccion->transaccionId = $row['transaccion_id'];
		$itTransaccion->ticketId = $row['ticket_id'];
		$itTransaccion->valor = $row['valor'];
		$itTransaccion->usuarioId = $row['usuario_id'];
		$itTransaccion->gameReference = $row['game_reference'];
		$itTransaccion->betStatus = $row['bet_status'];
		$itTransaccion->fechaCrea = $row['fecha_crea'];
		$itTransaccion->horaCrea = $row['hora_crea'];
		$itTransaccion->mandante = $row['mandante'];
		$itTransaccion->tipo = $row['tipo'];

		return $itTransaccion;
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