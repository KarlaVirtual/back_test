<?php

namespace Backend\dao;

use Backend\dto\UsuarioReferenteResumen;

/** 
 * @author David Torres <david.torres@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface UsuarioReferenteResumenDAO
{
    /**
     * Obtener el registro condicionado por la
     * llave primaria que se pasa como parámetro
     *
     * @param String $usurefresumId llave primaria
     */
    public function load($usurefresumId);


    /**
     * Obtener todos los registros de la tabla usuario_referente_resumen
     */
    public function queryAll();


    /**
     * Obtener todos los registros de la tabla usuario_referente_resumen
     * ordenadas por el nombre de la columna que se pasa como parámetro
     *
     * @param String $orderColumn nombre de la columna
     */
    public function queryAllOrderBy($orderColumn);


    /**
     * Insertar un registro en la tabla usuario_referente_resumen
     *
     * @param UsuarioReferenteResumen $UsuarioReferenteResumen
     */
    public function insert(UsuarioReferenteResumen $UsuarioReferenteResumen);


    /**
     * Editar un registro en la tabla usuario_referente_resumen
     *
     * @param UsuarioReferenteResumen $UsuarioReferenteResumen
     */
    public function update(UsuarioReferenteResumen $UsuarioReferenteResumen);


    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param int $usurefresumId Llave primaria
     */
    public function delete($usurefresumId);
}