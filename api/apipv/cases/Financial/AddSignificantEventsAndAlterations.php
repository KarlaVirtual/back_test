<?php

use Backend\dto\AuditoriaGeneral;
use Backend\mysql\AuditoriaGeneralMySqlDAO;

/**
 * Registra eventos o alteraciones en el sistema.
 *
 * @param string $Date :       Descripción: Fecha del evento o alteración, en formato Unix timestamp. Será convertido a formato `Y-m-d H:i:s`.
 * @param int $Users :         Descripción: Identificador único del usuario que autoriza la alteración o evento.
 * @param string $ValueBefore : Descripción: Valor antes de la alteración.
 * @param string $ValueAfter :  Descripción: Valor después de la alteración.
 * @param string $ReasonDescription : Descripción: Razón o motivo de la alteración o evento.
 *
 * @Description Este recurso permite registrar eventos o alteraciones en el sistema, incluyendo información sobre los valores antes y después del evento, el usuario responsable y otros datos relacionados.
 *
 * @return object $response Objeto con los atributos de respuesta requeridos.
 *
 * El objeto $response es un array con los siguientes atributos:
 * - *HasError* (bool): Indica si hubo un error en la operación.
 * - *AlertType* (string): Especifica el tipo de alerta que se mostrará en la vista.
 * - *AlertMessage* (string): Contiene el mensaje que se mostrará en la vista.
 * - *ModelErrors* (array): Lista de errores de validación del modelo.
 */

//Fecha de la alteracion o el evento

/* Código que asigna valores de parámetros a variables para un registro de alteración. */
$date = $params->Date;
//Id del usuario que autoriza la alteracion o el evento
$userId = $params->Users;
//Valor antes de la alteracion
$valueBefore = $params->ValueBeforeAlteration;
//Valor despues de la alteracion
$valueAfter = $params->ValueAfterAlteration;
//Razon de la alteracion o el evento

/* obtiene una razón, formatea una fecha y extrae una dirección IP. */
$reason = $params->ReasonDescription;

//formatear el date que esta en unix a timestamp
$date = date("Y-m-d H:i:s", ($date / 1000));
$ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
$ip = explode(",", $ip)[0];


/* Se crea una auditoría general con datos de usuario y valores antes y después. */
$AuditoriaGeneral = new AuditoriaGeneral();
$AuditoriaGeneral->setUsuarioId($_SESSION['usuario']);
$AuditoriaGeneral->setValorAntes($valueBefore);
$AuditoriaGeneral->setValorDespues($valueAfter);
$AuditoriaGeneral->setObservacion($reason);
$AuditoriaGeneral->setUsuarioaprobarId(0);

/* Configura parámetros de auditoría, como usuario, IP, tipo y estado de eventos. */
$AuditoriaGeneral->setUsuariosolicitaId($userId);
$AuditoriaGeneral->setUsuariosolicitaIp($ip);
$AuditoriaGeneral->setUsuarioIp('0');
$AuditoriaGeneral->setUsuarioaprobarIp('0');
$AuditoriaGeneral->setTipo('EVENTOSYALTERACIONES');
$AuditoriaGeneral->setEstado('0');

/* Asignación de variables para registrar auditoría, incluyendo usuario y configuración del sistema. */
$AuditoriaGeneral->setUsucreaId($_SESSION['usuario']);
$AuditoriaGeneral->setUsumodifId('0');
$AuditoriaGeneral->setDispositivo('0');
$AuditoriaGeneral->setSoperativo('0');
$AuditoriaGeneral->setSversion('0');
$AuditoriaGeneral->setImagen('0');

/* Inserta datos de auditoría en MySQL utilizando la clase AuditoriaGeneral. */
$AuditoriaGeneral->setData('0');
$AuditoriaGeneral->setCampo($date);
$AuditoriaGeneral->setEstado('A');

$AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO();
$AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);

/* confirma transacciones y establece una respuesta de éxito sin errores. */
$AuditoriaGeneralMySqlDAO->getTransaction()->commit();

$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = '';
$response["ModelErrors"] = [];