<?php namespace Backend\mysql;
use Backend\dao\SubproveedorDAO;
use Backend\dto\Helpers;
use Backend\dto\Subproveedor;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Exception;
use Backend\sql\ConnectionProperty;

use PDO;
/** 
* Clase 'SubproveedorMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'Subproveedor'
* 
* Ejemplo de uso: 
* $SubproveedorMySqlDAO = new SubproveedorMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class SubproveedorMySqlDAO implements SubproveedorDAO
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
        $sql = 'SELECT * FROM subproveedor WHERE subproveedor_id = ?';
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
        $sql = 'SELECT * FROM subproveedor';
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
        $sql = 'SELECT * FROM subproveedor ORDER BY ' . $orderColumn;
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }

    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $subproveedor_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function delete($subproveedor_id)
    {
        $sql = 'DELETE FROM subproveedor WHERE subproveedor_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($subproveedor_id);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Insertar un registro en la base de datos
     *
     * @param Object subproveedor subproveedor
     *
     * @return String $id resultado de la consulta
     *
     */
    public function insert($subproveedor)
    {
        $sql = 'INSERT INTO subproveedor (descripcion, tipo, estado, verifica,abreviado, usucrea_id, usumodif_id,imagen,orden,proveedor_id,credentials) VALUES (?, ?, ?, ?, ?, ?, ?, ?,?,?,?)';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->set($subproveedor->descripcion);
        $sqlQuery->set($subproveedor->tipo);
        $sqlQuery->set($subproveedor->estado);
        $sqlQuery->set($subproveedor->verifica);
        $sqlQuery->set($subproveedor->abreviado);
        $sqlQuery->setNumber($subproveedor->usucreaId);
        $sqlQuery->setNumber($subproveedor->usumodifId);
        $sqlQuery->set($subproveedor->image);
        if($subproveedor->orden == ""){
            $sqlQuery->set(0);
        }else{
            $sqlQuery->set($subproveedor->orden);
        }
        $sqlQuery->setNumber($subproveedor->proveedorId);
        $sqlQuery->set( !empty($subproveedor->credentials) ? $subproveedor->credentials : '{}');
        $id = $this->executeInsert($sqlQuery);


        $subproveedor->subproveedorId = $id;
        return $id;
    }

    /**
     * Editar un registro en la base de datos
     *
     * @param Object subproveedor subproveedor
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function update($subproveedor)
    {
        $sql = 'UPDATE subproveedor SET descripcion = ?, tipo = ?, estado = ?, verifica = ?,abreviado = ?, usucrea_id = ?, usumodif_id = ?,imagen = ?,orden=?, proveedor_id = ? WHERE subproveedor_id = ?';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->set($subproveedor->descripcion);
        $sqlQuery->set($subproveedor->tipo);
        $sqlQuery->set($subproveedor->estado);
        $sqlQuery->set($subproveedor->verifica);
        $sqlQuery->set($subproveedor->abreviado);
        $sqlQuery->setNumber($subproveedor->usucreaId);
        $sqlQuery->setNumber($subproveedor->usumodifId);
        $sqlQuery->set($subproveedor->image);
        if($subproveedor->orden == ""){
            $sqlQuery->set(0);
        }else{
            $sqlQuery->set($subproveedor->orden);
        }
        $sqlQuery->setNumber($subproveedor->proveedorId);

        $sqlQuery->setNumber($subproveedor->subproveedorId);
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
        $sql = 'DELETE FROM subproveedor';
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
        $sql = 'SELECT * FROM subproveedor WHERE descripcion = ?';
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
    public function queryByTipo($value,$partner=0,$estadoSubproveedorMandante='',$estadoSubproveedor='',$paisId='')
    {
        $innerJoinSubproveedorMandante ='';
        $strestadoSubproveedorMandante = '';
        $where = '';


        if($estadoSubproveedorMandante != ''){
            $strestadoSubproveedorMandante=" AND subproveedor_mandante.estado='A'  ";
        }

        if($estadoSubproveedor != ''){
            $where=" AND subproveedor.estado='".$estadoSubproveedor."'  ";
        }

        if($partner !=  ''){
            $innerJoinSubproveedorMandante = ' INNER JOIN  proveedor ON  (  subproveedor.proveedor_id = proveedor.proveedor_id )  INNER JOIN  proveedor_mandante ON  (proveedor_mandante.mandante IN ( '.$partner.') AND  proveedor_mandante.proveedor_id = proveedor.proveedor_id )    INNER JOIN  subproveedor_mandante ON  (subproveedor_mandante.mandante IN ( '.$partner.') AND  subproveedor_mandante.subproveedor_id = subproveedor.subproveedor_id '.$strestadoSubproveedorMandante.')  ';
        }

        if($paisId != ''){
            $innerJoinSubproveedorMandante.= ' INNER JOIN  subproveedor_mandante_pais ON  (subproveedor_mandante_pais.mandante IN ( '.$partner.') AND subproveedor_mandante_pais.pais_id IN ( '.$paisId.')  AND  subproveedor_mandante_pais.subproveedor_id = subproveedor.subproveedor_id '.$strestadoSubproveedorMandante.')  ';
            $where.=" AND proveedor_mandante.estado='A' ";
            $where.=" AND subproveedor_mandante.estado='A' ";
            $where.=" AND subproveedor_mandante_pais.estado='A' ";
            $where.=" AND proveedor.estado='A' ";

        }else{
            $innerJoinSubproveedorMandante.= ' INNER JOIN  subproveedor_mandante_pais ON  (subproveedor_mandante_pais.mandante IN ( '.$partner.')   AND  subproveedor_mandante_pais.subproveedor_id = subproveedor.subproveedor_id '.$strestadoSubproveedorMandante.')  ';
            $where.=" AND proveedor_mandante.estado='A' ";
            $where.=" AND subproveedor_mandante.estado='A' ";
            $where.=" AND subproveedor_mandante_pais.estado='A' ";

        }

        $sql = 'SELECT subproveedor.subproveedor_id,subproveedor.descripcion,subproveedor.tipo,subproveedor.estado,subproveedor.verifica,subproveedor.abreviado,subproveedor.abreviado,subproveedor.imagen,subproveedor.proveedor_id,subproveedor_mandante_pais.* FROM subproveedor ' . $innerJoinSubproveedorMandante .'  WHERE subproveedor.tipo = ?'.$where . ' ORDER BY subproveedor_mandante_pais.orden,subproveedor.descripcion ASC ';


        if($paisId != ''){

        }
        if($partner ==  '34'){

            print_r($sql);
        }
        if($_REQUEST['test'] ==  '1'){

            print_r($sql);
        }

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
        $sql = 'SELECT * FROM subproveedor WHERE estado = ?';
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
        $sql = 'SELECT * FROM subproveedor WHERE verifica = ?';
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
        $sql = 'SELECT * FROM subproveedor WHERE abreviado = ?';
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
        $sql = 'SELECT * FROM subproveedor WHERE fecha_crea = ?';
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
        $sql = 'SELECT * FROM subproveedor WHERE fecha_modif = ?';
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
        $sql = 'SELECT * FROM subproveedor WHERE usucrea_id = ?';
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
        $sql = 'SELECT * FROM subproveedor WHERE usumodif_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);
    }






    /**
    * Realizar una consulta en la tabla de Subproveedor 'Subproveedor'
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

        if ($provider != "") {
            $where = $where . " AND subproveedor.abreviado = ? ";
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

        if(($limit - $offset) < 0){
            $limit = $offset;
        }

        if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null){
            $connOriginal = $_ENV["connectionGlobal"];




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


        $sql = 'SELECT producto_mandante.prodmandante_id,producto.externo_id,producto.producto_id,categoria.categoria_id,categoria.descripcion,categoria.estado,
        producto.descripcion,producto.image_url,producto.image_url2,proveedor.proveedor_id,proveedor.descripcion,proveedor.abreviado,producto.estado,producto_mandante.num_fila,producto_mandante.num_columna,producto_detalle.p_value background FROM producto 
        INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id) 
        INNER JOIN subproveedor ON (producto.subproveedor_id = subproveedor.subproveedor_id) 
INNER JOIN categoria_producto ON (categoria_producto.producto_id = producto.producto_id  AND categoria_producto.mandante = "-1") 
INNER JOIN producto_mandante  ON (producto.producto_id = producto_mandante.producto_id AND producto_mandante.mandante = ' . $partnerId . ' ) 
INNER JOIN proveedor_mandante  ON (proveedor_mandante.mandante = producto_mandante.mandante AND proveedor_mandante.proveedor_id = subproveedor.proveedor_id) 

LEFT OUTER JOIN categoria ON (categoria.categoria_id =categoria_producto.categoria_id )
LEFT OUTER JOIN producto_detalle ON (producto_detalle.producto_id= producto.producto_id AND producto_detalle.p_key = "IMAGE_BACKGROUND")
 WHERE ' . $where . ' AND subproveedor.Tipo = ? AND proveedor.estado="A"  AND subproveedor.estado="A" AND producto.estado="A"  AND producto_mandante.estado="A"  AND producto_mandante.estado="A" AND categoria_producto.estado="A" AND producto.mostrar="S"  GROUP BY producto.producto_id ORDER BY producto_mandante.orden,producto.descripcion  LIMIT ' . ($limit - $offset) . ' OFFSET ' . $offset;


        $sqlQuery = new SqlQuery($sql);
        if ($category != "") {
            $sqlQuery->setNumber($category);
        }

        if ($provider != "") {
            $sqlQuery->set($provider);
        }
        $sqlQuery->set($value);

        $respuesta=$this->execute2($sqlQuery);
        if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ) {
            $connDB5 = null;
            $_ENV["connectionGlobal"]->setConnection($connOriginal->getConnection());
            $this->transaction->setConnection($connOriginal);
        }

        return $respuesta;
    }


    /**
     * Obtiene productos por tipo de mandante.
     *
     * @param string $value Valor de búsqueda.
     * @param string $category Categoría del producto.
     * @param string $provider Proveedor del producto.
     * @param int $offset Desplazamiento para la paginación.
     * @param int $limit Límite de resultados.
     * @param string $search Término de búsqueda.
     * @param int $partnerId ID del socio.
     * @param string $isMobile Indica si es una búsqueda móvil.
     * @param string $CountrySelect Selección de país.
     * @param int|null $userId ID del usuario (opcional).
     * @param bool &$getCount Indica si se debe obtener el conteo de resultados.
     * @return array Resultados de la consulta.
     */
    public function getProductosTipoMandante($value, $category, $provider, $offset, $limit, $search, $partnerId,$isMobile,$CountrySelect='', $userId = null, &$getCount = false)
    {
        $where = " 1=1 ";

        if ($category != "") {
            $where = $where . " AND categoria_producto.categoria_id= ? ";
            $where = $where . "  AND categoria_producto.estado='A' ";
        }

        if($isMobile != ""){
            $where = $where . " AND producto.mobile= 'S' ";
        }else{
            $where = $where . " AND producto.desktop= 'S' ";
        }

        if ($provider != "") {
            $where = $where . " AND subproveedor.abreviado = ? ";
        }

        if ($search != "") {
            $where = $where . " AND producto.descripcion  LIKE '%" . $search . "%' ";
        }


        if($partnerId != ""){
            $where = $where . " AND proveedor_mandante.estado='A' ";
            
        }

        //Se suministra el usumandante_id ($userId) a la función cuando se requiere sólo los juegos favoritos del usuario
        $innerJoinUsuarioFavorito = "";
        if (!empty($userId)) {
            $innerJoinUsuarioFavorito = ' INNER JOIN usuario_favorito on (usuario_favorito.producto_id = producto_mandante.prodmandante_id) ';
            $where .= ' AND usuario_favorito.usuario_id = ' . $userId . ' AND usuario_favorito.estado = "A"';
        }

        if($limit < 0){
            $limit = "0";
        }

        if ($offset == "" || $limit == "") {
            $limit = 15;
            $offset = 0;
        }
        if(($limit - $offset) < 0){
            $limit = $offset;
        }

        $orden ="categoria_producto.orden";

        if($category == "" || $category == "0"){
            $orden ="producto_mandante.orden";
        }

        $PartnerCategoriaProducto = "";



        $joinPais='';
        $joinPais2='';

        if($CountrySelect != ''){
            $joinPais = " AND producto_mandante.pais_id='".$CountrySelect."' ";
        }
        if ($category != "") {
            $PartnerCategoriaProducto = 'AND categoria_producto.mandante = "' . $partnerId . '"';
                    if($CountrySelect != '') {
                        $joinPais2 = " AND categoria_producto.pais_id='" . $CountrySelect . "' ";
                    }
        }

        $sql = 'SELECT  /*+ MAX_EXECUTION_TIME(50000) */ producto_mandante.prodmandante_id,producto.externo_id,producto.producto_id,categoria_mandante.catmandante_id,categoria_mandante.descripcion,categoria_mandante.estado,
        producto.descripcion,producto.image_url,producto.image_url2,proveedor.proveedor_id,proveedor.descripcion,proveedor.abreviado,producto.estado,producto_mandante.num_fila,producto_mandante.num_columna,producto_detalle.p_value background,proveedor.abreviado,subproveedor.abreviado,subproveedor.imagen,
	subproveedor_mandante_pais.imagen FROM producto 
        INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id) 
        INNER JOIN subproveedor ON (producto.subproveedor_id = subproveedor.subproveedor_id) 
