<?php
/**
 * Resúmen cronométrico
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 * @date 18.10.17
 *
 */

use Backend\cron\ResumenesCron;
use Backend\dto\BonoInterno;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\TransaccionApi;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\UsuarioAutomation;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioMandante;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransjuegoLogMySqlDAO;
use Backend\mysql\UsuarioAutomationMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\sql\Transaction;


require(__DIR__.'/../vendor/autoload.php');
///home/devadmin/api/api/

ini_set('memory_limit', '-1');
$_ENV["NEEDINSOLATIONLEVEL"] ='1';

/* asigna valores a variables según la conexión y parámetros existentes. */
$_ENV["enabledConnectionGlobal"] = 1;

$filename=__DIR__.'/lastrunBonosExpirados';
$argv1 = $argv[1];
$datefilename=date("Y-m-d H:i:s", filemtime($filename));

if($datefilename<=date("Y-m-d H:i:s", strtotime('-0.5 days'))) {
    unlink($filename);
}

if(file_exists($filename) ) {
    //throw new Exception("There is a process currently running", "1");
    exit();
}
file_put_contents($filename, 'RUN');


$message = "*CRON: (cronBonosExpirados) * " . " - Fecha: " . date("Y-m-d H:i:s");
$ConfigurationEnvironment = new ConfigurationEnvironment();

if (!$ConfigurationEnvironment->isDevelopment()) {
    exec("php -f ".__DIR__."../src/imports/Slack/message.php '" . $message . "' '#virtualsoft-cron' > /dev/null & ");

}
/*
$BonoInterno = new BonoInterno();
$BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
$transaccion = $BonoDetalleMySqlDAO->getTransaction();
$BonoInterno->execQuery($transaccion, "UPDATE usuario_bono SET estado='E' WHERE  estado='A' and fecha_expiracion <= now()");


$transaccion->commit();*/


$BonoInterno = new BonoInterno();
$BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
$transaccion = $BonoDetalleMySqlDAO->getTransaction();

$sqlInsert = "

SELECT usuario_bono.usubono_id,
       usuario_bono.estado

FROM usuario_bono

WHERE usuario_bono.estado = 'A'
  AND usuario_bono.fecha_expiracion <= now()
  
limit 10000
";


$datosBonosAExpirar = $BonoInterno->execQuery($transaccion, $sqlInsert);


foreach ($datosBonosAExpirar as $datanum) {
    $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
    $transaccion = $BonoDetalleMySqlDAO->getTransaction();
    $BonoInterno->execQuery($transaccion, "UPDATE usuario_bono SET estado='E' WHERE usubono_id='".$datanum->{'usuario_bono.usubono_id'}."'; ");
    $transaccion->commit();

}

$BonoInterno = new BonoInterno();
$BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
$transaccion = $BonoDetalleMySqlDAO->getTransaction();

$sqlInsert = "

SELECT usuario_bono.usubono_id,usuario_bono.estado,(CASE
           WHEN bono_detalle.tipo = 'EXPDIA'
               THEN DATE_ADD(usuario_bono.fecha_crea, INTERVAL bono_detalle.valor DAY)
           ELSE  bono_detalle.valor END
    ) fecha_expiracion
FROM usuario_bono
         INNER JOIN bono_detalle ON bono_detalle.bono_id = usuario_bono.bono_id

WHERE
    (usuario_bono.bono_id = bono_detalle.bono_id AND
       (bono_detalle.tipo = 'EXPDIA' OR
        bono_detalle.tipo = 'EXPFECHA'))
  AND usuario_bono.estado = 'A'
  AND usuario_bono.fecha_expiracion IS NULL AND (id_externo = '0' OR id_externo = '' OR id_externo IS NULL)
  AND (CASE
           WHEN bono_detalle.tipo = 'EXPDIA'
               THEN now() > DATE_ADD(usuario_bono.fecha_crea, INTERVAL bono_detalle.valor DAY)
           ELSE now() > bono_detalle.valor END
    )
limit 10000
";


$datosBonosAExpirar = $BonoInterno->execQuery($transaccion, $sqlInsert);


foreach ($datosBonosAExpirar as $datanum) {
    $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
    $transaccion = $BonoDetalleMySqlDAO->getTransaction();
    $BonoInterno->execQuery($transaccion, "UPDATE usuario_bono SET estado='E',fecha_expiracion='".$datanum->{'.fecha_expiracion'}."' WHERE usubono_id='".$datanum->{'usuario_bono.usubono_id'}."'; ");
    $transaccion->commit();

}

