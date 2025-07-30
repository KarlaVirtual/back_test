<?php

use Backend\dto\Usuario;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\Proveedor;
use Backend\dto\Automation;
use Backend\dto\Clasificador;
use Backend\dto\CuentaAsociada;
use Backend\dto\UsuarioRecarga;
use Backend\dto\MandanteDetalle;
use Backend\dto\UsuarioMandante;
use Backend\dto\AuditoriaGeneral;
use Backend\dto\ProductoMandante;
use Backend\dto\UsuarioAutomation;
use Backend\dto\FranquiciaProducto;
use Backend\dto\UsuarioInformacion;
use Backend\dto\TransaccionProducto;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\AuditoriaGeneralMySqlDAO;
use Backend\mysql\UsuarioAutomationMySqlDAO;
use Backend\dto\UsuarioBanco;
use Backend\dto\BancoDetalle;

/**
 * Recurso encargado de recibir, generar y canalizar las peticiones de depósitos requeridas desde la plataforma de usuarios online
 * @param int $json->params->amount Monto a depositar
 * @param string $json->params->currency Moneda del monto a depositar
 * @param string $json->params->service Servicio de pago a utilizar
 * @param int $json->params->bank_account_id ID de la cuenta bancaria del usuario
 * @param object $json->params->payer Información del jugador que realiza el depósito
 * @param object $json->params->status_urls URLs de estado para el depósito (cancel, fail, success)
 * @param string $json->params->operation_number Número de operación del depósito
 * @param object $json->params->response Datos de respuesta del depósito
 * @param string $json->params->cancel_url URL de cancelación del depósito
 * @param string $json->params->fail_url URL de fallo del depósito
 * @param string $json->params->success_url URL de éxito del depósito
 *
 * @return $response:
 *  - code int Código de respuesta
 *  - rid string ID de la petición
 *  - data array Datos del depósito
 *      -result object Resultado del depósito
 *       -details object Detalles del depósito
 *       -action string URL de acción del depósito
 *       -method string Método de pago
 *       -fields array Campos del depósito
 *
 */

// Obtiene el monto y la moneda a partir de los parámetros JSON
$amount = $json->params->amount;
$currency = $json->params->currency;

// Obtiene el servicio, el jugador y las URLs de estado a partir de los parámetros JSON
$service = $json->params->service;
$franchise = isset($json->params->franchise) ? $json->params->franchise : null; // Si viene por la nueva visual de franquicias
$bank_account_id = $json->params->bank_account_id;
$player = $json->params->payer;
$status_url = $player->status_urls;
$cancel_url = $status_url->cancel;
$fail_url = $status_url->fail;
$success_url = $status_url->success;
$operation_number = $json->params->operation_number;
$response_data = $json->params->response;

/*Sanitización de parámetros*/
if (!empty($bank_account_id) && !is_numeric($bank_account_id)) {
    throw new Exception('Parámetros invalidos', 300023);
}

// Crea una instancia de UsuarioMandante con el usuario de la sesión
$UsuarioMandante = new UsuarioMandante($json->session->usuario);
$Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

// Flujo para el modelo de franquicias, este selecciona un id del producto
if ($franchise == true){

    if($service == '4' && $Usuario->paisId == 173){
        if($amount<60){
            $service='9313';
        }else{
            $service='29942';
        }
    }else {
        //service será el id de la franquicia toca obtener el id del producto que estan manejando y devolverlo por service
        // Inicializamos un array de reglas
        $rules = [];
        array_push($rules, array("field" => "producto.estado", "data" => "A", "op" => "eq"));
        array_push($rules, array("field" => "proveedor.estado", "data" => "A", "op" => "eq"));
        array_push($rules, array("field" => "producto_mandante.estado", "data" => "A", "op" => "eq"));
        array_push($rules, array("field" => "producto_mandante.mandante", "data" => "$Usuario->mandante", "op" => "eq"));
        array_push($rules, array("field" => "proveedor.tipo", "data" => "PAYMENT", "op" => "eq"));
        array_push($rules, array("field" => "franquicia.estado", "data" => "A", "op" => "eq"));
        array_push($rules, array("field" => "franquicia_producto.estado", "data" => "A", "op" => "eq"));
        array_push($rules, array("field" => "franquicia_producto.franquicia_id", "data" => "$service", "op" => "eq"));


// Verificación basada en el parámetro de prueba del usuario
        if ($Usuario->test != 'S') {
            // Agregamos una regla adicional si el test no es 'S'
            array_push($rules, array("field" => "producto_mandante.filtro_pais", "data" => "I", "op" => "eq"));
        }

// Se define un filtro en forma de array con las reglas y la operación de agrupación.
        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json2 = json_encode($filtro);
        // Se crea una instancia de ProductoMandante
        $FranquiciaProducto = new FranquiciaProducto();
        $MaxRows = 1000;
        $OrderedItem = 1;
        $SkeepRows = 0;
// Se recuperan los productos del mandante y país utilizando el filtro JSON
//Obtenemos los Franquicias que ya han sido asignados a un partner y pais y los productos que ya han sido asignados a esta Franquicia
        $FranquiciasProductos = $FranquiciaProducto->getFranquiciasMandanteProductosCustom(" franquicia_mandante_pais.*,mandante.*,franquicia.*,proveedor.*,producto.*,franquicia_producto.*, proveedor.abreviado,proveedor.descripcion,proveedor.proveedor_id,proveedor.imagen,producto.producto_id , producto.descripcion,producto_mandante.extra_info,producto_mandante.valor ,CASE producto_mandante.filtro_pais WHEN 'A' THEN producto_mandante.min ELSE producto_mandante.min END min,CASE 'A' WHEN 'A' THEN producto_mandante.max ELSE producto_mandante.max END max,CASE 'A' WHEN 'A' THEN producto_mandante.tiempo_procesamiento ELSE producto_mandante.tiempo_procesamiento END tiempo, producto.image_url imagen,producto.orden,producto_mandante.min,producto_mandante.max ,producto_mandante.image_url,producto_mandante.image_url2", "producto.orden", "DESC,producto.descripcion", $SkeepRows, $MaxRows, $json2, true, $Usuario->mandante, $Usuario->paisId);

        $FranquiciasProductos = json_decode($FranquiciasProductos);

        if (!isset($FranquiciasProductos->count[0]->{'.count'}) || intval($FranquiciasProductos->count[0]->{'.count'}) === 0) {
            throw new Exception("No existen productos enlazados a la franquicia", "300161");
        }

        foreach ($FranquiciasProductos->data as $key => $value) {
            /*Definición e iteración para objetos de respuesta */

            $service = ($value->{"producto.producto_id"});//Producto enlazada a la franquicia
        }
    }
}


// Obtiene la campaña de UTM desde los parámetros JSON
$vs_utm_campaign = $json->params->vs_utm_campaign;

if ($vs_utm_campaign != '' && $vs_utm_campaign != 'undefined' && explode('_', $vs_utm_campaign)[1] != 'undefined') {
    $_REQUEST['vs_utm_campaign'] = $vs_utm_campaign;
}
$vs_utm_campaign2 = $json->params->vs_utm_campaign2;

if ($vs_utm_campaign2 != '' && $vs_utm_campaign2 != 'undefined' && explode('_', $vs_utm_campaign2)[1] != 'undefined') {
    $_REQUEST['vs_utm_campaign'] = $vs_utm_campaign2;
}
$vs_utm_campaign = $json->params->vs_utm_campaign;

if (strpos($vs_utm_campaign, 'ADFORM') !== false) {
    $_REQUEST['vs_utm_campaign'] = $vs_utm_campaign;
}

$vs_utm_content = $json->params->vs_utm_content;

// Verifica si el contenido de la UTM es válido y si contiene 'afclick', lo establece en la solicitud
if ($vs_utm_content != '' && $vs_utm_content != 'undefined' && explode('_', $vs_utm_content)[1] != 'undefined') {
    if (strpos($vs_utm_content, 'afclick') !== false) {
        $_REQUEST['vs_utm_campaign'] = $vs_utm_content;
    }
}

