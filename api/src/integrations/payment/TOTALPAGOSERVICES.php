<?php
/**
 * Clase para gestionar servicios de integración con TotalPago.
 *
 * Este archivo contiene la implementación de la clase `TOTALPAGOSERVICES`,
 * que permite realizar operaciones relacionadas con pagos, depósitos y
 * consultas bancarias a través de la pasarela de TotalPago.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-25
 */

namespace Backend\integrations\payment;

use Backend\dto\SubproveedorMandantePais;
use \CurlWrapper;
use Exception;
use Backend\dto\Pais;
use Backend\dto\Banco;
use Backend\dto\Usuario;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\CuentaCobro;
use Backend\dto\Clasificador;
use Backend\dto\Subproveedor;
use Backend\dto\TransprodLog;
use Backend\dto\UsuarioBanco;
use Backend\dto\MandanteDetalle;
use Backend\dto\ProductoMandante;
use Backend\dto\TransaccionProducto;
use Backend\dto\SubproveedorMandante;
use Backend\dto\TransproductoDetalle;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransproductoDetalleMySqlDAO;

/**
 * Clase `TOTALPAGOSERVICES` para manejar la integración con TotalPago.
 */
class TOTALPAGOSERVICES
{

    /**
     * Ruta del endpoint de la API.
     *
     * @var string
     */
    private $path = "";

    /**
     * Constructor de la clase.
     *
     * Inicializa las variables de entorno dependiendo de si el entorno es
     * de desarrollo o producción.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
        } else {
        }
    }

    /**
     * Crea una solicitud de pago.
     *
     * @param Usuario  $Usuario   Objeto del usuario que realiza el pago.
     * @param Producto $Producto  Objeto del producto asociado al pago.
     * @param float    $valor     Monto del pago.
     * @param integer  $bankId    ID del banco para la transferencia.
     * @param string   $date      Fecha de la transferencia.
     * @param string   $reference Referencia de la transacción.
     *
     * @return string Respuesta en formato JSON con el resultado de la operación.
     */
    public function createRequestPayment(Usuario $Usuario, Producto $Producto, $valor, $bankId, $date, $reference)
    {
        $Registro = new Registro("", $Usuario->usuarioId);
        $Pais = new Pais($Usuario->paisId);

        $data_ = array();
        $data_["success"] = false;
        $data_["Message"] = "Error de deposito";
        $data_["code"] = "";

        $estado = 'A';
        $estado_producto = 'E';
        $tipo = 'T';

        $externo = $Producto->externoId;

        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
        $Transaction = $TransaccionProductoMySqlDAO->getTransaction();

        $Credentials = $this->Credentials($Producto, $Usuario);

        $URL = $Credentials->URL;
        $TOKEN = $Credentials->TOKEN;
        $KEY = $Credentials->KEY;

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
        $TransaccionProducto->setExternoId($reference);
        $transproductoId = $TransaccionProductoMySqlDAO->insert($TransaccionProducto);

        $tipoDocumento = $Registro->tipoDoc;
        switch ($tipoDocumento) {
            case "E":
                $tipoDocumento = "V";
                break;
            case "P":
                $tipoDocumento = "E";
                break;
            case "C":
                $tipoDocumento = "J";
                break;
            default:
                $tipoDocumento = "V";
                break;
        }

        $fechaConvertida = date("d/m/Y", strtotime($date));

        $Cell = $Registro->celular;
        if ($Cell == "") {
            $Cell = '1230000000';
        }

        $data = array();
        $data['idusr'] = $KEY;
        $data['token'] = $TOKEN;
        $data['idPago'] = $transproductoId;
        $data['mtPago'] = strval($valorTax);
        $data['descPago'] = 'Solicitud de Deposito';
        $data['nuReferenciaTransf'] = $reference;
        $data['idbancoTransf'] = $bankId;
        $data['fechaTransferencia'] = $fechaConvertida;
        $data['nacCiTitularCuentaTransferencia'] = $tipoDocumento;
        $data['numeroCiTitularCuentaTransferencia'] = $Registro->cedula;
        $data['nmTitularCuentaTransferencia'] = $Registro->nombre;
        $data['telfTitularCuentaTransferencia'] = $Cell;
        $data['correoTitularCuentaTransferencia'] = $Registro->email;

        syslog(LOG_WARNING, "TOTALPAGO DATA: " . json_encode($data, JSON_UNESCAPED_SLASHES));

        $this->path = "/WM_fcn_RegistrarPagoTransf";

        $Result = $this->connectionPOST($data, $this->path, $URL);

        syslog(LOG_WARNING, "TOTALPAGO RESPONSE: " . $Result);

        $Result = json_decode($Result);

        if ($Result != "" && $Result->Mensaje == 'PAGO_INGRESADO') {
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
            $data_["Message"] = "";
            $data_["code"] = 'OK';
        }

        return json_encode($data_);
    }


