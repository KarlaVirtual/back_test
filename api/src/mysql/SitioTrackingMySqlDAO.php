<?php namespace Backend\mysql;
use Backend\dao\SitioTrackingDAO;
use Backend\dto\Helpers;
use Backend\dto\SitioTracking;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Exception;
/** 
* Clase 'SitioTrackingMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'SitioTracking'
* 
* Ejemplo de uso: 
* $SitioTrackingMySqlDAO = new SitioTrackingMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class SitioTrackingMySqlDAO implements SitioTrackingDAO
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
        $sql = 'SELECT * FROM sitio_tracking WHERE sitiotracking_id = ?';
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
        $sql = 'SELECT * FROM sitio_tracking';
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
        $sql = 'SELECT * FROM sitio_tracking ORDER BY ' . $orderColumn;
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }

    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $sitiotracking_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function delete($sitiotracking_id)
    {
        $sql = 'DELETE FROM sitio_tracking WHERE sitiotracking_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($sitiotracking_id);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Insertar un registro en la base de datos
     *
     * @param Object sitio_tracking sitio_tracking
     *
     * @return String $id resultado de la consulta
     *
     */
    public function insert($sitioTracking)
    {
        $sql = 'INSERT INTO sitio_tracking (tabla, tipo, tabla_id, tvalue, usucrea_id, usumodif_id,value_ind) VALUES (?, ?, ?, ?, ?, ?, ?)';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->set($sitioTracking->tabla);
        $sqlQuery->set($sitioTracking->tipo);
        $sqlQuery->set($sitioTracking->tablaId);
        $sqlQuery->set($sitioTracking->tvalue);
        $sqlQuery->setNumber($sitioTracking->usucreaId);
        $sqlQuery->setNumber($sitioTracking->usumodifId);
        $sqlQuery->set($sitioTracking->valueInd);

        $id = $this->executeInsert($sqlQuery);
        $sitioTracking->sitiotrackingId = $id;
        return $id;
    }

    /**
     * Editar un registro en la base de datos
     *
     * @param Object sitio_tracking sitio_tracking
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function update($sitioTracking)
    {
        $sql = 'UPDATE sitio_tracking SET tabla = ?, tipo = ?, tabla_id = ?, tvalue = ?, usucrea_id = ?, usumodif_id = ? WHERE sitiotracking_id = ?';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->set($sitioTracking->tabla);
        $sqlQuery->set($sitioTracking->tipo);
        $sqlQuery->set($sitioTracking->tablaId);
        $sqlQuery->set($sitioTracking->tvalue);
        $sqlQuery->setNumber($sitioTracking->usucreaId);
        $sqlQuery->setNumber($sitioTracking->usumodifId);

        $sqlQuery->setNumber($sitioTracking->sitiotrackingId);
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
        $sql = 'DELETE FROM sitio_tracking';
        $sqlQuery = new SqlQuery($sql);
        return $this->executeUpdate($sqlQuery);
    }







    /**
     * Obtener todos los registros donde se encuentre que
     * la columna tabla sea igual al valor pasado como parámetro
     *
     * @param String $value tabla requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByDescripcion($value)
    {
        $sql = 'SELECT * FROM sitio_tracking WHERE tabla = ?';
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
    public function queryByTipo($value)
    {
        $sql = 'SELECT * FROM sitio_tracking WHERE tipo = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }


    /**
     * Obtener todos los registros donde se encuentre que
     * la columna tvalue sea igual al valor pasado como parámetro
     *
     * @param String $value tvalue requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByVerifica($value)
    {
        $sql = 'SELECT * FROM sitio_tracking WHERE tvalue = ?';
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
        $sql = 'SELECT * FROM sitio_tracking WHERE fecha_crea = ?';
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
        $sql = 'SELECT * FROM sitio_tracking WHERE fecha_modif = ?';
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
        $sql = 'SELECT * FROM sitio_tracking WHERE usucrea_id = ?';
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
        $sql = 'SELECT * FROM sitio_tracking WHERE usumodif_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);
    }






    /**
    * Realizar una consulta en la tabla de SitioTracking 'SitioTracking'
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
        $where = " 1=1 ";

        if ($category != "") {
            $where = $where . " AND categoria_producto.categoria_id= ? ";
        }

        if($isMobile != ""){
            $where = $where . " AND producto.mobile= 'S' ";
        }else{
            $where = $where . " AND producto.desktop= 'S' ";
        }

        if ($search != "") {
            $where = $where . " AND producto.tabla  LIKE '%" . $search . "%' ";
        }

        if ($offset == "" || $limit == "") {
            $limit = 15;
            $offset = 0;
        }


        $sql = 'SELECT sitio_tracking.*,producto.*,producto_mandante.*, categoria_producto.*,categoria.*,producto_detalle.p_value background FROM producto 
        INNER JOIN sitio_tracking ON (producto.sitiotracking_id = sitio_tracking.sitiotracking_id) 
INNER JOIN categoria_producto ON (categoria_producto.producto_id = producto.producto_id) 
INNER JOIN producto_mandante ON (producto.producto_id = producto_mandante.producto_id AND producto_mandante.mandante = ' . $partnerId . ' ) LEFT OUTER JOIN categoria ON (categoria.categoria_id =categoria_producto.categoria_id )
LEFT OUTER JOIN producto_detalle ON (producto_detalle.producto_id= producto.producto_id AND producto_detalle.p_key = "IMAGE_BACKGROUND")
 WHERE ' . $where . ' AND sitio_tracking.Tipo = ? AND sitio_tracking.tablaId="A" AND producto.tablaId="A"  AND producto_mandante.tablaId="A" AND producto.mostrar="S"  GROUP BY producto.producto_id ORDER BY producto_mandante.orden,producto.tabla  LIMIT ' . ($limit - $offset) . ' OFFSET ' . $offset;


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
     * Consulta personalizada de seguimiento de sitios.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Campo por el cual se ordenará la consulta.
     * @param string $sord Orden de la consulta (ASC o DESC).
     * @param int $start Índice de inicio para la paginación.
     * @param int $limit Límite de registros para la paginación.
     * @param string $filters Filtros en formato JSON para la consulta.
     * @param bool $searchOn Indica si se deben aplicar los filtros.
     * @param bool $joinUsuarios Indica si se debe hacer un join con la tabla de usuarios.
     * @return string JSON con el conteo de registros y los datos resultantes de la consulta.
     */
    public function querySitioTrackingesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$joinUsuarios = false)
    {
        $joinClause = $joinUsuarios ? " INNER JOIN usuario ON sitio_tracking.tabla_id =  usuario.usuario_id " : "";


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
                

                $Helpers = new Helpers();

                if($fieldName == 'registro.cedula'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_DOCUMENT']);
                }
                if($fieldName == 'registro.celular'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_PHONE']);
                }
                if($fieldName == 'usuario.cedula'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_DOCUMENT']);
                }
                if($fieldName == 'usuario.login'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }
                if($fieldName == 'usuario_mandante.email'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }
                if($fieldName == 'punto_venta.email'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }
                if($fieldName == 'usuario_sitebuilder.login'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
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


        $sql = 'SELECT count(*) count FROM sitio_tracking ' . $joinClause . $where;



        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);

        $sql = 'SELECT ' . $select . ' FROM sitio_tracking ' . $joinClause . $where . " ORDER BY " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);

        $result = $Helpers->process_data($this->execute2($sqlQuery));

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }


    /**
     * Cuenta el número de productos de un tipo específico, filtrando por categoría, socio, dispositivo móvil y proveedor.
     *
     * @param string $value El tipo de producto a contar.
     * @param string $category La categoría del producto (opcional).
     * @param int $partnerId El ID del socio.
     * @param bool $isMobile Indica si se debe filtrar por productos móviles.
     * @param string $provider El proveedor del producto (opcional).
     * @return int El número total de productos que coinciden con los criterios especificados.
     */
    public function countProductosTipo($value,$category,$partnerId,$isMobile,$provider)
    {



         $sql = 'SELECT count(*) total FROM producto
        INNER JOIN sitio_tracking  ON (producto.sitiotracking_id = sitio_tracking.sitiotracking_id)
INNER JOIN categoria_producto ON (categoria_producto.producto_id = producto.producto_id)
INNER JOIN producto_mandante ON (producto.producto_id = producto_mandante.producto_id AND producto_mandante.mandante = ' . $partnerId . ' ) LEFT OUTER JOIN categoria ON (categoria.categoria_id =categoria_producto.categoria_id )
LEFT OUTER JOIN producto_detalle ON (producto_detalle.producto_id= producto.producto_id AND producto_detalle.p_key = "IMAGE_BACKGROUND")
 WHERE sitio_tracking.Tipo = ? AND sitio_tracking.tablaId="A" AND producto.tablaId="A" AND producto.mostrar="S" ' ;

        if ($category != "") {
            $sql = $sql . " AND categoria_producto.categoria_id= '" .$category. "'";
        }



        if ($provider != "") {
            $sql = $sql . " AND sitio_tracking.abreviado = '" .$provider. "'";
        }

        if($isMobile != ""){
            $sql = $sql .  " AND producto.mobile= 'S' ";
        }else{
            $sql = $sql .  " AND producto.desktop= 'S' ";
        }

        /*
        $sql = 'SELECT count(*) as total FROM sitio_tracking LEFT OUTER JOIN producto ON (producto.sitiotracking_id = sitio_tracking.sitiotracking_id) 
 WHERE sitio_tracking.Tipo = ?'; */


        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->execute($sqlQuery)[0]['total'];
    }









    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna tabla sea igual al valor pasado como parámetro
     *
     * @param String $value tabla requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByDescripcion($value)
    {
        $sql = 'DELETE FROM sitio_tracking WHERE tabla = ?';
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
        $sql = 'DELETE FROM sitio_tracking WHERE tipo = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }


    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna tvalue sea igual al valor pasado como parámetro
     *
     * @param String $value tvalue requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByVerifica($value)
    {
        $sql = 'DELETE FROM sitio_tracking WHERE tvalue = ?';
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
        $sql = 'DELETE FROM sitio_tracking WHERE fecha_crea = ?';
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
        $sql = 'DELETE FROM sitio_tracking WHERE fecha_modif = ?';
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
        $sql = 'DELETE FROM sitio_tracking WHERE usucrea_id = ?';
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
        $sql = 'DELETE FROM sitio_tracking WHERE usumodif_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->executeUpdate($sqlQuery);
    }









    /**
     * Crear y devolver un objeto del tipo SitioTracking
     * con los valores de una consulta sql
     * 
     *  
     * @param Arreglo $row arreglo asociativo
     *
     * @return Object $SitioTracking SitioTracking
     *
     * @access protected
     *
     */
    protected function readRow($row)
    {
        $sitioTracking = new SitioTracking();

        $sitioTracking->sitiotrackingId = $row['sitiotracking_id'];
        $sitioTracking->tabla = $row['tabla'];
        $sitioTracking->tipo = $row['tipo'];
        $sitioTracking->tablaId = $row['tabla_id'];
        $sitioTracking->tvalue = $row['tvalue'];
        $sitioTracking->usucreaId = $row['usucrea_id'];
        $sitioTracking->usumodifId = $row['usumodif_id'];
        $sitioTracking->imagen = $row['imagen'];

        return $sitioTracking;
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
