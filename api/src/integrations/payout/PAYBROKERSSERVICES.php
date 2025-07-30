<?php

/**
 * Esta clase se encarga de gestionar la conexión y las operaciones con el servicio de PAYBROKERS.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-19
 */

namespace Backend\integrations\payout;

use Backend\dto\Banco;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\CuentaCobro;
use Backend\dto\Pais;
use Backend\dto\Producto;
use Backend\dto\ProductoMandante;
use Backend\dto\Registro;
use Backend\dto\Subproveedor;
use Backend\dto\SubproveedorMandante;
use Backend\dto\SubproveedorMandantePais;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransprodLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioBanco;
use Backend\dto\UsuarioHistorial;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use \CurlWrapper;
use Exception;

/**
 * Clase PAYBROKERSSERVICES
 *
 * Esta clase se encarga de gestionar la conexión y las operaciones con el servicio de PAYBROKERS.
 * Proporciona métodos para realizar retiros, construir encabezados de autorización y manejar respuestas.
 */
class PAYBROKERSSERVICES
{

    /**
     * URL de retorno utilizada para las conexiones.
     * Se inicializa como una cadena vacía y se asigna según el entorno.
     *
     * @var string
     */
    private $callback_url = "";

    /**
     * URL de retorno utilizada para el entorno de desarrollo.
     * Se inicializa como una cadena única.
     *
     * @var string
     */
    private $callback_urlDEV = "https://apidev.virtualsoft.tech/integrations/payout/paybrokers/confirm/";

    /**
     * URL de retorno utilizada para el entorno de producción.
     * Se inicializa como una cadena única.
     *
     * @var string
     */
    private $callback_urlPROD = "https://integrations.virtualsoft.tech/payout/paybrokers/confirm/";