// Actualizacion de bonos que la fecha de expiracion ya paso


/**
 * Propósito:
 * Este script selecciona los bonos que están activos y cuyo tipo de expiración es por fecha específica.
 * Luego, actualiza el estado de los bonos a 'E' (expirado) si la fecha de expiración ya ha pasado.
 *
 * Descripción de variables:
 *    - $sql: Consulta SQL que selecciona los bonos cuyo tipo de expiración es 'EXPIRATIONDATESOFOUTSTANDINGBONDS' y cuya fecha de expiración ya ha pasado.
 *            Filtra los bonos que están activos ('A') y con estado 'P' (pendiente).
 *    - $BonoInterno: Objeto de la clase BonoInterno utilizado para ejecutar las consultas a la base de datos.
 *    - $BonoDetalleMySqlDAO: Objeto de la clase BonoDetalleMySqlDAO que maneja las transacciones en la base de datos.
 *    - $transaccion: Objeto de transacción usado para ejecutar las consultas de manera segura.
 *    - $BonosAExpirar: Arreglo que contiene los bonos a expirar, obtenidos de la consulta ejecutada a la base de datos.
 *    - $usubonoId: Arreglo que almacena los identificadores de los bonos que deben actualizar su estado.
 *    - $sqlUpdate: Consulta SQL que se genera para actualizar el estado del bono a 'E' (expirado) en la base de datos.
 *    - $ActualizacionUsuarioConfiguracion: Resultado de la ejecución de la consulta SQL de actualización.
 */



// Consulta SQL para obtener los bonos cuyo tipo de expiración es por fecha y cuya fecha límite ya ha pasado


$sql = "SELECT
	ub.usubono_id,
	ub.usuario_id,
	bono_interno.tipo,
	bono_interno.nombre,
	bono_interno.mandante,
	bono_interno.fecha_crea,
	bono_interno.usucrea_id,
	bono_interno.bono_id,
	bono_detalle.valor,
	bono_detalle.tipo,
	bono_interno.estado,
	ub.estado
FROM
	usuario_bono ub
INNER JOIN bono_interno ON
	(ub.bono_id = bono_interno.bono_id)
inner join bono_detalle ON
	(bono_interno.bono_id = bono_detalle.bono_id)
where
	ub.estado = 'P'
	and ub.usuario_id != ''
	and bono_detalle.tipo in ('EXPIRATIONDATESOFOUTSTANDINGBONDS')
	and bono_detalle.valor < NOW()
	and bono_interno.tipo = 2;
	";

if ($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null) {

    /* Conecta a una base de datos MySQL usando PDO, solo en entornos de producción. */
    $connOriginal = $_ENV["connectionGlobal"]->getConnection();
    $connDB5 = null;
    if ($_ENV['ENV_TYPE'] == 'prod') {
        $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()
            , array(
                PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-bundle.crt',
                PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
            )
        );
    } else {
        /* Conecta a una base de datos usando parámetros de entorno y propiedades de conexión. */

        $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()

        );
    }

    /* configura la conexión a la base de datos según variables de entorno. */
    $connDB5->exec("set names utf8");

    try {

        if ($_ENV["TIMEZONE"] != null && $_ENV["TIMEZONE"] != '') {
            $connDB5->exec('SET time_zone = "' . $_ENV["TIMEZONE"] . '";');
        }

        if ($_ENV["NEEDINSOLATIONLEVEL"] != null && $_ENV["NEEDINSOLATIONLEVEL"] == '1') {
            $connDB5->exec('SET TRANSACTION ISOLATION LEVEL READ COMMITTED;');
        }
        if ($_ENV["ENABLEDSETLOCKWAITTIMEOUT"] != null && $_ENV["ENABLEDSETLOCKWAITTIMEOUT"] == '1') {
            // $connDB5->exec('SET SESSION innodb_lock_wait_timeout = 5;');
        }
        if ($_ENV["ENABLEDSETMAX_EXECUTION_TIME"] != null && $_ENV["ENABLEDSETMAX_EXECUTION_TIME"] == '1') {
            // $connDB5->exec('SET SESSION MAX_EXECUTION_TIME = 120000;');
        }
        if ($_ENV["DBNEEDUTF8"] != null && $_ENV["DBNEEDUTF8"] == '1') {
            $connDB5->exec("SET NAMES utf8mb4");
        }
    } catch (\Exception $e) {
        /* Captura excepciones en PHP sin realizar ninguna acción dentro del bloque. */

    }

    /* Establece una conexión a la base de datos usando una instancia de conexión global. */
    $_ENV["connectionGlobal"]->setConnection($connDB5);
}

