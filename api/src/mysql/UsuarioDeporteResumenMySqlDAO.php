<?php namespace Backend\mysql;

use Backend\dao\UsuarioDeporteResumenDAO;
use Backend\dto\Helpers;
use Backend\dto\UsuarioDeporteResumen;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Exception;

/**
 * Class that operate on table 'usuario_deporte_resumen'. Database Mysql.
 *
 * @author: Daniel Tamayo
 * @date: 2017-09-06 18:57
 * @package No
 * @category No
 * @version    1.0
 */
class UsuarioDeporteResumenMySqlDAO implements UsuarioDeporteResumenDAO
{


    /**
    * Objeto contiene la relación entre la conexión a la base de datos y UsuarioDeporteResumenMySqlDAO
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
     * TransaccionUsuarioDeporteResumenMySqlDAO constructor.
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
     * @return UsuarioDeporteResumenMySql
     */
    public function load($id)
    {
        $sql = 'SELECT * FROM usuario_deporte_resumen WHERE usudepresume_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($id);
        return $this->getRow($sqlQuery);
    }

    /**
     * Get all records from table
     */
    public function queryAll()
    {
        $sql = 'SELECT * FROM usuario_deporte_resumen';
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
        $sql = 'SELECT * FROM usuario_deporte_resumen ORDER BY ' . $orderColumn;
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }

