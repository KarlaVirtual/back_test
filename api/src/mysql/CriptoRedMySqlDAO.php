<?php

namespace Backend\mysql;

use Backend\dto\CriptoRed;
use Backend\sql\QueryExecutor;
use Backend\sql\SqlQuery;
use Backend\sql\Transaction;

/**
 * Clase 'CriptoRedMySqlDAO'
 *
 * Esta clase provee las consultas del modelo o tabla 'cripto_red'
 *
 * Ejemplo de uso:
 * $CriptoRed = new CriptoRedMySqlDAO();
 *
 *
 * @package ninguno
 * @author  Juan David Taborda <juan.taborda@virtualsoft.tech>
 * @version ninguna
 * @access  public
 * @see no
 *
 */
class CriptoRedMySqlDAO
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
     * Constructor de la clase CriptoRedMySqlDAO.
     *
     * @param Transaction $transaction Objeto de transacción opcional.
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
     * Cargar una cuenta asociada por ID.
     *
     * @param int $id ID de la cuenta asociada.
     * @return CriptoRed Objeto CriptoRed.
     */

    public function load($id)
    {
        $sql = "SELECT * FROM cripto_red WHERE criptored_id = ?";
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($id);
        return $this->getRow($sqlQuery);
    }


    /**
     * Consultar por id de criptomonedas.
     *
     * @param int $value ID de la criptomoneda.
     * @return array      Lista de objetos CriptoMoneda.
     */


    public function queryByCriptoMonedaId($id)
    {
        $sql = "SELECT * FROM cripto_red WHERE criptomoneda_id = ?";
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($id);
        return $this->getRow($sqlQuery);
    }

    /**
     * Consultar por id de red blockchain.
     *
     * @param int $id ID de la red blockchain.
     * @return CriptoRed Objeto CriptoRed.
     */

    public function queryByRedBlockchainId($id)
    {
        $sql = "SELECT * FROM cripto_red WHERE redblockchain_id = ?";
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($id);
        return $this->getRow($sqlQuery);
    }


    /**
     * Consultar por id de criptomonedas y red blockchain.
     *
     * @param int $criptomonedaId ID de la criptomoneda.
     * @param int $redblockchainId ID de la red blockchain.
     * @return CriptoRed            Objeto CriptoRed.
     */

    public function queryByMonedaIdAndRedBlochainId($criptomonedaId, $redblockchainId)
    {
        $sql = "SELECT * FROM cripto_red where criptomoneda_id = ? AND redblockchain_id = ?";
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($this->criptomonedaId);
        $sqlQuery->set($this->redblockchainId);
        return $this->getRow($sqlQuery);
    }


    public function queryByMonedaIdAndRedBlochainIdAndEstado($criptomonedaId, $redBlochain, $estado)
    {
        $sql = "SELECT * FROM cripto_red where criptomoneda_id = ? AND redblockchain_id = ? AND estado = ?";
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($criptomonedaId);
        $sqlQuery->set($redBlochain);
        $sqlQuery->set($estado);
        return $this->getRow($sqlQuery);
    }

    /**
     * Obtiene una cripto_red filtrando por criptomoneda_id, redblockchain_id y banco_id
     *
     * @param string $criptomonedaId id de la criptomoneda
     * @param string $redblockchainId id de la red blockchain
     * @param string $bancoId id del banco
     * @return array
     */
    public function queryByMonedaIdAndRedBlochainIdAndBancoId($criptomonedaId, $redblockchainId, $bancoId)
    {
        $sql = "SELECT * FROM cripto_red WHERE criptomoneda_id = ? AND redblockchain_id = ? AND banco_id = ?";
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($criptomonedaId);
        $sqlQuery->set($redblockchainId);
        $sqlQuery->set($bancoId);
        return $this->getRow($sqlQuery);
    }

    /**
     * Obtiene una cripto_red filtrando por banco_id
     *
     * @param string $bancoId id del banco
     * @return array
     */
    public function queryByBancoId($bancoId)
    {
        $sql = "SELECT * FROM cripto_red WHERE banco_id = ?";
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($bancoId);
        return $this->getRow($sqlQuery);
    }


    /**
     * Consultar por estado.
     *
     * @param string $estado Estado de la red.
     * @return CriptoRed Objeto CriptoRed.
     */

    public function queryByEstado($estado)
    {
        $sql = "SELECT * FROM cripto_red WHERE estado = ?";
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($estado);
        return $this->getRow($sqlQuery);
    }

    /**
     * Consultar todos los datos por orden .
     *
     * @param string $fecha Fecha de creación.
     * @return CriptoRed Objeto CriptoRed.
     */

    public function queryAllOrderBy($orderColumn)
    {
        $sql = 'SELECT * FROM cripto_red ORDER BY' . $orderColumn;
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }

    /**
     * Consultar todas las cripto monedas asociadas a red blockchain.
     *
     * @return array Lista de objetos criptoRed.
     */
    public function queryAll()
    {
        $sql = "SELECT * FROM cripto_red";
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }


    /**
     * Eliminar una criptoRed.
     *
     * @param int $id ID de la criptomoneda y la red asociada.
     * @return int Número de filas afectadas.
     */

    public function Delete($id)
    {
        $sql = "DELETE FROM cripto_red WHERE criptoRed = ?";
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($id);
        return $this->executeUpdate($sqlQuery);
    }


    /**
     * Insertar una nueva cuenta asociada.
     *
     * @param CriptoRed $CriptoRed Objeto CriptoRed a insertar.
     * @return int ID de la CriptoRed insertada.
     */

    public function Insert($CriptoRed)
    {
        $sql = "INSERT INTO cripto_red (criptomoneda_id, redblockchain_id, estado, usucrea_id,usumodif_id,banco_id) VALUES (?, ?, ?, ?, ?,?)";

        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($CriptoRed->criptomonedaId);
        $sqlQuery->set($CriptoRed->redblockchainId);
        $sqlQuery->set($CriptoRed->estado);
        $sqlQuery->set($CriptoRed->usucreaId);
        $sqlQuery->set($CriptoRed->usumodifId);
        $sqlQuery->set($CriptoRed->bancoId);

        $id = $this->executeInsert($sqlQuery); // Ejecutar la inserción
        return $id; // Retornar el ID de la nueva cripto red

    }


    /**
     * Actualizar una cuenta asociada existente.
     *
     * @param CriptoRed $CriptoRed Objeto CriptoRed a actualizar.
     * @return int  Numero de filas afectadas.
     */

    public function Update($CriptoRed)
    {
        $sql = "UPDATE cripto_red SET criptomoneda_id = ?, redblockchain_id = ?, estado = ?, usucrea_id = ?, usumodif_id = ?,banco_id = ? WHERE criptored_id = ?";
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($CriptoRed->criptomonedaId);
        $sqlQuery->set($CriptoRed->redblockchainId);
        $sqlQuery->set($CriptoRed->estado);
        $sqlQuery->set($CriptoRed->usucreaId);
        $sqlQuery->set($CriptoRed->usumodifId);
        $sqlQuery->set($CriptoRed->bancoId);
        $sqlQuery->set($CriptoRed->criptoredId);

        return $this->executeUpdate($sqlQuery); // Ejecutar la actualización

    }


    /**
     * realizar una consulta a la tabla cripto_red de manera personalizada
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
     * @param array $joins Joins a incluir en la consulta
     * @return Array resultado de la consulta
     *
     *
     */


    public function queryCriptoRedCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $joins)
    {

        if ($limit < 0) {
            $limit = 0;
        }

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

        /* Construyendo cadena de joins solicitados fuera de la petición JOINS DINÁMICOS */
        $strJoins = " ";
        if (!empty($joins)) {
            foreach ($joins as $join) {
                /**
                 *Ejemplo estructura $join
                 *{
                 *     "type": "INNER" | "LEFT" | "RIGHT",
                 *     "table": "usuario_puntoslealtad"
                 *     "on": "usuario.usuario_id = usuario_puntoslealtad.usuario_id"
                 *}
                 */
                $join = (object)$join;
                $allowedJoins = ["INNER", "LEFT", "RIGHT"];
                if (in_array($join->type, $allowedJoins)) {
                    //Estructurando cadena de joins
                    $strJoins .= " " . strtoupper($join->type) . " JOIN " . $join->table . " ON (" . $join->on . ") ";
                }
            }
        }

        $sql = "SELECT count(*) count FROM cripto_red $strJoins INNER JOIN criptomoneda ON (cripto_red.criptomoneda_id = criptomoneda.criptomoneda_id) INNER JOIN red_blockchain ON (cripto_red.redblockchain_id = red_blockchain.redblockchain_id)" . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = "SELECT " . $select . " FROM cripto_red $strJoins INNER JOIN criptomoneda ON (cripto_red.criptomoneda_id = criptomoneda.criptomoneda_id) INNER JOIN red_blockchain ON (cripto_red.redblockchain_id = red_blockchain.redblockchain_id)" . $where . " " . " " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;


        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }


    /**
     * Lee una fila de resultados de la base de datos y la convierte en un objeto CriptoRed.
     *
     * @param array $row La fila de resultados de la base de datos.
     * @return CriptoRed El objeto CriptoRed con los datos de la fila.
     */


    protected function readRow($row)
    {
        $CriptoRed = new CriptoRed();
        $CriptoRed->criptoredId = $row['criptored_id'];
        $CriptoRed->criptomonedaId = $row['criptomoneda_id'];
        $CriptoRed->redblockchainId = $row["redblockchain_id"];
        $CriptoRed->estado = $row["estado"];
        $CriptoRed->fechaCrea = $row["fecha_crea"];
        $CriptoRed->usucreaId = $row["usucrea_id"];
        $CriptoRed->fechaModif = $row["fecha_modif"];
        $CriptoRed->usumodifId = $row["usumodif_id"];
        $CriptoRed->bancoId = $row["banco_id"];
        return $CriptoRed;

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
        if (count($tab) == 0) {
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

    protected function getList($sqlQuery)
    {
        $tab = QueryExecutor::execute($this->transaction, $sqlQuery);
        $ret = array();
        for ($i = 0; $i < count($tab); $i++) {
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

    protected function execute($sqlQuery)
    {
        return QueryExecutor::execute($this->transaction, $sqlQuery);
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
    protected function executeUpdate($sqlQuery)
    {
        return QueryExecutor::executeUpdate($this->transaction, $sqlQuery);
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

}