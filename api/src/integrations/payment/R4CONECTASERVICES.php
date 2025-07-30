<?php

/**
 * Clase para gestionar servicios de integración con R4CONECTA.
 *
 * Este archivo contiene la implementación de la clase `R4CONECTASERVICES`,
 * que permite realizar operaciones relacionadas con pagos a través de la API de R4CONECTA.
 *
 * @category   Red
 * @package    API
 * @subpackage Integraciones
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-23
 */

namespace Backend\integrations\payment;

use Exception;
use \CurlWrapper;
use Backend\dto\Pais;
use Backend\dto\Ciudad;
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\Proveedor;
use Backend\dto\Clasificador;
use Backend\dto\Subproveedor;
use Backend\dto\TransprodLog;
use Backend\dto\UsuarioToken;
use Backend\dto\MandanteDetalle;
use Backend\dto\UsuarioMandante;
use Backend\dto\TransaccionProducto;
use Backend\dto\SubproveedorMandante;
use Backend\dto\TransproductoDetalle;
use Backend\dto\UsuarioTarjetacredito;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransproductoDetalleMySqlDAO;
use Backend\mysql\UsuarioTarjetacreditoMySqlDAO;
use Backend\Integrations\payment\CLUBPAGOSERVICES;

/**
 * Clase para gestionar servicios de integración con R4CONECTA.
 *
 * Esta clase proporciona métodos para crear solicitudes de pago y manejar la autenticación
 * con la API de R4CONECTA.
 */
class R4CONECTASERVICES
{

    /**
     * Constructor de la clase R4CONECTASERVICES.
     *
     * Inicializa las propiedades del usuario y configura el proveedor y el entorno.
     * 
     * @param bool  $ConfigurationEnvironment  Indica si es modo desarrollo o producción.
     *
     */

    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        if ($ConfigurationEnvironment->isDevelopment()) {
        } else {
        }
    }

    /**
     * Crea una solicitud de pago con un enlace de checkout.
     *
     * @param Usuario  $idUsuario    Objeto del usuario.
     * @param Producto $Producto   Objeto del producto.
     * @param float    $valor      Valor de la transacción.
     * @param string   $urlSuccess URL de éxito.
     * @param string   $urlFailed  URL de fallo.
     * @param string   $cancel_url URL de cancelación.
     *
     * @return array Respuesta con el estado de la operación.
     */
    public function createRequestPayment($idUsuario, $valor)
    {
        try {

            $Proveedor = new Proveedor("", "R4CONECTA");
            $Usuario = new Usuario();

            $rules = array();

            array_push($rules, array("field" => "registro.cedula", "data" => $idUsuario, "op" => "eq"));
            array_push($rules, array("field" => "usuario.mandante", "data" => "21", "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);

            $SkeepRows = 0;
            $MaxRows = 1;

            $usuarios = $Usuario->getUsuariosCustom("usuario.usuario_id,usuario.nombre,pais.pais_nom,usuario.moneda,registro.cedula ", "usuario.fecha_crea", "desc", $SkeepRows, $MaxRows, $json, true);

            $usuarios = json_decode($usuarios);

            $usuario = $usuarios->data[0]->{'usuario.usuario_id'};

            $Usuario = new Usuario($usuario);
            $Producto = new Producto("", "r4conecta", $Proveedor->proveedorId);

            $mandante = $Usuario->mandante;

            $estado = 'A';
            $estado_producto = 'E';
            $tipo = 'T';
            $usuario_id = $Usuario->usuarioId;

            $producto_id = $Producto->productoId;

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

            $Result = array(
                "abono" => true,
                "transproductoId" => $transproductoId
            );

            $TransprodLog = new TransprodLog();
            $TransprodLog->setTransproductoId($transproductoId);
            $TransprodLog->setEstado('E');
            $TransprodLog->setTipoGenera('A');
            $TransprodLog->setComentario('Envio Solicitud de deposito');
            $TransprodLog->setTValue(json_encode($Result));
            $TransprodLog->setUsucreaId(0);
            $TransprodLog->setUsumodifId(0);

            $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
            $TransprodLogMySqlDAO->insert($TransprodLog);

            $Transaction->commit();
        } catch (Exception $e) {
            $Result = array(
                "abono" => false
            );
        }

        return json_encode($Result);
    }

    /**
     * Crea una solicitud de pago con un enlace de checkout.
     *
     * @param Usuario  $IdCliente    Objeto del usuario.
     *
     * @return array Respuesta con el estado de la operación.
     */
    public function R4consulta($IdCliente)
    {
        $Usuario = new Usuario();
        
        $rules = array();
        array_push($rules, array("field" => "registro.cedula", "data" => $IdCliente, "op" => "eq"));
        array_push($rules, array("field" => "usuario.mandante", "data" => "21", "op" => "eq"));

        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);

        $SkeepRows = 0;
        $MaxRows = 1;
        $usuarios = $Usuario->getUsuariosCustom("usuario.usuario_id,usuario.nombre,pais.pais_nom,usuario.moneda,registro.cedula", "usuario.fecha_crea", "desc", $SkeepRows, $MaxRows, $json, true);

        $usuarios = json_decode($usuarios);
        $cedula = $usuarios->data[0]->{'registro.cedula'};
        $status = false;

        if ($cedula != null && $cedula  != "") {
            $status = true;
        }

        $return = array(
            "status" => $status
        );

        return json_encode($return);
    }
}
