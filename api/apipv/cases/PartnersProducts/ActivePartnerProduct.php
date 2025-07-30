<?php

use Backend\dto\GeneralLog;
use Backend\sql\Transaction;
use Backend\dto\ProductoMandante;
use Backend\dto\AuditoriaGeneral;
use Backend\mysql\AuditoriaGeneralMySqlDAO;
use Backend\mysql\GeneralLogMySqlDAO;
use Backend\mysql\ProductoMandanteMySqlDAO;


/**
 * Activa o desactiva un producto asociado a un socio.
 *
 * @param array $params Parámetros de entrada:
 * @param string $params->Note (string|null) Nota asociada al cambio.
 * @param int $params->ProductId (int) ID del producto.
 * @param string $params->Partner (string) Identificador del socio.
 * @param int $params->CountrySelect (int) ID del país seleccionado.
 * @param string $params->Code (string|null) Código del producto.
 * 
 *
 * @return array $response Respuesta en formato JSON:
 *  - HasError (bool): Indica si ocurrió un error.
 *  - AlertType (string): Tipo de alerta ('success' o 'error').
 *  - AlertMessage (string): Mensaje de alerta.
 *  - ModelErrors (array): Lista de errores del modelo.
 *
 * @throws Exception Si no se envían los parámetros obligatorios 'Partner' y 'CountrySelect'.
 */

/* asigna valores de parámetros a variables para su uso posterior. */
$Note = $params->Note;
$ProductId = $params->ProductId;
$Partner = $params->Partner;
$CountrySelect = $params->CountrySelect;
$Code = $params->Code;

if ($Partner !== '' && !empty($CountrySelect)) {

    /* Inicializa variables y objetos necesarios para manejar transacciones y productos en la base de datos. */
    $beforeState = '';
    $afterState = '';
    $Transaction = new Transaction();
    $ProductoMandanteMySqlDAO = new ProductoMandanteMySqlDAO($Transaction);
    $GenerealLogMySqlDAO = new GeneralLogMySqlDAO($Transaction);

    $ProductoMandante = new ProductoMandante($ProductId, $Partner, '', $CountrySelect);

    /* cambia el estado de un producto y actualiza su información en la base de datos. */
    $beforeState = $ProductoMandante->estado;
    $ProductoMandante->estado = $ProductoMandante->estado === 'A' ? 'I' : 'A';
    if (!empty($Code) && $Code !== $ProductoMandante->codigoMinsetur) $ProductoMandante->codigoMinsetur = $Code;
    $afterState = $ProductoMandante->estado;

    $ProductoMandanteMySqlDAO->update($ProductoMandante);


    /* obtiene la IP del usuario y comienza a detectar el dispositivo. */
    $ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
    $ip = explode(",", $ip)[0];

    /**
     * Detecta si el dispositivo es móvil o PC analizando el User-Agent.
     *
     * @return string 'Móvil' si se detecta un dispositivo móvil, 'PC' en caso contrario.
     */
    function detectarDispositivo()
    {
        $userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);

        // Lista de palabras clave comunes en User-Agent de dispositivos móviles
        $movilKeywords = [
            'android', 'iphone', 'ipad', 'ipod', 'blackberry', 'windows phone', 'opera mini', 'mobile', 'silk'
        ];

        // Verificar si alguna palabra clave está en el User-Agent
        foreach ($movilKeywords as $keyword) {
            if (strpos($userAgent, $keyword) !== false) {
                return 'Móvil';
            }
        }

        return 'PC';
    }

