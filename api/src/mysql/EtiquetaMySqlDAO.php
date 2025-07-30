<?php namespace Backend\mysql;
use Backend\dao\EtiquetaDAO;
use Backend\dto\Etiqueta;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
/** 
* Clase 'EtiquetaMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'Etiqueta'
* 
* Ejemplo de uso: 
* $EtiquetaMySqlDAO = new EtiquetaMySqlDAO();
*   
* s
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class EtiquetaMySqlDAO implements EtiquetaDAO{


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
     * Obtener el registro condicionado por la 
     * llave primaria que se pasa como parámetro
     *
     * @param String $id llave primaria
     *
     * @return Array resultado de la consulta
     *
     */
	public function load($id){
		$sql = 'SELECT * FROM etiqueta WHERE etiqueta_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($id);
		return $this->getRow($sqlQuery);
	}

    /**
     * Obtener todos los registros de la base datos
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryAll(){
		$sql = 'SELECT * FROM etiqueta';
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
     * @return Array resultado de la consulta
     *
     */
	public function queryAllOrderBy($orderColumn){
		$sql = 'SELECT * FROM etiqueta ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}

    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $etiqueta llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function delete($etiqueta_id){
		$sql = 'DELETE FROM etiqueta WHERE $etiqueta_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($etiqueta_id);
		return $this->executeUpdate($sqlQuery);
	}
	
    /**
     * Insertar un registro en la base de datos
     *
     * @param Object etiqueta etiqueta
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($etiqueta){

		$sql = 'INSERT INTO etiqueta (nombre,descripcion,fecha_crea, fecha_modif, usucrea_id,usumodif_id,estado,mandante,pais_id,tipo) VALUES (?, ?,?,?, ?, ?, ?,?,?,?)';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($etiqueta->nombre);
        $sqlQuery->set($etiqueta->descripcion);
		$sqlQuery->set($etiqueta->fechaCrea);
        $sqlQuery->set($etiqueta->fechaModif);
		$sqlQuery->setNumber($etiqueta->usucreaId);
		$sqlQuery->setNumber($etiqueta->usumodifId);
		$sqlQuery->set($etiqueta->estado);
		$sqlQuery->set($etiqueta->mandante);
		$sqlQuery->set($etiqueta->pais_id);
        $sqlQuery->set($etiqueta->tipo);

		$id = $this->executeInsert($sqlQuery);
        $etiqueta->etiquetaId = $id;

		return $id;
	}
	
    /**
     * Editar un registro en la base de datos
     *
     * @param Object etiqueta etiqueta
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($etiqueta){

		$sql = 'UPDATE etiqueta SET nombre = ?, usucrea_id= ?, usumodif_id= ?, estado = ?,  descripcion = ? WHERE etiqueta_id = ?';

		$sqlQuery = new SqlQuery($sql);

		$sqlQuery->set($etiqueta->nombre);




        if($etiqueta->usucreaId == '' || $etiqueta->usucreaId == null){
            $sqlQuery->set(0);
        }else{
            $sqlQuery->set($etiqueta->usucreaId);
        }


        if($etiqueta->usumodifId == ''  || $etiqueta->usumodifId == null){
            $sqlQuery->set(0);
        }else{
            $sqlQuery->set($etiqueta->usumodifId);
        }

        $sqlQuery->set($etiqueta->estado);

        $sqlQuery->set($etiqueta->descripcion);



        $sqlQuery->set($etiqueta->etiquetaId);

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
		$sql = 'DELETE FROM etiqueta';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}







    /**
     * Realiza una consulta personalizada de etiquetas en la base de datos.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Campo por el cual se ordenarán los resultados.
     * @param string $sord Orden de los resultados (ASC o DESC).
     * @param int $start Índice de inicio para la paginación.
     * @param int $limit Número de registros a obtener.
     * @param string $filters Filtros en formato JSON para la consulta.
     * @param bool $searchOn Indica si se deben aplicar los filtros de búsqueda.
     * @param bool $filtroNUll Indica si se deben incluir etiquetas con tipo 'N' o nulo.
     * @return string JSON con el conteo de registros y los datos obtenidos.
     */
    public function queryEtiquetasCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$filtroNUll)                     
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
        if ($filtroNUll == true){
            $filtroNull = " AND (etiqueta.tipo != 'N' OR etiqueta.tipo IS NULL)";
        }else{
            $filtroNull = "";
        }
        $sql = 'SELECT count(*) count  from etiqueta ' . $where .$filtroNull  ;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT  etiqueta.* from etiqueta ' . $where .$filtroNull. " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;


        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : '.json_encode($count). ', "data" : '.json_encode($result).'}';

        return  $json;
    }







    /**
     * Crear y devolver un objeto del tipo Etiqueta
     * con los valores de una consulta sql
     * 
     *  
     * @param Arreglo $row arreglo asociativo
     *
     * @return Object $Etiqueta Etiqueta
     *
     * @access protected
     *
     */
	protected function readRow($row){

		$etiqueta = new Etiqueta();

        $etiqueta->etiquetaId =$row['etiqueta_id'];
        $etiqueta->nombre =$row['nombre'];
        $etiqueta->descripcion =$row['descripcion'];
        $etiqueta->fechaCrea =$row['fecha_crea'];
        $etiqueta->fechaModif =$row['fecha_modif'];
        $etiqueta->usucreaId =$row['usucrea_id'];
        $etiqueta->usumodifId =$row['usumodif_id'];
        $etiqueta->estado =$row['estado'];

		return $etiqueta;
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
	protected function getList($sqlQuery){
		$tab = QueryExecutor::execute($this->transaction,$sqlQuery);
		$ret = array();
		for($i=0;$i<count($tab);$i++){
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
		$tab = QueryExecutor::execute($this->transaction,$sqlQuery);
		if(count($tab)==0){
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
	protected function execute($sqlQuery){
		return QueryExecutor::execute($this->transaction,$sqlQuery);
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
	protected function executeUpdate($sqlQuery){
		return QueryExecutor::executeUpdate($this->transaction,$sqlQuery);
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
	protected function querySingleResult($sqlQuery){
		return QueryExecutor::queryForString($this->transaction,$sqlQuery);
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
	protected function executeInsert($sqlQuery){
		return QueryExecutor::executeInsert($this->transaction,$sqlQuery);
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
     * Obtener registros de la tabla 'etiqueta' filtrados por el nombre.
     *
     * @param string $value Nombre a buscar en la tabla 'etiqueta'.
     * @return array Lista de registros que coinciden con el nombre.
     */
    public function queryByNombre($value)
    {
        $sql = 'SELECT * FROM etiqueta WHERE nombre = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Eliminar registros de la tabla 'etiqueta' filtrados por el nombre.
     *
     * @param string $value Nombre a buscar en la tabla 'etiqueta' para eliminar.
     * @return boolean Resultado de la operación de eliminación.
     */
    public function deleteByNombre($value)
    {
        $sql = 'DELETE FROM etiqueta WHERE nombre = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }
}

?>