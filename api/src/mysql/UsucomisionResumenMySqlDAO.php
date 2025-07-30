<?php namespace Backend\mysql;
use Backend\dao\UsucomisionResumenDAO;
use Backend\dto\Helpers;
use Backend\dto\UsucomisionResumen;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Exception;
/**
 * Clase 'UsucomisionResumenMySqlDAO'
 *
 * Esta clase provee las consultas del modelo o tabla 'UsucomisionResumen'
 *
 * Ejemplo de uso:
 * $UsucomisionResumenMySqlDAO = new UsucomisionResumenMySqlDAO();
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class UsucomisionResumenMySqlDAO implements UsucomisionResumenDAO
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
        $sql = 'SELECT * FROM usucomision_resumen WHERE usucomresumen_id = ?';
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
        $sql = 'SELECT * FROM usucomision_resumen';
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
        $sql = 'SELECT * FROM usucomision_resumen ORDER BY ' . $orderColumn;
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }

    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $usucomresumen_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function delete($usucomresumen_id)
    {
        $sql = 'DELETE FROM usucomision_resumen WHERE usucomresumen_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($usucomresumen_id);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Insertar un registro en la base de datos
     *
     * @param Objeto usucomision_resumen usucomision_resumen
     *
     * @return String $id resultado de la consulta
     *
     */
    public function insert($usucomision_resumen)
    {
        $sql = 'INSERT INTO usucomision_resumen (usuario_id,usuarioref_id, externo_id, tipo, valor,comision, usucrea_id, usumodif_id,estado,usucambio_id,usupago_id,usurechaza_id,mensaje_usuario,observacion,valor_pagado) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($usucomision_resumen->usuarioId);
        $sqlQuery->setNumber($usucomision_resumen->usuariorefId);
        $sqlQuery->set($usucomision_resumen->externoId);
        $sqlQuery->set($usucomision_resumen->tipo);
        $sqlQuery->set($usucomision_resumen->valor);
        $sqlQuery->set($usucomision_resumen->comision);
        $sqlQuery->setNumber($usucomision_resumen->usucreaId);
        $sqlQuery->setNumber($usucomision_resumen->usumodifId);
        $sqlQuery->setString($usucomision_resumen->estado);
        $sqlQuery->setNumber($usucomision_resumen->usucambioId);
        $sqlQuery->setNumber($usucomision_resumen->usupagoId);
        $sqlQuery->setNumber($usucomision_resumen->usurechazaId);
        $sqlQuery->setString($usucomision_resumen->mensajeUsuario);
        $sqlQuery->setString($usucomision_resumen->observacion);
        $sqlQuery->setString($usucomision_resumen->valorPagado);

        $id = $this->executeInsert($sqlQuery);
        $usucomision_resumen->usucomresumenId = $id;
        return $id;
    }

    /**
     * Editar un registro en la base de datos
     *
     * @param Objeto usucomision_resumen usucomision_resumen
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function update($usucomision_resumen)
    {
        $sql = 'UPDATE usucomision_resumen SET usuario_id = ?,usuarioref_id = ?, externo_id = ?, tipo = ?, valor = ?, comision = ?, usucrea_id = ?, usumodif_id = ?, estado = ?, usucambio_id = ?, usupago_id = ?, usurechaza_id = ?, mensaje_usuario = ?, observacion = ?, valor_pagado = ? WHERE usucomresumen_id = ?';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($usucomision_resumen->usuarioId);
        $sqlQuery->setNumber($usucomision_resumen->usuariorefId);
        $sqlQuery->set($usucomision_resumen->externoId);
        $sqlQuery->set($usucomision_resumen->tipo);
        $sqlQuery->set($usucomision_resumen->valor);
        $sqlQuery->set($usucomision_resumen->comision);
        $sqlQuery->setNumber($usucomision_resumen->usucreaId);
        $sqlQuery->setNumber($usucomision_resumen->usumodifId);
        $sqlQuery->setString($usucomision_resumen->estado);
        $sqlQuery->setNumber($usucomision_resumen->usucambioId);
        $sqlQuery->setNumber($usucomision_resumen->usupagoId);
        $sqlQuery->setNumber($usucomision_resumen->usurechazaId);
        $sqlQuery->setString($usucomision_resumen->mensajeUsuario);
        $sqlQuery->setString($usucomision_resumen->observacion);
        $sqlQuery->setString($usucomision_resumen->valorPagado);


        $sqlQuery->setNumber($usucomision_resumen->usucomresumenId);
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
        $sql = 'DELETE FROM usucomision_resumen';
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
        $sql = 'SELECT * FROM usucomision_resumen WHERE fecha_crea = ?';
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
        $sql = 'SELECT * FROM usucomision_resumen WHERE usucrea_id = ?';
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
        $sql = 'SELECT * FROM usucomision_resumen WHERE usumodif_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);
    }











    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna externo_id sea igual al valor pasado como parámetro
     *
     * @param String $value externo_id requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByExternoId($value)
    {
        $sql = 'DELETE FROM usucomision_resumen WHERE externo_id = ?';
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
    public function deleteByEstado($value)
    {
        $sql = 'DELETE FROM usucomision_resumen WHERE valor = ?';
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
        $sql = 'DELETE FROM usucomision_resumen WHERE fecha_crea = ?';
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
        $sql = 'DELETE FROM usucomision_resumen WHERE usucrea_id = ?';
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
        $sql = 'DELETE FROM usucomision_resumen WHERE usumodif_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->executeUpdate($sqlQuery);
    }

















    /**
     * Crear y devolver un objeto del tipo UsucomisionResumen
     * con los valores de una consulta sql
     *
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Objeto $usucomision_resumen UsucomisionResumen
     *
     * @access protected
     *
     */
    protected function readRow($row)
    {
        $usucomision_resumen = new UsucomisionResumen();

        $usucomision_resumen->usucomresumenId = $row['usucomresumen_id'];
        $usucomision_resumen->usuarioId = $row['usuario_id'];
        $usucomision_resumen->usuariorefId = $row['usuarioref_id'];
        $usucomision_resumen->externoId = $row['externo_id'];
        $usucomision_resumen->tipo = $row['tipo'];
        $usucomision_resumen->valor = $row['valor'];
        $usucomision_resumen->comision = $row['comision'];
        $usucomision_resumen->usucreaId = $row['usucrea_id'];
        $usucomision_resumen->usumodifId = $row['usumodif_id'];
        $usucomision_resumen->estado = $row['estado'];
        $usucomision_resumen->usucambioId = $row['usucambio_id'];
        $usucomision_resumen->usupagoId = $row['usupago_id'];
        $usucomision_resumen->usurechazaId = $row['usurechaza_id'];
        $usucomision_resumen->mensajeUsuario = $row['mensaje_usuario'];
        $usucomision_resumen->observacion = $row['observacion'];
        $usucomision_resumen->valorPagado = $row['valor_pagado'];

        return $usucomision_resumen;
    }











    /**
     * Realizar una consulta en la tabla de UsucomisionResumen 'UsucomisionResumen'
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
    public function queryUsucomisionResumensCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$grouping="")
    {

        $Helpers=new Helpers();
        $where = " where 1=1 ";


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


        //  $sql = 'SELECT count(*) count FROM (SELECT '.$select.' FROM usucomision_resumen  INNER JOIN usuario ON (usucomision_resumen.usuario_id = usuario.usuario_id) INNER JOIN usuario usuarioref ON (usucomision_resumen.usuarioref_id = usuarioref.usuario_id)   INNER JOIN clasificador ON (clasificador.clasificador_id = usucomision_resumen.tipo)   ' . $where . ') countT';
        $sql = 'SELECT count(*)  FROM usucomision_resumen  INNER JOIN usuario ON (usucomision_resumen.usuario_id = usuario.usuario_id) INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = usuario.usuario_id) LEFT OUTER JOIN usuario usuarioref ON (usucomision_resumen.usuarioref_id = usuarioref.usuario_id)   INNER JOIN clasificador ON (clasificador.clasificador_id = usucomision_resumen.tipo)  LEFT OUTER JOIN concesionario ON (concesionario.usuhijo_id = usucomision_resumen.usuario_id)   ' . $where ;

        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT ' .$select .'  FROM usucomision_resumen  INNER JOIN usuario ON (usucomision_resumen.usuario_id = usuario.usuario_id) INNER JOIN usuario_perfil ON (usuario_perfil.usuario_id = usuario.usuario_id)  LEFT OUTER JOIN usuario usuarioref ON (usucomision_resumen.usuarioref_id = usuarioref.usuario_id)   INNER JOIN clasificador ON (clasificador.clasificador_id = usucomision_resumen.tipo)  LEFT OUTER JOIN concesionario ON (concesionario.usuhijo_id = usucomision_resumen.usuario_id)   ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;


        if($_ENV["debugFixed2"] == '1'){
            print_r($sql);
        }
        $sqlQuery = new SqlQuery($sql);

        $result = $Helpers->process_data($this->execute2($sqlQuery));

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }


    /**
     * Realizar una consulta en la tabla de UsucomisionResumen 'UsucomisionResumen'
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
    public function queryUsucomisionResumensGroupCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$group)
    {


        $Helpers = new Helpers();
        $where = " where 1=1 ";


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



        $sql = 'SELECT count(*) count FROM usucomision_resumen INNER JOIN producto_comision ON (producto_comision.prodcomision_id = usucomision_resumen.tipo) INNER JOIN producto_interno ON (producto_interno.productointerno_id = producto_comision.productointerno_id)  INNER JOIN usuario ON (usucomision_resumen.usuario_id = usuario.usuario_id)  ' . $where;
        $sql = 'SELECT count(*) count FROM usucomision_resumen  INNER JOIN clasificador ON (clasificador.clasificador_id = usucomision_resumen.tipo)  INNER JOIN usuario ON (usucomision_resumen.usuario_id = usuario.usuario_id)  ' . $where;


        $where = $where . " GROUP BY ". $group ." ";
        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT ' .$select .'  FROM usucomision_resumen INNER JOIN producto_comision ON (producto_comision.prodcomision_id = usucomision_resumen.tipo) INNER JOIN producto_interno ON (producto_interno.productointerno_id = producto_comision.productointerno_id)   INNER JOIN usuario ON (usucomision_resumen.usuario_id = usuario.usuario_id) ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;
        $sql = 'SELECT ' .$select .'  FROM usucomision_resumen  INNER JOIN clasificador ON (clasificador.clasificador_id = usucomision_resumen.tipo)   INNER JOIN usuario ON (usucomision_resumen.usuario_id = usuario.usuario_id) ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;


        if($_ENV["debugFixed2"] == '1'){
            print_r($sql);
        }
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
