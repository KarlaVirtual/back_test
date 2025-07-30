<?php

namespace Backend\mysql;

use Backend\dao\TransprodLogDAO;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Backend\dto\TransprodLog;

/** 
 * Clase 'TransprodLogMySqlDAO'
 * 
 * Esta clase provee las consultas del modelo o tabla 'TransprodLog'
 * 
 * Ejemplo de uso: 
 * $TransprodLogMySqlDAO = new TransprodLogMySqlDAO();
 *   
 * 
 * @package ninguno 
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public 
 * @see no
 * 
 */
class TransprodLogMySqlDAO implements TransprodLogDAO
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
        $sql = 'SELECT * FROM transprod_log WHERE transprodlog_id = ?';
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
        $sql = 'SELECT * FROM transprod_log';
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
        $sql = 'SELECT * FROM transprod_log ORDER BY ' . $orderColumn;
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }

    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $transprodlog_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function delete($transprodlog_id)
    {
        $sql = 'DELETE FROM transprod_log WHERE transprodlog_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($transprodlog_id);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Insertar un registro en la base de datos
     *
     * @param Objeto transprodLog transprodLog
     *
     * @return String $id resultado de la consulta
     *
     */
    public function insert($transprodLog)
    {
        $sql = 'INSERT INTO transprod_log (transproducto_id, estado, tipo_genera, comentario, t_value, usucrea_id, usumodif_id) VALUES (?, ?, ?, ?, ?, ?, ?)';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($transprodLog->transproductoId);
        $sqlQuery->set($transprodLog->estado);
        $sqlQuery->set($transprodLog->tipoGenera);
        $sqlQuery->set($transprodLog->comentario);
        $sqlQuery->set($transprodLog->tValue);
        $sqlQuery->setNumber($transprodLog->usucreaId);
        $sqlQuery->setNumber($transprodLog->usumodifId);

        $id = $this->executeInsert($sqlQuery);


        $transprodLog->transprodlogId = $id;
        return $id;
    }

    /**
     * Consulta registros en la tabla transprod_log filtrando por transproducto_id y comentario.
     *
     * @param int $transproducto_id El ID del transproducto.
     * @param string $comentario El comentario asociado.
     * @return array Lista de registros que coinciden con los criterios de búsqueda.
     */
    public function queryByTransproductoIdAndComentario($transproducto_id, $comentario)
    {
        $sql = 'SELECT * FROM transprod_log WHERE transproducto_id = ? AND comentario = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($transproducto_id);
        $sqlQuery->set($comentario);
        return $this->getList($sqlQuery);
    }

    /**
     * Editar un registro en la base de datos
     *
     * @param Objeto transprodLog transprodLog
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function update($transprodLog)
    {
        $sql = 'UPDATE transprod_log SET transproducto_id = ?, estado = ?, tipo_genera = ?, comentario = ?, t_value = ?, usucrea_id = ?, usumodif_id = ? WHERE transprodlog_id = ?';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($transprodLog->transproductoId);
        $sqlQuery->set($transprodLog->estado);
        $sqlQuery->set($transprodLog->tipoGenera);
        $sqlQuery->set($transprodLog->comentario);
        $sqlQuery->set($transprodLog->tValue);
        $sqlQuery->setNumber($transprodLog->usucreaId);
        $sqlQuery->setNumber($transprodLog->usumodifId);

        $sqlQuery->setNumber($transprodLog->transprodlogId);
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
        $sql = 'DELETE FROM transprod_log';
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
        $sql = 'SELECT * FROM transprod_log WHERE transproducto_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna estado sea igual al valor pasado como parámetro
     *
     * @param String $value estado requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByEstado($value)
    {
        $sql = 'SELECT * FROM transprod_log WHERE estado = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna tipo_genera sea igual al valor pasado como parámetro
     *
     * @param String $value tipo_genera requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByTipoGenera($value)
    {
        $sql = 'SELECT * FROM transprod_log WHERE tipo_genera = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna comentario sea igual al valor pasado como parámetro
     *
     * @param String $value comentario requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByComentario($value)
    {
        $sql = 'SELECT * FROM transprod_log WHERE comentario = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
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
        $sql = 'SELECT * FROM transprod_log WHERE t_value = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna fecha_crea sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_crea requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByFechaCrea($value)
    {
        $sql = 'SELECT * FROM transprod_log WHERE fecha_crea = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna fecha_modif sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_modif requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByFechaModif($value)
    {
        $sql = 'SELECT * FROM transprod_log WHERE fecha_modif = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna usucrea_id sea igual al valor pasado como parámetro
     *
     * @param String $value usucrea_id requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByUsucreaId($value)
    {
        $sql = 'SELECT * FROM transprod_log WHERE usucrea_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna usumodif_id sea igual al valor pasado como parámetro
     *
     * @param String $value usumodif_id requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByUsumodifId($value)
    {
        $sql = 'SELECT * FROM transprod_log WHERE usumodif_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
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
        $sql = 'DELETE FROM transprod_log WHERE transproducto_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna estado sea igual al valor pasado como parámetro
     *
     * @param String $value estado requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByEstado($value)
    {
        $sql = 'DELETE FROM transprod_log WHERE estado = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna tipo_genera sea igual al valor pasado como parámetro
     *
     * @param String $value tipo_genera requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByTipoGenera($value)
    {
        $sql = 'DELETE FROM transprod_log WHERE tipo_genera = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna comentario sea igual al valor pasado como parámetro
     *
     * @param String $value comentario requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByComentario($value)
    {
        $sql = 'DELETE FROM transprod_log WHERE comentario = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
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
        $sql = 'DELETE FROM transprod_log WHERE t_value = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna fecha_crea sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_crea requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByFechaCrea($value)
    {
        $sql = 'DELETE FROM transprod_log WHERE fecha_crea = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna fecha_modif sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_modif requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByFechaModif($value)
    {
        $sql = 'DELETE FROM transprod_log WHERE fecha_modif = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna usucrea_id sea igual al valor pasado como parámetro
     *
     * @param String $value usucrea_id requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByUsucreaId($value)
    {
        $sql = 'DELETE FROM transprod_log WHERE usucrea_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna usumodif_id sea igual al valor pasado como parámetro
     *
     * @param String $value usumodif_id requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByUsumodifId($value)
    {
        $sql = 'DELETE FROM transprod_log WHERE usumodif_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->executeUpdate($sqlQuery);
    }














    /**
     * Crear y devolver un objeto del tipo TransprodLog
     * con los valores de una consulta sql
     * 
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Objeto $transprodLog TransprodLog
     *
     * @access protected
     *
     */
    protected function readRow($row)
    {
        $transprodLog = new TransprodLog();

        $transprodLog->transprodlogId = $row['transprodlog_id'];
        $transprodLog->transproductoId = $row['transproducto_id'];
        $transprodLog->estado = $row['estado'];
        $transprodLog->tipoGenera = $row['tipo_genera'];
        $transprodLog->comentario = $row['comentario'];
        $transprodLog->tValue = $row['t_value'];
        $transprodLog->fechaCrea = $row['fecha_crea'];
        $transprodLog->fechaModif = $row['fecha_modif'];
        $transprodLog->usucreaId = $row['usucrea_id'];
        $transprodLog->usumodifId = $row['usumodif_id'];

        return $transprodLog;
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
