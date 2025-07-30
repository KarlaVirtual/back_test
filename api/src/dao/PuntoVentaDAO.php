<?php namespace Backend\dao;
/**
 * Interfaz para el modelo o tabla 'PuntoVenta'.
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface PuntoVentaDAO{

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
 	 * @param String $puntoventa_id llave primaria
 	 */
	public function delete($puntoventa_id);
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param String puntoVenta puntoVenta
 	 */
	public function insert($puntoVenta);
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Object puntoVenta puntoVenta
 	 */
	public function update($puntoVenta);	
	
	/**
	 * Eliminar todos los registros de la base de datos
	 */
	public function clean();





	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Descripcion sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Descripcion requerido
 	 */	
	public function queryByDescripcion($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Ciudad sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Ciudad requerido
 	 */	
	public function queryByCiudad($value);

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
 	 * la columna NombreContacto sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value NombreContacto requerido
 	 */	
	public function queryByNombreContacto($value);

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
 	 * la columna Email sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Email requerido
 	 */	
	public function queryByEmail($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna ValorCupo sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ValorCupo requerido
 	 */	
	public function queryByValorCupo($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna PorcenComision sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PorcenComision requerido
 	 */	
	public function queryByPorcenComision($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna PeriodicidadId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PeriodicidadId requerido
 	 */	
	public function queryByPeriodicidadId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna ValorRecarga sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ValorRecarga requerido
 	 */	
	public function queryByValorRecarga($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna ValorCupo2 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ValorCupo2 requerido
 	 */	
	public function queryByValorCupo2($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna PorcenComision2 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PorcenComision2 requerido
 	 */	
	public function queryByPorcenComision2($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Barrio sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Barrio requerido
 	 */	
	public function queryByBarrio($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Clasificador1Id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Clasificador1Id requerido
 	 */	
	public function queryByClasificador1Id($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Clasificador2Id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Clasificador2Id requerido
 	 */	
	public function queryByClasificador2Id($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Clasificador3Id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Clasificador3Id requerido
 	 */	
	public function queryByClasificador3Id($value);

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
 	 * la columna CupoRecarga sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value CupoRecarga requerido
 	 */	
	public function queryByCupoRecarga($value);





	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Descripcion sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Descripcion requerido
 	 */	
	public function deleteByDescripcion($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Ciudad sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Ciudad requerido
 	 */	
	public function deleteByCiudad($value);

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
 	 * la columna NombreContacto sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value NombreContacto requerido
 	 */	
	public function deleteByNombreContacto($value);

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
 	 * la columna Email sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Email requerido
 	 */	
	public function deleteByEmail($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna ValorCupo sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ValorCupo requerido
 	 */	
	public function deleteByValorCupo($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna PorcenComision sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PorcenComision requerido
 	 */	
	public function deleteByPorcenComision($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna PeriodicidadId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PeriodicidadId requerido
 	 */	
	public function deleteByPeriodicidadId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna ValorRecarga sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ValorRecarga requerido
 	 */	
	public function deleteByValorRecarga($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna ValorCupo2 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ValorCupo2 requerido
 	 */	
	public function deleteByValorCupo2($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna PorcenComision2 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PorcenComision2 requerido
 	 */	
	public function deleteByPorcenComision2($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Barrio sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Barrio requerido
 	 */	
	public function deleteByBarrio($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Clasificador1Id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Clasificador1Id requerido
 	 */	
	public function deleteByClasificador1Id($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Clasificador2Id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Clasificador2Id requerido
 	 */	
	public function deleteByClasificador2Id($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Clasificador3Id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Clasificador3Id requerido
 	 */	
	public function deleteByClasificador3Id($value);

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
 	 * la columna CupoRecarga sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value CupoRecarga requerido
 	 */	
	public function deleteByCupoRecarga($value);


}
?>