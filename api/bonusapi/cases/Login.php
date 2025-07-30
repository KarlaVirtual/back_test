<?php
/**
 * Este script gestiona el inicio de sesión de un usuario y genera un token de autenticación.
 *
 * @param object $params Objeto JSON con los siguientes valores:
 * @param string $params->Username Nombre de usuario.
 * @param string $params->Password Contraseña del usuario.
 * 
 * 
 * @return array $response Respuesta en formato JSON que incluye:
 * - @property bool $HasError Indica si hubo un error.
 * - @property string $AlertType Tipo de alerta (success o danger).
 * - @property string $AlertMessage Mensaje de alerta.
 * - @property array $ModelErrors Lista de errores del modelo.
 * - @property array $Data Información de autenticación, incluyendo:
 *   - @property int $AuthenticationStatus Estado de autenticación.
 *   - @property string $AuthToken Token de autenticación.
 *   - @property array $PermissionList Lista de permisos del usuario.
 */

/* error_reporting(E_ALL);
 ini_set('display_errors', 'ON');*/


/* Se asignan valores de usuario y contraseña desde un objeto de parámetros. */
$usuario = $params->Username;
$clave = $params->Password;

if ($clave == "" || $usuario == "") {

    /* Verifica si el usuario y la contraseña están vacíos y genera un error. */
    $usuario = $params->username;
    $clave = $params->password;

    if ($clave == "" || $usuario == "") {

        $response["HasError"] = true;
        $response["AlertType"] = "danger";
        $response["AlertMessage"] = "Error, No hay credenciales.";
        $response["ModelErrors"] = [];

    } else {


        /* Se crea una nueva instancia de la clase Usuario en la variable $Usuario. */
        $Usuario = new Usuario();

        try {


            /* gestiona el inicio de sesión y actualiza el token del usuario. */
            $responseU = $Usuario->login($usuario, $clave);

            /*
            $UsuarioToken = new UsuarioToken("", $responseU->user_id);

            $UsuarioToken->setRequestId($json->session->sid);
            $UsuarioToken->setCookie(encrypt($responseU->user_id . "#" . time()));

            $UsuarioTokenMySqlDAO = new \Backend\mysql\UsuarioTokenMySqlDAO();
            $UsuarioTokenMySqlDAO->update($UsuarioToken);
            $UsuarioTokenMySqlDAO->getTransaction()->commit();
             */
            /* crea una respuesta con estado y permisos de usuario autenticado. */
            $response["HasError"] = false;
            $response["AlertType"] = "success";
            $response["AlertMessage"] = "";
            $response["ModelErrors"] = [];

            /*
            "ViewClients","ViewMenuDashBoard","ManageClients","ViewPaymentReport","ViewDepositWithdrawalReport","ViewBalance","ViewSalesReport","ViewClientTurnoverReport","ViewMenuReport",
             */

            $response["Data"] = array(
                "AuthenticationStatus" => 0,
                "AuthToken" => $responseU->auth_token,
                "PermissionList" => array(
                    "ViewMenuSecurity", "ViewMenuTeacher", "AdminUser", "Contingencia", "Menu", "Perfil", "PerfilOpciones", "Submenu", "UsuarioPerfil", "Clasificador", "Concesionario", "ViewAddHocReport", "ViewMenuManagement", "ActivarRegistros", "AjustarSaldo", "AsignacionCupo", "Bonos", "CuentaCobroEliminar", "GestionRed", "RegistroRapido", "ChequeReimpresion", "RecargaReversion", "GestionContacto", "ViewMenuCash", "FlujoCaja", "PagoPremio", "PagoNotaRetiro", "RecargarCredito", "ViewMenuQueries", "FlujoCajaHistorico", "FlujoCajaResumido", "InformeCasino", "InformeGerencial", "ListadoRecargasRetiros", "PremiosPendientesPagar", "ConsultaOnlineDetalle", "ConsultaOnlineResumen",

                ),
            );

        } catch (Exception $e) {


            /* Maneja excepciones, informa error de autenticación y estructura respuesta JSON. */
            $response["HasError"] = true;
            $response["AlertType"] = "danger";
            $response["AlertMessage"] = "Invalid Username and/or password ('.$e->getCode().')";
            $response["ModelErrors"] = [];

        }

    }
}