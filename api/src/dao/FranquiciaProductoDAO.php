<?php namespace Backend\dao;
/**
 * Interfaz para el modelo o tabla 'Franquicia'.
 *
 * @author Juan Salazar <juan.salazar@virtualsoft.tech>
 * @category    No
 * @package     No
 * @author      Desconocido
 * @version     1.0
 * @since       No definida
 */
interface FranquiciaProductoDAO{

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
     * @param String $trnID llave primaria
     */
    public function delete($FranquiciaId);

    /**
     * Insertar un registro en la base de datos
     *
     * @param Object Franquicia Franquicia
     */
    public function insert($FranquiciaProducto);

    /**
     * Editar un registro en la base de datos
     *
     * @param Object Franquicia Franquicia
     */
    public function update($FranquiciaProducto);

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
    public function queryByFranquiciaId($value);

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna Estado sea igual al valor pasado como parámetro
     *
     * @param String $value Estado requerido
     */
    public function queryByEstado($value);

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
     * Obtener todos los registros donde se encuentre que
     * la columna abreviado sea igual al valor pasado como parámetro
     *
     * @param String $value abreviado requerido
     */
    public function queryByAbreviado($value);

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna Imagen sea igual al valor pasado como parámetro
     *
     * @param String $value Imagen requerido
     */
    public function queryByImagen($value);

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna Estado sea igual al valor pasado como parámetro
     *
     * @param String $value Estado requerido
     */
    public function deleteByEstado($value);

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna FechaCrea sea igual al valor pasado como parámetro
     *
     * @param String $value FechaCrea requerido
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
     * la columna UsumodifId( sea igual al valor pasado como parámetro
     *
     * @param String $value UsumodifId( requerido
     */
    public function deleteByUsumodifId($value);

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna FranquiciaId sea igual al valor pasado como parámetro
     *
     * @param String $value FranquiciaId requerido
     */
    public function deleteByFranquiciaId($value);

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna Abreviado sea igual al valor pasado como parámetro
     *
     * @param String $value Abreviado requerido
     */
    public function deleteByAbreviado($value);

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna Imagen sea igual al valor pasado como parámetro
     *
     * @param String $value Abreviado requerido
     */
    public function deleteByImagen($value);
}
?>