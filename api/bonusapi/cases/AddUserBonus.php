<?php

use Backend\sql\Transaction;
use Backend\dto\BonoInterno;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\dto\UsuarioMandante;
use Backend\sql\SqlQuery;
use Backend\dto\UsuarioRecarga;
use Backend\dto\AuditoriaGeneral;
use Backend\mysql\AuditoriaGeneralMySqlDAO;

/**
 * bonusapi/cases/AddUserBonus
 *
 * Procesamiento y Asignación de Bonos
 *
 * Este script maneja el proceso de validación, asignación y auditoría de bonos para los jugadores. Recibe parámetros
 * a través de solicitudes, valida la información, recupera los datos relacionados con los depósitos y jugadores,
 * y realiza la asignación del bono correspondiente. También realiza auditoría de las acciones ejecutadas y gestiona
 * los errores durante el proceso.
 *
 * @param object $params Parámetros recibidos que incluyen datos de jugadores, ID de depósitos, y el ID del bono.
 *
 * @return void
 *
 * @throws Exception En caso de errores durante la asignación de bonos o errores lógicos dentro del proceso de asignación.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/**Recibiendo inputs*/

/* asigna parámetros y valida entradas en un sistema de gestión de jugadores. */
$player = $params->Players;
$playersIdCsv = $params->PlayersIdCsv;
$recargaId = $params->Deposit;
$bonoId = $params->Id;

/** Validando inputs */
$coincidences = [];
$coincidences[] = preg_match('#[^\d]{1}#', $bonoId);
if ($coincidences[0] == 1 || $coincidences[0] === false) {
    $response["HasError"] = true;
    $response["AlertType"] = "danger";
    $response["AlertMessage"] = "Bono inválido";
    $response["ModelErrors"] = [];
    return;
} else $coincidences = [];

/** Recuperando bono */
$BonoInterno = new BonoInterno($bonoId);
if ($BonoInterno->estado == 'I') {
    $response["HasError"] = true;
    $response["AlertType"] = "danger";
    $response["AlertMessage"] = "Bono Inactivo";
    $response["ModelErrors"] = [];
    return;
}

/** Cargando data para usuario sujeto de la solicitud */
$playersData = [];
$failedRequisitions = [];
$totalRequisitions = 0;

/** Cargando valores enviados por parámetro */
if (!empty($player)) {
    $totalRequisitions++;

    //Validando inputs
    $coincidences[] = preg_match('#[^\d]{1}#', $player);
    $coincidences[] = preg_match('#[^\d]{1}#', $recargaId);

    if (in_array(false, $coincidences, true) || in_array(1, $coincidences, true)) {
        // Definiendo y almacenando fallo/Rechazo
        $failedRequisitions[] = ['UserId' => $player, 'DepositId' => $recargaId, 'Reason' => 'RejectedData'];
    } else $playersData[] = ['UserId' => $player, 'DepositId' => $recargaId];
}

/** Cargando valores enviados por CSV */
if (empty($playersData) && empty($failedRequisitions)) {
    // Formateando data CSV
    $playersIdCsv = explode("base64,", $playersIdCsv);
    $playersIdCsv = base64_decode($playersIdCsv[1]);
    $playersIdCsv = str_replace(';', ',', $playersIdCsv);
    $playersIdCsv = preg_split('/\r\n|\n|\r/', $playersIdCsv);
    $playersIdCsv = array_filter($playersIdCsv);
    $playersIdCsv = array_values($playersIdCsv);

    //Estructurando grupos de datos para solicitudes
    $playersData = array_map(function ($dataRow) use (&$failedRequisitions, &$totalRequisitions) {
        $totalRequisitions++;
        $requisitionTuple = explode(',', $dataRow);
        $playerData = null;

        //Validando inputs
        $coincidences[] = preg_match('#[^\d]{1}#', $requisitionTuple[0]); //Validando usuarioId
        $coincidences[] = preg_match('#[^\d]{1}#', $requisitionTuple[1]); // Validando recargaId

        if (in_array(false, $coincidences, true) || in_array(1, $coincidences, true)) {
            //Tupla inválida
            $failedRequisitions[] = ['UserId' => $requisitionTuple[0], 'DepositId' => $requisitionTuple[1], 'Reason' => 'RejectedData'];
            $playerData = false;
        } else $playerData = ['UserId' => $requisitionTuple[0], 'DepositId' => $requisitionTuple[1]];

        return $playerData;
    }, $playersIdCsv);

    $playersData = array_filter($playersData);
    $playersData = array_values($playersData);
}

