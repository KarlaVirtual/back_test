<?php
/**
 * Gestiona la comunicación con el proveedor de payout
 *
 * @category Red
 * @package  API
 * @author   sebastian.rico@virtualsoft.tech
 * @version  1.0.0
 * @since    02.07.25
 */

namespace Backend\integrations\payout;

use Exception;
use \CurlWrapper;
use Backend\dto\Pais;
use Backend\dto\Usuario;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\CuentaCobro;
use Backend\dto\TransprodLog;
use Backend\dto\UsuarioBanco;
use Backend\dto\UsuarioMandante;
use Backend\dto\TransaccionProducto;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;
use Backend\mysql\TransaccionProductoMySqlDAO;

/**
 * Esta clase incluye métodos para realizar retiros, consultar estados de retiros,
 * y manejar conexiones con la API de Payretailers.
 */
class PAYRETAILERSSERVICES
{

    /**
     * Representación de 'method'
     *
     * @var string
     * @access public
     */
    private $method;

    /**
     * Representación de 'token'
     *
     * @var string
     * @access public
     */
    public $token;

    /**
     * URL de callback..
     *
     * @var string
     */
    private $callback_url = "";

    /**
     * URL de callback en el entorno de desarrollo.
     *
     * @var string
     */
    private $callback_urlDEV = "https://apidevintegrations.virtualsoft.tech/integrations/payout/payretailers/confirm/";

    /**
     * URL de callback en el entorno de producción.
     *
     * @var string
     */
    private $callback_urlPROD = "https://integrations.virtualsoft.tech/payout/payretailers/confirm/";

    /**
     * Constructor de la clase.
     *
     * Configura la URL de la API según el entorno (desarrollo o producción).
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
     * Realiza un retiro de dinero a través de Payretailers.
     *
     * @param CuentaCobro $CuentaCobro Objeto que contiene la información de la cuenta de cobro.
     * @param Producto    $Producto    Objeto que contiene la información del producto asociado al retiro.
     *
     * @throws Exception Si ocurre un error durante el proceso de retiro.
     */
    public function cashOut(CuentaCobro $CuentaCobro, $Producto)
    {
        $Usuario = new Usuario($CuentaCobro->usuarioId);
        $Registro = new Registro("", $CuentaCobro->usuarioId);
        $Pais = new Pais($Usuario->paisId);
        $UsuarioBanco = new UsuarioBanco($CuentaCobro->mediopagoId);

        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
        $Transaction = $TransaccionProductoMySqlDAO->getTransaction();
        $valorFinal = $CuentaCobro->getValorAPagar();

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

        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);

        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

        switch ($UsuarioBanco->getTipoCuenta()) {
            case "Ahorros":
                if ($Usuario->paisId == 46) {
                    $PayoutAccountTypeCode = "0002";
                } elseif ($Usuario->paisId == 146) {
                    $PayoutAccountTypeCode = "0001";
                } elseif ($Usuario->paisId = 173) {
                    $PayoutAccountTypeCode = "0001";
                }
                break;
            case "Corriente":
                $PayoutAccountTypeCode = "0001";
                break;
            case "Vista":
                $PayoutAccountTypeCode = "0001";
                break;
        }

        switch ($Pais->iso) {
            case "PE":
                $documentType = "04";
                break;
            case "MX":
                $documentType = "40";
                break;
            case "CL":
                $documentType = "cl_rut";
                break;
        }

        $data = array(
            "BeneficiaryFirstName" => $Registro->nombre1,
            "BeneficiaryLastName" => $Registro->apellido1,
            "DocumentType" => $documentType,
            "DocumentNumber" => $Registro->cedula,
            "Email" => $Registro->email,
            "CurrencyCode" => $UsuarioMandante->moneda,
            "Country" => $Pais->iso,
            "BankName" => $Producto->externoId,
            "AccountNumber" => $UsuarioBanco->cuenta,
            "Amount" => $valorFinal,
            "PayoutAccountTypeCode" => $PayoutAccountTypeCode,
            "ExternalReference" => $transproductoId,
            "NotificationUrl" => $this->callback_url,
        );

        $result = $this->connection($data, $Credentials);

        syslog(LOG_WARNING, "PAYRETAILERS PAYOUT DATA" . json_encode($data) . 'RESPONSE: ' . $result);

        $result = json_decode($result);

        if ($result != "" && $result != null && is_int($result)) {
            $TransprodLog = new TransprodLog();
            $TransprodLog->setTransproductoId($transproductoId);
            $TransprodLog->setEstado('E');
            $TransprodLog->setTipoGenera('M');
            $TransprodLog->setComentario('Envio Solicitud de pago');
            $TransprodLog->setTValue(json_encode($data));
            $TransprodLog->setUsucreaId(0);
            $TransprodLog->setUsumodifId(0);

            $TransaccionProducto->setExternoId($result);
            $TransaccionProductoMySqlDAO->update($TransaccionProducto);

            $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
            $TransprodLogMySqlDAO->insert($TransprodLog);

            $Transaction->commit();
        } else {
            throw new Exception("No se pudo realizar la transaccion", "100000");
        }
    }

    /**
     * Realiza una conexión a la API de Payretailers para enviar datos de retiro.
     *
     * @param array $data Datos del retiro a enviar.
     * @param object $Credentials Credenciales necesarias para la conexión.
     *
     * @return string Respuesta de la API.
     */
    function connection($data, $Credentials)
    {
        $URL = $Credentials->URL;
        $USERNAME = $Credentials->USERNAME;
        $PASSWORD = $Credentials->PASSWORD;
        $SUBSCRIPTION_KEY = $Credentials->SUBSCRIPTION_KEY;

        $curl = new CurlWrapper($URL);

        $curl->setOptionsArray(array(
            CURLOPT_URL => $URL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic ' . base64_encode($USERNAME . ':' . $PASSWORD),
                'Content-Type: application/json',
                'Ocp-Apim-Subscription-Key: ' . $SUBSCRIPTION_KEY,
                'Accept: application/json'
            ),
        ));

        $response = $curl->execute();

        return $response;
    }
}
