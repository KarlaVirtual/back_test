<?php namespace Backend\mysql;

use Backend\dto\usuarioSorteo2;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Exception;

/**
 * Clase UsuarioSorteo2MySqlDAO
 *
 *Clase encargada de proveer las consultas de la tabla usuario_sorteo2
 *
 * @author Desconocido
 * @package No
 * @category No
 * @version    1.0
 * @since Desconocido
 */
class UsuarioSorteo2MySqlDAO{
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
     public function setTransaction($transaction){
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


     public function load($id){

        $sql = 'SELECT * FROM usuario_sorteo2 WHERE ususorteo2_id = ?';
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
         $sql = 'SELECT * FROM usuario_sorteo2';
         $sqlQuery = new SqlQuery($sql);
         return $this->getList($sqlQuery);
     }


    /**
     * Obtener todos los registros ordenados por una columna específica
     *
     * @param string $orderColumn Columna por la cual ordenar los registros
     * @return array Lista de registros ordenados
     */
    public function queryAllOrderBy($orderColumn)
    {
        $sql = 'SELECT * FROM usuario_sorteo2 ORDER BY ' . $orderColumn;
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde el registro2_id sea igual al valor pasado como parámetro
     *
     * @param int $value Valor del registro2_id
     * @return array Lista de registros que coinciden con el registro2_id
     */
    public function queryByRegistroId($value)
    {
        $sql = 'SELECT * FROM usuario_sorteo2 where registro2_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Verificar si existe un registro con el código especificado
     *
     * @param int $code Código a verificar
     * @return array Lista de registros que coinciden con el código
     */
    public function CheckCode($code)
    {
        $sql = 'SELECT * FROM usuario_sorteo2 where codigo = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($code);
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

    
     public function insert($UsuarioSorteo2){

        $sql = 'INSERT INTO usuario_sorteo2 (sorteo2_id,registro2_id,valor,posicion,valor_base,usucrea_id,usumodifId,estado,error_id,id_externo,mandante,version,apostado,codigo,externo_id,valor_premio,pais_id,premio,premio_id) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';

        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->set($UsuarioSorteo2->Sorteo2Id);
        $sqlQuery->set($UsuarioSorteo2->Registro2Id);
        $sqlQuery->set($UsuarioSorteo2->valor);
        $sqlQuery->set($UsuarioSorteo2->posicion);
        $sqlQuery->set($UsuarioSorteo2->valorBase);
        $sqlQuery->set($UsuarioSorteo2->usucreaId);
        $sqlQuery->set($UsuarioSorteo2->usumodifId);
        $sqlQuery->set($UsuarioSorteo2->estado);
    
        if($UsuarioSorteo2->errorId == ""){
            $sqlQuery->set(0);
        }else{
            $sqlQuery->set($UsuarioSorteo2->errorId);
        }
        
        if($UsuarioSorteo2->idExterno == ""){
            $sqlQuery->set(0);
        }else{
            $sqlQuery->set($UsuarioSorteo2->idExterno);
        }
        
        if($UsuarioSorteo2->mandante == ""){
            $sqlQuery->set(0);
        }else{
            $sqlQuery->set($UsuarioSorteo2->mandante);
        }

        if($UsuarioSorteo2->version == ""){
            $sqlQuery->set(0);
        }else{
            $sqlQuery->set($UsuarioSorteo2->version);
        }

        if($UsuarioSorteo2->apostado == ""){
            $sqlQuery->set(0);
        }else{
            $sqlQuery->set($UsuarioSorteo2->apostado);
        }
        
        if($UsuarioSorteo2->codigo == ""){
            $sqlQuery->set(0);
        }else{
            $sqlQuery->set($UsuarioSorteo2->codigo);
        }

        
        if($UsuarioSorteo2->externoId == ""){
            $sqlQuery->set(0);
        }else{
            $sqlQuery->set($UsuarioSorteo2->externoId);
        }

    
        if($UsuarioSorteo2->valorPremio == ""){
            $sqlQuery->set(0);
        }else{
            $sqlQuery->set($UsuarioSorteo2->valorPremio);
        }

        
        if($UsuarioSorteo2->paisId == ""){
            $sqlQuery->set(0);
        }else{
            $sqlQuery->set($UsuarioSorteo2->paisId);
        }

        if($UsuarioSorteo2->premio == ""){
            $sqlQuery->set(0);
        }else{
            $sqlQuery->set($UsuarioSorteo2->premio);
        }

        if($UsuarioSorteo2->premioId == ""){
            $sqlQuery->set(0);
        }else{
            $sqlQuery->set($UsuarioSorteo2->premioId);
        }


        $id = $this->executeInsert($sqlQuery);
        $UsuarioSorteo2->ususorteo2Id = $id;
        return $id;

    }

    /**
     * Actualiza un registro en la tabla usuario_sorteo2.
     *
     * @param UsuarioSorteo2 $usuarioSorteo2 Objeto que contiene los datos a actualizar.
     * @return int Número de filas afectadas por la actualización.
     */
    public function update($usuarioSorteo2){

        $sql = 'UPDATE usuario_sorteo2 SET sorteo2_id=?,registro2_id=?,valor=?,posicion=?,valor_base=?,usucrea_id=?,usumodifId=?,estado=?,error_id=?,id_externo=?,mandante=?,version=?,apostado=?,premio_id = ?,valor_premio=?,pais_id=?,premio=?,codigo=?,externo_id=? where ususorteo2_id =?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($usuarioSorteo2->Sorteo2Id);
        $sqlQuery->set($usuarioSorteo2->Registro2Id);
        $sqlQuery->set($usuarioSorteo2->valor);
        $sqlQuery->set($usuarioSorteo2->posicion);
        $sqlQuery->set($usuarioSorteo2->valorBase);
        $sqlQuery->set($usuarioSorteo2->usucreaId);

        if($usuarioSorteo2->usumodifId == ""){
            $sqlQuery->set(0);
        }else{
            $sqlQuery->set($usuarioSorteo2->usumodifId);

        }
        $sqlQuery->set($usuarioSorteo2->estado);
        $sqlQuery->set($usuarioSorteo2->errorId);
        $sqlQuery->set($usuarioSorteo2->idExterno);
        $sqlQuery->set($usuarioSorteo2->mandante);
        $sqlQuery->set($usuarioSorteo2->version);
        $sqlQuery->set($usuarioSorteo2->apostado);
        $sqlQuery->set($usuarioSorteo2->PremioId);
        $sqlQuery->set($usuarioSorteo2->valorPremio);
        $sqlQuery->set($usuarioSorteo2->paisId);
        $sqlQuery->set($usuarioSorteo2->premio);
        $sqlQuery->set($usuarioSorteo2->codigo);
        $sqlQuery->set($usuarioSorteo2->externoId);
        $sqlQuery->set($usuarioSorteo2->ususorteo2Id);


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


    public function clean(){
        $sql = 'DELETE FROM usuario_sorteo2';
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

     public function queryByEstado($value){
         $sql = 'SELECT * FROM usuario_sorteo2 WHERE estado = ?';
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
         $sql = 'SELECT * FROM usuario_sorteo2 WHERE fecha_crea = ?';
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

     public function queryByUsucreaId($value){
        $sql = 'SELECT * FROM usuario_sorteo2 WHERE usucrea_id = ?';
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
         $sql = 'SELECT * FROM usuario_sorteo2 WHERE usumodif_id = ?';
         $sqlQuery = new SqlQuery($sql);
         $sqlQuery->setNumber($value);
         return $this->getList($sqlQuery);
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
        $sql = 'DELETE FROM usuario_sorteo2 WHERE estado = ?';
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

     public function deleteByFechaCrea($value){
         $sql = 'DELETE FROM usuario_sorteo2 WHERE fecha_crea = ?';
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
 

     public function deleteByUsucreaId($value){
        $sql = 'DELETE FROM usuario_sorteo2 WHERE usucrea_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna usumodif_id sea igual al valor pasado como parámetro.
     *
     * @param int $value usumodif_id requerido
     * @return boolean Resultado de la ejecución
     */
    public function deleteByUsumodifId($value)
    {
        $sql = 'DELETE FROM usuario_sorteo2 WHERE usumodif_id = ?';
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
     * @return Objeto $usuario_sorteo2 UsuarioSorteo
     *
     * @access protected
     *
     */


     protected function readRow($row)
     {
         $usuario_sorteo2 = new usuarioSorteo2();
 
         $usuario_sorteo2->ususorteo2Id = $row['ususorteo2_id'];
         $usuario_sorteo2->Sorteo2Id = $row['sorteo2_id'];
         $usuario_sorteo2->Registro2Id = $row['registro2_id'];
         $usuario_sorteo2->valor = $row['valor'];
         $usuario_sorteo2->posicion = $row['posicion'];
         $usuario_sorteo2->valorBase = $row['valor_base'];
         $usuario_sorteo2->estado = $row['estado'];
         $usuario_sorteo2->fechaCrea = $row['fecha_crea'];
         $usuario_sorteo2->usucreaId = $row['usucrea_id'];
         $usuario_sorteo2->fechaModif = $row['fecha_modif'];
         $usuario_sorteo2->usumodifId = $row['usumodif_id'];
         $usuario_sorteo2->estado = $row['estado'];
         $usuario_sorteo2->errorId = $row['error_id'];
         $usuario_sorteo2->idExterno = $row['id_externo'];
         $usuario_sorteo2->mandante = $row['mandante'];
         $usuario_sorteo2->version = $row['version'];
         $usuario_sorteo2->paisId = $row['pais_id'];
         $usuario_sorteo2->externoId = $row['externo_id'];
         $usuario_sorteo2->valorPremio = $row['valor_premio'];
         $usuario_sorteo2->apostado = $row['apostado'];
         $usuario_sorteo2->codigo = $row['codigo'];
         $usuario_sorteo2->premio = $row['premio'];
         $usuario_sorteo2->PremioId = $row['premio_id'];


         return $usuario_sorteo2;
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

     
    public function queryUsuarioSorteosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$withPosition=false)
    {
        $sorteoEspecificoParaPosicion=0;

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
                if($withPosition && $fieldName=="sorteo_interno2.sorteo2_id"){
                    $sorteoEspecificoParaPosicion=$fieldData;
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
                if (count($whereArray) > 0) {
                    $where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
                } else {
                    $where = "";
                }
            }

        }
        $withPosition=false;
        if($withPosition){
            $sqlWherePos="";
            if($withPosition && $sorteoEspecificoParaPosicion !=0){
                $sqlWherePos = " WHERE t.sorteo2_id='".$sorteoEspecificoParaPosicion."' ";
            }
            $sql = 'SELECT count(*) count FROM usuario_sorteo2 INNER JOIN sorteo_interno2 ON (sorteo_interno2.sorteo2_id = usuario_sorteo2.sorteo2_id) INNER JOIN (SELECT t.registro2_id,
                t.sorteo2_id,
               t.valor,
               @rownum := @rownum + 1 AS position
          FROM  usuario_sorteo2 t'.$sqlWherePos.'
      ORDER BY t.valor DESC) position ON (position.registro2_id = usuario_sorteo2.registro2_id  AND position.sorteo2_id=sorteo_interno2.sorteo2_id)
 ' . $where;
        }else{
            $sql = 'SELECT count(*) count FROM usuario_sorteo2 INNER JOIN sorteo_interno2 ON (sorteo_interno2.sorteo2_id = usuario_sorteo2.sorteo2_id) INNER JOIN registro2 ON (usuario_sorteo2.registro2_id = registro2.registro2_id) ' . $where;
        }

        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);

        if($withPosition) {

            $sqlWherePos="";
            if($withPosition && $sorteoEspecificoParaPosicion !=0){
                $sqlWherePos = " WHERE t.sorteo2_id='".$sorteoEspecificoParaPosicion."' ";
            }

            $sql = 'SELECT ' . $select . '  FROM usuario_sorteo2  INNER JOIN sorteo_interno2 ON (sorteo_interno2.sorteo2_id = usuario_sorteo2.sorteo2_id) INNER JOIN (SELECT t.registro2_id,
                t.sorteo2_id,
               t.valor,
               @rownum := @rownum + 1 AS position
          FROM  usuario_sorteo2 t'.$sqlWherePos.'
      ORDER BY t.valor DESC) position ON (position.registro2_id = usuario_sorteo2.registro2_id AND position.sorteo2_id=sorteo_interno2.sorteo2_id)
 ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;
        }else{
            $sql = 'SELECT ' .$select .'  FROM usuario_sorteo2 INNER JOIN sorteo_interno2 ON (sorteo_interno2.sorteo2_id = usuario_sorteo2.sorteo2_id) INNER JOIN registro2 ON (usuario_sorteo2.registro2_id = registro2.registro2_id)' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;
        }

        if($_ENV["debugFixed2"] == '1'){
            print_r($sql);
        }

        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }

    /**
     * Consulta personalizada de usuario_sorteos sin posición.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Campo por el cual ordenar.
     * @param string $sord Orden de la consulta (ASC o DESC).
     * @param int $start Inicio del límite de la consulta.
     * @param int $limit Número de registros a obtener.
     * @param string $filters Filtros en formato JSON para aplicar en la consulta.
     * @param bool $searchOn Indica si se deben aplicar los filtros.
     * 
     * @return string JSON con el conteo de registros y los datos obtenidos.
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
                if (count($whereArray) > 0) {
                    $where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
                } else {
                    $where = "";
                }
            }

        }


        $sql = 'SELECT count(*) count FROM usuario_sorteo2 INNER JOIN sorteo_interno2 ON (sorteo_interno2.sorteo2_id = usuario_sorteo2.sorteo2_id)' . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT ' .$select .'  FROM usuario_sorteo2 INNER JOIN sorteo_interno2 ON (sorteo_interno2.sorteo2_id = usuario_sorteo2.sorteo2_id)' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;


        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

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
        for ($i = 0; $i < count($tab); $i++) {
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
        if (count($tab) == 0) {
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