// Inicialización de objetos y transacción
$BonoInterno = new BonoInterno();
$BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
$transaccion = $BonoDetalleMySqlDAO->getTransaction();
// Ejecutar la consulta SQL y obtener los bonos a expirar

$BonosAExpirar = $BonoInterno->execQuery('', $sql);
// Arreglo para almacenar los IDs de los bonos a actualizar

if ($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null) {
    $connDB5 = null;
    $_ENV["connectionGlobal"]->setConnection($connOriginal);
}

// Inicialización de objetos y transacción
$BonoInterno = new BonoInterno();
$BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
$transaccion = $BonoDetalleMySqlDAO->getTransaction();
// Ejecutar la consulta SQL y obtener los bonos a expirar


$usubonoId = [];

// Recorrer los bonos obtenidos y almacenar los IDs de los bonos a expirar

foreach ($BonosAExpirar as $value) {
    $usubonoId[] = $value->{'ub.usubono_id'};
}

// Si hay bonos a actualizar, realizar la actualización


if (!empty($usubonoId)) {
    foreach ($usubonoId as $id) {
        // Consulta SQL para actualizar el estado del bono a 'E' (expirado)

        $sqlUpdate = "UPDATE usuario_bono SET estado = 'E' WHERE usubono_id = $id;";
        $BonoInterno = new BonoInterno();
        $ActualizacionUsuarioConfiguracion = $BonoInterno->execQuery($transaccion,$sqlUpdate);
    }
}


/**
 * Propósito:
 * Este script obtiene los bonos que están activos y que tienen un tiempo de expiración definido en días.
 * Calcula la fecha de expiración sumando los días a la fecha de creación del bono y actualiza el estado del bono a 'E' (expirado)
 * si la fecha límite ya ha pasado.
 *
 * Descripción de variables:
 *    - $sql1: Consulta SQL que selecciona los bonos activos con un tipo de expiración por días, cuyo estado sea 'P' (pendiente),
 *      y que no hayan sido reclamados todavía. La consulta también filtra por bonos con el tipo 'EXPIRATIONDAYSOFOUTSTANDINGBONDS'.
 *    - $BonoInterno: Objeto de la clase BonoInterno que se usa para ejecutar consultas a la base de datos.
 *    - $BonoDetalleMySqlDAO: Objeto de la clase BonoDetalleMySqlDAO que maneja las transacciones en la base de datos.
 *    - $transaccion: Objeto de transacción usado para ejecutar las consultas de manera segura.
 *    - $BonosDiasAExpirar: Arreglo que contiene los bonos obtenidos de la base de datos que deben ser procesados.
 *    - $usubonoId: ID único del bono.
 *    - $fechaCrea: Fecha de creación del bono.
 *    - $diasASumar: Número de días que se deben sumar a la fecha de creación para calcular la fecha de expiración.
 *    - $fechaFinal: Fecha calculada después de sumar los días a la fecha de creación del bono.
 *    - $sqlUpdate: Consulta SQL que se genera para actualizar el estado del bono a 'E' (expirado) en la base de datos.
 */



//EXPIRACION POR DIAS



// Consulta SQL para obtener los bonos que deben ser procesados

$sql1 = "SELECT
	ub.usubono_id,
	ub.usuario_id,
	ub.fecha_crea,
	bono_interno.tipo,
	bono_interno.nombre,
	bono_interno.mandante,
	bono_interno.fecha_crea,
	bono_interno.usucrea_id,
	bono_interno.bono_id,
	bono_detalle.valor,
	bono_detalle.tipo,
	bono_interno.estado,
	ub.estado
FROM
	usuario_bono ub
INNER JOIN bono_interno ON
(ub.bono_id = bono_interno.bono_id)
inner join bono_detalle ON
(bono_interno.bono_id = bono_detalle.bono_id)
where
	ub.estado = 'P'
    and ub.usuario_id != ''
    and bono_detalle.tipo in ('EXPIRATIONDAYSOFOUTSTANDINGBONDS')
    and bono_interno.tipo = 2";



// Inicialización de objetos y transacción

$BonoInterno = new BonoInterno();
$BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
$transaccion = $BonoDetalleMySqlDAO->getTransaction();

// Ejecutar la consulta SQL y obtener los bonos a procesar

