<?php namespace Backend\mysql;
use Backend\dao\UsumandanteLogDAO;
use Backend\dto\UsumandanteLog;
use Backend\sql\QueryExecutor;
use Backend\sql\SqlQuery;
use Backend\sql\Transaction;
/** 
* Clase 'UsumandanteLogMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'UsumandanteLog'
* 
* Ejemplo de uso: 
* $UsumandanteLogMySqlDAO = new UsumandanteLogMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class UsumandanteLogMySqlDAO implements UsumandanteLogDAO
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
		$sql = 'SELECT * FROM usumandante_log WHERE usuconfig_id = ?';
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
		$sql = 'SELECT * FROM usumandante_log';
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
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
	public function queryAllOrderBy($orderColumn){
		$sql = 'SELECT * FROM usumandante_log ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $usuconfig_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function delete($usuconfig_id){
		$sql = 'DELETE FROM usumandante_log WHERE usuconfig_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($usuconfig_id);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Insertar un registro en la base de datos
     *
     * @param Objeto UsumandanteLog UsumandanteLog
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($UsumandanteLog){
		$sql = 'INSERT INTO usumandante_log (usuario_id,proveedor_id, tipo, valor_antes,valor_despues, usucrea_id, usumodif_id) VALUES (?, ?, ?, ?, ?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($UsumandanteLog->usuarioId);
        $sqlQuery->setNumber($UsumandanteLog->proveedorId);
		$sqlQuery->set($UsumandanteLog->tipo);
        $sqlQuery->set($UsumandanteLog->valorAntes);
        $sqlQuery->set($UsumandanteLog->valorDespues);
		$sqlQuery->setNumber($UsumandanteLog->usucreaId);
		$sqlQuery->setNumber($UsumandanteLog->usumodifId);

		$id = $this->executeInsert($sqlQuery);	
		$UsumandanteLog->usumandantelogId = $id;
		return $id;
	}
	

    /**
     * Editar un registro en la base de datos
     *
     * @param Objeto UsumandanteLog UsumandanteLog
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($UsumandanteLog){
		$sql = 'UPDATE usumandante_log SET usuario_id = ?, proveedor_id = ?, tipo = ?, valor_antes = ?, valor_despues = ?, usucrea_id = ?, usumodif_id = ? WHERE usuconfig_id = ?';
		$sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($UsumandanteLog->usuarioId);
        $sqlQuery->setNumber($UsumandanteLog->proveedorId);
		$sqlQuery->set($UsumandanteLog->tipo);
        $sqlQuery->set($UsumandanteLog->valorAntes);
        $sqlQuery->set($UsumandanteLog->valorDespues);
		$sqlQuery->setNumber($UsumandanteLog->usucreaId);
		$sqlQuery->setNumber($UsumandanteLog->usumodifId);

		$sqlQuery->setNumber($UsumandanteLog->usumandantelogId);
		return $this->executeUpdate($sqlQuery);
	}










    /**
    * Realizar una consulta en la tabla de UsumandanteLog 'UsumandanteLog'
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
    public function queryDeportesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
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


        $sql = 'SELECT count(*) count FROM usumandante_log ' . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT ' .$select .'  FROM usumandante_log ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

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
		$sql = 'DELETE FROM usumandante_log';
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
		$sql = 'SELECT * FROM usumandante_log WHERE usuario_id = ?';
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
		$sql = 'SELECT * FROM usumandante_log WHERE usucrea_id = ?';
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
		$sql = 'SELECT * FROM usumandante_log WHERE usumodif_id = ?';
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
		$sql = 'SELECT * FROM usumandante_log WHERE fecha_crea = ?';
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
		$sql = 'SELECT * FROM usumandante_log WHERE fecha_modif = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * las columnas usuario_id, proveedor_id y tipo son iguales a los valores 
     * pasados como parámetros
     *
     * @param String $usuarioId usuarioId requerido
     * @param String $proveedorId proveedorId requerido
     * @param String $tipo tipo requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByUsuarioIdAndProveedorAndTipo($usuarioId,$proveedorId,$tipo){
        $sql = 'SELECT * FROM usumandante_log WHERE usuario_id = ? AND proveedor_id = ? AND tipo=?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($usuarioId);
        $sqlQuery->set($proveedorId);
        $sqlQuery->set($tipo);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * las columnas proveedorId, tipo y valorAntes son iguales a los valores 
     * pasados como parámetros
     *
     * @param String $proveedorId proveedorId requerido
     * @param String $tipo tipo requerido
     * @param String $valorAntes valorAntes requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByProveedorAndTipoAndValorAntes($proveedorId,$tipo,$valorAntes){
        $sql = 'SELECT * FROM usumandante_log WHERE valor_antes = ? AND proveedor_id = ? AND tipo=? ORDER BY fecha_crea desc';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($valorAntes);
        $sqlQuery->set($proveedorId);
        $sqlQuery->set($tipo);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * las columnas usuario_id, proveedor_id y tipo son iguales a los valores 
     * pasados como parámetros
     *
     * @param String $usuarioId usuarioId requerido
     * @param String $proveedorId proveedorId requerido
     * @param String $tipo tipo requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByProveedorAndTipoAndUsuarioId($usuarioId,$proveedorId,$tipo){
        $sql = 'SELECT * FROM usumandante_log WHERE usuario_id = ? AND proveedor_id = ? AND tipo=? ORDER BY fecha_crea desc';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($usuarioId);
        $sqlQuery->set($proveedorId);
        $sqlQuery->set($tipo);
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
		$sql = 'DELETE FROM usumandante_log WHERE usuarioId = ?';
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
		$sql = 'DELETE FROM usumandante_log WHERE usucrea_id = ?';
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
		$sql = 'DELETE FROM usumandante_log WHERE usumodif_id = ?';
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
		$sql = 'DELETE FROM usumandante_log WHERE fecha_crea = ?';
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
		$sql = 'DELETE FROM usumandante_log WHERE fecha_modif = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}
















    /**
     * Crear y devolver un objeto del tipo UsumandanteLog
     * con los valores de una consulta sql
     * 
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Objeto $UsumandanteLog UsumandanteLog
     *
     * @access protected
     *
     */
	protected function readRow($row){
		$UsumandanteLog = new UsumandanteLog();
		
		$UsumandanteLog->usumandantelogId = $row['usuconfig_id'];
        $UsumandanteLog->usuarioId = $row['usuario_id'];
        $UsumandanteLog->proveedorId = $row['proveedor_id'];
		$UsumandanteLog->tipo = $row['tipo'];
        $UsumandanteLog->valorAntes = $row['valor_antes'];
        $UsumandanteLog->valorDespues = $row['valor_despues'];
		$UsumandanteLog->usucreaId = $row['usucrea_id'];
		$UsumandanteLog->usumodifId = $row['usumodif_id'];
		$UsumandanteLog->fechaCrea = $row['fecha_crea'];
		$UsumandanteLog->fechaModif = $row['fecha_modif'];

		return $UsumandanteLog;
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