<?php

use Backend\dto\ConfigurationEnvironment;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioMensaje;
use Backend\dto\UsuarioMensajecampana;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\UsuarioMensajecampanaMySqlDAO;

/**
 * Client/SendNotification
 *
 * Procesa la solicitud de campaña y mensaje para diferentes usuarios, insertando los datos en la base de datos y enviando notificaciones.
 *
 * Este recurso maneja los datos recibidos en formato JSON, asignando valores a las propiedades de los objetos de campaña y mensaje.
 * A continuación, inserta los datos correspondientes en la base de datos y realiza transacciones para garantizar la integridad de los datos.
 * También maneja la validación de los clientes y el envío de notificaciones de acuerdo al tipo de mensaje (notificación de escritorio o push).
 *
 * @param string $params : Datos JSON recibidos a través de `php://input`, que son decodificados y procesados para insertar en la base de datos.
 *
 * El objeto `$response` es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error durante el proceso de inserción o envío de notificaciones.
 *  - *AlertType* (string): Tipo de alerta que se mostrará en la vista (success, danger, etc.).
 *  - *AlertMessage* (string): Mensaje que se mostrará al usuario en caso de éxito o error.
 *  - *ModelErrors* (array): Errores específicos del modelo, en caso de existir.
 *  - *Data* (array): Datos adicionales relacionados con el proceso.
 *
 * Ejemplo de respuesta en caso de error:
 *
 * $response["HasError"] = true;
 * $response["AlertType"] = "danger";
 * $response["AlertMessage"] = "[Mensaje de error]";
 * $response["ModelErrors"] = [];
 *
 * @throws Exception Si ocurre un error durante el proceso de inserción de datos o el envío de notificaciones.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* obtiene y decodifica datos JSON del cuerpo de una solicitud HTTP. */
$params = file_get_contents('php://input');
$params = json_decode($params);

$Title = $params->Title;
$Name = $params->Name;
$CountrySelect = $params->CountrySelect;

/* asigna fechas y procesa identificadores de cliente a partir de parámetros. */
$DateFrom = !empty($params->DateFrom) ? date('Y-m-d H:i:s', $params->DateFrom) : date('Y-m-d H:i:s');
$DateExpiration = !empty($params->DateExpiration) ? date('Y-m-d H:i:s', $params->DateExpiration) : date('Y-m-d H:i:s');
$T_value = $params->T_Value;
$ClientIdCsv = $params->ClientIdCsv;
$ClientIdCsv = explode(',', $ClientIdCsv)[1];
$ClientId = trim($params->ClientId . ',' . base64_decode($ClientIdCsv), ',');

/* asigna valores a variables basadas en parámetros y procesa identificadores de clientes. */
$Message = $params->Message;
$Description = $params->Description;
$Type = $params->Type == 1 ? 'DESKTOPNOTIFICACION' : 'PUSHNOTIFICACION';

$Clients = explode(',', $ClientId);

$UsuToId = oldCount($Clients) == 0 || (oldCount($Clients) == 1 && $Clients[0] == 0) ? 0 : -1;


/* Código que configura un entorno y selecciona usuario basado en sesión. */
$ConfigurationEnviroment = new ConfigurationEnvironment();

if ($_SESSION['PaisCond'] == 'S') $CountrySelect = $_SESSION['pais_id'];

$UsuarioMensajecampana = new UsuarioMensajecampana();
$UsuarioMensajecampana->setUsufromId(0);

/* Asignación de propiedades a un objeto de campaña de mensajes basado en condiciones. */
$UsuarioMensajecampana->setUsutoId($UsuToId);
$UsuarioMensajecampana->setIsRead($Type === 'DESKTOPNOTIFICACION' ? 1 : 0);
$UsuarioMensajecampana->setMsubject('');
$UsuarioMensajecampana->setBody(json_encode($Message));
$UsuarioMensajecampana->setParentId(0);
$UsuarioMensajecampana->setUsucreaId($_SESSION['usuario2']);

/* Configura propiedades de un objeto UsuarioMensajecampana con valores específicos. */
$UsuarioMensajecampana->setUsumodifId(0);
$UsuarioMensajecampana->setTipo($Type);
$UsuarioMensajecampana->setExternoId(0);
$UsuarioMensajecampana->setProveedorId(0);
$UsuarioMensajecampana->setPaisId($CountrySelect ?: 0);
$UsuarioMensajecampana->setFechaExpiracion($DateExpiration);

