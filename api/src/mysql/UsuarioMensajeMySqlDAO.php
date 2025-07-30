<?php namespace Backend\mysql;

use Backend\dao\UsuarioMensajeDAO;
use Backend\dto\UsuarioMensaje;
use Backend\dto\Helpers;
use Backend\sql\QueryExecutor;
use Backend\sql\SqlQuery;
use Backend\sql\Transaction;

use Backend\sql\ConnectionProperty;
use PDO;
/**
 * Clase 'UsuarioMandanteMySqlDAO'
 *
 * Esta clase provee las consultas del modelo o tabla 'UsuarioMandante'
 *
 * Ejemplo de uso:
 * $UsuarioMandanteMySqlDAO = new UsuarioMandanteMySqlDAO();
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class UsuarioMensajeMySqlDAO implements UsuarioMensajeDAO
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
        $sql = 'SELECT * FROM usuario_mensaje WHERE usumensaje_id = ?';
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
    public function queryAll()
    {
        $sql = 'SELECT * FROM usuario_mensaje';
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

    public function queryByParent($value, $user, $time) {
        $sql = "SELECT parent_id FROM usuario_mensaje WHERE parent_id IN({$value}) AND fecha_expiracion >= '{$time}' AND tipo = 'PUSHNOTIFICACION' AND usuto_id = {$user}";
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery, true);
    }

    /**
     * Consulta todos los registros de la tabla 'usuario_mensaje' ordenados por una columna específica.
     *
     * @param string $orderColumn El nombre de la columna por la cual se ordenarán los registros.
     * @return array La lista de registros ordenados.
     */
    public function queryAllOrderBy($orderColumn)
    {
        $sql = 'SELECT * FROM usuario_mensaje ORDER BY ' . $orderColumn;
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }

    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $usumandante_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function delete($usumensaje_id)
    {
        $sql = 'DELETE FROM usuario_mensaje WHERE usumensaje_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($usumensaje_id);
        return $this->executeUpdate($sqlQuery);
    }


    /**
     * Actualiza el estado de un mensaje de usuario en la base de datos.
     *
     * @param object $usuarioMensaje Objeto que contiene los datos del mensaje de usuario a actualizar.
     *                               - estado: El nuevo estado del mensaje.
     *                               - usumencampanaId: El ID de la campaña del mensaje de usuario.
     * @return int Número de filas afectadas por la actualización.
     */
    public function updateEstado($usuarioMensaje)
    {
        $sql = 'UPDATE usuario_mensaje SET  estado= ? WHERE usumencampana_id = ?';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->set($usuarioMensaje->estado);

        $sqlQuery->setNumber($usuarioMensaje->usumencampanaId);

        return $this->executeUpdate($sqlQuery);
    }


    /**
     * Insertar un registro en la base de datos
     *
     * @param Objeto usuarioMandante usuarioMandante
     *
     * @return String $id resultado de la consulta
     *
     */
    public function insert($usuarioMensaje)
    {
        $sql = 'INSERT INTO usuario_mensaje (usufrom_id, usuto_id, is_read, msubject,body,parent_id, usucrea_id, usumodif_id,tipo,externo_id,proveedor_id,pais_id,fecha_expiracion,usumencampana_id,valor1,valor2,valor3) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($usuarioMensaje->usufromId);
        $sqlQuery->set($usuarioMensaje->usutoId);
        $sqlQuery->set($usuarioMensaje->isRead);
        $sqlQuery->set($usuarioMensaje->msubject);
        $sqlQuery->set($usuarioMensaje->body);
        $sqlQuery->set($usuarioMensaje->parentId);
        $sqlQuery->setNumber($usuarioMensaje->usucreaId);
        $sqlQuery->setNumber($usuarioMensaje->usumodifId);
        $sqlQuery->set($usuarioMensaje->tipo);
        $sqlQuery->set($usuarioMensaje->externoId);
        $sqlQuery->set($usuarioMensaje->proveedorId);

        if ($usuarioMensaje->paisId == "") {
            $usuarioMensaje->paisId = 0;
        }
        $sqlQuery->set($usuarioMensaje->paisId);
        $sqlQuery->set($usuarioMensaje->fechaExpiracion);
        if($usuarioMensaje->usumencampanaId == ""){
            $usuarioMensaje->usumencampanaId = 0;
        }
        $sqlQuery->set($usuarioMensaje->usumencampanaId);


        $sqlQuery->set($usuarioMensaje->valor1);
        $sqlQuery->set($usuarioMensaje->valor2);
        if($usuarioMensaje->valor3 ==""){
            $usuarioMensaje->valor3="0";
        }
        $sqlQuery->set($usuarioMensaje->valor3);

        $id = $this->executeInsert($sqlQuery);
        $usuarioMensaje->usumensajeId = $id;
        return $id;
    }

    public function updateReadForID($value) {
        $sql = 'UPDATE usuario_mensaje SET is_read = 1 WHERE usumensaje_id in (?)';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setSIN($value);

        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Editar un registro en la base de datos
     *
     * @param Objeto usuarioMandante usuarioMandante
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function update($usuarioMensaje)
    {
        $sql = 'UPDATE usuario_mensaje SET  usufrom_id = ?, usuto_id = ?, is_read = ?, msubject = ?, body = ?, parent_id=?,tipo=?,externo_id=?,proveedor_id=?,pais_id=?,fecha_expiracion = ?, usumencampana_id = ?, valor1 = ?, valor2 = ?, valor3 = ? WHERE usumensaje_id = ?';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($usuarioMensaje->usufromId);
        $sqlQuery->set($usuarioMensaje->usutoId);
        $sqlQuery->set($usuarioMensaje->isRead);
        $sqlQuery->set($usuarioMensaje->msubject);
        $sqlQuery->set($usuarioMensaje->body);
        $sqlQuery->set($usuarioMensaje->parentId);
        $sqlQuery->set($usuarioMensaje->tipo);
        $sqlQuery->set($usuarioMensaje->externoId);
        $sqlQuery->set($usuarioMensaje->proveedorId);

        if ($usuarioMensaje->paisId == "") {
            $usuarioMensaje->paisId = 0;
        }
        $sqlQuery->set($usuarioMensaje->paisId);
        $sqlQuery->set($usuarioMensaje->fechaExpiracion);
        if($usuarioMensaje->usumencampanaId == ""){
            $usuarioMensaje->usumencampanaId = 0;
        }
        $sqlQuery->set($usuarioMensaje->usumencampanaId);


        $sqlQuery->set($usuarioMensaje->valor1);
        $sqlQuery->set($usuarioMensaje->valor2);

        if($usuarioMensaje->valor3 ==""){
            $usuarioMensaje->valor3="0";
        }
        $sqlQuery->set($usuarioMensaje->valor3);

        $sqlQuery->setNumber($usuarioMensaje->usumensajeId);
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
        $sql = 'DELETE FROM usuario_mensaje';
        $sqlQuery = new SqlQuery($sql);
        return $this->executeUpdate($sqlQuery);
    }


    /**
     * Obtener todos los registros donde se encuentre que
     * la columna usufrom_id sea igual al valor pasado como parámetro
     *
     * @param String $value usufrom_id requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByUsufromId($value)
    {
        $sql = 'SELECT * FROM usuario_mensaje WHERE usufrom_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna usuto_id sea igual al valor pasado como parámetro
     *
     * @param String $value usuto_id requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByUsutoId($value)
    {
        $sql = 'SELECT * FROM usuario_mensaje WHERE usuto_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna is_read sea igual al valor pasado como parámetro
     *
     * @param String $value is_read requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByIsRead($value)
    {
        $sql = 'SELECT * FROM usuario_mensaje WHERE is_read = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna body sea igual al valor pasado como parámetro
     *
     * @param String $value body requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByBody($value)
    {
        $sql = 'SELECT * FROM usuario_mensaje WHERE body = ?';
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
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByFechaCrea($value)
    {
        $sql = 'SELECT * FROM usuario_mensaje WHERE fecha_crea = ?';
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
    public function queryByUsucreaId($value)
    {
        $sql = 'SELECT * FROM usuario_mensaje WHERE usucrea_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
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
    public function queryByFechaModif($value)
    {
        $sql = 'SELECT * FROM usuario_mensaje WHERE fecha_modif = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
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
    public function queryByUsumodifId($value)
    {
        $sql = 'SELECT * FROM usuario_mensaje WHERE usumodif_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);
    }
    /**
     * Obtener todos los registros donde se encuentre que
     * la columna usumencampana_id sea igual al valor pasado como parámetro
     *
     * @param String $value usumencampana_id requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByUsumencampanaId($value)
    {
        $sql = 'SELECT * FROM usuario_mensaje WHERE usumencampana_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);
    }


    /**
     * Realizar una consulta en la tabla de UsuarioMensaje 'UsuarioMensaje'
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
    public function queryUsuarioMensajesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $userToSpecific = '', $grouping = '')
    {

        $Helpers = new Helpers();
        $where = " where 1=1 ";

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
                $fieldOperation = str_replace("'now()'","now()",$fieldOperation);
                $fieldOperation = str_replace("'NOW()'","now()",$fieldOperation);
                if ($fieldOperation != "") {
                    $whereArray[] = $fieldName . $fieldOperation;
                }

                if (oldCount($whereArray) > 0) {
                    $where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
                } else {
                    $where = "";
                }
            }

        }
        $leftUserSpecific='';
        if($userToSpecific != ''){
            $leftUserSpecific=" LEFT OUTER JOIN usuario_mensaje usuario_mensaje2 on (usuario_mensaje2.parent_id = usuario_mensaje.usumensaje_id and usuario_mensaje2.usuto_id='".$userToSpecific."') ";
        }
        if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ){
            $connOriginal = $_ENV["connectionGlobal"]->getConnection();




            $connDB5 = null;
            $connDB5 = new \PDO("mysql:host=db5.local;dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword(), array(
                PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-bundle.crt',
                PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
            ));

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

        $sql = "SELECT  /*+ MAX_EXECUTION_TIME(30000) */ count(*) count FROM usuario_mensaje LEFT OUTER JOIN usuario_mensajecampana ON (usuario_mensajecampana.usumencampana_id = usuario_mensaje.usumencampana_id)   LEFT OUTER JOIN usuario_mandante usufrom ON (usufrom.usumandante_id = usuario_mensaje.usufrom_id) LEFT OUTER JOIN usuario_mandante usuto ON (usuto.usumandante_id = usuario_mensaje.usuto_id) LEFT OUTER JOIN pais ON (pais.pais_id = usuario_mensaje.pais_id)  " . $leftUserSpecific . $where;
        if ($grouping != "") {

            $where = $where . " GROUP BY " . $grouping;
        }


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
        $sql = "SELECT  /*+ MAX_EXECUTION_TIME(30000) */ " . $select . " FROM usuario_mensaje LEFT OUTER JOIN usuario_mensajecampana ON (usuario_mensajecampana.usumencampana_id = usuario_mensaje.usumencampana_id)  LEFT OUTER JOIN usuario_mandante usufrom ON (usufrom.usumandante_id = usuario_mensaje.usufrom_id) LEFT OUTER JOIN usuario_mandante usuto ON (usuto.usumandante_id = usuario_mensaje.usuto_id) LEFT OUTER JOIN pais ON (pais.pais_id = usuario_mensaje.pais_id)  " . $leftUserSpecific . $where . " " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        if($_ENV["debugFixed2"] == '1'){
            print_r($sql);
        }
        $sqlQuery = new SqlQuery($sql);

        $result = $Helpers->process_data($this->execute2($sqlQuery));

        if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ) {
            $connDB5 = null;
            $_ENV["connectionGlobal"]->setConnection($connOriginal);
            $this->transaction->setConnection($_ENV["connectionGlobal"]);
        }

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . ', "sql" : "' . $sql . '"}';

        return $json;
    }

    public function queryUsuarioMensajesCustomCampana($usuario,$paisId,$fechaCrea="",$mandante="",$usumandanteId="")
    {
        if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ){
            $connOriginal = $_ENV["connectionGlobal"]->getConnection();




            $connDB5 = null;
            $connDB5 = new \PDO("mysql:host=db5.local;dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword(), array(
                PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-bundle.crt',
                PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
            ));

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


        $sql = "
             /*+ MAX_EXECUTION_TIME(30000) */  SELECT m.*, umc.*
