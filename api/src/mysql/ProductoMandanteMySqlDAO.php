<?php namespace Backend\mysql;
use Backend\dao\ProductoMandanteDAO;
use Backend\dto\ProductoMandante;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Exception;
use Backend\sql\ConnectionProperty;
use PDO;

/**
 * Clase 'ProductoMandanteMySqlDAO'
 *
 * Esta clase provee las consultas del modelo o tabla 'ProductoMandante'
 *
 * Ejemplo de uso:
 * $ProductoMandanteMySqlDAO = new ProductoMandanteMySqlDAO();
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class ProductoMandanteMySqlDAO implements ProductoMandanteDAO
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
        $sql = 'SELECT * FROM producto_mandante WHERE prodmandante_id = ?';
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
        $sql = 'SELECT * FROM producto_mandante';
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
        $sql = 'SELECT * FROM producto_mandante ORDER BY ' . $orderColumn;
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }

    public function dataByCountryMandante($mandante, $prodmandanteId, $country, $estado = '')
    {
        $sql = 'SELECT * FROM producto_mandante where prodmandante_id=? and mandante=? and pais_id=?';
        if ($estado != '') {
            $sql .= ' AND estado = ? ';
        }
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($prodmandanteId);
        $sqlQuery->set($mandante);
        $sqlQuery->set($country);
        if ($estado != '') {
            $sqlQuery->set($estado);
        }
        return $this->getList($sqlQuery);
    }


    /**
     * Realizar una consulta en la tabla de ProductoMandante 'ProductoMandante'
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
    public function queryProductosMandanteCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {
        /*$message=$_SERVER['HTTP_REFERER'];

        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php 'CONSULTA A PRODUCTOS ".$message."' '#virtualsoft-cron' > /dev/null & ");*/


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
                        $fieldOperation = " NOT IN (" . $fieldData . ")";
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
        if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ){
            $connOriginal = $_ENV["connectionGlobal"]->getConnection();

            $connDB5 = null;


            if($_ENV['ENV_TYPE'] =='prod') {

                $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()
                    , array(
                        PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-bundle.crt',
                        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
                    )
                );
            }else{

                $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()

                );
            }

            $connDB5->exec("set names utf8");
            $connDB5->exec("set use_secondary_engine=off");

            try{

                if($_ENV["TIMEZONE"] !=null && $_ENV["TIMEZONE"] !='') {
                    $connDB5->exec('SET time_zone = "'.$_ENV["TIMEZONE"].'";');
                }

                if($_ENV["NEEDINSOLATIONLEVEL"] != null && $_ENV["NEEDINSOLATIONLEVEL"] == '1') {
                    $connDB5->exec('SET TRANSACTION ISOLATION LEVEL READ COMMITTED;');
                }
                if($_ENV["ENABLEDSETLOCKWAITTIMEOUT"] != null && $_ENV["ENABLEDSETLOCKWAITTIMEOUT"] == '1') {
                    // $connDB5->exec('SET SESSION innodb_lock_wait_timeout = 5;');
                }
                if($_ENV["ENABLEDSETMAX_EXECUTION_TIME"] != null && $_ENV["ENABLEDSETMAX_EXECUTION_TIME"] == '1') {
                    // $connDB5->exec('SET SESSION MAX_EXECUTION_TIME = 120000;');
                }
                if($_ENV["DBNEEDUTF8"] != null && $_ENV["DBNEEDUTF8"] == '1') {
                    $connDB5->exec("SET NAMES utf8mb4");
                }
            }catch (\Exception $e){

            }
            $_ENV["connectionGlobal"]->setConnection($connDB5);
            $this->transaction->setConnection($_ENV["connectionGlobal"]);

        }



        $sql = 'SELECT count(*) count FROM producto_mandante INNER JOIN producto ON (producto_mandante.producto_id = producto.producto_id) INNER JOIN mandante ON (mandante.mandante = producto_mandante.mandante) INNER JOIN proveedor ON (producto.proveedor_id = proveedor.proveedor_id)   INNER JOIN  proveedor_mandante ON  (proveedor_mandante.mandante = producto_mandante.mandante AND  proveedor_mandante.proveedor_id = proveedor.proveedor_id )  INNER JOIN subproveedor ON (producto.subproveedor_id = subproveedor.subproveedor_id) LEFT JOIN etiqueta_producto ON (producto_mandante.producto_id = etiqueta_producto.producto_id AND etiqueta_producto.mandante = producto_mandante.mandante AND etiqueta_producto.pais_id = producto_mandante.pais_id AND etiqueta_producto.estado = "A") LEFT OUTER JOIN subproveedor_mandante_pais ON (producto.subproveedor_id = subproveedor_mandante_pais.subproveedor_id AND producto_mandante.pais_id = subproveedor_mandante_pais.pais_id AND producto_mandante.mandante = subproveedor_mandante_pais.mandante)' . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT ' .$select .'  FROM producto_mandante INNER JOIN producto ON (producto_mandante.producto_id = producto.producto_id) INNER JOIN mandante ON (mandante.mandante = producto_mandante.mandante)  INNER JOIN proveedor ON (producto.proveedor_id = proveedor.proveedor_id)   INNER JOIN  proveedor_mandante ON  (proveedor_mandante.mandante = producto_mandante.mandante AND  proveedor_mandante.proveedor_id = proveedor.proveedor_id )  INNER JOIN subproveedor ON (producto.subproveedor_id = subproveedor.subproveedor_id)     LEFT JOIN etiqueta_producto ON (producto_mandante.producto_id = etiqueta_producto.producto_id AND etiqueta_producto.mandante = producto_mandante.mandante AND etiqueta_producto.pais_id = producto_mandante.pais_id AND etiqueta_producto.estado = "A") LEFT OUTER JOIN subproveedor_mandante_pais ON (producto.subproveedor_id = subproveedor_mandante_pais.subproveedor_id AND producto_mandante.pais_id = subproveedor_mandante_pais.pais_id AND producto_mandante.mandante = subproveedor_mandante_pais.mandante)
   ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        if($_REQUEST['isDebug']=='1'){
            print_r($sql);
        }
        try{
            //syslog(LOG_WARNING, "SQLSQL :". $sql  );

        }catch (Exception $e){

        }

        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);





        if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ) {
            $connDB5 = null;
            $_ENV["connectionGlobal"]->setConnection($connOriginal);
            $this->transaction->setConnection($_ENV["connectionGlobal"]);
        }

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }



    /**
     * Realizar una consulta en la tabla de ProductoMandante 'ProductoMandante'
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
    public function queryProductosMandanteCustom2($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {
        /*$message=$_SERVER['HTTP_REFERER'];

        exec("php -f /home/home2/backend/api/src/imports/Slack/message.php 'CONSULTA A PRODUCTOS ".$message."' '#virtualsoft-cron' > /dev/null & ");*/


        $where = " where 1=1";


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
                        $fieldOperation = " NOT IN (" . $fieldData . ")";
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
        if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ){
            $connOriginal = $_ENV["connectionGlobal"]->getConnection();

            $connDB5 = null;


            if($_ENV['ENV_TYPE'] =='prod') {

                $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()
                    , array(
                        PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-bundle.crt',
                        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
                    )
                );
            }else{

                $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()

                );
            }

            $connDB5->exec("set names utf8");
            $connDB5->exec("set use_secondary_engine=off");

            try{

                if($_ENV["TIMEZONE"] !=null && $_ENV["TIMEZONE"] !='') {
                    $connDB5->exec('SET time_zone = "'.$_ENV["TIMEZONE"].'";');
                }

                if($_ENV["NEEDINSOLATIONLEVEL"] != null && $_ENV["NEEDINSOLATIONLEVEL"] == '1') {
                    $connDB5->exec('SET TRANSACTION ISOLATION LEVEL READ COMMITTED;');
                }
                if($_ENV["ENABLEDSETLOCKWAITTIMEOUT"] != null && $_ENV["ENABLEDSETLOCKWAITTIMEOUT"] == '1') {
                    // $connDB5->exec('SET SESSION innodb_lock_wait_timeout = 5;');
                }
                if($_ENV["ENABLEDSETMAX_EXECUTION_TIME"] != null && $_ENV["ENABLEDSETMAX_EXECUTION_TIME"] == '1') {
                    // $connDB5->exec('SET SESSION MAX_EXECUTION_TIME = 120000;');
                }
                if($_ENV["DBNEEDUTF8"] != null && $_ENV["DBNEEDUTF8"] == '1') {
                    $connDB5->exec("SET NAMES utf8mb4");
                }
            }catch (\Exception $e){

            }
            $_ENV["connectionGlobal"]->setConnection($connDB5);
            $this->transaction->setConnection($_ENV["connectionGlobal"]);

        }



        $sql = 'SELECT count(*) count FROM producto_mandante 
                      INNER JOIN producto ON (producto_mandante.producto_id = producto.producto_id) 
                        INNER JOIN proveedor ON (producto.proveedor_id = proveedor.proveedor_id) 
                        INNER JOIN subproveedor ON (producto.subproveedor_id = subproveedor.subproveedor_id)
                         INNER JOIN subproveedor_mandante on (subproveedor_mandante.mandante=producto_mandante.mandante AND subproveedor_mandante.subproveedor_id = producto.subproveedor_id) 
                        INNER JOIN proveedor_mandante on (proveedor_mandante.mandante=producto_mandante.mandante AND proveedor_mandante.proveedor_id = producto.proveedor_id) 
                        
                          ' . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT ' .$select .'  FROM producto_mandante INNER JOIN producto ON (producto_mandante.producto_id = producto.producto_id) INNER JOIN proveedor ON (producto.proveedor_id = proveedor.proveedor_id)
         INNER JOIN subproveedor ON (producto.subproveedor_id = subproveedor.subproveedor_id)
                         INNER JOIN subproveedor_mandante on (subproveedor_mandante.mandante=producto_mandante.mandante AND subproveedor_mandante.subproveedor_id = producto.subproveedor_id) 
                        INNER JOIN proveedor_mandante on (proveedor_mandante.mandante=producto_mandante.mandante AND proveedor_mandante.proveedor_id = producto.proveedor_id) 
        
   ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        if($_ENV["debugFixed2"] == '1'){
            print_r($sql);
        }
        try{
            //syslog(LOG_WARNING, "SQLSQL :". $sql  );

        }catch (Exception $e){

        }

        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);





        if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ) {
            $connDB5 = null;
            $_ENV["connectionGlobal"]->setConnection($connOriginal);
            $this->transaction->setConnection($_ENV["connectionGlobal"]);
        }

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }



    /**
     * Realizar una consulta en la tabla de ProductoMandante 'ProductoMandante'
     * de una manera personalizada con otras tablas importantes como prodmandantepais para el tema de los productos de un pais en especifico
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
    public function queryProductosMandantePaisCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$paisId=0)
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
                        $fieldOperation = " NOT IN (" . $fieldData . ")";
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

        if($where == ""){
            $where =" WHERE ((producto_mandante.filtro_pais = 'A' AND prodmandante_pais.pais_id = '" . $paisId . "' AND prodmandante_pais.estado='A') OR (producto_mandante.filtro_pais='I'))";
            //$where=" WHERE  producto_mandante.pais_id = '" . $paisId . "' ";
        }else{
            $where = $where." AND ((producto_mandante.filtro_pais = 'A' AND prodmandante_pais.pais_id = '" . $paisId . "' AND prodmandante_pais.estado='A') OR (producto_mandante.filtro_pais='I'))";
            //$where = $where." AND  producto_mandante.pais_id = '" . $paisId . "' ";

        }

        if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ){
            $connOriginal = $_ENV["connectionGlobal"]->getConnection();




            $connDB5 = null;

            if($_ENV['ENV_TYPE'] =='prod') {

                $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()
                    , array(
                        PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-bundle.crt',
                        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
                    )
                );
            }else{

                $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()

                );
            }

            $connDB5->exec("set names utf8");
            $connDB5->exec("set use_secondary_engine=off");

            try{

                if($_ENV["TIMEZONE"] !=null && $_ENV["TIMEZONE"] !='') {
                    $connDB5->exec('SET time_zone = "'.$_ENV["TIMEZONE"].'";');
                }

                if($_ENV["NEEDINSOLATIONLEVEL"] != null && $_ENV["NEEDINSOLATIONLEVEL"] == '1') {
                    $connDB5->exec('SET TRANSACTION ISOLATION LEVEL READ COMMITTED;');
                }
                if($_ENV["ENABLEDSETLOCKWAITTIMEOUT"] != null && $_ENV["ENABLEDSETLOCKWAITTIMEOUT"] == '1') {
                    // $connDB5->exec('SET SESSION innodb_lock_wait_timeout = 5;');
                }
                if($_ENV["ENABLEDSETMAX_EXECUTION_TIME"] != null && $_ENV["ENABLEDSETMAX_EXECUTION_TIME"] == '1') {
                    // $connDB5->exec('SET SESSION MAX_EXECUTION_TIME = 120000;');
                }
                if($_ENV["DBNEEDUTF8"] != null && $_ENV["DBNEEDUTF8"] == '1') {
                    $connDB5->exec("SET NAMES utf8mb4");
                }
            }catch (\Exception $e){

            }
            $_ENV["connectionGlobal"]->setConnection($connDB5);
            $this->transaction->setConnection($_ENV["connectionGlobal"]);

        }


        $sql = 'SELECT count(*) count FROM producto_mandante INNER JOIN producto ON (producto_mandante.producto_id = producto.producto_id) INNER JOIN proveedor ON (producto.proveedor_id = proveedor.proveedor_id) LEFT OUTER JOIN prodmandante_pais  ON (prodmandante_pais.producto_id = producto.producto_id AND prodmandante_pais.mandante = producto_mandante.mandante  )  ' . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT ' .$select .'  FROM producto_mandante INNER JOIN producto ON (producto_mandante.producto_id = producto.producto_id) INNER JOIN proveedor ON (producto.proveedor_id = proveedor.proveedor_id) LEFT OUTER JOIN prodmandante_pais  ON (prodmandante_pais.producto_id = producto.producto_id AND prodmandante_pais.mandante = producto_mandante.mandante  ) ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);

        if($_ENV["debugFixed2"] == '1'){
            print_r($sql);
        }
        $result = $this->execute2($sqlQuery);




        if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ) {
            $connDB5 = null;
            $_ENV["connectionGlobal"]->setConnection($connOriginal);
            $this->transaction->setConnection($_ENV["connectionGlobal"]);
        }


        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }

    /**
     * Realizar una consulta personalizada en la tabla producto\_mandante con filtro por país
     *
     * @param string $select campos de consulta
     * @param string $sidx columna para ordenar
     * @param string $sord orden de los datos asc | desc
     * @param int $start inicio de la consulta
     * @param int $limit límite de la consulta
     * @param string $filters condiciones de la consulta
     * @param boolean $searchOn utilizar los filtros o no
     * @param int $paisId ID del país para filtrar
     *
     * @return string JSON con el conteo y los datos de la consulta
     */

    public function queryProductosMandantePaisCustom2($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$paisId=0)
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
                        $fieldOperation = " NOT IN (" . $fieldData . ")";
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

        if($where == ""){
            // $where =" WHERE ((producto_mandante.filtro_pais = 'A' AND prodmandante_pais.pais_id = '" . $paisId . "' AND prodmandante_pais.estado='A') OR (producto_mandante.filtro_pais='I'))";
            $where=" WHERE  producto_mandante.pais_id = '" . $paisId . "' ";
        }else{
            //$where = $where." AND ((producto_mandante.filtro_pais = 'A' AND prodmandante_pais.pais_id = '" . $paisId . "' AND prodmandante_pais.estado='A') OR (producto_mandante.filtro_pais='I'))";
            $where = $where." AND  producto_mandante.pais_id = '" . $paisId . "' ";

        }

        if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ){
            $connOriginal = $_ENV["connectionGlobal"]->getConnection();




            $connDB5 = null;

            if($_ENV['ENV_TYPE'] =='prod') {

                $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()
                    , array(
                        PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-bundle.crt',
                        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
                    )
                );
            }else{

                $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()

                );
            }

            $connDB5->exec("set names utf8");
            $connDB5->exec("set use_secondary_engine=off");

            try{

                if($_ENV["TIMEZONE"] !=null && $_ENV["TIMEZONE"] !='') {
                    $connDB5->exec('SET time_zone = "'.$_ENV["TIMEZONE"].'";');
                }

                if($_ENV["NEEDINSOLATIONLEVEL"] != null && $_ENV["NEEDINSOLATIONLEVEL"] == '1') {
                    $connDB5->exec('SET TRANSACTION ISOLATION LEVEL READ COMMITTED;');
                }
                if($_ENV["ENABLEDSETLOCKWAITTIMEOUT"] != null && $_ENV["ENABLEDSETLOCKWAITTIMEOUT"] == '1') {
                    // $connDB5->exec('SET SESSION innodb_lock_wait_timeout = 5;');
                }
                if($_ENV["ENABLEDSETMAX_EXECUTION_TIME"] != null && $_ENV["ENABLEDSETMAX_EXECUTION_TIME"] == '1') {
                    // $connDB5->exec('SET SESSION MAX_EXECUTION_TIME = 120000;');
                }
                if($_ENV["DBNEEDUTF8"] != null && $_ENV["DBNEEDUTF8"] == '1') {
                    $connDB5->exec("SET NAMES utf8mb4");
                }
            }catch (\Exception $e){

            }
            $_ENV["connectionGlobal"]->setConnection($connDB5);
            $this->transaction->setConnection($_ENV["connectionGlobal"]);

        }



        $sql = 'SELECT count(*) count FROM producto_mandante INNER JOIN producto ON (producto_mandante.producto_id = producto.producto_id) INNER JOIN proveedor ON (producto.proveedor_id = proveedor.proveedor_id)  
     INNER JOIN subproveedor_mandante on (subproveedor_mandante.mandante=producto_mandante.mandante AND subproveedor_mandante.subproveedor_id = producto.subproveedor_id) 
     INNER JOIN proveedor_mandante on (proveedor_mandante.mandante=producto_mandante.mandante AND proveedor_mandante.proveedor_id = producto.proveedor_id) 
    ' . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT ' .$select .'  FROM producto_mandante INNER JOIN producto ON (producto_mandante.producto_id = producto.producto_id) INNER JOIN proveedor ON (producto.proveedor_id = proveedor.proveedor_id) 
             INNER JOIN subproveedor_mandante on (subproveedor_mandante.mandante=producto_mandante.mandante AND subproveedor_mandante.subproveedor_id = producto.subproveedor_id) 
     INNER JOIN proveedor_mandante on (proveedor_mandante.mandante=producto_mandante.mandante AND proveedor_mandante.proveedor_id = producto.proveedor_id) 

        ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);

        if($_ENV["debugFixed2"] == '1'){
            print_r($sql);
        }
        $result = $this->execute2($sqlQuery);



        if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ) {
            $connDB5 = null;
            $_ENV["connectionGlobal"]->setConnection($connOriginal);
            $this->transaction->setConnection($_ENV["connectionGlobal"]);
        }

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }




    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $prodmandante_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function delete($prodmandante_id)
    {
        $sql = 'DELETE FROM producto_mandante WHERE prodmandante_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($prodmandante_id);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Insertar un registro en la base de datos
     *
     * @param Object productoMandante productoMandante
     *
     * @return String $id resultado de la consulta
     *
     */
    public function insert($productoMandante)
    {

		$sql = 'INSERT INTO producto_mandante (producto_id, mandante, estado, verifica, filtro_pais, usucrea_id, usumodif_id, max, min, tiempo_procesamiento,orden,num_fila,num_columna,orden_destacado,pais_id,borde,habilitacion, codigo_minsetur,extra_info,valor) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?)';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($productoMandante->productoId);
        $sqlQuery->setNumber($productoMandante->mandante);
        $sqlQuery->set($productoMandante->estado);
        $sqlQuery->set($productoMandante->verifica);
        $sqlQuery->set($productoMandante->filtroPais);
        $sqlQuery->setNumber($productoMandante->usucreaId);
        $sqlQuery->setNumber($productoMandante->usumodifId);
        $sqlQuery->set($productoMandante->max);
        $sqlQuery->set($productoMandante->min);
        $sqlQuery->set($productoMandante->tiempoProcesamiento);
        $sqlQuery->set($productoMandante->orden);
        $sqlQuery->set($productoMandante->numFila);
        $sqlQuery->set($productoMandante->numColumna);
        $sqlQuery->set($productoMandante->ordenDestacado);
        $sqlQuery->set($productoMandante->paisId);
        $sqlQuery->set($productoMandante->borde);
        $sqlQuery->set($productoMandante->habilitacion);
        $sqlQuery->set($productoMandante->codigoMinsetur);
        $sqlQuery->set($productoMandante->extrainfo);
        $sqlQuery->set($productoMandante->valor);

        $id = $this->executeInsert($sqlQuery);
        $productoMandante->prodmandanteId = $id;
        return $id;
    }

    /**
     * Editar un registro en la base de datos
     *
     * @param Object productoMandante productoMandante
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function update($productoMandante)
    {

		$sql = 'UPDATE producto_mandante SET producto_id = ?, mandante = ?, estado = ?, verifica = ?, filtro_pais = ?, usucrea_id = ?, usumodif_id = ?, max = ?, min = ?, tiempo_procesamiento = ?,orden=?, num_fila=?, num_columna=?, orden_destacado=?,image_url = ?, image_url2 = ?, borde = ?, habilitacion = ?, codigo_minsetur = ?,extra_info=? ,valor=? WHERE prodmandante_id = ? ';

        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($productoMandante->productoId);
        $sqlQuery->setNumber($productoMandante->mandante);
        $sqlQuery->set($productoMandante->estado);
        $sqlQuery->set($productoMandante->verifica);
        $sqlQuery->set($productoMandante->filtroPais);
        $sqlQuery->setNumber($productoMandante->usucreaId);
        $sqlQuery->setNumber($productoMandante->usumodifId);
        $sqlQuery->set($productoMandante->max);
        $sqlQuery->set($productoMandante->min);
        $sqlQuery->set($productoMandante->tiempoProcesamiento);
        $sqlQuery->set($productoMandante->orden);
        $sqlQuery->set($productoMandante->numFila);
        $sqlQuery->set($productoMandante->numColumna);
        $sqlQuery->set($productoMandante->ordenDestacado);
        $sqlQuery->set($productoMandante->Image);
        $sqlQuery->set($productoMandante->ImageUrl2);
        $sqlQuery->set($productoMandante->borde);
        if(empty($productoMandante->habilitacion)) $productoMandante->habilitacion = 0;
        $sqlQuery->set($productoMandante->habilitacion);
        $sqlQuery->set($productoMandante->codigoMinsetur);
        $sqlQuery->set($productoMandante->extrainfo);
        $sqlQuery->set($productoMandante->valor);

        $sqlQuery->setNumber($productoMandante->prodmandanteId);
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
        $sql = 'DELETE FROM producto_mandante';
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
        $sql = 'SELECT * FROM producto_mandante WHERE producto_id = ?';
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
        $sql = 'SELECT * FROM producto_mandante WHERE mandante = ?';
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
    public function queryByProductoIdAndMandante($productoId, $value, $estado = '')
    {
        $sql = 'SELECT * FROM producto_mandante WHERE producto_id=? AND mandante = ?';
        if ($estado != '') {
            $sql .= ' AND estado = ? ';
        }
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($productoId);
        $sqlQuery->setNumber($value);
        if ($estado != '') {
            $sqlQuery->set($estado);
        }
        return $this->getList($sqlQuery);
    }

    public function queryByProductoIdAndMandanteAndPaisId($productoId, $value, $paisId, $estado = '')
    {
        $sql = 'SELECT * FROM producto_mandante WHERE producto_id=? AND mandante = ? AND pais_id = ?';
        if ($estado != '') {
            $sql .= ' AND estado = ? ';
        }
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($productoId);
        $sqlQuery->setNumber($value);
        $sqlQuery->setNumber($paisId);
        if ($estado != '') {
            $sqlQuery->set($estado);
        }
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
        $sql = 'SELECT * FROM producto_mandante WHERE estado = ?';
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
        $sql = 'SELECT * FROM producto_mandante WHERE verifica = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna filtro_pais sea igual al valor pasado como parámetro
     *
     * @param String $value filtro_pais requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByFiltroPais($value)
    {
        $sql = 'SELECT * FROM producto_mandante WHERE filtro_pais = ?';
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
        $sql = 'SELECT * FROM producto_mandante WHERE fecha_crea = ?';
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
        $sql = 'SELECT * FROM producto_mandante WHERE fecha_modif = ?';
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
        $sql = 'SELECT * FROM producto_mandante WHERE usucrea_id = ?';
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
        $sql = 'SELECT * FROM producto_mandante WHERE usumodif_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna max sea igual al valor pasado como parámetro
     *
     * @param String $value max requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByMax($value)
    {
        $sql = 'SELECT * FROM producto_mandante WHERE max = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna min sea igual al valor pasado como parámetro
     *
     * @param String $value min requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByMin($value)
    {
        $sql = 'SELECT * FROM producto_mandante WHERE min = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna tiempo_procesamiento sea igual al valor pasado como parámetro
     *
     * @param String $value tiempo_procesamiento requerido
     *
     * @return Array resultado de la consulta
     *
     */
    public function queryByTiempoProcesamiento($value)
    {
        $sql = 'SELECT * FROM producto_mandante WHERE tiempo_procesamiento = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
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
        $sql = 'DELETE FROM producto_mandante WHERE producto_id = ?';
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
        $sql = 'DELETE FROM producto_mandante WHERE mandante = ?';
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
        $sql = 'DELETE FROM producto_mandante WHERE estado = ?';
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
        $sql = 'DELETE FROM producto_mandante WHERE verifica = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna filtro_pais sea igual al valor pasado como parámetro
     *
     * @param String $value filtro_pais requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByFiltroPais($value)
    {
        $sql = 'DELETE FROM producto_mandante WHERE filtro_pais = ?';
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
        $sql = 'DELETE FROM producto_mandante WHERE fecha_crea = ?';
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
        $sql = 'DELETE FROM producto_mandante WHERE fecha_modif = ?';
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
        $sql = 'DELETE FROM producto_mandante WHERE usucrea_id = ?';
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
        $sql = 'DELETE FROM producto_mandante WHERE usumodif_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna max sea igual al valor pasado como parámetro
     *
     * @param String $value max requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByMax($value)
    {
        $sql = 'DELETE FROM producto_mandante WHERE max = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna min sea igual al valor pasado como parámetro
     *
     * @param String $value min requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByMin($value)
    {
        $sql = 'DELETE FROM producto_mandante WHERE min = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna tiempo_procesamiento sea igual al valor pasado como parámetro
     *
     * @param String $value tiempo_procesamiento requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
    public function deleteByTiempoProcesamiento($value)
    {
        $sql = 'DELETE FROM producto_mandante WHERE tiempo_procesamiento = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }






    /**
     * Crear y devolver un objeto del tipo ProductoMandante
     * con los valores de una consulta sql
     *
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Object $ProductoMandante ProductoMandante
     *
     * @access protected
     *
     */
    protected function readRow($row)
    {
        $productoMandante = new ProductoMandante();

        $productoMandante->prodmandanteId = $row['prodmandante_id'];
        $productoMandante->productoId = $row['producto_id'];
        $productoMandante->mandante = $row['mandante'];
        $productoMandante->estado = $row['estado'];
        $productoMandante->verifica = $row['verifica'];
        $productoMandante->filtroPais = $row['filtro_pais'];
        $productoMandante->fechaCrea = $row['fecha_crea'];
        $productoMandante->fechaModif = $row['fecha_modif'];
        $productoMandante->usucreaId = $row['usucrea_id'];
        $productoMandante->usumodifId = $row['usumodif_id'];
        $productoMandante->max = $row['max'];
        $productoMandante->min = $row['min'];
        $productoMandante->tiempoProcesamiento = $row['tiempo_procesamiento'];
        $productoMandante->orden = $row['orden'];
        $productoMandante->numFila = $row['num_fila'];
        $productoMandante->numColumna = $row['num_columna'];
        $productoMandante->ordenDestacado = $row['orden_destacado'];
        $productoMandante->paisId = $row['pais_id'];
        $productoMandante->Image = $row['image_url'];
        $productoMandante->ImageUrl2 = $row['image_url2'];
        $productoMandante->habilitacion = $row['habilitacion'];
        $productoMandante->codigoMinsetur = $row['codigo_minsetur'];
        $productoMandante->extrainfo = $row['extra_info'];
        $productoMandante->valor = $row['valor'];

        return $productoMandante;
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
