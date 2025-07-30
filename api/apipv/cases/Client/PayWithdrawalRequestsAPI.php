<?php

use Backend\dto\Banco;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\CuentaCobro;
use Backend\dto\UsuarioBanco;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\integrations\payout\LPGSERVICES;
use Backend\integrations\payout\ALPSSERVICES;
use Backend\integrations\payout\PAYKUSERVICES;
use Backend\integrations\payout\TUPAYSERVICES;
use Backend\integrations\payout\ANINDASERVICES;
use Backend\integrations\payout\KASHIOSERVICES;
use Backend\integrations\payment\MONNETSERVICES;
use Backend\integrations\payout\CONEKTASERVICES;
use Backend\integrations\payout\EUKAPAYSERVICES;
use Backend\integrations\payout\EZZEPAYSERVICES;
use Backend\integrations\payout\WEPAY4USERVICES;
use Backend\integrations\payout\GLOBOKASSERVICES;
use Backend\integrations\payout\INSWITCHSERVICES;
use Backend\integrations\payout\MEGAPAYZSERVICES;
use Backend\integrations\payout\PAYPHONESERVICES;
use Backend\integrations\payout\STARPAGOSERVICES;
use Backend\integrations\payout\COINSPAIDSERVICES;
use Backend\integrations\payout\DIRECTA24SERVICES;
use Backend\integrations\payout\R4CONECTASERVICES;
use Backend\integrations\payout\PAYBROKERSSERVICES;
use Backend\integrations\payout\PUSHPAYMENTSERVICES;
use Backend\integrations\payout\PAYRETAILERSSERVICES;
use Backend\integrations\payment\ASTROPAYCARDSERVICES;

/**
 * Client/PayWithdrawalRequestsAPI
 *
 * Este script procesa solicitudes de retiro mediante la API.
 *
 * @param object $params Objeto que contiene los siguientes valores:
 * @param int $params ->Id Identificador de la solicitud de retiro.
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

/* Asigna valores de parámetros a variables, verificando la existencia de "ProductoId". */
$Id = $params->Id;
$ProductoId = $params->ProductoId;

if ($ProductoId == '') {
    $ProductoId = $params->ProductId;
}

/* Se crea una nueva instancia de la clase CuentaCobro usando un identificador específico. */
$CuentaCobro = new CuentaCobro($Id);

