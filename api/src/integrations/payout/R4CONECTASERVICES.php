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
use Backend\dto\UsuarioHistorial;
use Backend\dto\TransaccionProducto;
use Backend\dto\SubproveedorMandante;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use \CurlWrapper;

/**
 * Clase R4CONECTASERVICES.
 * Proporciona servicios relacionados con el proceso de retiro de dinero (cash out).
 */
class R4CONECTASERVICES
{
    /**
     * Constructor de la clase R4CONECTASERVICES.
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

        $Subproveedor = new Subproveedor('', 'R4CONECTAOUT');
        $Subproveedor = new SubproveedorMandante($Subproveedor->getSubproveedorId(), $mandante, '');

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

        $bankId = $UsuarioBanco->getBancoId();
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

        $monto = number_format((float)$amount, 2, '.', '');
        $data = array();
        $data = [
            "Banco" => $bankId,
            "Cedula" => $Registro->cedula,
            "Telefono" => $Registro->celular,
            "Monto" => $monto,
            "Concepto" => $subject,
        ];

        $mensaje = $bankId . $Registro->cedula . $Registro->celular . $monto;
        $token_authorization = hash_hmac('sha256', $mensaje, $Credentials->COMMERCE);
        $path = "/CreditoInmediato";

        $Result = $this->request($data, $Credentials->URL . $path, $token_authorization, $Credentials->COMMERCE);
        syslog(LOG_WARNING, "R4CONECTAOUT DATA: " . $Usuario->usuarioId . json_encode($data) . " RESPONSE: " . $Result);

        $result = json_decode($Result);
        $dataConsultaEstado = [
            "Id" => $result->id
        ];

        $token_consulta = hash_hmac('sha256', $result->id, $Credentials->COMMERCE);

        $pathConsulta = "/ConsultarOperaciones";

        $resultConsulta = $this->requestConsulta($dataConsultaEstado, $Credentials->URL . $pathConsulta, $token_consulta, $Credentials->COMMERCE);
        $result = json_decode($resultConsulta);

        if ($result != "" && $result->code == 'ACCP') {
            $TransprodLog = new TransprodLog();
            $TransprodLog->setTransproductoId($transproductoId);
            $TransprodLog->setEstado('E');
            $TransprodLog->setTipoGenera('M');
            $TransprodLog->setComentario('Envio Solicitud de pago');
            $TransprodLog->setTValue(json_encode($result));
            $TransprodLog->setUsucreaId(0);
            $TransprodLog->setUsumodifId(0);

            $TransaccionProducto->setExternoId($result->reference);
            $TransaccionProductoMySqlDAO->update($TransaccionProducto);


            $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
            $TransprodLogMySqlDAO->insert($TransprodLog);

            $Transaction->commit();

            $resultConsulta = $this->aprobacionCashout($result, $CuentaCobro);
        } else {
            throw new Exception("La transferencia no fue procesada", "10000");
        }
    }

    /**
     * Aprueba una solicitud de pago con un enlace de cashout.
     *
     * @param $result  Objeto del usuario.
     * @param $CuentaCobro  Objeto del usuario.
     *
     * @return array Respuesta con el estado de la operación.
     */
    public function aprobacionCashout($result, $CuentaCobro)
    {

        if (isset($result)) {
            $code = $result->code;
            $reference = $result->reference;
            $Id = $result->Id;

            $estado = 'P';
            if ($code == "ACCP") {
                $estado = 'A';
            } else {
                $estado = 'R';
            }

            if ($estado != "P") {
                $TransprodLogMysqlDAO = new TransprodLogMySqlDAO();

                $TransaccionProducto = new TransaccionProducto();
                $Transaction = $TransprodLogMysqlDAO->getTransaction();

                $TransaccionProducto->setEstado("I");
                $TransaccionProducto->setExternoId($reference);
                $TransaccionProducto->setEstadoProducto($estado);
                $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO($Transaction);
                $TransaccionProductoMySqlDAO->update($TransaccionProducto);

                $TransprodLog = new TransprodLog();
                $TransprodLog->setTransproductoId($reference);
                $TransprodLog->setEstado($estado);
                $TransprodLog->setTipoGenera('A');
                $TransprodLog->setComentario(json_encode($code));
                $TransprodLog->setTValue(json_encode($result));
                $TransprodLog->setUsucreaId(0);
                $TransprodLog->setUsumodifId(0);

                $TransprodLog_id = $TransprodLogMysqlDAO->insert($TransprodLog);

                $rowsUpdate = 0;


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

                    if ($estado == "R" && $rowsUpdate > 0) {
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
            }
        }
    }

    /**
     * Realiza una solicitud HTTP POST utilizando cURL.
     *
     * @param array  $dataC Datos a enviar en el cuerpo de la solicitud.
     * @param string $urlC  URL del endpoint al que se realizará la solicitud.
     * @param string $keyC  Clave de autorización para la solicitud.
     * @param string $commerceC  Clave de autorización para la solicitud.
     *
     * @return string Respuesta del servidor en formato JSON.
     */
    public function requestConsulta($dataC, $urlC, $keyC, $commerceC)
    {
        $curl = new CurlWrapper($urlC);

        $headers = [
            'authorization: ' . $keyC,
            'Commerce: ' . $commerceC,
            'content-type: application/json'
        ];

        $curl->setOptionsArray([
            CURLOPT_URL => $urlC,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($dataC),
            CURLOPT_HTTPHEADER => $headers
        ]);

        $response = $curl->execute();
        return $response;
    }
    /**
     * Realiza una solicitud HTTP POST utilizando cURL.
     *
     * @param array  $data Datos a enviar en el cuerpo de la solicitud.
     * @param string $url  URL del endpoint al que se realizará la solicitud.
     * @param string $key  Clave de autorización para la solicitud.
     * @param string $commerce  Clave de autorización para la solicitud.
     *
     * @return string Respuesta del servidor en formato JSON.
     */
    public function request($data, $url, $key, $commerce)
    {
        $curl = new CurlWrapper($url);

        $headers = [
            'authorization: ' . $key,
            'Commerce: ' . $commerce,
            'content-type: application/json'
        ];

        $curl->setOptionsArray([
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => $headers
        ]);

        $response = $curl->execute();
        return $response;
    }
}
