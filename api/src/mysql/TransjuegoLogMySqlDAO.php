<?php namespace Backend\mysql;
use Backend\dao\TransjuegoLogDAO;
use Backend\dto\TransjuegoLog;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
/** 
* Clase 'TransjuegoLogMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'TransjuegoLog'
* 
* Ejemplo de uso: 
* $TransjuegoLogMySqlDAO = new TransjuegoLogMySqlDAO();
*	
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class TransjuegoLogMySqlDAO implements TransjuegoLogDAO
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
	public function load($id)
	{
		$sql = 'SELECT * FROM transjuego_log WHERE transjuegolog_id = ?';
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
	public function queryAll()
	{
		$sql = 'SELECT * FROM transjuego_log';
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
		$sql = 'SELECT * FROM transjuego_log ORDER BY ' . $orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros condicionados
 	 * por la llave primaria
 	 *
 	 * @param String $transjuegolog_id llave primaria
 	 *
	 * @return boolean $ resultado de la consulta
     *
 	 */
	public function delete($transjuegolog_id)
	{
		$sql = 'DELETE FROM transjuego_log WHERE transjuegolog_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($transjuegolog_id);
		return $this->executeUpdate($sqlQuery);
	}






   	/**
   	* Realizar una consulta en la tabla de TransjuegoLog 'TransjuegoLog'
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
    public function queryTransaccionesCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping)
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
                        $fieldOperation = " NOT IN '".$fieldData."'";
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


        if($grouping != ""){
            $where = $where . " GROUP BY " . $grouping;
        }

//INNER JOIN transaccion_api ON (transaccion_juego.ticket_id = transaccion_api.identificador)
        $sql = "SELECT count(*) count FROM transjuego_log      INNER JOIN transaccion_juego ON (transaccion_juego.transjuego_id = transjuego_log.transjuego_id)  " . $where;

        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
        $sql = "SELECT ".$select." FROM transjuego_log  INNER JOIN transaccion_juego ON (transaccion_juego.transjuego_id = transjuego_log.transjuego_id) " . $where ." " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : '.json_encode($count). ', "data" : '.json_encode($result).'}';

        return  $json;
    }



	/**
	 * Realiza una consulta personalizada de transacciones con múltiples filtros y opciones de agrupamiento.
	 *
	 * @param string $select Campos a seleccionar en la consulta.
	 * @param string $sidx Campo por el cual se ordenarán los resultados.
	 * @param string $sord Orden de los resultados (ASC o DESC).
	 * @param int $start Índice de inicio para la paginación.
	 * @param int $limit Número de registros a devolver.
	 * @param string $filters Filtros en formato JSON para construir la cláusula WHERE.
	 * @param bool $searchOn Indica si se deben aplicar los filtros.
	 * @param string $grouping Campo por el cual se agruparán los resultados.
	 * @return string JSON con el conteo total de registros y los datos de las transacciones.
	 */
    public function queryTransaccionesCustom2($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping)
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
                        $fieldOperation = " NOT IN '".$fieldData."'";
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


        if($grouping != ""){
            $where = $where . " GROUP BY " . $grouping;
        }

//INNER JOIN transaccion_api ON (transaccion_juego.ticket_id = transaccion_api.identificador)
        $sql = "SELECT count(*) count FROM transjuego_log INNER JOIN transaccion_juego ON (transaccion_juego.transjuego_id = transjuego_log.transjuego_id) INNER JOIN producto_mandante ON (producto_mandante.prodmandante_id = transaccion_juego.producto_id) INNER JOIN producto ON ( producto.producto_id = producto_mandante.producto_id) INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id)  " . $where;

        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
        $sql = "SELECT ".$select." FROM transjuego_log INNER JOIN transaccion_juego ON (transaccion_juego.transjuego_id = transjuego_log.transjuego_id) INNER JOIN producto_mandante ON (producto_mandante.prodmandante_id = transaccion_juego.producto_id) INNER JOIN producto ON ( producto.producto_id = producto_mandante.producto_id) INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id) " . $where ." " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;
        //print_r($sql);
        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : '.json_encode($count). ', "data" : '.json_encode($result).'}';

        return  $json;
    }

	/**
	 * Realiza una consulta personalizada de transacciones con filtros y agrupamiento.
	 *
	 * @param string $select Campos a seleccionar en la consulta.
	 * @param string $sidx Campo por el cual se ordenarán los resultados.
	 * @param string $sord Orden de los resultados (ASC o DESC).
	 * @param int $start Índice de inicio para la paginación.
	 * @param int $limit Límite de resultados para la paginación.
	 * @param string $filters Filtros en formato JSON para construir la cláusula WHERE.
	 * @param bool $searchOn Indica si se deben aplicar los filtros.
	 * @param string $grouping Campo por el cual se agruparán los resultados.
	 *
	 * @return string JSON con el conteo de resultados y los datos de las transacciones.
	 */
    public function queryTransaccionesCustom3($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping)
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
                        $fieldOperation = " NOT IN '".$fieldData."'";
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


        if($grouping != ""){
            $where = $where . " GROUP BY " . $grouping;
        }

