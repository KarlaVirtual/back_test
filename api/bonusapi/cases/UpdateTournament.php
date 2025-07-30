<?php

use Backend\dto\TorneoInterno;
use Backend\dto\AuditoriaGeneral;
use Backend\dto\TorneoDetalle;
use Backend\mysql\TorneoInternoMySqlDAO;
use Backend\mysql\AuditoriaGeneralMySqlDAO;
use Backend\mysql\TorneoDetalleMySqlDAO;


/**
 * Actualiza los detalles de un torneo y registra auditorías.
 * 
 * @param object $params Contiene los parámetros de entrada:
 * @param int $params->Id ID del torneo.
 * @param string $params->RulesText Texto de las reglas del torneo.
 * @param string $params->MainImageURL URL de la imagen principal.
 * @param string $params->BackgroundURL URL del fondo.
 * @param string $params->ImgCenter URL de la imagen central.
 * @param string $params->ImgRight URL de la imagen derecha.
 * @param string $params->BackgroundURL2 URL del fondo secundario.
 * @param string $params->ImgCenter2 URL de la imagen central secundaria.
 * @param string $params->ImgAwards URL de la imagen de premios.
 * 
 * 
 * @return array $response Respuesta estructurada con los siguientes valores:
 * - idLottery: ID del torneo actualizado.
 * - HasError: Indica si ocurrió un error.
 * - AlertType: Tipo de alerta (success o error).
 * - AlertMessage: Mensaje de alerta.
 * - ModelErrors: Errores del modelo.
 * - Result: Resultado del procesamiento.
 */

/* Obtiene la dirección IP del usuario y define una función para detectar móviles. */
$ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
$ip = explode(",", $ip)[0];


/**
 * Verifica si el dispositivo del usuario es móvil.
 *
 * @return bool Devuelve true si el dispositivo es móvil, de lo contrario false.
 */
function esMovil()
{
    $userAgent = $_SERVER['HTTP_USER_AGENT'];

    // Código que verifica si un user agent corresponde a un dispositivo móvil específico.
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

/* determina si el dispositivo es móvil o de escritorio basado en el user agent. */
if (esMovil()) {
    $dispositivo = 'Mobile';
} else {
    $dispositivo = "Desktop";
}


$userAgent = $_SERVER['HTTP_USER_AGENT'];


/**
 * Obtiene el sistema operativo del usuario a partir del User-Agent.
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
        /* verifica si el usuario está en Linux a través del User-Agent. */

        $os = 'Linux';
    } elseif (stripos($userAgent, 'Macintosh') !== false || stripos($userAgent, 'Mac OS X') !== false) {
        /* Verifica si el agente de usuario corresponde a un sistema operativo Mac. */

        $os = 'Mac';
    }

    return $os;
}

/* obtiene el sistema operativo y crea un objeto TorneoInterno usando un ID. */
$so = getOS($userAgent);

$tournamentId = $params->Id;

try {
    $TorneoInterno = new TorneoInterno($tournamentId);
} catch (Exception $ex) {
    /* Manejo de excepciones en PHP, pero no se realiza ninguna acción en el bloque. */

}


/* Decodifica JSON a objeto; si falla, crea un objeto vacío. */
$data = json_decode($TorneoInterno->jsonTemp, false) ?: new stdClass();

