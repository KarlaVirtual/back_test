<?php

use Backend\dto\Banco;
use Backend\dto\Usuario;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\CuentaCobro;
use Backend\dto\UsuarioBanco;
use Backend\dto\TransaccionProducto;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\integrations\payout\LPGSERVICES;
use Backend\integrations\payout\PAYKUSERVICES;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\integrations\payout\ANINDASERVICES;
use Backend\integrations\payout\H2HBCPSERVICES;
use Backend\integrations\payout\KASHIOSERVICES;
use Backend\integrations\payment\MONNETSERVICES;
use Backend\integrations\payout\CONEKTASERVICES;
use Backend\integrations\payout\EUKAPAYSERVICES;
use Backend\integrations\payout\WEPAY4USERVICES;
use Backend\integrations\payout\GLOBOKASSERVICES;
use Backend\integrations\payout\INSWITCHSERVICES;
use Backend\integrations\payout\MEGAPAYZSERVICES;
use Backend\integrations\payout\PAYPHONESERVICES;
use Backend\integrations\payout\STARPAGOSERVICES;
use Backend\integrations\payout\COINSPAIDSERVICES;
use Backend\integrations\payout\DIRECTA24SERVICES;
use Backend\integrations\payout\INTERBANKSERVICES;
use Backend\integrations\payout\PAYFORFUNSERVICES;
use Backend\Integrations\payout\PAYBROKERSSERVICES;
use Backend\integrations\payment\ASTROPAYCARDSERVICES;

/**
 * Client/PayAllWithdrawalRequestsAPI
 *
 * Este script procesa múltiples solicitudes de retiro mediante la API.
 *
 * @param object $params Objeto que contiene los siguientes valores:
 * @param array $params ->Ids Lista de identificadores de solicitudes de retiro.
 * @param int|string $params ->ProductoId Identificador del producto asociado. Si está vacío, se utiliza ProductId.
 *
 * @return array Respuesta con los siguientes valores:
 *  - HasError (bool): Indica si ocurrió un error.
 *  - AlertType (string): Tipo de alerta ('success' o 'danger').
 *  - AlertMessage (string): Mensaje de alerta.
 *  - ModelErrors (array): Lista de errores del modelo.
 *  - Data (array): Datos adicionales de la respuesta.
 *
 * @throws Exception Si el usuario no tiene permisos para ejecutar esta acción.
 */

/* Verifica permisos de usuario antes de procesar solicitudes de retiro. */
$ConfigurationEnvironment = new ConfigurationEnvironment();
if (!$ConfigurationEnvironment->checkUserPermission('Client/PayAllWithdrawalRequestsAPI', $_SESSION['win_perfil'], $_SESSION['usuario'])) {
    throw new Exception('Permiso denegado', 100035);
}

$Id = $params->Id;
/* Se inicializan arrays para almacenar cuentas de cobro de diferentes fuentes. */
$Ids = $params->Ids;

$CuentasCobro = array();
$CuentasCobroInterbank = array();
$CuentasCobroH2hbcp = array();

/* Inicializa dos arrays y asigna un ID de producto basado en condiciones. */
$CuentasCobroMonnet = array();
$CuentasCobroPayphone = array();
$ProductoId = $params->ProductoId;

if ($ProductoId == '') {
    $ProductoId = $params->ProductId;
}

/* Variables booleanas para controlar el flujo y conteo de fallos en un proceso. */
$continue = false;
$continueInterbank = false;
$continueH2hbcp = false;
$continueMonnet = false;
$continuePayphone = false;

$CountFaileds = 0;

