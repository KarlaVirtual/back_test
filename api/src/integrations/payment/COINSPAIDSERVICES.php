<?php

/**
 * Clase COINSPAIDSERVICES
 *
 * Esta clase proporciona servicios de integración con Coinspaid para la creación de solicitudes de pago.
 * Incluye métodos para configurar el entorno, realizar solicitudes de pago, encriptar datos y establecer conexiones.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @version    1.0.0
 * @since      2025-04-23
 * @author     Desconocido
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
 * Clase COINSPAIDSERVICES
 *
 * Proporciona métodos para la integración con Coinspaid, incluyendo la creación de solicitudes de pago,
 * encriptación de datos y manejo de conexiones.
 */
class COINSPAIDSERVICES
{

    /**
     * Nombre de usuario para la autenticación.
     *
     * @var string
     */
    private $username = "";

    /**
     * Contraseña para la autenticación.
     *
     * @var string
     */
    private $password = "";

    /**
     * Nombre de usuario para el entorno de desarrollo.
     *
     * @var string
     */
    private $usernameDEV = "";

    /**
     * Nombre de usuario para el entorno de producción.
     *
     * @var string
     */
    private $usernamePROD = "";

    /**
     * URL base para las solicitudes.
     *
     * @var string
     */
    private $URL = "";

    /**
     * URL para las solicitudes en el entorno de desarrollo.
     *
     * @var string
     */
    private $URLDEV = 'https://app.sandbox.cryptoprocessing.com/api/v2/invoices/create';

    /**
     * URL para las solicitudes en el entorno de producción.
     *
     * @var string
     */
    private $URLPROD = 'https://app.cryptoprocessing.com/api/v2/invoices/create';

    /**
     * URL de callback para las notificaciones.
     *
     * @var string
     */
    private $callback_url = "";

    /**
     * URL de callback para el entorno de desarrollo.
     *
     * @var string
     */
    private $callback_urlDEV = "https://apidev.virtualsoft.tech/integrations/payment/coinspaid/confirm/";

    /**
     * URL de callback para el entorno de producción.
     *
     * @var string
     */
    private $callback_urlPROD = "https://integrations.virtualsoft.tech/payment/coinspaid/confirm/";

    /**
     * Clave privada para la autenticación.
     *
     * @var string
     */
    private $KeyPRIVATE = "";

    /**
     * Clave privada para el entorno de desarrollo.
     *
     * @var string
     */
    private $KeyPRIVATEDEV = "QMl8igj8VjgGbNsTjj7vCbPw752MOaa6EOvKsBNqtlVP4eL1SFLwNOcqv4FgpLOE";

    /**
     * Clave privada para el entorno de producción.
     *
     * @var string
     */
    private $KeyPRIVATEPROD = "ppsxHq9pwr6vbBDek7WbGbwPc2YWkPQhI806na3LLD0EkUpX7OgjmQEyTIRVjhBs";

    /**
     * Clave pública para la autenticación.
     *
     * @var string
     */
    private $KeyPUBLIC = "";

    /**
     * Clave pública para el entorno de desarrollo.
     *
     * @var string
     */
    private $KeyPUBLICDEV = "nvAXNtbiYs8nNjjMpg1t6wxRw5C4b8pL";

    /**
     * Clave pública para el entorno de producción.
     *
     * @var string
     */
    private $KeyPUBLICPROD = "x41doCzyELi4BOOH4nbKpb13GrsyHFKL";

    /**
     * Token de autenticación generado.
     *
     * @var string
     */
    private $Auth = "";

    /**
     * ID del cliente.
     *
     * @var string
     */
    private $client_id = "";

    /**
     * ID del cliente para el entorno de desarrollo.
     *
     * @var string
     */
    private $client_idDEV = "";

    /**
     * ID del cliente para el entorno de producción.
     *
     * @var string
     */
    private $client_idPROD = "";

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
    private $URLDEPOSITDEV = "https://devfrontend.virtualsoft.tech/doradobetv4/gestion/deposito";

    /**
     * URL para depósitos en el entorno de producción.
     *
     * @var string
     */
    private $URLDEPOSITPROD = "https://doradobet.com/gestion/deposito";

