<?php

use Backend\dto\AuditoriaGeneral;
use Backend\dto\Usuario;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\Clasificador;
use Backend\dto\MandanteDetalle;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\UsuarioTarjetacredito;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\AuditoriaGeneralMySqlDAO;

/**
 * Gestiona la realización de un pgo mediante tarjeta de crédito
 *
 * @param string $json->params->id Id de la tarjeta de crédito
 * @param string $json->params->amount  Monto a pagar
 * @param string $json->params->cvv  Código de seguridad de la tarjeta
 * @param string $json->params->site_id  Id del sitio
 * @param string $json->params->payer  Datos del pagador
 * @param string $player->status_urls  Urls de estado
 * @param string $status_url->cancel  Booleano de cancelación
 * @param string $status_url->fail  Booleano de fallo
 * @param string $status_url->success  Booleano de éxito
 * @param string $json->requestId  Id de la solicitud
 * @param string $json->referenceId  Id de referencia
 *
 * @return array
 *  -code: int Código de respuesta
 *  -rid: string Id de la solicitud
 *  -data: array Datos de respuesta
 *  -result: string Mensaje de respuesta
 */

$response = array(); // Inicializa un array para la respuesta
$response["code"] = 0; // Establece el código de respuesta en 0
$response["rid"] = $json->rid; // Asigna el ID de la solicitud a la respuesta
$response["data"] = array( // Configura el array de datos de respuesta
    "result" => ""
);

/*Obtenición Producto requerido e información del usuario*/
$productId = $json->params->productId;
$UsuarioMandante = new UsuarioMandante($json->session->usuario);
$Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

/*Asignación de parámetros*/
$id = $json->params->id;
$valor = $json->params->amount;
$cvv = $json->params->cvv;
$site_id = $json->params->site_id;
$player = $json->params->payer;
$status_url = $player->status_urls;
$cancel_url = $status_url->cancel;
$fail_url = $status_url->fail;
$success_url = $status_url->success;
$requestId = $json->requestId;
$referenceId = $json->referenceId;

$datos = $json->params;

