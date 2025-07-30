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
use Backend\sql\ConnectionProperty;


require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/cronSegundosCron.php');

ini_set('memory_limit', '-1');

$_ENV["enabledConnectionGlobal"] = 1;

for ($i = 0; $i < 10; $i++) {
    $hour = date('H');
    if ((intval($hour) >= 19 && intval($hour) <= 24) || (intval($hour) >= 0 && intval($hour) < 7)) {
        exit();
    }

    $BonoInterno = new BonoInterno();

    $sqlProcesoInterno2 = "

SELECT * FROM proceso_interno2 WHERE tipo='CATEGORIZACIONUSUARIO'
";


    $data = $BonoInterno->execQuery('', $sqlProcesoInterno2);
    $data = $data[0];
    $line = $data->{'proceso_interno2.fecha_ultima'};

    if ($line == '') {
        exit();
    }


    $fechaL1 = date('Y-m-d H:i:00', strtotime($line . '+1 minute'));
    $fechaL2 = date('Y-m-d H:i:59', strtotime($line . '+30 minute'));


    if ($fechaL1 >= date('Y-m-d H:i:00', strtotime('-60 minute'))) {
        exit();
    }
    $BonoInterno = new BonoInterno();
    $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
    $transaccion = $BonoDetalleMySqlDAO->getTransaction();

    $sqlProcesoInterno2 = "
UPDATE proceso_interno2 SET fecha_ultima='" . $fechaL2 . "' WHERE  tipo='CATEGORIZACIONUSUARIO';
";


    $data = $BonoInterno->execQuery($transaccion, $sqlProcesoInterno2);
    $transaccion->commit();


    $fechaSoloDia = $fechaL1;
    $fechaSoloDia2 = $fechaL2;

    $BonoInterno = new BonoInterno();
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

        $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()

        );
    }

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

    }

    if (true) {
        print_r('Fecha Inicio: ' . $fechaL1 . ' - Fecha Fin: ' . $fechaL2);


        $BonoInterno = new BonoInterno();

        $sqlProcesoInterno2 = "

SELECT * FROM proceso_interno2 WHERE tipo='CATEGORIZACIONUSUARIO'
";


        $data = $BonoInterno->execQuery('', $sqlProcesoInterno2);


        $_ENV["connectionGlobal"]->setConnection($connDB5);


        $data = $data[0];
        $line = $data->{'proceso_interno2.fecha_ultima'};
        $sqlProcesoInterno2 = "
select cuenta_cobro.cuenta_id
from cuenta_cobro
         inner join usuario on usuario.usuario_id = cuenta_cobro.usuario_id
where usuario.mandante = 8
  and cuenta_cobro.fecha_crea >= '" . $fechaL1 . "'
  and cuenta_cobro.fecha_crea < '" . $fechaL2 . "'
and cuenta_cobro.valor <500
and (cuenta_cobro.estado='A' OR cuenta_cobro.estado='M')
";
        $data2 = $BonoInterno->execQuery('', $sqlProcesoInterno2);

        foreach ($data2 as $datum) {
            $CuentaCobro = new \Backend\dto\CuentaCobro($datum->{'cuenta_cobro.cuenta_id'});
            $Usuario = new \Backend\dto\Usuario($CuentaCobro->usuarioId);


            if (
                $Usuario->mandante == 8
            ) {
                $BonoInterno = new BonoInterno();

                $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();


                $sql = "SELECT * FROM usuario_saldoresumen where usuario_id='" . $Usuario->usuarioId . "'";
                $data = $BonoInterno->execQuery('', $sql);
                $data = $data[0];

                $saldo_recarga = 0;
                $saldo_apuestas = 0;
                $saldo_premios = 0;
                $saldo_notaret_pagadas = 0;
                $saldo_notaret_pend = 0;
                $saldo_ajustes_entrada = 0;
                $saldo_ajustes_salida = 0;
                $saldo_bono = 0;
                $saldo_notaret_creadas = 0;
                $saldo_apuestas_casino = 0;
                $saldo_premios_casino = 0;
                $saldo_notaret_eliminadas = 0;
                $saldo_bono_free_ganado = 0;
                $saldo_bono_casino_free_ganado = 0;
                $saldo_bono_casino_vivo = 0;
                $saldo_bono_casino_vivo_free_ganado = 0;
                $saldo_bono_virtual = 0;
                $saldo_bono_virtual_free_ganado = 0;
                $saldo_apuestas_casino_vivo = 0;

                if ($data != null) {

                    $saldo_recarga = floatval($data->{'usuario_saldoresumen.saldo_recarga'});
                    $saldo_apuestas = floatval($data->{'usuario_saldoresumen.saldo_apuestas'});
                    $saldo_premios = floatval($data->{'usuario_saldoresumen.saldo_premios'});
                    $saldo_notaret_pagadas = floatval($data->{'usuario_saldoresumen.saldo_notaret_pagadas'});
                    $saldo_notaret_pend = floatval($data->{'usuario_saldoresumen.saldo_notaret_pend'});
                    $saldo_ajustes_entrada = floatval($data->{'usuario_saldoresumen.saldo_ajustes_entrada'});
                    $saldo_ajustes_salida = floatval($data->{'usuario_saldoresumen.saldo_ajustes_salida'});
                    $saldo_bono = floatval($data->{'usuario_saldoresumen.saldo_bono'});
                    $saldo_notaret_creadas = floatval($data->{'usuario_saldoresumen.saldo_notaret_creadas'});
                    $saldo_apuestas_casino = floatval($data->{'usuario_saldoresumen.saldo_apuestas_casino'});
                    $saldo_premios_casino = floatval($data->{'usuario_saldoresumen.saldo_premios_casino'});
                    $saldo_notaret_eliminadas = floatval($data->{'usuario_saldoresumen.saldo_notaret_eliminadas'});
                    $saldo_bono_free_ganado = floatval($data->{'usuario_saldoresumen.saldo_bono_free_ganado'});
                    $saldo_bono_casino_free_ganado = floatval($data->{'usuario_saldoresumen.saldo_bono_casino_free_ganado'});
                    $saldo_bono_casino_vivo = floatval($data->{'usuario_saldoresumen.saldo_bono_casino_vivo'});
                    $saldo_bono_casino_vivo_free_ganado = floatval($data->{'usuario_saldoresumen.saldo_bono_casino_vivo_free_ganado'});
                    $saldo_bono_virtual = floatval($data->{'usuario_saldoresumen.saldo_bono_virtual'});
                    $saldo_bono_virtual_free_ganado = floatval($data->{'usuario_saldoresumen.saldo_bono_virtual_free_ganado'});
                    $saldo_apuestas_casino_vivo = floatval($data->{'usuario_saldoresumen.saldo_apuestas_casino_vivo'});
                }

                $sql = "
select usuario_id,sum(vlr_apuesta) as vlr_apuesta,sum(vlr_premio) as vlr_premio
from it_ticket_enc
where fecha_cierre = '" . date('Y-m-d') . "' and usuario_id = '" . $Usuario->usuarioId . "'";
                $data = $BonoInterno->execQuery('', $sql);
                $data = $data[0];
                if ($data != null) {
                    $saldo_apuestas = $saldo_apuestas + floatval($data->{'.vlr_apuesta'});
                    $saldo_premios = $saldo_premios + floatval($data->{'.vlr_premio'});
                }

                $sql = "
        SELECT usuario_mandante.moneda,
       COUNT(transaccion_juego.transjuego_id)                                                             count,
       SUM(CASE WHEN transaccion_juego.tipo != 'FREESPIN' THEN transaccion_juego.valor_ticket ELSE 0 END) apuestas,
       SUM(CASE WHEN transaccion_juego.tipo != 'FREESPIN' THEN transaccion_juego.valor_premio ELSE 0 END) premios,
       SUM(CASE WHEN transaccion_juego.tipo = 'FREESPIN' THEN transaccion_juego.valor_ticket ELSE 0 END)  apuestasBonus,
       SUM(CASE WHEN transaccion_juego.tipo = 'FREESPIN' THEN transaccion_juego.valor_premio ELSE 0 END)  premiosBonus,
       SUM(transaccion_juego.valor_gratis)                                                                apuestasSaldogratis
FROM transaccion_juego
         INNER JOIN usuario_mandante ON (usuario_mandante.usumandante_id = transaccion_juego.usuario_id)
where 1 = 1
  AND ((transaccion_juego.fecha_crea)) >= '" . date('Y-m-d') . " 00:00:00'
  AND ((transaccion_juego.fecha_crea)) < '" . date('Y-m-d') . " 23:59:59'
  AND ((usuario_mandante.usuario_mandante)) = '" . $Usuario->usuarioId . "'
  
  ";
                $data = $BonoInterno->execQuery('', $sql);
                $data = $data[0];
                if ($data != null) {
                    $saldo_apuestas_casino = $saldo_apuestas_casino + floatval($data->{'.apuestas'});
                    $saldo_premios_casino = $saldo_premios_casino + floatval($data->{'.premios'});
                    $saldo_premios_casino = $saldo_premios_casino + floatval($data->{'.premiosBonus'});
                }

                $sql = "
select usuario_id,sum(usuario_recarga.valor) as valor
from usuario_recarga
where fecha_crea LIKE '" . date('Y-m-d') . "%' and usuario_id = '" . $Usuario->usuarioId . "'";
                $data = $BonoInterno->execQuery('', $sql);
                $data = $data[0];
                if ($data != null) {
                    $saldo_recarga = $saldo_recarga + floatval($data->{'.valor'});
                }


                $sql = "
select usuario_id,sum(cuenta_cobro.valor) as valor
from cuenta_cobro
where fecha_pago  LIKE '" . date('Y-m-d') . "%' and usuario_id = '" . $Usuario->usuarioId . "'";
                $data = $BonoInterno->execQuery('', $sql);
                $data = $data[0];
                if ($data != null) {
                    $saldo_notaret_pagadas = $saldo_notaret_pagadas + floatval($data->{'.valor'});
                }

                $esSeguro = true;

                //Usuario no haya retirado menos del 90% de lo depositado
                if ($saldo_notaret_pagadas > ($saldo_recarga * 0.85)) {
                    $esSeguro = false;
                }

                //Usuario tiene que jugar un 1.6 el valor depositado
                if (($saldo_apuestas + $saldo_apuestas_casino + $saldo_apuestas_casino_vivo) < ($saldo_recarga * 1.6)) {
                    $esSeguro = false;
                }

                //Usuario tiene que perder un 30% del valor depositado
                if ((($saldo_apuestas + $saldo_apuestas_casino) - ($saldo_premios + $saldo_premios_casino)) < 300) {
                    $esSeguro = false;
                }

                if ($esSeguro) {
                    exec("php -f " . __DIR__ . "../src/imports/Slack/message.php '" . 'esSeguro-CRON ' . $CuentaCobro->cuentaId . ' ' . $Usuario->usuarioId . "' '#dev' > /dev/null & ");
                    $_ENV["connectionGlobal"]->setConnection($connOriginal);
                    $CuentaCobro = new \Backend\dto\CuentaCobro($datum->{'cuenta_cobro.cuenta_id'});

                    if (($CuentaCobro->getMediopagoId() == "" || $CuentaCobro->getMediopagoId() == "0")) {
                        $CuentaCobro->setEstado('A');
                    } else {
                        $CuentaCobro->setEstado('P');
                    }
                    if ($CuentaCobro->getMediopagoId() != "" && ($CuentaCobro->getVersion() == "2")) {
                        $CuentaCobro->setEstado('A');
                    }
                    if ($CuentaCobro->getMediopagoId() == "2088007" && ($CuentaCobro->getVersion() == "2")) {
                        $CuentaCobro->setEstado('P');
                    }
                    $CuentaCobroMySqlDAO = new \Backend\mysql\CuentaCobroMySqlDAO();
                    $rowsUpdate = $CuentaCobroMySqlDAO->update($CuentaCobro, " AND (estado='A' OR estado='M') ");

                    if ($rowsUpdate == null || $rowsUpdate <= 0) {

                        $CuentaCobroMySqlDAO->getTransaction()->rollback();

                    } else {
                        $CuentaCobroMySqlDAO->getTransaction()->commit();

                    }


                    $_ENV["connectionGlobal"]->setConnection($connDB5);

                } else {

                }


            }
        }


        //DORADOBET ECUADOR

        $_ENV["connectionGlobal"]->setConnection($connDB5);

        $sqlProcesoInterno2 = "
select cuenta_cobro.cuenta_id
from cuenta_cobro
         inner join usuario on usuario.usuario_id = cuenta_cobro.usuario_id
where usuario.mandante = 0 AND usuario.pais_id=66
  and cuenta_cobro.fecha_crea >= '" . $fechaL1 . "'
  and cuenta_cobro.fecha_crea < '" . $fechaL2 . "'
and cuenta_cobro.valor <250
and (cuenta_cobro.estado='A' OR cuenta_cobro.estado='M')
";
        $data2 = $BonoInterno->execQuery('', $sqlProcesoInterno2);

        foreach ($data2 as $datum) {
            $CuentaCobro = new \Backend\dto\CuentaCobro($datum->{'cuenta_cobro.cuenta_id'});
            $Usuario = new \Backend\dto\Usuario($CuentaCobro->usuarioId);
            print_r($CuentaCobro);
            print_r($Usuario);


            if (
                $Usuario->mandante == 0
            ) {
                $BonoInterno = new BonoInterno();

                $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();


                $sql = "SELECT * FROM usuario_saldoresumen where usuario_id='" . $Usuario->usuarioId . "'";
                $data = $BonoInterno->execQuery('', $sql);
                $data = $data[0];

                $saldo_recarga = 0;
                $saldo_apuestas = 0;
                $saldo_premios = 0;
                $saldo_notaret_pagadas = 0;
                $saldo_notaret_pend = 0;
                $saldo_ajustes_entrada = 0;
                $saldo_ajustes_salida = 0;
                $saldo_bono = 0;
                $saldo_notaret_creadas = 0;
                $saldo_apuestas_casino = 0;
                $saldo_premios_casino = 0;
                $saldo_notaret_eliminadas = 0;
                $saldo_bono_free_ganado = 0;
                $saldo_bono_casino_free_ganado = 0;
                $saldo_bono_casino_vivo = 0;
                $saldo_bono_casino_vivo_free_ganado = 0;
                $saldo_bono_virtual = 0;
                $saldo_bono_virtual_free_ganado = 0;
                $saldo_apuestas_casino_vivo = 0;

                if ($data != null) {

                    $saldo_recarga = floatval($data->{'usuario_saldoresumen.saldo_recarga'});
                    $saldo_apuestas = floatval($data->{'usuario_saldoresumen.saldo_apuestas'});
                    $saldo_premios = floatval($data->{'usuario_saldoresumen.saldo_premios'});
                    $saldo_notaret_pagadas = floatval($data->{'usuario_saldoresumen.saldo_notaret_pagadas'});
                    $saldo_notaret_pend = floatval($data->{'usuario_saldoresumen.saldo_notaret_pend'});
                    $saldo_ajustes_entrada = floatval($data->{'usuario_saldoresumen.saldo_ajustes_entrada'});
                    $saldo_ajustes_salida = floatval($data->{'usuario_saldoresumen.saldo_ajustes_salida'});
                    $saldo_bono = floatval($data->{'usuario_saldoresumen.saldo_bono'});
                    $saldo_notaret_creadas = floatval($data->{'usuario_saldoresumen.saldo_notaret_creadas'});
                    $saldo_apuestas_casino = floatval($data->{'usuario_saldoresumen.saldo_apuestas_casino'});
                    $saldo_premios_casino = floatval($data->{'usuario_saldoresumen.saldo_premios_casino'});
                    $saldo_notaret_eliminadas = floatval($data->{'usuario_saldoresumen.saldo_notaret_eliminadas'});
                    $saldo_bono_free_ganado = floatval($data->{'usuario_saldoresumen.saldo_bono_free_ganado'});
                    $saldo_bono_casino_free_ganado = floatval($data->{'usuario_saldoresumen.saldo_bono_casino_free_ganado'});
                    $saldo_bono_casino_vivo = floatval($data->{'usuario_saldoresumen.saldo_bono_casino_vivo'});
                    $saldo_bono_casino_vivo_free_ganado = floatval($data->{'usuario_saldoresumen.saldo_bono_casino_vivo_free_ganado'});
                    $saldo_bono_virtual = floatval($data->{'usuario_saldoresumen.saldo_bono_virtual'});
                    $saldo_bono_virtual_free_ganado = floatval($data->{'usuario_saldoresumen.saldo_bono_virtual_free_ganado'});
                    $saldo_apuestas_casino_vivo = floatval($data->{'usuario_saldoresumen.saldo_apuestas_casino_vivo'});
                }

                $sql = "
select usuario_id,sum(vlr_apuesta) as vlr_apuesta,sum(vlr_premio) as vlr_premio
from it_ticket_enc
where fecha_cierre = '" . date('Y-m-d') . "' and usuario_id = '" . $Usuario->usuarioId . "'";
                $data = $BonoInterno->execQuery('', $sql);
                $data = $data[0];
                if ($data != null) {
                    $saldo_apuestas = $saldo_apuestas + floatval($data->{'.vlr_apuesta'});
                    $saldo_premios = $saldo_premios + floatval($data->{'.vlr_premio'});
                }

                $sql = "
        SELECT usuario_mandante.moneda,
       COUNT(transaccion_juego.transjuego_id)                                                             count,
       SUM(CASE WHEN transaccion_juego.tipo != 'FREESPIN' THEN transaccion_juego.valor_ticket ELSE 0 END) apuestas,
       SUM(CASE WHEN transaccion_juego.tipo != 'FREESPIN' THEN transaccion_juego.valor_premio ELSE 0 END) premios,
       SUM(CASE WHEN transaccion_juego.tipo = 'FREESPIN' THEN transaccion_juego.valor_ticket ELSE 0 END)  apuestasBonus,
       SUM(CASE WHEN transaccion_juego.tipo = 'FREESPIN' THEN transaccion_juego.valor_premio ELSE 0 END)  premiosBonus,
       SUM(transaccion_juego.valor_gratis)                                                                apuestasSaldogratis
FROM transaccion_juego
         INNER JOIN usuario_mandante ON (usuario_mandante.usumandante_id = transaccion_juego.usuario_id)
where 1 = 1
  AND ((transaccion_juego.fecha_crea)) >= '" . date('Y-m-d') . " 00:00:00'
  AND ((transaccion_juego.fecha_crea)) < '" . date('Y-m-d') . " 23:59:59'
  AND ((usuario_mandante.usuario_mandante)) = '" . $Usuario->usuarioId . "'
  
  ";
                $data = $BonoInterno->execQuery('', $sql);
                $data = $data[0];
                if ($data != null) {
                    $saldo_apuestas_casino = $saldo_apuestas_casino + floatval($data->{'.apuestas'});
                    $saldo_premios_casino = $saldo_premios_casino + floatval($data->{'.premios'});
                    $saldo_premios_casino = $saldo_premios_casino + floatval($data->{'.premiosBonus'});
                }

                $sql = "
select usuario_id,sum(usuario_recarga.valor) as valor
from usuario_recarga
where fecha_crea LIKE '" . date('Y-m-d') . "%' and usuario_id = '" . $Usuario->usuarioId . "'";
                $data = $BonoInterno->execQuery('', $sql);
                $data = $data[0];
                if ($data != null) {
                    $saldo_recarga = $saldo_recarga + floatval($data->{'.valor'});
                }


                $sql = "
select usuario_id,sum(cuenta_cobro.valor) as valor
from cuenta_cobro
where fecha_pago  LIKE '" . date('Y-m-d') . "%' and usuario_id = '" . $Usuario->usuarioId . "'";
                $data = $BonoInterno->execQuery('', $sql);
                $data = $data[0];
                if ($data != null) {
                    $saldo_notaret_pagadas = $saldo_notaret_pagadas + floatval($data->{'.valor'});
                }

                $esSeguro = true;

                //Usuario no haya retirado menos del 90% de lo depositado
                if ($saldo_notaret_pagadas > ($saldo_recarga * 0.9)) {
                    $esSeguro = false;
                }

                //Usuario tiene que jugar un 1.6 el valor depositado
                if (($saldo_apuestas + $saldo_apuestas_casino + $saldo_apuestas_casino_vivo) < ($saldo_recarga * 1.6)) {
                    $esSeguro = false;
                }

                //Usuario tiene que perder un 30% del valor depositado
                if ((($saldo_apuestas + $saldo_apuestas_casino) - ($saldo_premios + $saldo_premios_casino)) < 300) {
                    $esSeguro = false;
                }

                if ($esSeguro) {
                    exec("php -f " . __DIR__ . "../src/imports/Slack/message.php '" . 'esSeguro-CRON ' . $CuentaCobro->cuentaId . ' ' . $Usuario->usuarioId . "' '#dev' > /dev/null & ");
                    $_ENV["connectionGlobal"]->setConnection($connOriginal);
                    $CuentaCobro = new \Backend\dto\CuentaCobro($datum->{'cuenta_cobro.cuenta_id'});

                    if (($CuentaCobro->getMediopagoId() == "" || $CuentaCobro->getMediopagoId() == "0")) {
                        $CuentaCobro->setEstado('A');
                    } else {
                        $CuentaCobro->setEstado('P');
                    }
                    if ($CuentaCobro->getMediopagoId() != "" && ($CuentaCobro->getVersion() == "2")) {
                        $CuentaCobro->setEstado('A');
                    }
                    if ($CuentaCobro->getMediopagoId() == "2088007" && ($CuentaCobro->getVersion() == "2")) {
                        $CuentaCobro->setEstado('P');
                    }
                    $CuentaCobroMySqlDAO = new \Backend\mysql\CuentaCobroMySqlDAO();
                    $rowsUpdate = $CuentaCobroMySqlDAO->update($CuentaCobro, " AND (estado='A' OR estado='M') ");

                    if ($rowsUpdate == null || $rowsUpdate <= 0) {

                        $CuentaCobroMySqlDAO->getTransaction()->rollback();

                    } else {
                        $CuentaCobroMySqlDAO->getTransaction()->commit();

                    }


                    $_ENV["connectionGlobal"]->setConnection($connDB5);

                } else {

                }


            }
        }

    }
    print_r('PROCCESS OK');


    sleep(3);

}