foreach ($Ids as $Id) {

    /* Se crea una nueva instancia de la clase CuentaCobro con el identificador proporcionado. */
    $CuentaCobro = new CuentaCobro($Id);

    if ($CuentaCobro->getEstado() == "P" && $CuentaCobro->cuentaId == "1398255") {

        /* Se establece el estado de CuentaCobro y se maneja el usucambioId. */
        $CuentaCobro->setEstado('S');
        $CuentaCobro->setUsupagoId($_SESSION['usuario2']);
        //$CuentaCobro->setFechaPago(date('Y-m-d H:i:s'));

        if ($CuentaCobro->usucambioId == "" || $CuentaCobro->usucambioId == "null" || $CuentaCobro->usucambioId == null) {
            $CuentaCobro->usucambioId = 0;
        }

        /* Asignación de cero a variables si están vacías o nulas en un objeto. */
        if ($CuentaCobro->usupagoId == "" || $CuentaCobro->usupagoId == "null" || $CuentaCobro->usupagoId == null) {
            $CuentaCobro->usupagoId = 0;
        }

        if ($CuentaCobro->usurechazaId == "" || $CuentaCobro->usurechazaId == "null" || $CuentaCobro->usurechazaId == null) {
            $CuentaCobro->usurechazaId = 0;
        }

        /* Asigna la fecha actual si las fechas están vacías o son inválidas. */
        if ($CuentaCobro->fechaCambio == "" || $CuentaCobro->fechaCambio == "0000-00-00 00:00:00" || $CuentaCobro->fechaCambio == null) {
            $CuentaCobro->fechaCambio = date("Y-m-d H:i:s");
        }

        if ($CuentaCobro->fechaAccion == "" || $CuentaCobro->fechaAccion == "0000-00-00 00:00:00" || $CuentaCobro->fechaAccion == null) {
            $CuentaCobro->fechaAccion = date("Y-m-d H:i:s");
        }

        /* Se crea un usuario y banco, luego se realiza un cashOut si cumple condición. */
        $UsuarioBanco = new UsuarioBanco($CuentaCobro->mediopagoId);
        $Banco = new Banco($UsuarioBanco->bancoId);

        if ($CuentaCobro->cuentaId == "1398255") {
            $MONNETSERVICES = new MONNETSERVICES();
            $MONNETSERVICES->cashOut($CuentaCobro, $ProductoId);
        }

        /* Actualiza una cuenta de cobro y confirma la transacción sin errores. */
        $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();
        $CuentaCobroMySqlDAO->update($CuentaCobro);
        $CuentaCobroMySqlDAO->getTransaction()->commit();

        $response["HasError"] = false;
        $response["AlertType"] = "success";

        /* Se inicializan variables para almacenar mensajes de alerta, errores y datos en una respuesta. */
        $response["AlertMessage"] = '';
        $response["ModelErrors"] = [];
        $response["Data"] = [];
    } elseif ($CuentaCobro->getEstado() == "P") {

        /* Establece estado, usuario de pago y verifica ID de cambio en CuentaCobro. */
        $CuentaCobro->setEstado('S');
        $CuentaCobro->setUsupagoId($_SESSION['usuario2']);
        //$CuentaCobro->setFechaPago(date('Y-m-d H:i:s'));

        if ($CuentaCobro->usucambioId == "" || $CuentaCobro->usucambioId == "null" || $CuentaCobro->usucambioId == null) {
            $CuentaCobro->usucambioId = 0;
        }

        /* Asigna cero a usupagoId y usurechazaId si están vacíos o nulos. */
        if ($CuentaCobro->usupagoId == "" || $CuentaCobro->usupagoId == "null" || $CuentaCobro->usupagoId == null) {
            $CuentaCobro->usupagoId = 0;
        }

        if ($CuentaCobro->usurechazaId == "" || $CuentaCobro->usurechazaId == "null" || $CuentaCobro->usurechazaId == null) {
            $CuentaCobro->usurechazaId = 0;
        }

        /* Asigna la fecha actual si ciertas propiedades están vacías o nulas. */
        if ($CuentaCobro->fechaCambio == "" || $CuentaCobro->fechaCambio == "0000-00-00 00:00:00" || $CuentaCobro->fechaCambio == null) {
            $CuentaCobro->fechaCambio = date("Y-m-d H:i:s");
        }

        if ($CuentaCobro->fechaAccion == "" || $CuentaCobro->fechaAccion == "0000-00-00 00:00:00" || $CuentaCobro->fechaAccion == null) {
            $CuentaCobro->fechaAccion = date("Y-m-d H:i:s");
        }

        /* Condicional que verifica un método de pago y crea un producto y proveedor asociado. */
        if ($CuentaCobro->mediopagoId == 2088007) {
            // $Banco = new Banco($UsuarioBanco->bancoId);
            $Producto = new Producto($ProductoId);
            $Proveedor = new Proveedor($Producto->getProveedorId());
        } else {
            /* inicializa objetos de UsuarioBanco, Producto y Proveedor según ciertas condiciones. */
            $UsuarioBanco = new UsuarioBanco($CuentaCobro->mediopagoId);
            // $Banco = new Banco($UsuarioBanco->bancoId);
            $Producto = new Producto($ProductoId);
            $Proveedor = new Proveedor($Producto->getProveedorId());
        }

        /* verifica un proveedor y gestiona transacciones con manejo de excepciones. */
        if ($Proveedor->getAbreviado() == "LPGCO") {
            try {
                $LPGSERVICES = new LPGSERVICES();
                $LPGSERVICES->cashOut($CuentaCobro);

                $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();
                $CuentaCobroMySqlDAO->update($CuentaCobro);
                $CuentaCobroMySqlDAO->getTransaction()->commit();
            } catch (Exception $e) {
                if ($e->getCode() == 100000) {
                    $CountFaileds = $CountFaileds + 1;
                }
            }
        }

        /* Verifica si el proveedor es "WEPAY4U" y agrega una cuenta de cobro. */
        if ($Proveedor->getAbreviado() == "WEPAY4U") {
            array_push($CuentasCobro, $CuentaCobro);
            $continue = true;
        }

        /* Se añade una cuenta a una lista si el proveedor es "INTERBANK". */
        if ($Proveedor->getAbreviado() == "INTERBANK") {
            array_push($CuentasCobroInterbank, $CuentaCobro);
            $continueInterbank = true;
        }

        /* Se agrega una cuenta de cobro si el proveedor es "H2HBCP". */
        if ($Proveedor->getAbreviado() == "H2HBCP") {
            array_push($CuentasCobroH2hbcp, $CuentaCobro);
            $continueH2hbcp = true;
        }

        /* Proceso de retiro de fondos con manejo de excepciones para Astropaycard. */
        if ($Proveedor->getAbreviado() == "ASTROPAYCARD") {
            try {
                $ASTROPAYCARDSERVICES = new ASTROPAYCARDSERVICES();
                $ASTROPAYCARDSERVICES->cashOut($CuentaCobro);

                $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();
                $CuentaCobroMySqlDAO->update($CuentaCobro);
                $CuentaCobroMySqlDAO->getTransaction()->commit();
            } catch (Exception $e) {
                if ($e->getCode() == 100000) {
                    $CountFaileds = $CountFaileds + 1;
                }
            }
        }

        /* Condicional que ejecuta un servicio de retiro y maneja excepciones específicas. */
        if ($Proveedor->getAbreviado() == "GLOBOKASRETIROS") {
            try {
                $GLOBOKASSERVICES = new GLOBOKASSERVICES();
                $GLOBOKASSERVICES->cashOut($CuentaCobro);
            } catch (Exception $e) {
                if ($e->getCode() == 100000) {
                    $CountFaileds = $CountFaileds + 1;
                }
            }
        }

        /* verifica si el proveedor es "MONNETPAY" y gestiona cuentas de cobro. */
        if ($Proveedor->getAbreviado() == "MONNETPAY") {
            array_push($CuentasCobroMonnet, $CuentaCobro);
            $continueMonnet = true;
        }

        /* Código que procesa un pago usando PAYKUPAY y maneja excepciones en caso de error. */
        if ($Proveedor->getAbreviado() == "PAYKUPAY") {
            try {
                $PAYKUSERVICES = new PAYKUSERVICES();
                $PAYKUSERVICES->cashOut($CuentaCobro, $ProductoId);

                $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();
                $CuentaCobroMySqlDAO->update($CuentaCobro);
                $CuentaCobroMySqlDAO->getTransaction()->commit();
            } catch (Exception $e) {
                if ($e->getCode() == 100000) {
                    $CountFaileds = $CountFaileds + 1;
                }
            }
        }

        /* Se verifica si el proveedor es DIRECTA24PAY y se intenta realizar un cash out. */
        if ($Proveedor->getAbreviado() == "DIRECTA24PAY") {
            try {
                $DIRECTA24SERVICES = new DIRECTA24SERVICES();
                $DIRECTA24SERVICES->cashOut($CuentaCobro, $Producto);
            } catch (Exception $e) {
                if ($e->getCode() == 100000) {
                    $CountFaileds = $CountFaileds + 1;
                }
            }
        }

        /* gestiona un retiro y maneja excepciones para un proveedor específico. */
        if ($Proveedor->getAbreviado() == "PBROKERSPA") {
            try {
                $PAYBROKERSSERVICES = new PAYBROKERSSERVICES();
                $PAYBROKERSSERVICES->cashOut($CuentaCobro, $ProductoId);

                $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();
                $CuentaCobroMySqlDAO->update($CuentaCobro);
                $CuentaCobroMySqlDAO->getTransaction()->commit();
            } catch (Exception $e) {
                if ($e->getCode() == 21015) {
                    $CountFaileds = $CountFaileds + 1;
                }
            }
        }

        /* maneja transacciones de retiro para distintos proveedores utilizando clases específicas. */
        if ($Proveedor->getAbreviado() == "CONEKTARETIROS") {
            $CONEKTASERVICES = new CONEKTASERVICES();
            $CONEKTASERVICES->cashOut($CuentaCobro);
        }

        if ($Proveedor->getAbreviado() == "P4FPAYOUT") {
            try {
                $PAYFORFUNSERVICES = new PAYFORFUNSERVICES();
                $PAYFORFUNSERVICES->cashOut($CuentaCobro);

                $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();
                $CuentaCobroMySqlDAO->update($CuentaCobro);
                $CuentaCobroMySqlDAO->getTransaction()->commit();
            } catch (Exception $e) {
                if ($e->getCode() == 21015) {
                    $CountFaileds = $CountFaileds + 1;
                }
            }
        }

        /* Código que procesa un retiro de efectivo para el proveedor KASHIOOUT y maneja excepciones. */
        if ($Proveedor->getAbreviado() == "KASHIOOUT") {
            try {
                $KASHIOSERVICES = new KASHIOSERVICES();
                $KASHIOSERVICES->cashOut($CuentaCobro, $Producto);

                $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();
                $CuentaCobroMySqlDAO->update($CuentaCobro);
                $CuentaCobroMySqlDAO->getTransaction()->commit();
            } catch (Exception $e) {
                if ($e->getCode() == 21015) {
                    $CountFaileds = $CountFaileds + 1;
                }
            }
        }

        /* Realiza un retiro de efectivo y maneja errores en transacciones de Payphone. */
        if ($Proveedor->getAbreviado() == "PAYPHONEOUT") {
            try {
                $PAYPHONESERVICES = new PAYPHONESERVICES();
                $PAYPHONESERVICES->cashOut($CuentaCobro, $Producto);

                $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();
                $CuentaCobroMySqlDAO->update($CuentaCobro);
                $CuentaCobroMySqlDAO->getTransaction()->commit();
            } catch (Exception $e) {
                if ($e->getCode() == 21015) {
                    $CountFaileds = $CountFaileds + 1;
                }
            }
        }

        /* Se verifica el proveedor y se intenta realizar un cashOut, manejando excepciones. */
        if ($Proveedor->getAbreviado() == "INSWITCHOUT") {
            try {
                $INSWITCHSERVICES = new INSWITCHSERVICES();
                $INSWITCHSERVICES->cashOut($CuentaCobro);
            } catch (Exception $e) {
                if ($e->getCode() == 21015) {
                    $CountFaileds = $CountFaileds + 1;
                }
            }
        }

        /* Código que gestiona un retiro de fondos y maneja excepciones específicas. */
        if ($Proveedor->getAbreviado() == "COINSPAIDOUT") {
            try {
                $COINSPAIDSERVICES = new COINSPAIDSERVICES();
                $COINSPAIDSERVICES->cashOut($CuentaCobro, $Producto);
            } catch (Exception $e) {
                if ($e->getCode() == 21015) {
                    $CountFaileds = $CountFaileds + 1;
                }
            }
        }

        /* verifica un proveedor y actualiza datos de cuenta en MySQL. */
        if ($Proveedor->getAbreviado() == "PAYPHONEOUT") {
            array_push($CuentasCobroPayphone, $CuentaCobro);
            $continuePayphone = true;
        }

        /* Código que procesa un retiro de efectivo para el proveedor MEGAPAYZOUT y maneja excepciones. */
        if ($Proveedor->getAbreviado() == "MEGAPAYZOUT") {
            try {
                $MEGAPAYZSERVICES = new MEGAPAYZSERVICES();
                $MEGAPAYZSERVICES->cashOut($CuentaCobro, $Producto);

                $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();
                $CuentaCobroMySqlDAO->update($CuentaCobro);
                $CuentaCobroMySqlDAO->getTransaction()->commit();
            } catch (Exception $e) {
                if ($e->getCode() == 21015) {
                    $CountFaileds = $CountFaileds + 1;
                }
            }
        }

        /* Código que procesa un retiro de efectivo para el proveedor ANINDAOUT y maneja excepciones. */
        if ($Proveedor->getAbreviado() == "ANINDAOUT") {
            try {
                $ANINDASERVICES = new ANINDASERVICES();
                $ANINDASERVICES->cashOut($CuentaCobro, $Producto);

                $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();
                $CuentaCobroMySqlDAO->update($CuentaCobro);
                $CuentaCobroMySqlDAO->getTransaction()->commit();
            } catch (Exception $e) {
                if ($e->getCode() == 21015) {
                    $CountFaileds = $CountFaileds + 1;
                }
            }
        }

        if (!$continueInterbank) {
            $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();
            //$CuentaCobro->setEstado('S');
            $CuentaCobro->setUsupagoId($_SESSION['usuario2']);
            $CuentaCobroMySqlDAO->update($CuentaCobro);
            $CuentaCobroMySqlDAO->getTransaction()->commit();
        }

        /* Actualiza el estado de CuentaCobro si la condición no se cumple. */
        if (!$continueH2hbcp) {
            $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();
            //$CuentaCobro->setEstado('S');
            $CuentaCobro->setUsupagoId($_SESSION['usuario2']);
            $CuentaCobroMySqlDAO->update($CuentaCobro);
            $CuentaCobroMySqlDAO->getTransaction()->commit();
        }

        /* Inicializa una respuesta sin errores, con éxito y lista para almacenar datos. */
        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = '';
        $response["ModelErrors"] = [];
        $response["Data"] = [];
    } else {

        /* Código que maneja un error al intentar cambiar el estado de un retiro procesado. */
        $response["HasError"] = true;
        $response["AlertType"] = "danger";
        $response["AlertMessage"] = 'No puedes cambiar el estado de un retiro ya procesado.';
        $response["ModelErrors"] = [];
        $response["Data"] = [];
    }
}

