<?php

/**
 * Clase SAFETYSERVICES
 *
 * Esta clase proporciona servicios de integración con SafetyPay para la creación de solicitudes de pago.
 * Incluye métodos para configurar el entorno, manejar credenciales y realizar transacciones.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-25
 */

namespace Backend\Integrations\payment;

use Exception;
use Backend\dto\Pais;
use Backend\dto\Usuario;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\Clasificador;
use Backend\dto\TransprodLog;
use Backend\dto\SubproveedorMandantePais;
use Backend\dto\MandanteDetalle;
use Backend\dto\ProductoDetalle;
use Backend\dto\UsuarioMandante;
use Backend\dto\ProveedorMandante;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransproductoDetalle;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransproductoDetalleMySqlDAO;
use Backend\Integrations\payment\safetypay\SafetyPayProxy;

/**
 * Clase SAFETYSERVICES
 *
 * Proporciona métodos para la integración con SafetyPay, incluyendo la creación de solicitudes
 * de pago y la configuración del entorno de ejecución.
 */
class SAFETYSERVICES
{
    /**
     * Constructor de la clase SAFETYSERVICES.
     *
     * Inicializa el entorno de configuración para determinar si se está en un entorno de desarrollo o producción.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
        } else {
        }
    }

    /**
     * Crea una solicitud de pago a través de SafetyPay.
     *
     * Este método configura las credenciales, prepara los datos de la transacción y realiza la solicitud
     * a la API de SafetyPay para generar un token de pago.
     *
     * @param Usuario  $Usuario  Objeto que representa al usuario que realiza la transacción.
     * @param Producto $Producto Objeto que representa el producto asociado a la transacción.
     * @param float    $valor    Monto de la transacción.
     * @param string   $urlOK    URL de redirección en caso de éxito.
     * @param string   $urlERROR URL de redirección en caso de error.
     *
     * @return object Objeto JSON con los resultados de la solicitud, incluyendo el estado y la URL del token.
     */
    public function createRequestPayment(Usuario $Usuario, Producto $Producto, $valor, $urlOK, $urlERROR)
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            require_once 'safetypaydev/class/SafetyPayProxy.php';
            $proxy = new \Backend\Integrations\payment\safetypaydev\SafetyPayProxy();
        } else {
            require_once 'safetypay/class/SafetyPayProxy.php';
            $proxy = new SafetyPayProxy();
        }

        // Configuración inicial de credenciales y entorno
        $ApiKey = $proxy->conf['ApiKey'];
        $SignatureKey = $proxy->conf['SignatureKey'];
        $Environment = $proxy->conf['Environment'];
        $Protocol = $proxy->conf['Protocol'];

        // Creación de objetos relacionados con el usuario y el producto
        $Registro = new Registro("", $Usuario->usuarioId);

        $CustomMerchantName = '';
        $ProductID = '';
        $ChannelID = 0;

        $SalesCurrencyCode = "";

        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);

        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
        $credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $CustomMerchantName = $credentials->CUSTOMER_MERCHANT_NAME;
        $ProductID = $credentials->PRODUCT_ID;
        $ChannelID = $credentials->CHANNEL_ID;
        $ApiKey = $credentials->API_KEY;
        $SignatureKey = $credentials->SIGNATURE_KEY;

        // Preparación de datos de la transacción
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

        // Configuración de país y moneda según el ID del país
        switch ($pais) {
            case "173":
                $CountryCode = "PER";
                break;
            case "60":
                $CountryCode = "CRI";
                $SalesCurrencyCode = 'USD';
                break;
            case "66":
                $CountryCode = "ECU";
                $CountryCode = "593";
                if ($Usuario->mandante == 8) {
                    $SalesCurrencyCode = 'EUR';
                }
                break;
            case "2":
                $CountryCode = "NIC";
                $ChannelID = $jsonProveedor->CHANNELID;
                $SalesCurrencyCode = 'EUR';
                break;

            case "94":
                $CountryCode = "GTM";
                break;
        }

        $Environment = $proxy->conf['Environment'];
        $Protocol = $proxy->conf['Protocol'];

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

        $RequestDateTime = $proxy->conf['RequestDateTime'];

        $CurrencyCode = $proxy->conf['CurrencyCode'];
        $CurrencyCode = $Usuario->moneda;

        $Amount = round((float)($valorTax), 2);

        $MerchantSalesID = $transproductoId;
        $Language = $proxy->conf['Language'];

        $TrackingCode = $proxy->conf['TrackingCode'];
        $ExpirationTime = $proxy->conf['ExpirationTime'];

        $FilterBy = $proxy->conf['FilterBy'];

        $TransactionExpirationTime = $proxy->conf['TransactionExpirationTime'];
        $ShopperEmail = $proxy->conf['ShopperEmail'];
        $ShopperInformation_FirstName = $nombre;
        $ShopperInformation_LastName = "";
        $ShopperInformation_Email = $email;
        $ShopperInformation_CountryCode = $CountryCode;
        $ShopperInformation_Mobile = "";
        $ShopperInformation_NotifyExpiration = $proxy->conf['ShopperInformation_NotifyExpiration'];
        $ShopperInformation_RecoveryMessage = $proxy->conf['ShopperInformation_RecoveryMessage'];

        $proxy->conf['ApiKey'] = $ApiKey;
        $proxy->conf['SignatureKey'] = $SignatureKey;
        $proxy->conf['Environment'] = $Environment;
        $proxy->conf['Protocol'] = $Protocol;
        $proxy->conf['TransactionOkURL'] = $urlOK;
        $proxy->conf['TransactionErrorURL'] = $urlERROR;

        $proxy->conf['CurrencyCode'] = $CurrencyCode;
        $proxy->conf['Amount'] = $Amount;
        $proxy->conf['MerchantSalesID'] = $MerchantSalesID;
        $proxy->conf['Language'] = $Language;
        $proxy->conf['ExpirationTime'] = $ExpirationTime;
        $proxy->conf['ProductID'] = $ProductID;
        $proxy->conf['TransactionExpirationTime'] = $TransactionExpirationTime;
        $proxy->conf['CustomMerchantName'] = $CustomMerchantName;
        $proxy->conf['ShopperEmail'] = $ShopperEmail;
        $proxy->conf['ShopperInformation']['first_name'] = $ShopperInformation_FirstName;
        $proxy->conf['ShopperInformation']['last_name'] = $ShopperInformation_LastName;
        $proxy->conf['ShopperInformation']['email'] = $ShopperInformation_Email;
        $proxy->conf['ShopperInformation']['country_code'] = $ShopperInformation_CountryCode;
        $proxy->conf['ShopperInformation']['phone'] = $ShopperInformation_Mobile;
        $proxy->conf['ShopperInformation']['notify_expiration'] = $ShopperInformation_NotifyExpiration;
        $proxy->conf['ShopperInformation']['recovery_message'] = $ShopperInformation_RecoveryMessage;

        $apierror = false;

        if ($SalesCurrencyCode != "" && $SalesCurrencyCode != null) {
            if ($_ENV['debug']) {
                print_r('testtesttest');
            }
            $proxy->conf['SalesCurrencyCode'] = $SalesCurrencyCode;
            $Result = $proxy->CreateExpressTokenReverse();
        } else {
            $Result = $proxy->CreateExpressToken();
        }

        if ($_ENV['debug']) {
            print_r($proxy->conf);
            print_r($_SERVER);
            print_r($Result);
        }

        if ($Result['ErrorManager']['ErrorNumber']['@content'] == '0') {
            $tokenUrl = $Result['ShopperRedirectURL'];
        }
        if ($Result['ErrorManager']['ErrorNumber']['@content'] == '0') {
            $errorNo = '<span style="color:black;">'
                . current(@@$Result['ErrorManager']['ErrorNumber']) . ', '
                . current(@@$Result['ErrorManager']['Description']) . '</span>';
        } else {
            $apierror = true;
            if (is_array($Result['ErrorManager']['ErrorNumber'])) {
                $errorNo = '<span style="color:red;">'
                    . current(@@$Result['ErrorManager']['ErrorNumber']) . ', '
                    . current(@@$Result['ErrorManager']['Description']) . '</span>';
            } else {
                $errorNo = '<span style="color:red;">'
                    . @@$Result['ErrorManager']['ErrorNumber'] . ', '
                    . @@$Result['ErrorManager']['Description'] . '</span>';
            }
        }


        $signature = $proxy->conf['Signature'];

        if ( ! $apierror) {
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

            $data = array();
            $data["success"] = true;
            $data["url"] = $tokenUrl . "&CHANNELID=" . $ChannelID;
            $data["target"] = '_self';
        }

        return json_decode(json_encode($data));
    }

}
