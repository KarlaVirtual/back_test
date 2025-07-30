<?php

/**
 * Clase para gestionar servicios de pago mediante VISA QR.
 *
 * Este archivo contiene la implementación de métodos para realizar solicitudes de pago,
 * retiros de efectivo y otras operaciones relacionadas con transacciones de productos
 * utilizando servicios de VISA QR.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-25
 */

namespace Backend\Integrations\payment;

use Exception;
use Backend\dto\Pais;
use Backend\dto\Banco;
use Backend\dto\Usuario;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\CuentaCobro;
use Backend\dto\Clasificador;
use Backend\dto\TransprodLog;
use Backend\dto\UsuarioBanco;
use Backend\dto\MandanteDetalle;
use Backend\dto\UsuarioHistorial;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransproductoDetalle;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransproductoDetalleMySqlDAO;

/**
 * Clase `VISAQRSERVICES`
 *
 * Esta clase gestiona las operaciones relacionadas con pagos y retiros
 * mediante el servicio de VISA QR. Proporciona métodos para crear solicitudes
 * de pago, realizar retiros de efectivo y manejar transacciones.
 */
class VISAQRSERVICES
{
    /**
     * Constructor de la clase.
     *
     * Inicializa el entorno de configuración para determinar si se encuentra en desarrollo o producción.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        if ($ConfigurationEnvironment->isDevelopment()) {
        } else {
        }
    }

    /**
     * Crea una solicitud de pago mediante VISA QR.
     *
     * @param Usuario  $Usuario  Objeto que representa al usuario que realiza la transacción.
     * @param Producto $Producto Objeto que representa el producto asociado a la transacción.
     * @param float    $valor    Monto de la transacción.
     * @param string   $url      URL de retorno o callback.
     *
     * @return object Respuesta en formato JSON con el resultado de la operación.
     */
    public function createRequestPayment(Usuario $Usuario, Producto $Producto, $valor, $url)
    {
        $data = array();
        $data["success"] = false;
        $data["error"] = 1;

        $estado = 'A';
        $estado_producto = 'E';
        $tipo = 'T';

        $pais = $Usuario->paisId;
        $usuario_id = $Usuario->usuarioId;
        $valor = $valor;
        $producto_id = $Producto->productoId;
        $mandante = $Usuario->mandante;

        $Pais = new Pais($pais);

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

        //Realizamos la estructura con la data de la petición
        $transactionCurrency = "";
        $merchantId = "";

        if ($Pais->iso == "PE") {
            $transactionCurrency = "604";
            $merchantId = "650173776";
        }

        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $Usuario->mandante, $Usuario->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $curl = curl_init($Credentials->CALL_URL);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_USERPWD, $Credentials->PUBLIC_KEY . ":" . $Credentials->SECRET_KEY);
        $Result = (curl_exec($curl));
        curl_close($curl);

        $tokenSeguridad = $Result;

        $data = array();

        $data["enabled"] = true;
        $data["param"] = array(
            array(
                "name" => "merchantId",
                "value" => $merchantId
            ),
            array(
                "name" => "transactionCurrency",
                "value" => $transactionCurrency
            ),
            array(
                "name" => "transactionAmount",
                "value" => $valorTax
            ),
            array(
                "name" => "additionalData",
                "value" => 'recibo:' . $transproductoId
            ),
            array(
                "name" => "idc",
                "value" => $transproductoId
            )
        );
        $data["tagType"] = "DYNAMIC";
        $data["validityDate"] = "31122025";

        $curl = curl_init($Credentials->URL);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Authorization:' . $tokenSeguridad]);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        syslog(LOG_WARNING, "VISAQR DATA: " . $Usuario->usuarioId . ' ' . json_encode($data));
        $Result = (curl_exec($curl));
        syslog(LOG_WARNING, "VISAQR RESPONSE: " . $Usuario->usuarioId . ' ' . $Result);

        $Result = json_decode($Result);

        curl_close($curl);

        $TransproductoDetalle = new TransproductoDetalle();
        $TransproductoDetalle->transproductoId = $transproductoId;
        $TransproductoDetalle->tValue = json_encode(($data));

        $TransproductoDetalleMySqlDAO = new TransproductoDetalleMySqlDAO($Transaction);
        $TransproductoDetalleMySqlDAO->insert($TransproductoDetalle);

