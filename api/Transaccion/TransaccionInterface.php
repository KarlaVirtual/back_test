<?php namespace mKomorowski\Betfair\Transaccion;
/**
 * Interfaz para el modelo o tabla 'Transaccion'.
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 */
interface TransaccionInterface
{
	/**
	 * Obtener una transacción por medio de su id
	 *
	 * @param String $id llave primaria
	 */
    public function get($id);
}