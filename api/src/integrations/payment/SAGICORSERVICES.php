<?php

/**
 * Clase SAGICORSERVICES
 *
 * Esta clase proporciona servicios de integración con el sistema de pagos Sagicor.
 * Contiene métodos para crear solicitudes de pago, verificar tarjetas, eliminar tarjetas
 * y realizar otras operaciones relacionadas con transacciones financieras.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @version    1.0.0
 * @since      2025-04-25
 */

namespace Backend\Integrations\payment;

use Backend\dto\Subproveedor;
use Backend\dto\SubproveedorMandantePais;
use Backend\dto\UsuarioMandante;
use Exception;
use Backend\dto\Pais;
use Backend\dto\Ciudad;
use Backend\dto\Usuario;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\Proveedor;
use Backend\dto\Clasificador;
use Backend\dto\TransprodLog;
use Backend\dto\MandanteDetalle;
use Backend\dto\ProductoDetalle;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransproductoDetalle;
use Backend\dto\UsuarioTarjetacredito;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransproductoDetalleMySqlDAO;
use Backend\mysql\UsuarioTarjetacreditoMySqlDAO;
use Backend\Integrations\payment\safetypay\SafetyPayProxy;

/**
 * Clase SAGICORSERVICES
 *
 * Proporciona servicios de integración con el sistema de pagos Sagicor.
 * Incluye métodos para crear solicitudes de pago, verificar tarjetas,
 * eliminar tarjetas y realizar otras operaciones relacionadas con transacciones financieras.
 */
