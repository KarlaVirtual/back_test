<?php namespace Backend\dao;
/**
 * Interfaz para el modelo o tabla 'Usuario'.
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface UsuarioDAO{

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
 	 * @param String $usuario_id llave primaria
 	 */
	public function delete($usuario_id);
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param String usuario usuario
 	 */
	public function insert($usuario);
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Object usuario usuario
 	 */
	public function update($usuario);	

	/**
	 * Eliminar todos los registros de la base de datos
	 */
	public function clean();





	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Login sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Login requerido
 	 */	
	public function queryByLogin($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Clave sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Clave requerido
 	 */	
	public function queryByClave($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Nombre sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Nombre requerido
 	 */	
	public function queryByNombre($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Estado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Estado requerido
 	 */	
	public function queryByEstado($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna FechaUlt sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaUlt requerido
 	 */	
	public function queryByFechaUlt($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna ClaveTv sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ClaveTv requerido
 	 */	
	public function queryByClaveTv($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna EstadoAnt sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value EstadoAnt requerido
 	 */	
	public function queryByEstadoAnt($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Intentos sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Intentos requerido
 	 */	
	public function queryByIntentos($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna EstadoEsp sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value EstadoEsp requerido
 	 */	
	public function queryByEstadoEsp($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Observ sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Observ requerido
 	 */	
	public function queryByObserv($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna FechaCrea sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaCrea requerido
 	 */	
	public function queryByFechaCrea($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna DirIp sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value DirIp requerido
 	 */	
	public function queryByDirIp($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Eliminado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Eliminado requerido
 	 */	
	public function queryByEliminado($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 */	
	public function queryByMandante($value);

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
 	 * la columna FechaModif sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaModif requerido
 	 */	
	public function queryByFechaModif($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna ClaveCasino sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ClaveCasino requerido
 	 */	
	public function queryByClaveCasino($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna TokenItainment sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TokenItainment requerido
 	 */	
	public function queryByTokenItainment($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna FechaClave sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaClave requerido
 	 */	
	public function queryByFechaClave($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Retirado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Retirado requerido
 	 */	
	public function queryByRetirado($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna FechaRetiro sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaRetiro requerido
 	 */	
	public function queryByFechaRetiro($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna HoraRetiro sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value HoraRetiro requerido
 	 */	
	public function queryByHoraRetiro($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna UsuretiroId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsuretiroId requerido
 	 */	
	public function queryByUsuretiroId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna BloqueoVentas sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value BloqueoVentas requerido
 	 */	
	public function queryByBloqueoVentas($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna InfoEquipo sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value InfoEquipo requerido
 	 */	
	public function queryByInfoEquipo($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna EstadoJugador sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value EstadoJugador requerido
 	 */	
	public function queryByEstadoJugador($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna TokenCasino sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TokenCasino requerido
 	 */	
	public function queryByTokenCasino($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna SponsorId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value SponsorId requerido
 	 */	
	public function queryBySponsorId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna VerifCorreo sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value VerifCorreo requerido
 	 */	
	public function queryByVerifCorreo($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna PaisId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PaisId requerido
 	 */	
	public function queryByPaisId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Moneda sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Moneda requerido
 	 */	
	public function queryByMoneda($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Idioma sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Idioma requerido
 	 */	
	public function queryByIdioma($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna PermiteActivareg sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PermiteActivareg requerido
 	 */	
	public function queryByPermiteActivareg($value);





	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Login sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Login requerido
 	 */	
	public function deleteByLogin($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Clave sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Clave requerido
 	 */	
	public function deleteByClave($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Nombre sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Nombre requerido
 	 */	
	public function deleteByNombre($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Estado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Estado requerido
 	 */	
	public function deleteByEstado($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna FechaUlt sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaUlt requerido
 	 */	
	public function deleteByFechaUlt($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna ClaveTv sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ClaveTv requerido
 	 */	
	public function deleteByClaveTv($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna EstadoAnt sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value EstadoAnt requerido
 	 */	
	public function deleteByEstadoAnt($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Intentos sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Intentos requerido
 	 */	
	public function deleteByIntentos($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna EstadoEsp sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value EstadoEsp requerido
 	 */	
	public function deleteByEstadoEsp($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Observ sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Observ requerido
 	 */	
	public function deleteByObserv($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna FechaCrea sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaCrea requerido
 	 */	
	public function deleteByFechaCrea($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna DirIp sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value DirIp requerido
 	 */	
	public function deleteByDirIp($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Eliminado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Eliminado requerido
 	 */	
	public function deleteByEliminado($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 */	
	public function deleteByMandante($value);

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
 	 * la columna FechaModif sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaModif requerido
 	 */	
	public function deleteByFechaModif($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna ClaveCasino sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ClaveCasino requerido
 	 */	
	public function deleteByClaveCasino($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna TokenItainment sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TokenItainment requerido
 	 */	
	public function deleteByTokenItainment($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna FechaClave sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaClave requerido
 	 */	
	public function deleteByFechaClave($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Retirado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Retirado requerido
 	 */	
	public function deleteByRetirado($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna FechaRetiro sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaRetiro requerido
 	 */	
	public function deleteByFechaRetiro($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna HoraRetiro sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value HoraRetiro requerido
 	 */	
	public function deleteByHoraRetiro($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna UsuretiroId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsuretiroId requerido
 	 */	
	public function deleteByUsuretiroId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna BloqueoVentas sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value BloqueoVentas requerido
 	 */	
	public function deleteByBloqueoVentas($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna InfoEquipo sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value InfoEquipo requerido
 	 */	
	public function deleteByInfoEquipo($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna EstadoJugador sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value EstadoJugador requerido
 	 */	
	public function deleteByEstadoJugador($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna TokenCasino sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TokenCasino requerido
 	 */	
	public function deleteByTokenCasino($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna SponsorId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value SponsorId requerido
 	 */	
	public function deleteBySponsorId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna VerifCorreo sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value VerifCorreo requerido
 	 */	
	public function deleteByVerifCorreo($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna PaisId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PaisId requerido
 	 */	
	public function deleteByPaisId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Moneda sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Moneda requerido
 	 */	
	public function deleteByMoneda($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Idioma sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Idioma requerido
 	 */	
	public function deleteByIdioma($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna PermiteActivareg sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PermiteActivareg requerido
 	 */	
	public function deleteByPermiteActivareg($value);


}
?>