<?php

/**
 * Clase para manejar la integración con el servicio de PayForFun.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-19
 */

namespace Backend\integrations\payout;

use Backend\dto\Mandante;
use Backend\dto\SubproveedorMandantePais;
use \CurlWrapper;
use Exception;
use Backend\dto\Banco;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\CuentaCobro;
use Backend\dto\Pais;
use Backend\dto\Producto;
use Backend\dto\ProductoMandante;
use Backend\dto\Registro;
use Backend\dto\UsuarioOtrainfo;
use Backend\dto\Subproveedor;
use Backend\dto\SubproveedorMandante;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransprodLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioBanco;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransprodLogMySqlDAO;

/**
 * Clase para manejar la integración con el servicio de PayForFun.
 */
class PAYFORFUNSERVICES
{
    /**
     * URL de callback para el servicio de PayForFun.
     *
     * @var string
     */
    private $callback_url = "";

    /**
     * URL de callback para el servicio de PayForFun en modo desarrollo.
     *
     * @var string
     */
    private $callback_urlDEV = "https://apidev.virtualsoft.tech/integrations/payout/payforfun/confirm/";

    /**
     * URL de callback para el servicio de PayForFun en modo producción.
     *
     * @var string
     */
    private $callback_urlPROD = "https://integrations.virtualsoft.tech/payout/payforfun/confirm/";

    /**
     * URL de depósito para el servicio de PayForFun.
     *
     * @var string
     */
    private $URLDEPOSIT = '';

    /**
     * URL de depósito para el servicio de PayForFun en modo desarrollo.
     *
     * @var string
     */
    private $URLDEPOSITDEV = "https://devfrontend.virtualsoft.tech/lotosports/gestion/cuenta_cobro";

    /**
     * URL de depósito para el servicio de PayForFun en modo producción.
     *
     * @var string
     */
    private $URLDEPOSITPROD = "https://lotosports.bet/gestion/cuenta_cobro";

