<?php

namespace Backend\Integrations\payment;

// Importación de clases y DTOs utilizados a lo largo del servicio
use Backend\dto\SubproveedorMandantePais;
use Backend\dto\UsuarioMandante;
use DateTime;
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
use InvalidArgumentException;
use RuntimeException;

/**
 * Clase 'SPOTUPAYSERVICES'
 *
 * Esta clase provee funciones para la integración con la pasarela de pagos SpotUpay.
 * Permite la creación de solicitudes de pago, firma de datos y validación de las mismas.
 *
 * Ejemplo de uso:
 * $SPOTUPAYSERVICES = new SPOTUPAYSERVICES();
 *
 * @package ninguno
 * @author Karla Ramirez<karla.ramirez@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @date: 05.05.25
 */
class SPOTUPAYSERVICES
{
    /**
     * Método constructor
     *
     * Inicializa el entorno de configuración.
     *
     * @return void
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();
    }

    /**
     * Método createRequestPayment
     *
     * Crea una solicitud de pago para un usuario y producto dado.
     * Realiza los cálculos de impuestos, firma de datos y envía la solicitud a la pasarela.
     *
     * @param Usuario $Usuario Información del usuario que realiza el pago
     * @param Producto $Producto Información del producto asociado al pago
     * @param integer $valor Monto del depósito solicitado
     * @param string $urlSuccess URL de redirección si el proceso es exitoso
     * @param string $urlFailed URL de redirección si el proceso falla
     *
     * @return object Objeto con el estado de la solicitud y la URL de pago
     * /
     */
    public function createRequestPayment(Usuario $Usuario, Producto $Producto, $valor, $urlSuccess, $urlFailed)
    {
        $Registro = new Registro("", $Usuario->usuarioId);

        $data = array();
        $data["success"] = false;
        $data["error"] = 1;

        // Estados iniciales
        $estado = 'A';
        $estado_producto = 'E';
        $tipo = 'T';

        // Extracción de datos
        $usuario_id = $Usuario->usuarioId;
        $cedula = $Registro->cedula;
        $nombre = $Usuario->nombre;
        $email = $Usuario->login;
        $producto_id = $Producto->productoId;
        $mandante = $Usuario->mandante;

        // Inicia transacción
        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
        $Transaction = $TransaccionProductoMySqlDAO->getTransaction();

        // Cálculo de impuestos
        try {
            $Clasificador = new Clasificador("", "TAXDEPOSIT");
            $MandanteDetalle = new MandanteDetalle("", $Usuario->mandante, $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
            $taxedValue = $MandanteDetalle->valor;
        } catch (Exception $e) {
            $taxedValue = 0;
        }

        $totalTax = $valor * ($taxedValue / 100);
        $valorTax = $valor * (1 + $taxedValue / 100);

        // Registro de la transacción
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

        // Obtención de credenciales
        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
        $credentials = json_decode($SubproveedorMandantePais->getCredentials());

        // Datos adicionales del usuario
        $time = time();
        $timeStamp = $time + (5 * 60);
        $uuid = md5(uniqid(rand(), true));
        $Pais = new Pais($Usuario->paisId);
        $tipoDoc = $Registro->tipoDoc;
        $Iso = $Pais->iso;

        // Conversión del tipo de documento
        switch ($tipoDoc) {
            case 'C':
                $tipoDoc = 'CI';
                break;
            case 'E':
                $tipoDoc = 'CC';
                break;
            case 'P':
                $tipoDoc = 'PP';
                break;
        }

        // Ajuste del ISO para Venezuela
        if ($Iso == 'VE' || $Iso == 'VX') {
            $Iso = 'VEN';
        }

        // Datos que se enviarán a la pasarela
        $data = [
            'trans_id' => $uuid,
            'channel' => 'card',
            'currency' => $Usuario->moneda,
            'balance_amount' => $Usuario->getBalance(),
            'minimum_deposit' => $valorTax,
            'url_ok' => $urlSuccess,
            'url_error' => $urlFailed,
            'expiration_time' => $timeStamp,
            'customer_code' => $transproductoId,
            'user_name' => $Registro->email,
            'customer_name' => $Registro->nombre1,
            'customer_last_name' => $Registro->apellido1,
            'customer_email' => $Registro->email,
            'customer_limit' => $valorTax,
            'document_country' => $Iso,
            'document_type' => $tipoDoc,
            'document_value' => $Registro->cedula
        ];

        // Estructura final de datos para el request
        $Data_json = [
            'trans_id' => $uuid,
            'event_type' => $credentials->EVENT_TYPE,
            'created' => $timeStamp,
            'data' => base64_encode(json_encode($data)),
        ];

        $encodedData = json_encode($Data_json);

        // Registro del detalle de la transacción
        $TransproductoDetalle = new TransproductoDetalle();
        $TransproductoDetalle->transproductoId = $transproductoId;
        $TransproductoDetalle->tValue = json_encode($data);

        $TransproductoDetalleMySqlDAO = new TransproductoDetalleMySqlDAO($Transaction);
        $TransproductoDetalleMySqlDAO->insert($TransproductoDetalle);

        syslog(LOG_WARNING, "SPOTUPAY DATA PAYMENT" . $encodedData);

        // Firma de la solicitud
        $signature = $this->generateSignature($encodedData, $credentials->SECRET_KEY);

        // Validación de la firma
        $ValidateSign = $this->validateSignature($encodedData, $credentials->SECRET_KEY, $signature);
        if ($ValidateSign == false) {
            die("invalidate Signature");
        }

        // Envío de la solicitud a la pasarela
        $Result = $this->deposit($encodedData, $credentials->URL, $credentials->AUTH_TOKEN, $credentials->TERMINAL_ID, $credentials->ENVIROMENT_TYPE, $signature);

        syslog(LOG_WARNING, "SPOTUPAY RESPONSE PAYMENT" . $Result);

        // Procesamiento de respuesta
        $response = json_decode($Result);
        if ($response != '' && $response->status == '202') {

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

            // Decodificación del URL de redirección
            $url = base64_decode($response->details);

            // Confirmación de la transacción
            $Transaction->commit();
            $result = array();
            $result["success"] = true;
            $result["url"] = $url;
        }

        return json_decode(json_encode($result));
    }

    /**
     * Método deposit
     *
     * Envía una solicitud HTTP POST a la pasarela de pagos con los encabezados necesarios.
     *
     * @param string $Data Cuerpo JSON de la solicitud
     * @param string $Url URL del endpoint de la pasarela
     * @param string $Auth_Token Token de autenticación
     * @param string $TerminalId Identificador del terminal
     * @param string $Type Tipo de entorno
     * @param string $signature Firma generada para la solicitud
     *
     * @return string Respuesta de la pasarela
     */
    private function deposit($Data, $Url, $Auth_Token, $TerminalId, $Type, $signature)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $Url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $Data,
            CURLOPT_HTTPHEADER => array(
                'X-Terminal-Id: ' . $TerminalId,
                'X-Signature: ' . $signature,
                'X-Auth-Token: ' . $Auth_Token,
                'X-Environment-Type: ' . $Type,
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    /**
     * Método generateSignature
     *
     * Genera una firma HMAC-SHA512 sobre datos JSON ordenados y codificados.
     *
     * @param string $jsonData Cadena JSON a firmar
     * @param string $endpointSecret Clave secreta para firmar
     *
     * @return string Firma codificada en base64
     */
    private function generateSignature($jsonData, $endpointSecret)
    {
        try {
            if (!is_string($jsonData)) {
                throw new Exception("jsonData must be a string containing valid JSON");
            }

            $parsedData = json_decode($jsonData, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception("JSON parse error: " . json_last_error_msg());
            }

            $sortedData = $this->deepSortArray($parsedData);
            $sortedJson = json_encode($sortedData);

            $payload = $sortedJson;

            $hash = hash_hmac('sha512', $payload, $endpointSecret, true);

            return base64_encode($hash);
        } catch (Exception $e) {
            error_log("[SignatureService] Unexpected error in generateSignature: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Método deepSortArray
     *
     * Ordena recursivamente un arreglo por sus claves.
     *
     * @param array $data Datos a ordenar
     * @return array Arreglo ordenado
     */
    private function deepSortArray($data)
    {
        if (is_array($data)) {
            ksort($data);
            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    $data[$key] = self::deepSortArray($value);
                }
            }
        }
        return $data;
    }

    /**
     * Método validateSignature
     *
     * Valida si una firma proporcionada es igual a la generada.
     *
     * @param string $jsonData Datos JSON
     * @param string $endpointSecret Clave secreta
     * @param string $providedSignature Firma a validar
     *
     * @return bool Resultado de la validación
     */
    private function validateSignature($jsonData, $endpointSecret, $providedSignature)
    {
        try {
            $computedSignature = self::generateSignature($jsonData, $endpointSecret);
            if ($computedSignature === null) {
                return false;
            }

            return hash_equals($computedSignature, $providedSignature);
        } catch (Exception $e) {
            error_log("[SignatureService] Unexpected error in validateSignature: " . $e->getMessage());
            return false;
        }
    }
}
