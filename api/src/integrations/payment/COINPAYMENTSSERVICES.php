<?php

/**
 * Clase COINPAYMENTSSERVICES
 *
 * Esta clase implementa la integración con el servicio de pagos CoinPayments.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-23
 */

namespace Backend\Integrations\payment;

use Exception;
use Backend\dto\Pais;
use Backend\dto\Usuario;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\Clasificador;
use Backend\dto\PaisMandante;
use Backend\dto\Subproveedor;
use Backend\dto\TransprodLog;
use Backend\dto\MandanteDetalle;
use Backend\dto\TransaccionProducto;
use Backend\dto\SubproveedorMandante;
use Backend\dto\TransproductoDetalle;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransproductoDetalleMySqlDAO;

/**
 * Clase COINPAYMENTSSERVICES
 *
 * Esta clase se encarga de manejar la integración con el proveedor de pagos CoinPayments.
 * Proporciona métodos para crear solicitudes de pago y gestionar transacciones.
 */
class COINPAYMENTSSERVICES
{
    /**
     * Nombre de usuario para la autenticación con CoinPayments.
     * Este valor se inicializa dependiendo del entorno (desarrollo o producción).
     *
     * @var string
     */
    private $username = "";

    /**
     * Contraseña para la autenticación con CoinPayments.
     * Este valor se inicializa dependiendo del entorno (desarrollo o producción).
     *
     * @var string
     */
    private $password = "";

    /**
     * Nombre de usuario para el entorno de desarrollo.
     *
     * Este valor se utiliza para la autenticación con CoinPayments
     * cuando el entorno es de desarrollo.
     *
     * @var string
     */
    private $usernameDEV = "";

    /**
     * Nombre de usuario para el entorno de producción.
     *
     * Este valor se utiliza para la autenticación con CoinPayments
     * cuando el entorno es de producción.
     *
     * @var string
     */
    private $usernamePROD = "";

    /**
     * URL del servicio de CoinPayments.
     *
     * Este valor se inicializa dependiendo del entorno (desarrollo o producción).
     *
     * @var string
     */
    private $URL = "";

    /**
     * URL del servicio de CoinPayments para el entorno de desarrollo.
     *
     * Este valor se utiliza para realizar solicitudes al servicio de CoinPayments
     * en el entorno de desarrollo.
     *
     * @var string
     */
    private $URLDEV = 'https://www.coinpayments.net/api.php';

    /**
     * URL del servicio de CoinPayments para el entorno de producción.
     *
     * Este valor se utiliza para realizar solicitudes al servicio de CoinPayments
     * en el entorno de producción.
     *
     * @var string
     */
    private $URLPROD = 'https://www.coinpayments.net/api.php';

    /**
     * URL de callback para recibir notificaciones de CoinPayments.
     *
     * Este valor se inicializa dependiendo del entorno (desarrollo o producción).
     *
     * @var string
     */
    private $callback_url = "";

    /**
     * URL de callback para el entorno de desarrollo.
     *
     * Este valor se utiliza para recibir notificaciones de CoinPayments
     * en el entorno de desarrollo.
     *
     * @var string
     */
    private $callback_urlDEV = "https://admincert.virtualsoft.tech/api/api/integrations/payment/coinpayments/confirm/";

    /**
     * URL de callback para el entorno de producción.
     *
     * Este valor se utiliza para recibir notificaciones de CoinPayments
     * en el entorno de producción.
     *
     * @var string
     */
    private $callback_urlPROD = "https://integrations.virtualsoft.tech/payment/coinpayments/confirm/";

    /**
     * Clave privada para la autenticación con CoinPayments.
     * Este valor se inicializa dependiendo del entorno (desarrollo o producción).
     *
     * @var string
     */
    private $KeyPRIVATE = "";

    /**
     * Clave privada para el entorno de desarrollo.
     *
     * Este valor se utiliza para la autenticación con CoinPayments
     * cuando el entorno es de desarrollo.
     *
     * @var string
     */
    private $KeyPRIVATEDEV = "0bc204aDd14e787EB0767Eb70485FB3a4dEcCA81ac03f46a2550D29825D4eeD3";

    /**
     * Clave privada para el entorno de producción.
     *
     * Este valor se utiliza para la autenticación con CoinPayments
     * cuando el entorno es de producción.
     *
     * @var string
     */
    private $KeyPRIVATEPROD = "0bc204aDd14e787EB0767Eb70485FB3a4dEcCA81ac03f46a2550D29825D4eeD3";

    /**
     * Clave pública para la autenticación con CoinPayments.
     * Este valor se inicializa dependiendo del entorno (desarrollo o producción).
     *
     * @var string
     */
    private $KeyPUBLIC = "";

    /**
     * Clave pública para el entorno de desarrollo.
     *
     * Este valor se utiliza para la autenticación con CoinPayments
     * cuando el entorno es de desarrollo.
     *
     * @var string
     */
    private $KeyPUBLICDEV = "f67175698b0980bea734e6ce4a26efa07c3c57b52a647ded039c5690164814fc";

    /**
     * Clave pública para el entorno de producción.
     *
     * Este valor se utiliza para la autenticación con CoinPayments
     * cuando el entorno es de producción.
     *
     * @var string
     */
    private $KeyPUBLICPROD = "f67175698b0980bea734e6ce4a26efa07c3c57b52a647ded039c5690164814fc";

    /**
     * Clave de autenticación generada a partir de la clave privada.
     * Este valor se utiliza para firmar las solicitudes a CoinPayments.
     *
     * @var string
     */
    private $Auth = "";

