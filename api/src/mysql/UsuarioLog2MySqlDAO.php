<?php namespace Backend\mysql;
use Backend\dao\UsuarioLog2DAO;
use Backend\dto\Helpers;
use Backend\dto\UsuarioLog2;
use Backend\sql\QueryExecutor;
use Backend\sql\SqlQuery;
use Backend\sql\Transaction;
use Backend\sql\ConnectionProperty;
use PDO;
/** 
* Clase 'UsuarioLog2MySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'UsuarioLog2'
* 
* Ejemplo de uso: 
* $UsuarioLog2MySqlDAO = new UsuarioLog2MySqlDAO();
* 
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class UsuarioLog2MySqlDAO implements UsuarioLog2DAO
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
	public function load($id){
		$sql = 'SELECT * FROM usuario_log2 WHERE usuariolog2_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($id);
		return $this->getRow($sqlQuery);
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
    public function loadIdAndEstado($id,$estado){
        $sql = 'SELECT * FROM usuario_log2 WHERE usuariolog2_id = ? and estadod = ? ';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($id);
        $sqlQuery->setString($estado);
        return $this->getRow($sqlQuery);
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
    public function queryAll(){
		$sql = 'SELECT * FROM usuario_log2';
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
		$sql = 'SELECT * FROM usuario_log2 ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $usuariolog2_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function delete($usuariolog2_id){
		$sql = 'DELETE FROM usuario_log2 WHERE usuariolog2_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($usuariolog2_id);
		return $this->executeUpdate($sqlQuery);
	}
	
    /**
     * Insertar un registro en la base de datos
     *
     * @param Objeto UsuarioLog2 UsuarioLog2
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($UsuarioLog2){
		$sql = 'INSERT INTO usuario_log2 (usuario_id,usuarioaprobar_id,usuariosolicita_id,usuariosolicita_ip,usuario_ip,usuarioaprobar_ip, tipo,estado, valor_antes,valor_despues, usucrea_id, usumodif_id,dispositivo,soperativo,sversion,imagen) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($UsuarioLog2->usuarioId);
        $sqlQuery->setNumber($UsuarioLog2->usuarioaprobarId);
        $sqlQuery->setNumber($UsuarioLog2->usuariosolicitaId);
        $sqlQuery->set($UsuarioLog2->usuariosolicitaIp);

        $sqlQuery->set($UsuarioLog2->usuarioIp);
        $sqlQuery->set($UsuarioLog2->usuarioaprobarIp);
        $sqlQuery->set($UsuarioLog2->tipo);
        $sqlQuery->set($UsuarioLog2->estado);
        $sqlQuery->set($UsuarioLog2->valorAntes);
        $sqlQuery->set($UsuarioLog2->valorDespues);
		$sqlQuery->setNumber($UsuarioLog2->usucreaId);
        $sqlQuery->setNumber($UsuarioLog2->usumodifId);
        $sqlQuery->set($UsuarioLog2->dispositivo);
        $sqlQuery->set($UsuarioLog2->soperativo);
        $sqlQuery->set($UsuarioLog2->sversion);
        if($UsuarioLog2->imagen != ''){
            $sqlQuery->setSIN("'".$UsuarioLog2->imagen."'");
        }else{
            $sqlQuery->set("''");

        }

		$id = $this->executeInsert($sqlQuery);	
		$UsuarioLog2->usuariolog2Id = $id;
		return $id;
	}
	
    /**
     * Editar un registro en la base de datos
     *
     * @param Objeto UsuarioLog2 UsuarioLog2
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function updateEstadoByID($id, $state, $usuarioAp, $ip) {
        $sql = "UPDATE usuario_log2 SET estado = ?, usuarioaprobar_id = ?, usuarioaprobar_ip = ?  where usuariolog2_id = ?";
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->set($state);
        $sqlQuery->setNumber($usuarioAp);
        $sqlQuery->set($ip);
        $sqlQuery->setNumber($id);
        return $this->executeUpdate($sqlQuery);
    }

    public function update($UsuarioLog2){
		$sql = 'UPDATE usuario_log2 SET usuario_id = ?, usuarioaprobar_id = ?, usuariosolicita_id = ?, usuariosolicita_ip = ?,usuario_ip = ?, usuarioaprobar_ip = ?, tipo = ?, estado = ?, valor_antes = ?, valor_despues = ?, usucrea_id = ?, usumodif_id = ?,dispositivo=?,soperativo=?,sversion=? WHERE usuariolog2_id = ?';
		$sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($UsuarioLog2->usuarioId);
        $sqlQuery->setNumber($UsuarioLog2->usuarioaprobarId);
        $sqlQuery->setNumber($UsuarioLog2->usuariosolicitaId);
        $sqlQuery->set($UsuarioLog2->usuariosolicitaIp);
        $sqlQuery->set($UsuarioLog2->usuarioIp);
        $sqlQuery->set($UsuarioLog2->usuarioaprobarIp);
        $sqlQuery->set($UsuarioLog2->tipo);
        $sqlQuery->set($UsuarioLog2->estado);
        $sqlQuery->set($UsuarioLog2->valorAntes);
        $sqlQuery->set($UsuarioLog2->valorDespues);
		$sqlQuery->setNumber($UsuarioLog2->usucreaId);
		$sqlQuery->setNumber($UsuarioLog2->usumodifId);
        $sqlQuery->set($UsuarioLog2->dispositivo);
        $sqlQuery->set($UsuarioLog2->soperativo);
        $sqlQuery->set($UsuarioLog2->sversion);

		$sqlQuery->setNumber($UsuarioLog2->usuariolog2Id);
		return $this->executeUpdate($sqlQuery);
	}





    /**
    * Realizar una consulta en la tabla de UsuarioLog2 'UsuarioLog2'
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
    * @return Array $json resultado de la consulta
    *
    */
    public function queryUsuarioLog2sCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
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


        $sql = 'SELECT /*+ MAX_EXECUTION_TIME(50000) */  count(*) count 
              FROM usuario_log2 inner join time_dimension 
                        on (time_dimension.dbtimestamp = usuario_log2.fecha_crea) LEFT OUTER JOIN usuario ON (usuario.usuario_id=usuario_log2.usuario_id) 
                             LEFT OUTER JOIN usuario_mandante usuariosolicita ON (usuariosolicita.usumandante_id=usuario_log2.usuariosolicita_id) 
                             LEFT OUTER JOIN usuario_mandante usuarioaprobar ON (usuarioaprobar.usumandante_id=usuario_log2.usuarioaprobar_id) 
                            LEFT OUTER JOIN pais ON (usuario.pais_id=pais.pais_id)  LEFT OUTER JOIN clasificador ON (clasificador.clasificador_id=usuario_log2.tipo)  ' . $where;

        if($_REQUEST['isDebug']=='1'){
            print_r($sql);
        }

        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT /*+ MAX_EXECUTION_TIME(50000) */  ' .$select .'  FROM usuario_log2 
              LEFT OUTER JOIN usuario ON (usuario.usuario_id=usuario_log2.usuario_id) 
                                           LEFT OUTER JOIN usuario_mandante usuariosolicita ON (usuariosolicita.usumandante_id=usuario_log2.usuariosolicita_id) 
                             LEFT OUTER JOIN usuario_mandante usuarioaprobar ON (usuarioaprobar.usumandante_id=usuario_log2.usuarioaprobar_id) 

              LEFT OUTER JOIN pais ON (usuario.pais_id=pais.pais_id) LEFT OUTER JOIN clasificador ON (clasificador.clasificador_id=usuario_log2.tipo)  ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);

        if($_REQUEST['isDebug']=='1'){
            print_r($sql);
            exit();
        }

        $result = $this->execute2($sqlQuery);

        for ($i = 0; $i < oldCount($result); $i++) {
            $tmp = $result[$i];
            if ($result[$i]['usuario_log2.imagen'] != "") {
                $image_data = ($result[$i]['usuario_log2.imagen']);
                $tmp['usuario_log2.imagen'] = base64_encode($image_data);
                $tmp[20] = base64_encode($image_data);
            }
            $result[$i] = $tmp;

        }

        $result = $Helpers->process_data($result);

        if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ) {
            $connDB5 = null;
            $_ENV["connectionGlobal"]->setConnection($connOriginal);
            $this->transaction->setConnection($_ENV["connectionGlobal"]);
        }

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }


    /**
     * Realizar una consulta en la tabla de UsuarioLog2 'UsuarioLog2'
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
     * @return Array $json resultado de la consulta
     *
     */
    public function queryUsuarioLog2sCustomPuntoVenta($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
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


        $sql = "SELECT count(*) count 
              FROM usuario_log2 inner join time_dimension 
                        on (time_dimension.dbtimestamp = usuario_log2.fecha_crea) LEFT OUTER JOIN usuario ON (usuario.usuario_id=usuario_log2.usuario_id) 
                             LEFT OUTER JOIN usuario_mandante usuariosolicita ON (usuariosolicita.usumandante_id=usuario_log2.usuariosolicita_id) 
                             LEFT OUTER JOIN usuario_mandante usuarioaprobar ON (usuarioaprobar.usumandante_id=usuario_log2.usuarioaprobar_id) 
                            INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id=usuario_log2.usuario_id AND usuario_perfil.perfil_id='PUNTOVENTA')
                            LEFT OUTER JOIN pais ON (usuario.pais_id=pais.pais_id)  LEFT OUTER JOIN clasificador ON (clasificador.clasificador_id=usuario_log2.tipo)" . $where;



        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);

        $sql = "SELECT " . $select . "  FROM usuario_log2 
              LEFT OUTER JOIN usuario ON (usuario.usuario_id=usuario_log2.usuario_id) 
                                           LEFT OUTER JOIN usuario_mandante usuariosolicita ON (usuariosolicita.usumandante_id=usuario_log2.usuariosolicita_id) 
                             LEFT OUTER JOIN usuario_mandante usuarioaprobar ON (usuarioaprobar.usumandante_id=usuario_log2.usuarioaprobar_id) 
                             LEFT OUTER JOIN pais ON (usuario.pais_id=pais.pais_id) 
                             INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id=usuario_log2.usuario_id AND usuario_perfil.perfil_id='PUNTOVENTA')
                             LEFT OUTER JOIN clasificador ON (clasificador.clasificador_id=usuario_log2.tipo)  " . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;


        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        for($i=0;$i<oldCount($result);$i++){
            $tmp = $result[$i];
            if($result[$i]['usuario_log2.imagen'] != ""){
                $image_data=($result[$i]['usuario_log2.imagen']);
                $tmp['usuario_log2.imagen'] = base64_encode($image_data);
                $tmp[17] = base64_encode($image_data);


            }
            $result[$i]=$tmp;

        }

        $result = $Helpers->process_data($result);

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
	public function clean(){
		$sql = 'DELETE FROM usuario_log2';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}






    /**
     * Obtener todos los registros donde se encuentre que
     * la columna usuario_id sea igual al valor pasado como parámetro
     *
     * @param String $value usuario_id requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByUsuarioId($value){
		$sql = 'SELECT * FROM usuario_log2 WHERE usuario_id = ?';
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
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByUsucreaId($value){
		$sql = 'SELECT * FROM usuario_log2 WHERE usucrea_id = ?';
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
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByUsumodifId($value){
		$sql = 'SELECT * FROM usuario_log2 WHERE usumodif_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna fecha_crea sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_crea requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByFechaCrea($value){
		$sql = 'SELECT * FROM usuario_log2 WHERE fecha_crea = ?';
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
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByFechaModif($value){
		$sql = 'SELECT * FROM usuario_log2 WHERE fecha_modif = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}












    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna usuarioId sea igual al valor pasado como parámetro
     *
     * @param String $value usuarioId requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByNombre($value){
		$sql = 'DELETE FROM usuario_log2 WHERE usuarioId = ?';
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
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByUsucreaId($value){
		$sql = 'DELETE FROM usuario_log2 WHERE usucrea_id = ?';
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
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByUsumodifId($value){
		$sql = 'DELETE FROM usuario_log2 WHERE usumodif_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna fecha_crea sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_crea requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByFechaCrea($value){
		$sql = 'DELETE FROM usuario_log2 WHERE fecha_crea = ?';
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
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByFechaModif($value){
		$sql = 'DELETE FROM usuario_log2 WHERE fecha_modif = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}


	






    /**
     * Crear y devolver un objeto del tipo UsuarioLog2
     * con los valores de una consulta sql
     * 
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Objeto $UsuarioLog2 UsuarioLog2
     *
     * @access protected
     *
     */
	protected function readRow($row){
		$UsuarioLog2 = new UsuarioLog2();
		
		$UsuarioLog2->usuariolog2Id = $row['usuariolog2_id'];
        $UsuarioLog2->usuarioId = $row['usuario_id'];
        $UsuarioLog2->usuarioaprobarId = $row['usuarioaprobar_id'];
        $UsuarioLog2->usuariosolicitaId = $row['usuariosolicita_id'];
        $UsuarioLog2->usuariosolicitaIp = $row['usuariosolicita_ip'];

        $UsuarioLog2->usuarioIp = $row['usuario_ip'];
        $UsuarioLog2->usuarioaprobarIp = $row['usuarioaprobar_ip'];
        $UsuarioLog2->tipo = $row['tipo'];
        $UsuarioLog2->estado = $row['estado'];
        $UsuarioLog2->valorAntes = $row['valor_antes'];
        $UsuarioLog2->valorDespues = $row['valor_despues'];
		$UsuarioLog2->usucreaId = $row['usucrea_id'];
		$UsuarioLog2->usumodifId = $row['usumodif_id'];
		$UsuarioLog2->fechaCrea = $row['fecha_crea'];
		$UsuarioLog2->fechaModif = $row['fecha_modif'];
        $UsuarioLog2->dispositivo = $row['dispositivo'];
        $UsuarioLog2->soperativo = $row['soperativo'];
        $UsuarioLog2->sversion = $row['sversion'];
        $UsuarioLog2->imagen = $row['imagen'];

		return $UsuarioLog2;
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
