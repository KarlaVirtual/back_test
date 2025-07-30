<?php namespace Backend\mysql;

use Backend\dao\UsuarioSorteoDAO;
use Backend\dto\Helpers;
use Backend\dto\UsuarioSorteo;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Exception;

/**
 * Clase 'UsuarioSorteoMySqlDAO'
 *
 * Esta clase provee las consultas del modelo o tabla 'UsuarioSorteo'
 *
 * Ejemplo de uso:
 * $UsuarioSorteoMySqlDAO = new UsuarioSorteoMySqlDAO();
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class UsuarioSorteoMySqlDAO implements UsuarioSorteoDAO
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
        $sql = 'SELECT * FROM usuario_sorteo WHERE ususorteo_id = ?';
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
        $sql = 'SELECT * FROM usuario_sorteo';
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
        $sql = 'SELECT * FROM usuario_sorteo ORDER BY ' . $orderColumn;
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }

    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $ususorteo_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function delete($ususorteo_id)
    {
        $sql = 'DELETE FROM usuario_sorteo WHERE ususorteo_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($ususorteo_id);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Insertar un registro en la base de datos
     *
     * @param Objeto usuario_sorteo usuario_sorteo
     *
     * @return String $id resultado de la consulta
     *
     */
    public function insert($usuario_sorteo)
    {
        $sql = 'INSERT INTO usuario_sorteo (usuario_id, sorteo_id, valor,posicion,valor_base, estado, usucrea_id, usumodif_id,mandante,error_id,id_externo,version,apostado,codigo,externo_id,valor_premio,premio,premio_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?)';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($usuario_sorteo->usuarioId);
        $sqlQuery->set($usuario_sorteo->sorteoId);
        $sqlQuery->set($usuario_sorteo->valor);
        $sqlQuery->set($usuario_sorteo->posicion);
        $sqlQuery->set($usuario_sorteo->valorBase);
        $sqlQuery->set($usuario_sorteo->estado);
        $sqlQuery->setNumber($usuario_sorteo->usucreaId);
        $sqlQuery->setNumber($usuario_sorteo->usumodifId);
        $sqlQuery->setNumber($usuario_sorteo->mandante);
        $sqlQuery->set($usuario_sorteo->errorId);
        $sqlQuery->set($usuario_sorteo->idExterno);
        $sqlQuery->set($usuario_sorteo->version);
        $sqlQuery->set($usuario_sorteo->apostado);
        $sqlQuery->set($usuario_sorteo->codigo);
        $sqlQuery->set($usuario_sorteo->externoId);

        if ($usuario_sorteo->valorPremio == "") {
            $usuario_sorteo->valorPremio = 0;
        }
        $sqlQuery->set($usuario_sorteo->valorPremio);
        $sqlQuery->set($usuario_sorteo->premio);
        $sqlQuery->set($usuario_sorteo->premioId);

        $id = $this->executeInsert($sqlQuery);
        $usuario_sorteo->ususorteoId = $id;
        return $id;
    }

    /**
     * Editar un registro en la base de datos
     *
     * @param Objeto usuario_sorteo usuario_sorteo
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function update($usuario_sorteo)
    {
        $sql = 'UPDATE usuario_sorteo SET usuario_id = ?, sorteo_id = ?, valor = ?, posicion = ?, valor_base = ?, estado = ?,  usucrea_id = ?,  usumodif_id = ?, mandante=?, error_id = ?, id_externo = ?, version = ?, apostado = ?, codigo = ?, externo_id = ?, valor_premio = ?, premio=?, premio_id = ? WHERE ususorteo_id = ?';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($usuario_sorteo->usuarioId);
        $sqlQuery->set($usuario_sorteo->sorteoId);
        $sqlQuery->set($usuario_sorteo->valor);
        $sqlQuery->set($usuario_sorteo->posicion);
        $sqlQuery->set($usuario_sorteo->valorBase);
        $sqlQuery->set($usuario_sorteo->estado);
        $sqlQuery->setNumber($usuario_sorteo->usucreaId);
        $sqlQuery->setNumber($usuario_sorteo->usumodifId);
        $sqlQuery->setNumber($usuario_sorteo->mandante);
        $sqlQuery->set($usuario_sorteo->errorId);
        $sqlQuery->set($usuario_sorteo->idExterno);
        $sqlQuery->set($usuario_sorteo->version);
        $sqlQuery->set($usuario_sorteo->apostado);
        $sqlQuery->set($usuario_sorteo->codigo);
        $sqlQuery->set($usuario_sorteo->externoId);
        if ($usuario_sorteo->valorPremio == "") {
            $usuario_sorteo->valorPremio = 0;
        }
        $sqlQuery->setSIN($usuario_sorteo->valorPremio);
        $sqlQuery->set($usuario_sorteo->premio);
        $sqlQuery->set($usuario_sorteo->premioId);
        $sqlQuery->setNumber($usuario_sorteo->ususorteoId);
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
        $sql = 'DELETE FROM usuario_sorteo';
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
        $sql = 'SELECT * FROM usuario_sorteo WHERE estado = ?';
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
        $sql = 'SELECT * FROM usuario_sorteo WHERE fecha_crea = ?';
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
        $sql = 'SELECT * FROM usuario_sorteo WHERE usucrea_id = ?';
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
        $sql = 'SELECT * FROM usuario_sorteo WHERE usumodif_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * las columnas usuarioId y sorteoId son iguales a los valores
     * pasados como parámetros
     *
     * @param String $usuarioId usuarioId requerido
     * @param String $sorteoId sorteoId requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByUsuarioIdAndBannerId($usuarioId, $sorteoId)
    {
        $sql = 'SELECT * FROM usuario_sorteo WHERE usuario_id = ? AND sorteo_id =?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($usuarioId);
        $sqlQuery->setNumber($sorteoId);
        return $this->getList($sqlQuery);
    }


    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna sorteo_id sea igual al valor pasado como parámetro
     *
     * @param String $value sorteo_id requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByBannerId($value)
    {
        $sql = 'DELETE FROM usuario_sorteo WHERE sorteo_id = ?';
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
        $sql = 'DELETE FROM usuario_sorteo WHERE estado = ?';
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
        $sql = 'DELETE FROM usuario_sorteo WHERE fecha_crea = ?';
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
        $sql = 'DELETE FROM usuario_sorteo WHERE usucrea_id = ?';
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
        $sql = 'DELETE FROM usuario_sorteo WHERE usumodif_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->executeUpdate($sqlQuery);
    }


    /**
     * Crear y devolver un objeto del tipo UsuarioSorteo
     * con los valores de una consulta sql
     *
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Objeto $usuario_sorteo UsuarioSorteo
     *
     * @access protected
     *
     */
    protected function readRow($row)
    {
        $usuario_sorteo = new UsuarioSorteo();

        $usuario_sorteo->ususorteoId = $row['ususorteo_id'];
        $usuario_sorteo->usuarioId = $row['usuario_id'];
        $usuario_sorteo->sorteoId = $row['sorteo_id'];
        $usuario_sorteo->valor = $row['valor'];
        $usuario_sorteo->posicion = $row['posicion'];
        $usuario_sorteo->valorBase = $row['valor_base'];
        $usuario_sorteo->estado = $row['estado'];
        $usuario_sorteo->fechaCrea = $row['fecha_crea'];
        $usuario_sorteo->usucreaId = $row['usucrea_id'];
        $usuario_sorteo->fechaModif = $row['fecha_modif'];
        $usuario_sorteo->usumodifId = $row['usumodif_id'];
        $usuario_sorteo->mandante = $row['mandante'];
        $usuario_sorteo->errorId = $row['error_id'];
        $usuario_sorteo->idExterno = $row['id_externo'];
        $usuario_sorteo->version = $row['version'];
        $usuario_sorteo->apostado = $row['apostado'];
        $usuario_sorteo->codigo = $row['codigo'];
        $usuario_sorteo->externoId = $row['externo_id'];
        $usuario_sorteo->valorPremio = $row['valor_premio'];
        $usuario_sorteo->premio = $row['premio'];
        $usuario_sorteo->premioId = $row['premio_id'];

        return $usuario_sorteo;
    }


    /**
     * Realizar una consulta en la tabla de UsuarioSorteo 'UsuarioSorteo'
     * de una manera personalizada
     *
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
    public function queryUsuarioSorteos($sidx, $sord, $start, $limit, $filters, $searchOn)
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
                if ($fieldOperation != "") $whereArray[] = $fieldName . $fieldOperation;
                if (oldCount($whereArray) > 0) {
                    $where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
                } else {
                    $where = "";
                }
            }

        }


        $sql = 'SELECT count(*) count FROM proveedor LEFT OUTER JOIN usuario_sorteo ON (usuario_sorteo.proveedor_id = proveedor.proveedor_id)' . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT proveedor.*,usuario_sorteo.* FROM proveedor LEFT OUTER JOIN usuario_sorteo ON (usuario_sorteo.proveedor_id = proveedor.proveedor_id)' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }

    /**
     * Realizar una consulta en la tabla de UsuarioSorteo 'UsuarioSorteo'
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
    public function queryUsuarioSorteosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $withPosition = false, $random = false)
    {
        $Helpers = new Helpers();
        $sorteoEspecificoParaPosicion = 0;

        $where = " where 1=1 ";


        if ($searchOn) {
            // Construye el where
            $filters = json_decode($filters);
            $whereArray = array();
            $rules = $filters->rules;
            $groupOperation = $filters->groupOp;
            $cont = 0;

            foreach ($rules as $rule) {
                $fieldName = (string)$Helpers->set_custom_field($rule->field);
                $fieldData = $rule->data;
                if ($withPosition && $fieldName == "sorteo_interno.sorteo_id") {
                    $sorteoEspecificoParaPosicion = $fieldData;
                }


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
                if ($fieldOperation != "") $whereArray[] = $fieldName . $fieldOperation;
                if (oldCount($whereArray) > 0) {
                    $where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
                } else {
                    $where = "";
                }
            }

        }
        $withPosition = false;
        if ($withPosition) {

            $sqlWherePos = "";
            if ($withPosition && $sorteoEspecificoParaPosicion != 0) {
                $sqlWherePos = " WHERE t.sorteo_id='" . $sorteoEspecificoParaPosicion . "' ";
            }
            $sql = 'SELECT count(*) count FROM usuario_sorteo INNER JOIN usuario_mandante ON (usuario_mandante.usumandante_id = usuario_sorteo.usuario_id) INNER JOIN sorteo_interno ON (sorteo_interno.sorteo_id = usuario_sorteo.sorteo_id) INNER JOIN (SELECT t.usuario_id,
                t.sorteo_id,
                u.usuario_mandante,
                u.nombres,
               t.valor,
               @rownum := @rownum + 1 AS position
          FROM  usuario_sorteo t
          INNER JOIN usuario_mandante u ON (u.usumandante_id=t.usuario_id)
          JOIN (SELECT @rownum := 0) r ' . $sqlWherePos . '
      ORDER BY t.valor DESC) position ON (position.usuario_id = usuario_sorteo.usuario_id  AND position.sorteo_id=sorteo_interno.sorteo_id)
 ' . $where;
        } elseif ($random) {

            $sql = 'SELECT count(*) count FROM usuario_sorteo INNER JOIN usuario_mandante ON (usuario_mandante.usumandante_id = usuario_sorteo.usuario_id) INNER JOIN sorteo_interno ON (sorteo_interno.sorteo_id = usuario_sorteo.sorteo_id) ' . $where
                . "  AND ususorteo_id >= FLOOR(RAND() * (SELECT MAX(ususorteo_id) FROM casino.usuario_sorteo)) ";
        } else {

            $sql = 'SELECT count(*) count FROM usuario_sorteo INNER JOIN usuario_mandante ON (usuario_mandante.usumandante_id = usuario_sorteo.usuario_id) INNER JOIN sorteo_interno ON (sorteo_interno.sorteo_id = usuario_sorteo.sorteo_id) ' . $where;
        }


        if ($_ENV["debugFixed2"] == '1') {
            print_r($sql);
        }


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);

        if ($withPosition) {

            $sqlWherePos = "";
            if ($withPosition && $sorteoEspecificoParaPosicion != 0) {
                $sqlWherePos = " WHERE t.sorteo_id='" . $sorteoEspecificoParaPosicion . "' ";
            }

            $sql = 'SELECT ' . $select . '  FROM usuario_sorteo  INNER JOIN usuario_mandante ON (usuario_mandante.usumandante_id = usuario_sorteo.usuario_id) INNER JOIN sorteo_interno ON (sorteo_interno.sorteo_id = usuario_sorteo.sorteo_id) INNER JOIN (SELECT t.usuario_id,
                t.sorteo_id,
                u.usuario_mandante,
                u.nombres,
               t.valor,
               @rownum := @rownum + 1 AS position
          FROM  usuario_sorteo t
          INNER JOIN usuario_mandante u ON (u.usumandante_id=t.usuario_id)
          JOIN (SELECT @rownum := 0) r ' . $sqlWherePos . '
      ORDER BY t.valor DESC) position ON (position.usuario_id = usuario_sorteo.usuario_id AND position.sorteo_id=sorteo_interno.sorteo_id)
 ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;
        } elseif ($random) {
            $sql = 'SELECT ' . $select . '  FROM usuario_sorteo  INNER JOIN usuario_mandante ON (usuario_mandante.usumandante_id = usuario_sorteo.usuario_id) INNER JOIN sorteo_interno ON (sorteo_interno.sorteo_id = usuario_sorteo.sorteo_id) ' . $where

                . "  AND ususorteo_id >= FLOOR(RAND() * (SELECT MAX(ususorteo_id) FROM casino.usuario_sorteo)) "


                . " LIMIT " . $start . " , " . $limit;

        } else {
            $sql = 'SELECT ' . $select . '  FROM usuario_sorteo  INNER JOIN usuario_mandante ON (usuario_mandante.usumandante_id = usuario_sorteo.usuario_id) INNER JOIN sorteo_interno ON (sorteo_interno.sorteo_id = usuario_sorteo.sorteo_id) ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        }

        if ($_ENV["debugFixed2"] == '1') {
            print_r($sql);
        }

        $sqlQuery = new SqlQuery($sql);

        $result = $Helpers->process_data($this->execute2($sqlQuery));

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }

    /**
     * Consulta personalizada de usuario_sorteos sin posición.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Índice de ordenación.
     * @param string $sord Orden de clasificación (ASC o DESC).
     * @param int $start Inicio del límite de la consulta.
     * @param int $limit Número de registros a devolver.
     * @param string $filters Filtros en formato JSON.
     * @param bool $searchOn Indica si la búsqueda está activada.
     * @return string JSON con el conteo y los datos resultantes de la consulta.
     */
    public function queryUsuarioSorteosCustomWithoutPosition($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
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
                if ($fieldOperation != "") $whereArray[] = $fieldName . $fieldOperation;
                if (oldCount($whereArray) > 0) {
                    $where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
                } else {
                    $where = "";
                }
            }

        }


        $sql = 'SELECT count(*) count FROM usuario_sorteo INNER JOIN usuario_mandante ON (usuario_mandante.usumandante_id = usuario_sorteo.usuario_id) INNER JOIN sorteo_interno ON (sorteo_interno.sorteo_id = usuario_sorteo.sorteo_id)' . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT ' . $select . '  FROM usuario_sorteo  INNER JOIN usuario_mandante ON (usuario_mandante.usumandante_id = usuario_sorteo.usuario_id) INNER JOIN sorteo_interno ON (sorteo_interno.sorteo_id = usuario_sorteo.sorteo_id)' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;


        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }

    /**
     * Realizar una consulta en la tabla de UsuarioSorteo 'UsuarioSorteo'
     * de una manera personalizada
     *
     * @param String $value value
     * @param String $category category
     * @param String $provider provider
     * @param String $offset offset
     * @param String $limit limite de la consulta
     * @param String $search search
     * @param String $partnerId partnerId
     *
     * @return Array $json resultado de la consulta
     *
     */
    public function getAllUsuarioSorteos($value, $category, $provider, $offset, $limit, $search, $partnerId)
    {
        $where = " 1=1 ";
        if ($category != "") {
            $where = $where . " AND categoria_usuario_sorteo.categoria_id= ? ";
        }

        if ($provider != "") {
            $where = $where . " AND proveedor.abreviado = ? ";
        }

        if ($search != "") {
            $where = $where . " AND usuario_sorteo.descripcion  LIKE '%" . $search . "%' ";
        }

        if ($offset == "" || $limit == "") {
            $limit = 15;
            $offset = 0;
        }


        $sql = 'SELECT proveedor.*,usuario_sorteo.*,usuario_sorteo_mandante.*, categoria_usuario_sorteo.*,categoria.*,usuario_sorteo_detalle.p_value background FROM proveedor
        LEFT OUTER JOIN usuario_sorteo ON (usuario_sorteo.proveedor_id = proveedor.proveedor_id)
INNER JOIN categoria_usuario_sorteo ON (categoria_usuario_sorteo.ususorteo_id = usuario_sorteo.ususorteo_id)
INNER JOIN usuario_sorteo_mandante ON (usuario_sorteo.ususorteo_id = usuario_sorteo_mandante.ususorteo_id AND usuario_sorteo_mandante.mandante = ' . $partnerId . ' ) LEFT OUTER JOIN categoria ON (categoria.categoria_id =categoria_usuario_sorteo.categoria_id )
LEFT OUTER JOIN usuario_sorteo_detalle ON (usuario_sorteo_detalle.ususorteo_id= usuario_sorteo.ususorteo_id AND usuario_sorteo_detalle.p_key = "IMAGE_BACKGROUND")
 WHERE ' . $where . ' AND proveedor.Tipo = ? AND proveedor.estado="A" AND usuario_sorteo.estado="A" AND usuario_sorteo.mostrar="S" LIMIT ' . ($limit - $offset) . ' OFFSET ' . $offset;

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
