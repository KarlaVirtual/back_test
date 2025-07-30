<?php

use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioMensaje;

/**
 * Obtiene una lista de mensajes enviados basándose en los filtros y parámetros proporcionados.
 *
 * @param string $params JSON codificado que contiene los datos de la solicitud, incluyendo:
 * @param int $params ->Id Identificador del mensaje.
 * @param int $params ->ClientIdTo Identificador del cliente destinatario.
 * @param string $params ->Read Estado de lectura del mensaje ('0' para no leído, '1' para leído).
 * @param string $params ->DateFrom Fecha de inicio para filtrar los mensajes (formato 'Y-m-d').
 * @param string $params ->DateTo Fecha de fin para filtrar los mensajes (formato 'Y-m-d').
 * @param string $params ->Title Título del mensaje.
 * @param int $params ->start Número de registros a omitir para la paginación. Por defecto, 0.
 * @param int $params ->count Número máximo de registros a devolver. Por defecto, 10.
 * @param int $params ->Type Tipo de mensaje (0 para 'MENSAJE', 1 para 'POPUP').
 * @param int $params ->UserNetwork Red del usuario (0 para 'PUNTOVENTA', 1 para 'CONCESIONARIO', 2 para 'SUBCONCESIONARIO').
 *
 *
 * @return array $response Contiene los datos de la respuesta, incluyendo:
 *                         - HasError (bool): Indica si ocurrió un error.
 *                         - AlertType (string): Tipo de alerta ('Success' en caso de éxito).
 *                         - AlertMessage (string): Mensaje de alerta.
 *                         - ModelErrors (array): Lista de errores del modelo.
 *                         - data (array): Información de los mensajes enviados, incluyendo:
 *                             - Id (int): Identificador del mensaje.
 *                             - ClientFromId (string): Identificador y nombre del cliente remitente.
 *                             - ClientToId (string): Identificador y nombre del cliente destinatario.
 *                             - Title (string): Título del mensaje.
 *                             - Message (string): Contenido del mensaje.
 *                             - Read (string): Estado de lectura del mensaje ('0' para no leído, '1' para leído).
 *                             - Type (int): Tipo de mensaje (0 para 'MENSAJE', 1 para 'POPUP').
 *                             - DateCreation (string): Fecha de creación del mensaje.
 *                             - DateExpiration (string): Fecha de expiración del mensaje.
 *                             - UserNetwork (int|null): Red del usuario asociada al mensaje.
 *                         - pos (int): Posición inicial de los datos devueltos.
 *                         - total_count (int): Número total de registros encontrados.
 */

