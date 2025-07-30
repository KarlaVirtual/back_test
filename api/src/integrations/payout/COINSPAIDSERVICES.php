<?php

/**
 * Esta clase gestiona las operaciones de integración con el servicio de Coinspaid.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-19
 */

namespace Backend\integrations\payout;

use Backend\dto\BancoDetalle;
use Backend\dto\Mandante;
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
 * Clase COINSPAIDSERVICES
 *
 * Esta clase gestiona las operaciones de integración con el servicio de Coinspaid,
 * incluyendo la configuración de entornos, encriptación de datos y conexión con la API.
 */
class COINSPAIDSERVICES
{
    /**
     * URL base para las solicitudes a la API.
     *
     * @var string
     */
    private $URL = "";

    /**
     * URL de desarrollo para la API.
     *
     * @var string
     */
    private $URLDEV = 'https://app.sandbox.cryptoprocessing.com/api/v2/';

    /**
     * URL de producción para la API.
     *
     * @var string
     */
    private $URLPROD = 'https://app.cryptoprocessing.com/api/v2/';

    /**
     * URL de callback configurada dinámicamente.
     *
     * @var string
     */
    private $callback_url = "";

    /**
     * URL de callback en desarrollo.
     *
     * @var string
     */
    private $callback_urlDEV = "https://apidev.virtualsoft.tech/integrations/payout/coinspaid/confirm/";

    /**
     * URL de callback en producción.
     *
     * @var string
     */
    private $callback_urlPROD = "https://integrations.virtualsoft.tech/payout/coinspaid/confirm/";

    /**
     * Clave privada configurada dinámicamente.
     *
     * @var string
     */
    private $KeyPRIVATE = "";

    /**
     * Clave privada en desarrollo.
     *
     * @var string
     */
    private $KeyPRIVATEDEV = "QMl8igj8VjgGbNsTjj7vCbPw752MOaa6EOvKsBNqtlVP4eL1SFLwNOcqv4FgpLOE";

    /**
     * Clave privada en producción.
     *
     * @var string
     */
    private $KeyPRIVATEPROD = "ppsxHq9pwr6vbBDek7WbGbwPc2YWkPQhI806na3LLD0EkUpX7OgjmQEyTIRVjhBs";

    /**
     * Clave pública configurada dinámicamente.
     *
     * @var string
     */
    private $KeyPUBLIC = "";

    /**
     * Clave pública en desarrollo.
     *
     * @var string
     */
    private $KeyPUBLICDEV = "nvAXNtbiYs8nNjjMpg1t6wxRw5C4b8pL";

    /**
     * Clave pública en producción.
     *
     * @var string
     */
    private $KeyPUBLICPROD = "x41doCzyELi4BOOH4nbKpb13GrsyHFKL";

    /**
     * Token de autenticación.
     *
     * @var string
     */
    private $Auth = "";

    /**
     * ID del cliente configurado dinámicamente.
     *
     * @var string
     */
    private $client_id = "";

    /**
     * ID del cliente en desarrollo.
     *
     * @var string
     */
    private $client_idDEV = "";

    /**
     * ID del cliente en producción.
     *
     * @var string
     */
    private $client_idPROD = "";

    /**
     * URL de depósito configurada dinámicamente.
     *
     * @var string
     */
    private $URLDEPOSIT = '';

    /**
     * URL de depósito en desarrollo.
     *
     * @var string
     */
    private $URLDEPOSITDEV = "https://devfrontend.virtualsoft.tech/doradobetv4/gestion/deposito";

    /**
     * URL de depósito en producción.
     *
     * @var string
     */
    private $URLDEPOSITPROD = "https://doradobet.com/gestion/deposito";

