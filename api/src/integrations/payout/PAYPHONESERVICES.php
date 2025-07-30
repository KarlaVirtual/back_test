<?php

/**
 * Clase que proporciona servicios de integración con PayPhone..
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-19
 */

namespace Backend\integrations\payout;

use Backend\dto\Mandante;
use Exception;
use Backend\dto\Banco;
use Backend\dto\BancoDetalle;
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
 * Clase que proporciona servicios de integración con PayPhone.
 *
 * Esta clase incluye métodos para realizar pagos, transferencias y consultar
 * el estado de pagos a través de la API de PayPhone.
 */
class PAYPHONESERVICES
{
    /**
     * URL de la API de PayPhone.
     *
     * @var string
     */
    private $URL = "";

    /**
     * URL de la API de PayPhone en modo desarrollo.
     *
     * @var string
     */
    private $URLDEV = 'https://pay.payphonetodoesposible.com';

    /**
     * URL de la API de PayPhone en modo producción.
     *
     * @var string
     */
    private $URLPROD = 'https://pay.payphonetodoesposible.com';

    /**
     * Token de autenticación para la API de PayPhone.
     *
     * @var string
     */
    private $Token = "";

    /**
     * Token de autenticación para la API de PayPhone en modo desarrollo.
     *
     * @var string
     */
    private $TokenDEV = "0Ie8zIGed7MnL5CD4NmGewF6NWW0eIat2uNtbsvipw9kp8UvV1LDJsrCnNc5bjc51qlVsvBsPCUcPK4P3KevV21XuYyhwI5Ib3uCBGXzQqfaCjVhF2UUyz63hbBXA1NOlFyoobBez9k0AH9ynVhZQE-DhSTarzy71-DxlPjQ_uyY0Hz9sNubCeYT05149-Ll0dv4fXekBC9la87JUMxZJWawi4G6Wp7vMCRFsXtGfrbIQa-hf3vdhJL6KGs7g365clsJZ9xNtV9VgfuHYv--sW_xhrf_axW0FRV888Ya27txwCK3-Un8naaucWL1Vq1j7dNTOQ";

    /**
     * Token de autenticación para la API de PayPhone en modo producción.
     *
     * @var string
     */
    private $TokenPROD = "f6WQ4rzPARkLDXj4ZgDVV3uUtyVeyGCYYZHAX4rBn6FH0pbu3pbrqqbsPIFSqhChTZ9SAVAgAcsBeHqNsMhakli-nCoKRhGZ6rr2TpBm9Bs_s2GKI8ezZzaxW7rEnN0xaLfeFLb385QDV_DhHD3HrsZEceBsONS5SvHqGBPPexpRdVtXWDquTYBK1y5uSjGYgO6c1f2KrBqL2ppEF_QaWdG2sgadNsnBxMSp0KGVLl2N5IcAzmm30NQzVWmFh_XfatUEL5oDZvbPfFMIJcKuBOqLtDTWVpxXHDCzrIU1_B-CbwVQG62Q6QQbKlaTijaxN_h4eQ";

    /**
     * ID de la tienda en PayPhone.
     *
     * @var string
     */
    private $StoreId = "";

    /**
     * ID de la tienda en PayPhone en modo desarrollo.
     *
     * @var string
     */
    private $StoreIdDEV = "896cadbc-fbf1-40ff-8e27-a98a92e5af87";

    /**
     * ID de la tienda en PayPhone en modo producción.
     *
     * @var string
     */
    private $StoreIdPROD = "896cadbc-fbf1-40ff-8e27-a98a92e5af87";

    /**
     * Identificador de la tienda en PayPhone.
     *
     * @var string
     */
    private $Identificador = "";

    /**
     * Identificador de la tienda en PayPhone en modo desarrollo.
     *
     * @var string
     */
    private $IdentificadorDEV = "pHvHbxDr0muonnnZXmSgQ";

    /**
     * Identificador de la tienda en PayPhone en modo producción.
     *
     * @var string
     */
    private $IdentificadorPROD = "QGBzqB07k0qgXm3rNJ82nA";

