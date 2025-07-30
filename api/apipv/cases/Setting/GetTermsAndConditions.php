<?php

use Backend\dto\Pais;
use Backend\dto\ConfigMandante;


/**
 * Obtener términos y condiciones.
 *
 * Este script configura y devuelve los términos y condiciones según el país e idioma del usuario.
 *
 * @param object $params No se utilizan parámetros directos, pero se accede a las variables de sesión:
 *
 * @return array Respuesta estructurada:
 *  - HasError: boolean Indica si ocurrió un error.
 *  - AlertType: string Tipo de alerta.
 *  - AlertMessage: string Mensaje de alerta.
 *  - ModelErrors: array Errores del modelo.
 *  - Data: array Datos con los términos y condiciones.
 *
 * @throws Exception Si ocurre un error al procesar los términos y condiciones.
 */

/* configura términos y condiciones según el país e idioma del usuario. */
try {
    $ConfigMandante = new ConfigMandante('', $_SESSION['mandante']);
    $Pais = new Pais($_SESSION['PaisCond'] === 'N' ? $_SESSION['PaisCondS'] : $_SESSION['pais_id']);
    $search = ['u00c1', 'u00e1', 'u00c9', 'u00e9', 'u00cd', 'u00ed', 'u00d3', 'u00f3', 'u00da', 'u00fa', 'u00d1', 'u00f1', 'u00bf'];
    $replace = ['Á', 'á', 'É', 'é', 'Í', 'í', 'Ó', 'ó', 'Ú', 'ú', 'Ñ', 'ñ', '¿'];
    $config = str_replace($search, $replace, $ConfigMandante->config);
    $config = json_decode($config, true);
    $iso = strtolower($Pais->iso);
    $language = strtolower($_SESSION['idioma']) ?: strtolower($Pais->idioma);
    $data = $config['termsandconditionBackoffice'][$iso][$language];
} catch (Exception $ex) {
    /* Manejo de excepciones en PHP; captura de errores sin realizar acciones específicas. */

}


/* Respuesta estructurada para manejo de errores y mensajes en una aplicación. */
$response['HasError'] = false;
$response['AlertType'] = 'success';
$response['AlertMessage'] = '';
$response['ModelErrors'] = [];
$response['Data'] = $data ?: [];
?>