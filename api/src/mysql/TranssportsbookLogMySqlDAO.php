<?php namespace Backend\mysql;
use Backend\dao\TranssportsbookLogDAO;
use Backend\dto\TranssportsbookLog;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
/** 
* Clase 'TranssportsbookLogMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'TranssportsbookLog'
* 
* Ejemplo de uso: 
* $TranssportsbookLogMySqlDAO = new TranssportsbookLogMySqlDAO();
* 
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class TranssportsbookLogMySqlDAO implements TranssportsbookLogDAO
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
	public function load($id)
	{
		$sql = 'SELECT * FROM transsportsbook_log WHERE transsportlog_id = ?';
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
	public function queryAll()
	{
		$sql = 'SELECT * FROM transsportsbook_log';
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
	public function queryAllOrderBy($orderColumn)
	{
		$sql = 'SELECT * FROM transsportsbook_log ORDER BY ' . $orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros condicionados
 	 * por la llave primaria
 	 *
 	 * @param String $transsportlog_id llave primaria
 	 *
	 * @return boolean $ resultado de la consulta
     *
 	 */
	public function delete($transsportlog_id)
	{
		$sql = 'DELETE FROM transsportsbook_log WHERE transsportlog_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($transsportlog_id);
		return $this->executeUpdate($sqlQuery);
	}





   	/**
   	* Realizar una consulta en la tabla de TranssportsbookLog 'TranssportsbookLog'
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
    public function queryTransaccionesCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping)
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


        if($grouping != ""){
            $where = $where . " GROUP BY " . $grouping;
        }


        $sql = "SELECT count(*) count FROM transsportsbook_log      INNER JOIN transaccion_juego ON (transaccion_juego.transsport_id = transsportsbook_log.transsport_id) INNER JOIN transaccion_api ON (transaccion_juego.ticket_id = transaccion_api.identificador) " . $where;

        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
        $sql = "SELECT ".$select." FROM transsportsbook_log  INNER JOIN transaccion_juego ON (transaccion_juego.transsport_id = transsportsbook_log.transsport_id) INNER JOIN transaccion_api ON (transaccion_juego.ticket_id = transaccion_api.identificador) " . $where ." " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : '.json_encode($count). ', "data" : '.json_encode($result).'}';

        return  $json;
    }





	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param Objeto transsportsbookLog transsportsbookLog
 	 *
	 * @return String $id resultado de la consulta
     *
 	 */
	public function insert($transsportsbookLog)
	{
		$sql = 'INSERT INTO transsportsbook_log (transsport_id, tipo, transaccion_id, t_value,valor, usucrea_id, usumodif_id, usuario_id, game_reference, bet_status, mandante) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);

		$sqlQuery->setNumber($transsportsbookLog->transsportId);
		$sqlQuery->set($transsportsbookLog->tipo);
		$sqlQuery->setString($transsportsbookLog->transaccionId);
        $sqlQuery->set($transsportsbookLog->tValue);
        $sqlQuery->set($transsportsbookLog->valor);
		$sqlQuery->setNumber($transsportsbookLog->usucreaId);
        $sqlQuery->setNumber($transsportsbookLog->usumodifId);
        $sqlQuery->setNumber($transsportsbookLog->usuarioId);
        $sqlQuery->set($transsportsbookLog->gameReference);
        $sqlQuery->set($transsportsbookLog->betStatus);
        $sqlQuery->set($transsportsbookLog->mandante);

		$id = $this->executeInsert($sqlQuery);
		$transsportsbookLog->transsportlogId = $id;
		return $id;
	}

	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Objeto transsportsbookLog transsportsbookLog
 	 *
	 * @return boolean $ resultado de la consulta
     *
 	 */
	public function update($transsportsbookLog)
	{
		$sql = 'UPDATE transsportsbook_log SET transsport_id = ?, tipo = ?, transaccion_id = ?, t_value = ?, valor = ?,usucrea_id = ?, usumodif_id = ?, usuario_id = ?, game_reference = ?, bet_status = ?, mandante = ? WHERE transsportlog_id = ?';
		$sqlQuery = new SqlQuery($sql);

		$sqlQuery->setNumber($transsportsbookLog->transjuegoId);
		$sqlQuery->set($transsportsbookLog->tipo);
		$sqlQuery->setString($transsportsbookLog->transaccionId);
        $sqlQuery->set($transsportsbookLog->tValue);
        $sqlQuery->set($transsportsbookLog->valor);
		$sqlQuery->setNumber($transsportsbookLog->usucreaId);
		$sqlQuery->setNumber($transsportsbookLog->usumodifId);
        $sqlQuery->setNumber($transsportsbookLog->usuarioId);
        $sqlQuery->set($transsportsbookLog->gameReference);
        $sqlQuery->set($transsportsbookLog->betStatus);
        $sqlQuery->set($transsportsbookLog->mandante);

		$sqlQuery->setNumber($transsportsbookLog->transsportlogId);
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
	public function clean()
	{
		$sql = 'DELETE FROM transsportsbook_log';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}










	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna transsport_id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value transsport_id requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByTransjuegoId($value)
	{
		$sql = 'SELECT * FROM transsportsbook_log WHERE transsport_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * las columnas transsport_id y tipo son iguales a los valores 
 	 * pasados como parámetros
 	 *
 	 * @param String $value transsport_id requerido
 	 * @param String $tipo tipo requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
    public function queryByTransjuegoIdAndTipo($value,$tipo)
    {
        $sql = 'SELECT * FROM transsportsbook_log WHERE transsport_id = ? AND tipo=?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        $sqlQuery->set($tipo);
        return $this->getList($sqlQuery);
    }

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * las columnas transsport_id y transaccion_id son iguales a los valores 
 	 * pasados como parámetros
 	 *
 	 * @param String $value transsport_id requerido
 	 * @param String $transaccionId transaccion_id requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
    public function queryByTransjuegoIdAndTransaccionId($value,$transaccionId)
    {
        $sql = 'SELECT * FROM transsportsbook_log WHERE transsport_id = ? AND transaccion_id=?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        $sqlQuery->set($transaccionId);
        return $this->getList($sqlQuery);
    }

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna tipo sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value tipo requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByTipo($value)
	{
		$sql = 'SELECT * FROM transsportsbook_log WHERE tipo = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna transaccion_id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value transaccion_id requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByTransaccionId($value)
	{
		$sql = 'SELECT * FROM transsportsbook_log WHERE transaccion_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setString($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna t_value sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value t_value requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByTValue($value)
	{
		$sql = 'SELECT * FROM transsportsbook_log WHERE t_value = ?';
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
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByFechaCrea($value)
	{
		$sql = 'SELECT * FROM transsportsbook_log WHERE fecha_crea = ?';
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
	public function queryByUsucreaId($value)
	{
		$sql = 'SELECT * FROM transsportsbook_log WHERE usucrea_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
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
	public function queryByFechaModif($value)
	{
		$sql = 'SELECT * FROM transsportsbook_log WHERE fecha_modif = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
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
	public function queryByUsumodifId($value)
	{
		$sql = 'SELECT * FROM transsportsbook_log WHERE usumodif_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}










	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna transsport_id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value transsport_id requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByTransjuegoId($value)
	{
		$sql = 'DELETE FROM transsportsbook_log WHERE transsport_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna tipo sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value tipo requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByTipo($value)
	{
		$sql = 'DELETE FROM transsportsbook_log WHERE tipo = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna transaccion_id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value transaccion_id requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByTransaccionId($value)
	{
		$sql = 'DELETE FROM transsportsbook_log WHERE transaccion_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna t_value sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value t_value requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByTValue($value)
	{
		$sql = 'DELETE FROM transsportsbook_log WHERE t_value = ?';
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
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByFechaCrea($value)
	{
		$sql = 'DELETE FROM transsportsbook_log WHERE fecha_crea = ?';
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
	public function deleteByUsucreaId($value)
	{
		$sql = 'DELETE FROM transsportsbook_log WHERE usucrea_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
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
	public function deleteByFechaModif($value)
	{
		$sql = 'DELETE FROM transsportsbook_log WHERE fecha_modif = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
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
	public function deleteByUsumodifId($value)
	{
		$sql = 'DELETE FROM transsportsbook_log WHERE usumodif_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}








	/**
 	 * Crear y devolver un objeto del tipo TranssportsbookLog
 	 * con los valores de una consulta sql
 	 * 
 	 *
 	 * @param Arreglo $row arreglo asociativo
 	 *
 	 * @return Objeto $transsportsbookLog TranssportsbookLog
 	 *
 	 * @access protected
 	 *
 	 */
	protected function readRow($row)
	{
		$transsportsbookLog = new TranssportsbookLog();

		$transsportsbookLog->transsportlogId = $row['transsportlog_id'];
		$transsportsbookLog->transjuegoId = $row['transsport_id'];
		$transsportsbookLog->tipo = $row['tipo'];
		$transsportsbookLog->transaccionId = $row['transaccion_id'];
        $transsportsbookLog->tValue = $row['t_value'];
        $transsportsbookLog->valor = $row['valor'];
		$transsportsbookLog->fechaCrea = $row['fecha_crea'];
		$transsportsbookLog->usucreaId = $row['usucrea_id'];
		$transsportsbookLog->fechaModif = $row['fecha_modif'];
        $transsportsbookLog->usumodifId = $row['usumodif_id'];

        $transsportsbookLog->usuarioId = $row['usuario_id'];
        $transsportsbookLog->gameReference = $row['game_reference'];
        $transsportsbookLog->betStatus = $row['bet_status'];
        $transsportsbookLog->mandante = $row['mandante'];

		return $transsportsbookLog;
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
	protected function getList($sqlQuery)
	{
		$tab = QueryExecutor::execute($this->transaction, $sqlQuery);
		$ret = array();
		for ($i = 0; $i < oldCount($tab); $i++) {
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
	protected function getRow($sqlQuery)
	{
		$tab = QueryExecutor::execute($this->transaction, $sqlQuery);
		if (oldCount($tab) == 0) {
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
	protected function execute($sqlQuery)
	{
		return QueryExecutor::execute($this->transaction, $sqlQuery);
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
	protected function executeUpdate($sqlQuery)
	{
		return QueryExecutor::executeUpdate($this->transaction, $sqlQuery);
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
	protected function querySingleResult($sqlQuery)
	{
		return QueryExecutor::queryForString($this->transaction, $sqlQuery);
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
	protected function executeInsert($sqlQuery)
	{
		return QueryExecutor::executeInsert($this->transaction, $sqlQuery);
	}
}
?>