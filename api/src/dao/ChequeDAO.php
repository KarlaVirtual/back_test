<?php namespace Backend\dao;
/**
 * Interfaz para el modelo o tabla 'Cheque'.
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface ChequeDAO{

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
 	 * @param String $id llave primaria
 	 */
	public function delete($id);
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param Object cheque cheque
 	 */
	public function insert($cheque);
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Object cheque cheque
 	 */
	public function update($cheque);	

	/**
	 * Eliminar todos los registros de la base de datos
	 */
	public function clean();







	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna NroCheque sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value NroCheque requerido
 	 */
	public function queryByNroCheque($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna PaisId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PaisId requerido
 	 */
	public function queryByPaisId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Origen sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Origen requerido
 	 */
	public function queryByOrigen($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna DocumentoId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value DocumentoId requerido
 	 */
	public function queryByDocumentoId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 */
	public function queryByMandante($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna TicketId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TicketId requerido
 	 */
	public function queryByTicketId($value);







	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna NroCheque sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value NroCheque requerido
 	 */
	public function deleteByNroCheque($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna PaisId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PaisId requerido
 	 */	
	public function deleteByPaisId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Origen sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Origen requerido
 	 */
	public function deleteByOrigen($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna DocumentoId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value DocumentoId requerido
 	 */
	public function deleteByDocumentoId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 */
	public function deleteByMandante($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna TicketId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TicketId requerido
 	 */
	public function deleteByTicketId($value);


}
?>