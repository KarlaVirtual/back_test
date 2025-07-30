<?php namespace Backend\mysql;
/** 
* Clase 'ItainmentDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'Itainment'
* 
* Ejemplo de uso: 
* $ItainmentDAO = new ItainmentDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class ItainmentMySqlDAO implements ItainmentDAO{

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
		$sql = 'SELECT * FROM itainment WHERE id = ?';
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
		$sql = 'SELECT * FROM itainment';
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
		$sql = 'SELECT * FROM itainment ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function delete($id){
		$sql = 'DELETE FROM itainment WHERE id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($id);
		return $this->executeUpdate($sqlQuery);
	}
	
    /**
     * Insertar un registro en la base de datos
     *
     * @param Object itainment itainment
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($itainment){
		$sql = 'INSERT INTO itainment (texto) VALUES (?)';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($itainment->texto);

		$id = $this->executeInsert($sqlQuery);	
		$itainment->id = $id;
		return $id;
	}
	
    /**
     * Editar un registro en la base de datos
     *
     * @param Object itainment itainment
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($itainment){
		$sql = 'UPDATE itainment SET texto = ? WHERE id = ?';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($itainment->texto);

		$sqlQuery->set($itainment->id);
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
		$sql = 'DELETE FROM itainment';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}




    /**
     * Obtener todos los registros donde se encuentre que
     * la columna texto sea igual al valor pasado como parámetro
     *
     * @param String $value texto requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByTexto($value){
		$sql = 'SELECT * FROM itainment WHERE texto = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna texto sea igual al valor pasado como parámetro
     *
     * @param String $value texto requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByTexto($value){
		$sql = 'DELETE FROM itainment WHERE texto = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}


	

	
    /**
     * Crear y devolver un objeto del tipo Itainment
     * con los valores de una consulta sql
     * 
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Object $Itainment Itainment
     *
     * @access protected
     *
     */
	protected function readRow($row){
		$itainment = new Itainment();
		
		$itainment->id = $row['id'];
		$itainment->texto = $row['texto'];

		return $itainment;
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
		for($i=0;$i<oldCount($tab);$i++){
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
		if(oldCount($tab)==0){
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
}
?>