<?php namespace Backend\mysql;
use Backend\dao\UsuarioRuletaDAO;
use Backend\dto\Helpers;
use Backend\dto\UsuarioRuleta;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Exception;
/** 
* Clase 'UsuarioRuletaMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'UsuarioRuleta'
* 
* Ejemplo de uso: 
* $UsuarioRuletaMySqlDAO = new UsuarioRuletaMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class UsuarioRuletaMySqlDAO
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
        $sql = 'SELECT * FROM usuario_ruleta WHERE usuruleta_id = ?';
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
        $sql = 'SELECT * FROM usuario_ruleta';
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
        $sql = 'SELECT * FROM usuario_ruleta ORDER BY ' . $orderColumn;
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }

    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $usuruleta_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function delete($usuruleta_id)
    {
        $sql = 'DELETE FROM usuario_ruleta WHERE usuruleta_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($usuruleta_id);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Insertar un registro en la base de datos
     *
     * @param Objeto usuario_ruleta usuario_ruleta
     *
     * @return String $id resultado de la consulta
     *
     */
    public function insert($usuario_ruleta)
    {
        $fechaExpiracion1 = !empty($usuario_ruleta->fechaExpiracion) ? ',fecha_expiracion' : '';
        $fechaExpiracion2 = !empty($usuario_ruleta->fechaExpiracion) ? ', ?' : '';

        $sql = "INSERT INTO usuario_ruleta (usuario_id, ruleta_id, valor,posicion,valor_base, estado, usucrea_id, usumodif_id,mandante,error_id,id_externo,version,apostado,codigo,externo_id,valor_premio,premio $fechaExpiracion1) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? $fechaExpiracion2)";
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($usuario_ruleta->usuarioId);
        $sqlQuery->set($usuario_ruleta->ruletaId);
        $sqlQuery->set($usuario_ruleta->valor);
        $sqlQuery->set($usuario_ruleta->posicion);
        $sqlQuery->set($usuario_ruleta->valorBase);
        $sqlQuery->set($usuario_ruleta->estado);
        $sqlQuery->setNumber($usuario_ruleta->usucreaId);
        $sqlQuery->setNumber($usuario_ruleta->usumodifId);
        $sqlQuery->setNumber($usuario_ruleta->mandante);
        $sqlQuery->set($usuario_ruleta->errorId);
        $sqlQuery->set($usuario_ruleta->idExterno);
        $sqlQuery->set($usuario_ruleta->version);
        $sqlQuery->set($usuario_ruleta->apostado);
        $sqlQuery->set($usuario_ruleta->codigo);
        $sqlQuery->set($usuario_ruleta->externoId);

        if($usuario_ruleta->valorPremio == ""){
            $usuario_ruleta->valorPremio=0;
        }
        $sqlQuery->set($usuario_ruleta->valorPremio);
        $sqlQuery->set($usuario_ruleta->premio);
        if (!empty($usuario_ruleta->fechaExpiracion)) {
            $sqlQuery->set($usuario_ruleta->fechaExpiracion);
        }

        $id = $this->executeInsert($sqlQuery);
        $usuario_ruleta->usuruletaId = $id;
        return $id;
    }

    /**
     * Editar un registro en la base de datos
     *
     * @param Objeto usuario_ruleta usuario_ruleta
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function update($usuario_ruleta, $where = "")
    {
        $fechaExpiracion = !empty($usuario_ruleta->fechaExpiracion) ? ', fecha_expiracion = ? ' : '';

        $sql = "UPDATE usuario_ruleta SET usuario_id = ?, ruleta_id = ?, valor = ?, posicion = ?, valor_base = ?, estado = ?,  usucrea_id = ?,  usumodif_id = ?, mandante=?, error_id = ?, id_externo = ?, version = ?, apostado = ?, codigo = ?, externo_id = ?, valor_premio = ?, premio = ? $fechaExpiracion WHERE usuruleta_id = ? $where ";
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($usuario_ruleta->usuarioId);
        $sqlQuery->set($usuario_ruleta->ruletaId);
        $sqlQuery->set($usuario_ruleta->valor);
        $sqlQuery->set($usuario_ruleta->posicion);
        $sqlQuery->set($usuario_ruleta->valorBase);
        $sqlQuery->set($usuario_ruleta->estado);
        $sqlQuery->setNumber($usuario_ruleta->usucreaId);
        $sqlQuery->setNumber($usuario_ruleta->usumodifId);
        $sqlQuery->setNumber($usuario_ruleta->mandante);
        $sqlQuery->set($usuario_ruleta->errorId);
        $sqlQuery->set($usuario_ruleta->idExterno);
        $sqlQuery->set($usuario_ruleta->version);
        $sqlQuery->set($usuario_ruleta->apostado);
        $sqlQuery->set($usuario_ruleta->codigo);
        $sqlQuery->set($usuario_ruleta->externoId);
        if($usuario_ruleta->valorPremio == ""){
            $usuario_ruleta->valorPremio=0;
        }
        $sqlQuery->setSIN($usuario_ruleta->valorPremio);
        $sqlQuery->set($usuario_ruleta->premio);
        if (!empty($usuario_ruleta->fechaExpiracion)) {
            $sqlQuery->set($usuario_ruleta->fechaExpiracion);
        }
        $sqlQuery->setNumber($usuario_ruleta->usuruletaId);


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
        $sql = 'DELETE FROM usuario_ruleta';
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
        $sql = 'SELECT * FROM usuario_ruleta WHERE estado = ?';
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
        $sql = 'SELECT * FROM usuario_ruleta WHERE fecha_crea = ?';
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
        $sql = 'SELECT * FROM usuario_ruleta WHERE usucrea_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Consulta registros en la tabla usuario_ruleta por el ID de usuario.
     *
     * @param int $value El ID del usuario a buscar.
     * @return array Lista de registros que coinciden con el ID de usuario proporcionado.
     */
    public function queryByUsuarioId($value)
    {
        $sql = 'SELECT * FROM usuario_ruleta WHERE usuario_id = ? and ruletaId = ?';
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
        $sql = 'SELECT * FROM usuario_ruleta WHERE usumodif_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * las columnas usuarioId y ruletaId son iguales a los valores 
     * pasados como parámetros
     *
     * @param String $usuarioId usuarioId requerido
     * @param String $ruletaId ruletaId requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByUsuarioIdAndRuletaId($usuruletaId,$usuarioId)
    {
        $sql = 'SELECT * FROM usuario_ruleta WHERE usuruleta_id  = ? AND usuario_id =?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($usuruletaId);
        $sqlQuery->setNumber($usuarioId);
        return $this->getList($sqlQuery);
    }













    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna ruleta_id sea igual al valor pasado como parámetro
     *
     * @param String $value ruleta_id requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByBannerId($value)
    {
        $sql = 'DELETE FROM usuario_ruleta WHERE ruleta_id = ?';
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
        $sql = 'DELETE FROM usuario_ruleta WHERE estado = ?';
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
        $sql = 'DELETE FROM usuario_ruleta WHERE fecha_crea = ?';
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
        $sql = 'DELETE FROM usuario_ruleta WHERE usucrea_id = ?';
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
        $sql = 'DELETE FROM usuario_ruleta WHERE usumodif_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->executeUpdate($sqlQuery);
    }

















    /**
     * Crear y devolver un objeto del tipo UsuarioRuleta
     * con los valores de una consulta sql
     * 
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Objeto $usuario_ruleta UsuarioRuleta
     *
     * @access protected
     *
     */
    protected function readRow($row)
    {
        $usuario_ruleta = new UsuarioRuleta();

        $usuario_ruleta->usuruletaId = $row['usuruleta_id'];
        $usuario_ruleta->usuarioId = $row['usuario_id'];
        $usuario_ruleta->ruletaId = $row['ruleta_id'];
        $usuario_ruleta->valor = $row['valor'];
        $usuario_ruleta->posicion = $row['posicion'];
        $usuario_ruleta->valorBase = $row['valor_base'];
        $usuario_ruleta->estado = $row['estado'];
        $usuario_ruleta->fechaCrea = $row['fecha_crea'];
        $usuario_ruleta->usucreaId = $row['usucrea_id'];
        $usuario_ruleta->fechaModif = $row['fecha_modif'];
        $usuario_ruleta->usumodifId = $row['usumodif_id'];
        $usuario_ruleta->mandante = $row['mandante'];
        $usuario_ruleta->errorId = $row['error_id'];
        $usuario_ruleta->idExterno = $row['id_externo'];
        $usuario_ruleta->version = $row['version'];
        $usuario_ruleta->apostado = $row['apostado'];
        $usuario_ruleta->codigo = $row['codigo'];
        $usuario_ruleta->externoId = $row['externo_id'];
        $usuario_ruleta->valorPremio = $row['valor_premio'];
        $usuario_ruleta->premio = $row['premio'];
        $usuario_ruleta->fechaExpiracion = $row['fecha_expiracion'];

        return $usuario_ruleta;
    }














    /**
    * Realizar una consulta en la tabla de UsuarioRuleta 'UsuarioRuleta'
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
    public function queryUsuarioRuletas($sidx, $sord, $start, $limit, $filters, $searchOn)
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


        $sql = 'SELECT count(*) count FROM proveedor LEFT OUTER JOIN usuario_ruleta ON (usuario_ruleta.proveedor_id = proveedor.proveedor_id)' . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT proveedor.*,usuario_ruleta.* FROM proveedor LEFT OUTER JOIN usuario_ruleta ON (usuario_ruleta.proveedor_id = proveedor.proveedor_id)' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }

    /**
    * Realizar una consulta en la tabla de UsuarioRuleta 'UsuarioRuleta'
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
    public function queryUsuarioRuletaCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping="")
    {


        $where = " where 1=1 ";


        if($searchOn) {
            // Construye el where
            $filters = json_decode($filters);
            $whereArray = array();
            $rules = $filters->rules;
            $groupOperation = $filters->groupOp;
            $cont = 0;

            foreach($rules as $rule)
            {
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
                        $fieldOperation = " = '".$fieldData."'";
                        break;
                    case "ne":
                        $fieldOperation = " != '".$fieldData."'";
                        break;
                    case "lt":
                        $fieldOperation = " < '".$fieldData."'";
                        break;
                    case "gt":
                        $fieldOperation = " > '".$fieldData."'";
                        break;
                    case "le":
                        $fieldOperation = " <= '".$fieldData."'";
                        break;
                    case "ge":
                        $fieldOperation = " >= '".$fieldData."'";
                        break;
                    case "nu":
                        $fieldOperation = " = ''";
                        break;
                    case "nn":
                        $fieldOperation = " != ''";
                        break;
                    case "in":
                        $fieldOperation = " IN (".$fieldData.")";
                        break;
                    case "ni":
                        $fieldOperation = " NOT IN (".$fieldData.")";
                        break;
                    case "bw":
                        $fieldOperation = " LIKE '".$fieldData."%'";
                        break;
                    case "bn":
                        $fieldOperation = " NOT LIKE '".$fieldData."%'";
                        break;
                    case "ew":
                        $fieldOperation = " LIKE '%".$fieldData."'";
                        break;
                    case "en":
                        $fieldOperation = " NOT LIKE '%".$fieldData."'";
                        break;
                    case "cn":
                        $fieldOperation = " LIKE '%".$fieldData."%'";
                        break;
                    case "nc":
                        $fieldOperation = " NOT LIKE '%".$fieldData."%'";
                        break;
                    case "isnull":
                        $fieldOperation = " IS NULL ";
                        break;
                    case "nisnull":
                        $fieldOperation = " IS NOT NULL ";
                        break;
                    default:
                        $fieldOperation = "";
                        break;
                }
                if($fieldOperation != "") $whereArray[] = $fieldName.$fieldOperation;
                if (oldCount($whereArray)>0)
                {
                    $where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
                }
                else
                {
                    $where = "";
                }
            }

        }

        $sql = "SELECT count(*) count FROM usuario_ruleta INNER JOIN ruleta_interno ON (ruleta_interno.ruleta_id = usuario_ruleta.ruleta_id) INNER JOIN mandante ON (usuario_ruleta.mandante = mandante.mandante) " . $where;

        $sqlQuery = new SqlQuery($sql);


        if($grouping != ""){
            $where = $where . " GROUP BY " . $grouping;
        }




        $count = $this->execute2($sqlQuery);
        $sql = "SELECT ".$select." FROM usuario_ruleta INNER JOIN ruleta_interno ON (ruleta_interno.ruleta_id = usuario_ruleta.ruleta_id) INNER JOIN mandante ON (usuario_ruleta.mandante = mandante.mandante)  " . $where ." " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : '.json_encode($count). ', "data" : '.json_encode($result).'}';

        return  $json;
    }

    /**
     * Realiza una consulta personalizada en la tabla usuario_ruleta con filtros y paginación.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Campo por el cual se ordenarán los resultados.
     * @param string $sord Orden de los resultados (ASC o DESC).
     * @param int $start Índice de inicio para la paginación.
     * @param int $limit Número de registros a devolver.
     * @param string $filters Filtros en formato JSON para construir la cláusula WHERE.
     * @param bool $searchOn Indica si se deben aplicar los filtros.
     * @param string $grouping Campo por el cual se agruparán los resultados (opcional).
     * @return string JSON con el conteo de registros y los datos resultantes de la consulta.
     */
    public function queryUsuarioRuletaCustom2($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping="")
    {


        $where = " where 1=1 ";


        if($searchOn) {
            // Construye el where
            $filters = json_decode($filters);
            $whereArray = array();
            $rules = $filters->rules;
            $groupOperation = $filters->groupOp;
            $cont = 0;

            foreach($rules as $rule)
            {
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
                        $fieldOperation = " = '".$fieldData."'";
                        break;
                    case "ne":
                        $fieldOperation = " != '".$fieldData."'";
                        break;
                    case "lt":
                        $fieldOperation = " < '".$fieldData."'";
                        break;
                    case "gt":
                        $fieldOperation = " > '".$fieldData."'";
                        break;
                    case "le":
                        $fieldOperation = " <= '".$fieldData."'";
                        break;
                    case "ge":
                        $fieldOperation = " >= '".$fieldData."'";
                        break;
                    case "nu":
                        $fieldOperation = " = ''";
                        break;
                    case "nn":
                        $fieldOperation = " != ''";
                        break;
                    case "in":
                        $fieldOperation = " IN (".$fieldData.")";
                        break;
                    case "ni":
                        $fieldOperation = " NOT IN '".$fieldData."'";
                        break;
                    case "bw":
                        $fieldOperation = " LIKE '".$fieldData."%'";
                        break;
                    case "bn":
                        $fieldOperation = " NOT LIKE '".$fieldData."%'";
                        break;
                    case "ew":
                        $fieldOperation = " LIKE '%".$fieldData."'";
                        break;
                    case "en":
                        $fieldOperation = " NOT LIKE '%".$fieldData."'";
                        break;
                    case "cn":
                        $fieldOperation = " LIKE '%".$fieldData."%'";
                        break;
                    case "nc":
                        $fieldOperation = " NOT LIKE '%".$fieldData."%'";
                        break;
                    case "isnull":
                        $fieldOperation = " IS NULL ";
                        break;
                    case "nisnull":
                        $fieldOperation = " IS NOT NULL ";
                        break;
                    default:
                        $fieldOperation = "";
                        break;
                }
                if($fieldOperation != "") $whereArray[] = $fieldName.$fieldOperation;
                if (oldCount($whereArray)>0)
                {
                    $where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
                }
                else
                {
                    $where = "";
                }
            }

        }

        $sql = "SELECT count(*) count FROM usuario_ruleta INNER JOIN ruleta_interno ON (ruleta_interno.ruleta_id = usuario_ruleta.ruleta_id) INNER JOIN mandante ON (usuario_ruleta.mandante = mandante.mandante) " . $where;

        $sqlQuery = new SqlQuery($sql);


        if($grouping != ""){
            $where = $where . " GROUP BY " . $grouping;
        }




        $count = $this->execute2($sqlQuery);
        $sql = "SELECT ".$select." FROM usuario_ruleta INNER JOIN ruleta_interno ON (ruleta_interno.ruleta_id = usuario_ruleta.ruleta_id) INNER JOIN mandante ON (usuario_ruleta.mandante = mandante.mandante)  " . $where ." " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : '.json_encode($count). ', "data" : '.json_encode($result).'}';

        return  $json;
    }

    /**
     * Consulta personalizada de usuario ruletas.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Campo por el cual se ordenará la consulta.
     * @param string $sord Orden de la consulta (ASC o DESC).
     * @param int $start Inicio del límite de la consulta.
     * @param int $limit Cantidad de registros a obtener.
     * @param string $filters Filtros en formato JSON para aplicar en la consulta.
     * @param bool $searchOn Indica si se deben aplicar los filtros.
     * @param bool $withPosition Indica si se debe incluir la posición del usuario en la ruleta.
     * @return string JSON con el conteo de registros y los datos obtenidos.
     */
    public function queryUsuarioRuletasCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$withPosition=false)
    {
        $Helpers = new Helpers();
        $ruletaEspecificoParaPosicion=0;

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
                if($withPosition && $fieldName=="ruleta_interno.ruleta_id"){
                    $ruletaEspecificoParaPosicion=$fieldData;
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
            if($withPosition && $ruletaEspecificoParaPosicion !=0){
                $sqlWherePos = " WHERE t.ruleta_id='".$ruletaEspecificoParaPosicion."' ";
            }
            $sql = 'SELECT count(*) count FROM usuario_ruleta INNER JOIN usuario_mandante ON (usuario_mandante.usumandante_id = usuario_ruleta.usuario_id) INNER JOIN ruleta_interno ON (ruleta_interno.ruleta_id = usuario_ruleta.ruleta_id) INNER JOIN (SELECT t.usuario_id,
                t.ruleta_id,
                u.usuario_mandante,
                u.nombres,
               t.valor,
               t.premio,
               @rownum := @rownum + 1 AS position
          FROM  usuario_ruleta t
          INNER JOIN usuario_mandante u ON (u.usumandante_id=t.usuario_id)
          JOIN (SELECT @rownum := 0) r '.$sqlWherePos.'
      ORDER BY t.valor DESC) position ON (position.usuario_id = usuario_ruleta.usuario_id  AND position.ruleta_id=ruleta_interno.ruleta_id)
 ' . $where;
        }else{

            $sql = 'SELECT count(*) count FROM usuario_ruleta INNER JOIN usuario_mandante ON (usuario_mandante.usumandante_id = usuario_ruleta.usuario_id) INNER JOIN ruleta_interno ON (ruleta_interno.ruleta_id = usuario_ruleta.ruleta_id) ' . $where;
        }




        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);

        if($withPosition) {

            $sqlWherePos="";
            if($withPosition && $ruletaEspecificoParaPosicion !=0){
                $sqlWherePos = " WHERE t.ruleta_id='".$ruletaEspecificoParaPosicion."' ";
            }

            $sql = 'SELECT ' . $select . '  FROM usuario_ruleta  INNER JOIN usuario_mandante ON (usuario_mandante.usumandante_id = usuario_ruleta.usuario_id) INNER JOIN ruleta_interno ON (ruleta_interno.ruleta_id = usuario_ruleta.ruleta_id) INNER JOIN (SELECT t.usuario_id,
                t.ruleta_id,
                u.usuario_mandante,
                u.nombres,
               t.valor,
               t.premio,
               @rownum := @rownum + 1 AS position
          FROM  usuario_ruleta t
          INNER JOIN usuario_mandante u ON (u.usumandante_id=t.usuario_id)
          JOIN (SELECT @rownum := 0) r '.$sqlWherePos.'
      ORDER BY t.valor DESC) position ON (position.usuario_id = usuario_ruleta.usuario_id AND position.ruleta_id=ruleta_interno.ruleta_id)
 ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;
        }else{
            $sql = 'SELECT ' .$select .'  FROM usuario_ruleta  INNER JOIN usuario_mandante ON (usuario_mandante.usumandante_id = usuario_ruleta.usuario_id) INNER JOIN ruleta_interno ON (ruleta_interno.ruleta_id = usuario_ruleta.ruleta_id) ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        }


        $sqlQuery = new SqlQuery($sql);

        $result = $Helpers->process_data($this->execute2($sqlQuery));

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }

    /**
     * Realiza una consulta personalizada en la tabla usuario_ruleta sin considerar la posición.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Campo por el cual se ordenará la consulta.
     * @param string $sord Orden de la consulta (ASC o DESC).
     * @param int $start Índice de inicio para la paginación.
     * @param int $limit Límite de registros a obtener.
     * @param string $filters Filtros en formato JSON para construir la cláusula WHERE.
     * @param bool $searchOn Indica si se deben aplicar los filtros.
     *
     * @return string JSON con el conteo de registros y los datos obtenidos.
     */
    public function queryUsuarioRuletasCustomWithoutPosition($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
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


        $sql = 'SELECT count(*) count FROM usuario_ruleta INNER JOIN usuario_mandante ON (usuario_mandante.usumandante_id = usuario_ruleta.usuario_id) INNER JOIN ruleta_interno ON (ruleta_interno.ruleta_id = usuario_ruleta.ruleta_id)' . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT ' .$select .'  FROM usuario_ruleta  INNER JOIN usuario_mandante ON (usuario_mandante.usumandante_id = usuario_ruleta.usuario_id) INNER JOIN ruleta_interno ON (ruleta_interno.ruleta_id = usuario_ruleta.ruleta_id)' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;


        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }

    /**
    * Realizar una consulta en la tabla de UsuarioRuleta 'UsuarioRuleta'
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
    public function getAllUsuarioRuletas($value, $category, $provider, $offset, $limit, $search, $partnerId)
    {
        $where = " 1=1 ";
        if ($category != "") {
            $where = $where . " AND categoria_usuario_ruleta.categoria_id= ? ";
        }

        if ($provider != "") {
            $where = $where . " AND proveedor.abreviado = ? ";
        }

        if ($search != "") {
            $where = $where . " AND usuario_ruleta.descripcion  LIKE '%" . $search . "%' ";
        }

        if ($offset == "" || $limit == "") {
            $limit = 15;
            $offset = 0;
        }


        $sql = 'SELECT proveedor.*,usuario_ruleta.*,usuario_ruleta_mandante.*, categoria_usuario_ruleta.*,categoria.*,usuario_ruleta_detalle.p_value background FROM proveedor
        LEFT OUTER JOIN usuario_ruleta ON (usuario_ruleta.proveedor_id = proveedor.proveedor_id)
INNER JOIN categoria_usuario_ruleta ON (categoria_usuario_ruleta.usuruleta_id = usuario_ruleta.usuruleta_id)
INNER JOIN usuario_ruleta_mandante ON (usuario_ruleta.usuruleta_id = usuario_ruleta_mandante.usuruleta_id AND usuario_ruleta_mandante.mandante = ' . $partnerId . ' ) LEFT OUTER JOIN categoria ON (categoria.categoria_id =categoria_usuario_ruleta.categoria_id )
LEFT OUTER JOIN usuario_ruleta_detalle ON (usuario_ruleta_detalle.usuruleta_id= usuario_ruleta.usuruleta_id AND usuario_ruleta_detalle.p_key = "IMAGE_BACKGROUND")
 WHERE ' . $where . ' AND proveedor.Tipo = ? AND proveedor.estado="A" AND usuario_ruleta.estado="A" AND usuario_ruleta.mostrar="S" LIMIT ' . ($limit - $offset) . ' OFFSET ' . $offset;

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
     * Obtiene las ruletas expiradas
     * @return array
     */
    public function getUsuarioRuletasExpiradas() {

        $sql = "SELECT ur.usuruleta_id FROM usuario_ruleta ur 
            INNER JOIN ruleta_interno ri ON (ur.ruleta_id = ri.ruleta_id)
            INNER JOIN ruleta_detalle rd_exp ON (ur.ruleta_id = rd_exp.ruleta_id)
            WHERE ur.estado in ('PP','PR','A','P') AND ur.fecha_expiracion IS NOT NULL AND ur.fecha_expiracion < NOW();";

        $sqlQuery = new SqlQuery($sql);
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