    /**
     * Clave de cifrado para la API de PayPhone.
     *
     * @var string
     */
    private $Key = "";

    /**
     * Clave de cifrado para la API de PayPhone en modo desarrollo.
     *
     * @var string
     */
    private $KeyDEV = "5bd30f26d2dc41a8be1da3e16d4b69ac";

    /**
     * Clave de cifrado para la API de PayPhone en modo producción.
     *
     * @var string
     */
    private $KeyPROD = "6769d0a337534b1491b2ff11f124ec36";

    /**
     * Constructor de la clase PAYPHONESERVICES.
     *
     * Inicializa las propiedades de la clase dependiendo del entorno
     * (desarrollo o producción) utilizando la configuración proporcionada.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->URL = $this->URLDEV;
            $this->Token = $this->TokenDEV;
            $this->StoreId = $this->StoreIdDEV;
            $this->Identificador = $this->IdentificadorDEV;
            $this->Key = $this->KeyDEV;
        } else {
            $this->URL = $this->URLPROD;
            $this->Token = $this->TokenPROD;
            $this->StoreId = $this->StoreIdPROD;
            $this->Identificador = $this->IdentificadorPROD;
            $this->Key = $this->KeyPROD;
        }
    }

    /**
     * Realiza un retiro de dinero a través de la API de PayPhone.
     *
     * @param array    $CuentaCobro Array de objetos CuentaCobro que representan los retiros a realizar.
     * @param Producto $Producto    Objeto Producto que representa el producto asociado al retiro.
     *
     * @return void
     * @throws Exception Si ocurre un error durante el proceso de retiro.
     */
    public function cashOut($CuentaCobro, $Producto)
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 'OFF');

        if ( ! is_array($CuentaCobro)) {
            $CuentaCobro = array($CuentaCobro);
        }

        $UsuarioProv = new Usuario($CuentaCobro[0]->usuarioId);
        $data = array();
        $data['currency'] = $UsuarioProv->moneda;
        $data['reference'] = 'Solicitud de retiro';
        $data['paymentItems'] = array();

        foreach ($CuentaCobro as $key => $value) {
            $Usuario = new Usuario($CuentaCobro[$key]->usuarioId);
            $Registro = new Registro("", $CuentaCobro[$key]->usuarioId);

            $mandante = $Usuario->mandante;

            $Subproveedor = new Subproveedor('', 'PAYPHONEOUT');
            $Subproveedor = new SubproveedorMandante($Subproveedor->getSubproveedorId(), $mandante, '');

            $UsuarioBanco = new UsuarioBanco($CuentaCobro[$key]->mediopagoId);
            $Banco = new Banco($UsuarioBanco->bancoId);

            $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
            $Transaction = $TransaccionProductoMySqlDAO->getTransaction();

            $TransaccionProducto = new TransaccionProducto();
            $TransaccionProducto->setProductoId($Producto->productoId);
            $TransaccionProducto->setUsuarioId($Usuario->usuarioId);
            $TransaccionProducto->setValor($CuentaCobro[$key]->getValor());
            $TransaccionProducto->setEstado('A');
            $TransaccionProducto->setTipo('T');
            $TransaccionProducto->setExternoId(0);
            $TransaccionProducto->setEstadoProducto('E');
            $TransaccionProducto->setMandante($Usuario->mandante);
            $TransaccionProducto->setFinalId($CuentaCobro[$key]->getCuentaId());
            $TransaccionProducto->setFinalId(0);

            $transproductoId = $TransaccionProductoMySqlDAO->insert($TransaccionProducto);

            $CuentaCobro[$key]->setTransproductoId($transproductoId);
            $CuentaCobro[$key]->setEstado('X');

            $bankId = $Producto->getExternoId();
            $account_id = $UsuarioBanco->cuenta;
            $typeAccount = $UsuarioBanco->getTipoCuenta();
            $amount = $CuentaCobro[$key]->getValor();
            $tipoDocumento = $Registro->tipoDoc;

            switch ($tipoDocumento) {
                case "E":
                    $tipoDocumento = "CE";
                    break;
                case "P":
                    $tipoDocumento = "TP";
                    break;
                case "C":
                    $tipoDocumento = "CC";
                    break;
                default:
                    $tipoDocumento = "CC";
                    break;
            }

            switch ($UsuarioBanco->getTipoCuenta()) {
                case "0":
                    $typeAccount = "C";
                    break;
                case "1":
                    $typeAccount = "B";
                    break;
                case "Ahorros":
                    $typeAccount = "C";
                    break;
                case "Corriente":
                    $typeAccount = "B";
                    break;
                default:
                    throw new Exception("Tipo de cuenta bancaria no encontrada", "10000");
                    break;
            }

            $datas = array(
                "identifier" => $account_id,
                "customerType" => $typeAccount,
                "amount" => intval($amount * 100),
                "reference" => $transproductoId,
            );

            array_push($data['paymentItems'], $datas);
        }

        $dataTrans = json_encode($data);
        $dataTrans = json_decode($dataTrans);

        syslog(LOG_WARNING, "PAYPHONEOUT DATA: " . json_encode($data));

        $DataEncode = $this->encode(json_encode($data['paymentItems']), $this->Key);

        $data['paymentItems'] = $DataEncode;

        syslog(LOG_WARNING, "PAYPHONEOUT DATA ENC: " . json_encode($data));

        $path = "/api/StorePayments/transfer";

        $Result = $this->request(json_encode($data), $path);

        syslog(LOG_WARNING, "PAYPHONEOUT RESPONSE: " . $Result);

        if ($_ENV['debug'] == true) {
            print_r("\r\n");
            print_r('****DATAUSER****');
            print_r(json_encode($dataTrans));
            print_r("\r\n");
            print_r('****DATAENC****');
            print_r(json_encode($data));
            print_r("\r\n");
            print_r('****RESPONSE****');
            print_r($Result);
        }

        $result = json_decode($Result);

        if ($result != "") {
            foreach ($dataTrans->paymentItems as $value) {
                $TransprodLog = new TransprodLog();
                $TransprodLog->setTransproductoId($value->reference);
                $TransprodLog->setEstado('E');
                $TransprodLog->setTipoGenera('M');
                $TransprodLog->setComentario('Envio Solicitud de pago');
                $TransprodLog->setTValue(json_encode($result));
                $TransprodLog->setUsucreaId(0);
                $TransprodLog->setUsumodifId(0);

                $TransaccionProducto->setExternoId($result->batchId);
                $TransaccionProductoMySqlDAO->update($TransaccionProducto);

                $CuentaCobro[$key]->setObservacion(json_encode($result));

                $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
                $TransprodLogMySqlDAO->insert($TransprodLog);

                $Transaction->commit();
            }
        } else {
            throw new Exception("La transferencia no fue procesada", "10000");
        }
    }


    /**
     * Realiza una solicitud a la API de PayPhone.
     *
     * @param string $data Datos a enviar en la solicitud.
     * @param string $path Ruta de la API a la que se enviará la solicitud.
     *
     * @return string Respuesta de la API.
     */
    public function request($data, $path)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->URL . $path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->Token
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    /**
     * Consulta el estado de un pago a través de la API de PayPhone.
     *
     * @param string $path     Ruta de la API para consultar el estado del pago.
     * @param string $mandante ID del mandante (opcional).
     * @param string $pais     País asociado al pago.
     *
     * @return string Respuesta de la API con el estado del pago.
     */
    public function getStatusPayout($path, $mandante = '0', $pais)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->URL . $path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $this->Token,
                'Content-Type: application/json',
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    /**
     * Cifra los datos utilizando AES-256-CBC.
     *
     * @param string $data Datos a cifrar.
     * @param string $key  Clave de cifrado.
     *
     * @return string Datos cifrados en base64.
     */
    public function encode($data, $key)
    {
        $encrypted = base64_encode(openssl_encrypt($data, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, ""));

        return $encrypted;
    }


}
