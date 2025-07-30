<?php

/**
 * Clase para gestionar servicios de integración con AstroPay Card.
 *
 * Este archivo contiene la implementación de la clase `ASTROPAYCARDSERVICES`.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-23
 */

namespace Backend\Integrations\payment;

use Backend\dto\Banco;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\CuentaCobro;
use Backend\dto\Pais;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransprodLog;
use Backend\dto\TransproductoDetalle;
use Backend\dto\Usuario;
use Backend\dto\UsuarioBanco;
use Backend\dto\UsuarioHistorial;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\mysql\TransproductoDetalleMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Exception;


/**
 * Clase para gestionar servicios de integración con AstroPay Card.
 *
 * Este archivo contiene la implementación de la clase `ASTROPAYCARDSERVICES`,
 * que permite realizar operaciones de pago y retiro a través de la API de AstroPay.
 */
class ASTROPAYCARDSERVICES
{

    /**
     * Almacena el login de la API para la autenticación.
     *
     * @var string
     */
    private $api_login = "";

    /**
     * Contraseña de la API para la autenticación.
     *
     * @var string
     */
    private $api_password = "";

    /**
     * Método de la solicitud HTTP.
     *
     * @var string
     */
    private $method;

    /**
     * URL base para las solicitudes a la API.
     *
     * @var string
     */
    private $URL = "";

    /**
     * URL de desarrollo para la API de AstroPay.
     *
     * @var string
     */
    private $URLDEV = 'https://onetouch-api-sandbox.astropay.com';

    /**
     * URL de producción para la API de AstroPay.
     *
     * @var string
     */
    private $URLPROD = 'https://onetouch-api.astropay.com';

    /**
     * URL del servicio de depósito en el entorno de desarrollo.
     *
     * @var string
     */
    private $serviceUrlDepositDEV = "";

    /**
     * URL de callback utilizada para confirmar pagos.
     *
     * @var string
     */
    private $callback_url = "";

    /**
     * URL de callback en el entorno de desarrollo para confirmar pagos.
     *
     * @var string
     */
    private $callback_urlDEV = "https://admincert.virtualsoft.tech/api/api/integrations/payment/astropaycard/confirm/";

    /**
     * URL de callback en el entorno de producción para confirmar pagos.
     *
     * @var string
     */
    private $callback_urlPROD = "https://integrations.virtualsoft.tech/payment/astropaycard/confirm/";

    /**
     * URL de callback utilizada para confirmar retiros.
     *
     * @var string
     */
    private $callback_urlcashout = "";

    /**
     * URL de callback en el entorno de desarrollo para confirmar retiros.
     *
     * @var string
     */
    private $callback_urlcashoutDEV = "https://admincert.virtualsoft.tech/api/api/integrations/payout/astropaycard/confirm/";

    /**
     * URL de callback en el entorno de producción para confirmar retiros.
     *
     * @var string
     */
    private $callback_urlcashoutPROD = "https://integrations.virtualsoft.tech/payout/astropaycard/confirm/";

    /**
     * Código del comerciante.
     *
     * @var string
     */
    private $MerchantCode = "";

    /**
     * Código del comerciante en el entorno de desarrollo.
     *
     * @var string
     */
    private $MerchantCodeDEV = "7995";

    /**
     * Código del comerciante en el entorno de producción.
     *
     * @var string
     */
    private $MerchantCodePROD = "7995";

    /**
     * Clave de la API del gateway del comerciante.
     *
     * @var string
     */
    private $Merchant_Gateway_Api_Key = "";

    /**
     * Clave de la API del gateway del comerciante en el entorno de desarrollo.
     *
     * @var string
     */
    private $Merchant_Gateway_Api_KeyDEV = "r91192Y9s1Ggbyk0HCoAyF0p9SoE0iSOLKr9oFKZNdEgznf1Ke7NaEtoG841KRzZ";

    /**
     * Clave de la API del gateway del comerciante en el entorno de producción.
     *
     * @var string
     */
    private $Merchant_Gateway_Api_KeyPROD = "Y717QRf5SbCcHaoQG6dwH3MNlPogx9vZf5LrqQJo1KJ9RkEj5uJGJxkPrTfj5OCr";

    /**
     * URL del servicio de depósito.
     *
     * @var string
     */
    private $serviceUrlDeposit = "";