if ($continue) {
    foreach ($CuentasCobro as $key => $Id2) {

        /* Inicializa y actualiza el estado de una cuenta de cobro en el sistema. */
        $CuentaCobro = new CuentaCobro($CuentasCobro[$key]->cuentaId);

        $CuentaCobro->setEstado('X');
        $CuentaCobro->setUsupagoId($_SESSION['usuario2']);
        //$CuentaCobro->setFechaPago(date('Y-m-d H:i:s'));

        if ($CuentaCobro->usucambioId == "" || $CuentaCobro->usucambioId == "null" || $CuentaCobro->usucambioId == null) {
            $CuentaCobro->usucambioId = 0;
        }

        /* asigna 0 a 'usupagoId' y 'usurechazaId' si están vacíos o nulos. */
        if ($CuentaCobro->usupagoId == "" || $CuentaCobro->usupagoId == "null" || $CuentaCobro->usupagoId == null) {
            $CuentaCobro->usupagoId = 0;
        }

        if ($CuentaCobro->usurechazaId == "" || $CuentaCobro->usurechazaId == "null" || $CuentaCobro->usurechazaId == null) {
            $CuentaCobro->usurechazaId = 0;
        }

        /* asigna la fecha actual a ciertos campos vacíos o nulos. */
        if ($CuentaCobro->fechaCambio == "" || $CuentaCobro->fechaCambio == "0000-00-00 00:00:00" || $CuentaCobro->fechaCambio == null) {
            $CuentaCobro->fechaCambio = date("Y-m-d H:i:s");
        }

        if ($CuentaCobro->fechaAccion == "" || $CuentaCobro->fechaAccion == "0000-00-00 00:00:00" || $CuentaCobro->fechaAccion == null) {
            $CuentaCobro->fechaAccion = date("Y-m-d H:i:s");
        }

        /* Actualiza fecha y crea objetos de usuario y banco según cuentas de cobro. */
        $CuentaCobro->fechaCambio = date("Y-m-d H:i:s");

        $UsuarioBanco = new UsuarioBanco($CuentaCobro->mediopagoId);
        $Banco = new Banco($UsuarioBanco->bancoId);
        $Usuario = new Usuario($CuentaCobro->usuarioId);

        if ($ProductoId == "") {
            $Producto = new Producto($Banco->productoPago);
        } else {
            /* Se crea un objeto "Producto" basado en condiciones de ID y banco. */
            if ($ProductoId != '') {
                $Producto = new Producto($ProductoId);
            } elseif ($Banco->productoPago != '') {
                $Producto = new Producto($Banco->productoPago);
            } else {
                $Producto = new Producto($ProductoId);
            }
        }

        /* Creación de una transacción de producto y obtención del valor a pagar. */
        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
        $Transaction = $TransaccionProductoMySqlDAO->getTransaction();
        $valorFinal = $CuentaCobro->getValorAPagar();

        $TransaccionProducto = new TransaccionProducto();
        $TransaccionProducto->setProductoId($Producto->productoId);

        /* Establece atributos de un objeto TransaccionProducto, como usuario, valor y estado. */
        $TransaccionProducto->setUsuarioId($Usuario->usuarioId);
        $TransaccionProducto->setValor($valorFinal);
        $TransaccionProducto->setEstado('A');
        $TransaccionProducto->setTipo('T');
        $TransaccionProducto->setExternoId(0);
        $TransaccionProducto->setEstadoProducto('E');

        /* configura y guarda datos de una transacción de producto en MySQL. */
        $TransaccionProducto->setMandante($Usuario->mandante);
        $TransaccionProducto->setFinalId($CuentaCobro->getCuentaId());
        $TransaccionProducto->setFinalId(0);

        $transproductoId = $TransaccionProductoMySqlDAO->insert($TransaccionProducto);
        $TransaccionProductoMySqlDAO->getTransaction()->commit();

        /* Actualiza una cuenta de cobro en la base de datos mediante un DAO. */
        $CuentaCobro->setTransproductoId($transproductoId);
        $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();
        $CuentaCobroMySqlDAO->update($CuentaCobro);
        $CuentaCobroMySqlDAO->getTransaction()->commit();
    }

    if (false) {
        foreach ($CuentasCobro as $key => $Id2) {

            /* Se crea una nueva instancia de CuentaCobro utilizando un ID específico de $CuentasCobro. */
            $CuentaCobro = new CuentaCobro($CuentasCobro[$key]->cuentaId);

            if ($CuentaCobro->getEstado() == "S") {

                /* Actualiza el estado de cuenta y asigna ID de usuario, gestionando valores nulos. */
                $CuentaCobro->setEstado('P');
                $CuentaCobro->setUsupagoId($_SESSION['usuario2']);
                //$CuentaCobro->setFechaPago(date('Y-m-d H:i:s'));

                if ($CuentaCobro->usucambioId == "" || $CuentaCobro->usucambioId == "null" || $CuentaCobro->usucambioId == null) {
                    $CuentaCobro->usucambioId = 0;
                }

                /* Verifica si los IDs son nulos y los asigna a cero si es necesario. */
                if ($CuentaCobro->usupagoId == "" || $CuentaCobro->usupagoId == "null" || $CuentaCobro->usupagoId == null) {
                    $CuentaCobro->usupagoId = 0;
                }
                if ($CuentaCobro->usurechazaId == "" || $CuentaCobro->usurechazaId == "null" || $CuentaCobro->usurechazaId == null) {
                    $CuentaCobro->usurechazaId = 0;
                }

                /* Asigna la fecha actual si las fechas están vacías o en formato inválido. */
                if ($CuentaCobro->fechaCambio == "" || $CuentaCobro->fechaCambio == "0000-00-00 00:00:00" || $CuentaCobro->fechaCambio == null) {
                    $CuentaCobro->fechaCambio = date("Y-m-d H:i:s");
                }

                if ($CuentaCobro->fechaAccion == "" || $CuentaCobro->fechaAccion == "0000-00-00 00:00:00" || $CuentaCobro->fechaAccion == null) {
                    $CuentaCobro->fechaAccion = date("Y-m-d H:i:s");
                }

                /* Se instancian objetos relacionados a cuentas y productos en un sistema bancario. */
                $UsuarioBanco = new UsuarioBanco($CuentaCobro->mediopagoId);
                // $Banco = new Banco($UsuarioBanco->bancoId);
                $Producto = new Producto($ProductoId);
                $Proveedor = new Proveedor($Producto->getProveedorId());


                $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();

                /* Actualiza un registro de cuenta de cobro y obtiene la transacción asociada. */
                $CuentaCobroMySqlDAO->update($CuentaCobro);
                $CuentaCobroMySqlDAO->getTransaction()->commit();
            }
        }

        if (oldCount($CuentasCobro) > 1) {

            /* Crea un objeto y realiza una transacción de retiro de fondos. */
            $WEPAY4USERIVCES = new WEPAY4USERVICES();
            $transactions = $WEPAY4USERIVCES->cashOut2($CuentasCobro);

            foreach ($transactions as $key => $value) {

                if ($transactions[$key]["Status"] == "1") {

                    /* Se instancia un objeto "CuentaCobro" usando el identificador de cuenta de una transacción. */
                    $CuentaCobro = new CuentaCobro($transactions[$key]["CuentaId"]);

                    if ($CuentaCobro->getEstado() == "P") {


                        /* Se establece el estado y se asignan IDs a CuentaCobro condicionalmente. */
                        $CuentaCobro->setEstado('S');
                        $CuentaCobro->setUsupagoId($_SESSION['usuario2']);
                        $CuentaCobro->setTransproductoId($transactions[$key]["transproductoId"]);

                        if ($CuentaCobro->usucambioId == "" || $CuentaCobro->usucambioId == "null" || $CuentaCobro->usucambioId == null) {
                            $CuentaCobro->usucambioId = 0;
                        }

                        /* Asigna 0 a usupagoId y usurechazaId si están vacíos o nulos. */
                        if ($CuentaCobro->usupagoId == "" || $CuentaCobro->usupagoId == "null" || $CuentaCobro->usupagoId == null) {
                            $CuentaCobro->usupagoId = 0;
                        }
                        if ($CuentaCobro->usurechazaId == "" || $CuentaCobro->usurechazaId == "null" || $CuentaCobro->usurechazaId == null) {
                            $CuentaCobro->usurechazaId = 0;
                        }

                        /* Asignar fecha actual si `fechaCambio` o `fechaAccion` están vacíos o nulos. */
                        if ($CuentaCobro->fechaCambio == "" || $CuentaCobro->fechaCambio == "0000-00-00 00:00:00" || $CuentaCobro->fechaCambio == null) {
                            $CuentaCobro->fechaCambio = date("Y-m-d H:i:s");
                        }

                        if ($CuentaCobro->fechaAccion == "" || $CuentaCobro->fechaAccion == "0000-00-00 00:00:00" || $CuentaCobro->fechaAccion == null) {
                            $CuentaCobro->fechaAccion = date("Y-m-d H:i:s");
                        }


                        /* Actualiza la cuenta de cobro y confirma la transacción sin errores. */
                        $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();
                        $CuentaCobroMySqlDAO->update($CuentaCobro);
                        $CuentaCobroMySqlDAO->getTransaction()->commit();

                        $response["HasError"] = false;
                        $response["AlertType"] = "success";

                        /* inicializa un arreglo de respuesta con alertas, errores y datos vacíos. */
                        $response["AlertMessage"] = '';
                        $response["ModelErrors"] = [];
                        $response["Data"] = [];
                    } else {
                        /* maneja un error al intentar cambiar un retiro procesado. */


                        $response["HasError"] = true;
                        $response["AlertType"] = "danger";
                        $response["AlertMessage"] = 'No puedes cambiar el estado de un retiro ya procesado.';
                        $response["ModelErrors"] = [];
                        $response["Data"] = [];
                    }
                } else {
                    /* Incrementa el contador de fallos si no se cumple una condición previa. */

                    $CountFaileds = $CountFaileds + 1;
                }
            }

            /*if ($transactions["CantFaileds"]["CantFaileds"]!=0){

                 $CountFaileds=$CountFaileds+$transactions["CantFaileds"]["CantFaileds"];

            }*/
        } else {
            /* intenta realizar un cash out y maneja excepciones específicas. */


            try {
                $WEPAY4USERIVCES = new WEPAY4USERVICES();
                $WEPAY4USERIVCES->cashOut($CuentaCobro);
            } catch (Exception $e) {
                if ($e->getCode() == 100000) {
                    $CountFaileds = $CountFaileds + 1;
                }
            }
        }
    }
}

