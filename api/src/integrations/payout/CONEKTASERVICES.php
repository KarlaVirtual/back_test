<?php

/**
 * Gestiona la comunicación con el proveedor de payout
 *
 * @category Red
 * @package  API
 * @author   nicolas.guato@virtualsoft.tech
 * @version  1.0.0
 * @since    21.04.25
 */

namespace Backend\integrations\payout;

use Exception;
use \CurlWrapper;
use Backend\dto\Pais;
use Backend\dto\Banco;
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\Proveedor;
use Backend\dto\CuentaCobro;
use Backend\dto\Subproveedor;
use Backend\dto\TransprodLog;
use Backend\dto\UsuarioBanco;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioMandante;
use Backend\dto\ProductoMandante;
use Backend\dto\TransaccionProducto;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;
use Backend\mysql\TransaccionProductoMySqlDAO;

/**
 * Esta clase incluye métodos para realizar retiros, consultar estados de retiros,
 * y manejar conexiones con la API de Conekta.
 */
class CONEKTASERVICES
{

    /**
     * Representación de 'method'
     *
     * @var string
     * @access public
     */
    private $method;

    /**
     * Representación de 'token'
     *
     * @var string
     * @access public
     */
    public $token;

    /**
     * Constructor de la clase.
     *
     * Configura la URL de la API según el entorno (desarrollo o producción).
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
        } else {
        }
    }

    /**
     * Realiza una solicitud de retiro de efectivo utilizando el proveedor "CONEKTARETIROS".
     *
     * Este método registra una transacción de producto para el retiro, verifica si el saldo disponible en Conekta
     * es suficiente, y si lo es, crea una orden de pago (cashout). También registra los logs relacionados y
     * actualiza el estado de la transacción con la respuesta del proveedor externo.
     *
     * @param CuentaCobro $CuentaCobro Objeto que contiene la información de la cuenta del usuario y el monto a pagar.
     *
     * @throws Exception Si no hay saldo suficiente en la cuenta del proveedor o si falla la conexión con el proveedor.
     */
    public function cashOut(CuentaCobro $CuentaCobro)
    {
        $Usuario = new Usuario($CuentaCobro->usuarioId);
        $Registro = new Registro("", $CuentaCobro->usuarioId);

        $Proveedor = new Proveedor("", "CONEKTARETIROS");
        $Subproveedor = new Subproveedor("", "CONEKTARETIROS");
        $Producto = new Producto("", "CONEKTARETIROS", $Proveedor->proveedorId);

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

        $array = array(
            "name" => $Registro->nombre,
            "email" => $Registro->email,
            "phone" => $Registro->celular
        );

        $this->method = "/customers";
        $UsuarioToken = new UsuarioToken("", $Proveedor->proveedorId, $CuentaCobro->usuarioId);
        $UsuarioMandante = new UsuarioMandante($UsuarioToken->getUsuarioId());

        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Subproveedor->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
        $credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $URL = $credentials->URL;
        $PRIVATE_KEY = $credentials->PRIVATE_KEY;

        $respuesta2 = $this->connection($array, $URL, $PRIVATE_KEY);
        $respuesta2 = json_decode($respuesta2);

        $amount = $valorFinal * 100;

        $this->method = "/balance";

        $respuesta = $this->connectionGET($URL, $PRIVATE_KEY);

        $respuesta = json_decode($respuesta);
        $SaldoConekta = $respuesta->pending[0]->amount;

        if ($amount <= $SaldoConekta) {
            $this->method = "/payout_orders";

            $data = array(
                "currency" => $Usuario->moneda,
                "amount" => $valorFinal * 100,
                "customer_info" => array(

                    "customer_id" => $respuesta2->id,
                ),
                "allowed_payout_methods" => array("cashout"),
                "reason" => "Referencia de retiro en efectivo",
                "payout" => array("payout_method" => array("type" => "cashout")),
                "metadata" => array(
                    "usuarioId" => $Usuario->usuarioId,
                    "transaccionId" => $transproductoId
                ),
                "reference" => $transproductoId,
            );

            $result = $this->connection($data, $URL, $PRIVATE_KEY);

            if ($result != "" && $result != null) {
                $TransprodLog = new TransprodLog();
                $TransprodLog->setTransproductoId($transproductoId);
                $TransprodLog->setEstado('E');
                $TransprodLog->setTipoGenera('M');
                $TransprodLog->setComentario('Envio Solicitud de pago');
                $TransprodLog->setTValue('');
                $TransprodLog->setUsucreaId(0);
                $TransprodLog->setUsumodifId(0);

                $result = json_decode($result);
                $TransaccionProducto->setExternoId($result->id);
                $TransaccionProductoMySqlDAO->update($TransaccionProducto);


                $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
                $TransprodLogMySqlDAO->insert($TransprodLog);

                $Transaction->commit();
            } else {

                throw new Exception("No se pudo realizar la transaccion", "100000");
            }
        } else {
            throw new Exception("Cuenta administrativa de sin saldo", "100006");
        }
    }


    /**
     * Realiza una solicitud HTTP POST a la API de Conekta con autenticación básica.
     *
     * @param array  $data        Datos que se enviarán en el cuerpo de la solicitud en formato JSON.
     * @param string $URL         URL base de la API de Conekta.
     * @param string $PRIVATE_KEY Clave privada utilizada para autenticación básica.
     *
     * @return string|null Respuesta en formato JSON como string o null si la solicitud falla.
     */
    function connection($data, $URL, $PRIVATE_KEY)
    {

        $data = json_encode($data);

        $headers = array(
            'Authorization: Basic ' . base64_encode($PRIVATE_KEY),
            'Content-type: application/json ',
            'Accept: application/vnd.conekta-v2.0.0+json'
        );


        $curl = curl_init($URL . $this->method);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 300);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        $result = curl_exec($curl);

        curl_close($curl);


        return $result;
    }

    /**
     * Realiza una solicitud HTTP GET a la API de Conekta con autenticación básica.
     *
     * @param string $URL         URL base de la API de Conekta.
     * @param string $PRIVATE_KEY Clave privada utilizada para autenticación básica.
     *
     * @return string|null Respuesta en formato JSON como string o null si la solicitud falla.
     */
    public function connectionGET($URL, $PRIVATE_KEY)
    {

        $headers = array(
            'Authorization: Basic ' . base64_encode($PRIVATE_KEY),
            'Content-type: application/json ',
            'Accept: application/vnd.conekta-v2.0.0+json'
        );
        $curl = new CurlWrapper($URL . $this->method);
        $curl->setOptionsArray(array(
            CURLOPT_URL => $URL . $this->method,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 300,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT']
        ));

        $response = $curl->execute();
        return $response;
    }
}
