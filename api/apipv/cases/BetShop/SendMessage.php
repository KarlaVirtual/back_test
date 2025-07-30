<?php

use Backend\dto\UsuarioMensaje;
use Backend\dto\UsuarioPerfil;
use Backend\mysql\UsuarioMandanteMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
/**
 * BetShop/SendMessage
 *
 * Envía un mensaje a uno o varios usuarios, verificando permisos y validando datos.
 *
 * @param object $params Objeto que contiene los parámetros necesarios:
 * @param string $params ->DateExpiration Fecha de expiración del mensaje.
 * @param string $params ->ClientId IDs de los clientes separados por comas.
 * @param string $params ->UserNetwork Red de usuarios (0, 1, 2).
 * @param string $params ->Title Título del mensaje.
 * @param int $params ->Type Tipo de mensaje (0: MENSAJE, 1: POPUP).
 * @param string $params ->Message Contenido del mensaje.
 * @param string|null $params ->CountrySelect País seleccionado.
 *
 *
 *
 * @return array $response Respuesta en formato JSON:
 *                         - HasError (boolean): Indica si ocurrió un error.
 *                         - AlertType (string): Tipo de alerta.
 *                         - AlertMessage (string): Mensaje de alerta.
 *                         - ModelErrors (array): Lista de errores del modelo.
 *
 * @throws Exception Si el usuario no tiene permisos para enviar mensajes.
 */


/* verifica permisos de usuario antes de procesar una solicitud. */
$ConfigurationEnvironment = new \Backend\dto\ConfigurationEnvironment();
$permission = $ConfigurationEnvironment->checkUserPermission('BetShop/SendMessage', $_SESSION['win_perfil'], $_SESSION['usuario'], 'messageInternal');

if (!$permission) {
    $permission = $ConfigurationEnvironment->checkUserPermission('BetShop/SendMessage', $_SESSION['win_perfil'], $_SESSION['usuario'], 'messageInternalAgent');
    if (!$permission) throw new Exception('Permiso denegado', 100035);

}

$params = file_get_contents('php://input');

/* Decodifica y deserializa parámetros JSON, extrayendo datos específicos para validación. */
$params = base64_decode($params);
$params = json_decode($params);

$DateExpiration = $params->DateExpiration;
$ClientId = $params->ClientId;
$UserNetwork = in_array($params->UserNetwork, ['0', '1', '2']) ? $params->UserNetwork : '';

/* No se proporcionó ningún código para explicar. Por favor, envíalo para ayudarte. */
$Title = $params->Title;
$Type = in_array($params->Type, ['0', '1']) ? $params->Type : 0;
$Message = str_replace('"', '\'', $params->Message);
$CountrySelect = $params->CountrySelect;
$Country = $CountrySelect ?? $_SESSION['PaisCondS'];
$partner = $_SESSION['mandante'];

    // Formateo de la fecha de expiración si se proporciona
    if(!empty($DateExpiration)) $DateExpiration = date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $DateExpiration)));

    // Ajustar el país si corresponde a ciertas condiciones
    if($_SESSION['PaisCond'] === 'S') $Country = $_SESSION['PaisCondS'] ?: $Country;

// Validación del ClientId y creación de mensajes
if (!empty($ClientId)) {
    $UsuarioMensaje = new UsuarioMensaje();
    $ClientId = explode(',', $ClientId);
    $UsuarioMandanteMySqlDAO = new UsuarioMandanteMySqlDAO();
    foreach ($ClientId as $value) {
        try {
            $value = trim($value, ' ');
            if (!empty($value)) {
                $Usuario = (object)$UsuarioMandanteMySqlDAO->queryByUsuarioMandante($value)[0] ?: '';

                if (empty($Usuario->usumandanteId)) throw new Exception("No existe", '01');

                $UsuarioPerfil = new UsuarioPerfil($Usuario->usuarioMandante);

                if (in_array($UsuarioPerfil->perfilId, ['PUNTOVENTA', 'CONCESIONARIO', 'CONCESIONARIO2']) && $Usuario->paisId == $Country) {
                    /*Almacenamiento de mensaje para usuario indicado*/
                    $UsuarioMensaje->setUsufromId($_SESSION['usuario2']);
                    $UsuarioMensaje->setUsutoId($Usuario->usumandanteId);
                    $UsuarioMensaje->setIsRead(0);
                    $UsuarioMensaje->setMsubject($Title);
                    $UsuarioMensaje->setBody(html_entity_decode($Message));
                    $UsuarioMensaje->setParentId(0);
                    $UsuarioMensaje->setUsucreaId($_SESSION['usuario2']);
                    $UsuarioMensaje->setUsumodifId(0);
                    $UsuarioMensaje->setTipo($Type == 0 ? 'MENSAJE' : 'POPUP');
                    $UsuarioMensaje->setExternoId(0);
                    $UsuarioMensaje->setProveedorId($partner);
                    $UsuarioMensaje->setPaisId($Country);
                    $UsuarioMensaje->setFechaExpiracion($DateExpiration);
                    $UsuarioMensaje->setValor1('');
                    $UsuarioMensaje->setValor2('');
                    $UsuarioMensaje->setValor3('');

                    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                    $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                    $UsuarioMensajeMySqlDAO->getTransaction()->commit();
                }
            }
        } catch (Exception $ex) {
        }
    }
}

if ($UserNetwork !== '') {
    /*Definición rol del usuario objetivo*/
    switch ($UserNetwork) {
        case 0:
            $UserNetwork = 'PUNTOVENTA';
            break;
        case 1:
            $UserNetwork = 'CONCESIONARIO';
            break;
        case 2:
            $UserNetwork = 'CONCESIONARIO2';
            break;
    }
    $UsuarioMensaje = new UsuarioMensaje();
    $UsuarioMensaje->setUsufromId($_SESSION['usuario2']);
    $UsuarioMensaje->setUsutoId(-1);
    $UsuarioMensaje->setIsRead(0);
    $UsuarioMensaje->setMsubject($Title);
    $UsuarioMensaje->setBody(html_entity_decode($Message));
    $UsuarioMensaje->setParentId(0);
    $UsuarioMensaje->setUsucreaId($_SESSION['usuario2']);
    $UsuarioMensaje->setUsumodifId(0);
    $UsuarioMensaje->setTipo($Type == 0 ? 'MENSAJE' : 'POPUP');
    $UsuarioMensaje->setExternoId(0);
    $UsuarioMensaje->setProveedorId($partner);
    $UsuarioMensaje->setPaisId($Country);
    $UsuarioMensaje->setFechaExpiracion($DateExpiration);
    $UsuarioMensaje->setValor1($UserNetwork);
    $UsuarioMensaje->setValor2('');
    $UsuarioMensaje->setValor3('');

    /*Inserción de mensaje en base de datos*/
    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
    $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
    $UsuarioMensajeMySqlDAO->getTransaction()->commit();
}
$response['HasError'] = false;
$response['AlertType'] = 'Success';
$response['AlertMessage'] = '';
$response['ModelErrors'] = [];


?>