if ($continueInterbank) {
    /* Se inicializa un servicio para procesar retiros interbancarios si la condición es verdadera. */
    if (true) {
        $INTERBANKSERVICES = new INTERBANKSERVICES();
        $INTERBANKSERVICES->cashOutInterbank($CuentasCobroInterbank, $ProductoId);
    }

    foreach ($CuentasCobroInterbank as $key => $Id2) {
        /* Se crea una instancia de la clase CuentaCobro utilizando un identificador de cuenta específico. */
        $CuentaCobro = new CuentaCobro($CuentasCobroInterbank[$key]->cuentaId);

        if ($CuentaCobro->getEstado() == "P") {

            /* Establece estado y usuario de pago en objeto, gestionando cambio de usuario adecuadamente. */
            $CuentaCobro->setEstado('S');
            $CuentaCobro->setUsupagoId($_SESSION['usuario2']);

            if ($CuentaCobro->usucambioId == "" || $CuentaCobro->usucambioId == "null" || $CuentaCobro->usucambioId == null) {
                $CuentaCobro->usucambioId = 0;
            }

            /* Asigna 0 a usupagoId y usurechazaId si son nulos o vacíos. */
            if ($CuentaCobro->usupagoId == "" || $CuentaCobro->usupagoId == "null" || $CuentaCobro->usupagoId == null) {
                $CuentaCobro->usupagoId = 0;
            }

            if ($CuentaCobro->usurechazaId == "" || $CuentaCobro->usurechazaId == "null" || $CuentaCobro->usurechazaId == null) {
                $CuentaCobro->usurechazaId = 0;
            }

            /* Asigna la fecha actual si las fechas son vacías o inválidas. */
            if ($CuentaCobro->fechaCambio == "" || $CuentaCobro->fechaCambio == "0000-00-00 00:00:00" || $CuentaCobro->fechaCambio == null) {
                $CuentaCobro->fechaCambio = date("Y-m-d H:i:s");
            }

            if ($CuentaCobro->fechaAccion == "" || $CuentaCobro->fechaAccion == "0000-00-00 00:00:00" || $CuentaCobro->fechaAccion == null) {
                $CuentaCobro->fechaAccion = date("Y-m-d H:i:s");
            }

            /* Actualiza una cuenta de cobro y confirma la transacción sin errores. */
            $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();

            $CuentaCobroMySqlDAO->update($CuentaCobro);
            $CuentaCobroMySqlDAO->getTransaction()->commit();

            $response["HasError"] = false;

            /* Código define una estructura de respuesta con éxito, mensaje vacío, errores y datos. */
            $response["AlertType"] = "success";
            $response["AlertMessage"] = '';
            $response["ModelErrors"] = [];
            $response["Data"] = [];
        }

        /* Se crean instancias de usuario, banco, producto y proveedor para manejar datos de pagos. */
        $UsuarioBanco = new UsuarioBanco($CuentaCobro->mediopagoId);
        $Banco = new Banco($UsuarioBanco->bancoId);
        $Producto = new Producto($ProductoId);
        $Proveedor = new Proveedor($Producto->getProveedorId());

        $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();

        /* Actualiza datos de cuenta cobro y obtiene la transacción correspondiente en MySQL. */
        $CuentaCobroMySqlDAO->update($CuentaCobro);
        $CuentaCobroMySqlDAO->getTransaction()->commit();
    }
} //INTERBANK

