<?php

/**
 * Esta clase proporciona servicios para realizar transacciones de pago y manejo de solicitudes
 * hacia una API externa.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-19
 */


namespace Backend\Integrations\payout;

use Backend\dto\Banco;
use Backend\dto\CuentaCobro;
use Backend\dto\Producto;
use Backend\dto\ProductoMandante;
use Backend\dto\Registro;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransprodLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioBanco;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransprodLogMySqlDAO;

/**
 * Clase LPGSERVICES
 *
 * Esta clase proporciona servicios para realizar transacciones de pago y manejo de solicitudes
 * hacia una API externa. Incluye métodos para iniciar sesión, realizar transacciones y manejar
 * solicitudes HTTP.
 */
class LPGSERVICES
{

    /**
     * Credenciales de login para el entorno de desarrollo.
     *
     * @var string
     */
    private $api_loginDEV = "dev_carga";

    /**
     * Contraseña de login para el entorno de desarrollo.
     *
     * @var string
     */
    private $api_passwordDEV = "refunder1234";

    /**
     * Credenciales de login para el entorno de producción.
     *
     * @var string
     */
    private $api_login = "loydober1";

    /**
     * Contraseña de login para el entorno de producción.
     *
     * @var string
     */
    private $api_password = "refunder1234";

    /**
     * Metodo HTTP utilizado en las solicitudes.
     *
     * @var string
     */
    private $method;

    /**
     * Token de autenticación obtenido tras iniciar sesión.
     *
     * @var string
     */
    public $token;

    /**
     * URL base para el entorno de desarrollo.
     *
     * @var string
     */
    private $URLDEV = 'https://dev.get-refunds.com';

    /**
     * URL base para el entorno de producción.
     *
     * @var string
     */
    private $URL = 'https://app.get-refunds.com';

    /**
     * Constructor de la clase LPGSERVICES.
     */
    public function __construct()
    {
    }

    /**
     * Realiza una transacción de pago basada en la información proporcionada.
     *
     * @param CuentaCobro $CuentaCobro Objeto que contiene los datos de la cuenta de cobro.
     *
     * @return void
     * @throws Exception Si ocurre un error durante la transacción o la solicitud HTTP.
     */
    public function cashOut(CuentaCobro $CuentaCobro)
    {
        $Usuario = new Usuario($CuentaCobro->usuarioId);
        $Registro = new Registro("", $CuentaCobro->usuarioId);

        $UsuarioBanco = new UsuarioBanco($CuentaCobro->mediopagoId);
        $Banco = new Banco($UsuarioBanco->bancoId);

        $ProductoMandante = new ProductoMandante("", "", 4970);
        $Producto = new Producto($ProductoMandante->productoId);

        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
        $Transaction = $TransaccionProductoMySqlDAO->getTransaction();

        $TransaccionProducto = new TransaccionProducto();
        $TransaccionProducto->setProductoId($ProductoMandante->productoId);
        $TransaccionProducto->setUsuarioId($Usuario->usuarioId);
        $TransaccionProducto->setValor($CuentaCobro->getValor());
        $TransaccionProducto->setEstado('A');
        $TransaccionProducto->setTipo('T');
        $TransaccionProducto->setExternoId(0);
        $TransaccionProducto->setEstadoProducto('E');
        $TransaccionProducto->setMandante($Usuario->mandante);
        $TransaccionProducto->setFinalId($CuentaCobro->getCuentaId());
        $TransaccionProducto->setFinalId(0);

        $transproductoId = $TransaccionProductoMySqlDAO->insert($TransaccionProducto);


        $CuentaCobro->setTransproductoId($transproductoId);


        $this->apiLogin();


        $order_id = $transproductoId;
        $credit_note = $CuentaCobro->getCuentaId();
        $account_id = $UsuarioBanco->cuenta;
        $account_type = "Cuenta de Ahorros";
        $vat_id = $Registro->cedula;
        $amount = $CuentaCobro->getValor();
        $name = $Usuario->nombre;
        $subject = 'Transferencia Cuenta ' . $CuentaCobro->getCuentaId();
        $bank_detail = $Banco->descripcion;
        $channel = 1;
        $user_email = $Usuario->login;
        $phone_number = $Registro->celular;
        $bank = $Producto->getExternoId();
        $bank = $Banco->productoPago;


        $data = array(
            array(
                "order_id" => $order_id,
                "credit_note" => $credit_note,
                "account_id" => $account_id,
                "account_type" => $account_type,
                "vat_id" => $vat_id,
                "name" => $name,
                "amount" => $amount,
                "subject" => $subject,
                "bank_detail" => $bank_detail,
                "channel" => $channel,
                "user_email" => $user_email,
                "phone_number" => $phone_number,
                "bank" => $bank,
                "transfer_status" => 1
            )
        );

        $result = $this->createTransaction($data);

        $TransprodLog = new TransprodLog();
        $TransprodLog->setTransproductoId($transproductoId);
        $TransprodLog->setEstado('E');
        $TransprodLog->setTipoGenera('M');
        $TransprodLog->setComentario('Envio Solicitud de pago');
        $TransprodLog->setTValue(json_encode(array_merge($data, $result)));
        $TransprodLog->setUsucreaId(0);
        $TransprodLog->setUsumodifId(0);

        $TransaccionProducto->setExternoId($result[0]->id);
        $TransaccionProductoMySqlDAO->update($TransaccionProducto);


        $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
        $TransprodLogMySqlDAO->insert($TransprodLog);

        $Transaction->commit();
    }

