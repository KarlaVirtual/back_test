<?php

/**
 * Clase COINCAEXSERVICES
 *
 * Esta clase proporciona servicios de integración con la API de Coincaex para la creación de solicitudes de pago.
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
 * Clase COINCAEXSERVICES
 *
 * Esta clase proporciona servicios de integración con la API de Coincaex para la creación de solicitudes de pago.
 * Incluye métodos para configurar el entorno (desarrollo o producción), realizar solicitudes de pago y manejar
 * transacciones relacionadas con productos y usuarios.
 */
class COINCAEXSERVICES
{

    /**
     * URL del servicio de Coincaex.
     *
     * @var string
     */
    private $URL = '';

    /**
     * URL del servicio de Coincaex en modo desarrollo.
     *
     * @var string
     */
    private $URLDEV = 'https://coincaex.com/API/CreateInvoice';

    /**
     * URL del servicio de Coincaex en modo producción.
     *
     * @var string
     */
    private $URLPROD = 'https://coincaex.com/API/CreateInvoice';

    /**
     * URL de callback para la confirmación de pagos.
     *
     * @var string
     */
    private $callback_url = "";

    /**
     * URL de callback para la confirmación de pagos en modo desarrollo.
     *
     * @var string
     */
    private $callback_urlDEV = "https://apidev.virtualsoft.tech/integrations/payment/coincaex/confirm/";

    /**
     * URL de callback para la confirmación de pagos en modo producción.
     *
     * @var string
     */
    private $callback_urlPROD = "https://integrations.virtualsoft.tech/payment/coincaex/confirm/";

    /**
     * Tipo de transacción.
     *
     * @var string
     */
    private $tipo = "";

    /**
     * ID del usuario.
     *
     * @var integer
     */
    private $Key = '';

    /**
     * Clave de desarrollo para la API de Coincaex.
     *
     * @var string
     */
    private $KeyDev = 'OwMYDT9C6YWC90NWI2YmM5MDEwYTczYm3TOCGDS5T';

    /**
     * Clave de producción para la API de Coincaex.
     *
     * @var string
     */
    private $KeyProd = 'VdTFKA9J6FDJ90UDP2FtT5TKLdFAjgFt3AVJNKZ5A';

    /**
     * ID de la tienda.
     *
     * @var string
     */
    private $StoreId = '';

    /**
     * ID de la tienda en modo desarrollo.
     *
     * @var string
     */
    private $StoreIdDev = 'AY92w5bKDxmCqxvtt1zpf8Esg35ik3Z8yfV1GUzifVTJ';

    /**
     * ID de la tienda en modo producción.
     *
     * @var string
     */
    private $StoreIdProd = 'BHv1VHZBNvqkDp2E9a7XNp66HMsJZEKSLguRZnY48hys';

