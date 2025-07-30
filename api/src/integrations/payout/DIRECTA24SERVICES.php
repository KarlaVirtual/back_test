<?php

/**
 * Esta clase incluye métodos para realizar operaciones de cashout.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-19
 */

namespace Backend\integrations\payout;

use Backend\dto\SubproveedorMandantePais;
use \CurlWrapper;
use Exception;
use Backend\dto\Pais;
use Backend\dto\Banco;
use Backend\dto\Usuario;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\Registro;
use Backend\dto\CuentaCobro;
use Backend\dto\Subproveedor;
use Backend\dto\TransprodLog;
use Backend\dto\UsuarioBanco;
use Backend\dto\ProductoMandante;
use Backend\dto\ProveedorMandante;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransproductoDetalle;
use Backend\dto\SubproveedorMandante;
use Backend\mysql\TransprodLogMySqlDAO;


use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransproductoDetalleMySqlDAO;


/**
 * Clase que proporciona servicios de integración con Directa24.
 *
 * Esta clase incluye métodos para realizar operaciones de cashout,
 * generar firmas de payload, convertir datos a UTF-8, y más.
 * También maneja configuraciones específicas para entornos de desarrollo y producción.
 */
class DIRECTA24SERVICES
{

    /**
     * URL de la API de Directa24.
     *
     * @var string
     */
    private $URLPAYOUT = "";

    /**
     * URL de la API de Directa24 en el entorno de desarrollo.
     *
     * @var string
     */
    private $URLPAYOUTDEV = 'https://api-stg.directa24.com';

    /**
     * URL de la API de Directa24 en el entorno de producción.
     *
     * @var string
     */
    private $URLPAYOUTPROD = 'https://api.directa24.com';

    /**
     * URL de la API de TuPay.
     *
     * @var string
     */
    private $URL_API_TUPAY = '';

    /**
     * URL de la API de TuPay en el entorno de desarrollo.
     *
     * @var string
     */
    private $URL_API_TUPAYDEV = 'https://api-stg.tupayonline.com';

    /**
     * URL de la API de TuPay en el entorno de producción.
     *
     * @var string
     */
    private $URL_API_TUPAYPROD = 'https://api.tupayonline.com';

    /**
     * URL de callback para notificaciones.
     *
     * @var string
     */
    private $callback_url = "";

    /**
     * URL de callback en el entorno de desarrollo.
     *
     * @var string
     */
    private $callback_urlDEV = "https://apidev.virtualsoft.tech/integrations/payout/directa24/confirm/";

    /**
     * URL de callback en el entorno de producción.
     *
     * @var string
     */
    private $callback_urlPROD = "https://integrations.virtualsoft.tech/payout/directa24/confirm/";

    /**
     * Tipo de operación para la API.
     *
     * @var string
     */
    private $tipo = "";

    /**
     * Clave API para operaciones de cashout.
     *
     * @var string
     */
    private $cashout_API_Key = "";

    /**
     * Clave API para operaciones de cashout en el entorno de desarrollo.
     *
     * @var string
     */
    private $cashout_API_Key_DEV = "vIKGRGZipP";

    /**
     * Clave API para operaciones de cashout en el entorno de producción.
     *
     * @var string
     */
    private $cashout_API_Key_PROD = "";

    /**
     * Frase de contraseña para operaciones de cashout.
     *
     * @var string
     */
    private $cashout_API_Passphrase = "";

    /**
     * Frase de contraseña para operaciones de cashout en el entorno de desarrollo.
     *
     * @var string
     */
    private $cashout_API_Passphrase_DEV = "eaBGxPJqkTEYmIZepvVHxxdfEgjyLLyVgBkKKUox";

    /**
     * Frase de contraseña para operaciones de cashout en el entorno de producción.
     *
     * @var string
     */
    private $cashout_API_Passphrase_PROD = "";

    /**
     * Clave secreta para operaciones de cashout.
     *
     * @var string
     */
    private $cashout_secret_key = "";

    /**
     * Clave secreta para operaciones de cashout en el entorno de desarrollo.
     *
     * @var string
     */
    private $cashout_secret_key_DEV = "dspVvmxwlZixiWgSIUewcUxUbVKyvViRv";

