<?php namespace Backend\mysql;
use Backend\dao\CompetenciaPuntosDAO;
use Backend\dto\CompetenciaPuntos;
use Backend\sql\QueryExecutor;
use Backend\sql\SqlQuery;
use Backend\sql\Transaction;
/** 
* Clase 'CompetenciaPuntosMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'CompetenciaPuntos'
* 
* Ejemplo de uso: 
* $CompetenciaPuntosMySqlDAO = new CompetenciaPuntosMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class CompetenciaPuntosMySqlDAO implements CompetenciaPuntosDAO{

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
     * @return Array $ resultado de la consulta
     *
     */
	public function load($id){
		$sql = 'SELECT * FROM competencia_puntos WHERE comppunto_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($id);
		return $this->getRow($sqlQuery);
	}

    /**
     * Obtener todos los registros de la base datos
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryAll(){
		$sql = 'SELECT * FROM competencia_puntos';
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
		$sql = 'SELECT * FROM competencia_puntos ORDER BY '.$orderColumn;
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
	public function delete($comppunto_id){
		$sql = 'DELETE FROM competencia_puntos WHERE comppunto_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($comppunto_id);
		return $this->executeUpdate($sqlQuery);
	}
	
    /**
     * Insertar un registro en la base de datos
     *
     * @param Object usuarioConfiguracion usuarioConfiguracion
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($usuarioConfiguracion){
		$sql = 'INSERT INTO competencia_puntos (competencia_id, nombre, descripcion, usucrea_id, usumodif_id,longitud,estado,latitud,direccion,ciudad_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->setNumber($usuarioConfiguracion->competenciaId);
		$sqlQuery->set($usuarioConfiguracion->nombre);
		$sqlQuery->set($usuarioConfiguracion->descripcion);
		$sqlQuery->setNumber($usuarioConfiguracion->usucreaId);
        $sqlQuery->setNumber($usuarioConfiguracion->usumodifId);
        $sqlQuery->set($usuarioConfiguracion->longitud);
        $sqlQuery->set($usuarioConfiguracion->estado);
        $sqlQuery->set($usuarioConfiguracion->latitud);
        $sqlQuery->set($usuarioConfiguracion->direccion);
        $sqlQuery->set($usuarioConfiguracion->ciudadId);

		$id = $this->executeInsert($sqlQuery);	
		$usuarioConfiguracion->comppuntoId = $id;
		return $id;
	}
	
    /**
     * Editar un registro en la base de datos
     *
     * @param Object usuarioConfiguracion usuarioConfiguracion
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($usuarioConfiguracion){
		$sql = 'UPDATE competencia_puntos SET competencia_id = ?, nombre = ?, descripcion = ?, usucrea_id = ?, usumodif_id = ?, longitud = ?, estado = ?, latitud = ?,direccion = ?,ciudad_id = ? WHERE comppunto_id = ?';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->setNumber($usuarioConfiguracion->competenciaId);
		$sqlQuery->set($usuarioConfiguracion->nombre);
		$sqlQuery->set($usuarioConfiguracion->descripcion);
		$sqlQuery->setNumber($usuarioConfiguracion->usucreaId);
        $sqlQuery->setNumber($usuarioConfiguracion->usumodifId);
        $sqlQuery->set($usuarioConfiguracion->longitud);
        $sqlQuery->set($usuarioConfiguracion->estado);
        $sqlQuery->set($usuarioConfiguracion->latitud);
        $sqlQuery->set($usuarioConfiguracion->direccion);
        $sqlQuery->set($usuarioConfiguracion->ciudadId);

		$sqlQuery->setNumber($usuarioConfiguracion->comppuntoId);
		return $this->executeUpdate($sqlQuery);
	}

    /**
    * Realizar una consulta en la tabla de competencia_puntos 'CompetenciaPuntos'
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
    public function queryCompetenciaPuntosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
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


        $sql = 'SELECT count(*) count FROM competencia_puntos 
                      INNER JOIN competencia on (competencia_puntos.competencia_id=competencia.competencia_id)
                      LEFT OUTER JOIN ciudad ON (ciudad.ciudad_id = competencia_puntos.ciudad_id) LEFT OUTER JOIN departamento ON (departamento.depto_id = ciudad.depto_id) ' . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT ' .$select .'  FROM competencia_puntos 
                              INNER JOIN competencia on (competencia_puntos.competencia_id=competencia.competencia_id)

        LEFT OUTER JOIN ciudad ON (ciudad.ciudad_id = competencia_puntos.ciudad_id)
         LEFT OUTER JOIN departamento ON (departamento.depto_id = ciudad.depto_id) ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

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
		$sql = 'DELETE FROM competencia_puntos';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}







    /**
     * Obtener todos los registros donde se encuentre que
     * la columna competencia_id sea igual al valor pasado como parámetro
     *
     * @param String $value competencia_id requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByUsuarioId($value){
		$sql = 'SELECT * FROM competencia_puntos WHERE competencia_id = ?';
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
		$sql = 'SELECT * FROM competencia_puntos WHERE usucrea_id = ?';
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
		$sql = 'SELECT * FROM competencia_puntos WHERE usumodif_id = ?';
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
		$sql = 'SELECT * FROM competencia_puntos WHERE fecha_crea = ?';
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
		$sql = 'SELECT * FROM competencia_puntos WHERE fecha_modif = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * las columnas competenciaId, nombre y estado
     * sean iguales a la pasada como parámetro
     *
     * @param String $competenciaId competenciaId requerido
     * @param String $nombre nombre requerido
     * @param String $estado estado requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByUsuarioIdAndTipoAndEstado($competenciaId,$nombre,$estado){
        $sql = 'SELECT * FROM competencia_puntos WHERE competencia_id = ? AND nombre=? AND estado = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($competenciaId);
        $sqlQuery->set($nombre);
        $sqlQuery->set($estado);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * las columnas competenciaId, nombre, longitud y estado
     * sean iguales a la pasada como parámetro
     *
     * @param String $competenciaId competenciaId requerido
     * @param String $nombre nombre requerido
     * @param String $longitud estado requerido
     * @param String $estado estado requerido     
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByUsuarioIdAndTipoAndProductoIdAndEstado($competenciaId,$nombre,$longitud,$estado){
        $sql = 'SELECT * FROM competencia_puntos WHERE competencia_id = ? AND nombre=? AND longitud=? AND estado = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($competenciaId);
        $sqlQuery->set($nombre);
        $sqlQuery->set($longitud);
        $sqlQuery->set($estado);
        return $this->getList($sqlQuery);
    }








    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna competenciaId sea igual al valor pasado como parámetro
     *
     * @param String $value competenciaId requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByNombre($value){
		$sql = 'DELETE FROM competencia_puntos WHERE competenciaId = ?';
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
		$sql = 'DELETE FROM competencia_puntos WHERE usucrea_id = ?';
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
		$sql = 'DELETE FROM competencia_puntos WHERE usumodif_id = ?';
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
		$sql = 'DELETE FROM competencia_puntos WHERE fecha_crea = ?';
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
		$sql = 'DELETE FROM competencia_puntos WHERE fecha_modif = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}


	
    /**
     * Crear y devolver un objeto del tipo CompetenciaPuntos
     * con los valores de una consulta sql
     * 
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Object $CompetenciaPuntos CompetenciaPuntos
     *
     * @access protected
     *
     */
	protected function readRow($row){
		$usuarioConfiguracion = new CompetenciaPuntos();
		
		$usuarioConfiguracion->comppuntoId = $row['comppunto_id'];
		$usuarioConfiguracion->competenciaId = $row['competencia_id'];
		$usuarioConfiguracion->nombre = $row['nombre'];
		$usuarioConfiguracion->descripcion = $row['descripcion'];
		$usuarioConfiguracion->usucreaId = $row['usucrea_id'];
		$usuarioConfiguracion->usumodifId = $row['usumodif_id'];
		$usuarioConfiguracion->fechaCrea = $row['fecha_crea'];
        $usuarioConfiguracion->fechaModif = $row['fecha_modif'];
        $usuarioConfiguracion->longitud = $row['longitud'];
        $usuarioConfiguracion->estado = $row['estado'];
        $usuarioConfiguracion->latitud = $row['latitud'];
        $usuarioConfiguracion->direccion = $row['direccion'];
        $usuarioConfiguracion->ciudadId = $row['ciudad_id'];

		return $usuarioConfiguracion;
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