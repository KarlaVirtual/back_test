<?php namespace Backend\mysql;
/**
 * Class that operate on table 'usuarioCierrecaja'. Database Mysql.
 *
 * @author: Daniel Tamayo
 * @date: 2017-09-06 18:55
 * @package No
 * @category No
 * @version    1.0
 */

use Backend\dao\UsuarioCierrecajaDAO;
use Backend\dto\Helpers;
use Backend\dto\UsuarioCierrecaja;
use Backend\sql\QueryExecutor;
use Backend\sql\SqlQuery;
use Backend\sql\Transaction;
class UsuarioCierrecajaMySqlDAO implements UsuarioCierrecajaDAO{


    private $transaction;

    /**
     * @return mixed
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * @param mixed $transaction
     */
    public function setTransaction($transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * TransaccionProductoMySqlDAO constructor.
     * @param $transaction
     */

    public function __construct($transaction="")
    {
        if ($transaction == "") {

            $transaction = new Transaction();
            $this->transaction = $transaction;

        } else {
            $this->transaction = $transaction;
        }
    }



    /**
	 * Get Domain object by primry key
	 *
	 * @param String $id primary key
	 * @return UsuarioCierrecajaMySql 
	 */
	public function load($id){
		$sql = 'SELECT * FROM usuario_cierrecaja WHERE usucierrecaja_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($id);
		return $this->getRow($sqlQuery);
	}

	/**
	 * Get all records from table
	 */
	public function queryAll(){
		$sql = 'SELECT * FROM usuario_cierrecaja';
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
	/**
	 * Get all records from table ordered by field
	 *
	 * @param $orderColumn column name
	 */
	public function queryAllOrderBy($orderColumn){
		$sql = 'SELECT * FROM usuario_cierrecaja ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
	/**
 	 * Delete record from table
 	 * @param usuarioCierrecaja primary key
 	 */
	public function delete($usucierrecaja_id){
		$sql = 'DELETE FROM usuario_cierrecaja WHERE usucierrecaja_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($usucierrecaja_id);
		return $this->executeUpdate($sqlQuery);
	}
	
	/**
 	 * Insert record to table
 	 *
 	 * @param UsuarioCierrecajaMySql usuarioCierrecaja
 	 */
	public function insert($usuarioCierrecaja){
		$sql = 'INSERT INTO usuario_cierrecaja (usuario_id, fecha_cierre, ingresos_propios,usucrea_id,usumodif_id,egresos_propios,ingresos_productos,egresos_productos,ingresos_otros,egresos_otros,dinero_inicial,ingresos_tarjetacredito) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($usuarioCierrecaja->usuarioId);
		$sqlQuery->set($usuarioCierrecaja->fechaCierre);
		$sqlQuery->set($usuarioCierrecaja->ingresosPropios);
        $sqlQuery->set($usuarioCierrecaja->usucreaId);
        $sqlQuery->set($usuarioCierrecaja->usumodifId);

        $sqlQuery->set($usuarioCierrecaja->egresosPropios);
        $sqlQuery->set($usuarioCierrecaja->ingresosProductos);
        $sqlQuery->set($usuarioCierrecaja->egresosProductos);
        $sqlQuery->set($usuarioCierrecaja->ingresosOtros);
        $sqlQuery->set($usuarioCierrecaja->egresosOtros);
        $sqlQuery->set($usuarioCierrecaja->dineroInicial);
        $sqlQuery->set($usuarioCierrecaja->ingresosTarjetacredito);

		$id = $this->executeInsert($sqlQuery);	
		$usuarioCierrecaja->usucierrecajaId = $id;
		return $id;
	}
	
	/**
 	 * Update record in table
 	 *
 	 * @param UsuarioCierrecajaMySql usuarioCierrecaja
 	 */
	public function update($usuarioCierrecaja){
		$sql = 'UPDATE usuario_cierrecaja SET usuario_id = ?, fecha_cierre = ?, ingresos_propios = ?,usucrea_id = ?,usumodif_id = ?,egresos_propios = ?,ingresos_productos = ?,egresos_productos = ?,ingresos_otros = ?,egresos_otros = ?, dinero_inicial = ?, ingresos_tarjetacredito = ? WHERE usucierrecaja_id = ?';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($usuarioCierrecaja->usuarioId);
		$sqlQuery->set($usuarioCierrecaja->fechaCierre);
		$sqlQuery->set($usuarioCierrecaja->ingresosPropios);
        $sqlQuery->set($usuarioCierrecaja->usucreaId);
        $sqlQuery->set($usuarioCierrecaja->usumodifId);

        $sqlQuery->set($usuarioCierrecaja->egresosPropios);
        $sqlQuery->set($usuarioCierrecaja->ingresosProductos);
        $sqlQuery->set($usuarioCierrecaja->egresosProductos);
        $sqlQuery->set($usuarioCierrecaja->ingresosOtros);
        $sqlQuery->set($usuarioCierrecaja->egresosOtros);
        $sqlQuery->set($usuarioCierrecaja->dineroInicial);
        $sqlQuery->set($usuarioCierrecaja->ingresosTarjetacredito);

		$sqlQuery->set($usuarioCierrecaja->usucierrecajaId);


		return $this->executeUpdate($sqlQuery);
	}


	/**
	 * Executes a custom query on the `usuario_cierrecaja` table with various filters and sorting options.
	 *
	 * @param string $select The columns to select in the query.
	 * @param string $sidx The column by which to sort the results.
	 * @param string $sord The sorting order (ASC or DESC).
	 * @param int $start The starting point for the result set.
	 * @param int $limit The maximum number of results to return.
	 * @param string $filters JSON encoded string containing the filters to apply.
	 * @param bool $searchOn Flag indicating whether to apply the filters.
	 * 
	 * @return string JSON encoded string containing the count of results and the data.
	 */
    public function queryUsuarioCierrecajaesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {


        $where = " where 1=1 ";

		$Helpers = new Helpers();

        if ($searchOn) {
            // Construye el where
            $filters = json_decode($filters);
            $whereArray = array();
            $rules = $filters->rules;
            $groupOperation = $filters->groupOp;
            $cont = 0;

            foreach ($rules as $rule) {
                $fieldName = $Helpers->set_custom_field($rule->field);
                $fieldData = $rule->data;
                

                $Helpers = new Helpers();

                if($fieldName == 'registro.cedula'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_DOCUMENT']);
                }
                if($fieldName == 'registro.celular'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_PHONE']);
                }
                if($fieldName == 'usuario.cedula'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_DOCUMENT']);
                }
                if($fieldName == 'usuario.login'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }
                if($fieldName == 'usuario_mandante.email'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }
                if($fieldName == 'punto_venta.email'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }
                if($fieldName == 'usuario_sitebuilder.login'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }
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


        $sql = 'SELECT count(*) count FROM usuario_cierrecaja INNER JOIN usuario ON (usuario.usuario_id=usuario_cierrecaja.usuario_id)    LEFT OUTER JOIN concesionario ON (usuario.puntoventa_id = concesionario.usuhijo_id and prodinterno_id=0 AND concesionario.estado=\'A\')  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = usuario.usuario_id )  ' . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT ' .$select .'  FROM usuario_cierrecaja INNER JOIN usuario ON (usuario.usuario_id=usuario_cierrecaja.usuario_id)    LEFT OUTER JOIN concesionario ON (usuario.puntoventa_id = concesionario.usuhijo_id and prodinterno_id=0 AND concesionario.estado=\'A\')  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = usuario.usuario_id )  ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;


        $sqlQuery = new SqlQuery($sql);

        $result = $Helpers->process_data($this->execute2($sqlQuery));

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }



    /**
 	 * Delete all rows
 	 */
	public function clean(){
		$sql = 'DELETE FROM usuario_cierrecaja';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}

/**
 * Query records from the `usuario_cierrecaja` table by `usuarioId`.
 *
 * @param mixed $value The value of `usuarioId` to filter by.
 * @return array The list of matching records.
 */
public function queryByTipo($value){
	$sql = 'SELECT * FROM usuario_cierrecaja WHERE usuarioId = ?';
	$sqlQuery = new SqlQuery($sql);
	$sqlQuery->set($value);
	return $this->getList($sqlQuery);
}

/**
 * Query records from the `usuario_cierrecaja` table by `fechaCierre`.
 *
 * @param mixed $value The value of `fechaCierre` to filter by.
 * @return array The list of matching records.
 */
public function queryByDescripcion($value){
	$sql = 'SELECT * FROM usuario_cierrecaja WHERE fechaCierre = ?';
	$sqlQuery = new SqlQuery($sql);
	$sqlQuery->set($value);
	return $this->getList($sqlQuery);
}

/**
 * Query records from the `usuario_cierrecaja` table by `ingresosPropios`.
 *
 * @param mixed $value The value of `ingresosPropios` to filter by.
 * @return array The list of matching records.
 */
public function queryByEstado($value){
	$sql = 'SELECT * FROM usuario_cierrecaja WHERE ingresosPropios = ?';
	$sqlQuery = new SqlQuery($sql);
	$sqlQuery->set($value);
	return $this->getList($sqlQuery);
}

/**
 * Query records from the `usuario_cierrecaja` table by `mandante`.
 *
 * @param mixed $value The value of `mandante` to filter by.
 * @return array The list of matching records.
 */
public function queryByMandante($value){
	$sql = 'SELECT * FROM usuario_cierrecaja WHERE mandante = ?';
	$sqlQuery = new SqlQuery($sql);
	$sqlQuery->set($value);
	return $this->getList($sqlQuery);
}

/**
 * Query records from the `usuario_cierrecaja` table by `codigo`.
 *
 * @param mixed $value The value of `codigo` to filter by.
 * @return array The list of matching records.
 */
public function queryByAbreviado($value){
    $sql = 'SELECT * FROM usuario_cierrecaja WHERE codigo = ?';
    $sqlQuery = new SqlQuery($sql);
    $sqlQuery->set($value);
    return $this->getList($sqlQuery);
}

/**
 * Delete records from the `usuario_cierrecaja` table by `usuarioId`.
 *
 * @param mixed $value The value of `usuarioId` to filter by.
 * @return int The number of rows affected.
 */
public function deleteByTipo($value){
	$sql = 'DELETE FROM usuario_cierrecaja WHERE usuarioId = ?';
	$sqlQuery = new SqlQuery($sql);
	$sqlQuery->set($value);
	return $this->executeUpdate($sqlQuery);
}

/**
 * Delete records from the `usuario_cierrecaja` table by `fechaCierre`.
 *
 * @param mixed $value The value of `fechaCierre` to filter by.
 * @return int The number of rows affected.
 */
public function deleteByDescripcion($value){
	$sql = 'DELETE FROM usuario_cierrecaja WHERE fechaCierre = ?';
	$sqlQuery = new SqlQuery($sql);
	$sqlQuery->set($value);
	return $this->executeUpdate($sqlQuery);
}

/**
 * Delete records from the `usuario_cierrecaja` table by `ingresosPropios`.
 *
 * @param mixed $value The value of `ingresosPropios` to filter by.
 * @return int The number of rows affected.
 */
public function deleteByEstado($value){
	$sql = 'DELETE FROM usuario_cierrecaja WHERE ingresosPropios = ?';
	$sqlQuery = new SqlQuery($sql);
	$sqlQuery->set($value);
	return $this->executeUpdate($sqlQuery);
}

/**
 * Delete records from the `usuario_cierrecaja` table by `mandante`.
 *
 * @param mixed $value The value of `mandante` to filter by.
 * @return int The number of rows affected.
 */
public function deleteByMandante($value){
	$sql = 'DELETE FROM usuario_cierrecaja WHERE mandante = ?';
	$sqlQuery = new SqlQuery($sql);
	$sqlQuery->set($value);
	return $this->executeUpdate($sqlQuery);
}

	
	/**
	 * Read row
	 *
	 * @return UsuarioCierrecajaMySql 
	 */
	protected function readRow($row){
		$usuarioCierrecaja = new UsuarioCierrecaja();
		
		$usuarioCierrecaja->usucierrecajaId = $row['usucierrecaja_id'];
		$usuarioCierrecaja->usuarioId = $row['usuario_id'];
		$usuarioCierrecaja->fechaCierre = $row['fecha_cierre'];
		$usuarioCierrecaja->ingresosPropios = $row['ingresos_propios'];
        $usuarioCierrecaja->usucreaId = $row['usucrea_id'];
        $usuarioCierrecaja->usumodifId = $row['usumodif_id'];

        $usuarioCierrecaja->egresosPropios = $row['egresos_propios'];
        $usuarioCierrecaja->ingresosProductos = $row['ingresos_productos'];
        $usuarioCierrecaja->egresosProductos = $row['egresos_productos'];
        $usuarioCierrecaja->ingresosOtros = $row['ingresos_otros'];
        $usuarioCierrecaja->egresosOtros = $row['egresos_otros'];
        $usuarioCierrecaja->dineroInicial = $row['dinero_inicial'];
        $usuarioCierrecaja->ingresosTarjetacredito = $row['ingresos_tarjetacredito'];


		return $usuarioCierrecaja;
	}
	
	/**
	 * Retrieves a list of rows from the database based on the provided SQL query.
	 *
	 * @param SqlQuery $sqlQuery The SQL query to be executed.
	 * @return array An array of rows retrieved from the database.
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
	 * Get row
	 *
	 * @return UsuarioCierrecajaMySql 
	 */
	protected function getRow($sqlQuery){
		$tab = QueryExecutor::execute($this->transaction,$sqlQuery);
		if(oldCount($tab)==0){
			return null;
		}
		return $this->readRow($tab[0]);		
	}
	
	/**
	 * Execute sql query
	 */
	protected function execute($sqlQuery){
		return QueryExecutor::execute($this->transaction,$sqlQuery);
	}

    /**
     * Execute2 sql query
     */
    protected function execute2($sqlQuery)
    {
        return QueryExecutor::execute2($this->transaction, $sqlQuery);
    }
	
		
	/**
	 * Execute sql query
	 */
	protected function executeUpdate($sqlQuery){
		return QueryExecutor::executeUpdate($this->transaction,$sqlQuery);
	}

	/**
	 * Query for one row and one column
	 */
	protected function querySingleResult($sqlQuery){
		return QueryExecutor::queryForString($this->transaction,$sqlQuery);
	}

	/**
	 * Insert row to table
	 */
	protected function executeInsert($sqlQuery){
		return QueryExecutor::executeInsert($this->transaction,$sqlQuery);
	}
}
?>