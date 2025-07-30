<?php namespace Backend\mysql;
use Backend\dao\UsuarioBancoDAO;
use Backend\dto\Helpers;
use Backend\dto\UsuarioBanco;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Exception;
/** 
* Clase 'UsuarioBancoMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'UsuarioBanco'
* 
* Ejemplo de uso: 
* $UsuarioBancoMySqlDAO = new UsuarioBancoMySqlDAO();
* 
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
*/
class UsuarioBancoMySqlDAO implements UsuarioBancoDAO
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
     * @return mixed Transaction transacción
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * Modificar el atributo transacción del objeto
     *
     * @param mixed $transaction transacción
     *
     * @return void
     */
    public function setTransaction($transaction)
    {
        $this->transaction = $transaction;
    }

    /**
    * Constructor de clase
    *
    *
    * @param mixed $transaction transaccion
    *
    * @return void
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
     * @param string $id llave primaria
     *
     * @return array $ resultado de la consulta
     */
    public function load($id)
    {
        $sql = 'SELECT * FROM usuario_banco WHERE usubanco_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($id);
        return $this->getRow($sqlQuery);
    }

    /**
     * Obtener todos los registros condicionados por la
     * llave primaria que se pasa como parámetro
     *
     * @param string $usuarioId llave primaria
     * @param mixed $tipoCuenta de la consulta
     *
     * @return array $ resultado de la consulta
     */
    public function loadUserIdAndTypeAcount($usuarioId, $tipoCuenta)
    {
        $sql = 'SELECT * FROM usuario_banco WHERE usuario_id = ? AND tipo_cuenta = ? AND estado = "A" ';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($usuarioId);
        $sqlQuery->set($tipoCuenta);

        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros condicionados por la 
     * llave primaria que se pasa como parámetro
     *
     * @return array $ resultado de la consulta
     */
    public function queryAll()
    {
        $sql = 'SELECT * FROM usuario_banco';
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros
     * ordenadas por el nombre de la columna 
     * que se pasa como parámetro
     *
     * @param string $orderColumn nombre de la columna
     *
     * @return array $ resultado de la consulta
     */
    public function queryAllOrderBy($orderColumn)
    {
        $sql = 'SELECT * FROM usuario_banco ORDER BY ' . $orderColumn;
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }


    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param string $usubanco_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     */
    public function delete($usubanco_id)
    {
        $sql = 'DELETE FROM usuario_banco WHERE usubanco_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($usubanco_id);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Insertar un registro en la base de datos
     *
     * @param mixed $usuario_banco usuario_banco
     *
     * @return string $id resultado de la consulta
     */
    public function insert($usuario_banco)
    {
        $sql = 'INSERT INTO usuario_banco (usuario_id, banco_id, cuenta,tipo_cuenta,tipo_cliente, estado, usucrea_id, usumodif_id,codigo,token,producto_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?,?,?)';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($usuario_banco->usuarioId);
        $sqlQuery->set($usuario_banco->bancoId);
        $sqlQuery->set($usuario_banco->cuenta);
        $sqlQuery->set($usuario_banco->tipoCuenta);
        $sqlQuery->set($usuario_banco->tipoCliente);
        $sqlQuery->set($usuario_banco->estado);
        $sqlQuery->setNumber($usuario_banco->usucreaId);
        $sqlQuery->setNumber($usuario_banco->usumodifId);
        $sqlQuery->set($usuario_banco->codigo);
        $sqlQuery->set($usuario_banco->token);
        if($usuario_banco->productoId == ''){
            $sqlQuery->setSIN('0');
        }else{
            $sqlQuery->set($usuario_banco->productoId);
        }

        $id = $this->executeInsert($sqlQuery);
        $usuario_banco->usubancoId = $id;
        return $id;
    }


    /**
     * Editar un registro en la base de datos
     *
     * @param mixed $usuario_banco usuario_banco
     *
     * @return boolean resultado de la consulta
     */
    public function update($usuario_banco)
    {
        $sql = 'UPDATE usuario_banco SET usuario_id = ?, banco_id = ?, cuenta = ?, tipo_cuenta = ?, tipo_cliente = ?, estado = ?,  usucrea_id = ?,  usumodif_id = ?, codigo = ?, token = ?, producto_id = ? WHERE usubanco_id = ?';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($usuario_banco->usuarioId);
        $sqlQuery->set($usuario_banco->bancoId);
        $sqlQuery->set($usuario_banco->cuenta);
        $sqlQuery->set($usuario_banco->tipoCuenta);
        $sqlQuery->set($usuario_banco->tipoCliente);
        $sqlQuery->set($usuario_banco->estado);
        $sqlQuery->setNumber($usuario_banco->usucreaId);
        $sqlQuery->setNumber($usuario_banco->usumodifId);
        $sqlQuery->set($usuario_banco->codigo);
        $sqlQuery->set($usuario_banco->token);
        $sqlQuery->set($usuario_banco->productoId);

        $sqlQuery->setNumber($usuario_banco->usubancoId);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todas los registros de la base de datos
     *
     * @return boolean $ resultado de la consulta
     */
    public function clean()
    {
        $sql = 'DELETE FROM usuario_banco';
        $sqlQuery = new SqlQuery($sql);
        return $this->executeUpdate($sqlQuery);
    }








    /**
     * Obtener todos los registros donde se encuentre que
     * la columna estado sea igual al valor pasado como parámetro
     *
     * @param string $value estado requerido
     *
     * @return array $ resultado de la consulta
     */
    public function queryByEstado($value)
    {
        $sql = 'SELECT * FROM usuario_banco WHERE estado = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna fecha_crea sea igual al valor pasado como parámetro
     *
     * @param string $value fecha_crea requerido
     *
     * @return array $ resultado de la consulta
     */
    public function queryByFechaCrea($value)
    {
        $sql = 'SELECT * FROM usuario_banco WHERE fecha_crea = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna usucrea_id sea igual al valor pasado como parámetro
     *
     * @param string $value usucrea_id requerido
     *
     * @return array $ resultado de la consulta
     */
    public function queryByUsucreaId($value)
    {
        $sql = 'SELECT * FROM usuario_banco WHERE usucrea_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna usumodif_id sea igual al valor pasado como parámetro
     *
     * @param string $value usumodif_id requerido
     *
     * @return array resultado de la consulta
     */
    public function queryByUsumodifId($value)
    {
        $sql = 'SELECT * FROM usuario_banco WHERE usumodif_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * las colomunas usuario_id y banco_id sean iguales
     * a los valores pasados como parámetros
     *
     * @param string $usuarioId usuario_id requerido
     * @param string $bancoId banco_id requerido
     *
     * @return array $ resultado de la consulta
     */
    public function queryByUsuarioIdAndBannerId($usuarioId,$bancoId)
    {
        $sql = 'SELECT * FROM usuario_banco WHERE usuario_id = ? AND banco_id =?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($usuarioId);
        $sqlQuery->setNumber($bancoId);
        return $this->getList($sqlQuery);
    }










    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna banco_id sea igual al valor pasado como parámetro
     *
     * @param string $value banco_id requerido
     *
     * @return boolean resultado de la ejecución
     */
    public function deleteByBannerId($value)
    {
        $sql = 'DELETE FROM usuario_banco WHERE banco_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna estado sea igual al valor pasado como parámetro
     *
     * @param string $value estado requerido
     *
     * @return boolean $ resultado de la ejecución
     */
    public function deleteByEstado($value)
    {
        $sql = 'DELETE FROM usuario_banco WHERE estado = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna fecha_crea sea igual al valor pasado como parámetro
     *
     * @param string $value fecha_crea requerido
     *
     * @return boolean $ resultado de la ejecución
     */
    public function deleteByFechaCrea($value)
    {
        $sql = 'DELETE FROM usuario_banco WHERE fecha_crea = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna usucrea_id sea igual al valor pasado como parámetro
     *
     * @param string $value usucrea_id requerido
     *
     * @return boolean $ resultado de la ejecución
     */
    public function deleteByUsucreaId($value)
    {
        $sql = 'DELETE FROM usuario_banco WHERE usucrea_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna usumodif_id sea igual al valor pasado como parámetro
     *
     * @param string $value usumodif_id requerido
     *
     * @return boolean $ resultado de la ejecución
     */
    public function deleteByUsumodifId($value)
    {
        $sql = 'DELETE FROM usuario_banco WHERE usumodif_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->executeUpdate($sqlQuery);
    }









    /**
     * Crear y devolver un objeto del tipo UsuarioBanco
     * con los valores de una consulta sql
     * 
     *
     * @param array $row arreglo asociativo
     *
     * @return Objeto $usuario_banco UsuarioBanco
     *
     * @access protected
     */
    protected function readRow($row)
    {
        $usuario_banco = new UsuarioBanco();

        $usuario_banco->usubancoId = $row['usubanco_id'];
        $usuario_banco->usuarioId = $row['usuario_id'];
        $usuario_banco->bancoId = $row['banco_id'];
        $usuario_banco->cuenta = $row['cuenta'];
        $usuario_banco->tipoCuenta = $row['tipo_cuenta'];
        $usuario_banco->tipoCliente = $row['tipo_cliente'];
        $usuario_banco->estado = $row['estado'];
        $usuario_banco->fechaCrea = $row['fecha_crea'];
        $usuario_banco->usucreaId = $row['usucrea_id'];
        $usuario_banco->fechaModif = $row['fecha_modif'];
        $usuario_banco->usumodifId = $row['usumodif_id'];
        $usuario_banco->codigo = $row['codigo'];
        $usuario_banco->token = $row['token'];
        $usuario_banco->productoId = $row['producto_id'];

        return $usuario_banco;
    }







    /**
    * Realizar una consulta en la tabla de UsuarioBanco 'UsuarioBanco'
    * de una manera personalizada
    *
    * @param string $sidx columna para ordenar
    * @param string $sord orden los datos asc | desc
    * @param string $start inicio de la consulta
    * @param string $limit limite de la consulta
    * @param string $filters condiciones de la consulta
    * @param boolean $searchOn utilizar los filtros o no
    *
    * @return array $json resultado de la consulta
    */
    public function queryUsuarioBancos($sidx, $sord, $start, $limit, $filters, $searchOn)
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


        $sql = 'SELECT count(*) count FROM usuario_banco ' . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT usuario_banco.* FROM usuario_banco ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }

    /**
     * Realizar una consulta en la tabla de UsuarioBanco 'UsuarioBanco'
     * de una manera personalizada
     *
     * @param string $select campos de consulta
     * @param string $sidx columna para ordenar
     * @param string $sord orden los datos asc | desc
     * @param string $start inicio de la consulta
     * @param string $limit limite de la consulta
     * @param string $filters condiciones de la consulta
     * @param boolean $searchOn utilizar los filtros o no
     * @param array $joins joins personalizados
     *
     * @return array $json resultado de la consulta
     */
    public function queryUsuarioBancosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $joins = [])
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

                if ($fieldName == 'registro.cedula') {
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_DOCUMENT']);
                }
                if ($fieldName == 'registro.celular') {
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_PHONE']);
                }
                if ($fieldName == 'usuario.cedula') {
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_DOCUMENT']);
                }
                if ($fieldName == 'usuario.login') {
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }
                if ($fieldName == 'usuario_mandante.email') {
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }
                if ($fieldName == 'punto_venta.email') {
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }
                if ($fieldName == 'usuario_sitebuilder.login') {
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


        /* Construyendo cadena de joins solicitados fuera de la petición JOINS DINÁMICOS*/
        $strJoins = " ";
        if (!empty($joins)) {
            foreach ($joins as $join) {
                /*
                 *Ejemplo estructura $join
                 *{
                 *     "type": "INNER" | "LEFT" | "RIGHT",
                 *     "table": "usuario_puntoslealtad"
                 *     "on": "usuario.usuario_id = usuario_puntoslealtad.usuario_id"
                 *}
                 */
                $allowedJoins = ["INNER", "LEFT", "RIGHT"];
                if (in_array($join->type, $allowedJoins)) {
                    //Estructurando cadena de joins
                    $strJoins .= " " . strtoupper($join->type) . " JOIN " . $join->table . " ON (" . $join->on . ") ";
                }
            }
        }

        $sql = 'SELECT count(*) count FROM usuario_banco INNER JOIN banco ON (banco.banco_id = usuario_banco.banco_id)  INNER JOIN usuario ON (usuario.usuario_id = usuario_banco.usuario_id)  LEFT JOIN cripto_red ON (banco.banco_id = cripto_red.banco_id)' . $strJoins . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT ' .$select .'  FROM usuario_banco  INNER JOIN banco ON (banco.banco_id = usuario_banco.banco_id)  INNER JOIN usuario ON (usuario.usuario_id = usuario_banco.usuario_id) LEFT JOIN cripto_red ON (banco.banco_id = cripto_red.banco_id)' . $strJoins .  $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;


        $sqlQuery = new SqlQuery($sql);

        $result = $Helpers->process_data($this->execute2($sqlQuery));

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }








    /**
     * Ejecutar una consulta sql y devolver los datos
     * como un arreglo asociativo
     *
     * @param string $sqlQuery consulta sql
     *
     * @return array $ret arreglo indexado
     *
     * @access protected
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
     * @param string $sqlQuery consulta sql
     *
     * @return array resultado de la ejecución
     *
     * @access protected
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
     * @param string $sqlQuery consulta sql
     *
     * @return array resultado de la ejecución
     *
     * @access protected
     */
    protected function execute($sqlQuery)
    {
        return QueryExecutor::execute($this->transaction, $sqlQuery);
    }

    /**
     * Ejecutar una consulta sql
     *
     * @param string $sqlQuery consulta sql
     *
     * @return array $ resultado de la ejecución
     *
     * @access protected
     */
    protected function execute2($sqlQuery)
    {
        return QueryExecutor::execute2($this->transaction, $sqlQuery);
    }

    /**
     * Ejecutar una consulta sql como update
     * 
     *
     * @param string $sqlQuery consulta sql
     *
     * @return array $ resultado de la ejecución
     *
     * @access protected
     */
    protected function executeUpdate($sqlQuery)
    {
        return QueryExecutor::executeUpdate($this->transaction, $sqlQuery);
    }
    
    /**
     * Ejecutar una consulta sql como select
     * 
     *
     * @param string $sqlQuery consulta sql
     *
     * @return array resultado de la ejecución
     *
     * @access protected
     */
    protected function querySingleResult($sqlQuery)
    {
        return QueryExecutor::queryForString($this->transaction, $sqlQuery);
    }

    /**
     * Ejecutar una consulta sql como insert
     * 
     *
     * @param string $sqlQuery consulta sql
     *
     * @return array resultado de la ejecución
     *
     * @access protected
     */
    protected function executeInsert($sqlQuery)
    {
        return QueryExecutor::executeInsert($this->transaction, $sqlQuery);
    }
}
?>