INNER JOIN producto_mandante ON (producto.producto_id = producto_mandante.producto_id AND producto_mandante.mandante = ' . $partnerId . ' '.$joinPais.'  ) ' . $innerJoinUsuarioFavorito . '
INNER JOIN proveedor_mandante ON (proveedor_mandante.mandante = producto_mandante.mandante AND proveedor_mandante.proveedor_id = subproveedor.proveedor_id) 
INNER JOIN subproveedor_mandante ON (subproveedor_mandante.mandante = producto_mandante.mandante AND subproveedor_mandante.subproveedor_id = subproveedor.subproveedor_id) 
LEFT OUTER JOIN categoria_producto ON (categoria_producto.producto_id = producto.producto_id AND categoria_producto.mandante=producto_mandante.mandante '.$PartnerCategoriaProducto.' '.$joinPais2.') 

LEFT OUTER JOIN categoria_mandante ON (categoria_mandante.catmandante_id =categoria_producto.categoria_id )
LEFT OUTER JOIN producto_detalle ON (producto_detalle.producto_id= producto.producto_id AND producto_detalle.p_key = "IMAGE_BACKGROUND") INNER JOIN subproveedor_mandante_pais ON (subproveedor.subproveedor_id = subproveedor_mandante_pais.subproveedor_id)
 WHERE ' . $where . ' AND subproveedor.tipo in (?) AND proveedor.estado="A"  AND subproveedor.estado="A" AND producto.estado="A"  AND producto_mandante.estado="A"  AND subproveedor_mandante.estado="A"   AND proveedor_mandante.estado="A"   AND producto_mandante.estado="A"  AND producto.mostrar="S"  GROUP BY producto.producto_id ORDER BY '.$orden.',producto.descripcion  LIMIT ' . ($limit - $offset) . ' OFFSET ' . $offset;

        if($provider == "PLAYNGO"){
        }

        if($partnerId == "13"){
        }

        if($_ENV["debugFixed2"] == '1'){
            print_r($sql);
            print_r($category);
            print_r($value);
        }
        $sqlQuery = new SqlQuery($sql);
        if ($category != "") {
            $sqlQuery->setNumber($category);
        }

        if ($provider != "") {
            $sqlQuery->set($provider);
        }

        if ($value != '') {
            $sqlQuery->setSIN("'" . $value . "'");
        }
        elseif ($value == '') {
            $defaultTypes = ['LIVECASINO', 'CASINO', 'VIRTUAL'];
            $defaultTypes = array_reduce($defaultTypes, function ($carry, $type) {
                return $carry != '' ? $carry .= ",'" . $type . "'" : $carry.= "'" . $type . "'";
            }, '');

            $sqlQuery->setSIN($defaultTypes);
        }


        if($getCount == true) {
            $countQuery = $sqlQuery->getQuery();

            /** Identificadno partes de la SQL */
            $regexCoincidence = [];

            //Recuperando última columna agrupada  en la consulta
            $groupByPattern = '/GROUP BY(\w|\.|\s|,)*(,|\s){1}(?<lastGroupBy>\w+\.{1}\w+){1}\s+ORDER/';
            preg_match($groupByPattern, $countQuery, $regexCoincidence);
            $countColumn = $regexCoincidence['lastGroupBy'];

            //Removiendo order by y contenido posterior
            $orderByPattern = '/(?<order>ORDER BY.*(\n)*(\r)*.*){1}/';
            $countQuery = preg_replace($orderByPattern, '', $countQuery);

            //Removiendo y reemplazando select
            $preSelectPattern = '/(?<preselect>SELECT(\s)+\/\*\+\sMAX.*\*\/(\s)*){1}/';
            $postSelectPattern = '/(?<postselect>FROM(.*(\n)*(\r)*)+){1}/';

            preg_match($preSelectPattern, $countQuery, $regexCoincidence);
            $preSelect = $regexCoincidence['preselect'];

            preg_match($postSelectPattern, $countQuery, $regexCoincidence);
            $postSelect = $regexCoincidence['postselect'];

            $countQuery = "SELECT COUNT(query.register)  AS count FROM (" .$preSelect ." " .$countColumn . " AS register " .$postSelect .") as query";

            //Solicitando registros totales
            $sqlQueryCount = new sqlQuery($countQuery);
            $countResponse = $this->execute2($sqlQueryCount);
            $countResponse = (object) ($countResponse[0]);
            $getCount = $countResponse->{'.count'};
            unset($sqlQueryCount);
        }


        if($_ENV["debugFixed2"] == '1'){
            print_r($sql);
            print_r($category);
            print_r($value);
        }
        return $this->execute2($sqlQuery);
    }


    /**
     * Obtiene productos según varios criterios de búsqueda.
     *
     * @param string $value Valor del tipo de mandante.
     * @param string $category ID de la categoría del producto.
     * @param string $provider ID del proveedor.
     * @param string $subprovider ID del subproveedor.
     * @param int $offset Desplazamiento para la paginación.
     * @param int $limit Límite de resultados para la paginación.
     * @param string $search Término de búsqueda para la descripción del producto.
     * @param string $partnerId ID del socio.
     * @param string $isMobile Indica si es una búsqueda para dispositivos móviles.
     * @param string $name Nombre del producto.
     * @param string $CountrySelect ID del país seleccionado (opcional).
     * @return array Lista de productos que coinciden con los criterios de búsqueda.
     */
    public function getProductosTipoMandante2($value, $category, $provider, $subprovider, $offset, $limit, $search, $partnerId,$isMobile, $name, $CountrySelect='')
    {
        $where = ' 1 = 1';

        if ($category != '') $where = $where . ' AND categoria_producto.categoria_id= ? ';

        if($isMobile != '') $where = $where . " AND producto.mobile= 'S' ";
        else $where = $where . " AND producto.desktop= 'S' ";

        if ($provider != '') $where = $where . " AND proveedor.proveedor_id = ? ";

        if(!empty($subprovider)) $where .= " AND subproveedor.subproveedor_id = {$subprovider}";

        if(!empty($name)) $where .= " AND producto.descripcion = '{$name}'";

        if ($search != '')  $where = $where . " AND producto.descripcion  LIKE '%" . $search . "%' ";

        if($partnerId != '') $where = $where . " AND proveedor_mandante.estado='A' ";

        if ($offset == '' || $limit == '') {
            $limit = 15;
            $offset = 0;
        }

        $orden ="categoria_producto.orden";
        if($category == '') $orden ="producto_mandante.orden";

        $PartnerCategoriaProducto = '';

        if ($category != '') $PartnerCategoriaProducto = 'AND categoria_producto.mandante = "'.$partnerId.'"';

        $joinPais = '';
        $joinPais2 = '';

        if($CountrySelect != ''){
            $joinPais = " AND producto_mandante.pais_id='".$CountrySelect."' ";
            $joinPais2 = " AND categoria_producto.pais_id='".$CountrySelect."' ";
        }

        $sql = 'SELECT producto_mandante.prodmandante_id,producto.externo_id,producto.borde,producto.producto_id,categoria_mandante.catmandante_id,categoria_mandante.descripcion,categoria_mandante.estado,
        producto.descripcion,producto.image_url,proveedor.proveedor_id,proveedor.descripcion,proveedor.abreviado,producto.estado,producto_mandante.num_fila,producto_mandante.num_columna,producto_detalle.p_value background,proveedor.abreviado,subproveedor.abreviado,producto.borde,producto_mandante.orden FROM producto 
        INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id) 
        INNER JOIN subproveedor ON (producto.subproveedor_id = subproveedor.subproveedor_id) 
        INNER JOIN producto_mandante ON (producto.producto_id = producto_mandante.producto_id AND producto_mandante.mandante = ' . $partnerId . ' '.$joinPais.'  )
        INNER JOIN proveedor_mandante ON (proveedor_mandante.mandante = producto_mandante.mandante AND proveedor_mandante.proveedor_id = subproveedor.proveedor_id) 
        INNER JOIN subproveedor_mandante ON (subproveedor_mandante.mandante = producto_mandante.mandante AND subproveedor_mandante.subproveedor_id = subproveedor.subproveedor_id) 
        INNER JOIN categoria_producto ON (categoria_producto.producto_id = producto.producto_id AND categoria_producto.mandante=producto_mandante.mandante '.$PartnerCategoriaProducto.' '.$joinPais2.') 

        LEFT OUTER JOIN categoria_mandante ON (categoria_mandante.catmandante_id =categoria_producto.categoria_id )
        LEFT OUTER JOIN producto_detalle ON (producto_detalle.producto_id= producto.producto_id AND producto_detalle.p_key = "IMAGE_BACKGROUND")
        WHERE ' . $where . ' AND subproveedor.tipo = ? AND proveedor.estado="A"  AND subproveedor.estado="A" AND producto.estado="A"  AND producto_mandante.estado="A"  AND subproveedor_mandante.estado="A"   AND proveedor_mandante.estado="A"   AND producto_mandante.estado="A" AND categoria_producto.estado="A" AND producto.mostrar="S"  GROUP BY producto.producto_id ORDER BY '.$orden.',producto.descripcion  LIMIT ' . ($limit - $offset) . ' OFFSET ' . $offset;

        $sqlQuery = new SqlQuery($sql);
        if ($category != '') $sqlQuery->setNumber($category);

        if ($provider != '') $sqlQuery->set($provider);

        $sqlQuery->set($value);
        return $this->execute2($sqlQuery);
    }


    /**
     * Realiza una consulta personalizada de subproveedores con filtros y ordenamiento.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Campo por el cual se ordenará la consulta.
     * @param string $sord Orden de la consulta (ASC o DESC).
     * @param int $start Índice de inicio para la paginación.
     * @param int $limit Límite de registros a obtener.
     * @param string $filters Filtros en formato JSON para aplicar en la consulta.
     * @param bool $searchOn Indica si se deben aplicar los filtros.
     * @param string $mandanteSelect Filtro adicional para el mandante.
     * @return string JSON con el conteo de registros y los datos obtenidos.
     */
    public function querySubproveedoresCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$mandanteSelect="")
    {


        if($limit < 0){
            $limit = "0";
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


        $sqlMandante ='';

        if($mandanteSelect != ''){
            $sqlMandante= " INNER JOIN proveedor_mandante on (proveedor_mandante.mandante=".$mandanteSelect." AND proveedor_mandante.proveedor_id = proveedor.proveedor_id) ";
        }

        $sql = 'SELECT count(*) count FROM subproveedor INNER JOIN proveedor ON (proveedor.proveedor_id = subproveedor.proveedor_id) '.$sqlMandante . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT ' .$select .'  FROM subproveedor INNER JOIN proveedor ON (proveedor.proveedor_id = subproveedor.proveedor_id)  '.$sqlMandante . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }


    /**
     * Cuenta el número de productos de un tipo específico.
     *
     * @param string $value El tipo de subproveedor.
     * @param string $category La categoría del producto.
     * @param int $partnerId El ID del socio.
     * @param bool $isMobile Indica si es para móvil.
     * @param string $provider El proveedor del producto.
     * @param string $CountrySelect (Opcional) El país seleccionado.
     * @param string $search (Opcional) Término de búsqueda para la descripción del producto.
     * @return int El número total de productos que coinciden con los criterios.
     */
    public function countProductosTipo($value,$category,$partnerId,$isMobile,$provider,$CountrySelect='',$search='' )
    {


        $joinPais='';
        $joinPais2='';

        if($CountrySelect != ''){
            $joinPais = " AND producto_mandante.pais_id='".$CountrySelect."' ";
            $joinPais2 = " AND categoria_producto.pais_id='".$CountrySelect."' ";
        }


         $sql = 'SELECT  /*+ MAX_EXECUTION_TIME(50000) */  count(*) total FROM producto
        INNER JOIN proveedor  ON (producto.proveedor_id = proveedor.proveedor_id)
        INNER JOIN subproveedor ON (producto.subproveedor_id = subproveedor.subproveedor_id) 
INNER JOIN categoria_producto ON (categoria_producto.producto_id = producto.producto_id '.$joinPais2.')
INNER JOIN producto_mandante ON (producto.producto_id = producto_mandante.producto_id AND producto_mandante.mandante = ' . $partnerId . ' '.$joinPais.') LEFT OUTER JOIN categoria ON (categoria.categoria_id =categoria_producto.categoria_id )
LEFT OUTER JOIN producto_detalle ON (producto_detalle.producto_id= producto.producto_id AND producto_detalle.p_key = "IMAGE_BACKGROUND")
 WHERE subproveedor.Tipo = ? AND subproveedor.estado="A" AND producto.estado="A" AND producto.mostrar="S" ' ;

        if ($category != "") {
            $sql = $sql . " AND categoria_producto.categoria_id= '" .$category. "'";
        }

        if ($search != "") {
            $sql = $sql . " AND producto.descripcion  LIKE '%" . $search . "%' ";
        }



        if ($provider != "") {
            $sql = $sql . " AND subproveedor.abreviado = '" .$provider. "'";
        }

        if($isMobile != ""){
            $sql = $sql .  " AND producto.mobile= 'S' ";
        }else{
            $sql = $sql .  " AND producto.desktop= 'S' ";
        }

        /*
        $sql = 'SELECT count(*) as total FROM subproveedor LEFT OUTER JOIN producto ON (producto.subproveedor_id = subproveedor.subproveedor_id) 
 WHERE subproveedor.Tipo = ?'; */

        if($_REQUEST['isDebug'] == '1'){
            print_r($sql);
        }

        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->execute($sqlQuery)[0]['total'];
    }




    /**
     * @param string $value
     * @param string $category
     * @param int $partnerId
     * @param true | null $isMobile
     * @param string $provider
     * @param int $CountrySelect
     * @return mixed
     * @throws Exception
     */
    public function countProductosTipoMandante($value = "", $category = "", $partnerId = "", $isMobile = "", $provider = "", $countrySelected = "")
    {
        //Definiendo sentencia SQL
        $sql = 'SELECT COUNT(DISTINCT producto.producto_id) total
        FROM producto
        INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id)
        INNER JOIN subproveedor ON (producto.subproveedor_id = subproveedor.subproveedor_id)
        INNER JOIN producto_mandante ON (producto.producto_id = producto_mandante.producto_id AND producto_mandante.mandante =#$partnerId##$joinPais#)
        INNER JOIN proveedor_mandante ON (proveedor_mandante.mandante = producto_mandante.mandante AND proveedor_mandante.proveedor_id = subproveedor.proveedor_id)
        INNER JOIN subproveedor_mandante ON (subproveedor_mandante.mandante = producto_mandante.mandante AND subproveedor_mandante.subproveedor_id = subproveedor.subproveedor_id)
        INNER JOIN categoria_producto ON (categoria_producto.producto_id = producto.producto_id AND categoria_producto.mandante=producto_mandante.mandante#$partnerCategoriaProducto##$joinPais2#)
        LEFT OUTER JOIN categoria_mandante ON (categoria_mandante.catmandante_id =categoria_producto.categoria_id )
        LEFT OUTER JOIN producto_detalle ON (producto_detalle.producto_id= producto.producto_id AND producto_detalle.p_key = "IMAGE_BACKGROUND")
        WHERE#$where# AND subproveedor.tipo =#$value# AND
        proveedor.estado="A"  AND subproveedor.estado="A" AND producto.estado="A"
        AND subproveedor_mandante.estado="A"   AND proveedor_mandante.estado="A"   AND producto_mandante.estado="A"
        AND categoria_producto.estado="A" AND producto.mostrar="S"';


        //Reemplazando valores -- El cambio de orden de los condicionales puede afectar la consulta
        $where = " 1 = 1";
        $replacementValues = [];
        $sql = preg_replace('/\#\$partnerId\#/', ' ?', $sql, 1);
        array_push($replacementValues, ["type" => "string", "value" => $partnerId]);

        if($countrySelected != ""){
            $sql = preg_replace('/\#\$joinPais\#/', " AND producto_mandante.pais_id = ?", $sql,1);
            array_push($replacementValues, ["type" => "number", "value" => $countrySelected]);
        }
        else {
            $sql = preg_replace('/\#\$joinPais\#/', "", $sql, 1);
        }

        if($category != ""){
            $sql = preg_replace('/\#\$partnerCategoriaProducto\#/', " AND categoria_producto.mandante = ?", $sql, 1);
            array_push($replacementValues, ["type" => "number", "value" => $partnerId]);
        }
        else {
            $sql = preg_replace('/\#\$partnerCategoriaProducto\#/', "", $sql, 1);
        }

        if($countrySelected != ""){
            $sql = preg_replace('/\#\$joinPais2\#/', " AND categoria_producto.pais_id = ?", $sql, 1);
            array_push($replacementValues, ["type" => "number", "value" => $countrySelected]);
        }
        else {
            $sql = preg_replace('/\#\$joinPais2\#/', "", $sql, 1);
        }

        if ($category != "") {
            $where = $where . " AND categoria_producto.categoria_id= ?";
            array_push($replacementValues, ["type" => "number", "value" => $category]);
        }

        if($isMobile){
            $where = $where . " AND producto.mobile= 'S'";
        }else{
            $where = $where . " AND producto.desktop= 'S'";
        }

        if ($provider != "") {
            $where = $where . " AND subproveedor.abreviado = ?";
            array_push($replacementValues, ["type" => "string", "value" => $provider]);
        }

        if($partnerId != ""){
            $where = $where . " AND proveedor_mandante.estado= 'A'";
        }

        $sql = preg_replace('/\#\$where\#/', $where, $sql, 1);

        $sql = preg_replace('/\#\$value\#/', ' ?', $sql, 1);
        array_push($replacementValues, ["type" => "string", "value" => $value]);

        $sqlQuery = new SqlQuery($sql);

        foreach($replacementValues as $replaceValue) {
            if($replaceValue["type"] == "string") {
                $sqlQuery->set($replaceValue["value"]);
            }
            elseif ($replaceValue["type"] == "number"){
                $sqlQuery->setNumber($replaceValue["value"]);
            }
        }

        return $this->execute($sqlQuery)[0]["total"];
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
        $sql = 'DELETE FROM subproveedor WHERE descripcion = ?';
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
        $sql = 'DELETE FROM subproveedor WHERE tipo = ?';
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
        $sql = 'DELETE FROM subproveedor WHERE estado = ?';
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
        $sql = 'DELETE FROM subproveedor WHERE verifica = ?';
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
        $sql = 'DELETE FROM subproveedor WHERE fecha_crea = ?';
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
        $sql = 'DELETE FROM subproveedor WHERE fecha_modif = ?';
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
        $sql = 'DELETE FROM subproveedor WHERE usucrea_id = ?';
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
        $sql = 'DELETE FROM subproveedor WHERE usumodif_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->executeUpdate($sqlQuery);
    }









    /**
     * Crear y devolver un objeto del tipo Subproveedor
     * con los valores de una consulta sql
     * 
     *  
     * @param Arreglo $row arreglo asociativo
     *
     * @return Object $Subproveedor Subproveedor
     *
     * @access protected
     *
     */
    protected function readRow($row)
    {

        $subproveedor = new Subproveedor();

        $subproveedor->subproveedorId = $row['subproveedor_id'];
        $subproveedor->descripcion = $row['descripcion'];
        $subproveedor->tipo = $row['tipo'];
        $subproveedor->estado = $row['estado'];
        $subproveedor->verifica = $row['verifica'];
        $subproveedor->abreviado = $row['abreviado'];
        $subproveedor->usucreaId = $row['usucrea_id'];
        $subproveedor->usumodifId = $row['usumodif_id'];
        $subproveedor->imagen = $row['imagen'];
        $subproveedor->orden = $row['orden'];
        $subproveedor->proveedorId = $row['proveedor_id'];

        return $subproveedor;
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