FROM usuario_mensajecampana umc
         LEFT OUTER JOIN usuario_mensaje m
                         ON (umc.usumencampana_id = m.usumencampana_id AND m.usuto_id = '".$usumandanteId."')
         LEFT OUTER JOIN usuario_mandante ON (usuario_mandante.usumandante_id = '".$usumandanteId."')
WHERE umc.tipo = 'MENSAJE'
  AND umc.estado = 'A'
  AND ((
                   usuario_mandante.mandante = m.proveedor_id
               AND (m.externo_id = '' OR m.externo_id = '0')
               AND (m.fecha_expiracion IS NULL OR now() < m.fecha_expiracion)
               AND (((umc.fecha_envio IS NULL
               OR now() > umc.fecha_envio)
               AND umc.is_read = 0)
               OR m.is_read = 1)
               AND (umc.is_read <> 1 OR (umc.is_read = 1 AND m.is_read = 1))
               AND (umc.pais_id = '".$paisId."' OR umc.pais_id = '0')

           )
    OR (umc.is_read = 0 AND
        usuario_mandante.mandante = umc.proveedor_id
        AND (umc.usuto_id = 0 AND umc.usufrom_id = 0)
        AND umc.fecha_envio > '".$fechaCrea."'
        AND (umc.pais_id = '".$paisId."' OR umc.pais_id = '0')
           ))
