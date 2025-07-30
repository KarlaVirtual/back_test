<?php namespace Backend\mysql;
/** 
* Clase 'Promocional1MySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'Promocional1'
* 
* Ejemplo de uso: 
* $Promocional1MySqlDAO = new Promocional1MySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class Promocional1MySqlDAO implements Promocional1DAO{


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
		$sql = 'SELECT * FROM promocional1 WHERE mandante = ?';
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
		$sql = 'SELECT * FROM promocional1';
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
		$sql = 'SELECT * FROM promocional1 ORDER BY '.$orderColumn;
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
		$sql = 'DELETE FROM promocional1 WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($mandante);
		return $this->executeUpdate($sqlQuery);
	}
	
    /**
     * Insertar un registro en la base de datos
     *
     * @param Object promocional1 promocional1
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($promocional1){
		$sql = 'INSERT INTO promocional1 (porcen_bono, tope_bono, provision_bono, vlr_acumulado, fecha_modif, usumodif_id) VALUES (?, ?, ?, ?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($promocional1->porcenBono);
		$sqlQuery->set($promocional1->topeBono);
		$sqlQuery->set($promocional1->provisionBono);
		$sqlQuery->set($promocional1->vlrAcumulado);
		$sqlQuery->set($promocional1->fechaModif);
		$sqlQuery->set($promocional1->usumodifId);

		$id = $this->executeInsert($sqlQuery);	
		$promocional1->mandante = $id;
		return $id;
	}

    /**
     * Editar un registro en la base de datos
     *
     * @param Object promocional1 promocional1
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($promocional1){
		$sql = 'UPDATE promocional1 SET porcen_bono = ?, tope_bono = ?, provision_bono = ?, vlr_acumulado = ?, fecha_modif = ?, usumodif_id = ? WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($promocional1->porcenBono);
		$sqlQuery->set($promocional1->topeBono);
		$sqlQuery->set($promocional1->provisionBono);
		$sqlQuery->set($promocional1->vlrAcumulado);
		$sqlQuery->set($promocional1->fechaModif);
		$sqlQuery->set($promocional1->usumodifId);

		$sqlQuery->set($promocional1->mandante);
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
		$sql = 'DELETE FROM promocional1';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}







    /**
     * Obtener todos los registros donde se encuentre que
     * la columna porcen_bono sea igual al valor pasado como parámetro
     *
     * @param String $value porcen_bono requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByPorcenBono($value){
		$sql = 'SELECT * FROM promocional1 WHERE porcen_bono = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna tope_bono sea igual al valor pasado como parámetro
     *
     * @param String $value tope_bono requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByTopeBono($value){
		$sql = 'SELECT * FROM promocional1 WHERE tope_bono = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna provision_bono sea igual al valor pasado como parámetro
     *
     * @param String $value provision_bono requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByProvisionBono($value){
		$sql = 'SELECT * FROM promocional1 WHERE provision_bono = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna vlr_acumulado sea igual al valor pasado como parámetro
     *
     * @param String $value vlr_acumulado requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByVlrAcumulado($value){
		$sql = 'SELECT * FROM promocional1 WHERE vlr_acumulado = ?';
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
		$sql = 'SELECT * FROM promocional1 WHERE fecha_modif = ?';
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
		$sql = 'SELECT * FROM promocional1 WHERE usumodif_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}






    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna porcen_bono sea igual al valor pasado como parámetro
     *
     * @param String $value porcen_bono requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByPorcenBono($value){
		$sql = 'DELETE FROM promocional1 WHERE porcen_bono = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna tope_bono sea igual al valor pasado como parámetro
     *
     * @param String $value tope_bono requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByTopeBono($value){
		$sql = 'DELETE FROM promocional1 WHERE tope_bono = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna provision_bono sea igual al valor pasado como parámetro
     *
     * @param String $value provision_bono requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByProvisionBono($value){
		$sql = 'DELETE FROM promocional1 WHERE provision_bono = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna vlr_acumulado sea igual al valor pasado como parámetro
     *
     * @param String $value vlr_acumulado requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByVlrAcumulado($value){
		$sql = 'DELETE FROM promocional1 WHERE vlr_acumulado = ?';
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
		$sql = 'DELETE FROM promocional1 WHERE fecha_modif = ?';
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
		$sql = 'DELETE FROM promocional1 WHERE usumodif_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}






    /**
     * Crear y devolver un objeto del tipo Promocional1
     * con los valores de una consulta sql
     * 
     *	
     * @param Arreglo $row arreglo asociativo
     *
     * @return Object $Promocional1 Promocional1
     *
     * @access protected
     *
     */
	protected function readRow($row){
		$promocional1 = new Promocional1();
		
		$promocional1->porcenBono = $row['porcen_bono'];
		$promocional1->topeBono = $row['tope_bono'];
		$promocional1->provisionBono = $row['provision_bono'];
		$promocional1->vlrAcumulado = $row['vlr_acumulado'];
		$promocional1->fechaModif = $row['fecha_modif'];
		$promocional1->usumodifId = $row['usumodif_id'];
		$promocional1->mandante = $row['mandante'];

		return $promocional1;
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