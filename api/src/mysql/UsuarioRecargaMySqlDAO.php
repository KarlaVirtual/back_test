<?php namespace Backend\mysql;

use Backend\dao\UsuarioRecargaDAO;
use Backend\dto\UsuarioRecarga;
use Backend\sql\QueryExecutor;
use Backend\sql\SqlQuery;
use Backend\sql\Transaction;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Helpers;

/**
 * Clase 'UsuarioRecargaMySqlDAO'
 *
 * Esta clase provee las consultas del modelo o tabla 'UsuarioRecarga'
 *
 * Ejemplo de uso:
 * $UsuarioRecargaMySqlDAO = new UsuarioRecargaMySqlDAO();
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class UsuarioRecargaMySqlDAO implements UsuarioRecargaDAO
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
        $sql = 'SELECT * FROM usuario_recarga WHERE recarga_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($id);
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
        $sql = 'SELECT * FROM usuario_recarga';
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }


    /**
     * Ejecutar una consulta sql
     *
     *
     * @param String $sql consulta sql
     *
     * @return Array $ resultado de la ejecución
     *
     * @access protected
     *
     */
    public function querySQL($sql)
    {
        $Helpers = new Helpers();
        $sqlQuery = new SqlQuery($sql);
        return $Helpers->process_data($this->execute2($sqlQuery));
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
        $sql = 'SELECT * FROM usuario_recarga ORDER BY ' . $orderColumn;
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }

    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $recarga_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function delete($recarga_id)
    {
        $sql = 'DELETE FROM usuario_recarga WHERE recarga_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($recarga_id);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Insertar un registro en la base de datos
     *
     * @param Objeto usuarioRecarga usuarioRecarga
     *
     * @return String $id resultado de la consulta
     *
     */
    public function insert($usuarioRecarga)
    {

        $sql = 'INSERT INTO usuario_recarga (usuario_id, fecha_crea, puntoventa_id, valor, impuesto, porcen_regalo_recarga, mandante, pedido, dir_ip, promocional_id, valor_promocional, host, porcen_iva, mediopago_id, valor_iva,estado,version,fecha_elimina,usuelimina_id,tiene_comision) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)';
        $sqlQuery = new SqlQuery($sql);

        //$sqlQuery->set($usuarioRecarga->recargaId);
        $sqlQuery->set($usuarioRecarga->usuarioId);
        $sqlQuery->set($usuarioRecarga->fechaCrea);
        $sqlQuery->set($usuarioRecarga->puntoventaId);
        $sqlQuery->set($usuarioRecarga->valor);

        if($usuarioRecarga->impuesto == ""){
            $usuarioRecarga->impuesto='0';
        }

        $sqlQuery->set($usuarioRecarga->impuesto);
        $sqlQuery->set($usuarioRecarga->porcenRegaloRecarga);
        $sqlQuery->set($usuarioRecarga->mandante);
        $sqlQuery->set($usuarioRecarga->pedido);
        $sqlQuery->set($usuarioRecarga->dirIp);
        $sqlQuery->set($usuarioRecarga->promocionalId);
        $sqlQuery->set($usuarioRecarga->valorPromocional);
        $sqlQuery->set($usuarioRecarga->host);
        $sqlQuery->set($usuarioRecarga->porcenIva);
        $sqlQuery->set($usuarioRecarga->mediopagoId);
        $sqlQuery->set($usuarioRecarga->valorIva);
        $sqlQuery->set($usuarioRecarga->estado);

        if ($usuarioRecarga->version == "") {
            $usuarioRecarga->version = 1;
        }

        $sqlQuery->set($usuarioRecarga->version);

        if($usuarioRecarga->fechaElimina == ''){
            $sqlQuery->setSIN('null');
        }else{
            $sqlQuery->set($usuarioRecarga->fechaElimina);
        }


        if ($usuarioRecarga->usueliminaId == "") {
            $usuarioRecarga->usueliminaId = 0;
        }
        $sqlQuery->set($usuarioRecarga->usueliminaId);
        $sqlQuery->set($usuarioRecarga->tieneComision);

        $id = $this->executeInsert($sqlQuery);
        $usuarioRecarga->recargaId = $id;
        return $id;
    }

    /**
     * Editar un registro en la base de datos
     *
     * @param Objeto usuarioRecarga usuarioRecarga
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function update($usuarioRecarga)
    {
        $sql = 'UPDATE usuario_recarga SET usuario_id = ?, fecha_crea = ?, puntoventa_id = ?, valor = ?, impuesto = ?, porcen_regalo_recarga = ?, mandante = ?, pedido = ?, dir_ip = ?, promocional_id = ?, valor_promocional = ?, host = ?, porcen_iva = ?, mediopago_id = ?, valor_iva = ?, estado = ?, version = ?,fecha_elimina = ?,usuelimina_id = ?,tiene_comision = ? WHERE recarga_id = ?';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->set($usuarioRecarga->usuarioId);
        $sqlQuery->set($usuarioRecarga->fechaCrea);
        $sqlQuery->set($usuarioRecarga->puntoventaId);
        $sqlQuery->set($usuarioRecarga->valor);

        if($usuarioRecarga->impuesto == ""){
            $usuarioRecarga->impuesto='0';
        }

        $sqlQuery->set($usuarioRecarga->impuesto);
        $sqlQuery->set($usuarioRecarga->porcenRegaloRecarga);
        $sqlQuery->set($usuarioRecarga->mandante);
        $sqlQuery->set($usuarioRecarga->pedido);
        $sqlQuery->set($usuarioRecarga->dirIp);
        $sqlQuery->set($usuarioRecarga->promocionalId);
        $sqlQuery->set($usuarioRecarga->valorPromocional);
        $sqlQuery->set($usuarioRecarga->host);
        $sqlQuery->set($usuarioRecarga->porcenIva);
        $sqlQuery->set($usuarioRecarga->mediopagoId);
        $sqlQuery->set($usuarioRecarga->valorIva);
        $sqlQuery->set($usuarioRecarga->estado);

        if ($usuarioRecarga->version == "") {
            $usuarioRecarga->version = 1;
        }

        $sqlQuery->set($usuarioRecarga->version);

        if($usuarioRecarga->fechaElimina == ''){
            $sqlQuery->setSIN('null');
        }else{
            $sqlQuery->set($usuarioRecarga->fechaElimina);
        }


        if ($usuarioRecarga->usueliminaId == "") {
            $usuarioRecarga->usueliminaId = 0;
        }
        $sqlQuery->set($usuarioRecarga->usueliminaId); 
        $sqlQuery->set($usuarioRecarga->tieneComision);

        $sqlQuery->set($usuarioRecarga->recargaId);
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
        $sql = 'DELETE FROM usuario_recarga';
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
    public function queryByUsuarioId($value)
    {
        $sql = 'SELECT * FROM usuario_recarga WHERE usuario_id = ?';
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
        $sql = 'SELECT * FROM usuario_recarga WHERE fecha_crea = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna puntoventa_id sea igual al valor pasado como parámetro
     *
     * @param String $value puntoventa_id requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByPuntoventaId($value)
    {
        $sql = 'SELECT * FROM usuario_recarga WHERE puntoventa_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos valor registros donde se encuentre que
     * la columna nombres sea igual al valor pasado como parámetro
     *
     * @param String $value valor requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByValor($value)
    {
        $sql = 'SELECT * FROM usuario_recarga WHERE valor = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna porcen_regalo_recarga sea igual al valor pasado como parámetro
     *
     * @param String $value porcen_regalo_recarga requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByPorcenRegaloRecarga($value)
    {
        $sql = 'SELECT * FROM usuario_recarga WHERE porcen_regalo_recarga = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna mandante sea igual al valor pasado como parámetro
     *
     * @param String $value mandante requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByMandante($value)
    {
        $sql = 'SELECT * FROM usuario_recarga WHERE mandante = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna pedido sea igual al valor pasado como parámetro
     *
     * @param String $value pedido requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByPedido($value)
    {
        $sql = 'SELECT * FROM usuario_recarga WHERE pedido = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna dir_ip sea igual al valor pasado como parámetro
     *
     * @param String $value dir_ip requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByDirIp($value)
    {
        $sql = 'SELECT * FROM usuario_recarga WHERE dir_ip = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna promocional_id sea igual al valor pasado como parámetro
     *
     * @param String $value promocional_id requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByPromocionalId($value)
    {
        $sql = 'SELECT * FROM usuario_recarga WHERE promocional_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna valor_promocional sea igual al valor pasado como parámetro
     *
     * @param String $value valor_promocional requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByValorPromocional($value)
    {
        $sql = 'SELECT * FROM usuario_recarga WHERE valor_promocional = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna host sea igual al valor pasado como parámetro
     *
     * @param String $value host requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByHost($value)
    {
        $sql = 'SELECT * FROM usuario_recarga WHERE host = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna porcen_iva sea igual al valor pasado como parámetro
     *
     * @param String $value porcen_iva requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByPorcenIva($value)
    {
        $sql = 'SELECT * FROM usuario_recarga WHERE porcen_iva = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna mediopago_id sea igual al valor pasado como parámetro
     *
     * @param String $value mediopago_id requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByMediopagoId($value)
    {
        $sql = 'SELECT * FROM usuario_recarga WHERE mediopago_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna valor_iva sea igual al valor pasado como parámetro
     *
     * @param String $value valor_iva requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByValorIva($value)
    {
        $sql = 'SELECT * FROM usuario_recarga WHERE valor_iva = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }


    /**
     * Realizar una consulta en la tabla de UsuarioRecarga 'UsuarioRecarga'
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
    public function queryUsuarioRecargas($sidx, $sord, $start, $limit, $filters, $searchOn)
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

        $sql = "SELECT  /*+ MAX_EXECUTION_TIME(50000) */ count(*) count FROM usuario_recarga INNER JOIN usuario ON (usuario_recarga.usuario_id=usuario.usuario_id) " . $where;

        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
        $sql = "SELECT  /*+ MAX_EXECUTION_TIME(50000) */ usuario_recarga.*,usuario.* FROM usuario_recarga INNER JOIN usuario ON (usuario_recarga.usuario_id=usuario.usuario_id)  " . $where . " " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);

        $result = $Helpers->process_data($this->execute2($sqlQuery));

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }

    /**
     * Realizar una consulta en la tabla de UsuarioRecarga 'UsuarioRecarga'
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
    public function queryUsuarioRecargasCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping, $having = "",$withCount=true)
    {

        $where = " where 1=1 ";

        $innertime_dimension=false;
        $innusuario_mandante=false;

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

                $cond="time_dimension";
                if ( strpos($fieldName, $cond) !== false || strpos($sidx, $cond) !== false || strpos($grouping, $cond) !== false  || strpos($select, $cond) !== false)
                {
                    $innertime_dimension=true;
                }

                $cond="usuario_mandante";

                if ( strpos($fieldName, $cond) !== false || strpos($sidx, $cond) !== false|| strpos($grouping, $cond) !== false || strpos($select, $cond) !== false)
                {
                    $innusuario_mandante=true;
                }

                

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

        if ($having != null) {

            // Construye el where
            $filtershaving = json_decode($having);
            $havingArray = array();
            $ruleshaving = $filtershaving->rules;
            $groupOperation = $filtershaving->groupOp;
            $cont = 0;
            $having = ' 1=1 ';
            foreach ($ruleshaving as $rule) {
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
                    default:
                        $fieldOperation = "";
                        break;
                }
                if ($fieldOperation != "") $havingArray[] = $fieldName . $fieldOperation;
                if (oldCount($havingArray) > 0) {
                    $having = $having . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
                } else {
                    $having = "";
                }
            }
        }

        if ($grouping != "") {
            $where = $where . " GROUP BY " . $grouping;
        }

        if ($having != "") {
            $where = $where . " HAVING " . $having;
        }

        $cond="time_dimension";
        if ( strpos($fieldName, $cond) !== false || strpos($sidx, $cond) !== false || strpos($grouping, $cond) !== false  || strpos($select, $cond) !== false)
        {
            $innertime_dimension=true;
        }


        $innerjointime_dimension ="";

        if($innertime_dimension){
            $innerjointime_dimension =" inner join time_dimension force index (time_dimension_timestampint_timestr_index)
                    on (time_dimension.timestr = usuario_recarga.fecha_crea) ";
            $innerjointime_dimension ="";

        }

        if($withCount){

            $sql = "SELECT  /*+ MAX_EXECUTION_TIME(50000) */ count(*) count FROM usuario_recarga
              ".$innerjointime_dimension." ";


            if($innusuario_mandante){
                $sql .= " "."JOIN usuario_mandante ON (usuario_mandante.usuario_mandante = usuario_recarga.usuario_id)";
            }
            $sql .= " INNER JOIN usuario ON (usuario_recarga.usuario_id=usuario.usuario_id)  LEFT OUTER JOIN usuario usuario_punto ON (usuario_recarga.puntoventa_id = usuario_punto.usuario_id) LEFT OUTER JOIN  concesionario ON (usuario_punto.puntoventa_id=concesionario.usuhijo_id AND prodinterno_id=0 AND concesionario.estado='A') LEFT OUTER JOIN punto_venta ON (usuario_recarga.puntoventa_id = punto_venta.usuario_id) LEFT OUTER JOIN transaccion_producto ON (transaccion_producto.final_id=usuario_recarga.recarga_id) LEFT OUTER JOIN  producto ON (producto.producto_id=transaccion_producto.producto_id) LEFT OUTER JOIN  proveedor ON (producto.proveedor_id=proveedor.proveedor_id)   " . $where;

            $sqlQuery = new SqlQuery($sql);
            if($_ENV["debugFixed2"] == '1'){
                print_r($sql);

            }else{
                $count = $this->execute2($sqlQuery);

            }

        }
        $sql = "SELECT  /*+ MAX_EXECUTION_TIME(50000) */ " . $select . " FROM usuario_recarga 
              ".$innerjointime_dimension." ";



            if($innusuario_mandante){
                $sql .= " "."JOIN usuario_mandante ON (usuario_mandante.usuario_mandante = usuario_recarga.usuario_id)";
            }

        $sql .=" INNER JOIN usuario ON (usuario_recarga.usuario_id=usuario.usuario_id) INNER JOIN pais ON (pais.pais_id=usuario.pais_id) LEFT OUTER JOIN usuario usuario_punto ON (usuario_recarga.puntoventa_id = usuario_punto.usuario_id)  LEFT OUTER JOIN  concesionario ON (usuario_punto.puntoventa_id=concesionario.usuhijo_id AND prodinterno_id=0 AND concesionario.estado='A') LEFT OUTER JOIN punto_venta ON (usuario_recarga.puntoventa_id = punto_venta.usuario_id)  LEFT OUTER JOIN transaccion_producto ON (transaccion_producto.final_id=usuario_recarga.recarga_id)  LEFT OUTER JOIN  producto ON (producto.producto_id=transaccion_producto.producto_id) LEFT OUTER JOIN  proveedor ON (producto.proveedor_id=proveedor.proveedor_id)   " . $where . " " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;


        $sqlQuery = new SqlQuery($sql);
        if($_ENV["debugFixed2"] == '1'){

        }else{

        }
        $result = $Helpers->process_data($this->execute2($sqlQuery));

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }



    /**
     *
     * Realiza una consulta personalizada sobre la tabla `usuario_recarga`, con soporte
     * para filtros dinámicos, ordenamiento, paginación, agrupación, condiciones HAVING,
     * uniones internas adicionales y conteo opcional de resultados.
     *
     * @param string $select : Campos que serán seleccionados en la consulta.
     * @param string $sidx : Columna por la cual se ordenarán los resultados.
     * @param string $sord : Dirección del ordenamiento (asc | desc).
     * @param string $start : Posición inicial de los resultados (para paginación).
     * @param string $limit : Límite de registros a retornar.
     * @param string $filters : Filtros en formato JSON para aplicar condiciones WHERE.
     * @param boolean $searchOn : Determina si se aplican filtros de búsqueda.
     * @param string $grouping : Agrupaciones en la consulta (GROUP BY).
     * @param string $having : Filtros adicionales para aplicar sobre los datos agrupados (HAVING).
     * @param boolean $withCount : Indica si se debe realizar un conteo de resultados.
     * @param string $innerJoin : Clausulas JOIN adicionales personalizadas.
     *
     * @return array $json resultado de la consulta.
     *
     * El objeto $json es un array que incluye los siguientes atributos:
     *  - *count* (int): Número de registros si se solicitó conteo.
     *  - *data* (array): Resultados de la consulta si se implementa la parte SELECT (no incluida aquí).
     *
     * @throws no No contiene manejo de excepciones.
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */

    public function queryUsuarioRecargasCustomPRO($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping, $having = "",$withCount=true,$innerJoin ="")
    {

        $where = " where 1=1 ";

        $innertime_dimension=false;
        $innusuario_mandante=false;

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

                $cond="time_dimension";
                if ( strpos($fieldName, $cond) !== false || strpos($sidx, $cond) !== false || strpos($grouping, $cond) !== false  || strpos($select, $cond) !== false)
                {
                    $innertime_dimension=true;
                }

                $cond="usuario_mandante";

                if ( strpos($fieldName, $cond) !== false || strpos($sidx, $cond) !== false|| strpos($grouping, $cond) !== false || strpos($select, $cond) !== false)
                {
                    $innusuario_mandante=true;
                }



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

        if ($having != null) {

            // Construye el where
            $filtershaving = json_decode($having);
            $havingArray = array();
            $ruleshaving = $filtershaving->rules;
            $groupOperation = $filtershaving->groupOp;
            $cont = 0;
            $having = ' 1=1 ';
            foreach ($ruleshaving as $rule) {
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
                    default:
                        $fieldOperation = "";
                        break;
                }
                if ($fieldOperation != "") $havingArray[] = $fieldName . $fieldOperation;
                if (oldCount($havingArray) > 0) {
                    $having = $having . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
                } else {
                    $having = "";
                }
            }
        }

        if ($grouping != "") {
            $where = $where . " GROUP BY " . $grouping;
        }

        if ($having != "") {
            $where = $where . " HAVING " . $having;
        }

        $cond="time_dimension";
        if ( strpos($fieldName, $cond) !== false || strpos($sidx, $cond) !== false || strpos($grouping, $cond) !== false  || strpos($select, $cond) !== false)
        {
            $innertime_dimension=true;
        }


        $innerjointime_dimension ="";

        if($innertime_dimension){
            $innerjointime_dimension =" inner join time_dimension force index (time_dimension_timestampint_timestr_index)
                    on (time_dimension.timestr = usuario_recarga.fecha_crea) ";
            $innerjointime_dimension ="";

        }


        if($withCount){

            $sql = "SELECT  /*+ MAX_EXECUTION_TIME(50000) */ count(*) count FROM usuario_recarga
              ".$innerjointime_dimension." ";



            if($innusuario_mandante){
                $sql .= " "."JOIN usuario_mandante ON (usuario_mandante.usuario_mandante = usuario_recarga.usuario_id)";
            }
            $sql .= " INNER JOIN usuario ON (usuario_recarga.usuario_id=usuario.usuario_id)  LEFT OUTER JOIN usuario usuario_punto ON (usuario_recarga.puntoventa_id = usuario_punto.usuario_id) LEFT OUTER JOIN  concesionario ON (usuario_punto.puntoventa_id=concesionario.usuhijo_id AND prodinterno_id=0 AND concesionario.estado='A') LEFT OUTER JOIN punto_venta ON (usuario_recarga.puntoventa_id = punto_venta.usuario_id) LEFT OUTER JOIN transaccion_producto ON (transaccion_producto.final_id=usuario_recarga.recarga_id) LEFT OUTER JOIN  producto ON (producto.producto_id=transaccion_producto.producto_id) LEFT OUTER JOIN  proveedor ON (producto.proveedor_id=proveedor.proveedor_id)   ". $innerJoin ." " . $where;

            $sqlQuery = new SqlQuery($sql);
            if($_ENV["debugFixed2"] == '1'){
                print_r($sql);

            }else{
                $count = $this->execute2($sqlQuery);

            }

        }
        $sql = "SELECT  /*+ MAX_EXECUTION_TIME(50000) */ " . $select . " FROM usuario_recarga 
              ".$innerjointime_dimension." ";



        if($innusuario_mandante){
            $sql .= " "."JOIN usuario_mandante ON (usuario_mandante.usuario_mandante = usuario_recarga.usuario_id)";
        }



        $sql .=" INNER JOIN usuario ON (usuario_recarga.usuario_id=usuario.usuario_id) INNER JOIN pais ON (pais.pais_id=usuario.pais_id) LEFT OUTER JOIN usuario usuario_punto ON (usuario_recarga.puntoventa_id = usuario_punto.usuario_id)  LEFT OUTER JOIN  concesionario ON (usuario_punto.puntoventa_id=concesionario.usuhijo_id AND prodinterno_id=0 AND concesionario.estado='A') LEFT OUTER JOIN punto_venta ON (usuario_recarga.puntoventa_id = punto_venta.usuario_id)  LEFT OUTER JOIN transaccion_producto ON (transaccion_producto.final_id=usuario_recarga.recarga_id)  LEFT OUTER JOIN  producto ON (producto.producto_id=transaccion_producto.producto_id) LEFT OUTER JOIN  proveedor ON (producto.proveedor_id=proveedor.proveedor_id)   ". $innerJoin ." " . $where . " " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);
        if($_ENV["debugFixed2"] == '1'){

        }else{

        }
        $result = $Helpers->process_data($this->execute2($sqlQuery));

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }

    public function queryUsuarioRecargasCustom3($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping, $having = "",$withCount=true)
    {

        $where = " where 1=1 ";

        $innertime_dimension=false;

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

                $cond="time_dimension";
                if ( strpos($fieldName, $cond) !== false || strpos($sidx, $cond) !== false || strpos($grouping, $cond) !== false  || strpos($select, $cond) !== false)
                {
                    $innertime_dimension=true;
                }

                

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

        if ($having != null) {

            // Construye el where
            $filtershaving = json_decode($having);
            $havingArray = array();
            $ruleshaving = $filtershaving->rules;
            $groupOperation = $filtershaving->groupOp;
            $cont = 0;
            $having = ' 1=1 ';
            foreach ($ruleshaving as $rule) {
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
                    default:
                        $fieldOperation = "";
                        break;
                }
                if ($fieldOperation != "") $havingArray[] = $fieldName . $fieldOperation;
                if (oldCount($havingArray) > 0) {
                    $having = $having . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
                } else {
                    $having = "";
                }
            }
        }

        if ($grouping != "") {
            $where = $where . " GROUP BY " . $grouping;
        }

        if ($having != "") {
            $where = $where . " HAVING " . $having;
        }

        $cond="time_dimension";
        if ( strpos($fieldName, $cond) !== false || strpos($sidx, $cond) !== false || strpos($grouping, $cond) !== false  || strpos($select, $cond) !== false)
        {
            $innertime_dimension=true;
        }


        $innerjointime_dimension ="";

        if($innertime_dimension){
            $innerjointime_dimension =" inner join time_dimension force index (time_dimension_timestampint_timestr_index)
                    on (time_dimension.timestr = usuario_recarga.fecha_crea) ";

        }

        if($withCount){

            $sql = "SELECT   count(*) count FROM usuario_recarga
              ".$innerjointime_dimension."

        INNER JOIN usuario ON (usuario_recarga.usuario_id=usuario.usuario_id)  LEFT OUTER JOIN usuario usuario_punto ON (usuario_recarga.puntoventa_id = usuario_punto.usuario_id) LEFT OUTER JOIN  concesionario ON (usuario_punto.puntoventa_id=concesionario.usuhijo_id AND prodinterno_id=0 AND concesionario.estado='A') LEFT OUTER JOIN punto_venta ON (usuario_recarga.puntoventa_id = punto_venta.usuario_id) LEFT OUTER JOIN transaccion_producto ON (transaccion_producto.final_id=usuario_recarga.recarga_id) LEFT OUTER JOIN  producto ON (producto.producto_id=transaccion_producto.producto_id) LEFT OUTER JOIN  proveedor ON (producto.proveedor_id=proveedor.proveedor_id) LEFT OUTER JOIN usuario_mandante ON (usuario_mandante.usuario_mandante = usuario_recarga.usuario_id)   " . $where;


            $sqlQuery = new SqlQuery($sql);

            $count = $this->execute2($sqlQuery);
        }

        $sql = "SELECT  " . $select . " FROM usuario_recarga 
              ".$innerjointime_dimension."
        
        INNER JOIN usuario ON (usuario_recarga.usuario_id=usuario.usuario_id) INNER JOIN pais ON (pais.pais_id=usuario.pais_id) LEFT OUTER JOIN usuario usuario_punto ON (usuario_recarga.puntoventa_id = usuario_punto.usuario_id)  LEFT OUTER JOIN  concesionario ON (usuario_punto.puntoventa_id=concesionario.usuhijo_id AND prodinterno_id=0 AND concesionario.estado='A') LEFT OUTER JOIN punto_venta ON (usuario_recarga.puntoventa_id = punto_venta.usuario_id)  LEFT OUTER JOIN transaccion_producto ON (transaccion_producto.final_id=usuario_recarga.recarga_id)  LEFT OUTER JOIN  producto ON (producto.producto_id=transaccion_producto.producto_id) LEFT OUTER JOIN  proveedor ON (producto.proveedor_id=proveedor.proveedor_id)  LEFT OUTER JOIN usuario_mandante ON (usuario_mandante.usuario_mandante = usuario_recarga.usuario_id) INNER JOIN registro ON (usuario_recarga.usuario_id=registro.usuario_id)  " . $where . " " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);
        $result = $Helpers->process_data($this->execute2($sqlQuery));
        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }

    public function queryUsuarioRecargasCustom2($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping, $having = "")
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

        if ($having != null) {

            // Construye el where
            $filtershaving = json_decode($having);
            $havingArray = array();
            $ruleshaving = $filtershaving->rules;
            $groupOperation = $filtershaving->groupOp;
            $cont = 0;
            $having = ' 1=1 ';
            foreach ($ruleshaving as $rule) {
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
                    default:
                        $fieldOperation = "";
                        break;
                }
                if ($fieldOperation != "") $havingArray[] = $fieldName . $fieldOperation;
                if (oldCount($havingArray) > 0) {
                    $having = $having . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
                } else {
                    $having = "";
                }
            }
        }

        if ($grouping != "") {
            $where = $where . " GROUP BY " . $grouping;
        }

        if ($having != "") {
            $where = $where . " HAVING " . $having;
        }

        $sql = "SELECT count(*) count FROM usuario_recarga INNER JOIN usuario ON (usuario_recarga.usuario_id=usuario.usuario_id)  LEFT OUTER JOIN usuario usuario_punto ON (usuario_recarga.puntoventa_id = usuario_punto.usuario_id) LEFT OUTER JOIN  concesionario ON (usuario_punto.puntoventa_id=concesionario.usuhijo_id AND prodinterno_id=0 AND concesionario.estado='A') LEFT OUTER JOIN punto_venta ON (usuario_recarga.puntoventa_id = punto_venta.usuario_id) LEFT OUTER JOIN transaccion_producto ON (transaccion_producto.final_id=usuario_recarga.recarga_id) LEFT OUTER JOIN  producto ON (producto.producto_id=transaccion_producto.producto_id) LEFT OUTER JOIN  proveedor ON (producto.proveedor_id=proveedor.proveedor_id) LEFT OUTER JOIN usuario_mandante ON (usuario_mandante.usuario_mandante = usuario_recarga.usuario_id)   " . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
        $sql = "SELECT " . $select . " FROM usuario_recarga INNER JOIN usuario ON (usuario_recarga.usuario_id=usuario.usuario_id) INNER JOIN pais ON (pais.pais_id=usuario.pais_id) LEFT OUTER JOIN usuario usuario_punto ON (usuario_recarga.puntoventa_id = usuario_punto.usuario_id)  LEFT OUTER JOIN  concesionario ON (usuario_punto.puntoventa_id=concesionario.usuhijo_id AND prodinterno_id=0 AND concesionario.estado='A') LEFT OUTER JOIN punto_venta ON (usuario_recarga.puntoventa_id = punto_venta.usuario_id)  LEFT OUTER JOIN transaccion_producto ON (transaccion_producto.final_id=usuario_recarga.recarga_id)  LEFT OUTER JOIN  producto ON (producto.producto_id=transaccion_producto.producto_id) LEFT OUTER JOIN  proveedor ON (producto.proveedor_id=proveedor.proveedor_id)  LEFT OUTER JOIN usuario_mandante ON (usuario_mandante.usuario_mandante = usuario_recarga.usuario_id)   " . $where . " " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);
        $result = $Helpers->process_data($this->execute2($sqlQuery));
        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }

    public function queryUsuarioRecargasCustomAutomation($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping, $having = "")
    {

        $ConfigurationEnvironment = new ConfigurationEnvironment();
        // $relationship = "";
        $where = " WHERE 1=1 ";

        $Helpers = new Helpers();

        if ($searchOn) {
            // Construye el where
            $filters = json_decode($filters);
            $whereArray = array();
            $rules = $filters->rules;
            $groupOperation = $filters->groupOp;
            $count = 0;

            $relationship = "";
            $relationship .= " INNER JOIN usuario ON (usuario_recarga.usuario_id=usuario.usuario_id)";
            $relationship .= " LEFT OUTER JOIN transaccion_producto ON (transaccion_producto.final_id=usuario_recarga.recarga_id)";
            $relationship .= " LEFT OUTER JOIN producto ON (producto.producto_id=transaccion_producto.producto_id)";
            $relationship .= " LEFT OUTER JOIN proveedor ON (producto.proveedor_id=proveedor.proveedor_id)";
            $relationshipArray = [];

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
                $result = $ConfigurationEnvironment->getRelationship(explode(".", $rule->field)[0], $relationship, $relationshipArray);

                $relationship = $result['relationship'];
                $relationshipArray = $result['relationshipArray'];
            }
        }

        if ($having != null) {

            // Construye el where
            $filtershaving = json_decode($having);
            $havingArray = array();
            $ruleshaving = $filtershaving->rules;
            $groupOperation = $filtershaving->groupOp;
            $count = 0;
            $having = ' 1=1 ';
            foreach ($ruleshaving as $rule) {
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
                    default:
                        $fieldOperation = "";
                        break;
                }
                if ($fieldOperation != "") $havingArray[] = $fieldName . $fieldOperation;
                if (oldCount($havingArray) > 0) {
                    $having = $having . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
                } else {
                    $having = "";
                }
            }
        }

        if ($grouping != "") {
            $where = $where . " GROUP BY " . $grouping;
        }

        if ($having != "") {
            $where = $where . " HAVING " . $having;
        }

        // $sql = "SELECT " . $select . " FROM usuario_recarga INNER JOIN usuario ON (usuario_recarga.usuario_id=usuario.usuario_id) INNER JOIN pais ON (pais.pais_id=usuario.pais_id) LEFT OUTER JOIN usuario usuario_punto ON (usuario_recarga.puntoventa_id = usuario_punto.usuario_id)  LEFT OUTER JOIN  concesionario ON (usuario_punto.usuario_id=concesionario.usuhijo_id AND prodinterno_id=0) LEFT OUTER JOIN punto_venta ON (usuario_recarga.puntoventa_id = punto_venta.usuario_id)  LEFT OUTER JOIN transaccion_producto ON (transaccion_producto.final_id=usuario_recarga.recarga_id)  LEFT OUTER JOIN  producto ON (producto.producto_id=transaccion_producto.producto_id) LEFT OUTER JOIN  proveedor ON (producto.proveedor_id=proveedor.proveedor_id)  LEFT OUTER JOIN usuario_mandante ON (usuario_mandante.usuario_mandante = usuario_recarga.usuario_id)   " . $where . " " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;
        $relationship = $ConfigurationEnvironment->getRelationshipSelect($select, $relationship, $relationshipArray);

        $sql = "SELECT " . $select . " FROM usuario_recarga ". $relationship . $where . " LIMIT " . $start . " , " . $limit;

        if($_ENV["debugFixed2"]){
            print_r($sql);
        }
        $sqlQuery = new SqlQuery($sql);
        $result = $Helpers->process_data($this->execute2($sqlQuery));

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna usuario_id sea igual al valor pasado como parámetro
     *
     * @param String $value usuario_id requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByUsuarioId($value)
    {
        $sql = 'DELETE FROM usuario_recarga WHERE usuario_id = ?';
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
        $sql = 'DELETE FROM usuario_recarga WHERE fecha_crea = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna puntoventa_id sea igual al valor pasado como parámetro
     *
     * @param String $value puntoventa_id requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByPuntoventaId($value)
    {
        $sql = 'DELETE FROM usuario_recarga WHERE puntoventa_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna valor sea igual al valor pasado como parámetro
     *
     * @param String $value valor requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByValor($value)
    {
        $sql = 'DELETE FROM usuario_recarga WHERE valor = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna porcen_regalo_recarga sea igual al valor pasado como parámetro
     *
     * @param String $value porcen_regalo_recarga requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByPorcenRegaloRecarga($value)
    {
        $sql = 'DELETE FROM usuario_recarga WHERE porcen_regalo_recarga = ?';
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
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByMandante($value)
    {
        $sql = 'DELETE FROM usuario_recarga WHERE mandante = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna pedido sea igual al valor pasado como parámetro
     *
     * @param String $value pedido requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByPedido($value)
    {
        $sql = 'DELETE FROM usuario_recarga WHERE pedido = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna dir_ip sea igual al valor pasado como parámetro
     *
     * @param String $value dir_ip requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByDirIp($value)
    {
        $sql = 'DELETE FROM usuario_recarga WHERE dir_ip = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna promocional_id sea igual al valor pasado como parámetro
     *
     * @param String $value promocional_id requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByPromocionalId($value)
    {
        $sql = 'DELETE FROM usuario_recarga WHERE promocional_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna valor_promocional sea igual al valor pasado como parámetro
     *
     * @param String $value valor_promocional requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByValorPromocional($value)
    {
        $sql = 'DELETE FROM usuario_recarga WHERE valor_promocional = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna host sea igual al valor pasado como parámetro
     *
     * @param String $value host requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByHost($value)
    {
        $sql = 'DELETE FROM usuario_recarga WHERE host = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna porcen_iva sea igual al valor pasado como parámetro
     *
     * @param String $value porcen_iva requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByPorcenIva($value)
    {
        $sql = 'DELETE FROM usuario_recarga WHERE porcen_iva = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna mediopago_id sea igual al valor pasado como parámetro
     *
     * @param String $value mediopago_id requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByMediopagoId($value)
    {
        $sql = 'DELETE FROM usuario_recarga WHERE mediopago_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna valor_iva sea igual al valor pasado como parámetro
     *
     * @param String $value valor_iva requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByValorIva($value)
    {
        $sql = 'DELETE FROM usuario_recarga WHERE valor_iva = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }


    /**
     * Crear y devolver un objeto del tipo UsuarioRecarga
     * con los valores de una consulta sql
     *
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Objeto $usuarioRecarga UsuarioRecarga
     *
     * @access protected
     *
     */
    protected function readRow($row)
    {
        $usuarioRecarga = new UsuarioRecarga();

        $usuarioRecarga->recargaId = $row['recarga_id'];
        $usuarioRecarga->usuarioId = $row['usuario_id'];
        $usuarioRecarga->fechaCrea = $row['fecha_crea'];
        $usuarioRecarga->puntoventaId = $row['puntoventa_id'];
        $usuarioRecarga->valor = $row['valor'];
        $usuarioRecarga->impuesto = $row['impuesto'];
        $usuarioRecarga->porcenRegaloRecarga = $row['porcen_regalo_recarga'];
        $usuarioRecarga->mandante = $row['mandante'];
        $usuarioRecarga->pedido = $row['pedido'];
        $usuarioRecarga->dirIp = $row['dir_ip'];
        $usuarioRecarga->promocionalId = $row['promocional_id'];
        $usuarioRecarga->valorPromocional = $row['valor_promocional'];
        $usuarioRecarga->host = $row['host'];
        $usuarioRecarga->porcenIva = $row['porcen_iva'];
        $usuarioRecarga->mediopagoId = $row['mediopago_id'];
        $usuarioRecarga->valorIva = $row['valor_iva'];
        $usuarioRecarga->estado = $row['estado'];
        $usuarioRecarga->version = $row['version'];
        $usuarioRecarga->fechaElimina = $row['fecha_elimina'];
        $usuarioRecarga->usueliminaId = $row['usuelimina_id'];
        $usuarioRecarga->tieneComision = $row['tiene_comision'];

        return $usuarioRecarga;
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

    public function execQuery($transaccion, $sql)
    {

        $UsuarioRecargaMySqlDAO = new UsuarioRecargaMySqlDAO($transaccion);
        $return = $UsuarioRecargaMySqlDAO->querySQL($sql);

        //$return = json_decode(json_encode($return), FALSE);

        return $return;

    }
}
