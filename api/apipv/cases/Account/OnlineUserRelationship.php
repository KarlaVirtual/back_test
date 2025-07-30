<?php

use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Clasificador;
use Backend\dto\UsuarioConfiguracion;
use Backend\mysql\UsuarioConfiguracionMySqlDAO;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\dto\UsuarioLog;
use Backend\dto\Usuario;

/**
 * Account/OnlineUserRelationship
 *
 * Actualización de Configuración de Usuario y Registro de Acceso
 *
 * Este recurso actualiza la configuración de usuario en función del número de documento ingresado,
 * registrando la acción en el log de usuario. Solo está disponible para los perfiles "PUNTOVENTA" y "CAJERO".
 * Si la configuración no existe, la crea y registra la acción correspondiente.
 *
 * @param string $docnumber : Número de documento del usuario a actualizar.
 *
 * El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error en la operación.
 *  - *AlertType* (string): Tipo de alerta a mostrar en la vista.
 *  - *AlertMessage* (string): Mensaje de la operación.
 *  - *ModelErrors* (array): Devuelve un array vacío si no hay errores de validación.
 *  - *data* (array): Contiene el número de documento actualizado en la configuración.
 *
 * Objeto en caso de error:
 *
 * "HasError" => true,
 * "AlertType" => "danger",
 * "AlertMessage" => "[Mensaje de error]",
 * "ModelErrors" => [],
 * "data" => [],
 *
 * @throws Exception Si ocurre un error en la base de datos o en la actualización de configuración.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* limpia y depura el número de documento eliminando caracteres no ASCII. */
$docnumber = $params->docnumber;
$ConfigurationEnvironment = new ConfigurationEnvironment();
$docnumber = $ConfigurationEnvironment->DepurarCaracteres($docnumber);
$docnumber = preg_replace('/[^(\x20-\x7F)]*/', '', $docnumber);

if ($_SESSION["win_perfil"] == "PUNTOVENTA" || $_SESSION["win_perfil"] == "CAJERO") {

    /* Inicializa un clasificador y obtiene configuración de usuario y transacciones. */
    $Clasificador = new Clasificador('', 'RELATIONSHIPUSERONLINE');
    $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
    $Usuario = new Usuario($_SESSION["usuario"]);
    $plaform = strval($_SERVER['HTTP_SEC_CH_UA_PLATFORM']);
    $plaform = str_replace('"', "", $plaform);
    $Transaction = $UsuarioConfiguracionMySqlDAO->getTransaction();
    try {

        /* Se crea una instancia de UsuarioConfiguracion usando sesión de usuario y clasificador. */
        $UsuarioConfiguracion = new UsuarioConfiguracion($_SESSION['usuario'], 'A', $Clasificador->getClasificadorId());
        if ($UsuarioConfiguracion->getValor() !== $docnumber) {

            /* Actualiza configuración de usuario y registra la acción en el sistema. */
            $UsuarioConfiguracion->setValor($docnumber);
            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);
            $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);

            $UsuarioLog = new UsuarioLog();
            $UsuarioLog->setUsuarioId($_SESSION["usuario"]);

            /* Registro de usuario con información sobre IP, identificación y estado de sesión. */
            $UsuarioLog->setUsuarioIp($Usuario->dirIp);
            $UsuarioLog->setUsuariosolicitaId($docnumber);
            $UsuarioLog->setUsuariosolicitaIp($Usuario->dirIp);
            $UsuarioLog->setUsuarioaprobarId(0);
            $UsuarioLog->setTipo('LOGINPV');
            $UsuarioLog->setEstado("A");

            /* Se registran cambios de IP y datos operativos del usuario en el log. */
            $UsuarioLog->setValorAntes($Usuario->dirIp);
            $UsuarioLog->setValorDespues($Usuario->dirIp);

            $UsuarioLog->setSoperativo($plaform);
            $UsuarioLog->setSversion($plaform);

            $UsuarioLog->setUsucreaId(0);

            /* Inserta un registro de usuario en la base de datos, utilizando una transacción. */
            $UsuarioLog->setUsumodifId(0);
            $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO($Transaction);
            $UsuarioLogMySqlDAO->insert($UsuarioLog);
        }

    } catch (Exception $e) {
        if ($e->getCode() == 46) {

            /* Se crea una configuración de usuario con atributos específicos y estado activo. */
            $UsuarioConfiguracion = new UsuarioConfiguracion();
            $UsuarioConfiguracion->setUsuarioId($_SESSION['usuario']);
            $UsuarioConfiguracion->setTipo($Clasificador->getClasificadorId());
            $UsuarioConfiguracion->setValor($docnumber);
            $UsuarioConfiguracion->setUsucreaId($_SESSION['usuario']);
            $UsuarioConfiguracion->setEstado('A');


            /* inserta configuración de usuario y registra actividades de usuario en MySQL. */
            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);
            $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);

            $UsuarioLog = new UsuarioLog();
            $UsuarioLog->setUsuarioId($_SESSION["usuario"]);
            $UsuarioLog->setUsuarioIp($Usuario->dirIp);

            /* Se registra un inicio de sesión con detalles del usuario y su IP. */
            $UsuarioLog->setUsuariosolicitaId($docnumber);
            $UsuarioLog->setUsuariosolicitaIp($Usuario->dirIp);
            $UsuarioLog->setUsuarioaprobarId(0);
            $UsuarioLog->setTipo('LOGINPV');
            $UsuarioLog->setEstado("A");
            $UsuarioLog->setValorAntes($Usuario->dirIp);

            /* Se establecen valores del registro del usuario, incluyendo IP, sistema operativo y versión. */
            $UsuarioLog->setValorDespues($Usuario->dirIp);

            $UsuarioLog->setSoperativo($plaform);
            $UsuarioLog->setSversion($plaform);

            $UsuarioLog->setUsucreaId(0);

            /* inserta un registro de usuario log en la base de datos. */
            $UsuarioLog->setUsumodifId(0);
            $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO($Transaction);
            $UsuarioLogMySqlDAO->insert($UsuarioLog);
        }
    }

    /* confirma una transacción y prepara una respuesta sin errores. */
    $Transaction->commit();
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];
    $response["data"] = [$UsuarioConfiguracion->getValor()];
} else {
    /* maneja un error asignando mensajes a una respuesta JSON. */

    $response["HasError"] = true;
    $response["AlertType"] = "danger";
    $response["AlertMessage"] = "f";
    $response["ModelErrors"] = [];
}