if ($valor == '' || $valor == false || $valor == null) {
    throw new Exception("Inusual Detected", "100001");
} else {

    if ($id != '' && $id != null) { // Comprueba si el ID no está vacío o nulo

        $UsuarioTarjera = new UsuarioTarjetacredito($id);
        $Proveedor = new Proveedor($UsuarioTarjera->proveedorId);
    }

    $Producto = new Producto("", "Tarjetas", $Proveedor->getProveedorId()); // Crea un nuevo producto con la información del proveedor

    $ConfigurationEnvironment = new ConfigurationEnvironment();
    if ($ConfigurationEnvironment->isProduction() && $Proveedor->getAbreviado() == 'SAGICOR') {
        $Producto = new Producto(10312);
    }


    try {
        // Se inicializa un nuevo objeto de la clase Clasificador
        $Clasificador = new Clasificador("", "ACCVERIFFORDEPOSIT");
        $minimoMontoPremios = 0;

        // Se inicializa un nuevo objeto de la clase MandanteDetalle con los parámetros requeridos
        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');

        // Se verifica el valor del MandanteDetalle
        if ($MandanteDetalle->getValor() == '1') {
            // Se comprueba si las cédulas están verificadas, en caso contrario se lanza una excepción
            if ($Usuario->verifcedulaPost != "S" || $Usuario->verifcedulaAnt != 'S') {
                throw new Exception("La cuenta necesita estar verificada para poder depositar", "21006");
            }
        }
    } catch (Exception $e) {
        // Se maneja la excepción solo si el código de error no es 34 ni 41
        if ($e->getCode() != 34 && $e->getCode() != 41) {
            throw $e;
        }
    }



    /**
     * Verifica el estado de la contingencia para depósitos en pasarelas de pago.
     *
     * Este bloque de código intenta obtener el valor de la contingencia para depósitos
     * en pasarelas de pago del usuario. Si ocurre una excepción, se establece el valor
     * de la contingencia como inactivo ("I").
     *
     * en caso de haber una contingencia se deja el registro del intento fallido del jugador
     *
     */

    try {
        $Clasificador = new Clasificador('', 'PAYMENTGATEWAYCONTINGENCY');
        $UsuarioConfiguracion = new UsuarioConfiguracion($Usuario->usuarioId, 'A', $Clasificador->getClasificadorId());
        $Contingencia = $UsuarioConfiguracion->getValor();

    } catch (Exception $e) {
        $Contingencia = "I";
    }

    if ($Contingencia == "A") {


        $AuditoriaGeneral = new AuditoriaGeneral();
        $AuditoriaGeneral->setUsuarioId($Usuario->usuarioId);
        $AuditoriaGeneral->setUsuarioIp("");
        $AuditoriaGeneral->setUsuariosolicitaId($Usuario->usuarioId);
        $AuditoriaGeneral->setUsuariosolicitaIp("");
        $AuditoriaGeneral->setUsuarioaprobarIp(0);
        $AuditoriaGeneral->setTipo("FALLOENDEPOSITOPASARELA");
        $AuditoriaGeneral->setValorAntes(0);
        $AuditoriaGeneral->setValorDespues(0);
        $AuditoriaGeneral->setUsucreaId($Usuario->usuarioId);
        $AuditoriaGeneral->setUsumodifId(0);
        $AuditoriaGeneral->setEstado("A");
        $AuditoriaGeneral->setDispositivo(0);
        $AuditoriaGeneral->setObservacion('intento fallido por pasarela para depositar ');

        $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO();
        $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);
        $AuditoriaGeneralMySqlDAO->getTransaction()->commit();

        // Si la contingencia está activa, lanzamos una excepción indicando que no se pueden realizar depósitos.
        throw new Exception("Actualmente no puedes realizar depósitos por este medio. Por favor, comunícate con soporte.", "300167");
    }

    /*El código verifica si el usuario tiene una contingencia de depósito y lanza una excepción si es así. Luego, obtiene la latitud y longitud
    de los parámetros JSON y las concatena en una variable de ubicación.*/
    if ($Usuario->contingenciaDeposito == 'A') {

        throw new Exception("El usuario ingresado esta autoexcluido. ", "20027");
    }



    try {
        //Verifica si el partner presenta una contingencia total de depoósito
        $Clasificador = new Clasificador('', 'TOTALCONTINGENCEDEPOSIT');
        $MandanteDetalle = new MandanteDetalle('', '-1', $Clasificador->getClasificadorId(), '0', 'A');

        if ($MandanteDetalle->getValor() == 1) throw new Exception('We are currently in the process of maintaining the site.', 30004);
    } catch (Exception $ex) {
        if ($ex->getCode() == 30004) throw $ex;
    }

    try {
        //Verifica exclusión específica al usuario
        $tipo = "EXCTIMEOUT";
        $Tipo = new Clasificador("", $tipo);
        $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioMandante->getUsuarioMandante(), 'A', $Tipo->getClasificadorId());

        if (strtotime($UsuarioConfiguracion->getValor()) > (time())) {
            throw new Exception("El usuario ingresado esta autoexcluido. ", "20027");
        }
    } catch (Exception $e) {
        if ($e->getCode() != '46') {
            throw $e;
        }
    }