ORDER BY umc.fecha_envio desc
LIMIT 20

              ";

        if($usumandanteId != ''){

        }


        if($_ENV["debugFixed2"] == '1'){
            print_r($sql);
        }

        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ) {
            $connDB5 = null;
            $_ENV["connectionGlobal"]->setConnection($connOriginal);
            $this->transaction->setConnection($_ENV["connectionGlobal"]);
        }
        $json = '{ "count" : ' . json_encode(array()) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }

    /**
     * Consulta personalizada para contar mensajes de campaña no leídos de un usuario.
     *
     * @param int $usuario ID del usuario.
     * @param int $paisId ID del país.
     * @param string $fechaCrea Fecha de creación (opcional).
     * @param string $mandante Mandante (opcional).
     * @param int $usumandanteId ID del usuario mandante (opcional).
     * @return int Número total de mensajes no leídos.
     */
    public function queryUsuarioMensajesCustomCampanaCountNoRead($usuario,$paisId,$fechaCrea="",$mandante="",$usumandanteId="")
    {
        if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ){
            $connOriginal = $_ENV["connectionGlobal"]->getConnection();




            $connDB5 = null;
            $connDB5 = new \PDO("mysql:host=db5.local;dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword(), array(
                PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-bundle.crt',
                PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
            ));

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

        if($fechaCrea == ''){
            $fechaCrea =date('Y-m-d H:i:s');
        }
        $sql = "select /*+ MAX_EXECUTION_TIME(2000) */  COUNT(usuario_mensaje.usumensaje_id) mensajes
