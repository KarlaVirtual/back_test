<?php

use Backend\dto\DocumentoUsuario;
use Backend\dto\Descarga;
use Backend\dto\DescargaVersion;
use Backend\dto\UsuarioLog;
use Backend\mysql\DescargaMySqlDAO;
use Backend\mysql\DescargaVersionMySqlDAO;
use Backend\mysql\DocumentoUsuarioMySqlDAO;
use Backend\mysql\UsuarioLogMySqlDAO;

/**
 * BetShop/approvedDocumentByBetShop
 *
 * Aprueba un documento asociado a un usuario y registra la acción en el sistema.
 *
 * @param object $params Objeto que contiene los parámetros necesarios:
 * @param int $param ->DocumentId ID del documento a aprobar.
 * @param string $param ->Version Versión del documento.
 *
 * @return array $response Respuesta en formato JSON:
 *                         - HasError (boolean): Indica si ocurrió un error.
 *                         - AlertType (string): Tipo de alerta.
 *                         - AlertMessage (string): Mensaje de alerta.
 *                         - ModelErrors (array): Lista de errores del modelo.
 *
 * @throws Exception Si ocurre un error en la transacción de la base de datos.
 */


/* Asigna identificador, versión y estado; obtiene dirección IP del usuario. */
$documentId = $params->DocumentId;

$version = $params->Version;
$state = "A";

$ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];

/* procesa una IP y asigna valores a un objeto DocumentoUsuario. */
$ip = explode(",", $ip)[0];

$DocumentoUsuario = new DocumentoUsuario();
$DocumentoUsuario->setUsuarioId($_SESSION["usuario"]);
$DocumentoUsuario->setDocumentoId($documentId);
$DocumentoUsuario->setVersion($version);

/* establece el estado de aprobación y registra un documento en la base de datos. */
$DocumentoUsuario->setEstadoAprobacion($state);

$DocumentoUsuarioMySqlDAO = new DocumentoUsuarioMySqlDAO();
$DocumentoUsuarioMySqlDAO->insert($DocumentoUsuario);
$DocumentoUsuarioMySqlDAO->getTransaction()->commit();

$UsuarioLog = new UsuarioLog();

/* Registro de log de usuario con datos de sesión y estado de documentación aceptada. */
$UsuarioLog->setUsuarioId($_SESSION["usuario"]);
$UsuarioLog->setUsuarioIp($ip);
$UsuarioLog->setUsuariosolicitaId($_SESSION["usuario"]);
$UsuarioLog->setTipo("DocumentAccepted");
$UsuarioLog->setEstado("A");
$UsuarioLog->setValorAntes(0);

/* Se crea un registro de usuario y se guarda en la base de datos. */
$UsuarioLog->setValorDespues($state);
$UsuarioLog->setUsucreaId(0);
$UsuarioLog->setUsumodifId(0);

$UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
$UsuarioLogMySqlDAO->insert($UsuarioLog);

/* Código para confirmar transacción y preparar respuesta sin errores en formato JSON. */
$UsuarioLogMySqlDAO->getTransaction()->commit();

$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];