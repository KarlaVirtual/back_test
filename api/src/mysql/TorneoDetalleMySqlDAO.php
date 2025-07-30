<?php namespace Backend\mysql;
use Backend\dao\TorneoDetalleDAO;
use Backend\dto\Helpers;
use Backend\dto\TorneoDetalle;
use Backend\sql\QueryExecutor;
use Backend\sql\SqlQuery;
use Backend\sql\Transaction;
/** 
* Clase 'TorneoDetalleMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'SaldoUsuonlineAjuste'
* 
* Ejemplo de uso: 
* $TorneoDetalleMySqlDAO = new TorneoDetalle();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class TorneoDetalleMySqlDAO implements TorneoDetalleDAO
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
        $sql = 'SELECT * FROM torneo_detalle WHERE torneodetalle_id = ?';
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
    public function querybyTorneoIdAndTipo($id,$tipo)
    {
        $sql = 'SELECT * FROM torneo_detalle WHERE torneo_id= ? AND tipo= ?';
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
    public function querybyTorneoIdAndTipoAndMoneda($id,$tipo,$moneda)
    {
        $sql = 'SELECT * FROM torneo_detalle WHERE torneo_id= ? AND tipo= ? AND moneda=?';
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
        $sql = 'SELECT * FROM torneo_detalle';
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
        $sql = 'SELECT * FROM torneo_detalle ORDER BY ' . $orderColumn;
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
    public function delete($torneodetalle_id)
    {
        $sql = 'DELETE FROM torneo_detalle WHERE torneodetalle_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($torneodetalle_id);
        return $this->executeUpdate($sqlQuery);
    }
    
    /**
     * Insertar un registro en la base de datos
     *
     * @param Objeto torneoDetalle torneoDetalle
     *
     * @return String $id resultado de la consulta
     *
     */
    public function insert($torneoDetalle)
    {
        $sql = 'INSERT INTO torneo_detalle (torneo_id, tipo, moneda,valor,valor2,valor3, usucrea_id, usumodif_id, descripcion) VALUES ( ?, ?,?,?, ?, ?, ?, ?, ?)';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->set($torneoDetalle->torneoId);
        $sqlQuery->set($torneoDetalle->tipo);
        $sqlQuery->set($torneoDetalle->moneda);
        $sqlQuery->set($torneoDetalle->valor);
        $sqlQuery->set($torneoDetalle->valor2);
        $sqlQuery->set($torneoDetalle->valor3);
        $sqlQuery->setNumber($torneoDetalle->usucreaId);
        $sqlQuery->setNumber($torneoDetalle->usumodifId);

        $sqlQuery->set($torneoDetalle->descripcion);

        $id = $this->executeInsert($sqlQuery);
        $torneoDetalle->torneodetalleId = $id;
        return $id;
    }
    
    /**
     * Editar un registro en la base de datos
     *
     * @param Objeto torneoDetalle torneoDetalle
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function update($torneoDetalle)
    {
        $sql = 'UPDATE torneo_detalle SET torneo_id = ?, tipo = ?, moneda = ?,valor = ?,valor2 = ?,valor3 = ?, usucrea_id = ?, usumodif_id = ?, descripcion = ? WHERE torneodetalle_id = ?';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($torneoDetalle->torneoId);
        $sqlQuery->set($torneoDetalle->tipo);
        $sqlQuery->set($torneoDetalle->moneda);
        $sqlQuery->set($torneoDetalle->valor);
        $sqlQuery->set($torneoDetalle->valor2);
        $sqlQuery->set($torneoDetalle->valor3);
        $sqlQuery->setNumber($torneoDetalle->usucreaId);
        $sqlQuery->setNumber($torneoDetalle->usumodifId);

        $sqlQuery->set($torneoDetalle->descripcion);


        $sqlQuery->setNumber($torneoDetalle->torneodetalleId);

        return $this->executeUpdate($sqlQuery);
    }


    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna torneo_id sea igual al valor pasado como parámetro
     *
     * @param String $value torneo_id requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByTorneoId($value)
    {
        $sql = 'DELETE FROM torneo_detalle WHERE torneo_id = ?';
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
        $sql = 'DELETE FROM torneo_detalle';
        $sqlQuery = new SqlQuery($sql);
        return $this->executeUpdate($sqlQuery);
    }








    /**
    * Realizar una consulta en la tabla de detalles de torneos 'TorneoDetalle'
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
    public function queryTorneoDetallesCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping="")
    {

        $Helpers = new Helpers();
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
                $fieldName = (string)$Helpers->set_custom_field($rule->field);
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
                        $fieldOperation = "  LIKE '%".$fieldData."'";
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
                    case "isnull":
                        $fieldOperation = " IS NULL ";
                        break;
                    case "nisnull":
                        $fieldOperation = " IS NOT NULL ";
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

        $sql = "SELECT count(*) count FROM torneo_detalle INNER JOIN torneo_interno ON (torneo_interno.torneo_id = torneo_detalle.torneo_id) INNER JOIN mandante ON (torneo_interno.mandante = mandante.mandante) " . $where;

        $sqlQuery = new SqlQuery($sql);


        if($grouping != ""){
            $where = $where . " GROUP BY " . $grouping;
        }




        $count = $this->execute2($sqlQuery);
        $sql = "SELECT ".$select." FROM torneo_detalle INNER JOIN torneo_interno ON (torneo_interno.torneo_id = torneo_detalle.torneo_id) INNER JOIN mandante ON (torneo_interno.mandante = mandante.mandante) " . $where ." " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        if($_ENV["debugFixed2"] == '1'){
            print_r($sql);
        }
        $sqlQuery = new SqlQuery($sql);

        $result = $Helpers->process_data($this->execute2($sqlQuery));

        $json = '{ "count" : '.json_encode($count). ', "data" : '.json_encode($result).'}';

        return  $json;
    }






    
    /**
     * Crear y devolver un objeto del tipo TorneoDetalle
     * con los valores de una consulta sql
     * 
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Objeto $torneoDetalle TorneoDetalle
     *
     * @access protected
     *
     */
    protected function readRow($row)
    {
        $torneoDetalle = new TorneoDetalle();

        $torneoDetalle->torneodetalleId = $row['torneodetalle_id'];
        $torneoDetalle->torneoId = $row['torneo_id'];
        $torneoDetalle->tipo = $row['tipo'];
        $torneoDetalle->moneda = $row['moneda'];
        $torneoDetalle->valor = $row['valor'];
        $torneoDetalle->valor2 = $row['valor2'];
        $torneoDetalle->valor3 = $row['valor3'];
        $torneoDetalle->usucreaId = $row['usucrea_id'];
        $torneoDetalle->fechaCrea = $row['fecha_crea'];
        $torneoDetalle->usumodifId = $row['usumodif_id'];
        $torneoDetalle->fechaModif = $row['fecha_modif'];

        $torneoDetalle->descripcion = $row['descripcion'];

        return $torneoDetalle;
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
