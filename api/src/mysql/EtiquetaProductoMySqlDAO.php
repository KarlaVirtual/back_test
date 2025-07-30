<?php namespace Backend\mysql;
use Backend\dao\EtiquetaProductoDAO;
use Backend\dto\EtiquetaProducto;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Exception;
/**
 * Clase 'EtiquetaProductoMySqlDAO'
 *
 * Esta clase provee las consultas del modelo o tabla 'EtiquetaProducto'
 *
 * Ejemplo de uso:
 * $EtiquetaProductoMySqlDAO = new EtiquetaProductoMySqlDAO();
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class EtiquetaProductoMySqlDAO implements EtiquetaProductoDAO
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
        $sql = 'SELECT * FROM etiqueta_producto WHERE etiqprod_id = ?';
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
        $sql = 'SELECT * FROM etiqueta_producto';
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
        $sql = 'SELECT * FROM etiqueta_producto ORDER BY ' . $orderColumn;
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }



    /**
     * Realizar una consulta en la tabla de EtiquetaProducto 'EtiquetaProducto'
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
    public function queryEtiquetaProductoCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
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
                if (count($whereArray) > 0) {
                    $where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
                } else {
                    $where = "";
                }
            }

        }


        $sql = 'SELECT count(*) count FROM etiqueta_producto INNER JOIN producto ON (etiqueta_producto.producto_id = producto.producto_id) INNER JOIN etiqueta ON (etiqueta.etiqueta_id = etiqueta_producto.etiqueta_id) INNER JOIN proveedor ON (producto.proveedor_id = proveedor.proveedor_id)   INNER JOIN subproveedor ON (producto.subproveedor_id = subproveedor.subproveedor_id)   ' . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT ' .$select .' FROM etiqueta_producto INNER JOIN producto ON (etiqueta_producto.producto_id = producto.producto_id) INNER JOIN etiqueta ON (etiqueta.etiqueta_id = etiqueta_producto.etiqueta_id) INNER JOIN proveedor ON (producto.proveedor_id = proveedor.proveedor_id)   INNER JOIN subproveedor ON (producto.subproveedor_id = subproveedor.subproveedor_id)  ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        try{
            syslog(LOG_WARNING, "SQLSQL :". $sql  );

        }catch (Exception $e){

        }

        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }








    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $etiqprod_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function delete($etiqprod_id)
    {
        $sql = 'DELETE FROM etiqueta_producto WHERE etiqprod_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($etiqprod_id);
        return $this->executeUpdate($sqlQuery);
    }



    /**
     * Insertar un registro en la base de datos
     *
     * @param Object etiquetaProducto etiquetaProducto
     *
     * @return String $id resultado de la consulta
     *
     */
    public function insert($etiquetaProducto)
    {
        $sql = 'INSERT INTO etiqueta_producto (producto_id, etiqueta_id,usucrea_id, usumodif_id, estado,mandante,pais_id,image,text) VALUES (?, ?, ?, ?, ?,?,?,?,?)';

        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->set($etiquetaProducto->productoId);
        $sqlQuery->set($etiquetaProducto->etiquetaId);
        $sqlQuery->setNumber($etiquetaProducto->usucreaId);
        $sqlQuery->setNumber($etiquetaProducto->usumodifId);
        $sqlQuery->set($etiquetaProducto->estado);
        $sqlQuery->set($etiquetaProducto->mandante);
        $sqlQuery->set($etiquetaProducto->paisId);
        $sqlQuery->set($etiquetaProducto->image);
        $sqlQuery->set($etiquetaProducto->text);

        $id = $this->executeInsert($sqlQuery);

        $etiquetaProducto->prodmandanteId = $id;
        return $id;
    }

    /**
     * Editar un registro en la base de datos
     *
     * @param Object etiquetaProducto etiquetaProducto
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function update($etiquetaProducto)
    {

        $sql = 'UPDATE etiqueta_producto SET producto_id = ?, etiqueta_id = ?,  usucrea_id = ?, usumodif_id = ?, estado = ? WHERE etiqprod_id = ?';

        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->set($etiquetaProducto->productoId);
        $sqlQuery->set($etiquetaProducto->etiquetaId);
        $sqlQuery->setNumber($etiquetaProducto->usucreaId);
        $sqlQuery->setNumber($etiquetaProducto->usumodifId);
        $sqlQuery->set($etiquetaProducto->estado);
        $sqlQuery->set($etiquetaProducto->etiqprodId);


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
        $sql = 'DELETE FROM etiqueta_producto';
        $sqlQuery = new SqlQuery($sql);
        return $this->executeUpdate($sqlQuery);
    }





    /**
     * Obtener todos los registros donde se encuentre que
     * la columna producto_id sea igual al valor pasado como parámetro
     *
     * @param String $value producto_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByProductoId($value)
    {
        $sql = 'SELECT * FROM etiqueta_producto WHERE producto_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);
    }


    /**
     * Obtener todos los registros donde se encuentre que
     * la columna etiqueta_id sea igual al valor pasado como parámetro
     *
     * @param String $value etiqueta_id requerido
     *
     * @return Array resultado de la consulta
     *
     */

    public function queryByEtiquetaId($value)
    {
        $sql = 'SELECT * FROM etiqueta_producto WHERE etiqueta_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);
    }


    /**
     * Obtener todos los registros donde se encuentre que
     * las columnas producto_id y mandante sean iguales
     * a los valores pasados como parametro
     *
     * @param String $productoId productoId
     * @param String $value value
     *
     * @return Array resultado de la consulta
     *
     */

    public function queryByEtiquetaIdAndProductoId($productoId, $value)
    {

        $sql = 'SELECT * FROM etiqueta_producto WHERE producto_id=? AND etiqueta_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($productoId);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);

    }


    /**
     * Consulta registros en la tabla etiqueta_producto filtrando por producto_id y etiqueta_id.
     *
     * @param int $productoId El ID del producto.
     * @param int $value El ID de la etiqueta.
     * @return array Lista de registros que coinciden con los criterios de búsqueda.
     */
    public function queryByEtiqprodIdAndProductoId($productoId, $value)
    {

        $sql = 'SELECT * FROM etiqueta_producto WHERE producto_id=? AND etiqueta_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($productoId);
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
        $sql = 'SELECT * FROM etiqueta_producto WHERE estado = ?';
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
        $sql = 'SELECT * FROM etiqueta_producto WHERE fecha_crea = ?';
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
        $sql = 'SELECT * FROM etiqueta_producto WHERE fecha_modif = ?';
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
        $sql = 'SELECT * FROM etiqueta_producto WHERE usucrea_id = ?';
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
        $sql = 'SELECT * FROM etiqueta_producto WHERE usumodif_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);
    }


    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna producto_id sea igual al valor pasado como parámetro
     *
     * @param String $value producto_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByProductoId($value)
    {
        $sql = 'DELETE FROM etiqueta_producto WHERE producto_id = ?';
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
        $sql = 'DELETE FROM etiqueta_producto WHERE estado = ?';
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
        $sql = 'DELETE FROM etiqueta_producto WHERE fecha_crea = ?';
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
        $sql = 'DELETE FROM etiqueta_producto WHERE fecha_modif = ?';
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
        $sql = 'DELETE FROM etiqueta_producto WHERE usucrea_id = ?';
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
        $sql = 'DELETE FROM etiqueta_producto WHERE usumodif_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->executeUpdate($sqlQuery);
    }


    /**
     * Crear y devolver un objeto del tipo EtiquetaProducto
     * con los valores de una consulta sql
     *
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Object $EtiquetaProducto EtiquetaProducto
     *
     * @access protected
     *
     */
    protected function readRow($row)
    {
        $etiquetaProducto = new EtiquetaProducto();

        $etiquetaProducto->etiqprodId = $row['etiqprod_id'];
        $etiquetaProducto->productoId = $row['producto_id'];
        $etiquetaProducto->etiquetaId = $row['etiqueta_id'];
        $etiquetaProducto->fechaCrea = $row['fecha_crea'];
        $etiquetaProducto->fechaModif = $row['fecha_modif'];
        $etiquetaProducto->usucreaId = $row['usucrea_id'];
        $etiquetaProducto->usumodifId = $row['usumodif_id'];
        $etiquetaProducto->estado = $row['estado'];
        $etiquetaProducto->text = $row['text'];

        return $etiquetaProducto;
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
        for ($i = 0; $i < count($tab); $i++) {
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
        if (count($tab) == 0) {
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




    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna etiqueta_id sea igual al valor pasado como parámetro.
     *
     * @param int $value El ID de la etiqueta.
     * @return boolean Resultado de la ejecución.
     */
    public function deleteByEtiquetaId($value)
    {
        $sql = 'DELETE FROM etiqueta_producto WHERE etiqueta_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna producto_id sea igual al valor pasado como parámetro.
     *
     * @param int $value El ID del producto.
     * @return array Lista de registros que coinciden con los criterios de búsqueda.
     */
    public function queryByProducto($value)
    {
        $sql = 'SELECT * FROM etiqueta_producto WHERE producto_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * las columnas producto_id, pais_id, mandante y estado sean iguales
     * a los valores pasados como parámetro.
     *
     * @param int $productoId El ID del producto.
     * @param int $paisId El ID del país.
     * @param int $mandante El ID del mandante.
     * @param string $estado El estado del producto.
     * @return array Lista de registros que coinciden con los criterios de búsqueda.
     */
    public function queryByProductoIdAndPaisIdAndMandanteAndEstado($productoId, $paisId, $mandante, $estado)
    {
        $sql = 'SELECT * FROM etiqueta_producto WHERE producto_id = ? AND pais_id = ? AND mandante = ? AND estado = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($productoId);
        $sqlQuery->setNumber($paisId);
        $sqlQuery->setNumber($mandante);
        $sqlQuery->set($estado);

        return $this->getList($sqlQuery);
    }


}
?>
