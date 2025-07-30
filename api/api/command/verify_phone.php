<?php
//require('../../vendor/autoload.php');
use Backend\dto\Registro;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioOtrainfo;
use Backend\integrations\mensajeria\Bigid;
use Backend\dto\Usuario;
use Backend\mysql\UsuarioMySqlDAO;

/**
 * Envío de mensajes y verificación de código de celular
 *
 * Este código maneja el proceso de envío de mensajes o verificación de códigos asociados a un número de teléfono.
 * Dependiendo del `site_id` (14 o 17), se realiza la verificación de tipo 1 (envío de mensaje) o tipo 2 (verificación del código).
 * También se gestionan las verificaciones de celular de usuarios mediante la clase `Bigid` y el sistema de registros.
 *
 * @param object $json : Objeto que contiene los parámetros y la sesión del usuario.
 *
 * El objeto `$response` es un array con los siguientes atributos:
 *  - *code* (int): Código de respuesta del servicio, donde 0 indica éxito.
 *  - *message* (string): Mensaje generado por el servicio.
 *  - *data* (array): Contiene datos adicionales relacionados con el proceso de verificación.
 *
 * Objeto en caso de error:
 *
 * "code" => [Código de error],
 * "message" => "[Mensaje de error]",
 * "data" => array(),
 *
 * @throws Exception En caso de error al intentar enviar el mensaje o al realizar la verificación del código.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* Se extraen los valores de 'phone' y 'site_id' de un objeto JSON. */
$phone = $json->params->phone;
$site_id = $json->params->site_id;
if ($site_id == 14 || $site_id == 17) {

    if ($phone != '') {

        /* envía mensajes o comprobaciones basadas en parámetros y condición del sitio. */
        $rid = $json->rid;

        if ($site_id == 14 || $site_id == 17) {

            $api = new Bigid();

            switch ($json->params->type) {
                case 1:

                    $response = $api->sendMessage($phone, $rid, $site_id);
                    break;
                case 2:

                    $response = $api->sendCheck($json->params->ticked, $json->params->code, $rid, $site_id);

                    break;
            }

        }
    } else {


        /* Código que verifica condiciones y envía una petición a una API si se cumple. */
        $rid = $json->rid;

        if ($json->params->type == '2' && $json->session->usuario == '') {
            $api = new Bigid();

            $response = $api->sendCheck($json->params->ticked, $json->params->code, $rid, $site_id);


        } else {


            /* Se instancia un objeto UsuarioMandante y se obtiene el identificador 'rid' del JSON. */
            $UsuarioMandante = new UsuarioMandante($json->session->usuario);
            $rid = $json->rid;

            if ($json->session->usuario != "" && ($UsuarioMandante->getMandante() == 14 || $UsuarioMandante->getMandante() == 17)) {


                /* Se crean instancias de Usuario y Registro, y se inicializa la API Bigid. */
                $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

                $Regis = new Registro("", $Usuario->usuarioId);

                $api = new Bigid();

                switch ($json->params->type) {
                    case 1:
                        /* Envía un mensaje a un número celular usando la API y datos específicos. */


                        $response = $api->sendMessage($Regis->celular, $rid, $Usuario->mandante);
                        break;
                    case 2:
                        /* Envía verificación celular y actualiza datos del usuario si la respuesta es positiva. */


                        $response = $api->sendCheck($json->params->ticked, $json->params->code, $rid, $Usuario->mandante);

                        if ($response['code'] == 0) {
                            $Usuario->verifCelular = 'S';
                            $Usuario->fechaVerifCelular = date('Y-m-d H:i:s');

                            $UsuarioMySqlDAO = new UsuarioMySqlDAO();

                            $UsuarioMySqlDAO->update($Usuario);

                            $UsuarioMySqlDAO->getTransaction()->commit();
                        }

                        break;
                }

            }
        }
    }

}
