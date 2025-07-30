<?php

namespace Backend\dao;
use Backend\dto\ReferidoInvitacion;


/**
 * @author David Torres <david.torres@virutalsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface ReferidoInvitacionDAO
{
    /**
     *  Obtener el registro condicionado por la
     *  llave primaria que se pasa como parámetro.
     *
     * @param int
     */
    function load($id);

    /**
     * Obtener todos los registros de la base de datos
     */
    function queryAll();

    /**
     *  Obtener todos los registros
     *  ordenadas por el nombre de la columna
     *  que se pasa como parámetro
     *
     *@param string Nombre de la columna
     */
    function queryAllOrderBy($orderColumn);

    /**
     * Insertar un registro en la base de datos
     *
     * @param ReferidoInvitacion Clase DTO ReferidoInvitacion
     * @return int id del registro insertado
     */
    function insert(ReferidoInvitacion $ReferidoInvitacion);

    /**
     *Edita un registro en la base de datos
     *
     *@param ReferidoInvitacion Clase DTO ReferidoInvitacion
     *@return mixed
     */
    function update(ReferidoInvitacion $ReferidoInvitacion);

    /**
     *  Eliminar todos los registros condicionados
     *  por la llave primaria
     *
     * @param int $refinvitacionId
     * @return mixed
     */
    function delete($refinvitacionId);
}