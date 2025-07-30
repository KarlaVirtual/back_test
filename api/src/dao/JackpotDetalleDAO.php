<?php
namespace Backend\dao;

use Backend\dto\JackpotDetalle;
use Backend\dto\UsuarioFavorito;

/**
 * Interfaz para el modelo o tabla 'jackpot_detalle'.
 *
 * @author David Torres <david.torres@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface JackpotDetalleDAO {
    /**
     * Obtener el registro condicionado por la
     * llave primaria que se pasa como parámetro
     *
     *@param int $jackpotDetalleId Autoincremental del detalle solicitado
     */
    public function load($jackpotDetalleId);


    /**
     * Insertar un registro en la base de datos
     *
     * @param JackpotDetalle $JackpotDetalle
     */
    public function insert(JackpotDetalle $JackpotDetalle);


    /**
     * Editar un registro en la base de datos
     *
     * @param JackpotDetalle $JackpotDetalle
     */
    public function update (JackpotDetalle $JackpotDetalle);
}
?>