<?php

use Backend\dto\BonoInterno;
use Backend\dto\SorteoDetalle;
use Backend\dto\SorteoInterno;
use Backend\mysql\SorteoDetalleMySqlDAO;
use Backend\mysql\SorteoInternoMySqlDAO;

/**
 * Actualiza los datos de un sorteo en el sistema.
 *
 * @param array $params Arreglo que contiene los siguientes valores:
 * @param int $params['Id'] Identificador del sorteo.
 * @param array $params['RanksPrize'] Detalles de los premios por rango.
 * @param string $params['EndDate'] Fecha de finalización del sorteo.
 * @param string $params['BackgroundURL'] URL de fondo del sorteo.
 * @param string $params['CardBackgroundURL'] URL de fondo de la tarjeta.
 * @param string $params['MainImageURL'] URL de la imagen principal.
 *
 * @return array $response Respuesta del sistema con los siguientes valores:
 *  - idLottery: int ID del sorteo actualizado.
 *  - HasError: bool Indica si hubo un error.
 *  - AlertType: string Tipo de alerta (success, danger, etc.).
 *  - AlertMessage: string Mensaje de alerta.
 *  - ModelErrors: array Errores específicos del modelo.
 *  - Result: array Resultado de la operación.
 */

/* Verifica si $params está vacío; si es así, termina la ejecución. Luego, decodifica. */
if (empty($params)) die;

$params = json_decode(json_encode($params), true);


/* Se obtiene información de premios de una lotería usando su identificador. */
$LotteryId = $params['Id'];

$SorteoInterno = new SorteoInterno($LotteryId);

$data = json_decode($SorteoInterno->jsonTemp, true);

$CurrentData = $data['RanksPrize'];
$RanksPrize = $params['RanksPrize'];
$EndDate = $params['EndDate'];
$BackgroundURL = $params['BackgroundURL'];
$CardBackgroundURL = $params["CardBackgroundURL"];
$MainImageURL = $params['MainImageURL'];

try {

    /* Actualiza detalles de premios en una lotería basados en un arreglo de posiciones. */
    $RanksPrize = array_values($RanksPrize);

    foreach ($RanksPrize as $currency) {
        if (isset($currency['Amount']) && is_array($currency['Amount'])) {
            foreach ($currency['Amount'] as $prize) {
                $position = $prize['position'];
                $urlImg = $prize['urlImg'];
                $description = $prize['description'];
                $type = $prize['type'];


                $SorteoDetalle = new SorteoDetalle("", $LotteryId, "RANKAWARDMAT", "", $position);
                $SorteoDetalle->setImagenUrl($urlImg);
                $SorteoDetalleMySqlDAO = new SorteoDetalleMySqlDAO();
                $transaction = $SorteoDetalleMySqlDAO->getTransaction();
                $SorteoDetalleMySqlDAO->update($SorteoDetalle);
                $SorteoDetalleMySqlDAO->getTransaction()->commit();
            }
        }
    }
} catch (Exception $e) {
    /* Maneja excepciones en PHP, permitiendo continuar sin interrumpir el flujo del programa. */

}


/* actualiza un registro de imagen en la base de datos si existe. */
if ($MainImageURL != '') {
    $SorteoDetalle = new SorteoDetalle("", $LotteryId, "IMGPPALURL");
    $SorteoDetalle->valor = $MainImageURL;
    $SorteoDetalle->setImagenUrl($MainImageURL);


    $SorteoDetalleMySqlDAO = new SorteoDetalleMySqlDAO();
    $transaction = $SorteoDetalleMySqlDAO->getTransaction();
    $SorteoDetalleMySqlDAO->update($SorteoDetalle);
    $SorteoDetalleMySqlDAO->getTransaction()->commit();
}


/* Actualiza el detalle del sorteo con una nueva URL de fondo si está definida. */
if ($BackgroundURL != "") {
    $SorteoDetalle = new SorteoDetalle("", $LotteryId, "BACKGROUNDURL");
    $SorteoDetalle->valor = $BackgroundURL;
    $SorteoDetalle->setImagenUrl($BackgroundURL);

    $SorteoDetalleMySqlDAO = new SorteoDetalleMySqlDAO();
    $transaction = $SorteoDetalleMySqlDAO->getTransaction();
    $SorteoDetalleMySqlDAO->update($SorteoDetalle);
    $SorteoDetalleMySqlDAO->getTransaction()->commit();
}