    /**
     * Delete record from table
     * @param usuario_deporte_resumen primary key
     */
    public function delete($usudepresume_id)
    {
        $sql = 'DELETE FROM usuario_deporte_resumen WHERE usudepresume_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($usudepresume_id);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Insert record to table
     *
     * @param UsuarioDeporteResumenMySql usuario_deporte_resumen
     */
    public function insert($usuario_deporte_resumen)
    {
        $sql = 'INSERT INTO usuario_deporte_resumen (usuario_id, tipo, estado, valor,cantidad, usucrea_id, usumodif_id) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?)';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($usuario_deporte_resumen->usuarioId);
        $sqlQuery->set($usuario_deporte_resumen->tipo);
        $sqlQuery->set($usuario_deporte_resumen->estado);
        $sqlQuery->set($usuario_deporte_resumen->valor);
        $sqlQuery->set($usuario_deporte_resumen->cantidad);
        $sqlQuery->setNumber($usuario_deporte_resumen->usucreaId);
        $sqlQuery->setNumber($usuario_deporte_resumen->usumodifId);

        $id = $this->executeInsert($sqlQuery);
        $usuario_deporte_resumen->usudepresumeId = $id;
        return $id;
    }

    /**
     * Update record in table
     *
     * @param UsuarioDeporteResumenMySql usuario_deporte_resumen
     */
    public function update($usuario_deporte_resumen)
    {
        $sql = 'UPDATE usuario_deporte_resumen SET usuario_id = ?, tipo = ?, estado = ?, valor = ?, cantidad = ?, usucrea_id = ?, usumodif_id = ? WHERE usudepresume_id = ?';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($usuario_deporte_resumen->usuarioId);
        $sqlQuery->set($usuario_deporte_resumen->tipo);
        $sqlQuery->set($usuario_deporte_resumen->estado);
        $sqlQuery->set($usuario_deporte_resumen->valor);
        $sqlQuery->set($usuario_deporte_resumen->cantidad);
        $sqlQuery->setNumber($usuario_deporte_resumen->usucreaId);
        $sqlQuery->setNumber($usuario_deporte_resumen->usumodifId);

        $sqlQuery->setNumber($usuario_deporte_resumen->usudepresumeId);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Delete all rows
     */
    public function clean()
    {
        $sql = 'DELETE FROM usuario_deporte_resumen';
        $sqlQuery = new SqlQuery($sql);
        return $this->executeUpdate($sqlQuery);
    }


/**
     * Query records by 'fecha_crea' field.
     *
     * @param mixed $value The value to filter by.
     * @return array The list of matching records.
     */
    public function queryByFechaCrea($value)
    {
        $sql = 'SELECT * FROM usuario_deporte_resumen WHERE fecha_crea = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Query records by 'usucrea_id' field.
     *
     * @param mixed $value The value to filter by.
     * @return array The list of matching records.
     */
    public function queryByUsucreaId($value)
    {
        $sql = 'SELECT * FROM usuario_deporte_resumen WHERE usucrea_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Query records by 'usumodif_id' field.
     *
     * @param mixed $value The value to filter by.
     * @return array The list of matching records.
     */
    public function queryByUsumodifId($value)
    {
        $sql = 'SELECT * FROM usuario_deporte_resumen WHERE usumodif_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Delete records by 'tipo' field.
     *
     * @param mixed $value The value to filter by.
     * @return int The number of affected rows.
     */
    public function deleteByExternoId($value)
    {
        $sql = 'DELETE FROM usuario_deporte_resumen WHERE tipo = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Delete records by 'valor' field.
     *
     * @param mixed $value The value to filter by.
     * @return int The number of affected rows.
     */
    public function deleteByEstado($value)
    {
        $sql = 'DELETE FROM usuario_deporte_resumen WHERE valor = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Delete records by 'fecha_crea' field.
     *
     * @param mixed $value The value to filter by.
     * @return int The number of affected rows.
     */
    public function deleteByFechaCrea($value)
    {
        $sql = 'DELETE FROM usuario_deporte_resumen WHERE fecha_crea = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Delete records by 'usucrea_id' field.
     *
     * @param mixed $value The value to filter by.
     * @return int The number of affected rows.
     */
    public function deleteByUsucreaId($value)
    {
        $sql = 'DELETE FROM usuario_deporte_resumen WHERE usucrea_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Delete records by 'usumodif_id' field.
     *
     * @param mixed $value The value to filter by.
     * @return int The number of affected rows.
     */
    public function deleteByUsumodifId($value)
    {
        $sql = 'DELETE FROM usuario_deporte_resumen WHERE usumodif_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->executeUpdate($sqlQuery);
    }


    /**
     * Read row
     *
     * @return UsuarioDeporteResumenMySql
     */
    protected function readRow($row)
    {
        $usuario_deporte_resumen = new UsuarioDeporteResumen();

        $usuario_deporte_resumen->usudepresumeId = $row['usudepresume_id'];
        $usuario_deporte_resumen->usuarioId = $row['usuario_id'];
        $usuario_deporte_resumen->tipo = $row['tipo'];
        $usuario_deporte_resumen->estado = $row['estado'];
        $usuario_deporte_resumen->valor = $row['valor'];
        $usuario_deporte_resumen->cantidad = $row['cantidad'];
        $usuario_deporte_resumen->usucreaId = $row['usucrea_id'];
        $usuario_deporte_resumen->usumodifId = $row['usumodif_id'];

        return $usuario_deporte_resumen;
    }

    /**
     * Executes a custom query to retrieve user sports summary data with various filtering, sorting, and grouping options.
     *
     * @param string $select The columns to select in the main query.
     * @param string $sidx The column to sort by.
     * @param string $sord The sort order (ASC or DESC).
     * @param int $start The starting index for the result set.
     * @param int $limit The maximum number of records to return.
     * @param string $filters JSON encoded string containing the filters to apply.
     * @param bool $searchOn Flag indicating whether to apply the filters.
     * @param string $grouping The column(s) to group by in the main query.
     * @param string $select2 Optional. The columns to select in the subquery if additional grouping is applied.
     * @param string $grouping2 Optional. The column(s) to group by in the subquery if additional grouping is applied.
     *
     * @return string JSON encoded string containing the count of records and the result set data.
     */
    public function queryUsuarioDeporteResumensCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$grouping,$select2="",$grouping2="")
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

        if($grouping != ""){
            $where = $where . " GROUP BY " . $grouping;
        }
        $sql = 'SELECT count(*) count FROM usuario_deporte_resumen INNER JOIN usuario ON (usuario.usuario_id = usuario_deporte_resumen.usuario_id)  INNER JOIN usuario_perfil ON (usuario.usuario_id = usuario_perfil.usuario_id)  LEFT OUTER JOIN usuario usuario_punto ON (usuario_punto.usuario_id = usuario.puntoventa_id) LEFT OUTER JOIN usuario usuario_punto_pago ON (usuario_punto_pago.usuario_id = usuario_deporte_resumen.usuario_id)   LEFT OUTER JOIN usuario_mandante ON (usuario_mandante.usuario_mandante = usuario.usuario_id)  LEFT OUTER JOIN concesionario ON (usuario.puntoventa_id = concesionario.usuhijo_id  and prodinterno_id =0 and concesionario.estado="A") LEFT OUTER JOIN pais ON (usuario.pais_id = pais.pais_id)  ' . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT ' .$select .'  FROM usuario_deporte_resumen  INNER JOIN usuario ON (usuario.usuario_id = usuario_deporte_resumen.usuario_id)  INNER JOIN usuario_perfil ON (usuario.usuario_id = usuario_perfil.usuario_id)  LEFT OUTER JOIN usuario usuario_punto ON (usuario_punto.usuario_id = usuario.puntoventa_id) LEFT OUTER JOIN usuario usuario_punto_pago ON (usuario_punto_pago.usuario_id = usuario_deporte_resumen.usuario_id)   LEFT OUTER JOIN usuario_mandante ON (usuario_mandante.usuario_mandante = usuario.usuario_id)  LEFT OUTER JOIN concesionario ON (usuario.puntoventa_id = concesionario.usuhijo_id  and prodinterno_id =0 and concesionario.estado="A") LEFT OUTER JOIN pais ON (usuario.pais_id = pais.pais_id)    ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        if($select2 != ""){

            $sql = 'SELECT ' .$select2 . ' FROM ('.$sql.') a  GROUP BY ' . $grouping2;
        }

        if($_REQUEST["isDebug"]=="1"){

print_r($sql);
        }



        $sqlQuery = new SqlQuery($sql);

        $result = $Helpers->process_data($this->execute2($sqlQuery));

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }

    /**
     * Retrieves a list of results based on the provided SQL query.
     *
     * @param SqlQuery $sqlQuery The SQL query to be executed.
     * @return array An array of results, where each result is processed by the readRow method.
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
     * @return UsuarioDeporteResumenMySql
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
