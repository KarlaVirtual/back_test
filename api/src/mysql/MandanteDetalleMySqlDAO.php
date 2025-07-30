<?php namespace Backend\mysql;
use Backend\dao\MandanteDetalleDAO;
use Backend\dto\MandanteDetalle;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Exception;
/** 
* Clase 'MandanteDetalleMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'MandanteDetalle'
* 
* Ejemplo de uso: 
* $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class MandanteDetalleMySqlDAO implements MandanteDetalleDAO{


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
		$sql = 'SELECT * FROM mandante_detalle WHERE manddetalle_id = ?';
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
		$sql = 'SELECT * FROM mandante_detalle';
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
		$sql = 'SELECT * FROM mandante_detalle ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $manddetalle_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function delete($manddetalle_id){
		$sql = 'DELETE FROM mandante_detalle WHERE manddetalle_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($manddetalle_id);
		return $this->executeUpdate($sqlQuery);
	}
	
    /**
     * Insertar un registro en la base de datos
     *
     * @param Object mandanteDetalle mandanteDetalle
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($mandanteDetalle){
		$sql = 'INSERT INTO mandante_detalle (mandante, tipo, valor, usucrea_id, usumodif_id,pais_id,estado) VALUES (?, ?, ?, ?, ?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->setNumber($mandanteDetalle->mandante);
		$sqlQuery->set($mandanteDetalle->tipo);
		$sqlQuery->set($mandanteDetalle->valor);
		$sqlQuery->setNumber($mandanteDetalle->usucreaId);
        $sqlQuery->setNumber($mandanteDetalle->usumodifId);
        $sqlQuery->setNumber($mandanteDetalle->paisId);
        $sqlQuery->set($mandanteDetalle->estado);

		$id = $this->executeInsert($sqlQuery);	
		$mandanteDetalle->manddetalleId = $id;
		return $id;
	}
		
    /**
     * Editar un registro en la base de datos
     *
     * @param Object mandanteDetalle mandanteDetalle
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($mandanteDetalle){
		$sql = 'UPDATE mandante_detalle SET mandante = ?, tipo = ?, valor = ?, usucrea_id = ?, usumodif_id = ?, pais_id=?, estado = ? WHERE manddetalle_id = ?';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->setNumber($mandanteDetalle->mandante);
		$sqlQuery->set($mandanteDetalle->tipo);
		$sqlQuery->set($mandanteDetalle->valor);
		$sqlQuery->setNumber($mandanteDetalle->usucreaId);
		$sqlQuery->setNumber($mandanteDetalle->usumodifId);
        $sqlQuery->setNumber($mandanteDetalle->paisId);
        $sqlQuery->set($mandanteDetalle->estado);

        $sqlQuery->setNumber($mandanteDetalle->manddetalleId);
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
		$sql = 'DELETE FROM mandante_detalle';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
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
    public function queryByProductoId($value){
        $sql = 'SELECT * FROM mandante_detalle WHERE mandante = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * las columnas mandante, tipo y pais_id sean iguales
     * a los valores pasados como parametro
     *
     * @param String $value mandante requerido
     * @param String $value2 tipo requerido
     * @param String $value3 pais_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByMandanteAndTipoAndPais($value , $value2, $value3){
        $sql = 'SELECT * FROM mandante_detalle WHERE mandante = ? AND tipo = ? AND pais_id=?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        $sqlQuery->set($value2);
        $sqlQuery->set($value3);
        return $this->getList($sqlQuery);
    }

    public function queryByMandanteAndPaisAndEstado($value , $value2, $value3){
        $sql = 'SELECT * FROM mandante_detalle WHERE mandante = ? AND pais_id=? AND estado = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        $sqlQuery->set($value2);
        $sqlQuery->set($value3);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * las columnas mandante, tipo, pais_id y estado sean iguales
     * a los valores pasados como parametro
     *
     * @param String $value mandante requerido
     * @param String $value2 tipo requerido
     * @param String $value3 pais_id requerido
     * @param String $estado estado requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByMandanteAndTipoAndPaisAndEstado($value , $value2,$value3,$estado){
        $sql = 'SELECT * FROM mandante_detalle WHERE mandante = ? AND tipo = ? AND pais_id=? AND estado= ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        $sqlQuery->set($value2);
        $sqlQuery->set($value3);
        $sqlQuery->set($estado);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * las columnas mandante, tipo, pais_id y estado sean iguales
     * a los valores pasados como parametro
     *
     * @param String $value mandante requerido
     * @param String $value2 tipo requerido
     * @param String $value3 pais_id requerido
     * @param String $estado estado requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByMandanteAndTipoAndPaisAndValor($value,$value2,$value3,$valor){
        $sql = 'SELECT * FROM mandante_detalle WHERE mandante = ? AND tipo = ? AND pais_id=?  AND valor= ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        $sqlQuery->set($value2);
        $sqlQuery->set($value3);
        $sqlQuery->set($valor);
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
	public function queryByPKey($value){
		$sql = 'SELECT * FROM mandante_detalle WHERE tipo = ?';
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
	public function queryByPValue($value){
		$sql = 'SELECT * FROM mandante_detalle WHERE valor = ?';
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
		$sql = 'SELECT * FROM mandante_detalle WHERE fecha_crea = ?';
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
     * @return Array resultado de la consulta
     *
     */
	public function queryByFechaModif($value){
		$sql = 'SELECT * FROM mandante_detalle WHERE fecha_modif = ?';
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
		$sql = 'SELECT * FROM mandante_detalle WHERE usucrea_id = ?';
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
     * @return Array resultado de la consulta
     *
     */
	public function queryByUsumodifId($value){
		$sql = 'SELECT * FROM mandante_detalle WHERE usumodif_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}




    /**
    * Realizar una consulta en la tabla de MandanteDetalle 'MandanteDetalle'
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
    public function queryMandanteDetallesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
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
                if ($fieldOperation != "") {
                    $whereArray[] = $fieldName . $fieldOperation;
                }

                if (oldCount($whereArray) > 0) {
                    $where = $where . " " . $groupOperation . " UCASE(CAST(" . $fieldName . " AS CHAR)) " . strtoupper($fieldOperation);
                } else {
                    $where = "";
                }
            }

        }

        $sql = "SELECT count(*) count FROM mandante_detalle INNER JOIN mandante ON (mandante.mandante=mandante_detalle.mandante) INNER JOIN clasificador ON (mandante_detalle.tipo=clasificador.clasificador_id) " . $where;

        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
        $sql = "SELECT " . $select . " FROM mandante_detalle INNER JOIN mandante ON (mandante.mandante=mandante_detalle.mandante) INNER JOIN clasificador ON (mandante_detalle.tipo=clasificador.clasificador_id) " . $where . " " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }


    /**
     * Realizar una consulta en la tabla de MandanteDetalle 'MandanteDetalle'
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
    public function queryMandanteDetallesCustom2($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
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
                if ($fieldOperation != "") {
                    $whereArray[] = $fieldName . $fieldOperation;
                }

                if (oldCount($whereArray) > 0) {
                    $where = $where . " " . $groupOperation . " UCASE(CAST(" . $fieldName . " AS CHAR)) " . strtoupper($fieldOperation);
                } else {
                    $where = "";
                }
            }

        }

        $sql = "SELECT count(*) count FROM mandante_detalle LEFT OUTER JOIN clasificador ON (mandante_detalle.tipo=clasificador.clasificador_id) " . $where;

        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
        $sql = "SELECT " . $select . " FROM mandante_detalle LEFT OUTER JOIN clasificador ON (mandante_detalle.tipo=clasificador.clasificador_id) " . $where . " " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
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
    public function deleteByProductoId($value){
		$sql = 'DELETE FROM mandante_detalle WHERE mandante = ?';
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
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByPKey($value){
		$sql = 'DELETE FROM mandante_detalle WHERE tipo = ?';
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
	public function deleteByPValue($value){
		$sql = 'DELETE FROM mandante_detalle WHERE valor = ?';
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
		$sql = 'DELETE FROM mandante_detalle WHERE fecha_crea = ?';
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
		$sql = 'DELETE FROM mandante_detalle WHERE fecha_modif = ?';
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
		$sql = 'DELETE FROM mandante_detalle WHERE usucrea_id = ?';
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
		$sql = 'DELETE FROM mandante_detalle WHERE usumodif_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}


	




    /**
     * Crear y devolver un objeto del tipo MandanteDetalle
     * con los valores de una consulta sql
     * 
     *  
     * @param Arreglo $row arreglo asociativo
     *
     * @return Object $MandanteDetalle MandanteDetalle
     *
     * @access protected
     *
     */
	protected function readRow($row){
		$mandanteDetalle = new MandanteDetalle();
		
		$mandanteDetalle->manddetalleId = $row['manddetalle_id'];
		$mandanteDetalle->mandante = $row['mandante'];
		$mandanteDetalle->tipo = $row['tipo'];
		$mandanteDetalle->valor = $row['valor'];
		$mandanteDetalle->usucreaId = $row['usucrea_id'];
        $mandanteDetalle->usumodifId = $row['usumodif_id'];
        $mandanteDetalle->paisId = $row['pais_id'];
        $mandanteDetalle->estado = $row['estado'];

		return $mandanteDetalle;
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