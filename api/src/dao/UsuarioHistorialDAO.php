<?php namespace Backend\dao;
/**
 * Intreface DAO
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @date 2017-09-06 19:01
 * @category    No
 * @package     No
 * @version     1.0
 */
interface UsuarioHistorialDAO{

	/**
	 * Get Domain object by primry key
	 *
	 * @param String $id primary key
	 * @Return UsuarioHistorial 
	 */
	public function load($id);

	/**
	 * Get all records from table
	 */
	public function queryAll();
	
	/**
	 * Get all records from table ordered by field
	 * @Param $orderColumn column name
	 */
	public function queryAllOrderBy($orderColumn);
	
	/**
 	 * Delete record from table
 	 * @param cargo primary key
 	 */
	public function delete($cargo_id);
	
	/**
 	 * Insert record to table
 	 *
 	 * @param UsuarioHistorial cargo
 	 */
	public function insert($cargo);
	
	/**
 	 * Update record in table
 	 *
 	 * @param UsuarioHistorial cargo
 	 */
	public function update($cargo);	

	/**
	 * Delete all rows
	 */
	public function clean();

	/** 
	 * Solicita los UsuarioHistorial por el campo tipo
	 * @param string $value Tipo solicitado
	 * @return array Colección de UsuarioHistorial
	 */
	public function queryByTipo($value);

	/** 
	 * Solicita los UsuarioHistorial por el campo descripcion
	 * @param string $value Descripción solicitada
	 * @return array Colección de UsuarioHistorial
	 */
	public function queryByDescripcion($value);

	/** 
	 * Solicita los UsuarioHistorial por el campo estado
	 * @param string $value Estado solicitado
	 * @return array Colección de UsuarioHistorial
	 */
	public function queryByEstado($value);

	/** 
	 * Solicita los UsuarioHistorial por el campo mandante
	 * @param string $value Mandante solicitado
	 * @return array Colección de UsuarioHistorial
	 */
	public function queryByMandante($value);

	/** 
	 * Elimina los UsuarioHistorial por el campo tipo
	 * @param string $value Tipo solicitado
	 */
	public function deleteByTipo($value);

	/** 
	 * Elimina los UsuarioHistorial por el campo descripcion
	 * @param string $value Descripción solicitada
	 */
	public function deleteByDescripcion($value);

	/** 
	 * Elimina los UsuarioHistorial por el campo estado
	 * @param string $value Estado solicitado
	 */
	public function deleteByEstado($value);

	/** 
	 * Elimina los UsuarioHistorial por el campo mandante
	 * @param string $value Mandante solicitado
	 */
	public function deleteByMandante($value);


}
?>