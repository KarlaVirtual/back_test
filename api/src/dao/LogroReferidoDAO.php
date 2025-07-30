<?php

namespace Backend\dao;

use Backend\dto\LogroReferido;

/** Interfaz de la tabla
 * @autor David Torres <david.torres@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface LogroReferidoDAO
{
    /**
     * Obtener el registro condicionado por la
     * llave primaria que se pasa como parámetro
     *
     * @param String $logroreferidoId llave primaria
     */
    public function load($logroreferidoId);


    /**
     * Obtener todos los registros de la tabla logro_referido
     */
    public function queryAll();


    /**
     * Obtener todos los registros de la tabla logro_referido
     * ordenadas por el nombre de la columna que se pasa como parámetro
     *
     * @param String $orderColumn nombre de la columna
     */
    public function queryAllOrderBy($orderColumn);


    /**
     * Insertar un registro en la tabla logro_referido
     *
     * @param LogroReferido $LogroReferido
     */
    public function insert(LogroReferido $LogroReferido);


    /**
     * Editar un registro en la tabla logro_referido
     *
     * @param LogroReferido $LogroReferido
     */
    public function update(LogroReferido $LogroReferido);


    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param int $logroreferidoId Llave primaria
     */
    public function delete($logroreferidoId);
}