/* Código para establecer propiedades de un objeto de campaña de mensajería. */
$UsuarioMensajecampana->setNombre($Name);
$UsuarioMensajecampana->setDescripcion($Description);
$UsuarioMensajecampana->setT_value(!empty($T_value) ? json_encode($T_value) : '');
$UsuarioMensajecampana->setMandante($_SESSION['mandante']);
$UsuarioMensajecampana->setfechaEnvio($DateFrom);
$UsuarioMensajecampana->setEstado('A');


/* Código para insertar un mensaje en la base de datos y confirmar transacción. */
$UsuarioMensajecampanaMySqlDAO = new UsuarioMensajeMySqlDAO();

$UsuarioMensajecampanaMySqlDAO = new UsuarioMensajecampanaMySqlDAO();
$ID = $UsuarioMensajecampanaMySqlDAO->insert($UsuarioMensajecampana);
$UsuarioMensajecampanaMySqlDAO->getTransaction()->commit();

$Users = [];

if ($UsuToId === 0) {

    /* Crea un objeto UsuarioMensaje con atributos específicos para notificaciones. */
    $UsuarioMensaje = new UsuarioMensaje();
    $UsuarioMensaje->setUsufromId(0);
    $UsuarioMensaje->setUsutoId(-1);
    $UsuarioMensaje->setIsRead($Type === 'DESKTOPNOTIFICACION' ? 1 : 0);
    $UsuarioMensaje->setMsubject($Title);
    $UsuarioMensaje->setBody(json_encode($Message));

    /* Configura propiedades de un mensaje de usuario en una sesión PHP. */
    $UsuarioMensaje->setParentId(0);
    $UsuarioMensaje->setUsucreaId($_SESSION['usuario2']);
    $UsuarioMensaje->setUsumodifId(0);
    $UsuarioMensaje->setTipo($Type);
    $UsuarioMensaje->setExternoId(0);
    $UsuarioMensaje->setProveedorId(0);

    /* Código para establecer propiedades de un objeto UsuarioMensaje. */
    $UsuarioMensaje->setPaisId($CountrySelect);
    $UsuarioMensaje->setFechaExpiracion($DateExpiration);
    $UsuarioMensaje->setUsumencampanaId($ID);
    $UsuarioMensaje->setValor1('');
    $UsuarioMensaje->setValor2('');
    $UsuarioMensaje->setValor3('');


    /* Se inserta un mensaje de usuario en la base de datos y se obtiene la transacción. */
    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
    $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
    $UsuarioMensajeMySqlDAO->getTransaction()->commit();
} else {
    /* Instancia entidades para un proceso de mensajería */

    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
    $Transaction = $UsuarioMensajeMySqlDAO->getTransaction();
    $insertCounter = 0;

    foreach ($Clients as $key => $value) {
        try {
            $UsuarioMandante = new UsuarioMandante('', $value, $_SESSION['mandante']);

            $UsuarioMensaje = new UsuarioMensaje();
            $UsuarioMensaje->setUsufromId(0);
            $UsuarioMensaje->setUsutoId($UsuarioMandante->usumandanteId);
            $UsuarioMensaje->setIsRead($Type === 'DESKTOPNOTIFICACION' ? 1 : 0);
            $UsuarioMensaje->setMsubject($Title);
            $UsuarioMensaje->setBody(json_encode($Message));
            $UsuarioMensaje->setParentId(0);
            $UsuarioMensaje->setUsucreaId($_SESSION['usuario2']);
            $UsuarioMensaje->setUsumodifId(0);
            $UsuarioMensaje->setTipo($Type);
            $UsuarioMensaje->setProveedorId(0);
            $UsuarioMensaje->setExternoId(0);
            $UsuarioMensaje->setPaisId($CountrySelect);
            $UsuarioMensaje->setFechaExpiracion($DateExpiration);
            $UsuarioMensaje->setUsumencampanaId($ID);
            $UsuarioMensaje->setValor1('');
            $UsuarioMensaje->setValor2('');
            $UsuarioMensaje->setValor3('');

            $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($Transaction);
            $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);

            $insertCounter++;

            array_push($Users, $UsuarioMandante->getUsumandanteId());
        } catch (Exception $ex) {
        }
    }

    $sendTo = true;

    if ($Type === 'DESKTOPNOTIFICACION') $sendTo = $ConfigurationEnviroment->sendNotification($Users, $Title, $Message, $CountrySelect, $_SESSION['mandante']);
    if ($insertCounter > 0 && $sendTo) $Transaction->commit();
}


/* inicializa una respuesta con éxito y sin errores. */
$response = [];
$response['HasError'] = false;
$response['AlertType'] = 'success';
$response['AlertMessage'] = 'success';
$response['ModelErrors'] = [];
$response['Data'] = [];
?>