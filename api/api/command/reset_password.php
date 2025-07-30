<?php

use Backend\dto\Banco;
use Backend\dto\BonoDetalle;
use Backend\dto\Categoria;
use Backend\dto\Clasificador;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Consecutivo;
use Backend\dto\CuentaCobro;
use Backend\dto\Descarga;
use Backend\dto\DocumentoUsuario;
use Backend\dto\IntApuestaDetalle;
use Backend\dto\IntCompetencia;
use Backend\dto\IntDeporte;
use Backend\dto\IntEventoApuesta;
use Backend\dto\IntEventoApuestaDetalle;
use Backend\dto\IntEventoDetalle;
use Backend\dto\IntRegion;
use Backend\dto\ItTicketEnc;
use Backend\dto\Mandante;
use Backend\dto\MandanteDetalle;
use Backend\dto\PaisMoneda;
use Backend\dto\PromocionalLog;
use Backend\dto\PuntoVenta;
use Backend\dto\Registro;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioBanco;
use Backend\dto\UsuarioConfig;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\UsuarioLog;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioOtrainfo;
use Backend\dto\UsuarioPerfil;
use Backend\dto\UsuarioPremiomax;
use Backend\dto\UsuarioPublicidad;
use Backend\dto\UsuarioRecarga;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioMensaje;
use Backend\dto\ProductoMandante;
use Backend\dto\ProdMandanteTipo;
use Backend\dto\Proveedor;
use Backend\dto\Producto;
use Backend\dto\Pais;
use Backend\integrations\casino\IES;
use Backend\integrations\casino\JOINSERVICES;
use Backend\mysql\ConsecutivoMySqlDAO;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\DocumentoUsuarioMySqlDAO;
use Backend\mysql\PromocionalLogMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\RegistroMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\UsuarioBancoMySqlDAO;
use Backend\mysql\UsuarioBannerMySqlDAO;
use Backend\mysql\UsuarioConfiguracionMySqlDAO;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\mysql\UsuarioMandanteMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioOtrainfoMySqlDAO;
use Backend\mysql\UsuarioPerfilMySqlDAO;
use Backend\mysql\UsuarioPremiomaxMySqlDAO;
use Backend\mysql\UsuarioRecargaMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\websocket\WebsocketUsuario;

/**
 * command/reset_password
 *
 * Actualizar clave del usuario
 *
 * @param string $new_password : nueva clave a guardar
 * @param string $reset_code : token de reseteo que permite cambiar la contraseña
 *
 * @return object El objeto $response es un array con los siguientes atributos:
 *  - *code* (int): Codigo de error desde el proveedor
 *  - *rid* (string): Contiene el mensaje de error.
 *  - *data* (array): Contiene el mensaje de aprobación del proceso.
 *  - *HasError* (bool): Indica si hubo un error en la operación.
 *  - *AlertType* (string): Especifica el tipo de alerta que se mostrará en la vista.
 *  - *AlertMessage* (string): Contiene el mensaje que se mostrará en la vista.
 *
 * Objeto en caso de error:
 *
 * $response["HasError"] = true;
 * $response["AlertType"] = "danger";
 * $response["AlertMessage"] = 'Ocurrio un error, comuniquese con soporte0';
 *
 *
 * @throws Exception Error General
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* Se crea una respuesta JSON con código y resultado específicos. */
$ConfigurationEnvironment = new ConfigurationEnvironment();
$response = array();
$response["code"] = 0;
$response["rid"] = $json->rid;
$response["data"] = array(
    "result" => "-1119"

);


/* Asigna un nuevo password y código de reinicio desde parámetros JSON a variables. */
$new_password = $json->params->new_password;
$reset_code = $json->params->reset_code;


$ConfigurationEnvironment = new ConfigurationEnvironment();


$token = $reset_code;

/* asigna y decodifica un token de activación para el nuevo password. */
$password = $new_password;

$token = str_replace(" ", "+", $token);
$activation_code = $token;

$activation_code = urldecode($activation_code);

/* reemplaza espacios por '+' y verifica la presencia de guiones bajos. */
$activation_code = str_replace(" ", "+", $activation_code);

$code = ($ConfigurationEnvironment->decrypt($activation_code, $ENCRYPTION_KEY));
$seguir = true;

if (strpos($code, "_") == -1) {
    $seguir = false;
}


/* verifica si la contraseña está vacía y establece una variable en falso. */
if ($password == "") {
    $seguir = false;

}

