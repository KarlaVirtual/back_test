<?php namespace Backend\mysql;
use Backend\dao\UsuarioBonoDAO;
use Backend\dto\Helpers;
use Backend\dto\UsuarioBono;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Exception;
use Backend\sql\ConnectionProperty;
use PDO;
/**
* Clase 'UsuarioBonoMySqlDAO'
*
* Esta clase provee las consultas del modelo o tabla 'UsuarioBono'
*
* Ejemplo de uso:
* $UsuarioBonoMySqlDAO = new UsuarioBonoMySqlDAO();
*
*
* @package ninguno
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public
* @see no
*
*/
class UsuarioBonoMySqlDAO implements UsuarioBonoDAO
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
    public function load($id)
    {
        $sql = 'SELECT * FROM usuario_bono WHERE usubono_id = ?';
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
        $sql = 'SELECT * FROM usuario_bono';
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
        $sql = 'SELECT * FROM usuario_bono ORDER BY ' . $orderColumn;
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }

    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $usubono_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function delete($usubono_id)
    {
        $sql = 'DELETE FROM usuario_bono WHERE usubono_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($usubono_id);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Insertar un registro en la base de datos
     *
     * @param Objeto usuario_bono usuario_bono
     *
     * @return String $id resultado de la consulta
     *
     */
    public function insert($usuario_bono)
    {

        $sql = 'INSERT INTO usuario_bono (usuario_id, bono_id, valor,valor_bono,valor_base, estado, usucrea_id, usumodif_id,mandante,error_id,id_externo,version,apostado,rollower_requerido,codigo,externo_id,premio, usuid_referido) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($usuario_bono->usuarioId);
        $sqlQuery->set($usuario_bono->bonoId);
        $sqlQuery->set($usuario_bono->valor);
        $sqlQuery->set($usuario_bono->valorBono);
        $sqlQuery->set($usuario_bono->valorBase);
        $sqlQuery->set($usuario_bono->estado);
        $sqlQuery->setNumber($usuario_bono->usucreaId);
        $sqlQuery->setNumber($usuario_bono->usumodifId);
        $sqlQuery->setNumber($usuario_bono->mandante);
        $sqlQuery->set($usuario_bono->errorId);
        $sqlQuery->set($usuario_bono->idExterno);
        $sqlQuery->set($usuario_bono->version);
        $sqlQuery->set($usuario_bono->apostado);
        $sqlQuery->set($usuario_bono->rollowerRequerido);
        $sqlQuery->set($usuario_bono->codigo);
        $sqlQuery->set($usuario_bono->externoId);
        if($usuario_bono->premio == ""){
            $usuario_bono->premio = 0;

        }
        $sqlQuery->set($usuario_bono->premio);
        if($usuario_bono->usuidReferido ==''){
            $usuario_bono->usuidReferido='0';
        }
        $sqlQuery->setNumber($usuario_bono->usuidReferido);

        $id = $this->executeInsert($sqlQuery);
        $usuario_bono->usubonoId = $id;
        return $id;
    }


    /**
     * Editar un registro en la base de datos
     *
     * @param Objeto usuario_bono usuario_bono
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function update($usuario_bono)
    {
        $sql = 'UPDATE usuario_bono SET usuario_id = ?, bono_id = ?, valor = ?, valor_bono = ?, valor_base = ?, estado = ?,  usucrea_id = ?,  usumodif_id = ?, mandante=?, error_id = ?, id_externo = ?, version = ?, apostado = ?, rollower_requerido = ?, codigo = ?, externo_id = ?, premio = ?, usuid_referido = ?, externo_bono = ? WHERE usubono_id = ?';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($usuario_bono->usuarioId);
        $sqlQuery->set($usuario_bono->bonoId);
        $sqlQuery->set($usuario_bono->valor);
        $sqlQuery->set($usuario_bono->valorBono);
        $sqlQuery->set($usuario_bono->valorBase);
        $sqlQuery->set($usuario_bono->estado);
        $sqlQuery->setNumber($usuario_bono->usucreaId);
        $sqlQuery->setNumber($usuario_bono->usumodifId);
        $sqlQuery->setNumber($usuario_bono->mandante);
        $sqlQuery->set($usuario_bono->errorId);


        if($usuario_bono->idExterno == ''){
            $sqlQuery->setSIN('null');
        }else{

            $sqlQuery->set($usuario_bono->idExterno);
        }


        if($usuario_bono->version == ''){
            $sqlQuery->setSIN('null');
        }else{
            $sqlQuery->set($usuario_bono->version);
        }

        $sqlQuery->set($usuario_bono->apostado);
        $sqlQuery->set($usuario_bono->rollowerRequerido);
        $sqlQuery->set($usuario_bono->codigo);

        //$usuario_bono->externoBono='';
        if(strlen($usuario_bono->externoId)>=10){
            //$usuario_bono->externoBono=$usuario_bono->externoId;
        }

        if(strlen($usuario_bono->externoId)>=10){
            syslog(LOG_WARNING, " BONOLENINC : USUBONID" . $usuario_bono->usubonoId .' '. $usuario_bono->externoId . json_encode($_SERVER) . json_encode($_REQUEST) );
          //  $usuario_bono->externoId='0';
        }

        $sqlQuery->set($usuario_bono->externoId);

        if($usuario_bono->premio == ""){
            $usuario_bono->premio = 0;

        }
        if($usuario_bono->usuidReferido ==''){
            $usuario_bono->usuidReferido='0';
        }
        $sqlQuery->setNumber($usuario_bono->usuidReferido);
        $sqlQuery->set($usuario_bono->premio);
        $sqlQuery->set($usuario_bono->externoBono);


        $sqlQuery->setNumber($usuario_bono->usubonoId);
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
        $sql = 'DELETE FROM usuario_bono';
        $sqlQuery = new SqlQuery($sql);
        return $this->executeUpdate($sqlQuery);
    }













    /**
     * Obtener todos los registros donde se encuentre que
     * la columna estado sea igual al valor pasado como parámetro
     *
     * @param String $value estado requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByEstado($value)
    {
        $sql = 'SELECT * FROM usuario_bono WHERE estado = ?';
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
        $sql = 'SELECT * FROM usuario_bono WHERE fecha_crea = ?';
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
        $sql = 'SELECT * FROM usuario_bono WHERE usucrea_id = ?';
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
    public function queryByUsumodifId($value)
    {
        $sql = 'SELECT * FROM usuario_bono WHERE usumodif_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * las colomunas usuario_id y banco_id sean iguales
     * a los valores pasados como parámetros
     *
     * @param String $value usuario_id requerido
     * @param String $value banco_id requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByUsuarioIdAndBonoId($usuarioId,$bonoId)
    {
        $sql = 'SELECT * FROM usuario_bono WHERE usuario_id = ? AND bono_id =?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($usuarioId);
        $sqlQuery->setNumber($bonoId);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * las colomunas usuario_id y banco_id sean iguales
     * a los valores pasados como parámetros
     *
     * @param String $value usuario_id requerido
     * @param String $value banco_id requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByUsuarioIdAndExternoIdAndEstado($usuarioId, $externoId, $estado) //Agregar esta funcion en produccion
    {
        $sql = 'SELECT * FROM usuario_bono WHERE usuario_id = ? AND externo_id =? AND estado =? ORDER BY usubono_id DESC LIMIT 1';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($usuarioId);
        $sqlQuery->setNumber($externoId);
        $sqlQuery->set($estado);
        return $this->getList($sqlQuery);
    }




    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna banco_id sea igual al valor pasado como parámetro
     *
     * @param String $value banco_id requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByBannerId($value)
    {
        $sql = 'DELETE FROM usuario_bono WHERE bono_id = ?';
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
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByEstado($value)
    {
        $sql = 'DELETE FROM usuario_bono WHERE estado = ?';
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
        $sql = 'DELETE FROM usuario_bono WHERE fecha_crea = ?';
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
        $sql = 'DELETE FROM usuario_bono WHERE usucrea_id = ?';
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
    public function deleteByUsumodifId($value)
    {
        $sql = 'DELETE FROM usuario_bono WHERE usumodif_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->executeUpdate($sqlQuery);
    }












    /**
     * Crear y devolver un objeto del tipo UsuarioBono
     * con los valores de una consulta sql
     *
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Objeto $usuario_bono UsuarioBono
     *
     * @access protected
     *
     */
    protected function readRow($row)
    {
        $usuario_bono = new UsuarioBono();

        $usuario_bono->usubonoId = $row['usubono_id'];
        $usuario_bono->usuarioId = $row['usuario_id'];
        $usuario_bono->bonoId = $row['bono_id'];
        $usuario_bono->valor = $row['valor'];
        $usuario_bono->valorBono = $row['valor_bono'];
        $usuario_bono->valorBase = $row['valor_base'];
        $usuario_bono->estado = $row['estado'];
        $usuario_bono->fechaCrea = $row['fecha_crea'];
        $usuario_bono->usucreaId = $row['usucrea_id'];
        $usuario_bono->fechaModif = $row['fecha_modif'];
        $usuario_bono->usumodifId = $row['usumodif_id'];
        $usuario_bono->mandante = $row['mandante'];
        $usuario_bono->errorId = $row['error_id'];
        $usuario_bono->idExterno = $row['id_externo'];
        $usuario_bono->version = $row['version'];
        $usuario_bono->apostado = $row['apostado'];
        $usuario_bono->rollowerRequerido = $row['rollower_requerido'];
        $usuario_bono->codigo = $row['codigo'];
        $usuario_bono->externoId = $row['externo_id'];
        $usuario_bono->premio = $row['premio'];
        $usuario_bono->fechaExpiracion = $row['fecha_expiracion'];
        $usuario_bono->usuidReferido = $row['usuid_referido'];
        $usuario_bono->externoBono = $row['externo_bono'];

        return $usuario_bono;
    }




    /**
    * Realizar una consulta en la tabla de UsuarioBono 'UsuarioBono'
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
    public function queryUsuarioBonos($sidx, $sord, $start, $limit, $filters, $searchOn)
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


        if($where == " where 1=1 "){
            $json = '{ "count" : {}, "data" : []}';

            return $json;

        }

        $sql = 'SELECT count(*) count FROM proveedor LEFT OUTER JOIN usuario_bono ON (usuario_bono.proveedor_id = proveedor.proveedor_id)' . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT proveedor.*,usuario_bono.* FROM proveedor LEFT OUTER JOIN usuario_bono ON (usuario_bono.proveedor_id = proveedor.proveedor_id)' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }

    /**
    * Realizar una consulta en la tabla de UsuarioBono 'UsuarioBono'
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
    public function queryUsuarioBonosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$grouping="",$withIdBonoDetalle="",$withIdBonoDetalle2="", $onlyCount = false)
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


        if($where == " where 1=1 "){
            $json = '{ "count" : {}, "data" : []}';

            return $json;

        }

        if($grouping != ""){
            $where = $where . " GROUP BY " . $grouping;
        }

        $leftQuery="";

        if($withIdBonoDetalle != ""){
            $leftQuery .= " LEFT OUTER JOIN bono_detalle ON (bono_interno.bono_id = bono_detalle.bono_id AND bono_detalle.tipo='".$withIdBonoDetalle."') ";
        }

        if($withIdBonoDetalle2 != ""){
            $leftQuery .= " LEFT OUTER JOIN bono_detalle bono_detalle2 ON (bono_interno.bono_id = bono_detalle2.bono_id AND bono_detalle2.tipo IN (".$withIdBonoDetalle2.") ) ";
        }

        if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ){
            $connOriginal = $_ENV["connectionGlobal"]->getConnection();




            try{

                $connDB5 = null;
                $connDB5 = new \PDO("mysql:host=db5.local;dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword(), array(
                    PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-bundle.crt',
                    PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
                ));

                $connDB5->exec("set names utf8");
                $connDB5->exec("set use_secondary_engine=off");

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
                $_ENV["connectionGlobal"]->setConnection($connDB5);
                $this->transaction->setConnection($_ENV["connectionGlobal"]);

            }catch (\Exception $e){

            }

        }

        $sql = 'SELECT  /*+ MAX_EXECUTION_TIME(50000) */  count(*) count FROM usuario_bono INNER JOIN bono_interno ON (bono_interno.bono_id = usuario_bono.bono_id) LEFT OUTER JOIN usuario ON (usuario.usuario_id = usuario_bono.usuario_id) LEFT OUTER JOIN bono_log ON (bono_log.id_externo = usuario_bono.usubono_id AND bono_log.estado="W")  ' . $leftQuery . $where;





        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);

        if ($onlyCount) return '{"count":'.json_encode($count[0]).'}';

        $sql = 'SELECT  /*+ MAX_EXECUTION_TIME(50000) */  ' .$select .'  FROM usuario_bono INNER JOIN bono_interno ON (bono_interno.bono_id = usuario_bono.bono_id)  LEFT OUTER JOIN usuario ON (usuario.usuario_id = usuario_bono.usuario_id) LEFT OUTER JOIN bono_log ON (bono_log.id_externo = usuario_bono.usubono_id AND bono_log.estado="W") ' . $leftQuery . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

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

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }

    /**
     * Realizar una consulta en la tabla de UsuarioBono 'UsuarioBono'
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
    public function queryUsuarioBonosNoAltenarCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$grouping="",$withIdBonoDetalle="",$withIdBonoDetalle2="", $onlyCount = false)
    {

        //Ajustamos el where para retornar los bonos que no esten linkeados a altenar y que su estado sea pendiente o asignado
        $where = "where 1=1 AND ((usuario_bono.estado ) = 'P' OR (usuario_bono.estado ) = 'A')  AND  NOT EXISTS (SELECT 1 FROM bono_detalle WHERE bono_detalle.bono_id = usuario_bono.bono_id AND (bono_detalle.tipo = 'BONUSPLANIDALTENAR' or bono_detalle.tipo = 'BONUSCODEALTENAR' )) ";

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


        if($where == " where 1=1 "){
            $json = '{ "count" : {}, "data" : []}';

            return $json;

        }

        if($grouping != ""){
            $where = $where . " GROUP BY " . $grouping;
        }

        $leftQuery="";

        if($withIdBonoDetalle != ""){
            $leftQuery .= " LEFT OUTER JOIN bono_detalle ON (bono_interno.bono_id = bono_detalle.bono_id AND bono_detalle.tipo='".$withIdBonoDetalle."') ";
        }

        if($withIdBonoDetalle2 != ""){
            $leftQuery .= " LEFT OUTER JOIN bono_detalle bono_detalle2 ON (bono_interno.bono_id = bono_detalle2.bono_id AND bono_detalle2.tipo IN (".$withIdBonoDetalle2.") ) ";
        }

        if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ){
            $connOriginal = $_ENV["connectionGlobal"]->getConnection();




            try{

                $connDB5 = null;
                $connDB5 = new \PDO("mysql:host=db5.local;dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword(), array(
                    PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-bundle.crt',
                    PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
                ));

                $connDB5->exec("set names utf8");
                $connDB5->exec("set use_secondary_engine=off");

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
                $_ENV["connectionGlobal"]->setConnection($connDB5);
                $this->transaction->setConnection($_ENV["connectionGlobal"]);

            }catch (\Exception $e){

            }

        }

        $sql = 'SELECT  /*+ MAX_EXECUTION_TIME(50000) */  count(*) count FROM usuario_bono INNER JOIN bono_interno ON (bono_interno.bono_id = usuario_bono.bono_id) LEFT OUTER JOIN usuario ON (usuario.usuario_id = usuario_bono.usuario_id) LEFT OUTER JOIN bono_log ON (bono_log.id_externo = usuario_bono.usubono_id AND bono_log.estado="W")  ' . $leftQuery . $where;

        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);

        if ($onlyCount) return '{"count":'.json_encode($count[0]).'}';

        $sql = 'SELECT  /*+ MAX_EXECUTION_TIME(50000) */  ' .$select .'  FROM usuario_bono INNER JOIN bono_interno ON (bono_interno.bono_id = usuario_bono.bono_id)  LEFT OUTER JOIN usuario ON (usuario.usuario_id = usuario_bono.usuario_id) LEFT OUTER JOIN bono_log ON (bono_log.id_externo = usuario_bono.usubono_id AND bono_log.estado="W") ' . $leftQuery . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        if($_ENV["debugFixed2"] == '1'){
            print_r($sql);
        }
        $sqlQuery = new SqlQuery($sql);


        if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ) {
            $connDB5 = null;
            $_ENV["connectionGlobal"]->setConnection($connOriginal);
            $this->transaction->setConnection($_ENV["connectionGlobal"]);
        }

        return $this->getList($sqlQuery);
    }


    /**
     * Consulta personalizada de bonos de usuario.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Índice de ordenación.
     * @param string $sord Orden de clasificación (ASC o DESC).
     * @param int $start Inicio de los resultados.
     * @param int $limit Límite de resultados.
     * @param string $filters Filtros en formato JSON.
     * @param bool $searchOn Indica si la búsqueda está activada.
     * @param string $grouping Agrupación de resultados.
     * @param string $withIdBonoDetalle Filtro adicional para bono_detalle.
     * @param string $withIdBonoDetalle2 Filtro adicional para bono_detalle2.
     * @return string JSON con el conteo y los datos resultantes de la consulta.
     */
    public function queryUsuarioBonosCustom3($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$grouping="",$withIdBonoDetalle="",$withIdBonoDetalle2="")
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


        if($where == " where 1=1 "){
            $json = '{ "count" : {}, "data" : []}';

            return $json;

        }

        if($grouping != ""){
            $where = $where . " GROUP BY " . $grouping;
        }

        $leftQuery="";

        if($withIdBonoDetalle != ""){
            $leftQuery .= " LEFT OUTER JOIN bono_detalle ON (bono_interno.bono_id = bono_detalle.bono_id AND bono_detalle.tipo='".$withIdBonoDetalle."') ";
        }

        if($withIdBonoDetalle2 != ""){
            $leftQuery .= " LEFT OUTER JOIN bono_detalle bono_detalle2 ON (bono_interno.bono_id = bono_detalle2.bono_id AND bono_detalle2.tipo IN (".$withIdBonoDetalle2.") ) ";
        }

        $sql = 'SELECT  /*+ MAX_EXECUTION_TIME(50000) */  count(*) count FROM usuario_bono INNER JOIN bono_interno ON (bono_interno.bono_id = usuario_bono.bono_id) LEFT OUTER JOIN usuario ON (usuario.usuario_id = usuario_bono.usuario_id) LEFT OUTER JOIN bono_log ON (bono_log.id_externo = usuario_bono.usubono_id AND bono_log.estado="W")  ' . $leftQuery . $where;

        if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ){
            $connOriginal = $_ENV["connectionGlobal"]->getConnection();




            try{

                $connDB5 = null;
                $connDB5 = new \PDO("mysql:host=db5.local;dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword(), array(
                    PDO::ATTR_TIMEOUT => 5, // in seconds
                    PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-bundle.crt',
                    PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
                ));

                $connDB5->exec("set names utf8");
                $connDB5->exec("set use_secondary_engine=off");

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
                $_ENV["connectionGlobal"]->setConnection($connDB5);
                $this->transaction->setConnection($_ENV["connectionGlobal"]);

            }catch (\Exception $e){

            }
        }




        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT  /*+ MAX_EXECUTION_TIME(50000) */  ' .$select .'  FROM usuario_bono INNER JOIN bono_interno ON (bono_interno.bono_id = usuario_bono.bono_id)  LEFT OUTER JOIN usuario ON (usuario.usuario_id = usuario_bono.usuario_id) LEFT OUTER JOIN bono_log ON (bono_log.id_externo = usuario_bono.usubono_id AND bono_log.estado="W") ' . $leftQuery . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

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

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }

    /**
     * Realiza una consulta personalizada sobre la tabla usuario_bono con diversas opciones de filtrado, ordenación y paginación.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Campo por el cual se ordenarán los resultados.
     * @param string $sord Orden de los resultados (ASC o DESC).
     * @param int $start Índice de inicio para la paginación.
     * @param int $limit Número de registros a devolver.
     * @param string $filters Filtros en formato JSON para construir la cláusula WHERE.
     * @param bool $searchOn Indica si se deben aplicar los filtros.
     * @param string $grouping Campo por el cual se agruparán los resultados (opcional).
     * @return string JSON con el conteo de registros y los datos resultantes de la consulta.
     */
    public function queryUsuarioBonoCustom2($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping="")
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
                if($fieldOperation != "") $whereArray[] = $fieldName.$fieldOperation;
                if (oldCount($whereArray)>0)
                {
                    $where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
                }
                else
                {
                    $where = "";
                }
            }

        }

        $sql = "SELECT count(*) count FROM usuario_bono INNER JOIN bono_interno ON (bono_interno.bono_id = usuario_bono.bono_id) INNER JOIN mandante ON (usuario_bono.mandante = mandante.mandante) " . $where;

        $sqlQuery = new SqlQuery($sql);


        if($grouping != ""){
            $where = $where . " GROUP BY " . $grouping;
        }




        $count = $this->execute2($sqlQuery);
        $sql = "SELECT ".$select." FROM usuario_bono INNER JOIN bono_interno ON (bono_interno.bono_id = usuario_bono.bono_id) INNER JOIN mandante ON (usuario_bono.mandante = mandante.mandante)  " . $where ." " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : '.json_encode($count). ', "data" : '.json_encode($result).'}';

        return  $json;
    }

    /**
    * Realizar una consulta en la tabla de UsuarioBono 'UsuarioBono'
    * de una manera personalizada
    *
    * @param String $select campos de consulta
    * @param String $sidx columna para ordenar
    * @param String $sord orden los datos asc | desc
    * @param String $start inicio de la consulta
    * @param String $limit limite de la consulta
    * @param String $filters condiciones de la consulta
    * @param boolean $search utilizar los filtros o no
    * @param boolean $partnerId partnerId
    *
    * @return Array $json resultado de la consulta
    *
    */
    public function getAllUsuarioBonos($value, $category, $provider, $offset, $limit, $search, $partnerId)
    {
        $where = " 1=1 ";
        if ($category != "") {
            $where = $where . " AND categoria_usuario_bono.categoria_id= ? ";
        }

        if ($provider != "") {
            $where = $where . " AND proveedor.abreviado = ? ";
        }

        if ($search != "") {
            $where = $where . " AND usuario_bono.descripcion  LIKE '%" . $search . "%' ";
        }

        if ($offset == "" || $limit == "") {
            $limit = 15;
            $offset = 0;
        }


        $sql = 'SELECT proveedor.*,usuario_bono.*,usuario_bono_mandante.*, categoria_usuario_bono.*,categoria.*,usuario_bono_detalle.p_value background FROM proveedor
        LEFT OUTER JOIN usuario_bono ON (usuario_bono.proveedor_id = proveedor.proveedor_id)
INNER JOIN categoria_usuario_bono ON (categoria_usuario_bono.usubono_id = usuario_bono.usubono_id)
INNER JOIN usuario_bono_mandante ON (usuario_bono.usubono_id = usuario_bono_mandante.usubono_id AND usuario_bono_mandante.mandante = ' . $partnerId . ' ) LEFT OUTER JOIN categoria ON (categoria.categoria_id =categoria_usuario_bono.categoria_id )
LEFT OUTER JOIN usuario_bono_detalle ON (usuario_bono_detalle.usubono_id= usuario_bono.usubono_id AND usuario_bono_detalle.p_key = "IMAGE_BACKGROUND")
 WHERE ' . $where . ' AND proveedor.Tipo = ? AND proveedor.estado="A" AND usuario_bono.estado="A" AND usuario_bono.mostrar="S" LIMIT ' . ($limit - $offset) . ' OFFSET ' . $offset;

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
     * Actualiza los giros de un bono de usuario.
     *
     * @param object $UsuarioBono Objeto que contiene la información del bono de usuario.
     * @param string $Giros Cantidad de giros a actualizar. Valor por defecto es una cadena vacía.
     * @param bool $withValidation Indica si se debe realizar la validación de giros. Valor por defecto es false.
     * @return bool Devuelve true si la actualización fue exitosa, false en caso contrario.
     */
    public function updateGiros($UsuarioBono,$Giros="",$withValidation=false)
    {
        $fields="1=1";
        $where ="";

        if($Giros != ""){
            $fields .= ", apostado = apostado -'".$Giros."' ";

            if($withValidation){
                $where .= " AND apostado -'".$Giros."' >= -0.01 ";
            }
        }


        if($fields != "1=1"){
            $fields = str_replace("1=1,","",$fields);
            $sql = 'UPDATE usuario_bono SET ' . $fields.' WHERE usubono_id = ? '.$where;

            $sqlQuery = new SqlQuery($sql);

            $sqlQuery->set($UsuarioBono->usubonoId);
            return $this->executeUpdate($sqlQuery);
        }
        return false;
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * las colomunas bono_id sea igual y estado sea diferente
     * a los valores pasados como parámetros
     *
     * @param String $value bono_id requerido
     * @param String $value estado requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByBonoIdAndEstado($bonoId,$estado)
    {
        $sql = 'SELECT * FROM usuario_bono WHERE bono_id = ? AND estado !=?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($bonoId);
        $sqlQuery->set($estado);
        return $this->getList($sqlQuery);
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

?>
