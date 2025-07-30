<?php

/**
 * Clase `PAYPHONESERVICES` para gestionar la integración con el servicio de pagos PayPhone.
 *
 * Este archivo contiene la implementación de la clase `PAYPHONESERVICES`, que permite realizar
 * solicitudes de pago, confirmaciones y otras operaciones relacionadas con la integración
 * del servicio de pagos PayPhone.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-25
 */

namespace Backend\Integrations\payment;

use Backend\dto\Mandante;
use Backend\dto\Subproveedor;
use Backend\dto\SubproveedorMandantePais;
use \CurlWrapper;
use Exception;
use Backend\dto\Usuario;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\Clasificador;
use Backend\dto\TransprodLog;
use Backend\dto\MandanteDetalle;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransproductoDetalle;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransproductoDetalleMySqlDAO;

/**
 * Clase `PAYPHONESERVICES`
 *
 * Esta clase gestiona la integración con el servicio de pagos PayPhone,
 * proporcionando métodos para crear solicitudes de pago, confirmar transacciones
 * y realizar otras operaciones relacionadas.
 */
class PAYPHONESERVICES
{
    /**
     * Constructor de la clase `PAYPHONESERVICES`.
     *
     * Inicializa las configuraciones del entorno (desarrollo o producción)
     * y establece las URLs y tokens correspondientes.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
        } else {
        }
    }

    /**
     * Crea una solicitud de pago.
     *
     * @param Usuario  $Usuario  Objeto del usuario que realiza el pago.
     * @param Producto $Producto Objeto del producto a pagar.
     * @param float    $valor    Valor del pago.
     * @param string   $urlOK    URL de redirección en caso de éxito.
     * @param string   $urlERROR URL de redirección en caso de error.
     *
     * @return object Respuesta en formato JSON con los datos de la solicitud.
     */
    public function createRequestPayment(Usuario $Usuario, Producto $Producto, $valor, $urlOK, $urlERROR)
    {
        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $Usuario->mandante, $Usuario->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $Registro = new Registro("", $Usuario->usuarioId);

        $MaxRows = 1000;
        $OrderedItem = 1;
        $SkeepRows = 0;


        $rules = [];
        array_push($rules, array("field" => "producto.estado", "data" => "A", "op" => "eq"));
        array_push($rules, array("field" => "producto_detalle.producto_id", "data" => "$Producto->productoId", "op" => "eq"));


        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json2 = json_encode($filtro);


        $data = array();
        $data["success"] = false;
        $data["error"] = 1;


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

        switch ($pais) {
            case "173":
                $CountryCode = "PER";
                break;
            case "66":
                $CountryCode = "ECU";
                break;
            case "2":
                $CountryCode = "NIC";
                break;
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

        $baseUrl = "";
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $baseUrl = "http://localhost/BackendVirtualsoftDEV/api/partner_api/cases/payment/PayPhone?id=";
            $baseUrl = "https://admincert.virtualsoft.tech/api/api/partner_api/Payment/PayPhone?id=";
        } else {
            $baseUrl = "https://partnerapi.virtualsoft.tech/Payment/PayPhone?id=";
        }

        $data = array();
        $data["success"] = true;
        $data["url"] = $baseUrl . $ConfigurationEnvironment->encrypt($transproductoId);
        $data["target"] = '_blank';


        if ($Usuario->usuarioId == 73818 && false) {
            $URLConfirm = 'https://admincert.virtualsoft.tech/api/api/integrations/payment/payphone/api/confirm/';
            $URLJS = $Credentials->URLJS;
            $Identificador = $Credentials->IDENTIFICADOR;
            $IdClient = $Credentials->ID_CLIENT;
            $SecretKey = $Credentials->SECRET_KEY;
            $token = $Credentials->TOKEN_DEV;

            $data = array();
            $data["success"] = true;
            $data["payphoneJS"] = $URLJS . "?appId=" . $Identificador;
            $data["payphoneToken"] = $token;
            $data["payphoneJson"] = array(
                'amount' => intval(($valorTax) * 100),
                'amountWithoutTax' => intval(($valorTax) * 100),
                'clientTransactionId' => $TransaccionProducto->transproductoId,
                'email' => $Registro->getEmail(),
                'documentId' => $Registro->getCedula()
            );
        }
        return json_decode(json_encode($data));
    }

    /**
     * Confirma una transacción de pago.
     *
     * @param string $ID                  Identificador de la transacción.
     * @param string $ClientTransactionID Identificador del cliente para la transacción.
     *
     * @return string Respuesta de la confirmación.
     */
    public function confirm($ID, $ClientTransactionID)
    {
        $data = array(
            "id" => $ID,
            "clientTxId" => $ClientTransactionID
        );

        $transaccion_id = $ClientTransactionID;

        $TransaccionProducto = new TransaccionProducto($transaccion_id);
        $Producto = new Producto($TransaccionProducto->productoId);
        $Usuario = new Usuario($TransaccionProducto->usuarioId);

        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $Usuario->mandante, $Usuario->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $TOKEN_PROD = $Credentials->TOKEN_PROD;
        $URL = $Credentials->URL;

        $data = json_encode($data);
        $response = $this->request($data, $TOKEN_PROD, $URL);

        if ($response == '') {
            $TOKEN = $TOKEN_PROD;
            $response = $this->request($data, $TOKEN, $URL);
        }
        try {
            if ($response != '' && json_decode($response) != null && (json_decode($response)->errorCode == 3262 || json_decode($response)->errorCode == 20)) {
                $TOKEN = $TOKEN_PROD;
                $response = $this->request($data, $TOKEN, $URL);
            }
        } catch (\Exception $e) {
        }
        return $response;
    }

    /**
     * Realiza una solicitud HTTP POST a la URL especificada con los datos proporcionados.
     *
     * @param string $data  Datos a enviar en formato JSON.
     * @param string $TOKEN Token de autorización.
     * @param string $URL   URL del servicio al que se realiza la solicitud.
     *
     * @return string Respuesta del servidor.
     */
    public function request($data, $TOKEN, $URL)
    {

        $curl = new CurlWrapper($URL);

        $curl->setOptionsArray([
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $TOKEN,
                'Content-Type: application/json'
            ]
        ]);

        $result = $curl->execute();

        return $result;
    }
}