    /**
     * Clave pública utilizada para la autenticación.
     *
     * @var string
     */
    private $public_key = "";

    /**
     * Clave secreta utilizada para la autenticación.
     *
     * @var string
     */
    private $secret_key = "";

    /**
     * Clave secreta en el entorno de producción.
     *
     * @var string
     */
    private $secret_keyPROD = 'LMszAECm7c7lfmFTpVlDkbVelaScQdos';

    /**
     * Clave pública en el entorno de desarrollo.
     *
     * @var string
     */
    private $public_keyDEV = 'qcYAU1fDZM0gOlnupJGisliMzjbma4tg';

    /**
     * Clave pública en el entorno de producción.
     *
     * @var string
     */
    private $public_keyPROD = 'Y717QRf5SbCcHaoQG6dwH3MNlPogx9vZf5LrqQJo1KJ9RkEj5uJGJxkPrTfj5OCr';

    /**
     * Clave secreta en el entorno de desarrollo.
     *
     * @var string
     */
    private $secret_keyDEV = 'qcYAU1fDZM0gOlnupJGisliMzjbma4tg';


    /**
     * Constructor de la clase ASTROPAYCARDSERVICES.
     *
     * Inicializa las variables de configuración dependiendo del entorno (desarrollo o producción).
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->public_key = $this->public_keyDEV;
            $this->secret_key = $this->secret_keyDEV;
            $this->callback_url = $this->callback_urlDEV;
            $this->URL = $this->URLDEV;
            $this->MerchantCode = $this->MerchantCodeDEV;
            $this->Merchant_Gateway_Api_Key = $this->Merchant_Gateway_Api_KeyDEV;
            $this->callback_urlcashout = $this->callback_urlcashoutDEV;
        } else {
            $this->public_key = $this->public_keyPROD;
            $this->secret_key = $this->secret_keyPROD;
            $this->callback_url = $this->callback_urlPROD;
            $this->URL = $this->URLPROD;
            $this->MerchantCode = $this->MerchantCodePROD;
            $this->Merchant_Gateway_Api_Key = $this->Merchant_Gateway_Api_KeyPROD;
            $this->callback_urlcashout = $this->callback_urlcashoutPROD;
        }
    }

    /**
     * Crea una solicitud de pago para un usuario y producto específicos.
     *
     * @param Usuario  $Usuario  Objeto que representa al usuario que realiza el pago.
     * @param Producto $Producto Objeto que representa el producto asociado al pago.
     * @param float    $valor    Monto del pago.
     * @param string   $url      URL de redirección después del pago.
     *
     * @return object Respuesta de la API de AstroPay con el estado de la solicitud.
     */
    public function createRequestPayment(Usuario $Usuario, Producto $Producto, $valor, $url)
    {
        if ($Usuario->mandante == '8') {
            $this->secret_key = 'voWZfxLO2LN3NZLt4AI9tsytBPLFASGI';
            $this->Merchant_Gateway_Api_Key = 'dW9wDdSKobYYBP6Czp3Cy50RrKhdQDwdnjvigctgqFLJtvasWyjYy6OII3DSoAez';
            $this->MerchantCode = "7995";
        }

        if ($Usuario->mandante == '18') {
            $this->secret_key = 'lQ1xBAnwYP7Zkz9C99fId69PbgB1PfGU';
            $this->Merchant_Gateway_Api_Key = 'd0v81dpM0lZesURXO6xIFlGT6gdAUE83mDfPhiBFkFQRmS8my9xmk8q7CPDKg0MP';
            $this->MerchantCode = "7995";
        }

        $Registro = new Registro("", $Usuario->usuarioId);
        $data = array();
        $data["success"] = false;
        $data["error"] = 1;

        $estado = 'A';
        $estado_producto = 'E';
        $tipo = 'T';

        $pais = $Usuario->paisId;
        $usuario_id = $Usuario->usuarioId;
        $cedula = $Registro->cedula;
        $nombre = $Usuario->nombre;
        $email = $Usuario->login;
        $producto_id = $Producto->productoId;
        $moneda = $Usuario->moneda;
        $mandante = $Usuario->mandante;
        $descripcion = "Deposito";

        $producto = new Producto($producto_id);
        $Pais = new Pais($pais);

        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
        $Transaction = $TransaccionProductoMySqlDAO->getTransaction();

        $TransaccionProducto = new TransaccionProducto();
        $TransaccionProducto->setProductoId($producto_id);
        $TransaccionProducto->setUsuarioId($usuario_id);
        $TransaccionProducto->setValor($valor);
        $TransaccionProducto->setEstado($estado);
        $TransaccionProducto->setTipo($tipo);
        $TransaccionProducto->setEstadoProducto($estado_producto);
        $TransaccionProducto->setMandante($mandante);
        $TransaccionProducto->setFinalId(0);
        $TransaccionProducto->setExternoId(0);
        $transproductoId = $TransaccionProductoMySqlDAO->insert($TransaccionProducto);

        //Realizamos la estructura con la data de la petición
        $data = array();
        $data['amount'] = $valor;
        $data['currency'] = $moneda;
        $data['country'] = $Pais->iso;
        $data['merchant_deposit_id'] = $transproductoId;
        $data['callback_url'] = $this->callback_url;
        $data['redirect_url'] = $url;
        $data['user'] = array(
            "merchant_user_id" => $Usuario->usuarioId,
            "document" => $Registro->getCedula(),
            "first_name" => "Daniel test Temp test",
            "last_name" => $Registro->getApellido1(),
        );
        $data['product'] = array(
            "mcc" => $this->MerchantCode,
            "merchant_code" => 'Deposito',
            "description" => $producto->getDescripcion()
        );

        if ($_ENV['debug']) {
            print_r(json_encode($data));
        }

        syslog(LOG_WARNING, "ASTROPAY DATA: " . json_encode($data));

        $TransproductoDetalle = new TransproductoDetalle();
        $TransproductoDetalle->transproductoId = $transproductoId;
        $TransproductoDetalle->tValue = json_encode(($data));

        $TransproductoDetalleMySqlDAO = new TransproductoDetalleMySqlDAO($Transaction);
        $TransproductoDetalleMySqlDAO->insert($TransproductoDetalle);

        $curl = curl_init($this->URL . "/merchant/v1/deposit/init");
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:application/json', "Signature:" . $this->GetSign($this->secret_key, json_encode($data)), "Merchant-Gateway-Api-Key:" . $this->Merchant_Gateway_Api_Key]);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        $Result = (curl_exec($curl));

        curl_close($curl);

        if ($_ENV['debug']) {
            print_r($Result);
        }

        syslog(LOG_WARNING, "ASTROPAY RESPONSE: " . $Result);

        $t_value = json_encode($Result);

        if ($Result != '' && json_decode($Result)->url != '') {
            $TransprodLog = new TransprodLog();
            $TransprodLog->setTransproductoId($transproductoId);
            $TransprodLog->setEstado('E');
            $TransprodLog->setTipoGenera('A');
            $TransprodLog->setComentario('Envio Solicitud de deposito');
            $TransprodLog->setTValue($t_value);
            $TransprodLog->setUsucreaId(0);
            $TransprodLog->setUsumodifId(0);

            $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
            $TransprodLogMySqlDAO->insert($TransprodLog);

            $Transaction->commit();

            $data = array();
            $data["success"] = true;
            $data["url"] = json_decode($Result)->url;
        } else {
            $data = array();
            $data["success"] = false;
        }

        return json_decode(json_encode($data));
    }

