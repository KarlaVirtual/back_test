<?php

namespace Backend\dao;

/**
 * Interfaz para el modelo o tabla 'TransprodLog'.
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @category    No
 * @package     No
 * @author      Desconocido
 * @version     1.0
 * @since       No definida
 */
interface TransprodLogDAO
{

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
	 * @param String $transprodlog_id llave primaria
	 */
	public function delete($transprodlog_id);

	/**
	 * Insertar un registro en la base de datos
	 *
	 * @param String transprodLog transprodLog
	 */
	public function insert($transprodLog);

	/**
	 * Editar un registro en la base de datos
	 *
	 * @param Object transprodLog transprodLog
	 */
	public function update($transprodLog);

	/**
	 * Eliminar todos los registros de la base de datos
	 */
	public function clean();





	/**
	 * Obtener todos los registros donde se encuentre que
	 * la columna TransproductoId sea igual al valor pasado como parámetro
	 *
	 * @param String $value TransproductoId requerido
	 */
	public function queryByTransproductoId($value);

	/**
	 * Obtener todos los registros donde se encuentre que
	 * la columna Estado sea igual al valor pasado como parámetro
	 *
	 * @param String $value Estado requerido
	 */
	public function queryByEstado($value);

	/**
	 * Obtener todos los registros donde se encuentre que
	 * la columna TipoGenera sea igual al valor pasado como parámetro
	 *
	 * @param String $value TipoGenera requerido
	 */
	public function queryByTipoGenera($value);

	/**
	 * Obtener todos los registros donde se encuentre que
	 * la columna Comentario sea igual al valor pasado como parámetro
	 *
	 * @param String $value Comentario requerido
	 */
	public function queryByComentario($value);

	/**
	 * Obtener todos los registros donde se encuentre que
	 * la columna TValue sea igual al valor pasado como parámetro
	 *
	 * @param String $value TValue requerido
	 */
	public function queryByTValue($value);

	/**
	 * Obtener todos los registros donde se encuentre que
	 * la columna FechaCrea sea igual al valor pasado como parámetro
	 *
	 * @param String $value FechaCrea requerido
	 */
	public function queryByFechaCrea($value);

	/**
	 * Obtener todos los registros donde se encuentre que
	 * la columna FechaModif sea igual al valor pasado como parámetro
	 *
	 * @param String $value FechaModif requerido
	 */
	public function queryByFechaModif($value);

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
	 * Eliminar todos los registros donde se encuentre que
	 * la columna TransproductoId sea igual al valor pasado como parámetro
	 *
	 * @param String $value TransproductoId requerido
	 */
	public function deleteByTransproductoId($value);

	/**
	 * Eliminar todos los registros donde se encuentre que
	 * la columna Estado sea igual al valor pasado como parámetro
	 *
	 * @param String $value Estado requerido
	 */
	public function deleteByEstado($value);

	/**
	 * Eliminar todos los registros donde se encuentre que
	 * la columna TipoGenera sea igual al valor pasado como parámetro
	 *
	 * @param String $value TipoGenera requerido
	 */
	public function deleteByTipoGenera($value);

	/**
	 * Eliminar todos los registros donde se encuentre que
	 * la columna Comentario sea igual al valor pasado como parámetro
	 *
	 * @param String $value Comentario requerido
	 */
	public function deleteByComentario($value);

	/**
	 * Eliminar todos los registros donde se encuentre que
	 * la columna TValue sea igual al valor pasado como parámetro
	 *
	 * @param String $value TValue requerido
	 */
	public function deleteByTValue($value);

	/**
	 * Eliminar todos los registros donde se encuentre que
	 * la columna FechaCre sea igual al valor pasado como parámetro
	 *
	 * @param String $value FechaCre requerido
	 */
	public function deleteByFechaCrea($value);

	/**
	 * Eliminar todos los registros donde se encuentre que
	 * la columna FechaModif sea igual al valor pasado como parámetro
	 *
	 * @param String $value FechaModif requerido
	 */
	public function deleteByFechaModif($value);

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
}