try {


    /* Se crea un objeto DAO y se obtienen parámetros de un torneo. */
    $TorneoDetalleMySqlDAO = new TorneoDetalleMySqlDAO();
    $transaccion = $TorneoDetalleMySqlDAO->getTransaction();


    $RulesText = $params->RulesText;
    $MainImageURL = $params->MainImageURL;

    /* asigna valores de parámetros a variables relacionadas con imágenes y URLs. */
    $BackgroundURL = $params->BackgroundURL;
    $ImgCenter = $params->ImgCenter;
    $ImgRight = $params->ImgRight;
    $BackgroundURL2 = $params->BackgroundURL2;

    $ImgCenter2 = $params->ImgCenter2;

    /* Asignación de la variable $ImgAwards con el valor de $params->ImgAwards. */
    $ImgAwards = $params->ImgAwards;

    if ($MainImageURL != "" and $MainImageURL != $data->MainImageURL) {

        /* Se crea y actualiza un detalle de torneo en la base de datos. */
        $TorneoDetalle = new TorneoDetalle("", $tournamentId, "IMGPPALURL");
        $TorneoDetalle->valor = $MainImageURL;
        $TorneoDetalleMySqlDAO = new TorneoDetalleMySqlDAO($transaccion);
        $TorneoDetalleMySqlDAO->update($TorneoDetalle);


        $AuditoriaGeneral = new AuditoriaGeneral();

        /* Asigna información del usuario y su IP a un objeto de auditoría general. */
        $AuditoriaGeneral->usuarioId = $_SESSION['usuario'];
        $AuditoriaGeneral->usuarioIp = $ip;
        $AuditoriaGeneral->usuariosolicitaId = $_SESSION['usuario'];
        $AuditoriaGeneral->usuariosolicitaIp = $ip;
        $AuditoriaGeneral->usuarioaprobarId = 0;
        $AuditoriaGeneral->usuarioaprobarIp = 0;

        /* Registro de auditoría para actualizar la imagen principal del torneo. */
        $AuditoriaGeneral->tipo = 'ACTUALIZACION_TORNEO';
        $AuditoriaGeneral->valorAntes = $data->MainImageURL;
        $AuditoriaGeneral->valorDespues = $MainImageURL;
        $AuditoriaGeneral->usucreaId = $_SESSION['usuario'];
        $AuditoriaGeneral->usumodifId = 0;
        $AuditoriaGeneral->estado = 'A';

        /* Se asignan valores a propiedades del objeto AuditoriaGeneral para registrar un cambio. */
        $AuditoriaGeneral->dispositivo = $dispositivo;
        $AuditoriaGeneral->soperativo = $so;
        $AuditoriaGeneral->imagen = '';
        $AuditoriaGeneral->observacion = "Cambio en imagen principal";
        $AuditoriaGeneral->data = "";
        $AuditoriaGeneral->campo = "";


        /* Se inserta una auditoría en MySQL y se asigna una URL de imagen principal. */
        $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO($transaccion);
        $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);
        $data->MainImageURL = $MainImageURL;

        $changes = true;
    }

    if ($BackgroundURL != "" and $BackgroundURL != $data->BackgroundURL) {

        /* Actualiza los detalles del torneo y registra una auditoría general. */
        $TorneoDetalle = new TorneoDetalle("", $tournamentId, "BACKGROUNDURL");
        $TorneoDetalle->valor = $BackgroundURL;
        $TorneoDetalleMySqlDAO = new TorneoDetalleMySqlDAO($transaccion);
        $TorneoDetalleMySqlDAO->update($TorneoDetalle);

        $AuditoriaGeneral = new AuditoriaGeneral();

        /* Asignación de datos de usuario e IP en auditoría general. */
        $AuditoriaGeneral->usuarioId = $_SESSION['usuario'];
        $AuditoriaGeneral->usuarioIp = $ip;
        $AuditoriaGeneral->usuariosolicitaId = $_SESSION['usuario'];
        $AuditoriaGeneral->usuariosolicitaIp = $ip;
        $AuditoriaGeneral->usuarioaprobarId = 0;
        $AuditoriaGeneral->usuarioaprobarIp = 0;

        /* Registra una actualización de torneo con valores antes y después, y usuario activa. */
        $AuditoriaGeneral->tipo = 'ACTUALIZACION_TORNEO';
        $AuditoriaGeneral->valorAntes = $data->BackgroundURL;
        $AuditoriaGeneral->valorDespues = $BackgroundURL;
        $AuditoriaGeneral->usucreaId = $_SESSION['usuario'];
        $AuditoriaGeneral->usumodifId = 0;
        $AuditoriaGeneral->estado = 'A';

        /* Código que asigna valores a propiedades de un objeto de auditoría. */
        $AuditoriaGeneral->dispositivo = $dispositivo;
        $AuditoriaGeneral->soperativo = $so;
        $AuditoriaGeneral->imagen = '';
        $AuditoriaGeneral->observacion = "Cambio en URL Fondo";
        $AuditoriaGeneral->data = "";
        $AuditoriaGeneral->campo = "";


        /* Código para insertar auditoría en base de datos con configuración de fondo. */
        $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO($transaccion);
        $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);
        $data->BackgroundURL = $BackgroundURL;


        $changes = true;
    }

    if ($ImgCenter != "" and $ImgCenter != $data->ImgCenter) {

        /* Se actualiza el detalle del torneo y se crea una auditoría general. */
        $TorneoDetalle = new TorneoDetalle("", $tournamentId, "IMGCENTER");
        $TorneoDetalle->valor = $ImgCenter;
        $TorneoDetalleMySqlDAO = new TorneoDetalleMySqlDAO($transaccion);
        $TorneoDetalleMySqlDAO->update($TorneoDetalle);

        $AuditoriaGeneral = new AuditoriaGeneral();

        /* asigna datos de usuario e IP a un objeto de auditoría. */
        $AuditoriaGeneral->usuarioId = $_SESSION['usuario'];
        $AuditoriaGeneral->usuarioIp = $ip;
        $AuditoriaGeneral->usuariosolicitaId = $_SESSION['usuario'];
        $AuditoriaGeneral->usuariosolicitaIp = $ip;
        $AuditoriaGeneral->usuarioaprobarId = 0;
        $AuditoriaGeneral->usuarioaprobarIp = 0;

        /* Código que registra cambios en la auditoría de un torneo. */
        $AuditoriaGeneral->tipo = 'ACTUALIZACION_TORNEO';
        $AuditoriaGeneral->valorAntes = $data->ImgCenter;
        $AuditoriaGeneral->valorDespues = $ImgCenter;
        $AuditoriaGeneral->usucreaId = $_SESSION['usuario'];
        $AuditoriaGeneral->usumodifId = 0;
        $AuditoriaGeneral->estado = 'A';

        /* Asignación de valores a propiedades de un objeto AuditoriaGeneral para registrar cambios. */
        $AuditoriaGeneral->dispositivo = $dispositivo;
        $AuditoriaGeneral->soperativo = $so;
        $AuditoriaGeneral->imagen = '';
        $AuditoriaGeneral->observacion = "Cambio en URL imagen central ";
        $AuditoriaGeneral->data = "";
        $AuditoriaGeneral->campo = "";


        /* inserta una auditoría en MySQL y actualiza una imagen central. */
        $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO($transaccion);
        $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);

        $data->ImgCenter = $ImgCenter;

        $changes = true;
    }

    if ($ImgRight != "" and $ImgRight != $data->ImgRight) {

        /* Se actualiza el detalle del torneo y se registra en auditoría. */
        $TorneoDetalle = new TorneoDetalle("", $tournamentId, "IMGRIGHT");
        $TorneoDetalle->valor = $ImgRight;
        $TorneoDetalleMySqlDAO = new TorneoDetalleMySqlDAO($transaccion);
        $TorneoDetalleMySqlDAO->update($TorneoDetalle);

        $AuditoriaGeneral = new AuditoriaGeneral();

        /* Asigna valores de usuario y IP a propiedades de Auditoría General. */
        $AuditoriaGeneral->usuarioId = $_SESSION['usuario'];
        $AuditoriaGeneral->usuarioIp = $ip;
        $AuditoriaGeneral->usuariosolicitaId = $_SESSION['usuario'];
        $AuditoriaGeneral->usuariosolicitaIp = $ip;
        $AuditoriaGeneral->usuarioaprobarId = 0;
        $AuditoriaGeneral->usuarioaprobarIp = 0;

        /* Se registra una actualización de torneo con valores antes y después en auditoría. */
        $AuditoriaGeneral->tipo = 'ACTUALIZACION_TORNEO';
        $AuditoriaGeneral->valorAntes = $data->ImgRight;
        $AuditoriaGeneral->valorDespues = $ImgRight;
        $AuditoriaGeneral->usucreaId = $_SESSION['usuario'];
        $AuditoriaGeneral->usumodifId = 0;
        $AuditoriaGeneral->estado = 'A';

        /* Código establece propiedades en un objeto AuditoriaGeneral sobre un cambio de imagen. */
        $AuditoriaGeneral->dispositivo = $dispositivo;
        $AuditoriaGeneral->soperativo = $so;
        $AuditoriaGeneral->imagen = '';
        $AuditoriaGeneral->observacion = "Cambio en URL imagen derecha";
        $AuditoriaGeneral->data = "";
        $AuditoriaGeneral->campo = "";


        /* Se inserta una auditoría en MySQL y se actualizan datos de imagen. */
        $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO($transaccion);
        $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);

        $data->ImgRight = $ImgRight;
        $changes = true;
    }

    if ($BackgroundURL2 != "" and $BackgroundURL2 != $data->BackgroundURL2) {

        /* Se actualiza un detalle de torneo y se inicializa auditoría general. */
        $TorneoDetalle = new TorneoDetalle("", $tournamentId, "BACKGROUNDURL2");
        $TorneoDetalle->valor = $BackgroundURL2;
        $TorneoDetalleMySqlDAO = new TorneoDetalleMySqlDAO($transaccion);
        $TorneoDetalleMySqlDAO->update($TorneoDetalle);

        $AuditoriaGeneral = new AuditoriaGeneral();

        /* Asigna valores de sesión y IP a propiedades de AuditoríaGeneral. */
        $AuditoriaGeneral->usuarioId = $_SESSION['usuario'];
        $AuditoriaGeneral->usuarioIp = $ip;
        $AuditoriaGeneral->usuariosolicitaId = $_SESSION['usuario'];
        $AuditoriaGeneral->usuariosolicitaIp = $ip;
        $AuditoriaGeneral->usuarioaprobarId = 0;
        $AuditoriaGeneral->usuarioaprobarIp = 0;

        /* registra una actualización de un torneo en la auditoría general. */
        $AuditoriaGeneral->tipo = 'ACTUALIZACION_TORNEO';
        $AuditoriaGeneral->valorAntes = $data->BackgroundURL2;
        $AuditoriaGeneral->valorDespues = $BackgroundURL2;
        $AuditoriaGeneral->usucreaId = $_SESSION['usuario'];
        $AuditoriaGeneral->usumodifId = 0;
        $AuditoriaGeneral->estado = 'A';

        /* Se asignan valores a propiedades del objeto AuditoriaGeneral para registrar cambios. */
        $AuditoriaGeneral->dispositivo = $dispositivo;
        $AuditoriaGeneral->soperativo = $so;
        $AuditoriaGeneral->imagen = '';
        $AuditoriaGeneral->observacion = "Cambio en URL imagen derecha";
        $AuditoriaGeneral->data = "";
        $AuditoriaGeneral->campo = "";


        /* Código inserta auditoría en base de datos y establece fondo en variable. */
        $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO($transaccion);
        $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);

        $data->BackgroundURL2 = $BackgroundURL2;

        $changes = true;
    }

    if ($ImgCenter2 != "" and $ImgCenter2 != $data->ImgCenter2) {

        /* Se actualiza un detalle de torneo y se inicializa auditoría general. */
        $TorneoDetalle = new TorneoDetalle("", $tournamentId, "IMGCENTER2");
        $TorneoDetalle->valor = $ImgCenter2;
        $TorneoDetalleMySqlDAO = new TorneoDetalleMySqlDAO($transaccion);
        $TorneoDetalleMySqlDAO->update($TorneoDetalle);


        $AuditoriaGeneral = new AuditoriaGeneral();

        /* asigna valores de sesión e IP a un objeto de auditoría. */
        $AuditoriaGeneral->usuarioId = $_SESSION['usuario'];
        $AuditoriaGeneral->usuarioIp = $ip;
        $AuditoriaGeneral->usuariosolicitaId = $_SESSION['usuario'];
        $AuditoriaGeneral->usuariosolicitaIp = $ip;
        $AuditoriaGeneral->usuarioaprobarId = 0;
        $AuditoriaGeneral->usuarioaprobarIp = 0;

        /* Registro de auditoría para actualización de torneo con cambios en la imagen central. */
        $AuditoriaGeneral->tipo = 'ACTUALIZACION_TORNEO';
        $AuditoriaGeneral->valorAntes = $data->ImgCenter2;
        $AuditoriaGeneral->valorDespues = $ImgCenter2;
        $AuditoriaGeneral->usucreaId = $_SESSION['usuario'];
        $AuditoriaGeneral->usumodifId = 0;
        $AuditoriaGeneral->estado = 'A';

        /* asigna valores a propiedades de un objeto AuditoriaGeneral. */
        $AuditoriaGeneral->dispositivo = $dispositivo;
        $AuditoriaGeneral->soperativo = $so;
        $AuditoriaGeneral->imagen = '';
        $AuditoriaGeneral->observacion = "Cambio URL imagen central detallada";
        $AuditoriaGeneral->data = "";
        $AuditoriaGeneral->campo = "";


        /* Se inserta un registro de auditoría y se actualiza una propiedad de datos. */
        $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO($transaccion);
        $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);

        $data->ImgCenter2 = $ImgCenter2;


        $changes = true;
    }

    if ($ImgAwards != "" and $ImgAwards != $data->ImgAwards) {

        /* Actualiza detalles del torneo y registra auditoría general en la base de datos. */
        $TorneoDetalle = new TorneoDetalle("", $tournamentId, "IMGAWARDS");
        $TorneoDetalle->valor = $ImgAwards;
        $TorneoDetalleMySqlDAO = new TorneoDetalleMySqlDAO($transaccion);
        $TorneoDetalleMySqlDAO->update($TorneoDetalle);

        $AuditoriaGeneral = new AuditoriaGeneral();

        /* Se asignan datos de usuario y IP a la auditoría general. */
        $AuditoriaGeneral->usuarioId = $_SESSION['usuario'];
        $AuditoriaGeneral->usuarioIp = $ip;
        $AuditoriaGeneral->usuariosolicitaId = $_SESSION['usuario'];
        $AuditoriaGeneral->usuariosolicitaIp = $ip;
        $AuditoriaGeneral->usuarioaprobarId = 0;
        $AuditoriaGeneral->usuarioaprobarIp = 0;

        /* Registro de auditoría para actualización de torneo con cambios en ImgAwards. */
        $AuditoriaGeneral->tipo = 'ACTUALIZACION_TORNEO';
        $AuditoriaGeneral->valorAntes = $data->ImgAwards;
        $AuditoriaGeneral->valorDespues = $ImgAwards;
        $AuditoriaGeneral->usucreaId = $_SESSION['usuario'];
        $AuditoriaGeneral->usumodifId = 0;
        $AuditoriaGeneral->estado = 'A';

        /* asigna valores a propiedades de un objeto de auditoría general. */
        $AuditoriaGeneral->dispositivo = $dispositivo;
        $AuditoriaGeneral->soperativo = $so;
        $AuditoriaGeneral->imagen = '';
        $AuditoriaGeneral->observacion = "Cambio URL imagen premios";
        $AuditoriaGeneral->data = "";
        $AuditoriaGeneral->campo = "";


        /* Crea una instancia DAO, inserta datos y establece un indicador de cambios. */
        $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO($transaccion);
        $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);
        $data->ImgAwards = $ImgAwards;

        $changes = true;
    }


    /* Actualiza datos de TorneoInterno si hay cambios y confirma la transacción. */
    if ($changes) {
        $TorneoInterno->jsonTemp = json_encode($data);
        $TorneoInterno->reglas = $RulesText;
        $TorneoInternoMySqlDAO = new TorneoInternoMySqlDAO($transaccion);
        $TorneoInternoMySqlDAO->update($TorneoInterno);
        $transaccion->commit();
    }


    /* Código que genera una respuesta estructurada para una lotería en un torneo. */
    $response = [];
    $response['idLottery'] = $tournamentId;
    $response['HasError'] = false;
    $response['AlertType'] = 'success';
    $response['AlertMessage'] = '';
    $response['ModelErrors'] = [];

    /* Inicializa un array vacío llamado 'Result' en la variable $response. */
    $response['Result'] = [];
} catch (Exception $e) {
    /* Manejo de excepciones creando una respuesta estructurada al ocurrir un error. */


    $response = [];
    $response['idLottery'] = $tournamentId;
    $response['HasError'] = true;
    $response['AlertType'] = 'false';
    $response['AlertMessage'] = '';
    $response['ModelErrors'] = [];
    $response['Result'] = [];
}

?>
