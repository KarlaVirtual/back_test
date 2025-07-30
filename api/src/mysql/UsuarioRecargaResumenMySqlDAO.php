<?php

namespace Backend\mysql;

use Backend\dao\UsuarioRecargaResumenDAO;
use Backend\dto\Helpers;
use Backend\dto\UsuarioRecargaResumen;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Exception;

/**
 * Class that operate on table 'usuario_recarga_resumen'. Database Mysql.
 *
 * @author: Daniel Tamayo
 * @package No
 * @category No
 * @version    1.0
 * @since 2017-09-06 18:57
 */
class UsuarioRecargaResumenMySqlDAO implements UsuarioRecargaResumenDAO
{

    /**
     * Objeto contiene la relación entre la conexión a la base de datos y UsuarioRestriccionMySqlDAO
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
     * TransaccionUsuarioRecargaResumenMySqlDAO constructor.
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
     * @return UsuarioRecargaResumenMySql
     */
    public function load($id)
    {
        $sql = 'SELECT * FROM usuario_recarga_resumen WHERE usurecresume_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($id);
        return $this->getRow($sqlQuery);
    }

    /**
     * Get all records from table
     */
    public function queryAll()
    {
        $sql = 'SELECT * FROM usuario_recarga_resumen';
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
        $sql = 'SELECT * FROM usuario_recarga_resumen ORDER BY ' . $orderColumn;
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }

