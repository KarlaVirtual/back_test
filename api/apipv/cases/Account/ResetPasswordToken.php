<?php

use Backend\dto\UsuarioLog;
use Backend\dto\Usuario;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;

/**
 * Account/ResetPasswordToken
 *
 * Restablecimiento de Contraseña
 *
 * Este recurso permite a un usuario restablecer su contraseña mediante un código de activación
 * recibido previamente por correo electrónico. Se valida el código, se verifica el estado del usuario
 * y se actualiza la contraseña en caso de que todas las condiciones se cumplan correctamente.
 *
 * @param string $params : JSON con los datos de entrada, incluyendo:
 *  - *ResetCode* (string): Código de activación recibido para el restablecimiento de contraseña.
 *  - *NewPassword* (string): Nueva contraseña establecida por el usuario.
 *
 * El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error en la operación.
 *  - *AlertType* (string): Especifica el tipo de alerta que se mostrará en la vista.
 *  - *AlertMessage* (string): Contiene el mensaje que se mostrará en la vista.
 *
 * Objeto en caso de error:
 *
 * "HasError" => true,
 * "AlertType" => "danger",
 * "AlertMessage" => "[Mensaje de error]",
 *
 * @throws Exception Si ocurre un error en la validación del código, en la actualización de la contraseña o en la transacción con la base de datos.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* lee datos JSON de una solicitud y extrae un código de reinicio. */
$params = file_get_contents('php://input');
$params = json_decode($params);

$ConfigurationEnvironment = new ConfigurationEnvironment();


$token = $params->ResetCode;

/* asigna una nueva contraseña y procesa un token para activación. */
$password = $params->NewPassword;

$token = str_replace(" ", "+", $token);
$activation_code = $token;


$ENCRYPTION_KEY = "";


/* desencripta un código de activación y verifica su formato. */
$code = ($ConfigurationEnvironment->decrypt($activation_code, $ENCRYPTION_KEY));

$seguir = true;

if (strpos($code, "_") == -1) {
    $seguir = false;
}


/* verifica si la contraseña está vacía y establece $seguir en falso. */
if ($password == "") {
    $seguir = false;

}

try {
    if ($seguir) {

        /* Divide el código en partes, verifica si el primer elemento es numérico y gestiona errores. */
        $usuariologId = explode("_", $code)[0];

        if (!is_numeric($usuariologId)) {
            $response["HasError"] = true;
            $response["AlertType"] = "danger";
            $response["AlertMessage"] = 'Ocurrio un error, comuniquese con soporte';
        } else {

            /* Calcula la diferencia en horas entre la fecha de creación y el tiempo actual. */
            $UsuarioLog = new UsuarioLog($usuariologId);

            $hourdiff = round((strtotime($UsuarioLog->fechaCrea) - time()) / 3600, 2);

            if ($seguir) {

                /* Verifica si el código de activación coincide, manejando errores en caso contrario. */
                if (str_replace("", "", $UsuarioLog->getValorAntes()) != $activation_code) {
                    $response["HasError"] = true;
                    $response["AlertMessage"] = 'Ocurrio un error, comuniquese con soporte';

                    $seguir = false;
                } else {


                    /* Calcula la diferencia de horas y verifica si el recurso ha expirado. */
                    $hourdiff = round((strtotime($UsuarioLog->fechaCrea) - time()) / 3600, 2);

                    if ($hourdiff > 24 || $UsuarioLog->getEstado() != 'P') {
                        $response["HasError"] = true;
                        $response["AlertMessage"] = 'El recurso de recuperación ha expirado';
                        $seguir = false;

                    }


                    /* Verifica tipo de usuario y muestra mensaje de error si no es válido. */
                    if ($UsuarioLog->getTipo() != 'TOKENPASS' && $UsuarioLog->getTipo() != 'TOKENPASSIMPORT') {
                        $response["HasError"] = true;
                        $response["AlertMessage"] = 'El recurso de recuperación ha expirado';
                        $seguir = false;
                    }


                    if ($seguir) {

                        /* Verifica el estado de un usuario y maneja posibles errores al restaurar cuenta. */
                        $Usuario = new Usuario ($UsuarioLog->getUsuarioId());

                        if ($UsuarioLog->getTipo() == "TOKENPASSIMPORT") {
                            if ($Usuario->estadoImport == '3') {
                                $response["HasError"] = true;
                                $response["AlertMessage"] = 'Su cuenta ya fue restaurada, si tiene alguna inquietud puede comunicarse con soporte.';

                                $seguir = false;
                            }

                        }

                        if ($seguir) {


                            /* cambia la contraseña y restablece intentos y estado del usuario. */
                            $Usuario->changeClave($password);

                            if ($Usuario->intentos > 0) {
                                $Usuario->intentos = 0;
                            }

                            if ($Usuario->estado == 'I') {
                                $Usuario->estado = 'A';
                            }


                            /* actualiza el estado de un usuario y registra el cambio. */
                            if ($Usuario->estadoImport == '2') {
                                $Usuario->estadoImport = '3';
                                $Usuario->estadoEsp = 'A';
                            }


                            $UsuarioLog->setEstado('A');

                            /* Actualiza registros de usuario y log utilizando DAO y transacciones en MySQL. */
                            $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
                            $UsuarioLogMySqlDAO->update($UsuarioLog);

                            $UsuarioMySqlDAO = new UsuarioMySqlDAO($UsuarioLogMySqlDAO->getTransaction());
                            $UsuarioMySqlDAO->update($Usuario);

                            $UsuarioLogMySqlDAO->getTransaction()->commit();


                            /* asigna un valor booleano que indica ausencia de errores en la respuesta. */
                            $response["HasError"] = false;


                        }
                    }


                }
            } else {
                /* Maneja errores asignando mensajes y alertas a la respuesta de la aplicación. */

                $response["HasError"] = true;
                $response["AlertType"] = "danger";
                $response["AlertMessage"] = 'Ocurrio un error, comuniquese con soporte';
            }
        }
    } else {
        /* Maneja un error asignando un mensaje de alerta en la respuesta. */

        $response["HasError"] = true;
        $response["AlertType"] = "danger";
        $response["AlertMessage"] = 'Ocurrio un error, comuniquese con soporte';

    }

} catch (Exception $e) {
    /* Captura una excepción y la vuelve a lanzar sin modificarla. */

    throw $e;
}


