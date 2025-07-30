<?php namespace Backend\mysql;
/** 
* Clase 'Promocional2MySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'Promocional2'
* 
* Ejemplo de uso: 
* $Promocional2MySqlDAO = new Promocional2MySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class Promocional2MySqlDAO implements Promocional2DAO{

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
		$sql = 'SELECT * FROM promocional2 WHERE mandante = ?';
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
		$sql = 'SELECT * FROM promocional2';
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
		$sql = 'SELECT * FROM promocional2 ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $mandante llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function delete($mandante){
		$sql = 'DELETE FROM promocional2 WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($mandante);
		return $this->executeUpdate($sqlQuery);
	}
	
    /**
     * Insertar un registro en la base de datos
     *
     * @param Object promocional2 promocional2
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($promocional2){
		$sql = 'INSERT INTO promocional2 (tiempo, valor_bono, caducidad, fecha_modif, usumodif_id) VALUES (?, ?, ?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($promocional2->tiempo);
		$sqlQuery->set($promocional2->valorBono);
		$sqlQuery->set($promocional2->caducidad);
		$sqlQuery->set($promocional2->fechaModif);
		$sqlQuery->set($promocional2->usumodifId);

		$id = $this->executeInsert($sqlQuery);	
		$promocional2->mandante = $id;
		return $id;
	}
	
    /**
     * Editar un registro en la base de datos
     *
     * @param Object promocional2 promocional2
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($promocional2){
		$sql = 'UPDATE promocional2 SET tiempo = ?, valor_bono = ?, caducidad = ?, fecha_modif = ?, usumodif_id = ? WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($promocional2->tiempo);
		$sqlQuery->set($promocional2->valorBono);
		$sqlQuery->set($promocional2->caducidad);
		$sqlQuery->set($promocional2->fechaModif);
		$sqlQuery->set($promocional2->usumodifId);

		$sqlQuery->set($promocional2->mandante);
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
		$sql = 'DELETE FROM promocional2';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}






    /**
     * Obtener todos los registros donde se encuentre que
     * la columna tiempo sea igual al valor pasado como parámetro
     *
     * @param String $value tiempo requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByTiempo($value){
		$sql = 'SELECT * FROM promocional2 WHERE tiempo = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna valor_bono sea igual al valor pasado como parámetro
     *
     * @param String $value valor_bono requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByValorBono($value){
		$sql = 'SELECT * FROM promocional2 WHERE valor_bono = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna caducidad sea igual al valor pasado como parámetro
     *
     * @param String $value caducidad requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByCaducidad($value){
		$sql = 'SELECT * FROM promocional2 WHERE caducidad = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna fecha_modif sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_modif requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByFechaModif($value){
		$sql = 'SELECT * FROM promocional2 WHERE fecha_modif = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna usumodif_id sea igual al valor pasado como parámetro
     *
     * @param String $value usumodif_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByUsumodifId($value){
		$sql = 'SELECT * FROM promocional2 WHERE usumodif_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}






    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna tiempo sea igual al valor pasado como parámetro
     *
     * @param String $value tiempo requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByTiempo($value){
		$sql = 'DELETE FROM promocional2 WHERE tiempo = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna valor_bono sea igual al valor pasado como parámetro
     *
     * @param String $value valor_bono requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByValorBono($value){
		$sql = 'DELETE FROM promocional2 WHERE valor_bono = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna caducidad sea igual al valor pasado como parámetro
     *
     * @param String $value caducidad requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByCaducidad($value){
		$sql = 'DELETE FROM promocional2 WHERE caducidad = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna fecha_modif sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_modif requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByFechaModif($value){
		$sql = 'DELETE FROM promocional2 WHERE fecha_modif = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna usumodif_id sea igual al valor pasado como parámetro
     *
     * @param String $value usumodif_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByUsumodifId($value){
		$sql = 'DELETE FROM promocional2 WHERE usumodif_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}





	
    /**
     * Crear y devolver un objeto del tipo Promocional2
     * con los valores de una consulta sql
     * 
     *	
     * @param Arreglo $row arreglo asociativo
     *
     * @return Object $Promocional2 Promocional2
     *
     * @access protected
     *
     */
	protected function readRow($row){
		$promocional2 = new Promocional2();
		
		$promocional2->mandante = $row['mandante'];
		$promocional2->tiempo = $row['tiempo'];
		$promocional2->valorBono = $row['valor_bono'];
		$promocional2->caducidad = $row['caducidad'];
		$promocional2->fechaModif = $row['fecha_modif'];
		$promocional2->usumodifId = $row['usumodif_id'];

		return $promocional2;
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