    /**
     * Delete record from table
     * @param usuario_recarga_resumen primary key
     */
    public function delete($usurecresume_id)
    {
        $sql = 'DELETE FROM usuario_recarga_resumen WHERE usurecresume_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($usurecresume_id);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Insert record to table
     *
     * @param UsuarioRecargaResumenMySql usuario_recarga_resumen
     */
    public function insert($usuario_recarga_resumen)
    {
        $sql = 'INSERT INTO usuario_recarga_resumen (usuario_id, mediopago_id, estado, valor,cantidad, usucrea_id, usumodif_id) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?)';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($usuario_recarga_resumen->usuarioId);
        $sqlQuery->set($usuario_recarga_resumen->mediopagoId);
        $sqlQuery->set($usuario_recarga_resumen->estado);
        $sqlQuery->set($usuario_recarga_resumen->valor);
        $sqlQuery->set($usuario_recarga_resumen->cantidad);
        $sqlQuery->setNumber($usuario_recarga_resumen->usucreaId);
        $sqlQuery->setNumber($usuario_recarga_resumen->usumodifId);

        $id = $this->executeInsert($sqlQuery);
        $usuario_recarga_resumen->usurecresumeId = $id;
        return $id;
    }

    /**
     * Update record in table
     *
     * @param UsuarioRecargaResumenMySql usuario_recarga_resumen
     */
    public function update($usuario_recarga_resumen)
    {
        $sql = 'UPDATE usuario_recarga_resumen SET usuario_id = ?, mediopago_id = ?, estado = ?, valor = ?, cantidad = ?, usucrea_id = ?, usumodif_id = ? WHERE usurecresume_id = ?';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($usuario_recarga_resumen->usuarioId);
        $sqlQuery->set($usuario_recarga_resumen->mediopagoId);
        $sqlQuery->set($usuario_recarga_resumen->estado);
        $sqlQuery->set($usuario_recarga_resumen->valor);
        $sqlQuery->set($usuario_recarga_resumen->cantidad);
        $sqlQuery->setNumber($usuario_recarga_resumen->usucreaId);
        $sqlQuery->setNumber($usuario_recarga_resumen->usumodifId);

        $sqlQuery->setNumber($usuario_recarga_resumen->usurecresumeId);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Delete all rows
     */
    public function clean()
    {
        $sql = 'DELETE FROM usuario_recarga_resumen';
        $sqlQuery = new SqlQuery($sql);
        return $this->executeUpdate($sqlQuery);
    }


    /**
     * Query records by 'fecha_crea' field.
     *
     * @param string $value The value of 'fecha_crea' to filter by.
     * @return array The list of matching records.
     */
    public function queryByFechaCrea($value)
    {
        $sql = 'SELECT * FROM usuario_recarga_resumen WHERE fecha_crea = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Query records by 'usucrea_id' field.
     *
     * @param int $value The value of 'usucrea_id' to filter by.
     * @return array The list of matching records.
     */
    public function queryByUsucreaId($value)
    {
        $sql = 'SELECT * FROM usuario_recarga_resumen WHERE usucrea_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Query records by 'usumodif_id' field.
     *
     * @param int $value The value of 'usumodif_id' to filter by.
     * @return array The list of matching records.
     */
    public function queryByUsumodifId($value)
    {
        $sql = 'SELECT * FROM usuario_recarga_resumen WHERE usumodif_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Delete records by 'mediopago_id' field.
     *
     * @param string $value The value of 'mediopago_id' to filter by.
     * @return int The number of affected rows.
     */
    public function deleteByExternoId($value)
    {
        $sql = 'DELETE FROM usuario_recarga_resumen WHERE mediopago_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Delete records by 'valor' field.
     *
     * @param string $value The value of 'valor' to filter by.
     * @return int The number of affected rows.
     */
    public function deleteByEstado($value)
    {
        $sql = 'DELETE FROM usuario_recarga_resumen WHERE valor = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Delete records by 'fecha_crea' field.
     *
     * @param string $value The value of 'fecha_crea' to filter by.
     * @return int The number of affected rows.
     */
    public function deleteByFechaCrea($value)
    {
        $sql = 'DELETE FROM usuario_recarga_resumen WHERE fecha_crea = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Delete records by 'usucrea_id' field.
     *
     * @param int $value The value of 'usucrea_id' to filter by.
     * @return int The number of affected rows.
     */
    public function deleteByUsucreaId($value)
    {
        $sql = 'DELETE FROM usuario_recarga_resumen WHERE usucrea_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Delete records by 'usumodif_id' field.
     *
     * @param int $value The value of 'usumodif_id' to filter by.
     * @return int The number of affected rows.
     */
    public function deleteByUsumodifId($value)
    {
        $sql = 'DELETE FROM usuario_recarga_resumen WHERE usumodif_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Read row
     *
     * @return UsuarioRecargaResumenMySql
     */
    protected function readRow($row)
    {
        $usuario_recarga_resumen = new UsuarioRecargaResumen();

        $usuario_recarga_resumen->usurecresumeId = $row['usurecresume_id'];
        $usuario_recarga_resumen->usuarioId = $row['usuario_id'];
        $usuario_recarga_resumen->mediopagoId = $row['mediopago_id'];
        $usuario_recarga_resumen->estado = $row['estado'];
        $usuario_recarga_resumen->valor = $row['valor'];
        $usuario_recarga_resumen->cantidad = $row['cantidad'];
        $usuario_recarga_resumen->usucreaId = $row['usucrea_id'];
        $usuario_recarga_resumen->usumodifId = $row['usumodif_id'];

        return $usuario_recarga_resumen;
    }

    /**
     * Executes a custom query to retrieve user recharge summaries with various filtering, sorting, and grouping options.
     *
     * @param string $select The columns to select in the query.
     * @param string $sidx The column by which to sort the results.
     * @param string $sord The sorting order (ASC or DESC).
     * @param int $start The starting index for the results.
     * @param int $limit The maximum number of results to return.
     * @param string $filters JSON-encoded string containing the filters to apply.
     * @param bool $searchOn Flag indicating whether to apply filters.
     * @param string $grouping The column(s) by which to group the results.
     * @param bool $innProducto Flag indicating whether to join with the 'producto' table.
     * @param bool $innConcesionario Flag indicating whether to join with the 'concesionario' table.
     * @return string JSON-encoded string containing the count of results and the data.
     */
    public function queryUsuarioRecargaResumensCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping, $innProducto = false, $innConcesionario = false)
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
                    $where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
                } else {
                    $where = "";
                }
            }
        }

        if ($grouping != "") {
            $where = $where . " GROUP BY " . $grouping;
        }

        $inner = ($innProducto) ? " INNER JOIN producto ON (producto.producto_id = usuario_recarga_resumen.mediopago_id) INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id) " : "";
        $inner .= ($innConcesionario) ? " INNER JOIN concesionario ON (concesionario.usuhijo_id = usuario.puntoventa_id) " : "";



        $table_name = ' usuario_recarga_resumen ';

        if ($_SESSION['usuario'] == '4089418' || $_SESSION['usuario'] == '4089493' || $_SESSION['usuario'] == '1578169' || $_SESSION['usuario'] == '6997123') {
            $table_name = ' usuario_recarga_resumen_rf usuario_recarga_resumen ';
        }

        $sql = 'SELECT count(*) count FROM ' . $table_name . ' INNER JOIN usuario ON (usuario_recarga_resumen.usuario_id = usuario.usuario_id)  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = usuario.usuario_id)  INNER JOIN pais ON (pais.pais_id = usuario.pais_id) ' . $inner . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT ' . $select . '  FROM ' . $table_name . ' INNER JOIN usuario ON (usuario_recarga_resumen.usuario_id = usuario.usuario_id)  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = usuario.usuario_id)  INNER JOIN pais ON (pais.pais_id = usuario.pais_id) ' . $inner . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;



        $sqlQuery = new SqlQuery($sql);

        $result = $Helpers->process_data($this->execute2($sqlQuery));

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }


    /**
     * Retrieves a list of results from an SQL query.
     *
     * @param SqlQuery $sqlQuery The SQL query to execute.
     * @return array An array of results obtained from the query.
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
     * @return UsuarioRecargaResumenMySql
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
}

?>