    /**
     * Clave secreta para operaciones de cashout en el entorno de producción.
     *
     * @var string
     */
    private $cashout_secret_key_PROD = "";

    /**
     * Constructor de la clase.
     *
     * Configura las URLs y claves según el entorno (desarrollo o producción).
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->URLPAYOUT = $this->URLPAYOUTDEV;
            $this->callback_url = $this->callback_urlDEV;
            $this->cashout_API_Key = $this->cashout_API_Key_DEV;
            $this->cashout_API_Passphrase = $this->cashout_API_Passphrase_DEV;
            $this->cashout_secret_key = $this->cashout_secret_key_DEV;
            $this->URL_API_TUPAY = $this->URL_API_TUPAYDEV;
        } else {
            $this->URLPAYOUT = $this->URLPAYOUTPROD;
            $this->callback_url = $this->callback_urlPROD;
            $this->cashout_API_Key = $this->cashout_API_Key_PROD;
            $this->cashout_API_Passphrase = $this->cashout_API_Passphrase_PROD;
            $this->cashout_secret_key = $this->cashout_secret_key_PROD;
            $this->URL_API_TUPAY = $this->URL_API_TUPAYPROD;
        }
    }

    /**
     * Obtiene la dirección IP del cliente.
     *
     * @return string Dirección IP del cliente o 'UNKNOWN' si no se puede determinar.
     */
    function get_client_ip()
    {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP')) {
            $ipaddress = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_X_FORWARDED')) {
            $ipaddress = getenv('HTTP_X_FORWARDED');
        } elseif (getenv('HTTP_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        } elseif (getenv('HTTP_FORWARDED')) {
            $ipaddress = getenv('HTTP_FORWARDED');
        } elseif (getenv('REMOTE_ADDR')) {
            $ipaddress = getenv('REMOTE_ADDR');
        } else {
            $ipaddress = 'UNKNOWN';
        }
        return $ipaddress;
    }

    /**
     * Genera la firma del payload para Directa24.
     *
     * @param string $json_payload El payload en formato JSON.
     *
     * @return string Firma generada en formato HMAC-SHA256.
     */
    function PayloadSignature($json_payload)
    {
        $secretKey = $this->cashout_secret_key;
        $payload_signature = strtolower(hash_hmac('sha256', pack('A*', $json_payload), pack('A*', $secretKey)));

        return $payload_signature;
    }

    /**
     * Genera la firma del payload para TuPay.
     *
     * @param string $json_payload El payload en formato JSON.
     * @param string $secret_key   La clave secreta para la firma.
     *
     * @return string Firma generada en formato HMAC-SHA256.
     */
    function PayloadSignatureTUPAY($json_payload, $secret_key)
    {
        $secretKey = $secret_key;
        $payload_signature = strtolower(hash_hmac('sha256', pack('A*', $json_payload), pack('A*', $secretKey)));

        return $payload_signature;
    }

    /**
     * Convierte todos los valores de un array a codificación UTF-8.
     *
     * @param array $array El array a convertir.
     *
     * @return array El array con los valores convertidos a UTF-8.
     */
    function convertArrayToUtf8_Cashout($array)
    {
        array_walk_recursive($array, function (&$item, $key) {
            if (is_string($item)) {
                $item = mb_convert_encoding($item, 'UTF-8', 'auto');
            }
        });
        return $array;
    }

    /**
     * Realiza una operación de cashout.
     *
     * @param CuentaCobro $CuentaCobro Objeto que contiene la información de la cuenta de cobro.
     * @param Producto    $Producto    Objeto que contiene la información del producto.
     *
     * @return void
     * @throws Exception Si ocurre un error durante la operación de cashout.
     */
    public function cashOut(CuentaCobro $CuentaCobro, Producto $Producto)
    {
        $Usuario = new Usuario($CuentaCobro->usuarioId);
        $Pais = new Pais($Usuario->paisId);
        $Registro = new Registro("", $CuentaCobro->usuarioId);
        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $Usuario->mandante, $Usuario->paisId);
        $detalleSubProveedorMandantePais = json_decode($SubproveedorMandantePais->getDetalle());
        $UsuarioBanco = new UsuarioBanco($CuentaCobro->mediopagoId);