    /**
     * Constructor de la clase PAYFORFUNSERVICES.
     *
     * Inicializa las propiedades de la clase dependiendo del entorno de ejecución
     * (desarrollo o producción). Configura las URLs, credenciales y claves necesarias
     * para la integración con el servicio de PayForFun.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->callback_url = $this->callback_urlDEV;
            $this->URLDEPOSIT = $this->URLDEPOSITDEV;
        } else {
            $this->callback_url = $this->callback_urlPROD;
            $this->URLDEPOSIT = $this->URLDEPOSITPROD;
        }
    }

    /**
     * Metodo para procesar el retiro de dinero a través del servicio de PayForFun.
     *
     * @param CuentaCobro $CuentaCobro Objeto CuentaCobro que contiene la información del retiro.
     *
     * @return void
     * @throws Exception Si ocurre un error durante el proceso de retiro.
     */
    public function cashOut(CuentaCobro $CuentaCobro)
    {
        $Usuario = new Usuario($CuentaCobro->usuarioId);
        $Registro = new Registro("", $CuentaCobro->usuarioId);

        $mandante = $Usuario->mandante;
        $Mandante = new Mandante($Usuario->mandante);

        $Subproveedor = new Subproveedor('', 'P4FPAYOUT');
        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Subproveedor->subproveedorId, $mandante, $Usuario->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $URL = $Credentials->URL;
        $MERCHANT_ID_PAYOUT = $Credentials->MERCHANT_ID_PAYOUT;
        $MERCHANT_KEY_PAYOUT = $Credentials->MERCHANT_KEY_PAYOUT;
        $MERCHANT_SECRET_PAYOUT = $Credentials->MERCHANT_SECRET_PAYOUT;

        if ($mandante == 17) {
            $this->URLDEPOSIT = $Mandante->baseUrl . '/gestion/cuenta_cobro';
        }

        $UsuarioOtrainfo = new UsuarioOtrainfo($mandante);


        $UsuarioBanco = new UsuarioBanco($CuentaCobro->mediopagoId);
        $Banco = new Banco($UsuarioBanco->bancoId);

        $Producto = new Producto($Banco->productoPago);

        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
        $Transaction = $TransaccionProductoMySqlDAO->getTransaction();

        $TransaccionProducto = new TransaccionProducto();
        $TransaccionProducto->setProductoId($Producto->productoId);
        $TransaccionProducto->setUsuarioId($Usuario->usuarioId);
        $TransaccionProducto->setValor($CuentaCobro->getValor());
        $TransaccionProducto->setEstado('A');
        $TransaccionProducto->setTipo('T');
        $TransaccionProducto->setExternoId(0);
        $TransaccionProducto->setEstadoProducto('E');
        $TransaccionProducto->setMandante($Usuario->mandante);
        $TransaccionProducto->setFinalId($CuentaCobro->getCuentaId());
        $TransaccionProducto->setFinalId(0);

        $transproductoId = $TransaccionProductoMySqlDAO->insert($TransaccionProducto);

        $CuentaCobro->setTransproductoId($transproductoId);

        $order_id = $transproductoId;
        $usuario_id = $Usuario->usuarioId;
        $credit_note = $CuentaCobro->getCuentaId();
        $account_id = $UsuarioBanco->cuenta;
        $interbank = $UsuarioBanco->getCodigo();
        $bankId = $UsuarioBanco->getBancoId();
        $typeAccount = $UsuarioBanco->getTipoCuenta();
        $account_type = "Cuenta de Ahorros";
        $cedula = $Registro->cedula;
        $amount = $CuentaCobro->getValor();
        $name = $Usuario->nombre;
        $LastName = $Registro->apellido1;
        $subject = 'Transferencia Cuenta ' . $CuentaCobro->getCuentaId();
        $bank_detail = $Banco->descripcion;
        $channel = 1;
        $user_email = $Usuario->login;
        $phone_number = $Registro->celular;
        $bank = $Producto->getExternoId();
        $bank = $Banco->productoPago;
        $currency = $Usuario->moneda;
        $tipoDocumento = $Registro->tipoDoc;
        $TDocumento = $Registro->tipoDoc;
        $email = 'approved@gooutp4f.com';//$Registro->email;
        $naci = $UsuarioOtrainfo->fechaNacim;

        $Pais = new Pais($Usuario->paisId);
        //convertir el tipo de documento a los requeridos por el proveedor
        switch ($tipoDocumento) {
            case "E":
                if ($Pais->iso == "PE") {
                    $tipoDocumento = "CE";
                }
                break;
            case "P": //OK
                if ($Pais->iso == "EC") {
                    $tipoDocumento = "PAS";
                } elseif ($Pais->iso == "CL") {
                    $tipoDocumento = "PP";
                } elseif ($Pais->iso == "PE") {
                    $tipoDocumento = "PAS";
                }
                break;
            case "C": //OK
                if ($Pais->iso == "CL") {
                    $tipoDocumento = "RUT";
                } elseif ($Pais->iso == "PE") {
                    $tipoDocumento = "DNI";
                } elseif ($Pais->iso == "BR") {
                    $tipoDocumento = "CPF";
                }
                break;
            default:
                $tipoDocumento = "DNI";
                break;
        }

        $bankId = $Producto->getExternoId();

        switch ($UsuarioBanco->getTipoCuenta()) {
            case "0":
                $typeAccount = "CA";
                break;
            case "1":
                $typeAccount = "CC";
                break;
            case "Ahorros":
                $typeAccount = "CA";
                break;
            case "Corriente":
                $typeAccount = "CC";
                break;
            case "CPF":
                $typeAccount = "CPF";
                break;
            case "EMAIL":
                $typeAccount = "Email";
                break;
            default:
                throw new Exception("Tipo de cuenta bancaria no encontrada", "10000");
                break;
        }

        $valor = str_replace(',', '', number_format(round($amount, 2), 2, '.', null));
        $valor_ = $valor * 100;
        $hash = hash_hmac('sha256', $MERCHANT_ID_PAYOUT . $valor_ . $transproductoId . $email . $MERCHANT_SECRET_PAYOUT, $MERCHANT_KEY_PAYOUT);

        $data = array();
        $data['amount'] = $valor;//OK
        $data['merchantInvoiceId'] = $transproductoId;//OK
        $data['language'] = 'pt-BR';//$Pais->idioma;//OK
        $data['currency'] = $currency;//OK
        $data['confirmationUrl'] = $this->callback_url;//OK
        $data['merchantId'] = $MERCHANT_ID_PAYOUT;//OK
        $data['labelId'] = '1';//OK
        $data['hash'] = $hash;//OK
        $data['targetCustomerEmail'] = 'approved@gooutp4f.com';//$email;//OK
        $data['targetCustomerMainId'] = '56443853024';//$cedula;//OK
        $data['pixKeyType'] = $typeAccount;//OK
        $data['bankCode'] = $bankId;//OK
        $data['bankBranch'] = '';//OK
        $data['bankAccount'] = $account_id;//OK
        $data['bankAccountType'] = $account_type;//OK
        $path = "/1.0/goout/process/";

        $result = $this->request($data, $MERCHANT_ID_PAYOUT, $hash, $path, $URL);

        $result_ = json_encode($result);
        $result_ = json_decode($result_, true);
        $array_encode = json_encode(array_merge($data, $result_));


        if ($result != "" && $result != null && $result->code != 401 && $result->code != 400) {
            $TransprodLog = new TransprodLog();
            $TransprodLog->setTransproductoId($transproductoId);
            $TransprodLog->setEstado('E');
            $TransprodLog->setTipoGenera('M');
            $TransprodLog->setComentario('Envio Solicitud de pago');
            $TransprodLog->setTValue($array_encode);
            $TransprodLog->setUsucreaId(0);
            $TransprodLog->setUsumodifId(0);

            $TransaccionProducto->setExternoId($result->transactionId);
            $TransaccionProductoMySqlDAO->update($TransaccionProducto);


            $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
            $TransprodLogMySqlDAO->insert($TransprodLog);

            $Transaction->commit();
        } elseif ($result->code == 400 || $result->code == 401) {
            throw new Exception($result->message, "10000");
        } else {
            throw new Exception("La transferencia no fue procesada", "10000");
        }
    }

    /**
     * Realiza una solicitud al servicio de PayForFun.
     *
     * @param array  $data       Datos a enviar en la solicitud.
     * @param string $merchantID ID del comerciante.
     * @param string $hash       Hash de autenticación.
     * @param string $path       Ruta del endpoint.
     * @param string $URL        URL base del servicio.
     *
     * @return object Respuesta del servicio decodificada como objeto JSON.
     */
    public function request($data, $merchantID, $hash, $path, $URL)
    {
        $data = json_encode($data);

        $header = array(
            "Content-Type: application/json"
        );

        $url = $URL . $path;

        $curl = new CurlWrapper($url);

        $curl->setOptionsArray(array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => $header
        ));

        $response = $curl->execute();

        return json_decode($response);
    }
}
