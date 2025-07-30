<?php

/**
 *  Clase INSWITCHSERVICES
 *
 * Este archivo contiene la implementación de métodos para gestionar transacciones
 * relacionadas con productos, incluyendo pagos con criptomonedas y cuentas virtuales.
 * Proporciona funcionalidades para realizar conexiones HTTP, generar tokens de autenticación,
 * y manejar datos de transacciones en diferentes contextos.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-23
 */

namespace Backend\Integrations\payment;

use Exception;
use \CurlWrapper;
use Backend\dto\Pais;
use Backend\dto\Ciudad;
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\PuntoVenta;
use Backend\dto\Clasificador;
use Backend\dto\PaisMandante;
use Backend\dto\Subproveedor;
use Backend\dto\TransprodLog;
use Backend\dto\MandanteDetalle;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransproductoDetalle;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransproductoDetalleMySqlDAO;

/**
 * Clase INSWITCHSERVICES
 *
 * Esta clase proporciona métodos para interactuar con los servicios de pago de INSWITCH,
 * incluyendo la generación de tokens, procesamiento de pagos y comunicación con APIs.
 */
class INSWITCHSERVICES
{
    /**
     * Clave API utilizada para autenticar las solicitudes.
     *
     * @var string
     */
    private $ApiKey = "";

    /**
     * Contraseña utilizada para la autenticación.
     *
     * @var string
     */
    private $Password = "";

    /**
     * Nombre de usuario utilizado para la autenticación.
     *
     * @var string
     */
    private $Username = "";

    /**
     * URL base para las solicitudes a la API.
     *
     * @var string
     */
    private $URL = "";

    /**
     * URL específica para depósitos.
     *
     * @var string
     */
    private $URLDEPOSIT = "";

    /**
     * URL de redirección en caso de éxito.
     *
     * @var string
     */
    private $success_url = "";

    /**
     * URL de redirección en caso de fallo.
     *
     * @var string
     */
    private $fail_url = "";

    /**
     * URL de callback para notificaciones.
     *
     * @var string
     */
    private $callback_url = "";

    /**
     * URL de callback para el entorno de desarrollo.
     *
     * @var string
     */
    private $callback_urlDEV = "https://apidev.virtualsoft.tech/integrations/payment/inswitch/confirm/";

    /**
     * URL de callback para el entorno de producción.
     *
     * @var string
     */
    private $callback_urlPROD = "https://integrations.virtualsoft.tech/payment/inswitch/confirm/";