        //Method TuPay
        if ($Producto->externoId == "TUPAY_CASHOUT") {
            $ConfigurationEnvironment = new ConfigurationEnvironment();

            if ($ConfigurationEnvironment->isDevelopment()) {
                if ($Usuario->mandante == 0 && $Pais->iso == 'PE' || $Usuario->mandante == 18 && $Pais->iso == 'PE') {
                    $x_login = 'yqIyXmHlpT'; // API Key
                    $x_trans_key = 'ZloynNEtWFQvDanuxyGBGUsoHbRjwFGQvqwScFBJ'; //API Passphrase
                    $secret_key = 'xFrWNyAeryWSdvxFlQXsHmKQxEMnPCGeF'; // API Signature
                }
            } else {
                $x_login = 'IJDnAmkUOM'; // API Key
                $x_trans_key = 'YyBFpaIXZwCPMXuqfwJnosGvgcqFGTmUibxdVcUc'; //API Passphrase
                $secret_key = 'scHGfTRMUTaukKlrgGtNgcmBeQoajPvGv'; // API Signature
            }

            $tipoDoc = $this->tipoDoc($Registro, $Pais);

            $response = $this->Procesar($CuentaCobro, $Producto, $Usuario);

            $valorFinal = $response['valorFinal'];
            $transproductoId = $response['transproductoId'];
            $Transaction = $response['Transaction'];

            $order_id = $transproductoId;
            $bank_account = $UsuarioBanco->cuenta;
            $account_type = $UsuarioBanco->getTipoCuenta();
            $name = $Usuario->nombre;
            $beneficiary_lastname = $Registro->apellido1;
            $document_id = $Registro->cedula;

            switch ($account_type) {
                case "0":
                    $account_type = "S";
                    break;
                case "1":
                    $account_type = "C";
                    break;
                case "Ahorros":
                    $account_type = "S";
                    break;
                case "Corriente":
                    $account_type = "C";
                    break;
                case "Vista":
                    $account_type = "V";
                    break;
                default:
                    throw new Exception("Tipo de cuenta bancaria no encontrada", "10000");
                    break;
            }

            if ($Usuario->mandante == 18 && $Usuario->paisId == 173) {
                if ($UsuarioBanco->getCodigo() != '') {
                    $bank_account = $UsuarioBanco->getCodigo();
                }
            }

            $params_array = [
                "login" => $x_login,
                "pass" => $x_trans_key,
                "account_type" => $account_type,
                "amount" => $valorFinal,
                "bank_account" => $bank_account,
                "beneficiary_lastname" => $beneficiary_lastname,
                "beneficiary_name" => $name,
                "country" => $Pais->iso,
                "currency" => $Usuario->moneda,
                "document_type" => $tipoDoc,
                "document_id" => $document_id,
                "external_id" => $order_id,
                "notification_url" => $this->callback_url,
            ];

            $json_payload = $this->convertArrayToUtf8_Cashout($params_array);

            try {
                syslog(10, 'TUPAYDIRECTA24SERVICEDATA ' . (json_encode($json_payload)));
            } catch (Exception $e) {
            }

            $this->tipo = "/v3/cashout";
            $result = $this->createTransactionPOST_TUPAY($json_payload, $secret_key);

            try {
                syslog(10, 'TUPAYDIRECTA24SERVICERESPONSE ' . (json_encode($result)));
            } catch (Exception $e) {
            }
        } else {
            if ($Usuario->paisId == 66 && $Usuario->mandante == 0) {
                $this->cashout_API_Key = $detalleSubProveedorMandantePais->API_KeyDE;
                $this->cashout_API_Passphrase = $detalleSubProveedorMandantePais->API_PassphraseDE;
                $this->cashout_secret_key = $detalleSubProveedorMandantePais->API_SignatureDE;
            } else {
                $this->cashout_API_Key = $detalleSubProveedorMandantePais->API_Key;
                $this->cashout_API_Passphrase = $detalleSubProveedorMandantePais->API_Passphrase;
                $this->cashout_secret_key = $detalleSubProveedorMandantePais->API_Signature;
            }

            if ($Usuario->mandante == '18') {
                switch ($Usuario->paisId) {
                    case 33:
                        $this->cashout_API_Key = 'dQgOYbGnSv';
                        $this->cashout_secret_key = 'nbiAvahrqtHVKiWBcrnvXflWRQqNdTlve';
                        break;
                    case 173:
                        $this->cashout_API_Key = 'prpJkSLgto';
                        $this->cashout_secret_key = 'NoRgNaUgVgFKGzffNtcIlibatGyPqnNkc';
                        break;
                    case 46:
                        $this->cashout_API_Key = 'uHeZilBBbg';
                        $this->cashout_secret_key = 'lhXsnfvLoqcUHrgnRvkhlobmSBrjRZzOx';
                        break;
                    case 146:
                        $this->cashout_API_Key = 'lCdNoybZml';
                        $this->cashout_secret_key = 'znVOaiKQmvJpyyfsmkMsNFSgJCKPRPoaS';
                        break;
                }
            }

            $response = $this->Procesar($CuentaCobro, $Producto, $Usuario);

            $valorFinal = $response['valorFinal'];
            $transproductoId = $response['transproductoId'];
            $Transaction = $response['Transaction'];

            $order_id = $transproductoId;
            $account_id = $UsuarioBanco->cuenta;
            $typeAccount = $UsuarioBanco->getTipoCuenta();
            $vat_id = $Registro->cedula;
            $name = $Usuario->nombre;
            $LastName = $Registro->apellido1;
            $user_email = $Usuario->login;
            $phone_number = $Registro->celular;

            switch ($typeAccount) {
                case "0":
                    $typeAccount = "S";
                    break;
                case "1":
                    $typeAccount = "C";
                    break;
                case "Ahorros":
                    $typeAccount = "S";
                    break;
                case "Corriente":
                    $typeAccount = "C";
                    break;
                case "Vista":
                    $typeAccount = "V";
                    break;
                default:
                    throw new Exception("Tipo de cuenta bancaria no encontrada", "10000");
                    break;
            }

            if ($Usuario->mandante == 18 && $Usuario->paisId == 173) {
                if ($UsuarioBanco->getCodigo() != '') {
                    $account_id = $UsuarioBanco->getCodigo();
                }
            }

            $tipoDoc = $this->tipoDoc($Registro, $Pais);

            $json_payload = array(

                "login" => $this->cashout_API_Key,
                "pass" => $this->cashout_API_Passphrase,
                "external_id" => $order_id,
                "country" => $Pais->iso,
                "amount" => $valorFinal,
                "currency" => $Usuario->moneda,
                "document_id" => $vat_id,
                "document_type" => $tipoDoc,
                "beneficiary_name" => $name,
                "beneficiary_lastname" => $LastName,
                "email" => $user_email,
                "phone" => $phone_number,
                "bank_code" => $Producto->externoId,
                "bank_account" => $account_id,
                "bank_branch" => "",
                "account_type" => $typeAccount,
                "adress" => "",
                "city" => "",
                "postal_code" => "",
                "beneficiary_birthdate" => "",
                "notification_url" => $this->callback_url,
                "comments" => "",
                "on_hold" => false
            );


            try {
                syslog(10, 'DIRECTA24SERVICEDATA ' . (json_encode($json_payload)));
            } catch (Exception $e) {
            }

            $this->tipo = "/v3/cashout";
            $result = $this->createTransactionPOST($json_payload, $Usuario);

            try {
                syslog(10, 'DIRECTA24SERVICERESPONSE ' . (json_encode($result)));
            } catch (Exception $e) {
            }
        }

