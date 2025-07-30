<?php

/**
 * Clase para gestionar servicios de integración con Unlimint.
 *
 * Este archivo contiene la implementación de la clase `UNLIMINTSERVICES`,
 * que permite realizar solicitudes de pago y manejar configuraciones
 * específicas para entornos de desarrollo y producción.
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
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\Clasificador;
use Backend\dto\PaisMandante;
use Backend\dto\TransprodLog;
use Backend\dto\MandanteDetalle;
use Backend\dto\UsuarioMandante;
use Backend\dto\TransaccionProducto;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\TransaccionProductoMySqlDAO;

/**
 * Clase UNLIMINTSERVICES
 *
 * Proporciona métodos para realizar solicitudes de pago, obtener tokens de autenticación
 * y manejar configuraciones específicas según el entorno (desarrollo o producción).
 */
class UNLIMINTSERVICES
{

    /**
     * URL para depósitos.
     *
     * @var string
     */
    private $URLDEPOSIT = "";

    /**
     * URL para depósitos en el entorno de desarrollo.
     *
     * @var string
     */
    private $URLDEPOSITDEV = "https://devfrontend.virtualsoft.tech/gangabet/gestion/deposito";

    /**
     * URL para depósitos en el entorno de producción.
     *
     * @var string
     */
    private $URLDEPOSITPROD = "https://gangabet.mx/gestion/deposito";


