<?php namespace Backend\dao;
/**
 * Interfaz para el modelo o tabla 'red_blockchain'.
 *
 * @author Juan Salazar <juan.salazar@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface RedBlockchainDAO{

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
     * @param String $redblockchain_id llave primaria
     */
    public function delete($redblockchain_id);

    /**
     * Insertar un registro en la base de datos
     *
     * @param String proveedor proveedor
     */
    public function insert($redblockchain);

    /**
     * Editar un registro en la base de datos
     *
     * @param Object proveedor proveedor
     */
    public function update($redblockchain);

    /**
     * Eliminar todos los registros de la base de datos
     */
    public function clean();





    /**
     * Obtener todos los registros donde se encuentre que
     * la columna nombre sea igual al valor pasado como parámetro
     *
     * @param String $value Nombre requerido
     */
    public function queryByNombre($value);

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna codigo_red sea igual al valor pasado como parámetro
     *
     * @param String $value CodigoRed requerido
     */
    public function queryByCodigoRed($value);

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
     * la columna FechaCrea sea igual al valor pasado como parámetro
     *
     * @param String $value FechaCrea requerido
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
     * la columna Nombre sea igual al valor pasado como parámetro
     *
     * @param String $value Nombre requerido
     */
    public function deleteByNombre($value);

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna codigo_red sea igual al valor pasado como parámetro
     *
     * @param String $value CodigoRed requerido
     */
    public function deleteByCodigoRed($value);

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
     * la columna UsumodifId sea igual al valor pasado como parámetro
     *
     * @param String $value UsumodifId requerido
     */
    public function deleteByUsumodifId($value);


}
?>