<?php namespace Backend\dao;
/**
 * Interfaz para el modelo o tabla 'Bono'.
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface BonoDAO{

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
	public function delete($bono_id);
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param Object Bono bono
 	 */
	public function insert($bono);
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Object Bono bono
 	 */
	public function update($bono);	

	/**
	 * Eliminar todos los registros de la base de datos
	 */
	public function clean();




	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Codigo sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Codigo requerido
 	 */	
	public function queryByCodigo($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Bonusplanid sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Bonusplanid requerido
 	 */	
	public function queryByBonusplanid($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna FechaIni sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaIni requerido
 	 */	
	public function queryByFechaIni($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna FechaFin sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaFin requerido
 	 */	
	public function queryByFechaFin($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Tipo sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Tipo requerido
 	 */	
	public function queryByTipo($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Descripcion sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Descripcion requerido
 	 */	
	public function queryByDescripcion($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna UsucreaId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsucreaId requerido
 	 */	
	public function queryByUsucreaId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna FechaCrea sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaCrea requerido
 	 */	
	public function queryByFechaCrea($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 */	
	public function queryByMandante($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna DiasExpira sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value DiasExpira requerido
 	 */	
	public function queryByDiasExpira($value);

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
 	 * la columna Owner sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Owner requerido
 	 */	
	public function queryByOwner($value);





	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Codigo sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Codigo requerido
 	 */
	public function deleteByCodigo($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Bonusplanid sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Bonusplanid requerido
 	 */
	public function deleteByBonusplanid($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna FechaIni sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaIni requerido
 	 */
	public function deleteByFechaIni($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna FechaFin sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaFin requerido
 	 */
	public function deleteByFechaFin($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Tipo sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Tipo requerido
 	 */
	public function deleteByTipo($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Descripcion sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Descripcion requerido
 	 */
	public function deleteByDescripcion($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna UsucreaId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsucreaId requerido
 	 */
	public function deleteByUsucreaId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna FechaCrea sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaCrea requerido
 	 */
	public function deleteByFechaCrea($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 */
	public function deleteByMandante($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna DiasExpira sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value DiasExpira requerido
 	 */
	public function deleteByDiasExpira($value);

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
 	 * la columna Owner sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Owner requerido
 	 */
	public function deleteByOwner($value);


}
?>