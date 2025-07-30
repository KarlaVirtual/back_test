<?php namespace Backend\dao;
/**
 * Interfaz para el modelo o tabla 'PeriodicidadLiquida'.
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface PeriodicidadLiquidaDAO{


	/**
	 * Obtener el registro condicionado por la 
	 * llave primaria que se pasa como parámetro
	 *
	 * @param String $id llave primaria
	 */
	public function load($id);

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
 	 * @param String $periodicidad_id llave primaria
 	 */
	public function delete($periodicidad_id);
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param String periodicidadLiquida periodicidadLiquida
 	 */
	public function insert($periodicidadLiquida);
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Object periodicidadLiquida periodicidadLiquida
 	 */
	public function update($periodicidadLiquida);	

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
 	 * la columna Dias sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Dias requerido
 	 */	
	public function queryByDias($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Estado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Estado requerido
 	 */	
	public function queryByEstado($value);





	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Descripcion sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Descripcion requerido
 	 */	
	public function deleteByDescripcion($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Dias sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Dias requerido
 	 */	
	public function deleteByDias($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Estado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Estado requerido
 	 */	
	public function deleteByEstado($value);


}
?>