    /**
     * Inicia sesión en la API externa y obtiene un token de autenticación.
     *
     * @return void
     * @throws Exception Si la solicitud de inicio de sesión falla.
     */
    public function apiLogin()
    {
        $data = array(
            "username" => $this->api_login,
            "password" => $this->api_password

        );

        $respuesta = $this->request("/api/login", $data, array("Content-Type: application/json"));

        $respuesta = json_decode($respuesta);
        $this->token = $respuesta->token;
    }

    /**
     * Crea una transacción en la API externa.
     *
     * @param array $data Datos de la transacción a enviar.
     *
     * @return mixed Respuesta de la API externa.
     * @throws Exception Si la solicitud de creación de transacción falla.
     */
    public function createTransaction($data)
    {
        $respuesta = $this->request(
            "/api/transactions/",
            $data,
            array("Authorization: LPG " . $this->token, "Content-Type: application/json")
        );

        print_r($respuesta);
        $respuesta = json_decode($respuesta);
        return $respuesta;
    }

    /**
     * Realiza una solicitud HTTP POST a la API externa.
     *
     * @param string $path      Ruta del endpoint de la API.
     * @param array  $array_tmp Datos a enviar en la solicitud.
     * @param array  $header    Encabezados HTTP adicionales.
     *
     * @return string Respuesta de la API externa.
     * @throws Exception Si la solicitud HTTP falla.
     */
    public function request($path, $array_tmp, $header = array())
    {
        $data = array();

        $data = array_merge($data, $array_tmp);
        $data = json_encode($data);

        $ch = curl_init($this->URL . $path);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        //$rs = curl_exec($ch);
        $result = (curl_exec($ch));

        return ($result);
    }

    /**
     * Realiza una solicitud HTTP GET a la API externa.
     *
     * @param string $path      Ruta del endpoint de la API.
     * @param array  $array_tmp Datos a enviar en la solicitud.
     * @param array  $header    Encabezados HTTP adicionales.
     *
     * @return string Respuesta de la API externa.
     * @throws Exception Si la solicitud HTTP falla.
     */
    public function requestGET($path, $array_tmp, $header = array())
    {
        $data = array();

        $data = array_merge($data, $array_tmp);
        $data = json_encode($data);

        $ch = curl_init($this->URL . $path);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $result = (curl_exec($ch));

        return ($result);
    }

}