from usuario_mensaje
    INNER JOIN usuario_mensajecampana ON (usuario_mensajecampana.usumencampana_id = usuario_mensaje.usumencampana_id)
    
    LEFT JOIN usuario_mensaje um
                   ON (um.usumencampana_id = usuario_mensajecampana.usumencampana_id AND um.usuto_id = '".$usumandanteId."')

where ('".$mandante."' = usuario_mensaje.proveedor_id) AND (usuario_mensaje.usuto_id = 0  AND usuario_mensaje.usufrom_id = 0)
  AND usuario_mensaje.tipo = 'MENSAJE' AND usuario_mensajecampana.fecha_envio > '".$fechaCrea."'
  AND (usuario_mensaje.pais_id = '".$paisId."' OR usuario_mensaje.pais_id = '0')
            AND usuario_mensaje.is_read = 0 and um.usumensaje_id IS NULL   and usuario_mensajecampana.is_read = 0
                 AND usuario_mensajecampana.estado = 'A'
        ";
        $sql = "select  /*+ MAX_EXECUTION_TIME(2000) */  COUNT(usuario_mensajecampana.usumensaje_id) mensajes
from usuario_mensajecampana
    
    LEFT JOIN usuario_mensaje um
                   ON (um.usumencampana_id = usuario_mensajecampana.usumencampana_id AND um.usuto_id = '".$usumandanteId."')

