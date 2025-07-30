<?php

use Backend\dto\Clasificador;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioConfiguracion;
use Backend\mysql\UsuarioConfiguracionMySqlDAO;
use Backend\sql\Transaction;

/**
 * Este script guarda la configuración del tema de interfaz (claro u oscuro) para un usuario.
 *
 * @param object $params Objeto que contiene los parámetros enviados en la solicitud:
 * @param int    $params ->choicedTheme: Valor del tema elegido. Valores aceptados: 1 (claro), 2 (oscuro).
 *
 * @return array $response Respuesta de la operación:
 *                         - $response["code"] (int): Código de estado de la operación (0 para éxito).
 *                         - $response["data"] (array): Datos adicionales (vacío en este caso).
 *                         - $response["rid"] (mixed): Identificador de la solicitud.
 *
 * @throws Exception Si los parámetros enviados no son válidos (código 100001).
 * @throws Exception Si no existe la configuración previa del usuario (código 46).
 */


/*Obtención y sanitización de parámetros*/
$params = $json->params;
$choicedTheme = $params->choicedTheme;
$userId = $json->session->usuario;

$acceptedValues = [1,2];
if (!in_array($choicedTheme, $acceptedValues)) {
    throw new Exception("Error en los parametros enviados", 100001);
}

/*Obtención data del usuario solicitante y de la configuración a realizar*/
$UsuarioMandante = new UsuarioMandante($userId);
$Clasificador = new Clasificador(null, "FAVORITEINTERFACETHEME");
$Transaction = new Transaction();
$UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);
try {
    /*Obtención preferencias previas  para el tema de la interfaz*/
    $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioMandante->usuarioMandante, "A", $Clasificador->clasificadorId);
    $currentSetting = match (strtolower($UsuarioConfiguracion->getValor())) {
        'claro' => 1,
        'oscuro' => 2,
        default => 0
    };

    if ($currentSetting != $choicedTheme) {
        /*Apagado de la configuración anterior*/
        $UsuarioConfiguracion->estado = "I";
        $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);
        throw new Exception("No existe " . UsuarioConfiguracion::class, "46");
    }
} catch (Exception $e) {
    if ($e->getCode() != 46) throw $e;

    /*Almacenamiento de una nueva configuración*/
    $UsuarioConfiguracion = new UsuarioConfiguracion();
    $UsuarioConfiguracion->usuarioId = $UsuarioMandante->usuarioMandante;
    $UsuarioConfiguracion->tipo = $Clasificador->clasificadorId;
    $UsuarioConfiguracion->valor = match (intval($choicedTheme)) {
        1 => 'claro',
        2 => 'oscuro',
        default => 0
    };
    $UsuarioConfiguracion->estado = "A";
    $UsuarioConfiguracion->nota = "";

    $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);

    /*Confirmación de los cambios*/
    $Transaction->commit();
}


$response["code"] = 0;
$response["data"] = [];
$response["rid"] = $json->rid;
