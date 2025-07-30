<?php

namespace Backend\mysql;

use Backend\dao\JackpotDetalleDAO;
use Backend\dto\JackpotDetalle;
use Backend\sql\QueryExecutor;
use Backend\sql\SqlQuery;
use Backend\sql\Transaction;

/** 
 * Clase 'JackpotDetalleMySqlDAO' que implementa la interfaz 'JackpotDetalleDAO'
 * Provee las consultas a la base de datos para la tabla 'jackpot_detalle'
 * 
 * @author David Torres Rendon <david.torres@virtualsoft.tech>
 * @since: Desconocido
 * @category No
 * @package No
 * @version     1.0
 */
class JackpotDetalleMySqlDAO implements JackpotDetalleDAO
{
    /** Objeto vincula una conexión de la base de datos con el objeto correspondiente
     * @var Transaction $transaction
     */
    public Transaction $transaction;

/**
     * Constructor de la clase JackpotDetalleMySqlDAO.
     *
     * @param Transaction|null $transaction Objeto de transacción opcional.
     */
    public function __construct($transaction = null) {
        $this->transaction = $transaction ?? new Transaction();
    }

    /**
     * Obtiene la transacción actual.
     *
     * @return Transaction La transacción actual.
     */
    public function getTransaction(): Transaction
    {
        return $this->transaction;
    }

    /**
     * Establece una nueva transacción.
     *
     * @param Transaction $transaction La nueva transacción a establecer.
     */
    public function setTransaction(Transaction $transaction): void
    {
        $this->transaction = $transaction;
    }

    /**
     * @inheritDoc
     */
    public function load($jackpotDetalleId)
    {
        $sql = 'SELECT * FROM jackpot_detalle WHERE jackpotdetalle_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($jackpotDetalleId);
        return $this->getRow($sqlQuery);
    }

    /**
     * @inheritDoc
     */
    public function insert(JackpotDetalle $JackpotDetalle)
    {
        $sql = 'insert into jackpot_detalle (jackpot_id ,tipo ,moneda ,valor ,usucrea_id ,usumodif_id) values (?, ?, ?, ?, ?, ?)';

        $sqlQuery = new sqlQuery($sql);
        $sqlQuery->setNumber($JackpotDetalle->getJackpotId());
        $sqlQuery->set($JackpotDetalle->getTipo());
        $sqlQuery->set($JackpotDetalle->getMoneda());
        $sqlQuery->set($JackpotDetalle->getValor());
        $sqlQuery->setNumber($JackpotDetalle->getUsuCreaId());
        $sqlQuery->setNumber($JackpotDetalle->getUsumodifId());

        $jackpotDetalleId = $this->executeInsert($sqlQuery);
        $JackpotDetalle->setJackpotDetalleId($jackpotDetalleId);
        return $jackpotDetalleId;
    }

    /**
     * @inheritDoc
     */
    public function update(JackpotDetalle $JackpotDetalle)
    {
        $sql = 'update jackpot_detalle SET jackpot_id = ?, tipo = ?, moneda = ?, valor = ?, usucrea_id = ?, usumodif_id = ? WHERE jackpotdetalle_id = ?';

        $sqlQuery = new sqlQuery($sql);
        $sqlQuery->setNumber($JackpotDetalle->getJackpotId());
        $sqlQuery->set($JackpotDetalle->getTipo());
        $sqlQuery->set($JackpotDetalle->getMoneda());
        $sqlQuery->set($JackpotDetalle->getValor());
        $sqlQuery->setNumber($JackpotDetalle->getUsuCreaId());
        $sqlQuery->setNumber($JackpotDetalle->getUsumodifId());

        //Autoincremental
        $sqlQuery->setNumber($JackpotDetalle->getJackpotDetalleId());

        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Lee una fila de datos y la convierte en un objeto JackpotDetalle.
     *
     * @param array $row La fila de datos obtenida de la base de datos.
     * @return JackpotDetalle El objeto JackpotDetalle con los datos de la fila.
     */
    protected function readRow($row)
    {
        $JackpotDetalle = new JackpotDetalle();

        $JackpotDetalle->jackpotDetalleId = $row['jackpotdetalle_id'];
        $JackpotDetalle->jackpotId = $row['jackpot_id'];
        $JackpotDetalle->tipo = $row['tipo'];
        $JackpotDetalle->moneda = $row['moneda'];
        $JackpotDetalle->valor = $row['valor'];
        $JackpotDetalle->fechaCrea = $row['fecha_crea'];
        $JackpotDetalle->fechaModif = $row['fecha_modif'];
        $JackpotDetalle->usucreaId = $row['usucrea_id'];
        $JackpotDetalle->usumodifId = $row['usumodif_id'];

        return $JackpotDetalle;
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
     * Ejecutar una consulta sql y devolver los datos
     * como un arreglo asociativo
     *
     *
     * @param String $sqlQuery consulta sql
     *
     * @return Array $ret arreglo asociativo
     *
     * @access protected
     *
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


    /**
     * Ejecutar una consulta sql como update
     *
     * @param String $sqlQuery consulta sql
     * @return Array $ resultado de la ejecución
     * @access protected
     */
    protected function executeUpdate($sqlQuery)
    {
        return QueryExecutor::executeUpdate($this->transaction, $sqlQuery);
    }

    /**
     * Consulta personalizada de detalles de jackpot.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Campo por el cual ordenar.
     * @param string $sord Orden de la consulta (ASC o DESC).
     * @param mixed $start Inicio del límite de la consulta.
     * @param mixed $limit Límite de registros a obtener.
     * @param string $filters Filtros en formato JSON para construir la cláusula WHERE.
     * @param bool $searchOn Indica si se deben aplicar los filtros.
     * @param bool $onlyCount Indica si solo se debe devolver el conteo de registros.
     * @param array $joins Arreglo de joins dinámicos a incluir en la consulta.
     * @return string JSON con el conteo de registros y los datos obtenidos.
     */
    public function queryJackpotDetalleCustom(string $select, string $sidx, string $sord,mixed $start,mixed $limit,string $filters,bool $searchOn,bool $onlyCount = false, array $joins = []): string
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

        /** Construyendo cadena de joins solicitados fuera de la petición JOINS DINÁMICOS*/
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
                $allowedJoins = ["INNER", "LEFT", "RIGHT"];
                if (in_array($join->type, $allowedJoins)) {
                    //Estructurando cadena de joins
                    $strJoins .= " " . strtoupper($join->type) . " JOIN " . $join->table . " ON (" . $join->on . ") ";
                }
            }
        }

        $sql = "select count(*) as count from jackpot_detalle inner join jackpot_interno on (jackpot_detalle.jackpot_id = jackpot_interno.jackpot_id)" . $strJoins . $where;
        $sqlQuery = new SqlQuery($sql);
        $count = $this->execute2($sqlQuery);
        if ($onlyCount) return '{"count":'. json_encode($count) .'}';

        $sql = "select ". $select ." from jackpot_detalle inner join jackpot_interno on (jackpot_detalle.jackpot_id = jackpot_interno.jackpot_id) ". $strJoins . $where ." order by ". $sidx ." ". $sord . " limit ". $start ."," . $limit;
        $sqlQuery = new SqlQuery($sql);
        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : '.json_encode($count). ', "data" : '.json_encode($result).'}';

        return  $json;
    }

}