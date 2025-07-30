<?php

use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioNota;
use Backend\mysql\UsuarioNotaMySqlDAO;

/**
 * CreateNote
 *
 * Crea una nota asociada a un cliente.
 *
 * @param object $params Objeto que contiene los parámetros necesarios:
 * @param string $params ->Type Tipo de nota.
 * @param string $params ->Description Descripción de la nota.
 * @param int $params ->UserTo Identificador del usuario destinatario.
 * @param int|null $params ->ReferenceId Identificador de referencia (opcional).
 *
 *
 * @return array $response Respuesta con los siguientes valores:
 * - "HasError" (boolean): Indica si ocurrió un error.
 * - "AlertType" (string): Tipo de alerta (e.g., "success", "error").
 * - "AlertMessage" (string): Mensaje de alerta.
 * - "ModelErrors" (array): Lista de errores del modelo, si los hay.
 *
 * @throws Exception Si ocurre un error al crear la nota.
 */

/* asigna parámetros a variables y inicializa una respuesta vacía. */
$Type = $params->Type;
$Description = $params->Description;
$UserTo = $params->UserTo;
$ReferenceId = $params->ReferenceId ?: 0;

$response = [];


$response['HasError'] = false;
$response['AlertType'] = 'success';
$response['AlertMessage'] = '';
$response['ModelErrors'] = [];

try {
    // Crear una instancia de UsuarioMandante
    $UsuarioMandante = new UsuarioMandante('', $UserTo, $_SESSION['mandante']);

    // Verificar si el mandante coincide con el de la sesión
    if ($_SESSION['mandante'] != $UsuarioMandante->mandante) throw new Exception('Partner error', 14);

    // Crear una instancia de UsuarioNota y establecer sus propiedades
    $UsuarioNota = new UsuarioNota();
    $UsuarioNota->setTipo($Type);
    $UsuarioNota->setDescripcion($Description);
    $UsuarioNota->setUsufromId($_SESSION['usuario2']);
    $UsuarioNota->setUsutoId($UsuarioMandante->usumandanteId);
    $UsuarioNota->setMandante($UsuarioMandante->mandante);
    $UsuarioNota->setPaisId($UsuarioMandante->paisId);
    $UsuarioNota->setRefId($ReferenceId);
    $UsuarioNota->setUsucreaId($_SESSION['usuario']);

    // Crear un objeto DAO para manipular UsuarioNota en la base de datos
    $UsuarioNotaMySqlDAO = new UsuarioNotaMySqlDAO();
    $UsuarioNotaMySqlDAO->insert($UsuarioNota);
    $UsuarioNotaMySqlDAO->getTransaction()->commit();

} catch (Exception $ex) {
    // Capturar excepciones y actualizar la respuesta en caso de error
    print_r($ex);
    $response['HasError'] = true;
    $response['AlertType'] = 'error';
}
?>