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
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Registro;
use Backend\dto\CuentaCobro;
use Backend\dto\Subproveedor;
use Backend\dto\TransprodLog;
use Backend\dto\UsuarioBanco;
use Backend\dto\TransaccionProducto;
use Backend\dto\SubproveedorMandante;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;
use Backend\mysql\TransaccionProductoMySqlDAO;

/**
 * Clase KASHIOSERVICES.
 * Proporciona servicios relacionados con el proceso de retiro de dinero (cash out).
 */
class KASHIOSERVICES
{
    /**
     * Constructor de la clase KASHIOSERVICES.
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

        $mandante = $Usuario->mandante;
        $Mandante = new Mandante($Usuario->mandante);

        $Subproveedor = new Subproveedor('', 'KASHIOOUT');
        $Subproveedor = new SubproveedorMandante($Subproveedor->getSubproveedorId(), $mandante, '');

        $SubproveedorMandantePais = new SubproveedorMandantePais(
            '',
            $Producto->subproveedorId,
            $Usuario->mandante,
            $Usuario->paisId
        );
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
        $bankId = $UsuarioBanco->getBancoId();
        $typeAccount = $UsuarioBanco->getTipoCuenta();
        $amount = $CuentaCobro->getValor();
        $subject = 'Transferencia Cuenta ' . $CuentaCobro->getCuentaId();
        $tipoDocumento = $Registro->tipoDoc;

        switch ($tipoDocumento) {
            case "E":
                $tipoDocumento = "CEX";
                break;
            case "P":
                $tipoDocumento = "PAS";
                break;
            case "C":
                $tipoDocumento = "DNI";
                break;
            default:
                $tipoDocumento = "DNI";
                break;
        }

        $bankId = $Producto->getExternoId();

        switch ($UsuarioBanco->getTipoCuenta()) {
            case "0":
                $typeAccount = "CA";
                break;
            case "1":
                $typeAccount = "CC";
                break;
            case "Ahorros":
                $typeAccount = "SAVING";
                break;
            case "Corriente":
                $typeAccount = "CHECKING";
                break;
            case "CPF":
                $typeAccount = "CPF";
                break;
            case "EMAIL":
                $typeAccount = "Email";
                break;
            default:
                throw new Exception("Tipo de cuenta bancaria no encontrada", "10000");
                break;
        }

        date_default_timezone_set('GMT');
        $current_date = date('Y-m-d\TH:i:s');

        date_default_timezone_set('America/Bogota');
        $data = array();
        $data['customer'] = [
            "document_type" => $tipoDocumento,
            "name" => $Registro->nombre,
            "phone" => $Registro->celular,
            "email" => $Registro->email,
            "document_id" => $Registro->cedula,
            "external_id" => $Registro->usuarioId,
            "accounts" => [
                [
                    "bank" => [
                        "id" => $bankId
                    ],
                    "type" => $typeAccount,
                    "account_number" => $account_id
                ]
            ]
        ];
        $data['external_id'] = $transproductoId;
        $data['request_datetime'] = $current_date;
        $data['total'] = [
            "currency" => $Usuario->moneda,
            "value" => floatval($amount)
        ];
        $data['metadata'] = [
            "order_id" => $transproductoId,
            "order_name" => $subject
        ];

        $path = "payouts/invoices";

        $Result = $this->request($data, $Credentials->URL . $path, $Credentials->KEY);

        syslog(LOG_WARNING, "KASHIOOUT DATA: " . $Usuario->usuarioId . json_encode($data) . " RESPONSE: " . $Result);

        $result = json_decode($Result);

        if ($result != "" && $result->status == 'NEW') {
            $TransprodLog = new TransprodLog();
            $TransprodLog->setTransproductoId($transproductoId);
            $TransprodLog->setEstado('E');
            $TransprodLog->setTipoGenera('M');
            $TransprodLog->setComentario('Envio Solicitud de pago');
            $TransprodLog->setTValue(json_encode($result));
            $TransprodLog->setUsucreaId(0);
            $TransprodLog->setUsumodifId(0);

            $TransaccionProducto->setExternoId($result->id);
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
     * @param string $key  Clave de autorización para la solicitud.
     *
     * @return string Respuesta del servidor en formato JSON.
     */
    public function request($data, $url, $key)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
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
                'accept: application/json',
                'authorization: Basic ' . base64_encode($key . ':'),
                'content-type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
}
