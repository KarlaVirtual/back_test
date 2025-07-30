<?php namespace Backend\dao;
/**
 * Intreface DAO
 *
 * @author: http://phpdao.com
 * @date: 2018-02-11 17:49
 * @category    No
 * @package     No
 * @version     1.0
 */
interface UsuarioTokenInternoDAO{

	/**
	 * Get Domain object by primry key
	 *
	 * @param String $id primary key
	 * @Return UsuarioTokenInterno 
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
 	 * @param intDeporte primary key
 	 */
	public function delete($deporte_id);
	
	/**
 	 * Insert record to table
 	 *
 	 * @param UsuarioTokenInterno intDeporte
 	 */
	public function insert($intDeporte);
	
	/**
 	 * Update record in table
 	 *
 	 * @param UsuarioTokenInterno intDeporte
 	 */
	public function update($intDeporte);	

	/**
	 * Delete all rows
	 */
	public function clean();

	/** Solicita los UsuarioTokenInterno que poseen el UsucreaId indicado
	 * @param int $value UsucreaId
	 * @return array Colección de usuarioTokenInterno
	 */
	public function queryByUsucreaId($value);

	/** Solicita los UsuarioTokenInterno que poseen el UsumodifId indicado
	 * @param int $value UsumodifId
	 * @return array Colección de usuarioTokenInterno
	 */
	public function queryByUsumodifId($value);

	/** Solicita los UsuarioTokenInterno que poseen la FechaCrea indicada
	 * @param string $value FechaCrea
	 * @return array Colección de usuarioTokenInterno
	 */
	public function queryByFechaCrea($value);

	/** Solicita los UsuarioTokenInterno que poseen la FechaModif indicada
	 * @param string $value FechaModif
	 * @return array Colección de usuarioTokenInterno
	 */
	public function queryByFechaModif($value);

	/** Elimina los UsuarioTokenInterno que poseen el UsucreaId indicado
	 * @param int $value UsucreaId
	 */
	public function deleteByUsucreaId($value);

	/** Elimina los UsuarioTokenInterno que poseen el UsumodifId indicado
	 * @param int $value UsumodifId
	 */
	public function deleteByUsumodifId($value);

	/** Elimina los UsuarioTokenInterno que poseen la FechaCrea indicada
	 * @param string $value FechaCrea
	 */
	public function deleteByFechaCrea($value);

	/** Elimina los UsuarioTokenInterno que poseen la FechaModif indicada
	 * @param string $value FechaModif
	 */
	public function deleteByFechaModif($value);


}
?>