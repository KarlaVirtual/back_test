<?php

use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Mandante;
use Backend\dto\PaisMandante;
use Backend\mysql\MandanteMySqlDAO;
use Backend\mysql\PaisMandanteMySqlDAO;
use Backend\sql\QueryExecutor;
use Backend\sql\SqlQuery;
use Mpdf\Tag\Em;


/**
 * Actualiza la información de un socio (partner) en el sistema.
 *
 * @param object $params Objeto con los siguientes atributos:
 * @param int $params->partnerID ID del socio.
 * @param string $params->partnerName Nombre del socio.
 * @param string $params->urlApi URL de la API del socio.
 * @param string $params->urlSite URL del sitio del socio.
 * @param string $params->urlWebsocket URL del websocket del socio.
 * @param string $params->lightLogo URL del logo claro del socio.
 * @param string $params->darkLogo URL del logo oscuro del socio.
 * @param array $params->countriesAdded Lista de países a agregar, cada uno con:
 * @param int $params->countriesAdded->id ID del país.
 * @param string $params->countriesAdded->currency Moneda asociada al país.
 * @param array $params->countriesDelete Lista de países a eliminar
 * @param int $params->countriesDelete->id ID del país.
 * 
 *
 * @return array $response Respuesta con los siguientes atributos:
 * - HasError (boolean): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta (success, danger, etc.).
 * - AlertMessage (string): Mensaje de alerta.
 * - ModelErrors (array): Lista de errores del modelo.
 */

/* Asigna valores de parámetros a variables para su uso posterior en el código. */
$partnerID = $params->partnerID;
$partnerName = $params->partnerName;
$urlApi = $params->urlApi;
$urlSite = $params->urlSite;
$urlWebsocket = $params->urlWebsocket;
$lightLogo = $params->lightLogo;

/* asigna parámetros y crea una nueva instancia de ConfigurationEnvironment. */
$darkLogo = $params->darkLogo;
$countriesAdded = $params->countriesAdded;
$countriesDelete = $params->countriesDelete;


$ConfigurationEnvironment = new ConfigurationEnvironment();


/* Código establece conexión y obtiene información del usuario y su IP. */
$MandanteMysqlDAO = new MandanteMySqlDAO();
$Transaction = $MandanteMysqlDAO->getTransaction();
$PaisMandanteMySqlDAO = new PaisMandanteMySqlDAO($Transaction);

$userID = $_SESSION['usuario'];
$IP = $Global_IP;

/* inserta un registro de auditoría, adjuntando información del usuario y dispositivo. */
$device = $_SESSION['sistema'] === 'D' ? 'Desktop' : 'Mobile';

$query = 'INSERT INTO auditoria_general (usuario_id, usuario_ip, usuariosolicita_id, usuariosolicita_ip, usuarioaprobar_id, usuarioaprobar_ip, tipo, valor_antes, valor_despues, usucrea_id, usumodif_id, estado, dispositivo, observacion, data, campo) VALUES ';
$queryValues = "({$userID}, '{$IP}', {$userID}, '{$IP}', 0, 0, '$1', '$2', '$3', {$userID}, 0, 'A', '{$device}', '', '', '$4'), ";

$Mandante = new Mandante($partnerID);


/* Actualiza la descripción de Mandante si el nombre del socio es diferente y no está vacío. */
if (!empty($partnerName) && $Mandante->descripcion !== strtolower($partnerName)) {
    $description = $Mandante->descripcion;
    $query .= str_replace(['$1', '$2', '$3', '$4'], ['ACTUALIZACION_MANDANTE', $description, strtolower($partnerName), 'descripcion'], $queryValues);
    $query .= str_replace(['$1', '$2', '$3', '$4'], ['ACTUALIZACION_CONFIGURACION', $description, strtolower($partnerName), 'descripcion'], $queryValues);
    $Mandante->descripcion = strtolower($partnerName);
}


/* Actualiza el nombre del mandante si no coincide con el nuevo valor proporcionado. */
if (!empty($partnerName) && $Mandante->nombre !== $partnerName) {
    $name = $Mandante->nombre;
    $query .= str_replace(['$1', '$2', '$3', '$4'], ['ACTUALIZACION_MANDANTE', $name, $partnerName, 'nombre'], $queryValues);
    $query .= str_replace(['$1', '$2', '$3', '$4'], ['ACTUALIZACION_CONFIGURACION', $name, $partnerName, 'nombre'], $queryValues);
    //$Mandante->nombre = $partnerName;
}


/* Modifica la URL base si es diferente y actualiza consultas relacionadas. */
if (!empty($urlSite) && $Mandante->baseUrl !== $urlSite) {
    $baseUrl = $Mandante->baseUrl;
    $query .= str_replace(['$1', '$2', '$3', '$4'], ['ACTUALIZACION_MANDANTE', $baseUrl, $urlSite, 'base_url'], $queryValues);
    $query .= str_replace(['$1', '$2', '$3', '$4'], ['ACTUALIZACION_CONFIGURACION', $baseUrl, $urlSite, 'base_url'], $queryValues);
    $Mandante->baseUrl = $urlSite;
}


/* Actualiza la URL de la API si es diferente y no está vacía. */
if (!empty($urlApi) && $Mandante->urlApi !== $urlApi) {
    $api = $Mandante->urlApi;
    $query .= str_replace(['$1', '$2', '$3', '$4'], ['ACTUALIZACION_MANDANTE', $api, $urlApi, 'url_api'], $queryValues);
    $query .= str_replace(['$1', '$2', '$3', '$4'], ['ACTUALIZACION_CONFIGURACION', $api, $urlApi, 'url_api'], $queryValues);
    $Mandante->urlApi = $urlApi;
}


