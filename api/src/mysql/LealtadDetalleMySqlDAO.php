<?php namespace Backend\mysql;
use Backend\dao\LealtadDetalleDAO;
use Backend\dto\LealtadDetalle;
use Backend\sql\QueryExecutor;
use Backend\sql\SqlQuery;
use Backend\sql\Transaction;
/**
 * Clase 'LealtadDetalleMySqlDAO'
 *
 * Esta clase provee las consultas del modelo o tabla 'SaldoUsuonlineAjuste'
 *
 * Ejemplo de uso:
 * $LealtadDetalleMySqlDAO = new LealtadDetalle();
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class LealtadDetalleMySqlDAO implements LealtadDetalleDAO
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
        $sql = 'SELECT * FROM lealtad_detalle WHERE lealtad_detalle_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($id);
        return $this->getRow($sqlQuery);
    }


    /**
     * Obtener todos los registros donde se encuentre que
     * las columnas id y tipo sean iguales a los valores
     * pasados como parametros
     *
     * @param String $id llave primaria
     * @param String $tipo tipo
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function querybyLealtadIdAndTipo($id,$tipo)
    {
        $sql = 'SELECT * FROM lealtad_detalle WHERE lealtad_id= ? AND tipo= ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($id);
        $sqlQuery->set($tipo);
        return $this->getList($sqlQuery);
    }


    /**
     * Obtener todos los registros donde se encuentre que
     * las columnas id, tipo y moneda sean iguales a los valores
     * pasados como parametros
     *
     * @param String $id llave primaria
     * @param String $tipo tipo
     * @param String $moneda moneda
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function querybyLealtadIdAndTipoAndMoneda($id,$tipo,$moneda)
    {
        $sql = 'SELECT * FROM lealtad_detalle WHERE lealtad_id= ? AND tipo= ? AND moneda=?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($id);
        $sqlQuery->set($tipo);
        $sqlQuery->set($moneda);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros de la base datos
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryAll()
    {
        $sql = 'SELECT * FROM lealtad_detalle';
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
        $sql = 'SELECT * FROM lealtad_detalle ORDER BY ' . $orderColumn;
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }

    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $ajuste_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function delete($lealtad_detalle_id)
    {
        $sql = 'DELETE FROM lealtad_detalle WHERE lealtad_detalle_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($lealtad_detalle_id);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Insertar un registro en la base de datos
     *
     * @param Objeto lealtadDetalle lealtadDetalle
     *
     * @return String $id resultado de la consulta
     *
     */
    public function insert($lealtadDetalle)
    {
        $sql = 'INSERT INTO lealtad_detalle (lealtad_id, tipo, moneda,valor,valor2,valor3, usucrea_id, usumodif_id, descripcion) VALUES ( ?, ?,?,?, ?, ?, ?, ?, ?)';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->set($lealtadDetalle->lealtadId);
        $sqlQuery->set($lealtadDetalle->tipo);
        $sqlQuery->set($lealtadDetalle->moneda);
        $sqlQuery->set($lealtadDetalle->valor);
        $sqlQuery->set($lealtadDetalle->valor2);
        $sqlQuery->set($lealtadDetalle->valor3);
        $sqlQuery->setNumber($lealtadDetalle->usucreaId);
        $sqlQuery->setNumber($lealtadDetalle->usumodifId);

        $sqlQuery->set($lealtadDetalle->descripcion);

        $id = $this->executeInsert($sqlQuery);
        $lealtadDetalle->lealtadDetalleIdId = $id;
        return $id;
    }

    /**
     * Editar un registro en la base de datos
     *
     * @param Objeto lealtadDetalle lealtadDetalle
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function update($lealtadDetalle)
    {
        $sql = 'UPDATE lealtad_detalle SET lealtad_id = ?, tipo = ?, moneda = ?,valor = ?,valor2 = ?,valor3 = ?, usucrea_id = ?, usumodif_id = ?, descripcion = ? WHERE lealtad_detalle_id = ?';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($lealtadDetalle->lealtadId);
        $sqlQuery->set($lealtadDetalle->tipo);
        $sqlQuery->set($lealtadDetalle->moneda);
        $sqlQuery->set($lealtadDetalle->valor);
        $sqlQuery->set($lealtadDetalle->valor2);
        $sqlQuery->set($lealtadDetalle->valor3);
        $sqlQuery->setNumber($lealtadDetalle->usucreaId);
        $sqlQuery->setNumber($lealtadDetalle->usumodifId);

        $sqlQuery->set($lealtadDetalle->descripcion);


        $sqlQuery->setNumber($lealtadDetalle->lealtadDetalleId);

        return $this->executeUpdate($sqlQuery);
    }


    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna lealtad_id sea igual al valor pasado como parámetro
     *
     * @param String $value lealtad_id requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByLealtadId($value)
    {
        $sql = 'DELETE FROM lealtad_detalle WHERE lealtad_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
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
        $sql = 'DELETE FROM lealtad_detalle';
        $sqlQuery = new SqlQuery($sql);
        return $this->executeUpdate($sqlQuery);
    }








    /**
     * Realizar una consulta en la tabla de detalles de lealtads 'LealtadDetalle'
     * de una manera personalizada
     *
     * @param String $select campos de consulta
     * @param String $sidx columna para ordenar
     * @param String $sord orden los datos asc | desc
     * @param String $start inicio de la consulta
     * @param String $limit limite de la consulta
     * @param String $filters condiciones de la consulta
     * @param boolean $searchOn utilizar los filtros o no
     * @param String $grouping columna para agrupar
     *
     * @return Array $json resultado de la consulta
     *
     */
    public function queryLealtadDetallesCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping="")
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
                if (oldCount($whereArray)>0)
                {
                    $where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
                }
                else
                {
                    $where = "";
                }
            }

        }



        if($grouping != ""){
            $where = $where . " GROUP BY " . $grouping;
        }



        $sql = "SELECT count(*) count FROM lealtad_detalle INNER JOIN lealtad_interna ON (lealtad_interna.lealtad_id = lealtad_detalle.lealtad_id) INNER JOIN mandante ON (lealtad_interna.mandante = mandante.mandante) " . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
        $sql = "SELECT ".$select." FROM lealtad_detalle INNER JOIN lealtad_interna ON (lealtad_interna.lealtad_id = lealtad_detalle.lealtad_id) INNER JOIN mandante ON (lealtad_interna.mandante = mandante.mandante) " . $where ." " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        if($_ENV["debugFixed2"] == '1'){
            print_r($sql);
        }
        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : '.json_encode($count). ', "data" : '.json_encode($result).'}';

        return  $json;
    }







    /**
     * Crear y devolver un objeto del tipo LealtadDetalle
     * con los valores de una consulta sql
     *
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Objeto $lealtadDetalle LealtadDetalle
     *
     * @access protected
     *
     */
    protected function readRow($row)
    {
        $lealtadDetalle = new LealtadDetalle();

        $lealtadDetalle->lealtadDetalleId = $row['lealtad_detalle_id'];
        $lealtadDetalle->lealtadId = $row['lealtad_id'];
        $lealtadDetalle->tipo = $row['tipo'];
        $lealtadDetalle->moneda = $row['moneda'];
        $lealtadDetalle->valor = $row['valor'];
        $lealtadDetalle->valor2 = $row['valor2'];
        $lealtadDetalle->valor3 = $row['valor3'];
        $lealtadDetalle->usucreaId = $row['usucrea_id'];
        $lealtadDetalle->fechaCrea = $row['fecha_crea'];
        $lealtadDetalle->usumodifId = $row['usumodif_id'];
        $lealtadDetalle->fechaModif = $row['fecha_modif'];

        $lealtadDetalle->descripcion = $row['descripcion'];

        return $lealtadDetalle;
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
