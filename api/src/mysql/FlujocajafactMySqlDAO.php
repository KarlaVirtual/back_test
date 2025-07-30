<?php namespace Backend\mysql;
use Backend\dao\FlujoCajaDAO;
use Backend\dto\FlujoCaja;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Exception;
/** 
* Clase 'FlujocajafactMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'Flujocajafact'
* 
* Ejemplo de uso: 
* $FlujocajafactMySqlDAO = new FlujocajafactMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class FlujocajafactMySqlDAO implements FlujocajafactDAO{

    /**
     * Obtener el registro condicionado por la 
     * llave primaria que se pasa como parámetro
     *
     * @param String $flujocaja_id llave primaria
     *
     * @return Array resultado de la consulta
     *
     */
	public function load($id){
		$sql = 'SELECT * FROM flujocajafact WHERE flujocaja_id = ?';
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
		$sql = 'SELECT * FROM flujocajafact';
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
		$sql = 'SELECT * FROM flujocajafact ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $flujocaja_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function delete($flujocaja_id){
		$sql = 'DELETE FROM flujocajafact WHERE flujocaja_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($flujocaja_id);
		return $this->executeUpdate($sqlQuery);
	}
	
    /**
     * Insertar un registro en la base de datos
     *
     * @param Object flujocajafact flujocajafact
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($flujocajafact){
		$sql = 'INSERT INTO flujocajafact (fecha_crea, vlr_entrada_efectivo, vlr_entrada_bono, vlr_entrada_recarga, vlr_entrada_traslado, vlr_salida_efectivo, vlr_salida_notaret, vlr_salida_traslado, cant_tickets, mandante, puntoventa_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($flujocajafact->fechaCrea);
		$sqlQuery->set($flujocajafact->vlrEntradaEfectivo);
		$sqlQuery->set($flujocajafact->vlrEntradaBono);
		$sqlQuery->set($flujocajafact->vlrEntradaRecarga);
		$sqlQuery->set($flujocajafact->vlrEntradaTraslado);
		$sqlQuery->set($flujocajafact->vlrSalidaEfectivo);
		$sqlQuery->set($flujocajafact->vlrSalidaNotaret);
		$sqlQuery->set($flujocajafact->vlrSalidaTraslado);
		$sqlQuery->set($flujocajafact->cantTickets);
		$sqlQuery->set($flujocajafact->mandante);
		$sqlQuery->set($flujocajafact->puntoventaId);

		$id = $this->executeInsert($sqlQuery);	
		$flujocajafact->flujocajaId = $id;
		return $id;
	}

    /**
     * Editar un registro en la base de datos
     *
     * @param Object flujocajafact flujocajafact
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($flujocajafact){
		$sql = 'UPDATE flujocajafact SET fecha_crea = ?, vlr_entrada_efectivo = ?, vlr_entrada_bono = ?, vlr_entrada_recarga = ?, vlr_entrada_traslado = ?, vlr_salida_efectivo = ?, vlr_salida_notaret = ?, vlr_salida_traslado = ?, cant_tickets = ?, mandante = ?, puntoventa_id = ? WHERE flujocaja_id = ?';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($flujocajafact->fechaCrea);
		$sqlQuery->set($flujocajafact->vlrEntradaEfectivo);
		$sqlQuery->set($flujocajafact->vlrEntradaBono);
		$sqlQuery->set($flujocajafact->vlrEntradaRecarga);
		$sqlQuery->set($flujocajafact->vlrEntradaTraslado);
		$sqlQuery->set($flujocajafact->vlrSalidaEfectivo);
		$sqlQuery->set($flujocajafact->vlrSalidaNotaret);
		$sqlQuery->set($flujocajafact->vlrSalidaTraslado);
		$sqlQuery->set($flujocajafact->cantTickets);
		$sqlQuery->set($flujocajafact->mandante);
		$sqlQuery->set($flujocajafact->puntoventaId);

		$sqlQuery->set($flujocajafact->flujocajaId);
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
		$sql = 'DELETE FROM flujocajafact';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
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
		$sql = 'SELECT * FROM flujocajafact WHERE fecha_crea = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna vlr_entrada_efectivo sea igual al valor pasado como parámetro
     *
     * @param String $value vlr_entrada_efectivo requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByVlrEntradaEfectivo($value){
		$sql = 'SELECT * FROM flujocajafact WHERE vlr_entrada_efectivo = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna vlr_entrada_bono sea igual al valor pasado como parámetro
     *
     * @param String $value vlr_entrada_bono requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByVlrEntradaBono($value){
		$sql = 'SELECT * FROM flujocajafact WHERE vlr_entrada_bono = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna vlr_entrada_recarga sea igual al valor pasado como parámetro
     *
     * @param String $value vlr_entrada_recarga requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByVlrEntradaRecarga($value){
		$sql = 'SELECT * FROM flujocajafact WHERE vlr_entrada_recarga = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna vlr_entrada_traslado sea igual al valor pasado como parámetro
     *
     * @param String $value vlr_entrada_traslado requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByVlrEntradaTraslado($value){
		$sql = 'SELECT * FROM flujocajafact WHERE vlr_entrada_traslado = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna vlr_salida_efectivo sea igual al valor pasado como parámetro
     *
     * @param String $value vlr_salida_efectivo requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByVlrSalidaEfectivo($value){
		$sql = 'SELECT * FROM flujocajafact WHERE vlr_salida_efectivo = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna vlr_salida_notaret sea igual al valor pasado como parámetro
     *
     * @param String $value vlr_salida_notaret requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByVlrSalidaNotaret($value){
		$sql = 'SELECT * FROM flujocajafact WHERE vlr_salida_notaret = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna vlr_salida_traslado sea igual al valor pasado como parámetro
     *
     * @param String $value vlr_salida_traslado requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByVlrSalidaTraslado($value){
		$sql = 'SELECT * FROM flujocajafact WHERE vlr_salida_traslado = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna cant_tickets sea igual al valor pasado como parámetro
     *
     * @param String $value cant_tickets requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByCantTickets($value){
		$sql = 'SELECT * FROM flujocajafact WHERE cant_tickets = ?';
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
		$sql = 'SELECT * FROM flujocajafact WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna puntoventa_id sea igual al valor pasado como parámetro
     *
     * @param String $value puntoventa_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByPuntoventaId($value){
		$sql = 'SELECT * FROM flujocajafact WHERE puntoventa_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}






   	/**
   	* Realizar una consulta en la tabla de areas 'Flujocajafact'
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
    public function queryFlujocajafactCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping)
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

        $sql = "SELECT count(*) count FROM flujocajafact INNER JOIN puntoventadim  on (flujocajafact.puntoventa_id=puntoventadim.puntoventa_id) " . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
        $sql = "SELECT ".$select." FROM flujocajafact INNER JOIN puntoventadim  on (flujocajafact.puntoventa_id=puntoventadim.puntoventa_id)  " . $where ." " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;


        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : '.json_encode($count). ', "data" : '.json_encode($result).'}';

        return  $json;
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
		$sql = 'DELETE FROM flujocajafact WHERE fecha_crea = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna vlr_entrada_efectivo sea igual al valor pasado como parámetro
     *
     * @param String $value vlr_entrada_efectivo requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByVlrEntradaEfectivo($value){
		$sql = 'DELETE FROM flujocajafact WHERE vlr_entrada_efectivo = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna vlr_entrada_bono sea igual al valor pasado como parámetro
     *
     * @param String $value vlr_entrada_bono requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByVlrEntradaBono($value){
		$sql = 'DELETE FROM flujocajafact WHERE vlr_entrada_bono = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna vlr_entrada_recarga sea igual al valor pasado como parámetro
     *
     * @param String $value vlr_entrada_recarga requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByVlrEntradaRecarga($value){
		$sql = 'DELETE FROM flujocajafact WHERE vlr_entrada_recarga = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna vlr_entrada_traslado sea igual al valor pasado como parámetro
     *
     * @param String $value vlr_entrada_traslado requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByVlrEntradaTraslado($value){
		$sql = 'DELETE FROM flujocajafact WHERE vlr_entrada_traslado = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna vlr_salida_efectivo sea igual al valor pasado como parámetro
     *
     * @param String $value vlr_salida_efectivo requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByVlrSalidaEfectivo($value){
		$sql = 'DELETE FROM flujocajafact WHERE vlr_salida_efectivo = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna vlr_salida_notaret sea igual al valor pasado como parámetro
     *
     * @param String $value vlr_salida_notaret requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByVlrSalidaNotaret($value){
		$sql = 'DELETE FROM flujocajafact WHERE vlr_salida_notaret = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna vlr_salida_traslado sea igual al valor pasado como parámetro
     *
     * @param String $value vlr_salida_traslado requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByVlrSalidaTraslado($value){
		$sql = 'DELETE FROM flujocajafact WHERE vlr_salida_traslado = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna cant_tickets sea igual al valor pasado como parámetro
     *
     * @param String $value cant_tickets requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByCantTickets($value){
		$sql = 'DELETE FROM flujocajafact WHERE cant_tickets = ?';
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
		$sql = 'DELETE FROM flujocajafact WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna puntoventa_id sea igual al valor pasado como parámetro
     *
     * @param String $value puntoventa_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByPuntoventaId($value){
		$sql = 'DELETE FROM flujocajafact WHERE puntoventa_id = ?';
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
     * @return Object $Flujocajafact Flujocajafact
     *
     * @access protected
     *
     */
	protected function readRow($row){
		$flujocajafact = new Flujocajafact();
		
		$flujocajafact->flujocajaId = $row['flujocaja_id'];
		$flujocajafact->fechaCrea = $row['fecha_crea'];
		$flujocajafact->vlrEntradaEfectivo = $row['vlr_entrada_efectivo'];
		$flujocajafact->vlrEntradaBono = $row['vlr_entrada_bono'];
		$flujocajafact->vlrEntradaRecarga = $row['vlr_entrada_recarga'];
		$flujocajafact->vlrEntradaTraslado = $row['vlr_entrada_traslado'];
		$flujocajafact->vlrSalidaEfectivo = $row['vlr_salida_efectivo'];
		$flujocajafact->vlrSalidaNotaret = $row['vlr_salida_notaret'];
		$flujocajafact->vlrSalidaTraslado = $row['vlr_salida_traslado'];
		$flujocajafact->cantTickets = $row['cant_tickets'];
		$flujocajafact->mandante = $row['mandante'];
		$flujocajafact->puntoventaId = $row['puntoventa_id'];

		return $flujocajafact;
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