if (in_array($_SESSION['win_perfil'], ['ADMIN', 'ADMIN2', 'SA', 'CUSTOM', 'TIINCIDENTES', 'ACCOUNT'])) {

    /**
     * Obtiene la red correspondiente según un índice o el nombre de la red.
     *
     * @param mixed $network El índice de la red o el nombre de la red a buscar.
     * @return array La red correspondiente con las claves 'back' y 'front'.
     */
    function getNetwork($network)
    {
        $networks = [
            0 => [
                'back' => 'PUNTOVENTA',
                'front' => 'PUNTOS DE VENTA'
            ],
            1 => [
                'back' => 'CONCESIONARIO',
                'front' => 'CONCESIONARIOS'
            ],
            2 => [
                'back' => 'CONCESIONARIO2',
                'front' => 'SUBCONCESIONARIOS'
            ]
        ];
        return is_numeric($network) ? $networks[$network] : array_filter($networks, function ($item) use ($network) {
            if ($item['back'] === $network) return $item;
        });
    }

    // Obtener parámetros de la solicitud
    $Id = $_REQUEST['Id'];
    $ClientIdTo = $_REQUEST['ClientIdTo'];
    $Read = in_array($_REQUEST['Read'], ['0', '1']) ? $_REQUEST['Read'] : '';
    $DateFrom = $_REQUEST['DateFrom'];
    $DateTo = $_REQUEST['DateTo'] < $DateFrom ? $DateFrom : $_REQUEST['DateTo'];
    $Title = $_REQUEST['Title'];
    $start = $_REQUEST['start'] ?: 0;
    $count = $_REQUEST['count'] ?: 10;
    $Country = $_SESSION['pais_id'];
    $Type = in_array($_REQUEST['Type'], [0, 1]) ? $_REQUEST['Type'] : '';
    $UserNetwork = in_array($_REQUEST['UserNetwork'], [0, 1, 2]) ? $_REQUEST['UserNetwork'] : '';

    // Ajustar el país si se requiere
    if ($_SESSION['PaisCond'] === 'S') $Country = $_SESSION['PaisCondS'] ?: $Country;
    $rules = [];

    // Crear una instancia de UsuarioMandante
    $UsuarioMandante = new UsuarioMandante('', $ClientIdTo, $_SESSION['mandante']);

    // Obtener el ID del mandante
    $ClientIdTo = $UsuarioMandante->usumandanteId;

    if (!empty($Id)) array_push($rules, ['field' => 'usuario_mensaje.usumensaje_id', 'data' => $Id, 'op' => 'eq']);
    if (!empty($ClientIdTo)) array_push($rules, ['field' => 'usuario_mensaje.usuto_id', 'data' => $ClientIdTo, 'op' => 'eq']);
    if (!empty($Title)) array_push($rules, ['field' => 'usuario_mensaje.msubject', 'data' => $Title, 'op' => 'eq']);
    if (!empty($DateFrom)) array_push($rules, ['field' => 'usuario_mensaje.fecha_crea', 'data' => $DateFrom, 'op' => 'ge']);
    if (!empty($DateTo)) array_push($rules, ['field' => 'usuario_mensaje.fecha_crea', 'data' => $DateTo, 'op' => 'le']);
    if (!empty($Type)) array_push($rules, ['field' => 'usuario_mensaje.tipo', 'data' => $Type == 0 ? 'MENSAJE' : 'POPUP', 'op' => 'eq']);
    else array_push($rules, ['field' => 'usuario_mensaje.tipo', 'data' => "'MENSAJE', 'POPUP'", 'op' => 'in']);
    if ($Read != '') array_push($rules, ['field' => 'usuario_mensaje.is_read', 'data' => $Read, 'op' => 'eq']);
    if ($UserNetwork != '') array_push($rules, ['field' => 'usuario_mensaje.valor1', 'data' => getNetwork($UserNetwork)['back'], 'op' => 'eq']);

    array_push($rules, ['field' => 'usuario_mensaje.usufrom_id', 'data' => $_SESSION['usuario2'], 'op' => 'eq']);
    array_push($rules, ['field' => 'usuario_mensaje.pais_id', 'data' => $Country, 'op' => 'eq']);

    // Convertir las reglas a formato JSON
    $filter = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

    $UsuarioMensaje = new UsuarioMensaje();
    $messages = $UsuarioMensaje->getUsuarioMensajesCustom('usuario_mensaje.*, usufrom.nombres, usufrom.usuario_mandante, usuto.nombres, usuto.usuario_mandante', 'usuario_mensaje.usumensaje_id', 'asc', $start, $count, $filter, true);

    $messages = json_decode($messages, true);

    $allMessages = [];

    // Procesar cada mensaje y construir el array de salida
    foreach ($messages['data'] as $key => $value) {
        $data = [];
        $data['Id'] = $value['usuario_mensaje.usumensaje_id'];
        $data['ClientFromId'] = $value['usufrom.usuario_mandante'] . ' - ' . $value['usufrom.nombres'];
        $data['ClientToId'] = empty($value['usuario_mensaje.valor1']) ? $value['usuto.usuario_mandante'] . ' - ' . $value['usuto.nombres'] : getNetwork($value['usuario_mensaje.valor1'])[0]['front'];
        $data['Title'] = $value['usuario_mensaje.msubject'];
        $data['Message'] = $value['usuario_mensaje.body'];
        $data['Read'] = empty($value['usuario_mensaje.valor1']) ? $value['usuario_mensaje.is_read'] : '';
        $data['Type'] = $value['usuario_mensaje.tipo'] === 'MENSAJE' ? 0 : 1;
        $data['DateCreation'] = $value['usuario_mensaje.fecha_crea'];
        $data['DateExpiration'] = $value['usuario_mensaje.fecha_expiracion'];
        $data['UserNetwork'] = key(getNetwork($value['usuario_mensaje.valor1'])) !== null ? key(getNetwork($value['usuario_mensaje.valor1'])) : '';

        array_push($allMessages, $data);
    }
}


/* Código que estructura una respuesta con datos y mensajes de éxito. */
$response['HasError'] = false;
$response['AlertType'] = 'Success';
$response['AlertMessage'] = '';
$response['ModelErrors'] = [];
$response['data'] = $allMessages ?: [];
$response['pos'] = $start;

/* Asigna el valor de conteo de mensajes a total_count en el arreglo de respuesta. */
$response['total_count'] = $messages['count'][0]['.count'];
?>