    /**
     * Constructor de la clase.
     *
     * Inicializa las configuraciones de URL, credenciales y otros parámetros
     * según el entorno (desarrollo o producción).
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->URLDEPOSIT = $this->URLDEPOSITDEV;
        } else {
            $this->URLDEPOSIT = $this->URLDEPOSITPROD;
        }
    }

    /**
     * Crea una solicitud de pago.
     *
     * @param Usuario  $Usuario    Objeto del usuario que realiza el pago.
     * @param Producto $Producto   Objeto del producto asociado al pago.
     * @param float    $valor      Monto del pago.
     * @param string   $urlSuccess URL de redirección en caso de éxito.
     * @param string   $urlFailed  URL de redirección en caso de fallo.
     * @param string   $urlCancel  URL de redirección en caso de cancelación.
     *
     * @return array Respuesta con el estado de la solicitud y la URL de redirección.
     */
    public function createRequestPayment(Usuario $Usuario, Producto $Producto, $valor, $urlSuccess, $urlFailed, $urlCancel)
    {
        $Registro = new Registro("", $Usuario->usuarioId);
        $Pais = new Pais($Usuario->paisId);

        $data_ = array();
        $data_["success"] = false;
        $data_["error"] = 1;

        $estado = 'A';
        $estado_producto = 'E';
        $tipo = 'T';

        $externo = $Producto->externoId;
        $mandante = $Usuario->mandante;
        $pais = $Usuario->paisId;

        $UsuarioMandante = new UsuarioMandante('', $Usuario->usuarioId, $mandante);
        $Mandante = new Mandante($UsuarioMandante->mandante);
        if ($Mandante->baseUrl != '') {
            $this->URLDEPOSIT = $Mandante->baseUrl . "gestion/deposito";
        }

        try {
            $SubproveedorMandantePais = new SubproveedorMandantePais("", $Producto->subproveedorId, $Usuario->mandante, $Usuario->paisId);
            $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

            $URL = $Credentials->URL;

            $Credentials_decode = json_decode(base64_decode($Credentials->CREDENTIALS));

            $resultado = $this->asignarCredenciales($Credentials_decode, $externo, $pais, $mandante);

            $terminal_code = $resultado['terminal_code'];
            $grant_type = $resultado['grant_type'];
            $password = $resultado['password'];
            $p_m = $resultado['p_m'];
        } catch (Exception $e) {
            throw new Exception($e);
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

        $path = '/auth/token';
        $token = $this->getToken($path, $URL, $password, $terminal_code);
        $token = json_decode($token);
        $token = $token->access_token;

        date_default_timezone_set("UTC");
        $date = gmdate("Y-m-d\TH:i:s\Z");
        $mod_date = strtotime($date . "+ 2 days");
        $date_at = date('Y-m-d\TH:i:s', $mod_date);

        date_default_timezone_set('America/Bogota');

        $moneda = $Usuario->moneda;

        if ($moneda == 'GTQ' || $moneda == 'CRC') {
            $PaisMandante = new PaisMandante("", $Usuario->mandante, $Usuario->paisId);
            $valorTax = ($valorTax * $PaisMandante->trmUsd);
            $moneda = 'USD';
        }

        $data = array();
        $data['request'] = [
            "id" => $transproductoId,
            "time" => $date,
        ];
        $data['customer'] = [
            "email" => $Usuario->login,
            "phone" => $Registro->celular,
            "full_name" => $Usuario->nombre . ' ' . $Usuario->getApellido(),
            "locale" => $Usuario->idioma,
        ];
        $data['payment_data'] = [
            "amount" => round($valorTax, 2),
            "currency" => $moneda,
            "expire_at" => $date_at,
        ];
        $data['merchant_order'] = [
            "id" => $transproductoId,
            "description" => 'Deposito',
            "items" => [
                [
                    "count" => 1,
                    "name" => 'Deposit0',
                    "price" => round($valorTax, 2),
                ]
            ],
        ];
        $data['payment_method'] = $p_m;
        $data['return_urls'] = [
            "cancel_url" => $this->URLDEPOSIT,
            "decline_url" => $this->URLDEPOSIT,
            "inprocess_url" => $this->URLDEPOSIT,
            "success_url" => $this->URLDEPOSIT,
        ];

        $path = "/payments";
        $Result = $this->connectionPOST($data, $path, $token, $URL);

        syslog(LOG_WARNING, "UNLIMINT DATA: " . json_encode($data) . " RESPONSE: " . $Result);

        $Result = json_decode($Result);

        if ($Result != "" && $Result->redirect_url) {
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

            $data_ = array();
            $data_["success"] = true;
            $data_["url"] = $Result->redirect_url;
        }
        return json_decode(json_encode($data_));
    }

    /**
     * Asigna las credenciales de acceso según el país y mandante.
     *
     * @param array  $data            Datos de credenciales.
     * @param string $externoId       ID externo para buscar las credenciales.
     * @param int    $paisBuscado     ID del país buscado.
     * @param int    $mandanteBuscado ID del mandante buscado.
     *
     * @return array|bool Array con las credenciales o false si no se encuentra coincidencia.
     */
    public function asignarCredenciales($data, $externoId, $paisBuscado, $mandanteBuscado)
    {
        $data = (array)$data;

        if ($mandanteBuscado == 0 && $paisBuscado != 176) {
            $paisBuscado = 'default';
        } elseif ($mandanteBuscado == 8 && $paisBuscado != 66) {
            $paisBuscado = 'default';
        } elseif ($mandanteBuscado == 18) {
            $paisBuscado = 'default';
        }

        if ( ! isset($data[$externoId])) {
            return false; // No se encontró el externoId
        }

        $elemento = $data[$externoId];

        if (is_object($elemento)) {
            if ($elemento->pais == $paisBuscado && $elemento->mandante == $mandanteBuscado) {
                $terminal_code = $elemento->terminal_code;
                $grant_type = $elemento->grant_type;
                $password = $elemento->password;
                $p_m = $elemento->p_m;
                // Retornar como variables compactadas
                return compact('terminal_code', 'grant_type', 'password', 'p_m');
            }
        }

        if (is_array($elemento)) {
            foreach ($elemento as $obj) {
                if ($obj->pais == $paisBuscado && $obj->mandante == $mandanteBuscado) {
                    $terminal_code = $obj->terminal_code;
                    $grant_type = $obj->grant_type;
                    $password = $obj->password;
                    $p_m = $obj->p_m;
                    // Retornar como variables compactadas
                    return compact('terminal_code', 'grant_type', 'password', 'p_m');
                }
            }
        }

        return false; // No se encontró coincidencia
    }


    /**
     * Realiza una solicitud POST a la API de Unlimint.
     *
     * @param array  $data  Datos a enviar en la solicitud.
     * @param string $path  Ruta del endpoint de la API.
     * @param string $token Token de autenticación.
     *
     * @return string Respuesta de la API.
     */
    public function connectionPOST($data, $path, $token, $URL)
    {
        $data = json_encode($data);

        $url = $URL . $path;

        $curl = new CurlWrapper($url);

        $curl->setOptionsArray(array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json'
            ),
        ));

        $response = $curl->execute();

        return $response;
    }

    /**
     * Obtiene un token de autenticación.
     *
     * @param string $path Ruta del endpoint para obtener el token.
     *
     * @return string Token de autenticación en formato JSON.
     */
    public function getToken($path, $URL, $password, $terminal_code)
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
            CURLOPT_POSTFIELDS => 'grant_type=password&terminal_code=' . $terminal_code . '&password=' . $password,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = $curl->execute();

        return $response;
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
