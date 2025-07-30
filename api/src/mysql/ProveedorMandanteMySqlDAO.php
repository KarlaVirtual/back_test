<?php namespace Backend\mysql;
use Backend\dao\ProveedorMandanteDAO;
use Backend\dto\ProveedorMandante;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Exception;
/** 
* Clase 'ProveedorMandanteMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'ProveedorMandante'
* 
* Ejemplo de uso: 
* $ProveedorMandanteMySqlDAO = new ProveedorMandanteMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class ProveedorMandanteMySqlDAO implements ProveedorMandanteDAO
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
     * Obtener el registro condicionado por la 
     * llave primaria que se pasa como parámetro
     *
     * @param String $id llave primaria
     *
     * @return Array resultado de la consulta
     *
     */
	public function load($id)
	{
		$sql = 'SELECT * FROM proveedor_mandante WHERE provmandante_id = ?';
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
	public function queryAll()
	{
		$sql = 'SELECT * FROM proveedor_mandante';
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
	public function queryAllOrderBy($orderColumn)
	{
		$sql = 'SELECT * FROM proveedor_mandante ORDER BY ' . $orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}




    /**
    * Realizar una consulta en la tabla de ProveedorMandante 'ProveedorMandante'
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
    public function queryProveedoresMandanteCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
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


        $sql = 'SELECT count(*) count FROM proveedor_mandante INNER JOIN proveedor ON (proveedor_mandante.proveedor_id = proveedor.proveedor_id) INNER JOIN mandante ON (mandante.mandante = proveedor_mandante.mandante)  ' . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT ' .$select .'  FROM proveedor_mandante INNER JOIN proveedor ON (proveedor_mandante.proveedor_id = proveedor.proveedor_id) INNER JOIN mandante ON (mandante.mandante = proveedor_mandante.mandante)  ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;


        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }







    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $provmandante_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function delete($provmandante_id)
	{
		$sql = 'DELETE FROM proveedor_mandante WHERE provmandante_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($provmandante_id);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Insertar un registro en la base de datos
     *
     * @param Object productoMandante productoMandante
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($productoMandante)
	{
		$sql = 'INSERT INTO proveedor_mandante (proveedor_id, mandante, estado, verifica, filtro_pais, usucrea_id, usumodif_id, max, min, detalle) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);

		$sqlQuery->setNumber($productoMandante->proveedorId);
		$sqlQuery->setNumber($productoMandante->mandante);
		$sqlQuery->set($productoMandante->estado);
		$sqlQuery->set($productoMandante->verifica);
		$sqlQuery->set($productoMandante->filtroPais);
		$sqlQuery->setNumber($productoMandante->usucreaId);
		$sqlQuery->setNumber($productoMandante->usumodifId);
		$sqlQuery->set($productoMandante->max);
		$sqlQuery->set($productoMandante->min);
		$sqlQuery->set($productoMandante->detalle);

		$id = $this->executeInsert($sqlQuery);
		$productoMandante->provmandanteId = $id;
		return $id;
	}

    /**
     * Editar un registro en la base de datos
     *
     * @param Object productoMandante productoMandante
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($productoMandante)
	{
		$sql = 'UPDATE proveedor_mandante SET proveedor_id = ?, mandante = ?, estado = ?, verifica = ?, filtro_pais = ?, usucrea_id = ?, usumodif_id = ?, max = ?, min = ?, detalle = ? WHERE provmandante_id = ?';
		$sqlQuery = new SqlQuery($sql);

		$sqlQuery->setNumber($productoMandante->proveedorId);
		$sqlQuery->setNumber($productoMandante->mandante);
		$sqlQuery->set($productoMandante->estado);
		$sqlQuery->set($productoMandante->verifica);
		$sqlQuery->set($productoMandante->filtroPais);
		$sqlQuery->setNumber($productoMandante->usucreaId);
		$sqlQuery->setNumber($productoMandante->usumodifId);
		$sqlQuery->set($productoMandante->max);
		$sqlQuery->set($productoMandante->min);
		$sqlQuery->set($productoMandante->detalle);

		$sqlQuery->setNumber($productoMandante->provmandanteId);
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
		$sql = 'DELETE FROM proveedor_mandante';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}






    /**
     * Obtener todos los registros donde se encuentre que
     * la columna proveedor_id sea igual al valor pasado como parámetro
     *
     * @param String $value proveedor_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByProveedorId($value)
	{
		$sql = 'SELECT * FROM proveedor_mandante WHERE proveedor_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
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
	public function queryByMandante($value)
	{
		$sql = 'SELECT * FROM proveedor_mandante WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna productoId sea igual al valor pasado como parámetro
     *
     * @param String $proveedor_id proveedor_id
     * @param String $mandante mandante
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByProveedorIdAndMandante($proveedorId, $value)
	{
		$sql = 'SELECT * FROM proveedor_mandante WHERE proveedor_id=? AND mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($proveedorId);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna estado sea igual al valor pasado como parámetro
     *
     * @param String $value estado requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByEstado($value)
	{
		$sql = 'SELECT * FROM proveedor_mandante WHERE estado = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna verifica sea igual al valor pasado como parámetro
     *
     * @param String $value verifica requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByVerifica($value)
	{
		$sql = 'SELECT * FROM proveedor_mandante WHERE verifica = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna filtro_pais sea igual al valor pasado como parámetro
     *
     * @param String $value filtro_pais requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByFiltroPais($value)
	{
		$sql = 'SELECT * FROM proveedor_mandante WHERE filtro_pais = ?';
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
	public function queryByFechaCrea($value)
	{
		$sql = 'SELECT * FROM proveedor_mandante WHERE fecha_crea = ?';
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
	public function queryByFechaModif($value)
	{
		$sql = 'SELECT * FROM proveedor_mandante WHERE fecha_modif = ?';
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
	public function queryByUsucreaId($value)
	{
		$sql = 'SELECT * FROM proveedor_mandante WHERE usucrea_id = ?';
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
	public function queryByUsumodifId($value)
	{
		$sql = 'SELECT * FROM proveedor_mandante WHERE usumodif_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna max sea igual al valor pasado como parámetro
     *
     * @param String $value max requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByMax($value)
	{
		$sql = 'SELECT * FROM proveedor_mandante WHERE max = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna min sea igual al valor pasado como parámetro
     *
     * @param String $value min requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByMin($value)
	{
		$sql = 'SELECT * FROM proveedor_mandante WHERE min = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna detalle sea igual al valor pasado como parámetro
     *
     * @param String $value detalle requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByDetalle($value)
	{
		$sql = 'SELECT * FROM proveedor_mandante WHERE detalle = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}







    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna proveedor_id sea igual al valor pasado como parámetro
     *
     * @param String $value proveedor_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByProveedorId($value)
	{
		$sql = 'DELETE FROM proveedor_mandante WHERE proveedor_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
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
	public function deleteByMandante($value)
	{
		$sql = 'DELETE FROM proveedor_mandante WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna estado sea igual al valor pasado como parámetro
     *
     * @param String $value estado requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByEstado($value)
	{
		$sql = 'DELETE FROM proveedor_mandante WHERE estado = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna verifica sea igual al valor pasado como parámetro
     *
     * @param String $value verifica requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByVerifica($value)
	{
		$sql = 'DELETE FROM proveedor_mandante WHERE verifica = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna filtro_pais sea igual al valor pasado como parámetro
     *
     * @param String $value filtro_pais requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByFiltroPais($value)
	{
		$sql = 'DELETE FROM proveedor_mandante WHERE filtro_pais = ?';
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
	public function deleteByFechaCrea($value)
	{
		$sql = 'DELETE FROM proveedor_mandante WHERE fecha_crea = ?';
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
	public function deleteByFechaModif($value)
	{
		$sql = 'DELETE FROM proveedor_mandante WHERE fecha_modif = ?';
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
	public function deleteByUsucreaId($value)
	{
		$sql = 'DELETE FROM proveedor_mandante WHERE usucrea_id = ?';
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
	public function deleteByUsumodifId($value)
	{
		$sql = 'DELETE FROM proveedor_mandante WHERE usumodif_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna max sea igual al valor pasado como parámetro
     *
     * @param String $value max requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByMax($value)
	{
		$sql = 'DELETE FROM proveedor_mandante WHERE max = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna min sea igual al valor pasado como parámetro
     *
     * @param String $value min requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByMin($value)
	{
		$sql = 'DELETE FROM proveedor_mandante WHERE min = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna detalle sea igual al valor pasado como parámetro
     *
     * @param String $value detalle requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByDetalle($value)
	{
		$sql = 'DELETE FROM proveedor_mandante WHERE detalle = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}







    /**
     * Crear y devolver un objeto del tipo ProveedorMandante
     * con los valores de una consulta sql
     * 
     *  
     * @param Arreglo $row arreglo asociativo
     *
     * @return Object $ProveedorMandante ProveedorMandante
     *
     * @access protected
     *
     */
	protected function readRow($row)
	{
		$productoMandante = new ProveedorMandante();

		$productoMandante->provmandanteId = $row['provmandante_id'];
		$productoMandante->proveedorId = $row['proveedor_id'];
		$productoMandante->mandante = $row['mandante'];
		$productoMandante->estado = $row['estado'];
		$productoMandante->verifica = $row['verifica'];
		$productoMandante->filtroPais = $row['filtro_pais'];
		$productoMandante->fechaCrea = $row['fecha_crea'];
		$productoMandante->fechaModif = $row['fecha_modif'];
		$productoMandante->usucreaId = $row['usucrea_id'];
		$productoMandante->usumodifId = $row['usumodif_id'];
		$productoMandante->max = $row['max'];
		$productoMandante->min = $row['min'];
		$productoMandante->detalle = $row['detalle'];

		return $productoMandante;
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