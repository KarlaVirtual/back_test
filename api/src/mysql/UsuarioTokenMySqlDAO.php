<?php

namespace Backend\mysql;

use Backend\dao\UsuarioTokenDAO;
use Backend\dto\UsuarioToken;
use Backend\sql\QueryExecutor;
use Backend\sql\SqlQuery;
use Backend\sql\Transaction;

use Backend\utils\RedisConnectionTrait;
use Exception;
use Backend\dto\Helpers;

/**
 * Clase 'UsuarioTokenMySqlDAO'
 * 
 * Esta clase provee las consultas del modelo o tabla 'UsuarioToken'
 * 
 * Ejemplo de uso: 
 * $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
 *   
 * 
 * @package ninguno 
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public 
 * @see no
 * 
 */
class UsuarioTokenMySqlDAO implements UsuarioTokenDAO
{

    use RedisConnectionTrait;
    /**
     * Atributo Transaction transacción
     *
     * @var object
     */
    private $transaction;

    /**
     * Atributo redisParam parámetros de redis
     *
     * @var array
     */
    private $redisParam = ['ex' => 300];

    /**
     * Atributo redisPrefix prefijo de redis
     *
     * @var string
     */
    private $redisPrefix = "UsuarioToken+";

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
        $cachedKey = $this->redisPrefix . "UT" . $id;
        $cachedValue = json_decode($this->getKey($cachedKey));

        if ($_ENV['checkCache'] == 1) {

            if (!empty($cachedValue)) {
                return $cachedValue;
            }
        }
        $sql = 'SELECT * FROM usuario_token WHERE usutoken_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($id);

