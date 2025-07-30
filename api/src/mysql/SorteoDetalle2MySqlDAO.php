<?php namespace Backend\mysql;

use Backend\dto\SorteoDetalle2;
use Backend\sql\QueryExecutor;
use Backend\sql\SqlQuery;
use Backend\sql\Transaction;


/** 
* Clase 'SorteoDetalleMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'SaldoUsuonlineAjuste'
* 
* Ejemplo de uso: 
* $SorteoDetalleMySqlDAO = new SorteoDetalle();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/

class SorteoDetalle2MySqlDAO{
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
        $sql = 'SELECT * FROM sorteo_detalle2 WHERE sorteodetalle2_id = ?';
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

     public function querybySorteoIdAndTipo($id,$tipo)
     {
         $sql = 'SELECT * FROM sorteo_detalle2 WHERE sorteo2_id= ? AND tipo= ?';
         $sqlQuery = new SqlQuery($sql);
         $sqlQuery->setNumber($id);
         $sqlQuery->set($tipo);
         return $this->getList($sqlQuery);
     }

     
     /**
     * Obtener los registros de la base datos por id de sorteo
     *
     * @return Array $ resultado de la consulta
     *
     */


     public function queryBySorteoId($value){
        $sql = 'SELECT * FROM sorteo_detalle2 where sorteo2_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);
     }


    /**
     * Obtener todos los registros de la base datos
     *
     * @return Array $ resultado de la consulta
     *
     */


     public function queryAll(){
        $sql = 'SELECT * FROM sorteo_detalle2';
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



     public function queryAllOrderBy($orderColumn){
        $sql = 'SELECT * FROM sorteo_detalle2 ORDER BY ' . $orderColumn;
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

     public function delete($sorteodetalle2_id){
         $sql = 'DELETE FROM sorteo_detalle2 WHERE sorteodetalle2_id = ?';
         $sqlQuery = new SqlQuery($sql);
         $sqlQuery->setNumber($sorteodetalle2_id);
         return $this->executeUpdate($sqlQuery);
     }

    /**
     * Insertar un registro en la base de datos
     *
     * @param Objeto sorteoDetalle sorteoDetalle
     *
     * @return String $id resultado de la consulta
     *
     */


     public function queryBytipo($tipo){
        $sql = 'SELECT * FROM sorteo_detalle2 where tipo = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($tipo);
        return $this->executeUpdate($sqlQuery);
     }

    /**
     * Inserta un nuevo registro en la tabla sorteo_detalle2.
     *
     * @param object $sorteoDetalle Objeto que contiene los datos del sorteo detalle a insertar.
     * 
     * @return int El ID del registro insertado.
     */
     public function insert($sorteoDetalle){
      
        $sql = 'INSERT INTO sorteo_detalle2 (sorteo2_id,tipo,moneda,valor,usucrea_id,usumodif_id,descripcion,valor2,valor3,fecha_sorteo,estado,permite_ganador,imagen_url,jugador_excluido) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
        
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($sorteoDetalle->sorteo2Id);
        $sqlQuery->set($sorteoDetalle->tipo);
        $sqlQuery->set($sorteoDetalle->moneda);
        $sqlQuery->set($sorteoDetalle->valor);
        $sqlQuery->set($sorteoDetalle->usucreaId);
        $sqlQuery->set($sorteoDetalle->usumodifId);
        $sqlQuery->set($sorteoDetalle->descripcion);
        
        if($sorteoDetalle->valor2 == ""){
            $sqlQuery->set(0);
        }else{
            $sqlQuery->set($sorteoDetalle->valor2);
        }
        
        if($sorteoDetalle->valor3 == ''){
            $sqlQuery->set(0);
        }else{
            $sqlQuery->set($sorteoDetalle->valor3);
        }

        $sqlQuery->set($sorteoDetalle->fechaSorteo);

        if($sorteoDetalle->estado == ""){
            $sqlQuery->set('A');
        }else{
            $sqlQuery->set($sorteoDetalle->estado);
        }

        if($sorteoDetalle->permiteGanador == ""){
            $sqlQuery->set(0);
        }else{
            $sqlQuery->set($sorteoDetalle->permiteGanador);
        }


         if($sorteoDetalle->jugadorExcluido == ""){
            $sqlQuery->set(0);
        }else{
            $sqlQuery->set($sorteoDetalle->jugadorExcluido);
        }

        $sqlQuery->set($sorteoDetalle->imagenUrl);

      
       

        $id = $this->executeInsert($sqlQuery);
        $sorteoDetalle->sorteodetalle2Id = $id;
        return $id;
    }

     /**
     * Editar un registro en la base de datos
     *
     * @param Objeto sorteoDetalle sorteoDetalle
     *
     * @return boolean $ resultado de la consulta
     *
     */

     public function update($sorteoDetalle){
        $sql = "UPDATE sorteo_detalle2 SET sorteo2_id = ?,tipo = ?,moneda = ?,valor = ?,usucrea_id = ?,usumodif_id = ?, descripcion = ?,valor2 = ?,valor3 = ?,fecha_sorteo = ?,estado = ?,imagen_url = ?, permite_ganador = ?, jugador_excluido = ? WHERE sorteodetalle2_id = ?";
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($sorteoDetalle->sorteo2_id);
        $sqlQuery->set($sorteoDetalle->tipo);
        $sqlQuery->set($sorteoDetalle->moneda);
        $sqlQuery->set($sorteoDetalle->valor);
        $sqlQuery->set($sorteoDetalle->usucreaId);
        $sqlQuery->set($sorteoDetalle->usumodifId);
        $sqlQuery->set($sorteoDetalle->descripcion);
        $sqlQuery->set($sorteoDetalle->valor2);
        $sqlQuery->set($sorteoDetalle->valor3);
        $sqlQuery->set($sorteoDetalle->fechaSorteo);
        $sqlQuery->set($sorteoDetalle->estado);
        $sqlQuery->set($sorteoDetalle->imagenUrl);
        $sqlQuery->set($sorteoDetalle->permiteGanador);
        $sqlQuery->set($sorteoDetalle->jugadorExcluido);



         $sqlQuery->setNumber($sorteoDetalle->sorteodetalle2Id);
        return $this->executeUpdate($sqlQuery);

     }


    /**
     * Actualiza el estado de un sorteo detalle en la base de datos.
     *
     * @param object $sorteoDetalle Objeto que contiene los datos del sorteo detalle a actualizar.
     * @return int Número de filas afectadas por la actualización.
     */
     public function updateEstado($sorteoDetalle)
     {
         $sql = 'UPDATE sorteo_detalle2 SET  estado= ? WHERE sorteodetalle2_id = ?';
         $sqlQuery = new SqlQuery($sql);
 
         $sqlQuery->set($sorteoDetalle->estado);
 
         $sqlQuery->setNumber($sorteoDetalle->sorteodetalle2Id);
 
         return $this->executeUpdate($sqlQuery);
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


     public function deleteBySorteoId($value)
    {
        $sql = 'DELETE FROM sorteo_detalle2 WHERE sorteo2_id = ?';
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

     public function clean(){
         $sql = 'DELETE FROM sorteo_detalle2';
         $sqlQuery = new SqlQuery($sql);
         return $this->executeUpdate($sqlQuery);
     }
 

      /**
    * Realizar una consulta en la tabla de detalles de sorteos 'SorteoDetalle'
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


public function querySorteoDetallesCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping=""){


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
                        $fieldOperation = " NOT IS NULL ";
                        break;
                    default:
                        $fieldOperation = "";
                        break;
                }
                if($fieldOperation != "") $whereArray[] = $fieldName.$fieldOperation;
                if (count($whereArray)>0)
                {
                    $where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
                }
                else
                {
                    $where = "";
                }
            }

        }

        $sql = "SELECT count(*) count FROM sorteo_detalle2 INNER JOIN sorteo_interno2 ON (sorteo_interno2.sorteo2_id = sorteo_detalle2.sorteo2_id) INNER JOIN mandante ON (sorteo_interno2.mandante = mandante.mandante) " . $where;

        $sqlQuery = new SqlQuery($sql);


        if($grouping != ""){
            $where = $where . " GROUP BY " . $grouping;
        }


        $count = $this->execute2($sqlQuery);
        $sql = "SELECT ".$select." FROM sorteo_detalle2 INNER JOIN sorteo_interno2 ON (sorteo_interno2.sorteo2_id = sorteo_detalle2.sorteo2_id) INNER JOIN mandante ON (sorteo_interno2.mandante = mandante.mandante) " . $where ." " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : '.json_encode($count). ', "data" : '.json_encode($result).'}';

        return  $json;
    }


    /**
     * Crear y devolver un objeto del tipo SorteoDetalle
     * con los valores de una consulta sql
     * 
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Objeto $sorteoDetalle2 SorteoDetalle2
     *
     * @access protected
     *
     */


     protected function readRow($row)
     {
         $sorteoDetalle = new SorteoDetalle2();
 
         $sorteoDetalle->sorteodetalle2Id = $row['sorteodetalle2_id'];
         $sorteoDetalle->sorteo2Id = $row['sorteo2_id'];
         $sorteoDetalle->tipo = $row['tipo'];
         $sorteoDetalle->moneda = $row['moneda'];
         $sorteoDetalle->valor = $row['valor'];
         $sorteoDetalle->fechaCrea = $row['fecha_crea'];
         $sorteoDetalle->usucreaId = $row['usucrea_id'];
         $sorteoDetalle->fechaModif = $row['fecha_modif'];
         $sorteoDetalle->usumodifId = $row['usumodif_id'];
         $sorteoDetalle->descripcion = $row['descripcion'];
         $sorteoDetalle->valor2 = $row['valor2'];
         $sorteoDetalle->valor3 = $row['valor3'];
         $sorteoDetalle->estado = $row['estado'];
         $sorteoDetalle->fechaSorteo = $row['fecha_sorteo'];
         $sorteoDetalle->permiteGanador = $row['permite_ganador'];
         $sorteoDetalle->jugadorExcluido = $row['jugador_excluido'];


         $sorteoDetalle->imagenUrl = $row['imagen_url'];

 
         return $sorteoDetalle;
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

     protected function getRow($sqlQuery){
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
