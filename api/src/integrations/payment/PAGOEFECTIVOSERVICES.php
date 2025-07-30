<?php

/**
 * Clase para gestionar servicios de pago a través de PagoEfectivo.
 *
 * Este archivo contiene la implementación de métodos para crear solicitudes de pago
 * y realizar integraciones con el servicio de PagoEfectivo. Incluye lógica para manejar
 * transacciones, impuestos y comunicación con APIs externas.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @version    1.0.0
 * @since      2025-04-25
 */

namespace Backend\Integrations\payment;

use Exception;
use Backend\dto\Pais;
use AstroPayStreamline;
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\Clasificador;
use Backend\dto\TransprodLog;
use Backend\dto\MandanteDetalle;
use Backend\dto\UsuarioMandante;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransproductoDetalle;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransproductoDetalleMySqlDAO;
use Guzzle\Common\Exception\ExceptionCollection;

/**
 * Clase `PAGOEFECTIVOSERVICES`.
 *
 * Esta clase proporciona métodos para gestionar pagos a través del servicio PagoEfectivo.
 * Incluye funcionalidades para crear solicitudes de pago, manejar transacciones,
 * calcular impuestos y realizar integraciones con APIs externas.
 */
class PAGOEFECTIVOSERVICES
{
    /**
     * Constructor de la clase.
     *
     * Inicializa el entorno de configuración para determinar si se está en un entorno
     * de desarrollo o producción.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
        } else {
        }
    }

    /**
     * Crea una solicitud de pago a través de PagoEfectivo.
     *
     * @param Usuario  $Usuario  Objeto que representa al usuario.
     * @param Producto $Producto Objeto que representa el producto.
     * @param float    $valor    Monto de la transacción.
     * @param string   $urlOK    URL de redirección en caso de éxito.
     * @param string   $urlERROR URL de redirección en caso de error.
     *
     * @return object Respuesta en formato JSON con el estado de la solicitud.
     */
    public function createRequestPayment(Usuario $Usuario, Producto $Producto, $valor, $urlOK, $urlERROR)
    {
        $data = array();
        $data["success"] = false;
        $data["error"] = 1;

        try {
            $ConfigurationEnvironment = new ConfigurationEnvironment();
            if ($ConfigurationEnvironment->isDevelopment()) {
                require_once('pagoefectivodev/lib_pagoefectivo/code/PagoEfectivo.php');
                require_once('pagoefectivodev/lib_pagoefectivo/code/be/be_solicitud.php');
                //                use Backend\Integrations\payment\pagoefectivodev\lib_pagoefectivo\code\App_Service_PagoEfectivo;
                $paymentRequest = new \Backend\Integrations\payment\pagoefectivodev\lib_pagoefectivo\code\BEGenRequest();
                $pagoefectivo = new \Backend\Integrations\payment\pagoefectivodev\lib_pagoefectivo\code\App_Service_PagoEfectivo();
            } else {
                require_once(__DIR__ . '/../../imports/PagoEfectivo/lib_pagoefectivo/code/PagoEfectivo.php');
                require_once(__DIR__ . '/../../imports/PagoEfectivo/lib_pagoefectivo/code/be/be_solicitud.php');

                $pagoefectivo = new \App_Service_PagoEfectivo();
                $paymentRequest = new \BEGenRequest();
            }


            $Registro = new Registro("", $Usuario->usuarioId);


            $estado = 'A';
            $estado_producto = 'E';
            $tipo = 'T';

            $banco = 0;
            $pais = $Usuario->paisId;
            $usuario_id = $Usuario->usuarioId;
            $cedula = $Registro->cedula;
            $nombre = $Usuario->nombre;
            $email = $Usuario->login;
            $valor = $valor;
            $producto_id = $Producto->productoId;
            $moneda = $Usuario->moneda;
            $mandante = $Usuario->mandante;
            $descripcion = "Deposito";


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

            $TransproductoDetalle = new TransproductoDetalle();
            $TransproductoDetalle->transproductoId = $transproductoId;
            $TransproductoDetalle->tValue = json_encode(($data));

            $TransproductoDetalleMySqlDAO = new TransproductoDetalleMySqlDAO($Transaction);
            $TransproductoDetalleMySqlDAO->insert($TransproductoDetalle);


            date_default_timezone_set('America/Lima');
            $paymentRequest->_moneda = PE_MONEDA; // 1 soles - 2 dolares;
            $paymentRequest->_monto = number_format($valorTax, 2, '.', '');
            $paymentRequest->_medio_pago = PE_MEDIO_PAGO;
            $paymentRequest->_cod_servicio = PE_MERCHAND_ID;
            $paymentRequest->_numero_orden = $transproductoId;
            $paymentRequest->_concepto_pago = PE_COMERCIO_CONCEPTO_PAGO; // Debe ser menos de 20 d�gitos.
            $paymentRequest->_email_comercio = PE_EMAIL_PORTAL;
            $paymentRequest->_fecha_expirar = date('d/m/Y H:i:s', time() + ((int)PE_TIEMPO_EXPIRACION * 60 * 60)); //Este valor debe ser dinamico
            $paymentRequest->_data_adicional = "Canal Web";
            $paymentRequest->_usuario_id = $usuario_id;
            $paymentRequest->_usuario_tipodocumento = "DNI";
            $paymentRequest->_usuario_numerodocumento = $cedula;
            $paymentRequest->_usuario_alias = "Usuario" . $usuario_id;
            $paymentRequest->_usuario_nombre = $nombre;
            $paymentRequest->_usuario_apellidos = "";
            $paymentRequest->_usuario_email = $email;
            $paymentRequest->_usuario_localidad = 1;
            $paymentRequest->_usuario_provincia = 1;
            $paymentRequest->_usuario_pais = 1;

            if ($Usuario->mandante == 8) {
                date_default_timezone_set('America/Lima');
                $paymentRequest->_moneda = 2; // 1 soles - 2 dolares;
                $paymentRequest->_monto = number_format($valorTax, 2, '.', '');
                $paymentRequest->_medio_pago = PE_MEDIO_PAGO;
                $paymentRequest->_cod_servicio = PE_MERCHAND_ID;
                $paymentRequest->_numero_orden = $transproductoId;
                $paymentRequest->_concepto_pago = PE_COMERCIO_CONCEPTO_PAGO; // Debe ser menos de 20 d�gitos.
                $paymentRequest->_email_comercio = PE_EMAIL_PORTAL;
                $paymentRequest->_fecha_expirar = date('d/m/Y H:i:s', time() + ((int)PE_TIEMPO_EXPIRACION * 60 * 60)); //Este valor debe ser dinamico
                $paymentRequest->_data_adicional = "Canal Web";
                $paymentRequest->_usuario_id = $usuario_id;
                $paymentRequest->_usuario_tipodocumento = "DNI";
                $paymentRequest->_usuario_numerodocumento = $cedula;
                $paymentRequest->_usuario_alias = "Usuario" . $usuario_id;
                $paymentRequest->_usuario_nombre = $nombre;
                $paymentRequest->_usuario_apellidos = "";
                $paymentRequest->_usuario_email = $email;
                $paymentRequest->_usuario_localidad = 1;
                $paymentRequest->_usuario_provincia = 1;
                $paymentRequest->_usuario_pais = 1;
            }


            $paymentResponse = $pagoefectivo->GenerarCip($paymentRequest);

            $NumeroOrdenPago = intval($paymentResponse->CIP->NumeroOrdenPago);

            if ($paymentResponse->Estado == 1) {
                $OrdenID = $paymentResponse->idResSolPago;

                $respuesta = array();
                $respuesta["status"] = 1;
                $respuesta["orden"] = $OrdenID;
            } else {
                $apierror = true;

                $respuesta = array();
                $respuesta["status"] = 0;
                $respuesta["msg"] = json_decode($paymentResponse);
            }


            if ( ! $apierror) {
                $t_value = json_encode(array());

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

                $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
                $TransaccionProducto->setExternoId($NumeroOrdenPago);
                $TransaccionProductoMySqlDAO->update($TransaccionProducto);
                $TransaccionProductoMySqlDAO->getTransaction()->commit();

                $data = array();
                $data["success"] = true;
                $data["url"] = PE_WSGENPAGOIFRAME . '?Token=' . $paymentResponse->Token;
            }
        } catch (Exception $e) {
        }

        return json_decode(json_encode($data));
    }

