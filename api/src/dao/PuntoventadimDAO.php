<?php namespace Backend\dao;
/**
 * Interfaz para el modelo o tabla 'Itainment'.
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface PuntoventadimDAO{

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
 	 * @param String puntoventadim puntoventadim
 	 */
	public function insert($puntoventadim);
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Object puntoventadim puntoventadim
 	 */
	public function update($puntoventadim);	

	/**
	 * Eliminar todos los registros de la base de datos
	 */
	public function clean();





	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna PuntoventaNom sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PuntoventaNom requerido
 	 */	
	public function queryByPuntoventaNom($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna ConcesionarioId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ConcesionarioId requerido
 	 */	
	public function queryByConcesionarioId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna ConcesionarioNom sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ConcesionarioNom requerido
 	 */	
	public function queryByConcesionarioNom($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna SubconcesionarioId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value SubconcesionarioId requerido
 	 */	
	public function queryBySubconcesionarioId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna SubconcesionarioNom sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value SubconcesionarioNom requerido
 	 */	
	public function queryBySubconcesionarioNom($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna DeptoId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value DeptoId requerido
 	 */	
	public function queryByDeptoId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna DeptoNom sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value DeptoNom requerido
 	 */	
	public function queryByDeptoNom($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna CiudadId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value CiudadId requerido
 	 */	
	public function queryByCiudadId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna CiudadNom sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value CiudadNom requerido
 	 */	
	public function queryByCiudadNom($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 */	
	public function queryByMandante($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna RegionalId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value RegionalId requerido
 	 */	
	public function queryByRegionalId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna RegionalNom sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value RegionalNom requerido
 	 */	
	public function queryByRegionalNom($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna TipopuntoId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TipopuntoId requerido
 	 */	
	public function queryByTipopuntoId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna TipopuntoNom sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TipopuntoNom requerido
 	 */	
	public function queryByTipopuntoNom($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna TipoestablecimientoId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TipoestablecimientoId requerido
 	 */	
	public function queryByTipoestablecimientoId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna TipoestablecimientoNom sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TipoestablecimientoNom requerido
 	 */	
	public function queryByTipoestablecimientoNom($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna PaisId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PaisId requerido
 	 */	
	public function queryByPaisId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna PaisNom sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PaisNom requerido
 	 */	
	public function queryByPaisNom($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Moneda sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Moneda requerido
 	 */	
	public function queryByMoneda($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna MonedaNom sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value MonedaNom requerido
 	 */	
	public function queryByMonedaNom($value);





	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna queryByPuntoventaNom sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value queryByPuntoventaNom requerido
 	 */	
	public function deleteByPuntoventaNom($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna ConcesionarioId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ConcesionarioId requerido
 	 */	
	public function deleteByConcesionarioId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna ConcesionarioNom sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ConcesionarioNom requerido
 	 */	
	public function deleteByConcesionarioNom($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna SubconcesionarioId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value SubconcesionarioId requerido
 	 */	
	public function deleteBySubconcesionarioId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna SubconcesionarioNom sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value SubconcesionarioNom requerido
 	 */	
	public function deleteBySubconcesionarioNom($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna DeptoId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value DeptoId requerido
 	 */	
	public function deleteByDeptoId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna DeptoNom sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value DeptoNom requerido
 	 */	
	public function deleteByDeptoNom($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna CiudadId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value CiudadId requerido
 	 */	
	public function deleteByCiudadId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna CiudadNom sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value CiudadNom requerido
 	 */	
	public function deleteByCiudadNom($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 */	
	public function deleteByMandante($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna RegionalId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value RegionalId requerido
 	 */	
	public function deleteByRegionalId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna RegionalNom sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value RegionalNom requerido
 	 */	
	public function deleteByRegionalNom($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna TipopuntoId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TipopuntoId requerido
 	 */	
	public function deleteByTipopuntoId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna TipopuntoNom sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TipopuntoNom requerido
 	 */	
	public function deleteByTipopuntoNom($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna TipoestablecimientoId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TipoestablecimientoId requerido
 	 */	
	public function deleteByTipoestablecimientoId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna TipoestablecimientoNom sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TipoestablecimientoNom requerido
 	 */	
	public function deleteByTipoestablecimientoNom($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna PaisId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PaisId requerido
 	 */	
	public function deleteByPaisId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna PaisNom sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PaisNom requerido
 	 */	
	public function deleteByPaisNom($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Moneda sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Moneda requerido
 	 */	
	public function deleteByMoneda($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna MonedaNom sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value MonedaNom requerido
 	 */	
	public function deleteByMonedaNom($value);


}
?>