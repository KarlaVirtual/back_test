<?php namespace Backend\mysql;

use Backend\dto\CuentaAsociada;
use Backend\dto\Helpers;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Backend\sql\Transaction;

/** 
 * Clase CuentaAsociadaMySqlDAO
 * Provee las consultas vinculadas a la tabla cuenta_asociada de la base de datos
 * 
 * @author Desconocido
 * @since: Desconocido
 * @category No
 * @package No
 * @version     1.0
 */
class CuentaAsociadaMySqlDAO{

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
     * Constructor de la clase CuentaAsociadaMySqlDAO.
     *
     * @param Transaction $transaction Objeto de transacción opcional.
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
     * Cargar una cuenta asociada por ID.
     *
     * @param int $id ID de la cuenta asociada.
     * @return CuentaAsociada Objeto CuentaAsociada.
     */
    public function load($id){
        $sql = "SELECT * FROM cuenta_asociada WHERE cuentaasociada_id = ?";
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($id);
        return $this->getRow($sqlQuery);
    }

    /**
     * Consultar cuentas asociadas por ID de usuario.
     *
     * @param int $value ID del usuario.
     * @return array Lista de objetos CuentaAsociada.
     */
    public function queryByUsuarioId($value){
        $sql = "SELECT * FROM cuenta_asociada WHERE usuario_id = ?";
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Consultar cuentas asociadas por segundo ID de usuario.
     *
     * @param int $value Segundo ID del usuario.
     * @return array Lista de objetos CuentaAsociada.
     */
    public function queryByUsuarioId2($value){
        $sql = "SELECT * FROM cuenta_asociada where usuario_id2 = ?";
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Consultar todas las cuentas asociadas ordenadas por una columna.
     *
     * @param string $orderColumn Columna para ordenar.
     * @return array Lista de objetos CuentaAsociada.
     */
    public function queryAllOrderBy($orderColumn){
        $sql = 'SELECT * FROM cuenta_asociada ORDER BY'.$orderColumn;
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }

    /**
     * Consultar todas las cuentas asociadas.
     *
     * @return array Lista de objetos CuentaAsociada.
     */
    public function queryAll(){
        $sql = "SELECT * FROM cuenta_asociada";
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }

    /**
     * Eliminar una cuenta asociada por ID.
     *
     * @param int $id ID de la cuenta asociada.
     * @return int Número de filas afectadas.
     */
    public function Delete($id){
        $sql = "DELETE FROM cuenta_asociada WHERE cuentaasociada_id = ?";
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($id);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Insertar una nueva cuenta asociada.
     *
     * @param CuentaAsociada $cuentaAsociada Objeto CuentaAsociada a insertar.
     * @return int ID de la cuenta asociada insertada.
     */
    public function Insert($cuentaAsociada){
        $sql = "INSERT INTO cuenta_asociada (usuario_id,usuario_id2,usucrea_id,usumodif_id) VALUES (?,?,?,?)";
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($cuentaAsociada->usuarioId);
        $sqlQuery->set($cuentaAsociada->usuarioId2);
        $sqlQuery->set($cuentaAsociada->usucreaId);
        $sqlQuery->set($cuentaAsociada->usumodifId);

        $id = $this->executeInsert($sqlQuery);
        $cuentaAsociada->CuentaAsociadaId = $id;
        return $id;
    }

    /**
     * Actualizar una cuenta asociada existente.
     *
     * @param CuentaAsociada $cuentaAsociada Objeto CuentaAsociada a actualizar.
     * @return int N��mero de filas afectadas.
     */
    public function Update($cuentaAsociada){
        $sql = "UPDATE cuenta_asociada SET usuarioId = ?, usuarioId2 = ?, usucrea_id = ?, usumodif_id = ?";
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($cuentaAsociada->usuarioId);
        $sqlQuery->set($cuentaAsociada->usuarioId2);
        $sqlQuery->set($cuentaAsociada->usucreaId);
        $sqlQuery->set($cuentaAsociada->usumodifId);
        return $this->executeUpdate($sqlQuery);
    }

    /** 
     * realizar una consulta a la tabla descarga_version de manera personalizada
     * 
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
     * 
     */
     public function queryCuentaAsociadaCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping){

        
        $where = "where 1= 1";

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
                if ($fieldOperation != "") {
                    $whereArray[] = $fieldName . $fieldOperation;
                }

                if (count($whereArray) > 0) {
                    $where = $where . " " . $groupOperation . " UCASE(CAST(" . $fieldName . " AS CHAR)) " . strtoupper($fieldOperation);
                } else {
                    $where = "";
                }
            }

        }

        $sql = 'SELECT COUNT(*) FROM cuenta_asociada'.' '. $where;

        $sqlQuery = new SqlQuery($sql);
        
        $count = $this->execute2($sqlQuery);

        $sql = "SELECT " . $select . " FROM cuenta_asociada INNER JOIN usuario ON (cuenta_asociada.usuario.id = usuario.usuario_id)".' '. $where . " " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);

        $result = $Helpers->process_data($this->execute2($sqlQuery));

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;

    }


    
    /**
     * Lee una fila de resultados de la base de datos y la convierte en un objeto CuentaAsociada.
     *
     * @param array $row La fila de resultados de la base de datos.
     * @return CuentaAsociada El objeto CuentaAsociada con los datos de la fila.
     */
    protected function readRow($row){
        $CuentasAsociadas = new CuentaAsociada();
        $CuentasAsociadas->CuentaAsociadaId = $row['cuentaasociada_id'];
        $CuentasAsociadas->usuarioId = $row['usuario_id'];
        $CuentasAsociadas->usuarioId2 = $row["usuario_id2"];
        $CuentasAsociadas->fechaCrea = $row["fecha_crea"];
        $CuentasAsociadas->fechaModif = $row["fecha_modif"];
        $CuentasAsociadas->usucreaId = $row["usucrea_id"];
        $CuentasAsociadas->usumodifId = $row["usumodif_id"];
        return $CuentasAsociadas;

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
     * Obtiene una lista de resultados a partir de una consulta SQL.
     *
     * @param SqlQuery $sqlQuery La consulta SQL a ejecutar.
     * @return array Un arreglo de objetos resultantes de la consulta.
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