/* Actualiza el detalle del sorteo con la URL de fondo de tarjeta. */
if ($CardBackgroundURL != "") {
    $SorteoDetalle = new SorteoDetalle("", $LotteryId, "CARDBACKGROUNDURL");
    $SorteoDetalle->valor = $CardBackgroundURL;
    $SorteoDetalle->setImagenUrl($CardBackgroundURL);

    $SorteoDetalleMySqlDAO = new SorteoDetalleMySqlDAO();
    $transaction = $SorteoDetalleMySqlDAO->getTransaction();
    $SorteoDetalleMySqlDAO->update($SorteoDetalle);
    $SorteoDetalleMySqlDAO->getTransaction()->commit();
}


/* proporciona información sobre la fecha máxima de entrenamiento de un modelo. */
$SorteoInternoMySqlDAO = new SorteoInternoMySqlDAO();
$transaction = $SorteoInternoMySqlDAO->getTransaction();

// preguntaremos por los detalles del sorteo

foreach ($CurrentData as $key => $value) {
    if (oldCount($value['Amount']) !== oldCount($RanksPrize[$key]['Amount'])) {

        $newData = array_diff_assoc($RanksPrize[$key]['Amount'], $value['Amount']);

        if (oldCount($newData) === 0) continue;

        foreach ($newData as $key => $newValue) {
            $type = '';
            $description = '';
            switch ($newValue['type']) {
                case 0:
                    $type = 'RANKAWARDMAT';
                    $description = $newValue['description'];
                    break;
                case 2:
                    $type = 'BONO';
                    $bonusId = $newValue['amount'];
                    $BonoInterno = new BonoInterno($bonusId);
                    if ($BonoInterno->tipo == 5) $description = 'Saldo FreeCasino';
                    if ($BonoInterno->tipo == 6) $description = 'Saldo FreeBet';
                    break;
                default:
                    $type = 'RANKAWARD';
                    if ($newValue['description'] == 0) $description = 'Saldo Creditos';
                    if ($newValue['description'] == 1) $description = 'Saldo Premios';
                    if ($newValue['description'] == 2) $description = 'Saldo Bonos';
                    break;
            }

            $SorteoDetalle = new SorteoDetalle();
            $SorteoDetalle->sorteoId = $LotteryId;
            $SorteoDetalle->tipo = $type;
            $SorteoDetalle->moneda = $value['CurrencyId'];
            $SorteoDetalle->valor = $newValue['position'];
            $SorteoDetalle->valor2 = $newValue['idBono'];
            $SorteoDetalle->valor3 = $newValue['amount'];
            $SorteoDetalle->usucreaId = $_SESSION['usuario'];
            $SorteoDetalle->usumodifId = 0;
            $SorteoDetalle->descripcion = $description;
            $SorteoDetalle->estado = 'A';
            $SorteoDetalle->imagenUrl = $newValue['amount'];
            $SorteoDetalle->permiteGanador = $newValue['winningCoupons'];
                $SorteoDetalle->jugadorExcluido = $newValue['winningPlayers'];
            $SorteoDetalle->fechaSorteo = empty($newValue['fixedTime']) ? date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $EndDate))) : date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $newValue['fixedTime'])));

            $SorteoDetalleMySqlDAO = new SorteoDetalleMySqlDAO($transaction);
            $SorteoDetalleMySqlDAO->insert($SorteoDetalle);
        }
    }
}


/* actualiza un registro en la base de datos y configura una respuesta. */
$SorteoInterno->jsonTemp = json_encode($params);
$SorteoInternoMySqlDAO->update($SorteoInterno);
$SorteoInternoMySqlDAO->getTransaction()->commit();

$response = [];
$response['idLottery'] = $LotteryId;

/* Código que establece una respuesta exitosa sin errores o mensajes de alerta. */
$response['HasError'] = false;
$response['AlertType'] = 'success';
$response['AlertMessage'] = '';
$response['ModelErrors'] = [];
$response['Result'] = [];
?>