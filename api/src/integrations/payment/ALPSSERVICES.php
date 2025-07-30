<?php

/**
 * Clase ALPSSERVICES
 *
 * Esta clase proporciona servicios de integración con el proveedor ALPS.
 * Incluye métodos para obtener la URL de lanzamiento de la pasarela de pago.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-07-09
 */

namespace Backend\Integrations\payment;

use Exception;
use \CurlWrapper;
use Backend\dto\Pais;
use Backend\dto\Usuario;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\Clasificador;
use Backend\dto\TransprodLog;
use Backend\dto\MandanteDetalle;
use Backend\dto\ProductoDetalle;
use Backend\dto\TransaccionProducto;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\dto\SubproveedorMandantePais;
use Backend\mysql\TransaccionProductoMySqlDAO;

/**
 * Clase ALPSSERVICES
 *
 * Esta clase proporciona servicios de integración con ALPS, incluyendo la creación de solicitudes de pago
 * y la realización de peticiones HTTP. Contiene métodos para manejar transacciones y configurar el entorno
 * según el ambiente de desarrollo o producción.
 */
class ALPSSERVICES
{
    /**
     * Crea una solicitud de pago para un usuario y producto específicos.
     * 
     * @param Usuario $usuario Objeto que representa al usuario que realiza el pago.
     * @param Producto $producto objeto que representa el producto asociado al pago.
     * @param float $valor  Monto del pago.
     * @param string $urlOK URL de redirección  del pago exitoso.
     * @param string $urlERROR URL de redirección del pago erroneo.
     * 
     * @return object retorna un objeto que contiene la direccion web de la pasarela en la propiedad url
     * 
     * @throws Exception Si hay un error en el lanzamiento del juego
     */
    public function createRequestPayment(Usuario $usuario, Producto $producto, float $valor, string $urlOK, string $urlERROR)
    {
        $registro = new Registro("", $usuario->usuarioId);
        $pais = new Pais($usuario->paisId);

        try {
            $Clasificador = new Clasificador("", "TAXDEPOSIT");
            $MandanteDetalle = new MandanteDetalle("", $usuario->mandante, $Clasificador->getClasificadorId(), $usuario->paisId, 'A');
            $taxedValue = $MandanteDetalle->valor;
        } catch (Exception $e) {
            $taxedValue = 0;
        }

        $totalTax = $valor * ($taxedValue / 100);
        $valorTax = $valor * (1 + $taxedValue / 100);

        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
        $transaction = $TransaccionProductoMySqlDAO->getTransaction();

        $transaccionProducto = new TransaccionProducto();
        $transaccionProducto->setProductoId($producto->productoId);
        $transaccionProducto->setUsuarioId($usuario->usuarioId);
        $transaccionProducto->setValor($valor);
        $transaccionProducto->setImpuesto($totalTax);
        $transaccionProducto->setEstado('A');
        $transaccionProducto->setTipo('T');
        $transaccionProducto->setEstadoProducto('E');
        $transaccionProducto->setMandante($usuario->mandante);
        $transaccionProducto->setFinalId(0);
        $transaccionProducto->setExternoId(0);
        $transproductoId = $TransaccionProductoMySqlDAO->insert($transaccionProducto);

        $subproveedorMandantePais = new SubproveedorMandantePais('', $producto->subproveedorId, $usuario->mandante, $usuario->paisId);
        $credentials = json_decode($subproveedorMandantePais->getCredentials());

        $url = $credentials->URL;
        $public_key = $credentials->PUBLIC_KEY;
        $secureKey = $credentials->SECURE_KEY;

        try {
            $productoDetalle = new ProductoDetalle('', $producto->productoId, 'GAMEID');
        } catch (Exception $err) {
        }

        $filter = !empty($productoDetalle) ? $productoDetalle->getPValue() : '';

        $time = date("Y-m-d H:i:s");
        $currency = $usuario->moneda;
        $timeExpired = 120;
        $channel = $producto->externoId;
        $countryName = $usuario->moneda;
        $asignature = $public_key . $time . $valorTax . $currency . $transproductoId . $timeExpired . $urlOK . $urlERROR . $channel . $secureKey;
        $asignature = hash("sha256", $asignature);

        $data = array();
        $data['currency'] = $currency;
        $data['time_expired'] = $timeExpired;
        $data['signature'] = $asignature;
        $data['public_key'] = $public_key;
        $data['time'] = $time;
        $data['channel'] = $channel;
        $data['amount'] = $valorTax;
        $data['trans_id'] = $transproductoId;
        $data['url_ok'] = $urlOK;
        $data['url_error'] = $urlERROR;
        $data['shopper_information'] = json_encode([
            'name_shopper' => $registro->nombre1 . ' ' . $registro->nombre2,
            'name_shopper' => $registro->nombre1 . ' ' . $registro->nombre2,
            'last_name_Shopper' => $registro->apellido1 . ' ' . $registro->apellido2,
            'type_doc_identi' => $registro->tipoDoc,
            'Num_doc_identi' => $registro->cedula,
            'email' => $registro->email,
            'country_code' => $pais->prefijoCelular,
            'Phone' => $registro->celular,
            'country' => $countryName
        ]);

        if (!empty($filter)) {
            $data['filter_by'] = $filter;
        }

        $responsePayment = $this->getPayUrl($data, $url);
        $response = json_decode($responsePayment);

        $dataR = array();
        if (json_last_error() === JSON_ERROR_NONE) {
            $dataR["success"] = false;
            $dataR["url"] = '';
        } else {
            $TransprodLog = new TransprodLog();
            $TransprodLog->setTransproductoId($transproductoId);
            $TransprodLog->setEstado('E');
            $TransprodLog->setTipoGenera('A');
            $TransprodLog->setComentario('Envio Solicitud de deposito');
            $TransprodLog->setTValue(json_encode($responsePayment));
            $TransprodLog->setUsucreaId(0);
            $TransprodLog->setUsumodifId(0);

            $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($transaction);
            $TransprodLogMySqlDAO->insert($TransprodLog);
            $transaction->commit();

            $dataR["success"] = true;
            $dataR["url"] = $responsePayment;
        }

        return json_decode(json_encode($dataR));
    }

    /**
     * Esta funcion obtiene la URL de la pasarela de pago
     * 
     * @param array $data es un array que contiene la data a enviar a la pasarela
     * @param string $url cadena que representa la url que debemos consumir para obtener la la url de la pasarela.
     * 
     * @return string Retorna la respuesta de la pasarela al solicitar la url
     */
    public function getPayUrl($data, $url)
    {
        $curl = new CurlWrapper($url);
        $curl->setOptionsArray(array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 300,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            )
        ));

        $response = $curl->execute();
        return $response;
    }
}
