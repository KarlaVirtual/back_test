<?php namespace Backend\dao;
/**
 * Interfaz para el modelo o tabla 'TicketDet'.
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface TicketDetDAO{

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
 	 * @param String $ticketdet_id llave primaria
 	 */
	public function delete($ticketdet_id);
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param String ticketDet ticketDet
 	 */
	public function insert($ticketDet);
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Object ticketDet ticketDet
 	 */
	public function update($ticketDet);	

	/**
	 * Eliminar todos los registros de la base de datos
	 */
	public function clean();





	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna TicketId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TicketId requerido
 	 */	
	public function queryByTicketId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna ApuestadetId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ApuestadetId requerido
 	 */	
	public function queryByApuestadetId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Logro sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Logro requerido
 	 */	
	public function queryByLogro($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Estado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Estado requerido
 	 */	
	public function queryByEstado($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Abreviado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Abreviado requerido
 	 */	
	public function queryByAbreviado($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna UsumodificaId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsumodificaId requerido
 	 */	
	public function queryByUsumodificaId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna FechaModifica sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaModifica requerido
 	 */	
	public function queryByFechaModifica($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Premiado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Premiado requerido
 	 */	
	public function queryByPremiado($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna LogroConsolidado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value LogroConsolidado requerido
 	 */	
	public function queryByLogroConsolidado($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna VlrApuesta sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value VlrApuesta requerido
 	 */	
	public function queryByVlrApuesta($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Tipo sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Tipo requerido
 	 */	
	public function queryByTipo($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna LogroBase sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value LogroBase requerido
 	 */	
	public function queryByLogroBase($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna ListaId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ListaId requerido
 	 */	
	public function queryByListaId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna LogroConsolidadoBase sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value LogroConsolidadoBase requerido
 	 */	
	public function queryByLogroConsolidadoBase($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Accion sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Accion requerido
 	 */	
	public function queryByAccion($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 */	
	public function queryByMandante($value);





	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna TicketId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TicketId requerido
 	 */	
	public function deleteByTicketId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna ApuestadetId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ApuestadetId requerido
 	 */	
	public function deleteByApuestadetId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Logro sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Logro requerido
 	 */	
	public function deleteByLogro($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Estado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Estado requerido
 	 */	
	public function deleteByEstado($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Abreviado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Abreviado requerido
 	 */	
	public function deleteByAbreviado($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna UsumodificaId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsumodificaId requerido
 	 */	
	public function deleteByUsumodificaId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna FechaModifica sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaModifica requerido
 	 */	
	public function deleteByFechaModifica($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Premiado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Premiado requerido
 	 */	
	public function deleteByPremiado($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna LogroConsolidado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value LogroConsolidado requerido
 	 */	
	public function deleteByLogroConsolidado($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna VlrApuesta sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value VlrApuesta requerido
 	 */	
	public function deleteByVlrApuesta($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Tipo sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Tipo requerido
 	 */	
	public function deleteByTipo($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna LogroBase sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value LogroBase requerido
 	 */	
	public function deleteByLogroBase($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna ListaId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ListaId requerido
 	 */	
	public function deleteByListaId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna LogroConsolidadoBase sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value LogroConsolidadoBase requerido
 	 */	
	public function deleteByLogroConsolidadoBase($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Accion sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Accion requerido
 	 */	
	public function deleteByAccion($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 */	
	public function deleteByMandante($value);


}
?>