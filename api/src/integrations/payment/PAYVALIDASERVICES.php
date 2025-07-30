<?php

/**
 * Clase PAYVALIDASERVICES
 *
 * Esta clase proporciona servicios de integración con la API de Payvalida para la creación de solicitudes de pago
 * y otras operaciones relacionadas. Incluye métodos para configurar el entorno, realizar solicitudes de pago
 * y manejar transacciones.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-25
 */

namespace Backend\Integrations\payment;

use Backend\dto\SubproveedorMandantePais;
use \CurlWrapper;
use Exception;
use Backend\dto\Usuario;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\Clasificador;
use Backend\dto\TransprodLog;
use Backend\dto\MandanteDetalle;
use Backend\dto\ProductoDetalle;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransproductoDetalle;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransproductoDetalleMySqlDAO;

/**
 * Clase PAYVALIDASERVICES
 *
 * Proporciona métodos para la integración con la API de Payvalida, incluyendo
 * la creación de solicitudes de pago y el manejo de transacciones.
 */
class PAYVALIDASERVICES
{
     /**
     * Constructor de la clase.
     *
     * Configura las URLs y el hash fijo dependiendo del entorno (desarrollo o producción).
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
     * Este metodo genera una solicitud de pago utilizando los datos del usuario y del producto,
     * calcula impuestos, registra la transacción en la base de datos y envía la solicitud a la API de Payvalida.
     *
     * @param Usuario  $Usuario  Objeto que contiene los datos del usuario.
     * @param Producto $Producto Objeto que contiene los datos del producto.
     * @param float    $valor    Monto de la transacción.
     * @param string   $urlOK    URL de redirección en caso de éxito.
     * @param string   $urlERROR URL de redirección en caso de error.
     *
     * @return object Respuesta de la API de Payvalida.
     */
    public function createRequestPayment(Usuario $Usuario, Producto $Producto, $valor, $urlOK, $urlERROR)
    {
        $Registro = new Registro("", $Usuario->usuarioId);

        $SubproveedorMandantePais = new SubproveedorMandantePais("", $Producto->subproveedorId, $Usuario->mandante, $Usuario->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $FIXED_HASH = $Credentials->FIXED_HASH;
        $URL = $Credentials->URL;

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
                $pais = 348;
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

        $data = array();

        $data['merchant'] = "doradobet_pe";
        $data['email'] = $email;
        $data['country'] = $pais;
        $data['order'] = $transproductoId;
        $data['reference'] = $transproductoId;
        $data['money'] = $moneda;
        $data['amount'] = (string)$valorTax;
        $data['description'] = $descripcion;
        $data['language'] = "es";
        $data['recurrent'] = false;
        $data['expiration'] = Date('d/m/Y', strtotime("+3 days"));
        $data['iva'] = '0';

        $method = "";
        $data['method'] = $method;

        $data['checksum'] = hash('sha512', $email . $pais . $transproductoId . $moneda . $valorTax . $FIXED_HASH);

        $data = json_encode($data);

        $service_url = $URL;

        $curl = new CurlWrapper($service_url);

        $curl->setOptionsArray([
            CURLOPT_URL => $service_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json'
            ]
        ]);

        $response = $curl->execute();

        $Result = json_decode($response);

        if ($Result->DESC == "OK") {
            $t_value = json_encode($Result);

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
            $data["url"] = 'https://' . $Result->DATA->checkout;
            $data["method"] = "newtab";
        }

        return json_decode(json_encode($data));
    }
}
