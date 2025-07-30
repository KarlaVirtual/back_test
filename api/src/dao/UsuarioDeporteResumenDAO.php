<?php namespace Backend\dao;
/**
 * Intreface DAO
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @date: 2017-09-06 19:02
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface UsuarioDeporteResumenDAO{

	/**
	 * Get Domain object by primry key
	 *
	 * @param String $id primary key
	 * @Return UsuarioDeporteResumen 
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
 	 * @param UsuarioDeporteResumen producto
 	 */
	public function insert($usuariocomision);
	
	/**
 	 * Update record in table
 	 *
 	 * @param UsuarioDeporteResumen producto
 	 */
	public function update($producto);	

	/**
	 * Delete all rows
	 */
	public function clean();


	/** Solicita los UsuarioDeporteResumen que poseen el FechaCrea indicado
	 * @param string $value FechaCrea
	 * @return Colección de objetos UsuarioDeporteResumen
	 */
	public function queryByFechaCrea($value);

	/** Solicita los UsuarioDeporteResumen que poseen el UsucreaId indicado
	 * @param string $value UsucreaId
	 * @return Colección de objetos UsuarioDeporteResumen
	 */
	public function queryByUsucreaId($value);

	/** Solicita los UsuarioDeporteResumen que poseen el UsumodifId indicado
	 * @param string $value UsumodifId
	 * @return Colección de objetos UsuarioDeporteResumen
	 */
	public function queryByUsumodifId($value);

	/** Elimina los UsuarioDeporteResumen que poseen el FechaCrea indicado
	 * @param string $value FechaCrea
	 */
	public function deleteByFechaCrea($value);

	/** Elimina los UsuarioDeporteResumen que poseen el UsucreaId indicado
	 * @param string $value UsucreaId
	 */
	public function deleteByUsucreaId($value);

	/** Elimina los UsuarioDeporteResumen que poseen el UsumodifId indicado
	 * @param string $value UsumodifId
	 */
	public function deleteByUsumodifId($value);


}
?>