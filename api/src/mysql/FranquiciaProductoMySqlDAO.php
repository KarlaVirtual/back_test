<?php namespace Backend\mysql;
use Backend\dao\Franquicia_ProductoDAO;
use Backend\dao\FranquiciaProductoDAO;
use Backend\dto\Franquicia_Producto;
use Backend\dto\FranquiciaProducto;
use Backend\sql\QueryExecutor;
use Backend\sql\SqlQuery;
use Backend\sql\Transaction;
/**
 * Clase 'Franquicia_ProductoMySqlDAO'
 *
 * Esta clase provee las consultas del modelo o tabla 'Bano'
 *
 * Ejemplo de uso:
 * $Franquicia_ProductoMySqlDAO = new Franquicia_ProductoMySqlDAO();
 *
 *
 * @package ninguno
 * @author Juan Salazar <juan.salazar@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class FranquiciaProductoMySqlDAO implements FranquiciaProductoDAO {

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
    public function __construct($transaction="")
    {
        if ($transaction == "") {

            $transaction = new Transaction();
            $this->transaction = $transaction;

        } else {
            $this->transaction = $transaction;
        }
    }

    /**
     * Ejecutar una consulta sql
     *
     *
     * @param String $sqls consulta sql
     *
     * @return Array $ resultado de la ejecución
     *
     * @access protected
     *
     */
    public function querySQL($sql)
    {
        $sqlQuery = new SqlQuery($sql);
        return $this->execute2($sqlQuery);
    }



    /**
     * Obtener el registro condicionado por la
     * llave primaria que se pasa como parámetro
     *
     * @param String $id llave primaria
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function load($id){
        $sql = 'SELECT * FROM franquicia_producto WHERE franquiciaproducto_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($id);
//        $sqlQuery->set($estado);
        return $this->getRow($sqlQuery);
    }

    /**
     * Obtener todos los registros de la base datos
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryAll(){
        $sql = 'SELECT * FROM franquicia_producto';
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
    public function queryAllOrderBy($orderColumn){
        $sql = 'SELECT * FROM franquicia_producto ORDER BY '.$orderColumn;
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }

    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $Franquicia_Producto_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function delete($FranquiciaProducto){
        $sql = 'DELETE FROM franquicia_producto WHERE franquiciaproducto_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($FranquiciaProducto);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Insertar un registro en la base de datos
     *
     * @param Objeto Franquicia_Producto Franquicia_Producto
     *
     * @return String $id resultado de la consulta
     *
     */
    public function insert($FranquiciaProducto){
        $sql = 'INSERT INTO franquicia_producto (franquicia_id,producto_id,pais_id, mandante,estado,usucrea_id,usumodif_id, abreviado, imagen) VALUES (?,?,?,?,?,?,?,?,?)';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($FranquiciaProducto->franquiciaId);
        $sqlQuery->setNumber($FranquiciaProducto->productoId);
        $sqlQuery->setNumber($FranquiciaProducto->paisId);
        $sqlQuery->setNumber($FranquiciaProducto->mandante);
        $sqlQuery->set($FranquiciaProducto->estado);
        $sqlQuery->setNumber($FranquiciaProducto->usucreaId);
        $sqlQuery->setNumber($FranquiciaProducto->usumodifId);
        $sqlQuery->set($FranquiciaProducto->abreviado);
        $sqlQuery->set($FranquiciaProducto->imagen);

        $id = $this->executeInsert($sqlQuery);
        $FranquiciaProducto->franquiciaproducto_id = $id;
        return $id;
    }

    /**
     * Editar un registro en la base de datos
     *
     * @param Objeto Franquicia_Producto Franquicia_Producto
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function update($FranquiciaProducto){
        $sql = 'UPDATE franquicia_producto SET franquicia_id = ?,producto_id = ?,pais_id = ?, mandante = ?, estado = ?, usucrea_id = ?, usumodif_id = ?, abreviado = ?, imagen = ?  WHERE franquiciaproducto_id = ?';

        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($FranquiciaProducto->franquiciaId);
        $sqlQuery->setNumber($FranquiciaProducto->productoId);
        $sqlQuery->setNumber($FranquiciaProducto->paisId);
        $sqlQuery->setNumber($FranquiciaProducto->mandante);
        $sqlQuery->set($FranquiciaProducto->estado);
        $sqlQuery->setNumber($FranquiciaProducto->usucreaId);
        $sqlQuery->setNumber($FranquiciaProducto->usumodifId);
        $sqlQuery->set($FranquiciaProducto->abreviado);
        $sqlQuery->set($FranquiciaProducto->imagen);

        $sqlQuery->setNumber($FranquiciaProducto->franquiciaProductoId);
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
    public function clean(){
        $sql = 'DELETE FROM franquicia_producto';
        $sqlQuery = new SqlQuery($sql);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Realizar una consulta en la tabla de Franquicia_Productos 'Franquicia_Producto'
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
    public function queryFranquiciaProductosCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping="")
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
                    $where = $where . " " . $groupOperation . " UCASE(CAST(" . $fieldName . " AS CHAR)) " . strtoupper($fieldOperation);
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

        $sql = "SELECT count(*) count FROM franquicia_producto INNER JOIN pais ON (franquicia_producto.pais_id = pais.pais_id)   INNER JOIN producto ON (franquicia_producto.producto_id = producto.producto_id) INNER JOIN franquicia ON (franquicia_producto.franquicia_id = franquicia.franquicia_id)  INNER JOIN subproveedor ON (producto.subproveedor_id = subproveedor.subproveedor_id)  " . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
        $sql = "SELECT ".$select." FROM franquicia_producto INNER JOIN pais ON (franquicia_producto.pais_id = pais.pais_id) INNER JOIN producto ON (franquicia_producto.producto_id = producto.producto_id) INNER JOIN franquicia ON (franquicia_producto.franquicia_id = franquicia.franquicia_id)  INNER JOIN subproveedor ON (producto.subproveedor_id = subproveedor.subproveedor_id) LEFT OUTER JOIN " . $where." " .  " " . " order by " . $sidx . " " . $sord  . " LIMIT ". $start . " , " . $limit;

        if($_ENV["debugFixed2"] == '1'){
            print_r($sql);
        }
        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : '.json_encode($count). ', "data" : '.json_encode($result).'}';

        return  $json;
    }

    /**
     * Realizar una consulta en la tabla de Franquicia_Productos 'Franquicia_Producto'
     * de una manera personalizada con consultas en la tabla 'franquicia_mandante_pais' o 'Franquicia'
     *
     * @param String $select campos de consulta
     * @param String $sidx columna para ordenar
     * @param String $sord orden los datos asc | desc
     * @param String $start inicio de la consulta
     * @param String $limit limite de la consulta
     * @param String $filters condiciones de la consulta
     * @param boolean $searchOn utilizar los filtros o no
     * @param String $mandante para generar consulta por mandante
     * @param String $paisId para generar consulta por pais
     *
     * @return Array $json resultado de la consulta
     *
     */
    public function queryFranquiciasCustomMandante($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$mandante,$paisId='')
    {
        $where = " where 1=1 ";

        $withFranquiciaMandante=false;


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

                $cond="franquicia_mandante_pais";
                if ( strpos($fieldName, $cond) !== false || strpos($sidx, $cond) !== false  || strpos($select, $cond) !== false)
                {
                    $withFranquiciaMandante=true;
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

        $innerFranquicia_mandante='';

        if($withFranquiciaMandante){
            $strPaisId='';
            if($paisId != ''){
                $strPaisId=' AND franquicia_mandante_pais.pais_id = "'.$paisId.'" ';
            }
            $innerFranquicia_mandante = '  INNER JOIN franquicia_mandante_pais ON (franquicia_mandante_pais.franquicia_id = franquicia.franquicia_id AND franquicia_mandante_pais.mandante= "'.$mandante.'" '.$strPaisId.')  ';
        }
        else {
            $strPaisId='';
            if($paisId != ''){
                $strPaisId=' AND franquicia_producto.pais_id = "'.$paisId.'" ';
            }
            $innerFranquicia_mandante = '  INNER JOIN franquicia ON (franquicia_producto.franquicia_id = franquicia.franquicia_id AND franquicia_producto.mandante= "'.$mandante.'" '.$strPaisId.')  ';
        }


        $sql = 'SELECT count(*) count FROM franquicia_producto '.$innerFranquicia_mandante.'   ' . $where;


        $where=$where . " GROUP BY franquicia_producto.franquicia_id ";

        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $innerFranquicia_mandante='';

        if($withFranquiciaMandante){
            $strPaisId='';
            if($paisId != ''){
                $strPaisId=' AND franquicia_mandante_pais.pais_id = "'.$paisId.'" ';
            }
            $innerFranquicia_mandante = '  INNER JOIN franquicia_mandante_pais ON (franquicia_mandante_pais.franquicia_id = franquicia.franquicia_id AND franquicia_mandante_pais.mandante= "'.$mandante.'" '.$strPaisId.')  ';
        }
        else {
            $strPaisId='';
            if($paisId != ''){
                $strPaisId=' AND franquicia_producto.pais_id = "'.$paisId.'" ';
            }
            $innerFranquicia_mandante = '  INNER JOIN franquicia ON (franquicia_producto.franquicia_id = franquicia.franquicia_id AND franquicia_producto.mandante= "'.$mandante.'" '.$strPaisId.')  ';
        }

        $sql = 'SELECT ' .$select .'  FROM franquicia_producto  '.$innerFranquicia_mandante.'  ' . $where . "  order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;


        if($_ENV["debugFixed2"] == '1'){
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
     * Realizar una consulta en la tabla de Franquicia_Productos 'Franquicia_Producto'
     * de una manera personalizada con consultas en la tabla 'Franquicia_mandante' o 'Franquicia'
     *
     * @param String $select campos de consulta
     * @param String $sidx columna para ordenar
     * @param String $sord orden los datos asc | desc
     * @param String $start inicio de la consulta
     * @param String $limit limite de la consulta
     * @param String $filters condiciones de la consulta
     * @param boolean $searchOn utilizar los filtros o no
     * @param String $mandante para generar consulta por mandante
     * @param String $paisId para generar consulta por pais
     *
     * @return Array $json resultado de la consulta
     *
     */
    public function queryFranquiciasMandanteProductosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$mandante,$paisId='')
    {
        $where = " where 1=1   AND proveedor.tipo = 'PAYMENT' AND franquicia_mandante_pais.estado = 'A' AND franquicia.estado = 'A' " ;

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

        $sql = 'SELECT count(*) count 
FROM franquicia_producto 
INNER JOIN franquicia_mandante_pais ON franquicia_mandante_pais.franquicia_id = franquicia_producto.franquicia_id
    AND franquicia_producto.pais_id = franquicia_mandante_pais.pais_id
    AND franquicia_producto.mandante = franquicia_mandante_pais.mandante
    INNER JOIN franquicia ON franquicia.franquicia_id = franquicia_producto.franquicia_id
    INNER JOIN producto ON (franquicia_producto.producto_id = producto.producto_id)
    INNER JOIN mandante ON (franquicia_mandante_pais.mandante = mandante.mandante )
    INNER JOIN proveedor ON (producto.proveedor_id = proveedor.proveedor_id )
    INNER JOIN producto_mandante on (franquicia_producto.producto_id = producto_mandante.producto_id
            and franquicia_producto.mandante = producto_mandante.mandante and franquicia_producto.pais_id = producto_mandante.pais_id )' . $where;

//        $where=$where . " GROUP BY franquicia_producto.franquicia_id ";

        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);

        $sql = 'SELECT ' .$select .'  FROM franquicia_producto INNER JOIN franquicia_mandante_pais ON franquicia_mandante_pais.franquicia_id = franquicia_producto.franquicia_id AND franquicia_producto.pais_id = franquicia_mandante_pais.pais_id AND franquicia_producto.mandante = franquicia_mandante_pais.mandante INNER JOIN franquicia ON franquicia.franquicia_id = franquicia_producto.franquicia_id INNER JOIN producto ON (franquicia_producto.producto_id = producto.producto_id) INNER JOIN mandante ON (franquicia_mandante_pais.mandante = mandante.mandante ) INNER JOIN proveedor ON (producto.proveedor_id = proveedor.proveedor_id )INNER JOIN producto_mandante on (franquicia_producto.producto_id = producto_mandante.producto_id and franquicia_producto.mandante = producto_mandante.mandante and franquicia_producto.pais_id = producto_mandante.pais_id )  ' . $where . "  order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        if($_ENV["debugFixed2"] == '1'){
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
        $sql = 'SELECT * FROM franquicia_producto WHERE estado = ?';
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



    public function queryByFranquiciaProducto($FranquiciaProducto)
    {
        $sql = 'SELECT * FROM franquicia_producto WHERE franquicia_id = ? and producto_id = ? and mandante = ? and pais_id = ? ';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($FranquiciaProducto->franquiciaId);
        $sqlQuery->setNumber($FranquiciaProducto->productoId);
        $sqlQuery->setNumber($FranquiciaProducto->mandante);
        $sqlQuery->setNumber($FranquiciaProducto->paisId);
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
        $sql = 'SELECT * FROM franquicia_producto WHERE verifica = ?';
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
        $sql = 'SELECT * FROM franquicia_producto WHERE fecha_crea = ?';
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
        $sql = 'SELECT * FROM franquicia_producto WHERE fecha_modif = ?';
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
        $sql = 'SELECT * FROM franquicia_producto WHERE usucrea_id = ?';
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
        $sql = 'SELECT * FROM franquicia_producto WHERE usumodif_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);
    }


    /**
     * Obtener todos los registros donde se encuentre que
     * la columna Descripcion sea igual al valor pasado como parámetro
     *
     * @param String $value Descripcion requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByFranquiciaId($value){
        $sql = 'SELECT * FROM franquicia_producto WHERE franquicia_id = ?';
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
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByAbreviado($value){
        $sql = 'SELECT * FROM franquicia_producto WHERE abreviado = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna imagen sea igual al valor pasado como parámetro
     *
     * @param String $value imagen requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByImagen($value){
        $sql = 'SELECT * FROM franquicia_producto WHERE imagen = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna Descripcion sea igual al valor pasado como parámetro
     *
     * @param String $value Descripcion requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByFranquiciaId($value){
        $sql = 'DELETE FROM franquicia_producto WHERE franquicia_id = ?';
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
    public function deleteByMandante($value)
    {
        $sql = 'DELETE FROM franquicia_producto WHERE mandante = ?';
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
        $sql = 'DELETE FROM franquicia_producto WHERE estado = ?';
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
        $sql = 'DELETE FROM franquicia_producto WHERE fecha_crea = ?';
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
        $sql = 'DELETE FROM franquicia_producto WHERE fecha_modif = ?';
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
        $sql = 'DELETE FROM franquicia_producto WHERE usucrea_id = ?';
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
        $sql = 'DELETE FROM franquicia_producto WHERE usumodif_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna abreviado sea igual al valor pasado como parámetro
     *
     * @param String $value abreviado requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByAbreviado($value)
    {
        $sql = 'DELETE FROM franquicia_producto WHERE abreviado = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna imagen sea igual al valor pasado como parámetro
     *
     * @param String $value imagen requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByImagen($value)
    {
        $sql = 'DELETE FROM franquicia_producto WHERE imagen = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->executeUpdate($sqlQuery);
    }



    /**
     * Crear y devolver un objeto del tipo Franquicia_Producto
     * con los valores de una consulta sql
     *
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Objeto $FranquiciaProducto Franquicia_Producto
     *
     * @access protected
     *
     */
    protected function readRow($row){
        $FranquiciaProducto = new FranquiciaProducto();

        $FranquiciaProducto->franquiciaProductoId = $row['franquiciaproducto_id'];
        $FranquiciaProducto->franquiciaId = $row['franquicia_id'];
        $FranquiciaProducto->productoId = $row['producto_id'];
        $FranquiciaProducto->paisId = $row['pais_id'];
        $FranquiciaProducto->mandante = $row['mandante'];
        $FranquiciaProducto->estado = $row['estado'];
        $FranquiciaProducto->fechaCrea = $row['fecha_crea'];
        $FranquiciaProducto->fechaModif = $row['fecha_modif'];
        $FranquiciaProducto->usucreaId = $row['usucrea_id'];
        $FranquiciaProducto->usumodifId = $row['usumodif_id'];
        $FranquiciaProducto->abreviado = $row['abreviado'];
        $FranquiciaProducto->imagen = $row['imagen'];

        return $FranquiciaProducto;
    }

    /**
     * Ejecutar una consulta sql y devolver los datos
     * como un arreglo asociativo
     *
     *
     * @param String $sqlQuery consulta sql
     *
     * @return Array $ret arreglo asociativo
     *
     * @access protected
     *
     */
    protected function getList($sqlQuery){
        $tab = QueryExecutor::execute($this->transaction,$sqlQuery);
        $ret = array();
        for($i=0;$i<oldCount($tab);$i++){
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
    protected function getRow($sqlQuery){
        $tab = QueryExecutor::execute($this->transaction,$sqlQuery);
        if(oldCount($tab)==0){
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
    protected function execute($sqlQuery){
        return QueryExecutor::execute($this->transaction,$sqlQuery);
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
    protected function executeUpdate($sqlQuery){
        return QueryExecutor::executeUpdate($this->transaction,$sqlQuery);
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
    protected function querySingleResult($sqlQuery){
        return QueryExecutor::queryForString($this->transaction,$sqlQuery);
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
    protected function executeInsert($sqlQuery){
        return QueryExecutor::executeInsert($this->transaction,$sqlQuery);
    }
}
?>