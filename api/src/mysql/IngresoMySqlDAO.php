<?php namespace Backend\mysql;

use Backend\dao\IngresoDAO;
use Backend\dto\Helpers;
use Backend\dto\Ingreso;
use Backend\sql\QueryExecutor;
use Backend\sql\SqlQuery;
use Backend\sql\Transaction;

/**
 * Clase 'IngresoMySqlDAO'
 *
 * Esta clase provee las consultas del modelo o tabla 'Ingreso'
 *
 * Ejemplo de uso:
 * $IngresoMySqlDAO = new IngresoMySqlDAO();
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class IngresoMySqlDAO implements IngresoDAO
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
     * Obtener el registro condicionado por la
     * llave primaria que se pasa como parámetro
     *
     * @param String $cupolog_id llave primaria
     *
     * @return Array resultado de la consulta
     *
     */
    public function load($id)
    {
        $sql = 'SELECT * FROM ingreso WHERE ingreso_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($id);
        return $this->getRow($sqlQuery);
    }

    /**
     * Get all records from table
     */
    public function queryAll()
    {
        $sql = 'SELECT * FROM ingreso';
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
        $sql = 'SELECT * FROM ingreso ORDER BY ' . $orderColumn;
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }

    /**
     * Delete record from table
     * @param ingreso primary key
     */
    public function delete($ingreso_id)
    {
        $sql = 'DELETE FROM ingreso WHERE ingreso_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($ingreso_id);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Insert record to table
     *
     * @param IngresoMySql ingreso
     */
    public function insert($ingreso)
    {
        $sqlFechaA ='';
        $sqlFecha ='';

        if($ingreso->fechaCrea != ''){
            $sqlFechaA =',fecha_crea';
            $sqlFecha = ', ?';
        }

        $sql = 'INSERT INTO ingreso (tipo_id, descripcion, estado,usucrea_id,usumodif_id,centrocosto_id,documento,valor,retraccion,concepto_id,usuario_id,productoterc_id,usucajero_id,proveedorterc_id'.$sqlFechaA.',consecutivo) SELECT ?, ?, ?, ?, ?,?, ?, ?, ?, ?, ?, ?, ?, ? '.$sqlFecha.', CASE WHEN max(consecutivo)+1 IS NULL THEN 1 ELSE max(consecutivo)+1 END FROM ingreso WHERE usuario_id=' . $ingreso->usuarioId;
        $sqlQuery = new SqlQuery($sql);


        $sqlQuery->set($ingreso->tipoId);
        $sqlQuery->set($ingreso->descripcion);
        $sqlQuery->set($ingreso->estado);
        $sqlQuery->set($ingreso->usucreaId);
        $sqlQuery->set($ingreso->usumodifId);

        $sqlQuery->set($ingreso->centrocostoId);
        $sqlQuery->set($ingreso->documento);
        $sqlQuery->set($ingreso->valor);
        $sqlQuery->set($ingreso->retraccion);
        if($ingreso->conceptoId == ''){
            $ingreso->conceptoId=0;
        }
        $sqlQuery->set($ingreso->conceptoId);

        $sqlQuery->set($ingreso->usuarioId);
        $sqlQuery->set($ingreso->productotercId);
        $sqlQuery->set($ingreso->usucajeroId);
        $sqlQuery->set($ingreso->proveedortercId);
        if($ingreso->fechaCrea != ''){
            $sqlQuery->set($ingreso->fechaCrea);
        }

        $id = $this->executeInsert($sqlQuery);
        $ingreso->ingresoId = $id;
        return $id;
    }


    /**
     * Editar un registro en la base de datos
     *
     * @param Object egreso egreso
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function update($ingreso)
    {
        $sql = 'UPDATE ingreso SET tipo_id = ?, descripcion = ?, estado = ?,usucrea_id = ?,usumodif_id = ?,centrocosto_id = ?,documento = ?,valor = ?,retraccion = ?,concepto_id = ?,usuario_id=?, productoterc_id=?,usucajero_id=?,proveedorterc_id=? WHERE ingreso_id = ?';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->set($ingreso->tipoId);
        $sqlQuery->set($ingreso->descripcion);
        $sqlQuery->set($ingreso->estado);
        $sqlQuery->set($ingreso->usucreaId);
        $sqlQuery->set($ingreso->usumodifId);


        $sqlQuery->set($ingreso->centrocostoId);
        $sqlQuery->set($ingreso->documento);
        $sqlQuery->set($ingreso->valor);
        $sqlQuery->set($ingreso->retraccion);
        $sqlQuery->set($ingreso->conceptoId);

        $sqlQuery->set($ingreso->usuarioId);
        $sqlQuery->set($ingreso->productotercId);
        $sqlQuery->set($ingreso->usucajeroId);

        $sqlQuery->set($ingreso->proveedortercId);



        $sqlQuery->set($ingreso->ingresoId);
        return $this->executeUpdate($sqlQuery);
    }


    /**
     * Realizar una consulta en la tabla de ingresos 'Ingreso'
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
     * @return Array resultado de la consulta
     *
     */
    public
    function queryIngresoesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping = "")
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


        if ($grouping != "") {
            $where = $where . " GROUP BY " . $grouping;
        }


        $sql = 'SELECT count(*) count FROM ingreso  LEFT OUTER JOIN proveedor_tercero ON (proveedor_tercero.proveedorterc_id = ingreso.proveedorterc_id) LEFT OUTER JOIN producto_tercero ON (producto_tercero.productoterc_id = ingreso.productoterc_id)   LEFT OUTER JOIN cuenta_contable as cuenta_producto ON (producto_tercero.cuentacontable_id = cuenta_producto.cuentacontable_id)  LEFT OUTER JOIN cuenta_contable as cuenta_producto_egreso ON (producto_tercero.cuentacontableegreso_id = cuenta_producto_egreso.cuentacontable_id)  LEFT OUTER JOIN clasificador ON (clasificador.clasificador_id = ingreso.tipo_id)  LEFT OUTER JOIN concepto ON (ingreso.concepto_id = concepto.concepto_id)  LEFT OUTER JOIN cuenta_contable as cuenta_concepto ON (concepto.cuentacontable_id = cuenta_concepto.cuentacontable_id)  LEFT OUTER JOIN usuario as usuario_punto ON (usuario_punto.usuario_id = ingreso.usuario_id)    LEFT OUTER JOIN concesionario ON (usuario_punto.usuario_id = concesionario.usuhijo_id and prodinterno_id=0 AND concesionario.estado=\'A\')  LEFT OUTER JOIN usuario as usuario_cajero ON (usuario_cajero.usuario_id = ingreso.usucajero_id)  ' . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT ' . $select . '  FROM ingreso  LEFT OUTER JOIN proveedor_tercero ON (proveedor_tercero.proveedorterc_id = ingreso.proveedorterc_id) LEFT OUTER JOIN producto_tercero ON (producto_tercero.productoterc_id = ingreso.productoterc_id)    LEFT OUTER JOIN cuenta_contable as cuenta_producto ON (producto_tercero.cuentacontable_id = cuenta_producto.cuentacontable_id)  LEFT OUTER JOIN cuenta_contable as cuenta_producto_egreso ON (producto_tercero.cuentacontableegreso_id = cuenta_producto_egreso.cuentacontable_id)  LEFT OUTER JOIN clasificador ON (clasificador.clasificador_id = ingreso.tipo_id)  LEFT OUTER JOIN concepto ON (ingreso.concepto_id = concepto.concepto_id)   LEFT OUTER JOIN cuenta_contable as cuenta_concepto ON (concepto.cuentacontable_id = cuenta_concepto.cuentacontable_id)   LEFT OUTER JOIN usuario as usuario_punto ON (usuario_punto.usuario_id = ingreso.usuario_id)    LEFT OUTER JOIN concesionario ON (usuario_punto.usuario_id = concesionario.usuhijo_id and prodinterno_id=0 AND concesionario.estado=\'A\')  LEFT OUTER JOIN usuario as usuario_cajero ON (usuario_cajero.usuario_id = ingreso.usucajero_id)  ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;


        $sqlQuery = new SqlQuery($sql);

        $result = $Helpers->process_data($this->execute2($sqlQuery));

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }


    /**
     * Eliminar todas los registros de la base de datos
     *
     * @param no
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public
    function clean()
    {
        $sql = 'DELETE FROM ingreso';
        $sqlQuery = new SqlQuery($sql);
        return $this->executeUpdate($sqlQuery);
    }


    /**
     * Obtener todos los registros donde se encuentre que
     * la columna tipoId sea igual al valor pasado como parámetro
     *
     * @param String $value tipoId requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public
    function queryByTipo($value)
    {
        $sql = 'SELECT * FROM ingreso WHERE tipoId = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna descripcion sea igual al valor pasado como parámetro
     *
     * @param String $value descripcion requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public
    function queryByDescripcion($value)
    {
        $sql = 'SELECT * FROM ingreso WHERE descripcion = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
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
    public
    function queryByEstado($value)
    {
        $sql = 'SELECT * FROM ingreso WHERE estado = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
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
    public
    function queryByMandante($value)
    {
        $sql = 'SELECT * FROM ingreso WHERE mandante = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna codigo sea igual al valor pasado como parámetro
     *
     * @param String $value codigo requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public
    function queryByAbreviado($value)
    {
        $sql = 'SELECT * FROM ingreso WHERE codigo = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }


    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna tipoId sea igual al valor pasado como parámetro
     *
     * @param String $value tipoId requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public
    function deleteByTipo($value)
    {
        $sql = 'DELETE FROM ingreso WHERE tipoId = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna descripcion sea igual al valor pasado como parámetro
     *
     * @param String $value descripcion requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public
    function deleteByDescripcion($value)
    {
        $sql = 'DELETE FROM ingreso WHERE descripcion = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
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
    public
    function deleteByEstado($value)
    {
        $sql = 'DELETE FROM ingreso WHERE estado = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
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
    public
    function deleteByMandante($value)
    {
        $sql = 'DELETE FROM ingreso WHERE mandante = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }


    /**
     * Crear y devolver un objeto del tipo Ingreso
     * con los valores de una consulta sql
     *
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Object $Ingreso Ingreso
     *
     * @access protected
     *
     */
    protected
    function readRow($row)
    {
        $ingreso = new Ingreso();

        $ingreso->ingresoId = $row['ingreso_id'];
        $ingreso->tipoId = $row['tipo_id'];
        $ingreso->descripcion = $row['descripcion'];
        $ingreso->estado = $row['estado'];
        $ingreso->usucreaId = $row['usucrea_id'];
        $ingreso->usumodifId = $row['usumodif_id'];

        $ingreso->centrocostoId = $row['centrocosto_id'];
        $ingreso->documento = $row['documento'];
        $ingreso->valor = $row['valor'];
        $ingreso->retraccion = $row['retraccion'];
        $ingreso->conceptoId = $row['concepto_id'];
        $ingreso->usuarioId = $row['usuario_id'];
        $ingreso->productotercId = $row['productoterc_id'];
        $ingreso->proveedorterc_id = $row['proveedorterc_id'];
        $ingreso->usucajeroId = $row['usucajero_id'];
        $ingreso->consecutivo = $row['consecutivo'];
        $ingreso->fechaCrea = $row['fecha_crea'];

        return $ingreso;
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
    protected
    function getList($sqlQuery)
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
    protected
    function getRow($sqlQuery)
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
    protected
    function execute($sqlQuery)
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
    protected
    function execute2($sqlQuery)
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
    protected
    function executeUpdate($sqlQuery)
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
    protected
    function querySingleResult($sqlQuery)
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
    protected
    function executeInsert($sqlQuery)
    {
        return QueryExecutor::executeInsert($this->transaction, $sqlQuery);
    }
}

?>
