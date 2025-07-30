<?php

/**
 * Este archivo contiene un script para procesar y manejar solicitudes relacionadas con la integración
 * de la API de casino 'SISVENPROL'. Incluye operaciones como autenticación, consulta de saldo,
 * colocación de apuestas, resolución de tickets, revocación de transacciones y manejo de datos significativos.
 *
 * @category   Integración
 * @package    API
 * @subpackage Sisvenprol
 * @author     Karla Ramirez <karla.ramirez@virtualsoft.tech>
 * @version    ninguna
 * @since      2025-05-28
 */


header('Content-type: application/json');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\integrations\casino\Sisvenprol;

$_ENV["ENABLEDSETLOCKWAITTIMEOUT"] = '1';

$URI = $_SERVER['REQUEST_URI'];

$x_auth_signature = $_SERVER['HTTP_X_AUTH_SIGNATURE'];

$body = file_get_contents('php://input');
$body = trim(preg_replace("[\n|\r|\n\r]", "", $body));

// Intentamos decodificar directamente como JSON
$data = json_decode($body);

// Si json_decode falla, es texto tipo query string
if (json_last_error() !== JSON_ERROR_NONE || !is_object($data)) {
    parse_str($body, $parsedData);
    $data = json_decode(json_encode($parsedData));
}

if (true) {

    $URI = explode('/', $URI);
    $URI = $URI[count($URI) - 1];

    if ($URI == "GetBalance") {

        $user_Id = $data->user_id;
        $currency_id = $data->currency_id;

        /* Procesamos */
        $Sisvenprol = new Sisvenprol('', $user_Id);
        $response = ($Sisvenprol->getBalance());
    }

    if ($URI == "Rollback") {

        $user_id = $data->user_id;
        $roundId = $data->ticket_id;  // ticket Id
        $currency_id = $data->currency_id;

        $IsEndRound = true;

        /* Procesamos */
        $Sisvenprol = new Sisvenprol("", $user_id);
        $response = ($Sisvenprol->Rollback($roundId, $roundId . '_D', $user_id, $data, $IsEndRound));
    }

    if ($URI == "MultiCedit") {

        foreach ($data->rewarding as $rewarding) {
            $date = $rewarding->date;
            $schedule_id = $rewarding->schedule_id;
            $transactionId = $rewarding->draw_id;
            $result = $rewarding->result;
            $Id = str_replace(' ', '', $result);

            // Verificamos si rewards contiene elementos
            if (!empty((array)$rewarding->rewards)) {
                // Recorremos cada reward (puede haber más de uno por draw)
                foreach ($rewarding->rewards as $reward) {
                    $roundId = $reward->ticket_id;
                    $amount = $reward->amount;
                    $user_id = $reward->user_id;

                    $datos = $data;

                    /* Procesamos */
                    $Sisvenprol = new Sisvenprol('', $user_id);
                    $response = $Sisvenprol->Credit('', $amount, $roundId, $roundId . $Id . $schedule_id . '_C', json_encode($datos), $roundClosed = false);

                }
            }
        }
    }

    if ($URI == "RollbackPremios") {

        foreach ($data->rewarding_canceled as $rewarding) {
            $date = $rewarding->date;
            $schedule_id = $rewarding->schedule_id;
            $transactionId = $rewarding->draw_id;
            $result = $rewarding->result;
            $Id = str_replace(' ', '', $result);

            // Verificamos si rewards contiene elementos
            if (!empty((array)$rewarding->rewards)) {
                // Recorremos cada reward (puede haber más de uno por draw)
                foreach ($rewarding->rewards as $reward) {
                    $roundId = $reward->ticket_id;
                    $amount = $reward->amount;
                    $user_id = $reward->user_id;

                    $datos = $data;

                    /* Procesamos */
                    $Sisvenprol = new Sisvenprol('', $user_id);
                    $response = ($Sisvenprol->Rollback($roundId, $roundId . $Id . $schedule_id . '_C', $user_id, $data, $IsEndRound));

                }
            }
        }
    }

    if ($URI == "Triples") {

        $user_id = $data->user_id;
        $roundId = $data->ticket_id;
        $ticket = $data->ticket;
        $ticket_total = $ticket->total;
        $ticket_total = $ticket->id;
        $ticket_reward = $ticket->reward;
        $ticket_date = $ticket->date;

        foreach ($ticket->draws as $draw_id => $draw) {
            $draw_name = $draw->name;
            $draw_code = $draw->code;
            $draw_total = $draw->total;

            // Verificamos si hay detalles
            if (!empty($draw->details)) {
                foreach ($draw->details as $detail) {
                    $detail_id = $detail->id;
                    $amount = $detail->amount;
                    $reward = $detail->reward;
                    $number = $detail->number;

                    $freespin = false;
                    $transactionId = $roundId . $detail_id;
                    $datos = $data;

                    /* Procesamos */
                    $Sisvenprol = new Sisvenprol('', $user_id);
                    $response = $Sisvenprol->Debit('', $amount, $roundId, $transactionId . '_DT', json_encode($datos), $freespin, $URI);
                }

            }
        }
    }

    if ($URI == "Debit") {

        $user_Id = $data->user_id;
        $lotteryId = $data->draw_id; //Id del sorteo
        $draw_date = $data->draw_date;
        $amount = $data->amount;
        $roundId = $data->ticket_id;
        $currency_id = $data->currency_id;

        $freespin = false;

        $datos = $data;

        /* Procesamos */
        $Sisvenprol = new Sisvenprol('', $user_Id);
        $response = $Sisvenprol->Debit('', $amount, $roundId, $roundId . '_D', json_encode($datos), $freespin, $URI);
    }

    if ($URI == "CTriples") {

        // se accede a la sección de data que llega en el body
        $DataBody = $data->data;

        // Verificamos si hay ganadores
        if (!empty($DataBody->winners)) {

            foreach ($DataBody->winners as $winner) {
                $roundId = $winner->ticket_id;
                $transactionId = $winner->id;
                $amount = $winner->amount;
                $reward = $winner->reward;
                $user_id = $winner->client_user_id;

                $datos = $data; // Si necesitas enviar toda la data

                // Procesamos cada transacción individual
                $Sisvenprol = new Sisvenprol('', $user_id);
                $response = $Sisvenprol->Credit('', $reward, $roundId, $roundId . $transactionId . '_CT', json_encode($datos), $roundClosed = false);
            }
        }
    }

    if ($URI == "TriplesRoll") {

        // se accede a la sección de data que llega en el body
        $DataBody = $data->data;

        // Datos generales del draw
        $DataId = $DataBody->id;
        $DataCode = $DataBody->code;
        $DataDate = $DataBody->date;
        $DataName = $DataBody->name;

        // Verificamos si hay ganadores
        if (!empty($info->winners)) {

            foreach ($info->winners as $winner) {
                $roundId = $winner->ticket_id;
                $transactionId = $winner->id;
                $amount = $winner->amount;
                $reward = $winner->reward;
                $user_id = $winner->client_user_id;

                $datos = $data; // Si necesitas enviar toda la data

                // Procesamos cada transacción individual
                $Sisvenprol = new Sisvenprol('', $user_id);
                $response = ($Sisvenprol->Rollback($roundId, $roundId . '_CT', $user_id, $data, $IsEndRound));
            }
        }
    }

}

print_r($response);