//INNER JOIN transaccion_api ON (transaccion_juego.ticket_id = transaccion_api.identificador)
        $sql = "SELECT count(*) count FROM transjuego_log INNER JOIN transaccion_juego ON (transaccion_juego.transjuego_id = transjuego_log.transjuego_id) INNER JOIN producto_mandante ON (producto_mandante.prodmandante_id = transaccion_juego.producto_id) INNER JOIN producto ON ( producto.producto_id = producto_mandante.producto_id) INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id)   INNER JOIN subproveedor ON (subproveedor.subproveedor_id = transjuego_log.proveedor_id) " . $where;

        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
        $sql = "SELECT ".$select." FROM transjuego_log INNER JOIN transaccion_juego ON (transaccion_juego.transjuego_id = transjuego_log.transjuego_id) INNER JOIN producto_mandante ON (producto_mandante.prodmandante_id = transaccion_juego.producto_id) INNER JOIN producto ON ( producto.producto_id = producto_mandante.producto_id) INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id)  INNER JOIN subproveedor ON (subproveedor.subproveedor_id = transjuego_log.proveedor_id)  " . $where ." " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;
        //print_r($sql);
        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : '.json_encode($count). ', "data" : '.json_encode($result).'}';

        return  $json;
    }







    /**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param Objeto transjuegoLog transjuegoLog
 	 *
	 * @return String $id resultado de la consulta
     *
 	 */
	public function insert($transjuegoLog)
	{

        if($transjuegoLog->proveedorId == ""){
            $transjuegoLog->proveedorId=0;
        }

        $sql = 'INSERT INTO casino_transprovisional (transaccion_id,proveedor_id) VALUES (?,?)';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setString($transjuegoLog->transaccionId.'_'.$transjuegoLog->proveedorId);
        $sqlQuery->setString($transjuegoLog->proveedorId);
        $idprov = $this->executeInsert($sqlQuery);


        $sql = 'INSERT INTO transjuego_log (transjuego_id, tipo, transaccion_id, t_value,valor, usucrea_id, usumodif_id, saldo_creditos, saldo_creditos_base,saldo_bonos,saldo_free,proveedor_id,producto_id) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);

		$sqlQuery->setNumber($transjuegoLog->transjuegoId);
		$sqlQuery->set($transjuegoLog->tipo);
		$sqlQuery->setString($transjuegoLog->transaccionId.'_'.$transjuegoLog->proveedorId);
        //$sqlQuery->set($transjuegoLog->tValue);
        $sqlQuery->set("");
        $sqlQuery->set($transjuegoLog->valor);
		$sqlQuery->setNumber($transjuegoLog->usucreaId);
        $sqlQuery->setNumber($transjuegoLog->usumodifId);

        if($transjuegoLog->saldoCreditos == ""){
            $transjuegoLog->saldoCreditos=0;
        }
        if($transjuegoLog->saldoCreditosBase == ""){
            $transjuegoLog->saldoCreditosBase=0;
        }
        if($transjuegoLog->saldoBonos == ""){
            $transjuegoLog->saldoBonos=0;
        }
        if($transjuegoLog->saldoFree == ""){
            $transjuegoLog->saldoFree=0;
        }

        $sqlQuery->set($transjuegoLog->saldoCreditos);
        $sqlQuery->set($transjuegoLog->saldoCreditosBase);
        $sqlQuery->set($transjuegoLog->saldoBonos);
        $sqlQuery->set($transjuegoLog->saldoFree);



        if($transjuegoLog->productoId == ""){
            $transjuegoLog->productoId=0;
        }
        $sqlQuery->set($transjuegoLog->proveedorId);
        $sqlQuery->set($transjuegoLog->productoId);



        $id = $this->executeInsert($sqlQuery);
		$transjuegoLog->transjuegologId = $id;
		return $id;
	}

	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Objeto transjuegoLog transjuegoLog
 	 *
	 * @return boolean $ resultado de la consulta
     *
 	 */
	public function update($transjuegoLog)
	{
		$sql = 'UPDATE transjuego_log SET transjuego_id = ?, tipo = ?, transaccion_id = ?, t_value = ?, valor = ?,usucrea_id = ?, usumodif_id = ?, saldo_creditos = ?, saldo_creditos_base = ?,saldo_bonos = ?,saldo_free = ? WHERE transjuegolog_id = ?';
		$sqlQuery = new SqlQuery($sql);

		$sqlQuery->setNumber($transjuegoLog->transjuegoId);
		$sqlQuery->set($transjuegoLog->tipo);
		$sqlQuery->setString($transjuegoLog->transaccionId);
        $sqlQuery->set($transjuegoLog->tValue);
        $sqlQuery->set($transjuegoLog->valor);
		$sqlQuery->setNumber($transjuegoLog->usucreaId);
		$sqlQuery->setNumber($transjuegoLog->usumodifId);

        if($transjuegoLog->saldoCreditos == ""){
            $transjuegoLog->saldoCreditos=0;
        }
        if($transjuegoLog->saldoCreditosBase == ""){
            $transjuegoLog->saldoCreditosBase=0;
        }
        if($transjuegoLog->saldoBonos == ""){
            $transjuegoLog->saldoBonos=0;
        }
        if($transjuegoLog->saldoFree == ""){
            $transjuegoLog->saldoFree=0;
        }

        $sqlQuery->set($transjuegoLog->saldoCreditos);
        $sqlQuery->set($transjuegoLog->saldoCreditosBase);
        $sqlQuery->set($transjuegoLog->saldoBonos);
        $sqlQuery->set($transjuegoLog->saldoFree);


        $sqlQuery->setNumber($transjuegoLog->transjuegologId);
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
		$sql = 'DELETE FROM transjuego_log';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}







	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna transjuego_id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value transjuego_id requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByTransjuegoId($value)
	{
		$sql = 'SELECT * FROM transjuego_log WHERE transjuego_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * las columnas transjuego_id y tipo sean iguales
 	 * a los valores pasados como parámetros
 	 *
 	 * @param String $value transjuego_id requerido
 	 * @param String $tipo tipo requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
    public function queryByTransjuegoIdAndTipo($value,$tipo)
    {
        $sql = 'SELECT * FROM transjuego_log WHERE transjuego_id = ? AND tipo=?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        $sqlQuery->set($tipo);
        return $this->getList($sqlQuery);
    }


	/**
	 * Consulta registros en la tabla transjuego_log filtrados por transaccion_id y proveedor_id.
	 *
	 * @param mixed $value El valor del transaccion_id a buscar.
	 * @param mixed $proveedor El valor del proveedor_id a buscar.
	 * @return array Lista de registros que coinciden con los criterios de búsqueda.
	 */
	public function queryByTransaccionIdAndProveedor($value,$proveedor)
	{
		$sql = 'SELECT * FROM transjuego_log WHERE transaccion_id = ? AND proveedor_id=?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		$sqlQuery->set($proveedor);
		return $this->getList($sqlQuery);
	}


	/**
	 * Consulta registros en la tabla transjuego_log filtrando por transaccion_id y producto_id.
	 *
	 * @param mixed $value El valor del transaccion_id a buscar.
	 * @param mixed $producto El valor del producto_id a buscar.
	 * @return array Lista de registros que coinciden con los criterios de búsqueda.
	 */
	public function queryByTransaccionIdAndProducto($value,$producto)
	{
		$sql = 'SELECT * FROM transjuego_log WHERE transaccion_id = ? AND producto_id=?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		$sqlQuery->set($producto);
		return $this->getList($sqlQuery);
	}
	
	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * las columnas transjuego_id y transaccion_id sean iguales
 	 * a los valores pasados como parámetros
 	 *
 	 * @param String $value transjuego_id requerido
 	 * @param String $transaccionId transaccion_id requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
    public function queryByTransjuegoIdAndTransaccionId($value,$transaccionId)
    {
        $sql = 'SELECT * FROM transjuego_log WHERE transjuego_id = ? AND transaccion_id=?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        $sqlQuery->set($transaccionId);
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
	public function queryByTipo($value)
	{
		$sql = 'SELECT * FROM transjuego_log WHERE tipo = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}
	
	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna transaccion_id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value transaccion_id requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByTransaccionId($value)
	{
		$sql = 'SELECT * FROM transjuego_log WHERE transaccion_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setString($value);
		return $this->getList($sqlQuery);
	}
	
	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna t_value sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value t_value requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByTValue($value)
	{
		$sql = 'SELECT * FROM transjuego_log WHERE t_value = ?';
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
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByFechaCrea($value)
	{
		$sql = 'SELECT * FROM transjuego_log WHERE fecha_crea = ?';
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
	public function queryByUsucreaId($value)
	{
		$sql = 'SELECT * FROM transjuego_log WHERE usucrea_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
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
	public function queryByFechaModif($value)
	{
		$sql = 'SELECT * FROM transjuego_log WHERE fecha_modif = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
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
	public function queryByUsumodifId($value)
	{
		$sql = 'SELECT * FROM transjuego_log WHERE usumodif_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}










	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna transjuego_id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value transjuego_id requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByTransjuegoId($value)
	{
		$sql = 'DELETE FROM transjuego_log WHERE transjuego_id = ?';
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
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByTipo($value)
	{
		$sql = 'DELETE FROM transjuego_log WHERE tipo = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna transaccion_id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value transaccion_id requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByTransaccionId($value)
	{
		$sql = 'DELETE FROM transjuego_log WHERE transaccion_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna t_value sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value t_value requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByTValue($value)
	{
		$sql = 'DELETE FROM transjuego_log WHERE t_value = ?';
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
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByFechaCrea($value)
	{
		$sql = 'DELETE FROM transjuego_log WHERE fecha_crea = ?';
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
	public function deleteByUsucreaId($value)
	{
		$sql = 'DELETE FROM transjuego_log WHERE usucrea_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
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
	public function deleteByFechaModif($value)
	{
		$sql = 'DELETE FROM transjuego_log WHERE fecha_modif = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
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
	public function deleteByUsumodifId($value)
	{
		$sql = 'DELETE FROM transjuego_log WHERE usumodif_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}








	/**
 	 * Crear y devolver un objeto del tipo TransjuegoLog
 	 * con los valores de una consulta sql
 	 * 
 	 *
 	 * @param Arreglo $row arreglo asociativo
 	 *
 	 * @return Objeto $transjuegoLog TransjuegoLog
 	 *
 	 * @access protected
 	 *
 	 */
	protected function readRow($row)
	{
		$transjuegoLog = new TransjuegoLog();

		$transjuegoLog->transjuegologId = $row['transjuegolog_id'];
		$transjuegoLog->transjuegoId = $row['transjuego_id'];
		$transjuegoLog->tipo = $row['tipo'];
		$transjuegoLog->transaccionId = $row['transaccion_id'];
        $transjuegoLog->tValue = $row['t_value'];
        $transjuegoLog->valor = $row['valor'];
		$transjuegoLog->fechaCrea = $row['fecha_crea'];
		$transjuegoLog->usucreaId = $row['usucrea_id'];
		$transjuegoLog->fechaModif = $row['fecha_modif'];
		$transjuegoLog->usumodifId = $row['usumodif_id'];

        $transjuegoLog->saldoCreditos = $row['saldo_creditos'];
        $transjuegoLog->saldoCreditosBase = $row['saldo_creditos_base'];
        $transjuegoLog->saldoBonos = $row['saldo_bonos'];
        $transjuegoLog->saldoFree = $row['saldo_free'];

		return $transjuegoLog;
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

    public function querySQL($sql)
    {
        $sqlQuery = new SqlQuery($sql);
        return $this->execute2($sqlQuery);
    }
}
?>