/** Realizando solicitudes de asignación*/
foreach ($playersData as $playerData) {
    /**Recuperando detalles que evaluará la función encargada de agregar el bono*/
    try {
        $Transaction = new Transaction();
        $BonoInternoMySqlDAO = new BonoInternoMySqlDAO($Transaction);
        $player = $playerData['UserId'];
        $recargaId = $playerData['DepositId'];

        //array de Detalles vacío
        $detalles = [
            "Depositos" => "",
            "DepositoEfectivo" => "",
            "MetodoPago" => "",
            "ValorDeposito" => "",
            "PaisPV" => "",
            "DepartamentoPV" => "",
            "CiudadPV" => "",
            "PuntoVenta" => "",
            "PaisUSER" => "",
            "DepartamentoUSER" => "",
            "CiudadUSER" => "",
            "MonedaUSER" => ""
        ];

        //Recuperando total depósitos realizados por el usuario
        $sql = "select count(*) from usuario_recarga where usuario_id = ?";
        $SqlQuery = new SqlQuery($sql);
        $SqlQuery->set($player);
        $totalUserDeposits = $BonoInternoMySqlDAO->querySQL($SqlQuery->getQuery())[0][0];//Recuperando si el deposito se hará en efectivo
        $sql = "select bd.* from bono_detalle bd where bd.bono_id = ? and bd.tipo like 'CONDEFECTIVO'";
        $SqlQuery = new SqlQuery($sql);
        $SqlQuery->set($bonoId);
        $isCash = $BonoInternoMySqlDAO->querySQL($SqlQuery->getQuery())[0]['bd.valor'];
        $isCash = $isCash == "" ? false : $isCash;//Recuperando valor depósito -- Es 0, el valor es asignado bajo la política del bono disponible
        $depositValue = 0;

        //Recuperando método pago si la recarga NO se realizó por punto de venta
        $payMethod = "";
        if ($recargaId != '') {
            $UsuarioRecarga = new UsuarioRecarga($recargaId);
            $depositValue = $UsuarioRecarga->valor;
            if ($UsuarioRecarga->puntoventaId == 0) {
                $sql = "select tp.producto_id from transaccion_producto tp where tp.final_id = ?";
                $SqlQuery = new SqlQuery($sql);
                $SqlQuery->set($UsuarioRecarga->recargaId);
                $payMethod = $BonoInternoMySqlDAO->querySQL($SqlQuery->getQuery())[0]['tp.producto_id'];
            }

        }

        //Recuperando país, departamento, ciudad del punto de venta
        $countryPV = 0;
        $departmentPV = 0;
        $cityPV = 0;
        if ($UsuarioRecarga->puntoventaId != 0) {
            $sql = "select c.ciudad_id, c.depto_id, d.pais_id from usuario_recarga ur inner join registro r on (ur.puntoventa_id = r.usuario_id) inner join ciudad c on (r.ciudad_id = c.ciudad_id) inner join departamento d on (c.depto_id = d.depto_id) where ur.recarga_id = ?";
            $SqlQuery = new SqlQuery($sql);
            $SqlQuery->set($recargaId);
            $pvGeography = $BonoInternoMySqlDAO->querySQL($SqlQuery->getQuery())[0];
            $countryPV = $pvGeography["d.pais_id"];
            $departmentPV = $pvGeography["c.depto_id"];
            $cityPV = $pvGeography["c.ciudad_id"];
        }

        //Recuperando el punto de venta que realizó la recarga
        $sellingPoint = $UsuarioRecarga->puntoventaId;//Recuperando país, departamento, ciudad del usuario
        $countryUser = 0;
        $departmentUser = 0;
        $cityUser = 0;
        $sql = "select c.ciudad_id, c.depto_id, d.pais_id from usuario u inner join registro r on (u.usuario_id = r.usuario_id) inner join ciudad c on (r.ciudad_id = c.ciudad_id) inner join departamento d on (c.depto_id = d.depto_id) where u.usuario_id = ?";
        $SqlQuery = new SqlQuery($sql);
        $SqlQuery->set($player);
        $userGeography = $BonoInternoMySqlDAO->querySQL($SqlQuery->getQuery())[0];
        $countryUser = $userGeography["d.pais_id"];
        $departmentUser = $userGeography["c.depto_id"];
        $cityUser = $userGeography["c.ciudad_id"];

        //Recuperando moneda del usuario
        $UsuarioMandante = new UsuarioMandante("", $player, $BonoInterno->mandante);
        $userCurrency = $UsuarioMandante->moneda;

        /**Asignando detalles a array detalles*/
        $detalles["Depositos"] = 0;
        $detalles["DepositoEfectivo"] = $isCash;
        $detalles["MetodoPago"] = $payMethod;
        $detalles["ValorDeposito"] = $depositValue;
        $detalles["PaisPV"] = $countryPV;
        $detalles["DepartamentoPV"] = $departmentPV;
        $detalles["CiudadPV"] = $cityPV;
        $detalles["PuntoVenta"] = $sellingPoint;
        $detalles["PaisUSER"] = $UsuarioMandante->paisId;
        $detalles["DepartamentoUSER"] = $departmentUser;
        $detalles["CiudadUSER"] = $cityUser;
        $detalles["MonedaUSER"] = $userCurrency;
        $detalles = json_decode(json_encode($detalles));
    } catch (Exception $e) {
        //Realizando acciones necesarias en caso de error lógico
        $failedRequisitions[] = ['UserId' => $player, 'DepositId' => $recargaId, 'Reason' => 'ErrorOnLogic'];
        $Transaction->getConnection()->close();
        continue;
    }

    /**Filtrando asignación por tipo de bono*/
    try {
        $addBonusResponse = "";
        if ($BonoInterno->tipo == 2 || $BonoInterno->tipo == 3) {
            //Asignación para bono depósito y NO depósito
            $addBonusResponse = $BonoInterno->agregarBono($BonoInterno->tipo, $player, $UsuarioMandante->mandante, $detalles, $Transaction);
        } elseif ($BonoInterno->tipo == 5 || $BonoInterno->tipo == 6) {
            //Asignación para FreeCasino o FreeBet
            $addBonusResponse = $BonoInterno->agregarBonoFree($bonoId, $player, $UsuarioMandante->mandante, $detalles, false, "", $Transaction);
        }

        /** Guardando cambios */
        $Transaction->commit();

        if (!$addBonusResponse->WinBonus) $failedRequisitions[] = ['UserId' => $player, 'DepositId' => $recargaId, 'Reason' => 'MissedBonusRedemption'];
    } catch (Exception $e) {
        $failedRequisitions[] = ['UserId' => $player, 'DepositId' => $recargaId, 'Reason' => 'ErrorOnBonusRedemption'];
    } finally {
        $Transaction->getConnection()->close();
    }
}

