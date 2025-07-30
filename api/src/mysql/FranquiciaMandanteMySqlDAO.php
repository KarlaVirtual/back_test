<?php namespace Backend\mysql;
use Backend\dao\FranquiciaMandanteDAO;
use Backend\dto\FranquiciaMandante;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Exception;
/**
 * Clase 'FranquiciaMandanteMySqlDAO'
 *
 * Esta clase provee las consultas del modelo o tabla 'FranquiciaMandante'
 *
 * Ejemplo de uso:
 * $FranquiciaMandanteMySqlDAO = new FranquiciaMandanteMySqlDAO();
 *
 *
 * @package ninguno
 * @author Juan Salazar <juan.salazar@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class FranquiciaMandanteMySqlDAO implements FranquiciaMandanteDAO
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
     * Obtener el registro condicionado por la
     * llave primaria que se pasa como parámetro
     *
     * @param String $id llave primaria
     *
     * @return Array resultado de la consulta
     *
     */
    public function load($id)
    {
        $sql = 'SELECT * FROM franquicia_mandante_pais WHERE franquiciamandante_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($id);
        return $this->getRow($sqlQuery);
    }

    /**
     * Obtener todos los registros de la base datos
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryAll()
    {
        $sql = 'SELECT * FROM franquicia_mandante_pais';
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
     * @return Array resultado de la consulta
     *
     */
    public function queryAllOrderBy($orderColumn)
    {
        $sql = 'SELECT * FROM franquicia_mandante_pais ORDER BY ' . $orderColumn;
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }




    /**
     * Realizar una consulta en la tabla de FranquiciaMandante 'FranquiciaMandante'
     * de una manera personalizada
     *
     * @param String $select campos de consulta
     * @param String $sidx columna para ordenar
     * @param String $sord orden los datos asc | desc
     * @param String $start inicio de la consulta
     * @param String $limit limite de la consulta
     * @param String $filters condiciones de la consulta
     * @param boolean $searchOn utilizar los filtros o no
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryFranquiciasMandanteCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {


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


        $sql = 'SELECT count(*) count FROM franquicia_mandante_pais INNER JOIN franquicia ON (franquicia_mandante_pais.franquicia_id = franquicia.franquicia_id) INNER JOIN mandante ON (mandante.mandante = franquicia_mandante_pais.mandante)  ' . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT ' .$select .'  FROM franquicia_mandante_pais INNER JOIN franquicia ON (franquicia_mandante_pais.franquicia_id = franquicia.franquicia_id) INNER JOIN mandante ON (mandante.mandante = franquicia_mandante_pais.mandante)  ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }







    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $franquiciamandante_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function delete($franquiciamandante_id)
    {
        $sql = 'DELETE FROM franquicia_mandante_pais WHERE franquiciamandante_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($franquiciamandante_id);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Insertar un registro en la base de datos
     *
     * @param Object $franquiciaMandante franquiciaMandante
     *
     * @return String $id resultado de la consulta
     *
     */
    public function insert($franquiciaMandante)
    {
        $sql = 'INSERT INTO franquicia_mandante_pais (franquicia_id, pais_id, mandante, estado, verifica, usucrea_id, usumodif_id, detalle) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($franquiciaMandante->franquiciaId);
        $sqlQuery->setNumber($franquiciaMandante->paisId);
        $sqlQuery->setNumber($franquiciaMandante->mandante);
        $sqlQuery->set($franquiciaMandante->estado);
        $sqlQuery->set($franquiciaMandante->verifica);
        $sqlQuery->setNumber($franquiciaMandante->usucreaId);
        $sqlQuery->setNumber($franquiciaMandante->usumodifId);
        $sqlQuery->set($franquiciaMandante->detalle);

        $id = $this->executeInsert($sqlQuery);
        $franquiciaMandante->franquiciamandanteId = $id;
        return $id;
    }

    /**
     * Editar un registro en la base de datos
     *
     * @param Object $franquiciaMandante franquiciaMandante
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function update($franquiciaMandante)
    {
        $sql = 'UPDATE franquicia_mandante_pais SET franquicia_id = ?, mandante = ?, estado = ?, verifica = ?, usucrea_id = ?, usumodif_id = ?, detalle = ? WHERE franquiciamandante_id = ?';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($franquiciaMandante->franquiciaId);
        $sqlQuery->setNumber($franquiciaMandante->mandante);
        $sqlQuery->set($franquiciaMandante->estado);
        $sqlQuery->set($franquiciaMandante->verifica);
        $sqlQuery->setNumber($franquiciaMandante->usucreaId);
        $sqlQuery->setNumber($franquiciaMandante->usumodifId);
        $sqlQuery->set($franquiciaMandante->detalle);

        $sqlQuery->setNumber($franquiciaMandante->franquiciamandanteId);
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
        $sql = 'DELETE FROM franquicia_mandante_pais';
        $sqlQuery = new SqlQuery($sql);
        return $this->executeUpdate($sqlQuery);
    }






    /**
     * Obtener todos los registros donde se encuentre que
     * la columna franquicia_id sea igual al valor pasado como parámetro
     *
     * @param String $value franquicia_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByFranquiciaId($value)
    {
        $sql = 'SELECT * FROM franquicia_mandante_pais WHERE franquicia_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna mandante sea igual al valor pasado como parámetro
     *
     * @param String $value mandante requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByMandante($value)
    {
        $sql = 'SELECT * FROM franquicia_mandante_pais WHERE mandante = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna $FranquiciaId sea igual al valor pasado como parámetro
     *
     * @param String $Franquicia_id franquicia_id
     * @param String $mandante mandante
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByFranquiciaIdAndMandante($FranquiciaId, $value)
    {
        $sql = 'SELECT * FROM franquicia_mandante_pais WHERE franquicia_id=? AND mandante = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($FranquiciaId);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna estado sea igual al valor pasado como parámetro
     *
     * @param String $value estado requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByEstado($value)
    {
        $sql = 'SELECT * FROM franquicia_mandante_pais WHERE estado = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna verifica sea igual al valor pasado como parámetro
     *
     * @param String $value verifica requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByVerifica($value)
    {
        $sql = 'SELECT * FROM franquicia_mandante_pais WHERE verifica = ?';
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
     * @return Array resultado de la consulta
     *
     */
    public function queryByFechaCrea($value)
    {
        $sql = 'SELECT * FROM franquicia_mandante_pais WHERE fecha_crea = ?';
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
     * @return Array resultado de la consulta
     *
     */
    public function queryByFechaModif($value)
    {
        $sql = 'SELECT * FROM franquicia_mandante_pais WHERE fecha_modif = ?';
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
     * @return Array resultado de la consulta
     *
     */
    public function queryByUsucreaId($value)
    {
        $sql = 'SELECT * FROM franquicia_mandante_pais WHERE usucrea_id = ?';
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
     * @return Array resultado de la consulta
     *
     */
    public function queryByUsumodifId($value)
    {
        $sql = 'SELECT * FROM franquicia_mandante_pais WHERE usumodif_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);
    }


    /**
     * Obtener todos los registros donde se encuentre que
     * la columna detalle sea igual al valor pasado como parámetro
     *
     * @param String $value detalle requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByDetalle($value)
    {
        $sql = 'SELECT * FROM franquicia_mandante_pais WHERE detalle = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }


    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna franquicia_id sea igual al valor pasado como parámetro
     *
     * @param String $value franquicia_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByFranquiciaId($value)
    {
        $sql = 'DELETE FROM franquicia_mandante_pais WHERE franquicia_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna mandante sea igual al valor pasado como parámetro
     *
     * @param String $value mandante requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByMandante($value)
    {
        $sql = 'DELETE FROM franquicia_mandante_pais WHERE mandante = ?';
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
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByEstado($value)
    {
        $sql = 'DELETE FROM franquicia_mandante_pais WHERE estado = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna verifica sea igual al valor pasado como parámetro
     *
     * @param String $value verifica requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByVerifica($value)
    {
        $sql = 'DELETE FROM franquicia_mandante_pais WHERE verifica = ?';
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
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByFechaCrea($value)
    {
        $sql = 'DELETE FROM franquicia_mandante_pais WHERE fecha_crea = ?';
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
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByFechaModif($value)
    {
        $sql = 'DELETE FROM franquicia_mandante_pais WHERE fecha_modif = ?';
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
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByUsucreaId($value)
    {
        $sql = 'DELETE FROM franquicia_mandante_pais WHERE usucrea_id = ?';
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
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByUsumodifId($value)
    {
        $sql = 'DELETE FROM franquicia_mandante_pais WHERE usumodif_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->executeUpdate($sqlQuery);
    }



    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna detalle sea igual al valor pasado como parámetro
     *
     * @param String $value detalle requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByDetalle($value)
    {
        $sql = 'DELETE FROM franquicia_mandante_pais WHERE detalle = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }


    /**
     * Crear y devolver un objeto del tipo FranquiciaMandante
     * con los valores de una consulta sql
     *
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Object $FranquiciaMandante FranquiciaMandante
     *
     * @access protected
     *
     */
    protected function readRow($row)
    {
        $franquiciaMandante = new FranquiciaMandante();

        $franquiciaMandante->franquiciamandanteId = $row['franquiciamandante_id'];
        $franquiciaMandante->franquiciaId = $row['franquicia_id'];
        $franquiciaMandante->mandante = $row['mandante'];
        $franquiciaMandante->estado = $row['estado'];
        $franquiciaMandante->verifica = $row['verifica'];
        $franquiciaMandante->fechaCrea = $row['fecha_crea'];
        $franquiciaMandante->fechaModif = $row['fecha_modif'];
        $franquiciaMandante->usucreaId = $row['usucrea_id'];
        $franquiciaMandante->usumodifId = $row['usumodif_id'];
        $franquiciaMandante->detalle = $row['detalle'];

        return $franquiciaMandante;
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