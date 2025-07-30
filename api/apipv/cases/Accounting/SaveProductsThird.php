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
 * Accounting/SaveProductsThird
 *
 * Guardar un producto tercero
 *
 * @param object $params Objeto que contiene los parámetros de entrada:
 * @param int $Id : Identificador del producto.
 * @param string $Name : Nombre del producto.
 * @param int $Provider : Proveedor del producto.
 * @param int $Account : Cuenta contable del producto.
 * @param int $AccountExpenses : Cuenta de gastos del producto.
 * @param int $State : Estado del producto.
 * @param int $InternalProducts : Indica si es un producto interno.
 * @param int $TypeInternal : Tipo de producto interno.
 * @param int $ProductHasQuota : Indica si el producto tiene cupo.
 * @param int $AreWeAgents : Indica si somos agentes.
 *
 * @return array $response Respuesta del proceso:
 *                         - bool $HasError: Indica si hubo un error.
 *                         - string $AlertType: Tipo de alerta.
 *                         - string $AlertMessage: Mensaje de alerta.
 *                         - array $ModelErrors: Errores del modelo.
 *
 * @throws Exception Si ocurre un error durante la transacción.
 */


/* asigna valores de un objeto a varias variables. */
$Id = $params->Id;

$Name = $params->Name;
$Provider = $params->Provider;
$Account = $params->Account;
$AccountExpenses = $params->AccountExpenses;

/* asigna valores a variables basadas en parámetros de entrada. */
$State = $params->State;
$InternalProducts = ($params->InternalProducts == 2) ? 'N' : 'S';
$TypeInternal = ($params->TypeInternal == "" || intval($params->TypeInternal) > 10000) ? 0 : $params->TypeInternal;

$ProductHasQuota = $params->ProductHasQuota;
$AreWeAgents = $params->AreWeAgents;

if ($Id != '') {

    /* Se crea un objeto con atributos y condiciones para un producto tercero. */
    $ProductoTercero = new ProductoTercero($Id);
    $ProductoTercero->setEstado($State);
    $ProductoTercero->setDescripcion($Name);
    $ProductoTercero->setCuentacontableId($Account);
    $ProductoTercero->setCuentacontableegresoId($AccountExpenses);

    if ($ProductHasQuota == '1') {
        $ProductoTercero->setTieneCupo('S');
    } else {
        /* Asigna 'N' a TieneCupo si no se cumple la condición previa. */

        $ProductoTercero->setTieneCupo('N');
    }


    /* Asignar tipo de agente basado en condición y crear DAO para ProductoTercero. */
    if ($AreWeAgents == '1') {
        $ProductoTercero->setTipoAgente('A');
    } else {
        $ProductoTercero->setTipoAgente('N');
    }

    $ProductoTerceroMySqlDAO = new ProductoTerceroMySqlDAO();

    /* Actualiza un objeto en MySQL y obtiene la transacción correspondiente. */
    $ProductoTerceroMySqlDAO->update($ProductoTercero);
    $ProductoTerceroMySqlDAO->getTransaction()->commit();


} else {

    /* Se crea un objeto "ProductoTercero" y se establecen sus propiedades. */
    $ProductoTercero = new ProductoTercero();
    $ProductoTercero->setEstado($State);
    $ProductoTercero->setDescripcion($Name);
    $ProductoTercero->setProveedortercId($Provider);
    $ProductoTercero->setCuentacontableId($Account);
    $ProductoTercero->setCuentacontableegresoId($AccountExpenses);

    /* Configura propiedades de un objeto ProductoTercero según condiciones específicas. */
    $ProductoTercero->setUsucreaId(0);
    $ProductoTercero->setUsumodifId(0);
    $ProductoTercero->setInterno($InternalProducts);
    $ProductoTercero->setTipoId($TypeInternal);

    if ($ProductHasQuota == '1') {
        $ProductoTercero->setTieneCupo('S');
    } else {
        /* Establece que el producto no tiene cupo asignado ('N') en caso contrario. */

        $ProductoTercero->setTieneCupo('N');
    }


    /* asigna un tipo de agente basado en una condición booleana. */
    if ($AreWeAgents == '1') {
        $ProductoTercero->setTipoAgente('A');
    } else {
        $ProductoTercero->setTipoAgente('N');
    }

    $ProductoTerceroMySqlDAO = new ProductoTerceroMySqlDAO();

    /* Inserta un producto en MySQL y obtiene la transacción activa. */
    $ProductoTerceroMySqlDAO->insert($ProductoTercero);
    $ProductoTerceroMySqlDAO->getTransaction()->commit();

}


/* Código que configura una respuesta exitosa sin errores en un sistema. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];