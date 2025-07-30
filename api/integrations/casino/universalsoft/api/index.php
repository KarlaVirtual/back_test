<?php

/**
 * Este archivo contiene la implementación de una API para la integración con UniversalSoft.
 * Proporciona funcionalidades para manejar transacciones, verificar estados y realizar operaciones
 * relacionadas con UniversalSoft y UniversalSoft Services.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-02-06
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $_REQUEST              Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $_ENV                  Variable superglobal que contiene el entorno de variables del sistema.
 * @var mixed $headers               Variable que almacena los encabezados HTTP de la solicitud.
 * @var mixed $_SERVER               Variable superglobal que contiene información del servidor y entorno de ejecución.
 * @var mixed $name                  Variable que almacena el nombre de un archivo, usuario o entidad.
 * @var mixed $value                 Esta variable se utiliza para almacenar y manipular valores asociados a claves.
 * @var mixed $log                   Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $body                  Esta variable contiene el contenido del cuerpo de la solicitud HTTP.
 * @var mixed $data                  Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $URI                   Esta variable contiene el URI de la petición actual.
 * @var mixed $token                 Esta variable contiene el token de autenticación, utilizado para verificar y autorizar peticiones de forma segura.
 * @var mixed $transactionId         Variable que almacena el identificador único de una transacción.
 * @var mixed $roundid               Variable que almacena el identificador de una ronda de juego (posible duplicado de round).
 * @var mixed $Amount                Variable que almacena un monto o cantidad (posible duplicado de amount).
 * @var mixed $freespin              Variable que almacena información sobre giros gratis en un juego.
 * @var mixed $sign                  Variable que almacena una firma digital o de seguridad.
 * @var mixed $datos                 Variable que almacena datos genéricos.
 * @var mixed $UniversalSoft         Variable que almacena información relacionada con UniversalSoft.
 * @var mixed $response              Esta variable almacena la respuesta generada por una operación o petición.
 * @var mixed $serial                Variable que almacena un número de serie.
 * @var mixed $hora                  Variable que almacena una hora específica.
 * @var mixed $id_us                 Variable que almacena el identificador de un usuario.
 * @var mixed $idtx                  Variable que almacena el identificador de una transacción.
 * @var mixed $_GET                  Arreglo global que contiene los datos enviados mediante el método GET.
 * @var mixed $ids                   Variable que almacena una lista de identificadores.
 * @var mixed $UNIVERSALSOFTSERVICES Variable que almacena información sobre UniversalSoft Services.
 * @var mixed $respuesta             Esta variable se utiliza para almacenar y manipular la respuesta de una operación.
 * @var mixed $id                    Variable que almacena un identificador genérico.
 * @var mixed $estatus               Variable que almacena el estado de un proceso o entidad.
 * @var mixed $fecha                 Esta variable almacena una fecha, que puede indicar la creación, modificación o vencimiento de un registro.
 * @var mixed $respon                Variable que almacena una respuesta de un proceso o servicio.
 * @var mixed $user_name             Variable que almacena el nombre de usuario.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\UniversalSoft;
use Backend\integrations\casino\UNIVERSALSOFTSERVICES;

if ($_REQUEST['DXbDpfykzqwS'] == 'Q43c69X') {
    $_ENV['debug'] = true;
    error_reporting(E_ALL);
    ini_set('display_errors', 'ON');
}

header('Content-type: application/json; charset=utf-8');
$body = file_get_contents('php://input');

$body = preg_replace("[\n|\r|\n\r]", "", $body);
$body = str_replace("&", '","', $body);
$body = str_replace("=", '":"', $body);
$body = '{"' . $body . '"}';
$data = json_decode($body);

$URI = $_SERVER['REQUEST_URI'];

$data = json_decode(json_encode($_REQUEST));

if ($data->accion != "") {

    if ($data->accion == "venta_tck_API") {
        $token = $data->token;
        $transactionId = $data->id_tran;
        $roundid = $data->serial;
        $Amount = floatval($data->monto);

        $freespin = false;
        $sign = $_REQUEST["sign"];
        $datos = $data;

        /* Procesamos */
        $UniversalSoft = new UniversalSoft($token, $sign);
        $response = $UniversalSoft->CheckDebit("UniversalSoft", $Amount, $roundid, $transactionId, $datos, $freespin);

        print_r($response);
    }

    if ($data->accion == "final_tck_API2") {
        $transactionId = $data->id_tran;
        $serial = $data->serial;
        $hora = $data->hora;
        $id_us = $data->id_us;
        $idtx = $data->idtx;
        $Amount = floatval($data->monto);
        $datos = $data;

        /* Procesamos */
        $UniversalSoft = new UniversalSoft($token, $sign);
        $response = $UniversalSoft->Check($transactionId, $serial, $id_us, $hora, $idtx, $Amount, $datos);

        print_r($response);
    }

    if ($data->accion == "final_tck_API") {
        $token = $data->token;
        $transactionId = $data->id_tran;
        $serial = $data->serial;
        $hora = $data->hora;
        $id_us = $data->id_us;
        $idtx = $data->idtx;
        $Amount = floatval($data->monto);
        $freespin = false;
        $sign = $_REQUEST["sign"];
        $roundid = $data->serial;
        $datos = $data;

        $source = isset($data->k) ? $data->k : (isset($data->tck) ? $data->tck : null);

        /* Procesamos */
        $UniversalSoft = new UniversalSoft($token, $sign);

        if ($source) {
            foreach (json_decode($source) as $item) {
                $transactionId = $item->id_j;
                $Amount = $item->monto;
                $response = $UniversalSoft->Debit("UniversalSoft", $Amount, $roundid, $transactionId, $datos, $freespin);
            }
        } else {
            $response = $UniversalSoft->convertError("General Error", 1);
        }

        print_r($response);
    }

    if ($_GET["accion"] == "status_tck") {
        $ids = $_GET["id"];

        $UNIVERSALSOFTSERVICES = new UNIVERSALSOFTSERVICES();

        $respuesta = $UNIVERSALSOFTSERVICES->status_tck_API($ids);

        if ($respuesta->response->resp == "OK") {
            $UniversalSoft = new UniversalSoft($token, $sign);

            foreach ($respuesta->response->data as $datos) {
                $id = $datos->id_jugada;

                if ($id == '' || $id == '0') {
                    $id = $datos->id_j;
                }

                $id_us = $datos->id_us;
                $Amount = floatval($datos->monto);
                $estatus = $datos->estatus;
                $fecha = $datos->fecha;
                $serial = $datos->serial;

                if ($estatus == "G") {
                    $response = $UniversalSoft->Credit("UniversalSoft", $Amount, $serial, $id, $datos, $ids);

                    $response = json_decode($response);
                    $response->id_operacion = intval($ids);
                    $response = json_encode($response);

                    $respon = $UNIVERSALSOFTSERVICES->confirma_status_API($response);
                    $respon = $respon->response;
                    unset($respon->response->id_operacion);
                    $respon = json_encode($respon);
                }

                if ($estatus == "D") {
                    $user_name = $datos->user_name;

                    $response = $UniversalSoft->Rollback($Amount, $serial, $id_us, str_replace('Usuario', '', $user_name), $datos);

                    $response = json_decode($response);
                    $response->accion = 'confirma_status_API';
                    $response->id_operacion = intval($ids);
                    $response = json_encode($response);
                    $respon = $UNIVERSALSOFTSERVICES->confirma_status_API($response);
                    $respon = $respon->response;
                    $respon = json_encode($respon);
                }

                if ($estatus == "A" || $estatus == "R") {
                    $response = $UniversalSoft->Rollback($Amount, $serial, $id, $id_us, $datos);

                    $response = json_decode($response);
                    $response->id_operacion = intval($ids);
                    $response = json_encode($response);
                    $respon = $UNIVERSALSOFTSERVICES->confirma_status_API($response);
                    $respon = $respon->response;
                    unset($respon->response->id_operacion);
                    $respon = json_encode($respon);
                }

                print_r($respon);
            }
        }
    }
}
