<?php namespace Backend\mysql;
use Backend\dao\TranssportsbookDetalleDAO;
use Backend\dto\TranssportsbookDetalle;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Exception;
/** 
* Clase 'TranssportsbookDetalleMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'TranssportsbookDetalle'
* 
* Ejemplo de uso: 
* $TranssportsbookDetalleMySqlDAO = new TranssportsbookDetalleMySqlDAO();
* 
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class TranssportsbookDetalleMySqlDAO implements TranssportsbookDetalleDAO
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
		$sql = 'SELECT * FROM transsportsbook_detalle WHERE transsportdet_id = ?';
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
	public function queryAll(){
		$sql = 'SELECT * FROM transsportsbook_detalle';
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
		$sql = 'SELECT * FROM transsportsbook_detalle ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros condicionados
 	 * por la llave primaria
 	 *
 	 * @param String $transsportdet_id llave primaria
 	 *
	 * @return boolean $ resultado de la consulta
     *
 	 */
	public function delete($transsportdet_id){
		$sql = 'DELETE FROM transsportsbook_detalle WHERE transsportdet_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($transsportdet_id);
		return $this->executeUpdate($sqlQuery);
	}
	

	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param Objeto transsportsbookDetalle transsportsbookDetalle
 	 *
	 * @return String $id resultado de la consulta
     *
 	 */
	public function insert($transsportsbookDetalle){
		$sql = 'INSERT INTO transsportsbook_detalle (transsport_id, apuesta, agrupador, logro,sportid, opcion, apuesta_id,agrupador_id, ticket_id, fecha_evento, mandante, matchid, usucrea_id, usumodif_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';


		$sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($transsportsbookDetalle->transsportId);
		$sqlQuery->set($transsportsbookDetalle->apuesta);
		$sqlQuery->set($transsportsbookDetalle->agrupador);
        $sqlQuery->set($transsportsbookDetalle->logro);
        $sqlQuery->set($transsportsbookDetalle->sportid);
		$sqlQuery->set($transsportsbookDetalle->opcion);
        $sqlQuery->set($transsportsbookDetalle->apuestaId);
        $sqlQuery->set($transsportsbookDetalle->agrupadorId);
		$sqlQuery->setString($transsportsbookDetalle->ticketId);
		$sqlQuery->set($transsportsbookDetalle->fechaEvento);
		$sqlQuery->setNumber($transsportsbookDetalle->mandante);
		$sqlQuery->setString($transsportsbookDetalle->matchid);
		$sqlQuery->setNumber($transsportsbookDetalle->usucreaId);
        $sqlQuery->setNumber($transsportsbookDetalle->usumodifId);

		$id = $this->executeInsert($sqlQuery);	
		$transsportsbookDetalle->transsportdetId = $id;
		return $id;
	}

	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Objeto transsportsbookDetalle transsportsbookDetalle
 	 *
	 * @return boolean $ resultado de la consulta
     *
 	 */
	public function update($transsportsbookDetalle){
		$sql = 'UPDATE transsportsbook_detalle SET transsport_id = ?, apuesta = ?, agrupador = ?, logro = ?, sportid=?, opcion = ?, apuesta_id = ?, agrupador_id = ?, ticket_id = ?, fecha_evento = ?, mandante = ?, matchid = ?, usucrea_id = ?, usumodif_id = ? WHERE transsportdet_id = ?';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->setNumber($transsportsbookDetalle->transsportId);
		$sqlQuery->set($transsportsbookDetalle->apuesta);
		$sqlQuery->set($transsportsbookDetalle->agrupador);
        $sqlQuery->set($transsportsbookDetalle->logro);
        $sqlQuery->set($transsportsbookDetalle->sportid);
		$sqlQuery->set($transsportsbookDetalle->opcion);
        $sqlQuery->set($transsportsbookDetalle->apuestaId);
        $sqlQuery->set($transsportsbookDetalle->agrupadorId);
		$sqlQuery->setString($transsportsbookDetalle->ticketId);
		$sqlQuery->set($transsportsbookDetalle->fechaEvento);
		$sqlQuery->setNumber($transsportsbookDetalle->mandante);
		$sqlQuery->setString($transsportsbookDetalle->matchid);
		$sqlQuery->setNumber($transsportsbookDetalle->usucreaId);
		$sqlQuery->setNumber($transsportsbookDetalle->usumodifId);

		$sqlQuery->setNumber($transsportsbookDetalle->transsportdetId);
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
		$sql = 'DELETE FROM transsportsbook_detalle';
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
	public function queryByUsuarioId($value){
		$sql = 'SELECT * FROM transsportsbook_detalle WHERE transsport_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna apuesta sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value apuesta requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByProductoId($value){
		$sql = 'SELECT * FROM transsportsbook_detalle WHERE apuesta = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna agrupador sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value agrupador requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByValorTicket($value){
		$sql = 'SELECT * FROM transsportsbook_detalle WHERE agrupador = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna logro sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value logro requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByValorPremio($value){
		$sql = 'SELECT * FROM transsportsbook_detalle WHERE logro = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna opcion sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value opcion requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByEstado($value){
		$sql = 'SELECT * FROM transsportsbook_detalle WHERE opcion = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna apuestaId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value apuestaId requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByPremiado($value){
		$sql = 'SELECT * FROM transsportsbook_detalle WHERE apuestaId = ?';
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
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByTicketId($value){
		$sql = 'SELECT * FROM transsportsbook_detalle WHERE ticket_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setString($value);
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
	public function queryByTransaccionId($value){
		$sql = 'SELECT * FROM transsportsbook_detalle WHERE transaccion_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setString($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna fecha_evento sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value fecha_evento requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByFechaPago($value){
		$sql = 'SELECT * FROM transsportsbook_detalle WHERE fecha_evento = ?';
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
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByMandante($value){
		$sql = 'SELECT * FROM transsportsbook_detalle WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna matchid sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value matchid requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByClave($value){
		$sql = 'SELECT * FROM transsportsbook_detalle WHERE matchid = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setString($value);
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
		$sql = 'SELECT * FROM transsportsbook_detalle WHERE usucrea_id = ?';
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
		$sql = 'SELECT * FROM transsportsbook_detalle WHERE fecha_crea = ?';
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
	public function queryByUsumodifId($value){
		$sql = 'SELECT * FROM transsportsbook_detalle WHERE usumodif_id = ?';
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
	public function queryByFechaModif($value){
		$sql = 'SELECT * FROM transsportsbook_detalle WHERE fecha_modif = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}






   	/**
   	* Realizar una consulta en la tabla de TranssportsbookDetalle 'TranssportsbookDetalle'
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
    public function queryTransacciones($sidx,$sord,$start,$limit,$filters,$searchOn)
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
		  

   
		  $sql = "SELECT count(*) count FROM transsportsbook_detalle INNER JOIN producto_mandante ON (producto_mandante.prodmandante_id=transsportsbook_detalle.apuesta) INNER JOIN producto ON (producto.apuesta=producto_mandante.apuesta) INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id) " . $where;
		  

        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
		$sql = "SELECT transsportsbook_detalle.*,producto.*,producto_mandante.*,proveedor.* FROM transsportsbook_detalle INNER JOIN producto_mandante ON (producto_mandante.prodmandante_id=transsportsbook_detalle.apuesta) INNER JOIN producto ON (producto.apuesta=producto_mandante.apuesta) INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id) " . $where ." " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;
		

        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : '.json_encode($count). ', "data" : '.json_encode($result).'}';

        return  $json;
    }

   	/**
   	* Realizar una consulta en la tabla de TranssportsbookDetalle 'TranssportsbookDetalle'
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
	public function queryTransaccionesCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping="")
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

   
		  $sql = "SELECT count(*) count FROM transsportsbook_detalle INNER JOIN transaccion_sportsbook ON (transsportsbook_detalle.transsport_id = transaccion_sportsbook.transsport_id ) " . $where;
		  
		  $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
		$sql = "SELECT ".$select." FROM transsportsbook_detalle  INNER JOIN transaccion_sportsbook ON (transsportsbook_detalle.transsport_id = transaccion_sportsbook.transsport_id )" . $where ." " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

		$sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : '.json_encode($count). ', "data" : '.json_encode($result).'}';

        return  $json;
    }










	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * las columnas ticketId y transsport_id sean iguales a los
 	 * valores pasados como parámetros
 	 *
 	 * @param String $value ticketId requerido
  	 * @param String $value transsport_id requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
    public function existsTicketId($ticketId,$transsport_id){
        $sql = 'SELECT * FROM transsportsbook_detalle WHERE ticket_id = ? AND transsport_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setString($ticketId);
        $sqlQuery->setNumber($transsport_id);
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
	public function deleteByUsuarioId($value){
		$sql = 'DELETE FROM transsportsbook_detalle WHERE transsport_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna apuesta sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value apuesta requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByProductoId($value){
		$sql = 'DELETE FROM transsportsbook_detalle WHERE apuesta = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna agrupador sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value agrupador requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByValorTicket($value){
		$sql = 'DELETE FROM transsportsbook_detalle WHERE agrupador = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna logro sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value logro requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByValorPremio($value){
		$sql = 'DELETE FROM transsportsbook_detalle WHERE logro = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna opcion sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value opcion requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByEstado($value){
		$sql = 'DELETE FROM transsportsbook_detalle WHERE opcion = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna apuestaId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value apuestaId requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByPremiado($value){
		$sql = 'DELETE FROM transsportsbook_detalle WHERE apuestaId = ?';
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
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByTicketId($value){
		$sql = 'DELETE FROM transsportsbook_detalle WHERE ticket_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
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
	public function deleteByTransaccionId($value){
		$sql = 'DELETE FROM transsportsbook_detalle WHERE transaccion_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna fecha_evento sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value fecha_evento requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByFechaPago($value){
		$sql = 'DELETE FROM transsportsbook_detalle WHERE fecha_evento = ?';
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
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByMandante($value){
		$sql = 'DELETE FROM transsportsbook_detalle WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna matchid sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value tipo requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByClave($value){
		$sql = 'DELETE FROM transsportsbook_detalle WHERE matchid = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
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
		$sql = 'DELETE FROM transsportsbook_detalle WHERE usucrea_id = ?';
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
		$sql = 'DELETE FROM transsportsbook_detalle WHERE fecha_crea = ?';
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
	public function deleteByUsumodifId($value){
		$sql = 'DELETE FROM transsportsbook_detalle WHERE usumodif_id = ?';
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
	public function deleteByFechaModif($value){
		$sql = 'DELETE FROM transsportsbook_detalle WHERE fecha_modif = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}










	

	/**
 	 * Crear y devolver un objeto del tipo TranssportsbookDetalle
 	 * con los valores de una consulta sql
 	 * 
 	 *
 	 * @param Arreglo $row arreglo asociativo
 	 *
 	 * @return Objeto $transsportsbookDetalle TranssportsbookDetalle
 	 *
 	 * @access protected
 	 *
 	 */
	protected function readRow($row){
		$transsportsbookDetalle = new TranssportsbookDetalle();
		
		$transsportsbookDetalle->transsportdetId = $row['transsportdet_id'];
		$transsportsbookDetalle->transsportId = $row['transsport_id'];
		$transsportsbookDetalle->apuesta = $row['apuesta'];
		$transsportsbookDetalle->agrupador = $row['agrupador'];
        $transsportsbookDetalle->logro = $row['logro'];
        $transsportsbookDetalle->sportid = $row['sportid'];
		$transsportsbookDetalle->opcion = $row['opcion'];
        $transsportsbookDetalle->apuestaId = $row['apuesta_id'];
        $transsportsbookDetalle->agrupadorId = $row['agrupador_id'];
		$transsportsbookDetalle->ticketId = $row['ticket_id'];
		$transsportsbookDetalle->fechaEvento = $row['fecha_evento'];
		$transsportsbookDetalle->mandante = $row['mandante'];
		$transsportsbookDetalle->matchid = $row['matchid'];
		$transsportsbookDetalle->usucreaId = $row['usucrea_id'];
		$transsportsbookDetalle->fechaCrea = $row['fecha_crea'];
		$transsportsbookDetalle->usumodifId = $row['usumodif_id'];
		$transsportsbookDetalle->fechaModif = $row['fecha_modif'];
		
		return $transsportsbookDetalle;
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