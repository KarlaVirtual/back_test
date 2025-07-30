<?php namespace Backend\mysql;
use Backend\dao\UsuarioPerfilDAO;
use Backend\dto\Helpers;
use Backend\dto\UsuarioPerfil;
use Backend\sql\QueryExecutor;
use Backend\sql\SqlQuery;
use Backend\sql\Transaction;
use Exception;
/** 
* Clase 'UsuarioPerfilMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'UsuarioPerfil'
* 
* Ejemplo de uso: 
* $UsuarioPerfilMySqlDAO = new UsuarioPerfilMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class UsuarioPerfilMySqlDAO implements UsuarioPerfilDAO
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
        $sql = 'SELECT * FROM usuario_perfil WHERE usuario_id = ?';
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
        $sql = 'SELECT * FROM usuario_perfil';
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
        $sql = 'SELECT * FROM usuario_perfil ORDER BY ' . $orderColumn;
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }


    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $usuario_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function delete($usuario_id)
    {
        $sql = 'DELETE FROM usuario_perfil WHERE usuario_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($usuario_id);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Insertar un registro en la base de datos
     *
     * @param Objeto usuarioPerfil usuarioPerfil
     *
     * @return String $id resultado de la consulta
     *
     */
    public function insert($usuarioPerfil)
    {
        $sql = 'INSERT INTO usuario_perfil (usuario_id,perfil_id, mandante, pais, global, global_mandante,mandante_lista, consulta_agente,region,consentimiento_email,consentimiento_sms,consentimiento_telefono,consentimiento_push) VALUES (?,?, ?, ?, ?, ?,?,?,?,?,?,?,?)';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->set($usuarioPerfil->usuarioId);
        $sqlQuery->set($usuarioPerfil->perfilId);
        $sqlQuery->set($usuarioPerfil->mandante);
        $sqlQuery->set($usuarioPerfil->pais);
        $sqlQuery->set($usuarioPerfil->global);

        if($usuarioPerfil->globalMandante == ''){
            $usuarioPerfil->globalMandante='0';
        }

        if($usuarioPerfil->consultaAgente == ''){
            $usuarioPerfil->consultaAgente='0';
        }

        $sqlQuery->set($usuarioPerfil->globalMandante);


        $sqlQuery->set($usuarioPerfil->mandanteLista);
        if($usuarioPerfil->consultaAgente == ''){
            $usuarioPerfil->consultaAgente='0';
        }
        $sqlQuery->set($usuarioPerfil->consultaAgente);


        if($usuarioPerfil->region == ''){
            $usuarioPerfil->region='';
        }
        $sqlQuery->set($usuarioPerfil->region);

        if($usuarioPerfil->consentimientoEmail == ''){
            $usuarioPerfil->consentimientoEmail ='S';
        }
        $sqlQuery->set($usuarioPerfil->consentimientoEmail);

        if($usuarioPerfil->consentimientoSms == ''){
            $usuarioPerfil->consentimientoSms ='S';
        }
        $sqlQuery->set($usuarioPerfil->consentimientoSms);

        if($usuarioPerfil->consentimientoTelefono == ''){
            $usuarioPerfil->consentimientoTelefono ='S';
        }
        $sqlQuery->set($usuarioPerfil->consentimientoTelefono);

        if($usuarioPerfil->consentimientoPush == ''){
            $usuarioPerfil->consentimientoPush ='S';
        }
        $sqlQuery->set($usuarioPerfil->consentimientoPush);

        $id = $this->executeInsert($sqlQuery);
        $usuarioPerfil->usuarioId = $id;
        return $id;
    }


    /**
     * Ejecuta una consulta SQL personalizada.
     *
     * @param string $sql La consulta SQL a ejecutar.
     * @return mixed El resultado de la ejecución de la consulta.
     */
    public function querySQL($sql)
    {
        $sqlQuery = new SqlQuery($sql);
        return $this->execute2($sqlQuery);
    }


    /**
     * Editar un registro en la base de datos
     *
     * @param Objeto usuarioPerfil usuarioPerfil
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function update($usuarioPerfil)
    {
        $sql = 'UPDATE usuario_perfil SET perfil_id = ?, mandante = ?, pais = ?, global = ?, global_mandante = ?, mandante_lista = ?, consulta_agente = ?, region = ?,consentimiento_email = ?,consentimiento_sms = ?,consentimiento_telefono = ? ,consentimiento_push = ? WHERE usuario_id = ?';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->set($usuarioPerfil->perfilId);
        $sqlQuery->set($usuarioPerfil->mandante);
        $sqlQuery->set($usuarioPerfil->pais);
        $sqlQuery->set($usuarioPerfil->global);


        if($usuarioPerfil->globalMandante == ''){
            $usuarioPerfil->globalMandante='0';
        }
        $sqlQuery->set($usuarioPerfil->globalMandante);

        $sqlQuery->set($usuarioPerfil->mandanteLista);

        if($usuarioPerfil->consultaAgente == '' || $usuarioPerfil->consultaAgente==null){
            $usuarioPerfil->consultaAgente='0';
        }

        $sqlQuery->set($usuarioPerfil->consultaAgente);


        if($usuarioPerfil->region == '' || $usuarioPerfil->region==null){
            $usuarioPerfil->region='';
        }

        $sqlQuery->set($usuarioPerfil->region);


        if($usuarioPerfil->consentimientoEmail == ''){
            $usuarioPerfil->consentimientoEmail ='S';
        }
        $sqlQuery->set($usuarioPerfil->consentimientoEmail);

        if($usuarioPerfil->consentimientoSms == ''){
            $usuarioPerfil->consentimientoSms ='S';
        }
        $sqlQuery->set($usuarioPerfil->consentimientoSms);

        if($usuarioPerfil->consentimientoTelefono == ''){
            $usuarioPerfil->consentimientoTelefono ='S';
        }
        $sqlQuery->set($usuarioPerfil->consentimientoTelefono);

        if($usuarioPerfil->consentimientoPush == ''){
            $usuarioPerfil->consentimientoPush ='S';
        }
        $sqlQuery->set($usuarioPerfil->consentimientoPush);

        $sqlQuery->set($usuarioPerfil->usuarioId);
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
        $sql = 'DELETE FROM usuario_perfil';
        $sqlQuery = new SqlQuery($sql);
        return $this->executeUpdate($sqlQuery);
    }


    /**
     * Obtener todos los registros donde se encuentre que
     * la columna perfil_id sea igual al valor pasado como parámetro
     *
     * @param String $value perfil_id requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByPerfilId($value)
    {
        $sql = 'SELECT * FROM usuario_perfil WHERE perfil_id = ?';
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
        $sql = 'SELECT * FROM usuario_perfil WHERE mandante = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }


    /**
    * Realizar una consulta en la tabla de UsuarioPerfil 'UsuarioPerfil'
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
    public function queryUsuarioPerfilesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
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

        $sql = "SELECT count(*) count FROM usuario_perfil INNER JOIN usuario ON (usuario.usuario_id=usuario_perfil.usuario_id) LEFT OUTER JOIN concesionario ON (concesionario.usuhijo_id = usuario.usuario_id AND prodinterno_id=0 AND concesionario.estado='A') LEFT OUTER JOIN punto_venta ON (usuario.usuario_id=punto_venta.usuario_id) " . $where;

        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
        $sql = "SELECT " . $select . " FROM usuario_perfil INNER JOIN usuario ON (usuario.usuario_id=usuario_perfil.usuario_id) LEFT OUTER JOIN concesionario ON (concesionario.usuhijo_id = usuario.usuario_id AND prodinterno_id=0 AND concesionario.estado='A') LEFT OUTER JOIN punto_venta ON (usuario.usuario_id=punto_venta.usuario_id) LEFT OUTER JOIN ciudad ON (punto_venta.ciudad_id=ciudad.ciudad_id)  LEFT OUTER JOIN departamento ON (departamento.depto_id=ciudad.depto_id) LEFT OUTER JOIN pais ON (departamento.pais_id=pais.pais_id) " . $where . " " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        if($_ENV["debugFixed2"] == '1'){
           print_r($sql);
        }


        $sqlQuery = new SqlQuery($sql);

        $result = $Helpers->process_data($this->execute2($sqlQuery));

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }



    /**
     * Consulta perfiles de usuario personalizados con perfil.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Índice de ordenación.
     * @param string $sord Orden de clasificación (ASC o DESC).
     * @param int $start Inicio de la paginación.
     * @param int $limit Límite de registros a devolver.
     * @param string $filters Filtros en formato JSON.
     * @param bool $searchOn Indica si la búsqueda está activada.
     * @param string $perfil Perfil del usuario.
     * @return string JSON con el conteo y los datos resultantes de la consulta.
     */
    public function queryUsuarioPerfilesCustomWithPerfil($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$perfil)
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
                        $fieldOperation = "  LIKE '" . $fieldData . "%'";
                        break;
                    case "bn":
                        $fieldOperation = " NOT LIKE '" . $fieldData . "%'";
                        break;
                    case "ew":
                        $fieldOperation = "  LIKE '%" . $fieldData . "'";
                        break;
                    case "en":
                        $fieldOperation = " NOT LIKE '%" . $fieldData . "'";
                        break;
                    case "cn":
                        $fieldOperation = "  LIKE '%" . $fieldData . "%'";
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

        if($perfil == "CONCESIONARIO"){
            $where .= ' and (usuario_perfil.perfil_id="CONCESIONARIO2" OR (usuario_perfil.perfil_id="PUNTOVENTA" AND concesionario.usupadre2_id=0)) ';
        }

        if($perfil == "CONCESIONARIO2"){
            //$where .= ' and (usuario_perfil.perfil_id="PUNTOVENTA") ';
            $where .= ' and (usuario_perfil.perfil_id="CONCESIONARIO3" OR (usuario_perfil.perfil_id="PUNTOVENTA" AND concesionario.usupadre3_id=0)) ';
        }
        if($perfil == "CONCESIONARIO3"){
            $where .= ' and (usuario_perfil.perfil_id="PUNTOVENTA") ';
        }

        $sql = "SELECT count(*) count FROM usuario_perfil INNER JOIN usuario ON (usuario.usuario_id=usuario_perfil.usuario_id) LEFT OUTER JOIN concesionario ON (concesionario.usuhijo_id = usuario.usuario_id AND prodinterno_id=0 AND concesionario.estado='A') LEFT OUTER JOIN punto_venta ON (usuario.usuario_id=punto_venta.usuario_id) " . $where;

        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
        $sql = "SELECT " . $select . " FROM usuario_perfil INNER JOIN usuario ON (usuario.usuario_id=usuario_perfil.usuario_id) LEFT OUTER JOIN concesionario ON (concesionario.usuhijo_id = usuario.usuario_id AND prodinterno_id=0 AND concesionario.estado='A') LEFT OUTER JOIN punto_venta ON (usuario.usuario_id=punto_venta.usuario_id) LEFT OUTER JOIN ciudad ON (punto_venta.ciudad_id=ciudad.ciudad_id)  LEFT OUTER JOIN departamento ON (departamento.depto_id=ciudad.depto_id) LEFT OUTER JOIN pais ON (departamento.pais_id=pais.pais_id) " . $where . " " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;



        $sqlQuery = new SqlQuery($sql);

        $result = $Helpers->process_data($this->execute2($sqlQuery));

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }

    /**
    * Realizar una consulta en la tabla de UsuarioPerfil 'UsuarioPerfil'
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
    public function queryChilds($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
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

        $sql = "SELECT count(*) count FROM usuario_perfil INNER JOIN usuario ON (usuario.usuario_id=usuario_perfil.usuario_id) INNER JOIN concesionario ON (concesionario.usuhijo_id = usuario.usuario_id) LEFT OUTER JOIN punto_venta ON (usuario.usuario_id=punto_venta.usuario_id) " . $where;

        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
        $sql = "SELECT " . $select . " FROM usuario_perfil INNER JOIN usuario ON (usuario.usuario_id=usuario_perfil.usuario_id) INNER JOIN concesionario ON (concesionario.usuhijo_id = usuario.usuario_id)  LEFT OUTER JOIN punto_venta ON (usuario.usuario_id=punto_venta.usuario_id) LEFT OUTER JOIN ciudad ON (punto_venta.ciudad_id=ciudad.ciudad_id)  LEFT OUTER JOIN departamento ON (departamento.depto_id=ciudad.depto_id) LEFT OUTER JOIN pais ON (departamento.pais_id=pais.pais_id) " . $where . " " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;


        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }










    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna perfil_id sea igual al valor pasado como parámetro
     *
     * @param String $value perfil_id requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByPerfilId($value)
    {
        $sql = 'DELETE FROM usuario_perfil WHERE perfil_id = ?';
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
        $sql = 'DELETE FROM usuario_perfil WHERE mandante = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }








    /**
     * Crear y devolver un objeto del tipo UsuarioPerfil
     * con los valores de una consulta sql
     * 
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Objeto $usuarioPerfil UsuarioPerfil
     *
     * @access protected
     *
     */
    protected function readRow($row)
    {
        $usuarioPerfil = new UsuarioPerfil();

        $usuarioPerfil->usuarioId = $row['usuario_id'];
        $usuarioPerfil->perfilId = $row['perfil_id'];
        $usuarioPerfil->mandante = $row['mandante'];
        $usuarioPerfil->pais = $row['pais'];
        $usuarioPerfil->global = $row['global'];
        $usuarioPerfil->globalMandante = $row['global_mandante'];
        $usuarioPerfil->mandanteLista = $row['mandante_lista'];
        $usuarioPerfil->consultaAgente = $row['consulta_agente'];
        $usuarioPerfil->region = $row['region'];
        $usuarioPerfil->consentimientoEmail = $row['consentimiento_email'];
        $usuarioPerfil->consentimientoSms = $row['consentimiento_sms'];
        $usuarioPerfil->consentimientoTelefono = $row['consentimiento_telefono'];
        $usuarioPerfil->consentimientoPush = $row['consentimiento_push'];


        return $usuarioPerfil;
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
