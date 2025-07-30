<?php namespace Backend\dao;
/**
 * Interfaz para el modelo o tabla 'UsuarioRecargaEliminado'.
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface UsuarioRecargaEliminadoDAO{

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
 	 * @param String $recargaelimina_id llave primaria
 	 */
	public function delete($recargaelimina_id);
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param String usuarioRecargaEliminado usuarioRecargaEliminado
 	 */
	public function insert($usuarioRecargaEliminado);
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Object usuarioRecargaEliminado usuarioRecargaEliminado
 	 */
	public function update($usuarioRecargaEliminado);	

	/**
	 * Eliminar todos los registros de la base de datos
	 */
	public function clean();





	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna UsuarioId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsuarioId requerido
 	 */	
	public function queryByUsuarioId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna FechaCrea sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaCrea requerido
 	 */	
	public function queryByFechaCrea($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna PuntoventaId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PuntoventaId requerido
 	 */	
	public function queryByPuntoventaId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Valor sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Valor requerido
 	 */	
	public function queryByValor($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna FechaElimina sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaElimina requerido
 	 */	
	public function queryByFechaElimina($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna UsueliminaId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsueliminaId requerido
 	 */	
	public function queryByUsueliminaId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna RecargaId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value RecargaId requerido
 	 */	
	public function queryByRecargaId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 */	
	public function queryByMandante($value);





	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna UsuarioId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsuarioId requerido
 	 */	
	public function deleteByUsuarioId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna FechaCrea sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaCrea requerido
 	 */	
	public function deleteByFechaCrea($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna PuntoventaId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PuntoventaId requerido
 	 */	
	public function deleteByPuntoventaId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Valor sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Valor requerido
 	 */	
	public function deleteByValor($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna FechaEliminaa sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaEliminaa requerido
 	 */	
	public function deleteByFechaElimina($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna UsueliminaId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsueliminaId requerido
 	 */	
	public function deleteByUsueliminaId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna RecargaId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value RecargaId requerido
 	 */	
	public function deleteByRecargaId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 */	
	public function deleteByMandante($value);


}
?>