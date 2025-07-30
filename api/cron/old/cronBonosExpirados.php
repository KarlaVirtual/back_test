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

$filename=__DIR__.'/lastrunBonosExpirados';
$argv1 = $argv[1];
$datefilename=date("Y-m-d H:i:s", filemtime($filename));

if($datefilename<=date("Y-m-d H:i:s", strtotime('-0.5 days'))) {
    unlink($filename);
}

if(file_exists($filename) ) {
    throw new Exception("There is a process currently running", "1");
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


