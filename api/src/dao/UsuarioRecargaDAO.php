<?php namespace Backend\dao;
/**
 * Interfaz para el modelo o tabla 'UsuarioRecarga'.
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface UsuarioRecargaDAO{

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
 	 * @param String $recarga_id llave primaria
 	 */
	public function delete($recarga_id);
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param String usuarioRecarga usuarioRecarga
 	 */
	public function insert($usuarioRecarga);
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Object usuarioRecarga usuarioRecarga
 	 */
	public function update($usuarioRecarga);	

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
 	 * la columna PorcenRegaloRecarga sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PorcenRegaloRecarga requerido
 	 */	
	public function queryByPorcenRegaloRecarga($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 */	
	public function queryByMandante($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Pedido sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Pedido requerido
 	 */	
	public function queryByPedido($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna DirIp sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value DirIp requerido
 	 */	
	public function queryByDirIp($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna PromocionalId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PromocionalId requerido
 	 */	
	public function queryByPromocionalId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna ValorPromocional sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ValorPromocional requerido
 	 */	
	public function queryByValorPromocional($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Host sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Host requerido
 	 */	
	public function queryByHost($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna PorcenIva sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PorcenIva requerido
 	 */	
	public function queryByPorcenIva($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna MediopagoId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value MediopagoId requerido
 	 */	
	public function queryByMediopagoId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna ValorIva sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ValorIva requerido
 	 */	
	public function queryByValorIva($value);





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
 	 * la columna PorcenRegaloRecarga sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PorcenRegaloRecarga requerido
 	 */	
	public function deleteByPorcenRegaloRecarga($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 */	
	public function deleteByMandante($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Pedido sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Pedido requerido
 	 */	
	public function deleteByPedido($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna DirIp sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value DirIp requerido
 	 */	
	public function deleteByDirIp($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna PromocionalId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PromocionalId requerido
 	 */	
	public function deleteByPromocionalId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna ValorPromocional sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ValorPromocional requerido
 	 */	
	public function deleteByValorPromocional($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Host sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Host requerido
 	 */	
	public function deleteByHost($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna PorcenIva sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PorcenIva requerido
 	 */	
	public function deleteByPorcenIva($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna MediopagoId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value MediopagoId requerido
 	 */	
	public function deleteByMediopagoId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna ValorIva sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ValorIva requerido
 	 */	
	public function deleteByValorIva($value);


}
?>