$BonosDiasAExpirar = $BonoInterno->execQuery($transaccion, $sql1);

// Arreglo para almacenar los resultados procesados

$resultados = [];


$BonosDiasAExpirar = $BonoInterno->execQuery($transaccion, $sql1);


// Recorrer el arreglo y procesar los datos
foreach ($BonosDiasAExpirar as $item) {
    // Obtener el ID del bono
    $usubonoId = $item->{'ub.usubono_id'};
    // Obtener la fecha de creación del bono

    $fechaCrea = $item->{'ub.fecha_crea'};
    // Obtener la cantidad de días a sumar

    $diasASumar = $item->{'bono_detalle.valor'};

    // Sumar los días a la fecha usando strtotime
    $fechaFinal = date('Y-m-d H:i:s', strtotime("+{$diasASumar} days", strtotime($fechaCrea)));

    // Validar si la fecha límite ya pasó
    if (strtotime($fechaFinal) < time()) {
        // Generar y ejecutar la consulta SQL para cambiar el estado
        $sqlUpdate = "UPDATE usuario_bono SET estado = 'E' WHERE usubono_id = $usubonoId;";
        $BonoInterno->execQuery($transaccion, $sqlUpdate);
    }
}



/** Inactivación de jackpots */
$BonoInterno = new BonoInterno();
$BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
$transaccion = $BonoDetalleMySqlDAO->getTransaction();

$sql = "UPDATE jackpot_interno SET estado = 'I' WHERE jackpot_interno.estado = 'A' AND jackpot_interno.fecha_fin is not null AND jackpot_interno.fecha_fin < now()";
$BonoInterno->execQuery($transaccion, $sql);
$transaccion->commit();

/** Definición de nueva caída para jackpots en día de finalización */
if (true) {
    $BonoInterno = new BonoInterno();

    $sqlNearToExpireJackpots = "SELECT jackpot_interno.jackpot_id, jackpot_detalle.jackpotdetalle_id
    FROM jackpot_interno
    LEFT JOIN jackpot_detalle ON (jackpot_interno.jackpot_id = jackpot_detalle.jackpot_id AND
    jackpot_detalle.tipo = 'FALLCRITERIA_LASTDAYWINNERBET')
    WHERE jackpot_interno.estado = 'A'
    AND jackpot_interno.reinicio = 0
    AND jackpot_interno.fecha_fin IS NOT NULL
    AND DATE_FORMAT(jackpot_interno.fecha_fin, '%Y-%m-%d') = DATE_FORMAT(NOW(), '%Y-%m-%d')
    AND jackpot_detalle.jackpotdetalle_id IS NULL";

    $nearToExpireJackpots = $BonoInterno->execQuery('', $sqlNearToExpireJackpots);

    foreach ($nearToExpireJackpots as $jackpot) {
        $Transaction = new Transaction();

        $sqlNewJackpotFalling = "insert into jackpot_detalle (jackpot_id, tipo, moneda, valor, usucrea_id, usumodif_id)
        SELECT ji.jackpot_id,
        'FALLCRITERIA_LASTDAYWINNERBET',
        jdMin.moneda,
        CASE
        WHEN jdMin.valor > ji.cantidad_apuesta THEN floor((rand() * (jdMax.valor - jdMin.valor)) + jdMin.valor)
        ELSE floor((rand() * (jdMax.valor - (ji.cantidad_apuesta + 1))) + (ji.cantidad_apuesta + 1)) END as newWinnerBet,
        0,
        0
        FROM jackpot_interno ji
        INNER JOIN jackpot_detalle jdMin
        on (jdMin.jackpot_id = ji.jackpot_id AND jdMin.tipo = 'FALLCRITERIA_LASTDAYMINBETQUANTITY')
        INNER JOIN jackpot_detalle jdMax
        ON (jdMax.jackpot_id = ji.jackpot_id AND jdMax.tipo = 'FALLCRITERIA_LASTDAYMAXBETQUANTITY')
        WHERE ji.jackpot_id = {$jackpot->{'jackpot_interno.jackpot_id'}}
        AND ji.estado = 'A'
        AND jdMax.valor > ji.cantidad_apuesta";

        try {
            $BonoInterno->execQuery($Transaction, $sqlNewJackpotFalling);
        } catch (Exception $e) {
            $Transaction->getConnection()->close();
            continue;
        }

        $Transaction->commit();
    }
}


print_r('PROCCESS OK');
unlink( $filename );


