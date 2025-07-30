<?php namespace Backend\dao;
/**
 * Interfaz para el modelo o tabla 'ItTicketEncInfo1'.
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface ItTicketEncInfo1DAO{

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
 	 * @param String $it_ticket_id llave primaria
 	 */
	public function delete($it_ticket_id);
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param String ItTicketEncInfo1 ItTicketEncInfo1
 	 */
	public function insert($ItTicketEncInfo1);
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Object ItTicketEncInfo1 ItTicketEncInfo1
 	 */
	public function update($ItTicketEncInfo1);	

	/**
	 * Eliminar todos los registros de la base de datos
	 */
	public function clean();





	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna TransaccionId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TransaccionId requerido
 	 */	
	public function queryByTransaccionId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna TicketId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TicketId requerido
 	 */	
	public function queryByTicketId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna VlrApuesta sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value VlrApuesta requerido
 	 */	
	public function queryByVlrApuesta($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna VlrPremio sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value VlrPremio requerido
 	 */	
	public function queryByVlrPremio($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna UsuarioId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsuarioId requerido
 	 */	
	public function queryByUsuarioId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna GameReference sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value GameReference requerido
 	 */	
	public function queryByGameReference($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna BetStatus sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value BetStatus requerido
 	 */	
	public function queryByBetStatus($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna CantLineas sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value CantLineas requerido
 	 */	
	public function queryByCantLineas($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna FechaCrea sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaCrea requerido
 	 */	
	public function queryByFechaCrea($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna HoraCrea sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value HoraCrea requerido
 	 */	
	public function queryByHoraCrea($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Premiado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Premiado requerido
 	 */	
	public function queryByPremiado($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna PremioPagado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PremioPagado requerido
 	 */	
	public function queryByPremioPagado($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna FechaPago sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaPago requerido
 	 */	
	public function queryByFechaPago($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna HoraPago sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value HoraPago requerido
 	 */	
	public function queryByHoraPago($value);

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
 	 * la columna Eliminado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Eliminado requerido
 	 */	
	public function queryByEliminado($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna FechaMaxpago sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaMaxpago requerido
 	 */	
	public function queryByFechaMaxpago($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna FechaCierre sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaCierre requerido
 	 */	
	public function queryByFechaCierre($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna HoraCierre sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value HoraCierre requerido
 	 */	
	public function queryByHoraCierre($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Clave sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Clave requerido
 	 */	
	public function queryByClave($value);

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
 	 * la columna BeneficiarioId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value BeneficiarioId requerido
 	 */	
	public function queryByBeneficiarioId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna TipoBeneficiario sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TipoBeneficiario requerido
 	 */	
	public function queryByTipoBeneficiario($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna DirIp sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value DirIp requerido
 	 */	
	public function queryByDirIp($value);





	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna TransaccionId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TransaccionId requerido
 	 */	
	public function deleteByTransaccionId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna TicketId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TicketId requerido
 	 */	
	public function deleteByTicketId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna VlrApuesta sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value VlrApuesta requerido
 	 */	
	public function deleteByVlrApuesta($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna VlrPremio sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value VlrPremio requerido
 	 */	
	public function deleteByVlrPremio($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna UsuarioId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsuarioId requerido
 	 */	
	public function deleteByUsuarioId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna GameReference sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value GameReference requerido
 	 */	
	public function deleteByGameReference($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna BetStatus sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value BetStatus requerido
 	 */	
	public function deleteByBetStatus($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna CantLineas sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value CantLineas requerido
 	 */	
	public function deleteByCantLineas($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna FechaCrea sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaCrea requerido
 	 */	
	public function deleteByFechaCrea($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna HoraCrea sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value HoraCrea requerido
 	 */	
	public function deleteByHoraCrea($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Premiado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Premiado requerido
 	 */	
	public function deleteByPremiado($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna PremioPagado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PremioPagado requerido
 	 */	
	public function deleteByPremioPagado($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna FechaPago sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaPago requerido
 	 */	
	public function deleteByFechaPago($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna HoraPago sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value HoraPago requerido
 	 */	
	public function deleteByHoraPago($value);

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
 	 * la columna Eliminado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Eliminado requerido
 	 */	
	public function deleteByEliminado($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna FechaMaxpago sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaMaxpago requerido
 	 */	
	public function deleteByFechaMaxpago($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna FechaCierre sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaCierre requerido
 	 */	
	public function deleteByFechaCierre($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna HoraCierre sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value HoraCierre requerido
 	 */	
	public function deleteByHoraCierre($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Clave sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Clave requerido
 	 */	
	public function deleteByClave($value);

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
 	 * la columna BeneficiarioId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value BeneficiarioId requerido
 	 */	
	public function deleteByBeneficiarioId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna TipoBeneficiario sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TipoBeneficiario requerido
 	 */	
	public function deleteByTipoBeneficiario($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna DirIp sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value DirIp requerido
 	 */	
	public function deleteByDirIp($value);


}
?>