<?php namespace Backend\mysql;

/**
 * Class that operate on table 'usuario_retiro_resumen'. Database Mysql.
 *
 * @author: DT
 * @date: 2017-09-06 18:57
 */

use Backend\dao\UsuarioRetiroResumenDAO;
use Backend\dto\Helpers;
use Backend\dto\UsuarioRetiroResumen;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Exception;

/** 
 * Class UsuarioRetiroResumenMySqlDAO
 * 
 * Clase encargada de proveer consultas respecto a la tabla 'usuario_retiro_resumen' de la base
 * de datos.
 * @author Desconocido
 * @package No
 * @category No
 * @version    1.0
 * @since Desconocida
 */
class UsuarioRetiroResumenMySqlDAO implements UsuarioRetiroResumenDAO
{
    /**
     * Objeto vinculado a la conexión entre UsuarioRetiroResumenMySqlDAO y la base de datos.
     * @var Transaction
     */
    private $transaction;

/**
     * Obtiene la transacción actual.
     *
     * @return Transaction La transacción actual.
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * Establece una nueva transacción.
     *
     * @param Transaction $transaction La nueva transacción a establecer.
     */
    public function setTransaction($transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * TransaccionUsuarioRetiroResumenMySqlDAO constructor.
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
     * @return UsuarioRetiroResumenMySql
     */
    public function load($id)
    {
        $sql = 'SELECT * FROM usuario_retiro_resumen WHERE usuretresume_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($id);
        return $this->getRow($sqlQuery);
    }

    /**
     * Get all records from table
     */
    public function queryAll()
    {
        $sql = 'SELECT * FROM usuario_retiro_resumen';
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
        $sql = 'SELECT * FROM usuario_retiro_resumen ORDER BY ' . $orderColumn;
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }

    /**
     * Delete record from table
     * @param usuario_retiro_resumen primary key
     */
    public function delete($usuretresume_id)
    {
        $sql = 'DELETE FROM usuario_retiro_resumen WHERE usuretresume_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($usuretresume_id);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Insert record to table
     *
     * @param UsuarioRetiroResumenMySql usuario_retiro_resumen
     */
    public function insert($usuario_retiro_resumen)
    {
        $sql = 'INSERT INTO usuario_retiro_resumen (usuario_id, producto_id, estado, valor,cantidad, usucrea_id, usumodif_id) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?)';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($usuario_retiro_resumen->usuarioId);
        $sqlQuery->set($usuario_retiro_resumen->productoId);
        $sqlQuery->set($usuario_retiro_resumen->estado);
        $sqlQuery->set($usuario_retiro_resumen->valor);
        $sqlQuery->set($usuario_retiro_resumen->cantidad);
        $sqlQuery->setNumber($usuario_retiro_resumen->usucreaId);
        $sqlQuery->setNumber($usuario_retiro_resumen->usumodifId);

        $id = $this->executeInsert($sqlQuery);
        $usuario_retiro_resumen->usuretresumeId = $id;
        return $id;
    }

    /**
     * Update record in table
     *
     * @param UsuarioRetiroResumenMySql usuario_retiro_resumen
     */
    public function update($usuario_retiro_resumen)
    {
        $sql = 'UPDATE usuario_retiro_resumen SET usuario_id = ?, producto_id = ?, estado = ?, valor = ?, cantidad = ?, usucrea_id = ?, usumodif_id = ? WHERE usuretresume_id = ?';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($usuario_retiro_resumen->usuarioId);
        $sqlQuery->set($usuario_retiro_resumen->productoId);
        $sqlQuery->set($usuario_retiro_resumen->estado);
        $sqlQuery->set($usuario_retiro_resumen->valor);
        $sqlQuery->set($usuario_retiro_resumen->cantidad);
        $sqlQuery->setNumber($usuario_retiro_resumen->usucreaId);
        $sqlQuery->setNumber($usuario_retiro_resumen->usumodifId);

        $sqlQuery->setNumber($usuario_retiro_resumen->usuretresumeId);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Delete all rows
     */
    public function clean()
    {
        $sql = 'DELETE FROM usuario_retiro_resumen';
        $sqlQuery = new SqlQuery($sql);
        return $this->executeUpdate($sqlQuery);
    }


/**
     * Consulta registros por la fecha de creación.
     *
     * @param string $value La fecha de creación a buscar.
     * @return array Lista de registros que coinciden con la fecha de creación.
     */
    public function queryByFechaCrea($value)
    {
        $sql = 'SELECT * FROM usuario_retiro_resumen WHERE fecha_crea = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Consulta registros por el ID del usuario creador.
     *
     * @param int $value El ID del usuario creador a buscar.
     * @return array Lista de registros que coinciden con el ID del usuario creador.
     */
    public function queryByUsucreaId($value)
    {
        $sql = 'SELECT * FROM usuario_retiro_resumen WHERE usucrea_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Consulta registros por el ID del usuario modificador.
     *
     * @param int $value El ID del usuario modificador a buscar.
     * @return array Lista de registros que coinciden con el ID del usuario modificador.
     */
    public function queryByUsumodifId($value)
    {
        $sql = 'SELECT * FROM usuario_retiro_resumen WHERE usumodif_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Elimina registros por el ID del producto.
     *
     * @param int $value El ID del producto a buscar.
     * @return int Número de registros eliminados.
     */
    public function deleteByExternoId($value)
    {
        $sql = 'DELETE FROM usuario_retiro_resumen WHERE producto_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Elimina registros por el valor.
     *
     * @param string $value El valor a buscar.
     * @return int Número de registros eliminados.
     */
    public function deleteByEstado($value)
    {
        $sql = 'DELETE FROM usuario_retiro_resumen WHERE valor = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Elimina registros por la fecha de creación.
     *
     * @param string $value La fecha de creación a buscar.
     * @return int Número de registros eliminados.
     */
    public function deleteByFechaCrea($value)
    {
        $sql = 'DELETE FROM usuario_retiro_resumen WHERE fecha_crea = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Elimina registros por el ID del usuario creador.
     *
     * @param int $value El ID del usuario creador a buscar.
     * @return int Número de registros eliminados.
     */
    public function deleteByUsucreaId($value)
    {
        $sql = 'DELETE FROM usuario_retiro_resumen WHERE usucrea_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Elimina registros por el ID del usuario modificador.
     *
     * @param int $value El ID del usuario modificador a buscar.
     * @return int Número de registros eliminados.
     */
    public function deleteByUsumodifId($value)
    {
        $sql = 'DELETE FROM usuario_retiro_resumen WHERE usumodif_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->executeUpdate($sqlQuery);
    }


    /**
     * Read row
     *
     * @return UsuarioRetiroResumenMySql
     */
    protected function readRow($row)
    {
        $usuario_retiro_resumen = new UsuarioRetiroResumen();

        $usuario_retiro_resumen->usuretresumeId = $row['usuretresume_id'];
        $usuario_retiro_resumen->usuarioId = $row['usuario_id'];
        $usuario_retiro_resumen->productoId = $row['producto_id'];
        $usuario_retiro_resumen->estado = $row['estado'];
        $usuario_retiro_resumen->valor = $row['valor'];
        $usuario_retiro_resumen->cantidad = $row['cantidad'];
        $usuario_retiro_resumen->usucreaId = $row['usucrea_id'];
        $usuario_retiro_resumen->usumodifId = $row['usumodif_id'];

        return $usuario_retiro_resumen;
    }

    /**
     * Realiza una consulta personalizada sobre los resúmenes de retiro de usuarios.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Campo por el cual se ordenará la consulta.
     * @param string $sord Orden de la consulta (ASC o DESC).
     * @param int $start Índice de inicio para la paginación.
     * @param int $limit Límite de registros para la paginación.
     * @param string $filters Filtros en formato JSON para la consulta.
     * @param bool $searchOn Indica si se deben aplicar los filtros.
     * @param string $grouping Campo por el cual se agruparán los resultados.
     * @param bool $innProducto Indica si se debe hacer un INNER JOIN con la tabla de productos.
     * @param bool $innConcesionario Indica si se debe hacer un INNER JOIN con la tabla de concesionarios.
     * @return string JSON con el conteo de registros y los datos resultantes de la consulta.
     */
    public function queryUsuarioRetiroResumensCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$grouping,$innProducto=false,$innConcesionario=false)
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

        if($grouping != ""){
            $where = $where . " GROUP BY " . $grouping;
        }

        $inner =($innProducto) ? " INNER JOIN producto ON (producto.producto_id = usuario_retiro_resumen.producto_id) INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id) ":"";
        $inner .=($innConcesionario) ? " INNER JOIN concesionario ON (concesionario.usuhijo_id = usuario.usuario_id) ":"";

        $sql = 'SELECT count(*) count FROM usuario_retiro_resumen INNER JOIN usuario ON (usuario_retiro_resumen.usuario_id = usuario.usuario_id)  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = usuario.usuario_id)  INNER JOIN pais ON (pais.pais_id = usuario.pais_id) ' . $inner . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT ' .$select .'  FROM usuario_retiro_resumen INNER JOIN usuario ON (usuario_retiro_resumen.usuario_id = usuario.usuario_id)  INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = usuario.usuario_id)  INNER JOIN pais ON (pais.pais_id = usuario.pais_id) '.$inner . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;



        $sqlQuery = new SqlQuery($sql);

        $result = $Helpers->process_data($this->execute2($sqlQuery));

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }

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
     * @return UsuarioRetiroResumenMySql
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
