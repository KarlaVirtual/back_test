<?php

use Backend\dto\UsuarioLog;
use Backend\dto\Usuario;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;

/**
 * Este script permite restablecer la contraseña de un usuario utilizando un token de activación.
 * 
 * @param object $params Objeto JSON con los siguientes atributos:
 * @param string $token Token de activación para restablecer la contraseña.
 * @param string $password Nueva contraseña del usuario.
 * 
 * @return array $response Contiene el estado de la operación, mensajes de error o éxito.
 */

/* obtiene y decodifica datos JSON de entrada en PHP. */
$params = file_get_contents('php://input');
$params = json_decode($params);

$ConfigurationEnvironment = new ConfigurationEnvironment();


$token = $params->token;

/* descifra un código de activación usando una clave de encriptación. */
$password = $params->password;

$token = str_replace(" ", "+", $token);
$activation_code = $token;

$code = ($ConfigurationEnvironment->decrypt($activation_code, $ENCRYPTION_KEY));

/* verifica condiciones para continuar, basándose en un string y una contraseña. */
$seguir = true;

if (strpos($code, "_") == -1) {
    $seguir = false;
}

if ($password == "") {
    $seguir = false;

}

try {
    if ($seguir) {

        /* Verifica si el ID del usuario es un número y maneja errores. */
        $usuariologId = explode("_", $code)[0];

        if (!is_numeric($usuariologId)) {
            $response["HasError"] = true;
            $response["AlertType"] = "danger";
            $response["AlertMessage"] = 'Ocurrio un error, comuniquese con soporte';
        } else {

            /* Calcula la diferencia en horas entre una fecha y el tiempo actual. */
            $UsuarioLog = new UsuarioLog($usuariologId);

            $hourdiff = round((strtotime($UsuarioLog->fechaCrea) - time()) / 3600, 2);

            if ($seguir) {

                /* Valida si el valor anterior del usuario coincide con el código de activación. */
                if (str_replace("", "", $UsuarioLog->getValorAntes()) != $activation_code) {
                    $response["HasError"] = true;
                    $response["AlertMessage"] = 'Ocurrio un error, comuniquese con soporte';

                    $seguir = false;
                } else {


                    /* Calcula la diferencia en horas y verifica si el recurso ha expirado. */
                    $hourdiff = round((strtotime($UsuarioLog->fechaCrea) - time()) / 3600, 2);

                    if ($hourdiff > 24 || $UsuarioLog->getEstado() != 'P') {
                        $response["HasError"] = true;
                        $response["AlertMessage"] = 'El recurso de recuperación ha expirado';
                        $seguir = false;

                    }


                    /* Verifica el tipo de usuario y muestra un error si no es válido. */
                    if ($UsuarioLog->getTipo() != 'TOKENPASS' && $UsuarioLog->getTipo() != 'TOKENPASSIMPORT') {
                        $response["HasError"] = true;
                        $response["AlertMessage"] = 'El recurso de recuperación ha expirado';
                        $seguir = false;
                    }


                    if ($seguir) {

                        /* Verifica el estado de la cuenta del usuario y genera respuesta si ya fue restaurada. */
                        $Usuario = new Usuario ($UsuarioLog->getUsuarioId());

                        if ($UsuarioLog->getTipo() == "TOKENPASSIMPORT") {
                            if ($Usuario->estadoImport == '3') {
                                $response["HasError"] = true;
                                $response["AlertMessage"] = 'Su cuenta ya fue restaurada, si tiene alguna inquietud puede comunicarse con soporte.';

                                $seguir = false;
                            }

                        }

                        if ($seguir) {


                            /* Cambia la contraseña del usuario y reinicia intentos y estado si es necesario. */
                            $Usuario->changeClave($password);

                            if ($Usuario->intentos > 0) {
                                $Usuario->intentos = 0;
                            }

                            if ($Usuario->estado == 'I') {
                                $Usuario->estado = 'A';
                            }


                            /* Condicional que actualiza el estado de un usuario y registra el cambio. */
                            if ($Usuario->estadoImport == '2') {
                                $Usuario->estadoImport = '3';
                                $Usuario->estadoEsp = 'A';
                            }


                            $UsuarioLog->setEstado('A');

                            /* actualiza registros de usuario y su log en una base de datos MySQL. */
                            $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
                            $UsuarioLogMySqlDAO->update($UsuarioLog);

                            $UsuarioMySqlDAO = new UsuarioMySqlDAO($UsuarioLogMySqlDAO->getTransaction());
                            $UsuarioMySqlDAO->update($Usuario);

                            $UsuarioLogMySqlDAO->getTransaction()->commit();


                            /* asigna "false" a la clave "HasError" en el array de respuesta. */
                            $response["HasError"] = false;


                        }
                    }


                }
            } else {
                /* Maneja errores, estableciendo una alerta de peligro y un mensaje para soporte. */

                $response["HasError"] = true;
                $response["AlertType"] = "danger";
                $response["AlertMessage"] = 'Ocurrio un error, comuniquese con soporte';
            }
        }
    } else {
        /* Manejo de errores en respuesta, indicando fallo y sugiriendo contactar soporte. */

        $response["HasError"] = true;
        $response["AlertType"] = "danger";
        $response["AlertMessage"] = 'Ocurrio un error, comuniquese con soporte';

    }

} catch (Exception $e) {
    /* Captura excepciones y las vuelve a lanzar sin modificaciones. */

    throw $e;
}


