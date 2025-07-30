<?php namespace Backend\mysql;

use Backend\dao\TransproductoDetalleDAO;
use Backend\dto\TransproductoDetalle;
use Backend\sql\QueryExecutor;
use Backend\sql\SqlQuery;
use Backend\sql\Transaction;

/**
 * Clase 'TransproductoDetalleMySqlDAO'
 *
 * Esta clase provee las consultas del modelo o tabla 'TransproductoDetalle'
 *
 * Ejemplo de uso:
 * $TransproductoDetalleMySqlDAO = new TransproductoDetalleMySqlDAO();
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class TransproductoDetalleMySqlDAO implements TransproductoDetalleDAO
{

    /**
     * Atributo Transaction transacción
     *
     * @var Objeto
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
        $sql = 'SELECT * FROM transproducto_detalle WHERE transproddetalle_id = ?';
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
        $sql = 'SELECT * FROM transproducto_detalle';
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
        $sql = 'SELECT * FROM transproducto_detalle ORDER BY ' . $orderColumn;
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }

    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $transproddetalle_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function delete($transproddetalle_id)
    {
        $sql = 'DELETE FROM transproducto_detalle WHERE transproddetalle_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($transproddetalle_id);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Insertar un registro en la base de datos
     *
     * @param Objeto transproductoDetalle transproductoDetalle
     *
     * @return String $id resultado de la consulta
     *
     */
    public function insert($transproductoDetalle)
    {
        $sql = 'INSERT INTO transproducto_detalle (transproducto_id, t_value) VALUES (?, ?)';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($transproductoDetalle->transproductoId);
        $sqlQuery->set($transproductoDetalle->tValue);

        $id = $this->executeInsert($sqlQuery);
        $transproductoDetalle->transproddetalleId = $id;
        return $id;
    }

    /**
     * Editar un registro en la base de datos
     *
     * @param Objeto transproductoDetalle transproductoDetalle
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function update($transproductoDetalle)
    {
        $sql = 'UPDATE transproducto_detalle SET transproducto_id = ?, t_value = ? WHERE transproddetalle_id = ?';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($transproductoDetalle->transproductoId);
        $sqlQuery->set($transproductoDetalle->tValue);

        $sqlQuery->setNumber($transproductoDetalle->transproddetalleId);
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
        $sql = 'DELETE FROM transproducto_detalle';
        $sqlQuery = new SqlQuery($sql);
        return $this->executeUpdate($sqlQuery);
    }


    /**
     * Obtener todos los registros donde se encuentre que
     * la columna transproducto_id sea igual al valor pasado como parámetro
     *
     * @param String $value transproducto_id requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByTransproductoId($value)
    {
        $sql = 'SELECT * FROM transproducto_detalle WHERE transproducto_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
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
        $sql = 'SELECT * FROM transproducto_detalle WHERE t_value = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }


    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna transproducto_id sea igual al valor pasado como parámetro
     *
     * @param String $value transproducto_id requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByTransproductoId($value)
    {
        $sql = 'DELETE FROM transproducto_detalle WHERE transproducto_id = ?';
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
        $sql = 'DELETE FROM transproducto_detalle WHERE t_value = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }


    /**
     * Crear y devolver un objeto del tipo TransproductoDetalle
     * con los valores de una consulta sql
     *
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Objeto $transproductoDetalle TransproductoDetalle
     *
     * @access protected
     *
     */
    protected function readRow($row)
    {
        $transproductoDetalle = new TransproductoDetalle();

        $transproductoDetalle->transproddetalleId = $row['transproddetalle_id'];
        $transproductoDetalle->transproductoId = $row['transproducto_id'];
        $transproductoDetalle->tValue = $row['t_value'];

        return $transproductoDetalle;
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

?>