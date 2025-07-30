<?php namespace Backend\mysql;



use Backend\dao\UsuarioHistorialDAO;
use Backend\dto\Helpers;
use Backend\dto\UsuarioHistorial;
use Backend\sql\QueryExecutor;
use Backend\sql\SqlQuery;
use Backend\sql\Transaction;
use Backend\sql\ConnectionProperty;
use PDO;


/**
 * Class that operate on table 'usuarioHistorial'. Database Mysql.
 *
 * @author: Daniel Tamayo
 * @date: 2017-09-06 18:55
 * @package No
 * @category No
 * @version    1.0
 */
class UsuarioHistorialMySqlDAO implements UsuarioHistorialDAO
{

        /**
         * Objeto contiene la relación entre la conexión a la base de datos y UsuarioHistorialMySqlDAO
         * @var Transaction
         */
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
     * Get Domain object by primry key
     *
     * @param String $id primary key
     * @return UsuarioHistorialMySql
     */
    public function load($id)
    {
        $sql = 'SELECT * FROM usuario_historial WHERE usuhistorial_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($id);
        return $this->getRow($sqlQuery);
    }

    /**
     * Get all records from table
     */
    public function queryAll()
    {
        $sql = 'SELECT * FROM usuario_historial';
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }

    /**
     * Get all records from table ordered by field
     *
     * @param $orderColumn column name
     */
    public function queryAllOrderBy($orderColumn)
    {
        $sql = 'SELECT * FROM usuario_historial ORDER BY ' . $orderColumn;
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }

