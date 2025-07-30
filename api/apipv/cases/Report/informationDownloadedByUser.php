<?php

Use Backend\dto\Usuario;
Use Backend\mysql\UsuarioMySqlDAO;
Use Backend\dto\AuditoriaGeneral;
Use Backend\mysql\AuditoriaGeneralMySqlDAO;

/**
 * Report/informationDownloadedByUser
 * 
 * Registra información sobre la descarga de reportes por parte de usuarios
 *
 * @param object $params {
 *   "NameReport": string,          // Nombre del reporte descargado
 *   "Filters": object              // Filtros aplicados al reporte
 * }
 *
 * @return array {
 *   "HasError": boolean,           // Indica si hubo error
 *   "AlertType": string,           // Tipo de alerta (success, error)
 *   "AlertMessage": string,        // Mensaje descriptivo
 *   "ModelErrors": array,          // Errores del modelo
 *   "Data": array {
 *     "Objects": array[],          // Lista de registros
 *     "Count": int                 // Total de registros
 *   }
 * }
 */


try {
    // Obtiene y decodifica los parámetros de entrada desde el request
    $params = file_get_contents('php://input');
    $params = json_decode($params);

    // Obtiene los datos básicos para el registro de auditoría
    $Id = $_SESSION["usuario"];
    $NameReport = $params->NameReport;
    $Filters = $params->Filters;
    $Date = date("Y-m-d H:i:s");
    $Observation = "el usuario descargo el reporte $NameReport";

    // Obtiene la IP real del usuario, incluso si está detrás de un proxy
    $ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
    $ip = explode(",", $ip)[0];

    $Filters = json_encode($Filters);

    // Función para detectar si el acceso es desde un dispositivo móvil
    function esMovil()
    {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];

        $dispositivosMoviles = array(
            'iPhone', 'iPad', 'Android', 'BlackBerry', 'Windows Phone',
            'Opera Mini', 'Mobile Safari', 'webOS'
        );

        foreach ($dispositivosMoviles as $dispositivo) {
            if (stripos($userAgent, $dispositivo) !== false) {
                return true;
            }
        }

        return false;
    }

    // Determina el tipo de dispositivo (móvil o desktop)
    if (esMovil()) {
        $dispositivo = 'Mobile';
    } else {
        $dispositivo = "Desktop";
    }

    $userAgent = $_SERVER['HTTP_USER_AGENT'];

    // Función para detectar el sistema operativo del usuario
    function getOS($userAgent) {
        $os = "Desconocido";

        if (stripos($userAgent, 'Windows') !== false) {
            $os = 'Windows';
        } elseif (stripos($userAgent, 'Linux') !== false) {
            $os = 'Linux';
        } elseif (stripos($userAgent, 'Macintosh') !== false || stripos($userAgent, 'Mac OS X') !== false) {
            $os = 'Mac';
        }

        return $os;
    }

    $so = getOS($userAgent);

    // Crea y configura el objeto de auditoría con todos los datos recolectados
    $AuditoriaGeneral = new AuditoriaGeneral();
    $AuditoriaGeneral->setUsuarioId($Id);
    $AuditoriaGeneral->setUsuarioIp($ip);
    $AuditoriaGeneral->setUsuariosolicitaId($Id);
    $AuditoriaGeneral->setUsuariosolicitaIp($ip);
    $AuditoriaGeneral->setUsuarioaprobarId($Id);
    $AuditoriaGeneral->setUsuarioaprobarIp(0);
    $AuditoriaGeneral->setTipo("Excel");
    $AuditoriaGeneral->setValorAntes(0);
    $AuditoriaGeneral->setValorDespues($NameReport);
    $AuditoriaGeneral->setUsucreaId($Id);
    $AuditoriaGeneral->setUsumodifId(0);
    $AuditoriaGeneral->setEstado("A");
    $AuditoriaGeneral->setDispositivo($dispositivo);
    $AuditoriaGeneral->setSoperativo($so);
    $AuditoriaGeneral->setSversion(0);
    $AuditoriaGeneral->setImagen("");
    $AuditoriaGeneral->setObservacion("reporte descargado $NameReport");
    $AuditoriaGeneral->setData($Filters);
    $AuditoriaGeneral->setCampo("");

    // Guarda el registro de auditoría en la base de datos
    $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO();
    $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);
    $AuditoriaGeneralMySqlDAO->getTransaction()->commit();

    // Prepara la respuesta exitosa
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

}catch(exception $e){
    // En caso de error, prepara la respuesta con error
    $response["HasError"] = true;
    $response["AlertType"] = "false";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];
}
