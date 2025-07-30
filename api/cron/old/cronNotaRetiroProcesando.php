<?php

/**
 * Prueba api
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */

use Backend\dto\Automation;
use Backend\dto\BonoInterno;
use Backend\dto\Banco;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Mandante;
use Backend\dto\UsuarioBanco;
use Backend\dto\Categoria;
use Backend\dto\CategoriaProducto;
use Backend\dto\Clasificador;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\CuentaCobro;
use Backend\dto\UsuarioMandante;
use Backend\dto\TransaccionProducto;
use Backend\integrations\payment\MONNETSERVICES;
use Backend\dto\TransaccionApi;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\UsuarioAutomation;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioLog2;
use Backend\integrations\casino\Playson;
use Backend\integrations\payout\WEPAY4USERVICES;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransjuegoLogMySqlDAO;
use Backend\mysql\UsuarioAutomationMySqlDAO;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use \PDO;
use Backend\sql\ConnectionProperty;
use Backend\dto\UsuarioSession;
use Backend\dto\UsuarioToken;
use Backend\dto\Usuario;
use Backend\mysql\UsuarioLog2MySqlDAO;
use Backend\websocket\WebsocketUsuario;

require_once __DIR__ . '../../vendor/autoload.php';
require(__DIR__.'/cronSegundosCron.php');

ini_set('max_execution_time',0);
ini_set('memory_limit', '-1');

