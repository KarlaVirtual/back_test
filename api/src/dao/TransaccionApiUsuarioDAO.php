<?php namespace Backend\dao;
/**
 * Intreface DAO
 *
 * @author: http://phpdao.com
 * @date: 2017-09-15 04:48
 * @category    No
 * @package     No
 * @author      Desconocido
 * @version     1.0
 */
interface TransaccionApiUsuarioDAO
{

    /**
     * Get Domain object by primry key
     *
     * @param String $id primary key
     * @Return TransaccionApiUsuario 
     */
    public function load($id);

    /**
     * Get all records from table
     */
    public function queryAll();

    /**
     * Get all records from table ordered by field
     * @Param $orderColumn column name
     */
    public function queryAllOrderBy($orderColumn);

    /**
     * Delete record from table
     * @param transaccionApi primary key
     */
    public function delete($transapi_id);

    /**
     * Insert record to table
     *
     * @param TransaccionApiUsuario transaccionApi
     */
    public function insert($transaccionApi);

    /**
     * Update record in table
     *
     * @param TransaccionApiUsuario transaccionApi
     */
    public function update($transaccionApi);

    /**
     * Delete all rows
     */
    public function clean();

    /** Solicita los proveedores vinculados al ID entregado
     *@param mixed $value ProveedorId
     */
    public function queryByProveedorId($value);


    /** Solicita los proveedores vinculados al usuario entregado
     *@param mixed $value UsuarioId
     */
    public function queryByUsuarioId($value);

    /** Solicita los proveedores vinculados al productoId entregado
     *@param mixed $value ProductoId
     */
    public function queryByProductoId($value);

    /** Solicita los proveedores vinculados a las transacción entregada
     *@param mixed $value TransaccionId
     */
    public function queryByTransaccionId($value);

    /** Solicita los proveedores vinculados al tipo entregado
     *@param mixed $value Tipo
     */
    public function queryByTipo($value);

    /** Solicita los proveedores vinculados al UsucreaId entregado
     *@param mixed $value UsucreaId
     */
    public function queryByUsucreaId($value);

    /** Solicita los proveedores vinculados a la fechaCrea entregada
     *@param mixed $value FechaCrea
     */
    public function queryByFechaCrea($value);

    /** Solicita los proveedores vinculados al usumodifId entregado
     *@param mixed $value UsumodifId
     */
    public function queryByUsumodifId($value);

    /** Solicita los proveedores vinculados a la fechaModif entregada
     *@param mixed $value FechaModif
     */
    public function queryByFechaModif($value);

    /** Elimina los proveedores vinculados al proveedorId entregado
     *@param mixed $value ProveedorId
     */
    public function deleteByProveedorId($value);

    /** Elimina los proveedores vinculados al proveedorId entregado
     *@param mixed $value ProveedorId
     */
    public function deleteByUsuarioId($value);

    /** Elimina los proveedores vinculados al productoId entregado
     *@param mixed $value ProductoId
     */
    public function deleteByProductoId($value);

    /** Elimina los proveedores vinculados a la transaccionId entregada
     *@param mixed $value TransaccionId
     */
    public function deleteByTransaccionId($value);

    /** Elimina los proveedores vinculados al tipo entregado
     *@param mixed $value tipo
     */
    public function deleteByTipo($value);

    /** Elimina los proveedores vinculados al usucreaId entregado
     *@param mixed $value UsucreaId
     */
    public function deleteByUsucreaId($value);

    /** Elimina los proveedores vinculados a la fechaCrea entregada
     *@param mixed $value fechaCrea
     */
    public function deleteByFechaCrea($value);

    /** Elimina los proveedores vinculados al usumodifId entregado
     *@param mixed $value UsumodifId
     */
    public function deleteByUsumodifId($value);

    /** Elimina los proveedores vinculados a la fechaModif entregada
     *@param mixed $value FechaModif
     */
    public function deleteByFechaModif($value);


}
?>