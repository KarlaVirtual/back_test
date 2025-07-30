<?php

/**
 * Clase VISASERVICES
 *
 * Esta clase proporciona servicios para la integración de pagos con Visa.
 * Incluye métodos para crear solicitudes de pago y manejar transacciones relacionadas.
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
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\Clasificador;
use Backend\dto\TransprodLog;
use Backend\dto\MandanteDetalle;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransproductoDetalle;
use Backend\integrations\payment\Visa;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransproductoDetalleMySqlDAO;

/**
 * Clase que proporciona servicios para la integración de pagos con Visa.
 * Incluye métodos para crear solicitudes de pago y manejar transacciones relacionadas.
 */
class VISASERVICES
{
    /**
     * URL para confirmar transacciones.
     * Se inicializa dependiendo del entorno (desarrollo o producción).
     *
     * @var string
     */
    private $URLConfirm = '';

    /**
     * URL de confirmación en entorno de desarrollo.
     *
     * @var string
     */
    private $URLConfirm_dev = 'https://apidevintegrations.virtualsoft.tech/integrations/payment/visa/api/confirm/';

    /**
     * URL de confirmación en entorno de producción.
     *
     * @var string
     */
    private $URLConfirm_prod = 'https://visa.virtualsoft.tech/confirm/';

    /**
     * URL para depósitos.
     * Se construye dinámicamente según el mandante.
     *
     * @var string
     */
    private $URLDEPOSIT = '';

    /**
     * Constructor de la clase.
     * Configura la URL de confirmación según el entorno actual.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->URLConfirm = $this->URLConfirm_dev;
        } else {
            $this->URLConfirm = $this->URLConfirm_prod;
        }
    }

    /**
     * Crea una solicitud de pago para un usuario y un producto específicos.
     *
     * @param Usuario  $Usuario  Objeto que representa al usuario que realiza el pago.
     * @param Producto $Producto Objeto que representa el producto a pagar.
     * @param float    $valor    Monto del pago.
     * @param string   $urlOK    URL de redirección en caso de éxito.
     * @param string   $urlERROR URL de redirección en caso de error.
     *
     * @return object Respuesta con los datos de la transacción y configuración de Visa.
     * @throws Exception Si ocurre un error durante el proceso.
     */
    public function createRequestPayment(Usuario $Usuario, Producto $Producto, $valor, $urlOK, $urlERROR)
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        $Registro = new Registro("", $Usuario->usuarioId);
        $Mandante = new Mandante($Usuario->mandante);

        $rules = [];
        array_push($rules, array("field" => "producto.estado", "data" => "A", "op" => "eq"));
        array_push($rules, array("field" => "producto_detalle.producto_id", "data" => "$Producto->productoId", "op" => "eq"));

        $data = array();
        $data["success"] = false;
        $data["error"] = 1;

        $estado = 'A';
        $estado_producto = 'P';
        $tipo = 'T';

        $usuario_id = $Usuario->usuarioId;
        $valor = $valor;
        $producto_id = $Producto->productoId;
        $mandante = $Usuario->mandante;

        if ($Mandante->baseUrl != '') {
            $this->URLDEPOSIT = $Mandante->baseUrl . "gestion/deposito";
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
        $TransproductoDetalle->tValue = json_encode($data);

        $TransproductoDetalleMySqlDAO = new TransproductoDetalleMySqlDAO($Transaction);
        $TransproductoDetalleMySqlDAO->insert($TransproductoDetalle);

        $sessiontokenEncrypt = $ConfigurationEnvironment->encrypt($transproductoId);

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
        if ($ConfigurationEnvironment->isDevelopment()) {
            $baseUrl = "https://apidev.virtualsoft.tech/integrations/payment/visa/api/?id=";
        } else {
            $baseUrl = "https://visa.virtualsoft.tech/?id=";
        }

        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Producto->subproveedorId, $Usuario->mandante, $Usuario->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $url = $Credentials->URL;
        $merchant = $Credentials->MERCHANT;
        $Key = $Credentials->KEY;
        $secretKey = $Credentials->SECRET_KEY;
        $user = $Credentials->USER;
        $password = $Credentials->PASSWORD;

        $Visa = new Visa($transproductoId . "", "", $valorTax, $transproductoId, $url, $merchant, $Key, $secretKey, $user, $password);
        $token = $Visa->createToken();
        $response = $Visa->Authorization($token);
        $responsejson = json_decode($response);

        $dataVisa = array();
        $dataVisa['actionForm'] = $this->URLConfirm . '?tp=' . $sessiontokenEncrypt . '&pn=' . $transproductoId;
        $dataVisa['sessiontoken'] = $responsejson->sessionKey;
        $dataVisa['channel'] = 'web';
        $dataVisa['merchantid'] = $Visa->getMerchantId();
        $dataVisa['merchantlogo'] = 'https://images.virtualsoft.tech/site/doradobet/doradobet-borde-azul.png';
        $dataVisa['formbuttoncolor'] = "#D80000";
        $dataVisa['purchasenumber'] = $transproductoId;
        $dataVisa['amount'] = $valorTax;
        $dataVisa['cardholdername'] = $Registro->nombre1 . ' ' . $Registro->nombre2;
        $dataVisa['cardholderlastname'] = $Registro->apellido1 . ' ' . $Registro->apellido2;
        $dataVisa['cardholderemail'] = $Registro->email;
        $dataVisa['expirationminutes'] = "20";
        $dataVisa['timeouturl'] = $this->URLDEPOSIT;

        $data = array();
        $data["success"] = true;
        $data["url"] = $baseUrl . $ConfigurationEnvironment->encrypt($transproductoId);
        $data["isVisa"] = true;
        $data["dataVisa"] = $dataVisa;

        return json_decode(json_encode($data));
    }
}
