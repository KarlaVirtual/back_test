<?php

use Backend\dto\UsuarioLog;
use Backend\dto\Usuario;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;

/**
 * Este script verifica el código de activación enviado al teléfono del usuario.
 * 
 * @param object $params Objeto JSON con los siguientes atributos:
 * @param string $email Correo electrónico del usuario.
 * @param string $code Código de activación enviado al usuario.
 * @param int|string $partner ID del socio asociado al usuario.
 * 
 * @return array $response Contiene el estado de la operación, mensajes de error o éxito.
 */

/* recibe datos JSON, los decodifica y almacena un correo electrónico. */
$params = file_get_contents('php://input');
$params = json_decode($params);

$ConfigurationEnvironment = new ConfigurationEnvironment();


$email = $params->email;

/* asigna valores y reemplaza espacios en una variable de token. */
$code = $params->code;
$partner = $params->partner;


$token = str_replace(" ", "+", $token);
$activation_code = $token;


/* verifica la presencia de un guion bajo y si la variable está vacía. */
$seguir = true;

if (strpos($code, "_") == -1) {
    $seguir = false;
}

if ($code == "") {
    $seguir = false;
}


/* verifica si el email está vacío y detiene el proceso si es así. */
if ($email == "") {
    $seguir = false;

}

try {
    if ($seguir) {

        /* Se crea un nuevo objeto Usuario y se establecen reglas de validación para el login. */
        $Usuario = new Usuario('', $email, '0', $partner);


        $rules = [];

        array_push($rules, array("field" => "usuario.login", "data" => "$email", "op" => "eq"));

        /* Se crea un filtro JSON con reglas de comparación para validar datos de usuario. */
        array_push($rules, array("field" => "usuario_log.tipo", "data" => "VERIFYPHONE", "op" => "eq"));
        array_push($rules, array("field" => "usuario_log.estado", "data" => "P", "op" => "eq"));
        array_push($rules, array("field" => "usuario_log.valor_despues", "data" => $ConfigurationEnvironment->encryptWithoutRandom($Usuario->usuarioId . "_" . $code, $ENCRYPTION_KEY), "op" => "eq"));

        $filtro = array("rules" => $rules, "groupOp" => "AND");

        $json = json_encode($filtro);


        /* establece la configuración regional en checo y selecciona datos de usuario. */
        setlocale(LC_ALL, 'czech');


        $select = " usuario_log.* ";


        $UsuarioLog = new UsuarioLog();

        /* recupera, actualiza estado y confirma transacciones de registros de usuario. */
        $data = $UsuarioLog->getUsuarioLogsCustom($select, "usuario_log.usuariolog_id", "asc", 0, 1, $json, true, $grouping);

        $data = json_decode($data);

        if (oldCount($data->data) > 0) {

            $usuariologId = $data->data[0]->{"usuario_log.usuariolog_id"};

            $UsuarioLog->setEstado('A');

            $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
            $UsuarioLogMySqlDAO->update($UsuarioLog);
            $UsuarioLogMySqlDAO->getTransaction()->commit();
            $response["Cookie"] = $UsuarioLog->getValorAntes();

        } else {
            /* maneja un error informando al usuario sobre datos incorrectos ingresados. */

            $response["HasError"] = true;
            $response["AlertType"] = "danger";
            $response["AlertMessage"] = "Error en el dato ingresado";
            $response["ModelErrors"] = [];

        }

        if ($usuariologId != '') {


            /* Calcula la diferencia en horas entre la fecha de creación y el tiempo actual. */
            $UsuarioLog = new UsuarioLog($usuariologId);

            $hourdiff = round((strtotime($UsuarioLog->fechaCrea) - time()) / 3600, 2);

            if ($seguir) {

                /* Verifica si el código de activación coincide y maneja errores en la respuesta. */
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


                    /* Verifica el tipo de usuario y maneja errores si no es 'VERIFYPHONE'. */
                    if ($UsuarioLog->getTipo() != 'VERIFYPHONE') {
                        $response["HasError"] = true;
                        $response["AlertMessage"] = 'El recurso de recuperación ha expirado';
                        $seguir = false;
                    }


                    if ($seguir) {

                        /* Se crea un objeto Usuario utilizando el ID del usuario obtenido previamente. */
                        $Usuario = new Usuario ($UsuarioLog->getUsuarioId());


                        /* actualiza el estado de un usuario y verifica su celular. */
                        if ($seguir) {


                            $UsuarioLog->setEstado('A');
                            $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
                            $UsuarioLogMySqlDAO->update($UsuarioLog);

                            $Usuario->verifCelular = 'S';
                            $Usuario->fechaVerifCelular = date('Y-m-d H:i:s');

                            $UsuarioMySqlDAO = new UsuarioMySqlDAO($UsuarioLogMySqlDAO->getTransaction());
                            $UsuarioMySqlDAO->update($Usuario);

                            $UsuarioLogMySqlDAO->getTransaction()->commit();

                            $response["HasError"] = false;


                        }
                    }


                }
            } else {
                /* Manejo de errores que establece un mensaje de alerta en caso de fallo. */

                $response["HasError"] = true;
                $response["AlertType"] = "danger";
                $response["AlertMessage"] = 'Ocurrio un error, comuniquese con soporte';
            }
        } else {
            /* gestiona un error y establece parámetros para la respuesta de alerta. */

            $response["HasError"] = true;
            $response["AlertType"] = "danger";
            $response["AlertMessage"] = 'Ocurrio un error, comuniquese con soporte';

        }
    } else {
        /* Maneja errores estableciendo un mensaje de alerta para el usuario. */

        $response["HasError"] = true;
        $response["AlertType"] = "danger";
        $response["AlertMessage"] = 'Ocurrio un error, comuniquese con soporte';

    }

} catch (Exception $e) {
    /* Captura excepciones y las vuelve a lanzar sin modificaciones en PHP. */

    throw $e;
}


