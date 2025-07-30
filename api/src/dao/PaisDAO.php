<?php namespace Backend\dao;
/**
 * Interfaz para el modelo o tabla 'Pais'.
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface PaisDAO{

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
 	 * @param String $pais_id llave primaria
 	 */
	public function delete($pais_id);
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param String pai pai
 	 */
	public function insert($pai);
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Object pai pai
 	 */
	public function update($pai);	

	/**
	 * Eliminar todos los registros de la base de datos
	 */
	public function clean();






	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Iso sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Iso requerido
 	 */	
	public function queryByIso($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna PaisNom sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PaisNom requerido
 	 */	
	public function queryByPaisNom($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Moneda sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Moneda requerido
 	 */	
	public function queryByMoneda($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Estado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Estado requerido
 	 */	
	public function queryByEstado($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Utc sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Utc requerido
 	 */	
	public function queryByUtc($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Idioma sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Idioma requerido
 	 */	
	public function queryByIdioma($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna ReqCheque sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ReqCheque requerido
 	 */	
	public function queryByReqCheque($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna ReqDoc sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ReqDoc requerido
 	 */	
	public function queryByReqDoc($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna CodigoPath sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value CodigoPath requerido
 	 */	
	public function queryByCodigoPath($value);






	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Iso sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Iso requerido
 	 */	
	public function deleteByIso($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna PaisNom sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PaisNom requerido
 	 */	
	public function deleteByPaisNom($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Moneda sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Moneda requerido
 	 */	
	public function deleteByMoneda($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Estado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Estado requerido
 	 */	
	public function deleteByEstado($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Utc sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Utc requerido
 	 */	
	public function deleteByUtc($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Idioma sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Idioma requerido
 	 */	
	public function deleteByIdioma($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna ReqCheque sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ReqCheque requerido
 	 */	
	public function deleteByReqCheque($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna ReqDoc sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ReqDoc requerido
 	 */	
	public function deleteByReqDoc($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna CodigoPath sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value CodigoPath requerido
 	 */	
	public function deleteByCodigoPath($value);


}
?>