<?php namespace Backend\mysql;
use Backend\dao\UsuarioTorneoDAO;
use Backend\dto\Helpers;
use Backend\dto\UsuarioTorneo;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Exception;
/** 
* Clase 'UsuarioTorneoMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'UsuarioTorneo'
* 
* Ejemplo de uso: 
* $UsuarioTorneoMySqlDAO = new UsuarioTorneoMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class UsuarioTorneoMySqlDAO implements UsuarioTorneoDAO
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
        $sql = 'SELECT * FROM usuario_torneo WHERE usutorneo_id = ?';
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
        $sql = 'SELECT * FROM usuario_torneo';
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
        $sql = 'SELECT * FROM usuario_torneo ORDER BY ' . $orderColumn;
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }

    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $usutorneo_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function delete($usutorneo_id)
    {
        $sql = 'DELETE FROM usuario_torneo WHERE usutorneo_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($usutorneo_id);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Insertar un registro en la base de datos
     *
     * @param Objeto usuario_torneo usuario_torneo
     *
     * @return String $id resultado de la consulta
     *
     */
    public function insert($usuario_torneo)
    {
        $sql = 'INSERT INTO usuario_torneo (usuario_id, torneo_id, valor,posicion,valor_base, estado, usucrea_id, usumodif_id,mandante,error_id,id_externo,version,apostado,codigo,externo_id,valor_premio) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($usuario_torneo->usuarioId);
        $sqlQuery->set($usuario_torneo->torneoId);
        $sqlQuery->set($usuario_torneo->valor);
        $sqlQuery->set($usuario_torneo->posicion);
        $sqlQuery->set($usuario_torneo->valorBase);
        $sqlQuery->set($usuario_torneo->estado);
        $sqlQuery->setNumber($usuario_torneo->usucreaId);
        $sqlQuery->setNumber($usuario_torneo->usumodifId);
        $sqlQuery->setNumber($usuario_torneo->mandante);
        $sqlQuery->set($usuario_torneo->errorId);
        $sqlQuery->set($usuario_torneo->idExterno);
        $sqlQuery->set($usuario_torneo->version);
        $sqlQuery->set($usuario_torneo->apostado);
        $sqlQuery->set($usuario_torneo->codigo);
        $sqlQuery->set($usuario_torneo->externoId);

        if($usuario_torneo->valorPremio == ""){
            $usuario_torneo->valorPremio=0;
        }
        $sqlQuery->set($usuario_torneo->valorPremio);

        $id = $this->executeInsert($sqlQuery);
        $usuario_torneo->usutorneoId = $id;
        return $id;
    }

    /**
     * Editar un registro en la base de datos
     *
     * @param Objeto usuario_torneo usuario_torneo
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function update($usuario_torneo)
    {
        $sql = 'UPDATE usuario_torneo SET usuario_id = ?, torneo_id = ?, valor = ?, posicion = ?, valor_base = ?, estado = ?,  usucrea_id = ?,  usumodif_id = ?, mandante=?, error_id = ?, id_externo = ?, version = ?, apostado = ?, codigo = ?, externo_id = ?, valor_premio = ? WHERE usutorneo_id = ?';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($usuario_torneo->usuarioId);
        $sqlQuery->set($usuario_torneo->torneoId);
        $sqlQuery->set($usuario_torneo->valor);
        $sqlQuery->set($usuario_torneo->posicion);
        $sqlQuery->set($usuario_torneo->valorBase);
        $sqlQuery->set($usuario_torneo->estado);
        $sqlQuery->setNumber($usuario_torneo->usucreaId);
        $sqlQuery->setNumber($usuario_torneo->usumodifId);
        $sqlQuery->setNumber($usuario_torneo->mandante);
        if($usuario_torneo->errorId ==''){
            $usuario_torneo->errorId='0';
        }
        $sqlQuery->set($usuario_torneo->errorId);
        if($usuario_torneo->idExterno ==''){
            $usuario_torneo->idExterno='0';
        }
        $sqlQuery->set($usuario_torneo->idExterno);
        if($usuario_torneo->version ==''){
            $usuario_torneo->version='0';
        }
        $sqlQuery->set($usuario_torneo->version);
        $sqlQuery->set($usuario_torneo->apostado);
        $sqlQuery->set($usuario_torneo->codigo);
        $sqlQuery->set($usuario_torneo->externoId);
        if($usuario_torneo->valorPremio == ""){
            $usuario_torneo->valorPremio=0;
        }
        $sqlQuery->setSIN($usuario_torneo->valorPremio);

        $sqlQuery->setNumber($usuario_torneo->usutorneoId);
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
        $sql = 'DELETE FROM usuario_torneo';
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
        $sql = 'SELECT * FROM usuario_torneo WHERE estado = ?';
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
        $sql = 'SELECT * FROM usuario_torneo WHERE fecha_crea = ?';
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
        $sql = 'SELECT * FROM usuario_torneo WHERE usucrea_id = ?';
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
        $sql = 'SELECT * FROM usuario_torneo WHERE usumodif_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * las columnas usuarioId y torneoId son iguales a los valores 
     * pasados como parámetros
     *
     * @param String $usuarioId usuarioId requerido
     * @param String $torneoId torneoId requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByUsuarioIdAndBannerId($usuarioId,$torneoId)
    {
        $sql = 'SELECT * FROM usuario_torneo WHERE usuario_id = ? AND torneo_id =?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($usuarioId);
        $sqlQuery->setNumber($torneoId);
        return $this->getList($sqlQuery);
    }













    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna torneo_id sea igual al valor pasado como parámetro
     *
     * @param String $value torneo_id requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByBannerId($value)
    {
        $sql = 'DELETE FROM usuario_torneo WHERE torneo_id = ?';
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
        $sql = 'DELETE FROM usuario_torneo WHERE estado = ?';
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
        $sql = 'DELETE FROM usuario_torneo WHERE fecha_crea = ?';
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
        $sql = 'DELETE FROM usuario_torneo WHERE usucrea_id = ?';
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
        $sql = 'DELETE FROM usuario_torneo WHERE usumodif_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->executeUpdate($sqlQuery);
    }

















    /**
     * Crear y devolver un objeto del tipo UsuarioTorneo
     * con los valores de una consulta sql
     * 
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Objeto $usuario_torneo UsuarioTorneo
     *
     * @access protected
     *
     */
    protected function readRow($row)
    {
        $usuario_torneo = new UsuarioTorneo();

        $usuario_torneo->usutorneoId = $row['usutorneo_id'];
        $usuario_torneo->usuarioId = $row['usuario_id'];
        $usuario_torneo->torneoId = $row['torneo_id'];
        $usuario_torneo->valor = $row['valor'];
        $usuario_torneo->posicion = $row['posicion'];
        $usuario_torneo->valorBase = $row['valor_base'];
        $usuario_torneo->estado = $row['estado'];
        $usuario_torneo->fechaCrea = $row['fecha_crea'];
        $usuario_torneo->usucreaId = $row['usucrea_id'];
        $usuario_torneo->fechaModif = $row['fecha_modif'];
        $usuario_torneo->usumodifId = $row['usumodif_id'];
        $usuario_torneo->mandante = $row['mandante'];
        $usuario_torneo->errorId = $row['error_id'];
        $usuario_torneo->idExterno = $row['id_externo'];
        $usuario_torneo->version = $row['version'];
        $usuario_torneo->apostado = $row['apostado'];
        $usuario_torneo->codigo = $row['codigo'];
        $usuario_torneo->externoId = $row['externo_id'];
        $usuario_torneo->valorPremio = $row['valor_premio'];

        return $usuario_torneo;
    }














    /**
    * Realizar una consulta en la tabla de UsuarioTorneo 'UsuarioTorneo'
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
    public function queryUsuarioTorneos($sidx, $sord, $start, $limit, $filters, $searchOn)
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


        $sql = 'SELECT count(*) count FROM proveedor LEFT OUTER JOIN usuario_torneo ON (usuario_torneo.proveedor_id = proveedor.proveedor_id)' . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT proveedor.*,usuario_torneo.* FROM proveedor LEFT OUTER JOIN usuario_torneo ON (usuario_torneo.proveedor_id = proveedor.proveedor_id)' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }

    /**
    * Realizar una consulta en la tabla de UsuarioTorneo 'UsuarioTorneo'
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
    public function queryUsuarioTorneosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$withPosition=false)
    {
        $Helpers = new Helpers();
        $torneoEspecificoParaPosicion=0;

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
                if($withPosition && $fieldName=="torneo_interno.torneo_id"){
                    $torneoEspecificoParaPosicion=$fieldData;
                }
                

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

        if($withPosition){

            $sqlWherePos="";
            if($withPosition && $torneoEspecificoParaPosicion !=0){
                $sqlWherePos = " WHERE t.torneo_id='".$torneoEspecificoParaPosicion."' ";
            }
            $sql = 'SELECT count(*) count FROM usuario_torneo INNER JOIN usuario_mandante ON (usuario_mandante.usumandante_id = usuario_torneo.usuario_id) INNER JOIN torneo_interno ON (torneo_interno.torneo_id = usuario_torneo.torneo_id) INNER JOIN (SELECT t.usuario_id,
                t.torneo_id,
                u.usuario_mandante,
                u.nombres,
               t.valor,
               @rownum := @rownum + 1 AS position
          FROM  usuario_torneo t
          INNER JOIN usuario_mandante u ON (u.usumandante_id=t.usuario_id)
          JOIN (SELECT @rownum := 0) r '.$sqlWherePos.'
      ORDER BY t.valor DESC) position ON (position.usuario_id = usuario_torneo.usuario_id  AND position.torneo_id=torneo_interno.torneo_id)
 ' . $where;
        }else{

            $sql = 'SELECT count(*) count FROM usuario_torneo INNER JOIN usuario_mandante ON (usuario_mandante.usumandante_id = usuario_torneo.usuario_id) INNER JOIN torneo_interno ON (torneo_interno.torneo_id = usuario_torneo.torneo_id) ' . $where;
        }




        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);

        if($withPosition) {

            $sqlWherePos="";
            if($withPosition && $torneoEspecificoParaPosicion !=0){
                $sqlWherePos = " WHERE t.torneo_id='".$torneoEspecificoParaPosicion."' ";
            }

            $sql = 'SELECT ' . $select . '  FROM usuario_torneo  INNER JOIN usuario_mandante ON (usuario_mandante.usumandante_id = usuario_torneo.usuario_id) INNER JOIN torneo_interno ON (torneo_interno.torneo_id = usuario_torneo.torneo_id) INNER JOIN (SELECT t.usuario_id,
                t.torneo_id,
                u.usuario_mandante,
                u.nombres,
               t.valor,
               @rownum := @rownum + 1 AS position
          FROM  usuario_torneo t
          INNER JOIN usuario_mandante u ON (u.usumandante_id=t.usuario_id)
          JOIN (SELECT @rownum := 0) r '.$sqlWherePos.'
      ORDER BY t.valor DESC) position ON (position.usuario_id = usuario_torneo.usuario_id AND position.torneo_id=torneo_interno.torneo_id)
 ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;
        }else{
            $sql = 'SELECT ' .$select .'  FROM usuario_torneo  INNER JOIN usuario_mandante ON (usuario_mandante.usumandante_id = usuario_torneo.usuario_id) INNER JOIN torneo_interno ON (torneo_interno.torneo_id = usuario_torneo.torneo_id) ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        }

        if($_ENV["debugFixed2"] == '1'){
            print_r($sql);
        }

        $sqlQuery = new SqlQuery($sql);

        $result = $Helpers->process_data($this->execute2($sqlQuery));

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }

    public function queryUsuarioTorneosCustomWithoutPosition($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
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


        $sql = 'SELECT count(*) count FROM usuario_torneo INNER JOIN usuario_mandante ON (usuario_mandante.usumandante_id = usuario_torneo.usuario_id) INNER JOIN torneo_interno ON (torneo_interno.torneo_id = usuario_torneo.torneo_id)' . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT ' .$select .'  FROM usuario_torneo  INNER JOIN usuario_mandante ON (usuario_mandante.usumandante_id = usuario_torneo.usuario_id) INNER JOIN torneo_interno ON (torneo_interno.torneo_id = usuario_torneo.torneo_id)' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;


        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }

    /**
    * Realizar una consulta en la tabla de UsuarioTorneo 'UsuarioTorneo'
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
    public function getAllUsuarioTorneos($value, $category, $provider, $offset, $limit, $search, $partnerId)
    {
        $where = " 1=1 ";
        if ($category != "") {
            $where = $where . " AND categoria_usuario_torneo.categoria_id= ? ";
        }

        if ($provider != "") {
            $where = $where . " AND proveedor.abreviado = ? ";
        }

        if ($search != "") {
            $where = $where . " AND usuario_torneo.descripcion  LIKE '%" . $search . "%' ";
        }

        if ($offset == "" || $limit == "") {
            $limit = 15;
            $offset = 0;
        }


        $sql = 'SELECT proveedor.*,usuario_torneo.*,usuario_torneo_mandante.*, categoria_usuario_torneo.*,categoria.*,usuario_torneo_detalle.p_value background FROM proveedor
        LEFT OUTER JOIN usuario_torneo ON (usuario_torneo.proveedor_id = proveedor.proveedor_id)
INNER JOIN categoria_usuario_torneo ON (categoria_usuario_torneo.usutorneo_id = usuario_torneo.usutorneo_id)
INNER JOIN usuario_torneo_mandante ON (usuario_torneo.usutorneo_id = usuario_torneo_mandante.usutorneo_id AND usuario_torneo_mandante.mandante = ' . $partnerId . ' ) LEFT OUTER JOIN categoria ON (categoria.categoria_id =categoria_usuario_torneo.categoria_id )
LEFT OUTER JOIN usuario_torneo_detalle ON (usuario_torneo_detalle.usutorneo_id= usuario_torneo.usutorneo_id AND usuario_torneo_detalle.p_key = "IMAGE_BACKGROUND")
 WHERE ' . $where . ' AND proveedor.Tipo = ? AND proveedor.estado="A" AND usuario_torneo.estado="A" AND usuario_torneo.mostrar="S" LIMIT ' . ($limit - $offset) . ' OFFSET ' . $offset;

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
