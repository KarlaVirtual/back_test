<?php namespace Backend\dao;
/**
 * Intreface DAO
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 * @date: 2017-09-06 19:02
 */
interface UsuarioRetiroResumenDAO{

	/**
	 * Get Domain object by primry key
	 *
	 * @param String $id primary key
	 * @Return UsuarioRetiroResumen 
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
 	 * @param producto primary key
 	 */
	public function delete($usumarketing_id);
	
	/**
 	 * Insert record to table
 	 *
 	 * @param UsuarioRetiroResumen producto
 	 */
	public function insert($usuariocomision);
	
	/**
 	 * Update record in table
 	 *
 	 * @param UsuarioRetiroResumen producto
 	 */
	public function update($producto);	

	/**
	 * Delete all rows
	 */
	public function clean();

	/** Solicitar usuariosRetiroResumen condicionado por la fechaCea 
	 * @param string $value fechaCrea
	 * @return array $usuariosRetiroResumen
	 */
	public function queryByFechaCrea($value);

	/** Solicitar usuariosRetiroResumen condicionado por el usuCreaId 
	 * @param string $value usuCreaId
	 * @return array $usuariosRetiroResumen
	 */
	public function queryByUsucreaId($value);

	/** Solicitar usuariosRetiroResumen condicionado por el usuModifId 
	 * @param string $value usuModifId
	 * @return array $usuariosRetiroResumen
	 */
	public function queryByUsumodifId($value);

	/** Eliminar usuariosRetiroResumen condicionado por la fechaCrea 
	 * @param string $value fechaCrea
	 */
	public function deleteByFechaCrea($value);

	/** Eliminar usuariosRetiroResumen condicionado por el usuCreaId 
	 * @param string $value usuCreaId
	 */
	public function deleteByUsucreaId($value);

	/** Eliminar usuariosRetiroResumen condicionado por el usuModifId 
	 * @param string $value usuModifId
	 */
	public function deleteByUsumodifId($value);


}
?>