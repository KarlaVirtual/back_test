<?php namespace Backend\dao;
/**
 * Interfaz para el modelo o tabla 'TicketEnc'.
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface TicketEncDAO{

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
 	 * @param String $ticket_id llave primaria
 	 */
	public function delete($ticket_id);
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param String ticketEnc ticketEnc
 	 */
	public function insert($ticketEnc);
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Object ticketEnc ticketEnc
 	 */
	public function update($ticketEnc);	

	/**
	 * Eliminar todos los registros de la base de datos
	 */
	public function clean();





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
 	 * la columna UsucreaId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsucreaId requerido
 	 */
	public function queryByUsucreaId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna VlrApuesta sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value VlrApuesta requerido
 	 */
	public function queryByVlrApuesta($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Estado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Estado requerido
 	 */
	public function queryByEstado($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Clave sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Clave requerido
 	 */
	public function queryByClave($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Formapago1Id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Formapago1Id requerido
 	 */
	public function queryByFormapago1Id($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Formapago2Id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Formapago2Id requerido
 	 */
	public function queryByFormapago2Id($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna ValorForma1 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ValorForma1 requerido
 	 */
	public function queryByValorForma1($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna ValorForma2 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ValorForma2 requerido
 	 */
	public function queryByValorForma2($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna BonoId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value BonoId requerido
 	 */
	public function queryByBonoId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna VlrPremio sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value VlrPremio requerido
 	 */
	public function queryByVlrPremio($value);

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
 	 * la columna FechaMaxpago sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaMaxpago requerido
 	 */
	public function queryByFechaMaxpago($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna FechaPremio sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaPremio requerido
 	 */
	public function queryByFechaPremio($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Devolucion sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Devolucion requerido
 	 */
	public function queryByDevolucion($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Devolucion sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Devolucion requerido
 	 */
	public function queryByEsDevolucion($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna DirIp sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value DirIp requerido
 	 */
	public function queryByDirIp($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Impreso sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Impreso requerido
 	 */
	public function queryByImpreso($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 */
	public function queryByMandante($value);

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
 	 * la columna UsucreaId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsucreaId requerido
 	 */
	public function deleteByUsucreaId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna VlrApuesta sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value VlrApuesta requerido
 	 */
	public function deleteByVlrApuesta($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Estado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Estado requerido
 	 */
	public function deleteByEstado($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Clave sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Clave requerido
 	 */
	public function deleteByClave($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Formapago1Id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Formapago1Id requerido
 	 */
	public function deleteByFormapago1Id($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Formapago2Id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Formapago2Id requerido
 	 */
	public function deleteByFormapago2Id($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna ValorForma1 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ValorForma1 requerido
 	 */
	public function deleteByValorForma1($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna ValorForma2 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ValorForma2 requerido
 	 */
	public function deleteByValorForma2($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna BonoId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value BonoId requerido
 	 */
	public function deleteByBonoId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna VlrPremio sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value VlrPremio requerido
 	 */
	public function deleteByVlrPremio($value);

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
 	 * la columna FechaMaxpago sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaMaxpago requerido
 	 */
	public function deleteByFechaMaxpago($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna FechaPremio sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaPremio requerido
 	 */
	public function deleteByFechaPremio($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Devolucion sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Devolucion requerido
 	 */
	public function deleteByDevolucion($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna EsDevolucion sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value EsDevolucion requerido
 	 */
	public function deleteByEsDevolucion($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna DirIp sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value DirIp requerido
 	 */
	public function deleteByDirIp($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Impreso sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Impreso requerido
 	 */
	public function deleteByImpreso($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 */
	public function deleteByMandante($value);

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


}
?>