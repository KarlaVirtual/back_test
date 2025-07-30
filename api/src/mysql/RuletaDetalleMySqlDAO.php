<?php namespace Backend\mysql;
use Backend\dao\RuletaDetalleDAO;
use Backend\dto\Helpers;
use Backend\dto\RuletaDetalle;
use Backend\sql\QueryExecutor;
use Backend\sql\SqlQuery;
use Backend\sql\Transaction;
/** 
* Clase 'RuletaDetalleMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'SaldoUsuonlineAjuste'
* 
* Ejemplo de uso: 
* $RuletaDetalleMySqlDAO = new RuletaDetalle();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class RuletaDetalleMySqlDAO implements RuletaDetalleDAO
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
        $sql = 'SELECT * FROM ruleta_detalle WHERE ruletadetalle_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($id);
        return $this->getRow($sqlQuery);
    }


    /**
     * Obtener todos los registros donde se encuentre que
     * las columnas id y tipo sean iguales a los valores
     * pasados como parametros
     *
     * @param String $id llave primaria
     * @param String $tipo tipo
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function querybyRuletaIdAndTipo($id,$tipo)
    {
        $sql = 'SELECT * FROM ruleta_detalle WHERE ruleta_id= ? AND tipo= ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($id);
        $sqlQuery->set($tipo);
        return $this->getList($sqlQuery);
    }


    /**
     * Obtener todos los registros donde se encuentre que
     * las columnas id, tipo y moneda sean iguales a los valores
     * pasados como parametros
     *
     * @param String $id llave primaria
     * @param String $tipo tipo
     * @param String $moneda moneda
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function querybyRuletaIdAndTipoAndMoneda($id,$tipo,$moneda)
    {
        $sql = 'SELECT * FROM ruleta_detalle WHERE ruleta_id= ? AND tipo= ? AND moneda=?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($id);
        $sqlQuery->set($tipo);
        $sqlQuery->set($moneda);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros de la base datos
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryAll()
    {
        $sql = 'SELECT * FROM ruleta_detalle';
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
        $sql = 'SELECT * FROM ruleta_detalle ORDER BY ' . $orderColumn;
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }
    
    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $ajuste_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function delete($ruletadetalle_id)
    {
        $sql = 'DELETE FROM ruleta_detalle WHERE ruletadetalle_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($ruletadetalle_id);
        return $this->executeUpdate($sqlQuery);
    }
    
    /**
     * Insertar un registro en la base de datos
     *
     * @param Objeto ruletaDetalle ruletaDetalle
     *
     * @return String $id resultado de la consulta
     *
     */
    public function insert($ruletaDetalle)
    {
        $sql = 'INSERT INTO ruleta_detalle (ruleta_id, tipo, moneda,valor,valor2,valor3,porcentaje,usucrea_id, usumodif_id, descripcion) VALUES ( ?, ?,?,?, ?, ?, ?, ?, ?,?)';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->set($ruletaDetalle->ruletaId);
        $sqlQuery->set($ruletaDetalle->tipo);
        $sqlQuery->set($ruletaDetalle->moneda);
        $sqlQuery->set($ruletaDetalle->valor);
        $sqlQuery->set($ruletaDetalle->valor2);
        $sqlQuery->set($ruletaDetalle->valor3);
        $sqlQuery->set($ruletaDetalle->porcentaje);
        $sqlQuery->setNumber($ruletaDetalle->usucreaId);
        $sqlQuery->setNumber($ruletaDetalle->usumodifId);

        $sqlQuery->set($ruletaDetalle->descripcion);

        $id = $this->executeInsert($sqlQuery);
        $ruletaDetalle->ruletadetalleId = $id;
        return $id;
    }
    
    /**
     * Editar un registro en la base de datos
     *
     * @param Objeto ruletaDetalle ruletaDetalle
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function update($ruletaDetalle)
    {
        $sql = 'UPDATE ruleta_detalle SET ruleta_id = ?, tipo = ?, moneda = ?,valor = ?,valor2 = ?,valor3 = ?,porcentaje = ? ,usucrea_id = ?, usumodif_id = ?, descripcion = ? WHERE ruletadetalle_id = ?';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($ruletaDetalle->ruletaId);
        $sqlQuery->setNumber($ruletaDetalle->tipo);
        $sqlQuery->set($ruletaDetalle->moneda);
        $sqlQuery->set($ruletaDetalle->valor);
        $sqlQuery->set($ruletaDetalle->valor2);
        $sqlQuery->set($ruletaDetalle->valor3);
        $sqlQuery->set($ruletaDetalle->porcentaje);
        $sqlQuery->setNumber($ruletaDetalle->usucreaId);
        $sqlQuery->setNumber($ruletaDetalle->usumodifId);

        $sqlQuery->set($ruletaDetalle->descripcion);


        $sqlQuery->setNumber($ruletaDetalle->ruletadetalleId);

        return $this->executeUpdate($sqlQuery);
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
    public function deleteByRuletaId($value)
    {
        $sql = 'DELETE FROM ruleta_detalle WHERE ruleta_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
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
        $sql = 'DELETE FROM ruleta_detalle';
        $sqlQuery = new SqlQuery($sql);
        return $this->executeUpdate($sqlQuery);
    }








    /**
    * Realizar una consulta en la tabla de detalles de ruletas 'RuletaDetalle'
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
    public function queryRuletaDetallesCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping="")
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

        $sql = "SELECT count(*) count FROM ruleta_detalle INNER JOIN ruleta_interno ON (ruleta_interno.ruleta_id = ruleta_detalle.ruleta_id) INNER JOIN mandante ON (ruleta_interno.mandante = mandante.mandante)  " . $where;

        $sqlQuery = new SqlQuery($sql);


        if($grouping != ""){
            $where = $where . " GROUP BY " . $grouping;
        }




        $count = $this->execute2($sqlQuery);
        $sql = "SELECT ".$select." FROM ruleta_detalle INNER JOIN ruleta_interno ON (ruleta_interno.ruleta_id = ruleta_detalle.ruleta_id) INNER JOIN mandante ON (ruleta_interno.mandante = mandante.mandante) " . $where ." " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        if($_ENV["debugFixed2"] == '1'){
            print_r($sql);
        }

        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : '.json_encode($count). ', "data" : '.json_encode($result).'}';

        return  $json;
    }


    /**
     * Consulta personalizada de detalles de ruleta con filtros y ordenación.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Campo por el cual se ordenará.
     * @param string $sord Orden de clasificación (ASC o DESC).
     * @param int $start Índice de inicio para la paginación.
     * @param int $limit Límite de registros a devolver.
     * @param string $filters Filtros en formato JSON para aplicar en la consulta.
     * @param bool $searchOn Indica si se deben aplicar los filtros.
     * @param string $grouping Campo por el cual se agruparán los resultados (opcional).
     * 
     * @return string JSON con el conteo de registros y los datos resultantes de la consulta.
     */
    public function queryRuletaDetallesCustom3($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping="")
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

        $sql = "SELECT count(*) count FROM ruleta_detalle INNER JOIN ruleta_interno ON (ruleta_interno.ruleta_id = ruleta_detalle.ruleta_id) INNER JOIN mandante ON (ruleta_interno.mandante = mandante.mandante) INNER JOIN usuario_ruleta ON (ruleta_interno.ruleta_id = usuario_ruleta.ruleta_id)  " . $where;

        $sqlQuery = new SqlQuery($sql);


        if($grouping != ""){
            $where = $where . " GROUP BY " . $grouping;
        }




        $count = $this->execute2($sqlQuery);
        $sql = "SELECT ".$select." FROM ruleta_detalle INNER JOIN ruleta_interno ON (ruleta_interno.ruleta_id = ruleta_detalle.ruleta_id) INNER JOIN mandante ON (ruleta_interno.mandante = mandante.mandante) INNER JOIN usuario_ruleta ON (ruleta_interno.ruleta_id = usuario_ruleta.ruleta_id) " . $where ." " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        if($_ENV["debugFixed2"] == '1'){
            print_r($sql);
        }

        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : '.json_encode($count). ', "data" : '.json_encode($result).'}';

        return  $json;
    }


    /**
     * Consulta personalizada de detalles de ruleta con filtros y ordenamiento.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Campo por el cual se ordenará la consulta.
     * @param string $sord Orden de la consulta (ASC o DESC).
     * @param int $start Índice de inicio para la paginación.
     * @param int $limit Límite de registros a devolver.
     * @param string $filters Filtros en formato JSON para aplicar en la consulta.
     * @param bool $searchOn Indica si se deben aplicar los filtros.
     * @param string $grouping Campo por el cual se agrupará la consulta (opcional).
     * 
     * @return string JSON con el conteo de registros y los datos resultantes de la consulta.
     */
    public function queryRuletaDetallesCustom2($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping="")
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

        $sql = "SELECT count(*) count FROM ruleta_detalle INNER JOIN ruleta_interno ON (ruleta_interno.ruleta_id = ruleta_detalle.ruleta_id) INNER JOIN mandante ON (ruleta_interno.mandante = mandante.mandante)  " . $where;

        $sqlQuery = new SqlQuery($sql);


        if($grouping != ""){
            $where = $where . " GROUP BY " . $grouping;
        }




        $count = $this->execute2($sqlQuery);
        $sql = "SELECT ".$select." FROM ruleta_detalle INNER JOIN ruleta_interno ON (ruleta_interno.ruleta_id = ruleta_detalle.ruleta_id) INNER JOIN mandante ON (ruleta_interno.mandante = mandante.mandante)" . $where ." " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : '.json_encode($count). ', "data" : '.json_encode($result).'}';

        return  $json;
    }

    
    /**
     * Crear y devolver un objeto del tipo RuletaDetalle
     * con los valores de una consulta sql
     * 
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Objeto $ruletaDetalle RuletaDetalle
     *
     * @access protected
     *
     */
    protected function readRow($row)
    {
        $ruletaDetalle = new RuletaDetalle();

        $ruletaDetalle->ruletadetalleId = $row['ruletadetalle_id'];
        $ruletaDetalle->ruletaId = $row['ruleta_id'];
        $ruletaDetalle->tipo = $row['tipo'];
        $ruletaDetalle->moneda = $row['moneda'];
        $ruletaDetalle->valor = $row['valor'];
        $ruletaDetalle->valor2 = $row['valor2'];
        $ruletaDetalle->valor3 = $row['valor3'];
        $ruletaDetalle->porcentaje = $row['porcentaje'];
        $ruletaDetalle->usucreaId = $row['usucrea_id'];
        $ruletaDetalle->fechaCrea = $row['fecha_crea'];
        $ruletaDetalle->usumodifId = $row['usumodif_id'];
        $ruletaDetalle->fechaModif = $row['fecha_modif'];

        $ruletaDetalle->descripcion = $row['descripcion'];

        return $ruletaDetalle;
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
