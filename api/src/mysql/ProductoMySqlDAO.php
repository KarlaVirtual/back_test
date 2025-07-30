<?php

namespace Backend\mysql;

use Backend\dao\ProductoDAO;
use Backend\dto\BonoInterno;
use Backend\dto\Producto;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Exception;

/**
 * Clase 'ProductoMySqlDAO'
 *
 * Esta clase provee las consultas del modelo o tabla 'Producto'
 *
 * Ejemplo de uso:
 * $ProductoMySqlDAO = new ProductoMySqlDAO();
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class ProductoMySqlDAO implements ProductoDAO
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
        $sql = 'SELECT * FROM producto WHERE producto_id = ?';
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
        $sql = 'SELECT * FROM producto';
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
        $sql = 'SELECT * FROM producto ORDER BY ' . $orderColumn;
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }

    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $producto_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function delete($producto_id)
    {
        $sql = 'DELETE FROM producto WHERE producto_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($producto_id);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Insertar un registro en la base de datos
     *
     * @param Object producto producto
     *
     * @return String $id resultado de la consulta
     *
     */
    public function insert($producto)
    {
        $sql = 'INSERT INTO producto (proveedor_id, descripcion, image_url, estado, verifica,externo_id, usucrea_id, usumodif_id,mostrar,orden,mobile,desktop,subproveedor_id,image_url2, categoria_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?)';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($producto->proveedorId);
        $sqlQuery->set($producto->descripcion);
        $sqlQuery->set($producto->imageUrl);
        $sqlQuery->set($producto->estado);
        $sqlQuery->set($producto->verifica);
        $sqlQuery->set($producto->externoId);
        $sqlQuery->setNumber($producto->usucreaId);
        $sqlQuery->setNumber($producto->usumodifId);
        $sqlQuery->set($producto->mostrar);
        $sqlQuery->set($producto->orden);
        $sqlQuery->set($producto->mobile);
        $sqlQuery->set($producto->desktop);

        if ($producto->subproveedorId == '') {
            $producto->subproveedorId = 0;
        }
        $sqlQuery->set($producto->subproveedorId);
        $sqlQuery->set($producto->imageUrl2);

        if ($producto->categoriaId == '') {
            $producto->categoriaId = '0';
        }
        $sqlQuery->set($producto->categoriaId);

        $id = $this->executeInsert($sqlQuery);
        $producto->productoId = $id;
        return $id;
    }


    /**
     * Editar un registro en la base de datos
     *
     * @param Object producto producto
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function update($producto)
    {
        $sql = 'UPDATE producto SET proveedor_id = ?, descripcion = ?, image_url = ?, estado = ?, verifica = ?, externo_id = ?, usucrea_id = ?, usumodif_id = ?,mostrar = ?,orden = ?,mobile = ?,desktop = ?,subproveedor_id = ?,image_url2 = ?, categoria_id = ? WHERE producto_id = ?';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($producto->proveedorId);
        $sqlQuery->set($producto->descripcion);
        $sqlQuery->set($producto->imageUrl);
        $sqlQuery->set($producto->estado);
        $sqlQuery->set($producto->verifica);
        $sqlQuery->set($producto->externoId);
        $sqlQuery->setNumber($producto->usucreaId);
        $sqlQuery->setNumber($producto->usumodifId);
        $sqlQuery->set($producto->mostrar);
        $sqlQuery->set($producto->orden);
        $sqlQuery->set($producto->mobile);
        $sqlQuery->set($producto->desktop);

        if ($producto->subproveedorId == '') {
            $producto->subproveedorId = 0;
        }
        $sqlQuery->set($producto->subproveedorId);
        $sqlQuery->set($producto->imageUrl2);

        if ($producto->categoriaId == '') {
            $producto->categoriaId = '0';
        }
        $sqlQuery->set($producto->categoriaId);



        $sqlQuery->setNumber($producto->productoId);
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
        $sql = 'DELETE FROM producto';
        $sqlQuery = new SqlQuery($sql);
        return $this->executeUpdate($sqlQuery);
    }





    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna proveedor_id sea igual al valor pasado como parámetro
     *
     * @param String $value proveedor_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function queryByProveedorId($value)
    {
        $sql = 'SELECT * FROM producto WHERE proveedor_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);
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
    public function queryByDescripcion($value)
    {
        $sql = 'SELECT * FROM producto WHERE descripcion = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna image_url sea igual al valor pasado como parámetro
     *
     * @param String $value image_url requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function queryByImageUrl($value)
    {
        $sql = 'SELECT * FROM producto WHERE image_url = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
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
    public function queryByEstado($value)
    {
        $sql = 'SELECT * FROM producto WHERE estado = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
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
    public function queryByVerifica($value)
    {
        $sql = 'SELECT * FROM producto WHERE verifica = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna externo_id sea igual al valor pasado como parámetro
     *
     * @param String $value externo_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function queryByExternoId($value)
    {
        $sql = 'SELECT * FROM producto WHERE externo_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columnas externo_id y proveedor_id sean iguales
     * a los valores pasados como parametro
     *
     * @param String $value externo_id requerido
     * @param String $value proveedor_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function queryByExternoIdAndProveedorId($value, $proveedor)
    {
        $sql = 'SELECT * FROM producto WHERE externo_id = ? AND proveedor_id= ?';

        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        $sqlQuery->set($proveedor);
        return $this->getList($sqlQuery);
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
    public function queryByFechaCrea($value)
    {
        $sql = 'SELECT * FROM producto WHERE fecha_crea = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
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
    public function queryByUsucreaId($value)
    {
        $sql = 'SELECT * FROM producto WHERE usucrea_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);
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
    public function queryByUsumodifId($value)
    {
        $sql = 'SELECT * FROM producto WHERE usumodif_id = ?';
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
    public function getDetalles($productoId)
    {
        $sql = 'SELECT product_detalle.* FROM producto_detalle WHERE producto_id =? ';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($productoId);
        return $this->execute2($sqlQuery);
    }








    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna proveedor_id sea igual al valor pasado como parámetro
     *
     * @param String $value proveedor_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByProveedorId($value)
    {
        $sql = 'DELETE FROM producto WHERE proveedor_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
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
    public function deleteByDescripcion($value)
    {
        $sql = 'DELETE FROM producto WHERE descripcion = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna image_url sea igual al valor pasado como parámetro
     *
     * @param String $value image_url requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByImageUrl($value)
    {
        $sql = 'DELETE FROM producto WHERE image_url = ?';
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
    public function deleteByEstado($value)
    {
        $sql = 'DELETE FROM producto WHERE estado = ?';
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
        $sql = 'DELETE FROM producto WHERE verifica = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna externo_id sea igual al valor pasado como parámetro
     *
     * @param String $value externo_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByExternoId($value)
    {
        $sql = 'DELETE FROM producto WHERE externo_id = ?';
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
        $sql = 'DELETE FROM producto WHERE fecha_crea = ?';
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
        $sql = 'DELETE FROM producto WHERE usucrea_id = ?';
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
        $sql = 'DELETE FROM producto WHERE usumodif_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->executeUpdate($sqlQuery);
    }








    /**
     * Crear y devolver un objeto del tipo Producto
     * con los valores de una consulta sql
     *
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Object $Producto Producto
     *
     * @access protected
     *
     */

    protected function readRow($row)
    {
        $producto = new Producto();

        $producto->productoId = $row['producto_id'];
        $producto->proveedorId = $row['proveedor_id'];
        $producto->descripcion = $row['descripcion'];
        $producto->imageUrl = $row['image_url'];
        $producto->estado = $row['estado'];
        $producto->verifica = $row['verifica'];
        $producto->externoId = $row['externo_id'];
        $producto->usucreaId = $row['usucrea_id'];
        $producto->usumodifId = $row['usumodif_id'];
        $producto->orden = $row['orden'];
        $producto->mostrar = $row['mostrar'];
        $producto->mobile = $row['mobile'];
        $producto->desktop = $row['desktop'];
        $producto->subproveedorId = $row['subproveedor_id'];
        $producto->pagoTerceros = $row['pago_terceros'];
        $producto->rtpTeorico = $row['rtp_teorico'];
        $producto->categoriaId = $row['categoria_id'];

        $producto->imageUrl2 = $row['image_url2'];
        return $producto;
    }





    /**
     * Realizar una consulta en la tabla de Producto 'Producto'
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
    public function queryProductos($sidx, $sord, $start, $limit, $filters, $searchOn)
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
                    $where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
                } else {
                    $where = "";
                }
            }
        }


        $sql = 'SELECT count(*) count FROM proveedor LEFT OUTER JOIN producto ON (producto.proveedor_id = proveedor.proveedor_id)' . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT proveedor.*,producto.* FROM proveedor LEFT OUTER JOIN producto ON (producto.proveedor_id = proveedor.proveedor_id)' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }


    /**
     * Realizar una consulta en la tabla de Producto 'Producto'
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
    public function queryProductosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {


        $where = " where 1=1 ";
        $innProductoDetalle = false;


        if ($searchOn) {
            // Construye el where
            $filters = json_decode($filters);
            $whereArray = array();
            $rules = $filters->rules;
            $groupOperation = $filters->groupOp;
            $cont = 0;

            $cond = "producto_detalle";
            if (strpos($select, $cond) !== false) {
                $innProductoDetalle = true;
            }

            foreach ($rules as $rule) {
                $fieldName = $rule->field;
                $fieldData = $rule->data;
                $cond = "producto_detalle";

                if (strpos($fieldName, $cond) !== false || strpos($sidx, $cond) !== false   || strpos($select, $cond) !== false) {
                    $innProductoDetalle = true;
                }

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


        if ($innProductoDetalle) {

            $innnproductoDetalleStr = '
        
        LEFT OUTER JOIN producto_detalle ON (producto_detalle.producto_id= producto.producto_id AND producto_detalle.p_key="GAMEID" )
';
        }



        $sql = 'SELECT count(*) count FROM producto INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id) LEFT OUTER JOIN categoria_mandante ON (categoria_mandante.catmandante_id = producto.categoria_id)   INNER JOIN subproveedor ON (subproveedor.subproveedor_id = producto.subproveedor_id) 

    
    ' . $innnproductoDetalleStr . $where;


        $where = $where . " GROUP BY producto.producto_id ";

        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT ' . $select . '  FROM producto INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id) LEFT OUTER JOIN categoria_mandante ON (categoria_mandante.catmandante_id = producto.categoria_id) INNER JOIN subproveedor ON (subproveedor.subproveedor_id = producto.subproveedor_id)  LEFT OUTER JOIN categoria_producto ON (producto.producto_id = categoria_producto.producto_id) LEFT OUTER JOIN categoria ON (categoria.categoria_id = categoria_producto.categoria_id) ' . $innnproductoDetalleStr . $where . "  order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        if ($_ENV["debugFixed2"] == '1') {
            print_r($sql);
        }
        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }

    /**
     * Realizar una consulta personalizada en la tabla producto con filtro por mandante y país
     *
     * @param string $select campos de consulta
     * @param string $sidx columna para ordenar
     * @param string $sord orden de los datos asc | desc
     * @param int $start inicio de la consulta
     * @param int $limit límite de la consulta
     * @param string $filters condiciones de la consulta
     * @param boolean $searchOn utilizar los filtros o no
     * @param string $mandante mandante para filtrar
     * @param string $paisId ID del país para filtrar
     *
     * @return string JSON con el conteo y los datos de la consulta
     */

    public function queryProductosCustomMandante($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $mandante, $paisId = '')
    {


        $where = " where 1=1 ";

        $withproductoMandante = false;
        $withsubproveedorMandantePais = false;


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

                $cond = "producto_mandante";
                if (strpos($fieldName, $cond) !== false || strpos($sidx, $cond) !== false || strpos($grouping, $cond) !== false  || strpos($select, $cond) !== false) {
                    $withproductoMandante = true;
                }
                $cond = "subproveedor_mandante_pais";
                if (strpos($fieldName, $cond) !== false || strpos($sidx, $cond) !== false || strpos($grouping, $cond) !== false  || strpos($select, $cond) !== false) {
                    $withsubproveedorMandantePais = true;
                }
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

        $innerproducto_mandante = '';

        if ($withproductoMandante) {
            $strPaisId = '';
            if ($paisId != '') {
                $strPaisId = ' AND producto_mandante.pais_id = "' . $paisId . '" ';
            }
            $innerproducto_mandante = '  INNER JOIN producto_mandante ON (producto_mandante.producto_id = producto.producto_id AND producto_mandante.mandante= "' . $mandante . '" ' . $strPaisId . ')  ';
        }
        if ($withsubproveedorMandantePais) {

            $innerproducto_mandante .= '   LEFT OUTER JOIN subproveedor_mandante_pais ON (producto.subproveedor_id = subproveedor_mandante_pais.subproveedor_id AND "' . $paisId . '" = subproveedor_mandante_pais.pais_id AND "' . $mandante . '" = subproveedor_mandante_pais.mandante)  ';
        }


        $sql = 'SELECT count(*) count FROM producto ' . $innerproducto_mandante . '  INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id)  INNER JOIN subproveedor ON (subproveedor.subproveedor_id = producto.subproveedor_id)    LEFT OUTER JOIN categoria_producto ON (producto.producto_id = categoria_producto.producto_id) LEFT OUTER JOIN categoria ON (categoria.categoria_id = categoria_producto.categoria_id)    INNER JOIN subproveedor_mandante ON (subproveedor.subproveedor_id = subproveedor_mandante.subproveedor_id AND subproveedor_mandante.mandante="' . $mandante . '") ' . $where;


        $where = $where . " GROUP BY producto.producto_id ";

        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $innerproducto_mandante = '';

        if ($withproductoMandante) {
            $strPaisId = '';
            if ($paisId != '') {
                $strPaisId = ' AND producto_mandante.pais_id = "' . $paisId . '" ';
            }
            $innerproducto_mandante = '  INNER JOIN producto_mandante ON (producto_mandante.producto_id = producto.producto_id AND producto_mandante.mandante= "' . $mandante . '" ' . $strPaisId . ')  ';
        }
        if ($withsubproveedorMandantePais) {

            $innerproducto_mandante .= '   LEFT OUTER JOIN subproveedor_mandante_pais ON (producto.subproveedor_id = subproveedor_mandante_pais.subproveedor_id AND "' . $paisId . '" = subproveedor_mandante_pais.pais_id AND "' . $mandante . '" = subproveedor_mandante_pais.mandante)  ';
        }


        $sql = 'SELECT ' . $select . '  FROM producto  ' . $innerproducto_mandante . ' INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id) INNER JOIN subproveedor ON (subproveedor.subproveedor_id = producto.subproveedor_id)  LEFT OUTER JOIN categoria_producto ON (producto.producto_id = categoria_producto.producto_id) LEFT OUTER JOIN categoria ON (categoria.categoria_id = categoria_producto.categoria_id)  LEFT OUTER JOIN subproveedor_mandante ON (subproveedor.subproveedor_id = subproveedor_mandante.subproveedor_id AND subproveedor_mandante.mandante="' . $mandante . '") ' . $where . "  order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;


        if ($_ENV["debugFixed2"] == '1') {
            print_r(PHP_EOL);
            print_r(PHP_EOL);
            print_r($sql);
        }
        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }

    /**
     * Realizar una consulta en la tabla de Producto 'Producto'
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
    public function getAllProductos($value, $category, $provider, $offset, $limit, $search, $partnerId)
    {
        $where = " 1=1 ";
        if ($category != "") {
            $where = $where . " AND categoria_producto.categoria_id= ? ";
        }

        if ($provider != "") {
            $where = $where . " AND proveedor.abreviado = ? ";
        }

        if ($search != "") {
            $where = $where . " AND producto.descripcion  LIKE '%" . $search . "%' ";
        }

        if ($offset == "" || $limit == "") {
            $limit = 15;
            $offset = 0;
        }


        $sql = 'SELECT proveedor.*,producto.*,producto_mandante.*, categoria_producto.*,categoria.*,producto_detalle.p_value background FROM proveedor
        LEFT OUTER JOIN producto ON (producto.proveedor_id = proveedor.proveedor_id)
INNER JOIN categoria_producto ON (categoria_producto.producto_id = producto.producto_id)
INNER JOIN producto_mandante ON (producto.producto_id = producto_mandante.producto_id AND producto_mandante.mandante = ' . $partnerId . ' ) LEFT OUTER JOIN categoria ON (categoria.categoria_id =categoria_producto.categoria_id )
LEFT OUTER JOIN producto_detalle ON (producto_detalle.producto_id= producto.producto_id AND producto_detalle.p_key = "IMAGE_BACKGROUND")
 WHERE ' . $where . ' AND proveedor.Tipo = ? AND proveedor.estado="A" AND producto.estado="A" AND producto.mostrar="S" LIMIT ' . ($limit - $offset) . ' OFFSET ' . $offset;

        $sqlQuery = new SqlQuery($sql);
        if ($category != "") {
            $sqlQuery->setNumber($category);
        }

        if ($provider != "") {
            $sqlQuery->set($provider);
        }
        $sqlQuery->set($value);

        return $this->execute2($sqlQuery);
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

    /** ------------------------- @FuncionesReporteDePoker ----------------------------------------- */
    /** Función proveé las sql raíz para la implementación de los distintos reportes de poker
     *Estas SQL se fundamentas en un conjunto de consultas CTE (CTE = Common Table Expression) cada una con un alcance específico
     *La función recibe un parámetro tipo respecto al cual construye la consulta brindando un string con etiquetas
     * intercambiables a filtros y fragmentos de código.
     * @param int $type Tipo de reporte a construir
     * 1 = Detallado Poker
     * 2 = Agrupado por proveedor y usuario
     * @return string SQL raíz para el reporte de poker según el tipo de reporte
     */
    private function getRootSqlPokerReport(int $type): string
    {
        /*Definición estructura fundamental de las consultas*/
        $finalQuery = "";

        /*La CTE ROOT obtiene la información global de la sesión, info del usuario, producto y proveedor
        El select y múltiples parámetros en el where de esta consulta son parametrizables*/
        $ROOTCTE = "-- ROOTCTE_BEGIN --
        ROOT AS (SELECT
        #RootCteSelect#
        FROM transaccion_juego tj
        INNER JOIN usuario_mandante um ON (um.usumandante_id = tj.usuario_id) #Información del usuario
        INNER JOIN producto_mandante pm ON (tj.producto_id = pm.prodmandante_id) #Obtención fundamental del producto de POKER
        INNER JOIN producto p ON (pm.producto_id = p.producto_id) #Obtención fundamental del producto de POKER
        INNER JOIN subproveedor spv ON (p.subproveedor_id = spv.subproveedor_id) #Obtención del subproveedor
        INNER JOIN proveedor pv ON (pv.proveedor_id = spv.proveedor_id) #Obtención del proveedor
        WHERE 1 = 1
        #CollectableExternals#
        #Country#
        #Partner#
        #UserId#
        #SupplierId#
        #SubsupplierId#
        #DateFrom#
        #DateTo#
        )
        -- ROOTCTE_END --";

        /*La CTE MONETARIANCE_VALUES vincula cada sesión de juego con los múltiples logs que aloja en transjuego_log
        El select y group by de esta consulta son parametrizables*/
        $MONETARIANCTE = "-- MONETARIANCTE_BEGIN --
        MONETARIAN_VALUES AS (
        SELECT
        #MonetarianCteSelect#
        FROM ROOT
        INNER JOIN transjuego_log tjl ON (ROOT.transjuegoId = tjl.transjuego_id)
        #MonetarianGrouping#
        )
        -- MONETARIANCTE_END --";

        /* La CTE POKER_LOGS vincula cada sesión de juego con los múltiples logs que aloja en transjuego_info
        El select y group by de esta consulta son parametrizables */
        $POKERLOGSCTE = " -- POKERLOGSCTE_BEGIN --
        POKER_LOGS AS (
        SELECT
        #PokerLogsCteSelect#
        FROM ROOT
        INNER JOIN transjuego_info ti ON (ROOT.sesionId = ti.identificador AND ti.tipo in ('RAKE', 'DEBITPOKERTORNEO', 'TORNEOBUYPOKER'))
        #PokerLogsGrouping#
        )
        -- POKERLOGSCTE_END --";

        /*MAINQUERY es la consulta principal del CTE y sobre la cual obtenemos la data
        El select, múltiples filtros where, el group by y limit de esta consulta son parametrizables*/
        $MAINQUERY = "-- MAINQUERY_BEGIN --
        SELECT 
        #MainSelect#
        FROM ROOT
        INNER JOIN MONETARIAN_VALUES ON (ROOT.transjuegoId = MONETARIAN_VALUES.transjuegoId)
        LEFT JOIN POKER_LOGS ON (ROOT.sesionId = POKER_LOGS.sesionId)
        WHERE 1 = 1
        #GameType#
        #MainQueryGrouping#
        #Limit#
        -- MAINQUERY_END --";

        /*Definición Selects por defecto para las consultas*/
        $defaultRootCteSelect = "tj.transjuego_id AS transjuegoId,
        tj.ticket_id AS sesionId,
        tj.fecha_crea AS fechaCrea,
        um.usumandante_id AS usuarioCasino,
        um.usuario_mandante AS usuario,
        pv.proveedor_id AS proveedorId,
        pv.descripcion AS proveedor,
        spv.subproveedor_id AS subproveedorId,
        spv.descripcion AS subproveedor";

        $defaultMonetarianCteSelect = "ROOT.transjuegoId AS transjuegoId,
        ROOT.sesionId  AS sesionId,
        SUM(CASE WHEN tjl.tipo = 'DEBIT' THEN (ROUND(COALESCE(CAST(tjl.valor AS DOUBLE), 0), 2)) ELSE 0 END) AS valorApostado, #Acumulado de todos los DEBIT vinculados a la sesión como valor apostado
        SUM(CASE WHEN tjl.tipo = 'CREDIT' THEN (ROUND(COALESCE(CAST(tjl.valor AS DOUBLE), 0), 2)) ELSE 0 END) AS valorPremio #Acumulado de todos los CREDIT vinculados a la sesión como valor premio";

        $defaultPokerLogsCteSelect = "ROOT.sesionId AS sesionId,
        SUM(CASE WHEN ti.tipo = 'RAKE' THEN (COALESCE(CAST(ti.descripcion_txt AS DOUBLE), 0)) ELSE 0 END) AS rake, #Máximo valor del rake vinculado a la sesión
        SUM(CASE WHEN ti.tipo IN ('DEBITPOKERTORNEO', 'TORNEOBUYPOKER') THEN (ROUND(COALESCE(CAST(ti.valor AS DOUBLE), 0), 2)) ELSE 0 END) AS inscripcionTorneo #Debitos por ingreso en torneos para la sesión";


        /*Definición groupings por defecto para las consultas*/
        $defaultMonetarianGrouping = "GROUP BY ROOT.transjuegoId";

        $defaultPokerLogsGrouping = "GROUP BY ROOT.sesionId";


        /*Definición de la consulta principal*/
        $buildableReports = [
            1, //Reporte detallado de poker
            2 //Reporte agrupado por proveedor y usuario
        ];
        if (in_array($type, $buildableReports)) {
            /*Adecuaciones en ROOT (Select y externos)*/
            /*Externos vinculados a los productos de POKER --Esto permite distinguirlos de productos de casino estándar*/
            $collectableExternals = Producto::getCollectablePokerExternals();
            $collectableExternalsString = '"' . implode('","', $collectableExternals) . '"';

            $ROOTCTE = str_replace("#CollectableExternals#", "AND p.externo_id IN ($collectableExternalsString)", $ROOTCTE);
            $ROOTCTE = str_replace("#RootCteSelect#", $defaultRootCteSelect, $ROOTCTE);


            /*Adecuaciones en MONETARIAN (Select y grouping)*/
            $MONETARIANCTE = str_replace("#MonetarianCteSelect#", $defaultMonetarianCteSelect, $MONETARIANCTE);
            $MONETARIANCTE = str_replace("#MonetarianGrouping#", $defaultMonetarianGrouping, $MONETARIANCTE);


            /*Adecuaciones en POKERLOGS (Select y grouping)*/
            $POKERLOGSCTE = str_replace("#PokerLogsCteSelect#", $defaultPokerLogsCteSelect, $POKERLOGSCTE);
            $POKERLOGSCTE = str_replace("#PokerLogsGrouping#", $defaultPokerLogsGrouping, $POKERLOGSCTE);


            /*Adecuaciones a MAINQUERY se realizan en la función/servicio que llama a esta función*/

            /*Construccion consulta final*/
            $finalQuery = "WITH 
            $ROOTCTE \n,
            $MONETARIANCTE \n,
            $POKERLOGSCTE \n
            $MAINQUERY";


            return $finalQuery;
        }

        if ($type == 3) {
            /*Adecuaciones en ROOT (Select y externos)*/
            /*Externos vinculados a los productos de POKER --Esto permite distinguirlos de productos de casino estándar*/
            $collectableExternals = Producto::getCollectablePokerExternals();
            $collectableExternalsString = '"' . implode('","', $collectableExternals) . '"';

            $ROOTCTE = str_replace("#CollectableExternals#", "AND p.externo_id IN ($collectableExternalsString)", $ROOTCTE);
            $ROOTCTE = str_replace("#RootCteSelect#", $defaultRootCteSelect, $ROOTCTE);


            /*Adecuaciones en MONETARIAN (Select y grouping)*/
            $MONETARIANCTE = str_replace("#MonetarianCteSelect#", $defaultMonetarianCteSelect, $MONETARIANCTE);
            $MONETARIANCTE = str_replace("#MonetarianGrouping#", $defaultMonetarianGrouping, $MONETARIANCTE);


            /*Adecuaciones en POKERLOGS (Select y grouping)*/
            $pokerLogsCteSelectTypeThree = $defaultPokerLogsCteSelect . "\n ,MAX(CASE WHEN ti.tipo IN ('DEBITPOKERTORNEO', 'TORNEOBUYPOKER') THEN 1 ELSE NULL END) perteneceATorneo";
            $POKERLOGSCTE = str_replace("#PokerLogsCteSelect#", $pokerLogsCteSelectTypeThree, $POKERLOGSCTE);
            $POKERLOGSCTE = str_replace("#PokerLogsGrouping#", $defaultPokerLogsGrouping, $POKERLOGSCTE);


            /*Adecuaciones a MAINQUERY se realizan en la función/servicio que llama a esta función*/

            /*Construccion consulta final*/
            $finalQuery = "WITH 
            $ROOTCTE \n,
            $MONETARIANCTE \n,
            $POKERLOGSCTE \n
            $MAINQUERY";


            return $finalQuery;
        }


        return $finalQuery;
    }


    /** Obtiene el reporte de poker agrupado por cada sesión de juego
     *@param int $totalCount Variable de salida que contiene el total de registros obtenidos
     *@param array $filters Filtros aplicados a la consulta --Dentro de la función se especifica los filtros soportados por el reporte
     *@return array Array de objetos con la información del reporte.
     * Dentro de la función se ejemplifica la estructura de un objeto de respuesta
     */
    public function queryPokerReportBySessions(int &$totalCount, array $filters = []): array
    {
        /*EJEMPLO Definición de estructura para un elemento del array de respuesta*/
        $pokerSessionRowExample = (object)[
            'ProviderName' => "",
            'SubproviderName' => "",
            'UserId' => "",
            'User' => "",
            'IdSession' => "",
            'Date' => "",
            'AmountBet' => "",
            'AmountAward' => "",
            'Rake' => "",
            'TournamentInscription' => "",
            'Game' => "",
            'GGR' => "",
            'GGRPercentage' => "",
        ];

        /*EJEMPLO Definición estructura esperada en el $filters*/
        $filtersExampleCollection = [
            [
                "supportedFilter" => "valueToCompare"
            ]
        ];

        /*Obtención SQL base*/
        $pokerBySessionSql = $this->getRootSqlPokerReport(1);

        /*Definición filtros aceptados*/
        $supportedFilters = [
            "country",
            "partner",
            "datefrom",
            "dateto",
            "userid",
            "gametype",
            "supplierid",
            "subsupplierid",
            "start",
            "count"
        ];

        /*Iteración sobre los filtros especificados*/
        foreach ($filters as $supportedFilter => $valueToCompare) {
            /*Si el valor del item está vacío no se aplica filtro*/
            if ($valueToCompare == "" || $valueToCompare == null) continue;

            switch (strtolower($supportedFilter)) {
                case "country":
                    /*Corresponde al país del usuario*/
                    $countryStatement = " AND um.pais_id = $valueToCompare";
                    $pokerBySessionSql = str_replace("#Country#", $countryStatement, $pokerBySessionSql);
                    break;
                case "partner":
                    /*Corresponde al mandante del usuario*/
                    $partnerStatement = " AND um.mandante = $valueToCompare";
                    $pokerBySessionSql = str_replace("#Partner#", $partnerStatement, $pokerBySessionSql);
                    break;
                case "datefrom":
                    /*Fecha desde la que se creó la sesión*/
                    $dateFromStatement = " AND tj.fecha_crea >= '$valueToCompare'";
                    $pokerBySessionSql = str_replace("#DateFrom#", $dateFromStatement, $pokerBySessionSql);
                    break;
                case "dateto":
                    /*Fecha hasta la que se creó la sesión*/
                    $dateToStatement = " AND tj.fecha_crea <= '$valueToCompare'";
                    $pokerBySessionSql = str_replace("#DateTo#", $dateToStatement, $pokerBySessionSql);
                    break;
                case "userid":
                    /*Corresponde al usuario de casino*/
                    $userIdStatement = " AND um.usumandante_id = '$valueToCompare'";
                    $pokerBySessionSql = str_replace("#UserId#", $userIdStatement, $pokerBySessionSql);
                    break;
                case "gametype":
                    /*CashOut o Torneo*/
                    if ($valueToCompare == "TOURNAMENT") $gameTypeStatement = " AND (POKER_LOGS.inscripcionTorneo) > 0";
                    elseif ($valueToCompare == "CASHOUT") $gameTypeStatement = " AND COALESCE(POKER_LOGS.inscripcionTorneo, 0) = 0";
                    if (isset($gameTypeStatement)) {
                        $pokerBySessionSql = str_replace("#GameType#", $gameTypeStatement, $pokerBySessionSql);
                    }
                    break;
                case "supplierid":
                    /*Filtrado del proveedor*/
                    $supplierStatement = " AND p.proveedor_id = $valueToCompare";
                    $pokerBySessionSql = str_replace("#SupplierId#", $supplierStatement, $pokerBySessionSql);
                    break;
                case "subsupplierid":
                    /*Filtrado del subproveedor*/
                    $subsupplierStatement = " AND p.subproveedor_id = $valueToCompare";
                    $pokerBySessionSql = str_replace("#SubsupplierId#", $subsupplierStatement, $pokerBySessionSql);
                    break;
                case "start":
                    /*Almacenamiento de limit*/
                    if (is_numeric($valueToCompare)) $start = $valueToCompare;
                    break;
                case "count":
                    /*Almacenamiento del count*/
                    if (is_numeric($valueToCompare)) $count = $valueToCompare;
                    break;
            }
        }

        /*Obtención del count*/
        $pokerBySessionCountSql = $pokerBySessionSql;

        /*Definición de la cláusula LIMIT*/
        $limitStatement = " LIMIT $start, $count";
        $pokerBySessionSql = str_replace("#Limit#", $limitStatement, $pokerBySessionSql);

        /*Definición de la cláusula SELECT*/
        $mainSelect = "ROOT.transjuegoId,
        ROOT.sesionId,
        ROOT.fechaCrea,
        ROOT.usuarioCasino,
        ROOT.usuario,
        ROOT.proveedorId,
        ROOT.proveedor,
        ROOT.subproveedorId,
        ROOT.subproveedor,
        MONETARIAN_VALUES.valorApostado,
        MONETARIAN_VALUES.valorPremio,
        COALESCE(POKER_LOGS.inscripcionTorneo, 0) AS inscripcionTorneo,
        COALESCE(POKER_LOGS.rake, 0) AS rake";

        $countMainSelect = "COUNT(1) total";

        $pokerBySessionSql = str_replace("#MainSelect#", $mainSelect, $pokerBySessionSql);
        $pokerBySessionCountSql = str_replace("#MainSelect#", $countMainSelect, $pokerBySessionCountSql);

        /*Inicio procesos de consulta a base de datos*/
        $Transaction = new Transaction();
        $BonoInterno = new BonoInterno();

        /*Obtención del totalCount*/
        $sqlCountResponse = $BonoInterno->execQuery($Transaction, $pokerBySessionCountSql);
        $totalCount = $sqlCountResponse[0]->{".total"} ?? 0;

        /*Obtención de la información*/
        $sqlResponse = $BonoInterno->execQuery($Transaction, $pokerBySessionSql);

        /*Iteración y generación de los objetos de respuesta*/
        $stackSessions = [];
        foreach ($sqlResponse as $pokerSession) {
            $pokerSessionRow = [];


            $pokerSessionRow["ProviderName"] = (string) $pokerSession->{"ROOT.proveedor"};
            $pokerSessionRow["SubproviderName"] = (string) $pokerSession->{"ROOT.subproveedor"};
            $pokerSessionRow["UserId"] = (string) $pokerSession->{"ROOT.usuarioCasino"};
            $pokerSessionRow["User"] = (string) $pokerSession->{"ROOT.usuario"};
            $pokerSessionRow["IdSession"] = (string) $pokerSession->{"ROOT.sesionId"};
            $pokerSessionRow["Date"] = (string) $pokerSession->{"ROOT.fechaCrea"};
            $pokerSessionRow["AmountBet"] = (string) $pokerSession->{"MONETARIAN_VALUES.valorApostado"};
            $pokerSessionRow["AmountAward"] = (string) $pokerSession->{"MONETARIAN_VALUES.valorPremio"};
            $pokerSessionRow["Rake"] = (string) $pokerSession->{".rake"};
            $pokerSessionRow["TournamentInscription"] = (string) $pokerSession->{".inscripcionTorneo"};
            $pokerSessionRow["Game"] = (string) $pokerSession->{".inscripcionTorneo"} > 0 ? "TOURNAMENT" : "CASHOUT";
            $pokerSessionRow["GGR"] = (string) round($pokerSession->{"MONETARIAN_VALUES.valorApostado"} - $pokerSession->{"MONETARIAN_VALUES.valorPremio"}, 2, PHP_ROUND_HALF_DOWN);
            $pokerSessionRow["GGRPercentage"] = (string) (!empty($pokerSessionRow["AmountBet"]) ? (int) round(($pokerSessionRow["GGR"] * 100) / $pokerSessionRow["AmountBet"], 0, PHP_ROUND_HALF_DOWN) : 0);
            $pokerSessionRow["GGRPercentage"] .= " %";

            $stackSessions[] = $pokerSessionRow;
        }

        return $stackSessions;
    }


    /** Reporte de poker - Resumen de actividad agrupado por cada proveedor + usuario
     *@param int $totalCount Variable de salida que contiene el total de registros obtenidos
     *@param array $filters Filtros aplicados a la consulta
     * Dentro de la función se especifica los filtros soportados por el reporte
     * @return array Array de objetos con la información del reporte.
     */
    public function queryPokerReportBySupplierByUser(int &$totalCount, $filters = []): array
    {
        /*EJEMPLO Definición de estructura para un elemento del array de respuesta*/
        $pokerSessionRowExample = (object)[
            'ProviderName' => "",
            'SubproviderName' => "",
            'UserId' => "",
            'User' => "",
            'TotalAmountBet' => "",
            'TotalAmountAward' => "",
            'Rake' => "",
            'TotalTournamentInscription' => "",
            'GGR' => "",
            'GGRPercentage' => "",
        ];

        /*EJEMPLO Definición estructura esperada en el $filters*/
        $filtersExampleCollection = [
            [
                "supportedFilter" => "valueToCompare"
            ]
        ];

        /*Obtención SQL base*/
        $pokerBySessionSql = $this->getRootSqlPokerReport(2);

        /*Definición filtros aceptados*/
        $supportedFilters = [
            "country",
            "partner",
            "datefrom",
            "dateto",
            "userid",
            "supplierid",
            "subsupplierid",
            "start",
            "count"
        ];

        /*Iteración sobre los filtros especificados*/
        foreach ($filters as $supportedFilter => $valueToCompare) {
            /*Si el valor del item está vacío no se aplica filtro*/
            if ($valueToCompare == "" || $valueToCompare == null) continue;

            switch (strtolower($supportedFilter)) {
                case "country":
                    /*Corresponde al país del usuario*/
                    $countryStatement = " AND um.pais_id = $valueToCompare";
                    $pokerBySessionSql = str_replace("#Country#", $countryStatement, $pokerBySessionSql);
                    break;
                case "partner":
                    /*Corresponde al mandante del usuario*/
                    $partnerStatement = " AND um.mandante = $valueToCompare";
                    $pokerBySessionSql = str_replace("#Partner#", $partnerStatement, $pokerBySessionSql);
                    break;
                case "datefrom":
                    /*Fecha desde la que se creó la sesión*/
                    $dateFromStatement = " AND tj.fecha_crea >= '$valueToCompare'";
                    $pokerBySessionSql = str_replace("#DateFrom#", $dateFromStatement, $pokerBySessionSql);
                    break;
                case "dateto":
                    /*Fecha hasta la que se creó la sesión*/
                    $dateToStatement = " AND tj.fecha_crea <= '$valueToCompare'";
                    $pokerBySessionSql = str_replace("#DateTo#", $dateToStatement, $pokerBySessionSql);
                    break;
                case "userid":
                    /*Corresponde al usuario de casino*/
                    $userIdStatement = " AND um.usumandante_id = '$valueToCompare'";
                    $pokerBySessionSql = str_replace("#UserId#", $userIdStatement, $pokerBySessionSql);
                    break;
                case "supplierid":
                    /*Filtrado del proveedor*/
                    $supplierStatement = " AND p.proveedor_id = $valueToCompare";
                    $pokerBySessionSql = str_replace("#SupplierId#", $supplierStatement, $pokerBySessionSql);
                    break;
                case "start":
                    /*Almacenamiento de limit*/
                    if (is_numeric($valueToCompare)) $start = $valueToCompare;
                    break;
                case "count":
                    /*Almacenamiento del count*/
                    if (is_numeric($valueToCompare)) $count = $valueToCompare;
                    break;
            }
        }

        /*Definición de la cláusula GROUPING*/
        $mainGrouping = "GROUP BY ROOT.proveedorId, ROOT.usuario";
        $pokerBySessionSql = str_replace("#MainQueryGrouping#", $mainGrouping, $pokerBySessionSql);

        /*Obtención del count*/
        $pokerBySessionCountSql = $pokerBySessionSql;

        /*Definición de la cláusula SELECT*/
        $mainSelect = "ROOT.usuarioCasino,
        ROOT.usuario,
        ROOT.proveedorId,
        ROOT.proveedor,
        ROUND(SUM(MONETARIAN_VALUES.valorApostado), 2) AS valorApostado,
        ROUND(SUM(MONETARIAN_VALUES.valorPremio), 2) AS valorPremio,
        ROUND(SUM(COALESCE(POKER_LOGS.inscripcionTorneo, 0)), 2) AS inscripcionTorneo,
        ROUND(SUM(COALESCE(POKER_LOGS.rake, 0)), 2) AS rake";
        $pokerBySessionSql = str_replace("#MainSelect#", $mainSelect, $pokerBySessionSql);

        /*Definición de la cláusula LIMIT*/
        $limitStatement = " LIMIT $start, $count";
        $pokerBySessionSql = str_replace("#Limit#", $limitStatement, $pokerBySessionSql);

        /*Generación del count*/
        $countMainSelect = "1 total";
        $pokerBySessionCountSql = str_replace("#MainSelect#", $countMainSelect, $pokerBySessionCountSql);

        /*Obtención bloque vinculado a mainQuery*/
        $mainQueryPattern = "%-- MAINQUERY_BEGIN --(.*)-- MAINQUERY_END --%is";
        $mainQueryMatches = [];
        preg_match($mainQueryPattern, $pokerBySessionCountSql, $mainQueryMatches);
        $mainQueryBlock = $mainQueryMatches[1];
        $mainQueryBlock = "SELECT COUNT(1) total FROM (
        $mainQueryBlock
        ) AS COUNT_TABLE";

        /*Reemplazo con sentencia SQL del count*/
        $querySections = preg_split($mainQueryPattern, $pokerBySessionCountSql, 2);
        $pokerBySessionCountSql = $querySections[0] . "-- MAINQUERY_BEGIN --\n $mainQueryBlock \n-- MAINQUERY_END --" . $querySections[1];

        /*Inicio procesos de consulta a base de datos*/
        $Transaction = new Transaction();
        $BonoInterno = new BonoInterno();

        /*Obtención del totalCount*/
        $sqlCountResponse = $BonoInterno->execQuery($Transaction, $pokerBySessionCountSql);
        $totalCount = $sqlCountResponse[0]->{".total"} ?? 0;

        /*Obtención de la información*/
        $sqlResponse = $BonoInterno->execQuery($Transaction, $pokerBySessionSql);

        /*Iteración y generación de los objetos de respuesta*/
        $stackSessions = [];
        foreach ($sqlResponse as $pokerSession) {
            $pokerSessionRow = [];


            $pokerSessionRow["ProviderId"] = (string) $pokerSession->{"ROOT.proveedorId"};
            $pokerSessionRow["ProviderName"] = (string) $pokerSession->{"ROOT.proveedor"};
            $pokerSessionRow["SubproviderName"] = (string) $pokerSession->{"ROOT.proveedor"};
            $pokerSessionRow["UserId"] = (string) $pokerSession->{"ROOT.usuarioCasino"};
            $pokerSessionRow["User"] = (string) $pokerSession->{"ROOT.usuario"};
            $pokerSessionRow["TotalAmountBet"] = (string) $pokerSession->{".valorApostado"};
            $pokerSessionRow["TotalAmountAward"] = (string) $pokerSession->{".valorPremio"};
            $pokerSessionRow["Rake"] = (string) $pokerSession->{".rake"};
            $pokerSessionRow["TotalTournamentInscription"] = (string) $pokerSession->{".inscripcionTorneo"};
            $pokerSessionRow["GGR"] = (string) round($pokerSession->{".valorApostado"} - $pokerSession->{".valorPremio"}, 2, PHP_ROUND_HALF_DOWN);
            $pokerSessionRow["GGRPercentage"] = (string) (!empty($pokerSessionRow["TotalAmountBet"]) ? (int) round(($pokerSessionRow["GGR"] * 100) / $pokerSessionRow["TotalAmountBet"], 0, PHP_ROUND_HALF_DOWN) : 0);
            $pokerSessionRow["GGRPercentage"] .= " %";

            $stackSessions[] = $pokerSessionRow;
        }

        return $stackSessions;
    }


    /** Resumen de poker agrupado por proveedor + tipo de juego
     *@param int $totalCount Variable de salida que contiene el total de registros obtenidos
     *@param array $filters Filtros aplicados a la consulta
     * Dentro de la función se especifica los filtros soportados por el reporte
     * @return array Array de objetos con la información del reporte.
     */
    public function queryPokerReportBySupplierByGameType(int &$totalCount, $filters = []): array
    {
        /*EJEMPLO Definición de estructura para un elemento del array de respuesta*/
        $pokerSessionRowExample = (object)[
            'ProviderName' => "",
            'SubproviderName' => "",
            "GameType" => "",
            'TotalAmountBet' => "",
            'TotalAmountAward' => "",
            'Rake' => "",
            'TotalTournamentInscription' => "",
            'GGR' => "",
            'GGRPercentage' => "",
        ];

        /*EJEMPLO Definición estructura esperada en el $filters*/
        $filtersExampleCollection = [
            [
                "supportedFilter" => "valueToCompare"
            ]
        ];

        /*Obtención SQL base*/
        $pokerBySessionSql = $this->getRootSqlPokerReport(3);

        /*Definición filtros aceptados*/
        $supportedFilters = [
            "country",
            "partner",
            "datefrom",
            "dateto",
            "supplierid",
            "start",
            "count"
        ];

        /*Iteración sobre los filtros especificados*/
        foreach ($filters as $supportedFilter => $valueToCompare) {
            /*Si el valor del item está vacío no se aplica filtro*/
            if ($valueToCompare == "" || $valueToCompare == null) continue;

            switch (strtolower($supportedFilter)) {
                case "country":
                    /*Corresponde al país del usuario*/
                    $countryStatement = " AND um.pais_id = $valueToCompare";
                    $pokerBySessionSql = str_replace("#Country#", $countryStatement, $pokerBySessionSql);
                    break;
                case "partner":
                    /*Corresponde al mandante del usuario*/
                    $partnerStatement = " AND um.mandante = $valueToCompare";
                    $pokerBySessionSql = str_replace("#Partner#", $partnerStatement, $pokerBySessionSql);
                    break;
                case "datefrom":
                    /*Fecha desde la que se creó la sesión*/
                    $dateFromStatement = " AND tj.fecha_crea >= '$valueToCompare'";
                    $pokerBySessionSql = str_replace("#DateFrom#", $dateFromStatement, $pokerBySessionSql);
                    break;
                case "dateto":
                    /*Fecha hasta la que se creó la sesión*/
                    $dateToStatement = " AND tj.fecha_crea <= '$valueToCompare'";
                    $pokerBySessionSql = str_replace("#DateTo#", $dateToStatement, $pokerBySessionSql);
                    break;
                case "supplierid":
                    /*Filtrado del proveedor*/
                    $supplierStatement = " AND p.proveedor_id = $valueToCompare";
                    $pokerBySessionSql = str_replace("#SupplierId#", $supplierStatement, $pokerBySessionSql);
                    break;
                case "start":
                    /*Almacenamiento de limit*/
                    if (is_numeric($valueToCompare)) $start = $valueToCompare;
                    break;
                case "count":
                    /*Almacenamiento del count*/
                    if (is_numeric($valueToCompare)) $count = $valueToCompare;
                    break;
            }
        }

        /*Definición de la cláusula GROUPING*/
        $mainGrouping = "GROUP BY ROOT.proveedorId, POKER_LOGS.perteneceATorneo";
        $pokerBySessionSql = str_replace("#MainQueryGrouping#", $mainGrouping, $pokerBySessionSql);

        /*Obtención del count*/
        $pokerBySessionCountSql = $pokerBySessionSql;

        /*Definición de la cláusula SELECT*/
        $mainSelect = "ROOT.proveedorId,
        ROOT.proveedor,
        POKER_LOGS.inscripcionTorneo,
        ROUND(SUM(MONETARIAN_VALUES.valorApostado), 2) AS valorApostado,
        ROUND(SUM(MONETARIAN_VALUES.valorPremio), 2) AS valorPremio,
        ROUND(SUM(COALESCE(POKER_LOGS.inscripcionTorneo, 0)), 2) AS inscripcionTorneo,
        SUM(COALESCE(POKER_LOGS.rake, 0)) AS rake,
        COALESCE(perteneceATorneo, 0) AS perteneceATorneo,
        CASE WHEN POKER_LOGS.perteneceATorneo = 1 THEN 'Tournament'  ELSE 'CashOut' END AS tipoJuego";
        $pokerBySessionSql = str_replace("#MainSelect#", $mainSelect, $pokerBySessionSql);

        /*Generación del count*/
        $countMainSelect = "1 total";
        $pokerBySessionCountSql = str_replace("#MainSelect#", $countMainSelect, $pokerBySessionCountSql);

        /*Obtención bloque vinculado a mainQuery*/
        $mainQueryPattern = "%-- MAINQUERY_BEGIN --(.*)-- MAINQUERY_END --%is";
        $mainQueryMatches = [];
        preg_match($mainQueryPattern, $pokerBySessionCountSql, $mainQueryMatches);
        $mainQueryBlock = $mainQueryMatches[1];
        $mainQueryBlock = "SELECT COUNT(1) total FROM (
        $mainQueryBlock
        ) AS COUNT_TABLE";

        /*Reemplazo con sentencia SQL del count*/
        $querySections = preg_split($mainQueryPattern, $pokerBySessionCountSql, 2);
        $pokerBySessionCountSql = $querySections[0] . "-- MAINQUERY_BEGIN --\n $mainQueryBlock \n-- MAINQUERY_END --" . $querySections[1];

        /*Inicio procesos de consulta a base de datos*/
        $Transaction = new Transaction();
        $BonoInterno = new BonoInterno();

        /*Obtención del totalCount*/
        $sqlCountResponse = $BonoInterno->execQuery($Transaction, $pokerBySessionCountSql);
        $totalCount = $sqlCountResponse[0]->{".total"} ?? 0;

        /*Obtención de la información*/
        $sqlResponse = $BonoInterno->execQuery($Transaction, $pokerBySessionSql);

        /*Iteración y generación de los objetos de respuesta*/
        $stackSessions = [];
        foreach ($sqlResponse as $pokerSession) {
            $pokerSessionRow = [];


            $pokerSessionRow["ProviderId"] = (string) $pokerSession->{"ROOT.proveedorId"};
            $pokerSessionRow["ProviderName"] = (string) $pokerSession->{"ROOT.proveedor"};
            $pokerSessionRow["SubproviderName"] = (string) $pokerSession->{"ROOT.proveedor"};
            $pokerSessionRow["TotalAmountBet"] = (string) $pokerSession->{".valorApostado"};
            $pokerSessionRow["GameType"] = (string) ($pokerSession->{".tipoJuego"} == "Tournament" ? "Tournament" : "CashOut");
            $pokerSessionRow["TotalAmountAward"] = (string) $pokerSession->{".valorPremio"};
            $pokerSessionRow["Rake"] = (string) $pokerSession->{".rake"};
            $pokerSessionRow["TotalTournamentInscription"] = (string) $pokerSession->{".inscripcionTorneo"};
            $pokerSessionRow["GGR"] = (int) round($pokerSession->{".valorApostado"} - $pokerSession->{".valorPremio"}, 2, PHP_ROUND_HALF_DOWN);
            $pokerSessionRow["GGRPercentage"] = (int) (!empty($pokerSessionRow["TotalAmountBet"]) ? (int) round(($pokerSessionRow["GGR"] * 100) / $pokerSessionRow["TotalAmountBet"], 0, PHP_ROUND_HALF_DOWN) : 0);

            $stackSessions[] = $pokerSessionRow;
        }

        return $stackSessions;
    }


    /** ------------------------- @FuncionesReporteDePoker @FIN ----------------------------------------- */
}
