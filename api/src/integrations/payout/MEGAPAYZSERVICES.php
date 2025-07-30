<?php

/**
 * Proporciona servicios relacionados con el proceso de retiro de dinero (cash out).
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-19
 */

namespace Backend\integrations\payout;

use Exception;
use \CurlWrapper;
use Backend\dto\Usuario;
use Backend\dto\Registro;
use Backend\dto\CuentaCobro;
use Backend\dto\TransprodLog;
use Backend\dto\UsuarioBanco;
use Backend\dto\TransaccionProducto;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;
use Backend\mysql\TransaccionProductoMySqlDAO;

/**
 * Clase MEGAPAYZSERVICES.
 * Proporciona servicios relacionados con el proceso de retiro de dinero (cash out).
 */
class MEGAPAYZSERVICES
{
    /**
     * Constructor de la clase MEGAPAYZSERVICES.
     * Inicializa el entorno de configuración.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        if ($ConfigurationEnvironment->isDevelopment()) {
        } else {
        }
    }

    /**
     * Realiza un proceso de retiro de dinero (cash out).
     *
     * @param CuentaCobro $CuentaCobro Objeto que contiene la información de la cuenta de cobro.
     * @param mixed       $Producto    Objeto que contiene la información del producto asociado.
     *
     * @return void
     * @throws Exception Si el tipo de cuenta bancaria no es encontrado.
     * @throws Exception Si la transferencia no fue procesada.
     */
    public function cashOut(CuentaCobro $CuentaCobro, $Producto)
    {
        $Usuario = new Usuario($CuentaCobro->usuarioId);
        $Registro = new Registro("", $CuentaCobro->usuarioId);

        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $Usuario->mandante, $Usuario->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $UsuarioBanco = new UsuarioBanco($CuentaCobro->mediopagoId);

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

        $account_id = $UsuarioBanco->cuenta;
        $amount = $CuentaCobro->getValor();
        $method = $Producto->getExternoId();

        $hash = md5($Credentials->SID . $Usuario->usuarioId . $Registro->nombre1 . $transproductoId . $Credentials->PRIVATE_KEY);

        $data = array();
        $data['sid'] = $Credentials->SID;
        $data['hash'] = $hash;
        $data['method'] = 'get-withdraw-form';
        $data['username'] = $Registro->nombre1;
        $data['user_id'] = $Usuario->usuarioId;
        $data['fullname'] = $Registro->nombre . ' ' . $Registro->apellido1;
        $data['trx'] = $transproductoId;
        $data['input_fields'] = [
            "method" => $method,
            "account" => $account_id,
            "amount" => $amount,
        ];

        $path = "/create-withdraw";
        $Result = $this->request($data, $Credentials->URL . $path);

        syslog(LOG_WARNING, "MEGAPAYZOUT DATA: " . $Usuario->usuarioId . json_encode($data) . " RESPONSE: " . $Result);

        $result = json_decode($Result);

        if ($result != "" && $result->status == true) {
            $TransprodLog = new TransprodLog();
            $TransprodLog->setTransproductoId($transproductoId);
            $TransprodLog->setEstado('E');
            $TransprodLog->setTipoGenera('M');
            $TransprodLog->setComentario('Envio Solicitud de pago');
            $TransprodLog->setTValue(json_encode($result));
            $TransprodLog->setUsucreaId(0);
            $TransprodLog->setUsumodifId(0);

            $TransaccionProducto->setExternoId($transproductoId);
            $TransaccionProductoMySqlDAO->update($TransaccionProducto);


            $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
            $TransprodLogMySqlDAO->insert($TransprodLog);

            $Transaction->commit();
        } else {
            throw new Exception("La transferencia no fue procesada", "10000");
        }
    }

    /**
     * Realiza una solicitud HTTP POST utilizando cURL.
     *
     * @param array  $data Datos a enviar en el cuerpo de la solicitud.
     * @param string $url  URL del endpoint al que se realizará la solicitud.
     *
     * @return string Respuesta del servidor en formato JSON.
     */
    public function request($data, $url)
    {
        $curl = new CurlWrapper($url);
        $curl->setOptionsArray(array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'content-type: application/json'
            ),
        ));

        $response = $curl->execute();
        return $response;
    }
}
