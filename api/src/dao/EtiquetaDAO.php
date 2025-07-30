<?php namespace Backend\dao;

/**
 * Interfaz para el modelo o tabla 'Etiqueta'.
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface EtiquetaDAO {

    /**
     * Obtener el registro condicionado por la
     * llave primaria que se pasa como parámetro.
     *
     * @param int $id Llave primaria
     */
    public function load($id);

    /**
     * Obtener todos los registros de la base de datos.
     */
    public function queryAll();

    /**
     * Obtener todos los registros
     * ordenadas por el nombre de la columna
     * que se pasa como parámetro.
     *
     * @param string $orderColumn Nombre de la columna
     */
    public function queryAllOrderBy($orderColumn);

    /**
     * Eliminar un registro por su llave primaria.
     *
     * @param int $etiqueta_id Llave primaria
     */
    public function delete($etiqueta_id);

    /**
     * Insertar un registro en la base de datos.
     *
     * @param string $nombre Nombre del etiqueta
     */
    public function insert($etiqueta);

    /**
     * Editar un registro en la base de datos.
     *
     * @param int $etiqueta_id Llave primaria del etiqueta
     * @param string $nombre Nuevo nombre del etiqueta
     */
    public function update($etiqueta);

    /**
     * Eliminar todos los registros de la base de datos.
     */
    public function clean();

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna Nombre sea igual al valor pasado como parámetro.
     *
     * @param string $value Nombre requerido
     */
    public function queryByNombre($value);

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna Nombre sea igual al valor pasado como parámetro.
     *
     * @param string $value Nombre requerido
     */
    public function deleteByNombre($value);
}
?>

