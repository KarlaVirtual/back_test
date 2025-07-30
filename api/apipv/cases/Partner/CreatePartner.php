<?php

use Backend\dto\Mandante;
use Backend\dto\PaisMandante;
use Backend\dto\UsuarioPerfil;
use Backend\mysql\MandanteMySqlDAO;
use Backend\dto\AuditoriaGeneral;
use Backend\mysql\PaisMandanteMySqlDAO;
use Backend\mysql\UsuarioPerfilMySqlDAO;
use Backend\mysql\AuditoriaGeneralMySqlDAO;
use Backend\sql\QueryExecutor;
use Backend\sql\SqlQuery;


/**
 * Partner/CreatePartner
 *
 * Crea un nuevo socio (Partner) en el sistema.
 *
 * @param object $params Objeto con los siguientes atributos:
 * @param string $params->partnerName Nombre del socio.
 * @param string $params->urlApi URL de la API del socio.
 * @param string $params->urlSite URL del sitio del socio.
 * @param string $params->urlWebsocket URL del websocket del socio.
 * @param string $params->lightLogo URL del logo claro del socio.
 * @param string $params->darkLogo URL del logo oscuro del socio.
 * @param array $params->countries Lista de países asociados al socio, cada uno con:
 * @param int $params->countries->id ID del país.
 * @param string $params->countries->currency Moneda asociada al país.
 * 
 *
 * @return array $response Respuesta con los siguientes atributos:
 * - HasError (boolean): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta ('success', 'danger', etc.).
 * - AlertMessage (string): Mensaje de alerta.
 * - ID (int): ID del socio creado.
 * - ModelErrors (array): Lista de errores del modelo.
 */

/* Se asignan parámetros a variables para configurar una aplicación o servicio. */
$partnerName = $params->partnerName;
$urlApi = $params->urlApi;
$urlSite = $params->urlSite;
$urlWebsocket = $params->urlWebsocket;
$lightLogo = $params->lightLogo;
$darkLogo = $params->darkLogo;

/* inicializa objetos DAO para interactuar con bases de datos de países y usuarios. */
$countries = $params->countries;

$MandanteMySqlDAO = new MandanteMySqlDAO();
$Transaction = $MandanteMySqlDAO->getTransaction();
$PaisMandanteMySqlDAO = new PaisMandanteMySqlDAO($Transaction);
$UsuarioPerfilMySqlDAO = new UsuarioPerfilMySqlDAO($Transaction);


/* Inserta datos de auditoría general utilizando información de usuario y dispositivo. */
$userID = $_SESSION['usuario'];
$IP = $Global_IP;
$device = $_SESSION['sistema'] === 'D' ? 'Desktop' : 'Mobile';

$query = 'INSERT INTO auditoria_general (usuario_id, usuario_ip, usuariosolicita_id, usuariosolicita_ip, usuarioaprobar_id, usuarioaprobar_ip, tipo, valor_antes, valor_despues, usucrea_id, usumodif_id, estado, dispositivo, observacion, data, campo) VALUES ';
$queryValues = "({$userID}, '{$IP}', {$userID}, '{$IP}', 0, 0, '$1', '$2', '$3', {$userID}, 0, 'A', '{$device}', '', '',  '$4'), ";


/* Crea un objeto "Mandante" y asigna propiedades relacionadas con un socio. */
$Mandante = new Mandante();
$Mandante->descripcion = strtolower($partnerName);
$Mandante->nombre = $partnerName;
$Mandante->contacto = 'Administrador General';
$Mandante->propio = 'S';
$Mandante->baseUrl = $urlSite;

/* Se asignan valores a un objeto y se inserta en la base de datos. */
$Mandante->urlApi = $urlApi;
$Mandante->urlWebsocket = $urlWebsocket;
$Mandante->logo = $lightLogo;
$Mandante->logoOscuro = $darkLogo;

$partner = $MandanteMySqlDAO->insert($Mandante);


/* Convierte datos de un objeto Mandante a formato JSON para su uso. */
$partnerData = json_encode([
    'mandante' => $Mandante->mandante,
    'descripcion' => $Mandante->descripcion,
    'nombre' => $Mandante->nombre,
    'contacto' => $Mandante->contacto,
    'propio' => $Mandante->propio,
    'base_url' => $Mandante->baseUrl,
    'url_api' => $Mandante->urlApi,
    'url_websocket' => $Mandante->urlWebsocket,
    'logo' => $Mandante->logo,
    'logo_oscuro' => $Mandante->logoOscuro
]);


/* actualiza la lista de mandantes del usuario y construye consultas SQL. */
$partnerData = substr($partnerData, 0, 250);

