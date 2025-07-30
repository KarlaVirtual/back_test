<?php

namespace Backend\mysql;

use Backend\dao\ReferidoInvitacionDAO;
use Backend\dto\Helpers;
use Backend\dto\ReferidoInvitacion;
use Backend\sql\SqlQuery;
use Backend\sql\Transaction;
use Backend\sql\QueryExecutor;
use Exception;


/**
 * Clase 'ReferidoInvitacionMySqlDAO'
 *
 * Esta clase provee las consultas del modelo o tabla 'RangoIngreso'
 *
 * Ejemplo de uso:
 * $RangoIngresoMySqlDAO = new RangoIngresoMySqlDAO();
 *
 *
 * @package ninguno
 * @author desconocido
 * @version ninguna
 * @access public
 * @see no
 *
 */

class ReferidoInvitacionMySqlDAO implements ReferidoInvitacionDAO
{
    var $transaction;

  /**
 * Constructor de la clase ReferidoInvitacionMySqlDAO
 *
 * @param Transaction $transaction objeto de transacción opcional
 */
public function __construct($transaction = "")
{
    if ($transaction == null || $transaction == "") {
        $this->setTransaction(new Transaction());
    }
    else {
        $this->setTransaction($transaction);
    }
}
    /**
     * Obtener la transacción actual
     *
     * @return Transaction
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * Establecer la transacción
     *
     * @param Transaction $transaction Objeto de transacción
     */
    public function setTransaction($transaction): void
    {
        $this->transaction = $transaction;
    }


    /**
     * Cargar un registro por su ID
     *
     * @param int $id ID del registro
     * @return ReferidoInvitacion|null
     */
    function load($id)
    {
        $sql = 'SELECT * FROM referido_invitacion WHERE refinvitacion_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($id);
        return $this->getRow($sqlQuery);
    }

    /**
     * Consultar todos los registros
     *
     * @return array
     */
    function queryAll()
    {
        $sql = 'SELECT * FROM referido_invitacion';
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }

    /**
     * Consultar todos los registros ordenados por una columna
     *
     * @param string $orderColumn Nombre de la columna para ordenar
     * @return array
     */
    function queryAllOrderBy($orderColumn)
    {
        $sql = 'SELECT * FROM referido_invitacion ORDER BY ' . $orderColumn;
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }

    /**
     * Insertar un nuevo registro
     *
     * @param ReferidoInvitacion $referidoInvitacion Objeto ReferidoInvitacion
     * @return int ID del nuevo registro
     */

    function insert(ReferidoInvitacion $ReferidoInvitacion)
    {
        $sql = 'insert into referido_invitacion (usuid_referente, referido_email, referido_exitoso, asunto, mensaje, leido, usucrea_id, usumodif_id, estado) values (?, ?, ?, ?, ?, ?, ?, ?, ?)';

        $sqlQuery = new sqlQuery($sql);
        $sqlQuery->setNumber($ReferidoInvitacion->getUsuidReferente());
        $sqlQuery->set($ReferidoInvitacion->getReferidoEmail());
        $sqlQuery->setNumber($ReferidoInvitacion->getReferidoExitoso());
        $sqlQuery->set($ReferidoInvitacion->getAsunto());
        $sqlQuery->set($ReferidoInvitacion->getMensaje());
        $sqlQuery->setNumber($ReferidoInvitacion->getLeido());
        $sqlQuery->setNumber($ReferidoInvitacion->getUsucreaId());
        $sqlQuery->setNumber($ReferidoInvitacion->getUsumodifId());
        $sqlQuery->set($ReferidoInvitacion->getEstado());

        $id = $this->executeInsert($sqlQuery);
        $ReferidoInvitacion->setRefinvitacionId($id);
        return $id;
    }

    /**
     * Actualizar un registro existente
     *
     * @param ReferidoInvitacion $referidoInvitacion Objeto ReferidoInvitacion
     * @return bool Resultado de la actualización
     */
    function update(ReferidoInvitacion $ReferidoInvitacion)
    {
        $sql = 'UPDATE referido_invitacion SET usuid_referente = ?, referido_email = ?, referido_exitoso = ?, asunto = ?, mensaje = ?, leido = ?, usucrea_id = ?, usumodif_id = ?, estado = ? where refinvitacion_id = ?';

        $sqlQuery = new sqlQuery($sql);
        $sqlQuery->setNumber($ReferidoInvitacion->getUsuidReferente());
        $sqlQuery->set($ReferidoInvitacion->getReferidoEmail());
        $sqlQuery->setNumber($ReferidoInvitacion->getReferidoExitoso());
        $sqlQuery->set($ReferidoInvitacion->getAsunto());
        $sqlQuery->set($ReferidoInvitacion->getMensaje());
        $sqlQuery->setNumber($ReferidoInvitacion->getLeido());
        $sqlQuery->setNumber($ReferidoInvitacion->getUsucreaId());
        $sqlQuery->setNumber($ReferidoInvitacion->getUsumodifId());
        $sqlQuery->set($ReferidoInvitacion->getEstado());
        $sqlQuery->set($ReferidoInvitacion->getRefinvitacionId());

        return $this->executeUpdate($sqlQuery);
    }
    /**
     * Eliminar un registro por su ID
     *
     * @param int $refinvitacionId ID del registro
     * @return bool Resultado de la eliminación
     */
    function delete($refinvitacionId)
    {
        $sql = 'DELETE FROM referido_invitacion WHERE refinvitacion_id = ?';

        $SqlQuery = new SqlQuery($sql);
        $SqlQuery->setNumber($refinvitacionId);

        return $this->executeUpdate($SqlQuery);
    }

