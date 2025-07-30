<?php

use Backend\dto\ApiTransaction;
use Backend\dto\Area;
use Backend\dto\Bono;
use Backend\dto\BonoDetalle;
use Backend\dto\BonoInterno;
use Backend\dto\BonoLog;
use Backend\dto\Cargo;
use Backend\dto\Categoria;
use Backend\dto\CategoriaProducto;
use Backend\dto\CentroCosto;
use Backend\dto\Clasificador;
use Backend\dto\CodigoPromocional;
use Backend\dto\Competencia;
use Backend\dto\CompetenciaPuntos;
use Backend\dto\Concepto;
use Backend\dto\Concesionario;
use Backend\dto\Consecutivo;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\ContactoComercial;
use Backend\dto\ContactoComercialLog;
use Backend\dto\CuentaCobro;
use Backend\dto\CuentaContable;
use Backend\dto\CupoLog;
use Backend\dto\Descarga;
use Backend\dto\DocumentoUsuario;
use Backend\dto\Egreso;
use Backend\dto\Empleado;
use Backend\dto\FlujoCaja;
use Backend\dto\Flujocajafact;
use Backend\dto\Ingreso;
use Backend\dto\IntApuesta;
use Backend\dto\IntApuestaDetalle;
use Backend\dto\IntCompetencia;
use Backend\dto\IntDeporte;
use Backend\dto\IntEquipo;
use Backend\dto\IntEvento;
use Backend\dto\IntEventoApuesta;
use Backend\dto\IntEventoApuestaDetalle;
use Backend\dto\IntEventoDetalle;
use Backend\dto\IntRegion;
use Backend\dto\ItTicketEnc;
use Backend\dto\LenguajeMandante;
use Backend\dto\Mandante;
use Backend\dto\MandanteDetalle;
use Backend\dto\Pais;
use Backend\dto\PaisMandante;
use Backend\dto\Perfil;
use Backend\dto\PerfilSubmenu;
use Backend\dto\ProdMandanteTipo;
use Backend\dto\Producto;
use Backend\dto\ProductoComision;
use Backend\dto\ProductoInterno;
use Backend\dto\ProductoTercero;
use Backend\dto\ProductoterceroUsuario;
use Backend\dto\PromocionalLog;
use Backend\dto\Proveedor;
use Backend\dto\ProveedorMandante;
use Backend\dto\ProveedorTercero;
use Backend\dto\PuntoVenta;
use Backend\dto\ProductoMandante;
use Backend\dto\Registro;
use Backend\dto\SaldoUsuonlineAjuste;
use Backend\dto\Submenu;
use Backend\dto\TransaccionApi;
use Backend\dto\TransaccionApiMandante;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransaccionSportsbook;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransprodLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioAlerta;
use Backend\dto\UsuarioBanco;
use Backend\dto\UsuarioBloqueado;
use Backend\dto\UsuarioBono;
use Backend\dto\UsuarioCierrecaja;
use Backend\dto\UsuarioComision;
use Backend\dto\UsuarioConfig;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioLog;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioMensaje;
use Backend\dto\UsuarioNotas;
use Backend\dto\UsuarioPerfil;
use Backend\dto\UsuarioPremiomax;
use Backend\dto\UsuarioPublicidad;
use Backend\dto\UsuarioRecarga;
use Backend\dto\UsuarioRecargaResumen;
use Backend\dto\UsuarioRetiroResumen;
use Backend\dto\UsuarioSaldo;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioTokenInterno;
use Backend\dto\UsucomisionResumen;
use Backend\dto\UsumarketingResumen;
use Backend\imports\Google\GoogleAuthenticator;
use Backend\integrations\mensajeria\Okroute;
use Backend\integrations\payout\LPGSERVICES;
use Backend\mysql\AreaMySqlDAO;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\mysql\BonoLogMySqlDAO;
use Backend\mysql\CargoMySqlDAO;
use Backend\mysql\CategoriaMySqlDAO;
use Backend\mysql\CategoriaProductoMySqlDAO;
use Backend\mysql\CentroCostoMySqlDAO;
use Backend\mysql\ClasificadorMySqlDAO;
use Backend\mysql\CodigoPromocionalMySqlDAO;
use Backend\mysql\CompetenciaPuntosMySqlDAO;
use Backend\mysql\ConceptoMySqlDAO;
use Backend\mysql\ConcesionarioMySqlDAO;
use Backend\mysql\ConsecutivoMySqlDAO;
use Backend\mysql\ContactoComercialLogMySqlDAO;
use Backend\mysql\ContactoComercialMySqlDAO;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\CuentaContableMySqlDAO;
use Backend\mysql\CupoLogMySqlDAO;
use Backend\mysql\DocumentoUsuarioMySqlDAO;
use Backend\mysql\EgresoMySqlDAO;
use Backend\mysql\EmpleadoMySqlDAO;
use Backend\mysql\FlujoCajaMySqlDAO;
use Backend\mysql\IngresoMySqlDAO;
use Backend\mysql\IntApuestaDetalleMySqlDAO;
use Backend\mysql\IntApuestaMySqlDAO;
use Backend\mysql\IntCompetenciaMySqlDAO;
use Backend\mysql\IntDeporteMySqlDAO;
use Backend\mysql\IntEventoApuestaDetalleMySqlDAO;
use Backend\mysql\IntEventoApuestaMySqlDAO;
use Backend\mysql\IntEventoDetalleMySqlDAO;
use Backend\mysql\IntEventoMySqlDAO;
use Backend\mysql\IntRegionMySqlDAO;
use Backend\mysql\LenguajeMandanteMySqlDAO;
use Backend\mysql\MandanteDetalleMySqlDAO;
use Backend\mysql\MandanteMySqlDAO;
use Backend\mysql\PerfilSubmenuMySqlDAO;
use Backend\mysql\ProdMandanteTipoMySqlDAO;
use Backend\mysql\ProductoComisionMySqlDAO;
use Backend\mysql\ProductoTerceroMySqlDAO;
use Backend\mysql\ProductoterceroUsuarioMySqlDAO;
use Backend\mysql\ProveedorMandanteMySqlDAO;
use Backend\mysql\ProveedorMySqlDAO;
use Backend\mysql\ProveedorTerceroMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\RegistroMySqlDAO;
use Backend\mysql\SaldoUsuonlineAjusteMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\mysql\UsuarioAlertaMySqlDAO;
use Backend\mysql\UsuarioBancoMySqlDAO;
use Backend\mysql\UsuarioBloqueadoMySqlDAO;
use Backend\mysql\UsuarioBonoMySqlDAO;
use Backend\mysql\UsuarioCierrecajaMySqlDAO;
use Backend\mysql\UsuarioConfigMySqlDAO;
use Backend\mysql\UsuarioConfiguracionMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\mysql\UsuarioMandanteMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\ProductoMySqlDAO;
use Backend\mysql\ProductoMandanteMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioNotasMySqlDAO;
use Backend\mysql\UsuarioPerfilMySqlDAO;
use Backend\mysql\UsuarioPremiomaxMySqlDAO;
use Backend\mysql\UsuarioPublicidadMySqlDAO;
use Backend\mysql\UsuarioRecargaMySqlDAO;
use Backend\mysql\UsuarioTokenInternoMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\mysql\UsucomisionResumenMySqlDAO;
use Backend\websocket\WebsocketUsuario;