where ('".$mandante."' = usuario_mensajecampana.mandante) AND (usuario_mensajecampana.usuto_id = 0  AND usuario_mensajecampana.usufrom_id = 0)
  AND usuario_mensajecampana.tipo = 'MENSAJE' AND usuario_mensajecampana.fecha_envio < now() AND usuario_mensajecampana.fecha_envio > '".$fechaCrea."'
  AND (usuario_mensajecampana.pais_id = '".$paisId."' OR usuario_mensajecampana.pais_id = '0')
            and um.usumensaje_id IS NULL 
                 AND usuario_mensajecampana.estado = 'A'
        ";

        if($_ENV["debugFixed2"] == '1'){
            print_r($sql);
        }

        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $mensajesGlobales = $result[0]['.mensajes'];


        $sql = "select  /*+ MAX_EXECUTION_TIME(2000) */  COUNT(usuario_mensaje.usumensaje_id) mensajes from usuario_mensaje 
            LEFT OUTER JOIN  usuario_mandante ON (usuario_mandante.usumandante_id = usuario_mensaje.usuto_id)                LEFT OUTER JOIN usuario_mensajecampana ON (usuario_mensajecampana.usumencampana_id = usuario_mensaje.usumencampana_id)

            where (usuario_mandante.usuario_mandante=" . $usuario . ") AND usuario_mensaje.tipo='MENSAJE' 
                      AND (usuario_mensaje.fecha_expiracion IS NULL  OR usuario_mensaje.is_read = 1 OR now() < usuario_mensaje.fecha_expiracion) 
                      AND ((usuario_mensajecampana.is_read IS NULL AND usuario_mensaje.is_read = 0 ) OR
                (usuario_mensajecampana.is_read = 0 AND  usuario_mensaje.is_read = 0 ))        AND (usuario_mensajecampana.fecha_envio IS NULL  OR usuario_mensajecampana.fecha_envio IS NULL OR
                now() > DATE_FORMAT(usuario_mensajecampana.fecha_envio, '%Y-%m-%d %H:%i:%s'))

            ";
        $sql = "select /*+ MAX_EXECUTION_TIME(2000) */  COUNT(usuario_mensaje.usumensaje_id) mensajes
from usuario_mensaje
         INNER JOIN usuario_mandante ON (usuario_mandante.usumandante_id = usuario_mensaje.usuto_id)
         INNER JOIN usuario_mensajecampana
                         ON (usuario_mensajecampana.usumencampana_id = usuario_mensaje.usumencampana_id)