    /**
     * Realiza el proceso de retiro (cashout) para un usuario.
     *
     * Este método gestiona la creación de una transacción de retiro,
     * realiza la solicitud al servicio externo y actualiza los estados
     * de las transacciones y registros relacionados según el resultado.
     *
     * @param CuentaCobro $CuentaCobro Objeto que contiene la información de la cuenta de cobro.
     *
     * @return void
     * @throws Exception Si no se puede realizar la transacción.
     */
    public function cashOut(CuentaCobro $CuentaCobro)
    {
        $Usuario = new Usuario($CuentaCobro->usuarioId);
        $Registro = new Registro("", $CuentaCobro->usuarioId);

        $UsuarioBanco = new UsuarioBanco($CuentaCobro->mediopagoId);
        $Banco = new Banco($UsuarioBanco->bancoId);

        $Producto = new Producto($Banco->productoPago);

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

        $order_id = $transproductoId;
        $credit_note = $CuentaCobro->getCuentaId();
        $account_id = $UsuarioBanco->cuenta;
        $interbank = $UsuarioBanco->getCodigo();
        $bankId = $UsuarioBanco->getBancoId();
        $typeAccount = $UsuarioBanco->getTipoCuenta();
        $account_type = "Cuenta de Ahorros";
        $vat_id = $Registro->cedula;
        $amount = $CuentaCobro->getValor();
        $name = $Usuario->nombre;
        $LastName = $Registro->apellido1;
        $subject = 'Transferencia Cuenta ' . $CuentaCobro->getCuentaId();
        $bank_detail = $Banco->descripcion;
        $channel = 1;
        $user_email = $Usuario->login;
        $phone_number = $Registro->celular;
        $bank = $Producto->getExternoId();
        $bank = $Banco->productoPago;

        $Pais = new Pais($Usuario->paisId);

        $iso = $Pais->iso;


        $data = array(
            "amount" => $valorFinal,
            "currency" => $Usuario->moneda,
            "country" => $iso,
            "merchant_cashout_id" => $transproductoId,
            "Callback_url" => $this->callback_url,
            "user" => array(
                "merchant_user_id" => $Usuario->usuarioId,
                "email" => $Usuario->login,
                "phone" => $Pais->prefijoCelular . $Registro->getCelular(),
            ),
        );

        $result = $this->createTransaction($data, "/merchant/v1/cashout");

        if ($result != "" && $result != null) {
            $TransprodLog = new TransprodLog();
            $TransprodLog->setTransproductoId($transproductoId);
            $TransprodLog->setEstado('E');
            $TransprodLog->setTipoGenera('M');
            $TransprodLog->setComentario('Envio Solicitud de cashout');
            $TransprodLog->setTValue(json_encode(array_merge($data, $result)));
            $TransprodLog->setUsucreaId(0);
            $TransprodLog->setUsumodifId(0);

            $TransaccionProducto->setExternoId($result->cashout_id);
            $TransaccionProductoMySqlDAO->update($TransaccionProducto);

            $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
            $TransprodLogMySqlDAO->insert($TransprodLog);

            $Transaction->commit();
        } else {
            throw new Exception("No se pudo realizar la transaccion", "100000");
        }

        if ($result->status != "PENDING") {
            $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();
            $CuentaCobroMySqlDAO->update($CuentaCobro);
            $CuentaCobroMySqlDAO->getTransaction()->commit();


            $cashout_id = $result->cashout_id;
            $transproducto_id = $transproductoId;
            $transfer_status = $result->status;

            //Validamos dependiendo del status
            $estado = 'P';

            switch ($transfer_status) {
                case "PENDING":
                    $estado = 'P';
                    break;
                case "APPROVED":
                    $estado = 'A';
                    break;
                case "CANCELLED":
                    $estado = 'R';
                    break;
            }
            //procesamos  para actualizar la transacción y guardamos registro para los log
            if ($estado != "P") {
                $TransprodLogMysqlDAO = new TransprodLogMySqlDAO();

                $TransaccionProducto = new TransaccionProducto($transproducto_id);
                $Transaction = $TransprodLogMysqlDAO->getTransaction();

                $TransaccionProducto->setEstado("I");
                $TransaccionProducto->setEstadoProducto($estado);
                $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO($Transaction);
                $TransaccionProductoMySqlDAO->update($TransaccionProducto);

                $TransprodLog = new TransprodLog();
                $TransprodLog->setTransproductoId($transproducto_id);
                $TransprodLog->setEstado($estado);
                $TransprodLog->setTipoGenera('A');
                $TransprodLog->setComentario(json_decode($result));
                $TransprodLog->setTValue(json_decode($result));
                $TransprodLog->setUsucreaId(0);
                $TransprodLog->setUsumodifId(0);

                $TransprodLog_id = $TransprodLogMysqlDAO->insert($TransprodLog);

                $rowsUpdate = 0;
                $CuentaCobro = new CuentaCobro("", $transproducto_id);

                //Actualizamos dependiendo del estado de CuentaCobro
                if ($CuentaCobro->getEstado() == "S") {
                    if ($estado == "A") {
                        $CuentaCobro->setEstado("I");
                        $CuentaCobro->setFechaPago(date('Y-m-d H:i:s'));
                        $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO($Transaction);
                        $rowsUpdate = $CuentaCobroMySqlDAO->update($CuentaCobro, " AND (estado = 'S') ");
                    }
                    if ($estado == "R") {
                        $CuentaCobro->setEstado("R");
                        $CuentaCobro->setFechaAccion(date('Y-m-d H:i:s'));

                        $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO($Transaction);
                        $rowsUpdate = $CuentaCobroMySqlDAO->update($CuentaCobro, " AND (estado = 'S') ");
                    }
                }


                //en caso que sea rechazdo se realiza el proceso de devolución del dinero
                if ($estado == "R" && $rowsUpdate > 0) {
                    $Usuario = new Usuario($TransaccionProducto->usuarioId);
                    $Usuario->creditWin(floatval($CuentaCobro->getValor()) + floatval($CuentaCobro->getImpuesto()), $Transaction);


                    //Guardamos el registro en UsuarioHistorial
                    $UsuarioHistorial = new UsuarioHistorial();
                    $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                    $UsuarioHistorial->setDescripcion('');
                    $UsuarioHistorial->setMovimiento('E');
                    $UsuarioHistorial->setUsucreaId(0);
                    $UsuarioHistorial->setUsumodifId(0);
                    $UsuarioHistorial->setTipo(40);
                    $UsuarioHistorial->setValor($TransaccionProducto->getValor());
                    $UsuarioHistorial->setExternoId($CuentaCobro->getCuentaId());


                    $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                    $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);
                }


                $Transaction->commit();
            }
        }
    }

    /**
     * Crea una transacción enviando datos a un servicio externo.
     *
     * @param array  $data   Datos necesarios para la transacción.
     * @param string $method Método de la solicitud HTTP (por ejemplo, POST).
     *
     * @return object|null Respuesta decodificada del servicio externo o null si falla.
     */
    public function createTransaction($data, $method)
    {
        $respuesta = $this->request($method, $data, array("Content-Type: application/json"));

        $respuesta = json_decode($respuesta);
        return $respuesta;
    }

    /**
     * Genera una firma HMAC utilizando el algoritmo SHA-256.
     *
     * Este método se utiliza para crear una firma digital basada en una clave secreta
     * y un mensaje, lo que permite garantizar la integridad y autenticidad de los datos.
     *
     * @param string $key     La clave secreta utilizada para generar la firma.
     * @param string $message El mensaje que será firmado.
     *
     * @return string La firma generada en formato hexadecimal.
     */
    function GetSign($key, $message)
    {
        return (hash_hmac('sha256', pack('A*', $message), pack('A*', $key)));
    }

    /**
     * Realiza una solicitud HTTP al servicio externo.
     *
     * Este método permite enviar datos a un servicio externo utilizando cURL.
     * Se puede especificar el método HTTP (por defecto POST), los datos a enviar,
     * y los encabezados personalizados.
     *
     * @param string $path      Ruta del servicio externo (se concatena con la URL base).
     * @param array  $array_tmp Datos que se enviarán en la solicitud.
     * @param array  $header    Encabezados personalizados para la solicitud (opcional).
     * @param string $method    Método HTTP a utilizar (por defecto "POST").
     *
     * @return string Respuesta del servicio externo en formato JSON.
     */
    public function request($path, $array_tmp, $header = array(), $method = "POST")
    {
        $data = array();

        $data = array_merge($data, $array_tmp);
        $data = json_encode($data);

        $ch = curl_init($this->URL . $path);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json', "Signature:" . $this->GetSign($this->secret_key, ($data)), "Merchant-Gateway-Api-Key:" . $this->Merchant_Gateway_Api_Key]);
        curl_setopt($ch, CURLOPT_POST, true);
        $result = (curl_exec($ch));

        curl_close($ch);


        return ($result);
    }

    /**
     * Realiza una solicitud HTTP GET al servicio externo.
     *
     * Este método utiliza cURL para enviar una solicitud GET a una URL construida
     * a partir de las propiedades `URL2` y `productname`, junto con el texto proporcionado.
     *
     * @param string $text Texto adicional que se concatena a la URL para la solicitud.
     *
     * @return string Respuesta del servicio externo en formato JSON.
     */
    public function requestGET($text)
    {
        $ch = curl_init($this->URL2 . $this->productname . "/Main.ashx" . $text);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);

        $result = (curl_exec($ch));

        return ($result);
    }
}