/**
 * Accounting/SaveProductThirdByBetshop
 *
 * Guardar un producto tercero a un punto de venta
 *
 * @param object $params Objeto que contiene los parámetros necesarios para la operación
 * @param int $params ->Id Identificador del producto tercero
 * @param int $params ->State Estado del producto tercero (1 para activo, 0 para inactivo)
 * @param int $params ->Account Identificador de la cuenta contable
 * @param int $params ->AccountExpenses Identificador de la cuenta de gastos
 * @param int $params ->BetShopId Identificador del punto de venta
 * @param int $params ->ProductId Identificador del producto
 *
 * @return array $response Arreglo con la respuesta de la operación
 * - bool $response["HasError"] Indica si hubo un error en la operación
 * - string $response["AlertType"] Tipo de alerta generada
 * - string $response["AlertMessage"] Mensaje de alerta generado
 * - array $response["ModelErrors"] Errores del modelo si los hay
 * - string $response["Pdf"] PDF generado en base64
 * - string $response["PdfPOS"] PDF POS generado en base64
 */


/* extrae valores de parámetros para su uso posterior en un programa. */
$Id = $params->Id;
$State = $params->State;
$Account = $params->Account;
$AccountExpenses = $params->AccountExpenses;

$BetShopId = $params->BetShopId;

/* Asignación de un identificador de producto desde un objeto de parámetros. */
$ProductId = $params->ProductId;

if ($Id != "" && $Id != null) {


    /* Se asigna un estado 'A' o 'I' a un producto basado en su estado. */
    $ProductoterceroUsuario = new ProductoterceroUsuario($Id);

    if ($State != "" && $State != null) {
        if ($State == 1) {
            $ProductoterceroUsuario->setEstado('A');
        }
        if ($State == 0) {
            $ProductoterceroUsuario->setEstado('I');
        }
    }


    /* Se asignan IDs a cuentas contables si no están vacías o nulas. */
    if ($Account != "" && $Account != null) {
        $ProductoterceroUsuario->setCuentacontableId($Account);
    }


    if ($AccountExpenses != "" && $AccountExpenses != null) {
        $ProductoterceroUsuario->setCuentacontableegresoId($AccountExpenses);
    }

    /* Actualiza un producto de usuario en MySQL y obtiene la transacción correspondiente. */
    $ProductoterceroUsuarioMySqlDAO = new ProductoterceroUsuarioMySqlDAO();
    $ProductoterceroUsuarioMySqlDAO->update($ProductoterceroUsuario);
    $ProductoterceroUsuarioMySqlDAO->getTransaction()->commit();


} else {


    /* Creación y configuración de un objeto ProductoterceroUsuario con identificadores específicos. */
    $ProductoterceroUsuario = new ProductoterceroUsuario();

    $ProductoterceroUsuario->setProductoId($ProductId);
    $ProductoterceroUsuario->setUsuarioId($BetShopId);
    $ProductoterceroUsuario->setUsucreaId(0);
    $ProductoterceroUsuario->setUsumodifId(0);

    /* establece el estado y el cupo de un objeto según condiciones específicas. */
    $ProductoterceroUsuario->setEstado('I');
    $ProductoterceroUsuario->setCupo(0);

    if ($State == 1) {
        $ProductoterceroUsuario->setEstado('A');
    }

    /* asigna 0 a las variables si están vacías. */
    if ($Account == "") {
        $Account = 0;
    }
    if ($AccountExpenses == "") {
        $AccountExpenses = 0;
    }

    /* establece IDs contables y guarda datos en una base de datos MySQL. */
    $ProductoterceroUsuario->setCuentacontableId($Account);
    $ProductoterceroUsuario->setCuentacontableegresoId($AccountExpenses);

    $ProductoterceroUsuarioMySqlDAO = new ProductoterceroUsuarioMySqlDAO();
    $ProductoterceroUsuarioMySqlDAO->insert($ProductoterceroUsuario);
    $ProductoterceroUsuarioMySqlDAO->getTransaction()->commit();


}


/* inicializa un array de respuesta indicando éxito y sin errores. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];

