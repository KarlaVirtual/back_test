<?php namespace Backend\dao;
/**
 * Interfaz para el modelo o tabla 'ProveedorMandante'.
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface ProveedorMandanteDAO{

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
 	 * @param String $prodmandante_id llave primaria
 	 */
	public function delete($prodmandante_id);
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param String productoMandante productoMandante
 	 */
	public function insert($productoMandante);
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Object productoMandante productoMandante
 	 */
	public function update($productoMandante);	

	/**
	 * Eliminar todos los registros de la base de datos
	 */
	public function clean();





	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna ProveedorId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ProveedorId requerido
 	 */	
	public function queryByProveedorId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 */	
	public function queryByMandante($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Estado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Estado requerido
 	 */	
	public function queryByEstado($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Verifica sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Verifica requerido
 	 */	
	public function queryByVerifica($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna FiltroPais sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FiltroPais requerido
 	 */	
	public function queryByFiltroPais($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna FechaCrea sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaCrea requerido
 	 */	
	public function queryByFechaCrea($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna FechaModif sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaModif requerido
 	 */	
	public function queryByFechaModif($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna UsucreaId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsucreaId requerido
 	 */	
	public function queryByUsucreaId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna UsumodifId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsumodifId requerido
 	 */	
	public function queryByUsumodifId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Max sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Max requerido
 	 */	
	public function queryByMax($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Min sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Min requerido
 	 */	
	public function queryByMin($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Detalle sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Detalle requerido
 	 */	
	public function queryByDetalle($value);





	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna ProveedorId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ProveedorId requerido
 	 */	
	public function deleteByProveedorId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 */	
	public function deleteByMandante($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Estado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Estado requerido
 	 */	
	public function deleteByEstado($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Verifica sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Verifica requerido
 	 */	
	public function deleteByVerifica($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna FiltroPais sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FiltroPais requerido
 	 */	
	public function deleteByFiltroPais($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna FechaCrea sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaCrea requerido
 	 */	
	public function deleteByFechaCrea($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna FechaModif sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaModif requerido
 	 */	
	public function deleteByFechaModif($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna UsucreaId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsucreaId requerido
 	 */	
	public function deleteByUsucreaId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna UsumodifId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsumodifId requerido
 	 */	
	public function deleteByUsumodifId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Max sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Max requerido
 	 */	
	public function deleteByMax($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Min sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Min requerido
 	 */	
	public function deleteByMin($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Detalle sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Detalle requerido
 	 */	
	public function deleteByDetalle($value);


}
?>