class SAGICORSERVICES
{
    /**
     * Constructor de la clase SAGICORSERVICES.
     * Inicializa el entorno de configuración.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
        }
    }

    /**
     * Crea una solicitud de pago.
     *
     * @param Usuario  $Usuario  Objeto del usuario que realiza la transacción.
     * @param Producto $Producto Objeto del producto asociado a la transacción.
     * @param float    $valor    Monto de la transacción.
     * @param string   $urlOK    URL de redirección en caso de éxito.
     * @param string   $urlERROR URL de redirección en caso de error.
     *
     * @return object Respuesta en formato JSON con el resultado de la operación.
     */
    public function createRequestPayment(Usuario $Usuario, Producto $Producto, $valor, $urlOK, $urlERROR)
    {
        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $ConfigurationEnvironment = new ConfigurationEnvironment();

        $merchant = $Credentials->MERCHANT;
        $merchantPassword = $Credentials->MERCHANT_PASSWORD;

        $Registro = new Registro("", $Usuario->usuarioId);

        $MaxRows = 1000;
        $OrderedItem = 1;
        $SkeepRows = 0;

        $rules = [];
        array_push($rules, array("field" => "producto.estado", "data" => "A", "op" => "eq"));
        array_push($rules, array("field" => "producto_detalle.producto_id", "data" => "$Producto->productoId", "op" => "eq"));


        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json2 = json_encode($filtro);

        $data = array();
        $data["success"] = false;
        $data["error"] = 1;


        $estado = 'A';
        $estado_producto = 'E';
        $tipo = 'T';

        $banco = 0;
        $pais = $Usuario->paisId;
        $usuario_id = $Usuario->usuarioId;
        $cedula = $Registro->cedula;
        $nombre = $Usuario->nombre;
        $email = $Usuario->login;
        $valor = $valor;
        $producto_id = $Producto->productoId;
        $moneda = $Usuario->moneda;
        $mandante = $Usuario->mandante;
        $descripcion = "Deposito";

        switch ($pais) {
            case "173":
                $CountryCode = "PER";
                break;
            case "66":
                $CountryCode = "ECU";
                break;
            case "2":
                $CountryCode = "NIC";
                break;
        }

        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
        $Transaction = $TransaccionProductoMySqlDAO->getTransaction();

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

        $TransproductoDetalle = new TransproductoDetalle();
        $TransproductoDetalle->transproductoId = $transproductoId;
        $TransproductoDetalle->tValue = json_encode(($data));

        $TransproductoDetalleMySqlDAO = new TransproductoDetalleMySqlDAO($Transaction);
        $TransproductoDetalleMySqlDAO->insert($TransproductoDetalle);

        $json = '{"rules" : [{"field" : "ciudad.ciudad_id", "data": "' . $Registro->ciudadId . '","op":"eq"}] ,"groupOp" : "AND"}';
        $Pais = new Pais();
        $paises = $Pais->getPaises("pais_moneda.pais_id,ciudad.ciudad_id", "asc", $SkeepRows, $MaxRows, $json, true, $mandanteEspecifico);
        $paises = json_decode($paises);


        $data = array();

        $data["billing"] = array();
        $data["billing"]["address"] = array();
        $data["billing"]["address"]["city"] = $paises->data['0']->{"ciudad.ciudad_nom"};
        $data["billing"]["address"]["company"] = "Justbet";
        $data["billing"]["address"]["country"] = 'JAM';
        $data["billing"]["address"]["street"] = $Registro->direccion;

        $data["customer"] = array();
        $data["customer"]["firstName"] = $Registro->nombre1;
        $data["customer"]["lastName"] = $Registro->apellido1;

        $data["interaction"]["operation"] = "NONE";

        $data["order"] = array();
        $data["order"]["id"] = $TransaccionProducto->transproductoId;
        $data["order"]["notificationUrl"] = "https://integrations.virtualsoft.tech/payment/sagicor/confirm/";
        $data["order"]["currency"] = 'JMD';

        $data["apiOperation"] = "CREATE_CHECKOUT_SESSION";

        $service_url = $Credentials->SERVICE_URL . $merchant . "/session";

        $curl = curl_init($service_url);

        curl_setopt($curl, CURLOPT_USERPWD, 'merchant.' . $merchant . ":" . $merchantPassword);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        $Result = (curl_exec($curl));

        $Result = json_decode($Result);
        curl_close($curl);

        if ($Result->result == "SUCCESS") {
            $Session = $Result->session;
            $sessionid = $Session->id;

            $TransaccionProducto->setExternoId($sessionid);

            $t_value = json_encode(array());

            $TransprodLog = new TransprodLog();
            $TransprodLog->setTransproductoId($transproductoId);
            $TransprodLog->setEstado('E');
            $TransprodLog->setTipoGenera('A');
            $TransprodLog->setComentario('Envio Solicitud de deposito');
            $TransprodLog->setTValue($t_value);
            $TransprodLog->setUsucreaId(0);
            $TransprodLog->setUsumodifId(0);

            $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
            $TransprodLogMySqlDAO->insert($TransprodLog);

            $Transaction->commit();

            $baseUrl = $Credentials->SERVICE_URL_DEPOSIT;

            if (strpos($_SERVER['HTTP_REFERER'], "acropolis") !== false) {
                $baseUrl = $Credentials->SERVICE_URL_DEPOSIT_ACROPOLIS;
            }

            $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
            $TransaccionProductoMySqlDAO->update($TransaccionProducto);
            $TransaccionProductoMySqlDAO->getTransaction()->commit();

            $data = array();
            $data["success"] = true;
            $data["url"] = $baseUrl . $ConfigurationEnvironment->encrypt($transproductoId);
        } else {
            $data = array();
            $data["success"] = false;
        }
        return json_decode(json_encode($data));
    }

