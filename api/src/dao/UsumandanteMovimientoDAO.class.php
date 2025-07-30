<?php namespace Backend\dao;
/**
 * Interfaz para el modelo o tabla 'UsumandanteMovimiento'.
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface UsumandanteMovimientoDAO{

	/**
	 * Obtener el registro condicionado por la 
	 * llave primaria que se pasa como parámetro
	 *
	 * @param String $id llave primaria
	 */	public function load($id);

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
 	 * @param String $usumandmov_id llave primaria
 	 */
	public function delete($usumandmov_id);
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param String usumandanteMovimiento usumandanteMovimiento
 	 */
	public function insert($usumandanteMovimiento);
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Object usumandanteMovimiento usumandanteMovimiento
 	 */
	public function update($usumandanteMovimiento);	

	/**
	 * Eliminar todos los registros de la base de datos
	 */
	public function clean();





	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna UsumandanteId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsumandanteId requerido
 	 */	
	public function queryByUsumandanteId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna ProductoTipoProductoTipo sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ProductoTipoProductoTipo requerido
 	 */	
	public function queryByProductoTipo($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna MovimientoMovimiento sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value MovimientoMovimiento requerido
 	 */	
	public function queryByMovimiento($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Request sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Request requerido
 	 */	
	public function queryByRequest($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna ResponseResponse sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ResponseResponse requerido
 	 */	
	public function queryByResponse($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna FechaCreaFechaCrea sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaCreaFechaCrea requerido
 	 */	
	public function queryByFechaCrea($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna UsucreaId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsucreaId requerido
 	 */	
	public function queryByUsucreaId($value);

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
 	 * la columna UsumandanteId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsumandanteId requerido
 	 */	
	public function deleteByUsumandanteId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna ProductoTipo sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ProductoTipo requerido
 	 */	
	public function deleteByProductoTipo($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Movimiento sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Movimiento requerido
 	 */	
	public function deleteByMovimiento($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Request sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Request requerido
 	 */	
	public function deleteByRequest($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Response sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Response requerido
 	 */	
	public function deleteByResponse($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna FechaCrea sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaCrea requerido
 	 */	
	public function deleteByFechaCrea($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna UsucreaId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsucreaId requerido
 	 */	
	public function deleteByUsucreaId($value);

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