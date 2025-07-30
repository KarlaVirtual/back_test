<?php namespace Backend\mysql;
/** 
* Clase 'CredencialesProveedorMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'CredencialesProveedor'
* 
* Ejemplo de uso: 
* $CredencialesProveedorMySqlDAO = new CredencialesProveedorMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class CredencialesProveedorMySqlDAO implements CredencialesProveedorDAO{

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
		$sql = 'SELECT * FROM credenciales_proveedor WHERE credenproveedor_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($id);
		return $this->getRow($sqlQuery);
	}

    /**
     * Obtener todos los registros de la base datos
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryAll(){
		$sql = 'SELECT * FROM credenciales_proveedor';
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
		$sql = 'SELECT * FROM credenciales_proveedor ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $contactocom_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function delete($credenproveedor_id){
		$sql = 'DELETE FROM credenciales_proveedor WHERE credenproveedor_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($credenproveedor_id);
		return $this->executeUpdate($sqlQuery);
	}
	
    /**
     * Insertar un registro en la base de datos
     *
     * @param Object contactoComercial contactoComercial
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($credencialesProveedor){
		$sql = 'INSERT INTO credenciales_proveedor (proveedor_id, c_key, c_value, fecha_crea, fecha_modif, usucrea_id, usumodif_id) VALUES (?, ?, ?, ?, ?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->setNumber($credencialesProveedor->proveedorId);
		$sqlQuery->set($credencialesProveedor->cKey);
		$sqlQuery->set($credencialesProveedor->cValue);
		$sqlQuery->set($credencialesProveedor->fechaCrea);
		$sqlQuery->set($credencialesProveedor->fechaModif);
		$sqlQuery->setNumber($credencialesProveedor->usucreaId);
		$sqlQuery->setNumber($credencialesProveedor->usumodifId);

		$id = $this->executeInsert($sqlQuery);	
		$credencialesProveedor->credenproveedorId = $id;
		return $id;
	}
	
    /**
     * Editar un registro en la base de datos
     *
     * @param Object contactoComercial contactoComercial
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($credencialesProveedor){
		$sql = 'UPDATE credenciales_proveedor SET proveedor_id = ?, c_key = ?, c_value = ?, fecha_crea = ?, fecha_modif = ?, usucrea_id = ?, usumodif_id = ? WHERE credenproveedor_id = ?';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->setNumber($credencialesProveedor->proveedorId);
		$sqlQuery->set($credencialesProveedor->cKey);
		$sqlQuery->set($credencialesProveedor->cValue);
		$sqlQuery->set($credencialesProveedor->fechaCrea);
		$sqlQuery->set($credencialesProveedor->fechaModif);
		$sqlQuery->setNumber($credencialesProveedor->usucreaId);
		$sqlQuery->setNumber($credencialesProveedor->usumodifId);

		$sqlQuery->setNumber($credencialesProveedor->credenproveedorId);
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
		$sql = 'DELETE FROM credenciales_proveedor';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}








    /**
     * Obtener todos los registros donde se encuentre que
     * la columna proveedor_id sea igual al valor pasado como parámetro
     *
     * @param String $value proveedor_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByProveedorId($value){
		$sql = 'SELECT * FROM credenciales_proveedor WHERE proveedor_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna c_key sea igual al valor pasado como parámetro
     *
     * @param String $value c_key requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByCKey($value){
		$sql = 'SELECT * FROM credenciales_proveedor WHERE c_key = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna c_value sea igual al valor pasado como parámetro
     *
     * @param String $value c_value requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByCValue($value){
		$sql = 'SELECT * FROM credenciales_proveedor WHERE c_value = ?';
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
     * @return Array resultado de la consulta
     *
     */
	public function queryByFechaCrea($value){
		$sql = 'SELECT * FROM credenciales_proveedor WHERE fecha_crea = ?';
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
		$sql = 'SELECT * FROM credenciales_proveedor WHERE fecha_modif = ?';
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
     * @return Array resultado de la consulta
     *
     */
	public function queryByUsucreaId($value){
		$sql = 'SELECT * FROM credenciales_proveedor WHERE usucrea_id = ?';
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
     * @return Array resultado de la consulta
     *
     */
	public function queryByUsumodifId($value){
		$sql = 'SELECT * FROM credenciales_proveedor WHERE usumodif_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}









    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna proveedor_id sea igual al valor pasado como parámetro
     *
     * @param String $value proveedor_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByProveedorId($value){
		$sql = 'DELETE FROM credenciales_proveedor WHERE proveedor_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna c_key sea igual al valor pasado como parámetro
     *
     * @param String $value c_key requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByCKey($value){
		$sql = 'DELETE FROM credenciales_proveedor WHERE c_key = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna c_value sea igual al valor pasado como parámetro
     *
     * @param String $value c_value requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByCValue($value){
		$sql = 'DELETE FROM credenciales_proveedor WHERE c_value = ?';
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
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByFechaCrea($value){
		$sql = 'DELETE FROM credenciales_proveedor WHERE fecha_crea = ?';
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
		$sql = 'DELETE FROM credenciales_proveedor WHERE fecha_modif = ?';
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
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByUsucreaId($value){
		$sql = 'DELETE FROM credenciales_proveedor WHERE usucrea_id = ?';
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
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByUsumodifId($value){
		$sql = 'DELETE FROM credenciales_proveedor WHERE usumodif_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}








    /**
     * Crear y devolver un objeto del tipo Competencia
     * con los valores de una consulta sql
     * 
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Object $CredencialesProveedor CredencialesProveedor
     *
     * @access protected
     *
     */
	protected function readRow($row){
		$credencialesProveedor = new CredencialesProveedor();
		
		$credencialesProveedor->credenproveedorId = $row['credenproveedor_id'];
		$credencialesProveedor->proveedorId = $row['proveedor_id'];
		$credencialesProveedor->cKey = $row['c_key'];
		$credencialesProveedor->cValue = $row['c_value'];
		$credencialesProveedor->fechaCrea = $row['fecha_crea'];
		$credencialesProveedor->fechaModif = $row['fecha_modif'];
		$credencialesProveedor->usucreaId = $row['usucrea_id'];
		$credencialesProveedor->usumodifId = $row['usumodif_id'];

		return $credencialesProveedor;
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