    /**
     * Constructor de la clase COINPAYMENTSSERVICES.
     *
     * Este constructor inicializa las variables de configuración
     * dependiendo del entorno (desarrollo o producción).
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->username = $this->usernameDEV;
            $this->callback_url = $this->callback_urlDEV;
            $this->URL = $this->URLDEV;
            $this->KeyPRIVATE = $this->KeyPRIVATEDEV;
            $this->KeyPUBLIC = $this->KeyPUBLICDEV;
        } else {
            $this->username = $this->usernamePROD;
            $this->callback_url = $this->callback_urlPROD;
            $this->URL = $this->URLPROD;
            $this->KeyPRIVATE = $this->KeyPRIVATEPROD;
            $this->KeyPUBLIC = $this->KeyPUBLICPROD;
        }
    }

    /**
     * Crea una solicitud de pago para un producto específico.
     *
     * @param Usuario  $Usuario    Objeto Usuario que contiene información del usuario.
     * @param Producto $Producto   Objeto Producto que contiene información del producto.
     * @param float    $valor      Valor del producto.
     * @param string   $urlSuccess URL de éxito para redirigir después del pago.
     * @param string   $urlFailed  URL de fallo para redirigir si el pago falla.
     *
     * @return object Resultado de la solicitud de pago.
     */
    public function createRequestPayment(Usuario $Usuario, Producto $Producto, $valor, $urlSuccess, $urlFailed)
    {
        $Registro = new Registro("", $Usuario->usuarioId);
        $data = array();
        $data["success"] = false;
        $data["error"] = 1;

        $estado = 'A';
        $estado_producto = 'E';
        $tipo = 'T';
        $usuario_id = $Usuario->usuarioId;
        $cedula = $Registro->cedula;
        $nombre = $Usuario->nombre;
        $email = $Usuario->login;
        $valor = $valor;
        $producto_id = $Producto->productoId;
        $moneda = $Usuario->moneda;
        $mandante = $Usuario->mandante;
        $descripcion = "Deposito";

        if ($mandante == 20) {
            $this->KeyPRIVATE = "0c14DBf4566B72805475D0477D990100578B0b614040945233862E4fbc21141d";
            $this->KeyPUBLIC = "0866d9764df801f8369878cfedbd4f745c094de9760fd0f0c40e5acb44ce6fae";
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

        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
        } else {
            if ($Usuario->mandante == '0' && $Usuario->moneda == 'CRC') {
                $this->KeyPRIVATE = '8deBB3a526aF09ea86DAba9385550731b5f3ACd59E13735351E871287a57E40e';
                $this->KeyPUBLIC = 'a4798be3cda364c12e68a279a8926acedfb0cfd0f8c55f111394917d2a0d5142';
            }

            if ($Usuario->mandante == '15' && $Usuario->moneda == 'HNL') {
                $this->KeyPRIVATE = 'cc8d00dBbE09280a45a19bcB91122eba987803130efB0140B220a1B6Cd294f9B';
                $this->KeyPUBLIC = '750ced2ca517d43fc5ba73687ba2d0477d34f4d2f5dc89d4b3d452d60bf7da9b';
            }
        }


        if ($Usuario->mandante == '15' && $Usuario->moneda == 'HNL') {
            $PaisMandante = new PaisMandante("", $Usuario->mandante, $Usuario->paisId);
            $valorTax = ($valorTax * $PaisMandante->trmUsd);
            $moneda = 'USD';
        }

        $data = "cmd=create_transaction&amount=" . $valorTax . "&currency1=" . $moneda . "&currency2=" . $Producto->getExternoId() . "&buyer_email=" . $email . "&invoice=" . $transproductoId . "&ipn_url=" . $this->callback_url . "&success_url=" . $urlSuccess . "&cancel_url=" . $urlFailed . "&version=1&key=" . $this->KeyPUBLIC;

        syslog(LOG_WARNING, "COINPAYMENTS DATA: " . $data);

        $this->Encrypta($data);
        $TransproductoDetalle = new TransproductoDetalle();
        $TransproductoDetalle->transproductoId = $transproductoId;
        $TransproductoDetalle->tValue = json_encode(($data));

        $TransproductoDetalleMySqlDAO = new TransproductoDetalleMySqlDAO($Transaction);
        $TransproductoDetalleMySqlDAO->insert($TransproductoDetalle);


        $Result = $this->connection($data);

        syslog(LOG_WARNING, "COINPAYMENTS RESPONSE: " . $Result);

        $data2 = array();
        $data2["success"] = false;
        if (json_decode($Result)->error == 'ok') {
            $TransprodLog = new TransprodLog();
            $TransprodLog->setTransproductoId($transproductoId);
            $TransprodLog->setEstado('E');
            $TransprodLog->setTipoGenera('A');
            $TransprodLog->setComentario('Envio Solicitud de deposito');
            $TransprodLog->setTValue($Result);
            $TransprodLog->setUsucreaId(0);
            $TransprodLog->setUsumodifId(0);

            $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
            $TransprodLogMySqlDAO->insert($TransprodLog);

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
            $response = json_decode($Result);

            $data2 = array();
            $data2["success"] = true;
            $data2["url"] = $response->result->checkout_url;
            $data2["target"] = '_blank';
        }

        return json_decode(json_encode($data2));
    }

    /**
     * Crea una solicitud de pago para un producto específico.
     *
     * @param string $data Datos a encriptar.
     *
     * @return void
     */
    public function Encrypta($data)
    {
        $this->Auth = hash_hmac('sha512', $data, $this->KeyPRIVATE);
    }

    /**
     * Realiza una conexión cURL para enviar datos a CoinPayments.
     *
     * @param string $data Datos a enviar.
     *
     * @return string Resultado de la conexión.
     */
    public function connection($data)
    {
        $curl = curl_init($this->URL);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded', 'HMAC:' . $this->Auth]);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 300);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }
}
