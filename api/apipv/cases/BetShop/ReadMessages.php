<?php

use Backend\dto\UsuarioMensaje;
use Backend\mysql\UsuarioMensajeMySqlDAO;

/**
 * Lee los mensajes de un usuario basándose en los filtros y parámetros proporcionados.
 *
 * @param string $params JSON codificado que contiene los datos de la solicitud, incluyendo:
 * @params int $params->Id Identificador del mensaje. Si no se proporciona, se obtienen todos los mensajes.
 *
 * @return array $response Contiene los datos de la respuesta, incluyendo:
 *                         - HasError (bool): Indica si ocurrió un error.
 *                         - AlertType (string): Tipo de alerta ('Success' en caso de éxito).
 *                         - AlertMessage (string): Mensaje de alerta.
 *                         - ModelErrors (array): Lista de errores del modelo.
 *                         - Data (array): Información de los mensajes, incluyendo:
 *                             - id (int): Identificador del mensaje.
 *                             - subject (string): Asunto del mensaje.
 *                             - date (string): Fecha de creación del mensaje.
 *                             - text (string): Contenido del mensaje.
 *                             - is_read (bool): Indica si el mensaje ha sido leído.
 *
 * @throws Exception Si ocurre un error al actualizar el estado del mensaje.
 */

if (in_array($_SESSION['win_perfil'], ['PUNTOVENTA', 'CONCESIONARIO', 'CONCESIONARIO2'])) {

    /* Asignación del valor de 'Id' de 'params' a la variable '$Id'. */
    $Id = $params->Id;

    if (empty($Id)) {

        /* Define reglas para filtrar mensajes de usuario según ciertos criterios. */
        $rules = [];

        array_push($rules, ['field' => 'usuario_mensaje.usuto_id', 'data' => $_SESSION['usuario2'], 'op' => 'eq']);
        array_push($rules, ['field' => 'usuario_mensaje.pais_id', 'data' => $_SESSION['pais_id'], 'op' => 'eq']);
        array_push($rules, ['field' => 'usuario_mensaje.usuto_id', 'data' => -1, 'op' => 'ne']);
        array_push($rules, ['field' => 'usuario_mensaje.tipo', 'data' => 'MENSAJE', 'op' => 'eq']);


        /* Se obtienen mensajes de usuario aplicando un filtro JSON y ordenados por fecha. */
        $filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

        $UsuarioMensaje = new UsuarioMensaje();
        $messages = $UsuarioMensaje->getUsuarioMensajesCustom('usuario_mensaje.*, usufrom.nombres', 'usuario_mensaje.fecha_crea', 'desc', 0, 1000, $filter, true);

        $messages = json_decode($messages, true);


        /* Filtra y almacena mensajes no expirados en un arreglo estructurado. */
        $allMessages = [];

        foreach ($messages['data'] as $key => $value) {
            if ($value['usuario_mensaje.fecha_expiracion'] > date('Y-m-d H:i:s') || empty($value['usuario_mensaje.fecha_expiracion'])) {
                $data = [];
                $data['id'] = $value['usuario_mensaje.usumensaje_id'];
                $data['subject'] = $value['usuario_mensaje.msubject'];
                $data['date'] = $value['usuario_mensaje.fecha_crea'];
                $data['text'] = '<div>' . $value['usuario_mensaje.body'] . '</div>';
                $data['is_read'] = $value['usuario_mensaje.is_read'] == 1 ? true : false;
                array_push($allMessages, $data);
            }
        }
    } else {
        /* Actualiza el estado de un mensaje a leído si no ha sido leído previamente. */

        try {
            $UsuarioMensaje = new UsuarioMensaje($Id);

            if ($UsuarioMensaje->getisRead() == false) {
                $UsuarioMensaje->setUsumodifId($_SESSION['usuario2']);
                $UsuarioMensaje->setFechaModif(date('Y-m-d H:00:00'));
                $UsuarioMensaje->setIsRead(true);

                $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                $UsuarioMensajeMySqlDAO->update($UsuarioMensaje);
                $UsuarioMensajeMySqlDAO->getTransaction()->commit();
            }
        } catch (Exception $ex) {
        }
    }
}


/* configura una respuesta sin errores y lista de mensajes. */
$response['HasError'] = false;
$response['AlertType'] = 'Success';
$response['AlertMessage'] = '';
$response['ModelErrors'] = [];
$response['Data'] = $allMessages ?: [];
?>