    /**
     * Crear y devolver un objeto del tipo Banco
     * con los valores de una consulta sql
     *
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Objeto $Banco Banco
     *
     * @access protected
     *
     */
    protected function readRow($row)
    {
        $ReferidoInvitacion = new ReferidoInvitacion();

        $ReferidoInvitacion->refinvitacionId = $row['refinvitacion_id'];
        $ReferidoInvitacion->usuidReferente = $row['usuid_referente'];
        $ReferidoInvitacion->referidoEmail = $row['referido_email'];
        $ReferidoInvitacion->referidoExitoso = $row['referido_exitoso'];
        $ReferidoInvitacion->asunto = $row['asunto'];
        $ReferidoInvitacion->mensaje = $row['mensaje'];
        $ReferidoInvitacion->leido = $row['leido'];
        $ReferidoInvitacion->usucreaId = $row['usucrea_id'];
        $ReferidoInvitacion->usumodifId = $row['usumodif_id'];
        $ReferidoInvitacion->fechaCrea = $row['fecha_crea'];
        $ReferidoInvitacion->fechaModif = $row['fecha_modif'];
        $ReferidoInvitacion->estado = $row['estado'];

        return $ReferidoInvitacion;
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
        if (count($tab) == 0) {
            return null;
        }
        return $this->readRow($tab[0]);
    }

    /**
     * Ejecutar una consulta sql y devolver los datos
     * como un arreglo asociativo
     *
     *
     * @param String $sqlQuery consulta sql
     *
     * @return Array $ret arreglo asociativo
     *
     * @access protected
     *
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
     * @param String $sql consulta sql
     *
     * @return Array $ resultado de la ejecución
     *
     * @access protected
     *
     */
    public function querySQL($sql)
    {
        $sqlQuery = new SqlQuery($sql);
        return $this->execute2($sqlQuery);
    }

    /**
     * Ejecutar una consulta sql
     *
     *
     * @param $sqlQuery consulta sql
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

    //En desarrollo ...

    /**
     * Consultar registros personalizados de ReferidoInvitacion.
     *
     * Construye un filtro (WHERE) a partir de los parámetros recibidos.
     *
     * @param string $select Columnas que se van a seleccionar
     * @param string $sidx Nombre de la columna para ordenar
     * @param string $sord Dirección del orden (ASC o DESC)
     * @param int $start Índice de inicio de los registros
     * @param int $limit Cantidad de registros a devolver
     * @param string $filters Filtros en formato JSON
     * @param bool $searchOn Indica si se está aplicando algún filtro
     * @param bool $onlyCount Indica si solo se debe obtener la cantidad de registros
     *
     * @return string Respuesta en formato JSON con la cuenta y los datos obtenidos
     */
    public function queryReferidoInvitacionCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $onlyCount = false)
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
                if (count($whereArray) > 0) {
                    $where = $where . " " . $groupOperation . " UCASE(CAST(" . $fieldName . " AS CHAR)) " . strtoupper($fieldOperation);
                }
                else {
                    $where = "";
                }
            }

        }


        $sql = "select count(*) as count from referido_invitacion inner join usuario_otrainfo on (referido_invitacion.usuid_referente = usuario_otrainfo.usuario_id) inner join usuario on (usuario_otrainfo.usuario_id = usuario.usuario_id) " . $where;
        $sqlQuery = new SqlQuery($sql);
        $count = $this->execute2($sqlQuery);
        if ($onlyCount) return '{"count":'. json_encode($count) .'}';

        $sql = "select ". $select ." from referido_invitacion inner join usuario_otrainfo on (referido_invitacion.usuid_referente = usuario_otrainfo.usuario_id) inner join usuario on (usuario_otrainfo.usuario_id = usuario.usuario_id) ". $where ." order by ". $sidx ." "." ". $sord ." limit " . $start . " , " . $limit;
        $sqlQuery = new SqlQuery($sql);
        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';
        return $json;
    }


}