<?php

/**
 * Clase para gestionar los servicios de integración con EZZEPAY.
 *
 * Este archivo contiene la implementación de la clase `EZZEPAYSERVICES`, que permite
 * realizar operaciones relacionadas con pagos a través de la plataforma EZZEPAY.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-23
 */

namespace Backend\Integrations\payment;

use Backend\dto\SubproveedorMandantePais;
use \CurlWrapper;
use Exception;
use Backend\dto\Pais;
use Backend\dto\Usuario;
use Backend\dto\Mandante;
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
 * Clase principal para gestionar los servicios de integración con EZZEPAY.
 *
 * Esta clase contiene métodos para realizar operaciones relacionadas con pagos,
 * incluyendo la creación de solicitudes de pago y la generación de tokens de autenticación.
 */
class EZZEPAYSERVICES
{

    /**
     * Constructor de la clase EZZEPAYSERVICES.
     *
     * Inicializa las propiedades de la clase según el entorno de ejecución
     * (desarrollo o producción). Configura las URLs base, claves secretas,
     * identificadores de cliente y URLs de callback correspondientes.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
        } else {
        }
    }

    /**
     * Crea una solicitud de pago a través de la plataforma EZZEPAY.
     *
     * Este método realiza las siguientes acciones:
     * - Valida y ajusta el CPF del usuario.
     * - Calcula los impuestos aplicables al valor del pago.
     * - Registra la transacción en la base de datos.
     * - Genera un token de autenticación para la solicitud.
     * - Envía la solicitud de pago a la API de EZZEPAY.
     * - Procesa la respuesta de la API y actualiza los registros correspondientes.
     *
     * @param Usuario  $Usuario    Objeto que representa al usuario que realiza el pago.
     * @param Producto $Producto   Objeto que representa el producto asociado al pago.
     * @param float    $valor      Monto del pago.
     * @param string   $urlSuccess URL a la que se redirige en caso de éxito.
     * @param string   $urlFailed  URL a la que se redirige en caso de fallo.
     *
     * @return object Respuesta con los datos del pago, incluyendo el código QR y el estado.
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function createRequestPayment(Usuario $Usuario, Producto $Producto, $valor, $urlSuccess, $urlFailed)
    {
        $Registro = new Registro("", $Usuario->usuarioId);

        $SubproveedorMandantePais = new SubproveedorMandantePais("", $Producto->subproveedorId, $Usuario->mandante, $Usuario->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

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

        //Validación que CPF tenga 11 Digitos, en caso que no agregar ceros al inicio
        $cedula_Valida = str_pad($cedula, "11", "0", STR_PAD_LEFT);

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

        $pathToken = "/v2/oauth/token";
        $path = "/v2/pix/qrcode";

        $Respueta = $this->CreateToken($pathToken, $Credentials);
        $token = $Respueta->access_token;

        $data = array();
        $data['amount'] = number_format($valorTax, 2, '.', '');
        $data['payerQuestion'] = $usuario_id;
        $data['external_id'] = $transproductoId;
        $data['payer'] = [
            "name" => $nombre,
            "document" => $cedula_Valida
        ];

        $encodedData = json_encode($data);

        $TransproductoDetalle = new TransproductoDetalle();
        $TransproductoDetalle->transproductoId = $transproductoId;
        $TransproductoDetalle->tValue = json_encode(($data));

        $TransproductoDetalleMySqlDAO = new TransproductoDetalleMySqlDAO($Transaction);
        $TransproductoDetalleMySqlDAO->insert($TransproductoDetalle);

        syslog(LOG_WARNING, "EZZEPAY DATA PAYMENT" . json_encode($data));

        $Result = $this->conecctionCreatePIX($encodedData, $token, $path, $Credentials->URL);

        syslog(LOG_WARNING, "EZZEPAY RESPONSE PAYMENT" . ($Result));

        $response = json_decode($Result);
        if ($response != '' && $response->status == 'PENDING') {
            $TransaccionProducto->setExternoId($response->transactionId);
            $transproductoId = $TransaccionProductoMySqlDAO->update($TransaccionProducto);

            $TransprodLog = new TransprodLog();
            $TransprodLog->setTransproductoId($transproductoId);
            $TransprodLog->setEstado('E');
            $TransprodLog->setTipoGenera('A');
            $TransprodLog->setComentario('Envio Solicitud de deposito');
            $TransprodLog->setTValue(json_encode($response));
            $TransprodLog->setUsucreaId(0);
            $TransprodLog->setUsumodifId(0);

            $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
            $TransprodLogMySqlDAO->insert($TransprodLog);

            $Transaction->commit();

            $Message = "Digitalize o QR ou copie o código para efetuar o pagamento.";
            $data = array();
            $data["success"] = true;
            $data["dataText"] = $Message . '<br><p style="
            background: -webkit-linear-gradient(90deg,#4bba59 1%,#0e8507 48%,#51c752)!important;
            color: white;
            border: 0;
            box-shadow: none;
            font-size: 17px;
            font-weight: 500;
            border-radius: 5px;
            padding: 10px 32px;
            margin: 26px 5px 0 5px;
            cursor: pointer;" 
            class="confirm" tabindex="1" onclick="copyStringToClipboard(\'' . $response->emvqrcps . '\');">Copiar código</p>';
            $data["dataImg"] = "data:image/png;base64," . $response->base64image;
        }
        return json_decode(json_encode($data));
    }

    /**
     * Genera un token de autenticación para interactuar con la API de EZZEPAY.
     *
     * Este método realiza una solicitud HTTP POST para obtener un token de acceso
     * utilizando las credenciales del cliente (clientId y clientSecret).
     *
     * @param string $pathToken Ruta del endpoint para la generación del token.
     *
     * @return object|null Respuesta decodificada de la API que contiene el token de acceso,
     *                     o null si la solicitud falla.
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

        return json_decode($response);
    }

    /**
     * Realiza una conexión HTTP POST para crear un PIX en la API de EZZEPAY.
     *
     * Este método envía datos codificados en formato JSON a un endpoint específico
     * utilizando un token de autenticación Bearer.
     *
     * @param string $encodedData Datos codificados en formato JSON que se enviarán en la solicitud.
     * @param string $token       Token de autenticación Bearer para la solicitud.
     * @param string $path        Ruta del endpoint de la API donde se enviará la solicitud.
     *
     * @return string Respuesta de la API en formato JSON.
     */
    private function conecctionCreatePIX($encodedData, $token, $path, $URL)
    {
        $curl = new CurlWrapper($URL. $path);

        $curl->setOptionsArray(array(
            CURLOPT_URL => $URL. $path,
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

}
