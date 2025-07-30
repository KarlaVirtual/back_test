<?php

/* Configura la visualización de errores y carga dependencias de Composer en PHP. */
ini_set('display_errors', 'OFF');
error_reporting(E_ERROR);


require_once(__DIR__ . '/../../vendor/autoload.php');

use Backend\dto\BonoDetalle;
use Backend\dto\BonoInterno;
use Backend\dto\Proveedor;
use Backend\dto\Subproveedor;
use Backend\dto\Usuario;
use Backend\dto\UsuarioBono;
use Backend\sql\Transaction;
use Backend\integrations\casino\CTGaming;
use Backend\integrations\casino\REDRAKESERVICESBONUS;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\UsuarioBonoMySqlDAO;
use Backend\dto\Clasificador;
use Backend\dto\MandanteDetalle;
use Backend\integrations\crm\Optimove;

/**
 * bonusapi/cases/CreateBonusExec
 *
 * Asignar bonos a los usuarios
 *
 * Este recurso gestiona la asignación de bonos a usuarios específicos, incluyendo la validación de parámetros, la ejecución de transacciones y la notificación al CRM si corresponde.
 * Permite la asignación de bonos tanto estándar como de tipo FreeSpin, dependiendo del tipo de bono especificado.
 *
 * @param int $sleepTime : Tiempo en segundos para suspender la ejecución antes de comenzar el proceso de asignación.
 * @param int $bonoId : ID del bono que se asignará.
 * @param int $tipobono : Tipo de bono a asignar. Puede ser 8 para FreeSpin u otro valor para bonos estándar.
 * @param string $users : Lista de usuarios a los que se asignará el bono, separada por comas. Cada usuario debe estar identificado por su ID seguido del código de cupón (formato: `usuarioId_cupon`).
 * @param int $notifyCrm : Confirmación para notificar el bono a CRM. 0 = No notificar, 1 = Notificar.
 * @param int $currentExecPosition : Indica la posición de la ejecución (por ejemplo, primer o segundo Exec) en el proceso de asignación del bono.
 *
 * @returns object El objeto $response es un array con los siguientes atributos:
 *  - *idBonus* (int): ID del bono asignado.
 *  - *HasError* (bool): Indica si hubo un error en la operación.
 *  - *AlertType* (string): Especifica el tipo de alerta que se mostrará en la vista.
 *  - *AlertMessage* (string): Contiene el mensaje que se mostrará en la vista.
 *  - *ModelErrors* (array): Array vacío si no hay errores o contiene detalles sobre los errores encontrados.
 *  - *Result* (array): Array vacío en caso de éxito.
 *
 *
 * @throws Exception Si ocurre un error durante el proceso de asignación del bono o en la ejecución de transacciones.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


try {

    /** Recepción de parámetros */
    $sleepTime = $argv[1]; //Segundos de espera antes de iniciar la ejecución
    $bonoId = $argv[2]; //Id del bono por asignar
    $tipobono = $argv[3]; //Tipo de bono por asignar
    $users = $argv[4]; //Cadena separada por comas con todos los usuarios que deben recibir el bono
    $notifyCrm = $argv[5]; //Confirmación para notificar bono a CRM (0 = No notificar o 1 = Notificar)
    $currentExecPosition = $argv[6]; //Primer Exec, segundo Exec ETC ...

    //Suspención antes del inicio de la ejecución
    sleep($sleepTime);

    //Validación parámetros
    $unauthorizedParameterContent = [
        'bonoId' => '\D',
        'tipobono' => '\D',
        'users' => '[^,|\w]',
    ];

    foreach ($unauthorizedParameterContent as $parameter => $regexPattern) {
        if (preg_match("#" . $regexPattern . "#", $$parameter) || empty($$parameter)) return 0;
    }

    /** Una vez verificada la seguridad de los parámetros se carga el .env */
    if (empty($_ENV['DB_HOST'])) {
        $dirbase = __DIR__ . "/../..";
        $dotenv = Dotenv\Dotenv::createImmutable($dirbase);
        $dotenv->load();
    }

    /** Consultando información requerida por parte de los usuarios */
    $BonoInterno = new BonoInterno($bonoId);

    //Separando usuarios de sus códigos
    $usersCollection = explode(',', $users);
    $totalUsers = count($usersCollection);
    $usersIds = "";
    $usersCoupons = [];
    $usersIdsStack = [];
    $usersIdsArrayCount = [];

    for ($currentUserIndex = 0; $currentUserIndex < $totalUsers; $currentUserIndex++) {
        //Obteniendo valor del ID del usuarios y su cupón correspondiente
        $userKeyValues = explode('_', $usersCollection[$currentUserIndex]);
        $userIdentificator = $userKeyValues[0];
        $userCoupon = $userKeyValues[1];
        $usersCoupons[$userIdentificator] = $userCoupon;

        //Concatenando ID de los usuarios para consultar la información requerida en base de datos
        $usersIds .= ($usersIds != "" ? "," : "") . "{$userIdentificator}";
        if ($usersIdsArrayCount[$userIdentificator] == null || empty($usersIdsArrayCount[$userIdentificator])) {
            $usersIdsArrayCount[$userIdentificator] = 0;

        }
        $usersIdsArrayCount[$userIdentificator]++;

    }
    $usersIdsStack = explode(',', $usersIds);

    if ($tipobono == 8) goto LogicFreeSpin;

    $bonoMandante = $BonoInterno->mandante;
    $sqlUsersGeography = "select ciudad.ciudad_id, ciudad.depto_id, usuario.pais_id, usuario.moneda, usuario.mandante, usuario.usuario_id
    FROM registro
    INNER JOIN usuario ON (usuario.usuario_id = registro.usuario_id)
    INNER JOIN pais ON (pais.pais_id = usuario.pais_id)
    LEFT OUTER JOIN ciudad ON (ciudad.ciudad_id = registro.ciudad_id)
    LEFT OUTER JOIN departamento ON (ciudad.depto_id = departamento.depto_id)
    WHERE registro.usuario_id in ({$usersIds}) AND usuario.mandante = {$bonoMandante}";

    $usersArray = $BonoInterno->execQuery("", $sqlUsersGeography);
    $infoContainer = [];
    foreach ($usersArray as $user) {

        try {
            /** Inicializando transaccion */
            $Transaction = (isset($Transaction) && $Transaction->getConnection()->isBeginTransaction == 1) ? $Transaction : new Transaction();

            //Obteniendo información requerida para proceso de entrega
            $userId = $user->{'usuario.usuario_id'};
            $codeBonus = $usersCoupons[$userId] ?: "";
            $detalles = (object)[
                "PaisUSER" => $user->{'usuario.pais_id'},
                "DepartamentoUSER" => $user->{'ciudad.depto_id'},
                "CiudadUSER" => $user->{'ciudad.ciudad_id'},
                "MonedaUSER" => $user->{'usuario.moneda'},
                "ValorDeposito" => 0
            ];
            if ($usersIdsArrayCount[$userId] != null) {
                for ($i = 0; $i < $usersIdsArrayCount[$userId]; $i++) {
                    $bonusRedemptionResult = $BonoInterno->agregarBonoFree($bonoId, $userId, $bonoMandante, $detalles, true, $codeBonus, $Transaction);
                    print_r($bonusRedemptionResult);
                }
            } else {
                $bonusRedemptionResult = $BonoInterno->agregarBonoFree($bonoId, $userId, $bonoMandante, $detalles, true, $codeBonus, $Transaction);

            }

            //Validando efectividad de la solicitud
            if ($bonusRedemptionResult->WinBonus == true) {
                $Transaction->commit();
            } else $Transaction->rollback();
        } catch (Exception $e) {
            print_r($e);
            $Transaction->getConnection()->close();
        }
    }

    LogicFreeSpin:
    if ($tipobono == 8) {
        //Solicitando detalles generales del bono
        $rules = [];
        $rules[] = ['field' => 'bono_detalle.bono_id', 'data' => $bonoId, 'op' => 'eq'];
        $filters = ['rules' => $rules, 'groupOp' => 'AND'];
        $select = 'bono_detalle.bono_id,bono_detalle.bonodetalle_id, bono_detalle.tipo, bono_detalle.valor';
        $sidx = 'bono_detalle.bonodetalle_id';
        $BonoDetalle = new BonoDetalle();
        $bonusDetails = $BonoDetalle->getBonoDetallesCustom($select, $sidx, 'ASC', 0, 1000, json_encode($filters), true);
        $bonusDetails = json_decode($bonusDetails)->data;

        //Consultando por subproveedor
        $codeSubprovider = array_filter($bonusDetails, function ($detail) {
            if ($detail->{'bono_detalle.tipo'} == 'CODESUBPROVIDER') return true;
            else return false;
        });
        $codeSubprovider = array_filter($codeSubprovider);
        $codeSubprovider = array_values($codeSubprovider);
        $Subproveedor = new Subproveedor($codeSubprovider[0]->{'bono_detalle.valor'});
        $Proveedor = new Proveedor($Subproveedor->proveedorId);

        //Consultando por el ID de los juegos
        $CasinoProduct = array_filter($bonusDetails, function ($detail) {
            if (str_contains($detail->{'bono_detalle.tipo'}, 'CONDGAME')) return true;
            else return false;
        });
        $CasinoProduct = array_filter($CasinoProduct);
        $CasinoProduct = array_values($CasinoProduct);
        $CasinoProduct = array_map(function ($detail) {
            $idGame = explode('CONDGAME', $detail->{'bono_detalle.tipo'})[1];
            return (object)['Id' => $idGame, 'Percentage' => $detail->{'bono_detalle.valor'}];
        }, $CasinoProduct);

        //Obteniendo prefix
        $prefix = array_filter($bonusDetails, function ($detail) {
            if ($detail->{'bono_detalle.tipo'} == 'PREFIX') return true;
            else return false;
        });
        $prefix = array_filter($prefix);
        $prefix = array_values($prefix);
        $prefix = $prefix[0]->{'bono_detalle.valor'};

        //Obteniendo maxPlayersCount
        if ($currentExecPosition == 1) {
            /** Un único proveedor de FreeSpin utiliza el MaxPlayerCount y lo solicita para crear esa misma cantidad
             *de usuario_bono en la base de datos, por ello se valida que  este valor sólo se entregue en el primer exec de creación del bono
             */
            $MaxplayersCount = array_filter($bonusDetails, function ($detail) {
                if ($detail->{'bono_detalle.tipo'} == 'MAXJUGADORES') return true;
                else return false;
            });
            $MaxplayersCount = array_filter($MaxplayersCount);
            $MaxplayersCount = array_values($MaxplayersCount);
            $MaxplayersCount = $MaxplayersCount[0]->{'bono_detalle.valor'};
        } else $MaxplayersCount = null;

        //Consultando por uno de los usuarios a asignar
        $Usuario = new Usuario($usersIdsStack[0]);

        //inicializando transacción
        $Transaction = new Transaction();

        $responseBonoGlobal = $BonoInterno->bonoGlobal($Proveedor, $bonoId, $CasinoProduct, $Usuario->mandante, $usersIdsStack, $Transaction, 0, true, 0, $BonoInterno->nombre, $prefix, $MaxplayersCount);
        print_r('LogicFreeSpin');
        print_r($responseBonoGlobal);

        $status = $responseBonoGlobal["status"];

        if ($status == 'OK') $Transaction->commit();
        else $Transaction->rollback();
    }

} catch (Exception $e) {
    syslog(LOG_ERR, " ERRORASIGNACIONBONOS : " . $e->getCode() . " - " . $e->getMessage());
}

$response["idBonus"] = $bonoId;
$response["HasError"] = false;
$response["AlertType"] = "";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];
$response["Result"] = array();
