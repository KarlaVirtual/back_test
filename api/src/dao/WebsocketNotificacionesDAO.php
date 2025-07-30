<?php

namespace Backend\dao;

/**
 * Interfaz para el modelo o tabla 'websocket_notificaciones'.
 *
 * @author David Alvarez <juan.alvarez@virtualsoft.tech>
 */
interface WebsocketNotificacionesDAO
{

	/**
	 * Obtener el registro condicionado por la llave primaria que se pasa como parámetro
	 * @param string $id llave primaria
	 */
	public function load($id);

	/**
	 * Insertar un registro en la base de datos
	 * @param WebsocketNotificacion $websocketNotificacion
	 */
	public function insert($websocketNotificacion);

	/**
	 * Editar un registro en la base de datos
	 * @param WebsocketNotificacion $websocketNotificacion
	 */
	public function update($websocketNotificacion);
}
