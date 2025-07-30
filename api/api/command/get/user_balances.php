<?php

use \CurlWrapper;
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Registro;
use Backend\dto\Subproveedor;
use Backend\sql\ConnectionProperty;
use Backend\dto\SubproveedorMandantePais;

/**
 * Este script obtiene los saldos de un usuario, incluyendo depósitos, ganancias, bonos y saldos especiales.
 *
 * @param object $json Objeto JSON que contiene:
 * @param string $params->type Tipo de saldo a consultar (por ejemplo, 'bonuses').
 * @param object $session Objeto que contiene información de la sesión del usuario.
 *
 * @return array $response Respuesta en formato JSON que incluye:
 * - data: Objeto con los siguientes campos:
 *   - balance: Saldo total del usuario.
 *   - balanceDeposit: Saldo de depósitos.
 *   - balanceWinning: Saldo de ganancias.
 *   - balanceBonus: Saldo pendiente por rollover.
 *   - balanceFreebet: Saldo de apuestas gratuitas.
 *   - balanceFreecasino: Saldo de casino gratuito.
 *
 * @throws Exception Si ocurre un error durante la conexión a la base de datos o la consulta de datos.
 */

/* asigna parámetros JSON y define variables para usuario y saldo. */
$params = $json->params;

$UsuarioMandante = $UsuarioMandanteSite;
$saldoFreecasino = 0;

if ($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null) {

    /* Conecta a la base de datos usando la configuración almacenada en el entorno. */
    $connOriginal = $_ENV["connectionGlobal"]->getConnection();

    try {
        /* Conexión a la base de datos en producción utilizando PDO y SSL. */
        $connDB5 = null;

        if ($_ENV['ENV_TYPE'] == 'prod') {

            $connDB5 = new \PDO(
                "mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(),
                ConnectionProperty::getUser(),
                ConnectionProperty::getPassword(),
                array(
                    PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-bundle.crt',
                    PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
                )
            );
        } else {
            /* Conecta a una base de datos MySQL usando PDO y variables de entorno. */

            $connDB5 = new \PDO(
                "mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(),
                ConnectionProperty::getUser(),
                ConnectionProperty::getPassword()
            );
        }

        /* Configura la conexión a la base de datos con codificación UTF-8 y zona horaria. */
        $connDB5->exec("set names utf8");

        if ($_ENV["TIMEZONE"] != null && $_ENV["TIMEZONE"] != '') {
            $connDB5->exec('SET time_zone = "' . $_ENV["TIMEZONE"] . '";');
        }

        /* Configura el nivel de aislamiento y tiempo de espera de bloqueo en una conexión a base de datos. */
        if ($_ENV["NEEDINSOLATIONLEVEL"] != null && $_ENV["NEEDINSOLATIONLEVEL"] == '1') {
            $connDB5->exec('SET TRANSACTION ISOLATION LEVEL READ COMMITTED;');
        }

        if ($_ENV["ENABLEDSETLOCKWAITTIMEOUT"] != null && $_ENV["ENABLEDSETLOCKWAITTIMEOUT"] == '1') {
            // $connDB5->exec('SET SESSION innodb_lock_wait_timeout = 5;');
        }

        /* configura tiempo de ejecución y codificación UTF-8 en la base de datos. */
        if ($_ENV["ENABLEDSETMAX_EXECUTION_TIME"] != null && $_ENV["ENABLEDSETMAX_EXECUTION_TIME"] == '1') {
            // $connDB5->exec('SET SESSION MAX_EXECUTION_TIME = 120000;');
        }

        if ($_ENV["DBNEEDUTF8"] != null && $_ENV["DBNEEDUTF8"] == '1') {
            $connDB5->exec("SET NAMES utf8mb4");
        }

        /* Establece una conexión a la base de datos utilizando una variable de entorno. */
        $_ENV["connectionGlobal"]->setConnection($connDB5);
    } catch (\Exception $e) {
        /* captura excepciones en PHP, evitando interrupciones en la ejecución. */
    }
}

/* Se crean objetos de Usuario y Registro utilizando datos de UsuarioMandante. */
$Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
$Registro = new Registro("", $UsuarioMandante->getUsuarioMandante());

