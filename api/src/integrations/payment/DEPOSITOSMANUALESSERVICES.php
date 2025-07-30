<?php

/**
 * Clase DEPOSITOSMANUALESSERVICES
 *
 * Este archivo contiene la implementación de servicios relacionados con depósitos manuales.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-04-23
 */

namespace Backend\integrations\payment;

use Exception;
use Backend\dto\Pais;
use Backend\dto\Ciudad;
use Backend\dto\Usuario;
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\Proveedor;
use Backend\dto\UsuarioBanco;
use Backend\dto\Clasificador;
use Backend\dto\Departamento;
use Backend\dto\PaisMandante;
use Backend\dto\Subproveedor;
use Backend\dto\TransprodLog;
use Backend\dto\UsuarioToken;
use Backend\dto\MandanteDetalle;
use Backend\dto\TransaccionProducto;
use Backend\dto\SubproveedorMandante;
use Backend\dto\TransproductoDetalle;
use Backend\dto\UsuarioTarjetacredito;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransproductoDetalleMySqlDAO;
use Backend\mysql\UsuarioTarjetacreditoMySqlDAO;

/**
 * Clase DEPOSITOSMANUALESSERVICES
 *
 * Este archivo contiene la implementación de servicios relacionados con depósitos manuales.
 * Proporciona métodos para crear solicitudes de pago y gestionar transacciones asociadas.
 */
class DEPOSITOSMANUALESSERVICES
{


    /**
     * Crea una solicitud de pago y gestiona las transacciones asociadas.
     *
     * @param Usuario  $Usuario               Objeto que representa al usuario que realiza la solicitud.
     * @param Producto $Producto              Objeto que representa el producto asociado a la transacción.
     * @param float    $valor                 Monto del depósito.
     * @param string   $urlSuccess            URL a la que se redirige en caso de éxito.
     * @param string   $urlFailed             URL a la que se redirige en caso de fallo.
     * @param string   $numberOperation       Opcional Número de operación externa.
     * @param string   $date                  Opcional Fecha de la operación.
     * @param UsuarioBanco|null $UsuarioBanco Objeto que representa la cuenta bancaria del usuario, si aplica.
     *
     * @return object Objeto JSON con el estado de la operación y el ID de la transacción.
     */
    public function createRequestPayment(Usuario $Usuario, Producto $Producto, $valor, $urlSuccess, $urlFailed, $numberOperation = "", $date = "", ?UsuarioBanco $UsuarioBanco = null)
    {
        // Inicializa el subproveedor y su relación con el mandante.
        $Subproveedor = new Subproveedor("", "DEPOSITOSMANUALES");
        $SubproveedorMandante = new SubproveedorMandante($Subproveedor->subproveedorId, $Usuario->mandante);

        // Obtiene detalles del subproveedor mandante.
        $detalle = json_decode($SubproveedorMandante->detalle);
        $this->username = $detalle->username;

        // Crea un registro asociado al usuario.
        $Registro = new Registro('', $Usuario->usuarioId);

        // Inicializa datos de respuesta.
        $data = array();
        $data["success"] = false;
        $data["error"] = 1;

        // Define variables relacionadas con el estado y tipo de transacción.
        $estado = 'A';
        $estado_producto = 'E';
        $tipo = 'T';

        // Asigna datos del usuario y producto a variables locales.
        $pais = $Usuario->paisId;
        $usuario_id = $Usuario->usuarioId;
        $cedula = $Registro->cedula;
        $nombre = $Usuario->nombre;
        $email = $Usuario->login;
        $apellido = $Registro->apellido1;
        $valor = $valor;
        $producto_id = $Producto->productoId;
        $extID = $Producto->externoId;
        $moneda = $Usuario->moneda;
        $mandante = $Usuario->mandante;
        $descripcion = "Deposito";
        $iso = "";
        $celular = $Usuario->celular;
        $tipoDocumento = $Registro->tipoDoc;
        $genero = $Registro->sexo;
        $date = date("Y-m-d H:i:s");

        // Crea instancias de producto y país.
        $producto = new Producto($producto_id);
        $Pais = new Pais($Usuario->paisId);

        // Inicia una transacción en la base de datos.
        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
        $Transaction = $TransaccionProductoMySqlDAO->getTransaction();
        $Transaction->getConnection()->beginTransaction();

        // Calcula el impuesto asociado al depósito.
        try {
            $Clasificador = new Clasificador("", "TAXDEPOSIT");
            $MandanteDetalle = new MandanteDetalle("", $Usuario->mandante, $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
            $taxedValue = $MandanteDetalle->valor;
        } catch (Exception $e) {
            $taxedValue = 0;
        }

        $totalTax = $valor * ($taxedValue / 100);
        $valorTax = $valor * (1 + $taxedValue / 100);

        // Crea una transacción de producto.
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
        $TransaccionProducto->setExternoId($numberOperation);
        if (!empty($UsuarioBanco)) $TransaccionProducto->setUsubancoId($UsuarioBanco->usubancoId);
        $transproductoId = $TransaccionProductoMySqlDAO->insert($TransaccionProducto);

        // Registra un log de la transacción.
        $TransprodLog = new TransprodLog();
        $TransprodLog->setTransproductoId($transproductoId);
        $TransprodLog->setEstado('E');
        $TransprodLog->setTipoGenera('A');
        $TransprodLog->setComentario('Envio Solicitud de deposito' . ' ' . $date);
        $TransprodLog->setTValue(json_encode($numberOperation));
        $TransprodLog->setUsucreaId(0);
        $TransprodLog->setUsumodifId(0);

        $TransprodLogMySqlDAO = new TransprodLogMySqlDAO($Transaction);
        $TransprodLogMySqlDAO->insert($TransprodLog);

        // Actualiza la transacción y confirma la operación.
        $TransaccionProducto->setExternoId($numberOperation);
        $TransaccionProductoMySqlDAO->update($TransaccionProducto);
        $Transaction->commit();

        // Prepara la respuesta de éxito.
        $data = array();
        $data["success"] = true;
        $data["transactionId"] = $transproductoId;

        return json_decode(json_encode($data));
    }

}
?>