if ($continueH2hbcp) {
    /* Crea una instancia de H2HBCPSERVICES y ejecuta el método cashOutH2hbcp. */
    if (true) {
        $H2HBCPSERVICES = new H2HBCPSERVICES();
        $H2HBCPSERVICES->cashOutH2hbcp($CuentasCobroH2hbcp, $ProductoId);
    }

    foreach ($CuentasCobroH2hbcp as $key => $Id2) {
        /* Se crea una nueva instancia de la clase CuentaCobro utilizando un ID específico. */
        $CuentaCobro = new CuentaCobro($CuentasCobroH2hbcp[$key]->cuentaId);

        if ($CuentaCobro->getEstado() == "P") {
            /* establece estado y usuario de pago en una cuenta de cobro. */
            $CuentaCobro->setEstado('S');
            $CuentaCobro->setUsupagoId($_SESSION['usuario2']);

            if ($CuentaCobro->usucambioId == "" || $CuentaCobro->usucambioId == "null" || $CuentaCobro->usucambioId == null) {
                $CuentaCobro->usucambioId = 0;
            }

            /* Asigna valor 0 a usupagoId y usurechazaId si están vacíos o nulos. */
            if ($CuentaCobro->usupagoId == "" || $CuentaCobro->usupagoId == "null" || $CuentaCobro->usupagoId == null) {
                $CuentaCobro->usupagoId = 0;
            }

            if ($CuentaCobro->usurechazaId == "" || $CuentaCobro->usurechazaId == "null" || $CuentaCobro->usurechazaId == null) {
                $CuentaCobro->usurechazaId = 0;
            }

            /* Establece fecha actual si fechas específicas están vacías o no son válidas. */
            if ($CuentaCobro->fechaCambio == "" || $CuentaCobro->fechaCambio == "0000-00-00 00:00:00" || $CuentaCobro->fechaCambio == null) {
                $CuentaCobro->fechaCambio = date("Y-m-d H:i:s");
            }

            if ($CuentaCobro->fechaAccion == "" || $CuentaCobro->fechaAccion == "0000-00-00 00:00:00" || $CuentaCobro->fechaAccion == null) {
                $CuentaCobro->fechaAccion = date("Y-m-d H:i:s");
            }

            /* Actualiza una cuenta de cobro y confirma la transacción sin errores. */
            $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();

            $CuentaCobroMySqlDAO->update($CuentaCobro);
            $CuentaCobroMySqlDAO->getTransaction()->commit();

            $response["HasError"] = false;

            /* Código PHP que inicializa una respuesta con tipo de alerta y campos vacíos. */
            $response["AlertType"] = "success";
            $response["AlertMessage"] = '';
            $response["ModelErrors"] = [];
            $response["Data"] = [];
        }

        /* Se instancian objetos relacionados con una cuenta de cobro y sus entidades asociadas. */
        $UsuarioBanco = new UsuarioBanco($CuentaCobro->mediopagoId);
        $Banco = new Banco($UsuarioBanco->bancoId);
        $Producto = new Producto($ProductoId);
        $Proveedor = new Proveedor($Producto->getProveedorId());

        $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();

        /* Actualiza una cuenta de cobro y obtiene la transacción en una base de datos MySQL. */
        $CuentaCobroMySqlDAO->update($CuentaCobro);
        $CuentaCobroMySqlDAO->getTransaction()->commit();
    }
} //H2HBCP