where (usuario_mensaje.usuto_id ='".$usumandanteId."')
  AND usuario_mensaje.tipo = 'MENSAJE'
   AND usuario_mensajecampana.fecha_envio < now()
  AND ((usuario_mensaje.is_read = 0))
  AND usuario_mensajecampana.estado = 'A'


            ";

        $sqlQuery = new SqlQuery($sql);

        if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ){

            $connDB5 = null;
            $connDB5 = new \PDO("mysql:host=db5.local;dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword(), array(
                PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-bundle.crt',
                PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
            ));

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


        $result = $this->execute2($sqlQuery);

        if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ) {
            $connDB5 = null;
            $_ENV["connectionGlobal"]->setConnection($connOriginal);
            $this->transaction->setConnection($_ENV["connectionGlobal"]);
        }

        $mismensajes = $result[0]['.mensajes'];

        return $mismensajes+$mensajesGlobales;
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna usufrom_id sea igual al valor pasado como parámetro
     *
     * @param String $value usufrom_id requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByUsufromId($value)
    {
        $sql = 'DELETE FROM usuario_mensaje WHERE usufrom_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna usuto_id sea igual al valor pasado como parámetro
     *
     * @param String $value usuto_id requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByUsutoId($value)
    {
        $sql = 'DELETE FROM usuario_mensaje WHERE usuto_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna is_read sea igual al valor pasado como parámetro
     *
     * @param String $value is_read requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByIsRead($value)
    {
        $sql = 'DELETE FROM usuario_mensaje WHERE is_read = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna body sea igual al valor pasado como parámetro
     *
     * @param String $value body requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByBody($value)
    {
        $sql = 'DELETE FROM usuario_mensaje WHERE body = ?';
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
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByFechaCrea($value)
    {
        $sql = 'DELETE FROM usuario_mensaje WHERE fecha_crea = ?';
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
    public function deleteByUsucreaId($value)
    {
        $sql = 'DELETE FROM usuario_mensaje WHERE usucrea_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
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
    public function deleteByFechaModif($value)
    {
        $sql = 'DELETE FROM usuario_mensaje WHERE fecha_modif = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
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
    public function deleteByUsumodifId($value)
    {
        $sql = 'DELETE FROM usuario_mensaje WHERE usumodif_id = ?';
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
    public function deleteByUsumencampanaId($value)
    {
        $sql = 'DELETE FROM usuario_mensaje WHERE usumencampana_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->executeUpdate($sqlQuery);
    }


    /**
     * Crear y devolver un objeto del tipo UsuarioMensaje
     * con los valores de una consulta sql
     * 
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Objeto $usuarioMensaje UsuarioMensaje
     *
     * @access protected
     *
     */
    protected function readRow($row)
    {
        $usuarioMensaje = new UsuarioMensaje();

        $usuarioMensaje->usumensajeId = $row['usumensaje_id'];
        $usuarioMensaje->usufromId = $row['usufrom_id'];
        $usuarioMensaje->usutoId = $row['usuto_id'];
        $usuarioMensaje->isRead = $row['is_read'];
        $usuarioMensaje->msubject = $row['msubject'];
        $usuarioMensaje->body = $row['body'];
        $usuarioMensaje->parentId = $row['parent_id'];
        $usuarioMensaje->fechaCrea = $row['fecha_crea'];
        $usuarioMensaje->usucreaId = $row['usucrea_id'];
        $usuarioMensaje->fechaModif = $row['fecha_modif'];
        $usuarioMensaje->usumodifId = $row['usumodif_id'];
        $usuarioMensaje->paisId = $row['pais_id'];
        $usuarioMensaje->tipo = $row['tipo'];
        $usuarioMensaje->externoId = $row['externo_id'];
        $usuarioMensaje->proveedorId = $row['proveedor_id'];
        $usuarioMensaje->fechaExpiracion = $row['fecha_expiracion'];
        $usuarioMensaje->usumencampanaId = $row['usumencampana_id'];
        $usuarioMensaje->valor1 = $row['valor1'];
        $usuarioMensaje->valor2 = $row['valor2'];
        $usuarioMensaje->valor3 = $row['valor3'];

        return $usuarioMensaje;
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
    protected function getList($sqlQuery, $notObject = false)
    {
        $tab = QueryExecutor::execute($this->transaction, $sqlQuery);
        if($notObject) return $tab;
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