if ($amount == '' || $amount == false || $amount == null) {
    throw new Exception("Producto no disponible2", '21010');
} else {

    try {
        // Verifica si la campaña de UTM contiene '_FFBB_' en el parámetro vs_utm_campaign
        if (strpos($json->params->vs_utm_campaign, '_FFBB_') !== false) {
            $json->params->vs_utm_campaignFacebook = explode('_FFBB_', $json->params->vs_utm_campaign)[1];
            $json->params->vs_utm_campaign = explode('_FFBB_', $json->params->vs_utm_campaign)[0];

            // Asigna los valores separados a la variable de solicitud
            $_REQUEST['vs_utm_campaignFacebook'] = $json->params->vs_utm_campaignFacebook;
            $_REQUEST['vs_utm_campaign'] = $json->params->vs_utm_campaign;
        }

        // Verifica si la campaña de UTM contiene '_FFBB_' en el parámetro vs_utm_campaign2
        if (strpos($json->params->vs_utm_campaign2, '_FFBB_') !== false) {
            $json->params->vs_utm_campaignFacebook2 = explode('_FFBB_', $json->params->vs_utm_campaign2)[1];
            $json->params->vs_utm_campaign2 = explode('_FFBB_', $json->params->vs_utm_campaign2)[0];
            $_REQUEST['vs_utm_campaignFacebook2'] = $json->params->vs_utm_campaignFacebook2;
            $_REQUEST['vs_utm_campaign'] .= '_FFBB_' . $json->params->vs_utm_campaignFacebook2;
        }
    } catch (Exception $e) {
        // Manejo de excepciones vacío
    }

    // Obtiene el identificador de riesgo
    $riskId = $json->params->riskId;

    $stateRisk = 0;

    // Crea una instancia de UsuarioMandante con el usuario de la sesión
    $UsuarioMandante = new UsuarioMandante($json->session->usuario);
    $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
    //$Pais = new Pais($Usuario->paisId);
    try {
        // Crea una instancia de Clasificador con un tipo específico
        $Clasificador = new Clasificador("", "LIMITPERCLIENTTEST");
        $ClasificadorId = $Clasificador->getClasificadorId();
        $ProductoId = 4;

        // Crea una configuración de usuario específica
        $UsuarioConfiguracion = new UsuarioConfiguracion($Usuario->usuarioId, "A", $ClasificadorId, $ProductoId, "");

        $Valor = $UsuarioConfiguracion->valor;

        if (floatval($UsuarioConfiguracion->getValor()) > 0 && $amount > $Valor) {
            throw new Exception("Monto superior al indicado para el usuario de pruebas", 300018);
        }
    } catch (Exception $e) {
        // Si la excepción capturada tiene el código 300018, la vuelve a lanzar
        if ($e->getCode() == 300018) {

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


    try {
        /**
         * Se inicializa una instancia de la clase Clasificador con el tipo "LIMITEDEPOSITOSIMPLE".
         * Obtiene el ID del clasificador correspondiente.
         */
        $Clasificador = new Clasificador("", "LIMITEDEPOSITOSIMPLE");
        $ClasificadorId = $Clasificador->getClasificadorId();
        $UsuarioConfiguracion = new UsuarioConfiguracion($Usuario->usuarioId, "A", $ClasificadorId, '', '');
        $limitDeposit = $UsuarioConfiguracion->getValor();
        $FromDateLocal = $UsuarioConfiguracion->getFechaCrea();
        $ToDateLocal = $UsuarioConfiguracion->getFechaFin();
        $rules = [];
        array_push($rules, array("field" => "usuario_recarga.usuario_id", "data" => $Usuario->usuarioId, "op" => "eq"));
        array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => $FromDateLocal, "op" => "ge"));
        array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => $ToDateLocal, "op" => "le"));
        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);
        $select = " SUM(usuario_recarga.valor) valor ";
        $UsuarioRecarga = new UsuarioRecarga();
        $transacciones = $UsuarioRecarga->getUsuarioRecargasCustom($select, "usuario_recarga.usuario_id", "asc", 0, 1, $json, true);
        $transacciones = json_decode($transacciones);

        /**
         * Se verifica si la suma de las transacciones existentes más el nuevo monto excede el límite de depósito.
         * Si es así, se lanza una excepción.
         */
        if ((floatval($transacciones->data[0]->{'.valor'})  + $amount) > floatval($limitDeposit)) {
            throw new Exception("Limite de deposito.", '20008');
        }
    } catch (Exception $e) {
        if ($e->getCode() == 20008) {
            throw $e;
        }
    }



    if ($UsuarioMandante->mandante == 0 || $UsuarioMandante->mandante == 10 || $UsuarioMandante->mandante == 18) {

        try {
            $arrayExcProduct = array(3, 2, 1, 0);
            $Clasificador = new Clasificador("", "EXCPRODUCT");
            // Recorre cada tipo de producto en el array
            foreach ($arrayExcProduct as $tipoProducto) {
                if ($tipoProducto != '') {
                    try {
                        // Crea una configuracion de usuario para el mandante
                        $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioMandante->getUsuarioMandante(), "A", $Clasificador->getClasificadorId(), $tipoProducto);
                        if ($UsuarioConfiguracion->getProductoId() != "") {
                            throw new Exception("EXCPRODUCT", "20004");
                        }
                    } catch (Exception $e) {
                        // Si no es una excepción específica, vuelve a lanzar
                        if ($e->getCode() != 46) {
                            throw $e;
                        }
                    }
                }
            }
        } catch (Exception $e) {
            // Si no es una excepción específica, vuelve a lanzar
            if ($e->getCode() != 46) {
                throw $e;
            }
        }
    }



    if (($Usuario->test == 'S') && $Usuario->mandante == "0") {

        // Verifica si el mandante y el país del usuario están en los valores permitidos
        if (in_array($Usuario->mandante, ['0']) && in_array($Usuario->paisId, ['173'])) {

            // if($Usuario->verifCelular !== 'S') throw new Exception('Usuario debe verificar el celular', 100095);
        }
    }
    if (in_array($Usuario->usuarioId, ['312731'])) {

        //if($Usuario->verifCelular !== 'S') throw new Exception('Usuario debe verificar el celular', 100095);
    }
    if (in_array($Usuario->usuarioId, ['431619'])) {

        if ($Usuario->verifCelular !== 'S') throw new Exception('Usuario debe verificar el celular', 100095);
    }

    // Si el mandante es 8 y la fecha de creación es después del 8 de marzo de 2023 y el correo no está verificado
    if (($Usuario->mandante == 8 && date('Y-m-d H:i:s', strtotime($Usuario->fechaCrea)) >=  date('Y-m-d H:i:s', strtotime('2023-03-08 00:00:00'))) && $Usuario->verifCorreo == "N") {
        // Crea una instancia de ConfigurationEnvironment y verifica el correo del usuario
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $ConfigurationEnvironment->isCheckdUsuOnlineEmail($Usuario);
    }
    if (($Usuario->mandante == 0  &&  $Usuario->mandante == 94 && date('Y-m-d H:i:s', strtotime($Usuario->fechaCrea)) >=  date('Y-m-d H:i:s', strtotime('2023-12-14 00:00:00'))) && $Usuario->verifCorreo == "N") {
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $ConfigurationEnvironment->isCheckdUsuOnlineEmail($Usuario);
    }

    // Verifica si el usuario tiene un ID específico y no ha verificado su correo electrónico
    if (($Usuario->usuarioId == "4225885" || $Usuario->usuarioId == 2025708 || $Usuario->usuarioId == 886 || $Usuario->usuarioId == 73818  || $Usuario->usuarioId == 85 || $Usuario->usuarioId == 1508705 || $Usuario->usuarioId == 81582 || $Usuario->usuarioId == 312731) && $Usuario->verifCorreo == "N") {
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $ConfigurationEnvironment->isCheckdUsuOnlineEmail($Usuario);
        if (in_array($Usuario->mandante, ['8']) && in_array($Usuario->paisId, ['66'])) {
        }
    }


    if (strpos($service, 'PREPROD-') !== false && $Usuario->mandante == 8 && $Usuario->paisId == 66) {

        switch ($service) {
            case 'PREPROD-MASTERCARD':

                /*
             *
             * Para usuario que tengan en Deposito > 3.000 USD y Apuestas (deportivas + casino) mayores a 4.000 USD, mostrarle la pasarela inswitch con estas reglas.
             *
             * Transacciones en el día hasta 4 transacciones
             *
             * Monto por transacción hasta 2.000
             * Monto por día 4.000
            */
                // Se obtiene la cookie del usuario a través del token del sitio
                $cookie = $UsuarioTokenSite->getCookie();

                // Verifica si la cookie no está vacía ni es nula
                if ($cookie != '' && $cookie != null) {
                    $cookie = json_decode($cookie);
                    if ($cookie != null && in_array($cookie->RISK, array('RISK10', 'RISK0'))) {

                        // Asigna el servicio basado en el nivel de riesgo del usuario
                        if ($cookie->RISK == 'RISK0') {
                            $service = 16437;
                        } else {
                            $service = 11274;
                        }
                    }
                }

                if ($amount < 20) {
                    $service = 26378; //Payphone
                } else {
                    $service = 16437; //Inswitch

                }


                break;
            case 'PREPROD-VISA':

                /*
             *
             * Para usuario que tengan en Deposito > 3.000 USD y Apuestas (deportivas + casino) mayores a 4.000 USD, mostrarle la pasarela inswitch con estas reglas.
             *
             * Transacciones en el día hasta 4 transacciones
             *
             * Monto por transacción hasta 2.000
             * Monto por día 4.000
            */
                $cookie = $UsuarioTokenSite->getCookie();

                if ($cookie != '' && $cookie != null) {
                    $cookie = json_decode($cookie);
                    if ($cookie != null && in_array($cookie->RISK, array('RISK10', 'RISK0'))) {

                        if ($cookie->RISK == 'RISK0') {
                            $service = 16437;
                        } else {
                            $service = 11274;
                        }
                    }
                }
                if ($amount < 20) {
                    $service = 26378; //Payphone
                } else {
                    $service = 16437; //Inswitch

                }
                $service = 26378; //Payphone


                break;
            case 'PREPROD-EFECTIVO':
                $hour = date('G'); // La hora en formato 24 horas sin ceros a la izquierda (0-23)
                $minute = date('i'); // Los minutos con ceros a la izquierda (00-59)

                if (($minute > 30 && $minute < 59) && in_array($hour, [0, 3, 6, 9, 12, 15, 18, 21])) {
                    $service = 13437; //Monnet
                    $service = 8793; //Safetpay
                } else {
                    $service = 8793; //Safetpay
                }
                break;
            case 'PREPROD-3':
                $service = 8793;
                break;
        }
    }

    if (strpos($service, 'PREPROD-') !== false && $Usuario->mandante == '0' && $Usuario->paisId == 66) {
        $hour = date('G'); // La hora en formato 24 horas sin ceros a la izquierda (0-23)
        $minute = date('i'); // Los minutos con ceros a la izquierda (00-59)


        switch ($service) {
            case 'PREPROD-MASTERCARD':
                if (in_array($hour, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12])) {
                    $service = 28356; //Payphone englobamarketing
                } else {
                    $service = 25046; //Payphone PAYPHONE

                }
                break;
            case 'PREPROD-VISA':

                if (in_array($hour, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12])) {
                    $service = 28356; //Payphone englobamarketing
                } else {
                    $service = 25046; //Payphone PAYPHONE

                }

                break;
        }
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


    /*El código verifica si el usuario tiene una contingencia de depósito y lanza una excepción si es así. Luego, obtiene la latitud y longitud
 de los parámetros JSON y las concatena en una variable de ubicación.*/
    if ($Usuario->contingenciaDeposito == 'A') {

        throw new Exception("El usuario ingresado esta autoexcluido. ", "20027");
    }


    $latitud = $json->params->lat;
    $longitud = $json->params->lng;


    $ubicacion  = $longitud . " " . $latitud;


    /*El código verifica si las variables de latitud y longitud están vacías. Si lo están y el mandante del usuario es 13,
lanza una excepción indicando que no se pueden realizar retiros sin permiso de ubicación.*/
    $localizacion = '{"lat":"' . $latitud . '","lng":"' . $longitud . '"}';

    if ($longitud == '' || $latitud == '') {
        if ($Usuario->mandante == 13 && false) {
            throw new Exception("No puede realizar retiros porque no tiene activo el permiso de ubicación.", "21032");
        }
    }



    /*El código verifica si la cuenta del usuario necesita estar verificada para poder depositar, lanzando una excepción si no lo está.*/
    try {
        $Clasificador = new Clasificador("", "ACCVERIFFORDEPOSIT");
        $minimoMontoPremios = 0;

        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');

        if ($MandanteDetalle->getValor() == '1') {
            if ($Usuario->verifcedulaPost != "S" || $Usuario->verifcedulaAnt != 'S') {
                throw new Exception("La cuenta necesita estar verificada para poder depositar", "21006");
            }
        }
    } catch (Exception $e) {
        if ($e->getCode() != 34 && $e->getCode() != 41) {
            throw $e;
        }
    }


    /*El código verifica si el registro del usuario está aprobado antes de permitir un depósito. Si el registro no está aprobado, lanza una excepción.*/
    try {
        $Registro = new Registro("", $Usuario->usuarioId);
        $Clasificador = new Clasificador("", "ACTREGFORDEPOSIT");
        $minimoMontoPremios = 0;

        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');

        if ($MandanteDetalle->getValor() == '1') {

            if ($Registro->estadoValida != 'A') {
                throw new Exception("El registro debe de estar aprobado para poder depositar", "21007");
            }
        }
    } catch (Exception $e) {
        if ($e->getCode() != 34 && $e->getCode() != 41) {
            throw $e;
        }
    }



    /*El código verifica si el monto del depósito es mayor o igual al valor mínimo permitido para el usuario. Si no lo es, lanza una excepción.*/
    try {
        $Clasificador = new Clasificador("", "MINDEPOSIT");
        $minimoMontoPremios = 0;

        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');

        if ($MandanteDetalle->getValor() != '0') {
            if ($amount < $MandanteDetalle->getValor()) {
                throw new Exception("Valor menor al minimo permitido para depositar", "21008");
            }
        }
    } catch (Exception $e) {
        if ($e->getCode() != 34 && $e->getCode() != 41) {
            throw $e;
        }
    }

    /*El código verifica si el monto del depósito excede el valor máximo permitido para el usuario y lanza una excepción si es así.*/
    try {
        $Clasificador = new Clasificador("", "MAXDEPOSIT");
        $minimoMontoPremios = 0;

        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');

        if ($MandanteDetalle->getValor() != '0') {
            if ($amount > $MandanteDetalle->getValor()) {
                throw new Exception("Valor mayor al máximo permitido para depositar", "21009");
            }
        }
    } catch (Exception $e) {
        /*El código captura excepciones y vuelve a lanzar la excepción si el código de error no es 34 o 41.*/

        if ($e->getCode() != 34 && $e->getCode() != 41) {
            throw $e;
        }
    }

    $sg = true;


    if ($service == "FIXED-BANCOCR1") {
        // Inicializa la variable de texto para el depósito
        $textDeposito = "";

        $data = array();
        $data["success"] = true;
        $data["userid"] = $Usuario->usuarioId;
        $data["amount"] = "";
        $data["dataText"] = $textDeposito;
        $data["dataImg"] = "https://images.virtualsoft.tech/site/doradobet/logo-d-color.svg";
        $response = array();
        $response["code"] = 0;
        $response["rid"] = $json->rid;

        $response["data"] = $data;

        $response["data"] = array(
            "result" => 0,
            "details" => array(
                "action" => 'https://mercadeoonline.net/pago-codigos/',
                "method" => '',
                "fields" => array(
                    array("status" => $status_url),
                    // array("name" => "mtid", "value" => "012020321"),
                    // array("name" => "amount", "value" => "10"),
                    // array("name" => "currenct", "value" => "USD")
                )
            )


        );
        $response["data"]['target'] = '_blank';



        $sg = false;
    }

    // Verifica el tipo de servicio y genera una respuesta basada en el mismo
    if ($service == "FIXED-BANCOMX1") {
        $textDeposito = "Hola, realiza la transferencia y envia el comprobante al Whatsapp +52 55 90630287";

        // Inicializa un arreglo de datos para la respuesta
        $data = array();
        $data["success"] = true;
        $data["userid"] = $Usuario->usuarioId;
        $data["amount"] = "";
        $data["dataText"] = $textDeposito;
        $data["dataImg"] = "https://images.virtualsoft.tech/m/msjT1636497972.png";
        $response = array();
        $response["code"] = 0;
        $response["rid"] = $json->rid;

        $response["data"] = $data;

        $sg = false;
    }
    if ($service == "FIXED-BANCOCR2") {
        // Mensaje para el usuario al elegir el servicio FIXED-BANCOCR2
        $textDeposito = "Hola! Favor contactarnos por nuestro chat interno, opción Depósitos - Sinpe Móvil <br><br> En el detalle del deposito se debe enviar su ID de usuario, este se encuentra en la parte superior derecha de la plataforma.";

        // Inicializa un arreglo de datos para la respuesta
        $data = array();
        $data["success"] = true;
        $data["userid"] = $Usuario->usuarioId;
        $data["amount"] = "";
        $data["dataText"] = $textDeposito;
        $data["dataImg"] = "https://images.virtualsoft.tech/site/doradobet/logo-d-color.svg";
        $response = array();
        $response["code"] = 0;
        $response["rid"] = $json->rid;

        $response["data"] = $data;

        $sg = false;
    }
    if ($service == "FIXED-BANCOCR3") {

        $data = array();
        $data["success"] = true;
        $data["userid"] = $Usuario->usuarioId;
        $data["amount"] = "";
        $data["dataText"] = $textDeposito;
        $data["dataImg"] = "";
        $response = array();
        $response["code"] = 0;
        $response["rid"] = $json->rid;

        $response["data"] = $data;

        $response["data"] = array(
            "result" => 0,
            "details" => array(
                "action" => 'https://mercadeoonline.net/recarga/',
                "method" => '',
                "fields" => array(
                    array("status" => $status_url),
                    // array("name" => "mtid", "value" => "012020321"),
                    // array("name" => "amount", "value" => "10"),
                    // array("name" => "currenct", "value" => "USD")
                )
            )


        );
        $response["data"]['target'] = '_blank';

        $sg = false;
    }
    if ($service == "FIXED-BANCOGTQ1") {
        /**
         * Inicializa el arreglo de datos para la respuesta del servicio FIXED-BANCOGTQ1.
         */
        $textDeposito = "Hola!<br><br>Para recargar tu cuenta sigue los siguientes pasos:<br><br>1. Transfiere a la cuenta bancaria numero 08800074057 en Quetzales<br><br>2. Envia el comprobante al WhatsApp <a style='font-weight: bold;color:blue;' href='https://wa.me/50238478995'>+502 3847-8995</a<br><br>3. Tu recarga será tramitada (Este paso puede tardar hasta 2 horas en semana, los fines de semana se puede tardar incluso un poco mas";

        $data = array();
        $data["success"] = true;
        $data["userid"] = $Usuario->usuarioId;
        $data["amount"] = "";
        $data["dataText"] = $textDeposito;
        $data["dataImg"] = "https://images.virtualsoft.tech/site/doradobet/logo-d-color.svg";
        $response = array();
        $response["code"] = 0;
        $response["rid"] = $json->rid;

        $response["data"] = $data;

        $sg = false;
    }

    if ($service == "FIXED-BANCODBTSLVR1") {
        // Definición del texto que se mostrará al usuario con instrucciones sobre el depósito
        $textDeposito = "BAC CREDOMATIC:<br><br>
 
Cuenta corriente número: 201408341<br><br>
 
 Número de WhatsApp: +503 72419578<br><br>
 
Querido usuario; una vez realice el depósito, debe enviar foto o capture del comprobante, acompañado del ID de usuario a nuestra línea de WhatsApp +503 72419578<br><br>
 
Recuerda que el monto mínimo de recarga son $5 USD<br><br> ";

        // Inicialización del array de datos que se enviarán como respuesta
        $data = array();
        $data["success"] = true;                   // Indica si la operación fue exitosa
        $data["userid"] = $Usuario->usuarioId;    // ID del usuario asociado
        $data["amount"] = "";                       // Monto, se deja vacío
        $data["dataText"] = $textDeposito;         // Texto con las instrucciones de depósito
        $data["dataImg"] = "https://images.virtualsoft.tech/m/msj23212T1710981323.png"; // URL de la imagen a mostrar

        // Inicialización del array de respuesta
        $response = array();
        $response["code"] = 0;                     // Código de respuesta
        $response["rid"] = $json->rid;             // ID de la solicitud
        $response["data"] = $data;                  // Datos retornados
        $sg = false;                                // Variable de control (su valor no se utiliza en el fragmento proporcionado)
    }

    if ($service == "FIXED-BANCODBTSLVR1") {
        // Definición del mensaje de depósito para el servicio FIXED-BANCODBTSLVR1
        $textDeposito = "BAC CREDOMATIC:<br><br>
 
Cuenta corriente número: 201408341<br><br>
 
 Número de WhatsApp: +503 72419578<br><br>
 
Querido usuario; una vez realice el depósito, debe enviar foto o capture del comprobante, acompañado del ID de usuario a nuestra línea de WhatsApp +503 72419578<br><br>
 
Recuerda que el monto mínimo de recarga son $5 USD<br><br> ";

        // Inicializar el arreglo de datos
        $data = array();
        $data["success"] = true; // Indica que la operación fue exitosa
        $data["userid"] = $Usuario->usuarioId; // ID del usuario
        $data["amount"] = ""; // Monto, actualmente vacío
        $data["dataText"] = $textDeposito; // Mensaje de depósito
        $data["dataImg"] = "https://images.virtualsoft.tech/m/msj23212T1710981323.png"; // URL de la imagen

        // Inicializar el arreglo de respuesta
        $response = array();
        $response["code"] = 0; // Código de respuesta
        $response["rid"] = $json->rid; // ID de la solicitud
        $response["data"] = $data; // Datos de la respuesta

        $sg = false; // Variable de estado, inicialmente falsa
    }

    if ($service == "FIXED-BANCODBTSLVR2") {
        // Genera el texto de instrucción para el depósito
        $textDeposito = '<div style="width: 100%; display:flex;">
                         <span style="display: flex; align-items: center; justify-content: center; width: 50%;">
                            <img src="https://images.virtualsoft.tech/m/msj0212T1718142446.png" alt="img-QR" width="100px" height="100px" style="margin-top: 60px;">
                         </span>                                 
                         <p style="text-align: left; margin: auto; width: 80%;"> Chivo Wallet:<br><br> 
                            Querido usuario; una vez realice el depósito, debe enviar foto o capture del comprobante, acompañado del ID de usuario a nuestra línea de WhatsApp +503 72419578<br><br>
                            Recuerda que el monto mínimo de recarga son $5 USD<br><br> 
                            Quedamos atentos, muchas gracias. 
                         </p>
                     </div>';

        // Inicializa el arreglo de datos
        $data = array();
        $data["success"] = true; // Indica el éxito de la operación
        $data["userid"] = $Usuario->usuarioId; // Almacena el ID del usuario
        $data["amount"] = ""; // Monto de la transacción (vacío por el momento)
        $data["dataText"] = $textDeposito; // Texto de instrucciones
        $data["dataImg"] = "https://images.virtualsoft.tech/m/msj0212T1718140425.png"; // Imagen asociada

        // Inicializa la respuesta
        $response = array();
        $response["code"] = 0; // Código de éxito
        $response["rid"] = $json->rid; // ID de la transacción
        $response["data"] = $data; // Agrega los datos a la respuesta

        $sg = false; // Variable de estado
    }

    if ($service == "FIXED-BANCO1") {
        // Genera el texto de instrucción para el depósito
        $textDeposito = "Hola! Ahora puedes depositar a través de tu banco preferido, envíanos el comprobante a través de whatsapp y recibe tu saldo DoradoBet al instante, nuestro servicio es de 8:00 am a 8:00 pm.

Depósito mínimo: 10 USD 
Depósito máximo: 3000 USD 
Número de cuenta bancaria: BAC CORDOBAS 5700000330 ROSALIA RUIZ RESTREPO
Número de Whatsapp: 505 82442785
";

        // Inicializa el arreglo de datos
        $data = array();
        $data["success"] = true; // Indica el éxito de la operación
        $data["userid"] = $Usuario->usuarioId; // Almacena el ID del usuario
        $data["amount"] = ""; // Monto de la transacción (vacío por el momento)
        $data["dataText"] = $textDeposito; // Texto de instrucciones
        $data["dataImg"] = "https://images.virtualsoft.tech/site/doradobet/logo-d-color.svg"; // Imagen asociada

        // Inicializa la respuesta
        $response = array();
        $response["code"] = 0; // Código de éxito
        $response["rid"] = $json->rid; // ID de la transacción

        $response["data"] = $data;

        $sg = false;
    }
    if ($service == "FIXED-BANCO2") {

        // Mensaje de texto para el depósito en el banco FIXED-BANCO2
        $textDeposito = "Hola! Ahora puedes depositar a través de tu banco preferido, envíanos el comprobante a través de whatsapp y recibe tu saldo DoradoBet al instante, nuestro servicio es de 8:00 am a 8:00 pm.

Depósito mínimo: 10 USD 
Depósito máximo: 3000 USD 
Número de cuenta bancaria: BAC CORD 5710000222 ROSALIA RUIZ RESTREPO
Número de Whatsapp: 505 82442785
";

        // Inicializa el arreglo de datos
        $data = array();
        $data["success"] = true;
        $data["userid"] = $Usuario->usuarioId;
        $data["amount"] = "";
        $data["dataText"] = $textDeposito;
        $data["dataImg"] = "https://images.virtualsoft.tech/site/doradobet/logo-d-color.svg";
        $response = array();
        $response["code"] = 0;
        $response["rid"] = $json->rid;

        $response["data"] = $data;

        $sg = false;
    }
    if ($service == "FIXED-BANCO4") {

        $textDeposito = "Hola! Ahora puedes depositar a través de tu banco preferido, envíanos el comprobante a través de whatsapp y recibe tu saldo DoradoBet al instante, nuestro servicio es de 8:00 am a 8:00 pm.

Depósito mínimo: 10 USD 
Depósito máximo: 5000 USD 
Número de cuenta bancaria: BDF CORD 6080564153 ROSALIA RUIZ RESTREPO 
Número de Whatsapp: 505 82442785
";

        // Inicializa el arreglo de datos
        $data = array();
        $data["success"] = true; // Indica que la operación fue exitosa
        $data["userid"] = $Usuario->usuarioId; // Identificador del usuario
        $data["amount"] = ""; // Monto del depósito
        $data["dataText"] = $textDeposito; // Texto informativo sobre el depósito
        $data["dataImg"] = "https://images.virtualsoft.tech/site/doradobet/logo-d-color.svg"; // URL de la imagen del logo
        $response = array();
        $response["code"] = 0; // Código de respuesta
        $response["rid"] = $json->rid; // Identificador de la transacción

        $response["data"] = $data; // Asigna los datos a la respuesta

        $sg = false;
    }

    if ($service == "FIXED-BANCO3") {

        // Mensaje de depósito para el servicio FIXED-BANCO3
        $textDeposito = "Hola! Ahora puedes depositar a través de tu banco preferido, envíanos el comprobante a través de whatsapp y recibe tu saldo DoradoBet al instante, nuestro servicio es de 8:00 am a 8:00 pm.

Depósito mínimo: 10 USD 
Depósito máximo: 5000 USD 
Número de cuenta bancaria: LAFISE USD 106204583 DARWIN MEDINA
Número de Whatsapp: 505 82442785
";

        // Inicializa un array para los datos de respuesta
        $data = array();
        $data["success"] = true; // Indica que la respuesta fue exitosa
        $data["userid"] = $Usuario->usuarioId; // ID del usuario
        $data["amount"] = ""; // Monto, está vacío en este caso
        $data["dataText"] = $textDeposito; // Mensaje de depósito
        $data["dataImg"] = "https://images.virtualsoft.tech/site/doradobet/logo-d-color.svg"; // Imagen del logo
        $response = array();
        $response["code"] = 0; // Código de respuesta
        $response["rid"] = $json->rid; // ID de la respuesta JSON

        $response["data"] = $data; // Asigna los datos a la respuesta

        $sg = false; // Variable de estado
    }

    if ($service == "FIXED-BANCO5") {

        // Mensaje de depósito para el servicio FIXED-BANCO5
        $textDeposito = "Hola! Ahora puedes depositar a través de tu banco preferido, envíanos el comprobante a través de whatsapp y recibe tu saldo DoradoBet al instante, nuestro servicio es de 8:00 am a 8:00 pm.

Depósito mínimo: 10 USD 
Depósito máximo: 5000 USD 
Número de cuenta bancaria: BDF CORD 6070312903 ROSALIA RUIZ RESTREPO 
Número de Whatsapp: 505 82442785";

        // Inicializa un array para los datos de respuesta
        $data = array();
        $data["success"] = true;
        $data["userid"] = $Usuario->usuarioId;
        $data["amount"] = "";
        $data["dataText"] = $textDeposito;
        $data["dataImg"] = "https://images.virtualsoft.tech/site/doradobet/logo-d-color.svg";
        $response = array();
        $response["code"] = 0;
        $response["rid"] = $json->rid;

        $response["data"] = $data;

        $sg = false;
    }


    if ($service == "FIXED-BANCO6") {
        /**
         * Configuración de depósito para el servicio FIXED-BANCO6.
         * Proporciona detalles sobre cómo realizar un depósito.
         */
        $textDeposito = "Hola! Ahora puedes depositar a través de tu banco preferido, envíanos el comprobante a través de whatsapp y recibe tu saldo DoradoBet al instante, nuestro servicio es de 8:00 am a 8:00 pm.

Depósito mínimo: 10 USD 
Depósito máximo: 5000 USD 
Número de cuenta bancaria: BDF DOLARES 366688539 NELSON DAVID MARTINEZ 
Número de Whatsapp: 505 82442785";

        $data = array();
        $data["success"] = true;
        $data["userid"] = $Usuario->usuarioId;
        $data["amount"] = "";
        $data["dataText"] = $textDeposito;
        $data["dataImg"] = "https://images.virtualsoft.tech/site/doradobet/logo-d-color.svg";
        $response = array();
        $response["code"] = 0;
        $response["rid"] = $json->rid;

        $response["data"] = $data;

        $sg = false;
    }


    if ($service == "FIXED-BANCO7") {
        /**
         * Configuración de depósito para el servicio FIXED-BANCO7.
         * Proporciona detalles sobre cómo realizar un depósito.
         */
        $textDeposito = "Hola! Ahora puedes depositar a través de tu banco preferido, envíanos el comprobante a través de whatsapp y recibe tu saldo DoradoBet al instante, nuestro servicio es de 8:00 am a 8:00 pm.

Depósito mínimo: 10 USD 
Depósito máximo: 5000 USD 
Número de cuenta bancaria: BDF CORD 366688562 NELSON DAVID MARTINEZ 
Número de Whatsapp: 505 82442785";

        $data = array();
        $data["success"] = true;
        $data["userid"] = $Usuario->usuarioId;
        $data["amount"] = "";
        $data["dataText"] = $textDeposito;
        $data["dataImg"] = "https://images.virtualsoft.tech/site/doradobet/logo-d-color.svg";
        $response = array();
        $response["code"] = 0;
        $response["rid"] = $json->rid;

        $response["data"] = $data;

        $sg = false;
    }


    if ($service == "FIXED-BANCOECU1") {
        // Inicializa la variable para deposito
        $textDeposito = "";

        $data = array();
        $data["success"] = true;
        $data["userid"] = $Usuario->usuarioId;
        $data["amount"] = "";
        $data["dataText"] = $textDeposito;
        $data["dataImg"] = "";
        $response = array();
        $response["code"] = 0;
        $response["rid"] = $json->rid;

        $response["data"] = $data;

        $response["data"] = array(
            "result" => 0,
            "details" => array(
                "action" => 'https://inhz1.facilito.com.ec:2192/Ubicanos/Index.aspx',
                "method" => '',
                "fields" => array(
                    array("status" => $status_url),
                    // array("name" => "mtid", "value" => "012020321"),
                    // array("name" => "amount", "value" => "10"),
                    // array("name" => "currenct", "value" => "USD")
                )
            )


        );
        // Establece el objetivo de la acción
        $response["data"]['target'] = '_blank';

        // Variable de estado
        $sg = false;
    }

    /*El código verifica si el servicio es FIXED-BANCOECU2, establece un mensaje de texto para el depósito, crea un arreglo de datos con la
 información del depósito y actualiza la respuesta con estos datos.*/
    if ($service == "FIXED-BANCOECU2") {
        $textDeposito = "Dirigete al punto de Red activa Western Union, indica el id de tu cliente y solicita tu recarga.";

        $data = array();
        $data["success"] = true;
        $data["userid"] = $Usuario->usuarioId;
        $data["amount"] = "";
        $data["dataText"] = $textDeposito;
        $data["dataImg"] = "https://images.virtualsoft.tech/m/msjT1637954767.png";
        $response = array();
        $response["code"] = 0;
        $response["rid"] = $json->rid;

        $response["data"] = $data;

        $sg = false;
    }


    /*El código verifica si el servicio es FIXED-BANCOECU3, establece un mensaje de texto para el depósito, crea un arreglo de datos con la
 información del depósito y actualiza la respuesta con estos datos.*/
    if ($service == "FIXED-BANCOECU3") {
        $textDeposito = "Dirigete al punto de Bemovil, indica el id de tu cliente y solicita tu recarga.";

        $data = array();
        $data["success"] = true;
        $data["userid"] = $Usuario->usuarioId;
        $data["amount"] = "";
        $data["dataText"] = $textDeposito;
        $data["dataImg"] = "https://images.virtualsoft.tech/m/msjT1645551119.png";
        $response = array();
        $response["code"] = 0;
        $response["rid"] = $json->rid;

        $response["data"] = $data;

        $sg = false;
    }


    /*Este código configura la respuesta para el servicio FIXED-BANCOECU4, proporcionando detalles sobre cómo realizar un depósito en el punto de Bakan.*/
    if ($service == "FIXED-BANCOECU4") {
        $textDeposito = "Dirigete al punto de Bakan, indica el id de tu cliente y solicita tu recarga.";

        $data = array();
        $data["success"] = true;
        $data["userid"] = $Usuario->usuarioId;
        $data["amount"] = "";
        $data["dataText"] = $textDeposito;
        $data["dataImg"] = "https://images.virtualsoft.tech/m/msjT1655400914.png";
        $response = array();
        $response["code"] = 0;
        $response["rid"] = $json->rid;

        $response["data"] = $data;

        $sg = false;
    }


    /*El código verifica si el servicio es FIXED-BANCOECU5, establece un mensaje de texto para el depósito, crea un arreglo de datos con la información del depósito y actualiza la respuesta con estos datos.*/
    if ($service == "FIXED-BANCOECU5") {
        $textDeposito = "Dirigete al punto de FullCarga, indica el id de tu cliente y solicita tu recarga.";

        $data = array();
        $data["success"] = true;
        $data["userid"] = $Usuario->usuarioId;
        $data["amount"] = "";
        $data["dataText"] = $textDeposito;
        $data["dataImg"] = "https://images.virtualsoft.tech/m/msjT1669771359.png";
        $response = array();
        $response["code"] = 0;
        $response["rid"] = $json->rid;

        $response["data"] = $data;

        $sg = false;
    }

    /*El código verifica si el servicio es FIXED-BANCOECU6, establece un mensaje de texto para el depósito, crea un arreglo de datos con la
 información del depósito y actualiza la respuesta con estos datos.*/
    if ($service == "FIXED-BANCOECU6") {
        $textDeposito = "Dirigete al punto de Farmacias Medicity, indica el id de tu cliente y solicita tu recarga.";

        $data = array();
        $data["success"] = true;
        $data["userid"] = $Usuario->usuarioId;
        $data["amount"] = "";
        $data["dataText"] = $textDeposito;
        $data["dataImg"] = "https://images.virtualsoft.tech/m/msjT1696267989.png";
        $response = array();
        $response["code"] = 0;
        $response["rid"] = $json->rid;

        $response["data"] = $data;

        $sg = false;
    }

    // Verifica el servicio para el caso "FIXED-BANCOECU7"
    if ($service == "FIXED-BANCOECU7") {
        $textDeposito = "Dirigete al punto de Farmacias Economicas, indica el id de tu cliente y solicita tu recarga.";

        $data = array();
        $data["success"] = true;
        $data["userid"] = $Usuario->usuarioId;
        $data["amount"] = "";
        $data["dataText"] = $textDeposito;
        $data["dataImg"] = "https://images.virtualsoft.tech/m/msjT1696011878.png";
        $response = array();
        $response["code"] = 0;
        $response["rid"] = $json->rid;

        $response["data"] = $data;

        $sg = false;
    }


    if ($service == "FIXED-BANCOJMD1") {
        $textDeposito = "
    Beneficiary Name: Prime Sports Jamaica Limited
Beneficiary Address: 9A Retirement Crescent, Kingston 5
Beneficiary Bank: Bank of Nova Scotia
Beneficiary Account: 000802175
Branch Transit #: 50575
Account Type: Chequing Account
Bank Address: Knutsford Boulevard, Kingston 5 (New Kingston)

";

        /**
         * Array para almacenar la información de la respuesta.
         *
         * @var array $data Arreglo que contiene detalles de la respuesta sobre la solicitud de depósito.
         */
        $data = array();
        $data["success"] = true;
        $data["userid"] = $Usuario->usuarioId;
        $data["amount"] = "";
        $data["dataText"] = $textDeposito;
        $data["dataImg"] = "https://images.virtualsoft.tech/site/justbet/logo.png";
        $response = array();
        $response["code"] = 0;
        $response["rid"] = $json->rid;

        $response["data"] = $data;

        $sg = false;
    }
    // Verifica si el servicio es "FIXED-BANCOJMD2"
    if ($service == "FIXED-BANCOJMD2") {
        // Define el texto del depósito con la información del beneficiario
        $textDeposito = "
    Beneficiary Name: Prime Sports Jamaica Limited
Beneficiary Address: 9A Retirement Crescent, Kingston 5
Beneficiary Bank: Sagicor Bank Jamaica
Beneficiary Account #5503070814
Account Type: Savings Account
Bank Address: 17 Dominica Drive, Kingston 5.


";

        // Inicializa un array para los datos de respuesta
        $data = array();
        $data["success"] = true;
        $data["userid"] = $Usuario->usuarioId;
        $data["amount"] = "";
        $data["dataText"] = $textDeposito;
        $data["dataImg"] = "https://images.virtualsoft.tech/site/justbet/logo.png";
        $response = array();
        $response["code"] = 0;
        $response["rid"] = $json->rid;

        $response["data"] = $data;

        $sg = false;
    }

    if ($service == "FIXED-BANCOJMD3") {
        // Se define el texto del depósito con los detalles del beneficiario.
        $textDeposito = "
    Beneficiary Name: Prime Sports Jamaica Limited
Beneficiary Address: 9A Retirement Crescent, Kingston 5
Beneficiary Bank: Bank of Nova Scotia
Beneficiary Account: 000802175
Branch Transit #: 50575
Account Type: Chequing Account
Bank Address: Knutsford Boulevard, Kingston 5 (New Kingston)

";

        // Se inicializa un array para almacenar los datos de respuesta.
        $data = array();
        $data["success"] = true;
        $data["userid"] = $Usuario->usuarioId;
        $data["amount"] = "";
        $data["dataText"] = $textDeposito;
        $data["dataImg"] = "https://images.virtualsoft.tech/site/justbet/logo.png";
        $response = array();
        $response["code"] = 0;
        $response["rid"] = $json->rid;

        $response["data"] = $data;

        $sg = false;
    }

    if ($service == "FIXED-BANCOJMD4") {
        /**
         * Información del beneficiario para el servicio FIXED-BANCOJMD4.
         *
         * Se define el texto que contiene los detalles del beneficiario,
         * incluyendo nombre, dirección, banco, número de cuenta y tipo de cuenta.
         */
        $textDeposito = "
    Beneficiary Name: Prime Sports Jamaica Limited
Beneficiary Address: 9A Retirement Crescent, Kingston 5
Beneficiary Bank: Sagicor Bank Jamaica
Beneficiary Account #5503070814
Account Type: Savings Account
Bank Address: 17 Dominica Drive, Kingston 5.


";

        $data = array();
        $data["success"] = true;
        $data["userid"] = $Usuario->usuarioId;
        $data["amount"] = "";
        $data["dataText"] = $textDeposito;
        $data["dataImg"] = "https://images.virtualsoft.tech/site/justbet/logo.png";
        $response = array();
        $response["code"] = 0;
        $response["rid"] = $json->rid;

        $response["data"] = $data;

        $sg = false;
    }

    if ($Usuario->mandante == '15') {

        // Verifica el tipo de servicio y genera un mensaje específico para el depósito en función del servicio seleccionado.
        if ($service == "FIXED-BANCOHNL1") {
            $textDeposito = "⚪ BAC CREDOMATIC: <br><br><b>AHORROS:</b> 748498871<br><br>
<b>CHEQUES:</b> 730470431<br><br>
 Querido usuario; una vez realices el depósito, debes enviar foto o capture del comprobante, acompañado de tu ID de usuario a nuestra línea de WhatsApp 
 <a target='_blank' href='https://api.whatsapp.com/send?phone=50492062735' style='font-weight: bold;'>+504 9206-2735</a>.<br><br> 
Recuerda que el monto mínimo de recarga son 100 Lempiras<br><br> 

Quedamos atentos, muchas gracias.";

            $data = array();
            $data["success"] = true;
            $data["userid"] = $Usuario->usuarioId;
            $data["amount"] = "";
            $data["dataText"] = $textDeposito;
            $data["dataImg"] = "https://images.virtualsoft.tech/m/msjT1659200344.png";
            $response = array();
            $response["code"] = 0;
            $response["rid"] = $json->rid;

            $response["data"] = $data;

            $sg = false;
        }
        if ($service == "FIXED-BANCOHNL2") {
            // Mensaje para el depósito en BANCO ATLANTIDA
            $textDeposito = "🔴 BANCO ATLANTIDA AHORROS:<br><br><b>Cta de ahorro Lempiras # 2020002265</b> <br><br><b>Cta de Cheques Lempiras # 2010001603 </b> <br><br>RED DE SERVICIOS HONDURAS S.A.<br><br>
 Querido usuario; una vez realices el depósito, debes enviar foto o capture del comprobante, acompañado de tu ID de usuario a nuestra línea de WhatsApp 
 <a target='_blank' href='https://api.whatsapp.com/send?phone=50492062735' style='font-weight: bold;'>+504 9206-2735</a>.<br><br> 
Recuerda que el monto mínimo de recarga son 100 Lempiras<br><br> 

Quedamos atentos, muchas gracias";

            $data = array();
            $data["success"] = true;
            $data["userid"] = $Usuario->usuarioId;
            $data["amount"] = "";
            $data["dataText"] = $textDeposito;
            $data["dataImg"] = "https://images.virtualsoft.tech/m/msjT1659200381.png";
            $response = array();
            $response["code"] = 0;
            $response["rid"] = $json->rid;

            $response["data"] = $data;

            $sg = false;
        }

        // Se verifica el tipo de servicio proporcionado
        if ($service == "FIXED-BANCOHNL3") {
            // Mensaje de instrucciones para el depósito en Banco Fico HSA
            $textDeposito = "🟣 BACNO FICOHSA:<br><br><b>AHORROS:</b>  200014903355
<b>CHEQUES:</b>  200014903479<br><br>
RED DE SERVICIOS HONDURAS S.A.<br><br> 
 Querido usuario; una vez realices el depósito, debes enviar foto o capture del comprobante, acompañado de tu ID de usuario a nuestra línea de WhatsApp 
 <a target='_blank' href='https://api.whatsapp.com/send?phone=50492062735' style='font-weight: bold;'>+504 9206-2735</a>.<br><br> 
Recuerda que el monto mínimo de recarga son 100 Lempiras<br><br> 

Quedamos atentos, muchas gracias.";

            $data = array();
            $data["success"] = true;
            $data["userid"] = $Usuario->usuarioId;
            $data["amount"] = "";
            $data["dataText"] = $textDeposito;
            $data["dataImg"] = "https://images.virtualsoft.tech/m/msjT1659200422.png";
            $response = array();
            $response["code"] = 0;
            $response["rid"] = $json->rid;

            $response["data"] = $data;

            $sg = false;
        }
        if ($service == "FIXED-BANCOHNL4") {
            $textDeposito = "🟠 DAVIVIENDA AHORROS:<b>Cuenta Nro. 2211235596</b> <br><br> Intrucciones: Una vez realizado el deposito debe enviar foto o capture del comprobante junto a su numero de usuario, a nuestro numero de whatsapp <a target='_blank' href='https://api.whatsapp.com/send?phone=50492062735' style='font-weight: bold;'>+504 9206-2735</a>.";

            $data = array();
            $data["success"] = true;
            $data["userid"] = $Usuario->usuarioId;
            $data["amount"] = "";
            $data["dataText"] = $textDeposito;
            $data["dataImg"] = "https://images.virtualsoft.tech/m/msjT1659200456.png";
            $response = array();
            $response["code"] = 0;
            $response["rid"] = $json->rid;

            $response["data"] = $data;

            $sg = false;
        }
        // Verifica el servicio y genera un mensaje de depósito en función del tipo de servicio.
        if ($service == "FIXED-BANCOHNL6") {
            // Mensaje para el servicio FIXED-BANCOHNL6 describiendo cómo recargar por TIGOMONEY.
            $textDeposito = "🔵 TIGOMONEY: Pasos para recargar por TIGOMONEY  <br><br> Acércate al punto más cercano e indica que vas a realizar un depósito a tu cuenta Hondubet. <br> Recuerda tener presente tu número de ID de tu cuenta. <br><br> Monto mínimo de recarga: 250 HNL. <br> Tu recarga será acreditada de manera automática. <br><br> 🔵Puntos Tigo Money <br> <span>En el siguiente link podrás encontrar los <a href='https://lc.cx/fLPUhN' target='_blank'><strong>puntos disponibles de Tigo Money</strong></a></span>";

            $data = array();
            $data["success"] = true;
            $data["userid"] = $Usuario->usuarioId;
            $data["amount"] = "";
            $data["dataText"] = $textDeposito;
            $data["dataImg"] = "https://images.virtualsoft.tech/m/msjT1659200757.png";
            $response = array();
            $response["code"] = 0;
            $response["rid"] = $json->rid;

            $response["data"] = $data;

            $sg = false;
        }
        // Verifica si el servicio es FIXED-BANCOHNL7 y genera un mensaje de depósito correspondiente.
        if ($service == "FIXED-BANCOHNL7") {
            // Mensaje para el servicio FIXED-BANCOHNL7 describiendo cómo realizar un depósito a través de Wallet Tengo.
            $textDeposito = "🟣 WALLET TENGO: <b>Numero Telefonico:  94583165</b> <br><br> Intrucciones: Una vez realizado el deposito debe enviar foto o capture del comprobante junto a su numero de usuario, a nuestro numero de whatsapp <a target='_blank' href='https://api.whatsapp.com/send?phone=50492062735' style='font-weight: bold;'>+504 9206-2735</a>.";

            $data = array();
            $data["success"] = true;
            $data["userid"] = $Usuario->usuarioId;
            $data["amount"] = "";
            $data["dataText"] = $textDeposito;
            $data["dataImg"] = "https://images.virtualsoft.tech/m/msjT1659200778.png";
            $response = array();
            $response["code"] = 0;
            $response["rid"] = $json->rid;

            $response["data"] = $data;

            $sg = false;
        }


        /*El código verifica si el servicio es FIXED-BANCOHNL8, establece un mensaje de texto para el depósito,
     crea un arreglo de datos con la información del depósito y actualiza la respuesta con estos datos.*/
        if ($service == "FIXED-BANCOHNL8") {
            $textDeposito = "🟡 USDT TRC20: <b>TYV3EpbdozZ1S86bTFnx3aWnKkwZ9dNKZC </b> <br><br> Intrucciones: Una vez realizado el deposito debe enviar foto o capture del comprobante junto a su numero de usuario, a nuestro numero de whatsapp <a target='_blank' href='https://api.whatsapp.com/send?phone=50492062735' style='font-weight: bold;'>+504 9206-2735</a>.";

            $data = array();
            $data["success"] = true;
            $data["userid"] = $Usuario->usuarioId;
            $data["amount"] = "";
            $data["dataText"] = $textDeposito;
            $data["dataImg"] = "https://images.virtualsoft.tech/m/msjT1659201477.png";
            $response = array();
            $response["code"] = 0;
            $response["rid"] = $json->rid;

            $response["data"] = $data;

            $sg = false;
        }

        if ($service == "FIXED-BANCOHNL9") {
            $textDeposito = "🟤 BITCOIN: <b>bc1qyc6hv82zn35gk2290jx8qjctzjtsq9tpet5put</b> <br><br> Intrucciones: Una vez realizado el deposito debe enviar foto o capture del comprobante junto a su numero de usuario, a nuestro numero de whatsapp <a target='_blank' href='https://api.whatsapp.com/send?phone=50492062735' style='font-weight: bold;'>+504 9206-2735</a>.";

            $data = array();
            $data["success"] = true;
            $data["userid"] = $Usuario->usuarioId;
            $data["amount"] = "";
            $data["dataText"] = $textDeposito;
            $data["dataImg"] = "https://images.virtualsoft.tech/m/msjT1659200844.png";
            $response = array();
            $response["code"] = 0;
            $response["rid"] = $json->rid;

            $response["data"] = $data;

            $sg = false;
        }
        // Verifica el tipo de servicio y establece el texto de depósito y los datos de respuesta correspondientes
        if ($service == "FIXED-BANCOHNL10") {
            $textDeposito = "🟤 BANPAIS<br><br>  Cta de ahorro Lempiras: 215990026290<br><br> 

Cta de Cheques Lempiras: 015990023506:<br><br> 
RED DE SERVICIOS HONDURAS S.A.<br><br> 

Querido usuario; una vez realices el depósito, debes enviar foto o capture del comprobante, acompañado de tu ID de usuario a nuestra línea de WhatsApp <a target='_blank' href='https://api.whatsapp.com/send?phone=50492062735' style='font-weight: bold;'>+504 9206-2735</a>.
<br><br> 
Recuerda que el monto mínimo de recarga son 100 Lempiras<br><br> 

Quedamos atentos, muchas gracias..";

            // Inicializa el arreglo de datos para la respuesta
            $data = array();
            $data["success"] = true;
            $data["userid"] = $Usuario->usuarioId;
            $data["amount"] = "";
            $data["dataText"] = $textDeposito;
            $data["dataImg"] = "https://images.virtualsoft.tech/m/msjT1660930274.png";
            $response = array();
            $response["code"] = 0;
            $response["rid"] = $json->rid;

            $response["data"] = $data;

            $sg = false;
        }

        // Verifica el segundo tipo de servicio y establece el texto de depósito y los datos de respuesta correspondientes
        if ($service == "FIXED-BANCOHNL11") {
            $textDeposito = "<b>Deposito directo N1CO</b><br><br>Escanea el código QR o da click en el enlace <br><a target='_blank' href='https://pay.n1co.shop/pl/bKoWoSwm1' style='font-weight: bold;'>https://pay.n1co.shop/pl/bKoWoSwm1</a>.<br><br>
Querido usuario; una vez realices el depósito, debes enviar foto o capture del comprobante, acompañado de tu ID de usuario a nuestra línea de WhatsApp +504 9206-2735.";

            // Inicializa el arreglo de datos para la respuesta
            $data = array();
            $data["success"] = true;
            $data["userid"] = $Usuario->usuarioId;
            $data["amount"] = "";
            $data["dataText"] = $textDeposito;
            $data["dataImg"] = "https://images.virtualsoft.tech/m/msjT1667447157.png";
            $response = array();
            $response["code"] = 0;
            $response["rid"] = $json->rid;

            $response["data"] = $data;

            $sg = false;
        }


        if ($service == "FIXED-BANCOHNL12") {
            $textDeposito = "🟤 BANRURAL<br><br>  Cta de ahorro Lempiras: 10401010219774<br><br> 

Cta de Cheques Lempiras: 10403010009679:<br><br> 
RED DE SERVICIOS HONDURAS S.A.<br><br> 

Querido usuario; una vez realices el depósito, debes enviar foto o capture del comprobante, acompañado de tu ID de usuario a nuestra línea de WhatsApp <a target='_blank' href='https://api.whatsapp.com/send?phone=50492062735' style='font-weight: bold;'>+504 9206-2735</a>.
<br><br> 
Recuerda que el monto mínimo de recarga son 100 Lempiras<br><br> 

Quedamos atentos, muchas gracias..";
            $data = array();
            $data["success"] = true;
            $data["userid"] = $Usuario->usuarioId;
            $data["amount"] = "";
            $data["dataText"] = $textDeposito;
            $data["dataImg"] = "https://images.virtualsoft.tech/m/msjT1693349457.png";
            $response = array();
            $response["code"] = 0;
            $response["rid"] = $json->rid;

            $response["data"] = $data;

            $sg = false;
        }
    }

    // Verifica si el mandante del usuario es '16'
    if ($Usuario->mandante == '16') {
        // Comprueba si el servicio es 'FIXED-BANCOLATIN1'
        if ($service == "FIXED-BANCOLATIN1") {
            $textDeposito = "
Buenas tardes,<br><br>

Señor usuario, para realizar sus recargas y retiros, por favor comuníquese al siguiente WhatsApp:<br><br>
<a target='_blank' href='https://api.whatsapp.com/send/?phone=50768114280&text=Hola%21+Deseo+hablar+con+un+agente+de+latinbet.com.pa&type=phone_number&app_absent=0 ' style='font-weight: bold;'>50768114280</a>

<br><br>
Gracias!";

            // Inicializa un array para los datos de respuesta
            $data = array();
            $data["success"] = true;
            $data["userid"] = $Usuario->usuarioId;
            $data["amount"] = "";
            $data["dataText"] = $textDeposito;
            $data["dataImg"] = "https://images.virtualsoft.tech/m/msjT1661290169.png";
            $response = array();
            $response["code"] = 0;
            $response["rid"] = $json->rid;

            $response["data"] = $data;

            $sg = false;
        }
    }

    if ($Usuario->mandante == '18') {

        // Verifica si el servicio es "FIXED-BANCOGANMXN1"
        if ($service == "FIXED-BANCOGANMXN1") {

            // Mensaje de éxito para el servicio FIXED-BANCOGANMXN1
            $textDeposito = "Éxito<br><br>Televentas en nuestra línea de WhatsApp <br><br>
 <a target='_blank' href='https://api.whatsapp.com/send?phone=5215538766165' style='font-weight: bold;'>+52 1 55 3876 6165</a>.<br><br> 
<br><br> 

Muchas gracias.";

            // Inicializa el array de datos
            $data = array();
            $data["dataUrl"] = "https://api.whatsapp.com/send?phone=5215538766165";
            $data["success"] = true;
            $data["userid"] = $Usuario->usuarioId;
            $data["amount"] = "";
            $data["dataText"] = $textDeposito;
            $data["dataImg"] = "https://images.virtualsoft.tech/m/msjT1676583027.png";
            $response = array();
            $response["code"] = 0;
            $response["rid"] = $json->rid;

            $response["data"] = $data;

            $sg = false;
        }

        // Verifica si el servicio es "FIXED-BANCOGANPEN1"
        if ($service == "FIXED-BANCOGANPEN1") {

            // Mensaje de éxito para el servicio FIXED-BANCOGANPEN1
            $textDeposito = "Éxito<br><br>Televentas en nuestra línea de whatsapp<br><br><a target='_blank' href='https://api.whatsapp.com/send/?phone=51920455056&text=Ol%C3%A1+gangabet+&type=phone_number&app_absent=0' style='font-weight: bold;'>+51920455056</a>.<br><br>Muchas gracias";

            // Inicializa el array de datos
            $data = array();
            $data["success"] = true;
            $data["userid"] = $Usuario->usuarioId;
            $data["amount"] = "";
            $data["dataUrl"] = "https://api.whatsapp.com/send/?phone=51920455056&text=Ol%C3%A1+gangabet+&type=phone_number&app_absent=0";
            $data["dataText"] = $textDeposito;
            $data["dataImg"] = "https://images.virtualsoft.tech/m/msjT1676583027.png";
            $response = array();
            $response["code"] = 0;
            $response["rid"] = $json->rid;

            $response["data"] = $data;

            $sg = false;
        }
        // Verifica el tipo de servicio y ejecuta la lógica correspondiente para FIXED-BANCOGANBRL1
        if ($service == "FIXED-BANCOGANBRL1") {

            // Mensaje de éxito para el depósito utilizando WhatsApp
            $textDeposito = "Éxito<br><br>Televentas en nuestra línea de WhatsApp <br><br>
 <a target='_blank' href='https://api.whatsapp.com/send/?phone=51920455056&text=Ol%C3%A1+gangabet+&type=phone_number&app_absent=0' style='font-weight: bold;'>+51920455056</a>.<br><br> 
<br><br> 

Muchas gracias.";

            $data = array();
            $data["dataUrl"] = "https://api.whatsapp.com/send/?phone=51920455056&text=Ol%C3%A1+gangabet+&type=phone_number&app_absent=0";
            $data["success"] = true;
            $data["userid"] = $Usuario->usuarioId;
            $data["amount"] = "";
            $data["dataText"] = $textDeposito;
            $data["dataImg"] = "https://images.virtualsoft.tech/m/msjT1676583027.png";
            $response = array();
            $response["code"] = 0;
            $response["rid"] = $json->rid;

            $response["data"] = $data;

            $sg = false;
        }

        // Verifica el tipo de servicio y ejecuta la lógica correspondiente para FIXED-BANCOGANVES1
        if ($service == "FIXED-BANCOGANVES1") {

            // Mensaje de éxito para el depósito utilizando WhatsApp
            $textDeposito = "Éxito<br><br>Televentas en nuestra línea de WhatsApp <br><br>
 <a target='_blank' href='https://api.whatsapp.com/send/?phone=584128020732&text=Hola+televentas+gangabet+&type=phone_number&app_absent=0' style='font-weight: bold;'>+584128020732</a>.<br><br> 
<br><br> 

Muchas gracias.";

            $data = array();
            $data["dataUrl"] = "https://api.whatsapp.com/send/?phone=584128020732&text=Hola+televentas+gangabet+&type=phone_number&app_absent=0";
            $data["success"] = true;
            $data["userid"] = $Usuario->usuarioId;
            $data["amount"] = "";
            $data["dataText"] = $textDeposito;
            $data["dataImg"] = "https://images.virtualsoft.tech/m/msjT1676583027.png";
            $response = array();
            $response["code"] = 0;
            $response["rid"] = $json->rid;

            $response["data"] = $data;

            $sg = false;
        }
    }

    if ($Usuario->mandante == '12') {

        if ($service == "FIXED-BANCOPOWER1") {
            $textDeposito = "⚪ Bancamiga: <br><br><b>Cuenta Corriente:</b> 0172-0194-81-1945109302<br><br>RIF: J-502821732<br><br>Beneficiario: Inversiones Master 777 C.A.<br><br>
 Querido usuario; una vez realices el depósito, debes enviar foto o capture del comprobante, acompañado de tu ID de usuario a nuestra línea de WhatsApp 
 <a target='_blank' href='https://api.whatsapp.com/send?phone=573106710157' style='font-weight: bold;'>+57 3106710157</a>.<br><br> 
Recuerda que el monto mínimo de recarga son 1 Dolar<br><br> 

Quedamos atentos, muchas gracias.";

            $data = array();
            $data["success"] = true;
            $data["userid"] = $Usuario->usuarioId;
            $data["amount"] = "";
            $data["dataText"] = $textDeposito;
            $data["dataImg"] = "https://images.virtualsoft.tech/m/msj0212T1700495910.png";
            $response = array();
            $response["code"] = 0;
            $response["rid"] = $json->rid;

            $response["data"] = $data;

            $sg = false;
        }

        // Se verifica el servicio y se prepara el texto y datos para el depósito para FIXED-BANCOPOWER2
        if ($service == "FIXED-BANCOPOWER2") {
            // Mensaje con los detalles del depósito para Banco de Venezuela
            $textDeposito = "⚪ Banco de Venezuela: <br><br><b>Cuenta Corriente:</b> 0102-0109-84-0000360274<br><br>RIF: J-502821732<br><br>Beneficiario: Inversiones Master 777 C.A.<br><br>
 Querido usuario; una vez realices el depósito, debes enviar foto o capture del comprobante, acompañado de tu ID de usuario a nuestra línea de WhatsApp 
 <a target='_blank' href='https://api.whatsapp.com/send?phone=573106710157' style='font-weight: bold;'>+57 3106710157</a>.<br><br> 
Recuerda que el monto mínimo de recarga son 5 Dolares<br><br> 

Quedamos atentos, muchas gracias.";

            $data = array();
            $data["success"] = true;
            $data["userid"] = $Usuario->usuarioId;
            $data["amount"] = "";
            $data["dataText"] = $textDeposito;
            $data["dataImg"] = "https://images.virtualsoft.tech/m/msjT1687560208.png";
            $response = array();
            $response["code"] = 0;
            $response["rid"] = $json->rid;

            $response["data"] = $data;

            $sg = false;
        }

        if ($service == "FIXED-BANCOPOWER3") {
            // Mensaje que se mostrará al usuario con información sobre el depósito
            $textDeposito = "⚪ Correo: <br><br><b>inver.2023corp@gmail.com:</b><br><br>Beneficiario Technology Services 2023 Corp.<br><br>
 Querido usuario; una vez realices el depósito, debes enviar foto o capture del comprobante, acompañado de tu ID de usuario a nuestra línea de WhatsApp 
 <a target='_blank' href='https://api.whatsapp.com/send?phone=573106710157' style='font-weight: bold;'>+57 3106710157</a>.<br><br> 
Recuerda que el monto mínimo de recarga son 10 Dolares<br><br> 

Quedamos atentos, muchas gracias.";

            // Inicializa un array para los datos de la respuesta
            $data = array();
            $data["success"] = true;
            $data["userid"] = $Usuario->usuarioId;
            $data["amount"] = "";
            $data["dataText"] = $textDeposito;
            $data["dataImg"] = "https://images.virtualsoft.tech/m/msjT1695925438.png";
            $response = array();
            $response["code"] = 0;
            $response["rid"] = $json->rid;

            $response["data"] = $data;

            $sg = false;
        }
    }
    if ($Usuario->mandante == '23') {
        // Verifica si el servicio solicitado es "FIXED-BANCOPANIPLAY1"
        if ($service == "FIXED-BANCOPANIPLAY1") {
            // Mensaje informativo sobre el proceso de depósito en puntos de venta
            $textDeposito = "Puntos de venta Lotería Nacional y Pulperías<br><br>
 <br>
Realiza tu pago en cualquiera de nuestros puntos de venta de Lotería Nacional o pulperías.<br><br>
Pasos para transaccionar:<br><br>
    1. Acércate a tu pulpería o punto de venta de lotería nacional más cercano a realizar tu deposito.<br><br>
    2. Indica tu ID de usuario (el cual aparece en la parte superior derecha dándole clic al icono de perfil) y el monto a depositar.
<br><br>
    Recuerda que los límites de depósitos son:  <br>
    Mínimo de depósito: L 10  <br>
    Máximo de depósito: L 13,000 <br>
<br><br> 3. Luego de realizado el pago se te entregará el comprobante de la transacción (ticket) y el depósito se verá reflejado en el saldo de tu cuenta Paniplay.” 
";

            // Inicializa el arreglo de datos para la respuesta
            $data = array();
            $data["success"] = true;
            $data["userid"] = $Usuario->usuarioId;
            $data["amount"] = "";
            $data["dataText"] = $textDeposito;
            $data["dataImg"] = "";
            $response = array();
            $response["code"] = 0;
            $response["rid"] = $json->rid;

            $response["data"] = $data;

            $sg = false;
        }
        // Verifica si el servicio es "FIXED-BANCOPANIPLAY2"
        if ($service == "FIXED-BANCOPANIPLAY2") {
            // Mensaje de depósito con instrucciones y datos del banco
            $textDeposito = "BAC CREDOMATIC:<br><br>
 
AHORROS: 753366391<br><br>
 
Querido usuario; una vez realices el depósito, debes enviar foto o capture del comprobante, acompañado de tu ID de usuario a nuestra línea de WhatsApp +504 3227 6723.<br><br>
 
Recuerda que el monto mínimo de recarga son 100 Lempiras<br><br>
 
Quedamos atentos, muchas gracias.";

            // Inicializa un array para los datos del depósito
            $data = array();
            $data["success"] = true;
            $data["userid"] = $Usuario->usuarioId;
            $data["amount"] = "";
            $data["dataText"] = $textDeposito;
            $data["dataImg"] = "https://images.virtualsoft.tech/m/msj23212T1710981323.png";
            $response = array();
            $response["code"] = 0;
            $response["rid"] = $json->rid;

            $response["data"] = $data;

            $sg = false;
        }
    }

    if ($Usuario->mandante == '20') {

        // Verifica el servicio y genera el texto de depósito para FIXED-BANCOSIVAR1
        if ($service == "FIXED-BANCOSIVAR1") {
            // Texto informativo para el depósito
            $textDeposito = "TIGOMONEY: <br><br><b>Cuenta Bancaria:</b> 75086519<br><br>
El depósito mínimo es de 5 USD máximo 300 USD<br><br>
Querido usuario; una vez realices el depósito, debes enviar foto o capture del comprobante, acompañado de tu ID de usuario a nuestra línea de WhatsApp 
 <a target='_blank' href='https://api.whatsapp.com/send?phone=50372090596' style='font-weight: bold;'>+503 7209 0596</a>.<br><br> 
<br><br> Para una correcta validación del depósito, se deberá tener en cuenta que los comprobantes tengan visible con claridad los siguientes datos:
<br>📌Resultado de transferencia completado <br>📌Numero de referencia del comprobante <br>📌Cuenta de Destino <br>📌Fecha <br>📌Monto
Quedamos atentos, muchas gracias.";

            // Inicializa el arreglo de datos
            $data = array();
            $data["success"] = true;
            $data["userid"] = $Usuario->usuarioId;
            $data["amount"] = "";
            $data["dataText"] = $textDeposito;
            $data["dataImg"] = "https://images.virtualsoft.tech/m/msjT1696272612.png";
            $response = array();
            $response["code"] = 0;
            $response["rid"] = $json->rid;

            $response["data"] = $data;

            $sg = false;
        }

        // Verifica el servicio y genera el texto de depósito para FIXED-BANCOSIVAR2
        if ($service == "FIXED-BANCOSIVAR2") {
            // Texto informativo para el depósito
            $textDeposito = "BANCO CUSCATLAM: <br><br><b>Cuenta Bancaria:</b> 316401000030873<br><br>
El deposito mínimo es de 5 USD<br><br>
Querido usuario; una vez realices el depósito, debes enviar foto o capture del comprobante, acompañado de tu ID de usuario a nuestra línea de WhatsApp 
 <a target='_blank' href='https://api.whatsapp.com/send?phone=50372090596' style='font-weight: bold;'>+503 7209 0596</a>.<br><br> 
<br><br> Para una correcta validación del depósito, se deberá tener en cuenta que los comprobantes tengan visible con claridad los siguientes datos:
<br>📌Resultado de transferencia completado <br>📌Numero de referencia del comprobante <br>📌Cuenta de Destino <br>📌Fecha <br>📌Monto
Quedamos atentos, muchas gracias.";

            // Inicializa el arreglo de datos
            $data = array();
            $data["success"] = true;
            $data["userid"] = $Usuario->usuarioId;
            $data["amount"] = "";
            $data["dataText"] = $textDeposito;
            $data["dataImg"] = "https://images.virtualsoft.tech/m/msjT1696272650.png";
            $response = array();
            $response["code"] = 0;
            $response["rid"] = $json->rid;

            $response["data"] = $data;

            $sg = false;
        }

        /**
         * Verifica el servicio y prepara el mensaje de depósito correspondiente.
         *
         * Si el servicio es "FIXED-BANCOSIVAR3", se establece un texto específico
         * para el depósito en el Banco Davivienda con detalles de las opciones
         * de cuentas y las instrucciones para el usuario.
         *
         * Si el servicio es "FIXED-BANCOSIVAR6", se establece otro texto con la
         * información apropiada para otro tipo de depósito en el mismo banco.
         */

        if ($service == "FIXED-BANCOSIVAR3") {
            $textDeposito = "BANCO DAVIVIENDA: <br><br>
Opción 1: ERNESTO ANTONIO MARTINEZ CEA Cuenta de Ahorros: 015541098937<br><br>

Opción 2: TONY GONZALO CAÑAS MARTINEZ Cuenta de Ahorros:  777542279317<br><br>


Querido usuario; una vez realices el depósito, debes enviar foto o capture del comprobante, acompañado de tu ID de usuario a nuestra línea de WhatsApp 
 <a target='_blank' href='https://api.whatsapp.com/send?phone=50372090596' style='font-weight: bold;'>+503 7209 0596</a>.<br><br>Tiempo de procesamiento de 10 a 20 minutos.<br><br> 
<br><br> Para una correcta validación del depósito, se deberá tener en cuenta que los comprobantes tengan visible con claridad los siguientes datos:
<br>📌Resultado de transferencia completado <br>📌Numero de referencia del comprobante <br>📌Cuenta de Destino <br>📌Fecha <br>📌Monto
Quedamos atentos, muchas gracias.";

            $data = array();
            $data["success"] = true;
            $data["userid"] = $Usuario->usuarioId;
            $data["amount"] = "";
            $data["dataText"] = $textDeposito;
            $data["dataImg"] = "https://images.virtualsoft.tech/m/msjT1696272685.png";
            $response = array();
            $response["code"] = 0;
            $response["rid"] = $json->rid;

            $response["data"] = $data;

            $sg = false;
        }
        if ($service == "FIXED-BANCOSIVAR6") {
            $textDeposito = "BANCO DAVIVIENDA: <br><br>TONY GONZALO CAÑAS MARTINEZ  <b>Cuenta de Ahorros:</b> 777542279317<br><br>
El depósito mínimo es de 5 USD máximo 50.000 USD<br><br>
Querido usuario; una vez realices el depósito, debes enviar foto o capture del comprobante, acompañado de tu ID de usuario a nuestra línea de WhatsApp 
 <a target='_blank' href='https://api.whatsapp.com/send?phone=50372090596' style='font-weight: bold;'>+503 7209 0596</a>.<br><br>Tiempo de procesamiento de 10 a 20 minutos.<br><br> 
<br><br> Para una correcta validación del depósito, se deberá tener en cuenta que los comprobantes tengan visible con claridad los siguientes datos:
<br>📌Resultado de transferencia completado <br>📌Numero de referencia del comprobante <br>📌Cuenta de Destino <br>📌Fecha <br>📌Monto
Quedamos atentos, muchas gracias.";

            $data = array();
            $data["success"] = true;
            $data["userid"] = $Usuario->usuarioId;
            $data["amount"] = "";
            $data["dataText"] = $textDeposito;
            $data["dataImg"] = "https://images.virtualsoft.tech/m/msjT1696272685.png";
            $response = array();
            $response["code"] = 0;
            $response["rid"] = $json->rid;

            $response["data"] = $data;

            $sg = false;
        }
        // Verifica el servicio requerido y asigna un mensaje específico de depósito para FIXED-BANCOSIVAR7
        if ($service == "FIXED-BANCOSIVAR7") {
            $textDeposito = "BANCO AGRICOLA: <br><br>ERNESTO ANTONIO MARTINEZ CEA<b>Cuenta de Ahorros:</b> 003110743524<br><br>
El deposito mínimo es de 5 USD<br><br>
Querido usuario; una vez realices el depósito, debes enviar foto o capture del comprobante, acompañado de tu ID de usuario a nuestra línea de WhatsApp 
 <a target='_blank' href='https://api.whatsapp.com/send?phone=50372090596' style='font-weight: bold;'>+503 7209 0596</a>.<br><br> 
<br><br> Para una correcta validación del depósito, se deberá tener en cuenta que los comprobantes tengan visible con claridad los siguientes datos:
<br>📌Resultado de transferencia completado <br>📌Numero de referencia del comprobante <br>📌Cuenta de Destino <br>📌Fecha <br>📌Monto
Quedamos atentos, muchas gracias.";

            $data = array();
            $data["success"] = true;
            $data["userid"] = $Usuario->usuarioId;
            $data["amount"] = "";
            $data["dataText"] = $textDeposito;
            $data["dataImg"] = "https://images.virtualsoft.tech/m/msjT1696272717.png";
            $response = array();
            $response["code"] = 0;
            $response["rid"] = $json->rid;

            $response["data"] = $data;

            $sg = false;
        }
        // Verifica el servicio requerido y asigna un mensaje específico de depósito para FIXED-BANCOSIVAR4
        if ($service == "FIXED-BANCOSIVAR4") {
            $textDeposito = "BANCO AGRICOLA: <br><br>
Opción 1: ERNESTO ANTONIO MARTINEZ CEA Cuenta de Ahorros: 003110743524<br><br>

Opción 2: TONY GONZALO CAÑAS MARTINEZ Cuenta de Ahorros: 003691201003<br><br>

El depósito mínimo es de 5 USD máximo 50.000 USD<br><br>
Querido usuario; una vez realices el depósito, debes enviar foto o capture del comprobante, acompañado de tu ID de usuario a nuestra línea de WhatsApp 
 <a target='_blank' href='https://api.whatsapp.com/send?phone=50372090596' style='font-weight: bold;'>+503 7209 0596</a>.<br><br> 
<br><br> Para una correcta validación del depósito, se deberá tener en cuenta que los comprobantes tengan visible con claridad los siguientes datos:
<br>📌Resultado de transferencia completado <br>📌Numero de referencia del comprobante <br>📌Cuenta de Destino <br>📌Fecha <br>📌Monto
Quedamos atentos, muchas gracias.";

            $data = array();
            $data["success"] = true;
            $data["userid"] = $Usuario->usuarioId;
            $data["amount"] = "";
            $data["dataText"] = $textDeposito;
            $data["dataImg"] = "https://images.virtualsoft.tech/m/msjT1696272717.png";
            $response = array();
            $response["code"] = 0;
            $response["rid"] = $json->rid;

            $response["data"] = $data;

            $sg = false;
        }

        // Verifica si el servicio es "FIXED-BANCOSIVAR5"
        if ($service == "FIXED-BANCOSIVAR5") {
            // Mensaje de depósito para CHIVO WALLET
            $textDeposito = "CHIVO WALLET: <br><br><b>Cuenta Bancaria:</b> 70361274<br><br>
El deposito mínimo es de 5 USD<br><br>
Querido usuario; una vez realices el depósito, debes enviar foto o capture del comprobante, acompañado de tu ID de usuario a nuestra línea de WhatsApp 
 <a target='_blank' href='https://api.whatsapp.com/send?phone=50372090596' style='font-weight: bold;'>+503 7209 0596</a>.<br><br> 
<br><br> Para una correcta validación del depósito, se deberá tener en cuenta que los comprobantes tengan visible con claridad los siguientes datos:
<br>📌Resultado de transferencia completado <br>📌Numero de referencia del comprobante <br>📌Cuenta de Destino <br>📌Fecha <br>📌Monto
Quedamos atentos, muchas gracias.";

            $data = array();
            $data["success"] = true;
            $data["userid"] = $Usuario->usuarioId;
            $data["amount"] = "";
            $data["dataText"] = $textDeposito;
            $data["dataImg"] = "https://images.virtualsoft.tech/m/msjT1696272791.png";
            $response = array();
            $response["code"] = 0;
            $response["rid"] = $json->rid;

            $response["data"] = $data;

            $sg = false;
        }
        // Verifica si el servicio es "FIXED-BANCOSIVAR8"
        if ($service == "FIXED-BANCOSIVAR8") {
            // Mensaje de depósito para TIGO MONEY
            $textDeposito = "TIGO MONEY: <br><br><b>Cuenta Bancaria:</b><br><br>
Opción 1: Cuenta: 75086519<br><br>
Opción 2: Cuenta: 60251164<br><br>
El deposito mínimo es de 5 USD<br><br>
Querido usuario; una vez realices el depósito, debes enviar foto o capture del comprobante, acompañado de tu ID de usuario a nuestra línea de WhatsApp 
 <a target='_blank' href='https://api.whatsapp.com/send?phone=50372090596' style='font-weight: bold;'>+503 7209 0596</a>.<br><br> 
<br><br> Para una correcta validación del depósito, se deberá tener en cuenta que los comprobantes tengan visible con claridad los siguientes datos:
<br>📌Resultado de transferencia completado <br>📌Numero de referencia del comprobante <br>📌Cuenta de Destino <br>📌Fecha <br>📌Monto
Quedamos atentos, muchas gracias.";

            $data = array();
            $data["success"] = true;
            $data["userid"] = $Usuario->usuarioId;
            $data["amount"] = "";
            $data["dataText"] = $textDeposito;
            $data["dataImg"] = "https://images.virtualsoft.tech/m/msj-1212T1714506490.png";
            $response = array();
            $response["code"] = 0;
            $response["rid"] = $json->rid;

            $response["data"] = $data;

            $sg = false;
        }
    }

    if ($sg) {
        if ($riskId != '') {

            // Inicializa la variable que representa el estado de riesgo
            $stateRisk = 0;

            try {
                // Crea una instancia de UsuarioAutomation con el identificador de riesgo proporcionado
                $UsuarioAutomation = new UsuarioAutomation($riskId);

                if ($UsuarioAutomation->estado == 'A') {
                    $stateRisk = 1;
                }

                if ($UsuarioAutomation->estado == 'R') {
                    $stateRisk = 2;
                }
                if ($stateRisk == 0) {
                    $date1 = strtotime($UsuarioAutomation->fechaCrea);
                    $date2 = time();
                    $mins = ($date2 - $date1) / 60;

                    // Verifica que la fecha de creación no esté vacía
                    if ($UsuarioAutomation->fechaCrea != '') {
                        if ($mins >= 1) {

                            $UsuarioAutomation->estado = 'R';

                            $UsuarioAutomationMySqlDAO = new UsuarioAutomationMySqlDAO();

                            $UsuarioAutomationMySqlDAO->update($UsuarioAutomation);

                            $UsuarioAutomationMySqlDAO->getTransaction()->commit();
                            $stateRisk = 2;
                        }
                    }
                }
            } catch (Exception $e) {
                // Manejo de excepciones (vacío, sin procesamiento)
            }

            /**
             * Verifica el estado de riesgo y define la respuesta en caso de que no sea 1.
             * Si el estado de riesgo es diferente de 1, se establece la variable $seguir como falsa,
             * se prepara un arreglo de datos con el estado de riesgo y se envía como respuesta en formato JSON.
             */
            if ($stateRisk != 1) {
                $seguir = false;
                $data[] = array('success' => false, 'stateRisk' => $stateRisk);


                $response["data"] = array(
                    "result" => 0,
                    "stateRisk" => $stateRisk,


                );
                print json_encode($response);

                exit();
            } else {
            }
        }

        // Definición del número máximo de filas a retornar
        $MaxRows = 1000;
        $OrderedItem = 1;
        $SkeepRows = 0;

        // Inicialización de las reglas de filtrado
        $rules = [];
        array_push($rules, array("field" => "producto.estado", "data" => "A", "op" => "eq"));
        array_push($rules, array("field" => "producto_mandante.estado", "data" => "A", "op" => "eq"));
        array_push($rules, array("field" => "proveedor.estado", "data" => "A", "op" => "eq"));
        array_push($rules, array("field" => "producto.estado", "data" => "A", "op" => "eq"));
        array_push($rules, array("field" => "producto_mandante.mandante", "data" => "$Usuario->mandante", "op" => "eq"));
        array_push($rules, array("field" => "producto_mandante.pais_id", "data" => "$Usuario->paisId", "op" => "eq"));
        array_push($rules, array("field" => "proveedor.tipo", "data" => "PAYMENT", "op" => "eq"));
        array_push($rules, array("field" => "producto.producto_id", "data" => "$service", "op" => "eq"));

        // Verifica si el usuario está en modo de prueba
        if ($Usuario->test == 'S') {
            // No se realiza ninguna acción en caso de que esté en prueba
        } else {
            // Verifica si el mandante es '0'
            if ($Usuario->mandante == '0') {

                array_push($rules, array("field" => "proveedor.proveedor_id", "data" => "45,41,50,87", "op" => "ni"));
            }
        }

        // Construcción del filtro de reglas
        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json2 = json_encode($filtro);

        $ProductoMandante = new ProductoMandante();

        $ProductoMandantes = $ProductoMandante->getProductosMandanteCustom(" producto.*,proveedor.*,producto_mandante.* ", "producto.orden", "DESC,producto.descripcion", $SkeepRows, $MaxRows, $json2, true);


        $ProductoMandantes = json_decode($ProductoMandantes);

        $ProductoMandantesData = array();

        $moneda = $Usuario->moneda;

        // Inicialización del array de respuesta
        $response = array();
        $response["code"] = 0;
        $response["rid"] = $json->rid;

        $URLREDIRECCION = "";

        if (oldCount($ProductoMandantes->data) > 0) {

            $producto = $ProductoMandantes->data[0];

            $ConfigurationEnvironment = new ConfigurationEnvironment();

            if ($ConfigurationEnvironment->isDevelopment() || $Usuario->mandante == '2' || $Usuario->mandante == '18' || $Usuario->mandante == '0') {


                /* Verifica el límite de depósito para un usuario específico y lanza una excepción si excede. */
                if ($Usuario->usuarioId == '438120') {
                    $UsuarioConfiguracion = new UsuarioConfiguracion();

                    $UsuarioConfiguracion->setUsuarioId('27915');
                    $result = $UsuarioConfiguracion->verifyLimitesDeposito($amount);


                    if ($result != '0') {
                        throw new Exception("Limite de deposito", $result);
                    }
                }

                /* Verifica límites de depósito y lanza excepción si se supera el límite permitido. */
                $UsuarioConfiguracion = new UsuarioConfiguracion();

                $UsuarioConfiguracion->setUsuarioId($UsuarioMandante->getUsuarioMandante());
                $result = $UsuarioConfiguracion->verifyLimitesDeposito($amount);

                if ($result != '0') {
                    throw new Exception("Limite de deposito", $result);
                }
            }


            /* valida límites de depósito mínimo y máximo, lanzando excepciones si se exceden. */
            if ($producto->{'producto_mandante.min'} > $amount && $producto->{'producto_mandante.min'} != '') {
                throw new Exception("Valor menor al minimo permitido para depositar", '21008');
            }

            if ($producto->{'producto_mandante.max'} < $amount && $producto->{'producto_mandante.max'} != 0 && $producto->{'producto_mandante.max'} != '') {
                throw new Exception("Valor mayor al máximo permitido para depositar", '21009');
            }


            /* valida límites de depósito para un producto y lanza excepciones si se incumplen. */
            if ($producto->{'producto_mandante.min'} > $amount) {
                throw new Exception("Valor menor al minimo permitido para depositar", '21008');
            }

            if ($producto->{'producto_mandante.max'} < $amount && $producto->{'producto_mandante.max'} != 0) {
                throw new Exception("Valor mayor al máximo permitido para depositar", '21009');
            }


            /* valida un monto de depósito comparándolo con límites mínimos y máximos. */
            if ($producto->{'producto.min'} > $amount) {
                throw new Exception("Valor menor al minimo permitido para depositar", '21008');
            }

            if ($producto->{'producto.max'} < $amount && $producto->{'producto.max'} != 0) {
                throw new Exception("Valor mayor al máximo permitido para depositar", '21009');
            }

            $Proveedor = new Proveedor($ProductoMandantes->data[0]->{'proveedor.proveedor_id'});
            $Producto = new Producto($ProductoMandantes->data[0]->{'producto.producto_id'});

            /*Código verifica estado de la cuenta bancaria, del banco y la relación entre producto y cuenta bancaria para la transacción (En caso de que aplique)*/
            if (!empty($bank_account_id)) {
                $rules = [];
                $rules[] = ["field" => "usuario_banco.usubanco_id", "data" => $bank_account_id, "op" => "eq"];
                $rules[] = ["field" => "usuario_banco.usuario_id", "data" => $Usuario->getUsuarioId(), "op" => "eq"];
                $rules[] = ["field" => "usuario_banco.estado", "data" => "A", "op" => "eq"];
                $rules[] = ["field" => "banco.estado", "data" => "A", "op" => "eq"];
                $rules[] = ["field" => "banco_detalle.estado", "data" => "A", "op" => "eq"];
                $rules[] = ["field" => "banco_detalle.producto_id", "data" => $Producto->productoId, "op" => "eq"];
                $rules[] = ["field" => "banco_detalle.pais_id", "data" => $Usuario->paisId, "op" => "eq"];
                $rules[] = ["field" => "banco_detalle.mandante", "data" => $Usuario->mandante, "op" => "eq"];

                $json = ["rules" => $rules, "groupOp" => "AND"];

                $BancoDetalle = new BancoDetalle();
                $bankDisponibilityString = $BancoDetalle->queryBancodetallesCustom("banco_detalle.bancodetalle_id", "banco_detalle.bancodetalle_id", "ASC", 0, 1, json_encode($json), true);
                $bankDisponibility = json_decode($bankDisponibilityString);

                if (($bankDisponibility->count[0])->{".count"} < 1) {
                    throw new Exception("Cuenta bancaria incompatible para transacción", 300179);
                }
            }



            // Lotosports maximo 5 transacciones por día
            if ($Usuario->mandante == '14') {


                /* Establece rangos de fechas y reglas para filtrar transacciones de productos. */
                $FromDateLocal = date("Y-m-d 00:00:00");
                $ToDateLocal = date("Y-m-d 23:59:59");
                $rules = [];

                array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "A", "op" => "eq"));
                array_push($rules, array("field" => "transaccion_producto.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
                //array_push($rules, array("field" => "proveedor.proveedor_id", "data" =>  $Proveedor->getProveedorId(), "op" => "eq"));


                /* Añade una regla a un array y establece parámetros de filtrado y paginación. */
                array_push($rules, array("field" => "transaccion_producto.usuario_id", "data" => $Usuario->usuarioId, "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $SkeepRows = 0;
                $OrderedItem = 1;
                $MaxRows = 100;


                /* convierte un filtro a JSON y obtiene transacciones personalizadas. */
                $json = json_encode($filtro);

                $TransaccionProducto = new TransaccionProducto();
                $transacciones = $TransaccionProducto->getTransaccionesCustom(" SUM(transaccion_producto.valor) valor,count(transaccion_producto.transproducto_id) count ", "transaccion_producto.transproducto_id", "asc", $SkeepRows, $MaxRows, $json, true);

                $transacciones = json_decode($transacciones);



                /* Valida límites de monto y cantidad de transacciones diarias, lanzando excepciones si se exceden. */
                if ((floatval($transacciones->data[0]->{'valor'})  + $amount) >= 50000) {
                    throw new Exception("Los usuarios solo pueden realizar recargas por valor maximo a 50000 por día.", '21021');
                }

                if ((floatval($transacciones->data[0]->{'count'})) >= 10) {
                    throw new Exception("Solo puedes realizar máximo diez (5) transacciones por día", '21023');
                }
            }


            /* Código que verifica condiciones antes de permitir un depósito de usuario específico. */
            if ($Usuario->mandante == '8' && $Producto->productoId == '11274') {

                if ($Usuario->verifcedulaAnt != 'S' || $Usuario->verifcedulaPost != 'S') {


                    if (100 < $amount) {
                        throw new Exception("Valor mayor al máximo permitido para depositar", '21009');
                    }
                }
            }

            if ($Proveedor->getAbreviado() == 'SFP' && $Usuario->mandante == '8' && false) {


                /* Define fechas y reglas para filtrar transacciones de productos en un sistema. */
                $FromDateLocal = date("Y-m-d 00:00:00");
                $ToDateLocal = date("Y-m-d 23:59:59");
                $rules = [];

                array_push($rules, array("field" => "transaccion_producto.estado", "data" => "A", "op" => "eq"));
                array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "E", "op" => "eq"));

                /* Se construye un filtro con reglas de comparación para una consulta de datos. */
                array_push($rules, array("field" => "transaccion_producto.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
                array_push($rules, array("field" => "proveedor.proveedor_id", "data" =>  $Proveedor->getProveedorId(), "op" => "eq"));

                array_push($rules, array("field" => "transaccion_producto.usuario_id", "data" => $Usuario->usuarioId, "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                /* Código configura variables para controlar eliminación de filas y procesamiento de datos en JSON. */
                $SkeepRows = 0;
                $OrderedItem = 1;
                $MaxRows = 100;

                $json = json_encode($filtro);

                $TransaccionProducto = new TransaccionProducto();

                /* verifica si hay solicitudes pendientes antes de procesar nuevas transacciones. */
                $transacciones = $TransaccionProducto->getTransaccionesCustom(" transaccion_producto.*,producto.*,usuario.moneda ", "transaccion_producto.transproducto_id", "asc", $SkeepRows, $MaxRows, $json, true);

                $transacciones = json_decode($transacciones);


                if (oldCount($transacciones->data) > 0) {
                    throw new Exception("Ya tiene solicitudes pendientes del día del proveedor por pagar. Revisa tu correo electornico para encontrar y pagar estas solicitudes.", '21020');
                }
            }

            if ($Usuario->verificado != 'S' && $Proveedor->getAbreviado() == 'INSWITCH' && $Usuario->mandante == '0'  && $Usuario->paisId == '66') {


                /* Define fechas y reglas para filtrar transacciones de productos en una consulta. */
                $FromDateLocal = date("Y-m-d 00:00:00");
                $ToDateLocal = date("Y-m-d 23:59:59");
                $rules = [];

                array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "A", "op" => "eq"));
                array_push($rules, array("field" => "transaccion_producto.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));

                /* Se crea un filtro con reglas para validar datos en transacciones. */
                array_push($rules, array("field" => "proveedor.proveedor_id", "data" =>  $Proveedor->getProveedorId(), "op" => "eq"));

                array_push($rules, array("field" => "transaccion_producto.usuario_id", "data" => $Usuario->usuarioId, "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $SkeepRows = 0;

                /* Código inicializa variables, convierte un filtro a JSON y crea una instancia de TransaccionProducto. */
                $OrderedItem = 1;
                $MaxRows = 100;

                $json = json_encode($filtro);

                $TransaccionProducto = new TransaccionProducto();

                /* Verifica transacciones de usuarios no verificados y limita a una por día. */
                $transacciones = $TransaccionProducto->getTransaccionesCustom(" transaccion_producto.*,producto.*,usuario.moneda ", "transaccion_producto.transproducto_id", "asc", $SkeepRows, $MaxRows, $json, true);

                $transacciones = json_decode($transacciones);

                if (oldCount($transacciones->data) > 0) {
                    throw new Exception("Los usuarios no verificados solo pueden realizar una (1) transacción por día.", '21021');
                }
            }
            if ($Proveedor->getAbreviado() == 'INSWITCH' && $Usuario->mandante == '0' && $Usuario->paisId == '94') {


                /* Define un rango de fechas y reglas para consultar transacciones de productos. */
                $FromDateLocal = date("Y-m-d 00:00:00");
                $ToDateLocal = date("Y-m-d 23:59:59");
                $rules = [];

                array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "A", "op" => "eq"));
                array_push($rules, array("field" => "transaccion_producto.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));

                /* Se crean reglas de filtrado para validar datos de proveedor y usuario. */
                array_push($rules, array("field" => "proveedor.proveedor_id", "data" =>  $Proveedor->getProveedorId(), "op" => "eq"));

                array_push($rules, array("field" => "transaccion_producto.usuario_id", "data" => $Usuario->usuarioId, "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $SkeepRows = 0;

                /* inicializa variables y convierte un filtro en formato JSON. */
                $OrderedItem = 1;
                $MaxRows = 100;

                $json = json_encode($filtro);


                $TransaccionProducto = new TransaccionProducto();

                /* Verifica el total de recargas diarias y lanza excepción si excede 1600. */
                $transacciones = $TransaccionProducto->getTransaccionesCustom(" SUM(transaccion_producto.valor) valor,count(transaccion_producto.transproducto_id) count ", "transaccion_producto.transproducto_id", "asc", $SkeepRows, $MaxRows, $json, true);

                $transacciones = json_decode($transacciones);


                if ((floatval($transacciones->data[0]->{'valor'})  + $amount) >= 1600) {
                    throw new Exception("Los usuarios solo pueden realizar recargas por valor maximo a 1600 por día.", '21021');
                }


                /* Lanza una excepción si el conteo de transacciones es 3 o más. */
                if ((floatval($transacciones->data[0]->{'count'})) >= 3) {
                    throw new Exception("Solo puedes realizar máximo diez (5) transacciones por día", '21023');
                }
            }


            if ($Usuario->mandante == '8' && false) {


                /* Se generan fechas límite y se establece una regla para filtrar productos. */
                $FromDateLocal = date("Y-m-d 00:00:00");
                $ToDateLocal = date("Y-m-d 23:59:59");
                $rules = [];

                //array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "A", "op" => "eq"));
                array_push($rules, array("field" => "transaccion_producto.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));

                /* Se construye un filtro con reglas para consultas sobre proveedores y usuarios. */
                array_push($rules, array("field" => "proveedor.proveedor_id", "data" =>  $Proveedor->getProveedorId(), "op" => "eq"));

                array_push($rules, array("field" => "transaccion_producto.usuario_id", "data" => $Usuario->usuarioId, "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $SkeepRows = 0;

                /* inicializa variables y convierte un filtro a formato JSON para una transacción. */
                $OrderedItem = 1;
                $MaxRows = 100;

                $json = json_encode($filtro);

                $TransaccionProducto = new TransaccionProducto();

                /* Controla la cantidad de transacciones diarias permitidas para un usuario específico. */
                $transacciones = $TransaccionProducto->getTransaccionesCustom(" transaccion_producto.*,producto.*,usuario.moneda ", "transaccion_producto.transproducto_id", "asc", $SkeepRows, $MaxRows, $json, true);

                $transacciones = json_decode($transacciones);

                if ($Usuario->verifcedulaAnt != 'S' || $Usuario->verifcedulaPost != 'S') {

                    if (oldCount($transacciones->data) > 9) {
                        throw new Exception("Solo puedes realizar máximo diez (10) transacciones por día", '21023');
                    }
                }
            }

            if ($Proveedor->getAbreviado() == 'PAYPHONE' && $Usuario->mandante == '8' && false) {


                /* Establece fechas y reglas para filtrar productos en transacciones. */
                $FromDateLocal = date("Y-m-d 00:00:00");
                $ToDateLocal = date("Y-m-d 23:59:59");
                $rules = [];

                array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "A", "op" => "eq"));
                array_push($rules, array("field" => "transaccion_producto.fecha_crea", "data" => "$FromDateLocal", "op" => "ge"));

                /* Se construye un filtro con condiciones para consultas sobre proveedores y usuarios. */
                array_push($rules, array("field" => "proveedor.proveedor_id", "data" =>  $Proveedor->getProveedorId(), "op" => "eq"));

                array_push($rules, array("field" => "transaccion_producto.usuario_id", "data" => $Usuario->usuarioId, "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $SkeepRows = 0;

                /* Se establece un límite de filas y se codifica un filtro en JSON. */
                $OrderedItem = 1;
                $MaxRows = 100;

                $json = json_encode($filtro);

                $TransaccionProducto = new TransaccionProducto();

                /* Se obtienen transacciones y se limita a dos diarias para usuarios no verificados. */
                $transacciones = $TransaccionProducto->getTransaccionesCustom(" transaccion_producto.*,producto.*,usuario.moneda ", "transaccion_producto.transproducto_id", "asc", $SkeepRows, $MaxRows, $json, true);

                $transacciones = json_decode($transacciones);

                if ($Usuario->verifcedulaAnt != 'S' || $Usuario->verifcedulaPost != 'S') {

                    if (oldCount($transacciones->data) > 1) {
                        throw new Exception("Los usuarios no verificados solo pueden realizar dos (2) transacción por día.", '21021');
                    }
                } else {
                    /* lanza una excepción si se exceden tres transacciones diarias. */


                    if (oldCount($transacciones->data) > 2) {
                        throw new Exception("Solo puedes realizar máximo tres (3) transacciones por día.", '21022');
                    }
                }
            }

            if ($Proveedor->getAbreviado() == 'PAYPHONE' && $Usuario->mandante == '8') {



                /* configura filtros para consultar transacciones de productos en una base de datos. */
                $FromDateLocal = date("Y-m-01 00:00:00");
                $ToDateLocal = date("Y-m-d 23:59:59");
                /* $rules = [];

            array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "A", "op" => "eq"));
            array_push($rules, array("field" => "transaccion_producto.fecha_crea", "data" => "$FromDateLocal", "op" => "ge"));
            array_push($rules, array("field" => "transaccion_producto.usuario_id", "data" => $Usuario->usuarioId, "op" => "eq"));
            array_push($rules, array("field" => "proveedor.proveedor_id", "data" => $Proveedor->getProveedorId(), "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $SkeepRows = 0;
            $OrderedItem = 1;
            $MaxRows = 100;

            $json = json_encode($filtro);

            $TransaccionProducto = new TransaccionProducto();
            $transacciones = $TransaccionProducto->getTransaccionesCustom(" SUM(transaccion_producto.valor) valor ", "transaccion_producto.transproducto_id", "asc", $SkeepRows, $MaxRows, $json, true);
            */

                $rules = [];

                /* Se construye un filtro JSON con reglas para consultas basadas en condiciones específicas. */
                array_push($rules, array("field" => "proveedor.proveedor_id", "data" =>  $Proveedor->getProveedorId(), "op" => "eq"));
                array_push($rules, array("field" => "usuario_recarga.usuario_id", "data" => $Usuario->usuarioId, "op" => "eq"));
                array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => $FromDateLocal, "op" => "ge"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                $json = json_encode($filtro);


                /* Suma los valores de recarga por usuario y convierte el resultado a JSON. */
                $select = " SUM(usuario_recarga.valor) valor ";

                $UsuarioRecarga = new UsuarioRecarga();

                $transacciones = $UsuarioRecarga->getUsuarioRecargasCustom($select, "usuario_recarga.usuario_id", "asc", 0, 1, $json, true);


                $transacciones = json_decode($transacciones);


                /* Valida que la suma de recargas diarias no exceda 1000; genera excepción si lo hace. */
                if ((floatval($transacciones->data[0]->{'valor'})  + $amount) >= 1000) {
                    throw new Exception("Los usuarios solo pueden realizar recargas por valor maximo a 1000 por día.", '21021');
                }
            }
            if ($Proveedor->getAbreviado() == 'DIRECTA24' && $Usuario->mandante == '0' && $Usuario->paisId == '66') {


                /* Define fechas de inicio y fin del día actual para un conjunto de reglas. */
                $FromDateLocal = date("Y-m-d 00:00:00");
                $ToDateLocal = date("Y-m-d 23:59:59");


                $rules = [];
                array_push($rules, array("field" => "proveedor.proveedor_id", "data" =>  $Proveedor->getProveedorId(), "op" => "eq"));

                /* Crea un filtro en formato JSON con condiciones para consultar usuarios. */
                array_push($rules, array("field" => "usuario_recarga.usuario_id", "data" => $Usuario->usuarioId, "op" => "eq"));
                array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => $FromDateLocal, "op" => "ge"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                $json = json_encode($filtro);


                /* obtiene recargas de usuario y las decodifica en formato JSON. */
                $select = " usuario_recarga.recarga_id ";

                $UsuarioRecarga = new UsuarioRecarga();

                $transacciones = $UsuarioRecarga->getUsuarioRecargasCustom($select, "usuario_recarga.usuario_id", "asc", 0, 1, $json, true);

                $transacciones = json_decode($transacciones);


                /* Lanza una excepción si se superan tres transacciones diarias permitidas. */
                if (oldCount($transacciones->data) > 2) {
                    throw new Exception("Solo puedes realizar máximo tres (3) transacciones por día.", '21022');
                }
            }

            if ($Proveedor->getAbreviado() == 'DIRECTA24' && $Usuario->mandante == '8') {


                /* Se definen intervalos de fecha local y se inicializa un arreglo vacío. */
                $FromDateLocal = date("Y-m-d 00:00:00");
                $ToDateLocal = date("Y-m-d 23:59:59");




                $rules = [];

                /* Construye un filtro de reglas en formato JSON para consultas. */
                array_push($rules, array("field" => "proveedor.proveedor_id", "data" =>  $Proveedor->getProveedorId(), "op" => "eq"));
                array_push($rules, array("field" => "usuario_recarga.usuario_id", "data" => $Usuario->usuarioId, "op" => "eq"));
                array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => $FromDateLocal, "op" => "ge"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                $json = json_encode($filtro);


                /* Selecciona y decodifica en JSON las recargas de un usuario específico. */
                $select = " usuario_recarga.recarga_id ";

                $UsuarioRecarga = new UsuarioRecarga();

                $transacciones = $UsuarioRecarga->getUsuarioRecargasCustom($select, "usuario_recarga.usuario_id", "asc", 0, 1, $json, true);

                $transacciones = json_decode($transacciones);



                /* Controla transacciones de usuarios no verificados, limitando a dos por día. */
                if ($Usuario->verifcedulaAnt != 'S' || $Usuario->verifcedulaPost != 'S') {

                    if (oldCount($transacciones->data) > 1) {
                        throw new Exception("Los usuarios no verificados solo pueden realizar dos (2) transacción por día.", '21021');
                    }
                } else {
                    /* Lanza una excepción si se superan tres transacciones diarias permitidas. */


                    if (oldCount($transacciones->data) > 2) {
                        throw new Exception("Solo puedes realizar máximo tres (3) transacciones por día.", '21022');
                    }
                }
            }
            if ($Proveedor->getAbreviado() == 'INSWITCH' && $Usuario->mandante == '8') {


                /* Define fechas de inicio y fin del día actual en formato local. */
                $FromDateLocal = date("Y-m-d 00:00:00");
                $ToDateLocal = date("Y-m-d 23:59:59");




                $rules = [];

                /* Se construyen reglas de filtrado y se convierten a formato JSON. */
                array_push($rules, array("field" => "proveedor.proveedor_id", "data" =>  $Proveedor->getProveedorId(), "op" => "eq"));
                array_push($rules, array("field" => "usuario_recarga.usuario_id", "data" => $Usuario->usuarioId, "op" => "eq"));
                array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => $FromDateLocal, "op" => "ge"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                $json = json_encode($filtro);


                /* calcula el total y cuenta recargas de usuarios. */
                $select = " SUM(usuario_recarga.valor) valor, count(usuario_recarga.recarga_id) count ";

                $UsuarioRecarga = new UsuarioRecarga();

                $transacciones = $UsuarioRecarga->getUsuarioRecargasCustom($select, "usuario_recarga.usuario_id", "asc", 0, 1, $json, true);

                $transacciones = json_decode($transacciones);


                /* Verifica condiciones de recarga y limita transacciones para usuarios no verificados. */
                if ($Usuario->verifcedulaAnt != 'S' || $Usuario->verifcedulaPost != 'S') {

                    if ((floatval($transacciones->data[0]->{'.valor'})  + $amount) > 300) {
                        throw new Exception("Los usuarios solo pueden realizar recargas por valor maximo a 300 por día.", '21021');
                    }

                    if (($transacciones->data[0]->{'.count'}) > 1) {
                        throw new Exception("Los usuarios no verificados solo pueden realizar dos (2) transacción por día.", '21021');
                    }
                } else {
                    /* Valida transacciones diarias, limitando montos y cantidad de recargas permitidas. */

                    if ((floatval($transacciones->data[0]->{'.valor'})  + $amount) > 300) {
                        throw new Exception("Los usuarios solo pueden realizar recargas por valor maximo a 300 por día.", '21021');
                    }

                    if (($transacciones->data[0]->{'.count'}) > 2) {
                        throw new Exception("Solo puedes realizar máximo tres (3) transacciones por día.", '21022');
                    }
                }
            }


            if ($Proveedor->getAbreviado() == 'SFP' && $Usuario->mandante == '8') {



                /* define fechas de inicio y fin en formato local y crea un array vacío. */
                $FromDateLocal = date("Y-m-d 00:00:00");
                $ToDateLocal = date("Y-m-d 23:59:59");



                $rules = [];

                /* Se construye un filtro JSON con reglas para consultas de datos. */
                array_push($rules, array("field" => "proveedor.proveedor_id", "data" =>  $Proveedor->getProveedorId(), "op" => "eq"));
                array_push($rules, array("field" => "usuario_recarga.usuario_id", "data" => $Usuario->usuarioId, "op" => "eq"));
                array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => $FromDateLocal, "op" => "ge"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                $json = json_encode($filtro);


                /* suma valores de recargas de usuarios y decodifica el resultado JSON. */
                $select = " SUM(usuario_recarga.valor) valor ";

                $UsuarioRecarga = new UsuarioRecarga();

                $transacciones = $UsuarioRecarga->getUsuarioRecargasCustom($select, "usuario_recarga.usuario_id", "asc", 0, 1, $json, true);

                $transacciones = json_decode($transacciones);


                /* Verifica si la fecha de creación del usuario es hoy y valida su transacción. */
                if (strpos($Usuario->fechaCrea, date("Y-m-d")) !== false) {

                    if ((floatval($transacciones->data[0]->{'.valor'})  + $amount) > 500) {
                        throw new Exception("Los usuarios solo pueden realizar recargas por valor maximo a 2000 por día.", '21021');
                    }
                } else {
                    /* Verifica si la recarga diaria excede 2000 y lanza una excepción si es así. */


                    if ((floatval($transacciones->data[0]->{'.valor'})  + $amount) >= 2000) {
                        throw new Exception("Los usuarios solo pueden realizar recargas por valor maximo a 2000 por día.", '21021');
                    }
                }
            }

            if ($Usuario->usuarioId == '1314023') {


                /* Código que establece fechas y reglas para filtrar datos de transacciones. */
                $FromDateLocal = date("Y-m-d 00:00:00");
                $ToDateLocal = date("Y-m-d 23:59:59");
                $rules = [];

                array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "A", "op" => "eq"));
                array_push($rules, array("field" => "transaccion_producto.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));

                /* Define un conjunto de reglas de filtrado para consultas en base de datos. */
                array_push($rules, array("field" => "proveedor.proveedor_id", "data" =>  $Proveedor->getProveedorId(), "op" => "eq"));

                array_push($rules, array("field" => "transaccion_producto.usuario_id", "data" => $Usuario->usuarioId, "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $SkeepRows = 0;

                /* Se define una variable, se convierte un filtro a JSON y se instancia un objeto. */
                $OrderedItem = 1;
                $MaxRows = 100;

                $json = json_encode($filtro);

                $TransaccionProducto = new TransaccionProducto();

                /* limita a tres transacciones diarias y lanza una excepción si se excede. */
                $transacciones = $TransaccionProducto->getTransaccionesCustom(" transaccion_producto.*,producto.*,usuario.moneda ", "transaccion_producto.transproducto_id", "asc", $SkeepRows, $MaxRows, $json, true);

                $transacciones = json_decode($transacciones);


                if (oldCount($transacciones->data) > 2) {
                    throw new Exception("Solo puedes realizar máximo tres (3) transacciones por día.", '21022');
                }
            }


            if ($Proveedor->getAbreviado() == 'PAYCIPS' && $Usuario->mandante == '13') {


                /* Establece fechas y reglas para filtrar productos activos en transacciones. */
                $FromDateLocal = date("Y-m-d 00:00:00");
                $ToDateLocal = date("Y-m-d 23:59:59");
                $rules = [];

                array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "A", "op" => "eq"));
                array_push($rules, array("field" => "transaccion_producto.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));

                /* Se definen reglas de filtro para una consulta utilizando condiciones de igualdad. */
                array_push($rules, array("field" => "proveedor.proveedor_id", "data" =>  $Proveedor->getProveedorId(), "op" => "eq"));

                array_push($rules, array("field" => "transaccion_producto.usuario_id", "data" => $Usuario->usuarioId, "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $SkeepRows = 0;

                /* Se inicializan variables y se convierte un filtro a formato JSON en PHP. */
                $OrderedItem = 1;
                $MaxRows = 100;

                $json = json_encode($filtro);

                $TransaccionProducto = new TransaccionProducto();

                /* Validación de transacciones para usuarios no verificados, limitando a dos por día. */
                $transacciones = $TransaccionProducto->getTransaccionesCustom(" transaccion_producto.*,producto.*,usuario.moneda ", "transaccion_producto.transproducto_id", "asc", $SkeepRows, $MaxRows, $json, true);

                $transacciones = json_decode($transacciones);

                if (oldCount($transacciones->data) >= 1) {
                    throw new Exception("Los usuarios no verificados solo pueden realizar dos (2) transacción por día.", '21021');
                }
            }

            if (($Proveedor->getAbreviado() == 'SFP' || $Proveedor->getAbreviado() == 'PAYPHONE' || $Proveedor->getAbreviado() == 'PEFECTIVO' || $Proveedor->getAbreviado() == 'DIRECTA24') && $Usuario->mandante == '8') {


                /* genera filtros para consultar transacciones de productos en una base de datos. */
                $FromDateLocal = date("Y-m-d 00:00:00");
                $ToDateLocal = date("Y-m-d 23:59:59");
                /*$rules = [];

            //array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "A", "op" => "eq"));
            array_push($rules, array("field" => "transaccion_producto.fecha_crea", "data" => "$FromDateLocal", "op" => "ge"));
            array_push($rules, array("field" => "proveedor.proveedor_id", "data" =>  $Proveedor->getProveedorId(), "op" => "eq"));

            array_push($rules, array("field" => "transaccion_producto.usuario_id", "data" => $Usuario->usuarioId, "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $SkeepRows = 0;
            $OrderedItem = 1;
            $MaxRows = 100;

            $json = json_encode($filtro);

            $TransaccionProducto = new TransaccionProducto();
            $transacciones = $TransaccionProducto->getTransaccionesCustom(" transaccion_producto.*,producto.*,usuario.moneda ", "transaccion_producto.transproducto_id", "asc", $SkeepRows, $MaxRows, $json, true);*/

                $SkeepRows = 0;

                /* Código para definir reglas de filtrado en una consulta relacionada con proveedores y usuarios. */
                $OrderedItem = 1;
                $MaxRows = 100;

                $rules = [];
                array_push($rules, array("field" => "proveedor.proveedor_id", "data" =>  $Proveedor->getProveedorId(), "op" => "eq"));
                array_push($rules, array("field" => "usuario_recarga.usuario_id", "data" => $Usuario->usuarioId, "op" => "eq"));

                /* Se construye un filtro JSON para consultas en una base de datos. */
                array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => $FromDateLocal, "op" => "ge"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                $json = json_encode($filtro);

                $select = " usuario_recarga.* ";


                /* Se obtienen y decodifican transacciones de usuario en formato JSON. */
                $UsuarioRecarga = new UsuarioRecarga();

                $transacciones = $UsuarioRecarga->getUsuarioRecargasCustom($select, "usuario_recarga.usuario_id", "asc", $SkeepRows, $MaxRows, $json, true);


                $transacciones = json_decode($transacciones);


                /* verifica si los depósitos diarios exceden un límite y lanza una excepción. */
                if (oldCount($transacciones->data) >= 10) {
                    throw new Exception("Ha superado la cantidad maxima de depositos por día por este medio.", '21023');
                }
            }

            if (($Proveedor->getAbreviado() == 'SFP' || $Proveedor->getAbreviado() == 'PAYPHONE' || $Proveedor->getAbreviado() == 'PEFECTIVO' || $Proveedor->getAbreviado() == 'DIRECTA24') && $Usuario->mandante == '8') {



                /* Crea un filtro en PHP para consultar transacciones de productos específicas. */
                $FromDateLocal = date("Y-m-01 00:00:00");
                $ToDateLocal = date("Y-m-d 23:59:59");
                /* $rules = [];

            array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "A", "op" => "eq"));
            array_push($rules, array("field" => "transaccion_producto.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
            array_push($rules, array("field" => "transaccion_producto.usuario_id", "data" => $Usuario->usuarioId, "op" => "eq"));
            array_push($rules, array("field" => "proveedor.proveedor_id", "data" =>  $Proveedor->getProveedorId(), "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $SkeepRows = 0;
            $OrderedItem = 1;
            $MaxRows = 100;

            $json = json_encode($filtro);

            $TransaccionProducto = new TransaccionProducto();
            $transacciones = $TransaccionProducto->getTransaccionesCustom(" SUM(transaccion_producto.valor) valor ", "transaccion_producto.transproducto_id", "asc", $SkeepRows, $MaxRows, $json, true);*/


                $rules = [];

                /* Se crea un filtro con reglas en formato JSON para datos de proveedores y usuarios. */
                array_push($rules, array("field" => "proveedor.proveedor_id", "data" =>  $Proveedor->getProveedorId(), "op" => "eq"));
                array_push($rules, array("field" => "usuario_recarga.usuario_id", "data" => $Usuario->usuarioId, "op" => "eq"));
                array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => $FromDateLocal, "op" => "ge"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                $json = json_encode($filtro);


                /* Calcula la suma de recargas de un usuario y convierte el resultado a JSON. */
                $select = " SUM(usuario_recarga.valor) valor ";

                $UsuarioRecarga = new UsuarioRecarga();

                $transacciones = $UsuarioRecarga->getUsuarioRecargasCustom($select, "usuario_recarga.usuario_id", "asc", 0, 1, $json, true);


                $transacciones = json_decode($transacciones);


                /* Verifica si la suma de valores excede 1000 y lanza una excepción si es así. */
                if ((floatval($transacciones->data[0]->{'valor'})  + $amount) >= 1000) {
                    throw new Exception("Los usuarios solo pueden realizar recargas por valor maximo a 1000 por día.", '21021');
                }
            }

            if ($Usuario->mandante == '2') {


                /* Genera un rango de fechas y reglas para filtrar transacciones de productos. */
                $FromDateLocal = date("Y-m-d 00:00:00");
                $ToDateLocal = date("Y-m-d 23:59:59");
                $rules = [];

                array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "A", "op" => "eq"));
                array_push($rules, array("field" => "transaccion_producto.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));

                /* Se crean reglas de filtrado para consultas agrupadas en un array. */
                array_push($rules, array("field" => "proveedor.proveedor_id", "data" =>  $Proveedor->getProveedorId(), "op" => "eq"));

                array_push($rules, array("field" => "transaccion_producto.usuario_id", "data" => $Usuario->usuarioId, "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $SkeepRows = 0;

                /* inicializa variables, convierte datos a JSON y crea un objeto de transacción. */
                $OrderedItem = 1;
                $MaxRows = 100;

                $json = json_encode($filtro);

                $TransaccionProducto = new TransaccionProducto();

                /* Valida que la suma de transacciones y un monto no supere 40,000 diarios. */
                $transacciones = $TransaccionProducto->getTransaccionesCustom(" SUM(transaccion_producto.valor) valor ", "transaccion_producto.transproducto_id", "asc", $SkeepRows, $MaxRows, $json, true);

                $transacciones = json_decode($transacciones);

                if ((floatval($transacciones->data[0]->{'valor'})  + $amount) >= 40000) {
                    throw new Exception("Los usuarios solo pueden realizar recargas por valor maximo a 40000 por día.", '21021');
                }
            }

            if ($Usuario->usuarioId == '1220601') {


                /* define fechas y condiciones para filtrar transacciones de productos. */
                $FromDateLocal = date("Y-m-d 00:00:00");
                $ToDateLocal = date("Y-m-d 23:59:59");
                $rules = [];

                array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "A", "op" => "eq"));
                array_push($rules, array("field" => "transaccion_producto.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));

                /* Se define un filtro de reglas para consultas con usuarios en una transacción. */
                array_push($rules, array("field" => "transaccion_producto.usuario_id", "data" => $Usuario->usuarioId, "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $SkeepRows = 0;
                $OrderedItem = 1;
                $MaxRows = 100;


                /* convierte un filtro a JSON y obtiene transacciones personalizadas. */
                $json = json_encode($filtro);

                $TransaccionProducto = new TransaccionProducto();
                $transacciones = $TransaccionProducto->getTransaccionesCustom(" SUM(transaccion_producto.valor) valor ", "transaccion_producto.transproducto_id", "asc", $SkeepRows, $MaxRows, $json, true);

                $transacciones = json_decode($transacciones);


                /* Valida si la suma excede 1000 y lanza una excepción si es así. */
                if ((floatval($transacciones->data[0]->{'valor'})  + $amount) >= 1000) {
                    throw new Exception("Los usuarios solo pueden realizar recargas por valor maximo a 1000 por día.", '21021');
                }
            }
            if ($Usuario->usuarioId == '23955') {


                /* Define fechas y reglas para filtrar productos según su estado y fecha de creación. */
                $FromDateLocal = date("Y-m-d 00:00:00");
                $ToDateLocal = date("Y-m-d 23:59:59");
                $rules = [];

                array_push($rules, array("field" => "transaccion_producto.estado_producto", "data" => "A", "op" => "eq"));
                array_push($rules, array("field" => "transaccion_producto.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));

                /* Se construye un filtro para aplicar reglas en una consulta de datos. */
                array_push($rules, array("field" => "transaccion_producto.usuario_id", "data" => $Usuario->usuarioId, "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $SkeepRows = 0;
                $OrderedItem = 1;
                $MaxRows = 100;


                /* procesa datos de transacciones y los convierte a JSON para su uso. */
                $json = json_encode($filtro);

                $TransaccionProducto = new TransaccionProducto();
                $transacciones = $TransaccionProducto->getTransaccionesCustom(" SUM(transaccion_producto.valor) valor ", "transaccion_producto.transproducto_id", "asc", $SkeepRows, $MaxRows, $json, true);

                $transacciones = json_decode($transacciones);


                /* Verifica si la recarga supera 1000 y lanza excepción si es el caso. */
                if ((floatval($transacciones->data[0]->{'valor'})  + $amount) >= 1000) {
                    throw new Exception("Los usuarios solo pueden realizar recargas por valor maximo a 1000 por día.", '21021');
                }
            }

            switch ($Proveedor->getAbreviado()) {

                case 'LPG':
                    /* Se crea un pago para LPG y se redirige según el resultado. */


                    $LPGSERVICES = new Backend\integrations\payment\LPGSERVICES();

                    $data = $LPGSERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url);

                    $URLREDIRECCION = $data;
                    break;

                case 'ASTROPAY':
                    /* Crea una solicitud de pago con Astropay y redirige según el resultado. */


                    $ASTROPAYSERVICES = new Backend\integrations\payment\ASTROPAYSERVICES();

                    $data = $ASTROPAYSERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url);

                    $URLREDIRECCION = $data;
                    break;

                case 'NUVEI':
                    /* gestiona solicitudes de pago para diferentes métodos a través de NUVEISERVICES. */


                    $NUVEISERVICES = new Backend\integrations\payment\NUVEISERVICES();
                    switch ($Producto->getExternoId()) {
                        case "Tarjetas":
                            $data = $NUVEISERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url, $cancel_url);
                            break;
                        case "TF":
                            $data = $NUVEISERVICES->createRequestPayment2($Usuario, $Producto, $amount, $success_url, $fail_url, $cancel_url);
                            break;
                        case "OXXOPAY":
                            $data = $NUVEISERVICES->createRequestPaymentOXXOPAY($Usuario, $Producto, $amount, $success_url, $fail_url, $cancel_url);
                            break;
                    }


                    $URLREDIRECCION = $data;
                    break;

                case 'N1CO':
                    /* Manejo de pagos y redirección según el tipo de producto en un sistema backend. */


                    $N1COSERVICES = new Backend\integrations\payment\N1COSERVICES();
                    if ($Producto->getExternoId() == '0') {
                        $data = $N1COSERVICES->createRequestPayment2($Usuario, $Producto, $amount, $success_url, $fail_url, $cancel_url);
                    }
                    if ($Producto->getExternoId() == 'CHECKOUT') {
                        $data = $N1COSERVICES->createRequestCheckout($Usuario, $Producto, $amount, $success_url, $fail_url, $cancel_url);
                    }
                    $URLREDIRECCION = $data;
                    break;

                case 'ASTPAYCARD':
                    /* crea un objeto de servicios de pago y procesa una solicitud de pago. */


                    $ASTROPAYSERVICES = new Backend\integrations\payment\ASTROPAYCARDSERVICES();

                    $data = $ASTROPAYSERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url);

                    $URLREDIRECCION = $data;
                    break;

                case 'SMARTFASTPAY':
                    /* Se crea una solicitud de pago usando SMARTFASTPAY y se guarda la URL de redirección. */


                    $SMARTFASTPAYSERVICES = new Backend\integrations\payment\SMARTFASTPAYSERVICES();

                    $data = $SMARTFASTPAYSERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url);

                    $URLREDIRECCION = $data;
                    break;

                case 'SFP':
                    /* Se crea una solicitud de pago usando SAFETYSERVICES y se almacena la URL de redirección. */


                    $SAFETYSERVICES = new Backend\integrations\payment\SAFETYSERVICES();

                    $data = $SAFETYSERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url);

                    $URLREDIRECCION = $data;
                    break;

                case 'SINPEMOVIL':
                    /* Crea un pago usando el servicio SINPEMOVIL y redirige a una URL específica. */


                    $SINPEMOVILSERVICES = new Backend\integrations\payment\SINPEMOVILSERVICES();

                    $data = $SINPEMOVILSERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url);

                    $URLREDIRECCION = $data;
                    break;

                case 'PAGOEFECTIVO':
                    /* Se crea una solicitud de pago efectivo según el entorno (desarrollo o producción). */


                    $PAGOEFECTIVOSERVICES = new Backend\integrations\payment\PAGOEFECTIVOSERVICES();


                    if ($ConfigurationEnvironment->isDevelopment()) {
                        $data = $PAGOEFECTIVOSERVICES->createRequestPayment2($Usuario, $Producto, $amount, $success_url, $fail_url);
                    } else {
                        $data = $PAGOEFECTIVOSERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url);
                    }


                    $URLREDIRECCION = $data;
                    break;

                case 'PEFECTIVO':
                    /* Código para manejar pagos en efectivo con diferentes condiciones de usuario. */


                    $PAGOEFECTIVOSERVICES = new Backend\integrations\payment\PAGOEFECTIVOSERVICES();
                    if ($Usuario->usuarioId == 886 || true) {
                        $data = $PAGOEFECTIVOSERVICES->createRequestPayment2($Usuario, $Producto, $amount, $success_url, $fail_url);
                    } else {

                        $data = $PAGOEFECTIVOSERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url);
                    }
                    $URLREDIRECCION = $data;
                    break;

                case 'PAYPAL':
                    /* Código para procesar pagos con PayPal y redirigir a la URL correspondiente. */


                    $PAYPALSERVICES = new Backend\integrations\payment\PAYPALSERVICES();

                    $data = $PAYPALSERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url);

                    $URLREDIRECCION = $data;
                    break;


                case 'VISA':

                    if (false) {

                        /* asigna un ID específico si el usuario es el mandante 16. */
                        $ObjAutomation = [];

                        $usuarioMandante = $UsuarioMandante->getUsumandanteId();

                        if ($usuarioMandante == 16) {
                            $usuarioMandante = "331764";
                        }

                        /* crea un objeto JSON con detalles de un depósito. */
                        $ObjAutomation["method"] = 'deposit_created';
                        $ObjAutomation["user_id"] = $usuarioMandante;
                        $ObjAutomation["product"] = $Producto->getProductoId();
                        $ObjAutomation["amount"] = $amount;

                        $ObjAutomation = json_decode(json_encode($ObjAutomation));


                        /* verifica automatización de un objeto y maneja resultados según condiciones. */
                        $Automation = new Automation();
                        $result = $Automation->CheckAutomation($ObjAutomation, $ObjAutomation->method, $ObjAutomation->user_id, "");

                        if ($result != '') {
                            if ($result->isRejected == 1) {
                                throw new Exception("Rechazada por nuestro sistema", '60001');
                            } elseif ($result->needApprobation == 1) {
                                $response["data"] = array(
                                    "result" => 0,
                                    "necesitaAprobacion" => true,
                                    'riskId' => $result->riskId


                                );
                                print json_encode($response);

                                exit();
                            } else {
                            }
                        }
                    }


                    /* Se crea una solicitud de pago utilizando el servicio VISA y se redirige. */
                    $VISASERVICES = new Backend\integrations\payment\VISASERVICES();

                    $data = $VISASERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url);

                    $URLREDIRECCION = $data;
                    break;

                case 'PAYCIPS2':
                    /* crea un pago usando el servicio PAYCIPS2 y redirige según el resultado. */


                    $PAYCIPSSERVICES = new Backend\integrations\payment\PAYCIPSSERVICES();

                    $data = $PAYCIPSSERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url);

                    $URLREDIRECCION = $data;
                    break;


                case 'PAYCIPS':
                    /* maneja diferentes métodos de pago basados en condiciones específicas. */

                    $PAYCIPSSERVICES = new Backend\integrations\payment\PAYCIPSSERVICES();

                    if ($service == 9031) {
                        $data = $PAYCIPSSERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url);
                    } elseif ($Producto->externoId == "spei") {
                        $data = $PAYCIPSSERVICES->SpeiPayment($Usuario, $Producto, $amount, $success_url, $fail_url);
                    } else {
                        $data = $PAYCIPSSERVICES->createRequestPaymentREST($Usuario, $Producto, $amount, $success_url, $fail_url);
                    }


                    $URLREDIRECCION = $data;
                    break;

                case 'WEPAY4UPM':
                    /* Crea una solicitud de pago utilizando WEPAY4USERVICES y redirige según resultados. */

                    $WEPAY4U = new Backend\integrations\payment\WEPAY4USERVICES();
                    $data = $WEPAY4U->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url);
                    $URLREDIRECCION = $data;
                    break;

                case 'DIRECTA24':
                    /* crea una solicitud de pago usando el servicio DIRECTA24. */


                    $DIRECTA24SERVICES = new Backend\integrations\payment\DIRECTA24SERVICES();

                    $data = $DIRECTA24SERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url);

                    $URLREDIRECCION = $data;
                    break;

                case 'SPOTUPAY':
                    /* inicializa servicios de pago para SPOTUPAY y crea una solicitud de pago. */
                    $SPOTUPAYSERVICES = new Backend\integrations\payment\SPOTUPAYSERVICES();

                    $data = $SPOTUPAYSERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url);

                    $URLREDIRECCION = $data;
                    break;

                case 'PAYVALIDA':
                    /* crea un pago utilizando el servicio PAYVALIDA y redirige. */


                    $PAYVALIDASERVICES = new Backend\integrations\payment\PAYVALIDASERVICES();

                    $data = $PAYVALIDASERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url);

                    $URLREDIRECCION = $data;
                    break;

                case 'VISAQR':
                    /* Código para crear una solicitud de pago usando VISA QR y redirigir al usuario. */


                    $VISAQRSERVICES = new Backend\integrations\payment\VISAQRSERVICES();

                    $data = $VISAQRSERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url);

                    $URLREDIRECCION = $data;
                    break;

                case 'RAVE':
                    /* inicializa servicios de pago y crea una solicitud de pago. */


                    $RAVESERVICES = new Backend\integrations\payment\RAVESERVICES();

                    $data = $RAVESERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url);

                    $URLREDIRECCION = $data;
                    break;

                case 'SAGICOR':
                    /* Crea una solicitud de pago utilizando el servicio SAGICOR y redirige a la URL correspondiente. */


                    $SAGICORSERVICES = new Backend\integrations\payment\SAGICORSERVICES();

                    $data = $SAGICORSERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url);

                    $URLREDIRECCION = $data;
                    break;

                case 'COINPAYMEN':
                    /* inicializa un servicio de pago y crea una solicitud de pago. */

                    $COINPAYMENTSSERVICES = new Backend\integrations\payment\COINPAYMENTSSERVICES();
                    $data = $COINPAYMENTSSERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url);
                    $URLREDIRECCION = $data;
                    break;

                case 'COINSPAID':
                    /* Se inicia un servicio de pago con COINSPAID y se genera una solicitud de pago. */


                    $COINSPAIDSERVICIES = new Backend\integrations\payment\COINSPAIDSERVICES();
                    $data = $COINSPAIDSERVICIES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url);
                    $URLREDIRECCION = $data;
                    break;

                case 'INSWITCH':


                    /* Se crea una nueva instancia de la clase INSWITCHSERVICES en PHP. */
                    $INSWITCHSERVICES = new Backend\integrations\payment\INSWITCHSERVICES();
                    // $data = $INSWITCHSERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url);
                    switch ($Producto->getExternoId()) {

                        case "VIRTUALACCOUNT":
                            /* gestiona pagos mediante cuentas virtuales utilizando datos del usuario y producto. */

                            $data = $INSWITCHSERVICES->PaymentMethodsVirtualAccounts($Usuario, $Producto, $amount, $success_url, $fail_url);
                            break;
                        case "INSWITCHTARJETAS":
                            /* Se obtiene métodos de pago para tarjetas según usuario y producto especificado. */

                            $data = $INSWITCHSERVICES->PaymentMethodsCard($Usuario, $Producto, $amount, $success_url, $fail_url);
                            break;
                        case "INSWITCHPAGOSFISICOS":

                            /* llama a un método para procesar pagos en efectivo para un usuario y producto específicos. */
                            $data = $INSWITCHSERVICES->PaymentMethodsCash($Usuario, $Producto, $amount, $success_url, $fail_url);
                            break;


                        case "BIN_PAY":
                            /* gestiona un método de pago criptográfico para una transacción específica. */

                            $data = $INSWITCHSERVICES->PaymentMethodsCrypto($Usuario, $Producto, $amount, $success_url, $fail_url);
                            break;


                        default:

                            /* Código para crear un pago solicitando información del usuario y producto. */
                            $data = $INSWITCHSERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url);
                            break;
                    }


                    /* Se asigna el valor de `$data` a la variable `$URLREDIRECCION`. */
                    $URLREDIRECCION = $data;
                    break;

                case 'OMNI':
                    /* Inicializa servicios OMNI y genera un token para manejar sesiones de usuario. */


                    $OMNISERVICES = new Backend\integrations\payment\OMNISERVICES();

                    $generateToken = $OMNISERVICES->token();
                    $token = json_decode($generateToken->response);
                    $token = $token->data->accessToken;

                    $data = $OMNISERVICES->generate_session($Usuario, $token);


                    $URLREDIRECCION = $data;
                    break;

                case 'PAYPHONE':
                    /* Valida la cuenta del usuario y realiza solicitudes de pago para Payphone. */

                    if ($Usuario->mandante == 8 && ($Usuario->verifcedulaPost != "S" || $Usuario->verifcedulaAnt != 'S')) {
                        throw new Exception("La cuenta necesita estar verificada para poder depositar", "21006");
                    }


                    if ($Producto->externoId == 'PP_PAY' || $Producto->externoId == 'StoreIdECD') {
                        $PAYPHONEPAYSERVICES = new Backend\integrations\payment\PAYPHONEPAYSERVICES();
                        $data = $PAYPHONEPAYSERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url, $cancel_url);
                    } else {
                        $PAYPHONESERVICES = new Backend\integrations\payment\PAYPHONESERVICES();
                        $data = $PAYPHONESERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url);
                    }
                    $URLREDIRECCION = $data;
                    break;

                case 'ENGLOBAMARKETING':
                    /* Crea un pago usando servicios de Englobamarketing y establece la URL de redirección. */


                    $ENGLOBAMARKETINGSERVICES = new Backend\integrations\payment\ENGLOBASERVICES();
                    $data = $ENGLOBAMARKETINGSERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url, $cancel_url);
                    $URLREDIRECCION = $data;
                    break;

                case 'PAYU':
                    /* Verifica cuenta del usuario antes de procesar el pago con PAYU. */

                    if ($Usuario->mandante == 0 && ($Usuario->verifcedulaPost != "S" || $Usuario->verifcedulaAnt != 'S')) {
                        throw new Exception("La cuenta necesita estar verificada para poder depositar", "21006");
                    }
                    $PAYUSERVICES = new Backend\integrations\payment\PAYUSERVICES();
                    $data = $PAYUSERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url, $cancel_url);

                    $URLREDIRECCION = $data;
                    break;


                case 'INTERN':
                    /* Código que gestiona pagos para usuarios internos, validando verificación de cuentas y productos. */

                    if ($Usuario->mandante == 8 && ($Usuario->verifcedulaPost != "S" || $Usuario->verifcedulaAnt != 'S')) {
                        throw new Exception("La cuenta necesita estar verificada para poder depositar", "21006");
                    }


                    if ($Producto->externoId == 'PP_PAY' || $Producto->externoId == 'StoreIdECD') {
                        $INTERNPAYSERVICES = new Backend\integrations\payment\INTERNPAYSERVICES();
                        $data = $INTERNPAYSERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url, $cancel_url);
                    } else {
                        $INTERNSERVICES = new Backend\integrations\payment\INTERNSERVICES();
                        $data = $INTERNSERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url);
                    }
                    $URLREDIRECCION = $data;
                    break;


                case 'MONNET':
                    /* Configura un servicio de pago "MONNET" y redirige según respuesta del servidor. */


                    $MONNETSERVICES = new Backend\integrations\payment\MONNETSERVICES();
                    $paymentMethodId = "";
                    $data = $MONNETSERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url, $cancel_url);
                    $URLREDIRECCION = $data;
                    break;

                case 'PAYKU':
                    /* Código para crear un pago usando el servicio PAYKU y redirigir al usuario. */


                    $PAYKUSERVICES = new Backend\integrations\payment\PAYKUSERVICES();
                    $paymentMethodId = "";
                    $data = $PAYKUSERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url, $cancel_url);
                    $URLREDIRECCION = $data;
                    break;


                case 'PAGSMILE':
                    /* integra un servicio de pago PAGSMILE y genera una solicitud de pago. */


                    $PAGSMILESERVICES = new Backend\integrations\payment\PAGSMILESERVICES();
                    $data = $PAGSMILESERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url, $cancel_url);
                    $URLREDIRECCION = $data;
                    break;


                case 'COINPAY':
                    /* Se crea un pedido de pago utilizando el servicio COINPAY. */


                    $COINPAYSERVICES = new Backend\integrations\payment\COINPAYSERVICES();
                    $data = $COINPAYSERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url);
                    $URLREDIRECCION = $data;
                    break;

                case 'PAYRETAILE':
                    /* crea una solicitud de pago utilizando el servicio PAYRETIALER. */


                    $PAYRETAILERSSERVICES = new Backend\integrations\payment\PAYRETAILERSSERVICES();
                    $paymentMethodId = "";
                    $data = $PAYRETAILERSSERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url, $cancel_url);
                    $URLREDIRECCION = $data;
                    break;

                case 'PAYBROKERS':
                    /* Se crea una solicitud de pago usando los servicios de PAYBROKERS. */


                    $PAYBROKERSSERVICES = new Backend\integrations\payment\PAYBROKERSSERVICES();
                    $paymentMethodId = "";
                    $data = $PAYBROKERSSERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url, $cancel_url);
                    $URLREDIRECCION = $data;
                    break;

                case 'EZZEPAY':
                    /* Implementa el servicio de pago EZZEPAY y crea una solicitud de pago. */


                    $EZZEPAYSSERVICES = new Backend\integrations\payment\EZZEPAYSERVICES();
                    $paymentMethodId = "";
                    $data = $EZZEPAYSSERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url, $cancel_url);
                    $URLREDIRECCION = $data;
                    break;

                case 'TUPAY':

                    $TUPAYSERVICES = new Backend\integrations\payment\TUPAYSERVICES();
                    $data = $TUPAYSERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url, $cancel_url);
                    $URLREDIRECCION = $data;
                    break;

                case 'NUVEI':
                    /* procesa pagos utilizando servicios Nuvei según el tipo de producto seleccionado. */


                    $NUVEISERVICES = new Backend\integrations\payment\NUVEISERVICES();
                    switch ($Producto->getExternoId()) {
                        case "Tarjetas":
                            $data = $NUVEISERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url, $cancel_url);
                            break;
                        case "TF":
                            $data = $NUVEISERVICES->createRequestPayment2($Usuario, $Producto, $amount, $success_url, $fail_url, $cancel_url);
                            break;
                    }


                    $URLREDIRECCION = $data;
                    break;


                case 'CLUBPAGO':
                    /* Crea una solicitud de pago utilizando el servicio CLUBPAGO y redirige a la URL. */


                    $CLUBPAGOSERVICES = new Backend\integrations\payment\CLUBPAGOSERVICES();

                    $data = $CLUBPAGOSERVICES->createRequestPaymentPayformat($Usuario, $Producto, $amount, $success_url, $fail_url);

                    $URLREDIRECCION = $data;
                    break;


                case 'PAYFORFUN':
                    /* crea una solicitud de pago utilizando el servicio PAYFORFUN. */


                    $PAYFORFUNSERVICES = new Backend\integrations\payment\PAYFORFUNSERVICES();
                    $data = $PAYFORFUNSERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url);
                    $URLREDIRECCION = $data;
                    break;


                case 'KASHIO':
                    /* crea un pago usando el servicio de KASHIO y redirige al usuario. */


                    $KASHIOSERVICES = new Backend\integrations\payment\KASHIOSERVICES();
                    $data = $KASHIOSERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url, $cancel_url);
                    $URLREDIRECCION = $data;
                    break;

                case 'STARPAGO':
                    /* Crea una solicitud de pago utilizando el servicio STARPAGO y redirige según resultados. */


                    $STARPAGOSERVICES = new Backend\integrations\payment\STARPAGOSERVICES();
                    $data = $STARPAGOSERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url, $cancel_url);
                    $URLREDIRECCION = $data;
                    break;

                case 'EUKAPAY':
                    /* Crea una solicitud de pago utilizando el servicio EUKAPAY y redirige según resultados. */
                    $EUKAPAYSERVICES = new Backend\integrations\payment\EUKAPAYSERVICES();
                    $data = $EUKAPAYSERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url, $cancel_url);
                    $URLREDIRECCION = $data;
                    break;

                case 'COINCAEX':
                    /* Crea una solicitud de pago utilizando el servicio COINCAEX y redirige la URL correspondiente. */


                    $COINCAEXSERVICES = new Backend\integrations\payment\COINCAEXSERVICES();
                    $data = $COINCAEXSERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url);
                    $URLREDIRECCION = $data;
                    break;

                case 'PRONTOPAGA':
                    /* crea una solicitud de pago mediante el servicio PRONTOPAGA. */


                    $PRONTOPAGASERVICES = new Backend\integrations\payment\PRONTOPAGASERVICES();

                    $data = $PRONTOPAGASERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url);

                    $URLREDIRECCION = $data;
                    break;

                case 'CONEKTA':
                    /* Se crea una solicitud de pago utilizando el servicio CONEKTA para un usuario. */


                    $CONEKTASERVICES = new Backend\integrations\payment\CONEKTASERVICES();

                    $data = $CONEKTASERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url);

                    $URLREDIRECCION = $data;
                    break;

                case 'DIGITALFEMSA':
                    /* Integración de pagos para DIGITALFEMSA, creando una solicitud de pago y redirección. */


                    $DIGITALFEMSASERVICES = new Backend\integrations\payment\DIGITALFEMSASERVICES();

                    $data = $DIGITALFEMSASERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url);

                    $URLREDIRECCION = $data;
                    break;


                case 'PAYCASH':
                    /* Crea una solicitud de pago con PAYCASHSERVICES y guarda la URL de redirección. */

                    $PAYCASHSERVICES = new Backend\integrations\payment\PAYCASHSERVICES();
                    $data = $PAYCASHSERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url);
                    $URLREDIRECCION = $data;
                    break;

                case 'PAYSAFE':
                    /* Se crea una solicitud de pago con Paysafe y se redirige a la URL correspondiente. */


                    $PAYSAFESERVICES = new Backend\integrations\payment\PAYSAFESERVICES();
                    $data = $PAYSAFESERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url, $cancel_url);
                    $URLREDIRECCION = $data;
                    break;


                case 'UNLIMINT':
                    /* crea una solicitud de pago usando UNLIMINT y define URL de redirección. */


                    $UNLIMINTSERVICES = new Backend\integrations\payment\UNLIMINTSERVICES();
                    $data = $UNLIMINTSERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url, $cancel_url);
                    $URLREDIRECCION = $data;
                    break;


                case 'WESTERNUNION':
                    /* Crea una solicitud de pago usando Western Union y redirige a la URL correspondiente. */


                    $WESTERNUNIONSERVICES = new Backend\integrations\payment\WESTERNUNIONSERVICES();

                    $data = $WESTERNUNIONSERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url);

                    $URLREDIRECCION = $data;
                    break;

                case 'PROMETEO':
                    /* Código para crear un pago con el servicio Prometeo y redirigir a URLs específicas. */


                    $PROMETEOSERVICES = new Backend\integrations\payment\PROMETEOSERVICES();
                    $paymentMethodId = "";
                    $data = $PROMETEOSERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url, $cancel_url);
                    $URLREDIRECCION = $data;

                    break;

                case 'IZIPAY':
                    /* Código para gestionar pagos con IZIPAY, confirmando o creando solicitudes según respuesta. */


                    $IZIPAYSERVICES = new Backend\integrations\payment\IZIPAYSERVICES();
                    $paymentMethodId = "";
                    if ($response_data != "") {
                        $data = $IZIPAYSERVICES->confirmPayment(json_encode($response_data));
                    } else {
                        $data = $IZIPAYSERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url, $cancel_url);
                    }
                    $URLREDIRECCION = $data;

                    break;

                case 'FRI':


                    try {


                        /* Se crean instancias de clases para manejar pagos y usuarios en el backend. */
                        $FRISERVICES = new Backend\integrations\payment\FRISERVICES();


                        try {

                            $Clasificador = new Clasificador('', 'FRIUSERNAME');
                            $ClasificadorId = $Clasificador->getClasificadorId();
                            $UsuarioInformacion = new UsuarioInformacion('', $ClasificadorId, $Usuario->usuarioId, "", $Usuario->mandante);
                        } catch (Exception $ex) {
                            /* Bloque para manejar excepciones en PHP sin realizar acciones específicas. */
                        }

                        /* crea un clasificador y obtiene su ID, manejando excepciones. */
                        try {
                            $Clasificador = new Clasificador('', 'FRIPHONE');
                            $ClasificadorId = $Clasificador->getClasificadorId();
                            $UsuarioInformacion2 = new UsuarioInformacion('', $ClasificadorId, $Usuario->usuarioId, "", $Usuario->mandante);
                        } catch (Exception $ex) {
                        }


                        /* verifica si el nombre de usuario o número de teléfono están definidos. */
                        $FriUsername = $UsuarioInformacion->valor;
                        $FriPhoneNumber = $UsuarioInformacion2->valor;


                        if (($FriUsername !== "" && $FriUsername !== null) || ($FriPhoneNumber !== "" && $FriPhoneNumber !== null)) {
                        } else {
                            /* Lanza una excepción si no se ha configurado el método de pago. */


                            throw new Exception("Falta configurar el metodo de pago", 100116);
                        }


                        //$Fri_username="crmolinatest"; //Esto se debe cambiar para que sea dinamico como parametro de entrada.

                        //$Fri_phoneNumber="32676719"; //Esto se debe cambiar para que sea dinamico como parametro de entrada.


                        /* Crea una solicitud de pago utilizando los datos del usuario y producto especificados. */
                        $data = $FRISERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url, "", $FriUsername, $FriPhoneNumber);
                    } catch (Exception $e) {
                        /* Manejo de excepciones específicas para errores relacionados con "FRI" en PHP. */


                        if ($e->getCode() == 100111) {

                            throw new Exception("Usuario no encontrado en FRI", 100111);
                        } elseif ($e->getCode() == 100113) {

                            throw new Exception("Error al crear al transaccion en FRI", 100113);
                        } elseif ($e->getCode() == 100113) {

                            throw new Exception("Error al crear al link en FRI", 100115);
                        }
                    }


                    /* Asigna el valor de $data a la variable $URLREDIRECCION. */
                    $URLREDIRECCION = $data;
                    break;


                case 'GREENPAY':
                    /* crea una solicitud de pago utilizando el servicio GREENPAY. */


                    $GREENPAYSERVICES = new Backend\integrations\payment\GREENPAYSERVICES();
                    $data = $GREENPAYSERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url, $cancel_url);
                    $URLREDIRECCION = $data;
                    break;


                case 'DEPOSITOSMANUALES':


                    /* obtiene fecha, usuario, ID de usuario y moneda de un objeto JSON. */
                    $UsuarioBanco = null;

                    $date = $json->params->date;

                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                    $UsuarioId = $Usuario->usuarioId;

                    $moneda = $Usuario->moneda;

                    if (!empty($bank_account_id)) $UsuarioBanco = new UsuarioBanco($bank_account_id);


                    /* Verifica moneda y realiza un pago usando un servicio backend específico. */
                    if ($moneda === $currency) {
                        $DEPOSITOSMANUALESSERVICES = new Backend\integrations\payment\DEPOSITOSMANUALESSERVICES();
                        $paymentMethodId = "";
                        $data = $DEPOSITOSMANUALESSERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url, $operation_number, $date, $UsuarioBanco);
                        $URLREDIRECCION = $data;
                    } else {


                        /* Se crea un usuario y se asocia una segunda cuenta a él. */
                        $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                        $UsuarioId = $Usuario->usuarioId;

                        $moneda = $Usuario->moneda;

                        try {

                            $CuentaAsociada = new CuentaAsociada('', $UsuarioId);

                            $SegundaCuenta = $CuentaAsociada->usuarioId2;

                            $Usuario2 = new Usuario($SegundaCuenta);
                        } catch (Exception $e) {
                            /* Maneja excepciones, verifica código específico y crea objetos de cuenta y usuario. */

                            if ($e->getCode() == '110008') {

                                $CuentaAsociada = new CuentaAsociada('', '', $UsuarioId);

                                $SegundaCuenta = $CuentaAsociada->usuarioId;

                                $Usuario2 = new Usuario($SegundaCuenta);
                            }
                        }


                        /* crea un objeto de servicios de depósitos manuales y genera una solicitud de pago. */
                        $DEPOSITOSMANUALESSERVICES = new Backend\integrations\payment\DEPOSITOSMANUALESSERVICES();
                        $paymentMethodId = "";
                        $data = $DEPOSITOSMANUALESSERVICES->createRequestPayment($Usuario2, $Producto, $amount, $success_url, $fail_url, $operation_number, $date);
                        $URLREDIRECCION = $data;
                    }
                    break;

                case 'TOTALPAGO':
                    /* Crea una solicitud de pago usando el servicio TOTALPAGO y redirige al usuario. */
                    $TOTALPAGOSERVICES = new Backend\integrations\payment\TOTALPAGOSERVICES();
                    $data = $TOTALPAGOSERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url, $cancel_url);
                    $URLREDIRECCION = $data;
                    break;

                case 'YUNO':
                    /* Se crea una solicitud de pago utilizando el servicio YUNO y se obtiene una URL. */
                    $YUNOSERVICES = new Backend\integrations\payment\YUNOSERVICES();
                    $data = $YUNOSERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url, $cancel_url);
                    $URLREDIRECCION = $data;
                    break;

                case 'ALPS':
                     /* Se crea una solicitud de pago utilizando el servicio ALPSSERVICES y se obtiene una URL. */
                    $ALPSSERVICE = new Backend\integrations\payment\ALPSSERVICES();
                    $data = $ALPSSERVICE->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url);
                    $URLREDIRECCION = $data;
                    break;

                case 'MEGAPAYZ':
                    /* Crea una solicitud de pago utilizando el servicio EUKAPAY y redirige según resultados. */
                    $MEGAPAYZSERVICES = new Backend\integrations\payment\MEGAPAYZSERVICES();
                    $data = $MEGAPAYZSERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url, $cancel_url);
                    $URLREDIRECCION = $data;
                    break;

                case 'ANINDA':
                    /* Crea una solicitud de pago utilizando el servicio EUKAPAY y redirige según resultados. */
                    $ANINDASERVICES = new Backend\integrations\payment\ANINDASERVICES();
                    $data = $ANINDASERVICES->createRequestPayment($Usuario, $Producto, $amount, $success_url, $fail_url, $cancel_url);
                    $URLREDIRECCION = $data;
                    break;
            }

            $isPayphone = false;
            $isVisa = false;
            $isPayu = false;
            $isIzipay = false;

            if ($URLREDIRECCION->success && $URLREDIRECCION != '') {


                /* Se definen variables y se verifica si hay una imagen en la respuesta. */
                $isPayphone = $URLREDIRECCION->isPayphone;
                $isVisa = $URLREDIRECCION->isVisa;
                $isPayu = $URLREDIRECCION->isPayu;
                $isIzipay = $URLREDIRECCION->isIzipay;
                if ($URLREDIRECCION->dataImg != "") {

                    $response["data"] = $URLREDIRECCION;
                } else if ($URLREDIRECCION->payphoneJS != "" && $URLREDIRECCION->payphoneJson != "") {



                    /* Crea una respuesta estructurada con datos sobre un método de pago y su estado. */
                    $response["data"] = array(
                        "result" => 0,
                        "details" => array(
                            "payphoneJS" => $URLREDIRECCION->payphoneJS,
                            "payphoneToken" => $URLREDIRECCION->payphoneToken,
                            "payphoneJson" => $URLREDIRECCION->payphoneJson,
                            "method" => ($URLREDIRECCION->method == "" ? "post" : $URLREDIRECCION->method),
                            "fields" => array(
                                array("status" => $status_url),
                                // array("name" => "mtid", "value" => "012020321"),
                                // array("name" => "amount", "value" => "10"),
                                // array("name" => "currenct", "value" => "USD")
                            )
                        )


                    );
                } else if ($URLREDIRECCION->intentId != "" && $URLREDIRECCION->widgetId != "") {
                    /* verifica condiciones y construye una respuesta JSON con datos específicos. */



                    $response["data"] = array(
                        "result" => 0,
                        "details" => array(
                            "intentId" => $URLREDIRECCION->intentId,
                            "widgetId" => $URLREDIRECCION->widgetId,
                            "method" => ($URLREDIRECCION->method == "" ? "post" : $URLREDIRECCION->method),
                            "fields" => array(
                                array("status" => $status_url),
                                // array("name" => "mtid", "value" => "012020321"),
                                // array("name" => "amount", "value" => "10"),
                                // array("name" => "currenct", "value" => "USD")
                            )
                        )


                    );
                } else if ($URLREDIRECCION->transactionId != "" and $URLREDIRECCION != "null") {
                    /* verifica un ID de transacción y asigna resultados a una respuesta. */

                    $response["data"] = array(
                        "result" => 0,
                        "details" => array(
                            "transactionId" => $URLREDIRECCION->transactionId,
                            "fields" => array(
                                "status" => true
                            )
                        )
                    );
                } else {


                    /* Crea una respuesta estructurada para redirigir un formulario con datos específicos. */
                    $response["data"] = array(
                        "result" => 0,
                        "details" => array(
                            "action" => $URLREDIRECCION->url,
                            "method" => ($URLREDIRECCION->method == "" ? "post" : $URLREDIRECCION->method),
                            "fields" => array(
                                array("status" => $status_url),
                                // array("name" => "mtid", "value" => "012020321"),
                                // array("name" => "amount", "value" => "10"),
                                // array("name" => "currenct", "value" => "USD")
                            )
                        )


                    );


                    /* Verifica y asigna un valor si 'target' no está vacío ni es nulo. */
                    if ($URLREDIRECCION->target != '' && $URLREDIRECCION->target != null) {
                        $response["data"]['target'] = $URLREDIRECCION->target;
                    }
                }


                /* Se verifica si es un teléfono público y se agrega información a la respuesta. */
                if ($isPayphone) {
                    $response["data"]["details"]["dataPayphone"] = $URLREDIRECCION->dataPayphone;
                    if (!empty($URLREDIRECCION->dataPayphoneInfo)) {
                        $response["data"]["details"]["dataPayphoneInfo"] = $URLREDIRECCION->dataPayphoneInfo;
                    }
                }


                /* Condicionales que asignan datos a la respuesta según el tipo de pago. */
                if ($isVisa) {
                    $response["data"]["details"]["dataVisa"] = $URLREDIRECCION->dataVisa;
                }

                if ($isPayu) {
                    $response["data"]["details"]["dataPayU"] = $URLREDIRECCION->dataPayU;
                }


                /* Condicional que asigna información de Izipay a la respuesta si se cumple la condición. */
                if ($isIzipay) {
                    $response["data"]["details"]["dataIzipay"] = $URLREDIRECCION->dataIzipay;
                }
            } else {
                if ($Proveedor->getAbreviado() == 'CONEKTA') {
                    throw new Exception("Producto no disponible", '21014');
                }

                throw new Exception("Producto no disponible", ' 21010');
            }
        } else {
            throw new Exception("Producto no disponible2", '21010');
        }
    }
}