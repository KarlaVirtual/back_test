<?php namespace Backend\mysql;
use Backend\dao\ConcesionarioDAO;
use Backend\dto\Concesionario;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
/**
* Clase 'ConcesionarioMySqlDAO'
*
* Esta clase provee las consultas del modelo o tabla 'Concesionario'
*
* Ejemplo de uso:
* $ConcesionarioMySqlDAO = new ConcesionarioMySqlDAO();
*
*
* @package ninguno
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public
* @see no
*
*/
class ConcesionarioMySqlDAO implements ConcesionarioDAO
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
    public function __construct($transaction = "")
    {
        if ($transaction == "") {

            $transaction = new Transaction();
            $this->transaction = $transaction;

        } else {
            $this->transaction = $transaction;
        }
    }








    /**
     * Obtener el registro condicionado por la
     * llave primaria que se pasa como parámetro
     *
     * @param String $id llave primaria
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function load($id)
	{
		$sql = 'SELECT * FROM concesionario WHERE concesionario_id = ?';
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
    public function queryAll()
    {
        $sql = 'SELECT * FROM concesionario';
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
        $sql = 'SELECT * FROM concesionario ORDER BY ' . $orderColumn;
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }

    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $concesionario_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function delete($concesionario_id)
    {
        $sql = 'DELETE FROM concesionario WHERE concesionario_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($concesionario_id);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Insertar un registro en la base de datos
     *
     * @param Object concesionario concesionario
     *
     * @return String $id resultado de la consulta
     *
     */
    public function insert($concesionario)
    {
        $sql = 'INSERT INTO concesionario (usupadre_id, usuhijo_id, usupadre2_id, mandante, usupadre3_id, usupadre4_id, porcenhijo, porcenpadre1, porcenpadre2, porcenpadre3, porcenpadre4, usucrea_id, usumodif_id, prodinterno_id,estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->set($concesionario->usupadreId);
        $sqlQuery->set($concesionario->usuhijoId);
        $sqlQuery->set($concesionario->usupadre2Id);
        $sqlQuery->set($concesionario->mandante);
        $sqlQuery->set($concesionario->usupadre3Id);
        $sqlQuery->set($concesionario->usupadre4Id);
        $sqlQuery->set($concesionario->porcenhijo);
        $sqlQuery->set($concesionario->porcenpadre1);
        $sqlQuery->set($concesionario->porcenpadre2);
        $sqlQuery->set($concesionario->porcenpadre3);
        $sqlQuery->set($concesionario->porcenpadre4);

        $usuCrea=$_SESSION['usuario'];

        if($usuCrea ==""){
            $usuCrea='0';
        }

        $sqlQuery->set($usuCrea);

        $sqlQuery->set($concesionario->usumodifId);
        $sqlQuery->set($concesionario->prodinternoId);
        $sqlQuery->set($concesionario->estado);


        $id = $this->executeInsert($sqlQuery);
        $concesionario->concesionarioId = $id;
        return $id;
    }

    /**
     * Editar un registro en la base de datos
     *
     * @param Object concesionario concesionario
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function update($concesionario)
    {
        $sql = 'UPDATE concesionario SET usupadre_id = ?, usuhijo_id = ?, usupadre2_id = ?, mandante = ?, usupadre3_id = ?, usupadre4_id = ?, porcenhijo = ?, porcenpadre1 = ?, porcenpadre2 = ?, porcenpadre3 = ?, porcenpadre4 = ?, usucrea_id = ?, usumodif_id = ?, prodinterno_id = ?, estado = ? WHERE concesionario_id = ?';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->set($concesionario->usupadreId);
        $sqlQuery->set($concesionario->usuhijoId);
        $sqlQuery->set($concesionario->usupadre2Id);
        $sqlQuery->set($concesionario->mandante);
        $sqlQuery->set($concesionario->usupadre3Id);
        $sqlQuery->set($concesionario->usupadre4Id);
        $sqlQuery->set($concesionario->porcenhijo);
        $sqlQuery->set($concesionario->porcenpadre1);
        $sqlQuery->set($concesionario->porcenpadre2);
        $sqlQuery->set($concesionario->porcenpadre3);
        $sqlQuery->set($concesionario->porcenpadre4);
        $sqlQuery->set($concesionario->usucreaId);

        $usumodifId=$_SESSION['usuario'];

        if($usumodifId ==""){
            $usumodifId='0';
        }

        $sqlQuery->set($usumodifId);
        //$sqlQuery->set($concesionario->usumodifId);
        $sqlQuery->set($concesionario->prodinternoId);
        $sqlQuery->set($concesionario->estado);

        $sqlQuery->set($concesionario->concesionarioId);
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
        $sql = 'DELETE FROM concesionario';
        $sqlQuery = new SqlQuery($sql);
        return $this->executeUpdate($sqlQuery);
    }







    /**
     * Obtener todos los registros donde se encuentre que
     * la columna UsupadreId sea igual al valor pasado como parámetro
     *
     * @param String $value UsupadreId requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByUsupadreId($value)
	{
		$sql = 'SELECT * FROM concesionario WHERE usupadre_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna UsuhijoId sea igual al valor pasado como parámetro
     *
     * @param String $value UsuhijoId requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByUsuhijoId($value)
	{
		$sql = 'SELECT * FROM concesionario WHERE usuhijo_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * las columnas usuhijo_id y prodinterno_id son iguales
     * a los valores pasados como parámetros
     *
     * @param String $value usuhijo_id requerido
     * @param String $value prodinterno_id requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByUsuhijoIdAndProdinternoId($value, $prodinternoId)
    {
        $sql = 'SELECT * FROM concesionario WHERE usuhijo_id = ? AND prodinterno_id = ? AND estado="A"';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        $sqlQuery->set($prodinternoId);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna usupadre2_id sea igual al valor pasado como parámetro
     *
     * @param String $value usupadre2_id requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByUsupadre2Id($value)
	{
		$sql = 'SELECT * FROM concesionario WHERE usupadre2_id = ?';
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
	public function queryByMandante($value)
	{
		$sql = 'SELECT * FROM concesionario WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}







    /**
    * Realizar una consulta en la tabla de concesionarios 'Concesionario'
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
    public function queryConcesionariosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $withDISP = false;

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
                    $where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
                } else {
                    $where = "";
                }


            }

        }
        $sql = 'SELECT count(*) count FROM concesionario  LEFT OUTER JOIN producto_interno ON (producto_interno.productointerno_id=concesionario.prodinterno_id)  ' . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT ' . $select . '  FROM concesionario  LEFT OUTER JOIN producto_interno ON (producto_interno.productointerno_id=concesionario.prodinterno_id)  ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }

    /**
    * Realizar una consulta en la tabla de coceionario_productointerno 'ConceConcesionarioProductopto' de una manera personalizada
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
    public function queryConcesionariosProductoInternoCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $usuhijoId = "", $withClasificadorMandante = false)
    {

        $withDISP = false;

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
                if ($fieldData != "DISP") {

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
                            $fieldOperation = " NOT IN (" . $fieldData . ")";
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
                } else {
                    $withDISP = true;
                }
            }

        }

        $estadoConce='';
        if ($withDISP) {
            $estadoConce = " AND concesionario.estado='A' ";
            $where = $where . " AND (concesionario.estado ='A' OR concesionario.estado IS NULL) ";
        }

        $innerClasificadorMandante='';

        if($withClasificadorMandante){
            $innerClasificadorMandante = ' INNER JOIN clasificador_mandante ON (clasificador_mandante.clasificador_id = clasificador.clasificador_id)  ';
        }

        $sql = 'SELECT count(*) count FROM clasificador  LEFT OUTER JOIN concesionario ON (clasificador.clasificador_id=concesionario.prodinterno_id AND usuhijo_id="' . $usuhijoId . '" '.$estadoConce.') '.$innerClasificadorMandante.'  ' . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT ' . $select . '  FROM clasificador  LEFT OUTER JOIN concesionario ON (clasificador.clasificador_id=concesionario.prodinterno_id AND usuhijo_id="' . $usuhijoId . '" '.$estadoConce.')  '.$innerClasificadorMandante.' ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        if($_ENV["debugFixed2"] == '1'){
            print_r($sql);
        }
        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }







    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna usupadre_id sea igual al valor pasado como parámetro
     *
     * @param String $value usupadre_id requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByUsupadreId($value)
    {
        $sql = 'DELETE FROM concesionario WHERE usupadre_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna usuhijo_id sea igual al valor pasado como parámetro
     *
     * @param String $value usuhijo_id requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByUsuhijoId($value)
	{
		$sql = 'DELETE FROM concesionario WHERE usuhijo_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna usupadre2_id sea igual al valor pasado como parámetro
     *
     * @param String $value usupadre2_id requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByUsupadre2Id($value)
	{
		$sql = 'DELETE FROM concesionario WHERE usupadre2_id = ?';
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
	public function deleteByMandante($value)
	{
		$sql = 'DELETE FROM concesionario WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}








    /**
     * Crear y devolver un objeto del tipo Concesionario
     * con los valores de una consulta sql
     *
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Object $Concesionario Concesionario
     *
     * @access protected
     *
     */
	protected function readRow($row)
	{
		$concesionario = new Concesionario();

        $concesionario->concesionarioId = $row['concesionario_id'];
        $concesionario->usupadreId = $row['usupadre_id'];
        $concesionario->usuhijoId = $row['usuhijo_id'];
        $concesionario->usupadre2Id = $row['usupadre2_id'];
        $concesionario->mandante = $row['mandante'];
        $concesionario->usupadre3Id = $row['usupadre3_id'];
        $concesionario->usupadre4Id = $row['usupadre4_id'];
        $concesionario->porcenhijo = $row['porcenhijo'];
        $concesionario->porcenpadre1 = $row['porcenpadre1'];
        $concesionario->porcenpadre2 = $row['porcenpadre2'];
        $concesionario->porcenpadre3 = $row['porcenpadre3'];
        $concesionario->porcenpadre4 = $row['porcenpadre4'];
        $concesionario->usucreaId = $row['usucrea_id'];
        $concesionario->usumodifId = $row['usumodif_id'];
        $concesionario->prodinternoId = $row['prodinterno_id'];
        $concesionario->estado = $row['estado'];


        return $concesionario;
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
    protected function executeUpdate($sqlQuery)
    {
        return QueryExecutor::executeUpdate($this->transaction, $sqlQuery);
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