    /**
     * Constructor de la clase.
     *
     * Configura las variables de entorno dependiendo del entorno de ejecución (desarrollo o producción).
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->client_id = $this->client_idDEV;
            $this->callback_url = $this->callback_urlDEV;
            $this->URL = $this->URLDEV;
            $this->KeyPRIVATE = $this->KeyPRIVATEDEV;
            $this->KeyPUBLIC = $this->KeyPUBLICDEV;
            $this->URLDEPOSIT = $this->URLDEPOSITDEV;
        } else {
            $this->client_id = $this->client_idPROD;
            $this->callback_url = $this->callback_urlPROD;
            $this->URL = $this->URLPROD;
            $this->KeyPRIVATE = $this->KeyPRIVATEPROD;
            $this->KeyPUBLIC = $this->KeyPUBLICPROD;
            $this->URLDEPOSIT = $this->URLDEPOSITPROD;
        }
    }

    /**
     * Realiza un retiro (cash out) utilizando la API de Coinspaid.
     *
     * @param CuentaCobro $CuentaCobro Objeto que contiene los datos de la cuenta de cobro.
     * @param Producto    $Producto    Objeto que contiene los datos del producto.
     *
     * @return void
     * @throws Exception Si ocurre un error en el proceso de transferencia o en la validación de datos.
     */
    public function cashOut(CuentaCobro $CuentaCobro, $Producto)
    {
        $Usuario = new Usuario($CuentaCobro->usuarioId);
        $Registro = new Registro("", $CuentaCobro->usuarioId);

        $mandante = $Usuario->mandante;
        $Mandante = new Mandante($Usuario->mandante);

        $UsuarioOtrainfo = new UsuarioOtrainfo($mandante);

        $Subproveedor = new Subproveedor('', 'COINSPAIDOUT');

        $UsuarioBanco = new UsuarioBanco($CuentaCobro->mediopagoId);
        $Banco = new Banco($UsuarioBanco->bancoId);

        $Producto = new Producto($Producto->productoId);

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
        $email = $Registro->email;
        $naci = $UsuarioOtrainfo->fechaNacim;
        $Pais = new Pais($Usuario->paisId);

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
            case "CRYPTO":
                $typeAccount = "BNB";
                break;
            default:
                throw new Exception("Tipo de cuenta bancaria no encontrada", "10000");
                break;
        }

        $dataT = array();
        $dataT['foreign_id'] = $transproductoId;//OK
        $dataT['amount'] = $amount;//OK
        $dataT['currency'] = $currency;//OK
        $dataT['convert_to'] = 'BTC';//OK
        $dataT['address'] = $account_id;//OK
        $dataT['tag'] = $typeAccount;//OK

        syslog(LOG_WARNING, "COINSPAIDOUT DATA: " . json_encode($dataT));

        $path = "withdrawal/crypto";

        $dataT = json_encode($dataT);

        $data_enc = $this->encrypta($dataT);

        $Result = $this->connection($dataT, $data_enc, $path);

        syslog(LOG_WARNING, "COINSPAIDOUT RESPONSE: " . $Result);

        $array_encode = array_merge($dataT, $Result);

        $Result = json_decode($Result);

        if ($Result != "" && $Result != null && $Result->data->status == "processing") {
            $TransprodLog = new TransprodLog();
            $TransprodLog->setTransproductoId($transproductoId);
            $TransprodLog->setEstado('E');
            $TransprodLog->setTipoGenera('M');
            $TransprodLog->setComentario('Envio Solicitud de pago');
            $TransprodLog->setTValue($array_encode);
            $TransprodLog->setUsucreaId(0);
            $TransprodLog->setUsumodifId(0);

            $TransaccionProducto->setExternoId($Result->data->id);
            $TransaccionProductoMySqlDAO->update($TransaccionProducto);


            $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
            $TransprodLogMySqlDAO->insert($TransprodLog);

            $Transaction->commit();
        } else {
            if ($Result->errors != "" || $Result->errors != null) {
                throw new Exception($Result->errors->amount, "10000");
            } else {
                throw new Exception("La transferencia no fue procesada", "10000");
            }
        }
    }


    /**
     * Encripta los datos utilizando HMAC-SHA512.
     *
     * @param string $data Datos a encriptar.
     *
     * @return string Cadena encriptada.
     */
    public function encrypta($data)
    {
        $enc = $this->Auth = hash_hmac('sha512', $data, $this->KeyPRIVATE);
        return $enc;
    }

    /**
     * Realiza una conexión con la API de Coinspaid.
     *
     * @param string $data     Datos en formato JSON que se enviarán en la solicitud.
     * @param string $data_enc Firma encriptada de los datos.
     * @param string $path     Ruta del endpoint de la API.
     *
     * @return string Respuesta de la API.
     */
    public function connection($data, $data_enc, $path)
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
                'X-Processing-Key: ' . $this->KeyPUBLIC,
                'X-Processing-Signature: ' . $data_enc
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
}