    /**
     * Verifica el estado de un depósito.
     *
     * @param integer $idPago   ID del pago a verificar.
     * @param string  $mandante Identificador del mandante.
     *
     * @return string Respuesta en formato JSON con el estado del depósito.
     */
    public function verifyDeposit($idPago, $mandante, $Usuario, $Producto)
    {
        $Credentials = $this->Credentials($Producto, $Usuario);

        $URL = $Credentials->URL;
        $KEY = $Credentials->KEY;
        $TOKEN = $Credentials->TOKEN;

        $data = array();
        $data['idusr'] = $KEY;
        $data['token'] = $TOKEN;
        $data['idPago'] = $idPago;

        $this->path = "/WM_fcn_VerificacionPagoPost";

        $response = $this->connectionPOST($data, $this->path, $URL);
        $response = json_decode($response);

        $data = array();
        $data["success"] = true;
        $data["Message"] = "";
        $data["code"] = $response;

        return json_encode($data);
    }

    /**
     * Obtiene la lista de bancos asociados a un producto.
     *
     * Este método realiza una solicitud POST a la API de TotalPago para obtener
     * las cuentas bancarias disponibles asociadas al producto especificado.
     *
     * @param Producto $Producto Objeto del producto para el cual se obtendrán los bancos.
     *
     * @return string Respuesta en formato JSON con la lista de bancos o un mensaje de error.
     */
    public function getBank($Producto, $Usuario)
    {
        $Credentials = $this->Credentials($Producto, $Usuario);

        $URL = $Credentials->URL;
        $KEY = $Credentials->KEY;
        $TOKEN = $Credentials->TOKEN;

        $data = array();
        $data['idusr'] = $KEY;
        $data['token'] = $TOKEN;

        $this->path = "/WM_fcn_CuentasBancariasPost";

        $response = $this->connectionPOST($data, $this->path,$URL);
        $response = json_decode($response);
        $isErrror = $this->buscarError($response);

        if ($isErrror) {
            $data = array();
            $data["success"] = false;
            $data["Message"] = "Error en los Bancos";
            $data["code"] = "";
        } else {
            $data = array();
            $data["success"] = true;
            $data["Message"] = "";
            $data["code"] = $response;
        }

        return json_encode($data);
    }

    /**
     * Busca si existe un error en los datos proporcionados.
     *
     * Este método recorre un conjunto de datos y verifica si alguno de los valores
     * contiene la palabra "Error". Si encuentra un error, devuelve `true`.
     *
     * @param array $data Datos a analizar en busca de errores.
     *
     * @return boolean `true` si se encuentra un error, `false` en caso contrario.
     */
    public function buscarError($data)
    {
        foreach ($data as $item) {
            if (is_object($item)) {
                foreach ($item as $key => $value) {
                    if (is_string($value) && stripos($value, "Error") !== false) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    /**
     * Realiza una solicitud POST a la API de TotalPago.
     *
     * @param array  $data Datos a enviar en la solicitud.
     * @param string $path Ruta del endpoint de la API.
     *
     * @return string Respuesta de la API.
     */
    public function connectionPOST($data, $path, $URL)
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
            CURLOPT_POSTFIELDS => json_encode($data, JSON_UNESCAPED_SLASHES),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Cookie: ASP.NET_SessionId=1zhjs241gzoop55pwubn1w22'
            ),
        ));

        $response = $curl->execute();

        return $response;
    }

    public function Credentials($Producto, $Usuario){

        $SubproveedorMandantePais = new SubproveedorMandantePais("", $Producto->subproveedorId, $Usuario->mandante, $Usuario->paisId);
        return json_decode($SubproveedorMandantePais->getCredentials());
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

}