        $usuarioToken = $this->getRow($sqlQuery);
        $this->setKey($cachedKey, json_encode($usuarioToken), $this->redisParam);
        return $usuarioToken;
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
        $sql = 'SELECT * FROM usuario_token';
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
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
    public function queryAllOrderBy($orderColumn)
    {
        $sql = 'SELECT * FROM usuario_token ORDER BY ' . $orderColumn;
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }

    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $usutoken_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function delete($usutoken_id)
    {
        $sql = 'DELETE FROM usuario_token WHERE usutoken_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($usutoken_id);
        $this->deleteKey($this->redisPrefix . "UT" . $usutoken_id);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Insertar un registro en la base de datos
     *
     * @param Objeto usuarioToken usuarioToken
     *
     * @return String $id resultado de la consulta
     *
     */
    public function insert($usuarioToken)
    {
        $sql = 'INSERT INTO usuario_token (usuario_id, proveedor_id, token,request_id,estado,cookie, usucrea_id, usumodif_id,saldo, producto_id) VALUES (?, ?, ?, ?, ?,?, ?, ?, ?, ?)';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($usuarioToken->usuarioId);
        $sqlQuery->setNumber($usuarioToken->proveedorId);
        $sqlQuery->set($usuarioToken->token);
        $sqlQuery->set($usuarioToken->requestId);

        if ($usuarioToken->estado == '') {
            $usuarioToken->estado = 'A';
        }
        $sqlQuery->set($usuarioToken->estado);
        $sqlQuery->set($usuarioToken->cookie);
        //$sqlQuery->set($usuarioToken->usuarioProveedor);
        $sqlQuery->setNumber($usuarioToken->usucreaId);
        $sqlQuery->setNumber($usuarioToken->usumodifId);

        if ($usuarioToken->saldo == '') {
            $usuarioToken->saldo = 0;
        }

        $sqlQuery->set($usuarioToken->saldo);

        if ($usuarioToken->productoId == '') {
            $usuarioToken->productoId = 0;
        }

        $sqlQuery->setNumber($usuarioToken->productoId);

        $id = $this->executeInsert($sqlQuery);
        $usuarioToken->usutokenId = $id;

        $usuarioToken->fechaCrea = date('Y-m-d H:i:s');
        $usuarioToken->fechaModif = date('Y-m-d H:i:s');

        $this->setKey(
            $this->redisPrefix . "UT" . $id,
            json_encode($usuarioToken),
            $this->redisParam
        );

        $arrayT = array();
        array_push($arrayT, $usuarioToken);
        $this->setKey(
            $this->redisPrefix . $usuarioToken->token . "+" . $usuarioToken->proveedorId,
            json_encode($arrayT),
            $this->redisParam
        );

        return $id;
    }

    /**
     * Editar un registro en la base de datos
     *
     * @param Objeto usuarioToken usuarioToken
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function update($usuarioToken)
    {
        if ($usuarioToken->usuarioProveedor == "") {
            $usuarioToken->usuarioProveedor = 0;
        }

        $sql = 'UPDATE usuario_token SET usuario_id = ?, proveedor_id = ?, token = ?,request_id = ?, estado=?,cookie = ?, usucrea_id = ?, usumodif_id = ?, saldo=?,usuario_proveedor=?, producto_id = ?, fecha_modif = ? WHERE usutoken_id = ?';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($usuarioToken->usuarioId);
        $sqlQuery->setNumber($usuarioToken->proveedorId);
        $sqlQuery->set($usuarioToken->token);
        $sqlQuery->set($usuarioToken->requestId);
        $sqlQuery->set($usuarioToken->estado);
        $sqlQuery->set($usuarioToken->cookie);
        $sqlQuery->setNumber($usuarioToken->usucreaId);
        $sqlQuery->setNumber($usuarioToken->usumodifId);
        $sqlQuery->set($usuarioToken->saldo);
        $sqlQuery->set($usuarioToken->usuarioProveedor);

        if ($usuarioToken->productoId == '') {
            $usuarioToken->productoId = 0;
        }

        $sqlQuery->setNumber($usuarioToken->productoId);

        $sqlQuery->set($usuarioToken->fechaModif);


        $sqlQuery->setNumber($usuarioToken->usutokenId);

        $result = $this->executeUpdate($sqlQuery);

        $this->updateUsuarioTokenCache($usuarioToken);

        return $result;
    }
    public function updateState($usuarioToken)
    {

        $sql = 'UPDATE usuario_token SET fecha_crea = ?, estado = ? WHERE usutoken_id = ?';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->set($usuarioToken->fechaCrea);
        $sqlQuery->set($usuarioToken->estado);

        $sqlQuery->setNumber($usuarioToken->usutokenId);

        $result = $this->executeUpdate($sqlQuery);

        $this->updateUsuarioTokenCache($usuarioToken);

        return $result;
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
        $sql = 'DELETE FROM usuario_token';
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
        $sql = 'SELECT * FROM usuario_token WHERE usuario_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna proveedor_id sea igual al valor pasado como parámetro
     *
     * @param String $value proveedor_id requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByProveedorId($value)
    {
        $sql = 'SELECT * FROM usuario_token WHERE proveedor_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna token sea igual al valor pasado como parámetro
     *
     * @param String $value token requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByToken($value)
    {
        $sql = 'SELECT * FROM usuario_token WHERE token = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna cookie sea igual al valor pasado como parámetro
     *
     * @param String $value cookie requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByCookie($value)
    {
        $sql = 'SELECT * FROM usuario_token WHERE cookie = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * las columnas usuario_proveedor y proveedor_id son iguales a los valores 
     * pasados como parámetros
     *
     * @param String $value usuario_proveedor requerido
     * @param String $proveedorId proveedor_id requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByUsuarioProveedor($value, $proveedorId, $estado = '')
    {
        $sqlEstado = '';
        if ($estado != '') {
            $sqlEstado = " AND estado = '" . $estado . "' ";
        }
        $sql = 'SELECT * FROM usuario_token WHERE usuario_proveedor = ? AND proveedor_id = ?' . $sqlEstado;
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        $sqlQuery->set($proveedorId);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * las columnas token y proveedor_id son iguales a los valores 
     * pasados como parámetros
     *
     * @param String $value token requerido
     * @param String $productoId producto_id requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByTokenAndProductoId($value, $productoId, $estado = '')
    {
        $sqlEstado = '';
        if ($estado != '') {
            $sqlEstado = " AND estado = '" . $estado . "' ";
        }
        $sql = 'SELECT * FROM usuario_token WHERE token = ? AND producto_id = ?' . $sqlEstado;
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setString($value);
        $sqlQuery->setNumber($productoId);
        return $this->getList($sqlQuery);
    }



    /**
     * Obtener todos los registros donde se encuentre que
     * las columnas token y proveedor_id son iguales a los valores
     * pasados como parámetros
     *
     * @param String $value token requerido
     * @param String $productoId producto_id requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByUsuarioAndProductoId($value, $productoId, $estado = '')
    {
        $sqlEstado = '';
        if ($estado != '') {
            $sqlEstado = " AND estado = '" . $estado . "' ";
        }
        $sql = 'SELECT * FROM usuario_token WHERE usuario_id = ? AND producto_id = ?' . $sqlEstado;
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setString($value);
        $sqlQuery->setNumber($productoId);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * las columnas token y proveedor_id son iguales a los valores
     * pasados como parámetros
     *
     * @param String $value token requerido
     * @param String $proveedorId proveedor_id requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByTokenAndProveedorId($value, $proveedorId, $estado = '')
    {
        $cachedKey = $this->redisPrefix . $value . "+" . $proveedorId;
        $cachedValue = json_decode($this->getKey($cachedKey));

        $sqlEstado = "";
        if ($proveedorId == '0') {
            $estado = 'A';
        }

        if ($_ENV['checkCache'] == 1) {
            if (!empty($cachedValue)) {

                try {
                    $cachedValueT = $cachedValue[0];
                    if ($cachedValueT != null && $cachedValueT != "") {
                        if ($cachedValueT->estado == $estado) {
                            return $cachedValue;
                        }
                    }
                } catch (Exception $e) {
                }
            }
        }



        if ($estado != '') {
            $sqlEstado = " AND estado = '" . $estado . "' ";
        }
        $sql = 'SELECT * FROM usuario_token WHERE token = ? AND proveedor_id = ? ' . $sqlEstado;
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setString($value);
        $sqlQuery->setString($proveedorId);


        if ($_REQUEST['isDebug'] == '1') {
            print_r(date('Y-m-d H:i:s'));
        }

        $usuarioToken = $this->getList($sqlQuery);
        $this->setKey($cachedKey, json_encode($usuarioToken), $this->redisParam);
        return $usuarioToken;
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * las columnas usuario_id y proveedor_id son iguales a los valores 
     * pasados como parámetros
     *
     * @param String $value usuario_id requerido
     * @param String $proveedorId proveedor_id requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByUsuarioIdAndProveedorId($value, $proveedorId, $estado = '')
    {
        $sqlEstado = "";
        if ($proveedorId == '0') {
            $sqlEstado = " AND estado = 'A' ";
        }
        if ($estado != '') {
            $sqlEstado = " AND estado = '" . $estado . "' ";
        }

        $sql = 'SELECT * FROM usuario_token WHERE usuario_id = ? AND proveedor_id = ?' . $sqlEstado .
            '
        ORDER BY usutoken_id desc
        LIMIT 1
        
        ';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        $sqlQuery->set($proveedorId);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna request_id sea igual al valor pasado como parámetro
     *
     * @param String $value request_id requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByRequestId($value)
    {
        $sql = 'SELECT * FROM usuario_token WHERE request_id = ?';
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
        $sql = 'SELECT * FROM usuario_token WHERE usucrea_id = ?';
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
    public function queryByFechaCrea($value)
    {
        $sql = 'SELECT * FROM usuario_token WHERE fecha_crea = ?';
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
        $sql = 'SELECT * FROM usuario_token WHERE usumodif_id = ?';
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
        $sql = 'SELECT * FROM usuario_token WHERE fecha_modif = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }









    /**
     * Realizar una consulta en la tabla de UsuarioToken 'UsuarioToken'
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
    public function queryUsuariosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
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


        $sql = 'SELECT count(*) count FROM usuario_token  INNER JOIN usuario_mandante ON (usuario_mandante.usumandante_id = usuario_token.usuario_id) LEFT OUTER JOIN proveedor ON (proveedor.proveedor_id =usuario_token.proveedor_id )  ' . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT ' . $select . '  FROM usuario_token  INNER JOIN usuario_mandante ON (usuario_mandante.usumandante_id = usuario_token.usuario_id) LEFT OUTER  JOIN proveedor ON (proveedor.proveedor_id =usuario_token.proveedor_id ) ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

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
        $sql = 'DELETE FROM usuario_token WHERE usuario_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna proveedor_id sea igual al valor pasado como parámetro
     *
     * @param String $value proveedor_id requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByProveedorId($value)
    {
        $sql = 'DELETE FROM usuario_token WHERE proveedor_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna token sea igual al valor pasado como parámetro
     *
     * @param String $value token requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByToken($value)
    {
        $sql = 'DELETE FROM usuario_token WHERE token = ?';
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
        $sql = 'DELETE FROM usuario_token WHERE usucrea_id = ?';
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
    public function deleteByFechaCrea($value)
    {
        $sql = 'DELETE FROM usuario_token WHERE fecha_crea = ?';
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
        $sql = 'DELETE FROM usuario_token WHERE usumodif_id = ?';
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
        $sql = 'DELETE FROM usuario_token WHERE fecha_modif = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

















    /**
     * Crear y devolver un objeto del tipo UsuarioToken
     * con los valores de una consulta sql
     * 
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Objeto $usuarioToken UsuarioToken
     *
     * @access protected
     *
     */
    protected function readRow($row)
    {
        $usuarioToken = new UsuarioToken();

        $usuarioToken->usutokenId = $row['usutoken_id'];
        $usuarioToken->usuarioId = $row['usuario_id'];
        $usuarioToken->proveedorId = $row['proveedor_id'];
        $usuarioToken->token = $row['token'];
        $usuarioToken->requestId = $row['request_id'];
        $usuarioToken->cookie = $row['cookie'];
        $usuarioToken->usuarioProveedor = $row['usuario_proveedor'];
        $usuarioToken->usucreaId = $row['usucrea_id'];
        $usuarioToken->fechaCrea = $row['fecha_crea'];
        $usuarioToken->usumodifId = $row['usumodif_id'];
        $usuarioToken->fechaModif = $row['fecha_modif'];
        $usuarioToken->estado = $row['estado'];
        $usuarioToken->saldo = $row['saldo'];

        $usuarioToken->productoId = $row['producto_id'];

        return $usuarioToken;
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
     * @param $usuarioToken
     * @return void
     */
    public function updateUsuarioTokenCache($usuarioToken): void
    {
        $this->updateCache(
            $this->redisPrefix . "UT" . $usuarioToken->usutokenId,
            json_encode($usuarioToken)
        );
        $arrayT = array();
        array_push($arrayT, $usuarioToken);
        $this->updateCache(
            $this->redisPrefix . $usuarioToken->token . "+" . $usuarioToken->proveedorId,
            json_encode($arrayT)
        );
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
