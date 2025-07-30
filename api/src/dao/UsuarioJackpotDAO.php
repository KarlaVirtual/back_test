<?php namespace Backend\dao;
/**
 * Intreface DAO
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @date: 2017-09-06 19:01
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface UsuarioJackpotDAO{

	/**
	 * Get Domain object by primry key
	 *
	 * @param String $id primary key
	 * @Return LealtadHistorial 
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
 	 * @param LealtadHistorial cargo
 	 */
	public function insert($cargo);
	
	/**
 	 * Update record in table
 	 *
 	 * @param LealtadHistorial cargo
 	 */
	public function update($cargo);	

	/**
	 * Delete all rows
	 */
	public function clean();

	/** 
	 * Solicita los UsuarioJackpot por el campo tipo
	 * @param string $value Tipo solicitado
	 * @return array Colección de UsuarioJackpot
	 */
	public function queryByTipo($value);

	/** 
	 * Solicita los UsuarioJackpot por el campo descripcion
	 * @param string $value Descripción solicitada
	 * @return array Colección de UsuarioJackpot
	 */
	public function queryByDescripcion($value);

	/** 
	 * Solicita los UsuarioJackpot por el campo estado
	 * @param string $value Estado solicitado
	 * @return array Colección de UsuarioJackpot
	 */
	public function queryByEstado($value);

	/** 
	 * Solicita los UsuarioJackpot por el campo mandante
	 * @param string $value Mandante solicitado
	 * @return array Colección de UsuarioJackpot
	 */
	public function queryByMandante($value);

	/** Elimina los items por el tipo indicado
	 * @param string $value Tipo solicitado
	 */
	public function deleteByTipo($value);

	/** Elimina los items por la descripción indicada
	 * @param string $value Descripción solicitada
	 */
	public function deleteByDescripcion($value);

	/** Elimina los items por el estado indicado
	 * @param string $value Estado solicitado
	 */
	public function deleteByEstado($value);

	/** Elimina los items por el mandante indicado
	 * @param string $value Mandante solicitado
	 */
	public function deleteByMandante($value);


}
?>