if ($Usuario->mandante == '14' || $Usuario->mandante == '17' || $Usuario->mandante == '13' || ($Usuario->mandante == '0' && $Usuario->paisId == '46') || ($Usuario->mandante == '0' && $Usuario->paisId == '60') || ($Usuario->mandante == '0' && $Usuario->paisId == '94') || ($Usuario->mandante == '0' && $Usuario->paisId == '173')) {

    /* Inicializa la variable $saldoBonos con un valor inicial de cero. */
    $saldoBonos = 0;

    try {
        if ($params->type == 'bonuses') {

            $Subproveedor = new Subproveedor("", "ITN");
            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Subproveedor->subproveedorId, $Usuario->mandante, $Usuario->paisId);
            $Credentials = json_decode($SubproveedorMandantePais->getCredentials());
            $urlAltenar = $Credentials->URL2;
            $walletCode = $Credentials->WALLET_CODE;

            /* Se asigna un código de billetera según condiciones del mandante y país del usuario. */
            $Mandante = new Mandante($Usuario->mandante);

            if ($Mandante->mandante == '0' && $Usuario->paisId == 60) {
                $walletCode = "160124";
            }

            if ($Mandante->mandante == '0' && $Usuario->paisId == 2) {
                $walletCode = "160124";
            }

            /* Modifica el ID de usuario bajo ciertas condiciones y crea un array de datos. */
            $IdUsuarioAltenar = $Usuario->usuarioId;
            if ((intval($Usuario->usuarioId) > 73758) || (in_array(intval($Usuario->usuarioId), array(70363, 72098, 67393, 73497, 57196)))) {
                $IdUsuarioAltenar = $Usuario->usuarioId . "U";
            }

            $dataD = array(
                "ExtUserId" => $IdUsuarioAltenar,
                "WalletCode" => $walletCode
            );

            /* Código para enviar una solicitud POST en JSON usando cURL en PHP. */
            $dataD = json_encode($dataD);

            // Inicializar la clase CurlWrapper
            $curl = new CurlWrapper($urlAltenar . '/api/Bonus/GetClientBonusInfo/json');

            // Configurar opciones
            $curl->setOptionsArray(array(
                CURLOPT_URL => $urlAltenar . '/api/Bonus/GetClientBonusInfo/json',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 5,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $dataD,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));

            /* Se ejecuta una solicitud CURL, cierra la conexión y decodifica la respuesta JSON. */
            $response2 = $curl->execute();
            $response2 = json_decode($response2);

            /* Verifica si hay información de bono y calcula el monto pendiente. */
            if ($response2->GetClientBonusInfoMessageResult != null) {
                $bonoPendientePorRollover = floatval($response2->GetClientBonusInfoMessageResult->Amount) / 100;
            }
        }
    } catch (Exception $e) {
        /* captura excepciones en PHP sin realizar ninguna acción adicional. */
    }
}

/* Código obtiene y almacena distintos saldos de un usuario y fija un límite de filas. */
$saldo = $Usuario->getBalance();
$saldoRecargas = $Registro->getCreditosBase();
$saldoRetiros = $Registro->getCreditos();
$saldoBonos = $Registro->getCreditosBono();

/* Se inicializan variables para un proceso de ordenamiento y gestión de filas. */
$MaxRows = 1;
$OrderedItem = 1;
$SkeepRows = 0;
$rules = [];

