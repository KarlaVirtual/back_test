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
use Backend\dto\Usuario;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\CuentaCobro;
use Backend\dto\TransprodLog;
use Backend\dto\UsuarioBanco;
use Backend\dto\UsuarioMandante;
use Backend\dto\TransaccionProducto;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;
use Backend\mysql\TransaccionProductoMySqlDAO;

/**
 * Esta clase incluye métodos para realizar retiros, consultar estados de retiros,
 * y manejar conexiones con la API de ALPS.
 */
class ALPSSERVICES
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
     * Realiza una solicitud de retiro de efectivo utilizando el proveedor "ALPSRETIROS".
     *
     * Este método registra una transacción de producto para el retiro, verifica si el saldo disponible en ALPS
     * es suficiente, y si lo es, crea una orden de pago (cashout). También registra los logs relacionados y
     * actualiza el estado de la transacción con la respuesta del proveedor externo.
     *
     * @param CuentaCobro $CuentaCobro Objeto que contiene la información de la cuenta del usuario y el monto a pagar.
     * @param Producto $Producto    Objeto Producto que representa el producto asociado al retiro.
     *
     * @return boolean
     * @throws Exception Si no hay saldo suficiente en la cuenta del proveedor o si falla la conexión con el proveedor.
     */
    public function cashOut(CuentaCobro $CuentaCobro, Producto $Producto)
    {
        $Usuario = new Usuario($CuentaCobro->usuarioId);
        $Registro = new Registro("", $CuentaCobro->usuarioId);
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


        $UsuarioBanco = new UsuarioBanco($CuentaCobro->mediopagoId);
        $UsuarioMandante = new UsuarioMandante('', $CuentaCobro->usuarioId, $CuentaCobro->mandante);

        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
        $credentials = json_decode($SubproveedorMandantePais->getCredentials());

        //Loguearse para obtener acceso al token
        $path = 'login';
        $URL = $credentials->URL;
        $data = [
            'username' => $credentials->USERNAME,
            'password' => $credentials->PASSWORD
        ];

        $Token = $this->GetToken($URL . $path, json_encode($data));
        $Response = json_decode($Token);

        switch ($UsuarioBanco->getTipoCuenta()) {
            case "0":
                $typeAccount = "ahorro";
                break;
            case "1":
                $typeAccount = "corriente";
                break;
            case "Ahorros":
                $typeAccount = "ahorro";
                break;
            case "Corriente":
                $typeAccount = "corriente";
                break;
            default:
                throw new Exception("Tipo de cuenta bancaria no encontrada", "10000");
                break;
        }

        $Detail = 'Solicitud de retiro';

        if ($Usuario->paisId == 173) {
            switch ($Registro->tipoDoc) {
                case "C":
                    $tipoDoc = "DNI";
                    break;
                case "P":
                    $tipoDoc = "PAS";
                    break;
                case "E":
                    $tipoDoc = "CE";
                    break;
                default:
                    $tipoDoc = "DNI";
                    break;
            }
        } else if ($Usuario->paisId == 46) {
            switch ($Registro->tipoDoc) {
                case "C":
                    $tipoDoc = "DNI";
                    break;
                case "P":
                    $tipoDoc = "RUN";
                    break;
                case "E":
                    $tipoDoc = "RUN";
                    break;
                default:
                    $tipoDoc = "DNI";
                    break;
            }
        } else if ($Usuario->paisId == 66) {
            switch ($Registro->tipoDoc) {
                case "C":
                    $tipoDoc = "CED";
                    break;
                case "P":
                    $tipoDoc = "PAS";
                    break;
                case "E":
                    $tipoDoc = "PAS";
                    break;
                default:
                    $tipoDoc = "CED";
                    break;
            }
        } else {
            switch ($Registro->tipoDoc) {
                case "C":
                    $tipoDoc = "CPF";
                    break;
                case "P":
                    $tipoDoc = "RNE";
                    break;
                case "E":
                    $tipoDoc = "RNE";
                    break;
                default:
                    $tipoDoc = "CPF";
                    break;
            }
        }

        $Data = array(
            "order_id" =>  "$transproductoId",
            "credit_note" => "$CuentaCobro->cuentaId",
            "account_id" => $UsuarioBanco->cuenta,
            "account_type" => $typeAccount,
            "vat_id" => "$Registro->cedula",
            "vat_id_type" => $tipoDoc,
            "name" => $Registro->nombre,
            "amount" => $valorFinal,
            "subject" => 'transferencia',
            "bank_detail" => $Detail,
            "channel" => 'transferencia',
            "user_email" => $Registro->email,
            "phone_number" => $Registro->celular,
            "bank" => $Producto->externoId
        );

        // Se utiliza para crear la transación 

        $Path = "transactions/";

        $respuesta = $this->CreatePayout('[' . json_encode($Data) . ']', $URL . $Path, $Response->token);

        $RespuestaJson = json_decode($respuesta, true);
        $Respuesta = $RespuestaJson['data'][0];

        if ($Respuesta != '') {
            $TransprodLog = new TransprodLog();
            $TransprodLog->setTransproductoId($transproductoId);
            $TransprodLog->setEstado('E');
            $TransprodLog->setTipoGenera('M');
            $TransprodLog->setComentario('Envio Solicitud de pago');
            $TransprodLog->setTValue('[' . json_encode($RespuestaJson) . ']');
            $TransprodLog->setUsucreaId(0);
            $TransprodLog->setUsumodifId(0);

            $TransaccionProducto->setExternoId($Respuesta['id']);
            $TransaccionProductoMySqlDAO->update($TransaccionProducto);

            $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
            $TransprodLogMySqlDAO->insert($TransprodLog);

            $Transaction->commit();
        } else {
            throw new Exception("La transferencia no fue procesada", "10000");
        }
        
        return true;
    }

    /**
     * Realiza una solicitud HTTP POST a la API de ALPS con autenticación básica.
     *
     * @param array  $data        Datos que se enviarán en el cuerpo de la solicitud en formato JSON.
     * @param array $URL         URL base de la API de ALPS.
     * @param string $Token Clave privada utilizada para autenticación básica.
     *
     * @return string|null Respuesta en formato JSON como string o null si la solicitud falla.
     */
    public function CreatePayout($data, $URL, $Token)
    {
        $curl = new CurlWrapper($URL);
        $curl->setOptionsArray(array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                'Authorization: ALPS ' . $Token,
                'Content-Type: application/json'
            ),
        ));

        $response = $curl->execute();
        return $response;
    }

    /**
     * Realiza una solicitud HTTP POST a la API de ALPS con autenticación básica.
     *
     * @param string $URL         URL base de la API de ALPS.
     * @param string $Token Clave privada utilizada para autenticación básica.
     *
     * @return string|null Respuesta en formato JSON como string o null si la solicitud falla.
     */
    public function GetTransaction($URL, $Token)
    {
        $curl = new CurlWrapper($URL);
        $curl->setOptionsArray(array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: ALPS ' . $Token,
                'Accept: application/json'
            ),
        ));

        $response = $curl->execute();
        return $response;
    }

   /**
     * Realiza una solicitud HTTP POST a la API de ALPS para obtener un token.
     *
     * @param string $URL  URL base de la API de ALPS.
     * @param string $data Cuerpo de la solicitud en formato JSON.
     *
     * @return string|null Respuesta en formato JSON como string o null si la solicitud falla.
     */
    public function GetToken($URL, $data)
    {

        $curl = new CurlWrapper($URL);
        $curl->setOptionsArray(array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = $curl->execute();
        return $response;
    }
}