    /**
     * Delete record from table
     * @param usuarioHistorial primary key
     */
    public function delete($usuhistorial_id)
    {
        $sql = 'DELETE FROM usuario_historial WHERE usuhistorial_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($usuhistorial_id);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Insert record to table
     *
     * @param UsuarioHistorialMySql usuarioHistorial
     */
    public function insert($usuarioHistorial, $isPV = '0')
    {
        if ($isPV == '1') {

            $sqlprev = '
            
            SELECT 
            punto_venta.cupo_recarga,punto_venta.creditos_base FROM punto_venta WHERE usuario_id =' . $usuarioHistorial->usuarioId;

            $sqlQuery2 = new SqlQuery($sqlprev);
            $sqlprevC = $this->execute2($sqlQuery2);
            $sqlprevC=json_decode(json_encode($sqlprevC), FALSE);
            $cupo_recarga=(($sqlprevC[0])->{'punto_venta.cupo_recarga'}) == null ? '0':$sqlprevC[0]->{'punto_venta.cupo_recarga'};
            $creditos_base=($sqlprevC[0])->{'punto_venta.creditos_base'} == null ? '0': ($sqlprevC[0])->{'punto_venta.creditos_base'};

            $sql = "INSERT INTO usuario_historial (usuario_id, descripcion, movimiento,usucrea_id,usumodif_id, tipo, valor, externo_id,creditos,creditos_base,customs) VALUES (?, ?, ?, ?, ?, ?, ?, ?,{$cupo_recarga},{$creditos_base},?)";

        } else {

            $sqlprev = '
            
            SELECT 
            registro.creditos,registro.creditos_base FROM registro WHERE usuario_id =' . $usuarioHistorial->usuarioId;

            $sqlQuery2 = new SqlQuery($sqlprev);
            $sqlprevC = $this->execute2($sqlQuery2);
            $sqlprevC=json_decode(json_encode($sqlprevC), FALSE);

            $creditos_base=($sqlprevC[0])->{'registro.creditos_base'} == null ? '0':($sqlprevC[0])->{'registro.creditos_base'};
            $creditos=($sqlprevC[0])->{'registro.creditos'} == null ? '0':($sqlprevC[0])->{'registro.creditos'};

            $sql = "INSERT INTO usuario_historial (usuario_id, descripcion, movimiento,usucrea_id,usumodif_id, tipo, valor, externo_id,creditos,creditos_base,customs) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?,{$creditos},{$creditos_base},? )";

        }
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->set($usuarioHistorial->usuarioId);
        $sqlQuery->set($usuarioHistorial->descripcion);
        $sqlQuery->set($usuarioHistorial->movimiento);
        $sqlQuery->set($usuarioHistorial->usucreaId);
        $sqlQuery->set($usuarioHistorial->usumodifId);
        $sqlQuery->set($usuarioHistorial->tipo);
        $sqlQuery->set($usuarioHistorial->valor);
        $sqlQuery->set($usuarioHistorial->externoId);
        $sqlQuery->set($usuarioHistorial->customs);


        $id = $this->executeInsert($sqlQuery);
        $usuarioHistorial->usuHistorialId = $id;
        return $id;
    }

    /**
     * Update record in table
     *
     * @param UsuarioHistorialMySql usuarioHistorial
     */
    public function update($usuarioHistorial)
    {
        $sql = 'UPDATE usuario_historial SET usuario_id = ?, descripcion = ?, movimiento = ?,usucrea_id = ?,usumodif_id = ?, tipo = ?, valor = ?, externo_id = ? WHERE usuhistorial_id = ?';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->set($usuarioHistorial->usuarioId);
        $sqlQuery->set($usuarioHistorial->descripcion);
        $sqlQuery->set($usuarioHistorial->movimiento);
        $sqlQuery->set($usuarioHistorial->usucreaId);
        $sqlQuery->set($usuarioHistorial->usumodifId);
        $sqlQuery->set($usuarioHistorial->tipo);
        $sqlQuery->set($usuarioHistorial->valor);
        $sqlQuery->set($usuarioHistorial->externoId);

        $sqlQuery->set($usuarioHistorial->usuHistorialId);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Executes a custom query to retrieve user history records with various filtering, sorting, and pagination options.
     *
     * @param string $select The columns to select in the query.
     * @param string $sidx The column to sort by.
     * @param string $sord The sort order (ASC or DESC).
     * @param int $start The starting index for pagination.
     * @param int $limit The number of records to return for pagination.
     * @param string $filters JSON-encoded string containing the filters to apply.
     * @param bool $searchOn Flag indicating whether to apply filters.
     * @param bool $withTimestamp Flag indicating whether to join with the timestamp table.
     * @param string $fechaInicio The start date for filtering records.
     * @param string $fechaFinal The end date for filtering records.
     *
     * @return string JSON-encoded string containing the count of records and the data.
     */
    public function queryUsuarioHistorialesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $withTimestamp = false, $fechaInicio = "", $fechaFinal = "")
    {

        $innConcesionario = false;

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

                $cond = "concesionario";
                if (strpos($fieldName, $cond) !== false || strpos($sidx, $cond) !== false || strpos($grouping, $cond) !== false || strpos($select, $cond) !== false) {
                    $innConcesionario = true;
                }
                

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
                    $where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
                } else {
                    $where = "";
                }
            }

        }

        $innerConcesionario = "";
        if ($innConcesionario) {
            $innerConcesionario = " INNER JOIN concesionario on (usuario.usuario_id=concesionario.usuhijo_id and concesionario.prodinterno_id=0  and concesionario.estado='A') ";
        }

        $innerTimestamp = "";

        if ($withTimestamp) {
            $innerTimestamp = ' inner join time_dimension force index (time_dimension_timestampint_dbtimestamp_index)
                    on (time_dimension.dbtimestamp = usuario_historial.fecha_crea) ';
        }

        $nameTable = "usuario_historial";

        if (date("m_Y", strtotime($fechaInicio)) == date("m_Y", strtotime($fechaFinal)) && $fechaInicio != '') {
            if (date("m_Y", strtotime($fechaInicio)) != date("m_Y", time())) {
                $nameTable = "usuario_historial_" . date("m_Y", strtotime($fechaInicio));

            }


        }
        if ($fechaInicio >= '2021-11-01' && $fechaInicio != '') {
            $nameTable = "usuario_historial";
        }
        if ($fechaInicio <= '2021-07-31' && $fechaInicio != '') {
            $nameTable = "usuario_historial_" . date("m_Y", strtotime('2021-07-31'));
        }


        if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ){
            $connOriginal = $_ENV["connectionGlobal"]->getConnection();




            $connDB5 = null;


            if($_ENV['ENV_TYPE'] =='prod') {

                $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()
                    , array(
                        PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-bundle.crt',
                        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
                    )
                );
            }else{

                $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()

                );
            }

            $connDB5->exec("set names utf8");
            $connDB5->exec("set use_secondary_engine=off");

            try{

                if($_ENV["TIMEZONE"] !=null && $_ENV["TIMEZONE"] !='') {
                    $connDB5->exec('SET time_zone = "'.$_ENV["TIMEZONE"].'";');
                }

                if($_ENV["NEEDINSOLATIONLEVEL"] != null && $_ENV["NEEDINSOLATIONLEVEL"] == '1') {
                    $connDB5->exec('SET TRANSACTION ISOLATION LEVEL READ COMMITTED;');
                }
                if($_ENV["ENABLEDSETLOCKWAITTIMEOUT"] != null && $_ENV["ENABLEDSETLOCKWAITTIMEOUT"] == '1') {
                    // $connDB5->exec('SET SESSION innodb_lock_wait_timeout = 5;');
                }
                if($_ENV["ENABLEDSETMAX_EXECUTION_TIME"] != null && $_ENV["ENABLEDSETMAX_EXECUTION_TIME"] == '1') {
                    // $connDB5->exec('SET SESSION MAX_EXECUTION_TIME = 120000;');
                }
                if($_ENV["DBNEEDUTF8"] != null && $_ENV["DBNEEDUTF8"] == '1') {
                    $connDB5->exec("SET NAMES utf8mb4");
                }
            }catch (\Exception $e){

            }
            $_ENV["connectionGlobal"]->setConnection($connDB5);
            $this->transaction->setConnection($_ENV["connectionGlobal"]);

        }

        $sql = 'SELECT  /*+ MAX_EXECUTION_TIME(120000) */ count(*) count FROM ' . $nameTable . ' as usuario_historial

         INNER JOIN usuario ON (usuario_historial.usuario_id = usuario.usuario_id) ' . $innerConcesionario . $innerTimestamp . $where;


        $sqlQuery = new SqlQuery($sql);
        if ($_ENV["debugFixed2"] == '1') {
            print_r($sql);
        } else {
            $count = $this->execute2($sqlQuery);

        }


        $sql = 'SELECT  /*+ MAX_EXECUTION_TIME(120000) */ ' . $select . '  FROM ' . $nameTable . ' as usuario_historial 
        
        INNER JOIN usuario ON (usuario_historial.usuario_id = usuario.usuario_id)  ' . $innerConcesionario . $innerTimestamp . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);
        if ($_ENV["debugFixed2"] == '1') {
            print_r($sql);
            exit();
        }

        $result = $Helpers->process_data($this->execute2($sqlQuery));

        if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ) {
            $connDB5 = null;
            $_ENV["connectionGlobal"]->setConnection($connOriginal);
            $this->transaction->setConnection($_ENV["connectionGlobal"]);
        }

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }


    /**
     * Delete all rows
     */
    public function clean()
    {
        $sql = 'DELETE FROM usuario_historial';
        $sqlQuery = new SqlQuery($sql);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Retrieve records from the 'usuario_historial' table by 'usuarioId'.
     *
     * @param mixed $value The value of 'usuarioId' to filter by.
     * @return array The list of matching records.
     */
    public function queryByTipo($value)
    {
        $sql = 'SELECT * FROM usuario_historial WHERE usuarioId = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Retrieve records from the 'usuario_historial' table by 'descripcion'.
     *
     * @param mixed $value The value of 'descripcion' to filter by.
     * @return array The list of matching records.
     */
    public function queryByDescripcion($value)
    {
        $sql = 'SELECT * FROM usuario_historial WHERE descripcion = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Retrieve records from the 'usuario_historial' table by 'movimiento'.
     *
     * @param mixed $value The value of 'movimiento' to filter by.
     * @return array The list of matching records.
     */
    public function queryByEstado($value)
    {
        $sql = 'SELECT * FROM usuario_historial WHERE movimiento = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Retrieve records from the 'usuario_historial' table by 'mandante'.
     *
     * @param mixed $value The value of 'mandante' to filter by.
     * @return array The list of matching records.
     */
    public function queryByMandante($value)
    {
        $sql = 'SELECT * FROM usuario_historial WHERE mandante = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Retrieve records from the 'usuario_historial' table by 'codigo'.
     *
     * @param mixed $value The value of 'codigo' to filter by.
     * @return array The list of matching records.
     */
    public function queryByAbreviado($value)
    {
        $sql = 'SELECT * FROM usuario_historial WHERE codigo = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Delete records from the 'usuario_historial' table by 'usuarioId'.
     *
     * @param mixed $value The value of 'usuarioId' to filter by.
     * @return int The number of affected rows.
     */
    public function deleteByTipo($value)
    {
        $sql = 'DELETE FROM usuario_historial WHERE usuarioId = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Delete records from the 'usuario_historial' table by 'descripcion'.
     *
     * @param mixed $value The value of 'descripcion' to filter by.
     * @return int The number of affected rows.
     */
    public function deleteByDescripcion($value)
    {
        $sql = 'DELETE FROM usuario_historial WHERE descripcion = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Delete records from the 'usuario_historial' table by 'movimiento'.
     *
     * @param mixed $value The value of 'movimiento' to filter by.
     * @return int The number of affected rows.
     */
    public function deleteByEstado($value)
    {
        $sql = 'DELETE FROM usuario_historial WHERE movimiento = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Delete records from the 'usuario_historial' table by 'mandante'.
     *
     * @param mixed $value The value of 'mandante' to filter by.
     * @return int The number of affected rows.
     */
    public function deleteByMandante($value)
    {
        $sql = 'DELETE FROM usuario_historial WHERE mandante = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Read row
     *
     * @return UsuarioHistorialMySql
     */
    protected function readRow($row)
    {
        $usuarioHistorial = new UsuarioHistorial();

        $usuarioHistorial->usuHistorialId = $row['usuhistorial_id'];
        $usuarioHistorial->usuarioId = $row['usuario_id'];
        $usuarioHistorial->descripcion = $row['descripcion'];
        $usuarioHistorial->movimiento = $row['movimiento'];
        $usuarioHistorial->usucreaId = $row['usucrea_id'];
        $usuarioHistorial->usumodifId = $row['usumodif_id'];

        $usuarioHistorial->tipo = $row['tipo'];
        $usuarioHistorial->valor = $row['valor'];
        $usuarioHistorial->externoId = $row['externo_id'];

        return $usuarioHistorial;
    }

    /**
     * Retrieves a list of rows from the database based on the provided SQL query.
     *
     * @param SqlQuery $sqlQuery The SQL query to be executed.
     * @return array An array of rows retrieved from the database, where each row is represented as an associative array.
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
     * Get row
     *
     * @return UsuarioHistorialMySql
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
     * Execute sql query
     */
    protected function execute($sqlQuery)
    {
        return QueryExecutor::execute($this->transaction, $sqlQuery);
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
    protected function executeUpdate($sqlQuery)
    {
        return QueryExecutor::executeUpdate($this->transaction, $sqlQuery);
    }

    /**
     * Query for one row and one column
     */
    protected function querySingleResult($sqlQuery)
    {
        return QueryExecutor::queryForString($this->transaction, $sqlQuery);
    }

    /**
     * Insert row to table
     */
    protected function executeInsert($sqlQuery)
    {
        return QueryExecutor::executeInsert($this->transaction, $sqlQuery);
    }

/**
     * Executes a given SQL query within a specified transaction context.
     *
     * @param Transaction $transaccion The transaction context to use.
     * @param string $sql The SQL query to execute.
     * @return mixed The result of the executed query.
     */
    public function execQuery($transaccion, $sql)
    {
        $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($transaccion);
        $return = $UsuarioHistorialMySqlDAO->querySQL($sql);

        //$return = json_decode(json_encode($return), FALSE);

        return $return;
    }

    /**
     * Executes a given SQL query.
     *
     * @param string $sql The SQL query to execute.
     * @return mixed The result of the executed query.
     */
    public function querySQL($sql)
    {
        $sqlQuery = new SqlQuery($sql);
        return $this->execute2($sqlQuery);
    }

}

?>
