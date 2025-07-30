<?php namespace Backend\mysql;
use Backend\dao\SorteoDetalleDAO;
use Backend\dto\SorteoDetalle;
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
class SorteoDetalleMySqlDAO implements SorteoDetalleDAO
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
        $sql = 'SELECT * FROM sorteo_detalle WHERE sorteodetalle_id = ?';
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
        $sql = 'SELECT * FROM sorteo_detalle WHERE sorteo_id= ? AND tipo= ?';
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
    public function querybySorteoIdAndTipoAndMoneda($id,$tipo,$moneda)
    {
        $sql = 'SELECT * FROM sorteo_detalle WHERE sorteo_id= ? AND tipo= ? AND moneda=?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($id);
        $sqlQuery->set($tipo);
        $sqlQuery->set($moneda);
        return $this->getList($sqlQuery);
    }


    /**
     * Consulta los detalles del sorteo por ID de sorteo, tipo y valor.
     *
     * @param int $id El ID del sorteo.
     * @param string $tipo El tipo de sorteo.
     * @param string $valor El valor del sorteo.
     * @return array La lista de detalles del sorteo que coinciden con los criterios especificados.
     */
    public function queryBySorteoIdAndTipoAndValor($id,$tipo,$valor){
        $sql = 'SELECT * FROM sorteo_detalle WHERE sorteo_id =? AND tipo=? AND valor=? ORDER BY fecha_crea DESC';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($id);
        $sqlQuery->set($tipo);
        $sqlQuery->set($valor);
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
        $sql = 'SELECT * FROM sorteo_detalle';
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
        $sql = 'SELECT * FROM sorteo_detalle ORDER BY ' . $orderColumn;
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
    public function delete($sorteodetalle_id)
    {
        $sql = 'DELETE FROM sorteo_detalle WHERE sorteodetalle_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($sorteodetalle_id);
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
    public function insert($sorteoDetalle)
    {
        $sql = 'INSERT INTO sorteo_detalle (sorteo_id, tipo, moneda,valor,valor2,valor3, usucrea_id, usumodif_id, descripcion, fecha_sorteo, imagen_url, estado, permite_ganador, jugador_excluido) VALUES ( ?, ?,?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->set($sorteoDetalle->sorteoId);
        $sqlQuery->set($sorteoDetalle->tipo);
        $sqlQuery->set($sorteoDetalle->moneda);
        $sqlQuery->set($sorteoDetalle->valor);
        $sqlQuery->set($sorteoDetalle->valor2);
        $sqlQuery->set($sorteoDetalle->valor3);
        $sqlQuery->setNumber($sorteoDetalle->usucreaId);
        $sqlQuery->setNumber($sorteoDetalle->usumodifId);

        $sqlQuery->set($sorteoDetalle->descripcion);

        if($sorteoDetalle->fechaSorteo!="" && $sorteoDetalle->fechaSorteo!=null){
            $sqlQuery->set($sorteoDetalle->fechaSorteo);
        }else{
            $sorteoDetalle->fechaSorteo=date('Y-m-d H:i:s');
            $sqlQuery->set($sorteoDetalle->fechaSorteo);
        }

        $sqlQuery->set($sorteoDetalle->imagenUrl);
        $sqlQuery->set($sorteoDetalle->estado);

        if($sorteoDetalle->permiteGanador!="" && $sorteoDetalle->permiteGanador!=null){
            $sqlQuery->set($sorteoDetalle->permiteGanador);
        }else{
            $sorteoDetalle->permiteGanador=0;
            $sqlQuery->set($sorteoDetalle->permiteGanador);
        }
        // Nueva logica
        if($sorteoDetalle->jugadorExcluido!="" && $sorteoDetalle->jugadorExcluido!=null){
            $sqlQuery->set($sorteoDetalle->jugadorExcluido);
        }else{
            $sorteoDetalle->jugadorExcluido=0;
            $sqlQuery->set($sorteoDetalle->jugadorExcluido);
        }

        $id = $this->executeInsert($sqlQuery);
        $sorteoDetalle->sorteodetalleId = $id;
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
    public function update($sorteoDetalle)
    {
        $sql = 'UPDATE sorteo_detalle SET sorteo_id = ?, tipo = ?, moneda = ?,valor = ?,valor2 = ?,valor3 = ?, usucrea_id = ?, usumodif_id = ?, descripcion = ?, fecha_sorteo = ?, imagen_url= ?, estado= ?, permite_ganador= ?, jugador_excluido=? WHERE sorteodetalle_id = ?';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($sorteoDetalle->sorteoId);
        $sqlQuery->set($sorteoDetalle->tipo);
        $sqlQuery->set($sorteoDetalle->moneda);
        $sqlQuery->set($sorteoDetalle->valor);
        $sqlQuery->set($sorteoDetalle->valor2);
        $sqlQuery->set($sorteoDetalle->valor3);
        $sqlQuery->setNumber($sorteoDetalle->usucreaId);
        $sqlQuery->setNumber($sorteoDetalle->usumodifId);

        $sqlQuery->set($sorteoDetalle->descripcion);
        $sqlQuery->set($sorteoDetalle->fechaSorteo);
        $sqlQuery->set($sorteoDetalle->imagenUrl);
        $sqlQuery->set($sorteoDetalle->estado);

        if($sorteoDetalle->permiteGanador!="" && $sorteoDetalle->permiteGanador!=null){
            $sqlQuery->set($sorteoDetalle->permiteGanador);
        }else{
            $sorteoDetalle->permiteGanador=0;
            $sqlQuery->set($sorteoDetalle->permiteGanador);
        }


        if($sorteoDetalle->jugadorExcluido!="" && $sorteoDetalle->jugadorExcluido!=null){
            $sqlQuery->set($sorteoDetalle->jugadorExcluido);
        }else{
            $sorteoDetalle->jugadorExcluido=0;
            $sqlQuery->set($sorteoDetalle->jugadorExcluido);
        }


        $sqlQuery->setNumber($sorteoDetalle->sorteodetalleId);

        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Actualiza el estado de un sorteo detalle en la base de datos.
     *
     * @param SorteoDetalle $sorteoDetalle Objeto que contiene los datos del sorteo detalle a actualizar.
     * @return int Número de filas afectadas por la actualización.
     */
    public function updateEstado($sorteoDetalle)
    {
        $sql = 'UPDATE sorteo_detalle SET  estado= ? WHERE sorteodetalle_id = ?';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->set($sorteoDetalle->estado);

        $sqlQuery->setNumber($sorteoDetalle->sorteodetalleId);

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
        $sql = 'DELETE FROM sorteo_detalle WHERE sorteo_id = ?';
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
        $sql = 'DELETE FROM sorteo_detalle';
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
    public function querySorteoDetallesCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping="")
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

        $sql = "SELECT count(*) count FROM sorteo_detalle INNER JOIN sorteo_interno ON (sorteo_interno.sorteo_id = sorteo_detalle.sorteo_id) INNER JOIN mandante ON (sorteo_interno.mandante = mandante.mandante) " . $where;

        $sqlQuery = new SqlQuery($sql);


        if($grouping != ""){
            $where = $where . " GROUP BY " . $grouping;
        }




        $count = $this->execute2($sqlQuery);
        $sql = "SELECT ".$select." FROM sorteo_detalle INNER JOIN sorteo_interno ON (sorteo_interno.sorteo_id = sorteo_detalle.sorteo_id) INNER JOIN mandante ON (sorteo_interno.mandante = mandante.mandante) " . $where ." " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        if($_ENV["debugFixed2"] == '1'){
            print_r($sql);
        }
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
     * @return Objeto $sorteoDetalle SorteoDetalle
     *
     * @access protected
     *
     */
    protected function readRow($row)
    {
        $sorteoDetalle = new SorteoDetalle();

        $sorteoDetalle->sorteodetalleId = $row['sorteodetalle_id'];
        $sorteoDetalle->sorteoId = $row['sorteo_id'];
        $sorteoDetalle->tipo = $row['tipo'];
        $sorteoDetalle->moneda = $row['moneda'];
        $sorteoDetalle->valor = $row['valor'];
        $sorteoDetalle->valor2 = $row['valor2'];
        $sorteoDetalle->valor3 = $row['valor3'];
        $sorteoDetalle->usucreaId = $row['usucrea_id'];
        $sorteoDetalle->fechaCrea = $row['fecha_crea'];
        $sorteoDetalle->usumodifId = $row['usumodif_id'];
        $sorteoDetalle->fechaModif = $row['fecha_modif'];

        $sorteoDetalle->descripcion = $row['descripcion'];
        $sorteoDetalle->fechaSorteo = $row['fecha_sorteo'];
        $sorteoDetalle->imagenUrl = $row['imagen_url'];
        $sorteoDetalle->estado = $row['estado'];
        $sorteoDetalle->permiteGanador = $row['permite_ganador'];
        $sorteoDetalle->jugadorExcluido = $row['jugador_excluido'];


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
