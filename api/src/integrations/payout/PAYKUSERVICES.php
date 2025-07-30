<?php

/**
 * Esta clase proporciona métodos para interactuar con la API de PAYKU.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-19
 */

namespace Backend\integrations\payout;

use Backend\dto\Banco;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\CuentaCobro;
use Backend\dto\Pais;
use Backend\dto\Producto;
use Backend\dto\ProductoMandante;
use Backend\dto\Registro;
use Backend\dto\Subproveedor;
use Backend\dto\SubproveedorMandante;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransprodLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioBanco;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransprodLogMySqlDAO;
use Exception;

/**
 * Clase PAYKUSERVICES
 *
 * Esta clase proporciona métodos para interactuar con la API de PAYKU,
 * incluyendo funcionalidades como retiros de dinero y consultas de billetera.
 */
class PAYKUSERVICES
{

    /**
     * URL base utilizada para las solicitudes a la API.
     *
     * @var string
     */
    private $URL = "";

    /**
     * URL de desarrollo utilizada para las solicitudes a la API.
     *
     * @var string
     */
    private $URLDEV = 'https://des.payku.cl/api';

    /**
     * URL de producción utilizada para las solicitudes a la API.
     *
     * @var string
     */
    private $URLPROD = 'https://app.payku.cl/api';

    /**
     * URL de confirmación utilizada para las solicitudes a la API.
     *
     * @var string
     */
    private $callback_url = "";

    /**
     * URL de confirmación de desarrollo utilizada para las solicitudes a la API.
     *
     * @var string
     */
    private $callback_urlDEV = "https://apidev.virtualsoft.tech/integrations/payment/payku/confirm/";

    /**
     * URL de confirmación de producción utilizada para las solicitudes a la API.
     *
     * @var string
     */
    private $callback_urlPROD = "https://integrations.virtualsoft.tech/payment/payku/confirm/";

    /**
     * URL de depósito utilizada para las solicitudes a la API.
     *
     * @var string
     */
    private $URLDEPOSIT = '';

    /**
     * URL de depósito de desarrollo utilizada para las solicitudes a la API.
     *
     * @var string
     */
    private $URLDEPOSITDEV = "https://devfrontend.virtualsoft.tech/doradobetv4/gestion/deposito";

    /**
     * URL de depósito de producción utilizada para las solicitudes a la API.
     *
     * @var string
     */
    private $URLDEPOSITPROD = "https://doradobet.com/gestion/deposito";

    /**
     * URL de confirmación de depósito utilizada para las solicitudes a la API.
     *
     * @var string
     */
    private $username = " ";

    /**
     * URL de confirmación de depósito de desarrollo utilizada para las solicitudes a la API.
     *
     * @var string
     */
    private $tipo = "";

    /**
     * Token público utilizado para la autenticación en la API.
     *
     * @var string
     */
    private $tokenPUB = " ";

    /**
     * Token público de desarrollo utilizado para la autenticación en la API.
     *
     * @var string
     */
    private $tokenPUB_DEV = "tkpu9987f704786dfc8150e30fbbc941";

    /**
     * Token público de producción utilizado para la autenticación en la API.
     *
     * @var string
     */
    private $tokenPUB_PROD = "tkpu2946b40b3a0792326fd240e511a0";

    /**
     * Token privado utilizado para la autenticación en la API.
     *
     * @var string
     */
    private $tokenPRIV = " ";

    /**
     * Token privado utilizado para la autenticación en la API.
     *
     * @var string
     */
    private $tokenPRIV_DEV = "tkpi842d23f7538f13329c22afbf357c";

    /**
     * Token privado de producción utilizado para la autenticación en la API.
     *
     * @var string
     */
    private $tokenPRIV_PROD = "tkpib5154a95bcb4355df52203b7b814";