try {
    if ($seguir) {

        /* Divida el código en partes, verifica si es numérico y maneja errores. */
        $usuariologId = explode("_", $code)[0];

        if (!is_numeric($usuariologId)) {
            $response["HasError"] = true;


            $response["AlertType"] = "danger";
            $response["AlertMessage"] = 'Ocurrio un error, comuniquese con soporte0';
            throw new Exception("Error General", "100000");

        } else {

            /* Calcula la diferencia en horas entre la fecha de creación y el tiempo actual. */
            $UsuarioLog = new UsuarioLog($usuariologId);

            $hourdiff = round((strtotime($UsuarioLog->fechaCrea) - time()) / 3600, 2);

            if ($seguir) {

                /* Verifica si el código de activación es diferente al valor antes del usuario. */
                if (str_replace("", "", $UsuarioLog->getValorAntes()) != $activation_code) {
                    $response["HasError"] = true;
                    $response["AlertMessage"] = 'Ocurrio un error, comuniquese con soporte';
                    throw new Exception("Error General", "100000");

                    $seguir = false;
                } else {


                    /* Verifica si una recuperación de usuario ha expirado o no. */
                    $hourdiff = round((strtotime($UsuarioLog->fechaCrea) - time()) / 3600, 2);

                    if ($hourdiff > 24 || $UsuarioLog->getEstado() != 'P') {
                        $response["HasError"] = true;
                        $response["AlertMessage"] = 'El recurso de recuperación ha expirado';
                        $seguir = false;
                        throw new Exception("Error General", "100000");

                    }


                    /* verifica el tipo de usuario y gestiona un error si es inválido. */
                    if ($UsuarioLog->getTipo() != 'TOKENPASS' && $UsuarioLog->getTipo() != 'TOKENPASSIMPORT') {
                        $response["HasError"] = true;
                        $response["AlertMessage"] = 'El recurso de recuperación ha expirado';
                        $seguir = false;
                        throw new Exception("Error General", "100000");
                    }


                    if ($seguir) {

                        /* Verifica el estado de un usuario y lanza error si ya fue restaurado. */
                        $Usuario = new Usuario ($UsuarioLog->getUsuarioId());

                        if ($UsuarioLog->getTipo() == "TOKENPASSIMPORT") {
                            if ($Usuario->estadoImport == '3') {
                                $response["HasError"] = true;
                                $response["AlertMessage"] = 'Su cuenta ya fue restaurada, si tiene alguna inquietud puede comunicarse con soporte.';

                                throw new Exception("Error General", "100000");
                                $seguir = false;
                            }

                        }

                        if ($seguir) {


                            /* Lanza una excepción si el usuario tiene un ID específico; luego cambia la contraseña. */
                            if ($Usuario->usuarioId == 126457) {

                                throw new Exception("Error", "100001");

                            }
                            $Usuario->changeClave($password);


                            /* Reinicia los intentos y activa al usuario si está inactivo. */
                            if ($Usuario->intentos > 0) {
                                $Usuario->intentos = 0;
                            }

                            if ($Usuario->estado == 'I') {
                                $Usuario->estado = 'A';
                            }


                            /* cambia el estado de un usuario y registra la acción. */
                            if ($Usuario->estadoImport == '2') {
                                $Usuario->estadoImport = '3';
                                $Usuario->estadoEsp = 'A';
                            }


                            $UsuarioLog->setEstado('A');

                            /* Actualiza registros de usuario y log en una transacción MySQL. */
                            $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
                            $UsuarioLogMySqlDAO->update($UsuarioLog);

                            $UsuarioMySqlDAO = new UsuarioMySqlDAO($UsuarioLogMySqlDAO->getTransaction());
                            $UsuarioMySqlDAO->update($Usuario);

                            $UsuarioLogMySqlDAO->getTransaction()->commit();


                            /* establece que no hay errores en la respuesta. */
                            $response["HasError"] = false;


                        }
                    }


                }
            } else {
                /* maneja errores, configurando un mensaje de alerta y lanzando una excepción. */

                $response["HasError"] = true;
                $response["AlertType"] = "danger";
                $response["AlertMessage"] = 'Ocurrio un error, comuniquese con soporte1';
                throw new Exception("Error General", "100000");
            }
        }
    } else {
        /* Maneja errores asignando un mensaje de alerta y lanzando una excepción. */

        $response["HasError"] = true;
        $response["AlertType"] = "danger";
        $response["AlertMessage"] = 'Ocurrio un error, comuniquese con soporte2';
        throw new Exception("Error General", "100000");

    }

} catch (Exception $e) {
    /* Captura una excepción y la vuelve a lanzar para su manejo posterior. */

    throw $e;
}



