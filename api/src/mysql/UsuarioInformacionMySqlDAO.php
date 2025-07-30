<?php namespace Backend\mysql;

use Backend\dto\Helpers;
use Backend\dto\UsuarioInformacion;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Backend\sql\Transaction;

/** 
 * Clase 'UsuarioInformacionMySqlDAO'
 * 
 * Esta clase provee las consultas del modelo o tabla 'UsuarioInformacion'
 * 
 * @author: Desconocido
 * @package No
 * @category No
 * @version    1.0
 * @since Desconocido
 */
class UsuarioInformacionMySqlDAO
{
    /**
     * Atributo Transaction transacción
     *
     * @var object
     */
    private $transaction;

    /**
     *
     * Obtener la transaccion de un objeto
     *
     * @return Objeto Transaction transaccion
     *
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
     * Constructor de la clase UsuarioInformacionMySqlDAO.
     *
     * @param Transaction|string $transaction Objeto de transacción opcional. Si no se proporciona, se crea una nueva instancia de Transaction.
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
     * Carga la información de un usuario desde la base de datos utilizando su ID.
     *
     * @param int $id El ID del usuario cuya información se desea cargar.
     * @return array La información del usuario correspondiente al ID proporcionado.
     */
    public function load($id)
    {
        $sql = 'SELECT * FROM usuario_informacion where usuinformacion_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($id);
        return $this->getRows($sqlQuery);
    }

    /**
     * Obtener todos los registros de la base de datos
     *
     * @return Array resultado de la consulta
     *
     *
     *
     */

    public function queryAll()
    {
        $sql = 'SELECT * FROM usuario_informacion';
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
        $sql = 'SELECT * FROM usuario_informacion ORDER BY $orderColumn';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($orderColumn);
        return $this->getList($sqlQuery);
    }

    /**
     * Elimina un registro de la tabla usuario_informacion basado en el ID proporcionado.
     *
     * @param int $id El ID del registro que se desea eliminar.
     * @return void
     */
    public function delete($id)
    {
        $sql = 'DELETE FROM usuario_informacion WHERE usuinformacion_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($id);
        $sqlQuery->set($sql);
    }

    /**
     *
     * Insertar un registro en la base de datos
     *
     * @param Object
     *
     * @return String $id resultado de la consulta
     *
     */

    public function insert($informacionUsuario)
    {


        $sql = 'INSERT INTO usuario_informacion (clasificador_id,usuario_id,valor,usucrea_id,mandante,usumodif_id) VALUES (?,?,?,?,?,?)';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($informacionUsuario->clasificadorId);
        $sqlQuery->setNumber($informacionUsuario->usuarioId);
        $sqlQuery->set($informacionUsuario->valor);
        if ($informacionUsuario->usucreaId == "") {
            $informacionUsuario->usucreaId='0';
        }
        $sqlQuery->set($informacionUsuario->usucreaId);
        $sqlQuery->set($informacionUsuario->mandante);

        if ($informacionUsuario->usumodifId == "") {
            $informacionUsuario->usumodifId='0';
        }
        $sqlQuery->set($informacionUsuario->usumodifId);

        $id = $this->executeInsert($sqlQuery);
        $informacionUsuario->usuinformacionId = $id;
        return $id;

    }

/**
 * Actualiza un registro en la tabla usuario_informacion.
 *
 * @param object $InformacionUsuario Objeto que contiene la información del usuario a actualizar.
 * @return int Número de filas afectadas por la actualización.
 */
public function update($InformacionUsuario)
{
    $sql = 'UPDATE usuario_informacion SET valor = ?, usumodif_id = ?, usuario_id = ? , clasificador_id = ? , mandante = ? WHERE usuinformacion_id = ? ';
    $sqlQuery = new SqlQuery($sql);
    $sqlQuery->set($InformacionUsuario->valor);
    $sqlQuery->set($InformacionUsuario->usumodifId);
    $sqlQuery->set($InformacionUsuario->usuarioId);
    $sqlQuery->set($InformacionUsuario->clasificadorId);
    $sqlQuery->set($InformacionUsuario->mandante);
    $sqlQuery->set($InformacionUsuario->usuinformacionId);

    return $this->executeUpdate($sqlQuery);
}

/**
 * Consulta la información de usuario por su ID.
 *
 * @param int $usuarioId ID del usuario.
 * @return array Lista de información del usuario.
 */
public function queryByUsuario($usuarioId)
{
    $sql = 'SELECT * FROM usuario_informacion where usuario_id = ?';
    $sqlQuery = new SqlQuery($sql);
    $sqlQuery->set($usuarioId);
    return $this->getList($sqlQuery);
}

/**
 * Consulta la información de usuario por clasificador, usuario y mandante.
 *
 * @param int $clasificador_id ID del clasificador.
 * @param int $usuarioId ID del usuario.
 * @param string $mandante Mandante.
 * @return array Lista de información del usuario.
 */
public function queryData($clasificador_id, $usuarioId, $mandante)
{
    $sql = 'SELECT * FROM usuario_informacion where clasificador_id = ? and usuario_id = ?' and 'mandante = ?';
    $sqlQuery = new SqlQuery($sql);
    $sqlQuery->set($clasificador_id);
    $sqlQuery->set($usuarioId);
    $sqlQuery->set($mandante);

    return $this->getList($sqlQuery);
}

/**
 * Consulta la información de usuario por clasificador, usuario y mandante.
 *
 * @param int $clasificador_id ID del clasificador.
 * @param int $usuarioId ID del usuario.
 * @param string $mandante Mandante.
 * @return array Lista de información del usuario.
 */
public function queryByClasificador($clasificador_id, $usuarioId, $mandante)
{
    $sql = 'SELECT * FROM usuario_informacion where clasificador_id = ? and usuario_id = ?' and 'mandante = ?';
    $sqlQuery = new SqlQuery($sql);
    $sqlQuery->set($clasificador_id);
    $sqlQuery->set($usuarioId);
    $sqlQuery->set($mandante);

    return $this->getList($sqlQuery);
}

/**
 * Verifica si existe un usuario con los datos proporcionados.
 *
 * @param int $clasificador_id ID del clasificador.
 * @param string $valor Valor a verificar.
 * @param string $mandante Mandante.
 * @return bool True si existen registros, false en caso contrario.
 */
public function verificarUsuario($clasificador_id, $valor, $mandante)
{
    $sql = 'SELECT * FROM usuario_informacion WHERE clasificador_id = ? AND valor = ? AND mandante = ?';
    $sqlQuery = new SqlQuery($sql);
    $sqlQuery->set($clasificador_id);
    $sqlQuery->set($valor);
    $sqlQuery->set($mandante);

    $result = $this->execute2($sqlQuery);

    // Verificar si se retornaron registros reales
    if (is_array($result) && count($result) > 0) {
        return true; // Hay registros
    } else {
        return false; // No hay registros
    }
}

/**
 * Consulta la información de usuario por mandante.
 *
 * @param string $mandante Mandante.
 * @return array Lista de información del usuario.
 */
public function queryByMandante($mandante)
{
    $sql = 'SELECT * FROM usuario_informacion where mandante = ?';
    $sqlQuery = new SqlQuery($sql);
    $sqlQuery->set($mandante);
    return $this->getList($sqlQuery);
}

/**
 * Consulta la información de usuario por valor.
 *
 * @param string $valor Valor a consultar.
 * @return array Lista de información del usuario.
 */
public function queryByVerifica($valor)
{
    $sql = 'SELECT * FROM usuario_informacion where valor = ?';
    $sqlQuery = new SqlQuery($sql);
    $sqlQuery->set($valor);
    return $this->getList($sqlQuery);
}
    /**
     * realizar una consulta a la tabla usuario_informacion de una manera personalizada
     *
     * @param String $select campos de consulta
     * @param String $sidx columna para ordenar
     * @param String $sord columna para oden de los datos ASC|DESC
     * @param String $start inicio de la consulta
     * @param String $limit limite de la consulta
     * @param String $filters condiciones de la consulta
     * @param boolean $searchOn utilizar o no los filtros
     * @param String $grouping columna para agrupar
     *
     */