    /**
     * Crea una solicitud de pago alternativa a través de PagoEfectivo.
     *
     * Este método utiliza un flujo diferente para generar la solicitud de pago,
     * incluyendo la autorización previa y el manejo de credenciales específicas.
     *
     * @param Usuario  $Usuario  Objeto que representa al usuario.
     * @param Producto $Producto Objeto que representa el producto.
     * @param float    $valor    Monto de la transacción.
     * @param string   $urlOK    URL de redirección en caso de éxito.
     * @param string   $urlERROR URL de redirección en caso de error.
     *
     * @return object Respuesta en formato JSON con el estado de la solicitud.
     */
    public function createRequestPayment2(Usuario $Usuario, Producto $Producto, $valor, $urlOK, $urlERROR)
    {
        ini_set('display_errors', 'OFF');

        $data = array();
        $data["success"] = false;
        $data["error"] = 1;

        try {
            $Registro = new Registro("", $Usuario->usuarioId);


            $estado = 'A';
            $estado_producto = 'E';
            $tipo = 'T';

            $banco = 0;
            $Mandante = new Mandante($Usuario->mandante);
            $Pais = new Pais($Usuario->paisId);
            $codigoPais = $Pais->prefijoCelular;
            $usuario_id = $Usuario->usuarioId;
            $cedula = $Registro->cedula;
            $nombre = $Registro->nombre1;
            $apellido = $Registro->apellido1;
            $celular = $Registro->celular;
            $email = $Usuario->login;
            $valor = $valor;
            $producto_id = $Producto->productoId;
            $moneda = $Usuario->moneda;
            $mandante = $Usuario->mandante;
            $descripcion = "Deposito";
            $lenguaje = $Usuario->idioma;


            if ($Usuario->paisId == '66') {
                $codigoPais = '593';
            }

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


            $TransproductoDetalle = new TransproductoDetalle();
            $TransproductoDetalle->transproductoId = $transproductoId;
            $TransproductoDetalle->tValue = json_encode(($data));

            $TransproductoDetalleMySqlDAO = new TransproductoDetalleMySqlDAO($Transaction);
            $TransproductoDetalleMySqlDAO->insert($TransproductoDetalle);

            date_default_timezone_set('America/Lima');


            $UTC = str_replace(" ", "T", date("Y-m-d H:i:s", time()));
            $UTC = $UTC . "-05:00";
            $this->method = "authorizations";

            $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);

            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
            $credentials = json_decode($SubproveedorMandantePais->getCredentials());

            $ID_SERVICE = $credentials->ID_SERVICE;
            $ACCESS_KEY = $credentials->ACCESS_KEY;
            $SECRET_KEY = $credentials->SECRET_KEY;
            $URL = $credentials->URL;

            $hashString = hash('sha256', $ID_SERVICE . "." . $ACCESS_KEY . "." . $SECRET_KEY . "." . $UTC, false);

            $array = array(
                "accessKey" => $ACCESS_KEY,
                "idservice" => $ID_SERVICE,
                "dateRequest" => $UTC,
                "hashString" => $hashString,
            );

            $return = $this->request($URL, $array, $this->method, array('Content-Type:application/json'));
            $return = json_decode($return);

            $tipoDocumento = $Registro->tipoDoc;

            //convertir el tipo de documento a los requeridos por el proveedor
            switch ($tipoDocumento) {
                case "E":
                    if ($Pais->iso == "PE") {
                        $tipoDocumento = "CE";
                    }
                    break;
                case "P": //OK

                    if ($Pais->iso == "EC") {
                        $tipoDocumento = "PAS";
                    } elseif ($Pais->iso == "CL") {
                        $tipoDocumento = "PP";
                    } elseif ($Pais->iso == "PE") {
                        $tipoDocumento = "PAS";
                    }
                    break;
                case "C": //OK
                    if ($Pais->iso == "CL") {
                        $tipoDocumento = "RUT";
                    } elseif ($Pais->iso == "PE") {
                        $tipoDocumento = "DNI";
                    } elseif ($Pais->iso == "EC") {
                        $tipoDocumento = "NAN";
                    }
                    break;
                default:
                    $tipoDocumento = "DNI";
                    break;
            }


            $this->method = "cips";
            if ($return != "") {
                $array = array(
                    "currency" => $moneda,
                    "amount" => number_format($valorTax, 2, '.', ''),
                    "transactionCode" => $transproductoId,
                    "dateExpiry" => str_replace(' ', 'T', date('Y-m-d H:i:s' . "-05:00", time() + ((int)5 * 60 * 60))), //Este valor debe ser dinamico
                    "additionalData" => $Mandante->descripcion,
                    "userEmail" => $email,
                    "userId" => $usuario_id,
                    "userName" => $nombre,
                    "userLastName" => $apellido,
                    "userDocumentType" => $tipoDocumento,
                    "userDocumentNumber" => $cedula,
                    "userPhone" => $celular,
                    "userCodeCountry" => "+" . $codigoPais,
                    "idService" => $ID_SERVICE,
                );
            } else {
                throw new Exception("No se autorizo", "11101");
            }

            $return = $this->request($URL, $array, $this->method, array('Content-Type:application/json', 'Accept-Language:' . $lenguaje, 'Origin: web', 'Authorization:' . "Bearer" . " " . $return->data->token));
            $return = json_decode($return);


            if ($return->code == 100) {
                $OrdenID = $return->data->cip;

                $respuesta = array();
                $respuesta["status"] = 201;
                $respuesta["orden"] = $OrdenID;

                $TransaccionProducto->setExternoId($OrdenID);
                $TransaccionProductoMySqlDAO->update($TransaccionProducto);
            } else {
                $apierror = true;

                $respuesta = array();
                $respuesta["status"] = 0;
            }


            if ( ! $apierror) {
                $t_value = json_encode(array());

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
                $data["url"] = $return->data->cipUrl;
            }
        } catch (Exception $e) {
        }

        return json_decode(json_encode($data));
    }

    /**
     * Realiza una solicitud HTTP a un servicio externo.
     *
     * @param string $URL    URL base del servicio.
     * @param array  $data   Datos a enviar en la solicitud.
     * @param string $method Método HTTP a utilizar (por ejemplo, POST).
     * @param array  $header Encabezados HTTP adicionales.
     *
     * @return string Respuesta del servicio en formato JSON.
     */
    public function request($URL, $data, $method, $header = array())
    {
        $data = json_encode($data);
        $ch = curl_init($URL . $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $result = (curl_exec($ch));


        return ($result);
    }
}
