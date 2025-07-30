<?php namespace Backend\mysql;


use Backend\dao\UsuarioMensajecampanaDAO;
use Backend\dao\UsuarioMensajeDAO;
use Backend\dto\UsuarioMensaje;
use Backend\dto\UsuarioMensajecampana;
use Backend\sql\QueryExecutor;
use Backend\sql\SqlQuery;
use Backend\sql\Transaction;
use Backend\sql\ConnectionProperty;

use PDO;
/**
 * Clase 'UsuarioMensajecampanaMySqlDAO'
 *
 * Esta clase provee las consultas del modelo o tabla 'UsuarioMensajeCampana'
 *
 * Ejemplo de uso:
 * $UsuarioMensajecampanaMySqlDAO = new UsuarioMensajecampanaMySqlDAO();
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class UsuarioMensajecampanaMySqlDAO implements UsuarioMensajecampanaDAO
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
        $sql = 'SELECT * FROM usuario_mensajecampana WHERE usumencampana_id = ?';
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
        $sql = 'SELECT * FROM usuario_mensajecampana';
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
    public function queryAllOrderBy($orderColumn)
    {
        $sql = 'SELECT * FROM usuario_mensajecampana ORDER BY ' . $orderColumn;
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
    public function delete($usumencampanaId)
    {
        $sql = 'DELETE FROM usuario_mensajecampana WHERE usumencampana_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($usumencampanaId);
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
    public function insert($usuarioMensajecampana)
    {
        $sql = 'INSERT INTO usuario_mensajecampana ( usufrom_id, usuto_id, is_read, msubject,body,parent_id, usucrea_id, usumodif_id,tipo,externo_id,proveedor_id,pais_id,fecha_expiracion,nombre,descripcion,t_value,mandante,fecha_envio,estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?)';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($usuarioMensajecampana->usufromId);
        $sqlQuery->set($usuarioMensajecampana->usutoId);
        $sqlQuery->set($usuarioMensajecampana->isRead);
        $sqlQuery->set($usuarioMensajecampana->msubject);
        $sqlQuery->set($usuarioMensajecampana->body);
        $sqlQuery->set($usuarioMensajecampana->parentId);
        $sqlQuery->setNumber($usuarioMensajecampana->usucreaId);
        $sqlQuery->setNumber($usuarioMensajecampana->usumodifId);
        $sqlQuery->set($usuarioMensajecampana->tipo);
        $sqlQuery->set($usuarioMensajecampana->externoId);
        $sqlQuery->set($usuarioMensajecampana->proveedorId);

        if ($usuarioMensajecampana->paisId == "") {
            $usuarioMensajecampana->paisId = 0;
        }
        $sqlQuery->set($usuarioMensajecampana->paisId);
        $sqlQuery->set($usuarioMensajecampana->fechaExpiracion);
        $sqlQuery->set($usuarioMensajecampana->nombre);
        $sqlQuery->set($usuarioMensajecampana->descripcion);
        $sqlQuery->set($usuarioMensajecampana->t_value);
        $sqlQuery->set($usuarioMensajecampana->mandante);
        $sqlQuery->set($usuarioMensajecampana->fechaEnvio);

        if ($usuarioMensajecampana->estado == "") {
            $usuarioMensajecampana->estado = 'I';
        }
        $sqlQuery->set($usuarioMensajecampana->estado);

        $id = $this->executeInsert($sqlQuery);
        $usuarioMensajecampana->usumencampanaId = $id;
        return $id;
    }

    /**
     * Editar un registro en la base de datos
     *
     * @param Objeto usuarioMandante usuarioMandante
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function update($usuarioMensajecampana)
    {
        $sql = 'UPDATE usuario_mensajecampana SET  usufrom_id = ?, usuto_id = ?, is_read = ?, msubject = ?, body = ?, parent_id=?,tipo=?,externo_id=?,proveedor_id=?,pais_id=?,fecha_expiracion = ?, nombre = ?, descripcion = ?, t_value = ?, mandante = ?, fecha_envio = ?, usumensaje_id = ? , estado = ? WHERE usumencampana_id = ?';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($usuarioMensajecampana->usufromId);
        $sqlQuery->set($usuarioMensajecampana->usutoId);
        $sqlQuery->set($usuarioMensajecampana->isRead);
        $sqlQuery->set($usuarioMensajecampana->msubject);
        $sqlQuery->set($usuarioMensajecampana->body);
        $sqlQuery->set($usuarioMensajecampana->parentId);
        $sqlQuery->set($usuarioMensajecampana->tipo);
        $sqlQuery->set($usuarioMensajecampana->externoId);
        $sqlQuery->set($usuarioMensajecampana->proveedorId);

        if ($usuarioMensajecampana->paisId == "") {
            $usuarioMensajecampana->paisId = 0;
        }
        $sqlQuery->set($usuarioMensajecampana->paisId);
        $sqlQuery->set($usuarioMensajecampana->fechaExpiracion);
        $sqlQuery->set($usuarioMensajecampana->nombre);
        $sqlQuery->set($usuarioMensajecampana->descripcion);
        $sqlQuery->set($usuarioMensajecampana->t_value);
        $sqlQuery->set($usuarioMensajecampana->mandante);
        $sqlQuery->set($usuarioMensajecampana->fechaEnvio);

        if ($usuarioMensajecampana->usumensajeId == "") {
            $usuarioMensajecampana->usumensajeId = 0;
        }
        $sqlQuery->set($usuarioMensajecampana->usumensajeId);

        if ($usuarioMensajecampana->estado == "") {
            $usuarioMensajecampana->estado = 'I';
        }
        $sqlQuery->set($usuarioMensajecampana->estado);

        $sqlQuery->setNumber($usuarioMensajecampana->usumencampanaId);
        return $this->executeUpdate($sqlQuery);
    }


    /**
     * Actualiza el estado de un registro en la tabla usuario_mensajecampana.
     *
     * @param object $usuarioMensajecampana Objeto que contiene los datos del usuario y la campaña de mensaje.
     * - isRead (boolean): Indica si el mensaje ha sido leído.
     * - usumodifId (int): ID del usuario que modifica el registro.
     * - estado (string): Estado del mensaje. Si está vacío, se establece a 'I'.
     * - usumencampanaId (int): ID del registro en la tabla usuario_mensajecampana.
     *
     * @return int Número de filas afectadas por la actualización.
     */
    public function updateEstado($usuarioMensajecampana)
    {
        $sql = 'UPDATE usuario_mensajecampana SET is_read = ?, usumodif_id = ? , estado = ?   WHERE usumencampana_id = ?';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->set($usuarioMensajecampana->isRead);
        $sqlQuery->set($usuarioMensajecampana->usumodifId);

        if ($usuarioMensajecampana->estado == "") {
            $usuarioMensajecampana->estado = 'I';
        }
        $sqlQuery->set($usuarioMensajecampana->estado);


        $sqlQuery->setNumber($usuarioMensajecampana->usumencampanaId);
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
        $sql = 'DELETE FROM usuario_mensajecampana';
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
        $sql = 'SELECT * FROM usuario_mensajecampana WHERE usufrom_id = ?';
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
        $sql = 'SELECT * FROM usuario_mensajecampana WHERE usuto_id = ?';
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
        $sql = 'SELECT * FROM usuario_mensajecampana WHERE is_read = ?';
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
        $sql = 'SELECT * FROM usuario_mensajecampana WHERE body = ?';
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
        $sql = 'SELECT * FROM usuario_mensajecampana WHERE fecha_crea = ?';
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
        $sql = 'SELECT * FROM usuario_mensajecampana WHERE usucrea_id = ?';
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
        $sql = 'SELECT * FROM usuario_mensajecampana WHERE fecha_modif = ?';
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
        $sql = 'SELECT * FROM usuario_mensajecampana WHERE usumodif_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);
    }
    /**
     * Obtener todos los registros donde se encuentre que
     * la columna nombre sea igual al valor pasado como parámetro
     *
     * @param String $value nombre requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByNombre($value)
    {
        $sql = 'SELECT * FROM usuario_mensajecampana WHERE nombre = ?';
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
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByDescripcion($value)
    {
        $sql = 'SELECT * FROM usuario_mensajecampana WHERE descripcion = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }
    /**
     * Obtener todos los registros donde se encuentre que
     * la columna t_value sea igual al valor pasado como parámetro
     *
     * @param String $value t_value requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByT_value($value)
    {
        $sql = 'SELECT * FROM usuario_mensajecampana WHERE t_value = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }
    /**
     * Obtener todos los registros donde se encuentre que
     * la columna mandante sea igual al valor pasado como parámetro
     *
     * @param String $value t_value requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByMandante($value)
    {
        $sql = 'SELECT * FROM usuario_mensajecampana WHERE mandante = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }
    /**
     * Obtener todos los registros donde se encuentre que
     * la columna mandante sea igual al valor pasado como parámetro
     *
     * @param String $value t_value requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByFechaEnvio($value)
    {
        $sql = 'SELECT * FROM usuario_mensajecampana WHERE fecha_envio = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
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
        $innUsutoUsuFrom=false;

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


                $cond="usufrom";
                if ( strpos($fieldName, $cond) !== false || strpos($sidx, $cond) !== false || strpos($grouping, $cond) !== false  || strpos($select, $cond) !== false)
                {
                    $innUsutoUsuFrom=true;
                }
                $cond="usuto";
                if ( strpos($fieldName, $cond) !== false || strpos($sidx, $cond) !== false || strpos($grouping, $cond) !== false  || strpos($select, $cond) !== false)
                {
                    $innUsutoUsuFrom=true;
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
                    case "isnull":
                        $fieldOperation = " IS NULL ";
                        break;
                    case "isnotnull":
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

        $leftUserSpecific = '';
        if ($userToSpecific != '') {
            $leftUserSpecific = " LEFT OUTER JOIN usuario_mensaje usuario_mensaje2 on (usuario_mensaje2.usumencampana_id = usuario_mensajecampana.usumencampana_id and usuario_mensaje2.usuto_id='" . $userToSpecific . "') ";
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


        $sql = "SELECT  /*+ MAX_EXECUTION_TIME(5000) */ count(*) count FROM usuario_mensajecampana   LEFT OUTER JOIN usuario_mandante usufrom ON (usufrom.usumandante_id = usuario_mensajecampana.usufrom_id) LEFT OUTER JOIN usuario_mandante usuto ON (usuto.usumandante_id = usuario_mensajecampana.usuto_id) LEFT OUTER JOIN pais ON (pais.pais_id = usuario_mensajecampana.pais_id)  " . $leftUserSpecific . $where;
        if ($grouping != "") {

            $where = $where . " GROUP BY " . $grouping;
        }
        $innerLeftUsutoId= " ";

        if($innUsutoUsuFrom){
            $innerLeftUsutoId= " LEFT OUTER JOIN usuario_mandante usufrom ON (usufrom.usumandante_id = usuario_mensajecampana.usufrom_id) LEFT OUTER JOIN usuario_mandante usuto ON (usuto.usumandante_id = usuario_mensajecampana.usuto_id) ";

        }

        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
        $sql = "SELECT  /*+ MAX_EXECUTION_TIME(5000) */ " . $select . " FROM usuario_mensajecampana  ".$innerLeftUsutoId." LEFT OUTER JOIN pais ON (pais.pais_id = usuario_mensajecampana.pais_id)  " . $leftUserSpecific . $where . " " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

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

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . ', "sql" : "' . $sql . '"}';

        return $json;
    }

    /**
     * Realiza una consulta personalizada sobre los mensajes de usuario en la campaña.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Campo por el cual se ordenará la consulta.
     * @param string $sord Orden de la consulta (ASC o DESC).
     * @param int $start Índice de inicio para la paginación.
     * @param int $limit Límite de registros para la paginación.
     * @param string $filters Filtros en formato JSON para aplicar a la consulta.
     * @param bool $searchOn Indica si se deben aplicar los filtros.
     * @param string $userToSpecific (Opcional) ID de usuario específico para filtrar.
     * @param string $grouping (Opcional) Campo por el cual se agruparán los resultados.
     *
     * @return string JSON con el conteo de registros y los datos resultantes de la consulta.
     */
    public function queryUsuarioMensajesCustom2($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $userToSpecific = '', $grouping = '')
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
                    case "isnull":
                        $fieldOperation = " IS NULL ";
                        break;
                    default:
                        $fieldOperation = "";
                        break;
                }
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
        $leftUserSpecific = '';
        if ($userToSpecific != '') {
            $leftUserSpecific = " LEFT OUTER JOIN usuario_mensajecampana usuario_mensaje2 on (usuario_mensaje2.parent_id = usuario_mensajecampana.usumencampana_id and usuario_mensaje2.usuto_id='" . $userToSpecific . "') ";
        }

        $sql = "SELECT count(*) count FROM usuario_mensajecampana   LEFT OUTER JOIN usuario_mandante usufrom ON (usufrom.usumandante_id = usuario_mensajecampana.usufrom_id) LEFT OUTER JOIN usuario_mandante usuto ON (usuto.usumandante_id = usuario_mensajecampana.usuto_id) LEFT OUTER JOIN pais ON (pais.pais_id = usuario_mensajecampana.pais_id)  " . $leftUserSpecific . $where;
        if ($grouping != "") {

            $where = $where . " GROUP BY " . $grouping;
        }


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);

        $sql = "SELECT " . $select . " FROM usuario_mensajecampana  LEFT OUTER JOIN usuario_mandante usufrom ON (usufrom.usumandante_id = usuario_mensajecampana.usufrom_id) LEFT OUTER JOIN usuario_mandante usuto ON (usuto.usumandante_id = usuario_mensajecampana.usuto_id) LEFT OUTER JOIN pais ON (pais.pais_id = usuario_mensajecampana.pais_id)" . $leftUserSpecific . $where . " " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
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
        $sql = 'DELETE FROM usuario_mensajecampana WHERE usufrom_id = ?';
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
        $sql = 'DELETE FROM usuario_mensajecampana WHERE usuto_id = ?';
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
        $sql = 'DELETE FROM usuario_mensajecampana WHERE is_read = ?';
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
        $sql = 'DELETE FROM usuario_mensajecampana WHERE body = ?';
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
        $sql = 'DELETE FROM usuario_mensajecampana WHERE fecha_crea = ?';
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
        $sql = 'DELETE FROM usuario_mensajecampana WHERE usucrea_id = ?';
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
        $sql = 'DELETE FROM usuario_mensajecampana WHERE fecha_modif = ?';
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
        $sql = 'DELETE FROM usuario_mensajecampana WHERE usumodif_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna nombre sea igual al valor pasado como parámetro
     *
     * @param String $value nombre requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByNombre($value)
    {
        $sql = 'DELETE FROM usuario_mensajecampana WHERE nombre = ?';
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
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByDescripcion($value)
    {
        $sql = 'DELETE FROM usuario_mensajecampana WHERE descripcion = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->executeUpdate($sqlQuery);
    }
    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna t_value sea igual al valor pasado como parámetro
     *
     * @param String $value t_value requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByT_value($value)
    {
        $sql = 'DELETE FROM usuario_mensajecampana WHERE t_value = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->executeUpdate($sqlQuery);
    }
    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna mandante sea igual al valor pasado como parámetro
     *
     * @param String $value t_value requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByMandante($value)
    {
        $sql = 'DELETE FROM usuario_mensajecampana WHERE mandante = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->executeUpdate($sqlQuery);
    }
    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna mandante sea igual al valor pasado como parámetro
     *
     * @param String $value t_value requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByFechaEnvio($value)
    {
        $sql = 'DELETE FROM usuario_mensajecampana WHERE fecha_envio = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->executeUpdate($sqlQuery);
    }
    /**
     * Crear y devolver un objeto del tipo UsuarioMensajeCampana
     * con los valores de una consulta sql
     *
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Objeto $usuarioMensajeCampana UsuarioMensajeCampana
     *
     * @access protected
     *
     */
    protected function readRow($row)
    {
        $usuarioMensajeCampana = new UsuarioMensajeCampana();

        $usuarioMensajeCampana->usumencampanaId = $row['usumencampana_id'];
        $usuarioMensajeCampana->usufromId = $row['usufrom_id'];
        $usuarioMensajeCampana->usutoId = $row['usuto_id'];
        $usuarioMensajeCampana->isRead = $row['is_read'];
        $usuarioMensajeCampana->msubject = $row['msubject'];
        $usuarioMensajeCampana->body = $row['body'];
        $usuarioMensajeCampana->parentId = $row['parent_id'];
        $usuarioMensajeCampana->fechaCrea = $row['fecha_crea'];
        $usuarioMensajeCampana->usucreaId = $row['usucrea_id'];
        $usuarioMensajeCampana->fechaModif = $row['fecha_modif'];
        $usuarioMensajeCampana->usumodifId = $row['usumodif_id'];
        $usuarioMensajeCampana->paisId = $row['pais_id'];
        $usuarioMensajeCampana->tipo = $row['tipo'];
        $usuarioMensajeCampana->externoId = $row['externo_id'];
        $usuarioMensajeCampana->proveedorId = $row['proveedor_id'];
        $usuarioMensajeCampana->fechaExpiracion = $row['fecha_expiracion'];
        $usuarioMensajeCampana->nombre = $row['nombre'];
        $usuarioMensajeCampana->descripcion = $row['descripcion'];
        $usuarioMensajeCampana->t_value = $row['t_value'];
        $usuarioMensajeCampana->fechaEnvio = $row['fecha_envio'];
        $usuarioMensajeCampana->usumensajeId = $row['usumensaje_id'];
        $usuarioMensajeCampana->estado = $row['estado'];

        return $usuarioMensajeCampana;
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
