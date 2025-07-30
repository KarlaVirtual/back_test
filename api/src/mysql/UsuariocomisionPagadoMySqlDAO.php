<?php namespace Backend\mysql;
use Backend\dao\UsuariocomisionPagadoDAO;
use Backend\dto\UsuariocomisionPagado;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Exception;
use Backend\dto\Helpers;

/**
 * Clase 'UsuariocomisionPagadoMySqlDAO'
 *
 * Esta clase provee las consultas del modelo o tabla 'UsuariocomisionPagado'
 *
 * Ejemplo de uso:
 * $UsuariocomisionPagadoMySqlDAO = new UsuariocomisionPagadoMySqlDAO();
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class UsuariocomisionPagadoMySqlDAO implements UsuariocomisionPagadoDAO
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
        $sql = 'SELECT * FROM usuariocomision_pagado WHERE usucomisionpagado_id = ?';
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
        $sql = 'SELECT * FROM usuariocomision_pagado';
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
        $sql = 'SELECT * FROM usuariocomision_pagado ORDER BY ' . $orderColumn;
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }

    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $usucomisionpagado_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function delete($usucomisionpagado_id)
    {
        $sql = 'DELETE FROM usuariocomision_pagado WHERE usucomisionpagado_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($usucomisionpagado_id);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Insertar un registro en la base de datos
     *
     * @param Objeto usuariocomision_pagado usuariocomision_pagado
     *
     * @return String $id resultado de la consulta
     *
     */
    public function insert($usuariocomision_pagado)
    {
        $sql = 'INSERT INTO usuariocomision_pagado (usuario_id,fecha_inicio, fecha_fin, valor_pagado, estado,tipo, usucrea_id, usumodif_id,mandante,tipo_comision) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($usuariocomision_pagado->usuarioId);
        $sqlQuery->set($usuariocomision_pagado->fechaInicio);
        $sqlQuery->set($usuariocomision_pagado->fechaFin);
        $sqlQuery->setString($usuariocomision_pagado->valorPagado);
        $sqlQuery->setString($usuariocomision_pagado->estado);
        $sqlQuery->set($usuariocomision_pagado->tipo);
        $sqlQuery->setNumber($usuariocomision_pagado->usucreaId);
        $sqlQuery->setNumber($usuariocomision_pagado->usumodifId);
        $sqlQuery->setNumber($usuariocomision_pagado->mandante);
        $sqlQuery->setString($usuariocomision_pagado->tipoComision);

        $id = $this->executeInsert($sqlQuery);
        $usuariocomision_pagado->usucomisionpagadoId = $id;
        return $id;
    }

    /**
     * Editar un registro en la base de datos
     *
     * @param Objeto usuariocomision_pagado usuariocomision_pagado
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function update($usuariocomision_pagado)
    {
        //$sql = 'UPDATE usuariocomision_pagado SET usuario_id = ?,usuarioref_id = ?, externo_id = ?, tipo = ?, valor = ?, comision = ?, usucrea_id = ?, usumodif_id = ?, estado = ?, usucambio_id = ?, usupago_id = ?, usurechaza_id = ?, mensaje_usuario = ?, observacion = ?, valor_pagado = ? WHERE usucomisionpagado_id = ?';
        $sql = 'UPDATE usuariocomision_pagado SET usuario_id = ?,fecha_inicio = ?, fecha_fin = ?, valor_pagado = ?, estado = ?, tipo = ?, usucrea_id = ?, usumodif_id = ?, mandante = ?';

        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($usuariocomision_pagado->usuarioId);
        $sqlQuery->set($usuariocomision_pagado->fechaInicio);
        $sqlQuery->set($usuariocomision_pagado->fechaFin);
        $sqlQuery->setString($usuariocomision_pagado->valorPagado);
        $sqlQuery->setString($usuariocomision_pagado->estado);
        $sqlQuery->set($usuariocomision_pagado->tipo);
        $sqlQuery->setNumber($usuariocomision_pagado->usucreaId);
        $sqlQuery->setNumber($usuariocomision_pagado->usumodifId);
        $sqlQuery->setNumber($usuariocomision_pagado->mandante);

        $sqlQuery->setNumber($usuariocomision_pagado->usucomisionpagadoId);
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
        $sql = 'DELETE FROM usuariocomision_pagado';
        $sqlQuery = new SqlQuery($sql);
        return $this->executeUpdate($sqlQuery);
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
        $sql = 'SELECT * FROM usuariocomision_pagado WHERE fecha_crea = ?';
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
        $sql = 'SELECT * FROM usuariocomision_pagado WHERE usucrea_id = ?';
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
        $sql = 'SELECT * FROM usuariocomision_pagado WHERE usumodif_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);
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
    public function deleteByEstado($value)
    {
        $sql = 'DELETE FROM usuariocomision_pagado WHERE valor = ?';
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
        $sql = 'DELETE FROM usuariocomision_pagado WHERE fecha_crea = ?';
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
        $sql = 'DELETE FROM usuariocomision_pagado WHERE usucrea_id = ?';
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
        $sql = 'DELETE FROM usuariocomision_pagado WHERE usumodif_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->executeUpdate($sqlQuery);
    }

















    /**
     * Crear y devolver un objeto del tipo UsuariocomisionPagado
     * con los valores de una consulta sql
     *
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Objeto $usuariocomision_pagado UsuariocomisionPagado
     *
     * @access protected
     *
     */
    protected function readRow($row)
    {
        $usuariocomision_pagado = new UsuariocomisionPagado();


        $usuariocomision_pagado->usucomisionpagadoId = $row['usucomisionpagado_id'];
        $usuariocomision_pagado->usuarioId = $row['usuario_id'];
        $usuariocomision_pagado->fechaInicio = $row['fecha_inicio'];
        $usuariocomision_pagado->fechaFin = $row['fecha_fin'];
        $usuariocomision_pagado->valorPagado = $row['valor_pagado'];
        $usuariocomision_pagado->estado = $row['estado'];
        $usuariocomision_pagado->tipo = $row['tipo'];
        $usuariocomision_pagado->usucreaId = $row['usucrea_id'];
        $usuariocomision_pagado->usumodifId = $row['usumodif_id'];
        $usuariocomision_pagado->mandante= $row['mandante'];

        return $usuariocomision_pagado;
    }












    /**
     * Realizar una consulta en la tabla de UsuariocomisionPagado 'UsuariocomisionPagado'
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
    public function queryUsuariocomisionPagadosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$grouping="")
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


        //  $sql = 'SELECT count(*) count FROM (SELECT '.$select.' FROM usuariocomision_pagado  INNER JOIN usuario ON (usuariocomision_pagado.usuario_id = usuario.usuario_id) INNER JOIN usuario usuarioref ON (usuariocomision_pagado.usuarioref_id = usuarioref.usuario_id)   INNER JOIN clasificador ON (clasificador.clasificador_id = usuariocomision_pagado.tipo)   ' . $where . ') countT';
        $sql = 'SELECT count(*)  FROM usuariocomision_pagado  INNER JOIN usuario ON (usuariocomision_pagado.usuario_id = usuario.usuario_id) INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = usuario.usuario_id)  LEFT OUTER JOIN concesionario ON (concesionario.usuhijo_id = usuariocomision_pagado.usuario_id)   ' . $where ;

        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);

        $sql = 'SELECT ' .$select .' FROM usuariocomision_pagado 
        INNER JOIN usuario ON usuariocomision_pagado.usuario_id = usuario.usuario_id 
        LEFT JOIN usuario_perfil ON usuariocomision_pagado.usuario_id = usuario_perfil.usuario_id'
            . $where . " ORDER BY " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        if($_ENV["debugFixed2"]){
            print_r($sql);
        }

        $sqlQuery = new SqlQuery($sql);

        $result = $Helpers->process_data($this->execute2($sqlQuery));

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }

    /**
     * Realizar una consulta en la tabla de UsuariocomisionPagado 'UsuariocomisionPagado'
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
     * @param int $concesionarioId para consultar por concesionario
     *
     * @return Array $json resultado de la consulta
     *
     */
    public function queryUsuariocomisionPagadosCustom2($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$grouping="",$concesionarioId)
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
        $where .= " AND (usuariocomision_pagado.usuario_id = " . $concesionarioId . " OR con_hijo.usupadre_id = " . $concesionarioId . " OR con_sub.usupadre2_id = " . $concesionarioId . ")";
        if($grouping != ""){
            $where = $where . " GROUP BY " . $grouping;
        }


        $sqlCount = 'SELECT COUNT(*) FROM usuariocomision_pagado 
                    INNER JOIN usuario ON (usuariocomision_pagado.usuario_id = usuario.usuario_id)
                    LEFT JOIN concesionario AS con_hijo ON usuariocomision_pagado.usuario_id = con_hijo.usuhijo_id AND con_hijo.usupadre_id = ' . $concesionarioId .
                ' LEFT JOIN concesionario AS con_sub ON usuariocomision_pagado.usuario_id = con_sub.usuhijo_id AND con_sub.usupadre2_id = ' . $concesionarioId . $where;
        $sqlQuery = new SqlQuery($sqlCount);

        $count = $this->execute2($sqlQuery);

        $sql = 'SELECT ' .$select .'  FROM usuariocomision_pagado  INNER JOIN usuario ON (usuariocomision_pagado.usuario_id = usuario.usuario_id)
        LEFT JOIN concesionario AS con_hijo ON usuariocomision_pagado.usuario_id = con_hijo.usuhijo_id AND con_hijo.usupadre_id = '. $concesionarioId .
        ' LEFT JOIN concesionario AS con_sub ON usuariocomision_pagado.usuario_id = con_sub.usuhijo_id AND con_sub.usupadre2_id = '. $concesionarioId . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        if($_ENV["debugFixed2"]){
            print_r($sql);
        }
        $sqlQuery = new SqlQuery($sql);

        $result = $Helpers->process_data($this->execute2($sqlQuery));

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }


    /**
     * Realizar una consulta en la tabla de UsuariocomisionPagado 'UsuariocomisionPagado'
     * de una manera personalizada
     *
     * @param String $select campos de consulta
     * @param String $sidx columna para ordenar
     * @param String $sord orden los datos asc | desc
     * @param String $start inicio de la consulta
     * @param String $limit limite de la consulta
     * @param String $filters condiciones de la consulta
     * @param boolean $searchOn utilizar los filtros o no
     * @param String $group columna para agrupar
     *
     * @return Array $json resultado de la consulta
     *
     */
    public function queryUsuariocomisionPagadosGroupCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$group)
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



        $sql = 'SELECT count(*) count FROM usuariocomision_pagado INNER JOIN producto_comision ON (producto_comision.prodcomision_id = usuariocomision_pagado.tipo) INNER JOIN producto_interno ON (producto_interno.productointerno_id = producto_comision.productointerno_id)  INNER JOIN usuario ON (usuariocomision_pagado.usuario_id = usuario.usuario_id)  ' . $where;
        $sql = 'SELECT count(*) count FROM usuariocomision_pagado  INNER JOIN clasificador ON (clasificador.clasificador_id = usuariocomision_pagado.tipo)  INNER JOIN usuario ON (usuariocomision_pagado.usuario_id = usuario.usuario_id)  ' . $where;


        $where = $where . " GROUP BY ". $group ." ";
        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT ' .$select .'  FROM usuariocomision_pagado INNER JOIN producto_comision ON (producto_comision.prodcomision_id = usuariocomision_pagado.tipo) INNER JOIN producto_interno ON (producto_interno.productointerno_id = producto_comision.productointerno_id)   INNER JOIN usuario ON (usuariocomision_pagado.usuario_id = usuario.usuario_id) ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;
        $sql = 'SELECT ' .$select .'  FROM usuariocomision_pagado  INNER JOIN clasificador ON (clasificador.clasificador_id = usuariocomision_pagado.tipo)   INNER JOIN usuario ON (usuariocomision_pagado.usuario_id = usuario.usuario_id) ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;


        $sqlQuery = new SqlQuery($sql);

        $result = $Helpers->process_data($this->execute2($sqlQuery));

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
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