    /**
     * Crea una solicitud de pago con tarjeta.
     *
     * @param Usuario  $Usuario     Objeto del usuario que realiza la transacción.
     * @param Producto $Producto    Objeto del producto asociado a la transacción.
     * @param integer  $ProveedorId ID del proveedor.
     * @param object   $datos       Datos de la tarjeta y otros detalles de la transacción.
     *
     * @return object Respuesta en formato JSON con el resultado de la operación.
     */
    public function createRequestPayment2(Usuario $Usuario, Producto $Producto, $ProveedorId, $datos)
    {
        $num_tarjeta = $datos->num_tarjeta;
        $expiry_month = $datos->expiry_month;
        $expiry_year = $datos->expiry_year;
        $cvc = $datos->cvc;

        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $ConfigurationEnvironment = new ConfigurationEnvironment();

        $merchant = $Credentials->MERCHANT;
        $merchantPassword = $Credentials->MERCHANT_PASSWORD;

        if ($ConfigurationEnvironment->isProduction()) {
            $notificationUrl = "https://integrations.virtualsoft.tech/payment/sagicor/confirm2/";
        } else {
            $notificationUrl = "https://apidev.virtualsoft.tech/integrations/payment/sagicor/confirm2/";
        }

        $Registro = new Registro("", $Usuario->usuarioId);

        $MaxRows = 1000;
        $OrderedItem = 1;
        $SkeepRows = 0;

        $rules = [];
        array_push($rules, array("field" => "producto.estado", "data" => "A", "op" => "eq"));
        array_push($rules, array("field" => "producto_detalle.producto_id", "data" => "$Producto->productoId", "op" => "eq"));

        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json2 = json_encode($filtro);

        $data = array();
        $data["success"] = false;
        $data["error"] = 1;

        $estado = 'A';
        $estado_producto = 'E';
        $tipo = 'T';

        $banco = 0;
        $pais = $Usuario->paisId;
        $usuario_id = $Usuario->usuarioId;
        $cedula = $Registro->cedula;
        $nombre = $Usuario->nombre;
        $email = $Usuario->login;
        $valormin1 = 01;
        $valormax1 = 99;

        $valormin = 1;
        $valormax = 5;

        $valorfinal1 = rand($valormin, $valormax);
        $valorfinal2 = rand($valormin1, $valormax1);
        if ($valorfinal1 == 5) {
            $valorfinal = $valorfinal1 . '.00';
        } else {
            $valorfinal = $valorfinal1 . '.' . $valorfinal2;
        }


        $producto_id = $Producto->productoId;
        $moneda = $Usuario->moneda;
        $mandante = $Usuario->mandante;
        $descripcion = "Deposito";

        switch ($pais) {
            case "173":
                $CountryCode = "PER";
                break;
            case "66":
                $CountryCode = "ECU";
                break;
            case "2":
                $CountryCode = "NIC";
                break;
        }

        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
        $Transaction = $TransaccionProductoMySqlDAO->getTransaction();

        //Impuesto a los Depositos.
        try {
            $Clasificador = new Clasificador("", "TAXDEPOSIT");
            $MandanteDetalle = new MandanteDetalle("", $Usuario->mandante, $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
            $taxedValue = $MandanteDetalle->valor;
        } catch (Exception $e) {
            $taxedValue = 0;
        }

        $totalTax = $valorfinal * ($taxedValue / 100);
        $valorTax = $valorfinal * (1 + $taxedValue / 100);

        $TransaccionProducto = new TransaccionProducto();
        $TransaccionProducto->setProductoId($producto_id);
        $TransaccionProducto->setUsuarioId($usuario_id);
        $TransaccionProducto->setValor($valorfinal);
        $TransaccionProducto->setImpuesto($totalTax);
        $TransaccionProducto->setEstado($estado);
        $TransaccionProducto->setTipo($tipo);
        $TransaccionProducto->setEstadoProducto($estado_producto);
        $TransaccionProducto->setMandante($mandante);
        $TransaccionProducto->setFinalId(0);
        $TransaccionProducto->setExternoId(0);

        $transproductoId = $TransaccionProductoMySqlDAO->insert($TransaccionProducto);

        $TransproductoDetalle = new TransproductoDetalle();
        $TransproductoDetalle->transproductoId = $transproductoId;
        $TransproductoDetalle->tValue = json_encode(($data));
        $TransproductoDetalleMySqlDAO = new TransproductoDetalleMySqlDAO($Transaction);
        $TransproductoDetalleMySqlDAO->insert($TransproductoDetalle);

        $json = '{"rules" : [{"field" : "ciudad.ciudad_id", "data": "' . $Registro->ciudadId . '","op":"eq"}] ,"groupOp" : "AND"}';
        $Pais = new Pais();
        $paises = $Pais->getPaises("pais_moneda.pais_id,ciudad.ciudad_id", "asc", $SkeepRows, $MaxRows, $json, true, $mandanteEspecifico);
        $paises = json_decode($paises);

        //Creación de la session
        $data = array();

        $data["billing"] = array();
        $data["billing"]["address"] = array();
        $data["billing"]["address"]["city"] = $paises->data['0']->{"ciudad.ciudad_nom"};
        $data["billing"]["address"]["company"] = "Justbet";
        $data["billing"]["address"]["country"] = 'JAM';
        $data["billing"]["address"]["street"] = $Registro->direccion;

        $data["customer"] = array();

        $data["customer"]["firstName"] = $Registro->nombre1;
        $data["customer"]["lastName"] = $Registro->apellido1;

        $data["interaction"]["operation"] = "NONE";

        $data["order"] = array();
        $data["order"]["id"] = $TransaccionProducto->transproductoId;
        $data["order"]["notificationUrl"] = $notificationUrl;
        $data["order"]["currency"] = 'JMD';

        $data["apiOperation"] = "CREATE_CHECKOUT_SESSION";

        $service_url = $Credentials->SERVICE_URL . $merchant . "/session";

        $curl = curl_init($service_url);

        curl_setopt($curl, CURLOPT_USERPWD, 'merchant.' . $merchant . ":" . $merchantPassword);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        $Result = (curl_exec($curl));

        syslog(LOG_WARNING, " SAGICOR DATA2 " . json_encode($data));
        syslog(LOG_WARNING, " SAGICOR DATA2 " . $Result);

        $Result = json_decode($Result);
        curl_close($curl);


        if ($Result->result == "SUCCESS") {
            $Session = $Result->session;

            $this->sessionid = $Session->id;

            //Creación del Token
            $data2 = array(
                "session" => array(
                    "id" => $this->sessionid,
                ),
                "sourceOfFunds" => array(
                    "type" => "CARD",
                    "provided" => array(
                        "card" => array(
                            "expiry" => array(
                                "month" => $expiry_month,
                                "year" => $expiry_year,

                            ),
                            "number" => str_replace(' ', '', $num_tarjeta),
                            "securityCode" => $cvc
                        ),
                    ),
                ),
            );

            $service_url = $Credentials->SERVICE_URL . $merchant . "/token";


            $curl = curl_init($service_url);

            curl_setopt($curl, CURLOPT_USERPWD, 'merchant.' . $merchant . ":" . $merchantPassword);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data2));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            $Result2 = (curl_exec($curl));

            syslog(LOG_WARNING, " SAGICOR DATA3 " . json_encode($data2));
            syslog(LOG_WARNING, " SAGICOR DATA3 " . $Result2);

            $Result2 = json_decode($Result2);

            if ($Result2->result == "SUCCESS") {
                $sagToken = $Result2->token;
                $sagbrand = $Result2->sourceOfFunds->provided->card->brand;
                $sagexpiry = $Result2->sourceOfFunds->provided->card->expiry;
                $sagfundingMethod = $Result2->sourceOfFunds->provided->card->fundingMethod;
                $sagnumber = $Result2->sourceOfFunds->provided->card->number;
                $sagscheme = $Result2->sourceOfFunds->provided->card->scheme;

                $data5 = array();
                $data5["apiOperation"] = "VERIFY";

                $data5["order"] = array();
                $data5["order"]["currency"] = $Usuario->moneda;

                $data5["session"] = array();
                $data5["session"]["id"] = $this->sessionid;

                $data5["sourceOfFunds"] = array();
                $data5["sourceOfFunds"]["token"] = $sagToken;
                $data5["sourceOfFunds"]["type"] = "CARD";

                $service_url = $Credentials->SERVICE_URL . $merchant . "/order/" . $TransaccionProducto->transproductoId . "/transaction/1";

                $curl = curl_init($service_url);

                curl_setopt($curl, CURLOPT_USERPWD, 'merchant.' . $merchant . ":" . $merchantPassword);
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data5));
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");