// Uso de la función

    /* detecta el dispositivo y el sistema operativo del usuario a partir del User-Agent. */
    $dispositivo = detectarDispositivo();

    $userAgent = $_SERVER['HTTP_USER_AGENT'];

    /**
     * Detecta el sistema operativo del usuario a partir de su User-Agent.
     *
     * @param string $userAgent El User-Agent del navegador del usuario.
     * @return string El nombre del sistema operativo detectado.
     */
    function getOS($userAgent)
    {
        $os = "Desconocido";

        if (stripos($userAgent, 'Windows') !== false) {
            $os = 'Windows';
        } elseif (stripos($userAgent, 'Linux') !== false) {
            $os = 'Linux';
        } elseif (stripos($userAgent, 'Macintosh') !== false || stripos($userAgent, 'Mac OS X') !== false) {
            $os = 'Mac';
        } elseif (stripos($userAgent, 'Android') !== false) {
            $os = 'Android';
        } elseif (stripos($userAgent, 'iPhone') !== false || stripos($userAgent, 'iPad') !== false) {
            $os = 'iOS';
        }

        return $os;
    }

    /* Asignación de datos de usuario para auditoría en función del sistema operativo y dirección IP. */
    $so = getOS($userAgent);


    $AuditoriaGeneral = new AuditoriaGeneral();
    $AuditoriaGeneral->usuarioId = $_SESSION['usuario'];
    $AuditoriaGeneral->usuarioIp = $ip;

    /* Registro de auditoría que almacena cambios en configuración de usuario. */
    $AuditoriaGeneral->usuariosolicitaId = $_SESSION['usuario'];
    $AuditoriaGeneral->usuarioaprobarIp = '';
    $AuditoriaGeneral->tipo = 'ACTUALIZACION_CONFIGURACION';
    $AuditoriaGeneral->valorAntes = $beforeState;
    $AuditoriaGeneral->valorDespues = $afterState;
    $AuditoriaGeneral->usucreaId = $_SESSION['usuario'];

    /* Se define un registro de auditoría con estado, dispositivo y observación específica. */
    $AuditoriaGeneral->usumodifId = 0;
    $AuditoriaGeneral->estado = 'A';
    $AuditoriaGeneral->dispositivo = $dispositivo;
    $AuditoriaGeneral->soperativo = $so;
    $AuditoriaGeneral->imagen = '';
    $AuditoriaGeneral->observacion = "Cambio en estado de producto";

    /* Se inserta un registro de auditoría en la base de datos y se prepara un log. */
    $AuditoriaGeneral->data = '';
    $AuditoriaGeneral->campo = 'Estado';

    $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO($Transaction);
    $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);


    $GeneralLog = new GeneralLog();

    /* registra datos del usuario y su IP en un log general. */
    $GeneralLog->usuarioId = $_SESSION['usuario'];
    $GeneralLog->usuarioIp = $Global_IP;
    $GeneralLog->usuariosolicitaId = $_SESSION['usuario'];
    $GeneralLog->usuariosolicitaIp = $Global_IP;
    $GeneralLog->usuarioaprobarId = 0;
    $GeneralLog->usuarioaprobarIp = '';

    /* Registra un cambio de producto con datos del usuario y estados anterior y posterior. */
    $GeneralLog->tipo = 'CHANGEPRODUCT';
    $GeneralLog->valorAntes = $beforeState;
    $GeneralLog->valorDespues = $afterState;
    $GeneralLog->usucreaId = $_SESSION['usuario'];
    $GeneralLog->usumodifId = 0;
    $GeneralLog->estado = 'A';

    /* Se asignan valores a propiedades de un objeto relacionado con el registro de un producto. */
    $GeneralLog->dispositivo = $Global_dispositivo;
    $GeneralLog->soperativo = '';
    $GeneralLog->imagen = '';
    $GeneralLog->externoId = $ProductId;
    $GeneralLog->campo = 'estado';
    $GeneralLog->tabla = 'producto_mandante';

    /* inserta un registro en la base de datos y luego confirma la transacción. */
    $GeneralLog->explicacion = $Note;
    $GeneralLog->mandante = $Partner;

    $GenerealLogMySqlDAO->insert($GeneralLog);

    $Transaction->commit();


    /* Inicializa un array de respuesta sin errores y con mensaje de éxito. */
    $response['HasError'] = false;
    $response['AlertType'] = 'success';
    $response['AlertMessage'] = '';
    $response['ModelErrors'] = [];
} else {
    /* Código que maneja errores, configurando un array de respuesta para alertas. */

    $response['HasError'] = true;
    $response['AlertType'] = 'error';
    $response['AlertMessage'] = '';
    $response['ModelErrors'] = [];
}

?>