    /**
     * Constructor de la clase INSWITCHSERVICES.
     *
     * Inicializa la URL de callback dependiendo del entorno de ejecución
     * (desarrollo o producción) utilizando la configuración del entorno.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->callback_url = $this->callback_urlDEV;
        } else {
            $this->callback_url = $this->callback_urlPROD;
        }
    }

    /**
     * Crea una solicitud de pago para un usuario y un producto específicos.
     *
     * Este método genera una solicitud de pago, calcula impuestos, configura los datos
     * necesarios para la transacción y realiza la conexión con el servicio de pago.
     *
     * @param Usuario  $Usuario    Objeto que representa al usuario que realiza el pago.
     * @param Producto $Producto   Objeto que representa el producto asociado al pago.
     * @param float    $valor      Monto del pago.
     * @param string   $urlSuccess URL de redirección en caso de éxito.
     * @param string   $urlFailed  URL de redirección en caso de fallo.
     *
     * @return object Objeto JSON con el resultado de la operación, incluyendo éxito y URL de redirección.
     * @throws Exception Si ocurre un error durante la transacción o conexión.
     */
    public function createRequestPayment(Usuario $Usuario, Producto $Producto, $valor, $urlSuccess, $urlFailed)
    {
        $this->success_url = $urlSuccess;
        $this->fail_url = $urlFailed;

        $Subproveedor = new Subproveedor("", "INSWITCH");

        $data = array();
        $data["success"] = false;
        $data["error"] = 1;

        $estado = 'A';
        $estado_producto = 'E';
        $tipo = 'T';

        if ($Usuario->puntoventaId != '' && $Usuario->puntoventaId != '0' && $Usuario->puntoventaId != null) {
            $PuntoVenta = new PuntoVenta("", $Usuario->puntoventaId);
            $nombre = $PuntoVenta->nombreContacto;
            $cedula = $PuntoVenta->cedula;
            $apellido = '';
        } else {
            $Registro = new Registro("", $Usuario->usuarioId);
            $nombre = $Usuario->nombre;
            $cedula = $Registro->cedula;
            $apellido = $Registro->apellido1;
        }

        $usuario_id = $Usuario->usuarioId;
        $email = $Usuario->login;
        $valor = $valor;
        $producto_id = $Producto->productoId;
        $extID = $Producto->externoId;
        $moneda = $Usuario->moneda;
        $mandante = $Usuario->mandante;

        $Pais = new Pais($Usuario->paisId);

        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
        $Transaction = $TransaccionProductoMySqlDAO->getTransaction();
        $Transaction->getConnection()->beginTransaction();

        //Impuesto a los Depositos.
        try {
            $Clasificador = new Clasificador("", "TAXDEPOSIT");
            $MandanteDetalle = new MandanteDetalle("", $Usuario->mandante, $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
            $taxedValue = $MandanteDetalle->valor;
        } catch (Exception $e) {
            $taxedValue = 0;
        }

        $totalTax = $valor * ($taxedValue / 100);
        $valorTax = $valor * (1 + $taxedValue / 100);

        $TransaccionProducto = new TransaccionProducto();
        $TransaccionProducto->setProductoId($producto_id);
        $TransaccionProducto->setUsuarioId($usuario_id);
        $TransaccionProducto->setValor($valor);
        $TransaccionProducto->setImpuesto($totalTax);
        $TransaccionProducto->setEstado($estado);
        $TransaccionProducto->setTipo($tipo);
        $TransaccionProducto->setEstadoProducto($estado_producto);
        $TransaccionProducto->setMandante($mandante);
        $TransaccionProducto->setFinalId(0);
        $TransaccionProducto->setExternoId(0);
        $transproductoId = $TransaccionProductoMySqlDAO->insert($TransaccionProducto);

        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $Usuario->mandante, $Usuario->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());
        $this->ApiKey = $Credentials->API_KEY;
        $this->Username = $Credentials->USERNAME;
        $this->Password = $Credentials->PASSWORD;
        $this->URL = $Credentials->URL;

        switch ($Pais->iso) {
            case "GT":
                $moneda = 'USD';
                $valorTax = ($valorTax * 0.13);
                break;
        }

        //Generar token
        $respuesta = $this->generateToken($this->URL, '/auth-service/1.0/protocol/openid-connect/token');
        $tokenT = $respuesta->access_token;

        $ciudad = '';
        try {
            $ciudadNo = new Ciudad($Registro->ciudadId);
            $ciudad = $ciudadNo->ciudadNom;
            date_default_timezone_set('America/"' . $ciudad . '"');
        } catch (Exception  $e) {
        }

        date_default_timezone_set('America/Bogota');

        $lang = 'es';
        $idType = 'nationalId';
        $paymentM = 'bankkin-pe';

        if ($Pais->iso == 'BR') {
            $lang = 'pt';
            $idType = 'CPF';
        }
        if ($extID == 'DBR_PIX') {
            $paymentM = 'pixin-br';
        }

        if ($extID == 'BIN_PAY') {
            $paymentM = 'binancepayin-' . strtolower($Pais->iso);
        }

        if ($mandante == 22) {
            $paymentM = '';
        }

        if ($Pais->iso == "SV") {
            $paymentM = "cardurlin-sv";
        }

        $Mandante = new Mandante($Usuario->mandante);
        if ($Mandante->baseUrl != '') {
            $this->URLDEPOSIT = $Mandante->baseUrl . '/gestion/deposito';
        }

        //Cuerpo de la solicitud de deposito
        $data = array();
        $data['language'] = $lang;
        $data['paymentExpiration'] = 1700200;
        $data['pageExpiration'] = 600;
        $data['successUrl'] = $this->URLDEPOSIT;
        $data['errorUrl'] = $this->URLDEPOSIT;
        $data['currency'] = $moneda;
        $data['countryCode'] = $Pais->iso;
        $data['amount'] = $valorTax;
        $data['descriptionText'] = $transproductoId;
        $data['requestingOrganisationTransactionReference'] = $transproductoId;

        $data["purchaseItems"][] = array(
            'item_name' => 'Deposit',
            'item_quantity' => 1,
            'item_amount' => $valorTax
        );
        $data['senderKycInformation'] = [
            'name' => [
                "firstName" => $this->quitar_tildes($nombre),
                "lastName" => $this->quitar_tildes($apellido),
                "fullName" => $this->quitar_tildes($nombre . ' ' . $apellido),
            ],
            'contact' => [
                "email" => $email
            ],
            'idDocuments' => [
                [
                    "idType" => $idType,
                    "idNumber" => $cedula,
                    "issuerCountry" => $Pais->iso
                ]
            ],
            "entityType" => "naturalPerson"
        ];
        $data["metadata"][] = array(
            'key' => $transproductoId,
            'value' => $usuario_id
        );
        if ($mandante != 8) {
            $data["paymentMethods"] = array(
                $paymentM,
            );
        } elseif ($extID == 'BIN_PAY' && $mandante == 8) {
            $data["paymentMethods"] = array(
                $paymentM,
            );
        }

        $uuid = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(openssl_random_pseudo_bytes(16)), 4));

        $TransproductoDetalle = new TransproductoDetalle();
        $TransproductoDetalle->transproductoId = $transproductoId;
        $TransproductoDetalle->tValue = json_encode($data);
        $TransproductoDetalleMySqlDAO = new TransproductoDetalleMySqlDAO($Transaction);
        $TransproductoDetalleMySqlDAO->insert($TransproductoDetalle);

        $Result = $this->connection($data, $tokenT, $uuid, $this->URL, '/hostedcheckout/1.0/checkout');

        syslog(LOG_WARNING, "INSWITCH DATA: " . json_encode($data) . " RESPONSE: " . json_encode($Result));

        if ($Result != '' && $Result->errorCode == null) {
            $TransprodLog = new TransprodLog();
            $TransprodLog->setTransproductoId($transproductoId);
            $TransprodLog->setEstado('E');
            $TransprodLog->setTipoGenera('A');
            $TransprodLog->setComentario('Envio Solicitud de deposito');
            $TransprodLog->setTValue(json_encode($Result));
            $TransprodLog->setUsucreaId(0);
            $TransprodLog->setUsumodifId(0);

            $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
            $TransprodLogMySqlDAO->insert($TransprodLog);

            $TransaccionProducto->setExternoId($Result->id);
            $TransaccionProductoMySqlDAO->update($TransaccionProducto);
            $Transaction->commit();

            $response = $Result;
            $data = array();
            $data["success"] = true;
            $data["url"] = $response->redirect;
        }

        return json_decode(json_encode($data));
    }

    /**
     * Método para procesar pagos con tarjeta.
     *
     * Este método configura y envía una solicitud de pago con tarjeta para un usuario y un producto específicos.
     * Realiza cálculos de impuestos, obtiene credenciales, selecciona métodos de pago según el país,
     * y maneja la conexión con el servicio de pago.
     *
     * @param Usuario $Usuario     Objeto que representa al usuario que realiza el pago.
     * @param mixed   $Producto    Objeto que representa el producto asociado al pago.
     * @param float   $valor       Monto del pago.
     * @param string  $success_url URL de redirección en caso de éxito.
     * @param string  $fail_url    URL de redirección en caso de fallo.
     *
     * @return object Objeto JSON con el resultado de la operación, incluyendo éxito y URL de redirección.
     * @throws Exception Si ocurre un error durante la transacción o conexión.
     */
    public function PaymentMethodsCard(Usuario $Usuario, $Producto, $valor, $success_url, $fail_url)
    {
        $this->success_url = $success_url;
        $this->fail_url = $fail_url;

        $Subproveedor = new Subproveedor("", "INSWITCH");
        $Registro = new Registro("", $Usuario->usuarioId);

        $dataR = array();
        $dataR["success"] = false;
        $dataR["error"] = 1;

        $estado = 'A';
        $estado_producto = 'E';
        $tipo = 'T';

        $usuario_id = $Usuario->usuarioId;
        $cedula = $Registro->cedula;
        $nombre = $Usuario->nombre;
        $email = $Usuario->login;
        $apellido = $Registro->apellido1;
        $valor = $valor;
        $producto_id = $Producto->productoId;
        $moneda = $Usuario->moneda;
        $mandante = $Usuario->mandante;
        $celular = $Registro->celular;
        $direccion = $Registro->getDireccion();
        $Pais = new Pais($Usuario->paisId);

        //Impuesto a los Depositos.
        try {
            $Clasificador = new Clasificador("", "TAXDEPOSIT");
            $MandanteDetalle = new MandanteDetalle("", $Usuario->mandante, $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
            $taxedValue = $MandanteDetalle->valor;
        } catch (Exception $e) {
            $taxedValue = 0;
        }

        $totalTax = $valor * ($taxedValue / 100);
        $valorTax = $valor * (1 + $taxedValue / 100);
        $ValorTMR = $valorTax;

        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $Usuario->mandante, $Usuario->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());
        $this->ApiKey = $Credentials->API_KEY;
        $this->Username = $Credentials->USERNAME;
        $this->Password = $Credentials->PASSWORD;
        $this->URL = $Credentials->URL;
        $RefencePayment = $Credentials->REFERENCE;

        switch ($Pais->iso) {
            case "GT":
                $moneda = 'USD';
                $metodo = "cardurlin-gt";
                $PaisMandante = new PaisMandante("", $Usuario->mandante, $Pais->paisId);
                $ValorTMR = ($valor * $PaisMandante->trmUsd);
                break;
            case "SV":
                $metodo = "cardurlin-sv";
                break;
            case "CR":
                $metodo = "cardurlin-cr";
                break;
            case "EC":
                $metodo = "cardurlk3dsin-ec";
                break;
            case "BR":
                $metodo = "cardurlin-br";
                break;
            case "HN":
                $metodo = "cardurlin-hn";
                break;
            case "NI":
                $metodo = "cardurlin-ni";
                break;
            default:
                $metodo = "cardurlin-pe";
                break;
        }

        $ciudad = '';
        $nombreCiudad = '';

        try {
            $ciudad = new Ciudad($Registro->ciudadId);
            $nombreCiudad = $ciudad->ciudadNom;
        } catch (Exception  $e) {
            if ($Pais->iso == 'EC') {
                $nombreCiudad = 'Pichincha';
            }
            if ($Pais->iso == 'SV') {
                $nombreCiudad = 'San Salvador';
            }
            if ($Pais->iso == 'NI') {
                $nombreCiudad = 'Managua';
            }
            if ($Pais->iso == 'HN') {
                $nombreCiudad = 'Tegucigalpa';
            }
            if ($Pais->iso == 'GT') {
                $nombreCiudad = 'Ciudad de Guatemala';
            }
            if ($Pais->iso == 'MX') {
                $nombreCiudad = 'Ciudad de Mexico';
            }
            if ($Pais->iso == 'BR') {
                $nombreCiudad = 'Brasilia';
            }
            if ($Pais->iso == 'CR') {
                $nombreCiudad = 'San Jose';
            }
        }

        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
        $Transaction = $TransaccionProductoMySqlDAO->getTransaction();
        $Transaction->getConnection()->beginTransaction();

        $TransaccionProducto = new TransaccionProducto();
        $TransaccionProducto->setProductoId($producto_id);
        $TransaccionProducto->setUsuarioId($usuario_id);
        $TransaccionProducto->setValor($valor);
        $TransaccionProducto->setImpuesto($totalTax);
        $TransaccionProducto->setEstado($estado);
        $TransaccionProducto->setTipo($tipo);
        $TransaccionProducto->setEstadoProducto($estado_producto);
        $TransaccionProducto->setMandante($mandante);
        $TransaccionProducto->setFinalId(0);
        $TransaccionProducto->setExternoId(0);
        $transproductoId = $TransaccionProductoMySqlDAO->insert($TransaccionProducto);

        $mandante = $Usuario->mandante;
        if ($Pais->iso == "EC" && $Producto->externoId == "INSWITCHTARJETAS" && $mandante == "0") {
            if ($Usuario->mandante == 0 && $Usuario->paisId == 66 && $Usuario->test == 'S') {
                try {
                    $identifier = $transproductoId;
                    $currency = $moneda;
                    $amount = floatval($ValorTMR);
                    $gateway_methods = 'InswitchCards';
                    $details = 'Depositar a cuenta';
                    $ipn_url = 'https://integrations.virtualsoft.tech/payment/apppay/confirm/';
                    $cancel_url = $fail_url;
                    $success_url = $success_url;
                    $public_key = 'pro_yvv3ayxvu1dyzlm3ckxs04b4gpq04fkuxv415756ekkm77q2bk2';
                    $site_name = 'Doradobet';
                    $site_logo = 'https%3A%2F%2Fimages.virtualsoft.tech%2Fm%2FmsjT1666887931.png%3Fw%3D1440%26fm%3Dwebp';
                    $checkout_theme = 'light';
                    $first_name = $Registro->nombre1;
                    $last_name = $Registro->apellido1;
                    $email = $Usuario->login;
                    $mobile = $Registro->celular;
                    $address_one = $Registro->getDireccion();
                    $address_two = '';
                    $country = $Pais->iso;
                    $area = '';
                    $city = '';
                    $sub_city = '';
                    $state = '';
                    $postcode = '';
                    $typedocument = $Registro->tipoDoc;
                    $documentnumber = $Registro->cedula;
                    $others = '';

                    //InicializarlaclaseCurlWrapper
                    $curl = new CurlWrapper('https://apppay.virtualsoft.tech/payment/initiate');

                    $curl->setOptionsArray(array(
                        CURLOPT_URL => 'https://apppay.virtualsoft.tech/payment/initiate',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS => "country={$country}&documentnumber={$documentnumber}&typedocument={$typedocument}&identifier={$identifier}&currency={$currency}&amount={$amount}&gateway_methods%5B%5D={$gateway_methods}&details={$details}&ipn_url={$ipn_url}&cancel_url={$cancel_url}&success_url={$success_url}&public_key={$public_key}&site_name={$site_name}&site_logo={$site_logo}&checkout_theme={$checkout_theme}&customer%5Bfirst_name%5D={$first_name}&customer%5Blast_name%5D={$last_name}&customer%5Bemail%5D={$email}&customer%5Bmobile%5D={$mobile}&shipping_info%5Baddress_one%5D={$address_one}&shipping_info%5Baddress_two%5D={$address_two}&shipping_info%5Barea%5D={$area}&shipping_info%5Bcity%5D={$city}&shipping_info%5Bsub_city%5D={$sub_city}&shipping_info%5Bstate%5D={$state}&shipping_info%5Bpostcode%5D={$postcode}&shipping_info%5Bcountry%5D={$country}&shipping_info%5Bothers%5D={$others}",
                        CURLOPT_HTTPHEADER => array(
                            'Content-Type: application/x-www-form-urlencoded',
                        )
                    ));

                    // Ejecutar la solicitud
                    $response = $curl->execute();
                    $response = json_decode($response);

                    if ($response != null && $response->status == "success") {
                        $TransprodLog = new TransprodLog();
                        $TransprodLog->setTransproductoId($transproductoId);
                        $TransprodLog->setEstado('E');
                        $TransprodLog->setTipoGenera('A');
                        $TransprodLog->setComentario('Envio Solicitud de deposito');
                        $TransprodLog->setTValue(json_encode($response));
                        $TransprodLog->setUsucreaId(0);
                        $TransprodLog->setUsumodifId(0);

                        $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
                        $TransprodLogMySqlDAO->insert($TransprodLog);

                        $TransaccionProducto->setExternoId($response->id);
                        $TransaccionProductoMySqlDAO->update($TransaccionProducto);
                        $Transaction->commit();

                        $data = array();
                        $data["success"] = true;
                        $data["url"] = $response->redirect_url;
                        return json_decode(json_encode($data));
                    }
                } catch (Exception $e) {
                }
            }
        }

        $Tipometodo = "card";

        $respuesta = $this->generateToken($this->URL, '/auth-service/1.0/protocol/openid-connect/token');
        $tokenT = $respuesta->access_token;

        if ($Pais->iso == 'GT') {
            $data = "direction=in&country=SV&paymentMethodTypeClass=" . $Tipometodo . "&paymentMethodTypeStatus=available";
        } else {
            $data = "direction=in&country=" . $Pais->iso . "&paymentMethodTypeClass=" . $Tipometodo . "&paymentMethodTypeStatus=available";
        }

        $uuid = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(openssl_random_pseudo_bytes(16)), 4));
        $Result = $this->connectionGET($data, $tokenT, $uuid, $this->URL, '/payment-methods/1.0/paymentmethodtypes');
        syslog(LOG_WARNING, "INSWITCH PAYMENTCARD: " . json_encode($Result));

        // Verificar si es un array (respuesta exitosa)
        if (is_array($Result) && count($Result) > 0) {
            $metodo = $Result[0]->paymentMethodType;

            $uuid2 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(openssl_random_pseudo_bytes(16)), 4));

            $PaisMandante = new PaisMandante("", $Usuario->mandante, $Pais->paisId);

            if ($direccion == '') {
                $direccion = 'Direccion';
            }

            $data2 = array(
                "amount" => strval($ValorTMR),
                "currency" => $moneda,
                "debitParty" => array(
                    "type" => $metodo,
                    "data" => array(
                        "document_type" => 'nationalId',
                        "document_number" => $cedula,
                        "full_name" => $this->quitar_tildes($nombre),
                        "first_name" => $this->quitar_tildes($Registro->nombre1),
                        "last_name" => $this->quitar_tildes($apellido),
                        "email" => $email,
                        "city" => str_replace(' ', '', $nombreCiudad),
                        "state" => str_replace(' ', '', $nombreCiudad),
                        "address" => $this->removeSpecialCharacters($this->quitar_tildes($direccion)),
                        "phone" => $celular

                    )
                ),
                "creditParty" => array(
                    "paymentMethodReference" => $RefencePayment,
                ),
                "descriptionText" => $transproductoId,
                "requestingOrganisationTransactionReference" => $transproductoId,
                "country" => $Pais->iso
            );

            $TransproductoDetalle = new TransproductoDetalle();
            $TransproductoDetalle->transproductoId = $transproductoId;
            $TransproductoDetalle->tValue = json_encode($data2);
            $TransproductoDetalleMySqlDAO = new TransproductoDetalleMySqlDAO($Transaction);
            $TransproductoDetalleMySqlDAO->insert($TransproductoDetalle);

            $Result2 = $this->connectionPOST($data2, $tokenT, $uuid2, $this->URL, '/transactions/1.0/transactions/type/deposit');
            syslog(LOG_WARNING, "INSWITCH DATACARD: " . json_encode($data2) . " RESPONSECARD: " . json_encode($Result2));

            if ($Result2 != '' && $Result2->transactionStatus == "waiting") {
                $TransprodLog = new TransprodLog();
                $TransprodLog->setTransproductoId($transproductoId);
                $TransprodLog->setEstado('E');
                $TransprodLog->setTipoGenera('A');
                $TransprodLog->setComentario('Envio Solicitud de deposito');
                $TransprodLog->setTValue(json_encode($Result2));
                $TransprodLog->setUsucreaId(0);
                $TransprodLog->setUsumodifId(0);

                $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
                $TransprodLogMySqlDAO->insert($TransprodLog);

                $TransaccionProducto->setExternoId($Result2->transactionReference);
                $TransaccionProductoMySqlDAO->update($TransaccionProducto);
                $Transaction->commit();

                $response = $Result2;
                $dataR = array();
                $dataR["success"] = true;
                $dataR["url"] = $response->requiredAction->data->redirectURL;
            }
        }

        return json_decode(json_encode($dataR));
    }

    /**
     * Procesa pagos en efectivo para un usuario y un producto específicos.
     *
     * Este método configura y envía una solicitud de pago en efectivo, calculando impuestos,
     * obteniendo credenciales, seleccionando métodos de pago según el país, y manejando
     * la conexión con el servicio de pago.
     *
     * @param Usuario $Usuario     Objeto que representa al usuario que realiza el pago.
     * @param mixed   $Producto    Objeto que representa el producto asociado al pago.
     * @param float   $valor       Monto del pago.
     * @param string  $success_url URL de redirección en caso de éxito.
     * @param string  $fail_url    URL de redirección en caso de fallo.
     *
     * @return object Objeto JSON con el resultado de la operación, incluyendo éxito, URL de instrucciones y código.
     * @throws Exception Si ocurre un error durante la transacción o conexión.
     */
    public function PaymentMethodsCash(Usuario $Usuario, $Producto, $valor, $success_url, $fail_url)
    {
        $this->success_url = $success_url;
        $this->fail_url = $fail_url;

        $Subproveedor = new Subproveedor("", "INSWITCH");
        $Registro = new Registro("", $Usuario->usuarioId);

        $dataR = array();
        $dataR["success"] = false;
        $dataR["error"] = 1;

        $estado = 'A';
        $estado_producto = 'E';
        $tipo = 'T';

        $usuario_id = $Usuario->usuarioId;
        $cedula = $Registro->cedula;
        $email = $Usuario->login;
        $apellido = $Registro->apellido1;
        $valor = $valor;
        $producto_id = $Producto->productoId;
        $moneda = $Usuario->moneda;
        $mandante = $Usuario->mandante;
        $tipoDocumento = $Registro->tipoDoc;

        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $Usuario->mandante, $Usuario->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());
        $this->ApiKey = $Credentials->API_KEY;
        $this->Username = $Credentials->USERNAME;
        $this->Password = $Credentials->PASSWORD;
        $this->URL = $Credentials->URL;
        $RefencePayment = $Credentials->REFERENCE;

        $Pais = new Pais($Usuario->paisId);

        switch ($Pais->iso) {
            case "GT":
                $moneda = 'USD';
                break;
        }

        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
        $Transaction = $TransaccionProductoMySqlDAO->getTransaction();
        $Transaction->getConnection()->beginTransaction();

        //Impuesto a los Depositos.
        try {
            $Clasificador = new Clasificador("", "TAXDEPOSIT");
            $MandanteDetalle = new MandanteDetalle("", $Usuario->mandante, $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
            $taxedValue = $MandanteDetalle->valor;
        } catch (Exception $e) {
            $taxedValue = 0;
        }

        $totalTax = $valor * ($taxedValue / 100);
        $valorTax = $valor * (1 + $taxedValue / 100);

        $TransaccionProducto = new TransaccionProducto();
        $TransaccionProducto->setProductoId($producto_id);
        $TransaccionProducto->setUsuarioId($usuario_id);
        $TransaccionProducto->setValor($valor);
        $TransaccionProducto->setImpuesto($totalTax);
        $TransaccionProducto->setEstado($estado);
        $TransaccionProducto->setTipo($tipo);
        $TransaccionProducto->setEstadoProducto($estado_producto);
        $TransaccionProducto->setMandante($mandante);
        $TransaccionProducto->setFinalId(0);
        $TransaccionProducto->setExternoId(0);
        $transproductoId = $TransaccionProductoMySqlDAO->insert($TransaccionProducto);

        $mandante = $Usuario->mandante;
        $Tipometodo = "cash";

        $respuesta = $this->generateToken($this->URL, '/auth-service/1.0/protocol/openid-connect/token');
        $tokenT = $respuesta->access_token;

        $data = "direction=in&country=" . $Pais->iso . "&paymentMethodTypeClass=" . $Tipometodo . "&paymentMethodTypeStatus=available";

        $uuid = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(openssl_random_pseudo_bytes(16)), 4));

        $Result = $this->connectionGET($data, $tokenT, $uuid, $this->URL, '/payment-methods/1.0/paymentmethodtypes');
        syslog(LOG_WARNING, "INSWITCH PAYMENTCASH: " . json_encode($Result));

        // Verificar si es un array (respuesta exitosa)
        if (is_array($Result) && count($Result) > 0) {
            $metodo = $Result[0]->paymentMethodType;

            $uuid2 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(openssl_random_pseudo_bytes(16)), 4));

            if ($Pais->iso == "PE" && $Producto->externoId == "INSWITCHPAGOSFISICOS") {
                $tipoDocumento = 'nationalId';
            }

            $data2 = array(
                "amount" => strval($valorTax),
                "currency" => $moneda,
                "debitParty" => array(
                    "type" => $metodo,
                    "data" => array(
                        "first_name" => $this->quitar_tildes($Registro->nombre1),
                        "last_name" => $this->quitar_tildes($apellido),
                        "document_number" => $cedula,
                        "document_type" => $tipoDocumento,
                        "email" => $email

                    )
                ),
                "creditParty" => array(
                    "paymentMethodReference" => $RefencePayment,
                ),
                "descriptionText" => $transproductoId,
                "requestingOrganisationTransactionReference" => $transproductoId,
                "country" => $Pais->iso
            );

            syslog(LOG_WARNING, "INSWITCH DATACASH: " . json_encode($data2));

            $TransproductoDetalle = new TransproductoDetalle();
            $TransproductoDetalle->transproductoId = $transproductoId;
            $TransproductoDetalle->tValue = json_encode($data);
            $TransproductoDetalleMySqlDAO = new TransproductoDetalleMySqlDAO($Transaction);
            $TransproductoDetalleMySqlDAO->insert($TransproductoDetalle);

            $Result2 = $this->connectionPOST($data2, $tokenT, $uuid2, $this->URL, '/transactions/1.0/transactions/type/deposit');
            syslog(LOG_WARNING, "INSWITCH RESPONSECASH: " . json_encode($Result2));

            if ($Result2 != '' && $Result2->transactionStatus == "waiting") {
                $TransprodLog = new TransprodLog();
                $TransprodLog->setTransproductoId($transproductoId);
                $TransprodLog->setEstado('E');
                $TransprodLog->setTipoGenera('A');
                $TransprodLog->setComentario('Envio Solicitud de deposito');
                $TransprodLog->setTValue(json_encode($Result2));
                $TransprodLog->setUsucreaId(0);
                $TransprodLog->setUsumodifId(0);

                $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
                $TransprodLogMySqlDAO->insert($TransprodLog);

                $TransaccionProducto->setExternoId($Result2->transactionReference);
                $TransaccionProductoMySqlDAO->update($TransaccionProducto);
                $Transaction->commit();

                $response = $Result2;

                $dataR = array();
                $dataR["success"] = true;
                $dataR["url"] = $response->instructions;
                $dataR["code"] = $response->requiredAction->data->code;
            }
        }

        return json_decode(json_encode($dataR));
    }

    /**
     * Procesa pagos con criptomonedas para un usuario y un producto específicos.
     *
     * Este método configura y envía una solicitud de pago en criptomonedas, calculando impuestos,
     * obteniendo credenciales, seleccionando métodos de pago según el país, y manejando
     * la conexión con el servicio de pago.
     *
     * @param Usuario $Usuario     Objeto que representa al usuario que realiza el pago.
     * @param mixed   $Producto    Objeto que representa el producto asociado al pago.
     * @param float   $valor       Monto del pago.
     * @param string  $success_url URL de redirección en caso de éxito.
     * @param string  $fail_url    URL de redirección en caso de fallo.
     *
     * @return object Objeto JSON con el resultado de la operación, incluyendo éxito, URL de redirección y código.
     * @throws Exception Si ocurre un error durante la transacción o conexión.
     */
    public function PaymentMethodsCrypto(Usuario $Usuario, $Producto, $valor, $success_url, $fail_url)
    {
        $this->success_url = $success_url;
        $this->fail_url = $fail_url;

        $Subproveedor = new Subproveedor("", "INSWITCH");

        $dataR = array();
        $dataR["success"] = false;
        $dataR["error"] = 1;

        $estado = 'A';
        $estado_producto = 'E';
        $tipo = 'T';

        if ($Usuario->puntoventaId != '' && $Usuario->puntoventaId != '0' && $Usuario->puntoventaId != null) {
            $PuntoVenta = new PuntoVenta("", $Usuario->puntoventaId);
            $cedula = $PuntoVenta->cedula;
            $apellido = explode(' ', $PuntoVenta->nombreContacto)[1];
        } else {
            $Registro = new Registro("", $Usuario->usuarioId);
            $cedula = $Registro->cedula;
            $apellido = $Registro->apellido1;
        }

        $usuario_id = $Usuario->usuarioId;
        $valor = $valor;
        $producto_id = $Producto->productoId;
        $moneda = $Usuario->moneda;
        $mandante = $Usuario->mandante;

        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $Usuario->mandante, $Usuario->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());
        $this->ApiKey = $Credentials->API_KEY;
        $this->Username = $Credentials->USERNAME;
        $this->Password = $Credentials->PASSWORD;
        $this->URL = $Credentials->URL;
        $RefencePayment = $Credentials->REFERENCE_BIN;

        $Pais = new Pais($Usuario->paisId);

        switch ($Pais->iso) {
            case "GT":
                $moneda = 'USD';
                break;
        }

        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
        $Transaction = $TransaccionProductoMySqlDAO->getTransaction();
        $Transaction->getConnection()->beginTransaction();

        //Impuesto a los Depositos.
        try {
            $Clasificador = new Clasificador("", "TAXDEPOSIT");
            $MandanteDetalle = new MandanteDetalle("", $Usuario->mandante, $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
            $taxedValue = $MandanteDetalle->valor;
        } catch (Exception $e) {
            $taxedValue = 0;
        }

        $totalTax = $valor * ($taxedValue / 100);
        $valorTax = $valor * (1 + $taxedValue / 100);

        $TransaccionProducto = new TransaccionProducto();
        $TransaccionProducto->setProductoId($producto_id);
        $TransaccionProducto->setUsuarioId($usuario_id);
        $TransaccionProducto->setValor($valor);
        $TransaccionProducto->setImpuesto($totalTax);
        $TransaccionProducto->setEstado($estado);
        $TransaccionProducto->setTipo($tipo);
        $TransaccionProducto->setEstadoProducto($estado_producto);
        $TransaccionProducto->setMandante($mandante);
        $TransaccionProducto->setFinalId(0);
        $TransaccionProducto->setExternoId(0);
        $transproductoId = $TransaccionProductoMySqlDAO->insert($TransaccionProducto);

        $mandante = $Usuario->mandante;
        $Tipometodo = "crypto";

        $respuesta = $this->generateToken($this->URL, '/auth-service/1.0/protocol/openid-connect/token');

        $tokenT = $respuesta->access_token;

        syslog(LOG_WARNING, "INSWITCH TOKENCRYPTO: " . json_encode($respuesta));

        $ConfigurationEnvironment = new ConfigurationEnvironment();
        if ($ConfigurationEnvironment->isDevelopment()) {
            $data = "direction=in&paymentMethodTypeClass=" . $Tipometodo . "&paymentMethodTypeStatus=available";
        } elseif ($Pais->iso == "CR") {
            $data = "direction=in&paymentMethodTypeClass=" . $Tipometodo . "&paymentMethodTypeStatus=available";
        } else {
            $data = "direction=in&country=" . $Pais->iso . "&paymentMethodTypeClass=" . $Tipometodo . "&paymentMethodTypeStatus=available";
        }

        $uuid = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(openssl_random_pseudo_bytes(16)), 4));

        $Result = $this->connectionGET($data, $tokenT, $uuid, $this->URL, '/payment-methods/1.0/paymentmethodtypes');
        syslog(LOG_WARNING, "INSWITCH PAYMENTCRYPTO: " . json_encode($Result));

        // Verificar si es un array (respuesta exitosa)
        if (is_array($Result) && count($Result) > 0) {
            $metodo = $Result[0]->paymentMethodType;

            $uuid2 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(openssl_random_pseudo_bytes(16)), 4));

            if ($Pais->iso == "BR") {
                $data2 = array(
                    "amount" => strval($valorTax),
                    "currency" => $moneda,
                    "debitParty" => array(
                        "type" => $metodo,
                        "data" => array(
                            "first_name" => $this->quitar_tildes($Registro->nombre1),
                            "last_name" => $this->quitar_tildes($apellido),
                            "document_number" => $cedula,
                            "document_type" => "CPF"
                        )
                    ),
                    "creditParty" => array(
                        "paymentMethodReference" => $RefencePayment,
                    ),
                    "descriptionText" => $transproductoId,
                    "requestingOrganisationTransactionReference" => $transproductoId,
                    "country" => $Pais->iso
                );
            } else {
                $data2 = array(
                    "amount" => strval($valorTax),
                    "currency" => $moneda,
                    "debitParty" => array(
                        "type" => $metodo,
                        "data" => array(
                            "first_name" => $this->quitar_tildes($Registro->nombre1),
                            "last_name" => $this->quitar_tildes($apellido),
                        )
                    ),
                    "creditParty" => array(
                        "paymentMethodReference" => $RefencePayment,
                    ),
                    "descriptionText" => $transproductoId,
                    "requestingOrganisationTransactionReference" => $transproductoId,
                    "country" => $Pais->iso
                );
            }

            $TransproductoDetalle = new TransproductoDetalle();
            $TransproductoDetalle->transproductoId = $transproductoId;
            $TransproductoDetalle->tValue = json_encode($data);
            $TransproductoDetalleMySqlDAO = new TransproductoDetalleMySqlDAO($Transaction);
            $TransproductoDetalleMySqlDAO->insert($TransproductoDetalle);

            $Result2 = $this->connectionPOST($data2, $tokenT, $uuid2, $this->URL, '/transactions/1.0/transactions/type/deposit');
            syslog(LOG_WARNING, "INSWITCH DATACRYPTO: " . json_encode($data2) . "RESPONSECRYPTO: " . json_encode($Result2));

            if ($Result2 != '' && $Result2->transactionStatus == "waiting") {
                $TransprodLog = new TransprodLog();
                $TransprodLog->setTransproductoId($transproductoId);
                $TransprodLog->setEstado('E');
                $TransprodLog->setTipoGenera('A');
                $TransprodLog->setComentario('Envio Solicitud de deposito');
                $TransprodLog->setTValue(json_encode($Result2));
                $TransprodLog->setUsucreaId(0);
                $TransprodLog->setUsumodifId(0);

                $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
                $TransprodLogMySqlDAO->insert($TransprodLog);

                $TransaccionProducto->setExternoId($Result2->transactionReference);
                $TransaccionProductoMySqlDAO->update($TransaccionProducto);
                $Transaction->commit();
                $useragent = $_SERVER['HTTP_USER_AGENT'];
                $jsonServer = json_encode($_SERVER);
                $serverCodif = base64_encode($jsonServer);

                $ismobile = '';
                if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {
                    $ismobile = '1';
                }

                //Detect special conditions devices
                $iPod = stripos($_SERVER['HTTP_USER_AGENT'], "iPod");
                $iPhone = stripos($_SERVER['HTTP_USER_AGENT'], "iPhone");
                $iPad = stripos($_SERVER['HTTP_USER_AGENT'], "iPad");
                $Android = stripos($_SERVER['HTTP_USER_AGENT'], "Android");
                $webOS = stripos($_SERVER['HTTP_USER_AGENT'], "webOS");

                //do something with this information
                if ($iPod || $iPhone) {
                    $ismobile = '1';
                } elseif ($iPad) {
                    $ismobile = '1';
                } elseif ($Android) {
                    $ismobile = '1';
                }
                //exec("php -f ". __DIR__ ."/../crm/AgregarCrm.php " . $Usuario->usuarioId . " " . "SOLICITUDDEPOSITOCRM" . " " . $transproductoId . " " . $serverCodif . " " . $ismobile . " > /dev/null &");

                $response = $Result2;
                $dataR = array();
                $dataR["success"] = true;
                $dataR["url"] = $response->requiredAction->data->redirectURL;
                $dataR["code"] = $response->transactionReference;
            }
        }

        return json_decode(json_encode($dataR));
    }

    /**
     * Método para gestionar cuentas virtuales y realizar depósitos.
     *
     * @param Usuario $Usuario     Objeto que contiene la información del usuario.
     * @param mixed   $Producto    Objeto que contiene la información del producto.
     * @param float   $valor       Monto del depósito.
     * @param string  $success_url URL a redirigir en caso de éxito.
     * @param string  $fail_url    URL a redirigir en caso de fallo.
     *
     * @return object Objeto con información sobre el resultado de la operación.
     */
    public function PaymentMethodsVirtualAccounts(Usuario $Usuario, $Producto, $valor, $success_url, $fail_url)
    {
        $this->success_url = $success_url;
        $this->fail_url = $fail_url;

        $Subproveedor = new Subproveedor("", "INSWITCH");
        $Registro = new Registro("", $Usuario->usuarioId);

        $data = array();
        $data["success"] = false;
        $data["error"] = 1;

        $estado = 'A';
        $estado_producto = 'E';
        $tipo = 'T';

        $usuario_id = $Usuario->usuarioId;
        $cedula = $Registro->cedula;
        $apellido = $Registro->apellido1;
        $valor = $valor;
        $producto_id = $Producto->productoId;
        $moneda = $Usuario->moneda;
        $mandante = $Usuario->mandante;
        $tipoDocumento = $Registro->tipoDoc;
        $Pais = new Pais($Usuario->paisId);

        switch ($tipoDocumento) {
            case "C":
                $tipoDoc = "nationalId";
                break;
            case "E":
                $tipoDoc = "residentId";
                break;
        }

        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $Usuario->mandante, $Usuario->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());
        $this->Username = $Credentials->USERNAME;
        $this->Password = $Credentials->PASSWORD;
        $this->URL = $Credentials->URL;
        $RefencePayment = $Credentials->REFERENCE;

        $this->ApiKey = "eyJ4NXQiOiJNV1ExTWpBMlpESm1PV1U1WXpjNFpUazFZelk1T1dVeU56SmtaV1l5TWpZNE5qa3pZVFkyWXpjNE9EY3lZMlprWmpGaVpHUmhNMkkyTUdFeU5qRmpaZyIsImtpZCI6Im9rZGF3cyIsInR5cCI6IkpXVCIsImFsZyI6IlJTMjU2In0=.eyJzdWIiOiJkb3JhZG9iZXRkZXZAY2FyYm9uLnN1cGVyIiwiYXBwbGljYXRpb24iOnsib3duZXIiOiJkb3JhZG9iZXRkZXYiLCJ0aWVyUXVvdGFUeXBlIjpudWxsLCJ0aWVyIjoiVW5saW1pdGVkIiwibmFtZSI6IkRlZmF1bHRBcHBsaWNhdGlvbiIsImlkIjo5OSwidXVpZCI6ImYyNDEwYzcwLTE1NzctNDA0NS1iYjg5LTk1NDMyNTEyOWE0MCJ9LCJpc3MiOiJodHRwczpcL1wvYXBpbS1tYW5hZ2VtZW50LmFwcHMuaW5zLmluc3dodWIuY29tOjQ0M1wvb2F1dGgyXC90b2tlbiIsInRpZXJJbmZvIjp7IkJyb256ZSI6eyJ0aWVyUXVvdGFUeXBlIjoicmVxdWVzdENvdW50IiwiZ3JhcGhRTE1heENvbXBsZXhpdHkiOjAsImdyYXBoUUxNYXhEZXB0aCI6MCwic3RvcE9uUXVvdGFSZWFjaCI6dHJ1ZSwic3Bpa2VBcnJlc3RMaW1pdCI6MCwic3Bpa2VBcnJlc3RVbml0IjpudWxsfSwiVW5saW1pdGVkIjp7InRpZXJRdW90YVR5cGUiOiJyZXF1ZXN0Q291bnQiLCJncmFwaFFMTWF4Q29tcGxleGl0eSI6MCwiZ3JhcGhRTE1heERlcHRoIjowLCJzdG9wT25RdW90YVJlYWNoIjp0cnVlLCJzcGlrZUFycmVzdExpbWl0IjowLCJzcGlrZUFycmVzdFVuaXQiOm51bGx9fSwia2V5dHlwZSI6IlNBTkRCT1giLCJwZXJtaXR0ZWRSZWZlcmVyIjoiIiwic3Vic2NyaWJlZEFQSXMiOlt7InN1YnNjcmliZXJUZW5hbnREb21haW4iOiJjYXJib24uc3VwZXIiLCJuYW1lIjoiQXV0aC1TZXJ2aWNlIiwiY29udGV4dCI6IlwvYXV0aC1zZXJ2aWNlXC8xLjAiLCJwdWJsaXNoZXIiOiJhZG1pbiIsInZlcnNpb24iOiIxLjAiLCJzdWJzY3JpcHRpb25UaWVyIjoiVW5saW1pdGVkIn0seyJzdWJzY3JpYmVyVGVuYW50RG9tYWluIjoiY2FyYm9uLnN1cGVyIiwibmFtZSI6IkVudGl0aWVzIiwiY29udGV4dCI6IlwvZW50aXRpZXNcLzEuMiIsInB1Ymxpc2hlciI6ImFkbWluIiwidmVyc2lvbiI6IjEuMiIsInN1YnNjcmlwdGlvblRpZXIiOiJVbmxpbWl0ZWQifSx7InN1YnNjcmliZXJUZW5hbnREb21haW4iOiJjYXJib24uc3VwZXIiLCJuYW1lIjoiRlgiLCJjb250ZXh0IjoiXC9meFwvMS4wIiwicHVibGlzaGVyIjoicHVibGlzaGVyLnVzZXIiLCJ2ZXJzaW9uIjoiMS4wIiwic3Vic2NyaXB0aW9uVGllciI6IlVubGltaXRlZCJ9LHsic3Vic2NyaWJlclRlbmFudERvbWFpbiI6ImNhcmJvbi5zdXBlciIsIm5hbWUiOiJIb3N0ZWRDaGVja291dCIsImNvbnRleHQiOiJcL2hvc3RlZGNoZWNrb3V0XC8xLjAiLCJwdWJsaXNoZXIiOiJhZG1pbiIsInZlcnNpb24iOiIxLjAiLCJzdWJzY3JpcHRpb25UaWVyIjoiVW5saW1pdGVkIn0seyJzdWJzY3JpYmVyVGVuYW50RG9tYWluIjoiY2FyYm9uLnN1cGVyIiwibmFtZSI6Ik5vdGlmaWNhdGlvbkVuZ2luZSIsImNvbnRleHQiOiJcL25vdGlmaWNhdGlvbmVuZ2luZVwvMS4wIiwicHVibGlzaGVyIjoiYWRtaW4iLCJ2ZXJzaW9uIjoiMS4wIiwic3Vic2NyaXB0aW9uVGllciI6IkJyb256ZSJ9LHsic3Vic2NyaWJlclRlbmFudERvbWFpbiI6ImNhcmJvbi5zdXBlciIsIm5hbWUiOiJPVFAtTWFuYWdlciIsImNvbnRleHQiOiJcL290cC1tYW5hZ2VyXC8xLjAiLCJwdWJsaXNoZXIiOiJhZG1pbiIsInZlcnNpb24iOiIxLjAiLCJzdWJzY3JpcHRpb25UaWVyIjoiVW5saW1pdGVkIn0seyJzdWJzY3JpYmVyVGVuYW50RG9tYWluIjoiY2FyYm9uLnN1cGVyIiwibmFtZSI6IldhbGxldHMiLCJjb250ZXh0IjoiXC93YWxsZXRzXC8xLjAiLCJwdWJsaXNoZXIiOiJwdWJsaXNoZXIudXNlciIsInZlcnNpb24iOiIxLjAiLCJzdWJzY3JpcHRpb25UaWVyIjoiVW5saW1pdGVkIn0seyJzdWJzY3JpYmVyVGVuYW50RG9tYWluIjoiY2FyYm9uLnN1cGVyIiwibmFtZSI6IkdpZnRDYXJkIiwiY29udGV4dCI6IlwvZ2lmdGNhcmRcLzEuMCIsInB1Ymxpc2hlciI6InB1Ymxpc2hlci51c2VyIiwidmVyc2lvbiI6IjEuMCIsInN1YnNjcmlwdGlvblRpZXIiOiJVbmxpbWl0ZWQifSx7InN1YnNjcmliZXJUZW5hbnREb21haW4iOiJjYXJib24uc3VwZXIiLCJuYW1lIjoiS1lDIiwiY29udGV4dCI6Ilwva3ljXC8xLjAiLCJwdWJsaXNoZXIiOiJwdWJsaXNoZXIudXNlciIsInZlcnNpb24iOiIxLjAiLCJzdWJzY3JpcHRpb25UaWVyIjoiVW5saW1pdGVkIn0seyJzdWJzY3JpYmVyVGVuYW50RG9tYWluIjoiY2FyYm9uLnN1cGVyIiwibmFtZSI6IlNlcnZpY2VQcm92aWRlcnMiLCJjb250ZXh0IjoiXC9zZXJ2aWNlcHJvdmlkZXJzXC8zLjAiLCJwdWJsaXNoZXIiOiJwdWJsaXNoZXIudXNlciIsInZlcnNpb24iOiIzLjAiLCJzdWJzY3JpcHRpb25UaWVyIjoiVW5saW1pdGVkIn0seyJzdWJzY3JpYmVyVGVuYW50RG9tYWluIjoiY2FyYm9uLnN1cGVyIiwibmFtZSI6IlNtYXJ0TGVuZGluZyIsImNvbnRleHQiOiJcL3NtYXJ0TGVuZGluZ1wvMS4wIiwicHVibGlzaGVyIjoiYWRtaW4iLCJ2ZXJzaW9uIjoiMS4wIiwic3Vic2NyaXB0aW9uVGllciI6IlVubGltaXRlZCJ9LHsic3Vic2NyaWJlclRlbmFudERvbWFpbiI6ImNhcmJvbi5zdXBlciIsIm5hbWUiOiJUcmFuc2FjdGlvbnMiLCJjb250ZXh0IjoiXC90cmFuc2FjdGlvbnNcLzEuMCIsInB1Ymxpc2hlciI6ImFkbWluIiwidmVyc2lvbiI6IjEuMCIsInN1YnNjcmlwdGlvblRpZXIiOiJCcm9uemUifSx7InN1YnNjcmliZXJUZW5hbnREb21haW4iOiJjYXJib24uc3VwZXIiLCJuYW1lIjoiVmlydHVhbC1BY2NvdW50cyIsImNvbnRleHQiOiJcL3ZpcnR1YWxhY2NvdW50c1wvMS4wIiwicHVibGlzaGVyIjoicG9ydGFsIiwidmVyc2lvbiI6IjEuMCIsInN1YnNjcmlwdGlvblRpZXIiOiJCcm9uemUifV0sInBlcm1pdHRlZElQIjoiIiwiaWF0IjoxNjk0MTE5ODA5LCJqdGkiOiI0ZDBlZTExZC00ZDI1LTRhNGItYjZlMS1lZTc4ZjI0M2UyOWYifQ==.n5zqtoibf_Van1pevU017esYUiepOrQpFDY3FnySBow6QMVRSv4YShFk2f4XGjtMsW1NVLN3_Qk-1DyKR9njQthRwh049nSM6znKkBVkV72Lb_aOwspMXbdy6s0PSpoQeiHZA3DC432RKjpE-zxZYZUFvUz-2uXT6XTDD0gSAQ0JztIiu99qyB8WX9foaWAzrrtD5cxjrlUl-FOOt4NIRIcPucNsP87d1Aez10oxr8tHYg9HfzzBx-GvuOdhvRK8NcGoJb1X14oB4CUM3d9wql7SlhNV6pfjb4-RdVj-NskUJQ2T5n-excWWxD98EVf1VwQsN0ciHptyxnLkzDgRVA==";

        $moneda = 'CRC';

        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
        $Transaction = $TransaccionProductoMySqlDAO->getTransaction();
        $Transaction->getConnection()->beginTransaction();

        //Impuesto a los Depositos.
        try {
            $Clasificador = new Clasificador("", "TAXDEPOSIT");
            $MandanteDetalle = new MandanteDetalle("", $Usuario->mandante, $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
            $taxedValue = $MandanteDetalle->valor;
        } catch (Exception $e) {
            $taxedValue = 0;
        }

        $totalTax = $valor * ($taxedValue / 100);
        $valorTax = $valor * (1 + $taxedValue / 100);

        $TransaccionProducto = new TransaccionProducto();
        $TransaccionProducto->setProductoId($producto_id);
        $TransaccionProducto->setUsuarioId($usuario_id);
        $TransaccionProducto->setValor($valor);
        $TransaccionProducto->setImpuesto($totalTax);
        $TransaccionProducto->setEstado($estado);
        $TransaccionProducto->setTipo($tipo);
        $TransaccionProducto->setEstadoProducto($estado_producto);
        $TransaccionProducto->setMandante($mandante);
        $TransaccionProducto->setFinalId(0);
        $TransaccionProducto->setExternoId(0);
        $transproductoId = $TransaccionProductoMySqlDAO->insert($TransaccionProducto);

        $mandante = $Usuario->mandante;
        $Tipometodo = "sinpein-cr";

        $respuesta = $this->generateToken($this->URL, '/auth-service/1.0/protocol/openid-connect/token');
        $tokenT = $respuesta->access_token;

        $ConfigurationEnvironment = new ConfigurationEnvironment();

        $data = "paymentMethodTypeId%40" . $Tipometodo . "%24virtualAccountKey%40" . $tipoDoc . "%40" . $Registro->cedula;


        $uuid = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(openssl_random_pseudo_bytes(16)), 4));


        $Result = $this->connectionGETAccount($data, $tokenT, $uuid, $this->URL, '/virtualaccounts/1.0/virtualaccounts/');

        //Validar si ya existe para evitar duplicados
        if ($Result->errorCode != "NOT_FOUND" || $Result->errorCode == null) {
            //hacer el deposito
            $data2 = array(
                "amount" => $valorTax,
                "currency" => $moneda,
                "mode" => "perform",
                "debitParty" => array(
                    "type" => $Tipometodo,
                    "virtualAccountKey" => $tipoDoc . "@" . $cedula,
                    "data" => array(),
                ),
                "creditParty" => array(

                    "paymentMethodReference" => $RefencePayment
                ),
                "descriptionText" => "Payment using Order",
                "country" => $Pais->iso

            );
            $uuid2 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(openssl_random_pseudo_bytes(16)), 4));

            $TransproductoDetalle = new TransproductoDetalle();
            $TransproductoDetalle->transproductoId = $transproductoId;
            $TransproductoDetalle->tValue = json_encode($data);
            $TransproductoDetalleMySqlDAO = new TransproductoDetalleMySqlDAO($Transaction);
            $TransproductoDetalleMySqlDAO->insert($TransproductoDetalle);

            $Result2 = $this->connectionPOST($data2, $tokenT, $uuid2, $this->URL, '/transactions/1.0/transactions/type/deposit');

            if ($Result2 != '' && $Result2->transactionStatus == "waiting") {
                $TransprodLog = new TransprodLog();
                $TransprodLog->setTransproductoId($transproductoId);
                $TransprodLog->setEstado('E');
                $TransprodLog->setTipoGenera('A');
                $TransprodLog->setComentario('Envio Solicitud de deposito');
                $TransprodLog->setTValue(json_encode($Result2));
                $TransprodLog->setUsucreaId(0);
                $TransprodLog->setUsumodifId(0);

                $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
                $TransprodLogMySqlDAO->insert($TransprodLog);

                $TransaccionProducto->setExternoId($Result2->transactionReference);
                $TransaccionProductoMySqlDAO->update($TransaccionProducto);
                $Transaction->commit();
                $useragent = $_SERVER['HTTP_USER_AGENT'];
                $jsonServer = json_encode($_SERVER);
                $serverCodif = base64_encode($jsonServer);


                $ismobile = '';
                if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {
                    $ismobile = '1';
                }

                $iPod = stripos($_SERVER['HTTP_USER_AGENT'], "iPod");
                $iPhone = stripos($_SERVER['HTTP_USER_AGENT'], "iPhone");
                $iPad = stripos($_SERVER['HTTP_USER_AGENT'], "iPad");
                $Android = stripos($_SERVER['HTTP_USER_AGENT'], "Android");
                $webOS = stripos($_SERVER['HTTP_USER_AGENT'], "webOS");

                if ($iPod || $iPhone) {
                    $ismobile = '1';
                } elseif ($iPad) {
                    $ismobile = '1';
                } elseif ($Android) {
                    $ismobile = '1';
                }

                $dataFinal = array();
                $dataFinal["success"] = true;
                $dataFinal["url"] = $Result2->instructions;
                $dataFinal["code"] = $Result2->requiredAction->data->code;
            } else {
                $dataFinal = array();
                $dataFinal["success"] = false;
                $dataFinal["url"] = "";
                $dataFinal["code"] = $Result2->errorCode;
            }
        } else {
            $data2 = array(
                "paymentMethodTypeId" => $Tipometodo,
                "paymentMethodReference" => $RefencePayment,
                "virtualAccountKey" => $tipoDoc . "@" . $cedula,
                "data" => array(
                    "document_number" => $cedula,
                    "document_type" => "nationalId",
                    "first_name" => $this->quitar_tildes($Registro->nombre1),
                    "last_name" => $this->quitar_tildes($apellido),
                ),

            );
            $uuid2 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(openssl_random_pseudo_bytes(16)), 4));

            $Result2 = $this->connectionPOST($data2, $tokenT, $uuid2, $this->URL, '/virtualaccounts/1.0/virtualaccounts');


            if ($Result2->status == "complete") {
                $data3 = array(
                    "amount" => $valorTax,
                    "currency" => $moneda,
                    "mode" => "perform",
                    "debitParty" => array(
                        "type" => $Tipometodo,
                        "virtualAccountKey" => $tipoDoc . "@" . $cedula,
                        "data" => array(),
                    ),
                    "creditParty" => array(
                        "paymentMethodReference" => $RefencePayment
                    ),
                    "descriptionText" => "Payment using Order",
                    "country" => $Pais->iso
                );
                $uuid2 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(openssl_random_pseudo_bytes(16)), 4));

                $TransproductoDetalle = new TransproductoDetalle();
                $TransproductoDetalle->transproductoId = $transproductoId;
                $TransproductoDetalle->tValue = json_encode($data);
                $TransproductoDetalleMySqlDAO = new TransproductoDetalleMySqlDAO($Transaction);
                $TransproductoDetalleMySqlDAO->insert($TransproductoDetalle);

                $Result3 = $this->connectionPOST($data3, $tokenT, $uuid2, $this->URL, '/transactions/1.0/transactions/type/deposit');

                if ($Result3 != '' && $Result3->transactionStatus == "waiting") {
                    $TransprodLog = new TransprodLog();
                    $TransprodLog->setTransproductoId($transproductoId);
                    $TransprodLog->setEstado('E');
                    $TransprodLog->setTipoGenera('A');
                    $TransprodLog->setComentario('Envio Solicitud de deposito');
                    $TransprodLog->setTValue(json_encode($Result3));
                    $TransprodLog->setUsucreaId(0);
                    $TransprodLog->setUsumodifId(0);

                    $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
                    $TransprodLogMySqlDAO->insert($TransprodLog);

                    $TransaccionProducto->setExternoId($Result3->transactionReference);
                    $TransaccionProductoMySqlDAO->update($TransaccionProducto);
                    $Transaction->commit();
                    $useragent = $_SERVER['HTTP_USER_AGENT'];
                    $jsonServer = json_encode($_SERVER);
                    $serverCodif = base64_encode($jsonServer);


                    $ismobile = '';

                    if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {
                        $ismobile = '1';
                    }
                    //Detect special conditions devices
                    $iPod = stripos($_SERVER['HTTP_USER_AGENT'], "iPod");
                    $iPhone = stripos($_SERVER['HTTP_USER_AGENT'], "iPhone");
                    $iPad = stripos($_SERVER['HTTP_USER_AGENT'], "iPad");
                    $Android = stripos($_SERVER['HTTP_USER_AGENT'], "Android");
                    $webOS = stripos($_SERVER['HTTP_USER_AGENT'], "webOS");


                    if ($iPod || $iPhone) {
                        $ismobile = '1';
                    } elseif ($iPad) {
                        $ismobile = '1';
                    } elseif ($Android) {
                        $ismobile = '1';
                    }

                    $dataFinal = array();
                    $dataFinal["success"] = true;
                    $dataFinal["url"] = $Result3->instructions;
                    $dataFinal["code"] = $Result3->requiredAction->data->code;
                }
            } else {
                $dataFinal = array();
                $dataFinal["success"] = false;
                $dataFinal["url"] = "";
                $dataFinal["code"] = $Result2->errorCode;
            }
        }
        return json_decode(json_encode($dataFinal));
    }

    /**
     * Genera un token de autenticación utilizando las credenciales proporcionadas.
     *
     * Este método realiza una solicitud HTTP POST a la URL especificada para obtener
     * un token de acceso. Utiliza las credenciales del usuario (nombre de usuario y contraseña)
     * y una clave API para autenticar la solicitud.
     *
     * @param string $url  La URL base del servicio de autenticación.
     * @param string $path El endpoint del servicio de autenticación.
     *
     * @return object|null Devuelve un objeto JSON decodificado con la respuesta del servicio
     *                     o null si la respuesta no es válida.
     */
    public function generateToken($url, $path)
    {
        $curl = new CurlWrapper($url . $path);

        $curl->setOptionsArray(array(
            CURLOPT_URL => $url . $path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'grant_type=password&username=' . $this->Username . '&password=' . $this->Password,
            CURLOPT_HTTPHEADER => array(
                'apikey: ' . $this->ApiKey,
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = $curl->execute();

        syslog(LOG_WARNING, "INSWITCH TOKEN DATA " . ($this->ApiKey) . ' ' . ($this->Username) . ' ' . ($this->Password));
        syslog(LOG_WARNING, "INSWITCH TOKEN RESPONSE " . ($response));

        return json_decode($response);
    }

    /**
     * Realiza una conexión HTTP POST utilizando cURL.
     *
     * Este método envía datos a un endpoint específico utilizando cURL y devuelve
     * la respuesta decodificada en formato JSON.
     *
     * @param array  $data  Datos que se enviarán en el cuerpo de la solicitud.
     * @param string $token Token de autenticación para la solicitud.
     * @param string $uuid  Identificador único para la correlación de la solicitud.
     * @param string $url   URL base del servicio al que se conectará.
     * @param string $path  Endpoint específico del servicio.
     *
     * @return object|null Devuelve un objeto JSON decodificado con la respuesta del servicio
     *                     o null si la respuesta no es válida.
     */
    public function connection($data, $token, $uuid, $url, $path)
    {
        $curl = new CurlWrapper($url . $path);
        $curl->setOptionsArray(array(
            CURLOPT_URL => $url . $path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'X-CorrelationID: ' . $uuid,
                'apikey: ' . $this->ApiKey,
                'X-User-Bearer: Bearer ' . $token,
                'Content-Type: application/json',
                'X-Callback-URL: ' . $this->callback_url,
                'X-Redirect-OK: ' . $this->success_url,
                'X-Redirect-Error: ' . $this->fail_url
            ),
        ));

        $response = $curl->execute();

        syslog(LOG_WARNING, "INSWITCH DATA " . json_encode($data));
        syslog(LOG_WARNING, "INSWITCH RESPONSE " . ($response));

        return json_decode($response);
    }

    /**
     * Realiza una conexión HTTP POST utilizando cURL.
     *
     * Este método envía datos a un endpoint específico utilizando cURL y devuelve
     * la respuesta decodificada en formato JSON.
     *
     * @param array  $data  Datos que se enviarán en el cuerpo de la solicitud.
     * @param string $token Token de autenticación para la solicitud.
     * @param string $uuid  Identificador único para la correlación de la solicitud.
     * @param string $url   URL base del servicio al que se conectará.
     * @param string $path  Endpoint específico del servicio.
     *
     * @return object|null Devuelve un objeto JSON decodificado con la respuesta del servicio
     *                     o null si la respuesta no es válida.
     */
    public function connectionPOST($data, $token, $uuid, $url, $path)
    {
        $curl = new CurlWrapper($url . $path);
        $curl->setOptionsArray(array(
            CURLOPT_URL => $url . $path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'X-CorrelationID: ' . $uuid,
                'apikey: ' . $this->ApiKey,
                'X-User-Bearer: Bearer' . $token,
                'Content-Type: application/json',
                'X-Callback-URL: ' . $this->callback_url,
                'X-Channel: WS',
                'X-Redirect-OK: ' . $this->success_url,
                'X-Redirect-Error: ' . $this->fail_url
            ),
        ));

        $response = $curl->execute();

        $time = time();
        syslog(LOG_WARNING, " INSWITCH DATA " . $time . ' ' . json_encode($data));
        syslog(LOG_WARNING, " INSWITCH RESPONSE " . $time . ' ' . $response);

        return json_decode($response);
    }

    /**
     * Realiza una conexión HTTP GET utilizando cURL.
     *
     * Este método envía una solicitud GET a un endpoint específico utilizando cURL
     * y devuelve la respuesta decodificada en formato JSON.
     *
     * @param string $data  Parámetros de consulta que se enviarán en la URL.
     * @param string $token Token de autenticación para la solicitud.
     * @param string $uuid  Identificador único para la correlación de la solicitud.
     * @param string $url   URL base del servicio al que se conectará.
     * @param string $path  Endpoint específico del servicio.
     *
     * @return object|null Devuelve un objeto JSON decodificado con la respuesta del servicio
     *                     o null si la respuesta no es válida.
     */
    public function connectionGET($data, $token, $uuid, $url, $path)
    {
        $curl = new CurlWrapper($url . $path . "?" . $data);
        $curl->setOptionsArray(array(
            CURLOPT_URL => $url . $path . "?" . $data,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 300,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'X-CorrelationID: ' . $uuid,
                'apikey: ' . $this->ApiKey,
                'X-User-Bearer: ' . $token,
                'Content-Type: application/json'
            ),
        ));

        $response = $curl->execute();
        return json_decode($response);
    }

    /**
     * Realiza una conexión HTTP GET para obtener información de cuentas virtuales.
     *
     * Este método envía una solicitud GET a un endpoint específico utilizando cURL
     * y devuelve la respuesta decodificada en formato JSON.
     *
     * @param string $data  Parámetros de consulta que se enviarán en la URL.
     * @param string $token Token de autenticación para la solicitud.
     * @param string $uuid  Identificador único para la correlación de la solicitud.
     * @param string $url   URL base del servicio al que se conectará.
     * @param string $path  Endpoint específico del servicio.
     *
     * @return object|null Devuelve un objeto JSON decodificado con la respuesta del servicio
     *                     o null si la respuesta no es válida.
     */
    public function connectionGETAccount($data, $token, $uuid, $url, $path)
    {
        $curl = new CurlWrapper($url . $path . $data);
        $curl->setOptionsArray(array(
            CURLOPT_URL => $url . $path . $data,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'X-CorrelationID: ' . $uuid,
                'apikey: ' . $this->ApiKey,
                'X-User-Bearer: Bearer ' . $token,
                'Content-Type: application/json'
            ),
        ));

        $response = $curl->execute();
        return json_decode($response);
    }

    /**
     * Realiza una conexión HTTP PUT utilizando cURL.
     *
     * Este método envía datos a un endpoint específico utilizando cURL y devuelve
     * la respuesta decodificada en formato JSON.
     *
     * @param array  $data  Datos que se enviarán en el cuerpo de la solicitud.
     * @param string $token Token de autenticación para la solicitud.
     * @param string $uuid  Identificador único para la correlación de la solicitud.
     * @param string $url   URL base del servicio al que se conectará.
     * @param string $path  Endpoint específico del servicio.
     *
     * @return object|null Devuelve un objeto JSON decodificado con la respuesta del servicio
     *                     o null si la respuesta no es válida.
     */
    public function connectionPUT($data, $token, $uuid, $url, $path)
    {
        $curl = new CurlWrapper($url . $path);
        $curl->setOptionsArray(array(
            CURLOPT_URL => $url . $path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_HTTPHEADER => array(
                'X-CorrelationID: ' . $uuid,
                'apikey: ' . $this->ApiKey,
                'X-User-Bearer: ' . $token,
                'Content-Type: application/json'
            ),
        ));

        $response = $curl->execute();
        return json_decode($response);
    }

    /**
     * Elimina las tildes y caracteres especiales de una cadena de texto.
     *
     * Este método reemplaza caracteres acentuados y otros caracteres especiales
     * por sus equivalentes sin acento o caracteres permitidos.
     *
     * @param string $cadena La cadena de texto que se desea procesar.
     *
     * @return string La cadena de texto sin tildes ni caracteres especiales.
     */
    public function quitar_tildes($cadena)
    {
        $no_permitidas = array("á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú", "ñ", "À", "Ã", "Ì", "Ò", "Ù", "Ã™", "Ã ", "Ã¨", "Ã¬", "Ã²", "Ã¹", "ç", "Ç", "Ã¢", "ê", "Ã®", "Ã´", "Ã»", "Ã‚", "ÃŠ", "ÃŽ", "Ã”", "Ã›", "ü", "Ã¶", "Ã–", "Ã¯", "Ã¤", "«", "Ò", "Ã", "Ã„", "Ã‹");
        $permitidas = array("a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "n", "N", "A", "E", "I", "O", "U", "a", "e", "i", "o", "u", "c", "C", "a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "u", "o", "O", "i", "a", "e", "U", "I", "A", "E");
        $texto = str_replace($no_permitidas, $permitidas, $cadena);
        return $texto;
    }

    /**
     * Elimina caracteres especiales de una cadena de texto.
     *
     * Este método utiliza una expresión regular para eliminar todos los caracteres
     * que no sean letras, números o espacios de una cadena de texto.
     *
     * @param string $cadena La cadena de texto que se desea procesar.
     *
     * @return string La cadena de texto sin caracteres especiales.
     */
    public function removeSpecialCharacters($cadena)
    {
        $patron = '/[^\p{L}\p{N}\s]/u';
        $cadena_limpia = preg_replace($patron, '', $cadena);
        return $cadena_limpia;
    }
}
