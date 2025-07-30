<?php

namespace Backend\mysql;

use Backend\dao\LogRechazoSTBDAO;
use Backend\dto\LogRechazoSTB;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Exception;

class LogRechazoSTBMySqlDAO implements LogRechazoSTBDAO
{
    public Transaction $transaction;

    public function __construct(Transaction $Transaction = null) {
        $this->transaction = $Transaction ?: new Transaction();
    }

    public function getTransaction(): Transaction
    {
        return $this->transaction;
    }

    public function setTransaction(Transaction $transaction): void
    {
        $this->transaction = $transaction;
    }


    /**
     * @inheritDoc
     */
    public function load($logId)
    {
        $sql = "SELECT * FROM log_rechazo_STB WHERE log_id = ?";
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($logId);
        return $this->getRow($sqlQuery);
    }

    /**
     * @inheritDoc
     */
    public function insert(LogRechazoSTB $LogRechazoSTB)
    {
        $sql = "INSERT INTO log_rechazo_STB (usuario_id, tipo, tipo_id, transaccion, transaccion_id, descripcion) VALUE (?, ?, ?, ?, ?, ?)";
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($LogRechazoSTB->getUsuarioId());
        $sqlQuery->set($LogRechazoSTB->getTipo());
        $sqlQuery->setNumber($LogRechazoSTB->getTipoId());
        $sqlQuery->set($LogRechazoSTB->getTransaccion());
        $sqlQuery->set($LogRechazoSTB->getTransaccionId());
        $sqlQuery->set($LogRechazoSTB->getDescripcion());

        $LogRechazoSTB->logId = $this->executeInsert($sqlQuery);
        return $LogRechazoSTB->logId;
    }

    /**
     * @inheritDoc
     */
    public function update(LogRechazoSTB $LogRechazoSTB)
    {
        $sql = "UPDATE log_rechazo_STB SET usuario_id = ?, tipo = ?, tipo_id = ?, transaccion = ?, transaccion_id = ?, descripcion = ? WHERE log_id = ?";
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($LogRechazoSTB->getUsuarioId());
        $sqlQuery->set($LogRechazoSTB->getTipo());
        $sqlQuery->setNumber($LogRechazoSTB->getTipoId());
        $sqlQuery->set($LogRechazoSTB->getTransaccion());
        $sqlQuery->set($LogRechazoSTB->getTransaccionId());
        $sqlQuery->set($LogRechazoSTB->getDescripcion());

        //Autoincremental
        $sqlQuery->setNumber($LogRechazoSTB->getLogId());
        return $this->executeUpdate($sqlQuery);
    }

    protected function readRow($row)
    {
        $LogRechazoSTB = new LogRechazoSTB();

        $LogRechazoSTB->logId = $row['log_id'];
        $LogRechazoSTB->usuarioId = $row['usuario_id'];
        $LogRechazoSTB->tipo = $row['tipo'];
        $LogRechazoSTB->tipoId = $row['tipo_id'];
        $LogRechazoSTB->transaccion = $row['transaccion'];
        $LogRechazoSTB->transaccionId = $row['transaccion_id'];
        $LogRechazoSTB->descripcion = $row['descripcion'];
        $LogRechazoSTB->fechaCrea = $row['fecha_crea'];
        $LogRechazoSTB->fechaModif = $row['fecha_modif'];

        return $LogRechazoSTB;
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

}