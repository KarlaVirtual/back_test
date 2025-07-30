<?php namespace Backend\mysql;

use Backend\dto\SorteoInterno2;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Exception;
use Twilio\Rest\Serverless\V1\Service\FunctionList;

/**
* Clase 'SorteoInterno2MySqlDAO'
*
* Esta clase provee las consultas del modelo o tabla 'SorteoInterno'
*
* Ejemplo de uso:
* $SorteoInternoMySqlDAO = new SorteoInterno2MySqlDAO();
*
*
* @package ninguno
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public
* @see no
*
*/


class SorteoInterno2MySqlDAO{
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


    public function load($id){
        $sql = 'SELECT * FROM sorteo_interno2 where sorteo2_id = ?';
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
		$sql = 'SELECT * FROM sorteo_interno2';
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
		$sql = 'SELECT * FROM sorteo_interno2 ORDER BY '.$orderColumn;
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
		$sql = 'DELETE FROM sorteo_interno2 WHERE sorteo2_id = ?';
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


      public function insert($SorteoInterno2){

        $sql = 'INSERT INTO sorteo_interno2 (fecha_inicio,fecha_fin,descripcion,tipo,nombre,estado,mandante,usucrea_id,usumodif_id,condicional,orden,cupo_actual,cupo_maximo,cantidad_sorteos,maximo_sorteos,codigo,reglas,json_temp) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';


        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->set($SorteoInterno2->fechaInicio);
        $sqlQuery->set($SorteoInterno2->fechaFin);
        $sqlQuery->set($SorteoInterno2->descripcion);
        $sqlQuery->set($SorteoInterno2->tipo);
        $sqlQuery->set($SorteoInterno2->nombre);
        $sqlQuery->set($SorteoInterno2->estado);
        $sqlQuery->set($SorteoInterno2->mandante);
        $sqlQuery->set($SorteoInterno2->usucreaId);
        $sqlQuery->set($SorteoInterno2->usumodifId);
        $sqlQuery->set($SorteoInterno2->condicional);
        $sqlQuery->set($SorteoInterno2->orden);
        if($SorteoInterno2->cupoActual == ""){
            $sqlQuery->set(0);
        }else{
            $sqlQuery->set($SorteoInterno2->cupoActual);
        }
        if($SorteoInterno2->cupoMaximo == ""){
            $sqlQuery->set(0);
        }else{
            $sqlQuery->set($SorteoInterno2->cupoMaximo);
        }

        if($SorteoInterno2->cantidadSorteos == ""){
            $sqlQuery->set(0);
        }else{
            $sqlQuery->set($SorteoInterno2->cantidadSorteos);
        }

        if($SorteoInterno2->maximoSorteos == ''){
            $sqlQuery->set(0);
        }else{
            $sqlQuery->set($SorteoInterno2->maximoSorteos);
        }

        if($SorteoInterno2->codigo == ''){
            $sqlQuery->set(0);
        }else{
            $sqlQuery->set($SorteoInterno2->codigo);
        }

        $sqlQuery->set($SorteoInterno2->reglas);
        $sqlQuery->set($SorteoInterno2->jsonTemp);


        $id = $this->executeInsert($sqlQuery);
        $SorteoInterno2->sorteo2Id = $id;
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


      public function update($SorteoInterno2){



          $sql = 'UPDATE sorteo_interno2 SET fecha_inicio=?,fecha_fin =?,descripcion=?,tipo=?,nombre=?,estado=?,mandante=?,usucrea_id=?,usumodif_id=?,condicional=?,orden=?,cupo_actual=?,cupo_maximo=?,cantidad_sorteos=?,maximo_sorteos=?,codigo=?,reglas=?,json_temp=? WHERE sorteo2_id=?';

        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->set($SorteoInterno2->fechaInicio);
        $sqlQuery->set($SorteoInterno2->fechaFin);
        $sqlQuery->set($SorteoInterno2->descripcion);
        $sqlQuery->set($SorteoInterno2->tipo);
        $sqlQuery->set($SorteoInterno2->nombre);
        $sqlQuery->set($SorteoInterno2->estado);
        $sqlQuery->set($SorteoInterno2->mandante);
        if($SorteoInterno2->usucreaId == ''){
            $sqlQuery->set(0);
        }else{
            $sqlQuery->set($SorteoInterno2->usucreaId);
        }
        $sqlQuery->set($SorteoInterno2->usumodifId);
        $sqlQuery->set($SorteoInterno2->condicional);
        $sqlQuery->set($SorteoInterno2->orden);
        $sqlQuery->set($SorteoInterno2->cupoActual);
        $sqlQuery->set($SorteoInterno2->cupoMaximo);
        $sqlQuery->set($SorteoInterno2->cantidadSorteos);
        $sqlQuery->set($SorteoInterno2->maximoSorteos);
        $sqlQuery->set($SorteoInterno2->codigo);
        $sqlQuery->set($SorteoInterno2->reglas);
        // if($SorteoInterno2->reglas != ""){
        //     $SorteoInterno2->reglas = str_replace("'","\'",$SorteoInterno2->reglas);
        // }
        $sqlQuery->set($SorteoInterno2->jsonTemp);
        $sqlQuery->set($SorteoInterno2->sorteoId);

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
		$sql = 'DELETE FROM sorteo_interno2';
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
            $sql = 'SELECT * FROM sorteo_interno2 WHERE sorteo2_id = ?';
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

      public function queryByStartDate($value){
        $sql = 'SELECT * FROM sorteo_interno2 where fecha_inicio = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);
      }


     public function queryByEndDate($value){
        $sql = 'SELECT * FROM sorteo_interno2 where fecha_fin = ?';
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

     public function queryByDescription($value){
        $sql = 'SELECT * FROM sorteo_interno2 where fecha_fin = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);
     }


     public function queryByPrizeValue($value){
        $sql = 'SELECT * FROM sorteo_interno2 WHERE tipo = ?';
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
		$sql = 'SELECT * FROM sorteo_interno2 WHERE estado = ?';
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
		$sql = 'SELECT * FROM sorteo_interno2 WHERE fecha_modif = ?';
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
		$sql = 'SELECT * FROM sorteo_interno2 WHERE fecha_crea = ?';
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
		$sql = 'SELECT * FROM sorteo_interno2 WHERE mandante = ?';
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
		$sql = 'SELECT * FROM sorteo_interno2 WHERE usucrea_id = ?';
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
		$sql = 'SELECT * FROM sorteo_interno2 WHERE usumodif_id = ?';
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
  				if (count($whereArray)>0)
  				{
  					$where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
  				}
  				else
  				{
  					$where = "";
  				}
  			}

		  }



		  $sql = "SELECT count(*) count FROM sorteo_interno2 INNER JOIN mandante ON (sorteo_interno2.mandante = mandante.mandante) " . $where;


		  $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
		$sql = "SELECT ".$select." FROM sorteo_interno2 INNER JOIN mandante ON (sorteo_interno2.mandante = mandante.mandante)" . $where ." " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

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
		$SorteoInterno2 = new SorteoInterno2();

		$SorteoInterno2->sorteoId = $row['sorteo2_id'];
		$SorteoInterno2->fechaInicio = $row['fecha_inicio'];
		$SorteoInterno2->fechaFin = $row['fecha_fin'];
        $SorteoInterno2->descripcion = $row['descripcion'];
        $SorteoInterno2->nombre = $row['nombre'];
		$SorteoInterno2->tipo = $row['tipo'];
		$SorteoInterno2->estado = $row['estado'];
		$SorteoInterno2->mandante = $row['mandante'];
		$SorteoInterno2->fechaCrea = $row['fecha_crea'];
		$SorteoInterno2->usumodifId = $row['usumodif_id'];
        $SorteoInterno2->fechaModif = $row['fecha_modif'];
        $SorteoInterno2->orden = $row['orden'];
        $SorteoInterno2->condicional = $row['condicional'];
        $SorteoInterno2->cupoActual = $row['cupo_actual'];
        $SorteoInterno2->cupoMaximo = $row['cupo_maximo'];
        $SorteoInterno2->cantidadSorteos = $row['cantidad_sorteos'];
        $SorteoInterno2->maximoSorteos = $row['maximo_sorteos'];
        $SorteoInterno2->codigo = $row['codigo'];
        $SorteoInterno2->reglas = $row['reglas'];
		$SorteoInterno2->jsonTemp = $row['json_temp'];
        return $SorteoInterno2;
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
		for($i=0;$i<count($tab);$i++){
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
		if(count($tab)==0){
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