<?php namespace Backend\dao;
/**
 * Interfaz para el modelo o tabla 'BonoInterno'.
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface BonoInternoDAO{

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
 	 * @param String $bono_id llave primaria
 	 */
	public function delete($bono_id);

	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param Object BonoInterno BonoInterno
 	 */	
	public function insert($BonoInterno);
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Object BonoInterno BonoInterno
 	 */
	public function update($BonoInterno);	

	/**
	 * Eliminar todos los registros de la base de datos
	 */
	public function clean();

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 */	
	public function queryByMandante($value);


}
?>