/* Actualiza la URL del websocket si es diferente y no está vacía. */
if (!empty($urlWebsocket) && $Mandante->urlWebsocket !== $urlWebsocket) {
    $websocket = $Mandante->urlWebsocket;
    $query .= str_replace(['$1', '$2', '$3', '$4'], ['ACTUALIZACION_MANDANTE', $websocket, $urlWebsocket, 'url_websocket'], $queryValues);
    $query .= str_replace(['$1', '$2', '$3', '$4'], ['ACTUALIZACION_CONFIGURACION', $websocket, $urlWebsocket, 'url_websocket'], $queryValues);
    $Mandante->urlWebsocket = $urlWebsocket;
}


/* Actualiza el logo del mandante si el nuevo no está vacío y es diferente. */
if (!empty($lightLogo) && $Mandante->logo !== $lightLogo) {
    $logo = $Mandante->logo;
    $query .= str_replace(['$1', '$2', '$3', '$4'], ['ACTUALIZACION_MANDANTE', $logo, $lightLogo, 'logo'], $queryValues);
    $query .= str_replace(['$1', '$2', '$3', '$4'], ['ACTUALIZACION_CONFIGURACION', $logo, $lightLogo, 'logo'], $queryValues);
    $Mandante->logo = $lightLogo;
}


/* Actualiza el logo oscuro si es diferente y no está vacío. */
if (!empty($darkLogo) && $Mandante->logoOscuro !== $darkLogo) {
    $logo = $Mandante->logo;
    $query .= str_replace(['$1', '$2', '$3', '$4'], ['ACTUALIZACION_MANDANTE', $logo, $darkLogo, 'logo_oscuro'], $queryValues);
    $query .= str_replace(['$1', '$2', '$3', '$4'], ['ACTUALIZACION_CONFIGURACION', $logo, $darkLogo, 'logo_oscuro'], $queryValues);
    $Mandante->logoOscuro = $darkLogo;
}

foreach ($countriesAdded as $country) {

    /* Actualiza datos de un país mandante en la base de datos usando valores específicos. */
    try {
        $PaisMandante = new PaisMandante('', $Mandante->mandante, $country->id);
        if($PaisMandante->estado !== 'A' || $PaisMandante->moneda !== $country->currency) {
            $state = $PaisMandante->estado;
            $query .= str_replace(['$1', '$2', '$3', '$4'], ['ACTUALIZACION_PAIS_MANDANTE', $state, 'A', 'estado'], $queryValues);
            $query .= str_replace(['$1', '$2', '$3', '$4'], ['ACTUALIZACION_CONFIGURACION', $state, 'A', 'estado'], $queryValues);
            $PaisMandante->moneda = $country->currency;
            $PaisMandante->estado = 'A';
        // Si la URL cambió, registrar auditoría
        if (!empty($urlSite) && $PaisMandante->baseUrl !== $urlSite) {
            $baseUrlBefore = $PaisMandante->baseUrl;
            $PaisMandante->baseUrl = $urlSite;
        }
            $PaisMandanteMySqlDAO->update($PaisMandante);
        }
    } catch (Exception $ex) {
        /* Manejo de excepciones para insertar un nuevo registro de país mandante en base de datos. */
        $PaisMandante = new PaisMandante();
        $PaisMandante->paisId = $country->id;
        $PaisMandante->mandante = $Mandante->mandante;
        $PaisMandante->estado = 'A';
        $PaisMandante->usucreaId = $userID;
        $PaisMandante->moneda = $country->currency;
        /* Asigna la url_base de cada país*/
        $PaisMandante->baseUrl = $urlSite;
        $ID = $PaisMandanteMySqlDAO->insert($PaisMandante);

        $data = json_encode(['paismandante_id' => $ID, 'pais_id' => $country->id, 'mandante' => $Mandante->mandante, 'estado' => 'A', 'usucreaId' => $userID]);
        $state = $PaisMandante->estado;
        $query .= str_replace(['$1', '$2', '$3', '$4'], ['CREACION_PAIS_MANDANTE', '', $data, ''], $queryValues);
        $query .= str_replace(['$1', '$2', '$3', '$4'], ['ACTUALIZACION_CONFIGURACION', '', $data, ''], $queryValues);
    }
}


/* actualiza el estado de países mandantes a 'I' si están activos. */
foreach ($countriesDelete as $country) {
    try {
        $PaisMandante = new PaisMandante('', $Mandante->mandante, $country->id);
        if ($PaisMandante->estado === 'A') {
            $state = $PaisMandante->estado;
            $query .= str_replace(['$1', '$2', '$3', '$4'], ['ACTUALIZACION_PAIS_MANDANTE', $state, 'I', 'estado'], $queryValues);
            $query .= str_replace(['$1', '$2', '$3', '$4'], ['ACTUALIZACION_CONFIGURACION', $state, 'I', 'estado'], $queryValues);
            $PaisMandante->estado = 'I';
            $PaisMandanteMySqlDAO->update($PaisMandante);
        }
    } catch (Exception $ex) {
    }
}


/* ejecuta una consulta SQL si contiene un identificador de usuario específico. */
$query = rtrim($query, ', ');

if (strpos($query, $userID)) {
    $SqlQuery = new SqlQuery($query);
    QueryExecutor::executeInsert($Transaction, $SqlQuery);
    $MandanteMysqlDAO->update($Mandante);
    $Transaction->commit();
}


/* Código configura una respuesta inicial sin errores y con mensaje de éxito. */
$response['HasError'] = false;
$response['AlertType'] = 'success';
$response['AlertMessage'] = '';
$response['ModelErrors'] = [];
