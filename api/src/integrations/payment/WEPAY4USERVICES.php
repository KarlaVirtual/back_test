<?php

/**
 * Clase para la integración con el servicio de pagos WEPAY4USERVICES.
 *
 * Este archivo contiene la implementación de la clase `WEPAY4USERVICES`, que permite
 * gestionar solicitudes de pago y realizar conexiones con el proveedor de servicios
 * de pago WEPAY4USERVICES.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-25
 */

namespace Backend\Integrations\payment;

use Exception;
use \CurlWrapper;
use Backend\dto\Pais;
use Backend\dto\Usuario;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\Proveedor;
use Backend\dto\Clasificador;
use Backend\dto\TransprodLog;
use Backend\dto\MandanteDetalle;
use Backend\dto\UsuarioMandante;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransproductoDetalle;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransproductoDetalleMySqlDAO;

/**
 * Clase principal para la integración con WEPAY4USERVICES.
 */
class WEPAY4USERVICES
{
    /**
     * Constructor de la clase.
     *
     * Inicializa el entorno de configuración para determinar si se está
     * ejecutando en un entorno de desarrollo o producción.
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
     * Este método genera una solicitud de pago para un usuario y un producto
     * específicos, calculando impuestos y configurando los datos necesarios
     * para la transacción.
     *
     * @param Usuario  $Usuario    Objeto que representa al usuario.
     * @param Producto $Producto   Objeto que representa al producto.
     * @param float    $valor      Valor de la transacción.
     * @param string   $urlSuccess URL de redirección en caso de éxito.
     * @param string   $urlFailed  URL de redirección en caso de fallo.
     *
     * @return object Respuesta de la solicitud de pago.
     */
    public function createRequestPayment(Usuario $Usuario, Producto $Producto, $valor, $urlSuccess, $urlFailed)
    {
        $Registro = new Registro("", $Usuario->usuarioId);

        $dataR = array();
        $dataR["success"] = false;
        $dataR["error"] = 1;

        $estado = 'A';
        $estado_producto = 'E';
        $tipo = 'T';

        $pais = $Usuario->paisId;
        $usuario_id = $Usuario->usuarioId;
        $cedula = $Registro->cedula;
        $email = $Usuario->login;
        $valor = $valor;
        $producto_id = $Producto->productoId;
        $moneda = $Usuario->moneda;
        $mandante = $Usuario->mandante;
        $descripcion = "Deposito";
        $iso = "";
        $celular = $Usuario->celular;
        $tipoDoc = $Registro->tipoDoc;

        $Pais = new Pais($pais);

        //Convertir el iso del pais
        switch ($Pais->iso) {
            case 'PE':
                $iso = "PER";
                break;
            case 'EC':
                $iso = "ECU";
                break;
            case 'SV':
                $iso = "SLV";
                break;
            case 'MX':
                $iso = "MEX";
                break;
            case 'GT':
                $iso = "GTM";
                break;
            case 'BR':
                $iso = "BRA";
                break;
        }

        //convertir el tipo de documento a los requeridos por el proveedor
        switch ($tipoDoc) {
            case 'E':
                $tipoDoc = "C.EXT";
                break;
            case 'P':
                $tipoDoc = "PAS";
                break;
            default:
                $tipoDoc = "DNI";
                break;
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

        $data = array();
        $data['MerchantSalesID'] = $transproductoId;
        $data['Detalle'] = $descripcion;
        $data['Amount'] = $valorTax;
        $data['CountryCode'] = $iso;
        $data['CurrencyCode'] = $moneda;
        $data['CreationDate'] = date("Y-m-d H:i:s");
        $data['TimeExpired'] = 60;
        $data['OkURL'] = $urlSuccess;
        $data['ErrorURL'] = $urlFailed;
        $data['Channel'] = "Online, cash";
        $data['Customer'] = [
            "FirstName" => $Registro->getNombre1(),
            "LastName" => $Registro->getApellido1(),
            "DocNumber" => $cedula,
            "DocType" => $tipoDoc,
            "CountryCode" => $iso,
            "Email" => $email,
            "Mobile" => $celular
        ];

        if ($Producto->externoId == 'QR') {
            $data['CustomCheckout'] = true;
        }

        $TransproductoDetalle = new TransproductoDetalle();
        $TransproductoDetalle->transproductoId = $transproductoId;
        $TransproductoDetalle->tValue = json_encode(($data));

        $TransproductoDetalleMySqlDAO = new TransproductoDetalleMySqlDAO($Transaction);
        $TransproductoDetalleMySqlDAO->insert($TransproductoDetalle);

        $Proveedor = new Proveedor("", "WEPAY4UPM");
        $Producto = new Producto($producto_id, "", $Proveedor->getProveedorId());
        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);

        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
        $credentials = json_decode($SubproveedorMandantePais->getCredentials());
        $USERNAME = $credentials->USERNAME;
        $URL = $credentials->URL;

        $Result = $this->connection($URL, $USERNAME, $data, "payment_order");

        syslog(LOG_WARNING, "WEPAY4U DATA: " . $Usuario->usuarioId . json_encode($data) . " RESPONSE: " . $Result);

        $response = json_decode($Result);

        if ($Result != '' && $response->PublicID != '') {
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

            $dataR = array();
            $dataR["success"] = true;
            if ($Producto->externoId == 'QR') {

                // Buscar dentro de un array de objetos APPLICATIONS.
                foreach ($response->InstructionsPaymemt as $item) {
                    if (isset($item->APPLICATIONS)) {
                        $applications = $item->APPLICATIONS;
                        break;
                    }
                }

                $formattedText = '';
                // Recorrer cada instrucción
                foreach ($applications->yape->instructions as $instruction) {
                    $formattedText .= $instruction->step . '. ' . $instruction->description . "\n\n";
                }

                // Eliminar el último salto de línea
                $formattedText = trim($Producto->descripcion . "\n\n" . $formattedText);

                // Convertir saltos de línea en <br> para HTML
                $formattedText = nl2br($formattedText);

                $dataR["amount"] = $valorTax;
                $dataR["dataText"] = $formattedText;
                $dataR["dataImg"] = $applications->yape->qrCode;
            } else {
                $dataR["url"] = $response->UrlRedirect;
            }
        }

        return json_decode(json_encode($dataR));
    }

    /**
     * Realiza una conexión HTTP con el proveedor de servicios de pago.
     *
     * Este método utiliza cURL para enviar datos al proveedor de servicios
     * de pago y obtener una respuesta.
     *
     * @param string $URL      URL base del proveedor.
     * @param string $USERNAME Nombre de usuario para la autenticación.
     * @param array  $data     Datos a enviar en la solicitud.
     * @param string $path     Ruta específica del servicio.
     *
     * @return string Respuesta del proveedor.
     */
    public function connection($URL, $USERNAME, $data, $path)
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
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Basic ' . base64_encode($USERNAME . ':'),
            ),
        ));

        $response = $curl->execute();
        return $response;
    }
}