// validacion de vertical de deposito


    /*El código verifica si es posible realizar un depósito en el sitio en función de la configuración del usuario y el mandante. Si no es posible,
    lanza una excepción con un mensaje específico.*/
    try {
        $Clasificador = new Clasificador("", "DEPOSITUSUONLINE");

        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');

        if ($MandanteDetalle->valor == "A") {
            throw new Exception("Imposible depositar en este momento en el sitio", "300007");
        }
    } catch (Exception $e) {
        if ($e->getCode() != 34 && $e->getCode() != 41) {
            throw $e;
        }
    }


    // Se verifica si el mandante del usuario es igual a '2'
    if ($Usuario->mandante == '2') {

        // Se comprueba si el valor para depositar es mayor a 25000 y se lanza una excepción si es así
        if ($valor > 25000) {
            throw new Exception("Valor mayor al máximo permitido para depositar", '21009');
        }

        // Se inicializa un nuevo objeto de la clase UsuarioConfiguracion
        $UsuarioConfiguracion = new UsuarioConfiguracion();

        // Se establece el ID del usuario en la configuración de usuario
        $UsuarioConfiguracion->setUsuarioId($UsuarioMandante->getUsuarioMandante());

        // Se verifica los límites de depósito para el valor ingresado
        $result = $UsuarioConfiguracion->verifyLimitesDeposito($valor);

        // Si el resultado no es '0', se lanza una excepción con el mensaje correspondiente
        if ($result != '0') {
            throw new Exception("Limite de deposito", $result);
        }
    }
    switch ($Proveedor->getAbreviado()) {

        /*El código maneja un caso de pago con el proveedor PAGADITO, crea una solicitud de pago utilizando PAGADITOSERVICES y asigna el resultado a la variable $data.*/
        case 'PAGADITO':

            $PAGADITOSERVICES = new Backend\integrations\payment\PAGADITOSERVICES();

            $data = $PAGADITOSERVICES->createRequestPayment($Usuario, $Producto, $valor, $UsuarioTarjera, $requestId, $referenceId);

            break;

        /*El código maneja un caso de pago con el proveedor VISANET, crea una solicitud de pago utilizando VISANETSERVICES y asigna el resultado a la variable $data.*/
        case 'VISANET':

            $VISANETSERVICES = new Backend\integrations\payment\VISANETSERVICES();

            $data = $VISANETSERVICES->createRequestPayment($Usuario, $Producto, $valor, $UsuarioTarjera, $requestId, $referenceId, $cvv);

            break;

        /*Caso de pago del proveedor GREENPAY*/
        case 'GREENPAY':

            $GREENPAYSERVICES = new Backend\integrations\payment\GREENPAYSERVICES();

            $data = $GREENPAYSERVICES->createRequestPaymentCard($Usuario, $Producto, $valor, $UsuarioTarjera, $requestId, $referenceId);

            break;

        /*Caso de pago del proveedor PAYGATE*/
        case 'PAYGATE':
            if ($Usuario->verifcedulaPost != "S" || $Usuario->verifcedulaAnt != 'S') {
                throw new Exception("La cuenta necesita estar verificada para poder depositar", "21006");
            }

            $PAYGATESERVICES = new Backend\integrations\payment\PAYGATESERVICES();

            $data = $PAYGATESERVICES->createRequestPayment($Usuario, $Producto, $valor, $UsuarioTarjera, $requestId, $referenceId, $cvv);

            break;
        /*Caso de pago del proveedor N1CO*/
        case 'N1CO':

            $N1COSERVICES = new Backend\integrations\payment\N1COSERVICES();

            $data = $N1COSERVICES->createRequestPayment($Usuario, $Producto, $valor);

            break;
        /*Caso de pago del proveedor PAYMENTEZ*/
        case 'PAYMENTEZ':

            $PAYMENTEZSERVICES = new Backend\integrations\payment\PAYMENTEZSERVICES();

            $data = $PAYMENTEZSERVICES->createRequestPayment2($Usuario, $Producto, $valor, $id, $cvv);

            break;

        /*Caso de pago del proveedor SAGICOR*/
        case 'SAGICOR':

            $SAGICORSERVICES = new Backend\integrations\payment\SAGICORSERVICES();

            $data = $SAGICORSERVICES->createRequestPayment3($Usuario, $Producto, $Proveedor->getProveedorId(), $datos);

            break;

        /*Caso de pago del proveedor NUVEI*/
        case 'NUVEI':

            $NUVEISERVICES = new Backend\integrations\payment\NUVEISERVICES();

            $data = $NUVEISERVICES->createRequestPayment($Usuario, $Producto, $valor, $success_url, $fail_url, $cancel_url, $cvv);

            break;

        /*Caso de pago del proveedor INSWITCH*/
        case 'INSWITCH':
            $INSWITCSERVICES = new \Backend\Integrations\payment\INSWITCHSERVICES();

            break;
    }
}

/*El código verifica si la variable $data->success es verdadera. Si es así, inicializa un array de respuesta con un código de éxito y asigna el mensaje de
 $data a los campos data y result. También incluye el rid de la solicitud JSON. Si $data->success es falso, se ejecuta el bloque else.*/
if ($data->success == true) {
    $response = array();
    $response["code"] = 0;
    $response["data"] = $data->Message;
    $response["result"] = $data->Message;
    $response["rid"] = $json->rid;
} else {
    /*El código maneja la respuesta de una solicitud de pago con tarjeta de crédito, estableciendo un código de error y un mensaje en caso de fallo.*/
    $response = array();
    $response["code"] = 1;
    $response["data"] = $data->Message;
    $response["rid"] = $json->rid;
}
