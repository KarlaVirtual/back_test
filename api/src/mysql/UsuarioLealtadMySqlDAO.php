<?php namespace Backend\mysql;

use Backend\dao\UsuarioLealtadDAO;
use Backend\dto\Helpers;
use Backend\dto\UsuarioLealtad;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Exception;
/**
 * Clase 'UsuarioLealtadMySqlDAO'
 *
 * Esta clase provee las consultas del modelo o tabla 'UsuarioLealtad'
 *
 * Ejemplo de uso:
 * $UsuarioLealtadMySqlDAO = new UsuarioLealtadMySqlDAO();
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class UsuarioLealtadMySqlDAO implements UsuarioLealtadDAO
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
        $sql = 'SELECT * FROM usuario_lealtad WHERE usulealtad_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($id);
        return $this->getRow($sqlQuery);
    }

/**
     * Obtener registros de usuario\_lealtad por usuario\_id
     *
     * @param int \$usuarioId ID del usuario
     * @return array Resultado de la consulta
     */
    public function queryByUsuarioId($usuarioId)
    {
        $sql = 'SELECT * FROM usuario_lealtad WHERE usuario_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($usuarioId);
        return $this->getRow($sqlQuery);
    }

    /**
     * Obtener registros de usuario\_lealtad por usuario\_id y lealtad\_id
     *
     * @param int \$usuarioId ID del usuario
     * @param int \$lealtadId ID de lealtad
     * @return array Resultado de la consulta
     */
    public function queryByUsuarioIdAndLealtadId($usuarioId, $lealtadId)
    {
        $sql = 'SELECT * FROM usuario_lealtad WHERE usuario_id = ? AND lealtad_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($usuarioId);
        $sqlQuery->setNumber($lealtadId);
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
        $sql = 'SELECT * FROM usuario_lealtad';
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
        $sql = 'SELECT * FROM usuario_lealtad ORDER BY ' . $orderColumn;
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }

    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $usulealtad_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function delete($usulealtad_id)
    {
        $sql = 'DELETE FROM usuario_lealtad WHERE usulealtad_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($usulealtad_id);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Insertar un registro en la base de datos
     *
     * @param Objeto usuario_lealtad usuario_lealtad
     *
     * @return String $id resultado de la consulta
     *
     */
    public function insert($usuario_lealtad)
    {
        $sql = 'INSERT INTO usuario_lealtad (usuario_id, lealtad_id, valor,valor_lealtad,valor_base, estado, usucrea_id,
                             usumodif_id,mandante,error_id,id_externo,version,apostado,rollower_requerido,codigo,externo_id,
                             premio,observacion,puntoventaentrega,nombreusuentrega, apellidousuentrega, cedulausuentrega,
                             telefonousuentrega,ciudadusuentrega,provinciausuentrega,direccionusuentrega,teamusuentrega
                             ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?,?,?,?,?,?,?,?,?)';


        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($usuario_lealtad->usuarioId);
        $sqlQuery->set($usuario_lealtad->lealtadId);
        $sqlQuery->set($usuario_lealtad->valor);
        $sqlQuery->set($usuario_lealtad->valorLealtad);
        $sqlQuery->set($usuario_lealtad->valorBase);
        $sqlQuery->set($usuario_lealtad->estado);
        $sqlQuery->setNumber($usuario_lealtad->usucreaId);
        $sqlQuery->setNumber($usuario_lealtad->usumodifId);
        $sqlQuery->setNumber($usuario_lealtad->mandante);
        $sqlQuery->set($usuario_lealtad->errorId);
        $sqlQuery->set($usuario_lealtad->idExterno);
        $sqlQuery->set($usuario_lealtad->version);
        $sqlQuery->set($usuario_lealtad->apostado);
        $sqlQuery->set($usuario_lealtad->rollowerRequerido);
        $sqlQuery->set($usuario_lealtad->codigo);
        $sqlQuery->set($usuario_lealtad->externoId);
        $sqlQuery->set($usuario_lealtad->premio);
        $sqlQuery->set($usuario_lealtad->observacion);


        $sqlQuery->setNumber($usuario_lealtad->puntoventaentrega);
        $sqlQuery->set($usuario_lealtad->nombreusuentrega);
        $sqlQuery->set($usuario_lealtad->apellidousuentrega);
        $sqlQuery->set($usuario_lealtad->cedulausuentrega);
        $sqlQuery->set($usuario_lealtad->telefonousuentrega);
        $sqlQuery->set($usuario_lealtad->ciudadusuentrega);
        $sqlQuery->set($usuario_lealtad->provinciausuentrega);
        $sqlQuery->set($usuario_lealtad->direccionusuentrega);
        $sqlQuery->set($usuario_lealtad->teamusuentrega);

        $id = $this->executeInsert($sqlQuery);
        $usuario_lealtad->usulealtadId = $id;

        return $id;
    }


    /**
     * Editar un registro en la base de datos
     *
     * @param Objeto usuario_lealtad usuario_lealtad
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function update($usuario_lealtad)
    {
        $sql = 'UPDATE usuario_lealtad SET usuario_id = ?, lealtad_id = ?, valor = ?, valor_lealtad = ?, valor_base = ?, 
                           estado = ?,  usucrea_id = ?,  usumodif_id = ?, mandante=?, error_id = ?, id_externo = ?, 
                           version = ?, apostado = ?, rollower_requerido = ?, codigo = ?, externo_id = ?, premio = ?, 
                           observacion = ?, puntoventaentrega = ?,nombreusuentrega = ?, apellidousuentrega = ?, cedulausuentrega = ?,
                             telefonousuentrega = ?,ciudadusuentrega = ?,provinciausuentrega = ?,direccionusuentrega= ?,teamusuentrega = ? WHERE usulealtad_id = ?';



        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($usuario_lealtad->usuarioId);
        $sqlQuery->set($usuario_lealtad->lealtadId);
        $sqlQuery->set($usuario_lealtad->valor);
        $sqlQuery->set($usuario_lealtad->valorLealtad);
        $sqlQuery->set($usuario_lealtad->valorBase);
        $sqlQuery->set($usuario_lealtad->estado);
        $sqlQuery->setNumber($usuario_lealtad->usucreaId);
        $sqlQuery->setNumber($usuario_lealtad->usumodifId);
        $sqlQuery->setNumber($usuario_lealtad->mandante);
        $sqlQuery->set($usuario_lealtad->errorId);
        $sqlQuery->set($usuario_lealtad->idExterno);
        $sqlQuery->set($usuario_lealtad->version);
        $sqlQuery->set($usuario_lealtad->apostado);
        $sqlQuery->set($usuario_lealtad->rollowerRequerido);
        $sqlQuery->set($usuario_lealtad->codigo);
        $sqlQuery->set($usuario_lealtad->externoId);
        $sqlQuery->set($usuario_lealtad->premio);
        $sqlQuery->set($usuario_lealtad->observacion);

        $sqlQuery->set($usuario_lealtad->puntoventaentrega);
        $sqlQuery->set($usuario_lealtad->nombreusuentrega);
        $sqlQuery->set($usuario_lealtad->apellidousuentrega);
        $sqlQuery->set($usuario_lealtad->cedulausuentrega);
        $sqlQuery->set($usuario_lealtad->telefonousuentrega);
        $sqlQuery->set($usuario_lealtad->ciudadusuentrega);
        $sqlQuery->set($usuario_lealtad->provinciausuentrega);
        $sqlQuery->set($usuario_lealtad->direccionusuentrega);
        $sqlQuery->set($usuario_lealtad->teamusuentrega);

        $sqlQuery->setNumber($usuario_lealtad->usulealtadId);
        return $this->executeUpdate($sqlQuery);
    }


    public function updateState($usuario_lealtad)
    {
        $sql = 'UPDATE usuario_lealtad SET  estado = ?, observacion = ? WHERE usulealtad_id = ?';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->set($usuario_lealtad->estado);
        $sqlQuery->set($usuario_lealtad->observacion);

        $sqlQuery->setNumber($usuario_lealtad->usulealtadId);
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
        $sql = 'DELETE FROM usuario_lealtad';
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
        $sql = 'SELECT * FROM usuario_lealtad WHERE estado = ?';
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
        $sql = 'SELECT * FROM usuario_lealtad WHERE fecha_crea = ?';
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
        $sql = 'SELECT * FROM usuario_lealtad WHERE usucrea_id = ?';
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
        $sql = 'SELECT * FROM usuario_lealtad WHERE usumodif_id = ?';
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
    public function queryByUsuarioIdAndBannerId($usuarioId,$lealtadId)
    {
        $sql = 'SELECT * FROM usuario_lealtad WHERE usuario_id = ? AND lealtad_id =?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($usuarioId);
        $sqlQuery->setNumber($lealtadId);
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
        $sql = 'DELETE FROM usuario_lealtad WHERE lealtad_id = ?';
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
        $sql = 'DELETE FROM usuario_lealtad WHERE estado = ?';
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
        $sql = 'DELETE FROM usuario_lealtad WHERE fecha_crea = ?';
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
        $sql = 'DELETE FROM usuario_lealtad WHERE usucrea_id = ?';
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
        $sql = 'DELETE FROM usuario_lealtad WHERE usumodif_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->executeUpdate($sqlQuery);
    }












    /**
     * Crear y devolver un objeto del tipo UsuarioLealtad
     * con los valores de una consulta sql
     *
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Objeto $usuario_lealtad UsuarioLealtad
     *
     * @access protected
     *
     */
    protected function readRow($row)
    {
        $usuario_lealtad = new UsuarioLealtad();

        $usuario_lealtad->usulealtadId = $row['usulealtad_id'];
        $usuario_lealtad->usuarioId = $row['usuario_id'];
        $usuario_lealtad->lealtadId = $row['lealtad_id'];
        $usuario_lealtad->valor = $row['valor'];
        $usuario_lealtad->valorLealtad = $row['valor_lealtad'];
        $usuario_lealtad->valorBase = $row['valor_base'];
        $usuario_lealtad->estado = $row['estado'];
        $usuario_lealtad->fechaCrea = $row['fecha_crea'];
        $usuario_lealtad->usucreaId = $row['usucrea_id'];
        $usuario_lealtad->fechaModif = $row['fecha_modif'];
        $usuario_lealtad->usumodifId = $row['usumodif_id'];
        $usuario_lealtad->mandante = $row['mandante'];
        $usuario_lealtad->errorId = $row['error_id'];
        $usuario_lealtad->idExterno = $row['id_externo'];
        $usuario_lealtad->version = $row['version'];
        $usuario_lealtad->apostado = $row['apostado'];
        $usuario_lealtad->rollowerRequerido = $row['rollower_requerido'];
        $usuario_lealtad->codigo = $row['codigo'];
        $usuario_lealtad->externoId = $row['externo_id'];
        $usuario_lealtad->premio = $row['premio'];
        $usuario_lealtad->observacion = $row['observacion'];


        $usuario_lealtad->puntoventaentrega = $row['puntoventaentrega'];
        $usuario_lealtad->nombreusuentrega = $row['nombreusuentrega'];
        $usuario_lealtad->apellidousuentrega = $row['apellidousuentrega'];
        $usuario_lealtad->cedulausuentrega = $row['cedulausuentrega'];
        $usuario_lealtad->telefonousuentrega = $row['telefonousuentrega'];
        $usuario_lealtad->ciudadusuentrega = $row['ciudadusuentrega'];
        $usuario_lealtad->provinciausuentrega = $row['provinciausuentrega'];
        $usuario_lealtad->direccionusuentrega = $row['direccionusuentrega'];
        $usuario_lealtad->teamusuentrega = $row['teamusuentrega'];

        return $usuario_lealtad;
    }




    /**
     * Realizar una consulta en la tabla de UsuarioLealtad 'UsuarioLealtad'
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
    public function queryUsuarioLealtad($sidx, $sord, $start, $limit, $filters, $searchOn)
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


        $sql = 'SELECT count(*) count FROM proveedor LEFT OUTER JOIN usuario_lealtad ON (usuario_lealtad.proveedor_id = proveedor.proveedor_id)' . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT proveedor.*,usuario_lealtad.* FROM proveedor LEFT OUTER JOIN usuario_lealtad ON (usuario_lealtad.proveedor_id = proveedor.proveedor_id)' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }

    /**
     * Realizar una consulta en la tabla de UsuarioLealtad 'UsuarioLealtad'
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
    public function queryUsuarioLealtadCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$grouping="",$withIdLealtadDetalle="",$withIdLealtadDetalle2="")
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



        if($grouping != ""){
            $where = $where . " GROUP BY " . $grouping;
        }

        $leftQuery="";

        if($withIdLealtadDetalle != ""){
            $leftQuery = " LEFT OUTER JOIN lealtad_detalle ON (lealtad_interna.lealtad_id = lealtad_detalle.lealtad_id AND lealtad_detalle.tipo='".$withIdLealtadDetalle."') ";
        }

        if($withIdLealtadDetalle2 != ""){
            $leftQuery = " LEFT OUTER JOIN lealtad_detalle lealtad_detalle2 ON (lealtad_interna.lealtad_id = lealtad_detalle2.lealtad_id AND lealtad_detalle2.tipo IN (".$withIdLealtadDetalle2.") ) ";
        }


        $sql = 'SELECT count(*) count FROM usuario_lealtad INNER JOIN lealtad_interna ON (lealtad_interna.lealtad_id = usuario_lealtad.lealtad_id) LEFT OUTER JOIN usuario ON (usuario.usuario_id = usuario_lealtad.usuario_id) LEFT OUTER JOIN lealtad_log ON (lealtad_log.id_externo = usuario_lealtad.usulealtad_id) LEFT OUTER JOIN usuario_mandante ON (usuario_mandante.usuario_mandante = usuario_lealtad.usuario_id)  ' . $leftQuery . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT ' .$select .'  FROM usuario_lealtad INNER JOIN lealtad_interna ON (lealtad_interna.lealtad_id = usuario_lealtad.lealtad_id)  LEFT OUTER JOIN usuario ON (usuario.usuario_id = usuario_lealtad.usuario_id) LEFT OUTER JOIN lealtad_log ON (lealtad_log.id_externo = usuario_lealtad.usulealtad_id) LEFT OUTER JOIN usuario_mandante ON (usuario_mandante.usuario_mandante = usuario_lealtad.usuario_id)' . $leftQuery . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);


        $result = $Helpers->process_data($this->execute2($sqlQuery));

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }

    /**
     * Realizar una consulta en la tabla de UsuarioLealtad 'UsuarioLealtad'
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
    public function getAllUsuarioLealtad($value, $category, $provider, $offset, $limit, $search, $partnerId)
    {
        $where = " 1=1 ";
        if ($category != "") {
            $where = $where . " AND categoria_usuario_lealtad.categoria_id= ? ";
        }

        if ($provider != "") {
            $where = $where . " AND proveedor.abreviado = ? ";
        }

        if ($search != "") {
            $where = $where . " AND usuario_lealtad.descripcion  LIKE '%" . $search . "%' ";
        }

        if ($offset == "" || $limit == "") {
            $limit = 15;
            $offset = 0;
        }


        $sql = 'SELECT proveedor.*,usuario_lealtad.*,usuario_lealtad_mandante.*, categoria_usuario_lealtad.*,categoria.*,usuario_lealtad_detalle.p_value background FROM proveedor
        LEFT OUTER JOIN usuario_lealtad ON (usuario_lealtad.proveedor_id = proveedor.proveedor_id)
INNER JOIN categoria_usuario_lealtad ON (categoria_usuario_lealtad.usulealtad_id = usuario_lealtad.usulealtad_id)
INNER JOIN usuario_lealtad_mandante ON (usuario_lealtad.usulealtad_id = usuario_lealtad_mandante.usulealtad_id AND usuario_lealtad_mandante.mandante = ' . $partnerId . ' ) LEFT OUTER JOIN categoria ON (categoria.categoria_id =categoria_usuario_lealtad.categoria_id )
LEFT OUTER JOIN usuario_lealtad_detalle ON (usuario_lealtad_detalle.usulealtad_id= usuario_lealtad.usulealtad_id AND usuario_lealtad_detalle.p_key = "IMAGE_BACKGROUND")
 WHERE ' . $where . ' AND proveedor.Tipo = ? AND proveedor.estado="A" AND usuario_lealtad.estado="A" AND usuario_lealtad.mostrar="S" LIMIT ' . ($limit - $offset) . ' OFFSET ' . $offset;

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
