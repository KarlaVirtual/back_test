<?php namespace Backend\dao;
/**
 * Interfaz para el modelo o tabla 'Registro'.
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface RegistroDAO{

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
 	 * @param String $registro_id llave primaria
 	 */
	public function delete($registro_id);
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param String registro registro
 	 */
	public function insert($registro);
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Object registro registro
 	 */
	public function update($registro);	

	/**
	 * Eliminar todos los registros de la base de datos
	 */
	public function clean();





	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Nombre sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Nombre requerido
 	 */	
	public function queryByNombre($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Email sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Email requerido
 	 */	
	public function queryByEmail($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna PuntoventaId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PuntoventaId requerido
 	 */	
	public function queryByPuntoventaId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Estado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Estado requerido
 	 */	
	public function queryByEstado($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna UsuarioId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsuarioId requerido
 	 */	
	public function queryByUsuarioId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna ClaveActiva sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ClaveActiva requerido
 	 */	
	public function queryByClaveActiva($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Creditos sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Creditos requerido
 	 */	
	public function queryByCreditos($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna CreditosBase sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value CreditosBase requerido
 	 */	
	public function queryByCreditosBase($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Celular sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Celular requerido
 	 */	
	public function queryByCelular($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Ciudad sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Ciudad requerido
 	 */	
	public function queryByCiudad($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna CreditosAnt sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value CreditosAnt requerido
 	 */	
	public function queryByCreditosAnt($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna CreditosBaseAnt sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value CreditosBaseAnt requerido
 	 */	
	public function queryByCreditosBaseAnt($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna CiudadId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value CiudadId requerido
 	 */	
	public function queryByCiudadId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 */	
	public function queryByMandante($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Casino sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Casino requerido
 	 */	
	public function queryByCasino($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna CasinoBase sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value CasinoBase requerido
 	 */	
	public function queryByCasinoBase($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna FechaCasino sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaCasino requerido
 	 */	
	public function queryByFechaCasino($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna PreregistroId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PreregistroId requerido
 	 */	
	public function queryByPreregistroId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna CreditosBono sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value CreditosBono requerido
 	 */	
	public function queryByCreditosBono($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna CreditosBonoAnt sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value CreditosBonoAnt requerido
 	 */	
	public function queryByCreditosBonoAnt($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Ocupacion sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Ocupacion requerido
 	 */	
	public function queryByOcupacion($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna RangoingresoId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value RangoingresoId requerido
 	 */	
	public function queryByRangoingresoId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna OrigenFondos sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value OrigenFondos requerido
 	 */	
	public function queryByOrigenFondos($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna PaisnacimId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PaisnacimId requerido
 	 */	
	public function queryByPaisnacimId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Cedula sea igual al valor pasado como parámetro
 	 *
     * @param String $value Cedula requerido
     * @param String $value mandante requerido
 	 */
	public function queryByCedula($value,$mandante);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Nombre1 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Nombre1 requerido
 	 */	
	public function queryByNombre1($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Nombre2 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Nombre2 requerido
 	 */	
	public function queryByNombre2($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Apellido1 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Apellido1 requerido
 	 */	
	public function queryByApellido1($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Apellido2 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Apellido2 requerido
 	 */	
	public function queryByApellido2($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Sexo sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Sexo requerido
 	 */	
	public function queryBySexo($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Direccion sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Direccion requerido
 	 */	
	public function queryByDireccion($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Telefono sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Telefono requerido
 	 */	
	public function queryByTelefono($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna CiudnacimId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value CiudnacimId requerido
 	 */	
	public function queryByCiudnacimId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna NacionalidadId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value NacionalidadId requerido
 	 */	
	public function queryByNacionalidadId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna EstadoValida sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value EstadoValida requerido
 	 */	
	public function queryByEstadoValida($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna UsuvalidaId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsuvalidaId requerido
 	 */	
	public function queryByUsuvalidaId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna FechaValida sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaValida requerido
 	 */	
	public function queryByFechaValida($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna DirIp sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value DirIp requerido
 	 */	
	public function queryByDirIp($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna TipoDoc sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TipoDoc requerido
 	 */	
	public function queryByTipoDoc($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna CiudexpedId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value CiudexpedId requerido
 	 */	
	public function queryByCiudexpedId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna FechaExped sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaExped requerido
 	 */	
	public function queryByFechaExped($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna CodigoPostal sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value CodigoPostal requerido
 	 */	
	public function queryByCodigoPostal($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna OcupacionId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value OcupacionId requerido
 	 */	
	public function queryByOcupacionId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna OrigenfondosId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value OrigenfondosId requerido
 	 */	
	public function queryByOrigenfondosId($value);




	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Nombre sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Nombre requerido
 	 */	
	public function deleteByNombre($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Email sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Email requerido
 	 */	
	public function deleteByEmail($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna PuntoventaId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PuntoventaId requerido
 	 */	
	public function deleteByPuntoventaId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Estado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Estado requerido
 	 */	
	public function deleteByEstado($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna UsuarioId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsuarioId requerido
 	 */	
	public function deleteByUsuarioId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna ClaveActiva sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ClaveActiva requerido
 	 */	
	public function deleteByClaveActiva($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Creditos sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Creditos requerido
 	 */	
	public function deleteByCreditos($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna CreditosBase sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value CreditosBase requerido
 	 */	
	public function deleteByCreditosBase($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Celular sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Celular requerido
 	 */	
	public function deleteByCelular($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Ciudad sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Ciudad requerido
 	 */	
	public function deleteByCiudad($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna CreditosAnt sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value CreditosAnt requerido
 	 */	
	public function deleteByCreditosAnt($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna CreditosBaseAnt sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value CreditosBaseAnt requerido
 	 */	
	public function deleteByCreditosBaseAnt($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna CiudadId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value CiudadId requerido
 	 */	
	public function deleteByCiudadId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 */	
	public function deleteByMandante($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Casino sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Casino requerido
 	 */	
	public function deleteByCasino($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna CasinoBase sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value CasinoBase requerido
 	 */	
	public function deleteByCasinoBase($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna FechaCasino sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaCasino requerido
 	 */	
	public function deleteByFechaCasino($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna PreregistroId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PreregistroId requerido
 	 */	
	public function deleteByPreregistroId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna CreditosBono sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value CreditosBono requerido
 	 */	
	public function deleteByCreditosBono($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna CreditosBonoAnt sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value CreditosBonoAnt requerido
 	 */	
	public function deleteByCreditosBonoAnt($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Ocupacion sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Ocupacion requerido
 	 */	
	public function deleteByOcupacion($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna RangoingresoId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value RangoingresoId requerido
 	 */	
	public function deleteByRangoingresoId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna OrigenFondos sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value OrigenFondos requerido
 	 */	
	public function deleteByOrigenFondos($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna PaisnacimId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PaisnacimId requerido
 	 */	
	public function deleteByPaisnacimId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Cedula sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Cedula requerido
 	 */	
	public function deleteByCedula($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Nombre1 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Nombre1 requerido
 	 */	
	public function deleteByNombre1($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Nombre2 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Nombre2 requerido
 	 */	
	public function deleteByNombre2($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Apellido1 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Apellido1 requerido
 	 */	
	public function deleteByApellido1($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Apellido2 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Apellido2 requerido
 	 */	
	public function deleteByApellido2($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Sexo sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Sexo requerido
 	 */	
	public function deleteBySexo($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Direccion sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Direccion requerido
 	 */	
	public function deleteByDireccion($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Telefono sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Telefono requerido
 	 */	
	public function deleteByTelefono($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna CiudnacimId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value CiudnacimId requerido
 	 */	
	public function deleteByCiudnacimId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna NacionalidadId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value NacionalidadId requerido
 	 */	
	public function deleteByNacionalidadId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna EstadoValida sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value EstadoValida requerido
 	 */	
	public function deleteByEstadoValida($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna UsuvalidaId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsuvalidaId requerido
 	 */	
	public function deleteByUsuvalidaId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna FechaValida sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaValida requerido
 	 */	
	public function deleteByFechaValida($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna queryByDirIp sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value queryByDirIp requerido
 	 */	
	public function deleteByDirIp($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna TipoDoc sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TipoDoc requerido
 	 */	
	public function deleteByTipoDoc($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna CiudexpedId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value CiudexpedId requerido
 	 */	
	public function deleteByCiudexpedId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna FechaExped sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaExped requerido
 	 */	
	public function deleteByFechaExped($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna CodigoPostal sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value CodigoPostal requerido
 	 */	
	public function deleteByCodigoPostal($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna OcupacionId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value OcupacionId requerido
 	 */	
	public function deleteByOcupacionId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna OrigenfondosId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value OrigenfondosId requerido
 	 */	
	public function deleteByOrigenfondosId($value);


}
?>