        if ($Result != '' && $Result->codeResponse == "0") {
            $t_value = json_encode($Result);

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

            $textDeposito = "Lee el código QR con la app.";

            if ($producto_id == "8811") {
                $textDeposito = "Lee el código QR con la app Lukita";
            }

            if ($producto_id == "8820") {
                $textDeposito = "Lee el código QR con la app Plin";
            }

            if ($producto_id == "9313") {
                $textDeposito = "Lee el código QR con tu billetera favorita: Yape o Plin";
            }

            $data = array();
            $data["success"] = true;
            $data["userid"] = $Usuario->usuarioId;
            $data["amount"] = $valorTax;
            $data["dataText"] = $textDeposito;
            $data["dataImg"] = str_replace("\/", "/", $Result->tagImg);
        } else {
            $data = array();
            $data["success"] = false;
        }

        return json_decode(json_encode($data));
    }


    /**
     * Realiza un retiro de efectivo (cashout).
     *
     * @param CuentaCobro $CuentaCobro Objeto que contiene los datos de la cuenta de cobro.
     *
     * @return void
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

        $TransaccionProducto = new TransaccionProducto();
        $TransaccionProducto->setProductoId($Producto->productoId);
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

        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $Usuario->mandante, $Usuario->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $CuentaCobro->setTransproductoId($transproductoId);

        $amount = $CuentaCobro->getValor();

        $Pais = new Pais($Usuario->paisId);

        $iso = $Pais->iso;

        $data = array(
            "amount" => $amount,
            "currency" => $Usuario->moneda,
            "country" => $iso,
            "merchant_cashout_id" => $transproductoId,
            "Callback_url" => $Credentials->CALL_URL,
            "user" => array(
                "merchant_user_id" => $Usuario->usuarioId,
                "email" => $Usuario->login,
                "phone" => $Pais->prefijoCelular . $Registro->getCelular()
            ),
        );

        $result = $this->createTransaction($data, "/merchant/v1/cashout", $Credentials->URL, $Credentials->SECRET_KEY, $Credentials->API_KEY);

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

        if ($result->status != "PENDING") {
            $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();
            $CuentaCobroMySqlDAO->update($CuentaCobro);
            $CuentaCobroMySqlDAO->getTransaction()->commit();

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
     * Crea una transacción genérica.
     *
     * @param array  $data       Datos de la transacción.
     * @param string $method     Método de la API a invocar.
     * @param string $url        URL del servicio.
     * @param string $secret_key Clave secreta para la autenticación.
     * @param string $api_key    Clave de la API para la autenticación.
     *
     * @return object Respuesta en formato JSON con el resultado de la operación.
     */
    public function createTransaction($data, $method, $url = "", $secret_key = "", $api_key = "")
    {
        $respuesta = $this->request($method, $data, $url, $secret_key, $api_key);

        $respuesta = json_decode($respuesta);
        return $respuesta;
    }

    /**
     * Genera una firma HMAC-SHA256.
     *
     * @param string $key     Clave secreta para generar la firma.
     * @param string $message Mensaje a firmar.
     *
     * @return string Firma generada.
     */
    public function GetSign($key, $message)
    {
        return (hash_hmac('sha256', pack('A*', $message), pack('A*', $key)));
    }

    /**
     * Realiza una solicitud HTTP al servicio externo.
     *
     * @param string $path       Ruta del servicio.
     * @param array  $array_tmp  Datos de la solicitud.
     * @param string $url        URL base del servicio.
     * @param string $secret_key Clave secreta para la autenticación.
     * @param string $api_key    Clave de la API para la autenticación.
     *
     * @return string Respuesta del servicio en formato JSON.
     */
    public function request($path, $array_tmp, $url = "", $secret_key = "", $api_key = "")
    {
        $data = array();

        $data = array_merge($data, $array_tmp);
        $data = json_encode($data);

        $ch = curl_init($url . $path);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json', "Signature:" . $this->GetSign($secret_key, ($data)), "Merchant-Gateway-Api-Key:" . $api_key]);
        curl_setopt($ch, CURLOPT_POST, true);
        $result = (curl_exec($ch));

        curl_close($ch);
        return ($result);
    }
}
