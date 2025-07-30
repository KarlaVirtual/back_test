<?php

/**
 * Este archivo contiene la clase `PAYUSERVICES` que gestiona la integración con el servicio de pagos PayU.
 *
 * Proporciona métodos para crear solicitudes de pago y manejar la configuración del entorno
 * (desarrollo o producción) para las URLs de confirmación y respuesta.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-25
 */

namespace Backend\integrations\payment;

use Exception;
use Backend\dto\Pais;
use Backend\dto\Banco;
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\CuentaCobro;
use Backend\dto\Clasificador;
use Backend\dto\PaisMandante;
use Backend\dto\Subproveedor;
use Backend\dto\TransprodLog;
use Backend\dto\UsuarioBanco;
use Backend\dto\MandanteDetalle;
use Backend\dto\UsuarioMandante;
use Backend\dto\ProductoMandante;
use Backend\dto\TransaccionProducto;
use Backend\dto\SubproveedorMandante;
use Backend\dto\TransproductoDetalle;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransproductoDetalleMySqlDAO;

/**
 * Clase `PAYUSERVICES` para gestionar la integración con PayU.
 */
class PAYUSERVICES
{

    /**
     * URL de callback utilizada en el entorno actual (desarrollo o producción).
     *
     * @var string
     */
    private $callback_url = "";

    /**
     * URL de callback para el entorno de desarrollo.
     *
     * @var string
     */
    private $callback_urlDEV = "https://apidevintegrations.virtualsoft.tech/integrations/payment/payu/confirm/";

    /**
     * URL de callback para el entorno de producción.
     *
     * @var string
     */
    private $callback_urlPROD = "https://integrations.virtualsoft.tech/payment/payu/confirm/";

    /**
     * Constructor de la clase `PAYUSERVICES`.
     *
     * Inicializa la URL de callback según el entorno configurado
     * (desarrollo o producción) utilizando la clase `ConfigurationEnvironment`.
     *
     * @throws Exception Si ocurre un error al determinar el entorno.
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
     * Crea una solicitud de pago para un usuario y un producto específicos.
     *
     * @param Usuario  $Usuario    Objeto que representa al usuario que realiza el pago.
     * @param Producto $Producto   Objeto que representa el producto asociado al pago.
     * @param float    $valor      Monto del pago.
     * @param string   $urlSuccess URL a la que se redirige en caso de éxito.
     * @param string   $urlFailed  URL a la que se redirige en caso de fallo.
     * @param string   $urlCancel  URL a la que se redirige en caso de cancelación.
     *
     * @return array Respuesta con los datos de la solicitud de pago.
     */
    public function createRequestPayment(Usuario $Usuario, Producto $Producto, $valor, $urlSuccess, $urlFailed, $urlCancel)
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        $Registro = new Registro("", $Usuario->usuarioId);
        $Mandante = new Mandante($Usuario->mandante);
        $Pais = new Pais($Usuario->paisId);

        $pais = $Usuario->paisId;
        $mandante = $Usuario->mandante;

        $data_ = array();
        $data_["success"] = false;
        $data_["error"] = 1;

        $estado = 'A';
        $estado_producto = 'E';
        $tipo = 'T';

        $externo = $Producto->externoId;

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
        $TransaccionProducto->setProductoId($Producto->productoId);
        $TransaccionProducto->setUsuarioId($Usuario->usuarioId);
        $TransaccionProducto->setValor($valor);
        $TransaccionProducto->setImpuesto($totalTax);
        $TransaccionProducto->setEstado($estado);
        $TransaccionProducto->setTipo($tipo);
        $TransaccionProducto->setEstadoProducto($estado_producto);
        $TransaccionProducto->setMandante($Usuario->mandante);
        $TransaccionProducto->setFinalId(0);
        $TransaccionProducto->setExternoId(0);
        $transproductoId = $TransaccionProductoMySqlDAO->insert($TransaccionProducto);

        $DescripcionVenta = "Solicitud de deposito";

        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);

        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
        $credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $API_KEY = $credentials->API_KEY;
        $ID_COMERCIO = $credentials->ID_COMERCIO;
        $ID_CUENTA = $credentials->ID_CUENTA;

        $signature = md5($API_KEY . "~" . $ID_COMERCIO . "~" . $transproductoId . "~" . $valorTax . "~" . $Usuario->moneda);

        $data = array();
        $data['merchantId'] = $ID_COMERCIO;
        $data['accountId'] = $ID_CUENTA;
        $data['description'] = $DescripcionVenta;
        $data['referenceCode'] = $transproductoId;
        $data['amount'] = $valorTax;
        $data['tax'] = 0;
        $data['taxReturnBase'] = 0;
        $data['currency'] = $Usuario->moneda;
        $data['signature'] = $signature;
        if ($ConfigurationEnvironment->isDevelopment()) {
            $data['test'] = 1;
        } else {
            $data['test'] = 0;
        }
        $data['buyerEmail'] = $Registro->email;
        $data['documentId'] = $Registro->cedula;
        $data['responseUrl'] = $Mandante->baseUrl . "gestion/deposito";
        $data['confirmationUrl'] = $this->callback_url;
        $data['billingCountry'] = $Pais->paisId;
        $data['billingAddress'] = $Registro->direccion;
        $data['billingCity'] = $Registro->ciudad;
        $data['zipCode'] = $Registro->codigoPostal;
        $data['payerEmail'] = $Registro->email;
        $data['payerPhone'] = $Registro->celular;
        $data['payerOfficePhone'] = $Registro->celular;
        $data['payerFullName'] = $Registro->nombre;
        $data['payerDocument'] = $Registro->cedula;
        $data['payerDocumentType'] = $Registro->tipoDoc;


        syslog(LOG_WARNING, "PAYU DATA: " . json_encode($data));

        $Result = json_encode($data);

        if ($Result != "") {
            $TransprodLog = new TransprodLog();
            $TransprodLog->setTransproductoId($transproductoId);
            $TransprodLog->setEstado('E');
            $TransprodLog->setTipoGenera('A');
            $TransprodLog->setComentario('Envio Solicitud de deposito');
            $TransprodLog->setTValue(json_encode($Result));
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

            $data_ = array();
            $data_["success"] = true;
            $data_["isPayu"] = true;
            $data_["dataPayU"] = $data;
        }
        return json_decode(json_encode($data_));
    }

}


