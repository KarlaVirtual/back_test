<?php namespace Backend\mysql;

use Backend\dao\RegistroRapidoDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\RegistroRapido;
use Backend\sql\QueryExecutor;
use Backend\sql\SqlQuery;
use Backend\sql\Transaction;

/** 
* Clase 'RegistroRapidoMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'RegistroRapido'
* 
* Ejemplo de uso: 
* $RegistroRapidoMySqlDAO = new RegistroRapidoMySqlDAO();
*	
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class RegistroRapidoMySqlDAO implements RegistroRapidoDAO
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
	 * Obtener todos los registros condicionados por la 
	 * llave primaria que se pasa como parámetro
	 *
	 * @param String $id llave primaria
	 *
	 * @return Array $ resultado de la consulta
	 *
	 */
	public function load($id){
		$sql = 'SELECT * FROM registro_rapido WHERE registro_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($id);
		return $this->getRow($sqlQuery);
	}


	/**
	 * Realiza una consulta personalizada en la tabla registro_rapido con filtros y ordenamiento.
	 *
	 * @param string $select Campos a seleccionar en la consulta.
	 * @param string $sidx Campo por el cual se ordenarán los resultados.
	 * @param string $sord Orden de los resultados (ASC o DESC).
	 * @param int $start Índice de inicio para la paginación.
	 * @param int $limit Número de registros a devolver.
	 * @param string $filters Filtros en formato JSON para aplicar a la consulta.
	 * @param bool $searchOn Indica si se deben aplicar los filtros.
	 * @param string $grouping (Opcional) Campo por el cual agrupar los resultados.
	 * @return string JSON con el conteo de registros y los datos resultantes de la consulta.
	 */
    public function queryRegistroRapidoCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping = "")
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
                    $where = $where . " " . $groupOperation . " ((" . $fieldName . ")) " . strtoupper($fieldOperation);
                } else {
                    $where = "";
                }
            }

        }



        $sql = "SELECT count(*) count FROM registro_rapido INNER JOIN pais ON (registro_rapido.pais_id=pais.pais_id)  " . $where;


        if ($grouping != "") {
            $where = $where . " GROUP BY " . $grouping;
        }

        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
        $sql = "SELECT " . $select . " FROM registro_rapido INNER JOIN pais ON (registro_rapido.pais_id=pais.pais_id)   " . $where . " " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;
        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }



    /**
	 * Obtener todos los registros de la base datos
	 *
	 * @return Array $ resultado de la consulta
     *
	 */
	public function queryAll(){
		$sql = 'SELECT * FROM registro_rapido';
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
		$sql = 'SELECT * FROM registro_rapido ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
	/**
 	 * Eliminar todos los registros condicionados
 	 * por la llave primaria
 	 *
 	 * @param String $registro_id llave primaria
 	 *
	 * @return boolean $ resultado de la consulta
     *
 	 */
	public function delete($registro_id){
		$sql = 'DELETE FROM registro_rapido WHERE registro_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($registro_id);
		return $this->executeUpdate($sqlQuery);
	}
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param Objeto registroRapido registroRapido
 	 *
	 * @return String $id resultado de la consulta
     *
 	 */
	public function insert($registroRapido){
		$sql = 'INSERT INTO registro_rapido (tipo_doc, cedula, pais_id, moneda, nombre1, nombre2, apellido1, apellido2, mandante) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($registroRapido->tipoDoc);
		$sqlQuery->set($registroRapido->cedula);
		$sqlQuery->set($registroRapido->paisId);
		$sqlQuery->set($registroRapido->moneda);
		$sqlQuery->set($registroRapido->nombre1);
		$sqlQuery->set($registroRapido->nombre2);
		$sqlQuery->set($registroRapido->apellido1);
		$sqlQuery->set($registroRapido->apellido2);
		$sqlQuery->set($registroRapido->mandante);

		$id = $this->executeInsert($sqlQuery);	
		$registroRapido->registroId = $id;
		return $id;
	}
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Objeto registroRapido registroRapido
 	 *
	 * @return boolean $ resultado de la consulta
     *
 	 */
	public function update($registroRapido){
		$sql = 'UPDATE registro_rapido SET tipo_doc = ?, cedula = ?, pais_id = ?, moneda = ?, nombre1 = ?, nombre2 = ?, apellido1 = ?, apellido2 = ?, mandante = ? WHERE registro_id = ?';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($registroRapido->tipoDoc);
		$sqlQuery->set($registroRapido->cedula);
		$sqlQuery->set($registroRapido->paisId);
		$sqlQuery->set($registroRapido->moneda);
		$sqlQuery->set($registroRapido->nombre1);
		$sqlQuery->set($registroRapido->nombre2);
		$sqlQuery->set($registroRapido->apellido1);
		$sqlQuery->set($registroRapido->apellido2);
		$sqlQuery->set($registroRapido->mandante);

		$sqlQuery->set($registroRapido->registroId);
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
		$sql = 'DELETE FROM registro_rapido';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}







	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna tipo_doc sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value tipo_doc requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByTipoDoc($value){
		$sql = 'SELECT * FROM registro_rapido WHERE tipo_doc = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
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
	public function queryByCedula($value){
		$sql = 'SELECT * FROM registro_rapido WHERE cedula = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna pais_id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value pais_id requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByPaisId($value){
		$sql = 'SELECT * FROM registro_rapido WHERE pais_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna moneda sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value moneda requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByMoneda($value){
		$sql = 'SELECT * FROM registro_rapido WHERE moneda = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna nombre1 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value nombre1 requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByNombre1($value){
		$sql = 'SELECT * FROM registro_rapido WHERE nombre1 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna nombre2 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value nombre2 requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByNombre2($value){
		$sql = 'SELECT * FROM registro_rapido WHERE nombre2 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna apellido1 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value apellido1 requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByApellido1($value){
		$sql = 'SELECT * FROM registro_rapido WHERE apellido1 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna apellido2 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value apellido2 requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByApellido2($value){
		$sql = 'SELECT * FROM registro_rapido WHERE apellido2 = ?';
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
		$sql = 'SELECT * FROM registro_rapido WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}







	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna tipo_doc sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value tipo_doc requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByTipoDoc($value){
		$sql = 'DELETE FROM registro_rapido WHERE tipo_doc = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
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
	public function deleteByCedula($value){
		$sql = 'DELETE FROM registro_rapido WHERE cedula = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna pais_id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value pais_id requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByPaisId($value){
		$sql = 'DELETE FROM registro_rapido WHERE pais_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna moneda sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value moneda requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByMoneda($value){
		$sql = 'DELETE FROM registro_rapido WHERE moneda = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna nombre1 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value nombre1 requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByNombre1($value){
		$sql = 'DELETE FROM registro_rapido WHERE nombre1 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna nombre2 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value nombre2 requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByNombre2($value){
		$sql = 'DELETE FROM registro_rapido WHERE nombre2 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna apellido1 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value apellido1 requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByApellido1($value){
		$sql = 'DELETE FROM registro_rapido WHERE apellido1 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna apellido2 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value apellido2 requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByApellido2($value){
		$sql = 'DELETE FROM registro_rapido WHERE apellido2 = ?';
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
		$sql = 'DELETE FROM registro_rapido WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}


	



	
	/**
 	 * Crear y devolver un objeto del tipo RegistroRapido
 	 * con los valores de una consulta sql
 	 * 
 	 *
 	 * @param Arreglo $row arreglo asociativo
 	 *
 	 * @return Objeto $registroRapido RegistroRapido
 	 *
 	 * @access protected
 	 *
 	 */
	protected function readRow($row){
		$registroRapido = new RegistroRapido();
		
		$registroRapido->registroId = $row['registro_id'];
		$registroRapido->tipoDoc = $row['tipo_doc'];
		$registroRapido->cedula = $row['cedula'];
		$registroRapido->paisId = $row['pais_id'];
		$registroRapido->moneda = $row['moneda'];
		$registroRapido->nombre1 = $row['nombre1'];
		$registroRapido->nombre2 = $row['nombre2'];
		$registroRapido->apellido1 = $row['apellido1'];
		$registroRapido->apellido2 = $row['apellido2'];
		$registroRapido->mandante = $row['mandante'];

		return $registroRapido;
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