    public function queryUsuarioInformacionCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping)
    {
        $where = "where 1= 1";

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

                if ($fieldOperation != "") {
                    $whereArray[] = $fieldName . $fieldOperation;
                }

                if (count($whereArray) > 0) {
                    $where = $where . " " . $groupOperation . " UCASE(CAST(" . $fieldName . " AS CHAR)) " . strtoupper($fieldOperation);
                } else {
                    $where = "";
                }
            }
        }

        $sql = 'SELECT COUNT(*) FROM usuario_informacion  INNER JOIN clasificador ON (usuario_informacion.clasificador_id = clasificador.clasificador_id) INNER JOIN usuario ON (usuario_informacion.usuario_id = usuario.usuario_id) ' . ' ' . $where;

        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);

        $sql = "SELECT ".$select." FROM usuario_informacion INNER JOIN clasificador ON (usuario_informacion.clasificador_id = clasificador.clasificador_id) INNER JOIN usuario ON (usuario_informacion.usuario_id = usuario.usuario_id)  " . $where . " " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;

    }


    /**
     * Lee una fila de datos y la convierte en un objeto UsuarioInformacion.
     *
     * @param array $row La fila de datos obtenida de la base de datos.
     * 
     * @return UsuarioInformacion El objeto UsuarioInformacion con los datos de la fila.
     */
    protected function readRow($row)
    {

        $usuarioInformacion = new UsuarioInformacion();
        $usuarioInformacion->usuinformacionId = $row['usuinformacion_id'];
        $usuarioInformacion->clasificadorId = $row['clasificador_id'];
        $usuarioInformacion->usuarioId = $row['usuario_id'];
        $usuarioInformacion->valor = $row['valor'];
        $usuarioInformacion->mandante = $row['mandante'];
        $usuarioInformacion->fechaCrea = $row['fecha_crea'];
        $usuarioInformacion->usucreaId = $row['usucrea_id'];
        $usuarioInformacion->fechaModif = $row['fecha_modif'];
        $usuarioInformacion->usumodif = $row['usumodif'];

        return $usuarioInformacion;
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

    protected function getRows($sqlQuery)
    {
        $tab = QueryExecutor::execute($this->transaction, $sqlQuery);
        if (count($tab) == 0) {
            return null;
        }
        return $this->readRow($tab[0]);
    }

    /**
     * Obtiene una lista de resultados a partir de una consulta SQL.
     *
     * @param SqlQuery $sqlQuery La consulta SQL a ejecutar.
     * @return array Un arreglo de resultados obtenidos de la consulta SQL.
     */
    protected function getList($sqlQuery)
    {
        $tab = QueryExecutor::execute($this->transaction, $sqlQuery);
        $ret = array();
        for ($i = 0; $i < count($tab); $i++) {
            $ret[$i] = $this->readRow($tab[$i]);
        }
        return $ret;
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