    /**
     * Constructor de la clase PAYKUSERVICES.
     * Inicializa las variables de configuración según el entorno (desarrollo o producción).
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->URL = $this->URLDEV;
            $this->callback_url = $this->callback_urlDEV;
            $this->tokenPUB = $this->tokenPUB_DEV;
            $this->tokenPRIV = $this->tokenPRIV_DEV;
            $this->URLDEPOSIT = $this->URLDEPOSITDEV;
        } else {
            $this->URL = $this->URLPROD;
            $this->callback_url = $this->callback_urlPROD;
            $this->tokenPUB = $this->tokenPUB_PROD;
            $this->tokenPRIV = $this->tokenPRIV_PROD;
            $this->URLDEPOSIT = $this->URLDEPOSITPROD;
        }
    }

    /**
     * Realiza un retiro de dinero a través de la API de PAYKU.
     *
     * @param CuentaCobro $CuentaCobro Objeto CuentaCobro que contiene información sobre el retiro.
     * @param string      $ProductoId  ID del producto (opcional).
     *
     * @return void
     * @throws Exception Si ocurre un error durante el proceso de retiro.
     */
    public function cashOut(CuentaCobro $CuentaCobro, $ProductoId = "")
    {
        $Usuario = new Usuario($CuentaCobro->usuarioId);
        $Registro = new Registro("", $CuentaCobro->usuarioId);

        $Mandante = $Usuario->mandante;

        $Subproveedor = new Subproveedor('', 'PAYKUPAY');
        $Subproveedor = new SubproveedorMandante($Subproveedor->getSubproveedorId(), $Mandante, '');
        $Detalle = $Subproveedor->detalle;
        $Detalle = json_decode($Detalle);
        $this->username = $Detalle->username;


        $UsuarioBanco = new UsuarioBanco($CuentaCobro->mediopagoId);
        $Banco = new Banco($UsuarioBanco->bancoId);

        if ($ProductoId == "") {
            $Producto = new Producto($Banco->productoPago);
        } else {
            $Producto = new Producto($ProductoId);
        }


        if ($CuentaCobro->usuarioId == '2420738') {
            $Producto = new Producto(16452);
        }


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

        $usuario_id = $Usuario->usuarioId;
        $account_id = $UsuarioBanco->cuenta;
        $typeAccount = $UsuarioBanco->getTipoCuenta();
        $amount = $CuentaCobro->getValor();
        $name = $Usuario->nombre;
        $subject = ' Transferencia Cuenta' . $CuentaCobro->getCuentaId();
        $user_email = $Usuario->login;
        $phone_number = $Registro->celular;
        $currency = $Usuario->moneda;
        $cedula = $Registro->cedula;


        switch ($UsuarioBanco->getTipoCuenta()) {
            case "0":
                $typeAccount = "3";
                break;
            case "1":
                $typeAccount = "1";
                break;
            case "Ahorros":
                $typeAccount = "3";
                break;
            case "Corriente":
                $typeAccount = "1";
                break;
            case "Vista":
                $typeAccount = "2";
                break;
            default:
                throw new Exception("Tipo de cuenta bancaria no encontrada", "100000");
                break;
        }

        $this->tipo = "/wallet/payout";

        $request_path = urlencode('/api/wallet/payout');

        $data = [
            "email" => $user_email,
            "phone" => $phone_number,
            "subject" => "Solicitud de Retiro" . $Usuario->usuarioId . $subject,
            "currency" => "CLP",
            "order" => $transproductoId,
            "amount" => $amount,
            "accountbank_name" => $name,
            "accountbank_rut" => $cedula,
            "accountbank_sbif" => $Producto->externoId,
            "accountbank_type" => $typeAccount,
            "accountbank_num" => $account_id,
        ];

        ksort($data);

        $i = oldCount($data);
        $array_concat = null;
        foreach ($data as $key => $val) {
            $array_concat .= $key . '=' . urlencode($val);
            $last_iteration = ! (--$i);
            if ( ! $last_iteration) {
                $array_concat .= '&';
            }
        }

        $concat = $request_path . '&' . $array_concat;

        $sign = hash_hmac('sha256', $concat, $this->tokenPRIV);


        syslog(LOG_WARNING, "PAYKU DATA PAYOUT " . json_encode($data));


        $result = $this->request($data, $sign);


        syslog(LOG_WARNING, 'PAYKU RESPONSE PAYOUT' . (json_encode($result)));


        if ($result != "" && $result->status == "success") {
            $TransprodLog = new TransprodLog();
            $TransprodLog->setTransproductoId($transproductoId);
            $TransprodLog->setEstado('E');
            $TransprodLog->setTipoGenera('M');
            $TransprodLog->setComentario('Envio Solicitud de pago PAYKU');
            $TransprodLog->setTValue(json_encode($result));
            $TransprodLog->setUsucreaId(0);
            $TransprodLog->setUsumodifId(0);

            $TransaccionProducto->setExternoId($result->identifier_wallet);
            $TransaccionProductoMySqlDAO->update($TransaccionProducto);


            $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
            $TransprodLogMySqlDAO->insert($TransprodLog);

            $Transaction->commit();
        } else {
            throw new Exception("La transferencia no fue procesada", "100000");
        }
    }


    /**
     * Realiza una solicitud a la API de PAYKU.
     *
     * @param array  $data Datos a enviar en la solicitud.
     * @param string $sign Firma de autenticación.
     *
     * @return object Respuesta de la API decodificada en formato JSON.
     */
    public function request($data, $sign)
    {
        $curl = curl_init();

        $header = array(
            "Content-Type: application/json",
            'Authorization: Bearer ' . $this->tokenPUB,
            'sign: ' . $sign,
            'Cookie: PHPSESSID=lqkitgbfkksk9sq7rkidoq43on'
        );

        $url = $this->URL . $this->tipo;

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => $header
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response);
    }

    /**
     * Realiza una solicitud GET a la API de PAYKU para obtener información de la billetera.
     *
     * @param string $current_id ID de la billetera.
     *
     * @return string Respuesta de la API en formato JSON.
     */
    public function requestGET($current_id)
    {
        $request_path = urlencode('/api/wallet/');

        $data = [];

        ksort($data);

        $i = oldCount($data);
        $array_concat = null;
        foreach ($data as $key => $val) {
            $array_concat .= $key . '=' . urlencode($val);
            $last_iteration = ! (--$i);
            if ( ! $last_iteration) {
                $array_concat .= '&';
            }
        }

        $concat = $request_path . '&' . $array_concat;
        $sign = hash_hmac('sha256', $concat, $this->tokenPRIV);

        $header = array(
            "Content-Type: application/json",
            'Authorization: Bearer ' . $this->tokenPUB,
            'sign: ' . $sign,
        );

        $curl = curl_init();

        $this->tipo = "/wallet/";

        $url = $this->URL . $this->tipo . $current_id;

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => $header
        ));

        $response = curl_exec($curl);

        curl_close($curl);


        return $response;
    }

}
