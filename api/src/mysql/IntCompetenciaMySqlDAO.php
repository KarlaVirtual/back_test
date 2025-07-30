<?php namespace Backend\mysql;
use Backend\dao\IntCompetenciaDAO;
use Backend\dto\IntCompetencia;
use Backend\sql\QueryExecutor;
use Backend\sql\SqlQuery;
use Backend\sql\Transaction;
/** 
* Clase 'IntCompetenciaMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'IntCompetencia'
* 
* Ejemplo de uso: 
* $IntCompetenciaMySqlDAO = new IntCompetenciaMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class IntCompetenciaMySqlDAO implements IntCompetenciaDAO{

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
     * @param String $apuesta_id llave primaria
     *
     * @return Array resultado de la consulta
     *
     */
	public function load($id){
		$sql = 'SELECT * FROM int_competencia WHERE competencia_id = ?';
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
		$sql = 'SELECT * FROM int_competencia';
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
		$sql = 'SELECT * FROM int_competencia ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $competencia_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function delete($competencia_id){
		$sql = 'DELETE FROM int_competencia WHERE competencia_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($competencia_id);
		return $this->executeUpdate($sqlQuery);
	}
	
    /**
     * Insertar un registro en la base de datos
     *
     * @param Object intCompetencia intCompetencia
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($intCompetencia){
		$sql = 'INSERT INTO int_competencia (region_id, nombre, nombre_traduccion, nombre_internacional, abreviado, estado, usucrea_id, usumodif_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->setNumber($intCompetencia->regionId);
		$sqlQuery->set($intCompetencia->nombre);
		$sqlQuery->set($intCompetencia->nombreTraduccion);
		$sqlQuery->set($intCompetencia->nombreInternacional);
		$sqlQuery->set($intCompetencia->abreviado);
		$sqlQuery->set($intCompetencia->estado);
		$sqlQuery->setNumber($intCompetencia->usucreaId);
		$sqlQuery->setNumber($intCompetencia->usumodifId);

		$id = $this->executeInsert($sqlQuery);	
		$intCompetencia->competenciaId = $id;
		return $id;
	}
	
    /**
     * Editar un registro en la base de datos
     *
     * @param Object intCompetencia intCompetencia
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($intCompetencia){
		$sql = 'UPDATE int_competencia SET region_id = ?, nombre = ?, nombre_traduccion = ?, nombre_internacional = ?, abreviado = ?, estado = ?, usucrea_id = ?, usumodif_id = ? WHERE competencia_id = ?';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->setNumber($intCompetencia->regionId);
		$sqlQuery->set($intCompetencia->nombre);
		$sqlQuery->set($intCompetencia->nombreTraduccion);
		$sqlQuery->set($intCompetencia->nombreInternacional);
		$sqlQuery->set($intCompetencia->abreviado);
		$sqlQuery->set($intCompetencia->estado);
		$sqlQuery->setNumber($intCompetencia->usucreaId);
		$sqlQuery->setNumber($intCompetencia->usumodifId);

		$sqlQuery->setNumber($intCompetencia->competenciaId);
		return $this->executeUpdate($sqlQuery);
	}

    /**
    * Realizar una consulta en la tabla de IntCompetencia 'IntCompetencia'
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
    public function queryCompetenciasCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
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


        $sql = 'SELECT count(*) count FROM int_competencia INNER JOIN int_region ON (int_region.region_id=int_competencia.region_id) INNER JOIN int_deporte ON (int_region.deporte_id=int_deporte.deporte_id) ' . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT ' .$select .'  FROM int_competencia INNER JOIN int_region ON (int_region.region_id=int_competencia.region_id) INNER JOIN int_deporte ON (int_region.deporte_id=int_deporte.deporte_id) ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

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
		$sql = 'DELETE FROM int_competencia';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}







    /**
     * Obtener todos los registros donde se encuentre que
     * la columna region_id sea igual al valor pasado como parámetro
     *
     * @param String $value region_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByRegionId($value){
		$sql = 'SELECT * FROM int_competencia WHERE region_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna nombre sea igual al valor pasado como parámetro
     *
     * @param String $value nombre requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByNombre($value){
		$sql = 'SELECT * FROM int_competencia WHERE nombre = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna nombre_traduccion sea igual al valor pasado como parámetro
     *
     * @param String $value nombre_traduccion requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByNombreTraduccion($value){
		$sql = 'SELECT * FROM int_competencia WHERE nombre_traduccion = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna nombre_internacional sea igual al valor pasado como parámetro
     *
     * @param String $value nombre_internacional requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByNombreInternacional($value){
		$sql = 'SELECT * FROM int_competencia WHERE nombre_internacional = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna abreviado sea igual al valor pasado como parámetro
     *
     * @param String $value abreviado requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByAbreviado($value){
		$sql = 'SELECT * FROM int_competencia WHERE abreviado = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
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
	public function queryByEstado($value){
		$sql = 'SELECT * FROM int_competencia WHERE estado = ?';
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
		$sql = 'SELECT * FROM int_competencia WHERE usucrea_id = ?';
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
		$sql = 'SELECT * FROM int_competencia WHERE usumodif_id = ?';
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
     * @return Array resultado de la consulta
     *
     */
	public function queryByFechaCrea($value){
		$sql = 'SELECT * FROM int_competencia WHERE fecha_crea = ?';
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
		$sql = 'SELECT * FROM int_competencia WHERE fecha_modif = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}









    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna region_id sea igual al valor pasado como parámetro
     *
     * @param String $value region_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByRegionId($value){
		$sql = 'DELETE FROM int_competencia WHERE region_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna nombre sea igual al valor pasado como parámetro
     *
     * @param String $value nombre requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByNombre($value){
		$sql = 'DELETE FROM int_competencia WHERE nombre = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna nombre_traduccion sea igual al valor pasado como parámetro
     *
     * @param String $value nombre_traduccion requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByNombreTraduccion($value){
		$sql = 'DELETE FROM int_competencia WHERE nombre_traduccion = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna nombre_internacional sea igual al valor pasado como parámetro
     *
     * @param String $value nombre_internacional requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByNombreInternacional($value){
		$sql = 'DELETE FROM int_competencia WHERE nombre_internacional = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna abreviado sea igual al valor pasado como parámetro
     *
     * @param String $value abreviado requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByAbreviado($value){
		$sql = 'DELETE FROM int_competencia WHERE abreviado = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
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
	public function deleteByEstado($value){
		$sql = 'DELETE FROM int_competencia WHERE estado = ?';
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
		$sql = 'DELETE FROM int_competencia WHERE usucrea_id = ?';
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
		$sql = 'DELETE FROM int_competencia WHERE usumodif_id = ?';
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
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByFechaCrea($value){
		$sql = 'DELETE FROM int_competencia WHERE fecha_crea = ?';
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
		$sql = 'DELETE FROM int_competencia WHERE fecha_modif = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}


	






    /**
     * Crear y devolver un objeto del tipo IntCompetencia
     * con los valores de una consulta sql
     * 
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Object $IntCompetencia IntCompetencia
     *
     * @access protected
     *
     */
	protected function readRow($row){
		$intCompetencia = new IntCompetencia();
		
		$intCompetencia->competenciaId = $row['competencia_id'];
		$intCompetencia->regionId = $row['region_id'];
		$intCompetencia->nombre = $row['nombre'];
		$intCompetencia->nombreTraduccion = $row['nombre_traduccion'];
		$intCompetencia->nombreInternacional = $row['nombre_internacional'];
		$intCompetencia->abreviado = $row['abreviado'];
		$intCompetencia->estado = $row['estado'];
		$intCompetencia->usucreaId = $row['usucrea_id'];
		$intCompetencia->usumodifId = $row['usumodif_id'];
		$intCompetencia->fechaCrea = $row['fecha_crea'];
		$intCompetencia->fechaModif = $row['fecha_modif'];

		return $intCompetencia;
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
		$tab = QueryExecutor::execute($sqlQuery);
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
		$tab = QueryExecutor::execute($sqlQuery);
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