if (true) {
    $apmin2Sql = "SELECT
                        usuario.moneda,
                     bono_interno.nombre,
                     usuario_bono.valor,
                     usuario_bono.apostado,
                     usuario_bono.rollower_requerido,
                     usuario_bono.usuario_id,
                      CASE bd.tipo WHEN 'EXPDIA' THEN  DATE_FORMAT((DATE_ADD(DATE_FORMAT(usuario_bono.fecha_crea,'%Y-%m-%d %H:%i:%s'), INTERVAL bd.valor DAY)),'%Y-%m-%d %H:%i:%s') ELSE DATE_FORMAT(usuario_bono.fecha_crea,'%Y-%m-%d %H:%i:%s') END AS fecha_expiracion,
                    SUM(bd2.valor) valor

                   FROM usuario_bono
                     INNER JOIN usuario ON (usuario.usuario_id = usuario_bono.usuario_id)
                     INNER JOIN bono_interno ON (bono_interno.bono_id = usuario_bono.bono_id)                      
                      INNER JOIN bono_detalle bd ON (bono_interno.bono_id = bd.bono_id AND (bd.tipo='EXPDIA' OR bd.tipo='EXPFECHA' ) )
                      INNER JOIN bono_detalle bd2 ON (bono_interno.bono_id = bd2.bono_id AND (bd2.tipo='MINAMOUNT' ) )

                     
                   WHERE usuario_bono.estado = 'A' AND bono_interno.fecha_inicio<= now()  AND bono_interno.tipo =6   AND ((bd.tipo = 'EXPDIA' AND DATE_FORMAT(
                                                 (DATE_ADD(DATE_FORMAT(usuario_bono.fecha_crea, '%Y-%m-%d %H:%i:%s'),
                                                           INTERVAL bd.valor DAY)),
                                                 '%Y-%m-%d %H:%i:%s') >= now()) OR
       (bd.tipo = 'EXPFECHA' AND bd.valor >= now()))
                   AND usuario_bono.usuario_id=" . $Usuario->usuarioId;

    /* calcula el saldo de freebet basado en apuestas y valores de usuario. */
    $BonoInterno = new \Backend\dto\BonoInterno();
    $apmin2_RS = $BonoInterno->execQuery("", $apmin2Sql);


    foreach ($apmin2_RS as $key => $value) {

        if ($value->{'usuario_bono.apostado'} != '' && $value->{'usuario_bono.apostado'} != '0') {

            $saldoFreebet = $saldoFreebet + floatval(
                $value->{'usuario_bono.apostado'}
            );
        } else {

            $saldoFreebet = $saldoFreebet + floatval(
                $value->{'.valor'}
            );
        }
    }


    $apmin2Sql = "SELECT
                        usuario.moneda,
                     bono_interno.nombre,
                     usuario_bono.valor,
                     usuario_bono.apostado,
                     usuario_bono.rollower_requerido,
                     usuario_bono.usuario_id,
                      CASE bd.tipo WHEN 'EXPDIA' THEN  DATE_FORMAT((DATE_ADD(DATE_FORMAT(usuario_bono.fecha_crea,'%Y-%m-%d %H:%i:%s'), INTERVAL bd.valor DAY)),'%Y-%m-%d %H:%i:%s') ELSE DATE_FORMAT(usuario_bono.fecha_crea,'%Y-%m-%d %H:%i:%s') END AS fecha_expiracion

                   FROM usuario_bono
                     INNER JOIN usuario ON (usuario.usuario_id = usuario_bono.usuario_id)
                     INNER JOIN bono_interno ON (bono_interno.bono_id = usuario_bono.bono_id)                      
                      INNER JOIN bono_detalle bd ON (bono_interno.bono_id = bd.bono_id AND (bd.tipo='EXPDIA' OR bd.tipo='EXPFECHA' ) )

                     
                   WHERE usuario_bono.estado = 'A' AND usuario_bono.rollower_requerido >0   AND ((bd.tipo = 'EXPDIA' AND DATE_FORMAT(
                                                 (DATE_ADD(DATE_FORMAT(usuario_bono.fecha_crea, '%Y-%m-%d %H:%i:%s'),
                                                           INTERVAL bd.valor DAY)),
                                                 '%Y-%m-%d %H:%i:%s') >= now()) OR
       (bd.tipo = 'EXPFECHA' AND bd.valor >= now()))
                   AND usuario_bono.usuario_id=" . $Usuario->usuarioId;

    /* Suma valores de bonos internos obtenidos de una consulta a la base de datos. */
    $BonoInterno = new \Backend\dto\BonoInterno();
    $apmin2_RS = $BonoInterno->execQuery("", $apmin2Sql);


    foreach ($apmin2_RS as $key => $value) {

        $bonoPendientePorRollover = $bonoPendientePorRollover + floatval(
            $value->{'usuario_bono.valor'}
        );
    }

    //Saldo FreeCasino

    /* Consulta SQL que obtiene información de bonos internos activos para un usuario específico. */
    $apmin2Sql = "SELECT  U.moneda, BI.bono_id, BI.nombre, (UB.valor_base - UB.valor) AS valor_base, UB.fecha_expiracion, BD.fecha_crea, BD.tipo, BD.valor
                    FROM bono_interno as BI
                    INNER JOIN usuario_bono AS UB ON BI.bono_id = UB.bono_id
                    INNER JOIN usuario as U ON U.usuario_id = UB.usuario_id
                    INNER JOIN bono_detalle as BD ON (BI.bono_id = BD.bono_id AND (BD.tipo = 'EXPDIA' OR BD.tipo = 'EXPFECHA'))
                    WHERE CASE WHEN BD.tipo = 'EXPDIA' THEN NOW() BETWEEN BI.fecha_inicio AND DATE_ADD(BD.fecha_crea, INTERVAL BD.valor DAY)
                    WHEN BD.tipo = 'EXPFECHA' THEN NOW() BETWEEN BI.fecha_inicio AND BD.valor
                    ELSE 1=1 END
                    AND(BI.tipo = 5)
                    AND (UB.fecha_expiracion > NOW())
                    AND (UB.estado = 'A')
                    AND U.usuario_id = " . $Usuario->usuarioId;


    /* Se consulta y suma valores base de registros en una base de datos. */
    $BonoInterno = new \Backend\dto\BonoInterno();
    $apmin2_RS = $BonoInterno->execQuery("", $apmin2Sql);

    foreach ($apmin2_RS as $key => $value) {
        $saldoFreecasino += floatval($value->{'.valor_base'});
    }
}


/* Código PHP que crea un arreglo con valores relacionados al saldo y bonificaciones. */
$data = array();

/* asigna saldos a un arreglo y verifica la conexión global. */
$data["balance"] = $saldo;
$data["balanceDeposit"] = floatval($saldoRecargas);
$data["balanceWinning"] = $saldoRetiros;
$data["balanceBonus"] = $bonoPendientePorRollover;
$data["balanceFreebet"] = $saldoFreebet;
$data["balanceFreecasino"] = $saldoFreecasino;

$response["data"] = $data;

if ($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null) {
    $connDB5 = null;

    $_ENV["connectionGlobal"]->setConnection($connOriginal);
}