if ($continueMonnet) { //MONNET

    foreach ($CuentasCobroMonnet as $key => $Id2) {

        /* Se crea y configura un objeto CuentaCobro con estado y usuario de pago. */
        $CuentaCobro = new CuentaCobro($CuentasCobroMonnet[$key]->cuentaId);

        $CuentaCobro->setEstado('X');
        $CuentaCobro->setUsupagoId($_SESSION['usuario2']);
        //$CuentaCobro->setFechaPago(date('Y-m-d H:i:s'));

        if ($CuentaCobro->usucambioId == "" || $CuentaCobro->usucambioId == "null" || $CuentaCobro->usucambioId == null) {
            $CuentaCobro->usucambioId = 0;
        }

        /* Asigna 0 a usupagoId y usurechazaId si están vacíos o nulos. */
        if ($CuentaCobro->usupagoId == "" || $CuentaCobro->usupagoId == "null" || $CuentaCobro->usupagoId == null) {
            $CuentaCobro->usupagoId = 0;
        }
        if ($CuentaCobro->usurechazaId == "" || $CuentaCobro->usurechazaId == "null" || $CuentaCobro->usurechazaId == null) {
            $CuentaCobro->usurechazaId = 0;
        }

        /* Asigna la fecha actual a propiedades vacías en el objeto CuentaCobro. */
        if ($CuentaCobro->fechaCambio == "" || $CuentaCobro->fechaCambio == "0000-00-00 00:00:00" || $CuentaCobro->fechaCambio == null) {
            $CuentaCobro->fechaCambio = date("Y-m-d H:i:s");
        }

        if ($CuentaCobro->fechaAccion == "" || $CuentaCobro->fechaAccion == "0000-00-00 00:00:00" || $CuentaCobro->fechaAccion == null) {
            $CuentaCobro->fechaAccion = date("Y-m-d H:i:s");
        }

        /* Asigna la fecha actual a una cuenta y crea instancias de usuario y banco. */
        $CuentaCobro->fechaCambio = date("Y-m-d H:i:s");

        $UsuarioBanco = new UsuarioBanco($CuentaCobro->mediopagoId);
        $Banco = new Banco($UsuarioBanco->bancoId);
        $Usuario = new Usuario($CuentaCobro->usuarioId);

        if ($ProductoId == "") {
            $Producto = new Producto($Banco->productoPago);
        } else {
            /* Asignación de un objeto Producto basado en diferentes condiciones y variables. */
            if ($ProductoId != '') {
                $Producto = new Producto($ProductoId);
            } elseif ($Banco->productoPago != '') {
                $Producto = new Producto($Banco->productoPago);
            } else {
                $Producto = new Producto($ProductoId);
            }
        }

        /* Instancia y configuración de una transacción de producto utilizando datos de una cuenta de cobro. */
        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
        $Transaction = $TransaccionProductoMySqlDAO->getTransaction();
        $valorFinal = $CuentaCobro->getValorAPagar();

        $TransaccionProducto = new TransaccionProducto();
        $TransaccionProducto->setProductoId($Producto->productoId);

        /* Código para configurar atributos de un objeto TransaccionProducto. */
        $TransaccionProducto->setUsuarioId($Usuario->usuarioId);
        $TransaccionProducto->setValor($valorFinal);
        $TransaccionProducto->setEstado('A');
        $TransaccionProducto->setTipo('T');
        $TransaccionProducto->setExternoId(0);
        $TransaccionProducto->setEstadoProducto('E');

        /* establece propiedades y guarda un objeto en la base de datos. */
        $TransaccionProducto->setMandante($Usuario->mandante);
        $TransaccionProducto->setFinalId($CuentaCobro->getCuentaId());
        $TransaccionProducto->setFinalId(0);

        $transproductoId = $TransaccionProductoMySqlDAO->insert($TransaccionProducto);
        $TransaccionProductoMySqlDAO->getTransaction()->commit();

        /* Actualiza la cuenta de cobro y obtiene la transacción en MySQL. */
        $CuentaCobro->setTransproductoId($transproductoId);
        $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();
        $CuentaCobroMySqlDAO->update($CuentaCobro);
        $CuentaCobroMySqlDAO->getTransaction()->commit();
    }
} //MONNET

