<?php namespace Backend\mysql;
use Backend\dao\SorteoInternoDAO;
use Backend\dto\SorteoInterno;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Exception;
/** 
* Clase 'SorteoInternoMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'SorteoInterno'
* 
* Ejemplo de uso: 
* $SorteoInternoMySqlDAO = new SorteoInternoMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class SorteoInternoMySqlDAO implements SorteoInternoDAO{


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
		$sql = 'SELECT * FROM sorteo_interno WHERE sorteo_id = ?';
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
		$sql = 'SELECT * FROM sorteo_interno';
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Ejecutar una consulta sql 
 	 * 
 	 *
 	 * @param String $sql consulta sql
 	 *
 	 * @return Array $ resultado de la ejecución
 	 *
 	 * @access protected
 	 *
 	 */
    public function querySQL($sql)
    {
        $sqlQuery = new SqlQuery($sql);
        return $this->execute2($sqlQuery);
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
		$sql = 'SELECT * FROM sorteo_interno ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}

	
	/**
 	 * Eliminar todos los registros condicionados
 	 * por la llave primaria
 	 *
 	 * @param String $sorteo_id llave primaria
 	 *
	 * @return boolean $ resultado de la consulta
     *
 	 */
	public function delete($sorteo_id){
		$sql = 'DELETE FROM sorteo_interno WHERE sorteo_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($sorteo_id);
		return $this->executeUpdate($sqlQuery);
	}
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param Objeto SorteoInterno SorteoInterno
 	 *
	 * @return String $id resultado de la consulta
     *
 	 */
	public function insert($SorteoInterno){
		$sql = 'INSERT INTO sorteo_interno (fecha_inicio, fecha_fin, descripcion,nombre, tipo, estado, mandante, usucrea_id, usumodif_id,condicional, orden,cupo_actual,cupo_maximo,cantidad_sorteos,maximo_sorteos,codigo,reglas,pegatinas, json_temp, habilita_casino, habilita_deposito, habilita_deportivas) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';


		$sqlQuery = new SqlQuery($sql);

        $sqlQuery->set($SorteoInterno->fechaInicio);
		$sqlQuery->set($SorteoInterno->fechaFin);
        $sqlQuery->set($SorteoInterno->descripcion);
        $sqlQuery->set($SorteoInterno->nombre);
		$sqlQuery->set($SorteoInterno->tipo);
		$sqlQuery->set($SorteoInterno->estado);
		$sqlQuery->setNumber($SorteoInterno->mandante);
		$sqlQuery->setNumber($SorteoInterno->usucreaId);
        $sqlQuery->setNumber($SorteoInterno->usumodifId);
        $sqlQuery->set($SorteoInterno->condicional);
        $sqlQuery->set($SorteoInterno->orden);
        $sqlQuery->set($SorteoInterno->cupoActual);
        $sqlQuery->set($SorteoInterno->cupoMaximo);
        $sqlQuery->set($SorteoInterno->cantidadSorteos);
        $sqlQuery->set($SorteoInterno->maximoSorteos);
        $sqlQuery->set($SorteoInterno->codigo);
        $sqlQuery->set($SorteoInterno->reglas);
        $sqlQuery->setNumber($SorteoInterno->pegatinas);
		$sqlQuery->set($SorteoInterno->jsonTemp);

        $sqlQuery->setNumber($SorteoInterno->habilitaCasino);
        $sqlQuery->setNumber($SorteoInterno->habilitaDeposito);
        $sqlQuery->setNumber($SorteoInterno->habilitaDeportivas);




		$id = $this->executeInsert($sqlQuery);
		$SorteoInterno->sorteoId = $id;
		return $id;
	}
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Objeto SorteoInterno SorteoInterno
 	 *
	 * @return boolean $ resultado de la consulta
     *
 	 */
	public function update($SorteoInterno){
		$sql = 'UPDATE sorteo_interno SET fecha_inicio = ?, fecha_fin = ?, descripcion = ?,nombre=?, tipo = ?, estado = ?,  mandante = ?, usucrea_id = ?, usumodif_id = ?, condicional = ?,orden = ?, cupo_actual = ?, cupo_maximo = ?, cantidad_sorteos = ?, maximo_sorteos = ?, codigo = ?, reglas = ?, pegatinas = ?, json_temp = ?, habilita_casino=?, habilita_deposito=?, habilita_deportivas=? WHERE sorteo_id = ?';
		$sqlQuery = new SqlQuery($sql);

		$sqlQuery->set($SorteoInterno->fechaInicio);
		$sqlQuery->set($SorteoInterno->fechaFin);
        $sqlQuery->set($SorteoInterno->descripcion);
        $sqlQuery->set($SorteoInterno->nombre);
		$sqlQuery->set($SorteoInterno->tipo);
		$sqlQuery->set($SorteoInterno->estado);
		$sqlQuery->set($SorteoInterno->mandante);
		$sqlQuery->setNumber($SorteoInterno->usucreaId);
        $sqlQuery->setNumber($SorteoInterno->usumodifId);
        $sqlQuery->set($SorteoInterno->condicional);
        $sqlQuery->set($SorteoInterno->orden);
        $sqlQuery->set($SorteoInterno->cupoActual);
        $sqlQuery->set($SorteoInterno->cupoMaximo);
        $sqlQuery->set($SorteoInterno->cantidadSorteos);
        $sqlQuery->set($SorteoInterno->maximoSorteos);
        $sqlQuery->set($SorteoInterno->codigo);
        $sqlQuery->set($SorteoInterno->reglas);
        $sqlQuery->set($SorteoInterno->pegatinas);
		$sqlQuery->set($SorteoInterno->jsonTemp);

        if($SorteoInterno->habilitaCasino ==''){
            $SorteoInterno->habilitaCasino='0';
        }
        $sqlQuery->set($SorteoInterno->habilitaCasino);

        if($SorteoInterno->habilitaDeposito ==''){
            $SorteoInterno->habilitaDeposito='0';
        }
        $sqlQuery->set($SorteoInterno->habilitaDeposito);

        if($SorteoInterno->habilitaDeportivas ==''){
            $SorteoInterno->habilitaDeportivas='0';
        }
        $sqlQuery->set($SorteoInterno->habilitaDeportivas);


        $sqlQuery->setNumber($SorteoInterno->sorteoId);
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
		$sql = 'DELETE FROM sorteo_interno';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}








	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna fecha_inicio sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value fecha_inicio requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByUsuarioId($value){
		$sql = 'SELECT * FROM sorteo_interno WHERE fecha_inicio = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna fecha_fin sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value fecha_fin requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByProductoId($value){
		$sql = 'SELECT * FROM sorteo_interno WHERE fecha_fin = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna descripcion sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value descripcion requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByValorTicket($value){
		$sql = 'SELECT * FROM sorteo_interno WHERE descripcion = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna tipo sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value tipo requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByValorPremio($value){
		$sql = 'SELECT * FROM sorteo_interno WHERE tipo = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna estado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value estado requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByEstado($value){
		$sql = 'SELECT * FROM sorteo_interno WHERE estado = ?';
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
	public function queryByTransaccionId($value){
		$sql = 'SELECT * FROM sorteo_interno WHERE fecha_modif = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setString($value);
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
	public function queryByFechaPago($value){
		$sql = 'SELECT * FROM sorteo_interno WHERE fecha_crea = ?';
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
		$sql = 'SELECT * FROM sorteo_interno WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
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
		$sql = 'SELECT * FROM sorteo_interno WHERE usucrea_id = ?';
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
		$sql = 'SELECT * FROM sorteo_interno WHERE usumodif_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}






    /**
    * Realizar una consulta en la tabla de SorteoInterno 'SorteoInterno'
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
	public function querySorteosCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn)
    {

        $innSorteoDetalle=false;

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
                $cond="sorteo_detalle";
                if ( strpos($fieldName, $cond) !== false || strpos($sidx, $cond) !== false || strpos($grouping, $cond) !== false  || strpos($select, $cond) !== false)
                {
                    $innSorteoDetalle=true;
                }
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
  						$fieldOperation = " NOT IN (".$fieldData.")";
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
  					$where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
  				}
  				else
  				{
  					$where = "";
  				}
  			}

		  }




        $innerSorteoDetalle = "";
        if($innSorteoDetalle){
            $innerSorteoDetalle = " INNER JOIN sorteo_detalle on (sorteo_detalle.sorteo_id=sorteo_interno.sorteo_id) ";
        }

		  $sql = "SELECT count(*) count FROM sorteo_interno ".$innerSorteoDetalle." INNER JOIN mandante ON (sorteo_interno.mandante = mandante.mandante) " . $where;


		  $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
		$sql = "SELECT ".$select." FROM sorteo_interno ".$innerSorteoDetalle." INNER JOIN mandante ON (sorteo_interno.mandante = mandante.mandante)  " . $where ." " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

		$sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : '.json_encode($count). ', "data" : '.json_encode($result).'}';

        return  $json;
    }

    /**
     * Ejecutar una consulta sql como update
     * 
     *
     * @param String $sql consulta sql
     *
     * @return Array $ resultado de la ejecución
     *
     * @access protected
     *
     */
    public function queryUpdate($sql){
        $sqlQuery = new SqlQuery($sql);
        return $this->executeUpdate($sqlQuery);
	}











    /**
     * Crear y devolver un objeto del tipo SorteoInterno
     * con los valores de una consulta sql
     * 
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Objeto $SorteoInterno SorteoInterno
     *
     * @access protected
     *
     */
	protected function readRow($row){
		$SorteoInterno = new SorteoInterno();

		$SorteoInterno->sorteoId = $row['sorteo_id'];
		$SorteoInterno->fechaInicio = $row['fecha_inicio'];
		$SorteoInterno->fechaFin = $row['fecha_fin'];
        $SorteoInterno->descripcion = $row['descripcion'];
        $SorteoInterno->nombre = $row['nombre'];
		$SorteoInterno->tipo = $row['tipo'];
		$SorteoInterno->estado = $row['estado'];
		$SorteoInterno->mandante = $row['mandante'];
		$SorteoInterno->usucreaId = $row['usucrea_id'];
		$SorteoInterno->fechaCrea = $row['fecha_crea'];
		$SorteoInterno->usumodifId = $row['usumodif_id'];
        $SorteoInterno->fechaModif = $row['fecha_modif'];
        $SorteoInterno->orden = $row['orden'];
        $SorteoInterno->condicional = $row['condicional'];
        $SorteoInterno->cupoActual = $row['cupo_actual'];
        $SorteoInterno->cupoMaximo = $row['cupo_maximo'];
        $SorteoInterno->cantidadSorteos = $row['cantidad_sorteos'];
        $SorteoInterno->maximoSorteos = $row['maximo_sorteos'];
        $SorteoInterno->codigo = $row['codigo'];
        $SorteoInterno->reglas = $row['reglas'];
        $SorteoInterno->pegatinas = $row['pegatinas'];
		$SorteoInterno->jsonTemp = $row['json_temp'];

        $SorteoInterno->habilitaCasino = $row['habilita_casino'];
        $SorteoInterno->habilitaDeposito = $row['habilita_deposito'];
        $SorteoInterno->habilitaDeportivas = $row['habilita_deportivas'];

		return $SorteoInterno;
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