    /**
     * Tipo de transacción utilizada para las conexiones.
     * Se inicializa como una cadena vacía y se asigna según el entorno.
     *
     * @var string
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->callback_url = $this->callback_urlDEV;
        } else {
            $this->callback_url = $this->callback_urlPROD;
        }
    }

    /**
     * Realiza un retiro utilizando el servicio de PAYBROKERS.
     *
     * @param CuentaCobro $CuentaCobro Objeto CuentaCobro que contiene la información del retiro.
     * @param string      $ProductoId  ID del producto (opcional).
     *
     * @return void
     * @throws Exception Si ocurre un error durante el proceso de retiro.
     */
    public function cashOut(CuentaCobro $CuentaCobro, $ProductoId = "")
    {
        $Usuario = new Usuario($CuentaCobro->usuarioId);
        $Registro = new Registro("", $CuentaCobro->usuarioId);

        $Subproveedor = new Subproveedor("", "PAYBROKERS");
        $SubproveedorMandantePais = new SubproveedorMandantepais("", $Subproveedor->subproveedorId, $Usuario->mandante, $Usuario->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $URL = $Credentials->URL;

        $UsuarioBanco = new UsuarioBanco($CuentaCobro->mediopagoId);
        $Banco = new Banco($UsuarioBanco->bancoId);

        if ($ProductoId == "") {
            $Producto = new Producto($Banco->productoPago);
        } else {
            $Producto = new Producto($ProductoId);
        }

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
        $CuentaCobro->setTransproductoId($transproductoId);

        $amount = $CuentaCobro->getValor();
        $cedula = $Registro->cedula;

        $pix_key_type = $UsuarioBanco->getTipoCuenta();
        $pix_key = $UsuarioBanco->cuenta;

        if ($Usuario->mandante == 18) {
            $pix_key_type = 'CPF';
        }
        if ($pix_key_type == "PHONE") {
            $pix_key = '+55' . $pix_key;
        }

        $data['value'] = floatval($amount);
        $data['cpf'] = trim($cedula);
        $data['description'] = $Usuario->mandante . '##' . $transproductoId;
        $data['pix_key_type'] = $pix_key_type;
        $data['pix_key'] = $pix_key;
        $data['webhook_url'] = $this->callback_url;

        $header = $this->buildAuthorizationHeader($Usuario->mandante, $Usuario);

        $Respueta = $this->ConnectionToken($header, $URL);

        $token = $Respueta->token;

        $this->tipo = "/v4/withdraw/pix/cpf";

        $seguirToken = true;


        if ($token == "" || $token == null) {
            $seguirToken = false;
        }

        if($seguirToken){

            syslog(LOG_WARNING, "PAYBROKERS DATA PAYOUT ".json_encode($data).'TOKEN ='.$token);

            $result = $this->CreateWithdrawCPF($data, $token, $URL);

            syslog(LOG_WARNING, 'PAYBROKERSSERVICERESPONSE PAYOUT' . (json_encode($result)));


            if ($result != "" && $result != null && $result->error == null && $result->name != 'PAYMENT_GATEWAY_ERROR' && $result->message != 'Endpoint request timed out') {
                $TransprodLog = new TransprodLog();
                $TransprodLog->setTransproductoId($transproductoId);
                $TransprodLog->setEstado('E');
                $TransprodLog->setTipoGenera('M');
                $TransprodLog->setComentario('Envio Solicitud de pago');
                $TransprodLog->setTValue(json_encode(array_merge($data, (array)$result)));
                $TransprodLog->setUsucreaId(0);
                $TransprodLog->setUsumodifId(0);

                $TransaccionProducto->setExternoId($result->id);
                $TransaccionProductoMySqlDAO->update($TransaccionProducto);


                $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
                $TransprodLogMySqlDAO->insert($TransprodLog);

                $Transaction->commit();
            } else {
                $seguirToken = false;
            }
        }


        if ( ! $seguirToken) {
            if (true) {
                $TransprodLog = new TransprodLog();
                $TransprodLog->setTransproductoId($transproductoId);
                $TransprodLog->setEstado('E');
                $TransprodLog->setTipoGenera('M');
                $TransprodLog->setComentario('Envio Solicitud de pago');
                $TransprodLog->setTValue(json_encode(array_merge($data, (array)$result)));
                $TransprodLog->setUsucreaId(0);
                $TransprodLog->setUsumodifId(0);

                $TransaccionProducto->setExternoId($result->id);
                $TransaccionProductoMySqlDAO->update($TransaccionProducto);


                $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
                $TransprodLogMySqlDAO->insert($TransprodLog);

                $comment = "Error interno";

                if ((strpos($result->error->frames[0], "CPF invu00e1lido") !== false)) {
                    $comment = "CPF invalido.";
                } elseif ((strpos($result->error->frames[0], "Nu00famero de tentativas de consulta DICT por usuu00e1rio foi excedido.") !== false)) {
                    $comment = "O número de tentativas de consulta por usuario foi excedido.";
                } elseif ((strpos($result->error->frames[0], "E-mail invu00e1lido") !== false)) {
                    $comment = "E-mail invalido.";
                } elseif ((strpos($result->error->frames[0], "RECEBIMENTO REJEITADO PELO BANCO DE DESTINO") !== false)) {
                    $comment = "RECEBIMENTO REJEITADO PELO BANCO DE DESTINO.";
                } elseif ((strpos($result->error->frames[0], "O tamanho do campo nu00e3o foi respeitado") !== false)) {
                    $comment = "O tamanho do campo não foi respeitado.";
                } elseif ((strpos($result->error->frames[0], "CPF Nu00c3O PERTENCE AO TITULAR DA CONTA BANCu00c1RIA") !== false)) {
                    $comment = "CPF NUMERO PERTENCE AO TITULAR DA CONTA BANCARIA.";
                } elseif ((strpos($result->error->frames[0], "SAQUE Nu00c3O PERMITIDO PARA CONTA PJ") !== false)) {
                    $comment = "SAQUE NÃO PERMITIDO PARA CONTA PESSOA JURÍDICA.";
                } elseif ((strpos($result->error->frames[0], "value must be greater than") !== false)) {
                    $comment = "O valor deve ser maior que 1.00";
                }

                if ($result->name == 'PAYMENT_GATEWAY_ERROR') {
                    $comment = $result->message;
                }

                $TransprodLog = new TransprodLog();
                $TransprodLog->setTransproductoId($transproductoId);
                $TransprodLog->setEstado('R');
                $TransprodLog->setTipoGenera('A');

                if ($comment != "") {
                    $TransprodLog->setComentario('Rechazado Solicitud de pago. Respuesta Proveedor: ' . $comment);
                    $CuentaCobro->setMensajeUsuario(substr($comment, 0, 254));
                } else {
                    $TransprodLog->setComentario('Rechazado Solicitud de pago. Respuesta Proveedor: ' . json_encode(((array)$result->error->frames)));
                    $CuentaCobro->setMensajeUsuario(substr(json_encode(((array)$result->error->frames)), 0, 254));
                }

                $TransprodLog->setTValue(json_encode(array_merge($data, (array)$result)));
                $TransprodLog->setUsucreaId(0);
                $TransprodLog->setUsumodifId(0);


                $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
                $TransprodLogMySqlDAO->insert($TransprodLog);


                $CuentaCobro->setEstado("R");
                $CuentaCobro->setFechaAccion(date('Y-m-d H:i:s'));

                $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO($Transaction);
                $rowsUpdate = $CuentaCobroMySqlDAO->update($CuentaCobro, " AND (estado = 'P' OR estado = 'S') ");

                if ($rowsUpdate > 0) {
                    $Usuario = new Usuario($TransaccionProducto->usuarioId);
                    $Usuario->creditWin(floatval($CuentaCobro->getValor()) + floatval($CuentaCobro->getImpuesto()), $Transaction);

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


            if ($comment != "") {
                throw new Exception($comment, "21015"); //21015

            } else {
                throw new Exception("El retiro no pudo ser pagado y fue rechazado", "21013");
            }
        }
    }

    /**
     * Obtiene las credenciales del proveedor PAYBROKERS.
     *
     * @param Usuario $Usuario Objeto Usuario que contiene la información del usuario.
     *
     * @return object $Credentials Objeto con las credenciales del proveedor.
     */
    public function Credentials($Usuario)
    {
        $Subproveedor = new Subproveedor("", "PAYBROKERS");
        $SubproveedorMandantePais = new SubproveedorMandantePais("", $Subproveedor->subproveedorId, $Usuario->mandante, $Usuario->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

        return $Credentials;
    }

    /**
     * Construye el encabezado de autorización para las solicitudes a PAYBROKERS.
     *
     * @param string       $mandante ID del mandante (opcional, por defecto '14').
     * @param Usuario|null $Usuario  Objeto Usuario que contiene la información del usuario (opcional).
     *
     * @return array $header Encabezado de autorización codificado en base64.
     */
    public function buildAuthorizationHeader($mandante = '14', $Usuario = null)
    {
        $Credentials = $this->Credentials($Usuario);

        $USERNAME = $Credentials->USERNAME;
        $PASSWORD = $Credentials->PASSWORD;

        $header = array(
            'Authorization: Basic ' . base64_encode($USERNAME . ':' . $PASSWORD)
        );
        return $header;
    }

    /**
     * Realiza una conexión para obtener un token de autorización de PAYBROKERS.
     *
     * @param array  $header Encabezado de autorización.
     * @param string $URL    URL del servicio PAYBROKERS.
     *
     * @return object $response Objeto JSON con la respuesta del servicio.
     */
    public function ConnectionToken($header, $URL)
    {
        $this->tipo = "/v4/auth/token";
        $url = $URL . $this->tipo;

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
            CURLOPT_HTTPHEADER => $header,
        ));

        $response = $curl->execute();

        syslog(LOG_WARNING, "PAYBROKERS RESPONSE TOKENOUT " . $response);
        return json_decode($response);
    }

    /**
     * Crea un retiro utilizando el servicio de PAYBROKERS con la clave CPF.
     *
     * @param array  $data  Datos del retiro.
     * @param string $token Token de autorización.
     * @param string $URL   URL del servicio PAYBROKERS.
     *
     * @return object $response Objeto JSON con la respuesta del servicio.
     */
    public function CreateWithdrawCPF($data, $token, $URL)
    {
        $this->tipo = "/v4/withdraw/pix/cpf";
        $url = $URL . $this->tipo;

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
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json'
            ),
        ));

        $response = $curl->execute();

        return json_decode($response);
    }

    /**
     * Obtiene el estado de un retiro utilizando el ID del retiro y el token de autorización.
     *
     * @param string $id    ID del retiro.
     * @param string $token Token de autorización.
     * @param string $URL   URL del servicio PAYBROKERS.
     *
     * @return object $response Objeto JSON con la respuesta del servicio.
     */
    public function GetPIXWithdraw($id, $token, $URL)
    {
        $this->tipo = "/v4/report/api/withdraw?withdraw_id=";
        $url = $URL . $this->tipo . $id;

        $curl = new CurlWrapper($url);

        $curl->setOptionsArray(array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $token
            ),
        ));

        $response = $curl->execute();

        return json_decode($response);
    }

}
