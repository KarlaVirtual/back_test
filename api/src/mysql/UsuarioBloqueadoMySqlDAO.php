<?php namespace Backend\mysql;
use Backend\dao\UsuarioBloqueadoDAO;
use Backend\dto\UsuarioBloqueado;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
/** 
* Clase 'UsuarioBloqueadoMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'UsuarioBloqueado'
* 
* Ejemplo de uso: 
* $UsuarioBloqueadoMySqlDAO = new UsuarioBloqueadoMySqlDAO();
* 
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class UsuarioBloqueadoMySqlDAO implements UsuarioBloqueadoDAO
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
     * Obtener todos los registros condicionados por 
     * los valores usubloqueadoId y mandante
     * pasados como parámetro
     *
     * @param String $usubloqueadoId usubloqueadoId
     * @param String $mandante mandante
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function load($usubloqueadoId, $mandante)
	{
		$sql = 'SELECT * FROM usuario_bloqueado WHERE usubloqueado_id = ?  AND mandante = ? ';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($usubloqueadoId);
		$sqlQuery->setNumber($mandante);

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
		$sql = 'SELECT * FROM usuario_bloqueado';
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
		$sql = 'SELECT * FROM usuario_bloqueado ORDER BY ' . $orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}

    /**
     * Eliminar todos los registros condicionados
     * por los valores usubloqueado_id y usubloqueado_id
     * pasados como parámetro
     *
     * @param String $usubloqueadoId usubloqueadoId
     * @param String $mandante mandante
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function delete($usubloqueadoId, $mandante)
	{
		$sql = 'DELETE FROM usuario_bloqueado WHERE usubloqueado_id = ?  AND mandante = ? ';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($usubloqueadoId);
		$sqlQuery->setNumber($mandante);

		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Insertar un registro en la base de datos
     *
     * @param Objeto usuarioConfig usuarioConfig
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($usuarioConfig)
	{
		$sql = 'INSERT INTO usuario_bloqueado (cedula, primer_nombre, segundo_nombre, primer_apellido,segundo_apellido, mandante,tipo,estado,usucrea_id,usumodif_id,tipo_documento) VALUES (?, ?, ?, ?, ?, ?,?, ?, ?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);

		$sqlQuery->set($usuarioConfig->cedula);
		$sqlQuery->set($usuarioConfig->primerNombre);
		$sqlQuery->set($usuarioConfig->segundoNombre);
        $sqlQuery->set($usuarioConfig->primerApellido);
        $sqlQuery->set($usuarioConfig->segundoApellido);
        $sqlQuery->setNumber($usuarioConfig->mandante);
        $sqlQuery->set($usuarioConfig->tipo);
        $sqlQuery->set($usuarioConfig->estado);
        $sqlQuery->set($usuarioConfig->usucreaId);
        $sqlQuery->set($usuarioConfig->usumodifId);
        $sqlQuery->set($usuarioConfig->tipoDocumento);




		$this->executeInsert($sqlQuery);	
		//$usuarioConfig->id = $id;
		//return $id;

	}

    /**
     * Editar un registro en la base de datos
     *
     * @param Objeto usuarioConfig usuarioConfig
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($usuarioConfig)
	{
		$sql = 'UPDATE usuario_bloqueado SET cedula = ?, primer_nombre = ?, segundo_nombre = ?, primer_apellido = ?,segundo_apellido, mandante= ?,tipo= ?,estado= ?,usucrea_id= ?,usumodif_id= ?,tipo_documento= ? WHERE usubloqueado_id = ? ';
		$sqlQuery = new SqlQuery($sql);

		$sqlQuery->set($usuarioConfig->cedula);
		$sqlQuery->set($usuarioConfig->primerNombre);
		$sqlQuery->set($usuarioConfig->segundoNombre);
		$sqlQuery->set($usuarioConfig->primerApellido);
        $sqlQuery->setNumber($usuarioConfig->mandante);
        $sqlQuery->set($usuarioConfig->tipo);
        $sqlQuery->set($usuarioConfig->estado);
        $sqlQuery->set($usuarioConfig->usucreaId);
        $sqlQuery->set($usuarioConfig->usumodifId);
        $sqlQuery->set($usuarioConfig->tipoDocumento);


		$sqlQuery->setNumber($usuarioConfig->usubloqueadoId);


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
		$sql = 'DELETE FROM usuario_bloqueado';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}










    /**
     * Obtener todos los registros donde se encuentre que
     * la columna cedula sea igual al valor pasado como parámetro
     *
     * @param String $value cedula requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByCedula($value)
	{
		$sql = 'SELECT * FROM usuario_bloqueado WHERE cedula = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}




    /**
    * Realizar una consulta en la tabla de UsuarioBloqueado 'UsuarioBloqueado'
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
    public function queryUsuariosBloqueadosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
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

        $sql = "SELECT count(*) count FROM usuario_bloqueado " . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
        $sql = "SELECT " . $select . " FROM usuario_bloqueado  " . $where . " " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;




        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }





    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna cedula sea igual al valor pasado como parámetro
     *
     * @param String $value cedula requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByUsuarioId($value)
	{
		$sql = 'DELETE FROM usuario_bloqueado WHERE cedula = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna primer_nombre sea igual al valor pasado como parámetro
     *
     * @param String $value primer_nombre requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByPermiteRecarga($value)
	{
		$sql = 'DELETE FROM usuario_bloqueado WHERE primer_nombre = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna segundoNombre sea igual al valor pasado como parámetro
     *
     * @param String $value segundoNombre requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByPinagent($value)
	{
		$sql = 'DELETE FROM usuario_bloqueado WHERE segundoNombre = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna primer_apellido sea igual al valor pasado como parámetro
     *
     * @param String $value primer_apellido requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByReciboCaja($value)
	{
		$sql = 'DELETE FROM usuario_bloqueado WHERE primer_apellido = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}






    /**
     * Crear y devolver un objeto del tipo UsuarioBloqueado
     * con los valores de una consulta sql
     * 
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Objeto $usuarioConfig UsuarioBloqueado
     *
     * @access protected
     *
     */
	protected function readRow($row)
	{
		$usuarioConfig = new UsuarioBloqueado();

		$usuarioConfig->usubloqueadoId = $row['usubloqueado_id'];
		$usuarioConfig->cedula = $row['cedula'];
		$usuarioConfig->primerNombre = $row['primer_nombre'];
		$usuarioConfig->mandante = $row['mandante'];
		$usuarioConfig->segundoNombre = $row['segundo_nombre'];
        $usuarioConfig->primerApellido = $row['primer_apellido'];
        $usuarioConfig->segundoApellido = $row['segundo_apellido'];
        $usuarioConfig->tipo = $row['tipo'];
        $usuarioConfig->estado = $row['estado'];
        $usuarioConfig->usucreaId = $row['usucreaId'];
        $usuarioConfig->usumodifId = $row['usumodifId'];
        $usuarioConfig->tipoDocumento = $row['tipo_documento'];

		return $usuarioConfig;
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