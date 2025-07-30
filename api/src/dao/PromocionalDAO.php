<?php namespace Backend\dao;
/**
 * Interfaz para el modelo o tabla 'Promocional'.
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface PromocionalDAO{

	/**
	 * Obtener el registro condicionado por la 
	 * llave primaria que se pasa como parámetro
	 *
	 * @param String $id llave primaria
	 */
	public function load($promocionalId, $mandante);

	/**
	 * Obtener todos los registros de la base datos
	 */
	public function queryAll();
	
	/**
	 * Obtener todos los registros
	 * ordenadas por el nombre de la columna 
	 * que se pasa como parámetro
	 *
	 * @param String $orderColumn nombre de la columna
	 */
	public function queryAllOrderBy($orderColumn);
	
	/**
 	 * Eliminar todos los registros condicionados
 	 * por la llave primaria
 	 *
 	 * @param String $promocionalId llave primaria promocionalId
 	 * @param String $mandante mandante
 	 */
	public function delete($promocionalId, $mandante);
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param String promocional promocional
 	 */
	public function insert($promocional);
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Object promocional promocional
 	 */
	public function update($promocional);	

	/**
	 * Eliminar todos los registros de la base de datos
	 */
	public function clean();





	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Descripcion sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Descripcion requerido
 	 */	
	public function queryByDescripcion($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Estado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Estado requerido
 	 */	
	public function queryByEstado($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna FechaModif sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaModif requerido
 	 */	
	public function queryByFechaModif($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna UsumodifId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsumodifId requerido
 	 */	
	public function queryByUsumodifId($value);





	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Descripcion sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Descripcion requerido
 	 */	
	public function deleteByDescripcion($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Estado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Estado requerido
 	 */	
	public function deleteByEstado($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna FechaModif sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaModif requerido
 	 */	
	public function deleteByFechaModif($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna UsumodifId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsumodifId requerido
 	 */	
	public function deleteByUsumodifId($value);


}
?>