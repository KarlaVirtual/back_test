<?php namespace Backend\mysql;
use Backend\dao\ProveedorDAO;
use Backend\dto\Proveedor;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Exception;
/** 
* Clase 'ProveedorMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'Proveedor'
* 
* Ejemplo de uso: 
* $ProveedorMySqlDAO = new ProveedorMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class ProveedorMySqlDAO implements ProveedorDAO
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
        $sql = 'SELECT * FROM proveedor WHERE proveedor_id = ?';
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
        $sql = 'SELECT * FROM proveedor';
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
        $sql = 'SELECT * FROM proveedor ORDER BY ' . $orderColumn;
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }

    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $proveedor_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function delete($proveedor_id)
    {
        $sql = 'DELETE FROM proveedor WHERE proveedor_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($proveedor_id);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Insertar un registro en la base de datos
     *
     * @param Object proveedor proveedor
     *
     * @return String $id resultado de la consulta
     *
     */
    public function insert($proveedor)
    {
        $sql = 'INSERT INTO proveedor (descripcion, tipo, estado, verifica,abreviado, usucrea_id, usumodif_id,imagen) VALUES (?, ?, ?, ?, ?, ?, ?,?)';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->set($proveedor->descripcion);
        $sqlQuery->set($proveedor->tipo);
        $sqlQuery->set($proveedor->estado);
        $sqlQuery->set($proveedor->verifica);
        $sqlQuery->set($proveedor->abreviado);
        $sqlQuery->setNumber($proveedor->usucreaId);
        $sqlQuery->setNumber($proveedor->usumodifId);
        $sqlQuery->set($proveedor->imagen);

        $id = $this->executeInsert($sqlQuery);
        $proveedor->proveedorId = $id;
        return $id;
    }

    /**
     * Editar un registro en la base de datos
     *
     * @param Object proveedor proveedor
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function update($proveedor)
    {
        $sql = 'UPDATE proveedor SET descripcion = ?, tipo = ?, estado = ?, verifica = ?,abreviado = ?, usucrea_id = ?, usumodif_id = ?,imagen = ? WHERE proveedor_id = ?';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->set($proveedor->descripcion);
        $sqlQuery->set($proveedor->tipo);
        $sqlQuery->set($proveedor->estado);
        $sqlQuery->set($proveedor->verifica);
        $sqlQuery->set($proveedor->abreviado);
        $sqlQuery->setNumber($proveedor->usucreaId);
        $sqlQuery->setNumber($proveedor->usumodifId);
        $sqlQuery->set($proveedor->imagen);

        $sqlQuery->setNumber($proveedor->proveedorId);
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
        $sql = 'DELETE FROM proveedor';
        $sqlQuery = new SqlQuery($sql);
        return $this->executeUpdate($sqlQuery);
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
    public function queryByDescripcion($value)
    {
        $sql = 'SELECT * FROM proveedor WHERE descripcion = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna tipo sea igual al valor pasado como parámetro
     *
     * @param String $value tipo requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByTipo($value,$partner=0,$estadoProveedorMandante='',$estadoProveedor='')
    {
        $innerJoinProveedorMandante ='';
        $strestadoProveedorMandante = '';
        $where = '';

        if($estadoProveedorMandante != ''){
            $strestadoProveedorMandante=" AND proveedor_mandante.estado='A'  ";
        }

        if($estadoProveedor != ''){
            $where=" AND proveedor.estado='".$estadoProveedor."'  ";
        }

        if($partner !=  ''){
            $innerJoinProveedorMandante = ' INNER JOIN  proveedor_mandante ON  (proveedor_mandante.mandante IN ('.$partner.') AND  proveedor_mandante.proveedor_id = proveedor.proveedor_id '.$strestadoProveedorMandante.')  ';
        }

        $sql = 'SELECT * FROM proveedor ' . $innerJoinProveedorMandante .'  WHERE tipo = ?'.$where . ' GROUP BY proveedor.proveedor_id';
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
    public function queryByEstado($value)
    {
        $sql = 'SELECT * FROM proveedor WHERE estado = ?';
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
        $sql = 'SELECT * FROM proveedor WHERE verifica = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna abreviado sea igual al valor pasado como parámetro
     *
     * @param String $value abreviado requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByAbreviado($value)
    {
        $sql = 'SELECT * FROM proveedor WHERE abreviado = ?';
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
        $sql = 'SELECT * FROM proveedor WHERE fecha_crea = ?';
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
        $sql = 'SELECT * FROM proveedor WHERE fecha_modif = ?';
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
        $sql = 'SELECT * FROM proveedor WHERE usucrea_id = ?';
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
        $sql = 'SELECT * FROM proveedor WHERE usumodif_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);
    }






    /**
    * Realizar una consulta en la tabla de Proveedor 'Proveedor'
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
    public function getProductosTipo($value, $category, $provider, $offset, $limit, $search, $partnerId,$isMobile)
    {
        if($limit < 0){
            $limit=0;
        }

        $where = " 1=1 ";

        if ($category != "") {
            $where = $where . " AND categoria_producto.categoria_id= ? ";
        }

        if($isMobile != ""){
            $where = $where . " AND producto.mobile= 'S' ";
        }else{
            $where = $where . " AND producto.desktop= 'S' ";
        }

        if ($provider != "") {
            $where = $where . " AND proveedor.abreviado = ? ";
        }

        if ($search != "") {
            $where = $where . " AND producto.descripcion  LIKE '%" . $search . "%' ";
        }


        if($partnerId != ""){
            $where = $where . " AND proveedor_mandante.estado='A' ";

        }

        if ($offset == "" || $limit == "") {
            $limit = 15;
            $offset = 0;
        }


        $sql = 'SELECT proveedor.*,producto.*,producto_mandante.*, categoria_producto.*,categoria.*,producto_detalle.p_value background FROM producto 
        INNER JOIN proveedor ON (producto.proveedor_id = proveedor.proveedor_id) 
INNER JOIN categoria_producto ON (categoria_producto.producto_id = producto.producto_id) 
INNER JOIN producto_mandante ON (producto.producto_id = producto_mandante.producto_id AND producto_mandante.mandante = ' . $partnerId . ' ) 
INNER JOIN proveedor_mandante ON (proveedor_mandante.mandante = producto_mandante.mandante AND proveedor_mandante.proveedor_id = proveedor.proveedor_id) 

LEFT OUTER JOIN categoria ON (categoria.categoria_id =categoria_producto.categoria_id )
LEFT OUTER JOIN producto_detalle ON (producto_detalle.producto_id= producto.producto_id AND producto_detalle.p_key = "IMAGE_BACKGROUND")
 WHERE ' . $where . ' AND proveedor.Tipo = ? AND proveedor.estado="A" AND producto.estado="A"  AND producto_mandante.estado="A"  AND producto_mandante.estado="A" AND categoria_producto.estado="A" AND producto.mostrar="S"  GROUP BY producto.producto_id ORDER BY producto_mandante.orden,producto.descripcion  LIMIT ' . ($limit - $offset) . ' OFFSET ' . $offset;

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
     * Realizar una consulta personalizada en la tabla proveedor
     *
     * @param string $select campos de consulta
     * @param string $sidx columna para ordenar
     * @param string $sord orden de los datos asc | desc
     * @param int $start inicio de la consulta
     * @param int $limit límite de la consulta
     * @param string $filters condiciones de la consulta
     * @param boolean $searchOn utilizar los filtros o no
     *
     * @return string JSON con el conteo y los datos de la consulta
     */


    public function queryProveedoresCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        if($limit < 0){
            $limit=0;
        }

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


        $sql = 'SELECT count(*) count FROM proveedor ' . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT ' .$select .'  FROM proveedor ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }


    /**
     * Contar el número de productos de un tipo específico con varios filtros
     *
     * @param string $value tipo de proveedor requerido
     * @param string $category categoría del producto opcional
     * @param string $partnerId ID del socio opcional
     * @param string $isMobile indicador de si es para móvil opcional
     * @param string $provider abreviado del proveedor opcional
     *
     * @return int total de productos que cumplen con los filtros
     */

    public function countProductosTipo($value,$category,$partnerId,$isMobile,$provider)
    {



         $sql = 'SELECT count(*) total FROM producto
        INNER JOIN proveedor  ON (producto.proveedor_id = proveedor.proveedor_id)
INNER JOIN categoria_producto ON (categoria_producto.producto_id = producto.producto_id)
INNER JOIN producto_mandante ON (producto.producto_id = producto_mandante.producto_id AND producto_mandante.mandante = ' . $partnerId . ' ) LEFT OUTER JOIN categoria ON (categoria.categoria_id =categoria_producto.categoria_id )
LEFT OUTER JOIN producto_detalle ON (producto_detalle.producto_id= producto.producto_id AND producto_detalle.p_key = "IMAGE_BACKGROUND")
 WHERE proveedor.Tipo = ? AND proveedor.estado="A" AND producto.estado="A" AND producto.mostrar="S" ' ;

        if ($category != "") {
            $sql = $sql . " AND categoria_producto.categoria_id= '" .$category. "'";
        }



        if ($provider != "") {
            $sql = $sql . " AND proveedor.abreviado = '" .$provider. "'";
        }

        if($isMobile != ""){
            $sql = $sql .  " AND producto.mobile= 'S' ";
        }else{
            $sql = $sql .  " AND producto.desktop= 'S' ";
        }

        /*
        $sql = 'SELECT count(*) as total FROM proveedor LEFT OUTER JOIN producto ON (producto.proveedor_id = proveedor.proveedor_id) 
 WHERE proveedor.Tipo = ?'; */


        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->execute($sqlQuery)[0]['total'];
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
        $sql = 'DELETE FROM proveedor WHERE descripcion = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna tipo sea igual al valor pasado como parámetro
     *
     * @param String $value tipo requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByTipo($value)
    {
        $sql = 'DELETE FROM proveedor WHERE tipo = ?';
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
        $sql = 'DELETE FROM proveedor WHERE estado = ?';
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
        $sql = 'DELETE FROM proveedor WHERE verifica = ?';
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
        $sql = 'DELETE FROM proveedor WHERE fecha_crea = ?';
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
        $sql = 'DELETE FROM proveedor WHERE fecha_modif = ?';
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
        $sql = 'DELETE FROM proveedor WHERE usucrea_id = ?';
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
        $sql = 'DELETE FROM proveedor WHERE usumodif_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->executeUpdate($sqlQuery);
    }









    /**
     * Crear y devolver un objeto del tipo Proveedor
     * con los valores de una consulta sql
     * 
     *  
     * @param Arreglo $row arreglo asociativo
     *
     * @return Object $Proveedor Proveedor
     *
     * @access protected
     *
     */
    protected function readRow($row)
    {
        $proveedor = new Proveedor();

        $proveedor->proveedorId = $row['proveedor_id'];
        $proveedor->descripcion = $row['descripcion'];
        $proveedor->tipo = $row['tipo'];
        $proveedor->estado = $row['estado'];
        $proveedor->verifica = $row['verifica'];
        $proveedor->abreviado = $row['abreviado'];
        $proveedor->usucreaId = $row['usucrea_id'];
        $proveedor->usumodifId = $row['usumodif_id'];
        $proveedor->imagen = $row['imagen'];

        return $proveedor;
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
    protected function execute2($sqlQuery)
    {
        return QueryExecutor::execute2($this->transaction, $sqlQuery);
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