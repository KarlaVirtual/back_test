<?php namespace Backend\dao;
/**
 * Interfaz para el modelo o tabla 'PuntoFijo'.
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface PuntoFijoDAO{

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
 	 * @param String $puntofijo_id llave primaria
 	 */
	public function delete($puntofijo_id);
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param String puntoFijo puntoFijo
 	 */
	public function insert($puntoFijo);
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Object puntoFijo puntoFijo
 	 */
	public function update($puntoFijo);	

	/**
	 * Eliminar todos los registros de la base de datos
	 */
	public function clean();





	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna NodoId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value NodoId requerido
 	 */	
	public function queryByNodoId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna PuntoventaId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PuntoventaId requerido
 	 */	
	public function queryByPuntoventaId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 */	
	public function queryByMandante($value);





	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna NodoId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value NodoId requerido
 	 */	
	public function deleteByNodoId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna PuntoventaId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PuntoventaId requerido
 	 */	
	public function deleteByPuntoventaId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 */	
	public function deleteByMandante($value);


}
?>