try {
    /** Dejando LOG en auditoría general */
    $ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
    $ip = explode(",", $ip)[0];

    $AuditoriaGeneral = new AuditoriaGeneral();

    $AuditoriaGeneral->setUsuarioId($_SESSION["usuario"]);
    $AuditoriaGeneral->setUsuarioIp($ip);
    $AuditoriaGeneral->setUsuariosolicitaId($_SESSION["usuario"]);
    $AuditoriaGeneral->setUsuariosolicitaIp($ip);
    $AuditoriaGeneral->setTipo("ADICION_MASIVA_BONO");
    $AuditoriaGeneral->setValorAntes("");
    $AuditoriaGeneral->setValorDespues("A");
    $AuditoriaGeneral->setUsucreaId($_SESSION["usuario"]);
    $AuditoriaGeneral->setUsumodifId(0);
    $AuditoriaGeneral->setEstado("A");
    $AuditoriaGeneral->setDispositivo(0);
    $AuditoriaGeneral->setObservacion((string)$BonoInterno->bonoId);

    $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO();
    $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);
    $AuditoriaGeneralMySqlDAO->getTransaction()->commit();
} catch (Exception $e) {

}

/** Calculando peticiones exitosas */
$successfulRequisitions = $totalRequisitions - count($failedRequisitions);

if (count($failedRequisitions) < 1) {
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "Ejecución exitosa {$successfulRequisitions}/{$totalRequisitions} solicitudes exitosas";
    $response["ModelErrors"] = [
        "FailedRequisitions" => $failedRequisitions,
    ];
} else {
    $response["HasError"] = true;
    $response["AlertType"] = "danger";
    $response["AlertMessage"] = "Ejecución parcial {$successfulRequisitions}/{$totalRequisitions} solicitudes exitosas";
    $response["ModelErrors"] = [
        "FailedRequisitions" => $failedRequisitions,
    ];
}
?>