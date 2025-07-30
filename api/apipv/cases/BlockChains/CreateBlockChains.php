<?php


use Backend\dto\RedBlockchain;
use Backend\dto\AuditoriaGeneral;
use Backend\mysql\AuditoriaGeneralMySqlDAO;
use Backend\mysql\RedBlockchainMySqlDAO;
use Backend\dto\ConfigurationEnvironment;


/**
 * Crear RedBlockchain
 *
 * Crea una nueva RedBlockchain basándose en los datos proporcionados en la solicitud.
 * Verifica permisos del usuario antes de proceder y registra auditoría del proceso.
 *
 * @param string $params->name : Nombre de la RedBlockchain.
 * @param string $params->code : Código único de la RedBlockchain.
 * @param string|null $params->state : Estado de la RedBlockchain ('A' para activo, 'I' para inactivo). Si no se proporciona, se asigna por defecto 'A'.
 *
 * El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si ocurrió un error durante la ejecución.
 *  - *AlertType* (string): Tipo de alerta que se mostrará en la vista ('success', 'danger', etc.).
 *  - *AlertMessage* (string): Mensaje informativo del resultado de la operación.
 *  - *ModelErrors* (array): Lista de errores de validación del modelo, si los hay.
 *
 * Ejemplo de respuesta exitosa:
 * $response["HasError"] = false;
 * $response["AlertType"] = "success";
 * $response["AlertMessage"] = "";
 * $response["ModelErrors"] = [];
 *
 * @throws Exception Permiso denegado o código de red ya existente en el sistema.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* asigna valores de parámetros y establece un estado predeterminado. */
$Name = $params->name; // nombre para la red blockchain
$State = $params->state; // estado para la red blockchain
$Code = $params->code; // codigo para la red blockchain

$ConfigurationEnvironment = new ConfigurationEnvironment();
$permission = $ConfigurationEnvironment->checkUserPermission('BlockChains/CreateBlockChains', $_SESSION['win_perfil'], $_SESSION['usuario'], "BlockchainsAdd");
if (!$permission) Throw new Exception("Permiso denegado", 100035);

if ($State == '') {
    $State = 'A';  //por defecto se establece como activo si no se especifica
}

if ($Name == '' || $Code == '' )Throw new Exception("Error en los parametros enviados", 100001); // Si se envia data imcompleta para la creación


/* Se crea un objeto 'RedBlockchain' y se asignan propiedades específicas a él. */
$RedBlockchain = new RedBlockchain(); // se instacia la clase RedBlockchain y se le asignan los valores
$RedBlockchainMySqlDAO = new RedBlockchainMySqlDAO();
$existCode = $RedBlockchainMySqlDAO->queryByCodigoRed($Code);
if (!empty($existCode)){
    throw new Exception("El código de red ingresado ya existe en el sistema.", 300173);
}else{
    $RedBlockchain->nombre = $Name;
    $RedBlockchain->estado = $State;
    $RedBlockchain->codigoRed = $Code;
    $RedBlockchain->usucreaId = $_SESSION['usuario'];
    $RedBlockchain->usumodifId = 0;

    /* Convierte datos de un objeto Mandante a formato JSON para su uso. */
    $redData = json_encode([
        'Nombre' => $RedBlockchain->nombre,
        'Estado' => $RedBlockchain->estado,
        'Codigo_red' => $RedBlockchain->codigoRed
         ]);


    /* actualiza la lista de mandantes del usuario y construye consultas SQL. */
    $redData = substr($redData, 0, 250);

    /* Se crea una auditoría general con información de usuario e IP. */
    $AuditoriaGeneral = new AuditoriaGeneral();
    $ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
    $ip = explode(",", $ip)[0];
    $AuditoriaGeneral->setUsuarioId($_SESSION["usuario"]);
    $AuditoriaGeneral->setUsuarioIp($ip);
    $AuditoriaGeneral->setUsuariosolicitaIp($ip);
    $AuditoriaGeneral->setUsuariosolicitaId($_SESSION["usuario"]);

    /* configura una auditoría para la desactivación de un banco. */
    $AuditoriaGeneral->setUsuarioaprobarIp(0);
    $AuditoriaGeneral->setTipo("CREACION_REDBLOCKCHAIN");
    $AuditoriaGeneral->setValorAntes("");
    $AuditoriaGeneral->setValorDespues("$redData");
    $AuditoriaGeneral->setUsucreaId($_SESSION["usuario"]);
    $AuditoriaGeneral->setUsumodifId(0);

    /* Se establece el estado, dispositivo y observación en Auditoría General con MySQL. */
    $AuditoriaGeneral->setEstado("A");
    $AuditoriaGeneral->setDispositivo(0);
    $AuditoriaGeneral->setObservacion("");


    $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO();

    /* Inserta un registro de auditoría general en la base de datos MySQL. */
    $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);
    /* Se inserta un RedBlockchain en MySQL y se confirma la transacción con éxito. */
    $RedBlockchainMySqlDAO->insert($RedBlockchain);
    $RedBlockchainMySqlDAO->getTransaction()->commit();

    $response["HasError"] = false;
    $response["AlertType"] = "success";

    /* Se inicializan variables para mensajes de alerta y posibles errores del modelo. */
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];
}

