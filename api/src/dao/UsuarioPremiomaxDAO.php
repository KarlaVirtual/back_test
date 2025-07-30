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
interface UsuarioPremiomaxDAO{

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
 	 * @param String $usuarioPremiomax llave primaria
 	 */
	public function delete($usuarioPremiomax);
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param String usuarioPremiomax usuarioPremiomax
 	 */
	public function insert($usuarioPremiomax);
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Object usuarioPremiomax usuarioPremiomax
 	 */
	public function update($usuarioPremiomax);	

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
 	 * la columna PremioMax sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PremioMax requerido
 	 */	
	public function queryByPremioMax($value);

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
 	 * la columna CantLineas sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value CantLineas requerido
 	 */	
	public function queryByCantLineas($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna PremioMax1 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PremioMax1 requerido
 	 */	
	public function queryByPremioMax1($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna PremioMax2 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PremioMax2 requerido
 	 */	
	public function queryByPremioMax2($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna PremioMax3 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PremioMax3 requerido
 	 */	
	public function queryByPremioMax3($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna ApuestaMin sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ApuestaMin requerido
 	 */	
	public function queryByApuestaMin($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna ValorDirecto sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ValorDirecto requerido
 	 */	
	public function queryByValorDirecto($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna PremioDirecto sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PremioDirecto requerido
 	 */	
	public function queryByPremioDirecto($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 */	
	public function queryByMandante($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna OptimizarParrilla sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value OptimizarParrilla requerido
 	 */	
	public function queryByOptimizarParrilla($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna TextoOp1 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TextoOp1 requerido
 	 */	
	public function queryByTextoOp1($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna TextoOp2 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TextoOp2 requerido
 	 */	
	public function queryByTextoOp2($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna queryByUrlOp2 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value queryByUrlOp2 requerido
 	 */	
	public function queryByUrlOp2($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna queryByUrlOp3 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value queryByUrlOp3 requerido
 	 */	
	public function queryByTextoOp3($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna queryByUrlOp3 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value queryByUrlOp3 requerido
 	 */	
	public function queryByUrlOp3($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna ValorEvento sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ValorEvento requerido
 	 */	
	public function queryByValorEvento($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna ValorDiario sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ValorDiario requerido
 	 */	
	public function queryByValorDiario($value);





	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna UsuarioId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsuarioId requerido
 	 */	
	public function deleteByUsuarioId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna PremioMax sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PremioMax requerido
 	 */	
	public function deleteByPremioMax($value);

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
 	 * la columna CantLineas sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value CantLineas requerido
 	 */	
	public function deleteByCantLineas($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna PremioMax1 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PremioMax1 requerido
 	 */	
	public function deleteByPremioMax1($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna PremioMax2 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PremioMax2 requerido
 	 */	
	public function deleteByPremioMax2($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna PremioMax3 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PremioMax3 requerido
 	 */	
	public function deleteByPremioMax3($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna ApuestaMin sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ApuestaMin requerido
 	 */	
	public function deleteByApuestaMin($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna ValorDirecto sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ValorDirecto requerido
 	 */	
	public function deleteByValorDirecto($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna PremioDirecto sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PremioDirecto requerido
 	 */	
	public function deleteByPremioDirecto($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 */	
	public function deleteByMandante($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna OptimizarParrilla sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value OptimizarParrilla requerido
 	 */	
	public function deleteByOptimizarParrilla($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna TextoOp1 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TextoOp1 requerido
 	 */	
	public function deleteByTextoOp1($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna TextoOp2 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TextoOp2 requerido
 	 */	
	public function deleteByTextoOp2($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna deleteByUrlOp2 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value deleteByUrlOp2 requerido
 	 */	
	public function deleteByUrlOp2($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna deleteByUrlOp3 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value deleteByUrlOp3 requerido
 	 */	
	public function deleteByTextoOp3($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna deleteByUrlOp3 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value deleteByUrlOp3 requerido
 	 */	
	public function deleteByUrlOp3($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna deleteByValorEvento sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value deleteByValorEvento requerido
 	 */	
	public function deleteByValorEvento($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna deleteByValorDiario sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value deleteByValorDiario requerido
 	 */	
	public function deleteByValorDiario($value);


}
?>