<?php namespace Backend\dao;
/**
 * Interfaz para el modelo o tabla 'PerfilSubmenu'.
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface PerfilSubmenuDAO{

	/**
	 * Obtener el registro condicionado por las
	 * llaves primarias que se pasan como parámetro
	 *
	 * @param String $perfilId llave primaria perfilId
     * @param String $submenuId llave primaria submenuId
	 */
	public function load($perfilId, $submenuId);

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
 	 * por las llave primarias pasadas como parámetro
 	 *
 	 * @param String $perfilId llave primaria perfilId
 	 * @param String $submenuId llave primariasubmenuId
 	 */
	public function delete($perfilId, $submenuId);
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param String perfilSubmenu perfilSubmenu
 	 */
	public function insert($perfilSubmenu);
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Object perfilSubmenu perfilSubmenu
 	 */
	public function update($perfilSubmenu);	

	/**
	 * Eliminar todos los registros de la base de datos
	 */
	public function clean();





	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Adicionar sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Adicionar requerido
 	 */	
	public function queryByAdicionar($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Editar sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Editar requerido
 	 */	
	public function queryByEditar($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Eliminar sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Eliminar requerido
 	 */	
	public function queryByEliminar($value);





	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Adicionar sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Adicionar requerido
 	 */	
	public function deleteByAdicionar($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Editar sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Editar requerido
 	 */	
	public function deleteByEditar($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Eliminar sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Eliminar requerido
 	 */	
	public function deleteByEliminar($value);


}
?>