        $result = (json_decode($result));

        if ($result != "" && $result != null && $result->cashout_id != null && $result->cashout_id != "") {
            $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
            $TransaccionProducto = new TransaccionProducto();

            $TransprodLog = new TransprodLog();
            $TransprodLog->setTransproductoId($transproductoId);
            $TransprodLog->setEstado('E');
            $TransprodLog->setTipoGenera('M');
            $TransprodLog->setComentario('Envio Solicitud de pago');
            $TransprodLog->setTValue(json_encode(($json_payload)));
            $TransprodLog->setUsucreaId(0);
            $TransprodLog->setUsumodifId(0);

            $TransaccionProducto->setExternoId($result->cashout_id);
            $TransaccionProductoMySqlDAO->update($TransaccionProducto);

            $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
            $TransprodLogMySqlDAO->insert($TransprodLog);

            $Transaction->commit();
        } else {
            throw new Exception("No se pudo realizar la transaccion", "100000");
        }
    }

    /**
     * Procesa una transacción de cashout.
     *
     * @param CuentaCobro $CuentaCobro Objeto que contiene la información de la cuenta de cobro.
     * @param Producto    $Producto    Objeto que contiene la información del producto.
     * @param Usuario     $Usuario     Objeto que contiene la información del usuario.
     *
     * @return array Información de la transacción procesada.
     */
    public function Procesar($CuentaCobro, $Producto, $Usuario)
    {
        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
        $Transaction = $TransaccionProductoMySqlDAO->getTransaction();
        $valorFinal = $CuentaCobro->getValorAPagar();
        $valorFinal = str_replace(',', '', $valorFinal);
        $TransaccionProducto = new TransaccionProducto();
        $TransaccionProducto->setProductoId($Producto->productoId);
        $TransaccionProducto->setUsuarioId($Usuario->usuarioId);
        $TransaccionProducto->setValor($valorFinal);
        $TransaccionProducto->setEstado('A');
        $TransaccionProducto->setTipo('T');
        $TransaccionProducto->setExternoId(0);
        $TransaccionProducto->setEstadoProducto('E');
        $TransaccionProducto->setMandante($Usuario->mandante);
        $TransaccionProducto->setFinalId($CuentaCobro->getCuentaId());
        $TransaccionProducto->setFinalId(0);

        $transproductoId = $TransaccionProductoMySqlDAO->insert($TransaccionProducto);

        $CuentaCobro->setTransproductoId($transproductoId);

        return [
            'valorFinal' => $valorFinal,
            'transproductoId' => $transproductoId,
            'Transaction' => $Transaction
        ];
    }

    /**
     * Determina el tipo de documento según el país y el registro.
     *
     * @param Registro $Registro Objeto que contiene la información del registro.
     * @param Pais     $Pais     Objeto que contiene la información del país.
     *
     * @return string Tipo de documento determinado.
     */
    public function tipoDoc($Registro, $Pais)
    {
        $tipoDoc = "";

        switch ($Registro->getTipoDoc()) {
            case "E":
                if ($Pais->iso == "PE") {
                    $tipoDoc = "CE";
                }
                break;
            case "P":
                if ($Pais->iso == "EC") {
                    $tipoDoc = "PASS";
                } elseif ($Pais->iso == "PE") {
                    $tipoDoc = "PASS";
                } elseif ($Pais->iso == "MX") {
                    $tipoDoc = "PASS";
                }
                break;
            case "C":
                if ($Pais->iso == "CL") {
                    $tipoDoc = "ID";
                } elseif ($Pais->iso == "EC") {
                    $tipoDoc = "CC";
                } elseif ($Pais->iso == "PE") {
                    $tipoDoc = "DNI";
                } elseif ($Pais->iso == "NI") {
                    $tipoDoc = "CI";
                } elseif ($Pais->iso == "MX") {
                    $tipoDoc = "IFE";
                } elseif ($Pais->iso == "BR") {
                    $tipoDoc = "CPF";
                }
                break;
            default:
                $tipoDoc = "";
                break;
        }
        return $tipoDoc;
    }

    /**
     * Crea una transacción POST para Directa24.
     *
     * @param array   $json_payload El payload en formato JSON.
     * @param Usuario $Usuario      Objeto que contiene la información del usuario.
     *
     * @return string Respuesta de la API en formato JSON.
     */
    function createTransactionPOST($json_payload, $Usuario)
    {
        $curl = curl_init();

        $url = $this->URLPAYOUT . $this->tipo;

        $Payload_Signature = $this->PayloadSignature(json_encode($json_payload));

        $header = array(
            'Payload-Signature: ' . $Payload_Signature,
            'Content-Type: application/json',
            'Cookie: GCLB=CJzZoYqxgd2eYw'
        );

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($json_payload),
            CURLOPT_HTTPHEADER => $header,
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    /**
     * Crea una transacción POST para TuPay.
     *
     * @param array  $json_payload El payload en formato JSON.
     * @param string $secret_key   La clave secreta para la firma.
     *
     * @return string Respuesta de la API en formato JSON.
     */
    function createTransactionPOST_TUPAY($json_payload, $secret_key)
    {
        $url = $this->URL_API_TUPAY . $this->tipo;

        $Payload_Signature = $this->PayloadSignatureTUPAY(json_encode($json_payload), $secret_key);

        $header = array(
            'Payload-Signature: ' . $Payload_Signature,
            'Content-Type: application/json',
            'Cookie: GCLB=CLODnJ__1rqLhQE'
        );

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($json_payload),
            CURLOPT_HTTPHEADER => $header,
        ]);

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    /**
     * Solicita el estado de un cashout.
     *
     * @param string $cashout_id  ID del cashout.
     * @param string $external_id ID externo de la transacción.
     *
     * @return string Respuesta de la API en formato JSON.
     */
    function CashoutStatusRequest($cashout_id, $external_id)
    {
        $TransaccionProducto = new TransaccionProducto($external_id);
        $Usuario = new Usuario($TransaccionProducto->usuarioId);
        $Producto = new Producto($TransaccionProducto->productoId);
        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $Usuario->mandante, $Usuario->paisId);
        $detalleSubProveedorMandantePais = json_decode($SubproveedorMandantePais->getDetalle());
        $Pais = new Pais($Usuario->paisId);

        if ($Producto->externoId == "TUPAY_CASHOUT") {
            $ConfigurationEnvironment = new ConfigurationEnvironment();

            if ($ConfigurationEnvironment->isDevelopment()) {
                if (($Usuario->mandante == 0 && $Pais->iso == 'PE') || ($Usuario->mandante == 18 && $Pais->iso == 'PE')) {
                    $this->cashout_API_Key = 'yqIyXmHlpT'; // API Key
                    $this->cashout_API_Passphrase = 'ZloynNEtWFQvDanuxyGBGUsoHbRjwFGQvqwScFBJ'; //API Passphrase
                    $secret_key = 'xFrWNyAeryWSdvxFlQXsHmKQxEMnPCGeF'; // API Signature
                }
            } else {
                $this->cashout_API_Key = 'IJDnAmkUOM'; // API Key
                $this->cashout_API_Passphrase = 'YyBFpaIXZwCPMXuqfwJnosGvgcqFGTmUibxdVcUc'; //API Passphrase
                $secret_key = 'scHGfTRMUTaukKlrgGtNgcmBeQoajPvGv'; // API Signature
            }

            $this->tipo = "/v3/cashout/status";
            $url = $this->URL_API_TUPAY . $this->tipo;

            $json_payload = array(
                "login" => $this->cashout_API_Key,
                "external_id" => $external_id,
                "cashout_id" => $cashout_id,
                "pass" => $this->cashout_API_Passphrase,
            );
            $Payload_Signature = $this->PayloadSignatureTUPAY(json_encode($json_payload), $secret_key);
        } else {
            if ($Usuario->paisId == 66 && $Usuario->mandante == 0) {
                $this->cashout_API_Key = $detalleSubProveedorMandantePais->API_KeyDE;
                $this->cashout_API_Passphrase = $detalleSubProveedorMandantePais->API_PassphraseDE;
                $this->cashout_secret_key = $detalleSubProveedorMandantePais->API_SignatureDE;
            } else {
                $this->cashout_API_Key = $detalleSubProveedorMandantePais->API_Key;
                $this->cashout_API_Passphrase = $detalleSubProveedorMandantePais->API_Passphrase;
                $this->cashout_secret_key = $detalleSubProveedorMandantePais->API_Signature;
            }
            $this->tipo = '/v3/cashout/status';
            $url = $this->URLPAYOUT . $this->tipo;

            $json_payload = array(
                "login" => $this->cashout_API_Key,
                "pass" => $this->cashout_API_Passphrase,
                "cashout_id" => $cashout_id,
                "external_id" => $external_id,
            );
            $Payload_Signature = $this->PayloadSignature(json_encode($json_payload));
        }

        $curl = new CurlWrapper($url);

        $header = array(
            'Payload-Signature: ' . $Payload_Signature,
            'Content-Type: application/json'
        );

        $curl->setOptionsArray(array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($json_payload),
            CURLOPT_HTTPHEADER => $header,
        ));

        $response = $curl->execute();

        return $response;
    }


}
