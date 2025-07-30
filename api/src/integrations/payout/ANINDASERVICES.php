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
use Backend\dto\ProductoDetalle;
use Backend\dto\TransaccionProducto;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;
use Backend\mysql\TransaccionProductoMySqlDAO;

/**
 * Clase ANINDASERVICES.
 * Proporciona servicios relacionados con el proceso de retiro de dinero (cash out).
 */
class ANINDASERVICES
{
    /**
     * Constructor de la clase ANINDASERVICES.
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

        // Usar explode para separar en partes el externoId
        $externoId = explode('##', $method);

        // Usar regex para insertar el texto entre los puntos
        $nuevaUrl = preg_replace('/\.\./', '.' . $externoId[1] . '.', $Credentials->URL, 1);
        
        try {
            $Productodetalle = new ProductoDetalle("", $Producto->productoId, 'GAMEID');
            $externoId2 = $Productodetalle->pValue;
        } catch (Exception $e) {
            $externoId2 = '';
        }

        $data = array();
        $data['Key'] = $Credentials->KEY;
        $data['PlayerID'] = $Usuario->usuarioId;
        $data['PlayerFullName'] = $Registro->nombre . ' ' . $Registro->apellido1;
        $data['PlayerUserName'] = $Registro->nombre1;
        $data['PaymentMethodID'] = $externoId[0];
        $data['TraderTransactionID'] = $transproductoId;
        $data['BankID'] = $externoId2;
        $data['AccountNumber'] = $account_id;
        $data['Amount'] = $amount;
        $data['PlayerIdentityNumber'] = $Registro->cedula;
        $data['PlayerPhoneNumber'] = $Registro->celular;
        $data['PlayerEmail'] = $Registro->email;

        $concatenados = [];
        foreach ($data as $key => $value) {
            $concatenados[] = $key . $value;
        }
        sort($concatenados);
        $cadenaFinal = implode('', $concatenados);

        $data['checksum'] = md5($cadenaFinal . $Credentials->PASSWORD);

        $path = "/trader/set-withdraw";
        $Result = $this->request($data, $nuevaUrl . $path);

        syslog(LOG_WARNING, "ANINDAOUT DATA: " . $Usuario->usuarioId . json_encode($data) . " RESPONSE: " . $Result);

        $result = json_decode($Result);

        if ($result != "" && $result->HasError == false) {
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
     * @return string $response Respuesta del servidor en formato JSON.
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