    /**
     * Constructor de la clase COINCAEXSERVICES.
     *
     * Inicializa la URL, clave y ID de tienda según el entorno de desarrollo o producción.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->URL = $this->URLDEV;
            $this->Key = $this->KeyDev;
            $this->StoreId = $this->StoreIdDev;
            $this->callback_url = $this->callback_urlDEV;
        } else {
            $this->URL = $this->URLPROD;
            $this->Key = $this->KeyProd;
            $this->StoreId = $this->StoreIdProd;
            $this->callback_url = $this->callback_urlPROD;
        }
    }

    /**
     * Crea una solicitud de pago para un usuario y un producto específicos.
     *
     * Este metodo realiza los siguientes pasos:
     * - Calcula el impuesto sobre el valor proporcionado.
     * - Registra la transacción del producto en la base de datos.
     * - Envía una solicitud a la API de Coincaex para generar un pago.
     * - Maneja la respuesta de la API y actualiza los registros correspondientes.
     * - Genera un código QR para el pago y lo devuelve en la respuesta.
     *
     * @param Usuario  $Usuario    Objeto que representa al usuario que realiza el pago.
     * @param Producto $Producto   Objeto que representa el producto asociado al pago.
     * @param float    $valor      Valor del pago.
     * @param string   $urlSuccess URL a la que se redirige en caso de éxito.
     * @param string   $urlFailed  URL a la que se redirige en caso de fallo.
     *
     * @return object Objeto con los datos de la respuesta, incluyendo el estado y el código QR.
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
        $producto_id = $Producto->productoId;
        $mandante = $Usuario->mandante;
        $moneda = $Usuario->moneda;

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
        $data['StoreId'] = $this->StoreId;
        $data['Price'] = $valorTax;
        $data['Currency'] = $moneda;

        $TransproductoDetalle = new TransproductoDetalle();
        $TransproductoDetalle->transproductoId = $transproductoId;
        $TransproductoDetalle->tValue = json_encode(($data));

        $TransproductoDetalleMySqlDAO = new TransproductoDetalleMySqlDAO($Transaction);
        $TransproductoDetalleMySqlDAO->insert($TransproductoDetalle);

        syslog(LOG_WARNING, "COINCAEX DATA PAYMENT" . json_encode($data));

        $Result = $this->request(json_encode($data));

        syslog(LOG_WARNING, "COINCAEX RESPONSE PAYMENT" . json_encode($Result));

        if ($Result != '') {
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

            $TransaccionProducto->setExternoId($Result->id);
            $TransaccionProductoMySqlDAO->update($TransaccionProducto);
            $Transaction->commit();

            $response = ($Result);

            $textHeader = "Escanee el código QR a continuación directamente en la aplicación de su banco, o en su billetera digital.";
            $data = array();
            $data["success"] = true;
            $data["textHeader"] = $textHeader;
            $cambio = '<br>Cambio: BTC/' . $response->btcPrice;
            if ($Producto->externoId == 'C00') {
                if ($response->qr->BTC != null || $response->qr->BTC != '') {
                    $data["dataText"] = $textHeader . '<br><br>Metodo: BTC' . '<br>Monto: ' . $moneda . '/' . $valorTax . $cambio;
                    $data["dataImg"] = $response->qr->BTC->BIP21;
                } else {
                    if ($response->qr->Lightning != null || $response->qr->Lightning != '') {
                        $data["dataText"] = $textHeader . '<br><br>Metodo: Lightning' . '<br>Monto: ' . $moneda . '/' . $valorTax . $cambio;
                        $data["dataImg"] = $response->qr->Lightning->BOLT11;
                    }
                }
            } else {
                if ($Producto->externoId == 'C01') {
                    if ($response->qr->Lightning != null || $response->qr->Lightning != '') {
                        $data["dataText"] = $textHeader . '<br><br>Metodo: Lightning' . '<br>Monto: ' . $moneda . '/' . $valorTax . $cambio;
                        $data["dataImg"] = $response->qr->Lightning->BOLT11;
                    } else {
                        if ($response->qr->BTC != null || $response->qr->BTC != '') {
                            $data["dataText"] = $textHeader . '<br><br>Metodo: BTC' . '<br>Monto: ' . $moneda . '/' . $valorTax . $cambio;
                            $data["dataImg"] = $response->qr->BTC->BIP21;
                        }
                    }
                }
            }
        }

        return json_decode(json_encode($data));
    }

    /**
     * Realiza una solicitud HTTP POST a la API de Coincaex.
     *
     * Este metodo utiliza cURL para enviar datos en formato JSON a la URL configurada
     * y devuelve la respuesta decodificada como un objeto.
     *
     * @param string $data Datos en formato JSON que se enviarán en la solicitud.
     *
     * @return object|null Respuesta de la API decodificada como un objeto, o null si ocurre un error.
     */
    private function request($data)
    {
        $Cabeceras = [
            "Content-Type: application/json; charset=utf-8",
            "Key: " . $this->Key
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->URL);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $Cabeceras);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
        $output = curl_exec($ch);
        $output = json_decode($output);
        curl_close($ch);
        return $output;
    }
}
