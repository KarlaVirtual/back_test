<?php namespace Backend\mysql;

use Backend\dao\UsuarioSessionDAO;
use Backend\dto\UsuarioSession;
use Backend\sql\QueryExecutor;
use Backend\sql\SqlQuery;
use Backend\sql\Transaction;

use Backend\dto\Helpers;
/**
 * Clase 'UsuarioSessionMySqlDAO'
 *
 * Esta clase provee las consultas del modelo o tabla 'UsuarioSession'
 *
 * Ejemplo de uso:
 * $UsuarioSessionMySqlDAO = new UsuarioSessionMySqlDAO();
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class UsuarioSessionMySqlDAO implements UsuarioSessionDAO
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
     * Consulta una sesión de usuario activa por ID de usuario.
     *
     * @param int $value El ID del usuario.
     * @return array La fila correspondiente a la sesión de usuario activa.
     */
    public function queryByUser($value) {
        $sql = 'SELECT * from usuario_session WHERE usuario_id = ? AND estado = \'A\'';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
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
    public function load($id)
    {
        $sql = 'SELECT * FROM usuario_session WHERE ususession_id = ?';
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
        $sql = 'SELECT * FROM usuario_session';
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
        $sql = 'SELECT * FROM usuario_session ORDER BY ' . $orderColumn;
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }

    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $ususession_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function delete($ususession_id)
    {
        $sql = 'DELETE FROM usuario_session WHERE ususession_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($ususession_id);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Insertar un registro en la base de datos
     *
     * @param Objeto usuarioSession usuarioSession
     *
     * @return String $id resultado de la consulta
     *
     */
    public function insert($usuarioSession)
    {
        $sql = 'INSERT INTO usuario_session (usuario_id, tipo,request_id,estado,perfil, usucrea_id, usumodif_id) VALUES (?, ?, ?, ?, ?,?, ?)';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($usuarioSession->usuarioId);
        $sqlQuery->set($usuarioSession->tipo);
        $sqlQuery->set($usuarioSession->requestId);
        $sqlQuery->set($usuarioSession->estado);
        $sqlQuery->set($usuarioSession->perfil);
        //$sqlQuery->set($usuarioSession->usuarioProveedor);
        $sqlQuery->setNumber($usuarioSession->usucreaId);
        $sqlQuery->setNumber($usuarioSession->usumodifId);

        $id = $this->executeInsert($sqlQuery);
        $usuarioSession->ususessionId = $id;
        return $id;
    }

    /**
     * Editar un registro en la base de datos
     *
     * @param Objeto usuarioSession usuarioSession
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function update($usuarioSession)
    {


        if ($usuarioSession->usuarioProveedor == "") {
            $usuarioSession->usuarioProveedor = 0;
        }

        $sql = 'UPDATE usuario_session SET usuario_id = ?, tipo = ?,request_id = ?, estado=?,perfil = ?, usucrea_id = ?, usumodif_id = ? WHERE ususession_id = ?';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($usuarioSession->usuarioId);
        $sqlQuery->set($usuarioSession->tipo);
        $sqlQuery->set($usuarioSession->requestId);
        $sqlQuery->set($usuarioSession->estado);
        $sqlQuery->set($usuarioSession->perfil);
        $sqlQuery->setNumber($usuarioSession->usucreaId);
        $sqlQuery->setNumber($usuarioSession->usumodifId);

        $sqlQuery->setNumber($usuarioSession->ususessionId);

        return $this->executeUpdate($sqlQuery);
    }


    /**
     * Editar un registro en la base de datos
     *
     * @param Objeto usuarioSession usuarioSession
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function updateClose(UsuarioSession $usuarioSession)
    {


        if ($usuarioSession->usuarioProveedor == "") {
            $usuarioSession->usuarioProveedor = 0;
        }

        $sql = "UPDATE usuario_session SET estado = 'I' WHERE request_id = ? AND estado='A' ";
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->set($usuarioSession->requestId);


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
        $sql = 'DELETE FROM usuario_session';
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
        $sql = 'SELECT * FROM usuario_session WHERE usuario_id = ?';
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
        $sql = 'SELECT * FROM usuario_session WHERE proveedor_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna tipo sea igual al valor pasado como parámetro
     *
     * @param String $value tipo requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByTipo($value)
    {
        $sql = 'SELECT * FROM usuario_session WHERE tipo = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna perfil sea igual al valor pasado como parámetro
     *
     * @param String $value perfil requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByPerfil($value)
    {
        $sql = 'SELECT * FROM usuario_session WHERE perfil = ?';
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
    public function queryByUsuarioProveedor($value, $proveedorId)
    {
        $sql = 'SELECT * FROM usuario_session WHERE usuario_proveedor = ? AND proveedor_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        $sqlQuery->set($proveedorId);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * las columnas tipo y proveedor_id son iguales a los valores
     * pasados como parámetros
     *
     * @param String $value tipo requerido
     * @param String $proveedorId proveedor_id requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByTipoAndSessionIdAndEstado($value, $requestId, $estado = "")
    {
        $sql = 'SELECT * FROM usuario_session WHERE tipo = ? AND request_id = ? AND estado = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setString($value);
        $sqlQuery->setString($requestId);
        $sqlQuery->setString($estado);
        return $this->getList($sqlQuery);
    }

    /**
     * Consulta registros en la tabla usuario_session filtrando por tipo, usuario_id y estado.
     *
     * @param string $value El tipo de sesión.
     * @param string $userID El ID del usuario.
     * @param string $estado El estado de la sesión.
     * @return array Lista de registros que coinciden con los criterios de búsqueda.
     */
    public function queryByTipoAndUserIdAndEstado($value, $userID, $estado)
    {
        $sql = 'SELECT * FROM usuario_session WHERE tipo = ? AND usuario_id = ? AND estado = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setString($value);
        $sqlQuery->setString($userID);
        $sqlQuery->setString($estado);
        return $this->getList($sqlQuery);
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
    public function queryByUsuarioIdAndProveedorId($value, $proveedorId)
    {
        $sql = 'SELECT * FROM usuario_session WHERE usuario_id = ? AND proveedor_id = ?';
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
        $sql = 'SELECT * FROM usuario_session WHERE request_id = ?';
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
        $sql = 'SELECT * FROM usuario_session WHERE usucrea_id = ?';
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
        $sql = 'SELECT * FROM usuario_session WHERE fecha_crea = ?';
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
        $sql = 'SELECT * FROM usuario_session WHERE usumodif_id = ?';
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
        $sql = 'SELECT * FROM usuario_session WHERE fecha_modif = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }


    /**
     * Realizar una consulta en la tabla de UsuarioSession 'UsuarioSession'
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
    public function queryUsuariosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping)
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

        if(!empty($grouping)) $where .= "GROUP BY {$grouping}";

        $sql = 'SELECT count(*) count FROM usuario_session  INNER JOIN usuario_mandante ON (usuario_mandante.usumandante_id = usuario_session.usuario_id)   ' . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT ' . $select . '  FROM usuario_session  INNER JOIN usuario_mandante ON (usuario_mandante.usumandante_id = usuario_session.usuario_id)' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

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
        $sql = 'DELETE FROM usuario_session WHERE usuario_id = ?';
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
        $sql = 'DELETE FROM usuario_session WHERE proveedor_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna tipo sea igual al valor pasado como parámetro
     *
     * @param String $value tipo requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByTipo($value)
    {
        $sql = 'DELETE FROM usuario_session WHERE tipo = ?';
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
        $sql = 'DELETE FROM usuario_session WHERE usucrea_id = ?';
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
        $sql = 'DELETE FROM usuario_session WHERE fecha_crea = ?';
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
        $sql = 'DELETE FROM usuario_session WHERE usumodif_id = ?';
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
        $sql = 'DELETE FROM usuario_session WHERE fecha_modif = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }


    /**
     * Crear y devolver un objeto del tipo UsuarioSession
     * con los valores de una consulta sql
     *
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Objeto $usuarioSession UsuarioSession
     *
     * @access protected
     *
     */
    protected function readRow($row)
    {
        $usuarioSession = new UsuarioSession();

        $usuarioSession->ususessionId = $row['ususession_id'];
        $usuarioSession->usuarioId = $row['usuario_id'];
        $usuarioSession->tipo = $row['tipo'];
        $usuarioSession->requestId = $row['request_id'];
        $usuarioSession->perfil = $row['perfil'];
        $usuarioSession->usucreaId = $row['usucrea_id'];
        $usuarioSession->fechaCrea = $row['fecha_crea'];
        $usuarioSession->usumodifId = $row['usumodif_id'];
        $usuarioSession->fechaModif = $row['fecha_modif'];
        $usuarioSession->estado = $row['estado'];

        return $usuarioSession;
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