    /**
     * Constructor de la clase COINSPAIDSERVICES.
     *
     * Configura las propiedades de la clase según el entorno (desarrollo o producción).
     * Utiliza la clase ConfigurationEnvironment para determinar el entorno actual.
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
     * Crea una solicitud de pago para un usuario y producto específicos.
     *
     * Este método genera una transacción de producto, calcula impuestos,
     * y envía una solicitud de pago a través de la integración con Coinspaid.
     *
     * @param Usuario  $Usuario    Objeto que contiene la información del usuario.
     * @param Producto $Producto   Objeto que contiene la información del producto.
     * @param float    $valor      Monto de la transacción.
     * @param string   $urlSuccess URL a la que se redirige en caso de éxito.
     * @param string   $urlFailed  URL a la que se redirige en caso de fallo.
     *
     * @return object Objeto con el resultado de la operación, incluyendo el estado y la URL de pago.
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
        $descripcion = "Solicitud de deposito";

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

        if ($Usuario->mandante == 9) {
            $this->KeyPRIVATE = 'D6EDlnGemoSrzJmgEHiiUtnjBscho4XsKJH6G65I3Ql4CjxM2iYCEMn3vDniJoWb';
            $this->KeyPUBLIC = 'ea736Mo5NgFZB57dcodt3AAlwUeoBVdA';
        }
        if ($Usuario->mandante == 14) {
            $this->KeyPRIVATE = '1ljaUIqBt68A9rNX5vfKZlIoTf0eOZHTBhXMUTQU5sQmC0ZBRLRqiX0j3vOQrkkH';
            $this->KeyPUBLIC = 'PhwIzAGt59FcrjE2Lhro5rjdSTSoxZJL';
        }


        $dataT = array();
        $dataT['timer'] = 0;
        $dataT['client_id'] = $usuario_id;
        $dataT['title'] = $descripcion;
        $dataT['currency'] = $moneda;
        $dataT['amount'] = $valorTax;
        $dataT['foreign_id'] = $transproductoId;
        $dataT['url_success'] = $this->URLDEPOSIT;
        $dataT['url_failed'] = $urlFailed;
        $dataT['url_back'] = $this->URLDEPOSIT;
        $dataT['email_user'] = $email;

        syslog(LOG_WARNING, "COINSPAID DATA: " . json_encode($data));

        $dataT = json_encode($dataT);
        $data_enc = $this->encrypta($dataT);

        $Result = $this->connection($dataT, $data_enc);

        syslog(LOG_WARNING, "COINSPAID RESPONSE: " . ($Result));

        if ($Result != '') {
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
            $response = json_decode($Result);

            if ($response->data != null && $response->data->id != '') {
                $TransaccionProducto->setExternoId($response->data->id);
                $TransaccionProductoMySqlDAO->update($TransaccionProducto);
            }


            $Transaction->commit();


            $data = array();
            $data["success"] = true;
            $data["url"] = $response->data->url;
        }

        return json_decode(json_encode($data));
    }

    /**
     * Genera un hash HMAC utilizando el algoritmo SHA-512.
     *
     * Este método toma los datos proporcionados y los encripta utilizando
     * la clave privada configurada en la clase. El resultado es un hash
     * que se utiliza para la autenticación de las solicitudes.
     *
     * @param string $data Los datos a encriptar.
     *
     * @return string El hash HMAC generado.
     */
    public function encrypta($data)
    {
        $enc = $this->Auth = hash_hmac('sha512', $data, $this->KeyPRIVATE);
        return $enc;
    }

    /**
     * Realiza una conexión HTTP utilizando cURL para enviar datos a la URL configurada.
     *
     * Este método envía una solicitud POST con los datos proporcionados y las cabeceras
     * necesarias para la autenticación. Devuelve la respuesta del servidor.
     *
     * @param string $data     Los datos en formato JSON que se enviarán en la solicitud.
     * @param string $data_enc El hash HMAC generado para la autenticación de la solicitud.
     *
     * @return string La respuesta del servidor en formato JSON.
     */
    public function connection($data, $data_enc)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->URL,
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