for($i=0;$i<10;$i++) {


    $filename = __DIR__ . '/lastruncronNotaRetiroProcesando';

    $datefilename = date("Y-m-d H:i:s", filemtime($filename));
    if ($datefilename <= date("Y-m-d H:i:s", strtotime('-1 hour'))) {
        unlink($filename);

    }
    if (file_exists($filename)) {
        throw new Exception("There is a process currently running", "1");
        exit();
    }
    file_put_contents($filename, 'RUN');


    $BonoInterno = new BonoInterno();

    $sqlProcesoInterno2 = "

SELECT * FROM proceso_interno2 WHERE tipo='ACTUALIZACIONRETIROSX'
";


    $data = $BonoInterno->execQuery('', $sqlProcesoInterno2);
    $data = $data[0];

    $line = $data->{'proceso_interno2.fecha_ultima'};

    if ($line == '') {
        exit();
    }


    $fechaL1 = date('Y-m-d H:i:00', strtotime($line . '+1 minute'));
    $fechaL2 = date('Y-m-d H:i:59', strtotime($line . '+1 minute'));


    if ($fechaL1 >= date('Y-m-d H:i:00', strtotime('-5 minute'))) {
        unlink($filename);
        exit();
    }

    $BonoInterno = new BonoInterno();
    $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
    $transaccion = $BonoDetalleMySqlDAO->getTransaction();

    $sqlProcesoInterno2 = "
UPDATE proceso_interno2 SET fecha_ultima='" . $fechaL2 . "' WHERE  tipo='ACTUALIZACIONRETIROSX';
";


    $data = $BonoInterno->execQuery($transaccion, $sqlProcesoInterno2);
    $transaccion->commit();

    print_r('Fecha Inicio: ' . $fechaL1 . ' - Fecha Fin: ' . $fechaL2);

    $CuentaCobro = new CuentaCobro();

//$CountFaileds=0;

    $SkeepRows = 0;

    $MaxRows = 100000;

    $rules = [];

    array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "X", "op" => "eq"));

    array_push($rules, array("field" => "cuenta_cobro.fecha_cambio", "data" => $fechaL1, "op" => "ge"));
    array_push($rules, array("field" => "cuenta_cobro.fecha_cambio", "data" => $fechaL2, "op" => "le"));
    array_push($rules, array("field" => "producto.proveedor_id", "data" => 97, "op" => "neq"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);

    $select = "cuenta_cobro.cuenta_id, cuenta_cobro.transproducto_id";
    $grouping = "cuenta_cobro.cuenta_id";

    $daydimensionFechaPorPago = false;

    $cuentas = $CuentaCobro->getCuentasCobroCustom($select, "cuenta_cobro.cuenta_id", "desc", $SkeepRows, $MaxRows, $json, true, $grouping, '', true, $daydimensionFechaPorPago);

    $cuentas = json_decode($cuentas);

    $CuentasCobro = $cuentas->data;


    foreach ($CuentasCobro as $key => $Id2) {


        $CuentaCobro = new CuentaCobro($CuentasCobro[$key]->{'cuenta_cobro.cuenta_id'});

        //$CuentaCobro->setFechaPago(date('Y-m-d H:i:s'));
        if ($CuentaCobro->usucambioId == "" || $CuentaCobro->usucambioId == "null" || $CuentaCobro->usucambioId == null) {
            $CuentaCobro->usucambioId = 0;
        }
        if ($CuentaCobro->usupagoId == "" || $CuentaCobro->usupagoId == "null" || $CuentaCobro->usupagoId == null) {
            $CuentaCobro->usupagoId = 0;
        }
        if ($CuentaCobro->usurechazaId == "" || $CuentaCobro->usurechazaId == "null" || $CuentaCobro->usurechazaId == null) {
            $CuentaCobro->usurechazaId = 0;
        }
        if ($CuentaCobro->fechaCambio == "" || $CuentaCobro->fechaCambio == "0000-00-00 00:00:00" || $CuentaCobro->fechaCambio == null) {
            $CuentaCobro->fechaCambio = date("Y-m-d H:i:s");
        }

        if ($CuentaCobro->fechaAccion == "" || $CuentaCobro->fechaAccion == "0000-00-00 00:00:00" || $CuentaCobro->fechaAccion == null) {
            $CuentaCobro->fechaAccion = date("Y-m-d H:i:s");
        }


        $UsuarioBanco = new UsuarioBanco($CuentaCobro->mediopagoId);
        $Banco = new Banco($UsuarioBanco->bancoId);

        $TransaccionProducto = new TransaccionProducto($CuentaCobro->transproductoId);
        $Producto = new Producto ($TransaccionProducto->getProductoId());
        $Proveedor = new Proveedor($Producto->getProveedorId());


        if ($Proveedor->getAbreviado() == "MONNETPAY") {

            try {
                $MONNETSERVICES = new MONNETSERVICES();
                $MONNETSERVICES->cashOut($CuentaCobro, $Producto->productoId, $TransaccionProducto);
                $CuentaCobro->setEstado('S');
            } catch (Exception $e) {
                if ($e->getCode() == 100000) {
                    //$CountFaileds = $CountFaileds + 1;
                }
            }

        }


        $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();
        //$CuentaCobro->setUsupagoId($_SESSION['usuario2']);
        $CuentaCobroMySqlDAO->update($CuentaCobro);
        $CuentaCobroMySqlDAO->getTransaction()->commit();

    }


    $CuentaCobro = new CuentaCobro();

//$CountFaileds=0;

    $cantAEnviar = 0;
    $entroPrimeraVez = false;

    while (!$entroPrimeraVez || $cantAEnviar == 50) {
        $cantAEnviar = 0;
        $entroPrimeraVez = true;


        $SkeepRows = 0;

        $MaxRows = 100000;

        $rules = [];

        array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "X", "op" => "eq"));

        array_push($rules, array("field" => "cuenta_cobro.fecha_cambio", "data" => $fechaL1, "op" => "ge"));
        array_push($rules, array("field" => "cuenta_cobro.fecha_cambio", "data" => $fechaL2, "op" => "le"));
        array_push($rules, array("field" => "producto.proveedor_id", "data" => 97, "op" => "eq"));

        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);

        $select = "cuenta_cobro.cuenta_id, cuenta_cobro.transproducto_id";
        $grouping = "cuenta_cobro.cuenta_id";

        $daydimensionFechaPorPago = false;

        $cuentas = $CuentaCobro->getCuentasCobroCustom($select, "cuenta_cobro.cuenta_id", "desc", $SkeepRows, 50, $json, true, $grouping, '', true, $daydimensionFechaPorPago);

        $cuentas = json_decode($cuentas);

        $CuentasCobro = $cuentas->data;

        $CuentasCobros = array();

        foreach ($CuentasCobro as $key => $Id2) {
            $cantAEnviar++;

            $CuentaCobro = new CuentaCobro($CuentasCobro[$key]->{'cuenta_cobro.cuenta_id'});

            array_push($CuentasCobros, $CuentaCobro);

        }

        if (oldCount($CuentasCobros) > 0) {

            if (oldCount($CuentasCobros) >= 1) {

                $WEPAY4USERIVCES = new WEPAY4USERVICES();
                $transactions = $WEPAY4USERIVCES->cashOut2($CuentasCobros);

                foreach ($transactions as $key => $value) {

                    if ($transactions[$key]["Status"] == "1") {
                        $CuentaCobro = new CuentaCobro($transactions[$key]["CuentaId"]);

                        $CuentaCobro->setEstado('S');

                        if ($CuentaCobro->usucambioId == "" || $CuentaCobro->usucambioId == "null" || $CuentaCobro->usucambioId == null) {
                            $CuentaCobro->usucambioId = 0;
                        }
                        if ($CuentaCobro->usupagoId == "" || $CuentaCobro->usupagoId == "null" || $CuentaCobro->usupagoId == null) {
                            $CuentaCobro->usupagoId = 0;
                        }
                        if ($CuentaCobro->usurechazaId == "" || $CuentaCobro->usurechazaId == "null" || $CuentaCobro->usurechazaId == null) {
                            $CuentaCobro->usurechazaId = 0;
                        }
                        if ($CuentaCobro->fechaCambio == "" || $CuentaCobro->fechaCambio == "0000-00-00 00:00:00" || $CuentaCobro->fechaCambio == null) {
                            $CuentaCobro->fechaCambio = date("Y-m-d H:i:s");
                        }

                        if ($CuentaCobro->fechaAccion == "" || $CuentaCobro->fechaAccion == "0000-00-00 00:00:00" || $CuentaCobro->fechaAccion == null) {
                            $CuentaCobro->fechaAccion = date("Y-m-d H:i:s");
                        }

                        $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();
                        $CuentaCobroMySqlDAO->update($CuentaCobro);
                        $CuentaCobroMySqlDAO->getTransaction()->commit();

                    } else {
                        $CountFaileds = $CountFaileds + 1;

                    }
                }

                /*if ($transactions["CantFaileds"]["CantFaileds"]!=0){

                     $CountFaileds=$CountFaileds+$transactions["CantFaileds"]["CantFaileds"];

                }*/

            } else {

                try {
                    $WEPAY4USERIVCES = new WEPAY4USERVICES();
                    $WEPAY4USERIVCES->cashOut($CuentaCobro);
                } catch (Exception $e) {
                    if ($e->getCode() == 100000) {
                    }
                }

            }
        }
        sleep(1);

    }

    unlink($filename);

    print_r('PROCCESS OK');
    sleep(3);

}