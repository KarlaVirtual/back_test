<?php

/**
 * Clase para gestionar integraciones de pagos con PayPhone.
 *
 * Este archivo contiene la clase `INTERNSERVICES`, que proporciona métodos para
 * realizar solicitudes de pago y confirmaciones de transacciones utilizando la API de PayPhone.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @version    1.0.0
 * @since      2025-04-23
 */

namespace Backend\integrations\payment;

use Backend\dto\SubproveedorMandantePais;
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
 * Clase `INTERNSERVICES` para gestionar integraciones de pagos con PayPhone.
 * Proporciona métodos para realizar solicitudes de pago y confirmaciones de transacciones.
 */
class INTERNSERVICES
{

    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
        } else {
        }
    }

    /**
     * Crea una solicitud de pago utilizando los datos del usuario y el producto.
     *
     * Este método realiza varias operaciones, incluyendo la creación de registros
     * de transacciones, el cálculo de impuestos, y la generación de URLs para la
     * confirmación del pago. También maneja configuraciones específicas para
     * entornos de desarrollo y producción.
     *
     * @param Usuario  $Usuario  Objeto que contiene los datos del usuario.
     * @param Producto $Producto Objeto que contiene los datos del producto.
     * @param float    $valor    Monto del pago.
     * @param string   $urlOK    URL a la que se redirige en caso de éxito.
     * @param string   $urlERROR URL a la que se redirige en caso de error.
     *
     * @return object Objeto JSON con los datos de la solicitud de pago.
     */
    public function createRequestPayment(Usuario $Usuario, Producto $Producto, $valor, $urlOK, $urlERROR)
    {
        $SubproveedorMandantePais = new SubproveedorMandantePais("", $Producto->subproveedorId, $Usuario->mandante, $Usuario->paisId);
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
        }

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

        return json_decode(json_encode($data));
    }
}