if ($CuentaCobro->getEstado() == "P" && $CuentaCobro->cuentaId == "1398255") {

    /* Se establece el estado de cuenta y se asigna un usuario de pago. */
    $CuentaCobro->setEstado('S');
    $CuentaCobro->setUsupagoId($_SESSION['usuario2']);
    //$CuentaCobro->setFechaPago(date('Y-m-d H:i:s'));

    if ($CuentaCobro->usucambioId == "" || $CuentaCobro->usucambioId == "null" || $CuentaCobro->usucambioId == null) {
        $CuentaCobro->usucambioId = 0;
    }

    /* Inicializa valores a 0 si son nulos o vacíos en CuentaCobro. */
    if ($CuentaCobro->usupagoId == "" || $CuentaCobro->usupagoId == "null" || $CuentaCobro->usupagoId == null) {
        $CuentaCobro->usupagoId = 0;
    }

    if ($CuentaCobro->usurechazaId == "" || $CuentaCobro->usurechazaId == "null" || $CuentaCobro->usurechazaId == null) {
        $CuentaCobro->usurechazaId = 0;
    }

    /* Asigna la fecha actual a campos vacíos o nulos de un objeto "CuentaCobro". */
    if ($CuentaCobro->fechaCambio == "" || $CuentaCobro->fechaCambio == "0000-00-00 00:00:00" || $CuentaCobro->fechaCambio == null) {
        $CuentaCobro->fechaCambio = date("Y-m-d H:i:s");
    }

    if ($CuentaCobro->fechaAccion == "" || $CuentaCobro->fechaAccion == "0000-00-00 00:00:00" || $CuentaCobro->fechaAccion == null) {
        $CuentaCobro->fechaAccion = date("Y-m-d H:i:s");
    }

    /* Se crea un usuario y banco, luego se procesan pagos específicos para una cuenta. */
    $UsuarioBanco = new UsuarioBanco($CuentaCobro->mediopagoId);
    $Banco = new Banco($UsuarioBanco->bancoId);

    if ($CuentaCobro->cuentaId == "1398255") {
        $MONNETSERVICES = new MONNETSERVICES();
        $MONNETSERVICES->cashOut($CuentaCobro, $ProductoId);
    }

    /* Actualiza una cuenta de cobro en MySQL y confirma la transacción exitosamente. */
    $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();
    $CuentaCobroMySqlDAO->update($CuentaCobro);
    $CuentaCobroMySqlDAO->getTransaction()->commit();

    $response["HasError"] = false;
    $response["AlertType"] = "success";

    /* Se inicializan variables para manejar mensajes de alerta, errores y datos en una respuesta. */
    $response["AlertMessage"] = '';
    $response["ModelErrors"] = [];
    $response["Data"] = [];
} elseif ($CuentaCobro->getEstado() == "P") {

    /* Actualiza el estado de la cuenta y asigna un usuario de pago. */
    $CuentaCobro->setEstado('S');
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

    /* Asigna la fecha actual a propiedades vacías o nulas de $CuentaCobro. */
    if ($CuentaCobro->fechaCambio == "" || $CuentaCobro->fechaCambio == "0000-00-00 00:00:00" || $CuentaCobro->fechaCambio == null) {
        $CuentaCobro->fechaCambio = date("Y-m-d H:i:s");
    }

    if ($CuentaCobro->fechaAccion == "" || $CuentaCobro->fechaAccion == "0000-00-00 00:00:00" || $CuentaCobro->fechaAccion == null) {
        $CuentaCobro->fechaAccion = date("Y-m-d H:i:s");
    }

    /* Condicional que verifica el medio de pago y crea objetos de Producto y Proveedor. */
    if ($CuentaCobro->mediopagoId == 2088007) {
        // $Banco = new Banco($UsuarioBanco->bancoId);
        $Producto = new Producto($ProductoId);
        $Proveedor = new Proveedor($Producto->getProveedorId());
    } else {
        /* Controla condiciones para instanciar objetos basado en propiedades de $CuentaCobro. */
        if ($CuentaCobro->productoPagoId != "0" && $CuentaCobro->productoPagoId != "") {
        } else {
            if ($CuentaCobro->cuentaId != '6549157') {
                $UsuarioBanco = new UsuarioBanco($CuentaCobro->mediopagoId);
                $Banco = new Banco($UsuarioBanco->bancoId);
            }
        }
    }

    /* Crea un producto, verifica proveedor y realiza un pago si cumple condición. */
    $Producto = new Producto($ProductoId);
    $Proveedor = new Proveedor($Producto->getProveedorId());

    if ($Proveedor->getAbreviado() == "LPGCO") {
        $LPGSERVICES = new LPGSERVICES();
        $LPGSERVICES->cashOut($CuentaCobro);
    }

    /* verifica proveedores y realiza transacciones según su abreviatura. */
    if ($Proveedor->getAbreviado() == "WEPAY4U") {
        $WEPAY4USERIVCES = new WEPAY4USERVICES();
        $WEPAY4USERIVCES->cashOut($CuentaCobro, $ProductoId);
    }

    if ($Proveedor->getAbreviado() == "ASTPAYCPAY") {
        $ASTROPAYCARDSERVICES = new ASTROPAYCARDSERVICES();
        $ASTROPAYCARDSERVICES->cashOut($CuentaCobro);
    }

    /* Condicional que ejecuta un servicio de retiro si el proveedor es COINSPAIDOUT. */
    if ($Proveedor->getAbreviado() == "COINSPAIDOUT") {
        $COINSPAIDSERVICES = new COINSPAIDSERVICES();
        $COINSPAIDSERVICES->cashOut($CuentaCobro, $Producto);
    }

    /* gestiona retiros según el proveedor especificado. */
    if ($Proveedor->getAbreviado() == "INSWITCHOUT") {
        $INSWITCHSERVICES = new INSWITCHSERVICES();
        $INSWITCHSERVICES->cashOut($CuentaCobro, $Producto);
    }

    if ($Proveedor->getAbreviado() == "GLOBOKASRETIROS") {
        $WEPAY4USERIVCES = new GLOBOKASSERVICES();
        $WEPAY4USERIVCES->cashOut($CuentaCobro, $ProductoId);
    }

    /* realiza pagos según el proveedor seleccionado. */
    if ($Proveedor->getAbreviado() == "MONNETPAY") {
        $MONNETSERVICES = new MONNETSERVICES();
        $MONNETSERVICES->cashOut($CuentaCobro, $ProductoId);
    }



    if ($Proveedor->getAbreviado() == "PBROKERSPA") {
        $PAYBROKERSSERVICES = new PAYBROKERSSERVICES();
        $PAYBROKERSSERVICES->cashOut($CuentaCobro, $ProductoId);
    }

    /* ejecuta un servicio de retiro si el proveedor es EZZEPAY. */
    if ($Proveedor->getAbreviado() == "EZZEPAY") {
        $EZZEPAYSERVICES = new EZZEPAYSERVICES();
        $EZZEPAYSERVICES->cashOut($CuentaCobro, $ProductoId);
    }

    if ($Proveedor->getAbreviado() == "TUPAYOUT") {
        $TUPAYSERVICES = new TUPAYSERVICES();
        $TUPAYSERVICES->cashOut($CuentaCobro, $ProductoId);
    }
    
    /* evalúa si el proveedor es "ALPSPAYOUT" y ejecuta un servicio. */
    if ($Proveedor->getAbreviado() == "ALPSPAYOUT") {
        $ALPSSERVICES = new ALPSSERVICES();
        $ALPSSERVICES->cashOut($CuentaCobro, $Producto);
    }

    /* evalúa si el proveedor es "PUSHPAYMENT" y ejecuta un servicio. */
    if ($Proveedor->getAbreviado() == "PUSHPAYMENT") {
        $PUSHPAYMENTSERVICES = new PUSHPAYMENTSERVICES();
        $PUSHPAYMENTSERVICES->cashOut($CuentaCobro, $ProductoId);
    }

    /* Verifica proveedor y ejecuta un servicio de retiro de efectivo si coincide. */
    if ($Proveedor->getAbreviado() == "PAYKUPAY") {
        $PAYKUSERVICES = new PAYKUSERVICES();
        $PAYKUSERVICES->cashOut($CuentaCobro, $ProductoId);
    }

    /* Condiciona un pago cuando el proveedor es "P4FPAYOUT" utilizando PAYKUSERVICES. */
    if ($Proveedor->getAbreviado() == "P4FPAYOUT") {
        $PAYKUSERVICES = new PAYKUSERVICES();
        $PAYKUSERVICES->cashOut($CuentaCobro);
    }

    /* Condicionales para realizar retiros según el proveedor especificado. */
    if ($Proveedor->getAbreviado() == "CONEKTARETIROS") {
        $CONEKTASERVICES = new CONEKTASERVICES();
        $CONEKTASERVICES->cashOut($CuentaCobro);
    }

    if ($Proveedor->getAbreviado() == "COINSPAIDOUT") {
        $COINSPAIDSERVICES = new COINSPAIDSERVICES();
        $COINSPAIDSERVICES->cashOut($CuentaCobro, $Producto);
    }

    /* Realiza un retiro de fondos si el proveedor es DIRECTA24PAY. */
    if ($Proveedor->getAbreviado() == "DIRECTA24PAY") {
        $DIRECTA24SERVICES = new DIRECTA24SERVICES();
        $DIRECTA24SERVICES->cashOut($CuentaCobro, $Producto);
    }

    /* gestiona retiros de dinero según diferentes proveedores. */
    if ($Proveedor->getAbreviado() == "KASHIOOUT") {
        $KASHIOSERVICES = new KASHIOSERVICES();
        $KASHIOSERVICES->cashOut($CuentaCobro, $Producto);
    }

    if ($Proveedor->getAbreviado() == "R4CONECTAOUT") {
        $R4CONECTASERVICES = new R4CONECTASERVICES();
        $R4CONECTASERVICES->cashOut($CuentaCobro, $Producto);
    }

    if ($Proveedor->getAbreviado() == "EUKAPAYOUT") {
        $EUKAPAYSERVICES = new EUKAPAYSERVICES();
        $EUKAPAYSERVICES->cashOut($CuentaCobro, $Producto);
    }

    /* ejecuta servicios de retiro de efectivo según el proveedor especificado. */
    if ($Proveedor->getAbreviado() == "STARPAGOOUT") {
        $STARPAGOSERVICES = new STARPAGOSERVICES();
        $STARPAGOSERVICES->cashOut($CuentaCobro, $Producto);
    }

    if ($Proveedor->getAbreviado() == "PAYPHONEOUT") {
        $PAYPHONESERVICES = new PAYPHONESERVICES();
        $PAYPHONESERVICES->cashOut($CuentaCobro, $Producto);
    }

    if ($Proveedor->getAbreviado() == "PAYRETAILERSOUT") {
        $PAYRETAILERSSERVICES = new PAYRETAILERSSERVICES();
        $PAYRETAILERSSERVICES->cashOut($CuentaCobro, $Producto);
    }

    /* gestiona retiros de dinero según diferentes proveedores. */
    if ($Proveedor->getAbreviado() == "MEGAPAYZOUT") {
        $MEGAPAYZSERVICES = new MEGAPAYZSERVICES();
        $MEGAPAYZSERVICES->cashOut($CuentaCobro, $Producto);
    }

    /* gestiona retiros de dinero según diferentes proveedores. */
    if ($Proveedor->getAbreviado() == "ANINDAOUT") {
        $ANINDASERVICES = new ANINDASERVICES();
        $ANINDASERVICES->cashOut($CuentaCobro, $Producto);
    }

    /* Actualiza una cuenta de cobro y confirma la transacción sin errores. */
    $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();
    $CuentaCobroMySqlDAO->update($CuentaCobro);
    $CuentaCobroMySqlDAO->getTransaction()->commit();

    $response["HasError"] = false;
    $response["AlertType"] = "success";

    /* Inicializa un array de respuesta con mensajes y datos vacíos. */
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
