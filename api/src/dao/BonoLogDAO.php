<?php namespace Backend\dao;
/**
 * Interfaz para el modelo o tabla 'BonoLog'.
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface BonoLogDAO{

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
	public function delete($bonolog_id);
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param Object BonoLog bonoLog
 	 */
	public function insert($bonoLog);
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Object BonoLog bonoLog
 	 */
	public function update($bonoLog);	

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
 	 * la columna Tipo sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Tipo requerido
 	 */	
	public function queryByTipo($value);

    /**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Valor sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Valor requerido
 	 */	
	public function queryByValor($value);

    /**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna FechaCrea sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaCrea requerido
 	 */	
	public function queryByFechaCrea($value);

    /**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Estado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Estado requerido
 	 */	
	public function queryByEstado($value);

    /**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna ErrorId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ErrorId requerido
 	 */	
	public function queryByErrorId($value);
	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Externo sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Externo requerido
 	 */	
	public function queryByIdExterno($value);
	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 */	
	public function queryByMandante($value);
	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna FechaCierre sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaCierre requerido
 	 */	
	public function queryByFechaCierre($value);
	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna TransaccionId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TransaccionId requerido
 	 */	
	public function queryByTransaccionId($value);





	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna UsuarioId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsuarioId requerido
 	 */
	public function deleteByUsuarioId($value);
	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Tipo sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Tipo requerido
 	 */
	public function deleteByTipo($value);
	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Valor sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Valor requerido
 	 */
	public function deleteByValor($value);
	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna FechaCrea sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaCrea requerido
 	 */
	public function deleteByFechaCrea($value);
	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Estado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Estado requerido
 	 */
	public function deleteByEstado($value);
	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna ErrorId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ErrorId requerido
 	 */
	public function deleteByErrorId($value);
	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna IdExterno sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value IdExterno requerido
 	 */
	public function deleteByIdExterno($value);
	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 */
	public function deleteByMandante($value);
	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna FechaCierre sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaCierre requerido
 	 */
	public function deleteByFechaCierre($value);
	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna TransaccionId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TransaccionId requerido
 	 */
	public function deleteByTransaccionId($value);


}
?>