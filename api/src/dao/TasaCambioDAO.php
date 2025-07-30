<?php 

namespace Backend\dao;

/**
 * Interfaz para el modelo o tabla 'tasa_cambio'.
 * 
 * @author David Alvarez <juan.alvarez@virtualsoft.tech>
 * @since 2025/02/04
 */

interface TasaCambioDAO{

	/**
	 * Obtener el registro condicionado por la 
	 * llave primaria que se pasa como parámetro
	 *
	 * @param int $id llave primaria
	 */
	public function load($id);

	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param TasaCambio $tasaCambio
 	 */
	public function insert($tasaCambio);
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param TasaCambio $tasaCambio
 	 */
	public function update($tasaCambio);	
}
?>