                $Result5 = (curl_exec($curl));

                syslog(LOG_WARNING, " SAGICOR DATA " . json_encode($data5));
                syslog(LOG_WARNING, " SAGICOR DATA " . $Result5);

                $Result5 = json_decode($Result5);
                curl_close($curl);


                if ($Result5->response->gatewayCode == "APPROVED") {
                    $data3 = array();
                    $data3["apiOperation"] = "PAY";


                    $data3["order"] = array();
                    $data3["order"]["amount"] = $valorTax;
                    $data3["order"]["currency"] = $Usuario->moneda;
                    $data3["order"]["reference"] = $TransaccionProducto->transproductoId;


                    $data3["session"] = array();
                    $data3["session"]["id"] = $this->sessionid;

                    $data3["sourceOfFunds"] = array();
                    $data3["sourceOfFunds"]["token"] = $sagToken;

                    $service_url = $Credentials->SERVICE_URL . $merchant . "/order/" . $TransaccionProducto->transproductoId . "/transaction/2";

                    $curl = curl_init($service_url);

                    curl_setopt($curl, CURLOPT_USERPWD, 'merchant.' . $merchant . ":" . $merchantPassword);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data3));
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");

                    $Result3 = (curl_exec($curl));

                    syslog(LOG_WARNING, " SAGICOR DATA PAY " . json_encode($data3));
                    syslog(LOG_WARNING, " SAGICOR DATA PAY " . $Result3);

                    $Result3 = json_decode($Result3);
                    curl_close($curl);


                    if ($Result3 != "" && $Result3 != null) {
                        if ($Result3->response != null) {
                            if ($Result3->response->gatewayCode == "APPROVED") {
                                $Transaction->commit();

                                $Proveedor = new Proveedor($ProveedorId);

                                $UsuarioTarjetacredito = new UsuarioTarjetacredito();
                                $numTarjeta = substr_replace($sagnumber, '********', 4, 8);
                                $UsuarioTarjetacredito->setUsuarioId($TransaccionProducto->getUsuarioId());
                                $UsuarioTarjetacredito->setProveedorId($Proveedor->getProveedorId());
                                $UsuarioTarjetacredito->setCuenta($numTarjeta);
                                $UsuarioTarjetacredito->setCvv('');
                                $UsuarioTarjetacredito->setFechaExpiracion(('2000-01-01 00:00:00'));
                                $UsuarioTarjetacredito->setToken($ConfigurationEnvironment->encrypt($sagToken));
                                $UsuarioTarjetacredito->setEstado('P');
                                $UsuarioTarjetacredito->setUsucreaId('0');
                                $UsuarioTarjetacredito->setUsumodifId('0');
                                $UsuarioTarjetacredito->setDescripcion($sagbrand);

                                $UsuarioTarjetacreditoMySqlDAO = new UsuarioTarjetacreditoMySqlDAO();
                                $idTarjeta = $UsuarioTarjetacreditoMySqlDAO->insert($UsuarioTarjetacredito);
                                $UsuarioTarjetacreditoMySqlDAO->getTransaction()->commit();

                                $TransaccionProducto = new TransaccionProducto($transproductoId);

                                $TransaccionProducto->setUsutarjetacredId($idTarjeta);

                                $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();

                                $TransaccionProductoMySqlDAO->update($TransaccionProducto);
                                $TransaccionProductoMySqlDAO->getTransaction()->commit();
                                $transaccionRealizada = $Result->transaction->acquirer->transactionId;
                                $data4 = array();
                                $data4["apiOperation"] = "REFUND";

                                $data4["transaction"] = array();
                                $data4["transaction"]["amount"] = $valorTax;
                                $data4["transaction"]["currency"] = $Usuario->moneda;

                                $service_url = $Credentials->SERVICE_URL . $merchant . "/order/" . $TransaccionProducto->transproductoId . "/transaction/3";

                                $curl = curl_init($service_url);

                                curl_setopt($curl, CURLOPT_USERPWD, 'merchant.' . $merchant . ":" . $merchantPassword);
                                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data4));
                                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");

                                $Result4 = (curl_exec($curl));
                                syslog(LOG_WARNING, " SAGICOR DATA REFUND " . json_encode($data4));
                                syslog(LOG_WARNING, " SAGICOR DATA REFUND " . $Result4);

                                $Result4 = json_decode($Result4);
                                curl_close($curl);


                                if ($Result4->response->gatewayCode == "APPROVED") {
                                    $response["success"] = true;
                                    $response["Message"] = "Approved Card";
                                    $response["Id"] = $idTarjeta;
                                } else {
                                    $response["success"] = false;
                                    $response["Message"] = "Error in the refund";
                                }
                            } else {
                                $response["success"] = false;
                                $response["Message"] = "Random transaction error";
                                if ($Result3->response->acquirerMessage != '') {
                                    $response["Message"] = $Result3->response->acquirerMessage;
                                }
                            }
                        } else {
                            $response["success"] = false;
                            $response["Message"] = "Transaction failed";
                        }
                    }
                } else {
                    $response["success"] = false;
                    $response["Message"] = "Card Verification Error";
                    if ($Result5->response->acquirerMessage != '') {
                        $response["Message"] = $Result5->response->acquirerMessage;
                    }
                }
            }
        } else {
            $data = array();
            $data["success"] = false;
            $data["success"] = "Error de session";
        }

        return json_decode(json_encode($response));
    }

    /**
     * Crea una solicitud de pago utilizando un token de tarjeta existente.
     *
     * @param Usuario  $Usuario     Objeto del usuario que realiza la transacción.
     * @param Producto $Producto    Objeto del producto asociado a la transacción.
     * @param integer  $ProveedorId ID del proveedor.
     * @param object   $datos       Datos de la tarjeta y otros detalles de la transacción.
     *
     * @return object Respuesta en formato JSON con el resultado de la operación.
     */
    public function createRequestPayment3(Usuario $Usuario, Producto $Producto, $ProveedorId, $datos)
    {
        $num_tarjeta = $datos->num_tarjeta;
        $expiry_month = $datos->expiry_month;
        $expiry_year = $datos->expiry_year;
        $cvc = $datos->cvc;
        $id = $datos->id;

        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $ConfigurationEnvironment = new ConfigurationEnvironment();

        $merchant = $Credentials->MERCHANT;
        $merchantPassword = $Credentials->MERCHANT_PASSWORD;

        if ($ConfigurationEnvironment->isProduction()) {
            $notificationUrl = "https://integrations.virtualsoft.tech/payment/sagicor/confirm/";
        } else {
            $notificationUrl = "https://apidev.virtualsoft.tech/integrations/payment/sagicor/confirm/";
        }

        $Registro = new Registro("", $Usuario->usuarioId);
        $UsuarioTarjetacredito = new UsuarioTarjetacredito($id);

        $token = $ConfigurationEnvironment->decrypt($UsuarioTarjetacredito->token);
        $MaxRows = 1000;
        $OrderedItem = 1;
        $SkeepRows = 0;

        $rules = [];
        array_push($rules, array("field" => "producto.estado", "data" => "A", "op" => "eq"));
        array_push($rules, array("field" => "producto_detalle.producto_id", "data" => "$Producto->productoId", "op" => "eq"));

        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json2 = json_encode($filtro);

        $data = array();
        $data["success"] = false;
        $data["error"] = 1;

        $estado = 'A';
        $estado_producto = 'E';
        $tipo = 'T';

        $banco = 0;
        $pais = $Usuario->paisId;
        $usuario_id = $Usuario->usuarioId;
        $cedula = $Registro->cedula;
        $nombre = $Usuario->nombre;
        $email = $Usuario->login;

        $valorfinal = $datos->amount;
        $producto_id = $Producto->productoId;
        $moneda = $Usuario->moneda;
        $mandante = $Usuario->mandante;
        $descripcion = "Deposito";

        switch ($pais) {
            case "173":
                $CountryCode = "PER";
                break;
            case "66":
                $CountryCode = "ECU";
                break;
            case "2":
                $CountryCode = "NIC";
                break;
        }


        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
        $Transaction = $TransaccionProductoMySqlDAO->getTransaction();

        //Impuesto a los Depositos.
        try {
            $Clasificador = new Clasificador("", "TAXDEPOSIT");
            $MandanteDetalle = new MandanteDetalle("", $Usuario->mandante, $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
            $taxedValue = $MandanteDetalle->valor;
        } catch (Exception $e) {
            $taxedValue = 0;
        }

        $totalTax = $valorfinal * ($taxedValue / 100);
        $valorTax = $valorfinal * (1 + $taxedValue / 100);

        $TransaccionProducto = new TransaccionProducto();
        $TransaccionProducto->setProductoId($producto_id);
        $TransaccionProducto->setUsuarioId($usuario_id);
        $TransaccionProducto->setValor($valorfinal);
        $TransaccionProducto->setImpuesto($totalTax);
        $TransaccionProducto->setEstado($estado);
        $TransaccionProducto->setTipo($tipo);
        $TransaccionProducto->setEstadoProducto($estado_producto);
        $TransaccionProducto->setMandante($mandante);
        $TransaccionProducto->setFinalId(0);
        $TransaccionProducto->setExternoId(0);
        $transproductoId = $TransaccionProductoMySqlDAO->insert($TransaccionProducto);

        $TransproductoDetalle = new TransproductoDetalle();
        $TransproductoDetalle->transproductoId = $transproductoId;
        $TransproductoDetalle->tValue = json_encode(($data));

        $TransproductoDetalleMySqlDAO = new TransproductoDetalleMySqlDAO($Transaction);
        $TransproductoDetalleMySqlDAO->insert($TransproductoDetalle);

        $Transaction->commit();
        $json = '{"rules" : [{"field" : "ciudad.ciudad_id", "data": "' . $Registro->ciudadId . '","op":"eq"}] ,"groupOp" : "AND"}';
        $Pais = new Pais();
        $paises = $Pais->getPaises("pais_moneda.pais_id,ciudad.ciudad_id", "asc", $SkeepRows, $MaxRows, $json, true, $mandanteEspecifico);
        $paises = json_decode($paises);

        //Creación de la session
        $data = array();

        $data["billing"] = array();
        $data["billing"]["address"] = array();
        $data["billing"]["address"]["city"] = $paises->data['0']->{"ciudad.ciudad_nom"};
        $data["billing"]["address"]["company"] = "Justbet";
        $data["billing"]["address"]["country"] = 'JAM';
        $data["billing"]["address"]["street"] = $Registro->direccion;

        $data["customer"] = array();

        $data["customer"]["firstName"] = $Registro->nombre1;
        $data["customer"]["lastName"] = $Registro->apellido1;

        $data["interaction"]["operation"] = "NONE";

        $data["order"] = array();
        $data["order"]["id"] = $TransaccionProducto->transproductoId;
        $data["order"]["notificationUrl"] = $notificationUrl;
        $data["order"]["currency"] = 'JMD';

        $data["apiOperation"] = "CREATE_CHECKOUT_SESSION";

        $service_url = $Credentials->SERVICE_URL . $merchant . "/session";

        $curl = curl_init($service_url);

        curl_setopt($curl, CURLOPT_USERPWD, 'merchant.' . $merchant . ":" . $merchantPassword);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        $Result = (curl_exec($curl));

        $Result = json_decode($Result);
        curl_close($curl);

        if ($Result->result == "SUCCESS") {
            $sagToken = $token;
            $Session = $Result->session;

            $this->sessionid = $Session->id;
            $data3 = array();
            $data3["apiOperation"] = "PAY";

            $data3["order"] = array();
            $data3["order"]["amount"] = $valorTax;
            $data3["order"]["currency"] = $Usuario->moneda;
            $data3["order"]["reference"] = $TransaccionProducto->transproductoId;

            $data3["session"] = array();
            $data3["session"]["id"] = $this->sessionid;

            $data3["sourceOfFunds"] = array();
            $data3["sourceOfFunds"]["token"] = $sagToken;

            $service_url = $Credentials->SERVICE_URL . $merchant . "/order/" . $TransaccionProducto->transproductoId . "/transaction/1";

            $curl = curl_init($service_url);

            curl_setopt($curl, CURLOPT_USERPWD, 'merchant.' . $merchant . ":" . $merchantPassword);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data3));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");

            $Result3 = (curl_exec($curl));
            syslog(LOG_WARNING, " SAGICOR DATA PAY " . json_encode($data3));
            syslog(LOG_WARNING, " SAGICOR DATA PAY " . $Result3);

            $Result3 = json_decode($Result3);

            curl_close($curl);


            if ($Result3 != null) {
                if ($Result3->response != null) {
                    if ($Result3->response->gatewayCode == "APPROVED") {
                        $transaccion_id = $Result3->order->reference;
                        // Asignamos variables por tipo de transaccion
                        $tipo_genera = 'A';
                        // Estado especial por proveedor (A:Aprobado,P:Pendiente,R:Rechazada)
                        $estado = 'A';
                        $IdTrans = $Result3->transaction->acquirer->transactionId;
                        // Comentario personalizado para el log
                        $comentario = 'Aprobada por Sagicor ';
                        $t_value = '';
                        // Obtenemos la transaccion

                        $TransaccionProducto = new TransaccionProducto($transaccion_id);

                        $respuesta = $TransaccionProducto->setAprobada($transaccion_id, $tipo_genera, $estado, $comentario, $t_value, $IdTrans);

                        //PROCESADO CORRECTAMENTE
                        $response["success"] = true;
                        $response["Message"] = "Transacción realizada correctamente";
                    } else {
                        $response["success"] = false;
                        $response["Message"] = "Transaction failed";
                    }
                } else {
                    $response["success"] = false;
                    $response["Message"] = "Transaction failed";
                }
            } else {
                $response["success"] = false;
                $response["Message"] = "Error en  la transacción realizada";
            }
        } else {
            $response["success"] = false;
            $response["Message"] = "Error de session";
        }

        return json_decode(json_encode($response));
    }

    /**
     * Elimina una tarjeta de crédito asociada a un usuario.
     *
     * @param Usuario $Usuario Objeto del usuario propietario de la tarjeta.
     * @param integer $id      ID de la tarjeta a eliminar.
     *
     * @return object Respuesta en formato JSON con el resultado de la operación.
     */
    public function deleteCard(Usuario $Usuario, $id)
    {
        $UsuarioTarjetacredito = new UsuarioTarjetacredito($id);

        $UsuarioTarjetacredito->setEstado('I');

        $UsuarioTarjetacreditoMySqlDAO = new UsuarioTarjetacreditoMySqlDAO();
        $UsuarioTarjetacreditoMySqlDAO->update($UsuarioTarjetacredito);
        $UsuarioTarjetacreditoMySqlDAO->getTransaction()->commit();
        $data = array();
        $data["success"] = true;
        $data["Message"] = "Tarjeta Eliminada";

        return json_decode(json_encode($data));
    }

    /**
     * Verifica una tarjeta de crédito.
     *
     * @param Usuario $Usuario Objeto del usuario propietario de la tarjeta.
     * @param integer $id      ID de la tarjeta a verificar.
     * @param float   $valor   Monto de la transacción para la verificación.
     *
     * @return object Respuesta en formato JSON con el resultado de la operación.
     */
    public function VerificarTarjeta(Usuario $Usuario, $id, $valor)
    {
        $valor = floatval($valor);

        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $UsuarioTarjetacredito = new UsuarioTarjetacredito($id);

        if ($UsuarioTarjetacredito->getEstado() == "P" && $UsuarioTarjetacredito->getUsumodifId() < 2) {
            $TransaccionProducto = new TransaccionProducto('', '', '', $UsuarioTarjetacredito->usutarjetacreditoId);
            $UsumodifId = $UsuarioTarjetacredito->getUsumodifId();

            $UsumodifId = $UsumodifId + 1;
            $UsuarioTarjetacredito->setUsumodifId(intval($UsumodifId));

            $UsuarioTarjetacreditoMySqlDAO = new UsuarioTarjetacreditoMySqlDAO();
            $UsuarioTarjetacreditoMySqlDAO->update($UsuarioTarjetacredito);
            $UsuarioTarjetacreditoMySqlDAO->getTransaction()->commit();

            if (floatval($TransaccionProducto->getValor()) === floatval($valor)) {
                $UsuarioTarjetacredito->setEstado('A');
                $UsuarioTarjetacredito->setUsumodifId(intval($UsumodifId));

                $UsuarioTarjetacreditoMySqlDAO = new UsuarioTarjetacreditoMySqlDAO();
                $UsuarioTarjetacreditoMySqlDAO->update($UsuarioTarjetacredito);
                $UsuarioTarjetacreditoMySqlDAO->getTransaction()->commit();
                $data = array();
                $data["success"] = true;
                $data["Message"] = "Tarjeta Activada";
            } else {
                $data = array();
                $data["success"] = false;
                $data["Message"] = "Error en Activación de Tarjeta";
                $data["code"] = 1;
            }
        } else {
            $UsuarioTarjetacredito->setEstado('I');
            $UsuarioTarjetacreditoMySqlDAO = new UsuarioTarjetacreditoMySqlDAO();
            $UsuarioTarjetacreditoMySqlDAO->update($UsuarioTarjetacredito);
            $UsuarioTarjetacreditoMySqlDAO->getTransaction()->commit();
            $data = array();
            $data["success"] = false;
            $data["Message"] = "Intentos de validación completados";
            $data["code"] = 2;
        }
        return json_decode(json_encode($data));
    }

    /**
     * Realiza una solicitud HTTP GET a un servicio externo.
     *
     * @param string $text Parámetros de la solicitud.
     *
     * @return string Respuesta del servicio externo.
     */
    public function request($text)
    {
        $ch = curl_init($this->URL2 . $this->productname . "/Main.ashx" . $text);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);

        $result = (curl_exec($ch));

        return ($result);
    }
}