/* gestiona servicios de pago, actualizando cuentas y manejando excepciones. */
if ($continuePayphone) { //PAYPHONE
    if ($Proveedor->getAbreviado() == "PAYPHONEOUT") {
        try {
            $PAYPHONESERVICES = new PAYPHONESERVICES();
            $PAYPHONESERVICES->cashOut($CuentasCobroPayphone, $Producto);

            foreach ($CuentasCobroPayphone as $CuentaCobros) {
                $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();
                $CuentaCobroMySqlDAO->update($CuentaCobros);
                $CuentaCobroMySqlDAO->getTransaction()->commit();
            }
        } catch (Exception $e) {
            if ($e->getCode() == 21015) {
                $CountFaileds = $CountFaileds + 1;
            }
        }
    }
} //PAYPHONE

/* verifica si no hay errores y define una respuesta exitosa. */
if ($CountFaileds == 0) {

    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = '';
    $response["ModelErrors"] = [];
    $response["Data"] = [];
} elseif ($CountFaileds != 0) {

    /* Muestra un mensaje de error si hay transacciones fallidas al procesar. */
    $response["HasError"] = true;
    $response["AlertType"] = "danger";
    $response["AlertMessage"] = 'No se han procesado ' . $CountFaileds . ' transacciones';
    $response["ModelErrors"] = [];
    $response["Data"] = [];
} elseif (oldCount($Ids) == $CountFaileds) {

    /* Maneja un error y genera una respuesta en caso de fallar las transacciones. */
    $response["HasError"] = true;
    $response["AlertType"] = "danger";
    $response["AlertMessage"] = 'No se pudo realizar las transacciones';
    $response["ModelErrors"] = [];
    $response["Data"] = [];
}