$query .= str_replace(['$1', '$2', '$3', '$4'], ['CREACION_MANDANTE', '', $partnerData, ''], $queryValues);

$UsuarioPerfil = new UsuarioPerfil($_SESSION['usuario']);
if (!strpos($UsuarioPerfil->mandanteLista, $partner)) {
    $partnerList = $UsuarioPerfil->mandanteLista;
    $UsuarioPerfil->mandanteLista = str_replace(',-1', '', $UsuarioPerfil->mandanteLista) . ",{$partner},-1";
    $_SESSION['mandanteLista'] = $UsuarioPerfil->mandanteLista;

    $query .= str_replace(['$1', '$2', '$3', '$4'], ['ACTUALIZACION_USUARIO_PERFIL', $partnerList, $UsuarioPerfil->mandanteLista, 'mandante_lista'], $queryValues);
}

foreach ($countries as $country) {

    /* Se crea un objeto de PaísMandante con datos específicos del país y usuario. */
    $PaisMandante = new PaisMandante();

    $PaisMandante->paisId = $country->id;
    $PaisMandante->mandante = $partner;
    $PaisMandante->estado = 'A';
    $PaisMandante->usucreaId = $userID;

    /* Asigna moneda, inserta datos en base, y codifica respuesta en formato JSON. */
    $PaisMandante->moneda = $country->currency != '' ? $country->currency : '';

    /* Asigna la url_base de cada país*/
    $PaisMandante->baseUrl = $urlSite;

    $ID = $PaisMandanteMySqlDAO->insert($PaisMandante);

    $data = json_encode([
        'paismandante_id' => $ID,
        'pais_id' => $PaisMandante->paisId,
        'mandante' => $PaisMandante->mandante,
        'estado' => $PaisMandante->estado,
        'usucrea_id' => $PaisMandante->usucreaId,
        'base_url' => $PaisMandante->baseUrl
    ]);


    /* reemplaza marcadores en la consulta SQL con datos específicos. */
    $query .= str_replace(['$1', '$2', '$3', '$4'], ['CREACION_PAIS_MANDANTE', '', $data, ''], $queryValues);
}


/* Código que configura una instancia de AuditoriaGeneral con datos del usuario y solicitudes. */
$AuditoriaGeneral = new AuditoriaGeneral();
$AuditoriaGeneral->setUsuarioId($userID);
$AuditoriaGeneral->setUsuarioIp($IP);
$AuditoriaGeneral->setUsuariosolicitaId($userID);
$AuditoriaGeneral->setUsuariosolicitaIp($IP);
$AuditoriaGeneral->setUsuarioaprobarId(0);

/* configura detalles de un objeto "AuditoriaGeneral" relacionado con "Mandante". */
$AuditoriaGeneral->setUsuarioaprobarIp(0);
$AuditoriaGeneral->setTipo("");
$AuditoriaGeneral->setValorAntes('');
$AuditoriaGeneral->setValorDespues(json_encode([
    'mandante' => $Mandante->mandante,
    'descripcion' => $Mandante->descripcion,
    'nombre' => $Mandante->nombre,
    'contacto' => $Mandante->contacto,
    'propio' => $Mandante->propio,
    'base_url' => $Mandante->baseUrl,
    'url_api' => $Mandante->urlApi,
    'url_websocket' => $Mandante->urlWebsocket,
    'logo' => $Mandante->logo,
    'logo_oscuro' => $Mandante->logoOscuro
]));

/* Se configuran valores iniciales para una auditoría general en el sistema. */
$AuditoriaGeneral->setUsucreaId($userID);
$AuditoriaGeneral->setUsumodifId(0);
$AuditoriaGeneral->setEstado("A");
$AuditoriaGeneral->setDispositivo($device);
$AuditoriaGeneral->setObservacion("");
$AuditoriaGeneral->setData('');

/* Establece un campo vacío y guarda la auditoría en la base de datos. */
$AuditoriaGeneral->setCampo('');

$AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO($Transaction);
$AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);


$query = rtrim($query, ', ');

/* Ejecuta una consulta SQL, actualiza datos y confirma la transacción sin errores. */
$SqlQuery = new SqlQuery($query);
QueryExecutor::executeInsert($Transaction, $SqlQuery);
$UsuarioPerfilMySqlDAO->update($UsuarioPerfil);
$Transaction->commit();

$response['HasError'] = false;

/* Crea un array de respuesta con información sobre el éxito de una operación. */
$response['AlertType'] = 'success';
$response['AlertMessage'] = '';
$response['ID'] = $partner;
$response['ModelErrors'] = [];
?>