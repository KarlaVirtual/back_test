<?php

/**
 * Clase que gestiona los servicios de integración con EZZEPAY.
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
 * Clase que gestiona los servicios de integración con EZZEPAY.
 */
class EZZEPAYSERVICES
{
    /**
     * Constructor de la clase. Configura las credenciales y URLs según el entorno.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
        } else {
        }
    }

    /**
     * Realiza un retiro de dinero (cash out) utilizando los servicios de EZZEPAY.
     *
     * @param CuentaCobro $CuentaCobro Objeto que contiene la información de la cuenta de cobro.
     * @param integer     $productoId  ID del producto asociado al retiro.
     *
     * @return void
     * @throws Exception Si el tipo de cuenta bancaria no es válido o si la transferencia no fue procesada.
     */
    public function cashOut(CuentaCobro $CuentaCobro, $productoId)
    {
        $Usuario = new Usuario($CuentaCobro->usuarioId);
        $Registro = new Registro("", $CuentaCobro->usuarioId);

        $UsuarioBanco = new UsuarioBanco($CuentaCobro->mediopagoId);

        $Producto = new Producto($productoId);

        $SubproveedorMandantePais = new SubproveedorMandantePais("", $Producto->subproveedorId, $Usuario->mandante, $Usuario->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

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

        $pathToken = "/v2/oauth/token";

        $Respueta = $this->CreateToken($pathToken, $Credentials);
        $response = json_decode($Respueta);

        $token = $response->access_token;

        switch ($UsuarioBanco->getTipoCuenta()) {
            case "0":
                $typeAccount = "CHAVE_ALEATORIA";
                break;
            case "1":
                $typeAccount = "CHAVE_ALEATORIA";
                break;
            case "Ahorros":
                $typeAccount = "CHAVE_ALEATORIA";
                break;
            case "Corriente":
                $typeAccount = "CHAVE_ALEATORIA";
                break;
            case "CPF":
                $typeAccount = "CPF";
                break;
            case "CNPJ":
                $typeAccount = "CNPJ";
                break;
            case "EMAIL":
                $typeAccount = "EMAIL";
                break;
            case "PHONE":
                $typeAccount = "TELEFONE";
                break;

            default:
                throw new Exception("Tipo de cuenta bancaria no encontrada", "10000");
                break;
        }

        $pix_key = $UsuarioBanco->cuenta;

        if ($typeAccount == "TELEFONE") {
            $pix_key = '+55' . $pix_key;
        }

        $data['amount'] = $CuentaCobro->valor;
        $data['external_id'] = $transproductoId;
        $data['description'] = $Usuario->usuarioId . '##' . $transproductoId;
        $data['creditParty'] = [
            "name" => $Registro->nombre,
            "keyType" => $typeAccount,
            "key" => $pix_key,
            "taxId" => $Registro->cedula,
        ];

        $encodedData = json_encode($data);

        $path = "/v2/pix/payment";

        syslog(LOG_WARNING, "EZZEPAY DATA PAYOUT" . $encodedData);

        $result = $this->request($encodedData, $token, $path, $Credentials->URL);

        if ($_ENV['debug']) {
            print_r($encodedData);
            print_r(PHP_EOL);
            print_r($result);
        }

        syslog(LOG_WARNING, "EZZEPAY RESPONSE PAYOUT" . $result);

        $result_ = json_decode($result);

        if ($result_->status == "PROCESSING") {
            $TransprodLog = new TransprodLog();
            $TransprodLog->setTransproductoId($transproductoId);
            $TransprodLog->setEstado('E');
            $TransprodLog->setTipoGenera('M');
            $TransprodLog->setComentario('Envio Solicitud de pago');
            $TransprodLog->setTValue(json_encode($result_));
            $TransprodLog->setUsucreaId(0);
            $TransprodLog->setUsumodifId(0);

            $TransaccionProducto->setExternoId($result_->transactionId);
            $TransaccionProductoMySqlDAO->update($TransaccionProducto);


            $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
            $TransprodLogMySqlDAO->insert($TransprodLog);

            $Transaction->commit();
        } else {
            throw new Exception("La transferencia no fue procesada", "10000");
        }
    }

    /**
     * Realiza una solicitud HTTP POST a un endpoint de EZZEPAY con los datos codificados y el token de autorización.
     *
     * @param string $encodedData Datos codificados en JSON para enviar en la solicitud.
     * @param string $token       Token de autorización para la solicitud.
     * @param string $path        Ruta del endpoint al que se realiza la solicitud.
     * @param string $URL         URL base del servicio EZZEPAY.
     *
     * @return string Respuesta de la solicitud HTTP.
     */
    public function request($encodedData, $token, $path, $URL)
    {
        $curl = new CurlWrapper($URL . $path);

        $curl->setOptionsArray(array(
            CURLOPT_URL => $URL . $path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $encodedData,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json'
            ),
        ));

        $response = $curl->execute();

        return $response;
    }

    /**
     * Crea un token de acceso utilizando las credenciales proporcionadas.
     *
     * @param string $pathToken   Ruta del endpoint para crear el token.
     * @param object $Credentials Objeto que contiene las credenciales necesarias.
     *
     * @return string Respuesta del servidor al solicitar el token.
     */
    public function CreateToken($pathToken, $Credentials)
    {
        $URL = $Credentials->URL;
        $CLIENT_ID = $Credentials->CLIENT_ID;
        $CLIENT_SECRET = $Credentials->CLIENT_SECRET;

        $curl = new CurlWrapper($URL . $pathToken);

        $curl->setOptionsArray(array(
            CURLOPT_URL => $URL . $pathToken,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array('grant_type' => 'client_credentials'),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic ' . base64_encode($CLIENT_ID . ':' . $CLIENT_SECRET)
            ),
        ));

        $response = $curl->execute();

        return $response;
    }

}
