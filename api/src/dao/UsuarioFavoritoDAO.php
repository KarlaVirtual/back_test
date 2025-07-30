<?php

namespace Backend\dao;
use Backend\dto\UsuarioFavorito;


/**
 * Interfaz para el modelo o tabla 'UsuarioFavorito'.
 *
 * @author David Torres <david.torres@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface UsuarioFavoritoDAO {

    /**
     * Obtener el registro condicionado por la
     * llave primaria que se pasa como parámetro
     *
     * @param mixed $usufavoritoId llave primaria
     */
    public function load($usufavoritoId);


    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param mixed $usufavoritoId llave primaria
     */
    public function delete($usufavoritoId);


    /**
     * Insertar un registro en la base de datos
     *
     * @param UsuarioFavorito $UsuarioFavorito
     */
    public function insert(UsuarioFavorito $UsuarioFavorito);


    /**
     * Editar un registro en la base de datos
     *
     * @param UsuarioFavorito $UsuarioFavorito
     */
    public function update(UsuarioFavorito $UsuarioFavorito);
}
?>